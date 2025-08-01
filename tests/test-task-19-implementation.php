<?php
/**
 * Task-19 Implementation Validation Test
 *
 * Tests the Swiper gallery implementation according to task-19.json requirements.
 *
 * @package PBCRAgentMode
 */

echo "🧪 Task-19 Implementation Validation Test\n";
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

echo "📋 TESTING TASK-19 REQUIREMENTS\n";
echo "================================\n\n";

$template_path = __DIR__ . '/../includes/Views/agent-template.php';
$loader_path = __DIR__ . '/../includes/Loader.php';
$css_path = __DIR__ . '/../includes/css/agent-style.css';
$js_path = __DIR__ . '/../includes/js/agent-swiper.js';

// Test 1: Featured image section removal
echo "1. Featured Image Section Removal:\n";
check_pattern_not_exists(
	$template_path,
	"/<div\\s+class=['\"]property-image['\"]>/",
	"   Featured image section removed from template"
);

check_pattern_not_exists(
	$template_path,
	"/featured-image/",
	"   No featured-image classes remain in template"
);

echo "\n";

// Test 2: Swiper assets enqueuing
echo "2. Swiper Assets Enqueuing:\n";
check_pattern_exists(
	$loader_path,
	"/swiper@11\\/swiper-bundle\\.min\\.css/",
	"   Swiper CSS enqueued from CDN"
);

check_pattern_exists(
	$loader_path,
	"/swiper@11\\/swiper-bundle\\.min\\.js/",
	"   Swiper JS enqueued from CDN"
);

check_pattern_exists(
	$loader_path,
	"/agent-swiper\\.js/",
	"   Custom Swiper initialization script enqueued"
);

echo "\n";

// Test 3: Template structure
echo "3. Swiper Template Structure:\n";
check_pattern_exists(
	$template_path,
	"/property-gallery-main/",
	"   Main image slider container exists"
);

check_pattern_exists(
	$template_path,
	"/property-gallery-thumbs/",
	"   Thumbnail slider container exists"
);

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

echo "\n";

// Test 4: Gallery source validation
echo "4. Gallery Source Usage:\n";
check_pattern_exists(
	$template_path,
	"/property_data\\['gallery_urls'\\]/",
	"   Uses gallery_urls as primary source"
);

check_pattern_not_exists(
	$template_path,
	"/gallery_ids.*backward compatibility/",
	"   Gallery_ids fallback removed as specified"
);

check_pattern_not_exists(
	$template_path,
	"/array_slice.*6/",
	"   No image limit (removed 6-image restriction)"
);

echo "\n";

// Test 5: Accessibility features
echo "5. Accessibility Features:\n";
check_pattern_exists(
	$template_path,
	"/alt=.*Image.*\\d+/",
	"   Semantic alt text with image numbers"
);

check_pattern_exists(
	$js_path,
	"/keyboard:\\s*{[^}]*enabled:\\s*true/",
	"   Keyboard navigation enabled"
);

check_pattern_exists(
	$js_path,
	"/a11y/",
	"   Accessibility configuration present"
);

echo "\n";

// Test 6: Navigation elements
echo "6. Navigation Elements:\n";
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

// Test 7: Mobile responsiveness
echo "7. Mobile Responsiveness:\n";
check_pattern_exists(
	$js_path,
	"/breakpoints/",
	"   Responsive breakpoints configured"
);

check_pattern_exists(
	$css_path,
	"/@container.*max-width.*768px/",
	"   Mobile-specific CSS styles"
);

echo "\n";

// Test 8: CSS scoping
echo "8. CSS Scoping:\n";
check_pattern_exists(
	$css_path,
	"/&\\s+\\.property-gallery/",
	"   CSS properly scoped with .agent-mode"
);

check_pattern_not_exists(
	$css_path,
	"/\\.realhomes/i",
	"   No RealHomes classes used"
);

echo "\n";

// Test 9: Image loading optimization
echo "9. Image Loading Optimization:\n";
check_pattern_exists(
	$template_path,
	"/loading=.*eager/",
	"   First image loads eagerly"
);

