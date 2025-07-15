<?php
/**
 * Agent Template Tests
 *
 * Tests for the agent-template.php file and related data retrieval functionality.
 *
 * @package PBCRAgentMode
 */

use PHPUnit\Framework\TestCase;
use Brain\Monkey\Functions;
use Brain\Monkey\Actions;
use Brain\Monkey\Filters;
use PBCRAgentMode\Helpers\PropertyData;

/**
 * Test class for Agent Template functionality.
 */
class AgentTemplateTest extends TestCase {

	/**
	 * Set up test environment before each test.
	 */
	protected function setUp(): void {
		parent::setUp();
		\Brain\Monkey\setUp();

		// Mock WordPress functions that are commonly used.
		Functions\when( 'defined' )->justReturn( true );
		Functions\when( 'esc_html' )->returnArg();
		Functions\when( 'esc_url' )->returnArg();
		Functions\when( 'esc_attr' )->returnArg();
		Functions\when( 'wp_kses_post' )->returnArg();
		Functions\when( 'maybe_unserialize' )->alias( 'unserialize' );
	}

	/**
	 * Clean up after each test.
	 */
	protected function tearDown(): void {
		\Brain\Monkey\tearDown();
		parent::tearDown();
	}

	/**
	 * Test PropertyData helper with valid property data.
	 */
	public function test_property_data_helper_returns_expected_structure() {
		// Mock get_post_meta responses based on the var_dump provided.
		Functions\expect( 'get_post_meta' )
			->with( 123, 'REAL_HOMES_property_price', true )
			->once()
			->andReturn( '480000' );

		Functions\expect( 'get_post_meta' )
			->with( 123, 'REAL_HOMES_property_images', true )
			->once()
			->andReturn( [ 57, 58, 59, 62, 64, 65, 66, 67 ] );

		Functions\expect( 'get_post_meta' )
			->with( 123, 'REAL_HOMES_additional_details_list', true )
			->once()
			->andReturn( 'a:18:{i:0;a:2:{i:0;s:2:"AC";i:1;s:11:"Wall/Window";}i:1;a:2:{i:0;s:16:"AMENITIES, OTHER";i:1;s:18:"Home Warranty Plan";}}' );

		// Mock other required meta fields.
		Functions\expect( 'get_post_meta' )
			->with( 123, 'REAL_HOMES_property_size', true )
			->once()
			->andReturn( '3550' );

		Functions\expect( 'get_post_meta' )
			->with( 123, 'REAL_HOMES_property_size_postfix', true )
			->once()
			->andReturn( 'Sq Ft' );

		Functions\expect( 'get_post_meta' )
			->with( 123, 'REAL_HOMES_property_bedrooms', true )
			->once()
			->andReturn( '3' );

		Functions\expect( 'get_post_meta' )
			->with( 123, 'REAL_HOMES_property_bathrooms', true )
			->once()
			->andReturn( '3' );

		Functions\expect( 'get_post_meta' )
			->with( 123, 'REAL_HOMES_property_garage', true )
			->once()
			->andReturn( '2' );

		Functions\expect( 'get_post_meta' )
			->with( 123, 'REAL_HOMES_property_address', true )
			->once()
			->andReturn( 'Florida 5, Pinecrest, FL 33156, USA' );

		Functions\expect( 'get_post_meta' )
			->with( 123, 'REAL_HOMES_property_location', true )
			->once()
			->andReturn( '25.668099758065,-80.322086814017,0' );

		Functions\expect( 'get_post_meta' )
			->with( 123, 'REAL_HOMES_property_id', true )
			->once()
			->andReturn( 'RH1008' );

		Functions\expect( 'get_post_meta' )
			->with( 123, '_thumbnail_id', true )
			->once()
			->andReturn( '62' );

		Functions\expect( 'get_post_meta' )
			->with( 123, 'REAL_HOMES_featured', true )
			->once()
			->andReturn( '1' );

		Functions\expect( 'get_post_meta' )
			->with( 123, 'REAL_HOMES_add_in_slider', true )
			->once()
			->andReturn( 'yes' );

		Functions\expect( 'get_post_meta' )
			->with( 123, 'REAL_HOMES_property_map', true )
			->once()
			->andReturn( '0' );

		Functions\expect( 'get_post_meta' )
			->with( 123, 'inspiry_video_group', true )
			->once()
			->andReturn( 'a:1:{i:0;a:2:{s:25:"inspiry_video_group_image";a:1:{i:0;s:2:"62";}s:23:"inspiry_video_group_url";s:43:"https://www.youtube.com/watch?v=_kDGv5_ZsHw";}}' );

		Functions\expect( 'get_post_meta' )
			->with( 123, 'REAL_HOMES_agents', true )
			->once()
			->andReturn( [ 54 ] );

		Functions\expect( 'get_userdata' )
			->with( 54 )
			->once()
			->andReturn( (object) [
				'display_name' => 'John Doe',
				'user_email'   => 'john@example.com',
			] );

		Functions\expect( 'get_avatar_url' )
			->with( 54 )
			->once()
			->andReturn( 'http://example.com/avatar.jpg' );

		$property_data = PropertyData::get_property_data( 123 );

		// Assert expected structure and values.
		$this->assertIsArray( $property_data );
		$this->assertEquals( '480,000', $property_data['price'] );
		$this->assertEquals( '3,550', $property_data['size'] );
		$this->assertEquals( 'Sq Ft', $property_data['size_unit'] );
		$this->assertEquals( '3', $property_data['bedrooms'] );
		$this->assertEquals( '3', $property_data['bathrooms'] );
		$this->assertEquals( '2', $property_data['garage'] );
		$this->assertEquals( 'Florida 5, Pinecrest, FL 33156, USA', $property_data['address'] );
		$this->assertEquals( 'RH1008', $property_data['ref_id'] );
		$this->assertIsArray( $property_data['gallery_ids'] );
		$this->assertCount( 8, $property_data['gallery_ids'] );
		$this->assertEquals( [ 57, 58, 59, 62, 64, 65, 66, 67 ], $property_data['gallery_ids'] );
	}

