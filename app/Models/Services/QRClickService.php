<?php

namespace App\Models\Services;

use App\Models\Entities\QRClick;
use Carbon\Carbon;

class QRClickService{
	static function createQRClick(string $qrcode_id, string $ip, string $country_code, string $browser, string $os, string $language, string $device): QRClick{
		QRClick::create([
			"qrcode_id" => $qrcode_id,
			"access_datetime" => Carbon::now(),
			"access_ip" => $ip,
			"access_country_code" => $country_code,
			"access_browser" => $browser,
			"access_os" => $os,
			"access_language" => $language,
			"access_device" => $device,
		]);
	}




}