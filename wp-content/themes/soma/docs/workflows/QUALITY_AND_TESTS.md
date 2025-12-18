# ⚠️ DEPRECATED: SOMA CI - Quality & Tests Workflow

> **IMPORTANT**: This workflow has been deprecated as of December 18, 2025.  
> **Replacement**: See [CI_CD.md](CI_CD.md) for the unified ci-cd.yml workflow.  
> **Reason**: Merged with release-and-deploy.yml to eliminate race conditions.

---

**Previous File**: `.github/workflows/quality-and-tests.yml` (DELETED)  
**Current File**: `.github/workflows/ci-cd.yml` (Stage 1: Quality Gates)  
**Migration Date**: December 18, 2025  
**Status**: ❌ Workflow file deleted, functionality migrated to ci-cd.yml

---

## Migration Notice

This documentation is preserved for historical reference. All functionality from this workflow has been migrated to the **unified ci-cd.yml workflow**.

### What Changed

**OLD Architecture (v3.1.0 and earlier):**
- Separate `quality-and-tests.yml` workflow
- Ran independently from `release-and-deploy.yml`
- **Problem**: Race condition when both workflows triggered simultaneously

**NEW Architecture (v3.1.1+):**
- Unified `ci-cd.yml` workflow
- Stage 1 contains all quality gate jobs (code-quality, php-tests, frontend-build)
- Stage 2 (build-and-release) depends on Stage 1 via `needs:` keyword
- **Solution**: Guaranteed sequential execution, no race conditions

### Where to Find This Functionality Now

All jobs from this workflow are now in `.github/workflows/ci-cd.yml` **Stage 1**:

| Old Job (quality-and-tests.yml) | New Location (ci-cd.yml) | Status |
|----------------------------------|--------------------------|--------|
| `code-quality` | Stage 1: `code-quality` job | ✅ Migrated |
| `php-tests` | Stage 1: `php-tests` job | ✅ Migrated |
| `frontend-build` | Stage 1: `frontend-build` job | ✅ Migrated |
| `ci-summary` | Final: `ci-cd-summary` job | ✅ Migrated (merged with CD summary) |

### Documentation Update

**For current workflow documentation, see:**
- **[CI_CD.md](CI_CD.md)** - Complete unified workflow documentation
- **[WORKFLOWS.md](../WORKFLOWS.md)** - Main workflow index

---

## Historical Documentation (Preserved Below)

The following documentation describes the workflow as it existed before migration to ci-cd.yml. It is preserved for historical reference and understanding the previous architecture.

---

# SOMA CI - Quality & Tests Workflow (Historical)

**File**: `.github/workflows/quality-and-tests.yml`  
**Purpose**: Continuous Integration - Code quality analysis and automated testing  
**Triggers**: Push to any branch, Pull Requests, Manual dispatch  
**Duration**: ~3-5 minutes  
**Cost**: Free (GitHub Actions minutes on public repos)

---

## Overview

This workflow runs automatically on every push and pull request to ensure code quality and prevent regressions. It performs three parallel jobs: code quality analysis (PHPCS, PHPStan), PHP unit tests (PHPUnit), and frontend build validation (Webpack).

### Key Features

- ✅ **Parallel Execution** - All 3 jobs run simultaneously for faster feedback
- ✅ **WordPress Coding Standards** - PHPCS with strict WordPress rules
- ✅ **Static Analysis** - PHPStan Level 6+ type checking
- ✅ **108 Unit Tests** - PHPUnit with WordPress test environment
- ✅ **Frontend Validation** - Webpack production build verification
- ✅ **Auto-fix Support** - PHPCBF automatically fixes minor violations
- ✅ **Coverage Reports** - Code coverage uploaded to Codecov

---

## Workflow Triggers

### Push to Branches

```yaml
on:
  push:
    branches:
      - main
      - develop
      - 'week-*'
```

**When**: Any push to main, develop, or week-* branches  
**Purpose**: Validate code changes before release  
**Blocks**: Does NOT block deployment (only runs quality gates)

### Pull Requests

```yaml
on:
  pull_request:
    branches:
      - main
      - develop
```

**When**: Opening or updating PRs to main or develop  
**Purpose**: Validate changes before merge  
**Required**: Can be configured as required status check

### Manual Dispatch

```yaml
on:
  workflow_dispatch:
```

**When**: Manually triggered from GitHub Actions UI  
**Purpose**: On-demand quality checks or debugging  
**Access**: Requires repository write access

---

## Jobs Architecture

### Job 1: Code Quality Analysis (Parallel)

**Name**: 🔍 Code Quality Analysis (STRICT)  
**Duration**: ~1-2 minutes  
**Runs on**: ubuntu-latest

#### Steps

