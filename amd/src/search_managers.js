// This file is part of Moodle - https://moodle.org/
/* This file is part of a 108design source-available software product. Use and modification are permitted only under the Software License included with this distribution. See LICENSE.md for the full terms. @package local_menubuilder @copyright 2026 Andreas Giesen <andreas@108design.com> @license 108design source-available software license; see LICENSE.md */
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
