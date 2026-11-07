<?php
/**
 * classes/local/admin_navigation.php
 *
 * @package local_menubuilder
 * @copyright 2026 Andreas Giesen <andreas@108design.com>
 * @license 108design source-available software license; see LICENSE.md
 */

namespace local_menubuilder\local;

/**
 * Keep the menu list linked on its subordinate pages.
 */
final class admin_navigation {
    /**
     * Render the administration navigation permitted for the current user.
     *
     * @param string $active Active route or navigation key.
     * @return string
     */
    public static function render(string $active = 'menus'): string {
        global $PAGE;
        $list = basename($PAGE->url->get_path()) === 'menus.php';
        $tabs = \html_writer::tag(
            'nav',
            \html_writer::link(
                new \moodle_url('/local/menubuilder/menus.php'),
                \html_writer::tag('i', '', [ 'class' => 'fa fa-bars icon', 'aria-hidden' => 'true' ])
                . ' '
                . get_string('managedmenus', 'local_menubuilder'),
                ['class' => 'nav-link active', 'aria-current' => $list ? 'page' : null]
            ),
            ['class' => 'nav nav-tabs', 'aria-label' => get_string('pluginname', 'local_menubuilder')]
        );
        $action = $list && module_authority::is_owner() ? \html_writer::link(
            new \moodle_url('/local/menubuilder/menu_edit.php'),
            \html_writer::tag('i', '', ['class' => 'fa fa-plus icon', 'aria-hidden' => 'true'])
                . get_string('managedmenuadd', 'local_menubuilder'),
            ['class' => 'btn btn-primary']
        ) : '';
        return \html_writer::div($tabs . $action, 'local-menubuilder-admin-navigation mb-4');
    }
}
