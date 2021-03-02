<?php
namespace App\Mail;

use App\Models\Entities\EmailVerifyToken;
use App\Models\Entities\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class VerifyAccount extends Mailable {
	use Queueable, SerializesModels;
	public $subject = "Verifica tu cuenta";
	public $user;
	public $emailtoken;

	public function __construct(User $user, EmailVerifyToken $emailtoken){
		$this->user = $user;
		$this->emailtoken = $emailtoken;
	}

	//build the message.
	public function build(){
		return $this->view('account-verify');
	}
}