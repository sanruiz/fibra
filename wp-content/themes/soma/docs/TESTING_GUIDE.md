# Soma Theme - Testing Guide

## Overview

This guide covers all testing approaches for the Soma WordPress theme v3.0.0, including unit tests, integration tests, and manual testing procedures.

---

## Testing Infrastructure

### 1. **WordPress Admin UI Tests** (Recommended for Development)

**Location**: Built-in WordPress admin page  
**Access**: http://fibrasoma.local/wp-admin/admin.php?page=soma-pagebuilder-test  
**Requirements**:
- `WP_DEBUG` must be `true` in `wp-config.php`
- WordPress admin access

**Test Categories:**
1. ✅ **PSR-4 Classes** - Validates Loader, BlockRegistry, BlockRenderer existence
2. ✅ **Block Registry** - 53 blocks registered, singleton pattern, specific blocks (BusinessUnits, Portfolio, etc.)
3. ✅ **Partial Files** - Validates all 53 partial files exist in filesystem
4. ✅ **Block Renderer** - Tests null, empty, invalid blocks, error handling
5. ✅ **Helper Functions** - soma_translate_date(), translateDate(), logging, caching
6. ✅ **Page Rendering** - Tests up to 10 published pages with ACF soma_blocks field

**Features:**
- Color-coded results (green=pass, yellow=warn, red=fail)
- Statistics dashboard (total tests, passed, warned, failed)
- Detailed error messages
- Real-time testing on live WordPress environment

**How to Use:**
1. Enable `WP_DEBUG` in `wp-config.php`
2. Navigate to **Tools → PageBuilder Tests** in WordPress admin
3. View automated test results
4. Review any failures or warnings

---

### 2. **PHPUnit Integration Tests** (CI/CD Ready)

**Location**: `tests/Integration/PageBuilderTest.php`  
**Requirements**: WordPress test suite environment

**Setup:**
```bash
# Install WordPress test suite
./tests/bin/install-wp-tests.sh wordpress_test root '' localhost latest

# Run tests
vendor/bin/phpunit tests/Integration/PageBuilderTest.php --testdox
```

**Test Coverage (19 tests):**
- Singleton patterns (cannot clone BlockRegistry/BlockRenderer)
- 53 blocks registered validation
- Specific blocks existence (BusinessUnits, Portfolio, NewsList, etc.)
- Block mapping structure (field_group + partial keys)
- Partial files existence (all 53 files)
- Renderer error handling (null, empty, invalid, unregistered blocks)
- Renderer statistics
- Cache invalidation (all caches + specific block types)
- Dynamic block registration
- Field group and partial path retrieval

**Note**: Currently requires full WordPress test environment setup. For local development, use Admin UI tests instead.

---

### 3. **WP-CLI Test Runner** (Command Line)

**Location**: `scripts/test-integration.sh`

**Usage:**
```bash
chmod +x scripts/test-integration.sh
./scripts/test-integration.sh
```

**Features:**
- Bash script using `wp eval` for testing
- Color-coded terminal output
- Tests same functionality as PHPUnit
- Requires WP-CLI configuration for Local environment

**Note**: May require WP-CLI path configuration for Local by Flywheel environment.

---

## Quality Gates

### 1. **PHPCS (Code Standards)**

**Run:**
```bash
vendor/bin/phpcs --standard=WordPress --extensions=php includes/ page-builder.php functions.php
```

**Auto-fix:**
```bash
vendor/bin/phpcbf --standard=WordPress --extensions=php includes/ page-builder.php functions.php
```

**Current Status:**
- Critical errors: 0 ✅
- Auto-fixable violations: Resolved
- Remaining: Non-critical naming conventions and comment punctuation

---

### 2. **PHPStan (Static Analysis Level 6)**

**Run:**
```bash
vendor/bin/phpstan analyse includes/ page-builder.php functions.php --level=6 --memory-limit=512M
```

**Current Status:**
- Critical errors: 0 ✅
- Type hint warnings: 3 (CacheTag arrays - non-critical)
- All classes pass Level 6 analysis

---

### 3. **PHP Syntax Validation**

**Run:**
```bash
php -l page-builder.php
php -l includes/Core/Theme.php
# etc...
```

**All files:** ✅ No syntax errors

---

## Manual Testing Checklist

### Phase 6: PageBuilder System

#### ✅ **Infrastructure Tests**
- [ ] `\Soma\PageBuilder\Loader` class exists and loads
- [ ] `\Soma\PageBuilder\BlockRegistry` singleton instantiation
- [ ] `\Soma\PageBuilder\BlockRenderer` singleton instantiation
- [ ] Loader registered in Theme.php with priority 25
- [ ] Cache invalidation hooks active (`save_post`, `acf/save_post`)

#### ✅ **Block Registry Tests**
- [ ] 53 blocks registered (count validation)
- [ ] Specific blocks exist: BusinessUnits, Portfolio, NewsList, TeamMembers, Footer, Navbar
- [ ] Each block has `field_group` and `partial` keys
- [ ] Block registration returns true for valid blocks
- [ ] Block registration returns false for duplicates

