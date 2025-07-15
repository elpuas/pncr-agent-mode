<?php
/**
 * Agent Copy Button Block Class
 *
 * Handles registration and functionality for the Agent Copy Link Button Gutenberg block.
 *
 * @package PBCRAgentMode
 */

namespace PBCRAgentMode\Blocks;

// If this file is called directly, abort.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Agent Copy Button Block Class
 *
 * Registers and manages the Agent Copy Link Button Gutenberg block.
 */
class AgentCopyButton {

	/**
	 * Block name
	 */
	const BLOCK_NAME = 'pbcr-agent-mode/agent-copy-link-button';

	/**
	 * Register the block.
	 */
	public function register() {
		add_action( 'init', [ $this, 'register_block_type' ] );
		add_action( 'enqueue_block_editor_assets', [ $this, 'enqueue_block_editor_assets' ] );
	}

	/**
	 * Register the block type with WordPress.
	 */
	public function register_block_type() {
		// Only register if Gutenberg is available
		if ( ! function_exists( 'register_block_type' ) ) {
			return;
		}

		register_block_type( self::BLOCK_NAME, [
			'render_callback' => [ $this, 'render_block' ],
			'attributes'      => [
				'label' => [
					'type'    => 'string',
					'default' => __( 'Copy Agent Link', 'pbcr-agent-mode' ),
				],
			],
		] );
	}

	/**
	 * Render the block on the frontend.
	 *
	 * @param array $attributes Block attributes.
	 * @return string Block output.
	 */
	public function render_block( $attributes ) {
		$label = isset( $attributes['label'] ) ? $attributes['label'] : __( 'Copy Agent Link', 'pbcr-agent-mode' );

		// Use the shortcode to render the button
		return do_shortcode( sprintf( '[agent_view_link_button label="%s"]', esc_attr( $label ) ) );
	}

	/**
	 * Enqueue assets for the block editor.
	 */
	public function enqueue_block_editor_assets() {
		$plugin_url = plugin_dir_url( dirname( dirname( __FILE__ ) ) );
		$plugin_path = dirname( dirname( __FILE__ ) );

		wp_enqueue_script(
			'pbcr-agent-copy-button-block',
			$plugin_url . 'includes/blocks/agent-copy-button.js',
			[ 'wp-blocks', 'wp-element', 'wp-block-editor', 'wp-i18n' ],
			filemtime( $plugin_path . '/blocks/agent-copy-button.js' ),
			true
		);

		// Set up translations for the block
		wp_set_script_translations( 'pbcr-agent-copy-button-block', 'pbcr-agent-mode' );
	}
}
