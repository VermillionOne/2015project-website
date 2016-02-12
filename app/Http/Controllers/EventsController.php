<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Pagination\Paginator;
use App\Helpers\ViewHelper;
use App\Helpers\ApiHelper;
use App\StripeSubscription;

use Request;

use Illuminate\Http\Response;
use Redirect;

class EventsController extends Controller {

  public function times($slug)
  {
    // Set a default
    $times = [];

    // Instantiate the APIHelper
    $api = new \App\Helpers\ApiHelper;

    $url = 'events?filter[and][][slug]=' . $slug . '&with[]=times&with[]=nextEvent';

    if (\Auth::check()) {
      $url .= '&auth_user_id=' . \Auth::user()->id;
    }

    // Grab a single event by the slug
    $times = $api->index($url);

    return $times;
  }

  /**
   *
   * Grabs a single event for show method, if no event found or empty hash, will show 404
   * Returns all information needed for show method
   *
   */
  protected function setEvent($isAccessedByPrivateLink, $appendAuthUserId, $slug, $api)
  {
      // Set private hash if available
      $routeParams = app()->router->getCurrentRoute()->parameterNames();

      $hash = in_array('hash', $routeParams) ? $slug : null;

      // Abort if we are public and there is no hash or its empty
      if ($isAccessedByPrivateLink && empty($hash)) {

        // Show the not found page
        abort(404);

      }

      if ($isAccessedByPrivateLink) {

        // Grab a single event by the hash
        $events = $api->index('events?' . $appendAuthUserId . 'filter[and][][privateHash]=' . $hash . '&with[]=photos&with[]=reviews.user&with[]=times&with[]=comments.user&with[]=user&with[]=attendees.user&with[]=ticketsInventory&with[]=tags&with[]=nextEvent');

      } else {

        // Grab a single event by the slug
        $events = $api->index('events?' . $appendAuthUserId . 'filter[and][][slug]=' . urlencode($slug) . '&with[]=photos&with[]=reviews.user&with[]=times&with[]=comments.user&with[]=user&with[]=attendees.user&with[]=ticketsInventory&with[]=tags&with[]=nextEvent');
      }

      if (empty($events['data']['events'][0])) {

        return abort(404);
      } else {

      $events = $events['data']['events'][0];
      return $events;

      }

  }

  /**
   *
   *  Returns attendee list and attendee information to show attendee count and user information
   *  for show method.
   *
   */
  protected function setAttendees($appendAuthUserId, $slug, $api)
  {
    $attendeeInfo = [];
    $attendeeList = [];

    // Grabs number of attendees to event and attendees
    $attendeeList = $api->index('events/' . $slug . '?' . $appendAuthUserId . '&fields[]=slug&fields[]=attendeesCount&fields[]=attendeesAndFriends&with[]=attendeesAndFriends.user');

    $attendeeList = $attendeeList['data']['event'];

    foreach ($attendeeList['attendeesAndFriends'] as $user) {

      $attendeeInfo[$user['userId']] = $user;

    }

    return [
    'attendeeList' => $attendeeList,
    'attendeeInfo' => $attendeeInfo
    ];

  }

  /**
   *
   * Unsets disabled tickets from show method
   *
   */
  protected function unsetDisabledTickets($events)
  {
    if (!empty($events['ticketsInventory'])) {

      // Unset each disabled Ticket
      foreach($events['ticketsInventory'] as $index => $enabledTickets) {

        if ($enabledTickets['isEnabled'] !== true) {

          // Caught a disbaled
          unset($events['ticketsInventory'][$index]);

        }

      }

      return $events['ticketsInventory'];

    }
  }

  /**
   *
   * Sets logged in users friends if any for show method
   *
   */
  protected function setFriends($api)
  {
      $friends = $api->index('users/' . \Auth::user()->id . '?with[]=friends&with[]=ticketInventories&with[]=stripeManagedAccounts&auth_user_id=' . \Auth::user()->id);

      $friends = isset($friends['data']['user']['friends']) ? $friends['data']['user']['friends'] : [];

      // Strip fields needed for friends list
      $friends = ApiHelper::stripFieldsCollection($friends, [
        'id',
        'email',
        'username',
        'firstName',
        'lastName',
        'avatar',
        'pivot'
      ]);

      return $friends;
   }
  /**
   *
   * Sets related events for currently viewed event, unsets event matching same id
   * for show method.
   *
   */
  protected function setRelatedEvents($events, $api)
  {
    $relatedEvents = [];

    // Grabs tags specific to event
    $tags = isset($events['tags']) ? $events['tags'] : [];

    // Category1 set for related events to show
    $category = isset($events['category1']) ? $events['category1'] : null;

    // If category is null, pull first available tag
    $category = $category === null && isset($tags[0]) ? $tags[0]['tag'] : $category;

    if ($category !== null) {

      // Api end point
      $endpoint = ''
      . 'events?filter[and][][times.end]=date&search=' . $category
      . '&with[]=tags&with[]=photos'
      . '&filter[and][][is_published]=1&filter[and][][is_banned]=0&filter[and][][is_private]=0'
      . '&fields[]=slug&fields[]=category1&fields[]=id&fields[]=title&fields[]=photos&fields[]=tags&fields[]=featuredPhoto'
      ;

      if (\Auth::check()) {
        $endpoint .= '&auth_user_id=' . \Auth::user()->id;
      }

      // Get events related to event currently displayed
      $relatedEvents = $api->index($endpoint);
      $relatedEvents = isset($relatedEvents['data']['events']) ? $relatedEvents['data']['events'] : [];

      // Indexes and iterates through returned events
      foreach ($relatedEvents as $index => $relatedEvent){

        // If event has no photos or featuedPhoto
        if ( empty($relatedEvent['photos']) && $relatedEvent['featuredPhoto'] === null){
          // Remove from array
          unset($relatedEvents[$index]);
        }

        // If viewed event id matches a returned event, removes from list
        if ($relatedEvent['id'] === $events['id']){

          unset($relatedEvents[$index]);

        }

      }

    }

    return $relatedEvents;

  }

  /**
   *
   * Sets ticket tyoe as reservation or ticket, displays accordingly if exists
   *
   */
  protected function setTicketType($events)
  {
      // Set initial value of tickets and reservations to empty array
      $tickets = [];
      $reservations = [];

      if (!empty($events['ticketsInventory'])) {

        // Iterate through tickets inventory
        foreach ($events['ticketsInventory'] as $ticketType) {

          // If tickets have been created, reservations is set to empty array and not displayed in event detail
          if ($ticketType['isReservation'] === false) {

            $reservations = [];

            $tickets = $events['ticketsInventory'];

          } else {

            // If reservations have been created, tickets is set to empty array and not displayed in event detail
            $tickets = [];

            $reservations = $events['ticketsInventory'];

          }

        }

      }

      return [
      'tickets' => $tickets,
      'reservations' => $reservations
      ];

  }

