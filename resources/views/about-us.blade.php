@extends('layouts.app')
@section('title','About us')
    
@section('content')
    @include('partials.banner')
    <!--// Main Content \\-->
    <div class="lifestyle-main-content">

        <!--// Main Section \\-->
        <div class="lifestyle-main-section">
            <div class="container">
                <div class="row">
                    <div class="col-md-6">
                        <div class="about-text" style="padding: 33px 0px 0px;">
                            <h2>ABOUT <span>Indigenous</span> Lifestyle</h2>
{{--                            <h3>Long established fact</h3>--}}
                            <p>IndigenousLifestyle is a team, created to bring the very best lifestyle to everyone, looking to join us on our journey. Our goal is to bring you the greatest training, coaching, and healthy tips from some of the leading experts, in their fields.</p>
                            <p>Follow us and be apart of the team, to begin self improvement.</p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="about-img-wrap"><img src="/images/about-img1.png" alt=""></div>
                    </div>
                </div>
            </div>
        </div>
        <!--// Main Section \\-->

    </div>
    <!--// Main Content \\-->

@endsection
