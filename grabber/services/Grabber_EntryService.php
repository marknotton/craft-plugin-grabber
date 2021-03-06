<?php
namespace Craft;

class Grabber_EntryService extends BaseApplicationComponent {

  private $cacheEntry = [];

  public function entry() {

    $id      = null;
    $section = null;
    $full    = false;
    $cacheIt = false;

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
          if ($setting === true) {
            $full = $setting;
          } else if ($setting === false) {
            $cacheIt = $setting;
          }
        break;
      }
    }

    // Create a unique name for caching
    if(empty($id) && empty($section)) {
      $cache_name = 'current';
    } else {
      $cache_name = (string)$id.$section;
    }

    // If the cache name doesn't exist in the cache,
    // Then create and store the results.
    if (!array_key_exists($cache_name, $this->cacheEntry) || $cacheIt === false) {

      // echo "<br>This entry was queried just once: ".$cache_name."<br>";

      $element = craft()->urlManager->getMatchedElement();

      if ( is_null($id) && $cache_name == 'current' && isset($element->id)) {
        $id = (int)$element->id;
      }

      if (!empty($id)) {

        if (isset($element) && !empty($element)) {
          if ($element->getElementType() == ElementType::Entry) {
            $type = 'Entry';
            $criteria = craft()->elements->getCriteria(ElementType::Entry);
          } else if ( $element->getElementType() == ElementType::Category) {
            $type = 'Category';
            $criteria = craft()->elements->getCriteria(ElementType::Category);
          } else if ( $element->getElementType() == ElementType::Tag) {
            $type = 'Tag';
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

        }

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
              'parent' => ($entry->hasDescendants() ? 'parent' : false),
              'child' => ($entry->getParent() ? 'child' : false),
            ];

            switch ($type) {
              case 'Entry':
                $section = $entry->section;
                // echo "<pre>"; var_dump($section); echo "</pre>";  die();
                $results['type'] = $entry->getType()->name;
                $results['section'] = craft()->grabber_section->section($section->handle);
              break;
              case 'Category':
                $group = $entry->group;
                $results['type'] = 'category';
                $results['group'] = [
                  'name' => $group->name,
                  'id' => $group->id,
                  'handle' => $group->handle,
                ];
              break;
              case 'Tag':
                $group = $entry->group;
                $results['type'] = 'tag';
                $results['group'] = [
                  'name' => $group->name,
                  'id' => $group->id,
                  'handle' => $group->handle,
                ];
              break;
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
