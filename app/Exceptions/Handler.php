<?php namespace App\Exceptions;

use Exception;

# from: https://bugsnag.com/docs/notifiers/laravel
#use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Bugsnag\BugsnagLaravel\BugsnagExceptionHandler as ExceptionHandler;

class Handler extends ExceptionHandler {

	/**
	 * A list of the exception types that should not be reported.
	 *
	 * @var array
	 */
	protected $dontReport = [
		'Symfony\Component\HttpKernel\Exception\HttpException'
	];

	/**
	 * Report or log an exception.
	 *
	 * This is a great spot to send exceptions to Sentry, Bugsnag, etc.
	 *
	 * @param  \Exception  $e
	 * @return void
	 */
	public function report(Exception $e)
	{
		return parent::report($e);
	}

	/**
	 * Render an exception into an HTTP response.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  \Exception  $e
	 * @return \Illuminate\Http\Response
	 */
	public function render($request, Exception $e)
	{
		// This currenty disable the correct working of abort();
		// for example this page: http://suaray.com/events/5651/schedules
		// yields a 401, when this is uncommented, it just will show th 500 error page
		// if (app()->environment() == 'production') {
		//     return response()->view('errors.500', [], 500);
		// }

		return parent::render($request, $e);
	}

}
