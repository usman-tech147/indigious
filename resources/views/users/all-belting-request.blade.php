@extends('layouts.app')
@section('title','Belting Requests')

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
                            <h2 class="form-title">Belting Requests</h2>
                            <div class="lifestyle-table-responsive">
                                <table class="table" id="user-all-belting-request">
                                    <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Name</th>
                                        <th>Email</th>
                                        <th>Status</th>
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
        <!--// Main Section \\-->

    </div>
    <!--// Main Content \\-->
@endsection
@section('script')
    <script>
        //belting request:
        $(document).ready(function () {
            let token=$('meta[name="_token"]').attr('content');
            // $.blockUI({ message: '<img src="/images/loader1.gif" class="loader-size" />', css: { backgroundColor: 'transparent', border:'transparent'} });

            let table2 = $('#user-all-belting-request').DataTable({

                "processing": true,
                "serverSide": true,
                "searching": false,
                'ordering': false,
                "ajax": {
                    "url": "/user/get-all-belting-request",
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
                    {data: 'status'},
                    {data: 'action'},

                ]
            });
            // $('#search-belting-search').on('click', function (event) {
            //     event.preventDefault();
            //     table2.draw();
            // })
        })

    </script>
@endsection