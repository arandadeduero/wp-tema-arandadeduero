/**
 * Event poster preview.
 *
 * On a single event, clicking (or pressing Enter/Space on) the poster opens it at full size in a native
 * <dialog>, with a download button. Esc, the close button or a click on the backdrop close it.
 * Without JavaScript the image is just an image.
 *
 * Labels come from PHP (`arandaEventPoster`); the full-size URL from `data-poster-full` on the <article>.
 */
(() => {
    'use strict';

    const article = document.querySelector('article[data-poster-full]');
    const image = article && article.querySelector('.mc-image');
    if (!image || typeof HTMLDialogElement === 'undefined') return;

    const labels = window.arandaEventPoster || {};
    const fullUrl = article.dataset.posterFull;

    // The poster itself becomes the control.
    image.setAttribute('role', 'button');
    image.setAttribute('tabindex', '0');
    image.setAttribute('aria-haspopup', 'dialog');
    image.setAttribute('title', labels.zoom || 'Ampliar');
    image.classList.add('is-zoomable');

    const dialog = document.createElement('dialog');
    dialog.className = 'poster-dialog';
    dialog.setAttribute('aria-label', image.alt || labels.zoom || '');

    const picture = document.createElement('img');
    picture.className = 'poster-dialog-image';
    picture.alt = image.alt;

    const bar = document.createElement('div');
    bar.className = 'poster-dialog-bar';

    const download = document.createElement('a');
    download.className = 'poster-dialog-button';
    download.href = fullUrl;
    download.setAttribute('download', fullUrl.split('/').pop().split('?')[0]);
    download.textContent = labels.download || 'Descargar';

    // `download` is ignored for another origin (the image may be served from Jetpack's CDN): fetch it and save
    // the blob; if that fails, open the image in a new tab so the visitor can still save it.
    download.addEventListener('click', async (e) => {
        if (new URL(fullUrl, window.location.href).origin === window.location.origin) return;
        e.preventDefault();
        try {
            const response = await fetch(fullUrl);
            if (!response.ok) throw new Error(response.status);
            const url = URL.createObjectURL(await response.blob());
            const link = document.createElement('a');
            link.href = url;
            link.download = download.getAttribute('download');
            document.body.append(link);
            link.click();
            link.remove();
            setTimeout(() => URL.revokeObjectURL(url), 1000);
        } catch {
            window.open(fullUrl, '_blank', 'noopener');
        }
    });

    const close = document.createElement('button');
    close.type = 'button';
    close.className = 'poster-dialog-button';
    close.textContent = labels.close || 'Cerrar';
    close.addEventListener('click', () => dialog.close());

    bar.append(download, close);
    dialog.append(picture, bar);
    document.body.append(dialog);

    // A click on the backdrop lands on the <dialog> itself, not on its content.
    dialog.addEventListener('click', (e) => {
        if (e.target === dialog) dialog.close();
    });

    // Back to the poster when it closes, so keyboard users don't lose their place.
    dialog.addEventListener('close', () => image.focus());

    const open = () => {
        picture.src = fullUrl;
        dialog.showModal();
    };

    image.addEventListener('click', open);
    image.addEventListener('keydown', (e) => {
        if (e.key === 'Enter' || e.key === ' ') {
            e.preventDefault();
            open();
        }
    });
})();
