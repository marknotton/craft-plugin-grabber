<?php
namespace Craft;

class GrabberService extends BaseApplicationComponent {


    public $status = null;
    private $cacheSection = [];
    private $errors = [
      '400'=>'Bad Request',
      '403'=>'Forbidden',
      '404'=>'Page Not Found',
      '500'=>'Internal Server Error',
      '503'=>'Maintenance'
    ];

    public function setStatus() {
      $this->status = http_response_code();
    }

      // Return global data
    public function getGlobal($handle, $set = "settings") {
      if ( isset(craft()->globals->getSetByHandle($set)->$handle)) {
        return craft()->globals->getSetByHandle($set)->$handle;
      }
    }

  public function plugin() {
    return craft()->grabber_plugin(func_get_args());
  }

  public function content() {
    return craft()->grabber_content(func_get_args());
  }

  public function section() {
    return craft()->grabber_section(func_get_args());
  }

  public function status() {
    return craft()->grabber_status(func_get_args());
  }

  public function entry() {
    return craft()->grabber_entry(func_get_args());
  }

  public function page() {
    return craft()->grabber_page(func_get_args());
  }

  public function title() {
    return craft()->grabber_title(func_get_args());
  }

  public function classes() {
    return craft()->grabber_classes(func_get_args());
  }

  public function global() {
    return craft()->grabber_global(func_get_args());
  }

  public function link() {
    return craft()->grabber_link(func_get_args());
  }





}
