<?php
namespace Craft;
class QuickVariable {

  public function plugin($name) { 
    return craft()->quick->plugin($name); 
  }

  public function entry($id = null, $section = null) { 
    return craft()->quick->entry($id, $section); 
  }

}

