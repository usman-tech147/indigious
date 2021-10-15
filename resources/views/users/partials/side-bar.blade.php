
<div class="col-md-3">
    <div class="lifestyle-typo-wrap">
        <div class="lifestyle-dashboard-nav">
            <figure>
                 <button id="delete-profile-picture"><span class="delete-btn fa fa-trash" @if(auth()->guard('user')->user()->profile_picture==null) style="display: none" @endif></span></button>
                <a href="javascript:void(0)" class="lifestyle-dashboard-thumb"><img src="@if(auth()->guard('user')->user()->profile_picture==null){{asset('/images/user.jpg')}}@else {{asset('/uploads/profile-pictures/'.auth()->guard('user')->user()->profile_picture)}} @endif" alt=""></a>
                <figcaption>
                    <form enctype="multipart/form-data" id="user-upload-profile-picture-form">

                    <div class="lifestyle-fileUpload">
                        <span><i class="fa fa-plus"></i> Upload Photo</span>
                            @csrf
                            @method('put')
                        <input type="file" class="lifestyle-upload" accept="image/jpeg,image/png,image/jpg" name="profile_picture" id="user-profile-picture">

                    </div>
                        <label id="user-profile-picture-error" class="error" for="user-profile-picture" style="display: none"></label>
                    </form>

                    <h2>{{auth()->guard('user')->user()->name}}</h2>
                </figcaption>
            </figure>

            <div class="clearfix"></div>
            <ul class="nav">
                <li class="nav-item ">
                    <a class="nav-link @if( Route::currentRouteName()=='user') active @endif" href="{{route('user')}}">
                        <i class="fa fa-cubes"></i>
                        <p>My Profile</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a class=" nav-link @if(Route::currentRouteName()=='user.all-subscribed-packages') active @endif" href="{{route('user.all-subscribed-packages')}}">
                        <i class="fa fa-align-center"></i>
                        <p>Subscribed Packages</p>
                    </a>
                </li>
{{--                <li class="nav-item">--}}
{{--                    <a class="nav-link @if(Route::currentRouteName()=='user.subscribed-package') active @endif collapsed colap" data-toggle="collapse" href="#open" aria-expanded="true">--}}
{{--                        <i class="fa fa-align-center"></i>--}}
{{--                        <p>Subscribed Packages List</p>--}}
{{--                    </a>--}}
{{--                    <div class="collapse lifestyle-sidebar-drop" id="open" style="">--}}
{{--                        <ul class="nav">--}}
{{--                            @php--}}
{{--                                $user=\App\Models\User::findOrFail(\auth()->guard('user')->user()->id);--}}
{{--                                $packages= \App\Models\Package::whereHas('users', function($q) use ($user) {$q->where('subscribed_status','Not Like','Expired');$q->where('subscribed_status','Not Like','Past_due'); $q->where('user_id',$user->id);})->get();--}}
{{--                            @endphp--}}
{{--                            @forelse($packages as $package)--}}
{{--                            <li class="nav-item">--}}
{{--                                <a class="nav-link " href="{{route('user.subscribed-package',$package->id)}}">--}}
{{--                                    <span class="sidebar-normal">{{$package->name}}</span>--}}
{{--                                </a>--}}
{{--                            </li>--}}
{{--                            @empty--}}
{{--                                <li class="nav-item">--}}
{{--                                    <p>Not Subscribed</p>--}}
{{--                                </li>--}}
{{--                            @endforelse--}}
{{--                        </ul>--}}
{{--                    </div>--}}
{{--                </li>--}}
                <li class="nav-item">
                    <a class=" nav-link @if(Route::currentRouteName()=='user.access-password') active @endif" href="{{route('user.access-password')}}">
                        <i class="fa fa-lock"></i>
                        <p>Access Passwords</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link @if(Route::currentRouteName()=='user.belting-request') active @endif" href="{{route('user.belting-request')}}">
                        <i class="fa fa-cubes"></i>
                        <p>Belting Evaluation Request</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link @if(Route::currentRouteName()=='user.all-belting-request') active @endif" href="{{route('user.all-belting-request')}}">
                        <i class="fa fa-cubes"></i>
                        <p>All Belting Requests</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link @if(Route::currentRouteName()=='user.subscription-request') active @endif" href="{{route('user.subscription-request')}}">
                        <i class="fa fa-calendar"></i>
                        <p>Subscription Requests</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link @if(Route::currentRouteName()=='user.change-password') active @endif" href="{{route('user.change-password')}}">
                        <i class="fa fa-lock"></i>
                        <p>Change Account Password</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link @if(Route::currentRouteName()=='user.view-plan') active @endif" href="{{route('user.view-plan')}}" >
                        <i class="fa fa-calendar"></i>
                        <p>Manage Subscribed Plans</p>
                    </a>
                </li>
            </ul>
        </div>
    </div>
</div>
