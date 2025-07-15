<?php
/**
 * Agent Template Functional Tests
 *
 * Functional tests to verify template data retrieval and rendering.
 * These tests can be run directly to debug template issues.
 *
 * @package PBCRAgentMode
 */

// If this file is called directly, abort.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Agent Template Test Suite
 */
class AgentTemplateFunctionalTest {

	/**
	 * Test results array.
	 *
	 * @var array
	 */
	private $results = [];

	/**
	 * Run all tests and return results.
	 *
	 * @param int $property_id Property ID to test with.
	 * @return array Test results.
	 */
	public function run_tests( $property_id = null ) {
		if ( ! $property_id ) {
			$property_id = get_the_ID();
		}

		if ( ! $property_id ) {
			return [ 'error' => 'No property ID provided or available' ];
		}

		$this->results = [
			'property_id'           => $property_id,
			'timestamp'             => current_time( 'mysql' ),
			'post_type'             => get_post_type( $property_id ),
			'post_status'           => get_post_status( $property_id ),
			'gallery_tests'         => $this->test_gallery_data( $property_id ),
			'additional_details_tests' => $this->test_additional_details_data( $property_id ),
			'helper_tests'          => $this->test_property_data_helper( $property_id ),
			'meta_fields_tests'     => $this->test_all_meta_fields( $property_id ),
			'security_tests'        => $this->test_output_security(),
		];

		return $this->results;
	}

	/**
	 * Test gallery data retrieval.
	 *
	 * @param int $property_id Property ID.
	 * @return array Test results.
	 */
	private function test_gallery_data( $property_id ) {
		$results = [];

		// Test direct meta retrieval with true.
		$gallery_true = get_post_meta( $property_id, 'REAL_HOMES_property_images', true );
		$results['gallery_meta_true'] = [
			'type'  => gettype( $gallery_true ),
			'empty' => empty( $gallery_true ),
			'count' => is_array( $gallery_true ) ? count( $gallery_true ) : 0,
			'value' => is_array( $gallery_true ) ? array_slice( $gallery_true, 0, 3 ) : $gallery_true,
		];

		// Test direct meta retrieval with false.
		$gallery_false = get_post_meta( $property_id, 'REAL_HOMES_property_images', false );
		$results['gallery_meta_false'] = [
			'type'  => gettype( $gallery_false ),
			'empty' => empty( $gallery_false ),
			'count' => is_array( $gallery_false ) ? count( $gallery_false ) : 0,
			'value' => is_array( $gallery_false ) ? array_slice( $gallery_false, 0, 3 ) : $gallery_false,
		];

		// Test template logic.
		$gallery_ids = is_array( $gallery_true ) ? $gallery_true : $gallery_false;
		$results['template_logic'] = [
			'final_type'  => gettype( $gallery_ids ),
			'final_count' => is_array( $gallery_ids ) ? count( $gallery_ids ) : 0,
			'is_valid'    => ! empty( $gallery_ids ) && is_array( $gallery_ids ),
		];

		// Test individual image attachments.
		if ( is_array( $gallery_ids ) && ! empty( $gallery_ids ) ) {
			$results['image_validation'] = [];
			foreach ( array_slice( $gallery_ids, 0, 3 ) as $image_id ) {
				$attachment = get_post( $image_id );
				$results['image_validation'][ $image_id ] = [
					'exists'    => ! empty( $attachment ),
					'post_type' => $attachment ? $attachment->post_type : null,
					'url'       => wp_get_attachment_image_url( $image_id, 'thumbnail' ),
				];
			}
		}

		return $results;
	}

