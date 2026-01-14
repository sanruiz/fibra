---
description: Coding standards and conventions for Elementor widgets in SOMA theme
name: Elementor Widgets Standards
applyTo: "**/Elementor/Widgets/**/*.php"
---

# Elementor Widgets Standards

**Applies to**: `includes/Elementor/Widgets/**/*.php`  
**Last Updated**: January 13, 2026

---

## 📚 Detailed Workflow

For complete step-by-step widget creation, see:
- **Agent Skill**: `.github/skills/elementor-widget-creation/SKILL.md`

---

## ⚠️ Critical Rules

### 1. Always Use i18n Functions

```php
// ✅ CORRECT
'label' => esc_html__( 'Label Text', 'soma' )
'default' => esc_html__( 'Default', 'soma' )

// ❌ WRONG - Not translatable
'label' => 'Label Text'
```

### 2. Text Domain is Always `'soma'`

```php
// ✅ CORRECT
__( 'Text', 'soma' )

// ❌ WRONG
__( 'Text', 'theme' )
```

### 3. Post Titles: Use `get_the_title()` (WP-Multilang)

```php
// ✅ CORRECT - Applies WP-Multilang filters
$options[ $post->ID ] = get_the_title( $post->ID );

// ❌ WRONG - Bypasses filters, shows [:en]..[:es]..[:]
$options[ $post->ID ] = $post->post_title;
```

### 4. Use SOMA CSS Variables

```css
/* ✅ CORRECT */
color: var(--soma-color-text-primary);
font-family: var(--soma-font-family-primary);
padding: var(--soma-spacing-md);

/* ❌ WRONG */
color: #333;
font-family: Arial;
padding: 16px;
```

### 5. Use Elementor Global Styles

```php
use Elementor\Core\Kits\Documents\Tabs\Global_Colors;
use Elementor\Core\Kits\Documents\Tabs\Global_Typography;

// Color with global default
$this->add_control(
    'title_color',
    [
        'label'  => esc_html__( 'Color', 'soma' ),
        'type'   => Controls_Manager::COLOR,
        'global' => [ 'default' => Global_Colors::COLOR_PRIMARY ],
    ]
);
```

---

## 📋 Required Files Per Widget

| File | Location |
|------|----------|
| Widget Class | `includes/Elementor/Widgets/{WidgetName}.php` |
| CSS Styles | `assets/css/widgets/{widget-name}.css` |
| Integration Test | `tests/Integration/Elementor/{WidgetName}WidgetTest.php` |

---

## ✅ Quality Gates (Must Pass)

```bash
composer phpcs      # 0 errors
composer phpstan    # Level 6+
composer test       # All tests pass
```

---

## 🔄 Translation Commands (After Adding Strings)

```bash
cd wp-content/themes/soma
wp i18n make-pot . languages/soma.pot --domain=soma --exclude=node_modules,vendor,tests
wp i18n update-po languages/soma.pot languages/
wp i18n make-mo languages/
```

**⚠️ Always translate all new `msgstr ""` entries in `es_ES.po`**

---

## 📝 Documentation Updates

**Always update**: `docs/WIDGETS.md` (never create separate .md files)

---

## 🧪 Widget Testing Convention

**Widgets have Integration tests ONLY** (no unit tests).

See: `.github/instructions/testing.instructions.md`

---

## 🔗 Related Documentation

- **Widget Creation Skill**: `.github/skills/elementor-widget-creation/SKILL.md`
- **Widgets Reference**: `docs/WIDGETS.md`
- **i18n Guide**: `docs/INTERNATIONALIZATION.md`
