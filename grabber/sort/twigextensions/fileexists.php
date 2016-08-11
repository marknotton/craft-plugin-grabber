<?php
namespace Craft;

use Twig_Extension;
use Twig_Filter_Method;

class fileexists extends \Twig_Extension {

  public function getName() {
    return Craft::t('File Exists');
  }

  public function getFilters() {
    return array(
      'fileexists' => new Twig_Filter_Method( $this, 'fileexists')
    );
  }

  // When check for a files existance, just use this fill. If it doesn't exist, false is return. Otherwise return the url
  // {{ (images ~ '/logo.jpg')|fileexists }}
  public function fileexists() {

    // Atleast one symbol sting arugment should be passed
    if ( func_num_args() < 1 ){
      return false;
    }

    // The first argument is the file that is automatically passed.
    $file = func_get_arg(0);

    if (gettype($file) == 'string') {
      // Remove the first slash if it exists
      $file = ltrim($file, '/');

      // http://stackoverflow.com/a/2762083/843131
      if (preg_match("~^(?:f|ht)tps?://~i", $file)) {
        // Absolute URL
        return file_exists($file) ? $file : false;
      } else {
        // Relative URL
        return file_exists(getcwd().'/'.$file) ? $file : false;
      }
    }
  }

}
