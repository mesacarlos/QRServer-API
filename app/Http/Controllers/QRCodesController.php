<?php

namespace App\Http\Controllers;

use App\Models\Entities\QRCode;
use App\Models\Services\QRCodeService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class QRCodesController extends Controller {

	function getUserQRCodes(int $id): JsonResponse {
		return response() -> json(QRCodeService::getPaginatedAllQRCodesByUserId($id, 1), 200);
	}

	function getLoggedUserQRCodes(): JsonResponse {
		return response() -> json(QRCodeService::getPaginatedAllQRCodesByUserId(Auth::user()->id, 1), 200);
	}

}