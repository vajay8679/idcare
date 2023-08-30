<script src="<?php echo base_url() . 'backend_asset/admin/js/' ?>app.js"></script> 
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.16.0/jquery.validate.js"></script>
<script>
    App.datatables();
    jQuery('body').on('click', '#submit', function () {

        var form_name = this.form.id;
        if (form_name == '[object HTMLInputElement]')
            form_name = 'addFormAjax';
        $("#" + form_name).validate({
            rules: {
                name: "required",
                care_unit_code: "required",
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

