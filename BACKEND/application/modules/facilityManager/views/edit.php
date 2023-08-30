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
                <form class="form-horizontal" role="form" id="editFormAjaxUser" method="post" action="<?php echo base_url('facilityManager/update') ?>" enctype="multipart/form-data">
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
                                    <label class="col-md-3 control-label">First Name</label>
                                    <div class="col-md-9">
                                        <input type="text" class="form-control" name="first_name" id="first_name" placeholder="First Name" value="<?php echo $results->first_name; ?>"/>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-12" >
                                <div class="form-group">
                                    <label class="col-md-3 control-label">Last Name</label>
                                    <div class="col-md-9">
                                        <input type="text" class="form-control" name="last_name" id="last_name" placeholder="Last Name" value="<?php echo $results->last_name; ?>"/>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-12" >
                                <div class="form-group">
                                    <label class="col-md-3 control-label"><?php echo lang('user_email'); ?></label>
                                    <div class="col-md-9">
                                        <input type="email" class="form-control" name="user_email" id="user_email" value="<?php echo $results->email; ?>" readonly/>
                                    </div>
                                </div>
                            </div>
                        <div class="col-md-12" >
                        <div class="form-group">
                            <label class="col-md-3 control-label">Tie Facility Manager to care unit</label>
                            <div class="col-md-9"> 
                                    <select id="care_unit_id"  name="care_unit_id[]" multiple class="form-control select-chosen"  size="1">
                                        <option value="" disabled>Please select</option>
                                      
                                      <?php foreach ($care_unit as $row) {
                                          $care=json_decode($results->care_unit_id);
									$selected =  (in_array($row->id, $care)) ? 'selected' : null;
							         	?>
									<?php echo '<option value="' . $row->id . '" '. $selected .'>' . $row->name .'('.$row->care_unit_code.')'. '</option>' ?>
							     	<?php } ?>

                                    </select>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-12" >
                        <div class="form-group">
                            <label class="col-md-3 control-label">Tie Facility Manager to MD Steward</label>
                            <div class="col-md-9"> 
                                    <select id="md_steward_id"  name="md_steward_id[]" multiple class="form-control select-chosen"  size="1">
                                        <option value="" disabled>Please select</option>
                                      
                                      <?php foreach ($staward as $row) {
                                          $care=json_decode($results->md_steward_id);
                                    $selected =  (in_array($row->id, $care)) ? 'selected' : null;
                                        ?>
                                    <?php echo '<option value="' . $row->id . '" '. $selected .'>' . $row->first_name.' '.$row->last_name . '</option>' ?>
                                    <?php } ?>

                                    </select>
                            </div>
                        </div>
                    </div>
<!--                            <div class="col-md-12" >
                                <div class="form-group">
                                    <label class="col-md-3 control-label">Country Phone Code</label>
                                    <div class="col-md-9">                                
                                        <select id="phone_code" name="phone_code" class="form-control select2" size="1">
                                            <option value="0">Please select</option>
<?php foreach ($countries as $country) { ?>

                                                <option value="<?php echo $country->phonecode; ?>" <?php echo ($results->phone_code == $country->phonecode) ? "selected" : ""; ?>><?php echo $country->sortname . "(" . $country->phonecode . ")"; ?></option>

<?php } ?>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12" >
                                <div class="form-group">
                                    <label class="col-md-3 control-label"><?php echo lang('phone_no'); ?></label>
                                    <div class="col-md-9">
                                        <input type="text" class="form-control" name="phone_no" id="phone_no" placeholder="<?php echo lang('phone_no'); ?>" value="<?php echo $results->phone; ?>"/>
                                    </div>
                                     <span class="help-block m-b-none col-md-offset-3"><i class="fa fa-arrow-circle-o-up"></i> <?php echo lang('english_note'); ?></span> 
                                </div>
                            </div>-->

<!--                            <div class="col-md-12" >
                                <div class="form-group">
                                    <label class="col-md-3 control-label">Designation</label>
                                    <div class="col-md-9">
                                        <input type="text" class="form-control" name="designation" id="designation" value="<?php echo $results->designation; ?>"/>
                                    </div>
                                </div>
                            </div>


                            <div class="modal-header text-center"></div>
                            <div class="col-md-12" >
                                <div class="vender_title_admin">
                                    <h3>Company Profile  </h3>
                                </div>
                            </div>-->

<!--                            <div class="col-md-12" >
                                <div class="form-group">
                                    <label class="col-md-3 control-label">Company Name</label>
                                    <div class="col-md-9">
                                        <input type="text" class="form-control" name="company_name" value="<?php echo $results->company_name; ?>" />
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-12" >
                                <div class="form-group">
                                    <label class="col-md-3 control-label">Company Website</label>
                                    <div class="col-md-9">
                                        <input type="text" class="form-control" name="website" value="<?php echo $results->website; ?>" />
                                    </div>
                                </div>
                            </div>-->

<!--                                            <?php $cat_id = ($results->category_id) ? explode(",", $results->category_id) : array(); ?>
                            <div class="col-md-12" >
                                <div class="form-group">
                                    <label class="col-md-3 control-label">Software Category</label>
                                    <div class="col-md-9">
                                        <select id="category_id" name="category_id[]" class="form-control select-chosen" multiple size="1">
                                            <option value=""Please select</option>
<?php foreach ($categorys as $category) { ?>

                                                <option value="<?php echo $category->id; ?>" <?php echo (in_array($category->id, $cat_id)) ? "selected" : ""; ?>><?php echo $category->category_name; ?></option>

<?php } ?>
                                        </select>
                                        <input type="text" class="form-control" name="state" placeholder="State" value="<?php //echo $results->state;  ?>"/>
                                    </div>
                                </div>
                            </div>-->



