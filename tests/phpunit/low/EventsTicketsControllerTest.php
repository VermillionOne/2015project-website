<?php

use App\Helpers\ApiHelper;

class EventsTicketsControllerTest extends TestCase
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

  ///////////////////////////////////////////////
  /// TESTS BELOW THIS POINT ARE NOT COMPLETE ///
  ///////////////////////////////////////////////

  /**
   * Grab an event
   *
   * @depends testCreateEvent
   * @return array Event data
   */
  public function testGetEvent($events)
  {
    // Grab event
    $event = $this->call('GET', 'events/' . $events['slug']);

    // Check Response
    $this->assertResponseOk();

    // Extract data to view
    $event = $event->original;

    // Return data
    return $event;
  }

  /**
   * Get reservation confirmation (Logged out)
   *
   * @depends testGetEvent
   * @param  array $event event data
   * @return void
   */
  public function testGetReservationConfirmation($event)
  {
    // Define slug
    $slug = $event['slug'];

    // Grab confimation
    $result = $this->call('GET', 'events/' . $slug . '/reservation/confirmation');

    // Check status
    $this->assertResponseStatus(302);
  }

/////////////////////
/// ROUTES TO TEST //
/////////////////////

// |        | POST                           | events/{id}/reservation/create                                | events.create.reservation        | App\Http\Controllers\EventsTicketsController@doReserve                   |            |
// |        | GET|HEAD|POST|PUT|PATCH|DELETE | events/{slug}/confirmation                                    | tickets.confirmation             | App\Http\Controllers\EventsTicketsController@confirmation                |            |
// |        | GET|HEAD|POST|PUT|PATCH|DELETE | events/{slug}/tickets                                         | events.tickets                   | App\Http\Controllers\EventsTicketsController@show                        |            |
// |        | POST                           | events/{slug}/tickets/charge                                  | events.tickets.charge            | App\Http\Controllers\EventsTicketsController@doCharge                    |            |
// |        | GET|HEAD|POST|PUT|PATCH|DELETE | tickets/{hash}                                                |                                  | App\Http\Controllers\EventsTicketsController@qr                          |            |
}
