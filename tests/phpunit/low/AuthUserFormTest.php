<?php

use App\Helpers\ApiHelper;
use App\Auth\ApiGenericUser;

class AuthUserFormTest extends TestCase {

  /**
   * This will send email to change password and redirect to login page
   *
   * @return void
   */
  public function testAccountForgotPassword()
  {
    $this->visit('account/password/forgot')
         ->type('stodora@yahoo.com', 'email')
         ->press('Send Password Reset Link')
         ->see('An email has been sent')
         ->seePageIs('account/login');

  }

  /**
   * This will test initial user setup
   *
   * @return void
   */
  public function testAccountRegistration()
  {
    $this->visit('account/register')
         ->type('Jack', 'first_name')
         ->type('Flash', 'last_name')
         ->type('dev+PhpUnitTest' . rand(1, 999999) . '@suaray.com', 'email')
         ->type('timberlake', 'password')
         ->type('timberlake', 'password_confirmation')
         ->select('male', 'gender')
         ->select('30', 'birth_day')
         ->select('11', 'birth_month')
         ->select('1987', 'birth_year')
         ->press('Sign Up')
         ->see('dev+PhpUnitTest');

  }

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
    $user = $api->show('users', '4');

    // Set usersname
    $user = $user['data']['user'];

    $result = $this->visit('account/login')
      ->type($user['email'], 'email')
      ->type('timberlake', 'password')
      ->press('Sign in')
      ->see($user['email'])
      ->see('Search Events Near You')
      ->seePageIs('/');

    return $user;
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
   * This will test when a new password is too short
   *
   * @depends testAuthUser
   *
   * @return void
   */
  public function testUpdatePasswordShort($auth)
  {
    $this->actingAs($auth)
         ->visit('account/settings')
         ->type('timberlake', 'current_password')
         ->type('ee', 'password')
         ->type('ee', 'password_confirmation')
         ->see('characters');

  }

  /**
   * This will test when new password is missing
   *
   * @depends testAuthUser
   *
   * @return void
   */
  public function testUpdatePasswordMissing($auth)
  {
    $this->actingAs($auth)
         ->visit('account/settings')
         ->type('timberlake', 'current_password')
         ->press('save-password')
         ->see('required');

  }

  /**
   * This will test when current password is entered incorrectly
   *
   * @depends testAuthUser
   *
   * @return void
   */
  public function testUpdatePasswordWrong($auth)
  {
    $this->actingAs($auth)
         ->visit('account/settings')
         ->type('timbe', 'current_password')
         ->press('save-password')
         ->see('incorrect');

  }

  /**
   * This will test when new password and confirmed password do not match
   *
   * @depends testAuthUser
   *
   * @return void
   */
  public function testUpdatePasswordMatch($auth)
  {
    $this->actingAs($auth)
         ->visit('account/settings')
         ->type('timberlake', 'current_password')
         ->type('timberl', 'password')
         ->type('simple', 'password_confirmation')
         ->press('save-password')
         ->see('confirmation');

  }

  /**
   * This will test update of profile information
   *
   * @depends testAuthUser
   *
   * @return void
   *
   */
  public function testAccountProfileUpdate($auth)
  {

    $this->actingAs($auth)
         ->visit('account/profile')
         ->type('Benny', 'firstName')
         ->type('Joon', 'lastName')
         ->type('devPhpUnitTestUser', 'username')
         ->select('1', 'isPrivate')
         ->press('save')
         ->see('devPhpUnitTestUser');
  }

  /**
   * This will test properly updating password
   *
   * @depends testAuthUser
   * @depends testAccountProfileUpdate
   *
   * @return void
   */
  public function testUpdatePassword($auth)
  {
    $this->actingAs($auth)
         ->visit('account/settings')
         ->type('timberlake', 'current_password')
         ->type('timberlake', 'password')
         ->type('timberlake', 'password_confirmation')
         ->press('save-password')
         ->see('successfully');

  }

