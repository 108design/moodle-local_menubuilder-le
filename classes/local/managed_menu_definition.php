<?php
/**
 * classes/local/managed_menu_definition.php
 *
 * @package local_menubuilder
 * @copyright 2026 Andreas Giesen <andreas@108design.com>
 * @license 108design source-available software license; see LICENSE.md
 */
namespace local_menubuilder\local;

/** Validate the canonical menu before persistence and every runtime resolution. */
final class managed_menu_definition {
    public const SCHEMA = '108design-menubuilder/menu/1';
    public const KIND = 'menu';
    public const MAX_ITEMS = 100;
    public const MAX_DEPTH = 2;
    public const SOURCE_TYPES = ['internal', 'external', 'container'];

    public static function empty(): array {
        return ['schema' => self::SCHEMA, 'kind' => self::KIND, 'items' => [], 'extensions' => []];
    }

    public static function decode(?string $json): array {
        if ($json === null || trim($json) === '') {
            return self::empty();
        }
        if (strlen($json) > 262144) {
            throw new \invalid_parameter_exception('The menu JSON exceeds 256 KiB.');
        }
        try {
            return self::validate(json_decode($json, true, 32, JSON_THROW_ON_ERROR));
        } catch (\JsonException $e) {
            throw new \invalid_parameter_exception('Invalid menu JSON: ' . $e->getMessage());
        }
    }

    public static function encode(array $definition, bool $pretty = false): string {
        return json_encode(self::validate($definition), JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE
            | ($pretty ? JSON_PRETTY_PRINT : 0) | JSON_THROW_ON_ERROR);
    }

    /** Reject unknown fields rather than retaining executable data outside this schema. */
    public static function fields(mixed $value, array $allowed, string $where): array {
        if (!is_array($value)) {
            throw new \invalid_parameter_exception($where . ' must be an object.');
        }
        foreach ($value as $key => $unused) {
            if (!in_array($key, $allowed, true)) {
                throw new \invalid_parameter_exception($where . ': unsupported field ' . $key . '.');
            }
        }
        return $value;
    }

    public static function validate(mixed $definition): array {
        self::fields($definition, ['schema', 'kind', 'items', 'extensions'], 'Menu');
        if (($definition['schema'] ?? null) !== self::SCHEMA
                || ($definition['kind'] ?? self::KIND) !== self::KIND
                || !is_array($definition['items'] ?? null) || !array_is_list($definition['items'])) {
            throw new \invalid_parameter_exception('Expected a canonical menu document with an items list.');
        }
        self::no_extensions($definition['extensions'] ?? []);
        if (count($definition['items']) > self::MAX_ITEMS) {
            throw new \invalid_parameter_exception('A menu supports at most 100 entries.');
        }
        $result = self::empty();
        $ids = [];
        $previousdepth = 0;
        foreach ($definition['items'] as $index => $raw) {
            $where = 'Entry ' . ($index + 1);
            self::fields($raw, ['id', 'depth', 'title', 'showLabel', 'source', 'icon', 'target',
                'visibility', 'presentation', 'extensions'], $where);
            $id = $raw['id'] ?? '';
            if (!is_string($id) || !preg_match('/^[a-zA-Z0-9][a-zA-Z0-9_-]{7,63}$/D', $id)
                    || isset($ids[$id])) {
                throw new \invalid_parameter_exception($where . ': use a unique ID of 8 to 64 letters, numbers, _ or -.');
            }
            $ids[$id] = true;
            $depth = $raw['depth'] ?? null;
            if (!is_int($depth) || $depth < 0 || $depth > self::MAX_DEPTH
                    || ($index === 0 && $depth !== 0) || $depth > $previousdepth + 1) {
                throw new \invalid_parameter_exception($where . ': depth must be 0, 1 or 2; do not skip a level.');
            }
            $previousdepth = $depth;
            $title = $raw['title'] ?? '';
            if (!is_string($title) || trim($title) === '' || \core_text::strlen($title) > 255) {
                throw new \invalid_parameter_exception($where . ': title is required (maximum 255 characters).');
            }
            $target = $raw['target'] ?? 'self';
            if (!in_array($target, ['self', 'blank'], true)) {
                throw new \invalid_parameter_exception($where . ': target must be self or blank.');
            }
            if (isset($raw['showLabel']) && !is_bool($raw['showLabel'])) {
                throw new \invalid_parameter_exception($where . ': showLabel must be true or false.');
            }
            $presentation = self::fields($raw['presentation'] ?? [], ['submenu'], $where . ' presentation');
            if (($presentation['submenu'] ?? 'standard') !== 'standard') {
                throw new \invalid_parameter_exception($where . ': submenu must be standard.');
            }
            self::no_extensions($raw['extensions'] ?? []);
            $result['items'][] = ['id' => $id, 'depth' => $depth, 'title' => trim($title),
                'showLabel' => $raw['showLabel'] ?? true, 'source' => self::source($raw['source'] ?? null),
                'icon' => menu_icon::normalise($raw['icon'] ?? null), 'target' => $target,
                'visibility' => self::visibility($raw['visibility'] ?? []),
                'presentation' => ['submenu' => 'standard'], 'extensions' => []];
        }
        return $result;
    }

