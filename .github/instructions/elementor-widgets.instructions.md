---
description: Complete workflow for creating and modifying Elementor widgets in SOMA theme
name: Elementor Widgets Development
applyTo: "**/Elementor/Widgets/**/*.php"
---

# Elementor Widgets Development Instructions

**Applies to**: All files in `includes/Elementor/Widgets/`  
**Last Updated**: January 4, 2026  
**Project**: FibraSOMA Website Development

---

## 🎯 Purpose

This file defines the **mandatory workflow** for creating or modifying Elementor widgets in the SOMA theme. Following this workflow ensures consistency, quality, and proper internationalization.

---

## ⚠️ CRITICAL: Complete Workflow Checklist

**EVERY widget modification MUST follow ALL 10 steps in order:**

### Step 1: Widget Class Modification
**File**: `includes/Elementor/Widgets/{WidgetName}.php`

- Extend `Soma\Elementor\Base\WidgetBase` (or `\Elementor\Widget_Base`)
- Use WordPress i18n functions for ALL user-facing strings:
  ```php
  __( 'Label Text', 'soma' )        // Returns translated string
  esc_html__( 'Label', 'soma' )     // Returns escaped translated string
  esc_attr__( 'Attribute', 'soma' ) // For HTML attributes
  ```

### Step 2: CSS File
**File**: `assets/css/widgets/{widget-name}.css`

- Use SOMA CSS variables (`--soma-*`) for consistency
- Follow responsive design patterns with breakpoints

### Step 3: Register Widget
**File**: `includes/Elementor/Loader.php`

```php
\Elementor\Plugin::instance()->widgets_manager->register(
    new Widgets\{WidgetName}()
);
```

### Step 4: Enqueue CSS
Either in widget's `get_style_depends()` method or in `functions.php`

### Step 5: Create/Update Unit Tests
**File**: `tests/Unit/Elementor/{WidgetName}WidgetTest.php`

- Test class structure with ReflectionClass
- Test required methods exist
- Test return types and visibility

### Step 6: Create/Update Integration Tests
**File**: `tests/Integration/Elementor/{WidgetName}WidgetTest.php`

- Test widget name, title, icon
- Test categories contain 'soma'
- Test style dependencies
- Test new controls exist
- Test rendering output

### Step 7: Update AllWidgetsTest
**File**: `tests/Integration/Elementor/AllWidgetsTest.php`

```php
// Add to $widget_classes array
'{WidgetName}' => \Soma\Elementor\Widgets\{WidgetName}::class,

// Add to $widget_names array
'{WidgetName}' => 'soma-{widget-name}',
```

### Step 8: Run Tests
```bash
cd wp-content/themes/soma
composer test
# OR specific widget tests:
vendor/bin/phpunit tests/Integration/Elementor/{WidgetName}WidgetTest.php --testdox
```

### Step 9: Run Quality Gates
```bash
cd wp-content/themes/soma

# Auto-fix formatting first
composer phpcbf

# Check coding standards (MUST pass with 0 errors)
composer phpcs

# Static analysis Level 6+ (MUST pass)
composer phpstan
```

### Step 10: Regenerate Translations (MANDATORY)
**When**: After adding ANY new translatable string

```bash
cd wp-content/themes/soma

# 1. Generate .pot template
wp i18n make-pot . languages/soma.pot --domain=soma --exclude=node_modules,vendor,tests

# 2. Update existing .po files
wp i18n update-po languages/soma.pot languages/

# 3. Compile .mo files
wp i18n make-mo languages/
```

---

## 📝 Elementor Control Patterns

### Standard Controls with i18n

```php
// Text control
$this->add_control(
    'title',
    [
        'label'       => esc_html__( 'Title', 'soma' ),
        'type'        => Controls_Manager::TEXT,
        'default'     => esc_html__( 'Default Title', 'soma' ),
        'placeholder' => esc_html__( 'Enter title', 'soma' ),
    ]
);

// SELECT2 for taxonomy terms
$this->add_control(
    'category',
    [
        'label'       => esc_html__( 'Category', 'soma' ),
        'type'        => Controls_Manager::SELECT2,
        'options'     => $this->get_categories(),
        'default'     => 0,
        'description' => esc_html__( 'Select a category to filter', 'soma' ),
    ]
);

// Number control
$this->add_control(
    'posts_per_page',
    [
        'label'   => esc_html__( 'Posts Per Page', 'soma' ),
        'type'    => Controls_Manager::NUMBER,
        'default' => 6,
        'min'     => 1,
        'max'     => 50,
    ]
);

// Switcher control
$this->add_control(
    'show_title',
    [
        'label'        => esc_html__( 'Show Title', 'soma' ),
        'type'         => Controls_Manager::SWITCHER,
        'label_on'     => esc_html__( 'Yes', 'soma' ),
        'label_off'    => esc_html__( 'No', 'soma' ),
        'return_value' => 'yes',
        'default'      => 'yes',
    ]
);
```

