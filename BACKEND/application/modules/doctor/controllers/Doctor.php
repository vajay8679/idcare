<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Doctor extends Common_Controller {

    public $data = array();
    public $file_data = "";
    public $_table = 'doctors';
    public $title = "Provider MD";

    public function __construct() {
        parent::__construct();
        $this->is_auth_admin();

    }

    /**
     * @method index
     * @description listing display
     * @return array
     */
    public function index() {
        $this->data['url'] = base_url() . $this->router->fetch_class();
        $this->data['pageTitle'] = "Add " . $this->title;
        $this->data['parent'] = $this->router->fetch_class();
        $this->data['model'] = $this->router->fetch_class();
        $this->data['title'] = $this->title;
        $this->data['tablePrefix'] = 'vendor_sale_' . $this->_table;
        $this->data['table'] = $this->_table;

       
         $CareUnitID = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : '';
         $Sql = "SELECT vendor_sale_users.doctor_id FROM vendor_sale_users WHERE vendor_sale_users.id = '$CareUnitID'"; 
         $careUnit_list_id = $this->common_model->customQuery($Sql);
         $care_unit_ids =[];
         foreach($careUnit_list_id as $values){
            $care_unit_ids= $values->doctor_id;
            }
         $transaction_array = str_replace(array('[','"',']') , '', $care_unit_ids);
         $careUnit_lists = explode(",", $transaction_array);
         $careunit_facility_counts =[];
         foreach($careUnit_lists as $uids){
         $Sql = "SELECT vendor_sale_doctors.id,vendor_sale_doctors.name,vendor_sale_doctors.email FROM vendor_sale_doctors WHERE vendor_sale_doctors.id ='$uids'"; 
         $careunit_facility_counts[] = $this->common_model->customQuery($Sql);
            }
         $arraySingle = call_user_func_array('array_merge', $careunit_facility_counts);
         $this->data['careUnit'] = $arraySingle;

         $Sql = "SELECT vendor_sale_doctors.facility_user_id FROM vendor_sale_doctors WHERE vendor_sale_doctors.facility_user_id ='$CareUnitID'"; 
         $careunit_facility_user_id = $this->common_model->customQuery($Sql);

         $this->data['careUnit_user_id'] = $careunit_facility_user_id;
         //print_r($this->data['careUnit_user_id']);die;


        $option = array('table' => $this->_table, 'where' => array('delete_status' => 0), 'order' => array('doctors.id' => 'desc'));
        $this->data['list'] = $this->common_model->customGet($option);
        $this->load->admin_render('list', $this->data, 'inner_script');
    }

    /**
     * @method open_model
     * @description load model box
     * @return array
     */
    function open_model() {
        $this->data['title'] = "Add " . $this->title;
        $this->data['formUrl'] = $this->router->fetch_class() . "/add";
        $this->load->view('add', $this->data);
    }

    /**
     * @method menu_category_add
     * @description add dynamic rows
     * @return array
     */
    public function add() {

        $CareUnitID = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : '';
        // $option = array('table' => 'care_unit', 'where' => array('delete_status' => 0, 'is_active' => 1), 'order' => array('name' => 'ASC'));
        $Sql = "SELECT vendor_sale_users.doctor_id FROM vendor_sale_users WHERE vendor_sale_users.id = '$CareUnitID'"; 
       
        $careUnit_list_id = $this->common_model->customQuery($Sql);
       // print_r($careUnit_list_id);die;
       
        $care_unit_ids =[];
       
        foreach($careUnit_list_id as $values){
           $care_unit_ids= $values->doctor_id;
           }
        //    print_r($care_unit_ids);die;
        $transaction_array = str_replace(array('[','"',']') , '', $care_unit_ids);
        $careUnit_lists = explode(",", $transaction_array);
        //print_r($careUnit_lists);die;
        $careunit_facility_counts =[];
        $unit_idss =[];
        foreach($careUnit_lists as $uids){
        $Sql = "SELECT vendor_sale_doctors.id,vendor_sale_doctors.name,vendor_sale_doctors.email FROM vendor_sale_doctors WHERE vendor_sale_doctors.id ='$uids'"; 
        $careunit_facility_counts[] = $this->common_model->customQuery($Sql);
        $jsonDecode = json_decode($careUnit_list_id[0]->doctor_id);
       // print_r($careunit_facility_counts);die;
        if(empty($jsonDecode)){
            $jsonDecode = array();
        }
      //print_r($jsonDecode);die;
        $unit_idss[]=$uids;
        //print_r($unit_idss);die;
           }


        $this->form_validation->set_rules('first_name', "First Name", 'required|trim');
        $this->form_validation->set_rules('last_name', "Last Name", 'required|trim');
        $this->form_validation->set_rules('email', "Email", 'valid_email|trim');
        if ($this->form_validation->run() == true) {
            $this->filedata['status'] = 1;
            $image = "";
            if (!empty($_FILES['image']['name'])) {
                $this->filedata = $this->commonUploadImage($_POST, 'submenu', 'image');
                if ($this->filedata['status'] == 1) {
                    $image = 'uploads/submenu/' . $this->filedata['upload_data']['file_name'];
                }
            }
            if ($this->filedata['status'] == 0) {
                $response = array('status' => 0, 'message' => $this->filedata['error']);
            } else {
                $option = array('table' => $this->_table, 'where' => array('email' => $this->input->post('email')));
                if (!$this->common_model->customGet($option)) {
                    $first_name = $this->input->post('first_name');
                    $last_name =  $this->input->post('last_name');
                    $options_data = array(
                        'name' => $first_name." ".$last_name,
                        'email' => $this->input->post('email'),
                        'facility_user_id' =>$CareUnitID,
                        'is_active' => 1,
                        'create_date' => datetime()
                    );
                    $option = array('table' => $this->_table, 'data' => $options_data);
                    if ($this->common_model->customInsert($option)) {

                        $Sql = "SELECT vendor_sale_doctors.id FROM vendor_sale_doctors 
                        WHERE vendor_sale_doctors.facility_user_id ='$CareUnitID'"; 
                    $careunit_facility_counts = $this->common_model->customQuery($Sql);
                   // print_r($careunit_facility_counts);die;
                    $arrayState = array();
                    foreach ($careunit_facility_counts as $value) {
                        $arrayState[] = $value->id;
                      }  
                     // print_r($arrayState);die;
                      //print_r('ffffffffff');die;
                      //print_r($arrayState);die;
                    //$jsonDecode = json_decode($careUnit_list_id[0]->care_unit_id);
                    $arrayMerge = array_unique(array_merge($arrayState, $jsonDecode));
                   
                   // print_r($arrayMerge);die;
                    
                    $jsonEncode = json_encode($arrayMerge);
                    //print_r($jsonEncode);die;
                    if($CareUnitID != 1){
                        $updateCids ="UPDATE vendor_sale_users SET doctor_id='$jsonEncode' WHERE vendor_sale_users.id = '$CareUnitID'";
                       // $this->common_model->customQuery($updateCids,false, true);
                    }
                   // print_r($updateCids);die;

                       $this->common_model->customQuery($updateCids);


                        $response = array('status' => 1, 'message' => "Successfully added", 'url' => base_url($this->router->fetch_class()));
                    } else {
                        $response = array('status' => 0, 'message' => "Failed to add");
                    }
                } else {
                    $response = array('status' => 0, 'message' => "Email already exists");
                }
            }
        } else {
            $messages = (validation_errors()) ? validation_errors() : '';
            $response = array('status' => 0, 'message' => $messages);
        }
        echo json_encode($response);
    }

    /**
     * @method menu_category_edit
     * @description edit dynamic rows
     * @return array
     */
    public function edit() {
        $this->data['title'] = "Edit " . $this->title;
        $this->data['formUrl'] = $this->router->fetch_class() . "/update";
        $id = decoding($this->input->post('id'));
        if (!empty($id)) {
            $option = array(
                'table' => $this->_table,
                'where' => array('id' => $id),
                'single' => true
            );
            $results_row = $this->common_model->customGet($option);
            if (!empty($results_row)) {
                $this->data['results'] = $results_row;
                $this->load->view('edit', $this->data);
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
     * @method menu_category_update
     * @description update dynamic rows
     * @return array
     */
    public function update() {

        $this->form_validation->set_rules('name', "User Name", 'required|trim');
        $this->form_validation->set_rules('email', "Email", 'valid_email|trim');
        $where_id = $this->input->post('id');
        if ($this->form_validation->run() == FALSE):
            $messages = (validation_errors()) ? validation_errors() : '';
            $response = array('status' => 0, 'message' => $messages);
        else:
            $this->filedata['status'] = 1;
            $image = $this->input->post('exists_image');

            if (!empty($_FILES['image']['name'])) {
                $this->filedata = $this->commonUploadImage($_POST, 'submenu', 'image');
                if ($this->filedata['status'] == 1) {
                    $image = 'uploads/submenu/' . $this->filedata['upload_data']['file_name'];
                    delete_file($this->input->post('exists_image'), FCPATH);
                }
            }
            if ($this->filedata['status'] == 0) {
                $response = array('status' => 0, 'message' => $this->filedata['error']);
            } else {

                $option = array('table' => $this->_table, 'where' => array('email' => $this->input->post('email'), 'id !=' => $where_id,'delete_status'=>0));
                if (!$this->common_model->customGet($option)) {
                    $options_data = array(
                        'name' => $this->input->post('name'),
                        'email' => $this->input->post('email'),
                    );
                    $option = array(
                        'table' => $this->_table,
                        'data' => $options_data,
                        'where' => array('id' => $where_id)
                    );
                    $update = $this->common_model->customUpdate($option);
                    $response = array('status' => 1, 'message' => "Successfully updated", 'url' => base_url($this->router->fetch_class()));
                } else {
                    $response = array('status' => 0, 'message' => "Email already exists"); 
                }
            }
        endif;

        echo json_encode($response);
    }

}
