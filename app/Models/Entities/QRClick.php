<?php

namespace App\Models\Entities;

use Illuminate\Database\Eloquent\Model;

class QRClick extends Model {
	protected $table = 'qr_clicks';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'qrcode_id', 'access_datetime', 'access_country_code', 'access_browser', 'access_os', 'access_language', 'access_device'
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [
		'access_ip'
    ];
}
