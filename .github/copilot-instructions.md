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

### 🤔 Global vs Reactive: When to Use Which?

**Understanding the Architecture:**
- **Global** (`copilot-instructions.md`): ALWAYS loaded by VS Code, regardless of which files are open
- **Reactive** (`.github/instructions/*.md`): Only loaded when files matching `applyTo` pattern are opened

**What Goes in Global (this file):**
| Content Type | Reason |
|--------------|--------|
| Project context & status | Always needed for any task |
| Repository structure | Essential for navigation |
| Commit & branch conventions | Needed during git operations (no files open) |
| Release workflow commands | Used in terminal without editing files |
| PR review comment commands | GitHub API operations |
| Common pitfalls | Quick reference across all contexts |
| Architecture overview (PSR-4, PageBuilder) | Foundational knowledge |

**What Goes in Reactive Instructions:**
| Content Type | File | Reason |
|--------------|------|--------|
| PHP coding standards, security patterns | `php.instructions.md` | Only when editing `.php` files |
| Elementor widget workflow | `elementor-widgets.instructions.md` | Only when in Widgets directory |
| PHPUnit conventions | `testing.instructions.md` | Only when editing tests |
| Full workflow details | `github-workflow.instructions.md` | Detailed reference (not quick commands) |
| Documentation standards | `documentation-language.instructions.md` | When writing docs |

**Rule of Thumb:**
- ✅ **Global**: Commands you run in terminal, context needed without files open, quick references
- ✅ **Reactive**: Detailed coding standards, file-type-specific patterns, comprehensive guides
- ⚠️ **Avoid duplication**: If detailed content exists in reactive files, only add a summary to global


## 🎯 Agent Skills

This project includes **custom Agent Skills** that provide detailed knowledge for specific complex tasks. Skills are located in `.github/skills/` and should be consulted when performing related operations.

### Available Skills

| Skill | Location | Use When |
|-------|----------|----------|
| **Elementor Widget Creation** | `.github/skills/elementor-widget-creation/` | Creating new Elementor widgets or modifying existing ones |
| **PHPUnit Testing** | `.github/skills/phpunit-testing/` | Writing or updating tests, troubleshooting test failures |
| **GitHub Release Workflow** | `.github/skills/github-release-workflow/` | Creating releases, troubleshooting CI/CD, version management |
| **ACF Block Development** | `.github/skills/acf-block-development/` | Creating ACF flexible content blocks or PageBuilder partials |
| **GitHub Actions Debugging** | `.github/skills/github-actions-debugging/` | Diagnosing workflow failures, optimizing CI/CD pipelines |

### How to Use Skills

1. **Read the SKILL.md file** in the relevant skill directory before starting work
2. **Follow the checklist** provided in each skill for task completion
3. **Reference skill patterns** when implementing similar features
4. **Update skills** when discovering new patterns or solutions

### When to Consult Skills

- ✅ **Always**: Before creating Elementor widgets, ACF blocks, or writing tests
- ✅ **On Errors**: When CI/CD fails or tests break
- ✅ **For Releases**: When preparing version releases or managing deployments

---

## 🔄 Copilot Agent Workflow for Complex Tasks

When implementing new features, refactoring code, or fixing complex issues, **always follow this systematic workflow**:

### Phase 1: Initial Analysis
1. **Analyze the request** - Understand the full scope, dependencies, and potential impacts
2. **Search existing code** - Use semantic search and grep to understand current implementation
3. **Identify components** - List all files, functions, and components that need changes
4. **Review documentation** - Check existing docs for patterns and conventions

### Phase 2: Planning Documentation
1. **Create planning document** - `wp-content/themes/soma/docs/[feature-name]-plan.md` with:
   - Problem statement and objectives
   - Current architecture analysis
   - Proposed changes with before/after code examples
   - Risk assessment and mitigation strategies
   - Phase breakdown if complex (separate into logical phases)
   - Priority assignment (HIGH/MEDIUM/LOW)
2. **Add action plan** - Detailed step-by-step implementation guide
3. **Create TODO list** - Use `manage_todo_list` tool to track all phases
4. **Commit planning** - `git commit -m "#XX: Add [feature] implementation plan"`

### Phase 3: Implementation by Phases
For each phase:
1. **Mark TODO as in-progress** - Update status before starting work
2. **Implement changes** - Make code changes following the plan
3. **Write/update tests** - Add unit tests, ensure regression tests pass
4. **Run tests** - `composer test` to verify no regressions
5. **Mark TODO as completed** - Update status after successful implementation
6. **Commit phase** - `git commit -m "#XX: Implement [feature] - Phase N"`
7. **Validate** - Check build, tests, and functionality

### Phase 4: Final Documentation
1. **Create final documentation** - `wp-content/themes/soma/docs/[feature-name].md` with:
   - Feature overview and usage guide
   - API documentation and examples
   - Integration guide
   - Testing strategy
   - Troubleshooting section
