$(function() {
    var categoryTable = $('#category_table');
    categoryTable.DataTable({
        processing: true,
        serverSide: true,
        ajax: '/product-size/datatables',
        columns: [
            {data: 'product_size_name', name: 'product_size_name'},
            {data: 'view', name: 'view'},
            {data: 'edit', name: 'edit'},
            {data: 'delete', name: 'delete'}
        ]
    });

    categoryTable.on('click', '.delete', function(e) {
        $id = $(this).attr('id');
        $('#delete_form').attr('action', '/productsize/' + $id);
    });
})
