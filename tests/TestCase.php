<?php

class TestCase extends Illuminate\Foundation\Testing\TestCase {

	/**
	 * Creates the application.
	 *
	 * @return \Illuminate\Foundation\Application
	 */
	public function createApplication()
	{
		$app = require __DIR__.'/../bootstrap/app.php';

		$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

		return $app;
	}

	public function setUp()
	{
		parent::setUp();

		// Don't send mail in Testing
		Mail::pretend(true);
		// Migrate Database
		Artisan::call('migrate:refresh');
	}

	public function tearDown()
	{
		parent::tearDown();

		// Assert all mocks were true
		Mockery::Close();
	}

}
