<?php namespace App\Http\Controllers;

use Request;

class SubscribersController extends Controller {

	/*
	|--------------------------------------------------------------------------
	| Subscribers Controller
	|--------------------------------------------------------------------------
	|
	| This controller handles creating new subscibers.
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
	 * Show the subscriber form to the user.
	 *
	 * @return Response
	 */
	public function create()
	{
		// Test DB connection
		$database = \DB::connection()->getDatabaseName();

		return view('subscribers/create', compact(['database']));
	}

	/**
	 * Handle the POST response to the form
	 *
	 * @return Response
	 */
	public function store()
	{
		$input = Request::all();
		dd($input);
		return view('subscribers/sent');
	}

}
