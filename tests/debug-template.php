<?php
/**
 * Simple Agent Template Debugger
 *
 * Add this code to your agent template for quick debugging.
 *
 * Usage: Add <?php include 'tests/debug-template.php'; ?> to your template
 *
 * @package PBCRAgentMode
 */

// If this file is called directly, abort.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Run debug tests and output results.
 *
 * @param int $property_id Property ID to test.
 */
function pbcr_debug_template_data( $property_id = null ) {
	if ( ! $property_id ) {
		$property_id = get_the_ID();
	}

	if ( ! $property_id ) {
		echo '<div style="background: #ffcccc; padding: 10px; margin: 10px; border: 1px solid #ff0000;">';
		echo '<strong>❌ ERROR:</strong> No property ID available';
		echo '</div>';
		return;
	}

	echo '<div style="background: #f0f8ff; padding: 15px; margin: 15px; border: 1px solid #0066cc; font-family: monospace;">';
	echo '<h3>🔍 Agent Template Debug Results</h3>';
	echo '<p><strong>Property ID:</strong> ' . esc_html( $property_id ) . '</p>';
	echo '<p><strong>Post Type:</strong> ' . esc_html( get_post_type( $property_id ) ) . '</p>';
	echo '<p><strong>Post Status:</strong> ' . esc_html( get_post_status( $property_id ) ) . '</p>';

	// Test 1: Gallery Data
	echo '<h4>📸 Gallery Data Tests:</h4>';
	$gallery_true = get_post_meta( $property_id, 'REAL_HOMES_property_images', true );
	$gallery_false = get_post_meta( $property_id, 'REAL_HOMES_property_images', false );

	echo '<ul>';
	echo '<li><strong>get_post_meta(true):</strong> ' . gettype( $gallery_true ) . ' (' . ( is_array( $gallery_true ) ? count( $gallery_true ) . ' items' : 'not array' ) . ')</li>';
	echo '<li><strong>get_post_meta(false):</strong> ' . gettype( $gallery_false ) . ' (' . ( is_array( $gallery_false ) ? count( $gallery_false ) . ' items' : 'not array' ) . ')</li>';

	$gallery_ids = is_array( $gallery_true ) ? $gallery_true : $gallery_false;
	echo '<li><strong>Template Logic Result:</strong> ' . gettype( $gallery_ids ) . ' (' . ( is_array( $gallery_ids ) ? count( $gallery_ids ) . ' items' : 'not array' ) . ')</li>';

	if ( is_array( $gallery_ids ) && ! empty( $gallery_ids ) ) {
		echo '<li><strong>First 3 IDs:</strong> ' . esc_html( implode( ', ', array_slice( $gallery_ids, 0, 3 ) ) ) . '</li>';
		echo '<li><strong>✅ Gallery Test:</strong> PASS - Found ' . count( $gallery_ids ) . ' images</li>';
	} else {
		echo '<li><strong>❌ Gallery Test:</strong> FAIL - No valid gallery data</li>';
	}
	echo '</ul>';

	// Test 2: Additional Details Data
	echo '<h4>📋 Additional Details Tests:</h4>';
	$details_true = get_post_meta( $property_id, 'REAL_HOMES_additional_details_list', true );
	$details_false = get_post_meta( $property_id, 'REAL_HOMES_additional_details_list', false );

	echo '<ul>';
	echo '<li><strong>get_post_meta(true):</strong> ' . gettype( $details_true ) . ' (' . ( is_string( $details_true ) ? strlen( $details_true ) . ' chars' : 'not string' ) . ')</li>';
	echo '<li><strong>get_post_meta(false):</strong> ' . gettype( $details_false ) . ' (' . ( is_array( $details_false ) ? count( $details_false ) . ' items' : 'not array' ) . ')</li>';

	$details = ! empty( $details_true ) ? $details_true : ( ! empty( $details_false[0] ) ? $details_false[0] : null );
	$details_unserialized = maybe_unserialize( $details );

	echo '<li><strong>Raw Data Type:</strong> ' . gettype( $details ) . '</li>';
	echo '<li><strong>Unserialized Type:</strong> ' . gettype( $details_unserialized ) . '</li>';

	if ( is_array( $details_unserialized ) && ! empty( $details_unserialized ) ) {
		echo '<li><strong>Details Count:</strong> ' . count( $details_unserialized ) . '</li>';
		if ( isset( $details_unserialized[0] ) && is_array( $details_unserialized[0] ) ) {
			echo '<li><strong>First Item:</strong> ' . esc_html( $details_unserialized[0][0] ?? 'No key' ) . ' = ' . esc_html( $details_unserialized[0][1] ?? 'No value' ) . '</li>';
		}
		echo '<li><strong>✅ Details Test:</strong> PASS - Found ' . count( $details_unserialized ) . ' detail items</li>';
	} else {
		echo '<li><strong>❌ Details Test:</strong> FAIL - No valid details data</li>';
	}
	echo '</ul>';

	// Test 3: PropertyData Helper
	echo '<h4>🔧 PropertyData Helper Tests:</h4>';
	echo '<ul>';

	if ( function_exists( 'pbcr_agent_get_property_data' ) ) {
		$property_data = pbcr_agent_get_property_data( $property_id );
		echo '<li><strong>Helper Function:</strong> EXISTS</li>';
		echo '<li><strong>Returns:</strong> ' . gettype( $property_data ) . '</li>';

		if ( is_array( $property_data ) ) {
			echo '<li><strong>Keys Count:</strong> ' . count( $property_data ) . '</li>';
			echo '<li><strong>Has Price:</strong> ' . ( isset( $property_data['price'] ) ? '✅ YES (' . esc_html( $property_data['price'] ) . ')' : '❌ NO' ) . '</li>';
			echo '<li><strong>Has Gallery:</strong> ' . ( isset( $property_data['gallery_ids'] ) && is_array( $property_data['gallery_ids'] ) ? '✅ YES (' . count( $property_data['gallery_ids'] ) . ' items)' : '❌ NO' ) . '</li>';
			echo '<li><strong>Has Extras:</strong> ' . ( isset( $property_data['extras_raw'] ) && is_array( $property_data['extras_raw'] ) ? '✅ YES (' . count( $property_data['extras_raw'] ) . ' items)' : '❌ NO' ) . '</li>';
		}
	} else {
		echo '<li><strong>❌ Helper Function:</strong> NOT FOUND</li>';
	}

	if ( class_exists( 'PBCRAgentMode\\Helpers\\PropertyData' ) ) {
		echo '<li><strong>Helper Class:</strong> EXISTS</li>';
	} else {
		echo '<li><strong>❌ Helper Class:</strong> NOT FOUND</li>';
	}
	echo '</ul>';

	// Test 4: Key Meta Fields
	echo '<h4>🗂️ Key Meta Fields Tests:</h4>';
	$key_fields = [
		'REAL_HOMES_property_price'    => 'Price',
		'REAL_HOMES_property_images'   => 'Gallery',
		'REAL_HOMES_additional_details_list' => 'Details',
		'REAL_HOMES_property_bedrooms' => 'Bedrooms',
		'REAL_HOMES_property_bathrooms' => 'Bathrooms',
		'REAL_HOMES_property_address'  => 'Address',
		'REAL_HOMES_property_id'       => 'Property ID',
	];

	echo '<ul>';
	foreach ( $key_fields as $field => $label ) {
		$value = get_post_meta( $property_id, $field, true );
		$exists = metadata_exists( 'post', $property_id, $field );
		$status = $exists ? ( ! empty( $value ) ? '✅' : '⚠️' ) : '❌';
		echo '<li><strong>' . $status . ' ' . esc_html( $label ) . ':</strong> ' . ( $exists ? gettype( $value ) . ( is_array( $value ) ? ' (' . count( $value ) . ' items)' : '' ) : 'MISSING' ) . '</li>';
	}
	echo '</ul>';

	// Test 5: Template Rendering
	echo '<h4>🎨 Template Rendering Tests:</h4>';
	echo '<ul>';

	// Check if gallery section would render
	$would_render_gallery = ! empty( $gallery_ids ) && is_array( $gallery_ids );
	echo '<li><strong>Gallery Section:</strong> ' . ( $would_render_gallery ? '✅ WOULD RENDER (' . count( $gallery_ids ) . ' images)' : '❌ WOULD NOT RENDER' ) . '</li>';

	// Check if details section would render
	$would_render_details = is_array( $details_unserialized ) && ! empty( $details_unserialized );
	echo '<li><strong>Details Section:</strong> ' . ( $would_render_details ? '✅ WOULD RENDER (' . count( $details_unserialized ) . ' items)' : '❌ WOULD NOT RENDER' ) . '</li>';

	echo '</ul>';

	echo '</div>';
}

// Auto-run if debug parameter is present
if ( isset( $_GET['debug_template'] ) && $_GET['debug_template'] === '1' ) {
	pbcr_debug_template_data();
}
