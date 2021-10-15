<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ForgotPasswordMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public $email,$code;
    public function __construct($email,$code)
    {
        $this->email=$email;
        $this->code=$code;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
//        return $this->from('support@Indigenouslifestyle','Indigenous Lifestyle')->subject('Password Reset')->view('email.forgot-password');
        return $this->from('usmanarif.9219@gmail.com','Indigenous Lifestyle')->subject('Password Reset')->view('email.forgot-password');
    }
}
