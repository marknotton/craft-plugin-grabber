<?php
namespace Craft;

class Grabber_ContentService extends BaseApplicationComponent {

  // Grab a particular field from a partcilar page.
  // {{ grab.content('gallery', 405)}}
  public function content($field = 'body', $id = null, $section = null) {

    $entry = craft()->grabber_entry->entry($id, $section, true);

    if (isset($entry) && isset($entry[$field])) {
      return $entry[$field];
    }
  }

}
