<?php

namespace App\Http\Controllers;

use App\Models\Entities\QRClick;
use Illuminate\Http\JsonResponse;

class QRClicksController extends Controller {

	function test(): JsonResponse {
		return response() -> json(QRClick::all(), 200);
	}

}