### Global Styles (Site Kit Integration)

Use Elementor's Site Kit global colors and typography for consistency:

```php
use Elementor\Core\Kits\Documents\Tabs\Global_Colors;
use Elementor\Core\Kits\Documents\Tabs\Global_Typography;
use Elementor\Group_Control_Typography;

// Color control with global default
$this->add_control(
    'title_color',
    [
        'label'     => esc_html__( 'Color', 'soma' ),
        'type'      => Controls_Manager::COLOR,
        'global'    => [
            'default' => Global_Colors::COLOR_PRIMARY,
        ],
        'selectors' => [
            '{{WRAPPER}} .element-title' => 'color: {{VALUE}};',
        ],
    ]
);

// Typography control with global default
$this->add_group_control(
    Group_Control_Typography::get_type(),
    [
        'name'     => 'title_typography',
        'label'    => esc_html__( 'Typography', 'soma' ),
        'global'   => [
            'default' => Global_Typography::TYPOGRAPHY_PRIMARY,
        ],
        'selector' => '{{WRAPPER}} .element-title',
    ]
);
```

**Available Global Colors:**
- `Global_Colors::COLOR_PRIMARY` - Primary brand color
- `Global_Colors::COLOR_SECONDARY` - Secondary color
- `Global_Colors::COLOR_TEXT` - Body text color
- `Global_Colors::COLOR_ACCENT` - Accent/highlight color

**Available Global Typography:**
- `Global_Typography::TYPOGRAPHY_PRIMARY` - Headings
- `Global_Typography::TYPOGRAPHY_SECONDARY` - Subheadings
- `Global_Typography::TYPOGRAPHY_TEXT` - Body text
- `Global_Typography::TYPOGRAPHY_ACCENT` - Accent text

### Control Sections

```php
$this->start_controls_section(
    'section_content',
    [
        'label' => esc_html__( 'Content', 'soma' ),
        'tab'   => Controls_Manager::TAB_CONTENT,
    ]
);
// ... controls ...
$this->end_controls_section();

$this->start_controls_section(
    'section_query',
    [
        'label' => esc_html__( 'Query', 'soma' ),
        'tab'   => Controls_Manager::TAB_CONTENT,
    ]
);
// ... query controls ...
$this->end_controls_section();

$this->start_controls_section(
    'section_style',
    [
        'label' => esc_html__( 'Style', 'soma' ),
        'tab'   => Controls_Manager::TAB_STYLE,
    ]
);
// ... style controls ...
$this->end_controls_section();
```

---

## 🔍 Category/Taxonomy Filter Pattern

**Standard pattern for filtering by taxonomy:**

### 1. Helper Method
```php
/**
 * Get taxonomy terms for SELECT2 control.
 *
 * @return array<int, string> Term ID => Term name.
 */
private function get_categories(): array {
    $options = [ 0 => esc_html__( 'All Categories', 'soma' ) ];

    $terms = get_terms(
        [
            'taxonomy'   => 'your-taxonomy-slug',
            'hide_empty' => true,
        ]
    );

    if ( ! is_wp_error( $terms ) && ! empty( $terms ) ) {
        foreach ( $terms as $term ) {
            $options[ $term->term_id ] = $term->name;
        }
    }

    return $options;
}
```

### 2. SELECT2 Control
```php
$this->add_control(
    'category',
    [
        'label'   => esc_html__( 'Category', 'soma' ),
        'type'    => Controls_Manager::SELECT2,
        'options' => $this->get_categories(),
        'default' => 0,
    ]
);
```

### 3. Query with tax_query
```php
$args = [
    'post_type'      => 'your-post-type',
    'posts_per_page' => $posts_per_page,
    'post_status'    => 'publish',
];

$category = absint( $settings['category'] ?? 0 );
if ( $category > 0 ) {
    $args['tax_query'] = [
        [
            'taxonomy' => 'your-taxonomy-slug',
            'field'    => 'term_id',
            'terms'    => $category,
        ],
    ];
}

$query = new \WP_Query( $args );
```

---

## 🧪 Test Patterns

### Integration Test for New Control

