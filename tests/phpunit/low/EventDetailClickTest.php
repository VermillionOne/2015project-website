<?php

use App\Helpers\ApiHelper;
use App\Auth\ApiGenericUser;

class EventDetailClickTest extends TestCase {

  /**
   * testPostAccountLogin (Logged out)
   *
   * @return $user
   */
  public function testLogin()
  {
    // Instanciate apihelper
    $api = new \App\Helpers\ApiHelper;

    // Grab a user from
    $user = $api->show('users', '2');

    // Set usersname
    $user = $user['data']['user'];

    return $user;
  }

  /**
   * testPostAccountLogin (Logged out)
   *
   * @depends testLogin
   */
  public function testUser($user)
  {
    // Instanciate apihelper
    $api = new \App\Helpers\ApiHelper;

    $auth = new ApiGenericUser(['id' => $user['id']]);

    return $auth;
  }

  /**
   * Get events
   *
   * @depends testUser
   * @return $events
   */
  public function testGetEvents($auth)
  {
    // Instanciate apihelper
    $api = new \App\Helpers\ApiHelper;

    // Grab a user from
    $events = $api->index('events?filter[and][][is_published]=1&filter[and][][user_id]=' . $auth->id);

    $events = $events['data']['events'];

    return $events;
  }

  /**
   * This will send  logged out user to login page
   *
   * @depends testGetEvents
   * @return void
   */
  public function testRsvpSignInLink($events)
  {
    $event = $events[0];

    $this->visit('events/' . $event['slug'])
         ->click('rsvp-sign-in')
         ->seePageIs('account/login');
  }

  /**
   * This will send creator of event to edit event details
   *
   * @depends testUser
   * @depends testGetEvents
   * @return void
   */
  public function testOwnedEventEditDetails($auth, $events)
  {
    $event = $events[0];

    $visit = $this->actingAs($auth)
      ->visit('events/' . $event['slug']);

    $click = $this->click('Edit : Details')
      ->seePageIs('events/' . $event['id'] . '/updateevent');
  }

  /**
   * This will send creator of event to gallery
   *
   * @depends testUser
   * @depends testGetEvents
   * @return void
   */
  public function testOwnedEventEditPhotos($auth, $events)
  {
    $event = $events[0];

    $this->actingAs($auth)
         ->visit('events/' . $event['slug'])
         ->click('Edit : Photos')
         ->seePageIs('account/' . $event['slug'] . '/gallery');
  }

  /**
   * This will send creator of event to dashboard / schedules
   *
   * @depends testUser
   * @depends testGetEvents
   * @return void
   */
  public function testOwnedEventEditSchedules($auth, $events)
  {
    $event = $events[0];

    $this->actingAs($auth)
         ->visit('events/' . $event['slug'])
         ->click('Edit : Schedules')
         ->seePageIs('events/' . $event['id'] . '/schedules/index');
  }

  /**
   * This will send creator of event to dashboard / tickets
   *
   * @depends testUser
   * @depends testGetEvents
   * @return void
   */
  public function testOwnedEventEditTickets($auth, $events)
  {
    $event = $events[0];

    $this->actingAs($auth)
         ->visit('events/' . $event['slug'])
         ->click('Edit : Tickets')
         ->seePageIs('account/tickets/index/' . $event['id']);
  }

  /**
   * This will send creator of event to dashboard / analytics
   *
   * @depends testUser
   * @depends testGetEvents
   * @return void
   */
  public function testOwnedEventViewAnalytics($auth, $events)
  {
    $event = $events[0];

    $this->actingAs($auth)
         ->visit('events/' . $event['slug'])
         ->click('View : Analytics')
         ->seePageIs('account/dashboard/event/' . $event['id']);
  }

  /**
   * This will send user to search results with event creator as search parameter
   *
   * @depends testLogin
   * @depends testUser
   * @depends testGetEvents
   * @return void
   */
  public function testSearchMoreFromUser($user, $auth, $events)
  {
    $event = $events[0];

    $this->actingAs($auth)
         ->visit('events/' . $event['slug'])
         ->click($user['firstName'])
         ->seePageIs('events/search?q=' . $user['username']);
  }

  /**
   * This will send user to gallery from view all
   *
   * @depends testUser
   * @depends testGetEvents
   * @return void
   */
  public function testViewAllPhotos($auth, $events)
  {
    $event = $events[0];

    $this->actingAs($auth)
         ->visit('events/' . $event['slug'])
         ->click('View All')
         ->seePageIs('account/' . $event['slug'] . '/gallery');
  }

}
