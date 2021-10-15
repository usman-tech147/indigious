<?php

namespace App\Jobs\StripeWebhooks;

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

class CustomerSubscriptionUpdatedJob implements ShouldQueue
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
        $plan=$charge['plan'];
        $invoice=$charge['latest_invoice'];
        $stripe = new \Stripe\StripeClient(
            env("STRIPE_SK")
        );
        $in= $stripe->invoices->retrieve(
            $invoice,
            []);
        try {
            if($in['status']!='paid'){
                $stripe->invoices->pay(
                    $invoice,
                    []
                );
            }
        }
        catch (\Exception $e){
            $sub=$stripe->subscriptions->retrieve(
                $charge['id'],
                []
            );
            if($sub['status']=='past_due'){
                \Stripe\Stripe::setApiKey(env('STRIPE_SK'));
                $user=User::where('customer_id', $charge['customer'])->first();
                if($plan['interval']=='month' && $plan['interval_count']==1){
                    $userPackage=$user->packages->where('stripe_price_id', $plan['id'])->first();
                }
                else if($plan['interval']=='month' && $plan['interval_count']==6){
                    $userPackage=$user->packages->where('stripe_price_six_id', $plan['id'])->first();
                }
                else if($plan['interval']=='year' && $plan['interval_count']==1){
                    $userPackage=$user->packages->where('stripe_price_year_id', $plan['id'])->first();
                }
//                else{
//                    $userPackage=$user->packages->where('stripe_price_id', $plan['id'])->first();
//                }
                $userPackage->pivot->error_message='Your Subscription has been expired due to payment failure.';
                $userPackage->pivot->save();
                $subscription = \Stripe\Subscription::retrieve($userPackage->pivot->subscription_id);

                $subscription->cancel();
            }
        }
        if($charge['status']!='past_due'){
            if($plan['interval']=='month' && $plan['interval_count']==1){
                $pivot = User::where('customer_id', $charge['customer'])->first()->packages->where('stripe_price_id', $plan['id'])->first()->pivot;
            }
            else if($plan['interval']=='month' && $plan['interval_count']==6){
                $pivot = User::where('customer_id', $charge['customer'])->first()->packages->where('stripe_price_six_id', $plan['id'])->first()->pivot;
            }
            else if($plan['interval']=='year'){
                $pivot = User::where('customer_id', $charge['customer'])->first()->packages->where('stripe_price_year_id', $plan['id'])->first()->pivot;
            }
//            else{
//                $pivot = User::where('customer_id', $charge['customer'])->first()->packages->where('stripe_price_id', $plan['id'])->first()->pivot;
//            }
            $pivot->subscribed_status = ucwords($charge['status']);
            $pivot->frequency=strtoupper($plan['interval']);
            $pivot->interval_count=$plan['interval_count'];
            $pivot->renewal_status = $charge['cancel_at_period_end'];
            $pivot->subscribed_at = date('Y-m-d H:i:s', $charge['current_period_start']);
            $pivot->expired_at = date('Y-m-d H:i:s', $charge['current_period_end']);
            $pivot->payment_method = $charge['default_payment_method'];
            $pivot->billing_agreement_id=null;
            $pivot->updated_at = Carbon::now();
            $pivot->save();
        }
    }
}
