<?php

use App\Helpers\ApiHelper;
use App\Auth\ApiGenericUser;
use App\User;

class AccountsControllerTest extends TestCase {

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

    $result = $this->visit('account/login')
      ->type($user['email'], 'email')
      ->type('timberlake', 'password')
      ->press('Sign in')
      ->see($user['email'])
      ->see('Search Events Near You')
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
    $user = $api->show('users', '1?with[]=events');

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
      'title' => 'Unit test event ' . time(),
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

  // /**
  //  * Test Purchase Ticets
  //  *
  //  * @depends testShowProfile
  //  * @depends testCreateEvent
  //  * @return purchase
  //  */
  // public function testPurchaseTickets($user, $event)
  // {
  //   $user = $user['data']['user'];

  //   $api = new \App\Helpers\ApiHelper;

  //   $event = $api->show('events', $event['id'] . '?with[]=times');

  //   $event = $event['data']['event'];

  //   $eventTimeId = $event['times'][0]['id'];

  //   $purchaseData = [
  //     'name' => $user['firstName'],
  //     'number' => '4242424242424242',
  //     'email' => $user['email'],
  //     'cvc' => '947',
  //     'month' => '06',
  //     'year' => '2020',
  //     'zip' => '89074',
  //     'user_id' => $user['id'],
  //     'event_id' => $event['id'],
  //     'event_time_id' => $eventTimeId,
  //     'ticket_inventories' => [
  //       'id' => 1,
  //       'quantity' => 1,
  //     ],
  //   ];

  //   $purchase = $api->store('ticketsorders/purchase', $purchaseData);

  //   return $purchase;
  // }

  /**
   * Get ocode for a user
   *
   * @depends testAuth
   * @param  array    $user    user array data
   * @return string
   */
  public function testGetCode($auth)
  {
    // Define userId
    $userId = $auth->id;

    // Instanciate apihelper
    $api = new \App\Helpers\ApiHelper;

    // Grab a user from
    $code = $api->index('tickets/user/' . $userId . '/codes', null);

    $code = $code['data']['orders'][0]['code'];

    return $code;
  }

  /**
   * Get register page
   *
   * @return void
   */
  public function testGetRegisterOut()
  {
    // Get register
    $result = $this->visit('account/register')
      ->see('Create Your SUARAY Account')
      ->seePageIs('account/register');

    // Check Response
  }

  /**
   * Get register page
   *
   * @depends testAuth
   * @return void
   */
  public function testGetRegisterIn($auth)
  {
    // Get register
    $result = $this->actingAs($auth)
      ->visit('account/register')
      ->see('Sign Out')
      ->seePageIs('/');
  }

  /**
   * Get password forgot page, page contains no data
   * only thing to test is response
   *
   * @return void
   */
  public function testGetAccountPasswordForgotOut()
  {
    // Get forgot pass page
    $result = $this->visit('account/password/forgot')
      ->see('RESET YOUR PASSWORD')
      ->seePageIs('/account/password/forgot');
  }

  /**
   * Get password forgot page, page contains no data
   * only thing to test is response
   *
   * @depends testAuth
   * @return void
   */
  public function testGetAccountPasswordForgotIn($auth)
  {
    // Get forgot pass page
    $result = $this->actingAs($auth)
      ->visit('account/password/forgot')
      ->see('Search Events Near You')
      ->seePageIs('/');
  }

  /**
   * testGetAccountDashboardEvent (Logged out)
   * Check response status as there is nothing to test
   *
   * @return void
   */
  public function testGetAccountDashboardEventOut()
  {
    $eventId = '2';

    // Grab Page
    $result = $this->visit('account/dashboard/event/' . $eventId)
      ->see('create your suaray account')
      ->see('welcome to suaray')
      ->seePageIs('/account/register');
  }

  /**
   * testGetAccountDashboardEvent (Logged out)
   * Check response status as there is nothing to test
   *
   * @depends testAuth
   * @depends testShowProfile
   * @depends testCreateEvent
   * @return void
   */
  public function testGetAccountDashboardEventIn($auth, $user, $event)
  {
    // Grab Page
    $result = $this->actingAs($auth)
      ->visit('account/dashboard/event/' . $event['id'])
      ->see('Analytics')
      ->seePageIs('account/dashboard/event/' . $event['id']);
  }

  /**
   * testGetAccountSettings (Logged out)
   * Check response status as there is nothing to test
   *
   * @return void
   */
  public function testGetAccountSettingsOut()
  {
    // Grab settings page
    $result = $this->visit('account/settings')
      ->see('create your suaray account')
      ->see('welcome to suaray')
      ->seePageIs('/account/register');
  }

  /**
   * testGetAccountSettings
   * Check response status as there is nothing to test
   *
   * @depends testAuth
   * @return void
   */
  public function testGetAccountSettingsIn($auth)
  {
    // Grab settings page
    $result = $this->actingAs($auth)
      ->visit('account/settings')
      ->see('Account Settings')
      ->seePageIs('/account/settings');
  }

  /**
   * testGetAccountEventCheckIn
   * Check response status as there is nothing to test
   *
   * @depends testAuth
   * @return void
   */
  public function testGetAccountEventCheckIn($auth)
  {
    // Grab Page
    $result = $this->actingAs($auth)
      ->visit('account/event/check-in')
      ->see('Check-In History')
      ->seePageIs('/account/event/check-in');
  }

  /**
   * testGetAccountMyTickets (Logged out)
   * Check response status as there is nothing to test
   *
   * @return void
   */
  public function testGetAccountMyTicketsOut()
  {
    // Grab page
    $result = $this->visit('account/my-tickets')
      ->see('create your suaray account')
      ->see('welcome to suaray')
      ->seePageIs('/account/register');
  }

