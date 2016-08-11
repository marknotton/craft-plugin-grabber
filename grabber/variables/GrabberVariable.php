<?php
namespace Craft;
class IdentifyVariable {

  public function plugin($name) {
    return craft()->identify->plugin($name);
  }

  public function page() {
     return craft()->identify->page();
  }

  public function title() {
    return craft()->identify->title();
  }

  public function getEntry() {
     return craft()->identify->getEntry();
  }
}
