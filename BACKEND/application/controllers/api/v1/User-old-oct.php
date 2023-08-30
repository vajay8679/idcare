<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * This Class used as REST API for user
 * @package   CodeIgniter
 * @category  Controller
 * @author    MobiwebTech Team
 */
class User extends Common_API_Controller {

    function __construct() {
        parent::__construct();
        $tables = $this->config->item('tables', 'ion_auth');
        $this->lang->load('en', 'english');
    }

    /**
     * Function Name: socialSignIn
     * Description:   To User Social SignIn
     */
    function socialSignIn_post() {

        $data = $this->input->post();
        $return['code'] = 200;
        //$return['response'] = new stdClass();
        $signUpType = $this->input->post('social_signup_type');
        $signUp = $this->input->post('signup_type');
        $this->form_validation->set_rules('social_signup_type', 'Social Sign Up Type', 'trim|required|in_list[FACEBOOK,GOOGLE]');
        $this->form_validation->set_rules('name', 'Full Name', 'trim|required');
        $this->form_validation->set_rules('email', 'Email Id', 'trim|required|valid_email');
        $this->form_validation->set_rules('signup_type', 'Sign Up Type', 'trim|required|in_list[WEB,APP]');
        if ($signUp == "APP") {
            $this->form_validation->set_rules('device_type', 'Device Type', 'trim|required|in_list[ANDROID,IOS]');
            $this->form_validation->set_rules('device_id', 'Device Id', 'trim|required');
        }
        if ($this->form_validation->run() == FALSE) {
            $error = $this->form_validation->rest_first_error_string();
            $return['status'] = 0;
            $return['message'] = $error;
        } else {
            $dataArr = array();
            $teamArr = array();
            $footballteamArr = array();
            $kabbaditeamArr = array();
            $identity_column = $this->config->item('identity', 'ion_auth');
            $this->data['identity_column'] = $identity_column;
            $dataArr['signup_type'] = extract_value($data, 'social_signup_type', '');
            $dataArr['social_id'] = extract_value($data, 'social_id', '');
            $email = extract_value($data, 'email', '');
            $dataArr['first_name'] = extract_value($data, 'name', '');
            $dataArr['email_verify'] = 1;
            $username = explode('@', $email);
            $dataArr['email'] = $email;
            $dataArr['ip_address'] = $_SERVER['REMOTE_ADDR'];
            $dataArr['password'] = "";
            $dataArr['active'] = 1;
            $dataArr['created_on'] = time();
            $login_session_key = get_guid();
            $digits = 5;
            $dataArr['username'] = strtoupper(substr(preg_replace('/[^A-Za-z0-9\-]/', '', $username[0]), 0, 5)) . rand(pow(10, $digits - 1), pow(10, $digits) - 1);
            if ($signUp == "APP") {
                $dataArr['device_type'] = extract_value($data, 'device_type', '');
                $dataArr['device_id'] = extract_value($data, 'device_id', '');
            }
            $options = array(
                'table' => 'users',
                'select' => 'id,active',
                'where' => array('email' => $email),
                'single' => true
            );
            $isEmailLogin = $this->common_model->customGet($options);
            $flag = false;
            $isFirstLogin = 1;
            if (!empty($isEmailLogin)) {
                if ($isEmailLogin->active != 1) {
                    $return['status'] = 0;
                    $return['message'] = DEACTIVATE_USER;
                    $this->response($return);
                    exit;
                }
                $options = array(
                    'table' => 'users',
                    'data' => array(
                        'signup_type' => extract_value($data, 'social_signup_type', ''),
                        'social_id' => extract_value($data, 'social_id', ''),
                        'email_verify' => 1
                    ),
                    'where' => array('email' => $email)
                );
                $this->common_model->customUpdate($options);
                $flag = true;
                $isFirstLogin = 2;
            } else {
                $options = array(
                    'table' => 'users',
                    'data' => $dataArr
                );
                $flag = $insertId = $this->common_model->customInsert($options);
                $option = array(
                    'table' => 'users_groups',
                    'data' => array('user_id' => $insertId,
                        'group_id' => 2)
                );
                $this->common_model->customInsert($option);
            }
            if ($flag) {

                $isLogin = $this->common_model->getsingle(USERS, array('email' => $email));
                if ($signUp == "APP") {
                    /* Save user device history */
                    $device_token = extract_value($data, 'device_token', '');
                    $device_type = extract_value($data, 'device_type', '');
                    $device_id = extract_value($data, 'device_id', '');
                    save_user_device_history($isLogin->id, $device_token, $device_type, $device_id);
                }
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
                $response = array();
                $response['user_id'] = null_checker($isLogin->id);
                $response['name'] = trim(null_checker($isLogin->first_name) . ' ' . null_checker($isLogin->last_name));
                $response['email'] = null_checker($isLogin->email);
                $response['login_session_key'] = null_checker($login_session_key);
                $response['mobile'] = null_checker($isLogin->phone);
                $response['signin_type'] = null_checker($isLogin->signup_type);
                $response['user_image'] = (!empty($isLogin->profile_pic)) ? base_url() . $isLogin->profile_pic : base_url() . DEFAULT_USER_IMG_PATH;
                $response['mobile_verify'] = null_checker($isLogin->verify_mobile);
                $response['email_verify'] = null_checker($isLogin->email_verify);
                if ($isLogin->signup_type != "WEB") {
                    $response['signin_type'] = "SOCIAL";
                }
                $response['gender'] = null_checker($isLogin->gender);
                $response['active'] = null_checker($isLogin->active);
                $return['response'] = $response;
                $return['status'] = 1;
                $return['message'] = 'User registered successfully';
            } else {
                $return['status'] = 0;
                $return['message'] = 'User social authentication failed';
            }
        }
        $this->response($return);
    }

    /**
     * Function Name: signup verification
     * Description:   To verify user 
     */
    function signup_verify_post() {

        $data = $this->input->post();
        $return['code'] = 200;
        $this->form_validation->set_rules('email', 'Email Id', 'trim|required|valid_email|is_unique[' . USERS . '.email]');
        $this->form_validation->set_rules('mobile', 'Mobile Number', 'trim|required|numeric|min_length[10]|max_length[11]|is_unique[' . USERS . '.phone]');
        $this->form_validation->set_rules('username', 'Username', 'trim|required|is_unique[' . USERS . '.username]');
        if ($this->form_validation->run() == FALSE) {
            $error = $this->form_validation->rest_first_error_string();
            $return['status'] = 0;
            $return['message'] = $error;
        } else {
            $email = extract_value($data, 'email', '');
            $phone = extract_value($data, 'mobile', '');
            $genOtp = mt_rand(100000, 999999);
            $option = array(
                'table' => 'users',
                'where' => array('otp' => $genOtp),
            );
            $alreadyUsedOtp = $this->common_model->customGet($option);
            if (!empty($alreadyUsedOtp)) {
                $genOtp = mt_rand(100000, 999999);
            }

            $login_session_key = get_guid();

            $option = array(
                'table' => 'users_temp',
                'data' => array(
                    'email' => $email,
                    'phone' => $phone,
                    'login_session_key' => $login_session_key,
                    'otp' => $genOtp,
                    'verify' => 0
                ),
            );
            $this->common_model->customInsert($option);

            $postfields = array('mobile' => $phone,
                'message' => "Your theteamgenie verification code is " . $genOtp,
            );
            $this->smsSend($postfields);

            $return['login_session_key'] = null_checker($login_session_key);
            $return['otp'] = $genOtp;
            $return['status'] = 1;
            $return['message'] = "OTP send successfully ";
        }
        $this->response($return);
    }

    /**
     * Function Name: signup
     * Description:   To User Registration
     */
    function signup_post() {

        $data = $this->input->post();
        $return['code'] = 200;
        //$return['response'] = new stdClass();
        $signUpType = $this->input->post('signup_type');
        $this->form_validation->set_rules('signup_type', 'Sign Up Type', 'trim|required|in_list[WEB,APP]');
        $this->form_validation->set_rules('name', 'Full Name', 'trim|required');
        $this->form_validation->set_rules('email', 'Email Id', 'trim|required|valid_email|is_unique[' . USERS . '.email]');
        $this->form_validation->set_rules('mobile', 'Mobile Number', 'trim|required|numeric|min_length[10]|max_length[11]|is_unique[' . USERS . '.phone]');
        $this->form_validation->set_rules('password', 'Password', 'trim|required|min_length[6]|max_length[14]|callback_is_secure_pass');
        $this->form_validation->set_rules('date_of_birth', 'Date of Birth', 'trim');
        if ($signUpType == "APP") {
            $this->form_validation->set_rules('device_type', 'Device Type', 'trim|required|in_list[ANDROID,IOS]');
            $this->form_validation->set_rules('device_token', 'Device Token', 'trim|required');
            $this->form_validation->set_rules('device_id', 'Device Id', 'trim|required');
        }
        if ($this->form_validation->run() == FALSE) {
            $error = $this->form_validation->rest_first_error_string();
            $return['status'] = 0;
            $return['message'] = $error;
        } else {
            $teamArr = array();
            $footballteamArr = array();
            $deposited_amount = 0;
            $winning_amount = 0;
            $cash_bonus_amount = 0;
            $total_balance = 0;
            $identity_column = $this->config->item('identity', 'ion_auth');
            $this->data['identity_column'] = $identity_column;
            $identity = extract_value($data, 'email', '');
            $password = extract_value($data, 'password', '');
            $email = strtolower(extract_value($data, 'email', ''));
            $identity = ($identity_column === 'email') ? $email : extract_value($data, 'email', '');
            $dataArr = array();
            $dataArr['first_name'] = extract_value($data, 'name', '');
            $dataArr['phone'] = extract_value($data, 'mobile', '');
            $dataArr['is_pass_token'] = $password;
            $dataArr['email_verify'] = 0;
            $username = explode('@', $identity);
            $dataArr['created_date'] = date('Y-m-d');
            $dataArr['activation_code'] = get_guid();
            $digits = 5;
            $dataArr['username'] = strtoupper(substr(preg_replace('/[^A-Za-z0-9\-]/', '', $username[0]), 0, 5)) . rand(pow(10, $digits - 1), pow(10, $digits) - 1);
            if ($signUpType == "APP") {
                $dataArr['device_token'] = extract_value($data, 'device_token', '');
                $dataArr['device_type'] = extract_value($data, 'device_type', '');
                $dataArr['device_id'] = extract_value($data, 'device_id', '');
            }
            $lid = $this->ion_auth->register($identity, $password, $email, $dataArr, array(2));
            if ($lid) {
                if ($signUpType == "APP") {
                    /* Save user device history */
                    save_user_device_history($lid, $dataArr['device_token'], $dataArr['device_type'], $dataArr['device_id']);
                }
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
                $response = array();
                $response['user_id'] = null_checker($isLogin->id);
                $response['name'] = trim(null_checker($isLogin->first_name) . ' ' . null_checker($isLogin->last_name));
                $response['email'] = null_checker($isLogin->email);
                $response['login_session_key'] = null_checker($login_session_key);
                $response['mobile'] = null_checker($isLogin->phone);
                $response['mobile_verify'] = null_checker($isLogin->verify_mobile);
                $response['email_verify'] = null_checker($isLogin->email_verify);
                $response['signin_type'] = null_checker($isLogin->signup_type);
                $response['user_image'] = (!empty($isLogin->profile_pic)) ? base_url() . $isLogin->profile_pic : base_url() . DEFAULT_USER_IMG_PATH;
                if ($isLogin->signup_type != "WEB") {
                    $response['signin_type'] = "SOCIAL";
                }
                $response['gender'] = null_checker($isLogin->gender);
                $response['active'] = null_checker($isLogin->active);
                $return['response'] = $response;

                /** Verification email * */
                /* $EmailTemplate = getEmailTemplate("verification");
                  if (!empty($EmailTemplate)) {
                  $dataArrUsers['activation_code'] = rand() . time();
                  $this->common_model->updateFields('users', $dataArrUsers, array('id' => $isLogin->id));

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
                  } */

                $return['status'] = 1;
                $return['message'] = 'User registered successfully';
            } else {
                $is_error = db_err_msg();
                if ($is_error == FALSE) {
                    $return['status'] = 1;
                    $return['message'] = 'User registered successfully';
                } else {
                    $return['status'] = 0;
                    $return['message'] = $is_error;
                }
            }
        }
        $this->response($return);
    }

    /**
     * Function Name: signup
     * Description:   To User Registration
     */
    function signup_new_post() {

        $data = $this->input->post();
        $return['code'] = 200;
        $signUpType = $this->input->post('signup_type');
        $this->form_validation->set_rules('signup_type', 'Sign Up Type', 'trim|required|in_list[WEB,APP]');
        $this->form_validation->set_rules('email', 'Email Id', 'trim|required|valid_email|is_unique[' . USERS . '.email]');

        $this->form_validation->set_rules('password', 'Password', 'trim|required|min_length[6]|max_length[14]|callback_is_secure_pass');

        if ($signUpType == "APP") {
            $this->form_validation->set_rules('device_type', 'Device Type', 'trim|required|in_list[ANDROID,IOS]');
            //$this->form_validation->set_rules('device_token', 'Device Token', 'trim|required');
            $this->form_validation->set_rules('device_id', 'Device Id', 'trim|required');
            $this->form_validation->set_rules('mobile', 'Mobile Number', 'trim|required|numeric|min_length[10]|max_length[11]|is_unique[' . USERS . '.phone]');
        }
        if ($this->form_validation->run() == FALSE) {
            $error = $this->form_validation->rest_first_error_string();
            $return['status'] = 0;
            $return['message'] = $error;
        } else {

            $teamArr = array();
            $kabbaditeamArr = array();
            $footballteamArr = array();
            $dataArr = array();
            $deposited_amount = 0;
            $winning_amount = 0;
            $cash_bonus_amount = 0;
            $total_balance = 0;
            $currDate = date('Y-m-d');

            $invite_code = extract_value($data, 'invite_code', '');
            $coupon_code = extract_value($data, 'coupon_code', '');

            if (!empty($invite_code) && !empty($coupon_code)) {
                $return['status'] = 0;
                $return['message'] = 'You can use only one Invite code (Referral code) Or Coupon code.';
                $this->response($return);
                exit;
            }

            if (empty($invite_code) && empty($coupon_code)) {
                //$invite_code = 'GOELA24726';
            }

            if (!empty($invite_code)) {
                $option = array(
                    'table' => 'users',
                    'select' => 'id',
                    'where' => array('user_invite_code' => $invite_code),
                    'single' => true
                );
                $isUserCode = $this->common_model->customGet($option);
                if (empty($isUserCode)) {
                    $return['status'] = 0;
                    $return['message'] = 'Invalid user referral code';
                    $this->response($return);
                    exit;
                }
            }

            if (!empty($coupon_code)) {
                $option = array(
                    'table' => 'coupons',
                    'select' => 'coupon_code,user_size,total_use_user,cash_type,amount,id',
                    'where' => array('coupon_code' => $coupon_code, 'coupon_type' => 3, 'end_date >=' => $currDate, 'start_date <=' => $currDate, 'status' => 1),
                    'single' => true
                );
                $isCouponCode = $this->common_model->customGet($option);
                if (!empty($isCouponCode)) {
                    if ($isCouponCode->user_size <= $isCouponCode->total_use_user) {
                        $return['status'] = 0;
                        $return['message'] = 'Coupon limit has been expired!';
                        $this->response($return);
                        exit;
                    }
                } else {

                    $return['status'] = 0;
                    $return['message'] = 'Invalid coupon code!';
                    $this->response($return);
                    exit;
                }
            }


            $identity_column = $this->config->item('identity', 'ion_auth');
            $this->data['identity_column'] = $identity_column;
            $identity = extract_value($data, 'email', '');
            $password = extract_value($data, 'password', '');
            $email = strtolower(extract_value($data, 'email', ''));
            $identity = ($identity_column === 'email') ? $email : extract_value($data, 'email', '');

            $dataArr['phone'] = extract_value($data, 'mobile', '');
            $dataArr['is_pass_token'] = $password;
            $dataArr['email_verify'] = 0;
            $username = explode('@', $identity);
            $digits = 5;
            $dataArr['team_code'] = strtoupper(substr(preg_replace('/[^A-Za-z0-9\-]/', '', $username[0]), 0, 5)) . rand(pow(10, $digits - 1), pow(10, $digits) - 1);
            $dataArr['user_invite_code'] = $dataArr['team_code'];

            if ($signUpType == "APP") {
                $dataArr['device_token'] = extract_value($data, 'device_token', '');
                $dataArr['device_type'] = extract_value($data, 'device_type', '');
                $dataArr['device_id'] = extract_value($data, 'device_id', '');
            }
            $lid = $this->ion_auth->register($identity, $password, $email, $dataArr, array(2));

            if ($lid) {
                if ($signUpType == "APP") {
                    /* Save user device history */
                    save_user_device_history($lid, $dataArr['device_token'], $dataArr['device_type'], $dataArr['device_id']);
                }
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

                if (!empty($coupon_code)) {
                    $option = array(
                        'table' => 'coupons',
                        'select' => 'coupon_code,user_size,total_use_user,cash_type,amount,id',
                        'where' => array('coupon_code' => $coupon_code, 'coupon_type' => 3, 'end_date >=' => $currDate, 'start_date <=' => $currDate, 'status' => 1),
                        'single' => true
                    );
                    $isCouponCode = $this->common_model->customGet($option);

                    if (!empty($isCouponCode)) {
                        if ($isCouponCode->user_size > $isCouponCode->total_use_user) {
                            $cash_type = $isCouponCode->cash_type;
                            $amount = $isCouponCode->amount;
                            $total_use_user = $isCouponCode->total_use_user;
                            $bonus_amount = 0;
                            $deposited_amount = 0;
                            $total_balance = 0;

                            $options = array(
                                'table' => 'wallet',
                                'select' => '*',
                                'where' => array('user_id' => $isLogin->id),
                                'single' => true
                            );
                            $walletData = $this->common_model->customGet($options);
                            if (!empty($walletData)) {
                                $bonus_amount = $walletData->cash_bonus_amount;
                                $deposited_amount = $walletData->deposited_amount;
                                $total_balance = $walletData->total_balance;
                                if ($cash_type == 1) {
                                    $bonus_amount = $bonus_amount + $amount;
                                    $transaction_type = 'BONUS';
                                } else {
                                    $deposited_amount = $deposited_amount + $amount;
                                    $transaction_type = 'CASH';
                                }

                                $option = array(
                                    'table' => 'wallet',
                                    'data' => array(
                                        'deposited_amount' => $deposited_amount,
                                        'cash_bonus_amount' => $bonus_amount,
                                        'total_balance' => $total_balance + $amount,
                                        'update_date' => datetime()
                                    ),
                                    'where' => array('user_id' => $isLogin->id)
                                );
                                $updataData = $this->common_model->customUpdate($option);
                            } else {

                                if ($cash_type == 1) {
                                    $bonus_amount = $bonus_amount + $amount;
                                    $transaction_type = 'BONUS';
                                } else {
                                    $deposited_amount = $deposited_amount + $amount;
                                    $transaction_type = 'CASH';
                                }

                                $option = array(
                                    'table' => 'wallet',
                                    'data' => array(
                                        'user_id' => $isLogin->id,
                                        'deposited_amount' => $deposited_amount,
                                        'cash_bonus_amount' => $bonus_amount,
                                        'total_balance' => $total_balance + $amount,
                                        'create_date' => datetime()
                                    )
                                );

                                $updataData = $this->common_model->customInsert($option);
                            }

                            $total_use_user = $total_use_user + 1;

                            $option = array(
                                'table' => 'coupons',
                                'data' => array(
                                    'total_use_user' => $total_use_user,
                                ),
                                'where' => array('id' => $isCouponCode->id)
                            );
                            $this->common_model->customUpdate($option);

                            $option1 = array(
                                'table' => 'coupons_user',
                                'data' => array(
                                    'user_id' => $isLogin->id,
                                    'coupns_id' => $isCouponCode->id,
                                    'created_date' => datetime()
                                )
                            );
                            $this->common_model->customInsert($option1);

                            $orderId = commonUniqueCode();
                            $options = array(
                                'table' => 'transactions_history',
                                'data' => array(
                                    'user_id' => $isLogin->id,
                                    'match_id' => 0,
                                    'contest_id' => 0,
                                    'opening_balance' => $total_balance,
                                    'cr' => $amount,
                                    'available_balance' => $total_balance + $amount,
                                    'message' => "coupon offer",
                                    'datetime' => date('Y-m-d H:i:s'),
                                    'transaction_type' => $transaction_type,
                                    'pay_type' => "OFFER",
                                    'sports_type' => 0,
                                    'orderId' => $orderId,
                                    'total_wallet_balance' => $total_balance + $amount,
                                    'wallet_opening_balance' => $total_balance,
                                    'coupons_id' => $isCouponCode->id
                                )
                            );
                            $this->common_model->customInsert($options);
                        } else {

                            $return['status'] = 0;
                            $return['message'] = 'Coupon limit has been expired!';
                            $this->response($return);
                            exit;
                        }
                    } else {
                        $return['status'] = 0;
                        $return['message'] = 'Invalid coupon code!';
                        $this->response($return);
                        exit;
                    }
                }

                $response = array();
                $response['user_id'] = null_checker($isLogin->id);
                $response['name'] = trim(null_checker($isLogin->first_name) . ' ' . null_checker($isLogin->last_name));
                $response['email'] = null_checker($isLogin->email);
                $response['login_session_key'] = null_checker($login_session_key);
                $response['team_code'] = null_checker($isLogin->team_code);
                $response['user_invite_code'] = null_checker($isLogin->user_invite_code);
                $response['date_of_birth'] = null_checker($isLogin->date_of_birth);
                $response['mobile'] = null_checker($isLogin->phone);
                $response['city'] = null_checker($isLogin->city);
                $response['pin_code'] = null_checker($isLogin->pin_code);
                $response['state'] = null_checker($isLogin->state);
                $response['country'] = null_checker($isLogin->country);
                $response['address'] = null_checker($isLogin->street);
                $response['mobile_verify'] = null_checker($isLogin->verify_mobile);
                $response['email_verify'] = null_checker($isLogin->email_verify);
                $response['signin_type'] = null_checker($isLogin->signup_type);
                $response['is_team_name_changed'] = 0;
                $response['user_image'] = (!empty($isLogin->profile_pic)) ? base_url() . $isLogin->profile_pic : base_url() . DEFAULT_USER_IMG_PATH;
                $option = array('table' => 'user_pan_card',
                    'select' => 'user_pan_card.id,user_pan_card.full_name as name,user_pan_card.pan_number,'
                    . 'user_pan_card.date_of_birth,user_pan_card.pan_card_file,user_pan_card.verification_status as status,'
                    . 'user_pan_card.country as country_id,user_pan_card.state as state_id,countries.name as country_name,'
                    . 'states.name as state_name',
                    'join' => array('countries' => 'countries.id=user_pan_card.country',
                        'states' => 'states.id=user_pan_card.state'),
                    'where' => array('user_pan_card.user_id' => $isLogin->id),
                    'single' => true);
                $userPanData = $this->common_model->customGet($option);
                $panCardStatus = 0;
                if (!empty($userPanData)) {
                    $panCardStatus = $userPanData->status;
                    if (!empty($userPanData->pan_card_file)) {
                        $userPanData->pan_card_file = base_url() . $userPanData->pan_card_file;
                    } else {
                        $userPanData->pan_card_file = "";
                    }
                }
                $option = array('table' => 'user_bank_account_detail',
                    'select' => 'id,full_name as name,account_number,ifsc_code,account_file,verification_status as status,bank_name,branch_name',
                    'where' => array('user_id' => $isLogin->id),
                    'single' => true);
                $userAccountData = $this->common_model->customGet($option);
                $bankAccountStatus = 0;
                if (!empty($userAccountData)) {
                    $bankAccountStatus = $userAccountData->status;
                    if (!empty($userAccountData->account_file)) {
                        $userAccountData->account_file = base_url() . $userAccountData->account_file;
                    } else {
                        $userAccountData->account_file = "";
                    }
                }
                $option = array('table' => 'user_aadhar_card',
                    'select' => 'user_aadhar_card.id,user_aadhar_card.full_name as name,user_aadhar_card.aadhar_number,'
                    . 'user_aadhar_card.date_of_birth,user_aadhar_card.aadhar_file,user_aadhar_card.verification_status as status,'
                    . 'user_aadhar_card.state as state_id,'
                    . 'states.name as state_name',
                    'join' => array('states' => 'states.id=user_aadhar_card.state'),
                    'where' => array('user_aadhar_card.user_id' => $isLogin->id),
                    'single' => true);
                $userAadharData = $this->common_model->customGet($option);
                $aadharCardStatus = 0;
                if (!empty($userAadharData)) {
                    $aadharCardStatus = $userAadharData->status;
                    if (!empty($userPanData->aadhar_file)) {
                        $userAadharData->aadhar_file = base_url() . $userAadharData->aadhar_file;
                    } else {
                        $userAadharData->aadhar_file = "";
                    }
                }
                $response['pancard_verify'] = $panCardStatus;
                $response['account_verify'] = $bankAccountStatus;
                $response['aadhar_verify'] = $aadharCardStatus;
                if ($bankAccountStatus > 0) {
                    $response['bank_account'] = (!empty($userAccountData)) ? $userAccountData : array();
                }
                if ($panCardStatus > 0) {
                    $response['pan_card'] = (!empty($userPanData)) ? $userPanData : array();
                }
                if ($aadharCardStatus > 0) {
                    $response['aadhar_card'] = (!empty($userAadharData)) ? $userAadharData : array();
                }
                if ($isLogin->signup_type != "WEB") {
                    //$response['email_verify'] = 1;
                    $response['signin_type'] = "SOCIAL";
                }
                $response['gender'] = null_checker($isLogin->gender);
                $response['active'] = null_checker($isLogin->active);

                $response['favorite_teams'] = array();
                $favorite_teams = null_checker($isLogin->my_favorite_teams);
                if (!empty($favorite_teams)) {
                    $teams = json_decode($favorite_teams);

                    foreach ($teams as $key) {

                        $option = array(
                            'table' => 'team',
                            'select' => 'team_name',
                            'where' => array('id' => $key),
                            'single' => true
                        );
                        $teams = $this->common_model->customGet($option);
                        if (!empty($teams)) {
                            $temp['team_name'] = null_checker($teams->team_name);
                            $teamArr[] = $temp;
                        }
                    }
                    $response['favorite_teams'] = $teamArr;
                }

                $response['football_favorite_teams'] = array();
                $football_favorite_teams = null_checker($isLogin->my_football_favorite_teams);
                if (!empty($football_favorite_teams)) {
                    $football_teams = json_decode($football_favorite_teams);

                    foreach ($football_teams as $key) {
                        // print_r($key);die;

                        $option1 = array(
                            'table' => 'football_teams',
                            'select' => 'name as team_name',
                            'where' => array('team_id' => $key),
                            'single' => true
                        );
                        $football_teams = $this->common_model->customGet($option1);
                        if (!empty($football_teams)) {
                            $temp1['team_name'] = null_checker($football_teams->team_name);
                            $footballteamArr[] = $temp1;
                        }
                    }
                    $response['football_favorite_teams'] = $footballteamArr;
                }

                $response['kabbadi_favorite_teams'] = array();
                $kabbadi_favorite_teams = null_checker($isLogin->my_kabbadi_favorite_teams);
                if (!empty($kabbadi_favorite_teams)) {
                    $kabbadi_teams = json_decode($kabbadi_favorite_teams);

                    foreach ($kabbadi_teams as $key) {

                        $option2 = array(
                            'table' => 'kabaddi_team',
                            'select' => 'team_name',
                            'where' => array('season_team_key' => $key),
                            'single' => true
                        );
                        $kabbadi_teams = $this->common_model->customGet($option2);

                        if (!empty($kabbadi_teams)) {
                            $temp2['team_name'] = null_checker($kabbadi_teams->team_name);
                            $kabbaditeamArr[] = $temp2;
                        }
                    }
                    $response['kabbadi_favorite_teams'] = $kabbaditeamArr;
                }


                $option1 = array(
                    'table' => 'wallet',
                    'select' => 'user_id,deposited_amount,winning_amount,cash_bonus_amount,total_balance',
                    'where' => array('user_id' => $isLogin->id),
                    'single' => true
                );
                $userAccountData = $this->common_model->customGet($option1);
                if (!empty($userAccountData)) {
                    $deposited_amount = $userAccountData->deposited_amount;
                    $winning_amount = $userAccountData->winning_amount;
                    $cash_bonus_amount = $userAccountData->cash_bonus_amount;
                    $total_balance = $userAccountData->total_balance;
                }

                $temp1['deposited_amount'] = $deposited_amount;
                $temp1['winning_amount'] = $winning_amount;
                $temp1['cash_bonus_amount'] = $cash_bonus_amount;
                $temp1['total_balance'] = $total_balance;

                $response['my_balance'] = $temp1;

                $return['response'] = $response;

                /* Return success response */

                if (!empty($invite_code)) {
                    $option = array(
                        'table' => 'user_referrals',
                        'where' => array('user_id' => $isUserCode->id,
                            'invite_user_id' => $isLogin->id),
                    );
                    $alreadyUsed = $this->common_model->customGet($option);
                    if (empty($alreadyUsed)) {
                        $option = array(
                            'table' => 'user_referrals',
                            'data' => array('user_id' => $isUserCode->id,
                                'invite_user_id' => $isLogin->id),
                        );
                        $this->common_model->customInsert($option);
                        referralSchemeCashBonus($isLogin->id);
                    }
                }

                $genOtp = mt_rand(100000, 999999);
                $option = array(
                    'table' => 'users',
                    'where' => array('otp' => $genOtp),
                );
                $alreadyUsedOtp = $this->common_model->customGet($option);
                if (!empty($alreadyUsedOtp)) {
                    $genOtp = mt_rand(100000, 999999);
                }

                $options = array('table' => 'users',
                    'data' => array('phone' => $isLogin->phone,
                        'otp' => $genOtp),
                    'where' => array('id' => $isLogin->id));
                $updateCode = $this->common_model->customUpdate($options);

                $postfields = array('mobile' => $isLogin->phone,
                    'message' => "$genOtp is the OTP for your Funtush11 account. NEVER SHARE YOUR OTP WITH ANYONE. Funtush11 will never call or message to ask for the OTP.",
                );
                $this->smsSend($postfields);

                if ($signUpType == "APP") {

                    $message = "You have been successfully registered, OTP has been sent on your registerd mobile number";
                } else {
                    $message = "You have been successfully registered";
                }

                $html = array();

                $html['logo'] = base_url() . getConfig('site_logo');
                $html['site'] = getConfig('site_name');
                $html['password'] = $password;
                $html['username'] = $email;
                $html['user'] = $dataArr['team_code'];
                $email_template = $this->load->view('email/user_registration_tpl', $html, true);

                $status = send_mail($email_template, 'Your signup is successful!', $email, getConfig('admin_email'));


                $return['response'] = $response;
                $return['status'] = 1;
                $return['message'] = $message;
            } else {
                $is_error = db_err_msg();
                if ($is_error == FALSE) {
                    $return['status'] = 1;
                    $return['message'] = 'User registered successfully';
                } else {
                    $return['status'] = 0;
                    $return['message'] = $is_error;
                }
            }
        }

        $this->response($return);
    }

