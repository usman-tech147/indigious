<?php

namespace App\Http\Controllers;

use App\Models\Package;
use App\Models\Payment;
use App\Models\PayPal;
use App\Models\PayPalAgreement;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use \PayPal\Api\VerifyWebhookSignature;
use App\Models\ManualSubscriptionRequest;

class PayPalWebhookController extends Controller
{
    public function webhook(Request $request){
        //get the webhook payload
        $requestBody = file_get_contents('php://input');

        //check if webhook payload has data
        if($requestBody) {
            //request body is set
        } else {
            //request body is not set
            exit();
        }
//Receive HTTP headers that you received from PayPal webhook.
        $headers = getallheaders();
        $headers = array_change_key_case($headers, CASE_UPPER);

//if any of the relevant paypal signature headers are not set exit()
        if(
            (!array_key_exists('PAYPAL-AUTH-ALGO', $headers)) ||
            (!array_key_exists('PAYPAL-TRANSMISSION-ID', $headers)) ||
            (!array_key_exists('PAYPAL-CERT-URL', $headers)) ||
            (!array_key_exists('PAYPAL-TRANSMISSION-SIG', $headers)) ||
            (!array_key_exists('PAYPAL-TRANSMISSION-TIME', $headers))
        )
        {
            exit();
        }
//specify the ID for the webhook that you have set up on the paypal developer website, each web hook that you create has a unique ID
        $webhookID = env('PAYPAL_WEBHOOK');

//start paypal webhook signature validation

        $signatureVerification = new VerifyWebhookSignature();
        $signatureVerification->setAuthAlgo($headers['PAYPAL-AUTH-ALGO']);
        $signatureVerification->setTransmissionId($headers['PAYPAL-TRANSMISSION-ID']);
        $signatureVerification->setCertUrl($headers['PAYPAL-CERT-URL']);
        $signatureVerification->setWebhookId($webhookID);
        $signatureVerification->setTransmissionSig($headers['PAYPAL-TRANSMISSION-SIG']);
        $signatureVerification->setTransmissionTime($headers['PAYPAL-TRANSMISSION-TIME']);

        $signatureVerification->setRequestBody($requestBody);
        $request = clone $signatureVerification;

        try {

            $output = $signatureVerification->post( PayPalController::getApiContext());

        } catch (\Exception $ex) {


            exit(1);

        }
        $sigVerificationResult = $output->getVerificationStatus();

        if($sigVerificationResult != "SUCCESS"){

            exit();
        }
        else if($sigVerificationResult == "SUCCESS"){

            $requestBodyDecode = json_decode($requestBody);
            try {
                $paypal=PayPal::create([
                    'event_id' => $requestBodyDecode->id,
                    'event' => $requestBodyDecode->event_type,
                    'response' => $requestBody
                ]);

                $paymentSystemID = $requestBodyDecode->id;


                $eventType = $requestBodyDecode->event_type;
                try {


                    if ($eventType == "BILLING.SUBSCRIPTION.ACTIVATED") {
                        $billingAgreement = PayPalAgreement::where('agreement_id', $requestBodyDecode->resource->id)->first();

                        $user = User::find($billingAgreement->user_id);
                        ManualSubscriptionRequest::where('user_id',$billingAgreement->user_id)->where('package_id',$billingAgreement->package_id)->delete();
                        $subscribed_at = new Carbon($requestBodyDecode->resource->billing_info->last_payment->time);
                        $expired_at = new Carbon($requestBodyDecode->resource->billing_info->next_billing_time);
                        $plan = PayPalController::planDetails($requestBodyDecode->resource->plan_id);
                        try {

                            $user->packages()->attach($billingAgreement->package_id,
                                [
                                    'subscribed_at' => $subscribed_at,
                                    'expired_at' => $expired_at,
                                    'subscribed_status' => ucwords(strtolower($requestBodyDecode->resource->status)),
                                    'renewal_status' => true,
                                    'billing_agreement_id' => $requestBodyDecode->resource->id,
                                    'username' => sha1(uniqid() . time() . date('d-m-y')),
                                    'password' => '$2y$10$b19UQbpdgen.vWs8NzK0a.CFjrH.2jVcEgMhBt7pN6drd1aN3hkC6',
                                    'created_at' => new \DateTime(),
                                    'updated_at' => new \DateTime(),
                                    'payment_by' => 'PayPal',
                                    'error_message' => 'Waiting for Payment to complete.',
                                    'frequency' => $plan->billing_cycles[0]->frequency->interval_unit,
                                    'interval_count' => $plan->billing_cycles[0]->frequency->interval_count,

                                ]);

                        } catch (\Exception $ex) {
                            $subPackage = $user->packages->where('id', $billingAgreement->package_id)->first()->pivot;

                            if ($subPackage->subscribed_status == 'Expired') {
                                $subPackage->error_message = 'Waiting for Payment to complete.';
                            }

                            $subPackage->subscribed_status = ucwords(strtolower($requestBodyDecode->resource->status));
                            $subPackage->billing_agreement_id = $requestBodyDecode->resource->id;
                            $subPackage->frequency = $plan->billing_cycles[0]->frequency->interval_unit;
                            $subPackage->interval_count = $plan->billing_cycles[0]->frequency->interval_count;
                            $subPackage->payment_by = 'PayPal';
                            $subPackage->subscribed_at = $subscribed_at;
                            $subPackage->expired_at = $expired_at;
                            $subPackage->subscription_id = null;
                            if (ucwords(strtolower($requestBodyDecode->resource->status)) == 'Active') {
                                $subPackage->renewal_status = true;
                            } else {
                                $subPackage->renewal_status = false;
                            }
                            $subPackage->updated_at = Carbon::now();
                            $subPackage->save();

                        }
                    }
                    if ($eventType == "PAYMENT.SALE.COMPLETED") {
                        $billingAgreement = PayPalAgreement::where('agreement_id', $requestBodyDecode->resource->billing_agreement_id)->first();
                        $payment = Payment::create([
                            'user_id' => $billingAgreement->user_id,
                            'payment_id' => $billingAgreement->agreement_id,
                            'subtotal' => $requestBodyDecode->resource->amount->total,
                            'total_amount' => $requestBodyDecode->resource->amount->total,
                            'package_id' => $billingAgreement->package_id,
                            'payment_by' => 'Paypal'
                        ]);
                        $agreement = PayPalController::subscriptionDetails($requestBodyDecode->resource->billing_agreement_id);
                        $plan = PayPalController::planDetails($agreement->plan_id);
                        $user = User::find($billingAgreement->user_id);
                        $userPackage = $user->packages->where('pivot.billing_agreement_id', $requestBodyDecode->resource->billing_agreement_id)->first();


                        $userPackage->pivot->subscribed_status = ucwords(strtolower($agreement->status));
                        $userPackage->pivot->frequency = $plan->billing_cycles[0]->frequency->interval_unit;
                        $userPackage->pivot->interval_count = $plan->billing_cycles[0]->frequency->interval_count;
                        $userPackage->pivot->payment_by = 'PayPal';
                        $userPackage->pivot->billing_agreement_id = $requestBodyDecode->resource->billing_agreement_id;
                        $userPackage->pivot->subscription_id = null;
                        $userPackage->pivot->error_message = null;
                        $subscribed_at = new \Carbon\Carbon($agreement->billing_info->last_payment->time);
//
                        $expired_at = new Carbon($agreement->billing_info->next_billing_time);

                        if ($subscribed_at->diffInDays($expired_at)>=364) {
                            $userPackage->pivot->expired_at = $expired_at;
                        }
                        else{
                            $expired_at = new Carbon($subscribed_at);

                            if($plan->billing_cycles[0]->frequency->interval_unit=='MONTH' &&  $plan->billing_cycles[0]->frequency->interval_count==1){
                                $expired_at=$expired_at->addMonths(1);
                            }
                            if($plan->billing_cycles[0]->frequency->interval_unit=='MONTH' &&  $plan->billing_cycles[0]->frequency->interval_count==6){
                                $expired_at=$expired_at->addMonths(6);
                            }
                            if($plan->billing_cycles[0]->frequency->interval_unit=='YEAR' &&  $plan->billing_cycles[0]->frequency->interval_count==1){
                                $expired_at=$expired_at->addYear();
                            }
                            $userPackage->pivot->expired_at = $expired_at;
                        }

                        if($agreement->status=='ACTIVE'){
                            $userPackage->pivot->renewal_status = true;

                        }else{
                            $userPackage->pivot->renewal_status = false;

                        }
                        $userPackage->pivot->subscribed_at = $subscribed_at;
                        $userPackage->pivot->save();
                    }
                    if ($eventType == "BILLING.SUBSCRIPTION.SUSPENDED") {
                        $billingAgreement = PayPalAgreement::where('agreement_id', $requestBodyDecode->resource->id)->first();
                        $user = User::find($billingAgreement->user_id);
                        $userPackage = $user->packages->where('pivot.billing_agreement_id', $requestBodyDecode->resource->id)->first();
                        $userPackage->pivot->subscribed_status = ucwords(strtolower($requestBodyDecode->resource->status));
                        if ($userPackage->pivot->renewal_status == '1') {
                            $userPackage->pivot->renewal_status = false;
                        }
                        $userPackage->pivot->save();
                    }
                    if ($eventType == 'BILLING.SUBSCRIPTION.CANCELLED') {
                        $billingAgreement = PayPalAgreement::where('agreement_id', $requestBodyDecode->resource->id)->first();
                        $user = User::find($billingAgreement->user_id);
                        $userPackage = $user->packages->where('pivot.billing_agreement_id', $requestBodyDecode->resource->id)->first();
                        if(empty($userPackage)){
                            exit(1);
                        }
                        $userPackage->pivot->subscribed_status = ucwords(strtolower($requestBodyDecode->resource->status));
                        if ($userPackage->pivot->renewal_status == '1') {
                            $userPackage->pivot->renewal_status = false;
                        }
                        $userPackage->pivot->save();
                    }

                } catch (\Exception $ex) {
                    $paypal->exception=$ex->getMessage();
                    $paypal->save();
                }
            }catch (\Exception $ex){

            }

        }
    }
}
