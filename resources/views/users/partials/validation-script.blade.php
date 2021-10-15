<script>
    //
    $('#user-profile').validate({
        rules:{
            name:{
                required: true,
                alpha_space: true
            },
            institution:{
                required: true,
                alpha_space: true
            },
            phone_no:{
                required: true,
                digits: true,
                minlength: 11,
                maxlength: 11,
            }
        } ,
        messages:{
            name:{
                required: "Name is required."
            },
            institution:{
                required: "Community/Institution name is required."
            },
            phone_no:{
                required: "Phone number is required.",
                digits: "Phone number must contain number only.",
                minlength: "Phone number should be valid.",
                maxlength: "Phone number should be valid.",
            }
        },
        submitHandler: function(form) {
            form.submit();

        }
    });

    $("#user-signin-form").validate({
        rules: {
            email: {
                required: true,
                email: true
            },
            password:{
                required: true,
            }
        },
        // Specify validation error messages
        messages: {
            email: {
                required: "Email is required.",
                email: "Please enter a valid email address."
            },
            password:{
                required: "Password is required.",
            }
        },
        submitHandler: function(form) {
            form.submit();
        }
    });
    $("#user-signup-form").validate({
        rules: {
            name: {
                required: true,
                maxlength: {{env('INPUT_SMALL')}}
    },
    phone_no:{
        required: true,
            digits: true,
            minlength: 11,
            maxlength: 11,
    },
    email: {
        required: true,
            email: true
    },
    password:{
        required: true,
            // minlength: 8,
            password_check: true
    },
    confirm_password:{
        equalTo: '#password'
    },
    institution:{
        required: true,
            maxlength: {{env('INPUT_BIG')}}
    }

    },
    // Specify validation error messages
    messages: {
        name: {
            required: "Full Name is required.",
                maxlength: "Username must not exceed {{env("INPUT_SMALL")}} characters."
        },
        phone_no:{
            required: "Phone no is required.",
                digits: "Phone no should contain number only.",
                minlength: "Phone number should be valid.",
                maxlength: "Phone number should be valid.",
        },
        email: {
            required: "Email is required.",
                email: "Please enter a valid email address."
        },
        password:{
            required: "Password is required.",
            // minlength: "Password Should be of minimum 8 characters."
        },
        confirm_password:{
            required: "Please Re-enter your password.",
                equalTo: 'Password and confirm password should match'
        },
        institution:{
            required: "Institution name is required.",
                maxlength: "Institution name must not exceed {{env('INPUT_BIG')}} characters."
        }
    },
    submitHandler: function(form) {
        form.submit();
    }
    });
    // setTimeout(function() {
    //     $('.error').fadeOut('fast');
    // }, 5000);


    $("#user-belting-request-form").validate({
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
    $("#user-change-password-form").validate({
        rules: {
            password: {
                required: true,
            },
            new_password: {
                required: true,
                // minlength: 8,
                password_check: true

            },
            confirm_new_password: {
                equalTo: '#new_password'
            },
        },
        // Specify validation error messages
        messages: {
            password: {
                required: "Password is required.",
            },
            new_password: {
                required: 'New password is required.',
                // minlength: 'Password length should be minimum 8 characters'
            },
            confirm_new_password: {
                equalTo: 'Confirm Password should match new password.'
            },
        },
        submitHandler: function(form) {
            form.submit();
        }
    });
    $("#change-access-password-form").validate({
        rules: {
            password: {
                required: true,
            },
            new_password: {
                required: true,
                // minlength: 8,
                password_check: true

            },
            confirm_password: {
                equalTo: '#new_password'
            },
        },
        // Specify validation error messages
        messages: {
            password: {
                required: "Password is required.",
            },
            new_password: {
                required: 'New password is required.',
                // minlength: 'Password length should be minimum 8 characters'
            },
            confirm_password: {
                equalTo: 'Confirm Password should match new password.'
            },
        },
    });
</script>
