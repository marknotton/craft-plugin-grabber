<?php
namespace Craft;

class IdentifyPlugin extends BasePlugin {
  public function getName() {
    return Craft::t('Identify');
  }

  public function getVersion() {
    return '1.0';
  }

  public function getDeveloper() {
    return 'Yello Studio';
  }

  public function getDeveloperUrl() {
    return 'http://yellostudio.co.uk';
  }

  public function onBeforeInstall() {
    $query = craft()->db->createCommand()->addColumnAfter('sections', 'association', ColumnType::Varchar, 'enableVersioning');
  }

  public function onBeforeUninstall() {
    $query = craft()->db->createCommand()->dropColumn('sections', 'association');
  }

  // NOTE: This plugin is dependant on QUICK

  public function init() {
    if (!craft()->isConsole() && !craft()->request->isCpRequest())  {

      $videos = isset(craft()->config->get('environmentVariables')["videos"]) ? craft()->config->get('environmentVariables')["videos"] : null;
      $images = isset(craft()->config->get('environmentVariables')["images"]) ? craft()->config->get('environmentVariables')["images"] : null;
      $js     = isset(craft()->config->get('environmentVariables')["js"]) ? craft()->config->get('environmentVariables')["js"] : null;
      $css    = isset(craft()->config->get('environmentVariables')["css"]) ? craft()->config->get('environmentVariables')["css"] : null;

      craft()->urlManager->setRouteVariables(
        array(
          'identify' => craft()->identify,
          'title'    => craft()->identify->title(),
          'images'   => isset($images) ? $images : "/assets/images",
          'videos'   => isset($images) ? $images : "/assets/videos",
          'js'       => isset($js) ? $js : "/assets/js",
          'css'      => isset($css) ? $css : "/assets/css"
        )
      );

      craft()->templates->hook('settings', function(&$context) {
        craft()->quick->setStatus();
        $routeParams = craft()->urlManager->getRouteParams();
        if (isset($routeParams['variables'])) {
          $context = array_merge($context, $routeParams['variables']);

          if (isset($context['classes'])) {
            craft()->identify->extraClasses = $context['classes'];
          }
        }
      });

    } else {
      $segments = craft()->request->getSegments();
      if (count($segments) == 3 && $segments[1] == 'sections') {
        // print_r(craft()->templates->render('identify/association'));

        craft()->on('sections.onBeforeSaveSection', function($event) {
          // TODO: Save association information to database
        });

      }


    }
  }
}
