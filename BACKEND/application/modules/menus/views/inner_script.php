<script>


    jQuery('body').on('click', '#submit', function () {

        var form_name = this.form.id;
        if (form_name == '[object HTMLInputElement]')
            form_name = 'addFormAjax';
        $("#" + form_name).validate({
            rules: {
                category: "required",
                item_name: "required",
                price: {
                    required: true,
                    number: true
                },
                quantity_per_unit: {
                    required: true,
                    number: true
                },
                unit_weight: {
                    required: true
                },
                unit_in_stock: {
                    required: true,
                    number: true
                }
            },
            messages: {
                /*category: 'Category is required',
                 item_name: 'Item Name is required',
                 price: {
                 required: 'Price is required'
                 },*/
            },
            submitHandler: function (form) {
                jQuery(form).ajaxSubmit({
                });
            }
        });

    });


    jQuery('body').on('change', '.input_img2', function () {

        var file_name = jQuery(this).val();
        var fileObj = this.files[0];
        var calculatedSize = fileObj.size / (1024 * 1024);
        var split_extension = file_name.split(".");
        var ext = ["jpg", "gif", "png", "jpeg"];
        if (jQuery.inArray(split_extension[1].toLowerCase(), ext) == -1)
        {
            $(this).val(fileObj.value = null);
            //showToaster('error',"You Can Upload Only .jpg, gif, png, jpeg  files !");
            $('.ceo_file_error').html('<?php echo lang('image_upload_error'); ?>');
            return false;
        }
        if (calculatedSize > 1)
        {
            $(this).val(fileObj.value = null);
            //showToaster('error',"File size should be less than 1 MB !");
            $('.ceo_file_error').html(' 1MB');
            return false;
        }
        if (jQuery.inArray(split_extension[1].toLowerCase(), ext) != -1 && calculatedSize < 10)
        {
            $('.ceo_file_error').html('');
            readURL(this);
        }
    });

    function readURL(input) {
        var cur = input;
        if (cur.files && cur.files[0]) {
            var reader = new FileReader();
            reader.onload = function (e) {
                $(cur).hide();
                $(cur).next('span:first').hide();
                $(cur).next().next('img').attr('src', e.target.result);
                $(cur).next().next('img').css("display", "block");
                $(cur).next().next().next('span').attr('style', "");
            }
            reader.readAsDataURL(input.files[0]);
        }
    }

    jQuery('body').on('click', '.remove_img', function () {
        var img = jQuery(this).prev()[0];
        var span = jQuery(this).prev().prev()[0];
        var input = jQuery(this).prev().prev().prev()[0];
        jQuery(img).attr('src', '').css("display", "none");
        jQuery(span).css("display", "block");
        jQuery(input).css("display", "inline-block");
        jQuery(this).css("display", "none");
        jQuery(".image_hide").css("display", "block");
        jQuery("#news_image").val("");
    });

    var open_modal_menu = function (ctrl, method, id) {
        $.ajax({
            url: '<?php echo base_url(); ?>' + ctrl + "/" + method,
            type: 'POST',
            data: {'id': id},
            beforeSend: function () {
                $(".loaders").fadeIn("slow");
            },
            success: function (data, textStatus, jqXHR) {

                $('#form-modal-box').html(data);
                $("#commonModal").modal('show');
                //addFormBoot();
                $(".loaders").fadeOut("slow");
            }
        });
    }

    $('#common_datatable_menu').dataTable({
        order: [],
        columnDefs: [{orderable: false, targets: [3, 4]}]
    });

    function isDiscount() {
        var checkBox = document.getElementById("discount_available");
        if (checkBox.checked == true) {
            $(".discountPrice").show();
        } else {
            $(".discountPrice").hide();
        }
    }

    function getSubCategory(categoryId) {
        $.ajax({
            url: '<?php echo base_url(); ?>menus/getSubCategory',
            type: 'POST',
            data: {'categoryId': categoryId},
            beforeSend: function () {
                $(".loaders").fadeIn("slow");
            },
            success: function (data, textStatus, jqXHR) {
                $('#sub_category').html(data);
                $(".loaders").fadeOut("slow");
            }
        });

    }
</script>

