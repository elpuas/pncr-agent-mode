<?php
/**
 * Agent Mode Template
 * 
 * This template renders the minimal, unbranded view of a property for agent sharing.
 */

// If this file is called directly, abort.
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

// Get the current post
global $post;

// Verify we have a valid property post
if ( ! $post || get_post_type( $post ) !== 'property' ) {
    // Return 404 if not a valid property
    status_header( 404 );
    nocache_headers();
    include( get_query_template( '404' ) );
    exit;
}

// Check if the property is published
if ( get_post_status( $post ) !== 'publish' ) {
    // Return 404 if not published
    status_header( 404 );
    nocache_headers();
    include( get_query_template( '404' ) );
    exit;
}

?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo( 'charset' ); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="noindex, nofollow">
    <title><?php echo esc_html( get_the_title() ); ?> - Agent View</title>
    <?php wp_head(); ?>
</head>
<body class="agent-mode">
    <div class="agent-mode-container">
        <header class="agent-mode-header">
            <h1 class="property-title"><?php echo esc_html( get_the_title() ); ?></h1>
        </header>

        <main class="agent-mode-content">
            <div class="property-image">
                <?php if ( has_post_thumbnail() ) : ?>
                    <img src="<?php echo esc_url( get_the_post_thumbnail_url( get_the_ID(), 'large' ) ); ?>" 
                    alt="<?php echo esc_attr( get_the_title() ); ?>" 
                    class="featured-image">
                <?php endif; ?>
            </div>

            <div class="property-details">
                <div class="property-price">
                    <!-- Price will be implemented when property data structure is known -->
                    <span class="price-placeholder">Price: To be implemented</span>
                </div>

                <div class="property-location">
                    <!-- Location will be implemented when property data structure is known -->
                    <span class="location-placeholder">Location: To be implemented</span>
                </div>

                <div class="property-description">
                    <?php if ( get_the_content() ) : ?>
                        <div class="description-content">
                            <?php echo wp_kses_post( get_the_content() ); ?>
                        </div>
                    <?php endif; ?>
                </div>

                <div class="property-contact">
                    <a href="mailto:contact@example.com" class="contact-button">Contact Agent</a>
                </div>
            </div>
        </main>

        <footer class="agent-mode-footer">
            <p>Agent Mode View - Property Sharing</p>
        </footer>
    </div>

    <?php wp_footer(); ?>
</body>
</html> 