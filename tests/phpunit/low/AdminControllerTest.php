<?php

use App\Auth\ApiGenericUser;

class AdminControllerTest extends TestCase {

  /**
   * testPostAccountLogin (Logged out)
   *
   * @return void
   */
  public function testAccountLogin()
  {
    // Instanciate apihelper
    $api = new \App\Helpers\ApiHelper;

    // Grab a user from
    $user = $api->show('users', '1');

    // Set usersname
    $user = $user['data']['user'];

    $result = $this->visit('account/login')
      ->type($user['email'], 'email')
      ->type('timberlake', 'password')
      ->press('Sign in')
      ->see($user['email'])
      ->see('Search Events Near You')
      ->seePageIs('/');
  }

  /**
   * Get users profile
   *
   * @return $user
   */
  public function testShowProfile()
  {
    // Instanciate apihelper
    $api = new \App\Helpers\ApiHelper;

    // Grab a user from
    $user = $api->show('users', '1');

    // Set usersname
    $username = $user['data']['user']['username'];

    // Grab selected user via website
    $result = $this->visit('users/' . $username)
      ->see($user['data']['user']['id'])
      ->see($user['data']['user']['timeZoneId'])
      ->see($user['data']['user']['firstName'])
      ->see($user['data']['user']['lastName']);

    return $user;
  }

  /**
   * Auth User
   *
   * @depends testShowProfile
   * @return collection model collection data from api
   */
  public function testAuth($user)
  {
    $auth = new ApiGenericUser(['id' => $user['data']['user']['id']]);

    return $auth;
  }

  /**
   * Test Admin Controller
   * @return null
   */
  public function testAdminImpersonateOut()
  {
    $result = $this->visit('admin/impersonate/4')
      ->seePageIs('/account/register');
  }

  /**
   * Test Admin Controller
   *
   * @depends testAuth
   * @return null
   */
  public function testAdminImpersonateIn($auth)
  {
    $result = $this->actingAs($auth)
      ->visit('/admin/impersonate/4')
      ->seePageIs('/');
  }
}
