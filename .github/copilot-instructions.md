# GitHub Copilot Instructions for Soma WordPress Theme

## 🚀 Project Status: Modernization in Progress

**Current Version**: 2.0.7 (Legacy)  
**Target Version**: 3.0.0 (Modernized - PSR-4 + Elementor + Quality Gates)  
**Migration Phase**: Phase 2 - Module Migration (In Progress)  
**Migration Plan**: See [docs/MIGRATION_PLAN.md](../wp-content/themes/soma/docs/MIGRATION_PLAN.md)

## Project Context
This is a **WordPress theme** (`wp-content/themes/soma/`) undergoing a comprehensive modernization to adopt PSR-4 standards, Elementor integration, and enterprise-grade development practices inspired by the WellSpring theme architecture.

Only the `soma` theme directory is tracked in git—WordPress core and other themes are excluded via `.gitignore`.

## 📋 Modernization Roadmap

### Current State (v2.0.7 - Legacy)
- ❌ No PSR-4 compliance (files use underscores, no namespaces)
- ❌ No Singleton pattern or LoadableInterface
- ❌ No PHP 8.1+ features (enums, match, first-class callables)
- ❌ No testing infrastructure (zero unit tests)
- ❌ No quality gates (no PHPCS, PHPStan, or validation)
- ❌ No Elementor integration
- ❌ No centralized CSS variables system
- ✅ ACF Flexible Content page builder (50+ partials) - **PRESERVE**
- ✅ 4 custom post types working
- ✅ REST API endpoints functional
- ✅ Webpack build system operational

### Target State (v3.0.0 - Modernized)
- ✅ **PSR-4 Compliance**: `Soma\` namespace, PascalCase files
- ✅ **Singleton Pattern**: All core classes use `instance()` method
- ✅ **LoadableInterface**: Standardized component loading with priorities
- ✅ **PHP 8.1+ Features**: Enums, match expressions, first-class callables, readonly
- ✅ **Quality Gates**: PHPCS (0 errors), PHPStan Level 8, 80%+ test coverage
- ✅ **Testing Infrastructure**: PHPUnit unit tests, integration tests
- ✅ **Elementor Integration**: 8+ custom widgets in 'soma' category
- ✅ **CSS Variables**: 200+ design tokens in `assets/css/variables.css`
- ✅ **Helper Functions**: Centralized `soma_*` global functions
- ✅ **Logging System**: PSR-3 compliant Logger with 8 levels
- ✅ **Cache System**: Tag-based invalidation, auto-cleanup
- ✅ **Documentation**: Complete developer guides in `docs/`
- ✅ **Backward Compatibility**: ACF flexible content system fully preserved

### Migration Phases (13 weeks)

| Phase | Status | Deliverables |
|-------|--------|--------------|
| 1. Foundation & Infrastructure | ✅ Complete | Composer, PSR-4, quality tools |
| 2. Module Migration | 🔄 In Progress | Post Types ✅, CF7 ✅, API ✅, Custom Fields ⏳ |
| 3. Utilities & Helpers | ⏳ Pending | Logger, Cache, Enums, Helpers |
| 4. Elementor Integration | ⏳ Pending | 8+ custom widgets |
| 5. CSS Variables System | ⏳ Pending | 200+ design tokens |
| 6. Page Builder Enhancement | ⏳ Pending | ACF system modernization |
| 7. Testing & Quality | ⏳ Pending | 80%+ coverage, quality gates |
| 8. Documentation & Release | ⏳ Pending | Complete docs, v3.0.0 |

**Full Migration Plan**: [docs/MIGRATION_PLAN.md](../wp-content/themes/soma/docs/MIGRATION_PLAN.md)  
**Architecture Vision**: [docs/ARCHITECTURE_VISION.md](../wp-content/themes/soma/docs/ARCHITECTURE_VISION.md)

---

## Core Architecture: ACF Flexible Content Page Builder

**Critical Pattern:** This theme uses a custom page builder system (`page-builder.php`) that maps ACF flexible content layouts to PHP partials:

```php
// In page.php, templates/*.php, single.php:
global $pageBuilder;
$pageBuilder = get_field('soma_blocks'); // ACF flexible content field
get_template_part('page-builder'); // Renders all blocks

// page-builder.php maps ACF layouts to partials:
$blockList = [
    "BusinessUnits" => "business_units_content",
    "Navbar" => "navbar_content",
    // ...50+ mappings
];
```

**When creating new content blocks:**
1. Add partial to `/partials/ComponentName.php` with global `$pageBlock` access
2. Register mapping in `page-builder.php` `$blockList` array
3. Create corresponding SCSS in `/sass/partials/_ComponentName.scss`
4. Import SCSS in `/sass/main.scss` under `// #DittoPartials`
5. Add JS handler in `/js/components/componentName.js` if interactive
6. Import/initialize in `/js/main.js` with conditional check

## Directory Structure

**`/inc/`** - Module system loaded via `functions.php`:
- `theme-config.php`: Enqueues assets (versioned at `2.0.7`), registers 5 nav menus, ACF options pages
- `post-types.php`: Custom post types (portfolio, news, careers, team_members, events, documents)
- `endpoints.php`: REST API endpoints at `/wp-json/soma/*` (news, careers, portfolio, documents, events)
- `cf7-validations.php`: Contact Form 7 custom validation classes

