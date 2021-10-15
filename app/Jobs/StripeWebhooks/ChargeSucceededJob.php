<?php

namespace App\Jobs\StripeWebhooks;

use App\Models\Payment;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Spatie\WebhookClient\Models\WebhookCall;
class ChargeSucceededJob implements ShouldQueue
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
//        Payment::create([
//            'payment_id'=>$charge['id'],
//            'user_id'=>$user->id,
//            'subtotal'=>$charge['amount']/100,
//            'total_amount'=>$charge['amount']/100,
//            'payment_by'=>'Stripe'
//        ]);
    }
}
