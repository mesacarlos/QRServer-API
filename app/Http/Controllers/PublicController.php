<?php

namespace App\Http\Controllers;

use App\Models\Services\QRCodeService;

class PublicController extends Controller {

	function qrRedirect(string $id){
		$qrcode = QRCodeService::getQRCode($id);
		if(!$qrcode)
			return response() -> json("404", 404);

		return redirect($qrcode->destination_url);
	}

}