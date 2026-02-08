---
description: GitHub workflow standards including branch management, PRs, issues, and releases
name: GitHub Workflow
applyTo: "**"
---

# GitHub Workflow Instructions for FibraSOMA Project

**Applies to**: All GitHub operations, branch management, issues, PRs, releases, and deployments  
**Last Updated**: February 7, 2026  
**Project**: FibraSOMA Website (Post-Sprint Development)

---

## 🔒 Git Hooks Setup (REQUIRED)

### Installation

**CRITICAL**: Install Git hooks immediately after cloning the repository to enforce branch protection.

```bash
# Run once after cloning
chmod +x install-hooks.sh
./install-hooks.sh
```

### What Git Hooks Do

The pre-commit hook enforces the GitFlow workflow by:

- ✅ **Blocking direct commits** to protected branches (`main`, `develop`)
- ✅ **Enforcing PR workflow** - All work must be done on feature/fix/hotfix branches
- ✅ **Showing helpful messages** - Displays correct workflow if you try to commit to wrong branch
- ✅ **Preventing accidents** - Catches mistakes before they reach the remote repository

### Protected Branches

The hook blocks commits to:

- `main` - Production branch (only via PR from develop or hotfix/*)
- `develop` - Development branch (only via PR from feature/fix)

### Allowed Branches

You can commit directly to:

- `feature/*` - New features
- `fix/*` - Bug fixes
- `hotfix/*` - Emergency production fixes
- `chore/*` - Maintenance tasks
- `refactor/*` - Code refactoring
- `release/*` - Release preparation

### Example: Hook in Action

```bash
# ❌ Trying to commit to protected branch
$ git checkout main
$ git commit -m "feat: some change"

🚫 ════════════════════════════════════════════════════════════
   COMMIT BLOCKED: Cannot commit directly to 'main'
════════════════════════════════════════════════════════════

❌ The 'main' branch is protected and requires Pull Requests.

📋 Correct workflow:
   1. Create feature branch from develop:
      git checkout develop
      git checkout -b feature/your-feature

   2. Make changes and commit

   3. Create PR to develop:
      gh pr create --base develop --title 'feat: Your feature' | cat
```

### Troubleshooting

**Hook not working?**
```bash
# Re-run installation
./install-hooks.sh

# Verify hook is executable
ls -la .git/hooks/pre-commit
# Should show: -rwxr-xr-x (executable)

# Test hook manually
.git/hooks/pre-commit
```

**Need to bypass hook temporarily?**
```bash
# NOT RECOMMENDED - Only for emergencies
git commit --no-verify -m "message"

# Better: Switch to correct branch
git checkout -b feature/fix
```

---

## 🌳 Branch Management

### Branch Naming Conventions

```
main                → Production branch (stable releases only)
develop             → Development branch (integration branch for features)
feature/description → New features (e.g., feature/hero-section)
fix/description     → Bug fixes (e.g., fix/navbar-mobile)
chore/description   → Maintenance tasks (e.g., chore/update-deps)
hotfix/description  → Emergency fixes (e.g., hotfix/security-patch)
release/vX.Y.Z      → Release preparation (e.g., release/v3.1.3)
```

### Branch Hierarchy

```
main (production - stable releases)
 ↑
 └── develop (integration branch)
      ↑
      ├── feature/new-feature
      ├── feature/other-feature
      ├── fix/bug-fix
      └── chore/maintenance
```

### CRITICAL Rules

**✅ DO:**
- Create feature branches from `develop`
- Merge feature branches back to `develop` via PR
- Merge `develop` to `main` only for releases
- Use `hotfix/*` for emergency production fixes (branch from `main`)

**❌ NEVER:**
- Commit directly to `main` or `develop`
- Create feature branches from `main` (except hotfixes)
- Skip the PR review process
- Merge without CI passing

### Branch Creation Workflow

```bash
# 1. Start new feature from develop
git checkout develop
git pull origin develop
git checkout -b feature/new-feature

# 2. Work and commit
git add .
git commit -m "feat: Add new feature"

# 3. ⚠️ MANDATORY: Run quality checks BEFORE pushing
cd wp-content/themes/soma
composer phpcs        # Must pass (0 errors)
composer phpstan      # Must pass (Level 6+)
composer test         # Must pass (all tests green)
npm run prod          # Must pass (if CSS/JS modified)

# 4. Push to remote (only after quality checks pass)
git push -u origin feature/new-feature

# 5. Create PR to develop
gh pr create --title "feat: Add new feature" \
  --base develop \
  --label "enhancement,frontend" | cat

# 6. After PR merge, delete branch (LOCAL + REMOTE)
git checkout develop
git pull origin develop
git branch -d feature/new-feature           # Delete local
git push origin --delete feature/new-feature # Delete remote
```

### Release Workflow

```bash
# 1. Ensure develop is ready for release
git checkout develop
git pull origin develop

# 2. Create release branch
git checkout -b release/v3.2.0

# 3. Update version files and CHANGELOG
# ... make version updates ...

# 4. Create PR to main
gh pr create --title "Release v3.2.0" \
  --base main \
  --body "Release v3.2.0 with new features and fixes" | cat

# 5. After merge, tag the release FROM main
git checkout main
git pull origin main
git tag -a v3.2.0 -m "Release v3.2.0"
git push origin v3.2.0

# 6. Merge main back to develop to sync
git checkout develop
git merge main
git push origin develop
```

---

## 🏷️ Issue Management

### Issue Creation Template

```bash
gh issue create \
  --title "feat: Add hero section to homepage" \
  --body "**Description:**
Implement hero section with:
- Background image support
- Headline + subtitle
- CTA button
- Responsive design

**Acceptance Criteria:**
- [ ] Desktop layout (1920px)
- [ ] Tablet layout (768px)
- [ ] Mobile layout (375px)
- [ ] ACF fields configured
- [ ] Elementor widget created

**Related:**
- Component: Home Page" \
  --label "enhancement,frontend,alta-prioridad" | cat
```

### Required Labels for Each Issue

**Type (Required):**
- `enhancement` - New features
- `bug` - Bug fixes
- `documentation` - Documentation updates
- `automation` - CI/CD, scripts, tooling
- `cicd` - Workflow changes
- `code-quality` - PHPCS, PHPStan, linting
- `testing` - Unit/integration tests
- `security` - Security issues

**Area (Recommended):**
- `frontend` - HTML, CSS, JavaScript
- `backend` - PHP, WordPress, database
- `contenido` - Content and copy
- `diseño` - Design and UI/UX
- `qa` - Quality Assurance
- `performance` - Performance optimization
- `seo` - SEO and positioning

**Component (Optional):**
- `header` - Header component
- `footer` - Footer component

**Priority (Recommended):**
- `alta-prioridad` - Critical, blocking
- `media-prioridad` - Important
- `baja-prioridad` - Nice to have

### Issue Lifecycle

```
1. Create issue → Assign labels
2. Create feature branch from develop
3. Work on branch
4. Create PR linking issue (#NUMBER)
5. Merge PR to develop
6. Issue auto-closes (or close manually)
```

**⚠️ IMPORTANT: Auto-Close Works on develop Branch**

GitHub auto-closes issues (via "Closes #N", "Fixes #N") when PRs are merged to `develop` (if it's the default branch) or `main`.

### Issue Commands

```bash
# List all open issues
gh issue list | cat

# View specific issue
gh issue view 42 | cat

# Close issue
gh issue close 42 --comment "Completed in PR #55" | cat

# Add labels to existing issue
gh issue edit 42 --add-label "testing,code-quality" | cat

# List all available labels
gh label list | cat

# Create new label if needed
gh label create "new-label" \
  --description "Description of purpose" \
  --color "0052CC" | cat
```

---

## 🔀 Pull Request Management

### PR Creation

```bash
# Standard PR to develop
gh pr create \
  --title "feat: Add hero section component" \
  --body "Implements hero section with ACF integration.

**Changes:**
- Created HeroSection.php partial
- Added ACF field group
- Implemented responsive styles
- Added Elementor widget

**Testing:**
- ✅ Unit tests passing
- ✅ PHPCS clean
- ✅ PHPStan Level 6
- ✅ Responsive on all devices

Closes #42" \
  --base develop \
  --label "enhancement,frontend" | cat

# Release PR (develop to main)
gh pr create \
  --title "Release v3.2.0" \
  --body "Release v3.2.0 with new features and fixes.

**Features Completed:**
- Hero section
- Business units grid
- Portfolio showcase
- News feed
- Navbar and footer

**Quality Gates:**
- ✅ 108 tests passing (355 assertions)
- ✅ PHPCS clean (WordPress standards)
- ✅ PHPStan Level 6 (0 critical errors)
- ✅ All PRs reviewed and merged" \
  --base main | cat
```

### PR Review Checklist

**🚨 CRITICAL: Run Quality Checks LOCALLY Before Creating PR**

```bash
# Navigate to theme directory
cd wp-content/themes/soma

# Run ALL quality checks (all must pass)
composer phpcs        # WordPress Coding Standards - 0 errors required
composer phpstan      # Static Analysis Level 6+ - 0 critical errors
composer test         # PHPUnit - all 108+ tests must pass
npm run prod          # Frontend build - must complete successfully
```

**⚠️ DO NOT CREATE PR IF ANY CHECK FAILS**  
Failing to run local checks wastes CI/CD resources and delays the team.

---

**Before Creating PR:**
- [ ] ✅ **PHPCS passes locally** (`composer phpcs` - 0 errors)
- [ ] ✅ **PHPStan passes locally** (`composer phpstan` - 0 critical errors)
- [ ] ✅ **All tests pass locally** (`composer test` - all green)
- [ ] ✅ **Frontend builds locally** (`npm run prod` - no errors)
- [ ] All commits follow conventional commits format
- [ ] Documentation updated if needed

**PR Description Must Include:**
- [ ] Clear description of changes
- [ ] List of specific changes
- [ ] Testing notes
- [ ] Issue references (Closes #N)

**🏷️ PR Labels (REQUIRED):**

Every PR **MUST** include appropriate labels via `--label` flag:

| Type | Required Labels | Examples |
|------|-----------------|----------|
| **Bug Fix** | `bug` + area | `bug,backend,elementor` |
| **Feature** | `enhancement` + area | `enhancement,frontend` |
| **Docs** | `documentation` | `documentation` |
| **Refactor** | `refactor` + area | `refactor,backend` |
| **Chore** | `chore` | `chore,dependencies` |

**Area labels:** `frontend`, `backend`, `elementor`, `api`, `testing`, `cicd`

```bash
# Example: Bug fix PR
gh pr create \
  --title "fix: Portfolio Elementor support" \
  --base develop \
  --label "bug,backend,elementor" | cat

# Example: Feature PR
gh pr create \
  --title "feat: Add Events widget" \
  --base develop \
  --label "enhancement,frontend,elementor" | cat
```

**⚠️ PRs without labels are harder to track and categorize for releases.**

**After PR Created:**
- [ ] CI workflow passes (quality-and-tests.yml)
- [ ] Code review by team member (if applicable)
- [ ] All comments addressed
- [ ] No merge conflicts
- [ ] **Labels assigned** (if not included in `gh pr create`)

### PR Commands

```bash
# List open PRs
gh pr list | cat

# View PR details
gh pr view 55 | cat

# View PR diff
gh pr diff 55 | cat

# Check PR status (CI checks)
gh pr checks 55 | cat

# Merge PR (squash recommended, auto-deletes remote branch)
gh pr merge 55 --squash --delete-branch | cat

# Close PR without merging
gh pr close 55 --comment "Not needed anymore" | cat
```

---

## 📋 Post-PR Merge Checklist (MANDATORY)

**After EVERY PR is merged, complete these steps:**

### 1. Clean Up Branches (Local + Remote)

```bash
# Switch to base branch and update
git checkout develop
git pull origin develop

# Delete local branch
git branch -d feature/your-feature

# Delete remote branch (if not auto-deleted by --delete-branch)
git push origin --delete feature/your-feature

# Prune stale remote-tracking branches
git fetch --prune
```

### 2. Update Related Issue(s)

```bash
# Step 1: Check if issue is still open
gh issue view ISSUE_NUMBER | cat

# Step 2: Mark tasks as completed in issue body (if applicable)
gh issue edit ISSUE_NUMBER --body "...updated body with [x] checkboxes..." | cat

# Step 3: Add closing comment with PR reference
gh issue comment ISSUE_NUMBER --body "✅ Completed in PR #XX." | cat

# Step 4: Close the issue (if not auto-closed)
gh issue close ISSUE_NUMBER --comment "Implemented and merged." | cat
```

### 3. Quick Post-Merge Script

```bash
# One-liner for post-merge cleanup (replace values)
BRANCH="feature/your-feature" && ISSUE=42 && PR=55 && \
git checkout develop && git pull && \
git branch -d $BRANCH && \
git push origin --delete $BRANCH 2>/dev/null; \
gh issue close $ISSUE --comment "✅ Completed in PR #$PR." | cat
```

### Post-Merge Checklist

- [ ] 🗑️ Local branch deleted (`git branch -d feature/...`)
- [ ] 🗑️ Remote branch deleted (`git push origin --delete feature/...`)
- [ ] ✅ Issue tasks marked as completed
- [ ] 💬 Closing comment added to issue with PR reference
- [ ] 🔒 Issue closed

---

## 🚀 Release & Deployment Flow

### 🚨 CRITICAL: Tags Must Be Created from `main` Only

**NEVER create tags from `develop` or `release/*` branches before merging to main.** This is the most common mistake that leads to orphaned tags.

**Why this matters:**
- We use **squash merge** to keep `main` history clean
- Squash merge creates a NEW commit in `main` (not the same commits from `develop`)
- If you create a tag on `develop` BEFORE merging, that tag points to a commit that will NEVER exist in `main`
- Result: "Orphaned tags" - tags pointing to commits not reachable from any branch

**Visual explanation:**
```
❌ WRONG ORDER (creates orphaned tag):

develop:  A---B---C  <-- tag v3.2.0 points here
              \
               \ (squash merge)
                \
main:    X---Y---Z---[ABCD]  <-- NEW commit, tag NOT here!

✅ CORRECT ORDER (tag in main history):

develop:  A---B---C
              \
               \ (squash merge)
                \
main:    X---Y---Z---[ABCD]  <-- tag v3.2.0 points here!
```

**The ONLY correct workflow:**
1. ✅ Merge `develop` → `main` (via PR with squash) 
2. ✅ Checkout `main` and pull latest
3. ✅ Create tag on `main`
4. ✅ Push tag

---

### Prerequisites Checklist

**Before Creating a Release:**
- [ ] CI workflow passing (quality-and-tests.yml)
- [ ] All tests passing (108 tests, 355 assertions)
- [ ] PHPCS clean (WordPress Coding Standards)
- [ ] PHPStan Level 6+ (0 critical errors)
- [ ] Frontend build successful
- [ ] Version updated in `wp-content/themes/soma/style.css`
- [ ] CHANGELOG.md updated with release notes
- [ ] All PRs merged to target branch
- [ ] Milestone closed (if applicable)

### Release Process (Step-by-Step)

#### Phase 1: Prepare Release

```bash
# 1. Checkout develop branch
git checkout develop
git pull origin develop

# 2. Create release branch
git checkout -b release/v3.2.0

# 3. Update version in style.css
# Change: Version: 3.1.0
# To:     Version: 3.2.0
```

#### Phase 2: Update CHANGELOG.md

**IMPORTANT: Handle [Unreleased] Section**

The CHANGELOG.md may contain an `[Unreleased]` section with changes that haven't been assigned a version yet. During release preparation:

1. **Find the `[Unreleased]` section** at the top of the changelog
2. **Replace `[Unreleased]` with the new version number and date**:
   - `## [Unreleased]` → `## [3.2.0] - 2026-02-07`
3. **If no `[Unreleased]` section exists**, create a new version entry
4. **Add a new empty `[Unreleased]` section** above the new version for future changes (optional)

**Example transformation:**

```markdown
# Before release preparation:
## [Unreleased]

### Added
- New feature X
- New feature Y

## [3.1.0] - 2026-01-15
...

# After release preparation (v3.2.0):
## [Unreleased]

## [3.2.0] - 2026-02-07

### Added
- New feature X
- New feature Y

## [3.1.0] - 2026-01-15
...
```

**Standard entry format:**

```markdown
## [3.2.0] - 2026-02-07

### Added
- New hero section component
- Business units grid

### Fixed
- Navbar mobile menu not closing
- Portfolio image overflow on mobile

### Changed
- Updated footer layout for better responsiveness
```

#### Phase 3: Commit and Push

```bash
# Commit version changes
git add wp-content/themes/soma/style.css
git add wp-content/themes/soma/CHANGELOG.md
git commit -m "chore: Prepare release v3.2.0"

# Push release branch
git push -u origin release/v3.2.0

# Wait for CI to pass
gh run watch
```

#### Phase 4: Create Pull Request

```bash
gh pr create \
  --title "Release v3.2.0" \
  --body "Release v3.2.0 with new features and improvements.

**Changes:**
See CHANGELOG.md for details.

**Quality Gates:**
- ✅ PHPCS clean
- ✅ PHPStan Level 6
- ✅ All tests passing
- ✅ Frontend build successful" \
  --base main | cat
```

#### Phase 5: Merge to Main and Tag

**🚨 CRITICAL ORDER: Merge to main FIRST, then create tag FROM main**

```bash
# After release PR is approved, merge to main
gh pr merge NUMBER --squash | cat

# 🚨 NOW checkout main and create the tag
git checkout main
git pull origin main

# Verify you're on the correct commit (the squash merge)
git log -1 --oneline
# Should show something like: "6bedd53 Release v3.2.0 (#XX)"

# Create annotated tag
git tag -a v3.2.0 -m "Release v3.2.0: New features and improvements"

# Push tag (THIS TRIGGERS release-and-deploy.yml)
git push origin v3.2.0

# Merge main back to develop to sync
git checkout develop
git merge main
git push origin develop
```

#### Phase 6: Monitor Release Workflow

```bash
# Watch workflow execution
gh run watch

# Or list recent runs
gh run list --workflow=release-and-deploy.yml --limit 5 | cat

# View specific run
gh run view RUN_ID | cat
```

### Release Workflow (Automatic)

Once the tag is pushed, **release-and-deploy.yml** executes:

**Job 1: ⏳ Wait for CI** (1-2 min)
- Verifies that quality-and-tests.yml passed for this commit
- If CI failed → **BLOCKS** release ❌
- If CI passed → Continues ✅

**Job 2: 📦 Build & Release** (3-4 min)
```
1. composer install --no-dev
2. npm install && npm run prod
3. Create ZIP (soma-v3.0.1.zip) excluding dev files
4. Extract release notes from CHANGELOG.md
5. Create GitHub Release
6. Upload ZIP as release asset
7. Upload build artifact
```

**Job 3: 🚀 Deploy to Production** (2-3 min)
```
1. Download release ZIP
2. Configure SFTP credentials
3. Create backup: soma → soma-backup-TIMESTAMP
4. Upload ZIP to server via SFTP
5. Upload extract.php script
6. Generate deployment instructions
```

### Post-Deployment

**Manual Steps Required:**

**Option A: Via cPanel File Manager**
```
1. Login to cPanel
2. Navigate to: public_html/wp-content/themes/
3. Extract soma-v3.0.1.zip
4. Verify extraction successful
5. Delete ZIP file
```

**Option B: Via extract.php Script**
```
1. Visit: https://yourdomain.com/wp-content/themes/extract.php
2. Script extracts ZIP automatically
3. Removes ZIP after extraction
4. Displays success message
```

**Verification:**
```
1. Visit site: https://yourdomain.com
2. Check WordPress Admin → Appearance → Themes
3. Verify version: 3.0.1
4. Test critical pages
5. Check logs: wp-content/uploads/soma-logs/soma.log
```

---

## 🔧 GitHub CLI Commands Reference

### CRITICAL: Always Use `| cat`

**MANDATORY**: Always append `| cat` to `gh` commands to prevent terminal pagination.

```bash
# ❌ BAD (will hang waiting for user input)
gh issue list
gh pr view 42

# ✅ GOOD (completes immediately)
gh issue list | cat
gh pr view 42 | cat
```

### Issue Commands

```bash
# Create issue
gh issue create --label "enhancement,frontend" | cat

# List issues
gh issue list | cat
gh issue list --label "bug,alta-prioridad" | cat
gh issue list --state all | cat

# View issue
gh issue view NUMBER | cat

# Edit issue
gh issue edit NUMBER --add-label "testing" | cat

# Close issue
gh issue close NUMBER | cat
gh issue close NUMBER --comment "Fixed in PR #55" | cat

# Reopen issue
gh issue reopen NUMBER | cat
```

### Pull Request Commands

```bash
# Create PR
gh pr create --title "..." --base develop | cat

# List PRs
gh pr list | cat
gh pr list --state all | cat
gh pr list --base develop | cat

# View PR
gh pr view NUMBER | cat
gh pr view NUMBER --web  # Open in browser

# Check PR status
gh pr checks NUMBER | cat

# View PR diff
gh pr diff NUMBER | cat

# Merge PR
gh pr merge NUMBER --squash | cat
gh pr merge NUMBER --squash --delete-branch | cat

# Close PR
gh pr close NUMBER | cat
```

### Release Commands

```bash
# List releases
gh release list | cat

# View release
gh release view TAG | cat

# ⚠️ NEVER create releases manually with gh release create
# Releases are ALWAYS created automatically by ci-cd.yml workflow
# when a tag is pushed. The workflow handles:
# - Building the release package
# - Generating release notes from CHANGELOG.md
# - Creating the GitHub release
# - Uploading artifacts
# - Deploying to production
#
# To create a release:
# 1. Update version in style.css
# 2. Update CHANGELOG.md
# 3. Commit changes
# 4. Create and push tag: git tag -a v3.0.1 -m "Release v3.0.1" && git push origin v3.0.1
# 5. Monitor workflow: gh run watch | cat

# Delete release (emergency only - requires re-tag)
gh release delete TAG | cat
```

### Workflow Commands

```bash
# List workflows
gh workflow list | cat

# List workflow runs
gh run list | cat
gh run list --workflow=quality-and-tests.yml | cat
gh run list --workflow=release-and-deploy.yml | cat
gh run list --branch develop | cat

# View run details
gh run view RUN_ID | cat

# Watch run in real-time
gh run watch

# Trigger manual workflow
gh workflow run release-and-deploy.yml -f version=3.0.1
```

### Repository Commands

```bash
# View repo info
gh repo view | cat

# List branches
gh api repos/sanruiz/fibra/branches | jq '.[].name' | cat

# List tags
gh api repos/sanruiz/fibra/tags | jq '.[].name' | cat
```

---

## 📊 CI/CD Workflows

### quality-and-tests.yml (Continuous Integration)

**Triggers:**
- Push to branches: `main`, `develop`
- Pull requests to: `main`, `develop`
- Manual dispatch

**Duration:** ~3-5 minutes

**Jobs:**

1. **Code Quality** (parallel)
   - PHPCS (WordPress Coding Standards)
   - PHPStan (Static Analysis Level 6)
   - PHPCBF auto-fix suggestions

2. **PHP Tests** (parallel)
   - PHPUnit 108 tests (355 assertions)
   - Unit tests (PostTypes, Taxonomies, Utils)
   - Integration tests (PageBuilder, Elementor)
   - MySQL database for WordPress tests

3. **Frontend Build** (parallel)
   - npm install
   - Webpack production build
   - Verify CSS/JS bundles

4. **CI Summary** (always runs)
   - Aggregates results
   - Reports success/failure
   - Generates summary

**Does NOT:**
- Create releases
- Deploy to production
- Require manual intervention

**Purpose:** Fast feedback on code quality before merge/release

### release-and-deploy.yml (Continuous Deployment)

**Triggers:**
- Push to tags: `v*` (e.g., v3.0.0, v3.0.1)
- Manual dispatch with version input

**Duration:** ~5-8 minutes

**Jobs (Sequential):**

1. **⏳ Wait for CI** (1-2 min)
   - Verifies quality-and-tests.yml passed
   - Blocks if CI failed or not run
   - Continues only if CI successful

2. **📦 Build & Release** (3-4 min)
   - Install production dependencies
   - Build production assets
   - Create release ZIP (excludes dev files)
   - Generate release notes from CHANGELOG
   - Create GitHub Release
   - Upload ZIP as artifact

3. **🚀 Deploy to Production** (2-3 min)
   - Download release artifact
   - Configure SFTP connection
   - Backup current theme
   - Upload new theme ZIP
   - Generate deployment instructions

**Requires:**
- CI must pass first (enforced by Job 1)
- Version tag format: `v*`
- SFTP secrets configured

**Purpose:** Automated release and deployment pipeline

### Workflow Dependencies

```
┌─────────────────────────────────────┐
│ Push to branch / Create PR          │
└──────────────┬──────────────────────┘
               ↓
┌─────────────────────────────────────┐
│ quality-and-tests.yml (CI)          │
│ - PHPCS, PHPStan                    │
│ - PHPUnit (108 tests)               │
│ - Frontend Build                    │
└──────────────┬──────────────────────┘
               ↓
        ✅ CI Passes
               ↓
┌─────────────────────────────────────┐
│ Merge PR / Ready for Release        │
└──────────────┬──────────────────────┘
               ↓
┌─────────────────────────────────────┐
│ Create and Push Tag (v3.0.1)        │
└──────────────┬──────────────────────┘
               ↓
┌─────────────────────────────────────┐
│ release-and-deploy.yml (CD)         │
│                                     │
│ Job 1: Wait for CI ⏳               │
│   → Check CI passed                 │
│   → Block if failed ❌              │
│                                     │
│ Job 2: Build & Release 📦           │
│   → Build production                │
│   → Create GitHub Release           │
│                                     │
│ Job 3: Deploy 🚀                    │
│   → SFTP upload                     │
│   → Create backup                   │
└─────────────────────────────────────┘
```

---

## 🎭 Common Scenarios

### Scenario 1: Start New Feature

```bash
# 1. Ensure you're on latest develop
git checkout develop
git pull origin develop

# 2. Create feature branch
git checkout -b feature/hero-section

# 3. Work on feature
# ... edit files ...

# 4. Commit changes
git add .
git commit -m "feat: Add hero section component with ACF"

# 5. Push and create PR
git push -u origin feature/hero-section
gh pr create \
  --title "feat: Add hero section component" \
  --base develop \
  --label "enhancement,frontend,alta-prioridad" | cat

# 6. Wait for CI to pass
gh run watch

# 7. After approval, merge PR
gh pr merge NUMBER --squash --delete-branch | cat

# 8. Clean up local branch
git checkout develop
git pull origin develop
git branch -d feature/hero-section
```

### Scenario 2: Fix Bug

```bash
# 1. Create fix branch from develop
git checkout develop
git pull origin develop
git checkout -b fix/navbar-mobile

# 2. Fix the bug
# ... edit files ...

# 3. Test the fix
composer test
npm run prod

# 4. Commit and push
git add .
git commit -m "fix: Navbar mobile menu not closing properly"
git push -u origin fix/navbar-mobile

# 5. Create PR
gh pr create \
  --title "fix: Navbar mobile menu not closing" \
  --base develop \
  --label "bug,frontend,alta-prioridad" | cat

# 6. Merge after CI passes
gh pr merge NUMBER --squash --delete-branch | cat
```

### Scenario 3: Emergency Hotfix to Production

```bash
# 1. Create hotfix from main (production)
git checkout main
git pull origin main
git checkout -b hotfix/critical-security

# 2. Apply fix
# ... fix critical issue ...

# 3. Update version (patch increment)
# style.css: 3.0.1 → 3.0.2
# CHANGELOG.md: Add [3.0.2] section

# 4. Commit and push
git add .
git commit -m "fix: Critical security vulnerability in login"
git push -u origin hotfix/critical-security

# 5. Create PR to main
gh pr create \
  --title "HOTFIX: Critical security vulnerability" \
  --base main \
  --label "bug,security,alta-prioridad" | cat

# 6. After emergency approval and merge
gh pr merge NUMBER --squash | cat

# 7. Pull main and create tag
git checkout main
git pull origin main
git tag -a v3.0.2 -m "Hotfix: Critical security patch"
git push origin v3.0.2

# 8. Monitor deployment
gh run watch

# 9. Backport to develop if needed
git checkout develop
git cherry-pick COMMIT_SHA
git push origin develop
```

### Scenario 4: Create Release

```bash
# 1. Ensure all features merged to develop
gh pr list --base develop | cat  # Should be empty

# 2. Verify all tests pass
git checkout develop
git pull origin develop
composer test
npm run prod

# 3. Create release branch
git checkout -b release/v3.2.0

# 4. Update version and CHANGELOG
# Edit: wp-content/themes/soma/style.css
# Edit: wp-content/themes/soma/CHANGELOG.md

# 5. Commit version bump
git add wp-content/themes/soma/style.css
git add wp-content/themes/soma/CHANGELOG.md
git commit -m "chore: Prepare release v3.2.0"

# 6. Push and create PR to main
git push -u origin release/v3.2.0
gh pr create \
  --title "Release v3.2.0" \
  --base main \
  --body "Release v3.2.0 with new features and improvements.

**Quality:**
- ✅ All tests passing
- ✅ PHPCS clean
- ✅ PHPStan Level 6" | cat

# 7. Wait for CI and approval, then merge
gh run watch
gh pr merge NUMBER --squash | cat

# 8. 🚨 CRITICAL: Create release tag FROM MAIN
git checkout main
git pull origin main
git log -1 --oneline  # Verify you're on the merge commit
git tag -a v3.2.0 -m "Release v3.2.0"
git push origin v3.2.0

# 9. Merge main back to develop
git checkout develop
git merge main
git push origin develop

# 10. Monitor release workflow
gh run watch
```

---

## ✅ Best Practices

### DO (Recommended) ✅

**Branch Management:**
- ✅ Always create feature branches from `develop`
- ✅ Use descriptive branch names with type prefix
- ✅ Delete branches after PR merge
- ✅ Keep branches up to date with base branch

**Commits:**
- ✅ Follow Conventional Commits format
- ✅ Write clear, descriptive commit messages
- ✅ Make atomic commits (one logical change per commit)
- ✅ Reference issues in commits (#NUMBER)

**Pull Requests:**
- ✅ Wait for CI to pass before requesting review
- ✅ Write detailed PR descriptions
- ✅ Link related issues (Closes #NUMBER)
- ✅ Respond to review comments promptly
- ✅ Squash merge to keep main/develop history clean

**Quality:**
- ✅ **ALWAYS run `composer phpcs`, `composer phpstan`, `composer test` BEFORE pushing**
- ✅ **NEVER create PR until all local quality checks pass**
- ✅ Fix PHPCS/PHPStan errors before creating PR
- ✅ Test responsive design on all breakpoints
- ✅ Update documentation when needed

**Post-Merge:**
- ✅ Delete local branch after PR merge
- ✅ Delete remote branch after PR merge (or use `--delete-branch`)
- ✅ Update and close related issues with PR reference
- ✅ Mark issue tasks as completed

**GitHub CLI:**
- ✅ Always append `| cat` to `gh` commands
- ✅ Use labels consistently on issues/PRs
- ✅ Monitor CI/CD workflow status

**Releases:**
- ✅ Update version in style.css
- ✅ Update CHANGELOG.md with release notes
- ✅ **ALWAYS merge to `main` FIRST, then create tag FROM `main`**
- ✅ **NEVER create tags from `develop` branch (they become orphaned after squash merge)**
- ✅ Create release tag only after CI passes
- ✅ Verify deployment after release
- ✅ Create backup before major releases

### DON'T (Avoid) ❌

**Branch Management:**
- ❌ Never commit directly to `main` or `develop`
- ❌ Don't create feature branches from `main` (except hotfixes)
- ❌ Don't reuse old branch names
- ❌ Don't merge without PR review

**Commits:**
- ❌ Don't commit broken code
- ❌ Don't use vague commit messages ("fix", "update")
- ❌ Don't commit large files or node_modules
- ❌ Don't commit secrets or credentials

**Pull Requests:**
- ❌ Don't merge PRs with failing tests
- ❌ Don't ignore code review feedback
- ❌ Don't create PRs without description
- ❌ Don't merge your own PRs without approval

**Quality:**
- ❌ Don't skip tests to "save time"
- ❌ Don't disable PHPCS/PHPStan rules
- ❌ Don't commit commented-out code
- ❌ Don't leave console.log() in production code

**Releases:**
- ❌ Don't create tags without CI passing
- ❌ **NEVER create tags from `develop` branch (use `main` only!)**
- ❌ Don't skip version updates
- ❌ Don't deploy without testing
- ❌ Don't forget to update CHANGELOG.md

**GitHub CLI:**
- ❌ Don't forget `| cat` on `gh` commands
- ❌ Don't create issues without labels

---

## 🆘 Troubleshooting

### Issue: PR CI Failing

**Problem:** quality-and-tests.yml workflow failing

**Solutions:**
```bash
# 1. Check what failed
gh pr checks NUMBER | cat

# 2. View logs
gh run view RUN_ID | cat

# 3. Common fixes:
# - PHPCS errors: composer phpcbf
# - PHPStan errors: Fix type hints
# - Tests failing: composer test
# - Build errors: npm run prod

# 4. Fix and push
git add .
git commit -m "fix: Resolve CI errors"
git push
```

### Issue: Tag Didn't Trigger Release Workflow

**Problem:** Created tag but release-and-deploy.yml didn't run

**Causes:**
- Tag created on old commit without CI run
- Tag doesn't match `v*` pattern
- Workflow file syntax error

**Solutions:**
```bash
# 1. Verify tag format
git tag -l | cat
# Should show: v3.0.1 (not 3.0.1)

# 2. Check if CI passed for this commit
gh run list --workflow=quality-and-tests.yml | cat

# 3. If needed, trigger manually
gh workflow run release-and-deploy.yml -f version=3.0.1

# 4. Or delete and recreate tag on main (CORRECT APPROACH)
git tag -d v3.0.1
git push origin --delete v3.0.1
# 🚨 CRITICAL: Checkout MAIN, not develop
git checkout main
git pull origin main
git tag -a v3.0.1 -m "Release v3.0.1"
git push origin v3.0.1
```

### Issue: Can't Merge PR - Conflicts

**Problem:** Merge conflicts prevent PR merge

**Solutions:**
```bash
# 1. Update your branch with base
git checkout feature/my-feature
git fetch origin
git merge origin/develop

# 2. Resolve conflicts manually
# Edit conflicted files

# 3. Mark resolved and commit
git add .
git commit -m "chore: Resolve merge conflicts"

# 4. Push updated branch
git push origin feature/my-feature
```

### Issue: Deployment Failed

**Problem:** Job 3 (Deploy) failed in release workflow

**Common Causes:**
- SFTP credentials expired/invalid
- Server disk full
- Network issues

**Solutions:**
```bash
# 1. Check workflow logs
gh run view RUN_ID | cat

# 2. Verify SFTP secrets in repo settings
# GitHub → Settings → Secrets → Actions

# 3. Test SFTP connection manually
sftp -i ~/.ssh/key user@host

# 4. If needed, trigger deploy again
gh workflow run release-and-deploy.yml -f version=3.0.1
```

### Issue: Forgot to Close Milestone

**Problem:** Milestone still open after completion

**Solutions:**
```bash
# 1. List milestones
gh api repos/sanruiz/fibra/milestones | jq '.[] | {number, title, state}' | cat

# 2. Close milestone
gh api repos/sanruiz/fibra/milestones/NUMBER -X PATCH -f state=closed | cat

# 3. Verify closed
gh api repos/sanruiz/fibra/milestones | jq '.[] | select(.state=="closed")' | cat
```

---

## 📚 Additional Resources

### Documentation
- **Project Overview**: `.github/copilot-instructions.md`
- **PHP Guidelines**: `.github/instructions/php.instructions.md`
- **Development Guide**: `wp-content/themes/soma/docs/DEVELOPMENT.md`
- **Testing Guide**: `wp-content/themes/soma/docs/TESTING_GUIDE.md`

### GitHub Actions
- **CI Workflow**: `.github/workflows/quality-and-tests.yml`
- **CD Workflow**: `.github/workflows/release-and-deploy.yml`

### External Links
- [Conventional Commits](https://www.conventionalcommits.org/)
- [GitHub CLI Manual](https://cli.github.com/manual/)
- [Semantic Versioning](https://semver.org/)
- [GitHub Flow](https://guides.github.com/introduction/flow/)

---

**Last Updated**: December 15, 2025  
**Maintained By**: Miguel Colmenares  
**Repository**: https://github.com/sanruiz/fibra
