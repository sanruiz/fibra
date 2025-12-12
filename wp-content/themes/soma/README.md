SOMA v1.0
=====

> SOMA Wordpress Theme.

## Required plugins

* Advanced Custom Fields PRO By Elliot Condon
* Contact Form 7 By Takayuki Miyoshi
* Safe SVG By Daryll Doyle
* WP Multilang By Valentyn Riaboshtan
* **Elementor** (for Elementor widgets integration - v3.31+)

----

## Getting Started

### Installation

```bash
# Install Composer dependencies
composer install

# Install NPM packages
npm install
```

### Development

```bash
# Development build (with watch mode)
npm run watch

# Development build (single)
npm run dev

# Production build (minified)
npm run prod
```

### Testing

```bash
# Run all tests
composer test

# Run unit tests only
vendor/bin/phpunit --testsuite unit

# Run integration tests (requires WordPress + Elementor installed)
vendor/bin/phpunit --testsuite integration

# Code quality checks
composer phpcs        # Check coding standards
composer phpcbf       # Auto-fix coding standards
composer phpstan      # Static analysis
composer validate     # Run all quality checks
```

### Integration Tests Requirements

Integration tests require a WordPress testing environment with:
- WordPress Test Suite installed
- Elementor plugin active (v3.31+)
- Database connection configured in `wp-tests-config.php`

**To run integration tests:**

```bash
# Install WordPress test suite (if not already installed)
bash tests/bin/install-wp-tests.sh wordpress_test root '' localhost latest

# Run integration tests
vendor/bin/phpunit --group integration

# Run only Elementor integration tests
vendor/bin/phpunit --group elementor
```

**Skip integration tests if Elementor not available:**

```bash
# Run only unit tests
vendor/bin/phpunit --exclude-group integration
```

----

A [PIPE:CODE](https://pipe-code.github.io/) Theme.

© SOMA 2020-2025