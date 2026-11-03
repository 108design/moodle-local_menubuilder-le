<?php
/**
 * This file is part of a 108design source-available software product.
 *
 * Use and modification are permitted only under the Software License included
 * with this distribution. See LICENSE.md for the full terms.
 *
 * File documentation for classes/privacy/provider.php.
 *
 * @package   local_menubuilder
 * @copyright 2026 Andreas Giesen <andreas@108design.com>
 * @license   108design source-available software license; see LICENSE.md
 */
namespace local_menubuilder\privacy;

use context;
use context_system;
use core_privacy\local\metadata\collection;
use core_privacy\local\request\approved_contextlist;
use core_privacy\local\request\approved_userlist;
use core_privacy\local\request\contextlist;
use core_privacy\local\request\transform;
use core_privacy\local\request\userlist;
use core_privacy\local\request\writer;

/** Privacy provider for shared Menu Builder definitions and their administrator attribution. */
final class provider implements
        \core_privacy\local\metadata\provider,
        \core_privacy\local\request\core_userlist_provider,
        \core_privacy\local\request\plugin\provider {
    private const COMPONENT = 'local_menubuilder';

    /** @var array<string, array<int, string>> */
    private const USER_FIELDS = [
        'local_menubuilder_menu' => ['createdby', 'modifiedby'],
        'local_menubuilder_place' => ['modifiedby'],
        'local_menubuilder_menuasset' => ['createdby', 'modifiedby'],
    ];

    public static function get_metadata(collection $collection): collection {
        $collection->add_database_table('local_menubuilder_menu', [
            'createdby' => 'privacy:metadata', 'modifiedby' => 'privacy:metadata',
        ], 'privacy:metadata');
        $collection->add_database_table('local_menubuilder_place', [
            'modifiedby' => 'privacy:metadata',
        ], 'privacy:metadata');
        $collection->add_database_table('local_menubuilder_menuasset', [
            'createdby' => 'privacy:metadata', 'modifiedby' => 'privacy:metadata',
        ], 'privacy:metadata');
        $collection->add_subsystem_link('core_files', [], 'privacy:metadata');
        return $collection;
    }

    public static function get_contexts_for_userid(int $userid): contextlist {
        global $DB;
        $contexts = new contextlist();
        foreach (self::USER_FIELDS as $table => $fields) {
            [$select, $params] = self::user_select($fields, $userid, $table);
            if ($DB->record_exists_select($table, $select, $params)) {
                return $contexts->add_system_context();
            }
        }
        if ($DB->record_exists('files', [
                'contextid' => context_system::instance()->id,
                'component' => self::COMPONENT,
                'userid' => $userid,
        ])) {
            $contexts->add_system_context();
        }
        return $contexts;
    }

    public static function export_user_data(approved_contextlist $contextlist): void {
        global $DB;
        $context = context_system::instance();
        if (!in_array($context->id, $contextlist->get_contextids(), true)) {
            return;
        }
        $userid = (int) $contextlist->get_user()->id;
        foreach (self::USER_FIELDS as $table => $fields) {
            [$select, $params] = self::user_select($fields, $userid, $table);
            foreach ($DB->get_records_select($table, $select, $params, 'id ASC') as $record) {
                $data = self::export_record($record, $fields, $userid);
                $path = [get_string('pluginname', self::COMPONENT), $table, (string) $record->id];
                writer::with_context($context)->export_data($path, (object) $data);
            }
        }
        self::export_user_files($context, $userid);
    }

    public static function delete_data_for_all_users_in_context(context $context): void {
        if ($context->id !== context_system::instance()->id) {
            return;
        }
        global $DB;
        foreach (self::USER_FIELDS as $table => $fields) {
            foreach ($fields as $field) {
                $DB->set_field($table, $field, 0);
            }
        }
        self::anonymise_files(null, $context->id);
    }

    public static function delete_data_for_user(approved_contextlist $contextlist): void {
        $context = context_system::instance();
        if (!in_array($context->id, $contextlist->get_contextids(), true)) {
            return;
        }
        self::anonymise_users([(int) $contextlist->get_user()->id], $context->id);
    }

    public static function get_users_in_context(userlist $userlist): void {
        $context = $userlist->get_context();
        if ($context->id !== context_system::instance()->id) {
            return;
        }
        foreach (self::USER_FIELDS as $table => $fields) {
            foreach ($fields as $field) {
                $userlist->add_from_sql('userid',
                    "SELECT DISTINCT {$field} AS userid FROM {{$table}} WHERE {$field} <> 0", []);
            }
        }
        $userlist->add_from_sql('userid',
            'SELECT DISTINCT userid FROM {files}
              WHERE contextid = :contextid AND component = :component AND userid IS NOT NULL', [
                'contextid' => $context->id, 'component' => self::COMPONENT,
            ]);
    }

    public static function delete_data_for_users(approved_userlist $userlist): void {
        $context = $userlist->get_context();
        if ($context->id !== context_system::instance()->id || $userlist->get_userids() === []) {
            return;
        }
        self::anonymise_users($userlist->get_userids(), $context->id);
    }

    /** @return array{0:string,1:array<string,int>} */
    private static function user_select(array $fields, int $userid, string $prefix): array {
        $conditions = [];
        $params = [];
        foreach ($fields as $field) {
            $name = preg_replace('/[^a-z0-9]+/', '_', $prefix . '_' . $field);
            $conditions[] = "{$field} = :{$name}";
            $params[$name] = $userid;
        }
        return [implode(' OR ', $conditions), $params];
    }

    /** @return array<string, mixed> */
    private static function export_record(\stdClass $record, array $userfields, int $userid): array {
        $data = (array) $record;
        $relations = [];
        foreach ($userfields as $field) {
            if ((int) ($data[$field] ?? 0) === $userid) {
                $relations[] = $field;
            }
            unset($data[$field]);
        }
        $data['user_relations'] = implode(', ', $relations);
        foreach (['timecreated', 'timemodified'] as $field) {
            if (!empty($data[$field])) {
                $data[$field] = transform::datetime($data[$field]);
            }
        }
        return $data;
    }

    private static function export_user_files(context $context, int $userid): void {
        global $DB;
        $files = $DB->get_records('files', [
            'contextid' => $context->id, 'component' => self::COMPONENT, 'userid' => $userid,
        ], 'filearea ASC, itemid ASC', 'id,filearea,itemid');
        $seen = [];
        foreach ($files as $file) {
            $key = $file->filearea . ':' . $file->itemid;
            if (isset($seen[$key])) {
                continue;
            }
            $seen[$key] = true;
            writer::with_context($context)->export_area_files([
                get_string('pluginname', self::COMPONENT), 'files', $key,
            ], self::COMPONENT, $file->filearea, (int) $file->itemid);
        }
    }

    private static function anonymise_users(array $userids, int $contextid): void {
        global $DB;
        [$usersql, $params] = $DB->get_in_or_equal(array_map('intval', $userids), SQL_PARAMS_NAMED, 'uid');
        foreach (self::USER_FIELDS as $table => $fields) {
            foreach ($fields as $field) {
                $DB->set_field_select($table, $field, 0, "{$field} {$usersql}", $params);
            }
        }
        self::anonymise_files($userids, $contextid);
    }

    private static function anonymise_files(?array $userids, int $contextid): void {
        global $DB;
        $select = 'contextid = :contextid AND component = :component';
        $params = ['contextid' => $contextid, 'component' => self::COMPONENT];
        if ($userids !== null) {
            [$usersql, $userparams] = $DB->get_in_or_equal(array_map('intval', $userids), SQL_PARAMS_NAMED, 'fuid');
            $select .= " AND userid {$usersql}";
            $params += $userparams;
        }
        $DB->set_field_select('files', 'author', null, $select, $params);
        $DB->set_field_select('files', 'userid', null, $select, $params);
    }
}
