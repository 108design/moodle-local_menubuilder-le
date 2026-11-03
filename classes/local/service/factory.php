<?php
/**
 * This file is part of a 108design source-available software product.
 *
 * Use and modification are permitted only under the Software License included
 * with this distribution. See LICENSE.md for the full terms.
 *
 * File documentation for classes/local/service/factory.php.
 *
 * @package   local_menubuilder
 * @copyright 2026 Andreas Giesen <andreas@108design.com>
 * @license   108design source-available software license; see LICENSE.md
 */
namespace local_menubuilder\local\service;

use local_menubuilder\local\persistence\menu_repository;

final class factory {
    public static function menu_repository(): menu_repository {
        global $DB;
        return new menu_repository($DB);
    }
}
