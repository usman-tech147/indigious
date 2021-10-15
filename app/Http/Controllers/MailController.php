<?php

namespace App\Http\Controllers;

use App\Mail\AdminForgotPassword;
use App\Mail\ForgotPasswordMail;
use App\Mail\SubscriptionExpired;
use App\Mail\SubscriptionExpiryMail;
use App\Mail\VerifyAccount;
use Illuminate\Support\Facades\Mail;

class MailController extends Controller
{
    public static function sendForgotPasswordMailAdmin($email,$code){
        Mail::to($email)->send(new AdminForgotPassword($email,$code));
    }
    public static function sendEmailVerification($token,$email){
        Mail::to($email)->send(new VerifyAccount($token,$email));
    }
    public static function sendSubscriptionExpiry($date,$packageName,$email){
        Mail::to($email)->send(new SubscriptionExpiryMail($date,$packageName));
    }
    public static function sendSubscriptionExpired($packageName,$email){
        Mail::to($email)->send(new SubscriptionExpired($packageName));
    }
    public static function sendForgotPasswordMail($email,$code){
        Mail::to($email)->send(new ForgotPasswordMail($email,$code));
    }
}
