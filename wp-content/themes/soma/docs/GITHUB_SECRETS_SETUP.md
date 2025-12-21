# GitHub Secrets Configuration for SOMA CI/CD

**Version**: 1.0  
**Last Updated**: December 14, 2025  
**Security Level**: INTERNAL ONLY

---

## Overview

This document explains how to configure the required GitHub Secrets for the SOMA CI/CD pipeline. **Never commit actual secret values to the repository.**

---

## Required Secrets

The following secrets must be configured in your GitHub repository:

| Secret Name | Type | Description | Example Format |
|-------------|------|-------------|----------------|
| `SFTP_SSH_KEY` | Base64 String | SSH private key encoded in Base64 | `LS0tLS1CRUdJTi...` |
| `SFTP_HOST` | IP Address | Production server IP address | `XXX.XXX.XXX.XXX` |
| `SFTP_USER` | String | cPanel/SFTP username | `your_cpanel_user` |
| `SITE_DOMAIN` | Domain | Production website domain | `yoursite.com` |

---

## Setup Instructions

### Step 1: Access GitHub Settings

1. Navigate to your repository: `https://github.com/YOUR_ORG/YOUR_REPO`
2. Click **Settings** tab
3. In left sidebar, click **Secrets and variables** → **Actions**

### Step 2: Add Each Secret

For each required secret:

1. Click **New repository secret**
2. Enter the **Name** (e.g., `SFTP_SSH_KEY`)
3. Paste the **Secret value**
4. Click **Add secret**

---

## Secret Configuration Details

### 1. SFTP_SSH_KEY

**Purpose**: SSH private key for SFTP authentication

**How to generate:**

```bash
# Generate Base64-encoded SSH key (no newlines)
base64 -i ~/.ssh/your_key | tr -d '\n' > key.base64

# Copy the output
cat key.base64

# Delete temporary file
rm key.base64
```

**Format validation:**
- Must start with `LS0tLS1CRUdJTi` (Base64 for "-----BEGIN")
- Must be single line (no line breaks)
- Decodes to valid PEM format

**Security notes:**
- ⚠️ **Never commit this value to repository**
- ✅ Store securely in password manager
- ✅ Rotate every 6-12 months
- ✅ Use different keys for staging/production

---

### 2. SFTP_HOST

**Purpose**: Production server IP address

**How to obtain:**
- From your hosting provider control panel
- From server documentation
- Contact your system administrator

**Format**: IPv4 address (e.g., `123.456.789.012`)

**Validation:**
```bash
# Test connectivity
ping YOUR_SERVER_IP

# Test SSH port
nc -zv YOUR_SERVER_IP 22
```

---

### 3. SFTP_USER

**Purpose**: cPanel or SFTP username for authentication

**How to obtain:**
- From cPanel account information
- From hosting provider
- From server administrator

**Format**: Alphanumeric string (varies by provider)

**Validation:**
```bash
# Test SFTP connection
sftp USERNAME@SERVER_IP
# Should connect (may ask for password if key not set up)
```

---

### 4. SITE_DOMAIN

**Purpose**: Production website domain (for URL generation and verification)

**How to obtain:**
- Your registered domain name
- From hosting provider

**Format**: Domain without protocol (e.g., `example.com`, not `https://example.com`)

**Validation:**
```bash
# Check DNS resolution
nslookup yourdomain.com

# Test HTTP(S) connectivity
curl -I https://yourdomain.com
```

---

## Environment Configuration (Optional)

For enhanced security, create a **production environment**:

### Create Production Environment

1. **Settings** → **Environments** → **New environment**
2. Name: `production`
3. Click **Configure environment**

### Protection Rules

Enable these protection rules:

- ✅ **Required reviewers** - Require approval before deployment
- ✅ **Wait timer** - Add 5-minute delay before deployment
- ✅ **Deployment branches** - Restrict to tags matching `v*`

### Environment Secrets

Move deployment secrets to production environment:

1. In production environment, click **Add secret**
2. Add same secrets (SFTP_SSH_KEY, SFTP_HOST, SFTP_USER, SITE_DOMAIN)
3. Environment secrets override repository secrets

**Benefits:**
- Additional approval layer for production deployments
- Separate staging/production credentials
- Audit trail for production changes

---

## Verification

### Check Secrets Are Configured

```bash
# List configured secrets (via GitHub CLI)
gh secret list | cat

# Expected output:
# SFTP_SSH_KEY    Updated X minutes ago
# SFTP_HOST       Updated X minutes ago
# SFTP_USER       Updated X minutes ago
# SITE_DOMAIN     Updated X minutes ago
```

### Test Workflow

Before production deployment, run the test workflow:

```bash
# Run SFTP connection test
gh workflow run test-sftp-secrets.yml -f test_type=connection

# Watch execution
gh run watch

# Review results
gh run list --workflow=test-sftp-secrets.yml --limit 1 | cat
```

**Test levels:**
- `connection` - Basic SFTP connectivity (~1 min)
- `upload` - File upload capability (~2 min)
- `full` - Complete validation including domain check (~3 min)

---

## Security Best Practices

### SSH Key Management

