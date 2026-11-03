<?php
/**
 * classes/local/menu_active_state.php
 *
 * @package local_menubuilder
 * @copyright 2026 Andreas Giesen <andreas@108design.com>
 * @license 108design source-available software license; see LICENSE.md
 */
namespace local_menubuilder\local;

/** Match explicit links against the current Moodle route. */
final class menu_active_state {
    public static function matches(array $item, string|\moodle_url|null $target): bool {
        global $PAGE;
        if ($target === null || $target === '' || (is_string($target) && str_starts_with($target, '#'))) {
            return false;
        }
        try {
            return (new \moodle_url($target))->compare($PAGE->url, URL_MATCH_EXACT);
        } catch (\Throwable) {
            return false;
        }
    }
}
