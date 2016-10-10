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

  public function getSettingsHtml() {
    return craft()->templates->render('grabber/settings', array(
      'settings' => $this->getSettings()
    ));
  }

  protected function defineSettings() {
    return array(
      'globalVariablesName' => array(AttributeType::String, 'default' => ''),
    );
  }

  public function addTwigExtension() {
    Craft::import('plugins.grabber.twigextensions.link');
    Craft::import('plugins.grabber.twigextensions.Grabber_Globals');
    return array(
      new link(),
      new Grabber_Globals()
    );
  }

  public function init() {

    if (!craft()->isConsole() && !craft()->request->isCpRequest())  {
      craft()->templates->hook('grabber', function(&$context) {
        if (isset($context['classes'])) {
          craft()->grabber_classes->extraClasses = $context['classes'];
        }
      });

      // $var = 'something';
      // $twig = craft()->templates->getTwig(null, ['safe_mode' => false]);
      // $twig->addGlobal('var', $var);


    }
  }
};