  /**
   * testGetAccountMyTickets
   * Check response status as there is nothing to test
   *
   * @depends testAuth
   * @return void
   */
  public function testGetAccountMyTicketsIn($auth)
  {
    // Grab page
    $result = $this->actingAs($auth)
      ->visit('account/my-tickets')
      ->see('s Tickets')
      ->seePageIs('account/my-tickets');
  }

  /**
   * testGetAccountEventCheckIn (Logged out)
   * Check response status as there is nothing to test
   *
   * @return void
   */
  public function testGetAccountEventCheckOut()
  {
    // Grab Page
    $result = $this->visit('account/event/check-in')
      ->see('create your suaray account')
      ->see('welcome to suaray')
      ->seePageIs('/account/register');
  }

  /**
   * testGetAccountEventCheckInConfirm (Logged out)
   * Check response status as there is nothing to test
   *
   *  @depends testAuth
   *  @depends testGetCode
   * @return void
   */
  public function testGetAccountEventCheckInConfirmIn($auth, $code)
  {
    // Grab Page
    $result = $this->actingAs($auth)
      ->visit('account/event/check-in/confirm?code=' . $code)
      ->see('"success":true,')
      ->see('{"order":{"id":')
      ->seePageIs('account/event/check-in/confirm?code=' . $code);
  }

  /**
   * testGetAccountEventCheckInConfirm (Logged out)
   * Check response status as there is nothing to test
   *
   * @return void
   */
  public function testGetAccountEventCheckInConfirmOut()
  {
    // Grab Page
    $result = $this->visit('account/event/check-in/confirm')
      ->see('create your suaray account')
      ->see('welcome to suaray')
      ->seePageIs('/account/register');
  }

  /**
   * testGetAccountEventCheckInUpdate
   * Check response status as there is nothing to test
   *
   * @depends testAuth
   * @return void
   */
  public function testGetAccountEventCheckInUpdateIn($auth)
  {
    // Grab Page
    $result = $this->actingAs($auth)
      ->visit('account/event/check-in/update')
      ->see('success":true')
      ->see('error":null')
      ->seePageIs('account/event/check-in/update');
  }

  /**
   * testGetAccountEventCheckInUpdate (Logged out)
   * Check response status as there is nothing to test
   *
   * @return void
   */
  public function testGetAccountEventCheckInUpdateOut()
  {
    // Grab Page
    $result = $this->visit('account/event/check-in/update')
      ->see('create your suaray account')
      ->see('welcome to suaray')
      ->seePageIs('/account/register');
  }

  /**
   * testGetAccountProfile
   * Check response status as there is nothing to test
   *
   * @depends testAuth
   * @return void
   */
  public function testGetAccountProfileIn($auth)
  {
    // Grab Page
    $result = $this->actingAs($auth)
      ->visit('account/profile')
      ->see('Account Privacy')
      ->see('SPONSORED')
      ->see('ADS')
      ->see('CALENDAR')
      ->see('Edit Profile Image')
      ->seePageIs('account/profile');
  }

  /*
   * testGetAccountProfile (Logged out)
   * Check response status as there is nothing to test
   *
   * @return void
   */
  public function testGetAccountProfileOut()
  {
    // Grab Page
    $result = $this->visit('account/profile')
      ->see('create your suaray account')
      ->see('welcome to suaray')
      ->seePageIs('/account/register');
  }

  /**
   * testGetAccountFriends
   * Check response status as there is nothing to test
   *
   * @depends testAuth
   * @return void
   */
  public function testGetAccountFriendsIn($auth)
  {
    // Grab Page
    $result = $this->actingAs($auth)
      ->visit('account/friends')
      ->see('Notifications')
      ->see('Friends')
      ->see('Invite')
      ->seePageIs('account/friends');
  }

  /**
   * testGetAccountFriends (Logged out)
   * Check response status as there is nothing to test
   *
   * @return void
   */
  public function testGetAccountFriendsOut()
  {
    // Grab Page
    $result = $this->visit('account/friends')
      ->see('create your suaray account')
      ->see('welcome to suaray')
      ->seePageIs('/account/register');
  }

  /**
   * testGetLogout (Logged out)
   * Check response status as there is nothing to test
   *
   * @return void
   */
  public function testGetLogoutOut()
  {
    // Grab logout page
    $result = $this->visit('account/logout')
      ->see('SIGN IN TO YOUR SUARAY.COM ACCOUNT')
      ->see('OR SIGN UP WITH YOUR SOCIAL ACCOUNT')
      ->seePageIs('account/login');
  }

  /**
   * testGetLogout
   * Check response status as there is nothing to test
   *
   * @depends testAuth
   * @return void
   */
  public function testGetLogoutIn($auth)
  {
    // Grab logout page
    $result = $this->actingAs($auth)
      ->visit('account/logout')
      ->see('SIGN IN TO YOUR SUARAY.COM ACCOUNT')
      ->see('OR SIGN UP WITH YOUR SOCIAL ACCOUNT')
      ->seePageIs('account/login');
  }

  /**
   * testGetAccountFriendsUsers (Logged out)
   * Check response status as there is nothing to test
   *
   * @return void
   */
  public function testGetAccountFriendsUsersOut()
  {
    // Grab Page
    $result = $this->visit('account/friends/users')
      ->see('create your suaray account')
      ->see('welcome to suaray')
      ->seePageIs('/account/register');
  }

  /**
   * testGetAccountFriendsUsers
   * Check response status as there is nothing to test
   *
   * @depends testAuth
   * @return void
   */
  public function testGetAccountFriendsUsersIn($auth)
  {
    // Set query param
    // Grab Page
    $result = $this->actingAs($auth)
      ->visit('account/friends/users?query=a')
      ->see('success":true,')
      ->see('error":null,')
      ->seePageIs('/account/friends/users?query=a');
  }

