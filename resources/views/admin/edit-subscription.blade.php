

@extends('admin.layouts.app')
@section('title','Add Manual Subscription')

@section('content')
    <div class="app-main__inner">
        <div class="tabs-animation">
            <div class="row">
                <div class="col-md-12">
                    <div class="card-header-tab card-header">
                        <div class="card-header-title font-size-lg text-capitalize font-weight-normal"><i class="pe-7s-box1 mr-3 text-muted opacity-6" style="font-size: 35px; color: #E43D4E !important;"> </i>Edit Subscription</div>
                    </div>
                    <div class="main-card mb-3 card">
                        <div class="card-body">
                            @include('partials.flash-message')

                            <form action="{{route('admin.submit-update-subscription',[$user->id,$package->id])}}" method="post" id="user-manual-edit-form">
                                @csrf
                                @method('put')
                                <div class="row">
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>Select User</label>
                                            <select name="user" id="user" class="form-control" disabled>
                                                <option value="{{$user->id}}">{{$user->name}}</option>
                                            </select>
                                            <label id="user-error" class="error" for="user" style="display: none"></label>
                                            @error('package')
                                            <label id="user-error" class="error" for="user" style="display: none">{{$message}}</label>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>Select Package</label>
                                            <select name="package" id="package" class="form-control" disabled>
                                                <option value="{{$package->id}}">{{$package->name}}</option>

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
                                    <div class="col-md-1">
                                        <div class="form-group">
                                            <label>Free:</label>
                                            <input type="hidden" name="free" class="form-control-sm" placeholder="" value="false">
                                            <input type="checkbox" name="free" id="free" class="form-control-sm" placeholder="" value="true">
                                            @error('free')
                                            <label class="error">{{$message}}</label>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-3" id="priceDiv">
                                        <div class="form-group">
                                            <label>Price:</label>
                                            <input type="text" name="price" id="price" class="form-control" placeholder="">
                                            @error('price')
                                            <label class="error">{{$message}}</label>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-12"><button type="submit" id="submit-edit" class="btn btn-primary pull-left" >Submit</button></div>
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
        $('#submit-edit').on('click',function (event){
            event.preventDefault();
            if($('#user-manual-edit-form').valid()) {
                swal({
                    title: "Are you sure?",
                    text: "You will cancel user subscription from PayPal or Stripe.",
                    icon: "warning",
                    buttons: true,
                    dangerMode: true,
                })
                    .then((willDelete) => {
                        if (willDelete) {
                            $('#user-manual-edit-form').submit();

                        }
                    });
            }
        })
        jQuery.validator.addMethod("greaterThan", function(value, element) {
            return this.optional(element) || new Date()<=new Date(value);
        }, "Expiry date should be greater than current date");
        let ed=new Date('{{$package->pivot->expired_at}}');
        ed.setDate(ed.getDate() + 1);

        $('#expiry_date').datepicker({
            minDate: ed
        });
        $('#expiry_date').datepicker("setDate" , ed);

        $('#user-manual-edit-form').validate({
            rules:{
                package: {
                    required: true,
                },
                name: {
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
                name: {
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
        if($('#free:checked').val()){
            $('#priceDiv').hide()
        }
        $('#free').on('change',function (){
            let free=$('#free:checked').val();
            if(free){
                $('#priceDiv').hide()
            }else{
                $('#priceDiv').show()

            }
        });
    </script>

@endsection

