<?php

use App\Helpers\ApiHelper;

class SearchClickTest extends TestCase {

  public function testGetEvent()
  {
    // Instanciate ApiHelper
    $api = new \App\Helpers\ApiHelper;

    // Grab an event from the DB using api
    $event = $result = $api->index('events?with[]=user');

    // Define the event
    $event = $event['data']['events'][0];

    return $event;
  }

 /**
   *
   * Will take user to search results with bars as query (Logged out)
   *
   *
   */
  public function testViewTag()
  {

   $this->visit('events/search?q=nightlife&startDate=&endDate=&city=')
        ->click('bars')
        ->seePageIs('events/search?q=bars');

  }

 /**
   * Will take user to event page (Logged out)
   *
   * @depends testGetEvent
   *
   */
  public function testViewEventByTitle($event)
  {

   $this->visit('events/search?q=nightlife&startDate=&endDate=&city=')
        ->click($event['title'])
        ->seePageIs('events/' . $event['slug']);

  }

 /**
   * Will take user to event page (Logged out)
   *
   * @depends testGetEvent
   *
   */
  public function testViewEventByButton($event)
  {

   $this->visit('events/search?q=nightlife&startDate=&endDate=&city=')
        ->click('view-' . $event['slug'])
        ->seePageIs('events/' . $event['slug']);

  }

 /**
   * Will take user to event page (Logged out)
   *
   * @depends testGetEvent
   *
   */
  public function testViewEventByImage($event)
  {

   $this->visit('events/search?q=nightlife&startDate=&endDate=&city=')
        ->click($event['id'] . '-by-image')
        ->seePageIs('events/' . $event['slug']);

  }

 /**
   * Will take user to event creators profile (Logged out)
   *
   * @depends testGetEvent
   *
   */
  public function testViewEventCreatorProfile($event)
  {

   $this->visit('events/search?q=nightlife&startDate=&endDate=&city=')
        ->click($event['user']['firstName'] . ' ' . $event['user']['lastName'])
        ->seePageIs('users/' . $event['user']['username']);

  }

}
