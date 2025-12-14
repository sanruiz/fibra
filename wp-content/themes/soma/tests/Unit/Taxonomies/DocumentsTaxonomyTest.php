<?php
/**
 * Documents Taxonomy Tests
 *
 * @package Soma\Tests\Unit\Taxonomies
 */

namespace Soma\Tests\Unit\Taxonomies;

use Soma\Taxonomies\DocumentsTaxonomy;
use WP_UnitTestCase;

/**
 * Class DocumentsTaxonomyTest
 */
class DocumentsTaxonomyTest extends WP_UnitTestCase {

	/**
	 * Test singleton instance.
	 */
	public function test_singleton_instance(): void {
		$instance1 = DocumentsTaxonomy::instance();
		$instance2 = DocumentsTaxonomy::instance();

		$this->assertSame( $instance1, $instance2, 'Should return the same instance' );
		$this->assertInstanceOf( DocumentsTaxonomy::class, $instance1 );
	}

	/**
	 * Test taxonomy registration.
	 */
	public function test_taxonomy_registered(): void {
		$taxonomy = DocumentsTaxonomy::instance();

		$this->assertTrue(
			taxonomy_exists( 'documents-taxonomy' ),
			'Documents taxonomy should be registered'
		);
	}

	/**
	 * Test taxonomy is associated with correct post type.
	 */
	public function test_taxonomy_associated_with_post_type(): void {
		$taxonomy = get_taxonomy( 'documents-taxonomy' );

		$this->assertNotFalse( $taxonomy, 'Taxonomy should exist' );
		$this->assertContains(
			'documents-reports',
			$taxonomy->object_type,
			'Taxonomy should be associated with documents-reports post type'
		);
	}

	/**
	 * Test taxonomy is hierarchical.
	 */
	public function test_taxonomy_is_hierarchical(): void {
		$taxonomy = get_taxonomy( 'documents-taxonomy' );

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
		$taxonomy = get_taxonomy( 'documents-taxonomy' );

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
		$taxonomy = get_taxonomy( 'documents-taxonomy' );

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
		$instance = DocumentsTaxonomy::instance();

		$this->assertEquals(
			'documents-taxonomy',
			$instance->get_taxonomy(),
			'get_taxonomy() should return correct taxonomy slug'
		);
	}

	/**
	 * Test get_post_type method.
	 */
	public function test_get_post_type_method(): void {
		$instance = DocumentsTaxonomy::instance();

		$this->assertEquals(
			'documents-reports',
			$instance->get_post_type(),
			'get_post_type() should return correct post type slug'
		);
	}
}
