<?php

defined('BASEPATH') OR exit('No direct script access allowed');
require APPPATH . 'third_party/razorpay-php/Razorpay.php';
use Razorpay\Api\Api;

/**
 * This Class used as REST API for Payment
 * @package   CodeIgniter
 * @category  Controller
 * @author    MobiwebTech Team
 */
class Pay extends Common_API_Controller {

    public $PAYTM_ENVIRONMENT = "";
    public $PAYTM_MERCHANT_KEY = "";
    public $PAYTM_MERCHANT_MID = "";
    public $PAYTM_MERCHANT_WEBSITE = "";
    public $PAYTM_DOMAIN = "";
    public $PAYTM_REFUND_URL = "";
    public $PAYTM_STATUS_QUERY_URL = "";
    public $PAYTM_STATUS_QUERY_NEW_URL = "";
    public $PAYTM_TXN_URL = "";
    public $PAYUMONEY_MERCHANT_KEY = "";
    public $PAYUMONEY_SALT = "";
    public $PAYUMONEY_PAYU_BASE_URL = "";
    public $PAYUMONEY_SUCCESS_URL = "";
    public $PAYUMONEY_FAILURE_URL = "";

    function __construct() {
        parent::__construct();
        $this->lang->load('en', 'english');
        $this->PAYTM_ENVIRONMENT = (getConfig('paytm_environment') != "") ? getConfig('paytm_environment') : "TEST";
        $this->PAYTM_MERCHANT_KEY = getConfig('paytm_merchant_key');
        $this->PAYTM_MERCHANT_MID = getConfig('paytm_merchant_mid');
        $this->PAYTM_MERCHANT_WEBSITE = getConfig('paytm_merchant_website');
        $this->PAYTM_DOMAIN = ($this->PAYTM_ENVIRONMENT == "PROD") ? "secure.paytm.in" : "pguat.paytm.com";
        $this->PAYTM_REFUND_URL = 'https://' . $this->PAYTM_DOMAIN . '/oltp/HANDLER_INTERNAL/REFUND';
        $this->PAYTM_STATUS_QUERY_URL = 'https://' . $this->PAYTM_DOMAIN . '/oltp/HANDLER_INTERNAL/TXNSTATUS';
        $this->PAYTM_STATUS_QUERY_NEW_URL = 'https://' . $this->PAYTM_DOMAIN . '/oltp/HANDLER_INTERNAL/getTxnStatus';
        $this->PAYTM_TXN_URL = 'https://' . $this->PAYTM_DOMAIN . '/oltp-web/processTransaction';
        /** Payumoney configuration * */
        //$this->PAYUMONEY_MERCHANT_KEY = "oraWtWvA";
        $this->PAYUMONEY_MERCHANT_KEY = "sA1IrGTq";
        //$this->PAYUMONEY_SALT = "zAMy5x3y2w";
        $this->PAYUMONEY_SALT = "ZamEmtqmiA";
        //$this->PAYUMONEY_PAYU_BASE_URL = "https://sandboxsecure.payu.in"; // sandbox
        $this->PAYUMONEY_PAYU_BASE_URL = "https://secure.payu.in";
        $this->PAYUMONEY_SUCCESS_URL = base_url() . 'payment/PayUMoneyPayResponseSuccess';
        $this->PAYUMONEY_FAILURE_URL = base_url() . 'payment/PayUMoneyPayResponseFailure';
    }

    function encrypt_e($input, $ky) {
        $key = $ky;
        $size = mcrypt_get_block_size(MCRYPT_RIJNDAEL_128, 'cbc');
        $input = $this->pkcs5_pad_e($input, $size);
        $td = mcrypt_module_open(MCRYPT_RIJNDAEL_128, '', 'cbc', '');
        $iv = "@@@@&&&&####$$$$";
        mcrypt_generic_init($td, $key, $iv);
        $data = mcrypt_generic($td, $input);
        mcrypt_generic_deinit($td);
        mcrypt_module_close($td);
        $data = base64_encode($data);
        return $data;
    }

    function decrypt_e($crypt, $ky) {

        $crypt = base64_decode($crypt);
        $key = $ky;
        $td = mcrypt_module_open(MCRYPT_RIJNDAEL_128, '', 'cbc', '');
        $iv = "@@@@&&&&####$$$$";
        mcrypt_generic_init($td, $key, $iv);
        $decrypted_data = mdecrypt_generic($td, $crypt);
        mcrypt_generic_deinit($td);
        mcrypt_module_close($td);
        $decrypted_data = $this->pkcs5_unpad_e($decrypted_data);
        $decrypted_data = rtrim($decrypted_data);
        return $decrypted_data;
    }

    function pkcs5_pad_e($text, $blocksize) {
        $pad = $blocksize - (strlen($text) % $blocksize);
        return $text . str_repeat(chr($pad), $pad);
    }

    function pkcs5_unpad_e($text) {
        $pad = ord($text{strlen($text) - 1});
        if ($pad > strlen($text))
            return false;
        return substr($text, 0, -1 * $pad);
    }

    function generateSalt_e($length) {
        $random = "";
        srand((double) microtime() * 1000000);

        $data = "AbcDE123IJKLMN67QRSTUVWXYZ";
        $data .= "aBCdefghijklmn123opq45rs67tuv89wxyz";
        $data .= "0FGH45OP89";

        for ($i = 0; $i < $length; $i++) {
            $random .= substr($data, (rand() % (strlen($data))), 1);
        }

        return $random;
    }

    function checkString_e($value) {
        if ($value == 'null')
            $value = '';
        return $value;
    }

    function getChecksumFromArray($arrayList, $key, $sort = 1) {
        if ($sort != 0) {
            ksort($arrayList);
        }
        $str = $this->getArray2Str($arrayList);
        $salt = $this->generateSalt_e(4);
        $finalString = $str . "|" . $salt;
        $hash = hash("sha256", $finalString);
        $hashString = $hash . $salt;
        $checksum = $this->encrypt_e($hashString, $key);
        return $checksum;
    }

    function getChecksumFromString($str, $key) {

        $salt = $this->generateSalt_e(4);
        $finalString = $str . "|" . $salt;
        $hash = hash("sha256", $finalString);
        $hashString = $hash . $salt;
        $checksum = $this->encrypt_e($hashString, $key);
        return $checksum;
    }

    function verifychecksum_e($arrayList, $key, $checksumvalue) {
        $arrayList = $this->removeCheckSumParam($arrayList);
        ksort($arrayList);
        $str = $this->getArray2StrForVerify($arrayList);
        $paytm_hash = $this->decrypt_e($checksumvalue, $key);
        $salt = substr($paytm_hash, -4);

        $finalString = $str . "|" . $salt;

        $website_hash = hash("sha256", $finalString);
        $website_hash .= $salt;

        $validFlag = "FALSE";
        if ($website_hash == $paytm_hash) {
            $validFlag = "TRUE";
        } else {
            $validFlag = "FALSE";
        }
        return $validFlag;
    }

    function verifychecksum_eFromStr($str, $key, $checksumvalue) {
        $paytm_hash = $this->decrypt_e($checksumvalue, $key);
        $salt = substr($paytm_hash, -4);

        $finalString = $str . "|" . $salt;

        $website_hash = hash("sha256", $finalString);
        $website_hash .= $salt;

        $validFlag = "FALSE";
        if ($website_hash == $paytm_hash) {
            $validFlag = "TRUE";
        } else {
            $validFlag = "FALSE";
        }
        return $validFlag;
    }

    function getArray2Str($arrayList) {
        $findme = 'REFUND';
        $findmepipe = '|';
        $paramStr = "";
        $flag = 1;
        foreach ($arrayList as $key => $value) {
            $pos = strpos($value, $findme);
            $pospipe = strpos($value, $findmepipe);
            if ($pos !== false || $pospipe !== false) {
                continue;
            }

            if ($flag) {
                $paramStr .= $this->checkString_e($value);
                $flag = 0;
            } else {
                $paramStr .= "|" . $this->checkString_e($value);
            }
        }
        return $paramStr;
    }

    function getArray2StrForVerify($arrayList) {
        $paramStr = "";
        $flag = 1;
        foreach ($arrayList as $key => $value) {
            if ($flag) {
                $paramStr .= $this->checkString_e($value);
                $flag = 0;
            } else {
                $paramStr .= "|" . $this->checkString_e($value);
            }
        }
        return $paramStr;
    }

    function redirect2PG($paramList, $key) {
        $hashString = $this->getchecksumFromArray($paramList);
        $checksum = $this > encrypt_e($hashString, $key);
    }

    function removeCheckSumParam($arrayList) {
        if (isset($arrayList["CHECKSUMHASH"])) {
            unset($arrayList["CHECKSUMHASH"]);
        }
        return $arrayList;
    }

    function getTxnStatus($requestParamList) {
        return $this->callAPI(PAYTM_STATUS_QUERY_URL, $requestParamList);
    }

    function getTxnStatusNew($requestParamList) {
        return $this->callNewAPI(PAYTM_STATUS_QUERY_NEW_URL, $requestParamList);
    }

    function initiateTxnRefund($requestParamList) {
        $CHECKSUM = $this->getRefundChecksumFromArray($requestParamList, PAYTM_MERCHANT_KEY, 0);
        $requestParamList["CHECKSUM"] = $CHECKSUM;
        return $this->callAPI(PAYTM_REFUND_URL, $requestParamList);
    }

