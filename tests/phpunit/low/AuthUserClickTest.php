<?php

use App\Helpers\ApiHelper;
use App\Auth\ApiGenericUser;

class AuthUserClickTest extends TestCase {

 /**
   * testPostAccountLogin (Logged out)
   *
   * @return $user
   */
  public function testAccountLogin()
  {
    // Instanciate apihelper
    $api = new \App\Helpers\ApiHelper;

    // Grab a user from
    $user = $api->index('users');

    // Set usersname
    $user = $user['data']['users'][3];

    return $user;
  }

 /**
   * Will take user to sign up page from home (Logged out)
   *
   *
   */
  public function testUserSignUp()
  {
    $this->visit('/')
         ->click('Sign Up')
         ->seePageIs('account/register');

  }

 /**
   * Will take user to sign in page from home (Logged out)
   *
   *
   */
  public function testUserSignIn()
  {
    $this->visit('/')
         ->click('Sign In')
         ->seePageIs('account/login');

  }

 /**
   * Will take user to sign in page from register (Logged out)
   *
   *
   */
  public function testUserSignInFromRegister()
  {
    $this->visit('account/register')
         ->click('Sign In')
         ->seePageIs('account/login');

  }

 /**
   * Will take user to forgot password page from login (Logged out)
   *
   *
   */
  public function testUserForgotPassword()
  {
    $this->visit('account/login')
         ->click('Forgot Password')
         ->seePageIs('account/password/forgot');

  }

 /**
   * testPostAccountLogin (Logged out)
   *
   * @depends testAccountLogin
   *
   */
  public function testAuthUser($user)
  {
    // Instanciate apihelper
    $api = new \App\Helpers\ApiHelper;

    $auth = new ApiGenericUser(['id' => $user['id']]);

    return $auth;

  }

  /**
   * This will send user to home page
   *
   * @depends testAuthUser
   * @return void
   */
  public function testAccountSuarayLink()
  {
    $this->visit('account/profile')
         ->click('Suaray')
         ->seePageIs('/');
  }

  /**
   * This will send user to browse page
   *
   * @depends testAuthUser
   * @return void
   */
  public function testAccountBrowseLink()
  {
    $this->visit('/sellTickets')
         ->click('Browse')
         ->seePageIs('/');
  }

  /**
   * This will send  logged out user to register page
   *
   * @return void
   */
  public function testCreateAnEventLink()
  {
    $this->visit('/sellTickets')
         ->click('Create An Event')
         ->seePageIs('account/register');
  }

  /**
   * This will send user to profile
   *
   * @depends testAuthUser
   * @return void
   */
  public function testAccountProfileLink($auth)
  {
    $this->actingAs($auth)
         ->visit('/')
         ->click('Profile')
         ->seePageIs('account/profile');
  }

  /**
   * This will send user to friends page
   *
   * @depends testAuthUser
   * @return void
   */
  public function testAccountFriendsLink($auth)
  {
    $this->actingAs($auth)
         ->visit('/')
         ->click('Friends')
         ->seePageIs('account/friends');
  }

  /**
   * This will send user to settings page
   *
   * @depends testAuthUser
   * @return void
   */
  public function testAccountSettingsLink($auth)
  {
    $this->actingAs($auth)
         ->visit('/')
         ->click('Settings')
         ->seePageIs('account/settings');
  }

  /**
   * This will send user to friends page
   *
   * @depends testAuthUser
   * @return void
   */
  public function testAccountMyEventsLink($auth)
  {
    $this->actingAs($auth)
         ->visit('/')
         ->click('My Events')
         ->seePageIs('account/dashboard');
  }

  /**
   * This will send user to check in page
   *
   * @depends testAuthUser
   * @return void
   */
  public function testAccountCheckInLink($auth)
  {
    $this->actingAs($auth)
         ->visit('/')
         ->click('Check In')
         ->seePageIs('account/event/check-in');
  }

  /**
   * This will send user to my tickets
   *
   * @depends testAuthUser
   * @return void
   */
  public function testAccountMyTicketsLink($auth)
  {
    $this->actingAs($auth)
         ->visit('/')
         ->click('My Tickets')
         ->seePageIs('account/my-tickets');
  }

  /**
   * This will send user to payment blade
   *
   * @depends testAuthUser
   * @return void
   */
  public function testAccountPaymentLink($auth)
  {
    // Need to set remote address, otherwise test will fail with undefined index
    $_SERVER['REMOTE_ADDR'] = '192.168.44.1';

    $this->actingAs($auth)
         ->visit('/')
         ->click('Payment')
         ->seePageIs('account/payment');
  }

  /**
   * This will send user to create event page
   *
   * @depends testAuthUser
   * @return void
   */
  public function testAccountCreateEventLink($auth)
  {
    $this->actingAs($auth)
         ->visit('/')
         ->click('Create Event')
         ->seePageIs('events/create?tab=details');
  }

  /**
   * This will send user to categories page
   *
   * @depends testAuthUser
   * @return void
   */
  public function testAccountCategoriesLink($auth)
  {
    $this->actingAs($auth)
         ->visit('/')
         ->click('Categories')
         ->seePageIs('events/categories');
  }

  /**
   * This will send user to home page
   *
   * @depends testAuthUser
   * @return void
   */
  public function testAccountHomeLink($auth)
  {

    $this->actingAs($auth)
         ->visit('account/payment')
         ->click('Home')
         ->seePageIs('/');
  }

  /**
   * This will sign user out
   *
   * @depends testAuthUser
   * @return void
   */
    public function testSignOut($auth)
    {
      $this->actingAs($auth)
           ->visit('/')
           ->click('Sign Out')
           ->see('welcome to suaray');
    }

}
