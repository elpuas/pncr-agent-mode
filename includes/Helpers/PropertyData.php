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
			'price'        => self::get_formatted_price( $property_id ),
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
}
