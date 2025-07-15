<?php
/**
 * Quick test for Agent Copy Button shortcode functionality
 *
 * This file can be included in a property post to test the shortcode functionality.
 */

// Prevent direct access
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

echo '<h2>Agent Copy Button Shortcode Test</h2>';

// Test basic shortcode
echo '<h3>Basic shortcode test:</h3>';
echo do_shortcode( '[agent_view_link_button]' );

// Test with custom label
echo '<h3>Custom label test:</h3>';
echo do_shortcode( '[agent_view_link_button label="Custom Copy Button"]' );

// Test user capability check
echo '<h3>User capability test:</h3>';
if ( is_user_logged_in() ) {
	echo '<p>User is logged in: ✓</p>';
	if ( current_user_can( 'edit_posts' ) ) {
		echo '<p>User can edit posts: ✓</p>';
	} else {
		echo '<p>User cannot edit posts: ✗</p>';
	}
} else {
	echo '<p>User is not logged in: ✗</p>';
}

// Test post type check
global $post;
if ( $post && 'property' === get_post_type( $post ) ) {
	echo '<p>Current post is property type: ✓</p>';
} else {
	echo '<p>Current post is not property type: ✗</p>';
}

// Test shortcode detection
if ( isset( $post ) && has_shortcode( $post->post_content, 'agent_view_link_button' ) ) {
	echo '<p>Shortcode detected in content: ✓</p>';
} else {
	echo '<p>Shortcode not detected in content: ✗</p>';
}
