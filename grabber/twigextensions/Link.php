<?php
namespace Craft;

use Twig_Extension;
use Twig_Filter_Method;

class Link extends \Twig_Extension {

  public function getName() {
    return Craft::t('Link');
  }

  public function getFilters() {
    return array(
      'link' => new Twig_Filter_Method( $this, 'link', array('is_safe' => array('html')))
    );
  }

  public function link() {
    // Atleast one single string arugment should be passed
    if ( func_num_args() >= 1 ){
      $html = func_get_arg(0);
    } else {
      return false;
    }

    $arguments = array_slice(func_get_args(), 2);
    $id        = func_get_arg(1);

    $section  = null;
    $settings = null;


    foreach ($arguments as &$setting) {
      switch (gettype($setting)) {
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
    $title = is_null($title) ? $entry['title'] : $title;
    $url = ElementHelper::createSlug($relative ? $entry['uri'] : $entry['url']);

    if (empty($url)) {
      $url = '/';
    } else if (!preg_match("~^(?:f|ht)tps?://~i", $url)) {
      $url = "http://" . $url;
    }

    $link  = '<a ';
    $link .= 'href="'.$url.'"';
    if ($classes) { $link .= ' class="'.$classes.'"'; }
    if ($target) { $link .= ' target="'.$target.'"'; }
    $link .= ' title="'.$title.'"';
    $link .= '>';
    $link .= $html;
    $link .= '</a>';

    return $link;


  }
}
