---
description: Testing standards, best practices, and conventions for PHPUnit tests
name: Testing Standards
applyTo: "tests/**/*.php"
---

# PHPUnit Testing Standards

**Applies to**: `tests/**/*.php`  
**Last Updated**: January 13, 2026

---

## 📚 Detailed Guide

For complete testing templates and workflows, see:
- **Agent Skill**: `.github/skills/phpunit-testing/SKILL.md`

---

## 🚨 CRITICAL: Widget Testing Convention

**Elementor widgets have Integration tests ONLY. Never create Unit tests for widgets.**

```
tests/
├── Unit/                    # Pure PHP logic, no WordPress
│   └── Elementor/           # ❌ Do NOT create widget tests here
│       └── WidgetNameTest.php  # ❌ WRONG
│
└── Integration/             # WordPress environment required
    └── Elementor/
        ├── AllWidgetsTest.php           # ✅ Registry for all widgets
        └── WidgetNameWidgetTest.php     # ✅ CORRECT location
```

**Why Integration only?**
- Widgets require Elementor loaded (`did_action('elementor/loaded')`)
- Widgets extend `\Elementor\Widget_Base` (needs Elementor classes)
- Controls registration needs Elementor infrastructure

---

## ✅ Must-Follow Rules

### 1. Method Names Are camelCase

```php
// ✅ CORRECT
public function setUp(): void
public function tearDown(): void

// ❌ WRONG - WordPress style
public function set_up(): void
public function tear_down(): void
```

### 2. Skip When Dependencies Missing

```php
public function setUp(): void {
    parent::setUp();
    
    if ( ! did_action( 'elementor/loaded' ) ) {
        $this->markTestSkipped( 'Elementor not loaded' );
        return; // IMPORTANT: always return after skip
    }
    
    $this->widget = new MyWidget();
}
```

### 3. Always Clean Up Resources

```php
public function tearDown(): void {
    $this->widget = null;
    parent::tearDown();
}
```

### 4. Use PHPDoc Group Annotations

```php
/**
 * @group integration
 * @group elementor
 * @group widgets
 */
class MyWidgetTest extends WP_UnitTestCase {
```

---

## 📋 Widget Test Checklist

Every widget test MUST verify:

- [ ] Widget name matches pattern `soma-{name}`
- [ ] Widget has non-empty title
- [ ] Widget has non-empty icon
- [ ] Widget includes `'soma'` in categories
- [ ] Widget has style dependencies
- [ ] Widget registered in `AllWidgetsTest.php`

---

## 🗂️ Test Organization

```
tests/
├── bootstrap.php            # Test setup
├── Unit/                    # No WordPress required
│   ├── PostTypes/           # PostType class tests
│   ├── Taxonomies/          # Taxonomy class tests
│   └── Utils/               # Helper function tests
│
└── Integration/             # WordPress required
    ├── PostTypesTest.php    # CPT registration
    ├── TaxonomiesTest.php   # Taxonomy registration
    ├── API/                 # REST endpoint tests
    ├── Elementor/           # Widget tests (ALL widgets here)
    └── PageBuilder/         # Block tests
```

---

## ✅ Quality Gates

```bash
composer test       # All tests must pass
composer phpcs      # 0 errors
composer phpstan    # Level 6+
```

---

## 🔗 Related Documentation

- **Testing Skill**: `.github/skills/phpunit-testing/SKILL.md`
- **Testing Guide**: `docs/TESTING_GUIDE.md`
- **Widget Docs**: `docs/WIDGETS.md`
