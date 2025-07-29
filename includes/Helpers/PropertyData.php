<?php
/**
 * Property Data Helper
 *
 * Handles extraction and formatting of property data from RealHomes meta fields.
 *
 * @package PBCRAgentMode
 */

namespace PBCRAgentMode\Helpers;

// If this file is called directly, abort.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * PropertyData Helper Class
 *
 * Provides static methods to extract and format property data from RealHomes meta fields.
 */
class PropertyData {

	/**
	 * Get comprehensive property data from RealHomes meta fields.
	 *
	 * @param int $property_id The property post ID. If null, uses current post.
	 * @return array Associative array of formatted property data.
	 */
	public static function get_property_data( $property_id = null ) {
		if ( null === $property_id ) {
			$property_id = get_the_ID();
		}

		if ( ! $property_id ) {
			return [];
		}

		return [
			// Existing fields (backward compatibility)
			'price'        => self::get_formatted_price( $property_id ),
			'old_price'    => self::get_formatted_old_price( $property_id ),
			'size'         => self::get_formatted_size( $property_id ),
			'size_unit'    => self::get_size_unit( $property_id ),
			'bedrooms'     => self::get_meta_value( $property_id, 'REAL_HOMES_property_bedrooms' ),
			'bathrooms'    => self::get_meta_value( $property_id, 'REAL_HOMES_property_bathrooms' ),
			'garage'       => self::get_meta_value( $property_id, 'REAL_HOMES_property_garage' ),
			'address'      => self::get_meta_value( $property_id, 'REAL_HOMES_property_address' ),
			'location'     => self::get_meta_value( $property_id, 'REAL_HOMES_property_location' ),
			'ref_id'       => self::get_meta_value( $property_id, 'REAL_HOMES_property_id' ),
			'gallery_ids'  => self::get_gallery_ids( $property_id ),
			'featured_id'  => self::get_meta_value( $property_id, '_thumbnail_id' ),
			'is_featured'  => self::get_meta_value( $property_id, 'REAL_HOMES_featured' ),
			'in_slider'    => self::get_meta_value( $property_id, 'REAL_HOMES_add_in_slider' ),
			'map_data'     => self::get_map_data( $property_id ),
			'video_data'   => self::get_video_data( $property_id ),
			'agents'       => self::get_assigned_agents( $property_id ),
			'extras_raw'   => self::get_additional_details( $property_id ),

			// New fields (Task-14 enhancements)
			'status'           => self::get_property_status( $property_id ),
			'type'             => self::get_property_type( $property_id ),
			'breadcrumbs'      => self::get_location_breadcrumbs( $property_id ),
			'land_size'        => self::get_formatted_land_size( $property_id ),
			'land_unit'        => self::get_land_unit( $property_id ),
			'currency_prefix'  => self::get_currency_prefix( $property_id ),
			'currency_suffix'  => self::get_currency_suffix( $property_id ),
			'description'      => self::get_property_description( $property_id ),
			'features'         => self::get_property_features( $property_id ),
			'gallery_urls'     => self::get_gallery_urls( $property_id ),
			'featured_url'     => self::get_featured_image_url( $property_id ),
		];
	}

	/**
	 * Get and format property price.
	 *
	 * @param int $property_id The property post ID.
	 * @return string Formatted price or empty string.
	 */
	private static function get_formatted_price( $property_id ) {
		$price = self::get_meta_value( $property_id, 'REAL_HOMES_property_price' );

		if ( empty( $price ) ) {
			return '';
		}

		// Remove any non-numeric characters except decimal points.
		$numeric_price = preg_replace( '/[^\d.]/', '', $price );

		if ( is_numeric( $numeric_price ) ) {
			return number_format( (float) $numeric_price );
		}

		return $price; // Return original if not numeric.
	}

