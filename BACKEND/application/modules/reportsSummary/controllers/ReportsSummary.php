<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class ReportsSummary extends Common_Controller {

    public $data = [];

    function __construct() {

        parent::__construct();
        $this->load->library(array('ion_auth'));
        $this->load->helper(array('language'));
        $this->form_validation->set_error_delimiters($this->config->item('error_start_delimiter', 'ion_auth'), $this->config->item('error_end_delimiter', 'ion_auth'));
        $this->lang->load('auth');
    }

    public function index() {
        $this->data['parent'] = "Dashboard";
        if (!$this->ion_auth->logged_in()) {
            //$this->session->set_flashdata('message', 'Your session has been expired');
            redirect('pwfpanel/login', 'refresh');
        } else {
            if ($this->ion_auth->is_admin() || $this->ion_auth->is_subAdmin()) {

                $option = array('table' => USERS . ' as user',
                    'select' => 'user.*,group.name as group_name,UP.doc_file',
                    'join' => array(array(USER_GROUPS . ' as ugroup', 'ugroup.user_id=user.id', 'left'),
                        array(GROUPS . ' as group', 'group.id=ugroup.group_id', 'left'),
                        array('user_profile UP', 'UP.user_id=user.id', 'left')),
                    'order' => array('user.id' => 'ASC'),
                    'where' => array('user.delete_status' => 0,
                        'group.id' => 3),
                    'order' => array('user.first_name' => 'ASC')
                );

                $this->data['staward'] = $this->common_model->customGet($option);
                //$this->data['symptom_onset'] = $this->common_model->customGet($option);

                //start ---for Admin--
                $this->data['careUnit'] = $this->common_model->customGet(array('table' => 'care_unit', 'select' => 'id,name', 'where' => array('is_active' => 1, 'delete_status' => 0), 'order' => array('name' => 'ASC')));
               //end ---for Admin--

                //start ---for facility manager--
              
                //end ---for facility manager--

                $this->data['initial_dx'] = $this->common_model->customGet(array('table' => 'initial_dx', 'select' => 'id,name', 'where' => array('is_active' => 1, 'delete_status' => 0), 'order' => array('name' => 'ASC')));
                $this->data['initial_rx'] = $this->common_model->customGet(array('table' => 'initial_rx', 'select' => 'id,name', 'where' => array('is_active' => 1, 'delete_status' => 0), 'order' => array('name' => 'ASC')));
                $this->data['doctors'] = $this->common_model->customGet(array('table' => 'doctors', 'select' => 'id,name', 'where' => array('is_active' => 1, 'delete_status' => 0), 'order' => array('name' => 'ASC')));

                $this->data['symptom_onset'] = $this->common_model->customGet(array('table' => 'patient', 'select' => 'id,symptom_onset', 'where' => array('md_steward_id' => 94), 'order' => array('md_steward_id' => 'ASC')));

                $this->data['criteria_met'] = $this->common_model->customGet(array('table' => 'patient', 'select' => 'id,criteria_met', 'where' => array('md_steward_id' => 94), 'order' => array('md_steward_id' => 'ASC')));

                $this->data['criteria_met'] = $this->common_model->customGet(array('table' => 'patient', 'select' => 'id,infection_surveillance_checklist', 'where' => array('md_steward_id' => 94), 'order' => array('md_steward_id' => 'ASC')));

                $this->data['md_stayward_response'] = $this->common_model->customGet(array('table' => 'patient', 'select' => 'id,md_stayward_response', 'where' => array('md_steward_id' => 94), 'order' => array('md_steward_id' => 'ASC')));

                $this->data['culture_source'] = $this->common_model->customGet(array('table' => 'culture_source', 'select' => 'id,name', 'where' => array('is_active' => 1, 'delete_status' => 0), 'order' => array('name' => 'ASC')));

                $this->data['organism'] = $this->common_model->customGet(array('table' => 'organism', 'select' => 'id,name', 'where' => array('is_active' => 1, 'delete_status' => 0), 'order' => array('name' => 'ASC')));
                $this->data['precautions'] = $this->common_model->customGet(array('table' => 'precautions', 'select' => 'id,name', 'where' => array('is_active' => 1, 'delete_status' => 0), 'order' => array('name' => 'ASC')));

                
             // print_r($r);die;
                
                 $patients_days = $this->common_model->customGet(array('table' => 'patient', 'select' => 'SUM(total_days_of_patient_stay) as tatal_days', 'single' => true));
                $this->data['tatal_days']= $patients_days->tatal_days;
                //print_r($patients_days->tatal_days);die();

                $option = array(               

                    'table' => 'patient P',
                    'select' => '(CASE WHEN new_initial_dot IS NOT NULL THEN SUM(new_initial_dot) ELSE SUM(initial_dot) END) totalDays ,'
                    . 'P.care_unit_id',
                    'join' => array(array('patient_consult PC', 'PC.patient_id=P.id', 'inner')),
                    'group_by' => array('P.care_unit_id')
                );

                $list = $this->common_model->customGet($option);
                $this->data['total_antibiotic_days'] = $total_antibiotic_days = array_sum(array_column($list, 'totalDays'));

                $total_days = (getConfig('total_patient_days') <= 0) ? $patients_days->tatal_days : getConfig('total_patient_days');

                $this->data['toatl_patient_days_average'] = ($total_days > 0) ? round(($total_antibiotic_days / $total_days) * 1000) : 0;
              
                $this->load->admin_render('dashboards', $this->data, 'inner_script');
            } else if (  $this->ion_auth->is_facilityManager()) {

           
                $option = array('table' => USERS . ' as user',
                'select' => 'user.*,group.name as group_name,UP.doc_file',
                'join' => array(array(USER_GROUPS . ' as ugroup', 'ugroup.user_id=user.id', 'left'),
                    array(GROUPS . ' as group', 'group.id=ugroup.group_id', 'left'),
                    array('user_profile UP', 'UP.user_id=user.id', 'left')),
                'order' => array('user.id' => 'ASC'),
                'where' => array('user.delete_status' => 0,'user.id' => $_SESSION['user_id'],
                    'group.id' => 5),
                'order' => array('user.first_name' => 'ASC')
            );

            $this->data['staward'] = $this->common_model->customGet($option);
            //$this->data['symptom_onset'] = $this->common_model->customGet($option);

            //start ---for Admin--
           /*  $this->data['careUnit'] = $this->common_model->customGet(array('table' => 'care_unit', 'select' => 'id,name', 'where' => array('is_active' => 1, 'delete_status' => 0), 'order' => array('name' => 'ASC'))); */
           //end ---for Admin--

            //start ---for facility manager--
            $AdminCareUnitID = isset($_SESSION['admin_care_unit_id']) ? $_SESSION['admin_care_unit_id'] : '';

            $option = array('table' => 'care_unit', 'select' => 'id,name', 'where' => array('is_active' => 1, 'delete_status' => 0), 'order' => array('name' => 'asc'));
            if (!empty($AdminCareUnitID)) {
                $option['where']['id']  = $AdminCareUnitID;
            }
            $this->data['care_unit'] = $this->common_model->customGet($option);
    
            $this->data['careUnitss'] = json_decode($AdminCareUnitID);
    
            $careUnitDatas =array();
            foreach($this->data['careUnitss'] as $value){
    
                $option = array(
                    'table' => 'care_unit',
                    'select' => 'care_unit.id,care_unit.name',
                    'where' =>array('care_unit.id'=>$value)
                );
                $careUnitDatas[] = $this->common_model->customGet($option);
            }
            $arraySingle = call_user_func_array('array_merge', $careUnitDatas);
            $this->data['careUnitsUser'] = $arraySingle;
            //end ---for facility manager--

            $this->data['initial_dx'] = $this->common_model->customGet(array('table' => 'initial_dx', 'select' => 'id,name', 'where' => array('is_active' => 1, 'delete_status' => 0), 'order' => array('name' => 'ASC')));
            $this->data['initial_rx'] = $this->common_model->customGet(array('table' => 'initial_rx', 'select' => 'id,name', 'where' => array('is_active' => 1, 'delete_status' => 0), 'order' => array('name' => 'ASC')));
            $this->data['doctors'] = $this->common_model->customGet(array('table' => 'doctors', 'select' => 'id,name', 'where' => array('is_active' => 1, 'delete_status' => 0), 'order' => array('name' => 'ASC')));

            $this->data['symptom_onset'] = $this->common_model->customGet(array('table' => 'patient', 'select' => 'id,symptom_onset', 'where' => array('md_steward_id' => 94), 'order' => array('md_steward_id' => 'ASC')));

            $this->data['criteria_met'] = $this->common_model->customGet(array('table' => 'patient', 'select' => 'id,criteria_met', 'where' => array('md_steward_id' => 94), 'order' => array('md_steward_id' => 'ASC')));

            $this->data['criteria_met'] = $this->common_model->customGet(array('table' => 'patient', 'select' => 'id,infection_surveillance_checklist', 'where' => array('md_steward_id' => 94), 'order' => array('md_steward_id' => 'ASC')));

            $this->data['md_stayward_response'] = $this->common_model->customGet(array('table' => 'patient', 'select' => 'id,md_stayward_response', 'where' => array('md_steward_id' => 94), 'order' => array('md_steward_id' => 'ASC')));

            $this->data['culture_source'] = $this->common_model->customGet(array('table' => 'culture_source', 'select' => 'id,name', 'where' => array('is_active' => 1, 'delete_status' => 0), 'order' => array('name' => 'ASC')));

            $this->data['organism'] = $this->common_model->customGet(array('table' => 'organism', 'select' => 'id,name', 'where' => array('is_active' => 1, 'delete_status' => 0), 'order' => array('name' => 'ASC')));
            $this->data['precautions'] = $this->common_model->customGet(array('table' => 'precautions', 'select' => 'id,name', 'where' => array('is_active' => 1, 'delete_status' => 0), 'order' => array('name' => 'ASC')));
            
         // print_r($r);die;
            
             $patients_days = $this->common_model->customGet(array('table' => 'patient', 'select' => 'SUM(total_days_of_patient_stay) as tatal_days', 'single' => true));
            $this->data['tatal_days']= $patients_days->tatal_days;
            //print_r($patients_days->tatal_days);die();

            $option = array(               

                'table' => 'patient P',
                'select' => '(CASE WHEN new_initial_dot IS NOT NULL THEN SUM(new_initial_dot) ELSE SUM(initial_dot) END) totalDays ,'
                . 'P.care_unit_id',
                'join' => array(array('patient_consult PC', 'PC.patient_id=P.id', 'inner')),
                'group_by' => array('P.care_unit_id')
            );

            $list = $this->common_model->customGet($option);
            $this->data['total_antibiotic_days'] = $total_antibiotic_days = array_sum(array_column($list, 'totalDays'));

            $total_days = (getConfig('total_patient_days') <= 0) ? $patients_days->tatal_days : getConfig('total_patient_days');

            $this->data['toatl_patient_days_average'] = ($total_days > 0) ? round(($total_antibiotic_days / $total_days) * 1000) : 0;
          
            $this->load->admin_render('dashboards', $this->data, 'inner_script');
            } else if ($this->ion_auth->is_vendor()) {
                $this->load->admin_render('dashboard', $this->data, 'inner_script');
            } else {
                $this->session->set_flashdata('message', 'You are not authorised to access administration');
                redirect('pwfpanel/login', 'refresh');
            }
        }
    }

    /** new **/
    public function get_antibiotic_by_care_unit_provider() {
        $return = array();
        $return['status'] = 200;
        $antibiotic = array();
        $return['careUnit'] = $careUnit = $this->input->post('careUnit');
        $return['provider'] = $provider = $this->input->post('provider_doctor');
        $rx = $this->input->post('rx');
        $steward = $this->input->post('steward');
        $symptom_onset = $this->input->post('symptom_onset');
        $criteria_met = $this->input->post('criteria_met');
        $md_stayward_response = $this->input->post('md_stayward_response');
        $culture_source = $this->input->post('culture_source');
        $organism = $this->input->post('organism');
        $precautions = $this->input->post('precautions');
        $date_of_start_abx = $this->input->post('date_of_start_abx');
        $date_of_start_abx1 = $this->input->post('date_of_start_abx1');
        $date_of_start_abx2 = $this->input->post('date_of_start_abx2');
        $date_of_start_abx3 = $this->input->post('date_of_start_abx3');
        $date1 = (!empty($this->input->post('date1'))) ? date('Y-m-d',strtotime($this->input->post('date1'))) : '';
        $date2 = (!empty($this->input->post('date2'))) ? date('Y-m-d',strtotime($this->input->post('date2'))) : '';     
        //$careUnit = 8;
        $option = array(
            'table' => 'patient P',
            'select' => 'P.id,P.patient_id,P.doctor_id,U.name',
            'join' => array(array('doctors U', 'U.id=P.doctor_id', 'inner')),
            'group_by' => array('P.doctor_id'),
            'order' => array('U.name'=> 'ASC')
        );
        if(!empty($date_of_start_abx) && !empty($date_of_start_abx1)){
            $option['where']['month(P.date_of_start_abx)'] = $date_of_start_abx;
            $option['where']['year(P.date_of_start_abx)'] = $date_of_start_abx1;
        }

        if(!empty($date_of_start_abx2) && !empty($date_of_start_abx3)){
            $option['where']['QUARTER(P.date_of_start_abx)'] = $date_of_start_abx2;
            $option['where']['year(P.date_of_start_abx)'] = $date_of_start_abx3;
        }else if($date_of_start_abx2 == '2021'){
            $option['where']['Year(P.date_of_start_abx)'] = $date_of_start_abx2;
        }else if($date_of_start_abx3 == '2021'){
            $option['where']['Year(P.date_of_start_abx)'] = $date_of_start_abx3;
        }else if($date_of_start_abx2 == '2022'){
            $option['where']['Year(P.date_of_start_abx)'] = $date_of_start_abx2;
        }else if($date_of_start_abx3 == '2022'){
            $option['where']['Year(P.date_of_start_abx)'] = $date_of_start_abx3;
        }else if($date_of_start_abx2 == '2023'){
            $option['where']['Year(P.date_of_start_abx)'] = $date_of_start_abx2;
        }else if($date_of_start_abx3 == '2023'){
            $option['where']['Year(P.date_of_start_abx)'] = $date_of_start_abx3;
        }
        // if(!empty($date_of_start_abx) && !empty($date_of_start_abx1)){
        //     $option['where']['month(P.date_of_start_abx)'] = [01- 03];
        //     $option['where']['year(P.date_of_start_abx)'] = $date_of_start_abx1;
        // } 
        if (!empty($date1) && !empty($date2)) {
          $option['where']['date(P.date_of_start_abx) >='] = $date1;
          $option['where']['date(P.date_of_start_abx) <='] = $date2;
        }
        if (!empty($careUnit)) {
            $option['where']['P.care_unit_id'] = $careUnit;
        }
        if (!empty($provider)) {
            $option['where']['P.doctor_id'] = $provider;
        }
        if (!empty($steward)) {
            $option['where']['P.md_steward_id'] = $steward;
        }
        if(!empty($symptom_onset)){
            $option['where']['P.symptom_onset'] = $symptom_onset;
        }
        if(!empty($md_stayward_response)){
            $option['where']['P.md_stayward_response'] = $md_stayward_response;
        }
        if(!empty($culture_source)){
            $option['where']['P.culture_source'] = $culture_source;
        }
        if(!empty($organism)){
            $option['where']['P.organism'] = $organism;
        }
        if(!empty($precautions)){
            $option['where']['P.precautions'] = $precautions;
        }
        if(!empty($criteria_met=='Yes')){
            $option['where']['P.criteria_met'] = $criteria_met;
        }
        else if(!empty($criteria_met=='No')){
            $option['where']['P.criteria_met'] = $criteria_met;
        }
        else if(!empty($criteria_met=='N/A')){
            $option['where']['P.infection_surveillance_checklist'] = $criteria_met;
        }

        $steward = $this->common_model->customGet($option);
        $total_provider = count($steward);
        $optionsGraph = array();
        $mediRx = array();
        if (!empty($steward)) {
            foreach ($steward as $row) {
                $Temp['md_name'] = $row->name;
                $Temp['doctor_id'] = $row->doctor_id;
                $option = array(
                    'table' => 'patient P',
                    'select' => 'DISTINCT(PC.initial_rx) as rx_id,count(PC.initial_rx) as total,SUM(PC.initial_dot) as totalDays,P.care_unit_id,IRX.name,
                    CI.name as care_name,IRX.description',
                    'join' => array(array('care_unit CI', 'CI.id=P.care_unit_id', 'inner'),
                        array('patient_consult PC', 'PC.patient_id=P.id', 'inner'),
                        array('initial_rx IRX', 'IRX.id=PC.initial_rx', 'inner')),
                    'group_by' => array('PC.initial_rx'),
                    'order' => array('PC.initial_rx' => "ASC")
                );

                if (!empty($careUnit)) {
                    $option['where']['P.care_unit_id'] = $careUnit;
                }

                if (!empty($rx)) {
                    $option['where']['PC.initial_rx'] = $rx;
                }
                if(!empty($date_of_start_abx) && !empty($date_of_start_abx1)){
                    $option['where']['month(P.date_of_start_abx)'] = $date_of_start_abx;
                    $option['where']['year(P.date_of_start_abx)'] = $date_of_start_abx1;
                }
                if(!empty($date_of_start_abx2) && !empty($date_of_start_abx3)){
                    $option['where']['QUARTER(P.date_of_start_abx)'] = $date_of_start_abx2;
                    $option['where']['year(P.date_of_start_abx)'] = $date_of_start_abx3;
                }else if($date_of_start_abx2 == '2021'){
                    $option['where']['Year(P.date_of_start_abx)'] = $date_of_start_abx2;
                }else if($date_of_start_abx3 == '2021'){
                    $option['where']['Year(P.date_of_start_abx)'] = $date_of_start_abx3;
                }else if($date_of_start_abx2 == '2022'){
                    $option['where']['Year(P.date_of_start_abx)'] = $date_of_start_abx2;
                }else if($date_of_start_abx3 == '2022'){
                    $option['where']['Year(P.date_of_start_abx)'] = $date_of_start_abx3;
                }else if($date_of_start_abx2 == '2023'){
                    $option['where']['Year(P.date_of_start_abx)'] = $date_of_start_abx2;
                }else if($date_of_start_abx3 == '2023'){
                    $option['where']['Year(P.date_of_start_abx)'] = $date_of_start_abx3;
                }
                if (!empty($date1) && !empty($date2)) {
                    $option['where']['date(P.date_of_start_abx) >='] = $date1;
                    $option['where']['date(P.date_of_start_abx) <='] = $date2;
                  }
                if(!empty($symptom_onset)){
                    $option['where']['P.symptom_onset'] = $symptom_onset;
                }
                if(!empty($md_stayward_response)){
                    $option['where']['P.md_stayward_response'] = $md_stayward_response;
                }
                if(!empty($culture_source)){
                    $option['where']['P.culture_source'] = $culture_source;
                }
                if(!empty($organism)){
                    $option['where']['P.organism'] = $organism;
                }
                if(!empty($precautions)){
                    $option['where']['P.precautions'] = $precautions;
                }
                if(!empty($criteria_met=='Yes')){
                    $option['where']['P.criteria_met'] = $criteria_met;
                }
                else if(!empty($criteria_met=='No')){
                    $option['where']['P.criteria_met'] = $criteria_met;
                }
                else if(!empty($criteria_met=='N/A')){
                    $option['where']['P.infection_surveillance_checklist'] = $criteria_met;
                }
                $option['where']['P.doctor_id'] = $row->doctor_id;
                $list = $this->common_model->customGet($option);
                //echo $this->db->last_query;
                $Temp['all'] = array();
                if (!empty($list)) {
                    $total_antibiotic_used = array_sum(array_column($list, 'total'));
                    $total_antibiotic = count($list);
                    $color = array();
                    $percentages = array();
                    $labels = array();
                    $multi_labels = array();
                    foreach ($list as $rows) {
                        $percentage = round(($rows->totalDays / $total_antibiotic_used) * 100);
                        $Temp1['percentage'] = $rows->totalDays;
                        $Temp1['total_used'] = $rows->totalDays;
                        $Temp1['name'] = $rows->name;
                        $Temp1['rx_id'] = $rows->rx_id;
                        $Temp1['color'] = $rows->description;
                        $color[] = $rows->description;
                        $percentages[] = $percentage;
                        $labels[$rows->rx_id] = $rows->name;
                        $mediRx[$rows->rx_id]['id'] = $rows->rx_id;
                        $mediRx[$rows->rx_id]['name'] = $rows->name;
                        $mediRx[$rows->rx_id]['color'] = $rows->description;
                        $multi_labels[$rows->rx_id] = array('rx_id' => $rows->rx_id, 'Name' => $rows->name, 'percentage' => $rows->totalDays, 'color' => $rows->description, 'md_id' => $row->doctor_id);
                    }
                    $Temp['all'] = $multi_labels;
                }
                $optionsGraph[] = $Temp;
            }
        }
        $allData = $optionsGraph;
        //dump($optionsGraph);
        sort($mediRx);
        $Temp12 = array();
        foreach ($optionsGraph as $graph) {
            $all = $graph['all'];
            $all_only_id = array_column($all, "rx_id");
            $md_id = $graph['doctor_id'];
            foreach ($mediRx as $rx) {
                $Temp12[$rx['id']]['label'] = $rx['name'];
                $Temp12[$rx['id']]['backgroundColor'] = $rx['color'];
                if (in_array($rx['id'], $all_only_id)) {
                    if (isset($all[$rx['id']]) && $all[$rx['id']]['md_id'] == $md_id) {
                        if (!empty($all[$rx['id']])) {
                            $Temp12[$rx['id']]['data'][] = $all[$rx['id']]['percentage'];
                        }
                    }
                } else {
                    $Temp12[$rx['id']]['data'][] = 0;
                }
            }
        }
        $Temp13 = array();
        foreach ($Temp12 as $rows) {
            $Temp13[] = $rows;
        }
        $rest_graph = array();
        foreach ($Temp13 as $value) {
            $t['label'] = $value['label'];
            $t['backgroundColor'] = $value['backgroundColor'];
            $t['percentage'] = round(array_sum($value['data']) / $total_provider, 2);
            $rest_graph[] = $t;
        }
        $return['antibiotic'] = $optionsGraph;
        $return['datasheet'] = $Temp13;
        $return['rest_graph'] = $rest_graph;
        $return['care_name'] = (!empty($careUnit) && !empty($list)) ? "All" : "All";
       $color=array();
        $data = array();
        $label = array();
        foreach ($allData as $value) {
            $label[] = $value['md_name'];
            $color[] = '#' . str_pad(dechex(mt_rand(0, 0xFFFFFF)), 6, '0', STR_PAD_LEFT);
            $sum = 0;
            foreach($value['all'] as $row){
                $sum += $row['percentage'];
            }
            $data[] = $sum;
        }

        $return['labels'] = $label;
        $return['backgroundColor'] = $color;
        $return['data'] = $data;

        echo json_encode($return);
    }


    public function get_antibiotic_by_care_unit_provider_average() {
        $return = array();
        $return['status'] = 200;
        $antibiotic = array();
       // $return['careUnit'] = $careUnit = $this->input->post('careUnit');
        $return['provider'] = $provider = $this->input->post('provider_doctor');
        $rx = $this->input->post('rx');
        $steward = $this->input->post('steward');
        $symptom_onset = $this->input->post('symptom_onset');
        $criteria_met = $this->input->post('criteria_met');
        $md_stayward_response = $this->input->post('md_stayward_response');
        $culture_source = $this->input->post('culture_source');
        $organism = $this->input->post('organism');
        $precautions = $this->input->post('precautions');
        
        $date_of_start_abx = $this->input->post('date_of_start_abx');
        $date_of_start_abx1 = $this->input->post('date_of_start_abx1');
        $date_of_start_abx2 = $this->input->post('date_of_start_abx2');
        $date_of_start_abx3 = $this->input->post('date_of_start_abx3');
        $date1 = (!empty($this->input->post('date1'))) ? date('Y-m-d',strtotime($this->input->post('date1'))) : '';
        $date2 = (!empty($this->input->post('date2'))) ? date('Y-m-d',strtotime($this->input->post('date2'))) : '';     
        //$careUnit = 8;
        $option = array(
            'table' => 'patient P',
            'select' => 'P.id,P.patient_id,P.doctor_id,U.name',
            'join' => array(array('doctors U', 'U.id=P.doctor_id', 'inner')),
            'group_by' => array('P.doctor_id'),
            'order' => array('U.name'=> 'ASC')
        );
        if(!empty($date_of_start_abx) && !empty($date_of_start_abx1)){
            $option['where']['month(P.date_of_start_abx)'] = $date_of_start_abx;
            $option['where']['year(P.date_of_start_abx)'] = $date_of_start_abx1;
        }

        if(!empty($date_of_start_abx2) && !empty($date_of_start_abx3)){
            $option['where']['QUARTER(P.date_of_start_abx)'] = $date_of_start_abx2;
            $option['where']['year(P.date_of_start_abx)'] = $date_of_start_abx3;
        }else if($date_of_start_abx2 == '2021'){
            $option['where']['Year(P.date_of_start_abx)'] = $date_of_start_abx2;
        }else if($date_of_start_abx3 == '2021'){
            $option['where']['Year(P.date_of_start_abx)'] = $date_of_start_abx3;
        }else if($date_of_start_abx2 == '2022'){
            $option['where']['Year(P.date_of_start_abx)'] = $date_of_start_abx2;
        }else if($date_of_start_abx3 == '2022'){
            $option['where']['Year(P.date_of_start_abx)'] = $date_of_start_abx3;
        }else if($date_of_start_abx2 == '2023'){
            $option['where']['Year(P.date_of_start_abx)'] = $date_of_start_abx2;
        }else if($date_of_start_abx3 == '2023'){
            $option['where']['Year(P.date_of_start_abx)'] = $date_of_start_abx3;
        }

        if (!empty($date1) && !empty($date2)) {
          $option['where']['date(P.date_of_start_abx) >='] = $date1;
          $option['where']['date(P.date_of_start_abx) <='] = $date2;
        }
        if(!empty($symptom_onset)){
            $option['where']['P.symptom_onset'] = $symptom_onset;
        }
       /*  if (!empty($careUnit)) {
            $option['where']['P.care_unit_id'] = $careUnit;
        } */
       /*  if (!empty($provider)) {
            $option['where']['P.doctor_id'] = $provider;
        }
        if (!empty($steward)) {
            $option['where']['P.md_steward_id'] = $steward;
        }
        if(!empty($symptom_onset)){
            $option['where']['P.symptom_onset'] = $symptom_onset;
        }
        if(!empty($md_stayward_response)){
            $option['where']['P.md_stayward_response'] = $md_stayward_response;
        }
        if(!empty($culture_source)){
            $option['where']['P.culture_source'] = $culture_source;
        }
        if(!empty($organism)){
            $option['where']['P.organism'] = $organism;
        }
        if(!empty($precautions)){
            $option['where']['P.precautions'] = $precautions;
        }
        if(!empty($criteria_met=='Yes')){
            $option['where']['P.criteria_met'] = $criteria_met;
        }
        else if(!empty($criteria_met=='No')){
            $option['where']['P.criteria_met'] = $criteria_met;
        }
        else if(!empty($criteria_met=='N/A')){
            $option['where']['P.infection_surveillance_checklist'] = $criteria_met;
        } */



        

      

        $sql = array(
            'table' => 'patient P',
            'select' => 'SUM(initial_dot) totalDays ',
            'join' => array(array('care_unit CI', 'CI.id=P.care_unit_id', 'inner'),
                array('patient_consult PC', 'PC.patient_id=P.id', 'inner')
               )
           
        );
        if(!empty($date_of_start_abx) && !empty($date_of_start_abx1)){
            $sql['where']['month(P.date_of_start_abx)'] = $date_of_start_abx;
            $sql['where']['year(P.date_of_start_abx)'] = $date_of_start_abx1;
        }

        if(!empty($date_of_start_abx2) && !empty($date_of_start_abx3)){
            $sql['where']['QUARTER(P.date_of_start_abx)'] = $date_of_start_abx2;
            $sql['where']['year(P.date_of_start_abx)'] = $date_of_start_abx3;
        }else if($date_of_start_abx2 == '2021'){
            $sql['where']['Year(P.date_of_start_abx)'] = $date_of_start_abx2;
        }else if($date_of_start_abx3 == '2021'){
            $sql['where']['Year(P.date_of_start_abx)'] = $date_of_start_abx3;
        }else if($date_of_start_abx2 == '2022'){
            $sql['where']['Year(P.date_of_start_abx)'] = $date_of_start_abx2;
        }else if($date_of_start_abx3 == '2022'){
            $sql['where']['Year(P.date_of_start_abx)'] = $date_of_start_abx3;
        }else if($date_of_start_abx2 == '2023'){
            $sql['where']['Year(P.date_of_start_abx)'] = $date_of_start_abx2;
        }else if($date_of_start_abx3 == '2023'){
            $sql['where']['Year(P.date_of_start_abx)'] = $date_of_start_abx3;
        }

        if (!empty($date1) && !empty($date2)) {
          $sql['where']['date(P.date_of_start_abx) >='] = $date1;
          $sql['where']['date(P.date_of_start_abx) <='] = $date2;
        }
        if(!empty($symptom_onset)){
            $sql['where']['P.symptom_onset'] = $symptom_onset;
        }

        $steward = $this->common_model->customGet($option);
        $total_provider = count($steward);
        
        $list = $this->common_model->customGet($sql);

        //  if (!empty($list)) {
            $total_antibiotic_days = array_sum(array_column($list, 'totalDays'));
        //    print_r($total_provider);die;
           $total_provider = count($steward);
           
           // foreach ($list as $rows) {
                $percentage = round(($total_antibiotic_days)/($total_provider ?: 1),2);
                
               /*  $Temps['avg_days'] = $avg_days[] = $percentage;
                $Temps['total_days'] = $total_days[] = $total_antibiotic_days;
               
                $Temps['total_providers'] =  $total_providers[] =  $total_provider;
                */
                
               
               // $color[] = '#' . str_pad(dechex(mt_rand(0, 0xFFFFFF)), 6, '0', STR_PAD_LEFT);
           //    }
            // $return['antibiotic'] = $antibiotic;
          
           // $return['color'] = $color;
            $Temps['avg_dayss'] = $avg_days[] = $percentage;
           
          /*   $Temps['total_dayss'] = $total_days =  $total_antibiotic_days;
           
            $Temps['total_providerss'] = $total_providers =  $total_provider; */
            //print_r($total_providers);die;
            $Temps['name'] = "Average Days";

            $optionsGraphs[] = $Temps;


 




        $steward = $this->common_model->customGet($option);
        $total_provider = count($steward);
        $optionsGraph = array();
        $mediRx = array();
        if (!empty($steward)) {
            foreach ($steward as $row) {
                $Temp['md_name'] = $row->name;
                $Temp['doctor_id'] = $row->doctor_id;
                $option = array(
                    'table' => 'patient P',
                    'select' => 'DISTINCT(PC.initial_rx) as rx_id,count(PC.initial_rx) as total,SUM(PC.initial_dot) as totalDays,P.care_unit_id,IRX.name,
                    CI.name as care_name,IRX.description',
                    'join' => array(array('care_unit CI', 'CI.id=P.care_unit_id', 'inner'),
                        array('patient_consult PC', 'PC.patient_id=P.id', 'inner'),
                        array('initial_rx IRX', 'IRX.id=PC.initial_rx', 'inner')),
                    'group_by' => array('PC.initial_rx'),
                    'order' => array('PC.initial_rx' => "ASC")
                );

               /*  if (!empty($careUnit)) {
                    $option['where']['P.care_unit_id'] = $careUnit;
                } */

               /*  if (!empty($rx)) {
                    $option['where']['PC.initial_rx'] = $rx;
                } */

                if(!empty($date_of_start_abx) && !empty($date_of_start_abx1)){
                    $option['where']['month(P.date_of_start_abx)'] = $date_of_start_abx;
                    $option['where']['year(P.date_of_start_abx)'] = $date_of_start_abx1;
                }

                if(!empty($date_of_start_abx2) && !empty($date_of_start_abx3)){
                    $option['where']['QUARTER(P.date_of_start_abx)'] = $date_of_start_abx2;
                    $option['where']['year(P.date_of_start_abx)'] = $date_of_start_abx3;
                }else if($date_of_start_abx2 == '2021'){
                    $option['where']['Year(P.date_of_start_abx)'] = $date_of_start_abx2;
                }else if($date_of_start_abx3 == '2021'){
                    $option['where']['Year(P.date_of_start_abx)'] = $date_of_start_abx3;
                }else if($date_of_start_abx2 == '2022'){
                    $option['where']['Year(P.date_of_start_abx)'] = $date_of_start_abx2;
                }else if($date_of_start_abx3 == '2022'){
                    $option['where']['Year(P.date_of_start_abx)'] = $date_of_start_abx3;
                }else if($date_of_start_abx2 == '2023'){
                    $option['where']['Year(P.date_of_start_abx)'] = $date_of_start_abx2;
                }else if($date_of_start_abx3 == '2023'){
                    $option['where']['Year(P.date_of_start_abx)'] = $date_of_start_abx3;
                }


                if (!empty($date1) && !empty($date2)) {
                    $option['where']['date(P.date_of_start_abx) >='] = $date1;
                    $option['where']['date(P.date_of_start_abx) <='] = $date2;
                  }

                if(!empty($symptom_onset)){
                    $option['where']['P.symptom_onset'] = $symptom_onset;
                }
              /*   if(!empty($symptom_onset)){
                    $option['where']['P.symptom_onset'] = $symptom_onset;
                }
                if(!empty($md_stayward_response)){
                    $option['where']['P.md_stayward_response'] = $md_stayward_response;
                }
                if(!empty($culture_source)){
                    $option['where']['P.culture_source'] = $culture_source;
                }
                if(!empty($organism)){
                    $option['where']['P.organism'] = $organism;
                }
                if(!empty($precautions)){
                    $option['where']['P.precautions'] = $precautions;
                }
                if(!empty($criteria_met=='Yes')){
                    $option['where']['P.criteria_met'] = $criteria_met;
                }
                else if(!empty($criteria_met=='No')){
                    $option['where']['P.criteria_met'] = $criteria_met;
                }
                else if(!empty($criteria_met=='N/A')){
                    $option['where']['P.infection_surveillance_checklist'] = $criteria_met;
                } */
                $option['where']['P.doctor_id'] = $row->doctor_id;
                $list = $this->common_model->customGet($option);
                //echo $this->db->last_query;
                $Temp['all'] = array();
                if (!empty($list)) {
                    $total_antibiotic_used = array_sum(array_column($list, 'total'));
                    $total_antibiotic = count($list);
                    $color = array();
                    $percentages = array();
                    $labels = array();
                    $multi_labels = array();
                    foreach ($list as $rows) {
                        $percentage = round(($rows->totalDays / $total_antibiotic_used) * 100);
                        $Temp1['percentage'] = $rows->totalDays;
                        $Temp1['total_used'] = $rows->totalDays;
                        $Temp1['name'] = $rows->name;
                        $Temp1['rx_id'] = $rows->rx_id;
                        $Temp1['color'] = $rows->description;
                        $color[] = $rows->description;
                        $percentages[] = $percentage;
                        $labels[$rows->rx_id] = $rows->name;
                        $mediRx[$rows->rx_id]['id'] = $rows->rx_id;
                        $mediRx[$rows->rx_id]['name'] = $rows->name;
                        $mediRx[$rows->rx_id]['color'] = $rows->description;
                        $multi_labels[$rows->rx_id] = array('rx_id' => $rows->rx_id, 'Name' => $rows->name, 'percentage' => $rows->totalDays, 'color' => $rows->description, 'md_id' => $row->doctor_id);
                    }
                    $Temp['all'] = $multi_labels;
                }
                $optionsGraph[] = $Temp;
            }
        }
        $allData = $optionsGraph;
        //dump($optionsGraph);
        sort($mediRx);
        $Temp12 = array();
        foreach ($optionsGraph as $graph) {
            $all = $graph['all'];
            $all_only_id = array_column($all, "rx_id");
            $md_id = $graph['doctor_id'];
            foreach ($mediRx as $rx) {
                $Temp12[$rx['id']]['label'] = $rx['name'];
                $Temp12[$rx['id']]['backgroundColor'] = $rx['color'];
                if (in_array($rx['id'], $all_only_id)) {
                    if (isset($all[$rx['id']]) && $all[$rx['id']]['md_id'] == $md_id) {
                        if (!empty($all[$rx['id']])) {
                            $Temp12[$rx['id']]['data'][] = $all[$rx['id']]['percentage'];
                        }
                    }
                } else {
                    $Temp12[$rx['id']]['data'][] = 0;
                }
            }
        }
        $Temp13 = array();
        foreach ($Temp12 as $rows) {
            $Temp13[] = $rows;
        }
        $rest_graph = array();
        foreach ($Temp13 as $value) {
            $t['label'] = $value['label'];
            $t['backgroundColor'] = $value['backgroundColor'];
            $t['percentage'] = round(array_sum($value['data']) / $total_provider, 2);
            $rest_graph[] = $t;
        }
        $return['antibiotic']=$optionsGraphs;
        //print_r($return['antibiotic']);die;
       // $return['antibiotic'] = $optionsGraph;
       /*  $return['datasheet'] = $Temp13;
        $return['rest_graph'] = $rest_graph; */
       // $return['care_name'] = (!empty($careUnit) && !empty($list)) ? "All" : "All";
       $color=array();
        $data = array();
        $label = array();
        foreach ($allData as $value) {
            $label[] = $value['md_name'];
            $color[] = '#' . str_pad(dechex(mt_rand(0, 0xFFFFFF)), 6, '0', STR_PAD_LEFT);
            $sum = 0;
            foreach($value['all'] as $row){
                $sum += $row['percentage'];
            }
            $data[] = $sum;
        }

        $return['labels'] = $label;
        $return['backgroundColor'] = $color;
        $return['data'] = $data;

        echo json_encode($return);
    }

  
    /** new **/
    public function get_antibiotic_by_care_unit_provider_pie() {
         $return = array();
        $return['status'] = 200;
        $antibiotic = array();
        $return['careUnit'] = $careUnit = $this->input->post('careUnit');
        $return['provider'] = $provider = $this->input->post('provider_doctor');
        $rx = $this->input->post('rx');
        $steward = $this->input->post('steward');
        $symptom_onset = $this->input->post('symptom_onset');
        $md_stayward_response = $this->input->post('md_stayward_response');
        $culture_source = $this->input->post('culture_source');
        $organism = $this->input->post('organism');
        $precautions = $this->input->post('precautions');
        $criteria_met = $this->input->post('criteria_met');
        
        $date_of_start_abx2 = $this->input->post('date_of_start_abx2');
        $date_of_start_abx3 = $this->input->post('date_of_start_abx3');
        $date1 = (!empty($this->input->post('date1'))) ? date('Y-m-d',strtotime($this->input->post('date1'))) : '';
        $date2 = (!empty($this->input->post('date2'))) ? date('Y-m-d',strtotime($this->input->post('date2'))) : '';     
        //$careUnit = 8;
        $option = array(
            'table' => 'patient P',
            'select' => 'P.id,P.patient_id,P.doctor_id,U.name',
            'join' => array(array('doctors U', 'U.id=P.doctor_id', 'inner')),
            'group_by' => array('P.doctor_id'),
            'order' => array('U.name' => "ASC")
        );
        if (!empty($date1) && !empty($date2)) {
          $option['where']['date(P.date_of_start_abx) >='] = $date1;
          $option['where']['date(P.date_of_start_abx) <='] = $date2;
        }

        if(!empty($date_of_start_abx2) && !empty($date_of_start_abx3)){
            $option['where']['QUARTER(P.date_of_start_abx)'] = $date_of_start_abx2;
            $option['where']['year(P.date_of_start_abx)'] = $date_of_start_abx3;
        }else if($date_of_start_abx2 == '2021'){
            $option['where']['Year(P.date_of_start_abx)'] = $date_of_start_abx2;
        }else if($date_of_start_abx3 == '2021'){
            $option['where']['Year(P.date_of_start_abx)'] = $date_of_start_abx3;
        }else if($date_of_start_abx2 == '2022'){
            $option['where']['Year(P.date_of_start_abx)'] = $date_of_start_abx2;
        }else if($date_of_start_abx3 == '2022'){
            $option['where']['Year(P.date_of_start_abx)'] = $date_of_start_abx3;
        }else if($date_of_start_abx2 == '2023'){
            $option['where']['Year(P.date_of_start_abx)'] = $date_of_start_abx2;
        }else if($date_of_start_abx3 == '2023'){
            $option['where']['Year(P.date_of_start_abx)'] = $date_of_start_abx3;
        }


        if (!empty($provider)) {
            $option['where']['P.doctor_id'] = $provider;
        }
        if (!empty($careUnit)) {
            $option['where']['P.care_unit_id'] = $careUnit;
        }
        if (!empty($steward)) {
            $option['where']['P.md_steward_id'] = $steward;
        }
        if(!empty($symptom_onset)){
            $option['where']['P.symptom_onset'] = $symptom_onset;
        }
        if(!empty($md_stayward_response)){
            $option['where']['P.md_stayward_response'] = $md_stayward_response;
        }
        if(!empty($culture_source)){
            $option['where']['P.culture_source'] = $culture_source;
        }
        if(!empty($organism)){
            $option['where']['P.organism'] = $organism;
        }
        if(!empty($precautions)){
            $option['where']['P.precautions'] = $precautions;
        }
        if(!empty($criteria_met=='Yes')){
            $option['where']['P.criteria_met'] = $criteria_met;
        }
        else if(!empty($criteria_met=='No')){
            $option['where']['P.criteria_met'] = $criteria_met;
        }
        else if(!empty($criteria_met=='N/A')){
            $option['where']['P.infection_surveillance_checklist'] = $criteria_met;
        }
        $steward = $this->common_model->customGet($option);
        $total_provider = count($steward);
        $optionsGraph = array();
        $mediRx = array();
        if (!empty($steward)) {
            foreach ($steward as $row) {
                $Temp['md_name'] = $row->name;
                $Temp['doctor_id'] = $row->doctor_id;
                $option = array(
                    'table' => 'patient P',
                    'select' => 'DISTINCT(PC.initial_rx) as rx_id,count(PC.initial_rx) as total,SUM(PC.initial_dot) as totalDays,P.care_unit_id,IRX.name,
                    CI.name as care_name,IRX.description',
                    'join' => array(array('care_unit CI', 'CI.id=P.care_unit_id', 'inner'),
                        array('patient_consult PC', 'PC.patient_id=P.id', 'inner'),
                        array('initial_rx IRX', 'IRX.id=PC.initial_rx', 'inner')),
                    'group_by' => array('PC.initial_rx'),
                    'order' => array('PC.initial_rx' => "ASC")
                );

                if (!empty($rx)) {
                    $option['where']['PC.initial_rx'] = $rx;
                }
                if (!empty($date1) && !empty($date2)) {
                    $option['where']['date(P.date_of_start_abx) >='] = $date1;
                    $option['where']['date(P.date_of_start_abx) <='] = $date2;
                }

                if(!empty($date_of_start_abx2) && !empty($date_of_start_abx3)){
                    $option['where']['QUARTER(P.date_of_start_abx)'] = $date_of_start_abx2;
                    $option['where']['year(P.date_of_start_abx)'] = $date_of_start_abx3;
                }else if($date_of_start_abx2 == '2021'){
                    $option['where']['Year(P.date_of_start_abx)'] = $date_of_start_abx2;
                }else if($date_of_start_abx3 == '2021'){
                    $option['where']['Year(P.date_of_start_abx)'] = $date_of_start_abx3;
                }else if($date_of_start_abx2 == '2022'){
                    $option['where']['Year(P.date_of_start_abx)'] = $date_of_start_abx2;
                }else if($date_of_start_abx3 == '2022'){
                    $option['where']['Year(P.date_of_start_abx)'] = $date_of_start_abx3;
                }else if($date_of_start_abx2 == '2023'){
                    $option['where']['Year(P.date_of_start_abx)'] = $date_of_start_abx2;
                }else if($date_of_start_abx3 == '2023'){
                    $option['where']['Year(P.date_of_start_abx)'] = $date_of_start_abx3;
                }

                if(!empty($symptom_onset)){
                    $option['where']['P.symptom_onset'] = $symptom_onset;
                }
                if(!empty($md_stayward_response)){
                    $option['where']['P.md_stayward_response'] = $md_stayward_response;
                }
                if(!empty($culture_source)){
                    $option['where']['P.culture_source'] = $culture_source;
                }
                if(!empty($organism)){
                    $option['where']['P.organism'] = $organism;
                }
                if(!empty($precautions)){
                    $option['where']['P.precautions'] = $precautions;
                }
                if(!empty($criteria_met=='Yes')){
                    $option['where']['P.criteria_met'] = $criteria_met;
                }
                else if(!empty($criteria_met=='No')){
                    $option['where']['P.criteria_met'] = $criteria_met;
                }
                else if(!empty($criteria_met=='N/A')){
                    $option['where']['P.infection_surveillance_checklist'] = $criteria_met;
                }
                $option['where']['P.doctor_id'] = $row->doctor_id;
                                if (!empty($careUnit)) {
                    $option['where']['P.care_unit_id'] = $careUnit;
                }
                $list = $this->common_model->customGet($option);
                //echo $this->db->last_query;
                $Temp['all'] = array();
                if (!empty($list)) {
                    $total_antibiotic_used = array_sum(array_column($list, 'total'));
                    $total_antibiotic = count($list);
                    $color = array();
                    $percentages = array();
                    $labels = array();
                    $multi_labels = array();
                    foreach ($list as $rows) {
                        $percentage = round(($rows->totalDays / $total_antibiotic_used) * 100);
                        $Temp1['percentage'] = $rows->totalDays;
                        $Temp1['total_used'] = $rows->totalDays;
                        $Temp1['name'] = $rows->name;
                        $Temp1['rx_id'] = $rows->rx_id;
                        $Temp1['color'] = $rows->description;
                        $color[] = $rows->description;
                        $percentages[] = $percentage;
                        $labels[$rows->rx_id] = $rows->name;
                        $mediRx[$rows->rx_id]['id'] = $rows->rx_id;
                        $mediRx[$rows->rx_id]['name'] = $rows->name;
                        $mediRx[$rows->rx_id]['color'] = $rows->description;
                        $multi_labels[$rows->rx_id] = array('rx_id' => $rows->rx_id, 'Name' => $rows->name, 'percentage' => $rows->totalDays, 'color' => $rows->description, 'md_id' => $row->doctor_id);
                    }
                    $Temp['all'] = $multi_labels;
                }
                $optionsGraph[] = $Temp;
            }
        }
        $allData = $optionsGraph;
        sort($mediRx);
        $Temp12 = array();
        foreach ($optionsGraph as $graph) {
            $all = $graph['all'];
            $all_only_id = array_column($all, "rx_id");
            $md_id = $graph['doctor_id'];
            foreach ($mediRx as $rx) {
                $Temp12[$rx['id']]['label'] = $rx['name'];
                $Temp12[$rx['id']]['backgroundColor'] = $rx['color'];
                if (in_array($rx['id'], $all_only_id)) {
                    if (isset($all[$rx['id']]) && $all[$rx['id']]['md_id'] == $md_id) {
                        if (!empty($all[$rx['id']])) {
                            $Temp12[$rx['id']]['data'][] = $all[$rx['id']]['percentage'];
                        }
                    }
                } else {
                    $Temp12[$rx['id']]['data'][] = 0;
                }
            }
        }
        $Temp13 = array();
        foreach ($Temp12 as $rows) {
            $Temp13[] = $rows;
        }
        $rest_graph = array();
        foreach ($Temp13 as $value) {
            $t['label'] = $value['label'];
            $t['backgroundColor'] = $value['backgroundColor'];
            $t['percentage'] = round(array_sum($value['data']) / $total_provider, 2);
            $rest_graph[] = $t;
        }
        $return['antibiotic'] = $optionsGraph;
        $return['datasheet'] = $Temp13;
        $return['rest_graph'] = $rest_graph;
        $return['care_name'] = (!empty($careUnit) && !empty($list)) ? "All" : "All";

        $color=array();
        $data = array();
        $label = array();

        foreach ($allData as $value) {
            $label[] = $value['md_name'];
            $color[] = '#' . str_pad(dechex(mt_rand(0, 0xFFFFFF)), 6, '0', STR_PAD_LEFT);
            $sum = 0;
            foreach($value['all'] as $row){
                $sum += $row['percentage'];
            }
            $data[] = $sum;
        }

        $return['labels'] = $label;
        $return['backgroundColor'] = $color;
        $return['data'] = $data;



        echo json_encode($return); 


        /* $return = array();
        $return['status'] = 200;
        $antibiotic = array();
        $return['careUnit'] = $careUnit = $this->input->post('careUnit');
        $return['provider'] = $provider = $this->input->post('provider_doctor');
        $rx = $this->input->post('rx');
        $steward_id = $this->input->post('steward');
        $symptom_onset = $this->input->post('symptom_onset');

        $date1 = (!empty($this->input->post('date1'))) ? date('Y-m-d',strtotime($this->input->post('date1'))) : '';
        $date2 = (!empty($this->input->post('date2'))) ? date('Y-m-d',strtotime($this->input->post('date2'))) : '';
        $option = array(
            'table' => 'patient P',
            'select' => 'P.id,P.patient_id,P.doctor_id,U.name',
            'join' => array(array('doctors U', 'U.id=P.doctor_id', 'inner')),
            'group_by' => array('P.doctor_id')
        );
        if (!empty($date1) && !empty($date2)) {
          $option['where']['date(P.created_date) >='] = $date1;
          $option['where']['date(P.created_date) <='] = $date2;
        }
        if (!empty($careUnit)) {
            $option['where']['P.care_unit_id'] = $careUnit;
        }
        if (!empty($provider)) {
            $option['where']['P.doctor_id'] = $provider;
        }
        if (!empty($steward_id)) {
            $option['where']['P.md_steward_id'] = $steward_id;
        }
        if(!empty($symptom_onset)){
            $option['where']['P.symptom_onset'] = $symptom_onset;
        }
        $steward = $this->common_model->customGet($option);
        $optionsGraph = array();
        if (!empty($steward)) {
            foreach ($steward as $row) {
                $Temp['md_name'] = $row->name;
                $Temp['doctor_id'] = $row->doctor_id;
                $option = array(
                    'table' => 'patient P',
                    'select' => 'sum(PC.initial_dot) as total_initial_dot,sum(PC.new_initial_dot) as total_new_initial_dot,'
                    . 'CI.name as care_name',
                    'join' => array(array('care_unit CI', 'CI.id=P.care_unit_id', 'inner'),
                        array('patient_consult PC', 'PC.patient_id=P.id', 'inner')),
                    'single' => true
                );
                $option['where']['P.doctor_id'] = $row->doctor_id;
                $list = $this->common_model->customGet($option);
                if (!empty($list)) {
                    $Temp1['total_initial_dot'] = $list->total_initial_dot;
                    $Temp1['total_new_initial_dot'] = $list->total_new_initial_dot;
                    $Temp['dot'] = $Temp1;
                } else {
                    $Temp['dot'] = array();
                }
                $optionsGraph[] = $Temp;
            }
        }
        $Temp123 = array();
        foreach ($optionsGraph as $graph) {
            $Temp11['label'] = "Provider Days";
            $Temp11['backgroundColor'] = "#B7950B";
            $Temp11['data'][] = (!empty($graph['dot']['total_initial_dot'])) ? $graph['dot']['total_initial_dot'] : 0;

            $Temp12['label'] = "Steward Days";
            $Temp12['backgroundColor'] = "#8E44AD";
            $Temp12['data'][] = (!empty($graph['dot']['total_new_initial_dot'])) ? $graph['dot']['total_new_initial_dot'] : 0;
        }
        $Temp123 = array($Temp11, $Temp12);
        $return['antibiotic'] = $optionsGraph;
        $return['datasheet'] = $Temp123;
        echo json_encode($return); */
    
    }
    /** new **/
    public function get_dx_by_actual_dots_new() {
        $return = array();
        $return['status'] = 200;
        $antibiotic = array();
        $return['careUnit'] = $careUnit = $this->input->post('careUnit');
        $return['provider'] = $provider = $this->input->post('provider_doctor');
        $rx = $this->input->post('rx');
        $steward_id = $this->input->post('steward');
        $symptom_onset = $this->input->post('symptom_onset');
        $criteria_met = $this->input->post('criteria_met');
        $md_stayward_response = $this->input->post('md_stayward_response');
        $culture_source = $this->input->post('culture_source');
        $organism = $this->input->post('organism');
        $precautions = $this->input->post('precautions');
        $date_of_start_abx = $this->input->post('date_of_start_abx');
        $date_of_start_abx1 = $this->input->post('date_of_start_abx1');
        $date_of_start_abx2 = $this->input->post('date_of_start_abx2');
        $date_of_start_abx3 = $this->input->post('date_of_start_abx3');
        $date1 = (!empty($this->input->post('date1'))) ? date('Y-m-d',strtotime($this->input->post('date1'))) : '';
        $date2 = (!empty($this->input->post('date2'))) ? date('Y-m-d',strtotime($this->input->post('date2'))) : '';
        $option = array(
            'table' => 'patient P',
            'select' => 'SUM(initial_dot) totalDays ,P.care_unit_id,IRX.name,CI.name as care_name',
            'join' => array(array('care_unit CI', 'CI.id=P.care_unit_id', 'inner'),
                array('patient_consult PC', 'PC.patient_id=P.id', 'inner'),
                array('initial_dx IRX', 'IRX.id=PC.initial_dx', 'inner')),
            'group_by' => array('IRX.name'),
            'order' => array('IRX.name' => 'ASC')
        );
        if (!empty($date1) && !empty($date2)) {
          $option['where']['date(P.date_of_start_abx) >='] = $date1;
          $option['where']['date(P.date_of_start_abx) <='] = $date2;
        }
        if(!empty($date_of_start_abx) && !empty($date_of_start_abx1)){
            $option['where']['month(P.date_of_start_abx)'] = $date_of_start_abx;
            $option['where']['year(P.date_of_start_abx)'] = $date_of_start_abx1;
        }
        if(!empty($date_of_start_abx2) && !empty($date_of_start_abx3)){
            $option['where']['QUARTER(P.date_of_start_abx)'] = $date_of_start_abx2;
            $option['where']['year(P.date_of_start_abx)'] = $date_of_start_abx3;
        }else if($date_of_start_abx2 == '2021'){
            $option['where']['Year(P.date_of_start_abx)'] = $date_of_start_abx2;
        }else if($date_of_start_abx3 == '2021'){
            $option['where']['Year(P.date_of_start_abx)'] = $date_of_start_abx3;
        }else if($date_of_start_abx2 == '2022'){
            $option['where']['Year(P.date_of_start_abx)'] = $date_of_start_abx2;
        }else if($date_of_start_abx3 == '2022'){
            $option['where']['Year(P.date_of_start_abx)'] = $date_of_start_abx3;
        }else if($date_of_start_abx2 == '2023'){
            $option['where']['Year(P.date_of_start_abx)'] = $date_of_start_abx2;
        }else if($date_of_start_abx3 == '2023'){
            $option['where']['Year(P.date_of_start_abx)'] = $date_of_start_abx3;
        }


        if (!empty($careUnit)) {
            $option['where']['P.care_unit_id'] = $careUnit;
        }
       /*  if (!empty($rx)) {
            $option['where']['IRX.id'] = $rx;
        } */
        if (!empty($rx)) {
            $option['where']['PC.initial_rx'] = $rx;
        }
        if (!empty($provider)) {
            $option['where']['P.doctor_id'] = $provider;
        }
        if (!empty($steward_id)) {
            $option['where']['P.md_steward_id'] = $steward_id;
        }
        if(!empty($symptom_onset)){
            $option['where']['P.symptom_onset'] = $symptom_onset;
        }
        if(!empty($md_stayward_response)){
            $option['where']['P.md_stayward_response'] = $md_stayward_response;
        }
        if(!empty($culture_source)){
            $option['where']['P.culture_source'] = $culture_source;
        }
        if(!empty($organism)){
            $option['where']['P.organism'] = $organism;
        }
        if(!empty($precautions)){
            $option['where']['P.precautions'] = $precautions;
        }
        if(!empty($criteria_met=='Yes')){
            $option['where']['P.criteria_met'] = $criteria_met;
        }
        else if(!empty($criteria_met=='No')){
            $option['where']['P.criteria_met'] = $criteria_met;
        }
        else if(!empty($criteria_met=='N/A')){
            $option['where']['P.infection_surveillance_checklist'] = $criteria_met;
        }
        $list = $this->common_model->customGet($option);
        $color = array();
        $label = array();
        $data = array();
        if (!empty($list)) {
            $total_antibiotic_days = array_sum(array_column($list, 'totalDays'));
            $total_antibiotic = count($list);
            foreach ($list as $rows) {
                $percentage = round(($rows->totalDays / $total_antibiotic_days) * 100);
                //$Temp['percentage'] = $percentage;
                $Temp['percentage'] = $data[] = $rows->totalDays;
                $Temp['name'] =  $label[] = $rows->name;
                $antibiotic[] = $Temp;
                $color[] = '#' . str_pad(dechex(mt_rand(0, 0xFFFFFF)), 6, '0', STR_PAD_LEFT);
            }
            $return['antibiotic'] = $antibiotic;
            $return['color'] = $color;
            $return['labels'] = $label;
            $return['data'] = $data;
            $return['care_name'] = (!empty($careUnit)) ? $list[0]->care_name : "All";




        } else {
            $return['status'] = 400;
        }
        echo json_encode($return);
    }


    public function get_avg_dx_by_actual_dots_new() {
        $return = array();
        $return['status'] = 200;
        $antibiotic = array();
        $return['careUnit'] = $careUnit = $this->input->post('careUnit');
        $return['provider'] = $provider = $this->input->post('provider_doctor');
        $rx = $this->input->post('rx');
        $steward_id = $this->input->post('steward');
        $symptom_onset = $this->input->post('symptom_onset');
        $criteria_met = $this->input->post('criteria_met');
        $md_stayward_response = $this->input->post('md_stayward_response');
        $culture_source = $this->input->post('culture_source');
        $organism = $this->input->post('organism');
        $precautions = $this->input->post('precautions');
        
        $date_of_start_abx2 = $this->input->post('date_of_start_abx2');
        $date_of_start_abx3 = $this->input->post('date_of_start_abx3');
        $date1 = (!empty($this->input->post('date1'))) ? date('Y-m-d',strtotime($this->input->post('date1'))) : '';
        $date2 = (!empty($this->input->post('date2'))) ? date('Y-m-d',strtotime($this->input->post('date2'))) : '';
        $option = array(
            'table' => 'patient P',
            'select' => 'SUM(initial_dot) totalDays ,P.care_unit_id,IRX.name,CI.name as care_name',
            'join' => array(array('care_unit CI', 'CI.id=P.care_unit_id', 'inner'),
                array('patient_consult PC', 'PC.patient_id=P.id', 'inner'),
                array('initial_dx IRX', 'IRX.id=PC.initial_dx', 'inner')),
            'group_by' => array('IRX.name'),
            'order' => array('IRX.name' => 'ASC')
        );
        if (!empty($date1) && !empty($date2)) {
          $option['where']['date(P.date_of_start_abx) >='] = $date1;
          $option['where']['date(P.date_of_start_abx) <='] = $date2;
        }

        if(!empty($date_of_start_abx2) && !empty($date_of_start_abx3)){
            $option['where']['QUARTER(P.date_of_start_abx)'] = $date_of_start_abx2;
            $option['where']['year(P.date_of_start_abx)'] = $date_of_start_abx3;
        }else if($date_of_start_abx2 == '2021'){
            $option['where']['Year(P.date_of_start_abx)'] = $date_of_start_abx2;
        }else if($date_of_start_abx3 == '2021'){
            $option['where']['Year(P.date_of_start_abx)'] = $date_of_start_abx3;
        }else if($date_of_start_abx2 == '2022'){
            $option['where']['Year(P.date_of_start_abx)'] = $date_of_start_abx2;
        }else if($date_of_start_abx3 == '2022'){
            $option['where']['Year(P.date_of_start_abx)'] = $date_of_start_abx3;
        }else if($date_of_start_abx2 == '2023'){
            $option['where']['Year(P.date_of_start_abx)'] = $date_of_start_abx2;
        }else if($date_of_start_abx3 == '2023'){
            $option['where']['Year(P.date_of_start_abx)'] = $date_of_start_abx3;
        }

        if(!empty($symptom_onset)){
            $option['where']['P.symptom_onset'] = $symptom_onset;
        }
       
        $list = $this->common_model->customGet($option);
        $color = array();
        $label = array();
        $data = array();
        if (!empty($list)) {
            $total_antibiotic_days = array_sum(array_column($list, 'totalDays'));
            $total_antibiotic = count($list);
           // print_r($total_antibiotic_days);die;

           $percentage = round(($total_antibiotic_days)/($total_antibiotic ?: 1),2);
           // print_r($total_antibiotic_days);die;
        $Temps['avg_daysss1'] = $avg_days[] = $percentage;
       
      
        $Temps['name'] = "Average Days";

        $optionsGraphs[] = $Temps;



            foreach ($list as $rows) {
                $percentage = round(($rows->totalDays / $total_antibiotic_days) * 100);
                //$Temp['percentage'] = $percentage;
                $Temp['percentage'] = $data[] = $rows->totalDays;
                $Temp['name'] =  $label[] = $rows->name;
                $antibiotic[] = $Temp;
                $color[] = '#' . str_pad(dechex(mt_rand(0, 0xFFFFFF)), 6, '0', STR_PAD_LEFT);
            }
            $return['antibiotic'] = $optionsGraphs;
            $return['color'] = $color;
            $return['labels'] = $label;
            $return['data'] = $data;
            $return['care_name'] = (!empty($careUnit)) ? $list[0]->care_name : "All";




        } else {
            $return['status'] = 400;
        }
        echo json_encode($return);
    }


    /** new **/
    public function total_abx_days() {
        $return = array();
        $return['status'] = 200;
        $antibiotic = array();
        $return['careUnit'] = $careUnit = $this->input->post('careUnit');
        $return['provider'] = $provider = $this->input->post('provider_doctor');
        $rx = $this->input->post('rx');
        $steward_id = $this->input->post('steward');
        $symptom_onset = $this->input->post('symptom_onset');
        $criteria_met = $this->input->post('criteria_met');
        $md_stayward_response = $this->input->post('md_stayward_response');
        $culture_source = $this->input->post('culture_source');
        $organism = $this->input->post('organism');
        $precautions = $this->input->post('precautions');
        $date_of_start_abx = $this->input->post('date_of_start_abx');
        $date_of_start_abx1 = $this->input->post('date_of_start_abx1');
        $date_of_start_abx2 = $this->input->post('date_of_start_abx2');
        $date_of_start_abx3 = $this->input->post('date_of_start_abx3');
        $date1 = (!empty($this->input->post('date1'))) ? date('Y-m-d',strtotime($this->input->post('date1'))) : '';
        $date2 = (!empty($this->input->post('date2'))) ? date('Y-m-d',strtotime($this->input->post('date2'))) : '';
        $option = array(
            'table' => 'patient P',
            'select' => 'SUM(initial_dot) totalDays ,SUM(new_initial_dot) ActualDot,P.care_unit_id,IRX.name,CI.name as care_name',
            'join' => array(array('care_unit CI', 'CI.id=P.care_unit_id', 'inner'),
                array('patient_consult PC', 'PC.patient_id=P.id', 'inner'),
                array('initial_dx IRX', 'IRX.id=PC.initial_dx', 'inner'),
                array('initial_rx RX', 'RX.id=PC.initial_rx', 'inner')),
            'group_by' => array('IRX.name')
        );

        if(!empty($date_of_start_abx) && !empty($date_of_start_abx1)){
            $option['where']['month(P.date_of_start_abx)'] = $date_of_start_abx;
            $option['where']['year(P.date_of_start_abx)'] = $date_of_start_abx1;
        }

        if(!empty($date_of_start_abx2) && !empty($date_of_start_abx3)){
            $option['where']['QUARTER(P.date_of_start_abx)'] = $date_of_start_abx2;
            $option['where']['year(P.date_of_start_abx)'] = $date_of_start_abx3;
        }else if($date_of_start_abx2 == '2021'){
            $option['where']['Year(P.date_of_start_abx)'] = $date_of_start_abx2;
        }else if($date_of_start_abx3 == '2021'){
            $option['where']['Year(P.date_of_start_abx)'] = $date_of_start_abx3;
        }else if($date_of_start_abx2 == '2022'){
            $option['where']['Year(P.date_of_start_abx)'] = $date_of_start_abx2;
        }else if($date_of_start_abx3 == '2022'){
            $option['where']['Year(P.date_of_start_abx)'] = $date_of_start_abx3;
        }else if($date_of_start_abx2 == '2023'){
            $option['where']['Year(P.date_of_start_abx)'] = $date_of_start_abx2;
        }else if($date_of_start_abx3 == '2023'){
            $option['where']['Year(P.date_of_start_abx)'] = $date_of_start_abx3;
        }

        if (!empty($date1) && !empty($date2)) {
            $option['where']['date(P.date_of_start_abx) >='] = $date1;
            $option['where']['date(P.date_of_start_abx) <='] = $date2;
        }
        if (!empty($careUnit)) {
            $option['where']['P.care_unit_id'] = $careUnit;
        }
        if (!empty($rx)) {
            $option['where']['PC.initial_rx'] = $rx;
        }
        if (!empty($provider)) {
            $option['where']['P.doctor_id'] = $provider;
        }
        if (!empty($steward_id)) {
            $option['where']['P.md_steward_id'] = $steward_id;
        }
        if(!empty($symptom_onset)){
            $option['where']['P.symptom_onset'] = $symptom_onset;
        }
        if(!empty($md_stayward_response)){
            $option['where']['P.md_stayward_response'] = $md_stayward_response;
        }
        if(!empty($culture_source)){
            $option['where']['P.culture_source'] = $culture_source;
        }
        if(!empty($organism)){
            $option['where']['P.organism'] = $organism;
        }
        if(!empty($precautions)){
            $option['where']['P.precautions'] = $precautions;
        }
        if(!empty($criteria_met=='Yes')){
            $option['where']['P.criteria_met'] = $criteria_met;
        }
        else if(!empty($criteria_met=='No')){
            $option['where']['P.criteria_met'] = $criteria_met;
        }
        else if(!empty($criteria_met=='N/A')){
            $option['where']['P.infection_surveillance_checklist'] = $criteria_met;
        }
        $list = $this->common_model->customGet($option);
        $color = array();
        $label = array();
        $data = array();
        if (!empty($list)) {
            $total_antibiotic_days = array_sum(array_column($list, 'totalDays'));
            $total_antibiotic = count($list);
            foreach ($list as $rows) {
                $percentage = round(($rows->totalDays / $total_antibiotic_days) * 100);
                //$Temp['percentage'] = $percentage;
                $Temp['percentage'] = $data[] = $rows->totalDays;
                $Temp['name'] =  $label[] = $rows->name;
                $antibiotic[] = $Temp;
                $color[] = '#' . str_pad(dechex(mt_rand(0, 0xFFFFFF)), 6, '0', STR_PAD_LEFT);
            }
            $return['antibiotic'] = $antibiotic;
            $return['color'] = $color;
            $return['labels'] = $label;
            $return['data'] = $data;
            $return['total_days'] = array(array_sum(array_column($list, 'totalDays')),array_sum(array_column($list, 'ActualDot')),);
            $return['care_name'] = (!empty($careUnit)) ? $list[0]->care_name : "All";
        } else {
            $return['status'] = 400;
        }
        echo json_encode($return);
    }




     /** new **/
     public function total_abx_days_dollar() {
        $return = array();
        $return['status'] = 200;
        $antibiotic = array();
        $return['careUnit'] = $careUnit = $this->input->post('careUnit');
        $return['provider'] = $provider = $this->input->post('provider_doctor');
        $rx = $this->input->post('rx');
        $steward_id = $this->input->post('steward');
        $symptom_onset = $this->input->post('symptom_onset');
        $criteria_met = $this->input->post('criteria_met');
        $md_stayward_response = $this->input->post('md_stayward_response');
        $culture_source = $this->input->post('culture_source');
        $organism = $this->input->post('organism');
        $precautions = $this->input->post('precautions');

        $date_of_start_abx2 = $this->input->post('date_of_start_abx2');
        $date_of_start_abx3 = $this->input->post('date_of_start_abx3');
        $date1 = (!empty($this->input->post('date1'))) ? date('Y-m-d',strtotime($this->input->post('date1'))) : '';
        $date2 = (!empty($this->input->post('date2'))) ? date('Y-m-d',strtotime($this->input->post('date2'))) : '';
        $option = array(
            'table' => 'patient P',
            'select' => 'SUM(initial_dot*RX.price) totalDays ,SUM(new_initial_dot*NRX.price) ActualDot,P.care_unit_id,CI.name as care_name',
            'join' => array(array('care_unit CI', 'CI.id=P.care_unit_id', 'inner'),
                array('patient_consult PC', 'PC.patient_id=P.id', 'inner'),
                //array('initial_dx IRX', 'IRX.id=PC.initial_dx', 'inner'),
                array('initial_rx RX', 'RX.id=PC.initial_rx', 'inner'),
                array('initial_rx NRX', 'NRX.id=PC.new_initial_rx', 'inner') ),
           // 'group_by' => array('IRX.name')
        );
        if (!empty($date1) && !empty($date2)) {
            $option['where']['date(P.date_of_start_abx) >='] = $date1;
            $option['where']['date(P.date_of_start_abx) <='] = $date2;
        }

        if(!empty($date_of_start_abx2) && !empty($date_of_start_abx3)){
            $option['where']['QUARTER(P.date_of_start_abx)'] = $date_of_start_abx2;
            $option['where']['year(P.date_of_start_abx)'] = $date_of_start_abx3;
        }else if($date_of_start_abx2 == '2021'){
            $option['where']['Year(P.date_of_start_abx)'] = $date_of_start_abx2;
        }else if($date_of_start_abx3 == '2021'){
            $option['where']['Year(P.date_of_start_abx)'] = $date_of_start_abx3;
        }else if($date_of_start_abx2 == '2022'){
            $option['where']['Year(P.date_of_start_abx)'] = $date_of_start_abx2;
        }else if($date_of_start_abx3 == '2022'){
            $option['where']['Year(P.date_of_start_abx)'] = $date_of_start_abx3;
        }else if($date_of_start_abx2 == '2023'){
            $option['where']['Year(P.date_of_start_abx)'] = $date_of_start_abx2;
        }else if($date_of_start_abx3 == '2023'){
            $option['where']['Year(P.date_of_start_abx)'] = $date_of_start_abx3;
        }

        if (!empty($careUnit)) {
            $option['where']['P.care_unit_id'] = $careUnit;
        }
        if (!empty($rx)) {
            $option['where']['RX.id'] = $rx;
        }
        if (!empty($provider)) {
            $option['where']['P.doctor_id'] = $provider;
        }
        if (!empty($steward_id)) {
            $option['where']['P.md_steward_id'] = $steward_id;
        }
        if(!empty($symptom_onset)){
            $option['where']['P.symptom_onset'] = $symptom_onset;
        }
        if(!empty($md_stayward_response)){
            $option['where']['P.md_stayward_response'] = $md_stayward_response;
        }
        if(!empty($culture_source)){
            $option['where']['P.culture_source'] = $culture_source;
        }
        if(!empty($organism)){
            $option['where']['P.organism'] = $organism;
        }
        if(!empty($precautions)){
            $option['where']['P.precautions'] = $precautions;
        }
        if(!empty($criteria_met=='Yes')){
            $option['where']['P.criteria_met'] = $criteria_met;
        }
        else if(!empty($criteria_met=='No')){
            $option['where']['P.criteria_met'] = $criteria_met;
        }
        else if(!empty($criteria_met=='N/A')){
            $option['where']['P.infection_surveillance_checklist'] = $criteria_met;
        }
        $list = $this->common_model->customGet($option);
        $color = array();
        $label = array();
        $data = array();
        if (!empty($list)) {
            $total_antibiotic_days = array_sum(array_column($list, 'totalDays'));
            $total_antibiotic = count($list);
            foreach ($list as $rows) {
                $percentage = round(($rows->totalDays / $total_antibiotic_days) * 100);
                //$Temp['percentage'] = $percentage;
                $Temp['percentage'] = $data[] = $rows->totalDays;
                $Temp['name'] =  $label[] = $rows->name;
                $antibiotic[] = $Temp;
                $color[] = '#' . str_pad(dechex(mt_rand(0, 0xFFFFFF)), 6, '0', STR_PAD_LEFT);
            }
            $return['antibiotic'] = $antibiotic;
            $return['color'] = $color;
            $return['labels'] = $label;
            $return['data'] = $data;
            $return['total_amount'] = array(round(array_sum(array_column($list, 'totalDays')),2),round(array_sum(array_column($list, 'ActualDot')),2));
            $return['care_name'] = (!empty($careUnit)) ? $list[0]->care_name : "All";
        } else {
            $return['status'] = 400;
        }
        echo json_encode($return);
    }
   



    /** new **/
    public function get_days_provider_and_steward() {
        $return = array();
        $return['status'] = 200;
        $antibiotic = array();
        $return['careUnit'] = $careUnit = $this->input->post('careUnit');
        $return['provider'] = $provider = $this->input->post('provider_doctor');
        $rx = $this->input->post('rx');
        $steward_id = $this->input->post('steward');
        $symptom_onset = $this->input->post('symptom_onset');
        $criteria_met = $this->input->post('criteria_met');
        $md_stayward_response = $this->input->post('md_stayward_response');
        $culture_source = $this->input->post('culture_source');
        $organism = $this->input->post('organism');
        $precautions = $this->input->post('precautions');

        $date_of_start_abx2 = $this->input->post('date_of_start_abx2');
        $date_of_start_abx3 = $this->input->post('date_of_start_abx3');
        $date1 = (!empty($this->input->post('date1'))) ? date('Y-m-d',strtotime($this->input->post('date1'))) : '';
        $date2 = (!empty($this->input->post('date2'))) ? date('Y-m-d',strtotime($this->input->post('date2'))) : '';
        $option = array(
            'table' => 'patient P',
            'select' => 'P.id,P.patient_id,P.doctor_id,U.name',
            'join' => array(array('doctors U', 'U.id=P.doctor_id', 'inner')),
            'group_by' => array('P.doctor_id'),
            'order' => array('U.name' => 'ASC')
        );
        if (!empty($date1) && !empty($date2)) {
          $option['where']['date(P.date_of_start_abx) >='] = $date1;
          $option['where']['date(P.date_of_start_abx) <='] = $date2;
        }

        if(!empty($date_of_start_abx2) && !empty($date_of_start_abx3)){
            $option['where']['QUARTER(P.date_of_start_abx)'] = $date_of_start_abx2;
            $option['where']['year(P.date_of_start_abx)'] = $date_of_start_abx3;
        }else if($date_of_start_abx2 == '2021'){
            $option['where']['Year(P.date_of_start_abx)'] = $date_of_start_abx2;
        }else if($date_of_start_abx3 == '2021'){
            $option['where']['Year(P.date_of_start_abx)'] = $date_of_start_abx3;
        }else if($date_of_start_abx2 == '2022'){
            $option['where']['Year(P.date_of_start_abx)'] = $date_of_start_abx2;
        }else if($date_of_start_abx3 == '2022'){
            $option['where']['Year(P.date_of_start_abx)'] = $date_of_start_abx3;
        }else if($date_of_start_abx2 == '2023'){
            $option['where']['Year(P.date_of_start_abx)'] = $date_of_start_abx2;
        }else if($date_of_start_abx3 == '2023'){
            $option['where']['Year(P.date_of_start_abx)'] = $date_of_start_abx3;
        }

        if (!empty($careUnit)) {
            $option['where']['P.care_unit_id'] = $careUnit;
        }
        if (!empty($provider)) {
            $option['where']['P.doctor_id'] = $provider;
        }
        if (!empty($steward_id)) {
            $option['where']['P.md_steward_id'] = $steward_id;
        }
        if(!empty($symptom_onset)){
            $option['where']['P.symptom_onset'] = $symptom_onset;
        }
        if(!empty($md_stayward_response)){
            $option['where']['P.md_stayward_response'] = $md_stayward_response;
        }
        if(!empty($culture_source)){
            $option['where']['P.culture_source'] = $culture_source;
        }
        if(!empty($organism)){
            $option['where']['P.organism'] = $organism;
        }
        if(!empty($precautions)){
            $option['where']['P.precautions'] = $precautions;
        }
        if(!empty($criteria_met=='Yes')){
            $option['where']['P.criteria_met'] = $criteria_met;
        }
        else if(!empty($criteria_met=='No')){
            $option['where']['P.criteria_met'] = $criteria_met;
        }
        else if(!empty($criteria_met=='N/A')){
            $option['where']['P.infection_surveillance_checklist'] = $criteria_met;
        }
        $steward = $this->common_model->customGet($option);
        $optionsGraph = array();
        if (!empty($steward)) {
            foreach ($steward as $row) {
                $Temp['md_name'] = $row->name;
                $Temp['doctor_id'] = $row->doctor_id;
                $option = array(
                    'table' => 'patient P',
                    'select' => 'sum(PC.initial_dot) as total_initial_dot,sum(PC.new_initial_dot) as total_new_initial_dot,'
                    . 'CI.name as care_name',
                    'join' => array(array('care_unit CI', 'CI.id=P.care_unit_id', 'inner'),
                        array('patient_consult PC', 'PC.patient_id=P.id', 'inner')),
                    'single' => true
                );
                if (!empty($date1) && !empty($date2)) {
                    $option['where']['date(P.date_of_start_abx) >='] = $date1;
                    $option['where']['date(P.date_of_start_abx) <='] = $date2;
                }

                if(!empty($date_of_start_abx2) && !empty($date_of_start_abx3)){
                    $option['where']['QUARTER(P.date_of_start_abx)'] = $date_of_start_abx2;
                    $option['where']['year(P.date_of_start_abx)'] = $date_of_start_abx3;
                }else if($date_of_start_abx2 == '2021'){
                    $option['where']['Year(P.date_of_start_abx)'] = $date_of_start_abx2;
                }else if($date_of_start_abx3 == '2021'){
                    $option['where']['Year(P.date_of_start_abx)'] = $date_of_start_abx3;
                }else if($date_of_start_abx2 == '2022'){
                    $option['where']['Year(P.date_of_start_abx)'] = $date_of_start_abx2;
                }else if($date_of_start_abx3 == '2022'){
                    $option['where']['Year(P.date_of_start_abx)'] = $date_of_start_abx3;
                }else if($date_of_start_abx2 == '2023'){
                    $option['where']['Year(P.date_of_start_abx)'] = $date_of_start_abx2;
                }else if($date_of_start_abx3 == '2023'){
                    $option['where']['Year(P.date_of_start_abx)'] = $date_of_start_abx3;
                }

                if (!empty($careUnit)) {
                    $option['where']['P.care_unit_id'] = $careUnit;
                }
                if(!empty($symptom_onset)){
                    $option['where']['P.symptom_onset'] = $symptom_onset;
                }
                if(!empty($md_stayward_response)){
                    $option['where']['P.md_stayward_response'] = $md_stayward_response;
                }
                if(!empty($culture_source)){
                    $option['where']['P.culture_source'] = $culture_source;
                }
                if(!empty($organism)){
                    $option['where']['P.organism'] = $organism;
                }
                if(!empty($precautions)){
                    $option['where']['P.precautions'] = $precautions;
                }
                if(!empty($criteria_met=='Yes')){
                    $option['where']['P.criteria_met'] = $criteria_met;
                }
                else if(!empty($criteria_met=='No')){
                    $option['where']['P.criteria_met'] = $criteria_met;
                }
                else if(!empty($criteria_met=='N/A')){
                    $option['where']['P.infection_surveillance_checklist'] = $criteria_met;
                }
                if (!empty($rx)) {
                    $option['where']['PC.initial_rx'] = $rx;
                }
                if (!empty($provider)) {
                    $option['where']['P.doctor_id'] = $provider;
                }
                if (!empty($steward_id)) {
                    $option['where']['P.md_steward_id'] = $steward_id;
                }
                $option['where']['P.doctor_id'] = $row->doctor_id;
                $list = $this->common_model->customGet($option);
                if (!empty($list)) {
                    $Temp1['total_initial_dot'] = $list->total_initial_dot;
                    $Temp1['total_new_initial_dot'] = $list->total_new_initial_dot;
                    $Temp['dot'] = $Temp1;
                } else {
                    $Temp['dot'] = array();
                }
                $optionsGraph[] = $Temp;
            }
        }
        $Temp123 = array();
        foreach ($optionsGraph as $graph) {
            $Temp11['label'] = "Provider Days";
            $Temp11['backgroundColor'] = "#B7950B";
            $Temp11['data'][] = (!empty($graph['dot']['total_initial_dot'])) ? $graph['dot']['total_initial_dot'] : 0;

            $Temp12['label'] = "Steward Days";
            $Temp12['backgroundColor'] = "#8E44AD";
            $Temp12['data'][] = (!empty($graph['dot']['total_new_initial_dot'])) ? $graph['dot']['total_new_initial_dot'] : 0;
        }
        $Temp123 = array($Temp11, $Temp12);
        $return['antibiotic'] = $optionsGraph;
        $return['datasheet'] = $Temp123;
        echo json_encode($return);
    
    }
    /** new **/
    public function get_days_cost() {
        $return = array();
        $return['status'] = 200;
        $antibiotic = array();
        $return['careUnit'] = $careUnit = $this->input->post('careUnit');
        $return['provider'] = $provider = $this->input->post('provider_doctor');
        $rx = $this->input->post('rx');
        $steward_id = $this->input->post('steward');
        $symptom_onset = $this->input->post('symptom_onset');
        $criteria_met = $this->input->post('criteria_met');
        $md_stayward_response = $this->input->post('md_stayward_response');
        $culture_source = $this->input->post('culture_source');
        $organism = $this->input->post('organism');
        $precautions = $this->input->post('precautions');

        $date_of_start_abx2 = $this->input->post('date_of_start_abx2');
        $date_of_start_abx3 = $this->input->post('date_of_start_abx3');
        $date1 = (!empty($this->input->post('date1'))) ? date('Y-m-d',strtotime($this->input->post('date1'))) : '';
        $date2 = (!empty($this->input->post('date2'))) ? date('Y-m-d',strtotime($this->input->post('date2'))) : '';
                $total_days = 0;
                $option = array(
                    'table' => 'patient P',
                    'select' => 'sum(PC.initial_dot) as total_initial_dot,sum(PC.new_initial_dot) as total_new_initial_dot,'
                    . 'CI.name as care_name',
                    'join' => array(array('care_unit CI', 'CI.id=P.care_unit_id', 'inner'),
                        array('patient_consult PC', 'PC.patient_id=P.id', 'inner')),
                    'single' => true
                );
                if (!empty($rx)) {
                    $option['where']['PC.initial_rx'] = $rx;
                }
                if (!empty($date1) && !empty($date2)) {
                 $option['where']['date(P.date_of_start_abx) >='] = $date1;
                 $option['where']['date(P.date_of_start_abx) <='] = $date2;
                }

                if(!empty($date_of_start_abx2) && !empty($date_of_start_abx3)){
                    $option['where']['QUARTER(P.date_of_start_abx)'] = $date_of_start_abx2;
                    $option['where']['year(P.date_of_start_abx)'] = $date_of_start_abx3;
                }else if($date_of_start_abx2 == '2021'){
                    $option['where']['Year(P.date_of_start_abx)'] = $date_of_start_abx2;
                }else if($date_of_start_abx3 == '2021'){
                    $option['where']['Year(P.date_of_start_abx)'] = $date_of_start_abx3;
                }else if($date_of_start_abx2 == '2022'){
                    $option['where']['Year(P.date_of_start_abx)'] = $date_of_start_abx2;
                }else if($date_of_start_abx3 == '2022'){
                    $option['where']['Year(P.date_of_start_abx)'] = $date_of_start_abx3;
                }else if($date_of_start_abx2 == '2023'){
                    $option['where']['Year(P.date_of_start_abx)'] = $date_of_start_abx2;
                }else if($date_of_start_abx3 == '2023'){
                    $option['where']['Year(P.date_of_start_abx)'] = $date_of_start_abx3;
                }


                if (!empty($careUnit)) {
                    $option['where']['P.care_unit_id'] = $careUnit;
                }
                        if (!empty($provider)) {
            $option['where']['P.doctor_id'] = $provider;
        }
                if (!empty($steward_id)) {
            $option['where']['P.md_steward_id'] = $steward_id;
        }
        if(!empty($symptom_onset)){
            $option['where']['P.symptom_onset'] = $symptom_onset;
        }
        if(!empty($md_stayward_response)){
            $option['where']['P.md_stayward_response'] = $md_stayward_response;
        }
        if(!empty($culture_source)){
            $option['where']['P.culture_source'] = $culture_source;
        }
        if(!empty($organism)){
            $option['where']['P.organism'] = $organism;
        }
        if(!empty($precautions)){
            $option['where']['P.precautions'] = $precautions;
        }
        if(!empty($criteria_met=='Yes')){
            $option['where']['P.criteria_met'] = $criteria_met;
        }
        else if(!empty($criteria_met=='No')){
            $option['where']['P.criteria_met'] = $criteria_met;
        }
        else if(!empty($criteria_met=='N/A')){
            $option['where']['P.infection_surveillance_checklist'] = $criteria_met;
        }
                $list = $this->common_model->customGet($option);
                if (!empty($list)) {
                    $total_days = $list->total_initial_dot - $list->total_new_initial_dot;
                }
            
        $return['total_days'] = array($total_days);
        echo json_encode($return);
    
    }




 /** new **/
 public function get_days_costsavedbysteward() {
    $return = array();
    $return['status'] = 200;
    $antibiotic = array();
    $return['careUnit'] = $careUnit = $this->input->post('careUnit');
    $return['provider'] = $provider = $this->input->post('provider_doctor');
    $rx = $this->input->post('rx');
    $steward_id = $this->input->post('steward');
    $symptom_onset = $this->input->post('symptom_onset');
    $criteria_met = $this->input->post('criteria_met');
    $md_stayward_response = $this->input->post('md_stayward_response');
    $culture_source = $this->input->post('culture_source');
    $organism = $this->input->post('organism');
    $precautions = $this->input->post('precautions');
    
    $date_of_start_abx2 = $this->input->post('date_of_start_abx2');
    $date_of_start_abx3 = $this->input->post('date_of_start_abx3');
    $date1 = (!empty($this->input->post('date1'))) ? date('Y-m-d',strtotime($this->input->post('date1'))) : '';
    $date2 = (!empty($this->input->post('date2'))) ? date('Y-m-d',strtotime($this->input->post('date2'))) : '';
            $total_days = 0;
            $option = array(
                'table' => 'patient P',
                'select' => 'sum(PC.initial_dot*RX.price) as total_initial_dot,sum(PC.new_initial_dot*NRX.price) as total_new_initial_dot,'
                . 'CI.name as care_name',
                'join' => array(array('care_unit CI', 'CI.id=P.care_unit_id', 'inner'),
                    array('patient_consult PC', 'PC.patient_id=P.id', 'inner'),
                   // array('initial_rx RX', 'RX.patient_id=P.id', 'inner'),
                   // array('initial_dx IRX', 'IRX.id=PC.initial_dx', 'inner'),
                    array('initial_rx RX', 'RX.id=PC.initial_rx', 'inner'),
                    array('initial_rx NRX', 'NRX.id=PC.new_initial_rx', 'inner')),
                'single' => true
            );
            if (!empty($rx)) {
                $option['where']['PC.initial_rx'] = $rx;
            }
            if (!empty($date1) && !empty($date2)) {
             $option['where']['date(P.date_of_start_abx) >='] = $date1;
             $option['where']['date(P.date_of_start_abx) <='] = $date2;
            }

            if(!empty($date_of_start_abx2) && !empty($date_of_start_abx3)){
                $option['where']['QUARTER(P.date_of_start_abx)'] = $date_of_start_abx2;
                $option['where']['year(P.date_of_start_abx)'] = $date_of_start_abx3;
            }else if($date_of_start_abx2 == '2021'){
                $option['where']['Year(P.date_of_start_abx)'] = $date_of_start_abx2;
            }else if($date_of_start_abx3 == '2021'){
                $option['where']['Year(P.date_of_start_abx)'] = $date_of_start_abx3;
            }else if($date_of_start_abx2 == '2022'){
                $option['where']['Year(P.date_of_start_abx)'] = $date_of_start_abx2;
            }else if($date_of_start_abx3 == '2022'){
                $option['where']['Year(P.date_of_start_abx)'] = $date_of_start_abx3;
            }else if($date_of_start_abx2 == '2023'){
                $option['where']['Year(P.date_of_start_abx)'] = $date_of_start_abx2;
            }else if($date_of_start_abx3 == '2023'){
                $option['where']['Year(P.date_of_start_abx)'] = $date_of_start_abx3;
            }


            if (!empty($careUnit)) {
                $option['where']['P.care_unit_id'] = $careUnit;
            }
            if (!empty($provider)) {
               $option['where']['P.doctor_id'] = $provider;
            }
            if (!empty($steward_id)) {
               $option['where']['P.md_steward_id'] = $steward_id;
            }
            if(!empty($symptom_onset)){
                 $option['where']['P.symptom_onset'] = $symptom_onset;
            }
            if(!empty($md_stayward_response)){
                $option['where']['P.md_stayward_response'] = $md_stayward_response;
            }
            if(!empty($culture_source)){
                $option['where']['P.culture_source'] = $culture_source;
            }
            if(!empty($organism)){
                $option['where']['P.organism'] = $organism;
            }
            if(!empty($precautions)){
                $option['where']['P.precautions'] = $precautions;
            }
            if(!empty($criteria_met=='Yes')){
                $option['where']['P.criteria_met'] = $criteria_met;
            }
            else if(!empty($criteria_met=='No')){
                $option['where']['P.criteria_met'] = $criteria_met;
            }
            else if(!empty($criteria_met=='N/A')){
                $option['where']['P.infection_surveillance_checklist'] = $criteria_met;
            }
            $list = $this->common_model->customGet($option);
            if (!empty($list)) {
                $total_days = round(($list->total_initial_dot - $list->total_new_initial_dot),2);
            }
        
    $return['total_amount_saved'] = array($total_days);
    echo json_encode($return);

}







 /** new **/
 public function get_days_provider_and_steward_new() {
    $return = array();
    $return['status'] = 200;
    $antibiotic = array();
    $return['careUnit'] = $careUnit = $this->input->post('careUnit');
    $return['provider'] = $provider = $this->input->post('provider_doctor');
    $rx = $this->input->post('rx');
    $steward_id = $this->input->post('steward');
    $symptom_onset = $this->input->post('symptom_onset');
    $criteria_met = $this->input->post('criteria_met');
    $md_stayward_response = $this->input->post('md_stayward_response');
    $culture_source = $this->input->post('culture_source');
    $organism = $this->input->post('organism');
    $precautions = $this->input->post('precautions');
    
    $date_of_start_abx2 = $this->input->post('date_of_start_abx2');
    $date_of_start_abx3 = $this->input->post('date_of_start_abx3');
    $date1 = (!empty($this->input->post('date1'))) ? date('Y-m-d',strtotime($this->input->post('date1'))) : '';
    $date2 = (!empty($this->input->post('date2'))) ? date('Y-m-d',strtotime($this->input->post('date2'))) : '';
   /*  $option = array(
        'table' => 'patient P',
        'select' => 'P.id,P.patient_id,P.doctor_id,RX.name',
        'join' => array(array('patient_consult PC', 'PC.patient_id=P.id', 'inner'),array('initial_rx RX', 'RX.id=PC.initial_rx', 'inner')),
        'group_by' => array('PC.initial_rx'),
        'order' => array('RX.name' => 'ASC')
    );
    print_r($option); */
    $option = array(
        'table' => 'patient P',
        'select' => 'DISTINCT(PC.initial_rx) as rx_id,sum(PC.initial_dot) as total,PC.initial_rx,P.care_unit_id,IRX.name,CI.name as care_name',
        'join' => array(array('care_unit CI', 'CI.id=P.care_unit_id', 'inner'),
        array('patient_consult PC', 'PC.patient_id=P.id', 'inner'),
        array('initial_rx IRX', 'IRX.id=PC.initial_rx', 'inner')),
        'group_by' => array('PC.initial_rx'),
        'order' => array('IRX.name' => 'ASC')
        ); 
    if (!empty($date1) && !empty($date2)) {
        $option['where']['date(P.date_of_start_abx) >='] = $date1;
        $option['where']['date(P.date_of_start_abx) <='] = $date2;
    }

    if(!empty($date_of_start_abx2) && !empty($date_of_start_abx3)){
        $option['where']['QUARTER(P.date_of_start_abx)'] = $date_of_start_abx2;
        $option['where']['year(P.date_of_start_abx)'] = $date_of_start_abx3;
    }else if($date_of_start_abx2 == '2021'){
        $option['where']['Year(P.date_of_start_abx)'] = $date_of_start_abx2;
    }else if($date_of_start_abx3 == '2021'){
        $option['where']['Year(P.date_of_start_abx)'] = $date_of_start_abx3;
    }else if($date_of_start_abx2 == '2022'){
        $option['where']['Year(P.date_of_start_abx)'] = $date_of_start_abx2;
    }else if($date_of_start_abx3 == '2022'){
        $option['where']['Year(P.date_of_start_abx)'] = $date_of_start_abx3;
    }else if($date_of_start_abx2 == '2023'){
        $option['where']['Year(P.date_of_start_abx)'] = $date_of_start_abx2;
    }else if($date_of_start_abx3 == '2023'){
        $option['where']['Year(P.date_of_start_abx)'] = $date_of_start_abx3;
    }


    if (!empty($careUnit)) {
        $option['where']['P.care_unit_id'] = $careUnit;
    }
    if (!empty($provider)) {
        $option['where']['P.doctor_id'] = $provider;
    } 
    if (!empty($steward_id)) {
        $option['where']['P.md_steward_id'] = $steward_id;
    }
    if (!empty($rx)) {
        $option['where']['PC.initial_rx'] = $rx;
    }
    if(!empty($symptom_onset)){
        $option['where']['P.symptom_onset'] = $symptom_onset;
    }
    if(!empty($md_stayward_response)){
        $option['where']['P.md_stayward_response'] = $md_stayward_response;
    }
    if(!empty($culture_source)){
        $option['where']['P.culture_source'] = $culture_source;
    }
    if(!empty($organism)){
        $option['where']['P.organism'] = $organism;
    }
    if(!empty($precautions)){
        $option['where']['P.precautions'] = $precautions;
    }
    if(!empty($criteria_met=='Yes')){
        $option['where']['P.criteria_met'] = $criteria_met;
    }
    else if(!empty($criteria_met=='No')){
        $option['where']['P.criteria_met'] = $criteria_met;
    }
    else if(!empty($criteria_met=='N/A')){
        $option['where']['P.infection_surveillance_checklist'] = $criteria_met;
    }
    $steward = $this->common_model->customGet($option);
    $optionsGraph = array();
    if (!empty($steward)) {
        foreach ($steward as $row) {
            $Temp['name'] = $row->name;
            $Temp['initial_rx'] = $row->initial_rx;
            $option = array(
                'table' => 'patient P',
                'select' => 'sum(PC.initial_dot) as total_initial_dot,sum(PC.new_initial_dot) as total_new_initial_dot,'
                . 'CI.name as care_name',
                'join' => array(array('care_unit CI', 'CI.id=P.care_unit_id', 'inner'),
                    array('patient_consult PC', 'PC.patient_id=P.id', 'inner')),
                'single' => true
            );
            if (!empty($date1) && !empty($date2)) {
                $option['where']['date(P.date_of_start_abx) >='] = $date1;
                $option['where']['date(P.date_of_start_abx) <='] = $date2;
            }

            if(!empty($date_of_start_abx2) && !empty($date_of_start_abx3)){
                $option['where']['QUARTER(P.date_of_start_abx)'] = $date_of_start_abx2;
                $option['where']['year(P.date_of_start_abx)'] = $date_of_start_abx3;
            }else if($date_of_start_abx2 == '2021'){
                $option['where']['Year(P.date_of_start_abx)'] = $date_of_start_abx2;
            }else if($date_of_start_abx3 == '2021'){
                $option['where']['Year(P.date_of_start_abx)'] = $date_of_start_abx3;
            }else if($date_of_start_abx2 == '2022'){
                $option['where']['Year(P.date_of_start_abx)'] = $date_of_start_abx2;
            }else if($date_of_start_abx3 == '2022'){
                $option['where']['Year(P.date_of_start_abx)'] = $date_of_start_abx3;
            }else if($date_of_start_abx2 == '2023'){
                $option['where']['Year(P.date_of_start_abx)'] = $date_of_start_abx2;
            }else if($date_of_start_abx3 == '2023'){
                $option['where']['Year(P.date_of_start_abx)'] = $date_of_start_abx3;
            }

              if (!empty($careUnit)) {
                $option['where']['P.care_unit_id'] = $careUnit;
            }
              if (!empty($provider)) {
                $option['where']['P.doctor_id'] = $provider;
            } 
              if (!empty($steward_id)) {
                $option['where']['P.md_steward_id'] = $steward_id;
            }
              if(!empty($symptom_onset)){
                $option['where']['P.symptom_onset'] = $symptom_onset;
            }
            if(!empty($md_stayward_response)){
                $option['where']['P.md_stayward_response'] = $md_stayward_response;
            }
            if(!empty($culture_source)){
                $option['where']['P.culture_source'] = $culture_source;
            }
            if(!empty($organism)){
                $option['where']['P.organism'] = $organism;
            }
            if(!empty($precautions)){
                $option['where']['P.precautions'] = $precautions;
            }
            if(!empty($criteria_met=='Yes')){
                $option['where']['P.criteria_met'] = $criteria_met;
            }
            else if(!empty($criteria_met=='No')){
                $option['where']['P.criteria_met'] = $criteria_met;
            }
            else if(!empty($criteria_met=='N/A')){
                $option['where']['P.infection_surveillance_checklist'] = $criteria_met;
            }
            
            if (!empty($rx)) {
                $option['where']['PC.initial_rx'] = $rx;
            }
            $option['where']['PC.initial_rx'] = $row->initial_rx;
            $list = $this->common_model->customGet($option);
            if (!empty($list)) {
                $Temp1['total_initial_dot'] = $list->total_initial_dot;
                $Temp1['total_new_initial_dot'] = $list->total_new_initial_dot;
                $Temp['dot'] = $Temp1;
            } else {
                $Temp['dot'] = array();
            }
            $optionsGraph[] = $Temp;
        }
    }
    $Temp123 = array();
    foreach ($optionsGraph as $graph) {
        $Temp11['label'] = "Provider Antibiotic Days on Therapy ";
        $Temp11['backgroundColor'] = "#B7950B";
        $Temp11['data'][] = (!empty($graph['dot']['total_initial_dot'])) ? $graph['dot']['total_initial_dot'] : 0;

        $Temp12['label'] = "Steward Antibiotic Days on Therapy";
        $Temp12['backgroundColor'] = "#8E44AD";
        $Temp12['data'][] = (!empty($graph['dot']['total_new_initial_dot'])) ? $graph['dot']['total_new_initial_dot'] : 0;
    }
    $Temp123 = array($Temp11, $Temp12);
    $return['antibiotic'] = $optionsGraph;
    $return['datasheet'] = $Temp123;
    echo json_encode($return);

}







/** new **/
public function get_days_provider_and_steward_cost() {
    $return = array();
    $return['status'] = 200;
    $antibiotic = array();
    $return['careUnit'] = $careUnit = $this->input->post('careUnit');
    $return['provider'] = $provider = $this->input->post('provider_doctor');
    $rx = $this->input->post('rx');
    $steward_id = $this->input->post('steward');
    $symptom_onset = $this->input->post('symptom_onset');
    $criteria_met = $this->input->post('criteria_met');
    $md_stayward_response = $this->input->post('md_stayward_response');
    $culture_source = $this->input->post('culture_source');
    $organism = $this->input->post('organism');
    $precautions = $this->input->post('precautions');

    $date_of_start_abx2 = $this->input->post('date_of_start_abx2');
    $date_of_start_abx3 = $this->input->post('date_of_start_abx3');
    $date1 = (!empty($this->input->post('date1'))) ? date('Y-m-d',strtotime($this->input->post('date1'))) : '';
    $date2 = (!empty($this->input->post('date2'))) ? date('Y-m-d',strtotime($this->input->post('date2'))) : '';
   /*  $option = array(
        'table' => 'patient P',
        'select' => 'P.id,P.patient_id,P.doctor_id,RX.name,RX.price',
        'join' => array(array('doctors U', 'U.id=P.doctor_id', 'inner'),array('initial_rx RX', 'RX.id=P.doctor_id', 'inner')),
        'group_by' => array('P.doctor_id'),
        'order' => array('RX.name' => 'ASC')
    ); */
    $option = array(
        'table' => 'patient P',
        'select' => 'DISTINCT(PC.initial_rx) as rx_id,sum(PC.initial_dot) as total,PC.initial_rx,IRX.price,P.care_unit_id,IRX.name,CI.name as care_name',
        'join' => array(array('care_unit CI', 'CI.id=P.care_unit_id', 'inner'),
        array('patient_consult PC', 'PC.patient_id=P.id', 'inner'),
        array('initial_rx IRX', 'IRX.id=PC.initial_rx', 'inner')),
        'group_by' => array('PC.initial_rx'),
        'order' => array('IRX.name' => 'ASC')
        ); 
    if (!empty($date1) && !empty($date2)) {
      $option['where']['date(P.date_of_start_abx) >='] = $date1;
      $option['where']['date(P.date_of_start_abx) <='] = $date2;
    }

    if(!empty($date_of_start_abx2) && !empty($date_of_start_abx3)){
        $option['where']['QUARTER(P.date_of_start_abx)'] = $date_of_start_abx2;
        $option['where']['year(P.date_of_start_abx)'] = $date_of_start_abx3;
    }else if($date_of_start_abx2 == '2021'){
        $option['where']['Year(P.date_of_start_abx)'] = $date_of_start_abx2;
    }else if($date_of_start_abx3 == '2021'){
        $option['where']['Year(P.date_of_start_abx)'] = $date_of_start_abx3;
    }else if($date_of_start_abx2 == '2022'){
        $option['where']['Year(P.date_of_start_abx)'] = $date_of_start_abx2;
    }else if($date_of_start_abx3 == '2022'){
        $option['where']['Year(P.date_of_start_abx)'] = $date_of_start_abx3;
    }else if($date_of_start_abx2 == '2023'){
        $option['where']['Year(P.date_of_start_abx)'] = $date_of_start_abx2;
    }else if($date_of_start_abx3 == '2023'){
        $option['where']['Year(P.date_of_start_abx)'] = $date_of_start_abx3;
    }


    if (!empty($careUnit)) {
        $option['where']['P.care_unit_id'] = $careUnit;
    }
    if (!empty($provider)) {
        $option['where']['P.doctor_id'] = $provider;
    }
    if (!empty($steward_id)) {
        $option['where']['P.md_steward_id'] = $steward_id;
    }
    if (!empty($rx)) {
        $option['where']['PC.initial_rx'] = $rx;
    }
    if(!empty($symptom_onset)){
        $option['where']['P.symptom_onset'] = $symptom_onset;
    }
    if(!empty($md_stayward_response)){
        $option['where']['P.md_stayward_response'] = $md_stayward_response;
    }
    if(!empty($culture_source)){
        $option['where']['P.culture_source'] = $culture_source;
    }
    if(!empty($organism)){
        $option['where']['P.organism'] = $organism;
    }
    if(!empty($precautions)){
        $option['where']['P.precautions'] = $precautions;
    }
    if(!empty($criteria_met=='Yes')){
        $option['where']['P.criteria_met'] = $criteria_met;
    }
    else if(!empty($criteria_met=='No')){
        $option['where']['P.criteria_met'] = $criteria_met;
    }
    else if(!empty($criteria_met=='N/A')){
        $option['where']['P.infection_surveillance_checklist'] = $criteria_met;
    }
    $steward = $this->common_model->customGet($option);
    $optionsGraph = array();
    if (!empty($steward)) {
        foreach ($steward as $row) {
            $Temp['name'] = $row->name;
            $Temp['initial_rx'] = $row->initial_rx;
            /* $option = array(
                'table' => 'patient P',
                'select' => 'sum(PC.initial_dot)*(RX.price) as Initial_Cost,sum(PC.new_initial_dot)*(RX.price) as Actual_Cost,'
                . 'CI.name as care_name',
                'join' => array(array('care_unit CI', 'CI.id=P.care_unit_id', 'inner'),
                    array('patient_consult PC', 'PC.patient_id=P.id', 'inner'),array('initial_rx RX', 'RX.id=P.doctor_id', 'inner')),
                'single' => true
            ); */
            $option = array(
                'table' => 'patient P',
                'select' => 'sum(PC.initial_dot)*(RX.price) as Initial_Cost,sum(PC.new_initial_dot)*(NRX.price) as Actual_Cost,'
                . 'CI.name as care_name',
                'join' => array(array('care_unit CI', 'CI.id=P.care_unit_id', 'inner'),
                    array('patient_consult PC', 'PC.patient_id=P.id', 'inner'),array('initial_rx RX', 'RX.id=PC.initial_rx', 'inner'),array('initial_rx NRX', 'NRX.id=PC.new_initial_rx', 'inner')),
                'single' => true
            );
            if (!empty($date1) && !empty($date2)) {
                $option['where']['date(P.date_of_start_abx) >='] = $date1;
                $option['where']['date(P.date_of_start_abx) <='] = $date2;
              }

              if(!empty($date_of_start_abx2) && !empty($date_of_start_abx3)){
                $option['where']['QUARTER(P.date_of_start_abx)'] = $date_of_start_abx2;
                $option['where']['year(P.date_of_start_abx)'] = $date_of_start_abx3;
            }else if($date_of_start_abx2 == '2021'){
                $option['where']['Year(P.date_of_start_abx)'] = $date_of_start_abx2;
            }else if($date_of_start_abx3 == '2021'){
                $option['where']['Year(P.date_of_start_abx)'] = $date_of_start_abx3;
            }else if($date_of_start_abx2 == '2022'){
                $option['where']['Year(P.date_of_start_abx)'] = $date_of_start_abx2;
            }else if($date_of_start_abx3 == '2022'){
                $option['where']['Year(P.date_of_start_abx)'] = $date_of_start_abx3;
            }else if($date_of_start_abx2 == '2023'){
                $option['where']['Year(P.date_of_start_abx)'] = $date_of_start_abx2;
            }else if($date_of_start_abx3 == '2023'){
                $option['where']['Year(P.date_of_start_abx)'] = $date_of_start_abx3;
            }


              if(!empty($symptom_onset)){
                $option['where']['P.symptom_onset'] = $symptom_onset;
            }
            if(!empty($md_stayward_response)){
                $option['where']['P.md_stayward_response'] = $md_stayward_response;
            }
            if(!empty($culture_source)){
                $option['where']['P.culture_source'] = $culture_source;
            }
            if(!empty($organism)){
                $option['where']['P.organism'] = $organism;
            }
            if(!empty($precautions)){
                $option['where']['P.precautions'] = $precautions;
            }
            if(!empty($criteria_met=='Yes')){
                $option['where']['P.criteria_met'] = $criteria_met;
            }
            else if(!empty($criteria_met=='No')){
                $option['where']['P.criteria_met'] = $criteria_met;
            }
            else if(!empty($criteria_met=='N/A')){
                $option['where']['P.infection_surveillance_checklist'] = $criteria_met;
            }
            if (!empty($rx)) {
                $option['where']['PC.initial_rx'] = $rx;
            }
            if (!empty($careUnit)) {
                $option['where']['P.care_unit_id'] = $careUnit;
            }
            if (!empty($provider)) {
                $option['where']['P.doctor_id'] = $provider;
            }
            if (!empty($steward_id)) {
                $option['where']['P.md_steward_id'] = $steward_id;
            }
            $option['where']['PC.initial_rx'] = $row->initial_rx;
            $list = $this->common_model->customGet($option);
            if (!empty($list)) {
                $Temp1['Initial_Cost'] = $list->Initial_Cost;
                $Temp1['Actual_Cost'] = $list->Actual_Cost;
                $Temp['dot'] = $Temp1;
            } else {
                $Temp['dot'] = array();
            }
            $optionsGraph[] = $Temp;
        }
    }
    $Temp123 = array();
    foreach ($optionsGraph as $graph) {
        $Temp11['label'] = "Provider Antibiotic Cost on Therapy ";
        $Temp11['backgroundColor'] = "#B7950B";
        $Temp11['data'][] = (!empty($graph['dot']['Initial_Cost'])) ? $graph['dot']['Initial_Cost'] : 0;

        $Temp12['label'] = "Steward Antibiotic Cost on Therapy";
        $Temp12['backgroundColor'] = "#8E44AD";
        $Temp12['data'][] = (!empty($graph['dot']['Actual_Cost'])) ? $graph['dot']['Actual_Cost'] : 0;
    }
    $Temp123 = array($Temp11, $Temp12);
    $return['antibiotic'] = $optionsGraph;
    $return['datasheet'] = $Temp123;
    echo json_encode($return);

}

 



    /** new **/
    public function get_cost_cost() {
        $return = array();
        $return['status'] = 200;
        $antibiotic = array();
        $return['careUnit'] = $careUnit = $this->input->post('careUnit');
        $return['provider'] = $provider = $this->input->post('provider_doctor');
        $rx = $this->input->post('rx');
        $steward_id = $this->input->post('steward');
        $symptom_onset = $this->input->post('symptom_onset');
        $criteria_met = $this->input->post('criteria_met');
        $md_stayward_response = $this->input->post('md_stayward_response');
        $culture_source = $this->input->post('culture_source');
        $organism = $this->input->post('organism');
        $precautions = $this->input->post('precautions');

        $date_of_start_abx2 = $this->input->post('date_of_start_abx2');
        $date_of_start_abx3 = $this->input->post('date_of_start_abx3');
        $date1 = (!empty($this->input->post('date1'))) ? date('Y-m-d',strtotime($this->input->post('date1'))) : '';
        $date2 = (!empty($this->input->post('date2'))) ? date('Y-m-d',strtotime($this->input->post('date2'))) : '';
                $total_days = 0;
                $option = array(
                    'table' => 'patient P',
                    'select' => 'sum(PC.initial_dot) as total_initial_dot,sum(PC.new_initial_dot) as total_new_initial_dot,'
                    . 'CI.name as care_name,SUM(IRX.price) as initial_price',
                    'join' => array(array('care_unit CI', 'CI.id=P.care_unit_id', 'inner'),
                        array('patient_consult PC', 'PC.patient_id=P.id', 'inner'),
                     array('initial_rx IRX', 'IRX.id=PC.initial_rx', 'inner')),
                    'single' => true
                );
                        if (!empty($date1) && !empty($date2)) {
          $option['where']['date(P.created_date) >='] = $date1;
          $option['where']['date(P.created_date) <='] = $date2;
        }

        if(!empty($date_of_start_abx2) && !empty($date_of_start_abx3)){
            $option['where']['QUARTER(P.date_of_start_abx)'] = $date_of_start_abx2;
            $option['where']['year(P.date_of_start_abx)'] = $date_of_start_abx3;
        }else if($date_of_start_abx2 == '2021'){
            $option['where']['Year(P.date_of_start_abx)'] = $date_of_start_abx2;
        }else if($date_of_start_abx3 == '2021'){
            $option['where']['Year(P.date_of_start_abx)'] = $date_of_start_abx3;
        }else if($date_of_start_abx2 == '2022'){
            $option['where']['Year(P.date_of_start_abx)'] = $date_of_start_abx2;
        }else if($date_of_start_abx3 == '2022'){
            $option['where']['Year(P.date_of_start_abx)'] = $date_of_start_abx3;
        }else if($date_of_start_abx2 == '2023'){
            $option['where']['Year(P.date_of_start_abx)'] = $date_of_start_abx2;
        }else if($date_of_start_abx3 == '2023'){
            $option['where']['Year(P.date_of_start_abx)'] = $date_of_start_abx3;
        }


            if (!empty($careUnit)) {
                $option['where']['P.care_unit_id'] = $careUnit;
            }
                        if (!empty($provider)) {
            $option['where']['P.doctor_id'] = $provider;
        }
                if (!empty($steward_id)) {
            $option['where']['P.md_steward_id'] = $steward_id;
        }
        if(!empty($symptom_onset)){
            $option['where']['P.symptom_onset'] = $symptom_onset;
        }
        if(!empty($md_stayward_response)){
            $option['where']['P.md_stayward_response'] = $md_stayward_response;
        }
        if(!empty($culture_source)){
            $option['where']['P.culture_source'] = $culture_source;
        }
        if(!empty($organism)){
            $option['where']['P.organism'] = $organism;
        }
        if(!empty($precautions)){
            $option['where']['P.precautions'] = $precautions;
        }
        if(!empty($criteria_met=='Yes')){
            $option['where']['P.criteria_met'] = $criteria_met;
        }
        else if(!empty($criteria_met=='No')){
            $option['where']['P.criteria_met'] = $criteria_met;
        }
        else if(!empty($criteria_met=='N/A')){
            $option['where']['P.infection_surveillance_checklist'] = $criteria_met;
        }
                $list = $this->common_model->customGet($option);

                $option = array(
                    'table' => 'patient P',
                    'select' => 'sum(PC.initial_dot) as total_initial_dot,sum(PC.new_initial_dot) as total_new_initial_dot,'
                    . 'CI.name as care_name,SUM(IRXA.price) as actual_price',
                    'join' => array(array('care_unit CI', 'CI.id=P.care_unit_id', 'inner'),
                        array('patient_consult PC', 'PC.patient_id=P.id', 'inner'),
                     array('initial_rx IRXA', 'IRXA.id=PC.new_initial_rx', 'inner')),
                    'single' => true
                );
                $list2 = $this->common_model->customGet($option);
                if (!empty($list)) {
                    $total_days = $list->initial_price - $list2->actual_price;
                }
            
        $return['total_days'] = array($total_days);
        echo json_encode($return);
    
    }

      /** new **/
      public function get_antibiotic_by_care_unit_facility_days() {
        
        $return = array();
        $return['status'] = 200;
        $antibiotic = array();
        $return['careUnit'] = $careUnit = $this->input->post('careUnit');
        $return['steward'] = $steward = $this->input->post('steward');
        $provider = $this->input->post('provider');
        $rx = $this->input->post('rx');
        $symptom_onset = $this->input->post('symptom_onset');
        $criteria_met = $this->input->post('criteria_met');
        $md_stayward_response = $this->input->post('md_stayward_response');
        $culture_source = $this->input->post('culture_source');
        $organism = $this->input->post('organism');
        $precautions = $this->input->post('precautions');
        $date_of_start_abx = $this->input->post('date_of_start_abx');
        $date_of_start_abx1 = $this->input->post('date_of_start_abx1');
        $date_of_start_abx2 = $this->input->post('date_of_start_abx2');
        $date_of_start_abx3 = $this->input->post('date_of_start_abx3');
        $date1 = (!empty($this->input->post('date1'))) ? date('Y-m-d',strtotime($this->input->post('date1'))) : '';
        $date2 = (!empty($this->input->post('date2'))) ? date('Y-m-d',strtotime($this->input->post('date2'))) : '';

        $option = array(
            'table' => 'patient P',
            'select' => 'DISTINCT(PC.initial_rx) as rx_id,sum(PC.initial_dot) as total,P.care_unit_id,IRX.name,CI.name as care_name',
            'join' => array(array('care_unit CI', 'CI.id=P.care_unit_id', 'inner'),
                array('patient_consult PC', 'PC.patient_id=P.id', 'inner'),
                array('initial_rx IRX', 'IRX.id=PC.initial_rx', 'inner')),
            'group_by' => array('PC.initial_rx'),
            'order' => array('IRX.name' => 'ASC')
        );

        if(!empty($date_of_start_abx) && !empty($date_of_start_abx1)){
            $option['where']['month(P.date_of_start_abx)'] = $date_of_start_abx;
            $option['where']['year(P.date_of_start_abx)'] = $date_of_start_abx1;
        }

        if(!empty($date_of_start_abx2) && !empty($date_of_start_abx3)){
            $option['where']['QUARTER(P.date_of_start_abx)'] = $date_of_start_abx2;
            $option['where']['year(P.date_of_start_abx)'] = $date_of_start_abx3;
        }else if($date_of_start_abx2 == '2021'){
            $option['where']['Year(P.date_of_start_abx)'] = $date_of_start_abx2;
        }else if($date_of_start_abx3 == '2021'){
            $option['where']['Year(P.date_of_start_abx)'] = $date_of_start_abx3;
        }else if($date_of_start_abx2 == '2022'){
            $option['where']['Year(P.date_of_start_abx)'] = $date_of_start_abx2;
        }else if($date_of_start_abx3 == '2022'){
            $option['where']['Year(P.date_of_start_abx)'] = $date_of_start_abx3;
        }else if($date_of_start_abx2 == '2023'){
            $option['where']['Year(P.date_of_start_abx)'] = $date_of_start_abx2;
        }else if($date_of_start_abx3 == '2023'){
            $option['where']['Year(P.date_of_start_abx)'] = $date_of_start_abx3;
        }


        if (!empty($date1) && !empty($date2)) {
          $option['where']['date(P.date_of_start_abx) >='] = $date1;
          $option['where']['date(P.date_of_start_abx) <='] = $date2;
        }
        if (!empty($careUnit)) {
            $option['where']['P.care_unit_id'] = $careUnit;
        }
        if (!empty($rx)) {
            $option['where']['IRX.id'] = $rx;
        }
        if (!empty($provider)) {
            $option['where']['P.doctor_id'] = $provider;
        }
        if (!empty($steward)) {
            $option['where']['P.md_steward_id'] = $steward;
        }

        if(!empty($symptom_onset)){
            $option['where']['P.symptom_onset'] = $symptom_onset;
        }
        if(!empty($md_stayward_response)){
            $option['where']['P.md_stayward_response'] = $md_stayward_response;
        }
        if(!empty($culture_source)){
            $option['where']['P.culture_source'] = $culture_source;
        }
        if(!empty($organism)){
            $option['where']['P.organism'] = $organism;
        }
        if(!empty($precautions)){
            $option['where']['P.precautions'] = $precautions;
        }
        if(!empty($criteria_met=='Yes')){
            $option['where']['P.criteria_met'] = $criteria_met;
        }
        else if(!empty($criteria_met=='No')){
            $option['where']['P.criteria_met'] = $criteria_met;
        }
        else if(!empty($criteria_met=='N/A')){
            $option['where']['P.infection_surveillance_checklist'] = $criteria_met;
        }
         
        $list = $this->common_model->customGet($option);

        if (!empty($rx)) {
                   $option = array(
                    'table' => 'patient P',
                    'select' => 'DISTINCT(PC.initial_rx) as rx_id,sum(PC.initial_dot) as total,P.care_unit_id,IRX.name,CI.name as care_name',
                    'join' => array(array('care_unit CI', 'CI.id=P.care_unit_id', 'inner'),
                        array('patient_consult PC', 'PC.patient_id=P.id', 'inner'),
                        array('initial_rx IRX', 'IRX.id=PC.initial_rx', 'inner')),
                    'group_by' => array('PC.initial_rx')
                );

                if(!empty($date_of_start_abx) && !empty($date_of_start_abx1)){
                    $option['where']['month(P.date_of_start_abx)'] = $date_of_start_abx;
                    $option['where']['year(P.date_of_start_abx)'] = $date_of_start_abx1;
                }

                if(!empty($date_of_start_abx2) && !empty($date_of_start_abx3)){
                    $option['where']['QUARTER(P.date_of_start_abx)'] = $date_of_start_abx2;
                    $option['where']['year(P.date_of_start_abx)'] = $date_of_start_abx3;
                }else if($date_of_start_abx2 == '2021'){
                    $option['where']['Year(P.date_of_start_abx)'] = $date_of_start_abx2;
                }else if($date_of_start_abx3 == '2021'){
                    $option['where']['Year(P.date_of_start_abx)'] = $date_of_start_abx3;
                }else if($date_of_start_abx2 == '2022'){
                    $option['where']['Year(P.date_of_start_abx)'] = $date_of_start_abx2;
                }else if($date_of_start_abx3 == '2022'){
                    $option['where']['Year(P.date_of_start_abx)'] = $date_of_start_abx3;
                }else if($date_of_start_abx2 == '2023'){
                    $option['where']['Year(P.date_of_start_abx)'] = $date_of_start_abx2;
                }else if($date_of_start_abx3 == '2023'){
                    $option['where']['Year(P.date_of_start_abx)'] = $date_of_start_abx3;
                }


                if (!empty($date1) && !empty($date2)) {
                  $option['where']['date(P.date_of_start_abx) >='] = $date1;
                  $option['where']['date(P.date_of_start_abx) <='] = $date2;
                }
                if (!empty($careUnit)) {
                    $option['where']['P.care_unit_id'] = $careUnit;
                }
                if (!empty($provider)) {
                    $option['where']['P.doctor_id'] = $provider;
                }
                if (!empty($steward)) {
                    $option['where']['P.md_steward_id'] = $steward;
                }
                if(!empty($symptom_onset)){
                    $option['where']['P.symptom_onset'] = $symptom_onset;
                }
                if(!empty($md_stayward_response)){
                    $option['where']['P.md_stayward_response'] = $md_stayward_response;
                }
                if(!empty($culture_source)){
                    $option['where']['P.culture_source'] = $culture_source;
                }
                if(!empty($organism)){
                    $option['where']['P.organism'] = $organism;
                }
                if(!empty($precautions)){
                    $option['where']['P.precautions'] = $precautions;
                }
                if(!empty($criteria_met=='Yes')){
                    $option['where']['P.criteria_met'] = $criteria_met;
                }
                else if(!empty($criteria_met=='No')){
                    $option['where']['P.criteria_met'] = $criteria_met;
                }
                else if(!empty($criteria_met=='N/A')){
                    $option['where']['P.infection_surveillance_checklist'] = $criteria_met;
                }
                 
                $listRx = $this->common_model->customGet($option); 
        }else{
            $listRx = array();
        }
        if (!empty($list)) {
             if (!empty($rx)) {
                $total_antibiotic_used = array_sum(array_column($listRx, 'total'));
             }else{
               $total_antibiotic_used = array_sum(array_column($list, 'total')); 
             }
            
            $total_antibiotic = count($list);
            foreach ($list as $rows) {
                $days = round(($rows->total / $total_antibiotic_used) * 200);
              //  $Temp['total_antibiotic_used'] = $total_antibiotic;
                $Temp['days'] = $rows->total;
                $Temp['total_used'] = $rows->total;
                $Temp['name'] = $rows->name;
                $Temp['rx_id'] = $rows->rx_id;
                $Temp['backgroundColor'] = '#' . str_pad(dechex(mt_rand(0, 0xFFFFFF)), 6, '0', STR_PAD_LEFT);
                $antibiotic[] = $Temp;
            }
            $return['antibiotic'] = $antibiotic;
            $return['care_name'] = (!empty($careUnit)) ? $list[0]->care_name : "All";
        } else {
            $return['status'] = 400;
        }
        //}
        echo json_encode($return);
    }

    public function get_antibiotic_by_care_unit_facility_days_average() {
        
        $return = array();
        $return['status'] = 200;
        $antibiotic = array();
        $return['careUnit'] = $careUnit = $this->input->post('careUnit');
        $return['steward'] = $steward = $this->input->post('steward');
        $provider = $this->input->post('provider');
        $rx = $this->input->post('rx');
        $symptom_onset = $this->input->post('symptom_onset');
        $criteria_met = $this->input->post('criteria_met');
        $md_stayward_response = $this->input->post('md_stayward_response');
        $culture_source = $this->input->post('culture_source');
        $organism = $this->input->post('organism');
        $precautions = $this->input->post('precautions');
        //if(!empty($careUnit)){
        $date_of_start_abx2 = $this->input->post('date_of_start_abx2');
        $date_of_start_abx3 = $this->input->post('date_of_start_abx3');
        $date1 = (!empty($this->input->post('date1'))) ? date('Y-m-d',strtotime($this->input->post('date1'))) : '';
        $date2 = (!empty($this->input->post('date2'))) ? date('Y-m-d',strtotime($this->input->post('date2'))) : '';

        $option = array(
            'table' => 'patient P',
            'select' => 'DISTINCT(PC.initial_rx) as rx_id,sum(PC.initial_dot) as total,P.care_unit_id,IRX.name,CI.name as care_name',
            'join' => array(array('care_unit CI', 'CI.id=P.care_unit_id', 'inner'),
                array('patient_consult PC', 'PC.patient_id=P.id', 'inner'),
                array('initial_rx IRX', 'IRX.id=PC.initial_rx', 'inner')),
            'group_by' => array('PC.initial_rx'),
            'order' => array('IRX.name' => 'ASC')
        );
        if (!empty($date1) && !empty($date2)) {
          $option['where']['date(P.date_of_start_abx) >='] = $date1;
          $option['where']['date(P.date_of_start_abx) <='] = $date2;
        }

        if(!empty($date_of_start_abx2) && !empty($date_of_start_abx3)){
            $option['where']['QUARTER(P.date_of_start_abx)'] = $date_of_start_abx2;
            $option['where']['year(P.date_of_start_abx)'] = $date_of_start_abx3;
        }else if($date_of_start_abx2 == '2021'){
            $option['where']['Year(P.date_of_start_abx)'] = $date_of_start_abx2;
        }else if($date_of_start_abx3 == '2021'){
            $option['where']['Year(P.date_of_start_abx)'] = $date_of_start_abx3;
        }else if($date_of_start_abx2 == '2022'){
            $option['where']['Year(P.date_of_start_abx)'] = $date_of_start_abx2;
        }else if($date_of_start_abx3 == '2022'){
            $option['where']['Year(P.date_of_start_abx)'] = $date_of_start_abx3;
        }else if($date_of_start_abx2 == '2023'){
            $option['where']['Year(P.date_of_start_abx)'] = $date_of_start_abx2;
        }else if($date_of_start_abx3 == '2023'){
            $option['where']['Year(P.date_of_start_abx)'] = $date_of_start_abx3;
        }

        if(!empty($symptom_onset)){
            $option['where']['P.symptom_onset'] = $symptom_onset;
        }
       /*  if (!empty($careUnit)) {
            $option['where']['P.care_unit_id'] = $careUnit;
        }
        if (!empty($rx)) {
            $option['where']['IRX.id'] = $rx;
        }
        if (!empty($provider)) {
            $option['where']['P.doctor_id'] = $provider;
        }
        if (!empty($steward)) {
            $option['where']['P.md_steward_id'] = $steward;
        }

        if(!empty($symptom_onset)){
            $option['where']['P.symptom_onset'] = $symptom_onset;
        }
        if(!empty($md_stayward_response)){
            $option['where']['P.md_stayward_response'] = $md_stayward_response;
        }
        if(!empty($culture_source)){
            $option['where']['P.culture_source'] = $culture_source;
        }
        if(!empty($organism)){
            $option['where']['P.organism'] = $organism;
        }
        if(!empty($precautions)){
            $option['where']['P.precautions'] = $precautions;
        }
        if(!empty($criteria_met=='Yes')){
            $option['where']['P.criteria_met'] = $criteria_met;
        }
        else if(!empty($criteria_met=='No')){
            $option['where']['P.criteria_met'] = $criteria_met;
        }
        else if(!empty($criteria_met=='N/A')){
            $option['where']['P.infection_surveillance_checklist'] = $criteria_met;
        } */
         


          $sql = array(
            'table' => 'patient P',
            'select' => 'DISTINCT(PC.initial_rx) as rx_id,sum(PC.initial_dot) as total,P.care_unit_id,IRX.name,CI.name as care_name',
            'join' => array(array('care_unit CI', 'CI.id=P.care_unit_id', 'inner'),
                array('patient_consult PC', 'PC.patient_id=P.id', 'inner'),
                array('initial_rx IRX', 'IRX.id=PC.initial_rx', 'inner')),
            'group_by' => array('PC.initial_rx')
        );
        if (!empty($date1) && !empty($date2)) {
          $sql['where']['date(P.date_of_start_abx) >='] = $date1;
          $sql['where']['date(P.date_of_start_abx) <='] = $date2;
        }

        if(!empty($date_of_start_abx2) && !empty($date_of_start_abx3)){
            $sql['where']['QUARTER(P.date_of_start_abx)'] = $date_of_start_abx2;
            $sql['where']['year(P.date_of_start_abx)'] = $date_of_start_abx3;
        }else if($date_of_start_abx2 == '2021'){
            $sql['where']['Year(P.date_of_start_abx)'] = $date_of_start_abx2;
        }else if($date_of_start_abx3 == '2021'){
            $sql['where']['Year(P.date_of_start_abx)'] = $date_of_start_abx3;
        }else if($date_of_start_abx2 == '2022'){
            $sql['where']['Year(P.date_of_start_abx)'] = $date_of_start_abx2;
        }else if($date_of_start_abx3 == '2022'){
            $sql['where']['Year(P.date_of_start_abx)'] = $date_of_start_abx3;
        }else if($date_of_start_abx2 == '2023'){
            $sql['where']['Year(P.date_of_start_abx)'] = $date_of_start_abx2;
        }else if($date_of_start_abx3 == '2023'){
            $sql['where']['Year(P.date_of_start_abx)'] = $date_of_start_abx3;
        }

        if(!empty($symptom_onset)){
            $sql['where']['P.symptom_onset'] = $symptom_onset;
        }
        
        $steward = $this->common_model->customGet($option);
        $total_provider = count($steward);
        
        $list = $this->common_model->customGet($sql);

        //  if (!empty($list)) {
            $total_antibiotic_days = array_sum(array_column($list, 'total'));
           
           $total_provider = count($steward);
           
          
                $percentage = round(($total_antibiotic_days)/($total_provider ?: 1),2);
               // print_r($total_antibiotic_days);die;
            $Temps['avg_dayss1'] = $avg_days[] = $percentage;
           
          
            $Temps['name'] = "Average Days";

            $optionsGraphs[] = $Temps;







        $list = $this->common_model->customGet($option);

        if (!empty($rx)) {
                   $option = array(
                    'table' => 'patient P',
                    'select' => 'DISTINCT(PC.initial_rx) as rx_id,sum(PC.initial_dot) as total,P.care_unit_id,IRX.name,CI.name as care_name',
                    'join' => array(array('care_unit CI', 'CI.id=P.care_unit_id', 'inner'),
                        array('patient_consult PC', 'PC.patient_id=P.id', 'inner'),
                        array('initial_rx IRX', 'IRX.id=PC.initial_rx', 'inner')),
                    'group_by' => array('PC.initial_rx')
                );
                if (!empty($date1) && !empty($date2)) {
                  $option['where']['date(P.date_of_start_abx) >='] = $date1;
                  $option['where']['date(P.date_of_start_abx) <='] = $date2;
                }

                if(!empty($date_of_start_abx2) && !empty($date_of_start_abx3)){
                    $option['where']['QUARTER(P.date_of_start_abx)'] = $date_of_start_abx2;
                    $option['where']['year(P.date_of_start_abx)'] = $date_of_start_abx3;
                }else if($date_of_start_abx2 == '2021'){
                    $option['where']['Year(P.date_of_start_abx)'] = $date_of_start_abx2;
                }else if($date_of_start_abx3 == '2021'){
                    $option['where']['Year(P.date_of_start_abx)'] = $date_of_start_abx3;
                }else if($date_of_start_abx2 == '2022'){
                    $option['where']['Year(P.date_of_start_abx)'] = $date_of_start_abx2;
                }else if($date_of_start_abx3 == '2022'){
                    $option['where']['Year(P.date_of_start_abx)'] = $date_of_start_abx3;
                }else if($date_of_start_abx2 == '2023'){
                    $option['where']['Year(P.date_of_start_abx)'] = $date_of_start_abx2;
                }else if($date_of_start_abx3 == '2023'){
                    $option['where']['Year(P.date_of_start_abx)'] = $date_of_start_abx3;
                }

                if (!empty($careUnit)) {
                    $option['where']['P.care_unit_id'] = $careUnit;
                }
                if (!empty($provider)) {
                    $option['where']['P.doctor_id'] = $provider;
                }
                if (!empty($steward)) {
                    $option['where']['P.md_steward_id'] = $steward;
                }
                if(!empty($symptom_onset)){
                    $option['where']['P.symptom_onset'] = $symptom_onset;
                }
                if(!empty($md_stayward_response)){
                    $option['where']['P.md_stayward_response'] = $md_stayward_response;
                }
                if(!empty($culture_source)){
                    $option['where']['P.culture_source'] = $culture_source;
                }
                if(!empty($organism)){
                    $option['where']['P.organism'] = $organism;
                }
                if(!empty($precautions)){
                    $option['where']['P.precautions'] = $precautions;
                }
                if(!empty($criteria_met=='Yes')){
                    $option['where']['P.criteria_met'] = $criteria_met;
                }
                else if(!empty($criteria_met=='No')){
                    $option['where']['P.criteria_met'] = $criteria_met;
                }
                else if(!empty($criteria_met=='N/A')){
                    $option['where']['P.infection_surveillance_checklist'] = $criteria_met;
                }
                 
                $listRx = $this->common_model->customGet($option); 
        }else{
            $listRx = array();
        }
        if (!empty($list)) {
             if (!empty($rx)) {
                $total_antibiotic_used = array_sum(array_column($listRx, 'total'));
             }else{
               $total_antibiotic_used = array_sum(array_column($list, 'total')); 
             }
            //print($total_antibiotic_used);die;
            $total_antibiotic = count($list);
            //print($total_antibiotic);die;
            foreach ($list as $rows) {
                $days = round(($rows->total / $total_antibiotic_used) * 200);
              //  $Temp['total_antibiotic_used'] = $total_antibiotic;
                $Temp['days'] = $rows->total;
                $Temp['total_used'] = $rows->total;
                $Temp['name'] = $rows->name;
                $Temp['rx_id'] = $rows->rx_id;
                $Temp['backgroundColor'] = '#' . str_pad(dechex(mt_rand(0, 0xFFFFFF)), 6, '0', STR_PAD_LEFT);
                $antibiotic[] = $Temp;
            }
            $return['antibiotic'] = $optionsGraphs;
            $return['care_name'] = (!empty($careUnit)) ? $list[0]->care_name : "All";
        } else {
            $return['status'] = 400;
        }
        //}
        echo json_encode($return);
    }



    public function get_provider_steward_agreement_days() {
        
        $return = array();
        $return['status'] = 200;
        $antibiotic = array();
        $return['careUnit'] = $careUnit = $this->input->post('careUnit');
        $return['steward'] = $steward = $this->input->post('steward');
        $provider = $this->input->post('provider');
        $rx = $this->input->post('rx');
        $symptom_onset = $this->input->post('symptom_onset');
        $criteria_met = $this->input->post('criteria_met');
        $md_stayward_response = $this->input->post('md_stayward_response');
        $culture_source = $this->input->post('culture_source');
        $organism = $this->input->post('organism');
        $precautions = $this->input->post('precautions');
        //if(!empty($careUnit)){
        $date_of_start_abx2 = $this->input->post('date_of_start_abx2');
        $date_of_start_abx3 = $this->input->post('date_of_start_abx3');
        $date1 = (!empty($this->input->post('date1'))) ? date('Y-m-d',strtotime($this->input->post('date1'))) : '';
        $date2 = (!empty($this->input->post('date2'))) ? date('Y-m-d',strtotime($this->input->post('date2'))) : '';

        $option = array(
            'table' => 'patient P',
            'select' => '(P.psa) as total,P.doctor_id,DX.name,P.care_unit_id,CI.name as care_name',
            'join' => array(array('care_unit CI', 'CI.id=P.care_unit_id', 'inner'),
                array('patient_consult PC', 'PC.patient_id=P.id', 'inner'),
                array('initial_rx IRX', 'IRX.id=PC.initial_rx', 'inner'),
                array('doctors DX', 'DX.id=P.doctor_id', 'inner'),
                array('users U', 'U.id=P.md_steward_id', 'inner')),
            'group_by' => array('P.doctor_id'),
            'order' => array('DX.name' => 'ASC')
        );
        if (!empty($date1) && !empty($date2)) {
          $option['where']['date(P.date_of_start_abx) >='] = $date1;
          $option['where']['date(P.date_of_start_abx) <='] = $date2;
        }

        if(!empty($date_of_start_abx2) && !empty($date_of_start_abx3)){
            $option['where']['QUARTER(P.date_of_start_abx)'] = $date_of_start_abx2;
            $option['where']['year(P.date_of_start_abx)'] = $date_of_start_abx3;
        }else if($date_of_start_abx2 == '2021'){
            $option['where']['Year(P.date_of_start_abx)'] = $date_of_start_abx2;
        }else if($date_of_start_abx3 == '2021'){
            $option['where']['Year(P.date_of_start_abx)'] = $date_of_start_abx3;
        }else if($date_of_start_abx2 == '2022'){
            $option['where']['Year(P.date_of_start_abx)'] = $date_of_start_abx2;
        }else if($date_of_start_abx3 == '2022'){
            $option['where']['Year(P.date_of_start_abx)'] = $date_of_start_abx3;
        }else if($date_of_start_abx2 == '2023'){
            $option['where']['Year(P.date_of_start_abx)'] = $date_of_start_abx2;
        }else if($date_of_start_abx3 == '2023'){
            $option['where']['Year(P.date_of_start_abx)'] = $date_of_start_abx3;
        }

        if (!empty($careUnit)) {
            $option['where']['P.care_unit_id'] = $careUnit;
        }
        if (!empty($rx)) {
            $option['where']['IRX.id'] = $rx;
        }
        if (!empty($provider)) {
            $option['where']['P.doctor_id'] = $provider;
        }
        if (!empty($steward)) {
            $option['where']['P.md_steward_id'] = $steward;
        }

        if(!empty($symptom_onset)){
            $option['where']['P.symptom_onset'] = $symptom_onset;
        }
        if(!empty($md_stayward_response)){
            $option['where']['P.md_stayward_response'] = $md_stayward_response;
        }
        if(!empty($culture_source)){
            $option['where']['P.culture_source'] = $culture_source;
        }
        if(!empty($organism)){
            $option['where']['P.organism'] = $organism;
        }
        if(!empty($precautions)){
            $option['where']['P.precautions'] = $precautions;
        }
        if(!empty($criteria_met=='Yes')){
            $option['where']['P.criteria_met'] = $criteria_met;
        }
        else if(!empty($criteria_met=='No')){
            $option['where']['P.criteria_met'] = $criteria_met;
        }
        else if(!empty($criteria_met=='N/A')){
            $option['where']['P.infection_surveillance_checklist'] = $criteria_met;
        }
         
        $list = $this->common_model->customGet($option);

        if (!empty($rx)) {
                   $option = array(
                    'table' => 'patient P',
                    'select' => '(P.psa) as total,P.doctor_id,DX.name,P.care_unit_id,CI.name as care_name',
                    'join' => array(array('care_unit CI', 'CI.id=P.care_unit_id', 'inner'),
                        array('patient_consult PC', 'PC.patient_id=P.id', 'inner'),
                        array('doctors DX', 'DX.id=P.doctor_id', 'inner'),
                        array('initial_rx IRX', 'IRX.id=PC.initial_rx', 'inner'),
                        array('users U', 'U.id=P.md_steward_id', 'inner')),
                    'group_by' => array('P.doctor_id')
                    
                );
                if (!empty($date1) && !empty($date2)) {
                  $option['where']['date(P.date_of_start_abx) >='] = $date1;
                  $option['where']['date(P.date_of_start_abx) <='] = $date2;
                }

                if(!empty($date_of_start_abx2) && !empty($date_of_start_abx3)){
                    $option['where']['QUARTER(P.date_of_start_abx)'] = $date_of_start_abx2;
                    $option['where']['year(P.date_of_start_abx)'] = $date_of_start_abx3;
                }else if($date_of_start_abx2 == '2021'){
                    $option['where']['Year(P.date_of_start_abx)'] = $date_of_start_abx2;
                }else if($date_of_start_abx3 == '2021'){
                    $option['where']['Year(P.date_of_start_abx)'] = $date_of_start_abx3;
                }else if($date_of_start_abx2 == '2022'){
                    $option['where']['Year(P.date_of_start_abx)'] = $date_of_start_abx2;
                }else if($date_of_start_abx3 == '2022'){
                    $option['where']['Year(P.date_of_start_abx)'] = $date_of_start_abx3;
                }else if($date_of_start_abx2 == '2023'){
                    $option['where']['Year(P.date_of_start_abx)'] = $date_of_start_abx2;
                }else if($date_of_start_abx3 == '2023'){
                    $option['where']['Year(P.date_of_start_abx)'] = $date_of_start_abx3;
                }

                if (!empty($careUnit)) {
                    $option['where']['P.care_unit_id'] = $careUnit;
                }
                if (!empty($provider)) {
                    $option['where']['P.doctor_id'] = $provider;
                }
                if (!empty($steward)) {
                    $option['where']['P.md_steward_id'] = $steward;
                }
                if(!empty($symptom_onset)){
                    $option['where']['P.symptom_onset'] = $symptom_onset;
                }
                if(!empty($md_stayward_response)){
                    $option['where']['P.md_stayward_response'] = $md_stayward_response;
                }
                if(!empty($culture_source)){
                    $option['where']['P.culture_source'] = $culture_source;
                }
                if(!empty($organism)){
                    $option['where']['P.organism'] = $organism;
                }
                if(!empty($precautions)){
                    $option['where']['P.precautions'] = $precautions;
                }
                if(!empty($criteria_met=='Yes')){
                    $option['where']['P.criteria_met'] = $criteria_met;
                }
                else if(!empty($criteria_met=='No')){
                    $option['where']['P.criteria_met'] = $criteria_met;
                }
                else if(!empty($criteria_met=='N/A')){
                    $option['where']['P.infection_surveillance_checklist'] = $criteria_met;
                }
                 
                $listRx = $this->common_model->customGet($option); 
        }else{
            $listRx = array();
        }
        if (!empty($list)) {
             if (!empty($rx)) {
                $total_antibiotic_used = array_sum(array_column($listRx, 'total'));
             }else{
               $total_antibiotic_used = array_sum(array_column($list, 'total')); 
             }
            
            $total_antibiotic = count($list);

            foreach ($list as $rows) {
                $days = round(($rows->total / $total_antibiotic_used) * 200);
              //  $Temp['total_antibiotic_used'] = $total_antibiotic;
            $customAgree = $this->common_model->customAgree($rows->doctor_id);
            $Temp['agree'] = $customAgree->Total;
            $customDisagree = $this->common_model->customDisgree($rows->doctor_id);
            $Temp['disagree'] = $customDisagree->Total;
            $customNoResponse = $this->common_model->customNoResponse($rows->doctor_id);
            $Temp['NoResponse'] = $customNoResponse->Total;
            $customNutral = $this->common_model->customNutral($rows->doctor_id);
            $Temp['Neutral'] = $customNutral;
           // $x="Neutral";
            $Temp['days'] = $rows->total;
            $Temp['total_used'] = $rows->total;
            $Temp['name'] = !empty($rows->name)?$rows->name:0;
           // $Temp['rx_id'] = $rows->rx_id;
          // $Temp['backgroundColor'] = '#' . str_pad(dechex(mt_rand(0, 0xFFFFFF)), 6, '0', STR_PAD_LEFT);
            $antibiotic[] = $Temp;
            }
            $return['antibiotic'] = $antibiotic;
            $return['care_name'] = (!empty($careUnit)) ? $list[0]->care_name : "All";
        } else {
            $return['status'] = 400;
        }
        //}
        
        echo json_encode($return);
        
    }



    /** new **/
    public function get_antibiotic_by_care_unit_facility() {
        
        $return = array();
        $return['status'] = 200;
        $antibiotic = array();
        $return['careUnit'] = $careUnit = $this->input->post('careUnit');
        $return['steward'] = $steward = $this->input->post('steward');
        $provider = $this->input->post('provider');
        $rx = $this->input->post('rx');
        $symptom_onset = $this->input->post('symptom_onset');
        $criteria_met = $this->input->post('criteria_met');
        $md_stayward_response = $this->input->post('md_stayward_response');
        $culture_source = $this->input->post('culture_source');
        $organism = $this->input->post('organism');
        $precautions = $this->input->post('precautions');
        //if(!empty($careUnit)){

        $date_of_start_abx2 = $this->input->post('date_of_start_abx2');
        $date_of_start_abx3 = $this->input->post('date_of_start_abx3');
        $date1 = (!empty($this->input->post('date1'))) ? date('Y-m-d',strtotime($this->input->post('date1'))) : '';
        $date2 = (!empty($this->input->post('date2'))) ? date('Y-m-d',strtotime($this->input->post('date2'))) : '';

        $option = array(
            'table' => 'patient P',
            'select' => 'DISTINCT(PC.initial_rx) as rx_id,sum(PC.initial_dot) as total,P.care_unit_id,IRX.name,CI.name as care_name',
            'join' => array(array('care_unit CI', 'CI.id=P.care_unit_id', 'inner'),
                array('patient_consult PC', 'PC.patient_id=P.id', 'inner'),
                array('initial_rx IRX', 'IRX.id=PC.initial_rx', 'inner')),
            'group_by' => array('PC.initial_rx'),
            'order' => array('IRX.name' => 'ASC')
        );
        if (!empty($date1) && !empty($date2)) {
          $option['where']['date(P.date_of_start_abx) >='] = $date1;
          $option['where']['date(P.date_of_start_abx) <='] = $date2;
        }

        if(!empty($date_of_start_abx2) && !empty($date_of_start_abx3)){
            $option['where']['QUARTER(P.date_of_start_abx)'] = $date_of_start_abx2;
            $option['where']['year(P.date_of_start_abx)'] = $date_of_start_abx3;
        }else if($date_of_start_abx2 == '2021'){
            $option['where']['Year(P.date_of_start_abx)'] = $date_of_start_abx2;
        }else if($date_of_start_abx3 == '2021'){
            $option['where']['Year(P.date_of_start_abx)'] = $date_of_start_abx3;
        }else if($date_of_start_abx2 == '2022'){
            $option['where']['Year(P.date_of_start_abx)'] = $date_of_start_abx2;
        }else if($date_of_start_abx3 == '2022'){
            $option['where']['Year(P.date_of_start_abx)'] = $date_of_start_abx3;
        }else if($date_of_start_abx2 == '2023'){
            $option['where']['Year(P.date_of_start_abx)'] = $date_of_start_abx2;
        }else if($date_of_start_abx3 == '2023'){
            $option['where']['Year(P.date_of_start_abx)'] = $date_of_start_abx3;
        }


        if (!empty($careUnit)) {
            $option['where']['P.care_unit_id'] = $careUnit;
        }
        if (!empty($rx)) {
            $option['where']['IRX.id'] = $rx;
        }
        if (!empty($provider)) {
            $option['where']['P.doctor_id'] = $provider;
        }
        if (!empty($steward)) {
            $option['where']['P.md_steward_id'] = $steward;
        }

        if(!empty($symptom_onset)){
            $option['where']['P.symptom_onset'] = $symptom_onset;
        }
        if(!empty($md_stayward_response)){
            $option['where']['P.md_stayward_response'] = $md_stayward_response;
        }
        if(!empty($culture_source)){
            $option['where']['P.culture_source'] = $culture_source;
        }
        if(!empty($organism)){
            $option['where']['P.organism'] = $organism;
        }
        if(!empty($precautions)){
            $option['where']['P.precautions'] = $precautions;
        }
        if(!empty($criteria_met=='Yes')){
            $option['where']['P.criteria_met'] = $criteria_met;
        }
        else if(!empty($criteria_met=='No')){
            $option['where']['P.criteria_met'] = $criteria_met;
        }
        else if(!empty($criteria_met=='N/A')){
            $option['where']['P.infection_surveillance_checklist'] = $criteria_met;
        }
         
        $list = $this->common_model->customGet($option);

        if (!empty($rx)) {
                   $option = array(
                    'table' => 'patient P',
                    'select' => 'DISTINCT(PC.initial_rx) as rx_id,sum(PC.initial_dot) as total,P.care_unit_id,IRX.name,CI.name as care_name',
                    'join' => array(array('care_unit CI', 'CI.id=P.care_unit_id', 'inner'),
                        array('patient_consult PC', 'PC.patient_id=P.id', 'inner'),
                        array('initial_rx IRX', 'IRX.id=PC.initial_rx', 'inner')),
                    'group_by' => array('PC.initial_rx')
                );
                if (!empty($date1) && !empty($date2)) {
                  $option['where']['date(P.date_of_start_abx) >='] = $date1;
                  $option['where']['date(P.date_of_start_abx) <='] = $date2;
                }

                if(!empty($date_of_start_abx2) && !empty($date_of_start_abx3)){
                    $option['where']['QUARTER(P.date_of_start_abx)'] = $date_of_start_abx2;
                    $option['where']['year(P.date_of_start_abx)'] = $date_of_start_abx3;
                }else if($date_of_start_abx2 == '2021'){
                    $option['where']['Year(P.date_of_start_abx)'] = $date_of_start_abx2;
                }else if($date_of_start_abx3 == '2021'){
                    $option['where']['Year(P.date_of_start_abx)'] = $date_of_start_abx3;
                }else if($date_of_start_abx2 == '2022'){
                    $option['where']['Year(P.date_of_start_abx)'] = $date_of_start_abx2;
                }else if($date_of_start_abx3 == '2022'){
                    $option['where']['Year(P.date_of_start_abx)'] = $date_of_start_abx3;
                }else if($date_of_start_abx2 == '2023'){
                    $option['where']['Year(P.date_of_start_abx)'] = $date_of_start_abx2;
                }else if($date_of_start_abx3 == '2023'){
                    $option['where']['Year(P.date_of_start_abx)'] = $date_of_start_abx3;
                }

                
                if (!empty($careUnit)) {
                    $option['where']['P.care_unit_id'] = $careUnit;
                }
                if (!empty($provider)) {
                    $option['where']['P.doctor_id'] = $provider;
                }
                if (!empty($steward)) {
                    $option['where']['P.md_steward_id'] = $steward;
                }
                if(!empty($symptom_onset)){
                    $option['where']['P.symptom_onset'] = $symptom_onset;
                }
                if(!empty($md_stayward_response)){
                    $option['where']['P.md_stayward_response'] = $md_stayward_response;
                }
                if(!empty($culture_source)){
                    $option['where']['P.culture_source'] = $culture_source;
                }
                if(!empty($organism)){
                    $option['where']['P.organism'] = $organism;
                }
                if(!empty($precautions)){
                    $option['where']['P.precautions'] = $precautions;
                }
                if(!empty($criteria_met=='Yes')){
                    $option['where']['P.criteria_met'] = $criteria_met;
                }
                else if(!empty($criteria_met=='No')){
                    $option['where']['P.criteria_met'] = $criteria_met;
                }
                else if(!empty($criteria_met=='N/A')){
                    $option['where']['P.infection_surveillance_checklist'] = $criteria_met;
                }
                 
                $listRx = $this->common_model->customGet($option); 
        }else{
            $listRx = array();
        }
        if (!empty($list)) {
             if (!empty($rx)) {
                $total_antibiotic_used = array_sum(array_column($listRx, 'total'));
             }else{
               $total_antibiotic_used = array_sum(array_column($list, 'total')); 
             }
            
            $total_antibiotic = count($list);
            foreach ($list as $rows) {
                $percentage = round(($rows->total / $total_antibiotic_used) * 100);
              //  $Temp['total_antibiotic_used'] = $total_antibiotic;
                $Temp['percentage'] = $rows->total;
                $Temp['total_used'] = $rows->total;
                $Temp['name'] = $rows->name;
                $Temp['rx_id'] = $rows->rx_id;
                $Temp['backgroundColor'] = '#' . str_pad(dechex(mt_rand(0, 0xFFFFFF)), 6, '0', STR_PAD_LEFT);
                $antibiotic[] = $Temp;
            }
            $return['antibiotic'] = $antibiotic;
            $return['care_name'] = (!empty($careUnit)) ? $list[0]->care_name : "All";
        } else {
            $return['status'] = 400;
        }
        //}
        echo json_encode($return);
    }

    public function app() {
        $this->data['parent'] = "Dashboard";
        $option = array('table' => USERS . ' as user',
            'select' => 'user.*,group.name as group_name,UP.doc_file',
            'join' => array(array(USER_GROUPS . ' as ugroup', 'ugroup.user_id=user.id', 'left'),
                array(GROUPS . ' as group', 'group.id=ugroup.group_id', 'left'),
                array('user_profile UP', 'UP.user_id=user.id', 'left')),
            'order' => array('user.id' => 'ASC'),
            'where' => array('user.delete_status' => 0,
                'group.id' => 3),
            'order' => array('user.first_name' => 'ASC')
        );
        $this->data['staward'] = $this->common_model->customGet($option);
        $this->data['careUnit'] = $this->common_model->customGet(array('table' => 'care_unit', 'select' => 'id,name', 'where' => array('is_active' => 1, 'delete_status' => 0), 'order' => array('name' => 'ASC')));
        $this->data['initial_dx'] = $this->common_model->customGet(array('table' => 'initial_dx', 'select' => 'id,name', 'where' => array('is_active' => 1, 'delete_status' => 0), 'order' => array('name' => 'ASC')));
        $this->data['initial_rx'] = $this->common_model->customGet(array('table' => 'initial_rx', 'select' => 'id,name', 'where' => array('is_active' => 1, 'delete_status' => 0), 'order' => array('name' => 'ASC')));
        $this->data['doctors'] = $this->common_model->customGet(array('table' => 'doctors', 'select' => 'id,name', 'where' => array('is_active' => 1, 'delete_status' => 0), 'order' => array('name' => 'ASC')));
        $this->data['patients_days'] = $patients_days = $this->common_model->customGet(array('table' => 'patient', 'select' => 'SUM(total_days_of_patient_stay) as tatal_days', 'single' => true));
        $option = array(
            'table' => 'patient P',
            'select' => '(CASE WHEN new_initial_dot IS NOT NULL THEN SUM(new_initial_dot) ELSE SUM(initial_dot) END) totalDays ,'
            . 'P.care_unit_id',
            'join' => array(array('patient_consult PC', 'PC.patient_id=P.id', 'inner')),
            'group_by' => array('P.care_unit_id')
        );
        $list = $this->common_model->customGet($option);
        $this->data['total_antibiotic_days'] = $total_antibiotic_days = array_sum(array_column($list, 'totalDays'));
        $total_days = (getConfig('total_patient_days') <= 0) ? $patients_days->tatal_days : getConfig('total_patient_days');
        $this->data['toatl_patient_days_average'] = ($total_days > 0) ? round(($total_antibiotic_days / $total_days) * 1000) : 0;
        $this->load->view('app', $this->data);
        $this->load->view('inner_script', $this->data);
    }

    function get_antibiotic_by_care_unit() {
        $return = array();
        $return['status'] = 200;
        $antibiotic = array();
        $return['careUnit'] = $careUnit = $this->input->post('careUnit');
        $return['steward'] = $steward = $this->input->post('steward');
        $rx = $this->input->post('rx');
        $symptom_onset = $this->input->post('symptom_onset');
        $criteria_met = $this->input->post('criteria_met');
        $md_stayward_response = $this->input->post('md_stayward_response');
        $culture_source = $this->input->post('culture_source');
        $organism = $this->input->post('organism');
        $precautions = $this->input->post('precautions');
        //if(!empty($careUnit)){


        $option = array(
            'table' => 'patient P',
            'select' => 'DISTINCT(PC.initial_rx) as rx_id,count(PC.initial_rx) as total,P.care_unit_id,IRX.name,CI.name as care_name',
            'join' => array(array('care_unit CI', 'CI.id=P.care_unit_id', 'inner'),
                array('patient_consult PC', 'PC.patient_id=P.id', 'inner'),
                array('initial_rx IRX', 'IRX.id=PC.initial_rx', 'inner')),
            'group_by' => array('PC.initial_rx')
        );
        if (!empty($careUnit)) {
            $option['where']['P.care_unit_id'] = $careUnit;
        }
        if (!empty($rx)) {
            $option['where']['IRX.id'] = $rx;
        }
        if(!empty($symptom_onset)){
            $option['where']['P.symptom_onset'] = $symptom_onset;
        }
        if(!empty($md_stayward_response)){
            $option['where']['P.md_stayward_response'] = $md_stayward_response;
        }
        if(!empty($culture_source)){
            $option['where']['P.culture_source'] = $culture_source;
        }
        if(!empty($organism)){
            $option['where']['P.organism'] = $organism;
        }
        if(!empty($precautions)){
            $option['where']['P.precautions'] = $precautions;
        }
        if(!empty($criteria_met=='Yes')){
            $option['where']['P.criteria_met'] = $criteria_met;
        }
        else if(!empty($criteria_met=='No')){
            $option['where']['P.criteria_met'] = $criteria_met;
        }
        else if(!empty($criteria_met=='N/A')){
            $option['where']['P.infection_surveillance_checklist'] = $criteria_met;
        }
        $list = $this->common_model->customGet($option);
        //dump($list);
        if (!empty($list)) {
            $total_antibiotic_used = array_sum(array_column($list, 'total'));
            $total_antibiotic = count($list);
            foreach ($list as $rows) {
                $percentage = round(($rows->total / $total_antibiotic_used) * 100);
                $Temp['percentage'] = $percentage;
                $Temp['total_used'] = $rows->total;
                $Temp['name'] = $rows->name;
                $Temp['rx_id'] = $rows->rx_id;
                $antibiotic[] = $Temp;
            }
            $return['antibiotic'] = $antibiotic;
            $return['care_name'] = (!empty($careUnit)) ? $list[0]->care_name : "All";
        } else {
            $return['status'] = 400;
        }
        //}
        echo json_encode($return);
    }

    function get_antibiotic_by_care_unit_md_steward() {
        $return = array();
        $return['status'] = 200;
        $antibiotic = array();
        $return['careUnit'] = $careUnit = $this->input->post('careUnit');
        $return['steward'] = $stewardid = $this->input->post('steward');
        $rx = $this->input->post('rx');
        $symptom_onset = $this->input->post('symptom_onset');
        $criteria_met = $this->input->post('criteria_met');
        $md_stayward_response = $this->input->post('md_stayward_response');
        $culture_source = $this->input->post('culture_source');
        $organism = $this->input->post('organism');
        $precautions = $this->input->post('precautions');

        if(empty($stewardid)){
            $stewardid = 61;
        }

        $option = array(
            'table' => 'patient P',
            'select' => 'P.id,P.patient_id,P.md_steward_id,U.first_name,U.last_name,CONCAT(U.first_name," ",U.last_name) as name',
            'join' => array(array('users U', 'U.id=P.md_steward_id', 'inner')),
            'group_by' => array('P.md_steward_id')
        );
        //$option['where']['P.care_unit_id'] = $careUnit;
        if (!empty($careUnit)) {
            $option['where']['P.care_unit_id'] = $careUnit;
        }
        if (!empty($stewardid)) {
            $option['where']['P.md_steward_id'] = $stewardid;
        }
        if(!empty($symptom_onset)){
            $option['where']['P.symptom_onset'] = $symptom_onset;
        }
        if(!empty($md_stayward_response)){
            $option['where']['P.md_stayward_response'] = $md_stayward_response;
        }
        if(!empty($culture_source)){
            $option['where']['P.culture_source'] = $culture_source;
        }
        if(!empty($organism)){
            $option['where']['P.organism'] = $organism;
        }
        if(!empty($precautions)){
            $option['where']['P.precautions'] = $precautions;
        }
        if(!empty($criteria_met=='Yes')){
            $option['where']['P.criteria_met'] = $criteria_met;
        }
        else if(!empty($criteria_met=='No')){
            $option['where']['P.criteria_met'] = $criteria_met;
        }
        else if(!empty($criteria_met=='N/A')){
            $option['where']['P.infection_surveillance_checklist'] = $criteria_met;
        }
        $steward = $this->common_model->customGet($option);
        $optionsGraph = array();
        $mediRx = array();
        if (!empty($steward)) {
            foreach ($steward as $row) {
                $Temp['md_name'] = $row->name;
                $Temp['md_steward_id'] = $row->md_steward_id;
                $option = array(
                    'table' => 'patient P',
                    'select' => 'DISTINCT(PC.initial_rx) as rx_id,count(PC.initial_rx) as total,P.care_unit_id,IRX.name,
                    CI.name as care_name,IRX.description',
                    'join' => array(array('care_unit CI', 'CI.id=P.care_unit_id', 'inner'),
                        array('patient_consult PC', 'PC.patient_id=P.id', 'inner'),
                        array('initial_rx IRX', 'IRX.id=PC.initial_rx', 'inner')),
                    'group_by' => array('PC.initial_rx'),
                    'order' => array('PC.initial_rx' => "ASC")
                );

                if (!empty($careUnit)) {
                    $option['where']['P.care_unit_id'] = $careUnit;
                }
                if (!empty($rx)) {
                    $option['where']['IRX.id'] = $rx;
                }
                $option['where']['P.md_steward_id'] = $row->md_steward_id;
                $list = $this->common_model->customGet($option);
                $Temp['all'] = array();
                if (!empty($list)) {
                    $total_antibiotic_used = array_sum(array_column($list, 'total'));
                    $total_antibiotic = count($list);
                    $color = array();
                    $percentages = array();
                    $labels = array();
                    $multi_labels = array();
                    foreach ($list as $rows) {
                        $percentage = round(($rows->total / $total_antibiotic_used) * 100);
                        $Temp1['percentage'] = $percentage;
                        $Temp1['total_used'] = $rows->total;
                        $Temp1['name'] = $rows->name;
                        $Temp1['rx_id'] = $rows->rx_id;
                        $Temp1['color'] = $rows->description;
                        $color[] = $rows->description;
                        $percentages[] = $percentage;
                        $labels[$rows->rx_id] = $rows->name;
                        $mediRx[$rows->rx_id]['id'] = $rows->rx_id;
                        $mediRx[$rows->rx_id]['name'] = $rows->name;
                        $mediRx[$rows->rx_id]['color'] = $rows->description;
                        $multi_labels[$rows->rx_id] = array('rx_id' => $rows->rx_id, 'Name' => $rows->name, 'percentage' => $percentage, 'color' => $rows->description, 'md_id' => $row->md_steward_id);
                    }
                    $Temp['all'] = $multi_labels;
                }

                $optionsGraph[] = $Temp;
            }
        }
        sort($mediRx);
        $Temp12 = array();
        foreach ($optionsGraph as $graph) {
            $all = $graph['all'];
            $all_only_id = array_column($all, "rx_id");
            $md_id = $graph['md_steward_id'];
            foreach ($mediRx as $rx) {
                $Temp12[$rx['id']]['label'] = $rx['name'];
                $Temp12[$rx['id']]['backgroundColor'] = $rx['color'];
                if (in_array($rx['id'], $all_only_id)) {
                    if (isset($all[$rx['id']]) && $all[$rx['id']]['md_id'] == $md_id) {
                        if (!empty($all[$rx['id']])) {
                            $Temp12[$rx['id']]['data'][] = $all[$rx['id']]['percentage'];
                        }
                    }
                } else {
                    $Temp12[$rx['id']]['data'][] = 0;
                }
            }
        }
        $Temp13 = array();
        foreach ($Temp12 as $rows) {
            $Temp13[] = $rows;
        }
        $return['antibiotic'] = $optionsGraph;
        $return['datasheet'] = $Temp13;
        foreach($Temp13 as $Row){
         $return['labels'][] = $Row['label'];
         $return['backgroundColor'][] = $Row['backgroundColor'];
         $return['data'][] = $Row['data'][0];
        }
        $return['md_name'] =  $optionsGraph[0]['md_name'];
        $return['care_name'] = (!empty($careUnit) && !empty($list)) ? $list[0]->care_name : "All";

        echo json_encode($return);
    }

    function get_antibiotic_by_care_unit_md_steward_old_barcharts() {
        $return = array();
        $return['status'] = 200;
        $antibiotic = array();
        $return['careUnit'] = $careUnit = $this->input->post('careUnit');
        $return['steward'] = $stewardid = $this->input->post('steward');
        $rx = $this->input->post('rx');
        $symptom_onset = $this->input->post('symptom_onset');
        $criteria_met = $this->input->post('criteria_met');
        $md_stayward_response = $this->input->post('md_stayward_response');
        $culture_source = $this->input->post('culture_source');
        $organism = $this->input->post('organism');
        $precautions = $this->input->post('precautions');

        $option = array(
            'table' => 'patient P',
            'select' => 'P.id,P.patient_id,P.md_steward_id,U.first_name,U.last_name,CONCAT(U.first_name," ",U.last_name) as name',
            'join' => array(array('users U', 'U.id=P.md_steward_id', 'inner')),
            'group_by' => array('P.md_steward_id')
        );
        //$option['where']['P.care_unit_id'] = $careUnit;
        if (!empty($careUnit)) {
            $option['where']['P.care_unit_id'] = $careUnit;
        }
        if (!empty($stewardid)) {
            $option['where']['P.md_steward_id'] = $stewardid;
        }
        if(!empty($symptom_onset)){
            $option['where']['P.symptom_onset'] = $symptom_onset;
        }
        if(!empty($md_stayward_response)){
            $option['where']['P.md_stayward_response'] = $md_stayward_response;
        }
        if(!empty($culture_source)){
            $option['where']['P.culture_source'] = $culture_source;
        }
        if(!empty($organism)){
            $option['where']['P.organism'] = $organism;
        }
        if(!empty($precautions)){
            $option['where']['P.precautions'] = $precautions;
        }
        if(!empty($criteria_met=='Yes')){
            $option['where']['P.criteria_met'] = $criteria_met;
        }
        else if(!empty($criteria_met=='No')){
            $option['where']['P.criteria_met'] = $criteria_met;
        }
        else if(!empty($criteria_met=='N/A')){
            $option['where']['P.infection_surveillance_checklist'] = $criteria_met;
        }
        $steward = $this->common_model->customGet($option);
        $optionsGraph = array();
        $mediRx = array();
        if (!empty($steward)) {
            foreach ($steward as $row) {
                $Temp['md_name'] = $row->name;
                $Temp['md_steward_id'] = $row->md_steward_id;
                $option = array(
                    'table' => 'patient P',
                    'select' => 'DISTINCT(PC.initial_rx) as rx_id,count(PC.initial_rx) as total,P.care_unit_id,IRX.name,
                    CI.name as care_name,IRX.description',
                    'join' => array(array('care_unit CI', 'CI.id=P.care_unit_id', 'inner'),
                        array('patient_consult PC', 'PC.patient_id=P.id', 'inner'),
                        array('initial_rx IRX', 'IRX.id=PC.initial_rx', 'inner')),
                    'group_by' => array('PC.initial_rx'),
                    'order' => array('PC.initial_rx' => "ASC")
                );

                if (!empty($careUnit)) {
                    $option['where']['P.care_unit_id'] = $careUnit;
                }
                if (!empty($rx)) {
                    $option['where']['IRX.id'] = $rx;
                }
                $option['where']['P.md_steward_id'] = $row->md_steward_id;
                $list = $this->common_model->customGet($option);
                $Temp['all'] = array();
                if (!empty($list)) {
                    $total_antibiotic_used = array_sum(array_column($list, 'total'));
                    $total_antibiotic = count($list);
                    $color = array();
                    $percentages = array();
                    $labels = array();
                    $multi_labels = array();
                    foreach ($list as $rows) {
                        $percentage = round(($rows->total / $total_antibiotic_used) * 100);
                        $Temp1['percentage'] = $percentage;
                        $Temp1['total_used'] = $rows->total;
                        $Temp1['name'] = $rows->name;
                        $Temp1['rx_id'] = $rows->rx_id;
                        $Temp1['color'] = $rows->description;
                        $color[] = $rows->description;
                        $percentages[] = $percentage;
                        $labels[$rows->rx_id] = $rows->name;
                        $mediRx[$rows->rx_id]['id'] = $rows->rx_id;
                        $mediRx[$rows->rx_id]['name'] = $rows->name;
                        $mediRx[$rows->rx_id]['color'] = $rows->description;
                        $multi_labels[$rows->rx_id] = array('rx_id' => $rows->rx_id, 'Name' => $rows->name, 'percentage' => $percentage, 'color' => $rows->description, 'md_id' => $row->md_steward_id);
                    }
                    $Temp['all'] = $multi_labels;
                }

                $optionsGraph[] = $Temp;
            }
        }
        sort($mediRx);
        $Temp12 = array();
        foreach ($optionsGraph as $graph) {
            $all = $graph['all'];
            $all_only_id = array_column($all, "rx_id");
            $md_id = $graph['md_steward_id'];
            foreach ($mediRx as $rx) {
                $Temp12[$rx['id']]['label'] = $rx['name'];
                $Temp12[$rx['id']]['backgroundColor'] = $rx['color'];
                if (in_array($rx['id'], $all_only_id)) {
                    if (isset($all[$rx['id']]) && $all[$rx['id']]['md_id'] == $md_id) {
                        if (!empty($all[$rx['id']])) {
                            $Temp12[$rx['id']]['data'][] = $all[$rx['id']]['percentage'];
                        }
                    }
                } else {
                    $Temp12[$rx['id']]['data'][] = 0;
                }
            }
        }
        $Temp13 = array();
        foreach ($Temp12 as $rows) {
            $Temp13[] = $rows;
        }
        $return['antibiotic'] = $optionsGraph;
        $return['datasheet'] = $Temp13;
        $return['care_name'] = (!empty($careUnit) && !empty($list)) ? $list[0]->care_name : "All";

        echo json_encode($return);
    }



    function get_antibiotic_by_care_unit_provider_id() {
        $return = array();
        $return['status'] = 200;
        $antibiotic = array();
        $return['careUnit'] = $careUnit = $this->input->post('careUnit');
        $return['provider'] = $provider = (!empty($this->input->post('provider'))) ? $this->input->post('provider') : 5;
        $rx = $this->input->post('rx');
        $symptom_onset = $this->input->post('symptom_onset');
        $criteria_met = $this->input->post('criteria_met');
        $md_stayward_response = $this->input->post('md_stayward_response');
        $culture_source = $this->input->post('culture_source');
        $organism = $this->input->post('organism');
        $precautions = $this->input->post('precautions');


        //$careUnit = 8;
        $option = array(
            'table' => 'patient P',
            'select' => 'P.id,P.patient_id,P.doctor_id,U.name',
            'join' => array(array('doctors U', 'U.id=P.doctor_id', 'inner')),
            'group_by' => array('P.doctor_id')
        );
        $option['where']['P.doctor_id'] = $provider;
        // if (!empty($careUnit)) {
        //     $option['where']['P.care_unit_id'] = $careUnit;
        // }
        $steward = $this->common_model->customGet($option);
        $optionsGraph = array();
        $mediRx = array();
        if (!empty($steward)) {
            foreach ($steward as $row) {
                $Temp['md_name'] = $row->name;
                $Temp['doctor_id'] = $row->doctor_id;
                $option = array(
                    'table' => 'patient P',
                    'select' => 'DISTINCT(PC.initial_rx) as rx_id,count(PC.initial_rx) as total,SUM(PC.initial_dot) as totalDays,P.care_unit_id,IRX.name,
                    CI.name as care_name,IRX.description',
                    'join' => array(array('care_unit CI', 'CI.id=P.care_unit_id', 'inner'),
                        array('patient_consult PC', 'PC.patient_id=P.id', 'inner'),
                        array('initial_rx IRX', 'IRX.id=PC.initial_rx', 'inner')),
                    'group_by' => array('PC.initial_rx'),
                    'order' => array('PC.initial_rx' => "ASC")
                );

                // if (!empty($careUnit)) {
                //     $option['where']['P.care_unit_id'] = $careUnit;
                // }
                if (!empty($rx)) {
                    $option['where']['IRX.id'] = $rx;
                }
                if(!empty($symptom_onset)){
                    $option['where']['P.symptom_onset'] = $symptom_onset;
                }
                if(!empty($md_stayward_response)){
                    $option['where']['P.md_stayward_response'] = $md_stayward_response;
                }
                if(!empty($culture_source)){
                    $option['where']['P.culture_source'] = $culture_source;
                }
                if(!empty($organism)){
                    $option['where']['P.organism'] = $organism;
                }
                if(!empty($precautions)){
                    $option['where']['P.precautions'] = $precautions;
                }
                if(!empty($criteria_met=='Yes')){
                    $option['where']['P.criteria_met'] = $criteria_met;
                }
                else if(!empty($criteria_met=='No')){
                    $option['where']['P.criteria_met'] = $criteria_met;
                }
                else if(!empty($criteria_met=='N/A')){
                    $option['where']['P.infection_surveillance_checklist'] = $criteria_met;
                }
                $option['where']['P.doctor_id'] = $row->doctor_id;
                $list = $this->common_model->customGet($option);

                $Temp['all'] = array();
                if (!empty($list)) {
                    $total_antibiotic_used = array_sum(array_column($list, 'total'));
                    $total_antibiotic = count($list);
                    $color = array();
                    $percentages = array();
                    $labels = array();
                    $multi_labels = array();
                    foreach ($list as $rows) {
                        $percentage = round(($rows->totalDays / $total_antibiotic_used) * 100);
                        $Temp1['percentage'] = $rows->totalDays;
                        $Temp1['total_used'] = $rows->totalDays;
                        $Temp1['name'] = $rows->name;
                        $Temp1['rx_id'] = $rows->rx_id;
                        $Temp1['color'] = $rows->description;
                        $color[] = $rows->description;
                        $percentages[] = $percentage;
                        $labels[$rows->rx_id] = $rows->name;
                        $mediRx[$rows->rx_id]['id'] = $rows->rx_id;
                        $mediRx[$rows->rx_id]['name'] = $rows->name;
                        $mediRx[$rows->rx_id]['color'] = $rows->description;
                        $multi_labels[$rows->rx_id] = array('rx_id' => $rows->rx_id, 'Name' => $rows->name, 'percentage' => $rows->totalDays, 'color' => $rows->description, 'md_id' => $row->doctor_id);
                    }
                    $Temp['all'] = $multi_labels;
                }

                $optionsGraph[] = $Temp;
            }
        }
        //dump($optionsGraph);
        sort($mediRx);
        $Temp12 = array();
        foreach ($optionsGraph as $graph) {
            $all = $graph['all'];
            $all_only_id = array_column($all, "rx_id");
            $md_id = $graph['doctor_id'];
            foreach ($mediRx as $rx) {
                $Temp12[$rx['id']]['label'] = $rx['name'];
                $Temp12[$rx['id']]['backgroundColor'] = $rx['color'];
                if (in_array($rx['id'], $all_only_id)) {
                    if (isset($all[$rx['id']]) && $all[$rx['id']]['md_id'] == $md_id) {
                        if (!empty($all[$rx['id']])) {
                            $Temp12[$rx['id']]['data'][] = $all[$rx['id']]['percentage'];
                        }
                    }
                } else {
                    $Temp12[$rx['id']]['data'][] = 0;
                }
            }
        }
        $Temp13 = array();
        foreach ($Temp12 as $rows) {
            $Temp13[] = $rows;
        }
        $return['antibiotic'] = $optionsGraph;
        $return['datasheet'] = $Temp13;
        $return['care_name'] = (!empty($careUnit) && !empty($list)) ? $list[0]->care_name : "All";
        echo json_encode($return);
    }

    function actual_dot_vs_new_dot_by_care_unit_provider_doctor() {
        $return = array();
        $return['status'] = 200;
        $antibiotic = array();
        $return['careUnit'] = $careUnit = $this->input->post('careUnit');
        $rx = $this->input->post('rx');
        $symptom_onset = $this->input->post('symptom_onset');
        $criteria_met = $this->input->post('criteria_met');
        $md_stayward_response = $this->input->post('md_stayward_response');
        $culture_source = $this->input->post('culture_source');
        $organism = $this->input->post('organism');
        $precautions = $this->input->post('precautions');
        
        $option = array(
            'table' => 'patient P',
            'select' => 'P.id,P.patient_id,P.doctor_id,U.name',
            'join' => array(array('doctors U', 'U.id=P.doctor_id', 'inner')),
            'group_by' => array('P.doctor_id')
        );

        if (!empty($careUnit)) {
            $option['where']['P.care_unit_id'] = $careUnit;
        }
        $steward = $this->common_model->customGet($option);
        $optionsGraph = array();
        if (!empty($steward)) {
            foreach ($steward as $row) {
                $Temp['md_name'] = $row->name;
                $Temp['doctor_id'] = $row->doctor_id;
                $option = array(
                    'table' => 'patient P',
                    'select' => 'sum(PC.initial_dot) as total_initial_dot,sum(PC.new_initial_dot) as total_new_initial_dot,'
                    . 'CI.name as care_name',
                    'join' => array(array('care_unit CI', 'CI.id=P.care_unit_id', 'inner'),
                        array('patient_consult PC', 'PC.patient_id=P.id', 'inner')),
                    'single' => true
                );

                if (!empty($careUnit)) {
                    $option['where']['P.care_unit_id'] = $careUnit;
                }
                if(!empty($symptom_onset)){
                    $option['where']['P.symptom_onset'] = $symptom_onset;
                }
                if(!empty($md_stayward_response)){
                    $option['where']['P.md_stayward_response'] = $md_stayward_response;
                }
                if(!empty($culture_source)){
                    $option['where']['P.culture_source'] = $culture_source;
                }
                if(!empty($organism)){
                    $option['where']['P.organism'] = $organism;
                }
                if(!empty($precautions)){
                    $option['where']['P.precautions'] = $precautions;
                }
                if(!empty($criteria_met=='Yes')){
                    $option['where']['P.criteria_met'] = $criteria_met;
                }
                else if(!empty($criteria_met=='No')){
                    $option['where']['P.criteria_met'] = $criteria_met;
                }
                else if(!empty($criteria_met=='N/A')){
                    $option['where']['P.infection_surveillance_checklist'] = $criteria_met;
                }
                $option['where']['P.doctor_id'] = $row->doctor_id;
                $list = $this->common_model->customGet($option);
                if (!empty($list)) {
                    $Temp1['total_initial_dot'] = $list->total_initial_dot;
                    $Temp1['total_new_initial_dot'] = $list->total_new_initial_dot;
                    $Temp['dot'] = $Temp1;
                } else {
                    $Temp['dot'] = array();
                }
                $optionsGraph[] = $Temp;
            }
        }
        $Temp123 = array();
        foreach ($optionsGraph as $graph) {
            $Temp11['label'] = "Initial Days of Therapy";
            $Temp11['backgroundColor'] = "#B7950B";
            $Temp11['data'][] = (!empty($graph['dot']['total_initial_dot'])) ? $graph['dot']['total_initial_dot'] : 0;

            $Temp12['label'] = "Actual Days of Therapy";
            $Temp12['backgroundColor'] = "#8E44AD";
            $Temp12['data'][] = (!empty($graph['dot']['total_new_initial_dot'])) ? $graph['dot']['total_new_initial_dot'] : 0;
        }
        $Temp123 = array($Temp11, $Temp12);
        $return['antibiotic'] = $optionsGraph;
        $return['datasheet'] = $Temp123;
        echo json_encode($return);
    }

    function actual_dot_vs_new_dot_by_care_unit_md_steward() {
        $return = array();
        $return['status'] = 200;
        $antibiotic = array();
        $return['careUnit'] = $careUnit = $this->input->post('careUnit');
        $symptom_onset = $this->input->post('symptom_onset');
        $criteria_met = $this->input->post('criteria_met');
        $md_stayward_response = $this->input->post('md_stayward_response');
        $culture_source = $this->input->post('culture_source');
        $organism = $this->input->post('organism');
        $precautions = $this->input->post('precautions');
        
        $option = array(
            'table' => 'patient P',
            'select' => 'P.id,P.patient_id,P.md_steward_id,U.first_name,U.last_name,CONCAT(U.first_name," ",U.last_name) as name',
            'join' => array(array('users U', 'U.id=P.md_steward_id', 'inner')),
            'group_by' => array('P.md_steward_id')
        );

        if (!empty($careUnit)) {
            $option['where']['P.care_unit_id'] = $careUnit;
        }
        if(!empty($symptom_onset)){
            $option['where']['P.symptom_onset'] = $symptom_onset;
        }
        if(!empty($md_stayward_response)){
            $option['where']['P.md_stayward_response'] = $md_stayward_response;
        }
        if(!empty($culture_source)){
            $option['where']['P.culture_source'] = $culture_source;
        }
        if(!empty($organism)){
            $option['where']['P.organism'] = $organism;
        }
        if(!empty($precautions)){
            $option['where']['P.precautions'] = $precautions;
        }
        if(!empty($criteria_met=='Yes')){
            $option['where']['P.criteria_met'] = $criteria_met;
        }
        else if(!empty($criteria_met=='No')){
            $option['where']['P.criteria_met'] = $criteria_met;
        }
        else if(!empty($criteria_met=='N/A')){
            $option['where']['P.infection_surveillance_checklist'] = $criteria_met;
        }
        $steward = $this->common_model->customGet($option);
        $optionsGraph = array();
        if (!empty($steward)) {
            foreach ($steward as $row) {
                $Temp['md_name'] = $row->name;
                $Temp['md_steward_id'] = $row->md_steward_id;
                $option = array(
                    'table' => 'patient P',
                    'select' => 'sum(PC.initial_dot) as total_initial_dot,sum(PC.new_initial_dot) as total_new_initial_dot,'
                    . 'CI.name as care_name',
                    'join' => array(array('care_unit CI', 'CI.id=P.care_unit_id', 'inner'),
                        array('patient_consult PC', 'PC.patient_id=P.id', 'inner')),
                    'single' => true
                );

                if (!empty($careUnit)) {
                    $option['where']['P.care_unit_id'] = $careUnit;
                }
                if(!empty($symptom_onset)){
                    $option['where']['P.symptom_onset'] = $symptom_onset;
                }
                if(!empty($md_stayward_response)){
                    $option['where']['P.md_stayward_response'] = $md_stayward_response;
                }
                if(!empty($culture_source)){
                    $option['where']['P.culture_source'] = $culture_source;
                }
                if(!empty($organism)){
                    $option['where']['P.organism'] = $organism;
                }
                if(!empty($precautions)){
                    $option['where']['P.precautions'] = $precautions;
                }
                if(!empty($criteria_met=='Yes')){
                    $option['where']['P.criteria_met'] = $criteria_met;
                }
                else if(!empty($criteria_met=='No')){
                    $option['where']['P.criteria_met'] = $criteria_met;
                }
                else if(!empty($criteria_met=='N/A')){
                    $option['where']['P.infection_surveillance_checklist'] = $criteria_met;
                }
                $option['where']['P.md_steward_id'] = $row->md_steward_id;
                $list = $this->common_model->customGet($option);
                if (!empty($list)) {
                    $Temp1['total_initial_dot'] = $list->total_initial_dot;
                    $Temp1['total_new_initial_dot'] = $list->total_new_initial_dot;
                    $Temp['dot'] = $Temp1;
                } else {
                    $Temp['dot'] = array();
                }
                $optionsGraph[] = $Temp;
            }
        }
        $Temp123 = array();
        foreach ($optionsGraph as $graph) {
            $Temp11['label'] = "Initial Days of Therapy";
            $Temp11['backgroundColor'] = "#FF5733";
            $Temp11['data'][] = (!empty($graph['dot']['total_initial_dot'])) ? $graph['dot']['total_initial_dot'] : 0;

            $Temp12['label'] = "Actual Days of Therapy";
            $Temp12['backgroundColor'] = "#117864";
            $Temp12['data'][] = (!empty($graph['dot']['total_new_initial_dot'])) ? $graph['dot']['total_new_initial_dot'] : 0;
        }
        $Temp123 = array($Temp11, $Temp12);
        $return['antibiotic'] = $optionsGraph;
        $return['datasheet'] = $Temp123;
        echo json_encode($return);
    }

    function get_antibiotic_by_care_unit_md_steward_old() {
        $return = array();
        $return['status'] = 200;
        $antibiotic = array();
        $return['careUnit'] = $careUnit = $this->input->post('careUnit');
        $return['steward'] = $steward = $this->input->post('steward');
        $symptom_onset = $this->input->post('symptom_onset');
        $criteria_met = $this->input->post('criteria_met');
        $md_stayward_response = $this->input->post('md_stayward_response');
        $culture_source = $this->input->post('culture_source');   
        $organism = $this->input->post('organism');     
        $precautions = $this->input->post('precautions');

        $option = array(
            'table' => 'patient P',
            'select' => 'DISTINCT(PC.initial_rx) as rx_id,count(PC.initial_rx) as total,P.care_unit_id,IRX.name,CI.name as care_name',
            'join' => array(array('care_unit CI', 'CI.id=P.care_unit_id', 'inner'),
                array('patient_consult PC', 'PC.patient_id=P.id', 'inner'),
                array('initial_rx IRX', 'IRX.id=PC.initial_rx', 'inner')),
            'group_by' => array('PC.initial_rx')
        );
        $option['where']['P.care_unit_id'] = $careUnit;
        if (!empty($steward)) {
            $option['where']['P.md_steward_id'] = $steward;
        }
        if(!empty($symptom_onset)){
            $option['where']['P.symptom_onset'] = $symptom_onset;
        }
        if(!empty($md_stayward_response)){
            $option['where']['P.md_stayward_response'] = $md_stayward_response;
        }
        if(!empty($culture_source)){
            $option['where']['P.culture_source'] = $culture_source;
        }
        if(!empty($organism)){
            $option['where']['P.organism'] = $organism;
        }
        if(!empty($precautions)){
            $option['where']['P.precautions'] = $precautions;
        }
        if(!empty($criteria_met=='Yes')){
            $option['where']['P.criteria_met'] = $criteria_met;
        }
        else if(!empty($criteria_met=='No')){
            $option['where']['P.criteria_met'] = $criteria_met;
        }
        else if(!empty($criteria_met=='N/A')){
            $option['where']['P.infection_surveillance_checklist'] = $criteria_met;
        }
        $list = $this->common_model->customGet($option);
        if (!empty($list)) {
            $total_antibiotic_used = array_sum(array_column($list, 'total'));
            $total_antibiotic = count($list);
            foreach ($list as $rows) {
                $percentage = round(($rows->total / $total_antibiotic_used) * 100);
                $Temp['percentage'] = $percentage;
                $Temp['total_used'] = $rows->total;
                $Temp['name'] = $rows->name;
                $Temp['rx_id'] = $rows->rx_id;
                $antibiotic[] = $Temp;
            }
            $return['antibiotic'] = $antibiotic;
            $return['care_name'] = $list[0]->care_name;
        } else {
            $return['status'] = 400;
        }
        echo json_encode($return);
    }

    function get_days_saved_by_care_unit() {
        $return = array();
        $return['status'] = 200;
        $antibiotic = array();
        $return['careUnit'] = $careUnit = $this->input->post('careUnit');
        $option = array(
            'table' => 'patient P',
            'select' => 'sum(PC.initial_dot) as total_initial_dot,sum(PC.new_initial_dot) as total_new_initial_dot,'
            . 'CI.name as care_name',
            'join' => array(array('care_unit CI', 'CI.id=P.care_unit_id', 'inner'),
                array('patient_consult PC', 'PC.patient_id=P.id', 'inner')),
            'single' => true
        );

        if (!empty($careUnit)) {
            $option['where']['P.care_unit_id'] = $careUnit;
        }
        if(!empty($symptom_onset)){
            $option['where']['P.symptom_onset'] = $symptom_onset;
        }
        if(!empty($md_stayward_response)){
            $option['where']['P.md_stayward_response'] = $md_stayward_response;
        }
        if(!empty($culture_source)){
            $option['where']['P.culture_source'] = $culture_source;
        }
        if(!empty($organism)){
            $option['where']['P.organism'] = $organism;
        }
        if(!empty($precautions)){
            $option['where']['P.precautions'] = $precautions;
        }
        if(!empty($criteria_met=='Yes')){
            $option['where']['P.criteria_met'] = $criteria_met;
        }
        else if(!empty($criteria_met=='No')){
            $option['where']['P.criteria_met'] = $criteria_met;
        }
        else if(!empty($criteria_met=='N/A')){
            $option['where']['P.infection_surveillance_checklist'] = $criteria_met;
        }
        $list = $this->common_model->customGet($option);
        //dump($list);
        if (!empty($list)) {
            $total_initial_dot = $list->total_initial_dot;
            $total_new_initial_dot = $list->total_new_initial_dot;
            $percentage = round((($total_initial_dot - $total_new_initial_dot) / $total_initial_dot) * 100);
            $antibiotic[] = array('name' => "Total Days Saved", 'percentage' => $percentage);
            //$antibiotic[] = array('name' => "", 'percentage' => 100 - $percentage);
            $return['care_name'] = (!empty($careUnit)) ? $list->care_name : "All";
            $return['antibiotic'] = $antibiotic;
        } else {
            $return['status'] = 400;
        }
        echo json_encode($return);
    }

    function actual_dot_vs_new_dot_by_care_unit() {
        $return = array();
        $return['status'] = 200;
        $antibiotic = array();
        $return['careUnit'] = $careUnit = $this->input->post('careUnit');
        $symptom_onset = $this->input->post('symptom_onset');
        $criteria_met = $this->input->post('criteria_met');
        $md_stayward_response = $this->input->post('md_stayward_response');
        $culture_source = $this->input->post('culture_source');
        $organism = $this->input->post('organism');
        $precautions = $this->input->post('precautions');
        
        $option = array(
            'table' => 'patient P',
            'select' => 'sum(PC.initial_dot) as total_initial_dot,sum(PC.new_initial_dot) as total_new_initial_dot,CI.name as care_name',
            'join' => array(array('care_unit CI', 'CI.id=P.care_unit_id', 'inner'),
                array('patient_consult PC', 'PC.patient_id=P.id', 'inner')),
            'single' => true
        );

        if (!empty($careUnit)) {
            $option['where']['P.care_unit_id'] = $careUnit;
        }
        if(!empty($symptom_onset)){
            $option['where']['P.symptom_onset'] = $symptom_onset;
        }
        if(!empty($md_stayward_response)){
            $option['where']['P.md_stayward_response'] = $md_stayward_response;
        }
        if(!empty($culture_source)){
            $option['where']['P.culture_source'] = $culture_source;
        }
        if(!empty($organism)){
            $option['where']['P.organism'] = $organism;
        }
        if(!empty($precautions)){
            $option['where']['P.precautions'] = $precautions;
        }
        if(!empty($criteria_met=='Yes')){
            $option['where']['P.criteria_met'] = $criteria_met;
        }
        else if(!empty($criteria_met=='No')){
            $option['where']['P.criteria_met'] = $criteria_met;
        }
        else if(!empty($criteria_met=='N/A')){
            $option['where']['P.infection_surveillance_checklist'] = $criteria_met;
        }
        $list = $this->common_model->customGet($option);
        if (!empty($list)) {
            $total_initial_dot = $list->total_initial_dot;
            $total_new_initial_dot = $list->total_new_initial_dot;
            //$percentage = round((($total_initial_dot - $total_new_initial_dot) / $total_initial_dot) * 100);
            $antibiotic[] = array('name' => "Initial Days of Therapy", 'percentage' => $total_initial_dot);
            $antibiotic[] = array('name' => "Actual Days of Therapy", 'percentage' => $total_new_initial_dot);
            $return['care_name'] = (!empty($careUnit)) ? $list->care_name : "All";
            $return['antibiotic'] = $antibiotic;
        } else {
            $return['status'] = 400;
        }
        echo json_encode($return);
    }

    function actual_dot_vs_new_dot_by_care_unit_md_stewardOLD() {
        $return = array();
        $return['status'] = 200;
        $antibiotic = array();
        $return['careUnit'] = $careUnit = $this->input->post('careUnit');
        $return['steward'] = $steward = $this->input->post('steward');
        $symptom_onset = $this->input->post('symptom_onset');
        $criteria_met = $this->input->post('criteria_met');
        $md_stayward_response = $this->input->post('md_stayward_response');
        $culture_source = $this->input->post('culture_source');
        $organism = $this->input->post('organism');
        $precautions = $this->input->post('precautions');

        $option = array(
            'table' => 'patient P',
            'select' => 'sum(PC.initial_dot) as total_initial_dot,sum(PC.new_initial_dot) as total_new_initial_dot,CI.name as care_name',
            'join' => array(array('care_unit CI', 'CI.id=P.care_unit_id', 'inner'),
                array('patient_consult PC', 'PC.patient_id=P.id', 'inner')),
            'single' => true
        );
        $option['where']['P.care_unit_id'] = $careUnit;
        $option['where']['P.md_steward_id'] = $steward;
        $list = $this->common_model->customGet($option);
        if (!empty($list) && !empty($steward)) {
            $total_initial_dot = $list->total_initial_dot;
            $total_new_initial_dot = $list->total_new_initial_dot;
            //$percentage = round((($total_initial_dot - $total_new_initial_dot) / $total_initial_dot) * 100);
            $antibiotic[] = array('name' => "Initial Days of Therapy", 'percentage' => ($total_initial_dot) ? $total_initial_dot : 0);
            $antibiotic[] = array('name' => "Actual Days of Therapy", 'percentage' => ($total_new_initial_dot) ? $total_new_initial_dot : 0);
            $return['care_name'] = $list->care_name;
            $return['antibiotic'] = $antibiotic;
        } else {
            $return['status'] = 400;
        }
        echo json_encode($return);
    }

    function actual_dot_vs_new_dot_by_care_unit_provider_doctorOLD() {
        $return = array();
        $return['status'] = 200;
        $antibiotic = array();
        $return['careUnit'] = $careUnit = $this->input->post('careUnit');
        $return['provider_doctor'] = $provider_doctor = $this->input->post('provider_doctor');
        $symptom_onset = $this->input->post('symptom_onset');
        $criteria_met = $this->input->post('criteria_met');
        $md_stayward_response = $this->input->post('md_stayward_response');
        $culture_source = $this->input->post('culture_source');
        $organism = $this->input->post('organism');
        $precautions = $this->input->post('precautions');
        

        $option = array(
            'table' => 'patient P',
            'select' => 'sum(PC.initial_dot) as total_initial_dot,sum(PC.new_initial_dot) as total_new_initial_dot,CI.name as care_name',
            'join' => array(array('care_unit CI', 'CI.id=P.care_unit_id', 'inner'),
                array('patient_consult PC', 'PC.patient_id=P.id', 'inner')),
            'single' => true
        );
        $option['where']['P.care_unit_id'] = $careUnit;
        $option['where']['P.doctor_id'] = $provider_doctor;
        $list = $this->common_model->customGet($option);
        if (!empty($list) && !empty($provider_doctor)) {
            $total_initial_dot = $list->total_initial_dot;
            $total_new_initial_dot = $list->total_new_initial_dot;
            //$percentage = round((($total_initial_dot - $total_new_initial_dot) / $total_initial_dot) * 100);
            $antibiotic[] = array('name' => "Initial Days of Therapy", 'percentage' => ($total_initial_dot) ? $total_initial_dot : 0);
            $antibiotic[] = array('name' => "Actual Days of Therapy", 'percentage' => ($total_new_initial_dot) ? $total_new_initial_dot : 0);
            $return['care_name'] = $list->care_name;
            $return['antibiotic'] = $antibiotic;
        } else {
            $return['status'] = 400;
        }
        echo json_encode($return);
    }

    function get_rx_by_actual_dots() {
        $return = array();
        $return['status'] = 200;
        $antibiotic = array();
        $return['careUnit'] = $careUnit = $this->input->post('careUnit');
        $return['steward'] = $steward = $this->input->post('steward');
        $rx = $this->input->post('rx');
        $symptom_onset = $this->input->post('symptom_onset');
        $criteria_met = $this->input->post('criteria_met');
        $md_stayward_response = $this->input->post('md_stayward_response');
        $culture_source = $this->input->post('culture_source');
        $organism = $this->input->post('organism');
        $precautions = $this->input->post('precautions');
        

        $option = array(
            'table' => 'patient P',
            'select' => '(CASE WHEN new_initial_dot IS NOT NULL THEN SUM(new_initial_dot) ELSE SUM(initial_dot) END) totalDays ,P.care_unit_id,IRX.name,CI.name as care_name',
            'join' => array(array('care_unit CI', 'CI.id=P.care_unit_id', 'inner'),
                array('patient_consult PC', 'PC.patient_id=P.id', 'inner'),
                array('initial_rx IRX', 'IRX.id=PC.initial_rx', 'inner')),
            'group_by' => array('PC.initial_rx')
        );

        if (!empty($careUnit)) {
            $option['where']['P.care_unit_id'] = $careUnit;
        }
        if (!empty($rx)) {
            $option['where']['IRX.id'] = $rx;
        }
        if(!empty($symptom_onset)){
            $option['where']['P.symptom_onset'] = $symptom_onset;
        }
        if(!empty($md_stayward_response)){
            $option['where']['P.md_stayward_response'] = $md_stayward_response;
        }
        if(!empty($culture_source)){
            $option['where']['P.culture_source'] = $culture_source;
        }
        if(!empty($organism)){
            $option['where']['P.organism'] = $organism;
        }
        if(!empty($precautions)){
            $option['where']['P.precautions'] = $precautions;
        }
        if(!empty($criteria_met=='Yes')){
            $option['where']['P.criteria_met'] = $criteria_met;
        }
        else if(!empty($criteria_met=='No')){
            $option['where']['P.criteria_met'] = $criteria_met;
        }
        else if(!empty($criteria_met=='N/A')){
            $option['where']['P.infection_surveillance_checklist'] = $criteria_met;
        }
        $list = $this->common_model->customGet($option);
        if (!empty($list)) {
            $total_antibiotic_days = array_sum(array_column($list, 'totalDays'));
            $total_antibiotic = count($list);
            foreach ($list as $rows) {
                $percentage = round(($rows->totalDays / $total_antibiotic_days) * 100);
                $Temp['percentage'] = $percentage;
                $Temp['total_used'] = $rows->totalDays;
                $Temp['name'] = $rows->name;
                $antibiotic[] = $Temp;
            }
            $return['antibiotic'] = $antibiotic;
            $return['care_name'] = (!empty($careUnit)) ? $list[0]->care_name : "All";
        } else {
            $return['status'] = 400;
        }
        echo json_encode($return);
    }

    function get_dx_by_actual_dots() {
        $return = array();
        $return['status'] = 200;
        $antibiotic = array();
        $return['careUnit'] = $careUnit = $this->input->post('careUnit');
        $return['steward'] = $steward = $this->input->post('steward');
        $rx = $this->input->post('rx');
        $symptom_onset = $this->input->post('symptom_onset');
        $criteria_met = $this->input->post('criteria_met');
        $md_stayward_response = $this->input->post('md_stayward_response');
        $culture_source = $this->input->post('culture_source');
        $organism = $this->input->post('organism');
        $precautions = $this->input->post('precautions');

        $option = array(
            'table' => 'patient P',
            'select' => '(CASE WHEN new_initial_dot IS NOT NULL THEN SUM(new_initial_dot) ELSE SUM(initial_dot) END) totalDays ,P.care_unit_id,IRX.name,CI.name as care_name',
            'join' => array(array('care_unit CI', 'CI.id=P.care_unit_id', 'inner'),
                array('patient_consult PC', 'PC.patient_id=P.id', 'inner'),
                array('initial_dx IRX', 'IRX.id=PC.initial_dx', 'inner')),
            'group_by' => array('IRX.name')
        );

        if (!empty($careUnit)) {
            $option['where']['P.care_unit_id'] = $careUnit;
        }
        if (!empty($rx)) {
            $option['where']['IRX.id'] = $rx;
        }
        if(!empty($symptom_onset)){
            $option['where']['P.symptom_onset'] = $symptom_onset;
        }
        if(!empty($md_stayward_response)){
            $option['where']['P.md_stayward_response'] = $md_stayward_response;
        }
        if(!empty($culture_source)){
            $option['where']['P.culture_source'] = $culture_source;
        }
        if(!empty($organism)){
            $option['where']['P.organism'] = $organism;
        }
        if(!empty($precautions)){
            $option['where']['P.precautions'] = $precautions;
        }
        if(!empty($criteria_met=='Yes')){
            $option['where']['P.criteria_met'] = $criteria_met;
        }
        else if(!empty($criteria_met=='No')){
            $option['where']['P.criteria_met'] = $criteria_met;
        }
        else if(!empty($criteria_met=='N/A')){
            $option['where']['P.infection_surveillance_checklist'] = $criteria_met;
        }
        $list = $this->common_model->customGet($option);
        if (!empty($list)) {
            $total_antibiotic_days = array_sum(array_column($list, 'totalDays'));
            $total_antibiotic = count($list);
            foreach ($list as $rows) {
                $percentage = round(($rows->totalDays / $total_antibiotic_days) * 100);
                $Temp['percentage'] = $percentage;
                $Temp['total_used'] = $rows->totalDays;
                $Temp['name'] = $rows->name;
                $antibiotic[] = $Temp;
            }
            $return['antibiotic'] = $antibiotic;
            $return['care_name'] = (!empty($careUnit)) ? $list[0]->care_name : "All";
        } else {
            $return['status'] = 400;
        }
        echo json_encode($return);
    }

    function get_antibiotic_by_care_unit_provider_price() {
        $return = array();
        $return['status'] = 200;
        $antibiotic = array();
        $return['careUnit'] = $careUnit = $this->input->post('careUnit');
        $return['provider'] = $provider = $this->input->post('provider');
        $rx = $this->input->post('rx');
        $symptom_onset = $this->input->post('symptom_onset');
        $criteria_met = $this->input->post('criteria_met');
        $md_stayward_response = $this->input->post('md_stayward_response');
        $culture_source = $this->input->post('culture_source');
        $organism = $this->input->post('organism');
        $precautions = $this->input->post('precautions');

        $option = array(
            'table' => 'patient P',
            'select' => 'P.id,P.patient_id,P.doctor_id,U.name',
            'join' => array(array('doctors U', 'U.id=P.doctor_id', 'inner')),
            'group_by' => array('P.doctor_id')
        );
        if (!empty($careUnit)) {
            $option['where']['P.care_unit_id'] = $careUnit;
        }
        if (!empty($provider)) {
            $option['where']['P.doctor_id'] = $provider;
        }
        if(!empty($symptom_onset)){
            $option['where']['P.symptom_onset'] = $symptom_onset;
        }
        if(!empty($md_stayward_response)){
            $option['where']['P.md_stayward_response'] = $md_stayward_response;
        }
        if(!empty($culture_source)){
            $option['where']['P.culture_source'] = $culture_source;
        }
        if(!empty($organism)){
            $option['where']['P.organism'] = $organism;
        }
        if(!empty($precautions)){
            $option['where']['P.precautions'] = $precautions;
        }
        if(!empty($criteria_met=='Yes')){
            $option['where']['P.criteria_met'] = $criteria_met;
        }
        else if(!empty($criteria_met=='No')){
            $option['where']['P.criteria_met'] = $criteria_met;
        }
        else if(!empty($criteria_met=='N/A')){
            $option['where']['P.infection_surveillance_checklist'] = $criteria_met;
        }
        $steward = $this->common_model->customGet($option);
        $total_provider = count($steward);
        $optionsGraph = array();
        $mediRx = array();
        $ProviderName = "";
        if (!empty($steward)) {
            foreach ($steward as $row) {
                $Temp['md_name'] = $ProviderName = $row->name;
                $Temp['doctor_id'] = $row->doctor_id;
                $option = array(
                    'table' => 'patient P',
                    'select' => 'DISTINCT(PC.initial_rx) as rx_id,SUM(PC.initial_dot) as totalDays,P.care_unit_id,IRX.name,
                    CI.name as care_name,IRX.description,IRX.price',
                    'join' => array(array('care_unit CI', 'CI.id=P.care_unit_id', 'inner'),
                        array('patient_consult PC', 'PC.patient_id=P.id', 'inner'),
                        array('initial_rx IRX', 'IRX.id=PC.initial_rx', 'inner')),
                    'group_by' => array('PC.initial_rx'),
                    'order' => array('PC.initial_rx' => "ASC")
                );
                if (!empty($careUnit)) {
                    $option['where']['P.care_unit_id'] = $careUnit;
                }
                if (!empty($rx)) {
                    $option['where']['IRX.id'] = $rx;
                }
                if(!empty($symptom_onset)){
                    $option['where']['P.symptom_onset'] = $symptom_onset;
                }
                if(!empty($md_stayward_response)){
                    $option['where']['P.md_stayward_response'] = $md_stayward_response;
                }
                if(!empty($culture_source)){
                    $option['where']['P.culture_source'] = $culture_source;
                }
                if(!empty($organism)){
                    $option['where']['P.organism'] = $organism;
                }
                if(!empty($precautions)){
                    $option['where']['P.precautions'] = $precautions;
                }
                if(!empty($criteria_met=='Yes')){
                    $option['where']['P.criteria_met'] = $criteria_met;
                }
                else if(!empty($criteria_met=='No')){
                    $option['where']['P.criteria_met'] = $criteria_met;
                }
                else if(!empty($criteria_met=='N/A')){
                    $option['where']['P.infection_surveillance_checklist'] = $criteria_met;
                }
                $option['where']['P.doctor_id'] = $row->doctor_id;
                $list = $this->common_model->customGet($option);
                //dump( $list);
                $Temp['all'] = array();
                if (!empty($list)) {
                    //$total_antibiotic_used = array_sum(array_column($list, 'total'));
                    $total_antibiotic = count($list);
                    $color = array();
                    $percentages = array();
                    $labels = array();
                    $multi_labels = array();
                    foreach ($list as $rows) {
                        $percentage = $rows->totalDays * $rows->price;
                        $Temp1['percentage'] = $percentage;
                        $Temp1['total_used'] = $percentage;
                        $Temp1['name'] = $rows->name;
                        $Temp1['rx_id'] = $rows->rx_id;
                        $Temp1['color'] = $rows->description;
                        $color[] = $rows->description;
                        $percentages[] = $percentage;
                        $labels[$rows->rx_id] = $rows->name;
                        $mediRx[$rows->rx_id]['id'] = $rows->rx_id;
                        $mediRx[$rows->rx_id]['name'] = $rows->name;
                        $mediRx[$rows->rx_id]['color'] = $rows->description;
                        $multi_labels[$rows->rx_id] = array('rx_id' => $rows->rx_id, 'Name' => $rows->name, 'percentage' => $percentage, 'color' => $rows->description, 'md_id' => $row->doctor_id);
                    }
                    $Temp['all'] = $multi_labels;
                }
                $optionsGraph[] = $Temp;
            }
        }

        sort($mediRx);
        $Temp12 = array();
        foreach ($optionsGraph as $graph) {
            $all = $graph['all'];
            $all_only_id = array_column($all, "rx_id");
            $md_id = $graph['doctor_id'];
            foreach ($mediRx as $rx) {
                $Temp12[$rx['id']]['label'] = $rx['name'];
                $Temp12[$rx['id']]['backgroundColor'] = $rx['color'];
                if (in_array($rx['id'], $all_only_id)) {
                    if (isset($all[$rx['id']]) && $all[$rx['id']]['md_id'] == $md_id) {
                        if (!empty($all[$rx['id']])) {
                            $Temp12[$rx['id']]['data'][] = $all[$rx['id']]['percentage'];
                        }
                    }
                } else {
                    $Temp12[$rx['id']]['data'][] = 0;
                }
            }
        }
        $Temp13 = array();
        foreach ($Temp12 as $rows) {
            $Temp13[] = $rows;
        }

        $rest_graph = array();
        foreach ($Temp13 as $value) {
            $t['label'] = $value['label'];
            $t['backgroundColor'] = $value['backgroundColor'];
            $t['percentage'] = array_sum($value['data']);
            $rest_graph[] = $t;
        }
        //$return['antibiotic'] = $optionsGraph;
        $return['provider_name'] = $ProviderName;
        $return['rest_graph'] = $rest_graph;
        $return['care_name'] = (!empty($careUnit) && !empty($list)) ? $list[0]->care_name : "All";

        echo json_encode($return);
    }

    function get_antibiotic_by_care_unit_steward_price() {
        $return = array();
        $return['status'] = 200;
        $antibiotic = array();
        $return['careUnit'] = $careUnit = $this->input->post('careUnit');
        $return['provider'] = $provider = $this->input->post('provider');
        $rx = $this->input->post('rx');
        $symptom_onset = $this->input->post('symptom_onset');
        $criteria_met = $this->input->post('criteria_met');
        $md_stayward_response = $this->input->post('md_stayward_response');
        $culture_source = $this->input->post('culture_source');
        $organism = $this->input->post('organism');
        $precautions = $this->input->post('precautions');


        //$careUnit = 3;

        $option = array(
            'table' => 'patient P',
            'select' => 'P.id,P.patient_id,P.md_steward_id,U.first_name,U.last_name,CONCAT(U.first_name," ",U.last_name) as name',
            'join' => array(array('users U', 'U.id=P.md_steward_id', 'inner')),
            'group_by' => array('P.md_steward_id')
        );
        if (!empty($provider)) {
            $option['where']['P.md_steward_id'] = $provider;
        }
        if (!empty($careUnit)) {
            $option['where']['P.care_unit_id'] = $careUnit;
        }
        if(!empty($symptom_onset)){
            $option['where']['P.symptom_onset'] = $symptom_onset;
        }
        if(!empty($md_stayward_response)){
            $option['where']['P.md_stayward_response'] = $md_stayward_response;
        }
        if(!empty($culture_source)){
            $option['where']['P.culture_source'] = $culture_source;
        }
        if(!empty($organism)){
            $option['where']['P.organism'] = $organism;
        }
        if(!empty($precautions)){
            $option['where']['P.precautions'] = $precautions;
        }
        if(!empty($criteria_met=='Yes')){
            $option['where']['P.criteria_met'] = $criteria_met;
        }
        else if(!empty($criteria_met=='No')){
            $option['where']['P.criteria_met'] = $criteria_met;
        }
        else if(!empty($criteria_met=='N/A')){
            $option['where']['P.infection_surveillance_checklist'] = $criteria_met;
        }
        $steward = $this->common_model->customGet($option);
        $total_provider = count($steward);
        $optionsGraph = array();
        $mediRx = array();
        $ProviderName = "";
        if (!empty($steward)) {
            foreach ($steward as $row) {
                $Temp['md_name'] = $ProviderName = $row->name;
                $Temp['md_steward_id'] = $row->md_steward_id;
                $option = array(
                    'table' => 'patient P',
                    'select' => 'DISTINCT(PC.initial_rx) as rx_id,SUM(PC.new_initial_dot) as totalDays,P.care_unit_id,IRX.name,
                    CI.name as care_name,IRX.description,IRX.price',
                    'join' => array(array('care_unit CI', 'CI.id=P.care_unit_id', 'inner'),
                        array('patient_consult PC', 'PC.patient_id=P.id', 'inner'),
                        array('initial_rx IRX', 'IRX.id=PC.initial_rx', 'inner')),
                    'group_by' => array('PC.initial_rx'),
                    'order' => array('PC.initial_rx' => "ASC")
                );
                if (!empty($careUnit)) {
                    $option['where']['P.care_unit_id'] = $careUnit;
                }
                if (!empty($rx)) {
                    $option['where']['IRX.id'] = $rx;
                }
                if(!empty($symptom_onset)){
                    $option['where']['P.symptom_onset'] = $symptom_onset;
                }
                if(!empty($md_stayward_response)){
                    $option['where']['P.md_stayward_response'] = $md_stayward_response;
                }
                if(!empty($culture_source)){
                    $option['where']['P.culture_source'] = $culture_source;
                }
                if(!empty($organism)){
                    $option['where']['P.organism'] = $organism;
                }
                if(!empty($precautions)){
                    $option['where']['P.precautions'] = $precautions;
                }
                if(!empty($criteria_met=='Yes')){
                    $option['where']['P.criteria_met'] = $criteria_met;
                }
                else if(!empty($criteria_met=='No')){
                    $option['where']['P.criteria_met'] = $criteria_met;
                }
                else if(!empty($criteria_met=='N/A')){
                    $option['where']['P.infection_surveillance_checklist'] = $criteria_met;
                }
                $option['where']['P.md_steward_id'] = $row->md_steward_id;
                $list = $this->common_model->customGet($option);
                $Temp['all'] = array();
                if (!empty($list)) {
                    //$total_antibiotic_used = array_sum(array_column($list, 'total'));
                    $total_antibiotic = count($list);
                    $color = array();
                    $percentages = array();
                    $labels = array();
                    $multi_labels = array();
                    foreach ($list as $rows) {
                        $percentage = $rows->totalDays * $rows->price;
                        $Temp1['percentage'] = $percentage;
                        $Temp1['total_used'] = $percentage;
                        $Temp1['name'] = $rows->name;
                        $Temp1['rx_id'] = $rows->rx_id;
                        $Temp1['color'] = $rows->description;
                        $color[] = $rows->description;
                        $percentages[] = $percentage;
                        $labels[$rows->rx_id] = $rows->name;
                        $mediRx[$rows->rx_id]['id'] = $rows->rx_id;
                        $mediRx[$rows->rx_id]['name'] = $rows->name;
                        $mediRx[$rows->rx_id]['color'] = $rows->description;
                        $multi_labels[$rows->rx_id] = array('rx_id' => $rows->rx_id, 'Name' => $rows->name, 'percentage' => $percentage, 'color' => $rows->description, 'md_id' => $row->md_steward_id);
                    }
                    $Temp['all'] = $multi_labels;
                }
                $optionsGraph[] = $Temp;
            }
        }

        sort($mediRx);
        $Temp12 = array();
        foreach ($optionsGraph as $graph) {
            $all = $graph['all'];
            $all_only_id = array_column($all, "rx_id");
            $md_id = $graph['md_steward_id'];
            foreach ($mediRx as $rx) {
                $Temp12[$rx['id']]['label'] = $rx['name'];
                $Temp12[$rx['id']]['backgroundColor'] = $rx['color'];
                if (in_array($rx['id'], $all_only_id)) {
                    if (isset($all[$rx['id']]) && $all[$rx['id']]['md_id'] == $md_id) {
                        if (!empty($all[$rx['id']])) {
                            $Temp12[$rx['id']]['data'][] = $all[$rx['id']]['percentage'];
                        }
                    }
                } else {
                    $Temp12[$rx['id']]['data'][] = 0;
                }
            }
        }
        $Temp13 = array();
        foreach ($Temp12 as $rows) {
            $Temp13[] = $rows;
        }

        $rest_graph = array();
        foreach ($Temp13 as $value) {
            $t['label'] = $value['label'];
            $t['backgroundColor'] = $value['backgroundColor'];
            $t['percentage'] = array_sum($value['data']);
            $rest_graph[] = $t;
        }
        //$return['antibiotic'] = $optionsGraph;
        $return['provider_name'] = $ProviderName;
        $return['rest_graph'] = $rest_graph;
        $return['care_name'] = (!empty($careUnit) && !empty($list)) ? $list[0]->care_name : "All";

        echo json_encode($return);
    }




}
