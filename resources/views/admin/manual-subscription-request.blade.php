@extends('admin.layouts.app')
@section('title','User Subscription Request')

@section('content')
    <div class="app-main__inner">
        <div class="tabs-animation">
            <div class="row">
                <div class="col-md-12">
                    @include('partials.flash-message')
                    <div class="card-header-tab card-header">
                        <div class="card-header-title font-size-lg text-capitalize font-weight-normal"><i class="pe-7s-search mr-3 text-muted opacity-6" style="font-size: 35px; color: #E43D4E !important;"> </i>Search</div>
                    </div>
                    <div class="main-card mb-3 card">
                        <div class="card-body">

                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Username</label>
                                        <input type="text" id="username" class="form-control" placeholder="">
                                    </div>
                                </div>
{{--                                <div class="col-md-4">--}}
{{--                                    <div class="form-group">--}}
{{--                                        <label>Package Name</label>--}}
{{--                                        <input type="text" id="package-name" class="form-control" placeholder="">--}}
{{--                                    </div>--}}
{{--                                </div>--}}
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Status</label>
                                        <select name="status" id="status" class="form-control">
                                            <option value="">All</option>
                                            <option value="Pending">Pending</option>
                                            <option value="Approved">Approved</option>
                                            <option value="Rejected">Rejected</option>
                                        </select>
                                    </div>
                                </div>

                            </div>
                            <div class="row">
                                <div class="col-md-4"><button type="button" class="btn btn-primary pull-left" id="search-button">Search</button></div>
                            </div>
                            <div class="clearfix"></div>
                        </div>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="card-header-tab card-header">
                        <div class="card-header-title font-size-lg text-capitalize font-weight-normal"><i class="pe-7s-box1 mr-3 text-muted opacity-6" style="font-size: 35px; color: #E43D4E !important;"> </i>Subscription Request</div>
                    </div>
                    <div class="main-card mb-3 card">
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-hover table-striped table-bordered table-cursor" style="width: 100%;" id="subscription-request-list">
                                    <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Username</th>
                                        <th>Package Name</th>
                                        <th>Status</th>
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
@section('script')
    <div class="modal fade" id="approveModal" tabindex="-1" role="dialog" aria-labelledby="approveModal" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Subscription Request Approve</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="{{route('admin.manual-subscription')}}" method="post" id="user-manual-form">
                    @csrf
                    @method('put')
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="">Username:</label>
                                <select  name="user" id="modal_user" class="form-control" readonly>
                                    <option value="" id="user-option"></option>
                                </select>
                                <label id="modaluser-error" class="error" for="user" style="display: none"></label>
                                @error('user')
                                <label id="modaluser-error" class="error" for="user" style="display: none">{{$message}}</label>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="">Package Name</label>
                                <select  name="package" id="modal_package" class="form-control" readonly>
                                    <option value="" id="package-option"></option>

                                </select>
                                <label id="modalpackage-error" class="error" for="package" style="display: none">Select Package from dropdown.</label>
                                @error('package')
                                <label id="modalpackage-error" class="error" for="package" style="display: none">{{$message}}</label>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="">Expiry Date:</label>
                                <input type="text" name="expiry_date" id="modal_date" class="form-control" >
                                @error('expiry_date')
                                <label class="error">{{$message}}</label>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="">Free:</label>
                                <input type="hidden" name="free" class="form-control-sm" placeholder="" value="false">
                                <input type="checkbox" name="free" id="free" value="true" class="form-control-sm" >

                                @error('free')
                                <label class="error">{{$message}}</label>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-4" id="priceDiv">
                            <div class="form-group">
                                <label for="">Amound Paid:</label>
                                <input type="text" name="price" id="modal_price" class="form-control" >
                                @error('price')
                                <label class="error">{{$message}}</label>
                                @enderror
                            </div>
                        </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Approve</button>
                </div>
                </div>
                </form>
            </div>
        </div>
    </div>
    <script>
        jQuery.validator.addMethod("greaterThan", function(value, element) {
            return this.optional(element) || new Date()<=new Date(value);
        }, "Expiry date should be greater than current date");
        $('#user-manual-form').validate({
            rules:{
                package: {
                    required: true,
                },
                user: {
                    required: true,
                },
                expiry_date:{
                    required: true,
                    greaterThan: true
                },
                price: {
                    required: true
                }
            },
            messages:{
                package: {
                    required: "Select package from dropdown.",
                },
                user: {
                    required: "Select user from dropdown.",
                },
                expiry_date:{
                    required: "Expiry date is required.",
                },
                price:{
                    required: "Price is required.",
                }
            },
            submitHandler: function (form){
                $.blockUI({ message: '<img src="/images/loader1.gif" class="loader-size" />', css: { backgroundColor: 'transparent', border:'transparent'} });

                form.submit();
            }
        });
        $('#search-button').on('click',function (){
            table.draw()
        });
        $('#myModal').on('hidden.bs.modal', function (e) {
            $('#user-option').val('');
            $('#user-option').text('');
            $('#package-option').val('');
            $('#package-option').text('');
            $('#modal_price').val('');
        })
        let d=new Date()
        d.setDate(d.getDate() + 365);
        $('#modal_date').datepicker({
            minDate: d
        });
        $('#modal_date').datepicker("setDate" , d);
        $(document).on('click','.approve-subscription',function () {
            let user_id=$(this).data('user');
            let package_id=$(this).data('package');
            let id=$(this).data('id');
            console.log(id)
            $.ajax({
                url: '/admin/user-subscription-request/'+id,
                type: 'GET',

                success: function (response){
                    let r=response.subscription;
                    $('#user-option').val(r.user.id);
                    $('#user-option').text(r.user.name);
                    $('#package-option').val(r.package.id);
                    $('#package-option').text(r.package.name);
                    $('#modal_price').val(r.package.price_year);
                    $('#approveModal').modal('show')
                }
            })
        });
        $(document).on('click','.reject-subscription',function (){
            let user_id=$(this).data('user');
            let package_id=$(this).data('package');
            console.log(user_id)
            swal({
                title: "Are you sure?",
                text: "You will reject subscription request.",
                icon: "warning",
                buttons: true,
                dangerMode: true,
            })
                .then((willDelete) => {
                    if (willDelete) {
                        $.blockUI({ message: '<img src="/images/loader1.gif" class="loader-size" />', css: { backgroundColor: 'transparent', border:'transparent'} });
                        $.ajax({
                            url: '{{route('admin.user-subscription-request-reject')}}',
                            type: 'post',
                            data: {
                                _token:'{{@csrf_token()}}',
                                user_id: user_id,
                                package_id: package_id
                            },
                            success: function (response){
                                swal("Subscription Request Rejected!", {
                                    icon: "success",
                                });
                                table.draw();
                            },
                            error: function (response){

                                console.log(response);
                                table.draw();

                            }
                        });

                    }
                });

        });
        let table = $('#subscription-request-list').DataTable({

            "processing": true,
            "serverSide": true,
            "searching": false,
            'ordering': false,
            "ajax": {
                "url": "{{route('admin.user-subscription-request-list')}}",
                "type": "post",
                "data": function (d) {
                    d._token = '{{csrf_token()}}';
                    // d.package_name = $('#package-name').val();
                    d.username = $('#username').val();
                    d.status = $('#status').val();
                },

                error: function (response) {
                    console.log(response.responseJSON);
                }

            },
            "columns": [
                {data: 'no'},
                {data: 'user_name'},
                {data: 'package_name'},
                {data: 'status'},
                {data: 'action'},
            ]
        });
        if($('#free:checked').val()){
            $('#priceDiv').hide()
        }
        $('#free').on('change',function (){
            let free=$('#free:checked').val();
            console.log(free)
            if(free){
                $('#priceDiv').hide()
            }else{
                $('#priceDiv').show()

            }
        });
    </script>

@endsection
