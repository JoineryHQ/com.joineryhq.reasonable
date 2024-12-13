<?php

/**
 * Description of OnBehalfEmployer
 *
 * @author as
 */
class CRM_Reasonable_Alteration_OnBehalfEmployer extends CRM_Reasonable_Alteration {

  /**
   * @var string
   * The title of this alteration, to appear in extension settings.
   * (Translation is handled elsewhere, so don't use ts() here.
   */
  public $title = 'Do not update Current Employer when an "On Behalf Of" contribution is submitted.';

  /**
   * @var string
   * The description of this alteration, to appear in extension settings.
   * (Translation is handled elsewhere, so don't use ts() here.
   */
  protected $description = 'For contribution pages configured to allow contribution  "On Behalf Of" an organiztion: when such an "On Behalf Of" contribution is submitted, CiviCRM will, by design, set the given organization as the individual donor\'s "Current Employer." This implies that an individual would only ever contribute "On Behalf Of" their current employer. Enabling this option will prevent CiviCRM from modifying the "Current Employer" relationship in this way.';

  /**
   * Event listener for hook_civicrm_preProcess().
   * This extension expects hook implementations to be named beginning with 'hook_'.
   *
   * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_preProcess
   */
  public function hook_preProcess(string $formName, \CRM_Core_Form $form): void {
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
   * This extension expects hook implementations to be named beginning with 'hook_'.
   *
   * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_postProcess
   */
  public function hook_postProcess(string $formName, \CRM_Core_Form $form): void {
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
