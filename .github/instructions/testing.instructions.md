---
description: Testing standards, best practices, and conventions for PHPUnit tests
name: Testing Standards
applyTo: "tests/**/*.php"
---

# Testing Standards & Best Practices for SOMA Theme

**Applies to**: All test files in `tests/` directory  
**Last Updated**: January 7, 2026  
**Project**: SOMA WordPress Theme v3.0+  
**Widget Count**: 9 Elementor widgets (all with integration tests)

---

## 🧪 Testing Philosophy

### Test Coverage Strategy

The SOMA theme follows a **pragmatic testing approach** that balances comprehensive coverage with development velocity:

- ✅ **Integration tests** for Elementor widgets (testing real WordPress environment)
- ✅ **Unit tests** for pure PHP classes (PostTypes, Taxonomies, Utils)
- ✅ **Integration tests** for PageBuilder components
- ✅ **Unit tests** for helper functions and utilities

### Quality Gates

All code must pass these gates before merge:

```bash
composer phpcs      # WordPress Coding Standards (0 errors)
composer phpstan    # Static Analysis Level 6+ (0 critical errors)
composer test       # PHPUnit (108+ tests, 355+ assertions)
```

---

## 🚫 CRITICAL: Widgets Testing Convention

### Why Widgets Should NOT Have Unit Tests

**Project Convention**: Elementor widgets should **ONLY** have integration tests, **NOT** unit tests.

#### Rationale

1. **Tight WordPress Integration**
   - Widgets are deeply integrated with WordPress and Elementor ecosystems
   - They rely on global WordPress functions, hooks, and database
   - Unit testing would require extensive mocking (wp_enqueue_style, get_template_directory, etc.)
   - Mock complexity outweighs testing value

2. **Framework Dependency**
   - Widgets extend `\Elementor\Widget_Base` or `\Soma\Elementor\Base\WidgetBase`
   - Elementor plugin must be loaded for widgets to instantiate
   - Unit tests can't load full WordPress/Elementor environment efficiently

3. **Integration Testing Is More Valuable**
   - Widgets interact with templates, ACF fields, CSS files, and WordPress queries
   - Integration tests validate **actual behavior** in real environment
   - They catch issues that unit tests would miss (missing CSS files, ACF field structure, etc.)

4. **Project Consistency**
   - **No other widget in the project has unit tests**
   - All 8+ existing widgets follow integration-only pattern
   - Maintaining consistency reduces cognitive load

5. **Maintenance Burden**
   - Widget unit tests would require updating mocks when WordPress/Elementor APIs change
   - Integration tests naturally adapt to framework changes
   - Less brittle, more maintainable

#### Historical Context

In commit `5363091`, we removed `tests/Unit/Elementor/TeamMemberWidgetTest.php` because:
- It caused CI failures
- It violated project convention
- Integration tests already provided sufficient coverage

**Documentation**: See `.github/copilot-instructions.md` lines 935-968 for widget development workflow.

---

## ✅ Correct Testing Patterns

### Elementor Widgets: Integration Tests Only

**Location**: `tests/Integration/Elementor/{WidgetName}Test.php`

**Pattern Structure**:

