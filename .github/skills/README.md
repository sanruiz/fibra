# GitHub Copilot Agent Skills

This directory contains Agent Skills for the FibraSOMA WordPress project. Agent Skills provide specialized, context-aware guidance to GitHub Copilot when performing specific tasks.

## What are Agent Skills?

Agent Skills are self-contained instructions that Copilot loads dynamically based on the context of your prompt. Unlike Custom Instructions (which load based on file patterns), Skills activate when their capabilities are relevant to what you're asking.

## Available Skills

| Skill | Description | Trigger Example |
|-------|-------------|-----------------|
| `elementor-widget-creation` | Create new Elementor widgets following SOMA standards | "Create a new Elementor widget for testimonials" |
| `phpunit-testing` | Write PHPUnit tests for WordPress components | "Write tests for the Portfolio post type" |
| `github-release-workflow` | Guide through the GitFlow release process | "Help me create a release for v3.2.0" |
| `acf-block-development` | Create ACF flexible content blocks | "Create a new ACF block for FAQs" |
| `github-actions-debugging` | Troubleshoot CI/CD workflow issues | "Why is my CI pipeline failing?" |

## Skill Structure

Each skill follows this structure:

```
skills/
└── skill-name/
    ├── SKILL.md           # Required: Skill definition with YAML frontmatter
    ├── templates/         # Optional: Code templates
    └── examples/          # Optional: Example implementations
```

### SKILL.md Format

```markdown
---
name: Skill Display Name
description: What this skill does
---

# Instructions for Copilot

Detailed guidance, templates, and best practices...
```

## How Skills Differ from Custom Instructions

| Feature | Custom Instructions | Agent Skills |
|---------|-------------------|--------------|
| **Activation** | Based on file patterns (`applyTo`) | Based on prompt context |
| **Location** | `.github/instructions/` | `.github/skills/` |
| **Loading** | Always active for matching files | Dynamic, on-demand |
| **Resources** | Markdown only | Includes scripts, templates, examples |
| **Use Case** | Coding standards, file conventions | Complex workflows, task guidance |

## Maintaining Skills

1. **Keep skills focused** - Each skill should address one specific workflow
2. **Include examples** - Real code examples help Copilot generate accurate responses
3. **Update regularly** - Keep templates aligned with current project patterns
4. **Test prompts** - Verify skills activate correctly with sample prompts

## Related Documentation

- [Custom Instructions](.github/instructions/README.md)
- [SOMA Development Guide](wp-content/themes/soma/docs/DEVELOPMENT.md)
- [Elementor Widgets Reference](wp-content/themes/soma/docs/WIDGETS.md)
- [Testing Guide](wp-content/themes/soma/docs/TESTING_GUIDE.md)

---

**Project**: FibraSOMA WordPress Theme  
**Last Updated**: January 2026
