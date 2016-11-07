<?php
namespace Craft;

use Twig_Extension;

class Grabber_Globals extends \Twig_Extension {

  public function getName() {
    return Craft::t('Grabber Globals');
  }

  public function getGlobals() {
    return craft()->plugins->getPlugin('grabber')->getGlobals();
  }
}
