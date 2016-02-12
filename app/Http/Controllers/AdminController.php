<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

use Auth;
use Response;
use App\Auth\ApiGenericUser;
use App\Helpers\ApiHelper;

class AdminController extends Controller {

	/**
	 * Api helper instance
	 *
	 * @var object
	 */
	public $apiHelper;

	public function __construct()
	{
		$this->apiHelper = new ApiHelper;
	}

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function impersonate($userId)
	{
		// Check that the user requesting the impersonation is an admin
		if (Auth::user()->is_admin) {

			// Get user info
			$impersonateUser = $this->apiHelper->show('users', $userId);

			// Make sure this is a valid user
			if (empty($impersonateUser['data']['user'])) {

				// Redirect back home with error
				return redirect()->route('home')->with('message', 'Invalid user_id '.$userId);
			}

			// Make sure this is not an admin (admins cannot impersonate other admins)
			if ($impersonateUser['data']['user']['isAdmin']) {

				// Redirect back home with error
				return redirect()->route('home')->with('message', 'Admins cannot impersonate other admins');
			}

			// Login as requested user_id
			Auth::login(new ApiGenericUser([
				'id' => $userId,
			]));

			// Redirect back home with success
			return redirect()->route('home')->with('success_message', 'Logged in as user_id '.$userId);
		}

		// Show error
		return redirect()->route('home')->with('message', "Sorry, access not allowed");
	}


}
