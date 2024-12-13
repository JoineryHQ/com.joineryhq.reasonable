<?php

use CRM_Reasonable_ExtensionUtil as E;

// Define a settings array which we'll populate and then return.
$ret = [];

// Get a list of all alterations available in this extension.
$reasonableAlterationClasses = CRM_Reasonable_Util::getAlterationClasses();

// Add each alteration as a checkbox setting with a predictable name and 
// with the title/description defined by the alteration class.
foreach ($reasonableAlterationClasses as $reasonableAlterationClass) {
  $obj = CRM_Reasonable_Alteration::singleton($reasonableAlterationClass);
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
