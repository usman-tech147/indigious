
<!--// Footer \\-->
@php $web_settings=\App\Models\WebsiteSettings::first(); @endphp
<footer id="lifestyle-footer" class="lifestyle-footer">
    <div class="lifestyle-footer-widget">
        <div class="container">
            <div class="row">
                <div class="col-md-4">
                    <div class="widget footer-info">
                        <a href="{{route('home')}}" class="foot-logo"><img src="{{asset('images/footer-logo.png')}}" alt=""></a>
                        <div class="clearfix"></div>
                        <ul class="footer-social">
                                @if($web_settings->facebook_check==1)<li><a href="https://{{$web_settings->facebook}}" target="_blank" class="fa fa-facebook"></a></li>@endif
                                @if($web_settings->twitter_check==1)<li><a href="https://{{$web_settings->twitter}}" target="_blank" class="fa fa-twitter"></a></li>@endif
                                @if($web_settings->linkedin_check==1)<li><a href="https://{{$web_settings->linkedin}}" target="_blank" class="fa fa-linkedin"></a></li>@endif
                                @if($web_settings->instagram_check==1)<li><a href="https://{{$web_settings->instagram}}" target="_blank" class="fa fa-instagram"></a></li>@endif
                        </ul>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="widget quick-links">
                        <h2 class="footer-title">Quick Links</h2>
                        <ul>
                            <li><a href="{{route('home')}}">Homepage</a></li>
                            @if(auth()->guard('user')->check() || auth()->guard('admin')->check() || auth()->guard('community')->check()) @else<li><a href="{{route('sign-in')}}">Account</a></li>@endif
                            <li><a href="{{route('about-us')}}">About Us</a></li>
{{--                            <li><a href="{{route('video-access')}}">Access Videos</a></li>--}}
                            <li><a href="{{route('contact-us')}}">Contact Us</a></li>
                            <li><a href="{{route('packages')}}">Packages</a></li>
                        </ul>
                    </div>
                </div>
                <div class="col-md-4">
                    <h2 class="footer-title">Contact Info</h2>
                    <div class="widget contact-links">
                        <ul>
                            <li><i class="fa fa-phone"></i><a href="tel:123456789">{{$web_settings->phone_no}}</a></li>
                            <li><i class="fa fa-envelope"></i><a href="mailto:{{$web_settings->contact_email}}">{{$web_settings->contact_email}}</a></li>
                            <li><i class="fa fa-globe"></i><span>{{$web_settings->address}}</span></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!--// Copyright \\-->
    <div class="lifestyle-copyright">
        <!-- <a href="javascript:void(0)" class="top-btn fa fa-angle-up"></a> -->
        <p><i class="fa fa-copyright"></i> {{date('Y')}}, All Rights Reserved - Developed by <a href="https://www.softenica.com/" target="_blank">Softenica Technologies</a></p>
    </div>
    <!--// Copyright \\-->

</footer>
<!--// Footer \\-->
