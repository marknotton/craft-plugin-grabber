<?php
namespace Craft;

class Grabber_EntryService extends BaseApplicationComponent {

    private $cacheEntry = [];

    // Grab an array of commonly useed Entry data by defining just the id or slug and the entries given section

    // $id      = id or slug
    // $section = section or category group
    // $full    = if 'true', the entire given entry object will be returned, rather than common attributes

    // {{ quick.entry(2)['title'] }} - Returns homepage title
    // {{ quick.entry('about', '')['title'] }} - Returns homepage title

    // Returns an array of usable information. This is cached the first time it is called.
    // So no additial queries or http requests will trigger should the same criteria be used on the same page more than once
    // id      - Returns {string} - Entry ID
    // title   - Returns {string} - Entry title
    // slug    - Returns {string} - Entry slug
    // url     - Returns {string} - Entry absolute url
    // uri     - Returns {string} - Entry relative url
    // snippet - Returns {string} - Entry Snippet
    // status  - Returns {string} - Entry status
    // level   - Returns {string} - Entry hiarchy level
    // parent  - Returns {bool}   - Checks if entry has a parent
    // child   - Returns {bool}   - Checks if entry has a child
    // type    - Returns {string} - Returns Channel, Structure, Single, or Category
    // section - Returns {array}  - Entry section details. See section function above

    // Reverts to 'best-guess' fallbacks if special circomstance arise, like 404 error pages, or page exist but not in the cms

    public function entry() {

      $id      = null;
      $section = null;
      $full    = false;

      // Atleast one single string arugment should be passed
      if ( func_num_args() >= 1 ){
        $id = func_get_arg(0);
      }

      $arguments = array_slice(func_get_args(), 1);

      foreach ($arguments as &$setting) {
        switch (gettype($setting)) {
          case 'string':
            $section = $setting;
          break;
          case 'boolean':
            $full = $setting;
          break;
        }
      }

      //TODO: Refine results by using $section
      //TODO: If entry cannot be found, assume it's a category and redo the checks.
      //TODO: Make is so if the page doesn't exist, return nothing

      // Create a unique name for caching
      if(empty($id) && empty($section)) {
        $cache_name = 'current';
      } else {
        $cache_name = (string)$id.$section;
      }

      // echo func_get_arg(0);
      // echo is_numeric($id) ? '1' : '2';
        // if ( empty($id)) {
          // $id = craft()->urlManager->getMatchedElement()->id;
        // }


      // If the cache name doesn't exist in the cache,
      // Then create and store the results.
      if (!array_key_exists($cache_name, $this->cacheEntry)) {

        // echo "<br>This entry was queried just once: ".$cache_name."<br>";

        if ( is_null($id) && $cache_name == 'current' && isset(craft()->urlManager->getMatchedElement()->id)) {
          $id = (int)craft()->urlManager->getMatchedElement()->id;
          // echo "new id set:".$id;
        } else {

        }

        if (!empty($id)) {

          // TODO: Little check to avoid errors on pages that don't exist in craft
          // TODO: Determine a way to get the entry type of the given entry settings. Important for Category and Tag pages
          $type = 'Entry';

          if ( isset($type) && $type == "Category") {
            $criteria = craft()->elements->getCriteria(ElementType::Category);
          } else {
            $criteria = craft()->elements->getCriteria(ElementType::Entry);
          }

          if (is_string($id)) {
            $criteria->slug = $id;
          } else {
            $criteria->id = (is_numeric($id) && intval($id) > 0) ? (int)$id : craft()->urlManager->getMatchedElement()->id;
          }

          if ( !empty($section) ) {
            if ( $section == "single" || $section == "channel" || $section == "structure") {
              $criteria->section = $section;
            }
          }

          $criteria->status = null;
          $criteria->limit = 1;

          $entry = $criteria->first();

          if ($full == true) {
            $this->cacheEntry[$cache_name] = $entry;
          } else {
            if (isset($entry)) {

              $results = [
                'id' => $entry->id,
                'title' => $entry->title,
                'slug' => $entry->slug,
                'url' => $entry->url,
                'uri' => $entry->uri,
                'status' => $entry->status,
                'snippet' => isset($entry->snippet) ? $entry->snippet : false,
                'level' => $entry->level,
                'parent' => ($entry->getParent() ? 'parent' : false),
                'child' => ($entry->hasDescendants() ? 'child' : false),
              ];

              // Entry specific data
              if ( $type == "Entry") {
                $section = $entry->section;
                // echo "<pre>"; var_dump($section); echo "</pre>";  die();
                $results['type'] = $entry->getType()->name;
                $results['section'] = craft()->grabber_section->section($section->handle);
              }

              // Category specific data
              if ( $type == "Category") {
                $group = $entry->group;
                $results['type'] = 'category';
                $results['group'] = [
                  'name' => $group->name,
                  'id' => $group->id,
                  'handle' => $group->handle,
                ];
              }

              $this->cacheEntry[$cache_name] = $results;
            } else {
              // If the entry is not found, use the slug or id from the first param
              // to try and find a section instead.
              $section = craft()->grabber_section->section($id);
              if (!is_null($section)) {
                return $section;
              } else {
                // If the entry or category doesn't exist
                return "Doesn't exist";
              }
            }
          }
        } else {

          // When a page doesn't technically exist, create faux results.
          $segments = craft()->request->getSegments();
          $segment = end($segments);
          $title = str_replace(['_', '-'], ' ', $segment);

          // Check if the last URL segment matches an existing section
          if (is_string($segment)) {
            // If $segment is a string, loop through all available sections until one matches
            $sections = craft()->sections->getAllSections();
            while (list(, $sec) = each($sections)) {
              if ($sec->handle == $segment) {
                $section = $sec;
                break;
              }
            }
          } else if (is_numeric($segment)) {
            // Otherwise just use the $segment number
            $section = craft()->sections->getSectionById($id);
          }

          if (isset($section)) {
            $results = [
              'id' => $section->id,
              'title' => $section->name,
              'slug' => $section->handle,
              'type' => $section->type,
              'url' => $section->handle,
              'uri' => $section->handle,
              'status' => 'live',
              'snippet' => null,
              'level' => 1,
              'parent' => false,
              'child' => false
            ];

          } else {
            $results = [
              'id' => null,
              'title' => preg_replace('/\s+/', ' ', ucwords($title)),
              'slug' => preg_replace('/\s+/', '-', $segment),
              'url' => '/'.craft()->request->getPath().'/',
              'uri' => craft()->request->getPath(),
              'snippet' => null,
              'status' => 'live',
              'level' => count($segments),
              'parent' => false,
              'child' => false,
            ];
          }

          $errors = [
            '400'=>'Bad Request',
            '403'=>'Forbidden',
            '404'=>'Page Not Found',
            '500'=>'Internal Server Error',
            '503'=>'Maintenance'
          ];

          // Error page checks
          foreach ($errors as $error => $value) {
            if (http_response_code() == $error) {
              $results['error'] = $error;
              $results['title'] = $results['title'].' | '.(craft()->config->get('devMode') ? $error.' - ' : '').$value;
            }
          }

          $this->cacheEntry[$cache_name] = $results;
        }
      }
      return $this->cacheEntry[$cache_name];
    }

}
