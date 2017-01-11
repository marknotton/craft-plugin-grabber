<?php
namespace Craft;

class Grabber_DataService extends BaseApplicationComponent {

  private $cachedData = [];

  /**
   * [Grab common data from any entry, section, category or tag]
   * @param  [string] $type [Entry type, 'entry', 'category', 'tag']
   * @param  [string | number] $id [the id or handle/slug you want to target]
   * @param  [string | number] $section [the id or handle of the section relivent to the $id (optional)]
   * @param  [bool] $bool [true if you want the return an entire object, otherwise restricted/useful data is cached and returned
   * false if you don't want to all this query to be cahced (optional)]
   * @return [object] based on the criteria, a list of useful data will be returned
   */
  public function data() {

    $id       = null;
    $section  = null;
    $type     = null;
    $criteria = null;
    $full     = false;
    $cache    = true;

    $arguments = func_get_args();
    // If the first argument is a speicifc string, set this as the data type
    // that needs to be searched for.
    if ( gettype($arguments[0]) == 'string' && preg_match("(entry|category|tag)", $arguments[0]) === 1) {
      $type = array_shift($arguments);
    }

    // Atleast one single string arugment should be passed
    if ( count($arguments) >= 1 ){
      $id = array_shift($arguments);
    }

    // Loop through all remaining argments and apply them as settings
    foreach ($arguments as &$setting) {
      switch (gettype($setting)) {
        case 'string':
          $section = is_null($section) ? $setting : $section;
        break;
        case 'boolean':
          if ($setting === true) {
            $full = $setting;
          } else if ($setting === false) {
            $cache = $setting;
          }
        break;
      }
    }

    // Create a unique name for caching
    $cache_name = (string)$id.$section;

    // Return cached data if it exists, and $cache is set to false (default)
    if (array_key_exists($cache_name, $this->cachedData) && $cache === true) {
      return $this->cachedData[$cache_name];
    }

    // echo "<br>This entry was queried just once: ".$cache_name."<br>";

    // Set the criteria model depending on the element type
    switch ($type) {
      case 'entry':
        $criteria = craft()->elements->getCriteria(ElementType::Entry);
      break;
      case 'category':
        $criteria = craft()->elements->getCriteria(ElementType::Category);
      break;
      case 'tag':
        $criteria = craft()->elements->getCriteria(ElementType::Tag);
      break;
    }

    if (!empty($id) && !empty($criteria)) {

      if (is_string($id)) {
        // If id is passed as a string, assume it is targeting the slug
        $criteria->slug = ElementHelper::createSlug($id);
      } else if (is_numeric($id) && intval($id) > 0){
        // Otherwise a number will be used as an id number
        $criteria->id = (int)$id;
      }

      // If a section was defined, include this in the criteria for more accurate results
      if ( !empty($section) ) {
        $criteria->section = $section;
      }

      // Return all entries regardless of status (disabled entries will be included)
      $criteria->status = null;

      // Only return the one entry
      $criteria->limit = 1;

      // Set the entry
      $entry = $criteria->first();

      // If the entry exists...
      if (isset($entry)) {
        if ($full == true) {
          // If the 'full' setting is true, add the entire entry object to the cachedData
          // $this->cachedData[$cache_name] = $entry;
          return $entry;
        } else {
          // Set results array with commonly requested data
          $results = [
            'id'      => $entry->id,
            'title'   => $entry->title,
            'slug'    => $entry->slug,
            'url'     => $entry->url,
            'uri'     => $entry->uri,
            'status'  => $entry->status,
            'snippet' => isset($entry->snippet) ? $entry->snippet : false,
            'level'   => $entry->level,
            'parent'  => ($entry->hasDescendants() ? 'parent' : false),
            'child'   => ($entry->getParent() ? 'child' : false),
            'error'   => false,
          ];
          // Depending on the type, add additional data to the results array
          switch ($type) {
            case 'entry':
              $section = $entry->section;
              $results['type'] = $entry->getType()->name;
              // $results['section'] = craft()->grabber_section->section($section->handle);
            break;
            case 'category':
              $group = $entry->group;
              $results['type'] = 'category';
              $results['group'] = [
                'name' => $group->name,
                'id' => $group->id,
                'handle' => $group->handle,
              ];
            break;
            case 'tag':
              $group = $entry->group;
              $results['type'] = 'tag';
              $results['group'] = [
                'name' => $group->name,
                'id' => $group->id,
                'handle' => $group->handle,
              ];
            break;
          }
          $this->cachedData[$cache_name] = $results;
        }
      } else {
        // Fallback if an id was passed
        $this->cachedData[$cache_name] = $this->fallback($id);
      }
    } else {
      // Fallback if no id or criteria was found
      $this->cachedData[$cache_name] = $this->fallback();
    }

    return $this->cachedData[$cache_name];
  }

