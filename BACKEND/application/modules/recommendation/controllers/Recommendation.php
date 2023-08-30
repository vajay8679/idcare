<?php

use Mpdf\Tag\Select;

defined('BASEPATH') or exit('No direct script access allowed');

class Recommendation extends Common_Controller
{

    public $data = array();
    public $file_data = "";
    public $_table = 'recommendation';
    public $title = "Steward suggestions";

    public function __construct()
    {
        parent::__construct();
        $this->is_auth_admin();
        $this->load->helper(array('form', 'url'));
        $this->load->helper('string');
    }

    /**
     * @method index
     * @description listing display
     * @return array
     */
    public function index($vendor_profile_activate = "No")
    {

        $this->data['parent'] = $this->title;
        $this->data['title'] = $this->title;
        $this->data['model'] = $this->router->fetch_class();
        $role_name = $this->input->post('role_name');


        $LoginID = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : '';

        if ($LoginID != 1 && $LoginID != NULL) {
            $x = $LoginID;
        }

        $this->data['roles'] = array(
            'role_name' => $role_name
        );
        if ($vendor_profile_activate == "No") {
            $vendor_profile_activate = 0;
        } else {
            $vendor_profile_activate = 1;
        }

        if ($this->ion_auth->is_facilityManager()) {
            $option1 =
             "SELECT DISTINCT title,description,facility_manager_id
                         is_active,create_date,`vendor_sale_users`.`first_name`,
                         `vendor_sale_users`.`last_name`
        FROM `vendor_sale_recommendation` 
        LEFT JOIN `vendor_sale_users` ON 
        `vendor_sale_users`.`id` = `vendor_sale_recommendation`.`facility_manager_id`
        WHERE `vendor_sale_recommendation`.`delete_status` = 0  and
        (`vendor_sale_recommendation`.`facility_manager_id` =$LoginID or `vendor_sale_recommendation`.`facility_manager_id` = 1)
        ORDER BY `vendor_sale_recommendation`.`id` DESC";

            

            $this->data['list1'] = $this->common_model->customQuery($option1);
        } else if ($this->ion_auth->is_admin()) {


            $option =
          "SELECT DISTINCT title,description,facility_manager_id,
                         is_active,create_date,`vendor_sale_users`.`first_name`,
                          `vendor_sale_users`.`last_name`
        FROM `vendor_sale_recommendation` 
        LEFT JOIN `vendor_sale_users` ON 
        `vendor_sale_users`.`id` = `vendor_sale_recommendation`.`facility_manager_id`
        WHERE `vendor_sale_recommendation`.`delete_status` = 0 
        ORDER BY `vendor_sale_recommendation`.`create_date` DESC";

           
          


                $this->data['list'] = $this->common_model->customQuery($option);


        }
           # $this->data['list'] = $this->common_model->customQuery($option);
        $this->load->admin_render('list', $this->data, 'inner_script');
    }

    /**
     * @method profile
     * @description get profile
     * @return array
     */
    // public function profile() {
    //     $this->data['parent'] = $this->titlblank_arraye;
    //     $this->data['title'] = $this->title;
    //     $role_name = $this->input->post('role_name');
    //     $this->data['roles'] = array(
    //         'role_name' => $role_name
    //     );
    //     $user_id = $this->session->userdata('user_id');
    //     $option = array('table' => USERS . ' as user',
    //         'select' => 'user.*,group.name as group_name',
    //         'join' => array(array(USER_GROUPS . ' as ugroup', 'ugroup.user_id=user.id', 'left'),
    //             array(GROUPS . ' as group', 'group.id=ugroup.group_id', 'left')),
    //         'order' => array('user.id' => 'ASC'),
    //         'where' => array('user.delete_status' => 0, 'user.id' => $user_id),
    //         'where_not_in' => array('group.id' => array(1, 2, 3)),
    //         'order' => array('user.id' => 'desc')
    //     );
    //     $this->data['list'] = $this->common_model->customGet($option);
    //     $this->load->admin_render('list', $this->data, 'inner_script');
    // }

