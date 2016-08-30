<?php
namespace Craft;

use Twig_Extension;

class setGlobalVariables extends \Twig_Extension {

  public function getName() {
    return Craft::t('Set Global Variables');
  }

  public function getGlobals() {
    $globals = array(
      'grab'     => craft()->grabber,
      'videos'   => isset(craft()->config->get('environmentVariables')["videos"]) ? craft()->config->get('environmentVariables')["videos"] : "/assets/images",
      'images'   => isset(craft()->config->get('environmentVariables')["images"]) ? craft()->config->get('environmentVariables')["images"] : "/assets/videos",
      'js'       => isset(craft()->config->get('environmentVariables')["js"])     ? craft()->config->get('environmentVariables')["js"]     : "/assets/js",
      'css'      => isset(craft()->config->get('environmentVariables')["css"])    ? craft()->config->get('environmentVariables')["css"]    : "/assets/css",
      // 'settings' => craft()->grabber_settings->settings()
    );
    // $settings = $globals['settings'];

    $globals = !empty($settings) && !is_null($settings) ? array_merge($globals, $settings) : $globals;

    return $globals;
  }
}
