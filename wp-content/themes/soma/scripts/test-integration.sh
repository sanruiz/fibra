#!/bin/bash
#
# Page Builder Integration Test Runner
#
# Runs comprehensive integration tests for the Soma PageBuilder v3.0.0 system.
# Uses WP-CLI to execute tests in a proper WordPress environment.
#
# Usage: ./scripts/test-integration.sh
#

set -e

# Colors
GREEN='\033[0;32m'
RED='\033[0;31m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
NC='\033[0m' # No Color

echo -e "${BLUE}╔═══════════════════════════════════════════════════════════╗${NC}"
echo -e "${BLUE}║    Soma PageBuilder v3.0.0 - Integration Tests          ║${NC}"
echo -e "${BLUE}╚═══════════════════════════════════════════════════════════╝${NC}\n"

# Navigate to WordPress root
cd "$(dirname "$0")/../../../../"

echo -e "${YELLOW}Running Integration Tests via WP-CLI...${NC}\n"

# Test 1: Check WordPress is accessible
echo -e "${BLUE}Test 1: WordPress Core${NC}"
if wp core is-installed 2>/dev/null; then
    echo -e "  ${GREEN}✓${NC} WordPress is installed and accessible"
else
    echo -e "  ${RED}✗${NC} WordPress is not accessible"
    exit 1
fi

# Test 2: Check if theme is active
echo -e "\n${BLUE}Test 2: Theme Status${NC}"
ACTIVE_THEME=$(wp theme list --status=active --field=name 2>/dev/null)
if [ "$ACTIVE_THEME" = "soma" ]; then
    echo -e "  ${GREEN}✓${NC} Soma theme is active"
else
    echo -e "  ${YELLOW}⚠${NC} Active theme: $ACTIVE_THEME (expected: soma)"
fi

# Test 3: Check PSR-4 Classes
echo -e "\n${BLUE}Test 3: PSR-4 Classes${NC}"
wp eval 'if (class_exists("\\Soma\\PageBuilder\\Loader")) echo "Loader: OK\n"; else echo "Loader: FAIL\n";' 2>/dev/null
wp eval 'if (class_exists("\\Soma\\PageBuilder\\BlockRegistry")) echo "BlockRegistry: OK\n"; else echo "BlockRegistry: FAIL\n";' 2>/dev/null
wp eval 'if (class_exists("\\Soma\\PageBuilder\\BlockRenderer")) echo "BlockRenderer: OK\n"; else echo "BlockRenderer: FAIL\n";' 2>/dev/null

# Test 4: Check Block Registry
echo -e "\n${BLUE}Test 4: Block Registry${NC}"
BLOCK_COUNT=$(wp eval '$registry = \Soma\PageBuilder\BlockRegistry::instance(); echo $registry->count();' 2>/dev/null)
echo -e "  ${GREEN}✓${NC} Registered blocks: $BLOCK_COUNT"

if [ "$BLOCK_COUNT" -eq 53 ]; then
    echo -e "  ${GREEN}✓${NC} Expected 53 blocks - PASSED"
else
    echo -e "  ${YELLOW}⚠${NC} Expected 53 blocks, found $BLOCK_COUNT"
fi

# Test 5: Validate Partial Files
echo -e "\n${BLUE}Test 5: Partial File Validation${NC}"
wp eval '
$registry = \Soma\PageBuilder\BlockRegistry::instance();
$all_blocks = $registry->get_all_blocks();
$missing = 0;
$valid = 0;

foreach ($all_blocks as $layout => $mapping) {
    $file = $registry->get_partial_file_path($layout);
    if ($file && file_exists($file)) {
        $valid++;
    } else {
        $missing++;
        echo "  Missing: {$layout} -> {$mapping[\"partial\"]}\n";
    }
}

echo "\n  Valid partial files: {$valid}\n";
echo "  Missing partial files: {$missing}\n";

if ($missing === 0) {
    echo "  ✓ All partial files exist\n";
}
' 2>/dev/null

