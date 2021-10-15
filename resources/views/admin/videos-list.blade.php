@extends('admin.layouts.app')
@section('title','Videos')

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
                            <form method="get">
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Package Name</label>
                                            <select name="package_name" id="search_package_name" class="form-control">

                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Category Name</label>
                                            <select  name="category_name" id="search_category_name" class="form-control" placeholder=""></select>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Sub Category Name</label>
                                            <select  name="sub_category_name" id="search_sub_category_name" class="form-control" placeholder=""></select>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Video Name</label>
                                            <select name="video_name" id="search_video_name" class="form-control" placeholder=""></select>
                                        </div>
                                    </div>
                                    <div class="col-md-4"><button type="submit" class="btn btn-primary pull-left" id="video-search" style="margin: 30px 0px 0px;">Search</button></div>
                                </div>
                                <div class="clearfix"></div>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="card-header-tab card-header">
                        <div class="card-header-title font-size-lg text-capitalize font-weight-normal"><i class="pe-7s-film mr-3 text-muted opacity-6" style="font-size: 35px; color: #E43D4E !important;"> </i>Videos</div>
                    </div>
                    <div class="card mb-3">
                        <div class="card-body">
                            <div class="access-videos">
                                <ul id="all-videos" class="row"></ul>
                            </div>
                            <div class="access-videos">
                                <ul id="example-3" class="pagination">

                                </ul>
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
        $(document).ready(function () {
            let token=$('meta[name="_token"]').attr('content');



            $(document).on('click', '.btn-delete-video', function () {
                console.log('button click')
                event.preventDefault();
                let id = $(this).data('id');
                confirmDelete(id);

            });

            $('#video-search').on('click', function (e) {
                e.preventDefault();
                let video_name = $('#search_video_name').val();
                let package_name = $('#search_package_name').val();
                let category_name = $('#search_category_name').val();

                getAllVideos(video_name, package_name,category_name)
            })

            function confirmDelete(id) {
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
                                url: path+"/admin/delete-video/" + id,
                                type: "POST",
                                data: {
                                    _token: token,
                                    _method: 'delete'

                                },
                                success: function (response){
                                    console.log(response)
                                    swal("Poof! Your file has been deleted!", {
                                        icon: "success",

                                    });
                                    getAllVideos('', '','');
                                },
                                error: function (response){
                                    console.log(response)
                                }
                            });


                        } else {
                            swal("Your file is safe!");
                        }
                    });

            }

            getAllVideos('', '','');


            function getAllVideos(video_name, package_name,category_name) {
                $.blockUI({ message: '<img src="/images/loader1.gif" class="loader-size" />', css: { backgroundColor: 'transparent', border:'transparent'} });
                // $.ajax({
                //     type: 'get',
                //     url: path+'/admin/get-all-videos',
                //     data:
                //         {
                //             video_name: video_name,
                //             package_name: package_name,
                //             category_name: category_name
                //         },
                //     success: function (response) {
                //         console.log(response)
                //         let videos = response.videos;
                //         let responses = response.response;
                //         let i = 0;
                //         $('#all-videos').empty();
                //         if(videos.length==0){
                //             $('#all-videos').append('<li class="col-md-12 text-center"><strong style="font-size: 20px;">No Data Available</strong></li>')
                //
                //         }else {
                //             videos.forEach(function (video) {
                //                 $('#all-videos').append('<li class="col-md-4"><div class="access-videos-text"><div id="embedBox-' + (i++) + '" style="width:1280px;max-width:100%;height:auto;"></div><section><h2>Package Name: ' + video.package_name + '</h2><h4>Video Title: ' + video.title + '</h4><p>Video Description: ' + video.description + '</p><a href="#"  data-id="' + video.id + '" class="btn-delete-video btn btn-primary">Delete</a><a href="/admin/edit-video-details/' + video.id + '" class="btn btn-primary">Edit</a></section></div></li>')
                //             });
                //         }
                //         (function (v, i, d, e, o) {
                //             v[o] = v[o] || {};
                //             v[o].add = v[o].add || function V(a) {
                //                 (v[o].d = v[o].d || []).push(a);
                //             };
                //             if (!v[o].l) {
                //                 v[o].l = 1 * new Date();
                //                 a = i.createElement(d), m = i.getElementsByTagName(d)[0];
                //                 a.async = 1;
                //                 a.src = e;
                //                 m.parentNode.insertBefore(a, m);
                //             }
                //         })(window, document, "script", "https://cdn-gce.vdocipher.com/playerAssets/1.6.10/vdo.js", "vdo");
                //         let j = 0;
                //         responses.forEach(function (r) {
                //             vdo.add({
                //                 otp: r.otp,
                //                 playbackInfo: r.playbackInfo,
                //                 theme: "9ae8bbe8dd964ddc9bdb932cca1cb59a",
                //                 container: document.querySelector("#embedBox-" + (j++) + ""),
                //             });
                //
                //         });
                //     },
                //     error: function (response) {
                //         console.log(response)
                //     }
                // })
                $('#example-3').pagination({
                    // total: 100, // Total number of data
                    current: 1, // Current page number
                    length: 9, // Data volume per page
                    size: 5, // Display the number of buttons

                    ajax: function(options, refresh, $target){
                        $.ajax({
                            url: '{{route('admin.getAllVideos')}}',
                            type: "get",
                            data:{
                                current: options.current,
                                length: options.length,
                                video_name: video_name,
                                package_name: package_name,
                                category_name: category_name
                            },
                            dataType: 'json'
                        }).done(function(response){
                            $.unblockUI();
                            // console.log(response)
                            let videos = response.videos;
                            let responses = response.response;
                            let i = 0;
                            $('#all-videos').empty();
                            if(videos.length==0){
                                $('#all-videos').append('<li class="col-md-12 text-center"><strong style="font-size: 20px;">No Data Available</strong></li>')

                            }else {
                                videos.forEach(function (video) {
                                    $('#all-videos').append('<li class="col-md-4"><div class="access-videos-text"><div id="embedBox-' + (i++) + '" style="width:1280px;max-width:100%;height:auto;"></div><section><h2>Package Name: ' + video.package_name + '</h2><h4>Video Title: ' + video.title + '</h4><p>Video Description: ' + video.description + '</p><a href="#"  data-id="' + video.id + '" class="btn-delete-video btn btn-primary">Delete</a><a href="/admin/edit-video-details/' + video.id + '" class="btn btn-primary">Edit</a></section></div></li>')
                                });
                            }
                            (function (v, i, d, e, o) {
                                v[o] = v[o] || {};
                                v[o].add = v[o].add || function V(a) {
                                    (v[o].d = v[o].d || []).push(a);
                                };
                                if (!v[o].l) {
                                    v[o].l = 1 * new Date();
                                    a = i.createElement(d), m = i.getElementsByTagName(d)[0];
                                    a.async = 1;
                                    a.src = e;
                                    m.parentNode.insertBefore(a, m);
                                }
                            })(window, document, "script", "https://cdn-gce.vdocipher.com/playerAssets/1.6.10/vdo.js", "vdo");
                            let j = 0;
                            responses.forEach(function (r) {
                                vdo.add({
                                    otp: r.otp,
                                    playbackInfo: r.playbackInfo,
                                    theme: "9ae8bbe8dd964ddc9bdb932cca1cb59a",
                                    container: document.querySelector("#embedBox-" + (j++) + ""),
                                });

                            });
                            refresh({
                                total: response.total,
                                length: response.length
                            });

                        }).fail(function(error){
                            $('#all-videos').append('<li class="col-md-12 text-center"><strong style="font-size: 20px;">Some Error Has Occured</strong></li>')

                        });
                    }
                });
            }
            $('#search_package_name').select2({
                ajax: {
                    url: '{{route('admin.getAllPackages')}}',
                    data: function (params) {
                        var query = {
                            name: $('#search_package_name').val(),
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

            $('#search_category_name').select2({
                ajax: {
                    url: '{{route('admin.getPackageCategories')}}',
                    data: function (params) {
                        var query = {
                            name: $('#search_package_name').val(),
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
            $('#search_sub_category_name').select2({
                ajax: {
                    url: '{{route('admin.getPackageCategorySubCategories')}}',
                    data: function (params) {
                        var query = {
                            name: $('#search_package_name').val(),
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



            $('#search_video_name').select2({
                ajax: {
                    url: '{{route('admin.getCategoryVideos')}}',
                    data: function (params) {
                        var query = {
                            name: $('#search_sub_category_name').val(),
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
        })
</script>
@endsection
