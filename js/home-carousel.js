/**
 * Home carousel: arrows, optional autoplay and a pause button on top of a CSS scroll-snap strip, plus the optional
 * MP4 videos of the slides.
 *
 * The strip scrolls on its own (touch, trackpad, keyboard with the arrow keys once focused); this only adds the
 * buttons and the automatic change. No autoplay when the visitor prefers reduced motion; it also stops while the
 * pointer or the keyboard focus is inside, and on the pause button.
 *
 * Videos (a muted <video data-src> over the image of a slide, see inc/home-carousel.php) have no src in the HTML.
 * The first one is loaded once the page has loaded (so it never competes with the image that is the LCP) and the
 * next one only when the current one starts to play, so they are never all downloaded at once. A video plays when
 * its slide is the visible one and the carousel is not paused; the carousel moves on when it ends. With reduced
 * motion, a data saver or a browser that refuses to autoplay, the image of the slide stays.
 */
(() => {
    'use strict';

    const root = document.querySelector('.home-carousel');
    const track = root && root.querySelector('.home-carousel-track');
    if (!track) return;

    const slides = track.querySelectorAll('.home-carousel-slide');
    const single = slides.length < 2;
    if (single && !track.querySelector('video')) {
        root.querySelectorAll('.home-carousel-button').forEach((b) => (b.hidden = true));
        return;
    }

    const labels = window.arandaCarousel || {};
    const reduced = window.matchMedia('(prefers-reduced-motion: reduce)');
    const saveData = !!(navigator.connection && navigator.connection.saveData);
    const pauseButton = root.querySelector('.home-carousel-pause');
    const delay = 6000;
    let timer = null;
    let userPaused = reduced.matches;
    let active = 0;

    const videos = Array.from(slides).map((slide) => slide.querySelector('video'));
    const canPlayVideos = () => !reduced.matches && !saveData;

    // Only the first image is eager; once the page has loaded, fetch the rest (each GIF is ~1 MB) so a slide
    // is never blank when it comes into view. Native lazy loading alone would wait until it is almost visible.
    const preload = () => {
        track.querySelectorAll('img[loading="lazy"]').forEach((image) => {
            image.loading = 'eager';
        });
    };

    // Give a video its address and start downloading it, once.
    const load = (video) => {
        if (!video || video.dataset.loaded || !canPlayVideos()) return;
        video.dataset.loaded = 'true';
        video.preload = 'auto';
        video.src = video.dataset.src;
        video.load();
    };

    const nextIndex = (index) => (index + 1) % slides.length;

    const stopVideo = (video) => {
        if (!video) return;
        video.pause();
        video.classList.remove('is-playing');
        if (video.dataset.loaded) video.currentTime = 0;
    };

    // Play the video of the visible slide (when there is one and nothing forbids it) and get the next one ready.
    const playActive = () => {
        videos.forEach((video, index) => {
            if (index !== active) stopVideo(video);
        });

        const video = videos[active];
        if (!video) {
            load(videos[nextIndex(active)]);
            return;
        }
        if (!canPlayVideos() || userPaused || document.hidden) return;

        load(video);
        const started = video.play();
        if (started && started.catch) {
            // The browser refused to autoplay (low power mode, data saver…): the image stays.
            started.catch(() => video.classList.remove('is-playing'));
        }
    };

    // The video is shown over its image only once it is really playing; the next one is fetched at that moment.
    videos.forEach((video, index) => {
        if (!video) return;
        video.muted = true;
        video.addEventListener('playing', () => {
            video.classList.add('is-playing');
            load(videos[nextIndex(index)]);
        });
        video.addEventListener('ended', () => {
            if (timer && index === active) go(1);
        });
    });

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

    // A slide with a video playing moves on when the video ends; the others every few seconds.
    const tick = () => {
        const video = videos[active];
        if (video && video.classList.contains('is-playing') && !video.paused && !video.ended)
            return;
        go(1);
    };

    const start = () => {
        stop();
        if (!single && !userPaused && !document.hidden) timer = window.setInterval(tick, delay);
    };

    const syncPauseButton = () => {
        pauseButton.setAttribute('aria-pressed', String(userPaused));
        pauseButton.setAttribute(
            'aria-label',
            userPaused ? labels.resume || 'Reanudar' : labels.pause || 'Pausar'
        );
        pauseButton.classList.toggle('is-paused', userPaused);
    };

    // The visible slide changes with the arrows, a swipe or the autoplay: follow it from the scroll position.
    let settling = null;
    track.addEventListener(
        'scroll',
        () => {
            window.clearTimeout(settling);
            settling = window.setTimeout(() => {
                const index = Math.round(track.scrollLeft / track.clientWidth);
                if (index !== active && index >= 0 && index < slides.length) {
                    active = index;
                    playActive();
                    // Each slide gets its full time, whatever moved the strip.
                    if (timer) start();
                }
            }, 120);
        },
        { passive: true }
    );

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
        if (userPaused) {
            const video = videos[active];
            if (video) video.pause();
        } else {
            playActive();
        }
        start();
    });

    root.addEventListener('mouseenter', stop);
    root.addEventListener('mouseleave', start);
    root.addEventListener('focusin', stop);
    root.addEventListener('focusout', start);
    document.addEventListener('visibilitychange', () => {
        const video = videos[active];
        if (document.hidden) {
            if (video) video.pause();
        } else {
            playActive();
        }
        start();
    });

    const begin = () => {
        preload();
        playActive();
    };
    if (document.readyState === 'complete') {
        begin();
    } else {
        window.addEventListener('load', begin);
    }

    if (single) {
        // One slide: nothing to move to, but a video that plays on its own keeps its pause button.
        root.querySelectorAll('.home-carousel-prev, .home-carousel-next').forEach(
            (b) => (b.hidden = true)
        );
        if (videos[0]) videos[0].loop = true;
    }

    syncPauseButton();
    start();
})();
