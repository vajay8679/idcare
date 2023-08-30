<script src="<?php echo base_url() . 'backend_asset/admin/js/' ?>app.js"></script> 
<script>
    App.datatables();
    jQuery('body').on('click', '#submit', function () {

        var form_name = this.form.id;
        if (form_name == '[object HTMLInputElement]')
            form_name = 'addFormAjax';
        $("#" + form_name).validate({
            rules: {
                name: "required",
            },
            submitHandler: function (form) {
                jQuery(form).ajaxSubmit({
                });
            }
        });

    });
    $('#common_datatable_menucat').dataTable({
        order: [],
        columnDefs: [{orderable: false, targets: [0, 3]}]
    });
</script>

