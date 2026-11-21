<?php
// This file is part of Moodle - https://moodle.org/
/**
 * tests/managed_menu_definition_test.php
 *
 * @package local_menubuilder
 * @copyright 2026 Andreas Giesen <andreas@108design.com>
 * @license 108design source-available software license; see LICENSE.md
 */

namespace local_menubuilder;

use local_menubuilder\local\managed_menu_definition as document;
use local_menubuilder\local\menu_placement_definition as placement;

/**
 * Canonical input boundary, independent of the database.
 */
final class managed_menu_definition_test extends \PHPUnit\Framework\TestCase {
    /**
     * Build the definition used by the test.
     *
     * @return array
     */
    private function definition(): array {
        $d = document::empty();
        $d['items'][] = ['id' => 'root_item_01', 'depth' => 0, 'title' => 'Courses',
            'source' => ['type' => 'internal', 'url' => '/course/index.php']];
        return $d;
    }

    /**
     * Verify that roundtrip keeps IDs and defaults.
     *
     * @return void
     */
    public function test_roundtrip_keeps_ids_and_defaults(): void {
        $d = document::decode(document::encode($this->definition(), true));
        $this->assertSame('root_item_01', $d['items'][0]['id']);
        $this->assertSame('self', $d['items'][0]['target']);
        $this->assertSame(document::default_visibility(), $d['items'][0]['visibility']);
    }

    /**
     * Provide invalid definitions for the validation cases.
     *
     * @return array
     */
    public static function invalid_documents(): array {
        return [
            'system source' => ['source', ['type' => 'system', 'key' => 'myhome']],
            'page source' => ['source', ['type' => 'frontpage', 'key' => 'home']],
            'menu inclusion' => ['source', ['type' => 'menu', 'key' => 'another_menu']],
            'course category' => ['source', ['type' => 'course-category', 'key' => '1']],
            'script URL' => ['source', ['type' => 'external', 'url' => 'javascript:alert(1)']],
            'scheme relative' => ['source', ['type' => 'internal', 'url' => '//example.invalid/path']],
            'invalid email' => ['source', ['type' => 'external', 'url' => 'mailto:bad-address']],
            'image object' => ['icon', ['type' => 'image', 'key' => 'uploaded']],
            'panel presentation' => ['presentation', ['submenu' => 'panel']],
            'panel search' => ['presentation', ['panelSearch' => true]],
            'hidden nodes' => ['hiddenSystemNodes', ['myhome']],
            'unknown field' => ['arbitrary', true],
            'extensions' => ['extensions', ['shareable' => true]],
            'bad depth' => ['depth', 3],
            'non boolean' => ['showLabel', 'false'],
        ];
    }

    /**
     * Verify that rejects invalid document fields.
     *
     * @dataProvider invalid_documents
     *
     * @param string $field Field name.
     * @param mixed $value Input value to process.
     * @return void
     */
    #[\PHPUnit\Framework\Attributes\DataProvider('invalid_documents')]
    public function test_rejects_invalid_document_fields(string $field, mixed $value): void {
        $d = $this->definition();
        $d['items'][0][$field] = $value;
        $this->expectException(\invalid_parameter_exception::class);
        document::validate($d);
    }

    /**
     * Verify that depth count and duplicate limits.
     *
     * @return void
     */
    public function test_depth_count_and_duplicate_limits(): void {
        $d = $this->definition();
        for ($i = 1; $i < 100; $i++) {
            $d['items'][] = ['id' => 'item_number_' . $i, 'depth' => 0, 'title' => 'Link',
                'source' => ['type' => 'internal', 'url' => '/']];
        }
        $this->assertCount(100, document::validate($d)['items']);
        $d['items'][] = $d['items'][0];
        $this->expectException(\invalid_parameter_exception::class);
        document::validate($d);
    }

    /**
     * Verify that email anchor target and icons.
     *
     * @return void
     */
    public function test_email_anchor_target_and_icons(): void {
        $d = $this->definition();
        $d['items'][0]['source'] = ['type' => 'external', 'url' => 'mailto:team@example.invalid?subject=Help'];
        $d['items'][0]['icon'] = 'fa:solid:book';
        $d['items'][0]['target'] = 'blank';
        $this->assertSame('blank', document::validate($d)['items'][0]['target']);
        $d['items'][0]['source'] = ['type' => 'internal', 'url' => '#section-1'];
        $d['items'][0]['icon'] = 'i/course';
        $this->assertSame('i/course', document::validate($d)['items'][0]['icon']);
    }

    /**
     * Verify that placement rejects all alternative global options.
     *
     * @return void
     */
    public function test_placement_rejects_all_alternative_global_options(): void {
        $this->assertSame('extend', placement::empty()['options']['topnavMode']);
        $p = placement::empty();
        $p['options']['hiddenSystemNodes'] = [];
        $this->expectException(\invalid_parameter_exception::class);
        placement::validate($p);
    }

    /**
     * Verify that replace is rejected.
     *
     * @return void
     */
    public function test_replace_is_rejected(): void {
        $p = placement::empty();
        $p['options']['topnavMode'] = 'replace';
        $this->expectException(\invalid_parameter_exception::class);
        placement::validate($p);
    }

    /**
     * Verify that invalid JSON has a specific error.
     *
     * @return void
     */
    public function test_invalid_json_has_a_specific_error(): void {
        try {
            document::decode('{broken');
            $this->fail('Invalid JSON was accepted');
        } catch (\invalid_parameter_exception $e) {
            $this->assertStringContainsString('Invalid menu JSON', $e->debuginfo);
        }
    }
}
