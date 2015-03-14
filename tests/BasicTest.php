<?php

class BasicTest extends TestCase {

	public function testGetHomePage()
	{
		$response = $this->call('GET', '/');

		$this->assertEquals(200, $response->getStatusCode());
	}

	public function testGetSubscribers()
	{
		$response = $this->call('GET', '/subscribers');

		// Should return method forbidden (405)
		$this->assertEquals(405, $response->getStatusCode());
	}

	public function testSubscribersStoreWithValidParameters()
	{
		// Should send an email
		Mail::shouldReceive('send')->once();

		$data = [
			'first_name' => "Abcd",
			'last_name' => "Acda",
			'email' => "abcd@gmail.com"
		];
		$response = $this->action('POST', 'SubscribersController@store', $data);

		// Test request succeeds
		$this->assertEquals(200, $response->getStatusCode());

		// Test subscriber is created in database
		$this->assertEquals(App\Subscriber::count(), 1);

		// Test that verified is set to false
		$subscriber = App\Subscriber::first();
		$this->assertEquals($subscriber->verified, False);
	}

	public function testSubscribersStoreWithInvalidParameters()
	{
		// Should not send an email
		Mail::shouldReceive('send')->never();

		$data = [
			'first_name' => "Abcd",
			'last_name' => "Acda",
			'email' => "abcdgmail.com"
		];
		$response = $this->action('POST', 'SubscribersController@store', $data);
		$this->assertRedirectedToAction('SubscribersController@create');
		$this->assertEquals(App\Subscriber::count(), 0);
	}


	public function testSubscribersStoreWithAlreadyVerifiedEmail()
	{
		$subscriber = new App\Subscriber;
		$subscriber->first_name = "abcd";
		$subscriber->last_name = "abcd";
		$subscriber->email = "abcd@gmail.com";
		$subscriber->verified = True;
		$subscriber->nonce = str_random(32);
		$subscriber->save();

		// Should not send an email
		Mail::shouldReceive('send')->never();

		$data = [
			'first_name' => "Abcd",
			'last_name' => "Acda",
			'email' => "abcd@gmail.com"
		];
		$response = $this->action('POST', 'SubscribersController@store', $data);

		// Test request succeeds
		$this->assertEquals(200, $response->getStatusCode());

		// Test extra subscriber isn't created in database
		$this->assertEquals(App\Subscriber::count(), 1);

		// Test that verified is still True
		$subscriber = App\Subscriber::first();
		$this->assertEquals($subscriber->verified, True);

		$this->assertContains('is already verified', $response->getContent());
	}

	public function testSubscribersStoreWithExistingButUnverifiedEmail()
	{
		$subscriber = new App\Subscriber;
		$subscriber->first_name = "abcd";
		$subscriber->last_name = "abcd";
		$subscriber->email = "abcd@gmail.com";
		$subscriber->verified = False;
		$subscriber->nonce = str_random(32);
		$subscriber->save();

		$oldnonce = $subscriber->nonce;

		// Should resend the email
		Mail::shouldReceive('send')->once();

		$data = [
			'first_name' => "Abcd",
			'last_name' => "Acda",
			'email' => "abcd@gmail.com"
		];
		$response = $this->action('POST', 'SubscribersController@store', $data);

		// Test request succeeds
		$this->assertEquals(200, $response->getStatusCode());

		// Test extra subscriber isn't created in database
		$this->assertEquals(App\Subscriber::count(), 1);


		// Test nonce is regenerated
		$this->assertNotEquals(App\Subscriber::first()->nonce, $oldnonce);

		// Test that verified is still False
		$subscriber = App\Subscriber::first();
		$this->assertEquals($subscriber->verified, False);

		$this->assertContains('sent an email ', $response->getContent());
	}
}
