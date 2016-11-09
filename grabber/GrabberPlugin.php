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

  public function registerSiteRoutes() {
    return array(
      'api/everything' => array('action' => 'grabber/everything'),
    );
  }

  public function getSettingsHtml() {
    if (!craft()->plugins->getPlugin('settings')) {
      return craft()->templates->render('grabber/settings', array(
        'settings' => $this->getSettings()
      ));
    } else {
      return false;
    }
  }

  protected function defineSettings() {
    return array(
      'envVarsToGlobalVars' => array(AttributeType::Bool, 'default' => true),
    );
  }

  public function addTwigExtension() {
    if (!craft()->isConsole() && craft()->request->isSiteRequest() && craft()->plugins->getPlugin('settings')) {
      craft()->settings->addGlobals($this->getGlobals(), 'grabber');
    } else {
      Craft::import('plugins.grabber.twigextensions.Grabber_Globals');
      return new Grabber_Globals();
    }

    Craft::import('plugins.grabber.twigextensions.link');
    return new link();
  }

  public $title;

  public function getGlobals() {
    return array(
      'grab'  => craft()->grabber,
      'title' => $this->title
    );
  }

  public function init() {
    if (!craft()->isConsole() && !craft()->request->isCpRequest())  {

      $this->title = craft()->grabber_entry->entry(null, null, false)['title'];

      craft()->templates->hook('grabber', function(&$context) {
        if (isset($context['classes'])) {
          craft()->grabber_classes->extraClasses = $context['classes'];
        }
        if (isset($context['title'])) {
          $this->title = $context['title'];
        }
      });
    }
  }
};
