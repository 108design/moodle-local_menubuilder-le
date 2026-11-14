<?php
// This file is part of Moodle - https://moodle.org/
/**
 * classes/local/menu_renderer.php
 *
 * @package local_menubuilder
 * @copyright 2026 Andreas Giesen <andreas@108design.com>
 * @license 108design source-available software license; see LICENSE.md
 */

namespace local_menubuilder\local;

/**
 * Shared renderer for central menus used in blocks and filtered content.
 */
final class menu_renderer {
    /**
     * Styles.
     */
    public const STYLES = ['secondary', 'minimal', 'block'];
    /**
     * Paddings.
     */
    public const PADDINGS = ['none', 'compact', 'normal', 'spacious'];
    /**
     * @var int Submenuid.
     */
    private static int $submenuid = 0;

    /**
     * Render viewer-filtered menu items using the requested presentation.
     *
     * @param array $items Validated items to process.
     * @param array $options Presentation or operation options.
     * @return string
     */
    public static function render(array $items, array $options = []): string {
        // This static helper registers assets on the active native page.
        // phpcs:ignore moodle.PHP.ForbiddenGlobalUse.BadGlobal
        global $PAGE;
        if (!module_authority::is_owner() || !$items) {
            return '';
        }
        // phpcs:ignore moodle.PHP.ForbiddenGlobalUse.BadGlobal
        $PAGE->requires->js_call_amd('local_menubuilder/menu', 'init');
        $style = in_array($options['style'] ?? '', self::STYLES, true) ? $options['style'] : 'secondary';
        $padding = in_array($options['padding'] ?? '', self::PADDINGS, true) ? $options['padding'] : 'normal';
        return \html_writer::tag('nav', \html_writer::tag(
            'ul',
            self::items($items, 0),
            ['class' => 'nav local-menubuilder-menu-list']
        ), ['class' => 'local-menubuilder-menu local-menubuilder-menu-navigation'
                . ' local-menubuilder-menu-style-' . $style . ' local-menubuilder-menu-padding-' . $padding,
            'aria-label' => $options['label'] ?? get_string('navigation')]);
    }

    /**
     * Render one viewer-filtered branch for the native submenu adapter.
     *
     * @param array $items Validated items to process.
     * @return string
     */
    public static function branch_html(array $items): string {
        return self::items($items, 1);
    }

    /**
     * Render a viewer-filtered branch of menu items.
     *
     * @param array $items Validated items to process.
     * @param int $depth Current menu nesting depth.
     * @return string
     */
    private static function items(array $items, int $depth): string {
        $html = '';
        foreach ($items as $item) {
            $attributes = ['class' => ($depth === 0 ? 'nav-link' : 'dropdown-item')
                . (!empty($item['active']) ? ' active' : '')];
            if (!empty($item['active'])) {
                $attributes['aria-current'] = 'page';
            }
            if (($item['linktarget'] ?? '') === 'blank') {
                $attributes += ['target' => '_blank', 'rel' => 'noopener noreferrer'];
            }
            $label = ($item['iconhtml'] ?? '') . \html_writer::span(
                format_string($item['label']),
                'local-menubuilder-menu-item-text' . (empty($item['showlabel']) ? ' sr-only visually-hidden' : '')
            );
            $target = $item['target'];
            if ($item['children']) {
                $id = 'local-menubuilder-resolved-submenu-' . ++self::$submenuid;
                $button = ['type' => 'button', 'aria-expanded' => 'false', 'aria-controls' => $id,
                    'aria-label' => get_string(
                        'menusubmenutoggle',
                        'local_menubuilder',
                        html_to_text(format_string($item['label']), 0, false)
                    ),

                    'data-local-menubuilder-menu-toggle' => '1'];
                $glyph = \html_writer::span(
                    \html_writer::tag(
                        'i',
                        '',
                        [ 'class' => 'fa fa-chevron-down', 'aria-hidden' => 'true' ]
                    ),
                    'local-menubuilder-menu-chevron',
                    [ 'aria-hidden' => 'true' ]
                );
                if ($target === '') {
                    $control = \html_writer::tag('button', $label . $glyph, $button
                        + ['class' => $attributes['class'] . ' local-menubuilder-menu-parent-trigger']);
                } else {
                    $attributes['class'] .= ' local-menubuilder-menu-parent-link';
                    $control = \html_writer::link($target, $label, $attributes)
                        . \html_writer::tag('button', $glyph, $button
                            + ['class' => 'btn local-menubuilder-menu-submenu-toggle']);
                }
                $content = \html_writer::div(
                    \html_writer::div($control, 'local-menubuilder-menu-parent-control')
                    . \html_writer::tag(
                        'ul',
                        self::items($item['children'], $depth + 1),
                        ['id' => $id, 'class' => 'dropdown-menu', 'hidden' => 'hidden']
                    ),
                    'local-menubuilder-menu-dropdown'
                );
            } else {
                $content = \html_writer::link($target, $label, $attributes);
            }
            $html .= \html_writer::tag('li', $content, ['class' => $depth === 0 ? 'nav-item' : '']);
        }
        return $html;
    }
}
