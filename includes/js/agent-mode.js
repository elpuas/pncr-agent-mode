/**
 * PBCR Agent Mode - Swiper Gallery Implementation
 *
 * Implements synced Swiper gallery as per task-20.json requirements
 * Replicates CodeSandbox demo: https://codesandbox.io/p/sandbox/6j86wp
 */

document.addEventListener("DOMContentLoaded", function () {
	const mainSlider = document.querySelector(".agent-mode .swiper-main");
	const thumbSlider = document.querySelector(".agent-mode .swiper-thumbs");

	if (!mainSlider || !thumbSlider) {
		return;
	}

	// Initialize thumbnail slider first (like CodeSandbox demo)
	const swiperThumbs = new Swiper(thumbSlider, {
		spaceBetween: 10,
		slidesPerView: 4,
		freeMode: true,
		loop: true,
		watchSlidesProgress: true,
		navigation: {
			nextEl: ".thumbs-next",
			prevEl: ".thumbs-prev",
		},
		direction: "horizontal",
		breakpoints: {
			// Mobile responsive thumbnails
			320: {
				slidesPerView: 3,
				spaceBetween: 8,
			},
			// Tablet responsive thumbnails
			768: {
				slidesPerView: 4,
				spaceBetween: 10,
			},
			// Desktop responsive thumbnails
			1024: {
				slidesPerView: 6,
				spaceBetween: 12,
			},
		},
		// Keyboard accessibility
		keyboard: {
			enabled: true,
			onlyInViewport: true,
		},
		// Accessibility
		a11y: {
			prevSlideMessage: "Previous thumbnail",
			nextSlideMessage: "Next thumbnail",
		},
	});

	// Initialize main image slider (synchronized with thumbnails)
	const swiperMain = new Swiper(mainSlider, {
		spaceBetween: 10,
		slidesPerView: 1,
		// Connect with thumbnail slider (key feature from CodeSandbox demo)
		loop: true,
		autoplay: {
			delay: 3000,
			disableOnInteraction: true,
		},
		thumbs: {
			swiper: swiperThumbs,
		},
		// Navigation arrows
		navigation: {
			nextEl: ".agent-mode .swiper-button-next",
			prevEl: ".agent-mode .swiper-button-prev",
		},
		// Keyboard accessibility
		keyboard: {
			enabled: true,
			onlyInViewport: true,
		},
		// Touch/mouse interactions
		mousewheel: false,
		// Accessibility
		a11y: {
			prevSlideMessage: "Previous image",
			nextSlideMessage: "Next image",
		},
		// Responsive image handling
		autoHeight: false,
		// Smooth transitions
		speed: 300,
		effect: "slide",
	});

	// Enhanced keyboard shortcuts for better accessibility
	document.addEventListener("keydown", function (e) {
		// Only handle shortcuts when gallery is in viewport
		const galleryContainer = document.querySelector(
			".agent-mode .property-gallery"
		);
		if (!galleryContainer) return;

		const rect = galleryContainer.getBoundingClientRect();
		const isInViewport = rect.top >= 0 && rect.bottom <= window.innerHeight;

		if (isInViewport) {
			switch (e.key) {
				case "ArrowLeft":
					e.preventDefault();
					swiperMain.slidePrev();
					break;
				case "ArrowRight":
					e.preventDefault();
					swiperMain.slideNext();
					break;
				case "Home":
					e.preventDefault();
					swiperMain.slideTo(0);
					break;
				case "End":
					e.preventDefault();
					swiperMain.slideTo(swiperMain.slides.length - 1);
					break;
			}
		}
	});

	// Add touch support indicators for mobile devices
	if ("ontouchstart" in window) {
		const galleryContainer = document.querySelector(
			".agent-mode .property-gallery"
		);
		if (galleryContainer) {
			galleryContainer.classList.add("touch-device");
		}
	}
});
