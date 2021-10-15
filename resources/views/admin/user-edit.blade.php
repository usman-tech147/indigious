@extends('admin.layouts.app')
@section('title','Edit User')

@section('content')
    <div class="app-main__inner">
        <div class="tabs-animation">
            <div class="row">
                <div class="col-md-12">
                    <div class="card-header-tab card-header">
                        <div class="card-header-title font-size-lg text-capitalize font-weight-normal"><i class="pe-7s-user mr-3 text-muted opacity-6" style="font-size: 35px; color: #E43D4E !important;"> </i>Edit User Details</div>

                    </div>
                    <div class="main-card mb-3 card">
                        <div class="card-body">
                            @include('partials.flash-message')

                            <form action="{{route('admin.edit-user',$user->id)}}" method="post" id="user-edit-form">
                                @csrf
                                @method('put')
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Username</label>
                                            <input type="text" name="name" value="{{$user->name}}" class="form-control" placeholder="">
                                            @error('name')
                                            <p class="text-danger">{{$message}}</p>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Email</label>
                                            <input type="text" name="email" value="{{$user->email}}" class="form-control" placeholder="">
                                            @error('email')
                                            <p class="text-danger">{{$message}}</p>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Phone Number</label>
                                            <input type="text" name="phone_no" value="{{$user->phone_no}}" class="form-control" placeholder="">
                                            @error('phone_no')
                                            <p class="text-danger">{{$message}}</p>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Community/Institution Name</label>
                                            <input type="text" name="institution" value="{{$user->institution}}" class="form-control" placeholder="">
                                            @error('institution')
                                            <p class="text-danger">{{$message}}</p>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-12"><button type="submit" class="btn btn-primary pull-left" >Submit</button></div>
                                </div>
                                <div class="clearfix"></div>
                            </form>
                        </div>
                    </div>
                </div>

                <div class="col-md-12">
                    <div class="card-header-tab card-header">
                        <div class="card-header-title font-size-lg text-capitalize font-weight-normal"><i class="pe-7s-lock mr-3 text-muted opacity-6" style="font-size: 35px; color: #E43D4E !important;"> </i>Change Password</div>
                    </div>
                    <div class="main-card mb-3 card">
                        <div class="card-body">
                            <form action="{{route('admin.edit-user-password',$user->id)}}" method="post" id="user-edit-password-form">
                                @csrf
                                @method('put')
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>New Password:</label>
                                            <input type="password" name="password" id="password" class="form-control" placeholder="">
                                            @error('password')
                                            <p class="text-danger">{{$message}}</p>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Confirm New Password:</label>
                                            <input type="password" name="confirm_password" class="form-control" placeholder="">
                                            @error('confirm_password')
                                            <p class="text-danger">{{$message}}</p>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-12"><button type="submit" class="btn btn-primary pull-left" >Submit</button></div>
                                <div class="clearfix"></div>
                            </form>
                        </div>
                    </div>
                </div>


            </div>
        </div>
    </div>
@endsection
@section('script')
    <script>
        $('#user-edit-password-form').validate({
            rules:{
                password: {
                    required: true,
                    password_check: true

                },
                confirm_password:{
                    required: true,
                    equalTo: '#password'
                }
            },
            messages:{
                password: {
                    required: "Password is required.",

                },
                confirm_password:{
                    required: "Confirm Password is required.",
                    equalTo: 'Password and confirm password should match.'
                }
            },
            submitHandler: function (form){
                $.blockUI({ message: '<img src="/images/loader1.gif" class="loader-size" />', css: { backgroundColor: 'transparent', border:'transparent'} });

                form.submit();
            }
        });

    </script>

@endsection
