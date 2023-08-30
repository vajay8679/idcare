<?php if ($this->ion_auth->is_admin() || $this->ion_auth->is_subAdmin() || $this->ion_auth->is_facilityManager()) { ?>               
</style>
    <!-- Page content -->
    <div id="page-content">
        <!--        <div id="msg"></div>-->
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
                        <div class="panel panel-default">
                            <div class="panel-body">
                                <form action="<?php echo site_url('setting/add_days'); ?>" name="patientForm" method="post">
                                    <div class="col-sm-6 col-lg-2">
                                        <div class="text-left">Total Patient Days:</div>
                                    </div>
                                    <div class="col-sm-6 col-lg-4">
                                        <input type="text" name="total_patient_days" class="form-control" value="<?php echo getConfig('total_patient_days'); ?>"/>
                                    </div>
                                    <div class="col-sm-6 col-lg-2">
                                        <input type="submit" class="btn btn-primary btn-sm" value="Submit"/>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>   
<!--                    <div class="panel panel-default">
                        <div class="panel-body">                               
                            <div class="col-sm-6 col-lg-6">
                                <select id="steward" name="steward" class="form-control select-2" onchange="getAntibioticByCareUnitSteward()">
                                    <option value="">Select MD Steward</option>
                                    <?php
                                    if (isset($staward) && !empty($careUnit)) {
                                        foreach ($staward as $row) {
                                            $select = "";
                                            if (isset($careUnitID)) {
                                                if ($careUnitID == $row->id) {
                                                    $select = "selected";
                                                }
                                            }
                                            ?>
                                            <option value="<?php echo $row->id; ?>" <?php echo $select; ?>><?php echo $row->first_name . ' ' . $row->last_name; ?></option>
                                            <?php
                                        }
                                    }
                                    ?>
                                </select>
                            </div> 
                            <div class="col-sm-6 col-lg-6">
                                <select id="provider_doctor" name="provider_doctor" class="form-control select-2" onchange="actual_dot_vs_new_dot_by_care_unit_provider_doctor()">
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

                        </div>
                    </div>-->

                    <div class="panel panel-default">
                        <div class="panel-body">    
                            
                                <div class="col-sm-12 col-lg-12">
                                    <?php
                                    if (isset($careUnit) && !empty($careUnit)) {
                                        foreach ($careUnit as $row) {
                                            ?>
                                            <button id="careUnit" value="<?php echo $row->id; ?>" name="careUnit" type='button' class="btn btn-default" onclick="getAntibioticByCareUnit('<?php echo $row->id; ?>')"><?php echo $row->name; ?></button>
                                            <?php
                                        }
                                    }
                                    ?>
        <!--                                    <select id="careUnit" name="careUnit" class="form-control select-2" onchange="getAntibioticByCareUnit()">
                    <option value="">Select Care Unit</option>
                                    <?php
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
                                    ?>
                </select>-->
                                

                                <!--                                                                <div class="col-sm-1 col-lg-1">
                                                                                                    <div class="text-left">Filter:</div>
                                                                                                </div>-->



                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- eShop Overview Title -->
            <div class="block-title">

            </div>
            <div class="row">

                <div class="col-lg-12">
                    <div class="panel panel-default">
                        <div class="panel-body"> 
                            <div class="col-lg-6 col-sm-12">
                                <h5><strong>Antibiotics by Facility</strong></h5>
                                <div id='Graph-chart' style="min-width:250px; min-height: 200px;">
                                    <canvas id="canvas"></canvas>
                                </div>
                            </div>

                            <div class="col-lg-6 col-sm-12">
                                <h5><strong>Antibiotics by MD Steward</strong></h5>
                                <div id='Graph-chart1' style="min-width:250px; min-height: 250px;">
                                    <canvas id="canvas1"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="panel panel-default">
                        <div class="panel-body"> 
                            <div class="col-lg-6 col-sm-12">
                                <h5><strong>Days Saved by Facility</strong></h5>
                                <div id='Graph-chart2'>
                                    <canvas id="canvas2"></canvas>
                                </div>
                            </div>

                            <div class="col-lg-6 col-sm-12">
                                <h5><strong>Actual Days of Therapy v/s Initial Days of Therapy by Facility</strong></h5>
                                <div id='Graph-chart4'>
                                    <canvas id="canvas4"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="panel panel-default">
                        <div class="panel-body"> 
                            <div class="col-lg-6 col-sm-12">
                                <h5><strong> Actual Days of Therapy v/s Initial Days of Therapy by MD Steward</strong></h5>
                                <div id='Graph-chart5' style="min-width:250px; min-height: 200px;">
                                    <canvas id="canvas5"></canvas>
                                </div>
                            </div>

                            <div class="col-lg-6 col-sm-12">
                                <h5><strong> Actual Days of Therapy v/s Initial Days of Therapy  by Provider MD</strong></h5>
                                <div id='Graph-chart6' style="min-width:250px; min-height: 200px;">
                                    <canvas id="canvas6"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="panel panel-default">
                        <div class="panel-body"> 
                            <div class="col-lg-6 col-sm-12">
                                <h5><strong> Rx % by Actual Days of Therapy</strong></h5>
                                <div id='Graph-chart31' style="min-width:250px; min-height: 200px;">
                                    <canvas id="canvas31"></canvas>
                                </div>
                            </div>

                            <div class="col-lg-6 col-sm-12">
                                <h5><strong> Dx % by Actual Days of Therapy</strong></h5>
                                <div id='Graph-chart32' style="min-width:250px; min-height: 200px;">
                                    <canvas id="canvas32"></canvas>
                                </div>
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