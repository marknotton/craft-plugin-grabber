<?php
namespace Craft;

class Grabber_LinkService extends BaseApplicationComponent {

  // Used to add a single ID to the body. Strongly assists with CSS styling for specific pages, amongst other things

  // {{ grab.link('contact', {
  //    'relative' : true,
  //    'classes'  : 'someClass',
  //    'target'   : '_blank',
  //    'title'    : 'Bespoke Title',
  // }) }}
  //
  // <a href="/contact" class="someClass" target="_blank" title="Bespoke Title">Contact Us</a>

  // By passing a 'true' boolean, the link will be returned; as aposed to a href tag will all the gubbins
  // {{ grab.link('contact', true }}
  //
  // http://www.website.com/contact

  public function link() {

    $section  = null;
    $justUrl  = null;
    $settings = null;

    if ( func_num_args() >= 1 ){
      $id = func_get_arg(0);
    }

    $arguments = array_slice(func_get_args(), 1);

    foreach ($arguments as &$setting) {
      switch (gettype($setting)) {
        case 'boolean':
          $justUrl = $setting;
        break;
        case 'string':
          $section = $setting;
        break;
        case 'array':
          $settings = $setting;
        break;
      }
    }

    $relative = null; // if true, absolute link will be added, as-per your 'siteUrl' environmental variable settings
    $classes  = null;
    $target   = null;
    $title    = null;

    if (!is_null($settings)) {
      foreach ($settings as $key => $value) {

        if ( gettype($value) == 'boolean') {
          $relative = $value;
        } else {
          switch ($key) {
            case 'classes':
              $classes = $value;
            break;
            case 'title':
              $title = $value;
            break;
            case 'target':
              if(substr($value, 0, 1) !== '_') {
                $value = '_'.$value;
              }
              if (in_array($value, array('_blank', '_self', '_parent', '_top'))) {
                $target = $value;
              }
            break;
          }
        }
      }
    }

    $entry = craft()->grabber_entry->entry($id, $section);

    if ($justUrl) {

      return $relative ? $entry['uri'] : $entry['url'];

    } else {

      $title = is_null($title) ? $entry['title'] : $title;
      $url = $relative ? $entry['uri'] : $entry['url'];

      // $url = explode('/', $url);
      //
      // $urlSegments = [];
      //
      // foreach($url as $segment) {
      //   array_push($urlSegments, ElementHelper::createSlug($segment));
      // }
      //
      // $url = implode('/', $urlSegments);

      $link  = '<a ';
      $link .= 'href="'.$url.'"';
      if ($classes) { $link .= ' class="'.$classes.'"'; }
      if ($target) { $link .= ' target="'.$target.'"'; }
      $link .= ' title="'.$title.'"';
      $link .= '>';
      $link .= $title;
      $link .= '</a>';

      return $link;

    }
  }
}
