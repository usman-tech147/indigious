@extends('admin.layouts.app')
@section('title','Add new video')

@section('content')
    <div class="app-main__inner">
        <div class="card-header-tab card-header">
            <div class="card-header-title font-size-lg text-capitalize font-weight-normal"><i class="pe-7s-film mr-3 text-muted opacity-6" style="font-size: 35px; color: #E43D4E !important;"> </i>Upload New Video</div>
        </div>
        <div class="main-card mb-3 card">
            <div class="card-body">
                <div class="tabs-animation">
                    <div class="row">
                        <div class="col-md-12">
                            @include('partials.flash-message')

                            <form action="{{route('admin.new-video')}}" method="POST" enctype="multipart/form-data" id="upload-new-video-form">
                                @csrf
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Select Package</label>
                                            <select name="package" id="package" class="form-control">
                                                <option value="" disabled selected>Select Package</option>
                                                @foreach($packages as $package)
                                                <option value="{{$package->id}}" @if($selectedPackage && $selectedPackage->id==$package->id) selected @endif >{{$package->name}}</option>
                                                @endforeach
                                            </select>
                                            <p class="text-danger hide" id="video-package-error"></p>
                                            <label id="package-error" class="error" for="package" style="display: none"></label>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Select Category</label>
                                            <select name="category" id="category" class="form-control">

                                            </select>
                                            <p class="text-danger hide" id="video-category-error"></p>
                                            <label id="category-error" class="error" for="category" style="display: none"></label>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Select Sub Category</label>
                                            <select name="sub_category" id="sub_category" class="form-control">

                                            </select>
                                            <p class="text-danger hide" id="video-sub_category-error"></p>
                                            <label id="sub_category-error" class="error" for="sub_category" style="display: none"></label>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Video Name</label>
                                            <input type="text" placeholder="" name="video_name" id="video_name" class="form-control"  value="{{old('video_name')}}">
                                            <label class="error hide" id="video-name-error" for="video_name"></label>
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Select Video</label>
                                            <input type="file" placeholder="" accept="video/mp4,video/x-matroska,video/avi" name="video" id="video" style="overflow: hidden" class="form-control">
                                            <label id="video-error" class="error" for="video" style="display: none"></label>
                                            <div class="progress mt-2"></div>
{{--                                            <label class="error hide" id="video-error" for="video" ></label>--}}

                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Select Video Poster</label>
                                            <input type="file" placeholder="" accept="image/jpeg,image/jpg,image/png" name="poster" id="poster" class="form-control" >

                                            <label class="error hide" id="video-poster-error" ></label>

                                        </div>
                                        <label id="poster-error" class="error" for="poster" style="display: none"></label>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label>Description</label>
                                            <textarea class="form-control" placeholder="" name="description" id="description">{{old('description')}}</textarea>
                                            <label class="error hide" id="video-description-error" for="description"></label>
                                        </div>
                                    </div>
                                </div>
                                <button type="submit" class="btn btn-primary pull-right" id="upload-new-video" >Submit</button>
                                <div class="clearfix"></div>
                                <div id="video-loader" class="row justify-content-center hide">
                                    <img src="/images/loader.gif"  alt="">
                                </div>
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
        $(document).ready(function (){
            @if($selectedPackage)
                packageChange($('#package').val())
            @endif
                function packageChange(package_id){
                $('#category').empty();
                let token=$('meta[name="_token"]').attr('content');
                $.ajax({
                    url: path+'/admin/get-package-categories',
                    type: "POST",
                    data: {
                        package_id: package_id,
                        _token: token
                    },
                    success: function (response){
                        console.log(response);
                        response.forEach(function (r){
                            $('#category').append('<option value="'+r.id+'">'+r.name+'</option>>')
                        });
                    },
                    error: function (response){
                        console.log(response)
                    }
                })
            }
            $('#package').on('change',function (){
                let package_id=$(this).val();
                packageChange(package_id);
            });
            $('#upload-new-video').on('click', function (event) {
                event.preventDefault();
                if($('#upload-new-video-form').valid()){
                    if (!$('#video-name-error').hasClass('hide')) {
                        $('#video-name-error').addClass('hide')
                    }
                    if (!$('#video-sub_category-error').hasClass('hide')) {
                        $('#video-sub_category-error').addClass('hide')
                    }
                    if (!$('#video-category-error').hasClass('hide')) {
                        $('#video-category-error').addClass('hide')
                    }
                    if (!$('#video-description-error').hasClass('hide')) {
                        $('#video-description-error').addClass('hide')
                    }
                    if (!$('#video-error').hasClass('hide')) {
                        $('#video-error').addClass('hide')
                    }
                    if (!$('#video-package-error').hasClass('hide')) {
                        $('#video-package-error').addClass('hide')
                    }
                    if (!$('#video-poster-error').hasClass('hide')) {
                        $('#video-poster-error').addClass('hide')
                    }
                    let fd = new FormData(document.getElementById('upload-new-video-form'));
                    $('#upload-new-video').attr('disabled', true);
                    var data = [];
                    for (var i = 0; i < 100000; i++) {
                        var tmp = [];
                        for (var i = 0; i < 100000; i++) {
                            tmp[i] = 'hue';
                        }
                        data[i] = tmp;
                    }
                    ;
                    if ($('#video-loader').hasClass('hide')) {
                        $('#video-loader').removeClass('hide');
                    }
                    $.blockUI({ message: '<img src="/images/loader1.gif" class="loader-size" />', css: { backgroundColor: 'transparent', border:'transparent'} });

                    $.ajax(
                        {
                            type: 'POST',
                            url: path+'/admin/new-video',
                            cache: false,
                            processData: false,
                            contentType: false,
                            data: fd,
                            xhr: function () {
                                if ($('.progress').hasClass('hide')) {
                                    $('.progress').removeClass('hide');
                                }
                                var xhr = new window.XMLHttpRequest();
                                xhr.upload.addEventListener("progress", function (evt) {
                                    if (evt.lengthComputable) {
                                        var percentComplete = evt.loaded / evt.total;
                                        console.log(percentComplete);
                                        $('.progress').css({
                                            width: percentComplete * 100 + '%'
                                        });
                                        $('.progress').text(
                                            (percentComplete * 100).toFixed(1) + '%'
                                        );

                                    }
                                }, false);
                                xhr.addEventListener("progress", function (evt) {
                                    if (evt.lengthComputable) {
                                        var percentComplete = evt.loaded / evt.total;
                                        $('.progress').css({
                                            width: percentComplete * 100 + '%'
                                        });
                                    }
                                }, false);
                                return xhr;
                            },
                            success: function (response) {
                                $('#category').empty();
                                $('#displayImageOnUpload').hide();
                                $('.label-for-image').hide();
                                console.log(response)
                                $('#video-loader').addClass('hide');
                                $('#upload-new-video-form').trigger('reset');
                                $('#upload-new-video').removeAttr('disabled');

                                if (!$('#video-name-error').hasClass('hide')) {
                                    $('#video-name-error').addClass('hide')
                                }
                                if (!$('#video-description-error').hasClass('hide')) {
                                    $('#video-description-error').addClass('hide')
                                }
                                if (!$('#video-error').hasClass('hide')) {
                                    $('#video-error').addClass('hide')
                                }
                                if (!$('#video-package-error').hasClass('hide')) {
                                    $('#video-package-error').addClass('hide')
                                }
                                if (!$('#video-category-error').hasClass('hide')) {
                                    $('#video-category-error').addClass('hide')
                                }
                                if (!$('#video-sub_category-error').hasClass('hide')) {
                                    $('#video-sub_category-error').addClass('hide')
                                }
                                if (!$('#video-poster-error').hasClass('hide')) {
                                    $('#video-poster-error').addClass('hide')
                                }
                                console.log(response)
                                if (response.hasOwnProperty('error')) {
                                    swal({
                                        title: "Uploading Failed!",
                                        text: "Some Error Has Occurred While Uploading Video!",
                                        icon: "error",
                                    })
                                }
                                if (response.hasOwnProperty('message')) {
                                    swal({
                                        title: "Video Uploaded!",
                                        text: "Video is uploaded it will be available to watch after processing",
                                        icon: "success",
                                    })
                                }
                                $('.progress').addClass('hide');
                            },

                            error: function (response) {
                                console.log(response)

                                $('#video-loader').addClass('hide');

                                $('.progress').addClass('hide');

                                $('#upload-new-video').removeAttr('disabled');
                                if (response.hasOwnProperty('responseJSON')) {
                                    if (response.responseJSON.hasOwnProperty('errors')) {
                                        let errors = response.responseJSON.errors;
                                        if (errors.hasOwnProperty('video_name')) {
                                            if ($('#video-name-error').hasClass('hide')) {
                                                $('#video-name-error').removeClass('hide')
                                                $('#video-name-error').text(errors.video_name)
                                            }
                                        } else {
                                            $('#video-name-error').addClass('hide')
                                        }
                                        if (errors.hasOwnProperty('poster')) {
                                            if ($('#video-poster-error').hasClass('hide')) {
                                                $('#video-poster-error').removeClass('hide')
                                                $('#video-poster-error').text(errors.poster)
                                            }
                                        } else {
                                            $('#video-poster-error').addClass('hide')
                                        }
                                        if (errors.hasOwnProperty('video')) {
                                            if ($('#video-error').hasClass('hide')) {
                                                $('#video-error').removeClass('hide')
                                                $('#video-error').text(errors.video)
                                            }
                                        } else {
                                            $('#video-error').addClass('hide')
                                        }
                                        if (errors.hasOwnProperty('package')) {
                                            if ($('#video-package-error').hasClass('hide')) {
                                                $('#video-package-error').removeClass('hide')
                                                $('#video-package-error').text(errors.package)
                                            }
                                        } else {
                                            $('#video-package-error').addClass('hide')

                                        }
                                        if (errors.hasOwnProperty('description')) {
                                            if ($('#video-description-error').hasClass('hide')) {
                                                $('#video-description-error').removeClass('hide')
                                                $('#video-description-error').text(errors.description)
                                            }
                                        } else {
                                            $('#video-description-error').addClass('hide')

                                        }
                                        if (errors.hasOwnProperty('category')) {
                                            if ($('#video-category-error').hasClass('hide')) {
                                                $('#video-category-error').removeClass('hide')
                                                $('#video-category-error').text(errors.category)
                                            }
                                        } else {
                                            $('#video-category-error').addClass('hide')

                                        }
                                        if (errors.hasOwnProperty('sub_category')) {
                                            if ($('#video-sub_category-error').hasClass('hide')) {
                                                $('#video-sub_category-error').removeClass('hide')
                                                $('#video-sub_category-error').text(errors.category)
                                            }
                                        } else {
                                            $('#video-sub_category-error').addClass('hide')

                                        }
                                    }
                                }
                            }
                        })
                }
            });
        })

        $('#poster').fileinput({
            theme: 'fas',

            allowedFileExtensions: ["jpg", "png", "jpeg"],
        });
        $('#video').fileinput({
            theme: 'fas',
            allowedFileExtensions: ["mp4", "avi", "mkv", "mov"],
        })
        // $('#package').select2({
        //     ajax: {
        //         url: path+'/admin/get-selected-packages-list',
        //         data: function (params) {
        //             var query = {
        //                 search: params.term,
        //             }
        //             console.log(query)
        //             return query;
        //         },
        //         processResults: function (data) {
        //             // Transforms the top-level key of the response object from 'items' to 'results'
        //
        //             console.log(data)
        //             return {
        //                 results: data
        //             };
        //         }
        //     }
        // });
        $('#package').select2();
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
        $('#sub_category').select2({
            ajax: {
                url: '{{route('admin.getPackageCategoriesId')}}',
                data: function (params) {
                    var query = {
                        name: $('#category').val(),
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
