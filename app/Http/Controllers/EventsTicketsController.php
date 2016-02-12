<?php namespace App\Http\Controllers;

use App\Http\Requests;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use App\Helpers\ApiHelper;

use Redirect;

class EventsTicketsController extends Controller {

	/**
   * Show the ticket purchase page
   * @param  string $slug The slug name of the event
   * @return view         Confirmation view
   */
	public function show(Request $request, $slug)
	{
    // Grab data from front end
		$data = \Input::get();

		// Iniciate apiHelper
		$api = new ApiHelper;

    if (! empty($data)) {

      $inventoryUrl = 'tickets/inventory';

      if (\Auth::check()) {
        $inventoryUrl .= '?auth_user_id=' . \Auth::user()->id;
      }

      // Pull data for the ticket
      $checkInventory = $api->store($inventoryUrl, $data);
    }

    // If inventory check fails
    if (isset($checkInventory) && $checkInventory['success'] !== true) {

      // Redirect back with fail message
      return Redirect::back()->with('fail_message', $checkInventory['error']);
    }

    $eventUrl = 'events/' . $slug . '?with[]=photos&with[]=ticketsInventory&with[]=nextEvent';

    if (\Auth::check()) {
      $eventUrl .= '&auth_user_id=' . \Auth::user()->id;
    }

		// Pull events with photos and ticketsInventory
		$events = $api->index($eventUrl);

    if (! isset($events['data']['event'])) {
      abort('404');
    }

		// Define Events
		$events = $events['data']['event'];

    // Set tickets
    $tickets = $events['ticketsInventory'];

    // Indexes and iterates through ticket inventory
    foreach ($events['ticketsInventory'] as $index => $ticketType){

      $tickets = $events['ticketsInventory'];

      // If reservation is true, removes reservations and reorders array
      if ($ticketType['isReservation'] === true){

        unset($events['ticketsInventory'][$index]);

        $tickets = array_values($events['ticketsInventory']);

      }
    }

		if (\Session::has('fail_message')) {

			$cost = \Session::get('cost');
			$many = \Session::get('many');
      $checkInventory = \Session::get('checkInventory');

		} else {

			// Set the initial cost to null
			$cost = null;
			$many[] = 0;

		}

		// Initiate purchased tickets
		$purchased = null;
    $purchasedId = [];
    $qty = [];

		// If qty is set
		if (isset($data['qty'])) {

			// For each qty count $n
			for ($n = 0; $n < count($data['qty']); $n++){

				// If tickets has a selected value over or equal to 1 print its details
				if ($data['qty'][$n] >= 1 ){

					$purchased[] = ['name' => $tickets[$n]['name'], 'qty' => $data['qty'][$n]];

					// Multiply the amount to the qty purchased
					$total = $data['qty'][$n] * $tickets[$n]['amount'];

					// Add the amount of Tickets purchased
					$many[] = $data['qty'][$n];

          $qty[] = $data['qty'][$n];

          $purchasedId[] = $data['id'][$n];

					// Pass the total to cost
					$cost[] = $total;

				}

			}

      // If cost is not empty
      if ($cost !== null) {

        $cost = array_sum($cost);

      }

		}

    if ($many !== null) {
      $many = array_sum($many);
    }

		$response = new Response;
		$response->header('Cache-Control', 'no-store, no-cache, must-revalidate, post-check=0, pre-check=0');

		$stateList =[
			'SS' => "Select State",
      'AL' => "Alabama",
      'AK' => "Alaska",
      'AZ' => "Arizona",
      'AR' => "Arkansas",
      'CA' => "California",
      'CO' => "Colorado",
      'CT' => "Connecticut",
      'DE' => "Delaware",
      'DC' => "District Of Columbia",
      'FL' => "Florida",
      'GA' => "Georgia",
      'HI' => "Hawaii",
      'ID' => "Idaho",
      'IL' => "Illinois",
      'IN' => "Indiana",
      'IA' => "Iowa",
      'KS' => "Kansas",
      'KY' => "Kentucky",
      'LA' => "Louisiana",
      'ME' => "Maine",
      'MD' => "Maryland",
      'MA' => "Massachusetts",
      'MI' => "Michigan",
      'MN' => "Minnesota",
      'MS' => "Mississippi",
      'MO' => "Missouri",
      'MT' => "Montana",
      'NE' => "Nebraska",
      'NV' => "Nevada",
      'NH' => "New Hampshire",
      'NJ' => "New Jersey",
      'NM' => "New Mexico",
      'NY' => "New York",
      'NC' => "North Carolina",
      'ND' => "North Dakota",
      'OH' => "Ohio",
      'OK' => "Oklahoma",
      'OR' => "Oregon",
      'PA' => "Pennsylvania",
      'RI' => "Rhode Island",
      'SC' => "South Carolina",
      'SD' => "South Dakota",
      'TN' => "Tennessee",
      'TX' => "Texas",
      'UT' => "Utah",
      'VT' => "Vermont",
      'VA' => "Virginia",
      'WA' => "Washington",
      'WV' => "West Virginia",
      'WI' => "Wisconsin",
      'WY' => "Wyoming"
		];

    // Set eventTimeId
    $eventTimeId = isset($data['eventTimeId']) ? $data['eventTimeId'] : null;

    // Set default photo for carousel include view
    $defaultPhoto = isset($events['featuredPhoto']) ? $events['featuredPhoto'] : ['title' => null, 'url' => null];

    $updateErrors = \Session::get('updateErrors');



    if ($request->hasCookie('purchaseCookieData')) {

      $fromCookie = unserialize($request->cookie()['purchaseCookieData']);

      $purchased = $fromCookie['purchased'];
      $cost = $fromCookie['cost'];
      $eventTimeId = $fromCookie['eventTimeId'];
      $qty = $fromCookie['qty'];
      $many = $fromCookie['many'];
      $purchasedId = $fromCookie['purchasedId'];
      $defaultPhoto = $fromCookie['defaultPhoto'];

      return response()->view('events.tickets', [
          'events' => $events,
          'updateErrors' => $updateErrors,
          'stateList' => $stateList,
          'purchased' => $purchased,
          'cost' => $cost,
          'eventTimeId' => $eventTimeId,
          'qty' => $qty,
          'many' => $many,
          'purchasedId' => $purchasedId,
          'defaultPhoto' => $defaultPhoto,
        ]);
    } else {

      $responseData = [
        'purchased' => $purchased,
        'cost' => $cost,
        'eventTimeId' => $eventTimeId,
        'qty' => $qty,
        'many' => $many,
        'purchasedId' => $purchasedId,
        'defaultPhoto' => $defaultPhoto,
      ];

      $cookieData = cookie('purchaseCookieData', serialize($responseData), 5);

      return response()->view('events.tickets', [
          'events' => $events,
          'stateList' => $stateList,
          'purchased' => $purchased,
          'cost' => $cost,
          'eventTimeId' => $eventTimeId,
          'qty' => $qty,
          'many' => $many,
          'purchasedId' => $purchasedId,
          'defaultPhoto' => $defaultPhoto,
        ])
        ->withCookie($cookieData);
    }
	}

