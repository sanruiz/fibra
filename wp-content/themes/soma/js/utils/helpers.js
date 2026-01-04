/**
 * SOMA Theme JavaScript Utilities
 *
 * Shared helper functions for use across JavaScript components.
 *
 * @package Soma
 * @since   3.1.10
 */

/**
 * Escape HTML entities to prevent XSS attacks.
 *
 * Creates a temporary DOM element and uses textContent/innerHTML
 * to safely escape special characters (<, >, &, ", ').
 *
 * @param {string} text - Text to escape.
 * @returns {string} Escaped text safe for HTML insertion.
 *
 * @example
 * import { escapeHtml } from '../utils/helpers';
 * const safeText = escapeHtml(userInput);
 * element.innerHTML = `<span>${safeText}</span>`;
 */
export const escapeHtml = (text) => {
    const div = document.createElement('div');
    div.textContent = text;
    return div.innerHTML;
};

/**
 * Debounce function execution.
 *
 * Delays invoking the function until after `wait` milliseconds
 * have elapsed since the last time the debounced function was invoked.
 *
 * @param {Function} func - Function to debounce.
 * @param {number} wait - Milliseconds to delay.
 * @returns {Function} Debounced function.
 *
 * @example
 * import { debounce } from '../utils/helpers';
 * const debouncedResize = debounce(() => console.log('resized'), 250);
 * window.addEventListener('resize', debouncedResize);
 */
export const debounce = (func, wait) => {
    let timeout;
    return function executedFunction(...args) {
        const later = () => {
            clearTimeout(timeout);
            func(...args);
        };
        clearTimeout(timeout);
        timeout = setTimeout(later, wait);
    };
};

/**
 * Throttle function execution.
 *
 * Ensures the function is called at most once per `limit` milliseconds.
 *
 * @param {Function} func - Function to throttle.
 * @param {number} limit - Minimum milliseconds between calls.
 * @returns {Function} Throttled function.
 *
 * @example
 * import { throttle } from '../utils/helpers';
 * const throttledScroll = throttle(() => console.log('scrolled'), 100);
 * window.addEventListener('scroll', throttledScroll);
 */
export const throttle = (func, limit) => {
    let inThrottle;
    return function executedFunction(...args) {
        if (!inThrottle) {
            func(...args);
            inThrottle = true;
            setTimeout(() => (inThrottle = false), limit);
        }
    };
};
