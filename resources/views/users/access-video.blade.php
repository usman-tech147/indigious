
@extends('layouts.app')
@section('title','Access Video')

@section('content')
    @include('partials.banner')

<!--// Main Content \\-->
<div class="lifestyle-main-content">
    <!--// Main Section \\-->
    <div class="lifestyle-main-section">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="section-title">
                        <h2>Package <span>Name</span></h2>
                        <p>It is a long established fact that a reader will be distracted by the readable content of a page when looking at its layout.</p>
                    </div>
                    <div class="clearfix"></div>
                    <div class="access-videos">
                        <ul class="row">
                            <li class="col-md-6">
                                <div class="access-videos-text">
                                    <video poster="/images/poster1.jpg" playsinline controls="" preload="auto" src="/images/fight.mp4"><source type="video/mp4" src="images/fight.mp4"></video>
                                    <section>
                                        <h2>1- Video Name</h2>
                                        <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged.</p>
                                    </section>
                                </div>
                            </li>
                            <li class="col-md-6">
                                <div class="access-videos-text">
                                    <video poster="/images/poster1.jpg" playsinline controls="" preload="auto" src="/images/fight.mp4"><source type="video/mp4" src="/images/fight.mp4"></video>
                                    <section>
                                        <h2>2- Video Name</h2>
                                        <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged.</p>
                                    </section>
                                </div>
                            </li>
                            <li class="col-md-6">
                                <div class="access-videos-text">
                                    <video poster="/images/poster1.jpg" playsinline controls="" preload="auto" src="/images/fight.mp4"><source type="video/mp4" src="/images/fight.mp4"></video>
                                    <section>
                                        <h2>3- Video Name</h2>
                                        <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged.</p>
                                    </section>
                                </div>
                            </li>
                            <li class="col-md-6">
                                <div class="access-videos-text">
                                    <video poster="/images/poster1.jpg" playsinline controls="" preload="auto" src="/images/fight.mp4"><source type="video/mp4" src="/images/fight.mp4"></video>
                                    <section>
                                        <h2>4- Video Name</h2>
                                        <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged.</p>
                                    </section>
                                </div>
                            </li>
                        </ul>
                    </div>
                    <div class="pagination-wrap">
                        <ul>
                            <li><a href="#"><i class="fa fa-long-arrow-left"></i></a></li>
                            <li><a href="#">1</a></li>
                            <li><a href="#" class="active">2</a></li>
                            <li><a href="#">3</a></li>
                            <li><a href="#"><i class="fa fa-long-arrow-right"></i></a></li>
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
