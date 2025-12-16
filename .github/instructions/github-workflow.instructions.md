# GitHub Workflow Instructions for FibraSOMA Project

**Applies to**: All GitHub operations, branch management, issues, PRs, releases, and deployments  
**Last Updated**: December 15, 2025  
**Project**: FibraSOMA Website Development (8 weeks)

---

## 🌳 Branch Management

### Branch Naming Conventions

```
week-N              → Milestone branches (week-2, week-3, ..., week-9)
feature/description → New features (e.g., feature/hero-section)
fix/description     → Bug fixes (e.g., fix/navbar-mobile)
chore/description   → Maintenance tasks (e.g., chore/update-deps)
hotfix/description  → Emergency fixes (e.g., hotfix/security-patch)
```

### Branch Hierarchy

```
main (default branch)
 ↑
 └── week-2 (current milestone)
      ↑
      ├── feature/hero-section
      ├── feature/business-units
      ├── fix/navbar-mobile
      └── chore/update-styles
```

### CRITICAL Rules

**✅ DO:**
- Each milestone MUST be developed in a separate `week-N` branch
- Create feature branches from `week-N`, NOT from `main`
- Merge feature branches back to `week-N` via PR
- Merge `week-N` to `main` only after milestone completion

**❌ NEVER:**
- Commit directly to `main`
- Create feature branches from `main` during milestone work
- Skip the PR review process
- Merge without CI passing

### Branch Creation Workflow

```bash
# 1. Start new feature from current milestone
git checkout week-2
git pull origin week-2
git checkout -b feature/hero-section

# 2. Work and commit
git add .
git commit -m "feat: Add hero section component"

# 3. Push to remote
git push -u origin feature/hero-section

# 4. Create PR to week-2 (NOT main)
gh pr create --title "feat: Add hero section" \
  --base week-2 \
  --label "enhancement,week-2,frontend,alta-prioridad" | cat

# 5. After PR merge, delete branch
git branch -d feature/hero-section
git push origin --delete feature/hero-section
```

### Milestone Branch Lifecycle

```bash
# Week 2 starts
git checkout main
git pull origin main
git checkout -b week-2
git push -u origin week-2

# ... work on features via feature/* branches ...

# Week 2 complete - merge to main
gh pr create --title "Week 2: Home Page Development" \
  --base main \
  --body "Completes Week 2 milestone with all features" | cat

# After merge and approval
gh pr merge NUMBER --squash | cat

# Close milestone
gh api repos/sanruiz/fibra/milestones/2 -X PATCH -f state=closed | cat
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
- Milestone: Week 2
- Component: Home Page" \
  --label "enhancement,semana-2,frontend,alta-prioridad" \
  --milestone 2 | cat
```

### Required Labels for Each Issue