  /**
   * logged out test of delete friend request
   * @return void
   */
  public function testRequestDeleteOut()
  {
    $result = $this->visit('account/friends/delete/request/{id}')
      ->see('create your suaray account')
      ->see('welcome to suaray')
      ->seePageIs('/account/register');
  }

  /**
   * logged out test of delete friend
   * @return void
   */
  public function testFriendsDeleteOut()
  {
    $result = $this->visit('account/friends/delete/{id}')
      ->see('create your suaray account')
      ->see('welcome to suaray')
      ->seePageIs('/account/register');
  }

  /**
   * logged out test of update friend
   * @return void
   */
  public function testFriendsUpdateOut()
  {
    $result = $this->visit('account/friends/update/{id}')
      ->see('create your suaray account')
      ->see('welcome to suaray')
      ->seePageIs('/account/register');
  }

  /**
   * Logged out schedule update test
   *
   * @return void
   */
  public function testSchedulesUpdateOut()
  {
    $result = $this->visit('events/{event}/schedules/update/{id}')
      ->see('create your suaray account')
      ->see('welcome to suaray')
      ->seePageIs('/account/register');
  }

  /**
   * testRequestDeleteInBadFriend Logged in
   *
   * @depends testAuth
   * @return void
   */
  public function testRequestDeleteInBadFriend($auth)
  {
    $friendId = 0;

    $result = $this->actingAs($auth)
      ->visit('account/friends/delete/request/' . $friendId)
      ->seePageIs('/account/friends');
      // In order to properly test, we need to figure
      // out how to persist session data
  }

  /**
   * testGetAccountFriendsCreateRequest (Logged out)
   * Check response status as there is nothing to test
   *
   * @return void
   */
  public function testGetAccountFriendsCreateRequestOut()
  {
    $friendId = '5';

    Session::start();
    $token = csrf_token();

    $data = [
      '_token' => $token,
    ];

    // Grab Page
    $result = $this->get('account/friends/create/request/' . $friendId, $data);

    $this->assertResponseStatus(302);
    $this->assertRedirectedTo('/account/register');
    // How do we get more data? Test stops at redirect
  }

  /**
   * testGetEventsEventSchedulesId (Logged out)
   * Check response status as there is nothing to test
   *
   * @return void
   */
  public function testGetEventsEventSchedulesIdOut()
  {
    $eventId = '1';
    $id = '1';

    $result = $this->visit('events/' . $eventId . '/schedules/' . $id)
      ->see('create your suaray account')
      ->see('welcome to suaray')
      ->seePageIs('/account/register');
  }

  /**
   * testGetEventsIdSchedulesIndex (Logged out)
   * Check response status as there is nothing to test
   *
   * @depends testAuth
   * @depends testShowProfile
   * @return void
   */
  public function testGetEventsIdSchedulesIndexIn($auth, $user)
  {
    // Instanciate apihelper
    $api = new \App\Helpers\ApiHelper;

    // Pull users events with schedules
    $event = $api->index('events?filter[and][][user_id]=' . $user['data']['user']['id'] . '&with[]=schedules', []);

    $eventId = $event['data']['events'][0]['id'];

    $result = $this->actingAs($auth)
      ->visit('events/' . $eventId . '/schedules/index')
      ->see('Add Schedule')
      ->seePageIs('events/' . $eventId . '/schedules/index');
  }

  /**
   * testGetEventsIdSchedules (Logged out)
   * Check response status as there is nothing to test
   *
   * @return void
   */
  public function testGetEventsIdSchedulesOut()
  {
    $eventId = '1';

    $result = $this->visit('events/' . $eventId . '/schedules')
      ->see('create your suaray account')
      ->see('welcome to suaray')
      ->seePageIs('/account/register');
  }

  /**
   * Get email varification resend
   * @return void
   */
  public function testGetAccountEmailVerificationResendOut()
  {
    // Resend email verification
    $result = $this->visit('account/email/verification/resend')
      ->seePageIs('/account/email/verification/resend');
  }

  /**
   * Get email varification resend
   *
   * @depends testAuth
   * @return void
   */
  public function testGetAccountEmailVerificationResendIn($auth)
  {
    // Resend email verification
    $result = $this->actingAs($auth)
      ->visit('account/email/verification/resend')
      ->seePageIs('/account/email/verification/resend');
  }

  // ///////////////////////////////////////////////////////////
  // // Make sure we have logged in cross tests to the below. //
  // // If so move tests above this comment                   //
  // ///////////////////////////////////////////////////////////

  ///////////////////////////////////////////////
  /// TESTS BELOW THIS POINT ARE NOT COMPLETE ///
  ///////////////////////////////////////////////

  // Need to test Request DeleteIn Good Friend
  // Need to test Create Choices UpdateIn Good Friend





































  // *
  //  * ROUTE NOT USED

