<?php
/**
 * Quick Template Debug Snippet
 *
 * Add this code to the top of your agent-template.php for instant debugging.
 * Copy and paste this code after line 20 in agent-template.php
 *
 * @package PBCRAgentMode
 */

// Add this to agent-template.php after line 20:
/*

// =============================================================================
// QUICK DEBUG SECTION - Remove this before production
// =============================================================================
if ( isset( $_GET['debug'] ) && $_GET['debug'] === '1' ) {
    echo '<div style="background: #ffffcc; padding: 15px; margin: 15px; border: 2px solid #ffcc00; font-family: monospace; white-space: pre-wrap;">';
    echo '<h3>🔍 QUICK DEBUG - Property ID: ' . get_the_ID() . '</h3>';

    // Gallery Debug
    echo "\n📸 GALLERY DEBUG:\n";
    $gallery_true = get_post_meta( get_the_ID(), 'REAL_HOMES_property_images', true );
    $gallery_false = get_post_meta( get_the_ID(), 'REAL_HOMES_property_images', false );
    echo "gallery_true type: " . gettype( $gallery_true ) . "\n";
    echo "gallery_false type: " . gettype( $gallery_false ) . "\n";
    echo "gallery_true content: ";
    var_dump( $gallery_true );
    echo "\ngallery_false content: ";
    var_dump( $gallery_false );

    // Details Debug
    echo "\n\n📋 DETAILS DEBUG:\n";
    $details_true = get_post_meta( get_the_ID(), 'REAL_HOMES_additional_details_list', true );
    $details_false = get_post_meta( get_the_ID(), 'REAL_HOMES_additional_details_list', false );
    echo "details_true type: " . gettype( $details_true ) . "\n";
    echo "details_false type: " . gettype( $details_false ) . "\n";
    echo "details_true content: ";
    var_dump( $details_true );
    echo "\ndetails_false content: ";
    var_dump( $details_false );

    // Unserialized Details
    $details = ! empty( $details_true ) ? $details_true : ( ! empty( $details_false[0] ) ? $details_false[0] : null );
    if ( $details ) {
        $details_unserialized = maybe_unserialize( $details );
        echo "\nUnserialized details type: " . gettype( $details_unserialized ) . "\n";
        echo "Unserialized details content: ";
        var_dump( $details_unserialized );
    }

    // Helper Debug
    echo "\n\n🔧 HELPER DEBUG:\n";
    if ( function_exists( 'pbcr_agent_get_property_data' ) ) {
        $property_data = pbcr_agent_get_property_data( get_the_ID() );
        echo "Helper function result type: " . gettype( $property_data ) . "\n";
        echo "Helper function result: ";
        var_dump( $property_data );
    } else {
        echo "Helper function NOT FOUND\n";
    }

    echo '</div>';
}
// =============================================================================
// END DEBUG SECTION
// =============================================================================

*/
