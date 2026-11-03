<?php
/**
 * This file is part of a 108design source-available software product.
 *
 * Use and modification are permitted only under the Software License included
 * with this distribution. See LICENSE.md for the full terms.
 *
 * File documentation for classes/menu.php.
 *
 * @package   local_menubuilder
 * @copyright 2026 Andreas Giesen <andreas@108design.com>
 * @license   108design source-available software license; see LICENSE.md
 */
namespace local_menubuilder;

use local_menubuilder\local\managed_menu_definition;
use local_menubuilder\local\managed_menu_resolver;
use local_menubuilder\local\menu_renderer;
use local_menubuilder\local\service\factory;

/** Stable public facade for block, filter and third-party consumers. */
final class menu {
    public static function choices(): array {
        $choices = [];
        foreach (factory::menu_repository()->get_all() as $record) {
            $choices[(string) $record->definitionkey] = format_string($record->name);
        }
        return $choices;
    }

    public static function source_choices(): array {
        $choices = [];
        foreach (self::choices() as $key => $label) {
            $choices['central:' . $key] = $label;
        }
        return $choices ? [get_string('menusourcecentralgroup', 'local_menubuilder') => $choices] : [];
    }

    public static function source_info(string $source): ?array {
        if (!preg_match('/^(?:standalone:)?central:([a-zA-Z0-9][a-zA-Z0-9_-]{7,99})$/', $source, $match)) {
            return null;
        }
        try {
            $record = factory::menu_repository()->get_by_key($match[1]);
            return ['source' => 'central:' . $match[1], 'type' => 'central',
                'name' => format_string($record->name),
                'editurl' => (new \moodle_url('/local/menubuilder/menu_edit.php', ['id' => $record->id]))->out(false),
                'authority' => 'standalone'];
        } catch (\Throwable) {
            return null;
        }
    }

    public static function render(string $menukey, array $options = []): string {
        try {
            $record = factory::menu_repository()->get_by_key($menukey);
            return menu_renderer::render(managed_menu_resolver::resolve(
                managed_menu_definition::decode($record->definitionjson)), $options);
        } catch (\Throwable) {
            return '';
        }
    }

    public static function render_source(string $source, array $options = []): string {
        $info = self::source_info($source);
        return $info ? self::render(substr($info['source'], strlen('central:')), $options) : '';
    }
}
