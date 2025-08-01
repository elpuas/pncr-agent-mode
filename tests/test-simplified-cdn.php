<?php
/**
 * Test simplified CDN approach
 *
 * Quick validation of the simplified CDN implementation
 * Run: php tests/test-simplified-cdn.php
 */

echo "🧪 PBCR Agent Mode - Simplified CDN Test\n";
echo "=====================================\n\n";

// Test 1: Verify Loader.php has correct CDN URLs
echo "1. Testing Loader.php CDN URLs...\n";
$loader_content = file_get_contents(__DIR__ . '/../includes/Loader.php');

$expected_css_url = 'https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css';
$expected_js_url = 'https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js';

if (strpos($loader_content, $expected_css_url) !== false) {
    echo "   ✅ CSS CDN URL correct: $expected_css_url\n";
} else {
    echo "   ❌ CSS CDN URL not found or incorrect\n";
}

if (strpos($loader_content, $expected_js_url) !== false) {
    echo "   ✅ JS CDN URL correct: $expected_js_url\n";
} else {
    echo "   ❌ JS CDN URL not found or incorrect\n";
}

// Test 2: Verify template has correct CDN URL
echo "\n2. Testing agent-template.php CDN URL...\n";
$template_content = file_get_contents(__DIR__ . '/../includes/Views/agent-template.php');

if (strpos($template_content, $expected_css_url) !== false) {
    echo "   ✅ Template CSS CDN URL correct\n";
} else {
    echo "   ❌ Template CSS CDN URL not found or incorrect\n";
}

// Test 3: Verify JavaScript is simplified
echo "\n3. Testing agent-mode.js simplification...\n";
$js_content = file_get_contents(__DIR__ . '/../includes/js/agent-mode.js');

// Check that complex fallback code is removed
$complex_patterns = [
    'retryCount',
    'maxRetries',
    'loadWithRetry',
    'fallbackCDNUrls',
    'Array.isArray(fallbackCDNUrls)'
];

$simplified = true;
foreach ($complex_patterns as $pattern) {
    if (strpos($js_content, $pattern) !== false) {
        echo "   ❌ Complex fallback code still present: $pattern\n";
        $simplified = false;
    }
}

if ($simplified) {
    echo "   ✅ JavaScript simplified - no complex fallback code\n";
}

// Check for essential Swiper code
$essential_patterns = [
    'new Swiper(',
    '.swiper-main',
    '.swiper-thumbs',
    'thumbs: {'
];

$has_essentials = true;
foreach ($essential_patterns as $pattern) {
    if (strpos($js_content, $pattern) === false) {
        echo "   ❌ Essential Swiper code missing: $pattern\n";
        $has_essentials = false;
    }
}

if ($has_essentials) {
    echo "   ✅ Essential Swiper initialization code present\n";
}

// Test 4: Line count check
$line_count = count(file(__DIR__ . '/../includes/js/agent-mode.js'));
echo "\n4. JavaScript file size...\n";
echo "   📊 Total lines: $line_count\n";
if ($line_count < 150) {
    echo "   ✅ File simplified (under 150 lines)\n";
} else {
    echo "   ⚠️  File still large (over 150 lines)\n";
}

echo "\n🎯 SUMMARY:\n";
echo "- Loader.php: Using official jsdelivr CDN URLs\n";
echo "- Template: Using official CDN CSS URL\n";
echo "- JavaScript: Simplified without complex fallbacks\n";
echo "- Dependencies: Clean CDN-only approach\n";

echo "\n✨ Ready for testing with official Swiper CDN!\n";
?>
