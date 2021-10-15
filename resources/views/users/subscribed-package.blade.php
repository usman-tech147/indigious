@extends('layouts.app')
@section('title','Subscribed Packages')
@section('content')
@include('partials.banner')
    <!--// Main Content \\-->
    <div class="lifestyle-main-content">
        <!--// Main Section \\-->
        <div class="lifestyle-main-section">
            <div class="container">
                <div class="row">
                    {{--    sidebar--}}
                    @include('users.partials.side-bar')
                    <div class="col-md-9">
                        <div class="lifestyle-package-detail">
                            <figure>
                                <img src="{{asset('uploads/package/'.$package->thumbnail)}}" alt="">
                            </figure>
                            <section>
                                <h2>{{$package->name}}</h2>
                                <p>{{$package->detail}}</p>
                                <p> <strong>Price:
                                        <span>${{$package->price_year}}<small>/Year</small> </span>
                                    </strong>
                                </p>
                            </section>
                        </div>

                        <h2 class="form-title">Free Videos</h2>
                        @if($package->free_video_1 || $package->free_video_2)
                        <div class="category-list">
                            <ul class="row">
                                @if($package->free_video_1)
                                    <li class="col-md-6">
                                        <div >
                                            <video playsinline=""
                                                   controls=""
                                                   preload="auto"
                                                   src="{{asset('uploads/package/'.$package->free_video_1)}}"
                                                   style="width: 100%">
                                                <source type="video/*" src="{{asset('uploads/package/'.$package->free_video_1)}}">
                                            </video>

                                        </div>
                                    </li>
                                @endif
                                @if($package->free_video_2)
                                    <li class="col-md-6">
                                        <div >
                                            <video playsinline=""
                                                   controls=""
                                                   preload="auto"
                                                   src="{{asset('uploads/package/'.$package->free_video_2)}}"
                                                   style="width: 100%">
                                                <source type="video/*" src="{{asset('uploads/package/'.$package->free_video_2)}}">
                                            </video>
                                        </div>
                                    </li>
                                @endif
                            </ul>
                        </div>

                    @endif

                        <div class="category-list">
                            @forelse($categories as $category)
                                <h2 class="form-title">{{$category->name}} </h2>
                            <ul class="row">
                                @forelse($category->subCategories as $sub_category)
                                    <li class="col-md-12">
                                        <h4 class="suub-cat">{{$sub_category->name}} </h4>
                                    </li>
                                @forelse($sub_category->videos as $video)

                                    <li class="col-md-4">
                                    <div class="category-list-text">
                                        <figure>
                                            <div class="imageswrapper"><a href="{{route('user.video-information',$video->id)}}" style="background-image: url({{$video->poster}});"></a></div>
                                            <a href="{{route('user.video-information',$video->id)}}"  class="fa fa-play" ></a>
{{--                                            <a href="javascript:void(0)"  class="fa fa-play m-video" data-id="{{$video->id}}"></a>--}}
                                            <figcaption>
                                                <h2><a href="{{route('user.video-information',$video->id)}}">{{$video->title}}</a></h2>
                                            </figcaption>
                                        </figure>
                                    </div>

                                </li>
                                    @empty
                                        <div class="col-md-12 text-center">
                                            <h1>No data available</h1>
                                        </div>
                                    @endforelse
                                    @empty
                                        <div class="col-md-12 text-center">
                                            <h1>No data available</h1>
                                        </div>
                                    @endforelse
                                @empty
                                <div class="col-md-12 text-center">
                                    <h1>No data available</h1>
                                </div>
                                @endforelse
                            </ul>

                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!--// Main Section \\-->

    </div>
    <!--// Main Content \\-->
@endsection