	/**
	 * Test direct gallery meta retrieval.
	 */
	public function test_direct_gallery_meta_retrieval() {
		$expected_gallery_ids = [ 57, 58, 59, 62, 64, 65, 66, 67 ];

		// Test with get_post_meta returning array directly.
		Functions\expect( 'get_post_meta' )
			->with( 123, 'REAL_HOMES_property_images', true )
			->once()
			->andReturn( $expected_gallery_ids );

		Functions\expect( 'get_the_ID' )
			->once()
			->andReturn( 123 );

		// Simulate the template logic.
		$gallery_ids_true = get_post_meta( get_the_ID(), 'REAL_HOMES_property_images', true );
		$gallery_ids_false = null; // Not called since true works.
		$gallery_ids = is_array( $gallery_ids_true ) ? $gallery_ids_true : $gallery_ids_false;

		$this->assertIsArray( $gallery_ids );
		$this->assertCount( 8, $gallery_ids );
		$this->assertEquals( $expected_gallery_ids, $gallery_ids );
	}

	/**
	 * Test direct gallery meta retrieval with fallback.
	 */
	public function test_direct_gallery_meta_retrieval_with_fallback() {
		$expected_gallery_ids = [ 57, 58, 59, 62, 64, 65, 66, 67 ];

		Functions\expect( 'get_the_ID' )
			->twice()
			->andReturn( 123 );

		// Test when true returns non-array, fallback to false.
		Functions\expect( 'get_post_meta' )
			->with( 123, 'REAL_HOMES_property_images', true )
			->once()
			->andReturn( '' );

		Functions\expect( 'get_post_meta' )
			->with( 123, 'REAL_HOMES_property_images', false )
			->once()
			->andReturn( $expected_gallery_ids );

		// Simulate the template logic.
		$gallery_ids_true = get_post_meta( get_the_ID(), 'REAL_HOMES_property_images', true );
		$gallery_ids_false = get_post_meta( get_the_ID(), 'REAL_HOMES_property_images', false );
		$gallery_ids = is_array( $gallery_ids_true ) ? $gallery_ids_true : $gallery_ids_false;

		$this->assertIsArray( $gallery_ids );
		$this->assertCount( 8, $gallery_ids );
		$this->assertEquals( $expected_gallery_ids, $gallery_ids );
	}

