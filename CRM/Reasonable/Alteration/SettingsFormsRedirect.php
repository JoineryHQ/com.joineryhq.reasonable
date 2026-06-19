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
  public function hook_postProcess($formName, &$form) {
    if (strpos($formName, 'CRM_Admin_Form_') === 0) {
      // This is an admin form.
      $session = CRM_Core_Session::singleton();
      $topUserContext = $session->readUserContext();
      if (self::compareUrlPath($topUserContext, 'civicrm/admin')) {
        // This would have redirected to civicrm/admin/.
        // Instead, we'll tell it to reload the current form.
        $urlPath = implode('/', $form->urlPath);
        $session->replaceUserContext(CRM_Utils_System::url($urlPath, "reset=1"));
      }
    }
  }

  /**
   * For a given URL, determine whether it's pointing to the given civicrm path.
   * E.g., "Is this URL directing to "/civicrm/admin"?
   * This is meant to
   * - account for variances in CMS url structure (e.g. Drupal vs WordPress)
   * - ignore leading and trailing slashes
   *
   * @param string $testedUrl
   * @param string $matchCiviCrmPath
   * @return string
   */
  private static function compareUrlPath(string $testedUrl, string $matchCiviCrmPath): string {

    // Convert HTML entities (&amp; -> &)
    $testedUrl = html_entity_decode($testedUrl, ENT_QUOTES);
    $parts = parse_url($testedUrl);
    $path = isset($parts['path'])
      ? urldecode($parts['path'])
      : '';

    $queryParams = [];
    if (!empty($parts['query'])) {
      parse_str($parts['query'], $queryParams);
    }
    if ($queryParams['q'] ?? FALSE) {
      $testedPath = trim($queryParams['q'], '/');
    }
    else {
      $testedPath = trim($path, '/');
    }

    $matchCiviCrmPath = trim($matchCiviCrmPath, '/');
    return ($testedPath == $matchCiviCrmPath);
  }

}
