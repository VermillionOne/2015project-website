<?php


class BrowseClickTest extends TestCase {

  public function testGetEvents()
  {
    // Instanciate apihelper
    $api = new \App\Helpers\ApiHelper;

    // Grab a user from
    $events = $api->index('events?filter[and][][is_published]=1&filter[and][][user_id]=1');

    $events = $events['data']['events'];

    return $events;
  }

  // TODO: Reseed killed all my home page events...coming back
  /**
   * Will take user to event page (Logged out)
   *
   * @depends testGetEvents
   */
  // public function testViewEvent($events)
  // {
  //   $event = $events[0];

  //   $visit = $this->visit('/');

  //   $test = $this->click($event['title'])
  //     ->see('details')
  //     ->see('invite');

  // }

 // /**
 //   * Will take user to search results with concerts as query (Logged out)
 //   *
 //   *
 //   */
 //  public function testBrowseConcerts()
 //  {
 //    $this->visit('/')
 //         ->click('concerts')
 //         ->seePageIs('events/search?q=concerts');

 //  }

 // /**
 //   * Will take user to search results with conventions as query (Logged out)
 //   *
 //   *
 //   */
 //  public function testBrowseConventions()
 //  {
 //    $this->visit('/')
 //         ->click('conventions')
 //         ->seePageIs('events/search?q=conventions');

 //  }

 // /**
 //   * Will take user to search results with festivals as query (Logged out)
 //   *
 //   *
 //   */
 //  public function testBrowseFestivals()
 //  {
 //    $this->visit('/')
 //         ->click('festivals')
 //         ->seePageIs('events/search?q=festivals');

 //  }

 // /**
 //   * Will take user to search results with nightlife as query (Logged out)
 //   *
 //   *
 //   */
 //  public function testBrowseNightlife()
 //  {
 //    $this->visit('/')
 //         ->click('nightlife')
 //         ->seePageIs('events/search?q=nightlife');

 //  }

 // /**
 //   * Will take user to search results with parties as query (Logged out)
 //   *
 //   *
 //   */
 //  public function testBrowseParties()
 //  {
 //    $this->visit('/')
 //         ->click('parties')
 //         ->seePageIs('events/search?q=parties');

 //  }

}
