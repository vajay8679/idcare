<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.16.0/jquery.validate.js"></script>
<!-- Page content -->
<div id="page-content">
    <!-- Datatables Header -->
    <ul class="breadcrumb breadcrumb-top">
        <li>
            <a href="<?php echo site_url('pwfpanel'); ?>">Home</a>
        </li>
        <li>
            <a href="<?php echo site_url($this->router->fetch_class()); ?>"><?php echo $title;?></a>
        </li>
    </ul>
    <!-- END Datatables Header -->
    <div class="row">
    <p style="display:none">
    <?php $LoginID = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : ''; ?>
    </p>
        <div class="col-md-12" >    <div class="block full">
                <div class="block-title">
                    <h2><strong><?php echo $title;?></strong> Panel</h2>
                </div>        
                <form class="form-horizontal" role="form" id="editFormAjaxUser" method="post" action="<?php echo base_url('contactus/update') ?>" enctype="multipart/form-data">
                    <div class="modal-header text-center">
                        <h2 class="modal-title"><i class="fa fa-pencil"></i> <?php echo (isset($title)) ? ucwords($title) : "" ?></h2>
                    </div>
                    <!-- <div class="loaders">
                        <img src="<?php //echo base_url().'backend_asset/images/Preloader_2.gif'; ?>" class="loaders-img" class="img-responsive">
                    </div> -->
                    <div class="alert alert-danger" id="error-box" style="display: none"></div>
                    <div class="form-body">
                        <div class="row">



                            <div class="col-md-12" style="display:none">
                                <div class="form-group">
                                    <label class="col-md-3 control-label">Facility Manager</label>
                                    <div class="col-md-9">  
                                    <input type="text" class="form-control" name="facility_manager_id" id="facility_manager_id" placeholder="Login ID" value="<?php echo $LoginID; ?>"/>                              
                                            <!-- <select id="facility_manager_id" name="facility_manager_id" class="form-control select2" size="1">
                                                <option value="">Please select</option>
                                                <?php foreach($care_unit as $row){?>
                                                            
                                                <option value="<?php echo $row->id;?>" <?php echo ($results->facility_manager_id == $row->id) ? "selected" : ""; ?>><?php echo $row->first_name." ".$row->last_name;?></option>
                                                        
                                                <?php }?>
                                            </select> -->
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-12" >
                                <div class="form-group">
                                    <label class="col-md-3 control-label">Title</label>
                                    <div class="col-md-9">
                                        <input type="text" class="form-control" name="title" id="title" placeholder="Title" value="<?php echo $results->title; ?>"/>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-12" >
                                <div class="form-group">
                                    <label class="col-md-3 control-label"><?php echo lang('description');?></label>
                                    <div class="col-md-9">
                                        <textarea type="text" class=" form-control" style="height:100px; " name="description" id="description" placeholder="Description"><?php echo $results->description;?></textarea>
                                    </div>
                                </div>
                            </div>


                            <input type="hidden" name="id" value="<?php echo $results->id; ?>" />
                            <!-- <input type="hidden" name="password" value="<?php echo $results->password; ?>" />
                            <input type="hidden" name="exists_image" value="<?php echo $results->profile_pic; ?>" /> -->
                            <div class="space-22"></div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <div class="col-md-8 col-md-offset-4">
                            <button type="submit"  class="btn btn-sm btn-primary" id="submit">Save Changes</button>
                        </div>
                    </div>
                </form>
            </div></div>

    </div>

    <!-- Datatables Content -->







    <!-- END Datatables Content -->
</div>
<!-- END Page Content -->
<script type="text/javascript">
    $('#date_of_birth').datepicker({
        startView: 2,
        todayBtn: "linked",
        keyboardNavigation: false,
        forceParse: false,
        calendarWeeks: true,
        autoclose: true,
        endDate: 'today'
    });
    /*    $("#zipcode").select2({
     allowClear: true
     });*/
</script>
