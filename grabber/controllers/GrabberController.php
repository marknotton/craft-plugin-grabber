<?php

namespace Craft;

class GrabberController extends BaseController {

    protected $allowAnonymous = array('actionEverything');

    public function actionEverything() {

      // $criteria = craft()->elements->getCriteria(ElementType::Entry);
      // $studyPathways = $criteria->find();

      $entries = ['test'=>'test'];
      $categories = [];
      $globals = [];

      // foreach($studyPathways as $studyPathway){
      //
      //   $entry = craft()->entries->getEntryById($studyPathway['id']);
      //
      //   $qualifications = $entry['qualifications']->with('content')->find();
      //   foreach($qualifications as $key => $qualification) {
      //     $qualifications[$key] = array_merge(
      //       $qualification->getAttributes(array('slug','uri','level')),
      //       $qualification->getContent()->getAttributes(array('title'))
      //     );
      //   }
      //
      //   // $entryObj = array(
      //   //   "title"             => str_replace("(", "<br/> (", $entry['title']),
      //   //   "url"               => $entry['url'],
      //   //   "snippet"           => craft()->snip_snippet->snippet($entry, 30),
      //   //   "image"             => $entry['featured'][0]['url'] ? $entry['featured'][0]->getUrl($transform) : null,
      //   //   "theme"             => $entry['themeColour'] ? $entry['themeColour'] : null,
      //   //   "qualifications"    => $qualifications
      //   // );
      //
      //   array_push($entries, $entryObj);
      //
      // }

      $this->returnJson(
        array(
          'success'   => true,
          'entries'  => $entries,
          'categories'  => $categories,
          'globals' => $globals,
        )
      );
    }
}
