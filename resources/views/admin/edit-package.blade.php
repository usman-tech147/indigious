@extends('admin.layouts.app')
@section('title','Edit Package')

@section('content')
    <div class="app-main__inner">
        <div class="card-header-tab card-header">
            <div class="card-header-title font-size-lg text-capitalize font-weight-normal"><i class="pe-7s-server mr-3 text-muted opacity-6" style="font-size: 35px; color: #E43D4E !important;"> </i>Edit Package Details</div>
        </div>

        <div class="main-card mb-3 card">
            <div class="card-body">
                <div class="tabs-animation">
                    <div class="row">
                        <div class="col-md-12">
                            @include('partials.flash-message')
                            <form action="{{route('admin.edit-package',$package->id)}}" method="POST" id="edit-package" enctype="multipart/form-data">
                                @csrf
                                @method('PUT')
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Package Name</label>
                                            <input type="text" name="package_name" class="form-control" placeholder="" value="{{$package->name}}">
                                            @error('package_name')
                                            <label class="error" for="package_name">{{$message}}</label>
                                            @enderror
                                        </div>
                                    </div>

                                    {{--                                    <div class="col-md-4">--}}
                                    {{--                                        <div class="form-group">--}}
                                    {{--                                            <label>Package Price ($)/Day</label>--}}
                                    {{--                                            <input type="text" disabled class="form-control" name="price" placeholder="" value="{{$package->price}}">--}}
                                    {{--                                        </div>--}}
                                    {{--                                    </div>--}}
                                    {{--                                    <div class="col-md-4">--}}
                                    {{--                                        <div class="form-group">--}}
                                    {{--                                            <label>Package Price ($)/Month</label>--}}
                                    {{--                                            <input type="text"  class="form-control" name="price" placeholder="" value="{{$package->price}}">--}}
                                    {{--                                            @error('price')--}}
                                    {{--                                            <label class="error" for="price">{{$message}}</label>--}}
                                    {{--                                            @enderror--}}
                                    {{--                                        </div>--}}
                                    {{--                                    </div>--}}
                                    {{--                                    <div class="col-md-4">--}}
                                    {{--                                        <div class="form-group">--}}
                                    {{--                                            <label>Package Price ($)/6 Month</label>--}}
                                    {{--                                            <input type="text"  class="form-control" name="price_six" placeholder="" value="{{$package->price_six}}">--}}
                                    {{--                                            @error('price_six')--}}
                                    {{--                                            <label class="error" for="price_six">{{$message}}</label>--}}
                                    {{--                                            @enderror--}}
                                    {{--                                        </div>--}}
                                    {{--                                    </div>--}}
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Package Price ($)/Year</label>
                                            <input type="text"  class="form-control" name="price_year" placeholder="" value="{{$package->price_year}}">
                                            @error('price_year')
                                            <label class="error" for="price_six">{{$message}}</label>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-4">

                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Thumbnail</label>
                                            <input type="file" accept="image/jpeg,image/png,image/jpg" name="thumbnail" id="thumbnail" class="form-control">
                                            @error('thumbnail')
                                            <label id="thumbnail-error" class="error" for="thumbnail">{{$message}}</label>
                                            @enderror
                                            <label id="thumbnail-error" class="error" for="thumbnail" style="display: none"></label>

                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Free Video</label>
                                            <input type="file" name="free_video_1" id="free_video_1" accept="video/*" class="form-control" style="overflow: hidden">
                                            <label id="free_video_1-error" class="error" for="free_video_1"  style="display:none;">@error('free_video_1')Video is required. @enderror</label>
                                            @error('free_video_1')<label id="free_video_1-error" class="error" for="free_video_1" >{{$message}}</label>@enderror

                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Free Video</label>
                                            <input type="file" name="free_video_2" id="free_video_2" accept="video/*" class="form-control" style="overflow: hidden">
                                            <label id="free_video_2-error" class="error" for="free_video_2"  style="display:none;">@error('free_video_2')Video is required. @enderror</label>
                                            @error('free_video_2')<label id="free_video_2-error" class="error" for="free_video_2" >{{$message}}</label>@enderror
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label>Add Details</label>
                                            <textarea class="form-control" placeholder="" name="detail">{{$package->detail}}</textarea>
                                            @error('detail')
                                            <label class="error" for="detail">{{$message}}</label>
                                            @enderror
                                        </div>
                                    </div>

                                </div>
                                <button type="submit" class="btn btn-primary pull-right" >Submit</button>
                                <div class="clearfix"></div>
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
        $('#thumbnail').fileinput({
            theme: 'fas',

            allowedFileExtensions: ["jpg", "png", "jpeg"],
            initialPreview: [
                "<img src='{{asset('uploads/package/'.$package->thumbnail)}}' class='file-preview-image' alt=''>",
            ],
            initialPreviewShowDelete: false

        })
        $('#free_video_1').fileinput({
            theme: 'fas',
            allowedFileExtensions: ["mp4", "avi", "mkv", "mov"],
            @if(!empty($package->free_video_1))

            // initialPreviewShowDelete: false,
            initialPreview: [
                // VIDEO DATA
                "{{asset('uploads/package/'.$package->free_video_1)}}",

            ],
            initialPreviewAsData: true, // identify if you are sending preview data only and not the raw markup
            initialPreviewFileType: 'image', // image is the default and can be overridden in config below
            initialPreviewConfig: [
                {
                    type: "video",
                    filetype: "video/mp4",
                    url: "{{route('admin.delete-free-video',$package->id)}}",
                    key: 1,
                    extra: {
                        _token:"{{csrf_token()}}"
                    },
                    filename: '{{$package->free_video_1}}' // override download filename
                },
            ],
            @endif
        })
        $('#free_video_2').fileinput({
            theme: 'fas',
            allowedFileExtensions: ["mp4", "avi", "mkv", "mov"],
            @if(!empty($package->free_video_2))
            // initialPreviewShowDelete: false,
            initialPreview: [
                // VIDEO DATA
                "{{asset('uploads/package/'.$package->free_video_2)}}",

            ],
            initialPreviewAsData: true, // identify if you are sending preview data only and not the raw markup
            initialPreviewFileType: 'image', // image is the default and can be overridden in config below
            initialPreviewConfig: [
                {
                    type: "video",
                    filetype: "video/mp4",
                    url: "{{route('admin.delete-free-video',$package->id)}}",
                    key: 2,
                    extra: {
                        _token:"{{csrf_token()}}"
                    },
                    filename: '{{$package->free_video_2}}' // override download filename
                },
            ],
            @endif

        })
    </script>
@endsection
