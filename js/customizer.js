/**
 * WordPress Theme Customizer Live Preview
 *
 * Handles live updates in the theme customizer preview pane using
 * the WordPress Customizer API with vanilla JavaScript.
 *
 * Modern Implementation:
 * - Arrow functions and destructuring
 * - ES6 modules (IIFE)
 * - Object.entries for style application
 * - Safe DOM queries with null checking
 * - No external dependencies
 *
 * Features:
 * - Live preview of site title changes
 * - Live preview of site description changes
 * - Live preview of header text color changes
 * - Proper accessibility handling
 *
 * @since 2.0.0
 */

(() => {
    'use strict';

    // Utility for safe DOM queries
    const safeQuery = (selector) => document.querySelector(selector);
    const applyStyles = (el, styles) => {
        if (!el) return;
        Object.entries(styles).forEach(([key, value]) => {
            el.style[key] = value;
        });
    };

    // Watch for blog name changes
    wp.customize('blogname', (value) => {
        value.bind((to) => {
            const titleLink = safeQuery('.site-title a');
            if (titleLink) titleLink.textContent = to;
        });
    });

    // Watch for blog description changes
    wp.customize('blogdescription', (value) => {
        value.bind((to) => {
            const description = safeQuery('.site-description');
            if (description) description.textContent = to;
        });
    });

    // Watch for header text color changes
    wp.customize('header_textcolor', (value) => {
        value.bind((to) => {
            const siteTitle = safeQuery('.site-title');
            const siteDesc = safeQuery('.site-description');
            const titleLink = safeQuery('.site-title a');

            if (to === 'blank') {
                // Hidden state
                const hiddenStyles = {
                    clip: 'rect(1px, 1px, 1px, 1px)',
                    position: 'absolute',
                    height: '1px',
                    width: '1px',
                    overflow: 'hidden',
                };
                applyStyles(siteTitle, hiddenStyles);
                applyStyles(siteDesc, hiddenStyles);
            } else {
                // Visible state
                const visibleStyles = {
                    clip: 'auto',
                    position: 'relative',
                    height: 'auto',
                    width: 'auto',
                    overflow: 'visible',
                };
                applyStyles(siteTitle, visibleStyles);
                applyStyles(siteDesc, visibleStyles);

                // Apply color
                if (titleLink) titleLink.style.color = to;
                if (siteDesc) siteDesc.style.color = to;
            }
        });
    });
})();
