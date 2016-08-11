<?php
namespace Craft;

use Twig_Extension;

class checkfields extends \Twig_Extension {

  public function getName() {
    return Craft::t('Field Exists');
  }

  public function getFunctions() {
    return array(
      'getGlobal' => new \Twig_Function_Method($this, 'getGlobal'),
    );
  }

  public function getGlobal($data, $handle = 'settings') {
    if (strpos($data, '.')) {
      $field = explode(".", $data)[1];
      $handle = explode(".", $data)[0];
    } else {
      $field = $data;
    }


    if ($global = isset(craft()->globals->getSetByHandle($handle)->$field)) {
      if ($global != null && !empty($global)) {
        return craft()->globals->getSetByHandle($handle)->$field;
      }
    } 
  }
}