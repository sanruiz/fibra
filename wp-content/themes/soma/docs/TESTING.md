# Soma Theme - Testing Guide

## Overview

The Soma theme uses PHPUnit for unit and integration testing with proper WordPress test suite integration and stub packages for WordPress, ACF Pro, and Elementor.

---

## Prerequisites

- PHP 8.1+
- Composer
- MySQL/MariaDB database
- WordPress test suite installed

---

## Quick Start

### 1. Install Composer Dependencies

```bash
composer install
```

This will install:
- `phpunit/phpunit` - Testing framework
- `php-stubs/wordpress-stubs` - WordPress core stubs
- `php-stubs/acf-pro-stubs` - ACF Pro stubs
- `php-stubs/wordpress-tests-stubs` - WordPress test suite stubs
- `php-stubs/wordpress-seo-stubs` - Yoast SEO stubs (optional)
- `yoast/phpunit-polyfills` - PHPUnit polyfills for older PHP versions

### 2. Install WordPress Test Suite

```bash
cd tests/bin
./install-wp-tests.sh wordpress_test root 'password' localhost latest
```

**Parameters:**
- `wordpress_test` - Test database name
- `root` - MySQL username
- `password` - MySQL password
- `localhost` - MySQL host
- `latest` - WordPress version (use 'latest' or specific version like '6.4')

**Note:** This script will:
- Download WordPress core to `/tmp/wordpress/`
- Download WordPress test suite to `/tmp/wordpress-tests-lib/`
- Create test database
- Configure wp-tests-config.php

### 3. Set WP_TESTS_DIR Environment Variable

Add to your shell profile (`~/.zshrc` or `~/.bashrc`):

```bash
export WP_TESTS_DIR="/tmp/wordpress-tests-lib"
```

Then reload your shell:

```bash
source ~/.zshrc
```

### 4. Run Tests

```bash
composer test
```

Or directly with PHPUnit:

```bash
vendor/bin/phpunit
```

---

## Test Structure

```
tests/
├── bootstrap.php           # PHPUnit bootstrap file
├── phpunit.xml            # PHPUnit configuration (auto-found)
├── bin/
│   └── install-wp-tests.sh # WordPress test suite installer
├── Mocks/
│   └── SimpleMocks.php    # Theme-specific mocks
└── Unit/
    ├── Core/              # Core component tests
    ├── PostTypes/         # Post type tests
    ├── CustomFields/      # ACF field tests
    ├── API/               # REST API endpoint tests
    ├── CF7/               # Contact Form 7 tests
    └── Utils/             # Utility tests
```

---

## Writing Tests

### Basic Test Structure

```php
<?php
namespace Soma\Tests\Unit\PostTypes;

use PHPUnit\Framework\TestCase;
use Soma\PostTypes\Types\Portfolio;

class PortfolioTest extends TestCase
{
    public function test_singleton_returns_same_instance(): void
    {
        $instance1 = Portfolio::instance();
        $instance2 = Portfolio::instance();

        $this->assertSame($instance1, $instance2);
    }

    public function test_post_type_constant_is_defined(): void
    {
        $this->assertSame('portfolio', Portfolio::POST_TYPE);
    }
}
```

### LoadableInterface Tests

```php
<?php
namespace Soma\Tests\Unit\PostTypes;

use PHPUnit\Framework\TestCase;
use Soma\PostTypes\Loader;

class LoaderTest extends TestCase
{
    public function test_implements_loadable_interface(): void
    {
        $loader = Loader::instance();
        $this->assertInstanceOf(
            \Soma\Core\Interfaces\LoadableInterface::class,
            $loader
        );
    }

    public function test_get_priority_returns_20(): void
    {
        $loader = Loader::instance();
        $this->assertSame(20, $loader->get_priority());
    }

    public function test_should_load_returns_true(): void
    {
        $loader = Loader::instance();
        $this->assertTrue($loader->should_load());
    }
}
```

### Using WordPress Functions

WordPress functions and classes are available through stubs:

```php
<?php
// WordPress core functions work automatically
$post_id = wp_insert_post([
    'post_title' => 'Test Post',
    'post_type' => 'portfolio',
]);

// ACF functions available
$value = get_field('field_name', $post_id);

// REST API classes available
$request = new \WP_REST_Request('GET', '/soma/news');
```

---

## Mock Classes

### WPCF7 Mocks (in bootstrap.php)

