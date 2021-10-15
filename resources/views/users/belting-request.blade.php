@extends('layouts.app')
@section('title','Belting Request')

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
                                <h2 class="form-title">Submit a Belting Evaluation Request</h2>
                                <form action="{{route('user.belting-request')}}" method="POST" id="user-belting-request-form">
                                    @csrf
                                    <div class="row justify-content-center">
                                        <div class="col-6">
                                            @include('partials.flash-message')
                                        </div>
                                    </div>
                                    <ul class="row">
                                        <li>
                                            <label for="">Name</label>
                                            <input type="text" name="name" placeholder="Type here..." class="form-control" value="{{old('name')}}">
                                            @error('name')
                                            <label class="error error-fade" >{{$message}}</label>
                                            @enderror
                                        </li>
                                        <li>
                                            <label for="">Email</label>
                                            <input type="text" name='email' placeholder="Type here..." class="form-control" value="{{old('email')}}">
                                            @error('email')
                                            <label class="error error-fade">{{$message}}</label>
                                            @enderror
                                        </li>
                                        <li>
                                            <label for="">Phone</label>
                                            <input type="text" name="phone_no" placeholder="Type here..." class="form-control" value="{{old('phone_no')}}">
                                            @error('phone_no')
                                            <label class="error error-fade">{{$message}}</label>
                                            @enderror
                                        </li>
                                        <li class="full">
                                            <label>Message</label>
                                            <textarea class="form-control" name="message" placeholder="Type here...">{{old('message')}}</textarea>
                                            @error('message')
                                            <label class="error error-fade">{{$message}}</label>
                                            @enderror
                                        </li>
                                        <li class="full"><input type="submit" class="submit" value="Send"></li>
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
