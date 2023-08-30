<?php if ($this->ion_auth->is_admin() || $this->ion_auth->is_subAdmin() || $this->ion_auth->is_facilityManager()) { ?>               

    <!-- Page content -->
    <div id="page-content">
<!--        <div id="msg"></div>-->
        <!-- eShop Overview Block -->
        <div class="block full">
            <!-- eShop Overview Title -->
            <div class="block-title">
                <h2><strong>Dashboard</strong> Overview</h2>
            </div>
            <!-- END eShop Overview Title -->
            <!-- eShop Overview Content -->
    <div class="row">
        <div class="col-lg-12">
            <div class="wrapper wrapper-content">
                <div class="row">
                    <div class="col-lg-4">
                        <div class="ibox float-e-margins">
                            <div class="ibox-title">
                                <!-- <div class="stat-percent font-bold text-primary"> <i class="fa fa-plus"></i></div> -->
                                <?php if($this->ion_auth->is_admin()){?>
                                    <!-- <h5 class="text-primary"><strong>Patient</strong></h5> -->
                                <?php }else if($this->ion_auth->is_subAdmin()){ ?>

                                <h5 >Patient</h5>
                              
                                <?php }else if($this->ion_auth->is_facilityManager()){ ?>

                               <!--  <h5 >Patient</h5> -->
                                <?php }?>
                            </div>
                            <div class="ibox-content">
                                <h1 class="no-margins">
                                
                                  <?php echo $total_patient;?>
                                </h1>
                                <h5 class="text-primary"><strong>Total Patient</strong></h5>
                            </div>
                        </div>
                    </div>
                                
                    <div class="col-lg-4">
                        <div class="ibox float-e-margins">
                            <div class="ibox-title">
                                <!-- <div class="stat-percent font-bold text-primary"> <i class="fa fa-plus"></i></div> -->
                                <!-- <h5 class="text-primary"><strong>MD Steward</strong></h5> -->
                            </div>
                            <div class="ibox-content">
                                <h1 class="no-margins"> <?php echo $total_md_steward;?></h1>
                                <!-- <small>Total MD Steward</small> -->
                                <h5 class="text-primary"><strong>Total MD Steward</strong></h5>
                            </div>
                        </div>
                    </div>
                    
                     <div class="col-lg-4">
                        <div class="ibox float-e-margins">
                            <div class="ibox-title">
                                <!-- <div class="stat-percent font-bold text-primary"> <i class="fa fa-plus"></i></div> -->
                                <!-- <h5 class="text-primary"><strong>Provider Doctor</strong></h5> -->
                            </div>
                            <div class="ibox-content">
                                <h1 class="no-margins"><?php echo $doctors;?></h1>
                                <!-- <small>Total Provider Doctor</small> -->
                                <h5 class="text-primary"><strong>Total Provider MD</strong></h5>
                            </div>
                        </div>
                    </div>
                     <div class="col-lg-4">
                        <div class="ibox float-e-margins">
                            <div class="ibox-title">
                                <!-- <div class="stat-percent font-bold text-primary"> <i class="fa fa-plus"></i></div> -->
                                <!-- <h5 class="text-primary"><strong>Care Unit</strong></h5> -->
                            </div>
                            <div class="ibox-content">
                                <h1 class="no-margins"><?php echo $careUnit;?></h1>
                                <h5 class="text-primary"><strong>Total Care Unit</strong></h5>
                            </div>
                        </div>
                    </div>
                    
                     <div class="col-lg-4">
                        <div class="ibox float-e-margins">
                            <div class="ibox-title">
                                <!-- <div class="stat-percent font-bold text-primary"> <i class="fa fa-plus"></i></div> -->
                                <!-- <h5 class="text-primary"><strong>Infections</strong></h5> -->
                            </div>
                            <div class="ibox-content">
                                <h1 class="no-margins"><?php echo $initial_dx;?></h1>
                                <!-- <small>Total Infections</small> -->
                                <h5 class="text-primary"><strong>Total Infections</strong></h5>
                            </div>
                        </div>
                    </div>
                    
                     <div class="col-lg-4">
                        <div class="ibox float-e-margins">
                            <div class="ibox-title">
                                <!-- <div class="stat-percent font-bold text-primary"> <i class="fa fa-plus"></i></div> -->
                                <!-- <h5 class="text-primary"><strong>Total Antibiotic</strong></h5> -->
                            </div>
                            <div class="ibox-content">
                                <h1 class="no-margins"><?php echo $initial_rx;?></h1>
                                <!-- <small>Total Antibiotic</small> -->
                                <h5 class="text-primary"><strong>Total Antibiotic</strong></h5>
                            </div>
                        </div>
                    </div>
                    
                     <div class="col-lg-4">
                        <div class="ibox float-e-margins">
                            <div class="ibox-title">
                                <!-- <div class="stat-percent font-bold text-primary"> <i class="fa fa-plus"></i></div> -->
                                <!-- <h5 class="text-primary"><strong>Patient Today</strong></h5> -->
                            </div>
                            <div class="ibox-content">
                                <h1 class="no-margins"><?php echo $total_patient_today; ?></h1>
                                <!-- <small>Total Patient Today</small> -->
                                <h5 class="text-primary"><strong>Total Patient Today</strong></h5>
                            </div>
                        </div>
                    </div>
                   

                </div>
            </div>
        </div>
    </div>
            <!-- END eShop Overview Content -->
        </div>
        <!-- END eShop Overview Block -->

    </div>
    <!-- END Page Content -->



<?php } ?>