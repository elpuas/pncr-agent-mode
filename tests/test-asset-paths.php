<?php
/**
 * Asset Path Debug Test
 *
 * Debug test to verify asset paths are correctly resolved
 */

// Prevent direct access
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

echo '<h2>Asset Path Debug Test</h2>';

// Simulate the path calculation from AgentViewLinkButton.php
$plugin_url = plugin_dir_url( dirname( dirname( __FILE__ ) ) );
$plugin_path = dirname( dirname( __FILE__ ) );

echo '<h3>Path Calculation Debug:</h3>';
echo '<p><strong>Plugin URL:</strong> <code>' . esc_html( $plugin_url ) . '</code></p>';
echo '<p><strong>Plugin Path:</strong> <code>' . esc_html( $plugin_path ) . '</code></p>';

// Test CSS file
$css_url = $plugin_url . 'includes/css/agent-copy-btn.css';
$css_path = $plugin_path . '/includes/css/agent-copy-btn.css';

echo '<h3>CSS File Debug:</h3>';
echo '<p><strong>CSS URL:</strong> <code>' . esc_html( $css_url ) . '</code></p>';
echo '<p><strong>CSS Path:</strong> <code>' . esc_html( $css_path ) . '</code></p>';
echo '<p><strong>CSS File Exists:</strong> ' . ( file_exists( $css_path ) ? '✓ Yes' : '✗ No' ) . '</p>';

// Test JS file
$js_url = $plugin_url . 'includes/js/agent-copy-btn.js';
$js_path = $plugin_path . '/js/agent-copy-btn.js';

echo '<h3>JS File Debug:</h3>';
echo '<p><strong>JS URL:</strong> <code>' . esc_html( $js_url ) . '</code></p>';
echo '<p><strong>JS Path:</strong> <code>' . esc_html( $js_path ) . '</code></p>';
echo '<p><strong>JS File Exists:</strong> ' . ( file_exists( $js_path ) ? '✓ Yes' : '✗ No' ) . '</p>';

// Test shortcode functionality
echo '<h3>Shortcode Test:</h3>';
if ( is_singular( 'property' ) ) {
	echo '<p>Property context: ✓</p>';
	echo '<div style="border: 1px solid #ccc; padding: 10px; margin: 10px 0;">';
	echo do_shortcode( '[agent_view_link_button]' );
	echo '</div>';
} else {
	echo '<p>Not in property context: ✗</p>';
	echo '<p>This test should be run on a property post page</p>';
}

// Test user permissions
echo '<h3>User Permissions:</h3>';
if ( is_user_logged_in() ) {
	echo '<p>User logged in: ✓</p>';
	if ( current_user_can( 'edit_posts' ) ) {
		echo '<p>User can edit posts: ✓</p>';
	} else {
		echo '<p>User cannot edit posts: ✗</p>';
	}
} else {
	echo '<p>User not logged in: ✗</p>';
}

echo '<h3>Expected Asset URLs:</h3>';
echo '<p>If paths are correct, these URLs should be accessible:</p>';
echo '<ul>';
echo '<li><a href="' . esc_url( $css_url ) . '" target="_blank">' . esc_html( $css_url ) . '</a></li>';
echo '<li><a href="' . esc_url( $js_url ) . '" target="_blank">' . esc_html( $js_url ) . '</a></li>';
echo '</ul>';