```php
/**
 * Test that category control exists and is SELECT2 type.
 */
public function test_category_control_is_select2(): void {
    $reflection = new \ReflectionClass( $this->widget );
    $method     = $reflection->getMethod( 'register_controls' );

    // Verify the method exists.
    $this->assertTrue( $reflection->hasMethod( 'register_controls' ) );
}

/**
 * Test that get_categories helper returns array.
 */
public function test_get_categories_returns_array(): void {
    $reflection = new \ReflectionClass( $this->widget );

    if ( $reflection->hasMethod( 'get_document_categories' ) ) {
        $method = $reflection->getMethod( 'get_document_categories' );
        $result = $method->invoke( $this->widget );
        $this->assertIsArray( $result );
    } else {
        $this->markTestSkipped( 'Method get_document_categories does not exist' );
    }
}
```

---

## ❌ Common Mistakes to Avoid

### 1. Missing i18n Functions
```php
// ❌ WRONG - Not translatable
'label' => 'Category',

// ✅ CORRECT - Translatable
'label' => esc_html__( 'Category', 'soma' ),
```

### 2. Forgetting Translation Regeneration
```php
// After adding new strings, ALWAYS run:
wp i18n make-pot . languages/soma.pot --domain=soma --exclude=node_modules,vendor,tests
wp i18n update-po languages/soma.pot languages/
wp i18n make-mo languages/
```

### 3. Missing Tests for New Controls
```php
// When adding 'category' control, add test assertion:
public function test_has_controls(): void {
    // ... existing assertions ...
    $this->assertContains( 'category', $control_ids );
}
```

### 4. Skipping Quality Gates
```bash
# NEVER skip these before commit:
composer phpcs    # MUST pass
composer phpstan  # MUST pass
composer test     # MUST pass
```

### 5. Wrong Text Domain
```php
// ❌ WRONG - Wrong text domain
__( 'Label', 'theme' )

// ✅ CORRECT - SOMA text domain
__( 'Label', 'soma' )
```

### 6. Direct Post Title Access in Dropdowns (Multilang Bug)

**Problem:** When using `$post->post_title` directly in SELECT/SELECT2 controls, WP-Multilang strings stored in `[:en]Name[:es]Nombre[:]` format display as raw text instead of the translated version.

```php
// ❌ WRONG - Bypasses WP-Multilang filters
$options[ $post->ID ] = $post->post_title;
// Shows: "[:en]John Doe[:es]Juan Pérez[:]"

// ✅ CORRECT - Uses WordPress filter that WP-Multilang hooks into
$options[ $post->ID ] = get_the_title( $post->ID );
// Shows: "John Doe" (EN) or "Juan Pérez" (ES)
```

**Why it happens:**
- WP-Multilang stores translations in a single field using `[:lang]...[:]` delimiters
- The plugin hooks into the `the_title` filter to parse these strings
- `$post->post_title` is a direct property access that bypasses all filters
- `get_the_title()` applies the `the_title` filter, enabling WP-Multilang translation

**Affected widgets:**
- TeamMember.php (team member selector dropdown)
- ContactForm.php (CF7 form selector dropdown)
- Any widget with post/CPT selector dropdowns

**Always use:**
- `get_the_title( $post->ID )` for post titles
- `get_the_title( $post )` also works (accepts WP_Post object)

---

## 📋 Pre-Commit Checklist

Before creating a PR for widget changes, verify:

- [ ] All user-facing strings use `__()`, `esc_html__()`, or `esc_attr__()`
- [ ] Text domain is always `'soma'`
- [ ] Translation files regenerated (`wp i18n make-pot`, `update-po`, `make-mo`)
- [ ] Integration tests added/updated for new controls
- [ ] `test_has_controls()` includes new control IDs
- [ ] PHPCS passes with 0 errors
- [ ] PHPStan Level 6+ passes
- [ ] All tests pass
- [ ] CSS uses SOMA variables (`--soma-*`)
- [ ] Post titles use `get_the_title()` not `$post->post_title` (WP-Multilang compatibility)

---

## 🔗 Related Documentation

- **Widgets Reference**: `wp-content/themes/soma/docs/WIDGETS.md`
- **Internationalization**: `wp-content/themes/soma/docs/INTERNATIONALIZATION.md`
- **Development Guide**: `wp-content/themes/soma/docs/DEVELOPMENT.md`
- **Testing Guide**: `wp-content/themes/soma/docs/TESTING_GUIDE.md`

---

**Document Version**: 1.1  
**Last Updated**: January 8, 2026  
**Maintainer**: Miguel Colmenares
