<script src="<?php echo base_url() . 'backend_asset/admin/js/' ?>app.js"></script>
<script>
    var isDataChange = false;

    function isDirty(val) {
        if(val!="" || val.trim().length>0 ){
            isDataChange = true;
        }
        
    }

    function headingSearch() {
        var md_steward_id=jQuery('#md_steward_id').val();
        var doctor_id=jQuery('#doctor_id').val();
        var patient_id=jQuery('#name').val();
        var md_stayward_response = jQuery('#md_stayward_response').val().trim();
        var psa = jQuery('#psa').val().trim();
        var new_initial_dx = jQuery('#new_initial_dx :selected').text();
        var new_initial_rx = jQuery('#new_initial_rx :selected').text();
        var additional_comment_option = jQuery('#additional_comment_option :selected').text();
        
        var new_initial_dot = jQuery('#new_initial_dot').val().trim();
       // var pct = jQuery('#pct').val().trim();
        var url = "<?php echo base_url() ?>patient/email_smtp";
        if(md_stayward_response != "" || psa != "" || new_initial_dx != "" || new_initial_rx != "" ||  additional_comment_option != "" || new_initial_dot != "" /* || pct != "" */ ){
            $.ajax({
            method: "POST",
            url: url,
            data: {
                patient_id:patient_id,
                md_stayward_response: md_stayward_response,
                psa: psa,
                new_initial_dx: new_initial_dx,
                new_initial_rx: new_initial_rx,
                additional_comment_option: additional_comment_option,
                
                new_initial_dot: new_initial_dot,
                //pct: pct,
                doctor_id:doctor_id,
                md_steward_id:md_steward_id
            },
            success: function(data) {
                $('.center').html(data);
            }
        });
        }
       
    }

    function headingSearch1() {

        var to_email=jQuery('#to_email').val();
        var patient_id=jQuery('#name').val();
        var room_number=jQuery('#room_number').val();
        var care_unit_name=jQuery('#care_unit_name').val();
        var doctor_name=jQuery('#doctor_name').val();
        var symptom_onset=jQuery('#symptom_onset').val();
        var culture_source_name=jQuery('#culture_source_name').val();
        var organism_name=jQuery('#organism_name').val();
        var precautions_name=jQuery('#precautions_name').val();
        var md_steward = jQuery('#md_steward').val();
        var date_of_start_abx = jQuery('#date_of_start_abx').val();
        var initial_rx_name = jQuery('#initial_rx_name').val();
        var initial_dx_name = jQuery('#initial_dx_name').val();
        var initial_dot = jQuery('#initial_dot').val();
        var infection_surveillance_checklist = jQuery('#infection_surveillance_checklist').val();
        var criteria_met = jQuery('#criteria_met').val();
        var md_stayward_response = jQuery('#md_stayward_response').val();
        var new_initial_rx_name = jQuery('#new_initial_rx_name').val();
        var psa = jQuery('#psa').val();
        var new_initial_dx_name = jQuery('#new_initial_dx_name').val();
        var new_initial_dot = jQuery('#new_initial_dot').val();
       // var pct = jQuery('#pct').val().trim();
        var additional_comment_option = jQuery('#additional_comment_option').val().trim();

       /*  var md_steward_id=jQuery('#md_steward_id').val();
        var doctor_id=jQuery('#doctor_id').val();
        */
        //var md_stayward_response = jQuery('#md_stayward_response').val().trim();
       
        
        
        
        
       
       
        var url = "<?php echo base_url() ?>patient/email_smtp1";
        if(patient_id != "" /* || psa != "" || new_initial_dx != "" || new_initial_rx != "" ||  additional_comment_option != "" || new_initial_dot != "" || pct != "" */ ){
            $.ajax({
            method: "POST",
            url: url,
            data: {
                to_email:to_email,
                patient_id:patient_id,
                room_number:room_number,
                care_unit_name:care_unit_name,
                doctor_name:doctor_name,
                symptom_onset:symptom_onset,
                culture_source_name:culture_source_name,
                organism_name:organism_name,
                precautions_name:precautions_name,
                md_steward:md_steward,
                date_of_start_abx:date_of_start_abx,
                initial_rx_name:initial_rx_name,
                initial_dx_name:initial_dx_name,
                initial_dot:initial_dot,
                infection_surveillance_checklist:infection_surveillance_checklist,
                criteria_met:criteria_met,
                md_stayward_response:md_stayward_response,
                new_initial_rx_name: new_initial_rx_name,
                psa: psa,
                new_initial_dx_name: new_initial_dx_name,
                new_initial_dot: new_initial_dot,
                //pct: pct,
                additional_comment_option: additional_comment_option,
                
                
               
               /*  doctor_id:doctor_id,
                md_steward_id:md_steward_id */
            },
            success: function(data) {
                $('.center').html(data);
            }
        });
        }
       
    }

    App.datatables();
    jQuery('body').on('click', '#submit1', function() {
       // if (isDataChange1){
        headingSearch1();
        //}
    });
    App.datatables();
    jQuery('body').on('click', '#submit', function() {
        // e.preventDefault();
        /*  console.log(isDataChange);
         return false; */
       /*   if(headingSearch()=""){
             return false;
         } */
         /* if($("#md_stayward_response").val()!="" && $("#new_initial_dx").val()!=""
         && $("#new_initial_rx").val()!="" && $("#new_initial_dot").val()!="" 
         && $("#pct").val()!=""){
             return true;
         }
          */
        if (isDataChange) {
            headingSearch()
        }
        /* console.log(isDataChange);
         return false; */

        // if ($("#infection_surveillance_checklist").val() != "N/A" && $("#infection_surveillance_checklist").val() == "Loeb") {
        //     alert("Printable ABX Checklist form will appear after clicking OK button. Please complete and submit the form.");
        //     window.open("<?php //echo base_url(); ?>application/modules/patient/views/form1.html","_self");
        // };

        // if ($("#infection_surveillance_checklist").val() != "N/A" && $("#infection_surveillance_checklist").val() == "McGeer – UTI") {
        //     alert("Printable ABX Checklist form will appear after clicking OK button. Please complete and submit the form.");
        //     window.open("<?php //echo base_url(); ?>application/modules/patient/views/form2.html","_self");
        // };

        // if ($("#infection_surveillance_checklist").val() != "N/A" && $("#infection_surveillance_checklist").val() == "McGeer – RTI") {
        //     alert("Printable ABX Checklist form will appear after clicking OK button. Please complete and submit the form.");
        //     window.open("<?php //echo base_url(); ?>application/modules/patient/views/form3.html","_self");   
        // };

        // if ($("#infection_surveillance_checklist").val() != "N/A" && $("#infection_surveillance_checklist").val() == "McGeer – GITI") {
        //     alert("Printable ABX Checklist form will appear after clicking OK button. Please complete and submit the form.");
        //       window.open("<?php //echo base_url(); ?>application/modules/patient/views/form4.html","_self"); 
        // };

        // if ($("#infection_surveillance_checklist").val() != "N/A" && $("#infection_surveillance_checklist").val() == "McGeer –SSTI") {
        //     alert("Printable ABX Checklist form will appear after clicking OK button. Please complete and submit the form.");
        //     window.open("<?php //echo base_url(); ?>application/modules/patient/views/form5.html","_self");
        // };

        // var form_name = this.form.id;
        // if (form_name == '[object HTMLInputElement]')
        //     form_name = 'addFormAjax';
        // $("#" + form_name).validate({
        //     rules: {
        //         // name: "required",
        //     },
        //     submitHandler: function(form) {
        //         jQuery(form).ajaxSubmit({});
        //     }
        // });

    });
    $('#common_datatable_menucat').dataTable({
        order: [],
        columnDefs: [{
            orderable: false,
            targets: [0, 13]
        }]
    });

    function getPatient() {
        var care_unit = $("#care_unit").val();
        $("#careUnitID").val(care_unit);

        var care_unit1 = $("#care_unit1").val();
        if (care_unit1) {
            $(".hidetext").show();
        } else {
            $(".hidetext").hide();
        }
    }
    $(".hidetext").hide();

    /* function getPatientId(id) {
        var patient_mode = $("#patient_mode").val();
        if (patient_mode == 'Existing') {
            var url = "<?php echo base_url() ?>patient/get_patient_id";
            $.ajax({
                method: "POST",
                url: url,
                data: {
                    careunit_id: id
                },
                success: function(response) {
                    $("#patient_id_dropbox").html(response);
                },
                error: function(error, ror, r) {
                    bootbox.alert(error);
                },
            });
        }


    } */

    function deletePatient(patient_id) {
        bootbox.confirm({
            message: "Do you really want to delete patient?",
            buttons: {
                confirm: {
                    label: 'Ok',
                    className: '<?php echo THEME_BUTTON; ?>'
                },
                cancel: {
                    label: 'Cancel',
                    className: 'btn-danger'
                }
            },
            callback: function(result) {
                if (result) {
                    var url = "<?php echo base_url() ?>patient/delete_patient";
                    $.ajax({
                        method: "POST",
                        url: url,
                        data: {
                            patient_id: patient_id
                        },
                        dataType: "json",
                        success: function(response) {
                            if (response.status == 200) {
                                $("#msg").html("<div class='alert alert-success'>" + response.message + "</div>");
                                setTimeout(function() {
                                    window.location.href = response.url;
                                }, 1500);
                            } else {
                                $("#msg").html("<div class='alert alert-danger'>" + response.message + "</div>");
                            }
                        },
                        error: function(error, ror, r) {
                            bootbox.alert(error);
                        },
                    });
                } else {
                    $('.modal-backdrop').remove();
                }
            }
        });
    }

    $("#date").datepicker({
        dateFormat: 'mm/dd/yy'
    });
    $("#date1").datepicker({
        dateFormat: 'mm/dd/yy'
    });
</script>