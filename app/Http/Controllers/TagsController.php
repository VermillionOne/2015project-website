<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

class TagsController extends Controller {

  /**
   * [show description]
   * @param  int    $id  ID from URL
   * @return array       array of tags to the view
   */
	// public function show($tag)
	// {
	// 	// Insanciate the APIHelper
	//   $api = new \App\Helpers\ApiHelper;

	//   // Show tag associated the ID
	// 	$tags = $api->show('events/tag', $tag);

 //    // Define events for tag
 //    $events = $tags['data']['events'];

	// 	return \View::make('tags.show', ['tags' => $events, 'tagName' => $tag]);
	// }

}
