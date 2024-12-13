<?php

/*
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/PHPClass.php to edit this template
 */

/**
 * Description of Util
 *
 * @author as
 */
class CRM_Reasonable_Util {
  public static function getAlterations() {
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

}
