#!/usr/bin/env bash
#
# Advanced Custom Fields PRO Installation Script for Testing
#
# This script sets up ACF PRO in the WordPress test environment
# for integration testing purposes.
#
# @package Soma
# @since 3.0.0
#
# Usage:
#   bash tests/bin/install-acf-for-tests.sh
#

set -e

# Colors for output
RED='\033[0;31m'
GREEN='\033[0;32m'
BLUE='\033[0;34m'
YELLOW='\033[1;33m'
NC='\033[0m' # No Color

# Print functions
print_header() {
    echo -e "\n${BLUE}$1${NC}"
    echo "=========================================="
}

print_success() {
    echo -e "${GREEN}✅ $1${NC}"
}

print_warning() {
    echo -e "${YELLOW}⚠️  $1${NC}"
}

print_error() {
    echo -e "${RED}❌ $1${NC}"
}

# Get WordPress test installation directory
WP_TESTS_DIR="${WP_TESTS_DIR:-$(php -r 'echo sys_get_temp_dir();')/wordpress-tests-lib}"
WP_CORE_DIR="$(dirname "${WP_TESTS_DIR}")/wordpress"
WP_PLUGINS_DIR="$WP_CORE_DIR/wp-content/plugins"

print_header "ACF PRO Test Installation"

echo "WordPress Core Dir: $WP_CORE_DIR"
echo "WordPress Plugins Dir: $WP_PLUGINS_DIR"
echo ""

# Check if WordPress test environment exists
if [ ! -d "$WP_CORE_DIR" ]; then
    print_error "WordPress test environment not found at: $WP_CORE_DIR"
    echo "Please run: bash tests/bin/install-wp-tests.sh soma_test root '' localhost latest"
    exit 1
fi

# Create plugins directory if it doesn't exist
if [ ! -d "$WP_PLUGINS_DIR" ]; then
    print_warning "Creating plugins directory: $WP_PLUGINS_DIR"
    mkdir -p "$WP_PLUGINS_DIR"
fi

ACF_PLUGIN_DIR="$WP_PLUGINS_DIR/advanced-custom-fields-pro"

# Get the actual ACF PRO plugin directory from the live site
# Navigate from tests/bin/ to wp-content/plugins/advanced-custom-fields-pro
SCRIPT_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)"
THEME_DIR="$(cd "$SCRIPT_DIR/../.." && pwd)"
LIVE_ACF_DIR="$(cd "$THEME_DIR/../../plugins/advanced-custom-fields-pro" 2>/dev/null && pwd || echo "")"

if [ ! -d "$LIVE_ACF_DIR" ] || [ -z "$LIVE_ACF_DIR" ]; then
    print_error "ACF PRO not found in live site"
    print_warning "Expected location: wp-content/plugins/advanced-custom-fields-pro"
    print_warning "Please install ACF PRO on your WordPress site first"
    exit 1
fi

print_header "Copying ACF PRO from Live Site"

# Check if ACF is already in test environment
if [ -d "$ACF_PLUGIN_DIR" ]; then
    print_warning "ACF PRO already installed in test environment"
    
    # Check for non-interactive mode
    if [[ "$FORCE_ACF_REINSTALL" == "true" ]] || [[ "$CI" == "true" ]] || [[ "$GITHUB_ACTIONS" == "true" ]]; then
        print_warning "Non-interactive mode: Automatically reinstalling ACF PRO"
        REPLY="y"
    else
        read -p "Do you want to reinstall ACF PRO? (y/N): " -n 1 -r
        echo
    fi
    
    if [[ ! $REPLY =~ ^[Yy]$ ]]; then
        print_success "Using existing ACF PRO installation"
        exit 0
    fi
    
    print_warning "Removing existing ACF PRO..."
    rm -rf "$ACF_PLUGIN_DIR"
fi

# Copy ACF PRO from live site
print_success "Copying ACF PRO from: $LIVE_ACF_DIR"
cp -R "$LIVE_ACF_DIR" "$ACF_PLUGIN_DIR"

# Verify installation
if [ ! -d "$ACF_PLUGIN_DIR" ]; then
    print_error "ACF PRO copy failed"
    exit 1
fi

if [ ! -f "$ACF_PLUGIN_DIR/acf.php" ]; then
    print_error "ACF PRO main file not found"
    exit 1
fi

# Get installed version
ACF_VERSION=$(grep "Version:" "$ACF_PLUGIN_DIR/acf.php" | head -1 | sed 's/.*Version: //' | sed 's/ .*//')

print_success "ACF PRO v$ACF_VERSION installed successfully"

print_header "Verifying Installation"

# Check main plugin file
if [ -f "$ACF_PLUGIN_DIR/acf.php" ]; then
    print_success "Main plugin file: acf.php"
fi

# Check for key ACF directories
ACF_DIRS=(
    "includes"
    "includes/fields"
    "includes/forms"
    "includes/admin"
)

for acf_dir in "${ACF_DIRS[@]}"; do
    if [ -d "$ACF_PLUGIN_DIR/$acf_dir" ]; then
        print_success "Found: $acf_dir/"
    else
        print_warning "Missing: $acf_dir/"
    fi
done

print_header "Integration Test Setup"

# Create ACF test helper
ACF_TEST_HELPER="$ACF_PLUGIN_DIR/acf-test-helper.php"
cat > "$ACF_TEST_HELPER" << 'EOF'
<?php
/**
 * Advanced Custom Fields Test Helper
 * 
 * Helper functions to load ACF in WordPress test environment
 */

// Ensure ACF is loaded for tests
if (!defined('ACF')) {
    define('ACF', true);
}

if (!defined('ACF_PATH')) {
    define('ACF_PATH', __DIR__ . '/');
}

if (!defined('ACF_VERSION')) {
    define('ACF_VERSION', '6.0.0'); // Will be overridden by actual version
}

// Load ACF main file if not already loaded
if (!function_exists('acf')) {
    require_once __DIR__ . '/acf.php';
}

// Helper function to create test field group
function create_test_field_group($title = 'Test Field Group', $fields = array()) {
    if (empty($fields)) {
        $fields = array(
            array(
                'key' => 'field_test_text',
                'label' => 'Test Text',
                'name' => 'test_text',
                'type' => 'text',
            ),
        );
    }
    
    $field_group = array(
        'key' => 'group_test_' . uniqid(),
        'title' => $title,
        'fields' => $fields,
        'location' => array(
            array(
                array(
                    'param' => 'post_type',
                    'operator' => '==',
                    'value' => 'post',
                ),
            ),
        ),
    );
    
    return acf_add_local_field_group($field_group);
}

// Helper function to get ACF field
function get_test_acf_field($field_name, $post_id = false) {
    return get_field($field_name, $post_id);
}

// Helper function to update ACF field
function update_test_acf_field($field_name, $value, $post_id) {
    return update_field($field_name, $value, $post_id);
}
EOF

print_success "Created ACF test helper: acf-test-helper.php"

print_header "Installation Complete"

echo "ACF PRO Installation Summary:"
echo "- Version: $ACF_VERSION"
echo "- Location: $ACF_PLUGIN_DIR"
echo "- Test Helper: $ACF_TEST_HELPER"
echo ""
echo "To use ACF in tests, add this to your test setUp():"
echo ""
echo "    if (file_exists('$ACF_TEST_HELPER')) {"
echo "        require_once '$ACF_TEST_HELPER';"
echo "    }"
echo ""

print_success "ACF PRO ready for integration testing! 🚀"