#### ✅ **Block Renderer Tests**
- [ ] Renders valid blocks without errors
- [ ] Handles `null` blocks gracefully
- [ ] Handles empty array `[]` gracefully
- [ ] Handles invalid block structure (missing `acf_fc_layout`)
- [ ] Handles unregistered block layouts (logs error)
- [ ] Validates partial file existence before inclusion
- [ ] Logs errors to WordPress debug.log when `WP_DEBUG` enabled

#### ✅ **Query Vars Tests**
- [ ] `soma_block_counter` set correctly in partials
- [ ] `soma_block_content` contains ACF field data
- [ ] `soma_block_layout` contains layout name
- [ ] No global `$pageBuilder` or `$pageBlock` variables (removed)

#### ✅ **Page Integration Tests**
- [ ] `page-builder.php` loads without errors
- [ ] ACF flexible content blocks render correctly
- [ ] `get_template_part('page-builder')` works in templates
- [ ] Error handling shows debug comments when PSR-4 not loaded

#### ✅ **Performance Tests**
- [ ] Block caching works (when enabled)
- [ ] Cache invalidation on post save
- [ ] No memory leaks with large block sets
- [ ] Renderer statistics available

---

## Test Data Requirements

### ACF Field Setup

**Required Field:**
- Field Name: `soma_blocks`
- Field Type: Flexible Content
- Layouts: 53+ layouts registered in BlockRegistry

**Sample Layouts:**
- BusinessUnits (field group: `business_units_content`)
- Portfolio (field group: `portfolio_content`)
- NewsList (field group: `news_list`)
- TeamMembers (field group: `team_members`)
- Navbar (field group: `navbar_content`)
- Footer (field group: `footer_content`)

**Test Pages:**
Create WordPress pages with various combinations of these blocks for integration testing.

---

## Continuous Integration

### GitHub Actions (Future)

**Recommended Workflow:**
```yaml
name: PHP Tests

on: [push, pull_request]

jobs:
  test:
    runs-on: ubuntu-latest
    steps:
      - uses: actions/checkout@v2
      
      - name: Setup PHP
        uses: shivammathur/setup-php@v2
        with:
          php-version: '8.1'
          
      - name: Install dependencies
        run: composer install
        
      - name: PHPCS
        run: vendor/bin/phpcs
        
      - name: PHPStan
        run: vendor/bin/phpstan analyse --level=6
        
      - name: PHPUnit
        run: vendor/bin/phpunit
```

---

## Testing Best Practices

### 1. **Test Frequently**
- Run tests after each component modification
- Validate quality gates before committing

### 2. **Use Admin UI for Quick Validation**
- Fastest feedback loop
- Real WordPress environment
- Visual results

### 3. **Automate with PHPUnit for CI/CD**
- Reproducible results
- Suitable for automated pipelines
- Regression testing

### 4. **Monitor WordPress debug.log**
- Check for errors during development
- Validate error logging functionality
- Ensure PSR-3 logging works correctly

### 5. **Test Edge Cases**
- Null values
- Empty arrays
- Invalid data structures
- Missing files
- Unregistered blocks

---

## Troubleshooting

### Admin Test Page Not Showing

**Solution:**
- Ensure `WP_DEBUG` is `true` in `wp-config.php`
- Clear WordPress cache
- Check if Theme is active
- Verify Admin/Loader.php is loaded

### PHPUnit WordPress Environment Issues

**Solution:**
- For Local by Flywheel: Use Admin UI tests instead
- For standard WordPress: Run `tests/bin/install-wp-tests.sh`
- Verify database credentials
- Ensure WordPress core is installed in test environment

### PHPCS Errors Not Auto-Fixing

**Solution:**
- Some errors require manual fixes
- Naming conventions cannot be auto-fixed
- Comment punctuation may need manual review

### PHPStan Type Errors

**Solution:**
- Review type hints and docblocks
- Add `@var` annotations where needed
- Use proper return types
- Check CacheTag enum usage

---

## Quick Reference

### Common Test Commands

```bash
# Run all quality checks
vendor/bin/phpcs includes/ page-builder.php
vendor/bin/phpstan analyse includes/ page-builder.php --level=6
vendor/bin/phpunit tests/Integration/

# Auto-fix code style
vendor/bin/phpcbf includes/ page-builder.php

# Run specific test
vendor/bin/phpunit tests/Integration/PageBuilderTest.php --testdox

# Test individual file syntax
php -l path/to/file.php

# WordPress Admin Tests
# Navigate to: /wp-admin/admin.php?page=soma-pagebuilder-test
```

---

**Document Version**: 1.0  
**Last Updated**: December 12, 2025  
**Phase**: 6 (Page Builder Enhancement)  
**Status**: Testing Infrastructure Complete
