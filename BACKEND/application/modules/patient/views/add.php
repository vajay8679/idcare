<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<style>
    .modal-footer .btn+.btn {
        margin-bottom: 5px !important;
        margin-left: 5px;
    }
    span.select2.select2-container.select2-container--default{
        width:100%!important;
    }  
    span.select2-selection.select2-selection--multiple{
        min-height: auto!important;
        overflow: auto!important;
        border: solid #ddd0d0 1px;
        color: black;
    }
    .select2-container--default .select2-selection--multiple .select2-selection__choice {
    background-color: #d9416c;
    }
</style>

<div id="commonModal" class="modal fade" role="dialog">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form class="form-horizontal" role="form" id="addFormAjax" method="post" action="<?php echo $formUrl; ?>" enctype="multipart/form-data">
                <div class="modal-header text-center">
                    <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                    <h2 class="modal-title"><i class="fa fa-pencil"></i> <?php echo (isset($title)) ? ucwords($title) : "" ?></h2>
                </div>
                <div class="modal-body">
                    <!--                     <div class="loaders">
                                            <img src="<?php //echo base_url().'backend_asset/images/Preloader_2.gif';       
                                                        ?>" class="loaders-img" class="img-responsive">
                                        </div> -->
                    <div class="alert alert-danger" id="error-box" style="display: none"></div>
                    <div class="form-body">
                        <div class="row">
                           <!--  <div class="col-md-6">
                                <div class="form-group">
                                    <label class="col-md-3 control-label">Patient type</label>
                                    <div class="col-md-9">
                                        <select id="patient_mode" name="patient_mode" class="form-control select-chosen" size="1">
                                            <option value="">Please select</option>
                                            <option value="New">New</option>
                                            <option value="Existing">Existing</option>
                                        </select>
                                    </div>
                                </div>
                            </div> -->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="col-md-3 control-label">Care Unit</label>
                                    <div class="col-md-9">
                                        <select id="care_unit" name="care_unit_id" class="form-control select-chosen" size="1" onchange='getPatientId(this.value)'>
                                            <option value="">Please select</option>
                                            <?php 
                                            if(!empty($careUnitsUser)){

                                    
                                                if (!empty($careUnitsUser)) {
                                                    foreach ($careUnitsUser as $row) {
                                                        
                                                        //print_r($row);die;
                                                        $select = "";
                                                        if (isset($careUnitID)) {
                                                            if ($careUnitID == $row->id) {
                                                                $select = "selected";
                                                            }
                                                        }
                                                        ?>
                                                        <option value="<?php echo $row->id; ?>" <?php echo $select; ?>><?php echo $row->name; ?></option>
                                                        <?php
                                                    }
                                                }
                                            }else{

                                           
                                            
                                            
                                            foreach ($care_unit as $category) { ?>

                                                <option value="<?php echo $category->id; ?>"><?php echo $category->name; ?></option>
                                            <?php }  } ?>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="col-md-3 control-label">Patient Id</label>
                                    <!-- <div class="col-md-9" id='patient_id_dropbox'> -->
                                    <div class="col-md-9">
                                        <input type="text" class="form-control" name="patient_id" id="name" placeholder="Patient Id" maxlength="9" />
                                    </div>
                                </div>
                            </div>
                            <!--                             <div class="col-md-6" >
                                <div class="form-group">
                                    <label class="col-md-3 control-label">Patient</label>
                                    <div class="col-md-9">
                                        <input type="text" class="form-control" name="name" id="name" placeholder="Patient Name" />
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6" >
                                <div class="form-group">
                                    <label class="col-md-3 control-label">Address</label>
                                    <div class="col-md-9">
                                        <input type="text" class="form-control" name="address" id="address" placeholder="Address" />
                                    </div>
                                </div>
                            </div> -->



                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="col-md-3 control-label">Provider MD</label>
                                    <div class="col-md-9">
                                        <select id="doctor_id" name="doctor_id" class="form-control select-chosen" size="1">
                                            <option value="">Please select</option>
                                            <?php foreach ($doctors as $category) { ?>
                                                <option value="<?php echo $category->id; ?>"><?php echo $category->name; ?></option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="col-md-3 control-label">Infection Onset</label>
                                    <div class="col-md-9">
                                        <select id="symptom_onset" name="symptom_onset" class="form-control select-chosen" size="1">
                                            <option value="">Please select</option>
                                            <option value="Hospital">Hospital/CAI</option>
                                            <option value="Facility">Facility/HAI</option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                           <!--  <div class="col-md-6">
                                <div class="form-group">
                                    <label class="col-md-3 control-label">MD Steward Consult</label>
                                    <div class="col-md-9">
                                        <select id="md_stayward_consult" name="md_stayward_consult" class="form-control select-chosen" size="1">
                                            <option value="">Please select</option>
                                            <option value="Yes">Yes</option>
                                            <option value="No">No</option>
                                        </select>
                                    </div>
                                </div>
                            </div> -->
                            <!-- <div class="col-md-6" >
                                <div class="form-group">
                                    <label class="col-md-3 control-label">Total Days of Patient Stay</label>
                                    <div class="col-md-9">
                                        <input type="text" class="form-control" name="total_days_of_patient_stay" id="total_days_of_patient_stay" placeholder="0"/>
                                    </div>
                                </div>
                            </div> -->

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="col-md-3 control-label">Date of start abx</label>
                                    <div class="col-md-9">
                                        <input type="text" class="form-control" name="date_of_start_abx" id="date_of_start_abx" />
                                    </div>
                                </div>
                            </div>
                            
                            <?php if ($this->ion_auth->is_admin()) { ?>
<div class="col-md-6" >
                        <div class="form-group">
                            <label class="col-md-3 control-label">Select MD Steward</label>
                            <div class="col-md-9">                                
                                    <select id="md_steward_id" name="md_steward_id"  class="form-control select-chosen"  size="1">
                                        <option value="">Select MD Steward</option>
                                        <?php foreach($stawardss as $row){?>
                                                    
                                        <option value="<?php echo $row->id; ?>"><?php echo $row->first_name . ' ' . $row->last_name; ?></option>
                                                
                                        <?php }?>
                                    </select>
                            </div>
                        </div>
                    </div>


     <?php }else if($this->ion_auth->is_facilityManager()) {?>

         <div class="col-md-6">
                                <div class="form-group">
                                    <label class="col-md-3 control-label">MD Steward</label>
                                    <div class="col-md-9">
                                        <select id="md_steward_id" name="md_steward_id" class="form-control select-chosen">
                                           <option value="">Select MD Steward</option>
                                    <?php
                                    if (!empty($staward)) {
                                        
                                               $care=json_decode($staward[0]->md_steward_id);
                                              
                                            foreach( $care as $car)
                                              {

                                                $this->db->select('*');
                                                $this->db->from('vendor_sale_users');
                                                $this->db->where('id', $car);
                                                $query = $this->db->get();
                                                $row = $query->row();           

                                            ?>
                                            <option value="<?php echo $row->id; ?>"><?php echo $row->first_name . ' ' . $row->last_name; ?></option>

                                             <?php  } ?>
                                            <?php
                                        
                                    }
                                    ?>
                                        </select>
                                    </div>
                                </div>
                            </div>
          <?php } ?>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="col-md-3 control-label">Room Number</label>
                                    <div class="col-md-9">
                                        <input type="text" class="form-control" name="room_number" id="room_number" placeholder="0000" maxlength="4" />
                                        <p><b>Note :</b> Room Number can be 3 digit or 4 digit <br> number,if you dont know then write '<b>NA</b>'.</p>
                                    </div>
                                    
                                </div>
                            </div>

                            
                            <div class="modal-header text-center"></div>
                            <div class="col-md-12">
                                <div class="vender_title_admin">
                                    <h3>Initial </h3>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="col-md-3 control-label">Diagnosis</label>
                                    <div class="col-md-9">
                                        <select id="initial_dx" name="initial_dx" class="form-control select-chosen" size="1">
                                            <option value="">Please select</option>
                                            <?php foreach ($initial_dx as $category) { ?>
                                                <option value="<?php echo $category->id; ?>"><?php echo $category->name; ?></option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="col-md-3 control-label">Antibiotic Name</label>
                                    <div class="col-md-9">
                                        <select id="initial_rx" name="initial_rx" class="form-control select-chosen" size="1">
                                            <option value="">Please select</option>
                                            <?php foreach ($initial_rx as $category) { ?>
                                                <option value="<?php echo $category->id; ?>"><?php echo $category->name; ?></option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                </div>
                            </div>

                          
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="col-md-3 control-label">Days of Therapy</label>
                                    <div class="col-md-9">
                                        <input type="number" class="form-control" name="initial_dot" onkeyup="myFunction()" id="initial_dot" placeholder="0" />
                                        <b style="color:red"><span id="test"></span></b>
                                        <script>
                                            function myFunction() {
                                                var x = document.getElementById("initial_dot").value;
                                                if (x > 50) {
                                                    document.getElementById("test").innerHTML = " You are entering a high days on therapy, please confirm that this is correct.";
                                                } else {
                                                    document.getElementById("test").innerHTML = "";
                                                }
                                            }
                                        </script>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="col-md-3 control-label">ABX Checklist</label>
                                    <div class="col-md-9">
                                        <select id="infection_surveillance_checklist" name="infection_surveillance_checklist" class="form-control select-chosen" onchange="showDiv(this)" size="1">
                                            <option value="N/A">N/A</option>
                                            <option value="Loeb">Loeb</option>
                                            <option value="McGeer – UTI">McGeer – UTI</option>
                                            <option value="McGeer – RTI">McGeer – RTI</option>
                                            <option value="McGeer – GITI">McGeer – GITI</option>
                                            <option value="McGeer –SSTI">McGeer –SSTI
                                            </option>
                                            <option  value="Nhsn -UTI">NHSN -UTI
                                            </option>
                                            <option value="Nhsn -CDI/MDRO">NHSN -CDI/MDRO
                                            </option>

                                        </select>
                                        <br/>
                                        <div id="hidden_div" style="display: none;">
                                        <div style="text-align: right;">
                                         <button class="btn btn-sm btn-primary" onclick="myFun()">Print ABX Checklist form</button>
                                         </div>
                                        <label> Criteria Met</label>
                                        <input type="radio" id="criteria_met" name="criteria_met" value="Yes">
                                        <label for="criteria_met">YES</label>
                                        <input type="radio" id="criteria_met" name="criteria_met" value="No">
                                        <label for="criteria_met">NO</label>
                                        
                                        </div>

                                    </div>
                                </div>
                                
                            </div>

                           <!--  <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="col-md-3 control-label">Culture Source</label>
                                        <div class="col-md-9">
                                            <select id="culture_source" name="culture_source" class="form-control select-chosen" size="1">
                                                    <option value="">Please select</option> 
                                                    <option value="NA">NA</option>
                                                    <option value="Blood">Blood</option>
                                                    <option value="Nares">Nares</option>
                                                    <option value="Sputum">Sputum</option>
                                                    <option value="Stool">Stool</option>
                                                    <option value="Urine">Urine</option>
                                                    <option value="Wound">Wound</option>
                                            </select>
                                        </div>
                                    </div>
                                </div> -->

                                <div class="col-md-6">
                                <div class="form-group">
                                    <label class="col-md-3 control-label">Culture Source</label>
                                    <div class="col-md-9">
                                        <select id="culture_source" name="culture_source" class="form-control select-chosen" size="1">
                                            <option value="">Please select</option>
                                            <?php foreach ($culture_source as $category) { ?>
                                                <option value="<?php echo $category->name; ?>"><?php echo $category->name; ?></option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                </div>
                            </div>

                                <!-- <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="col-md-3 control-label">Organism</label>
                                        <div class="col-md-9">
                                            <select id="organism" name="organism" class="form-control select-chosen" size="1">
                                                    <option value="">Please select</option> 
                                                    <option value="NA">NA</option>
                                                    <option value="Candida">Candida</option>
                                                    <option value="C. auris">C. auris</option>
                                                    <option value="Citrobacter">Citrobacter</option>
                                                    <option value="Cdiff">Cdiff</option>
                                                    <option value="Coag Neg Staph">Coag Neg Staph</option>
                                                    <option value="COVID-19">COVID-19</option>
                                                    <option value="Enterobacter">Enterobacter</option>
                                                    <option value="Enterococcus">Enterococcus</option>
                                                    <option value="Ecoli">Ecoli</option>
                                                    <option value="ESBL ecoli">ESBL ecoli</option>
                                                    <option value="ESBL klebsiella">ESBL klebsiella</option>
                                                    <option value="Klebsiella">Klebsiella</option>
                                                    <option value="MDRO">MDRO</option>
                                                    <option value="MRSA">MRSA</option>
                                                    <option value="MSSA">MSSA</option>
                                                    <option value="Proteus">Proteus</option>
                                                    <option value="Pseudomonas">Pseudomonas</option>
                                                    <option value="Streptococcus">Streptococcus</option>
                                                    <option value="VRE">VRE</option>
                                                    <option value="Other">Other</option> 
                                            </select>
                                          
                                        </div>
                                    </div>
                                </div> -->

                                <div class="col-md-6">
                                <div class="form-group">
                                    <label class="col-md-3 control-label">Organism</label>
                                    <div class="col-md-9">
                                        <select id="organism" name="organism" class="form-control select-chosen" size="1">
                                            <option value="">Please select</option>
                                            <?php foreach ($organism as $category) { ?>
                                                <option value="<?php echo $category->name; ?>"><?php echo $category->name; ?></option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                </div>
                                </div>

                                <div class="col-md-6">
                                <div class="form-group">
                                    <label class="col-md-3 control-label">Precautions</label>
                                    <div class="col-md-9">
                                        <select id="precautions" name="precautions" class="form-control select-chosen" size="1">
                                            <option value="">Please select</option>
                                            <?php foreach ($precautions as $category) { ?>
                                                <option value="<?php echo $category->name; ?>"><?php echo $category->name; ?></option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                </div>
                                </div>

                                
                            <iframe src='' id='myFrame' hidden>
                            </iframe>
                            <!-- <iframe src='http://localhost/IDCARE/aj.pdf' id='myFrame' frameborder='0' style='border:0;' width='300' height='300' hidden>
                            </iframe> -->
                            <div class="modal-header text-center"></div>
                            <!--   <div class="col-md-12">
                                <div class="vender_title_admin">
                                    <h3>MD Steward Recommendation</h3>
                                </div>
                            </div> -->
                            <div class="col-md-12">
                                <div class="vender_title_admin">
                                    <h3>
                                        <button type="button" onclick="myFunction4()" class="btn btn-primary" data-toggle="collapse" data-target="#demo1">MD Steward Recommendation <i class="gi gi-circle_plus"></i></button>
                                    </h3>
                                </div>
                            </div>
                            <div id="demo1" class="collapse">
                                <!-- <div class="col-md-6" >
                                <div class="form-group">
                                    <label class="col-md-3 control-label">Infection Surveillance Checklist</label>
                                    <div class="col-md-9">
                                        <select id="infection_surveillance_checklist" name="infection_surveillance_checklist" class="form-control select-chosen" size="1">
                                            <option value="N/A" >N/A</option>
                                            <option value="Yes" >Yes</option>
                                            <option value="Not Present" >Not Present</option>
                                        </select>
                                    </div>
                                </div>
                            </div> -->
                                <!--  <input type="text" name="to" size="40" value="vajay8679@gmail.com" hidden> -->
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="col-md-3 control-label">MD Steward Response</label>
                                        <div class="col-md-9">
                                            <select id="md_stayward_response" name="md_stayward_response" class="form-control select-chosen" onchange="isDirty(this.value)" size="1">
                                                <option value="">Please select</option>
                                                <option value="Agree">Agree</option>
                                                <option value="Disagree">Disagree</option>
                                                <option value="NoResponse">Neutral</option>
                                                <option value="Modify">Modify</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="col-md-3 control-label">New Diagnosis</label>
                                        <div class="col-md-9">
                                            <select id="new_initial_dx" name="new_initial_dx" onchange="isDirty(this.value)" class="form-control select-chosen" size="1">
                                                <option value="">Please select</option>
                                                <?php foreach ($initial_dx as $category) { ?>
                                                    <option value="<?php echo $category->id; ?>"><?php echo $category->name; ?></option>
                                                <?php } ?>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="col-md-3 control-label">PSA(Provider Steward Agreement)</label>
                                        <div class="col-md-9">
                                            <select id="psa" name="psa" class="form-control select-chosen" onchange="isDirty(this.value)" size="1">
                                                <option value="">Please select</option>
                                                <option value="Agree">Agree</option>
                                                <option value="Disagree">Disagree</option>
                                                <option value="NoResponse">No Response</option>
                                                <option value="Neutral">Neutral</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="col-md-3 control-label">New Antibiotic Name</label>
                                        <div class="col-md-9">
                                            <select id="new_initial_rx" name="new_initial_rx" onchange="isDirty(this.value)" class="form-control select-chosen" size="1">
                                                <option value="">Please select</option>
                                                <?php foreach ($initial_rx as $category) { ?>
                                                    <option value="<?php echo $category->id; ?>"><?php echo $category->name; ?></option>
                                                <?php } ?>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="col-md-3 control-label">New Days of Therapy</label>
                                        <div class="col-md-9">
                                            <input type="number" onchange="isDirty(this.value)" onkeyup="myFunction1()" class="form-control" name="new_initial_dot" id="new_initial_dot" placeholder="0" />
                                            <b style="color:red"><span id="test1"></span></b>
                                            <script>
                                                function myFunction1() {
                                                    var x = document.getElementById("new_initial_dot").value;
                                                    if (x > 50) {
                                                        document.getElementById("test1").innerHTML = " You are entering a high days on therapy, please confirm that this is correct.";
                                                    } else {
                                                        document.getElementById("test1").innerHTML = "";
                                                    }
                                                }
                                            </script>
                                        </div>
                                    </div>
                                </div>
                                <!-- <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="col-md-3 control-label">PCT</label>
                                        <div class="col-md-9">
                                            <input type="text" onchange="isDirty(this.value)" class="form-control" name="pct" id="pct" placeholder="" />
                                        </div>
                                    </div>
                                </div> -->
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="col-md-3 control-label">Additional Comment</label>
                                        <div class="col-md-9">
                                            <select id="additional_comment_option" name="additional_comment_option[]" onchange="isDirty(this.value)" class="form-control multiple-select select-chosen"  size="1" multiple="multiple">
                                                    <option value="" disabled>Please select</option> 
                                                    <option value="Does not meet Loeb/ McGeer Criteria ">Does not meet Loeb/ McGeer Criteria</option>
                                                    <option value="Consider shorter antibiotic course ">Consider shorter antibiotic course</option>
                                                    <option value="Antibiotics not recommended ">Antibiotics not recommended</option>
                                                    <option value="Other/Free Text">Other/Free Text</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="col-md-3 control-label">Additional Comment</label>
                                        <div class="col-md-9">
                                            <input type="text" onchange="isDirty(this.value)" class="form-control" name="additional_comment_option[]" id="additional_comment_option" placeholder="Add your comment" />
                                        </div>
                                    </div>
                                </div>
                                <div class="space-22"></div>
                            </div>
                        </div>
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-sm btn-default" data-dismiss="modal"><?php echo lang('reset_btn'); ?></button>
                    <button type="submit" id="submit" class="btn btn-sm btn-primary"><?php echo lang('submit_btn'); ?></button>
                </div>
            </form>
        </div> <!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div>
<script>
    $('#date_of_start_abx').datepicker({
        todayBtn: "linked",
        format: "mm/dd/yyyy",
        keyboardNavigation: false,
        forceParse: false,
        calendarWeeks: true,
        autoclose: true
    });
</script>


<script>
    function myFunction4() {
        var txt;
        if (confirm("You are about to ADD the MD Steward recommendations, please confirm or cancel.")) {
            //txt = "You pressed OK!";
            document.getElementById("demo1").style = 'display:block';

        } else {
            //txt = "You pressed Cancel!";
            document.getElementById("demo1").style = 'display:none';
        }
    }


    function showDiv(select) {
        if (select.value == "Loeb" || select.value == "Nhsn -UTI" ||select.value == "Nhsn -CDI/MDRO" || select.value == "McGeer – UTI" || select.value == "McGeer – RTI" || select.value == "McGeer – GITI" || select.value == "McGeer –SSTI") {
            document.getElementById('hidden_div').style.display = "block";
        } else {
            document.getElementById('hidden_div').style.display = "none";
        }
    }



function myFun() {
    event.preventDefault();
    if ($("#infection_surveillance_checklist").val() != "N/A" && $("#infection_surveillance_checklist").val() == "Loeb") {
          alert("Printable ABX Checklist form will appear after clicking OK button. Please complete and submit the form.");
            window.open("<?php echo base_url(); ?>application/modules/patient/views/form1.html","_blank")
      }
      
      if ($("#infection_surveillance_checklist").val() != "N/A" && $("#infection_surveillance_checklist").val() == "McGeer – UTI") {
          alert("Printable ABX Checklist form will appear after clicking OK button. Please complete and submit the form.");
            window.open("<?php echo base_url(); ?>application/modules/patient/views/form2.html","_blank")
      }
      if ($("#infection_surveillance_checklist").val() != "N/A" && $("#infection_surveillance_checklist").val() == "McGeer – RTI") {
          alert("Printable ABX Checklist form will appear after clicking OK button. Please complete and submit the form.");
            window.open("<?php echo base_url(); ?>application/modules/patient/views/form3.html","_blank")
      }
      if ($("#infection_surveillance_checklist").val() != "N/A" && $("#infection_surveillance_checklist").val() == "McGeer – GITI") {
          alert("Printable ABX Checklist form will appear after clicking OK button. Please complete and submit the form.");
            window.open("<?php echo base_url(); ?>application/modules/patient/views/form4.html","_blank")
      }
      if ($("#infection_surveillance_checklist").val() != "N/A" && $("#infection_surveillance_checklist").val() == "McGeer –SSTI") {
          alert("Printable ABX Checklist form will appear after clicking OK button. Please complete and submit the form.");
            window.open("<?php echo base_url(); ?>application/modules/patient/views/form5.html","_blank")
      }
       if ($("#infection_surveillance_checklist").val() != "N/A" && $("#infection_surveillance_checklist").val() == "Nhsn -UTI") {
          alert("Printable ABX Checklist form will appear after clicking OK button. Please complete and submit the form.");
            window.open("<?php echo base_url(); ?>front_assets/images/57.114_uti_blank.pdf")
      }
       if ($("#infection_surveillance_checklist").val() != "N/A" && $("#infection_surveillance_checklist").val() == "Nhsn -CDI/MDRO") {
          alert("Printable ABX Checklist form will appear after clicking OK button. Please complete and submit the form.");
            window.open("<?php echo base_url(); ?>front_assets/images/57.128_LabIDEvent_BLANK")
      }
 
}

$('input[type=radio][name="criteria_met"]').prop('checked', false);

</script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>

$(".multiple-select").select2({
 // maximumSelectionLength: 2
 placeholder: "Please select",
});

</script>