  public function doReserve($id)
  {

    // Request all input
    $data = \Input::all();

    // Iniciate apiHelper
    $api = new ApiHelper;

    $url = 'events/' . $id . '?with[]=photos&with[]=ticketsInventory&with[]=nextEvent';

    if (\Auth::check()) {
      $url .= '&auth_user_id=' . \Auth::user()->id;
    }

    // Pull events with photos and ticketsInventory
    $events = $api->index($url);

    // Define Events
    $events = $events['data']['event'];

    $reservations = $events['ticketsInventory'];

    $eventTimeId = isset($data['eventTimeId']) ? $data['eventTimeId'] : null;
    $email = isset($data['email']) ? $data['email'] : null;

    // Indexes and iterates through tickets inventory
    foreach ($events['ticketsInventory'] as $index => $ticketType){

      // If reservation is false, function will unset and reorder array to properly send reservation
      if ($ticketType['isReservation'] === false){

        unset($events['ticketsInventory'][$index]);

        $reservations = array_values($events['ticketsInventory']);

      }
    }

    $request = [];
    $reserveInfo = [];

    // If request is set
    if (isset($data['reservation_request']) && !empty($data['reservation_request'])) {

      // For each request count $n
      for ($n = 0; $n < count($data['reservation_request']); $n++){

        // If request is not an empty string
        if ($data['reservation_request'][$n] != ""){

          $reserveInfo[] = ['id' => $data['reservation_id'][$n], 'request' => $data['reservation_request'][$n], 'name' => $reservations[$n]['name'], 'eventTimeId' => $eventTimeId];

        }

      }

    }
    // If no input is returned with request
    if ($reserveInfo === []) {

      // Redirect back with fail message
      return Redirect::back()->with('fail_message', 'No reservation information was entered');

    }

    $reservation = [
        'userId' => isset($data['userId']) ? $data['userId'] : null,
        'email' => $email,
        'reservations' => $reserveInfo,
    ];

    $api = new ApiHelper;

    $reserveUrl = 'events/' . $id . '/reservations';

    if (\Auth::check()) {
      $reserveUrl .= '?auth_user_id=' . \Auth::user()->id;
    }

    // Store reservation
    $reserve = $api->store($reserveUrl, $reservation);

    // If reservation successful
    if ($reserve['success'] === false) {

      foreach ($reserve['error'] as $key => $error) {
        for ($i=0; $i < count($reserve['error']); $i++) {
          if (isset($error[$i])) {
            $errors[] = $error[$i];
          }
        }
      }

      // Redirect back with fail message and input
      return Redirect::back()
        ->with(['updateErrors' => $errors])
        ->withInput();

    } else {

      $confirmation = $reserve['data']['reservations'];

      // Redirect to the reservation confirmation page with accompanying data
      return \Redirect::route('reservation.confirmation', $events['slug'])->with([
        'confirmation' => $confirmation,
        'reserveInfo' => $reserveInfo
      ]);

    }
  }

