<?php namespace App\Auth;

use Illuminate\Auth\GenericUser;

class ApiGenericUser extends GenericUser {

  /**
   * Dynamically access the user's attributes.
   *
   * @param  string  $key
   * @return mixed
   */
  public function __get($key)
  {
    return isset($this->attributes[$key]) ? $this->attributes[$key] : null;
  }

}
