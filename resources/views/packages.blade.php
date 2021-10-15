@extends('layouts.app')
@section('title','Packages')

@section('content')
    @include('partials.banner')
    <!--// Main Content \\-->
    <div class="lifestyle-main-content">

        <!--// Main Section \\-->
        <div class="lifestyle-main-section">
            <div class="container">
                <div class="row">
                    <div class="col-md-12">
                        <div class="video-package">
                            @include('partials.flash-message')
                            <ul class="row">
                                @foreach($packages as $package)
                                    <x-package :package="$package" />
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
                    <div class="row justify-content-end">
                {{$packages->links()}}
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