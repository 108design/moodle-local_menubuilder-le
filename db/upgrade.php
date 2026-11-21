<?php
// This file is part of Moodle - https://moodle.org/
/**
 * db/upgrade.php
 *
 * @package local_menubuilder
 * @copyright 2026 Andreas Giesen <andreas@108design.com>
 * @license 108design source-available software license; see LICENSE.md
 */

defined('MOODLE_INTERNAL') || die();

/**
 * Check that this package can update the installed plugin.
 *
 * @param int $oldversion Previously installed numeric plugin build.
 * @return bool
 */
function xmldb_local_menubuilder_upgrade(int $oldversion): bool {
    $supportedbuild = $oldversion >= 2026010100 && $oldversion <= 2026093099;
    $supportedsource = get_config('local_menubuilder', 'distribution') === 'marketplace';
    if (!$supportedbuild || !$supportedsource) {
        throw new moodle_exception('unsupportedupgrade', 'local_menubuilder');
    }
    return true;
}