	/**
	 * Get and format old property price.
	 *
	 * @param int $property_id The property post ID.
	 * @return string Formatted old price or empty string.
	 */
	private static function get_formatted_old_price( $property_id ) {
		$old_price = self::get_meta_value( $property_id, 'REAL_HOMES_property_old_price' );

		if ( empty( $old_price ) ) {
			return '';
		}

		// Remove any non-numeric characters except decimal points.
		$numeric_price = preg_replace( '/[^\d.]/', '', $old_price );

		if ( is_numeric( $numeric_price ) ) {
			return number_format( (float) $numeric_price );
		}

		return $old_price; // Return original if not numeric.
	}

	/**
	 * Get and format property size.
	 *
	 * @param int $property_id The property post ID.
	 * @return string Formatted size or empty string.
	 */
	private static function get_formatted_size( $property_id ) {
		$size = self::get_meta_value( $property_id, 'REAL_HOMES_property_size' );

		if ( empty( $size ) ) {
			return '';
		}

		// Remove any non-numeric characters except decimal points.
		$numeric_size = preg_replace( '/[^\d.]/', '', $size );

		if ( is_numeric( $numeric_size ) ) {
			return number_format( (float) $numeric_size );
		}

		return $size; // Return original if not numeric.
	}

	/**
	 * Get property size unit.
	 *
	 * @param int $property_id The property post ID.
	 * @return string Size unit or default.
	 */
	private static function get_size_unit( $property_id ) {
		$unit = self::get_meta_value( $property_id, 'REAL_HOMES_property_size_postfix' );
		return ! empty( $unit ) ? $unit : 'Sq Ft';
	}

	/**
	 * Get property gallery image IDs.
	 *
	 * Investigates the exact structure of REAL_HOMES_property_images meta value
	 * to ensure all image IDs are returned, not just the first one.
	 *
	 * @param int $property_id The property post ID.
	 * @return array Array of attachment IDs.
	 */
	private static function get_gallery_ids( $property_id ) {
		// Get gallery meta using both approaches to handle different storage formats
		$gallery_meta = get_post_meta( $property_id, 'REAL_HOMES_property_images', false );
		$gallery_ids = [];

		// Check if meta exists
		if ( empty( $gallery_meta ) ) {
			// Log debug info if gallery is empty
			error_log( "PBCR Agent Mode: Empty gallery meta for property ID {$property_id}" );
			return [];
		}

		// Handle nested array structure (common RealHomes format)
		if ( isset( $gallery_meta[0] ) && is_array( $gallery_meta[0] ) ) {
			$gallery_ids = $gallery_meta[0];
		} elseif ( is_array( $gallery_meta ) ) {
			// Handle direct array of IDs
			$gallery_ids = array_map( 'intval', $gallery_meta );
		}

		// Handle string/serialized data as fallback
		if ( empty( $gallery_ids ) ) {
			$gallery_single = get_post_meta( $property_id, 'REAL_HOMES_property_images', true );
			if ( is_string( $gallery_single ) ) {
				$gallery_ids = maybe_unserialize( $gallery_single );
			} elseif ( is_array( $gallery_single ) ) {
				$gallery_ids = $gallery_single;
			}
		}

		// Ensure we have a valid array
		if ( ! is_array( $gallery_ids ) ) {
			error_log( "PBCR Agent Mode: Gallery meta is not an array for property ID {$property_id}, type: " . gettype( $gallery_ids ) );
			return [];
		}

		// Filter out invalid IDs and ensure they are integers
		$valid_ids = array_filter( $gallery_ids, function( $id ) {
			return is_numeric( $id ) && (int) $id > 0;
		} );

		// Convert to integers
		$valid_ids = array_map( 'intval', $valid_ids );

		// Log results for debugging
		error_log( "PBCR Agent Mode: Found " . count( $valid_ids ) . " gallery images for property ID {$property_id}" );

		return $valid_ids;
	}