**Timeline (Required):**
- `semana-1` to `semana-9` - Based on milestone

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
1. Create issue → Assign labels + milestone
2. Create feature branch
3. Work on branch
4. Create PR linking issue (#NUMBER)
5. Merge PR
6. Issue auto-closes (or close manually)
7. After all issues done → Close milestone
```

### Issue Commands

```bash
# List issues for current milestone
gh issue list --milestone "Week 2" | cat

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
# Standard PR to week-N
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
  --base week-2 \
  --label "enhancement,frontend,week-2" | cat

# Milestone completion PR (week-N to main)
gh pr create \
  --title "Week 2: Home Page Development" \
  --body "Completes Week 2 milestone.

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
- ✅ All PRs reviewed and merged

Closes milestone #2" \
  --base main | cat
```

### PR Review Checklist

**Before Creating PR:**
- [ ] All commits follow conventional commits format
- [ ] Code follows WordPress Coding Standards (PHPCS)
- [ ] Static analysis passes (PHPStan Level 6+)
- [ ] All tests passing (PHPUnit)
- [ ] Frontend builds without errors
- [ ] Documentation updated if needed

**PR Description Must Include:**
- [ ] Clear description of changes
- [ ] List of specific changes
- [ ] Testing notes
- [ ] Issue references (Closes #N)

**After PR Created:**
- [ ] CI workflow passes (quality-and-tests.yml)
- [ ] Code review by team member (if applicable)
- [ ] All comments addressed
- [ ] No merge conflicts

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

# Merge PR (squash recommended)
gh pr merge 55 --squash --delete-branch | cat

# Close PR without merging
gh pr close 55 --comment "Not needed anymore" | cat
```

---

## 🎯 Milestone Management

### Milestone Structure

| Milestone | Branch | Timeline | Description |
|-----------|--------|----------|-------------|
| Week 2 | week-2 | Dec 16-22 | Home page sections |
| Week 3 | week-3 | Dec 23-29 | Corporate pages |
| Week 4-5 | week-4, week-5 | Dec 30-Jan 12 | Investor Relations |
| Week 6 | week-6 | Jan 13-19 | Portfolio templates |
| Week 7 | week-7 | Jan 20-26 | ESG/ASG section |
| Week 8 | week-8 | Jan 27-Feb 2 | News system |
| Week 9 | week-9 | Feb 3-9 | QA, optimization, launch |

### Milestone Workflow

```bash
# 1. Start milestone - Create week-N branch
git checkout main
git pull origin main
git checkout -b week-N
git push -u origin week-N

# 2. Work on milestone - Create issues
gh issue create --milestone N --label "semana-N,..." | cat

# 3. Complete issues - Create PRs to week-N
gh pr create --base week-N | cat

# 4. End milestone - Create PR to main
gh pr create --title "Week N: Description" --base main | cat

# 5. Close milestone
gh api repos/sanruiz/fibra/milestones/N -X PATCH -f state=closed | cat
```

### Milestone Commands

```bash
# List all milestones
gh api repos/sanruiz/fibra/milestones | jq '.[] | {number, title, state}' | cat

# View milestone details
gh api repos/sanruiz/fibra/milestones/2 | cat

# Close milestone
gh api repos/sanruiz/fibra/milestones/2 -X PATCH -f state=closed | cat

# List issues in milestone
gh issue list --milestone "Week 2" | cat
```

---

## 🚀 Release & Deployment Flow

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
# 1. Checkout base branch (week-N or main)
git checkout week-2
git pull origin week-2

# 2. Create release branch
git checkout -b release/v3.0.1

# 3. Update version in style.css
# Change: Version: 3.0.0
# To:     Version: 3.0.1
```

#### Phase 2: Update CHANGELOG.md

```markdown
## [3.0.1] - 2025-12-20

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
git commit -m "chore: Prepare release v3.0.1"

# Push release branch
git push -u origin release/v3.0.1

# Wait for CI to pass
gh run watch
```

#### Phase 4: Create Pull Request

```bash
gh pr create \
  --title "Release v3.0.1" \
  --body "Release v3.0.1 with bugfixes and improvements.

**Changes:**
See CHANGELOG.md for details.

**Quality Gates:**
- ✅ PHPCS clean
- ✅ PHPStan Level 6
- ✅ 108 tests passing
- ✅ Frontend build successful" \
  --base week-2 | cat
```

#### Phase 5: Merge and Tag

```bash
# After PR approval, merge
gh pr merge NUMBER --squash | cat

# Checkout base branch and pull
git checkout week-2
git pull origin week-2

# Create annotated tag
git tag -a v3.0.1 -m "Release v3.0.1: Bugfixes and improvements"

# Push tag (THIS TRIGGERS release-and-deploy.yml)
git push origin v3.0.1
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
gh issue create --label "enhancement,week-2" | cat

# List issues
gh issue list | cat
gh issue list --milestone "Week 2" | cat
gh issue list --label "bug,alta-prioridad" | cat
gh issue list --state all | cat

# View issue
gh issue view NUMBER | cat

# Edit issue
gh issue edit NUMBER --add-label "testing" | cat
gh issue edit NUMBER --milestone "Week 3" | cat

# Close issue
gh issue close NUMBER | cat
gh issue close NUMBER --comment "Fixed in PR #55" | cat

# Reopen issue
gh issue reopen NUMBER | cat
```

### Pull Request Commands

```bash
# Create PR
gh pr create --title "..." --base week-2 | cat

# List PRs
gh pr list | cat
gh pr list --state all | cat
gh pr list --base week-2 | cat

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

# Create release (manual - usually done by workflow)
gh release create v3.0.1 \
  --title "SOMA Theme v3.0.1" \
  --notes "See CHANGELOG.md for details" \
  soma-v3.0.1.zip | cat

# Delete release
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
gh run list --branch week-2 | cat

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

# List milestones
gh api repos/sanruiz/fibra/milestones | jq '.[] | {number, title, state}' | cat

# Close milestone
gh api repos/sanruiz/fibra/milestones/NUMBER -X PATCH -f state=closed | cat
```

---

## 📊 CI/CD Workflows

### quality-and-tests.yml (Continuous Integration)

**Triggers:**
- Push to branches: `main`, `develop`, `week-*`
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
# 1. Ensure you're on latest week-N
git checkout week-2
git pull origin week-2

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
  --base week-2 \
  --label "enhancement,frontend,week-2,alta-prioridad" | cat

# 6. Wait for CI to pass
gh run watch

# 7. After approval, merge PR
gh pr merge NUMBER --squash --delete-branch | cat

# 8. Clean up local branch
git checkout week-2
git pull origin week-2
git branch -d feature/hero-section
```

### Scenario 2: Fix Bug

```bash
# 1. Create fix branch from week-N
git checkout week-2
git pull origin week-2
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
  --base week-2 \
  --label "bug,frontend,week-2,alta-prioridad" | cat

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

# 9. Backport to week-N if needed
git checkout week-2
git cherry-pick COMMIT_SHA
git push origin week-2
```

### Scenario 4: Complete Week Milestone

```bash
# 1. Ensure all features merged to week-2
gh pr list --base week-2 | cat  # Should be empty

# 2. Verify all tests pass
git checkout week-2
git pull origin week-2
composer test
npm run prod

# 3. Create PR from week-2 to main
gh pr create \
  --title "Week 2: Home Page Development" \
  --base main \
  --body "Completes Week 2 milestone.

**Features:**
- Hero section
- Business units grid
- Portfolio showcase
- News feed

**Quality:**
- ✅ 108 tests passing
- ✅ PHPCS clean
- ✅ PHPStan Level 6

Closes milestone #2" | cat

# 4. Wait for CI and approval
gh run watch

# 5. Merge to main
gh pr merge NUMBER --squash | cat

# 6. Close milestone
gh api repos/sanruiz/fibra/milestones/2 -X PATCH -f state=closed | cat

# 7. Optional: Create release tag
git checkout main
git pull origin main
git tag -a v3.1.0 -m "Release v3.1.0: Week 2 complete"
git push origin v3.1.0
```

### Scenario 5: Create Patch Release

```bash
# 1. Start from current branch
git checkout week-2
git pull origin week-2
git checkout -b release/v3.0.1

# 2. Update version files
# Edit: wp-content/themes/soma/style.css
# Change: Version: 3.0.0 → Version: 3.0.1

# Edit: wp-content/themes/soma/CHANGELOG.md
# Add:
## [3.0.1] - 2025-12-20

### Fixed
- Navbar mobile menu issue
- Portfolio image overflow

# 3. Commit version bump
git add wp-content/themes/soma/style.css
git add wp-content/themes/soma/CHANGELOG.md
git commit -m "chore: Bump version to 3.0.1"

# 4. Push and wait for CI
git push -u origin release/v3.0.1
gh run watch

# 5. Create PR to week-2
gh pr create \
  --title "Release v3.0.1" \
  --base week-2 | cat

# 6. Merge PR
gh pr merge NUMBER --squash | cat

# 7. Create and push tag
git checkout week-2
git pull origin week-2
git tag -a v3.0.1 -m "Release v3.0.1"
git push origin v3.0.1

# 8. Monitor release workflow
gh run list --workflow=release-and-deploy.yml | cat
gh run watch
```

---

## ✅ Best Practices

### DO (Recommended) ✅

**Branch Management:**
- ✅ Always create feature branches from `week-N`
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
- ✅ Squash merge to keep main/week-N history clean

**Quality:**
- ✅ Run tests locally before pushing
- ✅ Fix PHPCS/PHPStan errors before creating PR
- ✅ Test responsive design on all breakpoints
- ✅ Update documentation when needed

**GitHub CLI:**
- ✅ Always append `| cat` to `gh` commands
- ✅ Use labels consistently on issues/PRs
- ✅ Close milestones after completion
- ✅ Monitor CI/CD workflow status

**Releases:**
- ✅ Update version in style.css
- ✅ Update CHANGELOG.md with release notes
- ✅ Create release tag only after CI passes
- ✅ Verify deployment after release
- ✅ Create backup before major releases

### DON'T (Avoid) ❌

**Branch Management:**
- ❌ Never commit directly to `main`
- ❌ Don't create feature branches from `main` during milestones
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
- ❌ Don't skip version updates
- ❌ Don't deploy without testing
- ❌ Don't forget to update CHANGELOG.md

**GitHub CLI:**
- ❌ Don't forget `| cat` on `gh` commands
- ❌ Don't create issues without labels
- ❌ Don't leave milestones open indefinitely

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

# 4. Or delete and recreate tag on latest commit
git tag -d v3.0.1
git push origin --delete v3.0.1
git checkout week-2
git pull origin week-2
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
git merge origin/week-2

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
