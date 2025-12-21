# SOMA Theme v3.0 - Elementor Widgets Reference

**Version**: 3.0.0  
**Last Updated**: December 12, 2025  
**Total Widgets**: 8

---

## Table of Contents

1. [Overview](#overview)
2. [Widget Catalog](#widget-catalog)
   - [Navbar Widget](#1-navbar-widget)
   - [Footer Widget](#2-footer-widget)
   - [Business Units Widget](#3-business-units-widget)
   - [Services Widget](#4-services-widget)
   - [Team Members Widget](#5-team-members-widget)
   - [News List Widget](#6-news-list-widget)
   - [Portfolio Widget](#7-portfolio-widget)
   - [Contact Form Widget](#8-contact-form-widget)
3. [Common Features](#common-features)
4. [Development Guide](#development-guide)
5. [Troubleshooting](#troubleshooting)

---

## Overview

SOMA v3.0 includes 8 custom Elementor widgets designed specifically for corporate websites and real estate investment trusts. All widgets are grouped under the **"SOMA"** category in the Elementor panel.

### Key Features

- **PSR-4 Architecture**: Modern PHP namespace structure
- **CSS Variables**: Consistent styling with design tokens
- **ACF Integration**: Seamless integration with Advanced Custom Fields
- **Responsive**: Mobile-first design approach
- **Typography Controls**: Native Elementor typography system
- **Style Dependencies**: Isolated CSS per widget
- **i18n Ready**: Fully translatable with WP Multilang support

### Widget Location

**Namespace**: `Soma\Elementor\Widgets\`  
**Files**: `includes/Elementor/Widgets/*.php`  
**Styles**: `assets/css/widgets/*.css`  
**Category**: `soma`

---

## Widget Catalog

### 1. Navbar Widget

**Class**: `Soma\Elementor\Widgets\Navbar`  
**ID**: `soma-navbar`  
**Icon**: `eicon-nav-menu`

#### Description

Customizable navigation bar with logo, menu, search functionality, and language switcher. Supports sticky header, transparent background, and multiple layout options.

#### Content Controls

| Control | Type | Default | Description |
|---------|------|---------|-------------|
| **Logo** | Media | - | Site logo image |
| **Logo Width** | Slider | 150px | Logo width (50-300px) |
| **Menu Location** | Select | `main_menu` | WordPress menu location |
| **Show Search** | Switcher | Yes | Display search icon |
| **Show Language Switcher** | Switcher | Yes | Display WP Multilang switcher |
| **Enable Sticky** | Switcher | Yes | Sticky header on scroll |
| **Transparent Background** | Switcher | No | Transparent navbar |

#### Style Controls

| Control | Type | Description |
|---------|------|-------------|
| **Background Color** | Color | Navbar background |
| **Text Color** | Color | Menu item color |
| **Text Hover Color** | Color | Menu hover state |
| **Logo Height** | Slider | Logo container height |
| **Padding** | Dimensions | Navbar padding |
| **Menu Item Spacing** | Slider | Space between items |
| **Typography** | Group | Menu text styling |

#### CSS Variables Used

```css
--soma-primary
--soma-text-primary
--soma-bg-white
--soma-spacing-md
--soma-spacing-lg
--soma-transition
```

#### Usage Example

```php
// In Elementor template
// Add widget from SOMA category → Navbar
// Configure logo, menu, and styling
```

#### ACF Integration

None - Uses WordPress menus and options.

---

### 2. Footer Widget

**Class**: `Soma\Elementor\Widgets\Footer`  
**ID**: `soma-footer`  
**Icon**: `eicon-footer`

#### Description

Site footer with logo, newsletter subscription, navigation menus, social links, and copyright text. Supports two style variants: default (social + business units) and fibrasoma (single menu).

#### Content Controls

| Control | Type | Default | Description |
|---------|------|---------|-------------|
| **Style Variant** | Select | `default` | Footer layout (default/fibrasoma) |
| **Logo** | Media | - | Footer logo image |
| **Logo Width** | Slider | 120px | Logo width |
| **Newsletter Title** | Text | - | Newsletter section heading |
| **Newsletter Shortcode** | Text | - | CF7 shortcode for newsletter |
| **Copyright Text** | Textarea | - | Copyright notice |
| **Social Menu** | Select | `social` | Social links menu location |
| **Business Units Menu** | Select | `business_units` | Business units menu (default variant) |
| **Main Menu** | Select | `fibrasoma_footer` | Footer menu (fibrasoma variant) |

#### Style Controls

| Control | Type | Description |
|---------|------|-------------|
| **Background Color** | Color | Footer background |
| **Text Color** | Color | Default text color |
| **Link Color** | Color | Link color |
| **Link Hover Color** | Color | Link hover state |
| **Heading Typography** | Group | Newsletter title styling |
| **Text Typography** | Group | Body text styling |
| **Copyright Typography** | Group | Copyright text styling |
| **Padding** | Dimensions | Footer padding |

#### CSS Variables Used

```css
--soma-bg-dark
--soma-text-light
--soma-primary
--soma-spacing-xl
--soma-spacing-2xl
```

#### Usage Example

```php
// Default variant (social + business units)
$settings['variant'] = 'default';
$settings['social_menu'] = 'social';
$settings['business_units_menu'] = 'business_units';

// Fibrasoma variant (single menu)
$settings['variant'] = 'fibrasoma';
$settings['main_menu'] = 'fibrasoma_footer';
```

#### ACF Integration

None - Uses WordPress menus and CF7 shortcode.

---

### 3. Business Units Widget

**Class**: `Soma\Elementor\Widgets\BusinessUnits`  
**ID**: `soma-business-units`  
**Icon**: `eicon-gallery-grid`

#### Description

Displays business units in a responsive grid layout. Automatically queries pages using the business-unit-template and displays custom ACF fields (icon, description, link).

#### Content Controls

| Control | Type | Default | Description |
|---------|------|---------|-------------|
| **Title** | Text | - | Section heading |
| **Subtitle** | Textarea | - | Section description |
| **Number of Units** | Number | -1 | Units to display (-1 = all) |
| **Order By** | Select | `menu_order` | Sort order |
| **Order** | Select | `ASC` | Sort direction |
| **Columns** | Select | `3` | Grid columns (2/3/4) |

#### Style Controls

| Control | Type | Description |
|---------|------|-------------|
| **Title Color** | Color | Section title color |
| **Title Typography** | Group | Title styling |
| **Subtitle Color** | Color | Subtitle color |
| **Subtitle Typography** | Group | Subtitle styling |
| **Card Background** | Color | Business unit card background |
| **Card Border Radius** | Slider | Card corner rounding |
| **Card Padding** | Dimensions | Card inner spacing |
| **Gap** | Slider | Space between cards |

#### CSS Variables Used

```css
--soma-primary
--soma-bg-white
--soma-text-primary
--soma-spacing-lg
--soma-border-radius
--soma-shadow-md
```

#### Usage Example

```php
// Query pages with business-unit-template
$args = [
    'post_type' => 'page',
    'meta_query' => [
        [
            'key' => '_wp_page_template',
            'value' => 'business-unit-template.php',
        ],
    ],
    'posts_per_page' => $settings['number_of_units'],
    'orderby' => $settings['order_by'],
    'order' => $settings['order'],
];
```

#### ACF Integration

**Required ACF Fields on Business Unit Pages:**
- `unit_icon` (Image) - Business unit icon
- `unit_description` (Textarea) - Short description
- `unit_link` (Link) - External or internal link

---

### 4. Services Widget

**Class**: `Soma\Elementor\Widgets\Services`  
**ID**: `soma-services`  
**Icon**: `eicon-gallery-grid`

#### Description

Grid display of services with icons, titles, and descriptions. Supports manual service entry via Elementor repeater.

#### Content Controls

| Control | Type | Default | Description |
|---------|------|---------|-------------|
| **Section Title** | Text | - | Main heading |
| **Section Subtitle** | Textarea | - | Description text |
| **Services** | Repeater | [] | List of services |
| └─ **Icon** | Icons | - | Service icon |
| └─ **Title** | Text | - | Service name |
| └─ **Description** | Textarea | - | Service details |
| └─ **Link** | URL | - | Service page URL |
| **Columns** | Select | `3` | Grid columns (2/3/4) |

#### Style Controls

| Control | Type | Description |
|---------|------|-------------|
| **Section Title Typography** | Group | Main title styling |
| **Section Title Color** | Color | Title color |
| **Icon Color** | Color | Service icon color |
| **Icon Size** | Slider | Icon dimensions |
| **Service Title Typography** | Group | Service name styling |
| **Service Title Color** | Color | Service title color |
| **Description Typography** | Group | Description text styling |
| **Description Color** | Color | Description color |
| **Card Background** | Color | Service card background |
| **Card Hover Background** | Color | Hover state background |

#### CSS Variables Used

```css
--soma-primary
--soma-text-primary
--soma-bg-light
--soma-spacing-md
--soma-spacing-lg
--soma-transition
```

#### Usage Example

```php
// Manual services entry
$settings['services'] = [
    [
        'icon' => ['value' => 'fas fa-building'],
        'title' => 'Property Management',
        'description' => 'Full-service property management',
        'link' => ['url' => '/services/property-management'],
    ],
    // ... more services
];
```

#### ACF Integration

None - Uses Elementor repeater.

---

### 5. Team Members Widget

**Class**: `Soma\Elementor\Widgets\TeamMembers`  
**ID**: `soma-team-members`  
**Icon**: `eicon-person`

#### Description

Displays team members from the custom post type in a grid with photos, names, positions, and social links. Supports taxonomy filtering.

#### Content Controls

| Control | Type | Default | Description |
|---------|------|---------|-------------|
| **Section Title** | Text | - | Section heading |
| **Section Subtitle** | Textarea | - | Section description |
| **Number of Members** | Number | 6 | Members to display |
| **Taxonomy Filter** | Select | - | Filter by team category |
| **Order By** | Select | `menu_order` | Sort field |
| **Order** | Select | `ASC` | Sort direction |
| **Columns** | Select | `3` | Grid columns (2/3/4) |

#### Style Controls

| Control | Type | Description |
|---------|------|-------------|
| **Section Title Typography** | Group | Main title styling |
| **Card Background** | Color | Member card background |
| **Card Border Radius** | Slider | Card corner rounding |
| **Card Padding** | Dimensions | Card inner spacing |
| **Photo Border Radius** | Slider | Member photo rounding |
| **Name Typography** | Group | Member name styling |
| **Name Color** | Color | Name text color |
| **Position Typography** | Group | Position/title styling |
| **Position Color** | Color | Position text color |
| **Social Icon Color** | Color | Social link icons color |

#### CSS Variables Used

```css
--soma-primary
--soma-text-primary
--soma-bg-white
--soma-spacing-lg
--soma-border-radius
--soma-shadow-sm
```

#### Usage Example

```php
// Query team members
$args = [
    'post_type' => 'team-members',
    'posts_per_page' => $settings['number_of_members'],
    'orderby' => $settings['order_by'],
    'order' => $settings['order'],
];

// With taxonomy filter
if (!empty($settings['taxonomy_filter'])) {
    $args['tax_query'] = [
        [
            'taxonomy' => 'team-members-taxonomy',
            'field' => 'term_id',
            'terms' => $settings['taxonomy_filter'],
        ],
    ];
}
```

#### ACF Integration

**Required ACF Fields on team-members post type:**
- `member_position` (Text) - Job title
- `member_bio` (Textarea) - Biography
- `member_email` (Email) - Contact email
- `member_phone` (Text) - Contact phone
- `member_social_links` (Repeater) - Social media links

---

### 6. News List Widget

**Class**: `Soma\Elementor\Widgets\NewsList`  
**ID**: `soma-news-list`  
**Icon**: `eicon-post-list`

#### Description

Grid or list display of news articles from the custom post type. Features excerpt, date, categories, and featured images.

#### Content Controls

| Control | Type | Default | Description |
|---------|------|---------|-------------|
| **Section Title** | Text | - | Section heading |
| **Number of Posts** | Number | 6 | Posts to display |
| **Order By** | Select | `date` | Sort field |
| **Order** | Select | `DESC` | Sort direction |
| **Layout** | Select | `grid` | Display layout (grid/list) |
| **Columns** | Select | `3` | Grid columns (2/3/4) |
| **Show Excerpt** | Switcher | Yes | Display excerpt |
| **Excerpt Length** | Number | 100 | Excerpt words |
| **Show Date** | Switcher | Yes | Display publish date |
| **Show Categories** | Switcher | Yes | Display categories |
| **Show Author** | Switcher | No | Display author name |
| **Show Read More** | Switcher | Yes | Show read more link |

#### Style Controls

| Control | Type | Description |
|---------|------|-------------|
| **Section Title Typography** | Group | Main title styling |
| **Card Background** | Color | Post card background |
| **Card Border Radius** | Slider | Card corner rounding |
| **Image Height** | Slider | Featured image height |
| **Title Typography** | Group | Post title styling |
| **Title Color** | Color | Post title color |
| **Title Hover Color** | Color | Title hover state |
| **Excerpt Typography** | Group | Excerpt text styling |
| **Meta Typography** | Group | Date/category styling |
| **Meta Color** | Color | Meta text color |
| **Button Typography** | Group | Read more button styling |

#### CSS Variables Used

```css
--soma-primary
--soma-text-primary
--soma-text-secondary
--soma-bg-white
--soma-spacing-md
--soma-border-radius
--soma-transition
```

#### Usage Example

```php
// Query news posts
$args = [
    'post_type' => 'news',
    'posts_per_page' => $settings['number_of_posts'],
    'orderby' => $settings['order_by'],
    'order' => $settings['order'],
];

// Excerpt customization
$excerpt = wp_trim_words(
    get_the_excerpt(),
    $settings['excerpt_length'],
    '...'
);
```

#### ACF Integration

Optional - Can display custom ACF fields if available.

---

### 7. Portfolio Widget

**Class**: `Soma\Elementor\Widgets\Portfolio`  
**ID**: `soma-portfolio`  
**Icon**: `eicon-gallery-grid`

#### Description

Portfolio/project showcase with filterable categories, lightbox support, and detailed project information. Supports taxonomy filtering and multiple layout options.

#### Content Controls

| Control | Type | Default | Description |
|---------|------|---------|-------------|
| **Section Title** | Text | - | Section heading |
| **Number of Items** | Number | 9 | Portfolio items to show |
| **Taxonomy Filter** | Select | - | Filter by portfolio category |
| **Show Filter Tabs** | Switcher | Yes | Show category filter UI |
| **Order By** | Select | `menu_order` | Sort field |
| **Order** | Select | `ASC` | Sort direction |
| **Columns** | Select | `3` | Grid columns (2/3/4) |
| **Show Excerpt** | Switcher | Yes | Display excerpt |
| **Enable Lightbox** | Switcher | Yes | Lightbox for images |

#### Style Controls

| Control | Type | Description |
|---------|------|-------------|
| **Section Title Typography** | Group | Main title styling |
| **Filter Typography** | Group | Category filter styling |
| **Filter Active Color** | Color | Active filter color |
| **Card Background** | Color | Portfolio card background |
| **Card Overlay Color** | Color | Image overlay color |
| **Card Overlay Opacity** | Slider | Overlay transparency |
| **Title Typography** | Group | Project title styling |
| **Title Color** | Color | Title text color |
| **Category Typography** | Group | Category badge styling |
| **Category Color** | Color | Category text color |

#### CSS Variables Used

```css
--soma-primary
--soma-text-primary
--soma-bg-white
--soma-spacing-lg
--soma-border-radius
--soma-shadow-md
--soma-transition
```

#### Usage Example

```php
// Query portfolio items
$args = [
    'post_type' => 'portfolio',
    'posts_per_page' => $settings['number_of_items'],
    'orderby' => $settings['order_by'],
    'order' => $settings['order'],
];

// With taxonomy filter
if (!empty($settings['taxonomy_filter'])) {
    $args['tax_query'] = [
        [
            'taxonomy' => 'portfolio-taxonomy',
            'field' => 'term_id',
            'terms' => $settings['taxonomy_filter'],
        ],
    ];
}
```

#### ACF Integration

**Required ACF Fields on portfolio post type:**
- `project_client` (Text) - Client name
- `project_location` (Text) - Project location
- `project_year` (Number) - Completion year
- `project_area` (Text) - Square footage/area
- `project_gallery` (Gallery) - Project images

---

### 8. Contact Form Widget

**Class**: `Soma\Elementor\Widgets\ContactForm`  
**ID**: `soma-contact-form`  
**Icon**: `eicon-form-horizontal`

#### Description

Contact Form 7 integration widget with custom styling options. Supports any CF7 form via shortcode with additional contact information display.

#### Content Controls

| Control | Type | Default | Description |
|---------|------|---------|-------------|
| **Form Title** | Text | - | Form heading |
| **Form Description** | Textarea | - | Form description |
| **CF7 Shortcode** | Text | - | Contact Form 7 shortcode |
| **Show Contact Info** | Switcher | Yes | Display contact details |
| **Contact Title** | Text | - | Contact section title |
| **Address** | Textarea | - | Physical address |
| **Phone** | Text | - | Phone number |
| **Email** | Text | - | Email address |
| **Office Hours** | Textarea | - | Business hours |

#### Style Controls

| Control | Type | Description |
|---------|------|-------------|
| **Form Title Typography** | Group | Title styling |
| **Form Title Color** | Color | Title text color |
| **Description Typography** | Group | Description styling |
| **Field Background** | Color | Input field background |
| **Field Border Color** | Color | Input border color |
| **Field Focus Border** | Color | Focus state border |
| **Field Typography** | Group | Input text styling |
| **Label Typography** | Group | Label text styling |
| **Label Color** | Color | Label text color |
| **Button Typography** | Group | Submit button styling |
| **Button Color** | Color | Button text color |
| **Button Background** | Color | Button background |
| **Button Hover Background** | Color | Button hover state |

#### CSS Variables Used

```css
--soma-primary
--soma-text-primary
--soma-bg-white
--soma-bg-light
--soma-spacing-md
--soma-spacing-lg
--soma-border-radius
--soma-transition
```

#### Usage Example

```php
// CF7 shortcode
$settings['cf7_shortcode'] = '[contact-form-7 id="123" title="Contact Form"]';

// Display with contact info
echo '<div class="contact-form-wrapper">';
echo '<div class="form-section">';
echo do_shortcode($settings['cf7_shortcode']);
echo '</div>';
if ($settings['show_contact_info']) {
    echo '<div class="contact-info">';
    // Display address, phone, email, hours
    echo '</div>';
}
echo '</div>';
```

#### ACF Integration

None - Uses CF7 shortcode and widget settings.

---

## Common Features

### All Widgets Include

1. **Category**: `soma` - All widgets grouped together
2. **Base Class**: `Soma\Elementor\Base\WidgetBase`
3. **CSS Dependencies**: Individual CSS files per widget
4. **i18n Support**: All strings translatable
5. **Responsive**: Mobile-first approach
6. **Typography Controls**: Elementor native controls
7. **Color Controls**: Supports CSS variables
8. **Error Handling**: Graceful degradation

### Standard Control Groups

**Content Tab:**
- Section settings (title, subtitle)
- Query settings (number, order, filter)
- Display options (layout, columns, show/hide)

**Style Tab:**
- Section styling (title, description)
- Card/item styling (background, borders, spacing)
- Typography (all text elements)
- Colors (all color properties)
- Spacing (padding, margins, gaps)

**Advanced Tab:**
- CSS ID
- CSS Classes
- Custom CSS
- Motion Effects
- Responsive Settings

---

## Development Guide

### Creating a New Widget

**1. Create Widget Class:**

```php
<?php
namespace Soma\Elementor\Widgets;

use Soma\Elementor\Base\WidgetBase;
use Elementor\Controls_Manager;

class MyWidget extends WidgetBase {
    public function get_name(): string {
        return 'soma-my-widget';
    }

    public function get_title(): string {
        return __('My Widget', 'soma');
    }

    public function get_icon(): string {
        return 'eicon-posts-grid';
    }

    public function get_style_depends(): array {
        return ['soma-my-widget'];
    }

    protected function register_controls(): void {
        // Add controls here
    }

    protected function render(): void {
        // Render output here
    }
}
```

**2. Create CSS File:**

```css
/* assets/css/widgets/my-widget.css */
.soma-my-widget {
    padding: var(--soma-spacing-lg);
    background: var(--soma-bg-white);
}
```

**3. Register Widget:**

```php
// includes/Elementor/Loader.php
private function register_widgets(): void {
    // ... existing widgets
    \Elementor\Plugin::instance()->widgets_manager->register(
        new Widgets\MyWidget()
    );
}
```

**4. Enqueue CSS:**

```php
// functions.php or widget class
wp_register_style(
    'soma-my-widget',
    get_template_directory_uri() . '/assets/css/widgets/my-widget.css',
    [],
    '3.0.0'
);
```

### Widget Best Practices

1. **Always use CSS variables** for colors and spacing
2. **Sanitize all output** with `esc_html()`, `esc_url()`, etc.
3. **Check for empty data** before rendering
4. **Use proper WordPress functions** for queries
5. **Add loading states** for dynamic content
6. **Support RTL languages** when applicable
7. **Test responsive behavior** on all devices
8. **Document custom controls** with descriptions

---

## Troubleshooting

### Widget Not Appearing in Panel

**Possible Causes:**
1. Widget class not registered in Loader
2. Elementor cache needs clearing
3. Widget class has syntax error
4. Base class not extended

**Solutions:**
```php
// 1. Check Loader.php registration
private function register_widgets(): void {
    \Elementor\Plugin::instance()->widgets_manager->register(
        new Widgets\YourWidget()
    );
}

// 2. Clear Elementor cache
// Tools → Regenerate CSS & Data

// 3. Check PHP error log
tail -f /path/to/php-error.log

// 4. Verify extends WidgetBase
class YourWidget extends WidgetBase { }
```

### Styles Not Loading

**Possible Causes:**
1. CSS file not enqueued
2. Style dependencies not declared
3. File path incorrect
4. Caching issue

**Solutions:**
```php
// 1. Check get_style_depends()
public function get_style_depends(): array {
    return ['soma-your-widget'];
}

// 2. Verify CSS file exists
ls assets/css/widgets/your-widget.css

// 3. Clear all caches
wp_cache_flush();
soma_cache_flush();
```

### ACF Fields Not Displaying

**Possible Causes:**
1. Field group not assigned to post type
2. Field key incorrect
3. ACF not active
4. Field data not saved

**Solutions:**
```php
// 1. Check field exists
$value = get_field('field_name');
if (!$value) {
    soma_log_error('ACF field not found', ['field' => 'field_name']);
}

// 2. Verify field group location
// ACF → Field Groups → Edit → Location Rules

// 3. Check ACF is active
if (!function_exists('get_field')) {
    soma_log_error('ACF not active');
}
```

### Query Returns No Results

**Possible Causes:**
1. Post type not registered
2. Incorrect query arguments
3. No posts published
4. Taxonomy filter too restrictive

**Solutions:**
```php
// 1. Verify post type exists
if (!post_type_exists('your-post-type')) {
    soma_log_error('Post type not registered');
}

// 2. Debug query
$query = new WP_Query($args);
soma_log_debug('Query results', [
    'found_posts' => $query->found_posts,
    'args' => $args,
]);

// 3. Check publish status
$args['post_status'] = 'publish';
```

---

## Additional Resources

### Related Documentation

- **Development Guide**: `docs/DEVELOPMENT.md`
- **Helper Functions**: `docs/HELPERS.md`
- **CSS Variables**: `docs/CSS_VARIABLES.md`
- **Testing Guide**: `docs/TESTING_GUIDE.md`

### External Resources

- [Elementor Developer Docs](https://developers.elementor.com/)
- [Elementor Widget API](https://developers.elementor.com/creating-a-new-widget/)
- [Elementor Controls](https://developers.elementor.com/elementor-controls/)
- [ACF Documentation](https://www.advancedcustomfields.com/resources/)

### Support

- **GitHub Issues**: https://github.com/sanruiz/fibra/issues
- **Project Board**: https://github.com/users/sanruiz/projects/4

---

**Document Version**: 1.0  
**Last Updated**: December 12, 2025  
**Widgets Count**: 8  
**Maintainer**: Miguel Colmenares
