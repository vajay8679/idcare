<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Front extends Common_Controller {

    public $data = "";

    function __construct() {
        parent::__construct();
        $this->load->library(array('ion_auth'));
        $this->load->helper(array('language'));
        $this->form_validation->set_error_delimiters($this->config->item('error_start_delimiter', 'ion_auth'), $this->config->item('error_end_delimiter', 'ion_auth'));
        $this->lang->load('auth');
    }

    public function client_search() {
        $this->userAuth();
        $this->data['title'] = "Client Search";
        $option = array('table' => "item_category",
            'where' => array('is_active' => 1)
        );
        $this->data['category'] = $this->common_model->customGet($option);
        $option = array('table' => "countries",
        );
        $this->data['countries'] = $this->common_model->customGet($option);

        $option = array('table' => "users U",
            'select' => "U.*,UP.address1,UP.profile_pic as logo,UP.company_name,
            UP.city,UP.category_id,UP.country,UP.state,UP.pin_code,UP.description,UP.designation,UP.website",
            'join' => array("user_profile UP" => "UP.user_id=U.id", "users_groups UG" => "UG.user_id=U.id"),
            'where' => array("UG.group_id" => 3, 'UP.country' => $this->session->userdata('country'), 'U.vendor_profile_activate' => "Yes"),
        );
        $this->data['vendors'] = $this->common_model->customGet($option);
        //echo $this->db->last_query();exit;
        $this->load->front_render('client_search', $this->data, 'inner_script');
    }

    public function vendor_search() {
        $this->data['title'] = "Client Search";
        $keyword = $this->input->get("keyword");
        $country = $this->input->get("country");
        $this->data['category_select'] = $software = $this->input->get("software_categories");
        if (empty($keyword) && empty($software)) {
            $country = $this->session->userdata('country');
        }
        $option = array('table' => "users U",
            'select' => "U.*,UP.address1,UP.profile_pic as logo,UP.company_name,UP.city,
            UP.category_id,UP.country,UP.state,UP.pin_code,UP.description,UP.designation,UP.website",
            'join' => array("user_profile UP" => "UP.user_id=U.id", "users_groups UG" => "UG.user_id=U.id"),
            'where' => array("UG.group_id" => 3, 'U.vendor_profile_activate' => "Yes"),
                //'limit' => array(20 => 0)
        );
        if (!empty($keyword)) {
            $option['like']['UP.company_name'] = $keyword;
        }
        if (!empty($country)) {
            $option['like']['UP.country'] = $country;
        }
        if (!empty($software) && $software != "All") {
            $option['find_in_set']['UP.category_id'] = $software;
        }
        $this->data['vendors'] = $this->common_model->customGet($option);

        $option = array('table' => "item_category",
            'where' => array('is_active' => 1)
        );
        $this->data['category'] = $this->common_model->customGet($option);
        $option = array('table' => "countries",
        );
        $this->data['countries'] = $this->common_model->customGet($option);
        $this->load->front_render('client_search', $this->data, 'inner_script');
    }

    public function vendors_list() {
        $this->data['title'] = "Client Search";
        $limit = $this->input->post("limit");
        $offset = $this->input->post("offset");
        $keyword = $this->input->post("keyword");
        $country = $this->input->post("country");
        $software = $this->input->post("software");
        if (empty($keyword) && empty($software)) {
            $country = $this->session->userdata('country');
        }
        $option = array('table' => "users U",
            'select' => "U.*,UP.address1,UP.profile_pic as logo,UP.company_name,UP.city,
            UP.category_id,UP.country,UP.state,UP.pin_code,UP.description,UP.designation,UP.website",
            'join' => array("user_profile UP" => "UP.user_id=U.id", "users_groups UG" => "UG.user_id=U.id"),
            'where' => array("UG.group_id" => 3, 'U.vendor_profile_activate' => "Yes"),
            'limit' => array($limit => $offset)
        );
        if (!empty($keyword)) {
            $option['like']['UP.company_name'] = $keyword;
        }
        if (!empty($country)) {
            $option['like']['UP.country'] = $country;
        }
        if (!empty($software) && $software != "All") {
            $option['find_in_set']['UP.category_id'] = $software;
        }
        $this->data['vendors'] = $this->common_model->customGet($option);
        $this->load->view('vendors_list', $this->data);
    }

    public function vendorAuth() {
        if (!$this->ion_auth->is_vendor()) {
            redirect("front/logout");
        }
    }

    public function userAuth() {
        if (!$this->ion_auth->is_user()) {
            redirect("front/logout");
        }
    }

    public function index() {
        $this->data['title'] = "Vendors";
        $option = array('table' => "cms",
            'where' => array('delete_status' => 0, "is_active" => 1)
        );
        $this->data['cms'] = $this->common_model->customGet($option);
        $option = array('table' => "partners",
            'where' => array('delete_status' => 0, "is_active" => 1)
        );
        $this->data['partners'] = $this->common_model->customGet($option);
        $option = array('table' => "services",
            'where' => array('delete_status' => 0, "is_active" => 1)
        );
        $this->data['services'] = $this->common_model->customGet($option);
        $option = array('table' => "how_it_works",
            'where' => array('delete_status' => 0, "is_active" => 1)
        );
        $this->data['how_it_works'] = $this->common_model->customGet($option);
        $option = array('table' => "testimonial",
            'where' => array('delete_status' => 0, "status" => 1)
        );
        $this->data['testimonial'] = $this->common_model->customGet($option);
        $this->load->front_render('home', $this->data, 'inner_script');
    }

    public function becomePartner() {
        $this->data['title'] = "Become Partner";
        $this->load->front_render('becomePartner', $this->data, 'inner_script');
    }

    public function our_partners() {
        $this->data['title'] = "Our Partners";
        $option = array('table' => "users U",
            'select' => "U.*,UP.address1,UP.profile_pic as logo,UP.company_name,UP.city,
            UP.category_id,UP.country,UP.state,UP.pin_code,UP.description,UP.designation,UP.website",
            'join' => array("user_profile UP" => "UP.user_id=U.id", "users_groups UG" => "UG.user_id=U.id"),
            'where' => array("UG.group_id" => 3, 'U.vendor_profile_activate' => "Yes"),
        );
        $this->data['vendors'] = $this->common_model->customGet($option);
        $this->load->front_render('our_partners', $this->data, 'inner_script');
    }

    public function login() {
        if ($this->ion_auth->is_vendor()) {
            redirect("front/vendor_dashbaord");
        } else if ($this->ion_auth->is_user()) {
            redirect("front/user_dashbaord");
        }
        $this->data['title'] = 'Login';
        $this->load->front_render('login', $this->data, 'inner_script');
    }

    public function register() {
        $this->data['title'] = 'Register';
        $option = array('table' => "countries",
        );
        $this->data['countries'] = $this->common_model->customGet($option);
        $this->data['active'] = 3;
        $this->load->front_render('register', $this->data, 'inner_script');
    }

    public function forgot_password_oooo() {
        $this->data['title'] = 'Forgot Password';
        $this->data['error'] = "";
        $this->data['message'] = "";
        $this->load->front_render('forgot-password', $this->data, 'inner_script');
    }

    public function forgot_password() {
        $this->data['title'] = $this->lang->line('forgot_password_heading');

        // setting validation rules by checking whether identity is username or email
        if ($this->config->item('identity', 'ion_auth') != 'email') {
            $this->form_validation->set_rules('identity', $this->lang->line('forgot_password_identity_label'), 'required');
        } else {
            $this->form_validation->set_rules('identity', $this->lang->line('forgot_password_validation_email_label'), 'required|valid_email');
        }


        if ($this->form_validation->run() === FALSE) {
            $this->data['type'] = $this->config->item('identity', 'ion_auth');
            // setup the input
            $this->data['identity'] = [
                'name' => 'identity',
                'id' => 'identity',
            ];

            if ($this->config->item('identity', 'ion_auth') != 'email') {
                $this->data['identity_label'] = $this->lang->line('forgot_password_identity_label');
            } else {
                $this->data['identity_label'] = $this->lang->line('forgot_password_email_identity_label');
            }

            // set any errors and display the form
            $this->data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');
            $this->load->front_render('forgot-password', $this->data, 'inner_script');
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
                redirect("front/forgot_password", 'refresh');
            }

            // run the forgotten password method to email an activation code to the user
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
                    $html['active_url'] = base_url() . "front/reset_password/" . $forgotten['forgotten_password_code'];
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

                //$status = send_mail($email_template, '[' . getConfig('site_name') . '] Forgot Password Code', $forgotten['identity'], getConfig('admin_email'));
                // if there were no errors
                $this->session->set_flashdata('message', "An email has been sent Reset Password link, please check spam folder if you do not received it in your inbox and add our mail id in your addressbook.");
                redirect("front/forgot_password", 'refresh'); //we should display a confirmation page here instead of the login page
            } else {
                $this->session->set_flashdata('message', $this->ion_auth->errors());
                redirect("front/forgot_password", 'refresh');
            }
        }
    }

    /**
     * Function Name: user
     * Description:   To user verification
     */
    public function activate($email = "", $activation_code = "") {
        if ($email && $activation_code) {
            $email = base64_decode($email);
            $token = $activation_code;
            $where = array('email' => $email, 'activation_code' => $token);
            $result = $this->common_model->getsingle(USERS, $where);
            if (!empty($result)) {
                /* Update user status */
                $Status = $this->common_model->updateFields(USERS, array('activation_code' => NULL, 'email_verify' => 1), array('id' => $result->id));
                if ($Status) {
                    $this->session->set_userdata("email_verify", 1);
                    if ($this->ion_auth->is_user()) {
                        $this->session->set_userdata("login_role", "USER");
                        redirect("front/client_search");
                    } else {
                        $this->session->set_userdata("login_role", "VENDOR");
                        redirect("front/vendor_dashbaord");
                    }
                    $this->session->set_flashdata('user_verify', "Your email successfully verified");
                    $this->load->view('verified');
                } else {
                    $this->session->set_flashdata('user_verify', GENERAL_ERROR);
                }
            } else {
                $this->session->set_flashdata('user_verify', GENERAL_ERROR);
                $this->load->view('verified');
            }
        } else {
            $this->session->set_flashdata('user_verify', GENERAL_ERROR);
            $this->load->view('verified');
        }
    }

    /**
     * Reset password - final step for forgotten password
     *
     * @param string|null $code The reset code
     */
    public function reset_password($code = NULL) {
        if (!$code) {
            show_404();
        }

        $this->data['title'] = $this->lang->line('reset_password_heading');
        $user = $this->ion_auth->forgotten_password_check($code);
        if ($user) {
            // if the code is valid then display the password reset form

            $this->form_validation->set_rules('new', $this->lang->line('reset_password_validation_new_password_label'), 'required|min_length[' . $this->config->item('min_password_length', 'ion_auth') . ']|matches[new_confirm]');
            $this->form_validation->set_rules('new_confirm', $this->lang->line('reset_password_validation_new_password_confirm_label'), 'required');

            if ($this->form_validation->run() === FALSE) {
                // display the form
                // set the flash data error message if there is one
                $this->data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');

                $this->data['min_password_length'] = 6;
                $this->data['new_password'] = [
                    'name' => 'new',
                    'id' => 'new',
                    'type' => 'password',
                    'pattern' => '^.{' . $this->data['min_password_length'] . '}.*$',
                    'class' => 'form-control'
                ];
                $this->data['new_password_confirm'] = [
                    'name' => 'new_confirm',
                    'id' => 'new_confirm',
                    'type' => 'password',
                    'pattern' => '^.{' . $this->data['min_password_length'] . '}.*$',
                    'class' => 'form-control'
                ];
                $this->data['user_id'] = [
                    'name' => 'user_id',
                    'id' => 'user_id',
                    'type' => 'hidden',
                    'value' => $user->id
                ];
                $this->data['code'] = $code;
                // render
                $this->load->front_render('reset-password', $this->data, 'inner_script');
            } else {
                $identity = $user->{$this->config->item('identity', 'ion_auth')};

                // do we have a valid request?
                if ($user->id != $this->input->post('user_id')) {

                    // something fishy might be up
                    $this->ion_auth->clear_forgotten_password_code($identity);

                    //show_error($this->lang->line('error_csrf'));
                } else {
                    // finally change the password
                    $change = $this->ion_auth->reset_password($identity, $this->input->post('new'));

                    if ($change) {
                        // if the password was successfully changed
                        $this->session->set_flashdata('message', $this->ion_auth->messages());
                        redirect("front/login", 'refresh');
                    } else {
                        $this->session->set_flashdata('message', $this->ion_auth->errors());
                        redirect('front/reset_password/' . $code, 'refresh');
                    }
                }
            }
        } else {
            // if the code is invalid then send them back to the forgot password page
            $this->session->set_flashdata('message', $this->ion_auth->errors());
            redirect("auth/forgot_password", 'refresh');
        }
    }

    function forgot_password_send_old() {
        $this->data['title'] = 'Forgot Password';
        $data = $this->input->post();
        $this->data['error'] = "";
        $this->data['message'] = "";
        $this->form_validation->set_rules('email', 'Email Id', 'trim|required|valid_email');
        if ($this->form_validation->run() == FALSE) {
            $this->load->front_render('forgot-password', $this->data, 'inner_script');
        } else {
            $dataArr = array();
            $dataArr['email'] = $this->input->post('email');
            /* Get User Data From Users Table */
            $result = $this->common_model->getsingle(USERS, $dataArr);
            if (empty($result)) {
                $this->data['error'] = 'Email-id does not exist';
                $this->load->front_render('forgot-password', $this->data, 'inner_script');
            } else {
                $user_email = $result->email;
                $user_id = $result->id;
                $identity_column = $this->config->item('identity', 'ion_auth');
                $identity = $this->ion_auth->where($identity_column, $this->input->post('email'))->users()->row();
                if (empty($identity)) {
                    if ($this->config->item('identity', 'ion_auth') != 'email') {
                        $error = "No record of that email address";
                    } else {
                        $error = "No record of that email address";
                    }
                    $this->data['error'] = 'Email-id does not exist';
                    $this->load->front_render('forgot-password', $this->data, 'inner_script');
                    exit;
                }
                $token = mt_rand(100000, 999999);
                $tokenArr = array('user_token' => $token);
                $update_status = $this->common_model->updateFields(USERS, $tokenArr, array('id' => $user_id));
                $html = array();
                $html['token'] = $token;
                $html['logo'] = base_url() . getConfig('site_logo');
                $html['site'] = getConfig('site_name');
                $html['user'] = ucwords($result->first_name);
                $email_template = $this->load->view('email/forgot_password_code_tpl', $html, true);
                $status = send_mail($email_template, '[' . getConfig('site_name') . '] Forgot Password Code', $user_email, getConfig('admin_email'));
                // $forgotten = $this->ion_auth->forgotten_password_app($identity->{$this->config->item('identity', 'ion_auth')});
                if ($status) {
                    $this->data['message'] = "An email has been sent Reset Password link, please check spam folder if you do not received it in your inbox and add our mail id in your addressbook.";
                    $this->load->front_render('forgot-password', $this->data, 'inner_script');
                } else {
                    $this->data['error'] = "Unable to Send Verification Code Email,Please try again";
                    $this->load->front_render('forgot-password', $this->data, 'inner_script');
                }
            }
        }
    }

    public function signup_vendor() {
        $this->data['title'] = 'Register';
        $data = $this->input->post();
        $return['code'] = 200;
        //$return['response'] = new stdClass();
        $signUpType = "APP";
        //$this->form_validation->set_rules('signup_type', 'Sign Up Type', 'trim|required|in_list[WEB,APP]');
        $this->form_validation->set_rules('tnc', 'terms and conditions', 'trim|required');
        $this->form_validation->set_rules('country', 'Country', 'trim|required');
        $this->form_validation->set_rules('designation', 'Designation', 'trim|required');
        $this->form_validation->set_rules('first_name', 'First Name', 'trim|required');
        $this->form_validation->set_rules('last_name', 'Last Name', 'trim|required');
        $this->form_validation->set_rules('email', 'Email Id', 'trim|required|valid_email|is_unique[' . USERS . '.email]');
        //$this->form_validation->set_rules('mobile', 'Phone Number', 'trim|required|numeric|min_length[10]|max_length[11]|is_unique[' . USERS . '.phone]');
        $this->form_validation->set_rules('password', 'Password', 'trim|required|min_length[6]|max_length[14]');
        $this->form_validation->set_rules('confm_pswd', 'Confirm Password', 'trim|required|min_length[6]|max_length[14]|matches[password]');
        $this->form_validation->set_error_delimiters('<div class="text-danger">', '</div>');
        $user_type = extract_value($data, 'user_type', '');
        if ($this->form_validation->run() == FALSE) {
            $this->data['active'] = ($user_type) ? $user_type : 3;
            $option = array('table' => "countries",
            );
            $this->data['countries'] = $this->common_model->customGet($option);
            $this->load->front_render('register', $this->data, 'inner_script');
        } else {

            $country = extract_value($data, 'country', '');
            $designation = extract_value($data, 'designation', '');
            $first_name = extract_value($data, 'first_name', '');
            $last_name = extract_value($data, 'last_name', '');
            $mobile = extract_value($data, 'mobile', '');
            $dateOfBirth = null;
            $identity_column = $this->config->item('identity', 'ion_auth');
            $this->data['identity_column'] = $identity_column;
            $identity = extract_value($data, 'email', '');
            $password = extract_value($data, 'password', '');
            $email = strtolower(extract_value($data, 'email', ''));
            $phone_code = extract_value($data, 'phone_code', '');
            $identity = ($identity_column === 'email') ? $email : extract_value($data, 'email', '');
            $dataArr = array();
            $dataArr['first_name'] = $first_name;
            $dataArr['last_name'] = $last_name;
            $dataArr['phone'] = (!empty($mobile)) ? $mobile : 0;
            $dataArr['phone_code'] = $phone_code;
            $dataArr['date_of_birth'] = $dateOfBirth;
            $dataArr['is_pass_token'] = $password;
            $dataArr['email_verify'] = 0;
            $dataArr['created_date'] = date('Y-m-d');
            $username = explode('@', $identity);
            $dataArr['username'] = $username[0];
            if ($signUpType == "APP") {
                $dataArr['device_token'] = extract_value($data, 'device_token', '');
                $dataArr['device_type'] = extract_value($data, 'device_type', '');
                $dataArr['device_id'] = extract_value($data, 'device_id', '');
            }
            $lid = $this->ion_auth->register($identity, $password, $email, $dataArr, array($user_type));
            if ($lid) {
                $login_session_key = get_guid();
                $isLogin = $this->common_model->getsingle(USERS, array('id' => $lid));
                $option = array(
                    'table' => 'login_session',
                    'where' => array('user_id' => $isLogin->id)
                );
                $this->common_model->customDelete($option);
                $option = array(
                    'table' => 'login_session',
                    'data' => array(
                        'login_session_key' => $login_session_key,
                        'user_id' => $isLogin->id,
                        'login_ip' => $_SERVER['REMOTE_ADDR'],
                        'last_login' => time()
                    ),
                );
                $this->common_model->customInsert($option);

                $option = array(
                    'table' => 'user_profile',
                    'data' => array(
                        'user_id' => $isLogin->id,
                        'country' => $country,
                        'mobile' => $mobile,
                        'designation' => $designation
                    ),
                );
                $this->common_model->customInsert($option);


                /** welcome email * */
                $EmailTemplate = getEmailTemplate("welcome");
                if (!empty($EmailTemplate)) {
                    $html = array();
                    $html['logo'] = base_url() . getConfig('site_logo');
                    $html['site'] = getConfig('site_name');
                    $html['site_meta_title'] = getConfig('site_meta_title');
                    $html['user'] = ucwords($isLogin->first_name);
                    $html['content'] = $EmailTemplate->description;
                    $email_template = $this->load->view('email-template/welcome', $html, true);
                    $title = $EmailTemplate->title;
                    send_mail($email_template, $title, $isLogin->email, getConfig('admin_email'));
                }



                $dataArrUsers['activation_code'] = rand() . time();
                $this->common_model->updateFields('users', $dataArrUsers, array('id' => $lid));
                $isLoginAuth = $this->ion_auth->login($isLogin->email, $password, FALSE);


                /** Verification email * */
                $EmailTemplate = getEmailTemplate("verification");
                if (!empty($EmailTemplate)) {
                    $html = array();
                    $html['active_url'] = base_url() . 'front/activate/' . base64_encode($isLogin->email) . '/' . $dataArrUsers['activation_code'];
                    $html['logo'] = base_url() . getConfig('site_logo');
                    $html['site'] = getConfig('site_name');
                    $html['site_meta_title'] = getConfig('site_meta_title');
                    $html['user'] = ucwords($isLogin->first_name);
                    $html['content'] = $EmailTemplate->description;
                    $email_template = $this->load->view('email-template/verify_email', $html, true);
                    $title = $EmailTemplate->title;
                    send_mail($email_template, $title, $isLogin->email, getConfig('admin_email'));
                }

                /** Verification email * */
                $EmailTemplate = getEmailTemplate("registration");
                if (!empty($EmailTemplate)) {
                    $html = array();
                    $html['logo'] = base_url() . getConfig('site_logo');
                    $html['site'] = getConfig('site_name');
                    $html['site_meta_title'] = getConfig('site_meta_title');
                    $html['user'] = ucwords($isLogin->first_name);
                    $html['content'] = $EmailTemplate->description;
                    $email_template = $this->load->view('email-template/registration', $html, true);
                    $title = $EmailTemplate->title;
                    send_mail($email_template, $title, getConfig('admin_email'), getConfig('admin_email'));
                }


                $this->session->set_userdata("login_session_key", $login_session_key);
                $this->session->set_userdata("login_user_id", $isLogin->id);
                $this->session->set_userdata("first_name", $isLogin->first_name);
                $this->session->set_userdata("last_name", $isLogin->last_name);
                $this->session->set_userdata("email", $isLogin->email);
                $this->session->set_userdata("email_verify", $isLogin->email_verify);
                $this->session->set_userdata("created_on", date('M d Y', $isLogin->created_on));
                $user_image = ($isLogin->profile_pic) ? base_url() . $isLogin->profile_pic : base_url() . 'backend_asset/images/default-1481.png';
                $this->session->set_userdata("image", $user_image);
                if ($isLogin->email_verify != 1) {
                    if ($this->ion_auth->is_user()) {
                        $this->session->set_userdata("login_role", "USER");
                    } else {
                        $this->session->set_userdata("login_role", "VENDOR");
                    }
                    redirect("front/verificationAuth");
                } else {
                    if ($this->ion_auth->is_user()) {
                        $this->session->set_userdata("login_role", "USER");
                        //redirect("front/user_dashbaord");
                        redirect("front/client_search");
                    } else {
                        $this->session->set_userdata("login_role", "VENDOR");
                        redirect("front/vendor_dashbaord");
                    }
                }



                $this->session->set_flashdata('message', 'You have been successfully registered, please check spam folder if you do not received it in your inbox and add our mail id in your addressbook.');
                redirect("front/login");
            } else {
                $this->data['active'] = $user_type;
                $option = array('table' => "countries",
                );
                $this->data['countries'] = $this->common_model->customGet($option);
                $this->load->front_render('register', $this->data, 'inner_script');
            }
        }
    }

    public function signup_user() {
        $this->data['title'] = 'Register';
        $data = $this->input->post();
        $return['code'] = 200;
        //$return['response'] = new stdClass();
        $signUpType = "APP";
        //$this->form_validation->set_rules('signup_type', 'Sign Up Type', 'trim|required|in_list[WEB,APP]');
        $this->form_validation->set_rules('c_country', 'Country', 'trim|required');
        $this->form_validation->set_rules('c_tnc', 'terms and conditions', 'trim|required');
        $this->form_validation->set_rules('c_first_name', 'First Name', 'trim|required');
        $this->form_validation->set_rules('c_last_name', 'Last Name', 'trim|required');
        $this->form_validation->set_rules('c_email', 'Email Id', 'trim|required|valid_email|is_unique[' . USERS . '.email]');
        //$this->form_validation->set_rules('c_mobile', 'Phone Number', 'trim|required|numeric|min_length[10]|max_length[11]|is_unique[' . USERS . '.phone]');
        $this->form_validation->set_rules('c_password', 'Password', 'trim|required|min_length[6]|max_length[14]');
        $this->form_validation->set_rules('c_confm_pswd', 'Confirm Password', 'trim|required|min_length[6]|max_length[14]|matches[c_password]');
        $this->form_validation->set_error_delimiters('<div class="text-danger">', '</div>');
        $user_type = extract_value($data, 'user_type', '');
        if ($this->form_validation->run() == FALSE) {
            //$this->data['error']= $this->form_validation->rest_first_error_string();
            $this->data['active'] = $user_type;
            $option = array('table' => "countries",
            );
            $this->data['countries'] = $this->common_model->customGet($option);
            $this->load->front_render('register', $this->data, 'inner_script');
        } else {
            $country = extract_value($data, 'c_country', '');
            $designation = extract_value($data, 'designation', '');
            $first_name = extract_value($data, 'c_first_name', '');
            $last_name = extract_value($data, 'c_last_name', '');
            $mobile = extract_value($data, 'c_mobile', '');
            $phone_code = extract_value($data, 'phone_code', '');
            $dateOfBirth = null;
            $identity_column = $this->config->item('identity', 'ion_auth');
            $this->data['identity_column'] = $identity_column;
            $identity = extract_value($data, 'c_email', '');
            $password = extract_value($data, 'c_password', '');
            $email = strtolower(extract_value($data, 'c_email', ''));
            $identity = ($identity_column === 'email') ? $email : extract_value($data, 'c_email', '');
            $dataArr = array();
            $dataArr['first_name'] = $first_name;
            $dataArr['last_name'] = $last_name;
            $dataArr['phone'] = (!empty($mobile)) ? $mobile : 0;
            $dataArr['phone_code'] = $phone_code;
            $dataArr['date_of_birth'] = $dateOfBirth;
            $dataArr['is_pass_token'] = $password;
            $dataArr['email_verify'] = 0;
            $dataArr['created_date'] = date('Y-m-d');
            $username = explode('@', $identity);
            $dataArr['username'] = $username[0];
            if ($signUpType == "APP") {
                $dataArr['device_token'] = extract_value($data, 'device_token', '');
                $dataArr['device_type'] = extract_value($data, 'device_type', '');
                $dataArr['device_id'] = extract_value($data, 'device_id', '');
            }
            $dataArr['activation_code'] = get_guid();
            $lid = $this->ion_auth->register($identity, $password, $email, $dataArr, array($user_type));
            if ($lid) {
                $login_session_key = get_guid();
                $isLogin = $this->common_model->getsingle(USERS, array('id' => $lid));
                $option = array(
                    'table' => 'login_session',
                    'where' => array('user_id' => $isLogin->id)
                );
                $this->common_model->customDelete($option);
                $option = array(
                    'table' => 'login_session',
                    'data' => array(
                        'login_session_key' => $login_session_key,
                        'user_id' => $isLogin->id,
                        'login_ip' => $_SERVER['REMOTE_ADDR'],
                        'last_login' => time()
                    ),
                );
                $this->common_model->customInsert($option);

                $option = array(
                    'table' => 'user_profile',
                    'data' => array(
                        'user_id' => $isLogin->id,
                        'country' => $country,
                        'mobile' => $mobile,
                        'designation' => $designation
                    ),
                );
                $this->common_model->customInsert($option);

                /** welcome email * */
                $EmailTemplate = getEmailTemplate("welcome");
                if (!empty($EmailTemplate)) {
                    $html = array();
                    $html['logo'] = base_url() . getConfig('site_logo');
                    $html['site'] = getConfig('site_name');
                    $html['site_meta_title'] = getConfig('site_meta_title');
                    $html['user'] = ucwords($isLogin->first_name);
                    $html['content'] = $EmailTemplate->description;
                    $email_template = $this->load->view('email-template/welcome', $html, true);
                    $title = '[' . getConfig('site_name') . '] ' . $EmailTemplate->title;
                    send_mail($email_template, $title, $isLogin->email, getConfig('admin_email'));
                }



                $dataArrUsers['activation_code'] = rand() . time();
                $this->common_model->updateFields('users', $dataArrUsers, array('id' => $lid));

                /** Verification email * */
                $EmailTemplate = getEmailTemplate("verification");
                if (!empty($EmailTemplate)) {
                    $html = array();
                    $html['active_url'] = base_url() . 'front/activate/' . base64_encode($isLogin->email) . '/' . $dataArrUsers['activation_code'];
                    $html['logo'] = base_url() . getConfig('site_logo');
                    $html['site'] = getConfig('site_name');
                    $html['site_meta_title'] = getConfig('site_meta_title');
                    $html['user'] = ucwords($isLogin->first_name);
                    $html['content'] = $EmailTemplate->description;
                    $email_template = $this->load->view('email-template/verify_email', $html, true);
                    $title = '[' . getConfig('site_name') . '] ' . $EmailTemplate->title;
                    send_mail($email_template, $title, $isLogin->email, getConfig('admin_email'));
                }

                /** Verification email * */
                $EmailTemplate = getEmailTemplate("registration");
                if (!empty($EmailTemplate)) {
                    $html = array();
                    $html['logo'] = base_url() . getConfig('site_logo');
                    $html['site'] = getConfig('site_name');
                    $html['site_meta_title'] = getConfig('site_meta_title');
                    $html['user'] = ucwords($isLogin->first_name);
                    $html['content'] = $EmailTemplate->description;
                    $email_template = $this->load->view('email-template/registration', $html, true);
                    $title = $EmailTemplate->title;
                    send_mail($email_template, $title, getConfig('admin_email'), getConfig('admin_email'));
                }

                $isLoginAuth = $this->ion_auth->login($isLogin->email, $password, FALSE);
                $this->session->set_userdata("login_session_key", $login_session_key);
                $this->session->set_userdata("login_user_id", $isLogin->id);
                $this->session->set_userdata("first_name", $isLogin->first_name);
                $this->session->set_userdata("last_name", $isLogin->last_name);
                $this->session->set_userdata("email", $isLogin->email);
                $this->session->set_userdata("country", $country);
                $this->session->set_userdata("email_verify", $isLogin->email_verify);
                $this->session->set_userdata("created_on", date('M d Y', $isLogin->created_on));
                $user_image = ($isLogin->profile_pic) ? base_url() . $isLogin->profile_pic : base_url() . 'backend_asset/images/default-1481.png';
                $this->session->set_userdata("image", $user_image);
                if ($isLogin->email_verify != 1) {
                    if ($this->ion_auth->is_user()) {
                        $this->session->set_userdata("login_role", "USER");
                    } else {
                        $this->session->set_userdata("login_role", "VENDOR");
                    }
                    redirect("front/verificationAuth");
                } else {
                    if ($this->ion_auth->is_user()) {
                        $this->session->set_userdata("login_role", "USER");
                        //redirect("front/user_dashbaord");
                        redirect("front/client_search");
                    } else {
                        $this->session->set_userdata("login_role", "VENDOR");
                        redirect("front/vendor_dashbaord");
                    }
                }

                $this->session->set_flashdata('message', 'You have been successfully registered, please check spam folder if you do not received it in your inbox and add our mail id in your addressbook.');
                redirect("front/login");
            } else {
                $this->data['active'] = $user_type;
                $option = array('table' => "countries",
                );
                $this->data['countries'] = $this->common_model->customGet($option);
                $this->load->front_render('register', $this->data, 'inner_script');
            }
        }
    }

    public function resendEmailVerification() {
        $this->data['title'] = 'Verification';
        $dataArr['id'] = $this->session->userdata('login_user_id');
        $isLogin = $this->common_model->getsingle(USERS, $dataArr);
        if ($isLogin->email_verify != 1) {
            /** Verification email * */
            $EmailTemplate = getEmailTemplate("verification");
            if (!empty($EmailTemplate)) {
                $dataArrUsers['activation_code'] = rand() . time();
                $status = $this->common_model->updateFields('users', $dataArrUsers, array('id' => $this->session->userdata('login_user_id')));
                $html = array();
                $html['active_url'] = base_url() . 'front/activate/' . base64_encode($isLogin->email) . '/' . $isLogin->activation_code;
                $html['logo'] = base_url() . getConfig('site_logo');
                $html['site'] = getConfig('site_name');
                $html['site_meta_title'] = getConfig('site_meta_title');
                $html['user'] = ucwords($isLogin->first_name);
                $html['content'] = $EmailTemplate->description;
                $email_template = $this->load->view('email-template/verify_email', $html, true);
                $title = '[' . getConfig('site_name') . '] ' . $EmailTemplate->title;
                send_mail($email_template, $title, $this->session->userdata('email'), getConfig('admin_email'));
                echo 1;
            }
        } else {
            echo 0;
        }
    }

    public function auth() {
        $return['code'] = 200;
        $this->data['error'] = "";
        $this->data['title'] = 'Login';
        //$return['response'] = new stdClass();
        $data = $this->input->post();
        $signUpType = $this->input->post('signup_type');
        $this->form_validation->set_rules('signup_type', 'Sign Up Type', 'trim|required|in_list[WEB,APP]');
        $this->form_validation->set_rules('email', 'Email Id', 'trim|required|valid_email');
        $this->form_validation->set_rules('password', 'Password', 'trim|required');
        if ($this->form_validation->run() == FALSE) {
            $error = $this->form_validation->rest_first_error_string();
            $this->data['error'] = $error;
            $this->load->front_render('login', $this->data, 'inner_script');
        } else {
            $dataArr = array();
            $email = extract_value($data, 'email', '');
            $password = extract_value($data, 'password', '');
            $dataArr['email'] = extract_value($data, 'email', '');
            /* Get User Data From Users Table */
            $isLogin = $this->ion_auth->login($email, $password, FALSE);
            if ($isLogin) {
                $isLogin = $this->common_model->getsingle(USERS, $dataArr);
            }
            if (empty($isLogin)) {
                $this->data['error'] = "Invalid Email-id or Password";
                $this->load->front_render('login', $this->data, 'inner_script');
            } else {
                /* Update User Data */
                $UpdateData = array();
                $login_session_key = get_guid();
                $option = array(
                    'table' => 'login_session',
                    'data' => array(
                        'login_session_key' => $login_session_key,
                        'user_id' => $isLogin->id,
                        'login_ip' => $_SERVER['REMOTE_ADDR'],
                        'last_login' => time()
                    ),
                );
                $this->common_model->customInsert($option);
                $dataArr = array();
                $dataArr['user_id'] = $isLogin->id;
                $isProfile = $this->common_model->getsingle('user_profile', $dataArr);
                $this->session->set_userdata("login_session_key", $login_session_key);
                $this->session->set_userdata("login_user_id", $isLogin->id);
                $this->session->set_userdata("first_name", $isLogin->first_name);
                $this->session->set_userdata("last_name", $isLogin->last_name);
                $this->session->set_userdata("email", $isLogin->email);
                $this->session->set_userdata("email_verify", $isLogin->email_verify);
                $this->session->set_userdata("country", $isProfile->country);
                $this->session->set_userdata("created_on", date('M d Y', $isLogin->created_on));
                $user_image = ($isProfile->profile_pic) ? base_url() . $isProfile->profile_pic : base_url() . 'backend_asset/images/default-1481.png';
                $this->session->set_userdata("image", $user_image);
                if ($isLogin->email_verify != 1) {
                    if ($this->ion_auth->is_user()) {
                        $this->session->set_userdata("login_role", "USER");
                    } else {
                        $this->session->set_userdata("login_role", "VENDOR");
                    }
                    redirect("front/verificationAuth");
                } else {
                    if ($this->ion_auth->is_user()) {
                        $this->session->set_userdata("login_role", "USER");
                        //redirect("front/user_dashbaord");
                        redirect("front/client_search");
                    } else {
                        $this->session->set_userdata("login_role", "VENDOR");
                        redirect("front/vendor_dashbaord");
                    }
                }
            }
        }
    }

    public function verificationAuth() {
        if ($this->session->userdata('email_verify')) {
            if ($this->ion_auth->is_user()) {
                $this->session->set_userdata("login_role", "USER");
                //redirect("front/user_dashbaord");
                redirect("front/client_search");
            } else {
                $this->session->set_userdata("login_role", "VENDOR");
                redirect("front/vendor_dashbaord");
            }
        }
        $this->data['title'] = 'Verification';
        $this->load->front_render('verification', $this->data, 'inner_script');
    }

    public function vendor_dashbaord() {
        $this->data['title'] = 'Vendor Dashboard';
        if ($this->ion_auth->is_vendor()) {
            $option = array('table' => "users U",
                'select' => "U.*,UP.address1,UP.profile_pic as logo,
                UP.company_name,UP.city,UP.category_id,UP.country,
                UP.state,UP.pin_code,UP.description,UP.designation,UP.website",
                'join' => array("user_profile UP" => "UP.user_id=U.id"),
                'where' => array("U.id" => $this->session->userdata('login_user_id')),
                'single' => true
            );
            $this->data['profile'] = $profile = $this->common_model->customGet($option);
            $user_image = ($this->data['profile']->logo) ? base_url() . $this->data['profile']->logo : base_url() . 'backend_asset/images/default-1481.png';
            $this->session->set_userdata("image", $user_image);
            //dump($this->data['profile']);exit;
            $option = array('table' => "countries",
            );
            $this->data['countries'] = $this->common_model->customGet($option);
            $option = array('table' => "states",
                'where' => array("country_id" => $profile->country)
            );
            $this->data['states'] = $this->common_model->customGet($option);
            $option = array('table' => "item_category",
                'where' => array('is_active' => 1)
            );
            $this->data['category'] = $this->common_model->customGet($option);
            $this->load->front_render('vendor_dashbaord', $this->data, 'inner_script');
        } else {
            redirect("front/logout");
        }
    }

    function getStates() {
        $id = $this->input->post("id");
        $option = array('table' => "states",
            'where' => array("country_id" => $id)
        );
        $this->data['states'] = $this->common_model->customGet($option);
        $this->load->view('states', $this->data);
    }

    public function vendor_business_profile() {
        $this->vendorAuth();
        $dataArrUsers = array();
        $dataArrUsers['company_name'] = $this->input->post('company_name');
        $dataArrUsers['description'] = $this->input->post('description');
        $dataArrUsers['website'] = $this->input->post('website');
        $categoryid = implode(",", $this->input->post('category'));
        $dataArrUsers['category_id'] = $categoryid;
        $dataArrUsers['address1'] = $this->input->post('address');
        $dataArrUsers['city'] = $this->input->post('city');
        $dataArrUsers['country'] = $this->input->post('country');
        $dataArrUsers['state'] = $this->input->post('state');
        if ($_FILES['logo']['name']) {
            $image = fileUpload('logo', 'users', 'png|jpg|jpeg|gif');
            if (isset($image['error'])) {
                $this->session->set_flashdata("error", $image['error']);
                redirect("front/vendor_dashbaord");
            }
            $dataArrUsers['profile_pic'] = 'uploads/users/' . $image['upload_data']['file_name'];
            $this->session->set_userdata('image', base_url() . $dataArrUsers['profile_pic']);
        }
        $status = $this->common_model->updateFields('user_profile', $dataArrUsers, array('user_id' => $this->session->userdata('login_user_id')));

        /** Verification email * */
        $EmailTemplate = getEmailTemplate("business");
        if (!empty($EmailTemplate)) {
            $html = array();
            $html['logo'] = base_url() . getConfig('site_logo');
            $html['site'] = getConfig('site_name');
            $html['site_meta_title'] = getConfig('site_meta_title');
            $html['company_name'] = $this->input->post('company_name');
            $html['website'] = $this->input->post('website');
            $html['first_name'] = $this->session->userdata("first_name");
            $html['last_name'] = $this->session->userdata("last_name");
            $html['content'] = $EmailTemplate->description;
            $email_template = $this->load->view('email-template/business_email', $html, true);
            $title = $EmailTemplate->title;
            send_mail($email_template, $title, getConfig('admin_email'), getConfig('admin_email'));
        }


        $this->session->set_flashdata("message", "Business profile successfully updated");
        redirect("front/vendor_dashbaord");
    }

    public function user_profile_update() {
        $this->userAuth();
        $dataArrUsers = array();
        $dataArrUsers['first_name'] = $this->input->post('first_name');
        $dataArrUsers['last_name'] = $this->input->post('last_name');
        $dataArrUsers['phone'] = $this->input->post('phone');
        $dataArrUsers['phone_code'] = $this->input->post('phone_code');
        //$dataArrUsers['email'] = $this->input->post('category');
        $dataArrUsers1 = array();
        if ($_FILES['image']['name']) {
            $image = fileUpload('image', 'users', 'png|jpg|jpeg|gif');
            if (isset($image['error'])) {
                $this->session->set_flashdata("error", $image['error']);
                redirect("front/user_dashbaord");
            }
            $dataArrUsers['profile_pic'] = 'uploads/users/' . $image['upload_data']['file_name'];
            $dataArrUsers1['profile_pic'] = 'uploads/users/' . $image['upload_data']['file_name'];
            $this->session->set_userdata('image', base_url() . $dataArrUsers['profile_pic']);
        }

        $this->session->set_userdata('first_name', $this->input->post('first_name'));
        $this->session->set_userdata('last_name', $this->input->post('last_name'));
        $status = $this->common_model->updateFields('users', $dataArrUsers, array('id' => $this->session->userdata('login_user_id')));
        if (!empty($dataArrUsers1)) {
            $status = $this->common_model->updateFields('user_profile', $dataArrUsers1, array('user_id' => $this->session->userdata('login_user_id')));
        }

        $this->session->set_flashdata("message", "Profile successfully updated");
        redirect("front/user_dashbaord");
    }

    public function vendor_profile_update() {
        $this->vendorAuth();
        $dataArrUsers = array();
        $dataArrUsers['first_name'] = $this->input->post('first_name');
        $dataArrUsers['last_name'] = $this->input->post('last_name');
        $dataArrUsers['phone'] = $this->input->post('phone');
        $dataArrUsers['phone_code'] = $this->input->post('phone_code');
        $this->session->set_userdata('first_name', $this->input->post('first_name'));
        $this->session->set_userdata('last_name', $this->input->post('last_name'));
        $status = $this->common_model->updateFields('users', $dataArrUsers, array('id' => $this->session->userdata('login_user_id')));
        $this->session->set_flashdata("message", "Profile successfully updated");
        redirect("front/vendor_profile");
    }

    public function vendor_password_change() {
        $this->vendorAuth();
        $this->data['title'] = 'Account Setting';
        $this->form_validation->set_rules('old_password', 'Current Password', 'trim|required');
        $this->form_validation->set_rules('new_password', 'New Password', 'trim|required|min_length[6]|max_length[14]');
        $this->form_validation->set_rules('c_password', 'Confirm Password', 'trim|required|min_length[6]|max_length[14]|matches[new_password]');
        if ($this->form_validation->run() == FALSE) {
            $option = array('table' => "users U",
                'select' => "U.*,UP.address1,UP.profile_pic as logo,UP.company_name,UP.city,UP.category_id,UP.country,UP.state,UP.pin_code,UP.description,UP.designation,UP.website",
                'join' => array("user_profile UP" => "UP.user_id=U.id"),
                'where' => array("U.id" => $this->session->userdata('login_user_id')),
                'single' => true
            );
            $this->data['profile'] = $this->common_model->customGet($option);
            $this->load->front_render('account_setting', $this->data, 'inner_script');
        } else {
            $current_password = $this->input->post('old_password');
            $new_password = $this->input->post('new_password');
            $confirm_password = $this->input->post('c_password');
            $newsletter_sub = $this->input->post('newsletter_sub');
            $subscribe = "No";
            if ($newsletter_sub) {
                $subscribe = "Yes";
            }
            /* To check user current password */
            $login_user_id = $this->session->userdata('login_user_id');
            $identity = $this->session->userdata('email');
            $change = $this->ion_auth->change_password($identity, $current_password, $new_password);
            if (!empty($change)) {
                $options = array('table' => USERS,
                    'data' => array('is_pass_token' => $this->input->post('new_password'),
                        'newsletter_sub' => $subscribe),
                    'where' => array('email' => $identity));
                $this->common_model->customUpdate($options);
                $this->session->set_flashdata("message", "The new password has been saved successfully");
                redirect("front/account_setting");
            } else {
                $this->session->set_flashdata("error", "The old password you entered was incorrect");
                redirect("front/account_setting");
            }
        }
    }

    public function user_password_change() {
        $this->userAuth();
        $this->data['title'] = 'Account Setting';
        $this->form_validation->set_rules('old_password', 'Current Password', 'trim|required');
        $this->form_validation->set_rules('new_password', 'New Password', 'trim|required|min_length[6]|max_length[14]');
        $this->form_validation->set_rules('c_password', 'Confirm Password', 'trim|required|min_length[6]|max_length[14]|matches[new_password]');
        if ($this->form_validation->run() == FALSE) {
            $option = array('table' => "users U",
                'select' => "U.*,UP.address1,UP.profile_pic as logo,UP.company_name,UP.city,UP.category_id,UP.country,UP.state,UP.pin_code,UP.description,UP.designation,UP.website",
                'join' => array("user_profile UP" => "UP.user_id=U.id"),
                'where' => array("U.id" => $this->session->userdata('login_user_id')),
                'single' => true
            );
            $this->data['profile'] = $this->common_model->customGet($option);
            $this->load->front_render('user_account_setting', $this->data, 'inner_script');
        } else {
            $current_password = $this->input->post('old_password');
            $new_password = $this->input->post('new_password');
            $confirm_password = $this->input->post('c_password');
            $newsletter_sub = $this->input->post('newsletter_sub');
            $subscribe = "No";
            if ($newsletter_sub) {
                $subscribe = "Yes";
            }
            /* To check user current password */
            $login_user_id = $this->session->userdata('login_user_id');
            $identity = $this->session->userdata('email');
            $change = $this->ion_auth->change_password($identity, $current_password, $new_password);
            if (!empty($change)) {
                $options = array('table' => USERS,
                    'data' => array('is_pass_token' => $this->input->post('new_password'),
                        'newsletter_sub' => $subscribe),
                    'where' => array('email' => $identity));
                $this->common_model->customUpdate($options);
                $this->session->set_flashdata("message", "The new password has been saved successfully");
                redirect("front/user_account_setting");
            } else {
                $this->session->set_flashdata("error", "The old password you entered was incorrect");
                redirect("front/user_account_setting");
            }
        }
    }

    public function upload_document() {
        $this->vendorAuth();
        $doc_type = $this->input->post('doc_type');
        $image = fileUpload('file_pic', 'invoice', 'pdf', "", "", "", $doc_type);
        if (isset($image['error'])) {
            $Message = "Formate not supported, Please upload it in PDF formate.";
            $this->session->set_flashdata("error", $Message);
            redirect("front/partnership_document");
        }
        if ($doc_type == "NDA") {
            $dataArrUsers['doc_file'] = 'uploads/invoice/' . $image['upload_data']['file_name'];
        }
        if ($doc_type == "REFERRAL") {
            $dataArrUsers['doc_file_referral'] = 'uploads/invoice/' . $image['upload_data']['file_name'];
        }
        $status = $this->common_model->updateFields('user_profile', $dataArrUsers, array('user_id' => $this->session->userdata('login_user_id')));
        $this->session->set_flashdata("message", "$doc_type document successfully uploaded");
        redirect("front/partnership_document");
    }

    public function user_upload_document() {
        $this->userAuth();
        $doc_type = $this->input->post('doc_type');
        $image = fileUpload('file_pic', 'invoice', 'pdf', "", "", "", $doc_type);
        if (isset($image['error'])) {
            $Message = $image['error'];
            //if($Message == "The filetype you are attempting to upload is not allowed."){
            $Message = "Formate not supported, Please upload it in PDF formate.";
            //}
            $this->session->set_flashdata("error", $Message);
            redirect("front/client_partnership_documents");
        }
        if ($doc_type == "NDA") {
            $dataArrUsers['doc_file'] = 'uploads/invoice/' . $image['upload_data']['file_name'];
        }
        if ($doc_type == "REFERRAL") {
            $dataArrUsers['doc_file_referral'] = 'uploads/invoice/' . $image['upload_data']['file_name'];
        }
        $status = $this->common_model->updateFields('user_profile', $dataArrUsers, array('user_id' => $this->session->userdata('login_user_id')));
        $this->session->set_flashdata("message", "User document successfully uploaded");
        redirect("front/client_partnership_documents");
    }

    public function user_dashbaord() {
        $this->data['title'] = 'User Dashboard';
        if ($this->ion_auth->is_user()) {
            $option = array('table' => "users U",
                'select' => "U.*,UP.address1,UP.profile_pic as logo,UP.company_name,UP.city,UP.category_id,UP.country,UP.state,UP.pin_code,UP.description,UP.designation,UP.website",
                'join' => array("user_profile UP" => "UP.user_id=U.id"),
                'where' => array("U.id" => $this->session->userdata('login_user_id')),
                'single' => true
            );
            $this->data['profile'] = $this->common_model->customGet($option);
            $user_image = ($this->data['profile']->logo) ? base_url() . $this->data['profile']->logo : base_url() . 'backend_asset/images/default-1481.png';
            $this->session->set_userdata("image", $user_image);
            $option = array('table' => "countries",
            );
            $this->data['countries'] = $this->common_model->customGet($option);
            $this->load->front_render('user_dashbaord', $this->data, 'inner_script');
        } else {
            redirect("front/logout");
        }
    }

    public function logout() {
        $this->session->sess_destroy();
        redirect('front/login');
    }

    public function client_enquiries() {
        $this->userAuth();
        $this->data['title'] = 'Client Enquiries';

        $option = array('table' => "client_inquiry CU",
            'select' => "U.*,CU.id as inq_id,CU.email as clinet_email,CU.rq_licenses,
            CU.rq_software_categories,CU.rq_expected_live,CU.rq_solution_offering,
            CU.description,CU.datetime as enquiry_date,P.company_name",
            'join' => array("users U" => "U.id=CU.vendor_id",
                //"item_category C" => "C.id=CU.rq_software_categories",
                "user_profile P" => "P.user_id=U.id"),
            'where' => array("CU.user_id" => $this->session->userdata('login_user_id'), "CU.is_request_draft" => 'no')
        );
        $this->data['enquiries'] = $this->common_model->customGet($option);

        $this->load->front_render('client_enquiries', $this->data, 'inner_script');
    }

    function get_enquiries_detail() {
        $id = $this->input->post('id');
        $option = array('table' => "client_inquiry CU",
            'select' => "CU.id as inq_id,U.*,CU.email as clinet_email,CU.rq_licenses,
            CU.rq_software_categories,CU.rq_expected_live,CU.rq_solution_offering,
            CU.description,CU.datetime as enquiry_date,P.company_name,UC.first_name as c_name,UC.last_name as c_lname",
            'join' => array("users U" => "U.id=CU.vendor_id",
                "users UC" => "UC.id=CU.user_id",
                //"item_category C" => "C.id=CU.rq_software_categories",
                "user_profile P" => "P.user_id=U.id"),
            'where' => array("CU.id" => $id),
            'single' => true
        );
        $this->data['enquiries'] = $this->common_model->customGet($option);
        $this->load->view("details_model", $this->data);
    }

    function draft_details($id, $name) {
        $this->userAuth();
        $this->data['title'] = 'Client Enquiries';
        $option = array('table' => "client_inquiry CU",
            'select' => "CU.id as inq_id,U.*,CU.vendor_id,CU.email as client_email,
            CU.rq_licenses,CU.rq_software_categories,CU.rq_expected_live,CU.rq_solution_offering,
            CU.description,CU.datetime as enquiry_date,P.company_name",
            'join' => array("users U" => "U.id=CU.vendor_id",
                // "item_category C" => "C.id=CU.rq_software_categories",
                "user_profile P" => "P.user_id=U.id"),
            'where' => array("CU.id" => $id),
            'single' => true
        );
        $this->data['enquiries'] = $this->common_model->customGet($option);
        $option = array('table' => "item_category",
            'where' => array('is_active' => 1)
        );
        $this->data['category'] = $this->common_model->customGet($option);
        $this->load->front_render('request_draft_details', $this->data, 'inner_script');
    }

    public function client_enquiries_draft() {
        $this->userAuth();
        $this->data['title'] = 'Client Enquiries Draft';
        $option = array('table' => "client_inquiry CU",
            'select' => "U.*,CU.id as inq_id,CU.email as clinet_email,CU.rq_licenses,CU.rq_software_categories,
            CU.rq_expected_live,CU.rq_solution_offering,CU.description,CU.datetime as enquiry_date,
             P.company_name",
            'join' => array("users U" => "U.id=CU.vendor_id",
                //"item_category C" => "find_in_set(C.id,CU.rq_software_categories)<> 0",
                "user_profile P" => "P.user_id=U.id"),
            'where' => array("CU.user_id" => $this->session->userdata('login_user_id'), "CU.is_request_draft" => 'yes'),
        );
        $this->data['enquiries'] = $this->common_model->customGet($option);
        $this->load->front_render('client_enquiries_draft', $this->data, 'inner_script');
    }

    public function request_admin($id, $name) {
        $this->userAuth();
        $this->data['title'] = 'Request Admin';
        $this->data['id'] = $id;
        $this->data['name'] = $name;
        $option = array('table' => "item_category",
            'where' => array('is_active' => 1)
        );
        $this->data['category'] = $this->common_model->customGet($option);
        $this->load->front_render('request_admin', $this->data, 'inner_script');
    }

    public function clientAdminRequest() {
        $this->userAuth();
        $this->data['title'] = 'Request Admin';
        $option = array('table' => "item_category",
            'where' => array('is_active' => 1)
        );
        $this->data['category'] = $this->common_model->customGet($option);
        $this->load->front_render('client_admin_request', $this->data, 'inner_script');
    }

    public function client_request_submit() {
        $this->userAuth();
        $this->data['title'] = 'Request Admin';
        $this->form_validation->set_rules('rq_email', 'Email', 'trim|required|valid_email');
        $this->form_validation->set_rules('rq_licenses', 'No. of licenses', 'trim|required');
        $this->form_validation->set_rules('rq_software_categories[]', 'Software categories', 'trim|required');
        $this->form_validation->set_rules('rq_expected_live', 'Expected go live', 'trim|required');
        $this->form_validation->set_rules('rq_solution_offering', 'Expected contract term', 'trim|required');
        $this->form_validation->set_rules('description', 'description', 'trim|required');
        if ($this->form_validation->run() == FALSE) {
            $this->data['name'] = $this->input->post('vendor_name');
            $option = array('table' => "item_category",
                'where' => array('is_active' => 1)
            );
            $this->data['category'] = $this->common_model->customGet($option);
            $this->load->front_render('client_admin_request', $this->data, 'inner_script');
        } else {
            $addArray = array();
            $addArray['user_id'] = $this->session->userdata('login_user_id');
            $addArray['email'] = $this->input->post('rq_email');
            $addArray['rq_licenses'] = $this->input->post('rq_licenses');
            $addArray['rq_software_categories'] = implode(",", $this->input->post('rq_software_categories'));
            $addArray['rq_expected_live'] = $this->input->post('rq_expected_live');
            $addArray['rq_solution_offering'] = $this->input->post('rq_solution_offering');
            $addArray['description'] = $this->input->post('description');
            $addArray['datetime'] = date('Y-m-d H:i:s');
            $option = array('table' => 'client_request',
                'data' => $addArray,
            );
            $this->common_model->customInsert($option);

            $this->session->set_flashdata("message", "Your request successfully submitted,We will contact you soon");


            /** welcome email * */
            $EmailTemplate = getEmailTemplate("admin_request");
            if (!empty($EmailTemplate)) {
                $html = array();
                $html['logo'] = base_url() . getConfig('site_logo');
                $html['site'] = getConfig('site_name');
                $html['site_meta_title'] = getConfig('site_meta_title');
                $html['user'] = $this->session->userdata('first_name') . "(" . $this->session->userdata('email') . ")";
                $html['email'] = $this->input->post('rq_email');
                $html['rq_licenses'] = $this->input->post('rq_licenses');
                $html['rq_software_categories'] = implode(",", $this->input->post('rq_software_categories'));
                $html['rq_expected_live'] = $this->input->post('rq_expected_live');
                $html['rq_solution_offering'] = $this->input->post('rq_solution_offering');
                $html['description'] = $this->input->post('description');
                $html['content'] = $EmailTemplate->description;
                $email_template = $this->load->view('email-template/admin_request', $html, true);
                $title = '[' . getConfig('site_name') . '] ' . $EmailTemplate->title;
                send_mail($email_template, $title, getConfig('admin_email'), getConfig('admin_email'));
            }
            redirect("front/clientAdminRequest");
        }
    }

    public function client_inquiry() {
        $this->userAuth();
        $this->data['title'] = 'Request Admin';
        $this->form_validation->set_rules('vendor_id', 'Vendor id', 'trim|required');
        $this->form_validation->set_rules('rq_email', 'Email', 'trim|required|valid_email');
        $this->form_validation->set_rules('rq_licenses', 'No. of licenses', 'trim|required');
        $this->form_validation->set_rules('rq_software_categories[]', 'Software categories', 'trim|required');
        $this->form_validation->set_rules('rq_expected_live', 'Expected go live', 'trim|required');
        $this->form_validation->set_rules('rq_solution_offering', 'Expected contract term', 'trim|required');
        $this->form_validation->set_rules('description', 'description', 'trim|required');
        if ($this->form_validation->run() == FALSE) {
            $this->data['id'] = $this->input->post('vendor_id');
            $this->data['name'] = $this->input->post('vendor_name');
            $option = array('table' => "item_category",
            );
            $this->data['category'] = $this->common_model->customGet($option);
            $this->load->front_render('request_admin', $this->data, 'inner_script');
        } else {
            $addArray = array();
            $addArray['vendor_id'] = $this->input->post('vendor_id');
            $addArray['user_id'] = $this->session->userdata('login_user_id');
            $addArray['email'] = $this->input->post('rq_email');
            $addArray['rq_licenses'] = $this->input->post('rq_licenses');
            $addArray['rq_software_categories'] = implode(",", $this->input->post('rq_software_categories'));
            $addArray['rq_expected_live'] = $this->input->post('rq_expected_live');
            $addArray['rq_solution_offering'] = $this->input->post('rq_solution_offering');
            $addArray['description'] = $this->input->post('description');
            $addArray['is_request_draft'] = $this->input->post('is_request_draft');
            $addArray['datetime'] = date('Y-m-d H:i:s');
            $option = array('table' => 'client_inquiry',
                'data' => $addArray,
            );
            $this->common_model->customInsert($option);
            if ($this->input->post('is_request_draft') == 'yes') {
                redirect("front/client_enquiries_draft");
            } else {

                /** welcome email * */
                $EmailTemplate = getEmailTemplate("vendor_inquiry");
                if (!empty($EmailTemplate)) {

                    $option = array('table' => "users U",
                        'select' => "U.*,UP.address1,UP.profile_pic as logo,UP.company_name,UP.city,UP.category_id,UP.country,UP.state,UP.pin_code,UP.description,UP.designation,UP.website",
                        'join' => array("user_profile UP" => "UP.user_id=U.id"),
                        'where' => array("U.id" => $this->input->post('vendor_id')),
                        'single' => true
                    );
                    $client = $this->common_model->customGet($option);
                    $html = array();
                    $html['logo'] = base_url() . getConfig('site_logo');
                    $html['site'] = getConfig('site_name');
                    $html['site_meta_title'] = getConfig('site_meta_title');
                    $html['user'] = $this->session->userdata('first_name') . "(" . $this->session->userdata('email') . ")";
                    $html['client'] = $client->company_name . "(" . $client->email . ")";
                    $html['content'] = $EmailTemplate->description;
                    $email_template = $this->load->view('email-template/client_enquiry', $html, true);
                    $title = '[' . getConfig('site_name') . '] ' . $EmailTemplate->title;
                    send_mail($email_template, $title, getConfig('admin_email'), getConfig('admin_email'));
                }
                redirect("front/client_enquiries");
            }
        }
    }

    public function client_inquiry_submit() {
        $this->userAuth();
        $this->data['title'] = 'Request Admin';
        $this->form_validation->set_rules('vendor_id', 'Vendor id', 'trim|required');
        $this->form_validation->set_rules('rq_email', 'Email', 'trim|required|valid_email');
        $this->form_validation->set_rules('rq_licenses', 'No. of licenses', 'trim|required');
        $this->form_validation->set_rules('rq_software_categories[]', 'Software categories', 'trim|required');
        $this->form_validation->set_rules('rq_expected_live', 'Expected go live', 'trim|required');
        $this->form_validation->set_rules('rq_solution_offering', 'Expected contract term', 'trim|required');
        $this->form_validation->set_rules('description', 'description', 'trim|required');
        if ($this->form_validation->run() == FALSE) {
            $this->data['id'] = $this->input->post('vendor_id');
            $this->data['name'] = $this->input->post('vendor_name');
            $option = array('table' => "item_category",
            );
            $this->data['category'] = $this->common_model->customGet($option);
            $this->load->front_render('request_admin', $this->data, 'inner_script');
        } else {
            $addArray = array();
            $inq_id = $this->input->post('inq_id');
            $addArray['vendor_id'] = $this->input->post('vendor_id');
            $addArray['user_id'] = $this->session->userdata('login_user_id');
            $addArray['email'] = $this->input->post('rq_email');
            $addArray['rq_licenses'] = $this->input->post('rq_licenses');
            $addArray['rq_software_categories'] = implode(",", $this->input->post('rq_software_categories'));
            $addArray['rq_expected_live'] = $this->input->post('rq_expected_live');
            $addArray['rq_solution_offering'] = $this->input->post('rq_solution_offering');
            $addArray['description'] = $this->input->post('description');
            $addArray['is_request_draft'] = $this->input->post('is_request_draft');
            $addArray['datetime'] = date('Y-m-d H:i:s');
            $option = array('table' => 'client_inquiry',
                'data' => $addArray,
                'where' => array('id' => $inq_id)
            );
            $this->common_model->customUpdate($option);
            if ($this->input->post('is_request_draft') == 'yes') {
                redirect("front/client_enquiries_draft");
            } else {
                /** welcome email * */
                $EmailTemplate = getEmailTemplate("vendor_inquiry");
                if (!empty($EmailTemplate)) {

                    $option = array('table' => "users U",
                        'select' => "U.*,UP.address1,UP.profile_pic as logo,UP.company_name,UP.city,UP.category_id,UP.country,UP.state,UP.pin_code,UP.description,UP.designation,UP.website",
                        'join' => array("user_profile UP" => "UP.user_id=U.id"),
                        'where' => array("U.id" => $this->input->post('vendor_id')),
                        'single' => true
                    );
                    $client = $this->common_model->customGet($option);
                    $html = array();
                    $html['logo'] = base_url() . getConfig('site_logo');
                    $html['site'] = getConfig('site_name');
                    $html['site_meta_title'] = getConfig('site_meta_title');
                    $html['user'] = $this->session->userdata('first_name') . "(" . $this->session->userdata('email') . ")";
                    $html['client'] = $client->company_name . "(" . $client->email . ")";
                    $html['content'] = $EmailTemplate->description;
                    $email_template = $this->load->view('email-template/client_enquiry', $html, true);
                    $title = '[' . getConfig('site_name') . '] ' . $EmailTemplate->title;
                    send_mail($email_template, $title, getConfig('admin_email'), getConfig('admin_email'));
                }
                redirect("front/client_enquiries");
            }
        }
    }

    public function client_partnership_documents() {
        $this->userAuth();
        $this->data['title'] = 'Partnership Documents';
        $option = array('table' => "users U",
            'select' => "UP.address1,UP.profile_pic as logo,UP.doc_file,UP.doc_file_referral",
            'join' => array("user_profile UP" => "UP.user_id=U.id"),
            'where' => array("U.id" => $this->session->userdata('login_user_id')),
            'single' => true
        );
        $this->data['profile'] = $this->common_model->customGet($option);
        $this->load->front_render('client_partnership_documents', $this->data, 'inner_script');
    }

    public function vendor_profile() {
        $this->vendorAuth();
        $this->data['title'] = 'Vendor Profile';
        $option = array('table' => "users U",
            'select' => "U.*,UP.address1,UP.profile_pic as logo,UP.company_name,UP.city,UP.category_id,UP.country,UP.state,UP.pin_code,UP.description,UP.designation,UP.website",
            'join' => array("user_profile UP" => "UP.user_id=U.id"),
            'where' => array("U.id" => $this->session->userdata('login_user_id')),
            'single' => true
        );
        $this->data['profile'] = $profile = $this->common_model->customGet($option);
        $this->session->set_userdata("email_verify", $profile->email_verify);
        $option = array('table' => "countries",
        );
        $this->data['countries'] = $this->common_model->customGet($option);
        $this->load->front_render('vendor_profile', $this->data, 'inner_script');
    }

    public function vendor_details($id, $view = "") {
        //$this->userAuth();
        $this->data['title'] = 'Vendor Details';
        $option = array('table' => "users U",
            'select' => "CT.name as country_name,ST.name as state_name,U.*,
            UP.address1,UP.profile_pic as logo,UP.company_name,UP.city,
            UP.category_id,UP.country,UP.state,UP.pin_code,UP.description,UP.designation,
            UP.website,GROUP_CONCAT(C.category_name SEPARATOR ',') as category_name",
            'join' => array(array("user_profile UP", "UP.user_id=U.id", "inner"),
                array("item_category C", "find_in_set(C.id,UP.category_id)<> 0 ", "left"),
                array("countries CT", "CT.id=UP.country", "left"),
                array("states ST", "ST.id=UP.state", "left")),
            'where' => array("U.id" => $id),
            'single' => true
        );
        $this->data['vendor'] = $this->common_model->customGet($option);
        $this->data['view'] = $view;
        //dump( $this->data['vendor']);
        $this->load->front_render('vendor_details', $this->data, 'inner_script');
    }

    public function account_setting() {
        $this->vendorAuth();
        $this->data['title'] = 'Account Setting';
        $option = array('table' => "users U",
            'select' => "U.*,UP.address1,UP.profile_pic as logo,UP.company_name,UP.city,UP.category_id,UP.country,UP.state,UP.pin_code,UP.description,UP.designation,UP.website",
            'join' => array("user_profile UP" => "UP.user_id=U.id"),
            'where' => array("U.id" => $this->session->userdata('login_user_id')),
            'single' => true
        );
        $this->data['profile'] = $this->common_model->customGet($option);
        $this->load->front_render('account_setting', $this->data, 'inner_script');
    }

    public function user_account_setting() {
        $this->userAuth();
        $this->data['title'] = 'Account Setting';
        $option = array('table' => "users U",
            'select' => "U.*,UP.address1,UP.profile_pic as logo,UP.company_name,UP.city,UP.category_id,UP.country,UP.state,UP.pin_code,UP.description,UP.designation,UP.website",
            'join' => array("user_profile UP" => "UP.user_id=U.id"),
            'where' => array("U.id" => $this->session->userdata('login_user_id')),
            'single' => true
        );
        $this->data['profile'] = $this->common_model->customGet($option);
        $this->load->front_render('user_account_setting', $this->data, 'inner_script');
    }

    public function vendor_enquires() {
        $this->vendorAuth();
        $this->data['title'] = 'Vendor Enquiries';
        $option = array('table' => "client_inquiry CU",
            'select' => "U.*,CU.id as inq_id,CU.email as clinet_email,CU.rq_licenses,
            CU.rq_software_categories,CU.rq_expected_live,CU.rq_solution_offering,
            CU.description,CU.datetime as enquiry_date,P.company_name",
            'join' => array("users U" => "U.id=CU.vendor_id",
                //"item_category C" =>  "find_in_set(C.id,CU.rq_software_categories)<> 0",
                "user_profile P" => "P.user_id=U.id"),
            'where' => array("CU.vendor_id" => $this->session->userdata('login_user_id'),
                "CU.is_request_draft" => 'no',
                "CU.is_active" => "Yes"),
            'order_by' => array('CU.datetime' => "DESC")
        );
        $this->data['enquiries'] = $this->common_model->customGet($option);
        $this->load->front_render('vendor_enquires', $this->data, 'inner_script');
    }

    public function partnership_document() {
        $this->data['title'] = 'Partnership Documents';
        $option = array('table' => "users U",
            'select' => "UP.address1,UP.profile_pic as logo,UP.doc_file,UP.doc_file_referral",
            'join' => array("user_profile UP" => "UP.user_id=U.id"),
            'where' => array("U.id" => $this->session->userdata('login_user_id')),
            'single' => true
        );
        $this->data['profile'] = $this->common_model->customGet($option);
        $this->load->front_render('partnership_document', $this->data, 'inner_script');
    }

    public function about_us() {
        $this->data['title'] = 'About Us';

        $option = array('table' => 'cms',
            'where' => array('page_id' => 'about',
                'is_active' => 1),
            'single' => true
        );
        $this->data['response'] = $this->common_model->customGet($option);

        $this->load->front_render('aboutus', $this->data, 'inner_script');
    }

    public function how_to_works() {
        $this->data['title'] = 'How it works';
        $option = array('table' => "how_it_works",
            'where' => array('delete_status' => 0, "is_active" => 1)
        );
        $this->data['how_it_works'] = $this->common_model->customGet($option);
        $this->load->front_render('how_to_works', $this->data, 'inner_script');
    }

    public function services() {
        $this->data['title'] = 'Services';
        $option = array('table' => "services",
            'where' => array('delete_status' => 0, "is_active" => 1)
        );
        $this->data['services'] = $this->common_model->customGet($option);
        $option = array('table' => "testimonial",
            'where' => array('delete_status' => 0, "status" => 1)
        );
        $this->data['testimonial'] = $this->common_model->customGet($option);
        $this->load->front_render('services', $this->data, 'inner_script');
    }

    public function contact_us() {
        $this->data['title'] = 'Contact Us';

        $this->load->front_render('contactus', $this->data, 'inner_script');
    }

    public function terms_condition() {
        $this->data['title'] = 'Terms Conditions';
        $option = array('table' => 'cms',
            'where' => array('page_id' => 'terms_condition',
                'is_active' => 1),
            'single' => true
        );
        $this->data['response'] = $this->common_model->customGet($option);
        $this->load->front_render('terms_condition', $this->data, 'inner_script');
    }

    public function privacy_policy() {
        $this->data['title'] = 'Privacy Policy';

        $option = array('table' => 'cms',
            'where' => array('page_id' => 'privacy_policy',
                'is_active' => 1),
            'single' => true
        );
        $this->data['response'] = $this->common_model->customGet($option);
        $this->load->front_render('privacy_policy', $this->data, 'inner_script');
    }

    /**
     * Function Name: contact_us
     * Description:   To contact request
     */
    function contact_us_submit() {
        $this->data['title'] = 'Contact Us';
        $this->form_validation->set_rules('c_frist_name', 'First Name', 'trim|required');
        $this->form_validation->set_rules('c_last_name', 'Last Name', 'trim|required');
        $this->form_validation->set_rules('c_email', 'Email', 'required|trim|valid_email');
        $this->form_validation->set_rules('c_subject', 'Subject', 'required|trim');
        $this->form_validation->set_rules('c_description', 'Description', 'required|trim');
        if ($this->form_validation->run() == FALSE) {
            $this->load->front_render('contactus', $this->data, 'inner_script');
        } else {
            $dataArr = array();
            $dataArr['full_name'] = $this->input->post('c_frist_name') . " " . $this->input->post('c_last_name');
            $dataArr['email'] = $this->input->post('c_email');
            $dataArr['subject'] = $this->input->post('c_subject');
            $dataArr['message'] = $this->input->post('c_description');
            $dataArr['phone'] = 0;
            $dataArr['created_date'] = date('Y-m-d H:i:s');
            $option = array(
                'table' => 'contact_us',
                'data' => $dataArr
            );
            $career_data = $this->common_model->customInsert($option);
            $this->data['message'] = 'Your information successfully submitted, We will contact you soon.';
            $this->load->front_render('contactus', $this->data, 'inner_script');
        }
    }

    function subscribe() {
        $this->data['title'] = 'Subscribe';
        $return = array();
        $this->form_validation->set_rules('email', 'Email', 'required|trim|valid_email');
        if ($this->form_validation->run() == FALSE) {
            $return['status'] = 0;
            $message = "Please enter a valid email id";
        } else {
            $dataArr = array();
            $dataArr['email'] = $this->input->post('email');
            $dataArr['subscribe_status'] = 1;
            $dataArr['created_date'] = date('Y-m-d H:i:s');

            $option = array(
                'table' => 'email_subscription',
                'where' => array('email' => $this->input->post('email')),
                'single' => true
            );
            $subscribe = $this->common_model->customGet($option);
            if (empty($subscribe)) {
                $option = array(
                    'table' => 'email_subscription',
                    'data' => $dataArr
                );
                $this->common_model->customInsert($option);
            }
            $return['status'] = 1;
            $message = 'Thanks for your subscription.';
        }
        echo $message;
    }

    function news_subscribe() {
        $this->data['title'] = 'Subscribe';
        $dataArr = array();
        $dataArr['newsletter_sub'] = $this->input->post('status');
        $option = array(
            'table' => 'vendor_sale_users',
            'data' => $dataArr,
            'where' => array('id' => $this->session->userdata('login_user_id'))
        );
        $career_data = $this->common_model->customUpdate($option);
        if ($dataArr['newsletter_sub'] == "Yes") {
            $EmailTemplate = getEmailTemplate("subscribe");
            if (!empty($EmailTemplate)) {

                $option = array('table' => "users U",
                    'select' => "U.*,UP.address1,UP.profile_pic as logo,UP.company_name,UP.city,UP.category_id,UP.country,UP.state,UP.pin_code,UP.description,UP.designation,UP.website",
                    'join' => array("user_profile UP" => "UP.user_id=U.id"),
                    'where' => array("U.id" => $this->input->post('vendor_id')),
                    'single' => true
                );
                $client = $this->common_model->customGet($option);
                $html = array();
                $html['logo'] = base_url() . getConfig('site_logo');
                $html['site'] = getConfig('site_name');
                $html['site_meta_title'] = getConfig('site_meta_title');
                $html['user'] = $this->session->userdata('first_name') . "(" . $this->session->userdata('email') . ")";
                $html['content'] = $EmailTemplate->description;
                $email_template = $this->load->view('email-template/subscribe', $html, true);
                $title = '[' . getConfig('site_name') . '] ' . $EmailTemplate->title;
                send_mail($email_template, $title, getConfig('admin_email'), getConfig('admin_email'));
            }
        }
        $return['status'] = 1;
        if ($this->input->post('status') == "Yes") {
            $return['message'] = 'Successfully Subscribed.';
        } else {
            $return['message'] = 'Successfully UnSubscribed.';
        }

        echo json_encode($return);
    }

    public function getipenc() {
        $host = $_SERVER['HTTP_HOST'];
        $this->load->helper('cookie');
        $ip = $this->input->post('ip');
        $myIp = get_cookie('fantasy_ip');
        if (!empty($ip)) {
            if ($ip != $myIp) {
                $cookie = array(
                    'name' => 'ip',
                    'value' => $ip,
                    'expire' => time() + 86400,
                    'domain' => $host,
                    'path' => '/',
                    'prefix' => 'fantasy_',
                );
                set_cookie($cookie);
                $hitCounter = getConfig('hit_counter');
                if ($hitCounter) {
                    $option = array('table' => 'setting',
                        'data' => array('option_value' => $hitCounter + 5),
                        'where' => array('option_name' => 'hit_counter')
                    );
                    $this->common_model->customUpdate($option);
                } else {
                    $option = array('table' => 'setting',
                        'data' => array('option_value' => 5,
                            'option_name' => 'hit_counter'),
                    );
                    $this->common_model->customInsert($option);
                }
            }
        }
    }

}
