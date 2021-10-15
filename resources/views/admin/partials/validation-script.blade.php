    <script>


        $("#create-new-package").validate({
            rules: {
                package_name: {
                    required:true,
                    maxlength: {{env('INPUT_SMALL')}}
                },
                thumbnail: {
                    required: true,
                    accept: "image/jpeg,image/jpg,image/png"
                },
                price: {
                    required: true,
                    digits: true,
                    maxlength: {{env('INPUT_SMALL')}}
                },
                price_six: {
                    required: true,
                    digits: true,
                    maxlength: {{env('INPUT_SMALL')}}
                },
                price_year: {
                    required: true,
                    digits: true,
                    maxlength: {{env('INPUT_SMALL')}}
                },
                detail: {
                    required: true,
                    maxlength: {{env('TEXT_AREA_LIMIT')}}
                },
                free_video_1: {
                    accept: "video/mp4,video/x-matroska,video/avi,video/quicktime"
                },
                free_video_2: {
                    accept: "video/mp4,video/x-matroska,video/avi,video/quicktime"
                }
            },
            // Specify validation error messages
            messages: {
                package_name: {
                    required:"Package name is required.",
                    maxlength: "Package name must not exceed "+'{{env('INPUT_SMALL')}}'+" characters."
                },
                thumbnail: {
                    required: 'Thumbnail is required.',
                    accept: "Please select only jpeg,jpg or png file."
                },
                price: {
                    required: "Price is required.",
                    digits: "Enter valid price with digits only.",
                    maxlength: "Price must no exceed "+'{{env('INPUT_SMALL')}}'+" digits."
                },
                price_six: {
                    required: "Price is required.",
                    digits: "Enter valid price with digits only.",
                    maxlength: "Price must no exceed "+'{{env('INPUT_SMALL')}}'+" digits."
                },
                price_year: {
                    required: "Price is required.",
                    digits: "Enter valid price with digits only.",
                    maxlength: "Price must no exceed "+'{{env('INPUT_SMALL')}}'+" digits."
                },
                detail: {
                    required:"Package details are required.",
                    maxlength: "Package details must not exceed "+'{{env('TEXT_AREA_LIMIT')}}'+" characters."
                }
            },

            submitHandler: function(form) {
                $('#create-package-submit').attr('disabled','disabled');
                document.getElementById('page_loader').style.display='block';
                $.blockUI({ message: '<img src="/images/loader1.gif" class="loader-size" />', css: { backgroundColor: 'transparent', border:'transparent'} });

                form.submit();
            }
        });

        $("#edit-package").validate({
            rules: {
                package_name: {
                    required:true,
                    maxlength:  {{env('INPUT_SMALL')}}
                },
                thumbnail: {
                    accept: "image/jpeg,image/jpg,image/png"
                },
                price: {
                    required: true,
                    digits: true,
                    maxlength: {{env('INPUT_SMALL')}}
                },
                detail: {
                    required: true,
                    maxlength: {{env('TEXT_AREA_LIMIT')}}
                },
                free_video_1: {
                    accept: "video/mp4,video/x-matroska,video/avi,video/quicktime"
                },
                free_video_2: {
                    accept: "video/mp4,video/x-matroska,video/avi,video/quicktime"
                }
            },
            // Specify validation error messages
            messages: {
                package_name: {
                    required: "Package name is required.",
                    maxlength: "Package name must not exceed " + '{{env('INPUT_SMALL')}}' + " characters."
                },
                thumbnail: {
                    accept: "Please select only jpeg,jpg or png file."
                },
                price: {
                    required: "Price is required.",
                    digits: "Enter valid price with digits only.",
                    maxlength: "Price must no exceed " + '{{env('INPUT_SMALL')}}' + " digits."
                },
                detail: {
                    required: "Package details are required.",
                    maxlength: "Package details must not exceed " + '{{env('TEXT_AREA_LIMIT')}}' + " characters."
                },

            },
            submitHandler: function(form) {
                $.blockUI({ message: '<img src="/images/loader1.gif" class="loader-size" />', css: { backgroundColor: 'transparent', border:'transparent'} });

                form.submit();
            }
        });


        $("#upload-new-video-form").validate({
            rules: {
                package: {
                    required:true,
                },
                video_name: {
                    required: true,
                    maxlength: {{env('INPUT_SMALL')}} ,
                },
                poster:{
                    accept: 'image/jpeg,image/png,image/jpg',
                    filesize: 1,
                },
                video: {
                    required: true,
                    accept: 'video/mp4,video/x-matroska,video/avi,video/quicktime'
                },
                description: {
                    required: true,
                    maxlength:  {{env('TEXT_AREA_LIMIT')}}
                },
                sub_category: {
                    required: true,
                },
                category: {
                    required: true,
                },
            },
            // Specify validation error messages
            messages: {
                package: {
                    required: 'Select package from dropdown.',
                },
                sub_category: {
                    required: 'Select subcategory from dropdown.',
                },
                category: {
                    required: 'Select category from dropdown.',
                },
                video_name: {
                    required: 'Video name is required.',
                    maxlength: "Video name must not exceed "+'{{env('INPUT_SMALL')}}'+" characters."
                },
                poster:{
                    accept: 'Only jpg , jpeg or png file can be selected.'
                },
                video: {
                    required: "Please select a video.",
                    accept: 'Video type should be mp4, avi or mkv.'
                },
                description: {
                    required: 'Video description is required.',
                    maxlength: "Video description must not exceed "+'{{env('TEXT_AREA_LIMIT')}}'+" characters."

                }
            },

        });

        $("#edit-video-form").validate({
            rules: {
                package: {
                    required:true,
                },
                video_name: {
                    required: true,
                    maxlength:  {{env('INPUT_SMALL')}},
                },
                poster: {
                    accept: 'image/jpeg,image/jpg,image/png',
                    filesize: 1,

                },
                description: {
                    required: true,
                    maxlength:  {{env('TEXT_AREA_LIMIT')}}
                },
                category: {
                    required: true,
                },
            },
            // Specify validation error messages
            messages: {
                package: {
                    required: 'Select package from dropdown.',
                },
                video_name: {
                    required: 'Video name is required.',
                    maxlength: "Video name must not exceed "+'{{env('INPUT_SMALL')}}'+" characters."
                },
                category: {
                    required: 'Select category from dropdown.',
                },
                poster: {
                    accept: 'Only jpg , jpeg or png file can be selected.'
                },
                description: {
                    required: 'Description is required',
                    maxlength: "Description must not exceed "+'{{env('TEXT_AREA_LIMIT')}}'+" characters."

                }
            },
            submitHandler: function(form) {
                $.blockUI({ message: '<img src="/images/loader1.gif" class="loader-size" />', css: { backgroundColor: 'transparent', border:'transparent'} });

                form.submit();
            }
        });
        $("#user-edit-form").validate({
            rules: {
                name: {
                    required: true,
                    maxlength:  {{env('INPUT_SMALL')}}
                },
                email: {
                    required: true,
                    email: true
                },
                phone_no: {
                    required: true,
                    digits: true,
                    minlength: 11,
                    maxlength: 11,
                },
                institution: {
                    required: true,
                    maxlength:  {{env('INPUT_BIG')}},
                }
            },
            // Specify validation error messages
            messages: {
                name: {
                    required: "Name is required.",
                    maxlength: "Name must not exceed "+'{{env('INPUT_SMALL')}}'+" characters.",
                },
                email: {
                    required: 'Email is required.',
                    email: 'Please enter a valid email address!'
                },
                phone_no: {
                    required: "Phone number is required.",
                    digits: 'Phone no should contain number only.',
                    minlength: 'Please enter valid phone no.',
                    maxlength: 'Please enter valid phone no.',
                },
                institution: {
                    required: 'Community/Institution name is required',
                    maxlength: "Institution name must not exceed "+'{{env('INPUT_BIG')}}'+" characters."
                }
            },
            submitHandler: function(form) {
                $.blockUI({ message: '<img src="/images/loader1.gif" class="loader-size" />', css: { backgroundColor: 'transparent', border:'transparent'} });

                form.submit();
            }
        });
        $("#change-password-form").validate({
            rules: {
                current_password: "required",
                new_password: {
                    required: true,
                    // minlength: 8,
                    password_check: true


                },
                confirm_new_password: {
                    required: true,
                    equalTo: '#new_password'
                },
            },
            // Specify validation error messages
            messages: {
                current_password: "please enter your current password.",
                new_password: {
                    required: 'Please enter your new password.',
                    // minlength: "Password should be of minimum 8 characters."
                },
                confirm_new_password: {
                    required: 'Please re-enter your password to confirm.',
                    equalTo: 'Password doesn\'t match.'
                },
            },
            submitHandler: function(form) {
                $.blockUI({ message: '<img src="/images/loader1.gif" class="loader-size" />', css: { backgroundColor: 'transparent', border:'transparent'} });

                form.submit();
            }
        });

        $("#website-settings-form").validate({
            rules: {
                facebook: {
                    required: function() {
                        return $('input[name=facebook_check]:checked').length > 0;
                    }
                },
                twitter: {
                    required: function() {
                        return $('input[name=twitter_check]:checked').length > 0;
                    }
                },
                linkedin: {
                    required: function() {
                        return $('input[name=linkedin_check]:checked').length > 0;
                    }
                },
                instagram: {
                    required: function() {
                        return $('input[name=instagram_check]:checked').length > 0;
                    }
                },

            },
            // Specify validation error messages
            messages: {
                facebook: {
                    required: 'Facebook url is required if checkbox is checked.',
                },
                twitter: {
                    required: 'Twitter url is required if checkbox is checked.',
                },
                linkedin: {
                    required: 'Linkedin url is required if checkbox is checked.',
                },
                instagram: {
                    required: 'Instagram url is required if checkbox is checked.',
                },

            },
            submitHandler: function(form) {
                $.blockUI({ message: '<img src="/images/loader1.gif" class="loader-size" />', css: { backgroundColor: 'transparent', border:'transparent'} });

                form.submit();
            }
        });
        $("#new-category-form").validate({
            rules: {
                package : {
                    required: true
                },
                name:{
                    required: true,
                    maxlength: {{env('INPUT_SMALL')}}
                },
                image:{
                    required: true,
                    accept: "image/jpeg,image/jpg,image/jpg"
                },
                detail:{
                    required: true,
                    maxlength: {{env('TEXT_AREA_LIMIT')}}
                },
            },

            // Specify validation error messages
            messages: {
                package: {
                    required: 'Select Package from dropdown',
                },
                name: {
                    required: 'Category name is required.',
                    maxlength: "Category name must not exceed {{env('INPUT_SMALL')}} characters."
                },
                image:{
                    required: "Image is required.",
                    accept: "Only jpeg, jpg or png can be uploaded."
                },
                detail:{
                    required: "Category details are required.",
                    maxlength: "Category details must not exceed {{env('TEXT_AREA_LIMIT')}} characters."
                },
            },
            submitHandler: function(form) {
                $.blockUI({ message: '<img src="/images/loader1.gif" class="loader-size" />', css: { backgroundColor: 'transparent', border:'transparent'} });

                form.submit();
            }
        });
        $("#new-sub-category-form").validate({
            rules: {
                category_id : {
                    required: true
                },
                name:{
                    required: true,
                    maxlength: {{env('INPUT_SMALL')}}
                },

                detail:{
                    required: true,
                    maxlength: {{env('TEXT_AREA_LIMIT')}}
                },
            },

            // Specify validation error messages
            messages: {
                category_id: {
                    required: 'Select Category from dropdown',
                },
                name: {
                    required: 'Sub Category name is required.',
                    maxlength: "Sub Category name must not exceed {{env('INPUT_SMALL')}} characters."
                },

                detail:{
                    required: "Sub Category details are required.",
                    maxlength: "Sub Category details must not exceed {{env('TEXT_AREA_LIMIT')}} characters."
                },
            },
            submitHandler: function(form) {
                $.blockUI({ message: '<img src="/images/loader1.gif" class="loader-size" />', css: { backgroundColor: 'transparent', border:'transparent'} });

                form.submit();
            }
        });
        $("#new-sub-category-form").validate({
            rules: {
                category_id : {
                    required: true
                },
                e_name:{
                    required: true,
                    maxlength: {{env('INPUT_SMALL')}}
                },

                e_detail:{
                    required: true,
                    maxlength: {{env('TEXT_AREA_LIMIT')}}
                },
            },

            // Specify validation error messages
            messages: {
                category_id: {
                    required: 'Select Category from dropdown',
                },
                e_name: {
                    required: 'Sub Category name is required.',
                    maxlength: "Sub Category name must not exceed {{env('INPUT_SMALL')}} characters."
                },

                e_detail:{
                    required: "Sub Category details are required.",
                    maxlength: "Sub Category details must not exceed {{env('TEXT_AREA_LIMIT')}} characters."
                },
            },
            submitHandler: function(form) {
                $.blockUI({ message: '<img src="/images/loader1.gif" class="loader-size" />', css: { backgroundColor: 'transparent', border:'transparent'} });

                form.submit();
            }
        });
        $("#edit-category-form").validate({
            rules: {
                package : {
                    required: true
                },
                e_name:{
                    required: true,
                    maxlength: {{env('INPUT_SMALL')}}
                },
                image:{
                    accept: "image/jpeg,image/jpg,image/jpg"
                },
                e_detail:{
                    required: true,
                    maxlength: {{env('TEXT_AREA_LIMIT')}}
                },
            },

            // Specify validation error messages
            messages: {
                package: {
                    required: 'Select Package from dropdown',
                },
                e_name: {
                    required: 'Category name is required.',
                    maxlength: "Category name must not exceed {{env('INPUT_SMALL')}} characters."
                },
                image:{
                    accept: "Only jpeg, jpg or png can be uploaded."
                },
                e_detail:{
                    required: "Category details are required.",
                    maxlength: "Category details must not exceed {{env('TEXT_AREA_LIMIT')}} characters."
                },
            },
            submitHandler: function(form) {
                $.blockUI({ message: '<img src="/images/loader1.gif" class="loader-size" />', css: { backgroundColor: 'transparent', border:'transparent'} });

                form.submit();
            }
        });


    </script>
