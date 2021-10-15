<?php

namespace App\Console\Commands;

use App\Http\Controllers\MailController;
use App\Http\Controllers\PayPalController;
use App\Models\Package;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use PayPal\Api\Agreement;
use function Clue\StreamFilter\fun;

class SubscriptionExpiryMail extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'subscription:mail';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send Subscription Expiry Mail';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $users=User::with('packages')->get();
        foreach ($users as $user){
            foreach ($user->packages as $package){
                    if($package!=null){
                        $date=\Carbon\Carbon::now()->startOfDay();
                        $d=strtotime($package->pivot->expired_at);

                        $newDate=new \Carbon\Carbon($d);
                        $newDate=$newDate->startOfDay();
                        $diff=$date->diffInDays($newDate);
                        if($package->pivot->payment_by=='Stripe'){
                            if($package->pivot->subscribed_status=='Active')
                            {
                            if($package->pivot->renewal_status==0){
                                    $this->send($diff,$package,$user);
                                }
                            }
                        }
                        if($package->pivot->payment_by=='PayPal'){
                            if($package->pivot->subscribed_status=='Active') {
                                if ($package->pivot->renewal_status == 1) {
                                    $this->send($diff, $package, $user);
                                }
                            }
                        }
                    }
                    $this->info('Current Date: '.$date.' Expiry Date:'.$newDate.'Difference: '.$diff);
            }
        }

    }
   public function send($diff,$package,$user){

        if($package->pivot->payment_by=='Stripe'){

            if($package->pivot->frequency=='YEAR'){
                if($diff==30){
                    $c=new Carbon($package->pivot->expired_at);
                    MailController::sendSubscriptionExpiry($c->format('d-M-Y'),$package->name,$user->email);
                    }
                }
            }
               if($package->pivot->payment_by=='PayPal'){

                   $frequency= $package->pivot->frequency;

                   if($frequency=='YEAR'){

                       if($diff==30){
                           $c=new Carbon($package->pivot->expired_at);
                           MailController::sendSubscriptionExpiry($c->format('d-M-Y'),$package->name,$user->email);
                       }
                   }
               }
                if($diff==7){
                    $c=new Carbon($package->pivot->expired_at);
                    MailController::sendSubscriptionExpiry($c->format('d-M-Y'),$package->name,$user->email);
                }
                if($diff==3){
                    $c=new Carbon($package->pivot->expired_at);
                    MailController::sendSubscriptionExpiry($c->format('d-M-Y'),$package->name,$user->email);
                }
                if($diff==1){
                        $c=new Carbon($package->pivot->expired_at);
                    MailController::sendSubscriptionExpiry($c->format('d-M-Y'),$package->name,$user->email);
                 }
            }
}
