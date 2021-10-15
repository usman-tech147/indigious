@extends('admin.layouts.app')
@section('title','Dashboard')

@section('content')
<div class="app-main__inner">
    <div class="card-header-tab card-header">
        <div class="card-header-title font-size-lg text-capitalize font-weight-normal"><i class="pe-7s-map mr-3 text-muted opacity-6" style="font-size: 35px; color: #E43D4E !important;"> </i>Dashboard</div>
    </div>
    <div class="main-card mb-3 card">
        <div class="card-body">
            <div class="tabs-animation">
                <div class="row">
                    <div class="col-md-12 col-lg-4">
                        <ul class="list-group list-group-flush">
                            <li class="bg-transparent list-group-item">
                                <div class="widget-content p-0">
                                    <div class="widget-content-outer">
                                        <div class="widget-content-wrapper">
                                            <div class="widget-content-left">
                                                <div class="widget-heading">Total Sales</div>
                                                <div class="widget-subheading">This year</div>
                                            </div>
                                            <div class="widget-content-right">
                                                <div class="widget-numbers "><a class="text-success" href="{{'/admin/payments'}}">${{\App\Models\Payment::whereRaw("DATE_FORMAT(created_at,'%Y') like ".(date('Y')))->sum('subtotal')}}</a></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </li>
                            <li class="bg-transparent list-group-item">
                                <div class="widget-content p-0">
                                    <div class="widget-content-outer">
                                        <div class="widget-content-wrapper">
                                            <div class="widget-content-left">
                                                <a href="{{route('admin.users')}}" style="color: black"> <div class="widget-heading">Users</div>
                                                <div class="widget-subheading">Total Users Subscription</div>
                                                </a>
                                            </div>
                                            <div class="widget-content-right">
                                                <div class="widget-numbers "><a class="text-success" href="{{route('admin.users')}}">{{$users->count()}}</a></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </li>
                        </ul>
                    </div>
                    <div class="col-md-12 col-lg-4">
                        <ul class="list-group list-group-flush">
                            <li class="bg-transparent list-group-item">
                                <div class="widget-content p-0">
                                    <div class="widget-content-outer">
                                        <div class="widget-content-wrapper">
                                            <div class="widget-content-left">
                                                <a href="{{route('admin.package-list')}}" style="color: black">
                                                <div class="widget-heading">Packages</div>
                                                </a>
                                            </div>
                                            <div class="widget-content-right">
                                                <div class="widget-numbers "><a class="text-success" href="{{route('admin.package-list')}}">{{$packages->count()}}</a></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </li>
                            <li class="bg-transparent list-group-item">
                                <div class="widget-content p-0">
                                    <div class="widget-content-outer">
                                        <div class="widget-content-wrapper">
                                            <div class="widget-content-left">
                                                <a href="{{route('admin.videos-list')}}" style="color: black">
                                                <div class="widget-heading">Videos</div>
                                                <div class="widget-subheading">Total Videos</div>
                                                </a>
                                            </div>
                                            <div class="widget-content-right">
                                                <div class="widget-numbers "><a class="text-success" href="{{route('admin.videos-list')}}">{{$videos->count()}}</a></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
