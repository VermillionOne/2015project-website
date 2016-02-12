<?php namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class HelperServiceProvider extends ServiceProvider {

	/**
	 * Bootstrap the application services.
	 *
	 * @return void
	 */
	public function boot()
	{
		//
	}

	/**
	 * Register the application services.
	 *
	 * @return void
	 */
	public function register()
	{
		// Helper classes to be available in views
		$helpers = [
			'ViewHelper',
		];

		// Require the classes in $helpers array
		foreach ($helpers as $helper) {

			// Require class
			require_once(app_path() . DIRECTORY_SEPARATOR . 'Helpers' . DIRECTORY_SEPARATOR . $helper . '.php');
		}
	}

}
