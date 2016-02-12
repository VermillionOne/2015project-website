<?php

use App\Helpers\ApiHelper;
use App\Auth\ApiGenericUser;

class TicketPurchaseTest extends TestCase {

  /**
   * Create a test purchaser
   *
   * @return $testUser
   */
  public function testPurchaser()
  {
    $api = new \App\Helpers\ApiHelper;

    $email = 'dev+websiteOrderTest@shindiig.com';

    $testUser = $api->index('users' . '?filter[and][][email]=' . urlencode($email));

    if ($testUser['success'] !== true && $testUser['error'] === 'No users') {

      $testUser = $api->store('users', [
        'email' => $email,
        'time_zone_id' => '6',
        'first_name' => 'TestPurchaser',
        'last_name' => 'ThatGuy',
        'password' => 'p@ssw0rd'
      ]);
    }

    $testUser = isset($testUser['data']['users'][0]) ? $testUser['data']['users'][0] : $testUser['data']['user'];

    return $testUser;
  }

  /**
   * testPostAccountLogin (Logged out)
   *
   * @return void
   */
  public function testAccountLogin()
  {
    // Instanciate apihelper
    $api = new \App\Helpers\ApiHelper;

    // Grab a user from
    $user = $api->show('users', '1');

    // Set usersname
    $user = $user['data']['user'];

    $login = $this->visit('account/login')
      ->type($user['email'], 'email')
      ->type('timberlake', 'password')
      ->press('Sign in');

    $see = $this->see($user['email'])
      ->see('Concerts')
      ->see('See More')
      ->seePageIs('/');
  }

  /**
   * Get users profile
   *
   * @return $user
   */
  public function testShowProfile()
  {
    // Instanciate apihelper
    $api = new \App\Helpers\ApiHelper;

    // Grab a user from
    $user = $api->index('users/4?with[]=events');

    // Set usersname
    $username = $user['data']['user']['username'];

    // Grab selected user via website
    $visit = $this->visit('users/' . $username);

    $see = $this->see($user['data']['user']['id'])
      ->see($user['data']['user']['timeZoneId'])
      ->see($user['data']['user']['firstName'])
      ->see($user['data']['user']['lastName']);

    return $user;
  }

  /**
   * Auth User
   *
   * @depends testShowProfile
   * @return collection model collection data from api
   */
  public function testAuth($user)
  {
    $auth = new ApiGenericUser(['id' => $user['data']['user']['id']]);

    return $auth;
  }

  /**
   * Test Create Event
   *
   * @depends testShowProfile
   * @return event
   */
  public function testCreateEvent($user)
  {
    $user = $user['data']['user'];

    $eventData = [
      'user_id' => $user['id'],
      'title' => 'Ticket purchase Unit test event ' . time(),
      'address1' => '123 st',
      'zipcode' => '89017',
      'tags' => 'concerts',
      'is_published' => 1,

      // Create a one-time schedule
      'meta' => '{"schedules": [{"timeZoneId": 6, "daysOfWeek": null, "start": {"date": "2020-10-31", "time": "12:00:00"}, "end": {"date": "2020-10-31", "time": "17:00:00"}, "repeat": null}]}',
    ];

    $api = new \App\Helpers\ApiHelper;

    $event = $api->store('events', $eventData);

    $event = $event['data']['event'];

    return $event;
  }

  /**
   * Create test tickets
   *
   * @depends testCreateEvent
   * @return $eventsTicketsUserData
   */
  public function testCreateTickets($event)
  {
    // Instanciate apihelper
    $api = new \App\Helpers\ApiHelper;

    $ticketsToMake = 6;

    for ($ticket=0; $ticket < $ticketsToMake; $ticket++) {

      switch (true) {
        case $ticket < 3:

          // Grab a user from
          $user = $api->store('ticketsinventories', [
            'name' => 'Test ticket' . time(),
            'amount' => 1.60,
            'inventory' => 9999,
            'user_id' => $event['user_id'],
            'event_id' => $event['id'],
            'is_enabled' => 1,
          ]);
          break;

        default:

          // Grab a user from
          $user = $api->store('ticketsinventories', [
            'name' => 'Test ticket' . time(),
            'amount' => 6 * 45,
            'inventory' => 9999,
            'user_id' => $event['user_id'],
            'event_id' => $event['id'],
            'is_enabled' => 1,
          ]);
          break;
      }
    }

    $eventsTicketsUserData = $api->index('events/' . $event['slug'] . '?with[]=ticketsInventory&with[]=nextEvent');

    echo 'Creating events schedule...';
    sleep(15);

    // Refresh event data
    $eventsTicketsUserData = $api->index('events/' . $event['slug'] . '?with[]=ticketsInventory&with[]=nextEvent');

    return $eventsTicketsUserData;
  }

