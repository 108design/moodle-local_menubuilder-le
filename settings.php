<?php
// This file is part of Moodle - https://moodle.org/
/**
 * settings.php
 *
 * @package local_menubuilder
 * @copyright 2026 Andreas Giesen <andreas@108design.com>
 * @license 108design source-available software license; see LICENSE.md
 */

defined('MOODLE_INTERNAL') || die();

if ($hassiteconfig) {
    $ADMIN->add('localplugins', new admin_externalpage(
        'local_menubuilder',
        get_string('pluginname', 'local_menubuilder'),
        new moodle_url('/local/menubuilder/menus.php'),
        'local/menubuilder:manage'
    ));
}
