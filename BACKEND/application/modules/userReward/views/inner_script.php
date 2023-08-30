<script src="<?php echo base_url() . 'backend_asset/admin/js/' ?>app.js"></script>
<script>
   

    

    App.datatables();
    jQuery('body').on('click', '#submit1', function() {
       // if (isDataChange1){
        headingSearch1();
        //}
    });
    App.datatables();
    jQuery('body').on('click', '#submit', function() {
       
        if (isDataChange) {
            headingSearch()
        }
       

    });
    $('#common_datatable_menucat').dataTable({
        order: [],
        columnDefs: [{
            orderable: false,
            targets: [0, 5]
        }]
    });

    

    $("#date").datepicker({
        dateFormat: 'mm/dd/yy'
    });
    $("#date1").datepicker({
        dateFormat: 'mm/dd/yy'
    });
</script>