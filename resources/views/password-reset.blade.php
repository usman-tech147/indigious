@extends('layouts.app')
@section('title','Reset Password')

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
                            <form action="{{route('password-reset',[$email,'token'=>$code])}}" method="post" id="reset-password-form">
                                @method('PUT')
                                @csrf
                                <ul class="row">
                                    <li class="col-md-12">
                                        <label>Password</label>
                                        <input type="password" name="password" id="password" class="form-control" placeholder="Type here...">
                                    </li>
                                    @error('password')
                                    <p class="error">{{$message}}</p>
                                    @enderror
                                    <li class="col-md-12">
                                        <label>Confirm Password</label>
                                        <input type="password" name="confirm_password" class="form-control" placeholder="Type here...">
                                    </li>
                                    @error('confirm_password')
                                    <p class="error">{{$message}}</p>
                                    @enderror
                                    <li class="center full"><input type="submit" class="submit" value="Change Password"></li>
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
        $('#reset-password-form').validate({
            rules:{
                password:{
                    required: true,
                    minlength:8
                },
                confirm_password:{
                    required: true,
                    equalTo: '#password'
                }
            },
            messages:{
                password:{
                    required: "Password is required.",
                    email: "Password length should be minimum 8 characters."
                },
                confirm_password:{
                    required: "Confirm Password is required.",
                    equalTo: 'Password and confirm password should match.'
                }
            }

        });
    </script>
@endsection
