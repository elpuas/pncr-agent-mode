<?php
/**
 * Agent Mode Assets Class
 *
 * Handles the enqueuing of CSS and JavaScript assets for Agent Mode functionality.
 * Ensures scripts are loaded globally when appropriate rather than conditionally
 * from individual shortcodes or blocks.
 *
 * @package PBCRAgentMode
 */

namespace PBCRAgentMode\Assets;

// If this file is called directly, abort.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Agent Mode Assets Class
 *
 * Centralized asset management for Agent Mode functionality.
 */
class AgentModeAssets {

	/**
	 * Register asset management hooks.
	 */
	public function register() {
		add_action( 'wp_enqueue_scripts', [ $this, 'enqueue_scripts' ] );
	}

	/**
	 * Enqueue Agent Mode scripts and styles.
	 */
	public function enqueue_scripts() {
		// Only enqueue on single property pages where Agent Mode is relevant
		if ( $this->should_enqueue_assets() ) {
			$this->enqueue_agent_copy_assets();
		}
	}

	/**
	 * Check if assets should be enqueued.
	 *
	 * @return bool True if assets should be enqueued, false otherwise.
	 */
	private function should_enqueue_assets() {
		// Only on single property pages
		if ( ! is_singular( 'property' ) ) {
			return false;
		}

		// Check if Agent Mode is active (either via query parameter or shortcode injection)
		return $this->is_agent_mode_active();
	}

	/**
	 * Check if Agent Mode is active on the current page.
	 *
	 * @return bool True if Agent Mode is active, false otherwise.
	 */
	private function is_agent_mode_active() {
		// Check for agent_view query parameter
		if ( isset( $_GET['agent_view'] ) && $_GET['agent_view'] === '1' ) {
			return true;
		}

		// Always load on property pages since Task 22 injects the button automatically
		// and users with appropriate capabilities should have access to the functionality
		return true;
	}

	/**
	 * Enqueue the agent copy button assets (CSS and JavaScript).
	 */
	private function enqueue_agent_copy_assets() {
		// Build asset URLs using plugin constants
		$plugin_url = PBCR_AGENT_MODE_PLUGIN_URL;
		$plugin_path = PBCR_AGENT_MODE_PLUGIN_PATH;

		// Enqueue CSS
		$css_path = 'includes/css/agent-copy-btn.css';
		wp_enqueue_style(
			'pbcr-agent-copy-btn-style',
			$plugin_url . $css_path,
			[],
			filemtime( $plugin_path . $css_path ),
			'all'
		);

		// Enqueue JavaScript
		$script_path = 'includes/js/agent-copy-btn.js';
		wp_enqueue_script(
			'pbcr-agent-copy-btn',
			$plugin_url . $script_path,
			[ 'wp-i18n' ],
			filemtime( $plugin_path . $script_path ),
			true
		);

		// Set up translations for JavaScript
		wp_set_script_translations( 'pbcr-agent-copy-btn', 'pbcr-agent-mode' );
	}
}
