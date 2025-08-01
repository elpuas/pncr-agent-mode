<?php
/**
 * Comprehensive Agent Mode Debug Test
 *
 * This script helps debug why the agent mode isn't working
 * Run this by accessing: https://pbcr.local/wp-content/plugins/pbcr-agent-mode/tests/live-debug.php
 */

// Basic WordPress bootstrap check
$wp_load_paths = [
    __DIR__ . '/../../../../wp-load.php',
    __DIR__ . '/../../../wp-load.php',
    __DIR__ . '/../../wp-load.php',
    __DIR__ . '/../wp-load.php'
];

$wp_loaded = false;
foreach ($wp_load_paths as $path) {
    if (file_exists($path)) {
        require_once $path;
        $wp_loaded = true;
        break;
    }
}

if (!$wp_loaded) {
    die('Could not load WordPress. Please run this from the WordPress admin or check file paths.');
}

echo '<h1>🔍 PBCR Agent Mode Live Debug</h1>';
echo '<style>body{font-family:Arial,sans-serif;max-width:1200px;margin:20px auto;padding:20px;}
.debug-section{margin:20px 0;padding:15px;border:1px solid #ddd;border-radius:5px;}
.success{background:#d4edda;border-color:#c3e6cb;}
.warning{background:#fff3cd;border-color:#ffeaa7;}
.error{background:#f8d7da;border-color:#f5c6cb;}
.code{background:#f4f4f4;padding:10px;border-radius:3px;font-family:monospace;white-space:pre-wrap;}
table{width:100%;border-collapse:collapse;}
th,td{border:1px solid #ddd;padding:8px;text-align:left;}
th{background:#f8f9fa;}
</style>';

// Test 1: Plugin Status
echo '<div class="debug-section">';
echo '<h2>📋 Plugin Status</h2>';
echo '<table>';

$plugin_file = PBCR_AGENT_MODE_PLUGIN_PATH;
$plugin_url = PBCR_AGENT_MODE_PLUGIN_URL ?? 'NOT DEFINED';
$plugin_version = PBCR_AGENT_MODE_VERSION ?? 'NOT DEFINED';

echo '<tr><th>Plugin Path</th><td>' . esc_html($plugin_file) . '</td></tr>';
echo '<tr><th>Plugin URL</th><td>' . esc_html($plugin_url) . '</td></tr>';
echo '<tr><th>Plugin Version</th><td>' . esc_html($plugin_version) . '</td></tr>';

// Check if files exist
$files_to_check = [
    'agent-mode.js' => $plugin_file . 'includes/js/agent-mode.js',
    'agent-template.php' => $plugin_file . 'includes/Views/agent-template.php',
    'agent-style.css' => $plugin_file . 'includes/css/agent-style.css',
    'Loader.php' => $plugin_file . 'includes/Loader.php',
];

foreach ($files_to_check as $name => $path) {
    $exists = file_exists($path) ? '✅ EXISTS' : '❌ MISSING';
    echo '<tr><th>' . esc_html($name) . '</th><td>' . $exists . '</td></tr>';
}

echo '</table>';
echo '</div>';

// Test 2: Current Request Analysis
echo '<div class="debug-section">';
echo '<h2>🌐 Current Request Analysis</h2>';
echo '<table>';
echo '<tr><th>Current URL</th><td>' . esc_url(home_url($_SERVER['REQUEST_URI'] ?? '')) . '</td></tr>';
echo '<tr><th>Is Singular Property</th><td>' . (is_singular('property') ? '✅ YES' : '❌ NO') . '</td></tr>';
echo '<tr><th>Post Type</th><td>' . esc_html(get_post_type() ?: 'NONE') . '</td></tr>';
echo '<tr><th>Agent View GET Param</th><td>' . esc_html($_GET['agent_view'] ?? 'NOT SET') . '</td></tr>';
echo '<tr><th>Query Var agent_view</th><td>' . esc_html(get_query_var('agent_view') ?: 'NOT SET') . '</td></tr>';

// Test agent view detection
$plugin = new PBCRAgentMode\Plugin();
$reflection = new ReflectionClass($plugin);
$method = $reflection->getMethod('is_agent_view');
$method->setAccessible(true);
$is_agent_view = $method->invoke($plugin);

echo '<tr><th>Agent View Detected</th><td>' . ($is_agent_view ? '✅ YES' : '❌ NO') . '</td></tr>';
echo '</table>';
echo '</div>';

// Test 3: Template Override Check
echo '<div class="debug-section">';
echo '<h2>📄 Template Override Test</h2>';

if (is_singular('property') && $is_agent_view) {
    $agent_template = PBCR_AGENT_MODE_PLUGIN_PATH . 'includes/Views/agent-template.php';
    if (file_exists($agent_template)) {
        echo '<div class="success">✅ Agent template would be loaded: ' . esc_html($agent_template) . '</div>';

        // Check template content
        $template_content = file_get_contents($agent_template);
        $has_swiper_main = strpos($template_content, 'swiper-main') !== false;
        $has_swiper_thumbs = strpos($template_content, 'swiper-thumbs') !== false;
        $has_gallery_urls = strpos($template_content, 'gallery_urls') !== false;

        echo '<table>';
        echo '<tr><th>Contains .swiper-main</th><td>' . ($has_swiper_main ? '✅ YES' : '❌ NO') . '</td></tr>';
        echo '<tr><th>Contains .swiper-thumbs</th><td>' . ($has_swiper_thumbs ? '✅ YES' : '❌ NO') . '</td></tr>';
        echo '<tr><th>Uses gallery_urls</th><td>' . ($has_gallery_urls ? '✅ YES' : '❌ NO') . '</td></tr>';
        echo '</table>';
    } else {
        echo '<div class="error">❌ Agent template file not found!</div>';
    }
} else {
    echo '<div class="warning">⚠️ Not on a property page with agent_view=1, so template override would not trigger.</div>';
}
echo '</div>';

// Test 4: Asset Enqueuing Check
echo '<div class="debug-section">';
echo '<h2>🎨 Asset Enqueuing Test</h2>';

if (is_singular('property') && $is_agent_view) {
    $loader = new PBCRAgentMode\Loader();

    // Manually trigger asset enqueuing to see what would happen
    $loader->enqueue_assets();

    global $wp_scripts, $wp_styles;

    $swiper_js_enqueued = wp_script_is('swiper-js', 'enqueued') || wp_script_is('swiper-js', 'registered');
    $agent_js_enqueued = wp_script_is('pbcr-agent-mode-js', 'enqueued') || wp_script_is('pbcr-agent-mode-js', 'registered');
    $swiper_css_enqueued = wp_style_is('swiper-css', 'enqueued') || wp_style_is('swiper-css', 'registered');
    $agent_css_enqueued = wp_style_is('pbcr-agent-mode-style', 'enqueued') || wp_style_is('pbcr-agent-mode-style', 'registered');

    echo '<table>';
    echo '<tr><th>Swiper JS</th><td>' . ($swiper_js_enqueued ? '✅ ENQUEUED' : '❌ NOT ENQUEUED') . '</td></tr>';
    echo '<tr><th>Agent Mode JS</th><td>' . ($agent_js_enqueued ? '✅ ENQUEUED' : '❌ NOT ENQUEUED') . '</td></tr>';
    echo '<tr><th>Swiper CSS</th><td>' . ($swiper_css_enqueued ? '✅ ENQUEUED' : '❌ NOT ENQUEUED') . '</td></tr>';
    echo '<tr><th>Agent Mode CSS</th><td>' . ($agent_css_enqueued ? '✅ ENQUEUED' : '❌ NOT ENQUEUED') . '</td></tr>';
    echo '</table>';

    // Show script URLs that would be loaded
    if ($agent_js_enqueued && isset($wp_scripts->registered['pbcr-agent-mode-js'])) {
        $script_src = $wp_scripts->registered['pbcr-agent-mode-js']->src;
        echo '<p><strong>Agent JS URL:</strong> ' . esc_url($script_src) . '</p>';
    }

} else {
    echo '<div class="warning">⚠️ Assets would not be enqueued because not on property page with agent_view=1</div>';
}
echo '</div>';

// Test 5: Property Data Check
echo '<div class="debug-section">';
echo '<h2>🏠 Property Data Check</h2>';

if (is_singular('property')) {
    global $post;
    if ($post) {
        echo '<table>';
        echo '<tr><th>Property ID</th><td>' . esc_html($post->ID) . '</td></tr>';
        echo '<tr><th>Property Title</th><td>' . esc_html($post->post_title) . '</td></tr>';

        // Check if PropertyData helper can get gallery data
        if (class_exists('PBCRAgentMode\Helpers\PropertyData')) {
            $property_data = PBCRAgentMode\Helpers\PropertyData::get_property_data($post->ID);
            $gallery_count = is_array($property_data['gallery_urls'] ?? null) ? count($property_data['gallery_urls']) : 0;

            echo '<tr><th>Gallery Images Count</th><td>' . esc_html($gallery_count) . '</td></tr>';

            if ($gallery_count > 0) {
                echo '<tr><th>First Gallery URL</th><td>' . esc_url($property_data['gallery_urls'][0] ?? '') . '</td></tr>';
            }
        } else {
            echo '<tr><th>PropertyData Helper</th><td>❌ CLASS NOT FOUND</td></tr>';
        }
        echo '</table>';
    }
} else {
    echo '<div class="warning">⚠️ Not on a property page</div>';
}
echo '</div>';

// Test 6: Quick Action Items
echo '<div class="debug-section">';
echo '<h2>🎯 Quick Action Items</h2>';

if (!is_singular('property')) {
    echo '<div class="warning">❌ Go to a property page first</div>';
} elseif (!$is_agent_view) {
    echo '<div class="warning">❌ Add ?agent_view=1 to the URL</div>';
} else {
    echo '<div class="success">✅ You are on the correct page with agent mode enabled!</div>';
    echo '<p><strong>If the JavaScript still isn\'t working:</strong></p>';
    echo '<ol>';
    echo '<li>Open DevTools (F12) and check the Console tab</li>';
    echo '<li>Look for the Network tab to see if scripts are loading</li>';
    echo '<li>Check if there are any JavaScript errors blocking execution</li>';
    echo '<li>Verify the property has gallery images</li>';
    echo '</ol>';
}

echo '</div>';

echo '<div class="debug-section">';
echo '<h2>🔗 Test URLs</h2>';
echo '<p>Here are some URLs you can test:</p>';

// Get a sample property
$properties = get_posts([
    'post_type' => 'property',
    'posts_per_page' => 3,
    'post_status' => 'publish'
]);

if ($properties) {
    echo '<ul>';
    foreach ($properties as $property) {
        $normal_url = get_permalink($property->ID);
        $agent_url = $normal_url . (strpos($normal_url, '?') !== false ? '&' : '?') . 'agent_view=1';
        echo '<li><strong>' . esc_html($property->post_title) . '</strong><br>';
        echo 'Normal: <a href="' . esc_url($normal_url) . '">' . esc_url($normal_url) . '</a><br>';
        echo 'Agent Mode: <a href="' . esc_url($agent_url) . '">' . esc_url($agent_url) . '</a></li>';
    }
    echo '</ul>';
}

echo '</div>';
?>
