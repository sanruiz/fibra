# GitHub Copilot Instructions for FibraSOMA Project

## 🚀 Project Status: Development Ready

**Website Project**: FibraSOMA (Real Estate Investment Trust)  
**WordPress Theme**: SOMA v2.0.7 → v3.0.0 (Modernization)  
**Development Timeline**: 9 weeks (Dec 9, 2025 - Jan 30, 2026)  
**Current Phase**: Week 1 - Theme Modernization + Base Setup  
**Project Management**: GitHub Projects @ https://github.com/users/miguelcolmenares/projects/1

## Project Context

This is a **9-week WordPress website development project** for FibraSOMA with two parallel workstreams:

1. **Website Development** (9 weeks): Building FibraSOMA corporate website
2. **Theme Modernization** (Week 1): Upgrading SOMA theme from v2.0.7 to v3.0.0 with PSR-4, Elementor, and quality gates

**Repository**: `sanruiz/fibra` (branch: master, default: main)  
**Theme Location**: `wp-content/themes/soma/`  
**Documentation**: See `docs/` for all project plans and guides

## 📊 GitHub Project Organization

### Issues & Milestones
- **54 total issues** created and organized
- **8 milestones** (one per week, plus combined weeks 4-5)
- **28 labels** for categorization (weeks, types, priorities, components)
- **4 project columns**: Backlog (46), Todo (8), In Progress, Done

### Current Distribution
- **Week 1** (8 issues in Todo): Theme modernization + base setup
- **Weeks 2-9** (46 issues in Backlog): Website features and content

### View Project
- **Project Board**: https://github.com/users/miguelcolmenares/projects/1
- **Issues**: https://github.com/sanruiz/fibra/issues
- **Milestones**: https://github.com/sanruiz/fibra/milestones

## 📋 9-Week Development Plan

### Week 1: Alistamiento + Modernización Theme (Current)
**Milestone**: Semana 1: Alistamiento + Modernización Theme  
**Status**: 🔄 In Progress  
**Issues**: #1-8

**Priority**: Issue #1 - Complete SOMA theme modernization (8 phases, 150+ subtasks)
- PSR-4 compliance with `Soma\` namespace
- Elementor integration (8+ widgets)
- Quality gates (PHPCS, PHPStan Level 8, 80%+ test coverage)
- See `docs/MIGRATION_PLAN.md` for full details

### Weeks 2-9: Website Development
**See**: `docs/PLAN_DESARROLLO_FIBRASOMA.md` for complete breakdown
- Week 2: Home page sections
- Week 3: Corporate pages (About, Portfolio, Team)
- Weeks 4-5: Investor relations section
- Week 6: Individual project templates
- Week 7: ESG/ASG section
- Week 8: News system
- Week 9: Contact forms + QA + launch

## 📝 Theme Modernization Status

### Modernization Phases (Week 1)

| Phase | Status | Deliverables |
|-------|--------|--------------|
| 1. Foundation & Infrastructure | ✅ Complete | Composer, PSR-4, quality tools |
| 2. Module Migration | ✅ Complete | Post Types ✅, CF7 ✅, API ✅, Custom Fields ✅ |
| 3. Utilities & Helpers | ✅ Complete | Logger, Cache, Enums, Helpers |
| 4. Elementor Integration | ✅ Complete | 8 widgets, CSS files, style dependencies, integration tests |
| 5. CSS Variables System | ✅ Complete | 200+ design tokens, 55 SCSS partials migrated |
| 6. Page Builder Enhancement | ✅ Complete | PSR-4 architecture, 53 blocks, query vars, testing |
| 7. Testing & Quality | ⏳ Pending | 80%+ coverage, quality gates |
| 8. Documentation & Release | ⏳ Pending | Complete docs, v3.0.0 |

**Current Focus**: Phase 7 - Testing & Quality

**Phase 6 Complete**: PageBuilder PSR-4 system with 53 blocks registered, breaking changes (globals → query vars), 3 testing approaches, quality validation (PHPCS + PHPStan Level 6), comprehensive documentation (PHASE_6_COMPLETION.md)

**Full Migration Plan**: [docs/MIGRATION_PLAN.md](../wp-content/themes/soma/docs/MIGRATION_PLAN.md)  
**Architecture Vision**: [docs/ARCHITECTURE_VISION.md](../wp-content/themes/soma/docs/ARCHITECTURE_VISION.md)

---

## Core Architecture: ACF Flexible Content Page Builder

**v3.0.0 PSR-4 System (Current):** The page builder uses modern PSR-4 architecture with WordPress query vars:

```php
// In page.php, templates/*.php, single.php:
get_template_part('page-builder'); // Renders all ACF soma_blocks using PSR-4

// page-builder.php (34 lines):
$soma_blocks = get_field( 'soma_blocks' );
if ( class_exists( '\Soma\PageBuilder\BlockRenderer' ) ) {
    $renderer = \Soma\PageBuilder\BlockRenderer::instance();
    $renderer->render( $soma_blocks ); // Validation, error logging, caching
}
```

**PSR-4 PageBuilder Components:**
- **`includes/PageBuilder/Loader.php`**: LoadableInterface, priority 25, cache invalidation hooks
- **`includes/PageBuilder/BlockRegistry.php`**: Centralized 53 block mappings (layout → field_group + partial)
- **`includes/PageBuilder/BlockRenderer.php`**: Rendering engine with validation, PSR-3 logging, optional caching

**Features:**
- Multi-layer validation (structure, registry, file existence)
- Error handling with `soma_log_error()` + WP_DEBUG display
- Optional block caching with tag-based invalidation
- Cache auto-invalidation on `save_post` and `acf/save_post`
- **WordPress query vars** (no globals) for partial data access

**When creating new content blocks:**
1. Add partial to `/partials/ComponentName.php` using WordPress query vars:
   ```php
   // Access block data via WordPress query vars (v3.0+)
   $block_counter = get_query_var( 'soma_block_counter' );
   $block_content = get_query_var( 'soma_block_content' );
   $block_layout = get_query_var( 'soma_block_layout' );
   ```
2. Register in `BlockRegistry::register_default_blocks()`: 
   ```php
   $this->register_block('ComponentName', 'component_name_content', 'ComponentName')
   ```
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

**`/partials/`** - Page builder components (50+ files). Each accesses block data via WordPress query vars:
```php
// v3.0.0+ Standard (NO globals)
$block_counter = get_query_var( 'soma_block_counter' ); // Block index
$block_content = get_query_var( 'soma_block_content' ); // ACF field data
$block_layout  = get_query_var( 'soma_block_layout' );  // Layout name
```

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
- 10: Utilities (must load FIRST to provide soma_* helper functions)
- 20: Post Types
- 30: CF7, Elementor, Integrations
- 35: REST API
- 40: Admin

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

**⚠️ IMPORTANTE - GitHub CLI (`gh`) Commands:**
- **ALWAYS append `| cat` to `gh` commands** to prevent terminal pagination/waiting
- Examples:
  - `gh issue list | cat`
  - `gh issue view 1 | cat`
  - `gh pr list | cat`
- This ensures commands complete immediately without user interaction

## Common Pitfalls
- **Don't use `locate_template()`** in this codebase—use `get_template_part()`
- **Webpack requires legacy OpenSSL flag** for Node.js (see `package.json` scripts)
- **Global variables matter:** `$pageBuilder` in templates, `$pageBlock` in partials
- **SCSS imports must be added to `main.scss`** under `// #DittoPartials` marker
- **JS handlers need conditional initialization** in `main.js` to avoid errors on pages without components