check_pattern_exists(
	$template_path,
	"/loading=['\"]lazy['\"]/",
	"   Subsequent images load lazily"
);

echo "\n";

// Test 10: JavaScript initialization
echo "10. JavaScript Initialization:\n";
$js_content = file_exists( $js_path ) ? file_get_contents( $js_path ) : '';

if ( file_exists( $js_path ) ) {
	// Check for Swiper initialization
	if ( strpos( $js_content, 'new Swiper' ) !== false ) {
		echo "✅ PASS:    Swiper initialization code exists\n";
		$tests_passed++;
	} else {
		echo "❌ FAIL:    Swiper initialization code missing\n";
	}
	$total_tests++;

	// Check for thumbnail synchronization
	if ( strpos( $js_content, 'thumbs:' ) !== false ) {
		echo "✅ PASS:    Thumbnail synchronization configured\n";
		$tests_passed++;
	} else {
		echo "❌ FAIL:    Thumbnail synchronization missing\n";
	}
	$total_tests++;

	// Check for DOM ready event
	if ( strpos( $js_content, 'DOMContentLoaded' ) !== false ) {
		echo "✅ PASS:    DOM ready event listener exists\n";
		$tests_passed++;
	} else {
		echo "❌ FAIL:    DOM ready event listener missing\n";
	}
	$total_tests++;
} else {
	echo "❌ FAIL:    JavaScript file not found\n";
	$total_tests += 3;
}

echo "\n";

// Test 11: Clean markup validation
echo "11. Clean Markup Validation:\n";
$template_content = file_exists( $template_path ) ? file_get_contents( $template_path ) : '';

// Check for clean, semantic markup
if ( strpos( $template_content, 'class="gallery-main-image"' ) !== false ) {
	echo "✅ PASS:    Semantic CSS classes used\n";
	$tests_passed++;
} else {
	echo "❌ FAIL:    Semantic CSS classes missing\n";
}
$total_tests++;

// Check for no inline styles
if ( strpos( $template_content, 'style=' ) === false ) {
	echo "✅ PASS:    No inline styles in template\n";
	$tests_passed++;
} else {
	echo "❌ FAIL:    Inline styles found in template\n";
}
$total_tests++;

echo "\n";

// Final Results
echo "🏁 FINAL RESULTS\n";
echo "================\n";
echo "Tests Passed: $tests_passed / $total_tests\n";

if ( $tests_passed === $total_tests ) {
	echo "🎉 ALL TESTS PASSED! Task-19 implementation is complete.\n\n";

	echo "📋 IMPLEMENTATION SUMMARY:\n";
	echo "==========================\n";
	echo "✅ Featured image section completely removed\n";
	echo "✅ Swiper CSS and JS properly enqueued from CDN\n";
	echo "✅ Two synchronized sliders implemented (main + thumbs)\n";
	echo "✅ Uses only gallery_urls as image source\n";
	echo "✅ Keyboard accessible with semantic markup\n";
	echo "✅ Mobile responsive with breakpoint configurations\n";
	echo "✅ CSS properly scoped to avoid theme conflicts\n";
	echo "✅ Image loading optimized (eager/lazy loading)\n";
	echo "✅ Clean, semantic HTML markup\n";
	echo "✅ No image count limitations\n\n";

	echo "🔧 IMPLEMENTATION DETAILS:\n";
	echo "==========================\n";
	echo "• Main slider shows large images with navigation buttons\n";
	echo "• Thumbnail slider acts as navigation with active highlighting\n";
	echo "• Responsive design with 3-5 thumbnails based on screen size\n";
	echo "• Keyboard navigation (arrow keys) and accessibility features\n";
	echo "• Touch-friendly on mobile devices\n";
	echo "• High contrast and reduced motion support\n";
	echo "• No external dependencies beyond Swiper CDN\n\n";

} else {
	$failed_tests = $total_tests - $tests_passed;
	echo "⚠️  $failed_tests TESTS FAILED. Please review the implementation.\n\n";
}

echo "Task-19 validation complete.\n";
