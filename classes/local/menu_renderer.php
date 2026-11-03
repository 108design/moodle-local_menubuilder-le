<?php
/**
 * classes/local/menu_renderer.php
 *
 * @package local_menubuilder
 * @copyright 2026 Andreas Giesen <andreas@108design.com>
 * @license 108design source-available software license; see LICENSE.md
 */
namespace local_menubuilder\local;

/** Shared renderer for central menus used in blocks and filtered content. */
final class menu_renderer {
    public const STYLES = ['secondary', 'minimal', 'block'];
    public const PADDINGS = ['none', 'compact', 'normal', 'spacious'];
    private static int $submenuid = 0;

    public static function render(array $items, array $options = []): string {
        global $PAGE;
        if (!module_authority::is_owner() || !$items) {
            return '';
        }
        $PAGE->requires->js_call_amd('local_menubuilder/menu', 'init');
        $style = in_array($options['style'] ?? '', self::STYLES, true) ? $options['style'] : 'secondary';
        $padding = in_array($options['padding'] ?? '', self::PADDINGS, true) ? $options['padding'] : 'normal';
        return \html_writer::tag('nav', \html_writer::tag('ul', self::items($items, 0),
            ['class' => 'nav local-menubuilder-menu-list']), ['class' => 'local-menubuilder-menu-navigation'
                . ' local-menubuilder-menu-style-' . $style . ' local-menubuilder-menu-padding-' . $padding,
            'aria-label' => $options['label'] ?? get_string('navigation')]);
    }

    public static function branch_html(array $items): string {
        return self::items($items, 1);
    }

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
            $label = ($item['iconhtml'] ?? '') . \html_writer::span(format_string($item['label']),
                'local-menubuilder-menu-item-text' . (empty($item['showlabel']) ? ' sr-only visually-hidden' : ''));
            $target = $item['target'];
            if ($item['children']) {
                $id = 'local-menubuilder-resolved-submenu-' . ++self::$submenuid;
                $button = ['type' => 'button', 'aria-expanded' => 'false', 'aria-controls' => $id,
                    'aria-label' => get_string('menusubmenutoggle', 'local_menubuilder', format_string($item['label'])),
                    'data-local-menubuilder-menu-toggle' => '1'];
                $glyph = \html_writer::tag('i', '', ['class' => 'fa fa-chevron-down', 'aria-hidden' => 'true']);
                if ($target === '') {
                    $control = \html_writer::tag('button', $label . $glyph, $button
                        + ['class' => $attributes['class'] . ' local-menubuilder-menu-parent-trigger']);
                } else {
                    $attributes['class'] .= ' local-menubuilder-menu-parent-link';
                    $control = \html_writer::link($target, $label, $attributes)
                        . \html_writer::tag('button', $glyph, $button
                            + ['class' => 'btn local-menubuilder-menu-submenu-toggle']);
                }
                $content = \html_writer::div(\html_writer::div($control, 'local-menubuilder-menu-parent-control')
                    . \html_writer::tag('ul', self::items($item['children'], $depth + 1),
                        ['id' => $id, 'class' => 'dropdown-menu', 'hidden' => 'hidden']),
                    'local-menubuilder-menu-dropdown');
            } else {
                $content = \html_writer::link($target, $label, $attributes);
            }
            $html .= \html_writer::tag('li', $content, ['class' => $depth === 0 ? 'nav-item' : '']);
        }
        return $html;
    }
}