	/**
	 * Get additional details list.
	 *
	 * @param int $property_id The property post ID.
	 * @return array Array of additional details or empty array.
	 */
	private static function get_additional_details( $property_id ) {
		$details = self::get_meta_value( $property_id, 'REAL_HOMES_additional_details_list' );

		if ( empty( $details ) ) {
			return [];
		}

		// Handle serialized data.
		if ( is_string( $details ) ) {
			$details = maybe_unserialize( $details );
		}

		if ( ! is_array( $details ) ) {
			return [];
		}

		return $details;
	}

	/**
	 * Get meta value with fallback.
	 *
	 * @param int    $property_id The property post ID.
	 * @param string $meta_key    The meta key to retrieve.
	 * @return mixed Meta value or empty string.
	 */
	private static function get_meta_value( $property_id, $meta_key ) {
		$value = get_post_meta( $property_id, $meta_key, true );
		return ! empty( $value ) ? $value : '';
	}

	/**
	 * Format property features (bedrooms, bathrooms, garage) for display.
	 *
	 * @param array $property_data Property data array.
	 * @return array Formatted features array.
	 */
	public static function get_formatted_features( $property_data ) {
		$features = [];

		if ( ! empty( $property_data['bedrooms'] ) ) {
			$features[] = [
				'label' => _n( 'Bedroom', 'Bedrooms', (int) $property_data['bedrooms'], 'pbcr-agent-mode' ),
				'value' => $property_data['bedrooms'],
				'icon'  => 'bed',
			];
		}

		if ( ! empty( $property_data['bathrooms'] ) ) {
			$features[] = [
				'label' => _n( 'Bathroom', 'Bathrooms', (int) $property_data['bathrooms'], 'pbcr-agent-mode' ),
				'value' => $property_data['bathrooms'],
				'icon'  => 'bath',
			];
		}

		if ( ! empty( $property_data['garage'] ) ) {
			$features[] = [
				'label' => _n( 'Garage', 'Garages', (int) $property_data['garage'], 'pbcr-agent-mode' ),
				'value' => $property_data['garage'],
				'icon'  => 'garage',
			];
		}

		return $features;
	}

	/**
	 * Get property map data.
	 *
	 * @param int $property_id The property post ID.
	 * @return array Map data array or empty array.
	 */
	private static function get_map_data( $property_id ) {
		$map_data = self::get_meta_value( $property_id, 'REAL_HOMES_property_map' );

		if ( empty( $map_data ) ) {
			return [];
		}

		// Handle serialized data.
		if ( is_string( $map_data ) ) {
			$map_data = \maybe_unserialize( $map_data );
		}

		if ( ! is_array( $map_data ) ) {
			return [];
		}

		return $map_data;
	}

	/**
	 * Get property video data.
	 *
	 * @param int $property_id The property post ID.
	 * @return array Video data array or empty array.
	 */
	private static function get_video_data( $property_id ) {
		$video_data = self::get_meta_value( $property_id, 'inspiry_video_group' );

		if ( empty( $video_data ) ) {
			return [];
		}

		// Handle serialized data.
		if ( is_string( $video_data ) ) {
			$video_data = \maybe_unserialize( $video_data );
		}

		if ( ! is_array( $video_data ) ) {
			return [];
		}

		// Extract video URL and image ID.
		$processed_video = [];
		if ( isset( $video_data[0] ) && is_array( $video_data[0] ) ) {
			$video_item = $video_data[0];
			$processed_video = [
				'url'      => isset( $video_item['inspiry_video_group_url'] ) ? $video_item['inspiry_video_group_url'] : '',
				'image_id' => isset( $video_item['inspiry_video_group_image'] ) ? $video_item['inspiry_video_group_image'] : '',
			];
		}

		return $processed_video;
	}

