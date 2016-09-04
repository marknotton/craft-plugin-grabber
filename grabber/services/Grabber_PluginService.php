<?php
namespace Craft;

class Grabber_PluginService extends BaseApplicationComponent {

  public function plugin($pluginHandle, $enabled = true) {
    if ($plugin = craft()->plugins->getPlugin($pluginHandle, false)) {
      if ($enabled) {
        return $plugin->isInstalled && $plugin->isEnabled;
      } else {
        return $plugin->isInstalled;
      }
    }
  }

}
