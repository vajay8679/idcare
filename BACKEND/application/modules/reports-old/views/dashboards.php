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
                                    <div class="col-sm-6 col-lg-3">
                                        <div class="text-left">Total Patient Days: </div>
                                        <input type="text" name="total_patient_days" class="form-control" value="<?php echo (getConfig('total_patient_days') <= 0) ? $patients_days->tatal_days : getConfig('total_patient_days'); ?>"/>
                                    </div>
                                    <div class="col-sm-6 col-lg-3">
                                        <div class="text-left">Total Days on Antibiotic: </div>
                                        <input type="text" name="total_patient_days1" class="form-control" value="<?php echo $total_antibiotic_days; ?>" disabled=""/>
                                    </div>
                                    <div class="col-sm-6 col-lg-2">
                                        <div class="text-left">ABx Days Per 1000 Total Patient days:</div>
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
                        </div>
                    </div>


                    <div class="panel panel-default">
                        <div class="panel-body">

                            <div class="col-sm-12 col-lg-3">
                                <select id="careUnit" name="careUnit" class="form-control select-2" onchange="getAntibioticByCareUnit(this.value)">
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
                                </select>


                            </div>



                            <div class="col-sm-12 col-lg-3">
                                <select id="provider_doctor" name="provider_doctor" class="form-control select-2" onchange="getAntibioticByCareUnitProviderId()">
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


                            <div class="col-sm-12 col-lg-3">
                                <select id="steward" name="steward" class="form-control select-2" onchange="getAntibioticByCareUnitStewardPrice()">
                                    <option value="">Select MD Steward</option>
                                    <?php
                                    if (!empty($staward)) {
                                        foreach ($staward as $row) {
                                            ?>
                                            <option value="<?php echo $row->id; ?>"><?php echo $row->first_name . ' ' . $row->last_name; ?></option>
                                            <?php
                                        }
                                    }
                                    ?></select></div>
                            <div class="col-sm-12 col-lg-3">
                                <select id="RX" name="RX" class="form-control select-2" onchange="getAntibioticByCareUnit('', this.value);getAntibioticByCareUnitProviderId();">
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
                        </div>
                    </div>
                </div>
            </div>
            <!-- eShop Overview Title -->
            <div class="block-title">

            </div>
            <div class="row">

                <div class="col-lg-12">
                    <div class="panel panel-success">
                        <div class="panel-heading">
                            <h4 class="panel-title"
                                data-toggle="collapse"
                                data-target="#collapseOne">
                                Antibiotics by Provider MD 

                                <a href="#" data-toggle="tooltip" data-placement="bottom"
                                   title="" data-original-title="This section will represent the data of antibiotics days based on selected provider, once you select  any provider that provider will be compared with the average of rest providers."
                                   class="red-tooltip"><i class="fa fa-info-circle"></i></a>
                            </h4>
                        </div>


                        <div id="collapseOne" class="panel-collapse collapse">
                            <div class="panel-body" id='reportPage'>
                                <div class="col-lg-12 col-sm-12">
                                    <h5><strong>Antibiotics by Provider MD 
                                        <button type="button" onclick="downloadPDF2('canvas21','Antibiotics by Provider MD')"> Export </button>
                                    </strong> </h5>
                                    <div id='Graph-chart21' style="min-width:250px; min-height: 320px;">
                                        <canvas id="canvas21"></canvas>
                                    </div>
                                </div>
                                </br>

                                <div class="col-lg-6 col-sm-12">
                                    <h5><strong>Antibiotics by Provider MD Comparison<button type="button" onclick="downloadPDF2('canvas22','Antibiotics by Provider MD Comparison')"> Export </button></strong></h5>
                                    <div id='Graph-chart22' style="min-width:250px; min-height: 320px;">
                                        <canvas id="canvas22"></canvas>
                                    </div>
                                </div>

                                <div class="col-lg-6 col-sm-12">
                                    <h5><strong>REST <button type="button" onclick="downloadPDF2('canvas23','REST')"> Export </button></strong></h5>
                                    <div id='Graph-chart23' style="min-width:250px; min-height: 320px;">
                                        <canvas id="canvas23"></canvas>
                                    </div>
                                </div>


                            </div>
                        </div>
                    </div>
                    <div class="panel panel-success">

                        <div class="panel-heading">
                            <h4 class="panel-title"
                                data-toggle="collapse"
                                data-target="#collapseOne1">
                                Antibiotics Price By Provider
                                <a href="#" data-toggle="tooltip" data-placement="bottom"
                                   title="" data-original-title="This section will represent the cost of antibiotics pescribed based on selected Care Unit, Provider and MD Steward. "
                                   class="red-tooltip"><i class="fa fa-info-circle"></i></a>
                            </h4>
                        </div>
                        <div id="collapseOne1" class="panel-collapse collapse">
                            <div class="panel-body">
                                <div class="col-lg-6 col-sm-12">
                                    <h5><strong>Antibiotics Price By Provider<button type="button" onclick="downloadPDF2('canvas24','Antibiotics Price By Provider')"> Export </button></strong></h5>
                                    <div id='Graph-chart24' style="min-width:250px; min-height: 320px;">
                                        <canvas id="canvas24"></canvas>
                                    </div>
                                </div>

                                <div class="col-lg-6 col-sm-12">
                                    <h5><strong>Antibiotics Price By MD Steward<button type="button" onclick="downloadPDF2('canvas25','Antibiotics Price By MD Steward')"> Export </button></strong></h5>
                                    <div id='Graph-chart25' style="min-width:250px; min-height: 320px;">
                                        <canvas id="canvas25"></canvas>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="panel panel-success">

                        <div class="panel-heading">
                            <h4 class="panel-title"
                                data-toggle="collapse"
                                data-target="#collapseOne2">
                                Antibiotics Percentage by Facility and MD Steward
                                <a href="#" data-toggle="tooltip" data-placement="bottom"
                                   title="" data-original-title="This section will represent antibiotics percentage based on selected facility and steward"
                                   class="red-tooltip"><i class="fa fa-info-circle"></i></a>
                            </h4>
                        </div>
                        <div id="collapseOne2" class="panel-collapse collapse">
                            <div class="panel-body">
                                <div class="col-lg-6 col-sm-12">
                                    <h5><strong>Antibiotics Percentage By Facility<button type="button" onclick="downloadPDF2('canvas','Antibiotics Percentage By Facility')"> Export </button></strong></h5>
                                    <div id='Graph-chart' style="min-width:250px; min-height: 320px;">
                                        <canvas id="canvas"></canvas>
                                    </div>
                                </div>

                                <div class="col-lg-6 col-sm-12">
                                    <h5><strong>Antibiotics Percentage by MD Steward<button type="button" onclick="downloadPDF2('canvas1','Antibiotics Percentage by MD Steward')"> Export </button></strong></h5>
                                    <div id='Graph-chart1' style="min-width:250px; min-height: 320px;">
                                        <canvas id="canvas1"></canvas>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="panel panel-success">

                        <div class="panel-heading">
                            <h4 class="panel-title"
                                data-toggle="collapse"
                                data-target="#collapseOne3">
                                Days Saved by Facility 
                                <a href="#" data-toggle="tooltip" data-placement="bottom"
                                   title="" data-original-title="This section represents percentage of days saved by facility and comparison of actual dot and Initial DOT."
                                   class="red-tooltip"><i class="fa fa-info-circle"></i></a>
                            </h4>
                        </div>
                        <div id="collapseOne3" class="panel-collapse collapse">

                            <div class="panel-body">
                                <div class="col-lg-6 col-sm-12">
                                    <h5><strong>Days Saved by Facility in percentage<button type="button" onclick="downloadPDF2('canvas2','Days Saved by Facility in percentage')"> Export </button></strong></h5>
                                    <div id='Graph-chart2'>
                                        <canvas id="canvas2"></canvas>
                                    </div>
                                </div>

                                <div class="col-lg-6 col-sm-12">
                                    <h5><strong>Actual v/s Initial DOT by Facility (Actual Figures)<button type="button" onclick="downloadPDF2('canvas4','Actual vs Initial DOT by Facility')"> Export </button></strong></h5>
                                    <div id='Graph-chart4'>
                                        <canvas id="canvas4"></canvas>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="panel panel-success">

                        <div class="panel-heading">
                            <h4 class="panel-title"
                                data-toggle="collapse"
                                data-target="#collapseOne4">
                                Actual v/s Initial DOT by MD Steward and Provider MD
                                <a href="#" data-toggle="tooltip" data-placement="bottom"
                                   title="" data-original-title="
