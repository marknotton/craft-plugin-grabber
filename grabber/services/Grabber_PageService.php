<?php
namespace Craft;

class Grabber_PageService extends BaseApplicationComponent {

  // Used to add a single ID to the body. Strongly assists with CSS styling for specific pages, amongst other things
  // {{ grab.page }}
  public function page() {
    $page = craft()->grabber_entry->entry()['slug'];
    return !empty($page) ? $page : 'home';
  }

}
