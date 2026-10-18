/**
 * Home carousel: arrows, optional autoplay and a pause button on top of a CSS scroll-snap strip.
 *
 * The strip scrolls on its own (touch, trackpad, keyboard with the arrow keys once focused); this only adds the
 * buttons and the automatic change. No autoplay when the visitor prefers reduced motion; it also stops while the
 * pointer or the keyboard focus is inside, and on the pause button.
 */
(() => {
    'use strict';

    const root = document.querySelector('.home-carousel');
    const track = root && root.querySelector('.home-carousel-track');
    if (!track) return;

    const slides = track.querySelectorAll('.home-carousel-slide');
    if (slides.length < 2) {
        root.querySelectorAll('.home-carousel-button').forEach((b) => (b.hidden = true));
        return;
    }

    const labels = window.arandaCarousel || {};
    const reduced = window.matchMedia('(prefers-reduced-motion: reduce)');
    const pauseButton = root.querySelector('.home-carousel-pause');
    const delay = 6000;
    let timer = null;
    let userPaused = reduced.matches;

    const go = (direction) => {
        const width = track.clientWidth;
        const atEnd = track.scrollLeft + width >= track.scrollWidth - 2;
        const atStart = track.scrollLeft <= 2;
        let left = track.scrollLeft + direction * width;
        if (direction > 0 && atEnd) left = 0;
        if (direction < 0 && atStart) left = track.scrollWidth;
        track.scrollTo({ left, behavior: reduced.matches ? 'auto' : 'smooth' });
    };

    const stop = () => {
        window.clearInterval(timer);
        timer = null;
    };

    const start = () => {
        stop();
        if (!userPaused && !document.hidden) timer = window.setInterval(() => go(1), delay);
    };

    const syncPauseButton = () => {
        pauseButton.setAttribute('aria-pressed', String(userPaused));
        pauseButton.setAttribute(
            'aria-label',
            userPaused ? labels.resume || 'Reanudar' : labels.pause || 'Pausar'
        );
        pauseButton.classList.toggle('is-paused', userPaused);
    };

    root.querySelector('.home-carousel-prev').addEventListener('click', () => {
        go(-1);
        start();
    });
    root.querySelector('.home-carousel-next').addEventListener('click', () => {
        go(1);
        start();
    });
    pauseButton.addEventListener('click', () => {
        userPaused = !userPaused;
        syncPauseButton();
        start();
    });

    root.addEventListener('mouseenter', stop);
    root.addEventListener('mouseleave', start);
    root.addEventListener('focusin', stop);
    root.addEventListener('focusout', start);
    document.addEventListener('visibilitychange', start);

    syncPauseButton();
    start();
})();
