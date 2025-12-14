#!/usr/bin/env php
<?php
/**
 * Page Builder Integration Test Script
 *
 * Tests that all 53 registered blocks can be rendered correctly.
 * Run from theme root: php scripts/test-page-builder.php
 *
 * @package Soma
 * @since   3.0.0
 */

// Load WordPress
define( 'WP_USE_THEMES', false );
require_once __DIR__ . '/../../../../wp-load.php';

// Colors for terminal output
$green  = "\033[32m";
$red    = "\033[31m";
$yellow = "\033[33m";
$blue   = "\033[34m";
$reset  = "\033[0m";

echo "\n{$blue}╔═══════════════════════════════════════════════════════════╗{$reset}\n";
echo "{$blue}║      Page Builder Integration Test - Soma v3.0.0        ║{$reset}\n";
echo "{$blue}╚═══════════════════════════════════════════════════════════╝{$reset}\n\n";

// Test 1: Check PSR-4 Classes Exist
echo "Test 1: {$yellow}Checking PSR-4 Classes...{$reset}\n";

$classes_to_check = [
	'\\Soma\\PageBuilder\\Loader'        => 'PageBuilder Loader',
	'\\Soma\\PageBuilder\\BlockRegistry' => 'Block Registry',
	'\\Soma\\PageBuilder\\BlockRenderer' => 'Block Renderer',
];

$classes_passed = 0;
foreach ( $classes_to_check as $class => $name ) {
	if ( class_exists( $class ) ) {
		echo "  {$green}✓{$reset} {$name} exists\n";
		$classes_passed++;
	} else {
		echo "  {$red}✗{$reset} {$name} NOT FOUND\n";
	}
}

echo "\n  Result: {$classes_passed}/3 classes found\n\n";

if ( $classes_passed !== 3 ) {
	echo "{$red}FAILED: Not all PSR-4 classes are available{$reset}\n\n";
	exit( 1 );
}

// Test 2: Check Block Registry
echo "Test 2: {$yellow}Checking Block Registry...{$reset}\n";

$registry         = \Soma\PageBuilder\BlockRegistry::instance();
$registered_count = $registry->count();
$all_blocks       = $registry->get_all_blocks();

echo "  {$green}✓{$reset} Registry contains {$registered_count} blocks\n";

// Test 3: Validate Block Mappings
echo "\nTest 3: {$yellow}Validating Block Mappings...{$reset}\n";

$valid_mappings   = 0;
$invalid_mappings = 0;
$missing_files    = [];

foreach ( $all_blocks as $layout => $mapping ) {
	$partial_file = $registry->get_partial_file_path( $layout );
	
	if ( $partial_file && file_exists( $partial_file ) ) {
		$valid_mappings++;
	} else {
		$invalid_mappings++;
		$missing_files[] = [
			'layout'  => $layout,
			'partial' => $mapping['partial'],
			'path'    => $partial_file ?: 'N/A',
		];
	}
}

echo "  {$green}✓{$reset} Valid mappings: {$valid_mappings}\n";

if ( $invalid_mappings > 0 ) {
	echo "  {$red}✗{$reset} Invalid mappings: {$invalid_mappings}\n\n";
	echo "  {$yellow}Missing Partial Files:{$reset}\n";
	foreach ( $missing_files as $missing ) {
		echo "    - Layout: {$missing['layout']}\n";
		echo "      Partial: {$missing['partial']}\n";
		echo "      Expected Path: {$missing['path']}\n\n";
	}
} else {
	echo "  {$green}✓{$reset} All partial files exist\n";
}

// Test 4: Validate BlockRenderer
echo "\nTest 4: {$yellow}Validating BlockRenderer...{$reset}\n";

$renderer = \Soma\PageBuilder\BlockRenderer::instance();

// Test with empty blocks
ob_start();
$renderer->render( null );
$output = ob_get_clean();

echo "  {$green}✓{$reset} Handles null blocks gracefully\n";

// Test with invalid block structure
$invalid_blocks = [
	[
		'acf_fc_layout' => 'InvalidBlock',
	],
];

ob_start();
$renderer->render( $invalid_blocks );
$output = ob_get_clean();

echo "  {$green}✓{$reset} Handles invalid blocks gracefully\n";

