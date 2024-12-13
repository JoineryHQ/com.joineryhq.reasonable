<?php

require_once 'reasonable.civix.php';

use CRM_Reasonable_ExtensionUtil as E;

/**
 * Implements hook_civicrm_config().
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_config/
 */
function reasonable_civicrm_config(&$config): void {
  _reasonable_civix_civicrm_config($config);
}

/**
 * Implements hook_civicrm_install().
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_install
 */
function reasonable_civicrm_install(): void {
  _reasonable_civix_civicrm_install();
}

/**
 * Implements hook_civicrm_enable().
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_enable
 */
function reasonable_civicrm_enable(): void {
  _reasonable_civix_civicrm_enable();
}

/**
 * Implements hook_civicrm_preProcess().
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_preProcess
 */
function reasonable_civicrm_preProcess(string $formName, \CRM_Core_Form $form): void {
  $onBehalfEmployer = new CRM_Reasonable_Alteration_OnBehalfEmployer();
  $onBehalfEmployer->hook_preProcess($formName, $form);
}

/**
 * Implements hook_civicrm_postProcess().
 *
 */
function reasonable_civicrm_postProcess(string $formName, \CRM_Core_Form $form): void {
  // TODO: I'd rather have each alteration define its own hooks as event listeners,
  // so we coud simply initialize those listeners in hook_civicrm_config(). However,
  // when I tried this today, I found that if we use that approach, civicrm won't
  // fire the postProcess hook for the form 'CRM_Contribute_Form_Contribution_Confirm',
  // which is exactly the thing we need here. So we have to use old-school hooks.
  $onBehalfEmployer = new CRM_Reasonable_Alteration_OnBehalfEmployer();
  $onBehalfEmployer->hook_postProcess($formName, $form);
}
