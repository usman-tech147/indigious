@extends('admin.layouts.app')
@section('title','Packages')

@section('content')
    <div class="app-main__inner">
        <div class="tabs-animation">
            <div class="row">
                <div class="col-md-12">
                    <form class="search-sides">
                        <div class="row">
                            <div class="col-md-8">
                                <input type="text" class="form-control" placeholder="Package Name"  id="package_list_search_name">
                            </div>
                            <div class="col-md-4"><button type="submit" class="btn btn-primary pull-left" id="package_list_search_button">Search</button></div>
                        </div>
                        <div class="clearfix"></div>
                    </form>
                    <div class="clearfix"></div>
                    <div class="card-header-tab card-header">
                        <div class="card-header-title font-size-lg text-capitalize font-weight-normal"><i class="pe-7s-server mr-3 text-muted opacity-6" style="font-size: 35px; color: #E43D4E !important;"> </i>Packages List</div>
                    </div>
                    <div class="main-card mb-3 card">
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-hover table-striped table-bordered table-cursor" style="width: 100%;" id="package-list">
                                   <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Package Name</th>
                                            <th>Thumbnail</th>
{{--                                            <th>Package Price/Month</th>--}}
{{--                                            <th>Package Price/6 Month</th>--}}
                                            <th>Package Price/Year</th>
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
        //package list
        $(document).ready(function () {

            let token=$('meta[name="_token"]').attr('content');

            let table = $('#package-list').DataTable({

                "processing": true,
                "serverSide": true,
                "searching": false,
                "ordering": false,
                "ajax": {
                    "url": path+"/admin/get-all-packages",
                    "type": "post",
                    "data": function (d) {
                        d._token = token,
                            d.package_name = $('#package_list_search_name').val()
                    },
                    error: function (response) {
                        console.log(response.responseJSON);
                    }

                },
                "columns": [
                    {data: 'no'},
                    {data: 'package_name'},
                    {data: 'thumbnail'},
                    // {data: 'price'},
                    // {data: 'price_months'},
                    {data: 'price_year'},
                    {data: 'action'},

                ]
            });

            $('#package_list_search_button').on('click', function (event) {
                event.preventDefault();
                $.blockUI({ message: '<img src="/images/loader1.gif" class="loader-size" />', css: { backgroundColor: 'transparent', border:'transparent'} });

                table.draw();
            })
            $(document).on('click', '.btn-delete-package', function () {
                event.preventDefault();
                let id = $(this).data('id');
                packageDelete(id);

            });

            function packageDelete(id) {
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
                            $.blockUI({ message: '<img src="/images/loader1.gif" class="loader-size" />', css: { backgroundColor: 'transparent', border:'transparent'} });
                            $.ajax({
                                url: path+"/admin/delete-package/" + id,
                                type: "POST",
                                data: {
                                    _token: token,
                                    _method: 'delete'

                                },
                                success: function (response){
                                    console.log(response)
                                    table.draw();

                                    swal("Your Package has been deleted!", {
                                        icon: "success",

                                    });
                                },
                                error: function (response){
                                    console.log(response)
                                    table.draw();

                                    swal("Your Cannot Delete This Package!", {
                                        icon: "error",

                                    });
                                }
                            });


                        } else {
                            swal("Your Package is safe!");
                        }
                    });

            }
        })

    </script>
@endsection