/**
 * Virtual assistant (see inc/chatbot.php): a bubble that opens a panel with a question box. On «Enviar pregunta» it opens a new
 * tab on ChatGPT with the prompt of the theme and the question inside it, once the visitor has ticked that the question goes to a third
 * party (the box is cleared after every send). Nothing is sent before that, and there is no server here.
 *
 * Escape and a click outside close the panel and the focus goes back to the bubble.
 */
(() => {
    'use strict';

    const config = window.arandaChatbot;
    const root = document.querySelector('[data-chatbot]');
    if (!config || !root) return;

    const panel = root.querySelector('[data-chatbot-panel]');
    const toggle = root.querySelector('[data-chatbot-open]');
    const closeButton = root.querySelector('[data-chatbot-close]');
    const form = root.querySelector('[data-chatbot-form]');
    const question = root.querySelector('[data-chatbot-question]');
    const counter = root.querySelector('[data-chatbot-counter]');
    const consent = root.querySelector('[data-chatbot-consent]');
    const error = root.querySelector('[data-chatbot-error]');
    if (!panel || !toggle || !form || !question || !consent) return;

    const max = Number(config.max) || 500;

    const setOpen = (open) => {
        panel.hidden = !open;
        toggle.setAttribute('aria-expanded', String(open));
        root.classList.toggle('is-open', open);
    };

    const open = () => {
        setOpen(true);
        question.focus({ preventScroll: true });
    };

    const close = () => {
        setOpen(false);
        toggle.focus({ preventScroll: true });
    };

    const updateCounter = () => {
        if (counter) counter.textContent = `${question.value.length} / ${max}`;
    };

    // One line, no double quotes: the question goes between quotes inside the prompt.
    const clean = (text) => text.replace(/\s+/g, ' ').replace(/"/g, "'").trim().slice(0, max);

    // Today's date, in the visitor's clock, written as «miércoles, 21 de octubre de 2026».
    const today = () =>
        new Date().toLocaleDateString('es-ES', {
            weekday: 'long',
            day: 'numeric',
            month: 'long',
            year: 'numeric',
        });

    // The question is also kept in the site, for the administrators (30 days). It never stops the question if it fails.
    const keep = (text) => {
        if (!config.logUrl) return;
        fetch(config.logUrl, {
            method: 'POST',
            credentials: 'same-origin',
            keepalive: true,
            headers: { 'Content-Type': 'application/json', 'X-WP-Nonce': config.nonce },
            body: JSON.stringify({ question: text, page: window.location.href }),
        }).catch(() => {});
    };

    const buildUrl = (text) => {
        const url = new URL(config.chatUrl);
        const prompt = config.prompt
            .split(config.datePlaceholder)
            .join(today())
            .split(config.placeholder)
            .join(text);
        url.searchParams.set('q', prompt);
        return url.toString();
    };

    toggle.addEventListener('click', () => (panel.hidden ? open() : close()));
    if (closeButton) closeButton.addEventListener('click', close);
    question.addEventListener('input', updateCounter);
    consent.addEventListener('change', () => {
        if (consent.checked && error) error.hidden = true;
    });

    form.addEventListener('submit', (event) => {
        event.preventDefault();
        const text = clean(question.value);
        if (!text) {
            question.focus({ preventScroll: true });
            return;
        }
        // The question goes to a third party: it is only sent with the box ticked, every time.
        if (!consent.checked) {
            if (error) error.hidden = false;
            consent.focus({ preventScroll: true });
            return;
        }
        keep(text);
        const link = document.createElement('a');
        link.href = buildUrl(text);
        link.target = '_blank';
        link.rel = 'noopener noreferrer';
        link.click();
        consent.checked = false;
    });

    document.addEventListener('pointerdown', (event) => {
        if (!panel.hidden && !root.contains(event.target)) setOpen(false);
    });
    document.addEventListener('keydown', (event) => {
        if (event.key === 'Escape' && !panel.hidden) close();
    });

    updateCounter();
    setOpen(false);
})();