  // public function testDoSettingsOut()
  // {
  //   $result = $this->visit('account/settings/update')
  //     ->see('SIGN IN TO YOUR SUARAY.COM ACCOUNT')
  //     ->see('OR SIGN UP WITH YOUR SOCIAL ACCOUNT')
  //     ->seePageIs('account/login');
  // }

// public function testFriendsStoreOut()
// {
//   $result = $this->visit('account/friends/create/request/{id}')
//     ->see('SIGN IN TO YOUR SUARAY.COM ACCOUNT')
//     ->see('OR SIGN UP WITH YOUR SOCIAL ACCOUNT')
//     ->seePageIs('account/login');
// }

//   /**
//    * testCreateChoicesUpdate Logged in
//    *
//    * @depends testAuth
//    * @return void
//    */
//   public function testFriendsUpdateIn($auth){

//     $friendId = '5';

//     Session::start();
//     $token = csrf_token();

//     $data = [
//       '_token' => $token,
//     ];

//     $result = $this->actingAs($auth)
//       ->put('account/friends/update/' . $friendId, $data);

//     $this->assertResponseStatus(302);
//     $this->assertRedirectedTo('/account/friends');
//     // Check session set is true or false
//   }

//   /**
//    * testCreateChoicesUpdate Logged in
//    *
//    * @depends testAuth
//    * @return void
//    */
//   public function testCreatePollsUpdateIn($auth)
//   {
//     Session::start();
//     $token = csrf_token();

//     $id = '1';

//     $data = [
//       '_token' => $token,
//       'startMeridian' => 'pm',
//       'id' => $id,
//     ];

//     $result = $this->actingAs($auth)
//       ->put('account/polls/edit/' . $id, $data);

//     $this->assertResponseStatus(302);
//     $this->assertRedirectedTo('/yup');
//   }

//   /**
//    * testCreateChoicesUpdate Logged in
//    *
//    * @depends testAuth
//    * @return void
//    */
//   public function testSchedulesUpdateIn($auth)
//   {
//     Session::start();
//     $token = csrf_token();
//     $id = '1';

//     $data = [
//       '_token' => $token,
//       'startTime' => null,
//     ];

//     $result = $this->actingAs($auth)
//       ->put('events/{event}/schedules/update/' . $id, $data);

//     $this->assertResponseStatus(302);
//     $this->assertRedirectedTo('/yup');
//   }

//   public function testResendReservationOut()
//   {

//   }

//   public function testResendReservationIn()
//   {

//   }

//   ////////////////////////
//   // Need Variable data //
//   ////////////////////////

//   *
//    * testGetEventsEventSchedulesId (Logged out)
//    * Check response status as there is nothing to test
//    *
//    * @depends testAuth
//    * @return void

//   public function testGetEventsEventSchedulesIdIn($auth)
//   {
//     $eventId = '1';
//     $id = '1';

//     $result = $this->actingAs($auth)
//       ->visit('events/' . $eventId . '/schedules/' . $id)
//       ->see('SIGN IN TO YOUR SUARAY.COM ACCOUNT')
//       ->see('OR SIGN UP WITH YOUR SOCIAL ACCOUNT')
//       ->seePageIs('/account/login');
//   }

// /**
//    * testGetEventsIdSchedules (Logged out)
//    * Check response status as there is nothing to test
//    *
//    * @testAuth
//    * @return void
//    */
//   public function testGetEventsIdSchedulesIn($auth)
//   {
//     $eventId = '1';

//     $result = $this->actingAs($auth)
//       ->visit('events/' . $eventId . '/schedules')
//       ->see('SIGN IN TO YOUR SUARAY.COM ACCOUNT')
//       ->see('OR SIGN UP WITH YOUR SOCIAL ACCOUNT')
//       ->seePageIs('/account/login');
//   }

//   /**
//    * testGetAccountFriendsCreateRequest (Logged out)
//    * Check response status as there is nothing to test
//    *
//    * @depends testAuth
//    * @return void
//    */
//   public function testGetAccountFriendsCreateRequestIn($auth)
//   {
//     $friendId = '5';

//     Session::start();
//     $token = csrf_token();

//     $data = [
//       '_token' => $token,
//     ];

//     // Grab Page
//     $result = $this->actingAs($auth)
//       ->post('account/friends/create/request/' . $friendId, $data);
//     print_r($result);
//   }

//   /**
//    * testGetAccountPollsIndex (Logged out)
//    * Check response status as there is nothing to test
//    *
//    * @depends testAuth
//    * @return void
//    */
//   public function testGetAccountPollsIndexIn($auth)
//   {
//     $id = '1';

//     // Grab Page
//     $result = $this->actingAs($auth)
//       ->visit('account/polls/index/' . $id);
//     print_r($result);
//   }

//   /**
//    * testGetAccountPollsCreate (Logged out)
//    * Check response status as there is nothing to test
//    *
//    * @depends testAuth
//    * @return void
//    */
//   public function testGetAccountPollsCreateIn($auth)
//   {
//     $id = '1';

//     // Grab Page
//     $result = $this->actingAs($auth)
//       ->post('account/polls/create/' . $id);
//     print_r($result);
//   }


































/**
   * testGetAccountMyEvents (Logged out)
   * Check response status as there is nothing to test
   *
   * @return void
   */
  // public function testGetAccountMyEventsOut()
  // {
  //   // Grab page
  //   $result = $this->visit('account/my-events')
  //     ->see('SIGN IN TO YOUR SUARAY.COM ACCOUNT')
      // ->see('OR SIGN UP WITH YOUR SOCIAL ACCOUNT')
  //     ->seePageIs('/account/login');

  // }

  /**
   * testGetAccountMyEvents (Logged out)
   * Check response status as there is nothing to test
   *
   * @depends testAuth
   * @return void
   */
  // public function testGetAccountMyEventsIn($auth)
  // {
  //   // Grab page
  //   $result = $this->actingAs($auth)
  //     ->visit('account/my-events')
  //     ->see('SIGN IN TO YOUR SUARAY.COM ACCOUNT')
      // ->see('OR SIGN UP WITH YOUR SOCIAL ACCOUNT')
  //     ->seePageIs('/account/login');
  // }

  // *
  //  * TODO: Need index REMOTE_ADDR of $_SERVER to have a value before completing
  //  *
  //  * testGetAccountPayment
  //  * Check response status as there is nothing to test
  //  *
  //  * @depends testAuth
  //  * @return void

  // public function testGetAccountPaymentIn($auth)
  // {
  //   // Grab page
  //   $result = $this->actingAs($auth)
  //     ->visit('account/payment');
  //     print_r($_SERVER);
  //     // ->seePageIs('/account/payment');
  //     print_r($result);
  // }

