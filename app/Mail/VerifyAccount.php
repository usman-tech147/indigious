<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class VerifyAccount extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public $token,$email;
    public function __construct($token,$email)
    {
        $this->token=$token;
        $this->email=$email;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
//        return $this->from('support@indigenouslifestyle.com','Indigenous Lifestyle')->subject('Email Verification')->view('email.verify-account');
        return $this->from('usmanarif.9219@gmail.com','Indigenous Lifestyle')->subject('Email Verification')->view('email.verify-account');
    }
}
