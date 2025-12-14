# Release and Deploy Workflow

**Workflow File**: `.github/workflows/release-and-deploy.yml`  
**Purpose**: Complete CI/CD pipeline for automated testing, building, and deployment  
**Triggers**: Push to main, Pull Requests, Tag creation (v*)

---

## Overview

This workflow automates the complete lifecycle of the SOMA WordPress theme from code push to production deployment on shared hosting infrastructure.

### Pipeline Stages

```
┌─────────────┐
│  Git Event  │
│ (push/tag)  │
└──────┬──────┘
       │
       ▼
┌──────────────────────────────────────────────────┐
│          Stage 1: Code Quality (Parallel)         │
│  ┌─────────────────────────────────────────────┐ │
│  │ • PHPCS - WordPress Coding Standards        │ │
│  │ • PHPStan - Static Analysis (Level 6+)      │ │
│  │ • PHPCBF - Auto-fix code style issues       │ │
│  │ ❌ Blocks deployment if critical errors     │ │
│  └─────────────────────────────────────────────┘ │
└──────────────────────────────────────────────────┘
       │
       ▼
┌──────────────────────────────────────────────────┐
│          Stage 2: PHP Tests (Parallel)            │
│  ┌─────────────────────────────────────────────┐ │
│  │ • PHPUnit - 108 tests, 355 assertions       │ │
│  │ • WordPress Integration Tests               │ │
│  │ • Coverage Reports                          │ │
│  │ ⚠️  Warns but doesn't block deployment      │ │
│  └─────────────────────────────────────────────┘ │
└──────────────────────────────────────────────────┘
       │
       ▼
┌──────────────────────────────────────────────────┐
│       Stage 3: Frontend Build (Parallel)          │
│  ┌─────────────────────────────────────────────┐ │
│  │ • npm install dependencies                  │ │
│  │ • Webpack production build                  │ │
│  │ • Asset verification (CSS/JS bundles)       │ │
│  │ ❌ Blocks deployment if build fails         │ │
│  └─────────────────────────────────────────────┘ │
└──────────────────────────────────────────────────┘
       │
       ▼ (Only on Tag)
┌──────────────────────────────────────────────────┐
│      Stage 4: Build & Release (Tag Only)          │
│  ┌─────────────────────────────────────────────┐ │
│  │ • Composer install --no-dev --optimize      │ │
│  │ • Build production assets                   │ │
│  │ • Create ZIP package (excluding dev files)  │ │
│  │ • Generate automated release notes          │ │
│  │ • Create GitHub Release with ZIP artifact   │ │
│  └─────────────────────────────────────────────┘ │
└──────────────────────────────────────────────────┘
       │
       ▼ (Only on Tag)
┌──────────────────────────────────────────────────┐
│         Stage 5: Deploy (Tag Only)                │
│  ┌─────────────────────────────────────────────┐ │
│  │ • Backup current theme (timestamped)        │ │
│  │ • Upload ZIP via SFTP                       │ │
│  │ • Upload PHP extraction script              │ │
│  │ • Trigger auto-extraction                   │ │
│  └─────────────────────────────────────────────┘ │
└──────────────────────────────────────────────────┘
       │
       ▼
┌───────────────┐
│  Production   │
│  fibrasoma    │
└───────────────┘
```

---

## Configuration

### Required GitHub Secrets

| Secret Name | Example Value | Purpose |
|-------------|---------------|---------||
| `SFTP_SSH_KEY` | `LS0tLS1CRUdJTi...` | Base64-encoded SSH private key |
| `SFTP_HOST` | `123.456.789.012` | Production server IP address |
| `SFTP_USER` | `your_cpanel_user` | cPanel/SFTP username |
| `SITE_DOMAIN` | `yourdomain.com` | Site domain for verification |

See [../GITHUB_SECRETS_SETUP.md](../GITHUB_SECRETS_SETUP.md) for detailed setup instructions.

### Workflow Permissions

```yaml
permissions:
  contents: write    # Create releases
  actions: read      # Read workflow artifacts
```

---

## Usage

### Development Workflow (No Deployment)

**Push to main or create Pull Request:**

```bash
git add .
git commit -m "feat: add new feature"
git push origin main
```

**Actions executed:**
- ✅ Code Quality checks
- ✅ PHP Tests
- ✅ Frontend Build
- ❌ NO release or deployment

### Production Deployment Workflow

**Create and push a version tag:**

```bash
# 1. Update version in style.css
vim wp-content/themes/soma/style.css
# Change: Version: 3.0.1

# 2. Update CHANGELOG.md
vim wp-content/themes/soma/CHANGELOG.md

# 3. Commit changes
git add .
git commit -m "chore: release v3.0.1"
git push origin main

# 4. Create annotated tag
git tag -a v3.0.1 -m "Release v3.0.1

## Features
- New feature X
- Improvement Y

## Bug Fixes
- Fix Z
"

# 5. Push tag to trigger deployment
git push origin v3.0.1
```

