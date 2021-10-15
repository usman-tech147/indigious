@extends('admin.layouts.app')
@section('title','Website Settings')

@section('content')
    <div class="app-main__inner">
        <div class="tabs-animation">
            <div class="row">
                <div class="col-md-12">
                    <div class="card-header-tab card-header">
                        <div class="card-header-title font-size-lg text-capitalize font-weight-normal"><i class="pe-7s-settings mr-3 text-muted opacity-6" style="font-size: 35px; color: #E43D4E !important;"> </i>Website Settings</div>
                    </div>
                    <div class="main-card mb-3 card">
                        <div class="card-body">
                            @include('partials.flash-message')

                            <form action="{{route('admin.website-settings')}}" method="POST" id="website-settings-form" enctype="multipart/form-data">
                                @csrf
                                @method('put')
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Website Title</label>
                                            <input type="text" name="website_title" value="{{$websiteSettings->website_title}}" class="form-control" placeholder="">
                                        </div>
                                        @error('website_title')
                                            <p class="text-danger">{{$message}}</p>
                                        @enderror
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Contact Email</label>
                                            <input type="text" name="contact_email" value="{{$websiteSettings->contact_email}}" class="form-control" placeholder="">
                                        </div>
                                        @error('contact_email')
                                        <p class="text-danger">{{$message}}</p>
                                        @enderror
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Phone Number</label>
                                            <input type="text" name="phone_no" value="{{$websiteSettings->phone_no}}" class="form-control" placeholder="">
                                        </div>
                                        @error('phone_no')
                                        <p class="text-danger">{{$message}}</p>
                                        @enderror
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Address</label>
                                            <input type="text" name="address" value="{{$websiteSettings->address}}" class="form-control" placeholder="">
                                        </div>
                                        @error('address')
                                        <p class="text-danger">{{$message}}</p>
                                        @enderror
                                    </div>
{{--                                    <div class="col-md-4">--}}
{{--                                        <div class="form-group">--}}
{{--                                            <label>PayPal Email</label>--}}
{{--                                            <input type="text" name="paypal_email" value="{{$websiteSettings->paypal_email}}" class="form-control" placeholder="">--}}
{{--                                        </div>--}}
{{--                                        @error('paypal_email')--}}
{{--                                        <p class="text-danger">{{$message}}</p>--}}
{{--                                        @enderror--}}
{{--                                    </div>--}}
{{--                                    <div class="col-md-4">--}}
{{--                                        <div class="form-group">--}}
{{--                                            <label>Stripe Public Key</label>--}}
{{--                                            <input type="text" name="stripe_api_pk" value="{{$websiteSettings->stripe_api_pk}}" class="form-control" placeholder="">--}}
{{--                                        </div>--}}
{{--                                        @error('stripe_api_pk')--}}
{{--                                        <p class="text-danger">{{$message}}</p>--}}
{{--                                        @enderror--}}
{{--                                    </div>--}}
{{--                                    <div class="col-md-4">--}}
{{--                                        <div class="form-group">--}}
{{--                                            <label>Stripe Secret Key</label>--}}
{{--                                            <input type="text" name="stripe_api_sk" value="{{$websiteSettings->stripe_api_sk}}" class="form-control" placeholder="">--}}
{{--                                        </div>--}}
{{--                                        @error('stripe_api_sk')--}}
{{--                                        <p class="text-danger">{{$message}}</p>--}}
{{--                                        @enderror--}}
{{--                                    </div>--}}
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Favicon:</label>
                                            <input type="file" name="favicon" id="favicon"   class="form-control" placeholder="">
                                        </div>
                                        @error('stripe_api_sk')
                                        <p class="text-danger">{{$message}}</p>
                                        @enderror
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <div class="row"><div class="col-md-5"><label style="margin: 7px 0 0;"> <input  type='hidden' value='0' name='facebook_check'><input type="checkbox" value="1" @if($websiteSettings->facebook_check==1){{'checked'}}@endif name="facebook_check"> Facebook</label></div><div class="col-md-7"><input type="text" value="{{$websiteSettings->facebook}}" name="facebook" class="form-control" placeholder="Add Link">@error('facebook')
                                                    <p class="text-danger">{{$message}}</p>
                                                    @enderror</div></div>
                                        </div>

                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <div class="row"><div class="col-md-5"><label style="margin: 7px 0 0;"> <input  type='hidden' value='0' name='twitter_check'><input type="checkbox" value="1" @if($websiteSettings->twitter_check==1){{'checked'}}@endif  name="twitter_check"> Twitter</label></div><div class="col-md-7"><input type="text" value="{{$websiteSettings->twitter}}" class="form-control" name="twitter" placeholder="Add Link">@error('twitter')
                                                    <p class="text-danger">{{$message}}</p>
                                                    @enderror</div></div>
                                        </div>

                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <div class="row"><div class="col-md-5"><label style="margin: 7px 0 0;"><input  type='hidden' value='0' name='linkedin_check'> <input type="checkbox" value="1" @if($websiteSettings->linkedin_check==1){{'checked'}}@endif name="linkedin_check"> Linkedin</label></div><div class="col-md-7"><input type="text" value="{{$websiteSettings->linkedin}}" class="form-control" name="linkedin" placeholder="Add Link">@error('linkedin')
                                                    <p class="text-danger">{{$message}}</p>
                                                    @enderror</div></div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <div class="row"><div class="col-md-5"><label style="margin: 7px 0 0;"><input  type='hidden' value='0' name='instagram_check'> <input type="checkbox" value="1" @if($websiteSettings->instagram_check==1){{'checked'}}@endif name="instagram_check"> instagram</label></div><div class="col-md-7"><input type="text" value="{{$websiteSettings->instagram}}" class="form-control" name="instagram" placeholder="Add Link">  @error('instagram')
                                                    <p class="text-danger">{{$message}}</p>
                                                    @enderror</div></div>
                                        </div>

                                    </div>
                                    <div class="col-md-12"><button type="submit" class="btn btn-primary pull-left" >Submit</button></div>
                                </div>
                                <div class="clearfix"></div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('script')
    <script>
        $('#favicon').fileinput({
            theme: 'fas',

            allowedFileExtensions: ["jpg", "png", "jpeg"],
            initialPreview: [
                "<img src='{{asset('uploads/favicon/'.$websiteSettings->favicon)}}' class='file-preview-image' alt=''>",
            ],
            initialPreviewShowDelete: false

        })
    </script>
@endsection
