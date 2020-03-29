function initializeSelect2(selector, url, placeholder = "") {
    let select = $(selector);
    select.select2({
        placeholder: placeholder,
        containerCss: "text-capitalize",
        dropdownCssClass: "text-capitalize",
        width: "100%",

        ajax: {
            url: url,
            method: 'GET',
            processResults: (data) => ({
                results: data.map((element) => ({
                    id: element.id,
                    text: element.name,
                }))
            })
        }
    })
}
function initializeSelect2WithOutAjax(selector, placeholder = "") {
    let select = $(selector);
    select.select2({
        placeholder: placeholder,
        containerCss: "text-capitalize",
        dropdownCssClass: "text-capitalize",
        width: "100%"
    })
}

function removeErrorClassFromSelect2(select2Id){
    $(select2Id).change(function () {
        $(this).removeClass('is-invalid');
        $(this).parent().removeClass('is-invalid');
        $(select2Id+"-error").remove();
    });
}


var getGetOrdinal = function(n) {
    var s=["th","st","nd","rd"],
        v=n%100;
    return n+(s[(v-20)%10]||s[v]||s[0]);
}