**Actions executed:**
- ✅ Code Quality checks
- ✅ PHP Tests
- ✅ Frontend Build
- ✅ **Build & Release** (creates GitHub Release)
- ✅ **Deploy to Production** (SFTP upload)

---

## Quality Gates

### PHPCS (WordPress Coding Standards)

**What it checks:**
- WordPress coding standards compliance
- PHP syntax errors
- Code formatting issues
- Naming conventions

**Auto-fix capability:**
```bash
# Workflow automatically runs PHPCBF to fix issues
# You can also run locally:
cd wp-content/themes/soma
composer phpcbf
```

**Failure behavior:**
- ❌ Blocks deployment if critical violations found
- Displays detailed error report in logs

---

### PHPStan (Static Analysis)

**What it checks:**
- Type safety violations
- Undefined variables
- Missing return types
- Potential bugs

**Analysis level:** Level 6+ (strict)

**Failure behavior:**
- ❌ Blocks deployment if errors found
- Requires manual fixes

---

### PHPUnit Tests

**Test coverage:**
- 108 tests
- 355 assertions
- Unit and integration tests

**Failure behavior:**
- ⚠️ Warns but doesn't block deployment
- Generates coverage reports
- Displays failed test details

---

## Build Process

### Backend Build

**Steps:**
1. Install Composer dependencies (`--no-dev --optimize-autoloader`)
2. Generate optimized autoloader
3. Exclude development files from package

**Excluded files:**
```
tests/
node_modules/
.git/
.github/ (except scripts)
*.md (except README.md)
phpunit.xml
phpstan.neon
```

### Frontend Build

**Steps:**
1. Install npm dependencies
2. Run Webpack production build
3. Verify output files exist:
   - `css/main.bundle.css`
   - `js/main.bundle.js`

**Build configuration:**
- Minification enabled
- Source maps disabled
- Asset optimization

---

## Deployment Strategy

### Shared Hosting Limitations

**Constraints:**
- ❌ No SSH shell access
- ❌ No remote command execution
- ✅ SFTP access only

**Solution:**
- Upload ZIP via SFTP
- Use PHP script for extraction
- Manual trigger or auto-extraction

### Backup Strategy

**Automatic backup:**
```
Current theme: soma/
     ↓
Renamed to: soma-backup-YYYYMMDD-HHMMSS/
     ↓
New theme extracted: soma/
```

**Backup location:** Same directory as theme  
**Naming pattern:** `soma-backup-YYYYMMDD-HHMMSS`

### Deployment Steps

1. **Upload ZIP**
   ```
   Source: soma-v3.0.1.zip (GitHub Release)
   Destination: /public_html/wp-content/themes/
   Method: SFTP with SSH key authentication
   ```

2. **Upload Extractor**
   ```
   Source: .github/scripts/soma-extractor.php
   Destination: /public_html/wp-content/themes/soma-extractor.php
   ```

3. **Extract Theme**
   ```
   Option 1: Visit https://fibrasoma.group/wp-content/themes/soma-extractor.php
   Option 2: cPanel File Manager → Extract
   ```

---

## Monitoring

### View Workflow Status

**Via GitHub UI:**
1. Navigate to **Actions** tab
2. Select workflow run
3. View job statuses and logs

**Via GitHub CLI:**
```bash
# List recent runs
gh run list --workflow=release-and-deploy.yml --limit 5

# Watch specific run
gh run watch <run-id>

# View logs
gh run view <run-id> --log
```

### Workflow Outputs

**Success summary:**
```markdown
## 🚀 Deployment Summary

**Version**: 3.0.1
**Environment**: Production (Shared Hosting via SFTP)
**Host**: XXX.XXX.XXX.XXX

### Files Uploaded
- ✅ soma-v3.0.1.zip (2.5 MB)
- ✅ soma-extractor.php (8.2 KB)

### Next Steps
1. Visit: https://yourdomain.com/wp-content/themes/soma-extractor.php
2. Or extract manually in cPanel File Manager

✅ Deployment completed successfully!
```

---

## Troubleshooting

### Issue: PHPCS Violations

**Symptom:** Pipeline fails at code-quality stage

**Solution:**
```bash
cd wp-content/themes/soma

# Auto-fix
composer phpcbf

# Verify
composer phpcs

# Commit fixes
git add .
git commit -m "fix: phpcs violations"
git push
```

---

### Issue: PHPStan Errors

**Symptom:** Pipeline fails at code-quality stage

**Solution:**
```bash
cd wp-content/themes/soma

# View errors
composer phpstan

# Fix manually (add type hints, docblocks, etc.)
# ...

# Verify
composer phpstan

# Commit
git add .
git commit -m "fix: phpstan type errors"
git push
```

---

### Issue: Build Failures

**Symptom:** Frontend build fails

