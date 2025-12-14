#!/bin/bash

# Soma Theme Validation Script
# Runs all quality checks: PHPCS, PHPStan, PHPUnit

set -e

THEME_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")/.." && pwd)"
cd "$THEME_DIR"

echo "🔍 Soma Theme Validation"
echo "========================"
echo ""

# Colors
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
NC='\033[0m' # No Color

# Check if vendor exists
if [ ! -d "vendor" ]; then
    echo -e "${RED}❌ Vendor directory not found. Run 'composer install' first.${NC}"
    exit 1
fi

# Track failures
FAILURES=0

# 1. PHP CodeSniffer
echo -e "${YELLOW}1. Running PHPCS (WordPress Coding Standards)...${NC}"
if vendor/bin/phpcs; then
    echo -e "${GREEN}✅ PHPCS passed${NC}"
else
    echo -e "${RED}❌ PHPCS found errors${NC}"
    FAILURES=$((FAILURES + 1))
fi
echo ""

# 2. PHPStan
echo -e "${YELLOW}2. Running PHPStan (Static Analysis)...${NC}"
if vendor/bin/phpstan analyse --memory-limit=512M; then
    echo -e "${GREEN}✅ PHPStan passed${NC}"
else
    echo -e "${RED}❌ PHPStan found errors${NC}"
    FAILURES=$((FAILURES + 1))
fi
echo ""

# 3. PHPUnit
echo -e "${YELLOW}3. Running PHPUnit (Unit Tests)...${NC}"
if [ -d "tests/Unit" ] && [ "$(ls -A tests/Unit 2>/dev/null)" ]; then
    if vendor/bin/phpunit; then
        echo -e "${GREEN}✅ PHPUnit passed${NC}"
    else
        echo -e "${RED}❌ PHPUnit tests failed${NC}"
        FAILURES=$((FAILURES + 1))
    fi
else
    echo -e "${YELLOW}⚠️  No unit tests found yet${NC}"
fi
echo ""

# Summary
echo "========================"
if [ $FAILURES -eq 0 ]; then
    echo -e "${GREEN}✅ All checks passed!${NC}"
    exit 0
else
    echo -e "${RED}❌ $FAILURES check(s) failed${NC}"
    exit 1
fi
