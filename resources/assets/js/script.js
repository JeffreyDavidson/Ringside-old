$(document).ready(function() {
    $('.datatable').DataTable({
        "pagingType": "full_numbers",
        "columnDefs": [
            { "width": "20px", "targets": 0 },
            { "width": "150px", "targets": -1 },
            { "targets": -1, "orderable": false }
        ]
    });
} );
