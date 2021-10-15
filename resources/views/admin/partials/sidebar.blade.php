<div class="app-sidebar sidebar-shadow">
    <div class="app-header__logo">
        <div class="logo-src"></div>
        <div class="header__pane ml-auto">
            <div>
                <button type="button" class="hamburger close-sidebar-btn hamburger--elastic" data-class="closed-sidebar">
                                <span class="hamburger-box">
                                    <span class="hamburger-inner"></span>
                                </span>
                </button>
            </div>
        </div>
    </div>
    <div class="app-header__mobile-menu">
        <div>
            <button type="button" class="hamburger hamburger--elastic mobile-toggle-nav">
                            <span class="hamburger-box">
                                <span class="hamburger-inner"></span>
                            </span>
            </button>
        </div>
    </div>
    <div class="app-header__menu">
                    <span>
                        <button type="button" class="btn-icon btn-icon-only btn btn-primary btn-sm mobile-toggle-header-nav">
                            <span class="btn-icon-wrapper">
                                <i class="fa fa-ellipsis-v fa-w-6"></i>
                            </span>
                        </button>
                    </span>
    </div>
    <div class="scrollbar-sidebar">
        <div class="app-sidebar__inner">
            <ul class="vertical-nav-menu">
                <li class="app-sidebar__heading">Menu</li>
                <li>
                    <a href="{{route('admin')}}" class="@if(Route::currentRouteName()=='admin') mm-active @endif">
                        <i class="metismenu-icon pe-7s-map"></i>
                        Dashboard
                    </a>
                </li>
                <li>
                    <a href="#">
                        <i class="metismenu-icon pe-7s-server"></i>
                        Packages
                        <i class="metismenu-state-icon pe-7s-angle-down caret-left"></i>
                    </a>
                    <ul>
                        <li>
                            <a href="{{route('admin.create-new-package')}}" class="@if(Route::currentRouteName()=='admin.create-new-package') mm-active @endif">
                                Create New Package
                            </a>
                        </li>
                        <li>
                            <a href="{{route('admin.package-list')}}" class="@if(Route::currentRouteName()=='admin.package-list') mm-active @endif">
                                Packages List
                            </a>
                        </li>
                    </ul>
                </li>
{{--                <li>--}}
{{--                    <a href="#">--}}
{{--                        <i class="metismenu-icon pe-7s-folder"></i>--}}
{{--                        Categories--}}
{{--                        <i class="metismenu-state-icon pe-7s-angle-down caret-left"></i>--}}
{{--                    </a>--}}
{{--                    <ul>--}}
{{--                        <li>--}}
{{--                            <a href="{{route('admin.new-category')}}" class="@if(Route::currentRouteName()=='admin.new-category') mm-active @endif">--}}
{{--                                Add New Category--}}
{{--                            </a>--}}
{{--                        </li>--}}
{{--                        <li>--}}
{{--                            <a href="" class="@if(Route::currentRouteName()=='admin.category-list') mm-active @endif">--}}
{{--                                All Categories--}}
{{--                            </a>--}}
{{--                        </li>--}}
{{--                    </ul>--}}
{{--                </li>--}}
{{--                <li>--}}
{{--                    <a href="#">--}}
{{--                        <i class="metismenu-icon pe-7s-copy-file"></i>--}}
{{--                        Sub Categories--}}
{{--                        <i class="metismenu-state-icon pe-7s-angle-down caret-left"></i>--}}
{{--                    </a>--}}
{{--                    <ul>--}}
{{--                        <li>--}}
{{--                            <a href="{{route('admin.new-sub-category')}}" class="@if(Route::currentRouteName()=='admin.new-sub-category') mm-active @endif">--}}
{{--                                Add New Sub Category--}}
{{--                            </a>--}}
{{--                        </li>--}}
{{--                        <li>--}}
{{--                            <a href="{{route('admin.sub-category-list')}}" class="@if(Route::currentRouteName()=='admin.sub-category-list') mm-active @endif">--}}
{{--                                All Sub Categories--}}
{{--                            </a>--}}
{{--                        </li>--}}
{{--                    </ul>--}}
{{--                </li>--}}
                <li>
                    <a href="#">
                        <i class="metismenu-icon pe-7s-film"></i>
                        Videos
                        <i class="metismenu-state-icon pe-7s-angle-down caret-left"></i>
                    </a>
                    <ul>
                        <li>
                            <a href="{{route('admin.new-video-upload')}}" class="@if(Route::currentRouteName()=='admin.new-video-upload') mm-active @endif">
                                Upload New Video
                            </a>
                        </li>
                        <li>
                            <a href="{{route('admin.videos-list')}}" class="@if(Route::currentRouteName()=='admin.videos-list') mm-active @endif">
                                Videos List
                            </a>
                        </li>
                    </ul>
                </li>

                <li>
                    <a href="{{route('admin.users')}}" class="@if(Route::currentRouteName()=='admin.users') mm-active @endif">
                        <i class="metismenu-icon pe-7s-users"></i>
                        Users
                    </a>
                </li>
                <li>
                    <a href="{{route('admin.manage-user-subscription')}}" class="@if(Route::currentRouteName()=='admin.manage-user-subscription') mm-active @endif">
                        <i class="metismenu-icon pe-7s-users"></i>
                        Manage User Subscription
                    </a>
                </li>
                <li>
                    <a href="{{route('admin.manage-user-subscription-request')}}" class="@if(Route::currentRouteName()=='admin.manage-user-subscription-request') mm-active @endif">
                        <i class="metismenu-icon pe-7s-box1"></i>
                        Subscription Request
                    </a>
                </li>
                <li>
                    <a href="{{route('admin.belting-evolution-requests')}}" class="@if(Route::currentRouteName()=='admin.belting-evolution-requests') mm-active @endif">
                        <i class="metismenu-icon pe-7s-users"></i>
                        Belting Evaluation Requests
                    </a>
                </li>
                <li>
                    <a href="{{route('admin.website-settings')}}" class="@if(Route::currentRouteName()=='admin.website-settings') mm-active @endif">
                        <i class="metismenu-icon pe-7s-settings"></i>
                        Website Settings
                    </a>
                </li>
                <li>
                    <a href="{{route('admin.admin-profile')}}" class="@if(Route::currentRouteName()=='admin.admin-profile') mm-active @endif">
                        <i class="metismenu-icon pe-7s-user"></i>
                        Admin Profile
                    </a>
                </li>
                <li>
                    <a href="{{route('admin.payments')}}" class="@if(Route::currentRouteName()=='admin.payments') mm-active @endif">
                        <i class="metismenu-icon pe-7s-cash"></i>
                        Payments
                    </a>
                </li>
            </ul>
        </div>
    </div>
</div>
