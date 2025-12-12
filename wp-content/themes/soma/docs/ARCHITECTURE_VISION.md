# Soma v3.0 - Architecture Vision

## Executive Summary

This document outlines the target architecture for Soma v3.0, a modern WordPress theme that combines the power of ACF flexible content with PSR-4 standards, Elementor integration, and enterprise-grade development practices.

---

## Design Principles

### 1. **Backward Compatibility First**
- Existing ACF flexible content pages continue to work
- No breaking changes to public APIs
- Graceful migration path from v2.x

### 2. **Modern PHP Standards**
- PSR-4 autoloading with `Soma\` namespace
- PHP 8.1+ features (enums, match, readonly, first-class callables)
- Singleton pattern for core services
- LoadableInterface for consistent component loading

### 3. **Dual Page Builder System**
- **ACF Flexible Content**: Existing power users, complex custom layouts
- **Elementor**: New users, rapid page building, client-friendly

### 4. **Quality-Driven Development**
- 80%+ test coverage requirement
- PHPCS + PHPStan Level 8 compliance
- Automated validation pipeline
- Git pre-commit hooks

### 5. **Performance Optimization**
- Tag-based cache system
- Lazy loading components
- CSS variables (no runtime processing)
- Minimal database queries

---

## Target Directory Structure

```
wp-content/themes/soma/
├── .github/
│   └── copilot-instructions.md          # AI agent documentation
├── assets/
│   ├── css/
│   │   ├── variables.css                # CSS custom properties (200+ tokens)
│   │   ├── theme.css                    # Main compiled styles
│   │   └── widgets/                     # Elementor widget styles
│   │       ├── navbar.css
│   │       ├── footer.css
│   │       ├── business-units.css
│   │       └── ...
│   ├── js/
│   │   ├── theme.js                     # Main JavaScript
│   │   └── components/                  # Modular JS components
│   └── images/
├── docs/
│   ├── MIGRATION_PLAN.md                # This migration plan
│   ├── ARCHITECTURE.md                  # System architecture
│   ├── DEVELOPMENT.md                   # Developer guide
│   ├── WIDGETS.md                       # Elementor widgets reference
│   ├── HELPERS.md                       # Helper functions API
│   ├── CSS_VARIABLES.md                 # Design system tokens
│   └── MIGRATION_FROM_V2.md            # Upgrade guide
├── includes/
│   ├── Core/
│   │   ├── Loader.php                   # Component loader (singleton)
│   │   ├── Theme.php                    # Main theme class (singleton)
│   │   ├── Interfaces/
│   │   │   └── LoadableInterface.php    # Component contract
│   │   └── Enums/
│   │       └── PostType.php             # Post type identifiers
│   ├── PostTypes/
│   │   ├── Loader.php                   # Post types loader
│   │   └── Types/
│   │       ├── Portfolio.php
│   │       ├── News.php
│   │       ├── Careers.php
│   │       └── TeamMembers.php
│   ├── CustomFields/
│   │   ├── Loader.php                   # ACF fields loader
│   │   └── Fields/
│   │       ├── PortfolioCustomFields.php
│   │       ├── NewsCustomFields.php
│   │       ├── CareersCustomFields.php
│   │       └── TeamMembersCustomFields.php
│   ├── API/
│   │   ├── Loader.php                   # REST API loader
│   │   └── Endpoints/
│   │       ├── NewsEndpoint.php
│   │       ├── CareersEndpoint.php
│   │       ├── PortfolioEndpoint.php
│   │       ├── DocumentsEndpoint.php
│   │       └── EventsEndpoint.php
│   ├── Elementor/
│   │   ├── Loader.php                   # Elementor integration
│   │   └── Widgets/
│   │       ├── Navbar.php
│   │       ├── Footer.php
│   │       ├── BusinessUnits.php
│   │       ├── Services.php
│   │       ├── TeamMembers.php
│   │       ├── NewsList.php
│   │       ├── Portfolio.php
│   │       └── ContactForm.php
│   ├── PageBuilder/
│   │   ├── Loader.php                   # ACF page builder
│   │   ├── BlockRegistry.php            # Block mappings
│   │   └── BlockRenderer.php            # Rendering engine
│   ├── CF7/
│   │   ├── Loader.php                   # Contact Form 7 integration
│   │   └── Validations.php              # Custom validation rules
│   ├── Admin/
│   │   ├── Loader.php                   # Admin customizations
│   │   └── Customizer/
│   │       └── ThemeSettings.php
│   └── Utils/
│       ├── Helpers.php                  # Global soma_* functions
│       ├── Logger.php                   # PSR-3 logging (singleton)
│       ├── Cache.php                    # Caching utilities (singleton)
│       ├── CacheInvalidationManager.php # Auto-invalidation
│       └── Enums/
│           ├── LogLevel.php             # 8 PSR-3 levels
│           └── CacheTag.php             # Cache tag identifiers
├── partials/                            # ACF flexible content blocks (preserved)
│   ├── Navbar.php                       # Legacy partial (backward compat)
│   ├── Footer.php
│   ├── BusinessUnits.php
│   └── ... (50+ partials)
├── templates/                           # Custom page templates
│   ├── business-unit-template.php
│   ├── navigationsidebar-template.php
│   └── ...
├── singles/                             # Single post templates
│   ├── news.php
│   ├── careers.php
│   └── team-members.php
├── tests/
│   ├── Unit/                            # PHPUnit unit tests
│   │   ├── Core/
│   │   ├── PostTypes/
│   │   ├── CustomFields/
│   │   ├── API/
│   │   ├── Elementor/
│   │   └── Utils/
│   ├── Integration/                     # Integration tests
│   ├── Mocks/                           # Test mocks
│   │   └── SimpleMocks.php
│   └── bootstrap.php                    # Test setup
├── scripts/
│   ├── validate-theme.sh                # Complete validation
│   └── update-version.sh                # Version management
├── languages/
│   └── soma.pot                         # Translation template
├── acf-json/                            # ACF field sync (preserved)
├── sass/                                # SCSS sources (preserved)
│   ├── main.scss
│   ├── _general.scss
│   └── partials/
├── functions.php                        # Theme initialization
├── style.css                            # WordPress theme header
├── composer.json                        # PHP dependencies
├── package.json                         # Node dependencies
├── phpstan.neon                         # Static analysis config
├── phpcs.xml                            # Coding standards config
├── phpunit.xml                          # Testing config
├── webpack.config.js                    # Asset compilation
└── README.md                            # Getting started
```

---

## Component Loading System

### LoadableInterface Pattern

All major components implement the `LoadableInterface`:

```php
<?php
namespace Soma\Core\Interfaces;

