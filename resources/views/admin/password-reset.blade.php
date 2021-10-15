@extends('admin.layouts.auth-app')
@section('title','Forgot Password')

@section('content')
    <div class="app-container app-theme-white body-tabs-shadow">
        <div class="app-container">
            <div class="h-100 bg-plum-plate bg-animation">
                <div class="d-flex h-100 justify-content-center align-items-center">
                    <div class="mx-auto app-login-box col-md-6">
                        <div class="app-logo-inverse mx-auto mb-3"></div>
                        <div class="modal-dialog w-100">
                            <div class="modal-content">
                                <form action="{{route('admin.password-reset-page',[$email,'token'=>$code])}}" id="admin-password-reset"  method="post" >

                                <div class="modal-header">
                                    <div class="h5 modal-title text-center" style="width: 100%;">Password Reset<h6 class="mt-1 mb-0 opacity-8"><span>Enter new password</span></h6></div>
                                </div>
                                <div class="modal-body">
                                    <div>
                                            @csrf
                                            @method('PUT')
                                            <div class="form-row">
                                                <div class="col-md-12">
                                                    @include('partials.flash-message')

                                                    <div class="position-relative form-group">
                                                            <label for="password" class="">Password</label>
                                                            <input name="password" id="password" placeholder="Password here..." type="password" class="form-control">
                                                            @error('password')
                                                            <p class="text-danger">{{$message}}</p>
                                                            @enderror
                                                        </div>

                                                        <div class="position-relative form-group"><label for="confirm_password" class="">Password</label><input name="confirm_password" id="confirm_password" placeholder="Confirm Password here..." type="password" class="form-control">
                                                            @error('confirm_password')
                                                            <p class="text-danger">{{$message}}</p>
                                                            @enderror
                                                        </div>
                                                </div>
                                            </div>
                                    </div>
                                    <div class="divider"></div>
                                    <h6 class="mb-0"><a href="{{route('admin.login')}}" class="text-primary">Back to Login</a></h6></div>
                                <div class="modal-footer clearfix">
                                    <div class="float-right">
                                        <button class="btn btn-primary btn-lg" type="submit">Change Password</button>
                                    </div>
                                </div>
                                </form>
                            </div>
                        </div>
                        <div class="text-center text-white opacity-8 mt-3">© {{date('Y')}} - All Rights Reserved</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
