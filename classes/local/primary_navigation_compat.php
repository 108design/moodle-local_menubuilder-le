<?php
/**
 * File documentation for classes/local/primary_navigation_compat.php.
 *
 * @package   local_menubuilder
 * @copyright 2026 Andreas Giesen <andreas@108design.com>
 * @license   108design source-available software license; see LICENSE.md
 */
// This file is part of a 108design source-available software product.
namespace local_menubuilder\local;

/** Keep managed HTML labels and native More-menu behaviour across the 5.3 React boundary. */
final class primary_navigation_compat {
    private static array $modules = [];

    public static function supported(): bool {
        return class_exists(\core\hook\output\before_import_map_config::class)
            && method_exists(\core\navigation\output\primary::class, 'export_nodes_for_react');
    }

    /** Defer primary-only enhancers until the React adapter has mounted the native template. */
    public static function require_module(string $module): void {
        global $PAGE;
        if (self::supported()) {
            self::$modules[$module] = $module;
        } else {
            $PAGE->requires->js_call_amd($module, 'init');
        }
    }

    public static function capture(\navigation_node $primary): void {
        global $PAGE, $CFG;
        $PAGE->requires->css(new \moodle_url('/local/menubuilder/styles/primary_navigation.css', ['rev' => $CFG->jsrev]));
        if (!self::supported()) {
            return;
        }
        $PAGE->requires->data_for_js('M.local_menubuilder_primary_navigation', [
            // Preserve attribute escaping before Core's React exporter decodes the entire HTML string.
            // These labels already passed the ordinary visibility, visibility and permission checks.
            'labels' => self::labels($primary),
            'modules' => array_values(self::$modules),
        ]);
    }

    private static function labels(\navigation_node $parent): array {
        $labels = [];
        foreach ($parent->children as $node) {
            $labels[(string) $node->key] = [
                'text' => (string) $node->text,
                'children' => self::labels($node),
            ];
        }
        return $labels;
    }

    public static function before_import_map_config(\core\hook\output\before_import_map_config $hook): void {
        global $CFG;
        if (!self::supported() || !self::owns_primary()) {
            return;
        }
        // ESM asset requests do not share the page's placement/licensing context. Resolve this
        // exact primary-only entry on the page, rather than re-evaluating ownership in core/esm.
        $hook->add_import('@moodle/lms/core/nav/PrimaryNav', loader: new \core\url(
            '/local/menubuilder/js/esm/build/primary_navigation.js', ['rev' => $CFG->jsrev]
        ));
    }

    private static function owns_primary(): bool {
        return authority::active()
            && (bool) \local_menubuilder\local\service\factory::menu_repository()->get_active_topnav();
    }
}
