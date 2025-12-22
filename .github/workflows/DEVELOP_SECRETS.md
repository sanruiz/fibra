# SOMA Development Environment - GitHub Secrets Configuration

**Document Version**: 1.0  
**Last Updated**: December 20, 2025  
**Purpose**: Configuration guide for development server deployment

---

## 📋 Overview

The development environment uses SSH with password authentication to deploy the SOMA theme automatically when changes are pushed to the `develop` branch.

**Workflow File**: `.github/workflows/develop-deploy.yml`  
**Secrets Type**: Repository secrets (with `DEV_` prefix)  
**Deployment Method**: SSH with password authentication (sshpass)

---

## 🔐 Required GitHub Secrets

Configure these secrets in your GitHub repository for the development environment deployment to work.

**Location**: GitHub → Repository → Settings → Secrets and variables → Actions → **Repository secrets**

> **Note**: We use repository secrets with `DEV_` prefix instead of environment secrets. This is simpler and works well since the workflow only triggers on the `develop` branch.

### 1. DEV_SSH_HOST
- **Description**: IP address or hostname of the development server
- **Type**: String
- **Example**: `192.168.1.100` or `dev.fibrasoma.com`
- **Required**: Yes

### 2. DEV_SSH_PORT
- **Description**: SSH port number
- **Type**: Number (stored as string)
- **Example**: `22` (default) or custom port like `2222`
- **Required**: Yes
- **Default**: `22` if using standard SSH port

### 3. DEV_SSH_USER
- **Description**: SSH username for authentication
- **Type**: String
- **Example**: `ubuntu`, `root`, or custom user
- **Required**: Yes

### 4. DEV_SSH_PASSWORD
- **Description**: SSH password for authentication
- **Type**: String (encrypted by GitHub)
- **Example**: Your server password
- **Required**: Yes
- **Security**: Never commit this value to code. GitHub encrypts secrets.

---

## 🛠️ How to Configure Secrets

### Step 1: Navigate to Repository Secrets

1. Go to **GitHub Repository** → **Settings**
2. Navigate to **Secrets and variables** → **Actions** (left sidebar)
3. Click on **Repository secrets** tab

### Step 2: Add Each Secret

For each secret listed above:

1. Click **New repository secret**
2. Enter **Name** (e.g., `DEV_SSH_HOST`)
3. Enter **Value** (e.g., `192.168.1.100`)
4. Click **Add secret**

**Repeat for all 4 secrets**:
- `DEV_SSH_HOST`
- `DEV_SSH_PORT`
- `DEV_SSH_USER`
- `DEV_SSH_PASSWORD`

---

## 📝 Example Configuration

**Scenario**: Ubuntu server with custom SSH port

```
Secret Name: DEV_SSH_HOST
Value: 192.168.1.100

Secret Name: DEV_SSH_PORT
Value: 2222

Secret Name: DEV_SSH_USER
Value: ubuntu

Secret Name: DEV_SSH_PASSWORD
Value: MySecurePassword123!
```

---

## 🔍 Verification

### Test SSH Connection Locally

Before configuring GitHub secrets, test the SSH connection from your local machine:

```bash
# Test with password authentication
ssh -p PORT USER@HOST

# Example:
ssh -p 2222 ubuntu@192.168.1.100

# You should be prompted for the password
# After entering password, you should see the server shell
```

**If connection fails**:
- ✅ Check firewall allows SSH on the specified port
- ✅ Verify SSH service is running: `sudo systemctl status ssh`
- ✅ Confirm username and password are correct
- ✅ Check IP address is reachable: `ping HOST`

---

## 🚀 Workflow Trigger

Once secrets are configured, the workflow automatically triggers when:

1. **Push to develop branch**:
   ```bash
   git checkout develop
   git push origin develop
   ```

2. **Manual dispatch** (optional):
   - GitHub → Actions → "SOMA Develop - Auto Deploy" → Run workflow

---

## 📂 Server Directory Structure

The workflow expects the following directory structure on the development server:

