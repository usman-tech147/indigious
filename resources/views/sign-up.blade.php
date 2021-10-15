@extends('layouts.app')
@section('title','Sign Up')

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
                        <h2>User <span>Sign Up</span></h2>
                        <p>Sign up today, and get started with all of our amazing packages!</p>
                    </div>
                    <div class="clearfix"></div>
                    <div class="signup-form">
                        <form action="{{route('sign-up')}}" method="post" id="user-signup-form">
                            @csrf
                            <ul class="row">
                                <li>
                                    <label>Full Name</label>
                                    <input type="text" name="name" class="form-control" placeholder="Type here..." value="{{old('name')}}">
                                    @error('name')
                                    <label class="error" id="error">{{$message}}</label>
                                    @enderror
                                </li>
                                <li>
                                    <label>Email Address</label>
                                    <input type="email" name="email" class="form-control" placeholder="Type here..." value="{{old('email')}}">
                                    @error('email')
                                    <label class="error" id="error">{{$message}}</label>
                                    @enderror
                                </li>
                                <li>
                                    <label>Community/Institution Name</label>
                                    <input type="text" name="institution" class="form-control" placeholder="Type here..." value="{{old('institution')}}">
                                    @error('institution')
                                    <label class="error" id="error">{{$message}}</label>
                                    @enderror
                                </li>
                                <li>
                                    <label>Phone Number</label>
                                    <input type="text" name="phone_no" class="form-control" placeholder="Type here..." value="{{old('phone_no')}}">
                                    @error('phone_no')
                                    <label class="error" id="error">{{$message}}</label>
                                    @enderror
                                </li>
                                <li>
                                    <label>Password</label>
                                    <input type="password" name="password" id="password" class="form-control" placeholder="Type here...">
                                    @error('password')
                                    <label class="error" id="error">{{$message}}</label>
                                    @enderror
                                </li>
                                <li>
                                    <label>Confirm Password</label>
                                    <input type="password" name="confirm_password" class="form-control" placeholder="Type here...">
                                    @error('confirm_password')
                                    <label class="error" id="error">{{$message}}</label>
                                    @enderror
                                </li>
                                <li class="center full"><input type="submit" class="submit" value="Signup"></li>
                                <li class="full center"><p>Already have an account? <a href="{{route('sign-in')}}">Sign In</a></p></li>
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
