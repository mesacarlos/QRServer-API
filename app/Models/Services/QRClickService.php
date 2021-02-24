<?php


namespace App\Models\Services;


use App\Models\Entities\QRClick;

class QRClickService{
	static function createQRClick(): QRClick{
		//TODO
	}

	static function getAllQRClicks(){ //Esta funcion va a desaparecer fijo
		//TODO
	}

	static function getAllQRClicksByQRCode(int $qrcode_id){
		//TODO
	}

	static function getQRClick(string $qrclick_id): QRClick{
		//TODO
	}

	static function deleteQRClick(string $qrclick_id): bool{
		//TODO
	}


}