// Copyright (C) 2026 Andreas Giesen. See the consuming plugin's LICENSE.md.
import {init as initSearchBar} from './search_bar';

export const init = (modules = []) => {
    document.querySelectorAll('form[data-search-bar]').forEach(form => {
        initSearchBar(form);
        if (form.dataset.searchManagerReady === '1') return;
        form.dataset.searchManagerReady = '1';
        form.addEventListener('search-bar-updated', () => {
            for (const item of modules) {
                require(['local_menubuilder/' + item.name], module => module.init(...(item.args || [])));
            }
        });
    });
};
