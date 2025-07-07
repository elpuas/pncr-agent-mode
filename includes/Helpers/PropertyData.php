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
			'ref_id'       => self::get_meta_value( $property_id, 'REAL_HOMES_property_id' ),
			'gallery_ids'  => self::get_gallery_ids( $property_id ),
			'featured_id'  => self::get_meta_value( $property_id, '_thumbnail_id' ),
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
	 * @param int $property_id The property post ID.
	 * @return array Array of attachment IDs.
	 */
	private static function get_gallery_ids( $property_id ) {
		$gallery_ids = self::get_meta_value( $property_id, 'REAL_HOMES_property_images' );

		if ( empty( $gallery_ids ) ) {
			return [];
		}

		// Handle both serialized and array formats.
		if ( is_string( $gallery_ids ) ) {
			$gallery_ids = maybe_unserialize( $gallery_ids );
		}

		if ( ! is_array( $gallery_ids ) ) {
			return [];
		}

		// Filter out invalid IDs.
		return array_filter( $gallery_ids, 'is_numeric' );
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
}
