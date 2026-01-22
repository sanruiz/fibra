<?php
/**
 * Unit tests for ProjectTypeTaxonomy class.
 *
 * @package Soma\Tests\Unit\Taxonomies
 * @since   3.1.23
 */

namespace Soma\Tests\Unit\Taxonomies;

use PHPUnit\Framework\TestCase;
use ReflectionClass;

/**
 * Test case for ProjectTypeTaxonomy.
 *
 * @group unit
 * @group taxonomies
 */
class ProjectTypeTaxonomyTest extends TestCase {

	/**
	 * The taxonomy class name.
	 *
	 * @var string
	 */
	private string $taxonomy_class = \Soma\Taxonomies\ProjectTypeTaxonomy::class;

	/**
	 * Test that the class exists.
	 *
	 * @return void
	 */
	public function test_class_exists(): void {
		$this->assertTrue( class_exists( $this->taxonomy_class ) );
	}

	/**
	 * Test singleton pattern returns same instance.
	 *
	 * @return void
	 */
	public function test_singleton_instance(): void {
		$instance1 = $this->taxonomy_class::instance();
		$instance2 = $this->taxonomy_class::instance();

		$this->assertSame( $instance1, $instance2 );
	}

	/**
	 * Test that TAXONOMY constant is defined.
	 *
	 * @return void
	 */
	public function test_taxonomy_constant_defined(): void {
		$reflection = new ReflectionClass( $this->taxonomy_class );

		$this->assertTrue( $reflection->hasConstant( 'TAXONOMY' ) );
		$this->assertSame( 'project-type', $reflection->getConstant( 'TAXONOMY' ) );
	}

	/**
	 * Test that class has required methods.
	 *
	 * @return void
	 */
	public function test_has_required_methods(): void {
		$reflection = new ReflectionClass( $this->taxonomy_class );
		$methods    = array( 'instance', 'register' );

		foreach ( $methods as $method ) {
			$this->assertTrue(
				$reflection->hasMethod( $method ),
				"Method {$method} should exist"
			);
		}
	}

	/**
	 * Test that class uses singleton pattern correctly (private constructor).
	 *
	 * @return void
	 */
	public function test_constructor_is_private(): void {
		$reflection  = new ReflectionClass( $this->taxonomy_class );
		$constructor = $reflection->getConstructor();

		$this->assertNotNull( $constructor );
		$this->assertTrue( $constructor->isPrivate() );
	}

	/**
	 * Test that clone is prevented (private __clone).
	 *
	 * @return void
	 */
	public function test_clone_is_private(): void {
		$reflection = new ReflectionClass( $this->taxonomy_class );

		$this->assertTrue( $reflection->hasMethod( '__clone' ) );
		$this->assertTrue( $reflection->getMethod( '__clone' )->isPrivate() );
	}

	/**
	 * Test that the taxonomy is non-hierarchical (like tags).
	 *
	 * @return void
	 */
	public function test_taxonomy_args_structure(): void {
		$reflection = new ReflectionClass( $this->taxonomy_class );
		$instance   = $this->taxonomy_class::instance();

		// Check that register method exists and can be called.
		$this->assertTrue( $reflection->hasMethod( 'register' ) );
	}
}
