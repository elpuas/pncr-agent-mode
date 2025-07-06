<?php

namespace PBCRAgentMode;

// If this file is called directly, abort.
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Main Plugin Class
 * 
 * Handles the initialization and registration of all plugin components.
 */
class Plugin {

    /**
     * Plugin version
     */
    const VERSION = '1.0.0';

    /**
     * The loader instance
     */
    private $loader;

    /**
     * Constructor
     */
    public function __construct() {
        $this->loader = new Loader();
    }

    /**
     * Register all plugin functionality
     */
    public function register() {
        // Register the loader
        $this->loader->register();
        
        // Hook into WordPress template system
        add_action( 'init', [ $this, 'init_rewrite_rules' ] );
        add_filter( 'template_include', [ $this, 'template_include' ] );
        add_filter( 'query_vars', [ $this, 'add_query_vars' ] );
    }

    /**
     * Initialize rewrite rules for agent mode
     */
    public function init_rewrite_rules() {
        // This will be implemented to handle agent mode URLs
    }

    /**
     * Add custom query variables
     */
    public function add_query_vars( $vars ) {
        $vars[] = 'agent_view';
        return $vars;
    }

    /**
     * Include custom template for agent mode
     */
    public function template_include( $template ) {
        // Check if we're viewing a single property with agent_view parameter
        if ( \is_singular( 'property' ) && $this->is_agent_view() ) {
            $agent_template = PBCR_AGENT_MODE_PLUGIN_PATH . 'includes/Views/agent-template.php';
            if ( file_exists( $agent_template ) ) {
                return $agent_template;
            }
        }
        
        return $template;
    }

    /**
     * Check if current request is for agent view
     */
    private function is_agent_view() {
        // Check both query var and GET parameter for compatibility
        return ( \get_query_var( 'agent_view' ) === '1' ) || ( isset( $_GET['agent_view'] ) && $_GET['agent_view'] === '1' );
    }
} 