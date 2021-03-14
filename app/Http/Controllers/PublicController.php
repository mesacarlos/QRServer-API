<?php

namespace App\Http\Controllers;

use App\Models\Services\QRClickService;
use App\Models\Services\QRCodeService;
use Illuminate\Http\Request;

class PublicController extends Controller {

	function qrRedirect(Request $req, string $id){
		$qrcode = QRCodeService::getQRCode($id);
		if(!$qrcode)
			return response() -> json("404 QR Code does not exist :sad:", 404);

		QRClickService::createQRClick(
			$id,
			$req->ip(),
			"", //TODO
			get_browser()->browser,
			get_browser()->platform,
			$req->getPreferredLanguage(),
			get_browser()->device_type,
		);

		return redirect($qrcode->destination_url);
	}

}