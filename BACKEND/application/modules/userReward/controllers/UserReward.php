<?php

defined('BASEPATH') or exit('No direct script access allowed');

class UserReward extends Common_Controller
{

    public $data = array();
    public $file_data = "";
    public $_table = 'users';
    public $title = "Login data";

    public function __construct()
    {
        parent::__construct();
        $this->is_auth_admin();
    }

    /**
     * @method index
     * @description listing display
     * @return array
     */
    public function index()
    {

        $this->data['url'] = base_url() . $this->router->fetch_class();
        $this->data['pageTitle'] = "Add " . $this->title;
        $this->data['parent'] = $this->router->fetch_class();
        $this->data['model'] = $this->router->fetch_class();
        $this->data['title'] = $this->title;
        $this->data['tablePrefix'] = 'vendor_sale_' . $this->_table;
        $this->data['model'] = 'patient/open_model';
        $this->data['table'] = $this->_table;
       
        $from1 = (isset($_GET['date']) && !empty($_GET['date'])) ? date('Y-m-d', strtotime($_GET['date'])) : "";
        $to1 = (isset($_GET['date1']) && !empty($_GET['date1'])) ? date('Y-m-d', strtotime($_GET['date1'])) : "";
        
        $from = strtotime($from1);
        $to = strtotime($to1);


        if(!empty($from) and !empty($to)){
         $Sql = "SELECT U.email,U.first_name,U.last_name,LS.last_login FROM `vendor_sale_users` as U 
         JOIN `vendor_sale_login_session` as LS on U.id = LS.user_id WHERE LS.last_login >= '$from' AND LS.last_login <= '$to' ORDER BY LS.last_login DESC";
        }else{
        $Sql = "SELECT U.email,U.first_name,U.last_name,LS.last_login FROM `vendor_sale_users` as U 
         JOIN `vendor_sale_login_session` as LS on U.id = LS.user_id
         ORDER BY LS.last_login DESC";
        }

                $login_details = $this->common_model->customQuery($Sql);
                $this->data['list'] = $login_details;

    //     $option = array(
    //         'table' => 'users U',
    //         'select' => 'U.email,U.first_name,U.last_name,'
    //             . 'LS.last_login',
    //         'join' => array(
    //             array('login_session LS', 'LS.user_id=U.id', 'inner'),
    //         ),
    //         /* 'group_by' => 'P.patient_id' */
    //     ); 

    //    /*  if (!empty($AdminCareUnitID)) {
    //         $option['where']['P.care_unit_id']  = $AdminCareUnitID;
    //     } */
    //     if (!empty($from)) {
    //         $option['where']['DATE(LS.last_login) >='] = $from;
    //     }
    //     if (!empty($to)) {
    //         $option['where']['DATE(LS.last_login) <='] = $to;
    //     }
    //     $option['order'] = array('LS.last_login' => 'desc');

    //     /* print_r($option);die; */
    //     $this->data['list'] = $this->common_model->customGet($option);
 
       // print_r($careUnitID);die;
        $this->load->admin_render('list', $this->data, 'inner_script');
    }
     

}