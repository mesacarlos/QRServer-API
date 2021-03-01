<?php

namespace App\Http\Controllers;

use App\Models\Entities\QRCode;
use App\Models\Services\QRCodeService;
use http\Env\Response;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class QRCodesController extends Controller {

	function getUserQRCodes(int $id): JsonResponse {
		return response() -> json(QRCodeService::getPaginatedAllQRCodesByUserId($id, 20), 200);
	}

	function getLoggedUserQRCodes(): JsonResponse {
		return response() -> json(QRCodeService::getPaginatedAllQRCodesByUserId(Auth::user()->id, 20), 200);
	}

	function getQRCode(string $id): JsonResponse {
		$qrcode = QRCodeService::getQRCode($id);
		if($qrcode == NULL || (Auth::user()->id != $qrcode->user_id && !Auth::user()->is_admin))
			return response() -> json(['Error' => 'QRCode not found'], 404);

		return response() -> json($qrcode, 200);
	}

	function createQRCode(Request $req){
		$this->validate($req, [
			'id' => 'required|alpha_dash|min:3|max:16|unique:qr_codes',
			'destination_url' => 'required|url'
		]);

		$qrcode = QRCodeService::createQRCode($req->get("id"), Auth::user()->id, $req->get("destination_url"));

		if($qrcode == NULL)
			return response() -> json(['Error' => 'QRCode was not created'], 500);
		return response() -> json($qrcode, 200);
	}

	function updateQRCode(string $id, Request $req){
		$this->validate($req, [
			'id' => 'alpha_dash|min:3|max:16|unique:qr_codes',
			'destination_url' => 'url'
		]);
		//Comprobamos que el QR es del usuario, o que el usuario es admin
		$originalQRCode = QRCodeService::getQRCode($id);
		if(!$originalQRCode)
			return response() -> json(['Error' => 'QRCode not found'], 409);
		if($originalQRCode->user_id != Auth::user()->id && !Auth::user()->is_admin)
			return response() -> json(['Error' => 'Forbidden'], 403);

		//Es admin o es un QR del usuario, lo actualizamos
		$qrcode = QRCodeService::updateQRCode($id, $req->get("id"), $req->get("destination_url"));

		if(!$qrcode)
			return response() -> json(['Error' => 'QRCode not found'], 409);
		return response() -> json($qrcode, 200);
	}

	function deleteQRCode(string $id){
		//Comprobamos que el QR es del usuario, o que el usuario es admin
		$originalQRCode = QRCodeService::getQRCode($id);
		if(!$originalQRCode)
			return response() -> json(['Error' => 'QRCode not found'], 409);
		if($originalQRCode->user_id != Auth::user()->id && !Auth::user()->is_admin)
			return response() -> json(['Error' => 'Forbidden'], 403);

		//Es admin o es un QR del usuario, lo borramos
		$wasDeleted = QRCodeService::deleteQRCode($id);
		return response() -> json(['QRCodeDeleted' => $wasDeleted], 200);
	}

}