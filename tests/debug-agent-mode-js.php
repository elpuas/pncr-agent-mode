<?php
/**
 * Debug Agent Mode JavaScript Loading
 *
 * Run this script to debug why the Swiper JavaScript isn't loading
 */

echo "🔍 PBCR Agent Mode JavaScript Debug\n";
echo "===================================\n\n";

// Check if we're in a WordPress environment
if ( ! defined( 'ABSPATH' ) ) {
	echo "❌ Not in WordPress environment. Please run this from WordPress admin or a property page.\n";
	exit;
}

// Simulate being on a property page with agent mode
$debug_info = array();

// Check if we're on a property page
$debug_info['is_singular_property'] = is_singular( 'property' );
$debug_info['current_post_type'] = get_post_type();

// Check agent view parameters
$debug_info['query_var_agent_view'] = get_query_var( 'agent_view' );
$debug_info['get_param_agent_view'] = isset( $_GET['agent_view'] ) ? $_GET['agent_view'] : 'not set';

// Check if files exist
$js_file = PBCR_AGENT_MODE_PLUGIN_URL . 'includes/js/agent-mode.js';
$js_file_path = __DIR__ . '/../includes/js/agent-mode.js';
$debug_info['js_file_exists'] = file_exists( $js_file_path );
$debug_info['js_file_url'] = $js_file;

// Check constants
$debug_info['plugin_url'] = defined( 'PBCR_AGENT_MODE_PLUGIN_URL' ) ? PBCR_AGENT_MODE_PLUGIN_URL : 'NOT DEFINED';
$debug_info['plugin_version'] = defined( 'PBCR_AGENT_MODE_VERSION' ) ? PBCR_AGENT_MODE_VERSION : 'NOT DEFINED';

echo "📋 DEBUG INFORMATION:\n";
echo "=====================\n";
foreach ( $debug_info as $key => $value ) {
	$status = is_bool( $value ) ? ( $value ? '✅ TRUE' : '❌ FALSE' ) : $value;
	echo sprintf( "%-25s: %s\n", $key, $status );
}

echo "\n";

// Test if agent mode would be detected
$loader = new PBCRAgentMode\Loader();
$reflection = new ReflectionClass( $loader );
$method = $reflection->getMethod( 'is_agent_view' );
$method->setAccessible( true );
$is_agent_view = $method->invoke( $loader );

echo "🎯 AGENT MODE DETECTION:\n";
echo "========================\n";
echo "is_agent_view() result: " . ( $is_agent_view ? '✅ TRUE' : '❌ FALSE' ) . "\n";

if ( ! $is_agent_view ) {
	echo "\n⚠️  SOLUTION: Add ?agent_view=1 to your property URL\n";
	echo "Example: http://yoursite.com/property/sample-property/?agent_view=1\n\n";
}

// Check if we can find any property to test with
$properties = get_posts( array(
	'post_type' => 'property',
	'posts_per_page' => 1,
	'post_status' => 'publish'
) );

if ( ! empty( $properties ) ) {
	$sample_property = $properties[0];
	$agent_url = get_permalink( $sample_property->ID ) . '?agent_view=1';
	echo "🔗 TEST URL:\n";
	echo "===========\n";
	echo "Sample Agent Mode URL: " . $agent_url . "\n";
	echo "Property Title: " . $sample_property->post_title . "\n\n";
}

echo "💡 DEBUGGING STEPS:\n";
echo "===================\n";
echo "1. Visit a property page with ?agent_view=1 parameter\n";
echo "2. Open browser DevTools (F12)\n";
echo "3. Check Console tab for messages\n";
echo "4. Check Network tab to see if assets load\n";
echo "5. Look for 'PBCR Agent Mode Swiper gallery initialized successfully'\n\n";

echo "🔧 COMMON ISSUES:\n";
echo "=================\n";
echo "• Missing ?agent_view=1 parameter\n";
echo "• Not on a 'property' post type page\n";
echo "• JavaScript errors preventing execution\n";
echo "• Swiper library not loading from CDN\n";
echo "• .swiper-main or .swiper-thumbs elements not found\n\n";

echo "Debug complete.\n";
