<?php
/**
 * Agent View Link Shortcode Class
 *
 * Handles the [agent_view_link] shortcode that outputs a URL to the property's Agent Mode view.
 *
 * @package PBCRAgentMode
 */

namespace PBCRAgentMode\Shortcodes;

// If this file is called directly, abort.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Agent View Link Shortcode Class
 *
 * Handles the [agent_view_link] shortcode that outputs a URL to the property's Agent Mode view.
 */
class AgentViewLink {

	/**
	 * Shortcode tag
	 */
	const SHORTCODE_TAG = 'agent_view_link';

	/**
	 * Register the shortcode.
	 */
	public function register() {
		add_shortcode( self::SHORTCODE_TAG, array( $this, 'render' ) );
	}

	/**
	 * Render the shortcode.
	 *
	 * @param array  $atts    Shortcode attributes (unused).
	 * @param string $content Shortcode content (unused).
	 * @return string The rendered shortcode output.
	 */
	public function render( $atts = array(), $content = null ) {
		// Unused parameters are kept for WordPress shortcode API compatibility.
		unset( $atts, $content );

		// Get the current post.
		global $post;

		// Check if we're in a property context.
		if ( ! $post || 'property' !== get_post_type( $post ) ) {
			return '';
		}

		// Generate the agent view URL.
		$agent_url = $this->get_agent_view_url( $post->ID );

		// Return the full URL.
		return esc_url( $agent_url );
	}

	/**
	 * Generate the agent view URL for a property.
	 *
	 * @param int $property_id The property post ID.
	 * @return string The agent view URL.
	 */
	private function get_agent_view_url( $property_id ) {
		// Build the URL with agent_view query parameter.
		$property_url = get_permalink( $property_id );
		$agent_url    = add_query_arg( 'agent_view', '1', $property_url );

		return $agent_url;
	}
}