interface LoadableInterface
{
    /**
     * Initialize the component
     */
    public function init(): void;

    /**
     * Get component loading priority
     * Lower = earlier (10-50 range)
     */
    public function get_priority(): int;

    /**
     * Check if component should load
     */
    public function should_load(): bool;
}
```

### Priority System

| Priority | Component Type | Examples |
|----------|----------------|----------|
| 10 | Core | Theme, Loader |
| 15 | Custom Fields | ACF field groups |
| 20 | Post Types | Portfolio, News, Careers, Team |
| 25 | Page Builder | ACF flexible content |
| 30 | Integrations | Elementor, CF7 |
| 35 | API | REST endpoints |
| 40 | Admin | Customizer, settings |
| 45 | Utils | Logger, Cache (load early) |

### Example Component

```php
<?php
namespace Soma\PostTypes;

use Soma\Core\Interfaces\LoadableInterface;

class Loader implements LoadableInterface
{
    private static ?Loader $instance = null;

    public static function instance(): Loader
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    private function __construct() {}

    public function init(): void
    {
        $this->load_post_types();
    }

    public function get_priority(): int
    {
        return 20; // Post types priority
    }

    public function should_load(): bool
    {
        return true; // Always load post types
    }

    private function load_post_types(): void
    {
        \Soma\PostTypes\Types\Portfolio::instance();
        \Soma\PostTypes\Types\News::instance();
        \Soma\PostTypes\Types\Careers::instance();
        \Soma\PostTypes\Types\TeamMembers::instance();
    }
}
```

---

## Dual Page Builder Strategy

### ACF Flexible Content (Preserved)

**When to use:**
- Complex custom layouts
- Power users who understand field groups
- Existing pages from v2.x
- Maximum flexibility needed

**Implementation:**
```php
// In page.php
global $pageBuilder;
$pageBuilder = get_field('soma_blocks');
get_template_part('page-builder');

