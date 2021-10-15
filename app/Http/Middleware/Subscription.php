<?php

namespace App\Http\Middleware;

use App\Http\Controllers\MailController;
use App\Http\Controllers\PayPalController;
use App\Models\ManualSubscriptionRequest;
use App\Models\User;
use Carbon\Carbon;
use Closure;
use Illuminate\Http\Request;
use PayPal\Api\Agreement;
use PayPal\Api\AgreementStateDescriptor;

class Subscription
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $users=User::with('packages')->get();
        $agreementStateDescriptor = new AgreementStateDescriptor();
        $agreementStateDescriptor->setNote("Cancel the agreement");

        foreach ($users as $user){
            foreach ($user->packages as $package){

                if($package->pivot->subscribed_status!='Expired') {
                $date=new Carbon($package->pivot->expired_at);
                if($package->pivot->payment_by=='PayPal') {
//                        $date= \Carbon\Carbon::createFromFormat('Y-m-d', $date->format('Y-m-d'))
//                            ->endOfDay();
                    $date=$date->endOfDay();
                }
                if($package->pivot->payment_by=='Stripe') {
                    $date->addMinutes(5);
                }
                if($date->isPast()>=1){
                    if($package->pivot->payment_by=='PayPal') {
//                        if($package->pivot->subscribed_status=='Suspended') {
//                                $agreement=\PayPal\Api\Agreement::get($package->pivot->billing_agreement_id,\App\Http\Controllers\PayPalController::getApiContext());

                            try {
                                PayPalController::cancelSubscription($package->pivot->billing_agreement_id,'User Subscription Expired.');
//                                    $agreement->cancel($agreementStateDescriptor,PayPalController::getApiContext());
                            }
                            catch (\Exception $e){

                            }
//                        }
                    }

                        $package->pivot->subscribed_status = 'Expired';
                        ManualSubscriptionRequest::where('user_id',$user->id)->where('package_id',$package->id)->delete();

                        $package->pivot->error_message='Your subscription has been expired because auto renewal was turned off.';
                        if($package->pivot->payment_by=='Manual')
                        {
                            $package->pivot->error_message='Your subscription has been expired because you have bought subscription manually.';
                        }
                        MailController::sendSubscriptionExpired($package->name,$user->email);
                        $package->pivot->save();
                    }
                }

            }
        }
        return $next($request);
    }
}
