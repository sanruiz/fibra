# SOMA CI/CD - Unified Workflow Documentation

**File**: `.github/workflows/ci-cd.yml`  
**Purpose**: Unified CI/CD pipeline - Quality gates, Build, Release, and Deploy  
**Triggers**: Push, Pull Requests, Tags (v*), Manual dispatch  
**Duration**: 3-8 minutes (varies by stage)  
**Version**: 1.0 (Unified from quality-and-tests.yml + release-and-deploy.yml)

---

## Overview

This workflow **unifies** the entire CI/CD pipeline into a single file to prevent race conditions that occurred when separate workflows triggered simultaneously on version tags. It provides a sequential execution flow with proper dependencies between stages.

### Why Unified?

**Problem with Separate Workflows:**
- Creating a version tag (e.g., `v3.1.0`) triggered BOTH `quality-and-tests.yml` AND `release-and-deploy.yml` simultaneously
- The `release-and-deploy.yml` workflow had a `wait-for-ci` job that checked if CI passed via GitHub API
- Race condition: `wait-for-ci` would find CI workflow "in_progress" because both started at the same time
- Result: 3 failed deployment attempts for v3.1.0 release

**Solution with Unified Workflow:**
- Single workflow file eliminates cross-workflow dependencies
- GitHub Actions `needs:` keyword creates proper sequential execution within same workflow
- Quality gates run first (parallel), then build/release (conditional), then deploy (conditional)
- No race conditions possible - stages execute in guaranteed order

---

## Architecture

### Three-Stage Pipeline

```
┌─────────────────────────────────────────────────────────┐
│  STAGE 1: QUALITY GATES (Parallel) - Always Runs        │
│  ┌────────────────┐  ┌────────────────┐  ┌────────────┐ │
│  │ Code Quality   │  │  PHP Tests     │  │  Frontend  │ │
│  │ (PHPCS+PHPStan)│  │  (PHPUnit)     │  │   Build    │ │
│  └────────────────┘  └────────────────┘  └────────────┘ │
└───────────────────────────┬─────────────────────────────┘
                            │ needs: [code-quality, php-tests, frontend-build]
                            ▼
┌─────────────────────────────────────────────────────────┐
│  STAGE 2: BUILD & RELEASE - Only on Tags (v*)           │
│  ┌─────────────────────────────────────────────────┐   │
│  │ • Extract version from tag                       │   │
│  │ • Install production dependencies                │   │
│  │ • Build production assets                        │   │
│  │ • Create release ZIP (exclude dev files)         │   │
│  │ • Extract CHANGELOG notes                        │   │
│  │ • Create GitHub Release                          │   │
│  │ • Upload artifact                                │   │
│  └─────────────────────────────────────────────────┘   │
└───────────────────────────┬─────────────────────────────┘
                            │ needs: build-and-release
                            ▼
┌─────────────────────────────────────────────────────────┐
│  STAGE 3: DEPLOY - Only after successful release        │
│  ┌─────────────────────────────────────────────────┐   │
│  │ • Download release artifact                      │   │
│  │ • Setup SSH key                                  │   │
│  │ • Create backup on server                        │   │
│  │ • Upload ZIP via SFTP                            │   │
│  │ • Upload extraction script                       │   │
│  │ • Deployment summary                             │   │
│  └─────────────────────────────────────────────────┘   │
└───────────────────────────┬─────────────────────────────┘
                            │ needs: [all stages]
                            ▼
┌─────────────────────────────────────────────────────────┐
│  FINAL: PIPELINE SUMMARY - Always Runs                  │
│  Reports status of all stages (success/failure/skipped) │
└─────────────────────────────────────────────────────────┘
```

---

## Workflow Triggers

### 1. Push to Branches

```yaml
on:
  push:
    branches:
      - main
      - develop
      - 'week-*'
```

**When**: Any push to main, develop, or week-* branches  
**What runs**: Only Stage 1 (Quality Gates)  
**Purpose**: Validate code changes before merge

**Example:**
```bash
git push origin week-2
# Triggers: code-quality + php-tests + frontend-build
# Does NOT trigger: build-and-release or deploy
```

