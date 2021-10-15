@extends('admin.layouts.app')
@section('title','Manage User Subscription')

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
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Username</label>
                                        <input type="text" id="username" class="form-control" placeholder="">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Package Name</label>
                                        <input type="text" id="package-name" class="form-control" placeholder="">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Payment By</label>
                                        <select name="payment_by" id="payment_by" class="form-control">
                                            <option value="">All</option>
                                            <option value="PayPal">PayPal</option>
                                            <option value="Stripe">Stripe</option>
                                            <option value="Manual">Manual</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4"><button type="button" class="btn btn-primary pull-left" id="search-button">Search</button></div>
                                <div class="col-md-12 ">
                                    <a class="btn btn-primary pull-right" href="{{route('admin.add-subscription')}}">Add Manual Subscription</a>
                                </div>
                            </div>
                            <div class="clearfix"></div>
                        </div>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="card-header-tab card-header">
                        <div class="card-header-title font-size-lg text-capitalize font-weight-normal"><i class="pe-7s-box1 mr-3 text-muted opacity-6" style="font-size: 35px; color: #E43D4E !important;"> </i>Subscriptions</div>
                    </div>
                    <div class="main-card mb-3 card">
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-hover table-striped table-bordered table-cursor" style="width: 100%;" id="manage-user-list">
                                    <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Package Name</th>
                                        <th>Username</th>
                                        <th>Status</th>
                                        <th>Payment By</th>
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

    <script>
        $('#search-button').on('click',function (){
                table.draw()
        });
        let table = $('#manage-user-list').DataTable({

            "processing": true,
            "serverSide": true,
            "searching": false,
            'ordering': false,
            "ajax": {
                "url": path+"/admin/user-subscription-list",
                "type": "post",
                "data": function (d) {
                        d._token = '{{csrf_token()}}';
                        d.package_name = $('#package-name').val();
                        d.username = $('#username').val();
                        d.payment_by = $('#payment_by').val();
                },

                error: function (response) {
                    console.log(response.responseJSON);
                }

            },
            "columns": [
                {data: 'no'},
                {data: 'package_name'},
                {data: 'user_name'},
                {data: 'status'},
                {data: 'payment_by'},
                {data: 'action'},
            ]
        });

    </script>

@endsection
