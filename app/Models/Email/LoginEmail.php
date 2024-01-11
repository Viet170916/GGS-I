<?php
namespace App\Models\Email;

use Tymon\JWTAuth\Token;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class LoginEmail extends Mailable {
    use SerializesModels;

    public Token $token;
    public function __construct( $data ) {
        $this->token = $data;
    }
    public function build(): LoginEmail {
        return $this->subject( 'Welcome to our website' )
            ->view( 'emails.loginEmail' )
            ->with( [ 'token' => $this->token ] );
    }
}


