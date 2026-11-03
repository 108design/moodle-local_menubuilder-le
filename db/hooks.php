<?php
/**
 * db/hooks.php
 *
 * @package local_menubuilder
 * @copyright 2026 Andreas Giesen <andreas@108design.com>
 * @license 108design source-available software license; see LICENSE.md
 */
defined('MOODLE_INTERNAL') || die();

$callbacks = [
    ['hook' => \core\hook\navigation\primary_extend::class,
        'callback' => [\local_menubuilder\local\hook_callbacks::class, 'primary_extend'], 'priority' => -1000],
];
if (class_exists(\core\hook\output\before_import_map_config::class)) {
    $callbacks[] = ['hook' => \core\hook\output\before_import_map_config::class,
        'callback' => [\local_menubuilder\local\primary_navigation_compat::class, 'before_import_map_config']];
}

// Product identity on native administration and upgrade surfaces.
$callbacks[] = ['hook'=>\core\hook\output\before_standard_head_html_generation::class,
    'callback'=>[\local_menubuilder\local\admin_branding::class,'head']];
$callbacks[] = ['hook'=>\core\hook\output\before_http_headers::class,
    'callback'=>[\local_menubuilder\local\admin_branding::class,'prepare']];