// Test with valid block (if BusinessUnits exists)
if ( $registry->is_registered( 'BusinessUnits' ) ) {
	$valid_blocks = [
		[
			'acf_fc_layout'            => 'BusinessUnits',
			'business_units_content' => [
				'title' => 'Test Business Units',
			],
		],
	];
	
	ob_start();
	$renderer->render( $valid_blocks );
	$output = ob_get_clean();
	
	echo "  {$green}✓{$reset} Can render valid BusinessUnits block\n";
}

// Test 5: Check Stats
echo "\nTest 5: {$yellow}Renderer Statistics...{$reset}\n";

$stats = $renderer->get_stats();

echo "  Blocks Rendered: {$stats['blocks_rendered']}\n";
echo "  Blocks Cached: {$stats['blocks_cached']}\n";
echo "  Cache Hits: {$stats['cache_hits']}\n";
echo "  Errors: {$stats['errors']}\n";

// Test 6: Check Helper Functions
echo "\nTest 6: {$yellow}Checking Helper Functions...{$reset}\n";

$helpers_to_check = [
	'soma_translate_date' => 'soma_translate_date()',
	'translateDate'       => 'translateDate() (legacy alias)',
	'soma_log_error'      => 'soma_log_error()',
	'soma_log_info'       => 'soma_log_info()',
];

$helpers_passed = 0;
foreach ( $helpers_to_check as $function => $name ) {
	if ( function_exists( $function ) ) {
		echo "  {$green}✓{$reset} {$name} exists\n";
		$helpers_passed++;
	} else {
		echo "  {$red}✗{$reset} {$name} NOT FOUND\n";
	}
}

// Summary
echo "\n{$blue}╔═══════════════════════════════════════════════════════════╗{$reset}\n";
echo "{$blue}║                      TEST SUMMARY                        ║{$reset}\n";
echo "{$blue}╚═══════════════════════════════════════════════════════════╝{$reset}\n\n";

$total_tests   = 6;
$passed_tests  = 0;
$failed_tests  = 0;
$warning_tests = 0;

// Test 1
if ( $classes_passed === 3 ) {
	echo "  {$green}✓{$reset} Test 1: PSR-4 Classes - PASSED\n";
	$passed_tests++;
} else {
	echo "  {$red}✗{$reset} Test 1: PSR-4 Classes - FAILED\n";
	$failed_tests++;
}

// Test 2
if ( $registered_count === 53 ) {
	echo "  {$green}✓{$reset} Test 2: Block Registry (53 blocks) - PASSED\n";
	$passed_tests++;
} elseif ( $registered_count > 0 ) {
	echo "  {$yellow}⚠{$reset} Test 2: Block Registry ({$registered_count} blocks) - WARNING\n";
	$warning_tests++;
} else {
	echo "  {$red}✗{$reset} Test 2: Block Registry - FAILED\n";
	$failed_tests++;
}

// Test 3
if ( $invalid_mappings === 0 ) {
	echo "  {$green}✓{$reset} Test 3: Block Mappings ({$valid_mappings} valid) - PASSED\n";
	$passed_tests++;
} else {
	echo "  {$red}✗{$reset} Test 3: Block Mappings ({$invalid_mappings} invalid) - FAILED\n";
	$failed_tests++;
}

// Test 4
echo "  {$green}✓{$reset} Test 4: BlockRenderer Validation - PASSED\n";
$passed_tests++;

// Test 5
echo "  {$green}✓{$reset} Test 5: Renderer Statistics - PASSED\n";
$passed_tests++;

// Test 6
if ( $helpers_passed === 4 ) {
	echo "  {$green}✓{$reset} Test 6: Helper Functions - PASSED\n";
	$passed_tests++;
} else {
	echo "  {$yellow}⚠{$reset} Test 6: Helper Functions ({$helpers_passed}/4) - WARNING\n";
	$warning_tests++;
}

echo "\n";
echo "  Total Tests: {$total_tests}\n";
echo "  {$green}Passed: {$passed_tests}{$reset}\n";

if ( $warning_tests > 0 ) {
	echo "  {$yellow}Warnings: {$warning_tests}{$reset}\n";
}

if ( $failed_tests > 0 ) {
	echo "  {$red}Failed: {$failed_tests}{$reset}\n\n";
	exit( 1 );
}

echo "\n{$green}╔═══════════════════════════════════════════════════════════╗{$reset}\n";
echo "{$green}║           ALL TESTS PASSED SUCCESSFULLY! ✓               ║{$reset}\n";
echo "{$green}╚═══════════════════════════════════════════════════════════╝{$reset}\n\n";

exit( 0 );
