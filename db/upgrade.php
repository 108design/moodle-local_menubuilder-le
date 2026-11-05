<?php
/**
 * db/upgrade.php
 *
 * @package local_menubuilder
 * @copyright 2026 Andreas Giesen <andreas@108design.com>
 * @license 108design source-available software license; see LICENSE.md
 */

defined('MOODLE_INTERNAL') || die();

/**
 * Refuse an incompatible migration; this runs after native file replacement.
 *
 * @param int $oldversion Previously installed numeric plugin build.
 * @return bool
 */
function xmldb_local_menubuilder_upgrade(int $oldversion): bool {
    if (
        $oldversion < 2026010100 || $oldversion > 2026093099
            || get_config('local_menubuilder', 'distribution') !== 'marketplace'
    ) {
        throw new moodle_exception('unsupportedmigration', 'local_menubuilder');
    }
    return true;
}
