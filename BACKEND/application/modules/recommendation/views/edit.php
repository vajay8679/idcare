<script src="//cdn.ckeditor.com/4.20.0/standard/ckeditor.js"></script>
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
 
        <div class="col-md-12" >    <div class="block full">
                <div class="block-title">
                    <h2><strong><?php echo $title;?></strong> Panel</h2>
                </div>        
                <form class="form-horizontal" role="form" id="editFormAjaxUser" method="post" action="<?php echo base_url('recommendation/update') ?>" enctype="multipart/form-data">
                    <div class="modal-header text-center">
                        <h2 class="modal-title"><i class="fa fa-pencil"></i> <?php echo (isset($title)) ? ucwords($title) : "" ?></h2>
                    </div>
                    <!-- <div class="loaders">
                        <img src="<?php //echo base_url().'backend_asset/images/Preloader_2.gif'; ?>" class="loaders-img" class="img-responsive">
                    </div> -->
                    <div class="alert alert-danger" id="error-box" style="display: none"></div>
                    <div class="form-body">
                        <div class="row">



                            <div class="col-md-12" >
                                <div class="form-group">
                                    <label class="col-md-3 control-label">Facility Manager</label>
                                    <div class="col-md-9">                                
                                            <select id="facility_manager_id" name="facility_manager_id" class="form-control select2" size="1">
                                                <option value="">Please select</option>
                                                <option value="1">All</option>
                                                <?php foreach($care_unit as $row){?>
                                                <?php if ($row->id !='1') { ?> 
                                                <option value="<?php echo $row->id;?>" <?php echo ($results->facility_manager_id == $row->id) ? "selected" : ""; ?>><?php echo $row->first_name." ".$row->last_name;?></option>
                                                       <?php }else{ ?>

                                                        <option value="1" <?php echo ($results->facility_manager_id == $row->id) ? "selected" : ""; ?>><?php echo 'All';?></option>

                                                      <?php }  ?>
                                                <?php }?>
                                            </select>
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
                                    <label class="col-md-3 control-label">File Attachment</label>
                                    <div class="col-md-9">
                                        <input type="file" class="form-control" name="file" id="file" placeholder="file"  value="<?php echo $results->file; ?>"/>
                                        <p><?php echo $results->file;?></p>
                                      
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-12" >
                                <div class="form-group">
                                    <label class="col-md-3 control-label"><?php echo lang('description');?></label>
                                    <div class="col-md-9">
                                        <textarea  class="summernote form-control ckeditor" name="description" id="description" ><?php echo $results->description;?></textarea>
                                    </div>
                                    <span class="help-block m-b-none col-md-offset-3">                                    
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
