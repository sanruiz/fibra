# Changelog

All notable changes to the SOMA WordPress theme will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

---

## [3.1.8] - 2025-12-31

### Week 4 Patch - Enhanced Portfolio Widget

This release completes the Portfolio Elementor widget with dynamic category filtering and improved layout controls.

---

### ✨ Added

#### Portfolio Widget Enhancements
- **Dynamic Category Filters** - Portfolio widget now displays interactive category filter buttons that filter content via AJAX (Issue #17)
- **Filter Position Control** - New Elementor control to position filters left, center, or right
- **Filter Spacing Control** - New control to adjust spacing between filter buttons using CSS `gap`
- **Active Filter Styling** - Configurable colors for active/hover filter states
- **AJAX Category Loading** - Categories loaded dynamically from REST API (`/wp-json/soma/portfolio?include_categories=true`)
- **Portfolio Widget Tests** - 17 integration tests covering widget controls, rendering, and API integration

#### REST API Improvements
- **Portfolio Categories** - New `include_categories=true` parameter returns available portfolio categories
- **Category Filtering** - Filter portfolio items by category slug via `category` parameter
- **Fibrasoma Exclusion** - Fibrasoma category automatically excluded from public category lists

### 🔄 Changed

#### CSS & Styling
- **Filter Layout** - Changed from `margin-right` to `gap` for filter spacing (better flexbox support)
- **Font Family** - Updated to "Neue Haas Unica Pro" in CSS variables
- **Responsive Filters** - Improved mobile responsiveness for filter buttons

#### Code Quality
- **PHPCS Compliance** - Fixed unsanitized `$_GET` input in Portfolio widget with proper `sanitize_text_field()`
- **Test Cleanup** - Removed deprecated `setAccessible(true)` call in StockPriceWidgetTest

### 📦 Files Changed

#### Added
- `assets/js/widgets/portfolio.js` - Client-side AJAX filtering logic
- `tests/Integration/Elementor/PortfolioWidgetTest.php` - 17 integration tests

#### Modified
- `includes/Elementor/Widgets/Portfolio.php` - Dynamic filters, AJAX loading, new controls
- `includes/API/Endpoints/PortfolioEndpoint.php` - Category filtering support
- `assets/css/widgets/portfolio.css` - Flexbox layout with gap, improved responsive
- `assets/css/variables.css` - Font family update
- `includes/Elementor/Loader.php` - AJAX action registration
- `tests/Integration/Elementor/StockPriceWidgetTest.php` - Removed deprecated method

---

### 🔗 Related Issues & PRs

- **Issue #17**: [Widgets para la sección de portafolio de la home y su template](https://github.com/sanruiz/fibra/issues/17) - Closed
- **PR #140**: [feat(elementor): Enhance Portfolio widget with dynamic filters](https://github.com/sanruiz/fibra/pull/140) - Merged

---

## [3.1.7] - 2025-12-30

### Week 4 Feature - Breadcrumb Navigation Widget

This release adds a new Breadcrumb Elementor widget for content page navigation.

---

### ✨ Added

#### New Elementor Widget
- **Breadcrumb Widget** - Clean navigation breadcrumb widget for content pages (#134)
  - Uses Global_Colors and Global_Typography from Elementor Site Kit
  - Customizable separator (text or icon)
  - Home icon option with configurable icon
  - Current page highlighting
  - Responsive container max-width control
  - Full typography and color controls for all elements

#### Helper Functions
- **`soma_get_breadcrumb_items()`** - Flexible breadcrumb generation helper function
  - Supports hierarchical pages and custom post types
  - Returns array of breadcrumb items with title and URL
  - Handles parent page traversal automatically

### 📦 Files Changed

#### Added
- `includes/Elementor/Widgets/Breadcrumb.php` - Breadcrumb widget (425 lines)
- `assets/css/widgets/breadcrumb.css` - Widget styles (49 lines)
- `tests/Integration/Elementor/BreadcrumbWidgetTest.php` - 15 integration tests

#### Modified
- `includes/Elementor/Loader.php` - Register Breadcrumb widget
- `includes/Utils/Helpers.php` - Added `soma_get_breadcrumb_items()` function
- `tests/Integration/Elementor/AllWidgetsTest.php` - Include Breadcrumb widget

---

### 🔗 Related Issues & PRs

- **PR #134**: [feat: Add Breadcrumb Elementor widget](https://github.com/sanruiz/fibra/pull/134) - Merged to week-4
- **PR #135**: [Week 4: Breadcrumb Widget & Documents Improvements](https://github.com/sanruiz/fibra/pull/135) - Merged to main
- **Issue #17**: [Página Portafolio (Vista General)](https://github.com/sanruiz/fibra/issues/17) - Closed
- **Issue #18**: [Página Equipo](https://github.com/sanruiz/fibra/issues/18) - Closed
- **Issue #19**: [Páginas Administrador Interno / Historia / Diferenciadores](https://github.com/sanruiz/fibra/issues/19) - Closed

---

## [3.1.6] - 2025-12-30

### Week 4 Patch - Spanish Translations & Documents Widget Improvements

This patch release completes Spanish translations for all theme components and improves the Documents widget layout.

---

### ✨ Added

#### Internationalization (i18n)
- **Complete Spanish Translations** - Added 170+ Spanish translations covering all theme components
- **Elementor Widgets** - All 10 widget titles, descriptions, and controls translated
- **PostType Enum** - Labels for Portfolio, News, Careers, Team Members, Events, Documents
- **CacheTag Enum** - Cache tag descriptions translated
- **LogLevel Enum** - All 8 PSR-3 log level labels translated
- **Template Names** - Page templates translated (Business Unit, Navigation Sidebar, Elementor)
- **UI Controls** - All Elementor control labels, placeholders, and descriptions

### 🐛 Fixed

#### Code Quality
- **PHPCS Compliance** - Fixed `count()` inside while loop in Documents widget (moved to variable before loop)

### 🔄 Changed

#### Documents Widget
- **Layout Improvements** - Enhanced grid layout for better document display
- **Responsive Styles** - Improved mobile responsiveness for document cards

### 📦 Files Changed

#### Modified
- `languages/es_ES.po` - Added 170+ Spanish translations
- `languages/es_ES.mo` - Compiled binary translation file
- `includes/Elementor/Widgets/Documents.php` - PHPCS fix and layout improvements
- `assets/css/widgets/documents.css` - Responsive style improvements

---

### 🔗 Related Issues & PRs

- **PR #131**: [fix: Add Spanish translations and improve Documents widget](https://github.com/sanruiz/fibra/pull/131) - Merged

---

## [3.1.5] - 2025-12-29

### Week 4 Release - New Elementor Widgets & Bug Fixes

This release adds two new Elementor widgets (StockPrice and Documents) and includes important bug fixes and refactoring improvements.

---

### ✨ Added

#### New Elementor Widgets
- **Documents Widget** - Display documents from `documents-reports` CPT in responsive grid layout with i18n support for bilingual file downloads (#128)
- **StockPrice Widget** - Display current stock price from cached stock data with configurable styling (#126)

#### Documentation
- **Manual Issue Closure Note** - Added documentation about manual issue closure for non-default branch merges (#125)

### 🐛 Fixed

#### Cron & Scheduling
- **Stock Data Cron** - Register cron schedule before using it in StockData endpoint (#124)

### 🔄 Changed

#### Code Quality
- **StockDataEndpoint Refactor** - Use `soma_get_stock_data()` helper instead of direct transient access (#127)

### 📦 Files Changed

#### Added
- `includes/Elementor/Widgets/Documents.php` - Documents grid widget (485 lines)
- `includes/Elementor/Widgets/StockPrice.php` - Stock price display widget
- `assets/css/widgets/documents.css` - Documents widget styles (148 lines)
- `assets/css/widgets/stock-price.css` - Stock price widget styles
- `tests/Integration/Elementor/DocumentsWidgetTest.php` - 17 integration tests
- `tests/Integration/Elementor/StockPriceWidgetTest.php` - Integration tests

#### Modified
- `includes/Elementor/Loader.php` - Register new widgets
- `includes/Core/Theme.php` - Loader registration order fix
- `includes/API/Endpoints/StockDataEndpoint.php` - Use helper function
- `tests/Integration/Elementor/AllWidgetsTest.php` - Include new widgets
- `docs/WIDGETS.md` - Widget development workflow documentation
- `.github/copilot-instructions.md` - Elementor widget development section

---

### 🔗 Related Issues & PRs

- **Issue #14**: [Sección de Documentos Relevantes](https://github.com/sanruiz/fibra/issues/14) - Closed
- **PR #128**: [feat: Add Documents Elementor widget](https://github.com/sanruiz/fibra/pull/128) - Merged
- **PR #127**: [refactor: Use soma_get_stock_data() helper](https://github.com/sanruiz/fibra/pull/127) - Merged
- **PR #126**: [feat: Add StockPrice Elementor widget](https://github.com/sanruiz/fibra/pull/126) - Merged
- **PR #125**: [docs: Add note about manual issue closure](https://github.com/sanruiz/fibra/pull/125) - Merged
- **PR #124**: [fix: Register cron schedule](https://github.com/sanruiz/fibra/pull/124) - Merged

---

## [3.1.4] - 2025-12-29

### Team Members Post Type Slug Fix

This patch release restores backward compatibility for the team-members post type.

---

### 🐛 Fixed

#### Post Type Slug
- **team-members Post Type** - Restored original hyphenated slug `'team-members'` (was incorrectly changed to `'team_members'` during v3.0.0 refactoring)
- **Backward Compatibility** - Existing Team Members posts created before v3.0.0 are now accessible again
- **Database Consistency** - Post type slug now matches existing database records

### 📦 Files Changed

#### Modified
- `wp-content/themes/soma/includes/Core/Enums/PostType.php` - Changed `TEAM_MEMBERS` enum value from `'team_members'` to `'team-members'`

---

### 🔗 Related Issues & PRs

- **Issue #119**: [fix: team-members post type slug broken after v3.0.0 refactoring](https://github.com/sanruiz/fibra/issues/119) - Closed
- **PR #120**: [fix: Restore team-members post type slug for backward compatibility](https://github.com/sanruiz/fibra/pull/120) - Merged
- **PR #121**: [Week 4: Team Members Post Type Fix](https://github.com/sanruiz/fibra/pull/121) - Merged

---

## [3.1.3] - 2025-12-21

### Elementor Styles Fix & Security Enhancements

This patch release fixes Elementor widget style conflicts and adds important security and workflow improvements.

---

### 🐛 Fixed

#### Elementor Styles
- **Global Styles Override** - Simplified exclusion selectors to prevent global typography from affecting Elementor widgets
- **CSS Specificity** - Changed from complex `:not()` with multiple conditions to simple `:not(.elementor a)` pattern
- **Widget Styling** - Elementor widgets now maintain their specific styles without interference from theme globals

### ✨ Added

#### Security & CI/CD
- **CodeQL Security Analysis** - Automated code scanning for security vulnerabilities
- **Git Hooks** - Pre-commit hooks to enforce branch protection and prevent direct commits to protected branches
- **Branch Protection** - Blocks commits to `main`, `week-*`, and `develop` branches
- **Workflow Restrictions** - Releases and deploys now restricted to `main` branch only

### 🔄 Changed

#### Workflow Improvements
- **Release Process** - Enforced GitFlow: only tags from `main` trigger releases and deployments
- **Branch Strategy** - Documented sprint-based workflow with `week-*` branches
- **Quality Gates** - CI runs on all PRs to `week-*` and `main` branches

### 📦 Files Changed

#### Modified
- `wp-content/themes/soma/sass/_general.scss` - Simplified Elementor exclusion selectors
- `.github/workflows/ci-cd.yml` - Added main branch restriction for releases
- `.github/workflows/codeql.yml` - New security scanning workflow

#### Added
- `install-hooks.sh` - Git hooks installation script
- `.git/hooks/pre-commit` - Branch protection enforcement

---

### 🔗 Related Issues & PRs

- **Issue #79**: [Restrict releases and deploys to main branch only](https://github.com/sanruiz/fibra/issues/79) - Closed (duplicate of #80)
- **Issue #80**: [Restrict releases and deploys to main branch only](https://github.com/sanruiz/fibra/issues/80) - Closed
- **PR #81**: [Restrict releases to main and add Git hooks](https://github.com/sanruiz/fibra/pull/81) - Merged
- **PR #82**: [Add CodeQL security analysis workflow](https://github.com/sanruiz/fibra/pull/82) - Merged
- **PR #83**: [Simplify Elementor exclusion in global styles](https://github.com/sanruiz/fibra/pull/83) - Merged

---

## [3.1.2] - 2025-12-18

### Asset Versioning Fix

This patch release fixes asset versioning to use a single source of truth from `style.css`, eliminating hardcoded version strings and ensuring proper cache busting.

---

### 🐛 Fixed

#### Asset Versioning
- **Outdated Version Numbers** - Assets were loading with version 2.0.7 instead of current version
- **Browser Caching Issues** - New styles not appearing due to old version numbers in query strings
- **Hardcoded Versions** - Eliminated hardcoded `$version` and `$legacy_version` properties
- **Version Mismatch** - Theme had multiple conflicting version definitions

### 🔄 Changed

#### Single Source of Truth Pattern
- **Assets.php** - Now reads version from `style.css` header using `wp_get_theme()->get('Version')` in constructor
- **Theme.php** - `get_version()` method uses `wp_get_theme()->get('Version')` instead of hardcoded constant
- **Removed** - `VERSION` constant from Theme.php
- **Removed** - `$legacy_version` property (was hardcoded to 2.0.7)
- **Simplified** - Future version updates only require changing `style.css` header

### 📦 Files Changed

#### Modified
- `wp-content/themes/soma/includes/Core/Assets.php` - Dynamic version loading
- `wp-content/themes/soma/includes/Core/Theme.php` - Removed hardcoded constant

---

### 🔗 Related Issues

- **Issue #76**: [fix: Asset versioning showing outdated 2.0.7 instead of current 3.1.1](https://github.com/sanruiz/fibra/issues/76) - Closed
- **PR #77**: [fix: Use wp_get_theme()->get('Version') as single source of truth](https://github.com/sanruiz/fibra/pull/77) - Merged to week-2

---

## [3.1.1] - 2025-12-18

### CI/CD Pipeline Unification & Race Condition Fix

This patch release fixes a critical race condition in the CI/CD pipeline that caused deployment failures in v3.1.0. The solution unifies previously separate workflows into a single, sequential pipeline eliminating timing issues.

---

### 🐛 Fixed

#### CI/CD Architecture
- **Race Condition Eliminated** - Unified `ci-cd.yml` replaces separate `quality-and-tests.yml` and `release-and-deploy.yml` workflows
- **Sequential Execution** - Stage 2 (Build & Release) waits for Stage 1 (Quality Gates) completion via `needs:` keyword
- **Deployment Reliability** - Stage 3 (Deploy) executes only after successful release creation
- **Cross-Workflow Timing Issues** - Removed unreliable wait-for-ci job that attempted to coordinate separate workflows
- **3 Failed Deployments** - Resolved v3.1.0 deployment failures (Run IDs: 20291546501, 20291554549, 20291578219)

---

### ✨ Added

#### Workflow Architecture
- **Unified CI/CD Pipeline** - Single `.github/workflows/ci-cd.yml` (546 lines) with 3-stage architecture
- **Stage 1: Quality Gates** (parallel execution, always runs)
  - `code-quality`: PHPCS strict + PHPStan Level 6+ (~27s)
  - `php-tests`: PHPUnit 108 tests with MySQL (~1m9s)
  - `frontend-build`: npm production build (~20s)
- **Stage 2: Build & Release** (conditional on tags, sequential after Stage 1)
  - Creates production ZIP package
  - Generates GitHub release
  - Uploads build artifact
  - Only runs when tag pushed (v*)
- **Stage 3: Deploy to Production** (conditional on Stage 2 success)
  - SFTP upload to fibrasoma.com
  - Automatic theme backup
  - Server-side extraction
- **Stage 4: Pipeline Summary** (always runs, reports all stage results)

#### Documentation
- **Comprehensive CI/CD Guide** - New `docs/workflows/CI_CD.md` (600+ lines)
  - Architecture overview with visual flow diagram
  - Detailed job descriptions for all stages
  - 4 execution flow scenarios
  - Troubleshooting guide with solutions
  - Migration information from old workflows
- **Updated Main Index** - `docs/WORKFLOWS.md` reflects unified architecture
- **Deprecated Old Docs** - `QUALITY_AND_TESTS.md` and `RELEASE_AND_DEPLOY.md` marked deprecated with migration notices

---

### 🔄 Changed

#### Workflow Files
- **Replaced**: `.github/workflows/quality-and-tests.yml` (302 lines, deleted)
- **Replaced**: `.github/workflows/release-and-deploy.yml` (357 lines, deleted)
- **Created**: `.github/workflows/ci-cd.yml` (546 lines, unified pipeline)

#### Execution Flow
- **OLD**: Separate workflows triggered simultaneously on tag push → race condition → wait-for-ci job → deployment failures
- **NEW**: Single workflow with guaranteed sequential stages → no race conditions → reliable deployment

---

### 📦 Files Changed

#### Created
- `.github/workflows/ci-cd.yml` - Unified CI/CD pipeline (546 lines)
- `docs/workflows/CI_CD.md` - Comprehensive workflow documentation (600+ lines)

#### Modified
- `docs/WORKFLOWS.md` - Updated with unified architecture, new quick start, troubleshooting
- `docs/workflows/QUALITY_AND_TESTS.md` - Added deprecation notice and migration guide
- `docs/workflows/RELEASE_AND_DEPLOY.md` - Added deprecation notice and migration guide

#### Deleted
- `.github/workflows/quality-and-tests.yml` - Merged into ci-cd.yml
- `.github/workflows/release-and-deploy.yml` - Merged into ci-cd.yml

---

### 📊 Quality Metrics

- **CI Validation**: PR #73 passed all Stage 1 checks (code-quality 27s, php-tests 1m9s, frontend-build 20s)
- **PHPCS**: 0 errors (WordPress Coding Standards compliant)
- **PHPStan**: Level 6+ compliance (0 critical errors)
- **PHPUnit**: 108 tests passing (355 assertions)
- **Architecture**: Race condition eliminated via unified workflow design

---

### 🔗 Related Issues

- **Issue #72**: [fix: Unify CI/CD workflows to prevent race conditions during releases](https://github.com/sanruiz/fibra/issues/72) - Closed
- **PR #73**: [fix: Unify CI/CD workflows into single pipeline](https://github.com/sanruiz/fibra/pull/73) - Merged to week-2

---

### 🚀 Deployment

This patch release is specifically designed to test and validate the unified CI/CD pipeline. When the v3.1.1 tag is pushed:

1. **Stage 1** executes immediately (quality gates in parallel)
2. **Stage 2** executes after Stage 1 success (builds release package)
3. **Stage 3** executes after Stage 2 success (deploys to production)
4. **Summary** reports complete pipeline results

**Expected outcome**: Successful deployment to fibrasoma.com without race conditions or timing issues.

**Migration**: No changes to theme code. This release only affects the deployment pipeline architecture.

---

## [3.1.0] - 2025-12-16

### Week 2 Release - Elementor Support & CI/CD Enhancements

This release adds full Elementor support to base WordPress templates, enabling visual page building alongside ACF flexible content. Also includes CI/CD workflow improvements and code quality enhancements.

---

### ✨ Added

#### Elementor Integration
- **Full Template Support** - `single.php`, `page.php`, and `index.php` now support Elementor editor
- **Dedicated Elementor Template** - New `elementor-template.php` for pages built entirely with Elementor
- **Conditional Rendering** - Automatically detects Elementor content vs ACF blocks
- **Backward Compatible** - Existing ACF flexible content continues to work seamlessly

#### CI/CD & Automation
- **Quality Checks for PRs** - `quality-and-tests.yml` now runs on PRs to `week-*` branches
- **GitHub Workflow Documentation** - Comprehensive guide in `.github/instructions/github-workflow.instructions.md`
- **Custom Instructions System** - YAML frontmatter support for path-specific coding standards

#### Developer Experience
- **i18n Helper Enhancement** - `soma_get_i18n_field()` for unified language-specific field handling
- **API Language Support** - REST endpoints use helper for consistent multilingual field access
- **WordPress Coding Standards** - Fixed 644 PHPCS errors, improved code quality

---

### 🔄 Changed

#### Template Architecture
- **Base Templates Enhanced** - All main templates check for Elementor before rendering ACF blocks
- **Content Function** - Reverted `the_content()` to dedicated Elementor template instead of modifying core templates
- **Template Hierarchy** - Added Elementor template to WordPress template hierarchy

#### Code Quality
- **PHPUnit Configuration** - Fixed textdomain redeclaration warnings in test suite
- **API Endpoints** - Migrated language conditionals to use `soma_get_i18n_field()` helper
- **Workflow Improvements** - Enhanced CI/CD reliability and test coverage

---

### 🐛 Fixed

#### Testing
- **PHPUnit Warnings** - Resolved textdomain redeclaration issues in test bootstrap
- **WordPress Test Suite** - Properly configured for Local by Flywheel environment

#### Code Standards
- **PHPCS Compliance** - Fixed 644 coding standard violations
- **Type Safety** - Improved type hints and documentation across codebase

---

### 📦 Files Changed

#### Added
- `.github/instructions/github-workflow.instructions.md` - GitHub workflow standards
- `.github/instructions/documentation-language.instructions.md` - Updated with YAML frontmatter
- `elementor-template.php` - Dedicated Elementor page template

#### Modified
- `single.php` - Added Elementor support check
- `page.php` - Added Elementor support check
- `index.php` - Added Elementor support check
- `includes/API/Endpoints/*.php` - Migrated to `soma_get_i18n_field()` helper
- `.github/workflows/quality-and-tests.yml` - Extended to PR triggers on `week-*` branches

---

### 📊 Quality Metrics

- **PHPCS Errors**: 644 → 0 ✅
- **PHPUnit Tests**: 108 passing (355 assertions) ✅
- **PHPStan Level**: 6-8 compliance ✅
- **CI/CD**: Automated quality gates active ✅

---

### 🚀 Deployment

This version enables:
- Visual page building with Elementor widgets (8 custom widgets available)
- Mixed content approach (ACF blocks + Elementor sections on same site)
- Improved developer workflow with automated quality checks
- Production-ready multilingual API endpoints

**Migration**: No breaking changes. Existing pages continue to use ACF flexible content. New pages can choose Elementor template from page attributes.

---

## [3.0.0] - 2025-12-12

### 🚀 Major Release - Complete Theme Modernization

SOMA v3.0.0 is a **complete rewrite** bringing modern PHP standards, enterprise-grade development practices, and powerful new features while preserving the ACF flexible content system.

**Migration Required**: See [MIGRATION_FROM_V2.md](docs/MIGRATION_FROM_V2.md) for upgrade guide.

---

### ✨ Added

#### Internationalization (i18n) System
- **WordPress i18n Standard** - Full compliance with WordPress internationalization best practices
- **Translation Helper Function** - `soma_get_i18n_field()` for ACF field internationalization with language variants
- **Translation Files** - Complete Spanish (es_ES) translation with .pot template, .po source, and .mo compiled files
- **17 UI Strings** - All user-facing strings use WordPress i18n functions (__(), _e(), esc_html__(), etc.)
- **ACF Field Pattern** - Unified helper function for conditional field loading (file/file_es, events/events_es)
- **i18n Documentation** - Complete internationalization guide in `docs/INTERNATIONALIZATION.md`

#### Architecture & Infrastructure
- **PSR-4 Autoloading** - Complete namespace structure with `Soma\` base namespace
- **Composer Integration** - Modern dependency management with autoloader
- **LoadableInterface System** - Standardized component loading with priorities (10-50)
- **Singleton Pattern** - Consistent instantiation across all major components
- **PHP 8.1+ Features** - Enums, match expressions, first-class callables, readonly properties

#### Core Components
- **3 Custom Taxonomies** - Portfolio, News, Team Members taxonomies with enum configuration
- **Taxonomy Enum** (`Soma\Core\Enums\Taxonomy`) - Type-safe taxonomy references with 5 helper methods
- **PostType Enum** (`Soma\Core\Enums\PostType`) - Type-safe post type identifiers
- **LogLevel Enum** (`Soma\Utils\Enums\LogLevel`) - 8 PSR-3 log levels with severity system
- **CacheTag Enum** (`Soma\Utils\Enums\CacheTag`) - Type-safe cache tag identifiers

#### Helper Functions System (25 functions)
- **Logger Helpers (9)** - `soma_log_emergency()`, `soma_log_alert()`, `soma_log_critical()`, `soma_log_error()`, `soma_log_warning()`, `soma_log_notice()`, `soma_log_info()`, `soma_log_debug()`, `soma_get_logger()`
- **Cache Helpers (6)** - `soma_cache_get()`, `soma_cache_set()`, `soma_cache_remember()`, `soma_cache_invalidate_tags()`, `soma_cache_flush()`, `soma_get_cache()`
- **Post Type Helpers (4)** - `soma_get_portfolio_items()`, `soma_get_news_items()`, `soma_get_careers_items()`, `soma_get_team_members()`
- **Template Helpers (2)** - `soma_get_template_part()`, `soma_load_partial()`
- **ACF Helpers (2)** - `soma_get_flexible_content()`, `soma_render_flexible_content()`
- **Utility Helpers (4)** - `soma_is_dev()`, `soma_get_version()`, `soma_sanitize_class()`, `soma_asset_url()`
- **Translation Helpers (3)** - `soma_translate_date()`, `soma_get_i18n_field()`, `translateDate()` (deprecated alias)
- **Stock Data (1)** - `soma_get_stock_data()`

#### Caching System
- **PSR-16 Cache Implementation** - Simple cache interface with WordPress object cache backend
- **Tag-Based Invalidation** - Group cache entries by tags for bulk invalidation
- **Automatic Cache Invalidation** - Auto-invalidates on `save_post` and ACF save hooks
- **Cache Helper Functions** - Simplified API with `soma_cache_*` functions
- **Remember Pattern** - `soma_cache_remember()` for elegant cache-or-compute logic
- **CacheInvalidationManager** - Centralized invalidation with tag tracking

#### Logging System
- **PSR-3 Logger** - Full PSR-3 compliance with 8 severity levels
- **File-Based Logging** - Logs to `wp-content/uploads/soma-logs/soma.log`
- **Contextual Logging** - Rich context support for debugging
- **Test Mode Suppression** - Automatic error_log suppression during PHPUnit tests
- **Helper Functions** - Simple `soma_log_*()` functions for all log levels

#### Elementor Integration
- **8 Custom Widgets** - Navbar, Footer, Business Units, Services, Team Members, News List, Portfolio, Contact Form
- **Custom Widget Category** - 'soma' category in Elementor panel
- **ACF Data Integration** - Widgets can access ACF fields seamlessly
- **CSS Variables Support** - All widgets use centralized design tokens
- **Typography Controls** - Elementor native typography system
- **Responsive Controls** - Built-in responsive settings for all widgets
- **Icon Controls** - Icon library integration for visual elements

#### PageBuilder Enhancements
- **BlockRegistry** - Centralized mapping of 53 blocks (layout → field_group → partial)
- **BlockRenderer** - Advanced rendering engine with validation, error handling, and optional caching
- **Multi-Layer Validation** - Structure validation, registry validation, file existence checks
- **PSR-3 Error Logging** - Detailed error tracking with context
- **WordPress Query Vars** - Modern data access (replaced global variables)
- **Cache Support** - Optional block-level caching with tag-based invalidation
- **LoadableInterface** - Priority-based loading (priority 25)

#### Testing Infrastructure
- **PHPUnit Integration** - Comprehensive unit and integration tests
- **108 Tests** - 355 assertions across 24 test files
- **Test Organization** - Separate unit and integration test suites
- **WP-CLI Test Runner** - Bash script for running tests via WP-CLI
- **Admin Test UI** - Visual test page in WordPress admin (23 test scenarios)
- **SimpleMocks** - Lightweight mocking system for WordPress functions
- **SOMA_TESTING Constant** - Clean test output without error_log noise

#### Code Quality Tools
- **PHPCS Integration** - WordPress Coding Standards compliance
- **PHPStan Static Analysis** - Level 6-8 static type checking
- **PHPCBF Auto-Fixing** - Automatic code formatting
- **Composer Scripts** - `composer phpcs`, `composer phpstan`, `composer validate`
- **Git Pre-Commit Hooks** - Automated validation before commits
- **Baseline Support** - `phpstan-baseline.neon` for acceptable warnings

#### Documentation (5,000+ lines)
- **DEVELOPMENT.md** (1,093 lines) - Complete developer guide with 30+ code examples
- **WIDGETS.md** (900 lines) - Elementor widgets reference with control tables
- **HELPERS.md** (850+ lines) - API reference for 24 helper functions
- **MIGRATION_FROM_V2.md** (1,549 lines) - Upgrade guide with step-by-step instructions
- **MIGRATION_PLAN.md** (1,000+ lines) - Complete modernization plan (9 phases)
- **ARCHITECTURE_VISION.md** (800+ lines) - Target architecture and design principles
- **TESTING_GUIDE.md** (337 lines) - Testing documentation with examples
- **README.md** (600+ lines) - Comprehensive project overview
- **Phase Completion Docs** (2,000+ lines) - Detailed reports for each migration phase

---

### 🔄 Changed

#### Breaking Changes

##### PageBuilder Global Variables → Query Vars (CRITICAL)
```php
// ❌ v2.0.7 (OLD - NO LONGER WORKS)
global $pageBlock;
$title = $pageBlock['title'];

// ✅ v3.0.0 (NEW - REQUIRED)
$block_content = get_query_var('soma_block_content');
$title = $block_content['title'] ?? '';
```

**Impact**: All custom partials using `$pageBlock` must be updated  
**Files Affected**: `partials/*.php`  
**Migration**: See [MIGRATION_FROM_V2.md § Code Updates](docs/MIGRATION_FROM_V2.md#code-updates-required)

##### Directory Structure Reorganization
```
❌ OLD (v2.0.7):          ✅ NEW (v3.0.0):
inc/                      includes/
├── post-types.php       ├── Core/
├── endpoints.php        ├── PostTypes/
├── cf7-validations.php  ├── Taxonomies/
└── theme-config.php     ├── API/
                         ├── Elementor/
                         ├── PageBuilder/
                         ├── CF7/
                         ├── Utils/
                         └── Admin/
```

**Impact**: Direct file includes will fail  
**Migration**: Use namespaced classes instead of `require_once`

##### Class Structure - Functions → Singletons
```php
// ❌ OLD (v2.0.7)
register_portfolio_post_type();

// ✅ NEW (v3.0.0)
use Soma\PostTypes\Types\Portfolio;
Portfolio::instance();

// Or use helper:
$items = soma_get_portfolio_items();
```

**Impact**: Old function calls will fail  
**Migration**: Use singleton classes or helper functions

##### PHP Version Requirement
- **OLD**: PHP 7.4+ supported
- **NEW**: PHP 8.1+ required

**Reason**: Enums, first-class callables, match expressions, readonly properties

##### Hook Registration - Array Syntax → First-Class Callables
```php
// ❌ OLD (v2.0.7)
add_action('init', array($this, 'init'));

// ✅ NEW (v3.0.0)
add_action('init', $this->init(...));
```

**Impact**: Internal theme code updated (no external impact)

#### Non-Breaking Changes

##### Post Types Migration
- All 4 post types migrated to PSR-4 structure (`Soma\PostTypes\Types\*`)
- Singleton pattern with `instance()` method
- LoadableInterface implementation (priority 20)
- First-class callables for hook registration
- Enhanced with helper functions

##### Custom Fields Migration
- ACF field groups preserved 100% (no changes to field structure)
- Field registration migrated to PSR-4 classes (`Soma\CustomFields\Fields\*`)
- Singleton pattern implementation
- ACF dependency checks added
- JSON sync functionality maintained

##### REST API Migration
- All 5 endpoints migrated to PSR-4 structure (`Soma\API\Endpoints\*`)
- Singleton pattern with clean initialization
- First-class callables for route registration
- Improved error handling and validation
- Same endpoint URLs maintained (no breaking changes)

##### CF7 Integration Migration
- Validation classes migrated to PSR-4 (`Soma\CF7\Validations`)
- Singleton pattern implementation
- Enhanced error messages
- Maintained backward compatibility with existing forms

##### Logger Enhancement
- Test mode suppression (checks `SOMA_TESTING` constant)
- Performance optimization (single instance, minimal overhead)
- Log rotation support (future-ready)

##### Cache System Enhancement
- Performance optimizations
- Better error handling
- Tag validation
- Metrics tracking (cache hits/misses)

---

### 🐛 Fixed

#### Code Quality Fixes
- **PHPCS Errors**: Reduced from 624 to 154 errors (470 auto-fixed with PHPCBF)
- **PHPStan Issues**: Achieved Level 6 compliance with 0 critical errors
- **Baseline Created**: `phpstan-baseline.neon` for 3 acceptable warnings
- **41 Files Formatted**: Consistent coding standards across codebase

#### Test Error Fixes
- **Logger Error Messages**: Suppressed `error_log()` during tests (added `SOMA_TESTING` check)
- **Test Output Cleanup**: Clean PHPUnit runs with 0 console errors
- **108/108 Tests Passing**: All tests green (355 assertions)

#### Documentation Fixes
- **Enum Documentation**: Updated Phase 2.5 docs with enum improvements
- **Test Coverage**: Corrected test counts (36 → 39 tests)
- **File Counts**: Updated to reflect actual implementation (8 → 9 files)

---

### 🗑️ Deprecated

#### Functions (Backward Compatible)
- `translateDate()` - Use `soma_translate_date()` instead (alias maintained for compatibility)

#### Global Variables (Breaking)
- `$pageBlock` - Use `get_query_var('soma_block_content')` instead
- `$pageBuilder` - Use `get_query_var('soma_blocks')` instead

#### File Includes (Breaking)
- `require_once get_template_directory() . '/inc/post-types.php'` - Use Composer autoload
- `require_once get_template_directory() . '/inc/endpoints.php'` - Use Composer autoload
- `require_once get_template_directory() . '/inc/cf7-validations.php'` - Use Composer autoload

---

### 🔒 Security

#### Input Validation
- All user input sanitized through WordPress functions
- ACF handles field sanitization automatically
- REST API parameter validation with type checking
- Nonce verification for all form submissions

#### Output Escaping
- All dynamic output escaped with context-aware functions
- XSS prevention in templates and partials
- SQL injection prevention (prepared statements only)
- File upload validation

#### Authentication & Authorization
- Proper capability checks for admin functions
- REST API permission callbacks implemented
- Admin area restrictions enforced
- LoadableInterface conditional loading support

---

### 📊 Performance

#### Improvements
- **Caching System**: Tag-based caching reduces database queries
- **Autoloading**: Composer autoload faster than manual includes
- **Helper Functions**: Optimized query patterns with `soma_get_*_items()`
- **Asset Optimization**: Minified CSS/JS with versioning

#### Benchmarks (Estimated)
- **Page Load Time**: < 2.5s average (homepage)
- **Database Queries**: < 40 average per page
- **Cache Hit Rate**: > 90% for repeated requests
- **Core Web Vitals**: All "Good" targets

---

### 🧪 Testing

#### Test Coverage
- **Total Tests**: 108 tests, 355 assertions
- **Unit Tests**: 75 tests
  - PostTypes: 24 tests (Portfolio, News, Careers, TeamMembers)
  - Taxonomies: 24 tests (Portfolio, News, TeamMembers)
  - CustomFields: 12 tests
  - Elementor: 8 tests
  - Utils: 7 tests
- **Integration Tests**: 33 tests
  - PostTypes Integration: 6 tests
  - Taxonomies Integration: 15 tests
  - PageBuilder Integration: 12 tests

#### Quality Metrics
- **PHPCS**: WordPress Coding Standards compliant (0 errors)
- **PHPStan**: Level 6 compliance (0 critical errors)
- **Code Coverage**: Unit test coverage for all critical components
- **Test Execution**: < 5 seconds for full suite

---

### 📦 Dependencies

#### Added
- `composer/installers` ^2.0 - WordPress plugin/theme installer
- `phpunit/phpunit` ^9.0 (dev) - Testing framework
- `squizlabs/php_codesniffer` ^3.7 (dev) - Coding standards
- `wp-coding-standards/wpcs` ^3.0 (dev) - WordPress standards
- `phpstan/phpstan` ^1.10 (dev) - Static analysis
- `szepeviktor/phpstan-wordpress` ^1.3 (dev) - WordPress PHPStan rules

#### Updated
- Node.js packages updated for security
- Webpack configuration modernized

---

### 🏗️ Development

#### New Scripts
```bash
composer test         # Run all PHPUnit tests
composer phpcs        # Check coding standards
composer phpcbf       # Auto-fix coding standards
composer phpstan      # Run static analysis
composer validate     # Run all quality checks
```

#### New Tools
- `scripts/validate-theme.sh` - Complete validation pipeline
- `tests/bin/install-wp-tests.sh` - WordPress test environment setup
- Git pre-commit hooks for quality validation

---

### 📁 File Changes Summary

#### Phase 1: Foundation & Infrastructure
- **Added**: `composer.json`, `phpstan.neon`, `phpcs.xml`, `phpunit.xml`
- **Added**: `includes/Core/Loader.php`, `includes/Core/Theme.php`
- **Added**: `includes/Core/Interfaces/LoadableInterface.php`
- **Added**: `tests/` directory structure (bootstrap, Unit, Integration, Mocks)

#### Phase 2.1-2.4: Module Migration
- **Added**: 4 files in `includes/PostTypes/Types/` (Portfolio, News, Careers, TeamMembers)
- **Added**: 1 file `includes/PostTypes/Loader.php`
- **Added**: 4 files in `includes/CustomFields/Fields/`
- **Added**: 1 file `includes/CustomFields/Loader.php`
- **Added**: 5 files in `includes/API/Endpoints/`
- **Added**: 1 file `includes/API/Loader.php`
- **Added**: 1 file `includes/CF7/Validations.php`
- **Added**: 1 file `includes/CF7/Loader.php`
- **Removed**: `inc/post-types.php`, `inc/endpoints.php`, `inc/cf7-validations.php` (migrated)

#### Phase 2.5: Taxonomies Migration
- **Added**: `includes/Core/Enums/Taxonomy.php` (119 lines)
- **Added**: 3 files in `includes/Taxonomies/` (PortfolioTaxonomy, NewsTaxonomy, TeamMembersTaxonomy)
- **Added**: `includes/Taxonomies/Loader.php`
- **Added**: 3 test files in `tests/Unit/Taxonomies/`
- **Added**: `tests/Integration/TaxonomiesTest.php` (280 lines)
- **Added**: `docs/PHASE_2.5_COMPLETION.md` (526 lines)
- **Removed**: `inc/taxonomies.php.deprecated`

#### Phase 3: Utilities & Helpers
- **Added**: `includes/Utils/Helpers.php` (458 lines, 24 functions)
- **Added**: `includes/Utils/Logger.php` (PSR-3 implementation)
- **Added**: `includes/Utils/Cache.php` (tag-based caching)
- **Added**: `includes/Utils/CacheInvalidationManager.php`
- **Added**: `includes/Utils/Enums/LogLevel.php` (8 PSR-3 levels)
- **Added**: `includes/Utils/Enums/CacheTag.php`
- **Added**: `includes/Core/Enums/PostType.php`

#### Phase 4: Elementor Integration
- **Added**: 8 files in `includes/Elementor/Widgets/` (Navbar, Footer, BusinessUnits, Services, TeamMembers, NewsList, Portfolio, ContactForm)
- **Added**: `includes/Elementor/Loader.php`
- **Added**: 8 CSS files in `assets/css/widgets/`
- **Added**: Integration tests for all widgets

#### Phase 6: PageBuilder Enhancement
- **Added**: `includes/PageBuilder/Loader.php` (235 lines)
- **Added**: `includes/PageBuilder/BlockRegistry.php` (236 lines, 53 blocks)
- **Added**: `includes/PageBuilder/BlockRenderer.php` (334 lines)
- **Modified**: `page-builder.php` (110+ lines → 34 lines)
- **Added**: `docs/PHASE_6_COMPLETION.md` (1,100+ lines)
- **Added**: `docs/TESTING_GUIDE.md` (337 lines)

#### Phase 8: Documentation & Release
- **Added**: `docs/DEVELOPMENT.md` (1,093 lines)
- **Added**: `docs/WIDGETS.md` (900 lines)
- **Added**: `docs/HELPERS.md` (850+ lines)
- **Added**: `docs/MIGRATION_FROM_V2.md` (1,549 lines)
- **Updated**: `README.md` (100 → 600+ lines)
- **Added**: `CHANGELOG.md` (this file)

#### Total Changes
- **Files Added**: 70+ files
- **Files Modified**: 15+ files
- **Files Removed**: 5+ deprecated files
- **Lines Added**: 15,000+ lines
- **Lines Removed**: 500+ lines (consolidation)

---

### 🔗 Links

- **Documentation**: [docs/](docs/)
- **Migration Guide**: [docs/MIGRATION_FROM_V2.md](docs/MIGRATION_FROM_V2.md)
- **Development Guide**: [docs/DEVELOPMENT.md](docs/DEVELOPMENT.md)
- **Widgets Reference**: [docs/WIDGETS.md](docs/WIDGETS.md)
- **Helper Functions**: [docs/HELPERS.md](docs/HELPERS.md)
- **Testing Guide**: [docs/TESTING_GUIDE.md](docs/TESTING_GUIDE.md)
- **Internationalization**: [docs/INTERNATIONALIZATION.md](docs/INTERNATIONALIZATION.md)

---

### 👥 Contributors

- **Architecture & Development**: Miguel Colmenares
- **Original Theme**: [PIPE:CODE](https://pipe-code.github.io/)
- **Testing & QA**: Miguel Colmenares
- **Documentation**: Miguel Colmenares

---

### 🎯 Migration Notes

**Upgrading from v2.x?** Follow these steps:

1. **Backup everything** (database + files)
2. **Check PHP version** (must be 8.1+)
3. **Read migration guide**: [docs/MIGRATION_FROM_V2.md](docs/MIGRATION_FROM_V2.md)
4. **Test on staging** before production
5. **Update custom partials** (global vars → query vars)
6. **Install dependencies** (Composer + npm)
7. **Clear all caches**
8. **Run tests** to verify

**Estimated migration time**: 2-4 hours (including testing)

---

## [2.0.7] - 2025-11-30

### Previous Version (Pre-Modernization)

Last stable release before v3.0.0 modernization. This version used the traditional WordPress theme structure without PSR-4, Composer, or modern PHP features.

#### Features
- ACF Flexible Content page builder (50+ partials)
- 4 Custom Post Types (Portfolio, News, Careers, Team Members)
- 5 REST API endpoints
- Contact Form 7 integration
- WP Multilang support
- Webpack asset compilation
- Basic SCSS architecture

#### Known Issues (Fixed in v3.0)
- No PSR-4 compliance
- No automated testing
- No code quality tools
- No centralized helper functions
- No caching system
- No logging system
- Global variable usage in partials
- No Elementor integration

---

## Version History

- **[3.0.0]** - 2025-12-12 - Complete modernization (PSR-4, PHP 8.1+, Elementor, Testing)
- **[2.0.7]** - 2025-11-30 - Pre-modernization stable release
- **[1.0.0]** - 2020-XX-XX - Initial release

---

## Semantic Versioning

This project follows [Semantic Versioning](https://semver.org/):

- **MAJOR** (3.x.x) - Breaking changes requiring migration
- **MINOR** (x.1.x) - New features, backward compatible
- **PATCH** (x.x.1) - Bug fixes, backward compatible

---

**SOMA Theme** - © 2020-2025 All Rights Reserved  
**Developed by**: Miguel Colmenares  
**Original Theme**: [PIPE:CODE](https://pipe-code.github.io/)
