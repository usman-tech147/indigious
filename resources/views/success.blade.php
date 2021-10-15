
@extends('layouts.app')
@section('title','Subscription Successful')

@section('content')
    @include('partials.banner')
    <!--// Main Content \\-->
    <div class="lifestyle-main-content">

        <!--// Main Section \\-->
        <div class="lifestyle-main-section">
            <div class="container">
                <div class="row">
                    <div class="col-md-12">
                        <div class="section-title">
                            <h2>Subscription <span>Successful</span></h2>
                            <div class="video-package">
                                <ul class="row justify-content-center">
                                    <li>
                                        <div class="video-package-text">
                                            <figure><img src="{{asset('uploads/package/'.$pkg['thumbnail'])}}" alt=""><span>${{$pkg['price']}}</span></figure>
                                            <section>
                                                <h2>{{$pkg['name']}}</h2>
                                                <ul>
                                                    <li>{{substr($pkg['detail'],0,100)}}@if(strlen($pkg['detail'])>100)<span id="dots1">...</span><span style="display: none" id="more1">{{substr($pkg['detail'],100)}}</span>
                                                        <button onclick="myFunction({{1}})" style="background: transparent;font-weight: bold" id="myBtn1">Read more</button>@endif</li>
                                                </ul>
                                                @if(auth()->guard('user')->check())
                                                    <a href="{{route('user.subscribed-package',[$pkg['id']])}}" class="price-cart">View in dashboard</a>
                                                @endif
                                            </section>
                                        </div>
                                    </li>
                                </ul>
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
