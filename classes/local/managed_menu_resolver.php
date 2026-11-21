<?php
// This file is part of Moodle - https://moodle.org/
/**
 * classes/local/managed_menu_resolver.php
 *
 * @package local_menubuilder
 * @copyright 2026 Andreas Giesen <andreas@108design.com>
 * @license 108design source-available software license; see LICENSE.md
 */

namespace local_menubuilder\local;

/**
 * Resolve only validated entries visible to the current viewer.
 */
final class managed_menu_resolver {
    /**
     * Resolve the validated menu for the current viewer.
     *
     * @param array $definition Validated menu definition.
     * @return array
     */
    public static function resolve(array $definition): array {
        return module_authority::is_owner() ? self::items(managed_menu_definition::tree($definition)) : [];
    }

    /**
     * Resolve menu items permitted for the current viewer.
     *
     * @param array $items Validated items to process.
     * @return array
     */
    private static function items(array $items): array {
        global $PAGE;
        $result = [];
        foreach ($items as $item) {
            if (!managed_menu_runtime::conditions_match($item['visibility']) || !self::destination_visible($item)) {
                continue;
            }
            $children = self::items($item['children']);
            $source = $item['source'];
            if ($source['type'] === 'container') {
                if (!$children) {
                    continue;
                }
                $url = '';
            } else if ($source['type'] === 'external' || str_starts_with($source['url'], '#')) {
                $url = $source['url'];
            } else {
                $url = (new \moodle_url($source['url']))->out(false);
            }
            $active = menu_active_state::matches($item, $url);
            $result[] = ['id' => $item['id'], 'label' => $item['title'], 'target' => $url,
                'linktarget' => $item['target'], 'iconhtml' => menu_icon::render_html($item['icon']),
                'showlabel' => $item['showLabel'], 'children' => $children, 'active' => $active,
                'branchactive' => $active || (bool)array_filter($children, static fn($c) => $c['branchactive'])];
        }
        return $result;
    }

    /**
     * Respect an installed destination's existing access policy.
     *
     * @param array $item Item to process.
     * @return bool
     */
    private static function destination_visible(array $item): bool {
        $url = $item['source']['url'];
        return !str_starts_with($url, '/local/storefront/')
            || !class_exists('\\local_storefront\\local\\store_access')
            || \local_storefront\local\store_access::can_view();
    }
}
