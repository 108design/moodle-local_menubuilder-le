<?php
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
echo html_writer::link(new moodle_url('/local/menubuilder/menu_edit.php'),
    get_string('managedmenuadd', 'local_menubuilder'), ['class' => 'btn btn-primary mb-3']);
echo html_writer::tag('form',
    html_writer::tag('label', get_string('search'), ['for' => 'menubuilder-search', 'class' => 'mr-2 me-2'])
    . html_writer::empty_tag('input', ['type' => 'search', 'name' => 'q', 'id' => 'menubuilder-search',
        'value' => $query, 'class' => 'form-control d-inline-block w-auto mr-2 me-2'])
    . html_writer::tag('button', get_string('search'), ['type' => 'submit', 'class' => 'btn btn-secondary']),
    ['method' => 'get', 'action' => $PAGE->url->out(false), 'class' => 'mb-4']);
$table = new html_table();
$table->attributes['class'] = 'generaltable table table-hover w-100 mb-4';
$table->head = [get_string('managedmenuname', 'local_menubuilder'), get_string('menukey', 'local_menubuilder'),
    get_string('menuusage', 'local_menubuilder') . ' ' . context_help::icon('menudelete'),
    get_string('lastmodified'), get_string('actions')];
$repository = factory::menu_repository();
foreach ($repository->get_all() as $menu) {
    if ($query !== '' && !str_contains(core_text::strtolower($menu->name . ' ' . $menu->definitionkey),
            core_text::strtolower($query))) {
        continue;
    }
    $edit = new moodle_url('/local/menubuilder/menu_edit.php', ['id' => $menu->id]);
    $actions = html_writer::link($edit, get_string('edit'), ['class' => 'mr-2 me-2']);
    $actions .= html_writer::tag('form',
        html_writer::empty_tag('input', ['type' => 'hidden', 'name' => 'id', 'value' => $menu->id])
        . html_writer::empty_tag('input', ['type' => 'hidden', 'name' => 'sesskey', 'value' => sesskey()])
        . html_writer::tag('button', get_string('managedmenuduplicate', 'local_menubuilder'),
            ['type' => 'submit', 'class' => 'btn btn-link p-0']),
        ['method' => 'post', 'action' => (new moodle_url('/local/menubuilder/menu_duplicate.php'))->out(false),
            'class' => 'd-inline-block mr-2 me-2']);
    $actions .= html_writer::link(new moodle_url('/local/menubuilder/menu_delete.php', ['id' => $menu->id]),
        get_string('delete'));
    $usage = $repository->usage((int)$menu->id);
    $table->data[] = [html_writer::link($edit, format_string($menu->name))
            . ($menu->description ? html_writer::div(s($menu->description), 'small text-muted') : ''),
        html_writer::tag('code', s($menu->definitionkey)),
        $usage ? html_writer::alist(array_map('s', $usage)) : get_string('menunoknownusage', 'local_menubuilder'),
        userdate($menu->timemodified, get_string('strftimedatetimeshort', 'langconfig')), $actions];
}
echo html_writer::div(html_writer::table($table), 'table-responsive');
echo context_help::render();
echo $OUTPUT->footer();
