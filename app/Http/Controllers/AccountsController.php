<?php namespace App\Http\Controllers;

use Auth;
use Socialize;
use Validator;
use View;
use Input;
use Redirect;
use App\User;
use App\StripeManagedAccount;
use App\Http\Controllers\Controller;
use Illuminate\Pagination\Paginator;
use App\Auth\ApiUserProvider;
use App\Helpers\ApiHelper;

use Request;

class AccountsController extends Controller {

	/**
	 * Define Social Providers for logging in
	 * @var null
	 */
	protected $socialProviders = [
		'twitter' => [
			'scope' => [],
		],
		'facebook' => [
			'scope' => ['email'],
		],
    'google' => [
      'scope' => ['email'],
    ],
    'instagram' => [
      'scope' => ['basic'],
    ],
	];

	/**
	 * Show the settings page
	 * @return settings page view
	 */
	public function showSettings()
	{
    $meta = [];

		// Sparkle the APIHelper
		$api = new \App\Helpers\ApiHelper;

	    $data = Request::all();

		// Check if user is logged in
		if (Auth::check()) {

			$user = Auth::user();

			$id = $user->id;

			// Instantiate the APIHelper
			$api = new \App\Helpers\ApiHelper;

      // Check if user is signed in
      if (\Auth::check()) {

  			// grab user information
  			$user = $api->show('users', $id . '?with[]=ticketInventories&with[]=stripeManagedAccounts&auth_user_id=' . \Auth::user()->id);
  			$user = $user['data']['user'];

      }

			$userDob = [
				'month' => date('m', strtotime($user['dob'])),
				'year' => date('Y', strtotime($user['dob'])),
				'days' => date('d', strtotime($user['dob']))
			];

			$user = array_merge($user, $userDob);

		}

		// Array of Months and Values for Select Menu
		$months = [
		    '01' => 'Jan',
		    '02' => 'Feb',
		    '03' => 'Mar',
		    '04' => 'Apr',
		    '05' => 'May',
		    '06' => 'Jun',
		    '07' => 'Jul',
		    '08' => 'Aug',
		    '09' => 'Sep',
		    '10' => 'Oct',
		    '11' => 'Nov',
		    '12' => 'Dec'
		];

		// Range of years between now and 100 years ago from today for Select Menu
		$years = range(date('Y') - 100, date('Y'));

		// Range of days between 1 and 31 for Select Menu
		$days = range(1,31);

		//Send array to view
		return View::make('accounts.settings')->with([
			'user' => isset($user) ? $user : null,
			'id' => $id,
			'months' => $months,
			'days' => $days,
			'years' => $years,
      'meta' => $meta
		]);
	}

  /**
   * Show the settings page
   * @return settings page view
   */
  public function showCheckIn()
  {
    // Initiate API helper
    $api = new \App\Helpers\ApiHelper;

    // Check if user is signed in
    if (\Auth::check()) {

      $user = $api->show('users', \Auth::user()->id . '?with[]=ticketInventories&with[]=stripeManagedAccounts&auth_user_id=' . \Auth::user()->id);
      $user = $user['data']['user'];

    }

    //Send array to view
    return View::make('events.check-in')->with(['user' => isset($user) ? $user : null]);

  }

  /**
   * Get response from code input
   * @return success of code input
   */
  public function showCheckInTicketCode()
  {
    // Request code data
    $data = \Request::all();

    // Define code
    $code = isset($data['code']) ? $data['code'] : null;

    // Initiate API helper
    $api = new \App\Helpers\ApiHelper;

    $url = 'tickets/code/' . $code;

    if (\Auth::check()) {
      $url .= '?auth_user_id=' . \Auth::user()->id;
    }

    // Show data from selected code
    $response = $api->index($url);

    // Redirect to appropriate
    if ($response['success'] !== true) {

      // Return with error
      return redirect()->route('events.check-in')
        ->with('fail_message', 'No Orders For This Code');
    }

    // Return with json
    return response()->json($response);

  }

  /**
   * Get response from code input
   * @return success of code input
   */
  public function doCheckInTicketCode()
  {

    $data = \Request::all();

    $usedTicket = [
      $data['ticketInventoryId'] => $data['used'],
    ];

    $ticketCode = $data['code'];

    // Initiate API helper
    $api = new \App\Helpers\ApiHelper;

    $response = $api->store('tickets/code/' . $ticketCode . '/use', $usedTicket);

    // Redirect to appropriate
    if ($response['success'] === true) {

    return response()->json($response);

    } else {

    return Redirect::back()->with('fail_message', 'No Orders For This Code');

    }

  }

  /**
   * Show the settings page
   * @return settings page view
   */
  public function showCheckInUpdate()
  {
    // Initiate API helper
    $api = new \App\Helpers\ApiHelper;

    $response = $api->show('tickets/user', \Auth::user()->id . '/codes?auth_user_id=' . \Auth::user()->id);

    // Redirect to appropriate
    if ($response['success'] !== true) {

      return Redirect::back()->with('fail_message', 'No Orders For This Code');
    }

    return response()->json($response);
  }

  /**
   * Changes users existing password in settings
   *
   * @return settings page view
   */
  public function doAccountPassword() {

    // Get all user form input
    $userFields = Input::all();

    // Set the users ID
    $id = \Auth::user()->id;

    // Get users existing password input and set the auth email
    $credentials = [
      'email' => \Auth::user()->email,
      'password'  => Input::get('current_password')
    ];

    // Initiate API helper
    $api = new \App\Helpers\ApiHelper;

    // Checks that user has entered the correct credentials
    $user = $api->store('users/verify', $credentials);

    // If user has not entered correct information, will redirect back with message
    if ($user['success'] != true) {

      return Redirect::back()->with('fail_message', 'The existing password you have entered is incorrect');

    }

    // Setup validation rules for the submitted data
    $validator = Validator::make($userFields, [

      // required, 6 chars min length, password must match password_confirmation
      'password' => 'required|min:6|confirmed'
    ]);

    if ($validator->fails()) {

      // Redirect to register page with previously submitted input and validation errors
      return Redirect::back()->withInput()->withErrors($validator->errors());

    // If validation is successful
    } else {

    // Initiate API helper
    $api = new \App\Helpers\ApiHelper;

    $user = $api->update('users', $id, $userFields);

    if ($user['success'] !== true) {

      // If $user was returned with an array of errors
      if (is_array($user)) {

        // Set a nice error that plays nice with validation errors
        foreach ($user as $inputField => $inputFieldErrors) {

          foreach ($inputFieldErrors as $error) {

            // Set api specific errors
            $validator->errors()->add('api', $error);

          }

        }

      }

    }

    return Redirect::back()->with('success_message', 'Account updated successfully.');

    }

  }


  public function doAccountSettings() {

    $data = \Request::all();

    Input::flash();

    // Set the users ID
    $id = \Auth::user()->id;

    // Initiate API helper
    $api = new \App\Helpers\ApiHelper;

    // check if birthdate fields are being edited
    if (isset($data['month'])){
      $birthMonth = $data['month'];
      $birthYear = $data['year'];
      $birthDay = $data['days'];

      // format birthdate fields
      $dob = ($birthYear.'-'.$birthMonth.'-'.$birthDay);

      //Set birthdate Field in database
      $data['dob'] = $dob;
    }

    $user = $api->update('users', $id . '?auth_user_id=' . $id, $data);

    if ($user['success'] !== true) {

      return Redirect::back()->with('fail_message', 'Something went wrong!')
        ->with('username', isset($user['error']['username'][0]) ? $user['error']['username'][0] : null)
        ->with('first_name', isset($user['error']['first_name'][0]) ? $user['error']['first_name'][0] : null)
        ->with('last_name', isset($user['error']['last_name'][0]) ? $user['error']['last_name'][0] : null)
        ->with('email', isset($user['error']['email'][0]) ? $user['error']['email'][0] : null)
        ->with('isPrivate', isset($user['error']['isprivate'][0]) ? $user['error']['isPrivate'][0] : null)
        ->withInput();

    }

    return Redirect::back()->with('success_message', 'Account updated successfully.');

  }

