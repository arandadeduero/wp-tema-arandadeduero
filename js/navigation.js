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

(() => {
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

    // Phone layout (the same breakpoint as the CSS): the menu is a stacked panel with accordion submenus.
    const phone = window.matchMedia('(max-width: 767px)');
    const closeMenu = () => {
        nav.classList.remove('toggled');
        toggle.setAttribute('aria-expanded', 'false');
        menu.querySelectorAll('.is-open').forEach((item) => {
            item.classList.remove('is-open');
            const button = item.querySelector(':scope > .submenu-toggle');
            if (button) button.setAttribute('aria-expanded', 'false');
        });
    };

    // One button per item with children, so the link itself still goes to its page.
    const labelTemplate = nav.dataset.submenuLabel || '%s';
    menu.querySelectorAll('.menu-item-has-children').forEach((item) => {
        const link = item.querySelector(':scope > a');
        if (!link) return;

        const button = document.createElement('button');
        button.type = 'button';
        button.className = 'submenu-toggle';
        button.setAttribute('aria-expanded', 'false');
        button.setAttribute('aria-label', labelTemplate.replace('%s', link.textContent.trim()));
        button.addEventListener('click', () => {
            const open = !item.classList.contains('is-open');
            // Accordion: only one submenu open at a time.
            Array.from(item.parentNode.children).forEach((sibling) => {
                sibling.classList.remove('is-open');
                const other = sibling.querySelector(':scope > .submenu-toggle');
                if (other) other.setAttribute('aria-expanded', 'false');
            });
            item.classList.toggle('is-open', open);
            button.setAttribute('aria-expanded', String(open));
        });
        link.after(button);
    });
    menu.classList.add('has-submenu-toggles');

    // Escape closes the panel and gives the focus back to the button.
    nav.addEventListener('keydown', (e) => {
        if (e.key === 'Escape' && nav.classList.contains('toggled')) {
            closeMenu();
            toggle.focus();
        }
    });

    // Back to desktop: forget the panel state.
    phone.addEventListener('change', (e) => {
        if (!e.matches) closeMenu();
    });

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
            // On phones the link navigates and the submenu button opens the submenu.
            if (window.matchMedia('(max-width: 767px)').matches) return;
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
})();
