---
applyTo: "**/*.php"
---

# PHP Code Quality Standards for SOMA Theme

## Project Context

**WordPress Theme**: SOMA v3.0.0 (PSR-4, PHP 8.1+, WordPress 6.0+)  
**Location**: `wp-content/themes/soma/`  
**Language Policy**: ALL code and comments MUST be in English

## Quality Gates (MANDATORY Before Commit)

**CRITICAL**: ALWAYS run these validations BEFORE committing PHP code:

```bash
cd wp-content/themes/soma

# 1. Auto-fix formatting (REQUIRED FIRST)
./vendor/bin/phpcbf

# 2. Check WordPress Coding Standards (MUST PASS - 0 errors)
./vendor/bin/phpcs

# 3. Static Analysis Level 6+ (MUST PASS - 0 critical errors)
./vendor/bin/phpstan analyse --memory-limit=1G
```

**Zero tolerance**: Code with PHPCS errors or PHPStan critical errors will be rejected by CI/CD.

## WordPress Coding Standards (PHPCS)

### 1. Inline Comments MUST End with Punctuation

```php
// ✅ CORRECT
// This is a proper comment.
the_content();

// Elementor support - required for Elementor editor to work.
if ( $condition ) {
    // Process the data.
    process_data();
}

// ❌ WRONG - Will fail PHPCS
// This is wrong
the_content();

// No period here
process_data();
```

### 2. No Trailing Whitespace

- Configure editor to auto-remove trailing spaces on save
- PHPCBF auto-fixes this violation
- Check: no spaces/tabs at end of lines

### 3. Use Tabs for Indentation (WordPress Standard)

```php
// ✅ CORRECT
if ( $condition ) {
	echo 'Use tabs';
}

// ❌ WRONG
if ( $condition ) {
    echo 'Spaces fail PHPCS';
}
```

### 4. Brace Placement

```php
// ✅ CORRECT - Opening brace on same line
if ( $condition ) {
	do_something();
}

// ❌ WRONG - Brace on new line
if ( $condition )
{
	do_something();
}
```

## Security (CRITICAL)

### Nonce Validation (CSRF Protection)

**ALWAYS validate nonces in AJAX handlers and form submissions.** Nonces protect against CSRF attacks.

**Creating nonces:**
```php
// In PHP (for forms)
wp_nonce_field( 'action_name', 'nonce_field_name' );

// In PHP (for URLs)
$url = wp_nonce_url( $url, 'action_name' );

// In PHP (for JavaScript via wp_localize_script)
wp_localize_script( 'handle', 'myAjax', array(
    'nonce' => wp_create_nonce( 'my_ajax_nonce' ),
    'ajaxurl' => admin_url( 'admin-ajax.php' ),
));
```

**Verifying nonces (REQUIRED in every AJAX handler):**
```php
// For AJAX handlers - use check_ajax_referer()
public function ajax_handler(): void {
    // ✅ ALWAYS verify nonce FIRST.
    check_ajax_referer( 'my_ajax_nonce', 'nonce' );
    
    // Then check capabilities.
    if ( ! current_user_can( 'manage_options' ) ) {
        wp_send_json_error( 'Unauthorized', 403 );
        return;
    }
    
    // Now process the request.
    wp_send_json_success( $data );
}

// For form submissions - use wp_verify_nonce()
if ( ! wp_verify_nonce( $_POST['nonce_field_name'], 'action_name' ) ) {
    wp_die( 'Security check failed' );
}
```

**❌ NEVER process AJAX without nonce verification:**
```php
// ❌ BAD - No nonce check.
public function ajax_handler(): void {
    $data = $_POST['data']; // Vulnerable to CSRF!
}

// ✅ GOOD - Nonce verified first.
public function ajax_handler(): void {
    check_ajax_referer( 'my_nonce', 'nonce' ); // Dies if invalid.
    $data = sanitize_text_field( $_POST['data'] );
}
```

### Input Sanitization

**Always sanitize user input before using or storing:**
```php
$text = sanitize_text_field( $_POST['field'] );
$email = sanitize_email( $_POST['email'] );
$url = esc_url_raw( $_POST['url'] );  // For database storage.
$int = absint( $_POST['number'] );     // Positive integer.
$key = sanitize_key( $_POST['key'] );  // Lowercase alphanumeric.
```

### Output Escaping

**ALL dynamic output MUST be escaped.** PHPCS enforces `WordPress.Security.EscapeOutput`.

#### Escaping Functions Reference

| Function | Use Case | Example |
|----------|----------|---------|
| `esc_html()` | Plain text (NO HTML) | `<h1><?php echo esc_html( $title ); ?></h1>` |
| `esc_url()` | URLs (href, src) | `<a href="<?php echo esc_url( $url ); ?>">` |
| `esc_attr()` | HTML attributes (class, id, style, alt) | `<div class="<?php echo esc_attr( $class ); ?>">` |
| `wp_kses_post()` | Rich HTML content (WYSIWYG) | `<?php echo wp_kses_post( $content ); ?>` |

### Common Patterns in SOMA Theme

