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
}
