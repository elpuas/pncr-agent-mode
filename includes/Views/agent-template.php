<?php
/**
 * Agent Mode Template
 *
 * This template renders the minimal, unbranded view of a property for agent sharing.
 *
 * @package PBCRAgentMode
 */

// If this file is called directly, abort.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Get the current post.
global $post;

// Verify we have a valid property post.
if ( ! $post || 'property' !== get_post_type( $post ) ) {
	// Return 404 if not a valid property.
	status_header( 404 );
	nocache_headers();
	include get_query_template( '404' );
	exit;
}

// Check if the property is published.
if ( 'publish' !== get_post_status( $post ) ) {
	// Return 404 if not published.
	status_header( 404 );
	nocache_headers();
	include get_query_template( '404' );
	exit;
}



?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="robots" content="noindex, nofollow">
	<title><?php echo esc_html( get_the_title() ); ?> - <?php esc_html_e( 'Agent View', 'pbcr-agent-mode' ); ?></title>
	<?php wp_head(); ?>
</head>
<body class="agent-mode">
	<div class="agent-mode-container">
		<header class="agent-mode-header">
			<h1 class="property-title"><?php echo esc_html( get_the_title() ); ?></h1>
		</header>

		<main class="agent-mode-content">
			<?php
			// Get property data using our helper function.
			$property_data = pbcr_agent_get_property_data();
			$features = \PBCRAgentMode\Helpers\PropertyData::get_formatted_features( $property_data );
			?>

			<div class="property-image">
				<?php if ( has_post_thumbnail() ) : ?>
					<img src="<?php echo esc_url( get_the_post_thumbnail_url( get_the_ID(), 'large' ) ); ?>"
					alt="<?php echo esc_attr( get_the_title() ); ?>"
					class="featured-image">
				<?php endif; ?>
			</div>

			<div class="property-details">
				<?php if ( ! empty( $property_data['price'] ) ) : ?>
					<div class="property-price">
						<span class="price-label"><?php esc_html_e( 'Price:', 'pbcr-agent-mode' ); ?></span>
						<span class="price-value">$<?php echo esc_html( $property_data['price'] ); ?></span>
					</div>
				<?php endif; ?>

				<?php if ( ! empty( $property_data['ref_id'] ) ) : ?>
					<div class="property-ref">
						<span class="ref-label"><?php esc_html_e( 'Property ID:', 'pbcr-agent-mode' ); ?></span>
						<span class="ref-value"><?php echo esc_html( $property_data['ref_id'] ); ?></span>
					</div>
				<?php endif; ?>

				<?php if ( ! empty( $features ) ) : ?>
					<div class="property-features">
						<h3 class="features-title"><?php esc_html_e( 'Property Features', 'pbcr-agent-mode' ); ?></h3>
						<div class="features-grid">
							<?php foreach ( $features as $feature ) : ?>
								<div class="feature-item">
									<span class="feature-icon feature-icon-<?php echo esc_attr( $feature['icon'] ); ?>"></span>
									<span class="feature-value"><?php echo esc_html( $feature['value'] ); ?></span>
									<span class="feature-label"><?php echo esc_html( $feature['label'] ); ?></span>
								</div>
							<?php endforeach; ?>
						</div>
					</div>
				<?php endif; ?>

				<?php if ( ! empty( $property_data['size'] ) ) : ?>
					<div class="property-size">
						<span class="size-label"><?php esc_html_e( 'Size:', 'pbcr-agent-mode' ); ?></span>
						<span class="size-value"><?php echo esc_html( $property_data['size'] ); ?> <?php echo esc_html( $property_data['size_unit'] ); ?></span>
					</div>
				<?php endif; ?>

				<?php if ( ! empty( $property_data['address'] ) ) : ?>
					<div class="property-location">
						<span class="location-icon">📍</span>
						<span class="location-value"><?php echo esc_html( $property_data['address'] ); ?></span>
					</div>
				<?php else : ?>
					<?php
					// Fallback to location taxonomy.
					$locations = get_the_terms( get_the_ID(), 'property-city' );
					if ( ! empty( $locations ) && ! is_wp_error( $locations ) ) :
						$location_names = wp_list_pluck( $locations, 'name' );
						?>
						<div class="property-location">
							<span class="location-icon">📍</span>
							<span class="location-value"><?php echo esc_html( implode( ', ', $location_names ) ); ?></span>
						</div>
					<?php endif; ?>
				<?php endif; ?>

				<?php if ( ! empty( $property_data['location'] ) ) : ?>
					<div class="property-location-meta">
						<span class="location-meta-label"><?php esc_html_e( 'Location:', 'pbcr-agent-mode' ); ?></span>
						<span class="location-meta-value"><?php echo esc_html( $property_data['location'] ); ?></span>
					</div>
				<?php endif; ?>

				<?php if ( ! empty( $property_data['is_featured'] ) && '1' === $property_data['is_featured'] ) : ?>
					<div class="property-featured-badge">
						<span class="featured-icon">⭐</span>
						<span class="featured-text"><?php esc_html_e( 'Featured Property', 'pbcr-agent-mode' ); ?></span>
					</div>
				<?php endif; ?>

				<?php if ( ! empty( $property_data['in_slider'] ) && '1' === $property_data['in_slider'] ) : ?>
					<div class="property-slider-badge">
						<span class="slider-icon">🎞️</span>
						<span class="slider-text"><?php esc_html_e( 'Featured in Slider', 'pbcr-agent-mode' ); ?></span>
					</div>
				<?php endif; ?>

				<?php if ( get_the_content() ) : ?>
					<div class="property-description">
						<h3 class="description-title"><?php esc_html_e( 'Description', 'pbcr-agent-mode' ); ?></h3>
						<div class="description-content">
							<?php echo wp_kses_post( get_the_content() ); ?>
						</div>
					</div>
				<?php endif; ?>

				<?php
				// Direct Additional Details Implementation - Try different data access methods
				$details_true = get_post_meta( get_the_ID(), 'REAL_HOMES_additional_details_list', true );
				$details_false = get_post_meta( get_the_ID(), 'REAL_HOMES_additional_details_list', false );

				// Try the true version first, then false if that doesn't work
				$details = ! empty( $details_true ) ? $details_true : ( ! empty( $details_false[0] ) ? $details_false[0] : null );
				$details = maybe_unserialize( $details );

				if ( is_array( $details ) && ! empty( $details ) ) : ?>
					<section class="property-additional-details-direct">
						<h3><?php esc_html_e( 'Additional Details', 'pbcr-agent-mode' ); ?></h3>
						<dl class="details-list">
							<?php foreach ( $details as $item ) :
								if ( is_array( $item ) && ! empty( $item[0] ) && ! empty( $item[1] ) ) : ?>
									<dt><?php echo esc_html( $item[0] ); ?></dt>
									<dd><?php echo esc_html( $item[1] ); ?></dd>
								<?php endif;
							endforeach; ?>
						</dl>
					</section>
				<?php endif; ?>

				<?php if ( ! empty( $property_data['gallery_ids'] ) && is_array( $property_data['gallery_ids'] ) ) : ?>
					<div class="property-gallery">
						<h3 class="gallery-title"><?php esc_html_e( 'Property Gallery', 'pbcr-agent-mode' ); ?></h3>
						<div class="gallery-grid">
							<?php foreach ( array_slice( $property_data['gallery_ids'], 0, 6 ) as $image_id ) : ?>
								<?php
								$image_url = wp_get_attachment_image_url( $image_id, 'medium' );
								$image_full_url = wp_get_attachment_image_url( $image_id, 'large' );
								$image_alt = get_post_meta( $image_id, '_wp_attachment_image_alt', true );
								if ( $image_url ) :
									?>
									<div class="gallery-item">
										<a href="<?php echo esc_url( $image_full_url ); ?>" target="_blank" rel="noopener">
											<img src="<?php echo esc_url( $image_url ); ?>"
											alt="<?php echo esc_attr( $image_alt ? $image_alt : get_the_title() . ' - Gallery Image' ); ?>"
											class="gallery-image">
										</a>
									</div>
								<?php endif; ?>
							<?php endforeach; ?>
						</div>
					</div>
				<?php endif; ?>

				<?php if ( ! empty( $property_data['video_data'] ) && ! empty( $property_data['video_data']['url'] ) ) : ?>
					<div class="property-video">
						<h3 class="video-title"><?php esc_html_e( 'Property Video', 'pbcr-agent-mode' ); ?></h3>
						<div class="video-container">
							<?php if ( ! empty( $property_data['video_data']['image_id'] ) ) : ?>
								<div class="video-thumbnail">
									<?php
									$video_image_url = wp_get_attachment_image_url( $property_data['video_data']['image_id'], 'medium' );
									if ( $video_image_url ) :
										?>
										<img
											src="<?php echo esc_url( $video_image_url ); ?>"
											alt="<?php esc_attr_e( 'Video Thumbnail', 'pbcr-agent-mode' ); ?>"
											class="video-thumb-image"
										>
									<?php endif; ?>
								</div>
							<?php endif; ?>
							<div class="video-link">
								<a href="<?php echo esc_url( $property_data['video_data']['url'] ); ?>"
								   target="_blank"
								   rel="noopener"
								   class="video-play-button">
									<span class="video-icon">▶</span>
									<?php esc_html_e( 'Watch Property Video', 'pbcr-agent-mode' ); ?>
								</a>
							</div>
						</div>
					</div>
				<?php endif; ?>

				<?php if ( ! empty( $property_data['agents'] ) && is_array( $property_data['agents'] ) ) : ?>
					<div class="property-agents">
						<h3 class="agents-title"><?php esc_html_e( 'Assigned Agents', 'pbcr-agent-mode' ); ?></h3>
						<div class="agents-list">
							<?php foreach ( $property_data['agents'] as $agent ) : ?>
								<div class="agent-item">
									<?php if ( ! empty( $agent['avatar_url'] ) ) : ?>
										<div class="agent-avatar">
											<img src="<?php echo esc_url( $agent['avatar_url'] ); ?>"
											     alt="<?php echo esc_attr( $agent['name'] ); ?>"
											     class="agent-avatar-image">
										</div>
									<?php endif; ?>
									<div class="agent-info">
										<span class="agent-name"><?php echo esc_html( $agent['name'] ); ?></span>
										<?php if ( ! empty( $agent['email'] ) ) : ?>
											<span class="agent-email">
												<a href="mailto:<?php echo esc_attr( $agent['email'] ); ?>">
													<?php echo esc_html( $agent['email'] ); ?>
												</a>
											</span>
										<?php endif; ?>
									</div>
								</div>
							<?php endforeach; ?>
						</div>
					</div>
				<?php endif; ?>

				<?php if ( ! empty( $property_data['map_data'] ) && is_array( $property_data['map_data'] ) ) : ?>
					<div class="property-map">
						<h3 class="map-title"><?php esc_html_e( 'Location Map', 'pbcr-agent-mode' ); ?></h3>
						<div class="map-info">
							<?php if ( ! empty( $property_data['map_data']['lat'] ) && ! empty( $property_data['map_data']['lng'] ) ) : ?>
								<p class="map-coordinates">
									<span class="coordinates-label"><?php esc_html_e( 'Coordinates:', 'pbcr-agent-mode' ); ?></span>
									<span class="coordinates-value">
										<?php echo esc_html( $property_data['map_data']['lat'] ); ?>,
										<?php echo esc_html( $property_data['map_data']['lng'] ); ?>
									</span>
								</p>
							<?php else : ?>
								<p class="map-fallback"><?php esc_html_e( 'Map data not available', 'pbcr-agent-mode' ); ?></p>
							<?php endif; ?>
						</div>
					</div>
				<?php endif; ?>

				<div class="property-contact">
					<a href="mailto:contact@example.com" class="contact-button">
						<span class="contact-icon">✉</span>
						<?php esc_html_e( 'Contact Agent', 'pbcr-agent-mode' ); ?>
					</a>
				</div>
			</div>
		</main>

		<footer class="agent-mode-footer">
			<p><?php esc_html_e( 'Agent Mode View - Property Sharing', 'pbcr-agent-mode' ); ?></p>
		</footer>
	</div>

	<?php wp_footer(); ?>
</body>
</html>