  /**
   * Auth test User
   *
   * @depends testPurchaser
   * @return collection model collection data from api
   */
  public function testAuthPurchaser($user)
  {
    $auth = new ApiGenericUser(['id' => $user['id']]);

    return $auth;
  }

  /**
   * Purchase a single ticket
   *
   * @depends testCreateTickets
   * @depends testAuthPurchaser
   * @depends testPurchaser
   * @return void
   */
  public function testPurchaseSingle($eventsTicketsUserData, $auth, $testUser)
  {
    $this->actingAs($auth);
    $visit = $this->visit('events/' . $eventsTicketsUserData['data']['event']['slug'] .'/tickets');

    $formData = $this->type($testUser['firstName'] . ' ' . $testUser['lastName'], 'name')
      ->type($testUser['id'], 'userId')
      ->type($testUser['email'], 'email')
      ->type($eventsTicketsUserData['data']['event']['id'], 'eventId')
      ->type('4242424242424242', 'billing[number]')
      ->type('687', 'billing[cvc]')
      ->type('06', 'billing[month]')
      ->type('2030', 'billing[year]')
      ->type('210 qwe st', 'address')
      ->type('henderson', 'city')
      ->type('89074', 'zip')
      ->type($eventsTicketsUserData['data']['event']['ticketsInventory'][0]['id'], 'purchasedId')
      ->type($eventsTicketsUserData['data']['event']['ticketsInventory'][0]['amount'], 'amount')
      ->type('1', 'totalQuantity')
      ->type('1', 'qty')
      ->type($eventsTicketsUserData['data']['event']['nextEvent']['id'], 'eventTimeId')
      ->select('NV', 'state');

    $formSubmit = $this->press('place-order');

    $see = $this->see('Purchase Confirmation')
      ->see('Order Details')
      ->see('$1.60')
      ->see('Congratulations, your order has been placed');
  }

  /**
   * Purchase two tickets
   *
   * @depends testCreateTickets
   * @depends testAuth
   * @depends testPurchaser
   * @return void
   */
  public function testPurchaseTwo($eventsTicketsUserData, $auth, $testUser)
  {
    $this->actingAs($auth);
    $visit = $this->visit('events/' . $eventsTicketsUserData['data']['event']['slug'] .'/tickets');

    $formData = $this->type($testUser['firstName'] . ' ' . $testUser['lastName'], 'name')
      ->type($testUser['id'], 'userId')
      ->type($testUser['email'], 'email')
      ->type($eventsTicketsUserData['data']['event']['id'], 'eventId')
      ->type('4242424242424242', 'billing[number]')
      ->type('687', 'billing[cvc]')
      ->type('06', 'billing[month]')
      ->type('2030', 'billing[year]')
      ->type('210 qwe st', 'address')
      ->type('henderson', 'city')
      ->type('89074', 'zip')
      ->type($eventsTicketsUserData['data']['event']['ticketsInventory'][0]['id'] . ',' . $eventsTicketsUserData['data']['event']['ticketsInventory'][1]['id'], 'purchasedId')
      ->type('1,1', 'qty')
      ->type($eventsTicketsUserData['data']['event']['nextEvent']['id'], 'eventTimeId')
      ->select('NV', 'state');

    $formSubmit = $this->press('place-order');

    $see = $this->see('Purchase Confirmation')
      ->see('Order Details')
      ->see('$3.20')
      ->see('Congratulations, your order has been placed');
  }

  /**
   * Purchase three tickets
   *
   * @depends testCreateTickets
   * @depends testAuth
   * @depends testPurchaser
   * @return void
   */
  public function testPurchaseThree($eventsTicketsUserData, $auth, $testUser)
  {
    $this->actingAs($auth);
    $visit = $this->visit('events/' . $eventsTicketsUserData['data']['event']['slug'] .'/tickets');

    $formData = $this->type($testUser['firstName'] . ' ' . $testUser['lastName'], 'name')
      ->type($testUser['id'], 'userId')
      ->type($testUser['email'], 'email')
      ->type($eventsTicketsUserData['data']['event']['id'], 'eventId')
      ->type('4242424242424242', 'billing[number]')
      ->type('687', 'billing[cvc]')
      ->type('06', 'billing[month]')
      ->type('2030', 'billing[year]')
      ->type('210 qwe st', 'address')
      ->type('henderson', 'city')
      ->type('89074', 'zip')
      ->type($eventsTicketsUserData['data']['event']['ticketsInventory'][0]['id'] .
        ',' . $eventsTicketsUserData['data']['event']['ticketsInventory'][1]['id'] .
        ',' . $eventsTicketsUserData['data']['event']['ticketsInventory'][2]['id'], 'purchasedId')
      ->type('1,1,1', 'qty')
      ->type($eventsTicketsUserData['data']['event']['nextEvent']['id'], 'eventTimeId')
      ->select('NV', 'state');

    $formSubmit = $this->press('place-order');

    $see = $this->see('Purchase Confirmation')
      ->see('Order Details')
      ->see('$4.80')
      ->see('Congratulations, your order has been placed');
  }

