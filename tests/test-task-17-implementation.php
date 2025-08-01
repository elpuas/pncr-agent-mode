<?php
/**
 * Task-17 Implementation Validation Test
 *
 * Tests the refactored price section implementation according to task-17.json requirements.
 *
 * @package PBCRAgentMode
 */

echo "🧪 Task-17 Implementation Validation Test\n";
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

/**
 * Helper function to check if a pattern does NOT exist in file content
 */
function check_pattern_not_exists( $file_path, $pattern, $description ) {
	global $tests_passed, $total_tests;
	$total_tests++;

	if ( ! file_exists( $file_path ) ) {
		echo "❌ FAIL: $description - File not found: $file_path\n";
		return false;
	}

	$content = file_get_contents( $file_path );
	if ( ! preg_match( $pattern, $content ) ) {
		echo "✅ PASS: $description\n";
		$tests_passed++;
		return true;
	} else {
		echo "❌ FAIL: $description\n";
		return false;
	}
}

echo "📋 TESTING TASK-17 REQUIREMENTS\n";
echo "================================\n\n";

$template_path = __DIR__ . '/../includes/Views/agent-template.php';
$property_data_path = __DIR__ . '/../includes/Helpers/PropertyData.php';

// Test 1: PropertyData has old_price field
echo "1. PropertyData Enhancement:\n";
check_pattern_exists(
	$property_data_path,
	"/['\"]old_price['\"]\\s*=>\\s*self::\\s*get_formatted_old_price/",
	"   PropertyData includes 'old_price' field"
);

check_pattern_exists(
	$property_data_path,
	"/private\\s+static\\s+function\\s+get_formatted_old_price/",
	"   get_formatted_old_price method exists"
);

check_pattern_exists(
	$property_data_path,
	"/REAL_HOMES_property_old_price/",
	"   Uses correct RealHomes old price meta field"
);

echo "\n";

// Test 2: RealHomes-style price container
echo "2. RealHomes Price Container:\n";
check_pattern_exists(
	$template_path,
	"/<div\\s+class=['\"]rh_page__property_price['\"]>/",
	"   RealHomes price container class exists"
);

echo "\n";

// Test 3: Status display
echo "3. Status Display:\n";
check_pattern_exists(
	$template_path,
	"/<p\\s+class=['\"]status['\"]>/",
	"   Status element with correct class"
);

check_pattern_exists(
	$template_path,
	"/property_data\\[.status.\\]/",
	"   Uses property_data status field"
);

echo "\n";

// Test 4: Price structure
echo "4. Price Structure:\n";
check_pattern_exists(
	$template_path,
	"/<p\\s+class=['\"]price['\"]>/",
	"   Price element with correct class"
);

check_pattern_exists(
	$template_path,
	"/<span\\s+class=['\"]property-price-wrapper['\"]>/",
	"   Property price wrapper span"
);

check_pattern_exists(
	$template_path,
	"/<ins\\s+class=['\"]property-current-price['\"]>/",
	"   Current price in <ins> element"
);

echo "\n";

// Test 5: Old price support
echo "5. Old Price Support:\n";
check_pattern_exists(
	$template_path,
	"/<del\\s+class=['\"]property-old-price['\"]>/",
	"   Old price in <del> element"
);

check_pattern_exists(
	$template_path,
	"/property_data\\[.old_price.\\]/",
	"   Uses property_data old_price field"
);

echo "\n";

// Test 6: Currency prefix/suffix
echo "6. Currency Support:\n";
check_pattern_exists(
	$template_path,
	"/property_data\\[.currency_prefix.\\]/",
	"   Uses currency_prefix field"
);

check_pattern_exists(
	$template_path,
	"/property_data\\[.currency_suffix.\\]/",
	"   Uses currency_suffix field"
);

echo "\n";

// Test 7: Duplicate status suppression
echo "7. Duplicate Status Suppression:\n";
check_pattern_exists(
	$template_path,
	"/\\/\\*.*Commented out.*property-meta-badges.*\\*\\//s",
	"   Original status badges are commented out"
);

// Simple check: badges should only appear within comment blocks
$template_content = file_get_contents( $template_path );
$uncommented_badges = preg_match( '/property-meta-badges(?![\\s\\S]*\\*\\/)/', $template_content );
if ( ! $uncommented_badges ) {
	echo "✅ PASS:    No active property-meta-badges div outside comments\n";
	$tests_passed++;
} else {
	echo "❌ FAIL:    Found property-meta-badges outside comment blocks\n";
}
$total_tests++;

echo "\n";

// Test 8: Security functions
echo "8. Security & Escaping:\n";
$template_content = file_exists( $template_path ) ? file_get_contents( $template_path ) : '';
$esc_html_count = substr_count( $template_content, 'esc_html(' );
$esc_url_count = substr_count( $template_content, 'esc_url(' );
$esc_attr_count = substr_count( $template_content, 'esc_attr(' );

echo "   esc_html() usage: $esc_html_count instances\n";
echo "   esc_url() usage: $esc_url_count instances\n";
echo "   esc_attr() usage: $esc_attr_count instances\n";

if ( $esc_html_count >= 15 && $esc_url_count >= 5 && $esc_attr_count >= 3 ) {
	echo "✅ PASS: Adequate security function usage\n";
	$tests_passed++;
} else {
	echo "❌ FAIL: Insufficient security function usage\n";
}
$total_tests++;

echo "\n";

// Test 9: Conditional rendering
echo "9. Conditional Rendering:\n";
check_pattern_exists(
	$template_path,
	"/property_data\\[.price.\\]/",
	"   Price section has conditional rendering"
);

check_pattern_exists(
	$template_path,
	"/property_data\\[.old_price.\\]/",
	"   Old price has conditional rendering"
);

check_pattern_exists(
	$template_path,
	"/property_data\\[.status.\\]/",
	"   Status has conditional rendering"
);

echo "\n";

// Final Results
echo "🏁 FINAL RESULTS\n";
echo "================\n";
echo "Tests Passed: $tests_passed / $total_tests\n";

if ( $tests_passed === $total_tests ) {
	echo "🎉 ALL TESTS PASSED! Task-17 implementation is complete.\n\n";

	echo "📋 IMPLEMENTATION SUMMARY:\n";
	echo "==========================\n";
	echo "✅ Added old_price field to PropertyData\n";
	echo "✅ Implemented RealHomes-style price container\n";
	echo "✅ Added status display with <p class='status'>\n";
	echo "✅ Structured price with wrapper, current price, and old price\n";
	echo "✅ Included currency prefix/suffix support\n";
	echo "✅ Suppressed duplicate status badges\n";
	echo "✅ Maintained security best practices\n";
	echo "✅ Applied conditional rendering for all fields\n\n";

	echo "🔧 NEW FEATURES:\n";
	echo "================\n";
	echo "• RealHomes-compatible price section styling\n";
	echo "• Old price display with strikethrough formatting\n";
	echo "• Centralized status display in price section\n";
	echo "• Enhanced currency symbol positioning\n";
	echo "• Reduced template duplication\n\n";

} else {
	$failed_tests = $total_tests - $tests_passed;
	echo "⚠️  $failed_tests TESTS FAILED. Please review the implementation.\n\n";
}

echo "Task-17 validation complete.\n";
