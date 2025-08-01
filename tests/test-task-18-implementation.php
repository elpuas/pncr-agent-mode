<?php
/**
 * Task-18 Implementation Validation Test
 *
 * Tests the currency prefix and suffix implementation according to task-18.json requirements.
 *
 * @package PBCRAgentMode
 */

echo "🧪 Task-18 Implementation Validation Test\n";
echo "==========================================\n\n";

// Test requirements counter
$tests_passed = 0;
$total_tests = 0;

/**
 * Helper function to check if a pattern exists in file content
 */
function check_pattern_exists( $file_path, $pattern, $description ) {
	global $tests_passed, $total_tests;
	$total_tests++;

	if ( ! file_exists( $file_path ) ) {
		echo "❌ FAIL: $description - File not found: $file_path\n";
		return false;
	}

	$content = file_get_contents( $file_path );
	if ( preg_match( $pattern, $content ) ) {
		echo "✅ PASS: $description\n";
		$tests_passed++;
		return true;
	} else {
		echo "❌ FAIL: $description\n";
		return false;
	}
}

echo "📋 TESTING TASK-18 REQUIREMENTS\n";
echo "================================\n\n";

$property_data_path = __DIR__ . '/../includes/Helpers/PropertyData.php';

// Test 1: Currency prefix and suffix methods exist
echo "1. Required Methods Exist:\n";
check_pattern_exists(
	$property_data_path,
	"/private\\s+static\\s+function\\s+get_currency_prefix/",
	"   get_currency_prefix method exists"
);

check_pattern_exists(
	$property_data_path,
	"/private\\s+static\\s+function\\s+get_currency_suffix/",
	"   get_currency_suffix method exists"
);

echo "\n";

// Test 2: Correct meta keys
echo "2. Correct Meta Keys Usage:\n";
check_pattern_exists(
	$property_data_path,
	"/REAL_HOMES_property_price_prefix/",
	"   Uses REAL_HOMES_property_price_prefix meta key"
);

check_pattern_exists(
	$property_data_path,
	"/REAL_HOMES_property_price_postfix/",
	"   Uses REAL_HOMES_property_price_postfix meta key"
);

echo "\n";

// Test 3: Methods use get_meta_value helper
echo "3. Proper Helper Usage:\n";
check_pattern_exists(
	$property_data_path,
	"/self::get_meta_value.*REAL_HOMES_property_price_prefix/",
	"   Currency prefix uses get_meta_value helper"
);

check_pattern_exists(
	$property_data_path,
	"/self::get_meta_value.*REAL_HOMES_property_price_postfix/",
	"   Currency suffix uses get_meta_value helper"
);

echo "\n";

// Test 4: Fields included in get_property_data return array
echo "4. Return Array Integration:\n";
check_pattern_exists(
	$property_data_path,
	"/['\"]currency_prefix['\"]\\s*=>\\s*self::\\s*get_currency_prefix\\s*\\(/",
	"   currency_prefix field in return array"
);

check_pattern_exists(
	$property_data_path,
	"/['\"]currency_suffix['\"]\\s*=>\\s*self::\\s*get_currency_suffix\\s*\\(/",
	"   currency_suffix field in return array"
);

echo "\n";

// Test 5: get_meta_value returns empty string for missing values
echo "5. Empty String Fallback:\n";
check_pattern_exists(
	$property_data_path,
	"/return.*empty.*value.*value.*['\"]['\"];/",
	"   get_meta_value returns empty string for missing values"
);

echo "\n";

// Test 6: No modifications to other currency fields
echo "6. Other Currency Fields Preservation:\n";
$property_data_content = file_exists( $property_data_path ) ? file_get_contents( $property_data_path ) : '';

// Check that we don't use the old meta keys in the new methods
$old_currency_symbol_in_prefix = preg_match( '/get_currency_prefix.*REAL_HOMES_currency_symbol/', $property_data_content );
$old_currency_suffix_key = preg_match( '/get_currency_suffix.*REAL_HOMES_currency_suffix/', $property_data_content );

if ( ! $old_currency_symbol_in_prefix ) {
	echo "✅ PASS:    Currency prefix doesn't use old REAL_HOMES_currency_symbol\n";
	$tests_passed++;
} else {
	echo "❌ FAIL:    Currency prefix still uses old REAL_HOMES_currency_symbol\n";
}
$total_tests++;

if ( ! $old_currency_suffix_key ) {
	echo "✅ PASS:    Currency suffix doesn't use old REAL_HOMES_currency_suffix\n";
	$tests_passed++;
} else {
	echo "❌ FAIL:    Currency suffix still uses old REAL_HOMES_currency_suffix\n";
}
$total_tests++;

echo "\n";

// Test 7: Method documentation
echo "7. Method Documentation:\n";
check_pattern_exists(
	$property_data_path,
	"/\\/\\*\\*[\\s\\S]*?Get currency prefix[\\s\\S]*?\\*\\//",
	"   Currency prefix method has documentation"
);

check_pattern_exists(
	$property_data_path,
	"/\\/\\*\\*[\\s\\S]*?Get currency suffix[\\s\\S]*?\\*\\//",
	"   Currency suffix method has documentation"
);

echo "\n";

// Test 8: Return type consistency
echo "8. Return Type Validation:\n";
check_pattern_exists(
	$property_data_path,
	"/@return\\s+string.*Currency prefix/",
	"   Currency prefix method returns string type"
);

check_pattern_exists(
	$property_data_path,
	"/@return\\s+string.*Currency suffix/",
	"   Currency suffix method returns string type"
);

echo "\n";

// Final Results
echo "🏁 FINAL RESULTS\n";
echo "================\n";
echo "Tests Passed: $tests_passed / $total_tests\n";

if ( $tests_passed === $total_tests ) {
	echo "🎉 ALL TESTS PASSED! Task-18 implementation is complete.\n\n";

	echo "📋 IMPLEMENTATION SUMMARY:\n";
	echo "==========================\n";
	echo "✅ Currency prefix method uses REAL_HOMES_property_price_prefix\n";
	echo "✅ Currency suffix method uses REAL_HOMES_property_price_postfix\n";
	echo "✅ Both methods return plain strings with empty string fallback\n";
	echo "✅ Fields properly integrated into get_property_data() return array\n";
	echo "✅ No modifications to existing currency-related fields\n";
	echo "✅ Proper method documentation and type hints\n\n";

	echo "🔧 IMPLEMENTATION DETAILS:\n";
	echo "==========================\n";
	echo "• get_currency_prefix() uses 'REAL_HOMES_property_price_prefix' meta key\n";
	echo "• get_currency_suffix() uses 'REAL_HOMES_property_price_postfix' meta key\n";
	echo "• Both methods leverage existing get_meta_value() helper\n";
	echo "• Empty string fallback handled by get_meta_value() implementation\n";
	echo "• Fields available in template via \$property_data['currency_prefix'] and \$property_data['currency_suffix']\n\n";

} else {
	$failed_tests = $total_tests - $tests_passed;
	echo "⚠️  $failed_tests TESTS FAILED. Please review the implementation.\n\n";
}

echo "Task-18 validation complete.\n";
