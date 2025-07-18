<?php
/**
 * Unit Test for Gallery Image Loading (Task-7)
 *
 * Tests the improved gallery loading functionality that properly handles
 * REAL_HOMES_property_images meta structure to return all image IDs.
 *
 * @package PBCRAgentMode
 */

use PHPUnit\Framework\TestCase;
use Brain\Monkey;
use Brain\Monkey\Functions;
use PBCRAgentMode\Helpers\PropertyData;

/**
 * Test class for PropertyData gallery methods.
 */
class GalleryLoadingTest extends TestCase {

	/**
	 * Set up before each test.
	 */
	protected function setUp(): void {
		parent::setUp();
		Monkey\setUp();
	}

	/**
	 * Tear down after each test.
	 */
	protected function tearDown(): void {
		Monkey\tearDown();
		parent::tearDown();
	}

	/**
	 * Test gallery extraction with nested array structure.
	 *
	 * This tests the most common RealHomes format where gallery IDs
	 * are stored as array within array.
	 */
	public function test_gallery_extraction_nested_array() {
		$property_id = 123;
		$gallery_ids = [ '101', '102', '103', '104' ];

		// Mock get_post_meta with nested array structure
		Functions\when( 'get_post_meta' )
			->justReturn( [ $gallery_ids ] )
			->whenReceived( $property_id, 'REAL_HOMES_property_images', false );

		Functions\when( 'error_log' )->justReturn( true );

		$property_data = PropertyData::get_property_data( $property_id );

		$this->assertIsArray( $property_data['gallery_ids'] );
		$this->assertCount( 4, $property_data['gallery_ids'] );
		$this->assertEquals( [ 101, 102, 103, 104 ], $property_data['gallery_ids'] );
	}

	/**
	 * Test gallery extraction with direct array structure.
	 *
	 * This tests when gallery IDs are stored as a direct array.
	 */
	public function test_gallery_extraction_direct_array() {
		$property_id = 123;
		$gallery_ids = [ '201', '202', '203' ];

		// Mock get_post_meta with direct array structure
		Functions\when( 'get_post_meta' )
			->justReturn( $gallery_ids )
			->whenReceived( $property_id, 'REAL_HOMES_property_images', false );

		Functions\when( 'error_log' )->justReturn( true );

		$property_data = PropertyData::get_property_data( $property_id );

		$this->assertIsArray( $property_data['gallery_ids'] );
		$this->assertCount( 3, $property_data['gallery_ids'] );
		$this->assertEquals( [ 201, 202, 203 ], $property_data['gallery_ids'] );
	}

	/**
	 * Test gallery extraction with serialized string fallback.
	 *
	 * This tests the fallback when the false approach returns empty
	 * but the true approach has serialized data.
	 */
	public function test_gallery_extraction_serialized_fallback() {
		$property_id = 123;
		$gallery_ids = [ 301, 302, 303 ];
		$serialized_data = serialize( $gallery_ids );

		// Mock get_post_meta for false call (empty)
		Functions\when( 'get_post_meta' )
			->justReturn( [] )
			->whenReceived( $property_id, 'REAL_HOMES_property_images', false );

		// Mock get_post_meta for true call (serialized data)
		Functions\when( 'get_post_meta' )
			->justReturn( $serialized_data )
			->whenReceived( $property_id, 'REAL_HOMES_property_images', true );

		Functions\when( 'maybe_unserialize' )
			->justReturn( $gallery_ids )
			->whenReceived( $serialized_data );

		Functions\when( 'error_log' )->justReturn( true );

		$property_data = PropertyData::get_property_data( $property_id );

		$this->assertIsArray( $property_data['gallery_ids'] );
		$this->assertCount( 3, $property_data['gallery_ids'] );
		$this->assertEquals( [ 301, 302, 303 ], $property_data['gallery_ids'] );
	}

	/**
	 * Test gallery extraction with empty meta data.
	 *
	 * This tests proper handling when no gallery data exists
	 * and ensures debug logging is triggered.
	 */
	public function test_gallery_extraction_empty_meta() {
		$property_id = 123;

		// Mock get_post_meta to return empty
		Functions\when( 'get_post_meta' )
			->justReturn( [] )
			->whenReceived( $property_id, 'REAL_HOMES_property_images', false );

		// Expect error_log to be called for empty gallery
		Functions\expect( 'error_log' )
			->once()
			->with( "PBCR Agent Mode: Empty gallery meta for property ID {$property_id}" );

		$property_data = PropertyData::get_property_data( $property_id );

		$this->assertIsArray( $property_data['gallery_ids'] );
		$this->assertEmpty( $property_data['gallery_ids'] );
	}

