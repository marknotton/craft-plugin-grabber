<?php
namespace Craft;

class IdentifyService extends BaseApplicationComponent {

  // For conditions that are dependant on other plugins, 
  // perform a quick check to see if a plugin is installed and enabled
  public function plugin($name) {
    $plugin = craft()->plugins->getPlugin($name, false);
    return $plugin->isInstalled && $plugin->isEnabled;
  }

  // Used to add a single ID to the body. Strongly assists with CSS styling for specific pages, amongst other things
  // {{ identify.page }}
  public function page() {
    $page = craft()->quick->entry()['slug'];
    return $this->plugin('quick') && !empty($page) ? $page : 'home';
  }

  // Define a title if one hasn't already been set
  public function title() {
    // TODO: Find a way of loading this after setStatus has been defined from the "quick" plugin
    // This is so error pages can sucessfully add data to the results array.
    return $this->plugin('quick') ? craft()->quick->entry()['title'] : 'No Title';
  }

  // Any additional classes defined in the template file will also be added to the classes list
  // {% set classes = "404" %}
  public $extraClasses = null;

  // Check to see if you are running locally
  public function local() {
    return $_SERVER['REMOTE_ADDR']=='127.0.0.1' ? 1 : 0;
  }

  // Gather information on the current page and define a usefull list of classes
  // {{ identify.classes }}
  public function classes() {

    if ($this->plugin('quick')) {
      $entry = craft()->quick->entry();
      $page = $this->page();
      
      // This is the information that will be searched and added to the classes
      // The 'slug' is automatically added. 
      $entryInfo = ['id', 'parent', 'child'];
      $sectionInfo = ['type', 'handle'];

      // If the parent or child is set, also include the levels
      if (!empty($entry['parent']) || !empty($entry['child'])) { 
        array_push($entryInfo, 'level');
      }

      $classes = [];
      
      // TODO: If this is an error page, refer to that error number instead of any classes

      // Get Entry Information
      while (list($key, $value) = each($entryInfo)) {
        // echo $key.' - '.$value;
        if (is_numeric($entry[$value])) {
          array_push($classes, strtolower($value.'-'.$entry[$value]));
        } else {
          if ($entry[$value] != $page) {
            array_push($classes, strtolower($entry[$value]));
          }
        }
      }
      // Get Section Information
      if (isset($entry['section'])) {
        while (list($key, $value) = each($sectionInfo)) {
          if (is_numeric($entry['section'][$value])) {
            array_push($classes, $value.'-'.$entry['section'][$value]);
          } else {
            if (strtolower($entry['section'][$value]) != $page) {
              array_push($classes, strtolower($entry['section'][$value]));
            }
          }
        }
      }

      array_push($classes, $this->extraClasses);

      if ($this->local()) {
        array_push($classes, 'local');
      }

      // Device Type
      if ($this->plugin('browser')) {
        $desktop = craft()->browser->agent->isDesktop();
        $mobile  = craft()->browser->agent->isMobile();
        $tablet  = craft()->browser->agent->isTablet();

        $device = ElementHelper::createSlug(craft()->browser->agent->device());
        array_push($classes, $device);

        $pageNumber = craft()->request->getPageNum();

        if ( $pageNumber > 1 ) { 
          array_push($classes, 'page-number-'.craft()->request->getPageNum());
        }

        if ( $desktop ) { array_push($classes, 'desktop'); }
        else if ( $tablet ) { array_push($classes, 'tablet'); }
        else if ( $mobile ) { array_push($classes, 'mobile'); }
      }

      $classes = array_unique($classes);

      $classes = array_filter($classes);

      $classes = implode(" ", $classes);

      $classes = trim($classes);

      return $classes;
    }
  }

}