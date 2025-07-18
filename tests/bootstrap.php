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

// Load Composer autoloader.
require_once dirname( __DIR__ ) . '/vendor/autoload.php';

// Initialize Brain Monkey for WordPress function mocking.
\Brain\Monkey\setUp();

// Mock WordPress functions that are needed during plugin loading
if ( ! function_exists( 'plugin_dir_path' ) ) {
	/**
	 * Mock plugin_dir_path function for testing.
	 *
	 * @param string $file The plugin file path.
	 * @return string The plugin directory path.
	 */
	function plugin_dir_path( $file ) {
		return trailingslashit( dirname( $file ) );
	}
}

if ( ! function_exists( 'plugin_dir_url' ) ) {
	/**
	 * Mock plugin_dir_url function for testing.
	 *
	 * @param string $file The plugin file path.
	 * @return string The plugin directory URL.
	 */
	function plugin_dir_url( $file ) {
		return 'http://localhost/wp-content/plugins/' . basename( dirname( $file ) ) . '/';
	}
}

if ( ! function_exists( 'plugin_basename' ) ) {
	/**
	 * Mock plugin_basename function for testing.
	 *
	 * @param string $file The plugin file path.
	 * @return string The plugin basename.
	 */
	function plugin_basename( $file ) {
		$plugin_dir = dirname( dirname( $file ) );
		return str_replace( $plugin_dir . '/', '', $file );
	}
}

if ( ! function_exists( 'trailingslashit' ) ) {
	/**
	 * Mock trailingslashit function for testing.
	 *
	 * @param string $string The string to add trailing slash to.
	 * @return string The string with trailing slash.
	 */
	function trailingslashit( $string ) {
		return rtrim( $string, '/\\' ) . '/';
	}
}

if ( ! function_exists( 'register_activation_hook' ) ) {
	/**
	 * Mock register_activation_hook function for testing.
	 *
	 * @param string $file The plugin file.
	 * @param callable $callback The activation callback.
	 */
	function register_activation_hook( $file, $callback ) {
		// Mock - do nothing in tests
	}
}

if ( ! function_exists( 'register_deactivation_hook' ) ) {
	/**
	 * Mock register_deactivation_hook function for testing.
	 *
	 * @param string $file The plugin file.
	 * @param callable $callback The deactivation callback.
	 */
	function register_deactivation_hook( $file, $callback ) {
		// Mock - do nothing in tests
	}
}

if ( ! function_exists( 'add_action' ) ) {
	/**
	 * Mock add_action function for testing.
	 *
	 * @param string $hook The action hook.
	 * @param callable $callback The callback function.
	 * @param int $priority The priority.
	 * @param int $accepted_args The number of accepted arguments.
	 */
	function add_action( $hook, $callback, $priority = 10, $accepted_args = 1 ) {
		// Mock - do nothing in tests
	}
}

if ( ! function_exists( 'add_filter' ) ) {
	/**
	 * Mock add_filter function for testing.
	 *
	 * @param string $hook The filter hook.
	 * @param callable $callback The callback function.
	 * @param int $priority The priority.
	 * @param int $accepted_args The number of accepted arguments.
	 */
	function add_filter( $hook, $callback, $priority = 10, $accepted_args = 1 ) {
		// Mock - do nothing in tests
	}
}

// Define plugin constants for testing.
define( 'PBCR_AGENT_MODE_PLUGIN_FILE', dirname( __DIR__ ) . '/pbcr-agent-mode.php' );
define( 'PBCR_AGENT_MODE_PLUGIN_PATH', dirname( __DIR__ ) . '/' );
define( 'PBCR_AGENT_MODE_PLUGIN_URL', 'http://localhost/wp-content/plugins/pbcr-agent-mode/' );
define( 'PBCR_AGENT_MODE_VERSION', '1.0.0' );

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

