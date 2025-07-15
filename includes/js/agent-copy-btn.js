/**
 * Agent Copy Button Functionality
 *
 * Handles clipboard copying with user feedback for the Agent View copy button.
 */

(function() {
	'use strict';

	// Wait for DOM to be ready
	document.addEventListener('DOMContentLoaded', function() {
		initializeCopyButtons();
	});

	/**
	 * Initialize all copy buttons on the page
	 */
	function initializeCopyButtons() {
		const copyButtons = document.querySelectorAll('.agent-copy-btn');

		copyButtons.forEach(function(button) {
			button.addEventListener('click', handleCopyClick);
		});
	}

	/**
	 * Handle copy button click event
	 * @param {Event} event - The click event
	 */
	function handleCopyClick(event) {
		event.preventDefault();

		const button = event.currentTarget;
		const url = button.getAttribute('data-url');

		if (!url) {
			showFeedback(button, 'error', wp.i18n.__('No URL to copy', 'pbcr-agent-mode'));
			return;
		}

		copyToClipboard(url, button);
	}

	/**
	 * Copy text to clipboard using modern Clipboard API with fallback
	 * @param {string} text - Text to copy
	 * @param {HTMLElement} button - Button element for feedback
	 */
	function copyToClipboard(text, button) {
		// Disable button during operation
		button.disabled = true;

		// Try modern Clipboard API first
		if (navigator.clipboard && navigator.clipboard.writeText) {
			navigator.clipboard.writeText(text)
				.then(function() {
					showFeedback(button, 'success', wp.i18n.__('Copied!', 'pbcr-agent-mode'));
				})
				.catch(function(err) {
					console.warn('Clipboard API failed, trying fallback:', err);
					fallbackCopyToClipboard(text, button);
				});
		} else {
			// Fallback for older browsers
			fallbackCopyToClipboard(text, button);
		}
	}

	/**
	 * Fallback clipboard copy method for older browsers
	 * @param {string} text - Text to copy
	 * @param {HTMLElement} button - Button element for feedback
	 */
	function fallbackCopyToClipboard(text, button) {
		const textArea = document.createElement('textarea');
		textArea.value = text;
		textArea.style.position = 'fixed';
		textArea.style.left = '-999999px';
		textArea.style.top = '-999999px';
		document.body.appendChild(textArea);

		try {
			textArea.focus();
			textArea.select();
			const success = document.execCommand('copy');

			if (success) {
				showFeedback(button, 'success', wp.i18n.__('Copied!', 'pbcr-agent-mode'));
			} else {
				showFeedback(button, 'error', wp.i18n.__('Copy failed', 'pbcr-agent-mode'));
			}
		} catch (err) {
			console.error('Fallback copy failed:', err);
			showFeedback(button, 'error', wp.i18n.__('Copy not supported', 'pbcr-agent-mode'));
		} finally {
			document.body.removeChild(textArea);
		}
	}

	/**
	 * Show feedback to user by temporarily changing button text and style
	 * @param {HTMLElement} button - Button element
	 * @param {string} type - Feedback type: 'success' or 'error'
	 * @param {string} message - Message to display
	 */
	function showFeedback(button, type, message) {
		const originalText = button.textContent;
		const originalClass = button.className;

		// Update button appearance
		button.textContent = message;
		button.classList.add(type === 'success' ? 'copied' : 'error');

		// Reset after 2 seconds
		setTimeout(function() {
			button.textContent = originalText;
			button.className = originalClass;
			button.disabled = false;
		}, 2000);
	}

})();