    function callAPI($apiURL, $requestParamList) {
        $jsonResponse = "";
        $responseParamList = array();
        $JsonData = json_encode($requestParamList);
        $postData = 'JsonData=' . urlencode($JsonData);
        $ch = curl_init($apiURL);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/json',
            'Content-Length: ' . strlen($postData))
        );
        $jsonResponse = curl_exec($ch);
        $responseParamList = json_decode($jsonResponse, true);
        return $responseParamList;
    }

    function callNewAPI($apiURL, $requestParamList) {
        $jsonResponse = "";
        $responseParamList = array();
        $JsonData = json_encode($requestParamList);
        $postData = 'JsonData=' . urlencode($JsonData);
        $ch = curl_init($apiURL);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/json',
            'Content-Length: ' . strlen($postData))
        );
        $jsonResponse = curl_exec($ch);
        $responseParamList = json_decode($jsonResponse, true);
        return $responseParamList;
    }

    function getRefundChecksumFromArray($arrayList, $key, $sort = 1) {
        if ($sort != 0) {
            ksort($arrayList);
        }
        $str = $this->getRefundArray2Str($arrayList);
        $salt = $this->generateSalt_e(4);
        $finalString = $str . "|" . $salt;
        $hash = hash("sha256", $finalString);
        $hashString = $hash . $salt;
        $checksum = $this->encrypt_e($hashString, $key);
        return $checksum;
    }

    function getRefundArray2Str($arrayList) {
        $findmepipe = '|';
        $paramStr = "";
        $flag = 1;
        foreach ($arrayList as $key => $value) {
            $pospipe = strpos($value, $findmepipe);
            if ($pospipe !== false) {
                continue;
            }

            if ($flag) {
                $paramStr .= $this->checkString_e($value);
                $flag = 0;
            } else {
                $paramStr .= "|" . $this->checkString_e($value);
            }
        }
        return $paramStr;
    }

    function callRefundAPI($refundApiURL, $requestParamList) {
        $jsonResponse = "";
        $responseParamList = array();
        $JsonData = json_encode($requestParamList);
        $postData = 'JsonData=' . urlencode($JsonData);
        $ch = curl_init($apiURL);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_URL, $refundApiURL);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $headers = array();
        $headers[] = 'Content-Type: application/json';
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        $jsonResponse = curl_exec($ch);
        $responseParamList = json_decode($jsonResponse, true);
        return $responseParamList;
    }

    /**
     * Function Name: payTm_app
     * Description:   To pay by paytm method for app
     */
    function payTm_app_post() {
        $return['code'] = 200;
        $return['response'] = new stdClass();
        $data = $this->input->post();
        $this->form_validation->set_rules('login_session_key', 'Login session key', 'trim|required|callback__validate_login_session_key');
        $this->form_validation->set_rules('payment', 'Payment', 'trim|required|numeric');
        $this->form_validation->set_rules('user_id', 'User Id', 'trim|required|numeric');
        if ($this->form_validation->run() == FALSE) {
            $error = $this->form_validation->rest_first_error_string();
            $return['status'] = 0;
            $return['message'] = $error;
        } else {
            $user_id = extract_value($data, 'user_id', '');
            $TXN_AMOUNT = abs(extract_value($data, 'payment', ''));
            $todayDepositeAmount = toCheckWithdrawalPerDay($user_id);
           /* if ($TXN_AMOUNT < 50) {
                $return['status'] = 0;
                $return['message'] = 'Minimum amount of deposit is rupee 50.';
                $this->response($return);
                exit;
            }*/
            if (!empty($todayDepositeAmount)) {
                $depostAmount = abs($todayDepositeAmount->totalDeposit) + abs($TXN_AMOUNT);
                if ($depostAmount > 10000) {
                    $return['status'] = 0;
                    $return['message'] = 'Maximum limit to add money is 10,000 in a day';
                    $this->response($return);
                    exit;
                }
            }

            $currDate = date('Y-m-d');
            $coupon_code = extract_value($data, 'coupon_code', '');

            if(!empty($coupon_code))
            {
                $option = array(
                    'table' => 'coupons',
                    'select' => 'coupon_type,coupon_code,user_size,total_use_user,cash_type,amount,id,used_type,min_amount,max_amount,percentage_in_amount',
                    'where' => array('coupon_code' => $coupon_code,'end_date >=' => $currDate,'start_date <=' => $currDate,'status' => 1),
                    'where_in' => array('coupon_type' => array(1, 4)),
                    'single' => true
                );
                $isCouponCode = $this->common_model->customGet($option);

                if(!empty($isCouponCode))
                {
                    if(($TXN_AMOUNT >= $isCouponCode->min_amount && $TXN_AMOUNT <= $isCouponCode->max_amount) || $isCouponCode->coupon_type == 4)
                    {
                        if($isCouponCode->coupon_type == 4){
                            $option = array(
                                'table' => 'custom_coupons',
                                'select' => 'id',
                                'where' => array('coupon_id' => $isCouponCode->id,'add_amount' => $TXN_AMOUNT),
                                'single' => true
                            );
                            $getAmountData = $this->common_model->customGet($option);
                            //print_r($this->db->last_query());die;
                            if(empty($getAmountData)){
                                $return['status'] = 0;
                                $return['message'] = 'Please check your add amount!';
                                $this->response($return);
                                exit;
                            }
                        }

                        if($isCouponCode->user_size > $isCouponCode->total_use_user)
                        {

                            $option = array(
                                'table' => 'coupons_user',
                                'select' => 'user_id',
                                'where' => array('user_id' => $user_id,'coupns_id' => $isCouponCode->id),
                                'single' => true

                            );
                            $totalusedcoupon = $this->common_model->customCount($option);

                            if($isCouponCode->used_type > $totalusedcoupon)
                            {
                                $coupon_id = $isCouponCode->id;
                          
                            }else{

                                $return['status'] = 0;
                                $return['message'] = 'Your limit has been expired for this coupon!';
                                $this->response($return);
                                exit;
                            }
                       
                        }else{

                            $return['status'] = 0;
                            $return['message'] = 'Coupon limit has been expired!';
                            $this->response($return);
                            exit;
                        }
                    }else{
                        $return['status'] = 0;
                        $return['message'] = 'Invalid coupon code!';
                        $this->response($return);
                        exit;
                    }
                }else{

                    $return['status'] = 0;
                    $return['message'] = 'Invalid coupon code!';
                    $this->response($return);
                    exit;
                } 
            } 

            if(!empty($coupon_code))
            {
                $coupon_id = $coupon_id;
            }else{
                $coupon_id = 0;
            }


            $dataResponse = array();
            $paramList = array();
            $ORDER_ID = "ORDS" . rand(10000, 99999999);
            $CUST_ID = "CUST" . $user_id;
            $INDUSTRY_TYPE_ID = "Retail";
            $CHANNEL_ID = "WAP";
            // Create an array having all required parameters for creating checksum.
            $dataResponse['MID'] = $paramList["MID"] = $this->PAYTM_MERCHANT_MID;
            $dataResponse['ORDER_ID'] = $paramList["ORDER_ID"] = $ORDER_ID;
            $dataResponse['CUST_ID'] = $paramList["CUST_ID"] = $CUST_ID;
            $dataResponse['INDUSTRY_TYPE_ID'] = $paramList["INDUSTRY_TYPE_ID"] = $INDUSTRY_TYPE_ID;
            $dataResponse['CHANNEL_ID'] = $paramList["CHANNEL_ID"] = $CHANNEL_ID;
            $dataResponse['TXN_AMOUNT'] = $paramList["TXN_AMOUNT"] = $TXN_AMOUNT;
            $dataResponse['WEBSITE'] = $paramList["WEBSITE"] = "DEFAULT";
            $dataResponse['CALLBACK_URL'] = $paramList["CALLBACK_URL"] = 'https://securegw.paytm.in/theia/paytmCallback?ORDER_ID='.$ORDER_ID;
            $dataResponse['CHECKSUMHASH'] = $this->getChecksumFromArray($paramList, $this->PAYTM_MERCHANT_KEY);
            //$dataResponse['PAYTM_TXN_URL'] = $this->PAYTM_TXN_URL;
            //echo "string";die;
            if (!empty($dataResponse['CHECKSUMHASH'])) {
                $option = array(
                    'table' => 'payment',
                    'data' => array(
                        'user_id' => $user_id,
                        'coupon_id' => $coupon_id,
                        'orderId' => $ORDER_ID,
                        'amount' => $TXN_AMOUNT,
                        'datetime' => date('Y-m-d H:i:s'),
                    ),
                );
                $userData = $this->common_model->customInsert($option);
                $return['response'] = $dataResponse;
                $return['status'] = 1;
                $return['message'] = 'Pay to payTm';
            } else {
                $return['status'] = 0;
                $return['message'] = 'Invalid payment process';
            }
        }
        $this->response($return);
    }

    /**
     * Function Name: payPayTm
     * Description:   To pay by paytm method
     */
    function payTm_post() {
        $return['code'] = 200;
        $return['response'] = new stdClass();
        $data = $this->input->post();
        $this->form_validation->set_rules('login_session_key', 'Login session key', 'trim|required|callback__validate_login_session_key');
        $this->form_validation->set_rules('payment', 'Payment', 'trim|required|numeric');
        $this->form_validation->set_rules('user_id', 'User Id', 'trim|required|numeric');
        if ($this->form_validation->run() == FALSE) {
            $error = $this->form_validation->rest_first_error_string();
            $return['status'] = 0;
            $return['message'] = $error;
        } else {
            $user_id = extract_value($data, 'user_id', '');
            $TXN_AMOUNT = abs(extract_value($data, 'payment', ''));
            $todayDepositeAmount = toCheckWithdrawalPerDay($user_id);
            $currDate = date('Y-m-d');
            /*if ($TXN_AMOUNT < 50) {
                $return['status'] = 0;
                $return['message'] = 'Minimum amount of deposit is rupee 50.';
                $this->response($return);
                exit;
            }*/
            if (!empty($todayDepositeAmount)) {
                $depostAmount = abs($todayDepositeAmount->totalDeposit) + abs($TXN_AMOUNT);
                if ($depostAmount > 10000) {
                    $return['status'] = 0;
                    $return['message'] = 'Maximum limit to add money is 10,000 in a day';
                    $this->response($return);
                    exit;
                }
            }

            $coupon_code = extract_value($data, 'coupon_code', '');

            if(!empty($coupon_code))
            {
                $option = array(
                    'table' => 'coupons',
                    'select' => 'coupon_type,coupon_code,user_size,total_use_user,cash_type,amount,id,used_type,min_amount,max_amount,percentage_in_amount',
                    'where' => array('coupon_code' => $coupon_code,'end_date >=' => $currDate,'start_date <=' => $currDate,'status' => 1),
                    'where_in' => array('coupon_type' => array(1, 4)),
                    'single' => true
                );
                $isCouponCode = $this->common_model->customGet($option);

                if(!empty($isCouponCode))
                {
                    if(($TXN_AMOUNT >= $isCouponCode->min_amount && $TXN_AMOUNT <= $isCouponCode->max_amount) || $isCouponCode->coupon_type == 4)
                    {
                        if($isCouponCode->coupon_type == 4){
                            $option = array(
                                'table' => 'custom_coupons',
                                'select' => 'id',
                                'where' => array('coupon_id' => $isCouponCode->id,'add_amount' => $TXN_AMOUNT),
                                'single' => true
                            );
                            $getAmountData = $this->common_model->customGet($option);
                            //print_r($this->db->last_query());die;
                            if(empty($getAmountData)){
                                $return['status'] = 0;
                                $return['message'] = 'Please check your add amount!';
                                $this->response($return);
                                exit;
                            }
                        }

                        if($isCouponCode->user_size > $isCouponCode->total_use_user)
                        {

                            $option = array(
                                'table' => 'coupons_user',
                                'select' => 'user_id',
                                'where' => array('user_id' => $user_id,'coupns_id' => $isCouponCode->id),
                                'single' => true

                            );
                            $totalusedcoupon = $this->common_model->customCount($option);

                            if($isCouponCode->used_type > $totalusedcoupon)
                            {
                                $coupon_id = $isCouponCode->id;
                          
                            }else{

                                $return['status'] = 0;
                                $return['message'] = 'Your limit has been expired for this coupon!';
                                $this->response($return);
                                exit;
                            }
                       
                        }else{

                            $return['status'] = 0;
                            $return['message'] = 'Coupon limit has been expired!';
                            $this->response($return);
                            exit;
                        }
                    }else{
                        $return['status'] = 0;
                        $return['message'] = 'Invalid coupon code!';
                        $this->response($return);
                        exit;
                    }
                }else{

                    $return['status'] = 0;
                    $return['message'] = 'Invalid coupon code!';
                    $this->response($return);
                    exit;
                } 
            } 

            if(!empty($coupon_code))
            {
                $coupon_id = $coupon_id;
            }else{
                $coupon_id = 0;
            }


            $dataResponse = array();
            $paramList = array();
            $ORDER_ID = "ORDS" . rand(10000, 99999999);
            $CUST_ID = "CUST" . $user_id;
            $INDUSTRY_TYPE_ID = "Retail";
            $CHANNEL_ID = "WEB";
            // Create an array having all required parameters for creating checksum.
            $dataResponse['MID'] = $paramList["MID"] = trim($this->PAYTM_MERCHANT_MID);
            $dataResponse['ORDER_ID'] = $paramList["ORDER_ID"] = $ORDER_ID;
            $dataResponse['CUST_ID'] = $paramList["CUST_ID"] = $CUST_ID;
            $dataResponse['INDUSTRY_TYPE_ID'] = $paramList["INDUSTRY_TYPE_ID"] = $INDUSTRY_TYPE_ID;
            $dataResponse['CHANNEL_ID'] = $paramList["CHANNEL_ID"] = $CHANNEL_ID;
            $dataResponse['TXN_AMOUNT'] = $paramList["TXN_AMOUNT"] = $TXN_AMOUNT;
            $dataResponse['WEBSITE'] = $paramList["WEBSITE"] = 'DEFAULT';
            $dataResponse['CALLBACK_URL'] = $paramList["CALLBACK_URL"] = base_url() . 'payment/PayResponse';
            $dataResponse['CHECKSUMHASH'] = $this->getChecksumFromArray($paramList, $this->PAYTM_MERCHANT_KEY);
            $dataResponse['PAYTM_TXN_URL'] = $this->PAYTM_TXN_URL;
            $dataResponse['PAYTM_MODE'] = (getConfig('paytm_environment') != "") ? "LIVE" : "TEST";
            //$dataResponse['PAYTM_MERCHANT_KEY'] = $this->PAYTM_MERCHANT_KEY;

            if (!empty($dataResponse['CHECKSUMHASH'])) {
                $option = array(
                    'table' => 'payment',
                    'data' => array(
                        'user_id' => $user_id,
                        'coupon_id' => $coupon_id,
                        'orderId' => $ORDER_ID,
                        'amount' => $TXN_AMOUNT,
                        'datetime' => date('Y-m-d H:i:s'),
                    ),
                );
                $userData = $this->common_model->customInsert($option);
                $return['response'] = $dataResponse;
                $return['status'] = 1;
                $return['message'] = 'Pay to payTm';
            } else {
                $return['status'] = 0;
                $return['message'] = 'Invalid payment process';
            }
        }
        $this->response($return);
    }

    /**
     * Function Name: payTmResponse_app
     * Description:   To handle response paytm by app
     */
    function payTmResponse_app_post() {

        $return['code'] = 200;
        //$return['response'] = new stdClass();
        $data = $this->input->post();
        
        $this->form_validation->set_rules('login_session_key', 'Login session key', 'trim|required|callback__validate_login_session_key');
        $this->form_validation->set_rules('payResponse', 'Pay Response', 'trim|required');
        if ($this->form_validation->run() == FALSE) {
            $error = $this->form_validation->rest_first_error_string();
            $return['status'] = 0;
            $return['message'] = $error;
        } else {

            $paytmChecksum = "";
            $paramList = array();
            $isValidChecksum = "FALSE";
            $paramList = $this->input->post();
            $payResponse = (array) json_decode($paramList['payResponse']);
            $UID = $this->user_details->id;
            $CUST_ID = 'CUST' . $UID;
            $INDUSTRY_TYPE_ID = "Retail";
            $CHANNEL_ID = "WAP";
            if ($payResponse["STATUS"] == "TXN_FAILURE") {
                /*$option = array(
                    'table' => 'payment',
                    'where' => array('orderId' => $payResponse['ORDERID'], 'status !=' => 'SUCCESS')
                );
                $this->common_model->customDelete($option);*/
                $return['status'] = 0;
                $return['message'] = "Payment failure";
            } else {

                $paytmChecksum = isset($payResponse["CHECKSUMHASH"]) ? $payResponse["CHECKSUMHASH"] : "";
                $checkSumData = array();
                $checkSumData["MID"] = $this->PAYTM_MERCHANT_MID;
                $checkSumData["ORDERID"] = $ORDERID = $payResponse['ORDERID'];
                $checkSumData["CUST_ID"] = $CUST_ID;
                $checkSumData["INDUSTRY_TYPE_ID"] = $INDUSTRY_TYPE_ID;
                $checkSumData["CHANNEL_ID"] = $CHANNEL_ID;
                $checkSumData["TXNAMOUNT"] = $payResponse['TXNAMOUNT'];
                $checkSumData["WEBSITE"] = "DEFAULT";
                $checkSumData["CALLBACK_URL"] = 'https://securegw.paytm.in/theia/paytmCallback?ORDER_ID='.$ORDERID;
                //print_r($checkSumData);
                $isValidChecksum = $this->verifychecksum_e($checkSumData, $this->PAYTM_MERCHANT_KEY, $paytmChecksum);
               
                if ($payResponse["STATUS"] == "TXN_SUCCESS") {

                    $option = array(
                        'table' => 'payment',
                        'select' => 'payment.orderId',
                        'join' => array('users' => 'users.id=payment.user_id'),
                        'where' => array('payment.orderId' => $payResponse['ORDERID'],
                            'payment.user_id' => $UID,
                            'payment.status' => 'SUCCESS'),
                        'single' => true
                    );
                    $paymentCheckIsDone = $this->common_model->customGet($option);
                    
                    if (!empty($paymentCheckIsDone)) {
                        $return['status'] = 0;
                        $return['message'] = "Payment already done for ORDERID:$ORDERID";
                        $this->response($return);
                        exit();
                    }
                    /* user payment add */
                    $option = array(
                        'table' => 'payment',
                        'data' => array(
                            'amount' => $payResponse['TXNAMOUNT'],
                            'currency' => $payResponse['CURRENCY'],
                            'txnid' => $payResponse['TXNID'],
                            'status' => "SUCCESS",
                            'payment_type' => "PAYTM",
                            'txn_slip_image' => $isValidChecksum,
                            'pay_response' => json_encode($this->input->post()),
                            'datetime' => date('Y-m-d H:i:s', strtotime($payResponse['TXNDATE']))
                        ),
                        'where' => array('orderId' => $payResponse['ORDERID'], 'user_id' => $UID)
                    );
                    $this->common_model->customUpdate($option);

                    
                    $option = array(
                        'table' => 'payment',
                        'select' => 'payment.user_id,users.email,users.first_name,users.badges,payment.coupon_id',
                        'join' => array('users' => 'users.id=payment.user_id'),
                        'where' => array('payment.orderId' => $payResponse['ORDERID']),
                        'single' => true
                    );
                    $userData = $this->common_model->customGet($option);

                    if (!empty($userData)) {
                        $creditAmount = $payResponse['TXNAMOUNT'];
                        $cash_opening_balance = 0;
                        $cash_credit = 0;
                        $cash_available_balance = 0;
                        $total_wallet_balance = 0;
                        $wallet_opening_balance = 0;
                        /* user payment wallet add & update */
                        $option = array(
                            'table' => 'wallet',
                            'where' => array('user_id' => $userData->user_id),
                            'single' => true
                        );
                        $userWallet = $this->common_model->customGet($option);

                        if (!empty($userWallet)) {
                            $prevDepositedAmount = $userWallet->deposited_amount;
                            $curDepositedAmount = $payResponse['TXNAMOUNT'];
                            $totalDepositedAmount = abs($prevDepositedAmount) + abs($curDepositedAmount);
                            $prevWinningAmount = $userWallet->winning_amount;
                            $prevCashBonusAmount = $userWallet->cash_bonus_amount;
                            $totalCurrentBalance = abs($prevWinningAmount) + abs($prevCashBonusAmount) + abs($totalDepositedAmount);
                            $total_wallet_balance = $totalCurrentBalance;
                            $wallet_opening_balance = $userWallet->total_balance;
                            $optionUpdate = array(
                                'table' => 'wallet',
                                'data' => array(
                                    'deposited_amount' => $totalDepositedAmount,
                                    'total_balance' => $totalCurrentBalance,
                                    'update_date' => date('Y-m-d H:i:s')
                                ),
                                'where' => array('user_id' => $userData->user_id)
                            );
                            $this->common_model->customUpdate($optionUpdate);
                            $cash_opening_balance = $userWallet->total_balance;
                            $cash_credit = $payResponse['TXNAMOUNT'];
                            $cash_available_balance = $totalCurrentBalance;
                        } else {
                            $total_wallet_balance = $payResponse['TXNAMOUNT'];
                            $optionW = array(
                                'table' => 'wallet',
                                'data' => array(
                                    'user_id' => $userData->user_id,
                                    'deposited_amount' => $payResponse['TXNAMOUNT'],
                                    'total_balance' => $payResponse['TXNAMOUNT'],
                                    'create_date' => date('Y-m-d H:i:s')
                                )
                            );
                            $this->common_model->customInsert($optionW);
                            $cash_credit = $payResponse['TXNAMOUNT'];
                            $cash_available_balance = $payResponse['TXNAMOUNT'];
                        }
                        /** To check first time user bonus * */
                        
                        /* user payment transaction history add */
                        $orderId = $payResponse['ORDERID'];
                        $options = array(
                            'table' => 'transactions_history',
                            'data' => array(
                                'user_id' => $userData->user_id,
                                'match_id' => 0,
                                'orderId' => $orderId,
                                'opening_balance' => $cash_opening_balance,
                                'cr' => $cash_credit,
                                'available_balance' => $cash_available_balance,
                                'message' => "Deposit amount",
                                'datetime' => date('Y-m-d H:i:s'),
                                'transaction_type' => 'CASH',
                                'pay_type' => "DEPOSIT",
                                'total_wallet_balance' => $total_wallet_balance,
                                'wallet_opening_balance' => $wallet_opening_balance
                            )
                        );
                        $this->common_model->customInsert($options);

                        $this->deposit_coupon_bonus($userData->coupon_id,$userData->user_id,$cash_credit);
                        $this->auto_deposit_coupon_bonus($userData->user_id,$cash_credit);

                        /* user payment sent mail */
                        $html = array();
                        $html['logo'] = base_url() . getConfig('site_logo');
                        $html['site'] = getConfig('site_name');
                        $html['amount'] = $payResponse['TXNAMOUNT'];
                        $html['orderId'] = $payResponse['ORDERID'];
                        $html['user'] = ucwords($userData->first_name);
                        $email_template = $this->load->view('email/user_payment_information_tpl', $html, true);
                        send_mail($email_template, '[' . getConfig('site_name') . '] Your deposit is successful. Start playing now!', $userData->email, getConfig('admin_email'));

                        /* admin notification */
                        $options = array(
                            'table' => 'notifications',
                            'data' => array(
                                'user_id' => 1,
                                'type_id' => 0,
                                'sender_id' => $userData->user_id,
                                'noti_type' => 'DEPOSITE_AMOUNT',
                                'message' => "New Deposited Cash " . getConfig('currency') . ". " . $payResponse['TXNAMOUNT'],
                                'read_status' => 'NO',
                                'sent_time' => date('Y-m-d H:i:s'),
                                'user_type' => 'ADMIN'
                            )
                        );
                        $this->common_model->customInsert($options);
                        /* user notification */
                        $options = array(
                            'table' => 'notifications',
                            'data' => array(
                                'user_id' => $userData->user_id,
                                'type_id' => 0,
                                'sender_id' => 1,
                                'noti_type' => 'USER_DEPOSITE_AMOUNT',
                                'message' => "Your deposit of " . getConfig('currency') . ". " . $payResponse['TXNAMOUNT'] . " was successful.",
                                'read_status' => 'NO',
                                'sent_time' => date('Y-m-d H:i:s'),
                                'user_type' => 'USER'
                            )
                        );
                        $this->common_model->customInsert($options);

                        $userBadges = $userData->badges + 1;
                        /** to send push notification * */
                        $notificationData = array(
                            'title' => 'Deposit Amount',
                            'message' => "Your deposit of " . getConfig('currency') . ". " . $payResponse['TXNAMOUNT'] . " was successful.",
                            'type' => "USER_DEPOSITE_AMOUNT",
                            'type_id' => 0,
                            'user_id' => $userData->user_id,
                            'badges' => $userBadges,
                        );

                        sendNotification($userData->user_id, $notificationData);
                        $return['status'] = 1;
                        $return['message'] = "Payment Success";
                    }
                } else {
                    $return['status'] = 0;
                    $return['message'] = "Payment failure";
                }
            }
        }

        $this->response($return);
    }

    /**
     * Function Name: PayResponse
     * Description:   To handle response
     */
    function PayResponseDemo() {
        $paytmChecksum = "";
        $paramList = array();
        $isValidChecksum = "FALSE";

        $paramList = $_POST;
        $paytmChecksum = isset($_POST["CHECKSUMHASH"]) ? $_POST["CHECKSUMHASH"] : ""; //Sent by 
        $isValidChecksum = $this->verifychecksum_e($paramList, $this->PAYTM_MERCHANT_KEY, $paytmChecksum); //will
        if ($isValidChecksum == "TRUE") {
            echo "<b>Checksum matched and following are the transaction details:</b>" . "<br/>";
            if ($_POST["STATUS"] == "TXN_SUCCESS") {
                echo "<b>Transaction status is success</b>" . "<br/>";
                //Process your transaction here as success transaction.
                //Verify amount & order id received from Payment gateway with your application's order id and amount.
            } else {
                echo "<b>Transaction status is failure</b>" . "<br/>";
            }

            if (isset($_POST) && count($_POST) > 0) {
                foreach ($_POST as $paramName => $paramValue) {
                    echo "<br/>" . $paramName . " = " . $paramValue;
                }
            }
        } else {
            echo "<b>Checksum mismatched.</b>";
            //Process transaction as suspicious.
        }
    }

    /**
     * Function Name: payUMoney
     * Description:   To pay by payumoney method
     */
    function payUMoney_post() {
        $return['code'] = 200;
        $return['response'] = new stdClass();
        $data = $this->input->post();
        $this->form_validation->set_rules('login_session_key', 'Login session key', 'trim|required|callback__validate_login_session_key');
        $this->form_validation->set_rules('amount', 'Amount', 'trim|required|numeric');
        $this->form_validation->set_rules('user_id', 'User Id', 'trim|required|numeric');
        $this->form_validation->set_rules('firstname', 'name', 'trim|required');
        $this->form_validation->set_rules('email', 'email', 'trim|required');
        $this->form_validation->set_rules('phone', 'Mobile Number verify', 'trim|required|numeric');
        if ($this->form_validation->run() == FALSE) {
            $error = $this->form_validation->rest_first_error_string();
            $return['status'] = 0;
            $return['message'] = $error;
        } else {
            $MERCHANT_KEY = $this->PAYUMONEY_MERCHANT_KEY;
            $SALT = $this->PAYUMONEY_SALT;
            $user_id = extract_value($data, 'user_id', '');
            $TXN_AMOUNT = abs(extract_value($data, 'amount', ''));
            
            $dataResponse = array();
            $paramList = array();
            $MERCHANT_KEY = $this->PAYUMONEY_MERCHANT_KEY;
            $SALT = $this->PAYUMONEY_SALT;
            //$MERCHANT_KEY = "oraWtWvA";
            //$SALT = "zAMy5x3y2w";
            $PAYU_BASE_URL = $this->PAYUMONEY_PAYU_BASE_URL;
            $user_id = extract_value($data, 'user_id', '');
            //$amount = extract_value($data, 'amount', '');
            //$amount = sprintf("%.2f", $amount);
            $ORDER_ID = "ORDS" . rand(10000, 99999999);
            $action = '';
            $coupon_code = extract_value($data, 'coupon_code', '');
            $coupon_id = 0;
            $currDate = date('Y-m-d');
            $posted = array();

             if(!empty($coupon_code))
             {
                $option = array(
                    'table' => 'coupons',
                    'select' => 'coupon_type,coupon_code,user_size,total_use_user,cash_type,amount,id,used_type,min_amount,max_amount,percentage_in_amount',
                    'where' => array('coupon_code' => $coupon_code,'end_date >=' => $currDate,'start_date <=' => $currDate,'status' => 1),
                    'where_in' => array('coupon_type' => array(1, 4)),
                    'single' => true
                );
                $isCouponCode = $this->common_model->customGet($option);

                if(!empty($isCouponCode))
                {
                   if(($TXN_AMOUNT >= $isCouponCode->min_amount && $TXN_AMOUNT <= $isCouponCode->max_amount) || $isCouponCode->coupon_type == 4)
                   {
                        if($isCouponCode->coupon_type == 4){
                            $option = array(
                                'table' => 'custom_coupons',
                                'select' => 'id',
                                'where' => array('coupon_id' => $isCouponCode->id,'add_amount' => $TXN_AMOUNT),
                                'single' => true
                            );
                            $getAmountData = $this->common_model->customGet($option);
                            //print_r($this->db->last_query());die;
                            if(empty($getAmountData)){
                                $return['status'] = 0;
                                $return['message'] = 'Please check your add amount!';
                                $this->response($return);
                                exit;
                            }
                        }

                    if($isCouponCode->user_size > $isCouponCode->total_use_user)
                    {

                        $option = array(
                            'table' => 'coupons_user',
                            'select' => 'user_id',
                            'where' => array('user_id' => $user_id,'coupns_id' => $isCouponCode->id),
                            'single' => true

                            );
                        $totalusedcoupon = $this->common_model->customCount($option);

                          if($isCouponCode->used_type > $totalusedcoupon)
                      {
                           $coupon_id = $isCouponCode->id;
                          
                      }else{

                           $return['status'] = 0;
                           $return['message'] = 'Your limit has been expired for this coupon!';
                           $this->response($return);
                           exit;
                      }
                       
                    }else{

                        $return['status'] = 0;
                        $return['message'] = 'Coupon limit has been expired!';
                        $this->response($return);
                        exit;
                    }
                 }else{
                        $return['status'] = 0;
                        $return['message'] = 'Invalid coupon code!';
                        $this->response($return);
                        exit;
                 }
                }else{

                        $return['status'] = 0;
                        $return['message'] = 'Invalid coupon code!';
                        $this->response($return);
                        exit;
                } 
             } 

                 if(!empty($coupon_code))
                 {
                    $coupon_id = $coupon_id;
                 }else{
                    $coupon_id = 0;
                 }

            if (!empty($_POST)) {
                unset($_POST['login_session_key']);
                unset($_POST['user_id']);
                foreach ($_POST as $key => $value) {
                    if ($key == 'amount') {
                        if (strpos($value, '.') !== FALSE) {
                            $posted[$key] = $value;
                        } else {
                            $amt = $value . '.0';
                            $posted[$key] = $amt;
                        }
                    } else {
                        $posted[$key] = $value;
                    }
                }
            }
            if (empty($posted['txnid'])) {
                // Generate random transaction id
                $txnid = substr(hash('sha256', mt_rand() . microtime()), 0, 20);
            } else {
                $txnid = $posted['txnid'];
            }
            $posted['hash'] = "";
            $posted['txnid'] = $txnid;
            $posted['productinfo'] = $ORDER_ID;
            $posted['key'] = $MERCHANT_KEY;
            $posted['surl'] = $this->PAYUMONEY_SUCCESS_URL;
            //$posted['surl'] = 'https://www.funtush11.com/funtush/payment/PayUMoneyPayResponseSuccess_NEW';
            $posted['furl'] = $this->PAYUMONEY_FAILURE_URL;
            $posted['service_provider'] = "payu_paisa";
            $posted['udf1'] = $user_id;
            $hash = '';
            $hashSequence = "key|txnid|amount|productinfo|firstname|email|udf1|udf2|udf3|udf4|udf5|udf6|udf7|udf8|udf9|udf10";
            if (empty($posted['hash']) && sizeof($posted) > 0) {
                $hashVarsSeq = explode('|', $hashSequence);
                $hash_string = '';
                foreach ($hashVarsSeq as $hash_var) {
                    $hash_string .= isset($posted[$hash_var]) ? $posted[$hash_var] : '';
                    $hash_string .= '|';
                }
                $hash_string .= $SALT;
                //echo $hash_string;exit;
                $hash = strtolower(hash('sha512', $hash_string));
                $action = $PAYU_BASE_URL . '/_payment';
            } elseif (!empty($posted['hash'])) {
                $hash = $posted['hash'];
                $action = $PAYU_BASE_URL . '/_payment';
            }
            $dataResponse['action'] = $action;

            $dataResponse['key'] = $MERCHANT_KEY;
            $dataResponse['salt'] = $SALT;
            $dataResponse['mid'] = 6681705;
            $dataResponse['hash'] = $hash;
            $dataResponse['txnid'] = $txnid;
            $dataResponse['amount'] = $posted['amount'];
            $dataResponse['email'] = $posted['email'];
            $dataResponse['phone'] = $posted['phone'];
            $dataResponse['firstname'] = $posted['firstname'];
            $dataResponse['productinfo'] = $posted['productinfo'];
            $dataResponse['surl'] = $posted['surl'];
            $dataResponse['furl'] = $posted['furl'];
            $dataResponse['service_provider'] = $posted['service_provider'];
            $dataResponse['user_id'] = $user_id;
             //$dataResponse['debug'] = true;
            $dataResponse['debug'] = false;
            if (!empty($dataResponse['hash'])) {
                $option = array(
                    'table' => 'payment',
                    'data' => array(
                        'user_id' => $user_id,
                        'orderId' => $ORDER_ID,
                        'coupon_id' => $coupon_id,
                        'amount' => $posted['amount'],
                        'datetime' => date('Y-m-d H:i:s'),
                        'payment_type' => 'PAYUMONEY'
                    ),
                );
                $userData = $this->common_model->customInsert($option);
                $return['response'] = $dataResponse;
                $return['status'] = 1;
                $return['message'] = 'Pay to PayUMoney';
            } else {
                $return['status'] = 0;
                $return['message'] = 'Invalid payment process';
            }
        }
        $this->response($return);
    }

    /**
     * Function Name: payUMoneyWeb
     * Description:   To pay by payumoney method
     */
    function payUMoneyWeb_post() {
        $return['code'] = 200;
        $return['response'] = new stdClass();
        $data = $this->input->post();
        $this->form_validation->set_rules('login_session_key', 'Login session key', 'trim|required|callback__validate_login_session_key');
        $this->form_validation->set_rules('amount', 'Amount', 'trim|required|numeric');
        $this->form_validation->set_rules('user_id', 'User Id', 'trim|required|numeric');
        $this->form_validation->set_rules('firstname', 'name', 'trim|required');
        $this->form_validation->set_rules('email', 'email', 'trim|required');
        //$this->form_validation->set_rules('phone', 'phone', 'trim|required|numeric');
        if ($this->form_validation->run() == FALSE) {
            $error = $this->form_validation->rest_first_error_string();
            $return['status'] = 0;
            $return['message'] = $error;
        } else {
            $user_id = extract_value($data, 'user_id', '');
            $TXN_AMOUNT = abs(extract_value($data, 'amount', ''));
            
            $dataResponse = array();
            $paramList = array();
            $MERCHANT_KEY = $this->PAYUMONEY_MERCHANT_KEY;
            $SALT = $this->PAYUMONEY_SALT;
            $PAYU_BASE_URL = $this->PAYUMONEY_PAYU_BASE_URL;
            $user_id = extract_value($data, 'user_id', '');
            $ORDER_ID = "ORDS" . rand(10000, 99999999);
            $action = '';
            $posted = array();
            $coupon_code = extract_value($data, 'coupon_code', '');
            $coupon_id = 0;
            $currDate = date('Y-m-d');


             if(!empty($coupon_code))
             {
                $option = array(
                    'table' => 'coupons',
                    'select' => 'coupon_type,coupon_code,user_size,total_use_user,cash_type,amount,id,used_type,min_amount,max_amount,percentage_in_amount',
                    'where' => array('coupon_code' => $coupon_code,'end_date >=' => $currDate,'start_date <=' => $currDate,'status' => 1),
                    'where_in' => array('coupon_type' => array(1, 4)),
                    'single' => true
                );
                $isCouponCode = $this->common_model->customGet($option);

                if(!empty($isCouponCode))
                {
                   if(($TXN_AMOUNT >= $isCouponCode->min_amount && $TXN_AMOUNT <= $isCouponCode->max_amount) || $isCouponCode->coupon_type == 4)
                   {
                        if($isCouponCode->coupon_type == 4){
                            $option = array(
                                'table' => 'custom_coupons',
                                'select' => 'id',
                                'where' => array('coupon_id' => $isCouponCode->id,'add_amount' => $TXN_AMOUNT),
                                'single' => true
                            );
                            $getAmountData = $this->common_model->customGet($option);
                            //print_r($this->db->last_query());die;
                            if(empty($getAmountData)){
                                $return['status'] = 0;
                                $return['message'] = 'Please check your add amount!';
                                $this->response($return);
                                exit;
                            }
                        }

                    if($isCouponCode->user_size > $isCouponCode->total_use_user)
                    {

                        $option = array(
                            'table' => 'coupons_user',
                            'select' => 'user_id',
                            'where' => array('user_id' => $user_id,'coupns_id' => $isCouponCode->id),
                            'single' => true

                            );
                        $totalusedcoupon = $this->common_model->customCount($option);

                          if($isCouponCode->used_type > $totalusedcoupon)
                      {
                           $coupon_id = $isCouponCode->id;
                          
                      }else{

                           $return['status'] = 0;
                           $return['message'] = 'Your limit has been expired for this coupon!';
                           $this->response($return);
                           exit;
                      }
                       
                    }else{

                        $return['status'] = 0;
                        $return['message'] = 'Coupon limit has been expired!';
                        $this->response($return);
                        exit;
                    }
                 }else{
                        $return['status'] = 0;
                        $return['message'] = 'Invalid coupon code!';
                        $this->response($return);
                        exit;
                 }
                }else{

                        $return['status'] = 0;
                        $return['message'] = 'Invalid coupon code!';
                        $this->response($return);
                        exit;
                } 
             } 

                 if(!empty($coupon_code))
                 {
                    $coupon_id = $coupon_id;
                 }else{
                    $coupon_id = 0;
                 }


            if (!empty($_POST)) {
                unset($_POST['login_session_key']);
                unset($_POST['user_id']);
                foreach ($_POST as $key => $value) {
                    if ($key == 'amount') {
                        if (strpos($value, '.') !== FALSE) {
                            $posted[$key] = $value;
                        } else {
                            $amt = $value . '.0';
                            $posted[$key] = $amt;
                        }
                    } else {
                        $posted[$key] = $value;
                    }
                }
            }
            if (empty($posted['txnid'])) {
                // Generate random transaction id
                $txnid = substr(hash('sha256', mt_rand() . microtime()), 0, 20);
            } else {
                $txnid = $posted['txnid'];
            }
            $posted['hash'] = "";
            $posted['txnid'] = $txnid;
            $posted['productinfo'] = $ORDER_ID;
            $posted['key'] = $MERCHANT_KEY;
            $posted['surl'] = base_url()."payment/payuMoneyResponseSuccessWEB";

            //$posted['surl'] = 'https://www.funtush11.com/funtush/payment/PayUMoneyPayResponseSuccess_NEW';
            $posted['furl'] = base_url()."payment/payuMoneyResponseFailureWEB";
            $posted['service_provider'] = "payu_paisa";
            //$posted['udf1'] = $user_id;
            $hash = '';
            $hashSequence = "key|txnid|amount|productinfo|firstname|email|udf1|udf2|udf3|udf4|udf5|udf6|udf7|udf8|udf9|udf10";
            if (empty($posted['hash']) && sizeof($posted) > 0) {
                $hashVarsSeq = explode('|', $hashSequence);
                $hash_string = '';
                foreach ($hashVarsSeq as $hash_var) {
                    $hash_string .= isset($posted[$hash_var]) ? $posted[$hash_var] : '';
                    $hash_string .= '|';
                }
                $hash_string .= $SALT;
               
                $hash = strtolower(hash('sha512', $hash_string));
                $action = $PAYU_BASE_URL . '/_payment';
            } elseif (!empty($posted['hash'])) {
                $hash = $posted['hash'];
                $action = $PAYU_BASE_URL . '/_payment';
            }
            $dataResponse['action'] = $action;
            $dataResponse['key'] = $this->PAYUMONEY_MERCHANT_KEY;
            $dataResponse['salt'] = $this->PAYUMONEY_SALT;
            $dataResponse['mid'] = 6681705;
            $dataResponse['hash'] = $hash;
            $dataResponse['txnid'] = $txnid;
            $dataResponse['amount'] = $posted['amount'];
            $dataResponse['email'] = $posted['email'];
            $dataResponse['phone'] = $posted['phone'];
            $dataResponse['firstname'] = $posted['firstname'];
            $dataResponse['productinfo'] = $posted['productinfo'];
            $dataResponse['surl'] = $posted['surl'];
            $dataResponse['furl'] = $posted['furl'];
            $dataResponse['service_provider'] = $posted['service_provider'];
            //$dataResponse['udf1'] = $user_id;
            // $dataResponse['debug'] = true;
            $dataResponse['debug'] = false;
            if (!empty($dataResponse['hash'])) {
                $option = array(
                    'table' => 'payment',
                    'data' => array(
                        'user_id' => $user_id,
                        'orderId' => $ORDER_ID,
                        'coupon_id' => $coupon_id,
                        'amount' => $posted['amount'],
                        'datetime' => date('Y-m-d H:i:s'),
                        'payment_type' => 'PAYUMONEY'
                    ),
                );
                $userData = $this->common_model->customInsert($option);
                $return['response'] = $dataResponse;
                $return['status'] = 1;
                $return['message'] = 'Pay to PayUMoney';
            } else {
                $return['status'] = 0;
                $return['message'] = 'Invalid payment process';
            }
        }
        $this->response($return);
    }

    /**
     * Function Name: payuMoneyResponse_App
     * Description:   To handle response payumoney by app
     */
    function payuMoneyResponse_App_post() {
        $return['code'] = 200;
        $data = $this->input->post();
        $this->form_validation->set_rules('login_session_key', 'Login session key', 'trim|required|callback__validate_login_session_key');
        $this->form_validation->set_rules('payResponse', 'response', 'trim|required');
        if ($this->form_validation->run() == FALSE) {
            $error = $this->form_validation->rest_first_error_string();
            $return['status'] = 0;
            $return['message'] = $error;
        } else {
            $UID = $this->user_details->id;
            $payResponse = json_decode($data['payResponse']);
            $status = strtolower($payResponse->status);
            $txnid = $payResponse->txnid;
            $amount = $payResponse->amount;
            $orderid = $payResponse->productinfo;
            $txnDate = $payResponse->addedon;
            if ($status == "failure") {
                /*$option = array(
                    'table' => 'payment',
                    'where' => array('orderId' => $orderid, 'status !=' => 'SUCCESS')
                );
                $this->common_model->customDelete($option);*/
                $return['status'] = 0;
                $return['message'] = "Payment failure";
                $this->response($return);
                exit;
            } else if ($status == "success") {
                /*$return['status'] = 1;
                $return['message'] = "Payment Success";
                $this->response($return);
                exit;*/

                $option = array(
                    'table' => 'payment',
                    'select' => 'payment.orderId',
                    'join' => array('users' => 'users.id=payment.user_id'),
                    'where' => array('payment.orderId' => $orderid,
                        'payment.user_id' => $UID,
                        'payment.status' => 'SUCCESS'),
                    'single' => true
                );
                $paymentCheckIsDone = $this->common_model->customGet($option);
                if (!empty($paymentCheckIsDone)) {
                    $return['status'] = 0;
                    $return['message'] = "Payment already done for ORDERID:$orderid";
                    $this->response($return);
                    exit();
                }
                /* user payment add */
                $option = array(
                    'table' => 'payment',
                    'data' => array(
                        'amount' => $amount,
                        'currency' => "INR",
                        'txnid' => $txnid,
                        'status' => "SUCCESS",
                        'payment_type' => "PAYUMONEY",
                        'pay_response' => $data['payResponse'],
                        'datetime' => date('Y-m-d H:i:s', strtotime($txnDate))
                    ),
                    'where' => array('orderId' => $orderid, 'user_id' => $UID)
                );
                $this->common_model->customUpdate($option);
                $option = array(
                    'table' => 'payment',
                    'select' => 'payment.user_id,users.email,users.first_name,users.badges',
                    'join' => array('users' => 'users.id=payment.user_id'),
                    'where' => array('payment.orderId' => $orderid),
                    'single' => true
                );
                $userData = $this->common_model->customGet($option);
                if (!empty($userData)) {
                    /** If the player has credit balance then system will automatically take 5% from playwinfantsy chips account * */
                    //$creditCash = (abs($amount) * 5) / 100;
                    //$creditChipBonus = round($creditCash);
                    //accountPolicyCreditChip($userData->user_id, $creditChipBonus);
                    $cash_opening_balance = 0;
                    $cash_credit = 0;
                    $cash_available_balance = 0;
                    $total_wallet_balance = 0;
                    $wallet_opening_balance = 0;
                    /* user payment wallet add & update */
                    $option = array(
                        'table' => 'wallet',
                        'where' => array('user_id' => $userData->user_id),
                        'single' => true
                    );
                    $userWallet = $this->common_model->customGet($option);
                    if (!empty($userWallet)) {
                        $prevDepositedAmount = $userWallet->deposited_amount;
                        $curDepositedAmount = $amount;
                        $totalDepositedAmount = abs($prevDepositedAmount) + abs($curDepositedAmount);
                        $prevWinningAmount = $userWallet->winning_amount;
                        $prevCashBonusAmount = $userWallet->cash_bonus_amount;
                        $totalCurrentBalance = abs($prevWinningAmount) + abs($prevCashBonusAmount) + abs($totalDepositedAmount);
                        $total_wallet_balance = $totalCurrentBalance;
                        $wallet_opening_balance = $userWallet->total_balance;
                        $optionUpdate = array(
                            'table' => 'wallet',
                            'data' => array(
                                'deposited_amount' => $totalDepositedAmount,
                                'total_balance' => $totalCurrentBalance,
                                'update_date' => date('Y-m-d H:i:s')
                            ),
                            'where' => array('user_id' => $userData->user_id)
                        );
                        $this->common_model->customUpdate($optionUpdate);
                        $cash_opening_balance = $userWallet->total_balance;
                        $cash_credit = $amount;
                        $cash_available_balance = $totalCurrentBalance;
                    } else {
                        $total_wallet_balance = $amount;
                        $optionW = array(
                            'table' => 'wallet',
                            'data' => array(
                                'user_id' => $userData->user_id,
                                'deposited_amount' => $amount,
                                'total_balance' => $amount,
                                'create_date' => date('Y-m-d H:i:s')
                            )
                        );
                        $this->common_model->customInsert($optionW);
                        $cash_credit = $amount;
                        $cash_available_balance = $amount;
                        $option = array(
                            'table' => 'wallet',
                            'where' => array('user_id' => $userData->user_id),
                            'single' => true
                        );
                        $userWallet = $this->common_model->customGet($option);
                    }
                    /** To check first time user bonus * */
                    $flagGetBonus = 1;
                    if (!empty($userWallet)) {
                        if ($userWallet->is_first_bonus != 1) {
                            $flagGetBonus = 0;
                        }
                    } else {
                        $flagGetBonus = 0;
                    }
                    if ($flagGetBonus != 1) {
                        
                        //firstTimeDepositBonusCash($userData->user_id, $amount);
                        
                        /*$minBountAmount = getConfig('first_time_deposite_bonus_min_amount');
                        if ($minBountAmount <= $amount) {
                            $opening_balance = 0;
                            $cr = 0;
                            $available_balance = 0;
                            $options = array(
                                'table' => 'wallet',
                                'select' => 'cash_bonus_amount,winning_amount,total_balance',
                                'where' => array('user_id' => $userData->user_id),
                                'single' => true
                            );

                            $UserChip = $this->common_model->customGet($options);
                            if (!empty($UserChip)) {
                                $bonusChipCredit = $amount;
                                if ($amount > 1000) {
                                    $bonusChipCredit = 1000;
                                }
                                $bonus_chip = abs($UserChip->cash_bonus_amount) + abs($bonusChipCredit);
                                $totalChip = abs($UserChip->total_balance) + abs($bonusChipCredit);
                                $opening_balance = $UserChip->total_balance;
                                $cr = $bonusChipCredit;
                                $available_balance = $totalChip;

                                $optionsChip = array(
                                    'table' => 'wallet',
                                    'data' => array('cash_bonus_amount' => $bonus_chip,
                                        'total_balance' => $totalChip),
                                    'where' => array('user_id' => $userData->user_id)
                                );

                                $this->common_model->customUpdate($optionsChip);
                            } else {
                                $bonusChipCredit = $amount;
                                if ($amount > 1000) {
                                    $bonusChipCredit = 1000;
                                }
                                $cr = $bonusChipCredit;
                                $available_balance = $bonusChipCredit;
                                $optionsChip = array(
                                    'table' => 'user_chip',
                                    'data' => array('bonus_chip' => $bonusChipCredit,
                                        'chip' => $bonusChipCredit,
                                        'user_id' => $userData->user_id,
                                        'update_date' => date('Y-m-d H:i:s')),
                                );

                                $optionsChip = array(
                                    'table' => 'wallet',
                                    'data' => array('cash_bonus_amount' => $bonusChipCredit,
                                        'total_balance' => $bonusChipCredit,
                                        'user_id' => $userData->user_id,
                                        'update_date' => date('Y-m-d H:i:s')),
                                );
                                
                                $this->common_model->customInsert($optionsChip);
                            }
                            $options = array(
                                'table' => 'transactions_history',
                                'data' => array(
                                    'user_id' => $userData->user_id,
                                    'match_id' => 0,
                                    'opening_balance' => $opening_balance,
                                    'cr' => $cr,
                                    'orderId' => $orderid,
                                    'available_balance' => $available_balance,
                                    'message' => "First time deposit get bonus theteamgenie cash",
                                    'datetime' => date('Y-m-d H:i:s'),
                                    'transaction_type' => 'CHIP',
                                    'pay_type' => "BONUS"
                                )
                            );
                            $this->common_model->customInsert($options);
                        }*/
                        /*$options = array('table' => 'wallet',
                            'data' => array('is_first_bonus' => 1),
                            'where' => array('user_id' => $userData->user_id));
                        $this->common_model->customUpdate($options);*/
                    }
                    
                    /* user payment transaction history add */
                    $options = array(
                        'table' => 'transactions_history',
                        'data' => array(
                            'user_id' => $userData->user_id,
                            'match_id' => 0,
                            'orderId' => $orderid,
                            'opening_balance' => $userWallet->deposited_amount,
                            'cr' => $cash_credit,
                            'available_balance' => $userWallet->deposited_amount + $cash_credit,
                            'message' => "Deposit amount",
                            'datetime' => date('Y-m-d H:i:s'),
                            'transaction_type' => 'CASH',
                            'pay_type' => "DEPOSIT",
                            'total_wallet_balance' => $total_wallet_balance,
                            'wallet_opening_balance' => $wallet_opening_balance
                        )
                    );
                    $this->common_model->customInsert($options);

                    /* user payment sent mail */
                    $html = array();
                    $html['logo'] = base_url() . getConfig('site_logo');
                    $html['site'] = getConfig('site_name');
                    $html['amount'] = $amount;
                    $html['orderId'] = $orderid;
                    $html['user'] = ucwords($userData->first_name);
                    $email_template = $this->load->view('email/user_payment_information_tpl', $html, true);
                    send_mail($email_template, '[' . getConfig('site_name') . '] Your deposit is successful. Start playing now!', $userData->email, getConfig('admin_email'));
                    /* admin notification */
                    $options = array(
                        'table' => 'notifications',
                        'data' => array(
                            'user_id' => 1,
                            'type_id' => 0,
                            'sender_id' => $userData->user_id,
                            'noti_type' => 'DEPOSITE_AMOUNT',
                            'message' => "New Deposited Cash " . getConfig('currency') . ". " . $amount,
                            'read_status' => 'NO',
                            'sent_time' => date('Y-m-d H:i:s'),
                            'user_type' => 'ADMIN'
                        )
                    );
                    $this->common_model->customInsert($options);
                    /* user notification */
                    $options = array(
                        'table' => 'notifications',
                        'data' => array(
                            'user_id' => $userData->user_id,
                            'type_id' => 0,
                            'sender_id' => 1,
                            'noti_type' => 'USER_DEPOSITE_AMOUNT',
                            'message' => "Your deposit of " . getConfig('currency') . ". " . $amount . " was successful.",
                            'read_status' => 'NO',
                            'sent_time' => date('Y-m-d H:i:s'),
                            'user_type' => 'USER'
                        )
                    );
                    $this->common_model->customInsert($options);
                   //everyDepositBonusCash($userData->user_id, $amount);
                    //cashWalletReports($userData->user_id, $amount, 'DEBIT');
                    /** to send push notification * */

                    $userBadges = $userData->badges + 1;
                    $notificationData = array(
                        'title' => 'Deposit Amount',
                        'body' => "Your deposit of " . getConfig('currency') . ". " . $amount . " was successful.",
                        'type' => "USER_DEPOSITE_AMOUNT",
                        'badges' => $userBadges,
                    );
                    sendNotification($userData->user_id, $notificationData,$userBadges);
                    $return['status'] = 1;
                    $return['message'] = "Payment Success";
                    $this->response($return);
                    exit;
                }
            } else {
                $return['status'] = 0;
                $return['message'] = "Payment failure";
                $this->response($return);
                exit;
            }
        }
    }

    /**
     * Function Name: payuMoneyResponse
     * Description:   To handle response payumoney by web
     */
    function payuMoneyResponse_post() {
        $return['code'] = 200;
        $data = $this->input->post();
        $this->form_validation->set_rules('login_session_key', 'Login session key', 'trim|required|callback__validate_login_session_key');
        $this->form_validation->set_rules('payResponse', 'response', 'trim|required');
        if ($this->form_validation->run() == FALSE) {
            $error = $this->form_validation->rest_first_error_string();
            $return['status'] = 0;
            $return['message'] = $error;
        } else {
            $UID = $this->user_details->id;
            $payResponse = json_decode($data['payResponse']);
           // dump($payResponse);
            $status = strtolower($payResponse->status);
            $txnid = $payResponse->txnid;
            $amount = $payResponse->amount;
            $orderid = $payResponse->productinfo;
            $txnDate = $payResponse->addedon;
            if ($status == "failed") {
                $return['status'] = 0;
                $return['message'] = "Payment failure";
                $this->response($return);
                exit;
            } else if ($status == "success") {
                $return['status'] = 1;
                $return['message'] = "Payment Success";
                $this->response($return);
                exit;

                $option = array(
                    'table' => 'payment',
                    'select' => 'payment.orderId',
                    'join' => array('users' => 'users.id=payment.user_id'),
                    'where' => array('payment.orderId' => $orderid,
                        'payment.user_id' => $UID,
                        'payment.status' => 'SUCCESS'),
                    'single' => true
                );
                $paymentCheckIsDone = $this->common_model->customGet($option);
                if (!empty($paymentCheckIsDone)) {
                    $return['status'] = 0;
                    $return['message'] = "Payment already done for ORDERID:$orderid";
                    $this->response($return);
                    exit();
                }
                /* user payment add */
                $option = array(
                    'table' => 'payment',
                    'data' => array(
                        'amount' => $amount,
                        'currency' => "INR",
                        'txnid' => $txnid,
                        'status' => "SUCCESS",
                        'payment_type' => "PAYUMONEY",
                        'txn_slip_image' => "222",
                        'pay_response' => $data['payResponse'],
                        'datetime' => date('Y-m-d H:i:s', strtotime($txnDate))
                    ),
                    'where' => array('orderId' => $orderid, 'user_id' => $UID)
                );
                $this->common_model->customUpdate($option);

                $option = array(
                    'table' => 'payment',
                    'select' => 'payment.user_id,users.email,users.first_name,users.badges,payment.coupon_id',
                    'join' => array('users' => 'users.id=payment.user_id'),
                    'where' => array('payment.orderId' => $orderid),
                    'single' => true
                );
                $userData = $this->common_model->customGet($option);
                if (!empty($userData)) {
                    /** If the player has credit balance then system will automatically take 5% from playwinfantsy chips account * */
                    //$creditCash = (abs($amount) * 5) / 100;
                    //$creditChipBonus = round($creditCash);
                    //accountPolicyCreditChip($userData->user_id, $creditChipBonus);
                    $cash_opening_balance = 0;
                    $cash_credit = 0;
                    $cash_available_balance = 0;
                    $total_wallet_balance = 0;
                    $wallet_opening_balance = 0;
                    /* user payment wallet add & update */
                    $option = array(
                        'table' => 'wallet',
                        'where' => array('user_id' => $userData->user_id),
                        'single' => true
                    );
                    $userWallet = $this->common_model->customGet($option);
                    if (!empty($userWallet)) {
                        $prevDepositedAmount = $userWallet->deposited_amount;
                        $curDepositedAmount = $amount;
                        $totalDepositedAmount = abs($prevDepositedAmount) + abs($curDepositedAmount);
                        $prevWinningAmount = $userWallet->winning_amount;
                        $prevCashBonusAmount = $userWallet->cash_bonus_amount;
                        $totalCurrentBalance = abs($prevWinningAmount) + abs($prevCashBonusAmount) + abs($totalDepositedAmount);
                        $total_wallet_balance = $totalCurrentBalance;
                        $wallet_opening_balance = $userWallet->total_balance;
                        $optionUpdate = array(
                            'table' => 'wallet',
                            'data' => array(
                                'deposited_amount' => $totalDepositedAmount,
                                'total_balance' => $totalCurrentBalance,
                                'update_date' => date('Y-m-d H:i:s')
                            ),
                            'where' => array('user_id' => $userData->user_id)
                        );
                        $this->common_model->customUpdate($optionUpdate);
                        $cash_opening_balance = $userWallet->total_balance;
                        $cash_credit = $amount;
                        $cash_available_balance = $totalCurrentBalance;
                    } else {
                        $total_wallet_balance = $amount;
                        $optionW = array(
                            'table' => 'wallet',
                            'data' => array(
                                'user_id' => $userData->user_id,
                                'deposited_amount' => $amount,
                                'total_balance' => $amount,
                                'create_date' => date('Y-m-d H:i:s')
                            )
                        );
                        $this->common_model->customInsert($optionW);
                        $cash_credit = $amount;
                        $cash_available_balance = $amount;
                        $option = array(
                            'table' => 'wallet',
                            'where' => array('user_id' => $userData->user_id),
                            'single' => true
                        );
                        $userWallet = $this->common_model->customGet($option);
                    }
                    /** To check first time user bonus * */
                    $flagGetBonus = 1;
                    if (!empty($userWallet)) {
                        if ($userWallet->is_first_bonus != 1) {
                            $flagGetBonus = 0;
                        }
                    } else {
                        $flagGetBonus = 0;
                    }
                    if ($flagGetBonus != 1) {
                        //firstTimeDepositBonusCash($userData->user_id, $amount);
                        
                        /*$minBountAmount = getConfig('first_time_deposite_bonus_min_amount');
                        if ($minBountAmount <= $amount) {
                            $opening_balance = 0;
                            $cr = 0;
                            $available_balance = 0;
                            $options = array(
                                'table' => 'wallet',
                                'select' => 'cash_bonus_amount,winning_amount,total_balance',
                                'where' => array('user_id' => $userData->user_id),
                                'single' => true
                            );
                            $UserChip = $this->common_model->customGet($options);
                            if (!empty($UserChip)) {
                                $bonusChipCredit = $amount;
                                if ($amount > 1000) {
                                    $bonusChipCredit = 1000;
                                }
                                // $bonus_chip = abs($UserChip->bonus_chip) + abs($bonusChipCredit);
                                $bonus_chip = abs($UserChip->cash_bonus_amount) + abs($bonusChipCredit);
                                $totalChip = abs($UserChip->total_balance) + abs($bonusChipCredit);
                                $opening_balance = $UserChip->total_balance;
                                $cr = $bonusChipCredit;
                                $available_balance = $totalChip;
                                $optionsChip = array(
                                    'table' => 'wallet',
                                    'data' => array('cash_bonus_amount' => $bonus_chip,
                                        'total_balance' => $totalChip),
                                    'where' => array('user_id' => $userData->user_id)
                                );
                                $this->common_model->customUpdate($optionsChip);
                            } else {
                            }
                            $options = array(
                                'table' => 'transactions_history',
                                'data' => array(
                                    'user_id' => $userData->user_id,
                                    'match_id' => 0,
                                    'opening_balance' => $opening_balance,
                                    'cr' => $cr,
                                    'orderId' => $orderid,
                                    'available_balance' => $available_balance,
                                    'message' => "First time deposit get bonus theteamgenie cash",
                                    'datetime' => date('Y-m-d H:i:s'),
                                    'transaction_type' => 'CASH',
                                    'pay_type' => "BONUS"
                                )
                            );
                            $this->common_model->customInsert($options);
                        }
                        $options = array('table' => 'wallet',
                            'data' => array('is_first_bonus' => 1),
                            'where' => array('user_id' => $userData->user_id));
                        $this->common_model->customUpdate($options);*/
                    }
                    /* user payment transaction history add */
                    $options = array(
                        'table' => 'transactions_history',
                        'data' => array(
                            'user_id' => $userData->user_id,
                            'match_id' => 0,
                            'orderId' => $orderid,
                            'opening_balance' => $userWallet->deposited_amount,
                            'cr' => $cash_credit,
                            'available_balance' => $userWallet->deposited_amount + $cash_credit,
                            'message' => "Deposit amount",
                            'datetime' => date('Y-m-d H:i:s'),
                            'transaction_type' => 'CASH',
                            'pay_type' => "DEPOSIT",
                            'total_wallet_balance' => $total_wallet_balance,
                            'wallet_opening_balance' => $wallet_opening_balance
                        )
                    );
                    $this->common_model->customInsert($options);

                    $this->deposit_coupon_bonus($userData->coupon_id,$userData->user_id,$cash_credit);
                    $this->auto_deposit_coupon_bonus($userData->user_id,$cash_credit);

                    /* user payment sent mail */
                    $html = array();
                    $html['logo'] = base_url() . getConfig('site_logo');
                    $html['site'] = getConfig('site_name');
                    $html['amount'] = $amount;
                    $html['orderId'] = $orderid;
                    $html['user'] = ucwords($userData->first_name);
                    $email_template = $this->load->view('email/user_payment_information_tpl', $html, true);
                    send_mail($email_template, '[' . getConfig('site_name') . '] Your deposit is successful. Start playing now!', $userData->email, getConfig('admin_email'));
                    /* admin notification */
                    $options = array(
                        'table' => 'notifications',
                        'data' => array(
                            'user_id' => 1,
                            'type_id' => 0,
                            'sender_id' => $userData->user_id,
                            'noti_type' => 'DEPOSITE_AMOUNT',
                            'message' => "New Deposited Cash " . getConfig('currency') . ". " . $amount,
                            'read_status' => 'NO',
                            'sent_time' => date('Y-m-d H:i:s'),
                            'user_type' => 'ADMIN'
                        )
                    );
                    $this->common_model->customInsert($options);
                    /* user notification */
                    $options = array(
                        'table' => 'notifications',
                        'data' => array(
                            'user_id' => $userData->user_id,
                            'type_id' => 0,
                            'sender_id' => 1,
                            'noti_type' => 'USER_DEPOSITE_AMOUNT',
                            'message' => "Your deposit of " . getConfig('currency') . ". " . $amount . " was successful.",
                            'read_status' => 'NO',
                            'sent_time' => date('Y-m-d H:i:s'),
                            'user_type' => 'USER'
                        )
                    );
                    $this->common_model->customInsert($options);
                    //everyDepositBonusCash($userData->user_id, $amount);
                    cashWalletReports($userData->user_id, $amount, 'DEBIT');
                    $return['status'] = 1;
                    $return['message'] = "Payment Success";
                    $this->response($return);
                    exit;
                }
            } else {
                $return['status'] = 0;
                $return['message'] = "Payment failure";
                $this->response($return);
                exit;
            }
        }
    }

   /**
     * Function Name: payTm_app_test
     * Description:   To pay by paytm method for app test
     */
    function payTm_app_test_post() {
        $return['code'] = 200;
        $return['response'] = new stdClass();
        $data = $this->input->post();
        $this->form_validation->set_rules('login_session_key', 'Login session key', 'trim|required|callback__validate_login_session_key');
        $this->form_validation->set_rules('payment', 'Payment', 'trim|required|numeric');
        $this->form_validation->set_rules('user_id', 'User Id', 'trim|required|numeric');
        if ($this->form_validation->run() == FALSE) {
            $error = $this->form_validation->rest_first_error_string();
            $return['status'] = 0;
            $return['message'] = $error;
        } else {
            $dataResponse = array();
            $paramList = array();
            $user_id = extract_value($data, 'user_id', '');
            $ORDER_ID = "ORD" . rand(10000, 99999999);
            $CUST_ID = "CUST" . $user_id;
            $INDUSTRY_TYPE_ID = "Retail";
            $CHANNEL_ID = "WEB";
            $TXN_AMOUNT = '1.0';

             $dataResponse['MID']=  $paramList['MID'] = "Aavish21170550533931";
             $dataResponse['ORDER_ID'] =   $paramList['ORDER_ID'] = $ORDER_ID;
             $dataResponse['CUST_ID'] =   $paramList['CUST_ID'] = $CUST_ID;
             $dataResponse['INDUSTRY_TYPE_ID'] =  $paramList['INDUSTRY_TYPE_ID'] = "Retail";
            $dataResponse['CHANNEL_ID'] =   $paramList['CHANNEL_ID'] = "WEB";
            $dataResponse['TXN_AMOUNT'] =  $paramList['TXN_AMOUNT'] = $TXN_AMOUNT;
             $dataResponse['WEBSITE'] = $paramList['WEBSITE'] = "APP_STAGING";
              $dataResponse['CALLBACK_URL'] = $paramList['CALLBACK_URL'] ="https://pguat.paytm.com/paytmchecksum/paytmCallback.jsp";



            // Create an array having all required parameters for creating checksum.
            // $dataResponse['MID'] = $paramList["MID"] = 'Aavish57727832299136';
            // $dataResponse['ORDER_ID'] = $paramList["ORDER_ID"] = '148Qwer';
            // $dataResponse['CUST_ID'] = $paramList["CUST_ID"] = '45678asdf';
            // $dataResponse['INDUSTRY_TYPE_ID'] = $paramList["INDUSTRY_TYPE_ID"] = $INDUSTRY_TYPE_ID;
            // $dataResponse['CHANNEL_ID'] = $paramList["CHANNEL_ID"] = $CHANNEL_ID;
            // $dataResponse['TXN_AMOUNT'] = $paramList["TXN_AMOUNT"] = $TXN_AMOUNT;
            // $dataResponse['WEBSITE'] = $paramList["WEBSITE"] = "WEB_STAGING";
            // $dataResponse['MOBILE_NO'] = $paramList["MOBILE_NO"] = "7777777777";
            // $dataResponse['EMAIL'] = $paramList["EMAIL"] = "murshid1.alam@paytm.com";

            // $dataResponse['CALLBACK_URL'] = $paramList["CALLBACK_URL"] = 'https://pguat.paytm.com/paytmchecksum/paytmCallback.jsp';


            $dataResponse['CHECKSUMHASH'] = $this->getChecksumFromArray($paramList, 'CySUL%EZps@5@uH2');
            //$dataResponse['PAYTM_TXN_URL'] = $this->PAYTM_TXN_URL;
            if (!empty($dataResponse['CHECKSUMHASH'])) {
                $option = array(
                    'table' => 'payment',
                    'data' => array(
                        'user_id' => $user_id,
                        'orderId' => '148Qwer',
                        'amount' => $TXN_AMOUNT,
                        'datetime' => date('Y-m-d H:i:s'),
                    ),
                );
                $userData = $this->common_model->customInsert($option);
                $return['response'] = $dataResponse;
                $return['status'] = 1;
                $return['message'] = 'Pay to payTm';
            } else {
                $return['status'] = 0;
                $return['message'] = 'Invalid payment process';
            }
        }
        $this->response($return);
    }

    /**
     * Function Name: bank_account_details
     * Description:   To get bank account details
     */

    function bank_account_details_post() {

        $return['code'] = 200;
        $data = $this->input->post();
        $this->form_validation->set_rules('login_session_key', 'Login session key', 'trim|required|callback__validate_login_session_key');

         if ($this->form_validation->run() == FALSE) {
            $error = $this->form_validation->rest_first_error_string();
            $return['status'] = 0;
            $return['message'] = $error;
        } else {
             $bankArr = array();
            $options = array(
                      'table' => 'bank_account_information',
                      'select' => '*',
                      'where' => array('delete_status' => 0)

                );
            $bankDetails = $this->common_model->customGet($options);
            if(!empty($bankDetails))
            {
                foreach($bankDetails as $bank)
                {

                    $image = base_url() . DEFAULT_USER_IMG_PATH;
                    if (!empty($bank->icon_image)) {
                        $image = base_url() . $bank->icon_image;
                    }

                    $temp['bank_name']      = $bank->bank_name;
                    $temp['branch_name']    = $bank->branch_name;
                    $temp['account_number'] = $bank->account_number;
                    $temp['ifsc_code']      = $bank->ifsc_code;
                    $temp['icon_image']     = $image;
                    
                    $bankArr[] = $temp;

                }

                $return['response'] = $bankArr;
                $return['status'] = 1;
                $return['message'] = 'Bank account details found successfully';
            }else{
                $return['status'] = 0;
                $return['message'] = 'Bank account details not found';
            }  
        }

        $this->response($return);

    }



     /**
     * Function Name: bank_wire_add_amount
     * Description:   To add amount through bank wire
     */
    function bank_wire_add_amount_post() {
      
        $return['code'] = 200;
        $data = $this->input->post();
        $this->form_validation->set_rules('login_session_key', 'Login session key', 'trim|required|callback__validate_login_session_key');
        $this->form_validation->set_rules('amount', 'Amount', 'trim|required');
        $this->form_validation->set_rules('user_id', 'User Id', 'trim|required|numeric');
        $this->form_validation->set_rules('account_number', 'Account Number', 'trim|required');
        $this->form_validation->set_rules('txn_id', 'Transaction Id', 'trim|required');
        
       
        if ($this->form_validation->run() == FALSE) {
            $error = $this->form_validation->rest_first_error_string();
            $return['status'] = 0;
            $return['message'] = $error;
        } else {

            $user_id = extract_value($data, 'user_id', '');
            $account_number = extract_value($data, 'account_number', '');
            $amount = extract_value($data, 'amount', '');
            $txn_id = extract_value($data, 'txn_id', '');
            $orderId = "ORD" . rand(10000, 99999999);
            $coupon_code = extract_value($data, 'coupon_code', '');
            $coupon_id = 0;
            $currDate = date('Y-m-d');

            $txnSlipImage = "";
            if (!empty($_FILES['txn_image_slip']['name'])) {

                $txnSlip = fileUpload('txn_image_slip', 'users', 'png|jpg|jpeg|gif|pdf');
                $txnSlipImage = 'uploads/users/' . $txnSlip['upload_data']['file_name'];
                  
            }

            if(!empty($coupon_code))
            {
                $option = array(
                    'table' => 'coupons',
                    'select' => 'coupon_type,coupon_code,user_size,total_use_user,cash_type,amount,id,used_type,min_amount,max_amount,percentage_in_amount',
                    'where' => array('coupon_code' => $coupon_code,'end_date >=' => $currDate,'start_date <=' => $currDate,'status'=>1),
                    'where_in' => array('coupon_type' => array(1, 4)),
                    'order' => array('id' => 'DESC'),
                    'single' => true
                );
                $isCouponCode = $this->common_model->customGet($option);
//print_r($isCouponCode);die;
                if(!empty($isCouponCode))
                {
                    if(($amount >= $isCouponCode->min_amount && $amount <= $isCouponCode->max_amount) || $isCouponCode->coupon_type == 4)
                    {
                        if($isCouponCode->coupon_type == 4){
                            $option = array(
                                'table' => 'custom_coupons',
                                'select' => 'id',
                                'where' => array('coupon_id' => $isCouponCode->id,'add_amount' => $amount),
                                'single' => true
                            );
                            $getAmountData = $this->common_model->customGet($option);
                            //print_r($this->db->last_query());die;
                            if(empty($getAmountData)){
                                $return['status'] = 0;
                                $return['message'] = 'Please check your add amount!';
                                $this->response($return);
                                exit;
                            }
                        }

                        if(($isCouponCode->user_size > $isCouponCode->total_use_user))
                        {

                            $option = array(
                                'table' => 'coupons_user',
                                'select' => 'user_id',
                                'where' => array('user_id' => $user_id,'coupns_id' => $isCouponCode->id),
                                'single' => true

                                );
                            $totalusedcoupon = $this->common_model->customCount($option);

                            if($isCouponCode->used_type > $totalusedcoupon)
                            {
                                $coupon_id = $isCouponCode->id;
                                  
                            }else{

                               $return['status'] = 0;
                               $return['message'] = 'Your limit has been expired for this coupon!';
                               $this->response($return);
                               exit;
                            }
                       
                        }else{

                            $return['status'] = 0;
                            $return['message'] = 'Coupon limit has been expired!';
                            $this->response($return);
                            exit;
                        }
                    }else{
                        $return['status'] = 0;
                        $return['message'] = 'Invalid coupon code!';
                        $this->response($return);
                        exit;
                    }
                }else{

                        $return['status'] = 0;
                        $return['message'] = 'Invalid coupon code!';
                        $this->response($return);
                        exit;
                } 
            } 

            if(!empty($coupon_code))
            {
                $coupon_id = $coupon_id;
            }else{
                $coupon_id = 0;
            }

              $option = array(

                'table' => 'payment',
                'data' => array(
                        'user_id' => $user_id,
                        'orderId' => $orderId,
                        'amount'  => $amount,
                        'txnid'   => $txn_id,
                        'coupon_id'   => $coupon_id,
                        'payment_type' => 'BANKWIRE',
                        'status' => 'PENDING',
                        'txn_slip_image' => $txnSlipImage,
                        'account_number' => $account_number,
                        'datetime' => datetime()
                     )

                );

            $transInsert = $this->common_model->customInsert($option);

            if($transInsert)
            {
                $option = array(

                    'table' => 'wallet',
                    'select' => '*',
                    'where' => array('user_id'=> $user_id),
                    'single' => true

                );
                $walletAmount = $this->common_model->customGet($option);
                $deposited_amount = 0;
                $total_balance = 0;
                if(!empty($walletAmount))
                {
                   $deposited_amount = $walletAmount->deposited_amount;
                   $total_balance = $walletAmount->total_balance;
                }

                    
                $options = array(
                    'table' => 'notifications',
                    'data' => array(
                        'user_id' => $user_id,
                        'type_id' => 0,
                        'sender_id' => 1,
                        'noti_type' => 'PAYMENT_REQUEST',
                        'message' => "Payment request successfully sent",
                        'read_status' => 'NO',
                        'sent_time' => date('Y-m-d H:i:s'),
                        'user_type' => 'ADMIN'
                    )
                );
                $this->common_model->customInsert($options);

                $return['status'] = 1;
                $return['message'] = 'Request submitted successfully';
            }else{
                   
                $return['status'] = 0;
                $return['message'] = 'Request failed to submit';
            }

        }
        $this->response($return);

    }

    function deposit_coupon_bonus($coupon_id,$user_id,$amount){

        $option = array(

            'table' => 'coupons',
            'select' => '*',
            'where' => array('id' => $coupon_id),
            'single' => true

            );
        $couponData = $this->common_model->customGet($option);
       
        if(!empty($couponData))
        {
               $cash_type = $couponData->cash_type;
               $amount = $amount;
               $total_use_user = $couponData->total_use_user;
               $amountPercentage = $couponData->percentage_in_amount;
               $added_amount = ($amount * $amountPercentage) / 100;
                if($couponData->coupon_type == 4){
                    $option = array(
                        'table' => 'custom_coupons',
                        'select' => 'id,get_amount',
                        'where' => array('coupon_id' => $couponData->id,'add_amount' => $amount),
                        'single' => true
                    );
                    $getAmountData = $this->common_model->customGet($option);
                    if(!empty($getAmountData))
                    { 
                        $total_use_user = $couponData->total_use_user;
                        $amountPercentage = $getAmountData->get_amount;
                        $cash_type = $couponData->cash_type;
                        $added_amount = $getAmountData->get_amount;
                    }
                }

               $cash_bonus_amount = 0;
               $deposited_amount = 0;
               $total_balance = 0;

               $options = array(

                'table' => 'wallet',
                'select' => '*',
                'where' => array('user_id' => $user_id),
                'single' => true

                );
               $walletData = $this->common_model->customGet($options);
               if(!empty($walletData))
               {
                      $cash_bonus_amount = $walletData->cash_bonus_amount;
                      $deposited_amount = $walletData->deposited_amount;
                      $total_balance = $walletData->total_balance;
                     if($cash_type == 1)
                     {
                        $cash_bonus_amount = $cash_bonus_amount + $added_amount;
                        $transaction_type = 'BONUS';
                     }else{
                        $deposited_amount = $deposited_amount + $added_amount;
                        $transaction_type = 'CASH';
                     }
                      
                      $option = array(
                        'table' => 'wallet',
                        'data' => array(
                            'deposited_amount' => $deposited_amount,
                            'cash_bonus_amount' => $cash_bonus_amount,
                            'total_balance' => $total_balance + $added_amount,
                            'update_date' => datetime()

                            ),
                        'where' => array('user_id' => $user_id)

                        );
                      $updataData = $this->common_model->customUpdate($option); 
               }else{
                   
                      if($cash_type == 1)
                         {
                            $cash_bonus_amount = $cash_bonus_amount + $added_amount;
                            $transaction_type = 'BONUS';
                         }else{
                            $deposited_amount = $deposited_amount + $added_amount;
                            $transaction_type = 'CASH';
                         }

                          $option = array(
                            'table' => 'wallet',
                            'data' => array(
                                'user_id' => $user_id,
                                'deposited_amount' => $deposited_amount,
                                'cash_bonus_amount' => $cash_bonus_amount,
                                'total_balance' => $total_balance + $added_amount,
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
                        'where' => array('id' => $couponData->id)

                        );
                   $this->common_model->customUpdate($option);

                    $option1 = array(

                        'table' => 'coupons_user',
                        'data' => array(
                            'user_id' => $user_id,
                            'coupns_id' => $couponData->id,
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
                            'cr' => $added_amount,
                            'available_balance' => $total_balance + $added_amount,
                            'message' => "coupon offer",
                            'datetime' => date('Y-m-d H:i:s'),
                            'transaction_type' => $transaction_type,
                            'pay_type' => "WITH DEPOSIT OFFER",
                            'sports_type' => 0,
                            'orderId' => $orderId,
                            'total_wallet_balance' => $total_balance + $added_amount,
                            'wallet_opening_balance' => $total_balance,
                            'coupons_id' => $couponData->id
                        )
                    );
                  $this->common_model->customInsert($options);

                    $option = array(
                        'table' => 'cash_bonus_expire',
                        'data' => array(
                            'user_id' => $user_id,
                            'coupons_id' => $couponData->id,
                            'cash_type' => $cash_type,
                            'cash_bonus_amount' => $added_amount,
                            'total_cash_bonus_amount' => $cash_bonus_amount,
                            'bonus_at' => date('Y-m-d')
                        )
                    );

                    $this->common_model->customInsert($option);

            }

          return true;
    }

    function auto_deposit_coupon_bonus($user_id,$amount){

        $currDate = date('Y-m-d');
        $option = array(

            'table' => 'coupons',
            'select' => '*',
            'where' => array('coupon_type' => 0,'end_date >=' => $currDate,'start_date <=' => $currDate,'status' => 1),
            'single' => true

            );
        $isCouponCode = $this->common_model->customGet($option);
        
        if(!empty($isCouponCode))
        {
            if($amount >= $isCouponCode->min_amount && $amount <= $isCouponCode->max_amount)
          {
            if($isCouponCode->user_size > $isCouponCode->total_use_user)
             {
                      $options = array(

                            'table' => 'coupons_user',
                            'select' => 'id',
                            'where' => array('user_id' => $user_id,'coupns_id' => $isCouponCode->id)

                        );
                        $used_coupon = $this->common_model->customCount($options);

                        if($isCouponCode->used_type > $used_coupon)
                      {
                         
                           $cash_type = $isCouponCode->cash_type;
                           $amount = $amount;
                           $total_use_user = $isCouponCode->total_use_user;
                           $amountPercentage = $isCouponCode->percentage_in_amount;

                           $added_amount = ($amount * $amountPercentage) / 100;
                           $cash_bonus_amount = 0;
                           $deposited_amount = 0;
                           $total_balance = 0;

                            $options = array(

                            'table' => 'wallet',
                            'select' => '*',
                            'where' => array('user_id' => $user_id),
                            'single' => true

                            );
                           $walletData = $this->common_model->customGet($options);
                           if(!empty($walletData))
                           {
                                  $cash_bonus_amount = $walletData->cash_bonus_amount;
                                  $deposited_amount = $walletData->deposited_amount;
                                  $total_balance = $walletData->total_balance;
                                 if($cash_type == 1)
                                 {
                                    $cash_bonus_amount = $cash_bonus_amount + $added_amount;
                                    $transaction_type = 'BONUS';
                                 }else{
                                    $deposited_amount = $deposited_amount + $added_amount;
                                    $transaction_type = 'CASH';
                                 }
                                  
                                  $option = array(
                                    'table' => 'wallet',
                                    'data' => array(
                                        'deposited_amount' => $deposited_amount,
                                        'cash_bonus_amount' => $cash_bonus_amount,
                                        'total_balance' => $total_balance + $added_amount,
                                        'update_date' => datetime()

                                        ),
                                    'where' => array('user_id' => $user_id)

                                    );
                                  $updataData = $this->common_model->customUpdate($option); 
                           }else{
                               
                                  if($cash_type == 1)
                                     {
                                        $cash_bonus_amount = $cash_bonus_amount + $added_amount;
                                        $transaction_type = 'BONUS';
                                     }else{
                                        $deposited_amount = $deposited_amount + $added_amount;
                                        $transaction_type = 'CASH';
                                     }

                                      $option = array(
                                        'table' => 'wallet',
                                        'data' => array(
                                            'user_id' => $user_id,
                                            'deposited_amount' => $deposited_amount,
                                            'cash_bonus_amount' => $cash_bonus_amount,
                                            'total_balance' => $total_balance + $added_amount,
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
                                        'cr' => $added_amount,
                                        'available_balance' => $total_balance + $added_amount,
                                        'message' => "coupon offer",
                                        'datetime' => date('Y-m-d H:i:s'),
                                        'transaction_type' => $transaction_type,
                                        'pay_type' => "AUTO DEPOSIT OFFER",
                                        'sports_type' => 0,
                                        'orderId' => $orderId,
                                        'total_wallet_balance' => $total_balance + $added_amount,
                                        'wallet_opening_balance' => $total_balance,
                                        'coupons_id' => $isCouponCode->id
                                    )
                                );
                              $this->common_model->customInsert($options);

                            $option = array(
                                'table' => 'cash_bonus_expire',
                                'data' => array(
                                    'user_id' => $user_id,
                                    'coupons_id' => $isCouponCode->id,
                                    'cash_type' => $cash_type,
                                    'cash_bonus_amount' => $added_amount,
                                    'total_cash_bonus_amount' => $cash_bonus_amount,
                                    'bonus_at' => date('Y-m-d')
                                )
                            );

                            $this->common_model->customInsert($option);

                      }
                 }
           }
        }

           return true;


    }

    function razorpayResponse_app_post() {
        $return['code'] = 200;
        $data = $this->input->post();
        $this->form_validation->set_rules('login_session_key', 'Login session key', 'trim|required|callback__validate_login_session_key');
        $this->form_validation->set_rules('user_id', 'User Id', 'trim|required|numeric');
        $this->form_validation->set_rules('razorpay_payment_id', 'Payment Id', 'trim|required');
        if ($this->form_validation->run() == FALSE) {
            $error = $this->form_validation->rest_first_error_string();
            $return['status'] = 0;
            $return['message'] = $error;
        } else {

            $success = true;
            $keyId = 'rzp_live_wJFOS9kdryOpsa';
            $keySecret = 'Z68L1ug5ohc8weuVCmqfqVFg';

            // $keyId = 'rzp_test_Bvfzyp9eOP2UGu';
            // $keySecret = 'yyqrFnD6lzOXGJO8agDyKtaO';
            
            $displayCurrency = 'INR';
            $api = new Api($keyId, $keySecret);
            $payment_id = extract_value($data, 'razorpay_payment_id', '');
            $user_id = extract_value($data, 'user_id', '');
            $orderid = rand(10000, 99999999);

            $paymentResonse = $api->payment->fetch($payment_id);

            $amount = $paymentResonse->amount / 100;
            $currency = $paymentResonse->currency;
           

            if (empty($_POST['razorpay_payment_id']) === false) {
                $return['status'] = 1;
                $return['message'] = "Payment Success";
                $this->response($return);
                exit;

                $option = array(
                    'table' => 'payment',
                    'select' => 'payment.orderId',
                    'join' => array('users' => 'users.id=payment.user_id'),
                    'where' => array('payment.orderId' => $orderid,
                        'payment.user_id' => $user_id,
                        'payment.status' => 'SUCCESS'),
                    'single' => true
                );
                $paymentCheckIsDone = $this->common_model->customGet($option);
                if (!empty($paymentCheckIsDone)) {
                    $return['status'] = 0;
                    $return['message'] = "Payment already done for ORDERID:$orderid";
                    $this->response($return);
                    exit;
                }
                /* user payment add */

                $option = array(
                    'table' => 'payment',
                    'data' => array(
                        'user_id' => $user_id,
                        'currency' => "INR",
                        'txnid' => $payment_id,
                        'orderId' => $orderid,
                        'amount' => $amount,
                        'datetime' => date('Y-m-d H:i:s'),
                        'payment_type' => 'RAZOR',
                        'status' => 'SUCCESS'
                    ),
                );
                $userData = $this->common_model->customInsert($option);

                $option = array(
                    'table' => 'payment',
                    'select' => 'payment.user_id,users.email,users.first_name',
                    'join' => array('users' => 'users.id=payment.user_id'),
                    'where' => array('payment.orderId' => $orderid),
                    'single' => true
                );
                $userData = $this->common_model->customGet($option);
                if (!empty($userData)) {
                    /** If the player has credit balance then system will automatically take 5% from playwinfantsy chips account * */
                    $creditCash = (abs($amount) * 5) / 100;
                    $creditChipBonus = round($creditCash);
                    $cash_opening_balance = 0;
                    $cash_credit = 0;
                    $cash_available_balance = 0;
                    /* user payment wallet add & update */
                    $option = array(
                        'table' => 'wallet',
                        'where' => array('user_id' => $userData->user_id),
                        'single' => true
                    );
                    $userWallet = $this->common_model->customGet($option);
                    if (!empty($userWallet)) {
                        $prevDepositedAmount = $userWallet->deposited_amount;
                        $curDepositedAmount = $amount;
                        $totalDepositedAmount = abs($prevDepositedAmount) + abs($curDepositedAmount);
                        $prevWinningAmount = $userWallet->winning_amount;
                        $prevCashBonusAmount = $userWallet->cash_bonus_amount;
                       /* $totalCurrentBalance = abs($prevWinningAmount) + abs($totalDepositedAmount);*/
                        $totalCurrentBalance =  abs($prevWinningAmount) + abs($prevCashBonusAmount) + abs($totalDepositedAmount);
                        $optionUpdate = array(
                            'table' => 'wallet',
                            'data' => array(
                                'deposited_amount' => $totalDepositedAmount,
                                'total_balance' => $totalCurrentBalance,
                                'update_date' => date('Y-m-d H:i:s')
                            ),
                            'where' => array('user_id' => $userData->user_id)
                        );
                        $this->common_model->customUpdate($optionUpdate);
                        $cash_opening_balance = $userWallet->total_balance;
                        $cash_credit = $amount;
                        $cash_available_balance = $totalCurrentBalance;
                    } else {
                        $optionW = array(
                            'table' => 'wallet',
                            'data' => array(
                                'user_id' => $userData->user_id,
                                'deposited_amount' => $amount,
                                'total_balance' => $amount,
                                'create_date' => date('Y-m-d H:i:s')
                            )
                        );
                        $this->common_model->customInsert($optionW);
                        $cash_credit = $amount;
                        $cash_available_balance = $amount;
                    }
                   
                    /* user payment transaction history add */
                    $options = array(
                        'table' => 'transactions_history',
                        'data' => array(
                            'user_id' => $userData->user_id,
                            'match_id' => 0,
                            'orderId' => $orderid,
                            'opening_balance' => $cash_opening_balance,
                            'cr' => $cash_credit,
                            'available_balance' => $cash_available_balance,
                            'message' => "Deposit amount Rs. $cash_credit order id $orderid",
                            'datetime' => date('Y-m-d H:i:s'),
                            'transaction_type' => 'CASH',
                            'pay_type' => "DEPOSIT"
                        )
                    );
                    $this->common_model->customInsert($options);

                    /* user payment sent mail */

                    $html = array();
                    $html['logo'] = base_url() . getConfig('site_logo');
                    $html['site'] = getConfig('site_name');
                    $html['amount'] = $cash_credit;
                    $html['orderId'] = $orderid;
                    $html['user'] = ucwords($userData->first_name);
                    $email_template = $this->load->view('email/user_payment_information_tpl', $html, true);
                    //send_mail($email_template, 'Your deposit is successful. Start playing FanBash now!', $userData->email, getConfig('admin_email'));
                   
                    /* admin notification */
                    $options = array(
                        'table' => 'notifications',
                        'data' => array(
                            'user_id' => 1,
                            'type_id' => 0,
                            'sender_id' => $userData->user_id,
                            'noti_type' => 'DEPOSITE_AMOUNT',
                            'message' => getConfig('currency') . ". " . $amount . " Deposited in user's wallet (Email: " . $userData->email . ")",
                            'read_status' => 'NO',
                            'sent_time' => date('Y-m-d H:i:s'),
                            'user_type' => 'ADMIN'
                        )
                    );
                    $this->common_model->customInsert($options);

                    /* user notification */
                    $options = array(
                        'table' => 'notifications',
                        'data' => array(
                            'user_id' => $userData->user_id,
                            'type_id' => 0,
                            'sender_id' => 1,
                            'noti_type' => 'USER_DEPOSITE_AMOUNT',
                            'message' => "Your deposite of " . getConfig('currency') . ". " . $amount . " was successful.",
                            'read_status' => 'NO',
                            'sent_time' => date('Y-m-d H:i:s'),
                            'user_type' => 'USER'
                        )
                    );
                    $this->common_model->customInsert($options);

                    //cashWalletReports($userData->user_id, $amount, 'DEBIT');
                    /** to send push notification * */

                    $notificationData = array(
                        'title' => 'Deposite Amount',
                        'message' => "Your deposite of " . getConfig('currency') . ". " . $amount . " was successful.",
                        'type' => "USER_DEPOSITE_AMOUNT",
                        //'type_id' => 0,
                        //'user_id' => $userData->user_id,
                        'badges' => 0,
                    );

                    sendNotification($userData->user_id, $notificationData);

                    $return['status'] = 1;
                    $return['message'] = "Payment Success";
                    $this->response($return);
                    exit;
                }
                // }
                //  catch(SignatureVerificationError $e)
                // {
                //     $success = false;
                //     $option = array(
                //         'table' => 'payment',
                //         'where' => array('orderId' => $orderid)
                //     );
                //     $this->common_model->customDelete($option);
                //     $return['status'] = 0;
                //     $return['message'] = "Payment failure";
                //     $this->response($return);
                //     exit;
                // }
            } else {
                $return['status'] = 0;
                $return['message'] = "Payment failure";
                $this->response($return);
                exit;
            }
        }
    }

    function razorPay_post() {
        $return['code'] = 200;
        $return['response'] = new stdClass();
        $data = $this->input->post();
        $this->form_validation->set_rules('login_session_key', 'Login session key', 'trim|required|callback__validate_login_session_key');
        $this->form_validation->set_rules('amount', 'Amount', 'trim|required|numeric');
        $this->form_validation->set_rules('user_id', 'User Id', 'trim|required|numeric');
        $this->form_validation->set_rules('firstname', 'name', 'trim|required');
        $this->form_validation->set_rules('email', 'email', 'trim|required');
        //$this->form_validation->set_rules('phone', 'phone', 'trim|required|numeric');
        if ($this->form_validation->run() == FALSE) {
            $error = $this->form_validation->rest_first_error_string();
            $return['status'] = 0;
            $return['message'] = $error;
        } else {
            $user_id = extract_value($data, 'user_id', '');
            $TXN_AMOUNT = abs(extract_value($data, 'amount', ''));
            $TXN_AMOUNT = $TXN_AMOUNT/100;
            $dataResponse = array();
            $paramList = array();
            $user_id = extract_value($data, 'user_id', '');
            $ORDER_ID = "ORDS" . rand(10000, 99999999);
            $action = '';
            $posted = array();
            $coupon_code = extract_value($data, 'coupon_code', '');
            $coupon_id = 0;
            $currDate = date('Y-m-d');


             if(!empty($coupon_code))
             {
                $option = array(
                    'table' => 'coupons',
                    'select' => 'coupon_type,coupon_code,user_size,total_use_user,cash_type,amount,id,used_type,min_amount,max_amount,percentage_in_amount',
                    'where' => array('coupon_code' => $coupon_code,'end_date >=' => $currDate,'start_date <=' => $currDate,'status' => 1),
                    'where_in' => array('coupon_type' => array(1, 4)),
                    'single' => true
                );
                $isCouponCode = $this->common_model->customGet($option);

                if(!empty($isCouponCode))
                {
                   if(($TXN_AMOUNT >= $isCouponCode->min_amount && $TXN_AMOUNT <= $isCouponCode->max_amount) || $isCouponCode->coupon_type == 4)
                   {
                        if($isCouponCode->coupon_type == 4){
                            $option = array(
                                'table' => 'custom_coupons',
                                'select' => 'id',
                                'where' => array('coupon_id' => $isCouponCode->id,'add_amount' => $TXN_AMOUNT),
                                'single' => true
                            );
                            $getAmountData = $this->common_model->customGet($option);
                            //print_r($this->db->last_query());die;
                            if(empty($getAmountData)){
                                $return['status'] = 0;
                                $return['message'] = 'Please check your add amount!';
                                $this->response($return);
                                exit;
                            }
                        }

                    if($isCouponCode->user_size > $isCouponCode->total_use_user)
                    {

                        $option = array(
                            'table' => 'coupons_user',
                            'select' => 'user_id',
                            'where' => array('user_id' => $user_id,'coupns_id' => $isCouponCode->id),
                            'single' => true

                            );
                        $totalusedcoupon = $this->common_model->customCount($option);

                          if($isCouponCode->used_type > $totalusedcoupon)
                      {
                           $coupon_id = $isCouponCode->id;
                          
                      }else{

                           $return['status'] = 0;
                           $return['message'] = 'Your limit has been expired for this coupon!';
                           $this->response($return);
                           exit;
                      }
                       
                    }else{

                        $return['status'] = 0;
                        $return['message'] = 'Coupon limit has been expired!';
                        $this->response($return);
                        exit;
                    }
                 }else{
                        $return['status'] = 0;
                        $return['message'] = 'Invalid coupon code!';
                        $this->response($return);
                        exit;
                 }
                }else{

                        $return['status'] = 0;
                        $return['message'] = 'Invalid coupon code!';
                        $this->response($return);
                        exit;
                } 
             } 

                 if(!empty($coupon_code))
                 {
                    $coupon_id = $coupon_id;
                 }else{
                    $coupon_id = 0;
                 }


            if (!empty($_POST)) {
                unset($_POST['login_session_key']);
                unset($_POST['user_id']);
                foreach ($_POST as $key => $value) {
                    if ($key == 'amount') {
                        if (strpos($value, '.') !== FALSE) {
                            $posted[$key] = $value;
                        } else {
                            $amt = $value . '.0';
                            $posted[$key] = $amt;
                        }
                    } else {
                        $posted[$key] = $value;
                    }
                }
            }
            
            $posted['orderId'] = $ORDER_ID;
            
            $dataResponse['amount'] = $posted['amount'];
            $dataResponse['email'] = $posted['email'];
            $dataResponse['phone'] = $posted['phone'];
            $dataResponse['firstname'] = $posted['firstname'];
            $dataResponse['orderId'] = $posted['orderId'];
            $dataResponse['key_id'] = 'rzp_live_wJFOS9kdryOpsa';
            $dataResponse['key_secret'] = 'Z68L1ug5ohc8weuVCmqfqVFg';
            //$dataResponse['udf1'] = $user_id;
            $option = array(
                'table' => 'payment',
                'data' => array(
                    'user_id' => $user_id,
                    'orderId' => $ORDER_ID,
                    'coupon_id' => $coupon_id,
                    'amount' => ($posted['amount']/100),
                    'datetime' => date('Y-m-d H:i:s'),
                    'payment_type' => 'RAZOR'
                ),
            );
            $userData = $this->common_model->customInsert($option);
            $return['response'] = $dataResponse;
            $return['status'] = 1;
            $return['message'] = 'Pay to PayUMoney';
            
        }
        $this->response($return);
    }

  
}
?>

