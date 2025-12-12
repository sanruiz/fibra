<?php
/**
 * Taxonomies Integration Tests
 *
 * Tests the complete integration of all custom taxonomies with their post types.
 *
 * @package Soma\Tests\Integration
 */

namespace Soma\Tests\Integration;

use Soma\Taxonomies\Loader;
use Soma\Taxonomies\TeamMembersTaxonomy;
use Soma\Taxonomies\PortfolioTaxonomy;
use Soma\Taxonomies\DocumentsTaxonomy;
use Soma\Core\Enums\Taxonomy;
use WP_UnitTestCase;

/**
 * Class TaxonomiesTest
 */
class TaxonomiesTest extends WP_UnitTestCase {

	/**
	 * Test taxonomy enum values and methods.
	 */
	public function test_taxonomy_enum(): void {
		// Test enum values match class outputs.
		$this->assertEquals(
			Taxonomy::TEAM_MEMBERS->value(),
			TeamMembersTaxonomy::instance()->get_taxonomy()
		);

		$this->assertEquals(
			Taxonomy::PORTFOLIO->value(),
			PortfolioTaxonomy::instance()->get_taxonomy()
		);

		$this->assertEquals(
			Taxonomy::DOCUMENTS->value(),
			DocumentsTaxonomy::instance()->get_taxonomy()
		);

		// Test enum post type associations.
		$this->assertEquals( 'team-members', Taxonomy::TEAM_MEMBERS->postType() );
		$this->assertEquals( 'portfolio', Taxonomy::PORTFOLIO->postType() );
		$this->assertEquals( 'documents-reports', Taxonomy::DOCUMENTS->postType() );

		// Test enum labels.
		$this->assertNotEmpty( Taxonomy::TEAM_MEMBERS->label() );
		$this->assertNotEmpty( Taxonomy::TEAM_MEMBERS->singularLabel() );

		// Test enum args method.
		$args = Taxonomy::TEAM_MEMBERS->getArgs();
		$this->assertIsArray( $args );
		$this->assertArrayHasKey( 'labels', $args );
		$this->assertArrayHasKey( 'hierarchical', $args );
		$this->assertTrue( $args['hierarchical'] );
	}

	/**
	 * Test Taxonomies Loader singleton.
	 */
	public function test_loader_singleton(): void {
		$instance1 = Loader::instance();
		$instance2 = Loader::instance();

		$this->assertSame( $instance1, $instance2, 'Loader should return the same instance' );
		$this->assertInstanceOf( Loader::class, $instance1 );
	}

	/**
	 * Test Loader implements LoadableInterface.
	 */
	public function test_loader_implements_loadable_interface(): void {
		$loader = Loader::instance();

		$this->assertInstanceOf(
			\Soma\Core\Interfaces\LoadableInterface::class,
			$loader,
			'Loader should implement LoadableInterface'
		);
	}

	/**
	 * Test Loader priority.
	 */
	public function test_loader_priority(): void {
		$loader = Loader::instance();

		$this->assertEquals(
			15,
			$loader->get_priority(),
			'Taxonomies should load at priority 15'
		);
	}

	/**
	 * Test Loader should_load method.
	 */
	public function test_loader_should_load(): void {
		$loader = Loader::instance();

		$this->assertTrue(
			$loader->should_load(),
			'Taxonomies should always load'
		);
	}

	/**
	 * Test taxonomy class singletons.
	 */
	public function test_taxonomy_class_singletons(): void {
		// TeamMembersTaxonomy singleton.
		$team1 = TeamMembersTaxonomy::instance();
		$team2 = TeamMembersTaxonomy::instance();
		$this->assertSame( $team1, $team2, 'TeamMembersTaxonomy should return same instance' );

		// PortfolioTaxonomy singleton.
		$portfolio1 = PortfolioTaxonomy::instance();
		$portfolio2 = PortfolioTaxonomy::instance();
		$this->assertSame( $portfolio1, $portfolio2, 'PortfolioTaxonomy should return same instance' );

		// DocumentsTaxonomy singleton.
		$docs1 = DocumentsTaxonomy::instance();
		$docs2 = DocumentsTaxonomy::instance();
		$this->assertSame( $docs1, $docs2, 'DocumentsTaxonomy should return same instance' );
	}

