<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Reports extends Common_Controller {

    public $data = "";

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
            if ($this->ion_auth->is_admin() || $this->ion_auth->is_subAdmin() || $this->ion_auth->is_facilityManager()) {

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
                $this->load->admin_render('dashboards', $this->data, 'inner_script');
            } else if ($this->ion_auth->is_vendor()) {
                $this->load->admin_render('dashboard', $this->data, 'inner_script');
            } else {
                $this->session->set_flashdata('message', 'You are not authorised to access administration');
                redirect('pwfpanel/login', 'refresh');
            }
        }
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
        $return['steward'] = $steward = $this->input->post('steward');
        $rx = $this->input->post('rx');

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

    function get_antibiotic_by_care_unit_provider() {
        $return = array();
        $return['status'] = 200;
        $antibiotic = array();
        $return['careUnit'] = $careUnit = $this->input->post('careUnit');
        $return['provider'] = $provider = $this->input->post('provider');
        $rx = $this->input->post('rx');


        //$careUnit = 8;
        $option = array(
            'table' => 'patient P',
            'select' => 'P.id,P.patient_id,P.doctor_id,U.name',
            'join' => array(array('doctors U', 'U.id=P.doctor_id', 'inner')),
            'group_by' => array('P.doctor_id')
        );
        // if (!empty($careUnit)) {
        //     $option['where']['P.care_unit_id'] = $careUnit;
        // }
        if (!empty($provider)) {
            $option['where']['P.doctor_id !='] = $provider;
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

                // if (!empty($careUnit)) {
                //     $option['where']['P.care_unit_id'] = $careUnit;
                // }

                if (!empty($rx)) {
                    $option['where']['PC.initial_rx'] = $rx;
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

        echo json_encode($return);
    }

    function get_antibiotic_by_care_unit_provider_id() {
        $return = array();
        $return['status'] = 200;
        $antibiotic = array();
        $return['careUnit'] = $careUnit = $this->input->post('careUnit');
        $return['provider'] = $provider = (!empty($this->input->post('provider'))) ? $this->input->post('provider') : 5;
        $rx = $this->input->post('rx');



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
            $Temp11['label'] = "Initial DOT";
            $Temp11['backgroundColor'] = "#B7950B";
            $Temp11['data'][] = (!empty($graph['dot']['total_initial_dot'])) ? $graph['dot']['total_initial_dot'] : 0;

            $Temp12['label'] = "Actual DOT";
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
        $option = array(
            'table' => 'patient P',
            'select' => 'P.id,P.patient_id,P.md_steward_id,U.first_name,U.last_name,CONCAT(U.first_name," ",U.last_name) as name',
            'join' => array(array('users U', 'U.id=P.md_steward_id', 'inner')),
            'group_by' => array('P.md_steward_id')
        );

        if (!empty($careUnit)) {
            $option['where']['P.care_unit_id'] = $careUnit;
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
            $Temp11['label'] = "Initial DOT";
            $Temp11['backgroundColor'] = "#FF5733";
            $Temp11['data'][] = (!empty($graph['dot']['total_initial_dot'])) ? $graph['dot']['total_initial_dot'] : 0;

            $Temp12['label'] = "Actual DOT";
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
        $list = $this->common_model->customGet($option);
        if (!empty($list)) {
            $total_initial_dot = $list->total_initial_dot;
            $total_new_initial_dot = $list->total_new_initial_dot;
            //$percentage = round((($total_initial_dot - $total_new_initial_dot) / $total_initial_dot) * 100);
            $antibiotic[] = array('name' => "Initial DOT", 'percentage' => $total_initial_dot);
            $antibiotic[] = array('name' => "Actual DOT", 'percentage' => $total_new_initial_dot);
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
            $antibiotic[] = array('name' => "Initial DOT", 'percentage' => ($total_initial_dot) ? $total_initial_dot : 0);
            $antibiotic[] = array('name' => "Actual DOT", 'percentage' => ($total_new_initial_dot) ? $total_new_initial_dot : 0);
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
            $antibiotic[] = array('name' => "Initial DOT", 'percentage' => ($total_initial_dot) ? $total_initial_dot : 0);
            $antibiotic[] = array('name' => "Actual DOT", 'percentage' => ($total_new_initial_dot) ? $total_new_initial_dot : 0);
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
