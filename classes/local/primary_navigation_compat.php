<?php
/**
 * Keep managed HTML labels and native More-menu behaviour across the 5.3 React boundary.
 *
 * @package   local_menubuilder
 * @copyright 2026 Andreas Giesen <andreas@108design.com>
 * @license   108design source-available software license; see LICENSE.md
 */

// This file is part of a 108design source-available software product.
namespace local_menubuilder\local;

/**
 * Keep managed HTML labels and native More-menu behaviour across the 5.3 React boundary.
 */
final class primary_navigation_compat {
    /**
     * @var array Modules.
     */
    private static array $modules = [];

    /**
     * Check whether the native primary-navigation adapter is available.
     *
     * @return bool
     */
    public static function supported(): bool {
        return class_exists(\core\hook\output\before_import_map_config::class)
            && method_exists(\core\navigation\output\primary::class, 'export_nodes_for_react');
    }

    /**
     * Defer primary-only enhancers until the React adapter has mounted the native template.
     *
     * @param string $module Module key whose owner is being resolved.
     * @return void
     */
    public static function require_module(string $module): void {
        global $PAGE;
        if (self::supported()) {
            self::$modules[$module] = $module;
        } else {
            $PAGE->requires->js_call_amd($module, 'init');
        }
    }

    /**
     * Capture the native navigation labels needed by the client adapter.
     *
     * @param \navigation_node $primary Primary.
     * @return void
     */
    public static function capture(\navigation_node $primary): void {
        global $PAGE, $CFG;
        $PAGE->requires->css(
            new \moodle_url(
                '/local/menubuilder/styles/primary_navigation.css',
                [ 'rev' => $CFG->jsrev ]
            )
        );
        if (!self::supported()) {
            return;
        }
        $PAGE->requires->data_for_js('M.local_menubuilder_primary_navigation', [
            // Preserve attribute escaping before Core's React exporter decodes the entire HTML string.
            // These labels already passed the ordinary visibility and permission checks.
            'labels' => self::labels($primary),
            'modules' => array_values(self::$modules),
        ], true);
    }

    /**
     * Return the captured labels for the native primary navigation.
     *
     * @param \navigation_node $parent Parent record or structure.
     * @return array
     */
    private static function labels(\navigation_node $parent): array {
        $labels = [];
        foreach ($parent->children as $node) {
            $labels[(string) $node->key] = [ 'text' => (string) $node->text, 'children' => self::labels($node) ];
        }
        return $labels;
    }

    /**
     * Expose the captured navigation data before native import-map output.
     *
     * @param \core\hook\output\before_import_map_config $hook Native Moodle output hook.
     * @return void
     */
    public static function before_import_map_config(\core\hook\output\before_import_map_config $hook): void {
        global $CFG, $DB;
        // Asset routes bootstrap without a database; ownership is resolved on the page.
        if (!($DB instanceof \moodle_database) || !self::supported() || !self::owns_primary()) {
            return;
        }
        // ESM asset requests do not share the page's placement/licensing context. Resolve this
        // exact primary-only entry on the page, rather than re-evaluating ownership in core/esm.
        $hook->add_import('@moodle/lms/core/nav/PrimaryNav', loader: new \core\url(
            '/local/menubuilder/js/esm/build/primary_navigation.js',
            ['rev' => $CFG->jsrev]
        ));
    }

    /**
     * Check whether this component owns the active primary menu.
     *
     * @return bool
     */
    private static function owns_primary(): bool {
        return authority::active()
            && (bool) \local_menubuilder\local\service\factory::menu_repository()->get_active_topnav();
    }
}
