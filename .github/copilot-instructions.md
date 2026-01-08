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

---

## 📋 Path-Specific Instructions

This project uses **automatic path-specific instructions** that VS Code loads based on file patterns. The following instruction files apply automatically:

| File | Applies To | Description |
|------|-----------|-------------|
| `github-workflow.instructions.md` | `**` | GitFlow workflow, branch strategy, PRs, releases, CI/CD |
| `documentation-language.instructions.md` | `**` | English-only policy, documentation standards |
| `php.instructions.md` | `**/*.php` | WordPress/PHP coding standards, security patterns |
| `elementor-widgets.instructions.md` | `**/Elementor/Widgets/**/*.php` | Widget development workflow |
| `testing.instructions.md` | `tests/**/*.php` | PHPUnit testing conventions |

**Note**: VS Code automatically combines applicable instructions. Content below provides unique project context not covered in path-specific files.

---

## 📁 Repository Structure

### Theme Development (within soma/)
- **Location**: `wp-content/themes/soma/`
- **Contains**: Source code, documentation, tests, assets
- **Documentation**: `wp-content/themes/soma/docs/`

### GitHub Actions Workflows (repository root)
- **Location**: `.github/workflows/` (repository root, NOT within theme)
- **Contains**: All CI/CD workflow files (`.yml`)
- **Current Workflows**:
  - `.github/workflows/quality-and-tests.yml` - **CI**: Code quality and automated testing
  - `.github/workflows/release-and-deploy.yml` - **CD**: Build, release, and deploy

### Directory Summary
- ✅ Theme code/docs: `wp-content/themes/soma/`
- ✅ Workflows: `.github/workflows/` (root)
- ✅ Deployment scripts: `.github/scripts/` (root)
- ✅ Copilot instructions: `.github/copilot-instructions.md` (root)
- ✅ Path-specific instructions: `.github/instructions/`

---

## 🎯 SOMA v3.0.0 Theme Architecture

**Version**: 3.0.0 (Released December 12, 2025)  
**Migration**: v2.0.7 → v3.0.0 complete  

### Core Architecture: ACF Flexible Content Page Builder

**v3.0.0 PSR-4 System:** The page builder uses modern PSR-4 architecture with WordPress query vars:

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

### Directory Structure (PSR-4)

**`/includes/`** - PSR-4 classes with `Soma\` namespace:
- `Core/` - Theme core (Loader, Theme, Interfaces, Enums)
- `PostTypes/` - Custom post types (Portfolio, News, Careers, TeamMembers)
- `Taxonomies/` - Custom taxonomies (PortfolioTaxonomy, NewsTaxonomy, TeamMembersTaxonomy)
- `API/` - REST endpoints (NewsEndpoint, CareersEndpoint, PortfolioEndpoint, DocumentsEndpoint, EventsEndpoint)
- `PageBuilder/` - ACF flexible content (Loader, BlockRegistry, BlockRenderer)
- `Elementor/` - Custom widgets (Navbar, Footer, BusinessUnits, Services, TeamMembers, NewsList, Portfolio, ContactForm)
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

**`/templates/`** - Custom page templates with special header comment  
**`/singles/`** - Single post templates by type: `news.php`, `careers.php`, `team-members.php`  
**`/acf-json/`** - ACF field group sync files (13 groups). Auto-synced—never edit manually  
**`/js/components/`** - Modular handlers initialized conditionally in `main.js`

### Build System (Webpack 4 + Sass)

```bash
npm run watch  # Dev mode with watch (requires --openssl-legacy-provider flag)
npm run dev    # Dev build
npm run prod   # Production build (minified)
```

**Entry:** `js/main.js` imports all components + `sass/main.scss`  
**Output:** `js/main.bundle.js` + `css/main.bundle.css`

### Custom Post Types & Endpoints

- **CPTs:** portfolio, news, careers, team_members, events, documents
- **REST routes:** `/wp-json/soma/{news|careers|portfolio|documents|events}`
- **Nav menus:** 5 locations: `main_menu`, `social`, `business_units`, `fibrasoma_footer`, `navigation_sidebar_template`

### CSS/JS Naming Conventions

- **Partials use hashed class names:** `.navbar-partial-df27ae`, `.businessunits-partial-a1b2c3`
- **Templates use descriptive IDs:** `#navigationsidebar-template-207713`
- **Dark mode:** Check `.dark-style` class; `main` gets `.latest-block-is-dark` if last section is dark

