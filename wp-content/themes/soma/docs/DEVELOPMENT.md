# SOMA Theme v3.0 - Developer Guide

**Version**: 3.0.0  
**Last Updated**: December 12, 2025  
**Audience**: Developers working with the SOMA WordPress theme

---

## Table of Contents

1. [Getting Started](#getting-started)
2. [Architecture Overview](#architecture-overview)
3. [Development Environment Setup](#development-environment-setup)
4. [Code Patterns & Best Practices](#code-patterns--best-practices)
5. [Component Development](#component-development)
6. [Testing](#testing)
7. [Building & Deployment](#building--deployment)
8. [Debugging](#debugging)
9. [Common Tasks](#common-tasks)
10. [Troubleshooting](#troubleshooting)

---

## Getting Started

### Quick Start

```bash
# Clone repository
git clone https://github.com/sanruiz/fibra.git
cd fibra/wp-content/themes/soma

# Install dependencies
composer install
npm install

# Build assets
npm run dev

# Run tests
vendor/bin/phpunit
```

### System Requirements

- **PHP**: 8.1 or higher
- **WordPress**: 6.0 or higher
- **Node.js**: 18.x or higher
- **Composer**: 2.x
- **MySQL**: 5.7 or higher

### Required Plugins

- **Advanced Custom Fields PRO** (v6.0+)
- **Contact Form 7** (v5.8+)
- **Elementor** (v3.18+)
- **WP Multilang** (v2.4+)
- **Safe SVG** (v2.2+)

---

## Architecture Overview

### PSR-4 Namespace Structure

```
Soma\
├── Core\               # Core framework components
│   ├── Loader.php      # Component loader with priority system
│   ├── Theme.php       # Main theme class (singleton)
│   ├── Interfaces\     # Contracts
│   │   └── LoadableInterface.php
│   └── Enums\          # Type-safe enumerations
│       ├── PostType.php
│       └── Taxonomy.php
│
├── PostTypes\          # Custom Post Types
│   ├── Loader.php      # Priority: 20
│   └── Types\
│       ├── Portfolio.php
│       ├── News.php
│       ├── Careers.php
│       └── TeamMembers.php
│
├── Taxonomies\         # Custom Taxonomies
│   ├── Loader.php      # Priority: 15
│   ├── TeamMembersTaxonomy.php
│   ├── PortfolioTaxonomy.php
│   └── DocumentsTaxonomy.php
│
├── CustomFields\       # ACF Field Groups
│   └── Loader.php      # Priority: 25
│
├── API\                # REST API Endpoints
│   ├── Loader.php      # Priority: 35
│   └── Endpoints\
│       ├── NewsEndpoint.php
│       ├── CareersEndpoint.php
│       ├── PortfolioEndpoint.php
│       ├── DocumentsEndpoint.php
│       └── EventsEndpoint.php
│
├── PageBuilder\        # ACF Flexible Content System
│   ├── Loader.php      # Priority: 25
│   ├── BlockRegistry.php   # 53 blocks registered
│   └── BlockRenderer.php   # Rendering engine
│
├── Elementor\          # Elementor Integration
│   ├── Loader.php      # Priority: 30
│   └── Widgets\
│       ├── Navbar.php
│       ├── Footer.php
│       ├── BusinessUnits.php
│       └── ... (8+ widgets)
│
├── CF7\                # Contact Form 7 Integration
│   ├── Loader.php      # Priority: 30
│   └── Validations.php
│
├── Admin\              # WordPress Admin Customizations
│   ├── Loader.php      # Priority: 40
│   └── Customizer\
│       └── ThemeSettings.php
│
└── Utils\              # Utilities & Helpers
    ├── Helpers.php     # 40+ soma_* global functions
    ├── Logger.php      # PSR-3 logging (singleton)
    ├── Cache.php       # Tag-based caching (singleton)
    ├── CacheInvalidationManager.php
    └── Enums\
        ├── LogLevel.php
        └── CacheTag.php
```

### LoadableInterface Pattern

All major components implement the `LoadableInterface`:

```php
<?php
namespace Soma\Core\Interfaces;

interface LoadableInterface {
    public function init(): void;           // Initialize component
    public function get_priority(): int;    // Loading order (10-50)
    public function should_load(): bool;    // Conditional loading
}
```

### Priority System

Components load in order based on priority:

| Priority | Component | Purpose |
|----------|-----------|---------|
| 10 | Utilities | Core functions needed by all components |
| 15 | Taxonomies | Must load before Post Types |
| 20 | Post Types | Custom content types |
| 25 | Custom Fields, PageBuilder | ACF integration |
| 30 | Elementor, CF7 | Third-party integrations |
| 35 | REST API | Depends on Post Types |
| 40 | Admin | UI customizations |

---

## Development Environment Setup

### 1. Local Development

**Recommended Stack:**
- [Local by Flywheel](https://localwp.com/) or [MAMP](https://www.mamp.info/)
- PHP 8.1+
- MySQL 5.7+
- WordPress 6.0+

### 2. Install Dependencies

```bash
cd wp-content/themes/soma

# PHP dependencies
composer install

# JavaScript dependencies
npm install
```

### 3. Configure Quality Tools

**PHPCS** (WordPress Coding Standards):
```bash
vendor/bin/phpcs --config-set installed_paths vendor/wp-coding-standards/wpcs
```

**PHPStan** (Static Analysis):
```bash
vendor/bin/phpstan analyse includes/ --level 6
```

### 4. Build Assets

```bash
# Development mode (with watch)
npm run watch

# Development build
npm run dev

# Production build
npm run prod
```

### 5. Test Environment

```bash
# Install WordPress test suite
cd tests/bin
./install-wp-tests.sh soma_test root '' localhost latest

# Install test plugins
./install-acf-for-tests.sh
./install-cf7-for-tests.sh
./install-elementor-for-tests.sh

# Run tests
cd ../..
vendor/bin/phpunit
```

---

## Code Patterns & Best Practices

### Singleton Pattern

All major components use the Singleton pattern:

```php
<?php
namespace Soma\PostTypes\Types;

class Portfolio {
    private static ?Portfolio $instance = null;

    public static function instance(): Portfolio {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    private function __construct() {
        $this->init();
    }

    private function __clone() {}

    public function __wakeup() {
        throw new \Exception('Cannot unserialize singleton');
    }
}
```

### First-Class Callables (PHP 8.1+)

Use modern callable syntax for WordPress hooks:

```php
// ✅ GOOD (PHP 8.1+)
add_action('init', $this->register(...), 0);
add_filter('the_content', $this->filter_content(...));

// ❌ AVOID (Old style)
add_action('init', array($this, 'register'), 0);
```

### Type Declarations

Always use strict typing:

```php
<?php
declare(strict_types=1);

namespace Soma\PostTypes\Types;

class Portfolio {
    private const POST_TYPE = 'portfolio';
    
    public function register(): void {
        register_post_type(self::POST_TYPE, $this->get_args());
    }
    
    private function get_args(): array {
        return [
            'public' => true,
            'has_archive' => true,
        ];
    }
}
```

### Enums Over Magic Strings

Use type-safe enums instead of string constants:

```php
// ✅ GOOD
use Soma\Core\Enums\PostType;

$posts = get_posts([
    'post_type' => PostType::PORTFOLIO->value(),
]);

// ❌ AVOID
$posts = get_posts([
    'post_type' => 'portfolio',
]);
```

### Helper Functions

Use centralized helper functions with `soma_` prefix:

```php
// Logging
soma_log_error('Something went wrong', ['context' => 'data']);
soma_log_info('Operation successful');

// Caching
$data = soma_cache_remember('key', function() {
    return expensive_operation();
}, 3600, [CacheTag::POST_TYPE]);

// Post Type helpers
$portfolio = soma_get_portfolio_items(['posts_per_page' => 10]);
```

### WordPress Query Vars (PageBuilder)

Access block data via query vars (not globals):

```php
// ✅ GOOD (v3.0+)
$block_counter = get_query_var('soma_block_counter');
$block_content = get_query_var('soma_block_content');
$block_layout = get_query_var('soma_block_layout');

// ❌ DEPRECATED (v2.x)
global $pageBlock;  // Don't use!
```

### CSS Variables

Use CSS custom properties for all styling:

```php
// In partial template
<div class="component" style="
    --component-bg: var(--soma-bg-light);
    --component-spacing: var(--soma-spacing-lg);
">
```

```scss
// In SCSS
.component {
    background: var(--soma-bg-light);
    padding: var(--soma-spacing-lg);
    color: var(--soma-text-primary);
}
```

---

## Component Development

### Creating a New Post Type

**1. Create the class:**

```php
<?php
// includes/PostTypes/Types/Events.php
namespace Soma\PostTypes\Types;

use Soma\Core\Enums\PostType;

class Events {
    private static ?Events $instance = null;

    public static function instance(): Events {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    private function __construct() {
        $this->init();
    }

    private function init(): void {
        add_action('init', $this->register(...), 0);
    }

    public function register(): void {
        register_post_type(
            PostType::EVENTS->value(),
            $this->get_args()
        );
    }

    private function get_args(): array {
        return [
            'labels' => [
                'name' => __('Events', 'soma'),
                'singular_name' => __('Event', 'soma'),
            ],
            'public' => true,
            'has_archive' => true,
            'supports' => ['title', 'editor', 'thumbnail'],
            'rewrite' => ['slug' => 'events'],
        ];
    }

    public function get_post_type(): string {
        return PostType::EVENTS->value();
    }
}
```

**2. Add to enum:**

```php
// includes/Core/Enums/PostType.php
enum PostType: string {
    case PORTFOLIO = 'portfolio';
    case NEWS = 'news';
    case CAREERS = 'careers';
    case TEAM_MEMBERS = 'team-members';
    case EVENTS = 'events';  // Add this
}
```

**3. Register in Loader:**

```php
// includes/PostTypes/Loader.php
private function load_post_types(): void {
    Types\Portfolio::instance();
    Types\News::instance();
    Types\Careers::instance();
    Types\TeamMembers::instance();
    Types\Events::instance();  // Add this
}
```

**4. Create tests:**

```php
// tests/Unit/PostTypes/EventsTest.php
<?php
namespace Soma\Tests\Unit\PostTypes;

use Soma\PostTypes\Types\Events;
use WP_UnitTestCase;

class EventsTest extends WP_UnitTestCase {
    public function test_singleton_instance(): void {
        $instance1 = Events::instance();
        $instance2 = Events::instance();
        
        $this->assertSame($instance1, $instance2);
    }

    public function test_post_type_registered(): void {
        $this->assertTrue(
            post_type_exists('events'),
            'Events post type should be registered'
        );
    }
}
```

### Creating an Elementor Widget

**1. Create widget class:**

```php
<?php
// includes/Elementor/Widgets/EventsList.php
namespace Soma\Elementor\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;

class EventsList extends Widget_Base {
    public function get_name(): string {
        return 'soma_events_list';
    }

    public function get_title(): string {
        return __('SOMA Events List', 'soma');
    }

    public function get_icon(): string {
        return 'eicon-posts-grid';
    }

    public function get_categories(): array {
        return ['soma'];
    }

    protected function register_controls(): void {
        $this->start_controls_section(
            'content_section',
            [
                'label' => __('Content', 'soma'),
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'posts_per_page',
            [
                'label' => __('Posts Per Page', 'soma'),
                'type' => Controls_Manager::NUMBER,
                'default' => 6,
            ]
        );

        $this->end_controls_section();
    }

    protected function render(): void {
        $settings = $this->get_settings_for_display();
        
        $events = get_posts([
            'post_type' => 'events',
            'posts_per_page' => $settings['posts_per_page'],
        ]);

        if (empty($events)) {
            return;
        }

        echo '<div class="soma-events-list">';
        foreach ($events as $event) {
            echo '<div class="event-item">';
            echo '<h3>' . esc_html($event->post_title) . '</h3>';
            echo '</div>';
        }
        echo '</div>';
    }
}
```

**2. Register widget:**

```php
// includes/Elementor/Loader.php
private function register_widgets(): void {
    \Elementor\Plugin::instance()->widgets_manager->register(
        new Widgets\EventsList()
    );
}
```

### Creating a PageBuilder Block (ACF Partial)

**1. Create ACF field group** (via ACF UI or code)

**2. Create partial template:**

```php
<?php
// partials/EventsGrid.php
if (!defined('ABSPATH')) exit;

// Access block data via WordPress query vars
$block_counter = get_query_var('soma_block_counter');
$block_content = get_query_var('soma_block_content');

$title = $block_content['title'] ?? '';
$events = $block_content['events'] ?? [];
?>

<section class="events-grid-partial" data-block="<?php echo esc_attr($block_counter); ?>">
    <?php if ($title): ?>
        <h2><?php echo esc_html($title); ?></h2>
    <?php endif; ?>
    
    <div class="events-grid">
        <?php foreach ($events as $event): ?>
            <div class="event-card">
                <h3><?php echo esc_html($event->post_title); ?></h3>
            </div>
        <?php endforeach; ?>
    </div>
</section>
```

**3. Register in BlockRegistry:**

```php
// includes/PageBuilder/BlockRegistry.php
private function register_default_blocks(): void {
    // ... existing blocks ...
    
    $this->register_block(
        'EventsGrid',           // Layout name in ACF
        'events_grid_content',  // ACF field group key
        'EventsGrid'            // Partial filename
    );
}
```

**4. Create SCSS:**

```scss
// sass/partials/_EventsGrid.scss
.events-grid-partial {
    padding: var(--soma-spacing-2xl) 0;
    
    .events-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
        gap: var(--soma-grid-gap);
    }
    
    .event-card {
        background: var(--soma-bg-white);
        padding: var(--soma-spacing-lg);
        border-radius: var(--soma-border-radius);
        box-shadow: var(--soma-shadow-md);
    }
}
```

**5. Import SCSS:**

```scss
// sass/main.scss
// ... existing imports ...
@import 'partials/EventsGrid';
```

**6. Add JS if needed:**

```javascript
// js/components/eventsGrid.js
export function eventsGridHandler($element) {
    // Your interactive code here
    console.log('Events grid initialized');
}

// js/main.js
import { eventsGridHandler } from './components/eventsGrid';

if ($('.events-grid-partial').length > 0) {
    eventsGridHandler($('.events-grid-partial'));
}
```

---

## Testing

### Running Tests

```bash
# All tests
vendor/bin/phpunit

# Without coverage
vendor/bin/phpunit --no-coverage

# Specific test file
vendor/bin/phpunit tests/Unit/PostTypes/PortfolioTest.php

# Specific test method
vendor/bin/phpunit --filter test_singleton_instance

# With coverage report
vendor/bin/phpunit --coverage-html coverage/
```

### Writing Unit Tests

```php
<?php
namespace Soma\Tests\Unit\Utils;

use Soma\Utils\Logger;
use Soma\Utils\Enums\LogLevel;
use WP_UnitTestCase;

class LoggerTest extends WP_UnitTestCase {
    private Logger $logger;

    public function setUp(): void {
        parent::setUp();
        $this->logger = Logger::instance();
    }

    public function test_singleton_instance(): void {
        $instance1 = Logger::instance();
        $instance2 = Logger::instance();
        
        $this->assertSame($instance1, $instance2);
    }

    public function test_log_levels(): void {
        // Test that all PSR-3 log levels work
        $levels = [
            LogLevel::EMERGENCY,
            LogLevel::ALERT,
            LogLevel::CRITICAL,
            LogLevel::ERROR,
            LogLevel::WARNING,
            LogLevel::NOTICE,
            LogLevel::INFO,
            LogLevel::DEBUG,
        ];

        foreach ($levels as $level) {
            $this->logger->log($level, 'Test message');
            $this->assertTrue(true); // Should not throw
        }
    }
}
```

### Integration Tests

```php
<?php
namespace Soma\Tests\Integration;

use WP_UnitTestCase;

class PostTypesTest extends WP_UnitTestCase {
    public function test_all_post_types_registered(): void {
        $post_types = ['portfolio', 'news', 'careers', 'team-members'];
        
        foreach ($post_types as $post_type) {
            $this->assertTrue(
                post_type_exists($post_type),
                "Post type {$post_type} should be registered"
            );
        }
    }

    public function test_portfolio_can_be_created(): void {
        $post_id = $this->factory->post->create([
            'post_type' => 'portfolio',
            'post_title' => 'Test Portfolio Item',
        ]);

        $this->assertIsInt($post_id);
        $this->assertGreaterThan(0, $post_id);
        
        $post = get_post($post_id);
        $this->assertEquals('portfolio', $post->post_type);
    }
}
```

---

## Building & Deployment

### Development Build

```bash
npm run dev
```

**Output:**
- `js/main.bundle.js` (not minified)
- `css/main.bundle.css` (expanded)
- Source maps included

### Production Build

```bash
npm run prod
```

**Output:**
- `js/main.bundle.js` (minified)
- `css/main.bundle.css` (minified)
- No source maps
- Optimized assets (~180 KiB bundle)

### Asset Enqueuing

Assets are automatically enqueued in `functions.php`:

```php
// CSS
wp_enqueue_style(
    'soma-main',
    get_template_directory_uri() . '/css/main.bundle.css',
    [],
    '3.0.0'
);

// JavaScript
wp_enqueue_script(
    'soma-main',
    get_template_directory_uri() . '/js/main.bundle.js',
    ['jquery'],
    '3.0.0',
    true
);
```

### Deployment Checklist

- [ ] Run production build: `npm run prod`
- [ ] Run all tests: `vendor/bin/phpunit`
- [ ] Check PHPCS: `vendor/bin/phpcs`
- [ ] Check PHPStan: `vendor/bin/phpstan analyse`
- [ ] Clear all caches
- [ ] Test on staging environment
- [ ] Backup database and files
- [ ] Deploy to production
- [ ] Clear production caches
- [ ] Test critical paths

---

## Debugging

### Debug Mode

Enable WordPress debug mode in `wp-config.php`:

```php
define('WP_DEBUG', true);
define('WP_DEBUG_LOG', true);
define('WP_DEBUG_DISPLAY', false);
define('SCRIPT_DEBUG', true);
```

### SOMA Logger

Use the built-in logger for debugging:

```php
use Soma\Utils\Enums\LogLevel;

// Quick logging
soma_log_error('Something went wrong', ['data' => $data]);
soma_log_info('Operation completed');
soma_log_debug('Variable value', ['var' => $value]);

// Full logger instance
$logger = \Soma\Utils\Logger::instance();
$logger->log(LogLevel::WARNING, 'Warning message', ['context']);
```

**Logs location:** `wp-content/uploads/soma-logs/soma.log`

### Cache Debugging

```php
// Clear all caches
soma_cache_flush();

// Invalidate specific tags
soma_cache_invalidate_tags([CacheTag::POST_TYPE]);

// Check cache stats
$renderer = \Soma\PageBuilder\BlockRenderer::instance();
$stats = $renderer->get_stats();
print_r($stats);
```

### Query Monitor

Install [Query Monitor](https://wordpress.org/plugins/query-monitor/) plugin for:
- Database queries analysis
- Hook execution order
- PHP errors and warnings
- HTTP requests
- Script dependencies

### Browser DevTools

**JavaScript debugging:**
```javascript
// Add breakpoints in source maps
// Check: Sources → webpack://soma/js/main.js
```

**CSS debugging:**
```css
/* Use CSS variables inspector
 * Check: Elements → Computed → filter: --soma
 */
```

---

## Common Tasks

### Add a New REST Endpoint

**1. Create endpoint class:**

```php
<?php
// includes/API/Endpoints/EventsEndpoint.php
namespace Soma\API\Endpoints;

class EventsEndpoint {
    private static ?EventsEndpoint $instance = null;

    public static function instance(): EventsEndpoint {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    private function __construct() {
        add_action('rest_api_init', $this->register(...));
    }

    public function register(): void {
        register_rest_route('soma/v1', '/events', [
            'methods' => 'GET',
            'callback' => $this->get_events(...),
            'permission_callback' => '__return_true',
        ]);
    }

    public function get_events(\WP_REST_Request $request): \WP_REST_Response {
        $events = get_posts([
            'post_type' => 'events',
            'posts_per_page' => $request->get_param('per_page') ?? 10,
        ]);

        return new \WP_REST_Response($events, 200);
    }
}
```

**2. Register in API Loader:**

```php
// includes/API/Loader.php
private function load_endpoints(): void {
    Endpoints\NewsEndpoint::instance();
    Endpoints\EventsEndpoint::instance();  // Add this
}
```

### Add a CSS Variable

**1. Add to variables.css:**

```css
:root {
    /* Add to appropriate category */
    --soma-color-brand-tertiary: #2ECC71;
}
```

**2. Use in SCSS:**

```scss
.my-component {
    background: var(--soma-color-brand-tertiary);
}
```

### Invalidate Cache on Post Save

```php
add_action('save_post_portfolio', function($post_id) {
    soma_cache_invalidate_tags([CacheTag::POST_TYPE]);
});
```

---

## Troubleshooting

### Issue: Tests Showing Errors

**Problem:** Error messages appear during tests

**Solution:**
```php
// tests/bootstrap.php has SOMA_TESTING constant
// Logger automatically suppresses error_log during tests
```

### Issue: PHPCS Errors

**Problem:** WordPress Coding Standards violations

**Solution:**
```bash
# Auto-fix most issues
vendor/bin/phpcbf

# Check remaining issues
vendor/bin/phpcs
```

### Issue: ACF Fields Not Loading

**Problem:** Custom fields not appearing

**Solution:**
1. Check ACF JSON sync: Settings → Custom Fields → Sync
2. Verify field group location rules
3. Check `acf-json/` directory permissions
4. Clear all caches

### Issue: Webpack Build Errors

**Problem:** `npm run dev` fails

**Solution:**
```bash
# Clear cache
rm -rf node_modules
npm cache clean --force

# Reinstall
npm install

# Legacy OpenSSL (for Node 18+)
export NODE_OPTIONS=--openssl-legacy-provider
npm run dev
```

### Issue: Partial Not Rendering

**Problem:** ACF block doesn't display

**Solution:**
1. Check BlockRegistry has block registered
2. Verify partial file exists in `partials/`
3. Check ACF layout name matches registered name
4. Clear PageBuilder cache: `soma_cache_invalidate_tags([CacheTag::PAGE_BUILDER])`

### Issue: Elementor Widget Missing

**Problem:** Widget not appearing in Elementor panel

**Solution:**
1. Check widget is registered in Elementor\Loader
2. Verify widget class extends `\Elementor\Widget_Base`
3. Check `get_categories()` returns `['soma']`
4. Clear Elementor cache: Tools → Regenerate CSS & Data

---

## Additional Resources

### Documentation

- **Architecture Vision**: `docs/ARCHITECTURE_VISION.md`
- **Migration Plan**: `docs/MIGRATION_PLAN.md`
- **CSS Variables**: `docs/CSS_VARIABLES.md`
- **Testing Guide**: `docs/TESTING_GUIDE.md`
- **Phase Completion Reports**: `docs/PHASE_*_COMPLETION.md`

### External Resources

- [WordPress Coding Standards](https://developer.wordpress.org/coding-standards/wordpress-coding-standards/php/)
- [ACF Documentation](https://www.advancedcustomfields.com/resources/)
- [Elementor Developer Docs](https://developers.elementor.com/)
- [PSR-3 Logger Interface](https://www.php-fig.org/psr/psr-3/)
- [PSR-4 Autoloading](https://www.php-fig.org/psr/psr-4/)

### Support

- **GitHub Issues**: https://github.com/sanruiz/fibra/issues
- **Project Board**: https://github.com/users/sanruiz/projects/4

---

**Document Version**: 1.0  
**Last Updated**: December 12, 2025  
**Maintainer**: Miguel Colmenares
