@extends('layouts.app')
@section('title','Subscribed Packages')

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
                                        <ul class="row">
                                            @php
                                                $i=0;
                                            @endphp
                                            @if($packages->count()>0)
                                            @foreach($packages as $package)
                                                    @php
                                                        $i++;
                                                            $pkg=$user->packages->where('id',$package->id)->first();
                                                            $date=new \Carbon\Carbon($pkg->pivot->expired_at);
                                                            $sDate=new \Carbon\Carbon($pkg->pivot->subscribed_at);
                                                    @endphp
                                                <li class="col-md-4">
                                                    <div class="video-package-text">
                                                        <figure><img src="{{asset('uploads/package/'.$package->thumbnail)}}" alt=""></figure>
                                                        <div class="prices-wrap">
                                                                   @if($pkg->pivot->payment_by=='PayPal' or $pkg->pivot->payment_by=='Stripe' )
                                                                <span>

                                                                        $@if($pkg->pivot->frequency=='YEAR' && $pkg->pivot->interval_count==1){{$package->price_year}}
                                                                        <small>Year</small>
                                                                        @elseif($pkg->pivot->frequency=='MONTH' && $pkg->pivot->interval_count==1){{$package->price}}
                                                                            <small>Month</small>
                                                                        @elseif($pkg->pivot->frequency=='MONTH' && $pkg->pivot->interval_count==6){{$package->price_six}}
                                                                            <small>6 Months</small> @endif
                                                            </span>
                                                                        @else
                                                                <span>


                                                                            @if(\App\Models\Payment::where('payment_id',$pkg->pivot->subscription_id)->first())
                                                                            {{'$'.\App\Models\Payment::where('payment_id',$pkg->pivot->subscription_id)->first()->total_amount}}
                                                                                @else
                                                                                {{'Free'}}
                                                                                @endif
                                                                                <small>@php
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
                                                                                    @endphp</small>
                                                                                                                                            </span>

                                                            @endif
                                                        </div>
                                                        <section>
                                                            <h2>{{$package->name}}</h2>
                                                            <ul>
                                                                <li>{{substr($package->detail,0,100)}}@if(strlen($package->detail)>100)<span id="dots{{$i}}">...</span><span style="display: none" id="more{{$i}}">{{substr($package->detail,100)}}</span>
                                                                    <button onclick="myFunction({{$i}})" style="background: transparent;font-weight: bold" id="myBtn{{$i}}">Read more</button>@endif</li>

                                                                <li>Expiry Date: {{$date->format('d-M-Y')}}</li>
                                                            </ul>
                                                            <div class="text-center">
                                                                <a href="{{route('user.subscribed-package',[$package->id])}}" class="price-cart" type="button" >View Details</a>
                                                            </div>
                                                        </section>
                                                    </div>
                                                </li>
                                            @endforeach
                                            @else
                                                <div class="col-12 text-center">
                                                    <p class="font-weight-bold">You have not subscribed any package.</p>
                                                </div>
                                            @endif
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

@endsection
