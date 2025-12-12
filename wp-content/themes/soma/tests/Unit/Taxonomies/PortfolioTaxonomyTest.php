<?php
/**
 * Portfolio Taxonomy Tests
 *
 * @package Soma\Tests\Unit\Taxonomies
 */

namespace Soma\Tests\Unit\Taxonomies;

use Soma\Taxonomies\PortfolioTaxonomy;
use WP_UnitTestCase;

/**
 * Class PortfolioTaxonomyTest
 */
class PortfolioTaxonomyTest extends WP_UnitTestCase {

	/**
	 * Test singleton instance.
	 */
	public function test_singleton_instance(): void {
		$instance1 = PortfolioTaxonomy::instance();
		$instance2 = PortfolioTaxonomy::instance();

		$this->assertSame( $instance1, $instance2, 'Should return the same instance' );
		$this->assertInstanceOf( PortfolioTaxonomy::class, $instance1 );
	}

	/**
	 * Test taxonomy registration.
	 */
	public function test_taxonomy_registered(): void {
		$taxonomy = PortfolioTaxonomy::instance();

		$this->assertTrue(
			taxonomy_exists( 'portfolio-taxonomy' ),
			'Portfolio taxonomy should be registered'
		);
	}

	/**
	 * Test taxonomy is associated with correct post type.
	 */
	public function test_taxonomy_associated_with_post_type(): void {
		$taxonomy = get_taxonomy( 'portfolio-taxonomy' );

		$this->assertNotFalse( $taxonomy, 'Taxonomy should exist' );
		$this->assertContains(
			'portfolio',
			$taxonomy->object_type,
			'Taxonomy should be associated with portfolio post type'
		);
	}

	/**
	 * Test taxonomy is hierarchical.
	 */
	public function test_taxonomy_is_hierarchical(): void {
		$taxonomy = get_taxonomy( 'portfolio-taxonomy' );

		$this->assertNotFalse( $taxonomy, 'Taxonomy should exist' );
		$this->assertTrue(
			$taxonomy->hierarchical,
			'Taxonomy should be hierarchical'
		);
	}

	/**
	 * Test taxonomy is public.
	 */
	public function test_taxonomy_is_public(): void {
		$taxonomy = get_taxonomy( 'portfolio-taxonomy' );

		$this->assertNotFalse( $taxonomy, 'Taxonomy should exist' );
		$this->assertTrue(
			$taxonomy->public,
			'Taxonomy should be public'
		);
	}

	/**
	 * Test taxonomy shows in UI.
	 */
	public function test_taxonomy_shows_in_ui(): void {
		$taxonomy = get_taxonomy( 'portfolio-taxonomy' );

		$this->assertNotFalse( $taxonomy, 'Taxonomy should exist' );
		$this->assertTrue(
			$taxonomy->show_ui,
			'Taxonomy should show in UI'
		);
	}

	/**
	 * Test get_taxonomy method.
	 */
	public function test_get_taxonomy_method(): void {
		$instance = PortfolioTaxonomy::instance();

		$this->assertEquals(
			'portfolio-taxonomy',
			$instance->get_taxonomy(),
			'get_taxonomy() should return correct taxonomy slug'
		);
	}

	/**
	 * Test get_post_type method.
	 */
	public function test_get_post_type_method(): void {
		$instance = PortfolioTaxonomy::instance();

		$this->assertEquals(
			'portfolio',
			$instance->get_post_type(),
			'get_post_type() should return correct post type slug'
		);
	}
}
