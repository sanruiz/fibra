<?php
/**
 * Documents Elementor Widget
 *
 * Displays recent documents/reports in a grid layout with featured image,
 * title, and download link with i18n support.
 *
 * @package Soma
 * @subpackage Elementor\Widgets
 * @since 3.1.5
 */

namespace Soma\Elementor\Widgets;

use Elementor\Controls_Manager;
use Elementor\Core\Kits\Documents\Tabs\Global_Colors;
use Elementor\Core\Kits\Documents\Tabs\Global_Typography;
use Elementor\Group_Control_Typography;
use Soma\Elementor\Base\WidgetBase;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Documents widget class
 *
 * Queries documents-reports post type and displays them in a grid
 * with featured image, title, and download link.
 */
class Documents extends WidgetBase {

	/**
	 * Get widget name
	 *
	 * @return string
	 */
	public function get_name(): string {
		return 'soma-documents';
	}

	/**
	 * Get widget title
	 *
	 * @return string
	 */
	public function get_title(): string {
		return __( 'Documents', 'soma' );
	}

	/**
	 * Get widget icon
	 *
	 * @return string
	 */
	public function get_icon(): string {
		return 'eicon-document-file';
	}

	/**
	 * Get style dependencies
	 *
	 * @return array<int, string>
	 */
	public function get_style_depends(): array {
		return array( 'soma-documents' );
	}

	/**
	 * Register widget controls
	 *
	 * @return void
	 */
	protected function register_controls(): void {
		$this->register_content_controls();
		$this->register_style_controls();
	}