	/**
	 * Test additional details data retrieval.
	 *
	 * @param int $property_id Property ID.
	 * @return array Test results.
	 */
	private function test_additional_details_data( $property_id ) {
		$results = [];

		// Test direct meta retrieval with true.
		$details_true = get_post_meta( $property_id, 'REAL_HOMES_additional_details_list', true );
		$results['details_meta_true'] = [
			'type'   => gettype( $details_true ),
			'empty'  => empty( $details_true ),
			'length' => is_string( $details_true ) ? strlen( $details_true ) : 0,
			'sample' => is_string( $details_true ) ? substr( $details_true, 0, 50 ) . '...' : $details_true,
		];

		// Test direct meta retrieval with false.
		$details_false = get_post_meta( $property_id, 'REAL_HOMES_additional_details_list', false );
		$results['details_meta_false'] = [
			'type'  => gettype( $details_false ),
			'empty' => empty( $details_false ),
			'count' => is_array( $details_false ) ? count( $details_false ) : 0,
		];

		// Test template logic.
		$details = ! empty( $details_true ) ? $details_true : ( ! empty( $details_false[0] ) ? $details_false[0] : null );
		$details_unserialized = maybe_unserialize( $details );

		$results['template_logic'] = [
			'raw_type'          => gettype( $details ),
			'unserialized_type' => gettype( $details_unserialized ),
			'is_valid_array'    => is_array( $details_unserialized ),
			'item_count'        => is_array( $details_unserialized ) ? count( $details_unserialized ) : 0,
		];

		// Test individual detail items.
		if ( is_array( $details_unserialized ) && ! empty( $details_unserialized ) ) {
			$results['detail_validation'] = [];
			foreach ( array_slice( $details_unserialized, 0, 3 ) as $index => $item ) {
				$results['detail_validation'][ $index ] = [
					'is_array'    => is_array( $item ),
					'has_key'     => is_array( $item ) && ! empty( $item[0] ),
					'has_value'   => is_array( $item ) && ! empty( $item[1] ),
					'key'         => is_array( $item ) && isset( $item[0] ) ? $item[0] : null,
					'value'       => is_array( $item ) && isset( $item[1] ) ? $item[1] : null,
				];
			}
		}

		return $results;
	}

	/**
	 * Test PropertyData helper functionality.
	 *
	 * @param int $property_id Property ID.
	 * @return array Test results.
	 */
	private function test_property_data_helper( $property_id ) {
		$results = [];

		// Test if helper function exists and works.
		if ( function_exists( 'pbcr_agent_get_property_data' ) ) {
			$property_data = pbcr_agent_get_property_data( $property_id );

			$results['helper_function'] = [
				'exists'     => true,
				'returns'    => gettype( $property_data ),
				'is_array'   => is_array( $property_data ),
				'key_count'  => is_array( $property_data ) ? count( $property_data ) : 0,
				'has_price'  => is_array( $property_data ) && isset( $property_data['price'] ),
				'has_gallery' => is_array( $property_data ) && isset( $property_data['gallery_ids'] ),
				'has_extras' => is_array( $property_data ) && isset( $property_data['extras_raw'] ),
			];

			// Test specific data points.
			if ( is_array( $property_data ) ) {
				$results['helper_data'] = [
					'price'            => $property_data['price'] ?? null,
					'ref_id'           => $property_data['ref_id'] ?? null,
					'gallery_count'    => is_array( $property_data['gallery_ids'] ?? null ) ? count( $property_data['gallery_ids'] ) : 0,
					'extras_count'     => is_array( $property_data['extras_raw'] ?? null ) ? count( $property_data['extras_raw'] ) : 0,
					'bedrooms'         => $property_data['bedrooms'] ?? null,
					'bathrooms'        => $property_data['bathrooms'] ?? null,
					'address'          => $property_data['address'] ?? null,
				];
			}
		} else {
			$results['helper_function'] = [
				'exists' => false,
				'error'  => 'pbcr_agent_get_property_data function not found',
			];
		}

		// Test PropertyData class directly.
		if ( class_exists( 'PBCRAgentMode\\Helpers\\PropertyData' ) ) {
			$class_data = \PBCRAgentMode\Helpers\PropertyData::get_property_data( $property_id );

			$results['helper_class'] = [
				'exists'     => true,
				'returns'    => gettype( $class_data ),
				'is_array'   => is_array( $class_data ),
				'key_count'  => is_array( $class_data ) ? count( $class_data ) : 0,
			];
		} else {
			$results['helper_class'] = [
				'exists' => false,
				'error'  => 'PropertyData class not found',
			];
		}

		return $results;
	}

