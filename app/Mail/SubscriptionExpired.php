<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SubscriptionExpired extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public $packageName;
    public function __construct($packageName)
    {
        $this->packageName=$packageName;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
//        return $this->from('info@indigenouslifestyle','Indigenous Lifestyle')->subject('Subscription Expired')->view('email.subscription-expired');
        return $this->from('usmanarif.9219@gmail.com','Indigenous Lifestyle')->subject('Subscription Expired')->view('email.subscription-expired');
    }
}
