<?php


namespace App\Models\Services;


use App\Models\Entities\QRCode;

class QRCodeService{
	static function createQRCode(string $id, int $user_id, string $destination_url): ?QRCode{ //TODO sin testear
		if(self::getQRCode($id))
			return NULL;

		return QRCode::create([
			'id' => $id,
			'user_id' => $user_id,
			'destination_url' => $destination_url
		]);
	}

	/**
	 * Get all QRCodes, paginated
	 * @param int $qrsPerPage Number of QRs per page
	 * @return array Array cointaining qrcodes, the number of total QRCodes, and the number of items per page
	 */
	static function getPaginatedAllQRCodes(int $qrsPerPage){
		$items = QRCode::paginate($qrsPerPage) -> items();
		$numItems = QRCode::paginate($qrsPerPage) -> total();
		return array("QRCodes" => $items, "totalQRCodes" => $numItems, "itemsPerPage" => $qrsPerPage);
	}

	/**
	 * Get all QRCodes of a given User Id, paginated
	 * @param int $userId Id of the user
	 * @param int $qrsPerPage Number of QRs per page
	 * @return array Array cointaining qrcodes, the number of total QRCodes, and the number of items per page
	 */
	static function getPaginatedAllQRCodesByUserId(int $userId, int $qrsPerPage){
		$items = QRCode::where('user_id', $userId)->paginate($qrsPerPage) -> items();
		$numItems = QRCode::where('user_id', $userId)->paginate($qrsPerPage) -> total();
		return array("QRCodes" => $items, "totalQRCodes" => $numItems, "itemsPerPage" => $qrsPerPage);
	}

	/**
	 * Get a QRCode by its Id
	 * @param string $token_id QRCode Id
	 * @return QRCode The QRCode object
	 */
	static function getQRCode(string $token_id): QRCode{ //TODO sin testear
		return QRCode::where('id', $token_id);
	}

	/**
	 * Update a QRCode
	 * @param string $id Id of the QRCode
	 * @param string $destination_url new URL of the QRCode. DOES OT CHECK THAT IT IS A VALID URL!
	 * @return QRCode|null The updated QRCode. NULL if a QRCode with the given id was not found.
	 */
	static function updateQRCode(string $id, string $destination_url): ?QRCode{ //TODO sin testear
		$qrcode = QRCode::find($id);
		if(!$qrcode)
			return NULL;
		if($destination_url)
			$qrcode -> destination_url = $destination_url;
		$qrcode->save();
		return $qrcode;
	}

	/**
	 * Delete the QRCode with the given Id
	 * @param string $token_id Id of the token
	 * @return bool true if any token was deleted. false otherwise.
	 */
	static function deleteQRCode(string $token_id): bool{ //TODO sin testear
		$count = QRCode::destroy($token_id);
		return $count > 0;
	}


}