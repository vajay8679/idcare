<!-- Page content -->
<div id="page-content">
    <ul class="breadcrumb breadcrumb-top">
        <li>
            <a href="<?php echo site_url('pwfpanel'); ?>">Home</a>
        </li>
        <li>
            <a href="<?php echo site_url($parent); ?>"><?php echo $title; ?></a>
        </li>
    </ul>

    <?php if($this->ion_auth->is_admin() OR $this->ion_auth->is_subAdmin() OR $this->ion_auth->is_facilityManager()){?>
    <div class="block full">
        <div class="row text-center">
               
            <div class="col-sm-6 col-lg-12">          
                <div class="panel panel-default">
                    <div class="panel-body">                   
                        <form action="<?php echo site_url('userReward'); ?>" name="patientForm" method="get">
                           <!--  <div class="col-sm-6 col-lg-2">
                                <div class="text-left">Download File:</div>
                            </div> -->
                            <div class="col-sm-6 col-lg-10">
                                <div class="text-left text-danger">Note: select date to check login details of users</div>
                            </div>
                           
                            <div class="col-sm-12 col-lg-5">
                                <input type="text" class="form-control" name="date" id="date" placeholder="from date" />
                                </div>
                                <div class="col-sm-12 col-lg-5">
                                <input type="text" class="form-control" name="date1" id="date1" placeholder="to date"/>
                                </div>
                            <div class="col-sm-12 col-lg-1">
                                <input type="submit" name="search" class="btn btn-primary btn-sm" value="Search"/>
                            </div>
                            <!-- <div class="col-sm-12 col-lg-1">
                                <button type="submit" value="Export" name="export" class="btn btn-success btn-sm"><fa class="fa fa-file-pdf-o"></fa> Export</button>
                                </div> -->
                        </form>
                        <form action="<?php echo site_url('userReward'); ?>" name="patientFormExport" method="get">
                        <div class="col-sm-12 col-lg-1">
                                <button type="submit" class="btn btn-primary btn-sm"><fa class="fa fa-undo"></fa> Reset</button>
                                </div>
                        </form>

                        <?php
                        if (isset($careUnitID)) {
                            $careUnitID = (!empty($careUnitID)) ? $careUnitID : '';
                        }
                        ?>
                        <!-- <form action="<?php echo site_url('patient/patientExport'); ?>" name="patientFormExport" method="get">
                            <div class="col-sm-12 col-lg-6">
                                <input type="hidden" name="careUnitID" id="careUnitID" value="<?php echo $careUnitID; ?>" />
                                <div class="col-sm-12 col-lg-4">
                                <input type="text" class="form-control" name="date" id="date" placeholder="from date" />
                                </div>
                                <div class="col-sm-12 col-lg-4">
                                <input type="text" class="form-control" name="date1" id="date1" placeholder="to date"/>
                                </div>
                                <div class="col-sm-12 col-lg-4">
                                <button type="submit" class="btn btn-success btn-sm"><fa class="fa fa-file-pdf-o"></fa> Export</button>
                                </div>
                            </div>
                        </form> -->
                    </div></div>                  
            </div>           
        </div>
    </div>
    <?php } ?>
    <!-- Datatables Content -->
    <div class="block full">
        <div class="block-title">
            <h2><strong><?php echo $title; ?></strong> Panel</h2>

          <!--   <h2><a href="javascript:void(0)"  onclick="open_modal('<?php echo $model; ?>')" class="btn btn-sm btn-primary">
                    <i class="gi gi-circle_plus"></i> <?php echo $title; ?>
                </a></h2> -->

        </div>

        <div class="table-responsive">
            <table id="common_datatable_menucat" class="table table-vcenter table-condensed table-bordered">
                <thead>
                    <tr>
                        <th style="width:60px">Sr. No</th>
                        
                        <th style="width:350px">User Name</th>
                        <th style="width:350px">Email</th>
                       <!--  <th>Provider MD</th> -->
                        <th  style="display:none">MD Steward</th>
                        <th >Login Date</th>
                        <th style="display:none"><?php echo lang('action'); ?></th>
                    </tr>
                </thead>
                <tbody>


                <?php
                                 //   if(!empty($careUnitsUser_list)){

                                    
                                        if (isset($list) && !empty($list)){
                                    $rowCount = 0;
                                    foreach ($list as $rows):
                                        $rowCount++;
                                        ?>
                                        <tr>
                                            <td><?php echo $rowCount; ?></td> 
                                            <td><?php echo $rows->first_name . ' ' . $rows->last_name; ?></td>
                                            <td><?php echo $rows->email ?></td>
                                            <!-- <td><?php echo $rows->doctor_name; ?></td> -->
                                            <td  style="display:none"><?php echo ucfirst($rows->md_stayward); ?></td>
                                            <td><?php echo  date('m/d/Y', $rows->last_login); ?></td>           

                                            <td class="actions" style="display:none">
                                                <a href="javascript:void(0)" class="btn btn-default" onclick="editFn('patient', 'edit_patient', '<?php echo encoding($rows->patient_id) ?>', 'patient');"><i class="fa fa-pencil"></i></a>
                                <!--                 <a href="<?php echo base_url() . 'patient/edit?id=' . encoding($rows->patient_id); ?>" data-toggle="tooltip" class="btn btn-default"><i class="fa fa-eye"></i></a> -->
                    <!--                                    <a href="<?php echo base_url() . 'patient/edit_parient?id=' . encoding($rows->patient_id); ?>" data-toggle="tooltip" class="btn btn-default" target="_blank"><i class="fa fa-pencil"></i></a>-->
                     <a href="<?php echo base_url() . 'patient/existing_list/' . $rows->pid; ?>" target='_blank' data-toggle="tooltip" class="btn btn-default">View History</a>
                                                <a href="javascript:void(0)" onclick="deletePatient('<?php echo $rows->patient_id; ?>')" class="btn btn-xs btn-danger"><i class="fa fa-trash"></i></a>
                                                 
                                            </td>
                                        </tr>
                                        <?php
                                    endforeach;
                                    }
                                    ?>

                </tbody>
            </table>
        </div>
    </div>
    <!-- END Datatables Content -->
</div>
<!-- END Page Content -->
<div id="form-modal-box"></div>
</div>
