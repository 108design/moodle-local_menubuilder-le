<?php
// This file is part of Moodle - https://moodle.org/
/**
 * menus.php
 *
 * @package local_menubuilder
 * @copyright 2026 Andreas Giesen <andreas@108design.com>
 * @license 108design source-available software license; see LICENSE.md
 */

require_once(__DIR__ . '/../../config.php');
require_once($CFG->libdir . '/adminlib.php');

use local_menubuilder\local\service\factory;
use local_menubuilder\local\admin_navigation;
use local_menubuilder\local\module_authority;
use local_menubuilder\output\context_help;

require_login();
require_capability('local/menubuilder:manage', context_system::instance());
admin_externalpage_setup('local_menubuilder');
$PAGE->set_url('/local/menubuilder/menus.php');
$PAGE->set_title(get_string('managedmenus', 'local_menubuilder'));
$PAGE->set_heading(get_string('pluginname', 'local_menubuilder'));
context_help::prepare('menus');
$PAGE->requires->js_call_amd('local_menubuilder/search_managers', 'init');
$query = trim(optional_param('q', '', PARAM_TEXT));
echo $OUTPUT->header();
echo admin_navigation::render();
if (!module_authority::is_owner()) {
    echo module_authority::notice();
    echo context_help::render();
    echo $OUTPUT->footer();
    exit;
}
echo html_writer::div(get_string('managedmenusintro', 'local_menubuilder'), 'mb-3');
echo html_writer::start_div('', ['data-search-bar-scope' => 'local_menubuilder-menus']);
echo \local_menubuilder\output\search_bar::controls($PAGE->url, [
    'q' => ['label' => get_string('search'), 'value' => $query,
        'placeholder' => get_string('searchbarmenus', 'local_menubuilder')],
], 'local_menubuilder-menus');
echo html_writer::start_div('', ['data-search-bar-results' => '1']);
$table = new html_table();
$table->attributes['class'] = 'generaltable table table-hover w-100 mb-4';
$table->head = [get_string('managedmenuname', 'local_menubuilder'),
    get_string('menuusage', 'local_menubuilder') . ' ' . context_help::icon('menudelete'),
    get_string('lastmodified'), get_string('actions')];
$repository = factory::menu_repository();
foreach ($repository->get_all() as $menu) {
    if (
        $query !== '' && !str_contains(
            core_text::strtolower($menu->name . ' ' . $menu->definitionkey),
            core_text::strtolower($query)
        )
    ) {
        continue;
    }
    $edit = new moodle_url('/local/menubuilder/menu_edit.php', ['id' => $menu->id]);
    $iconlink = static function (moodle_url $url, string $label, string $icon) use ($OUTPUT): string {
        return html_writer::link(
            $url,
            $OUTPUT->pix_icon($icon, '') . html_writer::span(s($label), 'sr-only'),
            ['class' => 'local-menubuilder-icon-button', 'title' => $label, 'aria-label' => $label]
        );
    };
    $actions = $iconlink($edit, get_string('edit'), 't/edit');
    $actions .= html_writer::tag(
        'form',
        html_writer::empty_tag('input', ['type' => 'hidden', 'name' => 'id', 'value' => $menu->id])
        . html_writer::empty_tag('input', ['type' => 'hidden', 'name' => 'sesskey', 'value' => sesskey()])
        . html_writer::tag(
            'button',
            $OUTPUT->pix_icon('t/copy', '')
            . html_writer::span(get_string('managedmenuduplicate', 'local_menubuilder'), 'sr-only'),
            [
            'type' => 'submit',
            'class' => 'local-menubuilder-icon-button',
            'title' => get_string('managedmenuduplicate', 'local_menubuilder'),
            'aria-label' => get_string('managedmenuduplicate', 'local_menubuilder'),
            ]
        ),
        ['method' => 'post', 'action' => (new moodle_url('/local/menubuilder/menu_duplicate.php'))->out(false),
        'class' => 'local-menubuilder-icon-form']
    );
    $actions .= $iconlink(
        new moodle_url('/local/menubuilder/menu_delete.php', [ 'id' => $menu->id ]),
        get_string('delete'),
        't/delete'
    );
    $usage = $repository->usage((int)$menu->id);
    $table->data[] = [html_writer::link($edit, format_string($menu->name))
            . html_writer::div(html_writer::tag('code', s($menu->definitionkey)), 'local-menubuilder-list-key small')
            . ($menu->description ? html_writer::div(s($menu->description), 'small text-muted') : ''),
        $usage ? html_writer::alist(array_map('s', $usage)) : get_string('menunoknownusage', 'local_menubuilder'),
        userdate($menu->timemodified, get_string('managerdateformat', 'local_menubuilder')),
         html_writer::div($actions, 'local-menubuilder-table-actions')];
}
if ($table->data) {
    echo html_writer::div(html_writer::table($table), 'table-responsive');
} else {
    echo html_writer::div(
        get_string($query === '' ? 'uinomenus' : 'uinoresults', 'local_menubuilder'),
        'local-menubuilder-empty'
    );
}
echo html_writer::end_div() . html_writer::end_div();
echo context_help::render();
echo $OUTPUT->footer();