    /**
     * @method open_model
     * @description load model box
     * @return array
     */
    function open_model()
    {

        $this->data['parent'] = $this->title;
        $this->data['title'] = "Add " . $this->title;
        $this->data['formUrl'] = $this->router->fetch_class() . "/add";
        $option = "SELECT `vendor_sale_users`.`id`,`vendor_sale_users`.`first_name`, 
        `vendor_sale_users`.`last_name`
        FROM `vendor_sale_users` 
        LEFT JOIN `vendor_sale_users_groups` ON `vendor_sale_users_groups`.`user_id` = `vendor_sale_users`.`id`
        LEFT JOIN `vendor_sale_groups` ON `vendor_sale_groups`.`id` = `vendor_sale_users_groups`.`group_id`
        WHERE `vendor_sale_users`.`delete_status` = 0 and `vendor_sale_users_groups`.`group_id` = 5
        ORDER BY `vendor_sale_users`.`first_name` ASC";

        $this->data['users'] = $this->common_model->customQuery($option);
        $this->load->admin_render('add', $this->data, 'inner_script');
    }

    /**
     * @method users_add
     * @description add dynamic rows
     * @return array
     */




    public function add()
    {

        $this->form_validation->set_rules('facility_manager_id', "Facility Manager Name", 'required|xss_clean');
        $this->form_validation->set_rules('title', "Title", 'required|trim');
        $this->form_validation->set_rules('description', "Description", 'required|trim');
        $this->form_validation->set_rules('file', 'File');

        if ($this->form_validation->run() == true) {

            $this->load->library('upload');
            $image = array();
            $ImageCount = count($_FILES['image_name']['name']);
            for ($i = 0; $i < $ImageCount; $i++) {

                $_FILES['file']['name'] = $_FILES['image_name']['name'][$i];
                $_FILES['file']['type'] = $_FILES['image_name']['type'][$i];
                $_FILES['file']['tmp_name'] = $_FILES['image_name']['tmp_name'][$i];
                $_FILES['file']['error'] = $_FILES['image_name']['error'][$i];
                $_FILES['file']['size'] = $_FILES['image_name']['size'][$i];

                // File upload configuration
                $uploadPath = 'uploads/file/';
                $config['upload_path'] = $uploadPath;
                $config['allowed_types'] = 'pdf|jpg|jpeg|png|ods|odt|doc|txt|docx|csv|xlsx|xls|ppt|pptx';
                $config['max_size'] = 102400 * 2000;
                $config['file_name'] = $_FILES['files']['name'][$i];
                // Load and initialize upload library
                $this->load->library('upload', $config);
                $this->upload->initialize($config);

                // Upload file to server $image = $this->input->post('file');

                if ($this->upload->do_upload('file')) {
                    $uploadData = $this->upload->data();
                    $image = 'uploads/file/' . $uploadData['file_name'];

                } else {
                    $image = "";
                }
                if (!$image) {
                    $response = array('status' => 0, 'message' => $this->filedata['error']);
                } else {
                    $options_data = array(
                        'facility_manager_id' => $this->input->post('facility_manager_id'),
                        'title' => $this->input->post('title'),
                        'description' => $this->input->post('description'),
                        'file' => $image,
                        'is_active' => 1,
                        'create_date' => strtotime(datetime()),
                    );

                    $option = array('table' => $this->_table, 'data' => $options_data);

                    if ($this->common_model->customInsert($option)) {
                        $response = array('status' => 1, 'message' => "Successfully added", 'url' => base_url($this->router->fetch_class()));
                    } else {
                        $response = array('status' => 0, 'message' => "Failed to add");
                    }
                }
            }
            

        } else {
            $messages = (validation_errors()) ? validation_errors() : '';
            $response = array('status' => 0, 'message' => $messages);
        }
        echo json_encode($response);
    }