  public function doSecuritySettings() {

    // Save updated details to the API
    $meta = [];

    $data = \Request::all();

    Input::flash();

    // Set the users ID
    $id = \Auth::user()->id;

    // Initiate API helper
    $api = new \App\Helpers\ApiHelper;

    // security questions if data is set section
    if (isset($data['loginrequest'])) {
      $meta['securityQuestions']['loginrequest'] = (bool) $data['loginrequest'];
    }

    if (isset($data['loginverify'])) {
      $meta['securityQuestions']['loginverify'] = (bool) $data['loginverify'];
    }

    if (isset($data['personalinfo'])) {
      $meta['securityQuestions']['personalinfo'] = (bool) $data['personalinfo'];
    }

    if (isset($data['findme'])) {
      $meta['securityQuestions']['findme'] = (bool) $data['findme'];
    }

    $data['meta'] = json_encode($meta);

    $user = $api->update('users', $id . '?auth_user_id=' . $id, $data);

    if ($user['success'] !== true) {

      return Redirect::back()->with('fail_message', 'Something went wrong!');

    }

    return Redirect::back()->with('success_message', 'Account updated successfully.');

  }

  public function doBillingSettings() {

    // Save updated details to the API
    $meta = [];

    $data = \Request::all();

    Input::flash();

    // Set the users ID
    $id = \Auth::user()->id;

    // Initiate API helper
    $api = new \App\Helpers\ApiHelper;

    // billing questions if data is set section
    if (isset($data['cchfirstname'])) {
      $meta['billing']['cchfirstname'] = $data['cchfirstname'];
    }
    if (isset($data['cchlastname'])) {
      $meta['billing']['cchlastname'] = $data['cchlastname'];
    }
    if (isset($data['ccnumber'])) {
      $meta['billing']['ccnumber'] = $data['ccnumber'];
    }
    if (isset($data['ccv'])) {
      $meta['billing']['ccv'] = $data['ccv'];
    }
    if (isset($data['ccmonth'])) {
      $meta['billing']['ccmonth'] = $data['ccmonth'];
    }

    $data['meta'] = json_encode($meta);

    $user = $api->update('users', $id . '?auth_user_id=' . $id, $data);

    if ($user['success'] !== true) {

      return Redirect::back()->with('fail_message', 'Something went wrong!');

    }

    return Redirect::back()->with('success_message', 'Account updated successfully.');

  }

  public function doNotificationSettings() {

    // Save updated details to the API
    $meta = [];

    $data = \Request::all();

    Input::flash();

    // Set the users ID
    $id = \Auth::user()->id;

    // Initiate API helper
    $api = new \App\Helpers\ApiHelper;

    // notification if data is set section
    if (isset($data['wallcomments'])) {
      $meta['notifications']['wallcomments'] = (bool) $data['wallcomments'];
    }

    if (isset($data['comments'])) {
      $meta['notifications']['comments'] = (bool) $data['comments'];
    }

    if (isset($data['loginnotification'])) {
      $meta['notifications']['loginnotification'] = (bool) $data['loginnotification'];
    }

    if (isset($data['eventtimechanges'])) {
      $meta['notifications']['eventtimechanges'] = (bool) $data['eventtimechanges'];
    }

    if (isset($data['cancelledevent'])) {
      $meta['notifications']['cancelledevent'] = (bool) $data['cancelledevent'];
    }

    if (isset($data['uploadedphotos'])) {
      $meta['notifications']['uploadedphotos'] = (bool) $data['uploadedphotos'];
    }

    $data['meta'] = json_encode($meta);

    $user = $api->update('users', $id . '?auth_user_id=' . $id, $data);

    if ($user['success'] !== true) {

      return Redirect::back()->with('fail_message', 'Something went wrong!');

    }

    return Redirect::back()->with('success_message', 'Account updated successfully.');

  }

  public function doContentRestrictionSettings() {

    // Save updated details to the API
    $meta = [];

    $data = \Request::all();

    Input::flash();

    // Set the users ID
    $id = \Auth::user()->id;

    // Initiate API helper
    $api = new \App\Helpers\ApiHelper;

    // content restrictions questions if data is set section
    if (isset($data['previewcomments'])) {
      $meta['contentSettings']['previewcomments'] = (bool) $data['previewcomments'];
    }

    if (isset($data['allowusercomments'])) {
      $meta['contentSettings']['allowusercomments'] = (bool) $data['allowusercomments'];
    }

    $data['meta'] = json_encode($meta);

    $user = $api->update('users', $id . '?auth_user_id=' . $id, $data);

    if ($user['success'] !== true) {

      return Redirect::back()->with('fail_message', 'Something went wrong!');

    }

    return Redirect::back()->with('success_message', 'Account updated successfully.');

  }

	/**
	 * Show the Login Page
	 * @return login page view
	 */
	public function showLogin()
	{
		// Check if this user is already logged in
		if (Auth::check()) {

			// Redirect with error message
			return redirect()->route('home')->with('warning_message', 'Already logged in');
		}

		return View::make('accounts.login');
	}

	/**
	 * Validate users email and password
	 * @return logged in view or error.
	 */
	public function doLogin()
	{
		$slug = Input::get('slug');

    Input::flash();

		// Get supplied credentials
		$credentials = [
			'email' 	=> Input::get('email'),
			'password' 	=> Input::get('password')
		];

		if (Auth::attempt($credentials)) {

			if (isset($slug)) {

				// Redirect back to event details page of previous event
				return \Redirect::intended(route('events.show', $slug));
				// return \Redirect::route('events.show', $slug);

			} else {

				// Redirect back to home page
				return redirect()->intended(route('home'));
			}

		} else {

			// Redirect with error message
			return redirect()->back()->with('fail_message', 'Wrong credentials. Please try again.');
		}
	}

	/**
	 * Show Friends Page
	 * @return friends page view
	 */
	public function showFriends()
	{
		// Shimmer the APIHelper
		$api = new \App\Helpers\ApiHelper;

    // Show current friends list
		$friend = $api->index('users/' . \Auth::user()->id . '?with[]=friends&with[]=events&with[]=friendRequests&with[]=eventInvites&with[]=ticketInventories&with[]=stripeManagedAccounts&auth_user_id=' . \Auth::user()->id);

    $user = $friend['data']['user'];

    // Shows event requests
    $eventInvite = $api->index('events/invites/' . \Auth::user()->id . '?fields[]=event.city&auth_user_id=' . \Auth::user()->id);

    $eventInvites = isset($eventInvite['data']['resource']) ? $eventInvite['data']['resource'] : [];

    // Grabs user created events for invites
    $event = $friend['data']['user']['events'];

    // Friend requests if any exist
		$friendRequest = isset($friend['data']['user']['friendRequests']) ? $friend['data']['user']['friendRequests'] : [];

    // Shows existing friends
		$friend = isset($friend['data']['user']['friends']) ? $friend['data']['user']['friends'] : [];

    // Strip fields needed for friends list
    $friend = ApiHelper::stripFieldsCollection($friend, [
      'id',
      'email',
      'username',
      'firstName',
      'lastName',
      'avatar',
    ]);

    // Strip fields needed for user created event
    $event = ApiHelper::stripFieldsCollection($event, [
      'id',
      'title',
    ]);

    // Strip fields needed for friend request
    $friendRequest = ApiHelper::stripFieldsCollection($friendRequest, [
      'id',
      'email',
      'username',
      'firstName',
      'lastName',
      'avatar',
    ]);

		return View::make('accounts.friends')->with([
			'friend' => $friend,
      'eventInvites' => $eventInvites,
      'user' => $user,
			'event' => $event,
			'friendRequest' => $friendRequest
		]);

	}

  /**
   * Show Friends Page
   * @return friends page view
   */
  public function showUsers()
  {
    // Request all data from website
    $data = \Request::all();

    // Shimmer the APIHelper
    $api = new \App\Helpers\ApiHelper;

    $url = 'users?' . $data['query'];

    if (\Auth::check()) {
      $url .= '&auth_user_id=' . \Auth::user()->id;
    }

    // Indexes users for search results
    $response = $api->index($url);

    return response()->json($response);

  }

	/**
	 * Add friend
	 * @return friends page view
	 */
	public function doFriend($id)
	{
		$api = new \App\Helpers\ApiHelper;

		$data = [
			'friendId' => $id
		];

    $addFriend = $api->store('users/' . \Auth::user()->id . '/friends?auth_user_id=' . \Auth::user()->id, $data);

		if ($addFriend['success'] !== true){

      // Return error
      return \Redirect::route('friends')
        ->with('fail_message', $addFriend['error']);
	  }

    return \Redirect::route('friends')
      ->with('success_message', 'Friend successfully added!');
  }

