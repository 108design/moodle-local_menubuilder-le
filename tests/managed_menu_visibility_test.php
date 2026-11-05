<?php
/**
 * Regression tests for managed menu visibility.
 *
 * @package   local_menubuilder
 * @copyright 2026 Andreas Giesen <andreas@108design.com>
 * @license   108design source-available software license; see LICENSE.md
 */

namespace local_menubuilder;

use local_menubuilder\local\managed_menu_definition;
use local_menubuilder\local\managed_menu_runtime;
use local_menubuilder\local\managed_menu_resolver;

/**
 * Real user/role/cohort fixtures for the shared menu visibility boundary.
 */
final class managed_menu_visibility_test extends \advanced_testcase {
    /**
     * Prepare the isolated native fixtures for the test.
     *
     * @return void
     */
    protected function setUp(): void {
        parent::setUp();
        $this->resetAfterTest();
        global $CFG, $PAGE;
        require_once($CFG->dirroot . '/cohort/lib.php');
        $PAGE->set_url('/');
        set_config('moduleowner', 'local_menubuilder', 'local_menubuilder');
    }

    /**
     * Verify that site admin needs no role or cohort membership.
     *
     * @return void
     */
    public function test_site_admin_needs_no_role_or_cohort_membership(): void {
        $this->setAdminUser();
        $cohort = $this->getDataGenerator()->create_cohort(['idnumber' => 'menu-restricted']);
        $conditions = managed_menu_definition::default_visibility();
        $conditions['auth'] = 'authenticated';
        $conditions['roles'] = ['manager'];
        $conditions['cohorts'] = [$cohort->idnumber];
        foreach ([false, true] as $adminonly) {
            $conditions['admin'] = $adminonly;
            $this->assertTrue(managed_menu_runtime::conditions_match($conditions));
        }
    }

    /**
     * Verify that site admin still obeys guest only login condition.
     *
     * @return void
     */
    public function test_site_admin_still_obeys_guest_only_login_condition(): void {
        $this->setAdminUser();
        $conditions = managed_menu_definition::default_visibility();
        $conditions['auth'] = 'guest';
        $this->assertFalse(managed_menu_runtime::conditions_match($conditions));
    }

    /**
     * Verify that non admin role and cohort filters remain conjunctive.
     *
     * @return void
     */
    public function test_non_admin_role_and_cohort_filters_remain_conjunctive(): void {
        global $DB;
        $user = $this->getDataGenerator()->create_user();
        $cohort = $this->getDataGenerator()->create_cohort(['idnumber' => 'menu-restricted']);
        $this->setUser($user);
        $conditions = managed_menu_definition::default_visibility();
        $conditions['roles'] = ['manager'];
        $conditions['cohorts'] = [$cohort->idnumber];
        $this->assertFalse(managed_menu_runtime::conditions_match($conditions));
        $roleid = $DB->get_field('role', 'id', ['shortname' => 'manager'], MUST_EXIST);
        role_assign($roleid, $user->id, \context_system::instance()->id);
        $this->assertFalse(managed_menu_runtime::conditions_match($conditions));
        cohort_add_member($cohort->id, $user->id);
        $this->assertTrue(managed_menu_runtime::conditions_match($conditions));
        role_unassign($roleid, $user->id, \context_system::instance()->id);
        $this->assertFalse(managed_menu_runtime::conditions_match($conditions));
    }

    /**
     * Verify that course role does not count as system role.
     *
     * @return void
     */
    public function test_course_role_does_not_count_as_system_role(): void {
        global $DB;
        $course = $this->getDataGenerator()->create_course();
        $user = $this->getDataGenerator()->create_user();
        $roleid = $DB->get_field('role', 'id', ['shortname' => 'manager'], MUST_EXIST);
        role_assign($roleid, $user->id, \context_course::instance($course->id)->id);
        $this->setUser($user);
        $conditions = managed_menu_definition::default_visibility();
        $conditions['roles'] = ['manager'];
        $this->assertFalse(managed_menu_runtime::conditions_match($conditions));
    }

    /**
     * Verify that admin only does not admit non admin manager.
     *
     * @return void
     */
    public function test_admin_only_does_not_admit_non_admin_manager(): void {
        global $DB;
        $user = $this->getDataGenerator()->create_user();
        $roleid = $DB->get_field('role', 'id', ['shortname' => 'manager'], MUST_EXIST);
        role_assign($roleid, $user->id, \context_system::instance()->id);
        $this->setUser($user);
        $conditions = managed_menu_definition::default_visibility();
        $conditions['admin'] = true;
        $conditions['roles'] = ['manager'];
        $this->assertFalse(managed_menu_runtime::conditions_match($conditions));
    }

    /**
     * Verify that guest cannot pass authenticated condition.
     *
     * @return void
     */
    public function test_guest_cannot_pass_authenticated_condition(): void {
        $this->setGuestUser();
        $conditions = managed_menu_definition::default_visibility();
        $conditions['auth'] = 'authenticated';
        $this->assertFalse(managed_menu_runtime::conditions_match($conditions));
    }

    /**
     * Verify that admin passes restricted parent and child in content menu.
     *
     * @return void
     */
    public function test_admin_passes_restricted_parent_and_child_in_content_menu(): void {
        $this->setAdminUser();
        $definition = $this->restricted_tree();
        $content = managed_menu_resolver::resolve($definition);
        $this->assertCount(1, $content);
        $this->assertCount(1, $content[0]['children']);
    }

    /**
     * Verify that non admin cannot bypass restricted parent.
     *
     * @return void
     */
    public function test_non_admin_cannot_bypass_restricted_parent(): void {
        $this->setUser($this->getDataGenerator()->create_user());
        $definition = $this->restricted_tree();
        $definition['items'][1]['visibility'] = managed_menu_definition::default_visibility();
        $this->assertSame([], managed_menu_resolver::resolve($definition));
    }

    /**
     * Build a menu tree with restricted parent and child entries.
     *
     * @return array
     */
    private function restricted_tree(): array {
        $definition = managed_menu_definition::empty();
        foreach ([0, 1] as $depth) {
            $conditions = managed_menu_definition::default_visibility();
            $conditions['auth'] = 'authenticated';
            $conditions['roles'] = ['manager'];
            $conditions['cohorts'] = ['menu-unassigned'];
            $definition['items'][] = [
                'id' => 'restricted_item_' . $depth, 'depth' => $depth, 'title' => 'Restricted ' . $depth,
                'showLabel' => true, 'source' => ['type' => 'internal', 'key' => '', 'url' => '/my/'],
                'icon' => null, 'target' => 'self', 'visibility' => $conditions, 'extensions' => [],
            ];
        }
        return $definition;
    }
}
