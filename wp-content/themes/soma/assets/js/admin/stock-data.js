/**
 * Stock Data Admin - API testing and status display.
 *
 * @package Soma\Admin
 * @since   3.1.10
 */

/* global jQuery, ajaxurl, somaStockData */

(function($) {
	'use strict';

	/**
	 * Stock Data Admin Module.
	 */
	var StockDataAdmin = {

		/**
		 * Configuration from PHP.
		 */
		config: {},

		/**
		 * DOM elements.
		 */
		elements: {
			container: null,
			infoContainer: null,
			testBtn: null
		},

		/**
		 * Initialize the module.
		 */
		init: function() {
			// Get config from localized script data.
			this.config = window.somaStockData || {};

			// Find the status container.
			this.elements.container = $('#soma-stock-data-status');
			if (!this.elements.container.length) {
				return;
			}

			this.buildUI();
			this.bindEvents();
			this.loadStatus();
		},

		/**
		 * Build the UI elements.
		 */
		buildUI: function() {
			var html = '<div class="soma-stock-status-wrapper">' +
				'<div class="soma-stock-status-info"></div>' +
				'<button type="button" class="button button-primary soma-test-api-btn">' +
				this.config.i18n.testButton +
				'</button>' +
				'</div>';

			this.elements.container.html(html);
			this.elements.infoContainer = this.elements.container.find('.soma-stock-status-info');
			this.elements.testBtn = this.elements.container.find('.soma-test-api-btn');
		},

		/**
		 * Bind event handlers.
		 */
		bindEvents: function() {
			var self = this;

			this.elements.testBtn.on('click', function() {
				self.testApiConnection();
			});
		},

		/**
		 * Test the API connection.
		 */
		testApiConnection: function() {
			var self = this;

			this.elements.testBtn
				.prop('disabled', true)
				.text(this.config.i18n.testing);

			$.ajax({
				url: ajaxurl,
				type: 'POST',
				data: {
					action: 'soma_test_stock_api',
					nonce: this.config.nonce
				},
				success: function(response) {
					if (response.success) {
						self.showMessage('success', response.data.message);
					} else {
						self.showMessage('error', response.data.message);
					}
					self.loadStatus();
				},
				error: function() {
					self.showMessage('error', self.config.i18n.requestFailed);
				},
				complete: function() {
					self.elements.testBtn
						.prop('disabled', false)
						.text(self.config.i18n.testButton);
				}
			});
		},

		/**
		 * Load the current sync status.
		 */
		loadStatus: function() {
			var self = this;

			$.ajax({
				url: ajaxurl,
				type: 'POST',
				data: {
					action: 'soma_get_stock_status',
					nonce: this.config.nonce
				},
				success: function(response) {
					if (response.success) {
						self.renderStatus(response.data);
					}
				}
			});
		},

		/**
		 * Render the status information.
		 *
		 * @param {Object} data Status data from server.
		 */
		renderStatus: function(data) {
			var html = '';
			var i18n = this.config.i18n;

			if (data.sync_status) {
				var statusClass = data.sync_status.success ? 'success' : 'error';
				var statusIcon = data.sync_status.success ? '✓' : '✗';

				html += '<p><strong>' + i18n.lastSync + '</strong> ' +
					data.sync_status.datetime +
					' <span class="soma-status-' + statusClass + '">' + statusIcon + '</span></p>';
				html += '<p class="soma-status-message soma-status-' + statusClass + '">' +
					data.sync_status.message + '</p>';
			} else {
				html += '<p class="soma-status-warning">' + i18n.noSync + '</p>';
			}

			if (data.stock_data) {
				var price = parseFloat(data.stock_data.price).toFixed(2);
				html += '<p><strong>' + i18n.currentData + '</strong> ' +
					data.stock_data.symbol + ' = ' + price + ' ' + data.stock_data.currency + '</p>';

				var timestamp = new Date(data.stock_data.timestamp * 1000);
				html += '<p><strong>' + i18n.marketTime + '</strong> ' +
					timestamp.toLocaleString() + '</p>';
			}

			if (data.next_run) {
				html += '<p><strong>' + i18n.nextSync + '</strong> ' +
					data.next_run + ' UTC (' + i18n.every + ' ' + data.interval + 'h)</p>';
			}

			this.elements.infoContainer.html(html);
		},

		/**
		 * Show a notification message.
		 *
		 * @param {string} type    Message type: 'success' or 'error'.
		 * @param {string} message Message text.
		 */
		showMessage: function(type, message) {
			var alertClass = type === 'success' ? 'notice-success' : 'notice-error';
			var $alert = $('<div class="notice ' + alertClass + ' is-dismissible"><p>' + message + '</p></div>');

			this.elements.container.before($alert);

			setTimeout(function() {
				$alert.fadeOut(function() {
					$(this).remove();
				});
			}, 5000);
		}
	};

	// Initialize on document ready.
	$(document).ready(function() {
		StockDataAdmin.init();
	});

})(jQuery);