	/**
	 * Create friend request
	 * @return profile page view
	 */
	public function doCreateRequest($friends, $fromRequest = null)
	{
    // Instanciate ApiHelper
		$api = new \App\Helpers\ApiHelper;

    // Create a friend request
    $request = $api->store('users/' . \Auth::user()->id . '/friends/' . $friends . '/requests?auth_user_id=' . \Auth::user()->id, []);

    // If we are coming from doRegister method using a cookie
    if ($fromRequest !== null) {
      return $request;
    }

    // Redirect to appropriate
    if ($request['success'] !== true) {

      // Return error
    	return Redirect::back()
        ->with('fail_message', $request['error']);
    }

    return Redirect::back()
        ->with('success_message', 'Request sent!!');

	}

 /**
  * Delete friend request
  * @return friends page view
  */
  public function doDeleteRequest($id)
  {
    $api = new \App\Helpers\ApiHelper;

    $deleteRequest = $api->destroy('users', \Auth::user()->id . '/friends/' . $id . '/requests/0');

    if ($deleteRequest['success'] !== true) {

      return redirect()->route('friends')
        ->with('fail_message', $deleteRequest['error']);
    }

    return \Redirect::back()
      ->with('fail_message', 'Friend is either already deleted or not found');
  }

	/**
	 * Delete friend
	 * @return friends page view
	 */
	public function doDeleteFriend($id)
	{
		$api = new \App\Helpers\ApiHelper;

    $deleteFriend = $api->destroy('users/' . \Auth::user()->id . '/friends', $id . '?auth_user_id=' . \Auth::user()->id);

    if ($deleteFriend['success'] !== true) {

    	return redirect()->route('friends')
        ->with('success_message', 'Friend is either already deleted or not found');
    }

    return \Redirect::back()
      ->with('fail_message', 'Friend Deleted');
	}

 /**
  * Updates event request sent from friend
  *
  * @param $data holds eventId and inviteId (id of request)
  */
  public function doUpdateInvites()
  {
    // Request all data from website
    $data = \Request::all();

    $eventId = $data['eventId'];

    $inviteId = $data['inviteId'];

    $api = new \App\Helpers\ApiHelper;

    // Sets confirmed_on date and removes from requests
    $updateRequest = $api->update('events', "{$eventId}/invite/{$inviteId}", []);

    if ($updateRequest['success'] === true) {

     return $updateRequest;

    } else {

      return Redirect::back()->with('error', 'Something went wrong. Please try again later.');
    }

  }

  /**
   * Sends user request to event
   *
   * @param   $data holds userId eventId requesterId
   *
   */
  public function doMyEventInvites()
  {
    // Grab invite data
    $data = Request::all();

    $id = $data['eventId'];

    // Instantiate the APIHelper
    $api = new \App\Helpers\ApiHelper;

    // Post your invite to specified event
    $friendEventInvite = $api->store('events/' . $id . '/invite?auth_user_id=' . \Auth::user()->id, $data );

    return response()->json($friendEventInvite);

  }

  /**
   * Shows details for user created event
   *
   * @param  [type] $id [description]
   * @return [type]     [description]
   */
  public function showDetails($id)
  {
    // Instantiate the APIHelper
    $api = new \App\Helpers\ApiHelper;

    $url = 'events/' . $id . '?with[]=TicketsInventory&with[]=TicketsOrder.user&with[]=tickets&with[]=attendees&with[]=times';

    if (\Auth::check()) {
      $url .= '&auth_user_id=' . \Auth::user()->id;
    }

    $event = $api->index($url);

    // If user enters information that holds now event, 404 will show
    if (empty($event['data']['event'])) {

      abort(404);

    } else {

      // Grabs event id with tickets
      $event = $event['data']['event'];

    }

    if (\Auth::user()->id != $event['userId']) {
      abort(401);
    }

    // Grabs number of attendees to event and attendees
    $reservations = $api->index('events/' . $id . '/reservations?limit=500&auth_user_id=' . \Auth::user()->id);

    $reservations = isset($reservations['data']['reservations']) ? $reservations['data']['reservations'] : [];

    // Check if user is logged in
    $appendAuthUserId = \Auth::check() ? 'auth_user_id=' . \Auth::user()->id : null;

    // Grabs number of attendees to event and attendees
    $attendees = $api->index('events/' . urlencode($event['slug']) . '?' . $appendAuthUserId . '&fields[]=slug&fields[]=attendeesCount&fields[]=attendeesAndFriends&with[]=attendeesAndFriends.user');

    $attendeeList = isset($attendees['data']['event']) ? $attendees['data']['event'] : [];

    // Set todays date for today revenue
    $today = date("Y-m-d");


    $totalInventory = [];
    $totalAmount = [];

    $totalAvailable = 0;

    $percentageUsed = 0;


    // Amount sold is based on count of tickets array
    // Every individual ticket that has been sold will have it's own record
    $totalSold = count($event['tickets']);

    // Sets array to pull used tickets
    $checkUsed = $event['tickets'];

      // Checks to see how many tickets have been used
      foreach ($checkUsed as $index => $ticket) {

        // Unset tickets with a used date
        if($ticket['usedAt'] === null) {

          unset($checkUsed[$index]);

        }

      }

    // Total used is a count of tickets that have been used
    $totalUsed = count($checkUsed);

      // For each ticket order, grabs amount paid by user
      foreach ($event['ticketsOrder'] as $value) {

        // If amount is not set, defaults to 0
        if( ! isset($totalAmount[$value['amount']]) ) {
           $totalAmount[$value['amount']] = 0;
        }

        $totalAmount[$value['amount']] += $value['amount'];

      }

    // Adds together the ticket order amounts for over all total
    $totalRevenue = array_sum($totalAmount);

      // Function to get the total inventory count for all tickets in specific event
      foreach ($event['ticketsInventory'] as $value) {

        // If inventory is not set, will default to 0
        if( ! isset($totalInventory[$value['inventory']]) )
        {
           $totalInventory[$value['inventory']] = 0;
        }

        // Puts together individual inventory amounts
        $totalInventory[$value['inventory']] += $value['inventory'];

      }

    // Gets final count of all ticket inventories
    $totalAvailable = array_sum($totalInventory);

    $initialTicketAvailable = $totalAvailable + $totalSold;

      if ($initialTicketAvailable >= 1) {

        $percentageAvailable = ($totalAvailable / $initialTicketAvailable) * 100;

      }

      if ($initialTicketAvailable >= 1) {
        $percentageSold = ($totalSold / $initialTicketAvailable) * 100;
      }

      if ($totalSold >= 1) {

        $percentageUsed =  ($totalUsed / $totalSold) * 100;

      }

    return \View::make('accounts.dashboard.events.details')->with([
    'event' => $event,
    'reservations' => $reservations,
    'totalSold' => $totalSold,
    'totalAvailable' => $totalAvailable,
    'totalUsed' => $totalUsed,
    'totalRevenue' => $totalRevenue,
    'totalAmount' => $totalAmount,
    'percentageAvailable' => isset($percentageAvailable) ? $percentageAvailable : null,
    'percentageSold' => isset($percentageSold) ? $percentageSold : null,
    'percentageUsed' => isset($percentageUsed) ? $percentageUsed : null
    ]);
  }

  /**
   * Resend Reservation confirmation
   * @param  int    $id             ID of event
   * @param  int    $reservation    ID of reservation
   * @return void
   */
  public function resendReservation($id, $reservation)
  {
    // Instantiate the APIHelper
    $api = new \App\Helpers\ApiHelper;

    // Send resend request to API
    $reservation = $api->index('events/reservations/resend/' . $reservation);

    // Redirect back with fail message if resend failed
    if ($reservation['success'] !== true) {
      return redirect()->back()->with('fail_message', 'Email resend failed');
    }

    // Return success
    return redirect()->back()->with('success_message', 'Email resent successfully');
  }