	/**
	 * Test taxonomy class getter methods.
	 */
	public function test_taxonomy_class_getters(): void {
		// TeamMembersTaxonomy getters.
		$team_tax = TeamMembersTaxonomy::instance();
		$this->assertEquals( 'team-members-taxonomy', $team_tax->get_taxonomy() );
		$this->assertEquals( 'team-members', $team_tax->get_post_type() );

		// PortfolioTaxonomy getters.
		$portfolio_tax = PortfolioTaxonomy::instance();
		$this->assertEquals( 'portfolio-taxonomy', $portfolio_tax->get_taxonomy() );
		$this->assertEquals( 'portfolio', $portfolio_tax->get_post_type() );

		// DocumentsTaxonomy getters.
		$docs_tax = DocumentsTaxonomy::instance();
		$this->assertEquals( 'documents-taxonomy', $docs_tax->get_taxonomy() );
		$this->assertEquals( 'documents-reports', $docs_tax->get_post_type() );
	}

	/**
	 * Test all three taxonomies are registered via classes.
	 */
	public function test_all_taxonomies_registered(): void {
		// Verify via classes that taxonomies exist.
		$team_tax      = TeamMembersTaxonomy::instance();
		$portfolio_tax = PortfolioTaxonomy::instance();
		$docs_tax      = DocumentsTaxonomy::instance();

		$this->assertTrue(
			taxonomy_exists( $team_tax->get_taxonomy() ),
			'Team Members taxonomy should be registered'
		);

		$this->assertTrue(
			taxonomy_exists( $portfolio_tax->get_taxonomy() ),
			'Portfolio taxonomy should be registered'
		);

		$this->assertTrue(
			taxonomy_exists( $docs_tax->get_taxonomy() ),
			'Documents taxonomy should be registered'
		);
	}

	/**
	 * Test taxonomies are associated with correct post types via classes.
	 */
	public function test_taxonomies_post_type_associations(): void {
		// Get instances.
		$team_tax      = TeamMembersTaxonomy::instance();
		$portfolio_tax = PortfolioTaxonomy::instance();
		$docs_tax      = DocumentsTaxonomy::instance();

		// Team Members Taxonomy.
		$team_wp_tax = get_taxonomy( $team_tax->get_taxonomy() );
		$this->assertContains( $team_tax->get_post_type(), $team_wp_tax->object_type );

		// Portfolio Taxonomy.
		$portfolio_wp_tax = get_taxonomy( $portfolio_tax->get_taxonomy() );
		$this->assertContains( $portfolio_tax->get_post_type(), $portfolio_wp_tax->object_type );

		// Documents Taxonomy.
		$docs_wp_tax = get_taxonomy( $docs_tax->get_taxonomy() );
		$this->assertContains( $docs_tax->get_post_type(), $docs_wp_tax->object_type );
	}

	/**
	 * Test all taxonomies are hierarchical.
	 */
	public function test_all_taxonomies_hierarchical(): void {
		$team_tax      = get_taxonomy( 'team-members-taxonomy' );
		$portfolio_tax = get_taxonomy( 'portfolio-taxonomy' );
		$docs_tax      = get_taxonomy( 'documents-taxonomy' );

		$this->assertTrue( $team_tax->hierarchical, 'Team Members taxonomy should be hierarchical' );
		$this->assertTrue( $portfolio_tax->hierarchical, 'Portfolio taxonomy should be hierarchical' );
		$this->assertTrue( $docs_tax->hierarchical, 'Documents taxonomy should be hierarchical' );
	}

	/**
	 * Test all taxonomies are public.
	 */
	public function test_all_taxonomies_public(): void {
		$team_tax      = get_taxonomy( 'team-members-taxonomy' );
		$portfolio_tax = get_taxonomy( 'portfolio-taxonomy' );
		$docs_tax      = get_taxonomy( 'documents-taxonomy' );

		$this->assertTrue( $team_tax->public, 'Team Members taxonomy should be public' );
		$this->assertTrue( $portfolio_tax->public, 'Portfolio taxonomy should be public' );
		$this->assertTrue( $docs_tax->public, 'Documents taxonomy should be public' );
	}

