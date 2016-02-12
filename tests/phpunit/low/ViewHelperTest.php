<?php

use App\Helpers\ViewHelper;

class ViewHelperTest extends TestCase {

  /**
   * ViewHelper helper class
   *
   * @var obj
   */
  protected $viewHelper;

  /**
   * This will run at the beginning of every test method
   */
  public function setUp()
  {
    // Parent setup
    parent::SetUp();

    Session::start();

    // Instantiate SocialHelper class
    $this->viewHelper = new viewHelper;
  }

  /**
   * This will run at the end of every test method
   */
  public function tearDown()
  {
    // Parent teardown
    parent::tearDown();

    // Unset ViewHelper class
    $this->viewHelper = null;
  }

  /**
   * Test testFormOpen()
   *
   * @return void
   */
  public function testAsset()
  {

    // Sets path to existing png
    $path = 'assets/img/transcoding/photo/generating-660x320.png';

    // Grabs base url
    $base = url('/');

    // Full image url after conversion
    $expectedResult = $base . '/assets/img/transcoding/photo/generating-660x320.png';

    // Converts asset path to url
    $result = ViewHelper::asset($path);

    // Checks that expected result and anctual result are equal
    $this->assertEquals($expectedResult, $result);
  }

  /**
   * Test testFormOpen()
   *
   * @return void
   */
  public function testFormOpen()
  {

    // Instantiate api helper
    $api = new \App\Helpers\ApiHelper;

    // Grab an event from
    $event = $api->index('events');
    $event = $event['data']['events'][0];

    // Set event id
    $eventId = $event['id'];

    // Set token to pass with form
    $token = Session::getToken();

    // Post schedule with data
    $result = $this->call('POST', 'events/schedules/create/' . $eventId, [
      '_token' => $token,
      'startDate' => '2018-11-11',
      'startTime' => '20:00:00',
      'endDate' => '2018-11-11',
      'endTime' => '21:00:00',
      'timeZoneId' => '6'
    ]);

    // Check Response for post to be a 302
    $this->assertResponseStatus(302);
  }

  // TODO: Route not being used
  // /**
  //  * Test testFormModel()
  //  *
  //  * @return void
  //  */
  // public function testFormModel()
  // {
  //   // Instantiate api helper
  //   $api = new \App\Helpers\ApiHelper;

  //   // Grab a user form
  //   $user = $api->show('users', '2');

  //   // Set user id
  //   $userId = $user['data']['user']['id'];

  //   // Set token to pass with form
  //   $token = Session::getToken();

  //   // Post update to auth user profile information
  //   $result = $this->call('POST', 'account/settings/update', [
  //     '_token' => $token,
  //     'id' => $userId,
  //     'firstName' => 'Bradly',
  //     'lastName' => 'Copper',
  //     'username' => 'bc123',
  //   ]);

  //   // Check Response for post to be 302
  //   $this->assertResponseStatus(302);
  // }

  /**
   * Test testLimitTextByWords()
   *
   * @return void
   */
  public function testLimitTextByWords()
  {
    // Set string
    $str = 'one two three four five six seven eight nine ten eleven twelve thirteen fourteen fifteen sixteen seventeen eighteen nineteen twenty twenty-one twenty-two twenty-three twenty-four twenty-five twenty-six twenty-seven';

    // Extected result is twenty five words
    $expectedResult = 'one two three four five six seven eight nine ten eleven twelve thirteen fourteen fifteen sixteen seventeen eighteen nineteen twenty twenty-one twenty-two twenty-three twenty-four twenty-five';

    // Sets string to twenty five words
    $result = ViewHelper::limitTextByWords($str);

    // Checks that expected and results match
    $this->assertEquals($expectedResult, $result);
  }

}
