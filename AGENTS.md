# Copilot Coding Agent Instructions

> **CRITICAL**: This file contains mandatory instructions for the GitHub Copilot Coding Agent.
> The agent MUST follow these rules when working on issues in this repository.

## 🚨 Branch Workflow (MANDATORY)

### Base Branch Rule

**NEVER create branches from `main`**. This repository uses GitFlow with `develop` as the integration branch.

1. **Use `develop` as base**: When assigned to an issue, ALWAYS create branches from `develop`
2. **Target PRs to `develop`**: Pull requests MUST target `develop`, NOT `main`
3. **`main` is for releases only**: Only release branches and hotfixes merge to `main`

```
✅ CORRECT:
   Base: develop → Feature branch: copilot/fix-123 → PR target: develop

❌ WRONG:
   Base: main → Feature branch: copilot/fix-123 → PR target: main
```

### Branch Types

- `main` - Production branch (stable releases only)
- `develop` - Development branch (integration branch for features)
- `feature/*` - New features
- `fix/*` - Bug fixes
- `hotfix/*` - Emergency production fixes
- `release/*` - Release preparation

### When Creating a Branch

```bash
# 1. Ensure develop is up to date
git fetch origin
git checkout develop
git pull origin develop

# 2. Create feature branch from develop
git checkout -b copilot/issue-description
```

---

## 📋 Pull Request Requirements

### PR Title Format

Use conventional commits format:

```
type(scope): brief description

Examples:
- feat(elementor): Add new Portfolio widget
- fix(api): Resolve image size endpoint issue
- perf(widgets): Optimize image loading
- docs(readme): Update installation guide
```

### Required Labels

**ALWAYS add appropriate labels to PRs:**

| Category | Labels |
|----------|--------|
| **Type** | `enhancement`, `bug`, `documentation`, `performance`, `security` |
| **Area** | `frontend`, `backend`, `elementor`, `api`, `testing` |
| **Priority** | `alta-prioridad`, `media-prioridad`, `baja-prioridad` |

### PR Description Template

Include in EVERY pull request:

```markdown
## Summary
Brief description of changes.

## Changes
- Specific change 1
- Specific change 2

## Testing
- [ ] Ran `composer phpcs` (0 errors)
- [ ] Ran `composer phpstan` (Level 6+)
- [ ] Ran `composer test` (all tests pass)
- [ ] Tested manually (describe how)

## Related Issue
Closes #ISSUE_NUMBER

## Notes for Reviewers
Any additional context or areas to focus on.
```

---

## 🧪 Testing Requirements

### Before Creating PR

**ALWAYS run these commands** before pushing:

```bash
cd wp-content/themes/soma

# 1. Code style check
composer phpcs

# 2. Static analysis
composer phpstan

# 3. Unit tests
composer test

# 4. Frontend build (if CSS/JS modified)
npm run prod
```

### Test Results in PR

Document test results in the PR description:

```markdown
## Quality Checks
- ✅ PHPCS: 0 errors
- ✅ PHPStan: Level 6, 0 critical errors
- ✅ PHPUnit: 108 tests, 355 assertions passing
- ✅ npm run prod: Build successful
```

---

## 📝 Version Notes (CHANGELOG)

### When to Update CHANGELOG

Update `wp-content/themes/soma/CHANGELOG.md` for:

- New features
- Bug fixes
- Performance improvements
- Breaking changes

### CHANGELOG Format

Add entries under the `[Unreleased]` section:

```markdown
## [Unreleased]

### ✨ Added
- New feature description (#ISSUE_NUMBER)

### 🐛 Fixed
- Bug fix description (#ISSUE_NUMBER)

### ⚡ Performance
- Performance improvement (#ISSUE_NUMBER)
```

---

## 🏗️ Project Structure

### Key Directories

```
wp-content/themes/soma/
├── includes/           # PSR-4 PHP classes (Soma\ namespace)
│   ├── Elementor/     # Elementor widgets
│   │   └── Widgets/   # Widget classes
│   ├── API/           # REST API endpoints
│   │   └── Endpoints/ # Endpoint classes
│   └── Utils/         # Helper functions
├── assets/            # CSS, JS, images
│   ├── css/widgets/   # Widget-specific CSS
│   └── js/widgets/    # Widget-specific JS
├── tests/             # PHPUnit tests
│   ├── Unit/          # Unit tests
│   └── Integration/   # Integration tests
└── docs/              # Documentation
```

### Naming Conventions

| Type | Convention | Example |
|------|------------|---------|
| PHP Classes | PascalCase | `PortfolioWidget.php` |
| CSS Files | kebab-case | `portfolio-widget.css` |
| JS Files | kebab-case | `portfolio-widget.js` |
| Test Files | PascalCase + Test suffix | `PortfolioWidgetTest.php` |

---

## 🔧 Build & Validation Commands

### Quick Reference

```bash
# Navigate to theme
cd wp-content/themes/soma

# Quality checks
composer phpcs        # WordPress Coding Standards
composer phpcbf       # Auto-fix coding standards
composer phpstan      # Static analysis Level 6+
composer test         # PHPUnit tests

# Frontend build
npm install           # Install dependencies
npm run dev           # Development build
npm run prod          # Production build (minified)
npm run watch         # Watch mode

# All quality gates (run before PR)
composer phpcs && composer phpstan && composer test && npm run prod
```

---

## ⚠️ Common Pitfalls

### DO NOT

- ❌ Create branches from `main` (use `develop`)
- ❌ Target PRs to `main` (use `develop`)
- ❌ Skip quality checks before PR
- ❌ Forget to add labels
- ❌ Leave CHANGELOG unchanged for features/fixes
- ❌ Use `$post->post_title` directly (use `get_the_title($post->ID)` for i18n)

### ALWAYS

- ✅ Use `develop` branch as base for all features/fixes
- ✅ Run all quality checks before pushing
- ✅ Add appropriate labels to PRs
- ✅ Document test results in PR
- ✅ Update CHANGELOG for significant changes
- ✅ Reference issue number in commits and PR

---

## 📚 Additional Context

### Issue Assignment

When assigned to an issue via `@copilot`:

1. Read the full issue description
2. Check for linked issues or related PRs
3. Checkout and update `develop` branch
4. Create feature branch from `develop`
5. Implement changes following this guide
6. Create PR targeting `develop`

### Commit Message Format

```
type(scope): description

- Additional detail 1
- Additional detail 2

Closes #ISSUE_NUMBER
```

Types: `feat`, `fix`, `perf`, `docs`, `style`, `refactor`, `test`, `chore`

---

**Last Updated**: February 2026  
**Repository**: sanruiz/fibra  
**Theme Version**: 3.1.18+
