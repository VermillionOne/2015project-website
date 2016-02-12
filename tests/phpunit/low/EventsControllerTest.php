<?php

class EventsControllerTest extends TestCase
{
  /**
   * Test Create Event
   *
   * @return event
   */
  public function testCreateEvent()
  {
    $eventData = [
      'user_id' => '1',
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

  /**
   * Show event
   *
   * @depends testCreateEvent
   * @return void
   */
  public function testGetEventsShow($event)
  {
    // Grab the event from api using website
    $response = $this->call('GET', 'events/' . $event['slug']);

    // Check response
    $this->assertResponseOk();

    // Grab the views data
    $data = $response->original;

    // Check if isset
    $this->assertTrue(isset($data['eventId']));

    // Check if equal
    $this->assertEquals($event['slug'], $data['slug']);
    $this->assertEquals($event['id'], $data['eventId']);
    $this->assertTrue(isset($data['hash']));

    return $data;
  }

  ///////////////////////////////////////////////
  /// TESTS BELOW THIS POINT ARE NOT COMPLETE ///
  ///////////////////////////////////////////////



  // /**
  //  * Test show above event as private
  //  *
  //  * @depends testGetEventsShow
  //  * @return void
  //  */
  // public function testGetEventsShowPrivate($data)
  // {
  //   // Define hash
  //   $hash = $data['hash'];
  //   dd($hash);
  //   $response = $this->call('GET', 'private/' . $hash);
  //   dd($response);
  //   // $response->assertResponseOk();
  // }

  public function testGetEventsSignuploadmedia()
  {
    $response = $this->call('GET', 'events/signuploadmedia');

    // Check response
    $this->assertRedirectedToRoute('register');
  }

  public function testGetEventsCreate()
  {
    $response = $this->call('GET', 'events/create/{id?}');

    // Check response
    $this->assertRedirectedToRoute('register');
  }

  /**
   * Get Events Times (Logged out)
   * @return void
   */
  public function testGetEventsTimes()
  {
    $response = $this->call('GET', 'events/{slug}/times');

    // Check response
    $this->assertRedirectedToRoute('register');
  }

  public function testGetEventsSearch()
  {
    $response = $this->call('GET', 'events/search');

    // Check response
    $this->assertResponseOk();
  }

  public function testGetEventsAllEvents()
  {
    // Get request URL
    $response = $this->call('GET', 'events/allevents');

    // Define the data
    $data = $response->original;

    // Check first index
    $this->assertTrue(isset($data[0]));
    $this->assertTrue(isset($data[0]['title']));
    $this->assertTrue(isset($data[0]['slug']));
    $this->assertTrue(isset($data[0]['address1']));
    $this->assertTrue(isset($data[0]['zipcode']));
  }

/////////////////////
/// ROUTES TO TEST //
/////////////////////

// |        | POST                           | account/polls/vote/{id}                                       | polls.vote.store                 | App\Http\Controllers\EventsController@doCreatePollVote                   | auth       |
// |        | GET|HEAD|POST|PUT|PATCH|DELETE | events/{id}/updateevent                                       | events.update-event              | App\Http\Controllers\EventsController@showEventUpdate                    | auth       |
// |        | POST                           | events/{id}/updateevent/publish                               | events.doUpdatePublish           | App\Http\Controllers\EventsController@doEventPublish                     | auth       |
// |        | GET|HEAD|POST|PUT|PATCH|DELETE | events/{id}/updateevent/update                                | events.doUpdateEvent             | App\Http\Controllers\EventsController@doEventUpdate                      | auth       |
// |        | POST                           | events/{slug}/comments                                        | events.comments                  | App\Http\Controllers\EventsController@doComments                         |            |
// |        | POST                           | events/{slug}/comments/update                                 | comments.update                  | App\Http\Controllers\EventsController@doUpdateComments                   |            |
// |        | POST                           | events/{slug}/create/event-invites                            | events.eventInvitesCreate        | App\Http\Controllers\EventsController@doEventInvites                     |            |
// |        | POST                           | events/{slug}/invite                                          | events.email                     | App\Http\Controllers\EventsController@shareViaEmail                      |            |
// |        | POST                           | events/{slug}/rsvp/update                                     | rsvp.update                      | App\Http\Controllers\EventsController@doUpdateRsvp                       |            |
// |        | GET|HEAD|POST|PUT|PATCH|DELETE | events/categories                                             | events.categories                | App\Http\Controllers\EventsController@showCategories                     |            |
// |        | POST                           | events/create/{id?}                                           | events.post                      | App\Http\Controllers\EventsController@doCreate                           | auth       |
// |        | PUT                            | events/create/{id?}                                           | events upatue                    | App\Http\Controllers\EventsController@doCreate                           | auth       |
// |        | POST                           | events/{slug}/reviews/update                                  | reviews.update                   | App\Http\Controllers\EventsController@doUpdateReviews                    |            |
// |        | GET|HEAD|POST|PUT|PATCH|DELETE | events/{slug}/updateimages                                    | events.update-images             | App\Http\Controllers\EventsController@showImagesUpdate                   | auth       |
// |        | POST                           | events/{slug}/rsvp                                            | events.rsvp                      | App\Http\Controllers\EventsController@postRsvp                           |            |
// |        | POST                           | events/{slug}/updateevent/updates                             | events.updates                   | App\Http\Controllers\EventsController@doEventUpdate                      |            |
}
