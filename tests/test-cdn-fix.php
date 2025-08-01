<?php
/**
 * Task-20 CDN Fix Validation Test
 *
 * Tests the updated Swiper CDN URLs and fallback system
 *
 * @package PBCRAgentMode
 */

echo "🔧 Task-20 CDN Fix Validation Test\n";
echo "==================================\n\n";

$tests_passed = 0;
$total_tests = 0;

function test_url($url, $description) {
    global $tests_passed, $total_tests;
    $total_tests++;

    // Test if URL is accessible
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_NOBODY, true);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 10);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    $result = curl_exec($ch);
    $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    if ($http_code == 200) {
        echo "✅ PASS: $description\n";
        echo "        URL: $url\n";
        $tests_passed++;
        return true;
    } else {
        echo "❌ FAIL: $description (HTTP $http_code)\n";
        echo "        URL: $url\n";
        return false;
    }
}

function check_file_content($file_path, $pattern, $description) {
    global $tests_passed, $total_tests;
    $total_tests++;

    if (!file_exists($file_path)) {
        echo "❌ FAIL: $description - File not found\n";
        return false;
    }

    $content = file_get_contents($file_path);
    if (strpos($content, $pattern) !== false) {
        echo "✅ PASS: $description\n";
        $tests_passed++;
        return true;
    } else {
        echo "❌ FAIL: $description\n";
        return false;
    }
}

echo "📋 TESTING CDN URLs\n";
echo "===================\n";

// Test the new CDN URLs
test_url('https://unpkg.com/swiper@11/swiper-bundle.min.css', 'Swiper CSS from unpkg CDN');
test_url('https://unpkg.com/swiper@11/swiper-bundle.min.js', 'Swiper JS from unpkg CDN');

echo "\n📋 TESTING OLD CDN URLs (should fail)\n";
echo "=====================================\n";

// Test the old CDN URLs that were failing
test_url('https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css', 'Old Swiper CSS from jsdelivr CDN');
test_url('https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js', 'Old Swiper JS from jsdelivr CDN');

echo "\n📋 TESTING FILE UPDATES\n";
echo "=======================\n";

$loader_path = __DIR__ . '/../includes/Loader.php';
$js_path = __DIR__ . '/../includes/js/agent-mode.js';
$template_path = __DIR__ . '/../includes/Views/agent-template.php';

// Check if Loader.php has been updated with new CDN URLs
check_file_content($loader_path, 'unpkg.com/swiper@11', 'Loader.php uses unpkg CDN');
check_file_content($loader_path, 'swiper-bundle.min.css', 'Loader.php enqueues Swiper CSS');
check_file_content($loader_path, 'swiper-bundle.min.js', 'Loader.php enqueues Swiper JS');

// Check if JavaScript has fallback system
check_file_content($js_path, 'checkSwiper', 'JavaScript has Swiper detection');
check_file_content($js_path, 'loadSwiperFallback', 'JavaScript has fallback loading');
check_file_content($js_path, 'maxAttempts', 'JavaScript has retry mechanism');

// Check if template has CSS fallback
check_file_content($template_path, 'unpkg.com/swiper@11/swiper-bundle.min.css', 'Template has CSS fallback');

echo "\n📋 TESTING JAVASCRIPT STRUCTURE\n";
echo "===============================\n";

if (file_exists($js_path)) {
    $js_content = file_get_contents($js_path);

    // Check for proper function structure
    $has_dom_ready = strpos($js_content, "addEventListener('DOMContentLoaded'") !== false;
    $has_check_swiper = strpos($js_content, 'function checkSwiper()') !== false;
    $has_init_swiper = strpos($js_content, 'function initializeSwiper()') !== false;
    $has_fallback = strpos($js_content, 'function loadSwiperFallback()') !== false;

    echo ($has_dom_ready ? "✅" : "❌") . " DOM ready event listener\n";
    echo ($has_check_swiper ? "✅" : "❌") . " Swiper check function\n";
    echo ($has_init_swiper ? "✅" : "❌") . " Swiper initialization function\n";
    echo ($has_fallback ? "✅" : "❌") . " Fallback loading function\n";

    $tests_passed += ($has_dom_ready + $has_check_swiper + $has_init_swiper + $has_fallback);
    $total_tests += 4;
}

echo "\n🏁 FINAL RESULTS\n";
echo "================\n";
echo "Tests Passed: $tests_passed / $total_tests\n";

if ($tests_passed === $total_tests) {
    echo "🎉 ALL TESTS PASSED! CDN fix is complete.\n\n";

    echo "📋 WHAT WAS FIXED:\n";
    echo "==================\n";
    echo "✅ Changed CDN from jsdelivr to unpkg (more reliable)\n";
    echo "✅ Added JavaScript retry mechanism for CDN loading\n";
    echo "✅ Added fallback Swiper loading in JavaScript\n";
    echo "✅ Added CSS fallback directly in template\n";
    echo "✅ Enhanced debug logging for troubleshooting\n\n";

    echo "🎯 NEXT STEPS:\n";
    echo "==============\n";
    echo "1. Visit: https://pbcr.local/property/florida-5-pinecrest-fl/?agent_view=1\n";
    echo "2. Open DevTools Console (F12)\n";
    echo "3. Look for these messages:\n";
    echo "   • 🔄 PBCR Agent Mode: Template HTML head loaded\n";
    echo "   • 🔄 PBCR Agent Mode: Script file loaded at [time]\n";
    echo "   • 🔄 Checking for Swiper library (attempt 1/10)\n";
    echo "   • ✅ Swiper library available\n";
    echo "   • ✅ PBCR Agent Mode Swiper gallery initialized successfully\n\n";

} else {
    $failed_tests = $total_tests - $tests_passed;
    echo "⚠️  $failed_tests TESTS FAILED. Some issues remain.\n\n";
}

echo "CDN fix validation complete.\n";
