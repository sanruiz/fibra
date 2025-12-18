# SOMA Theme - GitHub Workflows Documentation

**Version**: 3.1.0  
**Last Updated**: December 18, 2025

---

## Overview

This directory contains documentation for all GitHub Actions workflows used in the SOMA theme development and deployment pipeline.

### Available Workflows

| Workflow | File | Purpose | Trigger | Status |
|----------|------|---------|---------|--------|
| **[CI/CD Unified](workflows/CI_CD.md)** | `ci-cd.yml` | **CI/CD** - Quality gates, build, release, and deploy | Push, PR, Tags | ✅ Active |
| **[Test SFTP Secrets](workflows/TEST_SFTP_SECRETS.md)** | `test-sftp-secrets.yml` | Validate GitHub Secrets and SFTP connectivity | Manual | ✅ Active |
| ~~[Quality & Tests](workflows/QUALITY_AND_TESTS.md)~~ | ~~`quality-and-tests.yml`~~ | **DEPRECATED** - Replaced by ci-cd.yml | - | ❌ Removed |
| ~~[Release & Deploy](workflows/RELEASE_AND_DEPLOY.md)~~ | ~~`release-and-deploy.yml`~~ | **DEPRECATED** - Replaced by ci-cd.yml | - | ❌ Removed |

---

## CI/CD Architecture

### Unified Workflow (v3.1.1+)

SOMA now uses a **unified CI/CD workflow** that combines all pipeline stages into a single file to **prevent race conditions**.

#### Problem with Previous Architecture (v3.1.0 and earlier)

**Two separate workflows caused race conditions:**
- `quality-and-tests.yml` - CI workflow
- `release-and-deploy.yml` - CD workflow

**Issue:**
When pushing a version tag (e.g., `v3.1.0`), BOTH workflows triggered simultaneously:
1. Tag push → Triggers `quality-and-tests.yml` (starts CI)
2. Tag push → Triggers `release-and-deploy.yml` (starts CD)
3. CD workflow has `wait-for-ci` job that checks if CI passed via GitHub API
4. **Race condition**: `wait-for-ci` finds CI workflow "in_progress" because both started at same time
5. **Result**: Deployment fails with "CI not yet complete" error

**Impact**: 3 failed deployment attempts for v3.1.0 release.

---

#### Solution: Unified Workflow (v3.1.1+)

**Single workflow file eliminates race conditions:**
- `ci-cd.yml` - Unified CI/CD pipeline (546 lines)

**How it works:**
1. **Stage 1: Quality Gates** (parallel) - Always runs
   - `code-quality` - PHPCS + PHPStan
   - `php-tests` - PHPUnit 108 tests
   - `frontend-build` - Webpack production build

2. **Stage 2: Build & Release** (conditional) - Only on tags
   - `needs: [code-quality, php-tests, frontend-build]`
   - Creates production ZIP
   - Creates GitHub release
   - Uploads artifact

3. **Stage 3: Deploy** (conditional) - Only after release
   - `needs: build-and-release`
   - Downloads artifact
   - Deploys to production via SFTP

**Benefits:**
- ✅ No race conditions (single workflow file)
- ✅ Guaranteed sequential execution (`needs:` dependencies)
- ✅ Quality gates run first (parallel)
- ✅ Build/release only after quality gates pass
- ✅ Deploy only after successful release
- ✅ All functionality from both old workflows preserved

---

## Quick Start

### 1. Initial Setup

Before using any workflow, configure GitHub Secrets:

```bash
# See complete guide
cat docs/GITHUB_SECRETS_SETUP.md
```

**Required Secrets:**
- `SFTP_SSH_KEY` - Base64-encoded SSH private key
- `SFTP_HOST` - Server IP address
- `SFTP_USER` - cPanel username
- `SITE_DOMAIN` - Production domain

### 2. Test Your Configuration

Run the test workflow to validate secrets:

