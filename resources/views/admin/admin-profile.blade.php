@extends('admin.layouts.app')
@section('title','Profile')
    
@section('content')
    <div class="app-main__inner">
        <div class="card-header-tab card-header">
            <div class="card-header-title font-size-lg text-capitalize font-weight-normal">Update password</div>
        </div>
        <div class="tabs-animation">
            <div class="row">
                <div class="col-md-12">
                    <div class="main-card mb-3 card">
                        <div class="card-body">
                            @include('partials.flash-message')
                            <form action="{{route('admin.admin-profile')}}" method="POST" id="change-password-form">
                                @csrf
                                @method('PUT')
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Current Password</label>
                                            <input type="password" name="current_password" class="form-control" placeholder="">
                                            @error('current_password')
                                                <p class="text-danger">{{$message}}</p>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>New Password</label>
                                            <input type="password" name="new_password" id="new_password" class="form-control" placeholder="">
                                            @error('new_password')
                                            <p class="text-danger">{{$message}}</p>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Repeat New Password</label>
                                            <input type="password" name="confirm_new_password" class="form-control" placeholder="">
                                            @error('confirm_new_password')
                                            <p class="text-danger">{{$message}}</p>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                <button type="submit" class="btn btn-primary pull-right">Submit</button>
                                <div class="clearfix"></div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="card-header-tab card-header">
            <div class="card-header-title font-size-lg text-capitalize font-weight-normal">Change Email</div>
        </div>
        <div class="tabs-animation">
            <div class="row">
                <div class="col-md-12">
                    <div class="main-card mb-3 card">
                        <div class="card-body">
                            <form action="{{route('admin.change-email')}}" method="POST" id="change-email-form">
                                @csrf
                                @method('PUT')
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Update Email:</label>
                                            <input type="email" name="email" class="form-control" placeholder="" value="{{auth()->guard('admin')->user()->email}}">
                                            @error('email')
                                            <p class="text-danger">{{$message}}</p>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                <button type="submit" class="btn btn-primary pull-right">Submit</button>
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
        $('#change-email-form').validate({
           rules:{
            email:{
                required: true,
                email: true}
           },
           messages:{
               email:{
                   required: 'Email is required.',
                   email: 'Please enter valid email address.'
               }
           },
           submitHandler: function (form){
               $.blockUI({ message: '<img src="/images/loader1.gif" class="loader-size" />', css: { backgroundColor: 'transparent', border:'transparent'} });
               form.submit();
           }

        });
    </script>
@endsection