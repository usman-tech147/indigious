@extends('layouts.app')
@section('title','Dashboard')

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
                            <h2 class="form-title">Update Your Profile</h2>
                            <form action="{{route('user')}}" method="post" id="user-profile">
                                @csrf
                                @method('put')
                                <div class="row justify-content-center">
                                    <div class="col-6">
                                    @include('partials.flash-message')
                                    </div>
                                </div>
                                <ul class="row">
                                    <li>
                                        <label>Name</label>
                                        <input type="text" name="name" class="form-control" placeholder="Type here..." value="{{$user->name}}">
                                        @error('name')
                                        <label class="error error-fade">{{$message}}</label>
                                        @enderror
                                    </li>
                                    <li>
                                        <label>Email Address</label>
                                        <input type="email" name="email" style="background-color: #dbd9d9;cursor: not-allowed !important" class="form-control" placeholder="Type here..." value="{{$user->email}}" disabled >
                                        @error('email')
                                        <label class="error error-fade">{{$message}}</label>
                                        @enderror
                                    </li>
                                    <li>
                                        <label>Community/Institution Name</label>
                                        <input type="text" name="institution" class="form-control" placeholder="Type here..." value="{{$user->institution}}">
                                        @error('institution')
                                        <label class="error error-fade">{{$message}}</label>
                                        @enderror
                                    </li>
                                    <li>
                                        <label>Phone Number</label>
                                        <input type="text" name="phone_no" class="form-control" placeholder="Type here..." value="{{$user->phone_no}}">
                                        @error('phone_no')
                                        <label class="error error-fade" >{{$message}}</label>
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