  /**
   * [When a page doesn't technically exist, create faux results.]
   * @param  [type] $id []
   * @return [object]     [description]
   */
  public function fallback($id=null){

    if (isset($id) && !is_numeric($id)) {
      $sectionObj = craft()->grabber_section->search($id);
    } else {
      // Grab URL segments
      $segments = craft()->request->getSegments();

      // Assume the last segment if the current page
      $segment = !empty($segments) ? end($segments) : 'homepage';

      $sectionObj = craft()->grabber_section->search($segment);
    }

    if (!empty($sectionObj) && $sectionObj->type == 'single') {

      $criteria = craft()->elements->getCriteria(ElementType::Entry);
      $criteria->section = $sectionObj->handle;
      $section = $criteria->first();

      $results = [
        'id'      => $section->id,
        'title'   => $section->title,
        'slug'    => $section->slug,
        'type'    => 'single',
        'url'     => $section->url,
        'uri'     => $section->uri,
        'status'  => $section->status,
        'snippet' => isset($section->snippet) ? $section->snippet : false,
        'level'   => $section->level,
        'parent'  => ($section->hasDescendants() ? 'parent' : false),
        'child'   => ($section->getParent() ? 'child' : false),
        'error'   => false,
      ];
    } else if (isset($id) && !is_numeric($id)) {
      $slug = ElementHelper::createSlug($id);
      $results = [
        'id'      => null,
        'title'   => preg_replace('/\s+/', ' ', ucwords($id)),
        'slug'    => $slug,
        'url'     => '/'.$slug.'/',
        'uri'     => $slug,
        'snippet' => null,
        'status'  => 'fake',
        'level'   => 1,
        'parent'  => false,
        'child'   => false,
        'error'   => 'fallback',
      ];
    } else if(is_numeric($id)){
      $results = [
        'id'      => null,
        'title'   => null,
        'slug'    => null,
        'url'     => null,
        'uri'     => null,
        'snippet' => null,
        'status'  => null,
        'level'   => null,
        'parent'  => null,
        'child'   => null,
        'error'   => null,
      ];
    } else {

      $title = str_replace(['_', '-'], ' ', $segment);

      $slug = ElementHelper::createSlug($segment);
      $results = [
        'id'      => null,
        'title'   => preg_replace('/\s+/', ' ', ucwords($title)),
        'slug'    => $slug,
        'url'     => '/'.$slug.'/',
        'uri'     => $slug,
        'snippet' => null,
        'status'  => 'fake',
        'level'   => 1,
        'parent'  => false,
        'child'   => false,
        'error'   => 'fallback',
      ];
    }

    // List of errors to check
    $errors = [
      '400' => 'Bad Request',
      '403' => 'Forbidden',
      '404' => 'Page Not Found',
      '500' => 'Internal Server Error',
      '503' => 'Maintenance'
    ];

    // Error page checks
    foreach ($errors as $error => $value) {
      if (http_response_code() == $error) {
        $results['error'] = $error;
        $results['title'] = $results['title'].' | '.(craft()->config->get('devMode') ? $error.' - ' : '').$value;
      }
    }

    return $results;
  }
}