```
/home/ubuntu/              # Or your user's home directory
├── wp-content/
│   └── themes/
│       ├── soma/          # Current theme (backed up before deployment)
│       └── soma-backup-*  # Automatic backups
```

**WordPress Root**: The workflow assumes WordPress is in the user's home directory. If different, you'll need to adjust the paths in the workflow file.

### Adjusting Paths

If your WordPress installation is in a different location (e.g., `/var/www/html`):

**Edit `.github/workflows/develop-deploy.yml`**:

```yaml
# Find these lines in the deploy-develop job:
"cd wp-content/themes && ..."

# Change to your actual path:
"cd /var/www/html/wp-content/themes && ..."
```

---

## 🔄 Deployment Process

When you push to the `develop` branch, the workflow:

1. ✅ **Runs quality gates** (PHPCS, PHPStan, PHPUnit)
2. ✅ **Builds production assets** (CSS, JS)
3. ✅ **Creates deployment package** (ZIP file)
4. ✅ **Connects to server via SSH**
5. ✅ **Creates backup** of existing theme
6. ✅ **Uploads new theme ZIP**
7. ✅ **Extracts theme automatically**
8. ✅ **Sets correct permissions** (755 for folders, 644 for files)

**Total time**: ~3-5 minutes

---

## 🛡️ Security Considerations

### Password Authentication

The workflow uses `sshpass` for password-based SSH authentication. While convenient for development, consider these security practices:

**✅ DO:**
- Use a **strong, unique password** for the development server
- Store password **only in GitHub Secrets** (never in code)
- Use a **separate user account** for deployments (not root)
- Restrict SSH access with **firewall rules**
- Keep the development server **on a private network** when possible

**⚠️ RECOMMENDED for Production:**
- Use **SSH key authentication** (not passwords)
- Implement **two-factor authentication** (2FA)
- Use **certificate-based authentication**

### Firewall Configuration

Ensure your firewall allows SSH connections:

```bash
# Ubuntu/Debian with UFW
sudo ufw allow 22/tcp    # Or your custom port
sudo ufw enable

# Check status
sudo ufw status
```

---

## 🔧 Troubleshooting

### Issue: Workflow fails at "Test SSH connection"

**Error**: `SSH connection failed`

**Solutions**:

1. **Verify secrets are correct**:
   - GitHub → Settings → Environments → develop → Secrets
   - Confirm all 4 secrets are set

2. **Test connection manually**:
   ```bash
   ssh -p PORT USER@HOST
   ```

3. **Check server SSH service**:
   ```bash
   sudo systemctl status ssh
   sudo systemctl restart ssh
   ```

4. **Verify firewall rules**:
   ```bash
   sudo ufw status
   # Should show: PORT/tcp ALLOW
   ```

---

### Issue: "Permission denied" errors on server

**Error**: Cannot create directories or extract files

**Solution**: Ensure the SSH user has write permissions:

```bash
# On the server, check ownership
ls -la wp-content/themes/

# Fix ownership if needed
sudo chown -R ubuntu:ubuntu wp-content/

# Or your user:
sudo chown -R $USER:$USER wp-content/
```

---

### Issue: Theme not appearing in WordPress

**Error**: Theme deployed but not visible in WordPress admin

**Solutions**:

1. **Check directory structure**:
   ```bash
   # Should be:
   wp-content/themes/soma/style.css
   
   # NOT:
   wp-content/themes/soma/soma/style.css
   ```

2. **Verify permissions**:
   ```bash
   # Folders: 755
   find wp-content/themes/soma -type d -exec chmod 755 {} \;
   
   # Files: 644
   find wp-content/themes/soma -type f -exec chmod 644 {} \;
   ```

3. **Clear WordPress cache**:
   - WordPress Admin → Tools → Clear cache (if plugin installed)
   - Or manually: Delete `wp-content/cache/*`

---

### Issue: Assets not loading (CSS/JS 404 errors)

**Error**: Styles or scripts not loading after deployment

**Solution**: Check file permissions and paths:

