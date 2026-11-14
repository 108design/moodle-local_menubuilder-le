<?php
// This file is part of Moodle - https://moodle.org/
/**
 * classes/local/persistence/menu_repository.php
 *
 * @package local_menubuilder
 * @copyright 2026 Andreas Giesen <andreas@108design.com>
 * @license 108design source-available software license; see LICENSE.md
 */

namespace local_menubuilder\local\persistence;

use local_menubuilder\local\managed_menu_definition;
use local_menubuilder\local\menu_placement_definition;
use local_menubuilder\local\module_authority;

/**
 * Canonical definitions and their single independent primary placement.
 */
final class menu_repository {
    /**
     * Store the dependencies and context used by this helper.
     *
     * @param \moodle_database $db Moodle database connection.
     */
    public function __construct(
        /** @var \moodle_database Database connection. */ private readonly \moodle_database $db
    ) {
    }

    /**
     * Return all saved records with their relevant state.
     *
     * @return array
     */
    public function get_all(): array {
        $records = $this->db->get_records('local_menubuilder_menu', null, 'name ASC');
        $placements = [];
        foreach ($this->db->get_records('local_menubuilder_place', ['placementkey' => 'primary']) as $placement) {
            $placements[(int)$placement->menuid] = $placement;
        }
        foreach ($records as $record) {
            $this->attach($record, $placements[(int)$record->id] ?? false);
        }
        uasort($records, static fn($a, $b) => ((int)$b->topnav <=> (int)$a->topnav) ?: strcasecmp($a->name, $b->name));
        return $records;
    }

    /**
     * Load the requested menu with its current placement state.
     *
     * @param int $id Record ID.
     * @return \stdClass
     */
    public function get(int $id): \stdClass {
        return $this->attach($this->db->get_record('local_menubuilder_menu', ['id' => $id], '*', MUST_EXIST));
    }

    /**
     * Load the shared menu using its stable definition key.
     *
     * @param string $key Stable lookup key.
     * @return \stdClass
     */
    public function get_by_key(string $key): \stdClass {
        return $this->attach(
            $this->db->get_record(
                'local_menubuilder_menu',
                [ 'definitionkey' => $key ],
                '*',
                MUST_EXIST
            )
        );
    }

    /**
     * Return the menu currently assigned to primary navigation.
     *
     * @return \stdClass|false
     */
    public function get_active_topnav(): \stdClass|false {
        return $this->get_active_placement('primary');
    }

