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
		Mail::shouldReceive('queue')->once();

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
		Mail::shouldReceive('queue')->never();

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
		$subscriber = $this->createSubscriber(['verified' => True]);
		// Should receive an email
		Mail::shouldReceive('queue')->once();

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

		$this->assertContains('Check your email', $response->getContent());
	}

	public function testSubscribersStoreWithExistingButUnverifiedEmail()
	{
		$subscriber = $this->createSubscriber();
		$oldnonce = $subscriber->nonce;

		// Should resend the email
		Mail::shouldReceive('queue')->once();

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

		$this->assertContains('Check your email', $response->getContent());
	}

	public function testVerifyWithValidParams()
	{
		$subscriber = $this->createSubscriber();

		// Make sure that subscriber is unverified before request
		$this->AssertEquals(False, $subscriber->verified);

		$data = [
			"email" => $subscriber->email,
			"nonce" => $subscriber->nonce
		];

		$response = $this->action('GET', 'SubscribersController@verify', $data);

		// Test request succeeds
		$this->assertEquals(200, $response->getStatusCode());

		// Test that verified is set to True
		$this->assertEquals(True, $subscriber->fresh()->verified);

		$this->assertContains('verified', $response->getContent());
	}

	public function testVerifyWithWrongNonce()
	{
		$subscriber = $this->createSubscriber();

		// Make sure that subscriber is unverified before request
		$this->AssertEquals(False, $subscriber->verified);

		$data = [
			"email" => $subscriber->email,
			"nonce" => "abcd"
		];

		$response = $this->action('GET', 'SubscribersController@verify', $data);

		// Test request succeeds
		$this->assertEquals(200, $response->getStatusCode());

		// Test that verified is set to True
		$this->assertEquals(False, $subscriber->fresh()->verified);

		$this->assertContains('could not verify', $response->getContent());
	}

	public function testVerifyWithInvalidParams()
	{
		$subscriber = $this->createSubscriber();

		// Make sure that subscriber is unverified before request
		$this->AssertEquals(False, $subscriber->verified);

		$data = [
			"email" => $subscriber->email
		];

		$response = $this->action('GET', 'SubscribersController@verify', $data);

		// Test request succeeds
		$this->assertEquals(200, $response->getStatusCode());
		$this->assertContains('could not verify', $response->getContent());
	}
}
