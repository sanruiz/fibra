---
description: Documentation standards and language policy for all project files
name: Documentation & Language Policy
applyTo: "**"
---

# Documentation and Language Policy Instructions

**Applies to**: All documentation files, code comments, commit messages, and project communication  
**Last Updated**: December 15, 2025  
**Project**: FibraSOMA Website Development

---

## 🌍 Language Policy

### CRITICAL: English-Only Rule

**ALL technical project files MUST be written in ENGLISH:**

✅ **Always in English:**
- Code comments (PHP, JavaScript, CSS)
- Documentation files (`.md`)
- Commit messages
- Pull Request descriptions
- Issue descriptions
- GitHub Actions workflows (`.yml`)
- Scripts (`.sh`, `.php`, etc.)
- README files
- API documentation
- Code variable/function names
- Configuration files comments

❌ **Exception - Spanish Allowed:**
- User-facing content in WordPress (for FibraSOMA website visitors)
- Spanish strings in theme translation files (`.pot`, `.po`)
- Content entered in WordPress admin (posts, pages, ACF fields)
- Website copy and marketing materials

### Rationale

**Why English for Technical Content:**

1. **International Standard**
   - English is the lingua franca of software development
   - All major frameworks and libraries use English
   - Stack Overflow, GitHub, documentation resources are in English

2. **Tool Compatibility**
   - Better integration with IDEs and development tools
   - AI assistants (like Copilot) work better with English
   - Syntax highlighting and linting tools expect English

3. **Team Collaboration**
   - Accessible to international developers
   - Easier to onboard new team members
   - Facilitates code reviews and collaboration

4. **Consistency**
   - SOMA v3.0.0 codebase is fully in English
   - WordPress core and standards use English
   - Industry best practices expect English

5. **Maintainability**
   - Easier to search for issues/solutions online
   - Better documentation resources available
   - Consistent with open-source practices

---

## 📝 Documentation Standards

### File Naming Conventions

```
README.md                   # Project overview (always uppercase)
CHANGELOG.md               # Version history (always uppercase)
CONTRIBUTING.md            # Contribution guidelines
DEVELOPMENT.md             # Developer guide
ARCHITECTURE.md            # Architecture documentation
API.md                     # API reference
TESTING.md                 # Testing guide
{specific-topic}.md        # Topic-specific docs (lowercase with hyphens)
```

**Rules:**
- Major docs: UPPERCASE.md (README, CHANGELOG, LICENSE)
- Specific guides: lowercase-with-hyphens.md
- Always use `.md` extension
- No spaces in filenames

### Document Structure

**Every documentation file should include:**

```markdown
# Document Title

**Purpose**: Brief description of what this document covers  
**Last Updated**: YYYY-MM-DD  
**Audience**: Who should read this (developers, users, admins, etc.)

---

## Table of Contents

1. [Section 1](#section-1)
2. [Section 2](#section-2)

---

## Section 1

Content...

---

## Additional Resources

- Related documentation links
- External references

---

**Document Version**: 1.0  
**Last Updated**: YYYY-MM-DD  
**Maintainer**: Name
```

### Markdown Best Practices

**Headers:**
```markdown
# H1 - Document Title (only one per file)
## H2 - Main Sections
### H3 - Subsections
#### H4 - Sub-subsections (use sparingly)
```

**Emphasis:**
```markdown
**Bold** for important terms and emphasis
*Italic* for subtle emphasis (use sparingly)
`code` for inline code, commands, file paths
```

**Lists:**
```markdown
# Unordered lists
- Item 1
- Item 2
  - Nested item
  - Nested item

# Ordered lists
1. First step
2. Second step
   1. Sub-step
   2. Sub-step

# Task lists
- [ ] Incomplete task
- [x] Completed task
```

**Code Blocks:**
````markdown
```php
// Always specify language for syntax highlighting
function example() {
    return true;
}
```

```bash
# Use bash for terminal commands
git status
npm install
```

```yaml
# Use appropriate language
name: Workflow Name
on: [push]
```
````

**Links:**
```markdown
# External links
[Link Text](https://example.com)

# Internal links (relative paths)
[Development Guide](docs/DEVELOPMENT.md)

# Anchor links
[Go to Section](#section-name)
```

