<?php
/**
 * classes/local/managed_menu_runtime.php
 *
 * @package local_menubuilder
 * @copyright 2026 Andreas Giesen <andreas@108design.com>
 * @license 108design source-available software license; see LICENSE.md
 */

namespace local_menubuilder\local;

/**
 * Append authored nodes without capturing, hiding or editing native Moodle nodes.
 */
final class managed_menu_runtime {
    /**
     * Append viewer-filtered menu nodes without replacing native navigation.
     *
     * @param \navigation_node $primary Primary.
     * @param array $definition Validated menu definition.
     * @param array $placement Menu placement settings.
     * @return void
     */
    public static function apply_primary(\navigation_node $primary, array $definition, array $placement = []): void {
        if (!module_authority::is_owner()) {
            return;
        }
        menu_placement_definition::validate($placement ?: menu_placement_definition::empty());
        $items = managed_menu_resolver::resolve($definition);
        if (!$items) {
            return;
        }
        primary_navigation_compat::require_module('local_menubuilder/managed_menu_submenus');
        primary_navigation_compat::require_module('local_menubuilder/managed_menu_targets');
        foreach ($items as $item) {
            self::add_item($primary, $item, 0);
        }
        primary_navigation_compat::capture($primary);
    }

    /**
     * Append one managed item and its children to the navigation tree.
     *
     * @param \navigation_node $parent Parent record or structure.
     * @param array $item Item to process.
     * @param int $depth Current menu nesting depth.
     * @return void
     */
    private static function add_item(\navigation_node $parent, array $item, int $depth): void {
        global $PAGE;
        $url = $item['target'] === '' ? null : new \moodle_url($item['target']);
        if (str_starts_with($item['target'], '#')) {
            $url = new \moodle_url($PAGE->url);
            $url->set_anchor(substr($item['target'], 1));
        }
        $text = $item['iconhtml'] . \html_writer::span(
            format_string($item['label']),
            'local-menubuilder-primary-nav-text' . (!$item['showlabel'] ? ' sr-only visually-hidden' : '')
        );
        $text = \html_writer::span($text, 'local-menubuilder-primary-nav-label');
        if ($item['linktarget'] === 'blank' && $url) {
            $text = \html_writer::span(
                $text,
                'local-menubuilder-managed-menu-new-tab',
                ['data-local-menubuilder-managed-menu-new-tab' => '1']
            );
        }
        if ($item['children']) {
            // Core 5.3 decodes entities in the entire label, including attribute values. Keep the
            // filtered HTML and associated text intact through both native navigation exporters.
            $metadata = [
                'url' => $url ? $url->out(false) : '',
                'label' => get_string(
                    'menusubmenutoggle',
                    'local_menubuilder',
                    html_to_text(format_string($item['label']), 0, false)
                ),
                // Native exporters flatten deeper levels. Carry only viewer-filtered rendered children.
                'children' => $depth === 0 ? menu_renderer::branch_html($item['children']) : '',
            ];
            $text = \html_writer::span($text, 'local-menubuilder-primary-nav-parent-marker', [
                'data-local-menubuilder-primary-nav-parent' => base64_encode(json_encode($metadata, JSON_THROW_ON_ERROR)),
            ]);
        }
        $node = \navigation_node::create($text, $url, $url ? \navigation_node::TYPE_CUSTOM
            : \navigation_node::TYPE_CONTAINER, null, 'local_menubuilder_menu_' . $item['id']);
        $node = $parent->add_node($node);
        if ($item['active']) {
            $node->make_active();
        }
        foreach ($item['children'] as $child) {
            self::add_item($node, $child, $depth + 1);
        }
    }

    /**
     * Evaluate menu visibility against the current login, roles and cohorts.
     *
     * @param array $conditions Conditions.
     * @return bool
     */
    public static function conditions_match(array $conditions): bool {
        global $DB, $USER;
        $authenticated = isloggedin() && !isguestuser();
        if ($conditions['auth'] === 'authenticated' && !$authenticated) {
            return false;
        }
        if ($conditions['auth'] === 'guest' && $authenticated) {
            return false;
        }
        $isadmin = is_siteadmin($USER);
        if (!empty($conditions['admin']) && !$isadmin) {
            return false;
        }
        // Site administrators need no explicit system-role or cohort membership.
        // Login conditions and destination/licensing checks remain independent.
        if ($isadmin) {
            return true;
        }
        if ($conditions['roles']) {
            $contextid = \context_system::instance()->id;
            $matches = false;
            foreach ($conditions['roles'] as $roleshortname) {
                $roleid = (int) $DB->get_field('role', 'id', ['shortname' => $roleshortname]);
                if (user_has_role_assignment($USER->id, $roleid, $contextid)) {
                    $matches = true;
                    break;
                }
            }
            if (!$matches) {
                return false;
            }
        }
        if ($conditions['cohorts']) {
            [$insql, $params] = $DB->get_in_or_equal($conditions['cohorts'], SQL_PARAMS_NAMED, 'menucohort');
            $params['contextid'] = \context_system::instance()->id;
            $cohortids = array_keys($DB->get_records_select(
                'cohort',
                "idnumber $insql AND contextid = :contextid",
                $params,
                '',
                'id'
            ));
            if (!$cohortids) {
                return false;
            }
            [$cohortsql, $cohortparams] = $DB->get_in_or_equal($cohortids, SQL_PARAMS_NAMED, 'menucohortid');
            $cohortparams['userid'] = $USER->id;
            if (
                !$DB->record_exists_select(
                    'cohort_members',
                    "userid = :userid AND cohortid $cohortsql",
                    $cohortparams
                )
            ) {
                return false;
            }
        }
        return true;
    }
}
