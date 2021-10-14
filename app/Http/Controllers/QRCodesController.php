<?php

namespace App\Http\Controllers;

use App\Models\Entities\QRCode;
use App\Models\Services\QRCodeService;
use http\Env\Response;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use \SimpleSoftwareIO\QrCode\Facades\QrCode as QRCodeBuilderFacade;
use function PHPUnit\Framework\isEmpty;

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

		$qrcode_builder = QRCodeBuilderFacade::format('png')
			->errorCorrection('H')
			->size(256);

		$qrcode->png_image = "data:image/png;base64," . base64_encode((string)$qrcode_builder->generate(env('APP_URL') . "/q/" . $qrcode->id));
		return response() -> json($qrcode, 200);
	}

	function getQRCodeCustomized(Request $req, string $id): JsonResponse {
		$this->validate($req, [
			'foreground_color' => 'regex:/^(#[a-zA-Z0-9]{6})$/i',
			'background_color' => 'regex:/^(#[a-zA-Z0-9]{6})$/i',
			'dot_style' => Rule::in(['square', 'dot', 'round']),
			'size' => 'integer|min:32|max:2048',
			//'base64Image' => 'image|mimes:png'
		]);

		$qrcode = QRCodeService::getQRCode($id);
		if($qrcode == NULL || (Auth::user()->id != $qrcode->user_id && !Auth::user()->is_admin))
			return response() -> json(['Error' => 'QRCode not found'], 404);

		$qrcode_builder = QRCodeBuilderFacade::format('png')
			->errorCorrection('H')
			->size($req->get('size') ?? 256);

		if($foreground_hex = $req->get('foreground_color')){
			$fore_r = hexdec(substr($foreground_hex,1,2));
			$fore_g = hexdec(substr($foreground_hex,3,2));
			$fore_b = hexdec(substr($foreground_hex,5,2));
			$qrcode_builder->color($fore_r, $fore_g, $fore_b);
		}

		if($background_hex = $req->get('background_color')){
			$back_r = hexdec(substr($background_hex,1,2));
			$back_g = hexdec(substr($background_hex,3,2));
			$back_b = hexdec(substr($background_hex,5,2));
			$qrcode_builder->backgroundColor($back_r, $back_g, $back_b);
		}

		if($dot_style = $req->get('dot_style')){
			$qrcode_builder->style($dot_style, 0.8);
		}

		if($logo = $req->get('base64Image')){
			$qrcode_builder->mergeString(base64_decode($logo), 0.3);
		}

		$qrcode->png_image = "data:image/png;base64," . base64_encode((string)$qrcode_builder->generate(env('APP_URL') . "/q/" . $qrcode->id));
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
			return response() -> json(['Error' => 'A QR Code with the given ID already exists'], 422);
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