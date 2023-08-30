<link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.16.0/jquery.validate.js"></script>
<style>

.btn {
    margin: 1px 0;
    background-color: #b9adad;
}

.modal_popup{
    display: none;
}

.form-group {
    margin-bottom: 10px;
}


.modal-body1 {
    padding: 0px 15px;
}
.sendmail{
    float: right;
    margin: -41px 0;
}

.mailmodel{
    margin-left:-15px;
    margin-right:-15px;
}

@media only screen and (min-width: 668px) and (max-width: 1600px) {
        .sendmail{
            margin-top: -17px;
                 }  
    }

    @media only screen and (max-width: 600px) {
        .sendmail{
            margin-top: -32px;
                 }  
       
        }
</style>
<!-- Page content -->
<div id="page-content">
    <!-- Datatables Header -->
    <ul class="breadcrumb breadcrumb-top">
        <li>
            <a href="<?php echo site_url('pwfpanel'); ?>">Home</a>
        </li>
        <li>
            <a href="<?php echo site_url($this->router->fetch_class()); ?>"><?php echo $title; ?></a>
        </li>
    </ul>
    <!-- END Datatables Header -->
    <div class="row">
        
        <div class="col-md-12" >
        <div style="margin-bottom:20px;">
        <button type="button" class="btn btn-primary sendmail" data-toggle="modal" data-target="#exampleModal" data-whatever="@mdo" >Send Patient Details on Mail</button>
        </div>
            <div class="block">
           
                <!-- Customer Info Title -->
                <div class="block-title">
                    <h2><i class="fa fa-file-o"></i> <strong><?php echo $title; ?></strong> Info</h2>
                </div>
                <!-- END Customer Info Title -->
                <!-- Customer Info -->
                <div class="block-section text-center">
<!--                    <a href="javascript:void(0)">
                        <img src="<?php echo (!empty($results->img)) ? base_url() . $results->img : base_url() . 'backend_asset/images/default.jpg'; ?>" alt="avatar" class="img-circle" style=" width: 100px; height: 100px;">
                    </a>-->
