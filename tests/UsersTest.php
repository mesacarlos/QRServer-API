<?php

use Laravel\Lumen\Testing\DatabaseMigrations;
use Laravel\Lumen\Testing\DatabaseTransactions;

class UsersTest extends TestCase
{
	use DatabaseTransactions;

    public function testGetUserData()
    {
		$apikey = $this->post('/api/v1/login', ['email' => 'test-valid@mesacarlos.es', 'password' => 'asd'])->response->getOriginalContent()['api_token'];

		$this->get('/api/v1/user/me', ['apitoken' => $apikey])
			->seeStatusCode(200);
    }

	public function testUpdateUserData()
	{
		$apikey = $this->post('/api/v1/login', ['email' => 'test-valid@mesacarlos.es', 'password' => 'asd'])->response->getOriginalContent()['api_token'];

		$this->put('/api/v1/user/me', ['username' => 'TestingValido-temp'], ['apitoken' => $apikey])
			->seeStatusCode(200);

		$this->put('/api/v1/user/me', ['username' => 'TestingValido'], ['apitoken' => $apikey])
			->seeStatusCode(200);
	}

	public function testDeleteUser()
	{
		$apikey = $this->post('/api/v1/login', ['email' => 'test-valid@mesacarlos.es', 'password' => 'asd'])->response->getOriginalContent()['api_token'];

		$this->delete('/api/v1/user/me', ['password' => 'invalid-pwd'], ['apitoken' => $apikey])
			->seeStatusCode(403);

		$this->delete('/api/v1/user/me', ['password' => 'asd'], ['apitoken' => $apikey])
			->seeStatusCode(200);
	}
}