  /**
   * Shows index of event dates
   *
   * @param  [type] $id [description]
   * @return [type]     [description]
   */
  public function showScheduleIndex($id)
  {
    // Sets initial page number for pagination

    $page = \Input::get('page', 1);

    // Limit to twenty events per page
    $limit = 20;

    // Makes page content correctly display next events in line
    $offset = ($page - 1) * $limit;

    // TODO: How we upload images will be different, need that before implementing
    // Instantiate the APIHelper
    $api = new \App\Helpers\ApiHelper;

    $url = 'events/' . $id . '?with[]=schedules.times&with[]=attendees&sort[desc][]=created_at&limit=' . $limit . '&offset=' . $offset;

    if (\Auth::check()) {
      $url .= '&auth_user_id=' . \Auth::user()->id;
    }

    // Indexes dates for chosen event
    $events = $api->index($url);

    // If user enters information that holds now event, 404 will show
    if (empty($events['data']['event'])) {
      abort(404);
    } else {
      // Grabs event id with tickets
      $event = $events['data']['event'];
    }

    $event = $events['data']['event'];

    if (\Auth::user()->id != $event['userId']) {
      abort(401);
    }

    $updateErrors = \Session::get('updateErrors');

    $schedules = isset($event['schedules']) ? $event['schedules'] : [];

    $scheduleEdit = $schedules;

    $dateAttendee = isset($event['attendees']) ? $event['attendees'] : [];

    // Paginator sets events as items, number of events to null (already defined), and path is
    // set so that the browser uses current route
    $schedules = new Paginator($schedules, null, Paginator::resolveCurrentPage(), [
      'path' => Paginator::resolveCurrentPath()
    ]);

    return \View::make('accounts.dashboard.schedules.index')->with([
    'scheduleEdit' => $scheduleEdit,
    'dateAttendee' => $dateAttendee,
    'updateErrors' => $updateErrors,
    'event' => $event,
    'schedules' => $schedules
    ]);
  }

  /**
   * Shows update for existing event dates
   *
   * @param  [type] $id [description]
   * @return [type]     [description]
   */
  public function showScheduleUpdate($id, $eventSchedule)
  {
    // TODO: How we upload images will be different, need that before implementing
    // Instantiate the APIHelper
    $api = new \App\Helpers\ApiHelper;


    $url = 'events/' . $id . '?with[]=schedules.times';

    if (\Auth::check()) {
      $url .= '&auth_user_id=' . \Auth::user()->id;
    }

    $events = $api->index($url);

    if (empty($events['data']['event'])) {
      abort(404);
    }

    $events = isset($events['data']['event']) ? $events['data']['event'] : [];

    if (\Auth::user()->id != $events['userId']) {
      abort(401);
    }

    $updateErrors = \Session::get('updateErrors');

    // Grabs event schedules
    $times = isset($events['schedules']) ? $events['schedules'] : [];

    // Indexes and iterates through schedules
    foreach ($times as $index => $schedules){

      // Grabs schedule that matches the one being edited
      if ($schedules['id'] !== intval($eventSchedule)){

        // Removes schedules that do not match
        unset($times[$index]);

      }

    }

    $previousTimeInput = array_values($times);

    // If times do exists
    if (isset($previousTimeInput[0]['times'][0])) {

        $startTime = date('g:i', strtotime($previousTimeInput[0]['times'][0]['start']));

        $endTime = date('g:i', strtotime($previousTimeInput[0]['times'][0]['end']));

        $timeZonePrevious = $previousTimeInput[0]['timeZoneId'];

    }

    $timeList = [
      '12:00' => "12:00",
      '12:15' => "12:15",
      '12:30' => "12:30",
      '12:45' => "12:45",
      '1:00' => "1:00",
      '1:15' => "1:15",
      '1:30' => "1:30",
      '1:45' => "1:45",
      '2:00' => "2:00",
      '2:15' => "2:15",
      '2:30' => "2:30",
      '2:45' => "2:45",
      '3:00' => "3:00",
      '3:15' => "3:15",
      '3:30' => "3:30",
      '3:45' => "3:45",
      '4:00' => "4:00",
      '4:15' => "4:15",
      '4:30' => "4:30",
      '4:45' => "4:45",
      '5:00' => "5:00",
      '5:15' => "5:15",
      '5:30' => "5:30",
      '5:45' => "5:45",
      '6:00' => "6:00",
      '6:15' => "6:15",
      '6:30' => "6:30",
      '6:45' => "6:45",
      '7:00' => "7:00",
      '7:15' => "7:15",
      '7:30' => "7:30",
      '7:45' => "7:45",
      '8:00' => "8:00",
      '8:15' => "8:15",
      '8:30' => "8:30",
      '8:45' => "8:45",
      '9:00' => "9:00",
      '9:15' => "9:15",
      '9:30' => "9:30",
      '9:45' => "9:45",
      '10:00' => "10:00",
      '10:15' => "10:15",
      '10:30' => "10:30",
      '10:45' => "10:45",
      '11:00' => "11:00",
      '11:15' => "11:15",
      '11:30' => "11:30",
      '11:45' => "11:45"
    ];

    $repeatList = [
      'daily' => 'Daily',
      'weekly' => 'Weekly',
      'monthly' => 'Monthly',
      'yearly' => 'Yearly',
    ];

    $repeatEveryList = [
      '1' => "1",
      '2' => "2",
      '3' => "3",
      '4' => "4",
      '5' => "5",
      '6' => "6",
      '7' => "7",
      '8' => "8",
      '9' => "9",
      '10' => "10",
      '11' => "11",
      '12' => "12",
      '13' => "13",
      '14' => "14",
      '15' => "15",
      '16' => "16",
      '17' => "17",
      '18' => "18",
      '19' => "19",
      '20' => "20",
      '21' => "21",
      '22' => "22",
      '23' => "23",
      '24' => "24",
      '25' => "25",
      '26' => "26",
      '27' => "27",
      '28' => "28",
      '29' => "29",
      '30' => "30"
    ];

    // Get list of timezones
    $timezoneList = [];
    $timezones = $api->index('collections/timezones?limit=200&fields[]=id&fields[]=zoneName');
    $timezones = isset($timezones['data']['timezones']) ? $timezones['data']['timezones'] : [];

    // Create timezone list for select input
    foreach ($timezones as $timezone) {

      // Set time_zone_id
      $timeZoneId = $timezone['id'];

      // Set label
      $timezoneList[$timeZoneId] = $timezone['zoneName'];
    }

    return \View::make('accounts.dashboard.schedules.update')->with([
    'startTime' => isset($startTime) ? $startTime : null,
    'endTime' => isset($endTime) ? $endTime : null,
    'timeZonePrevious' => isset($timeZonePrevious) ? $timeZonePrevious : null,
    '$updateErrors' => $updateErrors,
    'id' => $id,
    'times' => $times,
    'event' => $events,
    'eventSchedule' => $eventSchedule,
    'timeList' => $timeList,
    'repeatList' => $repeatList,
    'timezoneList' => $timezoneList,
    'repeatEveryList' => $repeatEveryList
    ]);
  }