// page-builder.php (enhanced)
$builder = \Soma\PageBuilder\BlockRenderer::instance();
$builder->render($pageBuilder);
```

### Elementor (New)

**When to use:**
- Visual page building
- Client-facing content editing
- Rapid prototyping
- Standard layouts

**Implementation:**
- Custom Elementor widgets in `includes/Elementor/Widgets/`
- Widget category: 'soma'
- ACF data integration where needed
- Typography controls via Elementor

**Coexistence Strategy:**
- Both systems work independently
- ACF flexible content for structure
- Elementor for content pages
- No conflicts between systems

---

## CSS Variables System

### Design Token Categories

```css
:root {
    /* Brand Colors */
    --soma-primary: #0C1C3C;
    --soma-secondary: #4E7B95;
    --soma-accent: #E63946;
    
    /* Text Colors */
    --soma-text-primary: #171717;
    --soma-text-secondary: #666666;
    --soma-text-light: #999999;
    
    /* Background Colors */
    --soma-bg-white: #FFFFFF;
    --soma-bg-light: #F5F5F5;
    --soma-bg-dark: #1A1A1A;
    
    /* Typography */
    --soma-font-primary: 'Roboto', sans-serif;
    --soma-font-secondary: 'Roboto';
    --soma-font-size-h1: 2.5rem;
    --soma-font-size-h2: 2rem;
    --soma-font-size-body: 1rem;
    --soma-font-weight-normal: 400;
    --soma-font-weight-medium: 500;
    --soma-font-weight-semibold: 600;
    
    /* Spacing System */
    --soma-spacing-xs: 0.5rem;    /* 8px */
    --soma-spacing-sm: 1rem;      /* 16px */
    --soma-spacing-md: 1.5rem;    /* 24px */
    --soma-spacing-lg: 2rem;      /* 32px */
    --soma-spacing-xl: 3rem;      /* 48px */
    --soma-spacing-2xl: 4rem;     /* 64px */
    
    /* Layout */
    --soma-container-width: 1200px;
    --soma-border-radius: 8px;
    --soma-border-radius-sm: 4px;
    --soma-border-radius-lg: 12px;
    
    /* Effects */
    --soma-shadow-sm: 0 2px 4px rgba(0, 0, 0, 0.1);
    --soma-shadow-md: 0 4px 8px rgba(0, 0, 0, 0.15);
    --soma-shadow-lg: 0 8px 16px rgba(0, 0, 0, 0.2);
    --soma-transition: all 0.3s ease;
    
    /* Grid */
    --soma-grid-gap: 2rem;
    --soma-grid-gap-sm: 1rem;
    --soma-grid-gap-lg: 3rem;
}
```

**Naming Convention**: `--soma-[category]-[property]-[variant]`

---

## Helper Functions System

### Centralized Global Functions

All helper functions in `includes/Utils/Helpers.php`:

```php
// Logger helpers
soma_get_logger()
soma_log_error($message, $context)
soma_log_warning($message, $context)
soma_log_info($message, $context)
soma_log_debug($message, $context)