---

### 2. Pull Requests

```yaml
on:
  pull_request:
    branches:
      - main
      - develop
      - 'week-*'
```

**When**: PR opened or updated  
**What runs**: Only Stage 1 (Quality Gates)  
**Purpose**: Block merge if quality gates fail

**Example:**
```bash
gh pr create --base week-2 --title "feat: Add hero section" | cat
# Triggers: code-quality + php-tests + frontend-build
# PR cannot merge if any job fails
```

---

### 3. Version Tags

```yaml
on:
  push:
    tags:
      - 'v*'
```

**When**: Version tag pushed (v3.0.0, v3.1.0, etc.)  
**What runs**: ALL stages (Quality → Build/Release → Deploy)  
**Purpose**: Complete release and deployment pipeline

**Example:**
```bash
git tag -a v3.1.1 -m "Release v3.1.1"
git push origin v3.1.1
# Triggers: ALL STAGES
# 1. Quality gates (parallel)
# 2. Build & Release (after quality)
# 3. Deploy (after release)
```

**Critical**: This trigger now executes stages sequentially within ONE workflow, eliminating race conditions.

---

### 4. Manual Dispatch

```yaml
on:
  workflow_dispatch:
    inputs:
      version:
        description: 'Version number (e.g., 3.1.1)'
        required: false
```

**When**: Manually triggered from GitHub Actions UI  
**What runs**: ALL stages (if version provided) or only Quality Gates  
**Purpose**: Manual releases or quality checks

**Example:**
```bash
gh workflow run ci-cd.yml -f version=3.1.1 | cat
# Triggers: ALL STAGES
# Uses version input instead of tag
```

---

## Jobs Reference

### Stage 1: Quality Gates (Parallel)

#### Job: `code-quality`

**Duration**: ~2 minutes  
**Runs on**: ubuntu-latest  
**Dependencies**: None (runs in parallel)

**Steps:**
1. **Checkout code** - Clone repository
2. **Setup PHP 8.1** - Configure PHP environment
3. **Cache Composer** - Speed up dependency installation
4. **Install Composer dependencies** - `composer install`
5. **PHPCBF Auto-fix** - Fix minor code style issues
6. **PHPCS Check** - WordPress Coding Standards validation (STRICT mode)
7. **PHPStan Analysis** - Static analysis Level 6+

**Quality Gates:**
- ✅ **PHPCS**: 0 errors required (warnings acceptable)
- ✅ **PHPStan**: Level 6+ with 0 critical errors

**Failure Impact**: ❌ **BLOCKS** build-and-release stage

---

#### Job: `php-tests`

**Duration**: ~3 minutes  
**Runs on**: ubuntu-latest  
**Dependencies**: None (runs in parallel)  
**Services**: MySQL 8.0

**Steps:**
1. **Checkout code**
2. **Setup PHP 8.1** with xdebug
3. **Cache Composer**
4. **Install Composer dependencies**
5. **Install SVN** (for WordPress test suite)
6. **Setup WordPress Test Environment** - Install WordPress test library
7. **Create Theme Symlink** - Link theme to WordPress test install
8. **Run PHPUnit Tests** - Execute 108 tests (355 assertions)
9. **Upload Coverage** - Send to Codecov

**Quality Gates:**
- ✅ **PHPUnit**: 108/108 tests passing
- ✅ **Assertions**: 355 assertions must pass

**Failure Impact**: ❌ **BLOCKS** build-and-release stage

---

#### Job: `frontend-build`

**Duration**: ~2 minutes  
**Runs on**: ubuntu-latest  
**Dependencies**: None (runs in parallel)

**Steps:**
1. **Checkout code**
2. **Setup Node.js 18**
3. **Cache npm modules**
4. **Install npm dependencies** - `npm install`
5. **Build production assets** - `npm run prod`
6. **Verify outputs** - Check CSS/JS bundles exist

**Quality Gates:**
- ✅ **Build**: Must complete without errors
- ✅ **Outputs**: `css/main.bundle.css` and `js/main.bundle.js` must exist

**Failure Impact**: ❌ **BLOCKS** build-and-release stage

---

