# SOMA Theme - GitHub Workflows Documentation

**Version**: 3.0.0  
**Last Updated**: December 14, 2025

---

## Overview

This directory contains documentation for all GitHub Actions workflows used in the SOMA theme development and deployment pipeline.

### Available Workflows

| Workflow | File | Purpose | Trigger |
|----------|------|---------|---------|
| **[Release & Deploy](workflows/RELEASE_AND_DEPLOY.md)** | `release-and-deploy.yml` | Complete CI/CD pipeline for production deployment | Tags (v*), Push, PR |
| **[Test SFTP Secrets](workflows/TEST_SFTP_SECRETS.md)** | `test-sftp-secrets.yml` | Validate GitHub Secrets and SFTP connectivity | Manual dispatch |

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
gh workflow run test-sftp-secrets.yml -f test_type=full
```

### 3. Deploy to Production

Create and push a tag:

```bash
# Update version
vim wp-content/themes/soma/style.css  # Version: 3.0.1

# Commit
git add .
git commit -m "chore: release v3.0.1"
git push origin main

# Create tag
git tag -a v3.0.1 -m "Release v3.0.1"
git push origin v3.0.1
```

---

## Workflow Architecture

### CI/CD Pipeline Flow

```
┌─────────────┐
│  Developer  │
│  Git Push   │
└──────┬──────┘
       │
       ▼
┌──────────────────────────────────────┐
│     GitHub Actions Workflows          │
├──────────────────────────────────────┤
│                                       │
│  ┌─────────────────────────────────┐ │
│  │   Code Quality (Parallel)       │ │
│  │   • PHPCS (WordPress Standards) │ │
│  │   • PHPStan (Static Analysis)   │ │
│  │   • Auto-fix with PHPCBF        │ │
│  └─────────────────────────────────┘ │
│                                       │
│  ┌─────────────────────────────────┐ │
│  │   Tests (Parallel)              │ │
│  │   • PHPUnit (108 tests)         │ │
│  │   • WordPress Integration       │ │
│  └─────────────────────────────────┘ │
│                                       │
│  ┌─────────────────────────────────┐ │
│  │   Build (Parallel)              │ │
│  │   • Composer install            │ │
│  │   • npm build (Webpack)         │ │
│  └─────────────────────────────────┘ │
│                                       │
│  ┌─────────────────────────────────┐ │
│  │   Release (On Tag Only)         │ │
│  │   • Create GitHub Release       │ │
│  │   • Generate ZIP package        │ │
│  └─────────────────────────────────┘ │
│                                       │
│  ┌─────────────────────────────────┐ │
│  │   Deploy (On Tag Only)          │ │
│  │   • Backup current theme        │ │
│  │   • Upload via SFTP             │ │
│  │   • Auto-extract on server      │ │
│  └─────────────────────────────────┘ │
│                                       │
└──────────────────────────────────────┘
       │
       ▼
┌──────────────┐
│  Production  │
│  fibrasoma   │
└──────────────┘
```

---

## Common Tasks

### Run Tests Before Deployment

```bash
# Local tests
cd wp-content/themes/soma
composer test
composer phpcs
composer phpstan

# Remote test (validates secrets)
gh workflow run test-sftp-secrets.yml -f test_type=full
```

### Monitor Workflow Execution

```bash
# List recent runs
gh run list --workflow=release-and-deploy.yml --limit 5

# Watch specific run
gh run watch <run-id>

# View logs
gh run view <run-id> --log
```

### Debug Failed Deployment

```bash
# Download workflow artifacts
gh run download <run-id>

# Check specific job
gh run view <run-id> --job=<job-id> --log
```

---

## Best Practices

### Versioning

- ✅ Use semantic versioning: `vMAJOR.MINOR.PATCH`
- ✅ Create annotated tags: `git tag -a v3.0.1 -m "Release notes"`
- ✅ Update `style.css` version before tagging
- ✅ Document changes in `CHANGELOG.md`

### Testing

- ✅ Run `composer validate` before pushing
- ✅ Test locally with `composer test`
- ✅ Use test workflow before production deployment
- ✅ Review workflow logs before tagging

### Security

- ✅ Never commit secrets to repository
- ✅ Rotate SSH keys every 6-12 months
- ✅ Use environment protection for production
- ✅ Review workflow permissions regularly

---

## Troubleshooting

### Common Issues

| Issue | Solution | Documentation |
|-------|----------|---------------|
| Secrets validation fails | Check Base64 encoding | [Test Workflow](workflows/TEST_SFTP_SECRETS.md#troubleshooting) |
| PHPCS errors | Run `composer phpcbf` | [Release Workflow](workflows/RELEASE_AND_DEPLOY.md#troubleshooting) |
| Build fails | Check Node.js version | [Release Workflow](workflows/RELEASE_AND_DEPLOY.md#build-issues) |
| SFTP upload fails | Verify server access | [Test Workflow](workflows/TEST_SFTP_SECRETS.md#connection-tests) |

### Getting Help

1. Check workflow-specific documentation in `docs/workflows/`
2. Review workflow logs in GitHub Actions
3. Test secrets with test workflow
4. Check server logs in cPanel

---

## Additional Resources

- **[Development Guide](DEVELOPMENT.md)** - Complete developer documentation
- **[Testing Guide](TESTING_GUIDE.md)** - Testing infrastructure
- **[GitHub Secrets Setup](GITHUB_SECRETS_SETUP.md)** - Secret configuration guide
- **[Migration Guide](MIGRATION_FROM_V2.md)** - Upgrading from v2.x

---

**Document Version**: 1.0  
**Last Updated**: December 14, 2025  
**Maintainer**: SOMA Development Team