2. **Update related docs** - Update `readme.md`, etc.
3. **Update Copilot context** - Add patterns and conventions to this file
4. **Delete planning docs** - Remove temporary planning documents
5. **Final commit** - `git commit -m "#XX: Add [feature] documentation"`

### Key Principles
- ✅ **One commit per phase** - Create clear checkpoint commits
- ✅ **Test everything** - Run full test suite after each phase
- ✅ **No breaking changes** - Ensure backward compatibility
- ✅ **Document as you go** - Update docs with each phase
- ✅ **Clean up** - Remove temporary planning files at the end
- ✅ **Type safety** - Maintain full TypeScript coverage
- ✅ **Follow patterns** - Use existing project patterns and conventions

### Example Workflow
```bash
# Phase 1: Analysis and Planning
[semantic_search, grep_search, read_file]
create_file("wp-content/themes/soma/docs/feature-plan.md")
manage_todo_list(write, [todos])
git commit -m "#XX: Add feature implementation plan"

# Phase 2-N: Implementation Phases
manage_todo_list(update, phase1_in_progress)
[make changes]
composer test
manage_todo_list(update, phase1_completed)
git commit -m "#XX: Implement feature - Phase 1"

# Final Phase: Documentation
create_file("wp-content/themes/soma/docs/feature.md")
update copilot-instructions.md
delete planning docs
git commit -m "#XX: Add feature documentation and cleanup"
```

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

## 🏗️ PSR-4 & Modern PHP Conventions (v3.0.0+)

### Singleton Pattern

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

### First-Class Callables (PHP 8.1+)

```php
add_action( 'rest_api_init', $this->register(...) ); // ✅ Modern
// NOT: add_action( 'rest_api_init', array( $this, 'register' ) ); // ❌ Old
```

### ReflectionProperty (PHP 8.1+)

In PHP 8.1+, `setAccessible()` is deprecated. Private/protected properties are accessible via `getValue()` and `setValue()` without it:

```php
// ✅ CORRECT (PHP 8.1+)
$reflection = new \ReflectionClass( ClassName::class );
$property   = $reflection->getProperty( 'instance' );
$property->setValue( null, null ); // Works without setAccessible()

// ❌ DEPRECATED - Do NOT use
$property->setAccessible( true ); // Deprecated in PHP 8.1+
```

### LoadableInterface Pattern

All module loaders must implement `Soma\Core\Interfaces\LoadableInterface`:
- `init()`: Initialize the component
- `get_priority()`: Return loading priority (10-50)
- `should_load()`: Conditional loading check

**Priority System:**
- 10: Utilities (must load FIRST)
- 20: Post Types
- 25: PageBuilder, CustomFields
- 30: CF7, Elementor
- 35: REST API
- 40: Admin

---

## � Commit & Branch Conventions (Global)

**⚠️ Note**: These conventions are global because they're needed when making commits/PRs without files open.

### Branch Naming

```
main                → Production branch (stable releases only)
develop             → Development branch (integration branch for features)
feature/description → New features (e.g., feature/hero-section)
fix/description     → Bug fixes (e.g., fix/navbar-mobile)
chore/description   → Maintenance tasks (e.g., chore/update-deps)
hotfix/description  → Emergency fixes (e.g., hotfix/security-patch)
release/vX.Y.Z      → Release preparation (e.g., release/v3.1.3)
```

### Commit Message Format (Conventional Commits)

```
type(scope): brief description

Detailed explanation of changes (optional)

- Bullet point 1
- Bullet point 2

Closes #issue-number (if applicable)
```

### Commit Types

| Type | Usage |
|------|-------|
| `feat` | New features |
| `fix` | Bug fixes |
| `docs` | Documentation only |
| `style` | Code formatting (no logic change) |
| `refactor` | Code refactoring |
| `perf` | Performance improvements |
| `test` | Add or update tests |
| `build` | Build system changes |
| `ci` | CI/CD changes |
| `chore` | Maintenance tasks |
| `revert` | Revert previous commit |

### Scope Examples

`(homepage)`, `(navbar)`, `(portfolio)`, `(ci)`, `(deps)`, `(docs)`, `(tests)`, `(elementor)`

---

## 🚀 Release Workflow (Global Commands)

**⚠️ Note**: These commands are global because path-specific instructions are reactive (only activate when `applyTo` matches).

### 🚨 CRITICAL: Tags Must Be Created from `main` Branch Only

**NEVER create tags from `develop` branch.** Tags created before merging to main become orphaned when using squash merge, because:

1. Squash merge creates a NEW commit in `main` (combining all commits)
2. The original commits in `develop` are NOT ancestors of `main`
3. Tags pointing to those commits become "orphaned" (not reachable from any branch)

```
❌ WRONG: Create tag on develop, then merge develop to main
   → Tag points to commit that doesn't exist in main history

✅ CORRECT: Merge develop to main FIRST, then create tag on main
   → Tag points to the squash merge commit in main
```