  /**
   *
   * Holds all meta data for show method
   *
   */
  protected function setMetaData($events)
  {
      // Grabs event title for specific event
      $metaTitle = $events['title'];

      // Setting eventTags to empty string
      $metaKeywords = '';

      if (! empty($tags)) {
        // Pulls tags only from array
        $metaKeywords = array_column($tags, 'tag');

        // Makes array into string to show in view
        $metaKeywords = implode(",", $metaKeywords);
      }

      $metaDescription = $events['description'];

      // Splitting description to only display 25 words
      $metaDescription = ViewHelper::limitTextByWords($metaDescription, 25);

      return [
        'metaTitle' => $metaTitle,
        'metaKeywords' => $metaKeywords,
        'metaDescription' => $metaDescription
      ];

  }

   /**
   * Show a single event
   * @param  [type] $slug [description]
   * @return [type]       [description]
   */
  public function show($slug)
  {
    // Grab the requestersId
    $setFriendCookie = \Input::get('suarayrequestfrom');

    // Instantiate the APIHelper
    $api = new \App\Helpers\ApiHelper;

    $id = null;

    // Check if user is logged in
    $appendAuthUserId = \Auth::check() ? 'auth_user_id=' . \Auth::user()->id . '&' : null;

    // Is this event being accessed via a private link
    $isAccessedByPrivateLink = empty($hash) ? false : true;

    // Sets private hash if available
    // Aborts if we are public and there is no hash or its empty
    // Sets events variable once validated
    $events = $this->setEvent($isAccessedByPrivateLink, $appendAuthUserId, $slug, $api);

    $eventId = isset($events['id']) ? $events['id'] : null;

    $address = urlencode($events['address1'] . ", " . $events['city'] . ", " . $events['state']);

    // Sets attendees for idividual event with count and user information
    $attendees = $this->setAttendees($appendAuthUserId, $slug, $api);
    $attendeeInfo = $attendees['attendeeInfo'];
    $attendeeList = $attendees['attendeeList'];

    $events['ticketsInventory'] = $this->unsetDisabledTickets($events);

    // QR code hash
    $hash = '$2y$10$TfnEb9qbeS4hXu.EsE.yM.nWe6uRP3XQVPXwevQeJCaDd.Pfbgt1i';

    // Sets admin check to false
    $isAdmin = false;

    $attendingStatus = [];
    $ownedEvent = [];

    // Set variables for the different privacy levels
    $shareableLink = $events['meta']['private']['shareableLink'];
    $friendsOnly = $events['meta']['private']['friendsOnly'];
    $byInvite = $events['meta']['private']['byInvite'];

    // create a privacy array
    $private = [$shareableLink, $friendsOnly, $byInvite];

    // Check if user is signed in
    if (\Auth::check()) {

      // Find if the user is attending
      $attendingStatus = $api->show('events', urlencode($slug) . '?with[]=times&auth_user_id=' . \Auth::user()->id);

      $attendingStatus = isset($attendingStatus['data']['event']['auth']['isAttending']) ? $attendingStatus['data']['event']['auth']['isAttending'] : null;

      $isAdmin = $events['userId'] == \Auth::user()->id ? true : $isAdmin;

      $friends = $this->setFriends($api);

      $api = new \App\Helpers\ApiHelper;

      // If the users ID matches the UserId of the event
      if (\Auth::user()->id == $events['userId']) {

      // Otherwise show privacy options
      } else {

        // While the one of the 3 privacy settings are true
        while (list($key, $value) = each($events['meta']['private'])) {

          if ($value === true) {
            $private = true;

            // If the privacy setting is shareableLink
            if ($private === true && $key === 'shareableLink' ) {

              // Set the privacy message
              $privateMessage = 'This event is private and only viewed with a private link';

              // Return view
              return \View::make('events.private')->with('privateMessage', $privateMessage);

            // Else if the privacy setting is friends only
            }elseif ($private === true && $key === 'friendsOnly') {

              $friendIds = [];

              foreach ($friends as $key => $friendId) {
                $friendIds[] = $friendId['pivot']['friendId'];
              }

              // If the creater of the event are friends with the logged in user
              if (in_array($events['userId'], $friendIds)) {

              // Else continue on
              } else {

                // Set the privacy message
                $privateMessage = 'This event is private';

                // Return view
                return \View::make('events.private')->with('privateMessage', $privateMessage);
              }

            // If the privacy setting is by invite only
            }elseif ($private === true && $key === 'byInvite') {

              // Set privacy message
              $privateMessage = 'This event is private and only viewed by an invite';

              // Return view
              return \View::make('events.private')->with('privateMessage', $privateMessage);

            }
          }
        }
      }

    } else {

      // If the user is not logged in
      if (! \Auth::check() && $private === true)
      {
        // Return view login with the title of the event and the slug
        return \Redirect::route('login')->with('warning_message', 'In order to view ' . $events['title'] . ' you must log in')->with('previous_page', $events['slug']);
      }

      // While the one of the 3 privacy settings are true
      while (list($key, $value) = each($events['meta']['private'])) {
        if ($value === true) {
          $private = true;

          // If the privacy setting is shareableLink
          if ($private === true && $key === 'shareableLink' ) {

            // Set the privacy message
            $privateMessage = 'This event is private and only viewed with a private link';

            // Return view
            return \View::make('events.private')->with('privateMessage', $privateMessage);

          // Else if the privacy setting is friends only
          }elseif ($private === true && $key === 'friendsOnly') {

            // Set the privacy message
            $privateMessage = 'This event is private';

            // Return view
            return \View::make('events.private')->with('privateMessage', $privateMessage);

          // If the privacy setting is by invite only
          }elseif ($private === true && $key === 'byInvite') {

            // Set the privacy message
            $privateMessage = 'This event is private and only viewed by an invite';

            // Return view
            return \View::make('events.private')->with('privateMessage', $privateMessage);

          }
        }
      }

    }

    if (!empty($events)) {

      // Set default photo
      $defaultPhoto = null;

      // Use featured photo if available
      if (isset($events['featuredPhoto'])) {
        $defaultPhoto = $events['featuredPhoto'];
      }

      // Use first photo if available and featured photo is not available
      if ($defaultPhoto == null && isset($events['photos'][0])) {
        $defaultPhoto = $events['photos'][0];
      }

      // Sets events meta data
      $meta = $this->setMetaData($events);
        $metaTitle = $meta['metaTitle'];
        $metaDescription = $meta['metaDescription'];
        $metaKeywords = $meta['metaKeywords'];

      // Set ticket type to ticket or reservation
      $ticketType =  $this->setTicketType($events);
        $tickets = $ticketType['tickets'];
        $reservations = $ticketType['reservations'];

      // Shows related events
      $relatedEvents = $this->setRelatedEvents($events, $api);

      // Set times default
      $times = [];

      // Set times Id default
      $eventTimeIdSelected = null;

      // Set times Id default
      $timesId = [];

      // Times array build and set
      foreach ($events['times'] as $key => $value) {

        // Set key if set to null
        if ($eventTimeIdSelected === null) {
          $eventTimeIdSelected = $value['id'];
        }

        $key = $value['id'];
        $value = date('M d @ g:ia', strtotime($value['start']));

        $times[$key] = $value;
      }

      // View data
      $data = [
        'address' => $address,
        'attendeeInfo' => $attendeeInfo,
        'attendeeList' => $attendeeList,
        'eventId' => $eventId,
        'events' => $events,
        'times' => $times,
        'tickets' => $tickets,
        'reservations' => $reservations,
        'eventTimeIdSelected' => $eventTimeIdSelected,
        'timesId' => $timesId,
        'friends' => isset($friends) ? $friends : [],
        'defaultPhoto' => $defaultPhoto,
        'relatedEvents' => $relatedEvents,
        'hash' => $hash,
        'metaKeywords' => $metaKeywords,
        'attendingStatus' => $attendingStatus,
        'ownedEvent' => $ownedEvent,
        'metaDescription' => $metaDescription,
        'metaTitle' => $metaTitle,
        'isAdmin' => $isAdmin,
        'id' => $id,
        'slug' => $slug,
        'isAccessedByPrivateLink' => $isAccessedByPrivateLink,
      ];

      // Check if user is signed in
      if (\Auth::check()) {

        $user = $api->index('users/' . \Auth::user()->id . '?with[]=ticketInventories&with[]=stripeManagedAccounts&auth_user_id=' . \Auth::user()->id);
        $user = $user['data']['user'];

      }

      // The view
      $view = \View::make('events.show', $data)->with([
        'data' => $data,
        'user' => isset($user) ? $user : null,
      ]);

      // Instanciate response
      $response = new Response($view);

        // Return response
      return $response->withCookie(\Cookie::make('suarayrequestfrom', $setFriendCookie, 43829.1))
        ->withCookie(\Cookie::forget('purchaseCookieData'));
    }

  }


