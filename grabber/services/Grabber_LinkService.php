<?php
namespace Craft;

class Grabber_LinkService extends BaseApplicationComponent {

  public function linkFilter($html, $href='/', $classes=null, $target=null) {

    if ( !empty($html) ) {

      if (isset($classes)) {
        $classes = ' class="'.$classes.'"';
      }

      if (isset($target)) {
        $target = ' target="'.($target[0] == '_' ? $target : '_'.$target).'"';
      }

      $tag = '<a href="'.$href.'"'.$classes.$target.'>';

      return $tag.rtrim($html).'</a>';

    } else {

      return false;

    }
  }

}