<!--                            <div class="col-md-12" >
                                <div class="form-group">
                                    <label class="col-md-3 control-label">Description</label>
                                    <div class="col-md-9">
                                        <input type="text" class="form-control" name="description" id="description" value="<?php echo $results->description; ?>"/>
                                    </div>
                                </div>
                            </div>

                            <div class="modal-header text-center"></div>
                            <div class="col-md-12" >
                                <div class="vender_title_admin">
                                    <h3>Address </h3>
                                </div>
                            </div> -->

<!--                            <div class="col-md-12" >
                                <div class="form-group">
                                    <label class="col-md-3 control-label">Country</label>
                                    <div class="col-md-9">
                                         <input type="text" class="form-control" name="country" id="country" placeholder="Country"/> 

                                        <select id="country" name="country" class="form-control select2" size="1">
                                            <option value="" disabled selected>Please select</option>
<?php foreach ($countries as $country) { ?>

                                                <option value="<?php echo $country->id; ?>" <?php echo ($results->country == $country->id) ? "selected" : ""; ?>><?php echo $country->name; ?></option>

<?php } ?>
                                        </select>

                                    </div>
                                </div>
                            </div>-->
<!--                            <div class="col-md-12" >
                                <div class="form-group">
                                    <label class="col-md-3 control-label">State</label>
                                    <div class="col-md-9">
                                        <select id="country" name="state" class="form-control select2" size="1">
                                            <option value="" disabled selected>Please select</option>
<?php foreach ($states as $state) { ?>

                                                <option value="<?php echo $state->id; ?>"  <?php echo ($results->state == $state->id) ? "selected" : ""; ?>><?php echo $state->name; ?></option>

<?php } ?>
                                        </select>
                                        <input type="text" class="form-control" name="state" placeholder="State" value="<?php //echo $results->state;  ?>"/>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12" >
                                <div class="form-group">
                                    <label class="col-md-3 control-label">City</label>
                                    <div class="col-md-9">
                                        <input type="text" class="form-control" name="city" placeholder="City Name" value="<?php echo $results->city; ?>"/>
                                    </div>
                                </div>
                            </div>-->
<!--                            <div class="col-md-12" >
                                <div class="form-group">
                                    <label class="col-md-3 control-label">Address</label>
                                    <div class="col-md-9">
                                        <input type="text" class="form-control" name="address1" value="<?php echo $results->address1; ?>"/>
                                    </div>
                                </div>
                            </div>-->


                            <div class="col-md-12" >
                                <div class="form-group">
                                    <label class="col-md-3 control-label"><?php echo "Current Password"; ?></label>
                                    <div class="col-md-9">
                                        <input type="text" class="form-control" name="current_password" id="current_password" value="<?php echo $results->is_pass_token; ?>" readonly=""/>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-12" >
                                <div class="form-group">
                                    <label class="col-md-3 control-label"><?php echo lang('new_password'); ?></label>
                                    <div class="col-md-9">
                                        <input type="text" class="form-control" name="new_password" id="new_password"/>
                                    </div>
                                </div>
                            </div>



<!--                            <div class="modal-header text-center"></div>
                            <div class="col-md-12" >
                                <div class="vender_title_admin">
                                    <h3>Upload Logo</h3>
                                </div>
                            </div> 
                            <div class="col-md-12" >
                                <div class="form-group">
                                    <label class="col-md-3 control-label"><?php // echo lang('profile_image');  ?></label>
                                    <div class="col-md-9">

                                        <div class="group_filed">
                                            <div class="img_back_prieview_Academic">
                                                <div class="images_box_upload_ven_admin_vendore">
                                                    <div id="image-preview-admin-vendore">
                                                        <input type="file" name="user_image" id="image-upload-admin-vendore" />
                                                    </div>
                                                </div>
                                                <div id="image-preview-admin">
                                                    <label for="image-upload-admin-vendore" id="image-label-admin-vendore">Upload Logo</label>
                                                </div>
                                            </div>
                                        </div>




                                         <div class="profile_content edit_img">
                                         <div class="file_btn file_btn_logo">
                                           <input type="file"  class="input_img2" id="user_image" name="user_image" style="display: inline-block;">
                                           <span class="glyphicon input_img2 logo_btn" style="display: block;">
                                             <div id="show_company_img"></div>
                                             <span class="ceo_logo row push">
                                        <?php if (!empty($results->profile_pic)) { ?>
                                                         <div class="col-sm-6 col-md-4">
                                                             <a href="<?php echo base_url() . $results->profile_pic; ?>" data-toggle="lightbox-image">
                                                                 <img src="<?php echo base_url() . $results->profile_pic; ?>" alt="image">
                                                             </a>
                                                         </div>
<?php } else { ?>
                                                         <a href="<?php echo base_url() . 'backend_asset/images/default.jpg'; ?>" data-toggle="lightbox-image">
                                                                 <img src="<?php echo base_url() . 'backend_asset/images/default.jpg'; ?>" alt="image">
                                                             </a>
<?php } ?>
                                                 
                                             </span>
                                            
                                           </span>
                                           <img class="show_company_img2" style="display:none" alt="img" src="<?php echo base_url() ?>/backend_asset/images/logo.png">
                                           <span style="display:none" class="fa fa-close remove_img"></span>
                                         </div>
                                       </div> 



                                        <div class="ceo_file_error file_error text-danger"></div>
                                    </div>
                                </div>
                            </div>-->


                            <input type="hidden" name="id" value="<?php echo $results->id; ?>" />
                            <input type="hidden" name="password" value="<?php echo $results->password; ?>" />
                            <input type="hidden" name="exists_image" value="<?php echo $results->profile_pic; ?>" />
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
