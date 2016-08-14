<?php
namespace Craft;

class Grabber_GlobalService extends BaseApplicationComponent {

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
