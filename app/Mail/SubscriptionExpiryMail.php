<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SubscriptionExpiryMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public $date,$packageName;

    public function __construct($date,$packageName)
    {
        $this->date=$date;
        $this->packageName=$packageName;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
//        return $this->from('Indigenouslifestyle@gmail.com','Indigenous Lifestyle')->subject('Subscription Renewal Notification')->view('email.subscription-expiry');
        return $this->from('usmanarif.9219@gmail.com','Indigenous Lifestyle')->subject('Subscription Renewal Notification')->view('email.subscription-expiry');
    }
}
