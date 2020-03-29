var KTFormControls = function () {
    // Private functions

    var addCategoryValidation = function () {

        $( "#add-category-form" ).validate({
            // define validation rules
            rules:{
                category_name: {
                    required: true,
                },
                category_hsn: {
                    required: true,
                },
                category_description:{
                    required: true,
                },
                category_image:{
                    required: true,
                    accept: "image/*",
                    // extension: "png | jpeg | jpg | svg",
                    maxsize: 5000000,
                },
            },

            //display error alert on form submit
            invalidHandler: function(event, validator) {
                KTUtil.scrollTop();
                event.preventDefault();
            },

            submitHandler: function (form) {
                // submit the form
                form[0].submit();
            }
        });
    }

    return {
        // public functions
        init: function() {
            addCategoryValidation();
        }
    };
}();


jQuery(document).ready(function() {
    KTFormControls.init();
});
