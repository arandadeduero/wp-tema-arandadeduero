/**
 * Service suggestions while typing in the «¿Qué necesitas?» box.
 *
 * The list of services comes with the page (`arandaSearch`, built by aranda_de_duero_search_index() in inc/search.php), so
 * the suggestions appear at once and nothing is asked to the server. The matching is the same as the server's
 * (aranda_de_duero_search_services()): every word typed must start a word of the title, the description or the synonyms,
 * and matches in the title come first. Without JavaScript the box is a plain search form.
 *
 * It follows the ARIA «combobox with listbox popup» pattern: arrow keys move through the suggestions, Enter opens the
 * highlighted one (or searches if none is), Escape closes the list.
 */
(() => {
    'use strict';

    const data = window.arandaSearch;
    if (!data || !Array.isArray(data.items)) return;

    const i18n = data.i18n || {};
    const LIMIT = 6;
    const MIN_LENGTH = 2;

    const words = (text) =>
        text
            .normalize('NFD')
            .replace(/[̀-ͯ]/g, '')
            .toLowerCase()
            .split(/[^a-z0-9]+/)
            .filter(Boolean);

    const entries = data.items.map((item, index) => ({
        item,
        index,
        all: item.w.split(' '),
        title: item.t.split(' '),
    }));

    const startsAWord = (list, term) => list.some((word) => word.startsWith(term));

    const find = (query) => {
        const terms = words(query);
        if (terms.length === 0) return [];

        return entries
            .filter((entry) => terms.every((term) => startsAWord(entry.all, term)))
            .map((entry) => ({
                entry,
                missing: terms.filter((term) => !startsAWord(entry.title, term)).length,
            }))
            .sort((a, b) => a.missing - b.missing || a.entry.index - b.entry.index)
            .slice(0, LIMIT)
            .map((match) => match.entry.item);
    };

    const span = (className, text) => {
        const element = document.createElement('span');
        element.className = className;
        element.textContent = text;
        return element;
    };

    const init = (form) => {
        const input = form.querySelector('.search-box-field');
        if (!input || !input.id) return;

        const list = document.createElement('ul');
        list.className = 'search-suggest';
        list.id = `${input.id}-suggestions`;
        list.setAttribute('role', 'listbox');
        list.setAttribute('aria-label', i18n.listing || '');
        list.hidden = true;

        const status = document.createElement('p');
        status.className = 'screen-reader-text';
        status.setAttribute('role', 'status');

        form.append(list, status);

        input.setAttribute('role', 'combobox');
        input.setAttribute('aria-autocomplete', 'list');
        input.setAttribute('aria-expanded', 'false');
        input.setAttribute('aria-controls', list.id);

        let options = [];
        let active = -1;

        const close = () => {
            list.hidden = true;
            list.replaceChildren();
            options = [];
            active = -1;
            input.setAttribute('aria-expanded', 'false');
            input.removeAttribute('aria-activedescendant');
            status.textContent = '';
        };

        const setActive = (position) => {
            options.forEach((option, index) => {
                const on = index === position;
                option.classList.toggle('is-active', on);
                option.setAttribute('aria-selected', String(on));
            });
            active = position;

            if (position >= 0) {
                input.setAttribute('aria-activedescendant', options[position].id);
                options[position].scrollIntoView({ block: 'nearest' });
            } else {
                input.removeAttribute('aria-activedescendant');
            }
        };

        const option = (id, href, className) => {
            const item = document.createElement('li');
            item.setAttribute('role', 'presentation');

            const link = document.createElement('a');
            link.id = id;
            link.href = href;
            link.className = className;
            link.setAttribute('role', 'option');
            link.setAttribute('aria-selected', 'false');
            link.tabIndex = -1;

            item.append(link);
            list.append(item);
            return link;
        };

        const render = () => {
            const query = input.value.trim();
            const found = query.length >= MIN_LENGTH ? find(query) : [];

            if (found.length === 0) {
                close();
                return;
            }

            list.replaceChildren();
            options = found.map((service, index) => {
                const link = option(`${list.id}-${index}`, service.u, 'search-suggest-item');
                if (service.b) {
                    link.target = '_blank';
                    link.rel = 'noopener';
                }
                link.append(
                    span('search-suggest-title', service.n),
                    span('search-suggest-text', service.d)
                );
                if (service.b && i18n.newTab) {
                    link.append(span('screen-reader-text', ` ${i18n.newTab}`));
                }
                return link;
            });

            const all = option(
                `${list.id}-all`,
                `${form.action}?s=${encodeURIComponent(query)}`,
                'search-suggest-item search-suggest-all'
            );
            all.textContent = (i18n.all || '%s').replace('%s', query);
            options.push(all);

            active = -1;
            list.hidden = false;
            input.setAttribute('aria-expanded', 'true');
            input.removeAttribute('aria-activedescendant');
            status.textContent =
                found.length === 1
                    ? i18n.one || ''
                    : (i18n.many || '%d').replace('%d', String(found.length));
        };

        input.addEventListener('input', render);
        input.addEventListener('focus', () => {
            if (list.hidden) render();
        });

        input.addEventListener('keydown', (event) => {
            const open = !list.hidden;

            if (event.key === 'ArrowDown' || event.key === 'ArrowUp') {
                if (!open) render();
                if (list.hidden) return;
                event.preventDefault();
                const last = options.length - 1;
                if (event.key === 'ArrowDown') {
                    setActive(active >= last ? 0 : active + 1);
                } else {
                    setActive(active <= 0 ? last : active - 1);
                }
            } else if (event.key === 'Enter' && open && active >= 0) {
                event.preventDefault();
                options[active].click();
            } else if (event.key === 'Escape' && open) {
                event.preventDefault();
                close();
            }
        });

        // A click on a suggestion must not blur the field first (Safari does not focus links), or the list would close
        // before the click arrives.
        list.addEventListener('mousedown', (event) => event.preventDefault());

        form.addEventListener('focusout', (event) => {
            if (!form.contains(event.relatedTarget)) close();
        });
    };

    document.querySelectorAll('.search-box').forEach(init);
})();
