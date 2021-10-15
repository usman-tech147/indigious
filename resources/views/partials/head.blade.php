@php $websiteSettings= \App\Models\WebsiteSettings::first() @endphp

<meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="description" content="Indigenous Lifestyle">
    <meta name="keywords" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="_token" content="{{csrf_token()}}">
    <title>@php echo \App\Models\WebsiteSettings::first()->website_title @endphp | @yield('title')</title>

    <!-- Css Files -->
    <link rel="shortcut icon" href="{{asset('uploads/favicon/'.$websiteSettings->favicon)}}" type="image/*" />
{{--    <link href="{{asset('css/bootstrap-material-design.css')}}" rel="stylesheet">--}}
    <link href="{{asset('css/bootstrap.css')}}" rel="stylesheet">
    <link href="{{asset('css/font-awesome.css')}}" rel="stylesheet">
    <link href="{{asset('css/slick-slider.css')}}" rel="stylesheet">
    <link href="{{asset('css/t-scroll.min.css')}}" rel="stylesheet">
    <link href="{{asset('css/modal-video.min.css')}}" rel="stylesheet">
    <link href="{{asset('css/style.css')}}" rel="stylesheet">
    <link href="{{asset('css/responsive.css')}}" rel="stylesheet">
    <!-- jQuery Modal -->
    <link rel="stylesheet" href="{{asset('/css/jquery-modal.css')}}">
    <script src="https://polyfill.io/v3/polyfill.min.js?version=3.52.1&features=fetch"></script>
    <script src="https://js.stripe.com/v3/"></script>
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>


