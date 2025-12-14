#!/usr/bin/env bash
#
# Elementor Installation Script for Testing
#
# This script downloads and installs Elementor in the WordPress test environment
# for integration testing purposes.
#
# @package Soma
# @since 3.0.0
#
# Usage:
#   bash tests/bin/install-elementor-for-tests.sh
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

print_header "Elementor Test Installation"

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

ELEMENTOR_PLUGIN_DIR="$WP_PLUGINS_DIR/elementor"

# Check if Elementor is already installed
if [ -d "$ELEMENTOR_PLUGIN_DIR" ]; then
    print_success "Elementor already installed at: $ELEMENTOR_PLUGIN_DIR"
    
    # Check version
    if [ -f "$ELEMENTOR_PLUGIN_DIR/elementor.php" ]; then
        ELEMENTOR_VERSION=$(grep "Version:" "$ELEMENTOR_PLUGIN_DIR/elementor.php" | head -1 | sed 's/.*Version: //' | sed 's/ .*//')
        echo "Current version: $ELEMENTOR_VERSION"
    fi
    
    echo ""
    
    # Check for non-interactive mode
    if [[ "$FORCE_ELEMENTOR_REINSTALL" == "true" ]] || [[ "$CI" == "true" ]] || [[ "$GITHUB_ACTIONS" == "true" ]]; then
        print_warning "Non-interactive mode: Automatically reinstalling Elementor"
        REPLY="y"
    else
        read -p "Do you want to reinstall Elementor? (y/N): " -n 1 -r
        echo
    fi
    
    if [[ ! $REPLY =~ ^[Yy]$ ]]; then
        print_success "Using existing Elementor installation"
        exit 0
    fi
    
    print_warning "Removing existing Elementor..."
    rm -rf "$ELEMENTOR_PLUGIN_DIR"
fi

print_header "Downloading Elementor"

# Create temporary directory
TEMP_DIR=$(mktemp -d)
ELEMENTOR_ZIP="$TEMP_DIR/elementor.zip"

# Download latest Elementor from WordPress.org
echo "Downloading from WordPress.org repository..."
if command -v curl >/dev/null 2>&1; then
    curl -L "https://downloads.wordpress.org/plugin/elementor.latest-stable.zip" -o "$ELEMENTOR_ZIP"
elif command -v wget >/dev/null 2>&1; then
    wget "https://downloads.wordpress.org/plugin/elementor.latest-stable.zip" -O "$ELEMENTOR_ZIP"
else
    print_error "Neither curl nor wget found. Please install one of them."
    exit 1
fi

# Verify download
if [ ! -f "$ELEMENTOR_ZIP" ] || [ ! -s "$ELEMENTOR_ZIP" ]; then
    print_error "Failed to download Elementor"
    exit 1
fi

print_success "Elementor downloaded successfully"

print_header "Installing Elementor"

# Extract the plugin
cd "$WP_PLUGINS_DIR"
if command -v unzip >/dev/null 2>&1; then
    unzip -q "$ELEMENTOR_ZIP"
else
    print_error "unzip command not found. Please install unzip."
    exit 1
fi

# Verify installation
if [ ! -d "$ELEMENTOR_PLUGIN_DIR" ]; then
    print_error "Elementor extraction failed"
    exit 1
fi

if [ ! -f "$ELEMENTOR_PLUGIN_DIR/elementor.php" ]; then
    print_error "Elementor main file not found"
    exit 1
fi

# Get installed version
ELEMENTOR_VERSION=$(grep "Version:" "$ELEMENTOR_PLUGIN_DIR/elementor.php" | head -1 | sed 's/.*Version: //' | sed 's/ .*//')

print_success "Elementor v$ELEMENTOR_VERSION installed successfully"

# Clean up temporary files
rm -rf "$TEMP_DIR"

print_header "Verifying Installation"

# Check main plugin file
if [ -f "$ELEMENTOR_PLUGIN_DIR/elementor.php" ]; then
    print_success "Main plugin file: elementor.php"
fi

# Check for key Elementor classes/directories
ELEMENTOR_DIRS=(
    "includes"
    "includes/widgets"
    "core"
    "assets"
)

for dir in "${ELEMENTOR_DIRS[@]}"; do
    if [ -d "$ELEMENTOR_PLUGIN_DIR/$dir" ]; then
        print_success "Found: $dir/"
    else
        print_warning "Missing: $dir/"
    fi
done

print_header "Integration Test Setup"

# Create Elementor integration test helper
ELEMENTOR_TEST_HELPER="$ELEMENTOR_PLUGIN_DIR/elementor-test-helper.php"
cat > "$ELEMENTOR_TEST_HELPER" << 'EOF'
<?php
/**
 * Elementor Test Helper
 * 
 * Helper functions to load Elementor in WordPress test environment
 */

// Ensure Elementor is loaded for tests
if (!defined('ELEMENTOR_PATH')) {
    define('ELEMENTOR_PATH', __DIR__ . '/');
}

