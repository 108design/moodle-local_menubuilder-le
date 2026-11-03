<?php
/**
 * classes/local/module_authority.php
 *
 * @package local_menubuilder
 * @copyright 2026 Andreas Giesen <andreas@108design.com>
 * @license 108design source-available software license; see LICENSE.md
 */
namespace local_menubuilder\local;
defined('MOODLE_INTERNAL') || die();

/** Persistent ownership remains independent of installation order and entitlement expiry. */
final class module_authority {
    private static bool $writing = false;

    public static function owner(string $module = 'menubuilder'): string {
        if ($module !== 'menubuilder') {
            throw new \coding_exception('Unknown menu module.');
        }
        if (get_config('local_menubuilder', 'frontpagemigrationcompleted')) {
            return 'local_frontpage';
        }
        $saved = get_config('local_menubuilder', 'moduleowner');
        if ($saved !== false && $saved !== '') {
            return in_array($saved, ['local_frontpage', 'local_menubuilder'], true) ? $saved : 'conflict';
        }
        // Read existing authority evidence without creating activations or contacting a service.
        $hub = '\\local_lmh108\\local\\license_state';
        if (get_config('local_lmh108', 'version') && class_exists($hub) && ($record = $hub::record('menubuilder.pro'))) {
            $source = (string)($record->sourcecomponent ?? '');
            if ($source === 'local_menubuilder') {
                return $source;
            }
            if (in_array($source, ['local_frontpage', 'block_frontpage_menu', 'filter_frontpage_menu'], true)) {
                return 'local_frontpage';
            }
        }
        $sourceconfigured = (string)get_config('local_menubuilder', 'activationid') !== '';
        $targetconfigured = false;
        $target = '\\local_frontpage\\local\\licensing\\standalone_state';
        if (get_config('local_frontpage', 'version') && class_exists($target)) {
            $targetconfigured = $target::record('menubuilder.pro') !== null;
        }
        if ($sourceconfigured && $targetconfigured) {
            return 'conflict';
        }
        if ($targetconfigured) {
            return 'local_frontpage';
        }
        if ($sourceconfigured) {
            return 'local_menubuilder';
        }
        // A host with no menu facade does not provide this module.
        return get_config('local_frontpage', 'version') && class_exists('\\local_frontpage\\menu')
            ? 'local_frontpage' : 'local_menubuilder';
    }

    public static function initialise(string $module = 'menubuilder'): void {
        $lock = self::lock();
        try {
            if (!get_config('local_menubuilder', 'moduleowner')) {
                set_config('moduleowner', self::owner($module), 'local_menubuilder');
            }
        } finally {
            $lock->release();
        }
    }

    public static function is_owner(string $module = 'menubuilder'): bool {
        return self::owner($module) === 'local_menubuilder';
    }

    public static function assert_owner(string $module = 'menubuilder'): void {
        if (!self::is_owner($module)) {
            throw new \moodle_exception('moduleinactive', 'local_menubuilder');
        }
    }

    public static function write(string $module, callable $operation): mixed {
        if ($module !== 'menubuilder') {
            throw new \coding_exception('Unknown menu module.');
        }
        if (self::$writing) {
            gate::require_authoring();
            return $operation();
        }
        $lock = self::lock();
        try {
            gate::require_authoring();
            self::$writing = true;
            return $operation();
        } finally {
            self::$writing = false;
            $lock->release();
        }
    }

    private static function lock(): \core\lock\lock {
        $lock = \core\lock\lock_config::get_lock_factory('local_frontpage_modules')->get_lock('menubuilder', 10);
        if (!$lock) {
            throw new \moodle_exception('locktimeout');
        }
        return $lock;
    }

    public static function notice(string $module = 'menubuilder'): string {
        global $OUTPUT;
        return $OUTPUT->notification(get_string('moduleinactive', 'local_menubuilder'), 'warning');
    }
}
