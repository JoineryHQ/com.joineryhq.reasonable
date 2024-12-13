<?php

use CRM_Dupmon_ExtensionUtil as E;

return array(
  'reasonable_preserve_employer_on_behalf' => array(
    'group_name' => 'Be Reasonable Settings',
    'group' => 'reasonable',
    'name' => 'reasonable_preserve_employer_on_behalf',
    'add' => '5.0',
    'is_domain' => 1,
    'is_contact' => 0,
    'description' => E::ts('For contribution pages configured to allow contribution  "On Behalf Of" an organiztion: when such an "On Behalf Of" contribution is submitted, CiviCRM will, by design, set the given organization as the individual donor\'s "Current Employer." This implies that an individual would only ever contribute "On Behalf Of" their current employer. Enabling this option will prevent CiviCRM from modifying the "Current Employer" relationship in this way.'),
    'title' => E::ts('Do not update Current Employer when an "On Behalf Of" contribution is submitted.'),
    'type' => 'Boolean',
    'quick_form_type' => 'YesNo',
    'default' => 0,
//    'html_type' => 'text',
//    'formRules' => [
//      'required' => E::ts('%1 is a required field', [1 => 'Maximum Rule Scan Time']),
//      'positiveInteger' => E::ts('%1 must be a positive integer', [1 => 'Maximum Rule Scan Time']),
//    ],
  ),
);
