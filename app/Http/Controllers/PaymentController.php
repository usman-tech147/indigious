<?php

namespace App\Http\Controllers;

use App\Models\ManualSubscriptionRequest;
use App\Models\Package;
use App\Models\Payment;
use App\Models\PayPalAgreement;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Response;
use PayPal\Api\Agreement;
use PayPal\Api\Payer;
use PayPal\Api\Plan;
use PayPal\Api\ShippingAddress;
class PaymentController extends Controller
{
    public function payManually(Request $request){
        $request->validate([
           'package'=>'required|exists:packages,id'
        ]);
        try {
            $ms=ManualSubscriptionRequest::where('user_id',Auth::guard('user')->id())
                ->where('package_id',$request->input('package'))
                ->where('status','Rejected')
                ->first();
            if($ms){
             $ms->status='Pending';
            }else {
                $ms=new ManualSubscriptionRequest([
                    'package_id' => $request->input('package'),
                    'user_id' => Auth::guard('user')->id(),
                ]);
            }
            if($ms->save()){
                return Response::json(['message'=>'request sent']);

            }
        }catch (\Exception $exception){

            return Response::json(['message'=>'request failed to sent'],422);

        }
    }
    public function payWithStripe(Request $request)
    {
        $request->validate([
//            'plan'=>'required',
//            'interval'=>'required',
            'package_id'=>'required'
        ]);

        $user=User::findOrFail(auth()->guard('user')->user()->id);
        \Stripe\Stripe::setApiKey(env('STRIPE_SK'));
        header('Content-Type: application/json');

        $package=Package::findOrFail($request->input('package_id'));
////Only annual
//        if($request->input('plan')=='month' && $request->input('interval')==1){
//            $priceId=$package->stripe_price_id;
//        }
//        if($request->input('plan')=='month' && $request->input('interval')==6){
//            $priceId=$package->stripe_price_six_id;
//        }
//        else if($request->input('plan')=='year'){
//            $priceId=$package->stripe_price_year_id;
//        }
////
        $priceId=$package->stripe_price_year_id;
        $alreadySubscribed=$user->packages->where('id',$package->id)->first();
        if($alreadySubscribed && $alreadySubscribed->pivot->subscribed_status!= 'Expired'){
            return Response::json(['message' => "Already Subscribed"],422);
        }
        $checkout_session = \Stripe\Checkout\Session::create([
            'payment_method_types' => ['card'],
            'line_items' => [[
                'price' => $priceId,
                'quantity' => 1,
            ]],
            "customer"=>$user->customer_id,
            'mode' => 'subscription',
            'metadata'=>[
                'package_id'=>$package->id
            ],
            'success_url' => 'http://localhost:8000/user/success?session_id={CHECKOUT_SESSION_ID}',
//            'cancel_url' => env('APP_URL'),
            'cancel_url' => url()->previous(),
        ]);

        return Response::json(['id' => $checkout_session->id]);

    }

