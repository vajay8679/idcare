<script src="//cdn.ckeditor.com/4.20.0/standard/ckeditor.js"></script>
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
                    
                    
                    <div class="col-md-12" >
                        <div class="form-group">
                            <label class="col-md-3 control-label">Facility Manager</label>
                            <div class="col-md-9">                                
                                    <select id="facility_manager_id" name="facility_manager_id" class="form-control select2" size="1">
                                        <option value="">Please select</option>
                                        <option value="1">All</option>
                                        <?php foreach($users as $row){?>
                                                    
                                        <option value="<?php echo $row->id;?>"><?php echo $row->first_name." ". $row->last_name;?></option>
                                                
                                        <?php }?>
                                    </select>
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
                            <label class="col-md-3 control-label">File attachment</label>
                            <div class="col-md-9">
                                <input type="file" class="form-control"  name="image_name[]" id="file" placeholder="File" multiple/>
                            </div>
                            <!-- <span class="help-block m-b-none col-md-offset-3"><i class="fa fa-arrow-circle-o-up"></i> <?php echo lang('english_note');?></span> -->
                         </div>
                    </div> 

<!-- 
                    <div class="col-md-12">
                          <div class="">
                          <div class="justify-content-center">
                                                         <input type="file" name="file"  />
                            </div>
                            <div class="">
                            <div id="image-preview-addvendore">
                                                        
                             </div>
                            </div>
                          </div>
                    </div> -->
                    <!-- <div class="col-md-12">
                                <div class="">
                                            <div class="img_back_prieview_Academic">
                                                <div class="images_box_upload_ven_addvendore_vendore">
                                                    <div id="image-preview-addvendore-vendore">
                                                         <input type="file" name="file" id="image-upload-addvendore-vendore" />
                                                    </div>
                                                </div>
                                                    <div id="image-preview-addvendore">
                                                         <label for="image-upload-addvendore-vendore" id="image-label-addvendore-vendore">Upload File</label>
                                                   </div>
                                            </div>
                                        </div>
                     </div> -->
                                     


                    <div class="col-md-12" >
                        <div class="form-group">
                            <label class="col-md-3 control-label"><?php echo lang('description');?></label>
                            <div class="col-md-9">
                                <textarea class="form-control ckeditor"  name="description"></textarea>
                                <!-- <textarea id="textarea-ckeditor" name="textarea-ckeditor" class="ckeditor"></textarea> -->
                            </div>
                            <span class="help-block m-b-none col-md-offset-3">                                    
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