	/**
	 * Test all relevant meta fields.
	 *
	 * @param int $property_id Property ID.
	 * @return array Test results.
	 */
	private function test_all_meta_fields( $property_id ) {
		$meta_fields = [
			'REAL_HOMES_property_price',
			'REAL_HOMES_property_size',
			'REAL_HOMES_property_bedrooms',
			'REAL_HOMES_property_bathrooms',
			'REAL_HOMES_property_garage',
			'REAL_HOMES_property_address',
			'REAL_HOMES_property_location',
			'REAL_HOMES_property_id',
			'REAL_HOMES_featured',
			'REAL_HOMES_add_in_slider',
			'REAL_HOMES_property_map',
			'REAL_HOMES_property_images',
			'REAL_HOMES_additional_details_list',
			'REAL_HOMES_agents',
			'inspiry_video_group',
		];

		$results = [];

		foreach ( $meta_fields as $field ) {
			$value = get_post_meta( $property_id, $field, true );
			$results[ $field ] = [
				'exists' => metadata_exists( 'post', $property_id, $field ),
				'type'   => gettype( $value ),
				'empty'  => empty( $value ),
				'value'  => is_string( $value ) && strlen( $value ) > 100 ? substr( $value, 0, 50 ) . '...' : $value,
			];
		}

		return $results;
	}

	/**
	 * Test output security and escaping.
	 *
	 * @return array Test results.
	 */
	private function test_output_security() {
		$test_data = [
			'<script>alert("xss")</script>',
			'<img src="x" onerror="alert(1)">',
			'javascript:alert("xss")',
			'"onclick="alert(1)"',
		];

		$results = [];

		foreach ( $test_data as $index => $malicious_input ) {
			$results[ 'test_' . $index ] = [
				'input'      => $malicious_input,
				'esc_html'   => esc_html( $malicious_input ),
				'esc_attr'   => esc_attr( $malicious_input ),
				'esc_url'    => esc_url( $malicious_input ),
				'wp_kses'    => wp_kses_post( $malicious_input ),
			];
		}

		return $results;
	}

	/**
	 * Generate HTML report of test results.
	 *
	 * @return string HTML report.
	 */
	public function generate_html_report() {
		if ( empty( $this->results ) ) {
			return '<p>No test results available. Run tests first.</p>';
		}

		ob_start();
		?>
		<div style="font-family: monospace; background: #f9f9f9; padding: 20px; margin: 20px; border: 1px solid #ddd;">
			<h2>🧪 Agent Template Test Results</h2>
			<p><strong>Property ID:</strong> <?php echo esc_html( $this->results['property_id'] ); ?></p>
			<p><strong>Post Type:</strong> <?php echo esc_html( $this->results['post_type'] ); ?></p>
			<p><strong>Post Status:</strong> <?php echo esc_html( $this->results['post_status'] ); ?></p>
			<p><strong>Test Time:</strong> <?php echo esc_html( $this->results['timestamp'] ); ?></p>

			<h3>📸 Gallery Tests</h3>
			<pre><?php echo esc_html( print_r( $this->results['gallery_tests'], true ) ); ?></pre>

			<h3>📋 Additional Details Tests</h3>
			<pre><?php echo esc_html( print_r( $this->results['additional_details_tests'], true ) ); ?></pre>

			<h3>🔧 Helper Function Tests</h3>
			<pre><?php echo esc_html( print_r( $this->results['helper_tests'], true ) ); ?></pre>

			<h3>🗂️ Meta Fields Tests</h3>
			<pre><?php echo esc_html( print_r( $this->results['meta_fields_tests'], true ) ); ?></pre>

			<h3>🔒 Security Tests</h3>
			<pre><?php echo esc_html( print_r( $this->results['security_tests'], true ) ); ?></pre>
		</div>
		<?php
		return ob_get_clean();
	}
}

// If running this file directly in WordPress context, execute tests.
if ( defined( 'ABSPATH' ) && isset( $_GET['run_agent_tests'] ) ) {
	$tester = new AgentTemplateFunctionalTest();
	$property_id = isset( $_GET['property_id'] ) ? intval( $_GET['property_id'] ) : get_the_ID();
	$results = $tester->run_tests( $property_id );
	echo $tester->generate_html_report();
	exit;
}