1. **📥 Checkout code** - Clone repository
2. **🐘 Setup PHP 8.1** - Install PHP with extensions
3. **📦 Cache Composer** - Speed up dependency installation
4. **🎼 Install Composer** - Install PHP dependencies
5. **🔧 PHPCBF Auto-fix** - Auto-fix minor coding standard issues
6. **🔍 PHPCS Analysis** - Check WordPress coding standards (REQUIRED)
7. **🔍 PHPStan Analysis** - Static analysis Level 6+ (REQUIRED)

#### Quality Gates

| Check | Tool | Level | Action on Failure |
|-------|------|-------|-------------------|
| **Coding Standards** | PHPCS | WordPress | ❌ Fail workflow, block deployment |
| **Static Analysis** | PHPStan | Level 6+ | ❌ Fail workflow, block deployment |
| **Auto-fix** | PHPCBF | WordPress | ⚠️ Log warnings, continue |

#### Exit Codes

- **0**: All checks passed ✅
- **1**: Violations found, deployment blocked ❌
- **2**: Fixable errors found, run PHPCBF ⚠️

---

### Job 2: PHP Unit & Integration Tests (Parallel)

**Name**: 🧪 PHP Unit & Integration Tests  
**Duration**: ~2-3 minutes  
**Runs on**: ubuntu-latest  
**Services**: MySQL 8.0

#### Steps

1. **📥 Checkout code** - Clone repository
2. **🐘 Setup PHP 8.1** - Install PHP with Xdebug coverage
3. **🗄️ Start MySQL** - Launch MySQL 8.0 service for tests
4. **📦 Install Composer** - Install PHP dependencies
5. **📦 Install SVN** - Required for WordPress test suite
6. **🏗️ Setup WordPress** - Install WordPress test environment
7. **🔗 Create Symlink** - Link theme into test WordPress installation
8. **🧪 Run PHPUnit** - Execute 108 tests with 355 assertions
9. **📊 Upload Coverage** - Send coverage to Codecov

#### Test Environment

```bash
Database: wordpress_test
Host: 127.0.0.1:3306
User: root
Password: password
WordPress: Latest stable version
```

#### Expected Results

```
Tests: 108
Assertions: 355
Coverage: ~60-70% (target)
```

---

### Job 3: Frontend Build Validation (Parallel)

**Name**: 🎨 Frontend Build Validation  
**Duration**: ~1-2 minutes  
**Runs on**: ubuntu-latest

#### Steps

1. **📥 Checkout code** - Clone repository
2. **🟢 Setup Node.js 18** - Install Node.js runtime
3. **📦 Cache npm** - Speed up dependency installation
4. **📦 Install npm** - Install JavaScript dependencies
5. **🏗️ Build Assets** - Run `npm run prod` (Webpack)
6. **✅ Verify Outputs** - Check main.bundle.css and main.bundle.js exist

#### Build Validation

```bash
# Expected outputs
wp-content/themes/soma/css/main.bundle.css  (~180 KiB)
wp-content/themes/soma/js/main.bundle.js    (~150 KiB)
```

#### Webpack Configuration

- **Mode**: Production
- **Minification**: Enabled
- **Source Maps**: Disabled
- **Optimization**: Tree shaking, code splitting

---

### Job 4: CI Summary (Always Runs)

**Name**: 📊 CI Summary  
**Duration**: <10 seconds  
**Runs on**: ubuntu-latest  
**Depends on**: All 3 jobs  
**Condition**: Always runs (even if jobs fail)

#### Purpose

Generates a human-readable summary of all CI job results and posts it to the GitHub Actions summary page.

#### Summary Format

```markdown
## 🎯 CI Pipeline Summary

**Branch**: main
**Commit**: abc1234
**Triggered by**: push

### Job Results
- Code Quality: success
- PHP Tests: success
- Frontend Build: success

✅ **All checks passed!** Ready to merge or release.
```

---

## Usage Examples

### Running Workflow Manually

1. Go to **Actions** tab in GitHub
2. Select **SOMA CI - Quality & Tests**
3. Click **Run workflow** button
4. Select branch
5. Click **Run workflow**

### Checking Workflow Status

```bash
# Via GitHub CLI
gh run list --workflow=quality-and-tests.yml | cat

# View specific run
gh run view <run_id> | cat

# View logs
gh run view <run_id> --log | cat
```

### Local Testing (Before Push)

```bash
# Navigate to theme directory
cd wp-content/themes/soma

# Run code quality checks
composer phpcs           # Check coding standards
composer phpcbf          # Auto-fix violations
composer phpstan         # Static analysis

# Run tests
composer test            # PHPUnit tests

# Build frontend
npm run prod             # Production build
```

---

## Environment Variables

```yaml
NODE_VERSION: '18'      # Node.js version for frontend build
PHP_VERSION: '8.1'      # PHP version for backend
THEME_PATH: 'wp-content/themes/soma'  # Theme location
```

---

## Caching Strategy

### Composer Dependencies

