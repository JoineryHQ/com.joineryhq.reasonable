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
   * For a given hook, return all alteration objects which implement that hook,
   *   sorted by $obj->weight
   *
   * @param string $hookBaseName E.g. 'preProcess'
   * @return array Alteration objects implementing the given hook.
   */
  public static function getHookAlterations($hookBaseName) {
    if (!isset(Civi::$statics[__METHOD__][$hookBaseName])) {
      $alterations = [];
      $alterationClasses = self::getAlterationClasses();
      $classWeights = [];
      foreach ($alterationClasses as $alterationClass) {
        $obj = CRM_Reasonable_Alteration::singleton($alterationClass);
        $hooks = $obj->getHooks();
        if (in_array($hookBaseName, $hooks)) {
          $classWeights[] = $obj->get('weight');
          $alterations[] = $obj;
        }
      }
      array_multisort($classWeights, $alterations);
      Civi::$statics[__METHOD__][$hookBaseName] = $alterations;
    }
    return Civi::$statics[__METHOD__][$hookBaseName];
  }

  /* For a given hook, invoke the hook implementations from all enabled alterations.
   */
  public static function hook($hookBaseName, &$arg1 = NULL, &$arg2 = NULL, &$arg3 = NULL, &$arg4 = NULL, &$arg5 = NULL, &$arg6 = NULL) {
    $hookAlterations = CRM_Reasonable_Util::getHookAlterations($hookBaseName);
    foreach ($hookAlterations as $hookAlteration) {
      // Don't fire this hook implementation unless the alteration is enabled.
      if ($hookAlteration->get('isEnabled')) {
        $hookArgCount = self::countArgsPerHook($hookBaseName);
        $methodName = 'hook_' . $hookBaseName;
        switch($hookArgCount) {
          case 1:
            $hookAlteration->$methodName($arg1);
            break;
          case 2:
            $hookAlteration->$methodName($arg1, $arg2);
            break;
          case 3:
            $hookAlteration->$methodName($arg1, $arg2, $arg3);
            break;
          case 4:
            $hookAlteration->$methodName($arg1, $arg2, $arg3, $arg4);
            break;
          case 5:
            $hookAlteration->$methodName($arg1, $arg2, $arg3, $arg4, $arg5);
            break;
          case 6:
            $hookAlteration->$methodName($arg1, $arg2, $arg3, $arg4, $arg5, $arg6);
            break;
        }
      }
    }
  }

  /**
   * For a given hook, return the number of expected arguments in a hook implementation.
   *
   * @param String $hookName e.g. 'preProcess'
   * @return Int
   */
  private static function countArgsPerHook($hookName) {
    static $cache = [];
    if (!isset($cache[$hookName])) {
      $ref = new ReflectionMethod('CRM_Utils_Hook', $hookName);
      $cache[$hookName] = $ref->getNumberOfParameters();
    }
    return $cache[$hookName];
  }
}
