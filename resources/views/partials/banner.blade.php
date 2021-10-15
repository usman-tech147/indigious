<!-- subheader -->
<div class="subheader">
    <span class="transparent"></span>
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <h1>
                @yield('title')

                </h1>
                <ul>
                    <li><a href="{{route('home')}}">Homepage</a></li>
                    <li>@yield('title')</li>
                </ul>
            </div>
        </div>
    </div>
</div>
<!-- subheader -->
