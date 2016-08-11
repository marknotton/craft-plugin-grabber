<?php
namespace Craft;

use Twig_Extension;
use Twig_Filter_Method;

class link extends \Twig_Extension {

  public function getName() {
    return Craft::t('Link');
  }

  public function getFilters() {
    return array(
      'link' => new Twig_Filter_Method( $this, 'linkFilter', array('is_safe' => array('html')))
    );
  }

  // Usage: {{ "We're Hiring"|link(quick.entry('vacancies').url) }}
  // Todo: If a link doesn't exist, don't create an <a> link

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