    /**
     * Function Name: login
     * Description:   To User Login
     */
    function login_md_steward_post() {
        $return['code'] = 200;
        //$return['response'] = new stdClass();
        $data = $this->input->post();
        $signUpType = $this->input->post('signup_type');
        $this->form_validation->set_rules('login_type', 'Login Type', 'trim|in_list[ADMIN,MDSTEWARD]');
        $this->form_validation->set_rules('signup_type', 'Sign Up Type', 'trim|required|in_list[WEB,APP]');
        $this->form_validation->set_rules('email', 'Email Id', 'trim|required|valid_email');
        $this->form_validation->set_rules('password', 'Password', 'trim|required');
        if ($signUpType == 'APP') {
            $this->form_validation->set_rules('device_type', 'Device Type', 'trim|required|in_list[ANDROID,IOS]');
            $this->form_validation->set_rules('device_token', 'Device Token', 'trim|required');
            $this->form_validation->set_rules('device_id', 'Device Id', 'trim|required');
        }
        if ($this->form_validation->run() == FALSE) {
            $error = $this->form_validation->rest_first_error_string();
            $return['status'] = 0;
            $return['message'] = $error;
        } else {
            $dataArr = array();
            $teamArr = array();
            $footballteamArr = array();
            $email = extract_value($data, 'email', '');
            $password = extract_value($data, 'password', '');
            $dataArr['email'] = extract_value($data, 'email', '');
            $login_type = extract_value($data, 'login_type', '');

            $deposited_amount = 0;
            $winning_amount = 0;
            $cash_bonus_amount = 0;
            $total_balance = 0;
            /* Get User Data From Users Table */
            $isLogin = $this->ion_auth->login($email, $password, FALSE);
            if ($isLogin) {
                $isLogin = $this->common_model->getsingle(USERS, $dataArr);
            }
            if (empty($isLogin)) {
                $return['status'] = 0;
                $return['message'] = 'Invalid Email-id or Password';
            } else if ($isLogin->active != 1) {
                $return['status'] = 0;
                $return['message'] = DEACTIVATE_USER;
            } else {

                $option = array(
                    'select' => '(CASE group_id
                                when "1" then "Admin"
                                when "3" then "Md Steward"
                                when "4" then "Data Operator"
                                END) as role',
                    'table' => 'users_groups',
                    'where' => array('user_id' => $isLogin->id, 'group_id' => 3),
                    'single' => true
                );
                $Roles = $this->common_model->customGet($option);
                if (empty($Roles)) {
                    $return['status'] = 0;
                    $return['message'] = 'Permission denied';
                } else {
                    /* Update User Data */
                    $UpdateData = array();
                    if ($signUpType == 'APP') {
                        $UpdateData['device_type'] = extract_value($data, 'device_type', NULL);
                        $UpdateData['device_token'] = extract_value($data, 'device_token', NULL);
                        $UpdateData['device_id'] = extract_value($data, 'device_id', NULL);
                    }
                    $login_session_key = get_guid();
                    $UpdateDataDevice['device_type'] = $device_type = extract_value($data, 'device_type', NULL);
                    if ($signUpType == 'APP') {
                        $this->common_model->updateFields(USERS, $UpdateDataDevice, array('id' => $isLogin->id));
                    }
                    /* $option = array(
                      'table' => 'login_session',
                      'where' => array('user_id' => $isLogin->id)
                      );
                      $this->common_model->customDelete($option); */
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
                    if ($signUpType == 'APP') {
                        save_user_device_history($isLogin->id, $UpdateData['device_token'], $UpdateData['device_type'], $UpdateData['device_id']);
                    }
                    $response = array();
                    $response['user_id'] = null_checker($isLogin->id);
                    $response['username'] = null_checker($isLogin->username);
                    $response['name'] = trim(null_checker($isLogin->first_name) . ' ' . null_checker($isLogin->last_name));
                    $response['email'] = null_checker($isLogin->email);
                    $response['login_session_key'] = null_checker($login_session_key);
                    $response['mobile'] = null_checker($isLogin->phone);
                    $response['email_verify'] = null_checker($isLogin->email_verify);
                    $response['signin_type'] = null_checker($isLogin->signup_type);
                    $response['user_image'] = (!empty($isLogin->profile_pic)) ? base_url() . $isLogin->profile_pic : base_url() . DEFAULT_USER_IMG_PATH;
                    $response['mobile_verify'] = null_checker($isLogin->verify_mobile);
                    $response['login_role'] = $Roles->role;
                    $return['response'] = $response;
                    $return['status'] = 1;
                    $return['message'] = 'User logged in successfully';
                }
            }
        }
        $this->response($return);
    }

    function login_data_operator_post() {
        $return['code'] = 200;
        //$return['response'] = new stdClass();
        $data = $this->input->post();
        $signUpType = $this->input->post('signup_type');
        $this->form_validation->set_rules('login_type', 'Login Type', 'trim|in_list[ADMIN,MDSTEWARD]');
        $this->form_validation->set_rules('signup_type', 'Sign Up Type', 'trim|required|in_list[WEB,APP]');
        $this->form_validation->set_rules('email', 'Email Id', 'trim|required|valid_email');
        $this->form_validation->set_rules('password', 'Password', 'trim|required');
        if ($signUpType == 'APP') {
            $this->form_validation->set_rules('device_type', 'Device Type', 'trim|required|in_list[ANDROID,IOS]');
            $this->form_validation->set_rules('device_token', 'Device Token', 'trim|required');
            $this->form_validation->set_rules('device_id', 'Device Id', 'trim|required');
        }
        if ($this->form_validation->run() == FALSE) {
            $error = $this->form_validation->rest_first_error_string();
            $return['status'] = 0;
            $return['message'] = $error;
        } else {

            $dataArr = array();
            $teamArr = array();
            $footballteamArr = array();
            $email = extract_value($data, 'email', '');
            $password = extract_value($data, 'password', '');
            $dataArr['email'] = extract_value($data, 'email', '');
            $login_type = extract_value($data, 'login_type', '');

            $deposited_amount = 0;
            $winning_amount = 0;
            $cash_bonus_amount = 0;
            $total_balance = 0;
            /* Get User Data From Users Table */
            $isLogin = $this->ion_auth->login($email, $password, FALSE);
            if ($isLogin) {
                $isLogin = $this->common_model->getsingle(USERS, $dataArr);
            }
            if (empty($isLogin)) {
                $return['status'] = 0;
                $return['message'] = 'Invalid Email-id or Password';
            } else if ($isLogin->active != 1) {
                $return['status'] = 0;
                $return['message'] = DEACTIVATE_USER;
            } else {
                $option = array(
                    'select' => '(CASE group_id
                                when "1" then "Admin"
                                when "3" then "Md Steward"
                                when "4" then "Data Operator"
                                END) as role',
                    'table' => 'users_groups',
                    'where' => array('user_id' => $isLogin->id, 'group_id' => 4),
                    'single' => true
                );
                $Roles = $this->common_model->customGet($option);
                if (empty($Roles)) {
                    $return['status'] = 0;
                    $return['message'] = 'Permission denied';
                } else {

                    /* Update User Data */
                    $UpdateData = array();
                    if ($signUpType == 'APP') {
                        $UpdateData['device_type'] = extract_value($data, 'device_type', NULL);
                        $UpdateData['device_token'] = extract_value($data, 'device_token', NULL);
                        $UpdateData['device_id'] = extract_value($data, 'device_id', NULL);
                    }
                    $login_session_key = get_guid();
                    $UpdateDataDevice['device_type'] = $device_type = extract_value($data, 'device_type', NULL);
                    if ($signUpType == 'APP') {
                        $this->common_model->updateFields(USERS, $UpdateDataDevice, array('id' => $isLogin->id));
                    }
                    /* $option = array(
                      'table' => 'login_session',
                      'where' => array('user_id' => $isLogin->id)
                      );
                      $this->common_model->customDelete($option); */
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
                    if ($signUpType == 'APP') {
                        save_user_device_history($isLogin->id, $UpdateData['device_token'], $UpdateData['device_type'], $UpdateData['device_id']);
                    }

                    $response = array();
                    $response['user_id'] = null_checker($isLogin->id);
                    $response['username'] = null_checker($isLogin->username);
                    $response['name'] = trim(null_checker($isLogin->first_name) . ' ' . null_checker($isLogin->last_name));
                    $response['email'] = null_checker($isLogin->email);
                    $response['login_session_key'] = null_checker($login_session_key);
                    $response['mobile'] = null_checker($isLogin->phone);
                    $response['email_verify'] = null_checker($isLogin->email_verify);
                    $response['signin_type'] = null_checker($isLogin->signup_type);
                    $response['user_image'] = (!empty($isLogin->profile_pic)) ? base_url() . $isLogin->profile_pic : base_url() . DEFAULT_USER_IMG_PATH;
                    $response['mobile_verify'] = null_checker($isLogin->verify_mobile);
                    $response['login_role'] = $Roles->role;
                    $return['response'] = $response;
                    $return['status'] = 1;
                    $return['message'] = 'User logged in successfully';
                }
            }
        }
        $this->response($return);
    }

    function login_admin_post() {
        $return['code'] = 200;
        //$return['response'] = new stdClass();
        $data = $this->input->post();
        $signUpType = $this->input->post('signup_type');
        $this->form_validation->set_rules('login_type', 'Login Type', 'trim|in_list[ADMIN,MDSTEWARD]');
        $this->form_validation->set_rules('signup_type', 'Sign Up Type', 'trim|required|in_list[WEB,APP]');
        $this->form_validation->set_rules('email', 'Email Id', 'trim|required|valid_email');
        $this->form_validation->set_rules('password', 'Password', 'trim|required');
        if ($signUpType == 'APP') {
            $this->form_validation->set_rules('device_type', 'Device Type', 'trim|required|in_list[ANDROID,IOS]');
            $this->form_validation->set_rules('device_token', 'Device Token', 'trim|required');
            $this->form_validation->set_rules('device_id', 'Device Id', 'trim|required');
        }
        if ($this->form_validation->run() == FALSE) {
            $error = $this->form_validation->rest_first_error_string();
            $return['status'] = 0;
            $return['message'] = $error;
        } else {

            $dataArr = array();
            $teamArr = array();
            $footballteamArr = array();
            $email = extract_value($data, 'email', '');
            $password = extract_value($data, 'password', '');
            $dataArr['email'] = extract_value($data, 'email', '');
            $login_type = extract_value($data, 'login_type', '');

            $deposited_amount = 0;
            $winning_amount = 0;
            $cash_bonus_amount = 0;
            $total_balance = 0;
            /* Get User Data From Users Table */
            $isLogin = $this->ion_auth->login($email, $password, FALSE);
            if ($isLogin) {
                $isLogin = $this->common_model->getsingle(USERS, $dataArr);
            }
            if (empty($isLogin)) {
                $return['status'] = 0;
                $return['message'] = 'Invalid Email-id or Password';
            } else if ($isLogin->active != 1) {
                $return['status'] = 0;
                $return['message'] = DEACTIVATE_USER;
            } else {
                $option = array(
                    'select' => '(CASE group_id
                                when "1" then "Admin"
                                when "3" then "Md Steward"
                                when "4" then "Data Operator"
                                END) as role',
                    'table' => 'users_groups',
                    'where' => array('user_id' => $isLogin->id, 'group_id' => 1),
                    'single' => true
                );
                $Roles = $this->common_model->customGet($option);
                if (empty($Roles)) {
                    $return['status'] = 0;
                    $return['message'] = 'Permission denied';
                } else {

                    /* Update User Data */
                    $UpdateData = array();
                    if ($signUpType == 'APP') {
                        $UpdateData['device_type'] = extract_value($data, 'device_type', NULL);
                        $UpdateData['device_token'] = extract_value($data, 'device_token', NULL);
                        $UpdateData['device_id'] = extract_value($data, 'device_id', NULL);
                    }
                    $login_session_key = get_guid();
                    $UpdateDataDevice['device_type'] = $device_type = extract_value($data, 'device_type', NULL);
                    if ($signUpType == 'APP') {
                        $this->common_model->updateFields(USERS, $UpdateDataDevice, array('id' => $isLogin->id));
                    }
                    /* $option = array(
                      'table' => 'login_session',
                      'where' => array('user_id' => $isLogin->id)
                      );
                      $this->common_model->customDelete($option); */
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
                    if ($signUpType == 'APP') {
                        save_user_device_history($isLogin->id, $UpdateData['device_token'], $UpdateData['device_type'], $UpdateData['device_id']);
                    }

                    $response = array();
                    $response['user_id'] = null_checker($isLogin->id);
                    $response['username'] = null_checker($isLogin->username);
                    $response['name'] = trim(null_checker($isLogin->first_name) . ' ' . null_checker($isLogin->last_name));
                    $response['email'] = null_checker($isLogin->email);
                    $response['login_session_key'] = null_checker($login_session_key);
                    $response['mobile'] = null_checker($isLogin->phone);
                    $response['email_verify'] = null_checker($isLogin->email_verify);
                    $response['signin_type'] = null_checker($isLogin->signup_type);
                    $response['user_image'] = (!empty($isLogin->profile_pic)) ? base_url() . $isLogin->profile_pic : base_url() . DEFAULT_USER_IMG_PATH;
                    $response['mobile_verify'] = null_checker($isLogin->verify_mobile);
                    $response['login_role'] = $Roles->role;
                    $return['response'] = $response;
                    $return['status'] = 1;
                    $return['message'] = 'User logged in successfully';
                }
            }
        }
        $this->response($return);
    }

    function login_new_post() {

        $return['code'] = 200;
        $data = $this->input->post();
        $signUpType = $this->input->post('signup_type');
        $email = $this->input->post('email');

        $this->form_validation->set_rules('signup_type', 'Sign Up Type', 'trim|required|in_list[WEB,APP]');

        if (!empty($email)) {

            $this->form_validation->set_rules('password', 'Password', 'trim|required');
        }
        if ($signUpType == 'APP') {
            $this->form_validation->set_rules('device_type', 'Device Type', 'trim|required|in_list[ANDROID,IOS]');
            //$this->form_validation->set_rules('device_token', 'Device Token', 'trim|required');
            $this->form_validation->set_rules('device_id', 'Device Id', 'trim|required');
        }
        if ($this->form_validation->run() == FALSE) {
            $error = $this->form_validation->rest_first_error_string();
            $return['status'] = 0;
            $return['message'] = $error;
        } else {

            $dataArr = array();
            $teamArr = array();
            $kabbaditeamArr = array();
            $footballteamArr = array();

            $email = extract_value($data, 'email', '');
            $password = extract_value($data, 'password', '');
            $mobile = extract_value($data, 'mobile', '');

            if (empty($email) && empty($mobile)) {
                $return['status'] = 0;
                $return['message'] = 'Email or Mobile field is required and (at least) one of these needs to be filled';
            } else {

                $deposited_amount = 0;
                $winning_amount = 0;
                $cash_bonus_amount = 0;
                $total_balance = 0;


                /* Get User Data From Users Table */
                if (!empty($email) && !empty($password)) {
                    $dataArr['email'] = extract_value($data, 'email', '');
                    $isLogin = $this->ion_auth->login($email, $password, FALSE);
                } else if (!empty($mobile)) {
                    $option = array(
                        'table' => 'users',
                        'select' => 'verify_mobile,id,active',
                        'where' => array('phone' => $mobile),
                        'single' => true
                    );
                    $getmobile = $this->common_model->customGet($option);
                    if (!empty($getmobile)) {

                        if ($getmobile->active != 1) {
                            $return['status'] = 0;
                            $return['message'] = DEACTIVATE_USER;
                            $this->response($return);
                            exit;
                        }

                        $UpdateData = array();
                        if ($signUpType == 'APP') {
                            $UpdateData['device_type'] = extract_value($data, 'device_type', NULL);
                            $UpdateData['device_token'] = extract_value($data, 'device_token', NULL);
                            $UpdateData['device_id'] = extract_value($data, 'device_id', NULL);
                        }
                        $login_session_key = get_guid();
                        $UpdateDataDevice['device_type'] = $device_type = extract_value($data, 'device_type', NULL);
                        if ($signUpType == 'APP') {
                            $this->common_model->updateFields(USERS, $UpdateDataDevice, array('id' => $getmobile->id));
                        }
                        $option = array(
                            'table' => 'login_session',
                            'where' => array('user_id' => $getmobile->id)
                        );
                        $this->common_model->customDelete($option);
                        $option = array(
                            'table' => 'login_session',
                            'data' => array(
                                'login_session_key' => $login_session_key,
                                'user_id' => $getmobile->id,
                                'login_ip' => $_SERVER['REMOTE_ADDR'],
                                'last_login' => time()
                            ),
                        );
                        $this->common_model->customInsert($option);
                        if ($signUpType == 'APP') {
                            save_user_device_history($getmobile->id, $UpdateData['device_token'], $UpdateData['device_type'], $UpdateData['device_id']);
                        }

                        $genOtp = mt_rand(100000, 999999);
                        $option = array(
                            'table' => 'users',
                            'where' => array('otp' => $genOtp),
                        );
                        $alreadyUsedOtp = $this->common_model->customGet($option);
                        if (!empty($alreadyUsedOtp)) {
                            $genOtp = mt_rand(100000, 999999);
                        }

                        $options = array('table' => 'users',
                            'data' => array('phone' => $mobile,
                                'otp' => $genOtp),
                            'where' => array('id' => $getmobile->id));
                        $updateCode = $this->common_model->customUpdate($options);

                        $postfields = array('mobile' => $mobile,
                            'message' => "$genOtp is the OTP for your Funtush11 account. NEVER SHARE YOUR OTP WITH ANYONE. Funtush11 will never call or message to ask for the OTP.",
                        );
                        $this->smsSend($postfields);


                        $return['login_session_key'] = $login_session_key;
                        $return['status'] = 1;
                        $return['message'] = 'Please verify your mobile, OTP has been sent on your registerd mobile number';
                        $this->response($return);
                        exit;
                    } else {
                        $return['status'] = 0;
                        $return['message'] = 'Invalid Mobile Number';
                        $this->response($return);
                        exit;
                    }
                }

                if ($isLogin) {
                    $isLogin = $this->common_model->getsingle(USERS, $dataArr);
                }
                if (empty($isLogin)) {
                    $return['status'] = 0;
                    $return['message'] = 'Invalid Email-id or Password or Mobile or profile is deactivate';
                } else if ($isLogin->active != 1) {
                    $return['status'] = 0;
                    $return['message'] = DEACTIVATE_USER;
                } else {
                    /* Update User Data */
                    $UpdateData = array();
                    if ($signUpType == 'APP') {
                        $UpdateData['device_type'] = extract_value($data, 'device_type', NULL);
                        $UpdateData['device_token'] = extract_value($data, 'device_token', NULL);
                        $UpdateData['device_id'] = extract_value($data, 'device_id', NULL);
                    }
                    $login_session_key = get_guid();
                    $UpdateDataDevice['device_type'] = $device_type = extract_value($data, 'device_type', NULL);
                    if ($signUpType == 'APP') {
                        $this->common_model->updateFields(USERS, $UpdateDataDevice, array('id' => $isLogin->id));
                    }
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
                    if ($signUpType == 'APP') {
                        save_user_device_history($isLogin->id, $UpdateData['device_token'], $UpdateData['device_type'], $UpdateData['device_id']);
                    }
                    $response = array();
                    $response['user_id'] = null_checker($isLogin->id);
                    $response['username'] = null_checker($isLogin->username);
                    $response['name'] = trim(null_checker($isLogin->first_name) . ' ' . null_checker($isLogin->last_name));
                    $response['email'] = null_checker($isLogin->email);
                    $response['login_session_key'] = null_checker($login_session_key);
                    $response['team_code'] = null_checker($isLogin->team_code);
                    $response['user_invite_code'] = null_checker($isLogin->user_invite_code);
                    $response['date_of_birth'] = null_checker($isLogin->date_of_birth);
                    $response['mobile'] = null_checker($isLogin->phone);
                    $response['city'] = null_checker($isLogin->city);
                    $response['pin_code'] = null_checker($isLogin->pin_code);
                    $response['state'] = null_checker($isLogin->state);
                    $response['country'] = null_checker($isLogin->country);
                    $response['address'] = null_checker($isLogin->street);
                    $response['email_verify'] = null_checker($isLogin->email_verify);
                    $response['signin_type'] = null_checker($isLogin->signup_type);
                    $response['is_team_name_changed'] = 0;
                    $response['user_image'] = (!empty($isLogin->profile_pic)) ? base_url() . $isLogin->profile_pic : base_url() . DEFAULT_USER_IMG_PATH;
                    $response['mobile_verify'] = null_checker($isLogin->verify_mobile);


                    $option = array('table' => 'user_pan_card',
                        'select' => 'user_pan_card.id,user_pan_card.full_name as name,user_pan_card.pan_number,'
                        . 'user_pan_card.date_of_birth,user_pan_card.pan_card_file,user_pan_card.verification_status as status,'
                        . 'user_pan_card.country as country_id,user_pan_card.state as state_id,countries.name as country_name,'
                        . 'states.name as state_name',
                        'join' => array('countries' => 'countries.id=user_pan_card.country',
                            'states' => 'states.id=user_pan_card.state'),
                        'where' => array('user_pan_card.user_id' => $isLogin->id),
                        'single' => true);
                    $userPanData = $this->common_model->customGet($option);
                    $panCardStatus = 0;
                    if (!empty($userPanData)) {
                        $panCardStatus = $userPanData->status;
                        if (!empty($userPanData->pan_card_file)) {
                            $userPanData->pan_card_file = base_url() . $userPanData->pan_card_file;
                        } else {
                            $userPanData->pan_card_file = "";
                        }
                    }
                    $option = array('table' => 'user_bank_account_detail',
                        'select' => 'id,full_name as name,account_number,ifsc_code,account_file,verification_status as status,bank_name,branch_name',
                        'where' => array('user_id' => $isLogin->id),
                        'single' => true);
                    $userAccountData = $this->common_model->customGet($option);
                    $bankAccountStatus = 0;
                    if (!empty($userAccountData)) {
                        $bankAccountStatus = $userAccountData->status;
                        if (!empty($userAccountData->account_file)) {
                            $userAccountData->account_file = base_url() . $userAccountData->account_file;
                        } else {
                            $userAccountData->account_file = "";
                        }
                    }
                    $option = array('table' => 'user_aadhar_card',
                        'select' => 'user_aadhar_card.id,user_aadhar_card.full_name as name,user_aadhar_card.aadhar_number,'
                        . 'user_aadhar_card.date_of_birth,user_aadhar_card.aadhar_file,user_aadhar_card.verification_status as status,'
                        . 'user_aadhar_card.state as state_id,'
                        . 'states.name as state_name',
                        'join' => array('states' => 'states.id=user_aadhar_card.state'),
                        'where' => array('user_aadhar_card.user_id' => $isLogin->id),
                        'single' => true);
                    $userAadharData = $this->common_model->customGet($option);
                    $aadharCardStatus = 0;
                    if (!empty($userAadharData)) {
                        $aadharCardStatus = $userAadharData->status;
                        if (!empty($userPanData->aadhar_file)) {
                            $userAadharData->aadhar_file = base_url() . $userAadharData->aadhar_file;
                        } else {
                            $userAadharData->aadhar_file = "";
                        }
                    }
                    $response['pancard_verify'] = $panCardStatus;
                    $response['account_verify'] = $bankAccountStatus;
                    $response['aadhar_verify'] = $aadharCardStatus;
                    if ($bankAccountStatus > 0) {
                        $response['bank_account'] = (!empty($userAccountData)) ? $userAccountData : array();
                    }
                    if ($panCardStatus > 0) {
                        $response['pan_card'] = (!empty($userPanData)) ? $userPanData : array();
                    }
                    if ($aadharCardStatus > 0) {
                        $response['aadhar_card'] = (!empty($userAadharData)) ? $userAadharData : array();
                    }

                    if ($isLogin->signup_type != "WEB") {
                        //$response['email_verify'] = 1;
                        $response['signin_type'] = "SOCIAL";
                    }
                    $response['gender'] = null_checker($isLogin->gender);
                    $response['active'] = null_checker($isLogin->active);
                    if ($isLogin->is_first_login == 0) {
                        $option = array(
                            'table' => 'users',
                            'data' => array(
                                'is_first_login' => 1,
                            ),
                            'where' => array('id' => $isLogin->id)
                        );
                        $this->common_model->customUpdate($option);
                        $response['is_first_login'] = 1;
                    } else if ($isLogin->is_first_login == 1) {
                        $option = array(
                            'table' => 'users',
                            'data' => array(
                                'is_first_login' => 2,
                            ),
                            'where' => array('id' => $isLogin->id)
                        );
                        $this->common_model->customUpdate($option);
                        $response['is_first_login'] = 2;
                    } else {
                        $response['is_first_login'] = (int) $isLogin->is_first_login;
                    }

                    $response['favorite_teams'] = array();
                    $favorite_teams = null_checker($isLogin->my_favorite_teams);
                    if (!empty($favorite_teams)) {
                        $teams = json_decode($favorite_teams);

                        foreach ($teams as $key) {

                            $option = array(
                                'table' => 'team',
                                'select' => 'team_name',
                                'where' => array('id' => $key),
                                'single' => true
                            );
                            $teams = $this->common_model->customGet($option);
                            if (!empty($teams)) {
                                $temp['team_name'] = null_checker($teams->team_name);
                                $teamArr[] = $temp;
                            }
                        }
                        $response['favorite_teams'] = $teamArr;
                    }

                    $response['football_favorite_teams'] = array();
                    $football_favorite_teams = null_checker($isLogin->my_football_favorite_teams);
                    if (!empty($football_favorite_teams)) {
                        $football_teams = json_decode($football_favorite_teams);

                        foreach ($football_teams as $key) {
                            // print_r($key);die;

                            $option1 = array(
                                'table' => 'football_teams',
                                'select' => 'name as team_name',
                                'where' => array('team_id' => $key),
                                'single' => true
                            );
                            $football_teams = $this->common_model->customGet($option1);
                            if (!empty($football_teams)) {
                                $temp1['team_name'] = null_checker($football_teams->team_name);
                                $footballteamArr[] = $temp1;
                            }
                        }
                        $response['football_favorite_teams'] = $footballteamArr;
                    }

                    $response['kabbadi_favorite_teams'] = array();
                    $kabbadi_favorite_teams = null_checker($isLogin->my_kabbadi_favorite_teams);
                    if (!empty($kabbadi_favorite_teams)) {
                        $kabbadi_teams = json_decode($kabbadi_favorite_teams);

                        foreach ($kabbadi_teams as $key) {

                            $option2 = array(
                                'table' => 'kabaddi_team',
                                'select' => 'team_name',
                                'where' => array('season_team_key' => $key),
                                'single' => true
                            );
                            $kabbadi_teams = $this->common_model->customGet($option2);

                            if (!empty($kabbadi_teams)) {
                                $temp2['team_name'] = null_checker($kabbadi_teams->team_name);
                                $kabbaditeamArr[] = $temp2;
                            }
                        }
                        $response['kabbadi_favorite_teams'] = $kabbaditeamArr;
                    }


                    $option1 = array(
                        'table' => 'wallet',
                        'select' => 'user_id,deposited_amount,winning_amount,cash_bonus_amount,total_balance',
                        'where' => array('user_id' => $isLogin->id),
                        'single' => true
                    );
                    $userAccountData = $this->common_model->customGet($option1);
                    if (!empty($userAccountData)) {
                        $deposited_amount = $userAccountData->deposited_amount;
                        $winning_amount = $userAccountData->winning_amount;
                        $cash_bonus_amount = $userAccountData->cash_bonus_amount;
                        $total_balance = $userAccountData->total_balance;
                    }

                    $temp1['deposited_amount'] = $deposited_amount;
                    $temp1['winning_amount'] = $winning_amount;
                    $temp1['cash_bonus_amount'] = $cash_bonus_amount;
                    $temp1['total_balance'] = $total_balance;

                    $response['my_balance'] = $temp1;


                    // if ($signUpType == 'APP') {
                    //     referralSchemeCashBonus($isLogin->id);
                    // }
                    // registerCashBonusAndAppDownload($isLogin->id);
                    $return['response'] = $response;
                    $return['status'] = 1;
                    $return['message'] = 'User logged in successfully';
                }
            }
        }
        $this->response($return);
    }

    /**
     * Function Name: mobile_verify_otp
     * Description:   To verify mobile by otp
     */
    function mobile_verify_otp_post() {
        $return['code'] = 200;
        //$return['response'] = new stdClass();
        $data = $this->input->post();
        $this->form_validation->set_rules('login_key_session', 'Login session key', 'trim|required');
        $this->form_validation->set_rules('otp', 'OTP', 'trim|required|min_length[6]|numeric');
        $signUpType = $this->input->post('signup_type');
        $this->form_validation->set_rules('signup_type', 'Sign Up Type', 'trim|required|in_list[WEB,APP]');
        $this->form_validation->set_rules('name', 'Full Name', 'trim|required');
        $this->form_validation->set_rules('email', 'Email Id', 'trim|required|valid_email|is_unique[' . USERS . '.email]');
        $this->form_validation->set_rules('mobile', 'Mobile Number', 'trim|required|numeric|min_length[10]|max_length[11]|is_unique[' . USERS . '.phone]');
        $this->form_validation->set_rules('password', 'Password', 'trim|required|min_length[6]|max_length[14]|callback_is_secure_pass');
        $this->form_validation->set_rules('confm_pswd', 'Confirm Password', 'trim|required|min_length[6]|max_length[14]|matches[password]');
        $this->form_validation->set_rules('username', 'Username', 'trim|required|is_unique[' . USERS . '.username]');
        $this->form_validation->set_rules('date_of_birth', 'Date of Birth', 'trim|required');
        if ($signUpType == "APP") {
            $this->form_validation->set_rules('device_type', 'Device Type', 'trim|required|in_list[ANDROID,IOS]');
            $this->form_validation->set_rules('device_token', 'Device Token', 'trim|required');
            $this->form_validation->set_rules('device_id', 'Device Id', 'trim|required');
        }
        if ($this->form_validation->run() == FALSE) {
            $error = $this->form_validation->rest_first_error_string();
            $return['status'] = 0;
            $return['message'] = $error;
        } else {
            $dataArr = array();
            $teamArr = array();
            $deposited_amount = 0;
            $winning_amount = 0;
            $cash_bonus_amount = 0;
            $total_balance = 0;
            $otp = extract_value($data, 'otp', '');
            $options = array(
                'table' => 'users_temp',
                'select' => 'id',
                'where' => array('otp' => $otp)
            );
            $isUserOtp = $this->common_model->customGet($options);

            if (!empty($isUserOtp)) {
                //delete entry from user temp table
                $option = array(
                    'table' => 'users_temp',
                    'where' => array('email' => extract_value($data, 'email', ''))
                );
                $this->common_model->customDelete($option);
                $option = array(
                    'table' => 'users_temp',
                    'where' => array('phone' => extract_value($data, 'mobile', ''))
                );
                $this->common_model->customDelete($option);
                //now signup user 
                $invite_code = extract_value($data, 'invite_code', '');
                //$dateOfBirth = date('Y-m-d', strtotime(extract_value($data, 'date_of_birth', '')));
                if (!empty($invite_code)) {
                    $option = array(
                        'table' => 'users',
                        'select' => 'id',
                        'where' => array('team_code' => $user_invite_code),
                        'single' => true
                    );
                    $isUserCode = $this->common_model->customGet($option);
                    if (empty($isUserCode)) {
                        $return['status'] = 0;
                        $return['message'] = 'Invalid user referral code';
                        $this->response($return);
                        exit;
                    }
                }
                $identity_column = $this->config->item('identity', 'ion_auth');
                $this->data['identity_column'] = $identity_column;
                $identity = extract_value($data, 'email', '');
                $password = extract_value($data, 'password', '');
                $email = strtolower(extract_value($data, 'email', ''));
                $identity = ($identity_column === 'email') ? $email : extract_value($data, 'email', '');
                $dataArr = array();
                $dataArr['first_name'] = extract_value($data, 'name', '');
                $dataArr['phone'] = extract_value($data, 'mobile', '');
                $date_of_birth = extract_value($data, 'date_of_birth', '');
                $dataArr['date_of_birth'] = date('Y-m-d', strtotime($date_of_birth));
                $dataArr['is_pass_token'] = $password;
                $dataArr['email_verify'] = 0;
                $username = explode('@', $identity);
                $dataArr['username'] = extract_value($data, 'username', '');
                $digits = 5;
                $dataArr['team_code'] = strtoupper(substr(preg_replace('/[^A-Za-z0-9\-]/', '', $username[0]), 0, 5)) . rand(pow(10, $digits - 1), pow(10, $digits) - 1);

                $dataArr['user_invite_code'] = $dataArr['team_code'];
                if (!empty($invite_code)) {
                    $dataArr['verify_bonus'] = 1;
                    $dataArr['download_bonus'] = 1;
                }
                if ($signUpType == "APP") {
                    $dataArr['device_token'] = extract_value($data, 'device_token', '');
                    $dataArr['device_type'] = extract_value($data, 'device_type', '');
                    $dataArr['device_id'] = extract_value($data, 'device_id', '');
                }
                $lid = $this->ion_auth->register($identity, $password, $email, $dataArr, array(2));
                if ($lid) {
                    if ($signUpType == "APP") {
                        /* Save user device history */
                        save_user_device_history($lid, $dataArr['device_token'], $dataArr['device_type'], $dataArr['device_id']);
                    }
                    $login_session_key = get_guid();
                    $isLogin = $this->common_model->getsingle(USERS, array('id' => $lid));
                    $option = array(
                        'table' => 'login_session',
                        'where' => array('user_id' => $isLogin->id)
                    );
                    $this->common_model->customDelete($option);
                    $login_key_session = extract_value($data, 'login_key_session', '');
                    $option = array(
                        'table' => 'login_session',
                        'data' => array(
                            'login_session_key' => $login_key_session,
                            'user_id' => $isLogin->id,
                            'login_ip' => $_SERVER['REMOTE_ADDR'],
                            'last_login' => time()
                        ),
                    );
                    $this->common_model->customInsert($option);
                    $response = array();
                    $response['user_id'] = null_checker($isLogin->id);
                    $response['name'] = trim(null_checker($isLogin->first_name) . ' ' . null_checker($isLogin->last_name));
                    $response['email'] = null_checker($isLogin->email);
                    $response['login_session_key'] = null_checker($login_key_session);
                    $response['team_code'] = null_checker($isLogin->team_code);
                    $response['user_invite_code'] = null_checker($isLogin->user_invite_code);
                    $response['date_of_birth'] = null_checker($isLogin->date_of_birth);
                    $response['mobile'] = null_checker($isLogin->phone);
                    $response['city'] = null_checker($isLogin->city);
                    $response['pin_code'] = null_checker($isLogin->pin_code);
                    $response['state'] = null_checker($isLogin->state);
                    $response['country'] = null_checker($isLogin->country);
                    $response['address'] = null_checker($isLogin->street);
                    $response['mobile_verify'] = null_checker($isLogin->verify_mobile);
                    $response['email_verify'] = null_checker($isLogin->email_verify);
                    $response['signin_type'] = null_checker($isLogin->signup_type);
                    $response['is_team_name_changed'] = 0;
                    $response['user_image'] = (!empty($isLogin->profile_pic)) ? base_url() . $isLogin->profile_pic : base_url() . DEFAULT_USER_IMG_PATH;
                    $option = array('table' => 'user_pan_card',
                        'select' => 'user_pan_card.id,user_pan_card.full_name as name,user_pan_card.pan_number,'
                        . 'user_pan_card.date_of_birth,user_pan_card.pan_card_file,user_pan_card.verification_status as status,'
                        . 'user_pan_card.country as country_id,user_pan_card.state as state_id,countries.name as country_name,'
                        . 'states.name as state_name',
                        'join' => array('countries' => 'countries.id=user_pan_card.country',
                            'states' => 'states.id=user_pan_card.state'),
                        'where' => array('user_pan_card.user_id' => $isLogin->id),
                        'single' => true);
                    $userPanData = $this->common_model->customGet($option);
                    $panCardStatus = 0;
                    if (!empty($userPanData)) {
                        $panCardStatus = $userPanData->status;
                        if (!empty($userPanData->pan_card_file)) {
                            $userPanData->pan_card_file = base_url() . $userPanData->pan_card_file;
                        } else {
                            $userPanData->pan_card_file = "";
                        }
                    }
                    $option = array('table' => 'user_bank_account_detail',
                        'select' => 'id,full_name as name,account_number,ifsc_code,account_file,verification_status as status,bank_name,branch_name',
                        'where' => array('user_id' => $isLogin->id),
                        'single' => true);
                    $userAccountData = $this->common_model->customGet($option);
                    $bankAccountStatus = 0;
                    if (!empty($userAccountData)) {
                        $bankAccountStatus = $userAccountData->status;
                        if (!empty($userAccountData->account_file)) {
                            $userAccountData->account_file = base_url() . $userAccountData->account_file;
                        } else {
                            $userAccountData->account_file = "";
                        }
                    }
                    $option = array('table' => 'user_aadhar_card',
                        'select' => 'user_aadhar_card.id,user_aadhar_card.full_name as name,user_aadhar_card.aadhar_number,'
                        . 'user_aadhar_card.date_of_birth,user_aadhar_card.aadhar_file,user_aadhar_card.verification_status as status,'
                        . 'user_aadhar_card.state as state_id,'
                        . 'states.name as state_name',
                        'join' => array('states' => 'states.id=user_aadhar_card.state'),
                        'where' => array('user_aadhar_card.user_id' => $isLogin->id),
                        'single' => true);
                    $userAadharData = $this->common_model->customGet($option);
                    $aadharCardStatus = 0;
                    if (!empty($userAadharData)) {
                        $aadharCardStatus = $userAadharData->status;
                        if (!empty($userPanData->aadhar_file)) {
                            $userAadharData->aadhar_file = base_url() . $userAadharData->aadhar_file;
                        } else {
                            $userAadharData->aadhar_file = "";
                        }
                    }
                    $response['pancard_verify'] = $panCardStatus;
                    $response['account_verify'] = $bankAccountStatus;
                    $response['aadhar_verify'] = $aadharCardStatus;
                    if ($bankAccountStatus > 0) {
                        $response['bank_account'] = (!empty($userAccountData)) ? $userAccountData : array();
                    }
                    if ($panCardStatus > 0) {
                        $response['pan_card'] = (!empty($userPanData)) ? $userPanData : array();
                    }
                    if ($aadharCardStatus > 0) {
                        $response['aadhar_card'] = (!empty($userAadharData)) ? $userAadharData : array();
                    }
                    if ($isLogin->signup_type != "WEB") {
                        //$response['email_verify'] = 1;
                        $response['signin_type'] = "SOCIAL";
                    }
                    $response['gender'] = null_checker($isLogin->gender);
                    $response['active'] = null_checker($isLogin->active);

                    $option1 = array(
                        'table' => 'wallet',
                        'select' => 'user_id,deposited_amount,winning_amount,cash_bonus_amount,total_balance',
                        'where' => array('user_id' => $isLogin->id),
                        'single' => true
                    );
                    $userAccountData = $this->common_model->customGet($option1);
                    if (!empty($userAccountData)) {
                        $deposited_amount = $userAccountData->deposited_amount;
                        $winning_amount = $userAccountData->winning_amount;
                        $cash_bonus_amount = $userAccountData->cash_bonus_amount;
                        $total_balance = $userAccountData->total_balance;
                    }

                    $temp1['deposited_amount'] = $deposited_amount;
                    $temp1['winning_amount'] = $winning_amount;
                    $temp1['cash_bonus_amount'] = $cash_bonus_amount;
                    $temp1['total_balance'] = $total_balance;
                    $response['my_balance'] = $temp1;

                    $return['response'] = $response;


                    /* Return success response */
                    if (!empty($invite_code)) {
                        $option = array(
                            'table' => 'user_referrals',
                            'where' => array('user_id' => $isUserCode->id,
                                'invite_user_id' => $isLogin->id),
                        );
                        $alreadyUsed = $this->common_model->customGet($option);
                        if (empty($alreadyUsed)) {
                            $option = array(
                                'table' => 'user_referrals',
                                'data' => array('user_id' => $isUserCode->id,
                                    'invite_user_id' => $isLogin->id),
                            );
                            $this->common_model->customInsert($option);
                            referralSchemeCashBonus($isLogin->id);
                        }
                    }

                    // $genOtp = mt_rand(100000, 999999);
                    $options = array('table' => 'users',
                        'data' => array('phone' => $isLogin->phone,
                            'otp' => extract_value($data, 'otp', ''),
                            'verify_mobile' => 1),
                        'where' => array('id' => $isLogin->id));
                    $updateCode = $this->common_model->customUpdate($options);



                    // $postfields = array('mobile' => $isLogin->phone,
                    //         'message' => "Your theteamgenie verification code is " . $genOtp,
                    //     );
                    // $this->smsSend($postfields);

                    $html = array();
                    //$html['token'] = $token;
                    $html['logo'] = base_url() . getConfig('site_logo');
                    $html['site'] = getConfig('site_name');
                    $html['user'] = ucwords($isLogin->first_name);
                    $email_template = $this->load->view('email/email_registration_tpl', $html, true);
                    $status = send_mail($email_template, '[' . getConfig('site_name') . '] Welcome ', $isLogin->email, getConfig('admin_email'));


                    $return['response'] = $response;
                    $return['status'] = 1;
                    $return['message'] = 'You have been successfully registered';
                } else {
                    $is_error = db_err_msg();
                    if ($is_error == FALSE) {
                        $return['status'] = 1;
                        $return['message'] = 'User registered successfully';
                    } else {
                        $return['status'] = 0;
                        $return['message'] = $is_error;
                    }
                }


                registerCashBonusAndAppDownload($isLogin->id);
                referralSchemeCashBonus($isLogin->id);
                // $return['response'] = array('verify_mobile' => 1);
                // $return['status'] = 1;
                // $return['message'] = 'Mobile has been successfully verified';
            } else {
                $return['status'] = 0;
                $return['message'] = 'Please enter correct OTP, or use Resend OTP';
            }
        }
        $this->response($return);
    }

    /**
     * Function Name: mobile_verify_otp_post
     * Description:   To Verify Mobile Number
     */
    function mobile_verify_otp_new_post() {
        $return['code'] = 200;
        $data = $this->input->post();
        // $this->form_validation->set_rules('login_session_key', 'Login session key', 'trim|required|callback__validate_login_session_key');
        $this->form_validation->set_rules('otp', 'OTP', 'trim|required|min_length[6]|numeric');
        if ($this->form_validation->run() == FALSE) {
            $error = $this->form_validation->rest_first_error_string();
            $return['status'] = 0;
            $return['message'] = $error;
        } else {
            $dataArr = array();
            $response = array();
            $teamArr = array();
            $footballteamArr = array();
            $kabbaditeamArr = array();
            $deposited_amount = 0;
            $winning_amount = 0;
            $cash_bonus_amount = 0;
            $total_balance = 0;

            $otp = extract_value($data, 'otp', '');
            //$user_id = $this->user_details->id;
            $options = array(
                'table' => 'users',
                'select' => '*',
                'where' => array(
                    'otp' => $otp),
                'single' => true
            );
            $isLogin = $this->common_model->customGet($options);
            if (!empty($isLogin)) {
                $options = array(
                    'table' => 'users',
                    'data' => array('otp' => 0, 'verify_mobile' => 1),
                    'where' => array('id' => $isLogin->id,
                    )
                );
                $this->common_model->customUpdate($options);

                $option = array(
                    'table' => 'login_session',
                    'select' => 'login_session_key',
                    'where' => array('user_id' => $isLogin->id),
                    'single' => true
                );

                $loginsession = $this->common_model->customGet($option);
                if (!empty($loginsession)) {
                    $login_session_key = $loginsession->login_session_key;
                } else {
                    $login_session_key = "";
                }

                $response['user_id'] = null_checker($isLogin->id);
                $response['username'] = null_checker($isLogin->username);
                $response['name'] = trim(null_checker($isLogin->first_name) . ' ' . null_checker($isLogin->last_name));
                $response['email'] = null_checker($isLogin->email);
                $response['login_session_key'] = null_checker($login_session_key);
                $response['team_code'] = null_checker($isLogin->team_code);
                $response['user_invite_code'] = null_checker($isLogin->user_invite_code);
                $response['date_of_birth'] = null_checker($isLogin->date_of_birth);
                $response['mobile'] = null_checker($isLogin->phone);
                $response['city'] = null_checker($isLogin->city);
                $response['pin_code'] = null_checker($isLogin->pin_code);
                $response['state'] = null_checker($isLogin->state);
                $response['country'] = null_checker($isLogin->country);
                $response['address'] = null_checker($isLogin->street);
                $response['email_verify'] = null_checker($isLogin->email_verify);
                $response['signin_type'] = null_checker($isLogin->signup_type);
                $response['is_team_name_changed'] = 0;
                $response['user_image'] = (!empty($isLogin->profile_pic)) ? base_url() . $isLogin->profile_pic : base_url() . DEFAULT_USER_IMG_PATH;
                $response['mobile_verify'] = null_checker($isLogin->verify_mobile);


                $option = array('table' => 'user_pan_card',
                    'select' => 'user_pan_card.id,user_pan_card.full_name as name,user_pan_card.pan_number,'
                    . 'user_pan_card.date_of_birth,user_pan_card.pan_card_file,user_pan_card.verification_status as status,'
                    . 'user_pan_card.country as country_id,user_pan_card.state as state_id,countries.name as country_name,'
                    . 'states.name as state_name',
                    'join' => array('countries' => 'countries.id=user_pan_card.country',
                        'states' => 'states.id=user_pan_card.state'),
                    'where' => array('user_pan_card.user_id' => $isLogin->id),
                    'single' => true);
                $userPanData = $this->common_model->customGet($option);
                $panCardStatus = 0;
                if (!empty($userPanData)) {
                    $panCardStatus = $userPanData->status;
                    if (!empty($userPanData->pan_card_file)) {
                        $userPanData->pan_card_file = base_url() . $userPanData->pan_card_file;
                    } else {
                        $userPanData->pan_card_file = "";
                    }
                }
                $option = array('table' => 'user_bank_account_detail',
                    'select' => 'id,full_name as name,account_number,ifsc_code,account_file,verification_status as status,bank_name,branch_name',
                    'where' => array('user_id' => $isLogin->id),
                    'single' => true);
                $userAccountData = $this->common_model->customGet($option);
                $bankAccountStatus = 0;
                if (!empty($userAccountData)) {
                    $bankAccountStatus = $userAccountData->status;
                    if (!empty($userAccountData->account_file)) {
                        $userAccountData->account_file = base_url() . $userAccountData->account_file;
                    } else {
                        $userAccountData->account_file = "";
                    }
                }
                $option = array('table' => 'user_aadhar_card',
                    'select' => 'user_aadhar_card.id,user_aadhar_card.full_name as name,user_aadhar_card.aadhar_number,'
                    . 'user_aadhar_card.date_of_birth,user_aadhar_card.aadhar_file,user_aadhar_card.verification_status as status,'
                    . 'user_aadhar_card.state as state_id,'
                    . 'states.name as state_name',
                    'join' => array('states' => 'states.id=user_aadhar_card.state'),
                    'where' => array('user_aadhar_card.user_id' => $isLogin->id),
                    'single' => true);
                $userAadharData = $this->common_model->customGet($option);
                $aadharCardStatus = 0;
                if (!empty($userAadharData)) {
                    $aadharCardStatus = $userAadharData->status;
                    if (!empty($userPanData->aadhar_file)) {
                        $userAadharData->aadhar_file = base_url() . $userAadharData->aadhar_file;
                    } else {
                        $userAadharData->aadhar_file = "";
                    }
                }
                $response['pancard_verify'] = $panCardStatus;
                $response['account_verify'] = $bankAccountStatus;
                $response['aadhar_verify'] = $aadharCardStatus;
                if ($bankAccountStatus > 0) {
                    $response['bank_account'] = (!empty($userAccountData)) ? $userAccountData : array();
                }
                if ($panCardStatus > 0) {
                    $response['pan_card'] = (!empty($userPanData)) ? $userPanData : array();
                }
                if ($aadharCardStatus > 0) {
                    $response['aadhar_card'] = (!empty($userAadharData)) ? $userAadharData : array();
                }

                if ($isLogin->signup_type != "WEB") {
                    //$response['email_verify'] = 1;
                    $response['signin_type'] = "SOCIAL";
                }
                $response['gender'] = null_checker($isLogin->gender);
                $response['active'] = null_checker($isLogin->active);
                if ($isLogin->is_first_login == 0) {
                    $option = array(
                        'table' => 'users',
                        'data' => array(
                            'is_first_login' => 1,
                        ),
                        'where' => array('id' => $isLogin->id)
                    );
                    $this->common_model->customUpdate($option);
                    $response['is_first_login'] = 1;
                } else if ($isLogin->is_first_login == 1) {
                    $option = array(
                        'table' => 'users',
                        'data' => array(
                            'is_first_login' => 2,
                        ),
                        'where' => array('id' => $isLogin->id)
                    );
                    $this->common_model->customUpdate($option);
                    $response['is_first_login'] = 2;
                } else {
                    $response['is_first_login'] = (int) $isLogin->is_first_login;
                }

                $response['favorite_teams'] = array();
                $favorite_teams = null_checker($isLogin->my_favorite_teams);
                if (!empty($favorite_teams)) {
                    $teams = json_decode($favorite_teams);

                    foreach ($teams as $key) {

                        $option = array(
                            'table' => 'team',
                            'select' => 'team_name',
                            'where' => array('id' => $key),
                            'single' => true
                        );
                        $teams = $this->common_model->customGet($option);
                        if (!empty($teams)) {
                            $temp['team_name'] = null_checker($teams->team_name);
                            $teamArr[] = $temp;
                        }
                    }
                    $response['favorite_teams'] = $teamArr;
                }

                $response['football_favorite_teams'] = array();
                $football_favorite_teams = null_checker($isLogin->my_football_favorite_teams);
                if (!empty($football_favorite_teams)) {
                    $football_teams = json_decode($football_favorite_teams);

                    foreach ($football_teams as $key) {

                        $option1 = array(
                            'table' => 'football_teams',
                            'select' => 'name as team_name',
                            'where' => array('team_id' => $key),
                            'single' => true
                        );
                        $football_teams = $this->common_model->customGet($option1);
                        if (!empty($football_teams)) {
                            $temp1['team_name'] = null_checker($football_teams->team_name);
                            $footballteamArr[] = $temp1;
                        }
                    }
                    $response['football_favorite_teams'] = $footballteamArr;
                }

                $response['kabbadi_favorite_teams'] = array();
                $kabbadi_favorite_teams = null_checker($isLogin->my_kabbadi_favorite_teams);
                if (!empty($kabbadi_favorite_teams)) {
                    $kabbadi_teams = json_decode($kabbadi_favorite_teams);

                    foreach ($kabbadi_teams as $key) {

                        $option2 = array(
                            'table' => 'kabaddi_team',
                            'select' => 'team_name',
                            'where' => array('season_team_key' => $key),
                            'single' => true
                        );
                        $kabbadi_teams = $this->common_model->customGet($option2);

                        if (!empty($kabbadi_teams)) {
                            $temp2['team_name'] = null_checker($kabbadi_teams->team_name);
                            $kabbaditeamArr[] = $temp2;
                        }
                    }
                    $response['kabbadi_favorite_teams'] = $kabbaditeamArr;
                }


                $option1 = array(
                    'table' => 'wallet',
                    'select' => 'user_id,deposited_amount,winning_amount,cash_bonus_amount,total_balance',
                    'where' => array('user_id' => $isLogin->id),
                    'single' => true
                );
                $userAccountData = $this->common_model->customGet($option1);
                if (!empty($userAccountData)) {
                    $deposited_amount = $userAccountData->deposited_amount;
                    $winning_amount = $userAccountData->winning_amount;
                    $cash_bonus_amount = $userAccountData->cash_bonus_amount;
                    $total_balance = $userAccountData->total_balance;
                }

                $temp1['deposited_amount'] = $deposited_amount;
                $temp1['winning_amount'] = $winning_amount;
                $temp1['cash_bonus_amount'] = $cash_bonus_amount;
                $temp1['total_balance'] = $total_balance;

                $response['my_balance'] = $temp1;

                referralSchemeCashBonus($isLogin->id);


                $return['response'] = $response;
                $return['status'] = 1;
                $return['message'] = 'Mobile has been successfully verified';
            } else {
                $return['status'] = 0;
                $return['message'] = 'Please enter correct OTP, or use Resend OTP';
            }
        }
        $this->response($return);
    }

    /**
     * Function Name: countries
     * Description:   To Get All Countries
     */
    function countries_post() {
        $data = $this->input->post();
        $return['code'] = 200;
        //$return['response'] = array();
        $this->form_validation->set_rules('login_session_key', 'Login session key', 'trim|required|callback__validate_login_session_key');
        if ($this->form_validation->run() == FALSE) {
            $error = $this->form_validation->rest_first_error_string();
            $return['status'] = 0;
            $return['message'] = $error;
        } else {
            $countries = $this->common_model->customGet(array('table' => COUNTRY));
            if ($countries) {
                $return['status'] = 1;
                $return['response'] = $countries;
                $return['message'] = 'Successfully records found';
            } else {
                $return['status'] = 0;
                $return['message'] = 'Records not found';
            }
        }
        $this->response($return);
    }

    /**
     * Function Name: countries
     * Description:   To Get All Countries
     */
    function states_post() {
        $data = $this->input->post();
        $return['code'] = 200;
        //$return['response'] = array();
        //$this->form_validation->set_rules('country_id', 'Country Id', 'trim|required|numeric');
        $this->form_validation->set_rules('login_session_key', 'Login session key', 'trim|required|callback__validate_login_session_key');
        if ($this->form_validation->run() == FALSE) {
            $error = $this->form_validation->rest_first_error_string();
            $return['status'] = 0;
            $return['message'] = $error;
        } else {
            $states = $this->common_model->customGet(array('table' => 'states', 'select' => 'id,name', 'where' => array('country_id' => 101)));
            if ($states) {
                $return['status'] = 1;
                $return['response'] = $states;
                $return['message'] = 'Successfully records found';
            } else {
                $return['status'] = 0;
                $return['message'] = 'Records not found';
            }
        }
        $this->response($return);
    }

    /**
     * Function Name: category
     * Description:   To Get All category
     */
    function careUnit_post() {
        $data = $this->input->post();
        $return['code'] = 200;
        $this->form_validation->set_rules('login_session_key', 'Login session key', 'trim|required|callback__validate_login_session_key');
        if ($this->form_validation->run() == FALSE) {
            $error = $this->form_validation->rest_first_error_string();
            $return['status'] = 0;
            $return['message'] = $error;
        } else {
            $states = $this->common_model->customGet(array('table' => 'care_unit', 'select' => 'id,name', 'where' => array('is_active' => 1, 'delete_status' => 0)));
            if ($states) {
                $return['status'] = 1;
                $return['response'] = $states;
                $return['message'] = 'Successfully records found';
            } else {
                $return['status'] = 0;
                $return['message'] = 'Records not found';
            }
        }
        $this->response($return);
    }

    /**
     * Function Name: category
     * Description:   To Get All category
     */
    function initialDx_post() {
        $data = $this->input->post();
        $return['code'] = 200;
        $this->form_validation->set_rules('login_session_key', 'Login session key', 'trim|required|callback__validate_login_session_key');
        if ($this->form_validation->run() == FALSE) {
            $error = $this->form_validation->rest_first_error_string();
            $return['status'] = 0;
            $return['message'] = $error;
        } else {
            $states = $this->common_model->customGet(array('table' => 'initial_dx', 'select' => 'id,name', 'where' => array('is_active' => 1, 'delete_status' => 0)));
            if ($states) {
                $return['status'] = 1;
                $return['response'] = $states;
                $return['message'] = 'Successfully records found';
            } else {
                $return['status'] = 0;
                $return['message'] = 'Records not found';
            }
        }
        $this->response($return);
    }

    /**
     * Function Name: category
     * Description:   To Get All category
     */
    function initialRx_post() {
        $data = $this->input->post();
        $return['code'] = 200;
        $this->form_validation->set_rules('login_session_key', 'Login session key', 'trim|required|callback__validate_login_session_key');
        if ($this->form_validation->run() == FALSE) {
            $error = $this->form_validation->rest_first_error_string();
            $return['status'] = 0;
            $return['message'] = $error;
        } else {
            $states = $this->common_model->customGet(array('table' => 'initial_rx', 'select' => 'id,name', 'where' => array('is_active' => 1, 'delete_status' => 0)));
            if ($states) {
                $return['status'] = 1;
                $return['response'] = $states;
                $return['message'] = 'Successfully records found';
            } else {
                $return['status'] = 0;
                $return['message'] = 'Records not found';
            }
        }
        $this->response($return);
    }

    /**
     * Function Name: category
     * Description:   To Get All category
     */
    function doctors_post() {
        $data = $this->input->post();
        $return['code'] = 200;
        $this->form_validation->set_rules('login_session_key', 'Login session key', 'trim|required|callback__validate_login_session_key');
        if ($this->form_validation->run() == FALSE) {
            $error = $this->form_validation->rest_first_error_string();
            $return['status'] = 0;
            $return['message'] = $error;
        } else {
            $states = $this->common_model->customGet(array('table' => 'doctors', 'select' => 'id,name,email', 'where' => array('is_active' => 1, 'delete_status' => 0)));
            if ($states) {
                $return['status'] = 1;
                $return['response'] = $states;
                $return['message'] = 'Successfully records found';
            } else {
                $return['status'] = 0;
                $return['message'] = 'Records not found';
            }
        }
        $this->response($return);
    }

    /**
     * Function Name: category
     * Description:   To Get All category
     */
    function md_steward_post() {
        $data = $this->input->post();
        $return['code'] = 200;
        $this->form_validation->set_rules('login_session_key', 'Login session key', 'trim|required|callback__validate_login_session_key');
        if ($this->form_validation->run() == FALSE) {
            $error = $this->form_validation->rest_first_error_string();
            $return['status'] = 0;
            $return['message'] = $error;
        } else {
            $option = array('table' => 'users U',
                'select' => 'U.id,CONCAT(first_name," ",last_name) name',
                'join' => array('users_groups as UG' => 'UG.user_id=U.id'),
                'where' => array('U.active' => 1, 'U.delete_status' => 0, 'UG.group_id' => 3)
            );
            $states = $this->common_model->customGet($option);
            if ($states) {
                $return['status'] = 1;
                $return['response'] = $states;
                $return['message'] = 'Successfully records found';
            } else {
                $return['status'] = 0;
                $return['message'] = 'Records not found';
            }
        }
        $this->response($return);
    }

    /**
     * Function Name: category
     * Description:   To Get All category
     */
    function add_patient_post() {
        $data = $this->input->post();
        $return['code'] = 200;
        $this->form_validation->set_rules('login_session_key', 'Login session key', 'trim|required|callback__validate_login_session_key');
        $this->form_validation->set_rules('name', 'Name', 'trim');
        $this->form_validation->set_rules('address', 'Address', 'trim');
        $this->form_validation->set_rules('operator_id', 'Operator Id', 'trim|required|numeric');
        $this->form_validation->set_rules('symptom_onset', 'Infection Onset', 'trim|required|in_list[Hospital,Facility]');
        $this->form_validation->set_rules('care_unit_id', 'Care Unit', 'trim|required|numeric');
        $this->form_validation->set_rules('doctor_id', 'Provider MD Id', 'trim|required|numeric');
        $this->form_validation->set_rules('md_steward_id', 'MD Steward', 'trim|required|numeric');
        $this->form_validation->set_rules('md_stayward_consult', 'MD Stayward Consult', 'trim|required|in_list[Yes,No]');
        $this->form_validation->set_rules('initial_rx', 'Initial RX', 'trim|required|numeric');
        $this->form_validation->set_rules('initial_dx', 'Initial DX', 'trim|required|numeric');
        $this->form_validation->set_rules('initial_dot', 'Initial DOT', 'trim|required|numeric');
        $this->form_validation->set_rules('new_initial_rx', 'New Initial RX', 'trim');
        $this->form_validation->set_rules('new_initial_dx', 'New Initial DX', 'trim');
        $this->form_validation->set_rules('new_initial_dot', 'New Initial DOT', 'trim');
        $this->form_validation->set_rules('infection_surveillance_checklist', 'Infection Surveillance Checklist', 'trim');
        $this->form_validation->set_rules('md_stayward_response', 'MD Stayward Response', 'trim');
        $this->form_validation->set_rules('total_day_patient_stay', 'Total Days of Patient Stay', 'trim|required');
        //$this->form_validation->set_rules('patient_unique_id', 'Patient Unique Id', 'trim|required');
        //$this->form_validation->set_rules('date_of_start_abx', 'Date of start abx', 'trim|required');
        if ($this->form_validation->run() == FALSE) {
            $error = $this->form_validation->rest_first_error_string();
            $return['status'] = 0;
            $return['message'] = $error;
        } else {
            /* $patient_unique_id = randomUnique(); */
            //$patient_unique_id = randomUnique();
            /*$option = array(
                'table' => 'patient',
                'where' => array(
                    'patient_id' => $patient_unique_id,
                )
            );
            $IsPatientUniqueID = $this->common_model->customGet($option);
            if (!empty($IsPatientUniqueID)) {
                $return['status'] = 0;
                $return['message'] = 'Patient id already exists';
                $this->response($return);
                exit;
            }*/
            $patient_unique_id = 1;
            $option = array(
                'table' => 'patient',
                'order' => array('id'=>'DESC'),
                'single'=> true
            );
            $IsPatientUniqueID = $this->common_model->customGet($option);
            if (!empty($IsPatientUniqueID)) {
                $patient_unique_id = $IsPatientUniqueID->id + 1;
            }

            $option = array(
                'table' => 'patient',
                'data' => array(
                    'name' => ucwords($this->input->post('name')),
                    'patient_id' => $patient_unique_id,
                    'address' => ucwords($this->input->post('address')),
                    'symptom_onset' => $this->input->post('symptom_onset'),
                    'care_unit_id' => $this->input->post('care_unit_id'),
                    'operator_id' => (!empty($this->input->post('operator_id'))) ? $this->input->post('operator_id') : 0,
                    'doctor_id' => $this->input->post('doctor_id'),
                    'total_days_of_patient_stay' => (!empty($this->input->post('total_day_patient_stay'))) ? $this->input->post('total_day_patient_stay') : 0,
                    'md_steward_id' => (!empty($this->input->post('md_steward_id'))) ? $this->input->post('md_steward_id') : null,
                    'md_stayward_consult' => $this->input->post('md_stayward_consult'),
                    'md_stayward_response' => (!empty($this->input->post('md_stayward_response'))) ? $this->input->post('md_stayward_response') : null,
                    'infection_surveillance_checklist' => (!empty($this->input->post('infection_surveillance_checklist'))) ? $this->input->post('infection_surveillance_checklist') : "N/A",
                    'date_of_start_abx' => date('Y-m-d', strtotime($this->input->post('date_of_start_abx'))),
                    'created_date' => date('Y-m-d H:i:s')
                )
            );
            $patient_id = $this->common_model->customInsert($option);
            if ($patient_id) {
                $option = array(
                    'table' => 'patient_consult',
                    'data' => array(
                        'patient_id' => $patient_id,
                        'initial_rx' => $this->input->post('initial_rx'),
                        'initial_dx' => $this->input->post('initial_dx'),
                        'initial_dot' => $this->input->post('initial_dot'),
                        'new_initial_rx' => (!empty($this->input->post('new_initial_rx'))) ? $this->input->post('new_initial_rx') : null,
                        'new_initial_dx' => (!empty($this->input->post('new_initial_dx'))) ? $this->input->post('new_initial_dx') : null,
                        'new_initial_dot' => (!empty($this->input->post('new_initial_dot'))) ? $this->input->post('new_initial_dot') : null
                    )
                );
                $insert_id = $this->common_model->customInsert($option);

                $option = array(
                    'table' => 'notifications',
                    'data' => array(
                        'user_id' => $this->input->post('md_steward_id'),
                        'sender_id' => (!empty($this->input->post('operator_id'))) ? $this->input->post('operator_id') : 0,
                        'message' => "ID: $patient_unique_id New patient added",
                        'user_type' => "USER",
                        'type_id' => $patient_unique_id,
                        'sent_time' => date('Y-m-d H:i:s')
                    )
                );
                $this->common_model->customInsert($option);


                $return['status'] = 1;
                $return['response']['patient_id'] = $patient_unique_id;
                $return['message'] = 'Successfully records added';
            } else {
                $return['status'] = 0;
                $return['message'] = 'Records not added';
            }
        }
        $this->response($return);
    }

    /**
     * Function Name: category
     * Description:   To Get All category
     */
    function notification_list_post() {
        $data = $this->input->post();
        $return['code'] = 200;
        $this->form_validation->set_rules('login_session_key', 'Login session key', 'trim|required|callback__validate_login_session_key');
        $this->form_validation->set_rules('md_steward_id', 'MD Steward Id', 'trim|required|numeric');
        if ($this->form_validation->run() == FALSE) {
            $error = $this->form_validation->rest_first_error_string();
            $return['status'] = 0;
            $return['message'] = $error;
        } else {
            $option = array(
                'table' => 'notifications',
                'select' => 'type_id as patient_id,message,read_status,sent_time',
                'order' => array('id' => 'DESC')
            );
            $option['where']['user_id'] = $data['md_steward_id'];
            $notification_list = $this->common_model->customGet($option);
            if ($notification_list) {
                $return['status'] = 1;
                $return['response'] = $notification_list;
                $return['message'] = 'Successfully records found';
            } else {
                $return['status'] = 0;
                $return['message'] = 'Records not found';
            }
        }
        $this->response($return);
    }

    /**
     * Function Name: category
     * Description:   To Get All category
     */
    function patient_list_post() {
        $data = $this->input->post();
        $return['code'] = 200;
        $this->form_validation->set_rules('login_session_key', 'Login session key', 'trim|required|callback__validate_login_session_key');
        $this->form_validation->set_rules('patient_id', 'Patient Id', 'trim|numeric');
        $this->form_validation->set_rules('care_unit_id', 'Care Unit', 'trim|numeric');
        $this->form_validation->set_rules('md_steward_id', 'MD Steward', 'trim|numeric');
        if ($this->form_validation->run() == FALSE) {
            $error = $this->form_validation->rest_first_error_string();
            $return['status'] = 0;
            $return['message'] = $error;
        } else {
            $option = array(
                'table' => 'patient P',
                'select' => 'P.id as pid, P.patient_id as patient_id,P.name as patient_name,P.date_of_start_abx,P.address,P.symptom_onset,P.md_stayward_consult,P.md_stayward_response,P.created_date,'
                . 'P.care_unit_id,CI.name as care_unit_name,P.doctor_id,DOC.name as doctor_name,P.md_steward_id,U.first_name as md_steward,'
                . 'PC.initial_rx,IRX.name as initial_rx_name,PC.initial_dx,IDX.name as initial_dx_name,PC.initial_dot,'
                . 'PC.new_initial_rx,IRX2.name as new_initial_rx_name,PC.new_initial_dx,IDX2.name as new_initial_dx_name,PC.new_initial_dot,PC.comment',
                'join' => array(array('care_unit CI', 'CI.id=P.care_unit_id', 'inner'),
                    array('doctors DOC', 'DOC.id=P.doctor_id', 'inner'),
                    array('users U', 'U.id=P.md_steward_id', 'inner'),
                    array('patient_consult PC', 'PC.patient_id=P.id', 'inner'),
                    array('initial_rx IRX', 'IRX.id=PC.initial_rx', 'left'),
                    array('initial_dx IDX', 'IDX.id=PC.initial_dx', 'left'),
                    array('initial_rx IRX2', 'IRX2.id=PC.new_initial_rx', 'left'),
                    array('initial_dx IDX2', 'IDX2.id=PC.new_initial_dx', 'left'))
            );
            if (!empty($data['md_steward_id'])) {
                $option['where']['P.md_steward_id'] = $data['md_steward_id'];
            }
            if (!empty($data['patient_id'])) {
                $option['where']['P.patient_id'] = $data['patient_id'];
            }
            if (!empty($data['care_unit_id'])) {
                $option['where']['P.care_unit_id'] = $data['care_unit_id'];
            }
            $patient_list = $this->common_model->customGet($option);
            if ($patient_list) {
                $return['status'] = 1;
                $return['response'] = $patient_list;
                $return['message'] = 'Successfully records found';
            } else {
                $return['status'] = 0;
                $return['message'] = 'Records not found';
            }
        }
        $this->response($return);
    }

    /**
     * Function Name: category
     * Description:   To Get All category
     */
    function patient_details_post() {
        $data = $this->input->post();
        $return['code'] = 200;
        $this->form_validation->set_rules('login_session_key', 'Login session key', 'trim|required|callback__validate_login_session_key');
        $this->form_validation->set_rules('patient_id', 'Patient Id', 'trim|required');
        if ($this->form_validation->run() == FALSE) {
            $error = $this->form_validation->rest_first_error_string();
            $return['status'] = 0;
            $return['message'] = $error;
        } else {
            $option = array(
                'table' => 'patient P',
                'select' => 'P.md_patient_status,P.infection_surveillance_checklist,P.total_days_of_patient_stay,P.date_of_start_abx,P.patient_id as patient_id,P.name as patient_name,P.address,P.symptom_onset,P.md_stayward_consult,P.md_stayward_response,P.created_date,'
                . 'P.care_unit_id,CI.name as care_unit_name,P.doctor_id,DOC.name as doctor_name,P.md_steward_id,U.first_name as md_steward,'
                . 'PC.initial_rx,IRX.name as initial_rx_name,PC.initial_dx,IDX.name as initial_dx_name,PC.initial_dot,'
                . 'PC.new_initial_rx,IRX2.name as new_initial_rx_name,PC.new_initial_dx,IDX2.name as new_initial_dx_name,PC.new_initial_dot,PC.comment',
                'join' => array(array('care_unit CI', 'CI.id=P.care_unit_id', 'inner'),
                    array('doctors DOC', 'DOC.id=P.doctor_id', 'inner'),
                    array('users U', 'U.id=P.md_steward_id', 'inner'),
                    array('patient_consult PC', 'PC.patient_id=P.id', 'inner'),
                    array('initial_rx IRX', 'IRX.id=PC.initial_rx', 'left'),
                    array('initial_dx IDX', 'IDX.id=PC.initial_dx', 'left'),
                    array('initial_rx IRX2', 'IRX2.id=PC.new_initial_rx', 'left'),
                    array('initial_dx IDX2', 'IDX2.id=PC.new_initial_dx', 'left')),
                'single' => true
            );
            $option['where']['P.patient_id'] = $data['patient_id'];
            $patient_list = $this->common_model->customGet($option);
            if ($patient_list) {
                $return['status'] = 1;
                $return['response'] = $patient_list;
                $return['message'] = 'Successfully records found';
            } else {
                $return['status'] = 0;
                $return['message'] = 'Records not found';
            }
        }
        $this->response($return);
    }

    /**
     * Function Name: category
     * Description:   To Get All category
     */
    function update_patient_post() {
        $data = $this->input->post();
        $return['code'] = 200;
        $this->form_validation->set_rules('login_session_key', 'Login session key', 'trim|required|callback__validate_login_session_key');
        $this->form_validation->set_rules('patient_id', 'Patient Id', 'trim|required|numeric');
        $this->form_validation->set_rules('md_stayward_response', 'MD Stayward Response', 'trim|in_list[Agree,Disagree]');
        if (isset($data['new_initial_dx'])) {
            if (!empty($data['new_initial_dx'])) {
                $this->form_validation->set_rules('new_initial_dx', 'New Initial DX', 'trim|numeric');
            }
        }

        if (isset($data['new_initial_rx'])) {
            if (!empty($data['new_initial_rx'])) {
                $this->form_validation->set_rules('new_initial_rx', 'New Initial RX', 'trim|numeric');
            }
        }


        $this->form_validation->set_rules('new_initial_dot', 'New Initial DOT', 'trim');
        $this->form_validation->set_rules('comment', 'Comment', 'trim');
        $this->form_validation->set_rules('infection_surveillance_checklist', 'Infection Surveillance Checklist', 'trim');
        if ($this->form_validation->run() == FALSE) {
            $error = $this->form_validation->rest_first_error_string();
            $return['status'] = 0;
            $return['message'] = $error;
        } else {
            if (isset($data['md_stayward_response']) && !empty($data['md_stayward_response'])) {
                $option = array(
                    'table' => 'patient',
                    'data' => array(
                        'md_stayward_response' => $this->input->post('md_stayward_response'),
                        'infection_surveillance_checklist' => (!empty($this->input->post('infection_surveillance_checklist'))) ? $this->input->post('infection_surveillance_checklist') : "N/A",
                        'md_patient_status' => 'Completed'
                    ),
                    'where' => array('patient_id' => $data['patient_id'])
                );
                if (!empty($this->input->post('date_of_start_abx'))) {
                    $option['data']['date_of_start_abx'] = date('Y-m-d', strtotime($this->input->post('date_of_start_abx')));
                }
                $this->common_model->customupdate($option);
            }
            $option = array(
                'table' => 'patient',
                'select' => 'id,md_steward_id,operator_id,care_unit_id',
                'single' => true
            );
            $option['where']['patient_id'] = $data['patient_id'];
            $patient_list = $this->common_model->customGet($option);

            $new_initial_rx = null;
            if (isset($data['new_initial_rx'])) {
                if (!empty($data['new_initial_rx'])) {
                    $new_initial_rx = $data['new_initial_rx'];
                }
            }

            $new_initial_dx = null;
            if (isset($data['new_initial_dx'])) {
                if (!empty($data['new_initial_dx'])) {
                    $new_initial_dx = $data['new_initial_dx'];
                }
            }
            $option = array(
                'table' => 'patient_consult',
                'data' => array(
                    'new_initial_rx' => $new_initial_rx,
                    'new_initial_dx' => $new_initial_dx,
                    'new_initial_dot' => (!empty($this->input->post('new_initial_dot'))) ? $this->input->post('new_initial_dot') : 0,
                    'comment' => (!empty($this->input->post('comment'))) ? $this->input->post('comment') : null
                ),
                'where' => array('patient_id' => $patient_list->id)
            );
            $Update = $this->common_model->customupdate($option);
            $option = array(
                'table' => 'notifications',
                'where' => array(
                    'type_id' => $data['patient_id']
                )
            );
            $this->common_model->customDelete($option);

            /** operator sent email * */
            $option = array(
                'table' => 'care_unit',
                'select' => 'email,name',
                'single' => true
            );
            $option['where']['id'] = $patient_list->care_unit_id;
            $care_unit = $this->common_model->customGet($option);
            $EmailTemplate = getEmailTemplate("welcome");
            if (!empty($EmailTemplate) && !empty($care_unit)) {
                $html = array();
                $html['logo'] = base_url() . getConfig('site_logo');
                $html['site'] = getConfig('site_name');
                $html['site_meta_title'] = getConfig('site_meta_title');
                $name = $care_unit->name;
                $html['user'] = ucwords($name);
                $html['website'] = base_url();
                $html['content'] = $EmailTemplate->description;
                $email_template = $this->load->view('email-template/welcome', $html, true);
                $title = '[' . getConfig('site_name') . '] ' . $EmailTemplate->title;
                send_mail($email_template, $title, $care_unit->email, getConfig('admin_email'));
            }

            /** care unit sent email * */
            $option = array(
                'table' => 'users',
                'select' => 'email,first_name,last_name',
                'single' => true
            );
            $option['where']['id'] = $patient_list->operator_id;
            $operator_email = $this->common_model->customGet($option);
            $EmailTemplate = getEmailTemplate("welcome");
            if (!empty($EmailTemplate) && !empty($operator_email)) {
                $html = array();
                $html['logo'] = base_url() . getConfig('site_logo');
                $html['site'] = getConfig('site_name');
                $html['site_meta_title'] = getConfig('site_meta_title');
                $name = $operator_email->first_name . " " . $operator_email->last_name;
                $html['user'] = ucwords($name);
                $html['website'] = base_url();
                $html['content'] = $EmailTemplate->description;
                $email_template = $this->load->view('email-template/welcome', $html, true);
                $title = '[' . getConfig('site_name') . '] ' . $EmailTemplate->title;
                send_mail($email_template, $title, $operator_email->email, getConfig('admin_email'));
            }

            $return['status'] = 1;
            $return['message'] = 'Successfully records updated';
        }
        $this->response($return);
    }

    /**
     * Function Name: update_profile
     * Description:   To Update User Profile
     */
    function update_profile_post() {
        $data = $this->input->post();
        $return['code'] = 200;
        //$return['response'] = new stdClass();

        $this->form_validation->set_rules('login_session_key', 'Login session key', 'trim|required');
        $this->form_validation->set_rules('name', 'Full Name', 'trim|required');
        $this->form_validation->set_rules('date_of_birth', 'Date of Birth', 'trim|required|callback__validate_birthdate_format');
        //$this->form_validation->set_rules('city', 'City', 'trim|required');
        //$this->form_validation->set_rules('pin_code', 'Pin Code', 'trim|required|numeric|min_length[5]|max_length[6]');
        $this->form_validation->set_rules('state', 'State', 'trim|required|numeric');
        // $this->form_validation->set_rules('country', 'Country', 'trim|required|numeric');
        //$this->form_validation->set_rules('address', 'Address', 'trim|required');
        $this->form_validation->set_rules('gender', 'Gender', 'trim|required|in_list[MALE,FEMALE,OTHER]');


        if ($this->form_validation->run() == FALSE) {
            $error = $this->form_validation->rest_first_error_string();
            $return['status'] = 0;
            $return['message'] = $error;
        } else {
            $dataArr = array();
            $notification = extract_value($data, 'notification_status', '');
            $favorite_teams = extract_value($data, 'favorite_teams', '');
            $game_type = extract_value($data, 'game_type', '');

            if ($notification != "") {
                $dataArr['sms_notification_status'] = extract_value($data, 'notification_status', '');
            }
            if ($favorite_teams != "") {
                if ($game_type == 1) {

                    $dataArr['my_favorite_teams'] = $favorite_teams;
                } else if ($game_type == 2) {
                    $dataArr['my_football_favorite_teams'] = $favorite_teams;
                } else {
                    $dataArr['my_kabbadi_favorite_teams'] = $favorite_teams;
                }
            }
            $dateOfBirth = date('Y-m-d', strtotime(extract_value($data, 'date_of_birth', '')));
            $dataArr['first_name'] = extract_value($data, 'name', '');
            $dataArr['city'] = extract_value($data, 'city', '');
            $dataArr['pin_code'] = (extract_value($data, 'pin_code', '')) ? extract_value($data, 'pin_code', '') : 0;
            $dataArr['state'] = extract_value($data, 'state', '');
            $dataArr['country'] = extract_value($data, 'country', '');
            $dataArr['street'] = extract_value($data, 'address', '');
            $dataArr['gender'] = extract_value($data, 'gender', '');

            $dataArr['date_of_birth'] = $dateOfBirth;
            $dataArr['update_status'] = 2;
            /* Update User Data Into Users Table */

            $userData = $this->common_model->getsingle(USERS, array('id' => $this->user_details->id));

            $status = $this->common_model->updateFields(USERS, $dataArr, array('id' => $this->user_details->id));


            if ($status) {
                /* Return success response */
                $return['status'] = 1;
                $return['message'] = 'Profile updated successfully';
            } else {
                $is_error = db_err_msg();
                $return['status'] = 0;
                if ($is_error == FALSE) {
                    $return['message'] = NO_CHANGES;
                } else {
                    $return['message'] = $is_error;
                }
            }
        }
        $this->response($return);
    }

    /*
     * Function Name: profile_details
     * Description:   To Get User Profile Details
     */

    function profile_details_post() {
        $data = $this->input->post();
        $return['code'] = 200;
        //$return['response'] = new stdClass();
        $this->form_validation->set_rules('login_session_key', 'Login session key', 'trim|required');
        if ($this->form_validation->run() == FALSE) {
            $error = $this->form_validation->rest_first_error_string();
            $return['status'] = 0;
            $return['message'] = $error;
        } else {
            /* Return Response */
            $response = array();
            $image = base_url() . DEFAULT_USER_IMG_PATH;
            if (!empty($this->user_details->profile_pic)) {
                $image = base_url() . $this->user_details->profile_pic;
            }
            $response['login_session_key'] = extract_value($data, 'login_session_key', NULL);
            $response['username'] = null_checker($this->user_details->username);
            $response['user_id'] = null_checker($this->user_details->id);
            $response['name'] = null_checker($this->user_details->first_name);
            $response['email'] = null_checker($this->user_details->email);
            $response['mobile'] = null_checker($this->user_details->phone);
            $response['gender'] = null_checker($this->user_details->gender);
            $response['user_image'] = $image;
            $response['contest_played'] = 0;
            $response['contest_won'] = 0;
            $response['mobile_verify'] = null_checker($this->user_details->verify_mobile);
            $response['email_verify'] = null_checker($this->user_details->email_verify);
            $return['status'] = 1;
            $return['response'] = $response;
            $return['message'] = 'success';
        }
        $this->response($return);
    }

    /**
     * Function Name: change_password
     * Description:   To Change User Password
     */
    function change_password_post() {
        $data = $this->input->post();
        $return['code'] = 200;
        //$return['response'] = new stdClass();
        $this->form_validation->set_rules('login_session_key', 'Login Session Key', 'trim|required');
        $this->form_validation->set_rules('current_password', 'Current Password', 'trim|required');
        $this->form_validation->set_rules('new_password', 'New Password', 'trim|required|min_length[6]|max_length[14]|callback_is_secure_pass');
        $this->form_validation->set_rules('confirm_password', 'Confirm Password', 'trim|required|min_length[6]|max_length[14]|matches[new_password]');
        if ($this->form_validation->run() == FALSE) {
            $error = $this->form_validation->rest_first_error_string();
            $return['status'] = 0;
            $return['message'] = $error;
        } else {
            $current_password = extract_value($data, 'current_password', "");
            $new_password = extract_value($data, 'new_password', "");
            $confirm_password = extract_value($data, 'confirm_password', "");

            /* To check user current password */
            $identity = $this->user_details->email;
            $change = $this->ion_auth->change_password($identity, $current_password, $new_password);
            if (!empty($change)) {
                $options = array('table' => USERS,
                    'data' => array('is_pass_token' => $this->input->post('new_password')),
                    'where' => array('email' => $identity));
                $this->common_model->customUpdate($options);
                $return['status'] = 1;
                $return['message'] = 'The new password has been saved successfully';
            } else {
                $return['status'] = 0;
                $return['message'] = 'The old password you entered was incorrect';
            }
        }
        $this->response($return);
    }

    /*
     * Function Name: logout
     * Description:   To User Logout
     */

    function logout_post() {
        $data = $this->input->post();
        $return['code'] = 200;
        $return['response'] = new stdClass();
        /* $this->form_validation->set_rules('login_session_key', 'Login Session Key', 'trim|required');
          if ($this->form_validation->run() == FALSE) {
          $error = $this->form_validation->rest_first_error_string();
          $return['status'] = 0;
          $return['message'] = $error;
          } else { */
        $userId = extract_value($data, 'user_id', NULL);
        $option = array(
            'table' => 'login_session',
            'where' => array('user_id' => $userId)
        );
        $this->common_model->customDelete($option);
        /* $option = array(
          'table' => 'users_device_history',
          'where' => array('user_id' => $userId)
          );
          $this->common_model->customDelete($option); */
        /* Update User logout status */
        $this->common_model->updateFields(USERS, array('is_logged_out' => 1), array('id' => $userId));
        $return['status'] = 1;
        $return['message'] = 'User logout successfully';
        /* } */
        $this->response($return);
    }

    /**
     * Function Name: update_profile
     * Description:   To add pan card
     */
    function verify_pan_card_post() {
        $data = $this->input->post();
        $return['code'] = 200;
        //$return['response'] = new stdClass();
        $id = extract_value($data, 'id', '');
        $this->form_validation->set_rules('login_session_key', 'Login session key', 'trim|required');
        $this->form_validation->set_rules('name', 'Full Name', 'trim|required');
        $this->form_validation->set_rules('date_of_birth', 'Date of Birth', 'trim|required|callback__validate_birthdate_format');
        $this->form_validation->set_rules('pan_number', 'Pan Number', 'trim|required|min_length[10]|max_length[10]');
        $this->form_validation->set_rules('state', 'State', 'trim|required|numeric');
        if (empty($_FILES['pan_card_file']['name']) && empty($id)) {
            $this->form_validation->set_rules('pan_card_file', 'Pan Card File', 'required');
        }
        if ($this->form_validation->run() == FALSE) {
            $error = $this->form_validation->rest_first_error_string();
            $return['status'] = 0;
            $return['message'] = $error;
        } else {
            $dataArr = array();
            $pannumber = extract_value($data, 'pan_number', '');

            if (!preg_match("/^([a-zA-Z]){5}([0-9]){4}([a-zA-Z]){1}?$/", $pannumber)) {
                $return['message'] = "Invalid PAN Number";
                $return['status'] = 0;
                $this->response($return);
                exit;
            }

            $option = array(
                'table' => 'users',
                'select' => 'first_name',
                'where' => array('id' => $this->user_details->id),
                'single' => true
            );
            $userProfile = $this->common_model->customGet($option);
            if (!empty($userProfile)) {
                $userName = null_checker($userProfile->first_name);
                if (empty($userName)) {
                    $return['message'] = "Please update your profile first";
                    $return['status'] = 0;
                    $this->response($return);
                    exit;
                }
            }

            $option = array(
                'table' => 'wallet',
                'select' => '*',
                'where' => array('user_id' => $this->user_details->id),
                'single' => true
            );
            $checkWinning = $this->common_model->customGet($option);
            // if (!empty($checkWinning)) {
            //     $winning_amount = $checkWinning->winning_amount;
            //     if ($winning_amount <= 200) {
            //         $return['message'] = "You are eligible to verify your account after your winning balance exceeds Rs. 200";
            //         $return['status'] = 0;
            //         $this->response($return);
            //         exit;
            //     }
            // } else {
            //     $return['message'] = "You are eligible to verify your account after your winning balance exceeds Rs. 200";
            //     $return['status'] = 0;
            //     $this->response($return);
            //     exit;
            // }

            $aadharCardNo = extract_value($data, 'aadhar_card_no', '');
            $dateOfBirth = date('Y-m-d', strtotime(extract_value($data, 'date_of_birth', '')));
            $dataArr['full_name'] = extract_value($data, 'name', '');
            $dataArr['pan_number'] = extract_value($data, 'pan_number', '');
            $dataArr['state'] = extract_value($data, 'state', '');
            $dataArr['date_of_birth'] = $dateOfBirth;
            $dataArr['user_id'] = $this->user_details->id;
            $dataArr['create_date'] = date('Y-m-d H:i:s');
            $country = extract_value($data, 'country', '');
            $dataArr['country'] = (!empty($country)) ? $country : 101;
            /* Update User Data Into Users Table */
            if (empty($id)) {
                $option = array(
                    'table' => 'user_pan_card',
                    'where' => array('pan_number' => $dataArr['pan_number'], 'verification_status!=' => 3)
                );
                $existsPanNumber = $this->common_model->customGet($option);
                if (!empty($existsPanNumber)) {
                    $return['status'] = 0;
                    $return['message'] = 'Pan Number already exists';
                    $this->response($return);
                    exit;
                }
                $option = array(
                    'table' => 'user_pan_card',
                    'where' => array('user_id' => $this->user_details->id, 'verification_status!=' => 3)
                );
                $isExistsPanCard = $this->common_model->customGet($option);
                if (!empty($isExistsPanCard)) {
                    $return['message'] = "Your PAN Card detail already submitted,We will verified with in 4-5 working days";
                    $return['status'] = 0;
                    $this->response($return);
                    exit;
                }
                $panCardFile = fileUpload('pan_card_file', 'users', 'png|jpg|jpeg|gif|pdf');
                if (isset($panCardFile['error']) && !empty($panCardFile['error'])) {
                    $return['message'] = strip_tags($panCardFile['error']);
                    $return['status'] = 0;
                    $this->response($return);
                    exit;
                }
                $dataArr['pan_card_file'] = 'uploads/users/' . $panCardFile['upload_data']['file_name'];
                $option = array(
                    'table' => 'user_pan_card',
                    'where' => array('user_id' => $this->user_details->id, 'verification_status' => 3)
                );
                $existsPan = $this->common_model->customGet($option);

                if (!empty($existsPan)) {
                    $dataArr['verification_status'] = 1;
                    $option = array(
                        'table' => 'user_pan_card',
                        'data' => $dataArr,
                        'where' => array('user_id' => $this->user_details->id)
                    );
                    $status = $this->common_model->customUpdate($option);
                } else {
                    $option = array(
                        'table' => 'user_pan_card',
                        'data' => $dataArr
                    );
                    $status = $this->common_model->customInsert($option);
                }
            } else {
                $option = array(
                    'table' => 'user_pan_card',
                    'where' => array('pan_number' => $dataArr['pan_number'], 'user_id !=' => $this->user_details->id, 'verification_status!=' => 3)
                );
                $existsPanNumber = $this->common_model->customGet($option);
                if (!empty($existsPanNumber)) {
                    $return['status'] = 0;
                    $return['message'] = 'Pan Number already exists';
                    $this->response($return);
                    exit;
                }
                if (!empty($_FILES['pan_card_file']['name'])) {
                    $panCardFile = fileUpload('pan_card_file', 'users', 'png|jpg|jpeg|gif|pdf');
                    if (isset($panCardFile['error']) && !empty($panCardFile['error'])) {
                        $return['message'] = strip_tags($panCardFile['error']);
                        $return['status'] = 0;
                        $this->response($return);
                        exit;
                    }
                    $dataArr['pan_card_file'] = 'uploads/users/' . $panCardFile['upload_data']['file_name'];
                }
                $dataArr['verification_status'] = 1;
                $option = array(
                    'table' => 'user_pan_card',
                    'data' => $dataArr,
                    'where' => array('id' => $id)
                );
                $status = $this->common_model->customUpdate($option);
            }
            if ($status) {
                /* Return success response */
                $options = array(
                    'table' => 'notifications',
                    'data' => array(
                        'user_id' => 1,
                        'type_id' => 0,
                        'sender_id' => $this->user_details->id,
                        'noti_type' => 'USER_PANCARD_VERIFY',
                        'message' => $dataArr['full_name'] . ' just uploaded pancard for verification.',
                        'read_status' => 'NO',
                        'sent_time' => date('Y-m-d H:i:s'),
                        'user_type' => 'ADMIN'
                    )
                );
                //$this->common_model->customInsert($options);


                $return['status'] = 1;
                $return['message'] = 'Your PAN Card detail successfully submitted,We will verified with in 4-5 working days';
            } else {
                $is_error = db_err_msg();
                $return['status'] = 0;
                if ($is_error == FALSE) {
                    $return['message'] = "Your PAN Card detail incorrect,Please try again";
                } else {
                    $return['message'] = $is_error;
                }
            }
        }
        $this->response($return);
    }

    /**
     * Function Name: verify_bank_account
     * Description:   To add user bank account
     */
    function verify_bank_account_post() {
        $data = $this->input->post();
        $return['code'] = 200;
        //$return['response'] = new stdClass();
        $id = extract_value($data, 'id', '');
        $this->form_validation->set_rules('login_session_key', 'Login session key', 'trim|required');
        $this->form_validation->set_rules('name', 'Full Name', 'trim|required');
        $this->form_validation->set_rules('account_number', 'Bank Account Number', 'trim|required|numeric');
        $this->form_validation->set_rules('ifsc_code', 'IFSC Code', 'trim|required');
        $this->form_validation->set_rules('bank_name', 'Bank Name', 'trim|required');
        $this->form_validation->set_rules('branch_name', 'Branch Name', 'trim|required');
        if (empty($_FILES['account_file']['name']) && empty($id)) {
            $this->form_validation->set_rules('account_file', 'Bank Account File', 'required');
        }
        if ($this->form_validation->run() == FALSE) {
            $error = $this->form_validation->rest_first_error_string();
            $return['status'] = 0;
            $return['message'] = $error;
        } else {
            $dataArr = array();
            $dataArr['full_name'] = extract_value($data, 'name', '');
            $dataArr['account_number'] = extract_value($data, 'account_number', '');
            $dataArr['ifsc_code'] = extract_value($data, 'ifsc_code', '');
            $dataArr['bank_name'] = extract_value($data, 'bank_name', '');
            $dataArr['branch_name'] = extract_value($data, 'branch_name', '');
            $dataArr['user_id'] = $this->user_details->id;
            $dataArr['create_date'] = date('Y-m-d H:i:s');
            /* Update User Data Into Users Table */
            if (empty($id)) {
                $option = array(
                    'table' => 'user_bank_account_detail',
                    'where' => array('account_number' => $dataArr['account_number'], 'verification_status!=' => 3)
                );
                $existsAccountNumber = $this->common_model->customGet($option);
                if (!empty($existsAccountNumber)) {
                    $return['status'] = 0;
                    $return['message'] = 'Account Number already exists';
                    $this->response($return);
                    exit;
                }
                $option = array(
                    'table' => 'user_bank_account_detail',
                    'where' => array('user_id' => $this->user_details->id, 'verification_status!=' => 3)
                );
                $isExistsPanCard = $this->common_model->customGet($option);
                if (!empty($isExistsPanCard)) {
                    $return['message'] = "Your PAN Card detail already submitted,We will verified with in 4-5 working days";
                    $return['status'] = 0;
                    $this->response($return);
                    exit;
                }
                $panCardFile = fileUpload('account_file', 'users', 'png|jpg|jpeg|gif|pdf');
                if (isset($panCardFile['error']) && !empty($panCardFile['error'])) {
                    $return['message'] = strip_tags($panCardFile['error']);
                    $return['status'] = 0;
                    $this->response($return);
                    exit;
                }
                $dataArr['account_file'] = 'uploads/users/' . $panCardFile['upload_data']['file_name'];

                $option = array(
                    'table' => 'user_bank_account_detail',
                    'where' => array('user_id' => $this->user_details->id, 'verification_status' => 3)
                );
                $existsAccount = $this->common_model->customGet($option);
                if (!empty($existsAccount)) {
                    $dataArr['verification_status'] = 1;
                    $option = array(
                        'table' => 'user_bank_account_detail',
                        'data' => $dataArr,
                        'where' => array('user_id' => $this->user_details->id)
                    );
                    $status = $this->common_model->customUpdate($option);
                } else {
                    $option = array(
                        'table' => 'user_bank_account_detail',
                        'data' => $dataArr
                    );
                    $status = $this->common_model->customInsert($option);
                }
            } else {
                $option = array(
                    'table' => 'user_bank_account_detail',
                    'where' => array('account_number' => $dataArr['account_number'], 'user_id !=' => $this->user_details->id, 'verification_status!=' => 3)
                );
                $existsAccountNumber = $this->common_model->customGet($option);
                if (!empty($existsAccountNumber)) {
                    $return['status'] = 0;
                    $return['message'] = 'Account Number already exists';
                    $this->response($return);
                    exit;
                }
                if (!empty($_FILES['account_file']['name'])) {
                    $panCardFile = fileUpload('account_file', 'users', 'png|jpg|jpeg|gif|pdf');
                    if (isset($panCardFile['error']) && !empty($panCardFile['error'])) {
                        $return['message'] = strip_tags($panCardFile['error']);
                        $return['status'] = 0;
                        $this->response($return);
                        exit;
                    }
                    $dataArr['account_file'] = 'uploads/users/' . $panCardFile['upload_data']['file_name'];
                }
                $dataArr['verification_status'] = 1;
                $option = array(
                    'table' => 'user_bank_account_detail',
                    'data' => $dataArr,
                    'where' => array('id' => $id)
                );
                $status = $this->common_model->customUpdate($option);
            }
            if ($status) {
                /* Return success response */
                $options = array(
                    'table' => 'notifications',
                    'data' => array(
                        'user_id' => 1,
                        'type_id' => 0,
                        'sender_id' => $this->user_details->id,
                        'noti_type' => 'USER_BANK_ACCOUNT_VERIFY',
                        'message' => $dataArr['full_name'] . ' just uploaded bank details for verification.',
                        'read_status' => 'NO',
                        'sent_time' => date('Y-m-d H:i:s'),
                        'user_type' => 'ADMIN'
                    )
                );
                //$this->common_model->customInsert($options);

                $return['status'] = 1;
                $return['message'] = 'Your Bank Account detail successfully submitted,We will verified with in 4-5 working days';
            } else {
                $is_error = db_err_msg();
                $return['status'] = 0;
                if ($is_error == FALSE) {
                    $return['message'] = "Your Bank Account detail incorrect,Please try again";
                } else {
                    $return['message'] = $is_error;
                }
            }
        }
        $this->response($return);
    }

    /**
     * Function Name: verify_aadhar_card
     * Description:   To add aadhar card
     */
    function verify_aadhar_card_post() {
        $data = $this->input->post();
        $return['code'] = 200;
        //$return['response'] = new stdClass();
        $id = extract_value($data, 'id', '');
        $this->form_validation->set_rules('login_session_key', 'Login session key', 'trim|required');
        $this->form_validation->set_rules('name', 'Full Name', 'trim|required');
        $this->form_validation->set_rules('date_of_birth', 'Date of Birth', 'trim|required|callback__validate_birthdate_format');
        $this->form_validation->set_rules('aadhar_number', 'Aadhar Number', 'trim|required|numeric');
        $this->form_validation->set_rules('state', 'State', 'trim|required|numeric');
        if (empty($_FILES['aadhar_file']['name']) && empty($id)) {
            $this->form_validation->set_rules('aadhar_file', 'AAdhar Card File', 'required');
        }
        if ($this->form_validation->run() == FALSE) {
            $error = $this->form_validation->rest_first_error_string();
            $return['status'] = 0;
            $return['message'] = $error;
        } else {
            $dataArr = array();
            $dateOfBirth = date('Y-m-d', strtotime(extract_value($data, 'date_of_birth', '')));
            $dataArr['full_name'] = extract_value($data, 'name', '');
            $dataArr['aadhar_number'] = extract_value($data, 'aadhar_number', '');
            $dataArr['state'] = extract_value($data, 'state', '');
            $dataArr['date_of_birth'] = $dateOfBirth;
            $dataArr['user_id'] = $this->user_details->id;
            $dataArr['create_date'] = date('Y-m-d H:i:s');
            /* Update User Data Into Users Table */
            if (empty($id)) {
                $option = array(
                    'table' => 'user_aadhar_card',
                    'where' => array('aadhar_number' => $dataArr['aadhar_number'])
                );
                $existsAadharNumber = $this->common_model->customGet($option);
                if (!empty($existsAadharNumber)) {
                    $return['status'] = 0;
                    $return['message'] = 'Aadhar Number already exists';
                    $this->response($return);
                    exit;
                }
                $option = array(
                    'table' => 'user_aadhar_card',
                    'where' => array('user_id' => $this->user_details->id)
                );
                $isExistsPanCard = $this->common_model->customGet($option);
                if (!empty($isExistsPanCard)) {
                    $return['message'] = "Your Aadhar Card detail already submitted,We will verified with in 4-5 working days";
                    $return['status'] = 0;
                    $this->response($return);
                    exit;
                }
                $panCardFile = fileUpload('aadhar_file', 'users', 'png|jpg|jpeg|gif|pdf');
                if (isset($panCardFile['error']) && !empty($panCardFile['error'])) {
                    $return['message'] = strip_tags($panCardFile['error']);
                    $return['status'] = 0;
                    $this->response($return);
                    exit;
                }
                $dataArr['aadhar_file'] = 'uploads/users/' . $panCardFile['upload_data']['file_name'];
                $option = array(
                    'table' => 'user_aadhar_card',
                    'data' => $dataArr
                );
                $status = $this->common_model->customInsert($option);
            } else {
                $option = array(
                    'table' => 'user_aadhar_card',
                    'where' => array('aadhar_number' => $dataArr['aadhar_number'], 'user_id !=' => $this->user_details->id)
                );
                $existsAadharNumber = $this->common_model->customGet($option);
                if (!empty($existsAadharNumber)) {
                    $return['status'] = 0;
                    $return['message'] = 'Aadhar Number already exists';
                    $this->response($return);
                    exit;
                }
                if (!empty($_FILES['aadhar_file']['name'])) {
                    $panCardFile = fileUpload('aadhar_file', 'users', 'png|jpg|jpeg|gif|pdf');
                    if (isset($panCardFile['error']) && !empty($panCardFile['error'])) {
                        $return['message'] = strip_tags($panCardFile['error']);
                        $return['status'] = 0;
                        $this->response($return);
                        exit;
                    }
                    $dataArr['aadhar_file'] = 'uploads/users/' . $panCardFile['upload_data']['file_name'];
                }
                $dataArr['verification_status'] = 1;
                $option = array(
                    'table' => 'user_aadhar_card',
                    'data' => $dataArr,
                    'where' => array('id' => $id)
                );
                $status = $this->common_model->customUpdate($option);
            }
            if ($status) {
                /* Return success response */
                $options = array(
                    'table' => 'notifications',
                    'data' => array(
                        'user_id' => 1,
                        'type_id' => 0,
                        'sender_id' => $this->user_details->id,
                        'noti_type' => 'USER_AADHAR_VERIFY',
                        'message' => $dataArr['full_name'] . ' just uploaded aadhar card details for verification.',
                        'read_status' => 'NO',
                        'sent_time' => date('Y-m-d H:i:s'),
                        'user_type' => 'ADMIN'
                    )
                );
                //$this->common_model->customInsert($options);


                $return['status'] = 1;
                $return['message'] = 'Your Aadhar Card detail successfully submitted,We will verified with in 4-5 working days';
            } else {
                $is_error = db_err_msg();
                $return['status'] = 0;
                if ($is_error == FALSE) {
                    $return['message'] = "Your Aadhar Card detail incorrect,Please try again";
                } else {
                    $return['message'] = $is_error;
                }
            }
        }
        $this->response($return);
    }

    /**
     * Function Name: resend_verification_link
     * Description:   To Re-send User Verification Link
     */
    function email_verification_link_post() {
        $return['code'] = 200;
        //$return['response'] = new stdClass();
        $data = $this->input->post();
        $this->form_validation->set_rules('login_session_key', 'Login session key', 'trim|required');
        $this->form_validation->set_rules('email', 'Email Id', 'trim|required|valid_email');
        if ($this->form_validation->run() == FALSE) {
            $error = $this->form_validation->rest_first_error_string();
            $return['status'] = 0;
            $return['message'] = $error;
        } else {
            $dataArr = array();
            $dataArr['email'] = extract_value($data, 'email', '');

            /* Get User Data From Users Table */
            $result = $this->common_model->getsingle(USERS, $dataArr);

            if (empty($result)) {
                $return['status'] = 0;
                $return['message'] = 'Email-id does not exist';
            } else {
                if ($result->email_verify == 0) {
                    if ($result->signup_type == "WEB") {
                        $user_id = $result->id;
                        $user_email = $result->email;
                        /* Update user token */
                        //$token = encoding($user_email . "-" . $user_id . "-" . time());
                        $token = mt_rand(100000, 999999);
                        $option = array(
                            'table' => 'users',
                            'where' => array('otp' => $token),
                        );
                        $alreadyUsedOtp = $this->common_model->customGet($option);
                        if (!empty($alreadyUsedOtp)) {
                            $token = mt_rand(100000, 999999);
                        }

                        $tokenArr = array('user_token' => $token);
                        $update_status = $this->common_model->updateFields(USERS, $tokenArr, array('id' => $user_id));
                        //$link = base_url() . 'auth/verifyuser?email=' . $user_email . '&token=' . $token;
                        /* $message = "";
                          $message .= "<img style='width:200px' src='" . base_url() . getConfig('site_logo') . "' class='img-responsive'></br></br>";
                          $message .= "<br><br> Hello ".  ucwords($result->firest_name).",<br/><br/>";
                          $message .= "Your " . getConfig('site_name') . " profile has been created. Please click on below link to verify your account. <br/><br/>";
                          $message .= "Click here : <a href='" . $link . "'>Verify Your Email</a>";
                          $status = send_mail($message, '[' . getConfig('site_name') . '] Email verification', $user_email, getConfig('admin_email')); */

                        $html = array();
                        $html['token'] = $token;
                        $html['logo'] = base_url() . getConfig('site_logo');
                        $html['site'] = getConfig('site_name');
                        $html['user'] = ucwords($result->first_name);
                        $email_template = $this->load->view('email/email_verification_tpl', $html, true);
                        $status = send_mail($email_template, '[' . getConfig('site_name') . '] Email verification', $user_email, getConfig('admin_email'));

                        if ($status) {
                            $return['status'] = 1;
                            $return['message'] = 'An email has been sent verification Code. Please check your inbox';
                        } else {
                            $return['status'] = 0;
                            $return['message'] = EMAIL_SEND_FAILED;
                        }
                    } else {
                        $return['status'] = 0;
                        $return['message'] = 'Social User can not make request';
                    }
                } else {
                    $return['status'] = 0;
                    $return['message'] = 'Email already verified';
                }
            }
        }
        $this->response($return);
    }

    public function verify_code_email111_post() {
        $return['code'] = 200;
        //$return['response'] = new stdClass();
        $data = $this->input->post();
        $this->form_validation->set_rules('vCode', 'Verification Code', 'trim|required|numeric');
        $this->form_validation->set_rules('email', 'Email Id', 'trim|required|valid_email');
        if ($this->form_validation->run() == FALSE) {
            $error = $this->form_validation->rest_first_error_string();
            $return['status'] = 0;
            $return['message'] = $error;
        } else {
            $vCode = extract_value($data, 'vCode', '');
            $email = extract_value($data, 'email', '');
            $where = array('email' => $email, 'user_token' => $vCode);
            $isUser = $this->common_model->getsingle(USERS, $where);
            print_r($isUser);
            die;
            if (!empty($isUser)) {
                $Status = $this->common_model->updateFields(USERS, array('user_token' => NULL, 'email_verify' => 1), array('id' => $isUser->id));
                if ($Status) {
                    referralSchemeCashBonus($isUser->id);
                    $return['status'] = 1;
                    $return['message'] = 'Your email has been verified';
                } else {
                    $return['status'] = 0;
                    $return['message'] = GENERAL_ERROR;
                }
            } else {
                $return['status'] = 0;
                $return['message'] = 'Invalid Verification Code,Please resend again';
            }
        }
        $this->response($return);
    }

    /**
     * Function Name: user
     * Description:   To user verification email
     */
    public function verify_code_email_post() {
        $return['code'] = 200;
        //$return['response'] = new stdClass();
        $data = $this->input->post();
        $this->form_validation->set_rules('vCode', 'Verification Code', 'trim|required|numeric');
        $this->form_validation->set_rules('email', 'Email Id', 'trim|required|valid_email');
        if ($this->form_validation->run() == FALSE) {
            $error = $this->form_validation->rest_first_error_string();
            $return['status'] = 0;
            $return['message'] = $error;
        } else {
            $vCode = extract_value($data, 'vCode', '');
            $email = extract_value($data, 'email', '');
            $where = array('email' => $email, 'user_token' => $vCode);
            $isUser = $this->common_model->getsingle(USERS, $where);
            if (!empty($isUser)) {
                $Status = $this->common_model->updateFields(USERS, array('user_token' => NULL, 'email_verify' => 1), array('id' => $isUser->id));
                if ($Status) {
                    referralSchemeCashBonus($isUser->id);
                    $return['status'] = 1;
                    $return['message'] = 'Your email has been verified';
                } else {
                    $return['status'] = 0;
                    $return['message'] = GENERAL_ERROR;
                }
            } else {
                $return['status'] = 0;
                $return['message'] = 'Invalid Verification Code,Please resend again';
            }
        }
        $this->response($return);
    }

    /**
     * Function Name: forgot_password
     * Description:   To User Forgot Password
     */
    function forgot_password_post() {
        $return['code'] = 200;
        //$return['response'] = new stdClass();
        $data = $this->input->post();
        $this->form_validation->set_rules('email', 'Email Id', 'trim|required|valid_email');
        if ($this->form_validation->run() == FALSE) {
            $error = $this->form_validation->rest_first_error_string();
            $return['status'] = 0;
            $return['message'] = $error;
        } else {
            $dataArr = array();
            $dataArr['email'] = extract_value($data, 'email', '');
            /* Get User Data From Users Table */
            $result = $this->common_model->getsingle(USERS, $dataArr);
            if (empty($result)) {
                $return['status'] = 0;
                $return['message'] = 'Email-id does not exist';
            } else {
                $user_email = $result->email;
                $user_id = $result->id;
                $identity_column = $this->config->item('identity', 'ion_auth');
                $identity = $this->ion_auth->where($identity_column, $this->input->post('email'))->users()->row();
                if (empty($identity)) {
                    $return['status'] = 0;
                    $return['message'] = 'Email-id does not exist';
                    $this->response($return);
                    exit;
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
                        $html['active_url'] = base_url() . "pwfpanel/reset_password_app/" . $forgotten['forgotten_password_code'];
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
                    $return['status'] = 1;
                    $return['message'] = 'An email has been sent Reset Password link, please check spam folder if you do not received it in your inbox and add our mail id in your addressbook.';
                    $this->response($return);
                    exit;
                } else {
                    $return['status'] = 0;
                    $return['message'] = 'Email-id does not exist';
                    $this->response($return);
                    exit;
                }
            }
        }
        $this->response($return);
    }

    function forgot_password_new_post() {
        $return['code'] = 200;
        //$return['response'] = new stdClass();
        $data = $this->input->post();
        $this->form_validation->set_rules('email', 'Email Id', 'trim|required|valid_email');
        if ($this->form_validation->run() == FALSE) {
            $error = $this->form_validation->rest_first_error_string();
            $return['status'] = 0;
            $return['message'] = $error;
        } else {
            $dataArr = array();
            $dataArr['email'] = extract_value($data, 'email', '');
            /* Get User Data From Users Table */
            $result = $this->common_model->getsingle(USERS, $dataArr);
            if (empty($result)) {
                $return['status'] = 0;
                $return['message'] = 'Email-id does not exist';
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
                    $return['status'] = 0;
                    $return['message'] = $error;
                    $this->response($return);
                    exit;
                }
                $token = mt_rand(100000, 999999);
                $option = array(
                    'table' => 'users',
                    'where' => array('otp' => $token),
                );
                $alreadyUsedOtp = $this->common_model->customGet($option);
                if (!empty($alreadyUsedOtp)) {
                    $token = mt_rand(100000, 999999);
                }

                $tokenArr = array('user_token' => $token);
                $update_status = $this->common_model->updateFields(USERS, $tokenArr, array('id' => $user_id));

                $html = array();
                $html['token'] = $token;
                $html['logo'] = base_url() . getConfig('site_logo');
                $html['site'] = getConfig('site_name');
                $html['user'] = ucwords($result->first_name);
                $email_template = $this->load->view('email/forgot_password_code_tpl_new', $html, true);
                $status = send_mail_app($email_template, 'Forgot Password Code', $user_email, getConfig('admin_email'));
                // $forgotten = $this->ion_auth->forgotten_password_app($identity->{$this->config->item('identity', 'ion_auth')});
                //if ($status==1) {
                $return['status'] = 1;
                $return['message'] = "An email has been sent Forgot Password verification Code. Please check your inbox";
                // } else {
                //     $return['status'] = 0;
                //     $return['message'] = "Unable to Send Verification Code Email,Please try again";
                // }
            }
        }
        $this->response($return);
    }

    /**
     * Function Name: user
     * Description:   To user reset password
     */
    public function reset_password_post() {
        $return['code'] = 200;
        //$return['response'] = new stdClass();
        $data = $this->input->post();
        $this->form_validation->set_rules('vCode', 'Verification Code', 'trim|required|numeric');
        $this->form_validation->set_rules('email', 'Email Id', 'trim|required|valid_email');
        $this->form_validation->set_rules('new_password', 'New Password', 'trim|required|min_length[6]|max_length[14]|callback_is_secure_pass');
        $this->form_validation->set_rules('cnfm_password', 'Confirm Password', 'trim|required|min_length[6]|max_length[14]|matches[new_password]');
        if ($this->form_validation->run() == FALSE) {
            $error = $this->form_validation->rest_first_error_string();
            $return['status'] = 0;
            $return['message'] = $error;
        } else {
            $vCode = extract_value($data, 'vCode', '');
            $email = extract_value($data, 'email', '');
            $new_password = extract_value($data, 'new_password', '');
            $where = array('email' => $email, 'user_token' => $vCode);
            $isUser = $this->common_model->getsingle(USERS, $where);
            if (!empty($isUser)) {
                $change = $this->ion_auth->reset_password($email, $this->input->post('new_password'));
                if ($change) {
                    $Status = $this->common_model->updateFields(USERS, array('user_token' => NULL, 'is_pass_token' => $this->input->post('new_password')), array('id' => $isUser->id));
                    $return['status'] = 1;
                    $return['message'] = 'Your Password Successfully Reset';
                } else {
                    $return['status'] = 0;
                    $return['message'] = GENERAL_ERROR;
                }
            } else {
                $return['status'] = 0;
                $return['message'] = 'Invalid Verification Code,Please resend again';
            }
        }
        $this->response($return);
    }

    /**
     * Function Name: change_profile_image
     * Description:   To Change User Profile Image
     */
    function change_profile_image_post() {
        $data = $this->input->post();
        $return['code'] = 200;
        //$return['response'] = new stdClass();
        $this->form_validation->set_rules('login_session_key', 'Login session key', 'trim|required');

        $this->form_validation->set_rules('user_image', 'User Image', 'required');

        if ($this->form_validation->run() == FALSE) {
            $error = $this->form_validation->rest_first_error_string();
            $return['status'] = 0;
            $return['message'] = $error;
        } else {
            $dataArr = array();

            $user_image = extract_value($data, 'user_image', '');

            /* Upload user image */

            $dataArr['profile_pic'] = $user_image;

            /* Create user image thumb */
            //$dataArr['user_image_thumb'] = get_image_thumb($dataArr['profile_pic'], 'users', 250, 250);

            /* Update User Details */
            $status = $this->common_model->updateFields(USERS, $dataArr, array('id' => $this->user_details->id));
            if ($status) {
                /* Return Response */
                // $response = array();
                // $response['user_image'] = base_url() . $dataArr['profile_pic'];
                // $return['response'] = $response;
                $return['status'] = 1;
                $return['message'] = 'Profile image updated successfully';
            } else {
                $is_error = db_err_msg();
                $return['status'] = 0;
                if ($is_error == FALSE) {
                    $return['message'] = NO_CHANGES;
                } else {
                    $return['message'] = $is_error;
                }
            }
        }
        $this->response($return);
    }

    /**
     * Function Name: mobile_send_verification_otp
     * Description:   To send User mobile Verification otp
     */
    function mobile_send_verification_otp_post() {
        $return['code'] = 200;
        //$return['response'] = new stdClass();
        $data = $this->input->post();
        $this->form_validation->set_rules('login_session_key', 'Login session key', 'trim|required|callback__validate_login_session_key');
        $this->form_validation->set_rules('mobile', 'Mobile', 'trim|required|min_length[10]|max_length[11]|numeric');
        if ($this->form_validation->run() == FALSE) {
            $error = $this->form_validation->rest_first_error_string();
            $return['status'] = 0;
            $return['message'] = $error;
        } else {
            $dataArr = array();
            $genOtp = mt_rand(100000, 999999);
            $option = array(
                'table' => 'users',
                'where' => array('otp' => $genOtp),
            );
            $alreadyUsedOtp = $this->common_model->customGet($option);
            if (!empty($alreadyUsedOtp)) {
                $genOtp = mt_rand(100000, 999999);
            }

            $mobile = extract_value($data, 'mobile', '');
            $user_id = $this->user_details->id;
            $options = array('table' => 'users',
                'where' => array('id !=' => $this->user_details->id, 'phone' => $mobile));
            $alreadyUsed = $this->common_model->customGet($options);
            if (!empty($alreadyUsed)) {
                $return['status'] = 0;
                $return['message'] = 'Mobile Number already registered';
                $this->response($return);
                exit;
            }
            if ($this->user_details->verify_mobile != 1) {
                $options = array('table' => 'users',
                    'data' => array('phone' => $mobile,
                        'otp' => $genOtp),
                    'where' => array('id' => $this->user_details->id));
                $updateCode = $this->common_model->customUpdate($options);
                if (!empty($updateCode)) {
                    $postfields = array('mobile' => $mobile,
                        'message' => "Your funtush11 verification code is " . $genOtp,
                    );
                    if ($this->smsSend($postfields)) {
                        $return['status'] = 1;
                        $return['message'] = 'OTP has been sent on your updated mobile number';
                    } else {
                        $return['status'] = 0;
                        $return['message'] = 'OTP send failed please try again';
                    }
                } else {
                    $return['status'] = 0;
                    $return['message'] = 'User does not exist';
                }
            } else {
                $return['status'] = 0;
                $return['message'] = 'Mobile already verified';
            }
        }
        $this->response($return);
    }

    /**
     * Function Name: mobile_verify_otp
     * Description:   To verify mobile by otp
     */
    function mobile_verify_otp_social_post() {
        $return['code'] = 200;
        //$return['response'] = new stdClass();
        $data = $this->input->post();
        $this->form_validation->set_rules('login_session_key', 'Login session key', 'trim|required|callback__validate_login_session_key');
        $this->form_validation->set_rules('otp', 'OTP', 'trim|required|min_length[6]|numeric');
        if ($this->form_validation->run() == FALSE) {
            $error = $this->form_validation->rest_first_error_string();
            $return['status'] = 0;
            $return['message'] = $error;
        } else {
            $dataArr = array();
            $otp = extract_value($data, 'otp', '');
            $user_id = $this->user_details->id;
            $options = array(
                'table' => 'users',
                'select' => 'id',
                'where' => array('id' => $user_id,
                    'otp' => $otp)
            );
            $isUserOtp = $this->common_model->customGet($options);
            if (!empty($isUserOtp)) {
                $options = array(
                    'table' => 'users',
                    'data' => array('otp' => 0, 'verify_mobile' => 1),
                    'where' => array('id' => $user_id,
                    )
                );
                $this->common_model->customUpdate($options);
                registerCashBonusAndAppDownload($user_id);
                referralSchemeCashBonus($user_id);
                $return['response'] = array('verify_mobile' => 1);
                $return['status'] = 1;
                $return['message'] = 'Mobile has been successfully verified';
            } else {
                $return['status'] = 0;
                $return['message'] = 'Please enter correct OTP, or use Resend OTP';
            }
        }
        $this->response($return);
    }

    /*
     * Function Name: notification
     * Description:   To Get User notification
     */

    function notification_old_post() {
        $data = $this->input->post();
        $return['code'] = 200;
        //$return['response'] = new stdClass();
        $this->form_validation->set_rules('login_session_key', 'Login session key', 'trim|required');
        if ($this->form_validation->run() == FALSE) {
            $error = $this->form_validation->rest_first_error_string();
            $return['status'] = 0;
            $return['message'] = $error;
        } else {
            $limit = extract_value($data, 'limit', '');
            $offset = extract_value($data, 'offset', '');
            $user_id = $this->user_details->id;
            $options = array('table' => 'notifications',
                'select' => 'id as notification_id,TRIM(message) as message,read_status,sent_time,noti_type as notification_type,type_id',
                'where' => array('user_id' => $user_id, 'read_status' => 'NO', 'user_type' => 'USER'),
                'limit' => array($limit => $offset),
                'order' => array('id' => 'DESC')
            );
            $notification = $this->common_model->customGet($options);
            $options = array('table' => 'notifications',
                'select' => 'id as notification_id,TRIM(message) as message,read_status,sent_time,noti_type as notification_type,type_id',
                'where' => array('user_id' => $user_id, 'read_status' => 'NO', 'user_type' => 'USER')
            );
            $total = $this->common_model->customCount($options);
            if (!empty($notification)) {
                $return['status'] = 1;
                $return['response'] = $notification;
                $return['total'] = $total;
                $return['message'] = 'Notification successfully found';
            } else {
                $return['status'] = 0;
                $return['message'] = 'Notification not found';
            }
        }
        $this->response($return);
    }

    function notification_post() {
        $data = $this->input->post();
        $return['code'] = 200;
        //$return['response'] = new stdClass();
        $this->form_validation->set_rules('login_session_key', 'Login session key', 'trim|required');
        if ($this->form_validation->run() == FALSE) {
            $error = $this->form_validation->rest_first_error_string();
            $return['status'] = 0;
            $return['message'] = $error;
        } else {
            $notiArr = array();
            $limit = extract_value($data, 'limit', '');
            $offset = extract_value($data, 'offset', '');
            $user_id = $this->user_details->id;
            $options = array('table' => 'notifications',
                'select' => 'id as notification_id,TRIM(message) as message,read_status,sent_time,noti_type as notification_type,status',
                'where' => array('user_id' => $user_id, 'user_type' => 'USER'),
                'limit' => array($limit => $offset),
                'order' => array('id' => 'DESC')
            );
            $notifications = $this->common_model->customGet($options);
            $options = array('table' => 'notifications',
                'select' => 'id as notification_id,TRIM(message) as message,read_status,sent_time,noti_type as notification_type,type_id,status',
                'where' => array('user_id' => $user_id, 'user_type' => 'USER')
            );
            $total = $this->common_model->customCount($options);
            if (!empty($notifications)) {
                foreach ($notifications as $notification) {
                    $temp['notification_id'] = $notification->notification_id;
                    if ($notification->status == 1) {
                        $temp['message'] = decoding($notification->message);
                        $temp['notification_type'] = decoding($notification->notification_type);
                    } else {
                        $temp['message'] = $notification->message;
                        $temp['notification_type'] = $notification->notification_type;
                    }

                    $temp['read_status'] = $notification->read_status;
                    $temp['sent_time'] = UTCToConvertISTSecond($notification->sent_time, 'Asia/Kolkata');

                    $notiArr[] = $temp;
                }

                $return['status'] = 1;
                $return['response'] = $notiArr;
                $return['total'] = $total;
                $return['message'] = 'Notification successfully found';
            } else {
                $return['status'] = 0;
                $return['message'] = 'Notification not found';
            }
        }
        $this->response($return);
    }

    /*
     * Function Name: notification_read
     * Description:   To Get User notification read
     */

    function notification_read_post() {
        $data = $this->input->post();
        $return['code'] = 200;
        //$return['response'] = new stdClass();
        $this->form_validation->set_rules('login_session_key', 'Login session key', 'trim|required');
        $this->form_validation->set_rules('notification_id', 'Notification Id', 'trim|required|numeric');
        if ($this->form_validation->run() == FALSE) {
            $error = $this->form_validation->rest_first_error_string();
            $return['status'] = 0;
            $return['message'] = $error;
        } else {
            $user_id = $this->user_details->id;
            $notification_id = extract_value($data, 'notification_id', '');
            $options = array('table' => 'notifications',
                'data' => array('read_status' => 'YES'),
                'where' => array('user_id' => $user_id, 'id' => $notification_id)
            );
            $notification = $this->common_model->customUpdate($options);
            if (!empty($notification)) {
                $return['status'] = 1;
                $return['message'] = 'Successfully read';
            } else {
                $return['status'] = 0;
                $return['message'] = 'Failed to read';
            }
        }
        $this->response($return);
    }

    /*
     * Function Name: transactions_history
     * Description:   To Get User transactions history
     */

    function transactions_history_post() {
        $data = $this->input->post();
        $return['code'] = 200;
        //$return['response'] = new stdClass();
        $this->form_validation->set_rules('login_session_key', 'Login session key', 'trim|required');
        if ($this->form_validation->run() == FALSE) {
            $error = $this->form_validation->rest_first_error_string();
            $return['status'] = 0;
            $return['message'] = $error;
        } else {
            $user_id = $this->user_details->id;
            $limit = extract_value($data, 'limit', '');
            $offset = extract_value($data, 'offset', '');
            $options = array('table' => 'transactions_history',
                'select' => "orderId,message, (CASE WHEN cr != 0 THEN cr ELSE dr END) as amount,datetime as tranasction_date, 'completed' as status",
                'where' => array('user_id' => $user_id, 'transaction_type' => 'CASH'),
                'limit' => array($limit => $offset),
                'order' => array('id' => 'DESC')
            );
            $response = $this->common_model->customGet($options);
            $options = array('table' => 'transactions_history',
                'select' => "orderId,message, (CASE WHEN cr != 0 THEN cr ELSE dr END) as amount,datetime as tranasction_date, 'completed' as status",
                'where' => array('user_id' => $user_id, 'transaction_type' => 'CASH')
            );
            $total = $this->common_model->customCount($options);
            if (!empty($response)) {
                $return['status'] = 1;
                $return['response'] = $response;
                $return['total'] = $total;
                $return['message'] = 'Transactions successfully listed';
            } else {
                $return['status'] = 0;
                $return['message'] = 'Transactions not found';
            }
        }
        $this->response($return);
    }

    /*
     * Function Name: transactions
     * Description:   To Get User transactions history
     */

    function transactions_post() {
        $data = $this->input->post();
        $return['code'] = 200;
        //$return['response'] = new stdClass();
        $this->form_validation->set_rules('login_session_key', 'Login session key', 'trim|required');
        $this->form_validation->set_rules('transaction_type', 'transaction type', 'trim|required');
        $this->form_validation->set_rules('page_no', 'Page No', 'trim|numeric|callback__pageno_min_value');

        if ($this->form_validation->run() == FALSE) {
            $error = $this->form_validation->rest_first_error_string();
            $return['status'] = 0;
            $return['message'] = $error;
        } else {
            $page_no = extract_value($data, 'page_no', 1);
            $offset = get_offsets($page_no);
            $user_id = $this->user_details->id;
            $transaction_type = extract_value($data, 'transaction_type', '');
            $from_date = extract_value($data, 'from_date', '');
            $to_date = extract_value($data, 'to_date', '');
            $debit = extract_value($data, 'debit', '');
            $credit = extract_value($data, 'credit', '');
            $both = extract_value($data, 'both', '');
            $from_date = date('Y-m-d', strtotime($from_date));
            $to_date = date('Y-m-d', strtotime($to_date));
            $options = array('table' => 'transactions_history',
                'select' => "*",
                'where' => array('user_id' => $user_id),
                'limit' => array(20 => $offset),
                'order' => array('id' => 'DESC')
            );
            if ($transaction_type == "CASH") {
                $options['where']['transaction_type'] = 'CASH';
                $options['where']['pay_type !='] = 'BONUS';
            }
            if ($transaction_type == "CHIP") {
                $options['where']['transaction_type'] = 'CHIP';
            }
            if ($transaction_type == "CASHBONUS") {
                $options['where']['transaction_type'] = 'CASH';
                $options['where']['pay_type'] = 'BONUS';
            }
            if ($transaction_type == "CASHWITHDRAWAL") {
                $options['where']['transaction_type'] = 'CASH';
                $options['where']['pay_type'] = 'WITHDRAWAL';
            }
            if ($from_date) {
                $options['where']['DATE(datetime) >='] = $from_date;
            }
            if ($to_date) {
                $options['where']['DATE(datetime) <='] = $to_date;
            }
            if ($debit) {
                $options['where']['dr!='] = 0;
            }
            if ($credit) {
                $options['where']['cr !='] = 0;
            }
            //  if($both){
            //     $options['where']['datetime <='] = $to_date;
            // }
            $response = $this->common_model->customGet($options);
            echo $this->db->last_query();
            exit;
            $total_requested = (int) $page_no * 20;

            $options1 = array('table' => 'transactions_history',
                'select' => "*",
                'where' => array('user_id' => $user_id)
            );
            if ($transaction_type == "CASH") {
                $options1['where']['transaction_type'] = 'CASH';
                $options1['where']['pay_type !='] = 'BONUS';
            }
            if ($transaction_type == "CHIP") {
                $options1['where']['transaction_type'] = 'CHIP';
            }
            if ($transaction_type == "CASHBONUS") {
                $options1['where']['transaction_type'] = 'CASH';
                $options1['where']['pay_type'] = 'BONUS';
            }
            if ($transaction_type == "CASHWITHDRAWAL") {
                $options1['where']['transaction_type'] = 'CASH';
                $options1['where']['pay_type'] = 'WITHDRAWAL';
            }
            if ($from_date) {
                $options1['where']['DATE(datetime) >='] = $from_date;
            }
            if ($to_date) {
                $options1['where']['DATE(datetime) <='] = $to_date;
            }
            if ($debit) {
                $options1['where']['dr!='] = 0;
            }
            if ($credit) {
                $options1['where']['cr !='] = 0;
            }
            $total = $this->common_model->customCount($options1);
            if ($total > $total_requested) {
                $has_next = TRUE;
            } else {
                $has_next = FALSE;
            }

            if (!empty($response)) {
                $return['status'] = 1;
                $return['response'] = $response;
                $return['total'] = $total;
                $return['has_next'] = $has_next;
                $return['message'] = 'Transactions successfully listed';
            } else {
                $return['status'] = 0;
                $return['message'] = 'Transactions not found';
            }
        }
        $this->response($return);
    }

    /*
     * Function Name: transactions
     * Description:   To Get User transactions history
     */

    function transactions_app_old_post() {
        $data = $this->input->post();
        $return['code'] = 200;
        //$return['response'] = new stdClass();
        $this->form_validation->set_rules('login_session_key', 'Login session key', 'trim|required');
        $this->form_validation->set_rules('transaction_type', 'transaction type', 'trim|required');

        if ($this->form_validation->run() == FALSE) {
            $error = $this->form_validation->rest_first_error_string();
            $return['status'] = 0;
            $return['message'] = $error;
        } else {
            $user_id = $this->user_details->id;
            $transaction_type = extract_value($data, 'transaction_type', '');
            $from_date = extract_value($data, 'from_date', '');
            $to_date = extract_value($data, 'to_date', '');
            $cash_type = extract_value($data, 'cash_type', '');
            $limit = extract_value($data, 'limit', '');
            $offset = extract_value($data, 'offset', '');
            $select = "available_balance,opening_balance";
            if ($transaction_type == "CASH") {
                $select = "total_wallet_balance as available_balance, wallet_opening_balance as opening_balance";
            }
            $options = array('table' => 'transactions_history',
                'select' => "id,transaction_type,pay_type,user_id,match_id,contest_id,orderId,dr,cr,message,status,datetime, $select",
                'where' => array('user_id' => $user_id),
                'limit' => array($limit => $offset),
                'order' => array('id' => 'DESC')
            );
            if ($transaction_type == "CASH") {
                $options['where']['transaction_type'] = 'CASH';
                //$options['where_not_in']['pay_type'] = array('BONUS', 'WITHDRAWAL');
            }
            if ($transaction_type == "UNUTILIZED") {
                $options['where']['transaction_type'] = 'CASH';
                //$options['where_in']['bonus_type'] = 'DEPOSIT';
                $options['where_in']['pay_type'] = array('DEPOSIT', 'JOINCONTEST', 'REFUND');
            }
            if ($transaction_type == "REALCASH") {
                $options['where']['transaction_type'] = 'CASH';
                $options['where_in']['pay_type'] = array('WINNING');
            }
            if ($transaction_type == "CHIP") {
                $options['where']['transaction_type'] = 'CHIP';
            }
            if ($transaction_type == "CASHBONUS") {
                $options['where']['transaction_type'] = 'CASH';
                $options['where']['pay_type'] = 'BONUS';
            }
            if ($transaction_type == "CASHWITHDRAWAL") {
                $options['where']['transaction_type'] = 'CASH';
                $options['where']['pay_type'] = 'WITHDRAWAL';
            }
            if ($from_date) {
                $options['where']['DATE(datetime) >='] = $from_date;
            }
            if ($to_date) {
                $options['where']['DATE(datetime) <='] = $to_date;
            }
            if ($cash_type == 'DEBIT') {
                $options['where']['dr !='] = 0;
            }
            if ($cash_type == 'CREDIT') {
                $options['where']['cr !='] = 0;
            }
            $response = $this->common_model->customGet($options);
            $options1 = array('table' => 'transactions_history',
                'select' => "*",
                'where' => array('user_id' => $user_id)
            );
            if ($transaction_type == "CASH") {
                $options1['where']['transaction_type'] = 'CASH';
                // $options1['where']['pay_type !='] = 'BONUS';
            }
            if ($transaction_type == "REALCASH") {
                $options1['where']['transaction_type'] = 'CASH';
                $options1['where_in']['pay_type'] = array('JOINCONTEST', 'REFUND', 'DEPOSIT', 'WINNING');
            }
            if ($transaction_type == "CHIP") {
                $options1['where']['transaction_type'] = 'CHIP';
            }
            if ($transaction_type == "CASHBONUS") {
                $options1['where']['transaction_type'] = 'CASH';
                $options1['where']['pay_type'] = 'BONUS';
            }
            if ($transaction_type == "CASHWITHDRAWAL") {
                $options1['where']['transaction_type'] = 'CASH';
                $options1['where']['pay_type'] = 'WITHDRAWAL';
            }
            if ($from_date) {
                $options1['where']['DATE(datetime) >='] = $from_date;
            }
            if ($to_date) {
                $options1['where']['DATE(datetime) <='] = $to_date;
            }
            if ($cash_type == 'DEBIT') {
                $options1['where']['dr!='] = 0;
            }
            if ($cash_type == 'CREDIT') {
                $options1['where']['cr !='] = 0;
            }
            $total = $this->common_model->customCount($options1);
            if (!empty($response)) {
                $return['status'] = 1;
                $return['response'] = $response;
                $return['total'] = $total;
                //$return['has_next'] = $has_next;
                $return['message'] = 'Transactions successfully listed';
            } else {
                $return['status'] = 0;
                $return['message'] = 'Transactions not found';
            }
        }
        $this->response($return);
    }

    function transactions_app_post() {
        $data = $this->input->post();
        $return['code'] = 200;
        //$return['response'] = new stdClass();
        $this->form_validation->set_rules('login_session_key', 'Login session key', 'trim|required');
        //$this->form_validation->set_rules('transaction_type', 'transaction type', 'trim|required');

        if ($this->form_validation->run() == FALSE) {
            $error = $this->form_validation->rest_first_error_string();
            $return['status'] = 0;
            $return['message'] = $error;
        } else {
            $user_id = $this->user_details->id;
            $limit = extract_value($data, 'limit', '');
            $offset = extract_value($data, 'offset', '');
            $transArr = array();

            $sql = "SELECT user.team_code,trans.id,trans.sports_type,trans.orderId,trans.transaction_type,trans.pay_type,trans.bonus_type,trans.user_id,trans.match_id,trans.contest_id,trans.opening_balance,trans.dr,trans.cr,IF(trans.cr!= 0.00,trans.cr,trans.dr) as trans_amount,IF(trans.cr!= 0.00,'CREDIT','DEBIT') as amount_type,trans.available_balance,trans.message,trans.status,trans.datetime, trans.orderId as txn_id FROM  transactions_history as trans JOIN users as user ON user.id=trans.user_id where trans.user_id=$user_id order by trans.datetime DESC";
            /* $sql = "SELECT user.team_code,trans.id,trans.sports_type,trans.orderId,trans.transaction_type,trans.pay_type,trans.bonus_type,trans.user_id,trans.match_id,trans.contest_id,trans.opening_balance,trans.dr,trans.cr,IF(trans.cr!= 0.00,trans.cr,trans.dr) as trans_amount,IF(trans.cr!= 0.00,'CREDIT','DEBIT') as amount_type,trans.available_balance,trans.message,trans.status,trans.datetime, IF(payment.txnid IS NULL, trans.orderId, payment.txnid) as txn_id FROM  transactions_history as trans JOIN users as user ON user.id=trans.user_id left join payment ON payment.orderId= trans.orderId where trans.user_id=$user_id order by trans.datetime DESC"; */

            if (!empty($limit) && $offset >= 0) {
                $sql .= '  limit ' . $limit . ' offset ' . $offset . '';
            }
            $response = $this->common_model->customQuery($sql);


            $options1 = array('table' => 'transactions_history',
                'select' => "*",
                'where' => array('user_id' => $user_id)
            );

            $total = $this->common_model->customCount($options1);
            $contest_names = '';
            $contest_name = '';
            $team_name = '';

            if (!empty($response)) {
                foreach ($response as $res) {

                    if ($res->sports_type == 1) {
                        $sql = "SELECT CONCAT(localteam, ' Vs ', visitorteam) as team_name FROM `matches` WHERE match_id = $res->match_id ";
                        $team_name = $this->common_model->customQuery($sql);
                        if (!empty($team_name)) {
                            $team_name = $team_name[0]->team_name;
                        }
                        $sql = "SELECT fixture_contest_type FROM `contest` WHERE id = $res->contest_id ";
                        $contest_name = $this->common_model->customQuery($sql);
                        if (!empty($contest_name)) {
                            $contest_name = $contest_name[0]->fixture_contest_type;
                        }
                    }
                    if ($res->sports_type == 2) {
                        $sql = "SELECT CONCAT(localteam, ' Vs ', visitorteam) as team_name FROM `football_matches` WHERE match_id = $res->match_id ";
                        $team_name = $this->common_model->customQuery($sql);
                        if (!empty($team_name)) {
                            $team_name = $team_name[0]->team_name;
                        }
                        $sql = "SELECT fixture_contest_type FROM `football_contest` WHERE id = $res->contest_id ";
                        $contest_name = $this->common_model->customQuery($sql);
                        if (!empty($contest_name)) {
                            $contest_name = $contest_name[0]->fixture_contest_type;
                        }
                    }
                    if ($res->sports_type == 3) {
                        $sql = "SELECT CONCAT(localteam, ' Vs ', visitorteam) as team_name FROM `kabaddi_matches` WHERE match_id = '" . $res->match_id . "'";
                        $team_name = $this->common_model->customQuery($sql);
                        if (!empty($team_name)) {
                            $team_name = $team_name[0]->team_name;
                        }
                        $sql = "SELECT fixture_contest_type FROM `kabaddi_contest` WHERE id = $res->contest_id ";
                        $contest_name = $this->common_model->customQuery($sql);

                        if (!empty($contest_name)) {
                            $contest_name = $contest_name[0]->fixture_contest_type;
                        }
                    }

                    if ($res->contest_id) {
                        if (!$contest_name) {
                            $contest_names = 'User Created';
                        }
                    }
                    if ($contest_name == 1) {
                        $contest_names = 'Hot Contest';
                    }
                    if ($contest_name == 2) {
                        $contest_names = 'Contest for Champions';
                    }
                    if ($contest_name == 3) {
                        $contest_names = 'Head-to-Head';
                    }
                    if ($contest_name == 4) {
                        $contest_names = 'Winner takes All';
                    }
                    if ($contest_name == 5) {
                        $contest_names = 'More Contest';
                    }
                    if ($contest_name == 6) {
                        $contest_names = 'Practice Contest';
                    }
                    if ($contest_names) {
                        $message = null_checker($res->message) . " " . $team_name . " (" . $contest_names . ")";
                    } else {
                        $message = null_checker($res->message);
                    }

                    $temp['id'] = null_checker($res->id);
                    $temp['match_name'] = $team_name;
                    $temp['contest_name'] = $contest_names;
                    //$temp['orderId'] = null_checker($res->orderId);
                    $temp['transaction_type'] = null_checker($res->transaction_type);
                    $temp['pay_type'] = null_checker($res->pay_type);
                    $temp['transaction_amount_type'] = null_checker($res->amount_type);
                    $temp['game_type'] = null_checker($res->sports_type);
                    $temp['contest_id'] = null_checker($res->contest_id);
                    //$temp['bonus_type'] = null_checker($res->bonus_type);
                    $temp['user_id'] = null_checker($res->user_id);
                    //$temp['match_id'] = null_checker($res->match_id);
                    // $temp['contest_id'] = null_checker($res->contest_id);
                    //$temp['opening_balance'] = $res->opening_balance;
                    $temp['trans_amount'] = null_checker($res->trans_amount);
                    $temp['team_name'] = null_checker($res->team_code);
                    $temp['message'] = $message;
                    $temp['datetime'] = null_checker($res->datetime);
                    $temp['txn_id'] = null_checker($res->txn_id);

                    $transArr[] = $temp;
                }

                $return['status'] = 1;
                $return['response'] = $transArr;
                $return['total'] = $total;
                $return['message'] = 'Transactions successfully listed';
            } else {
                $return['status'] = 0;
                $return['message'] = 'Transactions not found';
            }
        }
        $this->response($return);
    }

    /*
     * Function Name: account_balance
     * Description:   To Get User wallet & chip account balance
     */

    function account_balance_old_post() {
        $data = $this->input->post();
        $return['code'] = 200;
        //$return['response'] = new stdClass();
        $this->form_validation->set_rules('login_session_key', 'Login session key', 'trim|required');
        if ($this->form_validation->run() == FALSE) {
            $error = $this->form_validation->rest_first_error_string();
            $return['status'] = 0;
            $return['message'] = $error;
        } else {
            /* Return Response */
            $response = array();
            $account = array();
            $walletData = array();
            $chipData = array();
            $usersArr = array();
            $user_id = $this->user_details->id;

            $options = array('table' => 'wallet',
                'where' => array('user_id' => $user_id),
                'single' => true);
            $wallet = $this->common_model->customGet($options);
            $walletData['deposited_amount'] = '0';
            $walletData['winning_amount'] = '0';
            $walletData['cash_bonus_amount'] = '0';
            $walletData['total_balance'] = '0';
            $walletData['amount_to_expire'] = '0';
            $walletData['bonus_to_expire'] = '0';

            if (!empty($wallet)) {
                $walletData['deposited_amount'] = (string) $wallet->deposited_amount;
                $walletData['winning_amount'] = (string) $wallet->winning_amount;
                $walletData['cash_bonus_amount'] = (string) $wallet->cash_bonus_amount;
                $walletData['total_balance'] = (string) $wallet->total_balance;
            }
            $account['cash'] = $walletData;
            $options = array('table' => 'user_chip',
                'where' => array('user_id' => $user_id),
                'single' => true);
            $user_chip = $this->common_model->customGet($options);
            $chipData['bonus_chip'] = '0';
            $chipData['winning_chip'] = '0';
            $chipData['total_chip'] = '0';
            if (!empty($user_chip)) {
                $chipData['bonus_chip'] = (string) $user_chip->bonus_chip;
                $chipData['winning_chip'] = (string) $user_chip->winning_chip;
                $chipData['total_chip'] = (string) $user_chip->chip;
            }
            $account['chip'] = $chipData;
            $account['verify_account'] = 'PENDING';
            $account['use_cash_bonus_limit'] = '10%';
            $account['withdrawal_amount_limit'] = '200';
            $account['minimum_withdraw_amount_limit'] = '200';

            $options = array('table' => 'user_bank_account_detail',
                'where' => array('user_id' => $user_id),
                'single' => true);
            $verifyAccount = $this->common_model->customGet($options);
            if (!empty($verifyAccount)) {
                if ($verifyAccount->verification_status == 2) {
                    $account['verify_account'] = 'VERIFIED';
                }
            }

            $option = array('table' => 'withdrawal_request as wd',
                'select' => 'wd.*',
                'join' => array('users as user' => 'user.id=wd.user_id',
                    'wallet' => 'wallet.user_id=user.id'),
                'where' => array('wd.user_id' => $user_id),
                'where_in' => array('wd.status' => array(1, 3)),
                'single' => true
            );
            $isWithwralReq = $this->common_model->customGet($option);
            $account['withdrawal_req'] = 0;
            if (!empty($isWithwralReq)) {
                $account['withdrawal_req'] = 1;
            }

            $option1 = array('table' => 'join_contest',
                'select' => 'contest_id,match_id',
                'where' => array('user_id' => $user_id),
            );
            $totalContest = $this->common_model->customCount($option1);

            $option1['group_by'] = 'match_id';

            $totalMatch = $this->common_model->customGet($option1);

            $option3 = array('table' => 'join_contest',
                'select' => 'join_contest.contest_id,join_contest.match_id',
                'join' => array('user_team as team' => 'team.id=join_contest.team_id'),
                'where' => array('join_contest.user_id' => $user_id),
                'group_by' => 'team.series_id'
            );
            $getSeries = $this->common_model->customGet($option3);

            $totalSeries = count($getSeries);


            $option2 = array('table' => 'user_team_rank',
                'select' => 'contest_id,match_id',
                'where' => array('user_id' => $user_id, 'winning_amount!=' => 0),
            );
            $totalWin = $this->common_model->customCount($option2);

            $playerDetail['total_contest'] = $totalContest;
            $playerDetail['total_matches'] = count($totalMatch);
            $playerDetail['series'] = $totalSeries;
            $playerDetail['total_wins'] = $totalWin;
            $account['playing_history'] = $playerDetail;

            $options = array(
                'table' => 'user_referrals as referral',
                'select' => 'referral.invite_user_id,user.team_code,user.profile_pic',
                'join' => array('users as user' => 'user.id=referral.invite_user_id'),
                'where' => array('referral.user_id' => $user_id)
            );
            $invitedUsers = $this->common_model->customGet($options);
            if (!empty($invitedUsers)) {
                foreach ($invitedUsers as $users) {
                    $image = base_url() . DEFAULT_USER_IMG_PATH;
                    if (!empty($users->profile_pic)) {
                        $image = base_url() . $users->profile_pic;
                    }
                    $temp['user_id'] = $users->invite_user_id;
                    $temp['team_code'] = $users->team_code;
                    $temp['user_image'] = $image;

                    $usersArr[] = $temp;
                }
            }

            $account['invited_friends_list'] = $usersArr;

            $account['transactions'] = array();
            $account['cards'] = array();
            $return['status'] = 1;
            $return['response'] = $account;
            $return['message'] = 'success';
        }
        $this->response($return);
    }

    function account_balance_post() {
        $data = $this->input->post();
        $return['code'] = 200;
        //$return['response'] = new stdClass();
        $this->form_validation->set_rules('login_session_key', 'Login session key', 'trim|required');
        if ($this->form_validation->run() == FALSE) {
            $error = $this->form_validation->rest_first_error_string();
            $return['status'] = 0;
            $return['message'] = $error;
        } else {
            /* Return Response */
            $response = array();
            $account = array();
            $walletData = array();
            $chipData = array();
            $usersArr = array();
            $user_id = $this->user_details->id;

            $options = array('table' => 'wallet',
                'where' => array('user_id' => $user_id),
                'single' => true);
            $wallet = $this->common_model->customGet($options);
            $walletData['deposited_amount'] = '0';
            $walletData['winning_amount'] = '0';
            $walletData['cash_bonus_amount'] = '0';
            $walletData['total_balance'] = '0';
            $walletData['amount_to_expire'] = '0';
            $walletData['bonus_to_expire'] = '0';

            if (!empty($wallet)) {
                $walletData['deposited_amount'] = (string) $wallet->deposited_amount;
                $walletData['winning_amount'] = (string) $wallet->winning_amount;
                $walletData['cash_bonus_amount'] = (string) $wallet->cash_bonus_amount;
                $walletData['total_balance'] = (string) $wallet->total_balance;
            }
            $account['cash'] = $walletData;
            $options = array('table' => 'user_chip',
                'where' => array('user_id' => $user_id),
                'single' => true);
            $user_chip = $this->common_model->customGet($options);
            $chipData['bonus_chip'] = '0';
            $chipData['winning_chip'] = '0';
            $chipData['total_chip'] = '0';
            if (!empty($user_chip)) {
                $chipData['bonus_chip'] = (string) $user_chip->bonus_chip;
                $chipData['winning_chip'] = (string) $user_chip->winning_chip;
                $chipData['total_chip'] = (string) $user_chip->chip;
            }
            $account['chip'] = $chipData;
            $account['verify_account'] = 'PENDING';
            $account['use_cash_bonus_limit'] = '10%';
            $account['withdrawal_amount_limit'] = '200';
            $account['minimum_withdraw_amount_limit'] = '200';

            $options = array('table' => 'user_bank_account_detail',
                'where' => array('user_id' => $user_id),
                'single' => true);
            $verifyAccount = $this->common_model->customGet($options);
            if (!empty($verifyAccount)) {
                if ($verifyAccount->verification_status == 2) {
                    $account['verify_account'] = 'VERIFIED';
                }
            }

            $option = array('table' => 'withdrawal_request as wd',
                'select' => 'wd.*',
                'join' => array('users as user' => 'user.id=wd.user_id',
                    'wallet' => 'wallet.user_id=user.id'),
                'where' => array('wd.user_id' => $user_id),
                'where_in' => array('wd.status' => array(1, 3)),
                'single' => true
            );
            $isWithwralReq = $this->common_model->customGet($option);
            $account['withdrawal_req'] = 0;
            if (!empty($isWithwralReq)) {
                $account['withdrawal_req'] = 1;
            }

            $sql = "SELECT COUNT(*) AS `total`
                    FROM `join_contest` WHERE `user_id` = '" . $user_id . "' UNION ALL SELECT COUNT(*) AS `total` FROM `football_join_contest` WHERE `user_id` = '" . $user_id . "' UNION ALL SELECT COUNT(*) AS `total` FROM `kabaddi_join_contest` WHERE `user_id` = '" . $user_id . "'
                    ";
            $totalCon = $this->common_model->customQuery($sql);

            $totalContest = 0;
            if (!empty($totalCon)) {
                $totalCricketContest = isset($totalCon[0]->total) ? $totalCon[0]->total : 0;
                $totalFootballContest = isset($totalCon[1]->total) ? $totalCon[1]->total : 0;
                $totalKabbadiContest = isset($totalCon[2]->total) ? $totalCon[2]->total : 0;

                $totalContest = $totalCricketContest + $totalFootballContest + $totalKabbadiContest;
            }

            $sql1 = "SELECT COUNT(*) AS `total_match`
                    FROM `join_contest` WHERE `user_id` = '" . $user_id . "' group by match_id UNION ALL SELECT COUNT(*) AS `total_match` FROM `football_join_contest` WHERE `user_id` = '" . $user_id . "' group by match_id UNION ALL SELECT COUNT(*) AS `total_match` FROM `kabaddi_join_contest` WHERE `user_id` = '" . $user_id . "' group by match_id 
                    ";
            $totalMat = $this->common_model->customQuery($sql1);
            $totalMatch = 0;
            if (!empty($totalMat)) {

                $totalMatch = count($totalMat);
            }

            $sql2 = "SELECT `join_contest`.`match_id`
                    FROM `join_contest`
                    JOIN `user_team` as `team` ON `team`.`id`=`join_contest`.`team_id`
                    WHERE `join_contest`.`user_id` = '" . $user_id . "' GROUP BY team.`series_id` UNION ALL SELECT `football_join_contest`.`match_id`
                    FROM `football_join_contest`
                    JOIN `football_user_team` as `team` ON `team`.`id`=`football_join_contest`.`team_id`
                    WHERE `football_join_contest`.`user_id` = '" . $user_id . "' GROUP BY team.`series_id` UNION ALL SELECT `kabaddi_join_contest`.`match_id`
                    FROM `kabaddi_join_contest`
                    JOIN `kabaddi_user_team` as `team` ON `team`.`id`=`kabaddi_join_contest`.`team_id`
                    WHERE `kabaddi_join_contest`.`user_id` = '" . $user_id . "'
                    GROUP BY team.`series_id`";
            $totalSer = $this->common_model->customQuery($sql2);

            $totalSeries = 0;
            if (!empty($totalSer)) {
                $totalSeries = count($totalSer);
            }

            $sql3 = "SELECT COUNT(*) AS `total_win`
                        FROM `cricket_match_contest_user_rank`
                        WHERE `user_id` = '" . $user_id . "' AND `winning_amount` != 0.00 UNION ALL SELECT COUNT(*) AS `total_win`
                        FROM `football_match_contest_user_rank`
                        WHERE `user_id` = '" . $user_id . "' AND `winning_amount` != 0.00 UNION ALL SELECT COUNT(*) AS `total_win`
                        FROM `kabaddi_match_contest_user_rank`
                        WHERE `user_id` = '" . $user_id . "'
                        AND `winning_amount` != 0.00";
            $totalWin = $this->common_model->customQuery($sql3);

            $totalWinning = 0;
            if (!empty($totalWin)) {
                $totalCricketWin = isset($totalWin[0]->total_win) ? $totalWin[0]->total_win : 0;
                $totalFootballWin = isset($totalWin[1]->total_win) ? $totalWin[1]->total_win : 0;
                $totalKabbadiWin = isset($totalWin[2]->total_win) ? $totalWin[2]->total_win : 0;

                $totalWinning = $totalCricketWin + $totalFootballWin + $totalKabbadiWin;
            }

            $sql4 = "SELECT SUM(winning_amount) AS `total_cash`
                        FROM `cricket_match_contest_user_rank`
                        WHERE `user_id` = '" . $user_id . "' AND `winning_amount` != 0.00 UNION ALL SELECT SUM(winning_amount) AS `total_cash`
                        FROM `football_match_contest_user_rank`
                        WHERE `user_id` = '" . $user_id . "' AND `winning_amount` != 0.00 UNION ALL
                        SELECT SUM(winning_amount) AS `total_cash`
                        FROM `kabaddi_match_contest_user_rank`
                        WHERE `user_id` = '" . $user_id . "' AND `winning_amount` != 0.00";
            $totalCashWon = $this->common_model->customQuery($sql4);
            $totalCashWinning = 0;
            if (!empty($totalCashWon)) {
                $totalCricketCashWin = isset($totalCashWon[0]->total_cash) ? $totalCashWon[0]->total_cash : 0;
                $totalFootballCashWin = isset($totalCashWon[1]->total_cash) ? $totalCashWon[1]->total_cash : 0;
                $totalKabbadiCashWin = isset($totalCashWon[2]->total_cash) ? $totalCashWon[2]->total_cash : 0;

                $totalCashWinning = $totalCricketCashWin + $totalFootballCashWin + $totalKabbadiCashWin;
            }


            $playerDetail['total_contest'] = $totalContest;
            $playerDetail['total_matches'] = $totalMatch;
            $playerDetail['series'] = $totalSeries;
            $playerDetail['total_wins'] = $totalWinning;
            $playerDetail['total_cash_wins'] = $totalCashWinning;
            $account['playing_history'] = $playerDetail;

            $options = array(
                'table' => 'user_referrals as referral',
                'select' => 'referral.invite_user_id,user.team_code,user.profile_pic',
                'join' => array('users as user' => 'user.id=referral.invite_user_id'),
                'where' => array('referral.user_id' => $user_id),
                'limit' => 20
            );
            $invitedUsers = $this->common_model->customGet($options);
            if (!empty($invitedUsers)) {
                foreach ($invitedUsers as $users) {
                    $image = base_url() . DEFAULT_USER_IMG_PATH;
                    if (!empty($users->profile_pic)) {
                        $image = base_url() . $users->profile_pic;
                    }
                    $temp['user_id'] = $users->invite_user_id;
                    $temp['team_code'] = $users->team_code;
                    $temp['user_image'] = $image;

                    $usersArr[] = $temp;
                }
            }

            $account['invited_friends_list'] = $usersArr;

            $account['transactions'] = array();
            $account['cards'] = array();
            $return['status'] = 1;
            $return['response'] = $account;
            $return['message'] = 'success';
        }
        $this->response($return);
    }

    function invited_friends_history_post() {
        $data = $this->input->post();
        $return['code'] = 200;

        $this->form_validation->set_rules('login_session_key', 'Login session key', 'trim|required');
        if ($this->form_validation->run() == FALSE) {
            $error = $this->form_validation->rest_first_error_string();
            $return['status'] = 0;
            $return['message'] = $error;
        } else {
            $user_id = $this->user_details->id;
            $usersArr = array();

            $options = array(
                'table' => 'user_referrals as referral',
                'select' => 'referral.invite_user_id,user.team_code,user.profile_pic,referral.referral_total_cash_bonus',
                'join' => array('users as user' => 'user.id=referral.invite_user_id'),
                'where' => array('referral.user_id' => $user_id)
            );
            $usersData = $this->common_model->customGet($options);
            if (!empty($usersData)) {
                foreach ($usersData as $users) {
                    $image = base_url() . DEFAULT_USER_IMG_PATH;
                    if (!empty($users->profile_pic)) {
                        $image = base_url() . $users->profile_pic;
                    }
                    $temp['user_id'] = $users->invite_user_id;
                    $temp['team_code'] = $users->team_code;
                    $temp['bonus_amount'] = 100;
                    $temp['received_amount'] = (int) $users->referral_total_cash_bonus;
                    $temp['user_image'] = $image;

                    $usersArr[] = $temp;
                }

                $sql = "SELECT sum(referral_total_cash_bonus) as total_received FROM `user_referrals` WHERE user_id = $user_id";
                $received = $this->common_model->customQuery($sql);

                $response['invited_friends'] = $usersArr;
                $total_friends_joined = count($usersData);
                $total_bonus_amount = 100 * $total_friends_joined;
                $total_received_amount = $received[0]->total_received;

                $response['total_friends_joined'] = $total_friends_joined;
                $response['total_bonus_amount'] = $total_bonus_amount;
                $response['total_received_amount'] = (int) $total_received_amount;
                $response['earning_through_friends'] = (int) $total_received_amount;
                $response['to_be_earned'] = round($total_bonus_amount - $total_received_amount);

                $return['status'] = 1;
                $return['response'] = $response;
                $return['message'] = 'Data Found Successfully';
            } else {
                $return['status'] = 0;
                $return['message'] = 'Data Not Found';
            }
        }
        $this->response($return);
    }

    /**
     * Function Name: point system
     * Description:   To get point ststem
     */
    function point_system_post_old() {
        $return['code'] = 200;
        $return['response'] = new stdClass();
        $pointsInput = fantasyPointInput();
        $main = $pointsInput['main'];
        $bonus = $pointsInput['bonus'];
        $economy_rate = $pointsInput['economy_rate'];
        $strike_rate = $pointsInput['strike_rate'];
        $response = array();
        foreach ($main as $key => $rows) {
            $name = $main[$key];
            $temp["t20"] = getConfig($key . "_t20");

            $temp["odi"] = getConfig($key . "_odi");
            $temp["test"] = getConfig($key . "_test");
            $response['main'][$name] = $temp;
        }
        foreach ($bonus as $key => $rows) {
            $name = $bonus[$key];
            $temp["t20"] = getConfig($key . "_t20");
            $temp["odi"] = getConfig($key . "_odi");
            $temp["test"] = getConfig($key . "_test");
            $response['bonus'][$name] = $temp;
        }
        foreach ($economy_rate as $key => $rows) {
            $name = $economy_rate[$key];
            $temp["t20"] = getConfig($key . "_t20");
            $temp["odi"] = getConfig($key . "_odi");
            $temp["test"] = getConfig($key . "_test");
            $response['economy_rate'][$name] = $temp;
        }
        foreach ($strike_rate as $key => $rows) {
            $name = $strike_rate[$key];
            $temp["t20"] = getConfig($key . "_t20");
            $temp["odi"] = getConfig($key . "_odi");
            $temp["test"] = getConfig($key . "_test");
            $response['strike_rate'][$name] = $temp;
        }
        if (empty($response)) {
            $return['status'] = 0;
            $return['message'] = 'Point System not found';
        } else {
            $return['response'] = $response;
            $return['status'] = 1;
            $return['message'] = 'Point System found successfully';
        }

        $this->response($return);
    }

    function point_system_new_old_post() {
        $return['code'] = 200;
        $return['response'] = new stdClass();
        $pointsInput = fantasyPointInput();
        $main = $pointsInput['main'];
        $bonus = $pointsInput['bonus'];
        $economy_rate = $pointsInput['economy_rate'];
        $strike_rate = $pointsInput['strike_rate'];
        $response = array();

        foreach ($main as $key => $rows) {
            $name = $main[$key];

            $temp_t20 = getConfig_point($key . "_t20");
            $temp4 = array();
            if ($temp_t20) {
                $temp4["t20"] = $temp_t20;
            }
            $temp_odi1 = getConfig_point($key . "_odi");
            if ($temp_odi1) {
                $temp4["odi"] = $temp_odi1;
            }
            $temp_test1 = getConfig_point($key . "_test");
            if ($temp_test1) {

                $temp4["test"] = $temp_test1;
            }
            $response['main'][$name] = $temp4;
        }

        foreach ($bonus as $key => $rows) {
            $name = $bonus[$key];

            $temp_t20 = getConfig_point($key . "_t20");
            $temp3 = array();
            if ($temp_t20) {

                $temp3["t20"] = $temp_t20;
            }
            $temp_odi1 = getConfig_point($key . "_odi");
            if ($temp_odi1) {

                $temp3["odi"] = $temp_odi1;
            }
            $temp_test1 = getConfig_point($key . "_test");
            if ($temp_test1) {

                $temp3["test"] = $temp_test1;
            }
            $response['bonus'][$name] = $temp3;
        }

        foreach ($economy_rate as $key => $rows) {
            $name = $economy_rate[$key];
            $temp_t20 = getConfig_point($key . "_t20");
            $temp2 = array();
            if ($temp_t20) {
                $temp2["t20"] = $temp_t20;
            }
            $temp_odi1 = getConfig_point($key . "_odi");
            if ($temp_odi1) {
                $temp2["odi"] = $temp_odi1;
            }
            $temp_test1 = getConfig_point($key . "_test");
            if ($temp_test1) {

                $temp2["test"] = $temp_test1;
            }
            $response['economy_rate'][$name] = $temp2;
        }

        foreach ($strike_rate as $key => $rows) {
            $name = $strike_rate[$key];
            $temp_t20 = getConfig_point($key . "_t20");
            $temp1 = array();
            if ($temp_t20) {
                $temp1["t20"] = $temp_t20;
            }
            $temp_odi1 = getConfig_point($key . "_odi");
            if ($temp_odi1) {
                $temp1["odi"] = $temp_odi1;
            }
            $temp_test1 = getConfig_point($key . "_test");
            if ($temp_test1) {

                $temp1["test"] = $temp_test1;
            }
            $response['strike_rate'][$name] = $temp1;
            //echo gettype($temp_t20).'<br>';
        }
        // print_r($bonus);
        // print_r($response['bonus']);
        // print_r($response['strike_rate']);
        //die;
        if (empty($response)) {
            $return['status'] = 0;
            $return['message'] = 'Point System not found';
        } else {
            $return['response'] = $response;
            $return['status'] = 1;
            $return['message'] = 'Point System found successfully';
        }

        $this->response($return);
    }

    function point_system_post() {
        $return['code'] = 200;
        //$return['response'] = new stdClass();
        $pointsInput = fantasyPointInput();
        $main = $pointsInput['main'];
        $bonus = $pointsInput['bonus'];
        $economy_rate = $pointsInput['economy_rate'];
        $strike_rate = $pointsInput['strike_rate'];
        $response = array();
        $t20 = array();
        foreach ($pointsInput as $key => $rows) {

            foreach ($pointsInput[$key] as $pointKey => $rowsKey) {

                $temp4 = "";
                $temp5 = "";
                $temp6 = "";

                $temp_t20 = getConfig_point($pointKey . "_t20");


                if ($temp_t20) {
                    $temp4 = $temp_t20;
                }

                $temp_odi1 = getConfig_point($pointKey . "_odi");
                if ($temp_odi1) {
                    $temp5 = $temp_odi1;
                }
                $temp_test1 = getConfig_point($pointKey . "_test");
                if ($temp_test1) {

                    $temp6 = $temp_test1;
                }

                /*   Response for t20  */
                if ($pointKey == 'run' || $pointKey == 'every_six' || $pointKey == 'half_century' || $pointKey == 'century' || $pointKey == 'duck' || $pointKey == 'every_fours') {

                    //print_r($temp4);die;
                    $response1['batting'][$pointKey] = $temp4;
                }

                if ($pointKey == 'wicket_run_out' || $pointKey == 'four_wickets' || $pointKey == 'five_wickets' || $pointKey == 'maiden_over') {
                    $response1['bowling'][$pointKey] = $temp4;
                }

                if ($pointKey == 'catch' || $pointKey == 'stumping_run_out_direct' || $pointKey == 'run_out_thrower' || $pointKey == 'run_out_catcher') {
                    $response1['fielding'][$pointKey] = $temp4;
                }

                if ($pointKey == 'captain' || $pointKey == 'vice_captain' || $pointKey == 'starting_xi') {
                    $response1['others'][$pointKey] = $temp4;
                }

                if ($pointKey == 'below_4_runs_per_over' || $pointKey == 'between_4_and_499_runs_per_over' || $pointKey == 'between_5_and_6_runs_per_over' || $pointKey == 'between_9_and_10_runs_per_over' || $pointKey == 'between_10_1_and_11_runs_per_over' || $pointKey == 'above_11_runs_per_over' || $pointKey == 'applicable_for_players_bowling_minimum_overs') {

                    $response1['economy_rate'][$pointKey] = $temp4;
                }

                if ($pointKey == 'between_60_and_70_runs_per_100_balls' || $pointKey == 'Between_50_and_59_9_runs_per_100_balls' || $pointKey == 'below_50_runs_per_100_balls' || $pointKey == 'applicable_for_players_batting_minimum_balls') {

                    $response1['strike_rate'][$pointKey] = $temp4;
                }

                /*   Response for odi  */

                if ($pointKey == 'run' || $pointKey == 'every_six' || $pointKey == 'half_century' || $pointKey == 'century' || $pointKey == 'duck' || $pointKey == 'every_fours') {

                    $response2['batting'][$pointKey] = $temp5;
                }

                if ($pointKey == 'wicket_run_out' || $pointKey == 'four_wickets' || $pointKey == 'five_wickets' || $pointKey == 'maiden_over') {
                    $response2['bowling'][$pointKey] = $temp5;
                }

                if ($pointKey == 'catch' || $pointKey == 'stumping_run_out_direct' || $pointKey == 'run_out_thrower' || $pointKey == 'run_out_catcher') {
                    $response2['fielding'][$pointKey] = $temp5;
                }

                if ($pointKey == 'captain' || $pointKey == 'vice_captain' || $pointKey == 'starting_xi') {
                    $response2['others'][$pointKey] = $temp5;
                }

                if ($pointKey == 'below_2_5_runs_per_over' || $pointKey == 'between_2_5_and_3_49_runs_per_over' || $pointKey == 'between_3_5_and_4_5_runs_per_over' || $pointKey == 'between_7_and_8_runs_per_over' || $pointKey == 'between_8_1_and_9_runs_per_over' || $pointKey == 'above_9_runs_per_over' || $pointKey == 'applicable_for_players_bowling_minimum_overs') {

                    $response2['economy_rate'][$pointKey] = $temp5;
                }

                if ($pointKey == 'between_50_and_60_runs_per_100_balls' || $pointKey == 'between_40_and_49_99_runs_per_100_balls' || $pointKey == 'below_40_runs_per_100_balls' || $pointKey == 'applicable_for_players_batting_minimum_balls') {

                    $response2['strike_rate'][$pointKey] = $temp5;
                }

                /*   Response for test  */

                if ($pointKey == 'run' || $pointKey == 'every_six' || $pointKey == 'half_century' || $pointKey == 'century' || $pointKey == 'duck' || $pointKey == 'every_fours') {

                    $response3['batting'][$pointKey] = $temp6;
                }

                if ($pointKey == 'wicket_run_out' || $pointKey == 'four_wickets' || $pointKey == 'five_wickets' || $pointKey == 'maiden_over') {
                    $response3['bowling'][$pointKey] = $temp6;
                }

                if ($pointKey == 'catch' || $pointKey == 'stumping_run_out_direct' || $pointKey == 'run_out_thrower' || $pointKey == 'run_out_catcher') {
                    $response3['fielding'][$pointKey] = $temp6;
                }

                if ($pointKey == 'captain' || $pointKey == 'vice_captain' || $pointKey == 'starting_xi') {
                    $response3['others'][$pointKey] = $temp6;
                }
            }
        }
        $response['t20'] = $response1;
        $response['odi'] = $response2;
        $response['test'] = $response3;

        if (empty($response)) {
            $return['status'] = 0;
            $return['message'] = 'Point System not found';
        } else {
            $return['response'] = $response;
            $return['status'] = 1;
            $return['message'] = 'Point System found successfully';
        }

        $this->response($return);
    }

    /**
     * Function Name: clear_badges
     * Description:   To Clear Notification Badges
     */
    function clear_badges_post() {
        $return['code'] = 200;
        //$return['response'] = new stdClass();
        $data = $this->input->post();
        $this->form_validation->set_rules('login_session_key', 'Login Session Key', 'trim|required');
        if ($this->form_validation->run() == FALSE) {
            $error = $this->form_validation->rest_first_error_string();
            $return['status'] = 0;
            $return['message'] = $error;
        } else {
            /* Update user badges */
            $this->common_model->updateFields(USERS, array('badges' => 0), array('id' => $this->user_details->id));

            $return['status'] = 1;
            $return['message'] = 'Badges cleared successfully';
        }
        $this->response($return);
    }

    /**
     * Function Name: get_badges
     * Description:   To Get Notification Badges
     */
    function get_badges_post() {
        $return['code'] = 200;
        //$return['response'] = new stdClass();
        $data = $this->input->post();
        $this->form_validation->set_rules('login_session_key', 'Login Session Key', 'trim|required');
        if ($this->form_validation->run() == FALSE) {
            $error = $this->form_validation->rest_first_error_string();
            $return['status'] = 0;
            $return['message'] = $error;
        } else {
            /* To get user badges */
            $badges = $this->common_model->getsingle(USERS, array('id' => $this->user_details->id));
            if (!empty($badges)) {
                $return['response'] = array('badges' => (int) null_checker($badges->badges));
            } else {
                $return['response'] = array('badges' => 0);
            }
            $return['status'] = 1;
            $return['message'] = 'Success';
        }
        $this->response($return);
    }

    /**
     * Function Name: forgot_password
     * Description:   To User Forgot Password
     */
    function forgot_password_post_OLD() {
        $return['code'] = 200;
        //$return['response'] = new stdClass();
        $data = $this->input->post();
        $this->form_validation->set_rules('email', 'Email Id', 'trim|required|valid_email');
        if ($this->form_validation->run() == FALSE) {
            $error = $this->form_validation->rest_first_error_string();
            $return['status'] = 0;
            $return['message'] = $error;
        } else {
            $dataArr = array();
            $dataArr['email'] = extract_value($data, 'email', '');

            /* Get User Data From Users Table */
            $result = $this->common_model->getsingle(USERS, $dataArr);
            if (empty($result)) {
                $return['status'] = 0;
                $return['message'] = 'Email-id does not exist';
            } else {

                $identity_column = $this->config->item('identity', 'ion_auth');
                $identity = $this->ion_auth->where($identity_column, $this->input->post('email'))->users()->row();

                if (empty($identity)) {

                    if ($this->config->item('identity', 'ion_auth') != 'email') {
                        $error = "No record of that email address";
                    } else {
                        $error = "No record of that email address";
                    }
                    $return['status'] = 0;
                    $return['message'] = $error;
                    $this->response($return);
                    exit;
                }


                $forgotten = $this->ion_auth->forgotten_password_app($identity->{$this->config->item('identity', 'ion_auth')});

                if ($forgotten) {

                    $return['status'] = 1;
                    $return['message'] = strip_tags($this->ion_auth->messages());
                } else {
                    $return['status'] = 0;
                    $return['message'] = strip_tags($this->ion_auth->errors());
                }
            }
        }
        $this->response($return);
    }

    /**
     * Function Name: page
     * Description:   To get pages
     */
    function page_post() {
        $return['code'] = 200;
        //$return['response'] = new stdClass();
        $data = $this->input->post();
        $this->form_validation->set_rules('page_id', 'Page Id', 'trim|required|in_list[ABOUT,CONTACT,TERMS_CONDITION,FAQS,PRIVACY_POLICY,HOW_TO_PLAY,LEGALITY]');
        if ($this->form_validation->run() == FALSE) {
            $error = $this->form_validation->rest_first_error_string();
            $return['status'] = 0;
            $return['message'] = $error;
        } else {
            $page_id = extract_value($data, 'page_id', '');
            $content = $this->common_model->getsingle('cms', array('page_id' => $page_id));
            if (empty($content)) {
                $return['status'] = 0;
                $return['message'] = 'Page not found';
            } else {

                $thumbnail_image = "";
                if (!empty($content->thumbnail_image)) {
                    $thumbnail_image = base_url() . "uploads/cms/" . $content->thumbnail_image;
                }
                $temp['cms_id'] = $content->cms_id;
                $temp['page_id'] = $content->page_id;
                $temp['description'] = $content->description;
                $temp['thumbnail_image'] = $thumbnail_image;
                $temp['active'] = $content->active;
                $temp['delete_status'] = $content->delete_status;
                $temp['create_date'] = $content->create_date;


                $return['response'] = $temp;
                $return['status'] = 1;
                $return['message'] = 'Page found successfully';
            }
        }
        $this->response($return);
    }

    /**
     * Function Name: deactivate_account
     * Description:   To Deactivate User Account
     */
    function deactivate_account_post() {
        $data = $this->input->post();
        $return['code'] = 200;
        //$return['response'] = new stdClass();
        $this->form_validation->set_rules('login_session_key', 'Login session key', 'trim|required');
        if ($this->form_validation->run() == FALSE) {
            $error = $this->form_validation->rest_first_error_string();
            $return['status'] = 0;
            $return['message'] = $error;
        } else {
            if ($this->user_details->active == 0) {
                $return['status'] = 0;
                $return['message'] = 'Account already deactivated';
            } else if ($this->user_details->active == 1) {
                /* Update User Details */
                $status = $this->common_model->updateFields(USERS, array('is_deactivated' => 1), array('id' => $this->user_details->id));
                if ($status) {
                    $return['status'] = 1;
                    $return['message'] = 'Account deactivated successfully';
                } else {
                    $is_error = db_err_msg();
                    $return['status'] = 0;
                    if ($is_error == FALSE) {
                        $return['message'] = NO_CHANGES;
                    } else {
                        $return['message'] = $is_error;
                    }
                }
            }
        }
        $this->response($return);
    }

    /**
     * Function Name: invite_friend
     * Description:   To invite friend
     */
    function invite_friend_post() {
        $return['code'] = 200;
        //$return['response'] = new stdClass();
        $data = $this->input->post();
        $this->form_validation->set_rules('login_session_key', 'Login session key', 'trim|required|callback__validate_login_session_key');
        if ($this->form_validation->run() == FALSE) {
            $error = $this->form_validation->rest_first_error_string();
            $return['status'] = 0;
            $return['message'] = $error;
        } else {
            $userInviteCode = $this->user_details->team_code;
            $base_url = base_url() . '#/?referral=' . $userInviteCode;
            $siteTitle = getConfig('site_name');
            $mobile = extract_value($data, 'mobile', '');
            $email = extract_value($data, 'email', '');
            if (empty($email) && empty($mobile)) {
                $return['status'] = 0;
                $return['message'] = 'Email or Mobile field is required and (at least) one of these needs to be filled';
            } else {
                $flag = false;
                if (!empty($mobile)) {
                    $postfields = array('mobile' => $mobile,
                        'message' => "Here is Rs. 100 to play Fantasy Sports with me on Fantasy11. Click on this link '" . $base_url . "' and use my code $userInviteCode to register.",
                    );
                    if ($this->smsSend($postfields)) {
                        $flag = true;
                    } else {
                        $flag = false;
                    }
                }
                if (!empty($email)) {
                    $base_url = base_url() . '#/?referral=' . $userInviteCode;
                    $html = array();
                    $html['logo'] = base_url() . getConfig('site_logo');
                    $html['site'] = getConfig('site_name');

                    $message = "Here is Rs. 100 to play Fantasy Sports with me on Fantasy11. Click on this link '" . $base_url . "' and use my code $userInviteCode to register.";

                    $html['message'] = $message;
                    $email_template = $this->load->view('email/email_user_invite_tpl', $html, true);
                    $status = send_mail($email_template, '[' . getConfig('site_name') . '] Invite', $email, getConfig('admin_email'));
                    if ($status) {
                        $flag = true;
                    } else {
                        $flag = false;
                    }
                }
                if ($flag) {
                    $return['status'] = 1;
                    $return['message'] = 'Successfully invited';
                } else {
                    $return['status'] = 0;
                    $return['message'] = 'Failed to invite please try again.';
                }
            }
        }
        $this->response($return);
    }

    /**
     * Function Name: use_invite_code
     * Description:   To invite code use by friend
     */
    function use_invite_code_post() {
        $return['code'] = 200;
        //$return['response'] = new stdClass();
        $data = $this->input->post();
        $this->form_validation->set_rules('login_session_key', 'Login session key', 'trim|required|callback__validate_login_session_key');
        $this->form_validation->set_rules('user_invite_code', 'Invite Code', 'trim|required');
        if ($this->form_validation->run() == FALSE) {
            $error = $this->form_validation->rest_first_error_string();
            $return['status'] = 0;
            $return['message'] = $error;
        } else {
            $user_id = $this->user_details->id;
            $user_invite_code = extract_value($data, 'user_invite_code', '');
            $option = array(
                'table' => 'users',
                'select' => 'id',
                'where' => array('team_code' => $user_invite_code),
                'single' => true
            );
            $is_available_code = $this->common_model->customGet($option);
            if (empty($is_available_code)) {
                $return['status'] = 0;
                $return['message'] = 'Invite code invalid,Please check again';
            } else {
                if ($is_available_code->id != $user_id) {
                    $option = array(
                        'table' => 'user_referrals',
                        'where' => array('user_id' => $is_available_code->id,
                            'invite_user_id' => $user_id),
                    );
                    $is_invited = $this->common_model->customGet($option);
                    if (empty($is_invited)) {
                        $option = array(
                            'table' => 'user_referrals',
                            'data' => array('user_id' => $is_available_code->id,
                                'invite_user_id' => $user_id),
                        );
                        $invite = $this->common_model->customInsert($option);
                        //referralSchemeCashBonus($user_id);
                        if ($invite) {
                            $return['status'] = 1;
                            $return['message'] = 'Successfully use code';
                        } else {
                            $return['status'] = 0;
                            $return['message'] = 'Failed to use code please try again.';
                        }
                    } else {
                        $return['status'] = 0;
                        $return['message'] = 'Invite code already used by current user';
                    }
                } else {
                    $return['status'] = 0;
                    $return['message'] = 'Invite code same as current user code';
                }
            }
        }
        $this->response($return);
    }

    function banners_list_post() {
        $return['code'] = 200;
        $data = $this->input->post();

        $this->form_validation->set_rules('module_type', 'Module Type', 'trim|required');
        if ($this->form_validation->run() == FALSE) {
            $error = $this->form_validation->rest_first_error_string();
            $return['status'] = 0;
            $return['message'] = $error;
        } else {

            $module_type = extract_value($data, 'module_type', '');
            $login_session_key = extract_value($data, 'login_session_key', '');

            if (!empty($login_session_key)) {
                $user_id = $this->user_details->id;
            } else {
                $user_id = 0;
            }

            $option = array(
                'table' => 'users',
                'select' => 'badges',
                'where' => array('id' => $user_id),
                'single' => true
            );
            $getUsers = $this->common_model->customGet($option);
            $user_badges = "0";
            if (!empty($getUsers)) {
                $user_badges = $getUsers->badges;
            }


            $options = array('table' => 'banner',
                'select' => "*",
                'where' => array('status' => 1, 'banner_type' => 'APP', 'delete_status' => 0, 'banner_module' => $module_type),
                'order' => array('id' => 'DESC')
            );
            $response = $this->common_model->customGet($options);
            if (!empty($response)) {
                $bannerArr = array();
                foreach ($response as $res) {
                    $banner_image = base_url() . DEFAULT_NO_IMG_PATH;
                    if (!empty($res->image)) {
                        $banner_image = base_url() . 'uploads/banner/' . $res->image;
                    }
                    $temp['id'] = $res->id;
                    $temp['banner_name'] = $res->banner_name;
                    $temp['url'] = $res->url;
                    $temp['banner_image'] = $banner_image;
                    $bannerArr[] = $temp;
                }
                $return['status'] = 1;
                $return['user_badges'] = $user_badges;
                $return['response'] = $bannerArr;
                $return['message'] = 'Banners successfully listed';
            } else {
                $return['status'] = 0;
                $return['user_badges'] = $user_badges;
                $return['message'] = 'Banners not found';
            }
        }

        $this->response($return);
    }

    function advertisement_banners_list_post() {
        $data = $this->input->post();
        $return['code'] = 200;

        $this->form_validation->set_rules('banner_type', 'Banner Type', 'trim|required');
        if ($this->form_validation->run() == FALSE) {
            $error = $this->form_validation->rest_first_error_string();
            $return['status'] = 0;
            $return['message'] = $error;
        } else {

            $banner_type = extract_value($data, 'banner_type', '');

            $options = array('table' => 'banner',
                'select' => "*",
                'where' => array('status' => 1, 'delete_status' => 0),
                'order' => array('id' => 'DESC')
            );

            if ($banner_type == 'WEB_ADVERTISEMENT') {
                $options['where']['banner_type'] = 'WEB_ADVERTISEMENT';
            }
            if ($banner_type == 'WEB_SLIDER') {
                $options['where']['banner_type'] = 'WEB_SLIDER';
            }

            $response = $this->common_model->customGet($options);
            if (!empty($response)) {
                $bannerArr = array();
                foreach ($response as $res) {
                    $banner_image = base_url() . DEFAULT_NO_IMG_PATH;
                    if (!empty($res->image)) {
                        $banner_image = base_url() . 'uploads/banner/' . $res->image;
                    }

                    $temp['id'] = null_checker($res->id);
                    $temp['banner_name'] = null_checker($res->banner_name);
                    $temp['banner_text'] = null_checker($res->banner_text);
                    $temp['url'] = null_checker($res->url);
                    $temp['banner_image'] = null_checker($banner_image);
                    $bannerArr[] = $temp;
                }
                $return['status'] = 1;
                $return['response'] = $bannerArr;
                $return['message'] = 'Banners successfully listed';
            } else {
                $return['status'] = 0;
                $return['message'] = 'Banners not found';
            }
        }
        $this->response($return);
    }

    /**
     * Function Name: career
     * Description:   To career request
     */
    function career_post() {

        $return['code'] = 200;
        //$return['response'] = new stdClass();
        $data = $this->input->post();

        // $this->form_validation->set_rules('login_session_key', 'Login session key', 'trim|required|callback__validate_login_session_key');
        // $this->form_validation->set_rules('user_id', 'User Id', 'trim|required|numeric');
        $this->form_validation->set_rules('full_name', 'Full Name', 'trim|required');
        $this->form_validation->set_rules('email', 'Email', 'required|trim');
        $this->form_validation->set_rules('phone', 'Phone', 'required|trim|numeric');
        $this->form_validation->set_rules('subject', 'Subject', 'required|trim');
        $this->form_validation->set_rules('message', 'Message', 'required|trim');

        if ($this->form_validation->run() == FALSE) {
            $error = $this->form_validation->rest_first_error_string();
            $return['status'] = 0;
            $return['message'] = $error;
        } else {
            $dataArr = array();

            $dataArr['full_name'] = extract_value($data, 'full_name', '');
            $dataArr['email'] = extract_value($data, 'email', '');
            $dataArr['phone'] = extract_value($data, 'phone', '');
            $dataArr['subject'] = extract_value($data, 'subject', '');
            $dataArr['message'] = extract_value($data, 'message', '');
            //$dataArr['user_id'] = extract_value($data, 'user_id', '');
            $dataArr['created_date'] = date('Y-m-d H:i:s');

            if (!empty($_FILES['image']['name'])) {
                $image = fileUpload('image', 'users', 'png|jpg|jpeg|gif|pdf');
                if (isset($image['error']) && !empty($image['error'])) {

                    $return['message'] = strip_tags($image['error']);
                    $return['status'] = 0;
                    $this->response($return);
                    exit;
                }
                $dataArr['image'] = 'uploads/users/' . $image['upload_data']['file_name'];
            }
            $option = array(
                'table' => 'career',
                'data' => $dataArr
            );
            $career_data = $this->common_model->customInsert($option);
            if ($career_data) {
                $return['status'] = 1;
                $return['message'] = 'Information saved successfully';
            } else {

                $is_error = db_err_msg();
                if ($is_error == FALSE) {
                    $return['status'] = 1;
                    $return['message'] = 'Information saved successfully';
                } else {
                    $return['status'] = 0;
                    $return['message'] = $is_error;
                }
            }
        }

        $this->response($return);
    }

    /**
     * Function Name: testimonial
     * Description:   To testimonial request
     */
    function testimonial_post() {
        $return['code'] = 200;
        $data = $this->input->post();
        $this->form_validation->set_rules('login_session_key', 'Login session key', 'trim|required|callback__validate_login_session_key');
        $this->form_validation->set_rules('user_id', 'User Id', 'trim|required|numeric');
        $this->form_validation->set_rules('rating', 'Rating', 'required|trim');
        $this->form_validation->set_rules('description', 'Description', 'required|trim');
        if ($this->form_validation->run() == FALSE) {
            $error = $this->form_validation->rest_first_error_string();
            $return['status'] = 0;
            $return['message'] = $error;
        } else {
            $dataArr = array();
            $dataArr['rating'] = $rating = extract_value($data, 'rating', '');
            $dataArr['description'] = extract_value($data, 'description', '');
            $dataArr['user_id'] = extract_value($data, 'user_id', '');
            $dataArr['created_date'] = date('Y-m-d H:i:s');
            if ($rating >= 1 && $rating <= 5) {
                $option = array(
                    'table' => 'testimonial',
                    'data' => $dataArr
                );
                $test_data = $this->common_model->customInsert($option);
                if ($test_data) {
                    $return['status'] = 1;
                    $return['message'] = 'Testimonial submitted successfully';
                } else {
                    $is_error = db_err_msg();
                    if ($is_error == FALSE) {
                        $return['status'] = 1;
                        $return['message'] = 'Testimonial submitted successfully';
                    } else {
                        $return['status'] = 0;
                        $return['message'] = $is_error;
                    }
                }
            } else {
                $return['status'] = 0;
                $return['message'] = "Please give rating";
            }
        }
        $this->response($return);
    }

    /**
     * Function Name: front_page
     * Description:  Front Pages Api
     */
    function front_page_post() {

        $return['code'] = 200;
        $data = $this->input->post();
        $this->form_validation->set_rules('page_type', 'Page Type', 'trim|required');

        if ($this->form_validation->run() == FALSE) {
            $error = $this->form_validation->rest_first_error_string();
            $return['status'] = 0;
            $return['message'] = $error;
        } else {
            $dataArr = array();
            $page_type = extract_value($data, 'page_type', '');
            $game_type = extract_value($data, 'game_type', '');
            $our_partners_title = "";
            $our_leaders_title = "";
            $our_story_title = "";
            if (strtolower($game_type) == 'cricket') {
                $game_type = strtolower($game_type);
            } else if (strtolower($game_type) == 'football') {
                $game_type = strtolower($game_type);
            } else if (strtolower($game_type) == 'kabbadi') {
                $game_type = strtolower($game_type);
            }

            if ($page_type == 'how_to_play') {
                $options = array('table' => 'how_to_play',
                    'select' => "*",
                    'where' => array('delete_status' => 0, 'page_type' => 1, 'game_type' => $game_type)
                );
                $category = $this->common_model->customGet($options);
                if (!empty($category)) {
                    $eachArr = array();
                    foreach ($category as $rows):

                        $temp['id'] = null_checker($rows->id);
                        $temp['category'] = null_checker($rows->category);
                        $temp['description'] = null_checker($rows->description);
                        $eachArr[] = $temp;
                    endforeach;
                }
            }else if ($page_type == 'faq') {
                $options = array('table' => 'how_to_play',
                    'select' => "*",
                    'where' => array('delete_status' => 0, 'page_type' => 3)
                );
                $category = $this->common_model->customGet($options);
                if (!empty($category)) {
                    $eachArr = array();
                    foreach ($category as $rows):

                        $temp['id'] = null_checker($rows->id);
                        $temp['category'] = null_checker($rows->category);
                        $temp['description'] = null_checker($rows->description);
                        $eachArr[] = $temp;
                    endforeach;
                }
            }else if ($page_type == 'fair_play') {
                $options = array('table' => 'how_to_play',
                    'select' => "*",
                    'where' => array('delete_status' => 0, 'page_type' => 2)
                );
                $category = $this->common_model->customGet($options);
                if (!empty($category)) {
                    $eachArr = array();
                    $transparancyArr = array();
                    $trustArr = array();
                    $responsibilityArr = array();
                    foreach ($category as $rows):

                        if ($rows->category_type == 'TRANSPARENCY') {
                            $image = "";
                            if (!empty($rows->image)) {
                                $image = base_url() . $rows->image;
                            }

                            $temp['id'] = null_checker($rows->id);
                            $temp['category'] = null_checker($rows->category);
                            $temp['description'] = null_checker($rows->description);
                            $temp['image'] = $image;

                            $transparancyArr[] = $temp;
                        }if ($rows->category_type == 'TRUST') {
                            $image = "";
                            if (!empty($rows->image)) {
                                $image = base_url() . $rows->image;
                            }

                            $temp1['id'] = null_checker($rows->id);
                            $temp1['category'] = null_checker($rows->category);
                            $temp1['description'] = null_checker($rows->description);
                            $temp1['image'] = $image;

                            $trustArr[] = $temp1;
                        }if ($rows->category_type == 'RESPONSIBILITY') {

                            $image = "";
                            if (!empty($rows->image)) {
                                $image = base_url() . $rows->image;
                            }

                            $temp2['id'] = null_checker($rows->id);
                            $temp2['category'] = null_checker($rows->category);
                            $temp2['description'] = null_checker($rows->description);
                            $temp2['image'] = $image;

                            $responsibilityArr[] = $temp2;
                        }

                        $option = array(
                            'table' => 'front_header_image',
                            'select' => '*',
                            'where' => array('page_type' => 2, 'category_type' => 'FAIR_PLAY'),
                            'single' => true
                        );
                        $headerImage = $this->common_model->customGet($option);
                        $header_image = "";
                        if (!empty($headerImage->header_image)) {
                            $header_image = base_url() . $headerImage->header_image;
                        }

                        $eachArr['header_image'] = $header_image;
                        $eachArr['transparancy'] = $transparancyArr;
                        $eachArr['trust'] = $trustArr;
                        $eachArr['responsibility'] = $responsibilityArr;

                    endforeach;
                }
            } else if ($page_type == 'help_desk') {

                $options = array('table' => 'helpDesk_category',
                    'select' => "*",
                );
                $category = $this->common_model->customGet($options);
                if (!empty($category)) {
                    $eachArr = array();
                    foreach ($category as $rows):
                        $cat_id = $rows->id;
                        $options = array('table' => 'helpDesk',
                            'select' => "helpDesk.question,helpDesk.answer",
                            'join' => array('helpDesk_category as cat' => 'cat.id=helpDesk.category_id'),
                            'where' => array('helpDesk.category_id' => $cat_id, 'helpDesk.delete_status' => 0)
                        );
                        $response = $this->common_model->customGet($options);
                        $temp['id'] = null_checker($rows->id);
                        $temp['category'] = null_checker($rows->category_name);
                        $temp['questions'] = $response;
                        $eachArr[] = $temp;
                    endforeach;
                }
            }
            else if ($page_type == 'about_us') {
                $options = array('table' => 'how_to_play',
                    'select' => "*",
                    'where' => array('delete_status' => 0, 'page_type' => 4)
                );
                $category = $this->common_model->customGet($options);
                if (!empty($category)) {
                    $eachArr = array();
                    $ourStoryArr = array();
                    $ourLeadersArr = array();
                    $ourResponsibilityArr = array();
                    foreach ($category as $rows):

                        if ($rows->category_type == 'OUR_STORY') {
                            $image = "";
                            if (!empty($rows->image)) {
                                $image = base_url() . $rows->image;
                            }

                            $temp['id'] = null_checker($rows->id);
                            //$temp['category'] = null_checker($rows->category);
                            $temp['description'] = null_checker($rows->description);
                            $temp['image'] = $image;

                            $ourStoryArr[] = $temp;
                        }if ($rows->category_type == 'OUR_LEADERS') {
                            $image = "";
                            if (!empty($rows->image)) {
                                $image = base_url() . $rows->image;
                            }

                            $temp1['id'] = null_checker($rows->id);
                            //$temp1['category'] = null_checker($rows->category);
                            $temp1['description'] = null_checker($rows->description);
                            $temp1['user_name'] = null_checker($rows->user_name);
                            $temp1['designation'] = null_checker($rows->designation);
                            $temp1['image'] = $image;

                            $ourLeadersArr[] = $temp1;
                        }if ($rows->category_type == 'OUR_PARTNERS') {

                            $image = "";
                            if (!empty($rows->image)) {
                                $image = base_url() . $rows->image;
                            }
                            $imgArr = array();
                            $option = array(
                                'table' => 'front_header_image',
                                'select' => '*',
                                'where' => array('page_type' => 4, 'category_type' => 'ABOUT_US_PARTNETSHIP'),
                                'single' => true
                            );
                            $partersImages = $this->common_model->customGet($option);

                            //$images = "";
                            if (!empty($partersImages)) {
                                $images = json_decode($partersImages->official_league_partnerships);

                                foreach ($images as $img) {

                                    $img = base_url() . "uploads/frontImage/" . $img;

                                    array_push($imgArr, $img);
                                }
                            }


                            $temp2['id'] = null_checker($rows->id);
                            //$temp2['category'] = null_checker($rows->category);
                            $temp2['description'] = null_checker($rows->description);
                            $temp2['image'] = $image;
                            $temp2['league_partnership_images'] = $imgArr;

                            $ourResponsibilityArr[] = $temp2;
                        }
                        $our_story_title = "";
                        $our_leaders_title = "";
                        $our_partners_title = "";
                        $option = array(
                            'table' => 'front_header_image',
                            'select' => '*',
                            'where' => array('page_type' => 4, 'category_type' => 'ABOUT_US_IMAGE'),
                            'single' => true
                        );
                        $headerImage = $this->common_model->customGet($option);
                        $header_image = "";
                        if (!empty($headerImage->header_image)) {
                            $header_image = base_url() . $headerImage->header_image;
                        }

                        $option = array(
                            'table' => 'front_header_image',
                            'select' => '*',
                            'where' => array('page_type' => 4),
                        );
                        $headerTitle = $this->common_model->customGet($option);
                        if (!empty($headerTitle)) {
                            foreach ($headerTitle as $title) {
                                if ($title->category_type == 'OUR_STORY') {
                                    $our_story_title = null_checker($title->category_title);
                                }if ($title->category_type == 'OUR_LEADERS') {
                                    $our_leaders_title = null_checker($title->category_title);
                                }if ($title->category_type == 'OUR_PARTNERS') {
                                    $our_partners_title = null_checker($title->category_title);
                                }
                            }
                        }
                        $eachArr['header_image'] = $header_image;
                        $eachArr['our_story'] = $ourStoryArr;
                        $eachArr['our_leaders'] = $ourLeadersArr;
                        $eachArr['our_partners'] = $ourResponsibilityArr;
                        $eachArr['our_story_title'] = $our_story_title;
                        $eachArr['our_leaders_title'] = $our_leaders_title;
                        $eachArr['our_partners_title'] = $our_partners_title;

                    endforeach;
                }
            }
            if (empty($category)) {
                $return['status'] = 0;
                $return['message'] = 'Not found';
            } else {
                $return['status'] = 1;
                $return['response'] = $eachArr;
                $return['message'] = 'Listed';
            }
        }
        $this->response($return);
    }

    /**
     * Function Name: contact_us
     * Description:   To contact request
     */
    function contact_us_post() {
        $return['code'] = 200;
        $data = $this->input->post();
        $this->form_validation->set_rules('full_name', 'Full Name', 'trim|required');
        $this->form_validation->set_rules('email', 'Email', 'required|trim');
        $this->form_validation->set_rules('phone', 'Phone', 'required|trim|numeric');
        //$this->form_validation->set_rules('business_name', 'Business Name', 'required|trim');
        //$this->form_validation->set_rules('comment', 'Comment', 'required|trim');
        if ($this->form_validation->run() == FALSE) {
            $error = $this->form_validation->rest_first_error_string();
            $return['status'] = 0;
            $return['message'] = $error;
        } else {
            $dataArr = array();
            $dataArr['full_name'] = extract_value($data, 'full_name', '');
            $dataArr['email'] = extract_value($data, 'email', '');
            $dataArr['phone'] = extract_value($data, 'phone', '');
            $dataArr['business_name'] = extract_value($data, 'business_name', '');
            $dataArr['comment'] = extract_value($data, 'comment', '');
            $dataArr['created_date'] = date('Y-m-d H:i:s');
            // if (!empty($_FILES['image']['name'])) {
            //     $image = fileUpload('image', 'users', 'png|jpg|jpeg|gif|pdf');
            //     if (isset($image['error']) && !empty($image['error'])) {
            //         $return['message'] = strip_tags($image['error']);
            //         $return['status'] = 0;
            //         $this->response($return);
            //         exit;
            //     }
            //     $dataArr['image'] = 'uploads/users/' . $image['upload_data']['file_name'];
            // }
            $option = array(
                'table' => 'contact_us',
                'data' => $dataArr
            );
            $career_data = $this->common_model->customInsert($option);
            if ($career_data) {
                $return['status'] = 1;
                $return['message'] = 'Information saved successfully';
            } else {
                $is_error = db_err_msg();
                if ($is_error == FALSE) {
                    $return['status'] = 1;
                    $return['message'] = 'Information saved successfully';
                } else {
                    $return['status'] = 0;
                    $return['message'] = $is_error;
                }
            }
        }

        $this->response($return);
    }

    /**
     * Function Name: withdrawal_req_cancel
     * Description:   To withdrawal request cancel
     */
    /* function withdrawal_req_cancel_post() {
      $return['code'] = 200;
      //$return['response'] = new stdClass();
      $data = $this->input->post();
      $this->form_validation->set_rules('login_session_key', 'Login session key', 'trim|required|callback__validate_login_session_key');
      if ($this->form_validation->run() == FALSE) {
      $error = $this->form_validation->rest_first_error_string();
      $return['status'] = 0;
      $return['message'] = $error;
      } else {
      $user_id = $this->user_details->id;
      $option = array('table' => 'withdrawal_request as wd',
      'select' => 'wd.*',
      'join' => array('users as user' => 'user.id=wd.user_id',
      'wallet' => 'wallet.user_id=user.id'),
      'where' => array('wd.user_id' => $user_id),
      'where_in' => array('wd.status' => array(1, 3)),
      'single' => true
      );
      $withdrawalRequest = $this->common_model->customGet($option);
      if (!empty($withdrawalRequest)) {

      $option = array(
      'table' => 'users',
      'select' => 'users.id,wallet.deposited_amount,wallet.winning_amount,wallet.total_balance',
      'join' => array('wallet' => 'wallet.user_id=users.id'),
      'where' => array('users.id' => $user_id),
      'single' => true
      );
      $wallet = $this->common_model->customGet($option);
      $deposited_amount = $withdrawalRequest->used_deposit_amount + $wallet->deposited_amount;
      $winning_amount = $withdrawalRequest->used_winning_amount + $wallet->winning_amount;
      $total_balance = abs($wallet->total_balance) + abs($withdrawalRequest->used_deposit_amount) + abs($withdrawalRequest->used_winning_amount);
      $optionsWallet = array(
      'table' => 'wallet',
      'data' => array(
      'deposited_amount' => $deposited_amount,
      'winning_amount' => $winning_amount,
      'total_balance' => $total_balance),
      'where' => array('user_id' => $user_id)
      );
      $updateWallet = $this->common_model->customUpdate($optionsWallet);
      if (!empty($updateWallet)) {
      $options = array(
      'table' => 'transactions_history',
      'data' => array(
      'opening_balance' => $wallet->total_balance,
      'cr' => $withdrawalRequest->amount,
      'available_balance' => abs($wallet->total_balance) + $withdrawalRequest->amount,
      'message' => "Your Withdrawal request has been cancelled",
      'user_id' => $user_id,
      'status' => 1,
      'pay_type' => 'WITHDRAWAL',
      'transaction_type' => 'CASH',
      'datetime' => date('Y-m-d H:i:s')
      )
      );
      $this->common_model->customInsert($options);
      $options = array(
      'table' => 'notifications',
      'data' => array(
      'user_id' => 1,
      'type_id' => 0,
      'sender_id' => $user_id,
      'noti_type' => 'USER_WITHDRAWAL_AMOUNT',
      'message' => 'Your Withdrawal request has been cancelled amount of Rs. ' . $withdrawalRequest->amount,
      'read_status' => 'NO',
      'sent_time' => date('Y-m-d H:i:s'),
      'user_type' => 'ADMIN'
      )
      );
      $this->common_model->customInsert($options);
      $options = array(
      'table' => 'withdrawal_request',
      'data' => array('status' => 4),
      'where' => array('id' => $withdrawalRequest->id, 'user_id' => $user_id)
      );
      $this->common_model->customUpdate($options);
      $return['status'] = 1;
      $return['message'] = "Your withdrawal request successfully cancelled";
      }
      } else {
      $return['status'] = 0;
      $return['message'] = "Withdrawal request not found";
      }
      }
      $this->response($return);
      } */
    function withdrawal_req_cancel_post() {
        $return['code'] = 200;
        //$return['response'] = new stdClass();
        $data = $this->input->post();
        $this->form_validation->set_rules('login_session_key', 'Login session key', 'trim|required|callback__validate_login_session_key');
        if ($this->form_validation->run() == FALSE) {
            $error = $this->form_validation->rest_first_error_string();
            $return['status'] = 0;
            $return['message'] = $error;
        } else {
            $user_id = $this->user_details->id;
            $option = array('table' => 'withdrawal_request as wd',
                'select' => 'wd.*',
                'join' => array('users as user' => 'user.id=wd.user_id',
                    'wallet' => 'wallet.user_id=user.id'),
                'where' => array('wd.user_id' => $user_id),
                'where_in' => array('wd.status' => array(1, 3)),
                'single' => true
            );
            $withdrawalRequest = $this->common_model->customGet($option);
            if (!empty($withdrawalRequest)) {

                $option = array(
                    'table' => 'users',
                    'select' => 'users.id,wallet.deposited_amount,wallet.winning_amount,wallet.total_balance',
                    'join' => array('wallet' => 'wallet.user_id=users.id'),
                    'where' => array('users.id' => $user_id),
                    'single' => true
                );
                $wallet = $this->common_model->customGet($option);
                $deposited_amount = $withdrawalRequest->used_deposit_amount + $wallet->deposited_amount;
                $winning_amount = $withdrawalRequest->used_winning_amount + $wallet->winning_amount;
                $total_balance = abs($wallet->total_balance) + abs($withdrawalRequest->used_deposit_amount) + abs($withdrawalRequest->used_winning_amount);
                $optionsWallet = array(
                    'table' => 'wallet',
                    'data' => array(
                        'deposited_amount' => $deposited_amount,
                        'winning_amount' => $winning_amount,
                        'total_balance' => $total_balance),
                    'where' => array('user_id' => $user_id)
                );
                $updateWallet = $this->common_model->customUpdate($optionsWallet);
                if (!empty($updateWallet)) {
                    $options = array(
                        'table' => 'transactions_history',
                        'data' => array(
                            'opening_balance' => $wallet->total_balance,
                            'cr' => $withdrawalRequest->amount,
                            'available_balance' => abs($wallet->total_balance) + $withdrawalRequest->amount,
                            'message' => "Your Withdrawal request has been cancelled",
                            'user_id' => $user_id,
                            'status' => 1,
                            'pay_type' => 'WITHDRAWAL',
                            'transaction_type' => 'CASH',
                            'datetime' => date('Y-m-d H:i:s')
                        )
                    );
                    $this->common_model->customInsert($options);
                    $options = array(
                        'table' => 'notifications',
                        'data' => array(
                            'user_id' => 1,
                            'type_id' => 0,
                            'sender_id' => $user_id,
                            'noti_type' => 'USER_WITHDRAWAL_AMOUNT',
                            'message' => 'Your Withdrawal request has been cancelled amount of Rs. ' . $withdrawalRequest->amount,
                            'read_status' => 'NO',
                            'sent_time' => date('Y-m-d H:i:s'),
                            'user_type' => 'ADMIN'
                        )
                    );
                    $this->common_model->customInsert($options);
                    $options = array(
                        'table' => 'withdrawal_request',
                        'data' => array('status' => 4),
                        'where' => array('id' => $withdrawalRequest->id, 'user_id' => $user_id)
                    );
                    $this->common_model->customUpdate($options);
                    $return['status'] = 1;
                    $return['message'] = "Your withdrawal request successfully cancelled";
                }
            } else {
                $return['status'] = 0;
                $return['message'] = "Withdrawal request not found";
            }
        }
        $this->response($return);
    }

    /**
     * Function Name: withdrawal_req
     * Description:   To withdrawal request
     */
    /* function withdrawal_req_post() {
      $return['code'] = 200;

      $data = $this->input->post();
      $this->form_validation->set_rules('login_session_key', 'Login session key', 'trim|required|callback__validate_login_session_key');
      $this->form_validation->set_rules('amount', 'Amount', 'trim|required|numeric');
      if ($this->form_validation->run() == FALSE) {
      $error = $this->form_validation->rest_first_error_string();
      $return['status'] = 0;
      $return['message'] = $error;
      } else {
      $user_id = $this->user_details->id;
      $userName = $this->user_details->first_name . '(' . $this->user_details->team_code . ')';
      $WithdrawalAmount = (int) extract_value($data, 'amount', '');
      $option = array('table' => 'users as USR',
      'select' => 'USR.id as user_id,USR.email_verify,USR.verify_mobile,UPC.verification_status as pan_status',
      'join' => array('user_pan_card as UPC' => 'UPC.user_id=USR.id',
      'user_bank_account_detail as UBAD' => 'UBAD.user_id=USR.id'),
      'where' => array('USR.id' => $user_id, 'USR.email_verify' => 1,
      'USR.verify_mobile' => 1, 'UPC.verification_status' => 2,
      'UBAD.verification_status' => 2));
      $userData = $this->common_model->customGet($option);
      if (!empty($userData)) {
      if ($WithdrawalAmount >= 200) {
      $option = array(
      'table' => 'users',
      'select' => 'users.id,wallet.deposited_amount,wallet.winning_amount,wallet.total_balance',
      'join' => array('wallet' => 'wallet.user_id=users.id'),
      'where' => array('users.id' => $user_id),
      'single' => true
      );
      $wallet = $this->common_model->customGet($option);
      if (!empty($wallet)) {
      $walletAmount = abs($wallet->deposited_amount) + abs($wallet->winning_amount);
      if ($WithdrawalAmount <= $walletAmount) {
      $option = array(
      'table' => 'withdrawal_request',
      'select' => 'id',
      'where' => array('user_id' => $user_id,
      'status' => 1),
      'single' => true
      );
      $is_pending_request = $this->common_model->customGet($option);
      if (empty($is_pending_request)) {

      if (!empty($wallet)) {
      $walletAmount = abs($wallet->deposited_amount) + abs($wallet->winning_amount);
      $WithdrawalAmount = $WithdrawalAmount;
      if ($WithdrawalAmount <= $walletAmount) {
      $deposited_amount = abs($wallet->deposited_amount);
      $winning_amount = abs($wallet->winning_amount);
      $total_balance = abs($wallet->total_balance);

      $total_balance = $total_balance - abs($WithdrawalAmount);

      if ($winning_amount > 0) {
      $winning_amount = $winning_amount - abs($WithdrawalAmount);
      if ($winning_amount < 0) {
      if ($deposited_amount > 0) {
      $deposited_amount = $deposited_amount - abs($winning_amount);
      }
      $winning_amount = 0;
      }
      } else {
      if ($deposited_amount > 0) {
      $deposited_amount = $deposited_amount - abs($WithdrawalAmount);
      }
      }

      $optionsWallet = array(
      'table' => 'wallet',
      'data' => array(
      'deposited_amount' => $deposited_amount,
      'winning_amount' => $winning_amount,
      'total_balance' => $total_balance),
      'where' => array('user_id' => $user_id)
      );
      $this->common_model->customUpdate($optionsWallet);
      $orderId = generateToken(6);
      $options = array(
      'table' => 'transactions_history',
      'data' => array(
      'opening_balance' => $wallet->total_balance,
      'dr' => $WithdrawalAmount,
      'orderId' => $orderId,
      'available_balance' => abs($wallet->total_balance) - $WithdrawalAmount,
      'total_wallet_balance' => abs($wallet->total_balance) - $WithdrawalAmount,
      'message' => "Withdrawal request in pending",
      'user_id' => $user_id,
      'status' => 1,
      'pay_type' => 'WITHDRAWAL',
      'transaction_type' => 'CASH',
      'datetime' => date('Y-m-d H:i:s')
      )
      );
      $this->common_model->customInsert($options);
      }
      }
      $deposited_amount_bal = abs($wallet->deposited_amount);
      $winning_amount_bal = abs($wallet->winning_amount);
      $win = 0;
      $depo = 0;
      if ($WithdrawalAmount >= $winning_amount_bal) {
      $win = $winning_amount_bal;
      $depo = $WithdrawalAmount - $winning_amount_bal;
      } else {
      $win = $WithdrawalAmount;
      }
      $option = array(
      'table' => 'withdrawal_request',
      'data' => array('user_id' => $user_id,
      'status' => 1,
      'amount' => $WithdrawalAmount,
      'deduct_amount' => 0,
      'used_winning_amount' => $win,
      'used_deposit_amount' => $depo,
      'datetime' => date('Y-m-d H:i:s'))
      );
      $withdrawalStatus = $this->common_model->customInsert($option);
      if (!empty($withdrawalStatus)) {
      $options = array(
      'table' => 'notifications',
      'data' => array(
      'user_id' => 1,
      'type_id' => 0,
      'sender_id' => $user_id,
      'noti_type' => 'USER_WITHDRAWAL_AMOUNT',
      'message' => $userName . ' processing withdrawal request at amount Rs. ' . $WithdrawalAmount,
      'read_status' => 'NO',
      'sent_time' => date('Y-m-d H:i:s'),
      'user_type' => 'ADMIN'
      )
      );
      $this->common_model->customInsert($options);
      $return['withdrawal_status'] = 1;
      $return['status'] = 1;
      $return['message'] = "Your withdrawal request successfully submitted,We will approved with in 5-7 working days";
      } else {
      $return['status'] = 0;
      $return['message'] = "You withdrawal request failed please try again";
      }
      } else {
      $return['status'] = 0;
      $return['message'] = "You withdrawal request already in pending";
      }
      } else {
      $return['status'] = 0;
      $return['message'] = "You haven't insufficient amount";
      }
      } else {
      $return['status'] = 0;
      $return['message'] = "You haven't insufficient amount";
      }
      } else {
      $return['status'] = 0;
      $return['message'] = "Withdrawal limits minimum Rs. 200";
      }
      } else {
      $return['status'] = 0;
      $return['message'] = "Please verify your account details.";
      }
      }
      $this->response($return);
      } */
    function withdrawal_req_post() {
        $return['code'] = 200;
        //$return['response'] = new stdClass();
        $data = $this->input->post();
        $this->form_validation->set_rules('login_session_key', 'Login session key', 'trim|required|callback__validate_login_session_key');
        $this->form_validation->set_rules('amount', 'Amount', 'trim|required|numeric');
        if ($this->form_validation->run() == FALSE) {
            $error = $this->form_validation->rest_first_error_string();
            $return['status'] = 0;
            $return['message'] = $error;
        } else {
            $user_id = $this->user_details->id;

            $option = array(
                'table' => 'users',
                'select' => 'first_name',
                'where' => array('id' => $this->user_details->id),
                'single' => true
            );
            $userProfile = $this->common_model->customGet($option);
            if (!empty($userProfile)) {
                $userName = null_checker($userProfile->first_name);
                if (empty($userName)) {
                    $return['message'] = "Please update your profile first";
                    $return['status'] = 0;
                    $this->response($return);
                    exit;
                }
            }
            $userName = $this->user_details->first_name . '(' . $this->user_details->team_code . ')';
            $WithdrawalAmount = (int) extract_value($data, 'amount', '');
            $option = array('table' => 'users as USR',
                'select' => 'USR.id as user_id,USR.email_verify,USR.verify_mobile,UPC.verification_status as pan_status',
                'join' => array('user_pan_card as UPC' => 'UPC.user_id=USR.id',
                    'user_bank_account_detail as UBAD' => 'UBAD.user_id=USR.id'),
                'where' => array('USR.id' => $user_id, 'USR.email_verify' => 1,
                    'USR.verify_mobile' => 1, 'UPC.verification_status' => 2,
                    'UBAD.verification_status' => 2));
            $userData = $this->common_model->customGet($option);
            if (!empty($userData)) {
                if ($WithdrawalAmount >= 200) {
                    $option = array(
                        'table' => 'users',
                        'select' => 'users.id,wallet.deposited_amount,wallet.winning_amount,wallet.total_balance',
                        'join' => array('wallet' => 'wallet.user_id=users.id'),
                        'where' => array('users.id' => $user_id),
                        'single' => true
                    );
                    $wallet = $this->common_model->customGet($option);
                    if (!empty($wallet)) {
                        $walletAmount = abs($wallet->winning_amount);
                        if ($WithdrawalAmount <= $walletAmount) {
                            $option = array(
                                'table' => 'withdrawal_request',
                                'select' => 'id',
                                'where' => array('user_id' => $user_id,
                                    'status' => 1),
                                'single' => true
                            );
                            $is_pending_request = $this->common_model->customGet($option);
                            if (empty($is_pending_request)) {


                                /** wallet calculation * */
                                if (!empty($wallet)) {
                                    $walletAmount = abs($wallet->deposited_amount) + abs($wallet->winning_amount);
                                    $WithdrawalAmount = $WithdrawalAmount;
                                    if ($WithdrawalAmount <= $walletAmount) {
                                        $deposited_amount = abs($wallet->deposited_amount);
                                        $winning_amount = abs($wallet->winning_amount);
                                        $total_balance = abs($wallet->total_balance);

                                        $total_balance = $total_balance - abs($WithdrawalAmount);

                                        if ($winning_amount > 0) {
                                            $winning_amount = $winning_amount - abs($WithdrawalAmount);
                                            if ($winning_amount < 0) {
                                                if ($deposited_amount > 0) {
                                                    $deposited_amount = $deposited_amount - abs($winning_amount);
                                                }
                                                $winning_amount = 0;
                                            }
                                        } else {
                                            if ($deposited_amount > 0) {
                                                $deposited_amount = $deposited_amount - abs($WithdrawalAmount);
                                            }
                                        }

                                        /* $optionsWallet = array(
                                          'table' => 'wallet',
                                          'data' => array(
                                          'deposited_amount' => $deposited_amount,
                                          'winning_amount' => $winning_amount,
                                          'total_balance' => $total_balance),
                                          'where' => array('user_id' => $user_id)
                                          );
                                          $this->common_model->customUpdate($optionsWallet); */
                                        $orderId = generateToken(6);
                                        $options = array(
                                            'table' => 'transactions_history',
                                            'data' => array(
                                                'opening_balance' => $wallet->total_balance,
                                                'dr' => $WithdrawalAmount,
                                                'orderId' => $orderId,
                                                'available_balance' => abs($wallet->total_balance),
                                                'total_wallet_balance' => abs($wallet->total_balance),
                                                'message' => "Withdrawal request in pending",
                                                'user_id' => $user_id,
                                                'status' => 1,
                                                'pay_type' => 'WITHDRAWAL',
                                                'transaction_type' => 'CASH',
                                                'datetime' => date('Y-m-d H:i:s')
                                            )
                                        );
                                        $this->common_model->customInsert($options);
                                    }
                                }
                                $deposited_amount_bal = abs($wallet->deposited_amount);
                                $winning_amount_bal = abs($wallet->winning_amount);
                                $win = 0;
                                $depo = 0;
                                if ($WithdrawalAmount >= $winning_amount_bal) {
                                    $win = $winning_amount_bal;
                                    $depo = $WithdrawalAmount - $winning_amount_bal;
                                } else {
                                    $win = $WithdrawalAmount;
                                }
                                $option = array(
                                    'table' => 'withdrawal_request',
                                    'data' => array('user_id' => $user_id,
                                        'status' => 1,
                                        'amount' => $WithdrawalAmount,
                                        'deduct_amount' => 0,
                                        'used_winning_amount' => $win,
                                        'used_deposit_amount' => $depo,
                                        'datetime' => date('Y-m-d H:i:s'))
                                );
                                $withdrawalStatus = $this->common_model->customInsert($option);
                                if (!empty($withdrawalStatus)) {
                                    $options = array(
                                        'table' => 'notifications',
                                        'data' => array(
                                            'user_id' => 1,
                                            'type_id' => 0,
                                            'sender_id' => $user_id,
                                            'noti_type' => 'USER_WITHDRAWAL_AMOUNT',
                                            'message' => $userName . ' processing withdrawal request at amount Rs. ' . $WithdrawalAmount,
                                            'read_status' => 'NO',
                                            'sent_time' => date('Y-m-d H:i:s'),
                                            'user_type' => 'ADMIN'
                                        )
                                    );
                                    $this->common_model->customInsert($options);
                                    $return['withdrawal_status'] = 1;
                                    $return['status'] = 1;
                                    $return['message'] = "Your withdrawal request successfully submitted,We will approved with in 5-7 working days";
                                } else {
                                    $return['status'] = 0;
                                    $return['message'] = "You withdrawal request failed please try again";
                                }
                            } else {
                                $return['status'] = 0;
                                $return['message'] = "You withdrawal request already in pending";
                            }
                        } else {
                            $return['status'] = 0;
                            $return['message'] = "You haven't insufficient amount";
                        }
                    } else {
                        $return['status'] = 0;
                        $return['message'] = "You haven't insufficient amount";
                    }
                } else {
                    $return['status'] = 0;
                    $return['message'] = "Withdrawal limits minimum Rs. 200";
                }
            } else {
                $return['status'] = 0;
                $return['message'] = "Please verify your account details.";
            }
        }
        $this->response($return);
    }

    /**
     * Function Name: testimonial_list
     * Description:   To get contest joined team download
     */
    function testimonial_list_post() {
        $return['code'] = 200;
        $data = $this->input->post();

        $option = array(
            'table' => 'testimonial',
            'select' => '*',
            'where' => array('testimonial.status' => 1, 'delete_status' => 0),
            'order' => array('testimonial.id' => 'DESC'),
                //'limit' => array($limit => $offset)
        );
        $testimonial = $this->common_model->customGet($option);
        if (!empty($testimonial)) {
            $response = array();
            foreach ($testimonial as $rows) {

                $temp['first_name'] = null_checker($rows->user_name);
                $temp['description'] = null_checker($rows->description);
                $temp['member_since'] = null_checker($rows->member_since);
                //$temp['rating'] = $rows->rating;
                $profile_pic = base_url() . DEFAULT_USER_IMG_PATH;
                if (!empty($rows->image)) {
                    $profile_pic = base_url() . "uploads/users/" . $rows->image;
                }
                $temp['profile_pic'] = $profile_pic;
                $response[] = $temp;
            }

            $options = array(
                'table' => 'front_header_image',
                'select' => '*',
                'where' => array('page_type' => 'testimonial'),
                'single' => true
            );
            $headerData = $this->common_model->customGet($options);
            if (!empty($headerData)) {
                $temp1['header_image'] = base_url() . $headerData->header_image;
                $temp1['header_image_text'] = null_checker($headerData->header_image_text);
            } else {
                $temp1['header_image'] = "";
                $temp1['header_image_text'] = "";
            }

            $return['response'] = $response;
            $return['header_response'] = $temp1;
            $return['status'] = 1;
            $return['message'] = 'Records listed';
        } else {
            $return['status'] = 0;
            $return['message'] = 'Records not found';
        }

        $this->response($return);
    }

    function test_notification_post() {
        $data_array = array(
            'title' => 'theteamgenie',
            'message' => 'hello all',
            'type' => "test",
            'type_id' => 1,
            'user_id' => 0,
            'badges' => 0,
        );
        //$token = "dumdkNfdGfw:APA91bHH9kNxLBgFNPIakOpEryC9r-EsuX1kaAho-3itF5WNQes1K28S6nh-1vnxcZxRrf2nN-KGIKblWWU1owXSFdh9AO6RJQq8NIJlDYsFwHrmIEzk-lprWl8QFGG1_foloXyzAtlH";

        $token = "c719f84573163b699f170ccd6eef53e0051c4d9da780d85dec7000665d5cdf40";
        $message = "hello test";
        //send_android_notification($data_array, $token);
        $notificationData = array(
            'title' => 'Deposite Amount',
            'message' => "Your deposite of rs. 100 was successful.",
            'type' => "USER_DEPOSITE_AMOUNT",
            'type_id' => 0,
            'user_id' => 56,
            'badges' => 0,
        );
        //send_ios_notification($token,$message,$notificationData);
        $notificationData = array(
            'title' => 'Deposite Amount',
            'message' => "Your deposite of Rs 100 was successful.",
            'type' => "USER_DEPOSITE_AMOUNT",
            'type_id' => 0,
            'user_id' => 56,
            'badges' => 0,
        );
        sendNotification(56, $notificationData);
    }

    /**
     * Function Name: contact_us
     * Description:   To contact request
     */
    function affiliate_scheme_post() {
        $return['code'] = 200;
        $data = $this->input->post();
        $this->form_validation->set_rules('full_name', 'Full Name', 'trim|required');
        $this->form_validation->set_rules('email', 'Email', 'required|trim');
        $this->form_validation->set_rules('subject', 'Subject', 'required|trim');
        $this->form_validation->set_rules('scheme', 'Scheme', 'required|trim');
        $this->form_validation->set_rules('message', 'Message', 'required|trim');
        if ($this->form_validation->run() == FALSE) {
            $error = $this->form_validation->rest_first_error_string();
            $return['status'] = 0;
            $return['message'] = $error;
        } else {
            $dataArr = array();
            $dataArr['full_name'] = extract_value($data, 'full_name', '');
            $dataArr['email'] = extract_value($data, 'email', '');
            $dataArr['scheme'] = $scheme = extract_value($data, 'scheme', '');
            $dataArr['subject'] = extract_value($data, 'subject', '');
            $dataArr['message'] = extract_value($data, 'message', '');
            $dataArr['created_date'] = date('Y-m-d H:i:s');

            $option = array(
                'table' => 'affiliate_scheme',
                'data' => $dataArr
            );
            $affiliate_scheme = $this->common_model->customInsert($option);
            if ($affiliate_scheme) {

                $html = array();
                $scText = "SCHEME - 1";
                if ($scheme == 2) {
                    $scText = "SCHEME - 2";
                }
                $html['full_name'] = extract_value($data, 'full_name', '');
                $html['email'] = extract_value($data, 'email', '');
                $html['scheme'] = $scText;
                $html['subject'] = extract_value($data, 'subject', '');
                $html['message'] = extract_value($data, 'message', '');
                $html['logo'] = base_url() . getConfig('site_logo');
                $html['site'] = getConfig('site_name');
                $email_template = $this->load->view('email/affiliated_scheme_request_tpl', $html, true);
                $sent = send_mail($email_template, '[' . getConfig('site_name') . '] Affiliated Ccheme', getConfig('admin_email'), getConfig('admin_email'));
                $return['status'] = 1;
                $return['message'] = 'Application submitted successfully';
            } else {
                $is_error = db_err_msg();
                if ($is_error == FALSE) {
                    $return['status'] = 1;
                    $return['message'] = 'Application submitted successfully';
                } else {
                    $return['status'] = 0;
                    $return['message'] = $is_error;
                }
            }
        }

        $this->response($return);
    }

    function update_userToken_post() {

        $return['code'] = 200;
        $data = $this->input->post();
        $this->form_validation->set_rules('login_session_key', 'Login session key', 'trim|required');
        $this->form_validation->set_rules('device_type', 'Device Type', 'trim|required|in_list[ANDROID,IOS]');
        $this->form_validation->set_rules('device_token', 'Device Token', 'trim|required');
        $this->form_validation->set_rules('device_id', 'Device Id', 'trim|required');

        if ($this->form_validation->run() == FALSE) {
            $error = $this->form_validation->rest_first_error_string();
            $return['status'] = 0;
            $return['message'] = $error;
        } else {

            $user_id = $this->user_details->id;
            $device_token = extract_value($data, 'device_token', '');
            $device_type = extract_value($data, 'device_type', '');
            $device_id = extract_value($data, 'device_id', '');

            $option = array(
                'table' => 'users_device_history',
                'select' => '*',
                'where' => array('user_id' => $user_id, 'device_id' => $device_id)
            );
            $user_details = $this->common_model->customGet($option);

            if (!empty($user_details)) {
                $options = array(
                    'table' => 'users_device_history',
                    'data' => array(
                        'device_token' => $device_token,
                        'device_type' => $device_type,
                        'device_id' => $device_id
                    ),
                    'where' => array('user_id' => $user_id, 'device_id' => $device_id)
                );
                $userData = $this->common_model->customUpdate($options);
            } else {


                $option = array(
                    'table' => 'users_device_history',
                    'data' => array(
                        'device_token' => $device_token,
                        'device_type' => $device_type,
                        'device_id' => $device_id,
                        'user_id' => $user_id,
                        'added_date' => datetime()
                    )
                );
                $userData = $this->common_model->customInsert($option);
            }

            if ($userData) {
                $return['status'] = 1;
                $return['message'] = 'Token updated successfully';
            } else {
                $return['status'] = 0;
                $return['message'] = 'Token updated successfully';
            }
        }
        $this->response($return);
    }

    /**
     * Function Name: send_download_url
     * Description:   To send User mobile on apk download url
     */
    function send_download_url_post() {
        $return['code'] = 200;
        //$return['response'] = new stdClass();
        $data = $this->input->post();
        $this->form_validation->set_rules('mobile', 'Mobile', 'trim|required|min_length[10]|max_length[11]|numeric');
        if ($this->form_validation->run() == FALSE) {
            $error = $this->form_validation->rest_first_error_string();
            $return['status'] = 0;
            $return['message'] = $error;
        } else {
            $dataArr = array();
            $url = base_url() . '/android/theteamgenie.apk';
            $mobile = extract_value($data, 'mobile', '');
            $postfields = array('mobile' => $mobile,
                'message' => "Click Here to download theteamgenie App " . $url,
            );
            if ($this->smsSend($postfields)) {
                $return['status'] = 1;
                $return['message'] = 'A download link successfully sent on your mobile number.';
            } else {
                $return['status'] = 0;
                $return['message'] = 'Message not sent';
            }
        }
        $this->response($return);
    }

    /* to check app version */

    function appVersion_post() {
        $return['code'] = 200;

        $data = $this->input->post();
        $this->form_validation->set_rules('version', 'Version', 'trim|required|min_length[1]|numeric');
        if ($this->form_validation->run() == FALSE) {
            $error = $this->form_validation->rest_first_error_string();
            $return['status'] = 0;
            $return['message'] = $error;
        } else {
            $version = extract_value($data, 'version', '');
            $option = array(
                'table' => 'app_version',
                'select' => 'MAX(version) as version,compulsary_update as compulsary',
                'where' => array('version >= ' => $version),
                'limit' => '1'
            );
            $appData = $this->common_model->customGet($option);

            $downloadLink = 'https://www.funtush11.com/android/funtush11.apk';

            if ($appData[0]->version != '') {
                $return['status'] = 1;
                $return['message'] = 'Version found successfully';
                $return['response'] = $appData;
                $return['downloadLink'] = $downloadLink;
            } else {

                $dataArr = array();

                $dataArr['version'] = $version;
                $dataArr['compulsary_update'] = 0;
                $dataArr['download_link'] = $downloadLink;


                $options = array(
                    'table' => 'app_version',
                    'data' => $dataArr
                );
                $insertId = $this->common_model->customInsert($options);
                $option = array(
                    'table' => 'app_version',
                    'select' => 'MAX(version) as version,compulsary_update as compulsary',
                    'where' => array('id ' => $insertId),
                    'limit' => '1'
                );
                $appDataVersion = $this->common_model->customGet($option);
                if ($appDataVersion[0]->version != '') {
                    $return['status'] = 1;
                    $return['message'] = 'Version found successfully';
                    $return['response'] = $appDataVersion;
                    $return['downloadLink'] = $downloadLink;
                }
            }
        }
        $this->response($return);
    }

    function checkUserMobileVerify_post() {
        $return['code'] = 200;
        $data = $this->input->post();
        $this->form_validation->set_rules('login_session_key', 'Login session key', 'trim|required|callback__validate_login_session_key');
        if ($this->form_validation->run() == FALSE) {
            $error = $this->form_validation->rest_first_error_string();
            $return['status'] = 0;
            $return['message'] = $error;
        } else {

            $user_id = $this->user_details->id;
            $option = array(
                'table' => 'users',
                'select' => 'verify_mobile',
                'where' => array('id' => $user_id),
                'single' => true
            );
            $userData = $this->common_model->customGet($option);

            if (!empty($userData)) {
                if ($userData->verify_mobile == 0) {
                    $option = array(
                        'table' => 'users',
                        'where' => array('id' => $user_id)
                    );
                    $this->common_model->customDelete($option);

                    $return['status'] = 1;
                    $return['message'] = 'Successfully deleted';
                } else {
                    $return['status'] = 0;
                    $return['message'] = 'Not deleted';
                }
            } else {

                $return['status'] = 0;
                $return['message'] = 'User not found';
            }
        }
        $this->response($return);
    }

    function users_icon_list_post() {
        $return['code'] = 200;
        $data = $this->input->post();

        $this->form_validation->set_rules('login_session_key', 'Login session key', 'trim|required|callback__validate_login_session_key');

        if ($this->form_validation->run() == FALSE) {
            $error = $this->form_validation->rest_first_error_string();
            $return['status'] = 0;
            $return['message'] = $error;
        } else {
            //$user_id = $this->user_details->id;

            $option = array(
                'table' => 'users_icon',
                'select' => '*',
                'where' => array('delete_status' => 0),
                'order' => array('id' => 'DESC'),
            );
            $usersIcon = $this->common_model->customGet($option);
            if (!empty($usersIcon)) {
                $response = array();
                foreach ($usersIcon as $icon) {
                    $temp['id'] = $icon->id;

                    $user_icon = base_url() . DEFAULT_USER_IMG_PATH;
                    if (!empty($icon->user_icon)) {
                        $user_icon = base_url() . $icon->user_icon;
                    }
                    $temp['user_icon'] = $user_icon;
                    $temp['icon_key'] = $icon->user_icon;
                    $response[] = $temp;
                }
                $return['response'] = $response;
                $return['status'] = 1;
                $return['message'] = 'Records listed';
            } else {
                $return['status'] = 0;
                $return['message'] = 'Records not found';
            }
        }
        $this->response($return);
    }

    function send_app_download_link_post() {

        $return['code'] = 200;
        $data = $this->input->post();

        //$this->form_validation->set_rules('login_session_key', 'Login session key', 'trim|required|callback__validate_login_session_key');
        $this->form_validation->set_rules('download_link', 'Download Link', 'trim|required');
        $this->form_validation->set_rules('send_type', 'Send Type', 'trim|required');
        $this->form_validation->set_rules('send_value', 'Send Value', 'trim|required');

        if ($this->form_validation->run() == FALSE) {
            $error = $this->form_validation->rest_first_error_string();
            $return['status'] = 0;
            $return['message'] = $error;
        } else {

            $download_link = extract_value($data, 'download_link', '');
            $send_type = extract_value($data, 'send_type', '');
            $send_value = extract_value($data, 'send_value', '');

            if ($send_type == 'mobile') {
                $postfields = array('mobile' => $send_value,
                    'message' => "Download funtush11 app to this link " . $download_link,
                );
                $status = $this->smsSend($postfields);
            } else if ($send_type == 'email') {

                $html = array();
                $html['link'] = $download_link;
                $html['logo'] = base_url() . getConfig('site_logo');
                $html['site'] = getConfig('site_name');
                //$html['user'] = ucwords($user_name);
                $email_template = $this->load->view('email/download_app_tpl', $html, true);
                $status = send_mail($email_template, '[' . getConfig('site_name') . '] Download App', $send_value, getConfig('admin_email'));
            }
            if ($status) {
                $return['status'] = 1;
                $return['message'] = 'Link Send Successfully';
            } else {
                $return['status'] = 0;
                $return['message'] = 'Link failed to send';
            }
        }
        $this->response($return);
    }

    function change_user_team_name_post() {

        $return['code'] = 200;
        $data = $this->input->post();

        $this->form_validation->set_rules('login_session_key', 'Login session key', 'trim|required|callback__validate_login_session_key');
        $this->form_validation->set_rules('team_name', 'Team Name', 'trim|required|min_length[6]|max_length[20]');

        if ($this->form_validation->run() == FALSE) {
            $error = $this->form_validation->rest_first_error_string();
            $return['status'] = 0;
            $return['message'] = $error;
        } else {

            $user_id = $this->user_details->id;
            $team_name = extract_value($data, 'team_name', '');

            if (preg_match("/^[0-9a-zA-Z]{6,}$/", $team_name)) {

                $option = array(
                    'table' => 'users',
                    'select' => 'team_code',
                    'where' => array('team_code' => $team_name),
                    'single' => true
                );
                $exitCode = $this->common_model->customGet($option);
                if (empty($exitCode)) {
                    //    $option = array(
                    //    'table' => 'users',
                    //    'select' => 'team_code_update_status',
                    //    'where' => array('id' => $user_id),
                    //    'single' => true
                    //  );
                    // $alreadyUpdate = $this->common_model->customGet($option);
                    // if($alreadyUpdate->team_code_update_status != 1)
                    // {

                    $options = array(
                        'table' => 'users',
                        'data' => array(
                            'team_code' => $team_name,
                        //'team_code_update_status' => 1
                        ),
                        'where' => array('id' => $user_id)
                    );
                    $updateTeam = $this->common_model->customUpdate($options);
                    if ($updateTeam) {
                        $return['status'] = 1;
                        $return['message'] = 'Team name changed successfully';
                    } else {

                        $return['status'] = 0;
                        $return['message'] = 'Team name failed to changed';
                    }
                    // }else{
                    //     $return['status'] = 0;
                    //     $return['message'] = 'Your have already changed your team name';
                    // }
                } else {
                    $return['status'] = 0;
                    $return['message'] = 'This team name is already used';
                }
            } else {
                $return['status'] = 0;
                $return['message'] = 'Team name contain minimum 6 charcters and no special characters allowed!';
            }
        }
        $this->response($return);
    }

    function get_dynamic_data_post() {
        $return['code'] = 200;
        $data = $this->input->post();

        $address = getConfig('contact_address');
        $phone = getConfig('phone_number');
        $email = getConfig('email');
        $facebook_link = getConfig('facebook_link');
        $twitter_link = getConfig('twitter_link');
        $vimeo_link = getConfig('instagram_link');
        $linked_link = getConfig('linked_link');

        $option = array(
            'table' => 'front_background_images',
            'select' => '*',
            'single' => true
        );
        $backImages = $this->common_model->customGet($option);
        $how_to_play_image = "";
        $testimonial_image = "";
        $home_image = "";

        if (!empty($backImages)) {
            $how_to_play_image = null_checker($backImages->how_to_play_image);
            $testimonial_image = null_checker($backImages->testimonial_image);
            $home_image = null_checker($backImages->home_image);

            if (!empty($how_to_play_image)) {
                $how_to_play_image = base_url() . $backImages->how_to_play_image;
            }if (!empty($testimonial_image)) {
                $testimonial_image = base_url() . $backImages->testimonial_image;
            }if (!empty($home_image)) {
                $home_image = base_url() . $backImages->home_image;
            }
        }

        $temp['contact_address'] = $address;
        $temp['contact_phone'] = $phone;
        $temp['contact_email'] = $email;
        $temp['facebook_link'] = $facebook_link;
        $temp['twitter_link'] = $twitter_link;
        $temp['vimeo_link'] = $vimeo_link;
        $temp['linkdin_link'] = $linked_link;
        $temp['how_to_play_image'] = $how_to_play_image;
        $temp['testimonial_image'] = $testimonial_image;
        $temp['home_image'] = $home_image;


        $return['response'] = $temp;
        $return['status'] = 1;
        $return['message'] = 'Data Found Successfully';

        $this->response($return);
    }

    function favorite_team_list_post() {
        $return['code'] = 200;
        $data = $this->input->post();

        $this->form_validation->set_rules('login_session_key', 'Login session key', 'trim|required|callback__validate_login_session_key');
        $this->form_validation->set_rules('game_type', 'Game Type', 'trim|required');

        if ($this->form_validation->run() == FALSE) {
            $error = $this->form_validation->rest_first_error_string();
            $return['status'] = 0;
            $return['message'] = $error;
        } else {
            //$user_id = $this->user_details->id;
            $teamData = array();
            $game_type = extract_value($data, 'game_type', '');

            if ($game_type == 1) {
                $option = array(
                    'table' => 'favorite_teams as ft',
                    'select' => 'ft.id,ft.team_id,team.team_name,team.team_flag',
                    'join' => array('team' => 'team.id=ft.team_id'),
                    'where' => array('ft.delete_status' => 0),
                    'order' => array('ft.id' => 'DESC'),
                );
            } else if ($game_type == 2) {
                $option = array(
                    'table' => 'football_favorite_teams as ft',
                    'select' => 'ft.id,ft.team_id,team.name as team_name,team.image as team_flag',
                    'join' => array('football_teams as team' => 'team.team_id=ft.team_id'),
                    'where' => array('ft.delete_status' => 0),
                    'order' => array('ft.id' => 'DESC'),
                );
            } else {
                $option = array(
                    'table' => 'kabaddi_favorite_teams as ft',
                    'select' => 'ft.id,ft.team_id,team.team_name,team.team_flag',
                    'join' => array('kabaddi_team as team' => 'team.season_team_key=ft.team_id'),
                    'where' => array('ft.delete_status' => 0),
                    'order' => array('ft.id' => 'DESC'),
                );
            }

            $teamList = $this->common_model->customGet($option);

            if (!empty($teamList)) {
                foreach ($teamList as $team) {

                    $team_flag = base_url() . "backend_asset/images/india1.png";
                    if (!empty($team->team_flag)) {

                        $parse = parse_url($team->team_flag);
                        if (isset($parse['host'])) {

                            $team_flag = $team->team_flag;
                        } else {
                            $team_flag = base_url() . $team->team_flag;
                        }
                    }
                    $temp['id'] = $team->id;
                    $temp['team_id'] = $team->team_id;
                    $temp['team_name'] = $team->team_name;
                    $temp['team_flag'] = $team_flag;

                    $teamData[] = $temp;
                }
                $return['response'] = $teamData;
                $return['status'] = 1;
                $return['message'] = 'Teams Found Successfully';
            } else {
                $return['status'] = 1;
                $return['message'] = 'Teams not Found';
            }
        }
        $this->response($return);
    }

    function subscribe_newsletter_post() {
        $return['code'] = 200;
        $data = $this->input->post();
        $this->form_validation->set_rules('email', 'Email', 'required|trim');

        if ($this->form_validation->run() == FALSE) {
            $error = $this->form_validation->rest_first_error_string();
            $return['status'] = 0;
            $return['message'] = $error;
        } else {
            $email = extract_value($data, 'email', '');

            $option = array(
                'table' => 'newsletter_subscription',
                'select' => 'email',
                'where' => array('email' => $email),
                'single' => true
            );
            $emailExist = $this->common_model->customGet($option);
            if (empty($emailExist)) {

                $options = array(
                    'table' => 'newsletter_subscription',
                    'data' => array(
                        'email' => $email,
                        'subscribe_status' => 1,
                        'created_date' => datetime()
                    )
                );
                $status = $this->common_model->customInsert($options);
                if ($status) {
                    $return['status'] = 1;
                    $return['message'] = 'Subscription submit successfully';
                } else {
                    $return['status'] = 0;
                    $return['message'] = 'Subscription not submitted';
                }
            } else {
                $return['status'] = 0;
                $return['message'] = 'Email already exists';
            }
        }
        $this->response($return);
    }

    function send_invoice_post() {
        $data = $this->input->post();
        $return['code'] = 200;
        //$return['response'] = new stdClass();
        $this->form_validation->set_rules('login_session_key', 'Login session key', 'required|trim');
        $this->form_validation->set_rules('contest_id', 'Contest id', 'required|trim');
        $this->form_validation->set_rules('sports_type', 'Sports Type', 'required|trim|in_list[CRICKET,FOOTBALL,KABADDI]');
        if ($this->form_validation->run() == FALSE) {
            $error = $this->form_validation->rest_first_error_string();
            $return['status'] = 0;
            $return['message'] = $error;
        } else {
            /* Return Response */
            $response = array();
            $account = array();
            $walletData = array();
            $chipData = array();
            $usersArr = array();
            $user_id = $this->user_details->id;
            $contest_id = extract_value($data, 'contest_id', '');
            $sports_type = strtoupper($this->input->post('sports_type'));
            $contestDetails = array();
            $GSTNO = "";
            $GSTADDRESS = "";
            $COMPANYNAME = "";
            if ($sports_type == "FOOTBALL") {
                $GSTNO = "06AAGCD8250Q1Z4";
                $GSTADDRESS = "DA.FA11 ENTERTAINMENT PRIVATE LIMITED. 8TH FLOOR, OFFICE NO.-826,TOWER-B3, SPAZE I-TECH PARK, SECTOR-49, GURGAON, Gurgaon, Haryana, 122018";
                $COMPANYNAME = "DA.FA11 ENTERTAINMENT PRIVATE LIMITED";
                $sqlQueryExecute = "SELECT
                        `users`.`first_name`,
                        `users`.`state`,
                        `users`.`email`,
                        `users`.`street`,
                        `users`.`city`,
                        `contest`.`id`,
                        `contest`.`total_winning_amount`,
                        `contest`.`contest_size`,
                        `contest`.`team_entry_fee`,
                        `contest`.`admin_percentage`,
                        `transactions_history`.`orderId`,
                        join_contest.joining_date
                      FROM
                        `football_contest` as contest
                      JOIN
                        `football_join_contest` as join_contest ON `join_contest`.`contest_id` = `contest`.`id`
                      JOIN
                        `users` ON `users`.`id` = `join_contest`.`user_id`
                      JOIN
                        `transactions_history` ON `transactions_history`.`contest_id` = `join_contest`.`contest_id`
                        AND `transactions_history`.`user_id` = `join_contest`.`user_id`
                      WHERE
                        `contest`.`id` = $contest_id AND `contest`.`match_type` = 1 AND `join_contest`.`user_id` = $user_id
                        AND `transactions_history`.`pay_type` = 'JOINCONTEST' AND `transactions_history`.`sports_type` = 2";
                $contestDetails = $this->common_model->customQuery($sqlQueryExecute, true);
            } else if ($sports_type == "KABADDI") {
                $GSTNO = "06AAGCD8250Q1Z4";
                $GSTADDRESS = "DA.FA11 ENTERTAINMENT PRIVATE LIMITED. 8TH FLOOR, OFFICE NO.-826,TOWER-B3, SPAZE I-TECH PARK, SECTOR-49, GURGAON, Gurgaon, Haryana, 122018";
                $COMPANYNAME = "DA.FA11 ENTERTAINMENT PRIVATE LIMITED";
                $sqlQueryExecute = "SELECT
                        `users`.`first_name`,
                         `users`.`state`,
                        `users`.`email`,
                        `users`.`street`,
                        `users`.`city`,
                        `contest`.`id`,
                        `contest`.`total_winning_amount`,
                        `contest`.`contest_size`,
                        `contest`.`team_entry_fee`,
                        `contest`.`admin_percentage`,
                        `transactions_history`.`orderId`,
                         join_contest.joining_date
                      FROM
                        `kabaddi_contest` as contest
                      JOIN
                        `kabaddi_join_contest` as join_contest ON `join_contest`.`contest_id` = `contest`.`id`
                      JOIN
                        `users` ON `users`.`id` = `join_contest`.`user_id`
                      JOIN
                        `transactions_history` ON `transactions_history`.`contest_id` = `join_contest`.`contest_id`
                        AND `transactions_history`.`user_id` = `join_contest`.`user_id`
                      WHERE
                        `contest`.`id` = $contest_id AND `contest`.`match_type` = 1 AND `join_contest`.`user_id` = $user_id
                        AND `transactions_history`.`pay_type` = 'JOINCONTEST' AND `transactions_history`.`sports_type` = 3";
                $contestDetails = $this->common_model->customQuery($sqlQueryExecute, true);
            } else if ($sports_type == "CRICKET") {
                $GSTNO = "06AADCF7505R1Z7";
                $GSTADDRESS = "Funtush.Eleven India Private Limited. 8TH FLOOR, OFFICE NO.-826, B3, SPAZE I-TECH PARK, SECTOR-49, GURUGRAM, Gurgaon, Haryana, 122018";
                $COMPANYNAME = "Funtush.Eleven India Private Limited";
                $sqlQueryExecute = "SELECT
                        `users`.`first_name`,
                         `users`.`state`,
                        `users`.`email`,
                        `users`.`street`,
                        `users`.`city`,
                        `contest`.`id`,
                        `contest`.`total_winning_amount`,
                        `contest`.`contest_size`,
                        `contest`.`team_entry_fee`,
                        `contest`.`admin_percentage`,
                        `transactions_history`.`orderId`,
                         join_contest.joining_date
                      FROM
                        `contest`
                      JOIN
                        `join_contest` ON `join_contest`.`contest_id` = `contest`.`id`
                      JOIN
                        `users` ON `users`.`id` = `join_contest`.`user_id`
                      JOIN
                        `transactions_history` ON `transactions_history`.`contest_id` = `join_contest`.`contest_id`
                        AND `transactions_history`.`user_id` = `join_contest`.`user_id`
                      WHERE
                        `contest`.`id` = $contest_id AND `contest`.`match_type` = 1 AND `join_contest`.`user_id` = $user_id
                        AND `transactions_history`.`pay_type` = 'JOINCONTEST' AND `transactions_history`.`sports_type` = 1";
                $contestDetails = $this->common_model->customQuery($sqlQueryExecute, true);
            }
            if (!empty($contestDetails)) {
                $name = $contestDetails->first_name;
                $address = $contestDetails->street . " " . $contestDetails->city;
                $email = $contestDetails->email;
                $contestID = $contestDetails->id;
                $contestOrderId = $contestDetails->orderId;
                $contestTotalWinningAmount = $contestDetails->total_winning_amount;
                $contestSize = $contestDetails->contest_size;
                $contestTeamEntryFee = $contestDetails->team_entry_fee;
                $contestAdminPercentage = $contestDetails->admin_percentage;

                /* Calculate admin fee */
                $adminFee = ($contestTeamEntryFee * $contestAdminPercentage) / 100;

                /* calculate prize pool */
                $prizePool = $contestTeamEntryFee - $adminFee;

                /* calculate GST */
                $platformFee = ($adminFee * 100) / 118;

                $IGST = $GST = ($platformFee * 18) / 100;

                if ($contestDetails->state == 14) {
                    $SGST = $CGST = ($GST / 2);
                } else {
                    $SGST = $CGST = 0;
                }
                $html = array();
                $html['state'] = $contestDetails->state;
                $html['GSTNO'] = $GSTNO;
                $html['GSTADDRESS'] = $GSTADDRESS;
                $html['COMPANYNAME'] = $COMPANYNAME;
                $html['name'] = $name;
                $html['address'] = $address;
                $html['entryFee'] = $contestTeamEntryFee;
                $html['prizePool'] = $prizePool;
                $html['IGST'] = round($IGST, 2);
                $html['SGST'] = round($SGST, 2);
                $html['CGST'] = round($CGST, 2);
                $html['orderId'] = $contestOrderId;
                $html['adminFee'] = round($adminFee, 2);
                $html['platformFee'] = round($platformFee, 2);
                $html['logo'] = base_url() . getConfig('site_logo');
                $html['site'] = getConfig('site_name');
                $html['date'] = date('d/m/Y', strtotime($contestDetails->joining_date));
                $html['entryFeeWord'] = $this->getIndianCurrency($contestTeamEntryFee);
                $html['prizePoolWord'] = $this->getIndianCurrency($prizePool);
                $html['platformFeeTotalWord'] = $this->getIndianCurrency(round($IGST, 2) + round($platformFee, 2));
                $email_template = $this->load->view('email/invoice_tpl', $html, true);
                /* $html2pdf = new Html2Pdf();
                  $html2pdf->writeHTML($email_template);
                  $html2pdf->output();
                  exit; */
                $sent = send_mail($email_template, '' . getConfig('site_name') . ' Invoice', $email, getConfig('admin_email'));
                if ($sent) {
                    $return['status'] = 1;
                    $return['message'] = 'Transaction invoice has been sent to your registered email id';
                } else {
                    $return['status'] = 0;
                    $return['message'] = 'Mail not sent, Please try again';
                }
            } else {
                $return['status'] = 0;
                $return['message'] = 'Records not found for this contest';
            }
        }
        $this->response($return);
    }

    function getIndianCurrency($number) {
        $number = (float) $number;
        $decimal = round($number - ($no = floor($number)), 2) * 100;
        $hundred = null;
        $digits_length = strlen($no);
        $i = 0;
        $str = array();
        $words = array(0 => '', 1 => 'one', 2 => 'two',
            3 => 'three', 4 => 'four', 5 => 'five', 6 => 'six',
            7 => 'seven', 8 => 'eight', 9 => 'nine',
            10 => 'ten', 11 => 'eleven', 12 => 'twelve',
            13 => 'thirteen', 14 => 'fourteen', 15 => 'fifteen',
            16 => 'sixteen', 17 => 'seventeen', 18 => 'eighteen',
            19 => 'nineteen', 20 => 'twenty', 30 => 'thirty',
            40 => 'forty', 50 => 'fifty', 60 => 'sixty',
            70 => 'seventy', 80 => 'eighty', 90 => 'ninety');
        $digits = array('', 'hundred', 'thousand', 'lakh', 'crore');
        while ($i < $digits_length) {
            $divider = ($i == 2) ? 10 : 100;
            $number = floor($no % $divider);
            $no = floor($no / $divider);
            $i += $divider == 10 ? 1 : 2;
            if ($number) {
                $plural = (($counter = count($str)) && $number > 9) ? 's' : null;
                $hundred = ($counter == 1 && $str[0]) ? ' and ' : null;
                $str [] = ($number < 21) ? $words[$number] . ' ' . $digits[$counter] . $plural . ' ' . $hundred : $words[floor($number / 10) * 10] . ' ' . $words[$number % 10] . ' ' . $digits[$counter] . $plural . ' ' . $hundred;
            } else $str[] = null;
        }
        $Rupees = implode('', array_reverse($str));
        $paise = ($decimal) ? " " . ($words[$decimal / 10] . " " . $words[$decimal % 10]) . ' Paise' : '';
        return ($Rupees ? $Rupees . 'Rupees ' : '') . $paise;
    }

    function getCouponOffers_post() {

        $data = $this->input->post();
        $return['code'] = 200;

        $this->form_validation->set_rules('login_session_key', 'Login session key', 'required|trim');
        if ($this->form_validation->run() == FALSE) {
            $error = $this->form_validation->rest_first_error_string();
            $return['status'] = 0;
            $return['message'] = $error;
        } else {

            $currDate = date('Y-m-d');
            $offersArr = array();

            $option = array(
                'table' => 'coupons',
                'select' => 'coupons.id,cash_type,coupon_code,total_use_user,used_type,coupon_type,user_size,min_amount,max_amount,amount,percentage_in_amount,start_date,end_date,coupons.message',
                'where' => array('status' => 1, 'end_date>=' => $currDate, 'offer_section' => 1),
                'order' => array('id' => 'desc')
            );
            $offerList = $this->common_model->customGet($option);
            if (!empty($offerList)) {
                foreach ($offerList as $offers) {

                    $from_date = convertDate($offers->start_date);
                    $to_date = convertDate($offers->end_date);
                    if ($offers->coupon_type == 0) {
                        $message = "First " . $offers->user_size .
                                " lucky users will get up to " .
                                $offers->max_amount . " Rs cash in there wallet.";
                    } else if ($offers->coupon_type == 1) {
                        $message = "User Offer code " . $offers->coupon_code .
                                ". First " . $offers->user_size . " users will get up to " .
                                $offers->max_amount . " Rs cash on add money into your wallet.";
                    } else if ($offers->coupon_type == 2) {
                        $message = "Apply Offer code " . $offers->coupon_code .
                                ". First " . $offers->user_size . " users will get " .
                                $offers->amount . " Rs cash in there wallet.";
                    } else if ($offers->coupon_type == 3) {
                        $message = "Register and apply offer code " . $offers->coupon_code .
                                ". First " . $offers->user_size . " users will get " .
                                $offers->amount . " Rs cash in there wallet.";
                    } else if ($offers->coupon_type == 4) {
                        $sql = "SELECT * FROM custom_coupons WHERE coupon_id = $offers->id";
                        $couponsData = $this->common_model->customQuery($sql);
                        if (!empty($couponsData)) {
                            $getMessage = '';
                            $i = 0;
                            foreach ($couponsData as $value) {
                                $i++;
                                $getMessage .= round($value->add_amount) . ' get ' . round($value->get_amount);
                                if ($i != count($couponsData)) {
                                    $getMessage .= ', ';
                                }
                            }
                            $message = "Apply Offer code " . $offers->coupon_code .
                                    ". First " . $offers->user_size . " users will " .
                                    $getMessage . " Rs cash in there wallet.";
                        }
                    }
                    $description = "Hurry! Offer valid from " . $from_date . " to " . $to_date . "";
                    $subdescription = "Offer valid from " . $from_date . " to " . $to_date . ".User will receive cash bonus in there wallet. Terms and condition applied*.";


                    $temp['cash_type'] = null_checker($offers->cash_type);
                    $temp['coupon_code'] = null_checker($offers->coupon_code);
                    $temp['total_use_user'] = null_checker($offers->total_use_user);
                    $temp['used_type'] = null_checker($offers->used_type);
                    $temp['coupon_type'] = null_checker($offers->coupon_type);
                    $temp['user_size'] = null_checker($offers->user_size);
                    $temp['min_amount'] = null_checker($offers->min_amount);
                    $temp['max_amount'] = null_checker($offers->max_amount);
                    $temp['amount'] = null_checker($offers->amount);
                    $temp['percentage_in_amount'] = null_checker($offers->percentage_in_amount);
                    $temp['start_date'] = null_checker($offers->start_date);
                    $temp['end_date'] = null_checker($offers->end_date);
                    $temp['message'] = $offers->message;
                    $temp['description'] = $description;
                    $temp['subdescription'] = $subdescription;

                    $offersArr[] = $temp;
                }

                $return['status'] = 1;
                $return['response'] = $offersArr;
                $return['message'] = 'Offers list found successfully';
            } else {
                $return['status'] = 0;
                $return['message'] = 'Offers list not found';
            }
        }
        $this->response($return);
    }

    function withoutdeposit_offer_post() {

        $data = $this->input->post();
        $return['code'] = 200;

        $this->form_validation->set_rules('login_session_key', 'Login session key', 'required|trim');
        $this->form_validation->set_rules('coupon_code', 'Coupon Code', 'required|trim');
        if ($this->form_validation->run() == FALSE) {
            $error = $this->form_validation->rest_first_error_string();
            $return['status'] = 0;
            $return['message'] = $error;
        } else {

            $currDate = date('Y-m-d');
            $user_id = $this->user_details->id;
            $coupon_code = extract_value($data, 'coupon_code', '');

            $option = array(
                'table' => 'coupons',
                'select' => 'coupon_code,user_size,total_use_user,cash_type,amount,id,used_type',
                'where' => array('coupon_code' => $coupon_code, 'coupon_type' => 2, 'end_date >=' => $currDate, 'start_date <=' => $currDate, 'status' => 1),
                'single' => true
            );
            $isCouponCode = $this->common_model->customGet($option);

            if (!empty($isCouponCode)) {
                if ($isCouponCode->user_size > $isCouponCode->total_use_user) {

                    $options = array(
                        'table' => 'coupons_user',
                        'select' => 'id',
                        'where' => array('user_id' => $user_id, 'coupns_id' => $isCouponCode->id)
                    );
                    $used_coupon = $this->common_model->customCount($options);

                    if ($isCouponCode->used_type > $used_coupon) {

                        $cash_type = $isCouponCode->cash_type;
                        $amount = $isCouponCode->amount;
                        $total_use_user = $isCouponCode->total_use_user;
                        $bonus_amount = 0;
                        $deposited_amount = 0;
                        $total_balance = 0;

                        $options = array(
                            'table' => 'wallet',
                            'select' => '*',
                            'where' => array('user_id' => $user_id),
                            'single' => true
                        );
                        $walletData = $this->common_model->customGet($options);
                        if (!empty($walletData)) {
                            $bonus_amount = $walletData->cash_bonus_amount;
                            $deposited_amount = $walletData->deposited_amount;
                            $total_balance = $walletData->total_balance;
                            if ($cash_type == 1) {
                                $bonus_amount = $bonus_amount + $amount;
                                $transaction_type = 'BONUS';
                            } else {
                                $deposited_amount = $deposited_amount + $amount;
                                $transaction_type = 'CASH';
                            }

                            $option = array(
                                'table' => 'wallet',
                                'data' => array(
                                    'deposited_amount' => $deposited_amount,
                                    'cash_bonus_amount' => $bonus_amount,
                                    'total_balance' => $total_balance + $amount,
                                    'update_date' => datetime()
                                ),
                                'where' => array('user_id' => $user_id)
                            );
                            $updataData = $this->common_model->customUpdate($option);
                        } else {

                            if ($cash_type == 1) {
                                $bonus_amount = $bonus_amount + $amount;
                                $transaction_type = 'BONUS';
                            } else {
                                $deposited_amount = $deposited_amount + $amount;
                                $transaction_type = 'CASH';
                            }

                            $option = array(
                                'table' => 'wallet',
                                'data' => array(
                                    'user_id' => $user_id,
                                    'deposited_amount' => $deposited_amount,
                                    'cash_bonus_amount' => $bonus_amount,
                                    'total_balance' => $total_balance + $amount,
                                    'create_date' => datetime()
                                )
                            );

                            $updataData = $this->common_model->customInsert($option);
                        }

                        $total_use_user = $total_use_user + 1;

                        $option = array(
                            'table' => 'coupons',
                            'data' => array(
                                'total_use_user' => $total_use_user,
                            ),
                            'where' => array('id' => $isCouponCode->id)
                        );
                        $this->common_model->customUpdate($option);

                        $option1 = array(
                            'table' => 'coupons_user',
                            'data' => array(
                                'user_id' => $user_id,
                                'coupns_id' => $isCouponCode->id,
                                'created_date' => datetime()
                            )
                        );
                        $this->common_model->customInsert($option1);

                        $orderId = commonUniqueCode();
                        $options = array(
                            'table' => 'transactions_history',
                            'data' => array(
                                'user_id' => $user_id,
                                'match_id' => 0,
                                'contest_id' => 0,
                                'opening_balance' => $total_balance,
                                'cr' => $amount,
                                'available_balance' => $total_balance + $amount,
                                'message' => "coupon offer",
                                'datetime' => date('Y-m-d H:i:s'),
                                'transaction_type' => $transaction_type,
                                'pay_type' => "WITHOUT DEPOSIT OFFER",
                                'sports_type' => 0,
                                'orderId' => $orderId,
                                'total_wallet_balance' => $total_balance + $amount,
                                'wallet_opening_balance' => $total_balance,
                                'coupons_id' => $isCouponCode->id
                            )
                        );
                        $this->common_model->customInsert($options);

                        $return['status'] = 1;
                        $return['message'] = 'Coupon applied successfully!';
                    } else {

                        $return['status'] = 0;
                        $return['message'] = 'Your limit has been expired for this coupon!';
                    }
                } else {

                    $return['status'] = 0;
                    $return['message'] = 'Coupon limit has been expired!';
                }
            } else {
                $return['status'] = 0;
                $return['message'] = 'Invalid coupon code!';
            }
        }
        $this->response($return);
    }

    function validate_coupon_code_post() {

        $data = $this->input->post();
        $return['code'] = 200;

        $this->form_validation->set_rules('login_session_key', 'Login session key', 'required|trim');
        $this->form_validation->set_rules('coupon_code', 'Coupon Code', 'required|trim');
        if ($this->form_validation->run() == FALSE) {
            $error = $this->form_validation->rest_first_error_string();
            $return['status'] = 0;
            $return['message'] = $error;
        } else {

            $currDate = date('Y-m-d');
            $user_id = $this->user_details->id;
            $coupon_code = extract_value($data, 'coupon_code', '');
            if ($this->user_details->email_verify == 1 && $this->user_details->verify_mobile == 1) {
                if (!empty($coupon_code)) {
                    $option = array(
                        'table' => 'coupons',
                        'select' => 'coupon_code,user_size,total_use_user,cash_type,amount,id,used_type,min_amount,max_amount,percentage_in_amount',
                        'where' => array('coupon_code' => $coupon_code, 'end_date >=' => $currDate, 'start_date <=' => $currDate, 'status' => 1),
                        'where_in' => array('coupon_type' => array(1, 4)),
                        'single' => true
                    );
                    $isCouponCode = $this->common_model->customGet($option);
                    //print_r($this->db->last_query());die;
                    if (!empty($isCouponCode)) {
                        // if($amount >= $isCouponCode->min_amount && $amount <= $isCouponCode->max_amount)
                        // {

                        if ($isCouponCode->user_size > $isCouponCode->total_use_user) {

                            $option = array(
                                'table' => 'coupons_user',
                                'select' => 'user_id',
                                'where' => array('user_id' => $user_id, 'coupns_id' => $isCouponCode->id),
                                'single' => true
                            );
                            $totalusedcoupon = $this->common_model->customCount($option);

                            if ($isCouponCode->used_type > $totalusedcoupon) {
                                $return['status'] = 1;
                                $return['message'] = 'Your coupon is valid';
                            } else {
                                $return['status'] = 0;
                                $return['message'] = 'Your limit has been expired for this coupon!';
                            }
                        } else {

                            $return['status'] = 0;
                            $return['message'] = 'Coupon limit has been expired!';
                        }
                        // }else{
                        //        $return['status'] = 0;
                        //        $return['message'] = 'Invalid coupon code!';
                        // }
                    } else {

                        $return['status'] = 0;
                        $return['message'] = 'Invalid coupon code!';
                    }
                }
            } else {
                $return['status'] = 0;
                $return['message'] = 'Please Verify User email and mobile number!';
            }
        }
        $this->response($return);
    }

    function contact_banner_post() {

        $data = $this->input->post();
        $return['code'] = 200;

        $option = array(
            'table' => 'cms',
            'select' => 'thumbnail_image',
            'where' => array('page_id' => 'contact'),
            'single' => true
        );
        $getContactBanner = $this->common_model->customGet($option);
        if (!empty($getContactBanner)) {
            $image = null_checker($getContactBanner->thumbnail_image);
            if (!empty($image)) {
                $image = base_url() . "uploads/cms/" . $image;
            }
            $temp['banner_image'] = $image;

            $return['response'] = $temp;
            $return['status'] = 1;
            $return['message'] = 'Contact Banner found successfully!';
        } else {
            $return['status'] = 1;
            $return['message'] = 'Banner not found!';
        }
        $this->response($return);
    }

}

/* End of file User.php */
/* Location: ./application/controllers/api/v1/User.php */
?>