<!--                    <h3>
                        <strong><?php echo $results->patient_name; ?></strong><br><small></small>
                    </h3>-->
                </div>
                <table class="table table-borderless table-striped table-vcenter">
                    <tbody>
                        <tr>
                            <td class="text-center"><strong>Patient ID</strong></td>
                            <td><?php echo $results->patient_id; ?></td>
                        </tr>
                        <?php if(empty($results->room_number)){ ?>
                        <tr>
                            <td class="text-center"><strong>Room Number</strong></td>
                            <td>NULL</td>
                        </tr>
                        <?php   } else{ ?>
                            <tr>
                            <td class="text-center"><strong>Room Number</strong></td>
                            <td><?php echo $results->room_number; ?></td>
                        </tr>
                        <?php } ?>
                        <!-- <tr>
                            <td class="text-center"><strong>Address</strong></td>
                            <td><?php echo $results->address; ?></td>
                        </tr> -->
                        <tr>
                            <td class="text-center"><strong>Care Unit</strong></td>
                            <td><?php echo ucwords($results->care_unit_name); ?></td>
                        </tr>
                        <tr>
                            <td class="text-center"><strong>Provider MD</strong></td>
                            <td><?php echo ucwords($results->doctor_name); ?></td>
                        </tr>
                        <?php if($results->symptom_onset == 'Facility'){ ?>
                        <tr>
                            <td class="text-center"><strong>Infection Onset</strong></td>
                            <td>Facility/HAI<!-- <?php echo $results->symptom_onset; ?> --></td>    
                        </tr>
                        <?php } else if($results->symptom_onset == 'Hospital'){ ?>
                        <tr>
                            <td class="text-center"><strong>Infection Onset</strong></td>
                            <td>Hospital/CAI<!-- <?php echo $results->symptom_onset; ?> --></td>    
                        </tr>
                        <?php }else{ ?>
                        <tr>
                            <td class="text-center"><strong>Infection Onset</strong></td>
                            <td><?php echo $results->symptom_onset; ?></td>    
                        </tr>
                        <?php } ?>
                        <!-- <tr>
                            <td class="text-center"><strong>MD Steward Consult</strong></td>
                            <td><?php //echo $results->md_stayward_consult; ?></td>
                        </tr> -->
                        <tr>
                            <td class="text-center"><strong>MD Steward</strong></td>
                            <td><?php echo ucwords($results->md_steward); ?></td>
                        </tr>
                          <!-- <tr>
                            <td class="text-center"><strong>Total Days Of Patient Stay</strong></td>
                            <td>
                            <?php echo $results->total_days_of_patient_stay; ?></td>
                        </tr> -->
                          <tr>
                            <td class="text-center"><strong>Date of start abx</strong></td>
                            <td><?php echo date('m/d/Y',strtotime($results->date_of_start_abx)); ?></td>
                        </tr>
   

                    </tbody>
                </table>
                <!-- END Customer Info -->
            </div>
        </div>
        <div class="col-md-6" >
            <div class="block">
                <!-- Customer Info Title -->
                <div class="block-title">
                    <h2><i class="fa fa-file-o"></i> <strong>Initial</strong> Info</h2>
                </div>
                <!-- END Customer Info Title -->
                <table class="table table-borderless table-striped table-vcenter">
                    <tbody>
                        <tr>
                            <td class="text-center"><strong>Antibiotic Name</strong></td>
                            <td ><?php echo $results->initial_rx_name; ?></td>
                        </tr>
                        <tr>
                            <td class="text-center"><strong>Diagnosis</strong></td>
                            <td><?php echo $results->initial_dx_name; ?></td>
                        </tr>
                        <tr>
                            <td class="text-center"><strong>Days of Therapy</strong></td>
                            <td><?php echo $results->initial_dot; ?></td>
                        </tr>
                        <tr>
                            <td class="text-center"><strong>ABX Checklist</strong></td>
                            <td><?php echo $results->infection_surveillance_checklist; ?></td>
                        </tr>
                        <tr>
                            <td class="text-center"><strong>Criteria Met</strong></td>
                            <td><?php echo $results->criteria_met; ?></td>
                        </tr>
                        <tr>
                            <td class="text-center"><strong>Culture Source</strong></td>
                            <td><?php echo $results->culture_source_name; ?></td>
                        </tr>
                        <tr>
                            <td class="text-center"><strong>Organism</strong></td>
                            <td><?php echo $results->organism_name; ?></td>
                        </tr>
                        <tr>
                            <td class="text-center"><strong>Precautions</strong></td>
                            <td><?php echo $results->precautions_name; ?></td>
                        </tr>
                    </tbody>
                </table>
                <!-- END Customer Info -->
            </div>
        </div>
        <div class="col-md-6" >
            <div class="block">
                <!-- Customer Info Title -->
                <div class="block-title">
                    <h2><i class="fa fa-file-o"></i> <strong>MD Steward</strong> Recommendation</h2>
                </div>
                <!-- END Customer Info Title -->
                <table class="table table-borderless table-striped table-vcenter">
                    <tbody>
                       
                          <tr>
                            <td class="text-center"><strong>MD Steward Response</strong></td>
                            <td><?php echo $results->md_stayward_response; ?></td>
                        </tr>
                      
                        <tr>
                            <td class="text-center"><strong>NEW Antibiotic Name</strong></td>
                            <td><?php echo $results->new_initial_rx_name; ?></td>
                        </tr>

                        <tr>
                            <td class="text-center"><strong>PSA(Provider Steward Agreement)</strong></td>
                            <td><?php echo $results->psa; ?></td>
                        </tr>

                        <tr>
                            <td class="text-center"><strong>New Diagnosis</strong></td>
                            <td><?php echo $results->new_initial_dx_name; ?></td>
                        </tr>
                        <tr>
                            <td class="text-center"><strong>New Days of Therapy</strong></td>
                            <td><?php echo $results->new_initial_dot; ?></td>
                        </tr>
                       <!--  <tr>
                            <td class="text-center"><strong>Comment</strong></td>
                            <td><?php //echo $results->comment; ?></td>
                        </tr> -->
                        <!-- <tr>
                            <td class="text-center"><strong>PCT</strong></td>
                            <td><?php echo $results->pct; ?></td>
                        </tr> -->
                        <tr>
                            <td class="text-center"><strong>Additional Comment</strong></td>
                            <td><?php echo str_replace( array('[','"',']') , ''  , $results->additional_comment_option )
                            ; ?></td>
                        </tr>
                        
                    </tbody>
                </table>
                <!-- END Customer Info -->
            </div>
        </div>
    </div>
    <!-- Datatables Content -->
    <!-- END Datatables Content -->
</div>
<!-- </div> -->

