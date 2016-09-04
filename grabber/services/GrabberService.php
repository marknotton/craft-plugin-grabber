<?php
namespace Craft;

class GrabberService extends BaseApplicationComponent {

  public function plugin($pluginHandle) {
    return craft()->grabber_plugin->plugin($pluginHandle);
  }

  public function content($field = 'body', $id = null, $section = null) {
    return craft()->grabber_content->content($field, $id, $section);
  }

  public function section() {
    $args = func_get_args();
    return call_user_func_array(array(craft()->grabber_section, 'section'), $args);
  }

  public function entry() {
    $args = func_get_args();
    return call_user_func_array(array(craft()->grabber_entry, 'entry'), $args);
  }

  public function page() {
    return craft()->grabber_page->page();
  }

  public function classes() {
    return craft()->grabber_classes->classes();
  }

  public function global() {
    $args = func_get_args();
    return call_user_func_array(array(craft()->grabber_global, 'global'), $args);
  }

  public function link() {
    $args = func_get_args();
    return call_user_func_array(array(craft()->grabber_link, 'link'), $args);
  }

}
