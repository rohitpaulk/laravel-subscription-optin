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
	| This controller handles actions related to subscibers.
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

		// Check whether subscriber exists
		if (Subscriber::where('email', $input['email'])->count() > 0) {
			// Fetch the subscriber from the database
			$subscriber = Subscriber::where('email', $input['email'])->firstOrFail();

			// If the subscriber isn't verified, resend verification email
			if (!$subscriber->verified)  {
				$subscriber->nonce = str_random(32);
				$subscriber->save();

				$success_message = $this->sendVerificationEmail($subscriber);
			}
			else {
				$success_message = "This email is already verified!";
			}
		} else {
			// Create a Subscriber
			$subscriber = new Subscriber($input);
			$subscriber->nonce = str_random(32);
			$subscriber->verified = False;
			$subscriber->save();

			$success_message = $this->sendVerificationEmail($subscriber);
		}

		return view('subscribers/sent', compact(['success_message']));
	}

	public function verify($email, $nonce)
	{

	}

	/**
	 * Send a verification email
	 *
	 * @param Subscriber
	 * @return String
	 */
	protected function sendVerificationEmail($subscriber)
	{
		Mail::send('emails.verification', ['email' => $subscriber->email], function($message) use ($subscriber)
		{
			$message->to($subscriber->email, $subscriber->full_name())->subject('Verify your subscription');
		});

		return "We've sent an email to " . $subscriber->email;
	}

}
