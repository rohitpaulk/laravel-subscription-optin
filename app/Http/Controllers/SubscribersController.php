<?php namespace App\Http\Controllers;

use App\Subscriber;
use App\Http\Requests\CreateSubscriberRequest;
use Mail;
use Request;
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
		return view('subscribers/create');
	}

	/**
	 * Handle the POST response to the form
	 *
	 * @return Response
	 */
	public function store(CreateSubscriberRequest $request)
	{
		$input = $request->all();
		$input['email'] = strtolower($input['email']);

		// Check whether subscriber exists
		if (Subscriber::where('email', $input['email'])->count() > 0) {
			// Fetch the subscriber from the database
			$subscriber = Subscriber::where('email', $input['email'])->firstOrFail();

			// If the subscriber isn't verified, resend verification email with new nonce
			if (!$subscriber->verified)  {
				$subscriber->nonce = str_random(32);
				$subscriber->save();

				$this->sendVerificationEmail($subscriber);
			}
			else {
				// If they are already subscribe, send an 'already-verified' email
				$this->sendAlreadyVerifiedEmail($subscriber);
			}
		} else {
			// Create a Subscriber
			$subscriber = new Subscriber($input);
			$subscriber->nonce = str_random(32);
			$subscriber->verified = False;
			$subscriber->save();

			// Send a verification email
			$this->sendVerificationEmail($subscriber);
		}

		return view('subscribers/sent');
	}

	public function verify()
	{
		$input = Request::all();

		// To be overridden if verification succeeds
		$success_message ="We could not verify your email.";

		// Don't proceed further if proper params aren't provided
		if (!isset($input['email']) || !isset($input['nonce'])) {
			return view('subscribers/verify', compact(['success_message']));
		}

		$input['email'] = strtolower($input['email']);

		if (Subscriber::where('email', $input['email'])->count() > 0) {
			$subscriber = Subscriber::where('email', $input['email'])->firstOrFail();

			if ($input['nonce'] == $subscriber->nonce) {
				$subscriber->verified = true;
				$subscriber->save();
				$success_message ="Your email has been verified!";
			}
		}

		return view('subscribers/verify', compact(['success_message']));
	}

	/**
	 * Send a verification email
	 *
	 * @param Subscriber
	 * @return void
	 */
	protected function sendVerificationEmail($subscriber)
	{
		$data = [
			'full_name' => $subscriber->full_name(),
			'verification_link' => $subscriber->verification_link(Request::root())
		];
		Mail::queue('emails.verification', $data, function($message) use ($subscriber)
		{
			$message->to($subscriber->email, $subscriber->full_name())->subject('Verify your subscription to Awesome Newsletter');
		});
	}

	/**
	 * Send an email saying that a user is already verified.
	 *
	 * @param Subscriber
	 * @return void
	 */
	protected function sendAlreadyVerifiedEmail($subscriber)
	{
		$data = [
			'full_name' => $subscriber->full_name(),
		];
		Mail::queue('emails.already_verified', $data, function($message) use ($subscriber)
		{
			$message->to($subscriber->email, $subscriber->full_name())->subject('You are already subscribed to Awesome Newsletter');
		});
	}

}
