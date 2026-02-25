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
function reasonable_civicrm_preProcess(string $formName, \CRM_Core_Form &$form): void {
  CRM_Reasonable_Util::hook('preProcess', $formName, $form);
}

/**
 * Implements hook_civicrm_postProcess().
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_postProcess
 *
 */
function reasonable_civicrm_postProcess(string $formName, \CRM_Core_Form &$form): void {
  // TODO: I'd rather have each alteration define its own hooks as event listeners,
  // so we coud simply initialize those listeners in hook_civicrm_config(). However,
  // when I tried this today, I found that if we use that approach, civicrm won't
  // fire the postProcess hook for the form 'CRM_Contribute_Form_Contribution_Confirm',
  // which is exactly the thing we need here. So we have to use old-school hooks.
  CRM_Reasonable_Util::hook('postProcess', $formName, $form);
}

/**
 * Implements hook_civicrm_buildForm().
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_buildForm
 */
function reasonable_civicrm_buildForm(string $formName, \CRM_Core_Form &$form): void {
  // Call any buildForm hooks in enabled alterations.
  CRM_Reasonable_Util::hook('buildForm', $formName, $form);

  // Take our own specific action for the Settings form.
  if ($formName == 'CRM_Admin_Form_Generic' && $form->getSettingPageFilter() == 'reasonable') {
    $ext = CRM_Extension_Info::loadFromFile(E::path('info.xml'));
    $alterations = CRM_Reasonable_Util::getAlterationClasses();

    // Alert user to clear caches if new alterations have been made available in latest code.
    foreach ($alterations as $alteration) {
      $settingName = 'reasonable_alteration_' . $alteration;
      if (!array_key_exists($settingName, $form->_elementIndex)) {
        CRM_Core_Session::setStatus(E::ts('This version of "%1" contains some alterations not available on this screen. Please <a href="%2">cleanup CiviCRM caches</a> to see all available alterations.', [
          '1' => $ext->label,
          '2' => CRM_Utils_System::url('civicrm/admin/setting/updateConfigBackend', 'reset=1'),
        ]));
        break;
      }
    }

    // Alert user to clear caches if OLD alterations have been made UNavailable in latest code.
    foreach ($form->_elementIndex as $elementName => $elementIndexValue) {
      if (strpos($elementName, 'reasonable_alteration_') === 0) {
        $alterationName = preg_replace('/^reasonable_alteration_/', '', $elementName);
        if (!in_array($alterationName, $alterations)) {
          CRM_Core_Session::setStatus(E::ts('This screen offers alterations which have been removed from "%1". Please <a href="%2">cleanup CiviCRM caches</a> to update available alterations.', [
            '1' => $ext->label,
            '2' => CRM_Utils_System::url('civicrm/admin/setting/updateConfigBackend', 'reset=1'),
          ]));
          break;
        }
      }
    }
  }
}