```bash
# Via GitHub UI
1. Go to Actions → Test SFTP Secrets
2. Click "Run workflow"
3. Select test type: connection / upload / full
4. Click "Run workflow"

# Or via GitHub CLI
gh workflow run test-sftp-secrets.yml -f test_type=full | cat
```

### 3. Deploy to Production

Create and push a tag:

```bash
# Update version
vim wp-content/themes/soma/style.css  # Version: 3.1.1

# Update CHANGELOG
vim wp-content/themes/soma/CHANGELOG.md  # Add [3.1.1] section

# Commit
git add .
git commit -m "chore: release v3.1.1"
git push origin week-2

# Wait for CI to pass
gh run watch

# Create and push tag (triggers full pipeline)
git tag -a v3.1.1 -m "Release v3.1.1"
git push origin v3.1.1

# Monitor deployment
gh run watch
```

---

## Workflow Architecture

### Unified Pipeline Flow (v3.1.1+)

```
┌─────────────┐
│  Developer  │
│  Git Push   │  
└──────┬──────┘
       │
       ▼
┌────────────────────────────────────────────────────┐
│        GitHub Actions - ci-cd.yml                   │
├────────────────────────────────────────────────────┤
│                                                     │
│  STAGE 1: QUALITY GATES (Parallel - Always Runs)   │
│  ┌──────────────┐ ┌──────────────┐ ┌────────────┐ │
│  │ Code Quality │ │  PHP Tests   │ │  Frontend  │ │
│  │ PHPCS+PHPStan│ │  PHPUnit 108 │ │   Build    │ │
│  └──────────────┘ └──────────────┘ └────────────┘ │
│         │                │                │         │
│         └────────────────┼────────────────┘         │
│                          ↓                          │
│  STAGE 2: BUILD & RELEASE (Conditional - Tags Only)│
│  ┌─────────────────────────────────────────────┐  │
│  │ • Extract version from tag                  │  │
│  │ • Install production dependencies           │  │
│  │ • Build production assets                   │  │
│  │ • Create release ZIP                        │  │
│  │ • Generate release notes from CHANGELOG     │  │
│  │ • Create GitHub Release                     │  │
│  │ • Upload artifact                           │  │
│  └─────────────────────────────────────────────┘  │
│                          ↓                          │
│  STAGE 3: DEPLOY (Conditional - After Release)     │
│  ┌─────────────────────────────────────────────┐  │
│  │ • Download release artifact                 │  │
│  │ • Setup SSH credentials                     │  │
│  │ • Create backup on server                   │  │
│  │ • Upload ZIP via SFTP                       │  │
│  │ • Upload extraction script                  │  │
│  └─────────────────────────────────────────────┘  │
│                          ↓                          │
│  FINAL: PIPELINE SUMMARY (Always Runs)             │
│  Reports status of all stages                      │
└────────────────────────────────────────────────────┘
       │
       ▼
┌──────────────┐
│  Production  │
│  fibrasoma   │
└──────────────┘
```

**Key Difference from v3.1.0:**
- ✅ Single workflow file (no race conditions)
- ✅ Sequential execution with `needs:` dependencies
- ✅ Quality gates always run first (parallel)
- ✅ Build/Release only after quality gates pass
- ✅ Deploy only after successful release

---

## Common Tasks

### Run Quality Checks Locally

```bash
# Before pushing code
cd wp-content/themes/soma
composer phpcs       # WordPress Coding Standards
composer phpstan     # Static Analysis Level 6+
composer test        # PHPUnit 108 tests
npm run prod         # Frontend build
```

### Monitor Workflow Execution

```bash
# List recent runs
gh run list --workflow=ci-cd.yml --limit 5 | cat

# Watch specific run
gh run watch

# View logs
gh run view RUN_ID --log | cat

# View specific job
gh run view RUN_ID --job=build-and-release --log | cat
```

### Create Release

