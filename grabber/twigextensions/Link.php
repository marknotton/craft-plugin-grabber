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
      'link' => new Twig_Filter_Method( $this, 'link', array('is_safe' => array('html')))
    );
  }

  public function link() {
    $args = func_get_args();
    return call_user_func_array(array(craft()->grabber_link, 'link'), $args);
  }
}