    public function cancel()
    {
        return view('error');
    }
    public function success(Request $request)
    {
        $stripe = new \Stripe\StripeClient(
            env('STRIPE_SK')
        );
        $session=$stripe->checkout->sessions->retrieve(
            $request->get('session_id'),
            []
        );
        $subscription=$stripe->subscriptions->retrieve(
            $session->subscription,
            []
        );
        $package=Package::findOrFail($session->metadata->package_id);
        if($subscription->items->data[0]->plan->interval=='month' && $subscription->items->data[0]->plan->interval_count==1){
            $price=$package->price;
        }
        if($subscription->items->data[0]->plan->interval=='month' && $subscription->items->data[0]->plan->interval_count==6){
            $price=$package->price_six;
        }
        if($subscription->items->data[0]->plan->interval=='year'){
            $price=$package->price_year;
        }
//        else{
//            $price=$package->price;
//        }

        $pkg=[
            'id'=>$package->id,
            'name'=>$package->name,
            'price'=>$price,
            'thumbnail'=>$package->thumbnail,
            'detail'=>$package->detail,
            'payment_by'=>'Stripe'
        ];
        return view('success',compact('pkg'));
    }
    public function payWithPayPal(Request $request){
        dd($request->all());
        $request->validate([
            'package_id'=>'required',
//            'interval'=>'required',
//            'plan'=>'required'
        ]);

        $package=Package::findOrFail($request->input('package_id'));
////Only annual
//        if($request->input('plan')=='month' && $request->input('interval')==1){
//            $subscription= PayPalController::subscribeNow($package->paypal_plan_id,env('APP_URL').'/user/process-subscription?success=true&package_id='.$package->id,url()->previous());
//        }
//        else if($request->input('plan')=='month' && $request->input('interval')==6){
//            $subscription= PayPalController::subscribeNow($package->paypal_plan_six_id,env('APP_URL').'/user/process-subscription?success=true&package_id='.$package->id,url()->previous());
//
//
//        }
//        else if($request->input('plan')=='year' && $request->input('interval')==1){
//            $subscription=PayPalController::subscribeNow($package->paypal_plan_year_id,env('APP_URL').'/user/process-subscription?success=true&package_id='.$package->id,url()->previous());
//        }
////
///
        $subscription=PayPalController::subscribeNow($package->paypal_plan_year_id,env('APP_URL').'/user/process-subscription?success=true&package_id='.$package->id,env('APP_URL'));
        $alreadySubscribed=auth()->guard('user')->user()->packages->where('id',$package->id)->first();

        if($alreadySubscribed && $alreadySubscribed->pivot->subscribed_status!= 'Expired'){
            return redirect()->back()->with('danger','Already Subscribed!');
        }
        try {
            $link=PayPalController::getApprovalLink($subscription);
            try {
                PayPalAgreement::create([
                    'user_id'=>auth()->guard('user')->user()->id,
                    'package_id'=>$package->id,
                    'agreement_id'=>$subscription->id
                ]);
            }
            catch (\Exception $ex){

            }
            return redirect($link);
        }
        catch (\Exception $ex){

        }
    }

    public function paypalSuccess(Request $request){
        if (!(empty($request->input('success')) && $request->input('success') == 'true')) {
            $token = $request->input('token');
            $package=Package::findOrFail($request->get('package_id'));
            $subscription=PayPalController::subscriptionDetails($request->get('subscription_id'));
            $plan=PayPalController::planDetails($subscription->plan_id);
            if($subscription->status=='ACTIVE'){
                $billingAgreement = PayPalAgreement::where('agreement_id',$subscription->id)->first();

                $user = User::find($billingAgreement->user_id);
                $subscribed_at = new Carbon( $subscription->billing_info->last_payment->time);
                $expired_at = new Carbon( $subscription->billing_info->next_billing_time);
                $plan = PayPalController::planDetails( $subscription->plan_id);
                try {

                    $user->packages()->attach($billingAgreement->package_id,
                        [
                            'subscribed_at' => $subscribed_at,
                            'expired_at' => $expired_at,
                            'subscribed_status' => ucwords(strtolower($subscription->status)),
                            'renewal_status' => true,
                            'billing_agreement_id' =>  $subscription->id,
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

                    $subPackage->subscribed_status = ucwords(strtolower( $subscription->status));
                    $subPackage->billing_agreement_id =  $subscription->id;
                    $subPackage->frequency = $plan->billing_cycles[0]->frequency->interval_unit;
                    $subPackage->interval_count = $plan->billing_cycles[0]->frequency->interval_count;
                    $subPackage->payment_by = 'PayPal';
                    $subPackage->subscribed_at = $subscribed_at;
                    $subPackage->expired_at = $expired_at;
                    $subPackage->subscription_id = null;
                    if (ucwords(strtolower( $subscription->status)) == 'Active') {
                        $subPackage->renewal_status = true;
                    } else {
                        $subPackage->renewal_status = false;
                    }
                    $subPackage->updated_at = Carbon::now();
                    $subPackage->save();

                }
            }


            if($plan->billing_cycles[0]->frequency->interval_unit=='MONTH' && $plan->billing_cycles[0]->frequency->interval_count==1){
                $price=$package->price;
            }
            if($plan->billing_cycles[0]->frequency->interval_unit=='MONTH' && $plan->billing_cycles[0]->frequency->interval_count==6){
                $price=$package->price_six;
            }
            if($plan->billing_cycles[0]->frequency->interval_unit=='YEAR' && $plan->billing_cycles[0]->frequency->interval_count==1){
                $price=$package->price_year;
            }

            $pkg=[
                'id'=>$package->id,
                'name'=>$package->name,
                'price'=>$price,
                'thumbnail'=>$package->thumbnail,
                'detail'=>$package->detail,
                'payment_by'=>'Stripe'
            ];
            return view('success',compact('pkg'));
        } else {
            return redirect()->route('packages');
        }
    }

}
