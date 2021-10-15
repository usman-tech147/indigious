@extends('layouts.app')
@section('title','Manage Plan')

@section('content')

    @include('partials.banner')
    <!--// Main Content \\-->
    <div class="lifestyle-main-content">
        <!--// Main Section \\-->
        <div class="lifestyle-main-section">
            <div class="container">
                <div class="row">
                    @include('users.partials.side-bar')
                    <div class="col-md-9">
                        <div class="lifestyle-wrapper">
                                <div class="row">
                                    <div class="col-md-12">
                                        @include('partials.flash-message')
                                        <div class="video-package screenhalf">
{{--                                            --}}
                                            <ul class="row">
                                                @forelse($user->packages as $package)
                                                    @if($package->pivot->payment_by=='Stripe' && $package->pivot->subscribed_status!='Expired')
                                                        <li class="col-md-4">
                                                        <div class="video-package-text">
                                                            <figure><img src="{{asset('uploads/package/'.$package->thumbnail)}}" alt=""></figure>
                                                            <div class="prices-wrap">
                                                                <span>
                                                                        $@if($package->pivot->frequency=='YEAR' && $package->pivot->interval_count==1){{$package->price_year}}
                                                                        <small>Year</small>
                                                                        @elseif($package->pivot->frequency=='MONTH' && $package->pivot->interval_count==1){{$package->price}}
                                                                            <small>Month</small>
                                                                        @elseif($package->pivot->frequency=='MONTH' && $package->pivot->interval_count==6){{$package->price_six}}
                                                                            <small>6 Months</small> @endif
                                                                </span>
                                                            </div>
                                                            <section>
                                                                <h2>{{$package->name}}</h2>
                                                                <ul>
                                                                    @if($package->pivot->subscribed_status!='Past_due' && $package->pivot->subscribed_status!='Unpaid')
                                                                    <li><strong>Subscribed On: </strong>{{ date('d-M-Y',strtotime($package->pivot->subscribed_at))}}</li>
                                                                    <li><strong>Valid Till: </strong>{{ date('d-M-Y',strtotime($package->pivot->expired_at))}}</li>
                                                                    @if($package->pivot->renewal_status==0)<li><strong>Auto Renew On: </strong>{{ date('d-M-Y',strtotime($package->pivot->expired_at))}}</li>@endif
                                                                    @endif
                                                                        @php
                                                                            $stripe = new \Stripe\StripeClient(
                                                                                env('STRIPE_SK')
                                                                            );
                                                                            $pm=$stripe->paymentMethods->retrieve(
                                                                                $package->pivot->payment_method,
                                                                                []
                                                                            );
                                                                        @endphp
                                                                    <li><strong>Payment Using: </strong><img src="/images/{{$pm['card']['brand'].".png"}}" style="height: 20px;width: auto" alt=""></li>
{{--                                                                    @if($package->pivot->subscribed_status=='Past_due')--}}
{{--                                                                        <li class="text-danger">Payment Failed Please Update Your Card Details</li>--}}
{{--                                                                    <li><strong>Card: </strong> {{"**** **** **** ".$pm['card']['last4']}}</li>--}}
{{--                                                                    <li><button class="btn btn-danger update-payment" data-id="{{$package->id}}">Update Card Details</button></li>--}}
{{--                                                                    @endif--}}
                                                                        @if($package->pivot->renewal_status==0)<li><strong>Auto Renew Turned On</strong></li>@endif
                                                                        @if($package->pivot->renewal_status==1)<li><strong>Auto Renew Turned Off</strong></li>@endif


                                                                </ul>
                                                               <div class="text-center">
                                                                   @if($package->pivot->subscribed_status=='Expired' && $package->pivot->subscribed_status=='Past_due')
                                                                       <button type="button" class="price-cart"><i class="fa fa-remove"></i> Expired</button>
                                                                   @else
                                                                       @if($package->pivot->renewal_status==0) <button type="button" class="price-cart" onclick="turnOffAutoRenewal({{$package->id}})">Turn Auto-Renew Off</button>@endif
                                                                       @if($package->pivot->renewal_status==1) <button type="button" class="price-cart" onclick="turnOnAutoRenewal({{$package->id}})">Turn Auto-Renew On</button>@endif
                                                                   @endif
                                                               </div>

                                                            </section>
                                                        </div>
                                                    </li>
                                                        @elseif($package->pivot->payment_by=='Manual' && $package->pivot->subscribed_status!='Expired')
                                                            <li class="col-md-4">
                                                                <div class="video-package-text">
                                                                    <figure><img src="{{asset('uploads/package/'.$package->thumbnail)}}" alt=""></figure>
                                                                    <div class="prices-wrap">
                                                                    <span>
                                                                        @if(\App\Models\Payment::where('payment_id',$package->pivot->subscription_id)->first())
                                                                            {{'$'.\App\Models\Payment::where('payment_id',$package->pivot->subscription_id)->first()->total_amount}}
                                                                        @else
                                                                            {{'Free'}}
                                                                        @endif

                                                                        <small>
                                                                            @php
                                                                                $sDate=new \Carbon\Carbon($package->pivot->subscribed_at);
                                                                                $date=new \Carbon\Carbon($package->pivot->expired_at);
                                                                                    $diff=$date->diffAsCarbonInterval($sDate);
                                                                                        if($diff->format('%y')>0){
                                                                                            $t='';
                                                                                            if($diff->format('%y')>1){
                                                                                                $t='s';
                                                                                            }
                                                                                                echo $diff->format('%y')." Year$t ";
                                                                                        }
                                                                                         if($diff->format('%m')>0){
                                                                                             $t='';
                                                                                            if($diff->format('%m')>1){
                                                                                                $t='s';
                                                                                            }
                                                                                                echo $diff->format('%m')." Month$t ";
                                                                                        }
                                                                                     if($diff->format('%d')>0){
                                                                                         $t='';
                                                                                         if($diff->format('%d')>1){
                                                                                                $t='s';
                                                                                            }
                                                                                                echo $diff->format('%d')." Day$t";
                                                                                        }
                                                                            @endphp
                                                                        </small>
                                                                           </span>
                                                                    </div>
                                                                    <section>
                                                                        <h2>{{$package->name}}</h2>
                                                                        <ul>
                                                                            @if($package->pivot->subscribed_status!='Past_due' && $package->pivot->subscribed_status!='Unpaid')
                                                                                <li><strong>Subscribed On: </strong>{{ date('d-M-Y',strtotime($package->pivot->subscribed_at))}}</li>
                                                                                <li><strong>Valid Till: </strong>{{ date('d-M-Y',strtotime($package->pivot->expired_at))}}</li>
                                                                            @endif
                                                                            <li><strong>Payment: </strong>Manual</li>
                                                                        </ul>
                                                                       <div class="text-center">
                                                                           @if($package->pivot->subscribed_status=='Expired' && $package->pivot->subscribed_status=='Past_due')
                                                                               <button type="button" class="price-cart"><i class="fa fa-remove"></i> Expired</button>
                                                                           @else
                                                                               <button type="button" class="price-cart">Subscribed</button>

                                                                           @endif
                                                                       </div>
                                                                    </section>
                                                                </div>
                                                            </li>
                                                        @elseif($package->pivot->payment_by=='PayPal' && $package->pivot->subscribed_status!='Expired')
                                                        <li class="col-md-4">
                                                            <div class="video-package-text">

                                                                <figure><img src="{{asset('uploads/package/'.$package->thumbnail)}}" alt=""></figure>
                                                            <div class="prices-wrap">
                                                                    <span>
                                                                        $@if($package->pivot->frequency=='YEAR' && $package->pivot->interval_count==1){{$package->price_year}}
                                                                        <small>Year</small>
                                                                        @elseif($package->pivot->frequency=='MONTH' && $package->pivot->interval_count==1){{$package->price}}
                                                                            <small>Month</small>
                                                                        @elseif($package->pivot->frequency=='MONTH' && $package->pivot->interval_count==6){{$package->price_six}}
                                                                            <small>6 Months</small> @endif</span>
                                                            </div>
                                                                <section>
                                                                    <h2>{{$package->name}}</h2>
                                                                    <ul>
                                                                        <li><strong>Subscribed On: </strong>{{ date('d-M-Y',strtotime($package->pivot->subscribed_at))}}</li>
                                                                        <li><strong>Valid Till: </strong>{{ date('d-M-Y',strtotime($package->pivot->expired_at))}}</li>
                                                                        @if($package->pivot->renewal_status=='1')<li><strong>Auto Renew On: </strong> {{ date('d-M-Y',strtotime($package->pivot->expired_at))}}</li>@endif
                                                                        <li><strong>Payment Using: </strong><img src="/images/paypal.png" style="height: 20px;width: auto" alt=""></li>
                                                                        @if($package->pivot->renewal_status==1)<li><strong>Auto Renew Turned On</strong></li>@endif
                                                                        @if($package->pivot->renewal_status==0)<li><strong>Auto Renew Turned Off</strong></li>@endif
                                                                    </ul>
                                                                 <div class="text-center">
                                                                     @if($package->pivot->subscribed_status!='Expired' && $package->pivot->subscribed_status!='Cancelled' && $package->pivot->error_message!='Waiting for Payment to complete.')
                                                                         @if($package->pivot->renewal_status=='1') <button type="button" class="price-cart" onclick="suspendBilling({{$package->id}})">Turn Auto-Renew Off</button>@endif
                                                                         @if($package->pivot->renewal_status=='0') <button type="button" class="price-cart" onclick="reactiveBilling({{$package->id}})">Turn Auto-Renew On</button>@endif
                                                                     @elseif($package->pivot->subscribed_status=='Cancelled')
                                                                         <button type="button" class="price-cart"><i class="fa fa-remove"></i> Cancelled</button>
                                                                     @else
                                                                         <button type="button" class="price-cart"><i class="fa fa-cash"></i> Waiting for payment</button>
                                                                     @endif
                                                                 </div>
                                                                </section>
                                                            </div>
                                                        </li>
                                                    @elseif($package->pivot->subscribed_status=='Expired')
                                                        <li class="col-md-4">
                                                            <div class="video-package-text">
                                                                <figure><img src="{{asset('uploads/package/'.$package->thumbnail)}}" alt=""></figure>
                                                                <div class="prices-wrap">
{{--                                                                    <span>${{$package->price}}<small>Month</small> </span>--}}
{{--                                                                    <span>${{$package->price_six}}<small>Six Months</small> </span>--}}
                                                                    <span>
                                                                        ${{$package->price_year}}<small>Year</small> </span>
                                                                </div>
                                                                <section>
                                                                    <h2>{{$package->name}}</h2>
                                                                    <p class="d-flex justify-content-center"> Expired                                                                     <i  class="fa fa-question" data-toggle="tooltip" data-placement="top" title="{{$package->pivot->error_message}}"></i>
                                                                    </p>
                                                                    <div class="text-center">
                                                                        <button type="button" class="price-cart mt-2" data-toggle="modal" data-target="#paymentModal" data-id="{{$package->id}}">Re-Subscribe</button>
                                                                    </div>
                                                                </section>
                                                            </div>
                                                        </li>
                                                    @endif
                                                @empty
                                                    <div class="col-12 text-center">
                                                        <p class="font-weight-bold">You have not subscribed any package.</p>
                                                    </div>
                                                @endforelse
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!--// Main Section \\-->
    </div>
    <!--// Main Content \\-->
    <form action="{{route('user-subscribed-plans-turn-off-auto')}}" id='turn-off-auto-renewal-form' method="post" style="display:none;"></form>
    <form action="{{route('user-subscribed-plans-turn-on-auto')}}" id='turn-on-auto-renewal-form' method="post" style="display:none;"></form>
    <form action="{{route('user-subscribed-plans-cancel-subscription')}}" id="cancel-subscription-form" method="post" style="display:none;"></form>
    <form action="{{route('user-subscribed-plans-suspend-subscription')}}" id="suspend-subscription-form" method="post" style="display:none;"></form>
    <form action="{{route('user-subscribed-plans-reactive-subscription')}}" id="reactive-subscription-form" method="post" style="display:none;"></form>
    <form action="{{route('user-subscribed-plans-cancel-subscription-paypal')}}" id="cancel-subscription-form-paypal" method="post" style="display:none;"></form>
@include('partials.payment-modal')

@endsection