<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h3 class="modal-title" style="text-align:center;" id="exampleModalLabel">Mail Complete Information of Patient </h3>
        <button type="button" class="close" style="margin-top:-40px;" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body1">
        <form  method="post" id="contact-form" data-toggle="validator" role="form" action="" enctype="multipart/form-data">
       

         </br>
          <div class="form-group">
            <label for="recipient-name" class="col-form-label">Mail Id:</label>
            <input id="to_email" type="email" name="to_email" multiple required="required" data-error="Valid email is required." placeholder="To Email Address" class="form-control" >
            <div class="help-block with-errors"></div>

           <div class="modal_popup">
           <input  name="id"  id='name' value="<?php echo $results->patient_id; ?>" /> 
           <?php if(empty($results->room_number)){ ?>
           <input  name="room_number"  id='room_number' value="NULL" /> 
           <?php   } else{ ?>
           <input  name="room_number"  id='room_number' value="<?php echo $results->room_number; ?>" /> 
            <?php } ?>
           <input  name="care_unit_name"  id='care_unit_name' value="<?php echo ucwords($results->care_unit_name); ?>" /> 
           <input  name="doctor_name"  id='doctor_name' value="<?php echo ucwords($results->doctor_name); ?>" /> 
           <input  name="symptom_onset"  id='symptom_onset' value="<?php echo $results->symptom_onset; ?>" /> 
           <input  name="culture_source_name"  id='culture_source_name' value="<?php echo $results->culture_source_name; ?>" /> 
           <input  name="organism_name"  id='organism_name' value="<?php echo $results->organism_name; ?>" /> 
           <input  name="precautions_name"  id='precautions_name' value="<?php echo $results->precautions_name; ?>" /> 
           <input  name="md_steward"  id='md_steward' value="<?php echo ucwords($results->md_steward); ?>" /> 
           <input  name="date_of_start_abx"  id='date_of_start_abx' value="<?php echo date('m/d/Y',strtotime($results->date_of_start_abx)); ?>" /> 
           <input  name="initial_rx_name"  id='initial_rx_name' value="<?php echo $results->initial_rx_name; ?>" /> 
           <input  name="initial_dx_name"  id='initial_dx_name' value="<?php echo $results->initial_dx_name; ?>" /> 
           <input  name="initial_dot"  id='initial_dot' value="<?php echo $results->initial_dot; ?>" /> 
           <input  name="infection_surveillance_checklist"  id='infection_surveillance_checklist' value="<?php echo $results->infection_surveillance_checklist; ?>" /> 
           <input  name="criteria_met"  id='criteria_met' value="<?php echo $results->criteria_met; ?>" /> 
           <input  name="md_stayward_response"  id='md_stayward_response' value="<?php echo $results->md_stayward_response; ?>" /> 
           <input  name="new_initial_rx_name"  id='new_initial_rx_name' value="<?php echo $results->new_initial_rx_name; ?>" /> 
           <input  name="psa"  id='psa' value="<?php echo $results->psa; ?>" /> 
           <input  name="new_initial_dx_name"  id='new_initial_dx_name' value="<?php echo $results->new_initial_dx_name; ?>" /> 
           <input  name="new_initial_dot"  id='new_initial_dot' value="<?php echo $results->new_initial_dot; ?>" /> 
          <!--  <input  name="pct"  id='pct' value="<?php echo $results->pct; ?>" />  -->
           <input  name="additional_comment_option"  id='additional_comment_option' value="<?php echo str_replace( array('[','"',']') , ''  , $results->additional_comment_option); ?>" /> 
           

        </div>
          </div>
          <br>
          <div class="modal-footer mailmodel">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="submit" name="submit"  id="submit1"   class="btn btn-primary">Send Mail</button>
      </div>

        </form>
      </div>
     
    </div>
  </div>
</div>
<!-- END Page Content -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/1.2.61/jspdf.min.js"></script>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/1000hz-bootstrap-validator/0.11.9/validator.min.js"></script>


<script type="text/javascript">
    $('#date_of_birth').datepicker({
        startView: 2,
        todayBtn: "linked",
        keyboardNavigation: false,
        forceParse: false,
        calendarWeeks: true,
        autoclose: true,
        endDate: 'today'
    });
    /*    $("#zipcode").select2({
     allowClear: true
     });*/
</script>

<script type="text/javascript">
$("#contact-form").submit(function(e) {
  $.ajax({
         type: "POST",
         url: '',
         data: $("#submit1").serialize(), // serializes the form's elements.
         success: function(data)
         {
             $("#contact-form").html("<h2 style='color:#2E8B57;text-align:center;'><b>Mail Sent Successfully....!!!</b></h2>");
         }
       });
  e.preventDefault(); // avoid to execute the actual submit of the form.
});
</script>