@extends('admin.layouts.app')
@section('title','Edit Video')

@section('content')
    <div class="app-main__inner">
        <div class="card-header-tab card-header">
            <div class="card-header-title font-size-lg text-capitalize font-weight-normal"><i class="pe-7s-server mr-3 text-muted opacity-6" style="font-size: 35px; color: #E43D4E !important;"> </i>Edit Video Details</div>
        </div>
        <div class="main-card mb-3 card">
            <div class="card-body">
                <div class="tabs-animation">
                    <div class="row">
                        <div class="col-md-12">
                            @include('partials.flash-message')

                            <form action="{{route('admin.edit-video',$video->id)}}" method="POST" enctype="multipart/form-data" id="edit-video-form">
                                @csrf
                                @method("PUT")
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Select Package</label>
                                            <select name="package" id="package" class="form-control">
                                                    @foreach($packages as $package)
                                                        <option value="{{$package->id}}" @if($package->id==$video->subCategory->category->package_id){{'selected'}} @endif>{{$package->name}}</option>
                                                    @endforeach
                                            </select>
                                            @error('package')
                                            <label class="error" for="package">{{$message}}</label>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Select Category</label>
                                            <select name="category" id="category" class="form-control">
                                                @foreach($categories as $category)
                                                    <option value="{{$category->id}}" @if($category->id==$video->subCategory->category->id){{'selected'}} @endif>{{$category->name}}</option>
                                                @endforeach
                                            </select>
                                            <label class="error hide" id="video-category-error" for="category"></label>
                                            <label id="category-error" class="error" for="category" style="display: none"></label>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Select Sub Category</label>
                                            <select name="sub_category" id="sub_category" class="form-control">
                                                @foreach($sub_categories as $subcategory)
                                                    <option value="{{$subcategory->id}}" @if($subcategory->id==$video->subCategory->id){{'selected'}} @endif>{{$subcategory->name}}</option>
                                                @endforeach
                                            </select>
                                            <label class="error hide" id="video-sub_category-error" for="sub_category"></label>
                                            <label id="sub_category-error" class="error" for="sub_category" style="display: none"></label>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Video Name</label>
                                            <input type="text" placeholder="" name="video_name" id="video_name" class="form-control" value="{{$video->title}}">
                                            @error('video_name')
                                            <label class="error" for="video_name">{{$message}}</label>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Select Video Poster</label>
                                            <input type="file" placeholder="" accept="image/jpeg,image/png,image/jpg" name="poster" id="poster" class="form-control">
                                            @error('poster')
                                            <label id="poster-error" class="error" for="poster">{{$message}}</label>
                                            @enderror
                                            <label id="poster-error" class="error" for="poster" style="display: none"></label>

                                        </div>

                                    </div>

                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label>Description</label>
                                            <textarea class="form-control" placeholder="" name="description" id="description">{{$video->description}}</textarea>
                                            @error('description')
                                            <label class="error" for="description">{{$message}}</label>
                                            @enderror
                                        </div>
                                    </div>
{{--                                    <div class="img-wrap">--}}
{{--                                        @if($video->poster_set==1)<a href="#" onclick="event.preventDefault(); deletePoster();"><span class="close">&times;</span></a>@endif--}}
{{--                                        <img src="{{$video->poster}}">--}}

{{--                                    </div>--}}
{{--                                    <script>--}}
{{--                                        function deletePoster(){--}}
{{--                                            swal({--}}
{{--                                                title: "Are you sure?",--}}
{{--                                                text: "Once deleted, you will not be able to recover!",--}}
{{--                                                icon: "warning",--}}
{{--                                                buttons: true,--}}
{{--                                                dangerMode: true,--}}
{{--                                            })--}}
{{--                                                .then((willDelete) => {--}}
{{--                                                    if (willDelete) {--}}
{{--                                                            document.getElementById('delete-poster-form').submit();--}}
{{--                                                    } else {--}}
{{--                                                        swal("Your file is safe!");--}}
{{--                                                    }--}}
{{--                                                });--}}
{{--                                        }--}}

{{--                                    </script>--}}
                                </div>
                                <button type="submit" class="btn btn-primary pull-right" id="edit-video">Submit</button>
                                <div class="clearfix"></div>
                            </form>
                            <form action="{{route('admin.delete-poster',[$video->id])}}" id="delete-poster-form" method="post">
                                @csrf
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('script')
    <script>
        $('#poster').fileinput({
            theme: 'fas',
            allowedFileExtensions: ["jpg", "png", "jpeg"],

            initialPreview: [
                "<img src='{{$video->poster}}' class='file-preview-image' alt=''>",

            ],
            initialPreviewConfig:[{

                    caption: "poster.jpeg",
                    url: "/admin/delete-video-poster/{{$video->id}}",
                    key: {{$video->id}},
                    extra:{
                        _token: '{{csrf_token()}}'
                    }
            }]
        });

        $('#package').on('change',function (){
           $('#category').empty();
        });
        $('#package').select2({
            ajax: {
                url: path+'/admin/get-selected-packages-list',
                data: function (params) {
                    var query = {
                        search: params.term,
                    }
                    console.log(query)
                    return query;
                },
                processResults: function (data) {
                    // Transforms the top-level key of the response object from 'items' to 'results'

                    console.log(data)
                    return {
                        results: data
                    };
                }
            }
        });
        $('#category').select2({
            ajax: {
                url: path+'/admin/get-package-categories-id-list',
                data: function (params) {
                    var query = {
                        name: $('#package').val(),
                        search: params.term,
                    }
                    console.log(query)
                    return query;
                },
                processResults: function (data) {
                    // Transforms the top-level key of the response object from 'items' to 'results'

                    console.log(data)
                    return {
                        results: data
                    };
                }
            }
        });
        $('#category').on('change',function () {
            $('#sub_category').empty();
        });
            $('#sub_category').select2({
            ajax: {
                url: '{{route('admin.getPackageCategoriesId')}}',
                data: function (params) {
                    var query = {
                        name: $('#package').val(),
                        search: params.term,
                    }
                    console.log(query)
                    return query;
                },
                processResults: function (data) {
                    // Transforms the top-level key of the response object from 'items' to 'results'

                    console.log(data)
                    return {
                        results: data
                    };
                }
            }
        });
    </script>
@endsection