  // REMOVED
  // /**
  //  * testGetAccountCreditCard
  //  * Check response status as there is nothing to test
  //  *
  //  * @depends testAuth
  //  * @return void
  //  */
  // public function testGetAccountCreditCardIn($auth)
  // {
  //   // Grab Page
  //   $result = $this->actingAs($auth)
  //     ->visit('account/credit-card');
  //     // ->seePageIs('account/credit-card');
  //     print_r($result);
  // }

  // /**
  //  * testGetAccountEmailVerification (Logged out)
  //  * Check response status as there is nothing to test
  //  *
  //  * @return void
  //  */
  // public function testGetAccountEmailVerification()
  // {
  //   $result = $this->visit('account/email/verification/{verification_cod
      // ->see('SIGN IN TO YOUR SUARAY.COM ACCOUNT')
      // ->see('OR SIGN UP WITH YOUR SOCIAL ACCOUNT')
      // ->seePageIs('/account/login');
  // }

  // /**
  //  * Test code checkin
  //  *
  //  * @depends testShowProfile
  //  * @return void
  //  */
  // public function testEventsPostTicketCodeOut($user)
  // {
  //   Session::start();
  //   $token = csrf_token();

  //   $data = [
  //     '_token' => $token,
  //   ];

  //   $result = $this->post('account/event/check-in/use', $data)
  //     ->seePageIs('/account/login');
  // }

  // /**
  //  * Test code checkin
  //  *
  //  * @depends testAuth
  //  * @depends testShowProfile
  //  * @return void
  //  */
  // public function testEventsPostTicketCodeIn($auth, $user)
  // {
  //   Session::start();
  //   $token = csrf_token();

  //   $data = [
  //     '_token' => $token,
  //     'ticketInventoryId' => null,
  //     'used' => null,
  //     'code' => null,
  //   ];

  //   $result = $this->actingAs($auth)
  //     ->post('account/event/check-in/use', $data);
  //     // ->seePageIs('account/event/check-in/use')
  //   print_r($result);
  // }

  // public function testFriendsMyEventInvitesCreate()
  // {
  //   Session::start();
  //   $token = csrf_token();

  //   $data = [
  //     '_token' => $token,
  //   ];

  //   $result = $this->post('account/friends/create/my-event-invites', $data)
  //     ->seePageIs('account/friends/create/my-event-invites')
  //     ->see('SIGN IN TO YOUR SUARAY.COM ACCOUNT')
      // ->see('OR SIGN UP WITH YOUR SOCIAL ACCOUNT')
  //     ->seePageIs('/account/login');
  //   print_r($result);
  // }

  // public function testFriendsInvitesUpdate()
  // {
  //   // Grab page
  //   $result = $this->actingAs($auth)
  //     ->visit('account/payment');
  //     // ->seePageIs('/account/payment');
  // }

  // REMOVED
  // /**
  //  * testGetAccountCreditCard
  //  * Check response status as there is nothing to test
  //  *
  //  * @depends testAuth
  //  * @return void
  //  */
  // public function testGetAccountCreditCardIn($auth)
  // {
  //   // Grab Page
  //   $result = $this->actingAs($auth)
  //     ->visit('account/credit-card');
  //     // ->seePageIs('account/credit-card');
  //     dd($result);
  // }

  // /**
  //  * testGetAccountEmailVerification (Logged out)
  //  * Check response status as there is nothing to test
  //  *
  //  * @return void
  //  */
  // public function testGetAccountEmailVerification()
  // {
  //   $result = $this->visit('account/email/verification/{verification_cod
      // ->see('SIGN IN TO YOUR SUARAY.COM ACCOUNT')
      // ->see('OR SIGN UP WITH YOUR SOCIAL ACCOUNT')
      // ->seePageIs('/account/login');
  // }

  // /**
  //  * Test code checkin
  //  *
  //  * @depends testShowProfile
  //  * @return void
  //  */
  // public function testEventsPostTicketCodeOut($user)
  // {
  //   Session::start();
  //   $token = csrf_token();

  //   $data = [
  //     '_token' => $token,
  //   ];
  //   $result = $this->post('account/event/check-in/use', $data)
  //     ->seePageIs('/account/login');
  // }

  // /**
  //  * Test code checkin
  //  *
  //  * @depends testAuth
  //  * @depends testShowProfile
  //  * @return void
  //  */
  // public function testEventsPostTicketCodeIn($auth, $user)
  // {
  //   Session::start();
  //   $token = csrf_token();

  //   $data = [
  //     '_token' => $token,
  //     'ticketInventoryId' => null,
  //     'used' => null,
  //     'code' => null,
  //   ];

  //   $result = $this->actingAs($auth)
  //     ->post('account/event/check-in/use', $data);
  //     // ->seePageIs('account/event/check-in/use')
  //   dd($result);
  // }

  // public function testFriendsMyEventInvitesCreate()
  // {
  //   Session::start();
  //   $token = csrf_token();

  //   $data = [
  //     '_token' => $token,
  //   ];

  //   $result = $this->post('account/friends/create/my-event-invites', $data)
  //     ->seePageIs('account/friends/create/my-event-invites')
  //     ->see('SIGN IN TO YOUR SUARAY.COM ACCOUNT')
      // ->see('OR SIGN UP WITH YOUR SOCIAL ACCOUNT')
  //     ->seePageIs('/account/login');
  //   dd($result);
  // }

  // public function testFriendsInvitesUpdate()

  // {
  //   Session::start();
  //   $token = csrf_token();

  //   $data = [
  //     '_token' => $token,
  //   ];

