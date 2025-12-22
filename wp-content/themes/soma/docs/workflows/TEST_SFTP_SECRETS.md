# Test SFTP Secrets Workflow

**Workflow File**: `.github/workflows/test-sftp-secrets.yml`  
**Purpose**: Validate GitHub Secrets and SFTP connectivity before production deployment  
**Trigger**: Manual dispatch only

---

## Overview

This workflow validates that all GitHub Secrets are configured correctly and that SFTP connectivity to the production server works as expected. Use this before creating production deployments to avoid deployment failures.

### Test Levels

1. **connection** - Basic SFTP connection test
2. **upload** - Tests file upload capability
3. **full** - Complete validation including domain check

---

## When to Use

✅ **Use this workflow when:**
- Setting up GitHub Secrets for the first time
- After rotating SSH keys
- Troubleshooting deployment failures
- Before creating production releases
- After changing server configuration

❌ **Don't use for:**
- Regular deployments (use release workflow instead)
- Automated testing (it's manual dispatch only)

---

## Usage

### Via GitHub UI

1. Navigate to **Actions** tab in GitHub
2. Select **Test SFTP Connection** workflow
3. Click **Run workflow** button
4. Select test type:
   - `connection` - Quick connectivity test (~1 min)
   - `upload` - Test file upload (~2 min)
   - `full` - Complete validation (~3 min)
5. Click **Run workflow**
6. Monitor execution in Actions tab

### Via GitHub CLI

```bash
# Quick connection test
gh workflow run test-sftp-secrets.yml -f test_type=connection

# Full validation
gh workflow run test-sftp-secrets.yml -f test_type=full

# Watch execution
gh run watch
```

---

## Test Stages

### Stage 1: SSH Key Setup

**What it does:**
- Decodes Base64-encoded SSH key from `SFTP_SSH_KEY` secret
- Saves key to `~/.ssh/soma_rsa`
- Sets correct permissions (600)
- Validates key format
- Configures SSH config for connection

**Success criteria:**
- ✅ Key contains valid PEM format
- ✅ File size > 0 bytes
- ✅ Permissions set to 600
- ✅ Key header detected ("BEGIN RSA PRIVATE KEY" or "BEGIN OPENSSH PRIVATE KEY")

**Failure scenarios:**
- ❌ Secret is empty or not Base64-encoded
- ❌ Invalid key format
- ❌ Base64 decoding fails

---

### Stage 2: Basic SFTP Connection

**What it does:**
- Tests SFTP connection using credentials from secrets
- Executes `pwd` command to verify access
- Validates authentication with SSH key

**Success criteria:**
- ✅ Connection established without password prompt
- ✅ Server accepts SSH key authentication
- ✅ pwd command returns current directory

**Failure scenarios:**
- ❌ Permission denied (publickey)
- ❌ Host unreachable
- ❌ Wrong username or IP
- ❌ SSH key not authorized on server

---

### Stage 3: Theme Directory Verification

**What it does:**
- Navigates to `/public_html/wp-content/themes`
- Lists contents of `soma` directory
- Verifies theme files exist

**Success criteria:**
- ✅ Directory exists
- ✅ Contains WordPress theme files (style.css, functions.php)
- ✅ Read permissions granted

**Failure scenarios:**
- ❌ Directory not found
- ❌ Permission denied
- ❌ Empty directory

---

### Stage 4: Upload Test (Optional)

**Trigger**: Only when `test_type=upload` or `test_type=full`

**What it does:**
- Creates test file with timestamp
- Uploads file via SFTP
- Verifies upload success
- Deletes test file from server

**Success criteria:**
- ✅ File uploaded successfully
- ✅ File appears in directory listing
- ✅ File can be deleted

**Failure scenarios:**
- ❌ Upload permission denied
- ❌ Insufficient disk space
- ❌ Connection timeout during upload

---

### Stage 5: Site Domain Check (Optional)

**Trigger**: Only when `test_type=full`

**What it does:**
- Sends HTTP(S) request to site domain
- Verifies site is reachable
- Checks response headers

**Success criteria:**
- ✅ HTTP 200 or 301/302 response
- ✅ Site reachable via HTTPS

**Failure scenarios:**
- ⚠️ Site not reachable (doesn't fail workflow)
- ⚠️ DNS not configured
- ⚠️ SSL certificate issues

---

## Expected Output

### Successful Run

```
━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
🔐 Configurando SSH Key desde GitHub Secrets...
━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
✅ SSH Key decodificada correctamente
📊 Tamaño: 1679 bytes
🔑 Tipo: -----BEGIN RSA PRIVATE KEY-----

━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
🔌 TEST 1: Conexión SFTP Básica
━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
🌐 Host: 50.62.137.55
👤 User: bdu55vogd8zc

Connected to 50.62.137.55.
/home/bdu55vogd8zc
✅ SFTP Connection: SUCCESS

━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
📁 TEST 2: Verificar Directorio del Tema
━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━

drwxr-xr-x    8 bdu55vogd8zc bdu55vogd8zc     4096 Dec 14 18:30 soma
-rw-r--r--    1 bdu55vogd8zc bdu55vogd8zc     2048 Dec 14 18:30 style.css
-rw-r--r--    1 bdu55vogd8zc bdu55vogd8zc     5632 Dec 14 18:30 functions.php

✅ Theme directory accessible

━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
📦 TEST 3: Probar Capacidad de Upload
━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━

📤 Subiendo archivo de prueba...
Uploading test-upload.txt to /public_html/wp-content/themes/test-upload.txt
test-upload.txt                          100%   32     1.5KB/s   00:00
-rw-r--r--    1 bdu55vogd8zc bdu55vogd8zc       32 Dec 14 23:45 test-upload.txt

✅ Upload test successful

════════════════════════════════════════════════════════════
📊 RESUMEN DE PRUEBAS
════════════════════════════════════════════════════════════

✅ Tests completados exitosamente

🔐 Secrets validados:
   • SFTP_SSH_KEY: ✓ (formato válido, tamaño correcto)
   • SFTP_HOST: ✓ (50.62.137.55)
   • SFTP_USER: ✓ (bdu55vogd8zc)
   • SITE_DOMAIN: ✓ (fibrasoma.group)

🎯 Siguiente paso: Crear un release para deployment real

   git tag -a v3.0.1 -m 'Test deployment'
   git push origin v3.0.1

════════════════════════════════════════════════════════════
```

---

## Troubleshooting

### Error: "SSH key validation failed"

**Symptom:**
```
❌ ERROR: SSH Key no tiene formato válido
```

**Possible causes:**
1. Secret value is not Base64-encoded
2. Wrong secret value copied
3. Corrupted during copy/paste

**Solution:**
```bash
# Re-encode SSH key
base64 -i ~/.ssh/soma_rsa | tr -d '\n' > key.base64
cat key.base64

# Copy output
# Update GitHub Secret: SFTP_SSH_KEY
```

**Verify format:**
- Must start with: `LS0tLS1CRUdJTi...`
- Must be single line (no newlines)
- Must decode to valid PEM format

---

### Error: "Permission denied (publickey)"

**Symptom:**
```
bdu55vogd8zc@50.62.137.55: Permission denied (publickey).
```

**Possible causes:**
1. SSH key not authorized in cPanel
2. Wrong username
3. Key mismatch

**Solution:**

**1. Verify key in cPanel:**
- Login to cPanel
- SSH Access → Manage SSH Keys
- Check `soma_rsa` is **Authorized** (not just imported)

**2. Verify username:**
```bash
# Check SFTP_USER secret matches cPanel username
gh secret list | grep SFTP_USER
```

**3. Test manually:**
```bash
# Test with local key
sftp -i ~/.ssh/soma_rsa bdu55vogd8zc@50.62.137.55
# Should connect without password
```

---

### Error: "Connection refused" or "Connection timed out"

**Symptom:**
```
ssh: connect to host 50.62.137.55 port 22: Connection refused
```

**Possible causes:**
1. Wrong IP address
2. Firewall blocking connection
3. Server SSH port changed

**Solution:**

**1. Verify IP:**
```bash
# Check SFTP_HOST secret
gh secret list | grep SFTP_HOST
```

**2. Test connectivity:**
```bash
# Ping server
ping 50.62.137.55

# Check SSH port
nc -zv 50.62.137.55 22
```

**3. Verify in cPanel:**
- SSH Access → SSH Access settings
- Confirm SSH is enabled

---

### Warning: "Theme directory not found"

**Symptom:**
```
⚠️  No se encontró el tema o está vacío
```

**Possible causes:**
1. Theme not installed yet
2. Wrong path
3. Permissions issue

**Solution:**

**Check actual path:**
```bash
sftp bdu55vogd8zc@50.62.137.55
pwd
ls
cd public_html/wp-content/themes
ls
```

**Common paths:**
- `public_html/wp-content/themes/` (most common)
- `www/wp-content/themes/`
- `htdocs/wp-content/themes/`

---

### Error: "Upload failed - Insufficient permissions"

**Symptom:**
```
put test-upload.txt: Permission denied
```

**Possible causes:**
1. Directory not writable
2. Disk quota exceeded
3. cPanel user permissions

**Solution:**

**Check permissions:**
```bash
sftp bdu55vogd8zc@50.62.137.55
cd public_html/wp-content/themes
ls -la
# Look for drwxr-xr-x (should be writable)
```

**Check disk quota:**
- cPanel → Files → Disk Usage
- Ensure space available

---

## Integration with CI/CD

### Before First Deployment

```bash
# Run full test
gh workflow run test-sftp-secrets.yml -f test_type=full

# Wait for completion
gh run watch

# If successful, proceed with deployment
git tag -a v3.0.1 -m "First deployment"
git push origin v3.0.1
```

### After SSH Key Rotation

```bash
# Update secret
gh secret set SFTP_SSH_KEY < key.base64

# Test immediately
gh workflow run test-sftp-secrets.yml -f test_type=connection

# If successful, authorize in cPanel
# Then test upload
gh workflow run test-sftp-secrets.yml -f test_type=upload
```

### Troubleshooting Failed Deployments

```bash
# Quick diagnostic
gh workflow run test-sftp-secrets.yml -f test_type=full

# Review logs
gh run list --workflow=test-sftp-secrets.yml --limit 1
gh run view <run-id> --log
```

---

## Security Considerations

### Secret Protection

- ✅ Secrets never exposed in logs (GitHub masks them automatically)
- ✅ SSH key permissions set to 600
- ✅ Temporary files cleaned up after execution
- ✅ StrictHostKeyChecking disabled only for testing

### Best Practices

1. **Run in private repository only**
2. **Don't share workflow logs publicly** (may contain paths)
3. **Rotate SSH keys regularly** (every 6-12 months)
4. **Use separate keys** for testing and production (optional)
5. **Monitor workflow executions** for unauthorized runs

---

## Related Documentation

- **[Release and Deploy Workflow](RELEASE_AND_DEPLOY.md)** - Production deployment workflow
- **[Development Guide](../DEVELOPMENT.md)** - Local development workflow

---

**Document Version**: 1.0  
**Last Updated**: December 14, 2025  
**Workflow Version**: 1.0.0  
**Maintainer**: Miguel Colmenares