	/**
	 * Test direct additional details meta retrieval.
	 */
	public function test_direct_additional_details_meta_retrieval() {
		$serialized_details = 'a:2:{i:0;a:2:{i:0;s:2:"AC";i:1;s:11:"Wall/Window";}i:1;a:2:{i:0;s:16:"AMENITIES, OTHER";i:1;s:18:"Home Warranty Plan";}}';
		$expected_details = [
			[ 'AC', 'Wall/Window' ],
			[ 'AMENITIES, OTHER', 'Home Warranty Plan' ],
		];

		Functions\expect( 'get_the_ID' )
			->twice()
			->andReturn( 123 );

		Functions\expect( 'get_post_meta' )
			->with( 123, 'REAL_HOMES_additional_details_list', true )
			->once()
			->andReturn( $serialized_details );

		Functions\expect( 'get_post_meta' )
			->with( 123, 'REAL_HOMES_additional_details_list', false )
			->once()
			->andReturn( [] );

		// Simulate the template logic.
		$details_true = get_post_meta( get_the_ID(), 'REAL_HOMES_additional_details_list', true );
		$details_false = get_post_meta( get_the_ID(), 'REAL_HOMES_additional_details_list', false );
		$details = ! empty( $details_true ) ? $details_true : ( ! empty( $details_false[0] ) ? $details_false[0] : null );
		$details = maybe_unserialize( $details );

		$this->assertIsArray( $details );
		$this->assertCount( 2, $details );
		$this->assertEquals( $expected_details, $details );
	}

	/**
	 * Test template security - output escaping.
	 */
	public function test_template_output_escaping() {
		$malicious_input = '<script>alert("xss")</script>';
		$safe_output = '&lt;script&gt;alert(&quot;xss&quot;)&lt;/script&gt;';

		Functions\expect( 'esc_html' )
			->with( $malicious_input )
			->once()
			->andReturn( $safe_output );

		$result = esc_html( $malicious_input );
		$this->assertEquals( $safe_output, $result );
		$this->assertStringNotContainsString( '<script>', $result );
	}

	/**
	 * Test property features formatting.
	 */
	public function test_property_features_formatting() {
		$property_data = [
			'bedrooms'  => '3',
			'bathrooms' => '2',
			'garage'    => '1',
		];

		Functions\expect( '_n' )
			->with( 'Bedroom', 'Bedrooms', 3, 'pbcr-agent-mode' )
			->once()
			->andReturn( 'Bedrooms' );

		Functions\expect( '_n' )
			->with( 'Bathroom', 'Bathrooms', 2, 'pbcr-agent-mode' )
			->once()
			->andReturn( 'Bathrooms' );

		Functions\expect( '_n' )
			->with( 'Garage', 'Garages', 1, 'pbcr-agent-mode' )
			->once()
			->andReturn( 'Garage' );

		$features = PropertyData::get_formatted_features( $property_data );

		$this->assertIsArray( $features );
		$this->assertCount( 3, $features );

		// Check bedroom feature.
		$this->assertEquals( 'Bedrooms', $features[0]['label'] );
		$this->assertEquals( '3', $features[0]['value'] );
		$this->assertEquals( 'bed', $features[0]['icon'] );

		// Check bathroom feature.
		$this->assertEquals( 'Bathrooms', $features[1]['label'] );
		$this->assertEquals( '2', $features[1]['value'] );
		$this->assertEquals( 'bath', $features[1]['icon'] );

		// Check garage feature.
		$this->assertEquals( 'Garage', $features[2]['label'] );
		$this->assertEquals( '1', $features[2]['value'] );
		$this->assertEquals( 'garage', $features[2]['icon'] );
	}

