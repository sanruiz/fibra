# GitHub Copilot Instructions for FibraSOMA Project

## Project Context

This is an **8-week WordPress website development project** for FibraSOMA's corporate website using the newly modernized SOMA v3.0.0 theme.

**Repository**: `sanruiz/fibra` (default branch: main)  
**Theme**: `wp-content/themes/soma/` (PSR-4, PHP 8.1+, Elementor integrated)  
**Documentation**: Comprehensive docs in `wp-content/themes/soma/docs/`

## 🚀 Project Status

**Website Project**: FibraSOMA (Real Estate Investment Trust)  
**WordPress Theme**: SOMA v3.0.0 ✅ (Fully Modernized)  
**Project Management**: GitHub Projects @ https://github.com/users/sanruiz/projects/4

**Current status**: Check GitHub Projects board for current milestone and progress

## ⚠️ CRITICAL: Language Policy

**ALL project files MUST be written in ENGLISH:**

- ✅ **Documentation** (`.md` files) - English only
- ✅ **GitHub Actions Workflows** (`.yml` files) - English comments and descriptions
- ✅ **Scripts** (`.sh`, `.php`, etc.) - English comments and output messages
- ✅ **Code comments** - English only
- ✅ **Commit messages** - English only
- ✅ **PR descriptions** - English only
- ✅ **Issue descriptions** - English only

**Rationale**: 
- Professional international standard
- Better integration with development tools
- Accessibility for global team members
- Consistency with SOMA v3.0.0 codebase (fully in English)

**Exception**: User-facing content in WordPress (Spanish for FibraSOMA website visitors)

---

## ⚠️ MANDATORY: GitHub Workflow Pre-Flight Checklist

**CRITICAL**: Before ANY git commit, push, or branch operation, you MUST verify:

### ✅ Branch Rules (STRICTLY ENFORCED)

**NEVER commit directly to these branches:**
- ❌ `main` (default branch - only via merged PRs)
- ❌ `week-*` (milestone branches - only via merged PRs)
- ❌ `develop` (development branch - only via merged PRs)

**ALWAYS work on feature branches:**
- ✅ Create feature branch from current milestone (`week-N`)
- ✅ Use naming convention: `feature/`, `fix/`, `chore/`, `refactor/`
- ✅ Example: `feature/hero-section`, `fix/navbar-mobile`

### ✅ Git Operation Checklist

**Before EVERY commit, verify:**
1. **Check current branch**: `git branch | grep "^\*"`
   - Must show a feature branch (NOT week-*, NOT main)
2. **If on wrong branch**: Create feature branch FIRST
   ```bash
   git checkout week-N
   git checkout -b feature/description
   ```
3. **Then commit**: Use conventional commits format
4. **Before push - Run quality gates locally**:
   ```bash
   # Required: Pass ALL quality checks before pushing
   composer quality-check  # Runs phpcs + phpstan + tests (all must pass)
   # Or run individually:
   composer phpcs        # WordPress Coding Standards (must pass)
   composer phpstan      # Static analysis Level 6+ (0 critical errors)
   composer test         # PHPUnit tests (all passing)
   npm run prod          # Frontend build (if modified CSS/JS)
   ```
   **Why**: Prevent wasting GitHub Actions resources on avoidable failures
5. **Then push**: `git push -u origin feature/description`
6. **Then create PR**: Target `week-N` branch (NOT main)

### ✅ Pull Request Rules

**When creating PRs:**
- Base branch: `week-N` (current milestone)
- Never target `main` directly during milestone work
- Use labels: `enhancement`, `semana-N`, `frontend`/`backend`, priority
- Reference issues: `Closes #N`, `Relates to #N`

### ✅ GitHub CLI Rules

**ALWAYS append `| cat` to `gh` commands:**
```bash
gh issue list | cat          # ✅ Correct
gh issue list                # ❌ Will hang

gh pr view 42 | cat          # ✅ Correct
gh pr view 42                # ❌ Will hang
```

### 🔄 Common Workflow Scenarios

**Scenario 1: Starting new feature**
```bash
git checkout week-2
git pull origin week-2
git checkout -b feature/my-feature
# ... make changes ...
git add .
git commit -m "feat: add new feature"
git push -u origin feature/my-feature
gh pr create --base week-2 --label "enhancement,semana-2" | cat
```

