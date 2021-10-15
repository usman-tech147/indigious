@extends('layouts.app')
@section('title','Package Details')

@section('content')
	@include('partials.banner')
	<!--// Main Content \\-->
	<div class="lifestyle-main-content">
		<!--// Main Section \\-->
		<div class="lifestyle-main-section">
			<div class="container">
				<div class="row">
					<div class="col-md-12">
						<div class="lifestyle-package-detail">
							<figure>
								<img src="{{asset('uploads/package/'.$package->thumbnail)}}" alt="">
							</figure>
							<section>
								@php
									if(\auth()->guard('user')->check()){
                                       $user=\App\Models\User::find(\auth()->guard('user')->user()->id);
                                       }
								@endphp
								@if(\auth()->guard('user')->check())
									@if($package->name!='ReevesMMA')
										@if($user->packages!=null && $user->packages->where('name','ReevesMMA')->first()!=null)
											@if($user->packages->find($package->id)!=null &&$user->packages->find($package->id)->pivot->subscribed_status=='Expired')
												<a href="#paymentModal" data-toggle="modal"  data-id="{{$package->id}}" class="pull-right simple-btn">Subscribe</a>
											@elseif($user->packages->find($package->id)==null)
												<a href="#paymentModal" data-toggle="modal"  data-id="{{$package->id}}" class="pull-right simple-btn">Subscribe</a>
											@else
												<button class="pull-right simple-btn" disabled type="button"><i class="fa fa-lock"></i> Already Subscribed</button>
											@endif
										@else
											<a href="#" onclick="event.preventDefault();swal('Error','First subscribe to ReevesMMA','error')" class="pull-right simple-btn">Subscribe</a>
										@endif
									@else
										@if($user->packages!=null && $user->packages->where('name','ReevesMMA')->first()!=null && $user->packages->where('name','ReevesMMA')->first()->pivot->subscribed_status!='Expired')
											<button class="pull-right simple-btn" disabled type="button"><i class="fa fa-lock"></i> Already Subscribed</button>
										@else
											<a href="#paymentModal" data-toggle="modal"  data-id="{{$package->id}}" class="pull-right simple-btn">Subscribe</a>
										@endif
									@endif
								@else
									<a href="{{route('sign-in')}}" class="pull-right simple-btn">Subscribe</a>
								@endif

								<h2>{{$package->name}}</h2>
								<p>{{$package->detail}}</p>
								<p> <strong>Price:
										{{--									<span>${{$package->price}}<small>/Month ,</small> </span>--}}
										{{--									<span>${{$package->price_six}}<small>/Every Six Month ,</small> </span>--}}
										<span>${{$package->price_year}}<small>/Year</small> </span>
									</strong>
								</p>
							</section>
						</div>
						@if($package->free_video_1 || $package->free_video_2)
						<h2 class="details-title">Free Videos</h2>
						<div class="category-list">
							<ul class="row">
								@if($package->free_video_1)
									<li class="col-md-4">
										<div >
											<video playsinline=""
												   controls=""
												   preload="auto"
												   src="{{asset('uploads/package/'.$package->free_video_1)}}"
												   style="width: 100%"

											>
												<source type="video/*" src="{{asset('uploads/package/'.$package->free_video_1)}}">
											</video>

										</div>
									</li>
								@endif
								@if($package->free_video_2)
									<li class="col-md-4">
										<div >
											<video playsinline=""
												   controls=""
												   preload="auto"
												   src="{{asset('uploads/package/'.$package->free_video_2)}}"
												   style="width: 100%"

											>
												<source type="video/*" src="{{asset('uploads/package/'.$package->free_video_2)}}">
											</video>
										</div>
									</li>
								@endif
							</ul>
						</div>
						@endif
						<div class="lifestyle-package-categories">
							@forelse($package->categories as $category)
								<h2>{{$category->name}}</h2>
							@forelse($category->subCategories as $sub_category)
                                    <h4 class="suub-cat">{{$sub_category->name}}</h4>
                                    <p>{{$sub_category->detail}}</p>
                                <section>
									<div class="category-list">
										<ul class="row">
											@forelse($sub_category->videos as $video)
												<li class="col-md-4">
													<div class="category-list-text">
														@if(auth()->guard('user')->check() && $package->users!=null && $package->users->find(auth()->guard('user')->user()->id)==null)
															<figure>
																<div class="imageswrapper"><a href="javascript:void(0)" style="background-image: url({{$video->poster}});"></a></div>
																<a href="#paymentModal" data-toggle="modal"  data-id="{{$package->id}}"  class="fa fa-play"></a>
																<figcaption>
																	<h2><a href="javascript:void(0)">{{$video->title}}</a></h2>
																</figcaption>
															</figure>
														@elseif(auth()->guard('user')->check() && $package->users!=null && $package->users->find(auth()->guard('user')->user()->id)!=null && $package->users->find(auth()->guard('user')->user()->id)->pivot!=null && $package->users->find(auth()->guard('user')->user()->id)->pivot->subscribed_status=='Expired')
															<figure>
																<div class="imageswrapper"><a href="javascript:void(0)" style="background-image: url({{$video->poster}});"></a></div>
																<a href="#paymentModal" data-toggle="modal"  data-id="{{$package->id}}"  class="fa fa-play"></a>
																<figcaption>
																	<h2><a href="javascript:void(0)">{{$video->title}}</a></h2>
																</figcaption>
															</figure>
														@elseif(auth()->guard('user')->check() && $package->users==null)
															<figure>
																<div class="imageswrapper"><a href="javascript:void(0)" style="background-image: url({{$video->poster}});"></a></div>
																<a href="#paymentModal" data-toggle="modal"  data-id="{{$package->id}}"  class="fa fa-play"></a>
																<figcaption>
																	<h2><a href="javascript:void(0)">{{$video->title}}</a></h2>
																</figcaption>
															</figure>
														@elseif(auth()->guard('user')->check() &&  $package->users!=null && $package->users->find(auth()->guard('user')->user()->id)->pivot->subscribed_status!='Expired' &&  $package->users->find(auth()->guard('user')->user()->id)->pivot!=null)
															<figure>
																<div class="imageswrapper"><a href="{{route('user.video-information',$video->id)}}" style="background-image: url({{$video->poster}});"></a></div>
{{--																<a href="javascript:void(0)"  class="fa fa-play m-video" data-id="{{$video->id}}"></a>--}}
																<a href="{{route('user.video-information',$video->id)}}"  class="fa fa-play"></a>
																<figcaption>
																	<h2><a href="{{route('user.video-information',$video->id)}}">{{$video->title}}</a></h2>
																</figcaption>
															</figure>
														@else
															<figure>
																<div class="imageswrapper"><a href="javascript:void(0)" style="background-image: url({{$video->poster}});"></a></div>
																<a href="javascript:void(0)" onclick="swal('Error','Subscribe to package first!','error')" class="fa fa-play"></a>
{{--																<a href="{{route('sign-in')}}" class="fa fa-play"></a>--}}
																<figcaption>
																	<h2><a href="javascript:void(0)">{{$video->title}}</a></h2>
																</figcaption>
															</figure>

														@endif

													</div>
												</li>
											@empty
												<li class="col-md-12">
													<div class="category-list-text">
														<p>Video Not Available.</p>
													</div>
												</li>
											@endforelse
										</ul>
									</div>
								</section>
                            @empty
                                    <section>
                                        {{--									<div class="category-list">--}}
                                        <div class="category-list">
                                            <ul class="row">
                                                <li class="col-md-12 text-center">
                                                    <div class="category-list-text">
                                                        <p>No subcategory available</p>
                                                    </div>
                                                </li>
                                            </ul>
                                        </div>
                                        {{--									</div>--}}
                                    </section>
                            @endforelse

                            @empty
								<h2>Categories</h2>
								<section>
									{{--									<div class="category-list">--}}
									<div class="category-list">
										<ul class="row">
											<li class="col-md-12 text-center">
												<div class="category-list-text">
													<p>No category available</p>
												</div>
											</li>
										</ul>
									</div>
									{{--									</div>--}}
								</section>
							@endforelse
							<div class="center">
								@if(\auth()->guard('user')->check())
									@if($package->name!='ReevesMMA')
										@if($user->packages!=null && $user->packages->where('name','ReevesMMA')->first()!=null)
											@if($user->packages->find($package->id)!=null &&$user->packages->find($package->id)->pivot->subscribed_status=='Expired')
												<a href="#paymentModal" data-toggle="modal"  data-id="{{$package->id}}" class="simple-btn">Subscribe</a>
											@elseif($user->packages->find($package->id)==null)
												<a href="#paymentModal" data-toggle="modal"  data-id="{{$package->id}}" class=" simple-btn">Subscribe</a>
											@else
												<button class=" simple-btn" disabled type="button"><i class="fa fa-lock"></i> Already Subscribed</button>
											@endif
										@else
											<a href="#" onclick="event.preventDefault();swal('Error','First subscribe to ReevesMMA','error')" class=" simple-btn">Subscribe</a>
										@endif
									@else
										@if($user->packages!=null && $user->packages->where('name','ReevesMMA')->first()!=null && $user->packages->where('name','ReevesMMA')->first()->pivot->subscribed_status!='Expired')
											<button class=" simple-btn" disabled type="button"><i class="fa fa-lock"></i> Already Subscribed</button>
										@else
											<a href="#paymentModal" data-toggle="modal"  data-id="{{$package->id}}" class="simple-btn">Subscribe</a>
										@endif
									@endif
								@else
									<a href="{{route('sign-in')}}" class=" simple-btn">Subscribe</a>
								@endif
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<!--// Main Section \\-->
	</div>
	<!--// Main Content \\-->
	@include('partials.payment-modal')

@endsection
