<?php
// This file is part of Moodle - https://moodle.org/
/**
 * Construct the component repositories and application services.
 *
 * @package   local_menubuilder
 * @copyright 2026 Andreas Giesen <andreas@108design.com>
 * @license   108design source-available software license; see LICENSE.md
 */

namespace local_menubuilder\local\service;

use local_menubuilder\local\persistence\menu_repository;

/**
 * Construct the component repositories and application services.
 */
final class factory {
    /**
     * Construct the repository for shared menu definitions and placements.
     *
     * @return menu_repository
     */
    public static function menu_repository(): menu_repository {
        global $DB;
        return new menu_repository($DB);
    }
}
