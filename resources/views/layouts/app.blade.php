<!DOCTYPE html>
<html lang="en">
<head>
@include('partials.head')
</head>
<body>
<!--// Main Wrapper \\-->
<div class="lifestyle-main-wrapper">

@include('partials.nav-bar')
    @yield('content')
@include('partials.footer')

    <div class="clearfix"></div>
</div>
<!--// Main Wrapper \\-->

@include('partials.links')
</body>
</html>