    /**
     * @method user_edit
     * @description edit dynamic rows
     * @return array
     */
    public function edit()
    {
        $this->data['parent'] = $this->title;
        $this->data['title'] = "Edit " . $this->title;
        $id = ($_GET['id']);

        if (!empty($id)) {

            /*     
            $option ="SELECT `vendor_sale_recommendation`.`title`, 
            `vendor_sale_recommendation`.`id`,
            `vendor_sale_recommendation`.`description`,
            `vendor_sale_recommendation`.`is_active`,
            `vendor_sale_recommendation`.`create_date`,
            `vendor_sale_users`.`first_name`,
            `vendor_sale_users`.`last_name`
            FROM `vendor_sale_recommendation` 
            LEFT JOIN `vendor_sale_users` ON 
            `vendor_sale_users`.`id` = `vendor_sale_recommendation`.`facility_manager_id`
            WHERE  `vendor_sale_recommendation`.`id` = $id and `vendor_sale_recommendation`.`delete_status` = 0 
            ORDER BY `vendor_sale_recommendation`.`id` DESC";
            $results_row = $this->common_model->customQuery($option);
            */

            $option = array(
                'table' => 'recommendation' . ' as R',
                'select' => 'R.*, '
                . 'U.id as u_id,U.first_name,U.last_name,',
                'join' => array(
                    array(USERS . ' as U', 'U.id=R.facility_manager_id', '')
                ),
                'where' => array('R.id' => $id),
                'single' => true
            );
            $results_row = $this->common_model->customGet($option);


            if (!empty($results_row)) {

                $this->data['results'] = $results_row;


                $option = "SELECT `vendor_sale_users`.`id`,`vendor_sale_users`.`first_name`, 
                            `vendor_sale_users`.`last_name`
                            FROM `vendor_sale_users` 
                            LEFT JOIN `vendor_sale_users_groups` ON `vendor_sale_users_groups`.`user_id` = `vendor_sale_users`.`id`
                            LEFT JOIN `vendor_sale_groups` ON `vendor_sale_groups`.`id` = `vendor_sale_users_groups`.`group_id`
                            WHERE `vendor_sale_users`.`delete_status` = 0 and (`vendor_sale_users_groups`.`group_id` = 5 or `vendor_sale_users_groups`.`group_id` = 1)
                            ORDER BY `vendor_sale_users`.`first_name` ASC";
                $this->data['care_unit'] = $this->common_model->customQuery($option);
                $this->load->admin_render('edit', $this->data, 'inner_script');
            } else {
                $this->session->set_flashdata('error', lang('not_found'));
                redirect($this->router->fetch_class());
            }
        } else {
            $this->session->set_flashdata('error', lang('not_found'));
            redirect($this->router->fetch_class());
        }
    }


public function show()
    {
        $this->data['parent'] = $this->title;
        $this->data['title'] = "Edit " . $this->title;
        $id = ($_GET['create_date']);

        if (!empty($id)) {

                 
            $option ="SELECT `vendor_sale_recommendation`.`file`
            
            FROM `vendor_sale_recommendation` 
            
            WHERE  `vendor_sale_recommendation`.`create_date` = $id
            ORDER BY `vendor_sale_recommendation`.`create_date` DESC";
            $results_row = $this->common_model->customQuery($option);
            

            // $option = array(
            //     'table' => 'recommendation' . ' as R',
            //     'select' => 'R.*, '
            //     . 'U.id as u_id,U.first_name,U.last_name,',
            //     'join' => array(
            //         array(USERS . ' as U', 'U.id=R.facility_manager_id', '')
            //     ),
            //     'where' => array('R.create_date' => $id),
            //     'single' => true
            // );
            // $results_row = $this->common_model->customGet($option);


            if (!empty($results_row)) {

                $this->data['results'] = $results_row;


                $option = "SELECT `vendor_sale_users`.`id`,`vendor_sale_users`.`first_name`, 
                            `vendor_sale_users`.`last_name`
                            FROM `vendor_sale_users` 
                            LEFT JOIN `vendor_sale_users_groups` ON `vendor_sale_users_groups`.`user_id` = `vendor_sale_users`.`id`
                            LEFT JOIN `vendor_sale_groups` ON `vendor_sale_groups`.`id` = `vendor_sale_users_groups`.`group_id`
                            WHERE `vendor_sale_users`.`delete_status` = 0 and (`vendor_sale_users_groups`.`group_id` = 5 or `vendor_sale_users_groups`.`group_id` = 1)
                            ORDER BY `vendor_sale_users`.`first_name` ASC";
                $this->data['care_unit'] = $this->common_model->customQuery($option);
                $this->load->admin_render('view_files', $this->data, 'inner_script');
            } else {
                $this->session->set_flashdata('error', lang('not_found'));
                redirect($this->router->fetch_class());
            }
        } else {
            $this->session->set_flashdata('error', lang('not_found'));
            redirect($this->router->fetch_class());
        }
    }
    /**
     * @method user_update
     * @description update dynamic rows
     * @return array
     */

