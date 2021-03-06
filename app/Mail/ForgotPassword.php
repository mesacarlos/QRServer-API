<?php
namespace App\Mail;

use App\Models\Entities\EmailVerifyToken;
use App\Models\Entities\ForgotPasswordToken;
use App\Models\Entities\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class ForgotPassword extends Mailable {
	use Queueable, SerializesModels;
	public $subject = "Recupera tu contraseña";
	public $user;
	public $token;

	public function __construct(User $user, ForgotPasswordToken $token){
		$this->user = $user;
		$this->token = $token;
	}

	//build the message.
	public function build(){
		return $this->view('forgot-password');
	}
}