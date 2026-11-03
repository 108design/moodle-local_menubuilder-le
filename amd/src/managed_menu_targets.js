/**
 * File documentation for amd/src/managed_menu_targets.js.
 *
 * @package   local_menubuilder
 * @copyright 2026 Andreas Giesen <andreas@108design.com>
 * @license   108design source-available software license; see LICENSE.md
 */
// This file is part of a 108design source-available software product.

const apply = root => {
    const markers = [];
    if (root.matches?.('[data-local-menubuilder-managed-menu-new-tab]')) markers.push(root);
    markers.push(...root.querySelectorAll?.('[data-local-menubuilder-managed-menu-new-tab]') || []);
    markers.forEach(marker => {
    const link = marker.closest('a[href]');
    if (!link) return;
    link.target = '_blank';
    link.rel = 'noopener noreferrer';
    });
};

/** Apply validated managed-menu link targets after Moodle/the theme has rendered primary navigation. */
export const init = () => {
    apply(document);
    const navigation = document.querySelector('header, nav, [data-region="morebutton"]') || document.body;
    new MutationObserver(records => records.forEach(record => record.addedNodes.forEach(node => {
        if (node.nodeType === Node.ELEMENT_NODE) apply(node);
    }))).observe(navigation, {childList: true, subtree: true});
};