### Stage 2: Build & Release (Conditional)

#### Job: `build-and-release`

**Duration**: ~3 minutes  
**Runs on**: ubuntu-latest  
**Dependencies**: `needs: [code-quality, php-tests, frontend-build]`  
**Condition**: `if: startsWith(github.ref, 'refs/tags/v') || github.event_name == 'workflow_dispatch'`

**When it runs:**
- ✅ Only when tag matches `v*` pattern (e.g., v3.1.0)
- ✅ Or when manually dispatched with version input
- ❌ NOT on regular pushes or PRs

**Steps:**
1. **Checkout code**
2. **Setup PHP 8.1 + Node.js 18**
3. **Install production dependencies** - `composer install --no-dev`
4. **Install npm dependencies**
5. **Extract version** - From tag or workflow input
6. **Build production assets** - `npm run prod`
7. **Create release package** - ZIP excluding dev files
8. **Generate release notes** - Extract from CHANGELOG.md
9. **Create GitHub Release** - With ZIP and release notes
10. **Upload artifact** - For deploy stage

**Outputs:**
- `version` - Version number (e.g., "3.1.0")
- `tag` - Git tag (e.g., "v3.1.0")
- `zip_file` - ZIP filename (e.g., "soma-v3.1.0.zip")

**Excluded from ZIP:**
- Development files: `tests/`, `docs/`, `scripts/`
- Config files: `phpcs.xml`, `phpstan.neon`, `phpunit.xml`
- Dependencies: `node_modules/`, dev Composer packages
- Build files: `webpack.config.js`, `.editorconfig`

**Failure Impact**: ❌ **BLOCKS** deploy stage, release NOT created

---

### Stage 3: Deploy (Conditional)

#### Job: `deploy`

**Duration**: ~2 minutes  
**Runs on**: ubuntu-latest  
**Dependencies**: `needs: build-and-release`  
**Condition**: `if: success()`  
**Environment**: `production`

**When it runs:**
- ✅ Only after successful `build-and-release`
- ✅ Only if previous stage succeeded
- ❌ NOT if build-and-release failed or was skipped

**Steps:**
1. **Checkout code** - For scripts
2. **Download release artifact** - ZIP from build-and-release
3. **Setup SSH key** - Configure SFTP credentials
4. **Create backup** - Rename current `soma/` to `soma-backup-TIMESTAMP`
5. **Upload ZIP** - Transfer via SFTP to server
6. **Upload extraction script** - `soma-extractor.php` as `extract.php`
7. **Deployment summary** - Generate GitHub Actions summary

**Required Secrets:**
- `SFTP_SSH_KEY` - Base64-encoded SSH private key
- `SFTP_HOST` - Server IP or hostname
- `SFTP_USER` - cPanel username
- `SITE_DOMAIN` - Production domain (for summary)

**Deployment Path:**
```
public_html/
  wp-content/
    themes/
      soma/                  ← Will be renamed to soma-backup-YYYYMMDD-HHMMSS
      soma-vX.X.X.zip       ← Uploaded here
      extract.php           ← Uploaded here
```

**Manual Steps After Deploy:**
1. Visit `https://yourdomain.com/wp-content/themes/extract.php`
2. Or extract ZIP via cPanel File Manager
3. Verify version in WordPress Admin → Appearance → Themes

**Failure Impact**: ⚠️ Release created but NOT deployed (manual deployment needed)

---

### Final: Pipeline Summary

#### Job: `ci-cd-summary`

**Duration**: < 1 minute  
**Runs on**: ubuntu-latest  
**Dependencies**: `needs: [code-quality, php-tests, frontend-build, build-and-release, deploy]`  
**Condition**: `if: always()`

**Purpose**: Reports final status of all stages

**Output Example:**
```
## 🎯 CI/CD Pipeline Summary

**Trigger**: push
**Ref**: v3.1.0
**Commit**: abc123def456

### Stage Results
- 🔍 Code Quality: success
- 🧪 PHP Tests: success
- 🎨 Frontend Build: success
- 📦 Build & Release: success
- 🚀 Deploy: success

✅ **Quality gates passed!**
✅ **Release created!**
✅ **Deployed to production!**
```

