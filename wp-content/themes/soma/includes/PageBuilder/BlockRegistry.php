<?php
/**
 * Block Registry
 *
 * Centralized registry for ACF flexible content blocks and their corresponding partials.
 * Maps ACF layout names to field groups and partial file paths.
 *
 * @package    Soma
 * @subpackage PageBuilder
 * @since      3.0.0
 */

namespace Soma\PageBuilder;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * BlockRegistry class
 *
 * Maintains the mapping between ACF flexible content layouts and theme partials.
 *
 * @since 3.0.0
 */
class BlockRegistry {
	/**
	 * Singleton instance
	 *
	 * @var BlockRegistry|null
	 */
	private static ?BlockRegistry $instance = null;

	/**
	 * Block mappings
	 *
	 * Format: 'layout_name' => ['field_group' => 'acf_field', 'partial' => 'path/to/partial']
	 *
	 * @var array<string, array{field_group: string, partial: string}>
	 */
	private array $blocks = [];

	/**
	 * Get singleton instance
	 *
	 * @return BlockRegistry
	 */
	public static function instance(): BlockRegistry {
		if ( self::$instance === null ) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	/**
	 * Private constructor
	 */
	private function __construct() {
		$this->register_default_blocks();
	}

	/**
	 * Prevent cloning
	 */
	private function __clone() {}

	/**
	 * Prevent unserialization
	 *
	 * @throws \Exception Always throws exception.
	 */
	public function __wakeup() {
		throw new \Exception( 'Cannot unserialize singleton' );
	}

	/**
	 * Register all default theme blocks
	 *
	 * Maps ACF flexible content layouts to their field groups and partial files.
	 * Maintains backward compatibility with existing page-builder.php structure.
	 *
	 * @return void
	 */
	private function register_default_blocks(): void {
		// Sliders & Fullscreen
		$this->register_block( 'fullscreenSlider', 'fullscreen_slider_content', 'fullscreenSlider' );
		$this->register_block( 'randomInfo', 'random_info_content', 'randomInfo' );
		$this->register_block( 'randomInfoFullscreen', 'random_info_fullscreen_content', 'randomInfoFullscreen' );

		// Business Units
		$this->register_block( 'BusinessUnits', 'business_units_content', 'BusinessUnits' );
		$this->register_block( 'BusinessUnits2', 'business_units_2_content', 'BusinessUnits2' );
		$this->register_block( 'BusinessUnitsContact', 'business_units_contact_content', 'BusinessUnitsContact' );

		// Text & Content Blocks
		$this->register_block( 'Bigtext_Image', 'bigtext_image_content', 'Bigtext_Image' );
		$this->register_block( 'Logo_TwoColumnsText', 'logo_twocolumnstext_content', 'Logo_TwoColumnsText' );
		$this->register_block( 'Phrase', 'phrase_content', 'Phrase' );
		$this->register_block( 'TwoColumnsText', 'two_columns_text_content', 'TwoColumnsText' );
		$this->register_block( 'HeaderText', 'header_text_content', 'HeaderText' );
		$this->register_block( 'Text', 'text_content', 'Text' );
		$this->register_block( 'FeaturedText', 'featured_text_content', 'FeaturedText' );
		$this->register_block( 'TextSlider', 'text_slider_content', 'TextSlider' );

		// Lists & Grids
		$this->register_block( 'NewsList', 'news_list_content', 'NewsList' );
		$this->register_block( 'CareersList', 'careers_list_content', 'CareersList' );
		$this->register_block( 'TeamMembers', 'team_members_content', 'TeamMembers' );
		$this->register_block( 'TeamMembersFibrasoma', 'team_members_fibrasoma_content', 'TeamMembersFibrasoma' );
		$this->register_block( 'LogoGrid', 'logo_grid_content', 'LogoGrid' );

		// Media
		$this->register_block( 'VimeoPlayer', 'vimeo_player_content', 'VimeoPlayer' );
		$this->register_block( 'Logo_Image', 'logo_image_content', 'Logo_Image' );
		$this->register_block( 'TwoImages', 'two_images_content', 'TwoImages' );
		$this->register_block( 'Image_Text', 'image_text_content', 'Image_Text' );
		$this->register_block( 'Image', 'image_content', 'Image' );

		// Special Features
		$this->register_block( 'Timeline', 'timeline_content', 'Timeline' );
		$this->register_block( 'Brand', 'brand_content', 'Brand' );
		$this->register_block( 'Portfolio', 'portfolio_content', 'Portfolio' );
		$this->register_block( 'ProjectInfo', 'project_info_content', 'ProjectInfo' );
		$this->register_block( 'ProjectContactInfo', 'project_contact_info_content', 'ProjectContactInfo' );
		$this->register_block( 'Art', 'art_content', 'Art' );
		$this->register_block( 'Initiatives', 'initiatives_content', 'Initiatives' );

		// Contact & Info
		$this->register_block( 'ContactInfo', 'contact_info_content', 'ContactInfo' );
		$this->register_block( 'Contact', 'contact_content', 'Contact' );
		$this->register_block( 'ContactHeader', 'contact_header_content', 'ContactHeader' );

		// Fibrasoma Specific
		$this->register_block( 'FibrasomaHome1', 'fibrasoma_home_1_content', 'FibrasomaHome1' );
		$this->register_block( 'FibrasomaHome2', 'fibrasoma_home_2_content', 'FibrasomaHome2' );
		$this->register_block( 'FibrasomaHome3', 'fibrasoma_home_3_content', 'FibrasomaHome3' );
		$this->register_block( 'FibrasomaHome4', 'fibrasoma_home_4_content', 'FibrasomaHome4' );
		$this->register_block( 'FibrasomaHeader', 'fibrasoma_header_content', 'FibrasomaHeader' );
		$this->register_block( 'FibrasomaHomeEvents', 'fibrasoma_home_events_content', 'FibrasomaHomeEvents' );

		// Documents & Reports
		$this->register_block( 'Documents', 'documents_list_content', 'Documents' );
		$this->register_block( 'Report', 'report_content', 'Report' );
		$this->register_block( 'AnnualReports', 'annual_reports_content', 'AnnualReports' );
		$this->register_block( 'AnalystCoverage', 'analyst_coverage_content', 'AnalystCoverage' );

		// Financial
		$this->register_block( 'ShareQuotation', 'share_quotation_content', 'ShareQuotation' );

		// Events
		$this->register_block( 'Events', 'events_content', 'Events' );

		// Utilities
		$this->register_block( 'Redirect', 'redirect_content', 'Redirect' );
		$this->register_block( 'CustomKeywords', 'custom_keywords_content', 'CustomKeywords' );
	}

	/**
	 * Register a block mapping
	 *
	 * @param string $layout      ACF layout name (e.g., 'BusinessUnits').
	 * @param string $field_group ACF field group name (e.g., 'business_units_content').
	 * @param string $partial     Partial file name without extension (e.g., 'BusinessUnits').
	 * @return void
	 */
	public function register_block( string $layout, string $field_group, string $partial ): void {
		$this->blocks[ $layout ] = [
			'field_group' => $field_group,
			'partial'     => $partial,
		];
	}

	/**
	 * Check if a block layout is registered
	 *
	 * @param string $layout ACF layout name.
	 * @return bool True if registered, false otherwise.
	 */
	public function is_registered( string $layout ): bool {
		return isset( $this->blocks[ $layout ] );
	}

	/**
	 * Get block field group name
	 *
	 * @param string $layout ACF layout name.
	 * @return string|null Field group name or null if not registered.
	 */
	public function get_field_group( string $layout ): ?string {
		return $this->blocks[ $layout ]['field_group'] ?? null;
	}

	/**
	 * Get block partial path
	 *
	 * @param string $layout ACF layout name.
	 * @return string|null Partial path or null if not registered.
	 */
	public function get_partial_path( string $layout ): ?string {
		return $this->blocks[ $layout ]['partial'] ?? null;
	}

	/**
	 * Get full partial file path
	 *
	 * @param string $layout ACF layout name.
	 * @return string|null Full path to partial file or null if not registered.
	 */
	public function get_partial_file_path( string $layout ): ?string {
		$partial = $this->get_partial_path( $layout );
		if ( $partial === null ) {
			return null;
		}

		return get_template_directory() . '/partials/' . $partial . '.php';
	}

	/**
	 * Get all registered blocks
	 *
	 * @return array<string, array{field_group: string, partial: string}>
	 */
	public function get_all_blocks(): array {
		return $this->blocks;
	}

	/**
	 * Get count of registered blocks
	 *
	 * @return int Number of registered blocks.
	 */
	public function count(): int {
		return count( $this->blocks );
	}
}
