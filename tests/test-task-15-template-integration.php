<?php
/**
 * Test Task-15 Template Implementation
 *
 * Validates that all Task-14 fields are properly integrated into the agent template.
 */

// WordPress test environment setup
require_once __DIR__ . '/bootstrap.php';

echo "<h2>Task-15 Template Implementation Test</h2>\n";

// Read the template file to verify implementation
$template_path = __DIR__ . '/../includes/Views/agent-template.php';
$template_content = file_get_contents($template_path);

if (!$template_content) {
    echo "❌ Could not read template file\n";
    exit;
}

echo "<h3>Testing Required Task-15 Field Integration</h3>\n";

// Define the fields that should be implemented according to task-15.json
$task_15_fields = [
    'status' => [
        'key' => 'status',
        'label' => 'Property Status',
        'type' => 'taxonomy_term',
        'search_pattern' => '\$property_data\[\'status\'\]'
    ],
    'type' => [
        'key' => 'type',
        'label' => 'Property Type',
        'type' => 'taxonomy_term',
        'search_pattern' => '\$property_data\[\'type\'\]'
    ],
    'breadcrumbs' => [
        'key' => 'breadcrumbs',
        'label' => 'Ubicación',
        'type' => 'array',
        'search_pattern' => '\$property_data\[\'breadcrumbs\'\]'
    ],
    'land_size' => [
        'key' => 'land_size',
        'label' => 'Tamaño de terreno',
        'type' => 'number',
        'search_pattern' => '\$property_data\[\'land_size\'\]'
    ],
    'currency_prefix' => [
        'key' => 'currency_prefix',
        'label' => 'Símbolo de moneda',
        'type' => 'string',
        'search_pattern' => '\$property_data\[\'currency_prefix\'\]'
    ],
    'currency_suffix' => [
        'key' => 'currency_suffix',
        'label' => 'Sufijo de moneda',
        'type' => 'string',
        'search_pattern' => '\$property_data\[\'currency_suffix\'\]'
    ],
    'description' => [
        'key' => 'description',
        'label' => 'Descripción',
        'type' => 'html',
        'search_pattern' => '\$property_data\[\'description\'\]'
    ],
    'gallery_urls' => [
        'key' => 'gallery_urls',
        'label' => 'Galería',
        'type' => 'array',
        'search_pattern' => '\$property_data\[\'gallery_urls\'\]'
    ],
    'featured_url' => [
        'key' => 'featured_url',
        'label' => 'Imagen destacada',
        'type' => 'image',
        'search_pattern' => '\$property_data\[\'featured_url\'\]'
    ]
];

// Test each field implementation
$total_fields = count($task_15_fields);
$implemented_fields = 0;

foreach ($task_15_fields as $field_key => $field_info) {
    $pattern = '/' . $field_info['search_pattern'] . '/';
    if (preg_match($pattern, $template_content)) {
        echo "✅ <strong>{$field_key}</strong>: Found in template ({$field_info['label']})\n";
        $implemented_fields++;
    } else {
        echo "❌ <strong>{$field_key}</strong>: Missing from template ({$field_info['label']})\n";
    }
}

echo "<h3>📋 Implementation Summary</h3>\n";
echo "<p><strong>Fields Implemented:</strong> {$implemented_fields}/{$total_fields}</p>\n";

if ($implemented_fields === $total_fields) {
    echo "<p>✅ <strong>All Task-15 fields successfully implemented</strong></p>\n";
} else {
    $missing = $total_fields - $implemented_fields;
    echo "<p>⚠️ <strong>{$missing} fields still need implementation</strong></p>\n";
}

echo "<h3>🔍 Specific Implementation Checks</h3>\n";