**Always runs**: Even if earlier stages fail (reports failure status)

---

## Execution Flow Examples

### Example 1: Push to Branch

**Trigger:**
```bash
git push origin week-2
```

**What happens:**
1. ✅ `code-quality` runs (parallel)
2. ✅ `php-tests` runs (parallel)
3. ✅ `frontend-build` runs (parallel)
4. ⏭️ `build-and-release` SKIPPED (not a tag)
5. ⏭️ `deploy` SKIPPED (build-and-release skipped)
6. ✅ `ci-cd-summary` runs (reports quality gate results)

**Duration**: ~3-5 minutes (only quality gates)

---

### Example 2: Create Release Tag

**Trigger:**
```bash
git tag -a v3.1.1 -m "Release v3.1.1"
git push origin v3.1.1
```

**What happens:**
1. ✅ `code-quality` runs (parallel)
2. ✅ `php-tests` runs (parallel)
3. ✅ `frontend-build` runs (parallel)
4. ⏸️ Wait for quality gates to complete...
5. ✅ `build-and-release` runs (after quality gates pass)
6. ⏸️ Wait for release to complete...
7. ✅ `deploy` runs (after release succeeds)
8. ✅ `ci-cd-summary` runs (reports all results)

**Duration**: ~8-10 minutes (full pipeline)

**No race condition** because all jobs execute sequentially within same workflow!

---

### Example 3: Pull Request

**Trigger:**
```bash
gh pr create --base week-2 --title "feat: Add hero section" | cat
```

**What happens:**
1. ✅ `code-quality` runs (parallel)
2. ✅ `php-tests` runs (parallel)
3. ✅ `frontend-build` runs (parallel)
4. ⏭️ `build-and-release` SKIPPED (not a tag)
5. ⏭️ `deploy` SKIPPED (build-and-release skipped)
6. ✅ `ci-cd-summary` runs (reports quality gate results)

**PR Status**: ✅ Can merge if all quality gates pass  
**PR Status**: ❌ Cannot merge if any quality gate fails

---

### Example 4: Manual Dispatch

**Trigger:**
```bash
gh workflow run ci-cd.yml -f version=3.1.2 | cat
```

**What happens:**
1. ✅ `code-quality` runs (parallel)
2. ✅ `php-tests` runs (parallel)
3. ✅ `frontend-build` runs (parallel)
4. ⏸️ Wait for quality gates...
5. ✅ `build-and-release` runs (version from input)
6. ⏸️ Wait for release...
7. ✅ `deploy` runs (after release)
8. ✅ `ci-cd-summary` runs

**Use case**: Create release without pushing tag

---

## Environment Variables

```yaml
env:
  NODE_VERSION: "18"
  PHP_VERSION: "8.1"
  THEME_PATH: "wp-content/themes/soma"
```

**Used by**: All jobs  
**Purpose**: Centralize version configuration

---

## Required GitHub Secrets

| Secret | Description | Example |
|--------|-------------|---------|
| `SFTP_SSH_KEY` | Base64-encoded SSH private key | `LS0tLS1CRUd...` |
| `SFTP_HOST` | Server hostname or IP | `192.168.1.100` |
| `SFTP_USER` | cPanel/SFTP username | `cpanelusername` |
| `SITE_DOMAIN` | Production domain | `yourdomain.com` |

**Setup Guide**: See `docs/GITHUB_SECRETS_SETUP.md`

---

## Monitoring & Debugging

### View Workflow Runs

```bash
# List recent runs
gh run list --workflow=ci-cd.yml | cat

# View specific run
gh run view RUN_ID | cat

# Watch live run
gh run watch
```

### Check Logs

**Quality Gates:**
```bash
# View code-quality logs
gh run view RUN_ID --log --job=code-quality

# View test logs
gh run view RUN_ID --log --job=php-tests
```

**Release & Deploy:**
```bash
# View release logs
gh run view RUN_ID --log --job=build-and-release

# View deployment logs
gh run view RUN_ID --log --job=deploy
```

### Common Issues

#### Issue: Quality gates fail