  public function showImagesUpdate($id)
  {
    // Instantiate the APIHelper
    $api = new \App\Helpers\ApiHelper;

    $eventsUrl = 'events/' . $id;

    $settingsUrl = 'settings/upload';

    if (\Auth::check()) {
      $eventsUrl .= '?auth_user_id=' . \Auth::user()->id;

      $settingsUrl .= '?auth_user_id=' . \Auth::user()->id;
    }

    // Show event associated the ID.
    $events = $api->index($eventsUrl);

    if (! isset($events['data']['event'])) {
      abort('404');
    }

    $event = $events['data']['event'];



    // Get upload settings for evaporate.js
    $uploadSettings = $api->index($settingsUrl);
    $uploadSettings = isset($uploadSettings['data']) ? $uploadSettings['data'] : [];

    return \View::make('events.update-images')->with([
      'event' => $event,
      'uploadSettings' => $uploadSettings,
    ]);
  }

  /**
   * Show the create event page
   * @param  [type] $id [description]
   * @return [type]     [description]
   */
  public function showEventUpdate($id)
  {
    $tagNames = [];

    // Instantiate the APIHelper
    $api = new \App\Helpers\ApiHelper;

    $url = 'events/' . $id . '?with[]=tags';

    if (\Auth::check()) {
      $url .= '&auth_user_id=' . \Auth::user()->id;
    }

    // Show event associated the ID.
    $events = $api->index($url);

    // load tags to show
    $tags = $events['data']['event']['tags'];

    $event = $events['data']['event'];

    $slug = $events['data']['event']['slug'];

    $tagNames = [];
    foreach ($tags as $tag) {
      $tagNames[] = $tag['tag'];
    }

    $title = $events['data']['event']['title'];

    $stateList = [
      'Alabama' => "Alabama",
      'Alaska' => "Alaska",
      'Arizona' => "Arizona",
      'Arkansas' => "Arkansas",
      'California' => "California",
      'Colorado' => "Colorado",
      'Connecticut' => "Connecticut",
      'Delaware' => "Delaware",
      'District Of Columbia' => "District Of Columbia",
      'Florida' => "Florida",
      'Georgia' => "Georgia",
      'Hawaii' => "Hawaii",
      'Idaho' => "Idaho",
      'Illinois' => "Illinois",
      'Indiana' => "Indiana",
      'Iowa' => "Iowa",
      'Kansas' => "Kansas",
      'Kentucky' => "Kentucky",
      'Louisiana' => "Louisiana",
      'Maine' => "Maine",
      'Maryland' => "Maryland",
      'Massachusetts' => "Massachusetts",
      'Michigan' => "Michigan",
      'Minnesota' => "Minnesota",
      'Mississippi' => "Mississippi",
      'Missouri' => "Missouri",
      'Montana' => "Montana",
      'Nebraska' => "Nebraska",
      'Nevada' => "Nevada",
      'New Hampshire' => "New Hampshire",
      'New Jersey' => "New Jersey",
      'New Mexico' => "New Mexico",
      'New York' => "New York",
      'North Carolina' => "North Carolina",
      'North Dakota' => "North Dakota",
      'Ohio' => "Ohio",
      'Oklahoma' => "Oklahoma",
      'Oregon' => "Oregon",
      'Pennsylvania' => "Pennsylvania",
      'Rhode Island' => "Rhode Island",
      'South Carolina' => "South Carolina",
      'South Dakota' => "South Dakota",
      'Tennessee' => "Tennessee",
      'Texas' => "Texas",
      'Utah' => "Utah",
      'Vermont' => "Vermont",
      'Virginia' => "Virginia",
      'Washington' => "Washington",
      'West Virginia' => "West Virginia",
      'Wisconsin' => "Wisconsin",
      'Wyoming' => "Wyoming"
      ];

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
      'yearly' => 'Yearly'
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

    if (\Auth::user()->id != $events['data']['event']['userId']) {
      abort(401);
    }

    $updateErrors = \Session::get('updateErrors');

    return \View::make('events.update-event')->with([
      'updateErrors' => $updateErrors,
      'events' => $events,
      'event' => $event,
      'slug' => $slug,
      'title' => $title,
      'step' => 'create_event',
      'stateList' => $stateList,
      'timeList' => $timeList,
      'repeatList' => $repeatList,
      'repeatEveryList' => $repeatEveryList,
      'stepsLocked' => $events === null,
      'tagConcat' => implode(',', $tagNames)
    ]);

  }

