<?php
// This file is part of Moodle - https://moodle.org/
/**
 * db/install.php
 *
 * @package local_menubuilder
 * @copyright 2026 Andreas Giesen <andreas@108design.com>
 * @license 108design source-available software license; see LICENSE.md
 */

defined('MOODLE_INTERNAL') || die();

/**
 * Initialise persistent component state for Menu Builder.
 *
 * @return void
 */
function xmldb_local_menubuilder_install(): void {
    \local_menubuilder\local\module_authority::initialise();
    set_config('distribution', 'marketplace', 'local_menubuilder');
}
