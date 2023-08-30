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
<!--     <div class="block full">
        <div class="row text-center">

            <div class="col-sm-6 col-lg-12">
                <div class="panel panel-default">
                    <div class="panel-body">
                        <div id="msg"></div>
                        <?php
                        $message = $this->session->flashdata('success');
                        if (!empty($message)):
                            ?><div class="alert alert-success">
                                <?php echo $message; ?></div><?php endif; ?>
                        <?php
                        $error = $this->session->flashdata('error');
                        if (!empty($error)):
                            ?><div class="alert alert-danger">
                                <?php echo $error; ?></div><?php endif; ?>
                        <form action="<?php echo site_url('patient/patientImport'); ?>" name="patientFormExport" method="post" enctype="multipart/form-data">
                            <div class="col-sm-6 col-lg-2">
                                <div class="text-left">Upload File:</div>
                            </div>
                            <div class="col-sm-6 col-lg-10">
                                <div class="text-left text-danger">Note: First, select care unit to upload the file</div>
                            </div>
                            <div class="col-sm-6 col-lg-4">
                                <select id="care_unit1" name="careUnit" class="form-control select-2" onchange="getPatient()">
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
                            <div class="col-sm-6 col-lg-4 hidetext">
                                <input type="file" name="patientFile" class="form-control" accept=".csv"/>
                            </div>
                            <div class="col-sm-6 col-lg-1 hidetext">
                                <button type="submit" class="btn btn-info btn-sm" value="Import"><fa class="fa fa-file-pdf-o"></fa> Import</button>
                            </div>
                            <div id="labelError"></div>
                        </form>
                    </div>
                </div></div>
            <div class="col-sm-6 col-lg-12">
                <div class="panel panel-default">
                    <div class="panel-body">
                        <form action="<?php echo site_url('patient'); ?>" name="patientForm" method="get">
                            <div class="col-sm-6 col-lg-2">
                                <div class="text-left">Download File:</div>
                            </div>
                            <div class="col-sm-6 col-lg-10">
                                <div class="text-left text-danger">Note: select care unit to download specific care unit's file or , overall file will be downloaded</div>
                            </div>
                            <div class="col-sm-6 col-lg-4">
                                <select id="care_unit" name="careUnit" class="form-control select-2" onchange="getPatient()">
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
                            <div class="col-sm-6 col-lg-2">
                                <input type="submit" class="btn btn-primary btn-sm" value="Search"/>
                            </div>
                        </form>
                        <?php
                        if (isset($careUnitID)) {
                            $careUnitID = (!empty($careUnitID)) ? $careUnitID : '';
                        }
                        ?>
                        <form action="<?php echo site_url('patient/patientExport'); ?>" name="patientFormExport" method="get">
                            <div class="col-sm-12 col-lg-6">
                                <input type="hidden" name="careUnitID" id="careUnitID" value="<?php echo $careUnitID; ?>" />
                                <div class="col-sm-12 col-lg-8">
                                <input type="text" class="form-control" name="date" id="date" />
                                </div>
                                <div class="col-sm-12 col-lg-4">
                                <button type="submit" class="btn btn-success btn-sm"><fa class="fa fa-file-pdf-o"></fa> Export</button>
                                </div>
                            </div>
                        </form>
                    </div></div>
            </div>
        </div>
    </div> -->
    <!-- Datatables Content -->
    <div class="block full">
<!--         <div class="block-title">
            <h2><strong><?php echo $title; ?></strong> Panel</h2>

            <h2><a href="javascript:void(0)"  onclick="open_modal('<?php echo $model; ?>')" class="btn btn-sm btn-primary">
                    <i class="gi gi-circle_plus"></i> <?php echo $title; ?>
                </a></h2>

        </div> -->

        <div class="table-responsive">
            <table id="common_datatable_menucat" class="table table-vcenter table-condensed table-bordered">
                <thead>
                    <tr>
                        <th style="width:10px">Sr. No</th>
                        <th >Date Of Start ABX</th>
                        <th >Patient ID</th>
                        <th>Care Unit</th>
                        <th>Provider MD</th>
                        <th >Diagnosis</th>
                        <th >Room Number</th>
                        <th >Infection Onset</th>
                        <th >Total Days</th>
                        <th >Culture Source</th>
                        <th >Organism</th>
                        <th >Antibiotic Name</th>
                        <th>MD Steward</th>
                        <th><?php echo lang('action'); ?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if (isset($list) && !empty($list)):
                        $rowCount = 0;
                        foreach ($list as $rows):
                            $rowCount++;
                            ?>
                            <tr>
                                <td><?php echo $rowCount; ?></td> 
                                <td><?php echo date('m/d/Y',strtotime($rows->date_of_start_abx)); ?></td>           
                                <td><?php echo $rows->pid; ?></td>
                                <td><?php echo $rows->care_unit_name; ?></td>
                                <td><?php echo $rows->doctor_name; ?></td>
                                <td><?php echo $rows->initial_dx_name; ?></td> 
                                <td><?php echo $rows->room_number; ?></td>   
                                <?php  if($rows->symptom_onset == 'Facility')  { ?>
                                            <td><?php echo 'Facility/HAI'; ?></td> 
                                            <?php } else if ($rows->symptom_onset == 'Hospital'){ ?>
                                            <td><?php echo 'Hospital/CAI'; ?></td>  
                                            <?php }else  { ?> 
                                            <td><?php echo 'NULL'; ?></td>  
                                            <?php } ?>  

                                            <td><?php echo $rows->initial_dot; ?></td>  

                                            <?php  if(!empty($rows->culture_source_name))  { ?>
                                                <td><?php echo $rows->culture_source_name; ?></td>
                                            <?php }else  { ?> 
                                            <td><?php echo 'NULL'; ?></td>  
                                            <?php } ?>    
                                            
                                            <?php  if(!empty($rows->organism_name))  { ?>
                                                <td><?php echo $rows->organism_name; ?></td>
                                            <?php }else  { ?> 
                                            <td><?php echo 'NULL'; ?></td>  
                                            <?php } ?>
                                <td><?php echo $rows->initial_rx_name; ?></td>
                                <td><?php echo ucfirst($rows->md_stayward); ?></td>
                                <td class="actions">
                                    <a href="javascript:void(0)" class="btn btn-default" onclick="editFn('patient', 'edit_patient', '<?php echo encoding($rows->patient_id) ?>', 'patient');"><i class="fa fa-pencil"></i></a>
                                    <a href="<?php echo base_url() . 'patient/edit?id=' . encoding($rows->patient_id); ?>" data-toggle="tooltip" class="btn btn-default"><i class="fa fa-eye"></i></a>
        <!--                                    <a href="<?php echo base_url() . 'patient/edit_parient?id=' . encoding($rows->patient_id); ?>" data-toggle="tooltip" class="btn btn-default" target="_blank"><i class="fa fa-pencil"></i></a>-->
                                    <a href="javascript:void(0)" onclick="deletePatient('<?php echo $rows->patient_id; ?>')" class="btn btn-xs btn-danger"><i class="fa fa-trash"></i></a>
<!--                                       <a href="<?php echo base_url() . 'patient/existing_list/' . $rows->pid; ?>" target='_blank' data-toggle="tooltip" class="btn btn-default">View List</a> -->
                                </td>
                            </tr>
                            <?php
                        endforeach;
                    endif;
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