	/**
	 * Register content controls
	 *
	 * @return void
	 */
	private function register_content_controls(): void {
		// Query section.
		$this->start_controls_section(
			'section_query',
			array(
				'label' => __( 'Query', 'soma' ),
			)
		);

		$this->add_control(
			'posts_per_page',
			array(
				'label'   => __( 'Number of Documents', 'soma' ),
				'type'    => Controls_Manager::NUMBER,
				'default' => 4,
				'min'     => 1,
				'max'     => 12,
			)
		);

		$this->add_control(
			'orderby',
			array(
				'label'   => __( 'Order By', 'soma' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'date',
				'options' => array(
					'date'       => __( 'Date', 'soma' ),
					'title'      => __( 'Title', 'soma' ),
					'menu_order' => __( 'Menu Order', 'soma' ),
					'modified'   => __( 'Modified Date', 'soma' ),
				),
			)
		);

		$this->add_control(
			'order',
			array(
				'label'   => __( 'Order', 'soma' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'DESC',
				'options' => array(
					'DESC' => __( 'Descending', 'soma' ),
					'ASC'  => __( 'Ascending', 'soma' ),
				),
			)
		);

		$this->end_controls_section();

		// Content section.
		$this->start_controls_section(
			'section_content',
			array(
				'label' => __( 'Content', 'soma' ),
			)
		);

		$this->add_control(
			'title_tag',
			array(
				'label'   => __( 'Title HTML Tag', 'soma' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'h3',
				'options' => array(
					'h1'   => 'H1',
					'h2'   => 'H2',
					'h3'   => 'H3',
					'h4'   => 'H4',
					'h5'   => 'H5',
					'h6'   => 'H6',
					'div'  => 'div',
					'span' => 'span',
					'p'    => 'p',
				),
			)
		);

		$this->add_control(
			'download_text',
			array(
				'label'       => __( 'Download Link Text', 'soma' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => __( 'Download Document', 'soma' ),
				'placeholder' => __( 'Enter download link text', 'soma' ),
			)
		);

		$this->end_controls_section();

		// Layout section.
		$this->start_controls_section(
			'section_layout',
			array(
				'label' => __( 'Layout', 'soma' ),
			)
		);

		$this->add_responsive_control(
			'columns',
			array(
				'label'           => __( 'Columns', 'soma' ),
				'type'            => Controls_Manager::SELECT,
				'desktop_default' => '4',
				'tablet_default'  => '2',
				'mobile_default'  => '1',
				'options'         => array(
					'1' => '1',
					'2' => '2',
					'3' => '3',
					'4' => '4',
				),
				'selectors'       => array(
					'{{WRAPPER}} .documents-grid' => 'grid-template-columns: repeat({{VALUE}}, 1fr);',
				),
			)
		);

		$this->add_responsive_control(
			'grid_gap',
			array(
				'label'      => __( 'Gap', 'soma' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', 'rem' ),
				'range'      => array(
					'px'  => array(
						'min' => 0,
						'max' => 100,
					),
					'rem' => array(
						'min' => 0,
						'max' => 6,
					),
				),
				'default'    => array(
					'size' => 30,
					'unit' => 'px',
				),
				'selectors'  => array(
					'{{WRAPPER}} .documents-grid' => 'gap: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_section();
	}

	/**
	 * Register style controls
	 *
	 * @return void
	 */
	private function register_style_controls(): void {
		// Card styles.
		$this->start_controls_section(
			'section_style_card',
			array(
				'label' => __( 'Card', 'soma' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_background_control( 'card_background', __( 'Background', 'soma' ), '{{WRAPPER}} .document-item' );
		$this->add_spacing_control( 'card_padding', __( 'Padding', 'soma' ), '{{WRAPPER}} .document-content' );
		$this->add_border_control( 'card_border', __( 'Border', 'soma' ), '{{WRAPPER}} .document-item' );
		$this->add_shadow_control( 'card_shadow', __( 'Box Shadow', 'soma' ), '{{WRAPPER}} .document-item' );

		$this->end_controls_section();

		// Image styles.
		$this->start_controls_section(
			'section_style_image',
			array(
				'label' => __( 'Image', 'soma' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_responsive_control(
			'image_height',
			array(
				'label'      => __( 'Height', 'soma' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array(
					'px' => array(
						'min' => 100,
						'max' => 600,
					),
				),
				'default'    => array(
					'size' => 350,
					'unit' => 'px',
				),
				'selectors'  => array(
					'{{WRAPPER}} .document-image' => 'height: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'image_border_radius',
			array(
				'label'      => __( 'Border Radius', 'soma' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .document-image' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_section();

		// Title styles.
		$this->start_controls_section(
			'section_style_title',
			array(
				'label' => __( 'Title', 'soma' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'title_typography',
				'label'    => __( 'Typography', 'soma' ),
				'global'   => array(
					'default' => Global_Typography::TYPOGRAPHY_PRIMARY,
				),
				'selector' => '{{WRAPPER}} .document-title',
			)
		);

		$this->add_control(
			'title_color',
			array(
				'label'     => __( 'Color', 'soma' ),
				'type'      => Controls_Manager::COLOR,
				'global'    => array(
					'default' => Global_Colors::COLOR_PRIMARY,
				),
				'selectors' => array(
					'{{WRAPPER}} .document-title' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_responsive_control(
			'title_spacing',
			array(
				'label'      => __( 'Spacing', 'soma' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', 'rem' ),
				'range'      => array(
					'px'  => array(
						'min' => 0,
						'max' => 50,
					),
					'rem' => array(
						'min' => 0,
						'max' => 3,
					),
				),
				'default'    => array(
					'size' => 15,
					'unit' => 'px',
				),
				'selectors'  => array(
					'{{WRAPPER}} .document-title' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_section();

		// Download link styles.
		$this->start_controls_section(
			'section_style_download',
			array(
				'label' => __( 'Download Link', 'soma' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'download_typography',
				'label'    => __( 'Typography', 'soma' ),
				'global'   => array(
					'default' => Global_Typography::TYPOGRAPHY_PRIMARY,
				),
				'selector' => '{{WRAPPER}} .document-download',
			)
		);

		$this->add_control(
			'download_color',
			array(
				'label'     => __( 'Color', 'soma' ),
				'type'      => Controls_Manager::COLOR,
				'global'    => array(
					'default' => Global_Colors::COLOR_PRIMARY,
				),
				'selectors' => array(
					'{{WRAPPER}} .document-download' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'download_hover_color',
			array(
				'label'     => __( 'Hover Color', 'soma' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .document-download:hover' => 'color: {{VALUE}};',
				),
			)
		);

		$this->end_controls_section();
	}

	/**
	 * Render widget output
	 *
	 * Only displays documents that have a valid file attached.
	 * Queries extra posts to ensure the configured limit is met.
	 *
	 * @return void
	 */
	protected function render(): void {
		$settings = $this->get_settings_for_display();

		$limit         = (int) $settings['posts_per_page'];
		$title_tag     = $settings['title_tag'];
		$download_text = $settings['download_text'];

		// Query extra posts to account for documents without files.
		// We fetch 3x the limit to have enough buffer.
		$documents = new \WP_Query(
			array(
				'post_type'      => 'documents-reports',
				'posts_per_page' => $limit * 3,
				'orderby'        => $settings['orderby'],
				'order'          => $settings['order'],
				'post_status'    => 'publish',
			)
		);

		// Filter documents to only include those with valid files.
		$valid_documents       = array();
		$valid_documents_count = 0;

		if ( $documents->have_posts() ) {
			while ( $documents->have_posts() && $valid_documents_count < $limit ) {
				$documents->the_post();
				$post_id = get_the_ID();
				$content = get_field( 'document_content', $post_id );
				$file    = $content ? \soma_get_i18n_field( $content, 'file' ) : null;

				// Only include documents with valid file URL.
				if ( ! empty( $file['url'] ) ) {
					$valid_documents[] = array(
						'id'        => $post_id,
						'title'     => get_the_title(),
						'thumbnail' => has_post_thumbnail() ? get_the_post_thumbnail(
							$post_id,
							'medium',
							array(
								'loading' => 'lazy',
								'alt'     => get_the_title(),
							)
						) : null,
						'file_url'  => $file['url'],
					);
					++$valid_documents_count;
				}
			}
			wp_reset_postdata();
		}

		?>
		<div class="soma-documents">
			<?php if ( ! empty( $valid_documents ) ) : ?>
				<div class="documents-grid">
					<?php foreach ( $valid_documents as $doc ) : ?>
						<article class="document-item">
							<?php if ( $doc['thumbnail'] ) : ?>
								<div class="document-image">
									<?php echo wp_kses_post( $doc['thumbnail'] ); ?>
								</div>
							<?php endif; ?>

							<div class="document-content">
								<<?php echo esc_html( $title_tag ); ?> class="document-title">
									<?php echo esc_html( $doc['title'] ); ?>
								</<?php echo esc_html( $title_tag ); ?>>

								<a
									href="<?php echo esc_url( $doc['file_url'] ); ?>"
									class="document-download"
									target="_blank"
									rel="noopener noreferrer"
								>
									<?php echo esc_html( $download_text ); ?>
								</a>
							</div>
						</article>
					<?php endforeach; ?>
				</div>
			<?php else : ?>
				<p class="no-documents"><?php esc_html_e( 'No documents found.', 'soma' ); ?></p>
			<?php endif; ?>
		</div>
		<?php
	}
}
