<script>
    {{--let path='{{env('APP_URL')}}';--}}
    let path='{{asset("")}}';
</script>

<!-- jQuery (necessary for JavaScript plugins) -->
<script type="text/javascript" src="{{asset('/script/jquery.js')}}"></script>

<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
<script type="text/javascript" src="{{asset('/script/bootstrap.min.js')}}"></script>
<script type="text/javascript" src="{{asset('/script/slick.slider.min.js')}}"></script>
<script type="text/javascript" src="{{asset('/script/t-scroll.min.js')}}"></script>
<script type="text/javascript" src="{{asset('/script/jquery-modal-video.min.js')}}"></script>
<script type="text/javascript" src="{{asset('/script/functions.js')}}"></script>
<script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.2/dist/jquery.validate.js"></script>
<script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.2/dist/additional-methods.js"></script>
<script src="{{asset('/script/jquery-modal.js')}}"></script>
<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.22/js/jquery.dataTables.js"></script>
<script type="text/javascript" charset="utf8" src="{{asset('script/jquery.blockUI.js')}}"></script>
<script>
    $(document).ajaxStop($.unblockUI);
</script>
@include('partials.additional-validation')
@include('users.partials.validation-script')
<script>

    $('.m-video').click(function(event) {
        event.preventDefault();
        let videoId=$(this).data('id');
        console.log(videoId)
        $('.jmodal').remove();
        this.blur(); // Manually remove focus from clicked link.
        $.blockUI({ message: '<img src="/images/loader1.gif" class="loader-size" />', css: { backgroundColor: 'transparent', border:'transparent'} });

        $.ajax({
            url: path+'/user/video/'+videoId,
            method: 'get',
            success: function (response){
                console.log(response)
                let video=response.response[0];
                $('<div id="embedBox" style="width:720px;max-width:100%;height:auto;"></div>').appendTo('body').jmodal();

                (function(v,i,d,e,o){v[o]=v[o]||{}; v[o].add = v[o].add || function V(a){ (v[o].d=v[o].d||[]).push(a);};
                    if(!v[o].l) { v[o].l=1*new Date(); a=i.createElement(d), m=i.getElementsByTagName(d)[0];
                        a.async=1; a.src=e; m.parentNode.insertBefore(a,m);}
                })(window,document,"script","https://cdn-gce.vdocipher.com/playerAssets/1.6.10/vdo.js","vdo");
                vdo.add({
                    otp: video.otp,
                    playbackInfo: video.playbackInfo,
                    theme: "9ae8bbe8dd964ddc9bdb932cca1cb59a",
                    container: document.querySelector( "#embedBox" ),
                });
            },
            error: function (response){
                console.log(response)
            }
        })


    });

    $('.m-video-community').click(function(event) {
        event.preventDefault();
        let videoId=$(this).data('id');
        console.log(videoId)
        $('.jmodal').remove();
        this.blur(); // Manually remove focus from clicked link.
        $.blockUI({ message: '<img src="/images/loader1.gif" class="loader-size" />', css: { backgroundColor: 'transparent', border:'transparent'} });

        $.ajax({
            url: path+'/community/video/'+videoId,
            method: 'get',
            success: function (response){
                console.log(response)
                let video=response.response[0];
                $('<div id="embedBox" style="width:720px;max-width:100%;height:auto;"></div>').appendTo('body').jmodal();

                (function(v,i,d,e,o){v[o]=v[o]||{}; v[o].add = v[o].add || function V(a){ (v[o].d=v[o].d||[]).push(a);};
                    if(!v[o].l) { v[o].l=1*new Date(); a=i.createElement(d), m=i.getElementsByTagName(d)[0];
                        a.async=1; a.src=e; m.parentNode.insertBefore(a,m);}
                })(window,document,"script","https://cdn-gce.vdocipher.com/playerAssets/1.6.10/vdo.js","vdo");
                vdo.add({
                    otp: video.otp,
                    playbackInfo: video.playbackInfo,
                    theme: "9ae8bbe8dd964ddc9bdb932cca1cb59a",
                    container: document.querySelector( "#embedBox" ),
                });
            },
            error: function (response){

            }
        })
    });


    $('#accessPasswordModal').on('show.bs.modal', function (event) {
        let button = $(event.relatedTarget);
        let id = button.data('id');
        let package_id = button.data('package_id');
        $('#change-access-password-form').attr('action','/user/change-access-password/'+package_id+'/'+id)
    })


    $('#submit-access-password').on('click',function (){
        if($('#change-access-password-form').valid()){
            $('#change-access-password-form').submit();
        }
    });
    function myFunction(id) {
        var dots = document.getElementById("dots"+id);
        var moreText = document.getElementById("more"+id);
        var btnText = document.getElementById("myBtn"+id);

        if (dots.style.display === "none") {
            dots.style.display = "inline";
            btnText.innerHTML = "Read more";
            moreText.style.display = "none";
        } else {
            dots.style.display = "none";
            btnText.innerHTML = "Read less";
            moreText.style.display = "inline";
        }
    }
