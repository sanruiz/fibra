<?php
/**
 * Title Description Accordion Elementor Widget.
 *
 * Displays a single accordion item with title and description content.
 *
 * @package    Soma
 * @subpackage Elementor\Widgets
 * @since      3.1.25
 */

declare(strict_types=1);

namespace Soma\Elementor\Widgets;

use Elementor\Controls_Manager;
use Soma\Elementor\Base\WidgetBase;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * TitleDescriptionAccordion widget class.
 *
 * @since 3.1.25
 */
class TitleDescriptionAccordion extends WidgetBase {

	/**
	 * Get widget name.
	 *
	 * @return string Widget name.
	 */
	public function get_name(): string {
		return 'soma-title-description-accordion';
	}

	/**
	 * Get widget title.
	 *
	 * @return string Widget title.
	 */
	public function get_title(): string {
		return esc_html__( 'SOMA Title Description Accordion', 'soma' );
	}

	/**
	 * Get widget icon.
	 *
	 * @return string Widget icon.
	 */
	public function get_icon(): string {
		return 'eicon-accordion';
	}

	/**
	 * Get widget keywords.
	 *
	 * @return array Widget keywords.
	 */
	public function get_keywords(): array {
		return array( 'accordion', 'title', 'description', 'content', 'soma' );
	}

	/**
	 * Get style dependencies.
	 *
	 * @return array Style dependencies.
	 */
	public function get_style_depends(): array {
		return array( 'soma-title-description-accordion' );
	}

	/**
	 * Register widget controls.
	 *
	 * @return void
	 */
	protected function register_controls(): void {
		$this->start_controls_section(
			'section_content',
			array(
				'label' => esc_html__( 'Content', 'soma' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);

		$this->add_control(
			'title',
			array(
				'label'       => esc_html__( 'Title', 'soma' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => $this->get_default_title(),
				'placeholder' => esc_html__( 'Enter title', 'soma' ),
				'label_block' => true,
				'dynamic'     => array(
					'active' => true,
				),
			)
		);

		$this->add_control(
			'description',
			array(
				'label'       => esc_html__( 'Fallback Description', 'soma' ),
				'type'        => Controls_Manager::WYSIWYG,
				'default'     => esc_html__( 'Write your accordion description content here.', 'soma' ),
				'placeholder' => esc_html__( 'Used only if ACF Sustainability is empty.', 'soma' ),
				'description' => esc_html__( 'Primary content is loaded from the ACF field "Sostenibilidad" (portfolio_sustainability).', 'soma' ),
				'dynamic'     => array(
					'active' => true,
				),
			)
		);

		$this->end_controls_section();
	}

	/**
	 * Get default title based on current editing language.
	 *
	 * @return string Localized default title.
	 */
	private function get_default_title(): string {
		$editor_lang = filter_input( INPUT_GET, 'edit_lang', FILTER_SANITIZE_FULL_SPECIAL_CHARS );

		if ( is_string( $editor_lang ) ) {
			$editor_lang = strtolower( $editor_lang );

			if ( str_starts_with( $editor_lang, 'es' ) ) {
				return 'Sostenibilidad';
			}

			if ( str_starts_with( $editor_lang, 'en' ) ) {
				return 'Sustainability';
			}
		}

		if ( function_exists( 'wpm_get_language' ) ) {
			$current_language = strtolower( (string) wpm_get_language() );

			if ( str_starts_with( $current_language, 'es' ) ) {
				return 'Sostenibilidad';
			}

			if ( str_starts_with( $current_language, 'en' ) ) {
				return 'Sustainability';
			}
		}

		$locale = function_exists( 'determine_locale' ) ? determine_locale() : get_locale();

		if ( str_starts_with( strtolower( (string) $locale ), 'en' ) ) {
			return 'Sustainability';
		}

		return 'Sostenibilidad';
	}

	/**
	 * Render widget output.
	 *
	 * @return void
	 */
	protected function render(): void {
		$settings     = $this->get_settings_for_display();
		$title        = ! empty( $settings['title'] ) ? $settings['title'] : '';
		$fallback     = ! empty( $settings['description'] ) ? $settings['description'] : '';
		$description  = $this->get_sustainability_content( $fallback );
		$accordion_id = 'soma-accordion-' . $this->get_id();

		if ( '' === $title && '' === $description ) {
			return;
		}
		?>
		<div class="soma-title-description-accordion">
			<details class="soma-title-description-accordion__item">
				<summary
					id="<?php echo esc_attr( $accordion_id . '-title' ); ?>"
					class="soma-title-description-accordion__title"
				>
					<?php echo esc_html( $title ); ?>
				</summary>
				<div
					id="<?php echo esc_attr( $accordion_id . '-content' ); ?>"
					class="soma-title-description-accordion__content"
					aria-labelledby="<?php echo esc_attr( $accordion_id . '-title' ); ?>"
				>
					<?php echo wp_kses_post( $description ); ?>
				</div>
			</details>
		</div>
		<?php
	}

	/**
	 * Get sustainability content from ACF field with fallback.
	 *
	 * @param string $fallback Fallback content from Elementor control.
	 * @return string
	 */
	private function get_sustainability_content( string $fallback ): string {
		$post_id = (int) get_the_ID();

		if ( $post_id <= 0 ) {
			$post_id = (int) get_queried_object_id();
		}

		if ( $post_id > 0 ) {
			$acf_content = $this->get_acf_field( 'portfolio_sustainability', $post_id, '' );

			if ( is_string( $acf_content ) && '' !== trim( $acf_content ) ) {
				return $acf_content;
			}
		}

		return $fallback;
	}
}
