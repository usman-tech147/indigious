@extends('admin.layouts.auth-app')
@section('title','Login')

@section('content')
    <div class="app-container app-theme-white body-tabs-shadow">
        <div class="app-container">
            <div class="h-100 bg-plum-plate bg-animation">
                <div class="d-flex h-100 justify-content-center align-items-center">
                    <div class="mx-auto app-login-box col-md-8">
                        <div class="app-logo-inverse mx-auto mb-3"></div>
                        <div class="modal-dialog w-100 mx-auto">
                            <div class="modal-content">
                                <form action="{{route('admin.login')}}" method="post" class="" id="admin-login">
                                    @csrf
                                <div class="modal-body">
                                    <div class="h5 modal-title text-center">
                                        <h4 class="mt-2">
                                            <div>Welcome back,</div>
                                            <span>Please sign in to your account below.</span>
                                        </h4>
                                    </div>

                                        <div class="form-row">
                                            <div class="col-md-12">
                                                @include('partials.flash-message')

                                                <div class="position-relative form-group"><input name="email" id="exampleEmail" placeholder="Email here..." type="email" class="form-control @error('email') is-invalid @enderror"></div>
                                                @error('email')
                                                <p class="text-danger">{{$message}}</p>
                                                @enderror
                                            </div>
                                            <div class="col-md-12">
                                                <div class="position-relative form-group"><input name="password" id="examplePassword" placeholder="Password here..." type="password" class="form-control @error('password') is-invalid @enderror"></div>
                                                @error('password')
                                                <p class="text-danger">{{$message}}</p>
                                                @enderror
                                            </div>
                                        </div>
                                </div>
                                <div class="modal-footer clearfix">
                                    <div class="float-left"><a href="{{route('admin.forgot-password')}}" class="btn-lg btn btn-link">Recover Password</a></div>
                                    <div class="float-right">
                                        <button class="btn btn-primary btn-lg" type="submit">Login to Dashboard</button>
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
