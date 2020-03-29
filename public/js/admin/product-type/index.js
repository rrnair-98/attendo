$(function() {
    var categoryTable = $('#sub_category_table');
    categoryTable.DataTable({
        processing: true,
        serverSide: true,
        ajax: '/product-type/datatables',
        columns: [
            {data: 'product_type_name', name: 'product_type_name'},
            {data: 'sub_category_name', name: 'sub_category_name'},
            {data: 'product_type_description', name: 'product_type_description'},
            {data: 'image', name: 'image'},
            {data: 'view', name: 'view'},
            {data: 'edit', name: 'edit'},
            {data: 'delete', name: 'delete'},
        ]
    });

    categoryTable.on('click', '.delete', function(e) {
        $id = $(this).attr('id');
        $('#delete_form').attr('action', '/subcategory/' + $id);
    });
})