	/**
	 * Get assigned agents data.
	 *
	 * @param int $property_id The property post ID.
	 * @return array Array of agent data or empty array.
	 */
	private static function get_assigned_agents( $property_id ) {
		$agent_ids = self::get_meta_value( $property_id, 'REAL_HOMES_agents' );

		if ( empty( $agent_ids ) ) {
			return [];
		}

		// Handle serialized data.
		if ( is_string( $agent_ids ) ) {
			$agent_ids = \maybe_unserialize( $agent_ids );
		}

		if ( ! is_array( $agent_ids ) ) {
			return [];
		}

		$agents = [];
		foreach ( $agent_ids as $agent_id ) {
			if ( ! is_numeric( $agent_id ) ) {
				continue;
			}

			$user_data = \get_userdata( $agent_id );
			if ( $user_data ) {
				$agents[] = [
					'id'           => $agent_id,
					'name'         => $user_data->display_name,
					'email'        => $user_data->user_email,
					'avatar_url'   => \get_avatar_url( $agent_id ),
				];
			}
		}

		return $agents;
	}

	/**
	 * Get taxonomy terms for a property.
	 *
	 * @param int    $property_id The property post ID.
	 * @param string $taxonomy    The taxonomy name.
	 * @param bool   $single      Whether to return single term or array (default: true).
	 * @return string|array Single term name, array of terms, or empty string/array.
	 */
	private static function get_property_taxonomy_terms( $property_id, $taxonomy, $single = true ) {
		$terms = \wp_get_post_terms( $property_id, $taxonomy, [ 'fields' => 'names' ] );

		if ( \is_wp_error( $terms ) || empty( $terms ) ) {
			return $single ? '' : [];
		}

		return $single ? $terms[0] : $terms;
	}

	/**
	 * Get property status from taxonomy.
	 *
	 * @param int $property_id The property post ID.
	 * @return string Property status label or empty string.
	 */
	private static function get_property_status( $property_id ) {
		return self::get_property_taxonomy_terms( $property_id, 'property-status' );
	}

	/**
	 * Get property type from taxonomy.
	 *
	 * @param int $property_id The property post ID.
	 * @return string Property type label or empty string.
	 */
	private static function get_property_type( $property_id ) {
		return self::get_property_taxonomy_terms( $property_id, 'property-type' );
	}

	/**
	 * Get location breadcrumbs from taxonomies.
	 *
	 * @param int $property_id The property post ID.
	 * @return array Array of location hierarchy (city, state, etc.).
	 */
	private static function get_location_breadcrumbs( $property_id ) {
		$breadcrumbs = [];

		// Get city terms
		$cities = self::get_property_taxonomy_terms( $property_id, 'property-city', false );
		$breadcrumbs = array_merge( $breadcrumbs, $cities );

		// Get state terms
		$states = self::get_property_taxonomy_terms( $property_id, 'property-state', false );
		$breadcrumbs = array_merge( $breadcrumbs, $states );

		return $breadcrumbs;
	}

	/**
	 * Get and format property land size.
	 *
	 * @param int $property_id The property post ID.
	 * @return string Formatted land size or empty string.
	 */
	private static function get_formatted_land_size( $property_id ) {
		$land_size = self::get_meta_value( $property_id, 'REAL_HOMES_property_lot_size' );

		if ( empty( $land_size ) ) {
			return '';
		}

		// Remove any non-numeric characters except decimal points.
		$numeric_size = preg_replace( '/[^\d.]/', '', $land_size );

		if ( is_numeric( $numeric_size ) ) {
			return number_format( (float) $numeric_size );
		}

		return $land_size; // Return original if not numeric.
	}

	/**
	 * Get property land size unit.
	 *
	 * @param int $property_id The property post ID.
	 * @return string Land size unit or default.
	 */
	private static function get_land_unit( $property_id ) {
		$unit = self::get_meta_value( $property_id, 'REAL_HOMES_property_lot_size_postfix' );
		return ! empty( $unit ) ? $unit : 'm²';
	}

	/**
	 * Get currency prefix symbol.
	 *
	 * @param int $property_id The property post ID.
	 * @return string Currency prefix symbol or empty string.
	 */
	private static function get_currency_prefix( $property_id ) {
		return self::get_meta_value( $property_id, 'REAL_HOMES_property_price_prefix' );
	}

