$(function() {
    var categoryTable = $('#order_table');
    categoryTable.DataTable({
        processing: true,
        serverSide: true,
        ajax: '/order/datatable',
        columns: [
            {data: 'id', name: 'id'},
            {data: 'price', name: 'category_description'},
            {data: 'str_order_delivery_status', name: 'delivery_status'},
            {data: 'str_payment_status', name: 'payment_status'},
            {data: 'created_at', name: 'created_at'},
            {data: 'edit', name: 'edit'},
        ]
    });

    categoryTable.on('click', '.delete', function(e) {
        $id = $(this).attr('id');
        $('#delete_form').attr('action', '/category/' + $id);
    });
})
