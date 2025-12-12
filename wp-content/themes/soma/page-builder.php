<?php
/**
 * Page Builder Template
 *
 * This file renders ACF flexible content blocks using the PSR-4 PageBuilder system.
 * Maintains backward compatibility with legacy $pageBuilder and $pageBlock globals.
 *
 * @package    Soma
 * @subpackage Templates
 * @since      2.0.7
 * @version    3.0.0
 *
 * @global array $pageBuilder ACF flexible content array from get_field('soma_blocks')
 * @global array $pageBlock   Current block data (set by BlockRenderer for each block)
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}
global $pageBuilder;
global $pageBlock;

// Use new PSR-4 PageBuilder system if available
if ( class_exists( '\Soma\PageBuilder\BlockRenderer' ) ) {
	$renderer = \Soma\PageBuilder\BlockRenderer::instance();
	$renderer->render( $pageBuilder );
} else {
	// Fallback to legacy implementation if PSR-4 system not loaded
	// This should never happen in production, but provides safety during migration
	
	$blockList = [
		// "partial_name"       => "field_group"
		"fullscreenSlider"      => "fullscreen_slider_content",
		"randomInfo"            => "random_info_content",
		"Bigtext_Image"         => "bigtext_image_content",
		"BusinessUnits"         => "business_units_content",
		"BusinessUnits2"        => "business_units_2_content",
		"Logo_TwoColumnsText"   => "logo_twocolumnstext_content",
		"Phrase"                => "phrase_content",
		"TwoColumnsText"        => "two_columns_text_content",
		"NewsList"              => "news_list_content",
		"CareersList"              => "careers_list_content",
		"HeaderText"            => "header_text_content",
		"VimeoPlayer"           => "vimeo_player_content",
		"Logo_Image"            => "logo_image_content",
		"TwoImages"             => "two_images_content",
		"TeamMembers"           => "team_members_content",
		"Timeline"              => "timeline_content",
		"Image_Text"            => "image_text_content",
		"LogoGrid"              => "logo_grid_content",
		"Brand"                 => "brand_content",
		"Portfolio"             => "portfolio_content",
		"ProjectInfo"           => "project_info_content",
		"Image"                 => "image_content",
		"Text"                  => "text_content",
		"Art"                   => "art_content",
		"Initiatives"           => "initiatives_content",
		"ContactInfo"           => "contact_info_content",
		"BusinessUnitsContact"  => "business_units_contact_content",
		"randomInfoFullscreen"  => "random_info_fullscreen_content",
		"FeaturedText"          => "featured_text_content",
		"FibrasomaHome1"        => "fibrasoma_home_1_content",
		"FibrasomaHome2"        => "fibrasoma_home_2_content",
		"FibrasomaHome3"        => "fibrasoma_home_3_content",
		"FibrasomaHome4"        => "fibrasoma_home_4_content",
		"Documents"             => "documents_list_content",
		"FibrasomaHeader"       => "fibrasoma_header_content",
		"ShareQuotation"        => "share_quotation_content",
		"TextSlider"            => "text_slider_content",
		"Contact"               => "contact_content",
		"TeamMembersFibrasoma"  => "team_members_fibrasoma_content",
		"Redirect"              => "redirect_content",
		"Report"                => "report_content",
		"AnnualReports"         => "annual_reports_content",
		"ProjectContactInfo"    => "project_contact_info_content",
		"CustomKeywords"        => "custom_keywords_content",
		"ContactHeader"         => "contact_header_content",
		"Events"                => "events_content",
		"FibrasomaHomeEvents"   => "fibrasoma_home_events_content",
		"AnalystCoverage"       => "analyst_coverage_content"
	];

	if ( $pageBuilder ):
		foreach ( $pageBuilder as $key => $block ):
			
			if ( array_key_exists( $block['acf_fc_layout'], $blockList ) ):
				$pageBlock = [
					'block_counter' => $key,
					'block_content' => $block[ $blockList[ $block['acf_fc_layout'] ] ]
				];
				get_template_part( 'partials/' . $block['acf_fc_layout'] );
			else:
				echo esc_html( $block['acf_fc_layout'] ) . ' block not found.';
			endif;
			
		endforeach; 
	endif;
}