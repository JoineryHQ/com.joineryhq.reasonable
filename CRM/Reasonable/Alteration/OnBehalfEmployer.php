<?php

/**
 * Description of OnBehalfEmployer
 *
 * @author as
 */
class CRM_Reasonable_Alteration_OnBehalfEmployer {
  
  var $isEnabled = FALSE;
  
  function __construct() {
    $this->isEnabled = Civi::settings()->get('reasonable_preserve_employer_on_behalf');
  }
  
  /**
   * Event listener for hook_civicrm_preProcess().
   *
   * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_preProcess
   */
  function hook_preProcess(string $formName, \CRM_Core_Form $form): void {
    if (!$this->isEnabled) {
      return;
    }
    if ($formName == 'CRM_Contribute_Form_Contribution_Confirm') {
      $contactID = $form->getContactID();
      if ($contactID) {
        $employer_id = CRM_Core_DAO::getFieldValue('CRM_Contact_DAO_Contact', $contactID, 'employer_id');
        $form->set('reasonable_original_employer_id', $employer_id);
      }
    }  
  }

  /**
   * Event listener for hook_civicrm_postProcess().
   *
   */
  function hook_postProcess(string $formName, \CRM_Core_Form $form): void {
    if (!$this->isEnabled) {
      return;
    }
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
}