**Scenario 2: If you find yourself on week-* or main**
```bash
# STOP - Do NOT commit
# Check staged changes
git status
# Create feature branch
git checkout -b feature/description
# Now you can commit
```

**See complete workflow guide**: `.github/instructions/github-workflow.instructions.md`

---

## 📋 Path-Specific Instructions

The following instruction files apply automatically based on file type. VS Code will load them when working with matching files.

**Available instruction files:**
- `.github/instructions/php.instructions.md` → PHP files (`**/*.php`)
- `.github/instructions/documentation-language.instructions.md` → Documentation and language policy
- `.github/instructions/github-workflow.instructions.md` → GitHub operations and workflow

**Note**: These files use YAML frontmatter with `applyTo` patterns and are automatically detected by VS Code.

---

## 📁 Repository Structure

**IMPORTANT Directory Conventions:**

### Theme Development (within soma/)
- **Location**: `wp-content/themes/soma/`
- **Contains**: Source code, documentation, tests, assets
- **Documentation**: `wp-content/themes/soma/docs/`

### GitHub Actions Workflows (repository root)
- **Location**: `.github/workflows/` (repository root, NOT within theme)
- **Contains**: All CI/CD workflow files (`.yml`)
- **Rationale**: GitHub Actions only reads workflows from repository root
- **Current Workflows**:
  - `.github/workflows/quality-and-tests.yml` - **CI**: Code quality and automated testing (runs on push/PR)
  - `.github/workflows/release-and-deploy.yml` - **CD**: Build, release, and deploy (runs on tags only)
  - `.github/workflows/test-sftp-secrets.yml` - Manual SFTP validation

### Deployment Scripts (repository root)
- **Location**: `.github/scripts/` (repository root)
- **Contains**: Deployment automation scripts (PHP, Shell, etc.)
- **Examples**:
  - `.github/scripts/soma-extractor.php`

### Copilot Instructions (repository root)
- **Location**: `.github/copilot-instructions.md` (repository root)
- **Purpose**: Global project context and conventions

**Summary:**
- ✅ Theme code/docs: `wp-content/themes/soma/`
- ✅ Workflows: `.github/workflows/` (root)
- ✅ Deployment scripts: `.github/scripts/` (root)
- ✅ Copilot instructions: `.github/copilot-instructions.md` (root)
- ✅ Workflow instructions: `.github/instructions/github-workflow.instructions.md`

## 🚀 CI/CD Workflows (Summary)

**For detailed workflow instructions, see** `.github/instructions/github-workflow.instructions.md`

### Quick Reference

**Continuous Integration** (`quality-and-tests.yml`):
- Triggers: Push to main/week-*, PRs
- Duration: ~3-5 minutes
- Jobs: PHPCS, PHPStan, PHPUnit, Frontend Build
- Purpose: Validate code quality before merge

**Continuous Deployment** (`release-and-deploy.yml`):
- Triggers: Tags `v*`, manual dispatch
- Duration: ~5-8 minutes  
- Jobs: Wait for CI → Build & Release → Deploy
- Purpose: Automated releases and deployment

## 📊 GitHub Project Organization

**For complete workflow details**, see `.github/instructions/github-workflow.instructions.md`

### View Project
- **Project Board**: https://github.com/users/sanruiz/projects/4
- **Issues**: https://github.com/sanruiz/fibra/issues
- **Milestones**: https://github.com/sanruiz/fibra/milestones

### Quick Commands
```bash
# View issues and PRs (always use | cat)
gh issue list | cat
gh pr list | cat

# Create issue with labels
gh issue create --label "enhancement,frontend,semana-2,alta-prioridad" | cat

# View labels
gh label list | cat
```

## 📋 Website Development Plan

**Full development plan**: Check GitHub Projects milestones for detailed weekly breakdown and current status.

## 🎯 SOMA v3.0.0 Theme - Completed ✅

**Version**: 3.0.0 (Released December 12, 2025)  
**Migration**: v2.0.7 → v3.0.0 complete  
**Documentation**: Comprehensive docs in `wp-content/themes/soma/docs/`

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

