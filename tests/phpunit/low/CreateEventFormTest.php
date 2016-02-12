<?php

use App\Helpers\ApiHelper;
use App\Auth\ApiGenericUser;

class CreateEventFormTest extends TestCase {

 /**
   * testPostAccountLogin (Logged out)
   *
   *
   */
  public function testAuthUser()
  {
    // Instanciate apihelper
    $api = new \App\Helpers\ApiHelper;

    // Grab a user from
    $user = $api->show('users', '3?with[]=events');

    // Set usersname
    $user = $user['data']['user'];

    $auth = new ApiGenericUser(['id' => $user['id']]);

    return $auth;

  }

  /**
   * This will create an event with only one date
   * No options selected
   *
   * @depends testAuthUser
   *
   * @return void
   */
  public function testCreateEventSingleDate($auth)
  {
    // Image path to be loaded in create form
    $testImage = 'assets/img/transcoding/photo/generating-660x320.png';

    // Sets randdom event number to be used to create and to 'see' on creation
    $eventNumber = rand(1, 999999);

    $this->actingAs($auth)
         ->visit('events/create?tab=freemium')
         ->type('Dev Test event # ' . $eventNumber, 'title')
         ->type('Dev test description', 'description')
         ->select('6', 'timeZoneId')
         ->type('12/15/2016', 'startDate')
         ->type('12/15/2016', 'endDate')
         ->select('1:00', 'startTime')
         ->select('3:00', 'endTime')
         ->select('4', 'category1')
         ->type('dev, fake, event', 'tags')
         ->type('50 S. Stephanie', 'address1')
         ->type('Henderson', 'city')
         ->type('Nevada', 'state')
         ->type('89011', 'zipcode')
         ->attach($testImage , 'files')
         ->press('create-event')
         ->see('Please wait')
         ->see('dev-test-event-' . $eventNumber);
  }

 /**
   * This will create an event with multiple dates, no final date
   * No options are selected
   *
   * @depends testAuthUser
   *
   * @return void
   */
  public function testCreateOngoingRecurringDate($auth)
  {
    // Image path to be loaded in create form
    $testImage = 'assets/img/transcoding/photo/generating-660x320.png';

    // Sets randdom event number to be used to create and to 'see' on creation
    $eventNumber = rand(1, 999999);

    $createData = $this->actingAs($auth)
      ->visit('events/create?tab=freemium')
      ->type('Dev Test event # ' . $eventNumber, 'title')
      ->type('Dev test description', 'description')
      ->select('6', 'timeZoneId')
      ->type('12/15/2016', 'startDate')
      ->type('12/15/2016', 'endDate')
      ->select('1:00', 'startTime')
      ->select('3:00', 'endTime')
      ->check('recurring')
      ->select('monthly', 'repeats')
      ->select('1', 'repeatEvery')
      ->select('4', 'category1')
      ->type('dev, fake, event', 'tags')
      ->type('50 S. Stephanie', 'address1')
      ->type('Henderson', 'city')
      ->type('Nevada', 'state')
      ->type('89011', 'zipcode')
      ->attach($testImage , 'files');

    $submit = $this->press('create-event')
      ->see('Please wait')
      ->see('dev-test-event-' . $eventNumber);
  }

  /**
   * This will create an event with multiple dates, final date set
   * Options included
   *
   * @depends testAuthUser
   *
   * @return void
   */
  public function testCreateFinalRecurringDate($auth)
  {
    // Image path to be loaded in create form
    $testImage = 'assets/img/transcoding/photo/generating-660x320.png';

    // Sets randdom event number to be used to create and to 'see' on creation
    $eventNumber = rand(1, 999999);

    $this->actingAs($auth)
         ->visit('events/create?tab=freemium')
         ->type('Dev Test event # ' . $eventNumber, 'title')
         ->type('Dev test description', 'description')
         ->select('6', 'timeZoneId')
         ->type('12/15/2016', 'startDate')
         ->type('12/15/2016', 'endDate')
         ->select('1:00', 'startTime')
         ->select('3:00', 'endTime')
         ->check('recurring')
         ->select('monthly', 'repeats')
         ->select('1', 'repeatEvery')
         ->type('12/16/2017', 'endFinalDate')
         ->select('4', 'category1')
         ->type('dev, fake, event', 'tags')
         ->type('50 S. Stephanie', 'address1')
         ->type('Henderson', 'city')
         ->type('Nevada', 'state')
         ->type('89011', 'zipcode')
         ->select('1', 'meta[comments][enabled]')
         ->select('1', 'meta[rsvp][enabled]')
         ->attach($testImage , 'files')
         ->press('create-event')
         ->see('Please wait')
         ->see('dev-test-event-' . $eventNumber);
  }

  /**
   * This will test if user does not enter api required information, redirects back
   *
   * @depends testAuthUser
   *
   * @return void
   */
  public function testCreateEventMissingItems($auth)
  {
    $this->actingAs($auth)
         ->visit('events/create?tab=freemium')
         ->press('create-event')
         ->see('The title field is required. The address1 field is required. The zipcode field is required.')
         ->seePageIs('events/create?tab=summary');
  }

}
