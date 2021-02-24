<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateQrclicksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('qr_clicks', function (Blueprint $table) {
			$table->increments('id');
			$table->string('qrcode_id');
			$table->foreign('qrcode_id')->references('id')->on('qr_codes')->onUpdate('cascade')->onDelete('cascade');
			$table->dateTime('access_datetime'); //Cuando el usuario ha entrado en el enlace
			$table->ipAddress('access_ip');
			$table->string('access_country_code', 2);
			$table->string('access_browser');
			$table->string('access_os'); //operating system
			$table->string('access_language');
			$table->string('access_device');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('qr_clicks');
    }
}
