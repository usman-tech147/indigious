@extends('layouts.app')
@section('title','Change Password')

@section('content')
    @include('partials.banner')
    <!--// Main Content \\-->
    <div class="lifestyle-main-content">

        <!--// Main Section \\-->
        <div class="lifestyle-main-section">
            <div class="container">
                <div class="row">
                    @include('users.partials.side-bar')
                    <div class="col-md-9">
                        <div class="lifestyle-wrapper">
                            <div class="lifestyle-profile-info">
                                <h2 class="form-title">Update Your Password</h2>
                                <form action="{{route('user.change-password')}}" method="post" id="user-change-password-form">
                                    @csrf
                                    @method('put')
                                    <div class="row justify-content-center">
                                        <div class="col-6">
                                            @include('partials.flash-message')
                                        </div>
                                    </div>
                                    <ul class="row">
                                        <li>
                                            <label>Old Password</label>
                                            <input type="password" name="password" class="form-control" placeholder="Type here...">
                                            @error('password')
                                            <label class="error error-fade" >{{$message}}</label>
                                            @enderror
                                        </li>
                                        <li>
                                            <label>New Password</label>
                                            <input type="password" name="new_password" id="new_password" class="form-control" placeholder="Type here...">
                                            @error('new_password')
                                            <label class="error error-fade">{{$message}}</label>
                                            @enderror
                                        </li>
                                        <li>
                                            <label>Confirm New Password</label>
                                            <input type="password" name="confirm_new_password" class="form-control" placeholder="Type here...">
                                            @error('confirm_new_password')
                                            <label class="error error-fade">{{$message}}</label>
                                            @enderror
                                        </li>
                                        <li class="full"><input type="submit" class="submit" value="Update"></li>
                                    </ul>
                                </form>
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
