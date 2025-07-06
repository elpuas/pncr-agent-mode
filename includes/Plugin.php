<?php
/**
 * Main Plugin Class
 *
 * Handles the initialization and registration of all plugin components.
 *
 * @package PBCRAgentMode
 */

namespace PBCRAgentMode;

// If this file is called directly, abort.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Main Plugin Class
 *
 * Handles the initialization and registration of all plugin components.
 */
class Plugin {

	/**
	 * Plugin version
	 */
	const VERSION = '1.0.0';

	/**
	 * The loader instance
	 *
	 * @var Loader
	 */
	private $loader;

	/**
	 * Constructor
	 */
	public function __construct() {
		$this->loader = new Loader();
	}

	/**
	 * Register all plugin functionality.
	 */
	public function register() {
		// Register the loader.
		$this->loader->register();

		// Hook into WordPress template system.
		add_action( 'init', array( $this, 'init_rewrite_rules' ) );
		add_filter( 'template_include', array( $this, 'template_include' ) );
		add_filter( 'query_vars', array( $this, 'add_query_vars' ) );
	}

	/**
	 * Initialize rewrite rules for agent mode.
	 *
	 * Note: For immediate functionality, the plugin uses query parameters (?agent_view=1).
	 * Custom rewrite rules can be added later if cleaner URLs are needed.
	 * To enable, uncomment the add_rewrite_endpoint line and flush permalinks.
	 */
	public function init_rewrite_rules() {
		// Uncomment the following line to enable clean URLs like /property/sample-property/agent-view/
		// Then go to Settings > Permalinks and click "Save Changes" to flush rewrite rules.
		// add_rewrite_endpoint( 'agent-view', EP_PERMALINK );
	}

	/**
	 * Add custom query variables.
	 *
	 * @param array $vars The array of query variables.
	 * @return array Modified query variables array.
	 */
	public function add_query_vars( $vars ) {
		$vars[] = 'agent_view';
		return $vars;
	}

	/**
	 * Include custom template for agent mode.
	 *
	 * @param string $template The path of the template to include.
	 * @return string The path of the template to include.
	 */
	public function template_include( $template ) {
		// Check if we're viewing a single property with agent_view parameter.
		if ( \is_singular( 'property' ) && $this->is_agent_view() ) {
			$agent_template = PBCR_AGENT_MODE_PLUGIN_PATH . 'includes/Views/agent-template.php';
			if ( file_exists( $agent_template ) ) {
				return $agent_template;
			}
		}

		return $template;
	}

	/**
	 * Check if current request is for agent view.
	 *
	 * @return bool True if this is an agent view request.
	 */
	private function is_agent_view() {
		// Check both query var and GET parameter for compatibility.
		// phpcs:ignore WordPress.Security.NonceVerification.Recommended -- GET parameter check, not form processing.
		return ( '1' === \get_query_var( 'agent_view' ) ) || ( isset( $_GET['agent_view'] ) && '1' === $_GET['agent_view'] );
	}
}