**Solution:**
```bash
# Run locally
cd wp-content/themes/soma
composer phpcs      # Check coding standards
composer phpstan    # Check static analysis
composer test       # Run tests
```

#### Issue: Build-and-release fails

**Check:**
- CHANGELOG.md has entry for version
- Version in style.css matches tag
- All dependencies install correctly

#### Issue: Deploy fails

**Check:**
- GitHub Secrets configured correctly
- SFTP credentials valid
- Server has write permissions
- Network connectivity

---

## Migration from Old Workflows

### Before (v3.1.0 and earlier)

**Two separate workflows:**
- `quality-and-tests.yml` (302 lines)
- `release-and-deploy.yml` (357 lines)

**Problem:**
- Tag push triggered BOTH workflows simultaneously
- `wait-for-ci` job in release workflow raced with CI workflow
- Result: Failed deployments due to race condition

### After (v3.1.1+)

**Single unified workflow:**
- `ci-cd.yml` (546 lines)

**Solution:**
- One workflow eliminates cross-workflow dependencies
- `needs:` keyword creates proper sequential execution
- Quality gates → Build/Release → Deploy (guaranteed order)
- No more race conditions

### Migration Checklist

- ✅ Old workflows deleted: `quality-and-tests.yml`, `release-and-deploy.yml`
- ✅ New workflow created: `ci-cd.yml`
- ✅ Documentation updated: This file
- ✅ All functionality preserved
- ✅ Race condition eliminated

---

## Best Practices

### 1. Always let CI complete before tagging

```bash
# ❌ BAD: Tag immediately after push
git push origin week-2
git tag v3.1.1
git push origin v3.1.1

# ✅ GOOD: Wait for CI to pass
git push origin week-2
gh run watch  # Wait for quality gates
git tag v3.1.1
git push origin v3.1.1
```

### 2. Use semantic versioning

```bash
# Format: vMAJOR.MINOR.PATCH
v3.0.0  # Major release
v3.1.0  # New features
v3.1.1  # Bug fixes
```

### 3. Update CHANGELOG.md before tagging

```markdown
## [3.1.1] - 2025-12-18

### Fixed
- Race condition in CI/CD workflow
- ...
```

### 4. Test on staging first

```bash
# Create PR to week-N (staging)
gh pr create --base week-2 | cat

# After merge and tests pass
git tag v3.1.1
git push origin v3.1.1
```

---

## Performance Metrics

| Stage | Jobs | Duration | Parallel |
|-------|------|----------|----------|
| Quality Gates | 3 | ~3-5 min | ✅ Yes |
| Build & Release | 1 | ~3 min | ❌ No |
| Deploy | 1 | ~2 min | ❌ No |
| **Total** | **5** | **~8-10 min** | **Mixed** |

**Optimization:**
- Quality gates run in parallel (3 jobs = ~3-5 min instead of ~9-15 min)
- Build/release and deploy run sequentially (required for data flow)

---

## Troubleshooting

### Workflow doesn't trigger

**Check:**
- Push event matches trigger pattern
- Workflow file syntax valid
- Branch protection rules not blocking

### Quality gates fail

**Local debugging:**
```bash
composer phpcs      # Fix: composer phpcbf
composer phpstan    # Fix: Add type hints
composer test       # Fix: Update tests
npm run prod        # Fix: Check webpack config
```

### Release not created

**Check:**
- Tag format matches `v*` pattern
- Quality gates passed
- CHANGELOG.md has entry for version

### Deployment fails

**Check:**
```bash
# Test SFTP workflow
gh workflow run test-sftp-secrets.yml | cat
```

---

## Related Documentation

- **GitHub Secrets Setup**: `docs/GITHUB_SECRETS_SETUP.md`
- **Testing Guide**: `docs/TESTING_GUIDE.md`
- **Development Guide**: `docs/DEVELOPMENT.md`
- **Helper Functions**: `docs/HELPERS.md`

---

**Document Version**: 1.0  
**Workflow Version**: 1.0 (Unified)  
**Last Updated**: December 18, 2025  
**Maintainer**: Miguel Colmenares  
**Related Issue**: #72 - Unify CI/CD workflows to prevent race conditions

