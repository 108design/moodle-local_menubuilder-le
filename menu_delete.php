<?php
/**
 * Native menu delete entry point for Menu Builder.
 *
 * @package   local_menubuilder
 * @copyright 2026 Andreas Giesen <andreas@108design.com>
 * @license   108design source-available software license; see LICENSE.md
 */

// This file is part of a 108design source-available software product.
require_once(__DIR__ . '/../../config.php');

use local_menubuilder\local\admin_navigation;
use local_menubuilder\local\gate;
use local_menubuilder\local\service\factory;

$id = required_param('id', PARAM_INT);
$confirm = optional_param('confirm', 0, PARAM_BOOL);
require_login();
$context = context_system::instance();
require_capability('local/menubuilder:manage', $context);
gate::require_authoring();
$repository = factory::menu_repository();
$menu = $repository->get($id);
$url = new moodle_url('/local/menubuilder/menu_delete.php', ['id' => $id]);
$PAGE->set_context($context);
$PAGE->set_url($url);
$PAGE->set_pagelayout('admin');
$PAGE->set_title(get_string('managedmenudelete', 'local_menubuilder'));
$PAGE->set_heading(get_string('managedmenudelete', 'local_menubuilder'));
$PAGE->navbar->add(
    get_string('managedmenus', 'local_menubuilder'),
    new moodle_url('/local/menubuilder/menus.php')
);
$PAGE->navbar->add(get_string('managedmenudelete', 'local_menubuilder'));

$usage = $repository->usage($id);
if ($confirm && ($_SERVER['REQUEST_METHOD'] ?? '') === 'POST') {
    require_sesskey();
    $repository->delete($id);
    redirect(
        new moodle_url('/local/menubuilder/menus.php'),
        get_string('managedmenudeleted', 'local_menubuilder'),
        null,
        \core\output\notification::NOTIFY_SUCCESS
    );
}

\local_menubuilder\output\context_help::prepare('menus');
echo $OUTPUT->header();
echo admin_navigation::render('menus');
echo $OUTPUT->heading(format_string($menu->name), 3);
if ($usage) {
    echo $OUTPUT->notification(get_string('managedmenudeleteused', 'local_menubuilder'), 'error');
    echo html_writer::alist(array_map('s', $usage));
    echo $OUTPUT->single_button(new moodle_url('/local/menubuilder/menus.php'), get_string('back'));
} else {
    echo $OUTPUT->confirm(
        get_string('managedmenudeleteconfirm', 'local_menubuilder', format_string($menu->name)),
        new moodle_url($url, ['confirm' => 1, 'sesskey' => sesskey()]),
        new moodle_url('/local/menubuilder/menus.php')
    );
}
echo \local_menubuilder\output\context_help::render();
echo $OUTPUT->footer();