Contact Form 7 classes are mocked for testing CF7 integration:

```php
// Available mock classes:
- WPCF7
- WPCF7_ContactForm
- WPCF7_Submission
- WPCF7_Validation
- WPCF7_FormTag
```

Usage in tests:

```php
<?php
public function test_should_load_returns_false_when_wpcf7_missing(): void
{
    // WPCF7 class exists in bootstrap but we can test conditional logic
    $loader = \Soma\CF7\Loader::instance();
    $this->assertTrue($loader->should_load());
}
```

---

## Running Specific Tests

### Run Single Test Class

```bash
vendor/bin/phpunit tests/Unit/PostTypes/PortfolioTest.php
```

### Run Single Test Method

```bash
vendor/bin/phpunit --filter test_singleton_returns_same_instance
```

### Run Tests by Directory

```bash
vendor/bin/phpunit tests/Unit/PostTypes/
```

---

## Code Coverage

Generate code coverage report:

```bash
composer coverage
```

This will:
- Run all tests with coverage collection
- Generate HTML report in `coverage/` directory
- Open `coverage/index.html` to view detailed coverage

**Requirements:**
- Xdebug or PCOV extension installed

---

## Continuous Integration

### GitHub Actions Example

```yaml
name: Tests

on: [push, pull_request]

jobs:
  test:
    runs-on: ubuntu-latest
    
    services:
      mysql:
        image: mysql:8.0
        env:
          MYSQL_ROOT_PASSWORD: password
          MYSQL_DATABASE: wordpress_test
        ports:
          - 3306:3306
    
    steps:
      - uses: actions/checkout@v3
      
      - name: Setup PHP
        uses: shivammathur/setup-php@v2
        with:
          php-version: '8.1'
          extensions: mbstring, intl, mysql
          coverage: xdebug
      
      - name: Install dependencies
        run: composer install --prefer-dist --no-progress
      
      - name: Install WordPress test suite
        run: |
          cd tests/bin
          ./install-wp-tests.sh wordpress_test root password 127.0.0.1 latest
      
      - name: Run tests
        run: vendor/bin/phpunit --coverage-text
```

---

## Troubleshooting

### "Could not find WP_TESTS_DIR"

**Solution:** Set the `WP_TESTS_DIR` environment variable:

```bash
export WP_TESTS_DIR="/tmp/wordpress-tests-lib"
```

### "Database connection failed"

**Solution:** Verify MySQL is running and credentials are correct:

```bash
mysql -u root -p
CREATE DATABASE wordpress_test;
```

### "Class not found" errors

**Solution:** Regenerate Composer autoloader:

```bash
composer dump-autoload
```

### "WordPress functions not found"

**Solution:** Ensure stubs are installed:

```bash
composer install
ls vendor/php-stubs/wordpress-stubs
```

---

## Best Practices

### 1. Test Organization
- One test class per component class
- Group related tests in directories
- Use descriptive test method names

### 2. Test Naming Convention
```php
test_[method_name]_[expected_behavior]_[conditions]()
```

Examples:
- `test_singleton_returns_same_instance()`
- `test_get_priority_returns_20()`
- `test_should_load_returns_false_when_wpcf7_missing()`

### 3. Assertions
- Use specific assertions: `assertSame()` over `assertEquals()`
- Test one concept per test method
- Use type hints for better IDE support

### 4. Mocking
- Only mock external dependencies (WPCF7, etc.)
- Don't mock WordPress core (use stubs)
- Keep mocks simple and focused

### 5. Coverage
- Aim for 80%+ coverage
- Focus on critical paths
- Test edge cases and error conditions

---

## Test Commands Reference

```bash
# Run all tests
composer test

# Run with coverage
composer coverage

# Run coding standards check
composer phpcs

# Auto-fix coding standards
composer phpcbf

# Run static analysis
composer phpstan

# Run all validation
composer validate-theme
```

---

## Resources

- [PHPUnit Documentation](https://phpunit.de/documentation.html)
- [WordPress PHPUnit Tests](https://make.wordpress.org/core/handbook/testing/automated-testing/phpunit/)
- [PHP Stubs WordPress](https://github.com/php-stubs/wordpress-stubs)
- [Yoast PHPUnit Polyfills](https://github.com/Yoast/PHPUnit-Polyfills)

---

**Document Version**: 1.0  
**Last Updated**: December 11, 2025  
**Status**: Active
