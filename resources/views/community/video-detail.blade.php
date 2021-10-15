@extends('layouts.app')
@section('title','Video Detail')
@section('content')
    @include('partials.banner')

    <!--// Main Content \\-->
    <div class="lifestyle-main-content">

        <!--// Main Section \\-->
        <div class="lifestyle-main-section">
            <div class="container">
                <div class="row">
                    <div class="col-md-12">
                        <div class="lifestyle-wrapper">
                            <a href="{{route('community.subscribed-package')}}" class="submit">Back</a>
                            <span class="pull-right" style="color: #E43D4E; font-size: 16px;"><a style="color: #E43D4E" href="{{route('community.subscribed-package')}}">{{$video->subCategory->category->package->name}}</a> <i style="font-size: 20px; display: inline-block; vertical-align: middle; margin: -5px 5px 0;" class="fa fa-angle-right"></i> <a style="color: #E43D4E" href="{{route('community.subscribed-package')}}">{{$video->subCategory->category->name}}</a><i style="font-size: 20px; display: inline-block; vertical-align: middle; margin: -5px 5px 0;" class="fa fa-angle-right"></i> <a style="color: #E43D4E" href="{{route('community.subscribed-package')}}">{{$video->subCategory->name}}</a></span>
                            <div class="clearfix"></div>
                            <div class="video-section">
                                <div id="video-details-div" style="width:1280px;max-width:100%;height:auto;"></div>
                                <script>
                                    (function(v,i,d,e,o){v[o]=v[o]||{}; v[o].add = v[o].add || function V(a){ (v[o].d=v[o].d||[]).push(a);};
                                        if(!v[o].l) { v[o].l=1*new Date(); a=i.createElement(d), m=i.getElementsByTagName(d)[0];
                                            a.async=1; a.src=e; m.parentNode.insertBefore(a,m);}
                                    })(window,document,"script","https://cdn-gce.vdocipher.com/playerAssets/1.6.10/vdo.js","vdo");
                                    vdo.add({
                                        otp: '{{$responsesCollection[0]['otp']}}',
                                        playbackInfo: '{{$responsesCollection[0]['playbackInfo']}}',
                                        theme: "9ae8bbe8dd964ddc9bdb932cca1cb59a",
                                        container: document.querySelector( "#video-details-div"),
                                    });
                                </script>
                                {{--                            <video poster="{{$video->poster}}" playsinline controls="" preload="auto" src="/images/fight.mp4"><source type="video/mp4" src="/images/fight.mp4"></video>--}}

                                <div class="clearfix"></div>
                            </div>
                            <div class="video-details">
                                <h2>{{$video->title}}</h2>
                                <p><strong>Description:</strong> {{$video->description}}</p>
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


