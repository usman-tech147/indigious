@extends('admin.layouts.app')
@section('title','Belting Evolution Requests')

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
                                            <label>Name</label>
                                            <input type="text" id="search-belting-name" class="form-control" placeholder="">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Status</label>
                                            <select id="search-belting-status" id="" class="form-control">
                                                <option value="">Select Status</option>
                                                <option value="pending">Pending</option>
                                                <option value="approved">Approved</option>
                                                <option value="rejected">Rejected</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-4"><button type="submit" id="search-belting-search" class="btn btn-primary pull-left" style="margin: 30px 0px 0px;">Search</button></div>
                                </div>
                                <div class="clearfix"></div>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="card-header-tab card-header">
                        <div class="card-header-title font-size-lg text-capitalize font-weight-normal"><i class="pe-7s-users mr-3 text-muted opacity-6" style="font-size: 35px; color: #E43D4E !important;"> </i>Belting Evaluation Requests</div>
                    </div>
                    <div class="main-card mb-3 card">
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-hover table-striped table-bordered table-cursor" style="width: 100%;" id="belting-list">
                                    <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Name</th>
                                        <th>Email</th>
                                        <th>Phone</th>
                                        <th>Date/Time</th>
                                        <th>Status</th>
                                        <th style="width: 40%;">Message</th>
                                        <th>Action</th>
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
        //belting request:
        $(document).ready(function () {
            let token=$('meta[name="_token"]').attr('content');

            let table2 = $('#belting-list').DataTable({

                "processing": true,
                "serverSide": true,
                "searching": false,
                'ordering': false,
                "ajax": {
                    "url": path+"/admin/get-all-belting-request",
                    "type": "post",
                    "data": function (d) {
                        d._token = token,
                            d.name = $('#search-belting-name').val(),
                            d.status = $('#search-belting-status').val()

                    },

                    error: function (response) {
                        console.log(response.responseJSON);
                    }

                },
                "columns": [
                    {data: 'no'},
                    {data: 'name'},
                    {data: 'email'},
                    {data: 'phone_no'},
                    {data: 'date',"sType": 'date'},
                    {data: 'status'},
                    {data: 'message'},
                    {data: 'action'},
                ]
            });
            $('#search-belting-search').on('click', function (event) {
                event.preventDefault();
                $.blockUI({ message: '<img src="/images/loader1.gif" class="loader-size" />', css: { backgroundColor: 'transparent', border:'transparent'} });

                table2.draw();
            })

            $(document).on('change','.change-status',function (event){
                event.preventDefault();
                let belting_id=$(this).data('id');
                let status=$(this).val();
                $.blockUI({ message: '<img src="/images/loader1.gif" class="loader-size" />', css: { backgroundColor: 'transparent', border:'transparent'} });

                $.ajax({
                    url: path+'/admin/update-belting-status',
                    type: 'put',
                    data: {
                        _token: token,
                        belting_id: belting_id,
                        status: status
                    },
                    success: function(response){
                        table2.draw();
                    },
                    error: function (response){
                        console.log(response);
                    }
                });
            })
        })
    </script>
@endsection