  /**
   * [doUpdate description]
   * @return [type] [description]
   */
  public function doEventUpdate($id)
  {
    $shareableLink = 0;
    $friendsOnly = 0;
    $byInvite = 0;

    $data = Request::all();

    \Input::flash();

    if (isset($data['data']['meta']['private']['shareableLink'])) {
      $shareableLink = $data['data']['meta']['private']['shareableLink'];
    }

    if (isset($data['data']['meta']['private']['friendsOnly'])) {
       $friendsOnly = $data['data']['meta']['private']['friendsOnly'];
    }

    if (isset($data['data']['meta']['private']['byInvite'])) {
      $byInvite = $data['data']['meta']['private']['byInvite'];
    }

    if (!isset($data['isAge0'])) {
      $data['isAge0'] = '0';
    }

    if (!isset($data['isAge13'])) {
      $data['isAge13'] = '0';
    }

    if (!isset($data['isAge16'])) {
      $data['isAge16'] = '0';
    }

    if (!isset($data['isAge18'])) {
      $data['isAge18'] = '0';
    }

    if (!isset($data['isAge21'])) {
      $data['isAge21'] = '0';
    }

    // if (isset($data['meta']['tweets'], $data['meta']['ticketPric'], $data['meta']['ticketPric'])) {
    $data['meta'] = json_encode([
      'private' => [
        'shareableLink' => (bool) $shareableLink,
        'friendsOnly' => (bool) $friendsOnly,
        'byInvite' => (bool) $byInvite,
      ],
    ]);

    // Instantiate the APIHelper
    $api = new \App\Helpers\ApiHelper;

    $updateUrl = 'events/' . $id;

    if (\Auth::check()) {
      $updateUrl .= '?auth_user_id=' . \Auth::user()->id;
    }

    // Show event associated the ID.
    $event = $api->update($updateUrl, '', $data);

    if ($event['success'] !== true) {

      foreach ($event['error'] as $key => $error) {
        for ($i=0; $i < count($event['error']); $i++) {
          if (isset($error[$i])) {
            $errors[] = $error[$i];
          }
        }
      }

      return Redirect::route('events.update-event', $id)
        ->with(['updateErrors' => $errors])
        ->withInput();

      if (isset($event['error']['tags'][0])) {
        return Redirect::back()
          ->with('warning_message', $event['error']['tags'][0])
          ->withInput();
      }

    }

    $event = $event['data']['resource'];

    $url = 'attendees';

    if (\Auth::check()) {
      $url .= '?auth_user_id=' . \Auth::user()->id;
    }

    // Show event associated the ID.
    $eventRsvp = $api->store($url, $data);

    if (isset($data['newImage'])) {

      // Encodes message to get sent with url
      $message = urlencode('Please wait while your photos are being added');

        // return Route::get('pages.loader', 302)->header('Location', (string)$url);
       $url = "time=4000&url=/account/". $event['slug'] ."/gallery&message=" . $message;

       return Redirect::to(route('pages.loader', (string)$url));

    } elseif (isset($data['data']['meta']['private'])) {

      // Return back with success message
      return Redirect::back()
        ->with('success_message','Event Updated Successfully !');

    } else {

      // Title has been updated so follow it
      return Redirect::route('events.update-event', ['id' => $event['id']])
        ->with('success_message','Event Updated Successfully !');

    }

  }

  /**
   * Grabs the value of published or unpublished and updates view accordingly
   *
   * @param  $id Event id
   * @param  $data Returns boolean only for published event
   * @return bool Will determine if published or unpublished event
   */
  public function doEventPublish($id)
  {
    $data = Request::all();

    // Instantiate the APIHelper
    $api = new \App\Helpers\ApiHelper;

    $url = 'events/' . $id;

    if (\Auth::check()) {
      $url .= '?auth_user_id=' . \Auth::user()->id;
    }

    // Show event associated the ID.
    $event = $api->update($url, '' ,$data);

    if ($event['success'] === true) {
      return Redirect::back()->with('success_message', 'Event updated successfully.');

    } else {

      return Redirect::back()->with('error', 'Error, Could not find resource.');
    }
  }

  /**
   * Deletes selected event
   *
   * @param  $id Event id
   *
   * @return bool Will determine if published or unpublished event
   */
  public function doEventDelete($id)
  {
    // Instantiate the APIHelper
    $api = new \App\Helpers\ApiHelper;

    $url = 'events/' . $id;

    if (\Auth::check()) {
      $url .= '?auth_user_id=' . \Auth::user()->id;
    }

    // Show event associated the ID.
    $event = $api->destroy($url, '');

    if ($event['success'] === true) {
      return \Redirect::route('dashboard')->with('success_message', 'Event succesfully deleted.');
    } else {

      return Redirect::back()->with('error', 'Error, Could not find resource.');
    }
  }

  /**
   * Enables or disables map in to show in event detail
   *
   * @param  $id Event id
   * @param  $data Returns boolean only for map
   * @return bool Will determine if map enabled or disabled for event
   */
  public function doUpdateMap($id)
  {
    $data = Request::all();

    \Input::flash();

    $data['meta'] = json_encode([
      'map' => [
        'enabled' => isset($data['meta']['map']['enabled']) ? (bool) $data['meta']['map']['enabled'] : true,
      ],
    ]);

    // Instantiate the APIHelper
    $api = new \App\Helpers\ApiHelper;

    $url = 'events/' . $id;

    if (\Auth::check()) {
      $url .= '?auth_user_id=' . \Auth::user()->id;
    }

    // Updates true or false value for map
    $event = $api->update($url, '', $data);

    if ($event['success'] === true) {
      return Redirect::back()->with('success_message', 'Event updated successfully.');

    } else {

      return Redirect::back()->with('error', 'Error, Could not find resource.');
    }
  }

  /**
   * Enables or disables rsvp in to show in event detail
   *
   * @param  $id Event id
   * @param  $data Returns boolean only for rsvp
   * @return bool Will determine if rsvp enabled or disabled for event
   */
  public function doUpdateRsvp($id)
  {
    $data = Request::all();

    \Input::flash();

    $data['meta'] = json_encode([
      'rsvp' => [
        'enabled' => isset($data['meta']['rsvp']['enabled']) ? (bool) $data['meta']['rsvp']['enabled'] : true,
      ],
    ]);

    // Instantiate the APIHelper
    $api = new \App\Helpers\ApiHelper;

    $url = 'events/' . $id;

    if (\Auth::check()) {
      $url .= '?auth_user_id=' . \Auth::user()->id;
    }

    // Updates true or false value for rsvp
    $event = $api->update($url, '', $data);

    if ($event['success'] === true) {
      return Redirect::back()->with('success_message', 'Event updated successfully.');

    } else {

      return Redirect::back()->with('error', 'Error, Could not find resource.');
    }
  }

  /**
   * Enables or disables rsvp in to show in event detail
   *
   * @param  $id Event id
   * @param  $data Returns boolean only for comments
   * @return bool Will determine if comments enabled or disabled for event
   */
  public function doUpdateComments($id)
  {
    $data = Request::all();

    \Input::flash();

    $data['meta'] = json_encode([
      'comments' => [
        'enabled' => isset($data['meta']['comments']['enabled']) ? (bool) $data['meta']['comments']['enabled'] : true,
      ],
    ]);

    // Instantiate the APIHelper
    $api = new \App\Helpers\ApiHelper;

    $url = 'events/' . $id;

    if (\Auth::check()) {
      $url .= '?auth_user_id=' . \Auth::user()->id;
    }

    // Updates true or false value for rsvp
    $event = $api->update($url, '', $data);

    if ($event['success'] === true) {
      return Redirect::back()->with('success_message', 'Event updated successfully.');

    } else {

      return Redirect::back()->with('error', 'Error, Could not find resource.');
    }
  }