```yaml
Cache Key: composer-${{ hashFiles('composer.lock') }}
Path: wp-content/themes/soma/vendor
Hit Rate: ~95%
Savings: ~30 seconds per run
```

### Node Modules

```yaml
Cache Key: npm-${{ hashFiles('package-lock.json') }}
Path: wp-content/themes/soma/node_modules
Hit Rate: ~95%
Savings: ~45 seconds per run
```

### Total Savings

- **Without cache**: ~5-6 minutes
- **With cache**: ~3-4 minutes
- **Improvement**: ~40% faster

---

## Troubleshooting

### PHPCS Failures

**Symptom**: PHPCS exits with code 1 or 2  
**Cause**: Coding standard violations

**Solution**:
```bash
# Auto-fix issues
composer phpcbf

# Check remaining errors
composer phpcs

# Fix manually if needed
```

### PHPStan Errors

**Symptom**: PHPStan finds type errors  
**Cause**: Missing type hints, incorrect types

**Solution**:
```bash
# Run locally with details
composer phpstan

# Fix type errors in code
# Add proper type hints and docblocks
```

### PHPUnit Failures

**Symptom**: Tests fail or don't run  
**Cause**: WordPress test environment issues

**Solution**:
```bash
# Reinstall test environment
cd tests/bin
bash install-wp-tests.sh wordpress_test root '' localhost latest

# Run tests locally
cd ../..
composer test
```

### Frontend Build Errors

**Symptom**: Webpack build fails  
**Cause**: Node version, dependency issues

**Solution**:
```bash
# Clear cache and reinstall
rm -rf node_modules
npm cache clean --force
npm install

# Rebuild
npm run prod
```

---

## Success Criteria

For the workflow to pass, **ALL** of the following must succeed:

- ✅ PHPCS: 0 errors, 0 warnings (STRICT)
- ✅ PHPStan: Level 6+, 0 critical errors
- ✅ PHPUnit: 108/108 tests passing
- ✅ Frontend build: Production assets created successfully

**If any job fails**: The entire workflow is marked as failed and deployment is blocked.

---

## Integration with CD Workflow

This CI workflow is a **prerequisite** for the CD workflow ([release-and-deploy.yml](RELEASE_AND_DEPLOY.md)):

```yaml
# In release-and-deploy.yml
jobs:
  wait-for-ci:
    name: ⏳ Wait for CI to Pass
    steps:
      - name: Check CI workflow status
        # Verifies quality-and-tests.yml passed for this commit
```

**Deployment Flow**:
1. Push code → **CI workflow runs** (this workflow)
2. Create tag v3.0.1 → **CD workflow checks CI passed**
3. If CI passed → **CD proceeds with release**
4. If CI failed → **CD blocked, deployment prevented**

---

## Performance Metrics

### Target Times (with cache)

| Job | Target | Typical | Max Acceptable |
|-----|--------|---------|----------------|
| Code Quality | 1-2 min | 1.5 min | 3 min |
| PHP Tests | 2-3 min | 2.5 min | 5 min |
| Frontend Build | 1-2 min | 1.5 min | 3 min |
| **Total** | **3-4 min** | **3.5 min** | **6 min** |

### Resource Usage

- **CPU**: 2 cores per job (GitHub Actions default)
- **RAM**: 7 GB per job (GitHub Actions default)
- **Disk**: ~2 GB (dependencies + cache)
- **Network**: ~500 MB (downloads)

---

## Best Practices

### For Developers

1. **Run checks locally** before pushing
   ```bash
   composer validate  # All checks at once
   ```

2. **Fix auto-fixable issues** with PHPCBF
   ```bash
   composer phpcbf
   ```

3. **Review CI logs** if workflow fails
   ```bash
   gh run view <run_id> --log | cat
   ```

4. **Keep dependencies updated**
   ```bash
   composer update
   npm update
   ```

### For Maintainers

1. **Monitor workflow duration** - Keep under 5 minutes
2. **Review cache hit rates** - Should be >90%
3. **Update dependencies monthly** - Security and performance
4. **Adjust quality gates** - Balance strictness vs. productivity

---

## Related Documentation

- **Release & Deploy Workflow**: [RELEASE_AND_DEPLOY.md](RELEASE_AND_DEPLOY.md)
- **SFTP Secrets Testing**: [TEST_SFTP_SECRETS.md](TEST_SFTP_SECRETS.md)
- **Main Workflows Guide**: [../WORKFLOWS.md](../WORKFLOWS.md)
- **Development Guide**: [../DEVELOPMENT.md](../DEVELOPMENT.md)
- **Testing Guide**: [../TESTING_GUIDE.md](../TESTING_GUIDE.md)

---

**Document Version**: 1.0  
**Last Updated**: December 14, 2025  
**Workflow Version**: 1.0  
**Maintainer**: Miguel Colmenares
