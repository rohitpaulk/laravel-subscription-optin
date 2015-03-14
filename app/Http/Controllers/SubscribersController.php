<?php namespace App\Http\Controllers;

use App\Subscriber;
use App\Http\Requests\CreateSubscriberRequest;
use Mail;

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
	public function store(CreateSubscriberRequest $request)
	{
		$input = $request->all();
		$email = $input['email'];
		$subscriber = new Subscriber;
		$subscriber->first_name = $input['first_name'];
		$subscriber->last_name = $input['last_name'];
		$subscriber->email = $input['email'];
		$subscriber->nonce = str_random(32);
		$subscriber->verified = False;
		$subscriber->save();
		Mail::send('emails.verification', ['email' => $email], function($message) use ($subscriber)
		{
		    $message->to($subscriber->email, $subscriber->first_name . " " . $subscriber->last_name)->subject('Verify your subscription');
		});
		return view('subscribers/sent', compact(['email']));
	}

}
