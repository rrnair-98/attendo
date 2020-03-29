var KTFormControls = function () {
    // Private functions

    var addCategoryValidation = function () {

        $( "#add-category-form" ).validate({
            // define validation rules
            rules:{
                category_id: {
                    required: true,
                },
                sub_category_name: {
                    required: true,
                },
                sub_category_description:{
                    required: true,
                },
                sub_category_image:{
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

function initWidgets() {
    let category                    =   $("#category_id");
    initializeSelect2(category,'/category/all', 'Select a Category');
    removeErrorClassFromSelect2(category);
}

jQuery(document).ready(function() {
    initWidgets();
    KTFormControls.init();
});