```php
// ACF Flexible Content - Get block data via query vars (v3.0+)
$block_content = get_query_var('soma_block_content');
$block_counter = get_query_var('soma_block_counter');

// Plain text fields
<h2><?php echo esc_html( $block_content['title'] ); ?></h2>

// Rich content (WYSIWYG fields)
<div class="description">
    <?php echo wp_kses_post( $block_content['description'] ); ?>
</div>

// Images
<img src="<?php echo esc_url( $block_content['image']['url'] ); ?>" 
     alt="<?php echo esc_attr( $block_content['image']['alt'] ); ?>">

// Links
<a href="<?php echo esc_url( $block_content['link']['url'] ); ?>" 
   target="<?php echo esc_attr( $block_content['link']['target'] ); ?>">
    <?php echo esc_html( $block_content['link']['title'] ); ?>
</a>

// WordPress functions - ALWAYS escape
<h3><?php echo esc_html( get_the_title() ); ?></h3>
<a href="<?php echo esc_url( get_permalink() ); ?>">Read more</a>
```

### CRITICAL: Never Use apply_filters() for Escaping

```php
// ❌ WRONG - PHPCS does NOT recognize this as escaping
echo apply_filters('the_content', $content);

// ✅ CORRECT - Use wp_kses_post() instead
echo wp_kses_post( $content );
```

## SOMA Theme Architecture (PSR-4)

### Directory Structure

```
wp-content/themes/soma/
├── includes/          # PSR-4 classes (Soma\ namespace)
│   ├── Core/         # Theme core, Loader, Enums
│   ├── PostTypes/    # Custom post types
│   ├── Taxonomies/   # Custom taxonomies
│   ├── API/          # REST endpoints
│   ├── Elementor/    # Custom widgets
│   ├── PageBuilder/  # ACF flexible content system
│   ├── CF7/          # Contact Form 7 integration
│   ├── Utils/        # Helpers, Logger, Cache
│   └── Admin/        # Admin customizations
├── partials/         # ACF flexible content blocks (50+ files)
├── templates/        # Custom page templates
├── singles/          # Single post templates
├── page.php          # Main page template
├── single.php        # Single post template
├── functions.php     # Theme initialization
└── vendor/           # Composer dependencies (phpcs, phpstan, phpunit)
```

### Key Conventions

1. **All files MUST start with**:
```php
<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}
```

2. **Partials access block data via WordPress query vars (NO globals)**:
```php
// ✅ CORRECT (v3.0+)
$block_content = get_query_var('soma_block_content');
$block_counter = get_query_var('soma_block_counter');
$block_layout = get_query_var('soma_block_layout');

// ❌ DEPRECATED (v2.x)
global $pageBlock; // Don't use!
```

3. **Elementor + ACF Compatibility**:
All page templates MUST include `the_content()` for Elementor support:
```php
<?php
// Elementor support - required for Elementor editor to work.
the_content();

// ACF Flexible Content - only render if not using Elementor.
if ( ! did_action( 'elementor/loaded' ) && ! \Elementor\Plugin::$instance->preview->is_preview_mode() ) {
	get_template_part( 'page-builder' );
}
?>
```

## Build & Validation Workflow

### Before Making Changes
```bash
cd wp-content/themes/soma

# Install dependencies if needed
composer install
npm install

# Build assets (if modifying CSS/JS)
npm run dev    # Development build
npm run prod   # Production build (minified)
```

### After Making Changes (MANDATORY)

```bash
# Step 1: Auto-fix what can be fixed
./vendor/bin/phpcbf

# Step 2: Check for remaining issues
./vendor/bin/phpcs

# Step 3: If errors remain, fix manually following patterns above

# Step 4: Run static analysis
./vendor/bin/phpstan analyse --memory-limit=1G

# Step 5: Verify specific files pass
./vendor/bin/phpcs path/to/modified/file.php
```

### Common PHPCS Errors & Fixes

| Error | Fix |
|-------|-----|
| `Inline comments must end in full-stops` | Add `.` to end of comment |
| `Whitespace found at end of line` | Remove trailing spaces (phpcbf auto-fixes) |
| `Tabs must be used to indent lines` | Replace spaces with tabs |
| `OutputNotEscaped` | Use esc_html(), esc_url(), esc_attr(), or wp_kses_post() |

### Testing (Optional but Recommended)

```bash
# Run PHPUnit tests (108 tests, 355 assertions)
cd wp-content/themes/soma
vendor/bin/phpunit

# Run specific test
vendor/bin/phpunit --filter=TestName
```

## CI/CD Integration

GitHub Actions will automatically run on push/PR:
- **quality-and-tests.yml**: PHPCS, PHPStan, PHPUnit (runs on every push)
- **release-and-deploy.yml**: Build & deploy (runs on version tags only)

**If CI fails**: Check logs, fix locally, run validations, commit fix.

## Quick Reference

### Development Commands
```bash
# Quality checks (run before commit)
./vendor/bin/phpcbf              # Auto-fix
./vendor/bin/phpcs               # Validate
./vendor/bin/phpstan analyse     # Static analysis

# Asset building
npm run watch                    # Development with hot reload
npm run prod                     # Production build

# Testing
vendor/bin/phpunit               # All tests
vendor/bin/phpunit --filter=X    # Specific test
```

### Helper Functions Available

24+ global `soma_*` functions (see `includes/Utils/Helpers.php`):
- `soma_log_error()`, `soma_log_info()` - PSR-3 logging
- `soma_cache_get()`, `soma_cache_set()` - Tag-based caching
- `soma_get_portfolio_items()` - Query custom post types
- `soma_get_template_part()` - Load templates with args

**Trust these instructions.** Only search for additional information if instructions are incomplete or incorrect.
