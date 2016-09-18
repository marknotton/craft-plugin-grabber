<?php
namespace Craft;

class Grabber_TitleService extends BaseApplicationComponent {

  public function title($title = null, $seperator = "|", $sitename = null, $order = true) {

    // Titles shuold not excede 75 characters in total.
    $titleLengthLimit = 75;

    $mb_ok = function_exists('mb_get_info');
    $charset = craft()->templates->getTwig()->getCharset();

    $sitename = is_null($sitename) ? craft()->getSiteName() : $sitename;
    $sitenameLength = mb_strlen($sitename);

    $seperator = ' '.$seperator.' ';
    $seperatorLength = mb_strlen($seperator);

    $title = is_null($title) ? craft()->grabber_entry->entry(null, null, false)['title'] : $title;

    if ( $title == 'home' || $title == 'homepage' || empty($title) ) {
      $title = null;
      $seperator = null;
    }

    $titleLength = mb_strlen($title);

    if (($titleLength + $seperatorLength + $sitenameLength) >= $titleLengthLimit) {
      $limit = $titleLengthLimit - $seperatorLength - $sitenameLength;
      $title = ($mb_ok) ? mb_substr($title, 0, $limit, $charset) : substr($title, 0, $limit);
    }

    $fullTitle = $order ? $title.$seperator.$sitename : $sitename.$seperator.$title;

    return $fullTitle;
  }

}