  // Updates new schedule information entered
  public function doScheduleUpdate($id, $eventSchedule)
  {
    // Instantiate the APIHelper
    $api = new \App\Helpers\ApiHelper;

    // Gets all new schedule data entered
    $data = Request::all();

    $deleteTimes = $api->destroy('events/' . $id . '/schedules?auth_user_id=' . \Auth::user()->id, $eventSchedule);

    // Checks if user has selected to choose an end date for recurring
    if (isset($data['finalDateSet'])) {

      // Final time for recurring event
      $finalEndTime = $data['endFinalTime'] . $data['endFinalMeridian'];

      // End in recurring refers to the overall end date of an event with recurring schedule
      $recurring = [
        'repeats' => isset($data['repeats']) ? $data['repeats'] : null,
        'every' => isset($data['repeatEvery']) ? $data['repeatEvery'] : 0,
        'end' => [
          'date' => date('Y-m-d', strtotime($data['endFinalDate'])),
          'time' => date('H:i:00', strtotime($finalEndTime)),
        ],
        'repeatsBy' => isset($data['repeatsBy']) ? $data['repeatsBy'] : null,
        'weekdays' => isset($data['weekdays']) ? $data['weekdays'] : null,
      ];

    } else {

      $recurring = [
        'repeats' => isset($data['repeats']) ? $data['repeats'] : null,
        'every' => isset($data['repeatEvery']) ? $data['repeatEvery'] : 0,
        'end' => null,
        'repeatsBy' => isset($data['repeatsBy']) ? $data['repeatsBy'] : null,
        'weekdays' => isset($data['weekdays']) ? $data['weekdays'] : null,
      ];

    }

    $start = $data['startTime'] . $data['startMeridian'];

    $end = $data['endTime'] . $data['endMeridian'];

    $data['meta'] = json_encode([
      'schedules' => [
        [
          'timeZoneId' => isset($data['timeZoneId']) ? $data['timeZoneId'] : '6',
          'daysOfWeek' => isset($data['daysOfWeek']) ? $data['daysOfWeek'] : '',
          'start' => [
            'date' => date('Y-m-d', strtotime($data['startDate'])),
            'time' => date('H:i:00', strtotime($start)),
          ],
          'end' => [
            'date' => date('Y-m-d', strtotime($data['endDate'])),
            'time' => date('H:i:00', strtotime($end)),
          ],
          'repeat' => isset($data['recurring']) ? $recurring : null,
        ],
      ],
    ]);

    if (!isset($data['startMeridian'])) {
      $data['startMeridian'] = null;
    }
    if (!isset($data['endMeridian'])) {
      $data['endMeridian'] = null;
    }

    // Save updated details to the API
    $times = $api->update('events', $id . '?auth_user_id=' . \Auth::user()->id, $data);

    // Display correct failed success error
    if (isset($times['success']) && $times['success'] === false) {

      // Error
      $error = null;
      if (isset($times['error']) === false || is_array($times['error']) === false) {

        // Could not parse error
        $error = 'Unknown error';

      } else {

        // Get every validation error
        foreach ($times['error'] as $errorName => $errors) {

          // Errors
          $error .= implode(', ', $errors);
        }
      }

      return Redirect::back()->with('error', $error)->withInput();
    }

    // Display connection error
    if ( ! isset($times['success'])) {
      return Redirect::back()->with('error', 'Connection error please try again')->withInput();
    }

      // Encodes message to get sent with url
      $message = urlencode('Please wait while your times are being updated');

        // return Route::get('pages.loader', 302)->header('Location', (string)$url);
       $url = "time=4000&url=/events/" . $id . "/schedules/index&message=" . $message;

       return Redirect::route('pages.loader', (string)$url);

  }

  // Deletes schedule chosen by user
  public function doScheduleDelete($eventId)
  {
    $data = Request::all();

    $scheduleId = $data['scheduleId'];

    $api = new \App\Helpers\ApiHelper;

    // Deletes selected schedule
    $deleteTimes = $api->destroy('events/' . $eventId . '/schedules', $scheduleId . '?auth_user_id=' . \Auth::user()->id);

    return response()->json($deleteTimes);
  }

  /**
   * Deletes individual date from recurring schedule
   *
   * @param  $eventId Sends event id related to schedule
   * @param  $timeId  Selected time id to be deleted
   *
   */
  public function doDateDelete($eventId)
  {
    $data = Request::all();

    $timeId = $data['timeId'];

    $api = new \App\Helpers\ApiHelper;

    // Deletes selected dates
    $deleteTimes = $api->destroy('events/' . $eventId . '/times', $timeId . '?auth_user_id=' . \Auth::user()->id);

    if ($deleteTimes['success'] === true) {

     return response()->json($deleteTimes);
      // return Redirect::back()->with('success_message', 'Successfully Deleted');

    } else {

      if (isset($deleteTimes['error'])) {

    return response()->json($deleteTimes);

      }
    }
  }

  /**
   * Show individual date from recurring schedule
   *
   * @param  $eventId Sends event id related to schedule
   * @param  $timeId  Selected time id to be deleted
   *
   */
  public function showDateUpdate()
  {
    $data = \Request::all();

    $eventId = isset($data['eventId']) ? $data['eventId'] : null;

    $api = new \App\Helpers\ApiHelper;

    // Indexes dates for chosen event
    $response = $api->show('events', $eventId . '?with[]=schedules.times&sort[desc][]=created_at&auth_user_id=' . \Auth::user()->id);

    if ($response['success'] === true) {

     return response()->json($response);
      // return Redirect::back()->with('success_message', 'Successfully Deleted');

    } else {

      if (isset($response['error'])) {

        return Redirect::back()->with('fail_message', 'Something Went Wrong');

      }
    }
  }
  /**
   * Shows create page for new dates
   *
   * @param  [type] $id [description]
   * @return [type]     [description]
   */
  public function showScheduleCreate($id)
  {
    $api = new \App\Helpers\ApiHelper;

    $events = $api->show('events', $id . '?with[]=schedules.times&auth_user_id=' . \Auth::user()->id);

    if (empty($events['data']['event'])) {
      abort(404);
    } else {
      $event = $events['data']['event'];
    }

    if (\Auth::user()->id != $events['data']['event']['userId']) {
      abort(401);
    }

    $updateErrors = \Session::get('updateErrors');

    $timeList = [
      '1:00' => "1:00",
      '1:15' => "1:15",
      '1:30' => "1:30",
      '1:45' => "1:45",
      '2:00' => "2:00",
      '2:15' => "2:15",
      '2:30' => "2:30",
      '2:45' => "2:45",
      '3:00' => "3:00",
      '3:15' => "3:15",
      '3:30' => "3:30",
      '3:45' => "3:45",
      '4:00' => "4:00",
      '4:15' => "4:15",
      '4:30' => "4:30",
      '4:45' => "4:45",
      '5:00' => "5:00",
      '5:15' => "5:15",
      '5:30' => "5:30",
      '5:45' => "5:45",
      '6:00' => "6:00",
      '6:15' => "6:15",
      '6:30' => "6:30",
      '6:45' => "6:45",
      '7:00' => "7:00",
      '7:15' => "7:15",
      '7:30' => "7:30",
      '7:45' => "7:45",
      '8:00' => "8:00",
      '8:15' => "8:15",
      '8:30' => "8:30",
      '8:45' => "8:45",
      '9:00' => "9:00",
      '9:15' => "9:15",
      '9:30' => "9:30",
      '9:45' => "9:45",
      '10:00' => "10:00",
      '10:15' => "10:15",
      '10:30' => "10:30",
      '10:45' => "10:45",
      '11:00' => "11:00",
      '11:15' => "11:15",
      '11:30' => "11:30",
      '11:45' => "11:45",
      '12:00' => "12:00",
      '12:15' => "12:15",
      '12:30' => "12:30",
      '12:45' => "12:45"
    ];

    $repeatList = [
      'daily' => 'Daily',
      'weekly' => 'Weekly',
      'monthly' => 'Monthly',
      'yearly' => 'Yearly',
    ];

    $repeatEveryList = [
      '1' => "1",
      '2' => "2",
      '3' => "3",
      '4' => "4",
      '5' => "5",
      '6' => "6",
      '7' => "7",
      '8' => "8",
      '9' => "9",
      '10' => "10",
      '11' => "11",
      '12' => "12",
      '13' => "13",
      '14' => "14",
      '15' => "15",
      '16' => "16",
      '17' => "17",
      '18' => "18",
      '19' => "19",
      '20' => "20",
      '21' => "21",
      '22' => "22",
      '23' => "23",
      '24' => "24",
      '25' => "25",
      '26' => "26",
      '27' => "27",
      '28' => "28",
      '29' => "29",
      '30' => "30"
    ];

    // Grabs event schedules
    $times = $event['schedules'];

    // Get list of timezones
    $timezoneList = [];
    $timezones = $api->index('collections/timezones?limit=200&fields[]=id&fields[]=zoneName');
    $timezones = isset($timezones['data']['timezones']) ? $timezones['data']['timezones'] : [];

    // Create timezone list for select input
    foreach ($timezones as $timezone) {

      // Set time_zone_id
      $timeZoneId = $timezone['id'];

      // Set label
      $timezoneList[$timeZoneId] = $timezone['zoneName'];
    }

    return \View::make('accounts.dashboard.schedules.create')->with([
      'updateErrors' => $updateErrors,
      'event' => $event,
      'times' => $times,
      'timeList' => $timeList,
      'repeatList' => $repeatList,
      'timezoneList' => $timezoneList,
      'repeatEveryList' => $repeatEveryList
    ]);
  }

