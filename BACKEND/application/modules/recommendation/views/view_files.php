 <div id="page-content">
    <ul class="breadcrumb breadcrumb-top">
        <li>
            <a href="<?php echo site_url('pwfpanel'); ?>">Home</a>
        </li>
        <li>
            <a href="<?php echo site_url($model); ?>"><?php echo $title; ?></a>
        </li>
    </ul>
     <div class="block full">
        <div class="block-title">
            <h2><strong>View Files</strong> Panel</h2>
        </div>
        <div class="table-responsive">
                          <div>
                               <?php 
                           foreach ($results as  $image) {   ?>
                           <button><a href="<?php echo base_url().$image->file; ?>" download>
                            <i class="fa fa-download"></i>
                           <embed src='<?php echo base_url().$image->file; ?>' frameBorder='0' scrolling='auto'
                            height='300px' width='480px'></embed>
                            </a></button> 
                            <?php } ?>
                          </div>
        </div>
    </div>
</div>
