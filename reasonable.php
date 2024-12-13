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
  if ($formName == 'CRM_Contribute_Form_Contribution_Confirm') {
    $contactID = $form->getContactID();
    if ($contactID) {
      $employer_id = CRM_Core_DAO::getFieldValue('CRM_Contact_DAO_Contact', $contactID, 'employer_id');
      $form->set('reasonable_original_employer_id', $employer_id);
    }
  }  
}

/**
 * Implements hook_civicrm_postProcess().
 *
 */
function reasonable_civicrm_postProcess($formName, $form) {
  if ($formName == 'CRM_Contribute_Form_Contribution_Confirm') {
    $contactID = $form->getContactID();
    $employer_id = $form->get('reasonable_original_employer_id');
    if ($contactID && $employer_id) {
      $currentEmpParams = [];
      $currentEmpParams[$contactID] = $employer_id;
      CRM_Contact_BAO_Contact_Utils::setCurrentEmployer($currentEmpParams);
    }
  }  
}