  /**
   * Enables or disables rsvp in to show in event detail
   *
   * @param  $id Event id
   * @param  $data Returns boolean only for reviews
   * @return bool Will determine if reviews enabled or disabled for event
   */
  public function doUpdateReviews($id)
  {
    $data = Request::all();

    \Input::flash();

    $data['meta'] = json_encode([
      'reviews' => [
        'enabled' => isset($data['meta']['reviews']['enabled']) ? (bool) $data['meta']['reviews']['enabled'] : true,
      ],
    ]);

    // Instantiate the APIHelper
    $api = new \App\Helpers\ApiHelper;

    $url = 'events/' . $id;

    if (\Auth::check()) {
      $url .= '?auth_user_id=' . \Auth::user()->id;
    }

    // Updates true or false value for rsvp
    $event = $api->update($url, '', $data);

    if ($event['success'] === true) {
      return Redirect::back()->with('success_message', 'Event updated successfully.');

    } else {

      return Redirect::back()->with('error', 'Error, Could not find resource.');
    }
  }

	/**
	 * Show the create event page
	 * @param  [type] $id [description]
	 * @return [type]     [description]
	 */
	public function showCreate($id = null)
	{
		$events = null;
		$tagNames = [];

    // Instantiate the APIHelper
    $api = new \App\Helpers\ApiHelper;

    $tagUrl = 'tags?filter[and][][is_category]=1';

    if (\Auth::check()) {
      $tagUrl .= '&auth_user_id=' . \Auth::user()->id;
    }

    // Event categories
    $categories = $api->index($tagUrl);
    $categories = $categories['data']['tags'];

    foreach ($categories as $key => $value){
      $categories[$key] = $value['tag'];
    }

    // Check if user is signed in
    if (\Auth::check()) {

      // Gather user information
      $user = $api->show('users', \Auth::user()->id . '?with[]=stripeSubscriptions&with[]=ticketInventories&with[]=stripeManagedAccounts&auth_user_id=' . \Auth::user()->id);
      $user = $user['data']['user'];

    }

    // Default value
    $userCard = null;

    // If there is a card available
    if (isset($user['stripeSubscriptions'][0])) {

      // Set the card ID
      $userCard = $user['stripeSubscriptions'][0]['cardId'];
    }

		if ($id !== null) {

      $eventUrl = 'events/' . $id . '?with[]=tags';

      if (\Auth::check()) {
        $eventUrl .= '&auth_user_id=' . \Auth::user()->id;
      }

		  // Show event associated the ID.
			$events = $api->show($eventUrl, '');

			// load tags to show
			$tags = $events['data']['event']['tags'];

			foreach ($tags as $tag) {
				$tagNames[] = $tag['tag'];
			}
		}
		$stateList = [
      'Alabama' => "Alabama",
      'Alaska' => "Alaska",
      'Arizona' => "Arizona",
      'Arkansas' => "Arkansas",
      'California' => "California",
      'Colorado' => "Colorado",
      'Connecticut' => "Connecticut",
      'Delaware' => "Delaware",
      'District Of Columbia' => "District Of Columbia",
      'Florida' => "Florida",
      'Georgia' => "Georgia",
      'Hawaii' => "Hawaii",
      'Idaho' => "Idaho",
      'Illinois' => "Illinois",
      'Indiana' => "Indiana",
      'Iowa' => "Iowa",
      'Kansas' => "Kansas",
      'Kentucky' => "Kentucky",
      'Louisiana' => "Louisiana",
      'Maine' => "Maine",
      'Maryland' => "Maryland",
      'Massachusetts' => "Massachusetts",
      'Michigan' => "Michigan",
      'Minnesota' => "Minnesota",
      'Mississippi' => "Mississippi",
      'Missouri' => "Missouri",
      'Montana' => "Montana",
      'Nebraska' => "Nebraska",
      'Nevada' => "Nevada",
      'New Hampshire' => "New Hampshire",
      'New Jersey' => "New Jersey",
      'New Mexico' => "New Mexico",
      'New York' => "New York",
      'North Carolina' => "North Carolina",
      'North Dakota' => "North Dakota",
      'Ohio' => "Ohio",
      'Oklahoma' => "Oklahoma",
      'Oregon' => "Oregon",
      'Pennsylvania' => "Pennsylvania",
      'Rhode Island' => "Rhode Island",
      'South Carolina' => "South Carolina",
      'South Dakota' => "South Dakota",
      'Tennessee' => "Tennessee",
      'Texas' => "Texas",
      'Utah' => "Utah",
      'Vermont' => "Vermont",
      'Virginia' => "Virginia",
      'Washington' => "Washington",
      'West Virginia' => "West Virginia",
      'Wisconsin' => "Wisconsin",
      'Wyoming' => "Wyoming"
      ];

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
      'yearly' => 'Yearly'
    ];

    $events = $events['data']['event'];

    $uploadUrl = 'settings/upload';

    $timezoneUrl = 'collections/timezones?limit=200&fields[]=id&fields[]=zoneName';

    if (\Auth::check()) {
      $uploadUrl .= '?auth_user_id=' . \Auth::user()->id;

      $timezoneUrl .= '&auth_user_id=' . \Auth::user()->id;
    }

    // Get upload settings for evaporate.js
    $uploadSettings = $api->index($uploadUrl);
    $uploadSettings = isset($uploadSettings['data']) ? $uploadSettings['data'] : [];

    // Get list of timezones
    $timezoneList = [];
    $timezones = $api->index($timezoneUrl);
    $timezones = isset($timezones['data']['timezones']) ? $timezones['data']['timezones'] : [];

    // Create timezone list for select input
    foreach ($timezones as $timezone) {

      // Set time_zone_id
      $timeZoneId = $timezone['id'];

      // Set label
      $timezoneList[$timeZoneId] = $timezone['zoneName'];
    }

