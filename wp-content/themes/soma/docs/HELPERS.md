# SOMA Theme v3.0 - Helper Functions API

**Version**: 3.0.0  
**Last Updated**: December 15, 2025  
**Total Functions**: 28 helper functions

---

## Table of Contents

1. [Overview](#overview)
2. [Logger Helpers](#logger-helpers)
3. [Cache Helpers](#cache-helpers)
4. [Post Type Helpers](#post-type-helpers)
5. [Template Helpers](#template-helpers)
6. [ACF Helpers](#acf-helpers)
7. [Utility Helpers](#utility-helpers)
8. [Translation Helpers](#translation-helpers)
9. [Stock Data Helpers](#stock-data-helpers)
10. [Usage Examples](#usage-examples)
11. [Best Practices](#best-practices)

---

## Overview

SOMA v3.0 provides 28 global helper functions with the `soma_` prefix for common theme operations. All functions are loaded automatically and available throughout the theme.

### File Location

**File**: `includes/Utils/Helpers.php`  
**Namespace**: Global (no namespace, can be called anywhere)  
**Loaded**: Automatically via Composer autoload

### Categories

- **Logger**: 9 functions (PSR-3 compliant logging)
- **Cache**: 6 functions (tag-based caching)
- **Post Types**: 4 functions (custom post type queries)
- **Templates**: 2 functions (template loading)
- **ACF**: 2 functions (flexible content)
- **Utilities**: 4 functions (theme helpers)
- **Translation**: 3 functions (i18n support)
- **Stock Data**: 4 functions (stock information)

---

## Logger Helpers

PSR-3 compliant logging with 8 severity levels. Logs are written to `wp-content/uploads/soma-logs/soma.log`.

### soma_get_logger()

Get the Logger singleton instance.

**Syntax:**
```php
soma_get_logger(): \Soma\Utils\Logger
```

**Returns:** Logger instance

**Example:**
```php
$logger = soma_get_logger();
$logger->log(\Soma\Utils\Enums\LogLevel::INFO, 'Custom log message');
```

---

### soma_log_emergency()

Log emergency message (system unusable).

**Syntax:**
```php
soma_log_emergency( string $message, array $context = [] ): void
```

**Parameters:**
- `$message` (string) - Log message
- `$context` (array) - Optional context data

**Example:**
```php
soma_log_emergency('Database connection failed', [
    'host' => DB_HOST,
    'user' => DB_USER,
]);
```

---

### soma_log_alert()

Log alert message (action must be taken immediately).

**Syntax:**
```php
soma_log_alert( string $message, array $context = [] ): void
```

**Example:**
```php
soma_log_alert('Filesystem full', ['disk' => '/var', 'usage' => '99%']);
```

---

### soma_log_critical()

Log critical message (critical conditions).

**Syntax:**
```php
soma_log_critical( string $message, array $context = [] ): void
```

**Example:**
```php
soma_log_critical('Payment gateway unavailable', ['gateway' => 'stripe']);
```

---

### soma_log_error()

Log error message (runtime errors).

**Syntax:**
```php
soma_log_error( string $message, array $context = [] ): void
```

**Example:**
```php
soma_log_error('Failed to send email', [
    'to' => 'user@example.com',
    'error' => $mailer->ErrorInfo,
]);
```

---

### soma_log_warning()

Log warning message (exceptional occurrences).

**Syntax:**
```php
soma_log_warning( string $message, array $context = [] ): void
```

**Example:**
```php
soma_log_warning('Cache miss', ['key' => 'portfolio_items']);
```

---

### soma_log_notice()

Log notice message (normal but significant).

**Syntax:**
```php
soma_log_notice( string $message, array $context = [] ): void
```

**Example:**
```php
soma_log_notice('User logged in', ['user_id' => 123]);
```

---

### soma_log_info()

Log informational message.

**Syntax:**
```php
soma_log_info( string $message, array $context = [] ): void
```

**Example:**
```php
soma_log_info('Post published', ['post_id' => 456]);
```

---

### soma_log_debug()

Log debug message (detailed debugging information).

**Syntax:**
```php
soma_log_debug( string $message, array $context = [] ): void
```

**Example:**
```php
soma_log_debug('Query executed', [
    'sql' => $wpdb->last_query,
    'time' => $wpdb->query_time,
]);
```

---

## Cache Helpers

Tag-based caching system with automatic invalidation.

### soma_get_cache()

Get the Cache singleton instance.

**Syntax:**
```php
soma_get_cache(): \Soma\Utils\Cache
```

**Returns:** Cache instance

**Example:**
```php
$cache = soma_get_cache();
```

---

### soma_cache_get()

Get cached value by key.

**Syntax:**
```php
soma_cache_get( string $key, mixed $default_value = null ): mixed
```

**Parameters:**
- `$key` (string) - Cache key
- `$default_value` (mixed) - Default value if key not found

**Returns:** Cached value or default

**Example:**
```php
$portfolio = soma_cache_get('portfolio_items', []);
if (empty($portfolio)) {
    // Fetch from database
    $portfolio = get_posts(['post_type' => 'portfolio']);
    soma_cache_set('portfolio_items', $portfolio);
}
```

---

### soma_cache_set()

Set cached value with optional TTL and tags.

**Syntax:**
```php
soma_cache_set( string $key, mixed $value, int $ttl = 3600, array $tags = [] ): bool
```

**Parameters:**
- `$key` (string) - Cache key
- `$value` (mixed) - Value to cache
- `$ttl` (int) - Time to live in seconds (default: 3600 = 1 hour)
- `$tags` (array) - Cache tags for group invalidation

**Returns:** True on success

**Example:**
```php
use Soma\Utils\Enums\CacheTag;

soma_cache_set(
    'portfolio_items',
    $portfolio,
    7200, // 2 hours
    [CacheTag::POST_TYPE]
);
```

---

### soma_cache_remember()

Get from cache or execute callback and store result.

**Syntax:**
```php
soma_cache_remember( string $key, callable $callback, int $ttl = 3600, array $tags = [] ): mixed
```

**Parameters:**
- `$key` (string) - Cache key
- `$callback` (callable) - Function to execute on cache miss
- `$ttl` (int) - Time to live in seconds
- `$tags` (array) - Cache tags

**Returns:** Cached or computed value

**Example:**
```php
use Soma\Utils\Enums\CacheTag;

$portfolio = soma_cache_remember('portfolio_items', function() {
    return get_posts([
        'post_type' => 'portfolio',
        'posts_per_page' => 10,
    ]);
}, 3600, [CacheTag::POST_TYPE]);
```

---

### soma_cache_invalidate_tags()

Invalidate all cache entries with specified tags.

**Syntax:**
```php
soma_cache_invalidate_tags( array $tags ): int
```

**Parameters:**
- `$tags` (array) - Array of CacheTag enums

**Returns:** Number of entries invalidated

**Example:**
```php
use Soma\Utils\Enums\CacheTag;

// Invalidate all portfolio-related caches
$count = soma_cache_invalidate_tags([CacheTag::POST_TYPE]);

// Invalidate multiple tags
soma_cache_invalidate_tags([
    CacheTag::POST_TYPE,
    CacheTag::PAGE_BUILDER,
]);
```

---

### soma_cache_flush()

Clear all cached data.

**Syntax:**
```php
soma_cache_flush(): bool
```

**Returns:** True on success

**Example:**
```php
// Clear all caches
if (soma_cache_flush()) {
    soma_log_info('All caches cleared');
}
```

---

## Post Type Helpers

Simplified query functions for custom post types.

### soma_get_portfolio_items()

Query portfolio items with default arguments.

**Syntax:**
```php
soma_get_portfolio_items( array $args = [] ): WP_Query
```

**Parameters:**
- `$args` (array) - WP_Query arguments (merged with defaults)

**Defaults:**
- `post_type`: `portfolio`
- `posts_per_page`: 10
- `post_status`: `publish`
- `orderby`: `date`
- `order`: `DESC`

**Returns:** WP_Query object

**Example:**
```php
// Get latest 5 portfolio items
$query = soma_get_portfolio_items([
    'posts_per_page' => 5,
]);

if ($query->have_posts()) {
    while ($query->have_posts()) {
        $query->the_post();
        the_title();
    }
}
wp_reset_postdata();

// Get portfolio by taxonomy
$query = soma_get_portfolio_items([
    'tax_query' => [
        [
            'taxonomy' => 'portfolio-taxonomy',
            'field' => 'slug',
            'terms' => 'commercial',
        ],
    ],
]);
```

---

### soma_get_news_items()

Query news items with default arguments.

**Syntax:**
```php
soma_get_news_items( array $args = [] ): WP_Query
```

**Parameters:**
- `$args` (array) - WP_Query arguments

**Defaults:**
- `post_type`: `news`
- `posts_per_page`: 10
- `post_status`: `publish`
- `orderby`: `date`
- `order`: `DESC`

**Example:**
```php
// Get latest news
$query = soma_get_news_items();

// Get news from last month
$query = soma_get_news_items([
    'date_query' => [
        [
            'after' => '1 month ago',
        ],
    ],
]);
```

---

### soma_get_careers_items()

Query careers/jobs with default arguments.

**Syntax:**
```php
soma_get_careers_items( array $args = [] ): WP_Query
```

**Parameters:**
- `$args` (array) - WP_Query arguments

**Defaults:**
- `post_type`: `careers`
- `posts_per_page`: 10
- `post_status`: `publish`
- `orderby`: `date`
- `order`: `DESC`

**Example:**
```php
// Get open positions
$query = soma_get_careers_items([
    'meta_query' => [
        [
            'key' => 'position_status',
            'value' => 'open',
        ],
    ],
]);
```

---

### soma_get_team_members()

Query team members with default arguments.

**Syntax:**
```php
soma_get_team_members( array $args = [] ): WP_Query
```

**Parameters:**
- `$args` (array) - WP_Query arguments

**Defaults:**
- `post_type`: `team-members`
- `posts_per_page`: -1 (all)
- `post_status`: `publish`
- `orderby`: `menu_order`
- `order`: `ASC`

**Example:**
```php
// Get all team members
$query = soma_get_team_members();

// Get executive team
$query = soma_get_team_members([
    'tax_query' => [
        [
            'taxonomy' => 'team-members-taxonomy',
            'field' => 'slug',
            'terms' => 'executives',
        ],
    ],
]);
```

---

## Template Helpers

Load template parts and partials with data.

### soma_get_template_part()

Load template part with arguments.

**Syntax:**
```php
soma_get_template_part( string $slug, ?string $name = null, array $args = [] ): void
```

**Parameters:**
- `$slug` (string) - Template slug (e.g., 'partials/Header')
- `$name` (string|null) - Template name (optional)
- `$args` (array) - Arguments to pass to template

**Example:**
```php
// Load template with args
soma_get_template_part('partials/Header', null, [
    'title' => 'Custom Title',
    'subtitle' => 'Description',
]);

// In template, access via query var
$args = get_query_var('template_args');
echo $args['title'];
```

---

### soma_load_partial()

Load PageBuilder partial with data (legacy wrapper).

**Syntax:**
```php
soma_load_partial( string $partial_name, array $data = [] ): void
```

**Parameters:**
- `$partial_name` (string) - Partial name without extension
- `$data` (array) - Data to pass to partial

**Example:**
```php
soma_load_partial('BusinessUnits', [
    'title' => 'Our Business Units',
    'units' => $business_units,
]);
```

**Note:** This function sets global `$pageBlock` for backward compatibility. New code should use WordPress query vars instead.

---

## ACF Helpers

Advanced Custom Fields integration helpers.

### soma_get_flexible_content()

Get ACF flexible content field.

**Syntax:**
```php
soma_get_flexible_content( string $field_name = 'soma_blocks', ?int $post_id = null ): array|false
```

**Parameters:**
- `$field_name` (string) - ACF field name (default: 'soma_blocks')
- `$post_id` (int|null) - Post ID (null = current post)

**Returns:** Array of blocks or false

**Example:**
```php
// Get blocks from current post
$blocks = soma_get_flexible_content();

// Get blocks from specific post
$blocks = soma_get_flexible_content('soma_blocks', 123);

// Get custom flexible field
$sections = soma_get_flexible_content('page_sections');
```

---

### soma_render_flexible_content()

Render ACF flexible content blocks.

**Syntax:**
```php
soma_render_flexible_content( array|false $blocks ): void
```

**Parameters:**
- `$blocks` (array|false) - Flexible content blocks

**Example:**
```php
// Get and render blocks
$blocks = soma_get_flexible_content();
soma_render_flexible_content($blocks);

// One-liner
soma_render_flexible_content(
    soma_get_flexible_content()
);
```

---

## Utility Helpers

General theme utility functions.

### soma_is_dev()

Check if development mode is enabled.

**Syntax:**
```php
soma_is_dev(): bool
```

**Returns:** True if WP_DEBUG is enabled

**Example:**
```php
if (soma_is_dev()) {
    soma_log_debug('Debug mode enabled');
    // Show additional debug info
}
```

---

### soma_get_version()

Get current theme version.

**Syntax:**
```php
soma_get_version(): string
```

**Returns:** Theme version (e.g., '3.0.0')

**Example:**
```php
$version = soma_get_version();
wp_enqueue_style('custom', get_stylesheet_uri(), [], $version);
```

---

### soma_sanitize_class()

Sanitize CSS class name.

**Syntax:**
```php
soma_sanitize_class( string $class_name ): string
```

**Parameters:**
- `$class_name` (string) - Class name to sanitize

**Returns:** Sanitized class name

**Example:**
```php
$class = soma_sanitize_class('My Custom Class!');
// Result: 'my-custom-class'

echo '<div class="' . soma_sanitize_class($user_input) . '">';
```

---

### soma_asset_url()

Get asset URL with automatic versioning.

**Syntax:**
```php
soma_asset_url( string $path ): string
```

**Parameters:**
- `$path` (string) - Asset path relative to theme root

**Returns:** Full URL with version query string

**Example:**
```php
// Enqueue with auto-versioning
wp_enqueue_script(
    'custom-script',
    soma_asset_url('js/custom.js'),
    ['jquery'],
    null,
    true
);

// Image src with version
echo '<img src="' . soma_asset_url('images/logo.png') . '">';
```

---

## Translation Helpers

Multilingual support functions.

### soma_translate_date()

Translate date strings to Spanish (WP Multilang integration).

**Syntax:**
```php
soma_translate_date( string $str_date, ?string $format = null ): string
```

**Parameters:**
- `$str_date` (string) - Date string to translate
- `$format` (string|null) - Format type ('short' for abbreviated months)

**Returns:** Translated date string

**Example:**
```php
// Full month names
$date = date('F j, Y'); // "December 12, 2025"
echo soma_translate_date($date); // "Diciembre 12, 2025"

// Short month names
$date = date('M j, Y'); // "Dec 12, 2025"
echo soma_translate_date($date, 'short'); // "Dic 12, 2025"
```

**Translations:**

**Full months:**
- January → Enero
- February → Febrero
- March → Marzo
- April → Abril
- May → Mayo
- June → Junio
- July → Julio
- August → Agosto
- September → Septiembre
- October → Octubre
- November → Noviembre
- December → Diciembre

**Short months:**
- Jan → Ene
- Apr → Abr
- Aug → Ago
- Dec → Dic

---

### soma_get_i18n_field()

Get internationalized ACF field value based on current language.

**Syntax:**
```php
soma_get_i18n_field( array $data, string $field_name ): mixed
```

**Parameters:**
- `$data` (array) - ACF field data array containing base and localized fields
- `$field_name` (string) - Base field name (e.g., 'file', 'events')

**Returns:** Field value for current language or base field value as fallback

**Description:**
Automatically selects the appropriate field based on WP Multilang language:
- **Spanish (`es`)**: Returns `{$field_name}_es` if exists
- **English/Default**: Returns `{$field_name}`
- **Fallback**: If localized field doesn't exist, returns base field

**Example:**
```php
// ACF structure:
// $content = [
//     'file' => ['url' => 'document-en.pdf'],
//     'file_es' => ['url' => 'documento-es.pdf'],
// ];

// Get file based on language
$file = soma_get_i18n_field($content, 'file');
// Returns: file_es if Spanish, file if English

// Usage with conditionals BEFORE (deprecated):
$file = (wpm_get_language() === 'en') ? $content['file'] : $content['file_es'];

// Usage with helper AFTER (recommended):
$file = soma_get_i18n_field($content, 'file');
```

**Use Cases:**
- ACF file fields with English/Spanish versions
- Events arrays with language-specific content
- Any ACF data with `_es` suffix fields

**Dependencies:**
- WP Multilang plugin (optional - gracefully degrades if not available)

**Since:** v3.0.0

---

### translateDate() (Deprecated)

Backward compatibility alias for `soma_translate_date()`.

**Status:** Deprecated since v3.0.0  
**Use:** `soma_translate_date()` instead

---

## Stock Data Helpers

Financial stock information functions.

### soma_get_stock_data()

Get current stock market data.

**Syntax:**
```php
soma_get_stock_data(): ?array
```

**Returns:** Array with stock data or null if unavailable

**Example:**
```php
$stock = soma_get_stock_data();
if ($stock) {
    echo 'Current Price: $' . $stock['price'];
    echo 'Change: ' . $stock['change'];
    echo 'Volume: ' . $stock['volume'];
}
```

**Data Structure:**
```php
[
    'symbol' => 'SOMA',
    'price' => 45.67,
    'change' => '+1.23',
    'change_percent' => '+2.77%',
    'volume' => '1,234,567',
    'market_cap' => '1.2B',
    'updated_at' => '2025-12-12 15:30:00',
]
```

---

### soma_format_stock_price_simple()

Format stock price with currency symbol only.

**Syntax:**
```php
soma_format_stock_price_simple( float $price, string $currency = 'MXN' ): string
```

**Parameters:**
- `$price` (float) - The stock price to format
- `$currency` (string) - Currency code: 'MXN', 'USD', 'EUR' (default: 'MXN')

**Returns:** Formatted price string with currency symbol and 2 decimal places

**Example:**
```php
echo soma_format_stock_price_simple( 21.52 );        // "$21.52 MXN"
echo soma_format_stock_price_simple( 21.52, 'USD' ); // "$21.52 USD"
echo soma_format_stock_price_simple( 21.52, 'EUR' ); // "€21.52 EUR"
```

**Since:** 3.1.13

---

### soma_format_stock_change_combined()

Format stock price change with percentage in a combined string.

**Syntax:**
```php
soma_format_stock_change_combined( float $change, float $change_percent ): string
```

**Parameters:**
- `$change` (float) - The price change amount
- `$change_percent` (float) - The percentage change

**Returns:** Combined formatted string showing change and percentage

**Example:**
```php
// Positive change
echo soma_format_stock_change_combined( 0.19, 0.89 );
// Output: "+$0.19 (+0.89%)"

// Negative change
echo soma_format_stock_change_combined( -0.50, -2.35 );
// Output: "-$0.50 (-2.35%)"

// No change
echo soma_format_stock_change_combined( 0, 0 );
// Output: "$0.00 (0.00%)"
```

**Since:** 3.1.13

---

### soma_format_stock_datetime()

Format stock timestamp with time, timezone, and date.

**Syntax:**
```php
soma_format_stock_datetime( int $timestamp ): string
```

**Parameters:**
- `$timestamp` (int) - Unix timestamp to format

**Returns:** Formatted datetime string with timezone

**Example:**
```php
$timestamp = time();
echo soma_format_stock_datetime( $timestamp );
// Output: "As of 11:10 AM CST 1/7/2026"
```

**Notes:**
- Uses WordPress site timezone settings via `wp_date()`
- Timezone abbreviation is dynamically determined (CST, CDT, EST, etc.)
- Time format is 12-hour with AM/PM
- Date format is month/day/year without leading zeros

**Since:** 3.1.13

---

## Usage Examples

### Complete Query & Cache Example

```php
use Soma\Utils\Enums\CacheTag;

// Get portfolio with caching
$portfolio = soma_cache_remember('portfolio_featured', function() {
    $query = soma_get_portfolio_items([
        'posts_per_page' => 6,
        'meta_query' => [
            [
                'key' => 'featured',
                'value' => '1',
            ],
        ],
    ]);
    
    $items = [];
    if ($query->have_posts()) {
        while ($query->have_posts()) {
            $query->the_post();
            $items[] = [
                'id' => get_the_ID(),
                'title' => get_the_title(),
                'url' => get_permalink(),
                'thumbnail' => get_the_post_thumbnail_url(),
            ];
        }
    }
    wp_reset_postdata();
    
    return $items;
}, 3600, [CacheTag::POST_TYPE]);

// Display portfolio
foreach ($portfolio as $item) {
    echo '<a href="' . esc_url($item['url']) . '">';
    echo esc_html($item['title']);
    echo '</a>';
}
```

### Error Handling with Logging

```php
try {
    $result = perform_complex_operation();
    
    if (!$result) {
        soma_log_warning('Operation returned empty result', [
            'operation' => 'complex_operation',
            'user_id' => get_current_user_id(),
        ]);
    } else {
        soma_log_info('Operation successful', [
            'result_count' => count($result),
        ]);
    }
} catch (Exception $e) {
    soma_log_error('Operation failed', [
        'exception' => $e->getMessage(),
        'trace' => $e->getTraceAsString(),
    ]);
}
```

### Cache Invalidation on Save

```php
add_action('save_post_portfolio', function($post_id) {
    // Invalidate all portfolio caches
    soma_cache_invalidate_tags([CacheTag::POST_TYPE]);
    
    soma_log_info('Portfolio cache invalidated', [
        'post_id' => $post_id,
    ]);
});
```

### Template with Data

```php
// Load header partial with custom data
soma_get_template_part('partials/Hero', null, [
    'title' => get_field('hero_title'),
    'subtitle' => get_field('hero_subtitle'),
    'background' => get_field('hero_background'),
    'cta_text' => get_field('hero_cta_text'),
    'cta_url' => get_field('hero_cta_url'),
]);
```

---

## Best Practices

### 1. Always Use Cache for Expensive Operations

```php
// ❌ BAD: Query every time
$team = soma_get_team_members();

// ✅ GOOD: Cache the query
$team = soma_cache_remember('team_members_all', function() {
    return soma_get_team_members();
}, 3600, [CacheTag::POST_TYPE]);
```

### 2. Use Appropriate Log Levels

```php
// ❌ BAD: Everything is an error
soma_log_error('User logged in');
soma_log_error('Cache miss');

// ✅ GOOD: Use correct levels
soma_log_info('User logged in');
soma_log_debug('Cache miss');
soma_log_error('Database connection failed');
```

### 3. Invalidate Caches Appropriately

```php
// ✅ GOOD: Invalidate related caches on save
add_action('save_post_portfolio', function($post_id) {
    soma_cache_invalidate_tags([CacheTag::POST_TYPE]);
});

// ✅ GOOD: Use specific tags
soma_cache_invalidate_tags([CacheTag::PAGE_BUILDER]);
```

### 4. Add Context to Logs

```php
// ❌ BAD: No context
soma_log_error('Failed');

// ✅ GOOD: Rich context
soma_log_error('Email send failed', [
    'to' => $recipient,
    'subject' => $subject,
    'error' => $mailer->ErrorInfo,
    'user_id' => get_current_user_id(),
]);
```

### 5. Check for Required Functions

```php
// ✅ GOOD: Check before using
if (function_exists('get_field')) {
    $blocks = soma_get_flexible_content();
} else {
    soma_log_error('ACF not available');
}
```

### 6. Use Type-Safe Enums

```php
use Soma\Core\Enums\PostType;
use Soma\Utils\Enums\CacheTag;
use Soma\Utils\Enums\LogLevel;

// ✅ GOOD: Type-safe
$query = soma_get_portfolio_items();
soma_cache_set($key, $value, 3600, [CacheTag::POST_TYPE]);
soma_get_logger()->log(LogLevel::INFO, 'Message');

// ❌ AVOID: Magic strings
$query = get_posts(['post_type' => 'portfolio']);
```

### 7. Always Reset Post Data

```php
// ✅ GOOD: Reset after custom queries
$query = soma_get_portfolio_items();
if ($query->have_posts()) {
    while ($query->have_posts()) {
        $query->the_post();
        the_title();
    }
}
wp_reset_postdata(); // Important!
```

### 8. Version Assets Automatically

```php
// ❌ BAD: Manual versioning
wp_enqueue_style('custom', get_stylesheet_uri(), [], '1.0.0');

// ✅ GOOD: Auto-version from theme
wp_enqueue_style('custom', get_stylesheet_uri(), [], soma_get_version());

// ✅ BETTER: Use soma_asset_url()
wp_enqueue_style('custom', soma_asset_url('css/custom.css'));
```

---

## See Also

- **Development Guide**: `docs/DEVELOPMENT.md`
- **Widgets Reference**: `docs/WIDGETS.md`
- **CSS Variables**: `docs/CSS_VARIABLES.md`
- **Testing Guide**: `docs/TESTING_GUIDE.md`

---

**Document Version**: 1.0  
**Last Updated**: December 12, 2025  
**Total Functions**: 24  
**Maintainer**: Miguel Colmenares