  // Updates new schedule information entered
  public function doScheduleCreate($id)
  {
    // Insanciate the APIHelper
    $api = new \App\Helpers\ApiHelper;

    // Gets all new schedule data entered
    $data = Request::all();

    Input::flash();

    // Checks if user has selected to choose an end date for recurring
    if (isset($data['finalDateSet'])) {

      // Final time for recurring event
      $finalEndTime = $data['endFinalTime'] . $data['endFinalMeridian'];

      // End in recurring refers to the overall end date of an event with recurring schedule
      $recurring = [
        'repeats' => isset($data['repeats']) ? $data['repeats'] : null,
        'every' => isset($data['repeatEvery']) ? $data['repeatEvery'] : 0,
        'end' => [
          'date' => date('Y-m-d', strtotime($data['endFinalDate'])),
          'time' => date('H:i:00', strtotime($finalEndTime)),
        ],
        'repeatsBy' => isset($data['repeatsBy']) ? $data['repeatsBy'] : null,
        'weekdays' => isset($data['weekdays']) ? $data['weekdays'] : null,
      ];

    } else {

      $recurring = [
        'repeats' => isset($data['repeats']) ? $data['repeats'] : null,
        'every' => isset($data['repeatEvery']) ? $data['repeatEvery'] : 0,
        'end' => null,
        'repeatsBy' => isset($data['repeatsBy']) ? $data['repeatsBy'] : null,
        'weekdays' => isset($data['weekdays']) ? $data['weekdays'] : null,
      ];

    }

    $start = $data['startTime'] . $data['startMeridian'];

    $end = $data['endTime'] . $data['endMeridian'];

    $data['meta'] = json_encode([
      'schedules' => [
        [
          'timeZoneId' => isset($data['timeZoneId']) ? $data['timeZoneId'] : '6',
          'daysOfWeek' => isset($data['daysOfWeek']) ? $data['daysOfWeek'] : '',
          'start' => [
            'date' => date('Y-m-d', strtotime($data['startDate'])),
            'time' => date('H:i:00', strtotime($start)),
          ],
          'end' => [
            'date' => date('Y-m-d', strtotime($data['endDate'])),
            'time' => date('H:i:00', strtotime($end)),
          ],
          'repeat' => isset($data['recurring']) ? $recurring : null,
        ],
      ],
    ]);

    if (!isset($data['startMeridian'])) {
      $data['startMeridian'] = null;
    }
    if (!isset($data['endMeridian'])) {
      $data['endMeridian'] = null;
    }

    // Save updated details to the API
    $times = $api->update('events', $id . '&auth_user_id=' . \Auth::user()->id, $data);

    // Display correct failed success error
    if (isset($times['success']) && $times['success'] === false) {

      // Error
      $error = null;
      if (isset($times['error']) === false || is_array($times['error']) === false) {

        // Could not parse error
        $error = 'Unknown error';

      } else {

        // Get every validation error
        foreach ($times['error'] as $errorName => $errors) {

          // Errors
          $error .= implode(', ', $errors);
        }
      }

      return Redirect::back()->with('error', $error)->withInput();
    }

    // Display connection error
    if ( ! isset($times['success'])) {

      return Redirect::back()->with('error', 'Connection error please try again')->withInput();

    }

      // Encodes message to be sent with url
      $message = urlencode('Please wait while your schedule is being saved');

      // return Route::get('pages.loader', 302)->header('Location', (string)$url);
      $url = "time=4000&url=/events/" . $id . "/schedules/index&message=" . $message;

      return Redirect::route('pages.loader', (string)$url);
  }

	/**
	 * Show Friends Page
	 * @return frien
	 * ds page view
	 */
	// public function showMyEvents()
	// {
	// 	// Illuminate the APIHelper
	// 	$api = new \App\Helpers\ApiHelper;
	// 	$events = $api->index('events?&with[]=times&filter[and][][is_published]=1&filter[and][][is_banned]=0');
	// 	$events = $events['data']['events'];

	// 	return View::make('accounts.my-events')->with(['events' => $events]);
	// }

  /**
   * Show MyTickets Page
   * @return purchased tickets
   * ds page view
   */
  public function showMyTickets()
  {
    // Illuminate the APIHelper
    $api = new \App\Helpers\ApiHelper;
    $tickets = $api->index('tickets/user/' . Auth::user()->id . '/codes?limit=40&auth_user_id=' . \Auth::user()->id);

    // Declare purchased tickets
    $purchasedTickets = isset($tickets['data']['orders']) ? $tickets['data']['orders'] : [];

    if (!empty($purchasedTickets)) {

      // Unset reservations
      foreach($purchasedTickets as $index => $ticketTypes) {

        if ($ticketTypes['types'] === null) {

          // This removes reservations from tickets array
          unset($purchasedTickets[$index]);

        }

      }

      $futurePurchasedTickets = $purchasedTickets;
      $pastPurchasedTickets = $purchasedTickets;

      foreach($futurePurchasedTickets as $index => $tickets) {
        if ($tickets['eventTime']['start'] <= $today = date("Y-m-d")) {

          // Unset any tickets that are not for today or future date
          unset($futurePurchasedTickets[$index]);
        }
      }

      foreach($pastPurchasedTickets as $index => $pastTickets) {
        if ($pastTickets['eventTime']['start'] > $today = date("Y-m-d")) {

          // Unset any tickets that are for today or future date
          unset($pastPurchasedTickets[$index]);
        }
      }

    }

    return View::make('accounts.my-tickets')->with([
      'pastPurchasedTickets' => $pastPurchasedTickets,
      'futurePurchasedTickets' => $futurePurchasedTickets
    ]);
  }

	// TODO: Auth Logic that we are waiting to implement.

	/**
	 * Get Authorization from the users Social Account
	 * @param  [type] $provider [description]
	 * @return [type]           [description]
	 */
	public function loginProvider($provider)
	{
		// require supported provider
		if ( ! array_key_exists($provider, $this->socialProviders)) {

			\Log::error('Unsupported Social Provider', compact('provider'));
			return redirect()->route('home');
		}

		// Set scopes
		$scopes = $this->socialProviders[$provider]['scope'];

		// Check if scopes are set for this provider "twitter"
		if (empty($scopes)) {

			// Redirect to provider login page with no scopes defined
			return Socialize::with($provider)->redirect();

		} else {

			// Redirect to provider login page with specified scopes
			return Socialize::with($provider)->scopes($scopes)->redirect();
		}

	}

	/**
	 * Callback function after the user is authenticated by a social network.
	 * @param  [type] $provider [description]
	 * @return [type]           [description]
	 */
	public function loginProviderCallback($provider)
	{
		// require supported provider
		if ( ! array_key_exists($provider, $this->socialProviders)) {
			\Log::error('Unsupported Social Provider', compact('provider'));
			return redirect()->route('home');
		}

		// Get social profile from provider
 		$user = Socialize::with($provider)->user();

		// Create or update a user via this social provider
		$apiUserProvider = new ApiUserProvider(new User);
		$user = $apiUserProvider->retrieveByProvider($provider, (array) $user);

		// Check if we got a successful retrieval
		if ($user !== null) {

			// Login
			Auth::login($user);

			// Redirect back to home page
			return redirect()->intended(route('home'));

		// Error creating/getting user
		} else {

			// Redirect with error message
			return redirect()->route('home')->with('fail_message', 'Error login in with ' . $provider);
		}
	}

	/**
	 * Show the register page
	 * @return [type] [description]
	 */
	public function showRegister()
	{
		// Check if this user is already logged in
		if (Auth::check()) {

			// Redirect with error message
			return redirect()->route('home')->with('warning_message', 'Already logged in');
		}

		return View::make('accounts.register');
	}

	/**
	 * Grab all the inputs from the registration form and register the user via the api
	 *
	 * @return Redirect
	 */
	public function doRegister()
	{
		// Get all user form input
		$userFields = Input::all();

		// Setup validation rules for the submitted data
		$validator = Validator::make($userFields, [

			// required
			'first_name' => 'required',

			// required
			'last_name' => 'required',

			// required, must be email
			'email' => 'required|email',

			// required, 6 chars min length, password must match password_confirmation
			'password' => 'required|min:6|confirmed'
		]);

		// If validation failed
		if ($validator->fails()) {

			// Redirect to register page with previously submitted input and validation errors
			return redirect()->route('register')->withInput()->withErrors($validator->errors());

		// If validation is successful
		} else {

			// Submit data to API for account creation
			$apiUserProvider = new ApiUserProvider(new User);
			$user = $apiUserProvider->retrieveByRegistration($userFields);

			// Check if we got a successful retrieval
			if ($user !== null && ! is_array($user)) {

				// Login
				Auth::login($user);

        $cookieGet = \Cookie::get('suarayrequestfrom');

        if ($cookieGet !== null) {

          // Illuminate the APIHelper
          $api = new \App\Helpers\ApiHelper;


          // Create friend request
          $userRequest = $this->doCreateRequest(\Cookie::get('suarayrequestfrom'), ['fromRequest' => true]);

          // Accept friend request
          $userRequest = $api->store('users/' . $userRequest['data']['request']['user_id'] . '/friends', ['friendId' => $userRequest['data']['request']['requester_id']]);

        }

				// Redirect back to home page
				return redirect()->route('home')->withCookie(\Cookie::forget('suarayrequestfrom'));

			// Error creating/getting user
			} else {

				// If $user was returned with an array of errors
				if (is_array($user)) {

					// Set a nice error that plays nice with validation errors
					foreach ($user as $inputField => $inputFieldErrors) {

						foreach ($inputFieldErrors as $error) {

							// Set api specific errors
							$validator->errors()->add('api', $error);
						}
					}
				}

				// Redirect to register page with previously submitted input and api errors
				return redirect()->route('register')->withInput()->withErrors($validator->errors());
			}
		}
	}

