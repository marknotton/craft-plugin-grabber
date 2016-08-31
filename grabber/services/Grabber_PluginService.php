<?php
namespace Craft;

class Grabber_PluginService extends BaseApplicationComponent {

  // For conditions that are dependant on other plugins,
  // perform a quick check to see if a plugin is installed and enabled
  public function plugin($pluginHandle) {
    if ($plugin = craft()->plugins->getPlugin($pluginHandle, false)) {
      return $plugin->isInstalled && $plugin->isEnabled;
    }
  }

}
