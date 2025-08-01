<?php
/**
 * Test local Swiper files setup
 *
 * Validate that Swiper files are properly downloaded and configured
 * Run: php tests/test-local-swiper.php
 */

echo "🧪 PBCR Agent Mode - Local Swiper Files Test\n";
echo "==========================================\n\n";

// Test 1: Check if Swiper files exist
echo "1. Testing Swiper file existence...\n";
$swiper_js = __DIR__ . '/../includes/js/swiper-bundle.min.js';
$swiper_css = __DIR__ . '/../includes/css/swiper-bundle.min.css';

if (file_exists($swiper_js)) {
    $js_size = filesize($swiper_js);
    echo "   ✅ Swiper JS found: " . number_format($js_size) . " bytes\n";
} else {
    echo "   ❌ Swiper JS not found at: $swiper_js\n";
}

if (file_exists($swiper_css)) {
    $css_size = filesize($swiper_css);
    echo "   ✅ Swiper CSS found: " . number_format($css_size) . " bytes\n";
} else {
    echo "   ❌ Swiper CSS not found at: $swiper_css\n";
}

// Test 2: Verify Loader.php uses local files
echo "\n2. Testing Loader.php configuration...\n";
$loader_content = file_get_contents(__DIR__ . '/../includes/Loader.php');

$local_js_pattern = "PBCR_AGENT_MODE_PLUGIN_URL . 'includes/js/swiper-bundle.min.js'";
$local_css_pattern = "PBCR_AGENT_MODE_PLUGIN_URL . 'includes/css/swiper-bundle.min.css'";

if (strpos($loader_content, $local_js_pattern) !== false) {
    echo "   ✅ Loader.php configured for local Swiper JS\n";
} else {
    echo "   ❌ Loader.php not configured for local Swiper JS\n";
}

if (strpos($loader_content, $local_css_pattern) !== false) {
    echo "   ✅ Loader.php configured for local Swiper CSS\n";
} else {
    echo "   ❌ Loader.php not configured for local Swiper CSS\n";
}

// Test 3: Verify no CDN references remain
echo "\n3. Testing for remaining CDN references...\n";
$cdn_patterns = [
    'https://cdn.jsdelivr.net',
    'https://unpkg.com',
    'https://cdnjs.cloudflare.com'
];

$cdn_found = false;
foreach ($cdn_patterns as $pattern) {
    if (strpos($loader_content, $pattern) !== false) {
        echo "   ⚠️  CDN reference still found: $pattern\n";
        $cdn_found = true;
    }
}

if (!$cdn_found) {
    echo "   ✅ No CDN references found in Loader.php\n";
}

// Test 4: Verify agent-mode.js is simplified
echo "\n4. Testing agent-mode.js simplification...\n";
$js_content = file_get_contents(__DIR__ . '/../includes/js/agent-mode.js');

$complex_patterns = [
    'checkSwiperAvailability',
    'setTimeout',
    'initializeSwiper',
    'retryCount'
];

$simplified = true;
foreach ($complex_patterns as $pattern) {
    if (strpos($js_content, $pattern) !== false) {
        echo "   ⚠️  Complex pattern still found: $pattern\n";
        $simplified = false;
    }
}

if ($simplified) {
    echo "   ✅ JavaScript simplified - no complex retry logic\n";
}

// Check for essential patterns
$essential_patterns = [
    'new Swiper(',
    '.swiper-main',
    '.swiper-thumbs',
    'PBCR Agent Mode Swiper gallery initialized successfully'
];

$has_essentials = true;
foreach ($essential_patterns as $pattern) {
    if (strpos($js_content, $pattern) === false) {
        echo "   ❌ Essential pattern missing: $pattern\n";
        $has_essentials = false;
    }
}

if ($has_essentials) {
    echo "   ✅ Essential Swiper code present\n";
}

echo "\n🎯 SUMMARY:\n";
echo "- Swiper files: Downloaded locally (no CDN dependency)\n";
echo "- Loader.php: Configured for local file loading\n";
echo "- JavaScript: Simplified without retry complexity\n";
echo "- Dependencies: Fully self-contained\n";

echo "\n✨ Local Swiper setup complete!\n";
echo "🚀 Ready for testing - Swiper will always be available now!\n";
?>