    /**
     * Return the enabled menu for the requested supported placement.
     *
     * @param string $placementkey Placementkey.
     * @return \stdClass|false
     */
    public function get_active_placement(string $placementkey): \stdClass|false {
        if ($placementkey !== 'primary' || !module_authority::is_owner()) {
            return false;
        }
        $record = $this->db->get_record_sql('SELECT m.* FROM {local_menubuilder_menu} m
            JOIN {local_menubuilder_place} p ON p.menuid = m.id
            WHERE p.placementkey = :placement AND p.enabled = 1', ['placement' => 'primary']);
        return $record ? $this->attach($record) : false;
    }

    /**
     * Validate and save a menu with its expected version and placement.
     *
     * @param int|null $id Record ID.
     * @param string $name Display name.
     * @param string $description Description text.
     * @param bool $topnav Topnav.
     * @param string $definitionjson Encoded menu definition.
     * @param int $expectedversion Version expected by the editor.
     * @param int $userid User ID.
     * @param string|null $placementjson Placementjson.
     * @param string|null $definitionkey Definitionkey.
     * @param string $kind Requested object or operation kind.
     * @param bool|null $usermenu Usermenu.
     * @param bool $transfer Transfer.
     * @return \stdClass
     */
    public function save(
        ?int $id,
        string $name,
        string $description,
        bool $topnav,
        string $definitionjson,
        int $expectedversion,
        int $userid,
        ?string $placementjson = null,
        ?string $definitionkey = null,
        string $kind = managed_menu_definition::KIND,
        ?bool $usermenu = null,
        bool $transfer = false
    ): \stdClass {
        return $this->write(function () use (
            $id,
            $name,
            $description,
            $topnav,
            $definitionjson,
            $expectedversion,
            $userid,
            $placementjson,
            $definitionkey,
            $kind,
            $usermenu,
            $transfer
) {
            if ($kind !== managed_menu_definition::KIND || $usermenu || $transfer) {
                throw new \invalid_parameter_exception('Unsupported menu operation.');
            }
            $name = trim($name);
            if ($name === '' || \core_text::strlen($name) > 255) {
                throw new \invalid_parameter_exception('A menu name of at most 255 characters is required.');
            }
            $definitionjson = managed_menu_definition::encode(managed_menu_definition::decode($definitionjson));
            $placement = menu_placement_definition::encode(menu_placement_definition::decode($placementjson));
            $record = $id === null ? null : $this->get($id);
            // Check the content version before changing another menu's active placement.
            if ($record && (int)$record->version !== $expectedversion) {
                throw new \moodle_exception('managedmenuversionconflict', 'local_menubuilder');
            }
            $key = $record ? $record->definitionkey : ($definitionkey ?: $this->new_key());
            if (!preg_match('/^[a-zA-Z0-9][a-zA-Z0-9_-]{7,99}$/D', $key)) {
                throw new \invalid_parameter_exception('Invalid menu key.');
            }
            $now = time();
            if ($topnav) {
                $this->db->set_field(
                    'local_menubuilder_place',
                    'enabled',
                    0,
                    [ 'placementkey' => 'primary', 'enabled' => 1 ]
                );
            }
            $data = (object)['name' => $name, 'description' => $description, 'definitionjson' => $definitionjson,
                'version' => $record ? (int)$record->version + 1 : 1, 'modifiedby' => $userid, 'timemodified' => $now];
            if ($record) {
                $data->id = $id;
                $this->db->update_record('local_menubuilder_menu', $data);
            } else {
                $data->definitionkey = $key;
                $data->kind = $kind;
                $data->createdby = $userid;
                $data->timecreated = $now;
                $id = (int)$this->db->insert_record('local_menubuilder_menu', $data);
            }
            $existing = $this->db->get_record(
                'local_menubuilder_place',
                [ 'menuid' => $id, 'placementkey' => 'primary' ]
            );
            $place = (object)['menuid' => $id, 'placementkey' => 'primary', 'enabled' => (int)$topnav,
                'configjson' => $placement, 'version' => $existing ? (int)$existing->version + 1 : 1,
                'modifiedby' => $userid, 'timemodified' => $now];
            if ($existing) {
                $place->id = $existing->id;
                $this->db->update_record('local_menubuilder_place', $place);
            } else {
                $this->db->insert_record('local_menubuilder_place', $place);
            }
            return $this->get($id);
        });
    }

    /**
     * Create an unassigned copy with a new stable definition key.
     *
     * @param int $id Record ID.
     * @param string $name Display name.
     * @param int $userid User ID.
     * @return \stdClass
     */
    public function duplicate(int $id, string $name, int $userid): \stdClass {
        $source = $this->get($id);
        return $this->save(null, $name, (string)$source->description, false, $source->definitionjson, 0, $userid);
    }

    /**
     * Known placements and configured blocks; arbitrary text shortcodes are not inventoried.
     *
     * @param int $id Record ID.
     * @return array
     */
    public function usage_details(int $id): array {
        $menu = $this->get($id);
        $usage = [];
        if ($menu->topnav) {
            $usage[] = ['key' => 'placement:primary', 'type' => 'primary',
                'label' => get_string('managedmenuusageprimary', 'local_menubuilder'),
                'statuses' => ['active'], 'url' => null, 'occurrences' => 1];
        }
        foreach ($this->db->get_records('block_instances', ['blockname' => 'frontpage_menu']) as $instance) {
            // Native block configdata is PHP-serialised; only stdClass may be restored.
            // phpcs:ignore moodle.PHP.ForbiddenFunctions.Found
            $config = @unserialize(base64_decode((string)$instance->configdata), ['allowed_classes' => ['stdClass']]);
            $source = is_object($config) ? (string)($config->menusource ?? '') : '';
            if (
                in_array(
                    $source,
                    [ 'central:' . $menu->definitionkey, 'standalone:central:' . $menu->definitionkey ],
                    true
                )
            ) {
                $usage[] = ['key' => 'block:' . $instance->id, 'type' => 'block',
                    'label' => get_string('managedmenuusageblockinstance', 'local_menubuilder', $instance->id),
                    'statuses' => ['configured'], 'url' => null, 'occurrences' => 1];
            }
        }
        return $usage;
    }

    /**
     * Return the known native placements and block uses of the menu.
     *
     * @param int $id Record ID.
     * @return array
     */
    public function usage(int $id): array {
        return array_column($this->usage_details($id), 'label');
    }

    /**
     * Delete the menu only when no known placement still uses it.
     *
     * @param int $id Record ID.
     * @return void
     */
    public function delete(int $id): void {
        $this->write(function () use ($id) {
            if ($this->usage($id)) {
                throw new \moodle_exception('managedmenudeleteused', 'local_menubuilder');
            }
            $this->db->delete_records('local_menubuilder_place', ['menuid' => $id]);
            $this->db->delete_records('local_menubuilder_menu', ['id' => $id]);
        });
    }

    /**
     * Attach the current placement state to the menu record.
     *
     * @param \stdClass $record Record to process.
     * @param \stdClass|false|null $placement Menu placement settings.
     * @return \stdClass
     */
    private function attach(\stdClass $record, \stdClass|false|null $placement = null): \stdClass {
        $placement ??= $this->db->get_record(
            'local_menubuilder_place',
            [ 'menuid' => $record->id, 'placementkey' => 'primary' ]
        );
        $record->topnav = $placement ? (int)$placement->enabled : 0;
        $record->placementjson = $placement ? $placement->configjson
            : menu_placement_definition::encode(menu_placement_definition::empty());
        return $record;
    }

    /**
     * Generate an unused stable key for a new menu definition.
     *
     * @return string
     */
    private function new_key(): string {
        do {
            $key = 'menu_' . bin2hex(random_bytes(16));
        } while ($this->db->record_exists('local_menubuilder_menu', ['definitionkey' => $key]));
        return $key;
    }

    /**
     * Run the operation under the module's authorised write boundary.
     *
     * @param callable $operation Operation to run inside the protected scope.
     * @return mixed
     */
    private function write(callable $operation): mixed {
        return module_authority::write('menubuilder', function () use ($operation) {
            $lock = \core\lock\lock_config::get_lock_factory('local_menubuilder')->get_lock('menu-catalogue', 10);
            if (!$lock) {
                throw new \moodle_exception('locktimeout');
            }
            try {
                $transaction = $this->db->start_delegated_transaction();
                try {
                    $result = $operation();
                    $transaction->allow_commit();
                    return $result;
                } catch (\Throwable $e) {
                    $transaction->rollback($e);
                }
            } finally {
                $lock->release();
            }
        });
    }
}
