<?php

use App\Helpers\ApiHelper;
use App\Auth\ApiGenericUser;

class EventScheduleFormTest extends TestCase {

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
    $user = $api->show('users', '1?with[]=events');

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
   * This will grab a single event to edit schedules
   *
   * @depends testGetEvents
   */
  public function testGrabSchedule($events)
  {
    $event = $events[0];

    // Instanciate apihelper
    $api = new \App\Helpers\ApiHelper;

    $event = $api->show('events', $event['id'] . '?with[]=schedules');

    $schedule = $event['data']['event']['schedules'][0];

    return $schedule;
  }

  /**
   * This will add a new schedule to event with a single date
   *
   * @depends testUser
   * @depends testGetEvents
   * @return void
   */
  public function testAddScheduleSingle($auth, $events)
  {
    $event = $events[0];

    $this->actingAs($auth)
         ->visit('events/' . $event['id'] . '/schedules')
         ->type('01/15/2016', 'startDate')
         ->type('01/15/2016', 'endDate')
         ->select('2:00', 'startTime')
         ->select('pm', 'startMeridian')
         ->select('4:00', 'endTime')
         ->select('pm', 'endMeridian')
         ->press('Submit')
         ->see('Please wait while your schedule is being saved');
  }

  /**
   * This will add a new schedule to event with a recurring and final date
   *
   * @depends testUser
   * @depends testGetEvents
   * @return void
   */
  public function testAddScheduleRecurringFinal($auth, $events)
  {
    $event = $events[0];

    $this->actingAs($auth)
         ->visit('events/' . $event['id'] . '/schedules')
         ->type('01/15/2016', 'startDate')
         ->type('01/15/2016', 'endDate')
         ->select('2:00', 'startTime')
         ->select('pm', 'startMeridian')
         ->select('4:00', 'endTime')
         ->select('pm', 'endMeridian')
         ->check('recurring')
         ->check('finalDateSet')
         ->select('monthly', 'repeats')
         ->select('1', 'repeatEvery')
         ->type('12/12/2018', 'endFinalDate')
         ->select('4:00', 'endFinalTime')
         ->select('pm', 'endFinalMeridian')
         ->press('Submit')
         ->see('Please wait while your schedule is being saved');
  }

  /**
   * This will add a new schedule to event with a recurring not final date
   *
   * @depends testUser
   * @depends testGetEvents
   * @return void
   */
  public function testAddScheduleRecurring($auth, $events)
  {
    $event = $events[0];

    $this->actingAs($auth)
         ->visit('events/' . $event['id'] . '/schedules')
         ->type('01/15/2016', 'startDate')
         ->type('01/15/2016', 'endDate')
         ->select('2:00', 'startTime')
         ->select('pm', 'startMeridian')
         ->select('4:00', 'endTime')
         ->select('pm', 'endMeridian')
         ->check('recurring')
         ->select('monthly', 'repeats')
         ->select('1', 'repeatEvery')
         ->press('Submit')
         ->see('Please wait while your schedule is being saved');
  }

  /**
   * This will edit an existing schedule with recurring date no final
   * Id is set because original record is deleted upon updating, will prevent errors
   *
   * @depends testUser
   * @depends testGrabSchedule
   * @depends testGetEvents
   * @return void
   */
  public function testEditScheduleRecurring($auth, $schedule, $events)
  {
    $event = $events[0];

    $this->actingAs($auth)
         ->visit('events/' . $event['id'] . '/schedules/' . $schedule['id'])
         ->type('01/15/2017', 'startDate')
         ->type('01/15/2017', 'endDate')
         ->select('2:00', 'startTime')
         ->select('pm', 'startMeridian')
         ->select('4:00', 'endTime')
         ->select('pm', 'endMeridian')
         ->check('recurring')
         ->select('monthly', 'repeats')
         ->select('1', 'repeatEvery')
         ->press('Update')
         ->see('Please wait while your times are being updated');
  }

  /**
   * This will edit an existing schedule with recurring date with final
   * Id is set because original record is deleted upon updating, will prevent errors
   *
   * @depends testUser
   * @depends testGetEvents
   * @depends testGrabSchedule
   * @depends testEditScheduleRecurring
   * @return void
   */
  public function testEditScheduleRecurringFinal($auth, $events, $schedule)
  {
    $event = $events[0];

    $this->actingAs($auth)
         ->visit('events/' . $event['id'] . '/schedules/' . $schedule['id'])
         ->type('01/15/2016', 'startDate')
         ->type('01/15/2016', 'endDate')
         ->select('2:00', 'startTime')
         ->select('pm', 'startMeridian')
         ->select('4:00', 'endTime')
         ->select('pm', 'endMeridian')
         ->check('recurring')
         ->check('finalDateSet')
         ->select('monthly', 'repeats')
         ->select('1', 'repeatEvery')
         ->type('12/12/2018', 'endFinalDate')
         ->select('4:00', 'endFinalTime')
         ->select('pm', 'endFinalMeridian')
         ->press('Update')
         ->see('Please wait while your times are being updated');
  }

  /**
   * This will edit an existing schedule with a single date
   * Id is set because original record is deleted upon updating, will prevent errors
   *
   * @depends testUser
   * @depends testGetEvents
   * @depends testGrabSchedule
   * @depends testEditScheduleRecurringFinal
   *
   * @return void
   */
  public function testEditScheduleSingle($auth, $events, $schedule)
  {
    $event = $events[0];

    $this->actingAs($auth)
         ->visit('events/' . $event['id'] . '/schedules/' . $schedule['id'])
         ->type('01/15/2016', 'startDate')
         ->type('01/15/2016', 'endDate')
         ->select('2:00', 'startTime')
         ->select('pm', 'startMeridian')
         ->select('4:00', 'endTime')
         ->select('pm', 'endMeridian')
         ->press('update')
         ->see('Please wait while your times are being updated');
  }

}
