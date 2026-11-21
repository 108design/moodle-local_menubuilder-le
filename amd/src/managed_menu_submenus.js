// This file is part of Moodle - https://moodle.org/
/**
 * File documentation for amd/src/managed_menu_submenus.js.
 *
 * @package   local_menubuilder
 * @copyright 2026 Andreas Giesen <andreas@108design.com>
 * @license   108design source-available software license; see LICENSE.md
 */
// This file is part of a 108design source-available software product.
/**
 * Restore linked managed-menu parents after Moodle reduces every parent to a disclosure-only anchor.
 *
 * @module local_menubuilder/managed_menu_submenus
 */

import {init as initialiseMenus} from 'local_menubuilder/menu';

const chevron = () => {
    const visual = document.createElement('span');
    visual.className = 'local-menubuilder-primary-nav-chevron';
    visual.setAttribute('aria-hidden', 'true');
    const glyph = document.createElement('i');
    glyph.className = 'fa fa-chevron-down';
    glyph.setAttribute('aria-hidden', 'true');
    visual.append(glyph);
    return visual;
};

let overflowRefreshPending = false;

/** Re-run Moodle's native More-menu measurement after a split-link enhancement changes root-item width. */
const refreshNativeOverflow = () => {
    if (overflowRefreshPending) return;
    overflowRefreshPending = true;
    window.requestAnimationFrame(() => window.requestAnimationFrame(() => {
        overflowRefreshPending = false;
        window.dispatchEvent(new Event('resize'));
    }));
};

const copyToggleAttributes = (source, target, type) => {
    ['id', 'role', 'tabindex', 'aria-controls', 'aria-expanded', 'aria-haspopup'].forEach(name => {
        if (source.hasAttribute(name)) target.setAttribute(name, source.getAttribute(name));
    });
    target.type = 'button';
    if (type === 'collapse') {
        // Keep Moodle's native mobile row classes. Replacing them with a generic .btn is what made managed roots
        // look translucent and unrelated to the other entries in the primary drawer.
        target.className = source.className;
        target.classList.remove('icons-collapse-expand', 'collapsed', 'd-flex');
        target.classList.add('local-menubuilder-primary-nav-toggle',
            'local-menubuilder-primary-nav-toggle-mobile');
    } else {
        target.className = 'btn nav-link local-menubuilder-primary-nav-toggle';
    }
    // Moodle 4.5/5.0 themes use Bootstrap 4 attributes while 5.1+ themes use Bootstrap 5.
    // Supplying both is harmless: each Bootstrap generation ignores the attribute it does not own.
    target.setAttribute('data-toggle', type);
    target.setAttribute('data-bs-toggle', type);
    const controlcolor = window.getComputedStyle(source).color;
    target.style.setProperty('--local-menubuilder-primary-nav-control-color', controlcolor);
};

const desktopParent = (marker, {url, label}) => {
    const opener = marker.closest([
        '.primary-navigation a.dropdown-toggle[data-toggle="dropdown"]',
        '.primary-navigation a.dropdown-toggle[data-bs-toggle="dropdown"]',
    ].join(','));
    if (!opener || opener.dataset.localMenubuilderParentEnhanced === 'true') return;
    const item = opener.closest('.dropdown.nav-item');
    if (!item) return;
    item.closest('.more-nav')?.classList.add('local-menubuilder-managed-more-nav');
    item.classList.add('local-menubuilder-primary-nav-parent');
    if (url) {
        const toggle = document.createElement('button');
        copyToggleAttributes(opener, toggle, 'dropdown');
        toggle.setAttribute('aria-label', label);
        toggle.append(chevron());
        opener.removeAttribute('id');
        opener.removeAttribute('data-toggle');
        opener.removeAttribute('data-bs-toggle');
        opener.removeAttribute('aria-controls');
        opener.removeAttribute('aria-haspopup');
        opener.removeAttribute('aria-expanded');
        opener.classList.remove('dropdown-toggle');
        opener.classList.add('local-menubuilder-primary-nav-parent-link');
        opener.href = url;
        opener.dataset.localMenubuilderParentEnhanced = 'true';
        opener.after(toggle);
    } else {
        const trigger = document.createElement('button');
        copyToggleAttributes(opener, trigger, 'dropdown');
        trigger.setAttribute('aria-label', label);
        trigger.classList.add('local-menubuilder-primary-nav-parent-trigger');
        while (opener.firstChild) trigger.append(opener.firstChild);
        trigger.append(chevron());
        trigger.dataset.localMenubuilderParentEnhanced = 'true';
        opener.replaceWith(trigger);
    }
    refreshNativeOverflow();
};