```php
<?php
/**
 * {WidgetName} Widget Integration Tests
 *
 * @package Soma
 * @subpackage Tests\Integration\Elementor
 * @since X.Y.Z
 */

namespace Soma\Tests\Integration\Elementor;

use WP_UnitTestCase;
use Soma\Elementor\Widgets\{WidgetName};

/**
 * Test {WidgetName} widget integration
 *
 * @group integration
 * @group elementor
 * @group widgets
 */
class {WidgetName}WidgetTest extends WP_UnitTestCase {

	/**
	 * Widget instance
	 *
	 * @var {WidgetName}
	 */
	private {WidgetName} $widget;

	/**
	 * Set up test environment
	 *
	 * ⚠️ IMPORTANT: Use camelCase setUp(), NOT snake_case set_up()
	 */
	public function setUp(): void {
		parent::setUp();

		// Skip if Elementor not loaded
		if ( ! class_exists( '\Elementor\Plugin' ) ) {
			$this->markTestSkipped( 'Elementor plugin is not active' );
		}

		$this->widget = new {WidgetName}();
	}

	/**
	 * Tear down test environment
	 *
	 * ⚠️ IMPORTANT: Use camelCase tearDown(), NOT snake_case tear_down()
	 */
	public function tearDown(): void {
		// Clean up resources if needed
		$this->widget = null;
		parent::tearDown();
	}

	/**
	 * Test widget name
	 */
	public function test_get_name(): void {
		$this->assertSame( 'soma-widget-name', $this->widget->get_name() );
	}

	/**
	 * Test widget title
	 */
	public function test_get_title(): void {
		$title = $this->widget->get_title();
		$this->assertIsString( $title );
		$this->assertNotEmpty( $title );
	}

	/**
	 * Test widget icon
	 */
	public function test_get_icon(): void {
		$icon = $this->widget->get_icon();
		$this->assertIsString( $icon );
		$this->assertNotEmpty( $icon );
	}

	/**
	 * Test widget categories
	 */
	public function test_get_categories(): void {
		$categories = $this->widget->get_categories();

		$this->assertIsArray( $categories );
		$this->assertContains( 'soma', $categories );
	}

	/**
	 * Test style dependencies
	 */
	public function test_get_style_depends(): void {
		$styles = $this->widget->get_style_depends();

		$this->assertIsArray( $styles );
		$this->assertContains( 'soma-widget-name', $styles );
	}

	/**
	 * Test CSS file exists
	 */
	public function test_css_file_exists(): void {
		$css_path = get_template_directory() . '/assets/css/widgets/widget-name.css';
		$this->assertFileExists( $css_path );
	}

	/**
	 * Test CSS uses SOMA variables
	 */
	public function test_css_uses_soma_variables(): void {
		$css_path    = get_template_directory() . '/assets/css/widgets/widget-name.css';
		$css_content = file_get_contents( $css_path );

		$this->assertStringContainsString( '--soma-spacing', $css_content );
		$this->assertStringContainsString( '--soma-font-family', $css_content );
		$this->assertStringContainsString( '--soma-color', $css_content );
	}

	/**
	 * Test CSS has responsive breakpoints
	 */
	public function test_css_has_responsive_breakpoints(): void {
		$css_path    = get_template_directory() . '/assets/css/widgets/widget-name.css';
		$css_content = file_get_contents( $css_path );

		// Check for tablet breakpoint
		$this->assertStringContainsString( '@media (max-width: 991px)', $css_content );

		// Check for mobile breakpoint
		$this->assertStringContainsString( '@media (max-width: 767px)', $css_content );
	}
}
```

### Complete Widget Integration Test Example

**Real example**: `tests/Integration/Elementor/NavbarWidgetTest.php`

```php
<?php
namespace Soma\Tests\Integration\Elementor;

use WP_UnitTestCase;
use Soma\Elementor\Widgets\Navbar;

class NavbarWidgetTest extends WP_UnitTestCase {
	private Navbar $widget;

	public function setUp(): void {
		parent::setUp();
		if ( ! class_exists( '\Elementor\Plugin' ) ) {
			$this->markTestSkipped( 'Elementor plugin is not active' );
		}
		$this->widget = new Navbar();
	}

	public function test_extends_widget_base(): void {
		$this->assertInstanceOf(
			\Soma\Elementor\Base\WidgetBase::class,
			$this->widget
		);
	}

	public function test_get_name(): void {
		$this->assertSame( 'soma-navbar', $this->widget->get_name() );
	}

	public function test_has_controls(): void {
		$reflection = new \ReflectionClass( $this->widget );
		$method     = $reflection->getMethod( 'register_controls' );
		$method->invoke( $this->widget );

		$controls = $this->widget->get_controls();
		$this->assertNotEmpty( $controls );
		$this->assertArrayHasKey( 'menu', $controls );
	}

	public function test_renders_without_errors(): void {
		$reflection = new \ReflectionClass( $this->widget );
		$method     = $reflection->getMethod( 'render' );

		ob_start();
		$method->invoke( $this->widget );
		$output = ob_get_clean();

		$this->assertNotEmpty( $output );
		$this->assertStringContainsString( 'soma-navbar', $output );
	}
}
```

---

## 🔧 PHPUnit Best Practices

### Critical Rules (Prevent CI Failures)

