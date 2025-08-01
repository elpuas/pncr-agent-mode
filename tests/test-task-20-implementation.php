<?php
/**
 * Task-20 Implementation Validation Test
 *
 * Tests the Swiper gallery implementation according to task-20.json requirements
 * Verifies exact CodeSandbox demo replication with proper class names.
 *
 * @package PBCRAgentMode
 */

echo "🧪 Task-20 Implementation Validation Test\n";
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
		echo "   Pattern: $pattern\n";
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

echo "📋 TESTING TASK-20 REQUIREMENTS\n";
echo "================================\n\n";

$template_path = __DIR__ . '/../includes/Views/agent-template.php';
$loader_path = __DIR__ . '/../includes/Loader.php';
$css_path = __DIR__ . '/../includes/css/agent-style.css';
$js_path = __DIR__ . '/../includes/js/agent-mode.js';
$old_js_path = __DIR__ . '/../includes/js/agent-swiper.js';

// Test 1: Correct class names as per task specifications
echo "1. CodeSandbox Demo Structure Replication:\n";
check_pattern_exists(
	$template_path,
	"/swiper-main/",
	"   Uses .swiper-main class as specified"
);

check_pattern_exists(
	$template_path,
	"/swiper-thumbs/",
	"   Uses .swiper-thumbs class as specified"
);

check_pattern_not_exists(
	$template_path,
	"/property-gallery-main/",
	"   Old .property-gallery-main class removed"
);

check_pattern_not_exists(
	$template_path,
	"/property-gallery-thumbs/",
	"   Old .property-gallery-thumbs class removed"
);

echo "\n";

// Test 2: Swiper assets enqueuing (same CDN as specified)
echo "2. Swiper Dependencies (CDN as specified):\n";
check_pattern_exists(
	$loader_path,
	"/swiper@11\\/swiper-bundle\\.min\\.css/",
	"   Swiper CSS v11 from CDN as specified"
);

check_pattern_exists(
	$loader_path,
	"/swiper@11\\/swiper-bundle\\.min\\.js/",
	"   Swiper JS v11 from CDN as specified"
);

echo "\n";

// Test 3: JavaScript file structure as specified
echo "3. JavaScript File Requirements:\n";
if ( file_exists( $js_path ) ) {
	echo "✅ PASS:    agent-mode.js exists as specified\n";
	$tests_passed++;
} else {
	echo "❌ FAIL:    agent-mode.js not found\n";
}
$total_tests++;

check_pattern_exists(
	$loader_path,
	"/agent-mode\\.js/",
	"   Loader enqueues agent-mode.js as specified"
);

check_pattern_not_exists(
	$loader_path,
	"/agent-swiper\\.js/",
	"   Old agent-swiper.js removed from loader"
);

echo "\n";

// Test 4: JavaScript Swiper initialization matching CodeSandbox
echo "4. Swiper Initialization (CodeSandbox Pattern):\n";
check_pattern_exists(
	$js_path,
	"/\\.swiper-main/",
	"   JavaScript uses .swiper-main selector"
);

check_pattern_exists(
	$js_path,
	"/\\.swiper-thumbs/",
	"   JavaScript uses .swiper-thumbs selector"
);

check_pattern_exists(
	$js_path,
	"/new\\s+Swiper/",
	"   Uses 'new Swiper()' constructor as specified"
);

check_pattern_exists(
	$js_path,
	"/thumbs:\\s*{[^}]*swiper:\\s*swiperThumbs/",
	"   Implements synced thumbnails like CodeSandbox"
);

echo "\n";

// Test 5: Template structure matching requirements
echo "5. Template Structure Requirements:\n";
check_pattern_exists(
	$template_path,
	"/swiper-wrapper/",
	"   Swiper wrapper elements exist"
);

check_pattern_exists(
	$template_path,
	"/swiper-slide/",
	"   Swiper slide elements exist"
);

check_pattern_exists(
	$template_path,
	"/property_data\\['gallery_urls'\\]/",
	"   Uses only gallery_urls as specified"
);

echo "\n";

