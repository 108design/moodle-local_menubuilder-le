<?php
/**
 * Native navigation, renderer and filter integration checks.
 *
 * @package local_menubuilder
 * @copyright 2026 Andreas Giesen <andreas@108design.com>
 * @license 108design source-available software license; see LICENSE.md
 */

namespace local_menubuilder;

use local_menubuilder\local\managed_menu_definition as document;
use local_menubuilder\local\managed_menu_runtime as runtime;
use local_menubuilder\local\menu_placement_definition as placement;
use local_menubuilder\local\persistence\menu_repository;

/**
 * Regression tests for topnav.
 */
final class topnav_test extends \advanced_testcase {
    /**
     * Prepare the isolated native fixtures for the test.
     *
     * @return void
     */
    protected function setUp(): void {
        parent::setUp();
        $this->resetAfterTest();
        $this->setAdminUser();
        global $PAGE;
        $PAGE->set_url('/');
        set_config('moduleowner', 'local_menubuilder', 'local_menubuilder');
    }

    /**
     * Build the definition used by the test.
     *
     * @return array
     */
    private function definition(): array {
        $d = document::empty();
        foreach ([0, 1, 2] as $depth) {
            $d['items'][] = ['id' => 'native_item_' . $depth, 'depth' => $depth, 'title' => 'Level ' . $depth,
                'source' => $depth === 0 ? [ 'type' => 'container' ] : [ 'type' => 'internal', 'url' => '/course/index.php' ]];
        }
        return $d;
    }

    /**
     * Verify that only appends and preserves native nodes and custom config.
     *
     * @return void
     */
    public function test_only_appends_and_preserves_native_nodes_and_custom_config(): void {
        global $CFG;
        $CFG->custommenuitems = 'Native custom|/native';
        $primary = new \navigation_node(['text' => 'Root']);
        $native = [];
        foreach (['home', 'myhome', 'mycourses', 'siteadminnode'] as $key) {
            $native[$key] = $primary->add('Native ' . $key, '/my/', \navigation_node::TYPE_CUSTOM, null, $key);
        }
        runtime::apply_primary($primary, $this->definition(), placement::empty());
        $this->assertSame(
            [...array_keys($native), 'local_menubuilder_menu_native_item_0'],
            array_values(array_map(static fn($n) => $n->key, iterator_to_array($primary->children)))
        );
        foreach ($native as $key => $node) {
            $this->assertSame($node, $primary->children->get($key));
            $this->assertSame('Native ' . $key, $node->text);
        }
        $this->assertSame('Native custom|/native', $CFG->custommenuitems);
        $root = $primary->children->get('local_menubuilder_menu_native_item_0');
        $this->assertCount(1, $root->children);
        $this->assertCount(1, $root->children->get('local_menubuilder_menu_native_item_1')->children);
    }

    /**
     * Verify that invalid runtime document does not change native nodes.
     *
     * @return void
     */
    public function test_invalid_runtime_document_does_not_change_native_nodes(): void {
        $primary = new \navigation_node(['text' => 'Root']);
        $native = $primary->add('Native home', '/', \navigation_node::TYPE_CUSTOM, null, 'home');
        $d = $this->definition();
        $d['items'][0]['source'] = ['type' => 'system', 'key' => 'home'];
        try {
            runtime::apply_primary($primary, $d);
            $this->fail('Invalid source reached the native navigation');
        } catch (\invalid_parameter_exception $e) {
            $this->assertSame($native, $primary->children->get('home'));
            $this->assertCount(1, $primary->children);
        }
    }

    /**
     * Verify that filter reuses same key and resolves current definition.
     *
     * @return void
     */
    public function test_filter_reuses_same_key_and_resolves_current_definition(): void {
        global $DB;
        if (!get_config('filter_frontpage_menu', 'version')) {
            $this->markTestSkipped('Requires the shortcode filter companion.');
        }
        $repo = new menu_repository($DB);
        $record = $repo->save(null, 'Shared', '', false, document::encode($this->definition()), 0, 2);
        $filter = new \filter_frontpage_menu\text_filter(\context_system::instance(), []);
        $token = '[[frontpage-menu:' . $record->definitionkey . ']]';
        $rendered = $filter->filter($token . $token);
        $this->assertSame(2, substr_count($rendered, '<nav '));
        $this->assertStringContainsString('Level 2', $rendered);
        $d = $this->definition();
        $d['items'][2]['title'] = 'Updated shared leaf';
        $repo->save($record->id, 'Shared', '', false, document::encode($d), 1, 2);
        $this->assertStringContainsString('Updated shared leaf', $filter->filter($token));
        $this->assertSame('', $filter->filter('[[frontpage-menu:missing_menu]]'));
    }

    /**
     * Verify that block facade uses standalone and same reference.
     *
     * @return void
     */
    public function test_block_facade_uses_standalone_and_same_reference(): void {
        global $DB;
        if (!get_config('block_frontpage_menu', 'version')) {
            $this->markTestSkipped('Requires the menu block companion.');
        }
        $record = (new menu_repository($DB))->save(
            null,
            'Shared',
            '',
            false,
            document::encode($this->definition()),
            0,
            2
        );
        $this->assertSame('local_menubuilder', \block_frontpage_menu\local\authority::component());
        foreach (['central:', 'standalone:central:'] as $prefix) {
            $info = \block_frontpage_menu\local\authority::source_info($prefix . $record->definitionkey);
            $this->assertSame('local_menubuilder', $info['authority']);
            $html = \block_frontpage_menu\local\authority::render_source(
                $prefix . $record->definitionkey,
                ['style' => 'block', 'padding' => 'compact']
            );
            $this->assertStringContainsString('Level 2', $html);
        }
    }

    /**
     * Verify that empty conditionally filtered container disappears.
     *
     * @return void
     */
    public function test_empty_conditionally_filtered_container_disappears(): void {
        $this->setGuestUser();
        $d = $this->definition();
        $d['items'][1]['visibility'] = ['auth' => 'authenticated'];
        $this->assertSame([], \local_menubuilder\local\managed_menu_resolver::resolve($d));
    }

    /**
     * Verify that native block editors can select without central menu management.
     *
     * @return void
     */
    public function test_native_block_editors_can_select_without_central_menu_management(): void {
        if (!get_config('block_frontpage_menu', 'version')) {
            $this->markTestSkipped('Requires the menu block companion.');
        }
        $this->setUser($this->getDataGenerator()->create_user());
        $this->assertFalse(has_capability('local/menubuilder:manage', \context_system::instance()));
        \block_frontpage_menu\local\authority::require_authoring();
        $this->assertTrue(\local_menubuilder\local\authority::source_selection_available());
    }

    /**
     * Verify that new window attributes and escaped labels.
     *
     * @return void
     */
    public function test_new_window_attributes_and_escaped_labels(): void {
        $d = $this->definition();
        $d['items'][2]['title'] = '<script>alert(1)</script>Visible';
        $d['items'][2]['target'] = 'blank';
        $items = \local_menubuilder\local\managed_menu_resolver::resolve($d);
        $html = \local_menubuilder\local\menu_renderer::render($items);
        $this->assertStringContainsString('target="_blank"', $html);
        $this->assertStringContainsString('rel="noopener noreferrer"', $html);
        $this->assertStringNotContainsString('<script>', $html);
    }
}
