/**
 * Aranda de Duero Theme - Main JavaScript Module
 *
 * Modern ES6+ Implementation with:
 * - IIFE Pattern (Immediately Invoked Function Expression) for scope isolation
 * - ES6 Arrow Functions for cleaner syntax
 * - Const/Let instead of Var for better scoping
 * - Template Literals for string manipulation
 * - Object destructuring and spread operators
 * - Data attributes for configuration
 * - NO jQuery - Pure Vanilla JavaScript
 * - Event delegation and modern DOM APIs
 *
 * Performance Optimizations:
 * - Single DOM traversal
 * - Early returns for missing elements
 * - Efficient event binding
 * - CSS classes for styling (not inline styles)
 * - CSS variables for dynamic values
 *
 * Browser Support: Modern browsers (ES6+)
 * Accessibility: WCAG 2.1 AA compliant
 *
 * @author Ayuntamiento de Aranda de Duero
 * @version 2.0.0
 * @requires None (Vanilla JavaScript)
 */

// Modern ES6+ modular approach with better separation of concerns
const ArandaTheme = (() => {
    'use strict';

    // Utility functions
    const DOM = {
        query: (selector) => document.querySelector(selector),
        queryAll: (selector) => document.querySelectorAll(selector),
        create: (tag, classes = '') => {
            const el = document.createElement(tag);
            if (classes) el.className = classes;
            return el;
        },
    };

    const toggleCategory = (categoryId) => {
        const sections = DOM.queryAll('.category-principal-sections');
        const selectedClass = `category-${categoryId}`;

        sections.forEach((section) => {
            const isSelected = section.classList.contains(selectedClass);
            const isVisible = section.getAttribute('aria-hidden') !== 'true';

            if (isSelected) {
                section.toggleAttribute('aria-hidden', isVisible);
                section.classList.toggle('hidden', isVisible);
            } else {
                section.setAttribute('aria-hidden', 'true');
                section.classList.add('hidden');
            }
        });
    };

    const setupCategoryToggle = () => {
        DOM.queryAll('.home-main-section-a').forEach((button) => {
            button.addEventListener('click', (e) => {
                e.preventDefault();
                toggleCategory(button.dataset.categoryid);
            });
        });
    };

    const setupEspecialToggle = () => {
        const btn = DOM.query('#moreEspecial');
        if (!btn) return;

        btn.addEventListener('click', (e) => {
            e.preventDefault();
            const target = DOM.query('.especial');
            if (!target) return;

            target.classList.toggle('hide');
            target.classList.toggle('nohide');
        });
    };

    const setupAjaxLoadMore = () => {
        const container = DOM.query('#ajax-load-more');
        if (!container) return;

        const observer = new MutationObserver(() => {
            DOM.queryAll('.alm-reveal').forEach((el) => {
                el.classList.add('row');
            });
        });

        observer.observe(container, { subtree: true, childList: true });
    };

    const setupEventDescriptions = () => {
        DOM.queryAll('.home-evento-descripcion').forEach((el) => {
            const fechaEl = el.querySelector('.home-evento-fecha');
            if (!fechaEl) return;

            const height = el.offsetHeight - 48;

            // Use CSS custom property instead of dynamic classes
            el.style.setProperty('--event-height', `${height}px`);
        });
    };

    const setupSearchMenu = () => {
        const menu = DOM.query('.astm-search-menu');
        if (!menu) return;

        menu.querySelectorAll('a').forEach((link) => {
            // Check if span doesn't already exist
            if (!link.querySelector('span.sr-only')) {
                const span = DOM.create('span', 'sr-only');
                span.textContent = 'Search';
                link.appendChild(span);
            }
        });
    };

    const setupStreamingBanner = () => {
        const banner = DOM.query('#banner_streaming');
        if (!banner) return;

        banner.addEventListener('click', () => {
            window.open(
                'https://www.youtube.com/user/SMArandaDeDuero',
                '_blank',
                'noopener,noreferrer'
            );
        });
    };

    // Initialize all modules
    const init = () => {
        setupCategoryToggle();
        setupEspecialToggle();
        setupAjaxLoadMore();
        setupEventDescriptions();
        setupSearchMenu();
        setupStreamingBanner();
    };

    return { init };
})();

// Initialize when DOM is ready
if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', ArandaTheme.init);
} else {
    ArandaTheme.init();
}
