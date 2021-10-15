@php
    if(\auth()->guard('user')->check()){
    $user=\App\Models\User::find(\auth()->guard('user')->user()->id);
    }
@endphp
<li class="make-active">
    <div class="video-package-text">
        <figure><img src="{{asset('uploads/package/'.$package->thumbnail)}}" alt=""></figure>
        <div class="prices-wrap">
            <span>${{$package->price_year}}<small>Year</small></span>
        </div>
        <section>
            <h2>{{$package->name}}</h2>
            <p>{{substr($package->detail,0,100)}}<span>...</span>
                <a href="{{route('package-details',[$package->id])}}" style="background: transparent;font-weight: bold">Read
                    more</a></p>
            <div class="text-center">
                @if(\auth()->guard('user')->check())
                    @if($package->name!='ReevesMMA')
                        @if($user->packages!=null && $user->packages->where('name','ReevesMMA')->first()!=null)
                            @if($user->packages->find($package->id)!=null &&$user->packages->find($package->id)->pivot->subscribed_status=='Expired')
                                <a href="#paymentModal" data-toggle="modal" data-id="{{$package->id}}"
                                   class="price-cart">Subscribe</a>
                            @elseif($user->packages->find($package->id)==null)
                                <a href="#paymentModal" data-toggle="modal" data-id="{{$package->id}}"
                                   class="price-cart">Subscribe</a>
                            @else
                                <button class="price-cart" disabled type="button"><i class="fa fa-lock"></i> Already
                                    Subscribed
                                </button>
                            @endif
                        @else
                            <a href="#"
                               onclick="event.preventDefault();swal('Error','First subscribe to ReevesMMA','error')"
                               class="price-cart">Subscribe</a>
                        @endif
                    @else
                        @if($user->packages!=null && $user->packages->where('name','ReevesMMA')->first()!=null && $user->packages->where('name','ReevesMMA')->first()->pivot->subscribed_status!='Expired')
                            <button class="price-cart" disabled type="button"><i class="fa fa-lock"></i> Already
                                Subscribed
                            </button>
                        @else
                            <a href="#paymentModal" data-toggle="modal" data-id="{{$package->id}}" class="price-cart">Subscribe</a>
                        @endif
                    @endif
                @else
                    <a href="{{route('sign-in')}}" class="price-cart">Subscribe</a>
                @endif
                <a href="{{route('package-details',[$package->id])}}" class="price-cart">View More</a>
            </div>
        </section>
    </div>
</li>
