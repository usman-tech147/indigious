@extends('layouts.app')
@section('title','Subscription Requests')

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
                            <h2 class="form-title">Subscription Requests</h2>
                            <div class="lifestyle-table-responsive">
                                <table class="table" id="user-subscription-request">
                                    <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Package</th>
                                        <th>Status</th>
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

            let table2 = $('#user-subscription-request').DataTable({

                "processing": true,
                "serverSide": true,
                "searching": false,
                'ordering': false,
                "ajax": {
                    "url": "{{route('user.subscription-request-list')}}",
                    "type": "post",
                    "data": function (d) {
                        d._token = token
                    },
                    error: function (response) {
                        console.log(response.responseJSON);
                    }
                },
                "columns": [
                    {data: 'no'},
                    {data: 'package_name'},
                    {data: 'status'},

                ]
            });

        })

    </script>
@endsection