#### 1. Use camelCase for Lifecycle Methods

**❌ WRONG** (causes test failures):
```php
public function set_up(): void {
	parent::set_up();
}

public function tear_down(): void {
	parent::tear_down();
}
```

**✅ CORRECT**:
```php
public function setUp(): void {
	parent::setUp();
}

public function tearDown(): void {
	parent::tearDown();
}
```

**Why**: PHPUnit requires camelCase for lifecycle methods. Snake_case is a deprecated convention that causes PHPUnit to not recognize these as setup/teardown hooks, leading to test initialization failures.

**Historical Context**: 
- Commit `c41d03a`: Fixed `TeamMemberWidgetTest.php` by changing `set_up`/`tear_down` to `setUp`/`tearDown`.
- Commit `862f767`: Added Elementor check to `TeamMemberWidgetTest` after 10 CI failures when widget instantiation failed without Elementor.
- Key learning: Always include `return;` after `markTestSkipped()` to prevent further execution.

#### 2. Always Call Parent Methods

```php
public function setUp(): void {
	parent::setUp();  // ✅ Required
	// Your setup code...
}

public function tearDown(): void {
	// Your cleanup code...
	parent::tearDown();  // ✅ Required
}
```

#### 3. Skip Tests When Dependencies Missing

```php
public function setUp(): void {
	parent::setUp();

	if ( ! class_exists( '\Elementor\Plugin' ) ) {
		$this->markTestSkipped( 'Elementor plugin is not active' );
		return; // Prevent further execution
	}

	$this->widget = new MyWidget();
}
```

#### 4. Use Type Hints Consistently

```php
// ✅ GOOD - Explicit types
private MyWidget $widget;
private array $test_data = array();
private ?string $result = null;

// ❌ AVOID - No type hints
private $widget;
private $test_data;
```

#### 5. Clean Up Resources in tearDown

```php
public function tearDown(): void {
	// Delete test posts
	foreach ( $this->test_posts as $post_id ) {
		wp_delete_post( $post_id, true );
	}
	$this->test_posts = array();

	// Delete test terms
	foreach ( $this->test_terms as $term_id ) {
		wp_delete_term( $term_id, 'taxonomy-name' );
	}
	$this->test_terms = array();

	parent::tearDown();
}
```

### Test Method Naming

**Convention**: `test_{what_is_being_tested}`

```php
// ✅ GOOD
public function test_widget_name(): void
public function test_css_file_exists(): void
public function test_renders_without_errors(): void

// ❌ AVOID
public function testWidgetName(): void  // Not WordPress style
public function test_1(): void  // Not descriptive
public function verify_widget(): void  // Wrong prefix
```

### Assertion Best Practices

#### Use Specific Assertions

```php
// ✅ GOOD - Specific assertions
$this->assertSame( 'expected', $actual );  // Strict equality
$this->assertIsArray( $data );
$this->assertContains( 'soma', $categories );
$this->assertArrayHasKey( 'key', $array );
$this->assertFileExists( $path );
$this->assertStringContainsString( 'needle', $haystack );

// ❌ AVOID - Generic assertions
$this->assertTrue( $actual === 'expected' );
$this->assertTrue( is_array( $data ) );
$this->assertTrue( in_array( 'soma', $categories ) );
```

#### Provide Meaningful Messages

```php
// ✅ GOOD
$this->assertFileExists(
	$css_path,
	'Widget CSS file should exist at: ' . $css_path
);

$this->assertContains(
	'soma',
	$categories,
	'Widget should be in "soma" category'
);

// ❌ AVOID
$this->assertFileExists( $css_path );
$this->assertContains( 'soma', $categories );
```

### Testing Protected Methods

Use reflection when needed:

```php
public function test_protected_method(): void {
	$reflection = new \ReflectionClass( $this->widget );
	$method     = $reflection->getMethod( 'protected_method_name' );

	$result = $method->invoke( $this->widget, $arg1, $arg2 );

	$this->assertSame( 'expected', $result );
}
```

### Testing Rendered Output

