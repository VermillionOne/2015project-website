<?php namespace App\Helpers;

use Request;

class ViewHelper {

  /**
   * Limits a paragraph by number of words specified
   *
   * @param  string  $str   paragraph to limit by words
   * @param  integer $words amount to limit paragraph to
   * @return string         string with int limitation set
   */
  public static function limitTextByWords($str, $limit = 25)
  {
    // Reads amount of words in paragraph
    $words = preg_split("/[\s]+/", $str, $limit + 1);

    // Limits paragraph to
    $words = array_slice($words, 0, $limit);
    return join(' ', $words);
  }

  /**
   * Returns whether the request is secure (through HTTPS)
   *
   * @return bool    true on HTTPS, false otherwise
   */
  public static function requestIsSecure()
  {
    // Check ELB X-Forwarded-Proto header
    return Request::server('HTTP_X_FORWARDED_PROTO') === 'https' ? true : Request::secure();
  }

  /**
   * Laravel's asset() method with $secure set to true based on user requesting HTTPS
   * if $secure is not set to something other than null
   *
   * @param  string  $path
   * @param  bool    $secure
   * @return string
   */
  public static function asset($path, $secure = null)
  {
    // Set request secure
    $isSecure = static::requestIsSecure();

    // Unless we specified we wanted secure on/off va true/false
    // set to $isSecure value
    $setHttps = $secure === null ? $isSecure : $secure;

    return asset($path, $setHttps);
  }

  /**
   * Laravel's Form::open() method with $secure set to true based on user requesting HTTPS
   *
   * @param  array   $options  form options
   * @param  bool    $secure   override secure option
   * @return string            form html tag with https or http
   */
  public static function formOpen($options, $secure = null)
  {
    // Get form
    $formOpen = \Form::open($options);

    // Set request secure
    $isSecure = static::requestIsSecure();

    // Unless we specified we wanted secure on/off va true/false
    // set to $isSecure value
    $setHttps = $secure === null ? $isSecure : $secure;

    // Replace http with https
    if ($setHttps) {
      $formOpen = str_replace('http://', 'https://', $formOpen);
    }

    // Return form
    return $formOpen;
  }

  /**
   * Laravel's Form::model() method with $secure set to true based on user requesting HTTPS
   *
   * @param  array   $model    example: $user
   * @param  array   $options  form options
   * @param  bool    $secure   override secure option
   * @return string            form html tag with https or http
   */
  public static function formModel($model, $options, $secure = null)
  {
    // Get form
    $formModel = \Form::model($model, $options);

    // Set request secure
    $isSecure = static::requestIsSecure();

    // Unless we specified we wanted secure on/off va true/false
    // set to $isSecure value
    $setHttps = $secure === null ? $isSecure : $secure;

    // Replace http with https
    if ($setHttps) {
      $formModel = str_replace('http://', 'https://', $formModel);
    }

    // Return form
    return $formModel;
  }
}
