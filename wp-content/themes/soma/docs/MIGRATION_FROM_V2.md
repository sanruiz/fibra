# SOMA Theme Migration Guide: v2.x → v3.0

**From**: SOMA v2.0.7 (Legacy)  
**To**: SOMA v3.0.0 (Modernized)  
**Migration Date**: December 2025  
**Estimated Time**: 2-4 hours (depending on customizations)

---

## Table of Contents

1. [Overview](#overview)
2. [What's New in v3.0](#whats-new-in-v30)
3. [Breaking Changes](#breaking-changes)
4. [Pre-Migration Checklist](#pre-migration-checklist)
5. [Step-by-Step Migration](#step-by-step-migration)
6. [Code Updates Required](#code-updates-required)
7. [Testing & Validation](#testing--validation)
8. [Rollback Plan](#rollback-plan)
9. [Troubleshooting](#troubleshooting)
10. [FAQ](#faq)

---

## Overview

SOMA v3.0 is a **major modernization** of the theme, bringing PSR-4 compliance, PHP 8.1+ features, Elementor integration, and enterprise-grade development practices while preserving the powerful ACF flexible content system.

### Migration Type

**Type**: Major version upgrade  
**Backward Compatibility**: 95% (see Breaking Changes)  
**Data Migration**: Not required (ACF fields preserved)  
**Downtime**: ~5-10 minutes  
**Risk Level**: Low to Medium

### Who Should Migrate?

✅ **Safe to migrate if:**
- You're using PHP 8.1 or higher
- Your site uses standard ACF flexible content
- No heavy custom modifications to theme core
- You have a staging environment for testing

⚠️ **Proceed with caution if:**
- Custom code uses `$pageBlock` global variable
- Custom modifications to `inc/` files
- Tight production deadlines (test thoroughly first)

❌ **Wait if:**
- PHP version < 8.1
- No staging environment available
- Mission-critical site with zero tolerance for issues

---

## What's New in v3.0

### 🏗️ Architecture Improvements

**PSR-4 Compliance**
- All classes in `includes/` follow PSR-4 autoloading
- Base namespace: `Soma\`
- Composer-based dependency management

**Modern PHP 8.1+ Features**
- Enums for type safety (`PostType`, `Taxonomy`, `LogLevel`, `CacheTag`)
- Match expressions
- First-class callables
- Readonly properties
- Nullsafe operator

**LoadableInterface System**
- Standardized component loading
- Priority-based initialization
- Conditional loading support

### 📦 New Features

**Elementor Integration**
- 8 custom widgets ready to use
- Custom 'soma' widget category
- ACF data integration in widgets
- Visual page building option

**Advanced Caching**
- Tag-based cache system
- Automatic invalidation on save
- Cache helper functions (`soma_cache_*`)

**PSR-3 Logging**
- 8 log levels (emergency → debug)
- File-based logging (`wp-content/uploads/soma-logs/`)
- Context-rich error tracking
- Logger helper functions (`soma_log_*`)

**Taxonomies System**
- 3 custom taxonomies migrated
- Enum-based configuration
- Type-safe taxonomy references

**Helper Functions**
- 24+ global `soma_*` functions
- Logger, cache, post types, templates, ACF, utilities
- Centralized in `includes/Utils/Helpers.php`

### 🧪 Quality Improvements

**Testing Infrastructure**
- PHPUnit integration (108 tests, 355 assertions)
- Unit and integration test coverage
- WP-CLI test runner
- Admin test UI

**Code Quality Tools**
- PHPCS (WordPress Coding Standards)
- PHPStan Level 6-8 static analysis
- PHPCBF auto-formatting
- Git pre-commit hooks

**Documentation**
- Complete developer guides (4 major docs, 5000+ lines)
- API references (widgets, helpers)
- Testing guides
- Migration guide (this document)

---

## Breaking Changes

### 🔴 CRITICAL: PageBuilder Global Variables Removed

**What Changed:**
```php
// ❌ v2.0.7 (OLD - NO LONGER WORKS)
global $pageBlock;
$title = $pageBlock['title'];
$content = $pageBlock['content'];

// ✅ v3.0.0 (NEW - REQUIRED)
$block_counter = get_query_var('soma_block_counter');
$block_content = get_query_var('soma_block_content');
$block_layout = get_query_var('soma_block_layout');

$title = $block_content['title'];
$content = $block_content['content'];
```

**Impact**: Custom partials using `$pageBlock` will fail  
**Fix Required**: Update all custom partials to use query vars  
**File Location**: `partials/*.php`

**Migration Example:**
```php
// OLD: partials/CustomSection.php (v2.0.7)
<?php
global $pageBlock;
$heading = $pageBlock['section_heading'];
$items = $pageBlock['section_items'];
?>

// NEW: partials/CustomSection.php (v3.0.0)
<?php
$block_content = get_query_var('soma_block_content');
$heading = $block_content['section_heading'] ?? '';
$items = $block_content['section_items'] ?? [];
?>
```

---

### 🔴 Directory Structure Changed

**What Changed:**
```
❌ OLD (v2.0.7):
inc/
├── post-types.php
├── endpoints.php
├── cf7-validations.php
└── theme-config.php

✅ NEW (v3.0.0):
includes/
├── Core/
├── PostTypes/
├── Taxonomies/
├── API/
├── CF7/
├── Elementor/
├── PageBuilder/
├── Utils/
└── Admin/
```

**Impact**: Direct file includes will fail  
**Fix Required**: Use class imports instead

**Migration Example:**
```php
// ❌ OLD (v2.0.7)
require_once get_template_directory() . '/inc/post-types.php';

// ✅ NEW (v3.0.0)
use Soma\PostTypes\Loader;
Loader::instance()->init();
```

---

### 🟡 MEDIUM: Class Structure Changes

**What Changed:**
All major components now use singleton pattern and namespaces.

**OLD (v2.0.7):**
```php
// Direct function calls
register_portfolio_post_type();
register_news_post_type();
```

**NEW (v3.0.0):**
```php
// Singleton classes with namespaces
use Soma\PostTypes\Types\Portfolio;
use Soma\PostTypes\Types\News;

Portfolio::instance();
News::instance();
```

**Impact**: Custom code calling old functions will fail  
**Fix Required**: Use new singleton classes or helper functions

---

### 🟡 Helper Functions Added (Non-Breaking)

**What Changed:**
New `soma_*` prefixed helper functions available globally.

**Examples:**
```php
// Post Type Queries
$portfolio = soma_get_portfolio_items(['posts_per_page' => 5]);
$news = soma_get_news_items();
$careers = soma_get_careers_items();
$team = soma_get_team_members();

// Caching
$data = soma_cache_remember('key', function() {
    return expensive_operation();
}, 3600);

// Logging
soma_log_error('Something went wrong', ['context' => 'data']);
soma_log_info('Operation successful');

// Templates
soma_get_template_part('partials/Header', null, ['title' => 'Custom']);
```

**Impact**: None (additions only)  
**Recommendation**: Use these for cleaner code

---

### 🟢 LOW: PHP Version Requirement

**What Changed:**
- **OLD**: PHP 7.4+ supported
- **NEW**: PHP 8.1+ required

**Why:** Enums, first-class callables, match expressions

**Migration:**
```bash
# Check current PHP version
php -v

# If < 8.1, upgrade PHP first
# Contact hosting provider or update server
```

---

### 🟢 ACF Flexible Content (No Breaking Changes)

**What Stayed the Same:**
✅ ACF field groups work identically  
✅ `soma_blocks` field name unchanged  
✅ ACF JSON sync still works  
✅ All existing partials compatible (with query var update)  
✅ `page-builder.php` still renders blocks

**What Improved:**
- Better error handling and validation
- Optional caching for blocks
- PSR-3 logging for debugging
- Multi-layer validation

---

## Pre-Migration Checklist

### 1. Environment Requirements

```bash
# ✅ Check PHP version (must be 8.1+)
php -v
# Output should show: PHP 8.1.x or higher

# ✅ Check Composer installed
composer --version
# If not installed: https://getcomposer.org/

# ✅ Check Node.js (for asset compilation)
node -v
npm -v
# Recommended: Node 16+ and npm 8+

# ✅ Check write permissions
ls -la wp-content/themes/soma
# Should be writable by web server user
```

### 2. WordPress & Plugins

```
✅ WordPress 6.0+ installed
✅ Advanced Custom Fields PRO active
✅ Contact Form 7 active
✅ Safe SVG active
✅ WP Multilang active (if using multilingual)
✅ All plugins updated to latest versions
```

### 3. Backup Everything

```bash
# ✅ Database backup
wp db export backup-$(date +%Y%m%d).sql

# ✅ Theme files backup
cd wp-content/themes
tar -czf soma-v2.0.7-backup-$(date +%Y%m%d).tar.gz soma/

# ✅ Uploads backup (optional but recommended)
tar -czf uploads-backup-$(date +%Y%m%d).tar.gz ../uploads/
```

### 4. Document Custom Code

```bash
# ✅ List custom partials
ls -1 wp-content/themes/soma/partials/*.php

# ✅ Find usage of $pageBlock
grep -r "pageBlock" wp-content/themes/soma/partials/

# ✅ Document custom modifications
# Create a list of all customized files
```

### 5. Create Staging Environment

```
✅ Clone production to staging
✅ Test migration on staging first
✅ Verify all pages work on staging
✅ Get client approval before production
```

---

## Step-by-Step Migration

### Step 1: Backup Current Site (CRITICAL)

```bash
# 1.1 Navigate to WordPress root
cd /path/to/wordpress

# 1.2 Export database
wp db export backups/soma-v2-$(date +%Y%m%d-%H%M%S).sql

# 1.3 Backup theme
cd wp-content/themes
cp -r soma soma-v2.0.7-backup

# 1.4 Verify backups
ls -lh backups/
ls -lh soma-v2.0.7-backup/
```

**⚠️ DO NOT PROCEED** without verified backups!

---

### Step 2: Update PHP Version (if needed)

**Check current version:**
```bash
php -v
```

**If < 8.1:**

**On shared hosting:**
1. Login to hosting control panel (cPanel, Plesk, etc.)
2. Find "PHP Version" or "Select PHP Version"
3. Select PHP 8.1 or 8.2
4. Save and wait for changes to apply

**On VPS/dedicated server:**
```bash
# Ubuntu/Debian
sudo apt update
sudo apt install php8.1-cli php8.1-fpm php8.1-mysql php8.1-xml php8.1-mbstring

# Update PHP-FPM configuration if needed
sudo systemctl restart php8.1-fpm
```

**Verify:**
```bash
php -v
# Should show PHP 8.1.x or 8.2.x
```

---

### Step 3: Pull v3.0.0 Theme Files

**Option A: Git (Recommended)**
```bash
cd wp-content/themes/soma

# Ensure you're on correct branch
git status

# Stash any local changes
git stash

# Pull v3.0.0
git fetch origin
git checkout master
git pull origin master

# Verify version
grep "Version:" style.css
# Should show: Version: 3.0.0
```

**Option B: Manual Upload**
```bash
# Download v3.0.0 zip from repository
# Extract and replace wp-content/themes/soma/
# Ensure permissions are correct:
chmod -R 755 wp-content/themes/soma
```

---

### Step 4: Install PHP Dependencies

```bash
cd wp-content/themes/soma

# Install Composer dependencies
composer install --no-dev --optimize-autoloader

# Verify installation
ls -la vendor/
# Should contain: autoload.php, composer/, bin/

# Test autoloader
php -r "require 'vendor/autoload.php'; echo 'Autoload OK';"
```

---

### Step 5: Rebuild Assets

```bash
# Install Node dependencies
npm install

# Build production assets
npm run prod

# Verify build
ls -lh css/main.bundle.css
ls -lh js/main.bundle.js
# Files should exist and have recent timestamps
```

---

### Step 6: Update Custom Partials

**For each custom partial:**

```bash
# Find partials using $pageBlock
grep -l "pageBlock" partials/*.php

# Example: partials/CustomHero.php
```

**Update pattern:**

```php
// ❌ BEFORE (v2.0.7)
<?php
if (!defined('ABSPATH')) exit;
global $pageBlock;

$title = $pageBlock['hero_title'];
$subtitle = $pageBlock['hero_subtitle'];
$background = $pageBlock['hero_background'];
?>

// ✅ AFTER (v3.0.0)
<?php
if (!defined('ABSPATH')) exit;

$block_content = get_query_var('soma_block_content');
$block_counter = get_query_var('soma_block_counter');

$title = $block_content['hero_title'] ?? '';
$subtitle = $block_content['hero_subtitle'] ?? '';
$background = $block_content['hero_background'] ?? '';
?>
```

**Add null coalescing operator (`??`) for safety!**

---

### Step 7: Update Custom Code Using Old Structure

**Update file includes:**

```php
// ❌ OLD
require_once get_template_directory() . '/inc/post-types.php';
include get_template_directory() . '/inc/endpoints.php';

// ✅ NEW (automatic via Composer)
// No manual includes needed - all auto-loaded!
```

**Update function calls:**

```php
// ❌ OLD
$portfolio = get_posts(['post_type' => 'portfolio']);

// ✅ NEW (use helper)
$portfolio = soma_get_portfolio_items();

// Or with caching
use Soma\Utils\Enums\CacheTag;
$portfolio = soma_cache_remember('portfolio_all', function() {
    return soma_get_portfolio_items();
}, 3600, [CacheTag::POST_TYPE]);
```

---

### Step 8: Clear All Caches

```bash
# WordPress object cache
wp cache flush

# If using Redis/Memcached
redis-cli FLUSHALL
# or
memcached-tool localhost:11211 flush_all

# Browser cache (tell users to hard refresh)
# Ctrl+Shift+R (Windows/Linux) or Cmd+Shift+R (Mac)
```

**In WordPress admin:**
1. Clear any caching plugin (WP Super Cache, W3 Total Cache, etc.)
2. Clear CDN cache if applicable
3. Regenerate CSS in page builders

---

### Step 9: Test ACF Flexible Content

**Test every page type:**

```bash
# List all pages using soma_blocks
wp post list --post_type=page --meta_key=soma_blocks --format=table
```

**For each page:**
1. ✅ Visit page in browser
2. ✅ Verify all sections render correctly
3. ✅ Check responsive design
4. ✅ Test interactive elements
5. ✅ Verify ACF fields display properly

**Common issues:**
- Missing content → Check query var migration
- Layout broken → Check CSS classes
- JS errors → Check browser console

---

### Step 10: Test REST API Endpoints

```bash
# Test all custom endpoints
curl https://yoursite.com/wp-json/soma/news | jq
curl https://yoursite.com/wp-json/soma/careers | jq
curl https://yoursite.com/wp-json/soma/portfolio | jq
curl https://yoursite.com/wp-json/soma/documents | jq
curl https://yoursite.com/wp-json/soma/events | jq

# All should return 200 status and valid JSON
```

---

### Step 11: Run Automated Tests (Optional)

```bash
cd wp-content/themes/soma

# Run PHPUnit tests
vendor/bin/phpunit --no-coverage

# Should show: OK (108 tests, 355 assertions)

# Run code quality checks
vendor/bin/phpcs
vendor/bin/phpstan analyse

# Should pass with no critical errors
```

---

### Step 12: Visual Regression Testing

**Test these pages manually:**

```
✅ Homepage
✅ About page
✅ Portfolio index
✅ Portfolio single
✅ News index
✅ News single
✅ Careers page
✅ Team members page
✅ Contact page
✅ All custom templates
```

**For each page:**
- ✅ Desktop view (1920px)
- ✅ Tablet view (768px)
- ✅ Mobile view (375px)
- ✅ All interactive elements work
- ✅ Forms submit correctly
- ✅ Navigation functional

---

### Step 13: Performance Testing

```bash
# Before migration (on staging)
curl -w "@curl-format.txt" -o /dev/null -s https://staging.yoursite.com/

# After migration
curl -w "@curl-format.txt" -o /dev/null -s https://staging.yoursite.com/

# Compare results (should be similar or better)
```

**Create curl-format.txt:**
```
time_namelookup:  %{time_namelookup}\n
time_connect:  %{time_connect}\n
time_appconnect:  %{time_appconnect}\n
time_pretransfer:  %{time_pretransfer}\n
time_redirect:  %{time_redirect}\n
time_starttransfer:  %{time_starttransfer}\n
----------\n
time_total:  %{time_total}\n
```

---

### Step 14: Production Deployment

**If all tests pass on staging:**

```bash
# 1. Enable maintenance mode
wp maintenance-mode activate

# 2. Final database backup
wp db export production-final-backup-$(date +%Y%m%d-%H%M%S).sql

# 3. Deploy v3.0.0 (same steps as staging)
# ... (repeat Steps 3-6) ...

# 4. Clear all caches
wp cache flush

# 5. Disable maintenance mode
wp maintenance-mode deactivate

# 6. Monitor for 30 minutes
tail -f wp-content/uploads/soma-logs/soma.log
```

---

## Code Updates Required

### Pattern 1: Global Variables → Query Vars

**Affected Files:** All custom partials in `partials/`

```php
// ❌ OLD
global $pageBlock, $pageBuilder;
$data = $pageBlock;

// ✅ NEW
$block_content = get_query_var('soma_block_content');
$block_layout = get_query_var('soma_block_layout');
$block_counter = get_query_var('soma_block_counter');
$data = $block_content;
```

---

### Pattern 2: Direct File Includes → Composer Autoload

**Affected Files:** `functions.php`, custom plugins

```php
// ❌ OLD
require_once get_template_directory() . '/inc/post-types.php';
require_once get_template_directory() . '/inc/endpoints.php';

// ✅ NEW
// All classes auto-loaded via Composer
// Just use the classes:
use Soma\PostTypes\Loader;
Loader::instance();
```

---

### Pattern 3: Magic Strings → Enums

**Affected Files:** Custom queries, filters, actions

```php
// ❌ OLD
$query = new WP_Query([
    'post_type' => 'portfolio',
]);
$taxonomy = 'portfolio-taxonomy';

// ✅ NEW
use Soma\Core\Enums\PostType;
use Soma\Core\Enums\Taxonomy;

$query = new WP_Query([
    'post_type' => PostType::PORTFOLIO->value(),
]);
$taxonomy = Taxonomy::PORTFOLIO->value();

// Or use helpers (even better)
$query = soma_get_portfolio_items();
```

---

### Pattern 4: Manual Caching → Cache Helpers

**Affected Files:** Performance-critical queries

```php
// ❌ OLD
$cache_key = 'my_custom_data';
$data = get_transient($cache_key);
if (false === $data) {
    $data = expensive_query();
    set_transient($cache_key, $data, HOUR_IN_SECONDS);
}

// ✅ NEW
use Soma\Utils\Enums\CacheTag;

$data = soma_cache_remember('my_custom_data', function() {
    return expensive_query();
}, 3600, [CacheTag::POST_TYPE]);
```

---

### Pattern 5: Error Handling → PSR-3 Logging

**Affected Files:** Error-prone operations

```php
// ❌ OLD
if (!$result) {
    error_log('Operation failed');
}

// ✅ NEW
if (!$result) {
    soma_log_error('Operation failed', [
        'context' => 'additional_info',
        'user_id' => get_current_user_id(),
    ]);
}
```

---

### Pattern 6: Array Hooks → First-Class Callables

**Affected Files:** Custom classes with WordPress hooks

```php
// ❌ OLD
add_action('init', array($this, 'my_method'));
add_filter('the_content', array($this, 'filter_content'));

// ✅ NEW (PHP 8.1+)
add_action('init', $this->my_method(...));
add_filter('the_content', $this->filter_content(...));
```

---

## Testing & Validation

### Automated Tests

```bash
cd wp-content/themes/soma

# 1. Run all PHPUnit tests
vendor/bin/phpunit --testdox

# Expected output:
# ✓ Tests 108/108 passing
# ✓ Assertions: 355
# ✓ Time: < 5 seconds

# 2. Run PHPCS (coding standards)
vendor/bin/phpcs

# Expected output:
# ✓ 0 errors
# ⚠ Warnings are acceptable

# 3. Run PHPStan (static analysis)
vendor/bin/phpstan analyse

# Expected output:
# ✓ 0 errors at Level 6
```

---

### Manual Testing Checklist

#### Content Pages
- [ ] Homepage loads correctly
- [ ] All sections render (no missing content)
- [ ] Images display properly
- [ ] Links work correctly
- [ ] Forms submit successfully

#### Custom Post Types
- [ ] Portfolio archive page works
- [ ] Portfolio single pages display
- [ ] News archive page works
- [ ] News single pages display
- [ ] Careers page lists jobs
- [ ] Team members page shows all

#### Taxonomies
- [ ] Portfolio taxonomy filter works
- [ ] News categories filter
- [ ] Team department filter

#### REST API
- [ ] `/wp-json/soma/news` returns data
- [ ] `/wp-json/soma/portfolio` returns data
- [ ] `/wp-json/soma/careers` returns data
- [ ] `/wp-json/soma/documents` returns data
- [ ] `/wp-json/soma/events` returns data

#### ACF Flexible Content
- [ ] All partials render correctly
- [ ] No PHP errors in logs
- [ ] Block data displays properly
- [ ] Custom fields accessible

#### Multilingual (if applicable)
- [ ] Language switcher works
- [ ] Translations load correctly
- [ ] Date translations work

#### Performance
- [ ] Page load time < 3 seconds
- [ ] No console errors
- [ ] Images optimized
- [ ] CSS/JS minified

#### Responsive Design
- [ ] Mobile (375px) looks good
- [ ] Tablet (768px) looks good
- [ ] Desktop (1920px) looks good
- [ ] Navigation works on all sizes

---

### Error Monitoring

**Check logs after migration:**

```bash
# SOMA theme logs
tail -f wp-content/uploads/soma-logs/soma.log

# WordPress debug log
tail -f wp-content/debug.log

# PHP error log (location varies)
tail -f /var/log/php/error.log
```

**What to look for:**
- ❌ `[SOMA ERROR]` - Critical issues
- ⚠️ `[SOMA WARNING]` - Review but may be acceptable
- ℹ️ `[SOMA INFO]` - Informational only

---

## Rollback Plan

**If migration fails, follow this plan:**

### Quick Rollback (< 5 minutes)

```bash
# 1. Activate maintenance mode
wp maintenance-mode activate

# 2. Restore v2.0.7 theme backup
cd wp-content/themes
rm -rf soma
mv soma-v2.0.7-backup soma

# 3. Restore database (if modified)
wp db import backups/soma-v2-YYYYMMDD-HHMMSS.sql

# 4. Clear caches
wp cache flush

# 5. Deactivate maintenance mode
wp maintenance-mode deactivate

# 6. Verify site works
curl https://yoursite.com/
```

### Post-Rollback Actions

1. **Document what failed**
   ```bash
   # Save error logs
   cp wp-content/uploads/soma-logs/soma.log soma-migration-errors-$(date +%Y%m%d).log
   ```

2. **Review errors**
   - Identify root cause
   - Check if environment requirements met
   - Verify custom code compatibility

3. **Plan retry**
   - Fix identified issues
   - Test more thoroughly on staging
   - Schedule new migration window

---

## Troubleshooting

### Issue 1: White Screen of Death (WSOD)

**Symptoms:**
- Blank white page
- No content displays
- May show HTTP 500 error

**Causes:**
1. PHP version < 8.1
2. Missing Composer dependencies
3. Syntax errors in custom code

**Solutions:**

```bash
# 1. Check PHP version
php -v
# Must be 8.1+

# 2. Enable WordPress debug
# Edit wp-config.php:
define('WP_DEBUG', true);
define('WP_DEBUG_LOG', true);
define('WP_DEBUG_DISPLAY', false);

# 3. Check debug log
tail -f wp-content/debug.log

# 4. Reinstall dependencies
cd wp-content/themes/soma
composer install --no-dev
```

---

### Issue 2: Missing Content in Partials

**Symptoms:**
- Sections appear empty
- ACF fields not displaying
- PHP warnings about undefined variables

**Cause:**
Still using `$pageBlock` global instead of query vars

**Solution:**

```php
// Check partial file for this pattern:
global $pageBlock;  // ❌ OLD

// Replace with:
$block_content = get_query_var('soma_block_content');  // ✅ NEW
```

**Verify all partials:**
```bash
# Find problematic partials
grep -n "global.*pageBlock" partials/*.php

# Update each file
```

---

### Issue 3: 404 on REST Endpoints

**Symptoms:**
- `/wp-json/soma/*` returns 404
- API calls fail
- JavaScript console errors

**Causes:**
1. Permalinks not flushed
2. Endpoints not registered
3. .htaccess issues

**Solutions:**

```bash
# 1. Flush rewrite rules
wp rewrite flush

# 2. Verify endpoints exist
wp rest route list | grep soma

# Expected output:
# /wp-json/soma/news
# /wp-json/soma/careers
# /wp-json/soma/portfolio
# etc.

# 3. Test directly
curl https://yoursite.com/wp-json/soma/news

# 4. If still failing, check .htaccess
cat .htaccess
# Should contain WordPress rewrite rules
```

---

### Issue 4: CSS/JS Not Loading

**Symptoms:**
- Unstyled content
- JavaScript errors
- Missing functionality

**Causes:**
1. Assets not compiled
2. Cache not cleared
3. Version mismatch

**Solutions:**

```bash
# 1. Rebuild assets
cd wp-content/themes/soma
npm install
npm run prod

# 2. Verify files exist
ls -lh css/main.bundle.css
ls -lh js/main.bundle.js

# 3. Clear all caches
wp cache flush
# Also clear CDN, browser cache

# 4. Hard refresh browser
# Ctrl+Shift+R (Windows/Linux)
# Cmd+Shift+R (Mac)
```

---

### Issue 5: Elementor Widgets Missing

**Symptoms:**
- Custom 'soma' category not in Elementor
- Widgets don't appear in widget panel
- Cannot add soma widgets

**Causes:**
1. Elementor not detecting widgets
2. PHP errors preventing registration
3. Cache issues

**Solutions:**

```bash
# 1. Regenerate Elementor files
wp elementor flush-css

# 2. Check debug log for errors
tail -f wp-content/debug.log

# 3. Verify Elementor active
wp plugin list | grep elementor

# 4. Check widgets registered
grep -r "register_widget" wp-content/themes/soma/includes/Elementor/

# 5. Clear Elementor cache in admin
# Go to: Elementor > Tools > Regenerate CSS
```

---

### Issue 6: Performance Degradation

**Symptoms:**
- Slower page loads than v2.0.7
- High database query count
- Server timeouts

**Causes:**
1. Cache not working
2. Inefficient queries
3. Debug mode enabled in production

**Solutions:**

```bash
# 1. Disable debug mode in production
# Edit wp-config.php:
define('WP_DEBUG', false);

# 2. Verify cache working
wp shell
soma_cache_set('test', 'data', 60);
soma_cache_get('test');
# Should return: 'data'

# 3. Install Query Monitor plugin
wp plugin install query-monitor --activate

# 4. Check for slow queries
# Visit site with Query Monitor active
# Review "Queries" panel

# 5. Enable object cache
# Install Redis/Memcached
# Configure in wp-config.php
```

---

### Issue 7: ACF Field Groups Not Syncing

**Symptoms:**
- Field changes not appearing
- "Sync available" notices in ACF
- Field data missing

**Causes:**
1. ACF JSON not writable
2. Sync not triggered
3. Field group conflicts

**Solutions:**

```bash
# 1. Check permissions
chmod 755 wp-content/themes/soma/acf-json
ls -la wp-content/themes/soma/acf-json/

# 2. Sync in admin
# Go to: Custom Fields > Tools > Sync Available
# Click "Sync" for all groups

# 3. Verify JSON files
ls -1 wp-content/themes/soma/acf-json/
# Should list .json files

# 4. Force re-sync
cd wp-content/themes/soma/acf-json
# Delete .json files
# Re-import in ACF admin
```

---

### Issue 8: Multilingual Breaks

**Symptoms:**
- Language switcher not working
- Translations missing
- Date translations fail

**Causes:**
1. WP Multilang not active
2. Translation functions not loaded
3. Locale issues

**Solutions:**

```bash
# 1. Verify WP Multilang active
wp plugin list | grep wp-multilang

# 2. Activate if needed
wp plugin activate wp-multilang

# 3. Test language switcher
# Check for wpm_language_switcher() in header

# 4. Test date translation
wp shell
echo soma_translate_date('December 12, 2025');
# Should return: "Diciembre 12, 2025"
```

---

### Issue 9: Tests Failing

**Symptoms:**
- PHPUnit errors
- Failed assertions
- Cannot run tests

**Causes:**
1. Test environment not configured
2. WordPress test library missing
3. Database not accessible

**Solutions:**

```bash
# 1. Install test dependencies
composer install --dev

# 2. Check test config
cat phpunit.xml
# Verify database credentials

# 3. Run specific test
vendor/bin/phpunit --filter=PostTypeTest

# 4. Check bootstrap
cat tests/bootstrap.php
# Verify WordPress loaded

# 5. Reinstall test suite
bash bin/install-wp-tests.sh wordpress_test root '' localhost latest
```

---

### Issue 10: Git Conflicts

**Symptoms:**
- Cannot pull v3.0.0
- Merge conflicts
- Modified files blocking update

**Causes:**
1. Local modifications
2. Uncommitted changes
3. Different branch

**Solutions:**

```bash
# 1. Check status
git status

# 2. Stash local changes
git stash save "Migration backup $(date +%Y%m%d)"

# 3. Pull v3.0.0
git fetch origin
git checkout master
git pull origin master

# 4. Review stashed changes
git stash list
git stash show -p stash@{0}

# 5. Apply if needed (may have conflicts)
git stash pop
# Resolve conflicts manually
```

---

## FAQ

### Q1: Do I need to migrate all at once?

**A:** Yes, it's a major version upgrade. You cannot partially migrate. However, you can:
- Migrate in staging first (recommended)
- Choose a low-traffic time window
- Have rollback plan ready

---

### Q2: Will my ACF field groups break?

**A:** No, ACF field groups are 100% compatible. The field data structure hasn't changed. Only the way partials access data (global → query vars) changed.

---

### Q3: Can I keep using v2.0.7?

**A:** Yes, but not recommended long-term:
- No security updates
- No new features
- No support for modern PHP
- Missing performance improvements

---

### Q4: How long does migration take?

**A:** Depends on customizations:
- Standard site: 1-2 hours
- Moderate customizations: 2-3 hours
- Heavy customizations: 3-4 hours
- Testing time: +1-2 hours

---

### Q5: Do I need a developer?

**A:** Depends on your setup:
- ✅ No developer needed if:
  - Using standard ACF flexible content
  - No custom code modifications
  - Comfortable with terminal/command line

- ⚠️ Developer recommended if:
  - Custom partials exist
  - Modified theme core files
  - Complex integrations
  - Not comfortable with technical tasks

---

### Q6: What if I find a bug after migration?

**A:** Two options:
1. **Rollback** to v2.0.7 (use rollback plan above)
2. **Report and fix:**
   ```bash
   # Check logs
   tail -f wp-content/uploads/soma-logs/soma.log
   
   # Report issue with:
   # - Error message
   # - Steps to reproduce
   # - Expected vs actual behavior
   ```

---

### Q7: Can I use both ACF and Elementor?

**A:** Yes! They work independently:
- **ACF Flexible Content**: For complex, developer-controlled layouts
- **Elementor**: For client-friendly page building
- No conflicts between the two systems

---

### Q8: Will performance improve or degrade?

**A:** Generally improves due to:
- ✅ Better caching system
- ✅ Code optimization
- ✅ Reduced database queries (when using helpers)

May degrade if:
- ❌ Debug mode left enabled
- ❌ Caching not working
- ❌ Inefficient custom code

---

### Q9: What about my custom plugins?

**A:** Custom plugins should work if they:
- ✅ Don't modify theme core files
- ✅ Use WordPress hooks properly
- ✅ Don't rely on theme internals

May need updates if they:
- ❌ Include files from `inc/` directory
- ❌ Access `$pageBlock` global
- ❌ Call removed functions

---

### Q10: Can I migrate incrementally?

**A:** No, it's all-or-nothing because:
- Directory structure changed completely
- Class namespaces incompatible
- Can't mix v2 and v3 code

However, you can:
- Test on staging first
- Migrate dev → staging → production
- Keep v2 backup for emergency rollback

---

## Need Help?

### Support Resources

**Documentation:**
- 📖 [Development Guide](DEVELOPMENT.md)
- 🎨 [Widgets Reference](WIDGETS.md)
- 🛠️ [Helper Functions API](HELPERS.md)
- 🧪 [Testing Guide](TESTING_GUIDE.md)

**Logs:**
```bash
# SOMA logs
tail -f wp-content/uploads/soma-logs/soma.log

# WordPress debug
tail -f wp-content/debug.log
```

**Community:**
- GitHub Issues: Report bugs and feature requests
- Development Team: Contact for enterprise support

---

## Summary

### Migration Success Criteria

✅ **Checklist:**
- [ ] PHP 8.1+ verified
- [ ] All backups created
- [ ] Staging environment tested
- [ ] Custom partials updated (query vars)
- [ ] Dependencies installed (Composer + npm)
- [ ] Assets compiled (CSS/JS)
- [ ] All pages render correctly
- [ ] REST API working
- [ ] Performance acceptable
- [ ] No errors in logs
- [ ] Client approval obtained

### Key Takeaways

1. **Test on staging first** - Never migrate directly to production
2. **Backup everything** - Database + files before starting
3. **Update custom code** - Global vars → query vars is critical
4. **Clear all caches** - WordPress, CDN, browser, object cache
5. **Monitor logs** - Watch for errors after migration
6. **Have rollback plan** - Be prepared to revert if needed

### Estimated Timeline

- **Preparation**: 30 minutes (backups, staging setup)
- **Migration**: 1-2 hours (files, dependencies, code updates)
- **Testing**: 1-2 hours (manual + automated testing)
- **Deployment**: 30 minutes (production rollout)
- **Monitoring**: 1 hour (post-deployment watch)

**Total**: 3-6 hours (depending on complexity)

---

**Document Version**: 1.0  
**Last Updated**: December 12, 2025  
**Theme Version**: v2.0.7 → v3.0.0  
**Maintainer**: Miguel Colmenares

Good luck with your migration! 🚀
