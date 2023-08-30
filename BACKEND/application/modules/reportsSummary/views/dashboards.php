<?php if ($this->ion_auth->is_admin() || $this->ion_auth->is_subAdmin() || $this->ion_auth->is_facilityManager()) { ?>
    </style>
    <style>
        .panel-bodyy{
            display:flex;
        }
        @media only screen and (max-width: 600px) {
            .panel-bodyy{
            flex-direction:column;
        } 
        .ajay1{
            width:190%;
        } 
        }


        @media only screen and (max-width: 600px) {
            .exportbutton{
                width: 100%;
        }  
        .exportbutton1{
            margin-top: -24px;
            float: left;
            width: 192px;
            font-size:11px;
            padding-left:4px;
            /* margin-left:-175 */
        }  
        .exportbutton2 {
            padding-bottom: 24px;
            margin-top: 10px;
        }
        }

        @media only screen and (max-width: 600px) {
            .exportbutton{
                width: 50%;
        }  
        .exportbutton3{
            margin-top: -24px;
            float: left;
            width: 192px;
        }  
        .exportbutton2 {
            padding-bottom: 24px;
            margin-top: 10px;
        }
        }


        @media only screen and (min-width: 668px) and (max-width: 1600px) {
        .exportbutton1{
            margin-top: -24px;
            margin-right: -33px;
            float: right;
            width: 246px;
        }  
        
        .exportbutton{
                max-width: 95%;
        }  
        .exportbutton2 {
            padding-bottom: 24px;
            margin-top: 10px;
        }
    }

    .cultur_source{
        padding: 0px 1px;
    }

    @media only screen and (min-width: 668px) and (max-width: 1600px) {
        .exportbutton3{
            margin-top: -24px;
            margin-right: -33px;
            float: right;
            width: 196px;
        }  
        
        .exportbutton{
                max-width: 95%;
        }  
        .exportbutton2 {
            padding-bottom: 24px;
            margin-top: 10px;
        }
    }

    .cultur_source{
        padding: 0px 1px;
    }


    #Graph-chart21{
        height: 540px;
    }
    </style>
    <!-- Page content -->
    <div id="page-content">
        <!--<div id="msg"></div>-->
        <!-- eShop Overview Block -->
        <?php
        $message = $this->session->flashdata('success');
        if (!empty($message)):
            ?><div class="alert alert-success">
                <?php echo $message; ?></div><?php endif; ?>
        <div class="block full">
            <div class="row text-center">
                <div class="col-sm-12 col-lg-12">
                    <div class="col-sm-12 col-lg-12">
                        <!-- <div class="panel panel-default">
                            <div class="panel-body">
                                <form action="<?php echo site_url('setting/add_days'); ?>" name="patientForm" method="post">
                                    <div class="col-sm-6 col-lg-3">
                                        <div class="text-left">Total Patient Days: </div>
                                        <input type="text" name="total_patient_days" class="form-control" value="<?php //echo (getConfig('total_patient_days') <= 0) ? $tatal_days : getConfig('total_patient_days'); ?>"/>
                                    </div>
                                    <div class="col-sm-6 col-lg-3">
                                        <div class="text-left">Total Days on Antibiotic: </div>
                                        <input type="text" name="total_patient_days1" class="form-control" value="<?php echo $total_antibiotic_days; ?>" disabled=""/>
                                    </div>
                                    <div class="col-sm-6 col-lg-2">
                                        <div class="text-left">Antibiotics Days Per 1000 Total Patient days:</div>
                                        <input type="text" name="total_patient_day2" class="form-control" value="<?php echo $toatl_patient_days_average; ?>" disabled=""/>
                                    </div>
                                    <div class="col-sm-6 col-lg-2">
                                        <div class="text-left"><i class="fa fa-redo"></i></div>
                                        <input type="submit" class="btn btn-primary btn-sm form-control" value="Submit"/>
                                    </div>
                                    <div class="col-sm-6 col-lg-2">
                                        <div class="text-left"><i class="fa fa-redo"></i></div>
                                        <button type="button" class="btn btn-danger btn-sm form-control" onclick="un_days()"><i class="fa fa-undo"></i> Undo</button>
                                    </div>
                                </form>
                            </div>
                        </div> -->

                    <div class="panel panel-default">
                        <div class="panel-body panel-bodyy">

                            <div class="col-sm-12 col-lg-3">
                                <select id="careUnit" name="careUnit" class="form-control select-2" onchange="getAntibioticByCareUnit(this.value)">
                                    <option value="">Select Care Unit</option>
                                   <!--  <?php
                                    if (isset($careUnit) && !empty($careUnit)) {
                                        foreach ($careUnit as $row) {
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
                                    ?> -->
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

                                           
                                            
                                            
                                            foreach ($careUnit as $category) { ?>

                                                <option value="<?php echo $category->id; ?>"><?php echo $category->name; ?></option>
                                            <?php }  } ?>
                                </select>


                            </div>



                            <div class="col-sm-12 col-lg-3">
                                <select id="provider_doctor" name="provider_doctor" class="form-control select-2" onchange="getAntibioticByCareUnit()">
                                    <option value="">Select Provider MD</option>
                                    <?php
                                    if (isset($doctors) && !empty($doctors)) {
                                        foreach ($doctors as $row) {
                                            ?>
                                            <option value="<?php echo $row->id; ?>"><?php echo $row->name; ?></option>
                                            <?php
                                        }
                                    }
                                    ?></select></div>


<?php if ($this->ion_auth->is_admin()) { ?>
                         <div class="col-sm-12 col-lg-3">
                        <select id="md_steward_id" name="md_steward_id"  class="form-control select-2"  size="1">
                                        <option value="">Select MD Steward</option>
                                        <?php foreach($staward as $row){?>
                                                    
                                        <option value="<?php echo $row->id; ?>"><?php echo $row->first_name . ' ' . $row->last_name; ?></option>
                                                
                                        <?php }?>
                                    </select>
                                </div>
    

 


     <?php }else if($this->ion_auth->is_facilityManager()) {?>

            <div class="col-sm-12 col-lg-3">
                                <select id="steward" name="steward" class="form-control select-2" onchange="getAntibioticByCareUnit()">
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
                                    ?></select></div>
          <?php } ?>

          
                            <div class="col-sm-12 col-lg-3">
                                <select id="RX" name="RX" class="form-control select-2" onchange="getAntibioticByCareUnit();">
                                    <option value="">Select Antibiotic</option>
                                    <?php
                                    if (!empty($initial_rx)) {
                                        foreach ($initial_rx as $row) {
                                            ?>
                                            <option value="<?php echo $row->id; ?>"><?php echo $row->name; ?></option>
                                            <?php
                                        }
                                    }
                                    ?></select></div>

                            <div class="col-sm-12 col-lg-3">
                                <select id="symptom_onset" name="symptom_onset" class="form-control select-2" onchange="getAntibioticByCareUnit(this.value)">
                                    <option value="">Infection Onset</option>
                                    <option value="Facility">Facility/HAI</option>
                                    <option value="Hospital">Hospital/CAI</option>
                                    <option value="">Total/HAI+CAI</option>

                                  
                                    </select></div>

                                    <div class="col-sm-12 col-lg-3">
                                <select id="criteria_met" name="criteria_met" class="form-control select-2" onchange="getAntibioticByCareUnit(this.value)">
                                    <option value=""><!-- Infection Checklist -->Criteria Met</option>
                                    <option value="Yes">Yes</option>
                                    <option value="No">No</option>
                                    <option value="N/A">N/A</option>
                                  
                                    </select></div>
                        </div>                      
                    </div>
                    <div class="panel panel-default">
                        <div class="panel-body panel-bodyy">

                       <!--  <div class="col-sm-12 col-lg-3">
                                <select id="md_stayward_response" name="md_stayward_response" class="form-control select-2" onchange="getAntibioticByCareUnit(this.value)">
                                    <option value="">PSA</option>
                                    <option value="Agree">Agree</option>
                                    <option value="Disagree">Disagree</option>
                                    <option value="NoResponse">NA</option>
                                    
                                    </select></div> -->
                                    
                                   <!--  <div class="col-sm-12 col-lg-3">
                                <select id="criteria_met" name="criteria_met" class="form-control select-2" onchange="getAntibioticByCareUnit(this.value)">
                                    <option value="">Infection Checklist</option>
                                    <option value="Yes">Yes</option>
                                    <option value="No">No</option>
                                    <option value="N/A">N/A</option>
                                  
                                    </select></div> -->
                                    
                                    <!-- <div class="col-sm-12 col-lg-3">
                                    <select id="culture_source" name="culture_source" class="form-control select-2 cultur_source" onchange="getAntibioticByCareUnit(this.value)">
                                                    <option value="">Select Culture Source</option> 
                                                    <option value="NA">NA</option>
                                                    <option value="Blood">Blood</option>
                                                    <option value="Nares">Nares</option>
                                                    <option value="Sputum">Sputum</option>
                                                    <option value="Stool">Stool</option>
                                                    <option value="Urine">Urine</option>
                                                    <option value="Wound">Wound</option>
                                    </select>
                                    </div>   --> 

                                    <div class="col-sm-12 col-lg-3">
                                    <select id="culture_source" name="culture_source" class="form-control select-2 cultur_source" onchange="getAntibioticByCareUnit()">
                                    <option value="">Select Culture Source</option>
                                    <?php
                                    if (isset($culture_source) && !empty($culture_source)) {
                                        foreach ($culture_source as $row) {
                                            ?>
                                            <option value="<?php echo $row->name; ?>"><?php echo $row->name; ?></option>
                                            <?php
                                        }
                                    }
                                    ?>
                                    </select>
                                    </div>
                                    
                                    

                                    <!-- <div class="col-sm-12 col-lg-3">
                                    <select id="organism" name="organism" class="form-control select-2" onchange="getAntibioticByCareUnit(this.value)">
                                                    <option value="">Select Organism</option> 
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
                                                    <option value="ESBL organism">ESBL organism</option>
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
                                    </div> -->


                                    <div class="col-sm-12 col-lg-3">
                                    <select id="organism" name="organism" class="form-control select-2" onchange="getAntibioticByCareUnit()">
                                    <option value="">Select Organism</option>
                                    <?php
                                    if (isset($organism) && !empty($organism)) {
                                        foreach ($organism as $row) {
                                            ?>
                                            <option value="<?php echo $row->name; ?>"><?php echo $row->name; ?></option>
                                            <?php
                                        }
                                    }
                                    ?>
                                    </select>
                                    </div>

                                    <div class="col-sm-12 col-lg-3">
                                    <select id="precautions" name="precautions" class="form-control select-2" onchange="getAntibioticByCareUnit(this.value)">
                                    <option value="">Select Precautions</option>
                                    <?php
                                    if (isset($precautions) && !empty($precautions)) {
                                        foreach ($precautions as $row) {
                                            ?>
                                            <option value="<?php echo $row->name; ?>"><?php echo $row->name; ?></option>
                                            <?php
                                        }
                                    }
                                    ?>
                                    </select>
                                    </div>
                        
                            <div class="col-sm-12 col-lg-3">
             
                                   <input type="text" class="form-control" name="fromdate" id="date1" placeholder="From Date" onchange="getReports()"/>

                            </div>
                              <div class="col-sm-12 col-lg-3">
             
                                   <input type="text" class="form-control" name="todate" id="date2" placeholder="To Date" onchange="getReports()" />
                               
                            </div>
                            <div class="col-sm-12 col-lg-3"style="margin:-18px;">
                                        <div class="text-left"><i class="fa fa-redo"></i></div>
                                        <button type="button" class="btn btn-primary btn-sm form-control" onclick="un_days()"><i class="fa fa-undo"></i> Reset</button>
                             </div>
                        </div>
                        
                    </div>

                    <div class="panel panel-default">
                        <div class="panel-body panel-bodyy">

                        <div class="col-sm-12 col-lg-4">
                                <select id="date_of_start_abx" name="date_of_start_abx" class="form-control select-2" onchange="getAntibioticByCareUnit(this.value)">
                                    <option value="">Select Month</option>
                                    <option value="01">January</option>
                                    <option value="02">February</option>
                                    <option value="03">March</option>
                                    <option value="04">April</option>
                                    <option value="05">May</option>
                                    <option value="06">June</option>
                                    <option value="07">July</option>
                                    <option value="08">August</option>
                                    <option value="09">September</option>
                                    <option value="10">October</option>
                                    <option value="11">November</option>
                                    <option value="12">December</option>
                                </select>
                        </div>

                        <div class="col-sm-12 col-lg-4">
                                <select id="date_of_start_abx1" name="date_of_start_abx1" class="form-control select-2" onchange="getAntibioticByCareUnit(this.value)">
                                    <option value="">Select Year</option>
                                    <option value="2023">2023</option>
                                    <option value="2022">2022</option>
                                    <option value="2021">2021</option>
                                </select>
                        </div>

                        <div class="col-sm-12 col-lg-4 ajay1">
                        <div class="exportbutton" >
                        <div  data-toggle="collapse" data-target="#collapseOne">
                        <div  data-toggle="collapse" data-target="#collapseOne2">
                        <div  data-toggle="collapse" data-target="#collapseOne5">
                        <div  data-toggle="collapse" data-target="#collapseOne90">
                        <div  data-toggle="collapse" data-target="#collapseOne6">
                        <div  data-toggle="collapse" data-target="#collapseOne7">
                        <div  data-toggle="collapse" data-target="#collapseOne8" class="">
                            <button type="button"  style="color:white;padding-right:15px" data-toggle="tooltip" data-placement="bottom" class="form-control btn btn-success " onclick="setTimeout(downloadPDF23,1000)">Download Monthly reports</button>
                        </div>
                        </div>
                        </div>
                        </div>
                        </div>
                        </div>
                        </div>
                        </div>
                        </div>

                        <div class="col-sm-12 col-lg-4">
                                <select id="date_of_start_abx2" name="date_of_start_abx2" class="form-control select-2" onchange="getAntibioticByCareUnit(this.value)">
                                    <option value="">Select Quarter/Year</option>
                                    <!-- <?php
                                    if (isset($precautions) && !empty($precautions)) {
                                        foreach ($precautions as $row) {
                                            ?>
                                            <option value="<?php echo $row->name; ?>"><?php echo $row->name; ?></option>
                                            <?php
                                        }
                                    }
                                    ?> -->

                                    <option value="01">First Quarter</option>
                                    <option value="02">Second Quarter</option>
                                    <option value="03">Third Quarter</option>
                                    <option value="04">Fourth Quarter</option>
                                    <option value="2023">Year 2023</option>
                                    <option value="2022">Year 2022</option>
                                    <option value="2021">Year 2021</option>

                                </select>
                        </div>

                        <div class="col-sm-12 col-lg-4">
                                <select id="date_of_start_abx3" name="date_of_start_abx3" class="form-control select-2" onchange="getAntibioticByCareUnit(this.value)">
                                    <option value="">Select Year</option>
                                    <!-- <?php
                                    if (isset($precautions) && !empty($precautions)) {
                                        foreach ($precautions as $row) {
                                            ?>
                                            <option value="<?php echo $row->name; ?>"><?php echo $row->name; ?></option>
                                            <?php
                                        }
                                    }
                                    ?> -->
                                    <option value="2023">2023</option>
                                    <option value="2022">2022</option>
                                    <option value="2021">2021</option>
                                </select>
                        </div>



                        <div class="col-sm-12 col-lg-4 ajay1">
                        <div class="exportbutton" >
                        <div  data-toggle="collapse" data-target="#collapseOne">
                        <div  data-toggle="collapse" data-target="#collapseOne2">
                        <div  data-toggle="collapse" data-target="#collapseOne5">
                        <div  data-toggle="collapse" data-target="#collapseOne90">
                        <div  data-toggle="collapse" data-target="#collapseOne6">
                        <div  data-toggle="collapse" data-target="#collapseOne7">
                        <div  data-toggle="collapse" data-target="#collapseOne8" class="">
                            <button style="color:white;padding-right:18px" type="button" data-toggle="tooltip" data-placement="bottom" class="form-control btn btn-success" onclick="setTimeout(downloadPDF22,1000)"> Download Quarterly/Yearly reports </button>
                        </div>
                        </div>
                        </div>
                        </div>
                        </div>
                        </div>
                        </div>
                        </div>
                        </div>

                    </div>
                        </div>


                    </div>
                </div>
            </div>

            <!-- eShop Overview Title -->
           <!--  <div class="row" style="margin-top:10px"> -->
            <!-- <div class="exportbutton"  style="margin-right: 310px;">
            <div  data-toggle="collapse" data-target="#collapseOne">
            <div  data-toggle="collapse" data-target="#collapseOne2">
            <div  data-toggle="collapse" data-target="#collapseOne5">
            <div  data-toggle="collapse" data-target="#collapseOne90">
            <div  data-toggle="collapse" data-target="#collapseOne6">
            <div  data-toggle="collapse" data-target="#collapseOne7">
            <div  data-toggle="collapse" data-target="#collapseOne8" class="exportbutton2">
            <button type="button" data-toggle="tooltip" data-placement="bottom" class="btn btn-success exportbutton1 exportbutton3" onclick="setTimeout(downloadPDF23,1000)/* ('canvas21','canvas30','canvas43','canvas42','canvas32','canvas33','canvas49','canvas34','canvas35','canvas37','canvas38','canvas46','canvas47','canvas44','canvas45','Total Antibiotic Days by Provider'),3000) */">Download Monthly reports</button>

            <button style="margin-right: -445px;" type="button" data-toggle="tooltip" data-placement="bottom" class="btn btn-success exportbutton1" onclick="setTimeout(downloadPDF22,1000)/* ('canvas21','canvas30','canvas43','canvas42','canvas32','canvas33','canvas49','canvas34','canvas35','canvas37','canvas38','canvas46','canvas47','canvas44','canvas45','Total Antibiotic Days by Provider'),3000) */"> Download Quarterly/Yearly reports </button>

            </div>
            </div>
            </div>
            </div>
            </div>
            </div>
            </div>
            </div> -->
            <!-- </div> -->
            <div class="row">

                <div class="col-lg-12">

                   
                <div class="panel panel-success">
                    <div class="panel-heading">
                        <h4 class="panel-title"
                            data-toggle="collapse"
                            data-target="#collapseOne6">
                        <!-- Total Antibiotics Days Vs Actual DOT -->
                        Total Antibiotic Days on Therapy Vs Total Actual Antibiotic Days On Therapy
                            <a href="#" data-toggle="tooltip" data-placement="bottom"
                            title="" data-original-title="This section represents the total Antibiotics days Vs Actual Days of Therapy."
                            class="red-tooltip"><i class="fa fa-info-circle"></i></a>
                        </h4>
                    </div>
                    <div id="collapseOne6" class="panel-collapse collapse">

                        <div class="panel-body">
                            <div class="col-lg-12 col-sm-12">
                                <h5><strong>Total Antibiotic Days on Therapy<button type="button" onclick="downloadPDF34('canvas34','Total Antibiotic Days on Therapy')"> Export </button></strong></h5>
                                <div id='Graph-chart34' style="min-width:250px; min-height: 320px;">
                                    <canvas id="canvas34"></canvas>
                                </div>
                            </div>
                            

                            <div class="col-lg-12 col-sm-12">
                                <h5><strong> <!-- Total Initial Days On Therapy  Vs Actual Days On Therapy   -->Total Antibiotic Days on Therapy vs. Steward Antibiotic Days on Therapy<button type="button" onclick="downloadPDF35('canvas35','Total Antibiotic Days on Therapy vs. Steward Antibiotic Days on Therapy')"> Export </button></strong></h5>
                                <div id='Graph-chart35' style="min-width:250px; min-height: 320px;">
                                    <canvas id="canvas35"></canvas>
                                </div>
                            </div>

                    <!--    <div class="col-lg-12 col-sm-12">
                                <h5><strong><button type="button" onclick="downloadPDF2('canvas35','Total Antibiotics days Vs Actual DOT')"> Export </button></strong></h5>
                                <div id='Graph-chart35' style="min-width:250px; min-height: 320px;">
                                    <canvas id="canvas35"></canvas>
                                </div>
                            </div> -->

                        </div>
                    </div>
                    </div>

                    <div class="panel panel-success">
                        <div class="panel-heading">
                            <h4 class="panel-title"
                                data-toggle="collapse"
                                data-target="#collapseOne">
                                <!-- Antibiotics by Provider MD  -->
                                Total Antibiotic Days by Provider

                                <a href="#" data-toggle="tooltip" data-placement="bottom"
                                   title="" data-original-title="This section will represent the data of antibiotics days based on selected provider, once you select  any provider that provider will be compared with the average of rest providers."
                                   class="red-tooltip"><i class="fa fa-info-circle"></i></a>
                            </h4>
                        </div>
                        <div id="collapseOne" class="panel-collapse collapse">
                            <div class="panel-body" id='reportPage'>
                            <div class="col-lg-4 col-sm-12">
                                    <h5><strong>Average Antibiotic Days by Provider
                                        <button type="button" onclick="downloadPDF211('canvas211','Average Antibiotic Days by Provider')"> Export </button>
                                    </strong> </h5>
                                    </div>
                            <div class="col-lg-4 col-sm-12">
                                    <h5><strong>Total Antibiotic Days by Provider
                                        <button type="button" onclick="downloadPDF21('canvas21','Total Antibiotic Days by Provider')"> Export </button>
                                    </strong> </h5>
                                    </div>
                                 <div class="col-lg-4 col-sm-12" id="total_percent_filter" style="display:none;">
                                    <h5><strong>Average Antibiotic days = Total Provider's Days / Total Provider : <span id="total_days"></span> / <span id="Total_providers"></span> = <span id="avg_days"></span></strong></h5>
                                </div> 
                   
                                <div class="col-lg-12 ">
                                     <div class="col-lg-2 col-sm-12" id='Graph-chart211' style="max-width:250px; height: 520px!important;" >
                                        <canvas id="canvas211"></canvas>
                                    </div>
                                    <div class="col-lg-10 col-sm-12" id='Graph-chart21' style="min-width:250px; height: 520px!important;">
                                        <canvas id="canvas21"></canvas>
                                    </div>
                                   
                                </div>
                                
                                
                                <!-- </br>
                                <div class="col-lg-12 col-sm-12">
                                    <h5><strong>Total Antibiotic Days by Provider- Pie Chart<button type="button" onclick="downloadPDF30('canvas30','Total Antibiotic Days by Provider- Pie Chart')"> Export </button></strong></h5>
                                    <div id='Graph-chart30' style="min-width:250px; min-height: 720px;">
                                        <canvas id="canvas30"></canvas>
                                    </div>
                                </div> -->
                            </div>
                        </div>
                    </div>


                    <div class="panel panel-success">
                        <div class="panel-heading">
                            <h4 class="panel-title"
                                data-toggle="collapse"
                                data-target="#collapseOne2">
                               <!-- Detailed Antibiotics Percentage by Facility -->
                              Total Antibiotic Days by Antibiotic
                                <a href="#" data-toggle="tooltip" data-placement="bottom"
                                   title="" data-original-title="This section will represent antibiotics days based on selected facility and steward"
                                   class="red-tooltip"><i class="fa fa-info-circle"></i></a>
                            </h4>
                        </div>
                        <div id="collapseOne2" class="panel-collapse collapse">
                            <div class="panel-body">
                            <div class="col-lg-4 col-sm-12">
                                    <h5><strong>Average Antibiotic Days by Antibiotic
                                        <button type="button" onclick="downloadPDF433('canvas433','Average Antibiotic Days by Antibiotic')"> Export </button>
                                    </strong> </h5>
                                    </div>
                                <div class="col-lg-4 col-sm-12">
                                    <h5><strong> Total Antibiotic Days by Antibiotic <button type="button" onclick="downloadPDF43('canvas43','Total Antibiotic Days by Antibiotic')"> Export </button></strong></h5>
                                    <!-- <div id='Graph-chart43' style="min-width:250px; min-height: 520px;">
                                        <canvas id="canvas43"></canvas>
                                    </div> -->
                                </div>


                                <div class="col-lg-12 ">
                                     <div class="col-lg-2 col-sm-12" id='Graph-chart433' style="max-width:250px; height: 520px!important;" >
                                        <canvas id="canvas433"></canvas>
                                    </div>
                                    <div class="col-lg-10 col-sm-12" id='Graph-chart43' style="min-width:250px; height: 520px!important;">
                                        <canvas id="canvas43"></canvas>
                                    </div>
                                   
                                </div>
                                <!-- </br> -->
                               <!--  <div class="col-lg-12 col-sm-12">
                                    <h5><strong>% Initial Days On Therapy by Antibiotic<button type="button" onclick="downloadPDF2('canvas41','% Initial Days On Therapy by Antibiotic')"> Export </button></strong></h5>
                                    <div id='Graph-chart41' style="min-width:250px; min-height: 420px;">
                                        <canvas id="canvas41"></canvas>
                                    </div>
                                </div> -->

                                <!-- <div class="col-lg-12 col-sm-12">
                                    <h5><strong>Total Initial Days On Therapy by Antibiotic-Pie Chart<button type="button" onclick="downloadPDF42('canvas42','Total Initial Days On Therapy by Antibiotic-Pie Chart')"> Export </button></strong></h5>
                                    <div id='Graph-chart42' style="min-width:250px; min-height: 520px;">
                                        <canvas id="canvas42"></canvas>
                                    </div>
                                </div> -->
                            </div>
                        </div>
                    </div>
                    

                     <div class="panel panel-success">

                        <div class="panel-heading">
                            <h4 class="panel-title"
                                data-toggle="collapse"
                                data-target="#collapseOne5">
                               <!-- Total Antibiotics for diagnosis -->
                               Total Antibiotic Days by Diagnosis
                                <a href="#" data-toggle="tooltip" data-placement="bottom"
                                   title="" data-original-title="This section represents the total Antibiotics for diagnosis."
                                   class="red-tooltip"><i class="fa fa-info-circle"></i></a>
                            </h4>
                        </div>
                        <div id="collapseOne5" class="panel-collapse collapse">

                            <div class="panel-body">

                            <div class="col-lg-4 col-sm-12">
                                    <h5><strong>Average Antibiotic Days by Diagnosis
                                        <button type="button" onclick="downloadPDF322('canvas322','Average Antibiotic Days by Diagnosis')"> Export </button>
                                    </strong> </h5>
                                    </div>

                                <div class="col-lg-4 col-sm-12">
                                    <h5><strong>Total Antibiotic Days by Diagnosis <button type="button" onclick="downloadPDF32('canvas32','Total Antibiotic Days by Diagnosis')"> Export </button></strong></h5>
                                    <!-- <div id='Graph-chart32' style="min-width:250px; min-height: 520px;">
                                        <canvas id="canvas32"></canvas>
                                    </div> -->
                                </div>

                                <div class="col-lg-12 ">
                                     <div class="col-lg-2 col-sm-12" id='Graph-chart322' style="max-width:250px; height: 520px!important;" >
                                        <canvas id="canvas322"></canvas>
                                    </div>
                                    <div class="col-lg-10 col-sm-12" id='Graph-chart32' style="min-width:250px; height: 520px!important;">
                                        <canvas id="canvas32"></canvas>
                                    </div>
                                   
                                </div>
                                <!-- </br>


                                <div class="col-lg-12 col-sm-12">
                                    <h5><strong>Total Initial Days On Therapy by Diagnosis-Pie Chart<button type="button" onclick="downloadPDF33('canvas33','Total Initial Days On Therapy by Diagnosis-Pie Chart')"> Export </button></strong></h5>
                                    <div id='Graph-chart33' style="min-width:250px; min-height: 520px;">
                                        <canvas id="canvas33"></canvas>
                                    </div>
                                </div> -->

                            </div>
                        </div>
                    </div>


                    <div class="panel panel-success">

                            <div class="panel-heading">
                                <h4 class="panel-title"
                                    data-toggle="collapse"
                                    data-target="#collapseOne90">
                                PSA(Provider Steward Agreement)
                                    <a href="#" data-toggle="tooltip" data-placement="bottom"
                                    title="" data-original-title="This section represents the Provider and Steward Agreement."
                                    class="red-tooltip"><i class="fa fa-info-circle"></i></a>
                                </h4>
                            </div>
                            <div id="collapseOne90" class="panel-collapse collapse">

                                <div class="panel-body">

                                    <div class="col-lg-12 col-sm-12">
                                        <h5><strong>Provider and Steward Agreement<button type="button" onclick="downloadPDF49('canvas49','Provider and Steward Agreement')"> Export </button></strong></h5>
                                        <div id='Graph-chart49' style="min-width:250px; min-height: 320px;">
                                            <canvas id="canvas49"></canvas>
                                        </div>
                                    </div>

                                </div>
                            </div>
                    </div>

                    <div class="panel panel-success">

                        <div class="panel-heading">
                            <h4 class="panel-title"
                                data-toggle="collapse"
                                data-target="#collapseOne7">
                               <!-- Total Antibiotics use by provider and steward -->
                               <!-- Total Antibiotic Days by Provider And Steward -->
                               Total Antibiotic Days on Therapy:  Provider vs. Steward
                                <a href="#" data-toggle="tooltip" data-placement="bottom"
                                   title="" data-original-title="This section represents the total Antibiotics use by provider and steward."
                                   class="red-tooltip"><i class="fa fa-info-circle"></i></a>
                            </h4>
                        </div>
                        <div id="collapseOne7" class="panel-collapse collapse">

                            <div class="panel-body">
                                <!-- <div class="col-lg-12 col-sm-12">
                                    <h5><strong>Sum Of All Actual Days On Therapy by Provider And Steward<button type="button" onclick="downloadPDF2('canvas36','Sum Of All Actual Days On Therapy by Provider And Steward')"> Export </button></strong></h5>
                                    <div id='Graph-chart36' style="min-width:250px; min-height: 520px;">
                                        <canvas id="canvas36"></canvas>
                                    </div>
                                </div> -->

                                <div class="col-lg-12 col-sm-12">
                                    <h5><strong>Total Antibiotic Days on Therapy:  Provider vs. Steward<button type="button" onclick="downloadPDF37('canvas37','Total Antibiotic Days on Therapy:  Provider vs. Steward')"> Export </button></strong></h5>
                                    <div id='Graph-chart37' style="min-width:250px; min-height: 620px;">
                                        <canvas id="canvas37"></canvas>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>

                    <div class="panel panel-success">

                        <div class="panel-heading">
                            <h4 class="panel-title"
                                data-toggle="collapse"
                                data-target="#collapseOne8">
                             <!--  Antibiotic days saved sum cost saved -->
                             Total Antibiotic Days Saved
                                <a href="#" data-toggle="tooltip" data-placement="bottom"
                                   title="" data-original-title="This section represents the antibiotic days saved sum cost saved."
                                   class="red-tooltip"><i class="fa fa-info-circle"></i></a>
                            </h4>
                        </div>
                        <div id="collapseOne8" class="panel-collapse collapse">

                            <div class="panel-body">
                                <div class="col-lg-12 col-sm-12">
                                    <h5><strong> Total Antibiotic Days Saved<button type="button" onclick="downloadPDF38('canvas38',' Total Antibiotic Days Saved')"> Export </button></strong></h5>
                                    <div id='Graph-chart38' style="min-width:250px; min-height: 320px;">
                                        <canvas id="canvas38"></canvas>
                                    </div>
                                </div>

                                <div class="col-lg-12 col-sm-12">
                                    <h5><strong>Total Antibiotic Dollars on Therapy vs. Steward Dollars on Therapy<button type="button" onclick="downloadPDF46('canvas46','Total Antibiotic Dollars on Therapy vs. Steward Dollars on Therapy')"> Export </button></strong></h5>
                                    <div id='Graph-chart46' style="min-width:250px; min-height: 320px;">
                                        <canvas id="canvas46"></canvas>
                                    </div>
                                </div>

                                <div class="col-lg-12 col-sm-12">
                                    <h5><strong>Total Dollars Saved<button type="button" onclick="downloadPDF47('canvas47','Total Dollars Saved')"> Export </button></strong></h5>
                                    <div id='Graph-chart47' style="min-width:250px; min-height: 320px;">
                                        <canvas id="canvas47"></canvas>
                                    </div>
                                </div>

                                <div class="col-lg-12 col-sm-12">
                                    <h5><strong>Antibiotic Days on Therapy:  Provider vs. Steward<button type="button" onclick="downloadPDF44('canvas44','Antibiotic Days on Therapy:  Provider vs. Steward')"> Export </button></strong></h5>
                                    <div id='Graph-chart44' style="min-width:250px; min-height: 620px;">
                                        <canvas id="canvas44"></canvas>
                                    </div>
                                </div>

                                <div class="col-lg-12 col-sm-12">
                                    <h5><strong>Antibiotic Dollars on Therapy:  Provider vs. Steward<button type="button" onclick="downloadPDF45('canvas45','Antibiotic Dollars on Therapy:  Provider vs. Steward')"> Export </button></strong></h5>
                                    <div id='Graph-chart45' style="min-width:250px; min-height: 750px;">
                                        <canvas id="canvas45"></canvas>
                                    </div>
                                </div>

                                <!-- <div class="col-lg-12 col-sm-12">
                                    <h5><strong>Sum Of All Initial Days On Therapy And Sum Of All Actual Days On Therapy By Antibiotic<button type="button" onclick="downloadPDF2('canvas39','Sum Of All Initial Days On Therapy And Sum Of All Actual Days On Therapy By Antibiotic')"> Export </button></strong></h5>
                                    <div id='Graph-chart39' style="min-width:250px; min-height: 320px;">
                                        <canvas id="canvas39"></canvas>
                                    </div>
                                </div> -->

                            </div>
                        </div>
                    </div>

                </div>

            </div>
            <!-- <div class="block-title">
                <h2><strong>Reports:</strong> Antibiotics by MD Steward</h2>
            </div>
            <div class="row">
                <div class="col-lg-12">
                    <div id='Graph-chart1'>
                        <canvas id="canvas1"></canvas>
                    </div>
                </div>
            </div> -->
            <!-- <div class="block-title">
                <h2><strong>Reports:</strong> Days Saved by Facility</h2>
            </div>
            <div class="row">
                <div class="col-lg-12">
                    <div style="width:50%;" id='Graph-chart2'>
                        <canvas id="canvas2"></canvas>
                    </div>
                </div>
            </div> -->
            <!-- <div class="block-title">
                <h2><strong>Reports:</strong> Actual v/s Initial DOT by Facility</h2>
            </div>
            <div class="row">
                <div class="col-lg-12">
                    <div style="width:50%;" id='Graph-chart4'>
                        <canvas id="canvas4"></canvas>
                    </div>
                </div>
            </div> -->

            <!-- <div class="block-title">
                <h2><strong>Reports:</strong> Actual v/s Initial DOT by MD Steward</h2>
            </div>
            <div class="row">
                <div class="col-lg-12">
                    <div style="width:50%;" id='Graph-chart5'>
                        <canvas id="canvas5"></canvas>
                    </div>
                </div>
            </div> -->
            <!-- <div class="block-title">
                <h2><strong>Reports:</strong> Actual v/s Initial DOT by Provider Doctor</h2>
            </div>
            <div class="row">
                <div class="col-lg-12">
                    <div style="width:50%;" id='Graph-chart6'>
                        <canvas id="canvas6"></canvas>
                    </div>
                </div>
            </div> -->
            <!-- remain -->

            <!-- <div class="block-title">
                <h2><strong>Reports:</strong> Dx % by Actual DOT</h2>
            </div>
            <div class="row">
                <div class="col-lg-12">
                    <div style="width:50%;" id='Graph-chart3'>
                        <canvas id="canvas3"></canvas>
                    </div>
                </div>
            </div> -->

            <!-- <div class="block-title">
                <h2><strong>Reports:</strong> Total agreement</h2>
            </div>
            <div class="row">
                <div class="col-lg-12">
                    <div style="width:50%;" id='Graph-chart7'>
                        <canvas id="canvas7"></canvas>
                    </div>
                </div>
            </div> -->
        </div>
    </div>
    <!-- END Page Content -->
<?php } ?>