```php
public function test_render_output(): void {
	$reflection = new \ReflectionClass( $this->widget );
	$method     = $reflection->getMethod( 'render' );

	ob_start();
	$method->invoke( $this->widget );
	$output = ob_get_clean();

	$this->assertNotEmpty( $output, 'Widget should render output' );
	$this->assertStringContainsString( 'soma-widget', $output );
	$this->assertStringContainsString( '<div', $output );
}
```

---

## 📂 Test File Organization

### Directory Structure

```
tests/
├── Unit/                       # Pure PHP unit tests (no WordPress)
│   ├── Core/
│   ├── PostTypes/
│   ├── Taxonomies/
│   └── Utils/
├── Integration/                # WordPress integration tests
│   ├── Elementor/
│   │   ├── AllWidgetsTest.php
│   │   ├── NavbarWidgetTest.php
│   │   ├── TeamMembersWidgetTest.php
│   │   ├── TeamMemberWidgetTest.php    ← Integration tests ONLY
│   │   └── ...
│   ├── PageBuilder/
│   └── API/
├── Mocks/                      # Mock classes for unit tests
└── bootstrap.php               # Test initialization
```

### Naming Conventions

| Component | File Name | Example |
|-----------|-----------|---------|
| Widget Integration Test | `{WidgetName}WidgetTest.php` | `NavbarWidgetTest.php` |
| Class Unit Test | `{ClassName}Test.php` | `PortfolioTest.php` |
| Integration Test | `{Feature}Test.php` | `PageBuilderTest.php` |

### Namespace Structure

```php
<?php
namespace Soma\Tests\Unit\PostTypes;        // Unit tests
namespace Soma\Tests\Integration\Elementor; // Integration tests
```

---

## 🎯 Widget Testing Checklist

When creating integration tests for an Elementor widget, verify:

### Basic Widget Properties
- [ ] ✅ Widget name (`get_name()`)
- [ ] ✅ Widget title (`get_title()`)
- [ ] ✅ Widget icon (`get_icon()`)
- [ ] ✅ Widget categories (contains `'soma'`)
- [ ] ✅ Style dependencies (contains `'soma-{widget-name}'`)
- [ ] ✅ Extends `WidgetBase` or `\Elementor\Widget_Base`

### Widget Structure
- [ ] ✅ Has `register_controls()` method (protected)
- [ ] ✅ Has `render()` method (protected)
- [ ] ✅ Has required controls registered

### CSS & Styling
- [ ] ✅ CSS file exists (`/assets/css/widgets/{widget-name}.css`)
- [ ] ✅ CSS uses SOMA variables (`--soma-*`)
- [ ] ✅ CSS has responsive breakpoints (`@media (max-width: 991px)`, `767px`)
- [ ] ✅ CSS uses mobile-specific variables (if applicable)

### Elementor Integration
- [ ] ✅ Uses `Global_Colors` (if applicable)
- [ ] ✅ Uses `Global_Typography` (if applicable)
- [ ] ✅ Controls use proper Elementor controls (`Controls_Manager::SELECT2`, etc.)

### Rendering
- [ ] ✅ Renders without PHP errors
- [ ] ✅ Output contains widget class name
- [ ] ✅ Output is valid HTML

### Test Structure
- [ ] ✅ Uses `setUp()` (camelCase, NOT `set_up()`)
- [ ] ✅ Uses `tearDown()` (camelCase, NOT `tear_down()`)
- [ ] ✅ Skips test if Elementor not loaded
- [ ] ✅ Has `@group` annotations (`integration`, `elementor`, `widgets`)

---

## 🚀 Running Tests

### Run All Tests

```bash
cd wp-content/themes/soma
composer test
```

### Run Specific Test Suites

```bash
# Unit tests only
vendor/bin/phpunit --testsuite unit

# Integration tests only
vendor/bin/phpunit --testsuite integration

# Widget tests only
vendor/bin/phpunit --group widgets

# Specific test file
vendor/bin/phpunit tests/Integration/Elementor/NavbarWidgetTest.php

# Specific test method
vendor/bin/phpunit --filter test_widget_name
```

### With Coverage

```bash
vendor/bin/phpunit --coverage-html coverage/
# Open: coverage/index.html
```

### Verbose Output

```bash
vendor/bin/phpunit --testdox
```

---

## 🐛 Common Testing Pitfalls

### Pitfall 1: Using snake_case Lifecycle Methods

