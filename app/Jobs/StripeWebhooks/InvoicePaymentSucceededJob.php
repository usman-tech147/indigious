<?php

namespace App\Jobs\StripeWebhooks;

use App\Models\Package;
use App\Models\Payment;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Spatie\WebhookClient\Models\WebhookCall;

class InvoicePaymentSucceededJob implements ShouldQueue
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
        $user=User::where('customer_id',$charge['customer'])->first();

        if($charge['lines']['data'][0]['plan']['interval']=='month' && $charge['lines']['data'][0]['plan']['interval_count']==1){
            $package=Package::where('stripe_price_id',$charge['lines']['data'][0]['plan']['id'])->first();

        }
        else if($charge['lines']['data'][0]['plan']['interval']=='month' && $charge['lines']['data'][0]['plan']['interval_count']==6){
            $package=Package::where('stripe_price_six_id',$charge['lines']['data'][0]['plan']['id'])->first();

        }
        else if($charge['lines']['data'][0]['plan']['interval']=='year' && $charge['lines']['data'][0]['plan']['interval_count']==1){
            $package=Package::where('stripe_price_year_id',$charge['lines']['data'][0]['plan']['id'])->first();
        }
//        else{
//            $package=Package::where('stripe_price_id',$charge['lines']['data'][0]['plan']['id'])->first();
//        }
        Payment::create([
            'payment_id'=>$charge['subscription'],
            'user_id'=>$user->id,
            'subtotal'=>$charge['subtotal']/100,
            'total_amount'=>$charge['total']/100,
            'package_id'=>$package->id,
            'payment_by'=>'Stripe'
        ]);
    }
}
