<?php
namespace Craft;

class Grabber_ClassesService extends BaseApplicationComponent {

  // Any additional classes defined in the template file will also be added to the classes list
  // {% set classes = "404" %}
  public $extraClasses = null;

  // Gather information on the current page and define a usefull list of classes
  // {{ grab.classes }}
  public function classes() {

    $entry = craft()->grabber_entry->entry();
    $page = $entry['slug'];

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

    if ($_SERVER['REMOTE_ADDR']=='127.0.0.1') {
      array_push($classes, 'local');
    }

    // Device Type
    if (craft()->grabber_plugin->plugin('browser')) {
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
