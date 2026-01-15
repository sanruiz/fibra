# Changelog

All notable changes to the SOMA WordPress theme will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

---

## [Unreleased]

---

## [3.1.19] - 2026-01-15

### Image Size Optimization & Documentation Updates

This patch release optimizes Elementor widget image sizes for better performance and adds documentation for GitHub Copilot Coding Agent workflow.

---

### ⚡ Performance

#### Elementor Widget Image Optimization

- **Documents Widget** - Changed from 'medium' to 'large' (1024x1024) image size for better quality/performance balance (#204)
- **TeamMember Widget** - Changed from 'full' to 'large' image size (80-95% file size reduction) (#204)
- **REST API Endpoints** - Updated DocumentsEndpoint, EventsEndpoint, and PortfolioEndpoint to use 'large' image size (#204)

### 📚 Documentation

- **AGENTS.md** - Added `.github/AGENTS.md` configuration file for GitHub Copilot Coding Agent workflow (#205)
  - Sprint branch workflow rules
  - PR requirements and base branch conventions
  - Testing and quality gate requirements
  - CHANGELOG update guidelines

---

### 🔗 Related Issues & PRs

- **Issue #203**: [Optimize image sizes in Elementor widgets](https://github.com/sanruiz/fibra/issues/203) - Closed
- **PR #204**: [perf: Use 'large' image size instead of 'full' in Elementor widgets](https://github.com/sanruiz/fibra/pull/204) - Merged
- **PR #205**: [docs: Add AGENTS.md for Copilot Coding Agent workflow](https://github.com/sanruiz/fibra/pull/205) - Merged

---

## [3.1.18] - 2026-01-14

### StockPrice Widget Currency Toggle

This patch release adds a toggle control to the StockPrice widget allowing users to show or hide the currency code.

---

### 🔄 Changed

#### StockPrice Widget

- **Currency Code Toggle** - Added 'Show Currency Code' toggle control to show/hide currency code (MXN, USD, EUR) (#192)
  - Default: Hidden (shows only symbol and price, e.g., "$45.67")
  - When enabled: Shows full format with code (e.g., "$45.67 MXN")
  - Preserves future flexibility if currency display needs to be re-enabled

### 🌐 Translations

- `Show Currency Code` → `Mostrar Código de Moneda`
- `Display currency code (e.g., MXN) after the price.` → `Mostrar código de moneda (ej. MXN) después del precio.`

---

### 🔗 Related Issues & PRs

- **Issue #192**: [Remove the currency](https://github.com/sanruiz/fibra/issues/192) - Closed
- **PR #200**: [feat(elementor): Add currency toggle to StockPrice widget](https://github.com/sanruiz/fibra/pull/200) - Merged

---

## [3.1.17] - 2026-01-13

### Week 6 Feature - Portfolio Detail Template and Elementor Widgets

This release implements the complete portfolio single page template with dedicated Elementor widgets for project galleries, technical specifications, expandable text content, and related projects.

---

### ✨ Added

#### Portfolio Single Template

- **Dedicated Template** - New `singles/portfolio.php` template for individual portfolio items (#27)
  - Custom header with project title and category
  - Flexible content sections via Elementor
  - Dark/light style variants support
  - Responsive layout for all devices

#### New Elementor Widgets

- **PortfolioGallery Widget** - Image gallery with Slick carousel integration (#28)
  - Responsive grid layout (3 columns desktop, 2 tablet, 1 mobile)
  - Lightbox functionality with navigation
  - Lazy loading for performance
  - Custom navigation arrows
  - ACF gallery field integration

- **PortfolioTechnicalSpecs Widget** - Technical specifications display (#29)
  - Key-value pairs layout
  - Icon support for each specification
  - Responsive grid system
  - ACF repeater field integration

- **TextWithReadMore Widget** - Expandable text content (#30)
  - Configurable initial text height
  - Smooth expand/collapse animation
  - Customizable "Read More" / "Read Less" button text
  - Typography and color controls
  - Gradient fade effect on collapsed state

#### Template Parts

- **Related Projects** - Reusable template part for showing related portfolio items (#31)
  - Query by same taxonomy terms
  - Configurable number of items
  - Dark-style variant support
  - Grid layout with hover effects

#### Breadcrumb Improvements

- **Standardized Styling** - Unified breadcrumb font sizes and colors (#191)
  - Consistent 14px font size across all views
  - Standardized separator and link colors
  - Improved mobile responsiveness

### 🐛 Fixed

- **PHPCS Compliance** - Renamed `$term` to `$current_term` to avoid WordPress global override
- **Script Dependencies** - Fixed `get_script_depends()` to include `soma-portfolio-gallery` ensuring widget JavaScript loads correctly

### ⚡ Performance

#### CI/CD Optimization

- **Skip Workflows for Documentation** - Added `paths-ignore` to CI/CD and CodeQL workflows
  - Commits with only `.md` files no longer trigger workflows
  - Saves CI resources and reduces unnecessary runs
  - Patterns excluded: `**/*.md`, `docs/**`, `.github/*.md`, `LICENSE`

### 📦 Files Changed

#### Added

- `singles/portfolio.php` - Portfolio single template
- `includes/Elementor/Widgets/PortfolioGallery.php` - Gallery widget (~380 lines)
- `includes/Elementor/Widgets/PortfolioTechnicalSpecs.php` - Tech specs widget (~280 lines)
- `includes/Elementor/Widgets/TextWithReadMore.php` - Expandable text widget (~320 lines)
- `assets/css/widgets/portfolio-gallery.css` - Gallery styles
- `assets/css/widgets/portfolio-technical-specs.css` - Tech specs styles
- `assets/css/widgets/text-with-read-more.css` - Expandable text styles
- `assets/js/widgets/portfolio-gallery.js` - Gallery JavaScript with Slick integration
- `assets/js/widgets/text-with-read-more.js` - Expand/collapse functionality
- `template-parts/related-projects.php` - Related projects template part
- `tests/Integration/Elementor/PortfolioGalleryWidgetTest.php` - Gallery widget tests
- `tests/Integration/Elementor/PortfolioTechnicalSpecsWidgetTest.php` - Tech specs tests
- `tests/Integration/Elementor/TextWithReadMoreWidgetTest.php` - Expandable text tests

#### Modified

- `includes/Elementor/Loader.php` - Register new widgets, styles, and scripts
- `includes/Elementor/Widgets/Breadcrumb.php` - Standardized styling
- `assets/css/widgets/breadcrumb.css` - Updated font sizes and colors
- `tests/Integration/Elementor/AllWidgetsTest.php` - Include new widgets in test arrays

---

### 🔗 Related Issues & PRs

- **Issue #27**: [Template de Proyecto Individual](https://github.com/sanruiz/fibra/issues/27) - Closed
- **Issue #28**: [Galería de Imágenes del Proyecto](https://github.com/sanruiz/fibra/issues/28) - Closed
- **Issue #29**: [Datos Clave del Proyecto](https://github.com/sanruiz/fibra/issues/29) - Closed
- **Issue #30**: [Sección de Sostenibilidad del Proyecto](https://github.com/sanruiz/fibra/issues/30) - Closed
- **Issue #31**: [Proyectos Relacionados](https://github.com/sanruiz/fibra/issues/31) - Closed
- **Issue #191**: [Standardize breadcrumb font sizes](https://github.com/sanruiz/fibra/issues/191) - Closed
- **PR #195**: [feat(portfolio): Week 6 - Portfolio detail template and Elementor widgets](https://github.com/sanruiz/fibra/pull/195)

---

## [3.1.16] - 2026-01-09

### AnnualReports Widget Enhancements

#### ✨ Added

- **Image Height Controls** - New Elementor SLIDER controls to customize image heights
  - `image_height_full_width` - Controls image height in full-width variant (default: 307px)
  - `image_height_three_columns` - Controls image height in three-columns variant (default: 348px)
  - Responsive support with px and vh units
  - Conditional display based on style_variant

#### 🔄 Changed

- **Removed Container Wrapper** - Eliminated `<div class="container">` wrapper from widget output
  - Widget now adapts to its Elementor context (sections, columns)
  - Improved layout flexibility for different page configurations
  - No CSS, JS, or test dependencies were affected

- **Image Display Improvements** - Enhanced image rendering in both layout variants
  - Full-width variant: Added `aspect-ratio: 16/9`, `overflow: hidden`, `object-fit: cover`
  - Three-columns variant: Added `aspect-ratio: 59/69`, `overflow: hidden`, `object-fit: cover`
  - Removed fixed `max-height: 348px` in favor of Elementor-controlled height
  - Images now fill container properly with consistent cropping

#### 📦 Files Changed

- `includes/Elementor/Widgets/AnnualReports.php` - Added Image style section with height controls, removed container div
- `assets/css/widgets/annual-reports.css` - Added aspect-ratio, overflow, object-fit properties for consistent image display
- `tests/Integration/Elementor/AnnualReportsWidgetTest.php` - Added assertions for image height controls
- `docs/WIDGETS.md` - Added Image style controls documentation
- `languages/soma.pot` - Updated translation template
- `languages/es_ES.po` - Added Spanish translations

#### 🌐 Translations

- `Image Height` → `Altura de Imagen`

---

## [3.1.15] - 2026-01-08

### TeamMembers Widget Enhancements

#### ✨ Added

- **Show Photo Control** - New SWITCHER control to show/hide member photos (default: Show)
- **Full Card Link** - Entire card is now clickable when "Link to Profile" is enabled
  - Hover effects apply to entire card area with opacity transition
  - Improves user experience and click target area
  - Uses `.team-member-link` wrapper element

#### 🔄 Changed

- **Card Link Behavior** - Link moved from member name to entire card
- **CSS Refactoring** - Removed old `.member-name a` styles, added `.team-member-link` flex container

#### 🌐 Translations

- `Show Photo` → `Mostrar Foto`
- `Display member featured image.` → `Mostrar la imagen destacada del miembro.`

#### 📦 Files Changed

- `includes/Elementor/Widgets/TeamMembers.php` - Added show_photo control, full card link
- `assets/css/widgets/team-members.css` - Card link styles and hover effects
- `languages/soma.pot` - Updated translation template
- `languages/es_ES.po` - Spanish translations
- `docs/WIDGETS.md` - Updated documentation

---

## [3.1.14] - 2026-01-08

### Feature - Show/Hide Controls for ShareQuotation and TeamMember Widgets (Issue #178)

This feature adds visibility controls to ShareQuotation and TeamMember Elementor widgets, allowing editors to customize which elements are displayed. Also improves TeamMember card interaction by making the entire card clickable.

---

### ✨ Added

#### ShareQuotation Widget Enhancements

- **Volume Visibility Control** - Switcher control to show/hide volume information (default: hidden)
  - Maintains 3-column layout when hidden on desktop
  - Hides empty column on mobile to save space
- **Date Visibility Control** - Switcher control to show/hide date/time (default: hidden)
- **Change Visibility Control** - Switcher control to show/hide price change (default: hidden)
- **Percentage Visibility Control** - Switcher control to show/hide percentage change (default: hidden)
- **Conditional Rendering** - Smart rendering that shows combined change/percent or individual values based on control settings

#### TeamMember Widget Enhancements

- **Photo Visibility Control** - Switcher control to show/hide featured image (default: visible)
- **Full Card Link** - Entire card is now clickable when a member is selected/detected
  - Hover effects apply to entire card area
  - Improves user experience and interaction
  - Maintains visual hierarchy

### 🔄 Changed

#### ShareQuotation Widget

- Modified render method to conditionally display volume, date, change, and percent fields
- Updated CSS to maintain column spacing when volume is hidden

#### TeamMember Widget

- Wrapped card content in clickable link when applicable
- Updated alt text for images to use member name
- Added variables for show_photo, show_logo, and show_featured_text settings

### 🌐 Translations

#### ShareQuotation Widget (4 strings)

- `Show Volume` → `Mostrar Volumen`
- `Show Date` → `Mostrar Fecha`
- `Show Change` → `Mostrar Cambio`
- `Show Percentage` → `Mostrar Porcentaje`

#### TeamMember Widget (1 string)

- `Show Photo` → `Mostrar Foto`

### 🧪 Tests

#### ShareQuotation Widget Tests

- Added test for show_volume control and default value
- Added test for show_date control and default value
- Added test for show_change control and default value
- Added test for show_percent control and default value
- Updated expected controls list in test_has_controls

#### TeamMember Widget Tests

- Added test for show_photo control presence
- Added test for show_photo default value (yes)
- Added test for card link wrapper presence
- Added test for use_card_link variable

### 📦 Files Changed

#### Modified

- `includes/Elementor/Widgets/ShareQuotation.php` - Added 4 visibility controls, conditional rendering
- `includes/Elementor/Widgets/TeamMember.php` - Added photo control, full-card link
- `assets/css/widgets/share-quotation.css` - Styles for hidden states (maintain column spacing)
- `assets/css/widgets/team-member.css` - Card link styles and hover effects
- `tests/Integration/Elementor/ShareQuotationWidgetTest.php` - New control tests
- `tests/Integration/Elementor/TeamMemberWidgetTest.php` - New control and card link tests
- `CHANGELOG.md` - Documented changes

---

### 🔗 Related Issues & PRs

- **Issue #178**: [Add Show/Hide controls to ShareQuotation and TeamMember widgets](https://github.com/sanruiz/fibra/issues/178)

---

## [3.1.13] - 2026-01-08

### Week 4 Feature - Events Elementor Widget (Issue #24)

This feature adds a new Events Elementor widget for displaying corporate events on the "Página de Eventos Relevantes" page. The widget includes month/year filtering, AJAX loading, and responsive grid layout.

---

### ✨ Added

#### New Elementor Widget

- **Events Widget** - Corporate events display with month filtering (#24)
  - **Responsive Grid Layout**:
    - 3-column grid on desktop
    - 2-column grid on tablet (991px)
    - 1-column on mobile (767px)
  - **Month/Year Filtering**:
    - AJAX-powered month filtering via REST API
    - "See All" option to show all events
    - Active month highlighting
    - Mobile-friendly filter toggle
  - **Event Card Display**:
    - Featured image with aspect ratio control
    - Event title with configurable typography
    - Event date or published date display
    - Hover effects with zoom transition
  - **Elementor Controls**:
    - Number of events to display
    - Sort order (ASC/DESC)
    - Sort by (event date or published date)
    - "See All" button text customization
    - Container, filter, and card style sections
    - Full typography and color controls
  - **REST API Integration**:
    - Uses existing `/wp-json/soma/events` endpoint
    - Month-based filtering parameter
    - Elementor editor preview support

### 📦 Files Changed

#### Added

- `includes/Elementor/Widgets/Events.php` - New Events widget (~490 lines)
- `assets/css/widgets/events.css` - Widget-specific styles (~185 lines)
- `assets/js/widgets/events.js` - AJAX loading and filtering (~173 lines)
- `tests/Integration/Elementor/EventsWidgetTest.php` - Integration tests (14 test methods)

#### Modified

- `includes/Elementor/Loader.php` - Register Events widget, styles, and scripts
- `tests/Integration/Elementor/AllWidgetsTest.php` - Include Events widget in test arrays
- `languages/soma.pot` - Updated translation template
- `languages/es_ES.po` - Spanish translations for new strings

---

### 🔗 Related Issues & PRs

- **Issue #24**: [Página de Eventos Relevantes](https://github.com/sanruiz/fibra/issues/24)
- **PR #170**: [feat(elementor): Add Events widget](https://github.com/sanruiz/fibra/pull/170)

---

### Week 4 Feature - Reports Elementor Widget (Issues #21 & #20)

This feature adds a new Reports Elementor widget for displaying financial reports with year filtering. The widget supports both the "Reportes Anuales" page (Issue #21) and "Reportes Trimestrales" page (Issue #20) through category selection and configurable layout variants.

---

### ✨ Added

#### New Elementor Widget

- **Reports Widget** - Financial reports display with year filtering (#21, #20)
  - **Two-Column Layout** (`full-width` variant):
    - Left column: Year filter buttons (vertical list)
    - Right column: Documents grid with download links
    - Used for "Reportes Anuales" page
  - **Three-Column Layout** (`three-columns` variant):
    - Compact 3-column document grid
    - Year filtering in horizontal header
    - Used for "Reportes Trimestrales" page
  - **Dynamic Year Filtering**:
    - AJAX-powered year filtering via REST API
    - All years loaded initially, filterable by click
    - Active year highlighting
  - **Document Display**:
    - Featured image thumbnail
    - Document title and description
    - Download link with file type icon
    - Publication date
  - **Elementor Controls**:
    - Style variant selection (`full-width` or `three-columns`)
    - Category selector (filters by documents-taxonomy)
    - Typography controls for titles, descriptions, dates
    - Color controls for text and backgrounds
    - Responsive spacing and layout controls
  - **REST API Integration**:
    - Uses existing `/wp-json/soma/documents` endpoint
    - Category filtering via taxonomy
    - Year-based document grouping

### 📦 Files Changed

#### Added

- `includes/Elementor/Widgets/AnnualReports.php` - New Reports widget (~440 lines)
- `assets/css/widgets/annual-reports.css` - Widget-specific styles (~280 lines)
- `tests/Integration/Elementor/AnnualReportsWidgetTest.php` - Integration tests (14 test methods)

#### Modified

- `includes/Elementor/Loader.php` - Register AnnualReports widget and assets
- `tests/Integration/Elementor/AllWidgetsTest.php` - Include AnnualReports widget in test arrays
- `languages/soma.pot` - Updated translation template
- `languages/es_ES.po` - Spanish translations for new strings

---

### 🔗 Related Issues & PRs

- **Issue #21**: [Página de Reportes Anuales](https://github.com/sanruiz/fibra/issues/21)
- **Issue #20**: [Página de Reportes Trimestrales](https://github.com/sanruiz/fibra/issues/20)

---

### Week 4 Feature - ShareQuotation Elementor Widget (Issue #23)

This feature adds a new ShareQuotation Elementor widget for displaying comprehensive stock market information on the "Información Bursátil" page.

---

### ✨ Added

#### New Elementor Widget

- **ShareQuotation Widget** - Comprehensive stock market information display widget (#23)
  - Current stock price with currency formatting
  - Price change indicator (positive/negative with color coding)
  - Percentage change display
  - Trading volume with number formatting
  - Market time display
  - **52-Week/Month High-Low Carousel**:
    - Slick carousel integration for high/low price ranges
    - 52-week high and low prices
    - Monthly high and low prices
    - Auto-advancement with navigation arrows
  - **Configurable Layout**:
    - Horizontal layout with flexible sections
    - Responsive design for mobile/tablet
  - **Styling Controls**:
    - Background color customization
    - Typography controls for all text elements
    - Price color (positive/negative) configuration

#### Shared Helper Functions

- **`soma_format_stock_price()`** - Format stock price with currency symbol and decimals
- **`soma_format_stock_change()`** - Format price change with +/- indicator
- **`soma_format_stock_volume()`** - Format trading volume with number separators
- **`soma_format_market_time()`** - Format market timestamp for display

#### Code Refactoring

- **StockPrice Widget** - Refactored to use shared helper functions from `Utils/Helpers.php`
- **DRY Principle** - Eliminated duplicate formatting logic between StockPrice and ShareQuotation widgets

### 🐛 Fixed

#### Multilang Dropdown Display

- **TeamMember Widget** - Fixed dropdown showing raw `[:en]Name[:es]Nombre[:]` format
  - Changed `$member->post_title` to `get_the_title($member->ID)` to trigger wp-multilang filters
- **ContactForm Widget** - Fixed same multilang issue in CF7 form selector
  - Changed `$form->post_title` to `get_the_title($form->ID)`

### 📦 Files Changed

#### Added

- `includes/Elementor/Widgets/ShareQuotation.php` - New ShareQuotation widget
- `assets/css/widgets/share-quotation.css` - Widget-specific styles
- `tests/Integration/Elementor/ShareQuotationWidgetTest.php` - Integration tests

#### Modified

- `includes/Elementor/Loader.php` - Register ShareQuotation widget
- `includes/Elementor/Widgets/StockPrice.php` - Refactored to use shared helpers
- `includes/Elementor/Widgets/TeamMember.php` - Fixed multilang dropdown display
- `includes/Elementor/Widgets/ContactForm.php` - Fixed multilang dropdown display
- `includes/Utils/Helpers.php` - Added 4 new stock formatting helper functions
- `tests/Integration/Elementor/AllWidgetsTest.php` - Include ShareQuotation widget
- `languages/soma.pot` - Updated translation template
- `languages/es_ES.po` - Spanish translations for new strings

---

### 🔗 Related Issues & PRs

- **Issue #23**: [Página de Información Bursátil](https://github.com/sanruiz/fibra/issues/23)

---

## [3.1.12] - 2026-01-08

### Week 4 Feature - TeamMember Elementor Widget

This release adds a new TeamMember Elementor widget that replicates the team member single page functionality, enabling flexible integration of team member profiles into any page layout via Elementor.

---

### ✨ Added

#### New Elementor Widget

- **TeamMember Widget** - New Elementor widget for displaying individual team member profiles
  - Replicates complete team-members single page structure and design
  - Auto-detects team member from URL context when used in global templates (Header/Footer)
  - Manual team member selection via Elementor control for custom layouts
  - **Responsive 2-Column Flexbox Layout**:
    - Left column: Name, Position, Featured Text (50% width, `padding-right: 15px`)
    - Right column: Featured Image, Biography (50% width, `padding-left: 15px`, `margin-left: auto`)
    - 50/50 split using `calc(100% * (6/12))`
    - Container padding: `60px 0 140px` (desktop), `45px 0 80px` (mobile)
    - Section margins: `70px` (desktop), `45px` (mobile)
  - **Typography Controls**:
    - Name styling with configurable margins
    - Position/title styling
    - Biography content with `18px` font-size, `25px` line-height
  - **Image Handling**:
    - Featured image with `max-width: 410px`
    - Responsive behavior on mobile
  - **Widget Independence**:
    - Uses widget-specific class `soma-team-member`
    - Completely independent of partial styles
    - Future-proof for partial elimination

### 📦 Files Changed

#### Added

- `includes/Elementor/Widgets/TeamMember.php` - New TeamMember widget (531 lines)
- `assets/css/widgets/team-member.css` - Widget-specific styles with flexbox layout

#### Modified

- `wp-content/themes/soma/CHANGELOG.md` - Version documentation

---

### 🔗 Related Issues & PRs

- **PR #164**: [feat(elementor): Add TeamMember widget](https://github.com/sanruiz/fibra/pull/164) - Merged to week-4

---

## [3.1.11] - 2025-01-06

### Week 4 Patch - JavaScript & Footer Bug Fixes

This patch release fixes the stock price display issue on the company page and resolves HTML tags being visible in the mobile footer.

---

### 🐛 Fixed

#### JavaScript Execution

- **ShareQuotation Component** - Fixed stock price showing $0 on `/company/` page (#158)
  - Added `safeInit()` wrapper for Slick-dependent handlers to prevent uncaught errors from halting JS execution
  - Slick carousel errors now logged as warnings instead of crashing the script
  - Removed debug console.logs from `main.js` and `ShareQuotation.js`

#### Footer Partial

- **HTML Tags Visible on Mobile** - Fixed `<br>` tags showing as literal text instead of rendering (#159)
  - Changed `logo_subtext` from `esc_html()` to `wp_kses_post()` to allow safe HTML
  - Changed `copyright` from `esc_html()` to `wp_kses_post()` for consistency with Elementor widget

---

### 🔗 Related Issues & PRs

- **PR #160**: [fix: Prevent Slick carousel crash from blocking ShareQuotation](https://github.com/sanruiz/fibra/pull/160) - Merged
- **PR #161**: [fix: Footer <br> tags visible on mobile](https://github.com/sanruiz/fibra/pull/161) - Merged

---

## [3.1.10] - 2025-01-05

### Week 4 Patch - Stock Data Admin & Documents Widget Enhancements

This release enhances the Stock Data admin panel with configurable sync intervals, adds category filtering to the Documents widget, improves TeamMembers widget field mapping clarity, and completes Spanish translations for all Week 4 components.

---

### ✨ Added

#### Documents Widget Category Filter

- **Category Dropdown** - New Elementor control to filter documents by taxonomy category (#151)
- **Dynamic Filtering** - Documents automatically filtered based on selected category
- **Empty State Handling** - Shows all documents when no category selected

#### Stock Data Admin Panel

- **Configurable Sync Interval** - New dropdown to select stock data update frequency (1, 2, 3, 6, 12, or 24 hours)
- **Test API Connection Button** - Quick button to test Yahoo Finance API connectivity without waiting for cron
- **Sync Status Display** - Shows last sync time, current cached data, and next scheduled sync
- **Real-time Feedback** - AJAX-powered status updates and API test results

#### External JavaScript Module

- **stock-data.js** - New external JavaScript file using IIFE module pattern
- **wp_localize_script Integration** - Proper WordPress data passing (nonce, i18n strings, AJAX URL)

#### Documentation

- **Elementor Widgets Instructions** - New `.github/instructions/elementor-widgets.instructions.md` with complete widget development workflow
- **Global Styles Examples** - Documentation for using `Global_Colors` and `Global_Typography` in Elementor widgets

### 🔄 Changed

#### Code Quality Improvements

- **External JavaScript** - Refactored inline JavaScript (~100 lines) to external `assets/js/admin/stock-data.js`
- **External CSS** - Refactored inline CSS (~35 lines) to external `assets/css/admin/stock-data.css`
- **Explicit Cron Intervals** - Changed from dynamic loop to explicit interval definitions for PHPCS compliance
- **Removed Unnecessary Comments** - Cleaned up phpcs:ignore comment that was no longer needed

#### TeamMembers Widget

- **Variable Naming Clarity** - Renamed `$member_title` to `$member_position` to better reflect ACF field purpose (#153)
- **Field Mapping Documentation** - Added inline comments documenting image (Featured Image) and position (ACF `team_member_info.title`) field sources

### 🔒 Security

- **CSRF Protection** - Added nonce verification (`check_ajax_referer`) to `ajax_get_status` AJAX handler
- **XSS Prevention** - Added `escapeHtml()` function to sanitize external API data before DOM insertion
- **Error Handling** - Improved error feedback in `loadStatus()` to match `testApiConnection()` pattern

### 🌐 Translations

#### Stock Data Admin (22 strings)

- `Test API Connection` → `Probar Conexión API`
- `Testing...` → `Probando...`
- `Request failed` → `Solicitud fallida`
- `Last Sync:` → `Última Sincronización:`
- `No sync has been performed yet.` → `No se ha realizado ninguna sincronización.`
- `Current Data:` → `Datos Actuales:`
- `Market Time:` → `Hora del Mercado:`
- `Next Scheduled Sync:` → `Próxima Sincronización:`
- `every` → `cada`
- `Every 1 Hour` → `Cada 1 Hora`
- `Every 2 Hours` → `Cada 2 Horas`
- `Every 3 Hours` → `Cada 3 Horas`
- `Every 6 Hours` → `Cada 6 Horas`
- `Every 12 Hours` → `Cada 12 Horas`
- `Every 24 Hours` → `Cada 24 Horas`
- `Stock data API not configured` → `API de datos bursátiles no configurada`
- `Failed to fetch stock data` → `Error al obtener datos bursátiles`
- `API returned HTTP error` → `La API devolvió error HTTP`
- `Stock data response empty/invalid` → `respuesta está vacía o es inválida`
- `Stock data updated` → `Datos bursátiles actualizados`
- `Unauthorized` → `No autorizado`
- `Stock Data` → `Datos Bursátiles`

#### TeamMembers Widget (6 strings)

- `Category` → `Categoría`
- `Section Title` → `Título de Sección`
- `Margin Bottom` → `Margen Inferior`
- `Name` → `Nombre`
- `Margin Top` → `Margen Superior`
- `Position` → `Cargo`

#### Breadcrumb Widget (11 strings)

- `SOMA Breadcrumb` → `Migas de Pan SOMA`
- `Breadcrumb Settings` → `Configuración de Migas de Pan`
- `Separator` → `Separador`
- `Show Home` → `Mostrar Inicio`
- `Show Current Page` → `Mostrar Página Actual`
- `Style` → `Estilo`
- `Current Page Color` → `Color de Página Actual`
- `Separator Color` → `Color del Separador`
- `Home` → `Inicio`
- `Breadcrumb Navigation` → `Navegación de Migas de Pan`

#### Portfolio Widget (28 strings)

- `Main Category` → `Categoría Principal`
- `Filter Categories` → `Filtrar Categorías`
- `Initial Posts` → `Publicaciones Iniciales`
- `Year` → `Año`
- `Display` → `Visualización`
- `Default View` → `Vista Predeterminada`
- `Show Filters` → `Mostrar Filtros`
- `Show View Toggle` → `Mostrar Cambio de Vista`
- `Show Year` → `Mostrar Año`
- `Show City` → `Mostrar Ciudad`
- `FibraSOMA (Dark)` → `FibraSOMA (Oscuro)`
- `SOMA (Light)` → `SOMA (Claro)`
- `"All" Filter Text` → `Texto del Filtro "Todos"`
- `List View Text` → `Texto de Vista Lista`
- `Grid View Text` → `Texto de Vista Cuadrícula`
- `Loading Text` → `Texto de Carga`
- `Loading more` → `Cargando más`
- `Background Color` → `Color de Fondo`
- `Filters` → `Filtros`
- `Active Color` → `Color Activo`
- `Border Color` → `Color del Borde`
- `City` → `Ciudad`
- `Hover Zoom Scale` → `Escala de Zoom al Pasar`
- `Transition Duration` → `Duración de Transición`
- `View Toggle` → `Cambio de Vista`

#### Documents Widget (1 string)

- `Select a category to filter documents...` → `Selecciona una categoría para filtrar documentos...`

#### Helper Functions (4 strings)

- `Search results for: %s` → `Resultados de búsqueda para: %s`
- `Page Not Found` → `Página No Encontrada`
- `Blog` → `Blog`
- `Home` → `Inicio`

### 📦 Files Changed

#### Added

- `assets/js/admin/stock-data.js` - External JavaScript module for admin panel (150 lines)
- `assets/css/admin/stock-data.css` - External CSS for admin panel
- `.github/instructions/elementor-widgets.instructions.md` - Elementor widget development workflow

#### Modified

- `includes/Admin/StockData.php` - Sync interval control, test API button, status display, external JS/CSS enqueue, CSRF protection
- `includes/Elementor/Widgets/Documents.php` - Added category filter control
- `includes/Elementor/Widgets/TeamMembers.php` - Variable rename for clarity, field mapping documentation
- `languages/soma.pot` - Updated translation template
- `languages/es_ES.po` - Spanish translations (48 new strings)
- `languages/es_ES.mo` - Compiled translation binary

---

### 🔗 Related Issues & PRs

- **PR #150**: [feat: Stock Data admin enhancements](https://github.com/sanruiz/fibra/pull/150) - Merged
- **PR #151**: [feat: Add category filter to Documents widget](https://github.com/sanruiz/fibra/pull/151) - Merged
- **PR #153**: [fix: TeamMembers widget field mapping clarity](https://github.com/sanruiz/fibra/pull/153) - Merged

---

## [3.1.9] - 2025-01-03

### Week 4 - TeamMembers Widget & StockData Refactor

This release improves the TeamMembers Elementor widget with modular single-category design and SOMA CSS variables integration, plus refactors the StockData system to use ACF options.

---

### ✨ Added

#### TeamMembers Widget

- **Modular Design** - Widget now accepts a single category per instance for flexible page layouts (#145)
- **Integration Tests** - 18 tests covering widget structure, CSS variables, and responsive breakpoints
- **Column Configuration** - Support for 2, 3, or 4 column layouts

#### StockData System

- **ACF Options Integration** - Stock API credentials now stored in ACF options page (#147)
- **WordPress Options Fallback** - Graceful fallback to WordPress options when ACF not available
- **Comprehensive Tests** - 26 new tests for StockData admin class and REST endpoint

### 🔄 Changed

#### CSS Variables Integration

- **Typography** - Name and position use `--soma-font-size-h3`, `--soma-font-size-body`, `--soma-font-family-primary`
- **Colors** - Text colors use `--soma-color-text-primary`, `--soma-color-text-secondary`
- **Spacing** - Grid gaps and padding use `--soma-spacing-*` variables
- **Transitions** - Animations use `--soma-transition-base`, `--soma-transition-slow`
- **Layout** - Container uses `--soma-container-max-width`

#### Responsive Breakpoints

- **Tablet (991px)** - Uses `--soma-font-size-h5`, `--soma-font-size-body-mobile`
- **Mobile (767px)** - Uses `--soma-font-size-h3-mobile`, `--soma-font-size-small`

#### StockData Refactor

- **Configuration** - API credentials moved from hardcoded values to ACF options
- **Type Safety** - Added explicit return type hints and casts

### 📦 Files Changed

#### Added

- `tests/Integration/Elementor/TeamMembersWidgetTest.php` - 18 integration tests
- `tests/Integration/API/StockDataEndpointTest.php` - REST API tests
- `tests/Integration/Admin/StockDataTest.php` - Admin integration tests
- `tests/Unit/Admin/StockDataTest.php` - Unit tests
- `acf-json/group_stock_data_settings.json` - ACF field group for stock settings
- `local.env.example` - Environment variables example file

#### Modified

- `includes/Elementor/Widgets/TeamMembers.php` - Modular single-category design with Elementor defaults
- `assets/css/widgets/team-members.css` - Full SOMA CSS variables integration
- `includes/Admin/StockData.php` - ACF options integration with WordPress fallback
- `includes/Admin/ThemeSettings.php` - Stock data settings tab

---

### 🌐 Translations

#### TeamMembers Widget (20 strings)

- `All Categories` → `Todas las Categorías`
- `Number of Members` → `Número de Miembros`
- `Show Section Title` → `Mostrar Título de Sección`
- `Title Tag` → `Etiqueta de Título`
- `Custom Title` → `Título Personalizado`
- `Show Position` → `Mostrar Cargo`
- `Link to Profile` → `Enlazar al Perfil`
- `Grayscale Images` → `Imágenes en Escala de Grises`
- `No Members Text` → `Texto Sin Miembros`
- `Aspect Ratio` → `Relación de Aspecto`
- `Placeholder Background` → `Fondo de Marcador de Posición`
- `Underline on Hover` → `Subrayado al Pasar`
- `Name Style` → `Estilo de Nombre`
- `Position Style` → `Estilo de Cargo`
- `Card Style` → `Estilo de Tarjeta`
- `Grid Gap` → `Espacio de Cuadrícula`
- `Card Padding` → `Relleno de Tarjeta`
- `Border Radius` → `Radio de Borde`
- `Order By` → `Ordenar Por`
- `Order` → `Orden`

#### Breadcrumb Widget (11 strings)

- `SOMA Breadcrumb` → `Migas de Pan SOMA`
- `Breadcrumb Settings` → `Configuración de Migas de Pan`
- `Separator` → `Separador`
- `Show Home` → `Mostrar Inicio`
- `Show Current Page` → `Mostrar Página Actual`
- `Style` → `Estilo`
- `Current Page Color` → `Color de la Página Actual`
- `Separator Color` → `Color del Separador`
- `Home` → `Inicio`
- `Breadcrumb Navigation` → `Navegación de Migas de Pan`

#### Portfolio Widget (28 strings)

- `Main Category` → `Categoría Principal`
- `Filter Categories` → `Filtrar Categorías`
- `Initial Posts` → `Publicaciones Iniciales`
- `Year` → `Año`
- `Display` → `Visualización`
- `Default View` → `Vista Predeterminada`
- `Show Filters` → `Mostrar Filtros`
- `Show View Toggle` → `Mostrar Cambio de Vista`
- `Show Year` → `Mostrar Año`
- `Show City` → `Mostrar Ciudad`
- `FibraSOMA (Dark)` → `FibraSOMA (Oscuro)`
- `SOMA (Light)` → `SOMA (Claro)`
- `"All" Filter Text` → `Texto del Filtro "Todos"`
- `List View Text` → `Texto de Vista Lista`
- `Grid View Text` → `Texto de Vista Cuadrícula`
- `Loading Text` → `Texto de Carga`
- `Loading more` → `Cargando más`
- `Background Color` → `Color de Fondo`
- `Filters` → `Filtros`
- `Active Color` → `Color Activo`
- `Border Color` → `Color del Borde`
- `City` → `Ciudad`
- `Hover Zoom Scale` → `Escala de Zoom al Pasar`
- `Transition Duration` → `Duración de la Transición`
- `View Toggle` → `Cambio de Vista`

#### Helper Functions (3 strings)

- `Search results for: %s` → `Resultados de búsqueda para: %s`
- `Page Not Found` → `Página No Encontrada`
- `Blog` → `Blog`

---

### 🔗 Related Issues & PRs

- **PR #147**: [refactor: Move StockData API credentials to ACF options](https://github.com/sanruiz/fibra/pull/147) - Merged
- **PR #145**: [feat(elementor): TeamMembers widget modular design with CSS variables](https://github.com/sanruiz/fibra/pull/145) - Merged
- **PR #144**: [feat(i18n): Add complete Spanish translations for Week 4 widgets](https://github.com/sanruiz/fibra/pull/144) - Merged

---

## [3.1.8] - 2025-12-31

### Week 4 Patch - Enhanced Portfolio Widget

This release completes the Portfolio Elementor widget with dynamic category filtering and improved layout controls.

---

### ✨ Added

#### Portfolio Widget Enhancements

- **Dynamic Category Filters** - Portfolio widget now displays interactive category filter buttons that filter content via AJAX (Issue #17)
- **Filter Position Control** - New Elementor control to position filters left, center, or right
- **Filter Spacing Control** - New control to adjust spacing between filter buttons using CSS `gap`
- **Active Filter Styling** - Configurable colors for active/hover filter states
- **AJAX Category Loading** - Categories loaded dynamically from REST API (`/wp-json/soma/portfolio?include_categories=true`)
- **Portfolio Widget Tests** - 17 integration tests covering widget controls, rendering, and API integration

#### REST API Improvements

- **Portfolio Categories** - New `include_categories=true` parameter returns available portfolio categories
- **Category Filtering** - Filter portfolio items by category slug via `category` parameter
- **Fibrasoma Exclusion** - Fibrasoma category automatically excluded from public category lists

### 🔄 Changed

#### CSS & Styling

- **Filter Layout** - Changed from `margin-right` to `gap` for filter spacing (better flexbox support)
- **Font Family** - Updated to "Neue Haas Unica Pro" in CSS variables
- **Responsive Filters** - Improved mobile responsiveness for filter buttons

#### Code Quality

- **PHPCS Compliance** - Fixed unsanitized `$_GET` input in Portfolio widget with proper `sanitize_text_field()`
- **Test Cleanup** - Removed deprecated `setAccessible(true)` call in StockPriceWidgetTest

### 📦 Files Changed

#### Added

- `assets/js/widgets/portfolio.js` - Client-side AJAX filtering logic
- `tests/Integration/Elementor/PortfolioWidgetTest.php` - 17 integration tests

#### Modified

- `includes/Elementor/Widgets/Portfolio.php` - Dynamic filters, AJAX loading, new controls
- `includes/API/Endpoints/PortfolioEndpoint.php` - Category filtering support
- `assets/css/widgets/portfolio.css` - Flexbox layout with gap, improved responsive
- `assets/css/variables.css` - Font family update
- `includes/Elementor/Loader.php` - AJAX action registration
- `tests/Integration/Elementor/StockPriceWidgetTest.php` - Removed deprecated method

---

### 🔗 Related Issues & PRs

- **Issue #17**: [Widgets para la sección de portafolio de la home y su template](https://github.com/sanruiz/fibra/issues/17) - Closed
- **PR #140**: [feat(elementor): Enhance Portfolio widget with dynamic filters](https://github.com/sanruiz/fibra/pull/140) - Merged

---

## [3.1.7] - 2025-12-30

### Week 4 Feature - Breadcrumb Navigation Widget

This release adds a new Breadcrumb Elementor widget for content page navigation.

---

### ✨ Added

#### New Elementor Widget

- **Breadcrumb Widget** - Clean navigation breadcrumb widget for content pages (#134)
  - Uses Global_Colors and Global_Typography from Elementor Site Kit
  - Customizable separator (text or icon)
  - Home icon option with configurable icon
  - Current page highlighting
  - Responsive container max-width control
  - Full typography and color controls for all elements

#### Helper Functions

- **`soma_get_breadcrumb_items()`** - Flexible breadcrumb generation helper function
  - Supports hierarchical pages and custom post types
  - Returns array of breadcrumb items with title and URL
  - Handles parent page traversal automatically

### 📦 Files Changed

#### Added

- `includes/Elementor/Widgets/Breadcrumb.php` - Breadcrumb widget (425 lines)
- `assets/css/widgets/breadcrumb.css` - Widget styles (49 lines)
- `tests/Integration/Elementor/BreadcrumbWidgetTest.php` - 15 integration tests

#### Modified

- `includes/Elementor/Loader.php` - Register Breadcrumb widget
- `includes/Utils/Helpers.php` - Added `soma_get_breadcrumb_items()` function
- `tests/Integration/Elementor/AllWidgetsTest.php` - Include Breadcrumb widget

---

### 🔗 Related Issues & PRs

- **PR #134**: [feat: Add Breadcrumb Elementor widget](https://github.com/sanruiz/fibra/pull/134) - Merged to week-4
- **PR #135**: [Week 4: Breadcrumb Widget & Documents Improvements](https://github.com/sanruiz/fibra/pull/135) - Merged to main
- **Issue #17**: [Página Portafolio (Vista General)](https://github.com/sanruiz/fibra/issues/17) - Closed
- **Issue #18**: [Página Equipo](https://github.com/sanruiz/fibra/issues/18) - Closed
- **Issue #19**: [Páginas Administrador Interno / Historia / Diferenciadores](https://github.com/sanruiz/fibra/issues/19) - Closed

---

## [3.1.6] - 2025-12-30

### Week 4 Patch - Spanish Translations & Documents Widget Improvements

This patch release completes Spanish translations for all theme components and improves the Documents widget layout.

---

### ✨ Added

#### Internationalization (i18n)

- **Complete Spanish Translations** - Added 170+ Spanish translations covering all theme components
- **Elementor Widgets** - All 10 widget titles, descriptions, and controls translated
- **PostType Enum** - Labels for Portfolio, News, Careers, Team Members, Events, Documents
- **CacheTag Enum** - Cache tag descriptions translated
- **LogLevel Enum** - All 8 PSR-3 log level labels translated
- **Template Names** - Page templates translated (Business Unit, Navigation Sidebar, Elementor)
- **UI Controls** - All Elementor control labels, placeholders, and descriptions

### 🐛 Fixed

#### Code Quality

- **PHPCS Compliance** - Fixed `count()` inside while loop in Documents widget (moved to variable before loop)

### 🔄 Changed

#### Documents Widget

- **Layout Improvements** - Enhanced grid layout for better document display
- **Responsive Styles** - Improved mobile responsiveness for document cards

### 📦 Files Changed

#### Modified

- `languages/es_ES.po` - Added 170+ Spanish translations
- `languages/es_ES.mo` - Compiled binary translation file
- `includes/Elementor/Widgets/Documents.php` - PHPCS fix and layout improvements
- `assets/css/widgets/documents.css` - Responsive style improvements

---

### 🔗 Related Issues & PRs

- **PR #131**: [fix: Add Spanish translations and improve Documents widget](https://github.com/sanruiz/fibra/pull/131) - Merged

---

## [3.1.5] - 2025-12-29

### Week 4 Release - New Elementor Widgets & Bug Fixes

This release adds two new Elementor widgets (StockPrice and Documents) and includes important bug fixes and refactoring improvements.

---

### ✨ Added

#### New Elementor Widgets

- **Documents Widget** - Display documents from `documents-reports` CPT in responsive grid layout with i18n support for bilingual file downloads (#128)
- **StockPrice Widget** - Display current stock price from cached stock data with configurable styling (#126)

#### Documentation

- **Manual Issue Closure Note** - Added documentation about manual issue closure for non-default branch merges (#125)

### 🐛 Fixed

#### Cron & Scheduling

- **Stock Data Cron** - Register cron schedule before using it in StockData endpoint (#124)

### 🔄 Changed

#### Code Quality

- **StockDataEndpoint Refactor** - Use `soma_get_stock_data()` helper instead of direct transient access (#127)

### 📦 Files Changed

#### Added

- `includes/Elementor/Widgets/Documents.php` - Documents grid widget (485 lines)
- `includes/Elementor/Widgets/StockPrice.php` - Stock price display widget
- `assets/css/widgets/documents.css` - Documents widget styles (148 lines)
- `assets/css/widgets/stock-price.css` - Stock price widget styles
- `tests/Integration/Elementor/DocumentsWidgetTest.php` - 17 integration tests
- `tests/Integration/Elementor/StockPriceWidgetTest.php` - Integration tests

#### Modified

- `includes/Elementor/Loader.php` - Register new widgets
- `includes/Core/Theme.php` - Loader registration order fix
- `includes/API/Endpoints/StockDataEndpoint.php` - Use helper function
- `tests/Integration/Elementor/AllWidgetsTest.php` - Include new widgets
- `docs/WIDGETS.md` - Widget development workflow documentation
- `.github/copilot-instructions.md` - Elementor widget development section

---

### 🔗 Related Issues & PRs

- **Issue #14**: [Sección de Documentos Relevantes](https://github.com/sanruiz/fibra/issues/14) - Closed
- **PR #128**: [feat: Add Documents Elementor widget](https://github.com/sanruiz/fibra/pull/128) - Merged
- **PR #127**: [refactor: Use soma_get_stock_data() helper](https://github.com/sanruiz/fibra/pull/127) - Merged
- **PR #126**: [feat: Add StockPrice Elementor widget](https://github.com/sanruiz/fibra/pull/126) - Merged
- **PR #125**: [docs: Add note about manual issue closure](https://github.com/sanruiz/fibra/pull/125) - Merged
- **PR #124**: [fix: Register cron schedule](https://github.com/sanruiz/fibra/pull/124) - Merged

---

## [3.1.4] - 2025-12-29

### Team Members Post Type Slug Fix

This patch release restores backward compatibility for the team-members post type.

---

### 🐛 Fixed

#### Post Type Slug

- **team-members Post Type** - Restored original hyphenated slug `'team-members'` (was incorrectly changed to `'team_members'` during v3.0.0 refactoring)
- **Backward Compatibility** - Existing Team Members posts created before v3.0.0 are now accessible again
- **Database Consistency** - Post type slug now matches existing database records

### 📦 Files Changed

#### Modified

- `wp-content/themes/soma/includes/Core/Enums/PostType.php` - Changed `TEAM_MEMBERS` enum value from `'team_members'` to `'team-members'`

---

### 🔗 Related Issues & PRs

- **Issue #119**: [fix: team-members post type slug broken after v3.0.0 refactoring](https://github.com/sanruiz/fibra/issues/119) - Closed
- **PR #120**: [fix: Restore team-members post type slug for backward compatibility](https://github.com/sanruiz/fibra/pull/120) - Merged
- **PR #121**: [Week 4: Team Members Post Type Fix](https://github.com/sanruiz/fibra/pull/121) - Merged

---

## [3.1.3] - 2025-12-21

### Elementor Styles Fix & Security Enhancements

This patch release fixes Elementor widget style conflicts and adds important security and workflow improvements.

---

### 🐛 Fixed

#### Elementor Styles

- **Global Styles Override** - Simplified exclusion selectors to prevent global typography from affecting Elementor widgets
- **CSS Specificity** - Changed from complex `:not()` with multiple conditions to simple `:not(.elementor a)` pattern
- **Widget Styling** - Elementor widgets now maintain their specific styles without interference from theme globals

### ✨ Added

#### Security & CI/CD

- **CodeQL Security Analysis** - Automated code scanning for security vulnerabilities
- **Git Hooks** - Pre-commit hooks to enforce branch protection and prevent direct commits to protected branches
- **Branch Protection** - Blocks commits to `main`, `week-*`, and `develop` branches
- **Workflow Restrictions** - Releases and deploys now restricted to `main` branch only

### 🔄 Changed

#### Workflow Improvements

- **Release Process** - Enforced GitFlow: only tags from `main` trigger releases and deployments
- **Branch Strategy** - Documented sprint-based workflow with `week-*` branches
- **Quality Gates** - CI runs on all PRs to `week-*` and `main` branches

### 📦 Files Changed

#### Modified

- `wp-content/themes/soma/sass/_general.scss` - Simplified Elementor exclusion selectors
- `.github/workflows/ci-cd.yml` - Added main branch restriction for releases
- `.github/workflows/codeql.yml` - New security scanning workflow

#### Added

- `install-hooks.sh` - Git hooks installation script
- `.git/hooks/pre-commit` - Branch protection enforcement

---

### 🔗 Related Issues & PRs

- **Issue #79**: [Restrict releases and deploys to main branch only](https://github.com/sanruiz/fibra/issues/79) - Closed (duplicate of #80)
- **Issue #80**: [Restrict releases and deploys to main branch only](https://github.com/sanruiz/fibra/issues/80) - Closed
- **PR #81**: [Restrict releases to main and add Git hooks](https://github.com/sanruiz/fibra/pull/81) - Merged
- **PR #82**: [Add CodeQL security analysis workflow](https://github.com/sanruiz/fibra/pull/82) - Merged
- **PR #83**: [Simplify Elementor exclusion in global styles](https://github.com/sanruiz/fibra/pull/83) - Merged

---

## [3.1.2] - 2025-12-18

### Asset Versioning Fix

This patch release fixes asset versioning to use a single source of truth from `style.css`, eliminating hardcoded version strings and ensuring proper cache busting.

---

### 🐛 Fixed

#### Asset Versioning

- **Outdated Version Numbers** - Assets were loading with version 2.0.7 instead of current version
- **Browser Caching Issues** - New styles not appearing due to old version numbers in query strings
- **Hardcoded Versions** - Eliminated hardcoded `$version` and `$legacy_version` properties
- **Version Mismatch** - Theme had multiple conflicting version definitions

### 🔄 Changed

#### Single Source of Truth Pattern

- **Assets.php** - Now reads version from `style.css` header using `wp_get_theme()->get('Version')` in constructor
- **Theme.php** - `get_version()` method uses `wp_get_theme()->get('Version')` instead of hardcoded constant
- **Removed** - `VERSION` constant from Theme.php
- **Removed** - `$legacy_version` property (was hardcoded to 2.0.7)
- **Simplified** - Future version updates only require changing `style.css` header

### 📦 Files Changed

#### Modified

- `wp-content/themes/soma/includes/Core/Assets.php` - Dynamic version loading
- `wp-content/themes/soma/includes/Core/Theme.php` - Removed hardcoded constant

---

### 🔗 Related Issues

- **Issue #76**: [fix: Asset versioning showing outdated 2.0.7 instead of current 3.1.1](https://github.com/sanruiz/fibra/issues/76) - Closed
- **PR #77**: [fix: Use wp_get_theme()->get('Version') as single source of truth](https://github.com/sanruiz/fibra/pull/77) - Merged to week-2

---

## [3.1.1] - 2025-12-18

### CI/CD Pipeline Unification & Race Condition Fix

This patch release fixes a critical race condition in the CI/CD pipeline that caused deployment failures in v3.1.0. The solution unifies previously separate workflows into a single, sequential pipeline eliminating timing issues.

---

### 🐛 Fixed

#### CI/CD Architecture

- **Race Condition Eliminated** - Unified `ci-cd.yml` replaces separate `quality-and-tests.yml` and `release-and-deploy.yml` workflows
- **Sequential Execution** - Stage 2 (Build & Release) waits for Stage 1 (Quality Gates) completion via `needs:` keyword
- **Deployment Reliability** - Stage 3 (Deploy) executes only after successful release creation
- **Cross-Workflow Timing Issues** - Removed unreliable wait-for-ci job that attempted to coordinate separate workflows
- **3 Failed Deployments** - Resolved v3.1.0 deployment failures (Run IDs: 20291546501, 20291554549, 20291578219)

---

### ✨ Added

#### Workflow Architecture

- **Unified CI/CD Pipeline** - Single `.github/workflows/ci-cd.yml` (546 lines) with 3-stage architecture
- **Stage 1: Quality Gates** (parallel execution, always runs)
  - `code-quality`: PHPCS strict + PHPStan Level 6+ (~27s)
  - `php-tests`: PHPUnit 108 tests with MySQL (~1m9s)
  - `frontend-build`: npm production build (~20s)
- **Stage 2: Build & Release** (conditional on tags, sequential after Stage 1)
  - Creates production ZIP package
  - Generates GitHub release
  - Uploads build artifact
  - Only runs when tag pushed (v*)
- **Stage 3: Deploy to Production** (conditional on Stage 2 success)
  - SFTP upload to fibrasoma.com
  - Automatic theme backup
  - Server-side extraction
- **Stage 4: Pipeline Summary** (always runs, reports all stage results)

#### Documentation

- **Comprehensive CI/CD Guide** - New `docs/workflows/CI_CD.md` (600+ lines)
  - Architecture overview with visual flow diagram
  - Detailed job descriptions for all stages
  - 4 execution flow scenarios
  - Troubleshooting guide with solutions
  - Migration information from old workflows
- **Updated Main Index** - `docs/WORKFLOWS.md` reflects unified architecture
- **Deprecated Old Docs** - `QUALITY_AND_TESTS.md` and `RELEASE_AND_DEPLOY.md` marked deprecated with migration notices

---

### 🔄 Changed

#### Workflow Files

- **Replaced**: `.github/workflows/quality-and-tests.yml` (302 lines, deleted)
- **Replaced**: `.github/workflows/release-and-deploy.yml` (357 lines, deleted)
- **Created**: `.github/workflows/ci-cd.yml` (546 lines, unified pipeline)

#### Execution Flow

- **OLD**: Separate workflows triggered simultaneously on tag push → race condition → wait-for-ci job → deployment failures
- **NEW**: Single workflow with guaranteed sequential stages → no race conditions → reliable deployment

---

### 📦 Files Changed

#### Created

- `.github/workflows/ci-cd.yml` - Unified CI/CD pipeline (546 lines)
- `docs/workflows/CI_CD.md` - Comprehensive workflow documentation (600+ lines)

#### Modified

- `docs/WORKFLOWS.md` - Updated with unified architecture, new quick start, troubleshooting
- `docs/workflows/QUALITY_AND_TESTS.md` - Added deprecation notice and migration guide
- `docs/workflows/RELEASE_AND_DEPLOY.md` - Added deprecation notice and migration guide

#### Deleted

- `.github/workflows/quality-and-tests.yml` - Merged into ci-cd.yml
- `.github/workflows/release-and-deploy.yml` - Merged into ci-cd.yml

---

### 📊 Quality Metrics

- **CI Validation**: PR #73 passed all Stage 1 checks (code-quality 27s, php-tests 1m9s, frontend-build 20s)
- **PHPCS**: 0 errors (WordPress Coding Standards compliant)
- **PHPStan**: Level 6+ compliance (0 critical errors)
- **PHPUnit**: 108 tests passing (355 assertions)
- **Architecture**: Race condition eliminated via unified workflow design

---

### 🔗 Related Issues

- **Issue #72**: [fix: Unify CI/CD workflows to prevent race conditions during releases](https://github.com/sanruiz/fibra/issues/72) - Closed
- **PR #73**: [fix: Unify CI/CD workflows into single pipeline](https://github.com/sanruiz/fibra/pull/73) - Merged to week-2

---

### 🚀 Deployment

This patch release is specifically designed to test and validate the unified CI/CD pipeline. When the v3.1.1 tag is pushed:

1. **Stage 1** executes immediately (quality gates in parallel)
2. **Stage 2** executes after Stage 1 success (builds release package)
3. **Stage 3** executes after Stage 2 success (deploys to production)
4. **Summary** reports complete pipeline results

**Expected outcome**: Successful deployment to fibrasoma.com without race conditions or timing issues.

**Migration**: No changes to theme code. This release only affects the deployment pipeline architecture.

---

## [3.1.0] - 2025-12-16

### Week 2 Release - Elementor Support & CI/CD Enhancements

This release adds full Elementor support to base WordPress templates, enabling visual page building alongside ACF flexible content. Also includes CI/CD workflow improvements and code quality enhancements.

---

### ✨ Added

#### Elementor Integration

- **Full Template Support** - `single.php`, `page.php`, and `index.php` now support Elementor editor
- **Dedicated Elementor Template** - New `elementor-template.php` for pages built entirely with Elementor
- **Conditional Rendering** - Automatically detects Elementor content vs ACF blocks
- **Backward Compatible** - Existing ACF flexible content continues to work seamlessly

#### CI/CD & Automation

- **Quality Checks for PRs** - `quality-and-tests.yml` now runs on PRs to `week-*` branches
- **GitHub Workflow Documentation** - Comprehensive guide in `.github/instructions/github-workflow.instructions.md`
- **Custom Instructions System** - YAML frontmatter support for path-specific coding standards

#### Developer Experience

- **i18n Helper Enhancement** - `soma_get_i18n_field()` for unified language-specific field handling
- **API Language Support** - REST endpoints use helper for consistent multilingual field access
- **WordPress Coding Standards** - Fixed 644 PHPCS errors, improved code quality

---

### 🔄 Changed

#### Template Architecture

- **Base Templates Enhanced** - All main templates check for Elementor before rendering ACF blocks
- **Content Function** - Reverted `the_content()` to dedicated Elementor template instead of modifying core templates
- **Template Hierarchy** - Added Elementor template to WordPress template hierarchy

#### Code Quality

- **PHPUnit Configuration** - Fixed textdomain redeclaration warnings in test suite
- **API Endpoints** - Migrated language conditionals to use `soma_get_i18n_field()` helper
- **Workflow Improvements** - Enhanced CI/CD reliability and test coverage

---

### 🐛 Fixed

#### Testing

- **PHPUnit Warnings** - Resolved textdomain redeclaration issues in test bootstrap
- **WordPress Test Suite** - Properly configured for Local by Flywheel environment

#### Code Standards

- **PHPCS Compliance** - Fixed 644 coding standard violations
- **Type Safety** - Improved type hints and documentation across codebase

---

### 📦 Files Changed

#### Added

- `.github/instructions/github-workflow.instructions.md` - GitHub workflow standards
- `.github/instructions/documentation-language.instructions.md` - Updated with YAML frontmatter
- `elementor-template.php` - Dedicated Elementor page template

#### Modified

- `single.php` - Added Elementor support check
- `page.php` - Added Elementor support check
- `index.php` - Added Elementor support check
- `includes/API/Endpoints/*.php` - Migrated to `soma_get_i18n_field()` helper
- `.github/workflows/quality-and-tests.yml` - Extended to PR triggers on `week-*` branches

---

### 📊 Quality Metrics

- **PHPCS Errors**: 644 → 0 ✅
- **PHPUnit Tests**: 108 passing (355 assertions) ✅
- **PHPStan Level**: 6-8 compliance ✅
- **CI/CD**: Automated quality gates active ✅

---

### 🚀 Deployment

This version enables:

- Visual page building with Elementor widgets (8 custom widgets available)
- Mixed content approach (ACF blocks + Elementor sections on same site)
- Improved developer workflow with automated quality checks
- Production-ready multilingual API endpoints

**Migration**: No breaking changes. Existing pages continue to use ACF flexible content. New pages can choose Elementor template from page attributes.

---

## [3.0.0] - 2025-12-12

### 🚀 Major Release - Complete Theme Modernization

SOMA v3.0.0 is a **complete rewrite** bringing modern PHP standards, enterprise-grade development practices, and powerful new features while preserving the ACF flexible content system.

**Migration Required**: See [MIGRATION_FROM_V2.md](docs/MIGRATION_FROM_V2.md) for upgrade guide.

---

### ✨ Added

#### Internationalization (i18n) System

- **WordPress i18n Standard** - Full compliance with WordPress internationalization best practices
- **Translation Helper Function** - `soma_get_i18n_field()` for ACF field internationalization with language variants
- **Translation Files** - Complete Spanish (es_ES) translation with .pot template, .po source, and .mo compiled files
- **17 UI Strings** - All user-facing strings use WordPress i18n functions (__(), _e(), esc_html__(), etc.)
- **ACF Field Pattern** - Unified helper function for conditional field loading (file/file_es, events/events_es)
- **i18n Documentation** - Complete internationalization guide in `docs/INTERNATIONALIZATION.md`

#### Architecture & Infrastructure

- **PSR-4 Autoloading** - Complete namespace structure with `Soma\` base namespace
- **Composer Integration** - Modern dependency management with autoloader
- **LoadableInterface System** - Standardized component loading with priorities (10-50)
- **Singleton Pattern** - Consistent instantiation across all major components
- **PHP 8.1+ Features** - Enums, match expressions, first-class callables, readonly properties

#### Core Components

- **3 Custom Taxonomies** - Portfolio, News, Team Members taxonomies with enum configuration
- **Taxonomy Enum** (`Soma\Core\Enums\Taxonomy`) - Type-safe taxonomy references with 5 helper methods
- **PostType Enum** (`Soma\Core\Enums\PostType`) - Type-safe post type identifiers
- **LogLevel Enum** (`Soma\Utils\Enums\LogLevel`) - 8 PSR-3 log levels with severity system
- **CacheTag Enum** (`Soma\Utils\Enums\CacheTag`) - Type-safe cache tag identifiers

#### Helper Functions System (25 functions)

- **Logger Helpers (9)** - `soma_log_emergency()`, `soma_log_alert()`, `soma_log_critical()`, `soma_log_error()`, `soma_log_warning()`, `soma_log_notice()`, `soma_log_info()`, `soma_log_debug()`, `soma_get_logger()`
- **Cache Helpers (6)** - `soma_cache_get()`, `soma_cache_set()`, `soma_cache_remember()`, `soma_cache_invalidate_tags()`, `soma_cache_flush()`, `soma_get_cache()`
- **Post Type Helpers (4)** - `soma_get_portfolio_items()`, `soma_get_news_items()`, `soma_get_careers_items()`, `soma_get_team_members()`
- **Template Helpers (2)** - `soma_get_template_part()`, `soma_load_partial()`
- **ACF Helpers (2)** - `soma_get_flexible_content()`, `soma_render_flexible_content()`
- **Utility Helpers (4)** - `soma_is_dev()`, `soma_get_version()`, `soma_sanitize_class()`, `soma_asset_url()`
- **Translation Helpers (3)** - `soma_translate_date()`, `soma_get_i18n_field()`, `translateDate()` (deprecated alias)
- **Stock Data (1)** - `soma_get_stock_data()`

#### Caching System

- **PSR-16 Cache Implementation** - Simple cache interface with WordPress object cache backend
- **Tag-Based Invalidation** - Group cache entries by tags for bulk invalidation
- **Automatic Cache Invalidation** - Auto-invalidates on `save_post` and ACF save hooks
- **Cache Helper Functions** - Simplified API with `soma_cache_*` functions
- **Remember Pattern** - `soma_cache_remember()` for elegant cache-or-compute logic
- **CacheInvalidationManager** - Centralized invalidation with tag tracking

#### Logging System

- **PSR-3 Logger** - Full PSR-3 compliance with 8 severity levels
- **File-Based Logging** - Logs to `wp-content/uploads/soma-logs/soma.log`
- **Contextual Logging** - Rich context support for debugging
- **Test Mode Suppression** - Automatic error_log suppression during PHPUnit tests
- **Helper Functions** - Simple `soma_log_*()` functions for all log levels

#### Elementor Integration

- **8 Custom Widgets** - Navbar, Footer, Business Units, Services, Team Members, News List, Portfolio, Contact Form
- **Custom Widget Category** - 'soma' category in Elementor panel
- **ACF Data Integration** - Widgets can access ACF fields seamlessly
- **CSS Variables Support** - All widgets use centralized design tokens
- **Typography Controls** - Elementor native typography system
- **Responsive Controls** - Built-in responsive settings for all widgets
- **Icon Controls** - Icon library integration for visual elements

#### PageBuilder Enhancements

- **BlockRegistry** - Centralized mapping of 53 blocks (layout → field_group → partial)
- **BlockRenderer** - Advanced rendering engine with validation, error handling, and optional caching
- **Multi-Layer Validation** - Structure validation, registry validation, file existence checks
- **PSR-3 Error Logging** - Detailed error tracking with context
- **WordPress Query Vars** - Modern data access (replaced global variables)
- **Cache Support** - Optional block-level caching with tag-based invalidation
- **LoadableInterface** - Priority-based loading (priority 25)

#### Testing Infrastructure

- **PHPUnit Integration** - Comprehensive unit and integration tests
- **108 Tests** - 355 assertions across 24 test files
- **Test Organization** - Separate unit and integration test suites
- **WP-CLI Test Runner** - Bash script for running tests via WP-CLI
- **Admin Test UI** - Visual test page in WordPress admin (23 test scenarios)
- **SimpleMocks** - Lightweight mocking system for WordPress functions
- **SOMA_TESTING Constant** - Clean test output without error_log noise

#### Code Quality Tools

- **PHPCS Integration** - WordPress Coding Standards compliance
- **PHPStan Static Analysis** - Level 6-8 static type checking
- **PHPCBF Auto-Fixing** - Automatic code formatting
- **Composer Scripts** - `composer phpcs`, `composer phpstan`, `composer validate`
- **Git Pre-Commit Hooks** - Automated validation before commits
- **Baseline Support** - `phpstan-baseline.neon` for acceptable warnings

#### Documentation (5,000+ lines)

- **DEVELOPMENT.md** (1,093 lines) - Complete developer guide with 30+ code examples
- **WIDGETS.md** (900 lines) - Elementor widgets reference with control tables
- **HELPERS.md** (850+ lines) - API reference for 24 helper functions
- **MIGRATION_FROM_V2.md** (1,549 lines) - Upgrade guide with step-by-step instructions
- **MIGRATION_PLAN.md** (1,000+ lines) - Complete modernization plan (9 phases)
- **ARCHITECTURE_VISION.md** (800+ lines) - Target architecture and design principles
- **TESTING_GUIDE.md** (337 lines) - Testing documentation with examples
- **README.md** (600+ lines) - Comprehensive project overview
- **Phase Completion Docs** (2,000+ lines) - Detailed reports for each migration phase

---

### 🔄 Changed

#### Breaking Changes

##### PageBuilder Global Variables → Query Vars (CRITICAL)

```php
// ❌ v2.0.7 (OLD - NO LONGER WORKS)
global $pageBlock;
$title = $pageBlock['title'];

// ✅ v3.0.0 (NEW - REQUIRED)
$block_content = get_query_var('soma_block_content');
$title = $block_content['title'] ?? '';
```

**Impact**: All custom partials using `$pageBlock` must be updated  
**Files Affected**: `partials/*.php`  
**Migration**: See [MIGRATION_FROM_V2.md § Code Updates](docs/MIGRATION_FROM_V2.md#code-updates-required)

##### Directory Structure Reorganization

```
❌ OLD (v2.0.7):          ✅ NEW (v3.0.0):
inc/                      includes/
├── post-types.php       ├── Core/
├── endpoints.php        ├── PostTypes/
├── cf7-validations.php  ├── Taxonomies/
└── theme-config.php     ├── API/
                         ├── Elementor/
                         ├── PageBuilder/
                         ├── CF7/
                         ├── Utils/
                         └── Admin/
```

**Impact**: Direct file includes will fail  
**Migration**: Use namespaced classes instead of `require_once`

##### Class Structure - Functions → Singletons

```php
// ❌ OLD (v2.0.7)
register_portfolio_post_type();

// ✅ NEW (v3.0.0)
use Soma\PostTypes\Types\Portfolio;
Portfolio::instance();

// Or use helper:
$items = soma_get_portfolio_items();
```

**Impact**: Old function calls will fail  
**Migration**: Use singleton classes or helper functions

##### PHP Version Requirement

- **OLD**: PHP 7.4+ supported
- **NEW**: PHP 8.1+ required

**Reason**: Enums, first-class callables, match expressions, readonly properties

##### Hook Registration - Array Syntax → First-Class Callables

```php
// ❌ OLD (v2.0.7)
add_action('init', array($this, 'init'));

// ✅ NEW (v3.0.0)
add_action('init', $this->init(...));
```

**Impact**: Internal theme code updated (no external impact)

#### Non-Breaking Changes

##### Post Types Migration

- All 4 post types migrated to PSR-4 structure (`Soma\PostTypes\Types\*`)
- Singleton pattern with `instance()` method
- LoadableInterface implementation (priority 20)
- First-class callables for hook registration
- Enhanced with helper functions

##### Custom Fields Migration

- ACF field groups preserved 100% (no changes to field structure)
- Field registration migrated to PSR-4 classes (`Soma\CustomFields\Fields\*`)
- Singleton pattern implementation
- ACF dependency checks added
- JSON sync functionality maintained

##### REST API Migration

- All 5 endpoints migrated to PSR-4 structure (`Soma\API\Endpoints\*`)
- Singleton pattern with clean initialization
- First-class callables for route registration
- Improved error handling and validation
- Same endpoint URLs maintained (no breaking changes)

##### CF7 Integration Migration

- Validation classes migrated to PSR-4 (`Soma\CF7\Validations`)
- Singleton pattern implementation
- Enhanced error messages
- Maintained backward compatibility with existing forms

##### Logger Enhancement

- Test mode suppression (checks `SOMA_TESTING` constant)
- Performance optimization (single instance, minimal overhead)
- Log rotation support (future-ready)

##### Cache System Enhancement

- Performance optimizations
- Better error handling
- Tag validation
- Metrics tracking (cache hits/misses)

---

### 🐛 Fixed

#### Code Quality Fixes

- **PHPCS Errors**: Reduced from 624 to 154 errors (470 auto-fixed with PHPCBF)
- **PHPStan Issues**: Achieved Level 6 compliance with 0 critical errors
- **Baseline Created**: `phpstan-baseline.neon` for 3 acceptable warnings
- **41 Files Formatted**: Consistent coding standards across codebase

#### Test Error Fixes

- **Logger Error Messages**: Suppressed `error_log()` during tests (added `SOMA_TESTING` check)
- **Test Output Cleanup**: Clean PHPUnit runs with 0 console errors
- **108/108 Tests Passing**: All tests green (355 assertions)

#### Documentation Fixes

- **Enum Documentation**: Updated Phase 2.5 docs with enum improvements
- **Test Coverage**: Corrected test counts (36 → 39 tests)
- **File Counts**: Updated to reflect actual implementation (8 → 9 files)

---

### 🗑️ Deprecated

#### Functions (Backward Compatible)

- `translateDate()` - Use `soma_translate_date()` instead (alias maintained for compatibility)

#### Global Variables (Breaking)

- `$pageBlock` - Use `get_query_var('soma_block_content')` instead
- `$pageBuilder` - Use `get_query_var('soma_blocks')` instead

#### File Includes (Breaking)

- `require_once get_template_directory() . '/inc/post-types.php'` - Use Composer autoload
- `require_once get_template_directory() . '/inc/endpoints.php'` - Use Composer autoload
- `require_once get_template_directory() . '/inc/cf7-validations.php'` - Use Composer autoload

---

### 🔒 Security

#### Input Validation

- All user input sanitized through WordPress functions
- ACF handles field sanitization automatically
- REST API parameter validation with type checking
- Nonce verification for all form submissions

#### Output Escaping

- All dynamic output escaped with context-aware functions
- XSS prevention in templates and partials
- SQL injection prevention (prepared statements only)
- File upload validation

#### Authentication & Authorization

- Proper capability checks for admin functions
- REST API permission callbacks implemented
- Admin area restrictions enforced
- LoadableInterface conditional loading support

---

### 📊 Performance

#### Improvements

- **Caching System**: Tag-based caching reduces database queries
- **Autoloading**: Composer autoload faster than manual includes
- **Helper Functions**: Optimized query patterns with `soma_get_*_items()`
- **Asset Optimization**: Minified CSS/JS with versioning

#### Benchmarks (Estimated)

- **Page Load Time**: < 2.5s average (homepage)
- **Database Queries**: < 40 average per page
- **Cache Hit Rate**: > 90% for repeated requests
- **Core Web Vitals**: All "Good" targets

---

### 🧪 Testing

#### Test Coverage

- **Total Tests**: 108 tests, 355 assertions
- **Unit Tests**: 75 tests
  - PostTypes: 24 tests (Portfolio, News, Careers, TeamMembers)
  - Taxonomies: 24 tests (Portfolio, News, TeamMembers)
  - CustomFields: 12 tests
  - Elementor: 8 tests
  - Utils: 7 tests
- **Integration Tests**: 33 tests
  - PostTypes Integration: 6 tests
  - Taxonomies Integration: 15 tests
  - PageBuilder Integration: 12 tests

#### Quality Metrics

- **PHPCS**: WordPress Coding Standards compliant (0 errors)
- **PHPStan**: Level 6 compliance (0 critical errors)
- **Code Coverage**: Unit test coverage for all critical components
- **Test Execution**: < 5 seconds for full suite

---

### 📦 Dependencies

#### Added

- `composer/installers` ^2.0 - WordPress plugin/theme installer
- `phpunit/phpunit` ^9.0 (dev) - Testing framework
- `squizlabs/php_codesniffer` ^3.7 (dev) - Coding standards
- `wp-coding-standards/wpcs` ^3.0 (dev) - WordPress standards
- `phpstan/phpstan` ^1.10 (dev) - Static analysis
- `szepeviktor/phpstan-wordpress` ^1.3 (dev) - WordPress PHPStan rules

#### Updated

- Node.js packages updated for security
- Webpack configuration modernized

---

### 🏗️ Development

#### New Scripts

```bash
composer test         # Run all PHPUnit tests
composer phpcs        # Check coding standards
composer phpcbf       # Auto-fix coding standards
composer phpstan      # Run static analysis
composer validate     # Run all quality checks
```

#### New Tools

- `scripts/validate-theme.sh` - Complete validation pipeline
- `tests/bin/install-wp-tests.sh` - WordPress test environment setup
- Git pre-commit hooks for quality validation

---

### 📁 File Changes Summary

#### Phase 1: Foundation & Infrastructure

- **Added**: `composer.json`, `phpstan.neon`, `phpcs.xml`, `phpunit.xml`
- **Added**: `includes/Core/Loader.php`, `includes/Core/Theme.php`
- **Added**: `includes/Core/Interfaces/LoadableInterface.php`
- **Added**: `tests/` directory structure (bootstrap, Unit, Integration, Mocks)

#### Phase 2.1-2.4: Module Migration

- **Added**: 4 files in `includes/PostTypes/Types/` (Portfolio, News, Careers, TeamMembers)
- **Added**: 1 file `includes/PostTypes/Loader.php`
- **Added**: 4 files in `includes/CustomFields/Fields/`
- **Added**: 1 file `includes/CustomFields/Loader.php`
- **Added**: 5 files in `includes/API/Endpoints/`
- **Added**: 1 file `includes/API/Loader.php`
- **Added**: 1 file `includes/CF7/Validations.php`
- **Added**: 1 file `includes/CF7/Loader.php`
- **Removed**: `inc/post-types.php`, `inc/endpoints.php`, `inc/cf7-validations.php` (migrated)

#### Phase 2.5: Taxonomies Migration

- **Added**: `includes/Core/Enums/Taxonomy.php` (119 lines)
- **Added**: 3 files in `includes/Taxonomies/` (PortfolioTaxonomy, NewsTaxonomy, TeamMembersTaxonomy)
- **Added**: `includes/Taxonomies/Loader.php`
- **Added**: 3 test files in `tests/Unit/Taxonomies/`
- **Added**: `tests/Integration/TaxonomiesTest.php` (280 lines)
- **Added**: `docs/PHASE_2.5_COMPLETION.md` (526 lines)
- **Removed**: `inc/taxonomies.php.deprecated`

#### Phase 3: Utilities & Helpers

- **Added**: `includes/Utils/Helpers.php` (458 lines, 24 functions)
- **Added**: `includes/Utils/Logger.php` (PSR-3 implementation)
- **Added**: `includes/Utils/Cache.php` (tag-based caching)
- **Added**: `includes/Utils/CacheInvalidationManager.php`
- **Added**: `includes/Utils/Enums/LogLevel.php` (8 PSR-3 levels)
- **Added**: `includes/Utils/Enums/CacheTag.php`
- **Added**: `includes/Core/Enums/PostType.php`

#### Phase 4: Elementor Integration

- **Added**: 8 files in `includes/Elementor/Widgets/` (Navbar, Footer, BusinessUnits, Services, TeamMembers, NewsList, Portfolio, ContactForm)
- **Added**: `includes/Elementor/Loader.php`
- **Added**: 8 CSS files in `assets/css/widgets/`
- **Added**: Integration tests for all widgets

#### Phase 6: PageBuilder Enhancement

- **Added**: `includes/PageBuilder/Loader.php` (235 lines)
- **Added**: `includes/PageBuilder/BlockRegistry.php` (236 lines, 53 blocks)
- **Added**: `includes/PageBuilder/BlockRenderer.php` (334 lines)
- **Modified**: `page-builder.php` (110+ lines → 34 lines)
- **Added**: `docs/PHASE_6_COMPLETION.md` (1,100+ lines)
- **Added**: `docs/TESTING_GUIDE.md` (337 lines)

#### Phase 8: Documentation & Release

- **Added**: `docs/DEVELOPMENT.md` (1,093 lines)
- **Added**: `docs/WIDGETS.md` (900 lines)
- **Added**: `docs/HELPERS.md` (850+ lines)
- **Added**: `docs/MIGRATION_FROM_V2.md` (1,549 lines)
- **Updated**: `README.md` (100 → 600+ lines)
- **Added**: `CHANGELOG.md` (this file)

#### Total Changes

- **Files Added**: 70+ files
- **Files Modified**: 15+ files
- **Files Removed**: 5+ deprecated files
- **Lines Added**: 15,000+ lines
- **Lines Removed**: 500+ lines (consolidation)

---

### 🔗 Links

- **Documentation**: [docs/](docs/)
- **Migration Guide**: [docs/MIGRATION_FROM_V2.md](docs/MIGRATION_FROM_V2.md)
- **Development Guide**: [docs/DEVELOPMENT.md](docs/DEVELOPMENT.md)
- **Widgets Reference**: [docs/WIDGETS.md](docs/WIDGETS.md)
- **Helper Functions**: [docs/HELPERS.md](docs/HELPERS.md)
- **Testing Guide**: [docs/TESTING_GUIDE.md](docs/TESTING_GUIDE.md)
- **Internationalization**: [docs/INTERNATIONALIZATION.md](docs/INTERNATIONALIZATION.md)

---

### 👥 Contributors

- **Architecture & Development**: Miguel Colmenares
- **Original Theme**: [PIPE:CODE](https://pipe-code.github.io/)
- **Testing & QA**: Miguel Colmenares
- **Documentation**: Miguel Colmenares

---

### 🎯 Migration Notes

**Upgrading from v2.x?** Follow these steps:

1. **Backup everything** (database + files)
2. **Check PHP version** (must be 8.1+)
3. **Read migration guide**: [docs/MIGRATION_FROM_V2.md](docs/MIGRATION_FROM_V2.md)
4. **Test on staging** before production
5. **Update custom partials** (global vars → query vars)
6. **Install dependencies** (Composer + npm)
7. **Clear all caches**
8. **Run tests** to verify

**Estimated migration time**: 2-4 hours (including testing)

---

## [2.0.7] - 2025-11-30

### Previous Version (Pre-Modernization)

Last stable release before v3.0.0 modernization. This version used the traditional WordPress theme structure without PSR-4, Composer, or modern PHP features.

#### Features

- ACF Flexible Content page builder (50+ partials)
- 4 Custom Post Types (Portfolio, News, Careers, Team Members)
- 5 REST API endpoints
- Contact Form 7 integration
- WP Multilang support
- Webpack asset compilation
- Basic SCSS architecture

#### Known Issues (Fixed in v3.0)

- No PSR-4 compliance
- No automated testing
- No code quality tools
- No centralized helper functions
- No caching system
- No logging system
- Global variable usage in partials
- No Elementor integration

---

## Version History

- **[3.0.0]** - 2025-12-12 - Complete modernization (PSR-4, PHP 8.1+, Elementor, Testing)
- **[2.0.7]** - 2025-11-30 - Pre-modernization stable release
- **[1.0.0]** - 2020-XX-XX - Initial release

---

## Semantic Versioning

This project follows [Semantic Versioning](https://semver.org/):

- **MAJOR** (3.x.x) - Breaking changes requiring migration
- **MINOR** (x.1.x) - New features, backward compatible
- **PATCH** (x.x.1) - Bug fixes, backward compatible

---

**SOMA Theme** - © 2020-2025 All Rights Reserved  
**Developed by**: Miguel Colmenares  
**Original Theme**: [PIPE:CODE](https://pipe-code.github.io/)
