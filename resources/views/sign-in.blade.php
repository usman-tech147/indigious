@extends('layouts.app')
@section('title','Sign in')

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
                        <h2>User <span>Sign in</span></h2>
                        <p>Sign in and get started with all of our amazing programs!</p>
                    </div>
                    <div class="clearfix"></div>
                    <div class="signup-form">
                        @include('partials.flash-message')
                        <form action="{{route('sign-in')}}" method="post" id="user-signin-form">
                            @csrf
                            <ul>
                                <li>
                                    <label>Email Address</label>
                                    <input type="email" name="email" class="form-control" placeholder="Type here...">
                                </li>
                                <li>
                                    <label>Password</label>
                                    <input type="password" name="password" class="form-control" placeholder="Type here...">
                                </li>
                                <li class="full">
                                    <a class="pull-right forgot-password" href="{{route('forgot-password')}}" style="font-family: 'Oswald', sans-serif;">Forgot Password?</a>
                                </li>
                                <li class="center full"><input type="submit" class="submit" value="Signin"></li>
                                <li class="full center"><p>Don't already have an account? <a href="{{route('sign-up')}}">Sign Up</a></p></li>
                            </ul>

                        <div class="clearfix"></div>
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
