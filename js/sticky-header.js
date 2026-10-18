/**
 * Fixed header: the contact bar and the main menu stay on screen while scrolling, and on the home page the «¿Qué necesitas?»
 * band stays under them, shrunk to the search field.
 *
 * The positions are CSS (`position: sticky` in css/aranda-de-duero.css). This script only measures what CSS cannot know:
 * the height of the bars (to stack the band under them and to leave room for anchors) and whether the band is stuck, which
 * is when it is shrunk. The space above the band is an empty sentinel, whose lower edge is where the band would be without sticking.
 */
(() => {
    const root = document.documentElement;
    const bar = document.querySelector('.top-bar');
    const header = document.querySelector('.site-header-wrapper');
    if (!header) {
        return;
    }
    const band = document.querySelector('.home-buscador');
    const sentinel = document.querySelector('.home-buscador-sentinel');
    let offset = 0;
    let ticking = false;

    const setVar = (name, value) => root.style.setProperty(name, `${value}px`);

    // The bar sticks too except on small screens, where it scrolls away to leave room for the content.
    const measure = () => {
        // Rounded down: the next bar then goes half a pixel under this one instead of leaving a gap where the page shows through.
        const barHeight =
            bar && 'sticky' === getComputedStyle(bar).position
                ? Math.floor(bar.getBoundingClientRect().height)
                : 0;
        offset = barHeight + Math.floor(header.getBoundingClientRect().height);
        setVar('--sticky-bar', barHeight);
        setVar('--sticky-offset', offset);

        if (band && sentinel) {
            // What the band stops taking up when shrunk, so the page below does not jump when it shrinks.
            const wasCompact = band.classList.contains('is-compact');
            band.classList.remove('is-compact');
            const expanded = band.offsetHeight;
            setVar('--buscador-delta', 0);
            band.classList.add('is-compact');
            setVar('--buscador-delta', expanded - band.offsetHeight);
            band.classList.toggle('is-compact', wasCompact);
        }
    };

    const update = () => {
        ticking = false;
        if (!band || !sentinel) {
            return;
        }
        band.classList.toggle('is-compact', sentinel.getBoundingClientRect().bottom <= offset + 1);
    };

    const onScroll = () => {
        if (!ticking) {
            ticking = true;
            window.requestAnimationFrame(update);
        }
    };

    measure();
    update();
    window.addEventListener('scroll', onScroll, { passive: true });
    window.addEventListener('resize', () => {
        measure();
        update();
    });
    if ('ResizeObserver' in window) {
        // The menu panel of the mobile layout changes the height of the header when it opens.
        new ResizeObserver(() => {
            measure();
            update();
        }).observe(header);
    }
})();
