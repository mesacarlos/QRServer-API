<?php
namespace App\Mail;

use App\Models\Entities\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class VerifyAccount extends Mailable {
	use Queueable, SerializesModels;
	public $user;

	public function __construct(User $user){
		$this->user = $user;
	}

	//build the message.
	public function build(){
		return $this->view('account-verify');
	}
}