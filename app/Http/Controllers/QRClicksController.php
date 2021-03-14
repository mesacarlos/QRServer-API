<?php

namespace App\Http\Controllers;

use App\Models\Entities\QRClick;
use App\Models\Services\QRClickService;
use App\Models\Services\QRCodeService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class QRClicksController extends Controller {

	function getStats(Request $req, string $id): JsonResponse {
		$qrcode = QRCodeService::getQRCode($id);

		if(!$qrcode)
			return response() -> json(['Error' => 'QRCode not found'], 404);

		$start_timestamp = !empty($req->get("start_timestamp")) && is_numeric($req->get("start_timestamp")) && $req->get("start_timestamp") != 0
			? $req->get("start_timestamp") : null;
		$end_timestamp = !empty($req->get("end_timestamp")) && is_numeric($req->get("end_timestamp")) && $req->get("end_timestamp") != 0
			? $req->get("end_timestamp") : null;

		//Get every QRClick
		$qrclicks = QRClickService::getQRClicksByQRCodeInRange($id, $start_timestamp, $end_timestamp);

		//Get every QRClick grouping by browser and replacing numbers with percentages
		$topBrowser = QRClickService::getQRClicksGroupByParamByQRCodeInRange('access_browser', $id, $start_timestamp, $end_timestamp);
		foreach ($topBrowser as $elem)
			$elem->total = ($elem->total / $qrclicks->count())*100;

		//Get every QRClick grouping by OS and replacing numbers with percentages
		$topOS = QRClickService::getQRClicksGroupByParamByQRCodeInRange('access_os', $id, $start_timestamp, $end_timestamp);
		foreach ($topOS as $elem)
			$elem->total = ($elem->total / $qrclicks->count())*100;

		//Get every QRClick grouping by locale and replacing numbers with percentages
		$topLocale = QRClickService::getQRClicksGroupByParamByQRCodeInRange('access_language', $id, $start_timestamp, $end_timestamp);
		foreach ($topLocale as $elem)
			$elem->total = ($elem->total / $qrclicks->count())*100;

		//Get every QRClick grouping by device and replacing numbers with percentages
		$topDevice = QRClickService::getQRClicksGroupByParamByQRCodeInRange('access_device', $id, $start_timestamp, $end_timestamp);
		foreach ($topDevice as $elem)
			$elem->total = ($elem->total / $qrclicks->count())*100;

		return response() -> json([
			'total_clicks' => $qrclicks->count(),
			'top_browser' => $topBrowser,
			'top_os' => $topOS,
			'top_locale' => $topLocale,
			'top_device' => $topDevice,
		], 200);
	}

}