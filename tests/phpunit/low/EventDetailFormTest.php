<?php

use App\Helpers\ApiHelper;
use App\Auth\ApiGenericUser;

class EventDetailFormTest extends TestCase {

 /**
   * testPostAccountLogin (Logged out)
   *
   *
   */
  public function testUser()
  {
    // Instanciate apihelper
    $api = new \App\Helpers\ApiHelper;

    // Grab a user from
    $user = $api->show('users', '1');

    // Set usersname
    $user = $user['data']['user'];

    $auth = new ApiGenericUser(['id' => $user['id']]);

    return $auth;
  }

  /**
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
   * This will test comments in event detail
   *
   * @depends testUser
   * @depends testGetEvents
   * @return void
   *
   */
  public function testEventComment($auth, $events)
  {
    $event = $events[0];

    // Sets a unique value for comment testing to 'see'
    $uniqueComment = rand(1, 999999);

    $this->actingAs($auth)
         ->visit('events/' . $event['slug'])
         ->type('Dev Test Comment' . $uniqueComment, 'comment')
         ->press('submit comment')
         ->see('Dev Test Comment' . $uniqueComment);

  }

  /**
   * This will test reviews in event detail
   *
   * @depends testUser
   * @depends testGetEvents
   * @return void
   */


  public function testEventReviews($auth, $events)
  {
    $event = $events[0];

    // Sets a unique value for comment testing to 'see'
    $uniqueReview = rand(1, 999999);

    $this->actingAs($auth)
         ->visit('events/' . $event['slug'])
         ->type('Dev Test Review' . $uniqueReview, 'review')
         ->select('4', 'rating')
         ->press('submit review')
         ->see('Dev Test Review' . $uniqueReview);

  }

  /**
   * This will make event available by shareable link only
   *
   * @depends testUser
   * @depends testGetEvents
   * @return void
   */
  public function testShareableLinkOnly($auth, $events)
  {
    $event = $events[0];

    $visit = $this->actingAs($auth)
      ->visit('events/' . $event['slug']);

    $click = $this->click('Shareable Link')
      ->see('Event Updated Successfully');
  }

  /**
   * This will make event available by friends only
   *
   * @depends testUser
   * @depends testGetEvents
   * @depends testShareableLinkOnly
   * @return void
   */
  public function testFriendsOnly($auth, $events)
  {
    $event = $events[0];

    $visit = $this->actingAs($auth)
      ->visit('events/' . $event['slug']);

    $click = $this->click('Friends Only')
      ->see('Event Updated Successfully');
  }

  /**
   * This will make event available by invite only
   *
   * @depends testUser
   * @depends testGetEvents
   * @depends testFriendsOnly
   * @return void
   */
  public function testInviteOnly($auth, $events)
  {
    $event = $events[0];

    $this->actingAs($auth)
         ->visit('events/' . $event['slug'])
         ->click('By Invite')
         ->see('Event Updated Successfully')
         ->click('By Invite')
         ->see('Event Updated Successfully');
  }

  /**
   * This will test sending user feedback
   *
   * @depends testUser
   * @depends testGetEvents
   * @return void
   */
  public function testFeedback($auth, $events)
  {
    $event = $events[0];


    $this->actingAs($auth)
         ->visit('events/' . $event['slug'])
         ->type('This is only a test', 'description')
         ->type('Fake User', 'name')
         ->press('send-feedback')
         ->see('Thank You!');

  }

  /**
   * This will test sending an event invite from event detail
   *
   * @depends testUser
   * @depends testGetEvents
   * @return void
   */
  public function testFriendInvite($auth, $events)
  {
    $event = $events[0];


    $this->actingAs($auth)
         ->visit('events/' . $event['slug'])
         ->press('friend-invite')
         ->see('Sent');

  }

  /**
   * This will test updating event
   *
   * @depends testUser
   * @depends testGetEvents
   * @return void
   */
  public function testUpdateEvent($auth, $events)
  {
    $event = $events[0];

    $uniqueDescription = rand(1, 999999);

    $this->actingAs($auth)
         ->visit('events/' . $event['slug'] . '/updateevent')
         ->type('Dev Test Description' . $uniqueDescription, 'description')
         ->press('save')
         ->see($uniqueDescription);

  }

}
