<?php

namespace App\Http\Controllers;

use App\Models\Entities\QRCode;
use App\Models\Services\QRCodeService;
use http\Env\Response;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class QRCodesController extends Controller {

	function getUserQRCodes(Request $req, int $id): JsonResponse {
		$itemsPerPage = $req->get("itemsPerPage");
		if(!$itemsPerPage)
			$itemsPerPage = 20;
		return response() -> json(QRCodeService::getPaginatedAllQRCodesByUserId($id, $itemsPerPage), 200);
	}

	function getLoggedUserQRCodes(Request $req): JsonResponse {
		$itemsPerPage = $req->get("itemsPerPage");
		if(!$itemsPerPage)
			$itemsPerPage = 20;
		return response() -> json(QRCodeService::getPaginatedAllQRCodesByUserId(Auth::user()->id, $itemsPerPage), 200);
	}

	function getQRCode(string $id): JsonResponse {
		$qrcode = QRCodeService::getQRCode($id);
		if($qrcode == NULL || (Auth::user()->id != $qrcode->user_id && !Auth::user()->is_admin))
			return response() -> json(['Error' => 'QRCode not found'], 404);

		//Add svg_image to the response
		$qrcode->svg_image = (string)\SimpleSoftwareIO\QrCode\Facades\QrCode::errorCorrection('H')->size(100)->generate(env('APP_URL') . "/q/" . $qrcode->id);
		return response() -> json($qrcode, 200);
	}

	function createQRCode(Request $req){
		$this->validate($req, [
			'id' => 'alpha_dash|min:3|max:16|unique:qr_codes',
			'destination_url' => 'required|url'
		]);

		$qrcode_id = $req->get("id");
		if(!$qrcode_id) {
			$qrcode_id = bin2hex(random_bytes(8));
			while(QRCodeService::getQRCode($qrcode_id)) //If already exists...
				$qrcode_id = bin2hex(random_bytes(8));
		}

		$qrcode = QRCodeService::createQRCode($qrcode_id, Auth::user()->id, $req->get("destination_url"));

		if($qrcode == NULL)
			return response() -> json(['Error' => 'A QR Code with the given ID already exists'], 409);
		return response() -> json($qrcode, 200);
	}

	function updateQRCode(string $id, Request $req){
		$this->validate($req, [
			'id' => 'alpha_dash|min:3|max:16',
			'destination_url' => 'url'
		]);
		//Comprobamos que el QR es del usuario, o que el usuario es admin
		$originalQRCode = QRCodeService::getQRCode($id);
		if(!$originalQRCode)
			return response() -> json(['Error' => 'QRCode not found'], 404);
		if($originalQRCode->user_id != Auth::user()->id && !Auth::user()->is_admin)
			return response() -> json(['Error' => 'Forbidden'], 403);

		//Es admin o es un QR del usuario, lo actualizamos
		$qrcode = QRCodeService::updateQRCode($id, $req->get("id"), $req->get("destination_url"));

		if(!$qrcode)
			return response() -> json(['Error' => 'QRCode not found'], 404);
		return response() -> json($qrcode, 200);
	}

	function deleteQRCode(string $id){
		//Comprobamos que el QR es del usuario, o que el usuario es admin
		$originalQRCode = QRCodeService::getQRCode($id);
		if(!$originalQRCode)
			return response() -> json(['Error' => 'QRCode not found'], 404);
		if($originalQRCode->user_id != Auth::user()->id && !Auth::user()->is_admin)
			return response() -> json(['Error' => 'Forbidden'], 403);

		//Es admin o es un QR del usuario, lo borramos
		$wasDeleted = QRCodeService::deleteQRCode($id);
		return response() -> json($wasDeleted, 200);
	}

}