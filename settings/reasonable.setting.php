<?php

use CRM_Reasonable_ExtensionUtil as E;

return array(
  'reasonable_preserve_employer_on_behalf' => array(
    'name' => 'reasonable_preserve_employer_on_behalf',
    'is_domain' => 1,
    'is_contact' => 0,
    'title' => E::ts('Do not update Current Employer when an "On Behalf Of" contribution is submitted.'),
    'description' => E::ts('For contribution pages configured to allow contribution  "On Behalf Of" an organiztion: when such an "On Behalf Of" contribution is submitted, CiviCRM will, by design, set the given organization as the individual donor\'s "Current Employer." This implies that an individual would only ever contribute "On Behalf Of" their current employer. Enabling this option will prevent CiviCRM from modifying the "Current Employer" relationship in this way.'),
    'type' => 'Boolean',
    'default' => 0,
    'settings_pages' => array(
      'reasonable' => array(
        'weight' => 99,
      ),
    ),
    'html_type' => 'checkbox',
  ),
);

