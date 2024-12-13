<?php
use CRM_Reasonable_ExtensionUtil as E;


/**
 * Base class for Reasonable alterations.
 *
 * @author as
 */
class CRM_Reasonable_Alteration {
  protected $isEnabled = FALSE;
  protected $title;
  protected $description;
  
  function __construct() {
    $settingsKey = $this->constructionSettingsKey();
    $enabled = Civi::settings()->get($this->constructionSettingsKey());
    $this->isEnabled = $enabled;
  }
  
  function constructionSettingsKey() {
    return 'reasonable_alteration_' . get_class($this);
  }
  
  function getTitle() {
    if (empty($this->title)) {
      throw new CRM_Extension_Exception('Title not set for Reasonable alteration: '. get_class($this), 'title_missing');
    }
    return E::ts($this->title);
  }
  
  function getDescription() {
    if (empty($this->description)) {
      throw new CRM_Extension_Exception('Description not set for Reasonable alteration: '. get_class($this), 'description_missing');
    }
    return E::ts($this->description);
  }

}