const mobileParent = (marker, {url, label}) => {
    const opener = marker.closest('a[data-toggle="collapse"], a[data-bs-toggle="collapse"]');
    if (!opener || opener.dataset.localMenubuilderParentEnhanced === 'true') return;
    const group = document.createElement('div');
    group.className = 'local-menubuilder-primary-nav-mobile-parent';
    opener.closest('.drawer-primary')?.classList.add('local-menubuilder-managed-primary-drawer');
    opener.before(group);
    if (url) {
        const toggle = document.createElement('button');
        copyToggleAttributes(opener, toggle, 'collapse');
        const target = opener.getAttribute('data-target') || opener.getAttribute('data-bs-target');
        toggle.setAttribute('data-target', target);
        toggle.setAttribute('data-bs-target', target);
        toggle.setAttribute('aria-label', label);
        toggle.append(chevron());
        opener.querySelectorAll('.expanded-icon, .collapsed-icon').forEach(icon => icon.remove());
        opener.removeAttribute('id');
        opener.removeAttribute('data-toggle');
        opener.removeAttribute('data-bs-toggle');
        opener.removeAttribute('data-target');
        opener.removeAttribute('data-bs-target');
        opener.removeAttribute('aria-controls');
        opener.removeAttribute('aria-expanded');
        opener.classList.remove('icons-collapse-expand', 'collapsed', 'd-flex');
        opener.classList.add('local-menubuilder-primary-nav-parent-link');
        opener.href = url;
        opener.dataset.localMenubuilderParentEnhanced = 'true';
        group.append(opener, toggle);
    } else {
        const trigger = document.createElement('button');
        copyToggleAttributes(opener, trigger, 'collapse');
        const target = opener.getAttribute('data-target') || opener.getAttribute('data-bs-target');
        trigger.setAttribute('data-target', target);
        trigger.setAttribute('data-bs-target', target);
        trigger.setAttribute('aria-label', label);
        trigger.classList.add('local-menubuilder-primary-nav-parent-trigger');
        opener.querySelectorAll('.expanded-icon, .collapsed-icon').forEach(icon => icon.remove());
        while (opener.firstChild) trigger.append(opener.firstChild);
        trigger.append(chevron());
        trigger.dataset.localMenubuilderParentEnhanced = 'true';
        group.append(trigger);
        opener.remove();
    }
};

let nextBranchId = 0;
const mountChildren = (target, html) => {
    target.innerHTML = html;
    const identifiers = new Map();
    target.querySelectorAll('[id]').forEach(node => {
        const id = `local-menubuilder-primary-child-${++nextBranchId}`;
        identifiers.set(node.id, id);
        node.id = id;
    });
    target.querySelectorAll('[aria-controls]').forEach(node => {
        const id = identifiers.get(node.getAttribute('aria-controls'));
        if (id) node.setAttribute('aria-controls', id);
    });
};

