<?php
namespace Craft;

class QuickPlugin extends BasePlugin {
  public function getName() {
    return Craft::t('Quick');
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

  public function addTwigExtension() {
    Craft::import('plugins.quick.twigextensions.link');
    Craft::import('plugins.quick.twigextensions.checkfields');
    Craft::import('plugins.quick.twigextensions.fileexists');
    Craft::import('plugins.quick.twigextensions.version');
    Craft::import('plugins.quick.twigextensions.slugify');
    Craft::import('plugins.quick.twigextensions.size');
    return array(
      new link(),
      new checkfields(),
      new fileexists(),
      new version(),
      new slugify()
    );
  }

  public function init() {
    if (!craft()->isConsole() && !craft()->request->isCpRequest())  {
      craft()->urlManager->setRouteVariables(
        array(
          'quick' => craft()->quick,
        )
      );
    }
  }
}
