<?php namespace App\Http\Controllers;

use View;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Pagination\Paginator;
use App\Helpers\ViewHelper;
use Redirect;

use \Input;

use Illuminate\Http\Request;

class TicketsController extends Controller {

 /**
  * Show a single event
  * @param  [type] $slug [description]
  * @return [type]       [description]
  */
  public function showCreate($id)
  {

  	// Instantiate api helper
		$api = new \App\Helpers\ApiHelper;

    $url = 'events/' . $id . '?with[]=ticketsInventory';

    if (\Auth::check()) {
      $url .= '&auth_user_id=' . \Auth::user()->id;
    }

		$events = $api->index($url);

    if (empty($events['data']['event'])) {
      abort(404);
    } else {

      if (\Auth::user()->id != $events['data']['event']['userId']) {
        abort(401);
      }
      // Grabs event id with tickets
      $events = $events['data']['event']['id'];
    }

		$updateErrors = \Session::get('updateErrors');

  	return \View::make('accounts.dashboard.tickets.create')->with([
  		'updateErrors' => $updateErrors,
    	'events' => $events,
    ]);

  }

	/**
 	 * Add tickets
   * @return Response
   */
  public function doCreate()
  {

  	// Request ticket data
		$data = \Request::all();

		\Input::flash();

		// Instantiate api helper
		$api = new \App\Helpers\ApiHelper;

		// Isset placed for users who have new reservation and do not send date
    if (isset($data['startsAt']) && isset($data['endsAt'])) {
			$data['startsAt'] = date('Y-m-d', strtotime($data['startsAt']));
	  	$data['endsAt'] = date('Y-m-d', strtotime($data['endsAt']));
	  }

     $url = 'ticketsinventories';

    if (\Auth::check()) {
      $url .= '?auth_user_id=' . \Auth::user()->id;
    }

		// Grabs id of event and posts new ticket
		$ticket = $api->store($url, $data);

		$eventId = $data['eventId'];

		// Json encode the response data
		$data = json_encode([
			'enabled' => (bool) $data,
		]);

		// If success is returned true show the success message
    if ( ! isset($ticket['success'])) {

			foreach ($ticket['error'] as $key => $error) {
        for ($i=0; $i < count($ticket['error']); $i++) {
          if (isset($error[$i])) {
            $errors[] = $error[$i];
          }
        }
      }

      return Redirect::route('tickets.create', $eventId)
        ->with(['updateErrors' => $errors])
        ->withInput();
		}

      // Encodes message to get sent with url
      $message = urlencode('Please wait while your tickets are being added');

        // return Route::get('pages.loader', 302)->header('Location', (string)$url);
       $url = "time=4000&url=/account/tickets/index/" . $eventId . "&message=" . $message;

       return Redirect::to(route('pages.loader', (string)$url));

	}

	/**
	 * Show the edit for tickets
	 * @param  int $id
	 * @return view     edit view
	 */
	public function showEdit($id)
	{

		// Instantiate api helper
		$api = new \App\Helpers\ApiHelper;

		$ticket = $api->show('ticketsinventories', $id);

		$ticket = $ticket['data']['tickets'];

		return View::make('accounts.dashboard.tickets.edit')->with([
			'ticket' => $ticket,
		]);
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function doEdit($id)
	{

		// Request the edit data
		$data = \Request::all();

		$eventId = $data['eventId'];

		Input::flash();

		// Instantiate api helper
		$api = new \App\Helpers\ApiHelper;

		// Submit the data to update
		$ticket = $api->update('ticketsinventories', $id, $data);

		// Json encode the returned data
		$data = json_encode([
			'enabled' => (bool) $data,
		]);

		// If success is returned true show the success message, if not, redirect back with errors
    if ( ! isset($ticket['success']))
		{

			return \Redirect::back()->with('fail_message', 'Ticket has errors')
				->with('name', isset($ticket['error']['name'][0]) ? $ticket['error']['name'][0] : null)
				->with('amount', isset($ticket['error']['amount'][0]) ? $ticket['error']['amount'][0] : null)
				->with('inventory', isset($ticket['error']['inventory'][0]) ? $ticket['error']['inventory'][0] : null)
				->withInput();

		}

    // Redirect message will show based on if saved item is ticket or reservation
    if ($ticket['data']['inventory']['is_reservation'] === true) {

      // Encodes message to get sent with url
      $message = urlencode('Please wait while your reservation is being updated');

    } else {

      // Encodes message to get sent with url
      $message = urlencode('Please wait while your ticket is being updated');

    }

    // return Route::get('pages.loader', 302)->header('Location', (string)$url);
    $url = "time=4000&url=/account/tickets/index/" . $eventId . "&message=" . $message;

    return Redirect::to(route('pages.loader', (string)$url));

	}

	/**
	 * Shows index of all user events with options to edit or view
	 *
	 * @return Response
	 */
	public function dashboard()
	{
		// Sets initial page number for pagination
		$page = \Input::get('page', 1);

		// Limit to twenty events per page
		$limit = 20;

		// Makes page content correctly display next events in line
		$offset = ($page - 1) * $limit;

		// Instantiate api helper
		$api = new \App\Helpers\ApiHelper;

		$events = $api->index('events?filter[and][][user_id]=' . \Auth::user()->id . '&limit=' . $limit . '&offset=' . $offset . '&sort[desc][]=created_at&&with[]=schedules&with[]=user&with[]=photos&with[]=times&filter[and][][is_banned]=0&with[]=TicketsInventory&with[]=attendees&auth_user_id=' . \Auth::user()->id);

		$events = isset($events['data']['events']) ? $events['data']['events'] : [];

		// Paginator sets events as items, number of events to null (already defined), and path is
		// set so that the browser uses current route
	 	$events = new Paginator($events, null, Paginator::resolveCurrentPage(), [
		    'path' => Paginator::resolveCurrentPath()
		]);

	 	// Check if user is signed in
    if (\Auth::check()) {

			$user = $api->show('users', \Auth::user()->id . '?with[]=ticketInventories&with[]=stripeManagedAccounts&auth_user_id=' . \Auth::user()->id);
			$user = $user['data']['user'];

		}

		return View::make('accounts.dashboard.dashboard')->with([
			'events' => $events,
			'user' => isset($user) ? $user : null,
		]);

	}

	/**
	 * Shows tickets inventory index page
	 *
	 * @param  string  $slug
	 * @return Response
	 */
	public function showIndex($slug)
	{
		// Instantiate api helper
		$api = new \App\Helpers\ApiHelper;

    $url = 'events/' . $slug . '?with[]=ticketsInventory';

    if (\Auth::check()) {
      $url .= '&auth_user_id=' . \Auth::user()->id;
    }

		// Grabs information needed to pull in tickets for event
		$events = $api->index($url);

    if (empty($events['data']['event'])) {
      abort(404);
    } else {
      // Define events as variable to hold the events
      $event = $events['data']['event'];
    }

    if (\Auth::user()->id != $event['userId']) {
      abort(401);
    }

		// Check if Auth user has managed account
		$managed = $api->show('users', \Auth::user()->id . '?with[]=stripeManagedAccounts&auth_user_id=' . \Auth::user()->id);
		$managed = isset($managed['data']['user']['stripeManagedAccounts'][0]) ? $managed['data']['user']['stripeManagedAccounts'][0] : [];

		// Return the tickets index view
		return View::make('accounts.dashboard.tickets.index')->with([
			'event' => $event,
			'managed' => $managed,
		]);

	}

}