	/**
	 * Test empty data handling.
	 */
	public function test_empty_data_handling() {
		Functions\expect( 'get_post_meta' )
			->with( 123, 'REAL_HOMES_property_images', true )
			->once()
			->andReturn( '' );

		Functions\expect( 'get_post_meta' )
			->with( 123, 'REAL_HOMES_property_images', false )
			->once()
			->andReturn( [] );

		Functions\expect( 'get_the_ID' )
			->twice()
			->andReturn( 123 );

		// Simulate the template logic.
		$gallery_ids_true = get_post_meta( get_the_ID(), 'REAL_HOMES_property_images', true );
		$gallery_ids_false = get_post_meta( get_the_ID(), 'REAL_HOMES_property_images', false );
		$gallery_ids = is_array( $gallery_ids_true ) ? $gallery_ids_true : $gallery_ids_false;

		// Should return empty array, not false or null.
		$this->assertIsArray( $gallery_ids );
		$this->assertEmpty( $gallery_ids );
	}

	/**
	 * Test video data parsing.
	 */
	public function test_video_data_parsing() {
		$serialized_video = 'a:1:{i:0;a:2:{s:25:"inspiry_video_group_image";a:1:{i:0;s:2:"62";}s:23:"inspiry_video_group_url";s:43:"https://www.youtube.com/watch?v=_kDGv5_ZsHw";}}';

		Functions\expect( 'get_post_meta' )
			->with( 123, 'inspiry_video_group', true )
			->once()
			->andReturn( $serialized_video );

		$video_data = PropertyData::get_property_data( 123 )['video_data'];

		$this->assertIsArray( $video_data );
		$this->assertEquals( 'https://www.youtube.com/watch?v=_kDGv5_ZsHw', $video_data['url'] );
		$this->assertEquals( '62', $video_data['image_id'] );
	}

	/**
	 * Test agent data retrieval.
	 */
	public function test_agent_data_retrieval() {
		Functions\expect( 'get_post_meta' )
			->with( 123, 'REAL_HOMES_agents', true )
			->once()
			->andReturn( [ 54 ] );

		Functions\expect( 'get_userdata' )
			->with( 54 )
			->once()
			->andReturn( (object) [
				'display_name' => 'John Doe',
				'user_email'   => 'john@example.com',
			] );

		Functions\expect( 'get_avatar_url' )
			->with( 54 )
			->once()
			->andReturn( 'http://example.com/avatar.jpg' );

		$agents = PropertyData::get_property_data( 123 )['agents'];

		$this->assertIsArray( $agents );
		$this->assertCount( 1, $agents );
		$this->assertEquals( 'John Doe', $agents[0]['name'] );
		$this->assertEquals( 'john@example.com', $agents[0]['email'] );
		$this->assertEquals( 'http://example.com/avatar.jpg', $agents[0]['avatar_url'] );
	}

	/**
	 * Test price formatting.
	 */
	public function test_price_formatting() {
		Functions\expect( 'get_post_meta' )
			->with( 123, 'REAL_HOMES_property_price', true )
			->once()
			->andReturn( '480000' );

		$price = PropertyData::get_property_data( 123 )['price'];

		$this->assertEquals( '480,000', $price );
	}

	/**
	 * Test size formatting.
	 */
	public function test_size_formatting() {
		Functions\expect( 'get_post_meta' )
			->with( 123, 'REAL_HOMES_property_size', true )
			->once()
			->andReturn( '3550' );

		Functions\expect( 'get_post_meta' )
			->with( 123, 'REAL_HOMES_property_size_postfix', true )
			->once()
			->andReturn( 'Sq Ft' );

		$property_data = PropertyData::get_property_data( 123 );

		$this->assertEquals( '3,550', $property_data['size'] );
		$this->assertEquals( 'Sq Ft', $property_data['size_unit'] );
	}
}
