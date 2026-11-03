<?php
/**
 * tests/menu_repository_test.php
 *
 * @package local_menubuilder
 * @copyright 2026 Andreas Giesen <andreas@108design.com>
 * @license 108design source-available software license; see LICENSE.md
 */
namespace local_menubuilder;

use local_menubuilder\local\managed_menu_definition as document;
use local_menubuilder\local\module_authority;
use local_menubuilder\local\persistence\menu_repository;

/** Native state, permission, identity and optimistic write checks. */
final class menu_repository_test extends \advanced_testcase {
    protected function setUp(): void {
        parent::setUp();
        $this->resetAfterTest();
        $this->setAdminUser();
        set_config('moduleowner', 'local_menubuilder', 'local_menubuilder');
        unset_config('frontpagemigrationcompleted', 'local_menubuilder');
    }

    public function test_switch_duplicate_edit_and_identity(): void {
        global $DB;
        $repo = new menu_repository($DB);
        $json = document::encode(document::empty());
        $first = $repo->save(null, 'First', '', true, $json, 0, 2);
        $second = $repo->save(null, 'Second', '', true, $json, 0, 2);
        $this->assertSame(0, $repo->get($first->id)->topnav);
        $this->assertSame(1, $repo->get($second->id)->topnav);
        $copy = $repo->duplicate($second->id, 'Copy', 2);
        $this->assertNotSame($second->definitionkey, $copy->definitionkey);
        $this->assertSame(0, $copy->topnav);
        $saved = $repo->save($first->id, 'Renamed', '', false, $json, 1, 2);
        $this->assertSame($first->definitionkey, $saved->definitionkey);
        $this->assertSame(2, (int)$saved->version);
        $this->assertSame($first->id, $saved->id);
    }

    public function test_conflict_rolls_back_all_placements(): void {
        global $DB;
        $repo = new menu_repository($DB);
        $json = document::encode(document::empty());
        $a = $repo->save(null, 'A', '', true, $json, 0, 2);
        $b = $repo->save(null, 'B', '', false, $json, 0, 2);
        try {
            $repo->save($b->id, 'Stale', '', true, $json, 0, 2);
            $this->fail('A stale version was accepted');
        } catch (\moodle_exception $e) {
            $this->assertSame('managedmenuversionconflict', $e->errorcode);
        }
        $this->assertSame(1, $repo->get($a->id)->topnav);
        $this->assertSame('B', $repo->get($b->id)->name);
    }

    public function test_no_menu_count_limit_and_batched_listing(): void {
        global $DB;
        $repo = new menu_repository($DB);
        for ($i = 0; $i < 12; $i++) {
            $repo->save(null, 'Menu ' . $i, '', false, document::encode(document::empty()), 0, 2);
        }
        $before = $DB->perf_get_queries();
        $this->assertCount(12, $repo->get_all());
        $this->assertSame(2, $DB->perf_get_queries() - $before);
    }

    public function test_runtime_and_writes_do_not_require_activation(): void {
        global $DB;
        unset_config('activationid', 'local_menubuilder');
        $this->assertTrue(\local_menubuilder\local\gate::allows_authoring());
        $repo = new menu_repository($DB);
        $record = $repo->save(null, 'Allowed', '', false, document::encode(document::empty()), 0, 2);
        $this->assertSame('Allowed', $repo->get_by_key($record->definitionkey)->name);
    }

    public function test_unprivileged_writes_are_denied(): void {
        global $DB;
        $this->setUser($this->getDataGenerator()->create_user());
        $this->expectException(\required_capability_exception::class);
        (new menu_repository($DB))->save(null, 'Denied', '', false, document::encode(document::empty()), 0, 2);
    }

    public function test_existing_other_ownership_is_not_taken_over(): void {
        set_config('moduleowner', 'local_frontpage', 'local_menubuilder');
        module_authority::initialise();
        $this->assertSame('local_frontpage', module_authority::owner());
        $this->assertFalse(\local_menubuilder\local\gate::allows_authoring());
    }

    public function test_active_placement_blocks_deletion(): void {
        global $DB;
        $repo = new menu_repository($DB);
        $record = $repo->save(null, 'Used', '', true, document::encode(document::empty()), 0, 2);
        $this->expectException(\moodle_exception::class);
        $this->expectExceptionMessage(get_string('managedmenudeleteused', 'local_menubuilder'));
        $repo->delete($record->id);
    }

    public function test_configured_block_blocks_deletion_then_missing_menu_renders_empty(): void {
        global $DB, $PAGE;
        if (!get_config('block_frontpage_menu', 'version')) {
            $this->markTestSkipped('Requires the menu block companion.');
        }
        $PAGE->set_url('/');
        $repo = new menu_repository($DB);
        $record = $repo->save(null, 'Used', '', false, document::encode(document::empty()), 0, 2);
        $block = $this->getDataGenerator()->create_block('frontpage_menu', ['parentcontextid' => \context_system::instance()->id]);
        $DB->set_field('block_instances', 'configdata', base64_encode(serialize((object)[
            'menusource' => 'central:' . $record->definitionkey])), ['id' => $block->id]);
        $this->assertCount(1, $repo->usage_details($record->id));
        try {
            $repo->delete($record->id);
            $this->fail('Referenced menu deleted');
        } catch (\moodle_exception $e) {
            $this->assertSame('managedmenudeleteused', $e->errorcode);
        }
        $DB->delete_records('block_instances', ['id' => $block->id]);
        $repo->delete($record->id);
        $this->assertSame('', \local_menubuilder\menu::render($record->definitionkey));
    }
}
