<?php namespace App\Http\Controllers;

class SubscriptionsController extends Controller {

	/*
	|--------------------------------------------------------------------------
	| Subscriptions Controller
	|--------------------------------------------------------------------------
	|
	| This controller handles creating new subsciptions.
	|
	*/

	/**
	 * Create a new controller instance.
	 *
	 * @return void
	 */
	public function __construct()
	{

	}

	/**
	 * Show the application welcome screen to the user.
	 *
	 * @return Response
	 */
	public function create()
	{
		// Test DB connection
		$database = \DB::connection()->getDatabaseName();

		return view('subscriptions/create', compact(['database']));
	}

}