  //   $result = $this->post('account/friends/invites/update', $data)
  //     ->seePageIs('account/friends/invites/update')
  //     ->see('SIGN IN TO YOUR SUARAY.COM ACCOUNT')
      // ->see('OR SIGN UP WITH YOUR SOCIAL ACCOUNT')
  //     ->seePageIs('/account/login');
  //   dd($result);
  // }

  /**
   * Test password update
   *
   * @depends testShowProfile
   * @return void
   */
  // public function testPasswordUpdateReset($user)
  // {
  //   $password = [
  //     'data' => [
  //       'password' => [
  //         'email' => $user['data']['user']['email']
  //       ]
  //     ]
  //   ];

  //   $result = $this->visit('account/password/reset/{token}', $password)
  //     ->see('SIGN IN TO YOUR SUARAY.COM ACCOUNT')
      // ->see('OR SIGN UP WITH YOUR SOCIAL ACCOUNT')
  //     ->seePageIs('/account/login');
  // }

  // public function testPaymentUpdate()
  // {
  //   Session::start();
  //   $token = csrf_token();

  //   $data = [
  //     '_token' => $token,
  //   ];

  //   $result = $this->post('account/payment/{accountId}', $data)
  //     ->see('SIGN IN TO YOUR SUARAY.COM ACCOUNT')
      // ->see('OR SIGN UP WITH YOUR SOCIAL ACCOUNT')
  //     ->seePageIs('/account/login');
  // }

  // public function testPollsStore()
  // {
  //   Session::start();
  //   $token = csrf_token();

  //   $data = [
  //     '_token' => $token,
  //   ];

  //   $result = $this->post('account/polls/{id}', $data)
  //     ->see('SIGN IN TO YOUR SUARAY.COM ACCOUNT')
      // ->see('OR SIGN UP WITH YOUR SOCIAL ACCOUNT')
  //     ->seePageIs('/account/login');
  // }

  // public function testSchedulesCreate()
  // {
  //   Session::start();
  //   $token = csrf_token();

  //   $data = [
  //     '_token' => $token,
  //   ];

  //   $result = $this->post('events/schedules/create/{id}', $data)
  //     ->see('SIGN IN TO YOUR SUARAY.COM ACCOUNT')
      // ->see('OR SIGN UP WITH YOUR SOCIAL ACCOUNT')
  //     ->seePageIs('/account/login');
  // }

  // public function testDatesDelete()
  // {
  //   Session::start();
  //   $token = csrf_token();

  //   $data = [
  //     '_token' => $token,
  //   ];

  //   $result = $this->post('events/{eventId}/schedules/index/delete/date/{id}', $data)
  //     ->see('SIGN IN TO YOUR SUARAY.COM ACCOUNT')
      // ->see('OR SIGN UP WITH YOUR SOCIAL ACCOUNT')
  //     ->seePageIs('/account/login');
  // }

  // public function testSchedulesDelete()
  // {
  //   Session::start();
  //   $token = csrf_token();

  //   $data = [
  //     '_token' => $token,
  //   ];

  //   $result = $this->post('events/{eventId}/schedules/index/delete/schedule/{id}', $data)
  //     ->see('SIGN IN TO YOUR SUARAY.COM ACCOUNT')
      // ->see('OR SIGN UP WITH YOUR SOCIAL ACCOUNT')
  //     ->seePageIs('/account/login');

  //   // Check status
  // }
  //

  // /**
  //  * testCreateChoicesUpdate Logged in
  //  *
  //  * @depends testAuth
  //  * @return void
  //  */
  // public function testFriendsStoreIn($auth){

  //   $friendId = '5';

  //   $result = $this->actingAs($auth)
  //     ->visit('account/friends/create/request/' . $friendId);
  //   print_r($result);
  // }

  // /**
  //  * testCreateChoicesUpdate Logged in
  //  *
  //  * @depends testAuth
  //  * @return void
  //  */
  // public function testRequestDeleteIn($auth){

  //   $friendId = '5';

  //   $result = $this->actingAs($auth)
  //     ->visit('account/friends/delete/request/' . $friendId)
  //     ->seePageIs('/account/friends');
  // }

  // *
  //  * testCreateChoicesUpdate Logged in
  //  *
  //  * @depends testAuth
  //  * @return void

  // public function testFriendsDeleteIn($auth){

  //   $friendId = '5';

  //   $result = $this->actingAs($auth)
  //     ->withSession(['success_message' => 'Friend Deleted'])
  //     ->visit('account/friends/delete/' . $friendId);
  //     // ->see('Friend Deleted');
  //   print_r($this);
  // }

  // /**
  //  * ROUTE NOT IN USE, NO METHOD FOUND IN CONTROLLER
  //  * testCreateChoicesUpdate Logged in
  //  *
  //  * @depends testAuth
  //  * @return void
  //  */
  // public function testDoSettingsIn($auth){
  //   $result = $this->actingAs($auth)
  //     ->visit('account/settings/update');
  //   print_r($result);
  // }

    // /**
  //  * testGetAccountLoginProviderTwitter (Logged out)
  //  * Check response status as there is nothing to test
  //  *
  //  * @return void
  //  */
  // public function testGetAccountLoginProviderTwitter()
  // {
  //   $provider = 'twitter';

  //     // Grab Page
  //     $result = $this->visit('account/login/' . $provi
      // ->see('SIGN IN TO YOUR SUARAY.COM ACCOUNT')
      // ->see('OR SIGN UP WITH YOUR SOCIAL ACCOUNT')
      // ->seePageIs('/account/login');
  // }

  /**
   * testGetAccountLoginProviderinstagram (Logged out)
   * Check response status as there is nothing to test
   *
   * @return void
   */
  // public function testGetAccountLoginProviderInstagram()
  // {
  //   $provider = 'instagram';

  //     // Grab Page
  //     $result = $this->visit('account/login/' . $provider);

  //     // Check Status
  //
  // }

