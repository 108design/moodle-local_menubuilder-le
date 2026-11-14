<?php
// This file is part of Moodle - https://moodle.org/
/**
 * lib.php
 *
 * @package local_menubuilder
 * @copyright 2026 Andreas Giesen <andreas@108design.com>
 * @license 108design source-available software license; see LICENSE.md
 */

defined('MOODLE_INTERNAL') || die();

/**
 * Page-specific contextual help in the native navbar.
 *
 * @param \renderer_base $renderer Native renderer used for output.
 * @return string
 */
function local_menubuilder_render_navbar_output(\renderer_base $renderer): string {
    return \local_menubuilder\output\context_help::navbar($renderer);
}
