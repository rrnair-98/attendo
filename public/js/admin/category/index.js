$(function() {
    var categoryTable = $('#category_table');
    categoryTable.DataTable({
        processing: true,
        serverSide: true,
        ajax: '/category/datatables',
        columns: [
            {data: 'category_name', name: 'category_name'},
            {data: 'category_hsn', name: 'category_hsn'},
            {data: 'category_description', name: 'category_description'},
            {data: 'image', name: 'image'},
            {data: 'view', name: 'view'},
            {data: 'edit', name: 'edit'},
            {data: 'delete', name: 'delete'},
        ]
    });

    categoryTable.on('click', '.delete', function(e) {
        $id = $(this).attr('id');
        $('#delete_form').attr('action', '/category/' + $id);
    });
})
