<?php

namespace App\Jobs\StripeWebhooks;

use App\Http\Controllers\MailController;
use App\Models\Package;
use App\Models\Payment;
use App\Models\User;
use App\Models\WebsiteSettings;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Spatie\WebhookClient\Models\WebhookCall;

class CustomerSubscriptionDeletedJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /** @var WebhookCall */

    public $webhookCall;

    public function __construct(WebhookCall $webhookCall)
    {
        $this->webhookCall = $webhookCall;
    }

    public function handle()
    {
        $charge= $this->webhookCall->payload['data']['object'];
        $plan=$charge['plan'];;

        if($charge['status']=='canceled'){
            $user=User::where('customer_id',$charge['customer'])->first();
            if($plan['interval']=='month' && $plan['interval_count']==1){
                $package=Package::where('stripe_price_id',$plan['id'])->first();
            }
            else if($plan['interval']=='month' && $plan['interval_count']==6){
                $package=Package::where('stripe_price_six_id',$plan['id'])->first();
            }
            else if($plan['interval']=='year'){
                $package=Package::where('stripe_price_year_id',$plan['id'])->first();
            }
//            else{
//                $package=Package::where('stripe_price_id',$plan['id'])->first();
//            }
//            $user->packages()->detach($package->id);



            $subPackage=$user->packages->find($package->id)->pivot;
            if($subPackage->payment_by=='Manual'){
                exit(1);
            }
            $subPackage->subscribed_status='Expired';
            $subPackage->subscription_id=null;
            $subPackage->payment_method=null;
            $subPackage->payment_by=null;
            $subPackage->billing_agreement_id=null;
            $subPackage->subscribed_at=null;
            $subPackage->expired_at=null;
            $subPackage->frequency=null;
            $subPackage->interval_count=null;
            MailController::sendSubscriptionExpired($package->name,$user->email);
            if($subPackage->error_message!='Your Subscription has been expired due to payment failure.'){
                $subPackage->error_message='Your subscription has been expired because auto renewal was turned off.';
            }
            $subPackage->save();

        }
    }
}
