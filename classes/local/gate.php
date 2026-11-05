<?php
/**
 * classes/local/gate.php
 *
 * @package local_menubuilder
 * @copyright 2026 Andreas Giesen <andreas@108design.com>
 * @license 108design source-available software license; see LICENSE.md
 */

namespace local_menubuilder\local;

/**
 * Menu management follows Moodle capabilities and persistent host ownership.
 */
final class gate {
    /**
     * Check whether the current module owner permits authoring.
     *
     * @return bool
     */
    public static function allows_authoring(): bool {
        return module_authority::is_owner('menubuilder')
            && has_capability('local/menubuilder:manage', \context_system::instance());
    }

    /**
     * Require the owning component's authoring policy.
     *
     * @return void
     */
    public static function require_authoring(): void {
        require_capability('local/menubuilder:manage', \context_system::instance());
        module_authority::assert_owner('menubuilder');
    }
}