    private static function no_extensions(mixed $extensions): void {
        if ($extensions !== []) {
            throw new \invalid_parameter_exception('Menu extensions must be empty.');
        }
    }

    private static function source(mixed $raw): array {
        self::fields($raw, ['type', 'url', 'key', 'extensions'], 'Source');
        $type = $raw['type'] ?? '';
        $url = $raw['url'] ?? '';
        if (!in_array($type, self::SOURCE_TYPES, true) || !is_string($url)
                || ($raw['key'] ?? '') !== '') {
            throw new \invalid_parameter_exception('Source type must be internal, external or container; key must be empty.');
        }
        self::no_extensions($raw['extensions'] ?? []);
        $url = trim($url);
        if (strlen($url) > 2048 || preg_match('/[\x00-\x20\x7f\\\\]/', $url) || preg_match('/%0[ad]/i', $url)) {
            throw new \invalid_parameter_exception('The link contains invalid characters or is too long.');
        }
        $valid = $type === 'container' && $url === '';
        if ($type === 'internal') {
            $valid = (str_starts_with($url, '/') && !str_starts_with($url, '//'))
                || (bool)preg_match('/^#[A-Za-z][A-Za-z0-9_.:-]*$/D', $url);
        } else if ($type === 'external') {
            $scheme = strtolower((string)parse_url($url, PHP_URL_SCHEME));
            $valid = in_array($scheme, ['http', 'https'], true) && (bool)filter_var($url, FILTER_VALIDATE_URL);
            if ($scheme === 'mailto') {
                $address = explode('?', substr($url, 7), 2)[0];
                $valid = (bool)filter_var($address, FILTER_VALIDATE_EMAIL);
            }
        }
        if (!$valid) {
            throw new \invalid_parameter_exception('Use a local /path or #anchor, an HTTP(S) or mailto link, or an empty container.');
        }
        return ['type' => $type, 'key' => '', 'url' => $url, 'extensions' => []];
    }

    public static function default_visibility(): array {
        return ['auth' => 'any', 'admin' => false, 'roles' => [], 'cohorts' => [], 'extensions' => []];
    }

    private static function visibility(mixed $raw): array {
        self::fields($raw, ['auth', 'admin', 'roles', 'cohorts', 'extensions'], 'Visibility');
        $result = self::default_visibility();
        $result['auth'] = $raw['auth'] ?? 'any';
        $result['admin'] = $raw['admin'] ?? false;
        if (!in_array($result['auth'], ['any', 'guest', 'authenticated'], true) || !is_bool($result['admin'])) {
            throw new \invalid_parameter_exception('Visibility requires auth any/guest/authenticated and an admin boolean.');
        }
        foreach (['roles', 'cohorts'] as $key) {
            $values = $raw[$key] ?? [];
            if (!is_array($values) || !array_is_list($values) || count($values) > 100) {
                throw new \invalid_parameter_exception('Visibility ' . $key . ' must be a list of at most 100 references.');
            }
            foreach ($values as $value) {
                if (!is_string($value) || trim($value) === '' || \core_text::strlen($value) > 255) {
                    throw new \invalid_parameter_exception('Invalid visibility reference in ' . $key . '.');
                }
                $result[$key][] = trim($value);
            }
            $result[$key] = array_values(array_unique($result[$key]));
        }
        self::no_extensions($raw['extensions'] ?? []);
        return $result;
    }

    public static function tree(array $definition): array {
        $definition = self::validate($definition);
        $roots = [];
        $path = [];
        foreach ($definition['items'] as $item) {
            $item['children'] = [];
            $depth = $item['depth'];
            if ($depth === 0) {
                $roots[] = $item;
                $path = [&$roots[array_key_last($roots)]];
            } else {
                $parent = &$path[$depth - 1];
                $parent['children'][] = $item;
                $path[$depth] = &$parent['children'][array_key_last($parent['children'])];
                $path = array_slice($path, 0, $depth + 1);
            }
        }
        return $roots;
    }
}
