@extends('admin.layouts.app')
@section('title','Package Detail')

@section('content')
    <div class="app-main__inner">
        <div class="tabs-animation">
            <div class="row">
                <div class="col-md-12">
                    <div class="card-header-tab card-header">
                        <div class="card-header-title font-size-lg text-capitalize font-weight-normal"><i class="pe-7s-server mr-3 text-muted opacity-6" style="font-size: 35px; color: #e43d4e !important;"> </i>Package Details</div>
                    </div>
                    <div class="main-card mb-3 card">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-12">
                                    <a href="{{route('admin.new-video-upload',$package->id)}}" class="btn btn-primary pull-right">Add New Video</a>
                                </div>
                                <div class="col-md-12">
                                    <div class="pakage-details-wrap">
                                        <figure><img src="{{asset('uploads/package/'.$package->thumbnail)}}"  alt=""></figure>
                                        <section>
                                            <ul>
                                                <li>
                                                    <h5>Name: </h5>
                                                    <p>{{$package->name}}</p>
                                                </li>
                                                <li>
                                                    <h5>Price: </h5>

{{--                                                    <p>${{$package->price}} <small>/Month</small> </p>--}}
{{--                                                    <p>${{$package->price_six}} <small>/6 Month</small> </p>--}}
                                                    <p>${{$package->price_year}} <small>/Year</small> </p>
                                                </li>
                                                <li>
                                                    <h5>Details: </h5>
                                                    <p>{{$package->detail}}</p>
                                                </li>
                                                    @if($package->free_video_1 || $package->free_video_2)

                                                                @if($package->free_video_1)
                                                                    <li class="col-md-4">
                                                                        <div >
                                                                            <video playsinline=""
                                                                                   controls=""
                                                                                   preload="auto"
                                                                                   src="{{asset('uploads/package/'.$package->free_video_1)}}"
                                                                                   style="width: 100%"

                                                                            >
                                                                                <source type="video/*" src="{{asset('uploads/package/'.$package->free_video_1)}}">
                                                                            </video>

                                                                        </div>
                                                                    </li>
                                                                @endif
                                                                @if($package->free_video_2)
                                                                    <li class="col-md-4">
                                                                        <div >
                                                                            <video playsinline=""
                                                                                   controls=""
                                                                                   preload="auto"
                                                                                   src="{{asset('uploads/package/'.$package->free_video_2)}}"
                                                                                   style="width: 100%"

                                                                            >
                                                                                <source type="video/*" src="{{asset('uploads/package/'.$package->free_video_2)}}">
                                                                            </video>
                                                                        </div>
                                                                    </li>
                                                                @endif
                                                    @endif
                                            </ul>
                                        </section>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="main-card mb-3 card">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-12">
                                    <h2 class="cat-title">Videos</h2>
                                </div>
                                <div class="col-md-12">
                                    <div id="accordion" class="cat-video-list">
                                    @forelse($package->categories as $key=>$category)
                                      <div class="card">
                                        <div class="card-header" id="heading{{$key}}">
                                          <h5 class="mb-0">
                                            <button class="btn btn-link" data-toggle="collapse" data-target="#collapse{{$key}}" aria-expanded="true" aria-controls="collapse{{$key}}">{{$category->name}}</button>
                                          </h5>
                                        </div>
                                        <div id="collapse{{$key}}" class="collapse @if($key==0)show @endif" aria-labelledby="heading{{$key}}" data-parent="#accordion">
                                          <div class="card-body">
                                            @forelse($category->subCategories as $subCategory)
                                            <div class="subcat-wrap">
                                                <h6>{{$subCategory->name}}</h6>
                                                <p>{{$subCategory->detail}}</p>
                                                <table class="table table-bordered">
                                                    <tr>
                                                        <th>#</th>
                                                        <th>Video Title</th>
                                                        <th>Action</th>
                                                    </tr>
                                                @forelse($subCategory->videos  as $key2=>$video)
                                                    <tr data-id="{{$video->id}}" data-position="{{$video->position}}" class="change-pos">
                                                        <td style="width: 33.33%">
                                                            {{$key2+1}}
                                                        </td>
                                                        <td style="width: 33.33%">
                                                            {{$video->title}}
                                                        </td>
                                                        <td style="width: 33.33%">
                                                            <a href="/admin/view-video/{{$video->id}}" class="tag">View</a>
                                                            <a href="/admin/edit-video-details/{{$video->id}}" class="tag">Edit</a>
                                                            <a href="#" data-id="{{$video->id}}" id="delete-video" class="tag">Delete</a>
                                                        </td>
                                                    </tr>
                                                @empty
                                                    <tr>
                                                        <td colspan="3" class="text-center">No Data Available</td>
                                                    </tr>
                                                @endforelse
                                            </table>
                                            </div>
                                              @empty
                                                  <div class="subcat-wrap">
                                                  <h6 style="text-align: center;font-weight: bold;font-size: 22px;margin: 0px;">No Subcategory Found.</h6>
                                                  </div>
                                              @endforelse
                                          </div>
                                        </div>
                                      </div>
                                      @empty
                                        <div class="card">
                                            <div class="collapse show">
                                                <div class="card-body">
                                                    <h5 style="text-align: center;"><strong>No Video Available</strong></h5>
                                                </div>
                                            </div>
                                        </div>
                                      @endforelse
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>

    </script>
@endsection
{{--<div class="modal fade" id="videoModal" tabindex="-1" role="dialog" aria-labelledby="videoModalLabel" aria-hidden="true">--}}
{{--    <div class="modal-dialog" role="document">--}}
{{--        <div class="modal-content bg-transparent">--}}
{{--            <div class="video-popup">--}}
{{--            </div>--}}
{{--        </div>--}}
{{--    </div>--}}
{{--</div>--}}

@section('script')
    <script>
        $('.sort-table tbody').sortable({
            update: function (event,ui){
                // console.log($(this).children())
                $(this).children().each(function (index){
                    if($(this).attr('data-position') != (index+1)){
                        $(this).attr('data-position',(index+1)).addClass('updated');
                    }
                });
                saveNewPositions();
            }
        });
        let token=$('meta[name="_token"]').attr('content');
        function saveNewPositions(){
            let positions=[];
            $('.updated').each(function (){
                positions.push([$(this).attr('data-id'),$(this).attr('data-position')]);
                $(this).removeClass('updated');
            });
            // console.log(positions)
            $.blockUI({ message: '<img src="/images/loader1.gif" class="loader-size" />', css: { backgroundColor: 'transparent', border:'transparent'} });

            $.ajax({
                url: path+'/admin/update-video-positions',
                type: 'post',
                data: {
                    _token: token,
                    positions: positions
                },
                success: function (response){
                    // console.log('position updated')
                    // console.log(response)
                    $('.change-pos').each(function (value){
                        $(this).children().first().text(value+1)
                    })
                },
                error: function (response){
                    console.log('failed')
                    // console.log(response)
                }

            })
        }
        $('#delete-video').on('click',function (event){
            event.preventDefault();
            let id=$(this).attr('data-id');
            confirmDelete(id);
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
                                }
                            });
                            swal("Poof! Your file has been deleted!", {
                                icon: "success",

                            });
                            window.location.href='';
                        } else {
                            swal("Your file is safe!");
                        }
                    });

            }
        });
    </script>
@endsection