## Directory Structure (PSR-4)

**`/includes/`** - PSR-4 classes with `Soma\` namespace:
- `Core/` - Theme core (Loader, Theme, Interfaces, Enums)
- `PostTypes/` - Custom post types (Portfolio, News, Careers, TeamMembers)
- `Taxonomies/` - Custom taxonomies (PortfolioTaxonomy, NewsTaxonomy, TeamMembersTaxonomy)
- `API/` - REST endpoints (NewsEndpoint, CareersEndpoint, PortfolioEndpoint, DocumentsEndpoint, EventsEndpoint)
- `PageBuilder/` - ACF flexible content (Loader, BlockRegistry, BlockRenderer)
- `Elementor/` - 8 custom widgets (Navbar, Footer, BusinessUnits, Services, TeamMembers, NewsList, Portfolio, ContactForm)
- `CF7/` - Contact Form 7 integration (Validations)
- `Utils/` - Helper functions (Helpers, Logger, Cache, CacheInvalidationManager, Enums)
- `Admin/` - Admin customizations

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
- **NO global variables:** Use `get_query_var()` for block data (v3.0+ breaking change)
- **SCSS imports must be added to `main.scss`** under `// #DittoPartials` marker
- **JS handlers need conditional initialization** in `main.js` to avoid errors on pages without components

## 📚 Complete Documentation (5,800+ lines)

**Development Guides:**
- **[DEVELOPMENT.md](../wp-content/themes/soma/docs/DEVELOPMENT.md)** (1,093 lines) - Complete developer guide with setup, architecture, patterns, testing
- **[WIDGETS.md](../wp-content/themes/soma/docs/WIDGETS.md)** (900 lines) - Elementor widgets reference with controls and examples
- **[HELPERS.md](../wp-content/themes/soma/docs/HELPERS.md)** (850+ lines) - API reference for 24 soma_* helper functions
- **[MIGRATION_FROM_V2.md](../wp-content/themes/soma/docs/MIGRATION_FROM_V2.md)** (1,549 lines) - Upgrade guide from v2.0.7 to v3.0.0
- **[TESTING_GUIDE.md](../wp-content/themes/soma/docs/TESTING_GUIDE.md)** (337 lines) - Testing documentation with examples
- **[CHANGELOG.md](../wp-content/themes/soma/CHANGELOG.md)** (850+ lines) - Complete v3.0.0 changelog
- **[README.md](../wp-content/themes/soma/README.md)** (600+ lines) - Comprehensive project overview

**Quick Reference:**
- **Helper Functions**: 24 functions in `Soma\Utils\Helpers` (Logger, Cache, Post Types, Templates, ACF, Utilities)
- **Enums**: PostType, Taxonomy, LogLevel, CacheTag (all type-safe)
- **Widgets**: 8 Elementor widgets in 'soma' category
- **Tests**: 108 tests passing (355 assertions) - `vendor/bin/phpunit`
- **Quality**: PHPCS clean, PHPStan Level 6, no critical errors

## v3.0.0 Quick Start

**Installation:**
```bash
cd wp-content/themes/soma
composer install --no-dev --optimize-autoloader
npm install
npm run prod
```

**Development:**
```bash
npm run watch           # Development with hot reload
composer test           # Run all 108 tests
composer phpcs          # Check coding standards
composer phpstan        # Static analysis Level 6
```

**Key Features v3.0.0:**
- ✅ PSR-4 architecture with `Soma\` namespace
- ✅ PHP 8.1+ (enums, match, first-class callables)
- ✅ Global helper functions (`soma_*` prefix)
- ✅ PSR-3 logging to `wp-content/uploads/soma-logs/`
- ✅ Tag-based caching with auto-invalidation
- ✅ Elementor widgets with ACF integration
- ✅ ACF flexible content blocks via PageBuilder
- ✅ Comprehensive test coverage, PHPCS clean, PHPStan Level 6+

---

## 🏷️ GitHub Labels & Project Organization

**For complete label inventory, usage guidelines, and GitHub workflow**, see:  
`.github/instructions/github-workflow.instructions.md`

**Quick reference**: Use `gh label list | cat` to see current labels
