@extends('layouts.app')
@section('title',$package->name)
@section('content')
    @include('partials.banner')

    <!--// Main Content \\-->
    <div class="lifestyle-main-content">

        <!--// Main Section \\-->
        <div class="lifestyle-main-section">
            <div class="container">
                <div class="row">
                    <div class="col-md-12">
                            <a href="#beltingModal" style="background-color: #e43d4e; border-color: #e43d4e; margin: 0px 0px 2px; box-shadow: none;" class="pull-right btn btn-primary" data-toggle="modal" id="submit-belting request">Belting Evaluation Request</a>
                        <div class="category-list">

                            <ul class="row">
                                @include('partials.flash-message')
                                @php
                                    $user=\App\Models\User::findOrFail(\auth()->guard('community')->user()->user_id);
                                @endphp
                                @if($user->is_blocked!=true)
                                @if(\auth()->guard('community')->user()->subscribed_status!='Expired')
                                @forelse($package->categories as $category)
                                    <div class="col-12">
                                            <h2 class="form-title">{{$category->name}} </h2>
                                    </div>
                                            @forelse($category->subCategories as $sub_category)

                                            <div class="col-12">
                                                    <h2 class="">{{$sub_category->name}} </h2>
                                                </div>

                                    @forelse($sub_category->videos as $video)
                                    <li class="col-md-4">
                                        <div class="category-list-text">
                                            <figure>
                                                <div class="imageswrapper"><a href="{{route('community.subscribed-package-video',[$video->id])}}" style="background-image: url({{$video->poster}});"></a></div>
                                                <a href="javascript:void(0)"  class="fa fa-play m-video-community" data-id="{{$video->id}}"></a>
                                                <figcaption>
                                                    <h2><a href="#">{{$video->title}}</a></h2>
                                                </figcaption>
                                            </figure>
                                        </div>

                                    </li>
                                            @empty
                                                <div class="text-center col-md-12">
                                                    <li><h3>Videos Not Found In This Sub Category!</h3></li>
                                                </div>
                                                @endforelse
                                        @empty
                                                <div class="text-center col-md-12">
                                                    <li><h3>Sub Category not found!</h3></li>
                                                </div>
                                                @endforelse
                                    @empty
                                                <div class="text-center col-md-12">
                                                    <li><h3>Videos Not Found!</h3></li>
                                                </div>
                                        @endforelse

                                @else
                                    <div class="text-center col-md-12">
                                        <li><h3>Package Expired!</h3></li>
                                    </div>
                                @endif
                                    @else
                                    <div class="text-center col-md-12">
                                        <li><h3>The user is blocked by admin!</h3></li>
                                    </div>
                                @endif
                            </ul>

                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!--// Main Section \\-->

    </div>
    <!--// Main Content \\-->

    <div class="modal fade" id="beltingModal" tabindex="-1" role="dialog" aria-labelledby="beltingModal" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Submit Belting Request</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="{{route('community.belting-request')}}" method="post" id="belting-form">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                            <label for="name" class="col-form-label">Name:</label>
                            <input type="text" class="form-control" name="name" id="name">
                        </div>
                        <div class="form-group">
                            <label for="email" class="col-form-label">Email:</label>
                            <input type="text" class="form-control" name="email" id="email">
                        </div>
                        <div class="form-group">
                            <label for="phone_no" class="col-form-label">Phone Number:</label>
                            <input type="text" class="form-control" name="phone_no" id="phone_no">
                        </div>
                        <div class="form-group">
                            <label for="message" class="col-form-label">Message:</label>
                            <textarea class="form-control" name="message" id="message"></textarea>
                        </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Submit</button>
                </div>
                </form>
            </div>
        </div>
    </div>
@endsection
@section('script')
    <script>
        $('#belting-form').validate({
            rules: {
                name: {
                    required: true,
                    maxlength: {{env('INPUT_SMALL')}}
                },
                phone_no:{
                    required: true,
                    digits: true,
                    minlength:11,
                    maxlength:11,
                },
                email: {
                    required: true,
                    email: true
                },
                message:{
                    required: true,
                    maxlength: {{env('TEXT_AREA_LIMIT')}}
                }

            },
            // Specify validation error messages
            messages: {
                name: {
                    required: "Name is required.",
                    maxlength: "Username must not exceed {{env('INPUT_SMALL')}} characters."
                },
                phone_no:{
                    required: "Phone no is required.",
                    digits: "Phone no should contain number only.",
                    minlength: "Please enter valid phone number",
                    maxlength: "Please enter valid phone number",
                },
                email: {
                    required: "Email is required.",
                    email: "Please enter a valid email address."
                },
                message:{
                    required: "Message is required.",
                    maxlength: "Message must not exceed {{env('TEXT_AREA_LIMIT')}} characters."
                }
            },
            submitHandler: function(form) {
                form.submit();
            }
        });
    </script>
@endsection
