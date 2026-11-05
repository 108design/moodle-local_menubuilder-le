<?php
/**
 * classes/local/menu_placement_definition.php
 *
 * @package local_menubuilder
 * @copyright 2026 Andreas Giesen <andreas@108design.com>
 * @license 108design source-available software license; see LICENSE.md
 */

namespace local_menubuilder\local;

/**
 * The single supplementary primary-navigation placement.
 */
final class menu_placement_definition {
    /**
     * Schema.
     */
    public const SCHEMA = '108design-menubuilder/placement/1';
    /**
     * Primary.
     */
    public const PRIMARY = 'primary';

    /**
     * Return an empty definition in the supported schema.
     *
     * @param string $placement Menu placement settings.
     * @return array
     */
    public static function empty(string $placement = self::PRIMARY): array {
        if ($placement !== self::PRIMARY) {
            throw new \invalid_parameter_exception('Only the primary placement is supported.');
        }
        return ['schema' => self::SCHEMA, 'placement' => self::PRIMARY,
            'presentation' => ['profile' => self::PRIMARY, 'topnavStyle' => 'auto'],
            'options' => ['topnavMode' => 'extend'], 'regions' => [], 'extensions' => []];
    }

    /**
     * Decode and validate the supported JSON definition.
     *
     * @param string|null $json Encoded JSON document.
     * @param string $placement Menu placement settings.
     * @return array
     */
    public static function decode(?string $json, string $placement = self::PRIMARY): array {
        if ($json === null || trim($json) === '') {
            return self::empty($placement);
        }
        try {
            return self::validate(json_decode($json, true, 16, JSON_THROW_ON_ERROR), $placement);
        } catch (\JsonException) {
            throw new \invalid_parameter_exception('Invalid placement JSON.');
        }
    }

    /**
     * Encode the validated definition in its canonical JSON form.
     *
     * @param array $definition Validated menu definition.
     * @return string
     */
    public static function encode(array $definition): string {
        return json_encode(self::validate($definition), JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
    }

    /**
     * Validate the supported definition fields and reject unknown options.
     *
     * @param mixed $definition Validated menu definition.
     * @param string|null $expectedplacement Expectedplacement.
     * @return array
     */
    public static function validate(mixed $definition, ?string $expectedplacement = null): array {
        $expected = self::empty($expectedplacement ?? self::PRIMARY);
        managed_menu_definition::fields($definition, array_keys($expected), 'Placement');
        if (
            ($definition['schema'] ?? '') !== self::SCHEMA
                || ($definition['placement'] ?? '') !== self::PRIMARY
        ) {
            throw new \invalid_parameter_exception('Only the canonical primary placement is supported.');
        }
        foreach (['presentation', 'options', 'regions', 'extensions'] as $key) {
            $value = $definition[$key] ?? $expected[$key];
            managed_menu_definition::fields($value, array_keys($expected[$key]), 'Placement ' . $key);
            foreach ($value as $field => $setting) {
                if ($setting !== $expected[$key][$field]) {
                    throw new \invalid_parameter_exception('Unsupported placement setting: ' . $field . '.');
                }
            }
        }
        return $expected;
    }
}
