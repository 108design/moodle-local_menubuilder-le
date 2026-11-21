<?php
// This file is part of Moodle - https://moodle.org/
/**
 * Native menu duplicate entry point for Menu Builder.
 *
 * @package   local_menubuilder
 * @copyright 2026 Andreas Giesen <andreas@108design.com>
 * @license   108design source-available software license; see LICENSE.md
 */

// This file is part of a 108design source-available software product.
require_once(__DIR__ . '/../../config.php');

use local_menubuilder\local\service\factory;
use local_menubuilder\local\gate;

$id = required_param('id', PARAM_INT);
require_login();
require_sesskey();
$context = context_system::instance();
require_capability('local/menubuilder:manage', $context);
gate::require_authoring();
if (($_SERVER['REQUEST_METHOD'] ?? '') !== 'POST') {
    throw new invalid_parameter_exception('Menu duplication requires POST.');
}

$repository = factory::menu_repository();
$source = $repository->get($id);
$copy = $repository->duplicate(
    $id,
    get_string('managedmenucopyname', 'local_menubuilder', (string) $source->name),
    (int) $USER->id
);
redirect(
    new moodle_url('/local/menubuilder/menu_edit.php', ['id' => $copy->id]),
    get_string('managedmenuduplicated', 'local_menubuilder'),
    null,
    \core\output\notification::NOTIFY_SUCCESS
);
