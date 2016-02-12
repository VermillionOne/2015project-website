<?php

class EventsPhotosControllerTest extends TestCase
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
  public function testGetEvent($event)
  {
    // Grab event
    $event = $this->call('GET', 'events/' . $event['slug']);

    // Check Response
    $this->assertResponseOk();

    // Extract data to view
    $event = $event->original;

    // Return data
    return $event;
  }

  /**
   * Get events gallary
   * When logged out we are redirected to login page
   * resulting in a 302 status (Logged out)
   *
   * @depends testGetEvent
   * @return void
   */
  public function testGetEventsGallery($event)
  {
    // Define slug
    $slug = $event['slug'];

    // Grab event data
    $result = $this->call('GET', 'account/' . $slug . '/gallery');

    // Check status
    $this->assertResponseStatus(302);

    // Check if logged out is redirected
    $this->assertRedirectedToRoute('register');
  }

  /**
   * Delete a gallery image
   * When logged out we are redirected to register page
   * resulting in a 302 status (Logged out)
   *
   * @depends testGetEvent
   * @return void
   */
  public function testGetEventsGalleryDelete($event) {

    // Set slug
    $slug = $event['slug'];

    // Set Photos
    $photos = $event['events']['photos'];

    if (isset($photos[0]['id'])) {

      // Set image id
      $imageId = $photos[0]['id'];

      // Delete Image
      $result = $this->call('GET', 'account/gallery/' . $slug . '/delete/' . $imageId);

      // Check Status
      $this->assertResponseStatus(302);

      // Check if logged out is redirected
      $this->assertRedirectedToRoute('register');

    } else {
      echo 'Event does not have images';
    }
  }

  /**
   * Set events featured image
   * When logged out we are redirected to register page
   * resulting in a 302 status (Logged out)
   *
   * @depends testGetEvent
   * @return void
   */
  public function testGetEventsGalleryFeatured($event) {

    // Set slug
    $slug = $event['slug'];

    // Set Photos
    $photos = $event['events']['photos'];

    if (isset($photos[0]['id'])) {

      // Set image id
      $imageId = $photos[0]['id'];

      // Set featured
      $result = $this->call('GET', 'account/gallery/' . $slug . '/featured/' . $imageId);

      // Check status
      $this->assertResponseStatus(302);

      // Check if logged out is redirected
      $this->assertRedirectedToRoute('register');

    } else {
      echo 'Image does not have images';
    }
  }

  // /**
  //  * Show the events gallery
  //  *
  //  * @depends testGetEvent
  //  * @return void
  //  */
  // public function testGetEventsPhotos($event) {

  //   // Set slug
  //   $slug = $event['slug'];

  //   // Grab event gallery
  //   $result = $this->call('GET', 'events/' . $slug . '/photos');

  //   // Check Response
  //   $this->assertResponseOk();

  //   // Pull view data
  //   $result = $result->original;

  //   // Check photos are set
  //   $this->assertTrue(isset($result['events']['photos']));
  //   $this->assertTrue(isset($result['events']['featuredPhoto']));
  // }
}
