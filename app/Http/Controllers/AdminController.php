<?php namespace App\Http\Controllers;

use App\Subscriber;
use Request;

class AdminController extends Controller {

	/*
	|--------------------------------------------------------------------------
	| Admin Controller
	|--------------------------------------------------------------------------
	|
	| This controller shows the actual DB entries
	|
	*/

	/**
	 * Show the admin dashboard.
	 *
	 * @return Response
	 */
	public function dashboard()
	{
		$subscribers = Subscriber::all();
		return view('admin/dashboard', compact(['subscribers']));
	}
}