### Required WordPress Plugins

- Advanced Custom Fields PRO
- Contact Form 7
- Safe SVG
- WP Multilang (language switcher via `wpm_language_switcher()`)

---

## ⚠️ WP-Multilang Compatibility (CRITICAL)

**WP-Multilang stores translations** in a single database field using `[:en]..[:es]..[:]` delimiters. The plugin hooks into WordPress filters to parse and display the correct language.

### Post Titles in Elementor Widget Dropdowns

**ALWAYS use `get_the_title()` instead of `$post->post_title`** in SELECT/SELECT2 controls:

```php
// ❌ WRONG - Bypasses WP-Multilang filters
foreach ( $posts as $post ) {
    $options[ $post->ID ] = $post->post_title;
    // Shows raw: "[:en]John Doe[:es]Juan Pérez[:]"
}

// ✅ CORRECT - Applies 'the_title' filter (WP-Multilang hooks here)
foreach ( $posts as $post ) {
    $options[ $post->ID ] = get_the_title( $post->ID );
    // Shows translated: "John Doe" or "Juan Pérez"
}
```

**Why this matters:**
- `$post->post_title` is direct property access → NO filters applied
- `get_the_title()` applies the `the_title` filter → WP-Multilang can translate
- This affects ALL widgets with post selector dropdowns

**Affected patterns:**
- Team member selectors
- CF7 form selectors
- Custom post type selectors
- Any dropdown populated from `WP_Query` results

**Helper function for i18n fields:**
Use `soma_get_i18n_field()` for ACF fields with language variants (`file`/`file_es`).

---

## ⚠️ Common Pitfalls

- **Don't use `locate_template()`** in this codebase—use `get_template_part()`
- **Webpack requires legacy OpenSSL flag** for Node.js (see `package.json` scripts)
- **NO global variables:** Use `get_query_var()` for block data (v3.0+ breaking change)
- **SCSS imports must be added to `main.scss`** under `// #DittoPartials` marker
- **JS handlers need conditional initialization** in `main.js` to avoid errors on pages without components
- **ALWAYS append `| cat` to `gh` commands** to prevent terminal pagination/waiting

---

## 📚 Complete Documentation (5,800+ lines)

**Development Guides:**
- **[DEVELOPMENT.md](../wp-content/themes/soma/docs/DEVELOPMENT.md)** (1,093 lines) - Complete developer guide
- **[WIDGETS.md](../wp-content/themes/soma/docs/WIDGETS.md)** (900 lines) - Elementor widgets reference
- **[HELPERS.md](../wp-content/themes/soma/docs/HELPERS.md)** (850+ lines) - API reference for 24 soma_* helper functions
- **[MIGRATION_FROM_V2.md](../wp-content/themes/soma/docs/MIGRATION_FROM_V2.md)** (1,549 lines) - Upgrade guide
- **[TESTING_GUIDE.md](../wp-content/themes/soma/docs/TESTING_GUIDE.md)** (337 lines) - Testing documentation
- **[CHANGELOG.md](../wp-content/themes/soma/CHANGELOG.md)** (850+ lines) - Complete v3.0.0 changelog
- **[README.md](../wp-content/themes/soma/README.md)** (600+ lines) - Comprehensive project overview

**Quick Reference:**
- **Helper Functions**: 24 functions in `Soma\Utils\Helpers` (Logger, Cache, Post Types, Templates, ACF, Utilities)
- **Enums**: PostType, Taxonomy, LogLevel, CacheTag (all type-safe)
- **Widgets**: 8+ Elementor widgets in 'soma' category
- **Tests**: 108 tests passing (355 assertions) - `vendor/bin/phpunit`
- **Quality**: PHPCS clean, PHPStan Level 6, no critical errors

---

## 🚀 v3.0.0 Quick Start

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
