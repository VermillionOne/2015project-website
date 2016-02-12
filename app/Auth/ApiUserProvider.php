<?php namespace App\Auth;

use App\User;
use App\Helpers\ApiHelper;

// ApiGenericUser extends GenericUser and overrides __set() to check for isset() to check for valid index fields otherwise return null
use App\Auth\ApiGenericUser;
// use Illuminate\Auth\GenericUser;

use Illuminate\Auth\UserProviderInterface;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Contracts\Auth\UserProvider;

class ApiUserProvider implements UserProvider {

  // Api helper class
  protected $apiHelper;

  // Model to be used
  protected $model;

  // Constructor
  public function __construct(User $model)
  {
    $this->model = $model;
    $this->apiHelper = new ApiHelper;
  }

  /**
   * Retrieve a user by submitted registration data
   *
   * @param  array  $userFields    All input fields in the registration form
   * @return \Illuminate\Contracts\Auth\Authenticatable|null|array  Will return an array, if api provided errors or null , Authenticatable if user data retreival was successful
   */
  public function retrieveByRegistration(array $userFields)
  {
    // Query api
    $response = $this->apiHelper->store('users', $userFields);

    // Check if our API request succeeded
    if (isset($response['success']) && $response['success']) {

      // Set user data in snake_case for the auth model
      $user = $this->apiHelper->morphArrayKeys($response['data']['user'], 'snake');

      // Return user data
      return new ApiGenericUser($user);

    // If the API is reporting an error
    } elseif (isset($response['success']) && $response['success'] === false && isset($response['error']) && ! empty($response['error'])) {

      // Return error from api
      return $response['error'];
    }

    // Failed attempt
    return null;
  }

  /**
   * Retrieve a user by their social provider data
   *
   * @param  string  $provider   google, facebook, twitter
   * @param  array   $profile    Data array holding all fields from the provider response
   * @return \Illuminate\Contracts\Auth\Authenticatable|null
   */
  public function retrieveByProvider($provider, array $profile)
  {
    // Query api
    $response = $this->apiHelper->store('users/provider/' . $provider, $profile);
    // dd($response);

    // Check if our API request succeeded
    if (isset($response['success']) && $response['success']) {

      // Set user data in snake_case for the auth model
      $user = $this->apiHelper->morphArrayKeys($response['data']['user'], 'snake');

      // Return user data
      return new ApiGenericUser($user);
    }

    // Failed attempt
    return null;
  }

  /**
   * Retrieve a user by their unique identifier.
   *
   * @param  mixed  $identifier
   * @return \Illuminate\Contracts\Auth\Authenticatable|null
   */
  public function retrieveById($identifier)
  {
    // Query api
    $response = $this->apiHelper->show('users', $identifier);

    // Check if our API request succeeded
    if (isset($response['success']) && $response['success']) {

      // Set user data in snake_case for the auth model
      $user = $this->apiHelper->morphArrayKeys($response['data']['user'], 'snake');

      // Return user data
      return new ApiGenericUser($user);
    }

    // Failed attempt
    return null;
  }

  /**
   * Retrieve a user by the given credentials.
   *
   * @param  array  $credentials
   * @return \Illuminate\Contracts\Auth\Authenticatable|null
   */
  public function retrieveByCredentials(array $credentials)
  {
    // Query api
    $response = $this->apiHelper->store('users/verify', $credentials);

    // Check if our API request succeeded
    if (isset($response['success']) && $response['success']) {

      // Set user data in snake_case for the auth model
      $user = $this->apiHelper->morphArrayKeys($response['data']['user'], 'snake');

      // Return user data
      return new ApiGenericUser($user);
    }

    // Failed attempt
    return null;
  }

  /**
   * Validate a user against the given credentials.
   *
   * @param  \Illuminate\Contracts\Auth\Authenticatable  $user
   * @param  array  $credentials
   * @return bool
   */
  public function validateCredentials(Authenticatable $user, array $credentials)
  {
    return true;
  }

  /**
   * Retrieve a user by by their unique identifier and "remember me" token.
   *
   * @param  mixed   $identifier
   * @param  string  $token
   * @return \Illuminate\Contracts\Auth\Authenticatable|null
   */
  public function retrieveByToken($identifier, $token)
  {
    return new \Exception('not implemented');
  }

  /**
   * Update the "remember me" token for the given user in storage.
   *
   * @param  \Illuminate\Contracts\Auth\Authenticatable  $user
   * @param  string  $token
   * @return void
   */
  public function updateRememberToken(Authenticatable $user, $token)
  {
    return new \Exception('not implemented');
  }

}
