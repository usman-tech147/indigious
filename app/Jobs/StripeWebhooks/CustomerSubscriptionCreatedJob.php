<?php

namespace App\Jobs\StripeWebhooks;

use App\Models\User;
use App\Models\Package;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Spatie\WebhookClient\Models\WebhookCall;

class CustomerSubscriptionCreatedJob implements ShouldQueue
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

//        $charge= $this->webhookCall->payload['data']['object'];
//        $user=User::where('customer_id',$charge['customer'])->first();
//        $user->packages()->attach(Package::where('stripe_price_id',$charge['plan']['id'])->first()->id,['subscription_id'=>$charge['id'],'username'=>sha1(uniqid().time().date('d-m-y')),'password'=>'$2y$10$b19UQbpdgen.vWs8NzK0a.CFjrH.2jVcEgMhBt7pN6drd1aN3hkC6','created_at'=> new \DateTime(),'updated_at'=> new \DateTime()]);

    }
}
