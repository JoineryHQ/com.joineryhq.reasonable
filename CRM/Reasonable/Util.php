<?php

/*
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/PHPClass.php to edit this template
 */

/**
 * Utilities for Reasonable extension
 *
 */
class CRM_Reasonable_Util {

  /**
   * Get a list of class names for all alterations defined under CRM/Reasonable/Alteration.
   * @return array
   */
  public static function getAlterationClasses() {
    if (empty(Civi::$statics[__METHOD__])) {
      $origClasses = get_declared_classes();

      $path = CRM_Core_Resources::singleton()->getPath('com.joineryhq.reasonable') . '/CRM/Reasonable/Alteration/';
      $files = glob($path . '/*.php');
      foreach ($files as $file) {
        require_once $file;
      }
      $newClasses = get_declared_classes();

      Civi::$statics[__METHOD__] = preg_grep('/^CRM_Reasonable_Alteration_/', array_diff(
        $newClasses,
        $origClasses
      ));
    }
    return Civi::$statics[__METHOD__];
  }

  /**
   * For a given hook, return all alteration objects which implement that hook.
   *
   * @param string $hookBaseFilter E.g. 'preProcess'
   * @return array Alteration objects implementing the given hook.
   */
  public static function getHookAlterations($hookBaseFilter) {
    if (!isset(Civi::$statics[__METHOD__])) {
      Civi::$statics[__METHOD__] = [];
      $reasonableAlterationClasses = self::getAlterationClasses();
      foreach ($reasonableAlterationClasses as $reasonableAlterationClass) {
        $obj = CRM_Reasonable_Alteration::singleton($reasonableAlterationClass);
        $hooks = $obj->getHooks();
        foreach ($hooks as $hook) {
          Civi::$statics[__METHOD__][$hook][] = $obj;
        }
      }
    }
    return Civi::$statics[__METHOD__][$hookBaseFilter];
  }

}
