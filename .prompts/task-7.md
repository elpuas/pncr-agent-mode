Fix gallery image loading from `REAL_HOMES_property_images` meta key

### Context

The current implementation of the Agent Mode template attempts to retrieve property gallery image IDs using:

```php
get_post_meta( $post_id, 'REAL_HOMES_property_images', true );

This approach only returns the first image ID because the meta value is either:
 • An array nested within another array
 • Or a serialized structure depending on how the theme stores data

Objective:
 • Investigate the exact structure of the REAL_HOMES_property_images meta value using get_post_meta( $post_id, 'REAL_HOMES_property_images', false )
 • Ensure that the gallery returns all image IDs (not just one)
 • Render the full gallery with all image URLs inside the agent preview template

Requirements:
 • Use proper checks (is_array, isset) to extract the full array of attachment IDs
 • Convert attachment IDs to URLs using wp_get_attachment_image_url( $id, 'large' ) or wp_get_attachment_image( $id, 'large' )
 • Render the gallery using vanilla HTML (preferably <ul><li><img /></li></ul> or a figure)
 • Add a debug log entry if the gallery is empty
 • Keep all logic encapsulated and document with comments
 • Write a unit test if possible for the helper method

Example extraction logic:

$gallery_meta = get_post_meta( get_the_ID(), 'REAL_HOMES_property_images', false );
$gallery_ids = [];

if ( isset( $gallery_meta[0] ) && is_array( $gallery_meta[0] ) ) {
 $gallery_ids = $gallery_meta[0];
} elseif ( is_array( $gallery_meta ) ) {
 $gallery_ids = array_map( 'intval', $gallery_meta );
}

Log File:
 • Log the result in /context/logs/2025-07-11-gallery-loading.md with:
 • Extracted data type
 • Number of images found
 • Any fallback handling

Tags:

meta extraction, gallery, wp_get_attachment_image, debug, agent mode
