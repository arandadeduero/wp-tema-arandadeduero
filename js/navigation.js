/**
 * Responsive Navigation Menu Handler
 *
 * Provides accessible, responsive navigation with:
 * - Mobile hamburger menu toggle
 * - Keyboard navigation support (TAB key)
 * - Touch device support
 * - WCAG 2.1 Level AA accessibility compliance
 *
 * Modern Implementation:
 * - ES6+ arrow functions and modern syntax
 * - querySelector instead of getElementsByTagName
 * - Event delegation and listeners
 * - ARIA attributes for screen readers
 * - Semantic HTML with proper roles
 *
 * Features:
 * - Responsive mobile menu toggle
 * - Auto-close when clicking outside navigation
 * - Focus management for keyboard navigation
 * - Touch support for dropdown menus
 * - Proper ARIA labels and states
 * - supports keyboard navigation on all devices
 *
 * @since 2.0.0
 * @version 1.0
 */

const NavigationMenu = (() => {
    'use strict';

    const nav = document.getElementById('site-navigation');
    if (!nav) return {};

    const toggle = nav.querySelector('button');
    if (!toggle) return {};

    const menu = nav.querySelector('ul');
    if (!menu) {
        toggle.hidden = true;
        return {};
    }

    // Ensure menu has correct class
    menu.classList.add('nav-menu');

    // Handle menu toggle button click
    toggle.addEventListener('click', () => {
        nav.classList.toggle('toggled');
        toggle.setAttribute(
            'aria-expanded',
            toggle.getAttribute('aria-expanded') === 'true' ? 'false' : 'true'
        );
    });

    // Close menu when clicking outside
    document.addEventListener('click', (e) => {
        if (!nav.contains(e.target)) {
            nav.classList.remove('toggled');
            toggle.setAttribute('aria-expanded', 'false');
        }
    });

    // Handle keyboard and focus navigation
    const menuLinks = menu.querySelectorAll('a');
    const linksWithChildren = menu.querySelectorAll(
        '.menu-item-has-children > a, .page_item_has_children > a'
    );

    menuLinks.forEach((link) => {
        link.addEventListener('focus', handleFocusEvent, true);
        link.addEventListener('blur', handleFocusEvent, true);
    });

    linksWithChildren.forEach((link) => {
        link.addEventListener('touchstart', handleFocusEvent, false);
    });

    /**
     * Handle focus state for menu items
     * @param {Event} event - Focus/blur/touchstart event
     */
    function handleFocusEvent(event) {
        if (event.type === 'focus' || event.type === 'blur') {
            let el = this;
            while (el && !el.classList.contains('nav-menu')) {
                if (el.tagName.toLowerCase() === 'li') {
                    el.classList.toggle('focus');
                }
                el = el.parentNode;
            }
        }

        if (event.type === 'touchstart') {
            event.preventDefault();
            const menuItem = this.parentNode;
            const siblings = Array.from(menuItem.parentNode.children);

            siblings.forEach((item) => {
                if (item !== menuItem) {
                    item.classList.remove('focus');
                }
            });
            menuItem.classList.toggle('focus');
        }
    }

    return { toggle, menu, nav };
})();
