# SOMA Theme v3.0

> **Modern WordPress theme with PSR-4 architecture, ACF Flexible Content, and Elementor integration**

[![PHP Version](https://img.shields.io/badge/PHP-8.1%2B-blue.svg)](https://www.php.net/)
[![WordPress](https://img.shields.io/badge/WordPress-6.0%2B-blue.svg)](https://wordpress.org/)
[![License](https://img.shields.io/badge/License-Proprietary-red.svg)](LICENSE)
[![Tests](https://img.shields.io/badge/Tests-108%20passing-success.svg)](tests/)

---

## 🚀 What's New in v3.0

SOMA v3.0 is a **complete modernization** of the theme, bringing enterprise-grade development practices while preserving the powerful ACF flexible content system.

### Key Improvements

✨ **PSR-4 Architecture** - Modern namespaced classes (`Soma\`) with Composer autoloading  
🔥 **PHP 8.1+ Features** - Enums, match expressions, first-class callables, readonly properties  
🎨 **Elementor Integration** - 8 custom widgets for visual page building  
⚡ **Advanced Caching** - Tag-based cache system with automatic invalidation  
📝 **PSR-3 Logging** - File-based logging with 8 severity levels  
🧪 **Testing Infrastructure** - 108 tests (355 assertions) with PHPUnit  
🛠️ **Helper Functions** - 24+ `soma_*` global functions for common tasks  
📚 **Comprehensive Docs** - 5,000+ lines of developer documentation  
🏷️ **Taxonomies System** - 3 custom taxonomies with enum-based configuration  
✅ **Quality Tools** - PHPCS, PHPStan Level 6-8, automated validation

---

## 📋 Table of Contents

- [Requirements](#requirements)
- [Features](#features)
- [Quick Start](#quick-start)
- [Development](#development)
- [Testing](#testing)
- [Architecture](#architecture)
- [Documentation](#documentation)
- [Migration](#migration)
- [License](#license)

---

## 💻 Requirements

### Server Requirements

| Component | Version | Notes |
|-----------|---------|-------|
| **PHP** | 8.1+ | Required for enums and modern features |
| **WordPress** | 6.0+ | Tested up to 6.4 |
| **MySQL** | 5.7+ / MariaDB 10.3+ | InnoDB support required |
| **Memory Limit** | 256MB+ | Recommended for optimal performance |

### Development Requirements

| Tool | Version | Purpose |
|------|---------|---------|
| **Composer** | 2.0+ | PHP dependency management |
| **Node.js** | 16+ | Asset compilation |
| **npm** | 8+ | Package management |
| **Git** | 2.0+ | Version control |

### Required WordPress Plugins

| Plugin | Version | Purpose |
|--------|---------|---------|
| **Advanced Custom Fields PRO** | 6.0+ | Custom fields and flexible content |
| **Contact Form 7** | 5.0+ | Contact forms |
| **Safe SVG** | Latest | SVG file uploads |
| **WP Multilang** | Latest | (Optional) Multilingual support |
| **Elementor** | 3.31+ | (Optional) Visual page builder widgets |

---

## ✨ Features

### 🏗️ Modern Architecture

- **PSR-4 Compliant** - All classes in `includes/` follow PSR-4 autoloading
- **Namespace Structure** - Base namespace `Soma\` with logical organization
- **LoadableInterface** - Standardized component loading with priorities
- **Singleton Pattern** - Consistent instantiation across theme components
- **First-Class Callables** - Modern hook registration (PHP 8.1+)
- **Type Safety** - Full type hints and strict types

### 📦 Core Components

- **4 Custom Post Types** - Portfolio, News, Careers, Team Members
- **3 Custom Taxonomies** - Portfolio, News, Team Members categories
- **5 REST API Endpoints** - `/wp-json/soma/{news|careers|portfolio|documents|events}`
- **8 Elementor Widgets** - Navbar, Footer, Business Units, Services, Team, News, Portfolio, Contact
- **50+ ACF Partials** - Flexible content blocks for page building
- **5 Navigation Menus** - Main, Social, Business Units, Footer, Sidebar
- **Multilingual Support** - WP Multilang integration with date translation

### 🛠️ Developer Tools

- **24 Helper Functions** - `soma_*` prefixed global utilities
- **PSR-3 Logger** - File-based logging (`wp-content/uploads/soma-logs/`)
- **Cache System** - Tag-based caching with invalidation
- **Enums** - PostType, Taxonomy, LogLevel, CacheTag
- **Quality Gates** - PHPCS, PHPStan, automated validation
- **108 Unit Tests** - Comprehensive test coverage
- **WP-CLI Integration** - Custom commands and test runner

### 🎨 Frontend

- **Webpack Build System** - Modern asset compilation
- **CSS Variables** - 200+ design tokens in `variables.css`
- **SCSS Architecture** - Modular component styles
- **Responsive Design** - Mobile-first approach
- **Dark Mode Support** - Automatic dark section detection
- **Performance Optimized** - Lazy loading, minification, caching

---

## 🚀 Quick Start

### 1. Installation

```bash
# Clone or download the theme
cd wp-content/themes/
git clone <repository-url> soma
cd soma

# Install PHP dependencies
composer install --no-dev --optimize-autoloader

# Install Node dependencies
npm install

# Build production assets
npm run prod
```

### 2. Activate Theme

1. Go to **WordPress Admin** → **Appearance** → **Themes**
2. Activate **SOMA** theme
3. Install and activate required plugins (ACF PRO, CF7, Safe SVG)

### 3. Configure Settings

1. Go to **Custom Fields** → **Tools** → **Sync Available**
2. Sync all ACF field groups (13 groups)
3. Create pages and assign ACF flexible content blocks
4. Configure navigation menus under **Appearance** → **Menus**

### 4. Build Your First Page

```php
// Create a new page in WordPress admin
// Add ACF flexible content blocks from the "soma_blocks" field
// Blocks automatically render via page-builder.php

// Or use Elementor widgets:
// 1. Edit page with Elementor
// 2. Find "SOMA" widget category
// 3. Drag widgets onto page
```

---

## 🔧 Development

### Development Workflow

```bash
# Start development with hot reload
npm run watch

# Build for development (single)
npm run dev

# Build for production (minified)
npm run prod
```

### Code Quality

```bash
# Check coding standards
composer phpcs

# Auto-fix coding standards
composer phpcbf

# Run static analysis (Level 6-8)
composer phpstan

# Run all quality checks
composer validate
```

### Testing

```bash
# Run all tests (108 tests, 355 assertions)
composer test

# Run unit tests only
vendor/bin/phpunit --testsuite unit

# Run integration tests (requires WordPress test environment)
vendor/bin/phpunit --testsuite integration

# Run tests with coverage
vendor/bin/phpunit --coverage-html coverage/

# Test specific component
vendor/bin/phpunit --filter=PostTypeTest
```

### Project Structure

```
soma/
├── includes/           # PSR-4 classes (Soma\ namespace)
│   ├── Core/          # Theme core (Loader, Theme, Enums)
│   ├── PostTypes/     # Custom post types
│   ├── Taxonomies/    # Custom taxonomies
│   ├── API/           # REST API endpoints
│   ├── Elementor/     # Elementor widgets
│   ├── PageBuilder/   # ACF flexible content system
│   ├── CF7/           # Contact Form 7 integration
│   ├── Utils/         # Helper functions, Logger, Cache
│   └── Admin/         # Admin customizations
├── partials/          # ACF flexible content blocks
├── templates/         # Custom page templates
├── singles/           # Single post templates
├── acf-json/          # ACF field group sync
├── assets/            # CSS, JS, images
├── sass/              # SCSS source files
├── tests/             # PHPUnit tests
├── docs/              # Documentation
├── functions.php      # Theme initialization
├── composer.json      # PHP dependencies
└── package.json       # Node dependencies
```

---

## 🧪 Testing

### Running Tests

SOMA v3.0 includes comprehensive test coverage with PHPUnit.

**Quick test run:**

```bash
composer test
```

**Detailed testing:**

```bash
# Unit tests (24 for PostTypes, 39 for Taxonomies, etc.)
vendor/bin/phpunit --testsuite unit --testdox

# Integration tests (PageBuilder, Elementor)
vendor/bin/phpunit --testsuite integration --testdox

# Test specific component
vendor/bin/phpunit tests/Unit/PostTypes/PortfolioTest.php

# Coverage report
vendor/bin/phpunit --coverage-html coverage/
```

### Test Environment Setup

```bash
# Install WordPress test suite
bash tests/bin/install-wp-tests.sh wordpress_test root '' localhost latest

# Run tests
vendor/bin/phpunit

# Expected output: ✓ Tests 108/108, Assertions 355
```

### Quality Validation

```bash
# Complete validation pipeline
composer validate

# Individual checks
composer phpcs      # WordPress Coding Standards
composer phpstan    # Static Analysis Level 6-8
composer test       # PHPUnit Tests
```

---

## 🏛️ Architecture

### PSR-4 Namespace Structure

```php
Soma\
├── Core\
│   ├── Loader              # Component loader with priorities
│   ├── Theme               # Main theme class
│   └── Enums\
│       ├── PostType        # 4 custom post types
│       └── Taxonomy        # 3 custom taxonomies
├── PostTypes\
│   ├── Loader              # Post types loader (priority 20)
│   └── Types\
│       ├── Portfolio
│       ├── News
│       ├── Careers
│       └── TeamMembers
├── Taxonomies\
│   ├── Loader              # Taxonomies loader (priority 15)
│   ├── PortfolioTaxonomy
│   ├── NewsTaxonomy
│   └── TeamMembersTaxonomy
├── API\
│   ├── Loader              # API loader (priority 35)
│   └── Endpoints\
│       ├── NewsEndpoint
│       ├── CareersEndpoint
│       ├── PortfolioEndpoint
│       ├── DocumentsEndpoint
│       └── EventsEndpoint
├── Elementor\
│   ├── Loader              # Elementor loader (priority 30)
│   └── Widgets\
│       ├── Navbar
│       ├── Footer
│       ├── BusinessUnits
│       ├── Services
│       ├── TeamMembers
│       ├── NewsList
│       ├── Portfolio
│       └── ContactForm
├── PageBuilder\
│   ├── Loader              # PageBuilder loader (priority 25)
│   ├── BlockRegistry       # 53 blocks registered
│   └── BlockRenderer       # Rendering engine with caching
├── CF7\
│   ├── Loader              # CF7 loader (priority 30)
│   └── Validations         # Custom validation rules
└── Utils\
    ├── Helpers             # 24+ soma_* functions
    ├── Logger              # PSR-3 logging (priority 10)
    ├── Cache               # Tag-based caching (priority 10)
    └── Enums\
        ├── LogLevel        # 8 PSR-3 levels
        └── CacheTag        # Cache tag identifiers
```

### Component Loading System

All components implement `LoadableInterface`:

```php
interface LoadableInterface {
    public function init(): void;           // Initialize component
    public function get_priority(): int;    // Loading priority (10-50)
    public function should_load(): bool;    // Conditional loading
}
```

**Priority System:**

- 10: Core utilities (Logger, Cache) - loaded first
- 15: Taxonomies
- 20: Post Types
- 25: Page Builder
- 30: Integrations (Elementor, CF7)
- 35: REST API
- 40: Admin

### Helper Functions

24+ global `soma_*` functions available everywhere:

```php
// Logger (9 functions)
soma_log_error($message, $context);
soma_log_warning($message, $context);
soma_log_info($message, $context);

// Cache (6 functions)
soma_cache_get($key, $default);
soma_cache_set($key, $value, $ttl, $tags);
soma_cache_remember($key, $callback, $ttl, $tags);
soma_cache_invalidate_tags($tags);

// Post Types (4 functions)
soma_get_portfolio_items($args);
soma_get_news_items($args);
soma_get_careers_items($args);
soma_get_team_members($args);

// Templates (2 functions)
soma_get_template_part($slug, $name, $args);
soma_load_partial($partial_name, $data);

// ACF (2 functions)
soma_get_flexible_content($field_name, $post_id);
soma_render_flexible_content($blocks);

// Utilities (4 functions)
soma_is_dev();
soma_get_version();
soma_sanitize_class($class_name);
soma_asset_url($path);
```

---

## 📚 Documentation

Comprehensive documentation available in the `docs/` directory:

| Document | Lines | Description |
|----------|-------|-------------|
| **[DEVELOPMENT.md](docs/DEVELOPMENT.md)** | 1,093 | Complete developer guide |
| **[WIDGETS.md](docs/WIDGETS.md)** | 900 | Elementor widgets reference |
| **[HELPERS.md](docs/HELPERS.md)** | 850+ | Helper functions API |
| **[MIGRATION_FROM_V2.md](docs/MIGRATION_FROM_V2.md)** | 1,549 | v2.x → v3.0 upgrade guide |
| **[MIGRATION_PLAN.md](docs/MIGRATION_PLAN.md)** | 1,000+ | Modernization plan |
| **[ARCHITECTURE_VISION.md](docs/ARCHITECTURE_VISION.md)** | 800+ | Architecture overview |
| **[TESTING_GUIDE.md](docs/TESTING_GUIDE.md)** | 337 | Testing documentation |
| **[INTERNATIONALIZATION.md](docs/INTERNATIONALIZATION.md)** | 500+ | i18n guide and best practices |
| **Phase Completion Docs** | 2,000+ | Detailed phase reports |

**Total Documentation:** 5,500+ lines

### Quick Links

- **Getting Started:** [DEVELOPMENT.md § Getting Started](docs/DEVELOPMENT.md#getting-started)
- **Creating Widgets:** [WIDGETS.md § Development Guide](docs/WIDGETS.md#development-guide)
- **Helper Functions:** [HELPERS.md § Usage Examples](docs/HELPERS.md#usage-examples)
- **Upgrading from v2:** [MIGRATION_FROM_V2.md](docs/MIGRATION_FROM_V2.md)
- **Testing Guide:** [TESTING_GUIDE.md](docs/TESTING_GUIDE.md)
- **Internationalization:** [INTERNATIONALIZATION.md](docs/INTERNATIONALIZATION.md)

---

## 🔄 Migration

### Upgrading from v2.x

If you're running SOMA v2.0.7 or earlier, see the **[Migration Guide](docs/MIGRATION_FROM_V2.md)** for:

- ✅ Breaking changes and compatibility notes
- ✅ Step-by-step upgrade instructions
- ✅ Code migration patterns
- ✅ Testing and validation procedures
- ✅ Rollback plan (< 5 minutes)
- ✅ Troubleshooting common issues

**Critical Breaking Change:**

```php
// ❌ OLD (v2.0.7) - NO LONGER WORKS
global $pageBlock;
$title = $pageBlock['title'];

// ✅ NEW (v3.0.0) - REQUIRED
$block_content = get_query_var('soma_block_content');
$title = $block_content['title'] ?? '';
```

**Migration Time:** 2-4 hours (including testing)  
**Risk Level:** Low to Medium  
**Backward Compatibility:** 95%

---

## 🤝 Contributing

### Development Standards

- ✅ Follow **WordPress Coding Standards**
- ✅ Pass **PHPStan Level 6+** static analysis
- ✅ Write **unit tests** for new features
- ✅ Document **all public APIs**
- ✅ Use **type hints** and **strict types**
- ✅ Prefix **global functions** with `soma_`
- ✅ Use **enums** instead of magic strings
- ✅ Implement **singleton pattern** for services

### Code Quality Gates

All pull requests must pass:

```bash
composer phpcs      # 0 errors
composer phpstan    # Level 6+, 0 critical errors
composer test       # 100% passing tests
```

---

## 📄 License

**Proprietary License**

© SOMA 2020-2025. All rights reserved.

This theme is proprietary software developed for SOMA. Unauthorized copying, modification, distribution, or use is strictly prohibited.

---

## 🙏 Credits

### Development Team

- **Architecture & Modernization:** Miguel Colmenares
- **Original Theme:** [PIPE:CODE](https://pipe-code.github.io/)
- **Testing Infrastructure:** PHPUnit community
- **Quality Tools:** WordPress Coding Standards, PHPStan

### Technologies

- **WordPress** - Content management system
- **Advanced Custom Fields** - Custom fields framework
- **Elementor** - Page builder integration
- **Composer** - PHP dependency management
- **Webpack** - Asset bundling
- **PHPUnit** - Testing framework
- **PHPCS/PHPStan** - Code quality tools

---

## 📞 Support

### Resources

- **Documentation:** [docs/](docs/)
- **Issues:** GitHub Issues (for bug reports)
- **Logs:** `wp-content/uploads/soma-logs/soma.log`

### Common Tasks

```bash
# Check version
grep "Version:" wp-content/themes/soma/style.css

# View logs
tail -f wp-content/uploads/soma-logs/soma.log

# Clear caches
wp cache flush
soma_cache_flush(); # In code

# Run diagnostics
composer validate
vendor/bin/phpunit --testdox
```

---

**SOMA v3.0** - Modern WordPress Theme with PSR-4 Architecture  
Built with ❤️ by the SOMA Development Team
