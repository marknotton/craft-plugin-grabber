<?php
namespace Craft;

use Twig_Extension;

class Grabber_Globals extends \Twig_Extension {

  public function getName() {
    return Craft::t('Grabber Globals');
  }

  public function getGlobals() {

    // Default values to add
    $globals = array(
      'grab'     => craft()->grabber,
      'title'    => craft()->grabber_entry->entry()['title']
    );

    // Get plugin settings 'globalVariablesName'
    $GVSettings = craft()->plugins->getPlugin('grabber')->getSettings()['globalVariablesName'];

    // Check settings are not empty or revert to default
    $GVName = !empty($GVSettings) ? $GVSettings : 'environmentVariables';

    // Define variable if the global variables exist
    $globalVariables = craft()->config->get($GVName) !== null ? craft()->config->get($GVName) : null;

    if (!is_null($globalVariables)) {
      $globals = array_merge($globalVariables, $globals);
    }

    // If theese don't exist, add them too
    $fallbackVariables = array(
      'videos'   => "/assets/images",
      'images'   => "/assets/videos",
      'js'       => "/assets/js",
      'css'      => "/assets/css"
    );

    return array_unique(array_merge($fallbackVariables, $globals), SORT_REGULAR);

  }
}
