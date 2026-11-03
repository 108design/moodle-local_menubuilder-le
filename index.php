<?php
/**
 * index.php
 *
 * @package local_menubuilder
 * @copyright 2026 Andreas Giesen <andreas@108design.com>
 * @license 108design source-available software license; see LICENSE.md
 */
require_once(__DIR__ . '/../../config.php');
require_login();
require_capability('local/menubuilder:manage', context_system::instance());
redirect(new moodle_url('/local/menubuilder/menus.php'));