    public function user()
    {
        $this->data['parent'] = $this->title;
        $this->data['title'] = "Edit " . $this->title;
        $id = decoding($_GET['id']);

        if (!empty($id)) {

            /*     
            $option ="SELECT `vendor_sale_recommendation`.`title`, 
            `vendor_sale_recommendation`.`id`,
            `vendor_sale_recommendation`.`description`,
            `vendor_sale_recommendation`.`is_active`,
            `vendor_sale_recommendation`.`create_date`,
            `vendor_sale_users`.`first_name`,
            `vendor_sale_users`.`last_name`
            FROM `vendor_sale_recommendation` 
            LEFT JOIN `vendor_sale_users` ON 
            `vendor_sale_users`.`id` = `vendor_sale_recommendation`.`facility_manager_id`
            WHERE  `vendor_sale_recommendation`.`id` = $id and `vendor_sale_recommendation`.`delete_status` = 0 
            ORDER BY `vendor_sale_recommendation`.`id` DESC";
            $results_row = $this->common_model->customQuery($option);
            */

            $option = array(
                'table' => 'recommendation' . ' as R',
                'select' => 'R.*, '
                . 'U.id as u_id,U.first_name,U.last_name,',
                'join' => array(
                    array(USERS . ' as U', 'U.id=R.facility_manager_id', '')
                ),
                'where' => array('R.id' => $id),
                'single' => true
            );
            $results_row = $this->common_model->customGet($option);


            if (!empty($results_row)) {

                $this->data['results'] = $results_row;


                $option = "SELECT `vendor_sale_users`.`id`,`vendor_sale_users`.`first_name`, 
                            `vendor_sale_users`.`last_name`
                            FROM `vendor_sale_users` 
                            LEFT JOIN `vendor_sale_users_groups` ON `vendor_sale_users_groups`.`user_id` = `vendor_sale_users`.`id`
                            LEFT JOIN `vendor_sale_groups` ON `vendor_sale_groups`.`id` = `vendor_sale_users_groups`.`group_id`
                            WHERE `vendor_sale_users`.`delete_status` = 0 and (`vendor_sale_users_groups`.`group_id` = 5 or `vendor_sale_users_groups`.`group_id` = 1)
                            ORDER BY `vendor_sale_users`.`first_name` ASC";
                $this->data['care_unit'] = $this->common_model->customQuery($option);
                $this->load->admin_render('edit', $this->data, 'inner_script');
            } else {
                $this->session->set_flashdata('error', lang('not_found'));
                redirect($this->router->fetch_class());
            }
        } else {
            $this->session->set_flashdata('error', lang('not_found'));
            redirect($this->router->fetch_class());
        }
    }

    /**
     * @method user_update
     * @description update dynamic rows
     * @return array
     */

    public function update()
    {
        $this->form_validation->set_rules('facility_manager_id', "Facility Manager", 'required|trim');
        $this->form_validation->set_rules('title', "Title", 'required|trim');
        $this->form_validation->set_rules('description', "Description", 'required|trim');
        $this->form_validation->set_rules('file', "File", );
        $config['upload_path'] = './uploads/file';
        $config['allowed_types'] = 'pdf|jpg|jpeg|png|ods|odt|doc|txt';

        $this->load->library('upload', $config);
        $where_id = $this->input->post('id');
        $image = $this->input->post('file');
        if ($this->upload->do_upload('file')) {
            $uploadData = $this->upload->data();
            $image = 'uploads/file/' . $uploadData['file_name'];
        } else {
            $image = '';
        }
        if ($this->form_validation->run() == FALSE):
            $messages = (validation_errors()) ? validation_errors() : '';
            $response = array('status' => 0, 'message' => $messages);
        else:
            $this->filedata['status'] = 1;

            if ($this->filedata['status'] == 0) {
                $response = array('status' => 0, 'message' => $this->filedata['error']);
            } else {

                $options_data = array(
                    'title' => $this->input->post('title'),
                    'description' => $this->input->post('description'),
                    'facility_manager_id' => $this->input->post('facility_manager_id'),
                    'file' => $image,
                );


                $option = array(
                    'table' => $this->_table,
                    'data' => $options_data,
                    'where' => array('id' => $where_id)
                );
                $update = $this->common_model->customUpdate($option);

                $response = array('status' => 1, 'message' => "Successfully updated", 'url' => base_url('recommendation/edit'), 'id' => encoding($this->input->post('id')));

            }
        endif;

        echo json_encode($response);
    }


