<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ContactUs extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public $data;
    public function __construct($data)
    {
        $this->data=$data;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
//        return $this->from('contact-us@indigenouslifestyle','Indigenous Lifestyle')->subject($this->data['subject'])->view('email.contact-us');
        return $this->from('usmanarif.9219@gmail.com','Indigenous Lifestyle')->subject($this->data['subject'])->view('email.contact-us');
    }
}
