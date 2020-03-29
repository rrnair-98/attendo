var KTFormControls = function () {
    // Private functions

    var addCategoryValidation = function () {

        $( "#add-category-form" ).validate({
            // define validation rules
            rules:{
                sub_category_id: {
                    required: true,
                },
                product_type_name: {
                    required: true,
                },
                product_type_description:{
                    required: true,
                },
                product_type_image:{
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
    let subCategory                    =   $("#sub_category_id");
    initializeSelect2(subCategory,'/subcategory/all', 'Select a Sub Category');
    removeErrorClassFromSelect2("sub_category_id");
}

jQuery(document).ready(function() {
    initWidgets();
    KTFormControls.init();
});
