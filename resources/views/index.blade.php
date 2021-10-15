@extends('layouts.app')
@section('title','Home')

@section('content')

    <!-- Banner -->
    <div class="lifestyle-banner-wrap">
        <div class="lifestyle-table">
            <div class="lifestyle-table-cell">
                <div class="container">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="lifestyle-table">
                                <div class="lifestyle-table-cell">
                                    <div class="lifestyle-baner-text">
                                        <h1>Welcome to the <span>Indigenous Lifestyle</span></h1>
                                        <p>An online, interactive, learning space, for all levels and ages.</p>
                                        <a href="{{route('contact-us')}}" class="banner-btn">Contact Us</a>
                                        <a href="{{route('packages')}}" class="banner-btn">View All Packages</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6"><figure class="banner-img"><img src="{{asset('images/about-img.png')}}" alt=""></figure></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Banner -->

    <!--// Main Content \\-->
    <div class="lifestyle-main-content">

        <!--// Main Section \\-->
        <div class="lifestyle-main-section about-textfull">
            <div class="container">
                <div class="row">
                    <div class="col-md-7 fadeLeft" data-t-show="3">
                        <div class="about-text">
                            <h2>ABOUT <span>indigenous</span> lifestyle</h2>
{{--                            <h3>Long established fact</h3>--}}
                            <p>IndigenousLifestyle is a team, created to bring the very best lifestyle to everyone, looking to join us on our journey. Our goal is to bring you the greatest training, coaching, and healthy tips from some of the leading experts, in their fields.</p>
                            <p>Follow us and be apart of the team, to begin self improvement.</p>
                            <a href="{{route('about-us')}}" class="banner-btn">Read More</a>
                        </div>
                    </div>
                    <div class="col-md-5 fadeRight" data-t-show="3"><img src="{{asset('images/about-img1.jpg')}}" class="about-img" alt=""></div>
                </div>
            </div>
        </div>
        <!--// Main Section \\-->

        <!--// Main Section \\-->
        <div class="lifestyle-main-section video-sectionfull">
            <span class="transparent"></span>
            <div class="container">
                <div class="row">
                    <div class="col-md-12">
                        <div class="section-title white fadeDown" data-t-show="3">
                            <h2>ABOUT <span>Indigenous</span> lifestyle</h2>
                            <p>Watch our “about us” video to learn more about IndigenousLifestyle!</p>
                        </div>
                        <div class="video-section fadeUp" data-t-show="5">
                            <video poster="images/poster1.jpg" playsinline controls="" preload="auto" src="{{asset('images/fight.mp4')}}"><source type="video/mp4" src="{{asset('images/fight.mp4')}}"></video>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!--// Main Section \\-->

        <!--// Main Section \\-->
        <div class="lifestyle-main-section video-packagefull">
            <div class="container">
                <div class="row">
                    <div class="col-md-12">
                        <div class="section-title fadeDown" data-t-show="4" style="margin: 0px 0px 70px;">
                            <h2><span>Our</span> Packages</h2>
                            <p>Check out all of our packages, instructed by some of the best coaches and physiotherapist in all of Canada.</p>
                        </div>
                        <div class="video-package">
                            <ul>
                                @foreach($packages as $package)
                                    <x-package :package="$package" />
                                @endforeach
                            </ul>
                        </div>
                        <a href="{{route('packages')}}" class="view-package">View All Packages <i class="fa fa-long-arrow-right"></i></a>
                    </div>
                </div>
            </div>
        </div>
        <!--// Main Section \\-->

    </div>
    <!--// Main Content \\-->
    @include('partials.payment-modal')

@endsection
@section('script')
    <script>
        $('.make-active').hover(function (){
            $(this).addClass('active')
        },
        function (){
            $(this).removeClass('active')
        });
    </script>
@endsection