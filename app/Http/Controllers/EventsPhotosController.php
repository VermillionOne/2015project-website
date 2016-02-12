<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Pagination\Paginator;
use Illuminate\Http\Request;

class EventsPhotosController extends Controller {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		//
	}

	// /**
	//  * Display the specified resource.
	//  *
	//  * @param  int  $id
	//  * @return Response
	//  */
	// public function show($slug)
	// {
	// 	// Insanciate the APIHelper
	//   $api = new \App\Helpers\ApiHelper;

 //    $url = 'events/' . $slug . '?with[]=photos&with[]=reviews.user&with[]=times&with[]=comments.user&with[]=user';

 //    if (\Auth::check()) {
 //      $url = '&auth_user_id=' . \Auth::user()->id;
 //    }

 //    $events = $api->show($url, '');

 //    if (isset($events['data']['event'])) {
 //    	$events = $events['data']['event'];
 //    }else{
 //    	echo 'There is no event Available';
 //    	// exit;
 //    }

	// 	return \View::make('events.photos')->with(array(
	// 		'events' => $events,
	// 	));
	// }

  public function showEdit($slug)
  {
    // Insanciate the APIHelper
    $api = new \App\Helpers\ApiHelper;

    $url = 'events/' . $slug . '?with[]=photos&with[]=reviews.user&with[]=times&with[]=comments.user&with[]=user';

    if (\Auth::check()) {
      $url .= '&auth_user_id=' . \Auth::user()->id;
    }

    $events = $api->index($url);

    if (isset($events['data']['event'])) {
      $events = $events['data']['event'];

      // Sets admin check to false
      $isAdmin = false;

      //Gets auth id of user
      $user = \Auth::user();
      $id = $user->id;

      $eventId = $events['id'];

      $isAdmin = $events['userId'] == \Auth::user()->id ? true : $isAdmin;

      // Sets initial page number for pagination
      $page = \Input::get('page', 1);

      // Limit to twenty photos per page
      $limit = 20;

      // Makes page content correctly display next photos in line
      $offset = ($page - 1) * $limit;

      // For now making two calls to pull in published and unpublished event photos until further notice
      $photoPublishedUrl = 'events/' . $eventId . '/photos?filter[and][][is_published]=1&limit=' . $limit . '&offset=' . $offset;
      $photoUnpublishedUrl = 'events/' . $eventId . '/photos?filter[and][][is_published]=0&limit=' . $limit . '&offset=' . $offset;

      // If auth check, append id
      if (\Auth::check()) {
        $photoPublishedUrl .= '&auth_user_id=' . \Auth::user()->id;
        $photoUnpublishedUrl .= '&auth_user_id=' . \Auth::user()->id;
      }

      // Grabs photos
      $photoPublished = $api->index($photoPublishedUrl);
      $photoUnpublished = $api->index($photoUnpublishedUrl);

      // Sets photos
      $photoPublished = isset($photoPublished['data']['resource']) ? $photoPublished['data']['resource'] : [];
      $photoUnpublished = isset($photoUnpublished['data']['resource']) ? $photoUnpublished['data']['resource'] : [];

      // Merges both arrays to display all published and unpublised photos
      $photo = array_merge($photoPublished, $photoUnpublished);

      // Paginator sets photo as items, number of photo to null (already defined), and path is
      // set so that the browser uses current route
      $photo = new Paginator($photo, null, Paginator::resolveCurrentPage(), [
        'path' => Paginator::resolveCurrentPath()
      ]);


    }else{
      echo 'There is no event Available';
      // exit;
    }

    return \View::make('events.gallery')->with(array(
      'events' => $events,
      'id' => isset($id) ? $id : null,
      'isAdmin' => $isAdmin,
      'photo' => $photo
    ));
  }

	public function edit($slug)
	{
		// Insanciate the APIHelper
	  $api = new \App\Helpers\ApiHelper;


      $url = 'events/' . $slug . '?with[]=photos&with[]=reviews.user&with[]=times&with[]=comments.user&with[]=user';

      if (\Auth::check()) {
        $url .= '&auth_user_id=' . \Auth::user()->id;
      }

    $events = $api->index($url);

    if (isset($events['data']['event'])) {
    	$events = $events['data']['event'];
    }else{
    	echo 'There is no event Available';
    	// exit;
    }

		return \View::make('events.gallery')->with(array(
			'events' => $events,
		));
	}

  public function doFeatured($event, $id, $data = null)
  {
    // $data = null;

    $api = new \App\Helpers\ApiHelper;

    $url = 'events/' . $event . '/photos/' . $id;

    if (\Auth::check()) {
      $url .= '?auth_user_id=' . \Auth::user()->id;
    }

    $featured = $api->update($url, '', $data);

    return \Redirect::back();
  }

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($event, $id)
	{
		$api = new \App\Helpers\ApiHelper;

    $url = 'events/' . $event . '/photos/' . $id;

    if (\Auth::check()) {
      $url .= '?auth_user_id=' . \Auth::user()->id;
    }

		$photos = $api->destroy($url, '');

    if ($photos['success'] === true) {
      return \Redirect::back()->with(
        'success_message','Image successfully deleted!
      ');
    }else{
      return \Redirect::back()->with(
        'success_message','Image not found, must be deleted!
      ');
    }

	}

}
