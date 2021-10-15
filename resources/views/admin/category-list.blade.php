@extends('admin.layouts.app')
@section('title','Categories')

@section('content')
    <div class="app-main__inner">
        <div class="tabs-animation">
            <div class="row">

                <div class="col-md-12">
                    <div class="card-header-tab card-header">
                        <div class="card-header-title font-size-lg text-capitalize font-weight-normal"><i class="pe-7s-folder mr-3 text-muted opacity-6" style="font-size: 35px; color: #E43D4E !important;"> </i>Add New Category</div>
                    </div>
                    <div class="main-card mb-3 card">
                        <div class="card-body">
                            @include('partials.flash-message')

                            <form action="{{route('admin.new-category',[$package->id])}}" method="POST" enctype="multipart/form-data"  id="new-category-form">
                                @csrf
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Category Name</label>
                                            <input type="text" class="form-control"  name="name" id="name" placeholder="" value="{{old('name')}}">
                                            @error('name')
                                            <label class="error" for="name">{{$message}}</label>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-4"><button type="submit" class="btn btn-primary pull-left" style="margin: 30px 0px 0px;" >Add New Category</button></div>
                                </div>
                                <div class="clearfix"></div>
                            </form>
                        </div>
                    </div>
                </div>


                <div class="col-md-12">
                    <div class="search-sides">
                        <div class="row">
                            <div class="col-md-8">
                                <input type="text" placeholder="Category Name" class="form-control"  id="category_list_search_name" >
                            </div>
                            <div class="col-md-4"><button type="submit" class="btn btn-primary pull-left" id="category_list_search_button">Search</button></div>
                        </div>
                    </div>
                    <div class="clearfix"></div>
                    <div class="card-header-tab card-header">
                        <div class="card-header-title font-size-lg text-capitalize font-weight-normal"><i class="pe-7s-folder mr-3 text-muted opacity-6" style="font-size: 35px; color: #E43D4E !important;"> </i>Category List</div>
                    </div>
                    <div class="main-card mb-3 card">
                        <div class="card-body">
                            <a href="{{route('admin.package-list')}}" class="btn btn-secondary pull-right">Back</a>
                            <br>
                            <div class="table-responsive">
                                <table class="table table-hover table-striped table-bordered table-cursor" style="width: 100%;" id="category-list">
                                    <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Category Name</th>
{{--                                        <th>Image</th>--}}
                                        <th>Package</th>
                                        <th>Videos</th>
{{--                                        <th class="width-33">Details</th>--}}
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
{{--        edit --}}
$('#categoryModal').on('show.bs.modal', function (event) {
    let button = $(event.relatedTarget) // Button that triggered the modal
    let id = button.data('id')
    let name = button.data('name')
    let detail = button.data('detail')
    let modal = $(this)
    modal.find('input[name=e_name]').val(name)
    modal.find('textarea[name=e_detail]').val(detail)
    $('#edit-category-form').attr('action','{{route('admin.edit-category',[''])}}/'+id);
})
        //category list
        $(document).ready(function () {

            let token=$('meta[name="_token"]').attr('content');
            let table = $('#category-list').DataTable({

                "processing": true,
                "serverSide": true,
                "searching": false,
                "ordering": false,
                "ajax": {
                    "url": '{{route('admin.get-all-categories')}}',
                    "type": "post",
                    "data": function (d) {
                            d._token = token,
                            d.name = $('#category_list_search_name').val(),
                                d.package_id= {{$package->id}}
                    },
                    error: function (response) {
                        console.log(response.responseJSON);
                    }

                },
                "columns": [
                    {data: 'no'},
                    {data: 'name'},
                    // {data: 'image'},
                    {data: 'package'},
                    {data: 'video'},
                    // {data: 'detail'},
                    {data: 'action'},

                ]
            });

            $('#category_list_search_button').on('click', function (event) {
                event.preventDefault();
                $.blockUI({ message: '<img src="/images/loader1.gif" class="loader-size" />', css: { backgroundColor: 'transparent', border:'transparent'} });
                table.draw();
            })
            $(document).on('click', '.btn-delete-package', function () {
                event.preventDefault();
                let id = $(this).data('id');
                categoryDelete(id);

            });

            function categoryDelete(id) {
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
                                url: path+"/admin/delete-category/" + id,
                                type: "POST",
                                data: {
                                    _token: token,
                                    _method: 'delete'

                                },
                                success: function (response){
                                    console.log(response)
                                    table.draw();
                                    swal("Your Category has been deleted!", {
                                        icon: "success",

                                    });
                                },
                                error: function (response){
                                    console.log(response)
                                    table.draw();
                                    swal("You Cannot Delete This Category!", {
                                        icon: "error",

                                    });
                                }
                            });


                        } else {
                            swal("Your Category Details are safe!");
                        }
                    });

            }
        })
    </script>
@endsection
