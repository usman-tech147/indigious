@extends('layouts.app')
@section('title','Contact us')

@section('content')
    @include('partials.banner')
    <!--// Main Content \\-->
    <div class="lifestyle-main-content">

        <!--// Main Section \\-->
        <div class="lifestyle-main-section">
            <div class="container">
                <div class="row">
                    <div class="col-md-12">
                        <div class="lifestyle-contact-info">
                            <ul>
                                <li>
                                    <div class="lifestyle-contact-text">
                                        <i class="fa fa-phone"></i>
                                        <h2>Phone</h2>
                                        <a href="tel:1234567890">{{$websiteSettings->phone_no}}</a>
                                    </div>
                                </li>
                                <li>
                                    <div class="lifestyle-contact-text">
                                        <i class="fa fa-envelope"></i>
                                        <h2>Email</h2>
                                        <a href="mailto:info@gmail.com">{{$websiteSettings->contact_email}}</a>
                                    </div>
                                </li>
                                <li>
                                    <div class="lifestyle-contact-text">
                                        <i class="fa fa-globe"></i>
                                        <h2>Address</h2>
                                        <span>{{$websiteSettings->address}}</span>
                                    </div>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="section-title">
                            <h2>Contact <span>Us</span></h2>
                            <p>Write us an email for more information, or get into contact with our great coaches.</p>
                        </div>
                        <div class="clearfix"></div>
                        <div class="contact-form">
                            @include('partials.flash-message')
                            <form action="{{route('contact-us')}}" method="post" id="contact-us">
                                @csrf
                                <ul class="row">
                                    <li>
                                        <label>Full Name</label>
                                        <input type="text" name="name" placeholder="Type here..." class="form-control">
                                    </li>
                                    @error('name')
                                    <p class="error">{{$message}}</p>
                                    @enderror
                                    <li>
                                        <label>Email Address</label>
                                        <input type="email" name="email" placeholder="Type here..." class="form-control">
                                    </li>
                                    @error('email')
                                    <p class="error">{{$message}}</p>
                                    @enderror
                                    <li>
                                        <label>Phone Number</label>
                                        <input type="text" name="phone_no" placeholder="Type here..." class="form-control">
                                    </li>
                                    @error('phone_no')
                                    <p class="error">{{$message}}</p>
                                    @enderror
                                    <li>
                                        <label>Subject</label>
                                        <input type="text" name="subject" placeholder="Type here..." class="form-control">
                                    </li>
                                    @error('subject')
                                    <p class="error">{{$message}}</p>
                                    @enderror
                                    <li class="full">
                                        <label>Message</label>
                                        <textarea name="message" id="" cols="30" rows="10" class="form-control"></textarea>
                                    </li>
                                    @error('message')
                                    <p class="error">{{$message}}</p>
                                    @enderror
                                    <li><input type="submit" value="Submit" class="submit"></li>
                                </ul>
                            </form>
                            <div class="clearfix"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!--// Main Section \\-->

    </div>
    <!--// Main Content \\-->

@endsection
@section('script')
    <script>
        $('#contact-us').validate({
            rules:{
                name:{
                    required: true,
                    alpha_space:true
                },
                email:{
                    required: true,
                    email:true
                },
                phone_no:{
                    required: true,
                    digits: true,
                    minlength: 11,
                    maxlength:11
                },
                subject:{
                    required: true
                },
                message:{
                    required: true
                }
            } ,
            messages:{
                name:{
                    required: "Name is required.",

                },
                email:{
                    required: "Email is required.",
                },
                phone_no:{
                    required: "Phone Number is required.",
                    digits: "Phone Number must contain number only",
                    minlength: "Please enter valid phone number.",
                    maxlength: "Please enter valid phone number."
                },
                subject:{
                    required: "Subject is required."
                },
                message:{
                    required: "Message is required."
                }
            },
            submitHandler: function(form) {
                form.submit();

            }
        });
    </script>
@endsection