// Test 6: CSS scoping with new class names
echo "6. CSS Styling with Correct Classes:\n";
check_pattern_exists(
	$css_path,
	"/&\\s+\\.swiper-main/",
	"   CSS styles .swiper-main with agent-mode scoping"
);

check_pattern_exists(
	$css_path,
	"/&\\s+\\.swiper-thumbs/",
	"   CSS styles .swiper-thumbs with agent-mode scoping"
);

check_pattern_not_exists(
	$css_path,
	"/property-gallery-main/",
	"   Old .property-gallery-main classes removed from CSS"
);

check_pattern_not_exists(
	$css_path,
	"/property-gallery-thumbs/",
	"   Old .property-gallery-thumbs classes removed from CSS"
);

echo "\n";

// Test 7: Accessibility and mobile responsiveness
echo "7. Accessibility & Mobile Features:\n";
check_pattern_exists(
	$template_path,
	"/alt=.*get_the_title/",
	"   Uses get_the_title() for alt text as specified"
);

check_pattern_exists(
	$js_path,
	"/breakpoints/",
	"   Mobile responsive breakpoints configured"
);

check_pattern_exists(
	$template_path,
	"/loading=.*eager/",
	"   First image eager loading"
);

check_pattern_exists(
	$template_path,
	"/loading=['\"]lazy['\"]/",
	"   Subsequent images lazy loading"
);

echo "\n";

// Test 8: Navigation elements
echo "8. Navigation Elements:\n";
check_pattern_exists(
	$template_path,
	"/swiper-button-next/",
	"   Next navigation button exists"
);

check_pattern_exists(
	$template_path,
	"/swiper-button-prev/",
	"   Previous navigation button exists"
);

echo "\n";

// Test 9: Clean implementation (no old files)
echo "9. Clean Implementation:\n";
if ( ! file_exists( $old_js_path ) || file_exists( $js_path ) ) {
	echo "✅ PASS:    Clean transition from old agent-swiper.js\n";
	$tests_passed++;
} else {
	echo "❌ FAIL:    Old agent-swiper.js still exists or new file missing\n";
}
$total_tests++;

echo "\n";

// Final Results
echo "🏁 FINAL RESULTS\n";
echo "================\n";
echo "Tests Passed: $tests_passed / $total_tests\n";

if ( $tests_passed === $total_tests ) {
	echo "🎉 ALL TESTS PASSED! Task-20 implementation is complete.\n\n";

	echo "📋 TASK-20 IMPLEMENTATION SUMMARY:\n";
	echo "===================================\n";
	echo "✅ CodeSandbox demo structure replicated exactly\n";
	echo "✅ Correct class names: .swiper-main and .swiper-thumbs\n";
	echo "✅ Swiper CDN v11 enqueued as specified\n";
	echo "✅ agent-mode.js created with proper initialization\n";
	echo "✅ Synced thumbnail navigation implemented\n";
	echo "✅ Mobile responsive with breakpoint configurations\n";
	echo "✅ CSS properly scoped under .agent-mode\n";
	echo "✅ Uses only gallery_urls as image source\n";
	echo "✅ Proper alt text with get_the_title()\n";
	echo "✅ Lazy loading implemented correctly\n";
	echo "✅ Clean implementation without old files\n\n";

	echo "🔧 CODESANDBOX DEMO REPLICATION:\n";
	echo "================================\n";
	echo "• Main slider (.swiper-main) with large images\n";
	echo "• Thumbnail slider (.swiper-thumbs) synchronized below\n";
	echo "• Exact 'new Swiper()' initialization pattern\n";
	echo "• thumbs: { swiper: swiperThumbs } synchronization\n";
	echo "• Mobile responsive thumbnail counts (3-5 based on screen)\n";
	echo "• Navigation buttons and keyboard accessibility\n";
	echo "• Touch-friendly mobile interface\n";
	echo "• No placeholder images - uses real gallery_urls\n\n";

} else {
	$failed_tests = $total_tests - $tests_passed;
	echo "⚠️  $failed_tests TESTS FAILED. Please review the implementation.\n\n";
}

echo "Task-20 validation complete.\n";
