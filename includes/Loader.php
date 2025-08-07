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
		// Register assets.
		$this->register_assets();

		// Register shortcodes.
		$this->register_shortcodes();

		// Register blocks.
		$this->register_blocks();
	}

	/**
	 * Register asset management.
	 */
	private function register_assets() {
		$agent_mode_assets = new Assets\AgentModeAssets();
		$agent_mode_assets->register();
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
}
