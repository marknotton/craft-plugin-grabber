<?php
namespace Craft;

class Grabber_PageService extends BaseApplicationComponent {

  public function page() {
    $page = craft()->grabber_entry->entry()['slug'];
    return !empty($page) ? $page : 'home';
  }

}
