<?php

use CRM_Reasonable_ExtensionUtil as E;

$ret = [];

$reasonableAlterationClasses = CRM_Reasonable_Util::getAlterations();

foreach ($reasonableAlterationClasses as $reasonableAlterationClass) {
  $obj = new $reasonableAlterationClass();
  $key = $obj->constructionSettingsKey();
  $ret[$key] = [
    'name' => $key,
    'is_domain' => 1,
    'is_contact' => 0,
    'title' => $obj->getTitle(),
    'description' => $obj->getDescription(),
    'type' => 'Boolean',
    'default' => 0,
    'settings_pages' => array(
      'reasonable' => array(
        'weight' => 99,
      ),
    ),
    'html_type' => 'checkbox',
  ];
  unset($obj);
}
return $ret;