  // /**
  //  * testGetAccountLoginProviderFacebook (Logged out)
  //  * Check response status as there is nothing to test
  //  *
  //  * @return void
  //  */
  // public function testGetAccountLoginProviderFacebook()
  // {
  //   $provider = 'facebook';

  //     // Grab Page
  //     $result = $this->visit('account/login/' . $provi
      // ->see('SIGN IN TO YOUR SUARAY.COM ACCOUNT')
      // ->see('OR SIGN UP WITH YOUR SOCIAL ACCOUNT')
      // ->seePageIs('/account/login');
  // }

  // /**
  //  * testGetAccountLoginProviderGoogle (Logged out)
  //  * Check response status as there is nothing to test
  //  *
  //  * @return void
  //  */
  // public function testGetAccountLoginProviderGoogle()
  // {
  //   $provider = 'google';

  //     // Grab Page
  //     $result = $this->visit('account/login/' . $provi
      // ->see('SIGN IN TO YOUR SUARAY.COM ACCOUNT')
      // ->see('OR SIGN UP WITH YOUR SOCIAL ACCOUNT')
      // ->seePageIs('/account/login');
  // }

  // /**
  //  * testAccountLoginProviderCallbackTwitter (Logged out)
  //  * Check response status as there is nothing to test
  //  *
  //  * @return void
  //  */
  // public function testAccountLoginProviderCallbackTwitter()
  // {
  //   $provider = 'twitter';
  //   // Grab Page
  //   $result = $this->visit('account/login/' . $provider . '/callba
      // ->see('SIGN IN TO YOUR SUARAY.COM ACCOUNT')
      // ->see('OR SIGN UP WITH YOUR SOCIAL ACCOUNT')
      // ->seePageIs('/account/login');
  // }

  // /**
  //  * testAccountLoginProviderCallbackFacebook (Logged out)
  //  * Check response status as there is nothing to test
  //  *
  //  * @return void
  //  */
  // public function testAccountLoginProviderCallbackFacebook()
  // {
  //   $provider = 'facebook';
  //   // Grab Page
  //   $result = $this->visit('account/login/' . $provider . '/callba
      // ->see('SIGN IN TO YOUR SUARAY.COM ACCOUNT')
      // ->see('OR SIGN UP WITH YOUR SOCIAL ACCOUNT')
      // ->seePageIs('/account/login');
  // }

  // /**
  //  * testAccountLoginProviderCallbackInstagram (Logged out)
  //  * Check response status as there is nothing to test
  //  *
  //  * @return void
  //  */
  // public function testAccountLoginProviderCallbackInstagram()
  // {
  //   $provider = 'instagram';
  //   // Grab Page
  //   $result = $this->visit('account/login/' . $provider . '/callba
      // ->see('SIGN IN TO YOUR SUARAY.COM ACCOUNT')
      // ->see('OR SIGN UP WITH YOUR SOCIAL ACCOUNT')
      // ->seePageIs('/account/login');
  // }

  // /**
  //  * testAccountLoginProviderCallbackGoogle (Logged out)
  //  * Check response status as there is nothing to test
  //  *
  //  * @return void
  //  */
  // public function testAccountLoginProviderCallbackGoogle()
  // {
  //   $provider = 'google';
  //   // Grab Page
  //   $result = $this->visit('account/login/' . $provider . '/callba
      // ->see('SIGN IN TO YOUR SUARAY.COM ACCOUNT')
      // ->see('OR SIGN UP WITH YOUR SOCIAL ACCOUNT')
      // ->seePageIs('/account/login');
  // }

  /////// POST /////////
  // public function testPostRegisterOut()
  // {
  //   Session::start();
  //   $token = csrf_token();

  //   $data = [
  //     '_token' => $token,
  //   ];

  //   // Get register
  //   $result = $this->post('account/register', $data);
  //     ->see('SIGN IN TO YOUR SUARAY.COM ACCOUNT')
      // ->see('OR SIGN UP WITH YOUR SOCIAL ACCOUNT')
  //     ->seePageIs('/account/register');
  //   print_r($result);
  // }

  /**
   * Post register
   *
   * @depends testAuth
   * @param  array $auth users auth data
   * @return void
   */
  // public function testPostRegisterIn($auth)
  // {
  //   Session::start();
  //   $token = csrf_token();

  //   $data = [
  //     '_token' => $token,
  //   ];

  //   // Get register
  //   $result = $this->actingAs($auth)
  //     ->post('account/register', $data);
  // }

  // public function testPostAccountPasswordForgotOut()
  // {
  //   Session::start();
  //   $token = csrf_token();

  //   $data = [
  //     '_token' => $token,
  //   ];

  //   // Get forgot pass page
  //   $result = $this->post('account/password/forgot', $data);
  //   print_r($result);
  // }
  //
  // /**
  //  * POST FORGOT PASSWORD
  //  *
  //  * @depends testAuth
  //  * @param  array $auth users auth data
  //  * @return void
  //  */
  // public function testPostAccountPasswordForgotIn($auth)
  // {
  //   Session::start();
  //   $token = csrf_token();

  //   $data = [
  //     '_token' => $token,
  //   ];

  //   // Get forgot pass page
  //   $result = $this->actingAs($auth)
  //     ->visit('account/password/forgot', $data)
  //     ->see('SIGN IN TO YOUR SUARAY.COM ACCOUNT')
      // ->see('OR SIGN UP WITH YOUR SOCIAL ACCOUNT')
  //     ->seePageIs('/account/login');
  // }

  /**
   * Post to edit polls
   *
   * @depends testAuth
   * @param  array $auth user auth details
   * @return void
   */
  // public function testPostAccountPollsEditIn($auth)
  // {
  //   Session::start();
  //   $token = csrf_token();

