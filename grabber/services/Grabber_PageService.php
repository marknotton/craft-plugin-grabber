<?php
namespace Craft;

class Grabber_PageService extends BaseApplicationComponent {

  public function page() {
    $page = craft()->grabber_entry->entry()['slug'];
    $errors = array("400", "403", "404", "500", "503");
    if (in_array($page, $errors)) {
      $page = "error-".$page;
    }
    return !empty($page) ? $page : 'home';
  }

}
