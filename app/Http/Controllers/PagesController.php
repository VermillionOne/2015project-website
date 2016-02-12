<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Config;

use Illuminate\Http\Request;

class PagesController extends Controller {

  /**
	 * Display the home page and pass the events variable along.
	 *
	 * @return Response
	 */
  public function selltickets()
  {
    Config::set('global.pageLoaderDisplay', true);

    // Instantiate the APIHelper
    $api = new \App\Helpers\ApiHelper;

    $featuredUrl = 'events/featured/carousel?filter[and][][times.end]=date&with[]=times';

    $featuredTagsUrl = 'events/featured/tags';

    $tagsIsFeaturedUrl = 'events/featured/tags/all?filter[and][][times.end]=date&fields[]=isSponsored&fields[]=slug&fields[]=city&fields[]=state&fields[]=location&fields[]=title&fields[]=featuredPhoto';

    if (\Auth::check()) {

      $featuredUrl .= '&auth_user_id=' . \Auth::user()->id;
      $featuredTagsUrl .= '?auth_user_id=' . \Auth::user()->id;
      $tagsIsFeaturedUrl .= '&auth_user_id=' . \Auth::user()->id;

    }


    // Get events that have is_featured
    $eventsIsFeatured = $api->index($featuredUrl);
    $eventsIsFeatured = isset($eventsIsFeatured['data']['events']) ? $eventsIsFeatured['data']['events'] : [];

    $eventsFeaturedTags = $api->index($featuredTagsUrl);

    // Get tags that have is_featured and postheir perspective events
    $eventsTagsIsFeatured = $api->index($tagsIsFeaturedUrl);
    $eventsTagsIsFeatured = isset($eventsTagsIsFeatured['data']['all']) ? $eventsTagsIsFeatured['data']['all'] : [];

    // State abbreviations to be used in carousel location label
    $stateAbbreviations = [
      'Alabama' => 'AL', 'Alaska' => 'AK', 'Arizona' => 'AZ', 'Arkansas' => 'AR', 'California' => 'CA', 'Colorado' => 'CO',
      'Connecticut' => 'CT', 'Delaware' => 'DE', 'Florida' => 'FL', 'Georgia' => 'GA', 'Hawaii' => 'HI', 'Idaho' => 'ID',
      'Illinois' => 'IL', 'Indiana' => 'IN', 'Iowa' => 'IA', 'Kansas' => 'KS', 'Kentucky' => 'KY', 'Louisiana' => 'LA',
      'Maine' => 'ME', 'Maryland' => 'MD', 'Massachusetts' => 'MA', 'Michigan' => 'MI', 'Minnesota' => 'MN', 'Mississippi' => 'MS',
      'Missouri' => 'MO', 'Montana' => 'MT', 'Nebraska' => 'NE', 'Nevada' => 'NV', 'New Hampshire' => 'NH', 'New Jersey' => 'NJ',
      'New Mexico' => 'NM', 'New York' => 'NY', 'North Carolina' => 'NC', 'North Dakota' => 'ND', 'Ohio' => 'OH', 'Oklahoma' => 'OK',
      'Oregon' => 'OR', 'Pennsylvania' => 'PA', 'Rhode Island' => 'RI', 'South Carolina' => 'SC', 'South Dakota' => 'SD',
      'Tennessee' => 'TN', 'Texas' => 'TX', 'Utah' => 'UT', 'Vermont' => 'VT', 'Virginia' => 'VA', 'Washington' => 'WA',
      'West Virginia' => 'WV', 'Wisconsin' => 'WI', 'Wyoming' => 'WY'
    ];

    return \View::make('pages.sell-tickets', array(
      'eventsIsFeatured' => $eventsIsFeatured,
      'eventsTagsIsFeatured' => $eventsTagsIsFeatured,
      'stateAbbreviations' => $stateAbbreviations,
      'metaTitle' => 'SUARAY - Discover Fun Events',
    ));
  }

