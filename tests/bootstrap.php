<?php
/**
 * PHPUnit Bootstrap File
 *
 * Sets up the testing environment for the PBCR Agent Mode plugin.
 *
 * @package PBCRAgentMode
 */

// Prevent direct access.
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', dirname( __DIR__ ) . '/' );
}

// Define plugin constants for testing.
define( 'PBCR_AGENT_MODE_PLUGIN_FILE', dirname( __DIR__ ) . '/pbcr-agent-mode.php' );
define( 'PBCR_AGENT_MODE_PLUGIN_PATH', dirname( __DIR__ ) . '/' );
define( 'PBCR_AGENT_MODE_PLUGIN_URL', 'http://localhost/wp-content/plugins/pbcr-agent-mode/' );
define( 'PBCR_AGENT_MODE_VERSION', '1.0.0' );

// Load Composer autoloader.
require_once dirname( __DIR__ ) . '/vendor/autoload.php';

// Initialize Brain Monkey for WordPress function mocking.
\Brain\Monkey\setUp();

// Load the plugin's autoloader function.
require_once PBCR_AGENT_MODE_PLUGIN_PATH . 'pbcr-agent-mode.php';

// Set up WordPress test environment if available.
if ( getenv( 'WP_TESTS_DIR' ) ) {
	$wp_tests_dir = getenv( 'WP_TESTS_DIR' );

	if ( file_exists( $wp_tests_dir . '/includes/functions.php' ) ) {
		require_once $wp_tests_dir . '/includes/functions.php';

		/**
		 * Manually load the plugin for testing.
		 */
		function _manually_load_plugin() {
			require PBCR_AGENT_MODE_PLUGIN_FILE;
		}
		tests_add_filter( 'muplugins_loaded', '_manually_load_plugin' );

		require $wp_tests_dir . '/includes/bootstrap.php';
	}
}
