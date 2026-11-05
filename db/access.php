<?php
/**
 * Capability definitions for Menu Builder.
 *
 * @package   local_menubuilder
 * @copyright 2026 Andreas Giesen <andreas@108design.com>
 * @license   108design source-available software license; see LICENSE.md
 */

defined('MOODLE_INTERNAL') || die();

$capabilities = ['local/menubuilder:manage' => [
    'riskbitmask' => RISK_XSS | RISK_CONFIG,
    'captype' => 'write',
    'contextlevel' => CONTEXT_SYSTEM,
    'archetypes' => ['manager' => CAP_ALLOW],
]];
