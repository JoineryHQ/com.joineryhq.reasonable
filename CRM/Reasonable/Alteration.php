<?php
use CRM_Reasonable_ExtensionUtil as E;

/**
 * Base class for Reasonable alterations.
 *
 */
class CRM_Reasonable_Alteration {

  /**
   * @var bool
   * Is this alteration enabled, per site-wide extension settings?
   */
  protected $isEnabled = FALSE;

  /**
   * @var string
   * The title of this alteration, to appear in extension settings.
   * (Translation is handled elsewhere, so don't use ts() here.
   */
  protected $title;

  /**
   * @var string
   * The description of this alteration, to appear in extension settings.
   * (Translation is handled elsewhere, so don't use ts() here.
   */
  protected $description;

  /**
   * @var int
   * Larger weights sort toward the bottom of the list.
   * Used for:
   *   - Ordering of hook execution when multiple alterations implement a the same hook.
   *   - Ordering of alterations on Settings page.
   */
  protected $weight;

  private function __construct() {
    // Determine the value of the setting for this alteration, and mark this
    // alteration object as enabled/disabled accordingly, per that setting.
    $settingsKey = $this->constructionSettingsKey();
    $enabled = Civi::settings()->get($this->constructionSettingsKey());
    $this->isEnabled = $enabled;
  }

  /**
   * Create and return a singleton instance of a named alteration.
   * @param string $className e.g. CRM_Reasonable_Alteration_OnBehalfEmployer
   * @return object
   */
  public static function singleton($className) {
    // Ensure className extends this base class
    if (!is_subclass_of($className, __CLASS__)) {
      throw new CRM_Extension_Exception("Given class name '$className' does not extend " . __CLASS__, 'does_not_extend_alteration_base_class');
    }
    static $singletons;
    if (!isset($singletons[$className])) {
      $singletons[$className] = new $className();
    }
    return $singletons[$className];
  }

  /**
   * Determine the settings key to use for this alteration.
   * @return string
   */
  public function constructionSettingsKey() {
    return 'reasonable_alteration_' . get_class($this);
  }

  /**
   * Get the ts() translated value of $this->title.
   */
  public function getTitle() {
    if (empty($this->title)) {
      throw new CRM_Extension_Exception('Title not set for Reasonable alteration: ' . get_class($this), 'title_missing');
    }
    return E::ts($this->title);
  }

  /**
   * Get the ts() translated value of $this->description.
   */
  public function getDescription() {
    if (empty($this->description)) {
      throw new CRM_Extension_Exception('Description not set for Reasonable alteration: ' . get_class($this), 'description_missing');
    }
    return E::ts($this->description);
  }

  /**
   * Get a property from this object, via a special getter if such exists, or
   * else just the bare property value.
   *
   * @param String $property
   * @return Mixed
   */
  public function get($property) {
    $methodName = 'get'. ucfirst($property);
    if (method_exists($this, $methodName)) {
      return call_user_func([$this, $methodName]);
    }
    if (property_exists($this, $property)) {
      return $this->$property;
    }
  }

  /**
   * Get an array of base hook names (e.g. 'preProcess') for all hooks defined
   * in this alteration (methods having names beginning with 'hook_')
   * @return array
   */
  public function getHooks() {
    $methods = get_class_methods($this);
    $hooks = preg_grep('/^hook_/', $methods);
    $hooks = preg_replace('/^hook_/', '', $hooks);
    return $hooks;
  }

}
