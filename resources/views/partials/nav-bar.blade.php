<!--// Header \\-->
<header id="lifestyle-header" class="lifestyle-header">
    <div class="container">
        <div class="row">
            <div class="col-md-3">
                <a href="{{route('home')}}" class="logo"><img src="{{asset('images/logo.png')}}" alt=""></a>
            </div>
            <div class="col-md-9">
{{--                <a href="#" class="cart-btn fa fa-shopping-cart"><span>2</span></a>--}}
                <div class="lifestyle-nav">
                    <nav class="navbar navbar-expand-lg">
                        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                            <span class="fa fa-bars"></span>
                        </button>

                        <div class="collapse navbar-collapse" id="navbarSupportedContent">
                            <ul class="navbar-nav mr-auto">

                                <li class="nav-item"><a class="nav-link @if(Route::currentRouteName()=='home') active @endif" href="{{route('home')}}">Home</a></li>
                                <li class="nav-item"><a class="nav-link @if(Route::currentRouteName()=='packages') active @endif" href="{{route('packages')}}">Packages</a></li>
                                <li class="nav-item"><a class="nav-link @if(Route::currentRouteName()=='about-us') active @endif" href="{{route('about-us')}}">About Us</a></li>
                                <li class="nav-item"><a class="nav-link @if(Route::currentRouteName()=='contact-us') active @endif" href="{{route('contact-us')}}">Contact Us</a></li>
                                @if(auth()->guard('user')->check())
                                    @php
                                        $path=explode('/',\Illuminate\Support\Facades\Request::path());

                                    @endphp
                                    <li class="nav-item"><a class="nav-link @if($path[0]=='user') active @endif" href="{{route('user')}}">Dashboard</a></li>
                                    <li class="nav-item"><a class="nav-link" href="#" onclick="event.preventDefault(); document.getElementById('user-logout').submit();">Logout <i class="fa fa-sign-out"></i></a></li>
                                    <form action="{{route('user.logout')}}" method="post" id="user-logout">
                                        @csrf
                                    </form>
                                    @elseif(auth()->guard('admin')->check())
                                    @elseif(auth()->guard('community')->check())
                                    <li class="nav-item"><a class="nav-link @if(Route::currentRouteName()=='community.subscribed-package') active @endif" href="{{route('community.subscribed-package')}}">View Packages</a></li>
                                    <li class="nav-item"><a class="nav-link" href="#" onclick="event.preventDefault(); document.getElementById('community-logout').submit();">Logout <i class="fa fa-sign-out"></i></a></li>
                                    <form action="{{route('community.logout')}}" method="post" id="community-logout">
                                        @csrf
                                    </form>
                                    @else
                                    <li class="nav-item"><a class="nav-link  @if(Route::currentRouteName()=='sign-in' || Route::currentRouteName()=='sign-up') active @endif" href="{{route('sign-in')}}">Account <i class="fa fa-sign-in"></i></a></li>
                                @endif
{{--                                <li class="nav-item"><a class="nav-link header-btn" href="{{route('video-access')}}">Access Videos</a></li>--}}
                            </ul>
                        </div>
                    </nav>
                </div>
            </div>
        </div>
    </div>
</header>
<!--// Header \\-->
