<!DOCTYPE html>
<html lang="en">

<head>
    @php $websiteSettings= \App\Models\WebsiteSettings::first() @endphp
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta http-equiv="Content-Language" content="en">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <title>@php echo $websiteSettings->website_title @endphp | @yield('title')</title>
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="_token" content="{{ csrf_token() }}"/>
    <link rel="shortcut icon" href="{{asset('uploads/favicon/'.$websiteSettings->favicon)}}" type="image/*" />
    <link href="{{asset('assets\main.css')}}" rel="stylesheet">
    <link href="{{asset('assets\font-awesome.css')}}" rel="stylesheet">
    <link href="{{asset('assets\css\lc_lightbox.css')}}" rel="stylesheet">
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
    <link rel="stylesheet" href='{{asset('css\jquery-modal.css')}}'>
    <link rel="stylesheet" href="{{asset('assets\jquery-ui.min.css')}}">
    <link rel="stylesheet" href="{{asset('assets\select2.min.css')}}">
    <link rel="stylesheet" href="{{asset('assets\fileinput.css')}}">
    <link href="{{asset('assets\style.css')}}" rel="stylesheet">
    <link href="{{asset('assets\responsive.css')}}" rel="stylesheet">
    <link href="{{asset('assets\css\pagination.min.css')}}" rel="stylesheet">
    <link href="{{asset('assets\css\bs-pagination.min.css')}}" rel="stylesheet">


</head>

<body>