**The correct order is:**
1. Merge `develop` → `main` (via PR with squash)
2. Checkout `main` and pull
3. Create tag on `main`
4. Push tag (triggers release workflow)

### Standard Release (from develop to main)

```bash
# 1. Ensure all features merged to develop
gh pr list --base develop | cat  # Should be empty

# 2. Verify quality gates pass
cd wp-content/themes/soma
composer phpcs && composer phpstan && composer test
npm run prod

# 3. Create release branch from develop
git checkout develop
git pull origin develop
git checkout -b release/vX.Y.Z

# 4. Update version files
# - wp-content/themes/soma/style.css: Version: X.Y.Z
# - wp-content/themes/soma/CHANGELOG.md: Add release notes

# 5. Commit version bump
git add style.css CHANGELOG.md
git commit -m "chore: Prepare release vX.Y.Z"

# 6. Push and create PR to main
git push -u origin release/vX.Y.Z
gh pr create --base main --title "Release vX.Y.Z" | cat

# 7. Wait for CI and merge
gh pr merge NUMBER --squash --delete-branch | cat

# 8. 🚨 CRITICAL: Checkout main and create tag FROM MAIN
# Tags MUST be created after merging to main, not before!
git checkout main
git pull origin main
# Verify you're on main with the merge commit:
git log -1 --oneline  # Should show the squash merge commit
git tag -a vX.Y.Z -m "Release vX.Y.Z: Description"

# 9. Push tag (triggers CI/CD → Release → Deploy)
git push origin vX.Y.Z

# 10. Merge main back to develop
git checkout develop
git merge main
git push origin develop

# 11. Monitor workflow
gh run watch
gh release view vX.Y.Z | cat
```

**Expected CI/CD Flow:**
1. ✅ Stage 1: Quality Gates (code-quality, php-tests, frontend-build) ~2min
2. ✅ Stage 2: Build & Release (creates soma-vX.Y.Z.zip, GitHub release **automatically**)
3. ✅ Stage 3: Deploy to Production (SFTP upload, backup, extract)
4. ✅ Total: ~5-6 minutes

**⚠️ IMPORTANT**: The GitHub release is created **automatically** by ci-cd.yml. NEVER use `gh release create` manually.

### Hotfix Workflow (Emergency Production Fix)

```bash
# 1. Create hotfix branch from main (NOT develop)
git checkout main
git pull origin main
git checkout -b hotfix/critical-issue

# 2. Apply fix and test
composer test && npm run prod

# 3. Commit and create PR to main
git add . && git commit -m "fix: Critical issue"
git push -u origin hotfix/critical-issue
gh pr create --base main --title "HOTFIX: Critical issue" --label "bug,alta-prioridad,hotfix" | cat

# 4. After emergency approval, merge
gh pr merge NUMBER --squash | cat

# 5. Create patch release tag
git checkout main && git pull origin main
# Update version (patch): 3.1.2 → 3.1.3
git tag -a v3.1.3 -m "Hotfix v3.1.3: Critical security patch"
git push origin v3.1.3

# 6. Backport to develop
git checkout develop
git cherry-pick COMMIT_SHA
git push origin develop
```

### 📝 Responding to PR Review Comments

When a PR has review comments from Copilot Code Review or other reviewers, use these commands to list and respond:

**List all review comments with IDs and file locations:**
```bash
gh api repos/sanruiz/fibra/pulls/<pr-number>/comments --jq '.[] | "ID: \(.id) | File: \(.path):\(.line // .original_line)"' | cat
```

**Get valid commit SHA from PR (required for replies):**
```bash
gh api repos/sanruiz/fibra/pulls/<pr-number>/commits --jq '.[].sha' | cat
```

**Reply to a review comment:**
```bash
gh api repos/sanruiz/fibra/pulls/<pr-number>/comments -X POST --input - <<EOF | cat
{
  "body": "Fixed in commit <short-sha>. <description>",
  "commit_id": "<full-40-char-sha-from-pr>",
  "path": "<file-path>",
  "line": <line-number>,
  "in_reply_to": <original-comment-id>
}
EOF
```

**GraphQL API (Recommended for Copilot Review):**
```bash
# Get review thread IDs
gh api graphql -f query='
query {
  repository(owner: "sanruiz", name: "fibra") {
    pullRequest(number: <pr-number>) {
      reviewThreads(first: 20) {
        nodes {
          id
          path
          isResolved
          comments(first: 1) { nodes { id body } }
        }
      }
    }
  }
}' | cat

# Reply to thread (uses PRRT_* thread ID)
gh api graphql -f query='
mutation {
  addPullRequestReviewThreadReply(input: {
    pullRequestReviewThreadId: "PRRT_kwDONqY9Pc6XXXXXXX",
    body: "Fixed in commit abc1234. Description of the fix."
  }) {
    comment { id body }
  }
}' | cat
```

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
