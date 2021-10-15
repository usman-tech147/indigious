<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta http-equiv="Content-Language" content="en">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <title>Indigenous Lifestyle | Login</title>
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no, shrink-to-fit=no">
    <meta name="description" content="">
    <link href="/assets/main.css" rel="stylesheet">
{{--    <link href="/assets/editor/toastr.min.css" rel="stylesheet" />--}}
{{--    <link href="/assets/editor/summernote.css" rel="stylesheet" />--}}
    <link href="/assets/style.css" rel="stylesheet">
    <link href="/assets/responsive.css" rel="stylesheet">
</head>

<body>
@yield('content')
<script type="text/javascript" src="\assets\scripts\main.js"></script>
<script src="https://code.jquery.com/jquery-3.4.1.js" integrity="sha256-WpOohJOqMqqyKL9FccASB9O0KwACQJpFTUBLTYOVvVU=" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.2/dist/jquery.validate.js"></script>
<script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.2/dist/additional-methods.js"></script>
@include('partials.additional-validation')
<script>
    // Jquery Validation
    // admin-forgot-password
    $("#admin-forgot-password").validate({
        rules: {
            email: {
                required: true,
                email: true
            },
        },
        // Specify validation error messages
        messages: {
            email: {
                required: "Email is required",
                email: "Please enter valid email address"
            },
        },
        submitHandler: function(form) {
            form.submit();
        }
    });

    $("#admin-login").validate({
        rules: {
            email: {
                required: true,
                email: true
            },
            password: {
                required: true,
            },
        },
        // Specify validation error messages
        messages: {
            email: {
                required: "Email is required",
                email: "Please enter valid email address"
            },
            password: {
                required: "Password is required",
            },
        },
        submitHandler: function(form) {
            form.submit();
        }

    });

    $("#admin-password-reset").validate({
        rules: {
            confirm_password: {
                required: true,
                equalTo: '#password',

            },
            password: {
                required: true,
                // minlength: 8
                password_check: true
            },
        },
        // Specify validation error messages
        messages: {
            confirm_password: {
                required: "Confirm Password is required",
                equalTo: "Password and Confirm Password Should Match"
            },
            password: {
                required: "Password is required",
                // minlength: "Password Should Be Of Minimum 8 Characters"
            },
        },
        submitHandler: function(form) {
            form.submit();
        }

    });
</script>
</body>

</html>
