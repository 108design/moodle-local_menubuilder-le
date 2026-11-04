<?php
/**
 * classes/local/menu_icon.php
 *
 * @package local_menubuilder
 * @copyright 2026 Andreas Giesen <andreas@108design.com>
 * @license 108design source-available software license; see LICENSE.md
 */
namespace local_menubuilder\local;

/** Validated built-in Moodle and Font Awesome glyphs. */
final class menu_icon {
    private const ICONS = [
        'i/home' => ['home', 'fa-house'],
        'i/dashboard' => ['dashboard', 'fa-gauge'],
        'i/course' => ['course', 'fa-graduation-cap'],
        'i/users' => ['users', 'fa-user-group'],
        'i/user' => ['user', 'fa-user'],
        'i/portfolio' => ['portfolio', 'fa-briefcase'],
        'i/news' => ['news', 'fa-newspaper'],
        'i/files' => ['files', 'fa-file'],
        'i/folder' => ['folder', 'fa-folder'],
        'i/contentbank' => ['contentbank', 'fa-laptop-file'],
        'i/email' => ['email', 'fa-envelope'],
        'i/notifications' => ['notifications', 'fa-bell'],
        'i/link' => ['link', 'fa-link'],
        'i/location' => ['location', 'fa-location-dot'],
        'i/language' => ['language', 'fa-language'],
        'i/search' => ['search', 'fa-magnifying-glass'],
        'i/settings' => ['settings', 'fa-gear'],
        'i/stats' => ['stats', 'fa-chart-line'],
        'i/report' => ['report', 'fa-chart-column'],
        'i/payment' => ['payment', 'fa-credit-card'],
        'i/star' => ['star', 'fa-star'],
        'i/tip' => ['tip', 'fa-lightbulb'],
        'i/info' => ['info', 'fa-info'],
        'i/questions' => ['questions', 'fa-question'],
        'i/rss' => ['rss', 'fa-rss'],
        'i/calendar' => ['calendar', 'fa-calendar-days'],
    ];


    public static function normalise(mixed $value): ?string {
        if ($value === null || $value === '') {
            return null;
        }
        if (!is_string($value) || (!isset(self::ICONS[$value])
                && !preg_match('/^fa:(solid|brand):[a-z0-9][a-z0-9-]{0,79}$/D', $value))) {
            throw new \invalid_parameter_exception('Use a supported Moodle pix identifier or fa:solid:name / fa:brand:name.');
        }
        return $value;
    }

    public static function pix_icon(mixed $value): ?\pix_icon {
        return is_string($value) && isset(self::ICONS[$value]) ? new \pix_icon($value, '', 'core') : null;
    }

    public static function render_html(mixed $value): string {
        global $OUTPUT;
        $value = self::normalise($value);
        if ($value === null) {
            return '';
        }
        $pixicon=self::pix_icon($value);
        if ($pixicon) {
            return $OUTPUT->render($pixicon);
        }
        preg_match('/^fa:(solid|brand):([a-z0-9][a-z0-9-]{0,79})$/D', $value, $match);
        return \html_writer::tag('i', '', ['class' => ($match[1] === 'brand' ? 'fab' : 'fa')
            . ' fa-' . $match[2] . ' icon', 'aria-hidden' => 'true']);
    }
}