**Tables:**
```markdown
| Column 1 | Column 2 | Column 3 |
|----------|----------|----------|
| Value 1  | Value 2  | Value 3  |
| Value 4  | Value 5  | Value 6  |

# Align columns
| Left | Center | Right |
|:-----|:------:|------:|
| L1   |   C1   |    R1 |
```

**Alerts/Callouts:**
```markdown
**⚠️ WARNING**: Important warning message

**✅ TIP**: Helpful tip or best practice

**❌ AVOID**: Things to avoid

**🔥 CRITICAL**: Critical information

**💡 NOTE**: Additional information
```

---

## ✍️ Writing Style Guide

### Tone and Voice

- **Clear and Concise**: Get to the point quickly
- **Professional**: Maintain technical accuracy
- **Helpful**: Anticipate questions and provide context
- **Direct**: Use active voice ("Run the command" not "The command should be run")
- **Consistent**: Use the same terminology throughout

### Technical Writing Rules

**DO ✅:**
- Use present tense ("The function returns..." not "The function will return...")
- Use second person for instructions ("You can run..." not "One can run...")
- Define acronyms on first use (ACF = Advanced Custom Fields)
- Include examples for complex concepts
- Use numbered lists for sequential steps
- Use bullet lists for non-sequential items
- Break long paragraphs into smaller chunks
- Add code examples with comments

**DON'T ❌:**
- Use jargon without explanation
- Assume reader knowledge
- Mix tenses inconsistently
- Use passive voice excessively
- Create walls of text without formatting
- Skip prerequisites or setup steps
- Leave ambiguous instructions

### Code Examples

**Always include:**
1. **Context**: Why this code is needed
2. **Complete example**: Working code that can be copy-pasted
3. **Comments**: Explain non-obvious parts
4. **Expected output**: What should happen

```markdown
### Example: Creating a Custom Post Type

Add this code to `functions.php` to register a custom post type:

\`\`\`php
/**
 * Register Events custom post type.
 * 
 * This allows managing events separately from regular posts.
 */
function register_events_post_type() {
    register_post_type('events', [
        'public' => true,
        'labels' => [
            'name' => __('Events', 'soma'),
            'singular_name' => __('Event', 'soma'),
        ],
        'supports' => ['title', 'editor', 'thumbnail'],
    ]);
}
add_action('init', 'register_events_post_type');
\`\`\`

**Expected Result**: A new "Events" menu appears in WordPress admin.
```

---

## 📂 Documentation Organization

### Repository Structure

```
.github/
├── copilot-instructions.md              # Main Copilot context
└── instructions/
    ├── php.instructions.md              # PHP/WordPress conventions
    ├── github-workflow.instructions.md  # GitHub workflows
    └── documentation-language.instructions.md  # This file

wp-content/themes/soma/
├── README.md                            # Theme overview
├── CHANGELOG.md                         # Version history
└── docs/
    ├── DEVELOPMENT.md                   # Developer guide
    ├── WIDGETS.md                       # Elementor widgets reference
    ├── HELPERS.md                       # Helper functions API
    ├── MIGRATION_FROM_V2.md             # Migration guide
    ├── TESTING_GUIDE.md                 # Testing documentation
    └── ARCHITECTURE_VISION.md           # Architecture overview
```

### When to Create New Documentation

**Create a new doc when:**
- Topic is substantial (500+ lines)
- Topic is self-contained
- Multiple people will reference it
- It serves a specific audience

**Add to existing doc when:**
- Topic is small (< 200 lines)
- Closely related to existing section
- Part of larger workflow
- Would create fragmentation

---

## 💬 Commit Message Standards

### Conventional Commits Format

**Required format:**
```
type(scope): brief description

Detailed explanation of changes (optional)

- Bullet point 1
- Bullet point 2

Closes #issue-number (if applicable)
```

### Commit Types

