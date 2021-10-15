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
                                <form action="{{route('admin.forgot-password')}}" id="admin-forgot-password"  method="post" >
                                    @csrf
                                <div class="modal-header">
                                    <div class="h5 modal-title text-center" style="width: 100%;">Forgot your Password?<h6 class="mt-1 mb-0 opacity-8"><span>Enter your email below to recover it.</span></h6></div>
                                </div>
                                <div class="modal-body">
                                    <div>
                                            <div class="form-row">
                                                <div class="col-md-12">
                                                    @include('partials.flash-message')

                                                    <div class="position-relative form-group"><label for="exampleEmail" class="">Email</label><input name="email" id="exampleEmail" placeholder="Email here..." type="email" class="form-control">
                                                        @error('email')
                                                        <p class="text-danger">{{$message}}</p>
                                                        @enderror
                                                    </div>
                                                </div>
                                            </div>
                                    </div>
                                    <div class="divider"></div>
                                    <h6 class="mb-0"><a href="{{route('admin.sign-in')}}" class="text-primary">Back to Login</a></h6></div>
                                <div class="modal-footer clearfix">
                                    <div class="float-right">
                                        <button type="submit" class="btn btn-primary btn-lg" >Recover Password</button>
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
