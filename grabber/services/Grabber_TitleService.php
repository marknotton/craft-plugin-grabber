<?php
namespace Craft;

class Grabber_TitleService extends BaseApplicationComponent {

  // Define a title if one hasn't already been set
  public function title() {
    // TODO: Find a way of loading this after setStatus has been defined from the "quick" plugin
    // This is so error pages can sucessfully add data to the results array.
    return craft()->grabber_entry()['title'];
  }

}
