@extends('admin.layouts.app')
@section('title','Payments')

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
                                            <label>User Name</label>
                                            <input type="text" class="form-control"  id="payment_list_search_name" placeholder="">
                                        </div>
                                    </div>
                                    <div class="col-md-4"><button type="submit" class="btn btn-primary pull-left" style="margin: 30px 0px 0px;" id="payment_search_button">Search</button></div>
                                </div>
                                <div class="clearfix"></div>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="card-header-tab card-header">
                        <div class="card-header-title font-size-lg text-capitalize font-weight-normal"><i class="pe-7s-cash mr-3 text-muted opacity-6" style="font-size: 35px; color: #E43D4E !important;"> </i>Payments</div>
                    </div>
                    <div class="main-card mb-3 card">
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-hover table-striped table-bordered table-cursor" style="width: 100%;" id="payment-list">
                                    <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>User Name</th>
                                        <th>Package Name</th>
                                        <th>Amount</th>
                                        <th>Subscription Date</th>
                                        <th>Next Payment</th>
                                        <th>Subscription Id</th>
                                        <th>Payment By</th>
                                    </tr>
                                    </thead>

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

let token=$('meta[name="_token"]').attr('content');



        let payment = $('#payment-list').DataTable({

            "processing": true,
            "serverSide": true,
            "searching": false,
            "ordering": false,
            "ajax": {
                "url": path+"/admin/get-all-payments",
                "type": "post",
                "data": function (d) {
                    d._token = token,
                        d.name = $('#payment_list_search_name').val()
                },
                error: function (response) {
                    console.log(response.responseJSON);
                }

            },
            "columns": [
                {data: 'no'},
                {data: 'username'},
                {data: 'package_name'},
                {data: 'amount'},
                {data: 'subscribed_at'},
                {data: 'expired_at'},
                {data: 'subscription_id'},
                {data: 'payment_by'},

            ]
        });

        $('#payment_search_button').on('click', function (event) {
            event.preventDefault();
            $.blockUI({ message: '<img src="/images/loader1.gif" class="loader-size" />', css: { backgroundColor: 'transparent', border:'transparent'} });
            payment.draw();
        })
    </script>
@endsection
