<?php

namespace App\Mail;

use Illuminate\Mail\Mailable;

class WelcomeMail extends Mailable
{
    public function build()
    {
        return $this->subject('Welcome Email')
            ->view('emails.welcome');
    }
}