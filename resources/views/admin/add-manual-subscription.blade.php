

@extends('admin.layouts.app')
@section('title','Add Manual Subscription')

@section('content')
    <div class="app-main__inner">
        <div class="tabs-animation">
            <div class="row">
                <div class="col-md-12">
                    <div class="card-header-tab card-header">
                        <div class="card-header-title font-size-lg text-capitalize font-weight-normal"><i class="pe-7s-box1 mr-3 text-muted opacity-6" style="font-size: 35px; color: #E43D4E !important;"> </i>Add Manual Subscription</div>
                    </div>
                    <div class="main-card mb-3 card">
                        <div class="card-body">
                            @include('partials.flash-message')

                            <form action="{{route('admin.manual-subscription')}}" method="post" id="user-manual-form">
                                @csrf
                                @method('put')
                                <div class="row">
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>Select User</label>
                                            <select name="user" id="user" class="form-control">

                                            </select>
                                            <label id="user-error" class="error" for="user" style="display: none"></label>
                                            @error('user')
                                            <label id="user-error" class="error" for="user" style="display: none">{{$message}}</label>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>Select Package</label>
                                            <select name="package" id="package" class="form-control">

                                            </select>
                                            <label id="package-error" class="error" for="package" style="display: none">Select Package from dropdown.</label>
                                            @error('package')
                                            <label id="package-error" class="error" for="package" style="display: none">{{$message}}</label>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>Select Expiry Date:</label>
                                            <input type="text" name="expiry_date" id="expiry_date" class="form-control" placeholder="">
                                            @error('expiry_date')
                                            <label class="error">{{$message}}</label>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-3" id="priceDiv">
                                        <div class="form-group">
                                            <label>Amount Paid:</label>
                                            <input type="text" name="price" id="price" class="form-control" placeholder="">
                                            @error('price')
                                            <label class="error">{{$message}}</label>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-1">
                                        <div class="form-group">
                                            <label>Free:</label>
                                            <input type="hidden" name="free" class="form-control-sm" placeholder="" value="false">
                                            <input type="checkbox" name="free" id="free" class="form-control-sm" placeholder="" value="true" >
                                            @error('free')
                                            <label class="error">{{$message}}</label>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-12"><button type="submit" class="btn btn-primary pull-left" >Submit</button></div>
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
            jQuery.validator.addMethod("greaterThan", function(value, element) {
            return this.optional(element) || new Date()<=new Date(value);
        }, "Expiry date should be greater than current date");
            let d=new Date()
            d.setDate(d.getDate() + 1);
            $('#expiry_date').datepicker({
                minDate: d
            });
            $('#expiry_date').datepicker("setDate" , d);

            $('#user-manual-form').validate({
                rules:{
                    package: {
                        required: true,
                    },
                    user: {
                        required: true,
                    },
                    expiry_date:{
                        required: true,
                        greaterThan: true
                     },
                    price: {
                        required: true
                    }
            },
            messages:{
                    package: {
                        required: "Select package from dropdown.",
                    },
                    user: {
                        required: "Select user from dropdown.",
                    },
                    expiry_date:{
                        required: "Expiry date is required.",
                    },
                    price:{
                        required: "Price is required.",
                    }
        },
            submitHandler: function (form){
            $.blockUI({ message: '<img src="/images/loader1.gif" class="loader-size" />', css: { backgroundColor: 'transparent', border:'transparent'} });

            form.submit();
        }
        });


            $('#package').select2({
                ajax: {
                    url: '/admin/user-not-subscribed-packages',
                    data: function (params) {
                        var query = {
                            search: params.term,
                            user:$('#user').val()
                        }
                        return query;
                    },
                    processResults: function (data) {
                        // Transforms the top-level key of the response object from 'items' to 'results'
                        return {
                            results: data
                        };
                    }
                }
            });
        $('#user').select2({
            ajax: {
            url: '/admin/all-users',
            data: function (params) {
            var query = {
            search: params.term,
        }
            console.log(query)
            return query;
        },
            processResults: function (data) {
            // Transforms the top-level key of the response object from 'items' to 'results'
            console.log(data)
            return {
            results: data
        };
        }
        }
        });
            if($('#free:checked').val()){
            $('#priceDiv').hide()
        }
            $('#free').on('change',function (){
            let free=$('#free:checked').val();
                console.log(free)
            if(free){
            $('#priceDiv').hide()
        }else{
            $('#priceDiv').show()

        }
        });
    </script>

@endsection

