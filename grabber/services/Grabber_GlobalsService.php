<?php
namespace Craft;

class Grabber_GlobalsService extends BaseApplicationComponent {

  public function globals($field = null, $set = null) {

    if ( is_null($field) && is_null($set) ) {
      return false;
    }

    $sets = array();
    $globals = craft()->templates->getTwig()->getGlobals();
    foreach($globals as $key => $value) {
      if(is_object($value) && is_a($value, 'Craft\GlobalSetModel')) {
        $sets[$value->handle] = $value->getContent()->getAttributes();
      }
    }

    if (!is_null($set) && isset($sets[$set][$field])) {
      // Search through a specific set to find the $field that matches
      return $sets[$set][$field];
    } else {
      // Search through all sets to find the first $field that matches
      foreach($sets as $key => $value) {
        if (gettype($set) === 'integer') {
          // echo 'number'.$sets[$key]['elementId'];
          if($sets[$key]['elementId'] == $set && isset($sets[$key][$field])) {
            return $sets[$key][$field];
          }
        } else {
          if(isset($sets[$key][$field])) {
            return $sets[$key][$field];
          }
        }
      }
    }
  }

}