This section represents the comparison of initial DOT and Actual DOT based on MD Steward and Provider MD."
                                   class="red-tooltip"><i class="fa fa-info-circle"></i></a>
                            </h4>
                        </div>
                        <div id="collapseOne4" class="panel-collapse collapse">

                            <div class="panel-body">
                                <div class="col-lg-6 col-sm-12">
                                    <h5><strong> Actual v/s Initial DOT by MD Steward<button type="button" onclick="downloadPDF2('canvas5','Actual vs Initial DOT by MD Steward')"> Export </button></strong></h5>
                                    <div id='Graph-chart5' style="min-width:250px; min-height: 320px;">
                                        <canvas id="canvas5"></canvas>
                                    </div>
                                </div>

                                <div class="col-lg-6 col-sm-12">
                                    <h5><strong> Actual v/s Initial DOT by Provider MD<button type="button" onclick="downloadPDF2('canvas6','Actual vs Initial DOT by Provider MD')"> Export </button></strong></h5>
                                    <div id='Graph-chart6' style="min-width:250px; min-height: 320px;">
                                        <canvas id="canvas6"></canvas>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="panel panel-success">

                        <div class="panel-heading">
                            <h4 class="panel-title"
                                data-toggle="collapse"
                                data-target="#collapseOne5">
                                Rx and Dx percentage by days
                                <a href="#" data-toggle="tooltip" data-placement="bottom"
                                   title="" data-original-title="This section represents the percentage of Rx and Dx by Days."
                                   class="red-tooltip"><i class="fa fa-info-circle"></i></a>
                            </h4>
                        </div>
                        <div id="collapseOne5" class="panel-collapse collapse">

                            <div class="panel-body">
                                <div class="col-lg-6 col-sm-12">
                                    <h5><strong> Rx % by Days<button type="button" onclick="downloadPDF2('canvas31','Rx percentage by Days')"> Export </button></strong></h5>
                                    <div id='Graph-chart31' style="min-width:250px; min-height: 320px;">
                                        <canvas id="canvas31"></canvas>
                                    </div>
                                </div>

                                <div class="col-lg-6 col-sm-12">
                                    <h5><strong> Dx % by Days<button type="button" onclick="downloadPDF2('canvas32','Dx percentage by Days')"> Export </button></strong></h5>
                                    <div id='Graph-chart32' style="min-width:250px; min-height: 320px;">
                                        <canvas id="canvas32"></canvas>
                                    </div>
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