$(document).ready(function () {
    function showDatatable(url,element) {
        $('#'+ element).DataTable({
            "draw": 0,
            "serverSide": true,
            "ajax": url
        });
    }
    showDatatable("api/call-recording/list","example");

    
});