  //   $data = [
  //     '_token' => $token,
  //   ];

  //   // Set poll id
  //   $pollId = 1;

  //   $result = $this->actingAs($auth)
  //     ->visit('account/polls/edit/' . $pollId, $data)
  //     ->see('SIGN IN TO YOUR SUARAY.COM ACCOUNT')
      // ->see('OR SIGN UP WITH YOUR SOCIAL ACCOUNT')
  //     ->seePageIs('/account/login');
  // }

  /**
   * Logged out post to edit poll
   *
   * @depends testAuth
   * @param  array $auth authed user details
   * @return void
   */
  // public function testPostAccountPollsEditOut($auth)
  // {
  //   Session::start();
  //   $token = csrf_token();

  //   $data = [
  //     '_token' => $token,
  //   ];

  //   // Set poll id
  //   $pollId = 1;

  //   $result = $this->actingAs($auth)
  //     ->visit('account/polls/edit/' . $pollId, $data)
  //     ->see('SIGN IN TO YOUR SUARAY.COM ACCOUNT')
      // ->see('OR SIGN UP WITH YOUR SOCIAL ACCOUNT')
  //     ->seePageIs('/account/login');
  // }
  //
  // /*
  //  * testGetAccountPayment (Logged out)
  //  * Check response status as there is nothing to test
  //  *
  //  * @return void
  //  */
  // public function testGetAccountPaymentOut()
  // {
  //   // Grab page
  //   $result = $this->visit('account/payment')
  //     ->see('SIGN IN TO YOUR SUARAY.COM ACCOUNT')
  //     ->see('OR SIGN UP WITH YOUR SOCIAL ACCOUNT')
  //     ->seePageIs('/account/login');
  // }

/////////////////////
/// ROUTES TO TEST //
/////////////////////

// |        | POST                           | account/choices/create/{id}                                   | create.choices.store             | App\Http\Controllers\AccountsController@doCreateChoices                  | auth       |
// |        | POST | account/choices/edit/{id}                                     | create.choices.update            | App\Http\Controllers\AccountsController@doUpdateChoice                   | auth       |
// |        | PUT | account/choices/edit/{id}                                     | create.choices.update            | App\Http\Controllers\AccountsController@doUpdateChoice                   | auth       |
// |        | DELETE | account/choices/edit/{id}                                     | create.choices.update            | App\Http\Controllers\AccountsController@doUpdateChoice                   | auth       |
// |        | POST                           | account/dashboard/event/{id}/resend/{reservation}/reservation | resend.reservation               | App\Http\Controllers\AccountsController@resendReservation                | auth       |
// |        | POST | account/friends/create/request/{id}                           | friends.store                    | App\Http\Controllers\AccountsController@doCreateRequest                  | auth       |
// |        | PUT | account/friends/create/request/{id}                           | friends.store                    | App\Http\Controllers\AccountsController@doCreateRequest                  | auth       |
// |        | DELETE | account/friends/create/request/{id}                           | friends.store                    | App\Http\Controllers\AccountsController@doCreateRequest                  | auth       |
// |        | POST | account/friends/delete/request/{id}                           | request.delete                   | App\Http\Controllers\AccountsController@doDeleteRequest                  | auth       |
// |        | PUT | account/friends/delete/request/{id}                           | request.delete                   | App\Http\Controllers\AccountsController@doDeleteRequest                  | auth       |
// |        | DELETE | account/friends/delete/request/{id}                           | request.delete                   | App\Http\Controllers\AccountsController@doDeleteRequest                  | auth       |
// |        | POST | account/friends/delete/{id}                                   | friends.delete                   | App\Http\Controllers\AccountsController@doDeleteFriend                   | auth       |
// |        | PUT | account/friends/delete/{id}                                   | friends.delete                   | App\Http\Controllers\AccountsController@doDeleteFriend                   | auth       |
// |        | DELETE | account/friends/delete/{id}                                   | friends.delete                   | App\Http\Controllers\AccountsController@doDeleteFriend                   | auth       |
// |        | POST | account/friends/update/{id}                                   | friends.update                   | App\Http\Controllers\AccountsController@doFriend                         | auth       |
// |        | PUT | account/friends/update/{id}                                   | friends.update                   | App\Http\Controllers\AccountsController@doFriend                         | auth       |
// |        | DELETE | account/friends/update/{id}                                   | friends.update                   | App\Http\Controllers\AccountsController@doFriend                         | auth       |
// |        | POST | account/settings/update                                       | do.settings                      | App\Http\Controllers\AccountsController@doSettings                       | auth       |
// |        | PUT | account/settings/update                                       | do.settings                      | App\Http\Controllers\AccountsController@doSettings                       | auth       |
// |        | DELETE | account/settings/update                                       | do.settings                      | App\Http\Controllers\AccountsController@doSettings                       | auth       |
// |        | POST | account/polls/edit/{id}                                       | create.polls.update              | App\Http\Controllers\AccountsController@doUpdatePolls                    | auth       |
// |        | PUT | account/polls/edit/{id}                                       | create.polls.update              | App\Http\Controllers\AccountsController@doUpdatePolls                    | auth       |
// |        | DELETE | account/polls/edit/{id}                                       | create.polls.update              | App\Http\Controllers\AccountsController@doUpdatePolls                    | auth       |
// |        | POST | events/{event}/schedules/update/{id}                          | schedules.update                 | App\Http\Controllers\AccountsController@doScheduleUpdate                 | auth       |
// |        | PUT | events/{event}/schedules/update/{id}                          | schedules.update                 | App\Http\Controllers\AccountsController@doScheduleUpdate                 | auth       |
// |        | DELETE | events/{event}/schedules/update/{id}                          | schedules.update                 | App\Http\Controllers\AccountsController@doScheduleUpdate                 | auth       |
}