	/**
	 * Log the user out
	 * @return [type] [description]
	 */
	public function doLogout()
	{
		Auth::logout();
		return redirect()->route('login');
	}

	/**
	 * Verify the users email via varification code.
	 * @param  [type] $verificationCode [description]
	 * @return [type]                   [description]
	 */
	public function verifyEmail($verificationCode) {
		// TODO: Need to add some logic to verify the users email
	}

	/**
	 * Resend verification email
	 * @return [type] [description]
	 */
	public function resendEmailVerification() {
		// TODO: Laravel 5 comes equiped with php artisan make:auth .. maybe we should use that instead?
		// user should be authenticated
		if (! Auth::check())
			return \Redirect::intended('/')->with('fail_message', 'You are not authenticated.');

		$user = Auth::user();

		// if email is already verified
		if ($user->email_verification === null)
			return \Redirect::intended('/')->with('warning_message', 'Your email is already verified.');

		// tell api to send email
		$response = Unirest::post(Config::get('shindiig.api.url') . 'user/email/verification/send', ['X-API-Authorization' => Config::get('shindiig.api.key')], [
			'id' => $user->id
		]);

		// check if api call succeeded
		if ($response->code != 200)
			return \Redirect::intended('/')->with('fail_message', 'Sorry, there was an error. Please try again later.');

		return \Redirect::intended('/')->with('success_message', 'You will receive verification email shortly on <b>' . $user->email . '</b>');
	}

	/**
	 * Show user profile
	 *
	 * @param  string $username user username (optional)
	 */
	public function showProfile($username = null)
	{
		// Incandescence the APIHelper
		$api = new \App\Helpers\ApiHelper;

		// Is this profile being called publicly or privately
		$isAccessedPublicly = empty($username) ? false : true;

		// Abort if we are public and there is no username or its empty
		if ($isAccessedPublicly && empty($username)) {

			// Show the not found page
			abort(404);
		}

		// Initiate created events by user
		$eventsCreatedByUser = [];

		// Get user info
		if ($isAccessedPublicly) {

      $url = 'users?filter[and][][username]=' . $username . '&with[]=updates&with[]=ticketInventories&with[]=friends&with[]=friendRequests&with[]=stripeManagedAccounts';

      if (\Auth::check()) {
        $url .= '&auth_user_id=' . \Auth::user()->id;
      }

			// Query by username
			$user = $api->index($url);

      // Set user data
      $user = isset($user['data']['users'][0]) ? $user['data']['users'][0] : [];

      // If user exists
      if (!empty($user)) {

        $eventUrl = 'events?filter[and][][user_id]=' . $user['id'] . '&with[]=times&filter[and][][is_published]=1&filter[and][][is_banned]=0&with[]=TicketsInventory';

        if (\Auth::check()) {
          $eventUrl .= '&auth_user_id=' . \Auth::user()->id;
        }

  			$eventsCreatedByUser = $api->index($eventUrl);

        $attendingUrl = 'events/attending/' . $user['id'];

        if (\Auth::check()) {
          $attendingUrl .= '?auth_user_id=' . \Auth::user()->id;
        }

        // Get events that this user is attending
        $userEventsTimesAttending = $api->index($attendingUrl);

        // Set events that this user is attending
        $userEventsTimesAttending = isset($userEventsTimesAttending['data']['times']) ? $userEventsTimesAttending['data']['times'] : [];

        $myEventsformatted = [];

        $isFriend = false;

        $isRequested = false;

        if (isset($user['friendRequests']) && \Auth::user()) {

          // Indexes and iterates through friend requests
          foreach ($user['friendRequests'] as $index => $checkRequested){

            // If friend request id and auth id match, will display proper message in view
            if ($checkRequested['id'] === \Auth::user()->id){

              $isRequested = true;

              // Once match is found, stops iteration
              break;
            }

          }

        }

        if (isset($userEventsTimesAttending)) {

          foreach ($userEventsTimesAttending as $time) {

              $myEventsformatted[] = [
                'title' => isset($time['event']['title']) ? $time['event']['title'] : null,
                'start' => isset($time['start']) ? $time['start'] : null ,
                'end' => isset($time['end']) ? $time['end'] : null ,
                'url' => route('events.show' , [
                  'slug' => isset($time['event']['slug']) ? $time['event']['slug'] : null,
                ]),
              ];

          }
        }

        // JSON encode
        $myEventsformatted = addslashes(json_encode($myEventsformatted));

      }

		} else {

			// Query by id of logged in user
			$user = $api->index('users?filter[and][][id]=' . Auth::user()->id . '&with[]=updates&with[]=ticketInventories&with[]=stripeManagedAccounts&auth_user_id=' . \Auth::user()->id);

      // Set user data
      $user = isset($user['data']['users'][0]) ? $user['data']['users'][0] : [];

      // If user exists
      if (!empty($user)) {

        $eventsCreatedByUser = $api->index('events?filter[and][][user_id]=' . \Auth::user()->id . '&with[]=times&filter[and][][is_published]=1&filter[and][][is_banned]=0&with[]=TicketsInventory&auth_user_id=' . \Auth::user()->id);

        // Get events that this user is attending
        $userEventsTimesAttending = $api->index('events/attending/' . \Auth::user()->id . '?auth_user_id=' . \Auth::user()->id);

        // Set events that this user is attending
        $userEventsTimesAttending = isset($userEventsTimesAttending['data']['times']) ? $userEventsTimesAttending['data']['times'] : [];

        $myEventsformatted = [];

        if (isset($userEventsTimesAttending)) {

          foreach ($userEventsTimesAttending as $time) {

              $myEventsformatted[] = [
                'title' => isset($time['event']['title']) ? $time['event']['title'] : null,
                'start' => isset($time['start']) ? $time['start'] : null ,
                'end' => isset($time['end']) ? $time['end'] : null ,
                'url' => route('events.show' , [
                  'slug' => isset($time['event']['slug']) ? $time['event']['slug'] : null,
                ]),
              ];

          }
        }

        // JSON encode
        $myEventsformatted = addslashes(json_encode($myEventsformatted));

      }

		}

    // Make sure we have data
    if (empty($user)) {

      // Show the not found page
      abort(404);

    }

    $friendPost = [];

    if (isset($user['friends']) && \Auth::user()) {

      // Indexes and iterates through public user friends
      foreach ($user['friends'] as $index => $checkFriend){

        // If auth user id and friend id matches, will display proper message in view
        if ($checkFriend['id'] === \Auth::user()->id){

          $isFriend = true;
          $friendPost = $checkFriend;
          // Once match is found, stops iteration
          break;
        }

      }

    }

    $eventsCreatedByUser = isset($eventsCreatedByUser['data']['events']) ? $eventsCreatedByUser['data']['events'] : [];

    $url = 'events?filter[and][][isSponsored]=1&fields[]=id&fields[]=title' .
      '&fields[]=description&fields[]=slug&fields[]=isSponsored' .
      '&fields[]=featuredPhoto&limit=3&filter[and][][is_published]=1' .
      '&filter[and][][is_banned]=0';

    if (auth::check()) {

       $url .= '&auth_user_id=' . \Auth::user()->id;

    }

		// Get 3 sponsored events
		$sponsoredEvents = $api->index($url);

		// Give events a short variable to work with
		$sponsoredEvents = isset($sponsoredEvents['data']['events']) ? $sponsoredEvents['data']['events'] : [];

		// Get upload settings for evaporate.js
    $uploadSettings = $api->index('settings/upload');
    $uploadSettings = isset($uploadSettings['data']) ? $uploadSettings['data'] : [];

		// Return view
		return View::make('accounts.profile')->with(array(
      'friendPost' => isset($friendPost) ? $friendPost : [],
      'isFriend' => isset($isFriend) ? $isFriend : false,
      'isRequested' => isset($isRequested) ? $isRequested : false,
			'isAccessedPublicly' => $isAccessedPublicly,
      'myEventsformatted' => $myEventsformatted,
			'user' => $user,
			'eventsCreatedByUser' => $eventsCreatedByUser,
			'userEventsTimesAttending' => $userEventsTimesAttending,
			'uploadSettings' => $uploadSettings,
			'sponsoredEvents' => $sponsoredEvents,
		));
	}