| Type | Usage | Example |
|------|-------|---------|
| `feat` | New features | `feat: Add hero section component` |
| `fix` | Bug fixes | `fix: Navbar mobile menu not closing` |
| `docs` | Documentation only | `docs: Update DEVELOPMENT.md with new patterns` |
| `style` | Code style (formatting, no logic change) | `style: Format PHP files with PHPCS` |
| `refactor` | Code refactoring | `refactor: Simplify cache invalidation logic` |
| `perf` | Performance improvements | `perf: Optimize portfolio query with caching` |
| `test` | Add or update tests | `test: Add unit tests for Cache class` |
| `build` | Build system changes | `build: Update webpack config` |
| `ci` | CI/CD changes | `ci: Add PHPStan to quality workflow` |
| `chore` | Maintenance tasks | `chore: Update dependencies` |
| `revert` | Revert previous commit | `revert: Revert "feat: Add feature X"` |

### Commit Message Examples

**Good ✅:**
```bash
feat: Add hero section component with ACF integration

- Created HeroSection.php partial
- Added ACF field group
- Implemented responsive styles
- Added Elementor widget

Closes #42
```

**Bad ❌:**
```bash
updates
# Too vague

Fixed bug
# No context, no issue reference

Added new feature to the homepage that allows users to see hero section
# Too verbose, should be split into subject and body
```

### Scope Examples

- `(homepage)` - Home page specific
- `(navbar)` - Navigation component
- `(portfolio)` - Portfolio functionality
- `(ci)` - CI/CD workflows
- `(deps)` - Dependencies
- `(docs)` - Documentation
- `(tests)` - Testing

---

## 📋 Pull Request Standards

### PR Title Format

Same as commit messages:
```
type: brief description
```

Examples:
- `feat: Add hero section to homepage`
- `fix: Resolve navbar mobile menu issue`
- `docs: Update GitHub workflow instructions`

### PR Description Template

```markdown
## Description

Brief summary of changes (1-2 sentences).

## Changes

- Specific change 1
- Specific change 2
- Specific change 3

## Testing

- [ ] Unit tests pass
- [ ] PHPCS clean
- [ ] PHPStan Level 6
- [ ] Frontend build successful
- [ ] Tested on desktop (1920px)
- [ ] Tested on tablet (768px)
- [ ] Tested on mobile (375px)

## Screenshots (if applicable)

[Add screenshots for UI changes]

## Related Issues

Closes #issue-number
Relates to #issue-number

## Checklist

- [ ] Code follows WordPress Coding Standards
- [ ] Documentation updated
- [ ] Tests added/updated
- [ ] No breaking changes (or documented)
```

---

## 🔤 Code Comments Standards

### PHP Comments

**File Headers:**
```php
<?php
/**
 * Brief file description.
 *
 * More detailed description if needed.
 *
 * @package    Soma
 * @subpackage PageBuilder
 * @since      3.0.0
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}
```

**Class Headers:**
```php
/**
 * Class description.
 *
 * Detailed explanation of what the class does.
 *
 * @since 3.0.0
 */
class ClassName {
    // Class implementation
}
```

**Method/Function Comments:**
```php
/**
 * Brief description of what the method does.
 *
 * More detailed explanation if needed.
 *
 * @since 3.0.0
 *
 * @param string $param1 Description of parameter.
 * @param array  $param2 Description of array parameter.
 * @return bool True on success, false on failure.
 */
public function method_name(string $param1, array $param2): bool {
    // Implementation
}
```

**Inline Comments:**
```php
// Single-line comment for brief explanations

/*
 * Multi-line comment for longer explanations
 * that span multiple lines.
 */

// TODO: Future enhancement description
// FIXME: Known issue that needs fixing
// NOTE: Important information
// HACK: Temporary solution (should be refactored)
```

### JavaScript Comments

**File Headers:**
```javascript
/**
 * Brief file description.
 *
 * @package Soma
 * @since   3.0.0
 */
```

**Function Comments:**
```javascript
/**
 * Brief description of function.
 *
 * @param {string} param1 - Description.
 * @param {Object} param2 - Description.
 * @returns {boolean} True on success.
 */
function functionName(param1, param2) {
    // Implementation
}
```

### SCSS/CSS Comments

```scss
/**
 * Component: Hero Section
 *
 * Styles for the hero section component including
 * responsive breakpoints and dark mode variants.
 */

// Variables
$hero-min-height: 600px;

/* Main container */
.hero-section {
    // Desktop styles
    min-height: $hero-min-height;
    
    // Tablet breakpoint
    @media (max-width: 768px) {
        min-height: 400px;
    }
}
```

---

## 🌐 Translation and i18n

### WordPress Text Domains

