(function ($) {
    "use strict";

    $(document).ready(function () {
        $(document).on("click", ".page-title-action", function (e) {
            $('#ess-shortcodeFrm').toggleClass('d-none');
        });
        $('.alphabetic_only').keypress(function (event) {
            var charCode = event.which;
            // Allow only alphabetic characters (a-z, A-Z)
            if ((charCode >= 65 && charCode <= 90) || (charCode >= 97 && charCode <= 122) || charCode === 32) {
                return true;
            } else {
                return false;
            }
        });
        $(document).on("click", "#submitShortcode", function (e) {
            e.preventDefault();
            $('#ess-shortcodeFrm .form-group').each(function () {
                $(this).removeClass('c-error');
                $(this).find('.error-input-span').remove();
            });
            var shortCodeValid = true;
            var ess_shortcode_name = $('#ess_shortcode_name').val();
            if ($.trim(ess_shortcode_name) == '') {
                shortCodeValid = false;
                $('#ess_shortcode_name').closest('.form-group').addClass('c-error');
                $('#ess_shortcode_name').closest('.form-group').append('<span for="ess_shortcode_name" class="error-input-span">Please enter a name</span>');
            }
            var shortcode_file = $('#shortcode_file').val();
            if ($.trim(shortcode_file) == '') {
                shortCodeValid = false;
                $('#shortcode_file').closest('.form-group').addClass('c-error');
                $('#shortcode_file').closest('.form-group').append('<span for="shortcode_file" class="error-input-span">Please enter a name</span>');
            }
            if (shortCodeValid != false) {
                var formData = new FormData($('#ess-shortcodeFrm')[0]);
                var shortcode_file = $('#shortcode_file');
                $.ajax({
                    url: ESSObj.ajaxurl, // Replace with your own URL to handle form submission
                    method: 'POST', // Use the appropriate HTTP method (POST, GET, etc.)
                    data: formData,
                    processData: false,
                    contentType: false,              
                    success: function (response) {
                        console.log('Form submitted successfully');
                        $('p.response-msg').html(response.data.msg);
                        setTimeout(() => {
                            location.reload();
                        }, 500);
                    },
                    error: function (xhr, status, error) {
                        if(response.data.msg){
                            $('p.response-msg').html('Some error occur. Please try again');
                        } else{
                            $('p.response-msg').html(response.data.msg);
                            setTimeout(() => {
                                location.reload();
                            }, 500);
                        }
                        // console.log('Error submitting form');
                    }
                });
            }
        });
    });


})(jQuery);