		return \View::make('events.create')->with([
      'categories' => $categories,
      'event' => $events,
			'step' => 'create_event',
			'stateList' => $stateList,
      'timeList' => $timeList,
      'repeatList' => $repeatList,
			'stepsLocked' => $events === null,
			'tagConcat' => implode(',', $tagNames),
      'uploadSettings' => $uploadSettings,
      'userCard' => $userCard,
      'user' => isset($user) ? $user : null,
      'timezoneList' => $timezoneList,
		]);
	}

  public function doComments($id)
  {
    // Instantiate the APIHelper
    $api = new \App\Helpers\ApiHelper;

    // Request comments data from the db
    $data = Request::all();

    // Get the logged in users ID
    $data['userId'] = \Auth::user()->id;

    // Store the comment in the database
    $comment = $api->store('comments?auth_user_id=' . \Auth::user()->id, $data);

    // Redirect back to the events page and comment section
    if ($comment) {
      return Redirect::to(\URL::previous() . "#comments");

    }

  }

	/**
	 * Create the event
	 * @param  string $id ID of the event if doing an update
	 * @return view     View with session message
	 */
	public function doCreate($id = null)
	{

    // Set defailt meta
    $data['meta'] = [];

    $data = Request::all();

    \Input::flash();

    // Instantiate the APIHelper
    $api = new \App\Helpers\ApiHelper;

    $userEmail = ['email' => \Auth::user()->email];

    if (isset($data['billing'])) {
      $data['billing'] = array_merge($data['billing'], $userEmail);
    }

    if (!isset($data['isIndoor'])) {
      $data['isIndoor'] = '0';
    }

    if (!isset($data['isOutdoor'])) {
      $data['isOutdoor'] = '0';
    }

    if (!isset($data['isAge0'])) {
      $data['isAge0'] = '0';
    }

    if (!isset($data['isAge13'])) {
      $data['isAge13'] = '0';
    }

    if (!isset($data['isAge16'])) {
      $data['isAge16'] = '0';
    }

    if (!isset($data['isAge18'])) {
      $data['isAge18'] = '0';
    }

    if (!isset($data['isAge21'])) {
      $data['isAge21'] = '0';
    }

    if (!isset($data['startMeridian'])) {
      $data['startMeridian'] = null;
    }

    if (!isset($data['endMeridian'])) {
      $data['endMeridian'] = null;
    }

    if (!isset($data['endFinalMeridian'])) {
      $data['endFinalMeridian'] = null;
    }

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

    if (isset($data['billing'])) {
      $data['meta'] = array_merge($data['billing'], isset($data['meta']) ? $data['meta'] : []);
    }

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
      'tickets' => [
        'ticketNme' => isset($data['meta']['tickets']['ticketNme']) ? $data['meta']['tickets']['ticketNme'] : null,
        'ticket_pric' => isset($data['meta']['ticketPric']) ? $data['meta']['ticketPric'] : 0,
        'enabled' => isset($data['meta']['tickets']['enabled']) ? (bool) $data['meta']['tickets']['enabled'] : true,
      ],
      'comments' => [
        'enabled' => isset($data['meta']['comments']['enabled']) ? (bool) $data['meta']['comments']['enabled'] : true,
      ],
      // 'tweets' => [
      //   'enabled' => isset($data['meta']['tweets']['enabled']) ? (bool) $data['meta']['tweets']['enabled'] : true,
      // ],
      'map' => [
        'enabled' => isset($data['meta']['map']['enabled']) ? (bool) $data['meta']['map']['enabled'] : true,
      ],
      'transportation' => [
        'enabled' => isset($data['meta']['transportation']['enabled']) ? (bool) $data['meta']['transportation']['enabled'] : true,
      ],
      // 'weather' => [
      //   'enabled' => isset($data['meta']['weather']['enabled']) ? (bool) $data['meta']['weather']['enabled'] : true,
      // ],
      'reviews' => [
        'enabled' => isset($data['meta']['reviews']['enabled']) ? (bool) $data['meta']['reviews']['enabled'] : true,
      ],
      'rsvp' => [
        'enabled' => isset($data['meta']['rsvp']['enabled']) ? (bool) $data['meta']['rsvp']['enabled'] : true,
      ],
      'guest_list' => [
        'enabled' => isset($data['meta']['guestList']['enabled']) ? (bool) $data['meta']['guestList']['enabled'] : true,
      ],
      'guest_pictures' => [
        'enabled' => isset($data['meta']['guestPictures']['enabled']) ? (bool) $data['meta']['guestPictures']['enabled'] : true,
      ],
      'guest_video' => [
        'enabled' => isset($data['meta']['guestVideo']['enabled']) ? (bool) $data['meta']['guestVideo']['enabled'] : true,
      ],
      'tos_acceptance' => [
        'date' => isset($data['meta']['tos_acceptance']['date']) ? $data['meta']['tos_acceptance']['date'] : null,
        'ip' => isset($data['meta']['tos_acceptance']['ip']) ? $data['meta']['tos_acceptance']['ip'] : null,
      ],
    ]);

		if ($id !== null) {

      $data['user_id'] = \Auth::user()->id;

			// Show event associated the ID.
			$event = $api->update('events', $id . '?auth_user_id=' . $data['user_id'], $data);

		} else {

      $data['user_id'] = \Auth::user()->id;

			// Store Event
			$event = $api->store('events?auth_user_id=' . $data['user_id'], $data);

      if ($event['success'] !== true) {

        // Check if errors are in an array
        if (is_array($event['error'])) {

          // Iterate through each error
          foreach ($event['error'] as $error => $message) {
            for ($i=0; $i < count($event['error']); $i++) {

              if (isset($message[$i])) {
                $errorMessage[] = $message[$i];
              }
            }
          }

          // Implode error
          $errorMessage = implode(' ', $errorMessage);
        } else {

          $errorMessage = $event['error'];
        }

        return Redirect::to(route('events.create', ['id' => null, 'tab' => 'summary']))->with('fail_message', $errorMessage);
      }

		}

    // TODO: Duplicate charging!! Charge is being handled on the API pm first event create
    // if (isset($data['cardId']) || (isset($data['billing']['number']) && ! empty($data['billing']['number']))) {

    //   // If is premium or a card is saved
    //   if (isset($data['cardId'])) {

    //     // Preimum data with remembered card
    //     $premiumCharge = [
    //       'userId' => \Auth::user()->id,
    //       'email' => \Auth::user()->email,
    //       'cardId' => isset($data['cardId']) ? $data['cardId'] : null,
    //       'token' => isset($data['token']) ? $data['token'] : null,
    //       'eventId' => isset($event['data']['event']['id']) ? $event['data']['event']['id'] : null
    //     ];

    //   } else {

    //     // Preimum data with card
    //     $premiumCharge = [
    //       'userId' => \Auth::user()->id,
    //       'email' => isset($data['billing']['email']) ? $data['billing']['email'] : null,
    //       'name' => isset($data['billing']['name']) ? $data['billing']['name'] : null,
    //       'number' => isset($data['billing']['number']) ? $data['billing']['number'] : null,
    //       'cvc' => isset($data['billing']['cvc']) ? $data['billing']['cvc'] : null,
    //       'month' => isset($data['billing']['month']) ? $data['billing']['month'] : null,
    //       'year' => isset($data['billing']['year']) ? $data['billing']['year'] : null,
    //       'address' => isset($data['billing']['address']) ? $data['billing']['address'] : null,
    //       'city' => isset($data['billing']['city']) ? $data['billing']['city'] : null,
    //       'state' => isset($data['billing']['state']) ? $data['billing']['state'] : null,
    //       'zip' => isset($data['billing']['zip']) ? $data['billing']['zip'] : null,
    //       'eventId' => isset($event['data']['event']['id']) ? $event['data']['event']['id'] : null
    //     ];

    //   }

    //   // Unset null
    //   foreach ($premiumCharge as $key => $value) {

    //     if (is_null($value)) {

    //       unset($premiumCharge[$key]);

    //     }

    //   }

    //   // Create the users account and charge premium
    //   $customerCreate = $api->store('ticketsorders/purchase', $premiumCharge);

    //   if ($customerCreate['success'] !== true) {

    //     // Check if errors are in an array
    //     if (is_array($customerCreate['error'])) {

    //       // Iterate through each error
    //       foreach ($customerCreate['error'] as $error => $message) {

    //         for ($i=0; $i < count($customerCreate['error']); $i++) {

    //           if (isset($message[$i])) {
    //             $errorMessage[] = $message[$i];
    //           }

    //         }

    //       }

    //       $errorMessage = implode(' ', $errorMessage);

    //     } else {

    //       $errorMessage = $customerCreate['error'];

    //     }

    //     return Redirect::to(route('events.create', ['id' => null, 'tab' => 'freemium']))->with('fail_message', $errorMessage);

    //   }

    // }

    if ($event['success'] !== true) {

      // Check if errors are in an array
      if (is_array($event['error'])) {

        // Iterate through each error
        foreach ($event['error'] as $error => $message) {
          for ($i=0; $i < count($event['error']); $i++) {

            if (isset($message[$i])) {
              $errorMessage[] = $message[$i];
            }
          }
        }

        $errorMessage = implode(' ', $errorMessage);
      } else {

        $errorMessage = $event['error'];
      }

      return Redirect::to(route('events.create', ['id' => null, 'tab' => 'summary']))->with('fail_message', $errorMessage);
    }

    $id = $event['data']['event']['slug'];

    // Return false
    if ($event['success'] !== true) {

      return Redirect::back()->with('fail_message', 'Error occurred. Please try again later.');
    }

    // If id is nulled
    if($id !== null){

      // Encodes message to get sent with url
      $message = urlencode('Please wait while your event is being created');

      // Will redirect to loader with message, then load event
      $url = "time=4000&url=/events/" . $id . "&message=" . $message;

      return Redirect::to(route('pages.loader', (string)$url));

    } else {

      // Update event
      return Redirect::to(route('events.show', [
        'id' => $id
      ]))->with('success_message', 'Your event is successfully updated!');

    }
	}

	/**
	 * TODO: I need a comment
	 * @param  [type] $id [description]
	 * @return [type]     [description]
	 */
	public function showCreatePhotoAndVideo($id)
	{
		// TODO: How we upload images will be different, need that before implementing
		// Instantiate the APIHelper
		$api = new \App\Helpers\ApiHelper;

    $url = 'events/' . $id;

    if (\Auth::check()) {
      $url .= '?auth_user_id=' . \Auth::user()->id;
    }

		// Show event associated the ID.
		$event = $api->index($url);

		// $photos = $event->photos();

		return \View::make('events.create_photo_and_video')->with([
			'step' => 'photo_and_video',
			'stepsLocked' => false,
			'event' => $event['data']['event'],
			'photos' => $event['data']['event']['photos'],
			'justUploaded' => \Session::has('just_uploaded'),
		]);
	}

	/**
	 * TODO: I need a comment
	 * @param  [type] $id [description]
	 * @return [type]     [description]
	 */
	public function showEventTimes($id)
	{
		// Instantiate the APIHelper
		$api = new \App\Helpers\ApiHelper;

    $url = 'events/' . $id;

    if (\Auth::check()) {
      $url .= '?auth_user_id=' . \Auth::user()->id;
    }

		// Grab event matching ID
		$event = $api->index($url);

		// Parse only event times
		$times = $event['data']['event']['times'];

		return \View::make('events.create_show_event_times')->with([
	  	'times' => $event['data']['event']['times'],
		]);

		// filter out old times
	}

	/**
	 * Event search results
	 *
	 * @return [type] [description]
	 */
	public function doSearch()
	{
    // Sets initial page number for pagination
    $page = \Input::get('page', 1);

    // Limit to twenty events per page
    $limit = 20;

    // Makes page content correctly display next events in line
    $offset = ($page - 1) * $limit;

		// Instantiate the APIHelper
		$api = new \App\Helpers\ApiHelper;

		// Grab all the data from the search input
		$data = \Input::all();

    // Search query input
    $query = isset($data['q']) ? $data['q'] : '';

    // Set query filter
    $filterQuery = '';

    // Api filter options
    $filterOptions = [
      'isFeatured',
      'isIndoor',
      'isOutdoor',
      'venueName',
      'isAge13',
      'isAge16',
      'isAge18',
      'isAge21',
      'city',
      'zipcode',
    ];

    // Apply api filter
    foreach ($filterOptions as $option) {

      // If we have a value for this field
      if (! empty($data[$option])) {

        // Convert to snake case
        $optionSnakeCase = snake_case($option);

        // Add to filter
        $filterQuery .= '&filter[and][][' . $optionSnakeCase . ']=' . $data[$option];
      }
    }

    // Api end point
    $endpoint = ''
    . 'events?filter[and][][times.end]=date&limit=' . $limit . '&offset=' . $offset
    . '&with[]=tags&with[]=photos&with[]=user&with[]=times&with[]=nextEvent'
    . '&filter[and][][is_published]=1&filter[and][][is_banned]=0&filter[and][][is_private]=0' . $filterQuery
    . '&search=' . $query
    ;

    if (\Auth::check()) {
      $endpoint .= '&auth_user_id=' . \Auth::user()->id;
    }

    // Get events
    $events = $api->index($endpoint);
    $events = isset($events['data']['events']) ? $events['data']['events'] : [];

    // Paginator sets events as items, number of events to null (already defined), and path is
    // set so that the browser uses current route
    $events = new Paginator($events, null, $page, [
      'path' => Paginator::resolveCurrentPath()
    ]);

		$trans = [
		  	'Alabama'=>'AL',
        'Alaska'=>'AK',
        'Arizona'=>'AZ',
        'Arkansas'=>'AR',
        'California'=>'CA',
        'Colorado'=>'CO',
        'Connecticut'=>'CT',
        'Delaware'=>'DE',
        'Florida'=>'FL',
        'Georgia'=>'GA',
        'Hawaii'=>'HI',
        'Idaho'=>'ID',
        'Illinois'=>'IL',
        'Indiana'=>'IN',
        'Iowa'=>'IA',
        'Kansas'=>'KS',
        'Kentucky'=>'KY',
        'Louisiana'=>'LA',
        'Maine'=>'ME',
        'Maryland'=>'MD',
        'Massachusetts'=>'MA',
        'Michigan'=>'MI',
        'Minnesota'=>'MN',
        'Mississippi'=>'MS',
        'Missouri'=>'MO',
        'Montana'=>'MT',
        'Nebraska'=>'NE',
        'Nevada'=>'NV',
        'New Hampshire'=>'NH',
        'New Jersey'=>'NJ',
        'New Mexico'=>'NM',
        'New York'=>'NY',
        'North Carolina'=>'NC',
        'North Dakota'=>'ND',
        'Ohio'=>'OH',
        'Oklahoma'=>'OK',
        'Oregon'=>'OR',
        'Pennsylvania'=>'PA',
        'Rhode Island'=>'RI',
        'South Carolina'=>'SC',
        'South Dakota'=>'SD',
        'Tennessee'=>'TN',
        'Texas'=>'TX',
        'Utah'=>'UT',
        'Vermont'=>'VT',
        'Virginia'=>'VA',
        'Washington'=>'WA',
        'West Virginia'=>'WV',
        'Wisconsin'=>'WI',
        'Wyoming'=>'WY'
      ];

    return \View::make('events.search-results')->with([
      'trans' => $trans,
      'events' => $events,
    ]);

	}

  /**
   * Recaptcha
   * @return [type] [description]
   */
  public function showCategories()
  {
    // Sets initial page number for pagination
    $page = \Input::get('page', 1);

    // Limit to twenty events per page
    $limit = 20;

    // Makes page content correctly display next events in line
    $offset = ($page - 1) * $limit;

    // Instantiate the APIHelper
    $api = new \App\Helpers\ApiHelper;

    // Grab all the data from the search input
    $data = \Input::all();

    $url = 'collections/categories?sort[asc][]=tag&limit=40';

    if (\Auth::check()) {
      $url .= '&auth_user_id=' . \Auth::user()->id;
    }

    $tags = $api->index($url);

    if (isset($tags['data']['categories'])) {
      $tags = $tags['data']['categories'];
    }else{
      $tags = [];
    }

    return \View::make('events.categories')->with([
      'tags' => $tags,
    ]);

  }

  /**
   * Recaptcha
   * @return [type] [description]
   */
  public function shareViaEmail($slug)
  {
    // Instantiate the APIHelper
    $api = new \App\Helpers\ApiHelper;

    $data = Request::all();

    // Verify correct captcha was entered
    $reCaptcha = new \ReCaptcha\ReCaptcha(\Config::get('api.google.captcha.private'));
    $response = $reCaptcha->verify(\Input::get("g-recaptcha-response"), Request::server("REMOTE_ADDR"));

    // Captcha verified
    if ($response->isSuccess()) {

      $url = 'users/invite';

      if (\Auth::check()) {
        $url .= '?auth_user_id=' . \Auth::user()->id;
      }

      $sendInvite = $api->store($url, $data);

      if ($sendInvite['success'] !== true) {
        return Redirect::back()->with('fail_message', $sendInvite['error']);
      }

      return Redirect::back()->with('success_message', 'Congratulations! This event has been shared!');

    // Captcha failed
    } else {

      // Reply back with error message
      $errors = $response->getErrorCodes();
      return Redirect::back()->with('fail_message', 'Error occurred. Please check your input and try again later.');

    }

  }

  /**
   * Sends user request to event
   *
   * @param   $data holds userId eventId requesterId
   * @param   $slug   holds event slug
   */
  public function doEventInvites($slug)
  {
    // Grab invite data
    $data = Request::all();

    $id = $data['eventId'];

    // Instantiate the APIHelper
    $api = new \App\Helpers\ApiHelper;

    $url = 'events/' . $id . '/invite';

    if (\Auth::check()) {
      $url .= '?auth_user_id=' . \Auth::user()->id;
    }

    // Send invite
    $friendEventInvite = $api->store($url, $data);

    return $friendEventInvite;

  }

  public function postRsvp($slug)
  {
    // Grab rsvp data
    $data = Request::all();

    // Instantiate the APIHelper
    $api = new \App\Helpers\ApiHelper;

    $url = 'attendees';

    if (\Auth::check()) {
      $url .= '?auth_user_id=' . \Auth::user()->id;
    }

    // Post your rsvp
    $rsvpStatus = $api->store($url, $data);

    return $rsvpStatus;
  }

  public function signUploadMedia()
  {
    $toSign = \Input::get('to_sign');

    $secret = 'b4II6G+RP2xFqodbp4zrFMzJopUcrMw/LeMgd1sY';

    $hmacSha1 = hash_hmac('sha1', $toSign, $secret, true);

    $signature = base64_encode($hmacSha1);

    $response = \Response::make($signature);
    $response->header('Content-Type', 'text/HTML');
    return $response;
  }

  /**
   * Show List of events
   * @return friends page view
   */
  public function showAllEvents()
  {
    // Illuminate the APIHelper
    $api = new \App\Helpers\ApiHelper;

    $limit = 200;

    $url = 'events?filter[and][][times.end]=date&limit=' . $limit . '&fields[]=slug&fields[]=title&fields[]=latitude&fields[]=longitude&fields[]=address1&fields[]=address2&fields[]=city&fields[]=state&fields[]=zipcode&fields[]=phone&fields[]=venueName&fields[]=tags&fields[]=nextEvent&with[]=tags&with[]=nextEvent';

    if (\Auth::check()) {
      $url .= '&auth_user_id=' . \Auth::user()->id;
    }

    $events = $api->index($url);
    $events = $events['data']['events'];

    for ($i=0; $i < count($events); $i++) {

      $events[$i] = [
        'title' => isset($events[$i]['title']) ? $events[$i]['title'] : null,
        'slug' => isset($events[$i]['slug']) ? $events[$i]['slug'] : null,
        'venueName' => isset($events[$i]['venueName']) ? $events[$i]['venueName'] : null,
        'address1' => isset($events[$i]['address1']) ? $events[$i]['address1'] : null,
        'address2' => isset($events[$i]['address2']) ? $events[$i]['address2'] : null,
        'city' => isset($events[$i]['city']) ? $events[$i]['city'] : null,
        'state' => isset($events[$i]['state']) ? $events[$i]['state'] : null,
        'zipcode' => isset($events[$i]['zipcode']) ? $events[$i]['zipcode'] : null,
        'phone' => isset($events[$i]['phone']) ? $events[$i]['phone'] : null,
        'latitude' => isset($events[$i]['latitude']) ? $events[$i]['latitude'] : null,
        'longitude' => isset($events[$i]['longitude']) ? $events[$i]['longitude'] : null,
        'tags' => isset($events[$i]['tags']) ? $events[$i]['tags'] : null,
        'nextEvent' => isset($events[$i]['nextEvent']) ? date('m/d/Y g:ia', strtotime($events[$i]['nextEvent']['start'])) : '',
      ];
    }

    return $events;

  }

}
