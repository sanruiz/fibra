/**
 * PortfolioGallery Slick Initialization
 *
 * Initializes Slick carousel for portfolio gallery widgets.
 *
 * @package Soma
 * @since   3.1.17
 */

(function ($) {
	'use strict';

	/**
	 * Initialize portfolio gallery sliders.
	 */
	function initPortfolioGallery() {
		$('.soma-portfolio-gallery').each(function () {
			var $gallery = $(this);
			var $slider = $gallery.find('.soma-portfolio-gallery__slider');

			// Skip if already initialized.
			if ($slider.hasClass('slick-initialized')) {
				return;
			}

			// Get options from data attribute.
			var options = $gallery.data('slick-options') || {};

			// Default options.
			var defaults = {
				arrows: true,
				dots: false,
				autoplay: false,
				autoplaySpeed: 5000,
				infinite: true,
				slidesToShow: 1,
				slidesToScroll: 1,
				fade: true,
				cssEase: 'ease-in-out',
				adaptiveHeight: false,
				lazyLoad: 'ondemand',
			};

			// Merge options.
			var slickOptions = $.extend({}, defaults, options);

			// Initialize Slick with error handling.
			try {
				$slider.slick(slickOptions);
			} catch (e) {
				console.warn('SOMA Portfolio Gallery: Slick initialization failed', e);
			}
		});
	}

	/**
	 * Reinitialize on Elementor frontend init.
	 *
	 * @param {jQuery} $scope The widget scope.
	 */
	function onElementorFrontendInit($scope) {
		var $gallery = $scope.find('.soma-portfolio-gallery');
		if ($gallery.length) {
			// Small delay to ensure DOM is ready.
			setTimeout(initPortfolioGallery, 100);
		}
	}

	// Initialize on document ready.
	$(document).ready(function () {
		initPortfolioGallery();
	});

	// Initialize on Elementor frontend.
	$(window).on('elementor/frontend/init', function () {
		if (typeof elementorFrontend !== 'undefined') {
			elementorFrontend.hooks.addAction(
				'frontend/element_ready/soma-portfolio-gallery.default',
				onElementorFrontendInit
			);
		}
	});
})(jQuery);
