$(function() {
    var categoryTable = $('#sub_category_table');
    categoryTable.DataTable({
        processing: true,
        serverSide: true,
        ajax: '/subcategory/datatables',
        columns: [
            {data: 'sub_category_name', name: 'sub_category_name'},
            {data: 'category_name', name: 'category_name'},
            {data: 'sub_category_description', name: 'sub_category_description'},
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
