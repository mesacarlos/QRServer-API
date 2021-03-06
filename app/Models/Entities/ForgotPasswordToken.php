<?php

namespace App\Models\Entities;

use Illuminate\Database\Eloquent\Model;

class ForgotPasswordToken extends Model {
	public $timestamps = false;
	public $incrementing = false;
	protected $keyType = 'string';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
		'id', 'user_id'
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [

    ];
}
