<?php


namespace App\Models\Services;


use App\Models\Entities\QRCode;

class QRCodeService{
	static function createQRCode(): QRCode{
		//TODO
	}

	static function getAllQRCodes(){
		//TODO
	}

	static function getAllQRCodesByUserId(int $userId){
		//TODO
	}

	static function getQRCode(string $token_id): QRCode{
		//TODO
	}

	static function updateQRCode(): QRCode{
		//TODO (y tambien cuales serán los parametros de la funcion)
	}

	static function deleteQRCode(string $token_id): bool{
		//TODO
	}


}