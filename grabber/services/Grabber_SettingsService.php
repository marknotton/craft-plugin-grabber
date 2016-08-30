<?php
namespace Craft;

class Grabber_SettingsService extends BaseApplicationComponent {

  public $settings = null;
  public $defaultSettings = array(
    'theme'    => 'green',
    'transforms' => [
      'background' => [ 'mode'=>'crop', 'width'=>1080, 'quality'=>80, 'position'=>'center-center'],
      'thumb'      => [ 'mode'=>'crop', 'width'=>300, 'height'=>300,  'quality'=>80, 'position'=>'center-center'],
      'small'      => [ 'mode'=>'fit',  'width'=>100, 'height'=>100,  'quality'=>70, 'position'=>'center-center'],
      'mobile'     => [ 'mode'=>'crop', 'width'=>640, 'height'=>1136, 'quality'=>60, 'position'=>'center-center']
    ],
    'globals' => [
      'telephone'  => '0123 456 7890',
      'address'    => '42 Wallaby Way, Sydney'
    ],
    'social' => [
      'facebook'   => 'http://www.facebook.com',
      'twitter'    => 'http://www.twitter.com',
      'linkedin'   => 'http://www.linkedin.com',
      'youtube'    => 'http://www.youtube.com'
    ]
  );

  public function settings() {
    // If the settings are defined, add/override these to the newSettings.
    // TODO: Hooks are unable to update the '$this->settings' before this function is called.
    // So for the time being, you can't add additional settings directly into layout templates; only the defaultSettings from this file.

    if (is_array($this->settings)) {
      return $this->_array_merge_recursive_distinct($this->settings, $this->defaultSettings);
    } else {
      return $this->_flatten($this->defaultSettings);
    }

  }

  // http://php.net/manual/en/function.array-merge-recursive.php#92195
  private function _array_merge_recursive_distinct ( array &$array1, array &$array2 ) {
    $merged = $array1;

    foreach ( $array2 as $key => &$value ) {
      if ( is_array ( $value ) && isset ( $merged [$key] ) && is_array ( $merged [$key] ) ) {
        $merged [$key] = $this->_array_merge_recursive_distinct ( $merged [$key], $value );
      } else {
        $merged [$key] = $value;
      }
    }
    return $merged;
  }

  // This will reformat an arrray so all first level arrays will have it's childen brought forward one level.
  // Before:
  // array(
  //   'one' => '1',
  //   'two' => [ 'foo' => 'bar', 'ping' => ['nested' => 'array']],
  //   'three' => '3'
  // )
  // After:
  // array(
  //   'one' => '1',
  //   'foo' => 'bar',
  //   'ping' => ['nested' => 'array'],
  //   'three' => '3'
  // )
  private function _flatten($settings) {
    $newSettings = [];

    if (is_array($settings)) {
      foreach ($settings as $key => $value) {
        if (is_array($value)) {
          foreach ($settings[$key] as $k => $v) {
            $newSettings[$k] = $v;
          }
        } else {
          $newSettings[$key] = $value;
        }
      }
    }

    return !empty($newSettings) ? $newSettings : $settings;
  }

}