    /*  public function update1() {
    $this->form_validation->set_rules('title', lang('title'), 'required|trim|xss_clean');
    $this->form_validation->set_rules('description', lang('description'), 'required|trim|xss_clean');
    $this->form_validation->set_rules('facility_manager_id', "Facility Manager", 'required|trim|xss_clean');
    $newpass = $this->input->post('new_password');
    $user_email = $this->input->post('user_email');
    if ($newpass != "") {
    $this->form_validation->set_rules('new_password', 'New Password', 'trim|required|xss_clean|min_length[6]|max_length[14]');
    //$this->form_validation->set_rules('confirm_password1', 'Confirm Password', 'trim|required|xss_clean|matches[new_password]');
    if (!preg_match('/(?=.*[a-z])(?=.*[0-9]).{6,}/i', $this->input->post('new_password'))) {
    $response = array('status' => 0, 'message' => "The Password Should be required alphabetic and numeric");
    echo json_encode($response);
    exit;
    }
    }
    $where_id = $this->input->post('id');
    #print_r($where_id);die('ajay');
    if ($this->form_validation->run() == FALSE):
    $messages = (validation_errors()) ? validation_errors() : '';
    $response = array('status' => 0, 'message' => $messages);
    else:
    $option = array(
    'table' => USERS,
    'select' => 'email',
    'where' => array('email' => $user_email, 'id !=' => $where_id)
    );
    $is_unique_email = $this->common_model->customGet($option);
    if (empty($is_unique_email)) {
    $this->filedata['status'] = 1;
    $image = $this->input->post('exists_image');
    if (!empty($_FILES['user_image']['name'])) {
    $this->filedata = $this->commonUploadImage($_POST, 'users', 'user_image');
    if ($this->filedata['status'] == 1) {
    $image = 'uploads/users/' . $this->filedata['upload_data']['file_name'];
    unlink_file($this->input->post('exists_image'), FCPATH);
    }
    }
    if ($this->filedata['status'] == 0) {
    $response = array('status' => 0, 'message' => $this->filedata['error']);
    } else {
    if (empty($newpass)) {
    $currentPass = $this->input->post('current_password');
    } else {
    $currentPass = $newpass;
    }
    $options_data = array(
    'title' => $this->input->post('title'),
    'description' => $this->input->post('description'),
    'last_name' => $this->input->post('last_name'),
    'date_of_birth' => "0000-00-00",
    'gender' => "OTHER",
    'phone' => $this->input->post('phone_no'),
    'profile_pic' => $image,
    'email' => $user_email,
    'zipcode_access' => json_encode($this->input->post('zipcode')),
    'facility_manager_id' => $this->input->post('facility_manager_id'),
    'is_pass_token' => $currentPass,
    );
    $this->ion_auth->update($where_id, $options_data);
    $additional_data_profile = array(
    'description' => $this->input->post('description'),
    'designation' => $this->input->post('designation'),
    'website' => $this->input->post('website'),
    'country' => $this->input->post('country'),
    'state' => $this->input->post('state'),
    'city' => $this->input->post('city'),
    'address1' => $this->input->post('address1'),
    'category_id' => (!empty($this->input->post('category_id'))) ? implode(",", $this->input->post('category_id')) : "",
    'company_name' => $this->input->post('company_name'),
    'profile_pic' => $image,
    'update_date' => date('Y-m-d H:i:s')
    );
    $this->db->where("user_id", $where_id);
    $this->db->update('vendor_sale_user_profile', $additional_data_profile);
    if ($newpass != "") {
    $change = $this->ion_auth->change_password($user_email, $this->input->post('current_password'), $this->input->post('new_password'));
    // $pass_new = $this->common_model->encryptPassword($this->input->post('new_password'));
    //$this->common_model->customUpdate(array('table' => 'users', 'data' => array('password' => $pass_new), 'where' => array('id' => $where_id)));
    }
    $response = array('status' => 1, 'message' => 'updated Successfully', 'url' => base_url('mdSteward/edit'), 'id' => encoding($this->input->post('id')));
    }
    } else {
    $response = array('status' => 0, 'message' => "The email address already exists");
    }
    endif;
    echo json_encode($response);
    } */

