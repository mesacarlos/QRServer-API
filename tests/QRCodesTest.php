<?php

use Laravel\Lumen\Testing\DatabaseMigrations;
use Laravel\Lumen\Testing\DatabaseTransactions;

class QRCodesTest extends TestCase
{
	use DatabaseTransactions;

    public function testGetUserQRs()
    {
		$apikey = $this->post('/api/v1/login', ['email' => 'test-valid@mesacarlos.es', 'password' => 'asd'])->response->getOriginalContent()['api_token'];

		$this->get('/api/v1/user/me/qrcodes', ['apitoken' => $apikey])
			->seeStatusCode(200);
    }

	public function testCreateQr()
	{
		$apikey = $this->post('/api/v1/login', ['email' => 'test-valid@mesacarlos.es', 'password' => 'asd'])->response->getOriginalContent()['api_token'];

		$this->post('/api/v1/qrcode', ['id' => 'existe', 'destination_url' => 'http://ingenieriainfomatica.uniovi.es'], ['apitoken' => $apikey])
			->seeStatusCode(200);

		$this->post('/api/v1/qrcode', ['id' => 'existe','destination_url' => 'http://uniovi.es'], ['apitoken' => $apikey])
			->seeStatusCode(422);

		$this->post('/api/v1/qrcode', [], ['apitoken' => $apikey])
			->seeStatusCode(422);
	}

	public function testGetUserQrInfo()
	{
		$apikey = $this->post('/api/v1/login', ['email' => 'test-valid@mesacarlos.es', 'password' => 'asd'])->response->getOriginalContent()['api_token'];

		//Create QR code
		$this->post('/api/v1/qrcode', ['id' => 'existe', 'destination_url' => 'http://ingenieriainfomatica.uniovi.es'], ['apitoken' => $apikey])->seeStatusCode(200);


		$this->get('/api/v1/qrcode/existe', ['apitoken' => $apikey])
			->seeStatusCode(200);

		$this->get('/api/v1/qrcode/noexiste', ['apitoken' => $apikey])
			->seeStatusCode(404);
	}

	public function testCustomizeQr()
	{
		$apikey = $this->post('/api/v1/login', ['email' => 'test-valid@mesacarlos.es', 'password' => 'asd'])->response->getOriginalContent()['api_token'];

		//Create QR code
		$this->post('/api/v1/qrcode', ['id' => 'existe', 'destination_url' => 'http://ingenieriainfomatica.uniovi.es'], ['apitoken' => $apikey])->seeStatusCode(200);


		$this->post('/api/v1/qrcode/existe/customize', ['foreground_color' => '#808000'], ['apitoken' => $apikey])
			->seeStatusCode(200);

		$this->post('/api/v1/qrcode/existe/customize', ['background_color' => '#898989'], ['apitoken' => $apikey])
			->seeStatusCode(200);

		$this->post('/api/v1/qrcode/existe/customize', ['dot_style' => 'square'], ['apitoken' => $apikey])
			->seeStatusCode(200);

		$this->post('/api/v1/qrcode/existe/customize', ['dot_style' => 'dot'], ['apitoken' => $apikey])
			->seeStatusCode(200);

		$this->post('/api/v1/qrcode/existe/customize', ['dot_style' => 'round'], ['apitoken' => $apikey])
			->seeStatusCode(200);

		$this->post('/api/v1/qrcode/existe/customize', ['size' => 128], ['apitoken' => $apikey])
			->seeStatusCode(200);

		$this->post('/api/v1/qrcode/noexiste/customize', [], ['apitoken' => $apikey])
			->seeStatusCode(404);

		$this->post('/api/v1/qrcode/existe/customize', ['size' => 'texto'], ['apitoken' => $apikey])
			->seeStatusCode(422);
	}

	public function testUpdateQr()
	{
		$apikey = $this->post('/api/v1/login', ['email' => 'test-valid@mesacarlos.es', 'password' => 'asd'])->response->getOriginalContent()['api_token'];

		//Create QR code
		$this->post('/api/v1/qrcode', ['id' => 'existe', 'destination_url' => 'http://ingenieriainfomatica.uniovi.es'], ['apitoken' => $apikey])->seeStatusCode(200);


		$this->put('/api/v1/qrcode/existe', ['destination_url' => 'http://sies.uniovi.es/'], ['apitoken' => $apikey])
			->seeStatusCode(200);

		$this->put('/api/v1/qrcode/noexiste', ['destination_url' => 'http://sies.uniovi.es/'], ['apitoken' => $apikey])
			->seeStatusCode(404);
	}

	public function testGetStatsQr()
	{
		$apikey = $this->post('/api/v1/login', ['email' => 'test-valid@mesacarlos.es', 'password' => 'asd'])->response->getOriginalContent()['api_token'];

		//Create QR code
		$this->post('/api/v1/qrcode', ['id' => 'existe', 'destination_url' => 'http://ingenieriainfomatica.uniovi.es'], ['apitoken' => $apikey])->seeStatusCode(200);


		$this->get('/api/v1/qrcode/existe/stats', ['apitoken' => $apikey])
			->seeStatusCode(200);

		$this->get('/api/v1/qrcode/noexiste/stats', ['apitoken' => $apikey])
			->seeStatusCode(404);
	}

	public function testDeleteQr()
	{
		$apikey = $this->post('/api/v1/login', ['email' => 'test-valid@mesacarlos.es', 'password' => 'asd'])->response->getOriginalContent()['api_token'];

		//Create QR code
		$this->post('/api/v1/qrcode', ['id' => 'existe', 'destination_url' => 'http://ingenieriainfomatica.uniovi.es'], ['apitoken' => $apikey])->seeStatusCode(200);


		$this->delete('/api/v1/qrcode/existe', [], ['apitoken' => $apikey])
			->seeStatusCode(200);

		$this->delete('/api/v1/qrcode/noexiste', [], ['apitoken' => $apikey])
			->seeStatusCode(404);
	}
}
