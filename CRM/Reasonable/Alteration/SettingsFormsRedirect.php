<?php

/**
 * Description of SettingsFormsRedirect
 *
 * @author as
 */
class CRM_Reasonable_Alteration_SettingsFormsRedirect extends CRM_Reasonable_Alteration {

  /**
   * @var string
   * The title of this alteration, to appear in extension settings.
   * (Translation is handled elsewhere, so don't use ts() here.
   */
  public $title = 'On submission of admin settings forms, reload the form instead of redirecting to /civicrm/admin';

  /**
   * @var string
   * The description of this alteration, to appear in extension settings.
   * (Translation is handled elsewhere, so don't use ts() here.
   */
  protected $description = 'Many settings forms tend to redirect to the main /civicrm/admin page, which can seem confusing to users.';

  /**
   * @var int
   * Larger weights sort toward the bottom of the list.
   * Used for:
   *   - Ordering of hook execution when multiple alterations implement a the same hook.
   *   - Ordering of alterations on Settings page.
   */
  protected $weight = 20;

  /**
   * Event listener for hook_civicrm_postProcess().
   * This extension expects hook implementations to be named beginning with 'hook_'.
   *
   * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_postProcess
   */
  public function hook_buildForm($formName, &$form) {
    $session = CRM_Core_Session::singleton();
    $urlPath = implode('/', $form->urlPath);
    $session->pushUserContext(CRM_Utils_System::url($urlPath, "reset=1"));
  }

}
