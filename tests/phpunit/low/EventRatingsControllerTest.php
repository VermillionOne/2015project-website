<?php

class EventsRatingsControllerTest extends TestCase {
  ///////////////////////////////////////////////
  /// TESTS BELOW THIS POINT ARE NOT COMPLETE ///
  ///////////////////////////////////////////////
  /**
   * Test EventsRatings Controller
   * @return null
   */
    public function testEventsRatings()
    {

      // EventsRatingsController Test
      // NOT DEFINED BUT LISTED ON CONTROLLER
      // $response = $this->action('GET', 'EventRatingsController@index');
      // NOT DEFINED BUT LISTED ON CONTROLLER
      // $response = $this->action('GET', 'EventRatingsController@create');
      // NOT DEFINED BUT LISTED ON CONTROLLER
      // $response = $this->action('GET', 'EventRatingsController@store');
      // NOT DEFINED BUT LISTED ON CONTROLLER
      // $response = $this->action('GET', 'EventRatingsController@edit');
      $response = $this->action('GET', 'EventRatingsController@doReviews');
      // NOT DEFINED BUT LISTED ON CONTROLLER
      // $response = $this->action('GET', 'EventRatingsController@doRatings');
      // NOT DEFINED BUT LISTED ON CONTROLLER
      // $response = $this->action('GET', 'EventRatingsController@update');
      // NOT DEFINED BUT LISTED ON CONTROLLER
      // $response = $this->action('GET', 'EventRatingsController@editTables');
      $response = $this->action('GET', 'EventRatingsController@show');
      }

/////////////////////
/// ROUTES TO TEST //
/////////////////////

// |        | POST                           | events/{slug}/event-ratings                                   | events.event-ratings             | App\Http\Controllers\EventRatingsController@doReviews                    |            |
// |        | GET|HEAD                       | events/{slug}/reviews                                         | events.reviews                   | App\Http\Controllers\EventRatingsController@show                         |            |
}
