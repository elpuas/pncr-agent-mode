<?php
/**
 * Sample Test Case
 *
 * Basic test to verify the testing infrastructure is working correctly.
 *
 * @package PBCRAgentMode
 */

namespace PBCRAgentMode\Tests;

use PHPUnit\Framework\TestCase;
use Brain\Monkey;
use PBCRAgentMode\Plugin;

/**
 * Class SampleTest
 *
 * Test the basic plugin functionality and infrastructure.
 */
class SampleTest extends TestCase {

	/**
	 * Set up the test environment before each test.
	 */
	protected function setUp(): void {
		parent::setUp();
		Monkey\setUp();
	}

	/**
	 * Tear down the test environment after each test.
	 */
	protected function tearDown(): void {
		Monkey\tearDown();
		parent::tearDown();
	}

	/**
	 * Test that the plugin class exists and can be instantiated.
	 */
	public function test_plugin_class_exists() {
		$this->assertTrue( class_exists( 'PBCRAgentMode\Plugin' ) );
	}

	/**
	 * Test that the plugin class can be instantiated.
	 */
	public function test_plugin_can_be_instantiated() {
		$plugin = new Plugin();
		$this->assertInstanceOf( Plugin::class, $plugin );
	}

	/**
	 * Test that plugin constants are defined.
	 */
	public function test_plugin_constants_defined() {
		$this->assertTrue( defined( 'PBCR_AGENT_MODE_VERSION' ) );
		$this->assertTrue( defined( 'PBCR_AGENT_MODE_PLUGIN_PATH' ) );
		$this->assertTrue( defined( 'PBCR_AGENT_MODE_PLUGIN_URL' ) );
	}

	/**
	 * Test that the autoloader function exists.
	 */
	public function test_autoloader_function_exists() {
		$this->assertTrue( function_exists( 'pbcr_agent_mode_autoload' ) );
	}

	/**
	 * Test basic WordPress function mocking with Brain Monkey.
	 */
	public function test_wordpress_function_mocking() {
		// Mock a WordPress function.
		Monkey\Functions\when( 'is_singular' )->justReturn( true );
		Monkey\Functions\when( 'get_query_var' )->justReturn( '1' );

		// Test the mocked functions.
		$this->assertTrue( \is_singular() );
		$this->assertEquals( '1', \get_query_var() );
	}
}
