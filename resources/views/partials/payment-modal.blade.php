
<div class="modal fade" id="paymentModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Select Payment Method</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">


                <div class="form-group">
                    <div class="container">
                        <div class="row">

                            <div class="col-md-12">
                                <form action="{{route('user-subscribe-package-paypal')}}" method="post" id="paypal-checkout-form">
                                    @csrf
                                    <input type="hidden" name="package_id" id="package_id">
                                    <input type="hidden" name="interval" id="interval" value="1">
                                    <input type="hidden" name="plan" id="plan" value="year">

                                    {{--                                    Only Annual--}}
{{--                                    <div class="form-group">--}}
{{--                                        <label>Select Subscription Limit</label>--}}
{{--                                        <select name="plan" id="plan" style="margin: 0px 0px 0px" class="form-control">--}}
{{--                                        </select>--}}
{{--                                    </div>--}}
                                </form>
                            </div>
                            <div class="col-md-6" style="text-align: center;">
                                <label>Credit/Debit Card: </label>
                                <button style="font-size: 40px; background: transparent" id="checkout-button"><i class="fa fa-credit-card"></i></button>
                            </div>
                            <div class="col-md-6" style="text-align: center;">
                                <label style="margin: 0px 0px 25px">Paypal: </label>
                                <button style="background: transparent" id="checkout-button-paypal" onclick="event.preventDefault();submitPayPal()"><img src="/images/paypal.jpg" style="width: 100px;" alt=""></button>
                            </div>
                            <div class="col-md-12" style="text-align: center;">
                                <label style="margin: 0px 0px 25px">Pay Manually: </label>
                                <button class="btn btn-primary" id="pay-manual" onclick="event.preventDefault();submitManual()">Apply To Pay Manually</button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row justify-content-center hide" id="loader"><img src="/images/loader.gif" style="height: 50px;width: 50px" alt=""></div>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
{{--                                        --}}
<script type="text/javascript">

    function submitPayPal(){
        if($('#paypal-checkout-form').valid()){
            $('#loader').removeClass('hide');
            $('#paypal-checkout-form').submit();
        }
    }
    function submitManual(){
       $.ajax({
           url: '{{route('user.manual-subscription')}}',
           type: "POST",
           data: {
                    _token : '{{@csrf_token()}}',
                    package: $('#package_id').val(),
           },
           success: function (response){
               swal(
                   {
                       title: "Success",
                       icon: "success",
                       text:"Manual Subscription Request Sent."
                   }
               )
               // setTimeout(function(){ location.reload() }, 3000);

           },
           error: function (response){
               swal(
                   {
                       title: "Error",
                       icon: "error",
                       text:"Manual Subscription Request Already Sent."
                   }
               )
               // setTimeout(function(){ location.reload() }, 3000);

           }

       })
    }
    // Create an instance of the Stripe object with your publishable API key
    var stripe = Stripe('{{env('STRIPE_PK')}}');
    var checkoutButton = document.getElementById("checkout-button");

    checkoutButton.addEventListener("click", function () {
        if($('#paypal-checkout-form').valid()){
            // let plan=$('#plan :selected').val();
            // let interval=$('#interval').val();
        $('#loader').removeClass('hide');
        fetch(path+"user/subscribe/package", {
            method: "POST",
            body: JSON.stringify({
                _token: '{{@csrf_token()}}',
                package_id: $('#package_id').val(),
                plan: plan,
                interval: interval

            }),
            headers: {
                'Content-type': 'application/json; charset=UTF-8'
            }
        })
            .then(function (response) {
                console.log(response)
                return response.json();
            })
            .then(function (session) {
                return stripe.redirectToCheckout({sessionId: session.id});
            })
            .then(function (result) {

                if (result.error) {
                    alert(result.error.message);
                }
            })
            .catch(function (error) {
                console.log(error)

                swal("Error", 'Some error has occurred!','error');
                $('#loader').addClass('hide');
            });
    }
    });

</script>
