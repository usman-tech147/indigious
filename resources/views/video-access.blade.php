@extends('layouts.app')
@section('title','Video Access')

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
                            <h2>Video <span>Access</span></h2>
                            <p>It is a long established fact that a reader will be distracted by the readable content of a page when looking at its layout.</p>
                        </div>
                        <div class="clearfix"></div>
                        <div class="signup-form">
                            <div class="row justify-content-center">
                                <div class="col-6">
                                    @include('partials.flash-message')
                                </div>
                            </div>
                            <form action="{{route('video-access',[$username])}}" method="post" id="video-form">
                                @csrf
                                <ul>
                                    <li class="full">
                                        <label>Enter Your Password</label>
                                        <input type="password" name="password" class="form-control" placeholder="Type here...">
                                        <!-- <span><small class="red"></small><small class="yellow"></small><small class="green"></small></span> -->
                                        @error('password')
                                        <label class="error">{{$message}}</label>
                                        @enderror
                                    </li>
                                    <li class="center full"><input type="submit" class="submit" value="Submit"></li>
                                </ul>
                            </form>
                            <div class="clearfix"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!--// Main Section \\-->

    </div>
    <!--// Main Content \\-->

@endsection
@section('script')
    <script>
        $('#video-form').validate({
            rules:{
                password:{
                    required: true
                }
            },
            messages:{
                 password:{
                required: "Password is required."
                }
            },
            submitHandler: function (form){
                form.submit();
            }
        })
    </script>
@endsection