- ✅ Generate unique keys for each environment
- ✅ Use passphrase-protected keys locally
- ✅ Store backup of keys securely (encrypted)
- ✅ Rotate keys regularly (every 6-12 months)
- ❌ Never commit keys to repository
- ❌ Never share keys via email or chat

### Secret Rotation

**When to rotate:**
- Every 6-12 months (scheduled)
- After team member departure
- After suspected compromise
- After hosting provider migration

**How to rotate:**

```bash
# 1. Generate new key
ssh-keygen -t rsa -b 4096 -C "soma-deploy" -f ~/.ssh/soma_new_rsa

# 2. Authorize in cPanel (add public key)

# 3. Test new key
sftp -i ~/.ssh/soma_new_rsa USERNAME@SERVER_IP

# 4. Encode for GitHub
base64 -i ~/.ssh/soma_new_rsa | tr -d '\n' > new_key.base64

# 5. Update GitHub Secret
gh secret set SFTP_SSH_KEY < new_key.base64

# 6. Test deployment workflow
gh workflow run test-sftp-secrets.yml -f test_type=connection

# 7. If successful, deauthorize old key in cPanel
# 8. Delete old key files securely
shred -u ~/.ssh/soma_rsa ~/.ssh/soma_rsa.pub
rm new_key.base64
```

### Access Control

- ✅ Limit GitHub repository access to authorized team members
- ✅ Enable 2FA for all GitHub accounts
- ✅ Use organization-level secrets when possible
- ✅ Audit secret access regularly
- ✅ Monitor workflow execution logs

---

## Troubleshooting

### Error: "Secret not found"

**Symptom:** Workflow fails with "Secret SFTP_SSH_KEY not found"

**Solution:**
1. Verify secret name exactly matches (case-sensitive)
2. Check secret is in correct location (repository vs environment)
3. Ensure workflow has access to secrets

---

### Error: "Invalid Base64"

**Symptom:** "SSH key validation failed" or "Invalid Base64"

**Solution:**

```bash
# Re-encode without newlines
base64 -i ~/.ssh/your_key | tr -d '\n' > key.base64

# Verify it's valid Base64
cat key.base64 | base64 -d | head -1
# Should output: -----BEGIN RSA PRIVATE KEY-----

# Update secret
gh secret set SFTP_SSH_KEY < key.base64
rm key.base64
```

---

### Error: "Permission denied (publickey)"

**Symptom:** SFTP connection fails with permission denied

**Possible causes:**
1. SSH key not authorized on server
2. Wrong username
3. Incorrect key format
4. Key mismatch

**Solution:**

**Verify key in cPanel:**
1. Login to cPanel
2. **SSH Access** → **Manage SSH Keys**
3. Find your key
4. Click **Authorize** (if not already authorized)
5. Verify "Authorized" status

**Verify username:**
```bash
# Check SFTP_USER matches cPanel username
gh secret list | grep SFTP_USER | cat
```

**Test manually:**
```bash
# Extract key from secret (for local testing only)
gh secret list | cat  # Verify secret exists

# Test with your local key
sftp -i ~/.ssh/your_key USERNAME@SERVER_IP
# Should connect without password
```

---

### Error: "Connection refused"

**Symptom:** Cannot connect to SFTP_HOST

**Possible causes:**
1. Wrong IP address
2. Firewall blocking connection
3. Server offline
4. SSH port changed

**Solution:**

```bash
# Verify IP
ping YOUR_SERVER_IP

# Check SSH port
nc -zv YOUR_SERVER_IP 22

# Test from GitHub Actions IP range
# GitHub uses dynamic IPs - may need to whitelist ranges
```

---

## Additional Resources

### Related Documentation

- **[Workflows Overview](WORKFLOWS.md)** - All GitHub Actions workflows
- **[Test SFTP Secrets](workflows/TEST_SFTP_SECRETS.md)** - Testing workflow documentation
- **[Release and Deploy](workflows/RELEASE_AND_DEPLOY.md)** - Production deployment workflow

### External Resources

- [GitHub Encrypted Secrets](https://docs.github.com/en/actions/security-guides/encrypted-secrets)
- [GitHub Environments](https://docs.github.com/en/actions/deployment/targeting-different-environments)
- [SSH Key Authentication](https://docs.github.com/en/authentication/connecting-to-github-with-ssh)

---

## Support

### Need Help?

1. **Test workflow first**: `gh workflow run test-sftp-secrets.yml -f test_type=full`
2. **Check workflow logs**: Review failure details in Actions tab
3. **Verify all secrets**: Ensure all 4 required secrets are configured
4. **Contact administrator**: For server access issues

### Security Concerns?

If you suspect a secret has been compromised:

1. ⚠️ **Immediately rotate the secret**
2. 🔍 **Review workflow execution logs** for unauthorized access
3. 🔒 **Update cPanel authorized keys**
4. 📧 **Notify team** of security incident

---

**Document Version**: 1.0  
**Last Updated**: December 14, 2025  
**Maintainer**: Miguel Colmenares  
**Security Classification**: INTERNAL ONLY - DO NOT SHARE PUBLICLY

---

## ⚠️ IMPORTANT SECURITY NOTICE

**This document contains instructions for configuring secrets.**  
**NEVER include actual secret values in this file.**  
**Keep actual credentials in a secure password manager.**  
**Rotate secrets regularly (every 6-12 months).**
