<?php namespace App\Http\Controllers;

use View;
use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

class EventRatingsController extends Controller {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		//
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		//
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store()
	{
		//
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		//
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function doReviews($id)
  	{

	    // Instanciate the APIHelper
	    $api = new \App\Helpers\ApiHelper;

	    $data = \Request::all();

    	$data['userId'] = \Auth::user()->id;

	    // Store the review in the database
	    $reviews = $api->store('reviews?auth_user_id=' . \Auth::user()->id, $data);

	    //$ratings = $api->store('ratings' $data);

	    /* if ($reviews) {
	      return Redirect::to(\URL::previous() . "#reviews");
	    } */
	   	return \Redirect::back()->with('message', 'Posted!');

	}
	public function doRatings()
  	{

	    // Instanciate the APIHelper
	    $api = new \App\Helpers\ApiHelper;

	    $data = \Request::all();

    	$data['userId'] = \Auth::user()->id;

	    // Store the review in the database
	    $reviews = $api->store('reviews?auth_user_id=' . \Auth::user()->id, $data);

	    //$ratings = $api->store('ratings' $data);

	    /* if ($reviews) {
	      return Redirect::to(\URL::previous() . "#reviews");
	    } */
	   	return \Redirect::back();

	}


	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{
		//
	}

	public function editTables($id)
	{
		$data = \Request::all();

		$api = new \App\Helpers\ApiHelper;
		$events = $api->update('events', $id, $data);
		$events = $events['data']['event'];

		return View::make('accounts.dashboard.tickets.edit.edit')->with([
			'events' => $events,
		]);
	}

	public function show($slug)
	{
		$api = new \App\Helpers\ApiHelper;

		//Checking for user auth
		$user = \Auth::user();

		$url = 'events/' . $slug . '?with[]=reviews';

		if (\Auth::check()) {
			$url .= '&auth_user_id=' . $user;
		}

		$events = $api->show($url, '');

		$events = $events['data']['event']['reviews'];

		//If statement to determine whether or snot user is logged in to post
		if (\Auth::check()) {

			// Getting user id
			$id = $user->id;
			// Insanciate the APIHelper
			$api = new \App\Helpers\ApiHelper;

			// grab user information
			$eventsAttending = $api->index('events/' . $id . '?with[]=user&with[]=photos&auth_user_id=' . \Auth::user()->id);

			$eventsAttending = $eventsAttending['data'];

			// User must be logged in to post review
	    } else {
			return \Redirect::guest(\URL::action('AccountsController@showLogin'));
	    }

	    return \View::make('events.event-ratings')->with([
			'eventsAttending' => $eventsAttending,
			'slug' => $slug,
			'events' => $events
		]);
	}


}