**`/partials/`** - Page builder components (50+ files). Each accesses `global $pageBlock` containing `block_counter` and `block_content` from ACF

**`/templates/`** - Custom page templates with special header comment (e.g., `business-unit-template.php`, `navigationsidebar-template.php`)

**`/singles/`** - Single post templates by type: `news.php`, `careers.php`, `team-members.php`

**`/acf-json/`** - ACF field group sync files (13 groups). Auto-synced—never edit manually

**`/js/components/`** - Modular handlers initialized conditionally in `main.js`:
```javascript
if($('.navbar-partial-df27ae').length > 0) navbarHandler($('.navbar-partial-df27ae'));
```

## Build System (Webpack 4 + Sass)

```bash
npm run watch  # Dev mode with watch (requires --openssl-legacy-provider flag)
npm run dev    # Dev build
npm run prod   # Production build (minified)
```

**Entry:** `js/main.js` imports all components + `sass/main.scss`  
**Output:** `js/main.bundle.js` + `css/main.bundle.css`  
**Sass imports:** Organized by `// #DittoPartials` marker in `main.scss` (autogenerated list)

## WordPress-Specific Conventions

**Security:** All PHP files start with:
```php
if (!defined('ABSPATH')) exit; // Exit if accessed directly
```

**Template inclusion:** Use `get_template_part()`, not `include()`:
```php
get_template_part('partials/BreadCrumb');
get_template_part('page-builder');
```

**ACF data access:**
```php
$data = get_field('field_name'); // From current post
$options = get_field('header_content', 'options'); // From ACF options page
```

**REST endpoints:** Registered in `inc/endpoints.php` with `'permission_callback' => '__return_true'`

## PSR-4 & Modern PHP Conventions (v3.0.0+)

**Namespace Structure:**
- Base namespace: `Soma\`
- All classes in `includes/` directory follow PSR-4 autoloading
- Example: `includes/API/Endpoints/NewsEndpoint.php` → `namespace Soma\API\Endpoints;`

**Use Statements:**
- Always import external classes at the top of the file to avoid `\` in code
- Place `use` statements after namespace declaration, before class definition
- Group `use` statements logically (WordPress classes, plugin classes, internal classes)

```php
<?php
namespace Soma\CF7;

use WPCF7_Submission;
use WPCF7_Validation;
use WPCF7_FormTag;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Validations {
	// Now use classes directly without \ prefix
	public function validate_email( $result, $tag ) {
		$submission = WPCF7_Submission::get_instance(); // ✅ Clean
		// NOT: $submission = \WPCF7_Submission::get_instance(); // ❌ Avoid
	}
}
```

**Singleton Pattern:**
```php
private static ?ClassName $instance = null;

public static function instance(): ClassName {
	if ( self::$instance === null ) {
		self::$instance = new self();
	}
	return self::$instance;
}

private function __construct() {}
private function __clone() {}
public function __wakeup() {
	throw new \Exception( 'Cannot unserialize singleton' );
}
```

**First-Class Callables (PHP 8.1+):**
```php
add_action( 'rest_api_init', $this->register(...) ); // ✅ Modern
// NOT: add_action( 'rest_api_init', array( $this, 'register' ) ); // ❌ Old
```

**LoadableInterface Pattern:**
All module loaders must implement `Soma\Core\Interfaces\LoadableInterface`:
- `init()`: Initialize the component
- `get_priority()`: Return loading priority (10-50)
- `should_load()`: Conditional loading check

**Priority System:**
- 10: Core components (Theme, Loader)
- 20: Post Types
- 30: CF7, Integrations
- 35: REST API
- 40: Admin
- 45: Utilities

## Custom Post Types & Endpoints
- **CPTs:** portfolio, news, careers, team_members, events, documents (all registered in `inc/post-types.php`)
- **REST routes:** `/wp-json/soma/{news|careers|portfolio|documents|events}` (see `inc/endpoints.php`)
- **Nav menus:** 5 locations registered: `main_menu`, `social`, `business_units`, `fibrasoma_footer`, `navigation_sidebar_template`

## CSS/JS Naming Conventions
- **Partials use hashed class names:** `.navbar-partial-df27ae`, `.businessunits-partial-a1b2c3`
- **Templates use descriptive IDs:** `#navigationsidebar-template-207713`
- **Dark mode:** Check `.dark-style` class; `main` gets `.latest-block-is-dark` if last section is dark

## Required WordPress Plugins
- Advanced Custom Fields PRO
- Contact Form 7
- Safe SVG
- WP Multilang (language switcher via `wpm_language_switcher()`)

## Development Workflow
1. Edit source files (`.php`, `.scss`, `.js`)
2. Run `npm run watch` for hot reloading
3. ACF field changes sync to `/acf-json/` automatically
4. Build production with `npm run prod` before deployment
5. Only commit `wp-content/themes/soma/` (per `.gitignore`)

## Common Pitfalls
- **Don't use `locate_template()`** in this codebase—use `get_template_part()`
- **Webpack requires legacy OpenSSL flag** for Node.js (see `package.json` scripts)
- **Global variables matter:** `$pageBuilder` in templates, `$pageBlock` in partials
- **SCSS imports must be added to `main.scss`** under `// #DittoPartials` marker
- **JS handlers need conditional initialization** in `main.js` to avoid errors on pages without components
