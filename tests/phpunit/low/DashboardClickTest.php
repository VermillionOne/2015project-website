<?php

use App\Helpers\ApiHelper;
use App\Auth\ApiGenericUser;

class DashboardClickTest extends TestCase {

  public function testGetUser()
  {
    // Instanciate apihelper
    $api = new \App\Helpers\ApiHelper;

    // Grab a user from
    $user = $api->index('users?filter[and][][id]=1&with[]=events');

    // Set usersname
    $user = $user['data']['users'][0];

    return $user;
  }

  /**
   * testPostAccountLogin (Logged out)
   *
   * @depends testGetUser
   */
  public function testAuthUser($user)
  {
    $auth = new ApiGenericUser(['id' => $user['id']]);

    return $auth;

  }

  /**
   * Get users events
   *
   * @depends testAuthUser
   * @param  array      $auth
   * @return void
   */
  public function testGetUsersEvents($auth)
  {
    // Instanciate apihelper
    $api = new \App\Helpers\ApiHelper;

    $events = $api->index('events?filter[and][][user_id]=' . $auth->id . '&sort[desc][]=id');

    return $events;
  }

  /**
   * This will send user to analytics from dashboard
   *
   * @depends testAuthUser
   * @depends testGetUser
   * @depends testGetUsersEvents
   * @return void
   */
  public function testDashboardAnalytics($auth, $user, $events)
  {
    $events = $events['data']['events'];

    $visit = $this->actingAs($auth)
      ->visit('account/dashboard');

    $click = $this->click('event-view-analytics-' . $events[0]['id'])
      ->seePageIs('account/dashboard/event/' . $events[0]['id']);
  }

  /**
   * This will send user to single event photos from dashboard
   *
   * @depends testAuthUser
   * @depends testGetUser
   * @depends testGetUsersEvents
   * @return void
   */
  public function testDashboardPhotos($auth, $user, $events)
  {
    $events = $events['data']['events'];

    $this->actingAs($auth)
         ->visit('account/dashboard')
         ->click('event-edit-photos-' . $events[0]['id'])
         ->seePageIs('account/' . $events[0]['slug'] . '/gallery');
  }

  /**
   * This will send user to single event tickets from dashboard
   *
   * @depends testAuthUser
   * @depends testGetUser
   * @depends testGetUsersEvents
   * @return void
   */
  public function testDashboardTickets($auth, $user, $events)
  {
    $events = $events['data']['events'];

    $this->actingAs($auth)
         ->visit('account/dashboard')
         ->click('event-edit-tickets-' . $events[0]['id'])
         ->seePageIs('account/tickets/index/' . $events[0]['id']);
  }

  /**
   * This will send user to single event schedules from dashboard
   *
   * @depends testAuthUser
   * @depends testGetUser
   * @depends testGetUsersEvents
   * @return void
   */
  public function testDashboardSchedules($auth, $user, $events)
  {
    $events = $events['data']['events'];

    $this->actingAs($auth)
         ->visit('account/dashboard')
         ->click('event-edit-schedules-' . $events[0]['id'])
         ->seePageIs('events/' . $events[0]['id'] . '/schedules/index');
  }

  /**
   * This will send user to signle event detail update from dashboard
   *
   * @depends testAuthUser
   * @depends testGetUser
   * @depends testGetUsersEvents
   * @return void
   */
  public function testDashboardDetails($auth, $user, $events)
  {
    $events = $events['data']['events'];

    $this->actingAs($auth)
         ->visit('account/dashboard')
         ->click('event-edit-details-' . $events[0]['id'])
         ->seePageIs('events/' . $events[0]['slug'] . '/updateevent');
  }

  /**
   * This will send user to event detail by clicking event title
   *
   * @depends testAuthUser
   * @depends testGetUser
   * @depends testGetUsersEvents
   * @return void
   */
  public function testViewEventBySlug($auth, $user, $events)
  {
    $events = $events['data']['events'];

    $this->actingAs($auth)
         ->visit('account/dashboard')
         ->click('view-by-slug-' . $events[0]['id'])
         ->seePageIs('events/' . $events[0]['slug']);
  }

  /**
   * This will send user to event detail by clicking event image
   *
   * @depends testAuthUser
   * @depends testGetUser
   * @depends testGetUsersEvents
   * @return void
   */
  public function testViewEventByImage($auth, $user, $events)
  {
    $events = $events['data']['events'];

    $this->actingAs($auth)
         ->visit('account/dashboard')
         ->click('view-by-image-' . $events[0]['id'])
         ->seePageIs('events/' . $events[0]['slug']);
  }

  ////////////////////////////////////////////////////////////
 //// TESTS BELOW ARE TESTING 'MORE' LINK THROUGHOUT DASH ///
////////////////////////////////////////////////////////////

  /**
   * This will send user to update event information
   *
   * @depends testAuthUser
   * @depends testGetUser
   * @depends testGetUsersEvents
   * @return void
   */
  public function testMoreDetails($auth, $user, $events)
  {
    $events = $events['data']['events'];

    $this->actingAs($auth)
         ->visit('account/dashboard/event/' . $events[0]['id'])
         ->click('Edit : Details')
         ->seePageIs('events/' . $events[0]['slug'] . '/updateevent');
  }

  /**
   * This will send user to update event images
   *
   * @depends testAuthUser
   * @depends testGetUser
   * @depends testGetUsersEvents
   * @return void
   */
  public function testMorePhotos($auth, $user, $events)
  {
    $events = $events['data']['events'];

    $this->actingAs($auth)
         ->visit('account/dashboard/event/' . $events[0]['id'])
         ->click('Edit : Photos')
         ->seePageIs('account/' . $events[0]['slug'] . '/gallery');
  }

  /**
   * This will send user to event schedules index
   *
   * @depends testAuthUser
   * @depends testGetUser
   * @depends testGetUsersEvents
   * @return void
   */
  public function testMoreSchedules($auth, $user, $events)
  {
    $events = $events['data']['events'];

    $this->actingAs($auth)
         ->visit('account/dashboard/event/' . $events[0]['id'])
         ->click('Edit : Schedules')
         ->seePageIs('events/' . $events[0]['id'] . '/schedules/index');
  }

  /**
   * This will send user to event tickets index
   *
   * @depends testAuthUser
   * @depends testGetUser
   * @depends testGetUsersEvents
   * @return void
   */
  public function testMoreTickets($auth, $user, $events)
  {
    $events = $events['data']['events'];

    $this->actingAs($auth)
         ->visit('account/dashboard/event/' . $events[0]['id'])
         ->click('Edit : Tickets')
         ->seePageIs('account/tickets/index/' . $events[0]['id']);
  }

  /**
   * This will send user to event detail page
   *
   * @depends testAuthUser
   * @depends testGetUser
   * @depends testGetUsersEvents
   * @return void
   */
  public function testMoreEvent($auth, $user, $events)
  {
    $events = $events['data']['events'];

    $this->actingAs($auth)
         ->visit('account/dashboard/event/' . $events[0]['id'])
         ->click('View : Event')
         ->seePageIs('events/' . $events[0]['slug']);
  }

  /**
   * This will send user to event analytics page
   *
   * @depends testAuthUser
   * @depends testGetUser
   * @depends testGetUsersEvents
   * @return void
   */
  public function testMoreAnalytics($auth, $user, $events)
  {
    $events = $events['data']['events'];

    $this->actingAs($auth)
         ->visit('account/dashboard/event/' . $events[0]['id'])
         ->click('View : Analytics')
         ->seePageIs('account/dashboard/event/' . $events[0]['id']);
  }

}