```bash
# Ensure on latest
git checkout week-2
git pull origin week-2

# Update version files
# 1. wp-content/themes/soma/style.css → Version: 3.1.1
# 2. wp-content/themes/soma/CHANGELOG.md → Add [3.1.1] section

# Commit changes
git add .
git commit -m "chore: bump version to v3.1.1"
git push origin week-2

# Wait for CI
gh run watch

# Create and push tag
git tag -a v3.1.1 -m "Release v3.1.1: Bug fixes and improvements"
git push origin v3.1.1

# Monitor full pipeline
gh run watch
```

### Debug Failed Deployment

```bash
# Check workflow status
gh run list --workflow=ci-cd.yml --limit 1 | cat

# View failed job logs
gh run view RUN_ID --log | cat

# Test SFTP connectivity
gh workflow run test-sftp-secrets.yml -f test_type=full | cat

# Check specific stage
gh run view RUN_ID --job=code-quality --log | cat    # Quality gates
gh run view RUN_ID --job=build-and-release --log | cat  # Release
gh run view RUN_ID --job=deploy --log | cat         # Deployment
```

---

## Best Practices

### Versioning

- ✅ Use semantic versioning: `vMAJOR.MINOR.PATCH`
- ✅ Create annotated tags: `git tag -a v3.1.1 -m "Release notes"`
- ✅ Update `style.css` version before tagging
- ✅ Update `CHANGELOG.md` before tagging
- ✅ Wait for Stage 1 (quality gates) to pass before creating tag
- ✅ Monitor all 3 stages after tag push

### Testing

- ✅ Run `composer phpcs` before pushing (must pass with 0 errors)
- ✅ Run `composer phpstan` before pushing (Level 6+ with 0 critical errors)
- ✅ Run `composer test` before pushing (108/108 tests must pass)
- ✅ Run `npm run prod` before pushing (build must succeed)
- ✅ Use `test-sftp-secrets.yml` workflow to validate secrets
- ✅ Monitor ci-cd.yml workflow logs after push

### Security

- ✅ Never commit secrets to repository
- ✅ Store all credentials in GitHub Secrets
- ✅ Rotate SSH keys every 6-12 months
- ✅ Use environment protection for production
- ✅ Review workflow permissions regularly
- ✅ Test secrets with `test-sftp-secrets.yml` after rotation

### Workflow Management

- ✅ Use PRs to trigger Stage 1 (quality gates) before merging
- ✅ Use tags to trigger full pipeline (Stages 1 → 2 → 3)
- ✅ Use `workflow_dispatch` to test workflow changes
- ✅ Monitor all stages: quality → build/release → deploy
- ✅ Verify deployment on server after workflow completes

---

## Troubleshooting

### Common Issues

