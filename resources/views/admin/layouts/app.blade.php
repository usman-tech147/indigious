@include('admin.partials.header')
<div class="app-container app-theme-white body-tabs-shadow fixed-header fixed-sidebar">
    @include('admin.partials.navbar')
    <div class="app-main">
        @include('admin.partials.sidebar')
        <div class="app-main__outer">
            @yield('content')
          @include('admin.partials.footer')
        </div>
    </div>
</div>
@include('admin.partials.links')
