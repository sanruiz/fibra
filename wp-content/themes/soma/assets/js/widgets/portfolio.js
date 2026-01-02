/**
 * Portfolio Widget JavaScript
 *
 * Handles AJAX loading, filtering, view switching, and infinite scroll
 * for the Elementor Portfolio widget.
 *
 * @package Soma
 * @since 3.1.8
 */

(function ($) {
	'use strict';

	/**
	 * Initialize Portfolio Widget
	 *
	 * @param {jQuery} $widget - The widget container element.
	 */
	function initPortfolioWidget($widget) {
		// Widget state.
		var state = {
			mainCategory: $widget.data('main-category') || '',
			postsPerPage: parseInt($widget.data('posts-per-page'), 10) || 10,
			lang: $widget.data('lang') || 'en',
			orderby: $widget.data('orderby') || 'year',
			order: $widget.data('order') || 'DESC',
			showYear: $widget.data('show-year') === true || $widget.data('show-year') === 'true',
			showCity: $widget.data('show-city') === true || $widget.data('show-city') === 'true',
			preFilter: $widget.data('pre-filter') || '',
			allText: $widget.data('all-text') || 'All',
			showFilters: $widget.data('show-filters') === true || $widget.data('show-filters') === 'true',
			currentCategory: '', // Current category slug for filtering
			offset: 0,
			total: 0,
			loading: false,
			allLoaded: false,
			allProjects: [], // Store all projects for client-side sorting/pagination
			categories: [] // Available categories from API
		};

		// Cache DOM elements.
		var $projectsContainer = $widget.find('.projects');
		var $loaderContainer = $widget.find('.loader-container');
		var $filtersListDesktop = $widget.find('.portfolio-filters.desk .filters-list');
		var $filtersListMobile = $widget.find('.portfolio-filters.movil .IteamView');
		var $viewModeList = $widget.find('.view-mode .list');
		var $viewModeGrid = $widget.find('.view-mode .grid');
		var $mobileViewAll = $widget.find('.ViewAll');
		var $mobileItemView = $widget.find('.IteamView');

		/**
		 * Get projects from REST API
		 *
		 * @param {string} category - Category slug to filter by.
		 * @param {boolean} fetchAll - Whether to fetch all posts.
		 * @param {boolean} includeCategories - Whether to include categories in response.
		 * @return {Promise} AJAX promise.
		 */
		function getProjects(category, fetchAll, includeCategories) {
			var data = {
				posts_per_page: fetchAll ? -1 : state.postsPerPage,
				offset: 0,
				orderby: state.orderby !== 'year' ? state.orderby : 'date',
				order: state.order,
				lang: state.lang
			};

			// Add category filter if not "all".
			if (category && category !== 'all') {
				data.category = category;
			}

			// Include categories on initial load.
			if (includeCategories) {
				data.include_categories = 'true';
			}

			return $.ajax({
				type: 'GET',
				url: window.wpApiSettings?.root 
					? window.wpApiSettings.root + 'soma/portfolio' 
					: '/wp-json/soma/portfolio',
				data: data
			});
		}

		/**
		 * Sort projects by year (client-side)
		 *
		 * @param {Array} projects - Array of project objects.
		 * @return {Array} Sorted projects.
		 */
		function sortProjectsByYear(projects) {
			return projects.sort(function(a, b) {
				var yearA = parseInt(a.year, 10) || 0;
				var yearB = parseInt(b.year, 10) || 0;
				return state.order === 'DESC' ? yearB - yearA : yearA - yearB;
			});
		}

		/**
		 * Generate HTML for a single project
		 *
		 * @param {Object} project - Project data from REST API.
		 * @return {string} HTML string.
		 */
		function generateProjectHTML(project) {
			var yearHTML = state.showYear && project.year 
				? '<span class="year">' + escapeHtml(project.year) + '</span>' 
				: '';
			var cityHTML = state.showCity && project.city 
				? '<span class="city">' + escapeHtml(project.city) + '</span>' 
				: '';

			return '<a href="' + escapeHtml(project.permalink) + '" class="project">' +
				'<div class="info">' +
					yearHTML +
					'<div class="title"><h3>' + escapeHtml(project.title) + '</h3></div>' +
					cityHTML +
				'</div>' +
				'<div class="image">' +
					'<img src="' + escapeHtml(project.featured_image) + '" ' +
						'alt="' + escapeHtml(project.title) + '" loading="lazy">' +
				'</div>' +
			'</a>';
		}

		/**
		 * Escape HTML entities for safe output
		 *
		 * @param {string} str - String to escape.
		 * @return {string} Escaped string.
		 */
		function escapeHtml(str) {
			if (!str) return '';
			var div = document.createElement('div');
			div.appendChild(document.createTextNode(str));
			return div.innerHTML;
		}

		/**
		 * Render projects to container
		 *
		 * @param {Array} projects - Array of project objects.
		 * @param {boolean} append - Whether to append or replace.
		 */
		function renderProjects(projects, append) {
			var html = projects.map(generateProjectHTML).join('');
			
			if (append) {
				$projectsContainer.append(html);
			} else {
				$projectsContainer.html(html);
			}
		}

		/**
		 * Render filters dynamically from API categories
		 *
		 * @param {Array} categories - Array of category objects from API.
		 */
		function renderFilters(categories) {
			if (!state.showFilters || !categories || categories.length === 0) {
				return;
			}

			state.categories = categories;

			// Determine which filter should be active.
			var activeSlug = state.preFilter || 'all';

			// Build filter HTML.
			var filtersHTML = '';

			// Add "All" filter.
			var allActiveClass = (activeSlug === 'all' || activeSlug === '') ? ' active' : '';
			filtersHTML += '<div class="filter' + allActiveClass + '" data-category="all">' + 
				escapeHtml(state.allText) + '</div>';

			// Add category filters.
			categories.forEach(function(cat) {
				var activeClass = (cat.slug === activeSlug) ? ' active' : '';
				filtersHTML += '<div class="filter' + activeClass + '" data-category="' + 
					escapeHtml(cat.slug) + '">' + escapeHtml(cat.name) + '</div>';
			});

			// Render to both desktop and mobile containers.
			$filtersListDesktop.html(filtersHTML);
			$filtersListMobile.html(filtersHTML);

			// Bind filter click events.
			bindFilterEvents();

			// If there's a pre-filter, set the current category.
			if (state.preFilter && state.preFilter !== 'all') {
				state.currentCategory = state.preFilter;
			}
		}

		/**
		 * Bind filter click events
		 */
		function bindFilterEvents() {
			$widget.find('.portfolio-filters .filter').off('click').on('click', handleFilterClick);
		}

		/**
		 * Load projects (initial or more)
		 *
		 * @param {boolean} append - Whether to append to existing projects.
		 * @param {boolean} isInitial - Whether this is the initial load.
		 */
		function loadProjects(append, isInitial) {
			if (state.loading || state.allLoaded) {
				return;
			}

			state.loading = true;
			$widget.addClass('loading');

			// If sorting by year, use client-side sorting and pagination.
			if (state.orderby === 'year') {
				loadProjectsWithYearSort(append, isInitial);
			} else {
				loadProjectsFromAPI(append, isInitial);
			}
		}

		/**
		 * Load projects with client-side year sorting
		 *
		 * @param {boolean} append - Whether to append to existing projects.
		 * @param {boolean} isInitial - Whether this is the initial load.
		 */
		function loadProjectsWithYearSort(append, isInitial) {
			// If we already have all projects cached, paginate from cache.
			if (state.allProjects.length > 0) {
				paginateFromCache(append);
				return;
			}

			// Fetch all projects, then sort and paginate.
			getProjects(state.currentCategory, true, isInitial)
				.done(function (response) {
					if (response.status === 'success') {
						// Render filters on initial load.
						if (isInitial && response.categories) {
							renderFilters(response.categories);
						}

						if (response.data && response.data.length > 0) {
							// Sort all projects by year.
							state.allProjects = sortProjectsByYear(response.data);
							state.total = state.allProjects.length;
							paginateFromCache(append);
						} else if (!append) {
							showNoResults();
						} else {
							state.allLoaded = true;
						}
					} else if (!append) {
						showNoResults();
					}
				})
				.fail(function () {
					console.error('Portfolio widget: Failed to load projects');
				})
				.always(function () {
					state.loading = false;
					$widget.removeClass('loading');
				});
		}

		/**
		 * Paginate projects from cached array
		 *
		 * @param {boolean} append - Whether to append to existing projects.
		 */
		function paginateFromCache(append) {
			var start = state.offset;
			var end = start + state.postsPerPage;
			var projectsToShow = state.allProjects.slice(start, end);

			if (projectsToShow.length > 0) {
				renderProjects(projectsToShow, append);
				state.offset = end;

				if (state.offset >= state.total) {
					state.allLoaded = true;
				}
			} else {
				state.allLoaded = true;
			}

			state.loading = false;
			$widget.removeClass('loading');
		}

		/**
		 * Load projects directly from API (no client-side sorting)
		 *
		 * @param {boolean} append - Whether to append to existing projects.
		 * @param {boolean} isInitial - Whether this is the initial load.
		 */
		function loadProjectsFromAPI(append, isInitial) {
			getProjects(state.currentCategory, false, isInitial)
				.done(function (response) {
					if (response.status === 'success') {
						// Render filters on initial load.
						if (isInitial && response.categories) {
							renderFilters(response.categories);
						}

						if (response.data && response.data.length > 0) {
							renderProjects(response.data, append);
							state.offset += response.data.length;
							state.total = response.total;

							// Check if all posts are loaded.
							if (state.offset >= state.total) {
								state.allLoaded = true;
							}
						} else if (!append) {
							showNoResults();
						} else {
							state.allLoaded = true;
						}
					} else if (!append) {
						showNoResults();
					}
				})
				.fail(function () {
					console.error('Portfolio widget: Failed to load projects');
				})
				.always(function () {
					state.loading = false;
					$widget.removeClass('loading');
				});
		}

		/**
		 * Show no results message
		 */
		function showNoResults() {
			$projectsContainer.html(
				'<p class="no-results">' + 
				(state.lang === 'es' ? 'No se encontraron proyectos.' : 'No projects found.') + 
				'</p>'
			);
			state.allLoaded = true;
		}

		/**
		 * Handle filter click
		 *
		 * @param {Event} e - Click event.
		 */
		function handleFilterClick(e) {
			var $filter = $(e.currentTarget);
			var category = $filter.data('category');

			// Update active state on both desktop and mobile.
			$widget.find('.portfolio-filters .filter').removeClass('active');
			$widget.find('.portfolio-filters .filter[data-category="' + category + '"]').addClass('active');

			// Close mobile menu.
			if ($mobileItemView.is(':visible')) {
				$mobileItemView.slideUp();
				$widget.find('.ViewAllsvg').css('transform', 'rotate(0deg)');
			}

			// Reset state and load with new category.
			state.currentCategory = (category === 'all') ? '' : category;
			state.offset = 0;
			state.allLoaded = false;
			state.allProjects = []; // Clear cached projects for new filter.
			$projectsContainer.empty();
			loadProjects(false, false);
		}

		/**
		 * Handle view mode switch
		 *
		 * @param {string} mode - 'list' or 'grid'.
		 */
		function switchViewMode(mode) {
			if (mode === 'list') {
				$projectsContainer.removeClass('grid-view').addClass('list-view');
				$viewModeList.addClass('current-view');
				$viewModeGrid.removeClass('current-view');
			} else {
				$projectsContainer.removeClass('list-view').addClass('grid-view');
				$viewModeGrid.addClass('current-view');
				$viewModeList.removeClass('current-view');
			}
		}

		/**
		 * Initialize Intersection Observer for infinite scroll
		 */
		function initInfiniteScroll() {
			// Check for IntersectionObserver support.
			if (!('IntersectionObserver' in window)) {
				return;
			}

			var observer = new IntersectionObserver(function (entries) {
				entries.forEach(function (entry) {
					if (entry.isIntersecting && !state.loading && !state.allLoaded) {
						loadProjects(true, false);
					}
				});
			}, {
				root: null,
				rootMargin: '100px',
				threshold: 0
			});

			observer.observe($loaderContainer[0]);
		}

		/**
		 * Bind event handlers
		 */
		function bindEvents() {
			// View mode toggle.
			$viewModeList.on('click', function () {
				switchViewMode('list');
			});

			$viewModeGrid.on('click', function () {
				switchViewMode('grid');
			});

			// Mobile menu toggle.
			$mobileViewAll.on('click', function () {
				$mobileItemView.slideToggle();
				var isOpen = $mobileItemView.is(':visible');
				$widget.find('.ViewAllsvg').css(
					'transform', 
					isOpen ? 'rotate(45deg)' : 'rotate(0deg)'
				);
			});
		}

		/**
		 * Initialize the widget
		 */
		function init() {
			// Set initial category from pre-filter if present.
			if (state.preFilter && state.preFilter !== 'all') {
				state.currentCategory = state.preFilter;
			}

			bindEvents();
			loadProjects(false, true); // Initial load with categories.
			initInfiniteScroll();
		}

		// Start initialization.
		init();
	}

	/**
	 * Initialize all Portfolio Widgets on page
	 */
	function initAllWidgets() {
		$('.soma-portfolio-widget').each(function () {
			var $widget = $(this);

			// Prevent double initialization.
			if ($widget.data('portfolio-initialized')) {
				return;
			}

			$widget.data('portfolio-initialized', true);
			initPortfolioWidget($widget);
		});
	}

	// Document ready.
	$(document).ready(function () {
		initAllWidgets();
	});

	// Elementor frontend ready.
	$(window).on('elementor/frontend/init', function () {
		if (typeof elementorFrontend !== 'undefined') {
			elementorFrontend.hooks.addAction('frontend/element_ready/soma-portfolio.default', function ($scope) {
				var $widget = $scope.find('.soma-portfolio-widget');
				if ($widget.length && !$widget.data('portfolio-initialized')) {
					$widget.data('portfolio-initialized', true);
					initPortfolioWidget($widget);
				}
			});
		}
	});

})(jQuery);
