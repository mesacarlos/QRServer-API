<?php

namespace App\Http\Controllers;

use App\Models\Entities\QRCode;
use Illuminate\Http\JsonResponse;

class QRCodesController extends Controller {

	function test(): JsonResponse {
		return response() -> json(QRCode::all(), 200);
	}

}