// Desktop and mobile export the same tree; each concrete disclosure needs its own DOM ID.
// Carry the complete filtered subtree through Core's two-level desktop template.
const restoreChildren = (marker, html) => {
    if (!html || marker.dataset.childrenRestored) return;
    const item = marker.closest('.dropdown.nav-item');
    const desktop = item?.querySelector(':scope > .dropdown-menu');
    if (desktop) {
        mountChildren(desktop, html);
        desktop.classList.add('local-menubuilder-menu-navigation');
        marker.dataset.childrenRestored = '1';
        return;
    }
    const row = marker.closest('.local-menubuilder-primary-nav-mobile-parent');
    const control = row?.querySelector('[aria-controls]');
    const collapse = control && document.getElementById(control.getAttribute('aria-controls'));
    if (collapse) {
        const nav = document.createElement('ul');
        nav.className = 'local-menubuilder-menu-navigation local-menubuilder-mobile-children';
        mountChildren(nav, html);
        collapse.replaceChildren(nav);
        marker.dataset.childrenRestored = '1';
    }
};

// Native navigation may move or remove a node before the observer callback runs.
const enhance = root => {
    if (!root || !root.isConnected) return;
    root.querySelectorAll('[data-local-menubuilder-primary-nav-parent]').forEach(marker => {
        let metadata;
        try {
            const bytes = Uint8Array.from(atob(marker.dataset.localMenubuilderPrimaryNavParent), char => char.charCodeAt(0));
            metadata = JSON.parse(new TextDecoder().decode(bytes));
        } catch {
            return; // Leave native navigation usable if a cached marker uses an older format.
        }
        desktopParent(marker, metadata);
        mobileParent(marker, metadata);
        restoreChildren(marker, metadata.children);
    });
    initialiseMenus();
};

let navigationObserver;

/** Bootstrap 4 Core More assumes one anchor and the click target's immediate parent.
 * Bootstrap 5 owns its capture-phase disclosure handling and must not be toggled twice.
 */
const legacyOverflowItem = target => {
    const item = target.closest?.(
        '.primary-navigation [data-region="moredropdown"] > .local-menubuilder-primary-nav-parent'
    );
    const legacyTrigger = item?.closest('.moremenu')?.querySelector(
        '[data-region="morebutton"] > [data-toggle="dropdown"]:not([data-bs-toggle])'
    );
    return legacyTrigger ? item : null;
};

const overflowClick = event => {
    const control = event.target.closest?.(
        '.local-menubuilder-primary-nav-toggle, .local-menubuilder-primary-nav-parent-link'
    );
    const item = control ? legacyOverflowItem(control) : null;
    if (!item || control.parentElement !== item) return;
    const submenu = item.querySelector(':scope > .dropdown-menu');
    if (!submenu) return;
    // Capture before Core's LI listener, while preserving normal URL activation on the linked label.
    event.stopPropagation();
    if (control.classList.contains('local-menubuilder-primary-nav-parent-link')) return;
    event.preventDefault();
    control.setAttribute('aria-expanded', String(submenu.classList.toggle('show')));
};

const overflowEscape = event => {
    if (event.key !== 'Escape') return;
    const item = legacyOverflowItem(event.target);
    const submenu = item?.querySelector(':scope > .dropdown-menu.show');
    const control = item?.querySelector(':scope > .local-menubuilder-primary-nav-toggle');
    if (!submenu || !control) return;
    event.preventDefault();
    event.stopPropagation();
    submenu.classList.remove('show');
    control.setAttribute('aria-expanded', 'false');
    control.focus();
};

/** Initialise current navigation and any mobile drawer nodes rendered later by the theme. */
export const init = () => {
    enhance(document);
    if (navigationObserver) return;
    document.addEventListener('click', overflowClick, true);
    document.addEventListener('keydown', overflowEscape, true);
    navigationObserver = new MutationObserver(records => {
        records.forEach(record => record.addedNodes.forEach(node => {
            if (node.nodeType === Node.ELEMENT_NODE) enhance(node.matches?.('[data-local-menubuilder-primary-nav-parent]')
                ? node.parentElement : node);
        }));
    });
    navigationObserver.observe(document.body, {childList: true, subtree: true});
};
