<?php
/**
 * menu_edit.php
 *
 * @package local_menubuilder
 * @copyright 2026 Andreas Giesen <andreas@108design.com>
 * @license 108design source-available software license; see LICENSE.md
 */

require_once(__DIR__ . '/../../config.php');

use local_menubuilder\form\managed_menu_form;
use local_menubuilder\local\managed_menu_definition;
use local_menubuilder\local\service\factory;
use local_menubuilder\local\admin_navigation;
use local_menubuilder\output\context_help;

require_login();
\local_menubuilder\local\gate::require_authoring();
$id = optional_param('id', 0, PARAM_INT);
$repository = factory::menu_repository();
$record = $id ? $repository->get($id) : null;
$PAGE->set_context(context_system::instance());
$PAGE->set_url('/local/menubuilder/menu_edit.php', $id ? ['id' => $id] : []);
$PAGE->set_pagelayout('admin');
$title = get_string($id ? 'managedmenuedit' : 'managedmenuadd', 'local_menubuilder');
$PAGE->set_title($title);
$PAGE->set_heading($title);
$PAGE->navbar->add(get_string('managedmenus', 'local_menubuilder'), new moodle_url('/local/menubuilder/menus.php'));
$PAGE->navbar->add($title);
context_help::prepare('menus');
$form = new managed_menu_form();
if ($form->is_cancelled()) {
    redirect(new moodle_url('/local/menubuilder/menus.php'));
}
$saveerror = '';
if ($data = $form->get_data()) {
    try {
        $saved = $repository->save(
            $id ?: null,
            trim($data->name),
            trim($data->description),
            !empty($data->topnav),
            $data->definitionjson,
            (int)$data->version,
            (int)$USER->id
        );
        redirect(
            new moodle_url('/local/menubuilder/menu_edit.php', ['id' => $saved->id]),
            get_string('changessaved'),
            null,
            \core\output\notification::NOTIFY_SUCCESS
        );
    } catch (moodle_exception $e) {
        $saveerror = $e->getMessage();
    }
}
$form->set_data((object)[
    'id' => $id, 'name' => $record->name ?? '', 'description' => $record->description ?? '',
    'topnav' => $record->topnav ?? 0, 'version' => $record->version ?? 0,
    'definitionjson' => managed_menu_definition::encode($record
        ? managed_menu_definition::decode($record->definitionjson) : managed_menu_definition::empty(), true),
]);
echo $OUTPUT->header();
echo admin_navigation::render();
if ($saveerror !== '') {
    echo $OUTPUT->notification(s($saveerror), 'error');
}
if ($record) {
    echo html_writer::start_tag('details', ['class' => 'local-menubuilder-use-details mb-4']);
    echo html_writer::tag('summary', get_string('menuuse', 'local_menubuilder'));
    echo html_writer::start_div('p-3');
    echo html_writer::tag('h3', get_string('menuuse', 'local_menubuilder'), ['class' => 'h5']);
    echo html_writer::div(html_writer::tag('code', s($record->definitionkey)), 'mb-2');
    echo html_writer::div(html_writer::tag('code', s('[[frontpage-menu:' . $record->definitionkey . ']]')), 'mb-3');
    echo html_writer::div(
        get_string('menublockuse', 'local_menubuilder') . ' ' . context_help::icon('menuuse'),
        'mb-4'
    );
    $usage = $repository->usage($id);
    echo html_writer::tag('h3', get_string('menuusage', 'local_menubuilder'), ['class' => 'h5']);
    echo html_writer::div($usage ? html_writer::alist(array_map('s', $usage))
        : get_string('menunoknownusage', 'local_menubuilder'), 'mb-4');
    echo html_writer::end_div() . html_writer::end_tag('details');
}
$form->display();
echo context_help::render();
echo $OUTPUT->footer();