**Always use text domain in translatable strings:**

```php
// ✅ GOOD
__('Hello World', 'soma');
_e('Welcome', 'soma');
_n('%s item', '%s items', $count, 'soma');
esc_html__('Title', 'soma');
esc_attr__('Attribute', 'soma');

// ❌ BAD
__('Hello World'); // Missing text domain
echo 'Welcome'; // Not translatable
```

### String Conventions

**User-facing strings (WordPress content):**
- Written in Spanish (target audience)
- Properly escaped for security
- Use WordPress translation functions

**Developer-facing strings (code, logs, errors):**
- Written in English
- Technical and precise
- Not necessarily translatable

```php
// User-facing (Spanish OK)
echo esc_html__('Bienvenido a FibraSOMA', 'soma');

// Developer-facing (English only)
soma_log_error('Failed to load portfolio items', ['count' => 0]);
```

---

## 📊 Documentation Maintenance

### Version Numbers

**Document versioning:**
```markdown
**Document Version**: 1.0  
**Last Updated**: 2025-12-15
```

**Update version when:**
- 1.0 → 1.1: Minor updates, clarifications
- 1.0 → 2.0: Major restructuring, new sections
- Add date in YYYY-MM-DD format

### Review Schedule

**Documentation should be reviewed:**
- When code changes affect documented behavior
- When users report confusion
- Quarterly for accuracy
- Before major releases

### Deprecation Notices

**Mark deprecated content clearly:**

```markdown
## Old Method (Deprecated)

**⚠️ DEPRECATED**: This method is deprecated as of v3.0.0.  
**Use instead**: [New Method](#new-method)  
**Removal planned**: v4.0.0

\`\`\`php
// Old way (don't use)
global $pageBlock;
\`\`\`
```

---

## ✅ Documentation Checklist

**Before committing documentation:**

- [ ] Written in English (except user-facing Spanish content)
- [ ] Spell-checked (use VS Code spell checker)
- [ ] Grammar-checked
- [ ] Code examples tested and working
- [ ] Links verified (no broken links)
- [ ] Proper Markdown formatting
- [ ] Headers properly structured (H1 → H2 → H3)
- [ ] Table of contents updated (if applicable)
- [ ] Version number updated
- [ ] Last updated date updated
- [ ] Consistent terminology used
- [ ] Acronyms defined on first use
- [ ] Screenshots updated (if UI changed)

---

## 🛠️ Tools and Extensions

### Recommended VS Code Extensions

**For Documentation:**
- `yzhang.markdown-all-in-one` - Markdown formatting and shortcuts
- `DavidAnson.vscode-markdownlint` - Markdown linting
- `streetsidesoftware.code-spell-checker` - Spell checking
- `bierner.markdown-preview-github-styles` - GitHub-style preview

**Configuration:**
```json
{
    "markdown.extension.toc.levels": "2..3",
    "markdown.extension.toc.updateOnSave": true,
    "cSpell.language": "en",
    "cSpell.words": [
        "FibraSOMA",
        "SOMA",
        "Elementor",
        "PHPCS",
        "PHPStan"
    ]
}
```

### Markdown Linting

**Configure `.markdownlint.json`:**
```json
{
    "default": true,
    "MD013": false,
    "MD033": false,
    "MD041": false
}
```

---

## 📚 Additional Resources

### Writing Resources
- [Google Developer Documentation Style Guide](https://developers.google.com/style)
- [Microsoft Writing Style Guide](https://docs.microsoft.com/style-guide/)
- [WordPress Documentation Standards](https://make.wordpress.org/docs/style-guide/)

### Markdown Resources
- [GitHub Flavored Markdown Spec](https://github.github.com/gfm/)
- [Markdown Guide](https://www.markdownguide.org/)
- [CommonMark Spec](https://spec.commonmark.org/)

### Code Documentation
- [PHPDoc Standards](https://docs.phpdoc.org/guide/references/phpdoc/)
- [JSDoc Reference](https://jsdoc.app/)
- [WordPress Inline Documentation Standards](https://developer.wordpress.org/coding-standards/inline-documentation-standards/)

---

**Last Updated**: December 15, 2025  
**Maintained By**: Miguel Colmenares  
**Repository**: https://github.com/sanruiz/fibra
