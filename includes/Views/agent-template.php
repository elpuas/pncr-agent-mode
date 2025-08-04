<?php

/**
 * Agent Mode Template
 *
 * This template renders the minimal, unbranded view of a property for agent sharing.
 *
 * @package PBCRAgentMode
 */

// If this file is called directly, abort.
if (! defined('ABSPATH')) {
	exit;
}

// Get the current post.
global $post;

// Verify we have a valid property post.
if (! $post || 'property' !== get_post_type($post)) {
	// Return 404 if not a valid property.
	status_header(404);
	nocache_headers();
	include get_query_template('404');
	exit;
}

// Check if the property is published.
if ('publish' !== get_post_status($post)) {
	// Return 404 if not published.
	status_header(404);
	nocache_headers();
	include get_query_template('404');
	exit;
}

// Get property data using our helper function.
$property_data = pbcr_agent_get_property_data();
$features = \PBCRAgentMode\Helpers\PropertyData::get_formatted_features($property_data);

?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>

<head>
	<meta charset="<?php bloginfo('charset'); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="robots" content="noindex, nofollow">
	<title><?php echo esc_html(get_the_title()); ?> - <?php esc_html_e('Agent View', 'pbcr-agent-mode'); ?></title>

	<!-- Direct Swiper CSS inclusion -->
	<link rel="stylesheet" href="<?php echo PBCR_AGENT_MODE_PLUGIN_URL . 'includes/css/swiper-bundle.min.css'; ?>?v=<?php echo PBCR_AGENT_MODE_VERSION; ?>">
	<link rel="stylesheet" href="<?php echo PBCR_AGENT_MODE_PLUGIN_URL . 'includes/css/agent-style.css'; ?>?v=<?php echo PBCR_AGENT_MODE_VERSION; ?>">

	<?php wp_head(); ?>
</head>

