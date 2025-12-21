<?php
/**
 * SOMA Theme Auto-Extractor
 * 
 * This script automatically extracts the SOMA theme from a ZIP file
 * and places it in the correct directory.
 * 
 * Usage:
 * 1. Upload this file to: public_html/wp-content/themes/
 * 2. Upload the theme ZIP to the same folder
 * 3. Visit: https://yourdomain.com/wp-content/themes/soma-extractor.php?zip=soma-v3.0.0.zip
 * 
 * @version 1.0
 * @author Miguel Colmenares
 */

// Configuration
define('ALLOW_EXECUTION', true); // Set to false to disable
define('LOG_FILE', 'soma-extractor.log');
define('MAX_EXECUTION_TIME', 300); // 5 minutes

// Security headers
header('Content-Type: text/plain; charset=utf-8');
header('X-Robots-Tag: noindex, nofollow');

// Validate execution is allowed
if (!ALLOW_EXECUTION) {
    die("❌ Extractor disabled. Set ALLOW_EXECUTION to true to enable.\n");
}

// Increase execution time
set_time_limit(MAX_EXECUTION_TIME);

/**
 * Logging function
 */
function log_message($message) {
    $timestamp = date('Y-m-d H:i:s');
    $log_entry = "[{$timestamp}] {$message}\n";
    echo $log_entry;
    file_put_contents(LOG_FILE, $log_entry, FILE_APPEND);
}

/**
 * Error function
 */
function error_exit($message) {
    log_message("❌ ERROR: {$message}");
    exit(1);
}

// Banner
echo "╔════════════════════════════════════════════════════════════╗\n";
echo "║           SOMA Theme Auto-Extractor v1.0                   ║\n";
echo "╚════════════════════════════════════════════════════════════╝\n\n";

log_message("🚀 Starting SOMA theme extraction process...");

// Verificar que PHP tenga ZipArchive
if (!class_exists('ZipArchive')) {
    error_exit("ZipArchive class not available. Please install php-zip extension.");
}

log_message("✅ ZipArchive class is available");

// Get ZIP file name from GET parameter
$zip_file = isset($_GET['zip']) ? basename($_GET['zip']) : null;

// If not specified, search for ZIP files starting with "soma-v"
if (!$zip_file) {
    log_message("🔍 Searching for SOMA theme ZIP files...");
    
    $zip_files = glob('soma-v*.zip');
    
    if (empty($zip_files)) {
        error_exit("No ZIP file specified and no soma-v*.zip files found in current directory.");
    }
    
    // Sort by modification date (most recent first)
    usort($zip_files, function($a, $b) {
        return filemtime($b) - filemtime($a);
    });
    
    $zip_file = $zip_files[0];
    log_message("📦 Found ZIP file: {$zip_file}");
}

// Validate ZIP file exists
if (!file_exists($zip_file)) {
    error_exit("ZIP file not found: {$zip_file}");
}

log_message("✅ ZIP file exists: {$zip_file}");
log_message("📊 File size: " . number_format(filesize($zip_file) / 1024 / 1024, 2) . " MB");

// Destination directory
$extract_to = './soma/';

// Create destination directory if it doesn't exist
if (!is_dir($extract_to)) {
    log_message("📁 Creating destination directory: {$extract_to}");
    
    if (!mkdir($extract_to, 0755, true)) {
        error_exit("Failed to create destination directory: {$extract_to}");
    }
    
    log_message("✅ Destination directory created");
} else {
    log_message("✅ Destination directory exists: {$extract_to}");
}

// Validate write permissions
if (!is_writable($extract_to)) {
    error_exit("Destination directory is not writable: {$extract_to}");
}

log_message("✅ Destination directory is writable");

// Start extraction
log_message("🔄 Opening ZIP archive...");

$zip = new ZipArchive;
$result = $zip->open($zip_file);

if ($result !== TRUE) {
    $error_codes = [
        ZipArchive::ER_EXISTS => 'File already exists',
        ZipArchive::ER_INCONS => 'Zip archive inconsistent',
        ZipArchive::ER_INVAL => 'Invalid argument',
        ZipArchive::ER_MEMORY => 'Malloc failure',
        ZipArchive::ER_NOENT => 'No such file',
        ZipArchive::ER_NOZIP => 'Not a zip archive',
        ZipArchive::ER_OPEN => 'Can\'t open file',
        ZipArchive::ER_READ => 'Read error',
        ZipArchive::ER_SEEK => 'Seek error'
    ];
    
    $error_message = isset($error_codes[$result]) ? $error_codes[$result] : "Unknown error code: {$result}";
    error_exit("Failed to open ZIP file: {$error_message}");
}

