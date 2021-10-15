@extends('layouts.app')
@section('title','Access Password')

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
                            <h2 class="form-title">Access List</h2>
                            <span><strong> Default password:</strong> 12341234 </span>
                            <div class="row justify-content-center">
                                <div class="col-6">
                                    @include('partials.flash-message')
                                </div>
                            </div>
                            <div class="lifestyle-table-responsive">
                                <table class="table">
                                    <tr>
                                        <th>No</th>
                                        <th>Package Name</th>
                                        <th>Sharing Link (Click the link to copy)</th>
                                        <th>Action</th>
                                    </tr>
                                    @php
                                    $i=1;
                                    @endphp
                                    @if($userPackages->count()==0)
                                    <tr>
                                        <td class="text-center" colspan="4">No Data Available</td>
                                    </tr>
                                    @else
                                    @foreach($userPackages as $package)
                                    <tr>
                                        <td>{{$i++}}</td>
                                        <td>{{$package->name}}</td>
                                        <td><a class="text-to-copy" href="{{route('video-access',[$package->pivot->username])}}">{{route('video-access',[$package->pivot->username])}}</a></td>
                                        <td><a href="#" data-toggle="modal" data-target="#accessPasswordModal" data-id="{{$package->pivot->id}}" data-package_id="{{$package->id}}" class="tags-btn">Change Password</a></td>
                                    </tr>
                                    @endforeach
                                        @endif
                                </table>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!--// Main Section \\-->

    </div>
    <!--// Main Content \\-->
    <div class="modal fade" id="accessPasswordModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Set Password</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form method="post" id="change-access-password-form">
                        @csrf
                        @method('put')
                        <div class="form-group">
                            <label for="password" class="col-form-label">Current Password:</label>
                            <input type="password" name="password" id="password" class="form-control" >
                        </div>
                        <div class="form-group">
                            <label for="new_password" class="col-form-label">New Password:</label>
                            <input type="password" name="new_password" id="new_password" class="form-control" >
                        </div>
                        <div class="form-group">
                            <label for="confirm_password" class="col-form-label">Confirm Password:</label>
                            <input type="password" name="confirm_password" id="confirm_password" class="form-control">
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" id="submit-access-password" class="btn btn-primary">Change Password</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script>
        $('.text-to-copy').on('click',function (event){
            event.preventDefault();
            var $temp = $("<input>");
            $("body").append($temp);
                let link=$(this).attr('href');
            console.log(link)
            $temp.val(link).select();
            console.log($temp.val())
            document.execCommand("copy");
            $temp.remove();
            swal({
                title:'Copied to clipboard',
                icon:'success'
            });
        });
    </script>
@endsection