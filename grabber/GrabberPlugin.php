<?php
namespace Craft;

class GrabberPlugin extends BasePlugin {
  public function getName() {
    return Craft::t('Grabber');
  }

  public function getVersion() {
    return '0.1';
  }

  public function getSchemaVersion() {
    return '0.1';
  }

  public function getDescription() {
    return 'Grab useful data and entry content from anywhere.';
  }

  public function getDeveloper() {
    return 'Yello Studio';
  }

  public function getDeveloperUrl() {
    return 'http://yellostudio.co.uk';
  }

  public function getDocumentationUrl() {
    return 'https://github.com/marknotton/craft-plugin-grabber';
  }

  public function getReleaseFeedUrl() {
    return 'https://raw.githubusercontent.com/marknotton/craft-plugin-grabber/master/grabber/releases.json';
  }

  public function init() {
    if (!craft()->isConsole() && !craft()->request->isCpRequest())  {

      $videos = isset(craft()->config->get('environmentVariables')["videos"]) ? craft()->config->get('environmentVariables')["videos"] : null;
      $images = isset(craft()->config->get('environmentVariables')["images"]) ? craft()->config->get('environmentVariables')["images"] : null;
      $js     = isset(craft()->config->get('environmentVariables')["js"])     ? craft()->config->get('environmentVariables')["js"]     : null;
      $css    = isset(craft()->config->get('environmentVariables')["css"])    ? craft()->config->get('environmentVariables')["css"]    : null;

      craft()->urlManager->setRouteVariables(
        array(
          'grab'   => craft()->grabber,
          'title'  => craft()->grabber->title(),
          'images' => isset($images) ? $images : "/assets/images",
          'videos' => isset($images) ? $images : "/assets/videos",
          'js'     => isset($js) ? $js : "/assets/js",
          'css'    => isset($css) ? $css : "/assets/css"
        )
      );

      craft()->templates->hook('settings', function(&$context) {
        // craft()->quick->setStatus();
        $routeParams = craft()->urlManager->getRouteParams();
        if (isset($routeParams['variables'])) {
          $context = array_merge($context, $routeParams['variables']);

          //if (isset($context['classes'])) {
            //craft()->grabber->extraClasses = $context['classes'];
          //}
        }
      });

    }
  }
}
