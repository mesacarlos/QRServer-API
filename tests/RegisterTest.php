<?php

use Laravel\Lumen\Testing\DatabaseMigrations;
use Laravel\Lumen\Testing\DatabaseTransactions;

class RegisterTest extends TestCase
{
	use DatabaseTransactions;

    public function testRegistroOk()
    {
		$this->post('/api/v1/register', ['username' => 'PHPUnit001', 'email' => 'qr-PHPUnit-001@mesacarlos.es', 'password' => 'asd'])
			->seeStatusCode(200);
    }

	public function testRegistroNoOk()
	{
		$this->post('/api/v1/register', ['username' => 'PHPUnit002', 'email' => 'qr-PHPUnit-002@mesacarlos.es'])
			->seeStatusCode(422);
	}
}
