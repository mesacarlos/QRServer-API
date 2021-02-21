<?php

namespace App\Models\Entities;

use Illuminate\Database\Eloquent\Model;

class QRCode extends Model {
	protected $table = 'qr_codes';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id', 'destination_url', 'created_at', 'updated_at'
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [

    ];
}
