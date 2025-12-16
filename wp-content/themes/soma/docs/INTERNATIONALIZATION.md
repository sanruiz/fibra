# SOMA Theme - Internationalization (i18n) Guide

**Version**: 3.0.0  
**Last Updated**: December 16, 2025  
**Text Domain**: `soma`

---

## Table of Contents

1. [Overview](#overview)
2. [WordPress i18n Functions](#wordpress-i18n-functions)
3. [Translation Workflow](#translation-workflow)
4. [Helper Function for ACF Fields](#helper-function-for-acf-fields)
5. [Creating Translation Files](#creating-translation-files)
6. [Adding New Languages](#adding-new-languages)
7. [Best Practices](#best-practices)
8. [Troubleshooting](#troubleshooting)

---

## Overview

The SOMA theme uses WordPress's standard internationalization system with:

- **Text Domain**: `soma` (configured in `style.css`)
- **Domain Path**: `/languages`
- **Default Language**: English (en_US)
- **Available Translations**: Spanish (es_ES)
- **Translation Functions**: `__()`, `_e()`, `esc_html__()`, `esc_html_e()`, `esc_attr__()`
- **Custom Helper**: `soma_get_i18n_field()` for ACF field internationalization

### Files Structure

```
wp-content/themes/soma/
├── languages/
│   ├── soma.pot              # Translation template (auto-generated)
│   ├── es_ES.po              # Spanish translations (human-editable)
│   └── es_ES.mo              # Spanish compiled binary
├── includes/Utils/Helpers.php  # Contains soma_get_i18n_field()
└── functions.php              # Loads text domain
```

---

## WordPress i18n Functions

### Core Functions

| Function | Usage | When to Use |
|----------|-------|-------------|
| `__()` | Get translated string | Variable assignment |
| `_e()` | Echo translated string | Direct output |
| `_x()` | Translate with context | Ambiguous strings |
| `_n()` | Plural translation | Singular/plural forms |

**Examples:**

```php
// Get string for variable
$text = __( 'Hello World', 'soma' );

// Echo string directly
_e( 'Hello World', 'soma' );

// Translate with context
$post_noun = _x( 'Post', 'noun', 'soma' );
$post_verb = _x( 'Post', 'verb', 'soma' );

// Plurals
printf(
    _n( '%s item', '%s items', $count, 'soma' ),
    number_format_i18n( $count )
);
```

### Escaped Functions (Recommended)

**Always use escaped functions for output** to prevent XSS vulnerabilities:

| Function | Usage | When to Use |
|----------|-------|-------------|
| `esc_html__()` | Get + escape HTML | HTML content in variables |
| `esc_html_e()` | Echo + escape HTML | Direct HTML output |
| `esc_attr__()` | Get + escape attribute | HTML attributes in variables |
| `esc_attr_e()` | Echo + escape attribute | Direct attribute output |

**Examples:**

```php
// HTML content
echo '<h1>' . esc_html__( 'Welcome', 'soma' ) . '</h1>';
<h2><?php esc_html_e( 'Our Services', 'soma' ); ?></h2>

// HTML attributes
<input type="text" placeholder="<?php echo esc_attr__( 'Search', 'soma' ); ?>">
<a title="<?php esc_attr_e( 'Click here', 'soma' ); ?>">Link</a>
```

### Standard Pattern

```php
// ✅ CORRECT: WordPress i18n with text domain
<?php esc_html_e( 'See All', 'soma' ); ?>

// ❌ INCORRECT: Hardcoded conditional
<?php echo ( wpm_get_language() === 'en' ) ? 'See All' : 'Ver Todos'; ?>

// ❌ INCORRECT: Missing text domain
<?php esc_html_e( 'See All' ); ?>

// ❌ INCORRECT: No escaping
<?php echo __( 'See All', 'soma' ); ?>
```

---

## Translation Workflow

### For Developers

#### 1. Add Translatable Strings to Code

```php
// In templates/partials
<h2><?php esc_html_e( 'Latest News', 'soma' ); ?></h2>
<button><?php echo esc_html__( 'Load More', 'soma' ); ?></button>
<input placeholder="<?php echo esc_attr__( 'Enter your name', 'soma' ); ?>">
```

#### 2. Extract Strings to .pot Template

After adding new translatable strings, regenerate the `.pot` file:

```bash
# From theme root directory
wp i18n make-pot . languages/soma.pot --domain=soma
```

**What this does:**
- Scans all PHP files for translation functions
- Extracts strings with `soma` text domain
- Updates `languages/soma.pot` template
- Preserves existing translations

**Output:**
```
Success: POT file successfully generated.
```

#### 3. Update Translation Files

**Option A: Using Poedit (Recommended)**

1. Download [Poedit](https://poedit.net/) (free)
2. Open `languages/es_ES.po`
3. Click **Update from POT file** → Select `soma.pot`
4. Translate new/updated strings
5. Save (auto-generates `es_ES.mo`)

**Option B: Using LocoTranslate Plugin**

1. Install [LocoTranslate](https://wordpress.org/plugins/loco-translate/)
2. Go to **Loco Translate → Themes → SOMA**
3. Edit Spanish translation
4. Add translations for new strings
5. Save (auto-compiles)

**Option C: WP-CLI**

```bash
# Update .po from .pot
wp i18n update-po languages/soma.pot languages/es_ES.po

# Compile .mo file
wp i18n make-mo languages/
```

#### 4. Test Translations

```bash
# Clear WordPress cache
wp cache flush

# Switch language and test
# Visit pages in both English and Spanish
```

### For Translators

#### Manual Translation (.po file)

**File structure:**
```po
msgid "See All"
msgstr "Ver Todos"

msgid "Search"
msgstr "Buscar"
```

**Header configuration:**
```po
"Language: es_ES\n"
"Plural-Forms: nplurals=2; plural=(n != 1);\n"
```

**Compile to binary:**
```bash
msgfmt es_ES.po -o es_ES.mo
```

---

## Helper Function for ACF Fields

### soma_get_i18n_field()

For ACF fields with language-specific versions (e.g., `file` and `file_es`), use this helper function instead of conditionals.

**Location**: `includes/Utils/Helpers.php`

**Function Signature:**
```php
function soma_get_i18n_field( array $data, string $field_name ): mixed
```

### How It Works

1. Checks if WP Multilang plugin is active
2. Gets current language via `wpm_get_language()`
3. If Spanish (`es`), looks for `{$field_name}_es`
4. Returns localized field if exists
5. Falls back to base field if not

### Usage Examples

#### File Fields

```php
// ❌ OLD: Conditional pattern
$file = ( wpm_get_language() === 'en' ) ? $content['file'] : $content['file_es'];

// ✅ NEW: Helper function
$file = soma_get_i18n_field( $content, 'file' );

if ( $file ) {
    echo '<a href="' . esc_url( $file['url'] ) . '">';
    echo esc_html( $file['title'] );
    echo '</a>';
}
```

#### Event Arrays

```php
// ❌ OLD: Duplicate conditionals
$events = ( wpm_get_language() === 'en' ) 
    ? get_query_var( 'soma_block_content' )['events'] 
    : get_query_var( 'soma_block_content' )['events_es'];

// ✅ NEW: Single helper call
$events = soma_get_i18n_field( get_query_var( 'soma_block_content' ), 'events' );

foreach ( $events as $event ) {
    // Process events
}
```

#### Any ACF Field

```php
// Works with any ACF field that has an _es variant
$description = soma_get_i18n_field( $content, 'description' );
$document = soma_get_i18n_field( $content, 'document' );
$custom_field = soma_get_i18n_field( $data, 'custom_field' );
```

### ACF Field Structure

**In ACF field group:**
- Base field: `file` (File upload)
- Spanish field: `file_es` (File upload)

**In template:**
```php
<?php
$content = get_query_var( 'soma_block_content' );
$file = soma_get_i18n_field( $content, 'file' );
?>
```

### Benefits

- ✅ Eliminates duplicate EN/ES conditionals
- ✅ Single source of truth
- ✅ Graceful fallback if plugin not active
- ✅ Consistent pattern across theme
- ✅ Easier to maintain and extend

---

## Creating Translation Files

### Initial Setup

#### 1. Generate .pot Template

```bash
cd wp-content/themes/soma
wp i18n make-pot . languages/soma.pot --domain=soma
```

**Expected output:**
```
Success: POT file successfully generated.
```

#### 2. Create Spanish Translation

**Option A: From .pot file (Poedit)**

1. Open Poedit
2. **File → New from POT file**
3. Select `languages/soma.pot`
4. Choose language: **Spanish (es_ES)**
5. Translate strings
6. Save as `languages/es_ES.po`

**Option B: Manual creation**

```bash
# Copy template
cp languages/soma.pot languages/es_ES.po

# Edit header in es_ES.po
nano languages/es_ES.po
```

**Update header:**
```po
"Language: es_ES\n"
"Plural-Forms: nplurals=2; plural=(n != 1);\n"
```

**Translate strings:**
```po
msgid "See All"
msgstr "Ver Todos"

msgid "Search"
msgstr "Buscar"

msgid "Filter by Year"
msgstr "Filtrar por año"
```

#### 3. Compile .mo File

**Automatic (Poedit):**
- Save → auto-generates `.mo`

**Manual (WP-CLI):**
```bash
wp i18n make-mo languages/
```

**Manual (msgfmt):**
```bash
msgfmt languages/es_ES.po -o languages/es_ES.mo
```

#### 4. Verify Files

```bash
ls -lh languages/
# Expected:
# -rw-r--r-- soma.pot  (17K) - Template
# -rw-r--r-- es_ES.po  (2.3K) - Source
# -rw-r--r-- es_ES.mo  (1.0K) - Compiled
```

### Updating Existing Translations

```bash
# 1. Regenerate .pot after code changes
wp i18n make-pot . languages/soma.pot --domain=soma

# 2. Update .po from .pot (merges new strings)
wp i18n update-po languages/soma.pot languages/es_ES.po

# 3. Translate new strings in Poedit or manually

# 4. Recompile .mo
wp i18n make-mo languages/
```

---

## Adding New Languages

### Example: Adding Portuguese (pt_BR)

#### Step 1: Create Translation File

**From Poedit:**
1. **File → New from POT file**
2. Select `languages/soma.pot`
3. Choose **Portuguese (Brazil)**
4. Save as `languages/pt_BR.po`

**Manual:**
```bash
cp languages/soma.pot languages/pt_BR.po
```

#### Step 2: Configure Header

Edit `pt_BR.po`:
```po
"Language: pt_BR\n"
"Plural-Forms: nplurals=2; plural=(n > 1);\n"
```

#### Step 3: Translate

Add Portuguese translations:
```po
msgid "See All"
msgstr "Ver Todos"

msgid "Search"
msgstr "Buscar"
```

#### Step 4: Compile

```bash
wp i18n make-mo languages/
# Or
msgfmt pt_BR.po -o pt_BR.mo
```

#### Step 5: Configure WordPress

1. Install WP Multilang (or similar plugin)
2. Add Portuguese to available languages
3. Test language switching

---

## Best Practices

### DO ✅

**Use proper escaping:**
```php
<?php esc_html_e( 'Title', 'soma' ); ?>
<?php echo esc_html__( 'Text', 'soma' ); ?>
<?php echo esc_attr__( 'Attribute', 'soma' ); ?>
```

**Always include text domain:**
```php
__( 'Text', 'soma' )  // ✅ Correct
```

**Use helper for ACF fields:**
```php
$file = soma_get_i18n_field( $content, 'file' );  // ✅ Clean
```

**Add context for ambiguous strings:**
```php
_x( 'Post', 'noun', 'soma' )  // Publishing post
_x( 'Post', 'verb', 'soma' )  // To post content
```

### DON'T ❌

**Missing text domain:**
```php
__( 'Text' )  // ❌ Won't be extracted
```

**Hardcoded translations:**
```php
echo ( $lang === 'en' ) ? 'Text' : 'Texto';  // ❌ Anti-pattern
```

**No escaping:**
```php
echo __( 'Text', 'soma' );  // ❌ XSS risk
```

**Concatenation:**
```php
__( 'Hello ' . $name, 'soma' );  // ❌ Can't translate
```

**Variables in strings:**
```php
__( "Welcome $name", 'soma' );  // ❌ Use printf instead
```

### Correct Patterns

**Dynamic content:**
```php
printf(
    /* translators: %s: user name */
    esc_html__( 'Welcome %s', 'soma' ),
    esc_html( $name )
);
```

**Plurals:**
```php
printf(
    esc_html( _n( '%s item', '%s items', $count, 'soma' ) ),
    number_format_i18n( $count )
);
```

**Links in text:**
```php
printf(
    /* translators: %s: link to documentation */
    wp_kses_post( __( 'Read the <a href="%s">documentation</a>', 'soma' ) ),
    esc_url( $doc_url )
);
```

---

## Troubleshooting

### Translations Not Showing

**Symptoms:** Strings display in English even after translating

**Solutions:**

1. **Clear all caches:**
   ```bash
   wp cache flush
   # Clear Redis/Memcached if using
   ```

2. **Verify .mo file exists:**
   ```bash
   ls -lh languages/es_ES.mo
   # Should show recent timestamp
   ```

3. **Check text domain matches:**
   ```php
   // In code
   esc_html_e( 'Text', 'soma' );  // Must be 'soma'
   ```

4. **Verify textdomain loads:**
   ```php
   // In functions.php - should exist
   add_action( 'after_setup_theme', 'soma_load_textdomain' );
   ```

5. **Check language setting:**
   - WP Multilang plugin active?
   - Language switched correctly?

### .pot Not Updating

**Solutions:**

1. **Ensure using translation functions:**
   ```php
   esc_html_e( 'Text', 'soma' );  // ✅ Will extract
   echo 'Text';                    // ❌ Won't extract
   ```

2. **Regenerate manually:**
   ```bash
   wp i18n make-pot . languages/soma.pot --domain=soma --force
   ```

3. **Check file permissions:**
   ```bash
   chmod 644 languages/soma.pot
   ```

### .mo File Not Compiling

**Solutions:**

1. **Use Poedit** (auto-compiles on save)

2. **Check .po syntax:**
   ```bash
   msgfmt -c es_ES.po
   # Fix any reported errors
   ```

3. **Manual compile:**
   ```bash
   msgfmt es_ES.po -o es_ES.mo
   ```

### Missing Strings in Translation

**Symptoms:** Some strings don't appear in .pot file

**Solutions:**

1. **Verify text domain:**
   ```php
   __( 'Text', 'soma' )  // Must be 'soma'
   ```

2. **Check function usage:**
   ```php
   // ✅ Standard functions
   __(), _e(), esc_html__(), esc_html_e()
   
   // ❌ Non-standard won't extract
   custom_translate( 'Text' )
   ```

3. **Regenerate .pot:**
   ```bash
   wp i18n make-pot . languages/soma.pot --domain=soma
   ```

### Helper Function Not Working

**Symptoms:** `soma_get_i18n_field()` returns wrong field

**Solutions:**

1. **Check WP Multilang active:**
   ```php
   if ( function_exists( 'wpm_get_language' ) ) {
       // Plugin active
   }
   ```

2. **Verify field structure:**
   ```php
   // ACF fields should be:
   // 'file' => [...],
   // 'file_es' => [...],
   ```

3. **Check current language:**
   ```php
   $lang = wpm_get_language();  // Should return 'en' or 'es'
   ```

4. **Test fallback:**
   ```php
   // Should return base field if _es doesn't exist
   $file = soma_get_i18n_field( $content, 'file' );
   ```

---

## Validation

### Check Translation Coverage

```bash
# Count total strings
grep -c "^msgid" languages/soma.pot

# Count translated strings
grep -c "^msgstr" languages/es_ES.po

# Find untranslated (empty msgstr)
grep -A1 "^msgid" languages/es_ES.po | grep "^msgstr \"\"$"

# Check for fuzzy translations
grep -i "fuzzy" languages/es_ES.po
```

### Validate .po File

```bash
# Check syntax
msgfmt -c es_ES.po

# Test compile
msgfmt es_ES.po -o test.mo && echo "Valid" && rm test.mo
```

### PHPCS Validation

```bash
# Check translation function usage
vendor/bin/phpcs --standard=WordPress \
  --sniffs=WordPress.WP.I18n \
  --extensions=php \
  partials/ includes/
```

---

## Resources

### Tools

- **[WP-CLI i18n](https://developer.wordpress.org/cli/commands/i18n/)** - Command-line tools
- **[Poedit](https://poedit.net/)** - Translation editor (free)
- **[LocoTranslate](https://wordpress.org/plugins/loco-translate/)** - WordPress plugin

### Documentation

- [WordPress i18n Handbook](https://developer.wordpress.org/apis/internationalization/)
- [Theme i18n](https://developer.wordpress.org/themes/functionality/internationalization/)
- [Translation Functions](https://developer.wordpress.org/apis/internationalization/internationalization-functions/)

### SOMA Documentation

- [Helper Functions API](HELPERS.md) - `soma_get_i18n_field()` reference
- [Development Guide](DEVELOPMENT.md) - Theme development overview
- [CHANGELOG.md](../CHANGELOG.md) - i18n system history

---

## Quick Reference

### Common Translation Patterns

```php
// Simple text
<?php esc_html_e( 'Hello', 'soma' ); ?>

// Variable
$text = esc_html__( 'Hello', 'soma' );

// Attribute
placeholder="<?php echo esc_attr__( 'Search', 'soma' ); ?>"

// Dynamic content
printf( esc_html__( 'Hello %s', 'soma' ), esc_html( $name ) );

// Plurals
_n( '%s item', '%s items', $count, 'soma' )

// Context
_x( 'Post', 'noun', 'soma' )

// ACF field
$file = soma_get_i18n_field( $content, 'file' );
```

### Essential Commands

```bash
# Generate/update .pot
wp i18n make-pot . languages/soma.pot --domain=soma

# Update .po from .pot
wp i18n update-po languages/soma.pot languages/es_ES.po

# Compile .mo
wp i18n make-mo languages/

# Validate .po
msgfmt -c languages/es_ES.po

# Clear cache
wp cache flush
```

---

**Document Version**: 1.0  
**Last Updated**: December 16, 2025  
**Maintainer**: Miguel Colmenares
