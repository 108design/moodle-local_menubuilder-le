<?php
// This file is part of Moodle - https://moodle.org/
/**
 * classes/local/hook_callbacks.php
 *
 * @package local_menubuilder
 * @copyright 2026 Andreas Giesen <andreas@108design.com>
 * @license 108design source-available software license; see LICENSE.md
 */

namespace local_menubuilder\local;

/**
 * The sole global menu output path.
 */
final class hook_callbacks {
    /**
     * Append the current viewer's managed menu to native primary navigation.
     *
     * @param \core\hook\navigation\primary_extend $hook Native Moodle output hook.
     * @return void
     */
    public static function primary_extend(\core\hook\navigation\primary_extend $hook): void {
        if (!authority::active()) {
            return;
        }
        $menu = service\factory::menu_repository()->get_active_topnav();
        if ($menu) {
            managed_menu_runtime::apply_primary(
                $hook->get_primaryview(),
                managed_menu_definition::decode($menu->definitionjson),
                menu_placement_definition::decode($menu->placementjson)
            );
        }
    }
}