log_message("✅ ZIP archive opened successfully");
log_message("📋 Number of files in archive: " . $zip->numFiles);

// Extract
log_message("📦 Extracting files to: {$extract_to}");

if (!$zip->extractTo($extract_to)) {
    $zip->close();
    error_exit("Failed to extract ZIP file");
}

$zip->close();

log_message("✅ Files extracted successfully");

// Remove ZIP file
log_message("🗑️ Removing ZIP file: {$zip_file}");

if (unlink($zip_file)) {
    log_message("✅ ZIP file removed successfully");
} else {
    log_message("⚠️ Warning: Could not remove ZIP file: {$zip_file}");
}

// Verify critical theme files
$critical_files = [
    'style.css',
    'functions.php',
    'index.php'
];

log_message("🔍 Verifying critical theme files...");

foreach ($critical_files as $file) {
    $file_path = $extract_to . $file;
    
    if (file_exists($file_path)) {
        log_message("  ✅ {$file}");
    } else {
        log_message("  ⚠️ Missing: {$file}");
    }
}

// Read theme version from style.css
$style_css = $extract_to . 'style.css';
if (file_exists($style_css)) {
    $style_content = file_get_contents($style_css);
    
    if (preg_match('/Version:\s*([0-9.]+)/i', $style_content, $matches)) {
        $theme_version = $matches[1];
        log_message("📊 Theme version: {$theme_version}");
    }
    
    if (preg_match('/Theme Name:\s*(.+)/i', $style_content, $matches)) {
        $theme_name = trim($matches[1]);
        log_message("📝 Theme name: {$theme_name}");
    }
}

// Final statistics
$total_files = count(glob($extract_to . '*', GLOB_BRACE));
$total_size = 0;

$iterator = new RecursiveIteratorIterator(
    new RecursiveDirectoryIterator($extract_to, RecursiveDirectoryIterator::SKIP_DOTS),
    RecursiveIteratorIterator::LEAVES_ONLY
);

foreach ($iterator as $file) {
    if ($file->isFile()) {
        $total_size += $file->getSize();
    }
}

log_message("\n" . str_repeat("=", 60));
log_message("📊 EXTRACTION SUMMARY");
log_message(str_repeat("=", 60));
log_message("Source ZIP: {$zip_file}");
log_message("Destination: {$extract_to}");
log_message("Total files extracted: ~{$total_files}");
log_message("Total size: " . number_format($total_size / 1024 / 1024, 2) . " MB");
log_message("Execution time: " . number_format(microtime(true) - $_SERVER['REQUEST_TIME_FLOAT'], 2) . " seconds");
log_message(str_repeat("=", 60));

// Success
log_message("\n✅ SOMA THEME EXTRACTION COMPLETED SUCCESSFULLY!\n");
log_message("🎉 You can now activate the theme in WordPress admin.");
log_message("📝 Logs saved to: " . LOG_FILE);

// Final instructions
echo "\n╔════════════════════════════════════════════════════════════╗\n";
echo "║                    NEXT STEPS                              ║\n";
echo "╠════════════════════════════════════════════════════════════╣\n";
echo "║ 1. Go to WordPress Admin → Appearance → Themes            ║\n";
echo "║ 2. Activate SOMA theme                                     ║\n";
echo "║ 3. Clear all caches (WordPress, CDN, Browser)             ║\n";
echo "║ 4. Test the website                                        ║\n";
echo "║                                                            ║\n";
echo "║ 🔒 SECURITY: Delete this extractor file after use!        ║\n";
echo "║    rm soma-extractor.php                                   ║\n";
echo "╚════════════════════════════════════════════════════════════╝\n";

// Auto-delete option (commented for security)
// echo "\n⚠️  Auto-deleting extractor file in 5 seconds...\n";
// sleep(5);
// unlink(__FILE__);
// log_message("🗑️ Extractor file deleted");

?>
