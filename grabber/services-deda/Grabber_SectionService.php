<?php
namespace Craft;

class Grabber_SectionService extends BaseApplicationComponent {
  private $cacheSection = [];

  // Grab an array of commonly useed Section data by defining just the id or slug and the entries given section
  // section - Returns {array}  - Entry section name
  // title {string}  -  Section title
  // name {string}   -  Section name
  // id {string}     -  Section ID
  // handle {string} -  Section handle
  // type {string}   -  Section Type - Channel, Structure, or Single
  // url {string}    -  Section absolute url
  // uri {string}    -  Section relative url
  public function section($id) {
    // Create a unique name for caching
    $cache_name = empty($id) ? 'current' : (string)$id;

    if (!array_key_exists($cache_name, $this->cacheSection)) {

      if (is_string($id)) {
        // If $id is a string, loop through all available sections until one matches
        $sections = craft()->sections->getAllSections();
        while (list(, $sec) = each($sections)) {
          if ($sec->handle == $id) {
            $section = $sec;
            break;
          }
        }
      } else {
        // Otherwise just use the ID number
        $section = craft()->sections->getSectionById($id);
      }

      if (isset($section)) {
        $results = [
          'id' => $section->id,
          'title' => $section->name,
          'name' => $section->name,
          'handle' => $section->handle,
          'slug' => $section->handle,
          'type' => $section->type,
          'url' => $section->handle, // TODO
          'uri' => $section->handle, // TODO
          // 'association' => $section->association,
        ];

        $this->cacheSection[$cache_name] = $results;
      } else {
        // When a section doesn't technically exist, create faux results.

        if (is_string($id)) {

          $title = str_replace(['_', '-'], ' ', $id);

          $results = [
            'id' => null,
            'title' => preg_replace('/\s+/', ' ', ucwords($title)),
            'slug' => preg_replace('/\s+/', '-', $id),
            'url' => '/'.$id.'/',
            'uri' => $id,
            'status' => 'live',
            'level' => false,
            'parent' => false,
            'child' => false,
          ];
        }
        $this->cacheSection[$cache_name] = $results;
      }
    }
    return $this->cacheSection[$cache_name];
  }

}