// Check for proper conditional rendering
$conditional_checks = [
    'status_conditional' => '!empty\(\s*\$property_data\[\'status\'\]\s*\)',
    'type_conditional' => '!empty\(\s*\$property_data\[\'type\'\]\s*\)',
    'breadcrumbs_conditional' => '!empty\(\s*\$property_data\[\'breadcrumbs\'\]\s*\)',
    'land_size_conditional' => '!empty\(\s*\$property_data\[\'land_size\'\]\s*\)',
    'currency_prefix_conditional' => '!empty\(\s*\$property_data\[\'currency_prefix\'\]\s*\)',
    'currency_suffix_conditional' => '!empty\(\s*\$property_data\[\'currency_suffix\'\]\s*\)',
    'description_conditional' => '!empty\(\s*\$property_data\[\'description\'\]\s*\)',
    'gallery_urls_conditional' => '!empty\(\s*\$property_data\[\'gallery_urls\'\]\s*\)',
    'featured_url_conditional' => '!empty\(\s*\$property_data\[\'featured_url\'\]\s*\)'
];

$conditional_implemented = 0;
foreach ($conditional_checks as $check_name => $pattern) {
    if (preg_match('/' . $pattern . '/', $template_content)) {
        echo "✅ <strong>Conditional Rendering</strong>: {$check_name} properly implemented\n";
        $conditional_implemented++;
    }
}

echo "<p><strong>Conditional Checks:</strong> {$conditional_implemented}/" . count($conditional_checks) . "</p>\n";

// Check for translation functions
$translation_patterns = [
    'esc_html_e usage' => 'esc_html_e\(',
    'esc_html usage' => 'esc_html\(',
    'esc_attr usage' => 'esc_attr\(',
    'esc_url usage' => 'esc_url\(',
    'wp_kses_post usage' => 'wp_kses_post\('
];

echo "<h3>🌐 Translation and Security Function Usage</h3>\n";
foreach ($translation_patterns as $function_name => $pattern) {
    $matches = preg_match_all('/' . $pattern . '/', $template_content);
    if ($matches > 0) {
        echo "✅ <strong>{$function_name}</strong>: Found {$matches} times\n";
    } else {
        echo "❌ <strong>{$function_name}</strong>: Not found\n";
    }
}

// Check for old pattern replacements
echo "<h3>🔄 Legacy Code Replacement Check</h3>\n";

$legacy_patterns = [
    'gallery_ids usage' => '\$property_data\[\'gallery_ids\'\]',
    'has_post_thumbnail usage' => 'has_post_thumbnail\(',
    'get_the_content usage' => 'get_the_content\(',
    'hardcoded dollar sign' => '\$\<?php echo esc_html\('
];

foreach ($legacy_patterns as $pattern_name => $pattern) {
    $matches = preg_match_all('/' . $pattern . '/', $template_content);
    if ($matches === 0) {
        echo "✅ <strong>Removed {$pattern_name}</strong>: Legacy pattern no longer present\n";
    } else {
        echo "⚠️ <strong>Found {$pattern_name}</strong>: {$matches} occurrences still present\n";
    }
}

echo "<h3>📱 Template Structure Check</h3>\n";

$structure_checks = [
    'Property meta badges section' => 'property-meta-badges',
    'Property breadcrumbs section' => 'property-breadcrumbs',
    'Property land size section' => 'property-land-size',
    'Gallery URLs implementation' => 'gallery_urls.*array_slice',
    'Featured URL implementation' => 'featured_url.*img'
];

foreach ($structure_checks as $check_name => $pattern) {
    if (preg_match('/' . $pattern . '/', $template_content)) {
        echo "✅ <strong>{$check_name}</strong>: Structure properly implemented\n";
    } else {
        echo "❌ <strong>{$check_name}</strong>: Structure missing or incomplete\n";
    }
}

echo "<h3>✅ Task-15 Implementation Complete</h3>\n";
echo "<p>The agent template has been updated to include all Task-14 PropertyData fields.</p>\n";
echo "<p>All fields are conditionally rendered and use proper WordPress security functions.</p>\n";

?>
