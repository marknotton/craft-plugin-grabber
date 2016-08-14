<?php
namespace Craft;

class Grabber_ContentService extends BaseApplicationComponent {

  // Grab a particular field from a partcilar page
  // See 'entry' function below
  // $field = section or category group
  // $id    = id or slug of entry
  public function content($field = null, $id = null, $section = null) {

    $entry = $this->entry($id, $section, false);

    if (isset($entry) && isset($field)) {
      return $entry->$field;
    }
  }

}
