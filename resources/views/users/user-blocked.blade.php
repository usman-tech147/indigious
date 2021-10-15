@extends('layouts.app')
@section('title','Blocked')

@section('content')
    @include('partials.banner')
    <!--// Main Content \\-->
    <div class="lifestyle-main-content">

        <!--// Main Section \\-->
        <div class="lifestyle-main-section">
            <div class="container">
                <div class="row align-items-center">
                    <div class="col-md-12">
                        <div class="section-title">
                            <h2>Blocked <i class="fa fa-lock"></i> </h2>
                            <p>You profile has been blocked by admin.</p>
                        </div>
                        <div class="clearfix"></div>
                    </div>
                        <div class="offset-md-4 col-md-4">
                         <a href="{{route('contact-us')}}" class="price-cart">Contact Admin</a>
                        </div>
                </div>
            </div>
        </div>
        <!--// Main Section \\-->

    </div>
    <!--// Main Content \\-->

@endsection
