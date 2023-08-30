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
    <!-- Datatables Content -->
   
    <div class="block full">
        <div class="block-title">

        <?php foreach($careUnit_user_id as $value){

            $user_idddd = $value->facility_user_id;
        }

        //print_r($user_idddd);die;
        ?>
            <h2><strong><?php echo $title; ?></strong> Panel</h2>
            <?php if ($this->ion_auth->is_admin()) { ?>
                <h2><a href="javascript:void(0)"  onclick="open_modal('<?php echo $model; ?>')" class="btn btn-sm btn-primary">
                        <i class="gi gi-circle_plus"></i> <?php echo $title; ?>
                    </a></h2>
            <?php }else if(($this->ion_auth->is_facilityManager()) &&  $user_idddd =='') { ?>
               
                <h2><a href="javascript:void(0)" onclick="open_modal('<?php echo $model; ?>')" class="btn btn-sm btn-primary">
                        <i class="gi gi-circle_plus"></i> <?php echo $title; ?>
              </a></h2>

                <?php }else if(($this->ion_auth->is_facilityManager()) && $user_idddd!=''){ ?>
                <!-- <h2><a href="javascript:void(0)"  onclick="open_modal('<?php echo $model; ?>')" class="btn btn-sm btn-primary">
                        <i class="gi gi-circle_plus"></i><?php echo $title; ?>
              </a></h2> -->
<?php } ?>
        </div>
        <div class="table-responsive">
            <table id="common_datatable_menucat" class="table table-vcenter table-condensed table-bordered">
                <thead>
                    <tr>
                        <th style="width: 10px;">Sr. No</th>
                        <th>Care Unit Code</th>
                        <th>Name</th>
                        <th>Email</th>
                      <?php if($this->ion_auth->is_admin()){?>
                        <th><?php echo lang('action'); ?></th>
                        <?php } ?>
                    </tr>
                </thead>
                <tbody>
                    <?php
                   // print_r($careUnit);die;
                    if (!empty($careUnit)){

                        $rowCount = 0;
                        foreach ($careUnit as $rows){
                            //print_r($rows);die;
                            $rowCount++;
                            ?>
                            <tr>
                                <td><?php echo $rowCount; ?></td>
                                <td><?php echo $rows->care_unit_code; ?></td>           
                                <td><?php echo $rows->name; ?></td>
                                <td><?php echo $rows->email; ?></td>
                               <!--  <td class="actions">
                                    <a href="javascript:void(0)" class="btn btn-xs btn-default" onclick="editFn('<?php echo $model; ?>', 'edit', '<?php echo encoding($rows->id) ?>', '<?php echo $model; ?>');"><i class="fa fa-pencil"></i></a>
                                    <?php if ($rows->is_active == 1) { ?>
                                        <a href="javascript:void(0)" class="btn btn-xs btn-success" onclick="editStatusFn('<?php echo $tablePrefix; ?>', 'id', '<?php echo encoding($rows->id); ?>', '<?php echo $rows->is_active; ?>','<?php echo $rows->name; ?>')" title="Inactive Now"><i class="fa fa-check"></i></a>
                                    <?php } else { ?>
                                        <a href="javascript:void(0)" class="btn btn-xs btn-danger" onclick="editStatusFn('<?php echo $tablePrefix; ?>', 'id', '<?php echo encoding($rows->id); ?>', '<?php echo $rows->is_active; ?>','<?php echo $rows->name; ?>')" title="Active Now"><i class="fa fa-times"></i></a>
                                    <?php } ?>
                                    <a href="javascript:void(0)" onclick="deleteFn('<?php echo $table; ?>', 'id', '<?php echo encoding($rows->id); ?>', '<?php echo $model; ?>','','<?php echo $rows->name; ?>')" class="btn btn-xs btn-danger"><i class="fa fa-trash"></i></a>
                                </td> -->
                            </tr>
                            <?php
                       }
                    }else if($this->ion_auth->is_facilityManager()){
                        $rowCount = 0;
                        foreach ($careUnit as $rows){
                            //print_r($rows);die;
                            $rowCount++;
                            ?>
                            <tr>
                                <td><?php echo $rowCount; ?></td>
                                <td><?php echo $rows->care_unit_code; ?></td>           
                                <td><?php echo $rows->name; ?></td>
                                <td><?php echo $rows->email; ?></td>
                               <!--  <td class="actions">
                                    <a href="javascript:void(0)" class="btn btn-xs btn-default" onclick="editFn('<?php echo $model; ?>', 'edit', '<?php echo encoding($rows->id) ?>', '<?php echo $model; ?>');"><i class="fa fa-pencil"></i></a>
                                    <?php if ($rows->is_active == 1) { ?>
                                        <a href="javascript:void(0)" class="btn btn-xs btn-success" onclick="editStatusFn('<?php echo $tablePrefix; ?>', 'id', '<?php echo encoding($rows->id); ?>', '<?php echo $rows->is_active; ?>','<?php echo $rows->name; ?>')" title="Inactive Now"><i class="fa fa-check"></i></a>
                                    <?php } else { ?>
                                        <a href="javascript:void(0)" class="btn btn-xs btn-danger" onclick="editStatusFn('<?php echo $tablePrefix; ?>', 'id', '<?php echo encoding($rows->id); ?>', '<?php echo $rows->is_active; ?>','<?php echo $rows->name; ?>')" title="Active Now"><i class="fa fa-times"></i></a>
                                    <?php } ?>
                                    <a href="javascript:void(0)" onclick="deleteFn('<?php echo $table; ?>', 'id', '<?php echo encoding($rows->id); ?>', '<?php echo $model; ?>','','<?php echo $rows->name; ?>')" class="btn btn-xs btn-danger"><i class="fa fa-trash"></i></a>
                                </td> -->
                            </tr>
                            <?php
                       }

                    }else{ 
                        $rowCount = 0;
                        foreach ($list as $rows){
                            $rowCount++;
                            
                            ?>
<tr>
                                <td><?php echo $rowCount; ?></td>
                                <td><?php echo $rows->care_unit_code; ?></td>           
                                <td><?php echo $rows->name; ?></td>
                                <td><?php echo $rows->email; ?></td>
                                <td class="actions">
                                    <a href="javascript:void(0)" class="btn btn-xs btn-default" onclick="editFn('<?php echo $model; ?>', 'edit', '<?php echo encoding($rows->id) ?>', '<?php echo $model; ?>');"><i class="fa fa-pencil"></i></a>
                                    <?php if ($rows->is_active == 1) { ?>
                                        <a href="javascript:void(0)" class="btn btn-xs btn-success" onclick="editStatusFn('<?php echo $tablePrefix; ?>', 'id', '<?php echo encoding($rows->id); ?>', '<?php echo $rows->is_active; ?>','<?php echo $rows->name; ?>')" title="Inactive Now"><i class="fa fa-check"></i></a>
                                    <?php } else { ?>
                                        <a href="javascript:void(0)" class="btn btn-xs btn-danger" onclick="editStatusFn('<?php echo $tablePrefix; ?>', 'id', '<?php echo encoding($rows->id); ?>', '<?php echo $rows->is_active; ?>','<?php echo $rows->name; ?>')" title="Active Now"><i class="fa fa-times"></i></a>
                                    <?php } ?>
                                    <a href="javascript:void(0)" onclick="deleteFn('<?php echo $table; ?>', 'id', '<?php echo encoding($rows->id); ?>', '<?php echo $model; ?>','','<?php echo $rows->name; ?>')" class="btn btn-xs btn-danger"><i class="fa fa-trash"></i></a>
                                </td>
                            </tr>
                  <?php   } }
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
