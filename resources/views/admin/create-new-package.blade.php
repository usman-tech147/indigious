@extends('admin.layouts.app')
@section('title','Add new package')

@section('content')
    <div class="app-main__inner">
        <div class="card-header-tab card-header">
            <div class="card-header-title font-size-lg text-capitalize font-weight-normal"><i class="pe-7s-server mr-3 text-muted opacity-6" style="font-size: 35px; color: #E43D4E !important;"> </i>Create New Package</div>
        </div>
        <div class="main-card mb-3 card">
            <div class="card-body">
                <div class="tabs-animation">
                    <div class="row">
                        <div class="col-md-12">
                                @include('partials.flash-message')
                            <form action="{{route('admin.create-new-package')}}" method="post" id="create-new-package" enctype="multipart/form-data">
                                @csrf
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Package Name</label>
                                            <input type="text" name="package_name" class="form-control" placeholder="" value="{{old('package_name')}}">
                                            @error('package_name')
                                            <label class="error" for="package_name">{{$message}}</label>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Package Price/Year ($)</label>
                                            <input type="text" class="form-control" name="price_year" placeholder="" value="{{old('price_year')}}">
                                            @error('price_year')
                                            <label class="error" for="price_year">{{$message}}</label>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-4">

                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">

                                            <label>Thumbnail</label>
                                            <input type="file" name="thumbnail" id="thumbnail" accept="image/jpeg,image/png,image/jpg" class="form-control" style="overflow: hidden">
                                            <label id="thumbnail-error" class="error" for="thumbnail"  style="display:none;">@error('thumbnail')Thumbnail is required. @enderror</label>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Free Video</label>
                                            <input type="file" name="free_video_1" id="free_video_1" accept="video/*" class="form-control" style="overflow: hidden">
                                            <label id="free_video_1-error" class="error" for="free_video_1"  style="display:none;"></label>
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
                                            <textarea class="form-control" placeholder="" name="detail">{{old('detail')}}</textarea>
                                            @error('detail')
                                            <label class="error" for="detail">{{$message}}</label>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                <button type="submit" class="btn btn-primary pull-right" id="create-package-submit">Submit</button>
                                <div class="clearfix"></div>
                            </form>
                        </div>

                        <div>
                            <img id="page_loader" src="{{asset('images/loader.gif')}}" alt="" style="display: none">
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

        })
        $('#free_video_1').fileinput({
            theme: 'fas',
            allowedFileExtensions: ["mp4", "avi", "mkv", "mov"],

        })
        $('#free_video_2').fileinput({
            theme: 'fas',
            allowedFileExtensions: ["mp4", "avi", "mkv", "mov"],

        })
    </script>
@endsection