	/**
	 * TODO: update DOC
	 * @return [type] [Update Wall Comments]
	 */
	public function doWallComments(){

		// Insanciate the APIHelper
		$api = new \App\Helpers\ApiHelper;

		// Request Data
		$data = \Request::all();

		// Setup validation rules for the submitted data
		$validator = Validator::make($data, [

			// required
			'message' => 'required'

		]);

		// If validation failed
		if ($validator->fails()) {

			// Redirect to register page with previously submitted input and validation errors
			$failedwall = "Dont leave your wall post empty";

			return Redirect::to(\URL::previous() . "#wallcomments");

		// If validation is successful
		} else {

			// Authorize User to leave comments if logged in
			$user = Auth::user();

			// Get users ID
			$id = $user->id;

			// Store the wall update in the database
			$user = $api->store('usersupdates?auth_user_id=' . \Auth::user()->id, $data);

					// Redirect back to the events page and comment section
			if ($user) {
			  return Redirect::to(\URL::previous() . "#wallcomments");
			}

		}

	}

  /**
   * TODO: update DOC
   * @return [type] Deletes wall comments
   */
  public function doDeleteWallComments($id)
  {

    // Instanciate the APIHelper
    $api = new \App\Helpers\ApiHelper;

    // Deletes wall comment from database
    $response = $api->destroy('usersupdates', $id);

    // Redirect back to the events page and comment section
    if ($response) {
      return Redirect::to(\URL::previous() . "#wallcomments");
    }

  }

	/**
	 * TODO: update DOC
	 * @return [type] [description]
	 */
	public function updateProfileSettings() {

		$data = \Request::all();

		// Set the users ID
		$id = $data['id'];

		// Initiate API helper
		$api = new \App\Helpers\ApiHelper;

		// Save updated details to the API
		$user = $api->update('users', $id . '?auth_user_id=' . \Auth::user()->id, $data);

		if ($user['success'] === true) {
			\Session::flash('success_message', 'Account updated successfully.');
		}

		return \Redirect::route('profile');
	}

	/**
	 * Allow the user to add/update their profile picture
	 * @return [type] [description]
	 */
	public function updateProfilePhoto()
  {

  	$user = Auth::user();

    // Initiate API helper
    $api = new \App\Helpers\ApiHelper;

    // Save updated details to the API
    $user = $api->update('users', $user->id . '?auth_user_id=' . \Auth::user()->id, []);

    if ($user['success'] === true) {
  		\Session::flash('success_message', 'Profile pic updated successfully.');
    }

    return $user;

	}

  // Requesting a reset
  // From login the user forgets password
  public function showForgot()
  {
    if (Auth::check()) {
      return \Redirect::route('home');
    } else {
      return View::make('auth.password');
    }
  }

  // Resetting Password
  // they post email to endpoint, endpoint sends email
  public function showReset($token, $id = null)
  {
    if (Auth::check()) {

      return \Redirect::route('home');

    } else {

      // Insanciate the APIHelper
      $api = new \App\Helpers\ApiHelper;

      // Save updated details to the API
      $password = $api->show('password/reset/' . $token, $id . '?auth_user_id=' . \Auth::user()->id);

      $email = $password['data']['password']['email'];

      return View::make('auth.reset')->with(['token' => $token, 'email' => $email]);

    }
  }

  // Payment setup page
  public function showPayment()
  {
    // Insanciate the APIHelper
    $api = new \App\Helpers\ApiHelper;

    // Check if user is signed in
    if (\Auth::check()) {

      // Pull in users account
      $user = $api->show('users', \Auth::user()->id . '?with[]=ticketInventories&with[]=stripeManagedAccounts&auth_user_id=' . \Auth::user()->id);
      $user = $user['data']['user'];

    }

    if (isset($user['stripeManagedAccounts'][0]['acctId'])) {
      $accountId = $user['stripeManagedAccounts'][0]['acctId'];

      // Pull users stripe account
      $account = $api->show('users/showManaged', $accountId . '?auth_user_id=' . \Auth::user()->id);
      $account = isset($account['data'][0][0]) ? $account['data'][0][0] : $account;

      if (isset($account['verification'])) {

        // Return with view and variables
        return View::make('accounts.payment')->with([
          'warning_message' => $account['verification']['fields_needed'],
          'account' => $account,
          'accountId' => isset($account['id']) ? $account['id'] : $accountId,
          'user' => isset($user) ? $user : null,
        ]);
      }
    }

    return View::make('accounts.payment')->with([
      'user' => isset($user) ? $user : null,
      'account' => isset($account) ? $account : [],
    ]);
  }

  // Payment setup page
  public function updatePayment($accountId)
  {
    // Request all data
    $data = Input::all();

    // Insanciate the APIHelper
    $api = new \App\Helpers\ApiHelper;

    $updateAccount = $api->store('users/updateManaged/' . $accountId . '?auth_user_id=' . \Auth::user()->id, $data);

    if ($updateAccount['success'] !== true) {

      return Redirect::route('payment')->with('fail_message', isset($updateAccount['message']) ? $updateAccount['message'] : 'Something went wrong. Check your input and retry.')->withInput();

    }

    return Redirect::back()->with('success_message', 'Account has been updated successfully');
  }

  // they are taken to the forgot password page
  public function doForgot()
  {
    // Insanciate the APIHelper
    $api = new \App\Helpers\ApiHelper;

    // Request all data
    $data = Request::all();

    // Grab the callback url of the current server
    $data['callback'] = env('APP_URL');

    // Save updated details to the API
    $password = $api->store('password/forgot', $data);

    return Redirect::route('login')->with('success_message', 'An email has been sent with a link to reset your password');
  }

  public function updateReset($token)
  {
    // Insanciate the APIHelper
    $api = new \App\Helpers\ApiHelper;

    $data = Request::all();

    // Save updated details to the API
    $password = $api->store('password/reset/' . $token, $data);

    if ($password['success'] !== true) {

      // Return fail message
      return Redirect::back()->with('fail_message', $password['data']['password']);

    }else{

      // Return with success
      return Redirect::route('login')->with('success_message', 'password changed, please sign in');

    }

  }

  public function oath($provider)
  {
    // Redirect to stripe connect page
    return Socialize::with($provider)->redirect();
  }

  public function oathCallback(Request $request, $provider)
  {
    // Check if user is signed in
    if (! \Auth::check()) {

      // Return fail message
      return Redirect::route('home')->with('fail_message', 'You must be signed in to make this request');
    }

    // Check if code is set
    if (! isset($request::all()['code'])) {
      return Redirect::route('payment')
        ->with('fail_message', 'There was a problem creating your account. Please try again later');
    }

    // Define userId
    $userId = \Auth::user()->id;

    // Define connected user data with return authorization
    $connectedUser = Socialize::driver($provider)->user();

    // Define create data
    $acctId = $connectedUser->id;
    $meta = json_encode($connectedUser);

    // Insanciate the APIHelper
    $api = new \App\Helpers\ApiHelper;

    // Save managed account to API
    $stripeManagedAccount = $api->store('users/createManaged', [
      'user_id' => $userId,
      'acct_id' => $acctId,
      'meta' => $meta,
    ]);

    // Check success
    if ($stripeManagedAccount['success'] !== true) {
      return Redirect::route('payment')->with(['fail_message' => $stripeManagedAccount['error']]);
    }

    // Return success
    return Redirect::route('payment')->with(['success_message' => 'Account connected successfully!']);
  }

}