</script>
<script>
    let stripeUpdate = Stripe('{{env('STRIPE_PK')}}');

    $('.update-payment').on('click', function() {
        let package=$(this).attr('data-id');
        console.log(package);
        fetch(path+"/user/update-payment/"+package,{
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
                return stripeUpdate.redirectToCheckout({ sessionId: session.id });
            })

    });


    function turnOffAutoRenewal(package_id){
        $('#turn-off-auto-renewal-form').empty();
        swal({
            title: "Are you sure?",
            text: "You can turn on auto renewal before subscription end.",
            icon: "warning",
            buttons: true,
            dangerMode: true,
        })

            .then((willDelete) => {
                if (willDelete) {
                    $('#turn-off-auto-renewal-form').append('<input type="hidden" name="package_id" value='+package_id+'>')
                    $('#turn-off-auto-renewal-form').append('<input type="hidden" name="_token" value="{{csrf_token()}}">')
                    $('#turn-off-auto-renewal-form').submit();
                }
            });
    }
    function turnOnAutoRenewal(package_id){
        $('#turn-on-auto-renewal-form').empty();
        swal({
            title: "Are you sure?",
            text: "You can turn off auto renewal before subscription end.",
            icon: "warning",
            buttons: true,
            dangerMode: true,
        })

            .then((willDelete) => {
                if (willDelete) {
                    $('#turn-on-auto-renewal-form').append('<input type="hidden" name="package_id" value='+package_id+'>')
                    $('#turn-on-auto-renewal-form').append('<input type="hidden" name="_token" value="{{csrf_token()}}">')
                    $('#turn-on-auto-renewal-form').submit();
                }
            });
    }
    function cancelSubscription(package_id){
        $('#cancel-subscription-form').empty();
        swal({
            title: "Are you sure?",
            text: "You will loose all access to subscribed package.",
            icon: "error",
            buttons: true,
            dangerMode: true,
        })

            .then((willDelete) => {
                if (willDelete) {
                    $('#cancel-subscription-form').append('<input type="hidden" name="package_id" value='+package_id+'>')
                    $('#cancel-subscription-form').append('<input type="hidden" name="_token" value="{{csrf_token()}}">')
                    $('#cancel-subscription-form').submit();
                }
            });
    }
    $('#checkout-button-paypal').on('click',function (event){
        event.preventDefault();
        console.log($('#package_id').val());
        $('#paypal-checkout-form').submit();

    });
    $('#paypal-checkout-form').validate({
        ignore:{},
        rules:{
            plan: {required:true},
            interval: {required:true}

        },
        messages:{
            plan: {
                required: "Plan is required."
            }
        }
    });
    // //Only Annual
    // $(document).on('change','#plan',function (){
    //
    //     let plan=$('#plan :selected').text();
    //     plan=(plan.split('/')[1])
    //     if(plan=='One Month'){
    //         $('#interval').val(1)
    //     }
    //     else if(plan=='6 Months'){
    //         $('#interval').val(6)
    //     }
    //     else if(plan=='One Year'){
    //         $('#interval').val(1)
    //     }
    // });
    // //
    $('#paymentModal').on('show.bs.modal', function (event) {
        let button = $(event.relatedTarget) // Button that triggered the modal
        let package_id = button.data('id') // Extract info from data-* attributes
        $('#package_id').val(package_id);
        // $('#plan').empty();
        //Only annual
        // $.blockUI({ message: '<img src="/images/loader1.gif" class="loader-size" />', css: { backgroundColor: 'transparent', border:'transparent'} });
        //
        // $.ajax({
        //     url: path+'/get-package-info',
        //     method: 'post',
        //     data: {
        //         _token: '{{csrf_token()}}',
        //         package_id: package_id
        //     },
        //     success: function (response){
        //         // console.log(response)
        //        `` $('#plan').append('                      <option value="">Select Plan</option>\n' +
        //             '                                    <option value="month">$'+response.price+' /One Month</option>\n' +
        //             '                                    <option value="month">$'+response.price_six+' /6 Months</option>\n' +
        //             '                                    <option value="year">$'+response.price_year+' /One Year</option>')
        //         // $('#month-label').text(response.price);
        //         // $('#year-label').text(response.price_year);
        //         // $('#six-label').text(response.price_six);
        //
        //     },
        //     error: function (response){
        //
        //     }
        // });
    })

    function suspendBilling(package_id)
    {
        $('#suspend-subscription-form').empty();
        swal({
            title: "Are you sure?",
            text: "You can turn on auto renewal before subscription end.",
            icon: "warning",
            buttons: true,
            dangerMode: true,
        })
            .then((willDelete) => {
                if (willDelete) {
                    $('#suspend-subscription-form').append('<input type="hidden" name="package_id" value='+package_id+'>')
                    $('#suspend-subscription-form').append('<input type="hidden" name="_token" value="{{csrf_token()}}">')
                    $('#suspend-subscription-form').submit();
                }
            });
    }
    function reactiveBilling(package_id)
    {
        $('#reactive-subscription-form').empty();
        swal({
            title: "Are you sure?",
            text: "You can turn off auto renewal before subscription end.",
            icon: "warning",
            buttons: true,
            dangerMode: true,
        })
            .then((willDelete) => {
                if (willDelete) {
                    $('#reactive-subscription-form').append('<input type="hidden" name="package_id" value='+package_id+'>')
                    $('#reactive-subscription-form').append('<input type="hidden" name="_token" value="{{csrf_token()}}">')
                    $('#reactive-subscription-form').submit();
                }
            });
    }
    function cancelPayPalSubscription(package_id)
    {
        $('#cancel-subscription-form-paypal').empty();
        swal({
            title: "Are you sure?",
            text: "This will cancel your access to package immediately.",
            icon: "error",
            buttons: true,
            dangerMode: true,
        })
            .then((willDelete) => {
                if (willDelete) {
                    $('#cancel-subscription-form-paypal').append('<input type="hidden" name="package_id" value='+package_id+'>')
                    $('#cancel-subscription-form-paypal').append('<input type="hidden" name="_token" value="{{csrf_token()}}">')
                    $('#cancel-subscription-form-paypal').submit();
                }
            });
    }