  /**
   * Purchase an expensive single
   *
   * @depends testCreateTickets
   * @depends testAuth
   * @depends testPurchaser
   * @return void
   */
  public function testPurchaseHighSingle($eventsTicketsUserData, $auth, $testUser)
  {
    $this->actingAs($auth);
    $visit = $this->visit('events/' . $eventsTicketsUserData['data']['event']['slug'] .'/tickets');

    $formData = $this->type($testUser['firstName'] . ' ' . $testUser['lastName'], 'name')
      ->type($testUser['id'], 'userId')
      ->type($testUser['email'], 'email')
      ->type($eventsTicketsUserData['data']['event']['id'], 'eventId')
      ->type('4242424242424242', 'billing[number]')
      ->type('687', 'billing[cvc]')
      ->type('06', 'billing[month]')
      ->type('2030', 'billing[year]')
      ->type('210 qwe st', 'address')
      ->type('henderson', 'city')
      ->type('89074', 'zip')
      ->type($eventsTicketsUserData['data']['event']['ticketsInventory'][3]['id'], 'purchasedId')
      ->type('1', 'qty')
      ->type($eventsTicketsUserData['data']['event']['nextEvent']['id'], 'eventTimeId')
      ->select('NV', 'state');

    $formSubmit = $this->press('place-order');

    $see = $this->see('Purchase Confirmation')
      ->see('Order Details')
      ->see('$270.00')
      ->see('Congratulations, your order has been placed');
  }

  /**
   * Purchase two expensive
   *
   * @depends testCreateTickets
   * @depends testAuth
   * @depends testPurchaser
   * @return void
   */
  public function testPurchaseHighTwo($eventsTicketsUserData, $auth, $testUser)
  {
    $this->actingAs($auth);
    $visit = $this->visit('events/' . $eventsTicketsUserData['data']['event']['slug'] .'/tickets');

    $formData = $this->type($testUser['firstName'] . ' ' . $testUser['lastName'], 'name')
      ->type($testUser['id'], 'userId')
      ->type($testUser['email'], 'email')
      ->type($eventsTicketsUserData['data']['event']['id'], 'eventId')
      ->type('4242424242424242', 'billing[number]')
      ->type('687', 'billing[cvc]')
      ->type('06', 'billing[month]')
      ->type('2030', 'billing[year]')
      ->type('210 qwe st', 'address')
      ->type('henderson', 'city')
      ->type('89074', 'zip')
      ->type($eventsTicketsUserData['data']['event']['ticketsInventory'][3]['id'] .
        ',' . $eventsTicketsUserData['data']['event']['ticketsInventory'][4]['id'], 'purchasedId')
      ->type('1,1', 'qty')
      ->type($eventsTicketsUserData['data']['event']['nextEvent']['id'], 'eventTimeId')
      ->select('NV', 'state');

    $formSubmit = $this->press('place-order');

    $see = $this->see('Purchase Confirmation')
      ->see('Order Details')
      ->see('$540.00')
      ->see('Congratulations, your order has been placed');
  }

  /**
   * Purchase three expensive
   *
   * @depends testCreateTickets
   * @depends testAuth
   * @depends testPurchaser
   * @return void
   */
  public function testPurchaseHighThree($eventsTicketsUserData, $auth, $testUser)
  {
    $this->actingAs($auth);
    $visit = $this->visit('events/' . $eventsTicketsUserData['data']['event']['slug'] .'/tickets');

    $formData = $this->type($testUser['firstName'] . ' ' . $testUser['lastName'], 'name')
      ->type($testUser['id'], 'userId')
      ->type($testUser['email'], 'email')
      ->type($eventsTicketsUserData['data']['event']['id'], 'eventId')
      ->type('4242424242424242', 'billing[number]')
      ->type('687', 'billing[cvc]')
      ->type('06', 'billing[month]')
      ->type('2030', 'billing[year]')
      ->type('210 qwe st', 'address')
      ->type('henderson', 'city')
      ->type('89074', 'zip')
      ->type($eventsTicketsUserData['data']['event']['ticketsInventory'][3]['id'] .
        ',' . $eventsTicketsUserData['data']['event']['ticketsInventory'][4]['id'] .
        ',' . $eventsTicketsUserData['data']['event']['ticketsInventory'][5]['id'], 'purchasedId')
      ->type('1,1,1', 'qty')
      ->type($eventsTicketsUserData['data']['event']['nextEvent']['id'], 'eventTimeId')
      ->select('NV', 'state');

    $formSubmit = $this->press('place-order');

    $see = $this->see('Purchase Confirmation')
      ->see('Order Details')
      ->see('$810.00')
      ->see('Congratulations, your order has been placed');
  }

}