// Cache helpers
soma_cache_get($key, $default)
soma_cache_set($key, $value, $ttl, $tags)
soma_cache_remember($key, $callback, $ttl, $tags)
soma_cache_invalidate_tags($tags)
soma_cache_flush()

// Post type helpers
soma_get_portfolio_items($args)
soma_get_news_items($args)
soma_get_careers_items($args)
soma_get_team_members($args)

// Template helpers
soma_get_template_part($slug, $name)
soma_load_partial($partial_name, $data)

// ACF helpers
soma_get_flexible_content($field_name, $post_id)
soma_render_flexible_content($blocks)
```

---

## Testing Strategy

### Unit Tests (80% Coverage Target)

**Critical Components:**
- Core\Loader
- Core\Theme
- All PostTypes
- All CustomFields
- All API Endpoints
- All Elementor Widgets
- Utils\Logger
- Utils\Cache
- PageBuilder\BlockRenderer

### Integration Tests

**Test Scenarios:**
- ACF field registration
- Post type registration
- REST API functionality
- Elementor widget rendering
- Cache invalidation
- Hook integration

### Quality Gates (Mandatory)

```bash
# All must pass before commit
vendor/bin/phpcbf                    # Auto-fix
vendor/bin/phpcs                     # 0 errors
vendor/bin/phpstan analyse           # Level 8, 0 errors
vendor/bin/phpunit                   # 80%+ coverage
scripts/validate-theme.sh            # Complete validation
```

---

## Performance Targets

### Page Load Time
- **Homepage**: < 2 seconds
- **Archive Pages**: < 2.5 seconds  
- **Single Posts**: < 1.5 seconds

### Database Queries
- **Homepage**: < 30 queries
- **Archive Pages**: < 40 queries
- **Single Posts**: < 20 queries

### Cache Hit Ratio
- **Target**: > 90% for repeated requests
- **Invalidation**: < 100ms per tag
- **Storage**: Object cache > transients

---

## Security Considerations

### Input Validation
- All user input sanitized
- ACF handles field sanitization
- REST API parameter validation
- Nonce verification for forms

### Output Escaping
- All dynamic output escaped
- Context-aware escaping functions
- XSS prevention in templates
- SQL injection prevention (prepared statements)

### Authentication & Authorization
- Proper capability checks
- REST API permission callbacks
- Admin area restrictions
- File upload validation

---

## Backward Compatibility Guarantee

### v2.x to v3.0 Migration

**Zero Breaking Changes:**
- All ACF flexible content layouts work
- All existing partials function
- All custom post types preserved
- All REST endpoints compatible
- All CF7 forms work

**Enhanced Features:**
- Elementor widgets as alternative to partials
- Better performance with caching
- Improved code organization
- Modern development tools

**Migration Path:**
1. Install v3.0 (automatic update)
2. Run validation script
3. Optional: Convert pages to Elementor
4. Optional: Update custom code to use helpers

---

## Success Metrics

### Code Quality
- ✅ PHPCS: 0 errors
- ✅ PHPStan: Level 8, 0 errors
- ✅ Test Coverage: > 80%
- ✅ Code Duplication: < 5%

### Performance
- ✅ Page Load: < 2.5s average
- ✅ Database Queries: < 40 average
- ✅ Cache Hit Rate: > 90%
- ✅ Core Web Vitals: All "Good"

### Developer Experience
- ✅ Setup Time: < 15 minutes
- ✅ Component Creation: < 30 minutes
- ✅ Widget Creation: < 1 hour
- ✅ Documentation: Complete and accurate

### User Experience
- ✅ Backward Compatibility: 100%
- ✅ Elementor Integration: Seamless
- ✅ Page Builder: Both systems work
- ✅ Performance: No degradation

---

**Document Version**: 1.0  
**Last Updated**: December 11, 2025  
**Status**: Vision Document
