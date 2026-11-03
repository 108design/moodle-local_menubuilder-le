<?php
/**
 * classes/form/managed_menu_form.php
 *
 * @package local_menubuilder
 * @copyright 2026 Andreas Giesen <andreas@108design.com>
 * @license 108design source-available software license; see LICENSE.md
 */
namespace local_menubuilder\form;
defined('MOODLE_INTERNAL') || die();
require_once($CFG->libdir . '/formslib.php');

/** Visible JSON editing with native form validation and optimistic versions. */
final class managed_menu_form extends \moodleform {
    protected function definition(): void {
        $mform = $this->_form;
        $mform->addElement('text', 'name', get_string('managedmenuname', 'local_menubuilder'), ['maxlength' => 255]);
        $mform->setType('name', PARAM_TEXT);
        $mform->addRule('name', get_string('required'), 'required');
        $mform->addHelpButton('name', 'managedmenuname', 'local_menubuilder');
        $mform->addElement('textarea', 'description', get_string('description'), ['rows' => 3, 'cols' => 70]);
        $mform->setType('description', PARAM_TEXT);
        $mform->addHelpButton('description', 'menudescription', 'local_menubuilder');
        $mform->addElement('advcheckbox', 'topnav', get_string('managedmenutopnav', 'local_menubuilder')
            . ' ' . \local_menubuilder\output\context_help::icon('menutopnav'));
        $mform->addElement('textarea', 'definitionjson', get_string('menujson', 'local_menubuilder')
            . ' ' . \local_menubuilder\output\context_help::icon('menujson'),
            ['rows' => 24, 'cols' => 90, 'spellcheck' => 'false', 'class' => 'local-menubuilder-json']);
        $mform->setType('definitionjson', PARAM_RAW);
        $mform->addRule('definitionjson', get_string('required'), 'required');
        foreach (['id', 'version'] as $field) {
            $mform->addElement('hidden', $field, 0);
            $mform->setType($field, PARAM_INT);
        }
        $this->add_action_buttons(true, get_string('savechanges'));
    }

    public function validation($data, $files): array {
        $errors = parent::validation($data, $files);
        try {
            \local_menubuilder\local\managed_menu_definition::decode((string)($data['definitionjson'] ?? ''));
        } catch (\invalid_parameter_exception $e) {
            // Only this parser's bounded input explanation is exposed, never database diagnostics.
            $errors['definitionjson'] = get_string('menujsoninvalid', 'local_menubuilder', s((string)$e->debuginfo));
        } catch (\moodle_exception $e) {
            $errors['definitionjson'] = $e->getMessage();
        }
        return $errors;
    }
}
