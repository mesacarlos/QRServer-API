<?php

use Laravel\Lumen\Testing\DatabaseMigrations;
use Laravel\Lumen\Testing\DatabaseTransactions;

class LoginTest extends TestCase
{
	use DatabaseTransactions;

    /**
     * Test login with valid data
     *
     * @return void
     */
    public function testLoginOk()
    {
        $this->post('/api/v1/login', ['email' => 'test-valid@mesacarlos.es', 'password' => 'asd'])
			->seeStatusCode(200);
    }

	/**
	 * Test login to an unverified account
	 *
	 * @return void
	 */
	public function testLoginUnverified()
	{
		$this->post('/api/v1/login', ['email' => 'testing-invalid@mesacarlos.es', 'password' => 'asd'])
			->seeStatusCode(401);
	}

	/**
	 * Test login with invalid data
	 *
	 * @return void
	 */
	public function testLoginInvalidData()
	{
		$this->post('/api/v1/login', ['email' => 'carlos@mesacarlos.es', 'password' => 'invalidPwd'])
			->seeStatusCode(403);
	}

	/**
	 * Test login with incomplete data
	 *
	 * @return void
	 */
	public function testLoginIncompleteData()
	{
		$this->post('/api/v1/login', ['email' => 'carlos@mesacarlos.es'])
			->seeStatusCode(422);
	}

	/**
	 * Test login with incomplete data
	 *
	 * @return void
	 */
	public function testLogout()
	{
		$apikey = $this->post('/api/v1/login', ['email' => 'test-valid@mesacarlos.es', 'password' => 'asd'])->response->getOriginalContent()['api_token'];

		$this->delete('/api/v1/logout', [], ['apitoken' => $apikey])
			->seeStatusCode(200);
	}
}
