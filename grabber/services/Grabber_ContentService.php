<?php
namespace Craft;

class Grabber_ContentService extends BaseApplicationComponent {

  // Grab a particular field from a partcilar page.
  // {{ grab.content('gallery', 405)}}
  public function content() {

    $id      = null;
    $section = null;

    // Atleast one single string arugment should be passed
    if ( func_num_args() >= 1 ){
      $field = func_get_arg(0);
      $arguments = array_slice(func_get_args(), 1);

      foreach ($arguments as &$setting) {
        switch (gettype($setting)) {
          case 'integer':
            $id = $setting;
          break;
          case 'string':
            $section = $setting;
          break;
        }
      }
    } else {
      $field = 'body';
    }

    $entry = craft()->grabber_entry->entry($id, $section, false);

    if (isset($entry) && isset($field)) {
      return $entry[$field];
    }
  }

}