**Solution:**
```bash
cd wp-content/themes/soma

# Clean install
rm -rf node_modules package-lock.json
npm install

# Build
npm run prod

# Verify outputs
ls -lh css/main.bundle.css js/main.bundle.js

# Commit if needed
git add .
git commit -m "fix: rebuild assets"
git push
```

---

### Issue: SFTP Upload Fails

**Symptom:** Deploy stage fails with permission denied

**Possible causes:**
1. SSH key not configured correctly in secrets
2. Server IP or username incorrect
3. Server denies connection

**Solutions:**

**Test secrets:**
```bash
# Run test workflow first
gh workflow run test-sftp-secrets.yml -f test_type=connection
```

**Verify SSH key:**
- Check key is properly Base64-encoded
- Verify key format (starts with `LS0tLS1CRUdJTi...`)
- Ensure key is authorized in cPanel

**Check server access:**
- Login to cPanel → SSH Access → Manage SSH Keys
- Verify `soma_rsa` key is "Authorized"

---

### Issue: Extraction Fails

**Symptom:** ZIP uploaded but not extracted

**Solutions:**

**Option 1: PHP Script**
```
Visit: https://yourdomain.com/wp-content/themes/soma-extractor.php
```

**Option 2: cPanel File Manager**
1. Login to cPanel
2. File Manager → `/public_html/wp-content/themes/`
3. Right-click `soma-v3.0.1.zip`
4. Select "Extract"
5. Confirm extraction location

**Option 3: Manual SFTP**
```bash
# Download, extract locally, re-upload
sftp USERNAME@SERVER_IP
cd public_html/wp-content/themes
get soma-v3.0.1.zip
quit

unzip soma-v3.0.1.zip -d soma-extracted
# Re-upload extracted files
```

---

## Performance Metrics

### Typical Execution Times

| Stage | Duration | Notes |
|-------|----------|-------|
| Code Quality | 2-3 min | Parallel execution |
| PHP Tests | 3-5 min | WordPress setup overhead |
| Frontend Build | 2-4 min | Webpack compilation |
| Build & Release | 3-5 min | Composer + npm + ZIP creation |
| Deploy | 5-10 min | SFTP upload (depends on connection) |
| **Total** | **15-25 min** | From push to deployed |

### Optimization Tips

- Use caching for Composer and npm dependencies
- Parallelize independent jobs
- Minimize ZIP file size (exclude dev files)

---

## Security Considerations

### Secret Management

- ✅ Never commit secrets to repository
- ✅ GitHub encrypts all secret values
- ✅ Secrets are masked in logs
- ✅ Use Base64 for multiline values (SSH keys)
- ⚠️ Rotate SSH keys every 6-12 months

### Workflow Permissions

```yaml
permissions:
  contents: write      # Minimum required for releases
  actions: read        # Read-only access to artifacts
```

**Principle of Least Privilege:**
- Only grant necessary permissions
- Review permissions quarterly
- Audit workflow changes

### SFTP Security

- Use SSH key authentication (not passwords)
- Limit key permissions (chmod 600)
- Restrict cPanel user access to theme directory only
- Monitor server logs for unauthorized access

---

## Rollback Procedure

### Option 1: Restore from Backup

```bash
# Via cPanel File Manager
1. Navigate to: public_html/wp-content/themes/
2. Rename: soma → soma-failed
3. Rename: soma-backup-YYYYMMDD-HHMMSS → soma
4. Clear WordPress cache
```

### Option 2: Re-deploy Previous Version

```bash
# Create new tag from previous commit
git checkout v3.0.0  # Last working version
git tag -a v3.0.0-hotfix -m "Rollback to v3.0.0"
git push origin v3.0.0-hotfix
```

### Option 3: Manual Upload

```bash
# Download previous release ZIP
# Upload manually via SFTP or cPanel
```

---

## Best Practices

### Before Creating Release

- ✅ Run all tests locally: `composer validate`
- ✅ Update version in `style.css`
- ✅ Document changes in `CHANGELOG.md`
- ✅ Test on staging environment if available
- ✅ Review Git diff before commit

### Versioning Strategy

- **MAJOR** (v3.x.x): Breaking changes
- **MINOR** (vx.1.x): New features (backward compatible)
- **PATCH** (vx.x.1): Bug fixes only

### Tag Naming

```bash
# ✅ GOOD
git tag -a v3.0.1 -m "Detailed release notes"

# ❌ BAD
git tag v3.0.1  # Not annotated
git tag release-3.0.1  # Wrong format
```

---

## Related Documentation

- **[Test SFTP Secrets Workflow](TEST_SFTP_SECRETS.md)** - Validate configuration before deployment
- **[GitHub Secrets Setup](../GITHUB_SECRETS_SETUP.md)** - Secret configuration guide
- **[Development Guide](../DEVELOPMENT.md)** - Local development workflow
- **[Testing Guide](../TESTING_GUIDE.md)** - Testing infrastructure

---

**Document Version**: 1.0  
**Last Updated**: December 14, 2025  
**Workflow Version**: 1.0.0  
**Maintainer**: SOMA Development Team