**❌ WRONG**:
```php
public function set_up(): void { }
public function tear_down(): void { }
```

**✅ CORRECT**:
```php
public function setUp(): void { }
public function tearDown(): void { }
```

**Symptom**: Tests fail with "setUp method not found" or similar errors.

### Pitfall 2: Not Skipping When Dependencies Missing

**❌ WRONG**:
```php
public function setUp(): void {
	parent::setUp();
	$this->widget = new MyWidget(); // Fatal error if Elementor not loaded
}
```

**✅ CORRECT**:
```php
public function setUp(): void {
	parent::setUp();
	if ( ! class_exists( '\Elementor\Plugin' ) ) {
		$this->markTestSkipped( 'Elementor plugin is not active' );
		return;
	}
	$this->widget = new MyWidget();
}
```

### Pitfall 3: Not Cleaning Up Resources

**❌ WRONG**:
```php
public function test_with_posts(): void {
	$post_id = $this->factory->post->create();
	// Test...
	// Post remains in database
}
```

**✅ CORRECT**:
```php
private array $test_posts = array();

public function test_with_posts(): void {
	$post_id = $this->factory->post->create();
	$this->test_posts[] = $post_id;
	// Test...
}

public function tearDown(): void {
	foreach ( $this->test_posts as $post_id ) {
		wp_delete_post( $post_id, true );
	}
	$this->test_posts = array();
	parent::tearDown();
}
```

### Pitfall 4: Creating Widget Unit Tests

**❌ WRONG**:
```php
// tests/Unit/Elementor/NavbarWidgetTest.php
// Don't create this file - it violates project convention
```

**✅ CORRECT**:
```php
// tests/Integration/Elementor/NavbarWidgetTest.php
// Integration tests ONLY for widgets
```

---

## 📚 Additional Resources

### Internal Documentation
- **Widget Development**: `.github/copilot-instructions.md` (lines 935-968)
- **Development Guide**: `wp-content/themes/soma/docs/DEVELOPMENT.md`
- **Testing Guide**: `wp-content/themes/soma/docs/TESTING_GUIDE.md`
- **Widget Reference**: `wp-content/themes/soma/docs/WIDGETS.md`

### External Resources
- [PHPUnit Documentation](https://phpunit.de/documentation.html)
- [WordPress PHPUnit Testing](https://make.wordpress.org/core/handbook/testing/automated-testing/phpunit/)
- [WP_UnitTestCase Reference](https://developer.wordpress.org/reference/classes/wp_unittestcase/)

### Example Tests to Study
- `tests/Integration/Elementor/NavbarWidgetTest.php` (simple widget, 127 lines)
- `tests/Integration/Elementor/TeamMembersWidgetTest.php` (complex widget, 236 lines)
- `tests/Integration/Elementor/DocumentsWidgetTest.php` (comprehensive, 9,865 bytes)
- `tests/Integration/PageBuilderTest.php` (PageBuilder system tests)

---

## 🎓 Testing Principles Summary

### DO ✅

- ✅ **Use camelCase for setUp/tearDown** (NOT snake_case)
- ✅ **Write integration tests for Elementor widgets** (NOT unit tests)
- ✅ **Test CSS file existence and SOMA variable usage**
- ✅ **Test responsive breakpoints in CSS**
- ✅ **Use specific assertions** (assertSame, assertContains, etc.)
- ✅ **Provide meaningful assertion messages**
- ✅ **Clean up resources in tearDown**
- ✅ **Skip tests when dependencies missing**
- ✅ **Follow existing widget test patterns**

### DON'T ❌

- ❌ **Don't create unit tests for Elementor widgets**
- ❌ **Don't use snake_case lifecycle methods** (set_up, tear_down)
- ❌ **Don't forget to skip when Elementor not loaded**
- ❌ **Don't leave test data in database**
- ❌ **Don't use generic assertions** (assertTrue for everything)
- ❌ **Don't test implementation details** (test behavior, not internals)
- ❌ **Don't duplicate test logic** (use helper methods)

---

**Document Version**: 1.0  
**Last Updated**: January 7, 2026  
**Project Convention Since**: v3.0.0 (December 2025)  
**Maintained By**: Miguel Colmenares

