@extends('layouts.app')
@section('title','Forgot Password')

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
                            <h2>Forgot <span>Password</span></h2>
                        </div>
                        <div class="clearfix"></div>
                        <div class="signup-form">
                            @include('partials.flash-message')
                            <form action="{{route('forgot-password')}}" method="post" id="forgot-password-form">
                                @csrf
                                <ul class="row">
                                    <li class="col-md-12">
                                            <label>Email Address</label>
                                            <input type="email" name="email" class="form-control" placeholder="Type here...">
                                    </li>
                                    @error('email')
                                    <p class="error">{{$message}}</p>
                                    @enderror
                                    <li class="center full"><input type="submit" class="submit" value="Submit"></li>
                                    <li class="full center"><p><a href="{{route('sign-in')}}">Sign In</a></p></li>
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
        $('#forgot-password-form').validate({
            rules:{
                email:{
                    required: true,
                    email: true
                }
            },
            messages:{
                required: "Email is required.",
                email: "Please enter valid email address."
            }
        });
    </script>
@endsection
