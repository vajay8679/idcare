<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Pwfpanel extends Common_Controller {

    public $data = "";

    function __construct() {

        parent::__construct();
        $this->load->library(array('ion_auth'));
        $this->load->helper(array('language'));
        $this->form_validation->set_error_delimiters($this->config->item('error_start_delimiter', 'ion_auth'), $this->config->item('error_end_delimiter', 'ion_auth'));
        $this->lang->load('auth');
    }

    public function index() {
        $data['parent'] = "Dashboard";
        if (!$this->ion_auth->logged_in()) {
            //$this->session->set_flashdata('message', 'Your session has been expired');
            redirect('pwfpanel/login', 'refresh');
        } else {
            if ($this->ion_auth->is_admin() || $this->ion_auth->is_subAdmin()) {

                $data['careUnit'] = $this->common_model->customCount(array('table' => 'care_unit', 'select' => 'id,name', 'where' => array('is_active' => 1, 'delete_status' => 0)));
                $data['initial_dx'] = $this->common_model->customCount(array('table' => 'initial_dx', 'select' => 'id,name', 'where' => array('is_active' => 1, 'delete_status' => 0)));
                $data['initial_rx'] = $this->common_model->customCount(array('table' => 'initial_rx', 'select' => 'id,name', 'where' => array('is_active' => 1, 'delete_status' => 0)));
                $data['doctors'] = $this->common_model->customCount(array('table' => 'doctors', 'select' => 'id,name', 'where' => array('is_active' => 1, 'delete_status' => 0)));
                $option = array('table' => USERS . ' as user',
                    'select' => 'user.id',
                    'join' => array(array(USER_GROUPS . ' as ugroup', 'ugroup.user_id=user.id', 'left'),
                        array(GROUPS . ' as group', 'group.id=ugroup.group_id', 'left'),
                        array('user_profile UP', 'UP.user_id=user.id', 'left')),
                    'order' => array('user.id' => 'ASC'),
                    'where' => array('user.delete_status' => 0,
                        'group.id' => 3),
                    'order' => array('user.id' => 'desc'),
                );
                $data['total_md_steward'] = $this->common_model->customCount($option);
                $option = array('table' => "patient P",
                    'select' => "P.id"
                );
                // print_r($this->data['total_md_steward']);die;
                
                $data['total_patient'] = $this->common_model->customCount($option);
                $option = array('table' => "patient P",
                    'select' => "P.id",
                    'where' => array('DATE(created_date)' => date('Y-m-d'))
                );
               
                $data['total_patient_today'] = $this->common_model->customCount($option);
                if ($this->ion_auth->is_subAdmin()) {
                    redirect('patient', 'refresh');
                }
                /* else if ($this->ion_auth->is_facilityManager()) {
                    //redirect('patient', 'refresh');
                    $this->load->admin_render('dashboard', $data);
                } */else{
                   // print_r($data);die;
                    $this->load->admin_render('dashboard', $data);
                    
                }
            } else if ($this->ion_auth->is_vendor()) {
                $this->load->admin_render('vendorDashboard', $this->data, 'inner_script');
                
            } else if($this->ion_auth->is_facilityManager()){
             



           /*     $AdminCareUnitID = isset($_SESSION['admin_care_unit_id']) ? $_SESSION['admin_care_unit_id'] : '';
                $option = array('table' => 'care_unit', 'where' => array('delete_status' => 0, 'is_active' => 1), 'order' => array('name' => 'ASC'));
                if (!empty($AdminCareUnitID)) {
        
                    $option['where']['id'] = $AdminCareUnitID;
                }
        
                    $careunit_facility_count = json_decode($AdminCareUnitID);
                    $user_facility_count =count($careunit_facility_count); */
                //  $data['careUnit'] = $this->common_model->customCount(array('table' => 'care_unit', 'select' => 'id,name', 'where' => array('is_active' => 1, 'delete_status' => 0)));
               // $data['careUnit'] = $user_facility_count;

                $CareUnitID = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : '';
                $Sql = "SELECT vendor_sale_users.care_unit_id FROM vendor_sale_users WHERE vendor_sale_users.id = '$CareUnitID'"; 
                $careUnit_list_id = $this->common_model->customQuery($Sql);
                $care_unit_ids =[];
                foreach($careUnit_list_id as $values){
                   $care_unit_ids= $values->care_unit_id;
                   }

                $transaction_array = str_replace(array('[','"',']') , '', $care_unit_ids);
                $careUnit_lists = explode(",", $transaction_array);
              //  print_r($y);die;
                $careunit_facility_counts =[];
                foreach($careUnit_lists as $uids){
                $Sql = "SELECT vendor_sale_care_unit.id,vendor_sale_care_unit.care_unit_code,vendor_sale_care_unit.name,vendor_sale_care_unit.email FROM vendor_sale_care_unit WHERE vendor_sale_care_unit.id ='$uids'"; 
                $careunit_facility_counts[] = $this->common_model->customQuery($Sql);
                   }
                $arraySingle = call_user_func_array('array_merge', $careunit_facility_counts);
                $y=count($arraySingle);
                //print_r($y);die;
               // $this->data['careUnit'] = $arraySingle;
                $data['careUnit'] = $y;

                 
                $data['initial_dx'] = $this->common_model->customCount(array('table' => 'initial_dx', 'select' => 'id,name', 'where' => array('is_active' => 1, 'delete_status' => 0)));
                $data['initial_rx'] = $this->common_model->customCount(array('table' => 'initial_rx', 'select' => 'id,name', 'where' => array('is_active' => 1, 'delete_status' => 0)));
                $data['doctors'] = $this->common_model->customCount(array('table' => 'doctors', 'select' => 'id,name', 'where' => array('is_active' => 1, 'delete_status' => 0)));
                $option = array('table' => USERS . ' as user',
                    'select' => 'user.id',
                    'join' => array(array(USER_GROUPS . ' as ugroup', 'ugroup.user_id=user.id', 'left'),
                        array(GROUPS . ' as group', 'group.id=ugroup.group_id', 'left'),
                        array('user_profile UP', 'UP.user_id=user.id', 'left')),
                    'order' => array('user.id' => 'ASC'),
                    'where' => array('user.delete_status' => 0,
                        'group.id' => 5),
                    'order' => array('user.id' => 'desc'),
                );
                $data['total_md_steward'] = $this->common_model->customCount($option);

                $option = array('table' => "patient P",
                    'select' => "P.id"
                );
                // print_r($this->data['total_md_steward']);die;
                
                // $data['total_patient'] = $this->common_model->customCount($option);
                // $option = array('table' => "patient P",
                //     'select' => 'P.operator_id',
                //     'where' => array('DATE(created_date)' => date('Y-m-d'))
                // );
               
                $AdminCareUnitID = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : '';
                $Sql = "SELECT vendor_sale_patient.operator_id FROM vendor_sale_patient WHERE vendor_sale_patient.operator_id = $AdminCareUnitID";
                $careunit_facility_counts = $this->common_model->customQuery($Sql);
                $user_facility_counts =count($careunit_facility_counts);
                $data['total_patient'] = $user_facility_counts;
               // print_r($data['total_patient']);die;
               
               
               $date= date("Y-m-d");
               //print_r($date);die;
               $AdminCareUnitID = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : '';
               $Sql = "SELECT vendor_sale_patient.operator_id FROM vendor_sale_patient WHERE vendor_sale_patient.operator_id = $AdminCareUnitID AND  DATE(created_date) = '$date'" ;
               $careunit_facility_counts = $this->common_model->customQuery($Sql);
               $user_facility_counts =count($careunit_facility_counts);
               $data['total_patient_today'] = $user_facility_counts;

                /* $option = array('table' => "patient P",
                     'select' => 'P.operator_id',
                    'where' => array('DATE(created_date)' => date('Y-m-d'))
                 ); */
                //$data['total_patient_today'] = $this->common_model->customCount($option);
                redirect('reportsSummary', 'refresh');
               // $this->load->admin_render('dashboard', $data);

            } else {
                $this->session->set_flashdata('message', 'You are not authorised to access administration');
                redirect('pwfpanel/login', 'refresh');
            }
        }
    }

    public function getcharts() {
        $return = array();
        $return['status'] = 200;
        $filterval = $this->input->post('filterval');
        $year = date('Y');
        $where = " YEAR(U.`created_date`) = $year ";
        $where1 = " YEAR(U.`datetime`) = $year ";
        if ($filterval == "lastYear") {
            $year = date("Y", strtotime("-1 year"));
            $where = " YEAR(U.`created_date`) = $year ";
            $where1 = " YEAR(U.`datetime`) = $year ";
        }
        if ($filterval == "lastMonth") {
            $month = date("m", strtotime("-1 month"));
            $where = " YEAR(U.`created_date`) = $year AND MONTH(U.`created_date`) = $month ";
            $where1 = " YEAR(U.`datetime`) = $year AND MONTH(U.`datetime`) = $month ";
        }
        if ($filterval == "currentMonth") {
            $month = date("m");
            $where = " YEAR(U.`created_date`) = $year AND MONTH(U.`created_date`) = $month ";
            $where1 = " YEAR(U.`datetime`) = $year AND MONTH(U.`datetime`) = $month ";
        }
        if ($filterval == "today") {
            $date = date("Y-m-d");
            $where = " DATE(U.`created_date`) = $date ";
            $where1 = " DATE(U.`datetime`) = $date ";
        }

        $Sql = "SELECT COUNT(U.id) total ,MONTH(U.`created_date`) as months
        FROM  vendor_sale_patient U
        WHERE   $where 
        GROUP BY  MONTH(U.`created_date`)";
        $TotalVendors = $this->common_model->customQuery($Sql);
        $vendor = array();
        $totalv = 0;
        foreach ($TotalVendors as $rows) {
            $temp = array();
            $temp[] = $rows->months;
            $temp[] = $rows->total;
            $vendor[] = $temp;
            $totalv = $totalv + $rows->total;
        }

        $return['vendors'] = $vendor;

        $return['totalv'] = $totalv;

        echo json_encode($return);
    }

    public function getTablesVendor() {
        $return = array();
        $return['status'] = 200;
        $filterval = $this->input->post('filterval');
        $year = date('Y');
        $where = " YEAR(U.`created_date`) = $year ";
        $where1 = " YEAR(U.`datetime`) = $year ";
        if ($filterval == "lastYear") {
            $year = date("Y", strtotime("-1 year"));
            $where = " YEAR(U.`created_date`) = $year ";
            $where1 = " YEAR(U.`datetime`) = $year ";
        }
        if ($filterval == "lastMonth") {
            $month = date("m", strtotime("-1 month"));
            $where = " YEAR(U.`created_date`) = $year AND MONTH(U.`created_date`) = $month ";
            $where1 = " YEAR(U.`datetime`) = $year AND MONTH(U.`datetime`) = $month ";
        }
        if ($filterval == "currentMonth") {
            $month = date("m");
            $where = " YEAR(U.`created_date`) = $year AND MONTH(U.`created_date`) = $month ";
            $where1 = " YEAR(U.`datetime`) = $year AND MONTH(U.`datetime`) = $month ";
        }
        if ($filterval == "today") {
            $date = date("Y-m-d");
            $where = " DATE(U.`created_date`) = $date ";
            $where1 = " DATE(U.`datetime`) = $date ";
        }

        $Sql = "SELECT MONTH(U.`created_date`) as months,U.*
        FROM  vendor_sale_users U  INNER JOIN vendor_sale_users_groups UG on UG.user_id=U.id
        WHERE   $where AND UG.group_id =3 ";
        $return['vendors'] = $this->common_model->customQuery($Sql);
        $this->load->view('table_vendor', $return);
    }

    public function getTablesUsers() {
        $return = array();
        $return['status'] = 200;
        $filterval = $this->input->post('filterval');
        $year = date('Y');
        $where = " YEAR(U.`created_date`) = $year ";
        $where1 = " YEAR(U.`datetime`) = $year ";
        if ($filterval == "lastYear") {
            $year = date("Y", strtotime("-1 year"));
            $where = " YEAR(U.`created_date`) = $year ";
            $where1 = " YEAR(U.`datetime`) = $year ";
        }
        if ($filterval == "lastMonth") {
            $month = date("m", strtotime("-1 month"));
            $where = " YEAR(U.`created_date`) = $year AND MONTH(U.`created_date`) = $month ";
            $where1 = " YEAR(U.`datetime`) = $year AND MONTH(U.`datetime`) = $month ";
        }
        if ($filterval == "currentMonth") {
            $month = date("m");
            $where = " YEAR(U.`created_date`) = $year AND MONTH(U.`created_date`) = $month ";
            $where1 = " YEAR(U.`datetime`) = $year AND MONTH(U.`datetime`) = $month ";
        }

        if ($filterval == "today") {
            $date = date("Y-m-d");
            $where = " DATE(U.`created_date`) = $date ";
            $where1 = " DATE(U.`datetime`) = $date ";
        }

        $Sql = "SELECT MONTH(U.`created_date`) as months,U.*
        FROM  vendor_sale_users U  INNER JOIN vendor_sale_users_groups UG on UG.user_id=U.id
        WHERE   $where AND UG.group_id =2 ";
        $return['users'] = $this->common_model->customQuery($Sql);
        $this->load->view('table_user', $return);
    }

    public function getTablesEnquiries() {
        $return = array();
        $return['status'] = 200;
        $filterval = $this->input->post('filterval');
        $year = date('Y');
        $where = " YEAR(U.`created_date`) = $year ";
        $where1 = " YEAR(CU.`datetime`) = $year ";
        if ($filterval == "lastYear") {
            $year = date("Y", strtotime("-1 year"));
            $where = " YEAR(U.`created_date`) = $year ";
            $where1 = " YEAR(CU.`datetime`) = $year ";
        }
        if ($filterval == "lastMonth") {
            $month = date("m", strtotime("-1 month"));
            $where = " YEAR(U.`created_date`) = $year AND MONTH(U.`created_date`) = $month ";
            $where1 = " YEAR(CU.`datetime`) = $year AND MONTH(CU.`datetime`) = $month ";
        }
        if ($filterval == "currentMonth") {
            $month = date("m");
            $where = " YEAR(U.`created_date`) = $year AND MONTH(U.`created_date`) = $month ";
            $where1 = " YEAR(CU.`datetime`) = $year AND MONTH(CU.`datetime`) = $month ";
        }

        if ($filterval == "today") {
            $date = date("Y-m-d");
            $where = " DATE(U.`created_date`) = $date ";
            $where1 = " DATE(U.`datetime`) = $date ";
        }

        $Sql = "SELECT U.*,CU.id as inq_id,CU.email as clinet_email,CU.rq_licenses,CU.rq_software_categories,
        CU.rq_expected_live,CU.rq_solution_offering,CU.description,CU.datetime as enquiry_date,
        P.company_name,UP.first_name as c_first_name,UP.last_name as c_last_name
        FROM  vendor_sale_client_inquiry CU INNER JOIN  vendor_sale_users U ON U.id=CU.vendor_id
        INNER JOIN vendor_sale_users UP ON UP.id=CU.user_id 
        INNER JOIN vendor_sale_user_profile P ON P.user_id=U.id
        WHERE  $where1 AND CU.is_request_draft = 'no'
        ";
        $return['enquiries'] = $this->common_model->customQuery($Sql);
        $this->load->view('table_enquiries', $return);
    }

    public function logAuth() {
        $this->data['title'] = $this->lang->line('login_heading');
        $this->load->view('login_new', $this->data);
    }

    /**
     * @method login
     * @description login authentication
     * @return array
     */
    public function login() {
        $this->data['title'] = $this->lang->line('login_heading');
        $this->form_validation->set_rules('identity', str_replace(':', '', $this->lang->line('login_identity_label')), 'required');
        $this->form_validation->set_rules('password', str_replace(':', '', $this->lang->line('login_password_label')), 'required');
        if (strtolower(getConfig('google_captcha')) == 'on') {
            $this->form_validation->set_rules('g-recaptcha-response', 'Google recaptcha', 'required');
        }
        if ($this->form_validation->run() == true) {
            $is_captcha = true;
            if (strtolower(getConfig('google_captcha')) == 'on') {
                if (isset($_POST['g-recaptcha-response']) && !empty($_POST['g-recaptcha-response'])) {
                    $secret = getConfig('secret_key');
                    $verifyResponse = file_get_contents('https://www.google.com/recaptcha/api/siteverify?secret=' . $secret . '&response=' . $_POST['g-recaptcha-response']);
                    $responseData = json_decode($verifyResponse);
                    $is_captcha = $responseData->success;
                }
            }
            if ($is_captcha) {
                $remember = (bool) $this->input->post('remember');
                if ($this->ion_auth->login($this->input->post('identity'), $this->input->post('password'), $remember)) {
                    if ($this->ion_auth->is_admin()) {
                        $option = array(
                            'table' => 'users',
                            'select' => 'users.id',
                            'join' => array('users_groups' => 'users_groups.user_id=users.id',
                                'groups' => 'groups.id=users_groups.group_id'),
                            'where' => array('users.email' => $this->input->post('identity'),
                                'groups.id' => 1),
                        );
                        $isAdmin = $this->common_model->customGet($option);
                    } else if ($this->ion_auth->is_subAdmin()) {
                        $option = array(
                            'table' => 'users',
                            'select' => 'users.id,users.care_unit_id',
                            'join' => array('users_groups' => 'users_groups.user_id=users.id',
                                'groups' => 'groups.id=users_groups.group_id'),
                            'where' => array('users.email' => $this->input->post('identity'),
                                'groups.id' => 4),
                        );
                        $isAdmin = $this->common_model->customGet($option);
                        if (!empty($isAdmin)) {
                            $_SESSION['admin_care_unit_id']=$isAdmin[0]->care_unit_id;
                            $option = array(
                                'table' => 'login_session',
                                'where' => array(
                                    'user_id' => $isAdmin[0]->id
                                ),
                            );
                            $isLogin = $this->common_model->customGet($option);
                            if (!empty($isLogin) or empty($isLogin)) {
                               /*  $option = array(
                                    'table' => 'login_session',
                                    'data' => array(
                                        'login_session_key' => get_guid(),
                                        'login_ip' => $_SERVER['REMOTE_ADDR'],
                                        'last_login' => time()
                                    ),
                                    'where' => array('user_id' => $isAdmin[0]->id)
                                );
                                $this->common_model->customUpdate($option);
                            } else { */
                                $option = array(
                                    'table' => 'login_session',
                                    'data' => array(
                                        'login_session_key' => get_guid(),
                                        'user_id' => $isAdmin[0]->id,
                                        'login_ip' => $_SERVER['REMOTE_ADDR'],
                                        'last_login' => time()
                                    ),
                                );
                                $this->common_model->customInsert($option);
                            }
                        }
                    }
                    else if ($this->ion_auth->is_facilityManager()) {
                        $option = array(
                            'table' => 'users',
                            'select' => 'users.id,users.care_unit_id',
                            'join' => array('users_groups' => 'users_groups.user_id=users.id',
                                'groups' => 'groups.id=users_groups.group_id'),
                            'where' => array('users.email' => $this->input->post('identity'),
                                'groups.id' => 5),
                        );
                        $isAdmin = $this->common_model->customGet($option);
                        if (!empty($isAdmin)) {
                            $_SESSION['admin_care_unit_id']=$isAdmin[0]->care_unit_id;
                            $option = array(
                                'table' => 'login_session',
                                'where' => array(
                                    'user_id' => $isAdmin[0]->id
                                ),
                            );
                            $isLogin = $this->common_model->customGet($option);
                            if (!empty($isLogin) or empty($isLogin)) {
                               /*  $option = array(
                                    'table' => 'login_session',
                                    'data' => array(
                                        'login_session_key' => get_guid(),
                                        'login_ip' => $_SERVER['REMOTE_ADDR'],
                                        'last_login' => time()
                                    ),
                                    'where' => array('user_id' => $isAdmin[0]->id)
                                );
                                $this->common_model->customUpdate($option);
                            } else { */
                                $option = array(
                                    'table' => 'login_session',
                                    'data' => array(
                                        'login_session_key' => get_guid(),
                                        'user_id' => $isAdmin[0]->id,
                                        'login_ip' => $_SERVER['REMOTE_ADDR'],
                                        'last_login' => time()
                                    ),
                                );
                                $this->common_model->customInsert($option);
                            }
                        }
                    }
                    if (empty($isAdmin)) {
                        $this->session->set_flashdata('message', "Incorrect Login");
                        redirect('pwfpanel/login');
                    }
                    $this->session->set_flashdata('message', $this->ion_auth->messages());
                    redirect('pwfpanel', 'refresh');
                } else {
                    $this->session->set_flashdata('message', $this->ion_auth->errors());
                    redirect('pwfpanel/login', 'refresh'); // use redirects instead of loading views for compatibility with MY_Controller libraries
                }
            } else {
                $this->session->set_flashdata('message', "Robot verification failed, please try again");
                redirect('pwfpanel/login', 'refresh');
            }
        } else {
            $this->data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');
            $this->data['identity'] = array('name' => 'identity',
                'id' => 'identity',
                'type' => 'text',
                'value' => $this->form_validation->set_value('identity'),
                'placeholder' => 'Identity'
            );
            $this->data['password'] = array('name' => 'password',
                'id' => 'password',
                'type' => 'password',
                'placeholder' => 'Password'
            );
            $this->data['parent'] = "Login";
            $this->data['title'] = "Login";
            $this->load->view('login', $this->data);
        }
    }

    /**
     * @method vendorLogin
     * @description login authentication
     * @return array
     */
    public function vendorLogin() {
        $this->data['title'] = $this->lang->line('login_heading');
        $this->form_validation->set_rules('identity', str_replace(':', '', $this->lang->line('login_identity_label')), 'required');
        $this->form_validation->set_rules('password', str_replace(':', '', $this->lang->line('login_password_label')), 'required');

        if (strtolower(getConfig('google_captcha')) == 'on') {
            $this->form_validation->set_rules('g-recaptcha-response', 'Google recaptcha', 'required');
        }

        if ($this->form_validation->run() == true) {

            $is_captcha = true;
            if (strtolower(getConfig('google_captcha')) == 'on') {
                if (isset($_POST['g-recaptcha-response']) && !empty($_POST['g-recaptcha-response'])) {
                    $secret = getConfig('secret_key');
                    $verifyResponse = file_get_contents('https://www.google.com/recaptcha/api/siteverify?secret=' . $secret . '&response=' . $_POST['g-recaptcha-response']);
                    $responseData = json_decode($verifyResponse);
                    $is_captcha = $responseData->success;
                }
            }

            if ($is_captcha) {

                $remember = (bool) $this->input->post('remember');

                if ($this->ion_auth->login($this->input->post('identity'), $this->input->post('password'), $remember)) {
                    $option = array(
                        'table' => 'users',
                        'select' => 'users.id',
                        'join' => array('users_groups' => 'users_groups.user_id=users.id',
                            'groups' => 'groups.id=users_groups.group_id'),
                        'where' => array('users.email' => $this->input->post('identity'),
                            'groups.id' => 3),
                    );
                    $isAdmin = $this->common_model->customGet($option);
                    if (empty($isAdmin)) {
                        $this->session->set_flashdata('message', "Incorrect Login");
                        redirect('site/authVendorLogin');
                    }
                    $this->session->set_flashdata('message', $this->ion_auth->messages());

                    redirect('pwfpanel', 'refresh');
                } else {
                    $this->session->set_flashdata('message', $this->ion_auth->errors());
                    redirect('site/authVendorLogin', 'refresh'); // use redirects instead of loading views for compatibility with MY_Controller libraries
                }
            } else {
                $this->session->set_flashdata('message', "Robot verification failed, please try again");
                redirect('site/authVendorLogin', 'refresh');
            }
        } else {
            $this->data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');
            $this->data['identity'] = array('name' => 'identity',
                'id' => 'identity',
                'type' => 'text',
                'value' => $this->form_validation->set_value('identity'),
                'placeholder' => 'Identity'
            );
            $this->data['password'] = array('name' => 'password',
                'id' => 'password',
                'type' => 'password',
                'placeholder' => 'Password'
            );
            $this->load->view('vendor_login', $this->data);
        }
    }

    /**
     * @method authSecurityLogin
     * @description security point double check login point configuration
     * @return array
     */
    function authSecurityLogin() {
        $this->form_validation->set_rules('identity', str_replace(':', '', $this->lang->line('login_identity_label')), 'required');
        $this->form_validation->set_rules('password', str_replace(':', '', $this->lang->line('login_password_label')), 'required');
        if ($this->form_validation->run() == true) {
            if ($this->ion_auth->login($this->input->post('identity'), $this->input->post('password'), false)) {
                $this->session->set_userdata('isConfigLoggedIn', TRUE);
                $this->session->set_flashdata('success', "Successfully security unlock");
                redirect('configuration');
            } else {
                $this->session->set_flashdata('errMessage', "Incorrect Login");
                redirect('configuration');
            }
        } else {
            $errMessage = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');
            $this->session->set_flashdata('errMessage', $errMessage);
            redirect('configuration');
        }
    }

    /**
     * @method logout
     * @description logout
     * @return array
     */
    public function logout() {
        $this->data['title'] = "Logout";
        $logout = $this->ion_auth->logout();
        $this->session->set_flashdata('message', $this->ion_auth->messages());
        $logoutUrl = base_url() . 'pwfpanel/login';
        if ($this->ion_auth->is_admin()) {
            $logoutUrl = base_url() . 'pwfpanel/login';
        } else if ($this->ion_auth->is_vendor()) {
            $logoutUrl = base_url() . 'site/authVendorLogin';
        } else if ($this->ion_auth->is_subAdmin()) {
            $logoutUrl = base_url() . 'pwfpanel/login';
        }
        else if ($this->ion_auth->is_facilityManager()) {
            $logoutUrl = base_url() . 'pwfpanel/login';
        }
        $this->session->unset_userdata('isConfigLoggedIn');
        $response = array('status' => 1, 'message' => $this->ion_auth->messages(), 'url' => $logoutUrl);
        echo json_encode($response);
    }

    /**
     * @method profile
     * @description profile display
     * @return array
     */
    public function profile() {
        $this->data['parent'] = "Profile";
        $this->adminIsAuth();
        $option = array(
            'table' => 'users',
            'where' => array('id' => $this->session->userdata('user_id')),
            'single' => true
        );
        $this->data['user'] = $this->common_model->customGet($option);
        $this->data['title'] = "Profile";
        $this->load->admin_render('profile', $this->data);
    }

    /**
     * @method updateProfile
     * @description user profile update
     * @return array
     */
    public function updateProfile() {
        $this->adminIsAuth();
        $this->form_validation->set_rules('first_name', 'First Name', 'required');
        $this->form_validation->set_rules('last_name', "Last Name", 'required');
        if ($this->form_validation->run() == true) {

            $additional_data = array(
                'first_name' => $this->input->post('first_name'),
                'last_name' => $this->input->post('last_name')
            );
            if ($this->ion_auth->update($this->session->userdata('user_id'), $additional_data)) {
                $this->session->set_flashdata('message', 'your profile account has been updated successfully');
                redirect('pwfpanel/profile');
            } else {
                $this->session->set_flashdata('message', 'your profile account has been updated successfully');
                redirect('pwfpanel/profile');
            }
        } else {
            $requireds = strip_tags($this->form_validation->error_string());
            $result = explode("\n", trim($requireds, "\n"));
            $this->session->set_flashdata('error', $result);
            redirect('pwfpanel/profile/');
        }
    }

    /**
     * @method password
     * @description change password dispaly
     * @return array
     */
    public function password() {
        $this->data['parent'] = "Password";
        $this->adminIsAuth();
        $this->data['error'] = "";
        $this->data['message'] = "";
        $this->data['min_password_length'] = $this->config->item('min_password_length', 'ion_auth');
        $this->data['old_password'] = array(
            'name' => 'old',
            'id' => 'old',
            'type' => 'password',
            'class' => 'form-control'
        );
        $this->data['new_password'] = array(
            'name' => 'new',
            'id' => 'new',
            'type' => 'password',
            'pattern' => '^.{' . $this->data['min_password_length'] . '}.*$',
            'class' => 'form-control'
        );
        $this->data['new_password_confirm'] = array(
            'name' => 'new_confirm',
            'id' => 'new_confirm',
            'type' => 'password',
            'pattern' => '^.{' . $this->data['min_password_length'] . '}.*$',
            'class' => 'form-control'
        );
        $this->data['user_id'] = array(
            'name' => 'user_id',
            'id' => 'user_id',
            'type' => 'hidden',
            'value' => $this->session->userdata('user_id'),
        );
        $this->data['title'] = "Password";
        $this->load->admin_render('changePassword', $this->data);
    }

    /**
     * @method change_password
     * @description change password
     * @return array
     */
    public function change_password() {
        $data['parent'] = "Password";
        $this->adminIsAuth();

        $data['title'] = "Password";
        $this->form_validation->set_rules('old', $this->lang->line('change_password_validation_old_password_label'), 'required');
        $this->form_validation->set_rules('new', $this->lang->line('change_password_validation_new_password_label'), 'required|min_length[6]|max_length[14]|matches[new_confirm]');
        $this->form_validation->set_rules('new_confirm', $this->lang->line('change_password_validation_new_password_confirm_label'), 'required');

        if (!$this->ion_auth->logged_in()) {
            redirect('pwfpanel/login', 'refresh');
        }

        $user = $this->ion_auth->user()->row();

        if ($this->form_validation->run() == false) {

            $data['error'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');

            $data['min_password_length'] = $this->config->item('min_password_length', 'ion_auth');
            $data['old_password'] = array(
                'name' => 'old',
                'id' => 'old',
                'type' => 'password',
                'class' => 'form-control'
            );
            $data['new_password'] = array(
                'name' => 'new',
                'id' => 'new',
                'type' => 'password',
                'pattern' => '^.{' . $data['min_password_length'] . '}.*$',
                'class' => 'form-control'
            );
            $data['new_password_confirm'] = array(
                'name' => 'new_confirm',
                'id' => 'new_confirm',
                'type' => 'password',
                'pattern' => '^.{' . $data['min_password_length'] . '}.*$',
                'class' => 'form-control'
            );
            $data['user_id'] = array(
                'name' => 'user_id',
                'id' => 'user_id',
                'type' => 'hidden',
                'value' => $this->session->userdata('user_id'),
            );
            $this->load->admin_render('changePassword', $data);
        } else {

            $identity = $this->session->userdata('identity');

            $change = $this->ion_auth->change_password($identity, $this->input->post('old'), $this->input->post('new'));

            if ($change) {
                //if the password was successfully changed
                $this->session->set_flashdata('message', "The new password has been saved successfully.");
                redirect('pwfpanel/password');
            } else {
                $this->session->set_flashdata('error', "The old password you entered was incorrect");
                redirect('pwfpanel/change_password');
            }
        }
    }

    /**
     * @method forgot_password
     * @description forgot password
     * @return array
     */
    public function forgot_password() {
        $this->data['parent'] = "Forgot Password";
        if ($this->config->item('identity', 'ion_auth') != 'email') {
            $this->form_validation->set_rules('identity', $this->lang->line('forgot_password_identity_label'), 'required');
        } else {
            $this->form_validation->set_rules('identity', $this->lang->line('forgot_password_validation_email_label'), 'required|valid_email');
        }
        if ($this->form_validation->run() == false) {
            $this->data['type'] = $this->config->item('identity', 'ion_auth');
            $this->data['identity'] = array('name' => 'identity',
                'id' => 'identity',
                'placeholder' => 'Email',
                'class' => 'form-control'
            );
            if ($this->config->item('identity', 'ion_auth') != 'email') {
                $this->data['identity_label'] = $this->lang->line('forgot_password_identity_label');
            } else {
                $this->data['identity_label'] = $this->lang->line('forgot_password_email_identity_label');
            }
            $this->data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');
            $this->load->view('forgot_password', $this->data);
        } else {
            $identity_column = $this->config->item('identity', 'ion_auth');
            $identity = $this->ion_auth->where($identity_column, $this->input->post('identity'))->users()->row();
            if (empty($identity)) {
                if ($this->config->item('identity', 'ion_auth') != 'email') {
                    $this->ion_auth->set_error('forgot_password_identity_not_found');
                } else {
                    $this->ion_auth->set_error('forgot_password_email_not_found');
                }
                $this->session->set_flashdata('message', $this->ion_auth->errors());
                redirect("pwfpanel/forgot_password", 'refresh');
            }
            $forgotten = $this->ion_auth->forgotten_password($identity->{$this->config->item('identity', 'ion_auth')});
            if ($forgotten) {

                $dataArr = array();
                $dataArr['email'] = $forgotten['identity'];
                /* Get User Data From Users Table */
                $result = $this->common_model->getsingle(USERS, $dataArr);

                /** Verification email * */
                $EmailTemplate = getEmailTemplate("forgot_password");
                if (!empty($EmailTemplate)) {
                    $html = array();
                    $html['active_url'] = base_url() . "pwfpanel/reset_password/" . $forgotten['forgotten_password_code'];
                    $html['logo'] = base_url() . getConfig('site_logo');
                    $html['site'] = getConfig('site_name');
                    $html['site_meta_title'] = getConfig('site_meta_title');
                    $html['user'] = ucwords($result->first_name);
                    $html['email'] = $result->email;
                    $html['content'] = $EmailTemplate->description;
                    $email_template = $this->load->view('email-template/forgot_password', $html, true);
                    $title = '[' . getConfig('site_name') . '] ' . $EmailTemplate->title;
                    send_mail($email_template, $title, $forgotten['identity'], getConfig('admin_email'));
                }
                //$this->session->set_flashdata('message', $this->ion_auth->messages());
                $this->session->set_flashdata('message', "An email has been sent Reset Password link, please check spam folder if you do not received it in your inbox and add our mail id in your addressbook.");
                redirect("pwfpanel/login", 'refresh'); //we should display a confirmation 
            } else {
                $this->session->set_flashdata('message', $this->ion_auth->errors());
                redirect("pwfpanel/forgot_password", 'refresh');
            }
        }
    }

    /**
     * @method reset_password
     * @description reset password
     * @return array
     */
    public function reset_password($code = NULL) {
        if (!$code) {
            show_404();
        }

        $user = $this->ion_auth->forgotten_password_check($code);

        if ($user) {

            $this->form_validation->set_rules('new', $this->lang->line('reset_password_validation_new_password_label'), 'required|min_length[6]|max_length[14]|matches[new_confirm]');
            $this->form_validation->set_rules('new_confirm', $this->lang->line('reset_password_validation_new_password_confirm_label'), 'required');

            if (!preg_match('/(?=.*[a-z])(?=.*[0-9]).{6,}/i', $this->input->post('new'))) {
                if (isset($_POST['new'])) {
                    $this->data['message'] = "The Password Should be required alphabetic and numeric";
                }

                $this->data['min_password_length'] = $this->config->item('min_password_length', 'ion_auth');
                $this->data['new_password'] = array(
                    'name' => 'new',
                    'id' => 'new',
                    'type' => 'password',
                    'placeholder' => 'New Password',
                    'pattern' => '^.{' . $this->data['min_password_length'] . '}.*$',
                    'class' => 'form-control input-lg'
                );
                $this->data['new_password_confirm'] = array(
                    'name' => 'new_confirm',
                    'id' => 'new_confirm',
                    'type' => 'password',
                    'placeholder' => 'Confirm Password',
                    'pattern' => '^.{' . $this->data['min_password_length'] . '}.*$',
                    'class' => 'form-control input-lg'
                );
                $this->data['user_id'] = array(
                    'name' => 'user_id',
                    'id' => 'user_id',
                    'type' => 'hidden',
                    'value' => $user->id,
                );
                $this->data['csrf'] = $this->_get_csrf_nonce();
                $this->data['code'] = $code;


                $this->load->view('pwfpanel/reset_password', $this->data);
            } else if ($this->form_validation->run() == false) {

                $this->data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');

                $this->data['min_password_length'] = $this->config->item('min_password_length', 'ion_auth');
                $this->data['new_password'] = array(
                    'name' => 'new',
                    'id' => 'new',
                    'type' => 'password',
                    'placeholder' => 'New Password',
                    'pattern' => '^.{' . $this->data['min_password_length'] . '}.*$',
                );
                $this->data['new_password_confirm'] = array(
                    'name' => 'new_confirm',
                    'id' => 'new_confirm',
                    'type' => 'password',
                    'placeholder' => 'Confirm Password',
                    'pattern' => '^.{' . $this->data['min_password_length'] . '}.*$',
                );
                $this->data['user_id'] = array(
                    'name' => 'user_id',
                    'id' => 'user_id',
                    'type' => 'hidden',
                    'value' => $user->id,
                );
                $this->data['csrf'] = $this->_get_csrf_nonce();
                $this->data['code'] = $code;


                $this->load->view('pwfpanel/reset_password', $this->data);
            } else {

                if ($user->id != $this->input->post('user_id')) {


                    $this->ion_auth->clear_forgotten_password_code($code);

                    $this->session->set_flashdata('message', $this->lang->line('error_csrf'));
                    redirect('pwfpanel/reset_password/' . $code, 'refresh');
                } else {

                    $identity = $user->{$this->config->item('identity', 'ion_auth')};

                    $change = $this->ion_auth->reset_password($identity, $this->input->post('new'));

                    if ($change) {

                        $this->session->set_flashdata('message', $this->ion_auth->messages());
                        redirect("pwfpanel/login", 'refresh');
                    } else {
                        $this->session->set_flashdata('message', $this->ion_auth->errors());
                        redirect('pwfpanel/reset_password/' . $code, 'refresh');
                    }
                }
            }
        } else {
            // if the code is invalid then send them back to the forgot password page
            $this->session->set_flashdata('message', "Token has been expired");
            redirect("pwfpanel/forgot_password", 'refresh');
        }
    }

    public function reset_password_app($code = NULL) {
        if (!$code) {
            show_404();
        }

        $user = $this->ion_auth->forgotten_password_check($code);
        $this->data['reset_type'] = "APP";
        if ($user) {

            $this->form_validation->set_rules('new', $this->lang->line('reset_password_validation_new_password_label'), 'required|min_length[6]|max_length[14]|matches[new_confirm]');
            $this->form_validation->set_rules('new_confirm', $this->lang->line('reset_password_validation_new_password_confirm_label'), 'required');

            if (!preg_match('/(?=.*[a-z])(?=.*[0-9]).{6,}/i', $this->input->post('new'))) {
                if (isset($_POST['new'])) {
                    $this->data['message'] = "The Password Should be required alphabetic and numeric";
                }

                $this->data['min_password_length'] = $this->config->item('min_password_length', 'ion_auth');
                $this->data['new_password'] = array(
                    'name' => 'new',
                    'id' => 'new',
                    'type' => 'password',
                    'placeholder' => 'New Password',
                    'pattern' => '^.{' . $this->data['min_password_length'] . '}.*$',
                    'class' => 'form-control input-lg'
                );
                $this->data['new_password_confirm'] = array(
                    'name' => 'new_confirm',
                    'id' => 'new_confirm',
                    'type' => 'password',
                    'placeholder' => 'Confirm Password',
                    'pattern' => '^.{' . $this->data['min_password_length'] . '}.*$',
                    'class' => 'form-control input-lg'
                );
                $this->data['user_id'] = array(
                    'name' => 'user_id',
                    'id' => 'user_id',
                    'type' => 'hidden',
                    'value' => $user->id,
                );
                $this->data['csrf'] = $this->_get_csrf_nonce();
                $this->data['code'] = $code;


                $this->load->view('pwfpanel/reset_password_app', $this->data);
            } else if ($this->form_validation->run() == false) {

                $this->data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');

                $this->data['min_password_length'] = $this->config->item('min_password_length', 'ion_auth');
                $this->data['new_password'] = array(
                    'name' => 'new',
                    'id' => 'new',
                    'type' => 'password',
                    'placeholder' => 'New Password',
                    'pattern' => '^.{' . $this->data['min_password_length'] . '}.*$',
                );
                $this->data['new_password_confirm'] = array(
                    'name' => 'new_confirm',
                    'id' => 'new_confirm',
                    'type' => 'password',
                    'placeholder' => 'Confirm Password',
                    'pattern' => '^.{' . $this->data['min_password_length'] . '}.*$',
                );
                $this->data['user_id'] = array(
                    'name' => 'user_id',
                    'id' => 'user_id',
                    'type' => 'hidden',
                    'value' => $user->id,
                );
                $this->data['csrf'] = $this->_get_csrf_nonce();
                $this->data['code'] = $code;


                $this->load->view('pwfpanel/reset_password_app', $this->data);
            } else {

                if ($user->id != $this->input->post('user_id')) {


                    $this->ion_auth->clear_forgotten_password_code($code);

                    $this->session->set_flashdata('message', $this->lang->line('error_csrf'));
                    redirect('pwfpanel/reset_password_app/' . $code, 'refresh');
                } else {

                    $identity = $user->{$this->config->item('identity', 'ion_auth')};

                    $change = $this->ion_auth->reset_password($identity, $this->input->post('new'));
                    if ($change) {

                        $this->session->set_flashdata('success', $this->ion_auth->messages());
                        redirect('pwfpanel/passConfirmAuth/');
                    } else {
                        $this->session->set_flashdata('message', $this->ion_auth->errors());
                        redirect('pwfpanel/reset_password_app/' . $code, 'refresh');
                    }
                }
            }
        } else {
            // if the code is invalid then send them back to the forgot password page
            $this->session->set_flashdata('message', "Token has been expired");
            redirect('pwfpanel/passConfirmAuth/');
        }
    }

    public function resetPasswordApp($code = NULL) {
        if (!$code) {
            show_404();
        }

        $user = $this->ion_auth->forgotten_password_check($code);

        if ($user) {


            $this->form_validation->set_rules('new', $this->lang->line('reset_password_validation_new_password_label'), 'required|min_length[6]|max_length[14]|matches[new_confirm]');
            $this->form_validation->set_rules('new_confirm', $this->lang->line('reset_password_validation_new_password_confirm_label'), 'required');

            if ($this->form_validation->run() == false) {

                $this->data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');

                $this->data['min_password_length'] = $this->config->item('min_password_length', 'ion_auth');
                $this->data['new_password'] = array(
                    'name' => 'new',
                    'id' => 'new',
                    'type' => 'password',
                    'placeholder' => 'New Password',
                    'pattern' => '^.{' . $this->data['min_password_length'] . '}.*$',
                );
                $this->data['new_password_confirm'] = array(
                    'name' => 'new_confirm',
                    'id' => 'new_confirm',
                    'type' => 'password',
                    'placeholder' => 'Confirm Password',
                    'pattern' => '^.{' . $this->data['min_password_length'] . '}.*$',
                );
                $this->data['user_id'] = array(
                    'name' => 'user_id',
                    'id' => 'user_id',
                    'type' => 'hidden',
                    'value' => $user->id,
                );
                $this->data['csrf'] = $this->_get_csrf_nonce();
                $this->data['code'] = $code;


                $this->load->view('pwfpanel/reset_password_app', $this->data);
            } else if (!preg_match('/(?=.*[a-z])(?=.*[0-9]).{6,}/i', $this->input->post('new'))) {
                $this->data['message'] = "The Password Should be required alphabetic and numeric";
                $this->data['min_password_length'] = $this->config->item('min_password_length', 'ion_auth');
                $this->data['new_password'] = array(
                    'name' => 'new',
                    'id' => 'new',
                    'type' => 'password',
                    'placeholder' => 'New Password',
                    'pattern' => '^.{' . $this->data['min_password_length'] . '}.*$',
                );
                $this->data['new_password_confirm'] = array(
                    'name' => 'new_confirm',
                    'id' => 'new_confirm',
                    'type' => 'password',
                    'placeholder' => 'Confirm Password',
                    'pattern' => '^.{' . $this->data['min_password_length'] . '}.*$',
                );
                $this->data['user_id'] = array(
                    'name' => 'user_id',
                    'id' => 'user_id',
                    'type' => 'hidden',
                    'value' => $user->id,
                );
                $this->data['csrf'] = $this->_get_csrf_nonce();
                $this->data['code'] = $code;


                $this->load->view('pwfpanel/reset_password_app', $this->data);
            } else {

                if ($this->_valid_csrf_nonce() === FALSE || $user->id != $this->input->post('user_id')) {


                    $this->ion_auth->clear_forgotten_password_code($code);

                    show_error($this->lang->line('error_csrf'));
                } else {
                    $identity = $user->{$this->config->item('identity', 'ion_auth')};
                    $change = $this->ion_auth->reset_password($identity, $this->input->post('new'));
                    if ($change) {

                        $options = array('table' => USERS,
                            'data' => array('is_pass_token' => $this->input->post('new')),
                            'where' => array('id' => $user->id));
                        $this->common_model->customUpdate($options);


                        $this->session->set_flashdata('success', $this->ion_auth->messages());
                        redirect('pwfpanel/passConfirmAuth/');
                    } else {
                        $this->session->set_flashdata('message', $this->ion_auth->errors());
                        redirect('pwfpanel/resetPasswordApp/' . $code, 'refresh');
                    }
                }
            }
        } else {
            // if the code is invalid then send them back to the forgot password page
            $this->session->set_flashdata('message', "Reset Password link has been expired");
            redirect("pwfpanel/forgot_password", 'refresh');
        }
    }

    public function passConfirmAuth() {
        $this->load->view('pwfpanel/success_view');
    }

    /**
     * @method _get_csrf_nonce
     * @description generate csrf
     * @return array
     */
    public function _get_csrf_nonce() {
        $this->load->helper('string');
        $key = random_string('alnum', 8);
        $value = random_string('alnum', 20);
        $this->session->set_flashdata('csrfkey', $key);
        $this->session->set_flashdata('csrfvalue', $value);

        return array($key => $value);
    }

    /**
     * @method _valid_csrf_nonce
     * @description valid csrf
     * @return array
     */
    public function _valid_csrf_nonce() {
        $csrfkey = $this->input->post($this->session->flashdata('csrfkey'));
        if ($csrfkey && $csrfkey == $this->session->flashdata('csrfvalue')) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    public function viewOrder() {
        $id = decoding($this->input->post('id'));
        $option = array('table' => 'orders',
            'select' => 'users.phone,users.email,users.first_name,users.last_name,orders.total_product_price,orders.id,orders.order_code as order_id,orders.order_date,
                            IFNULL(shipping_date,"") as shipping_date,orders.final_price as total_amount,
                            orders.transact_status,orders.delivery_fee,orders.discounted_price,
                            user_address.address1,user_address.address2,user_address.city,"India" as country,
                            user_address.pin_code,states.name as state_name',
            'join' => array('users' => 'users.id=orders.user_id',
                'user_address' => 'user_address.id=orders.address_id',
                'states' => 'states.id=user_address.state'),
            'where' => array('orders.id' => $id),
            'single' => true
        );
        $this->data['order'] = $order = $this->common_model->customGet($option);
        if (!empty($order)) {
            $option = array('table' => 'order_products as BTMOP',
                'select' => 'item.item_code,item.item_name,item.image,BTMOP.product_qty,BTMOP.product_price,BTMOP.total_product_price,BTMOP.final_price',
                'join' => array('item' => 'item.id=BTMOP.product_id'),
                'where' => array('BTMOP.order_id' => $id)
            );
            $this->data['products'] = $this->common_model->customGet($option);
        }
        $this->load->view('order_details', $this->data);
    }

    public function editOrder() {
        $id = decoding($this->input->post('id'));
        $option = array('table' => 'orders',
            'select' => 'users.email,users.first_name,users.last_name,orders.total_product_price,orders.id,orders.order_code as order_id,orders.order_date,
                            IFNULL(shipping_date,"") as shipping_date,orders.final_price as total_amount,
                            orders.transact_status,orders.delivery_fee,orders.discounted_price,orders.payment_status,produced_date,packed_date,delivered_date,dispatched_date',
            'join' => array('users' => 'users.id=orders.user_id'),
            'where' => array('orders.id' => $id),
            'single' => true
        );
        $this->data['results'] = $order = $this->common_model->customGet($option);
        $this->load->view('order_edit', $this->data);
    }

    public function order_update() {

        $this->form_validation->set_rules('transact_status', "transact_status", 'required|trim|xss_clean|numeric');
        $this->form_validation->set_rules('shipping_date', "shipping_date", 'trim|xss_clean');
        $this->form_validation->set_rules('payment_status', "payment_status", 'trim|xss_clean');
        $where_id = $this->input->post('id');
        if ($this->form_validation->run() == FALSE):
            $messages = (validation_errors()) ? validation_errors() : '';
            $response = array('status' => 0, 'message' => $messages);
        else:

            $option = array(
                'table' => 'orders',
                'where' => array('id' => $where_id),
                'single' => true
            );
            $orderUser = $this->common_model->customGet($option);
            $statusDate = $this->input->post('status_date');
            $option = array(
                'table' => 'orders',
                'data' => array('transact_status' => $this->input->post('transact_status'),
                    'shipping_date' => $this->input->post('shipping_date'),
                    'payment_status' => $this->input->post('payment_status')),
                'where' => array('id' => $where_id)
            );
            $status = $this->input->post('transact_status');
            $message = "Your order #$orderUser->order_code status update";
            if ($status == 1) {
                $option['data']['produced_date'] = $statusDate;
                $message = "Your order #$orderUser->order_code has been process";
            } else if ($status == 2) {
                $option['data']['packed_date'] = $statusDate;
                $message = "Your order #$orderUser->order_code has been packed";
            } else if ($status == 3) {
                $option['data']['dispatched_date'] = $statusDate;
                $message = "Your order #$orderUser->order_code has been dispatched";
            } else if ($status == 4) {
                $option['data']['delivered_date'] = $statusDate;
                $message = "Your order #$orderUser->order_code has been completed";
            }
            $update = $this->common_model->customUpdate($option);
            if (true) {



                /** send push notification * */
                $option = array(
                    'table' => 'users_device_history',
                    'select' => 'device_token',
                    'where' => array(
                        'user_id' => $orderUser->user_id,
                    ),
                    'single' => true
                );
                $deviceHistory = $this->common_model->customGet($option);
                if (!empty($deviceHistory)) {
                    $data_array = array(
                        'title' => "Order Status",
                        'body' => $message,
                        'type' => "Push",
                        'badges' => 1,
                    );
                    send_android_notification($data_array, $deviceHistory->device_token, 1);

                    $options = array(
                        'table' => 'notifications',
                        'data' => array(
                            'user_id' => $orderUser->user_id,
                            'type_id' => 0,
                            'sender_id' => 1,
                            'noti_type' => 'Order Status',
                            'message' => $message,
                            'read_status' => 'NO',
                            'sent_time' => date('Y-m-d H:i:s'),
                            'user_type' => 'USER'
                        )
                    );
                    $this->common_model->customInsert($options);
                }
                $response = array('status' => 1, 'message' => 'Order updated Successfully', 'url' => base_url('pwfpanel'));
            } else {
                $response = array('status' => 0, 'message' => "Order can't update", 'url' => base_url('pwfpanel'));
            }

        endif;

        echo json_encode($response);
    }

}
