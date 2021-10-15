<script>
    jQuery.validator.addMethod("alpha_space", function(value, element) {
        return this.optional(element) ||  /^[a-zA-Z ]*$/.test(value);
    }, "Please enter letters and space only.");
    jQuery.validator.addMethod("password_check", function(value, element) {
        return this.optional(element) || /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/g.test(value);
    }, "Password should have minimum eight characters, at least one uppercase letter, one lowercase letter, one number and one special character.");
    $.validator.addMethod('filesize', function (value, element, param) {
        return this.optional(element) || (element.files[0].size <= param * 1000000)
    }, 'File size must be less than {0} MB');``
</script>
