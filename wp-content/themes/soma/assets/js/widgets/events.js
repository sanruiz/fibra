/**
 * Events Widget JavaScript Handler
 *
 * Handles AJAX loading and filtering for the Events Elementor widget.
 * Uses data attributes from widget container for configuration.
 *
 * @package Soma
 * @since   3.1.13
 */

(function ($) {
	'use strict';

	/**
	 * SVG Icons
	 */
	const arrow = `
		<svg width="18px" viewBox="0 0 46 42" xmlns="http://www.w3.org/2000/svg">
			<g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
				<g transform="translate(-733.000000, -553.000000)">
					<g transform="translate(734.000000, 553.052734)">
						<g stroke="currentColor" transform="translate(1.011719, -0.562098)">
							<line x1="21.1159338" y1="0.0967807903" x2="21.1159338" y2="41.6778482" stroke-width="2" stroke-linecap="square"></line>
							<polygon stroke-width="1" fill="currentColor" fill-rule="nonzero" transform="translate(21.115934, 32.962543) rotate(-270.000000) translate(-21.115934, -32.962543)" points="11.3693933 53.4967977 10.3282199 52.4556243 29.8213008 32.9625434 10.3282199 13.4694625 11.3693933 12.4282891 31.9036477 32.9625434"></polygon>
						</g>
					</g>
				</g>
			</g>
		</svg>
	`;

	const calendar = `
		<svg width="20px" height="20px" viewBox="0 0 34 33" xmlns="http://www.w3.org/2000/svg">
			<g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
				<g transform="translate(-529.000000, -338.000000)">
					<g transform="translate(530.000000, 338.936000)">
						<polygon stroke="currentColor" stroke-width="1.21196244" points="24.037255 30.4801685 0.201993739 30.4801685 0.201993739 3.00901994 32.1170045 3.00901994 32.1170045 22.1984252"></polygon>
						<polygon fill="currentColor" points="6.26180592 14.010003 9.71024304 14.010003 9.71024304 12.523329 6.26180592 12.523329"></polygon>
						<polygon fill="currentColor" points="14.6372743 14.010003 18.0861154 14.010003 18.0861154 12.523329 14.6372743 12.523329"></polygon>
						<polygon fill="currentColor" points="23.0127427 14.010003 26.4611798 14.010003 26.4611798 12.523329 23.0127427 12.523329"></polygon>
						<polygon fill="currentColor" points="6.26180592 18.4070027 9.71024304 18.4070027 9.71024304 16.9207327 6.26180592 16.9207327"></polygon>
						<polygon fill="currentColor" points="14.6372743 18.4070027 18.0861154 18.4070027 18.0861154 16.9207327 14.6372743 16.9207327"></polygon>
						<polygon fill="currentColor" points="23.0127427 18.4070027 26.4611798 18.4070027 26.4611798 16.9207327 23.0127427 16.9207327"></polygon>
						<polygon fill="currentColor" points="6.26180592 22.8044064 9.71024304 22.8044064 9.71024304 21.3177325 6.26180592 21.3177325"></polygon>
						<polygon fill="currentColor" points="14.6372743 22.8044064 18.0861154 22.8044064 18.0861154 21.3177325 14.6372743 21.3177325"></polygon>
						<polygon fill="currentColor" points="6.26180592 1.48639113 9.71024304 1.48639113 9.71024304 0.000121196244 6.26180592 0.000121196244"></polygon>
						<polygon fill="currentColor" points="14.6368703 1.48639113 18.0857114 1.48639113 18.0857114 0.000121196244 14.6368703 0.000121196244"></polygon>
						<polygon fill="currentColor" points="23.0127427 1.48639113 26.4611798 1.48639113 26.4611798 0.000121196244 23.0127427 0.000121196244"></polygon>
					</g>
				</g>
			</g>
		</svg>
	`;

	/**
	 * Fetch events from REST API
	 *
	 * @param {string} endpoint - REST API endpoint URL
	 * @param {Object} args     - Query arguments
	 * @param {Function} callback - Success callback
	 */
	function getEvents(endpoint, args, callback) {
		$.ajax({
			method: 'GET',
			url: endpoint,
			data: args,
			success: function (response) {
				callback(response);
			},
			error: function (xhr, status, error) {
				console.error('Events widget: Failed to fetch events', error);
				callback({ data: [], total: 0 });
			}
		});
	}

	/**
	 * Generate event card HTML
	 *
	 * @param {Object} data - Event data from API
	 * @return {string} HTML string
	 */
	function eventItem(data) {
		var link = '';
		if (data.file && data.file.url) {
			link = '<a target="_blank" rel="noopener noreferrer" href="' + data.file.url + '">' + 
				data.file_label + arrow + '</a>';
		}

		return '<div class="event show" data-filter="' + data.filter + '">' +
			'<span class="label">' + data.label + '</span>' +
			'<h3><span>' + data.formated_date + '</span>' + calendar + '</h3>' +
			'<p>' + data.description + '</p>' +
			link +
		'</div>';
	}

	/**
	 * Initialize Events widget
	 *
	 * @param {jQuery} $container - Widget container element
	 */
	function initEventsWidget($container) {
		var endpoint = $container.data('endpoint');
		var lang = $container.data('lang') || 'en';
		var order = $container.data('order') || 'ASC';
		var orderBy = $container.data('order-by') || 'custom_date';
		
		var $content = $container.find('.event-list');
		var $filterList = $container.find('.filters ul');
		var $loading = $container.find('.loading');
		var $mobileTitle = $container.find('.filters .mobile-title');
		var $filters = $container.find('.filters');

		// Build query args from data attributes
		var args = {
			lang: lang,
			order: order,
			order_by: orderBy
		};

		// Mobile filter toggle
		$mobileTitle.on('click', function () {
			$filters.toggleClass('open');
		});

		// Fetch events
		getEvents(endpoint, args, function (response) {
			$loading.hide();

			if (!response.data || response.data.length === 0) {
				$content.html('<p class="no-events">' + 
					(lang === 'es' ? 'No hay eventos disponibles.' : 'No events available.') + 
				'</p>');
				return;
			}

			// Collect unique filters and render events
			var filters = [];
			var html = '';

			response.data.forEach(function (item) {
				if (item.filter && filters.indexOf(item.filter) === -1) {
					filters.push(item.filter);
				}
				html += eventItem(item);
			});

			$content.html(html);

			// Add filter buttons
			if (filters.length > 0 && $filterList.length > 0) {
				filters.forEach(function (filter) {
					$filterList.append('<li><a data-filter="' + filter + '">' + filter + '</a></li>');
				});

				// Filter click handler
				$filterList.find('a').on('click', function (e) {
					e.preventDefault();
					var $this = $(this);
					var filterValue = $this.data('filter');

					// Update active state
					$filterList.find('a').removeClass('active');
					$this.addClass('active');

					// Filter events
					if (filterValue === 'all') {
						$content.find('.event').removeClass('hide').addClass('show');
					} else {
						$content.find('.event').each(function () {
							var $event = $(this);
							if ($event.data('filter') === filterValue) {
								$event.removeClass('hide').addClass('show');
							} else {
								$event.removeClass('show').addClass('hide');
							}
						});
					}
				});
			}
		});
	}

	/**
	 * Initialize on document ready
	 */
	$(document).ready(function () {
		$('.soma-events-widget').each(function () {
			initEventsWidget($(this));
		});
	});

	/**
	 * Reinitialize on Elementor frontend init (for editor preview)
	 */
	$(window).on('elementor/frontend/init', function () {
		if (typeof elementorFrontend !== 'undefined') {
			elementorFrontend.hooks.addAction('frontend/element_ready/soma-events.default', function ($scope) {
				initEventsWidget($scope.find('.soma-events-widget'));
			});
		}
	});

})(jQuery);
