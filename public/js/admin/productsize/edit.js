var KTFormControls = function () {
    // Private functions

    var addCategoryValidation = function () {

        $( "#add-category-form" ).validate({
            // define validation rules
            rules:{
                product_size_name: {
                    required: true,
                }
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
    removeErrorClassFromSelect2("#category_id");
}

jQuery(document).ready(function() {
    initWidgets();
    KTFormControls.init();
});