	/**
	 * Test all taxonomies show in UI.
	 */
	public function test_all_taxonomies_show_in_ui(): void {
		$team_tax      = get_taxonomy( 'team-members-taxonomy' );
		$portfolio_tax = get_taxonomy( 'portfolio-taxonomy' );
		$docs_tax      = get_taxonomy( 'documents-taxonomy' );

		$this->assertTrue( $team_tax->show_ui, 'Team Members taxonomy should show in UI' );
		$this->assertTrue( $portfolio_tax->show_ui, 'Portfolio taxonomy should show in UI' );
		$this->assertTrue( $docs_tax->show_ui, 'Documents taxonomy should show in UI' );
	}

	/**
	 * Test creating terms in taxonomies.
	 */
	public function test_create_taxonomy_terms(): void {
		// Create term in Team Members taxonomy.
		$team_term = wp_insert_term( 'Test Team Category', 'team-members-taxonomy' );
		$this->assertIsArray( $team_term, 'Should create term in Team Members taxonomy' );
		$this->assertArrayHasKey( 'term_id', $team_term );

		// Create term in Portfolio taxonomy.
		$portfolio_term = wp_insert_term( 'Test Portfolio Category', 'portfolio-taxonomy' );
		$this->assertIsArray( $portfolio_term, 'Should create term in Portfolio taxonomy' );
		$this->assertArrayHasKey( 'term_id', $portfolio_term );

		// Create term in Documents taxonomy.
		$docs_term = wp_insert_term( 'Test Documents Category', 'documents-taxonomy' );
		$this->assertIsArray( $docs_term, 'Should create term in Documents taxonomy' );
		$this->assertArrayHasKey( 'term_id', $docs_term );

		// Clean up.
		wp_delete_term( $team_term['term_id'], 'team-members-taxonomy' );
		wp_delete_term( $portfolio_term['term_id'], 'portfolio-taxonomy' );
		wp_delete_term( $docs_term['term_id'], 'documents-taxonomy' );
	}

	/**
	 * Test assigning taxonomy terms to posts.
	 */
	public function test_assign_terms_to_posts(): void {
		// Create a test portfolio post.
		$post_id = $this->factory->post->create(
			array(
				'post_type'   => 'portfolio',
				'post_status' => 'publish',
			)
		);

		// Create a taxonomy term.
		$term = wp_insert_term( 'Test Category', 'portfolio-taxonomy' );

		// Assign term to post.
		$result = wp_set_object_terms( $post_id, $term['term_id'], 'portfolio-taxonomy' );

		$this->assertIsArray( $result, 'Should assign term to post' );
		$this->assertNotEmpty( $result );

		// Verify term is assigned.
		$terms = wp_get_object_terms( $post_id, 'portfolio-taxonomy' );
		$this->assertCount( 1, $terms, 'Post should have one term assigned' );
		$this->assertEquals( 'Test Category', $terms[0]->name );

		// Clean up.
		wp_delete_post( $post_id, true );
		wp_delete_term( $term['term_id'], 'portfolio-taxonomy' );
	}

	/**
	 * Test taxonomy rewrite rules.
	 */
	public function test_taxonomy_rewrite_rules(): void {
		$team_tax      = get_taxonomy( 'team-members-taxonomy' );
		$portfolio_tax = get_taxonomy( 'portfolio-taxonomy' );
		$docs_tax      = get_taxonomy( 'documents-taxonomy' );

		$this->assertIsArray( $team_tax->rewrite, 'Team Members taxonomy should have rewrite rules' );
		$this->assertFalse( $team_tax->rewrite['with_front'], 'Team Members taxonomy should not use with_front' );

		$this->assertIsArray( $portfolio_tax->rewrite, 'Portfolio taxonomy should have rewrite rules' );
		$this->assertFalse( $portfolio_tax->rewrite['with_front'], 'Portfolio taxonomy should not use with_front' );

		$this->assertIsArray( $docs_tax->rewrite, 'Documents taxonomy should have rewrite rules' );
		$this->assertFalse( $docs_tax->rewrite['with_front'], 'Documents taxonomy should not use with_front' );
	}
}