	public function doCharge($slug)
	{

    // Set the error array as empty array
    $errorMessage = [];

    // Request all input
		$data = \Input::all();

    \Input::flash();

    if (! isset($data['userId'])) {
      $data['userId'] = 0;
    }

    // Explode purchaseId
    $purchasedId = explode(',', $data['purchasedId']);

    // Explode quantity
    $ticketQuantity = explode(',', $data['qty']);

    // For each purchaseId set the tickets array
    for ($i=0; $i <= count($purchasedId); $i++) {
      if (isset($purchasedId[$i])) {
        $tickets[] = ['id' => $purchasedId[$i], 'quantity' => $ticketQuantity[$i]];
      }
    }

    // Set TicketsInventories
    $data['ticketInventories'] = $tickets;

    // Instanciate ApiHelper
		$api = new ApiHelper;

    // Set quantity
    $quantity = $data['totalQuantity'];

    $chargeUrl = 'ticketsorders/purchase';

    if (\Auth::check()) {
      $chargeUrl .= '?auth_user_id=' . \Auth::user()->id;
    }

		// Charge the user
		$charge = $api->store($chargeUrl, $data);

    // if the charge is successful
		if ($charge['success'] === false) {

      // If error is string
      if (! is_array($charge['error'])) {

        // Redirect back with fail message and input
        return Redirect::back()
          ->with(['fail_message' => $charge['error']])
          ->withInput();
      } else {

        // Set all errors to one level array
        foreach ($charge['error'] as $key => $error) {
          for ($i=0; $i < count($charge['error']); $i++) {
            if (isset($error[$i])) {
              $errors[] = $error[$i];
            }
          }
        }

        // Redirect back with fail message and input
        return Redirect::back()
          ->with(['updateErrors' => $errors])
          ->withInput();
      }

		} else {

      // Redirect to the ticket confirmation page with accompanying data
			return \Redirect::route('tickets.confirmation', $slug)->with(['quantity' => $quantity]);

		}
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function confirmation($slug)
	{
		// \Response::header('Cache-Control', 'no-store, no-cache, must-revalidate, post-check=0, pre-check=0');

		$api = new ApiHelper;

    $url = 'events/' . $slug . '?with[]=photos&with[]=TicketsOrder';

    if (\Auth::check()) {
      $url .= '&auth_user_id=' . \Auth::user()->id;
    }

		// Pull in single event with photos
		$events = $api->index($url);

		// Define events
		$events = $events['data']['event'];

		if(\Session::has('quantity')){

			foreach($events['ticketsOrder'] as $ticketOrder) {
		    if ($ticketOrder === end($events['ticketsOrder'])){
		    	$email = $ticketOrder['email'];
		      $amount =  $ticketOrder['amount'];
		      $total = $ticketOrder['amount'];
		    }
		  }

			$quantity = \Session::get('quantity');

			return \View::make('events.confirmation')->with(array(
				'events' => $events,
				'quantity' => $quantity,
				'amount' => $amount
			));

		} else {
			return \Redirect::route('home')->with('message', 'Confirmation page no longer available. Check your email for recipt and tickets');
		}

	}


  /**
   * Display the reservation confirmation page.
   *
   * @param  $slug returns title of specified event
   * @param  $reserve holds name of reservation
   * @param  $confirmation holds confirmation message
   *
   * @return Returns reservation confirmation for all reservations made and returns view
   */
  public function showReservationConfirmation($slug)
  {
    // Instantiate the APIHelper
    $api = new \App\Helpers\ApiHelper;

    if(\Session::has('confirmation')){

      $url = 'events/' . $slug . '?with[]=photos&with[]=TicketsOrder';

      if (\Auth::check()) {
        $url .= '&auth_user_id=' . \Auth::user()->id;
      }

      // Pull in single event with photos
      $events = $api->show($url, '');

      // Define events
      $events = $events['data']['event'];

      // Set default photo
      $defaultPhoto = null;

      // Use featured photo if available
      if (isset($events['featuredPhoto'])) {
        $defaultPhoto = $events['featuredPhoto'];
      }

      // Holds name of reservation
      $reserve = \Session::get('reserveInfo');

      // Holds returned success response for confirmation info
      $confirmation = \Session::get('confirmation');

      // For each reservation made, pull name and confirmation message
      for ($n = 0; $n < count($reserve); $n++){

      $reservationConfirmation []= ['name' => $reserve[$n]['name'], 'confirmation' => $confirmation[$n]['confirmation']];

      }

      return \View::make('events.reservation-confirmation')->with(array(
        'events' => $events,
        'defaultPhoto' => $defaultPhoto,
        'reservationConfirmation' => $reservationConfirmation
      ));

    } else {

      return \Redirect::back()->with('message', 'Something went wrong.');

    }

  }

	public function qr($hash)
	{
		$contents = \QrCode::format('png')->size(300)->generate('http://local.suaray.com/');
		$response = \Response::make($contents, 200);
		$response->header('Content-Type', 'image/png');
		return $response;
	}

}
