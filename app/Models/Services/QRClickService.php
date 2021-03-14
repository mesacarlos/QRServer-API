<?php

namespace App\Models\Services;

use App\Models\Entities\QRClick;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class QRClickService{
	static function createQRClick(string $qrcode_id, string $ip, string $country_code, string $browser, string $os, string $language, string $device): QRClick{
		return QRClick::create([
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

	static function getQRClicksByQRCodeInRange(string $qrcode_id, int|null $start_timestamp, int|null $end_timestamp){
		$result = QRClick::where('qrcode_id', $qrcode_id);

		if($start_timestamp)
			$result->whereDate('access_datetime', '>=', date("Y-m-d H:i:s", $start_timestamp));
		if($end_timestamp)
			$result->whereDate('access_datetime', '<=', date("Y-m-d H:i:s", $end_timestamp));

		return $result->get();
	}

	static function getQRClicksGroupByParamByQRCodeInRange(string $groupBy, string $qrcode_id, int|null $start_timestamp, int|null $end_timestamp){
		$result = QRClick::groupBy($groupBy)
			->select($groupBy, DB::raw('count(*) as total'))
			->where('qrcode_id', $qrcode_id);

		if($start_timestamp)
			$result->whereDate('access_datetime', '>=', date("Y-m-d H:i:s", $start_timestamp));
		if($end_timestamp)
			$result->whereDate('access_datetime', '<=', date("Y-m-d H:i:s", $end_timestamp));

		return $result->get();
	}


}