/**
 * TextWithReadMore Widget JavaScript
 *
 * Handles expand/collapse functionality for text content.
 *
 * @package Soma
 * @since   3.1.17
 */

(function ($) {
	'use strict';

	/**
	 * Initialize TextWithReadMore widgets.
	 */
	function initTextWithReadMore() {
		$('.soma-text-read-more').each(function () {
			const $widget = $(this);

			// Skip if already initialized.
			if ($widget.data('initialized')) {
				return;
			}

			const $content = $widget.find('.soma-text-read-more__content');
			const $wrapper = $widget.find('.soma-text-read-more__wrapper');
			const $toggle = $widget.find('.soma-text-read-more__toggle');
			const $toggleText = $widget.find('.soma-text-read-more__toggle-text');

			// Get settings from data attributes.
			const maxLines = parseInt($widget.data('max-lines'), 10) || 5;
			const lineHeight = parseFloat($widget.data('line-height')) || 1.6;
			const lineUnit = $widget.data('line-unit') || 'em';
			const animationDuration = parseInt($widget.data('animation-duration'), 10) || 300;
			const readMoreText = $widget.data('read-more') || 'Read More';
			const readLessText = $widget.data('read-less') || 'Read Less';

			// Calculate max height based on lines.
			const fontSize = parseFloat($content.css('font-size'));
			let lineHeightPx;

			if (lineUnit === 'em') {
				lineHeightPx = fontSize * lineHeight;
			} else {
				lineHeightPx = lineHeight;
			}

			const collapsedHeight = Math.ceil(lineHeightPx * maxLines);
			const fullHeight = $content[0].scrollHeight;

			// Check if content exceeds max lines.
			if (fullHeight <= collapsedHeight) {
				$widget.addClass('no-overflow');
				$widget.data('initialized', true);
				return;
			}

			// Set initial collapsed state.
			$wrapper.css({
				maxHeight: collapsedHeight + 'px',
				transition: 'max-height ' + animationDuration + 'ms ease-out'
			});

			$content.attr('aria-expanded', 'false');
			$toggle.attr('aria-expanded', 'false');

			// Toggle handler.
			$toggle.on('click', function () {
				const isExpanded = $widget.hasClass('is-expanded');

				if (isExpanded) {
					// Collapse.
					$widget.removeClass('is-expanded');
					$wrapper.css('maxHeight', collapsedHeight + 'px');
					$content.attr('aria-expanded', 'false');
					$toggle.attr('aria-expanded', 'false');
					$toggleText.text(readMoreText);
				} else {
					// Expand.
					$widget.addClass('is-expanded');
					$wrapper.css('maxHeight', fullHeight + 'px');
					$content.attr('aria-expanded', 'true');
					$toggle.attr('aria-expanded', 'true');
					$toggleText.text(readLessText);
				}
			});

			// Mark as initialized.
			$widget.data('initialized', true);
		});
	}

	// Initialize after fonts are loaded to ensure accurate height calculations.
	$(document).ready(function () {
		var timeoutFired = false;
		var fontsReady = false;

		function initOnTimeout() {
			if (!timeoutFired) {
				timeoutFired = true;
				initTextWithReadMore();
			}
		}

		function initOnFontsReady() {
			if (!fontsReady) {
				fontsReady = true;
				// If timeout already fired, re-initialize to get accurate measurements with loaded fonts.
				if (timeoutFired) {
					$('.soma-text-read-more').removeData('initialized').removeClass('no-overflow');
				}
				initTextWithReadMore();
			}
		}

		// Use document.fonts API if available, otherwise fallback to window.load.
		if (document.fonts && document.fonts.ready) {
			document.fonts.ready.then(initOnFontsReady);
			// Safety timeout: initialize after 2 seconds if fonts.ready doesn't resolve.
			// This handles edge cases on mobile Safari where fonts.ready may hang.
			setTimeout(initOnTimeout, 2000);
		} else {
			// Fallback for older browsers.
			$(window).on('load', initOnFontsReady);
		}
	});

	// Re-initialize for Elementor editor.
	$(window).on('elementor/frontend/init', function () {
		if (typeof elementorFrontend !== 'undefined' && elementorFrontend.hooks) {
			elementorFrontend.hooks.addAction(
				'frontend/element_ready/soma-text-with-read-more.default',
				function ($scope) {
					$scope.find('.soma-text-read-more').removeData('initialized');
					initTextWithReadMore();
				}
			);
		}
	});

	// Handle window resize (recalculate heights).
	let resizeTimeout;
	$(window).on('resize', function () {
		clearTimeout(resizeTimeout);
		resizeTimeout = setTimeout(function () {
			$('.soma-text-read-more').each(function () {
				const $widget = $(this);

				// Only recalculate if not expanded and not no-overflow.
				if (!$widget.hasClass('is-expanded') && !$widget.hasClass('no-overflow')) {
					$widget.removeData('initialized');
				}
			});
			initTextWithReadMore();
		}, 250);
	});

})(jQuery);
