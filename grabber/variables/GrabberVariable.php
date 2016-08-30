<?php
namespace Craft;
class GrabberVariable {

  public function plugin() {
    return craft()->grabber->plugin(func_get_args());
  }

  public function content() {
    return craft()->grabber->content(func_get_args());
  }

  public function section() {
    return craft()->grabber->section(func_get_args());
  }

  public function status() {
    return craft()->grabber->status(func_get_args());
  }

  public function entry() {
    return craft()->grabber->entry(func_get_args());
  }

  public function page() {
    return craft()->grabber->page(func_get_args());
  }

  public function title() {
    return craft()->grabber->title(func_get_args());
  }

  public function global() {
    return craft()->grabber->global(func_get_args());
  }

  public function link() {
    return craft()->grabber->link(func_get_args());
  }

}
