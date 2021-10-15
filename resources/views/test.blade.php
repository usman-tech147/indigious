@extends('layouts.app')
@section('content')
    <button id="checkout-button" style="padding: 200px">
        Test
    </button>
@endsection
@section('script')
    <script>
        var stripe = Stripe('{{env('STRIPE_PK')}}');
        var checkoutButton = document.getElementById("checkout-button");

        checkoutButton.addEventListener("click", function () {

                fetch("/test-route", {
                    method: "POST",
                    body: JSON.stringify({
                        _token: '{{@csrf_token()}}',

                    }),
                    headers: {
                        'Content-type': 'application/json; charset=UTF-8'
                    }
                })
                    .then(function (response) {
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
                        console.error("Error:", error);
                    });
        });

    </script>
@endsection