    public function updateAccountStatus()
    {
        $id = decoding($this->input->post('userId'));
        $status = $this->input->post('status');
        if ($status == "No") {
            $status = 0;
        } else {
            $status = 1;
        }
        $update = $this->common_model->customUpdate(array('table' => 'users', 'data' => array('active' => $status), 'where' => array('id' => $id)));
        if ($update) {
            $response = array('status' => 1, 'message' => "Vendor Verified Successfully");
        } else {
            $response = array('status' => 0, 'message' => "Error");
        }
        echo json_encode($response);
    }

    /**
     * @method export_user
     * @description export users
     * @return array
     */
    public function export_user()
    {
        $option = array(
            'table' => USERS,
            'select' => '*'
        );
        $users = $this->common_model->customGet($option);
        $print_array = array();
        $i = 1;
        foreach ($users as $value) {
            $print_array[] = array('s_no' => $i, 'name' => $value->name, 'email' => $value->email);
            $i++;
        }
        $filename = "user_email_csv.csv";
        $fp = fopen('php://output', 'w');
        header('Content-type: application/csv');
        header('Content-Disposition: attachment; filename=' . $filename);
        fputcsv($fp, array('S.no', 'User Name', 'Email'));
        foreach ($print_array as $row) {
            fputcsv($fp, $row);
        }
    }

    /**
     * @method reset_password
     * @description reset password
     * @return array
     */
    public function reset_password()
    {
        $user_id_encode = $this->uri->segment(3);

        $data['id_user_encode'] = $user_id_encode;

        if (!empty($_POST) && isset($_POST)) {

            $user_id_encode = $_POST['user_id'];

            if (!empty($user_id_encode)) {

                $user_id = base64_decode(base64_decode(base64_decode(base64_decode($user_id_encode))));


                $this->form_validation->set_rules('new_password', 'Password', 'required');
                $this->form_validation->set_rules('confirm_password', 'Confirm Password', 'required|matches[new_password]');

                if ($this->form_validation->run() == FALSE) {
                    $this->load->view('reset_password', $data);
                } else {


                    $user_pass = $_POST['new_password'];

                    $data1 = array('password' => md5($user_pass));
                    $where = array('id' => $user_id);

                    $out = $this->common_model->updateFields(USERS, $data1, $where);



                    if ($out) {

                        $this->session->set_flashdata('passupdate', 'Password Successfully Changed.');
                        $data['success'] = 1;
                        $this->load->view('reset_password', $data);
                    } else {

                        $this->session->set_flashdata('error_passupdate', 'Password Already Changed.');
                        $this->load->view('reset_password', $data);
                    }
                }
            } else {

                $this->session->set_flashdata('error_passupdate', 'Unable to Change Password, Authentication Failed.');
                $this->load->view('reset_password');
            }
        } else {
            $this->load->view('reset_password', $data);
        }
    }

    /**
     * @method delVendors
     * @description delete vendors
     * @return array
     */

    public function delVendors()
    {
        $response = "";
        $id = ($this->input->post('create_date')); // delete id
        $table = $this->input->post('table'); //table name
        $id_name = $this->input->post('id_name'); // table field name
        if (!empty($table) && !empty($id) && !empty($id_name)) {
            $option = array(
                'table' => $table,
                'data' => array('delete_status' => 1),
                'where' => array($id_name => $id)
            );
        // $id = ($_GET['create_date']);
        // $option = "UPDATE vendor_sale_recommendation
        //                 SET delete_status = 1
        //                WHERE create_date = $id";
            $delete = $this->common_model->customUpdate($option);
            if ($delete) {
                $response = 200;
            } else
                $response = 400;
        } else {
            $response = 400;
        }
        echo $response;
    }

}