if (!defined('ELEMENTOR_URL')) {
    define('ELEMENTOR_URL', plugins_url('/', __FILE__));
}

if (!defined('ELEMENTOR_ASSETS_URL')) {
    define('ELEMENTOR_ASSETS_URL', ELEMENTOR_URL . 'assets/');
}

if (!defined('ELEMENTOR_VERSION')) {
    // Get version from main file
    $plugin_data = get_file_data(__DIR__ . '/elementor.php', array('Version' => 'Version'));
    define('ELEMENTOR_VERSION', $plugin_data['Version']);
}

// Load Elementor main file if not already loaded
if (!class_exists('\Elementor\Plugin')) {
    require_once __DIR__ . '/elementor.php';
}

// Initialize Elementor for testing
add_action('plugins_loaded', function() {
    if (!did_action('elementor/loaded')) {
        do_action('elementor/loaded');
    }
}, 1);

/**
 * Get Elementor plugin instance
 * 
 * @return \Elementor\Plugin|null
 */
function get_elementor_instance() {
    if (class_exists('\Elementor\Plugin')) {
        return \Elementor\Plugin::instance();
    }
    return null;
}

/**
 * Register a test Elementor widget
 * 
 * @param string $widget_class Widget class name (must extend \Elementor\Widget_Base)
 * @return bool Success status
 */
function register_test_elementor_widget($widget_class) {
    if (!class_exists($widget_class)) {
        return false;
    }
    
    $elementor = get_elementor_instance();
    if (!$elementor) {
        return false;
    }
    
    $widgets_manager = $elementor->widgets_manager;
    if (!$widgets_manager) {
        return false;
    }
    
    try {
        $widgets_manager->register_widget_type(new $widget_class());
        return true;
    } catch (Exception $e) {
        return false;
    }
}

/**
 * Unregister a test Elementor widget
 * 
 * @param string $widget_name Widget name/ID
 * @return bool Success status
 */
function unregister_test_elementor_widget($widget_name) {
    $elementor = get_elementor_instance();
    if (!$elementor) {
        return false;
    }
    
    $widgets_manager = $elementor->widgets_manager;
    if (!$widgets_manager) {
        return false;
    }
    
    return $widgets_manager->unregister_widget_type($widget_name);
}

/**
 * Get all registered Elementor widgets
 * 
 * @return array Array of widget instances
 */
function get_registered_elementor_widgets() {
    $elementor = get_elementor_instance();
    if (!$elementor) {
        return array();
    }
    
    $widgets_manager = $elementor->widgets_manager;
    if (!$widgets_manager) {
        return array();
    }
    
    return $widgets_manager->get_widget_types();
}

/**
 * Check if a specific Elementor widget is registered
 * 
 * @param string $widget_name Widget name/ID
 * @return bool
 */
function is_elementor_widget_registered($widget_name) {
    $widgets = get_registered_elementor_widgets();
    return isset($widgets[$widget_name]);
}

/**
 * Create a test post with Elementor data
 * 
 * @param array $elementor_data Elementor content data
 * @param array $post_args Additional post arguments
 * @return int|WP_Error Post ID on success, WP_Error on failure
 */
function create_test_elementor_post($elementor_data = array(), $post_args = array()) {
    $defaults = array(
        'post_title'   => 'Test Elementor Post',
        'post_content' => '',
        'post_status'  => 'publish',
        'post_type'    => 'page',
    );
    
    $post_args = array_merge($defaults, $post_args);
    $post_id = wp_insert_post($post_args);
    
    if (is_wp_error($post_id)) {
        return $post_id;
    }
    
    // Enable Elementor for this post
    update_post_meta($post_id, '_elementor_edit_mode', 'builder');
    
    // Set Elementor data
    if (!empty($elementor_data)) {
        update_post_meta($post_id, '_elementor_data', json_encode($elementor_data));
    }
    
    // Set Elementor version
    update_post_meta($post_id, '_elementor_version', ELEMENTOR_VERSION);
    
    return $post_id;
}

/**
 * Get Elementor data from a post
 * 
 * @param int $post_id Post ID
 * @return array Elementor data
 */
function get_test_elementor_data($post_id) {
    $data = get_post_meta($post_id, '_elementor_data', true);
    
    if (empty($data)) {
        return array();
    }
    
    if (is_string($data)) {
        $data = json_decode($data, true);
    }
    
    return is_array($data) ? $data : array();
}
EOF

print_success "Created Elementor test helper: elementor-test-helper.php"

print_header "Installation Complete"

echo "Elementor Installation Summary:"
echo "- Version: $ELEMENTOR_VERSION"
echo "- Location: $ELEMENTOR_PLUGIN_DIR"
echo "- Test Helper: $ELEMENTOR_TEST_HELPER"
echo ""
echo "To use Elementor in tests, add this to your test bootstrap.php:"
echo ""
echo "    if (file_exists('$ELEMENTOR_TEST_HELPER')) {"
echo "        require_once '$ELEMENTOR_TEST_HELPER';"
echo "    }"
echo ""

print_success "Elementor ready for integration testing! 🚀"
