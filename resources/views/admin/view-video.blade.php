@extends('admin.layouts.app')
@section('title','View Video')

@section('content')
    <div class="app-main__inner">
        <div class="tabs-animation">
            <div class="row">
                <div class="col-md-12">
                    <div class="card-header-tab card-header">
                        <div class="card-header-title font-size-lg text-capitalize font-weight-normal"><i class="pe-7s-server mr-3 text-muted opacity-6" style="font-size: 35px; color: #e43d4e !important;"> </i>Video Details</div>
                    </div>
                    <div class="main-card mb-3 card">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="pakage-details-wrap">
                                        <figure><div id="embedBox" style="width:720px;max-width:100%;height:auto;"></div>
                                        </figure>
                                        <section>
                                            <ul>
                                                <li>
                                                    <h5>Title: </h5>
                                                    <p>{{$video->title}}</p>
                                                </li>
                                                <li>
                                                    <h5>Details: </h5>
                                                    <p>{{$video->description}}</p>
                                                </li>
                                            </ul>
                                        </section>
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
        (function(v,i,d,e,o){v[o]=v[o]||{}; v[o].add = v[o].add || function V(a){ (v[o].d=v[o].d||[]).push(a);};
            if(!v[o].l) { v[o].l=1*new Date(); a=i.createElement(d), m=i.getElementsByTagName(d)[0];
                a.async=1; a.src=e; m.parentNode.insertBefore(a,m);}
        })(window,document,"script","https://cdn-gce.vdocipher.com/playerAssets/1.6.10/vdo.js","vdo");
        vdo.add({
            otp: '{{$responsesCollection[0]['otp']}}',
            playbackInfo: '{{$responsesCollection[0]['playbackInfo']}}',
            theme: "9ae8bbe8dd964ddc9bdb932cca1cb59a",
            container: document.querySelector( "#embedBox" ),
        });
    </script>
@endsection
<div class="modal fade" id="videoModal" tabindex="-1" role="dialog" aria-labelledby="videoModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content bg-transparent">
            <div class="video-popup">
            </div>
        </div>
    </div>
</div>