  /**
   * This will test post to activity wall
   *
   * @depends testAuthUser
   * @return void
   */
  public function testAccountWallUpdate($auth)
  {
    $this->actingAs($auth)
         ->visit('account/profile')
         ->type('Updating wall test', 'message')
         ->press('Post')
         ->see('Updating wall test');
  }

  /**
   * This will test posting account settings
   *
   * @depends testAuthUser
   * @return void
   */
  public function testAccountSettings($auth)
  {
    $this->actingAs($auth)
         ->visit('account/settings')
         ->type('Dev', 'firstName')
         ->type('Fakeuser', 'lastName')
         ->type('dev+PhpUnitTest' . rand(1, 999999) . '@suaray.com', 'email')
         ->select('female', 'gender')
         ->select('11', 'month')
         ->select('30', 'days')
         ->select('1987', 'year')
         ->press('Save Changes')
         ->see('Fakeuser');

  }



  /**
   * This will test posting security settings
   *
   * @depends testAuthUser
   * @return void
   */
  public function testAccountSecurity($auth)
  {

     $this->actingAs($auth)
          ->visit('account/settings')
          ->select('1', 'loginrequest')
          ->select('0', 'loginverify')
          ->select('1', 'personalinfo')
          ->select('1', 'findme')
          ->press('save-security')
          ->seePageIs('account/settings');
  }

  /**
   * This will test posting billing settings
   *
   * @depends testAuthUser
   * @return void
   */
  public function testAccountBilling($auth)
  {
    $this->actingAs($auth)
         ->visit('account/settings')
         ->type('Dev', 'cchfirstname')
         ->type('Fakeuser', 'cchlastname')
         ->type('4242424242424242', 'ccnumber')
         ->type('123', 'ccv')
         ->type('11', 'ccmonth')
         ->type('2017', 'ccmonth')
         ->press('save-billing')
         ->seePageIs('account/settings');
  }

  /**
   * This will test posting notification settings
   *
   * @depends testAuthUser
   * @return void
   */
  public function testAccountNotifications($auth)
  {
    $this->actingAs($auth)
         ->visit('account/settings')
         ->select('1', 'wallcomments')
         ->select('0', 'comments')
         ->select('1', 'loginnotification')
         ->select('1', 'eventtimechanges')
         ->select('0', 'cancelledevent')
         ->select('1', 'uploadedphotos')
         ->press('save-notifications')
         ->seePageIs('account/settings');

  }

  // TODO: No longer creating managed account
  /**
   * This will test payment form
   *
   * @depends testAuthUser
   * @return void
   */
  // public function testAccountPayment($auth)
  // {

  //   // Need to set remote address, otherwise test will fail with undefined index
  //   $_SERVER['REMOTE_ADDR'] = '192.168.44.1';

  //   $this->actingAs($auth)
  //        ->visit('account/payment')
  //        ->type('Dev', 'legal_entity[first_name]')
  //        ->type('Fakeuser', 'legal_entity[last_name]')
  //        ->type('11', 'legal_entity[dob][month]')
  //        ->type('30', 'legal_entity[dob][day]')
  //        ->type('1987', 'legal_entity[dob][year]')
  //        ->type('dev+PhpUnitTest' . rand(1, 999999) . '@suaray.com', 'email')
  //        ->type('50 S. Stephanie', 'legal_entity[address][line1]')
  //        ->type('Las Vegas', 'legal_entity[address][city]')
  //        ->type('Nevada', 'legal_entity[address][state]')
  //        ->type('89011', 'legal_entity[address][postal_code]')
  //        ->type('110000000', 'bank_account[routing_number]')
  //        ->type('000123456789', 'bank_account[account_number]')
  //        ->select('individual', 'legal_entity[type]')
  //        ->type('1234', 'legal_entity[ssn_last_4]')
  //        ->type('us', 'bank_account[country]')
  //        ->press('Save')
  //        ->see('6789');
  // }

}
