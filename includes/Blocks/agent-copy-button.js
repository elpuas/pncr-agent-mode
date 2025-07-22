/**
 * Agent Copy Link Button Block
 *
 * Simple Gutenberg block for inserting the agent copy link button shortcode.
 */

(function() {
	'use strict';

	const { registerBlockType } = wp.blocks;
	const { __ } = wp.i18n;
	const { useBlockProps } = wp.blockEditor;
	const { createElement: el } = wp.element;

	registerBlockType('pbcr-agent-mode/agent-copy-link-button', {
		title: __('Agent Copy Link Button', 'pbcr-agent-mode'),
		description: __('Adds a button to copy the Agent Mode link for the current property.', 'pbcr-agent-mode'),
		category: 'widgets',
		icon: 'clipboard',
		keywords: [
			__('agent', 'pbcr-agent-mode'),
			__('copy', 'pbcr-agent-mode'),
			__('link', 'pbcr-agent-mode'),
			__('property', 'pbcr-agent-mode')
		],
		supports: {
			html: false,
			multiple: false,
			reusable: false
		},
		attributes: {
			label: {
				type: 'string',
				default: __('Copy Agent Link', 'pbcr-agent-mode')
			}
		},
		edit: function(props) {
			const blockProps = useBlockProps();
			const { attributes } = props;

			return el(
				'div',
				blockProps,
				el(
					'button',
					{
						className: 'agent-copy-btn preview',
						style: {
							padding: '0.75rem 1.5rem',
							backgroundColor: '#3b82f6',
							color: '#ffffff',
							border: '2px solid #3b82f6',
							borderRadius: '0.5rem',
							cursor: 'pointer',
							fontSize: '1rem',
							fontWeight: '500'
						},
						disabled: true
					},
					attributes.label + ' ' + __('(Preview)', 'pbcr-agent-mode')
				)
			);
		},
		save: function() {
			// Return null since we're using a shortcode for server-side rendering
			return null;
		}
	});

})();
