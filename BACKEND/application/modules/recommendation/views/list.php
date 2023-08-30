<!-- Page content -->
<div id="page-content">
    <!-- Datatables Header -->
    <ul class="breadcrumb breadcrumb-top">
        <li>
            <a href="<?php echo site_url('pwfpanel'); ?>">Home</a>
        </li>
        <li>
            <a href="<?php echo site_url($model); ?>"><?php echo $title; ?></a>
        </li>
    </ul>
    <!-- END Datatables Header -->

    <!-- Quick Stats -->
    <div class="block_list full">

        <!--        <div class="row text-center">
                    <div class="col-sm-6 col-lg-3">
                        <a href="<?php echo base_url() ?>vendors/index/No" class="widget widget-hover-effect2">
                            <div class="widget-extra themed-background">
                                <h4 class="widget-content-light"><strong> Inactivate </strong> Vendors</h4>
                            </div>
                            <div class="widget-extra-full">
                                <span class="h2 animation-expandOpen"><?php echo $inactive_vendors; ?></span></div>
                        </a>
                    </div>
                    <div class="col-sm-6 col-lg-3">
                        <a href="<?php echo base_url() ?>vendors/index/Yes" class="widget widget-hover-effect2">
                            <div class="widget-extra themed-background-dark">
                                <h4 class="widget-content-light"><strong> Activated </strong> Vendors</h4>
                            </div>
                            <div class="widget-extra-full"><span class="h2 themed-color-dark animation-expandOpen"><?php echo $active_vendors; ?></span></div>
                        </a>
                    </div>
                    <div class="col-sm-6 col-lg-3">
        
                    </div>
                    <div class="col-sm-6 col-lg-3">
        
                    </div>
                </div>-->

    </div>
    <!-- END Quick Stats -->

    <!-- Datatables Content -->
    <div class="block full">
        <div class="block-title">
            <h2><strong><?php echo $title; ?></strong> Panel</h2>
            <?php if ($this->ion_auth->is_admin()) { ?>
            <h2>
                <a href="<?php echo base_url() . $this->router->fetch_class(); ?>/open_model"
                    class="btn btn-sm btn-primary">
                    <i class="gi gi-circle_plus"></i> <?php echo $title; ?>
                </a>
            </h2>
            <?php } ?>
        </div>
        <?php
        $LoginID = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : '';
        ?>

        <div class="table-responsive">
            <table id="common_datatable_users" class="table table-vcenter table-condensed table-bordered">
                <thead>
                    <tr>
                        <th class="text-center" style="width: 10px;">Sr. No</th>
                        <th class="text-center" style="width: 60px;">Date</th>
                        <!--                                <th><?php echo "Referral Code"; ?></th>-->
                        <?php if ($this->ion_auth->is_admin()) { ?>
                        <th class="text-center" style="width: 150px;"><?php echo "Facility Manager Name"; ?></th>
                        <?php } ?>
                        <th class="text-center" style="width: 200px;"><?php echo "Title"; ?></th>
                        <th class="text-center" style="width: 400px;"><?php echo "Description";
                        ; ?></th>
                        
                         <th class="text-center" style="width: 200px;"><?php echo "File";
                        ; ?></th> 
                        <?php if ($this->ion_auth->is_admin()) { ?>
                        
                        <?php } else if ($this->ion_auth->is_facilityManager()) { ?>
                        <!-- <th class="text-center" style="width: 60px;">Recommendation Date</th> -->
                        <?php } ?>
                        <?php if ($this->ion_auth->is_admin()) { ?>
                        <th class="text-center" style="width: 70px;"><?php echo lang('action'); ?></th>
                        <?php } else if ($this->ion_auth->is_facilityManager()) { ?>
                        <th class="text-center" style="display: none;"><?php echo lang('action'); ?></th>
                        <?php } ?>
                    </tr>
                </thead>
                <tbody>
                    <?php

                    if (isset($list1) && !empty($list1)) {
                        $rowCount = 0;
                        foreach ($list1 as $rows) {

                            $rowCount++;
                    ?>

                    <tr>

                        <td class="text-center text-primary"><strong><?php echo $rowCount; ?></strong></td>
                        <td class="text-center"><?php echo date('m/d/Y', $rows->create_date); ?></td>
                        <!-- <td class="text-primary"><?php echo $rows->first_name . ' ' . $rows->last_name; ?></td> -->
                        <td><?php echo $rows->title ?></td>
                        <td><?php echo $rows->description ?></td>
                      <td>
                        <a class="btn btn-primary" href="<?php echo base_url() . 'recommendation/show?create_date=' . ($rows->create_date); ?>" data-toggle="tooltip" class="btn btn-default"></i>View Attachments</a> 
                            <!-- <button><a href="<?php echo $rows->file;?>" download>
                            <i class="fa fa-download"></i>
                           <embed src='<?php echo $rows->file;?>' frameBorder='0' scrolling='auto'
                                height='100%' width='200px'></embed>
                            </a></button> -->
                            </td>

                        


                        <td class="actions text-center" style="display:none">
                            <!-- <div class="btn-group btn-group-xs">
                                <a href="<?php echo base_url() . 'recommendation/edit?id=' . encoding($rows->id); ?>"
                                    data-toggle="tooltip" class="btn btn-default"><i class="fa fa-pencil"></i></a> -->

                                <?php

                            if ($rows->is_active == 1) {
                                ?>
                                <!--                                                    <a href="javascript:void(0)" data-toggle="tooltip" class="btn btn-xs btn-success" onclick="statusFn('<?php echo USERS; ?>', 'id', '<?php echo encoding($rows->id); ?>', '<?php echo $rows->is_active; ?>')" title="Inactive Now"><i class="fa fa-check"></i></a>-->
                                <?php } else { ?>
                                <!--                                                    <a href="javascript:void(0)" data-toggle="tooltip" class="btn btn-xs btn-danger" onclick="statusFn('<?php echo USERS; ?>', 'id', '<?php echo encoding($rows->id); ?>', '<?php echo $rows->is_active; ?>')" title="Active Now"><i class="fa fa-times"></i></a>-->
                                <?php
                            }
                            if ($rows->is_active == 1) {
                                ?>
                                <!-- <a href="javascript:void(0)" data-toggle="tooltip" class="btn btn-xs btn-success" onclick="changeVendorStatus('<?php echo encoding($rows->id); ?>', 'No','<?php echo $rows->first_name . ' ' . $rows->last_name; ?>')" title="Inactive Now"><i class="fa fa-check"></i> Active</a> -->
                                <?php } else { ?>
                                <!--  <a href="javascript:void(0)" data-toggle="tooltip" class="btn btn-xs btn-danger" onclick="changeVendorStatus('<?php echo encoding($rows->id); ?>', 'Yes','<?php echo $rows->first_name . ' ' . $rows->last_name; ?>')" title="Active Now"><i class="fa fa-times"></i> Inactive</a> -->
                                <?php } ?>
                                <a href="javascript:void(0)" style="margin-left: 10px;" data-toggle="tooltip"
                                    onclick="deleteFn('<?php echo 'recommendation'; ?>', 'unique_id', '<?php echo encoding($rows->unique_id); ?>', 'recommendation', 'recommendation/delVendors','<?php echo $rows->first_name . ' ' . $rows->last_name; ?>')"
                                    class="btn btn-danger"><i class="fa fa-trash"></i></a>

                                <!-- <a href="<?php echo base_url() . 'vendors/paymentList/' . $rows->id; ?>" class="btn btn-sm btn-primary">Client List</a> -->
                            </div>
                        </td>
                    </tr>
                    <?php

                        }
                        ;
                    } else {
                        $rowCount = 0;

                        /* foreach ($list1 as $rows){ */
                        foreach ($list as $rows) {
                          //   echo '<pre>';
                          // print_r($rows);
                          //   die;
                           
                            $rowCount++;
                    ?>

                    <?php if ($LoginID == 1) { ?>
                    <tr>

                        <td class="text-center text-primary"><strong><?php echo $rowCount; ?></strong></td>
                        <td class="text-center"><?php echo date('m/d/Y', $rows->create_date); ?></td>
                        <?php if ($rows->facility_manager_id == 1) { ?>
                        <td class="text-primary h5">All</td>
                        <?php } else { ?>
                        <td  class="text-primary h5"><?php echo $rows->first_name . ' ' . $rows->last_name; ?></td>
                        <?php } ?>
                        <td class="h5"><?php echo $rows->title ?></td>
                        <td class="h5"><?php echo $rows->description ?></td>
                       
                         <td>
                           <a class="btn btn-primary" href="<?php echo base_url() . 'recommendation/show?create_date=' . ($rows->create_date); ?>" data-toggle="tooltip" class="btn btn-default"></i>View Attachments</a> 
                           <!--  <button><a href="<?php echo $rows->file;?>" download>
                            <i class="fa fa-download"></i>
                           <embed src='<?php echo $rows->file;?>' frameBorder='0' scrolling='auto'
                                height='100%' width='200px'></embed>
                            </a></button> -->
                            </td> 
                        

                        <?php if ($this->ion_auth->is_admin()) { ?>
                        <td class="actions text-center">
                            <div class="btn-group btn-group-xs">
                                 <!-- <a href="<?php echo base_url() . 'recommendation/edit?create_date=' . ($rows->create_date); ?>" data-toggle="tooltip" class="btn btn-default"><i class="fa fa-pencil"></i></a>  -->
                                <!-- <a href="<?php echo base_url() . 'recommendation/user?id=' . encoding($rows->id); ?>"
                                    data-toggle="tooltip" class="btn btn-default"><i class="fa fa-eye"></i>View files</a> -->

                                <?php
                                    if ($rows->id != 1) {
                                        if ($rows->is_active == 1) {
                                ?>
                                <!--                                                    <a href="javascript:void(0)" data-toggle="tooltip" class="btn btn-xs btn-success" onclick="statusFn('<?php echo USERS; ?>', 'id', '<?php echo encoding($rows->id); ?>', '<?php echo $rows->is_active; ?>')" title="Inactive Now"><i class="fa fa-check"></i></a>-->
                                <?php } else { ?>
                                <!--                                                    <a href="javascript:void(0)" data-toggle="tooltip" class="btn btn-xs btn-danger" onclick="statusFn('<?php echo USERS; ?>', 'id', '<?php echo encoding($rows->id); ?>', '<?php echo $rows->is_active; ?>')" title="Active Now"><i class="fa fa-times"></i></a>-->
                                <?php
                                        }
                                        if ($rows->is_active == 1) {
                                ?>
                                <!-- <a href="javascript:void(0)" data-toggle="tooltip" class="btn btn-xs btn-success" onclick="changeVendorStatus('<?php echo encoding($rows->id); ?>', 'No','<?php echo $rows->first_name . ' ' . $rows->last_name; ?>')" title="Inactive Now"><i class="fa fa-check"></i> Active</a> -->
                                <?php } else { ?>
                                <!--  <a href="javascript:void(0)" data-toggle="tooltip" class="btn btn-xs btn-danger" onclick="changeVendorStatus('<?php echo encoding($rows->id); ?>', 'Yes','<?php echo $rows->first_name . ' ' . $rows->last_name; ?>')" title="Active Now"><i class="fa fa-times"></i> Inactive</a> -->
                                <?php } ?>


                                    <a class="btn btn-primary" href="javascript:void(0)" style="margin-left: 10px;" data-toggle="tooltip"
                                    onclick="deleteFn1('<?php echo 'recommendation'; ?>', 'create_date', '<?php echo ($rows->create_date); ?>', 'recommendation', 'recommendation/delVendors','<?php echo $rows->first_name . ' ' . $rows->last_name; ?>')"
                                    class="btn btn-danger"><i class="fa fa-trash"></i></a>  


                                   
                                <?php }
                                ?>
                                <!-- <a href="<?php echo base_url() . 'vendors/paymentList/' . $rows->id; ?>" class="btn btn-sm btn-primary">Client List</a> -->
                            </div>
                        </td>
                    </tr>
                    <?php } ?>
                    <?php
                                /* } */}
                        }
                        ;
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