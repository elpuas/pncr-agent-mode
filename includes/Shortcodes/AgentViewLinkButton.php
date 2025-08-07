<?php
/**
 * Agent View Link Button Shortcode Class
 *
 * Handles the [agent_view_link_button] shortcode that displays a button for copying
 * the Agent Mode URL with role/capability restrictions.
 *
 * The shortcode renders an interactive button that:
 * - Copies the Agent Mode URL to clipboard with user feedback
 * - Requires user to be logged in and have appropriate capability
 * - Only works in property post context
 * - Assets are loaded globally via AgentModeAssets class
 *
 * Developer Filter:
 * Use 'pbcr_agent_button_capability' filter to modify required capability:
 *
 * Example:
 * add_filter( 'pbcr_agent_button_capability', function( $capability ) {
 *     return 'manage_options'; // Only administrators
 * });
 *
 * @package PBCRAgentMode
 */

namespace PBCRAgentMode\Shortcodes;

// If this file is called directly, abort.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Agent View Link Button Shortcode Class
 *
 * Displays a copy button for the Agent Mode URL, restricted to users with specific capabilities.
 */
class AgentViewLinkButton {

	/**
	 * Shortcode tag
	 */
	const SHORTCODE_TAG = 'agent_view_link_button';

	/**
	 * Required capability to see the button
	 */
	const REQUIRED_CAPABILITY = 'edit_posts';

	/**
	 * Register the shortcode.
	 */
	public function register() {
		add_shortcode( self::SHORTCODE_TAG, [ $this, 'render' ] );
	}

	/**
	 * Render the shortcode.
	 *
	 * @param array  $atts    Shortcode attributes.
	 * @param string $content Shortcode content (unused).
	 * @return string The rendered shortcode output.
	 */
	public function render( $atts = [], $content = null ) {
		// Parse attributes with defaults
		$atts = shortcode_atts( [
			'label' => __( 'Copy Agent Link', 'pbcr-agent-mode' ),
		], $atts, self::SHORTCODE_TAG );

		// Security checks
		if ( ! $this->can_render() ) {
			return '';
		}

		// Get the current post
		global $post;

		// Generate the agent view URL
		$agent_url = $this->get_agent_view_url( $post->ID );

		// Return the button HTML
		return sprintf(
			'<button class="agent-copy-btn" data-url="%s" type="button">%s</button>',
			esc_url( $agent_url ),
			esc_html( $atts['label'] )
		);
	}

	/**
	 * Check if the shortcode can be rendered.
	 *
	 * @return bool True if can render, false otherwise.
	 */
	private function can_render() {
		global $post;

		// Must be logged in
		if ( ! is_user_logged_in() ) {
			return false;
		}

		// Get required capability with filter for developer customization
		$required_capability = apply_filters( 'pbcr_agent_button_capability', self::REQUIRED_CAPABILITY );

		// Must have required capability
		if ( ! current_user_can( $required_capability ) ) {
			return false;
		}

		// Must be in property context
		if ( ! $post || 'property' !== get_post_type( $post ) ) {
			return false;
		}

		return true;
	}

	/**
	 * Generate the agent view URL for a property.
	 *
	 * @param int $property_id The property post ID.
	 * @return string The agent view URL.
	 */
	private function get_agent_view_url( $property_id ) {
		// Build the URL with agent_view query parameter
		$property_url = get_permalink( $property_id );
		$agent_url    = add_query_arg( 'agent_view', '1', $property_url );

		return $agent_url;
	}
}
