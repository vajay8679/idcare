<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.16.0/jquery.validate.js"></script>
<!-- Page content -->
<div id="page-content">
    <!-- Datatables Header -->
    <ul class="breadcrumb breadcrumb-top">
        <li>
            <a href="<?php echo site_url('pwfpanel');?>">Home</a>
        </li>
        <li>
            <a href="<?php echo site_url('recommendation');?>"><?php echo $title;?></a>
        </li>
    </ul>
    <!-- END Datatables Header -->
    <!-- Datatables Content -->
    <p style="display:none">
    <?php $LoginID = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : ''; ?>
    </p>

    <div class="block full">
        <div class="block-title">
            <h2><strong><?php echo $title;?></strong> Panel</h2>
        </div>
        <form class="form-horizontal" role="form" id="addFormAjax" method="post" action="<?php echo base_url($formUrl) ?>" enctype="multipart/form-data">
            <div class="modal-header text-center">
                <h2 class="modal-title"><i class="fa fa-pencil"></i> <?php echo (isset($title)) ? ucwords($title) : "" ?></h2>
            </div>
            <div class="alert alert-danger" id="error-box" style="display: none"></div>
            <div class="form-body">
                <div class="row">
                    
                    <div class="col-md-12" style="display:none">
                        <div class="form-group">
                            <label class="col-md-3 control-label">Login ID</label>
                            <div class="col-md-9">
                                <input type="text" class="form-control" name="login_id" id="login_id" placeholder="Login ID" value="<?php echo $LoginID; ?>"/>
                            </div>
                            <!-- <span class="help-block m-b-none col-md-offset-3"><i class="fa fa-arrow-circle-o-up"></i> <?php echo lang('english_note');?></span> -->
                        </div>
                    </div>
                    
                    
                    <div class="col-md-12" style="display:none">
                        <div class="form-group">
                            <label class="col-md-3 control-label">Facility Manager</label>
                            <div class="col-md-9"> 
                            <input type="text" class="form-control" name="facility_manager_id" id="facility_manager_id" placeholder="Login ID" value="<?php echo $LoginID; ?>"/>                               
                                   <!--  <select id="facility_manager_id" name="facility_manager_id"  value="<?php echo $LoginID; ?>" class="form-control select2" size="1">
                                        <option value="">Please select</option>
                                        <?php foreach($users as $row){?>
                                                    
                                        <option value="<?php echo $LoginID;?>"><?php echo $row->first_name." ". $row->last_name;?></option>
                                                
                                        <?php }?>
                                    </select> -->
                            </div>
                        </div>
                    </div>
                    

                    <div class="col-md-12" >
                        <div class="form-group">
                            <label class="col-md-3 control-label">Title</label>
                            <div class="col-md-9">
                                <input type="text" class="form-control" name="title" id="title" placeholder="Title" />
                            </div>
                            <!-- <span class="help-block m-b-none col-md-offset-3"><i class="fa fa-arrow-circle-o-up"></i> <?php echo lang('english_note');?></span> -->
                        </div>
                    </div>

                    <div class="col-md-12" >
                        <div class="form-group">
                            <label class="col-md-3 control-label"><?php echo lang('description');?></label>
                            <div class="col-md-9">
                                <textarea class="summernote form-control ckeditor" style="height: 100px;" placeholder="Description" name="description"></textarea>
                                <!-- <textarea id="textarea-ckeditor" name="textarea-ckeditor" class="ckeditor"></textarea> -->
                            </div>                                    
                        </div>
                    </div>
                    
                    <!-- <div class="col-md-12" >
                        <div class="form-group">
                            <label class="col-md-3 control-label"><?php echo lang('password');?></label>
                            <div class="col-md-9">
                                <input type="text" class="form-control" name="password" id="password" placeholder="<?php echo lang('password');?>" value="<?php echo randomPassword();?>"/>
                            </div>
                        </div>
                    </div> -->

                    <div class="space-22"></div>
                </div>
            </div>
            <div class="text-right">
                <button type="submit" id="submit" class="btn btn-sm btn-primary" >Save</button>
            </div>
        </form>
        
    </div>
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
        endDate:'today'       
    });
/*    $("#zipcode").select2({
        allowClear: true
    });*/
</script>