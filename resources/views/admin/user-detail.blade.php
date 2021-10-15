@extends('admin.layouts.app')
@section('title','User Details')

@section('content')
    <div class="app-main__inner">
        <div class="tabs-animation">
            <div class="row">
                <div class="col-md-12">
                    <div class="card-header-tab card-header">
                        <div class="card-header-title font-size-lg text-capitalize font-weight-normal"><i class="pe-7s-server mr-3 text-muted opacity-6" style="font-size: 35px; color: #E43D4E !important;"> </i>User Details</div>
                    </div>
                    <div class="main-card mb-3 card">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="table-responsive">
                                        <table class="table table-detail table-hover table-striped table-bordered table-cursor" style="width: 100%;">
                                            <tr>
                                                <th>Username</th>
                                                <td>{{$user->name}}</td>
                                            </tr>
                                            <tr>
                                                <th>Email</th>
                                                <td>{{$user->email}}</td>
                                            </tr>
                                            <tr>
                                                <th>Subscribed Packages</th>
                                                <td>{{$user->packages->where('pivot.subscribed_status','!=','Expired')->count()}}</td>
                                            </tr>
                                            <tr>
                                                <th>Phone Number</th>
                                                <td>{{$user->phone_no}}</td>
                                            </tr>
                                            <tr>
                                                <th>Community/Institution Name</th>
                                                <td>{{$user->institution}}</td>
                                            </tr>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="card-header-tab card-header">
                        <div class="card-header-title font-size-lg text-capitalize font-weight-normal"><i class="pe-7s-server mr-3 text-muted opacity-6" style="font-size: 35px; color: #E43D4E !important;"> </i>Payments List</div>
                    </div>
                    <div class="main-card mb-3 card">
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-hover table-striped table-bordered table-cursor" style="width: 100%;" id="package-list">
                                    <thead>
                                    <tr>
                                        <th>Package Name</th>
                                        <th>Subscription Type</th>
                                        <th>Amount</th>
                                        <th>Subscription Date</th>
                                        <th>Next Due Payment On</th>
                                        <th>Payment By</th>
                                        <th>Subscription Id</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    {{--                                            ->where('subscription_id',$payment->payment_id)->first()--}}

                                    @forelse($user->payments->sortByDesc('id') as $payment)
                                        @php
                        if($payment->payment_by=='Stripe')
                        {
                            $package=$user->packages->where('pivot.subscription_id',$payment->payment_id)->first();
                            if(!empty($package)){

                                $subscribed_date=new \Carbon\Carbon($package->pivot->subscribed_at);
                            if($package->pivot->subscribed_status=='Active'){
                                $expired_date=new \Carbon\Carbon($package->pivot->expired_at);
                                $expired_date=$expired_date->format('d-M-Y');
                                if($package->pivot->renewal_status==1 && $package->pivot->subscribed_status=='Active'){
                                    $expired_date='Auto Subscription Off';
                                }
                            }
                            else if($package->pivot->subscribed_status=='Expired'){
                                $expired_date='Expired';
                            }
                            }else if(empty($package)) {
                                $package=\App\Models\Package::find($payment->package_id);
                                $subscribed_date = new \Carbon\Carbon($payment->created_at);
                                $expired_date = 'Expired';
                            }
                        }
                        else if($payment->payment_by=='Paypal'){
                            $package=$user->packages->where('pivot.billing_agreement_id',$payment->payment_id)->first();
                            if(!empty($package)){
                            $subscribed_date=new \Carbon\Carbon($package->pivot->subscribed_at);
                            $expired_date=new \Carbon\Carbon($package->pivot->expired_at);
                            if($package->pivot->subscribed_status=='Active'){
                                $expired_date=$expired_date->format('d-M-Y');

                            }
                            else if($package->pivot->subscribed_status=='Suspended'){
                                $expired_date='Auto Subscription Off';

                            }
                            else if($package->pivot->subscribed_status=='Cancelled'){
                                $expired_date='Auto Subscription Off';

                            }
                            else if($package->pivot->subscribed_status=='Expired'){

                                $expired_date='Expired';
                            }
                            }
                            else{
                                  $package=\App\Models\Package::find($payment->package_id);
                                $subscribed_date=new \Carbon\Carbon($payment->created_at);
                                $expired_date='Expired';
                                }
                        }
                        else if($payment->payment_by=='Manual'){
                            $package=$user->packages->where('pivot.subscription_id',$payment->payment_id)->first();
                            if(!empty($package)){
                            $subscribed_date=new \Carbon\Carbon($package->pivot->subscribed_at);
                            $expired_date=new \Carbon\Carbon($package->pivot->expired_at);
                            if($package->pivot->subscribed_status=='Active'){
                                $expired_date=$expired_date->format('d-M-Y');

                            }
                            else if($package->pivot->subscribed_status=='Expired'){

                                $expired_date='Manually';
                            }
                            }
                            else{
                                  $package=\App\Models\Package::find($payment->package_id);
                                $subscribed_date=new \Carbon\Carbon($payment->created_at);
                                $expired_date='Manually';
                                }
                        }
                                        @endphp
                                        <tr>
                                            <td><a class="text-dark" href="/admin/package-details/{{$package->id}}">{{$package->name}}</a></td>
                                            <td>@if($package->pivot!=null )
                                                    @if($package->pivot->frequency=='MONTH' && $package->pivot->interval_count==1)
                                                        {{'Monthly'}}
                                                    @elseif($package->pivot->frequency=='YEAR' && $package->pivot->interval_count==1)
                                                        {{'Yearly'}}
                                                    @elseif($package->pivot->frequency=='MONTH' && $package->pivot->interval_count==6)
                                                        {{'Every Six Month'}}
                                                    @elseif($package->pivot->frequency==null && $package->pivot->interval_count==null && $package->pivot->payment_by=='Manual')
                                                        {{'Manual'}}
                                                    @endif
                                                @else{{'Expired'}} @endif</td>
                                            <td>${{$payment->subtotal}}</td>
                                            <td>{{$subscribed_date->format('d-M-Y')}}</td>
                                            <td>{{$expired_date}}</td>
                                            <td>{{$payment->payment_by}}</td>
                                            <td>{{$payment->payment_id}}</td>
                                        </tr>
                                        @empty
                                        <tr>
                                        <td colspan="7" class="text-center">No Data Available</td>
                                        </tr>
                                    @endforelse
                                    </tbody>

                                </table>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
@endsection
