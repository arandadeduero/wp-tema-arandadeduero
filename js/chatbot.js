/**
 * Virtual assistant (see inc/chatbot.php): a bubble that opens a panel with a question box. On «Enviar pregunta» it opens a new
 * tab on ChatGPT with the prompt of the theme and the question inside it. Nothing is sent before that, and there is no server here.
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
    if (!panel || !toggle || !form || !question) return;

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

    const buildUrl = (text) => {
        const url = new URL(config.chatUrl);
        url.searchParams.set('q', config.prompt.split(config.placeholder).join(text));
        return url.toString();
    };

    toggle.addEventListener('click', () => (panel.hidden ? open() : close()));
    if (closeButton) closeButton.addEventListener('click', close);
    question.addEventListener('input', updateCounter);

    form.addEventListener('submit', (event) => {
        event.preventDefault();
        const text = clean(question.value);
        if (!text) {
            question.focus({ preventScroll: true });
            return;
        }
        const link = document.createElement('a');
        link.href = buildUrl(text);
        link.target = '_blank';
        link.rel = 'noopener noreferrer';
        link.click();
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