	/**
	 * Get currency suffix.
	 *
	 * @param int $property_id The property post ID.
	 * @return string Currency suffix or empty string.
	 */
	private static function get_currency_suffix( $property_id ) {
		return self::get_meta_value( $property_id, 'REAL_HOMES_property_price_postfix' );
	}

	/**
	 * Get property description with shortcodes stripped.
	 *
	 * @param int $property_id The property post ID.
	 * @return string Property description with shortcodes removed.
	 */
	private static function get_property_description( $property_id ) {
		$content = \get_post_field( 'post_content', $property_id );

		if ( empty( $content ) ) {
			return '';
		}

		// Strip shortcodes and return clean content
		return \strip_shortcodes( $content );
	}

	/**
	 * Get property features from class_list or taxonomy.
	 *
	 * @param int $property_id The property post ID.
	 * @return array Array of formatted feature labels.
	 */
	private static function get_property_features( $property_id ) {
		$features = [];

		// First try to get from class_list
		$class_list = self::get_meta_value( $property_id, 'class_list' );
		if ( ! empty( $class_list ) ) {
			// Parse property-feature-* classes
			if ( preg_match_all( '/property-feature-([a-zA-Z0-9-_]+)/', $class_list, $matches ) ) {
				foreach ( $matches[1] as $feature_slug ) {
					// Convert slug to readable label
					$features[] = ucwords( str_replace( '-', ' ', $feature_slug ) );
				}
			}
		}

		// Fallback to property-feature taxonomy if no class_list features found
		if ( empty( $features ) ) {
			$terms = \wp_get_post_terms( $property_id, 'property-feature', [ 'fields' => 'names' ] );
			if ( ! \is_wp_error( $terms ) && ! empty( $terms ) ) {
				$features = $terms;
			}
		}

		return $features;
	}

	/**
	 * Get a single attachment image URL.
	 *
	 * @param int|string $attachment_id The attachment ID.
	 * @param string     $size          Image size (default: 'full').
	 * @return string Image URL or empty string if invalid.
	 */
	private static function get_attachment_url( $attachment_id, $size = 'full' ) {
		if ( empty( $attachment_id ) || ! is_numeric( $attachment_id ) ) {
			return '';
		}

		$url = \wp_get_attachment_image_url( (int) $attachment_id, $size );
		return $url ? $url : '';
	}

	/**
	 * Convert multiple attachment IDs to URLs.
	 *
	 * @param array  $attachment_ids Array of attachment IDs.
	 * @param string $size          Image size (default: 'full').
	 * @return array Array of valid image URLs.
	 */
	private static function get_multiple_attachment_urls( $attachment_ids, $size = 'full' ) {
		if ( ! is_array( $attachment_ids ) || empty( $attachment_ids ) ) {
			return [];
		}

		$urls = [];
		foreach ( $attachment_ids as $attachment_id ) {
			$url = self::get_attachment_url( $attachment_id, $size );
			if ( ! empty( $url ) ) {
				$urls[] = $url;
			}
		}

		return $urls;
	}

	/**
	 * Get gallery image URLs from gallery IDs.
	 *
	 * @param int $property_id The property post ID.
	 * @return array Array of image URLs.
	 */
	private static function get_gallery_urls( $property_id ) {
		$gallery_ids = self::get_gallery_ids( $property_id );
		return self::get_multiple_attachment_urls( $gallery_ids );
	}

	/**
	 * Get featured image URL.
	 *
	 * @param int $property_id The property post ID.
	 * @return string Featured image URL or empty string.
	 */
	private static function get_featured_image_url( $property_id ) {
		$thumbnail_id = self::get_meta_value( $property_id, '_thumbnail_id' );
		return self::get_attachment_url( $thumbnail_id );
	}
}