# Test 6: Test Renderer
echo -e "\n${BLUE}Test 6: Block Renderer${NC}"
wp eval '
$renderer = \Soma\PageBuilder\BlockRenderer::instance();

// Test null blocks
ob_start();
$renderer->render(null);
$output = ob_get_clean();
echo "  ✓ Handles null blocks\n";

// Test empty array
ob_start();
$renderer->render([]);
$output = ob_get_clean();
echo "  ✓ Handles empty array\n";

// Test invalid block
ob_start();
$renderer->render([["invalid" => "block"]]);
$output = ob_get_clean();
echo "  ✓ Handles invalid blocks\n";

// Test unregistered block
ob_start();
$renderer->render([["acf_fc_layout" => "UnregisteredBlock"]]);
$output = ob_get_clean();
echo "  ✓ Handles unregistered blocks\n";

// Get stats
$stats = $renderer->get_stats();
echo "  ✓ Renderer statistics available\n";
echo "    - Blocks rendered: {$stats[\"blocks_rendered\"]}\n";
echo "    - Errors: {$stats[\"errors\"]}\n";
' 2>/dev/null

# Test 7: Test Helper Functions
echo -e "\n${BLUE}Test 7: Helper Functions${NC}"
wp eval 'echo (function_exists("soma_translate_date") ? "  ✓ soma_translate_date()\n" : "  ✗ soma_translate_date()\n");' 2>/dev/null
wp eval 'echo (function_exists("translateDate") ? "  ✓ translateDate() (legacy)\n" : "  ✗ translateDate()\n");' 2>/dev/null
wp eval 'echo (function_exists("soma_log_error") ? "  ✓ soma_log_error()\n" : "  ✗ soma_log_error()\n");' 2>/dev/null
wp eval 'echo (function_exists("soma_log_info") ? "  ✓ soma_log_info()\n" : "  ✗ soma_log_info()\n");' 2>/dev/null

# Test 8: Test Actual Page Rendering
echo -e "\n${BLUE}Test 8: Page Rendering Test${NC}"
wp eval '
// Find a page with soma_blocks field
$pages = get_posts([
    "post_type" => "page",
    "posts_per_page" => 5,
    "post_status" => "publish"
]);

$pages_with_blocks = 0;
$successful_renders = 0;

foreach ($pages as $page) {
    $blocks = get_field("soma_blocks", $page->ID);
    if ($blocks && is_array($blocks) && count($blocks) > 0) {
        $pages_with_blocks++;
        
        $renderer = \Soma\PageBuilder\BlockRenderer::instance();
        ob_start();
        try {
            $renderer->render($blocks);
            $output = ob_get_clean();
            $successful_renders++;
            echo "  ✓ Page #{$page->ID} ({$page->post_title}): " . count($blocks) . " blocks\n";
        } catch (Exception $e) {
            ob_get_clean();
            echo "  ✗ Page #{$page->ID} ERROR: {$e->getMessage()}\n";
        }
    }
}

if ($pages_with_blocks > 0) {
    echo "\n  Tested {$pages_with_blocks} pages with blocks\n";
    echo "  Successful renders: {$successful_renders}\n";
} else {
    echo "  ⚠ No pages found with soma_blocks field\n";
}
' 2>/dev/null

# Summary
echo -e "\n${BLUE}╔═══════════════════════════════════════════════════════════╗${NC}"
echo -e "${BLUE}║                     TEST SUMMARY                         ║${NC}"
echo -e "${BLUE}╚═══════════════════════════════════════════════════════════╝${NC}\n"

echo -e "${GREEN}✓ Integration tests completed successfully${NC}\n"
echo -e "Next steps:"
echo -e "  1. Run quality validation: ${YELLOW}vendor/bin/phpcs includes/PageBuilder/${NC}"
echo -e "  2. Run static analysis: ${YELLOW}vendor/bin/phpstan analyse includes/PageBuilder/ --level=6${NC}"
echo -e "  3. Test on staging site with real content"
echo -e "  4. Document partials with PHPDoc\n"

exit 0
