/**
 * @module local_menubuilder/menu
 * @copyright 2026 Andreas Giesen
 * @license 108design source-available software license; see LICENSE.md
 */
let started = false;
const toggleFor = dropdown => dropdown.querySelector(':scope > .local-menubuilder-menu-parent-control [data-local-menubuilder-menu-toggle]');
const setOpen = (dropdown, open) => {
    const menu = dropdown.querySelector(':scope > ul');
    const toggle = toggleFor(dropdown);
    if (!menu || !toggle) return;
    dropdown.classList.toggle('is-open', open);
    toggle.setAttribute('aria-expanded', String(open));
    menu.hidden = !open;
    menu.classList.toggle('show', open);
    if (!open) dropdown.classList.remove('local-menubuilder-menu-flyout-start');
    else positionFlyout(dropdown);
};
// Match desktop flyouts to the available viewport; inline mobile/block branches need no positioning.
const positionFlyout = dropdown => {
    dropdown.classList.remove('local-menubuilder-menu-flyout-start');
    const menu = dropdown.querySelector(':scope > ul');
    if (!menu || getComputedStyle(menu).position !== 'absolute'
            || !dropdown.parentElement.closest('.dropdown-menu')) return;
    const bounds = menu.getBoundingClientRect();
    if (bounds.right > window.innerWidth - 8 && dropdown.getBoundingClientRect().left - bounds.width >= 8) {
        dropdown.classList.add('local-menubuilder-menu-flyout-start');
    }
};
const close = (root, except = null) => root.querySelectorAll('.local-menubuilder-menu-dropdown.is-open').forEach(d => {
    if (!except || (d !== except && !d.contains(except))) setOpen(d, false);
});
const controls = list => [...list.querySelectorAll(':scope > li > a, :scope > li > .local-menubuilder-menu-dropdown > .local-menubuilder-menu-parent-control > a, :scope > li > .local-menubuilder-menu-dropdown > .local-menubuilder-menu-parent-control > button')];
export const init = () => {
    if (started) return;
    started = true;
    window.addEventListener('resize', () => {
        document.querySelectorAll('.local-menubuilder-menu-dropdown.is-open').forEach(positionFlyout);
    });
    document.addEventListener('click', event => {
        const root = event.target.closest('.local-menubuilder-menu-navigation');
        document.querySelectorAll('.local-menubuilder-menu-navigation').forEach(r => { if (!r.contains(event.target)) close(r); });
        const toggle = event.target.closest('[data-local-menubuilder-menu-toggle]');
        if (!root || !toggle) return;
        const dropdown = toggle.closest('.local-menubuilder-menu-dropdown');
        if (!dropdown) return;
        event.preventDefault();
        event.stopPropagation();
        const open = !dropdown.classList.contains('is-open');
        close(root, dropdown);
        setOpen(dropdown, open);
    }, true);
    document.addEventListener('keydown', event => {
        const root = event.target.closest('.local-menubuilder-menu-navigation');
        if (!root) return;
        const control = event.target.closest('a, button');
        if (!control) return;
        const dropdown = control.closest('.local-menubuilder-menu-dropdown');
        if (event.key === 'Escape') {
            const open = dropdown?.classList.contains('is-open') ? dropdown : control.closest('ul, .dropdown-menu')?.closest('.local-menubuilder-menu-dropdown');
            if (open) {
                event.preventDefault(); event.stopPropagation(); setOpen(open, false); toggleFor(open)?.focus();
            }
        } else if (['ArrowDown', 'ArrowUp', 'Home', 'End'].includes(event.key)) {
            const list = control.closest('ul, .dropdown-menu');
            const nodes = controls(list);
            const index = nodes.indexOf(control);
            if (!nodes.length) return;
            event.preventDefault(); event.stopPropagation();
            const next = event.key === 'Home' ? 0 : event.key === 'End' ? nodes.length - 1
                : (index + (event.key === 'ArrowDown' ? 1 : -1) + nodes.length) % nodes.length;
            nodes[next].focus();
        } else if (event.key === 'ArrowRight' && dropdown && control.hasAttribute('data-local-menubuilder-menu-toggle')) {
            event.preventDefault(); event.stopPropagation(); setOpen(dropdown, true);
            controls(dropdown.querySelector(':scope > ul'))[0]?.focus();
        } else if (event.key === 'ArrowLeft') {
            const owner = control.closest('ul, .dropdown-menu')?.closest('.local-menubuilder-menu-dropdown');
            if (owner) { event.preventDefault(); event.stopPropagation(); setOpen(owner, false); toggleFor(owner)?.focus(); }
        }
    }, true);
};
