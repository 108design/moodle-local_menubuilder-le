/**
 * File documentation for js/esm/src/primary_navigation.js.
 *
 * @package   local_menubuilder
 * @copyright 2026 Andreas Giesen
 * @license   108design source-available software license; see LICENSE.md
 */
// This file is part of a 108design source-available software product.
// Copyright (C) 2026 Andreas Giesen. Licensed under LICENSE.md.
import {createElement, useEffect, useRef, useState} from 'react';
import CoreNav from '@moodle/lms/core/nav/Nav';
import {requireAsync} from '@moodle/lms/core/amd';

const escapeText = value => String(value ?? '').replace(/[&<>"']/g, character => ({
    '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#39;',
})[character]);

/** Translate native React props back to the native More template, preserving original managed labels. */
export const templateContext = (props, labels, id) => {
    const nodes = (items, originals, path) => items.map((item, index) => {
        const original = originals?.[item.key];
        const children = nodes(item.children || [], original?.children, `${path}-${index}`);
        return {
            key: item.key,
            text: original ? original.text : escapeText(item.text),
            title: item.title,
            url: escapeText(item.href),
            isactive: Boolean(item.active),
            forceintomoremenu: Boolean(item.forceintomoremenu),
            divider: Boolean(item.divider),
            haschildren: children.length > 0,
            children,
            moremenuid: `${path}-${index}`,
        };
    });
    return {
        nodearray: nodes(props.items || [], labels, id),
        moremenuid: id,
        navbarstyle: props.navbarstyle || 'navbar-nav',
        istablist: Boolean(props.istablist),
    };
};

let nextId = 0;

/** A primary-only adapter; the native template/AMD own their subtree, avoiding React/DOM competition. */
export default function PrimaryNavigation(props) {
    const config = window.M?.local_menubuilder_primary_navigation;
    const host = useRef(null);
    const id = useRef(`local_menubuilder-primary-${++nextId}`);
    const [rendered, setRendered] = useState(null);
    const [failure, setFailure] = useState(false);

    useEffect(() => {
        if (!config) return undefined;
        let cancelled = false;
        requireAsync('core/templates').then(Templates => Templates.renderForPromise(
            'core/moremenu', templateContext(props, config.labels, id.current)
        )).then(result => {
            if (!cancelled) setRendered(result);
        }).catch(error => {
            console.error('local_menubuilder primary navigation rendering failed', error);
            if (!cancelled) setFailure(true);
        });
        return () => { cancelled = true; };
    }, [config, props.items, props.navbarstyle, props.istablist]);

    useEffect(() => {
        if (!rendered || !host.current) return undefined;
        let cancelled = false;
        const initialise = async() => {
            const Templates = await requireAsync('core/templates');
            if (cancelled) return;
            Templates.runTemplateJS(rendered.js);
            // Enhancers must run on the final mounted nodes, including a second call if a block ran first.
            for (const name of config.modules) {
                const module = await requireAsync(name);
                if (cancelled) return;
                module.init();
            }
            host.current.parentElement?.classList.add(props.measuredclass || 'primarynav-measured');
        };
        initialise().catch(error => console.error('local_menubuilder primary navigation initialisation failed', error));
        return () => { cancelled = true; };
    }, [rendered]);

    if (!config || failure) return createElement(CoreNav, props);
    return createElement('div', {
        ref: host,
        className: 'local_menubuilder-primary-navigation-compat',
        dangerouslySetInnerHTML: {__html: rendered?.html || ''},
    });
}
