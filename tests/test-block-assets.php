<?php
/**
 * Test Block JavaScript Loading
 *
 * Quick test to verify the block assets are registered correctly
 */

// Test if we're in WordPress environment
if ( ! defined( 'ABSPATH' ) ) {
	echo "This test must be run within WordPress.\n";
	exit;
}

echo "=== Block JavaScript Loading Test ===\n\n";

// Test 1: Check if block class exists
if ( class_exists( 'PBCRAgentMode\Blocks\AgentCopyButton' ) ) {
	echo "✅ Block class exists\n";
} else {
	echo "❌ Block class not found\n";
}

// Test 2: Check if assets files exist
$plugin_path = dirname( dirname( __FILE__ ) );
$js_file = $plugin_path . '/js/agent-copy-btn.js';
$css_file = $plugin_path . '/css/agent-copy-btn.css';

if ( file_exists( $js_file ) ) {
	echo "✅ JavaScript file exists: " . $js_file . "\n";
} else {
	echo "❌ JavaScript file missing: " . $js_file . "\n";
}

if ( file_exists( $css_file ) ) {
	echo "✅ CSS file exists: " . $css_file . "\n";
} else {
	echo "❌ CSS file missing: " . $css_file . "\n";
}

// Test 3: Check if scripts are registered
if ( wp_script_is( 'pbcr-agent-copy-btn-block', 'registered' ) ) {
	echo "✅ Frontend script is registered\n";
} else {
	echo "❌ Frontend script not registered\n";
}

if ( wp_style_is( 'pbcr-agent-copy-btn-block', 'registered' ) ) {
	echo "✅ Frontend style is registered\n";
} else {
	echo "❌ Frontend style not registered\n";
}

echo "\n=== Test Complete ===\n";