</script>
<script>
    $('#user-profile-picture').on('change', function (){
        if($('#user-upload-profile-picture-form').valid()){
            let form=document.getElementById('user-upload-profile-picture-form');
            let fd=new FormData(form);
            // $.blockUI();
            $.blockUI({ message: '<img src="/images/loader1.gif" class="loader-size" />', css: { backgroundColor: 'transparent', border:'transparent'} });

            $.ajax(
                {
                    url: path+'/user/update-profile-picture',
                    method: 'post',
                    data: fd,
                    processData: false,
                    contentType: false,
                    success: function (response){
                        window.location.href='';
                        // $.unblockUI();
                    },
                    error: function (response){
                        console.log(response)
                        // $.unblockUI();
                    }
                }
            )
        }
    });
    $('#delete-profile-picture').on('click', function (){
        // $.blockUI();
        $.blockUI({ message: '<img src="/images/loader1.gif" class="loader-size" />', css: { backgroundColor: 'transparent', border:'transparent'} });

        $.ajax(
            {
                url: '{{route('user.delete-profile-picture')}}',
                type: 'put',
                data: {
                    _token: '{{csrf_token()}}'
                },
                success: function (response){
                    window.location.href='';
                    // $.unblockUI();
                },
                error: function (response){
                    console.log(response)
                    // $.unblockUI();
                }
            }
        )
    });
    $('#user-upload-profile-picture-form').validate({
        rules: {
            profile_picture: {
                accept: "image/jpeg,image/jpg,image/png"
            }
        },
        messages: {
            profile_picture: {
                accept: "Profile picture should be jpg,jpeg or png."

            }
        }
    });
</script>
{{--<script src="{{asset('script/belting-request.js')}}"></script>--}}
@yield('script')

