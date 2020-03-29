// Class definition
"use strict";

// Class definition




var KTFormControls = function () {
    // Private functions

    var addProductValidation = function () {


        $("#add_product_form").validate({
            // define validation rules
            rules: {
                product_type_id: {
                    required: true,
                },
                product_size_id: {
                    required: true,
                },
                product_description: {
                    required: true,
                },
            },

            //display error alert on form submit
            invalidHandler: function (event, validator) {
                KTUtil.scrollTop();
                event.preventDefault();
            },

            submitHandler: function (form) {
                if (priceCount != 0) {
                    form[0].submit(); // submit the form
                }else {
                    window.alert("Add one Price");
                }
            }
        });
    }

    return {
        // public functions
        init: function () {
            // demo1();
            addProductValidation();
        }
    };
}();

var modalFormControl = function () {
    // Private functions
    var addPriceValidation = function ()  {


        $("#add-price-form").validate({
            // define validation rules
            rules: {
                product_price: {
                    required: true,
                    number:true
                },
                product_price_wef: {
                    required: true,
                },
            },

            //display error alert on form submit
            invalidHandler: function (event, validator) {
                event.preventDefault();
            },
            submitHandler: function (form, e) {

                e.preventDefault();
                // form[0].submit(); // submit the form

                const priceDetails = $("#price-details")[0];
                let output = priceDetails.innerHTML;

                let product_price = $("#product_price").val();
                let product_price_wef = $("#product_price_wef").val();

                output += `
<tr id="price-${++count}">

    <td id="product-price-${count}">
        <span id="person-name-text-${count}">${product_price}</span>
        <input id="input-person-name-${count}" type="text" hidden value="${product_price}" name="product_price[]">
    </td>

    <td id="product-price-wef-${count}">
        <span id="person-name-text-${count}">${product_price_wef}</span>
        <input id="input-person-name-${count}" type="text" hidden value="${product_price_wef}" name="product_price_wef[]">
    </td>

    <td>
        <a href="#" id="${count}" class="btn btn-danger btn-icon delete-person" data-toggle="modal" data-target="#delete-modal" data-id="${count}">
            <i id="${count}" class="flaticon-delete delete-price" data-id="${count}"></i>
        </a>
    </td>
</tr>
        `;
                priceDetails.innerHTML = output;
                $("#add-price-modal").modal('hide');
                $('body').removeClass('modal-open');
                $('.modal-backdrop').remove();
                priceCount++;
            }
        });
    }

    return {
        // public functions
        init: function () {
            // demo1();
            addPriceValidation();
        }
    };
}();


function initWidgets() {

    initializeSelect2($("#product_type_id"),'/product-type/all', 'Select a Product Type');
    removeErrorClassFromSelect2("product_type_id");

    initializeSelect2($("#product_size_id"),'/product-size/all', 'Select a Product Size');
    removeErrorClassFromSelect2("product_size_id");

    $('#product_price_wef').datepicker({
        todayHighlight: true,
        format: 'dd/mm/yyyy',
        startDate: new Date() ,
        templates: {
            leftArrow: '<i class="la la-angle-left"></i>',
            rightArrow: '<i class="la la-angle-right"></i>'
        }
    });
}


jQuery(document).ready(function () {
    KTFormControls.init();
    //KTUppy.init();
    modalFormControl.init();


    $("#add-price").click(blankAllFields);


    function blankAllFields() {
        /*
        * Please edit the parent class or the sibling if you are adding any elements in the form group
        * */
        //Add Person
        $("#product_price-error").parent().parent().removeClass('is-invalid');
        $("#product_price-error").prev().removeClass('is-invalid');
        $("#product_price-error").remove();

        $("#product_price_wef-error").parent().parent().removeClass('is-invalid');
        $("#product_price_wef-error").prev().removeClass('is-invalid');
        $("#product_price_wef-error").remove();

        /*
        * Please edit the parent class if you are adding any elements in the form group
        * */

        $('#product_price')[0].value = "";
        $('#product_price_wef')[0].value = "";
    }

    $("#price-details").click(setDeletePriceId);

    $("#delete-price-btn").click(deletePrice);

    function deletePrice() {
        let id = $("#delete_price_id").val();
        console.log(id);

        let delete_id = $("#product-price-id-"+id).val();

        const deletedPrices = $("#deleted-prices")[0];
        let output = deletedPrices.innerHTML;
        if (delete_id != null)
            output += `<input type="hidden"  value="${delete_id}" name="deleted_product_price_id[]">`;
        deletedPrices.innerHTML = output;

        $("#price-" + id).remove();
        priceCount--;
    }

    function setDeletePriceId(e) {
        if (e.target.classList.contains("delete-price")) {
            let id = e.target.id;
            console.log("value is");
            console.log(id);
            $('#delete_price_id').val(id);
        }
    }

    initWidgets();
});