```bash
# Verify files exist
ls -la wp-content/themes/soma/css/main.bundle.css
ls -la wp-content/themes/soma/js/main.bundle.js

# Should show: -rw-r--r-- (644 permissions)

# If missing, the build step may have failed
# Check workflow logs in GitHub Actions
```

---

## 📊 Monitoring Deployments

### View Deployment Logs

1. GitHub → **Actions** tab
2. Select **"SOMA Develop - Auto Deploy"** workflow
3. Click on the latest run
4. View logs for each job:
   - 🔍 Code Quality
   - 🧪 PHP Tests
   - 🎨 Frontend Build
   - 📦 Build Package
   - 🚀 Deploy Develop

### Deployment Summary

After each successful deployment, check the **Summary** tab in the workflow run:

- ✅ Version deployed
- ✅ Steps completed
- ✅ Next steps for testing

---

## 🔄 Development Workflow

### Recommended Git Flow

```bash
# 1. Work on feature branch
git checkout -b feature/new-feature

# 2. Commit changes
git add .
git commit -m "feat: Add new feature"

# 3. Push to remote
git push origin feature/new-feature

# 4. Create PR to week-N branch
gh pr create --base week-2 --title "feat: New feature" | cat

# 5. After PR approved, merge to week-N
gh pr merge NUMBER --squash | cat

# 6. Merge week-N to develop for testing
git checkout develop
git pull origin develop
git merge week-2
git push origin develop  # Triggers automatic deployment

# 7. Test on development server
# Visit: http://dev.fibrasoma.com

# 8. If tests pass, merge week-N to main for production
git checkout main
git pull origin main
git merge week-2
git push origin main

# 9. Create release tag (triggers production deployment)
git tag -a v3.2.0 -m "Release v3.2.0"
git push origin v3.2.0
```

---

## 🌐 Environment Comparison

| Feature | **Develop** | **Production** |
|---------|-------------|----------------|
| **Branch** | `develop` | `main` |
| **Workflow** | `develop-deploy.yml` | `ci-cd.yml` |
| **Trigger** | Push to develop | Tag push (v*) |
| **Authentication** | SSH + password | SFTP + SSH key |
| **Requires Release** | ❌ No | ✅ Yes (GitHub Release) |
| **Deployment** | Automatic | Automatic |
| **Extraction** | ✅ Automatic | ⚠️ Manual (cPanel) |
| **Purpose** | Testing | Live site |
| **URL** | dev.fibrasoma.com | fibrasoma.com |

---

## 📚 Additional Resources

### Related Documentation

- **CI/CD Guide**: `docs/workflows/CI_CD.md`
- **GitHub Workflow**: `.github/instructions/github-workflow.instructions.md`
- **Production Deployment**: `.github/workflows/ci-cd.yml`

### GitHub Documentation

- [GitHub Environments](https://docs.github.com/en/actions/deployment/targeting-different-environments/using-environments-for-deployment)
- [GitHub Secrets](https://docs.github.com/en/actions/security-guides/encrypted-secrets)
- [GitHub Actions Workflows](https://docs.github.com/en/actions/using-workflows)

---

## ✅ Quick Reference

### Required Secrets Checklist

Before first deployment, ensure these are configured:

- [ ] `DEV_SSH_HOST` - Server IP or hostname
- [ ] `DEV_SSH_PORT` - SSH port number
- [ ] `DEV_SSH_USER` - SSH username
- [ ] `DEV_SSH_PASSWORD` - SSH password
- [ ] Environment `develop` created in GitHub
- [ ] All secrets added to `develop` environment (not repository level)

### Test Command

```bash
# Test connection with your values:
ssh -p <DEV_SSH_PORT> <DEV_SSH_USER>@<DEV_SSH_HOST>
```

**If this works manually, the workflow should work too.**

---

**Document Version**: 1.0  
**Last Updated**: December 20, 2025  
**Maintained By**: Miguel Colmenares  
**Repository**: https://github.com/sanruiz/fibra