| Issue | Symptom | Solution | Documentation |
|-------|---------|----------|---------------|
| **Stage 1 fails** | Quality gates don't pass | Run locally: `composer phpcs`, `composer phpstan`, `composer test`, `npm run prod` | [CI_CD.md § Troubleshooting](workflows/CI_CD.md#troubleshooting) |
| **Stage 2 doesn't run** | Build/release skipped | Check tag format `v3.1.1`, verify pushed with `git push origin v3.1.1` | [CI_CD.md § Triggers](workflows/CI_CD.md#triggers) |
| **Stage 3 fails** | Deployment errors | Test secrets with `test-sftp-secrets.yml`, check server access | [TEST_SFTP_SECRETS.md](workflows/TEST_SFTP_SECRETS.md) |
| **PHPCS errors** | Code standards violations | Run `composer phpcbf` to auto-fix, then `composer phpcs` to verify | [CI_CD.md § Code Quality](workflows/CI_CD.md#code-quality-job) |
| **PHPStan errors** | Type hint issues | Fix type hints and docblocks, ensure proper @param/@return annotations | [CI_CD.md § Code Quality](workflows/CI_CD.md#code-quality-job) |
| **PHPUnit failures** | Tests not passing | Check error messages, run locally with `composer test --testdox` | [CI_CD.md § PHP Tests](workflows/CI_CD.md#php-tests-job) |
| **Frontend build fails** | npm/webpack errors | Delete `node_modules`, run `npm install`, then `npm run prod` | [CI_CD.md § Frontend Build](workflows/CI_CD.md#frontend-build-job) |
| **SFTP upload fails** | Network/auth issues | Verify secrets correct, test with `test-sftp-secrets.yml -f test_type=full` | [TEST_SFTP_SECRETS.md § Troubleshooting](workflows/TEST_SFTP_SECRETS.md#troubleshooting) |
| **Version mismatch** | Wrong version deployed | Ensure `style.css` version matches tag, update before creating tag | [CI_CD.md § Version Extraction](workflows/CI_CD.md#version-extraction) |
| **Release notes missing** | Empty GitHub release | Update `CHANGELOG.md` with version section before tagging | [CI_CD.md § Release Notes](workflows/CI_CD.md#release-notes-generation) |

### Debugging Steps

**1. Check Workflow Status:**
```bash
gh run list --workflow=ci-cd.yml --limit 5 | cat
gh run view RUN_ID | cat
```

**2. View Stage-Specific Logs:**
```bash
# Stage 1: Quality Gates (parallel)
gh run view RUN_ID --job=code-quality --log | cat
gh run view RUN_ID --job=php-tests --log | cat
gh run view RUN_ID --job=frontend-build --log | cat

# Stage 2: Build & Release (conditional)
gh run view RUN_ID --job=build-and-release --log | cat

# Stage 3: Deploy (conditional)
gh run view RUN_ID --job=deploy --log | cat

# Final: Summary (always runs)
gh run view RUN_ID --job=ci-cd-summary --log | cat
```

**3. Test SFTP Secrets:**
```bash
# Test connection only
gh workflow run test-sftp-secrets.yml -f test_type=connection | cat
gh run watch

# Test full upload cycle
gh workflow run test-sftp-secrets.yml -f test_type=full | cat
gh run watch
```

**4. Manual Workflow Trigger:**
```bash
# Test workflow without creating tag
gh workflow run ci-cd.yml | cat
gh run watch
```

### Getting Help

**Documentation:**
- **[CI/CD Workflow](workflows/CI_CD.md)** - Complete unified workflow documentation
- **[SFTP Testing](workflows/TEST_SFTP_SECRETS.md)** - Secret validation workflow
- **[GitHub Secrets Setup](GITHUB_SECRETS_SETUP.md)** - Secret configuration guide
- **[Testing Guide](TESTING_GUIDE.md)** - Testing infrastructure

**Logs:**
- **GitHub Actions**: `gh run view RUN_ID --log | cat`
- **Server Logs**: `wp-content/uploads/soma-logs/soma.log`
- **WordPress Debug**: `wp-content/debug.log`

**Support:**
- GitHub Issues: Report bugs and feature requests
- Workflow Runs: Monitor at https://github.com/sanruiz/fibra/actions
- Documentation: Complete guides in `docs/workflows/`

---

## Additional Resources

- **[Development Guide](DEVELOPMENT.md)** - Complete developer documentation with setup, architecture, patterns
- **[Testing Guide](TESTING_GUIDE.md)** - Testing infrastructure with PHPUnit, PHPCS, PHPStan
- **[GitHub Secrets Setup](GITHUB_SECRETS_SETUP.md)** - Detailed secret configuration guide
- **[Migration Guide](MIGRATION_FROM_V2.md)** - Upgrading from v2.x to v3.x
- **[CI/CD Workflow](workflows/CI_CD.md)** - Unified ci-cd.yml workflow documentation
- **[SFTP Testing](workflows/TEST_SFTP_SECRETS.md)** - Test workflow for secret validation

---

**Document Version**: 2.0  
**Last Updated**: December 18, 2025  
**Workflow Version**: ci-cd.yml (unified)  
**Maintainer**: Miguel Colmenares
