// Copyright (C) 2026 Andreas Giesen. See LICENSE.md.
import {basicSetup, EditorView} from 'codemirror';
import {EditorState} from '@codemirror/state';
import {json, jsonParseLinter} from '@codemirror/lang-json';
import {linter} from '@codemirror/lint';

define('local_menubuilder/menu_json_editor', [], () => ({init: labels => {
    const source = document.getElementById('id_definitionjson');
    if (!source || source.dataset.jsonEditorReady) return;
    const field = source.closest('.fitem');
    if (!field) return;
    const element = (tag, className, text) => {
        const node = document.createElement(tag);
        if (className) node.className = className;
        if (text !== undefined) node.textContent = text;
        return node;
    };
    const button = text => {
        const node = element('button', 'btn btn-outline-secondary', text);
        node.type = 'button';
        return node;
    };
    const root = element('section', 'local-menubuilder-json-workspace mb-4');
    root.setAttribute('aria-label', labels.workspace);
    const intro = element('p', 'small text-muted', labels.intro);
    const layout = element('div', 'local-menubuilder-json-layout');
    const preview = element('nav', 'local-menubuilder-json-preview');
    preview.setAttribute('aria-label', labels.preview);
    preview.append(element('h3', 'h6', labels.preview));
    const all = button(labels.complete);
    all.classList.add('mb-3');
    const entries = element('ol', 'local-menubuilder-json-entries');
    preview.append(all, entries);
    const editor = element('div', 'local-menubuilder-json-entry-editor');
    const completeEditor = element('div', 'local-menubuilder-json-full-editor');
    editor.hidden = true;
    const heading = element('h3', 'h5', labels.entry);
    const guidance = element('p', 'small text-muted', labels.guidance);
    const entryInput = element('textarea', 'form-control');
    entryInput.id = 'local-menubuilder-entry-json';
    entryInput.setAttribute('aria-label', labels.entry);
    entryInput.spellcheck = false;
    entryInput.rows = 20;
    const actions = element('div', 'local-menubuilder-json-actions');
    const apply = button(labels.apply);
    const discard = button(labels.discard);
    actions.append(apply, discard);
    const status = element('p', 'small mt-2 mb-0');
    status.setAttribute('role', 'status');
    status.setAttribute('aria-live', 'polite');
    editor.append(heading, guidance, entryInput, actions, status);
    layout.append(preview, completeEditor, editor);
    root.append(intro, layout);
    field.before(root);
    completeEditor.append(field);
    let definition = null;
    let selected = -1;
    let entryDirty = false;
    let sourceAtSelection = '';
    let updating = false;
    const createEditor = (input, onChange) => {
        const container = element('div', 'local-menubuilder-code-editor');
        input.after(container);
        const state = EditorState.create({doc: input.value, extensions: [basicSetup, json(),
            linter(jsonParseLinter()), EditorView.lineWrapping, EditorState.tabSize.of(4),
            EditorView.contentAttributes.of({'aria-label': input.getAttribute('aria-label') || labels.complete,
                'aria-multiline': 'true'}),
            EditorView.updateListener.of(update => {
                if (!update.docChanged) return;
                input.value = update.state.doc.toString();
                if (!updating) onChange();
            })]});
        const view = new EditorView({state, parent: container});
        input.hidden = true;
        return {
            focus: () => view.focus(),
            set: value => {
                updating = true;
                view.dispatch({changes: {from: 0, to: view.state.doc.length, insert: value}});
                updating = false;
            },
        };
    };
    const entryView = createEditor(entryInput, () => {
        entryDirty = true;
        apply.disabled = false;
        discard.disabled = false;
        status.textContent = labels.pending;
        status.classList.remove('text-danger');
    });
    const sourceView = createEditor(source, () => {
        source.dispatchEvent(new Event('input', {bubbles: true}));
        read();
    });
    const announce = (message, error = false) => {
        status.textContent = message;
        status.classList.toggle('text-danger', error);
    };
    const clearDirty = () => {
        entryDirty = false;
        apply.disabled = true;
        discard.disabled = true;
    };
    const choose = index => {
        if (entryDirty) { announce(labels.resolve, true); entryView.focus(); return; }
        selected = index;
        sourceAtSelection = source.value;
        completeEditor.hidden = index >= 0;
        editor.hidden = index < 0;
        all.setAttribute('aria-pressed', String(index < 0));
        entries.querySelectorAll('button').forEach((node, i) => node.setAttribute('aria-pressed', String(i === index)));
        if (index < 0) { sourceView.focus(); return; }
        heading.textContent = definition.items[index]?.title || labels.entry;
        entryView.set(JSON.stringify(definition.items[index], null, 4));
        clearDirty();
        announce(labels.entrynotice);
        entryView.focus();
    };
    const read = () => {
        entries.replaceChildren();
        try {
            definition = JSON.parse(source.value);
            if (!definition || !Array.isArray(definition.items)) throw new Error('items');
            definition.items.forEach((item, index) => {
                const row = element('li');
                const control = button('');
                control.className = 'local-menubuilder-json-preview-entry';
                // Invalid depth must never create unbounded indentation in the preview.
                control.style.setProperty('--menu-json-depth', String(Math.max(0, Math.min(2, Number(item?.depth) || 0))));
                const title = element('strong', '', item?.title || labels.entry);
                const target = element('span', 'small text-muted', item?.source?.url || labels.container);
                control.append(title, target);
                control.setAttribute('aria-pressed', String(index === selected));
                control.addEventListener('click', () => choose(index));
                row.append(control);
                entries.append(row);
            });
            if (!definition.items.length) entries.append(element('li', 'small text-muted p-2', labels.empty));
        } catch (error) {
            definition = null;
            entries.append(element('li', 'small text-danger p-2', labels.invalid));
        }
    };
    const applyEntry = () => {
        if (!entryDirty) return true;
        if (source.value !== sourceAtSelection) { announce(labels.conflict, true); return false; }
        try {
            const value = JSON.parse(entryInput.value);
            if (!value || typeof value !== 'object' || Array.isArray(value)) throw new Error('object');
            const current = JSON.parse(source.value);
            current.items[selected] = value;
            const text = JSON.stringify(current, null, 4);
            sourceView.set(text);
            sourceAtSelection = text;
            source.dispatchEvent(new Event('input', {bubbles: true}));
            clearDirty();
            read();
            heading.textContent = value.title || labels.entry;
            announce(labels.applied);
            return true;
        } catch (error) {
            announce(labels.entryinvalid + ' ' + error.message, true);
            entryView.focus();
            return false;
        }
    };
    all.addEventListener('click', () => choose(-1));
    apply.addEventListener('click', applyEntry);
    discard.addEventListener('click', () => {
        entryView.set(JSON.stringify(definition.items[selected], null, 4));
        clearDirty();
        announce(labels.entrynotice);
        entryView.focus();
    });
    source.addEventListener('input', () => {
        if (source.value !== sourceAtSelection && selected >= 0 && !entryDirty) {
            sourceView.set(source.value);
            selected = -1;
            completeEditor.hidden = false;
            editor.hidden = true;
        }
        read();
    });
    source.form.addEventListener('submit', event => {
        if (event.submitter?.name === 'cancel') return;
        if (!applyEntry()) { event.preventDefault(); event.stopImmediatePropagation(); }
    }, true);
    source.addEventListener('invalid', () => { completeEditor.hidden = false; editor.hidden = true; sourceView.focus(); });
    window.addEventListener('beforeunload', event => {
        if (entryDirty) { event.preventDefault(); event.returnValue = ''; }
    });
    clearDirty();
    all.setAttribute('aria-pressed', 'true');
    source.dataset.jsonEditorReady = '1';
    read();
}}));
