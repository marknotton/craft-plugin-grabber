<?php
namespace Craft;

use Twig_Extension;

class Grabber_Globals extends \Twig_Extension {

  public function getName() {
    return Craft::t('Grabber Globals');
  }

  public function getGlobals() {

    $envVars = null !== craft()->config->get('environmentVariables') ? craft()->config->get('environmentVariables') : false;
    $global = craft()->plugins->getPlugin('grabber')->getGlobals();

    if ($envVars && craft()->plugins->getPlugin('grabber')->getSettings()['envVarsToGlobalVars']) {
      $global = array_merge($global, craft()->config->get('environmentVariables'));
    }

    return $global;
  }
}
