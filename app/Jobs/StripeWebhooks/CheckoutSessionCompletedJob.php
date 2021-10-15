<?php

namespace App\Jobs\StripeWebhooks;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Spatie\WebhookClient\Models\WebhookCall;
use App\Models\Package;
use App\Models\ManualSubscriptionRequest;

class CheckoutSessionCompletedJob implements ShouldQueue
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
        if($charge['mode']=='setup'){

            \Stripe\Stripe::setApiKey(env('STRIPE_SK'));
            $intent = \Stripe\SetupIntent::retrieve($charge['setup_intent']);
            $user=User::where('customer_id',$intent['metadata']["customer_id"])->first();
//              $pivot=$user->packages->pluck('pivot')->where('subscription_id',$intent["subscription_id"])->first();
            $userPackage=$user->packages->where('id',$user->packages->pluck('pivot')->where('subscription_id',$intent['metadata']["subscription_id"])->first())->first();

            \Stripe\Subscription::update($intent['metadata']["subscription_id"], [
                'default_payment_method' => $intent['payment_method'],
            ]);
//            $stripe = new \Stripe\StripeClient(
//                env('STRIPE_SK')
//            );
//            $sub=$stripe->subscriptions->retrieve(
//                $intent['metadata']["subscription_id"]
//                ,
//                []
//            );
//            $invoice=$stripe->invoices->retrieve(
//                $sub->latest_invoice,
//                []
//            );
//            $payment_intent=$stripe->paymentIntents->retrieve(
//                $invoice->payment_intent,
//                []
//            );
//            if($payment_intent->status!='succeeded') {
//                $stripe->invoices->pay(
//                    "$sub->latest_invoice",
//                    []
//                );
//            }
        }
        if($charge['mode']=='subscription') {
            if ($charge['payment_status'] == 'paid') {
                $user = User::where('customer_id', $charge['customer'])->first();
                ManualSubscriptionRequest::where('user_id',$user->id)->where('package_id',$charge['metadata']['package_id'])
                    ->delete();
                try {
                    $user->packages()->attach($charge['metadata']['package_id'], ['subscription_id' => $charge['subscription'], 'username' => sha1(uniqid() . time() . date('d-m-y')), 'password' => '$2y$10$b19UQbpdgen.vWs8NzK0a.CFjrH.2jVcEgMhBt7pN6drd1aN3hkC6', 'created_at' => new \DateTime(), 'updated_at' => new \DateTime(), 'payment_by' => 'Stripe']);

                } catch (\Exception $ex) {
                    $subPackage = $user->packages->find($charge['metadata']['package_id'])->pivot;
                    $subPackage->subscribed_status = 'Active';
                    $subPackage->subscription_id = $charge['subscription'];
                    $subPackage->billing_agreement_id = null;
                    $subPackage->payment_by = 'Stripe';
                    $subPackage->save();
                }
            }
        }
    }
}
