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
	<title><?php echo esc_html( get_the_title() ); ?> - Agent View</title>
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
						<span class="price-label">Price:</span>
						<span class="price-value">$<?php echo esc_html( $property_data['price'] ); ?></span>
					</div>
				<?php endif; ?>

				<?php if ( ! empty( $property_data['ref_id'] ) ) : ?>
					<div class="property-ref">
						<span class="ref-label">Property ID:</span>
						<span class="ref-value"><?php echo esc_html( $property_data['ref_id'] ); ?></span>
					</div>
				<?php endif; ?>

				<?php if ( ! empty( $features ) ) : ?>
					<div class="property-features">
						<h3 class="features-title">Property Features</h3>
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
						<span class="size-label">Size:</span>
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

				<?php if ( get_the_content() ) : ?>
					<div class="property-description">
						<h3 class="description-title">Description</h3>
						<div class="description-content">
							<?php echo wp_kses_post( get_the_content() ); ?>
						</div>
					</div>
				<?php endif; ?>

				<?php if ( ! empty( $property_data['extras_raw'] ) && is_array( $property_data['extras_raw'] ) ) : ?>
					<div class="property-extras">
						<h3 class="extras-title">Additional Details</h3>
						<div class="extras-list">
							<?php foreach ( $property_data['extras_raw'] as $detail ) : ?>
								<?php if ( is_array( $detail ) && ! empty( $detail['detail_title'] ) && ! empty( $detail['detail_value'] ) ) : ?>
									<div class="extra-item">
										<span class="extra-label"><?php echo esc_html( $detail['detail_title'] ); ?>:</span>
										<span class="extra-value"><?php echo esc_html( $detail['detail_value'] ); ?></span>
									</div>
								<?php endif; ?>
							<?php endforeach; ?>
						</div>
					</div>
				<?php endif; ?>

				<?php if ( ! empty( $property_data['gallery_ids'] ) && is_array( $property_data['gallery_ids'] ) ) : ?>
					<div class="property-gallery">
						<h3 class="gallery-title">Property Gallery</h3>
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

				<div class="property-contact">
					<a href="mailto:contact@example.com" class="contact-button">
						<span class="contact-icon">✉</span>
						Contact Agent
					</a>
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
