<?php
/**
 * classes/local/admin_navigation.php
 *
 * @package local_menubuilder
 * @copyright 2026 Andreas Giesen <andreas@108design.com>
 * @license 108design source-available software license; see LICENSE.md
 */
namespace local_menubuilder\local;

/** Keep the menu list linked on its subordinate pages. */
final class admin_navigation {
    public static function render(string $active = 'menus'): string {
        return \html_writer::tag('nav', \html_writer::link(new \moodle_url('/local/menubuilder/menus.php'),
            get_string('managedmenus', 'local_menubuilder'), ['class' => 'nav-link active', 'aria-current' => 'page']),
            ['class' => 'nav nav-tabs mb-4', 'aria-label' => get_string('pluginname', 'local_menubuilder')]);
    }
}
