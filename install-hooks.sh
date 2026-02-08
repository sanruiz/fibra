#!/bin/bash
#
# SOMA Theme - Git Hooks Installation Script
# 
# This script installs Git hooks to enforce branch protection and workflow compliance.
# Run once after cloning the repository.
#
# Protected branches:
#   - main: Production branch (only via PR from develop or hotfix/*)
#   - develop: Development branch (only via PR from feature/fix)
#
# Usage:
#   ./install-hooks.sh
#

echo ""
echo "🔧 ════════════════════════════════════════════════════════════"
echo "   SOMA Theme - Git Hooks Installation"
echo "════════════════════════════════════════════════════════════"
echo ""

# Check if .git directory exists
if [ ! -d ".git" ]; then
    echo "❌ Error: .git directory not found."
    echo "   Make sure you're in the root of the Git repository."
    echo ""
    exit 1
fi

# Check if .githooks directory exists
if [ ! -d ".githooks" ]; then
    echo "❌ Error: .githooks directory not found."
    echo "   Repository structure may be corrupted."
    echo ""
    exit 1
fi

# Copy hooks and make them executable
echo "📦 Installing Git hooks..."
echo ""

HOOKS_INSTALLED=0

for hook in .githooks/*; do
    if [ -f "$hook" ]; then
        hook_name=$(basename "$hook")
        echo "   📋 Installing $hook_name hook..."
        
        # Copy hook to .git/hooks/
        cp "$hook" ".git/hooks/$hook_name"
        
        # Make executable
        chmod +x ".git/hooks/$hook_name"
        
        echo "   ✅ $hook_name hook installed and made executable"
        echo ""
        
        HOOKS_INSTALLED=$((HOOKS_INSTALLED + 1))
    fi
done

# Summary
echo "════════════════════════════════════════════════════════════"
echo "🎉 Git hooks installation completed!"
echo ""
echo "📊 Summary:"
echo "   • $HOOKS_INSTALLED hook(s) installed"
echo ""
echo "🔒 Active Protections:"
echo "   • pre-commit: Blocks direct commits to protected branches"
echo "   • pre-push: Blocks pushing v* tags from non-main branches"
echo ""
echo "🚫 Protected Branches:"
echo "   • main - Production (only via PR from develop or hotfix/*)"
echo "   • develop - Development (only via PR from feature/fix)"
echo ""
echo "🏷️  Tag Protection:"
echo "   • Version tags (v*) can ONLY be pushed from 'main' branch"
echo "   • This prevents orphaned tags caused by squash merge"
echo ""
echo "✅ Allowed Branches:"
echo "   • feature/* - New features"
echo "   • fix/* - Bug fixes"
echo "   • hotfix/* - Emergency fixes"
echo "   • chore/* - Maintenance tasks"
echo "   • refactor/* - Code refactoring"
echo "   • release/* - Release preparation"
echo ""
echo "📚 Documentation:"
echo "   • Workflow guide: .github/instructions/github-workflow.instructions.md"
echo "   • Copilot guide: .github/copilot-instructions.md"
echo ""
echo "════════════════════════════════════════════════════════════"
echo ""
echo "🚀 You're all set! Happy coding with protected branches."
echo ""
