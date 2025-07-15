<?php
/**
 * Loader Class
 *
 * Handles the registration of shortcodes and other plugin components.
 *
 * @package PBCRAgentMode
 */

namespace PBCRAgentMode;

// If this file is called directly, abort.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Loader Class
 *
 * Handles the registration of shortcodes and other plugin components.
 */
class Loader {

	/**
	 * Register all loader functionality.
	 */
	public function register() {
		// Register shortcodes.
		$this->register_shortcodes();

		// Register blocks.
		$this->register_blocks();

		// Register styles and scripts.
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_assets' ) );
	}

	/**
	 * Register all shortcodes.
	 */
	private function register_shortcodes() {
		$agent_view_link_button = new Shortcodes\AgentViewLinkButton();
		$agent_view_link_button->register();
	}

	/**
	 * Register all blocks.
	 */
	private function register_blocks() {
		$agent_copy_button_block = new Blocks\AgentCopyButton();
		$agent_copy_button_block->register();
	}

	/**
	 * Enqueue plugin assets.
	 */
	public function enqueue_assets() {
		// Only enqueue on agent mode views for property posts.
		if ( \is_singular( 'property' ) && $this->is_agent_view() ) {
			wp_enqueue_style(
				'pbcr-agent-mode-style',
				PBCR_AGENT_MODE_PLUGIN_URL . 'includes/css/agent-style.css',
				array(),
				PBCR_AGENT_MODE_VERSION
			);
		}
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
