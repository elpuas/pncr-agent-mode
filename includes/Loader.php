<?php

namespace PBCRAgentMode;

// If this file is called directly, abort.
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Loader Class
 * 
 * Handles the registration of shortcodes and other plugin components.
 */
class Loader {

    /**
     * Register all loader functionality
     */
    public function register() {
        // Register shortcodes
        $this->register_shortcodes();
        
        // Register styles and scripts
        add_action( 'wp_enqueue_scripts', [ $this, 'enqueue_assets' ] );
    }

    /**
     * Register all shortcodes
     */
    private function register_shortcodes() {
        $agent_view_link = new Shortcodes\AgentViewLink();
        $agent_view_link->register();
    }

    /**
     * Enqueue plugin assets
     */
    public function enqueue_assets() {
        // Only enqueue on agent mode views
        if ( get_query_var( 'agent_view' ) ) {
            wp_enqueue_style(
                'pbcr-agent-mode-style',
                PBCR_AGENT_MODE_PLUGIN_URL . 'includes/css/agent-style.css',
                [],
                PBCR_AGENT_MODE_VERSION
            );
        }
    }
} 