	public function home()
	{
    Config::set('global.pageLoaderDisplay', true);

		// Instantiate the APIHelper
	  $api = new \App\Helpers\ApiHelper;

    $featuredUrl = 'events/featured/carousel?filter[and][][times.end]=date&with[]=times&with[]=ticketsInventory';

    $featuredTagsUrl = 'events/featured/tags';

    $tagsIsFeaturedUrl = 'events/featured/tags/all?filter[and][][times.end]=date&filter[and][][is_published]=1&with[]=times&fields[]=isSponsored&fields[]=slug&fields[]=title&fields[]=featuredPhoto&fields[]=city&fields[]=state&fields[]=description&fields[]=times';

    if (\Auth::check()) {

      $featuredUrl .= '&auth_user_id=' . \Auth::user()->id;
      $featuredTagsUrl .= '?auth_user_id=' . \Auth::user()->id;
      $tagsIsFeaturedUrl .= '&auth_user_id=' . \Auth::user()->id;

    }

    // Get events that have is_featured
    $eventsIsFeatured = $api->index($featuredUrl);
    $eventsIsFeatured = isset($eventsIsFeatured['data']['events']) ? $eventsIsFeatured['data']['events'] : [];

    $eventsFeaturedTags = $api->index($featuredTagsUrl);

	  // Get tags that have is_featured and postheir perspective events
	  $eventsTagsIsFeatured = $api->index($tagsIsFeaturedUrl);
    $eventsTagsIsFeatured = isset($eventsTagsIsFeatured['data']['all']) ? $eventsTagsIsFeatured['data']['all'] : [];

    // State abbreviations to be used in carousel location label
    $stateAbbreviations = [
      'Alabama' => 'AL', 'Alaska' => 'AK', 'Arizona' => 'AZ', 'Arkansas' => 'AR', 'California' => 'CA', 'Colorado' => 'CO',
      'Connecticut' => 'CT', 'Delaware' => 'DE', 'Florida' => 'FL', 'Georgia' => 'GA', 'Hawaii' => 'HI', 'Idaho' => 'ID',
      'Illinois' => 'IL', 'Indiana' => 'IN', 'Iowa' => 'IA', 'Kansas' => 'KS', 'Kentucky' => 'KY', 'Louisiana' => 'LA',
      'Maine' => 'ME', 'Maryland' => 'MD', 'Massachusetts' => 'MA', 'Michigan' => 'MI', 'Minnesota' => 'MN', 'Mississippi' => 'MS',
      'Missouri' => 'MO', 'Montana' => 'MT', 'Nebraska' => 'NE', 'Nevada' => 'NV', 'New Hampshire' => 'NH', 'New Jersey' => 'NJ',
      'New Mexico' => 'NM', 'New York' => 'NY', 'North Carolina' => 'NC', 'North Dakota' => 'ND', 'Ohio' => 'OH', 'Oklahoma' => 'OK',
      'Oregon' => 'OR', 'Pennsylvania' => 'PA', 'Rhode Island' => 'RI', 'South Carolina' => 'SC', 'South Dakota' => 'SD',
      'Tennessee' => 'TN', 'Texas' => 'TX', 'Utah' => 'UT', 'Vermont' => 'VT', 'Virginia' => 'VA', 'Washington' => 'WA',
      'West Virginia' => 'WV', 'Wisconsin' => 'WI', 'Wyoming' => 'WY'
    ];

		return \View::make('pages.home', array(
      'eventsIsFeatured' => $eventsIsFeatured,
      'eventsTagsIsFeatured' => $eventsTagsIsFeatured,
			'stateAbbreviations' => $stateAbbreviations,
      'metaTitle' => 'SUARAY - Discover Fun Events',
		));
	}

  public function mobile()
  {
    return \View::make('pages.mobile');
  }

  public function terms()
  {
    return \View::make('pages.terms');
  }

  public function privacy()
  {
    return \View::make('pages.privacy');
  }

  public function robots()
  {
    $response = \Response::make(\View::make('pages.robots', [
      'isProduction' => env('APP_ENV') == 'production' ? true : false,
    ]));

    $response->header('Content-Type', 'text/plain; charset=UTF-8');

    return $response;
  }
  public function loader()
  {

    $data = \Request::all();

    return \View::make('pages.loader')->with([
      'data' => $data
      ]);
  }
}