<body class="agent-mode">
	<div class="agent-mode-container">
		<header class="agent-mode-header">
			<div>
				<?php if (! empty($property_data['breadcrumbs']) && is_array($property_data['breadcrumbs'])) : ?>
					<p class="status"><?php echo esc_html(implode(' › ', $property_data['breadcrumbs'])); ?></p>
				<?php endif; ?>
				<h1 class="property-title"><?php echo esc_html(get_the_title()); ?></h1>
			</div>
			<?php if (! empty($property_data['price']) || ! empty($property_data['status'])) : ?>
	<div class="property_price">
		<?php if (! empty($property_data['status'])) : ?>
			<p class="status"><?php echo esc_html($property_data['status']); ?></p>
		<?php endif; ?>

		<?php if (! empty($property_data['price'])) : ?>
					<p class="price">
						<span class="property-price-wrapper">
							<ins class="property-current-price">
								<?php
								$currency_prefix = ! empty($property_data['currency_prefix']) ? $property_data['currency_prefix'] : '$';
								echo esc_html($currency_prefix .  $property_data['price']); ?>
							</ins>

							<?php if (! empty($property_data['old_price'])) : ?>
								<del class="property-old-price">
									<?php
									$currency_prefix = ! empty($property_data['currency_prefix']) ? $property_data['currency_prefix'] : '$';
									echo esc_html($currency_prefix . $property_data['old_price']);
									?>
								</del>
							<?php endif; ?>

							<?php if (! empty($property_data['currency_suffix'])) : ?>
								<?php echo esc_html(' ' . $property_data['currency_suffix']); ?>
							<?php endif; ?>
						</span>
					</p>
				<?php endif; ?>
			</div>
		<?php endif; ?>
		</header>

		<main class="agent-mode-content">
			<div class="property-details">
				<?php if (! empty($property_data['gallery_urls']) && is_array($property_data['gallery_urls'])) : ?>
					<!-- Task-19: Swiper Gallery Implementation -->
					<div class="property-gallery">
						<div class="carousel-wrapper">
						<!-- Main Image Slider -->
							<div class="swiper-main swiper">
								<div class="swiper-wrapper">
									<?php foreach ($property_data['gallery_urls'] as $index => $image_url) : ?>
										<?php if ($image_url) : ?>
											<div class="swiper-slide">
												<img
													src="<?php echo esc_url($image_url); ?>"
													alt="<?php echo esc_attr(get_the_title() . ' - Image ' . ($index + 1)); ?>"
													class="gallery-main-image"
													loading="<?php echo $index === 0 ? 'eager' : 'lazy'; ?>">
											</div>
										<?php endif; ?>
									<?php endforeach; ?>
								</div><!-- Swiper Wrapper -->
								<!-- Navigation buttons -->
								<div class="swiper-button-next"></div>
								<div class="swiper-button-prev"></div>
							</div>
							<!-- Thumbnail Slider -->
							<div class="swiper-thumbs swiper">
								<div class="swiper-button-prev thumbs-prev"></div>
								<div class="swiper-button-next thumbs-next"></div>
								<div class="swiper-wrapper">
									<?php foreach ($property_data['gallery_urls'] as $index => $image_url) : ?>
										<?php if ($image_url) : ?>
											<div class="swiper-slide">
												<img
													src="<?php echo esc_url($image_url); ?>"
													alt="<?php echo esc_attr(get_the_title() . ' - Thumbnail ' . ($index + 1)); ?>"
													class="gallery-thumb-image"
													loading="lazy">
											</div>
										<?php endif; ?>
									<?php endforeach; ?>
								</div>
							</div>
						</div>
					</div><!-- Property Gallery -->
				<?php endif; ?>
				<?php if (! empty($features)) : ?>
					<div class="property-features">
						<div class="property-info-meta">
							<?php if (! empty($property_data['ref_id'])) : ?>
								<div class="property-ref">
									<span class="ref-label"><?php esc_html_e('Property ID:', 'pbcr-agent-mode'); ?></span>
									<span class="ref-value"><?php echo esc_html($property_data['ref_id']); ?></span>
								</div>
							<?php endif; ?>
						</div><!-- Property Info Meta -->
						<h3 class="features-title"><?php esc_html_e('Property Features', 'pbcr-agent-mode'); ?></h3>
						<div class="features-grid">
							<?php foreach ($features as $feature) : ?>
								<div class="feature-item">
									<span class="feature-icon feature-icon-<?php echo esc_attr($feature['icon']); ?>"></span>
									<span class="feature-value"><?php echo esc_html($feature['value']); ?></span>
									<span class="feature-label"><?php echo esc_html($feature['label']); ?></span>
								</div>
							<?php endforeach; ?>
							<?php if (! empty($property_data['size'])) : ?>
								<div class="feature-item">
									<span class="feature-icon feature-icon-size"></span>
									<span class="feature-value"><?php echo esc_html($property_data['size']); ?> m²</span>
									<span class="feature-label"><?php esc_html_e('Construcción', 'pbcr-agent-mode'); ?></span>
								</div>
							<?php endif; ?>
							<?php if (! empty($property_data['land_size'])) : ?>
								<div class="feature-item">
									<span class="feature-icon feature-icon-land"></span>
									<span class="feature-value">
										<?php echo esc_html($property_data['land_size']); ?>
										<?php if (! empty($property_data['land_unit'])) : ?>
											<?php echo esc_html(' ' . $property_data['land_unit']); ?>
										<?php else : ?>
											m²
										<?php endif; ?>
									</span>
									<span class="feature-label"><?php esc_html_e('Terreno', 'pbcr-agent-mode'); ?></span>
								</div>
							<?php endif; ?>
						</div>
					</div><!-- Property Features -->
				<?php endif; ?>

				<!-- Property Description -->
				<?php if (! empty($property_data['description'])) : ?>
					<div class="property-description">
						<h3 class="description-title"><?php esc_html_e('Description', 'pbcr-agent-mode'); ?></h3>
						<div class="description-content">
							<?php echo wp_kses_post($property_data['description']); ?>
						</div>
					</div>
				<?php elseif (get_the_content()) : ?>
					<!-- Fallback to get_the_content() for backward compatibility -->
					<div class="property-description">
						<h3 class="description-title"><?php esc_html_e('Description', 'pbcr-agent-mode'); ?></h3>
						<div class="description-content">
							<?php echo wp_kses_post(strip_shortcodes(get_the_content())); ?>
						</div>
					</div>
				<?php endif; ?>

				<!-- Additional Features Section -->
				<?php if (! empty($property_data['features']) && is_array($property_data['features'])) : ?>
					<div class="property-extra-features">
						<h3 class="extra-features-title"><?php esc_html_e('Caracteristicas', 'pbcr-agent-mode'); ?></h3>
						<ul class="extra-features-list">
							<?php foreach ($property_data['features'] as $feature_name) : ?>
								<li class="extra-feature-item">
									<span class="feature-bullet">•</span>
									<span class="feature-text"><?php echo esc_html($feature_name); ?></span>
								</li>
							<?php endforeach; ?>
						</ul>
					</div>
				<?php endif; ?>

				<?php
				// Direct Additional Details Implementation - Try different data access methods
				$details_true = get_post_meta(get_the_ID(), 'REAL_HOMES_additional_details_list', true);
				$details_false = get_post_meta(get_the_ID(), 'REAL_HOMES_additional_details_list', false);

				// Try the true version first, then false if that doesn't work
				$details = ! empty($details_true) ? $details_true : (! empty($details_false[0]) ? $details_false[0] : null);
				$details = maybe_unserialize($details);

				if (is_array($details) && ! empty($details)) : ?>
					<section class="property-additional-details-direct">
						<h3><?php esc_html_e('Additional Details', 'pbcr-agent-mode'); ?></h3>
						<dl class="details-list">
							<?php foreach ($details as $item) :
								if (is_array($item) && ! empty($item[0]) && ! empty($item[1])) : ?>
									<dt><?php echo esc_html($item[0]); ?></dt>
									<dd><?php echo esc_html($item[1]); ?></dd>
							<?php endif;
							endforeach; ?>
						</dl>
					</section>
				<?php endif; ?>

				<?php if (! empty($property_data['video_data']) && ! empty($property_data['video_data']['url'])) : ?>
					<div class="property-video">
						<h3 class="video-title"><?php esc_html_e('Property Video', 'pbcr-agent-mode'); ?></h3>
						<div class="video-container">
							<?php if (! empty($property_data['video_data']['image_id'])) : ?>
								<div class="video-thumbnail">
									<?php
									$video_image_url = wp_get_attachment_image_url($property_data['video_data']['image_id'], 'medium');
									if ($video_image_url) :
									?>
										<img
											src="<?php echo esc_url($video_image_url); ?>"
											alt="<?php esc_attr_e('Video Thumbnail', 'pbcr-agent-mode'); ?>"
											class="video-thumb-image">
									<?php endif; ?>
								</div>
							<?php endif; ?>
							<div class="video-link">
								<a
									href="<?php echo esc_url($property_data['video_data']['url']); ?>"
									target="_blank"
									rel="noopener"
									class="video-play-button">
									<span class="video-icon">▶</span>
									<?php esc_html_e('Watch Property Video', 'pbcr-agent-mode'); ?>
								</a>
							</div>
						</div>
					</div>
				<?php endif; ?>
			</div>
		</main>
	</div>

	<!-- Direct Swiper JS inclusion -->
	<script src="<?php echo PBCR_AGENT_MODE_PLUGIN_URL . 'includes/js/swiper-bundle.min.js'; ?>?v=<?php echo PBCR_AGENT_MODE_VERSION; ?>"></script>
	<script src="<?php echo PBCR_AGENT_MODE_PLUGIN_URL . 'includes/js/agent-mode.js'; ?>?v=<?php echo PBCR_AGENT_MODE_VERSION; ?>"></script>
	<script>
		// (function () {
		// 	if (location.search.includes('agent_view=1')) {
		// 		const slug = 'mc-' + Math.random().toString(36).slice(2, 6)
		// 		history.replaceState({}, '', '/' + slug)
		// 	}
		// })()
	</script>
</body>

</html>
