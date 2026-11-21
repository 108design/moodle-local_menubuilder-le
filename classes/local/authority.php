<?php
// This file is part of Moodle - https://moodle.org/
/**
 * classes/local/authority.php
 *
 * @package local_menubuilder
 * @copyright 2026 Andreas Giesen <andreas@108design.com>
 * @license 108design source-available software license; see LICENSE.md
 */

namespace local_menubuilder\local;

/**
 * Public host selection for menu consumers.
 */
final class authority {
    /**
     * Report whether this component currently owns its module.
     *
     * @return bool
     */
    public static function active(): bool {
        return module_authority::is_owner('menubuilder');
    }

    /**
     * Block configuration selects existing menus under Moodle's native block permissions.
     *
     * @return bool
     */
    public static function source_selection_available(): bool {
        return self::active();
    }
}