	/**
	 * Test gallery extraction with invalid data types.
	 *
	 * This tests proper handling when meta data is not in expected format
	 * and ensures debug logging for invalid types.
	 */
	public function test_gallery_extraction_invalid_data() {
		$property_id = 123;

		// Mock get_post_meta to return string instead of array
		Functions\when( 'get_post_meta' )
			->justReturn( 'invalid_string_data' )
			->whenReceived( $property_id, 'REAL_HOMES_property_images', false );

		Functions\when( 'get_post_meta' )
			->justReturn( 'invalid_string_data' )
			->whenReceived( $property_id, 'REAL_HOMES_property_images', true );

		Functions\when( 'maybe_unserialize' )
			->justReturn( 'invalid_string_data' )
			->whenReceived( 'invalid_string_data' );

		// Expect error_log to be called for invalid type
		Functions\expect( 'error_log' )
			->once()
			->with( "PBCR Agent Mode: Gallery meta is not an array for property ID {$property_id}, type: string" );

		$property_data = PropertyData::get_property_data( $property_id );

		$this->assertIsArray( $property_data['gallery_ids'] );
		$this->assertEmpty( $property_data['gallery_ids'] );
	}

	/**
	 * Test gallery extraction with mixed valid and invalid IDs.
	 *
	 * This tests filtering of non-numeric and zero/negative IDs.
	 */
	public function test_gallery_extraction_mixed_ids() {
		$property_id = 123;
		$mixed_gallery_ids = [ '101', 'invalid', '0', '102', '-5', '103', '' ];

		// Mock get_post_meta with mixed array
		Functions\when( 'get_post_meta' )
			->justReturn( [ $mixed_gallery_ids ] )
			->whenReceived( $property_id, 'REAL_HOMES_property_images', false );

		Functions\when( 'error_log' )->justReturn( true );

		$property_data = PropertyData::get_property_data( $property_id );

		$this->assertIsArray( $property_data['gallery_ids'] );
		$this->assertCount( 3, $property_data['gallery_ids'] );
		$this->assertEquals( [ 101, 102, 103 ], $property_data['gallery_ids'] );
	}

	/**
	 * Test gallery extraction logs correct count.
	 *
	 * This tests that the success logging includes correct count.
	 */
	public function test_gallery_extraction_logs_count() {
		$property_id = 123;
		$gallery_ids = [ '401', '402', '403', '404', '405' ];

		// Mock get_post_meta with nested array structure
		Functions\when( 'get_post_meta' )
			->justReturn( [ $gallery_ids ] )
			->whenReceived( $property_id, 'REAL_HOMES_property_images', false );

		// Expect error_log to be called with correct count
		Functions\expect( 'error_log' )
			->once()
			->with( "PBCR Agent Mode: Found 5 gallery images for property ID {$property_id}" );

		$property_data = PropertyData::get_property_data( $property_id );

		$this->assertCount( 5, $property_data['gallery_ids'] );
	}

	/**
	 * Test gallery extraction handles large arrays.
	 *
	 * This tests performance with larger gallery sets.
	 */
	public function test_gallery_extraction_large_array() {
		$property_id = 123;
		$large_gallery_ids = range( 1001, 1050 ); // 50 images
		$large_gallery_strings = array_map( 'strval', $large_gallery_ids );

		// Mock get_post_meta with large nested array
		Functions\when( 'get_post_meta' )
			->justReturn( [ $large_gallery_strings ] )
			->whenReceived( $property_id, 'REAL_HOMES_property_images', false );

		Functions\when( 'error_log' )->justReturn( true );

		$property_data = PropertyData::get_property_data( $property_id );

		$this->assertIsArray( $property_data['gallery_ids'] );
		$this->assertCount( 50, $property_data['gallery_ids'] );
		$this->assertEquals( $large_gallery_ids, $property_data['gallery_ids'] );
	}
}
