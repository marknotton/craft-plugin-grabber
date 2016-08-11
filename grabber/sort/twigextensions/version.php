<?php
namespace Craft;

use Twig_Extension;
use Twig_Filter_Method;

class version extends \Twig_Extension {

  public function getName() {
    return Craft::t('Version');
  }

  public function getFilters() {
    return array(
      'version' => new Twig_Filter_Method( $this, 'version')
    );
  }

  // Usage:  <link rel="stylesheet" href="{{ (css ~ '/main.css')|version }}" />
  // Output: <link rel="stylesheet" href="/assets/css/main.css?v=8989392029" />

  // Add a string parameter if you want to define the variable name. 'v' is used by default
  // Usage:  <link rel="stylesheet" href="{{ (css ~ '/main.css')|version('cache') }}" />
  // Output: <link rel="stylesheet" href="/assets/css/main.css?cache=8989392029" />
  public function version($filefull, $param=null) {

    // Remove all parameters from $filefull. Validate and check the url is relative or absolute and apply amendments if necessary
    $fileClean = ltrim(strtok($filefull, '?'), '/');  

    // Validate and check the url is relative or absolute and apply amendments if necessary
    $fileVal = getcwd().'/'.rtrim(ltrim(parse_url($filefull)["path"], '/'), '/');  
    
    if(file_exists($fileVal)) {

      // The second argument defines what variable name will be used within the string. This gets also slugified.
      $param = !empty((string)$param) ? ElementHelper::createSlug((string)func_get_arg(1)) : 'v';

      $version = filemtime($fileClean);

      return craft()->quick->modifyParams($filefull, $param, $version);

    } else {
      return $filefull;
    }
  }

}