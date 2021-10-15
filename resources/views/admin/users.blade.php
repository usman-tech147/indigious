@extends('admin.layouts.app')
@section('title','Users')

@section('content')
    <div class="app-main__inner">
        <div class="tabs-animation">
            <div class="row">
                <div class="col-md-12">
                    <div class="card-header-tab card-header">
                        <div class="card-header-title font-size-lg text-capitalize font-weight-normal"><i class="pe-7s-search mr-3 text-muted opacity-6" style="font-size: 35px; color: #E43D4E !important;"> </i>Search</div>
                    </div>
                    <div class="main-card mb-3 card">
                        <div class="card-body">
                            <form>
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Username</label>
                                            <input type="text" id="search-user-username" class="form-control" placeholder="">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Package Name</label>
                                            <input type="text" id="search-user-package" class="form-control" placeholder="">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Community/Institution Name</label>
                                            <input type="text" id="search-user-institution" class="form-control" placeholder="">
                                        </div>
                                    </div>
                                    <div class="col-md-4"><button type="submit" class="btn btn-primary pull-left" id="search-user-button">Search</button></div>
                                </div>
                                <div class="clearfix"></div>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="card-header-tab card-header">
                        <div class="card-header-title font-size-lg text-capitalize font-weight-normal"><i class="pe-7s-users mr-3 text-muted opacity-6" style="font-size: 35px; color: #E43D4E !important;"> </i>Users</div>
                    </div>
                    <div class="main-card mb-3 card">
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-hover table-striped table-bordered table-cursor" style="width: 100%;" id="user-list">
                                    <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Username</th>
                                        <th>Email</th>
                                        <th>Subscribed Packages</th>
                                        <th>Phone Number</th>
                                        <th>Community/Institution Name</th>
                                        <th>Action</th>
                                    </tr>
                                    </thead>
                                    <tbody>

                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

{{--<!-- Modal -->--}}
{{--<div class="modal fade" id="passwordModal" tabindex="-1" role="dialog" aria-labelledby="passwordModalLabel" aria-hidden="true">--}}
{{--    <div class="modal-dialog" role="document">--}}
{{--        <div class="modal-content">--}}
{{--            <div class="modal-header">--}}
{{--                <h5 class="modal-title" id="passwordModalLabel"></h5>--}}
{{--                <button type="button" class="close" data-dismiss="modal" aria-label="Close">--}}
{{--                    <span aria-hidden="true">&times;</span>--}}
{{--                </button>--}}
{{--            </div>--}}
{{--            <div class="modal-body">--}}
{{--                <div class="form-group">--}}
{{--                    <label>Username</label>--}}
{{--                    <input type="text" readonly=""  class="modal-username form-control" >--}}
{{--                </div>--}}
{{--                <div class="form-group">--}}
{{--                    <label>Password</label>--}}
{{--                    <input type="text" readonly=""  class="modal-password form-control" >--}}
{{--                </div>--}}
{{--                <div class="form-group">--}}
{{--                    <label>Subscribed on:</label>--}}
{{--                    <input type="text" readonly=""  class="modal-subscribed-on form-control" >--}}
{{--                </div>--}}
{{--                <div class="form-group">--}}
{{--                    <label>Valid till:</label>--}}
{{--                    <input type="text" readonly=""  class="modal-payment-due-on form-control" >--}}
{{--                </div>--}}
{{--            </div>--}}
{{--            <div class="modal-footer">--}}
{{--                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>--}}
{{--                <!-- <button type="button" class="btn btn-primary">Save changes</button> -->--}}
{{--            </div>--}}
{{--        </div>--}}
{{--    </div>--}}
{{--</div>--}}
@section('script')

    <script>
        //user list

        let token=$('meta[name="_token"]').attr('content');

        let table1 = $('#user-list').DataTable({

            "processing": true,
            "serverSide": true,
            "searching": false,
            'ordering': false,
            "ajax": {
                "url": path+"/admin/get-all-users",
                "type": "post",
                "data": function (d) {
                    d._token = token,
                        d.name = $('#search-user-username').val(),
                        d.package_name = $('#search-user-package').val(),
                        d.institution = $('#search-user-institution').val()

                },
                error: function (response) {
                    console.log(response.responseJSON);
                }

            },
            "columns": [
                {data: 'no'},
                {data: 'name'},
                {data: 'email'},
                {data: 'packages'},
                {data: 'phone_no'},
                {data: 'institution'},
                {data: 'action'},

            ]
        });
        $('#search-user-button').on('click', function (event) {
            event.preventDefault();
            $.blockUI({ message: '<img src="/images/loader1.gif" class="loader-size" />', css: { backgroundColor: 'transparent', border:'transparent'} });
            table1.draw();
        })
        $(document).on('click', '.btn-delete-user', function (event) {
            event.preventDefault();
            let id = $(this).data('id');
            userDelete(id);

        });
        $(document).on('click','.btn-block-user' ,function(event) {
            event.preventDefault();
            let user_id=$(this).data('id');
            let blocked=$(this).data('block');
            let msg;
            if(blocked){
                msg="You will unblock user account they will be able to access their account!";
            }else{
                msg="You will block user from accessing their account!";
            }
            swal({
                title: "Are you sure?",
                text: msg,
                icon: "warning",
                buttons: true,
                dangerMode: true,
            })
                .then((willDelete) => {
                    if (willDelete) {
                        $.blockUI({ message: '<img src="/images/loader1.gif" class="loader-size" />', css: { backgroundColor: 'transparent', border:'transparent'} });

                        $.ajax({
                            url: '/admin/block-user',
                            type: 'put',
                            data: {
                                _token: token,
                                user_id: user_id
                            },
                            success: function (response){

                                console.log(response.success)
                                let block=response.success;
                                let message;
                                if(block){
                                    message="User is blocked!";
                                }else{
                                    message="User is unblocked!";
                                }
                                swal(message, {
                                    icon: "success",
                                });
                                table1.draw();
                            },
                            error: function (response){

                                console.log(response);
                                table1.draw();

                            }
                        });

                    }
                });

        })
        function userDelete(id) {
            console.log(id);
            swal({
                title: "Are you sure?",
                text: "Once deleted, you will not be able to recover!",
                icon: "warning",
                buttons: true,
                dangerMode: true,
            })
                .then((willDelete) => {
                    if (willDelete) {
                        $.blockUI({ message: '<img src="/images/loader.gif" />' });
                        $.ajax({
                            url: path+"/admin/delete-user/" + id,
                            type: "POST",
                            data: {
                                _token: token,
                                _method: 'delete'

                            },
                            success: function() {

                                swal("Poof! Your User Details has been deleted!", {
                                    icon: "success",

                                });
                                table1.draw();

                            },
                            error: function (response)
                            {

                                console.log(response)
                                swal("You Cannot delete this user!", {
                                    icon: "error",

                                });
                                table1.draw();

                            }
                        });

                    } else {
                        swal("Your User Details Are Safe!");
                    }
                });

        }

        $('#passwordModal').on('show.bs.modal', function (event) {
            let button = $(event.relatedTarget) // Button that triggered the modal
            let username = button.data('username')
            let password = button.data('password')
            let package_name = button.data('package_name')
            let sub=button.data('subscribed_at');
            let exp=button.data('expired_at');
            let modal = $(this)
            modal.find('.modal-username').val(username);
            modal.find('.modal-password').val(password);
            modal.find('.modal-subscribed-on').val(sub);
            modal.find('.modal-payment-due-on').val(exp);;
            modal.find('.modal-title').text(package_name);

        })
    </script>

@endsection