<?php
/**
 * Plugin Name: PBCR Agent Mode
 * Description: Enables a "Broker Mode" or "Agent Mode" feature for sharing property listings via unique URLs with minimal, unbranded views.
 * Version: 1.0.0
 * Author: ElPuas Digital Crafts
 * Author URI: https://elpuasdigitalcrafts.com
 * Text Domain: pbcr-agent-mode
 * Domain Path: /languages
 * Requires at least: 6.4
 * Tested up to: 6.4
 * Requires PHP: 8.2
 * License: GPL v2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 *
 * @package PBCRAgentMode
 */

// If this file is called directly, abort.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Define plugin constants.
define( 'PBCR_AGENT_MODE_VERSION', '1.0.0' );
define( 'PBCR_AGENT_MODE_PLUGIN_FILE', __FILE__ );
define( 'PBCR_AGENT_MODE_PLUGIN_PATH', plugin_dir_path( __FILE__ ) );
define( 'PBCR_AGENT_MODE_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
define( 'PBCR_AGENT_MODE_PLUGIN_BASENAME', plugin_basename( __FILE__ ) );

/**
 * Autoloader function for plugin classes.
 *
 * @param string $class_name The name of the class to load.
 */
function pbcr_agent_mode_autoload( $class_name ) {
	// Check if this is our namespace.
	if ( strpos( $class_name, 'PBCRAgentMode\\' ) !== 0 ) {
		return;
	}

	// Remove the namespace prefix.
	$class_name = str_replace( 'PBCRAgentMode\\', '', $class_name );

	// Convert namespace separators to directory separators.
	$class_name = str_replace( '\\', DIRECTORY_SEPARATOR, $class_name );

	// Build the file path.
	$file_path = PBCR_AGENT_MODE_PLUGIN_PATH . 'includes' . DIRECTORY_SEPARATOR . $class_name . '.php';

	// Include the file if it exists.
	if ( file_exists( $file_path ) ) {
		require_once $file_path;
	}
}

// Register the autoloader.
spl_autoload_register( 'pbcr_agent_mode_autoload' );

// Plugin activation hook.
register_activation_hook( __FILE__, 'pbcr_agent_mode_activate' );

/**
 * Plugin activation callback.
 */
function pbcr_agent_mode_activate() {
	// Flush rewrite rules on activation.
	flush_rewrite_rules();
}

// Plugin deactivation hook.
register_deactivation_hook( __FILE__, 'pbcr_agent_mode_deactivate' );

/**
 * Plugin deactivation callback.
 */
function pbcr_agent_mode_deactivate() {
	// Flush rewrite rules on deactivation.
	flush_rewrite_rules();
}

/**
 * Initialize the plugin.
 */
function pbcr_agent_mode_init() {
	if ( class_exists( 'PBCRAgentMode\Plugin' ) ) {
		$plugin = new PBCRAgentMode\Plugin();
		$plugin->register();
	}
}

/**
 * Global helper function to get property data.
 * This is defined here to ensure it's always available.
 *
 * @param int $property_id The property post ID. If null, uses current post.
 * @return array Associative array of formatted property data.
 */
function pbcr_agent_get_property_data( $property_id = null ) {
	if ( class_exists( 'PBCRAgentMode\Helpers\PropertyData' ) ) {
		return \PBCRAgentMode\Helpers\PropertyData::get_property_data( $property_id );
	}
	return [];
}

/**
 * Inject Agent Mode Button into property content.
 *
 * Automatically appends the [agent_view_link_button] shortcode to the content
 * of single property pages to provide easy access to the agent mode view.
 *
 * @param string $content The post content.
 * @return string Modified content with agent button appended.
 */
function pbcr_inject_agent_button_shortcode( $content ) {
	// Only inject on single property pages in frontend
	if ( is_singular( 'property' ) && ! is_admin() && in_the_loop() && is_main_query() ) {
		// Check if shortcode exists to avoid errors
		if ( shortcode_exists( 'agent_view_link_button' ) ) {
			// Avoid duplicate injection if shortcode already exists in content
			if ( strpos( $content, '[agent_view_link_button]' ) === false ) {
				$shortcode_html = do_shortcode( '[agent_view_link_button]' );
				return $content . $shortcode_html;
			}
		}
	}
	return $content;
}

// Hook into WordPress init.
add_action( 'plugins_loaded', 'pbcr_agent_mode_init' );

// Inject agent button into property content.
add_filter( 'the_content', 'pbcr_inject_agent_button_shortcode' );
