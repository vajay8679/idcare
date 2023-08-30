
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<style>
    .modal-footer .btn + .btn {
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
            <form class="form-horizontal" role="form" id="addFormAjax" method="post" action="<?php echo base_url($formUrl) ?>" enctype="multipart/form-data">
                <div class="modal-header text-center">
                    <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                    <h2 class="modal-title"><i class="fa fa-pencil"></i> <?php echo (isset($title)) ? ucwords($title) : "" ?></h2>
                </div>
                <div class="modal-body">
                    <!--                     <div class="loaders">
                                            <img src="<?php //echo base_url().'backend_asset/images/Preloader_2.gif';        ?>" class="loaders-img" class="img-responsive">
                                        </div> -->
                    <div class="alert alert-danger" id="error-box" style="display: none"></div>
                    <div class="form-body">
                        <div class="row">
<!--                             <div class="col-md-6" >
                                <div class="form-group">
                                    <label class="col-md-3 control-label">Patient</label>
                                    <div class="col-md-9">
                                        <input type="text" class="form-control" name="name" id="name" placeholder="Patient Name" value="<?php echo $results->patient_name; ?>"/>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6" >
                                <div class="form-group">
                                    <label class="col-md-3 control-label">Address</label>
                                    <div class="col-md-9">
                                        <input type="text" class="form-control" name="address" id="address" placeholder="Address" value="<?php echo $results->address; ?>"/>
                                    </div>
                                </div>
                            </div> -->


                            <div class="col-md-6" >
                                <div class="form-group">
                                    <label class="col-md-3 control-label">Care Unit</label>
                                    <div class="col-md-9">
                                        <select id="care_unit" name="care_unit_id" class="form-control select-chosen" size="1">
                                            <option value="">Please select</option>
                                            <?php 
            
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
                                                        <option value="<?php echo $row->id; ?>" <?php echo ($results->care_unit_id == $row->id) ? "selected" : ""; ?>><?php echo $row->name; ?></option>
                                                        <?php
                                                    }
                                               
                                            }else{

                                               foreach ($care_unit as $category) { ?>

                                               <option value="<?php echo $category->id; ?>" <?php echo ($results->care_unit_id == $category->id) ? "selected" : ""; ?>><?php echo $category->name; ?></option>

                                            <?php }  } ?>

                                            <!-- <?php foreach ($care_unit as $category) { ?>
                                                <option value="<?php echo $category->id; ?>" <?php echo ($results->care_unit_id == $category->id) ? "selected" : ""; ?>><?php echo $category->name; ?></option>
                                            <?php } ?> -->
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6" >
                                <div class="form-group">
                                    <label class="col-md-3 control-label">Provider MD</label>
                                    <div class="col-md-9">
                                        <select id="doctor_id" name="doctor_id" class="form-control select-chosen" size="1">
                                            <option value="">Please select</option>
                                            <?php foreach ($doctors as $category) { ?>
                                                <option value="<?php echo $category->id; ?>" <?php echo ($results->doctor_id == $category->id) ? "selected" : ""; ?>><?php echo $category->name; ?></option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6" >
                                <div class="form-group">
                                    <label class="col-md-3 control-label">Infection Onset</label>
                                    <div class="col-md-9">
                                        <select id="symptom_onset" name="symptom_onset" class="form-control select-chosen" size="1">
                                            <option value="" >Please select</option>
                                            <option value="Hospital" <?php echo ($results->symptom_onset == "Hospital") ? "selected" : ""; ?>>Hospital/CAI</option>
                                            <option value="Facility" <?php echo ($results->symptom_onset == "Facility") ? "selected" : ""; ?>>Facility/HAI</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                           <!--  <div class="col-md-6" >
                                <div class="form-group">
                                    <label class="col-md-3 control-label">MD Steward Consult</label>
                                    <div class="col-md-9">
                                        <select id="md_stayward_consult" name="md_stayward_consult" class="form-control select-chosen" size="1">
                                            <option value="" >Please select</option>
                                            <option value="Yes" <?php //echo ($results->md_stayward_consult == "Yes") ? "selected" : ""; ?>>Yes</option>
                                            <option value="No" <?php //echo ($results->md_stayward_consult == "No") ? "selected" : ""; ?>>No</option>
                                        </select>
                                    </div>
                                </div>
                            </div> -->
                            <!-- <div class="col-md-6" >
                                <div class="form-group">
                                    <label class="col-md-3 control-label">Total Days of Patient Stay</label>
                                    <div class="col-md-9">
                                        <input type="text" class="form-control" name="total_days_of_patient_stay" id="total_days_of_patient_stay" placeholder="" value="<?php echo $results->total_days_of_patient_stay; ?>"/>
                                    </div>
                                </div>
                            </div> -->

                             <div class="col-md-6" >
                                <div class="form-group">
                                    <label class="col-md-3 control-label">Date of start abx</label>
                                    <div class="col-md-9">
                                        <input type="text" class="form-control" name="date_of_start_abx" id="date_of_start_abx" value="<?php echo date('m/d/Y',strtotime($results->date_of_start_abx)); ?>"/>
                                    </div>
                                </div>
                            </div>

                            <?php if ($this->ion_auth->is_admin()) { ?>
                            <div class="col-md-6" >
                                <div class="form-group">
                                    <label class="col-md-3 control-label">MD Steward</label>
                                    <div class="col-md-9">
                                        <select id="md_steward_id" name="md_steward_id" class="form-control select-chosen">
                                            <option value="">Please select</option>
                                            <?php foreach ($md_steward as $category) { ?>
                                                <option value="<?php echo $category->id; ?>" <?php echo ($results->md_steward_id == $category->id) ? "selected" : ""; ?>><?php echo $category->name; ?></option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <?php }else if($this->ion_auth->is_facilityManager()) {?>
                            <div class="col-md-6" >
                                <div class="form-group">
                                    <label class="col-md-3 control-label">MD Steward</label>
                                    <div class="col-md-9">
                                        <select id="md_steward_id" name="md_steward_id" class="form-control select-chosen">
                                            <option value="">Please select</option>
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
                                            <option value="<?php echo $row->id; ?>"  <?php echo ($results->md_steward_id == $row->id) ? "selected" : ""; ?>><?php echo $row->first_name . ' ' . $row->last_name; ?></option>

                                             <?php  } ?>
                                            <?php
                                        
                                    }
                                    ?>
                                        </select>
                                    </div>
                                </div>
                            </div>
          <?php } ?>
                            <div class="col-md-6" >
                                <div class="form-group">
                                    <label class="col-md-3 control-label">Room Number</label>
                                    <div class="col-md-9">
                                        <?php if(empty($results->room_number)){ ?> 
                                            <input type="text" class="form-control" name="room_number" id="room_number" placeholder="0000" maxlength="4" value=""/>
                                        <p><b>Note :</b> Room Number can be 3 digit or 4 digit <br> number,if you dont know then write '<b>NA</b>'.</p>

                                      <?php   } else{ ?>
                                        <input type="text" class="form-control" name="room_number" id="room_number" placeholder="0000" maxlength="4" value="<?php echo $results->room_number; ?>"/>
                                        <p><b>Note :</b> Room Number can be 3 digit or 4 digit <br> number,if you dont know then write '<b>NA</b>'.</p>
                                        <?php } ?>
                                    </div>
                                </div>
                            </div>

                            <div class="modal-header text-center"></div>
                            <div class="col-md-12" >
                                <div class="vender_title_admin">
                                    <h3>Initial </h3>
                                </div>
                            </div> 
                            <div class="col-md-6" >
                                <div class="form-group">
                                    <label class="col-md-3 control-label">Diagnosis</label>
                                    <div class="col-md-9">
                                        <select id="initial_dx" name="initial_dx" class="form-control select-chosen" size="1">
                                            <option value="">Please select</option>
                                            <?php foreach ($initial_dx as $category) { ?>
                                                <option value="<?php echo $category->id; ?>" <?php echo ($results->initial_dx == $category->id) ? "selected" : ""; ?>><?php echo $category->name; ?></option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6" >
                                <div class="form-group">
                                    <label class="col-md-3 control-label">Antibiotic Name</label>
                                    <div class="col-md-9">
                                        <select id="initial_rx" name="initial_rx" class="form-control select-chosen" size="1">
                                            <option value="">Please select</option>
                                            <?php foreach ($initial_rx as $category) { ?>
                                                <option value="<?php echo $category->id; ?>"  <?php echo ($results->initial_rx == $category->id) ? "selected" : ""; ?>><?php echo $category->name; ?></option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6" >
                                <div class="form-group">
                                    <label class="col-md-3 control-label">Days of Therapy</label>
                                    <div class="col-md-9">
                                        <input type="number" onkeyup="myFunction2()"  class="form-control" name="initial_dot" id="initial_dot" placeholder="0" value="<?php echo $results->initial_dot; ?>"/>
                                        <b style="color:red"><span id="test2"></span></b>
                                        <script>
                                            function myFunction2() {
                                                var x = document.getElementById("initial_dot").value;
                                                if (x > 50) {
                                                    document.getElementById("test2").innerHTML = " You are entering a high days on therapy, please confirm that this is correct.";
                                                } else {
                                                    document.getElementById("test2").innerHTML = "";
                                                }
                                            }
                                        </script>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6" >
                                <div class="form-group">
                                    <label class="col-md-3 control-label">ABX Checklist</label>
                                    <div class="col-md-9">
                                        <select id="infection_surveillance_checklist" name="infection_surveillance_checklist" class="form-control select-chosen" onchange="showDiv(this)" size="1">
                                            <option value="N/A" <?php echo ($results->infection_surveillance_checklist == "N/A") ? "selected" : ""; ?>>N/A</option>
                                            <option  value="Loeb" <?php echo ($results->infection_surveillance_checklist == "Loeb") ? "selected" : ""; ?>>Loeb</option>
                                            <option value="McGeer – UTI" <?php echo ($results->infection_surveillance_checklist == "McGeer – UTI") ? "selected" : ""; ?>>McGeer – UTI</option>
                                            <option value="McGeer – RTI" <?php echo ($results->infection_surveillance_checklist == "McGeer – RTI") ? "selected" : ""; ?>>McGeer – RTI</option>
                                            <option value="McGeer – GITI" <?php echo ($results->infection_surveillance_checklist == "McGeer – GITI") ? "selected" : ""; ?>>McGeer – GITI</option>
                                            <option value="McGeer –SSTI" <?php echo ($results->infection_surveillance_checklist == "McGeer –SSTI") ? "selected" : ""; ?>>McGeer –SSTI</option>
                                            <option value="Nhsn -UTI" <?php echo ($results->infection_surveillance_checklist == "Nhsn -UTI") ? "selected" : ""; ?>>NHSN -UTI</option>
                                            <option value="Nhsn -CDI/MDRO" <?php echo ($results->infection_surveillance_checklist == "Nhsn -CDI/MDRO") ? "selected" : ""; ?>>NHSN -CDI/MDRO</option>
                                        </select>
                                        <br/>
                                        <div id="hidden_div" style="display: none;">
                                        <div style="text-align: right;">
                                        <button class="btn btn-sm btn-primary" onclick="myFun()">Print ABX Checklist form</button>
                                        </div>
                                        <label> Criteria Met</label>
                                        <input type="radio" id="criteria_met" name="criteria_met" value="Yes" <?php echo ($results->criteria_met == "Yes") ? "checked" : ""; ?>>
                                        <label for="criteria_met">YES</label>
                                        <input type="radio" id="criteria_met" name="criteria_met" value="No"  <?php echo ($results->criteria_met == "No") ? "checked" : ""; ?>>
                                        <label for="criteria_met">NO</label>
                                      
                                        </div>
                                         
                                    </div>
                                </div>

                               
                            </div>

                            <!-- <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="col-md-3 control-label">Culture Source</label>
                                        <div class="col-md-9">
                                            <select id="culture_source" name="culture_source" class="form-control select-chosen" size="1">
                                                    <option value="">Please select</option> 
                                                    <option value="NA" <?php echo ($results->culture_source == "NA") ? "selected" : ""; ?>>NA</option>
                                                    <option value="Blood" <?php echo ($results->culture_source == "Blood") ? "selected" : ""; ?>>Blood</option>
                                                    <option value="Nares" <?php echo ($results->culture_source == "Nares") ? "selected" : ""; ?>>Nares</option>
                                                    <option value="Sputum" <?php echo ($results->culture_source == "Sputum") ? "selected" : ""; ?>>Sputum</option>
                                                    <option value="Stool" <?php echo ($results->culture_source == "Stool") ? "selected" : ""; ?>>Stool</option>
                                                    <option value="Urine" <?php echo ($results->culture_source == "Urine") ? "selected" : ""; ?>>Urine</option>
                                                    <option value="Wound" <?php echo ($results->culture_source == "Wound") ? "selected" : ""; ?>>Wound</option>
                                            </select>
                                        </div>
                                    </div>
                                </div> -->

                                <div class="col-md-6" >
                                <div class="form-group">
                                    <label class="col-md-3 control-label">Culture Source</label>
                                    <div class="col-md-9">
                                        <select id="culture_source" name="culture_source" class="form-control select-chosen" size="1">
                                            <option value="">Please select</option>
                                            <?php foreach ($culture_source as $category) { ?>
                                                <option value="<?php echo $category->name; ?>" <?php echo ($results->culture_source == $category->name) ? "selected" : ""; ?>><?php echo $category->name; ?></option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                </div>
                            </div>    

                            <!-- <div class="col-md-6" >
                                <div class="form-group">
                                    <label class="col-md-3 control-label">Organism</label>
                                        <div class="col-md-9">
                                            <select id="organism" name="organism" class="form-control select-chosen" size="1">
                                                    <option value="">Please select</option> 
                                                    <option value="NA" <?php echo ($results->organism == "NA") ? "selected" : ""; ?>>NA</option>
                                                    <option value="Candida" <?php echo ($results->organism == "Candida") ? "selected" : ""; ?>>Candida</option>
                                                    <option value="C. auris" <?php echo ($results->organism == "C. auris") ? "selected" : ""; ?>>C. auris</option>
                                                    <option value="Citrobacter" <?php echo ($results->organism == "Citrobacter") ? "selected" : ""; ?>>Citrobacter</option>
                                                    <option value="Cdiff" <?php echo ($results->organism == "Cdiff") ? "selected" : ""; ?>>Cdiff</option>
                                                    <option value="Coag Neg Staph" <?php echo ($results->organism == "Coag Neg Staph") ? "selected" : ""; ?>>Coag Neg Staph</option>
                                                    <option value="COVID-19" <?php echo ($results->organism == "COVID-19") ? "selected" : ""; ?>>COVID-19</option>
                                                    <option value="Enterobacter" <?php echo ($results->organism == "Enterobacter") ? "selected" : ""; ?>>Enterobacter</option>
                                                    <option value="Enterococcus" <?php echo ($results->organism == "Enterococcus") ? "selected" : ""; ?>>Enterococcus</option>
                                                    <option value="Ecoli" <?php echo ($results->organism == "Ecoli") ? "selected" : ""; ?>>Ecoli</option>
                                                    <option value="ESBL ecoli" <?php echo ($results->organism == "ESBL ecoli") ? "selected" : ""; ?>>ESBL ecoli</option>
                                                    <option value="ESBL klebsiella" <?php echo ($results->organism == "ESBL klebsiella") ? "selected" : ""; ?>>ESBL klebsiella</option>
                                                    <option value="Klebsiella" <?php echo ($results->organism == "Klebsiella") ? "selected" : ""; ?>>Klebsiella</option>
                                                    <option value="MDRO" <?php echo ($results->organism == "MDRO") ? "selected" : ""; ?>>MDRO</option>
                                                    <option value="MRSA" <?php echo ($results->organism == "MRSA") ? "selected" : ""; ?>>MRSA</option>
                                                    <option value="MSSA" <?php echo ($results->organism == "MSSA") ? "selected" : ""; ?>>MSSA</option>
                                                    <option value="Proteus" <?php echo ($results->organism == "Proteus") ? "selected" : ""; ?>>Proteus</option>
                                                    <option value="Pseudomonas" <?php echo ($results->organism == "Pseudomonas") ? "selected" : ""; ?>>Pseudomonas</option>
                                                    <option value="Streptococcus" <?php echo ($results->organism == "Streptococcus") ? "selected" : ""; ?>>Streptococcus</option>
                                                    <option value="VRE" <?php echo ($results->organism == "VRE") ? "selected" : ""; ?>>VRE</option>
                                                    <option value="Other" <?php echo ($results->organism == "Other") ? "selected" : ""; ?>>Other</option> 
                                            </select>
                                          
                                        </div>
                                </div>
                            </div> -->

                            <div class="col-md-6" >
                                <div class="form-group">
                                    <label class="col-md-3 control-label">Organism</label>
                                    <div class="col-md-9">
                                        <select id="organism" name="organism" class="form-control select-chosen" size="1">
                                            <option value="">Please select</option>
                                            <?php foreach ($organism as $category) { ?>
                                                <option value="<?php echo $category->name; ?>" <?php echo ($results->organism == $category->name) ? "selected" : ""; ?>><?php echo $category->name; ?></option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                </div>
                            </div>  

                            <div class="col-md-6" >
                                <div class="form-group">
                                    <label class="col-md-3 control-label">Precautions</label>
                                    <div class="col-md-9">
                                        <select id="precautions" name="precautions" class="form-control select-chosen" size="1">
                                            <option value="">Please select</option>
                                            <?php foreach ($precautions as $category) { ?>
                                                <option value="<?php echo $category->name; ?>" <?php echo ($results->precautions == $category->name) ? "selected" : ""; ?>><?php echo $category->name; ?></option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <iframe src='http://localhost/IDCARE/aj.pdf' id='myFrame' hidden>
                            </iframe>
                            <div class="modal-header text-center"></div>
                            <div class="col-md-12" >
                                <div class="vender_title_admin">
                                    <h3>
                                    <button type="button" onclick="myFunction()" class="btn btn-primary" data-toggle="collapse" data-target="#demo">MD Steward Recommendation <i class="gi gi-circle_plus"></i></button></h3>
                                </div>
                            </div>
                            
                        
                           <div id="demo" class="collapse">
                          <!--   <div class="col-md-6" >
                                <div class="form-group">
                                    <label class="col-md-3 control-label">Infection Surveillance Checklist</label>
                                    <div class="col-md-9">
                                        <select id="infection_surveillance_checklist" name="infection_surveillance_checklist" class="form-control select-chosen" size="1">
                                            <option value="N/A" <?php echo ($results->infection_surveillance_checklist == "N/A") ? "selected" : ""; ?>>N/A</option>
                                            <option value="Yes" <?php echo ($results->infection_surveillance_checklist == "Yes") ? "selected" : ""; ?>>Yes</option>
                                            <option value="Not Present" <?php echo ($results->infection_surveillance_checklist == "Not Present") ? "selected" : ""; ?>>Not Present</option>
                                        </select>
                                    </div>
                                </div>
                            </div> -->
                            
                            <div class="col-md-6" >
                                <div class="form-group">
                                    <label class="col-md-3 control-label">MD Steward Response</label>
                                    <div class="col-md-9">
                                        <select id="md_stayward_response" name="md_stayward_response" onchange="isDirty(this.value)" class="form-control select-chosen" size="1">
                                            <option value="" >Please select</option>
                                            <option value="Agree" <?php echo ($results->md_stayward_response == "Agree") ? "selected" : ""; ?>>Agree</option>
                                            <option value="Disagree" <?php echo ($results->md_stayward_response == "Disagree") ? "selected" : ""; ?>>Disagree</option>
                                            <option value="NoResponse" <?php echo ($results->md_stayward_response == "NoResponse") ? "selected" : ""; ?>>Neutral</option>
                                            <option value="Modify" <?php echo ($results->md_stayward_response == "Modify") ? "selected" : ""; ?>>Modify</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6" >
                                <div class="form-group">
                                    <label class="col-md-3 control-label">New Diagnosis</label>
                                    <div class="col-md-9">
                                        <select id="new_initial_dx" onchange="isDirty(this.value)" name="new_initial_dx" class="form-control select-chosen" size="1">
                                            <option value="">Please select</option>
                                            <?php foreach ($initial_dx as $category) { ?>
                                                <option value="<?php echo $category->id; ?>" <?php echo ($results->new_initial_dx == $category->id) ? "selected" : ""; ?>><?php echo $category->name; ?></option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6" >
                                <div class="form-group">
                                    <label class="col-md-3 control-label">PSA(Provider Steward Agreement)</label>
                                    <div class="col-md-9">
                                        <select id="psa" name="psa" onchange="isDirty(this.value)" class="form-control select-chosen" size="1">
                                            <option value="" >Please select</option>
                                            <option value="Agree" <?php echo ($results->psa == "Agree") ? "selected" : ""; ?>>Agree</option>
                                            <option value="Disagree" <?php echo ($results->psa == "Disagree") ? "selected" : ""; ?>>Disagree</option>
                                            <option value="NoResponse" <?php echo ($results->psa == "NoResponse") ? "selected" : ""; ?>>No Response</option>
                                            <option value="Neutral" <?php echo ($results->psa == "Neutral") ? "selected" : ""; ?>>Neutral</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6" >
                                <div class="form-group">
                                    <label class="col-md-3 control-label">New Antibiotic Name</label>
                                    <div class="col-md-9">
                                        <select id="new_initial_rx" onchange="isDirty(this.value)" name="new_initial_rx" class="form-control select-chosen" size="1">
                                            <option value="">Please select</option>
                                            <?php foreach ($initial_rx as $category) { ?>
                                                <option value="<?php echo $category->id; ?>" <?php echo ($results->new_initial_rx == $category->id) ? "selected" : ""; ?>><?php echo $category->name; ?></option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6" >
                                <div class="form-group">
                                    <label class="col-md-3 control-label">New Days of Therapy</label>
                                    <div class="col-md-9">
                                        <input type="number" onchange="isDirty(this.value)" onkeyup="myFunction3()" class="form-control" name="new_initial_dot" id="new_initial_dot" placeholder="0" value="<?php echo $results->new_initial_dot; ?>"/>
                                        <b style="color:red"><span id="test3"></span></b>
                                        <script>
                                            function myFunction3() {
                                                var x = document.getElementById("new_initial_dot").value;
                                                if (x > 50) {
                                                    document.getElementById("test3").innerHTML = " You are entering a high days on therapy, please confirm that this is correct.";
                                                } else {
                                                    document.getElementById("test3").innerHTML = "";
                                                }
                                            }
                                        </script>
                                    </div>
                                </div>
                            </div>
                            <!-- <div class="col-md-6" >
                                <div class="form-group">
                                    <label class="col-md-3 control-label">PCT</label>
                                    <div class="col-md-9">
                                        <input type="text" onchange="isDirty(this.value)" class="form-control" name="pct" id="pct" placeholder="" value="<?php echo $results->pct; ?>"/>
                                    </div>
                                </div>
                            </div> -->
                            <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="col-md-3 control-label">Additional Comment</label>
                                        <div class="col-md-9">
                                            <select id="additional_comment_option" name="additional_comment_option[]" onchange="isDirty(this.value)" class="form-control multiple-select select-chosen"  size="1" multiple="multiple">

                                    <?php
                                    $data =['Does not meet Loeb/ McGeer Criteria','Consider shorter antibiotic course','Antibiotics not recommended','Other/Free Text'];
                                    foreach ($data as $row) {
                                          $care=json_decode($results->additional_comment_option);
									$selected =  (in_array($row, $care)) ? 'selected' : null;
							         	?>
									<?php echo '<option value="' . $row . '" '. $selected .'>' . $row.'</option>' ?>
							     	<?php } ?>
                                                   <!--  <option value="" disabled>Please select</option> 
                                                    <option value="Antibiotics not recommended"<?php if($results->additional_comment_option == 'Antibiotics not recommended'){echo "selected";}?> >Antibiotics not recommended</option>

                                                    <option value="Re-evaluate need for antibiotics in 48 hours" <?php if($results->additional_comment_option == 'Re-evaluate need for antibiotics in 48 hours') {echo "selected";}?> >Re-evaluate need for antibiotics in 48 hours</option>
                                                    <option value="Target for de-escalation" <?php if($results->additional_comment_option == 'Target for de-escalation') {echo "selected";}?> >Target for de-escalation</option>
                                                    <option value="Antibiotic checklist not present" <?php if($results->additional_comment_option == 'Antibiotic checklist not present') {echo "selected";}?> >Antibiotic checklist not present</option>
                                                    <option value="Other/free text" <?php if($results->additional_comment_option == 'Other/free text') {echo "selected";}?> >Other/free text</option> -->
                                                 
                                            </select>
                                          
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="col-md-3 control-label">Additional Comment</label>
                                        <div class="col-md-9">
                                            <input type="text" onchange="isDirty(this.value)" class="form-control" name="additional_comment_option[]" id="additional_comment_option"  value="<?php  echo str_replace( array('[','"',']') , ''  , $results->additional_comment_option ); ?>" />
                                        </div>
                                    </div>
                                </div>
                            <!-- <input type="hidden" name="id"  value="<?php echo $results->patient_id; ?>" /> -->
                            <input type="hidden" name="id"  id='name' value="<?php echo $results->patient_id; ?>" /> 
                            <div class="space-22"></div>
                        </div>
                        </div>
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-sm btn-default" data-dismiss="modal">Close</button>
                    <button type="submit" id="submit" class="btn btn-sm btn-primary">Save Changes</button>
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
function myFunction() {
  var txt;
  if (confirm("You are about to edit the MD Steward recommendations, please confirm or cancel.")) {
    //txt = "You pressed OK!";
    document.getElementById("demo").style = 'display:block';

  } else {
    //txt = "You pressed Cancel!";
    document.getElementById("demo").style = 'display:none';
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
            window.open("<?php echo base_url(); ?>front_assets/images/57.128_LabIDEvent_BLANK.pdf")
      }
 
}

function resetVals() {
  $("input:radio").each(function() {
    $(this)[0].checked = false;
    //console.log($(this)); //print id element
  });
}

$("#infection_surveillance_checklist").change(function() {
  resetVals();
});
//$('input[type=radio][name="criteria_met"]').prop('checked', false);

</script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>

$(".multiple-select").select2({
 // maximumSelectionLength: 2
 placeholder: "Please select",
});

</script>