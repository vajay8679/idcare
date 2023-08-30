<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Patient extends Common_Controller
{

    public $data = array();
    public $file_data = "";
    public $_table = 'patient';
    public $title = "Patient";

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
        $AdminCareUnitID = isset($_SESSION['admin_care_unit_id']) ? $_SESSION['admin_care_unit_id'] : '';
        $option = array('table' => 'care_unit', 'where' => array('delete_status' => 0, 'is_active' => 1), 'order' => array('name' => 'ASC'));
        if (!empty($AdminCareUnitID)) {

            $option['where']['id'] = $AdminCareUnitID;
        }
         //print_r(json_decode($AdminCareUnitID));die;
        $this->data['careUnit'] = $this->common_model->customGet($option);
// print_r($this->data['careUnit']);die;
$this->data['careUnits'] = json_decode($AdminCareUnitID);

$y = $this->data['careUnits'];
$x =count($y);
// print_r($x);die;

$careUnitData =array();
foreach($this->data['careUnits'] as $value){

    $option = array(
        'table' => 'care_unit',
        'select' => 'care_unit.id,care_unit.name',
        'where' =>array('care_unit.id'=>$value)
    );
    $careUnitData[] = $this->common_model->customGet($option);
}
$arraySingle = call_user_func_array('array_merge', $careUnitData);
$this->data['careUnitsUser'] = $arraySingle;

//$this->data['careUnits_list'] = json_decode($AdminCareUnitID);
$careUnitData_list =array();
foreach($this->data['careUnits_list'] as $value){

    $option = array(
        'table' => 'patient',
        'select' => 'patient.patient_id as pid,care_unit.name as care_unit_name,doctors.name as doctor_name,users.first_name as md_stayward,patient.date_of_start_abx',
        'join' => array(array('care_unit','care_unit.id=patient.care_unit_id'),
        array('doctors','doctors.id=patient.doctor_id'),
        array('user','user.id=patient.md_steward_id')),
        'where' =>array('patient.id'=>$value)
    );


        // $option = array(
        //     'table' => 'patient P',
        //     'select' => 'P.id as patient_id,P.patient_id as pid,P.name as patient_name,P.date_of_start_abx,P.address,P.room_number,P.symptom_onset,P.md_stayward_consult,P.criteria_met,P.md_stayward_response,P.psa,P.created_date,'
        //         . 'P.care_unit_id,CI.name as care_unit_name,P.doctor_id,P.culture_source,P.organism,P.precautions,CS.name as culture_source_name,Org.name as organism_name,Pre.name as precautions_name,DOC.name as doctor_name,P.md_steward_id,U.first_name as md_stayward,'
        //         . 'PC.initial_rx,IRX.name as initial_rx_name,PC.initial_dx,IDX.name as initial_dx_name,PC.initial_dot,'
        //         . 'PC.new_initial_rx,IRX2.name as new_initial_rx_name,PC.new_initial_dx,IDX2.name as new_initial_dx_name,PC.new_initial_dot,PC.comment',
        //         'join' => array(
        //             array('care_unit CI', 'CI.id=P.care_unit_id', 'inner'),
        //             array('doctors DOC', 'DOC.id=P.doctor_id', 'inner'),
        //             array('users U', 'U.id=P.md_steward_id', 'left'),
        //             array('patient_consult PC', 'PC.patient_id=P.id', 'inner'),
        //             array('initial_rx IRX', 'IRX.id=PC.initial_rx', 'left'),
        //             array('initial_dx IDX', 'IDX.id=PC.initial_dx', 'left'),
        //             array('culture_source CS', 'CS.name=P.culture_source', 'left'),
        //             array('organism Org', 'Org.name=P.organism', 'left'),
        //             array('precautions Pre', 'Pre.name=P.precautions', 'left'),
        //             array('initial_rx IRX2', 'IRX2.id=PC.new_initial_rx', 'left'),
        //             array('initial_dx IDX2', 'IDX2.id=PC.new_initial_dx', 'left')
        //         ),
        //         'where' =>array('patient.id'=>$value)
        //     );


            // $option = array(
            //     'table' => 'patient P',
            //     'select' => 'P.id as patient_id,P.patient_id as pid,P.name as patient_name,P.date_of_start_abx,P.address,P.room_number,P.symptom_onset,P.md_stayward_consult,P.criteria_met,P.md_stayward_response,P.psa,P.created_date,'
            //         . 'P.care_unit_id,CI.name as care_unit_name,P.doctor_id,P.culture_source,P.organism,P.precautions,CS.name as culture_source_name,Org.name as organism_name,Pre.name as precautions_name,DOC.name as doctor_name,P.md_steward_id,U.first_name as md_stayward,'
            //         . 'PC.initial_rx,IRX.name as initial_rx_name,PC.initial_dx,IDX.name as initial_dx_name,PC.initial_dot,'
            //         . 'PC.new_initial_rx,IRX2.name as new_initial_rx_name,PC.new_initial_dx,IDX2.name as new_initial_dx_name,PC.new_initial_dot,PC.comment',
            //     'join' => array(
            //         array('care_unit CI', 'CI.id=P.care_unit_id', 'inner'),
            //         array('doctors DOC', 'DOC.id=P.doctor_id', 'inner'),
            //         array('users U', 'U.id=P.md_steward_id', 'left'),
            //         array('patient_consult PC', 'PC.patient_id=P.id', 'inner'),
            //         array('initial_rx IRX', 'IRX.id=PC.initial_rx', 'left'),
            //         array('initial_dx IDX', 'IDX.id=PC.initial_dx', 'left'),
            //         array('culture_source CS', 'CS.name=P.culture_source', 'left'),
            //         array('organism Org', 'Org.name=P.organism', 'left'),
            //         array('precautions Pre', 'Pre.name=P.precautions', 'left'),
            //         array('initial_rx IRX2', 'IRX2.id=PC.new_initial_rx', 'left'),
            //         array('initial_dx IDX2', 'IDX2.id=PC.new_initial_dx', 'left')
            //     ),
            //     'order' => array('P.id' => 'DESC')
            // );
    
    $careUnitData_list[] = $this->common_model->customGet($option);
}



        $UsersCareUnitID = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : '';


        $this->data['careUnitID'] = $careUnitID = (isset($_GET['careUnit'])) ? $_GET['careUnit'] : "";

        $careUnitID = (isset($_GET['careUnit'])) ? $_GET['careUnit'] : "";
        $from = (isset($_GET['date']) && !empty($_GET['date'])) ? date('Y-m-d', strtotime($_GET['date'])) : "";
        $to = (isset($_GET['date1']) && !empty($_GET['date1'])) ? date('Y-m-d', strtotime($_GET['date1'])) : "";

        if ($_GET["export"] == 'Export') {

            $this->patientExport($from, $to, $careUnitID);
            return;
        }
                // $Sql = "SELECT vendor_sale_patient.id as patient_id,vendor_sale_patient.patient_id as pid,vendor_sale_care_unit.name,vendor_sale_doctors.name as doctor_name,vendor_sale_users.first_name as md_stayward,vendor_sale_patient.date_of_start_abx FROM vendor_sale_patient JOIN vendor_sale_care_unit ON vendor_sale_care_unit.id = vendor_sale_patient.care_unit_id JOIN vendor_sale_doctors ON vendor_sale_doctors.id= vendor_sale_patient.doctor_id JOIN vendor_sale_users ON vendor_sale_users.id= vendor_sale_patient.md_steward_id  WHERE vendor_sale_patient.operator_id = $UsersCareUnitID ORDER BY `patient_id` DESC";
                if(!empty($careUnitID) and !empty($from) and !empty($to)){
                    $Sql = "SELECT vendor_sale_patient.id as patient_id,vendor_sale_patient.room_number,vendor_sale_patient.symptom_onset,vendor_sale_patient.total_days_of_patient_stay,vendor_sale_patient_consult.initial_dot,vendor_sale_patient.culture_source as culture_source_name,vendor_sale_patient.organism as organism_name,vendor_sale_patient.patient_id as pid,vendor_sale_care_unit.name,vendor_sale_doctors.name as doctor_name,vendor_sale_initial_dx.name as initial_dx_name,vendor_sale_initial_rx.name as initial_rx_name,vendor_sale_users.first_name as md_stayward,vendor_sale_patient.date_of_start_abx FROM vendor_sale_patient JOIN vendor_sale_care_unit ON vendor_sale_care_unit.id = vendor_sale_patient.care_unit_id JOIN vendor_sale_doctors ON vendor_sale_doctors.id= vendor_sale_patient.doctor_id JOIN vendor_sale_patient_consult ON vendor_sale_patient_consult.patient_id= vendor_sale_patient.id JOIN vendor_sale_initial_dx ON vendor_sale_initial_dx.id= vendor_sale_patient_consult.initial_dx JOIN vendor_sale_initial_rx ON vendor_sale_initial_rx.id= vendor_sale_patient_consult.initial_rx JOIN vendor_sale_users ON vendor_sale_users.id= vendor_sale_patient.md_steward_id  WHERE vendor_sale_patient.operator_id = $UsersCareUnitID AND vendor_sale_patient.care_unit_id = $careUnitID AND vendor_sale_patient.date_of_start_abx  >= '$from'  AND vendor_sale_patient.date_of_start_abx <= '$to' ORDER BY `patient_id` DESC";
                
            
        }else if(!empty($from) and !empty($to)){
            $Sql = "SELECT vendor_sale_patient.id as patient_id,vendor_sale_patient.room_number,vendor_sale_patient.symptom_onset,vendor_sale_patient.total_days_of_patient_stay,vendor_sale_patient_consult.initial_dot,vendor_sale_patient.culture_source as culture_source_name,vendor_sale_patient.organism as organism_name,vendor_sale_patient.patient_id as pid,vendor_sale_care_unit.name,vendor_sale_doctors.name as doctor_name,vendor_sale_initial_dx.name as initial_dx_name,vendor_sale_initial_rx.name as initial_rx_name,vendor_sale_users.first_name as md_stayward,vendor_sale_patient.date_of_start_abx FROM vendor_sale_patient JOIN vendor_sale_care_unit ON vendor_sale_care_unit.id = vendor_sale_patient.care_unit_id JOIN vendor_sale_doctors ON vendor_sale_doctors.id= vendor_sale_patient.doctor_id JOIN vendor_sale_patient_consult ON vendor_sale_patient_consult.patient_id= vendor_sale_patient.id JOIN vendor_sale_initial_dx ON vendor_sale_initial_dx.id= vendor_sale_patient_consult.initial_dx JOIN vendor_sale_initial_rx ON vendor_sale_initial_rx.id= vendor_sale_patient_consult.initial_rx JOIN vendor_sale_users ON vendor_sale_users.id= vendor_sale_patient.md_steward_id  WHERE vendor_sale_patient.operator_id = $UsersCareUnitID AND  vendor_sale_patient.date_of_start_abx  >= '$from'  AND vendor_sale_patient.date_of_start_abx <= '$to' ORDER BY `patient_id` DESC";
        }else if(!empty($careUnitID)){


            $Sql = "SELECT vendor_sale_patient.id as patient_id,vendor_sale_patient.room_number,vendor_sale_patient.symptom_onset,vendor_sale_patient.total_days_of_patient_stay,vendor_sale_patient_consult.initial_dot,vendor_sale_patient.culture_source as culture_source_name,vendor_sale_patient.organism as organism_name,vendor_sale_patient.patient_id as pid,vendor_sale_care_unit.name,vendor_sale_doctors.name as doctor_name,vendor_sale_initial_dx.name as initial_dx_name,vendor_sale_initial_rx.name as initial_rx_name,vendor_sale_users.first_name as md_stayward,vendor_sale_patient.date_of_start_abx FROM vendor_sale_patient JOIN vendor_sale_care_unit ON vendor_sale_care_unit.id = vendor_sale_patient.care_unit_id JOIN vendor_sale_doctors ON vendor_sale_doctors.id= vendor_sale_patient.doctor_id JOIN vendor_sale_patient_consult ON vendor_sale_patient_consult.patient_id= vendor_sale_patient.id JOIN vendor_sale_initial_dx ON vendor_sale_initial_dx.id= vendor_sale_patient_consult.initial_dx JOIN vendor_sale_initial_rx ON vendor_sale_initial_rx.id= vendor_sale_patient_consult.initial_rx JOIN vendor_sale_users ON vendor_sale_users.id= vendor_sale_patient.md_steward_id  WHERE vendor_sale_patient.operator_id = $UsersCareUnitID AND vendor_sale_patient.care_unit_id = $careUnitID ORDER BY `patient_id` DESC";
        }
        
        else {

            $Sql = "SELECT vendor_sale_patient.id as patient_id,vendor_sale_patient.room_number,vendor_sale_patient.symptom_onset,vendor_sale_patient.total_days_of_patient_stay,vendor_sale_patient_consult.initial_dot,vendor_sale_patient.culture_source as culture_source_name,vendor_sale_patient.organism as organism_name,vendor_sale_patient.patient_id as pid,vendor_sale_care_unit.name,vendor_sale_doctors.name as doctor_name,vendor_sale_initial_dx.name as initial_dx_name,vendor_sale_initial_rx.name as initial_rx_name,vendor_sale_users.first_name as md_stayward,vendor_sale_patient.date_of_start_abx FROM vendor_sale_patient JOIN vendor_sale_care_unit ON vendor_sale_care_unit.id = vendor_sale_patient.care_unit_id JOIN vendor_sale_doctors ON vendor_sale_doctors.id= vendor_sale_patient.doctor_id JOIN vendor_sale_patient_consult ON vendor_sale_patient_consult.patient_id= vendor_sale_patient.id JOIN vendor_sale_initial_dx ON vendor_sale_initial_dx.id= vendor_sale_patient_consult.initial_dx JOIN vendor_sale_initial_rx ON vendor_sale_initial_rx.id= vendor_sale_patient_consult.initial_rx JOIN vendor_sale_users ON vendor_sale_users.id= vendor_sale_patient.md_steward_id  WHERE vendor_sale_patient.operator_id = $UsersCareUnitID ORDER BY `patient_id` DESC";

        }

                $careunit_facility_counts = $this->common_model->customQuery($Sql);


        // $arraySingles = call_user_func_array('array_merge', $careUnitData_list);
        $this->data['careUnitsUser_list'] = $careunit_facility_counts;



        // $this->data['careUnitID'] = $careUnitID = (isset($_GET['careUnit'])) ? $_GET['careUnit'] : "";

        // $careUnitID = (isset($_GET['careUnit'])) ? $_GET['careUnit'] : "";

        // print_r($UsersCareUnitID);die;
        // $from = (isset($_GET['date']) && !empty($_GET['date'])) ? date('Y-m-d', strtotime($_GET['date'])) : "";
        // $to = (isset($_GET['date1']) && !empty($_GET['date1'])) ? date('Y-m-d', strtotime($_GET['date1'])) : "";


        // if ($_GET["export"] == 'Export') {

        //     $this->patientExport($from, $to, $careUnitID);
        //     return;
        // }

        $option = array(
            'table' => 'patient P',
            'select' => 'P.id as patient_id,P.patient_id as pid,P.name as patient_name,P.date_of_start_abx,P.address,P.total_days_of_patient_stay,P.room_number,P.symptom_onset,P.md_stayward_consult,P.criteria_met,P.md_stayward_response,P.psa,P.created_date,'
                . 'P.care_unit_id,CI.name as care_unit_name,P.doctor_id,P.culture_source,P.organism,P.precautions,CS.name as culture_source_name,Org.name as organism_name,Pre.name as precautions_name,DOC.name as doctor_name,P.md_steward_id,U.first_name as md_stayward,'
                . 'PC.initial_rx,IRX.name as initial_rx_name,PC.initial_dx,IDX.name as initial_dx_name,PC.initial_dot,'
                . 'PC.new_initial_rx,IRX2.name as new_initial_rx_name,PC.new_initial_dx,IDX2.name as new_initial_dx_name,PC.new_initial_dot,PC.comment',
            'join' => array(
                array('care_unit CI', 'CI.id=P.care_unit_id', 'inner'),
                array('doctors DOC', 'DOC.id=P.doctor_id', 'inner'),
                array('users U', 'U.id=P.md_steward_id', 'left'),
                array('patient_consult PC', 'PC.patient_id=P.id', 'inner'),
                array('initial_rx IRX', 'IRX.id=PC.initial_rx', 'left'),
                array('initial_dx IDX', 'IDX.id=PC.initial_dx', 'left'),
                array('culture_source CS', 'CS.name=P.culture_source', 'left'),
                array('organism Org', 'Org.name=P.organism', 'left'),
                array('precautions Pre', 'Pre.name=P.precautions', 'left'),
                array('initial_rx IRX2', 'IRX2.id=PC.new_initial_rx', 'left'),
                array('initial_dx IDX2', 'IDX2.id=PC.new_initial_dx', 'left')
            ),
            //'group_by' => 'P.patient_id'
            'group_by' => 'pid'
        );
        if (!empty($careUnitID)) {
            $option['where']['P.care_unit_id'] = $careUnitID;
        }
        if (!empty($AdminCareUnitID)) {
            $option['where']['P.care_unit_id']  = $AdminCareUnitID;
        }
        if (!empty($from)) {
            $option['where']['DATE(P.date_of_start_abx) >='] = $from;
        }
        if (!empty($to)) {
            $option['where']['DATE(P.date_of_start_abx) <='] = $to;
        }
        $option['order'] = array('P.id' => 'desc');

        /* print_r($option);die; */
        $this->data['list'] = $this->common_model->customGet($option);
 
       // print_r($careUnitID);die;
        $this->load->admin_render('list', $this->data, 'inner_script');
    }

    /**
     * @method index
     * @description listing display
     * @return array
     */
    public function existing_list($patient_ids)
    {
        $this->data['url'] = base_url() . $this->router->fetch_class();
        $this->data['pageTitle'] = "Add " . $this->title;
        $this->data['parent'] = $this->router->fetch_class();
        $this->data['model'] = $this->router->fetch_class();
        $this->data['title'] = $this->title;
        $this->data['tablePrefix'] = 'vendor_sale_' . $this->_table;
        $this->data['model'] = 'patient/open_model';
        $this->data['table'] = $this->_table;
        // $patient_ids = $this->db->get('patient_id');
        $option = array('table' => 'care_unit', 'where' => array('delete_status' => 0, 'is_active' => 1));
        $this->data['careUnit'] = $this->common_model->customGet($option);

        $this->data['careUnitID'] = $careUnitID = (isset($_GET['careUnit'])) ? $_GET['careUnit'] : "";
        $option = array(
            'table' => 'patient P',
            'select' => 'P.id as patient_id,P.patient_id as pid,P.name as patient_name,P.date_of_start_abx,P.total_days_of_patient_stay,P.address,P.room_number,P.symptom_onset,P.md_stayward_consult,P.criteria_met,P.md_stayward_response,P.psa,P.created_date,'
                . 'P.care_unit_id,CI.name as care_unit_name,P.doctor_id,P.culture_source,P.organism,P.precautions,CS.name as culture_source_name,Org.name as organism_name,Pre.name as precautions_name,DOC.name as doctor_name,P.md_steward_id,U.first_name as md_stayward,'
                . 'PC.initial_rx,IRX.name as initial_rx_name,PC.initial_dx,IDX.name as initial_dx_name,PC.initial_dot,'
                . 'PC.new_initial_rx,IRX2.name as new_initial_rx_name,PC.new_initial_dx,IDX2.name as new_initial_dx_name,PC.new_initial_dot,PC.comment',
            'join' => array(
                array('care_unit CI', 'CI.id=P.care_unit_id', 'inner'),
                array('doctors DOC', 'DOC.id=P.doctor_id', 'inner'),
                array('users U', 'U.id=P.md_steward_id', 'left'),
                array('patient_consult PC', 'PC.patient_id=P.id', 'inner'),
                array('initial_rx IRX', 'IRX.id=PC.initial_rx', 'left'),
                array('initial_dx IDX', 'IDX.id=PC.initial_dx', 'left'),
                array('culture_source CS', 'CS.name=P.culture_source', 'left'),
                array('organism Org', 'Org.name=P.organism', 'left'),
                array('precautions Pre', 'Pre.name=P.precautions', 'left'),
                array('initial_rx IRX2', 'IRX2.id=PC.new_initial_rx', 'left'),
                array('initial_dx IDX2', 'IDX2.id=PC.new_initial_dx', 'left')
            ),
            'order' => array('P.id' => 'DESC')
        );
        $option['where']['P.patient_id'] = $patient_ids;
        if (!empty($careUnitID)) {
            $option['where']['P.care_unit_id'] = $careUnitID;
        }
        $option['order'] = array('P.id' => 'desc');
        $this->data['list'] = $this->common_model->customGet($option);
        // print_r($this->common_model->customGet($option));die;
        $this->load->admin_render('existing_list', $this->data, 'inner_script');
    }

    /**
     * @method open_model
     * @description load model box
     * @return array
     */
    function open_model()
    {
        $this->data['title'] = "Add " . $this->title;
        $this->data['formUrl'] = $this->router->fetch_class() . "/add";
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
        //print_r($arraySingle);die;
        // print_r($this->data['careUnitsUser']);die;


        $this->data['initial_dx'] = $this->common_model->customGet(array('table' => 'initial_dx', 'select' => 'id,name', 'where' => array('is_active' => 1, 'delete_status' => 0), 'order' => array('name' => 'asc')));
        $this->data['culture_source'] = $this->common_model->customGet(array('table' => 'culture_source', 'select' => 'id,name', 'where' => array('is_active' => 1, 'delete_status' => 0), 'order' => array('name' => 'asc')));
        $this->data['organism'] = $this->common_model->customGet(array('table' => 'organism', 'select' => 'id,name', 'where' => array('is_active' => 1, 'delete_status' => 0), 'order' => array('name' => 'asc')));
        $this->data['precautions'] = $this->common_model->customGet(array('table' => 'precautions', 'select' => 'id,name', 'where' => array('is_active' => 1, 'delete_status' => 0), 'order' => array('name' => 'asc')));
        $this->data['initial_rx'] = $this->common_model->customGet(array('table' => 'initial_rx', 'select' => 'id,name', 'where' => array('is_active' => 1, 'delete_status' => 0), 'order' => array('name' => 'asc')));
        $this->data['doctors'] = $this->common_model->customGet(array('table' => 'doctors', 'select' => 'id,name', 'where' => array('is_active' => 1, 'delete_status' => 0), 'order' => array('name' => 'asc')));
        // $option = array(
        //     'table' => 'users U',
        //     'select' => 'U.id,CONCAT(first_name," ",last_name) name',
        //     'join' => array('users_groups as UG' => 'UG.user_id=U.id'),
        //     'where' => array('U.active' => 1, 'U.delete_status' => 0, 'UG.group_id' => 3),
        //     'order' => array('first_name' => 'asc')
        // );
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

         $option2 = array('table' => USERS . ' as user',
                    'select' => 'user.*,group.name as group_name,UP.doc_file',
                    'join' => array(array(USER_GROUPS . ' as ugroup', 'ugroup.user_id=user.id', 'left'),
                        array(GROUPS . ' as group', 'group.id=ugroup.group_id', 'left'),
                        array('user_profile UP', 'UP.user_id=user.id', 'left')),
                    'order' => array('user.id' => 'ASC'),
                    'where' => array('user.delete_status' => 0,
                        'group.id' => 3),
                    'order' => array('user.first_name' => 'ASC')
                );

                $this->data['stawardss'] = $this->common_model->customGet($option2);
        $this->load->view('add', $this->data);
    }

    /**
     * @method patientExport
     * @description export patient list
     * @return array
     */
    function patientExport($from, $to, $careUnitID)
    {
        /* $careUnitID = (isset($_GET['careUnitID'])) ? $_GET['careUnitID'] : "";
        $from = (isset($_GET['date']) && !empty($_GET['date'])) ? date('Y-m-d', strtotime($_GET['date'])) : "";
        $to = (isset($_GET['date1']) && !empty($_GET['date1'])) ? date('Y-m-d', strtotime($_GET['date1'])) : "";  */

        /* print_r($careUnitID);
        print_r($from);
        print_r($to);die; */
        $UsersCareUnitID = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : '';
        if($this->ion_auth->is_admin() OR $this->ion_auth->is_subAdmin()){
        $option = array(
            'table' => 'patient P',
            'select' => 'P.infection_surveillance_checklist,P.total_days_of_patient_stay,P.patient_id as patient_id,P.name as patient_name,P.address,P.room_number,P.symptom_onset,P.md_stayward_consult,P.criteria_met,P.md_stayward_response,P.psa,P.created_date,'
                . 'P.care_unit_id,CI.name as care_unit_name,P.doctor_id,P.culture_source,P.organism,P.precautions,Pre.name as precautions_name,CS.name as culture_source_name,Org.name as organism_name,DOC.name as doctor_name,P.md_steward_id,U.first_name as md_stayward,'
                . 'PC.initial_rx,IRX.name as initial_rx_name,PC.initial_dx,IDX.name as initial_dx_name,PC.initial_dot,'
                . 'PC.new_initial_rx,IRX2.name as new_initial_rx_name,PC.new_initial_dx,IDX2.name as new_initial_dx_name,PC.new_initial_dot,IRX.price as price1,IRX2.price as price2,P.created_date,P.date_of_start_abx',
            'join' => array(
                array('care_unit CI', 'CI.id=P.care_unit_id', 'inner'),
                array('doctors DOC', 'DOC.id=P.doctor_id', 'inner'),
                array('users U', 'U.id=P.md_steward_id', 'inner'),
                array('patient_consult PC', 'PC.patient_id=P.id', 'inner'),
                array('initial_rx IRX', 'IRX.id=PC.initial_rx', 'left'),
                array('initial_dx IDX', 'IDX.id=PC.initial_dx', 'left'),
                array('culture_source CS', 'CS.name=P.culture_source', 'left'),
                array('organism Org', 'Org.name=P.organism', 'left'),
                array('precautions Pre', 'Pre.name=P.precautions', 'left'),
                array('initial_rx IRX2', 'IRX2.id=PC.new_initial_rx', 'left'),
                array('initial_dx IDX2', 'IDX2.id=PC.new_initial_dx', 'left')
            )
        );
        if (!empty($careUnitID)) {
            $option['where']['P.care_unit_id'] = $careUnitID;
        }
        if (!empty($from)) {
            $option['where']['DATE(P.date_of_start_abx) >='] = $from;
        }
        if (!empty($to)) {
            $option['where']['DATE(P.date_of_start_abx) <='] = $to;
        }
        $AdminCareUnitID = isset($_SESSION['admin_care_unit_id']) ? $_SESSION['admin_care_unit_id'] : '';
        if (!empty($AdminCareUnitID)) {
            $option['where']['P.care_unit_id']  = $AdminCareUnitID;
        }
        $option['order'] = array('P.name' => 'asc');
        $patientList = $this->common_model->customGet($option);


    }else{

        if(!empty($careUnitID) and !empty($from) and !empty($to)){


            $Sql = "SELECT vendor_sale_patient.id,vendor_sale_patient.patient_id ,vendor_sale_care_unit.name,vendor_sale_patient.room_number,vendor_sale_patient.symptom_onset,vendor_sale_patient.culture_source,vendor_sale_patient.organism,vendor_sale_patient.md_stayward_response,vendor_sale_patient.psa,vendor_sale_initial_dx.name as initial_dx_name,vendor_sale_culture_source.name as culture_source_name,vendor_sale_organism.name as organism_name,vendor_sale_precautions.name as precautions_name,ird2.name as new_initial_dx_name,vendor_sale_initial_rx.name as initial_rx_name,irx2.name as new_initial_rx_name,vendor_sale_patient_consult.initial_dot,vendor_sale_patient_consult.new_initial_dot,vendor_sale_doctors.name as doctor_name,vendor_sale_users.first_name as md_stayward,vendor_sale_patient.date_of_start_abx FROM vendor_sale_patient JOIN vendor_sale_care_unit ON vendor_sale_care_unit.id = vendor_sale_patient.care_unit_id JOIN vendor_sale_patient_consult ON vendor_sale_patient_consult.patient_id= vendor_sale_patient.id JOIN vendor_sale_doctors ON vendor_sale_doctors.id= vendor_sale_patient.doctor_id JOIN vendor_sale_initial_dx ON vendor_sale_initial_dx.id= vendor_sale_patient_consult.initial_dx JOIN vendor_sale_culture_source ON vendor_sale_culture_source.name= vendor_sale_patient.culture_source JOIN vendor_sale_organism ON vendor_sale_organism.name= vendor_sale_patient.organism JOIN vendor_sale_precautions ON vendor_sale_precautions.name= vendor_sale_patient.precautions LEFT JOIN vendor_sale_initial_dx ird2 ON ird2.id= vendor_sale_patient_consult.new_initial_dx JOIN vendor_sale_initial_rx ON vendor_sale_initial_rx.id= vendor_sale_patient_consult.initial_rx LEFT JOIN vendor_sale_initial_rx irx2 ON irx2.id= vendor_sale_patient_consult.new_initial_rx JOIN vendor_sale_users ON vendor_sale_users.id= vendor_sale_patient.md_steward_id  WHERE vendor_sale_patient.operator_id = $UsersCareUnitID AND vendor_sale_patient.care_unit_id = $careUnitID AND vendor_sale_patient.date_of_start_abx  >= '$from'  AND vendor_sale_patient.date_of_start_abx <= '$to' ORDER BY `patient_id` ASC";
    }
     else if(!empty($from) and !empty($to)){


        $Sql = "SELECT vendor_sale_patient.id,vendor_sale_patient.patient_id ,vendor_sale_care_unit.name,vendor_sale_patient.room_number,vendor_sale_patient.symptom_onset,vendor_sale_patient.culture_source,vendor_sale_patient.organism,vendor_sale_patient.md_stayward_response,vendor_sale_patient.psa,vendor_sale_initial_dx.name as initial_dx_name,vendor_sale_culture_source.name as culture_source_name,vendor_sale_organism.name as organism_name,vendor_sale_precautions.name as precautions_name,ird2.name as new_initial_dx_name,vendor_sale_initial_rx.name as initial_rx_name,irx2.name as new_initial_rx_name,vendor_sale_patient_consult.initial_dot,vendor_sale_patient_consult.new_initial_dot,vendor_sale_doctors.name as doctor_name,vendor_sale_users.first_name as md_stayward,vendor_sale_patient.date_of_start_abx FROM vendor_sale_patient JOIN vendor_sale_care_unit ON vendor_sale_care_unit.id = vendor_sale_patient.care_unit_id JOIN vendor_sale_patient_consult ON vendor_sale_patient_consult.patient_id= vendor_sale_patient.id JOIN vendor_sale_doctors ON vendor_sale_doctors.id= vendor_sale_patient.doctor_id JOIN vendor_sale_initial_dx ON vendor_sale_initial_dx.id= vendor_sale_patient_consult.initial_dx JOIN vendor_sale_culture_source ON vendor_sale_culture_source.name= vendor_sale_patient.culture_source JOIN vendor_sale_organism ON vendor_sale_organism.name= vendor_sale_patient.organism JOIN vendor_sale_precautions ON vendor_sale_precautions.name= vendor_sale_patient.precautions LEFT JOIN vendor_sale_initial_dx ird2 ON ird2.id= vendor_sale_patient_consult.new_initial_dx JOIN vendor_sale_initial_rx ON vendor_sale_initial_rx.id= vendor_sale_patient_consult.initial_rx LEFT JOIN vendor_sale_initial_rx irx2 ON irx2.id= vendor_sale_patient_consult.new_initial_rx JOIN vendor_sale_users ON vendor_sale_users.id= vendor_sale_patient.md_steward_id  WHERE vendor_sale_patient.operator_id = $UsersCareUnitID AND vendor_sale_patient.date_of_start_abx  >= '$from'  AND vendor_sale_patient.date_of_start_abx <= '$to' ORDER BY `patient_id` ASC";
}

        else if(!empty($careUnitID)){


            $Sql = "SELECT vendor_sale_patient.id,vendor_sale_patient.patient_id ,vendor_sale_care_unit.name,vendor_sale_patient.room_number,vendor_sale_patient.symptom_onset,vendor_sale_patient.culture_source,vendor_sale_patient.organism,vendor_sale_patient.md_stayward_response,vendor_sale_patient.psa,vendor_sale_initial_dx.name as initial_dx_name,vendor_sale_culture_source.name as culture_source_name,
            vendor_sale_organism.name as organism_name,vendor_sale_precautions.name as precautions_name,ird2.name as new_initial_dx_name,vendor_sale_initial_rx.name as initial_rx_name,irx2.name as new_initial_rx_name,vendor_sale_patient_consult.initial_dot,vendor_sale_patient_consult.new_initial_dot,vendor_sale_doctors.name as doctor_name,vendor_sale_users.first_name as md_stayward,vendor_sale_patient.date_of_start_abx FROM vendor_sale_patient JOIN vendor_sale_care_unit ON vendor_sale_care_unit.id = vendor_sale_patient.care_unit_id JOIN vendor_sale_patient_consult ON vendor_sale_patient_consult.patient_id= vendor_sale_patient.id JOIN vendor_sale_doctors ON vendor_sale_doctors.id= vendor_sale_patient.doctor_id JOIN vendor_sale_initial_dx ON vendor_sale_initial_dx.id= vendor_sale_patient_consult.initial_dx JOIN vendor_sale_culture_source ON vendor_sale_culture_source.name= vendor_sale_patient.culture_source JOIN vendor_sale_organism ON vendor_sale_organism.name= vendor_sale_patient.organism JOIN vendor_sale_precautions ON vendor_sale_precautions.name= vendor_sale_patient.precautions LEFT JOIN vendor_sale_initial_dx ird2 ON ird2.id= vendor_sale_patient_consult.new_initial_dx JOIN vendor_sale_initial_rx ON vendor_sale_initial_rx.id= vendor_sale_patient_consult.initial_rx LEFT JOIN vendor_sale_initial_rx irx2 ON irx2.id= vendor_sale_patient_consult.new_initial_rx JOIN vendor_sale_users ON vendor_sale_users.id= vendor_sale_patient.md_steward_id  WHERE vendor_sale_patient.operator_id = $UsersCareUnitID AND vendor_sale_patient.care_unit_id = $careUnitID ORDER BY `patient_id` ASC";
    }else{

        $Sql = "SELECT vendor_sale_patient.id,vendor_sale_patient.patient_id ,vendor_sale_care_unit.name,vendor_sale_patient.room_number,vendor_sale_patient.symptom_onset,vendor_sale_patient.culture_source,vendor_sale_patient.organism,vendor_sale_patient.md_stayward_response,vendor_sale_patient.psa,vendor_sale_initial_dx.name as initial_dx_name,vendor_sale_culture_source.name as culture_source_name,vendor_sale_organism.name as organism_name,vendor_sale_precautions.name as precautions_name,ird2.name as new_initial_dx_name,vendor_sale_initial_rx.name as initial_rx_name,irx2.name as new_initial_rx_name,vendor_sale_patient_consult.initial_dot,vendor_sale_patient_consult.new_initial_dot,vendor_sale_doctors.name as doctor_name,vendor_sale_users.first_name as md_stayward,vendor_sale_patient.date_of_start_abx FROM vendor_sale_patient JOIN vendor_sale_care_unit ON vendor_sale_care_unit.id = vendor_sale_patient.care_unit_id JOIN vendor_sale_patient_consult ON vendor_sale_patient_consult.patient_id= vendor_sale_patient.id JOIN vendor_sale_doctors ON vendor_sale_doctors.id= vendor_sale_patient.doctor_id JOIN vendor_sale_initial_dx ON vendor_sale_initial_dx.id= vendor_sale_patient_consult.initial_dx JOIN vendor_sale_culture_source ON vendor_sale_culture_source.name= vendor_sale_patient.culture_source JOIN vendor_sale_organism ON vendor_sale_organism.name= vendor_sale_patient.organism JOIN vendor_sale_precautions ON vendor_sale_precautions.name= vendor_sale_patient.precautions LEFT JOIN vendor_sale_initial_dx ird2 ON ird2.id= vendor_sale_patient_consult.new_initial_dx JOIN vendor_sale_initial_rx ON vendor_sale_initial_rx.id= vendor_sale_patient_consult.initial_rx LEFT JOIN vendor_sale_initial_rx irx2 ON irx2.id= vendor_sale_patient_consult.new_initial_rx JOIN vendor_sale_users ON vendor_sale_users.id= vendor_sale_patient.md_steward_id  WHERE vendor_sale_patient.operator_id = $UsersCareUnitID ORDER BY `patient_id` ASC";
/* 
        $Sql = "SELECT vendor_sale_patient.id ,vendor_sale_patient.patient_id ,vendor_sale_care_unit.name,vendor_sale_patient.symptom_onset,vendor_sale_patient.culture_source,vendor_sale_patient.organism,vendor_sale_doctors.name as doctor_name,vendor_sale_users.first_name as md_stayward,vendor_sale_patient.date_of_start_abx FROM vendor_sale_patient JOIN vendor_sale_care_unit ON vendor_sale_care_unit.id = vendor_sale_patient.care_unit_id JOIN vendor_sale_doctors ON vendor_sale_doctors.id= vendor_sale_patient.doctor_id JOIN vendor_sale_users ON vendor_sale_users.id= vendor_sale_patient.md_steward_id  WHERE vendor_sale_patient.operator_id = $UsersCareUnitID ORDER BY `patient_id` ASC"; */

    }

            $patientList = $this->common_model->customQuery($Sql);

    }


        // print_r($patientList);die;
        $print_array = array();
        if (!empty($patientList)) {
            foreach ($patientList as $Row) {


                if($Row->md_stayward_response=='NoResponse'){
                    $x="Neutral";
                }
                if($Row->md_stayward_response==''){
                    $x="Neutral";
                }
                if($Row->md_stayward_response=='Agree'){
                    $x="Agree";
                }
                if($Row->md_stayward_response=='Disagree'){
                    $x="Disagree";
                }
                if($Row->md_stayward_response=='Modify'){
                    $x="Modify";
                }

                if($Row->psa=='NoResponse'){
                    $y="No Response";
                }
                if($Row->psa=='Neutral'){
                    $y="Neutral";
                }
                if($Row->psa==''){
                    $y="Neutral";
                }
                if($Row->psa=='Agree'){
                    $y="Agree";
                }
                if($Row->psa=='Disagree'){
                 $y="Disagree";
                }


                if($Row->symptom_onset == 'Facility'){
                    $symptom_onset = 'Facility/HAI';
                }else if($Row->symptom_onset == 'Hospital'){
                    $symptom_onset = 'Hospital/CAI';
                }else{
                    $symptom_onset = '';
                }


                if($Row->precautions_name == 'Airborne'){
                    $precautions = 'A';
                }else if($Row->precautions_name == 'Contact'){
                    $precautions = 'C';
                }else if($Row->precautions_name == 'Droplet'){
                    $precautions = 'D';
                }else if($Row->precautions_name == 'N/A'){
                    $precautions = 'N/A';
                }

                if($Row->room_number == 'NULL'){
                    $room_number = 'NA';
                }else if($Row->room_number == 'NA'){
                    $room_number = 'NA';
                }else if($Row->room_number == null){
                    $room_number = 'NA';
                }else{
                    $room_number = $Row->room_number;
                }

                $print_array[] = array(
                    //'CreateDate' => date('m/d/Y h:i:s A', strtotime($Row->created_date)),
                    'PatientID' => $Row->patient_id,
                    // 'CareUnitName' => $Row->care_unit_name,
                    // 'PatientName' => $Row->patient_name,
                    // 'PatientAddress' => $Row->address,
                    'RoomNumber' => $room_number,
                    'SymptomOnset' => $symptom_onset,
                    'CultureSource' => $Row->culture_source_name,
                    'Organism' => $Row->organism_name,
                    //'Precautions' => $Row->precautions_name,
                    'Precautions' => $precautions,
                    'DateOfStartAbx' => date('m/d/Y', strtotime($Row->date_of_start_abx)),
                    'DoctorName' => $Row->doctor_name,
                    'InitialDX' => $Row->initial_dx_name,
                    'InitialRX' => $Row->initial_rx_name,
                    'InitialDOT' => $Row->initial_dot,
                    //'MDStewardConsult' => $Row->md_stayward_consult,
                    'MDSteward' => $Row->md_stayward,

                    /* $x=(!empty($Row->md_stayward_response ? $Row->md_stayward_response=='NoResponse':"Neutral")) ? $Row->md_stayward_response :"Neutral",
 */
                   
                    'MDStewardResponse' => $x,
                    'PSA' => $y,
                    'NewInitialDX' => $Row->new_initial_dx_name,
                    'NewInitialRX' => $Row->new_initial_rx_name,
                    'NewInitialDOT' => $Row->new_initial_dot
                    //'total_days_of_patient_stay' => $Row->total_days_of_patient_stay,
                    //'infection_surveillance_checklist' => $Row->infection_surveillance_checklist,
                    //'price1' => $Row->price1,
                    //'price2' => $Row->price2,
                );
            }
            if (!empty($print_array)) {
                //$fp = fopen('PatientList.csv', 'w');
                $HeaderTitle = array(
                    //'Admission Date',
                    'PatientID',
                    // 'Care Unit Name',
                    // 'Patient Name',
                    // 'Patient Address',
                    'Room Number',
                    'Infection Onset',
                    'Culture Source',
                    'Organism',
                    'Precautions',
                    'Date Of Start ABX',
                    'Provider Doctor',
                    'Diagnosis',
                    'Antibiotic Name',
                    //'Initial DOT',
                    'Days of Therapy',
                   // 'MD Steward Consult',
                    'MD Steward',
                    'MD Steward Response',
                    'PSA',
                    'New Diagnosis',
                    'New Antibiotic Name',
                    //'New Initial DOT'
                    'New Days of Therapy',
                    //'Total Days Of Patient Stay',
                    //'Infection Surveillance Checklist',
                    //'Antibiotic Price',
                    //'New Antibiotic Price',


                );

                $filename = "PatientList.csv";
                $fp = fopen('php://output', 'w');

                header('Content-type: application/csv');
                header('Content-Disposition: attachment; filename=' . $filename);
                fputcsv($fp, $HeaderTitle);
                foreach ($print_array as $row) {
                    fputcsv($fp, $row);
                }
                readfile($filename);
            }
        } else {
            if (!empty($created_date)) {
                $this->session->set_flashdata('error', "Records not found for this date " . $_GET['date']);
            } else {
                $this->session->set_flashdata('error', "Records not found");
            }

            redirect('patient');
        }
    }

    function patientImport()
    {
        if (!empty($_POST['careUnit'])) {
            if (!empty($_FILES['patientFile']['name'])) {
                $fileNameExt = $_FILES["patientFile"]["name"];
                $ext = pathinfo($fileNameExt, PATHINFO_EXTENSION);
                if (strtolower($ext) == "csv") {
                    if ($_FILES["patientFile"]["size"] > 0) {
                        $fileName = $_FILES["patientFile"]["tmp_name"];
                        $file = fopen($fileName, "r");
                        $InsertArray = array();
                        while (($column = fgetcsv($file, 10000, ",")) !== FALSE) {
                            if ($column[0] != "PatientID") {
                                $InsertArray[] = array(
                                    'PatientID' => $column[0],
                                    'CareUnitName' => $column[1],
                                    'PatientName' => $column[2],
                                    'PatientAddress' => $column[3],
                                    'SymptomOnset' => $column[4],
                                    'CultureSource' => $column[5],
                                    'Organism' => $column[6],
                                   // 'MDStewardConsult' => $column[5],
                                    'MDStewardResponse' => $column[7],
                                    'PSA' => $column[8],
                                    'TotalDaysOfPatientStay' => $column[9],
                                    'InfectionSurveillanceChecklist' => $column[10],
                                    'DoctorName' => $column[11],
                                    'MDSteward' => $column[12],
                                    'InitialRX' => $column[13],
                                    'InitialDX' => $column[14],
                                    'InitialDOT' => $column[15],
                                    'NewInitialRX' => $column[16],
                                    'NewInitiaDX' => $column[17],
                                    'NewInitialDOT' => $column[18],
                                );
                            }
                        }
                    }
                } else {
                    $this->session->set_flashdata('error', "Invalid file, Please select CSV file.");
                    redirect('patient');
                }
            } else {
                $this->session->set_flashdata('error', "Invalid file, Please select CSV file.");
                redirect('patient');
            }
            //dump($InsertArray);
            if (!empty($InsertArray)) {
                foreach ($InsertArray as $Row) {

                    $doctor_id = "";
                    $doctorName = ucwords(trim($Row['DoctorName']));
                    $Query = $this->db->query("SELECT id FROM vendor_sale_doctors WHERE name = '" . $doctorName . "' limit 1");
                    $result = $Query->row_array();
                    if (!empty($result)) {
                        $doctor_id = $result['id'];
                    }
                    $md_steward_id = "";
                    $doctorName = ucwords(trim($Row['MDSteward']));
                    $Query = $this->db->query("SELECT id FROM vendor_sale_users WHERE full_name = '" . $doctorName . "' limit 1");
                    $result = $Query->row_array();
                    if (!empty($result)) {
                        $md_steward_id = $result['id'];
                    }
                    if (!empty($doctor_id) && !empty($md_steward_id)) {
                        $result = array();
                        if (!empty($Row['PatientID'])) {
                            $Query = $this->db->query("SELECT id FROM vendor_sale_patient WHERE patient_id = '" . $Row['PatientID'] . "' limit 1");
                            $result = $Query->row_array();
                        }
                        if (empty($result)) {
                            $option = array(
                                'table' => 'patient',
                                'data' => array(
                                    'patient_id' => randomUnique(),
                                    'name' => ucwords($Row['PatientName']),
                                    'address' => ucwords($Row['PatientAddress']),
                                    'symptom_onset' => $Row['SymptomOnset'],
                                    'CultureSource' => $Row['culture_source'],
                                    'Organism' => $Row['organism'],
                                    'total_days_of_patient_stay' => $Row['TotalDaysOfPatientStay'],
                                    'infection_surveillance_checklist' => $Row['InfectionSurveillanceChecklist'],
                                    'care_unit_id' => $_POST['careUnit'],
                                    'doctor_id' => $doctor_id,
                                    'md_steward_id' => $md_steward_id,
                                  //  'md_stayward_consult' => ucfirst($Row['MDStewardConsult']),
                                    'md_stayward_response' => ucfirst($Row['MDStewardResponse']),
                                    'psa' => ucfirst($Row['PSA']),
                                    'created_date' => date('Y-m-d H:i:s')
                                )
                            );
                            $patient_id = $this->common_model->customInsert($option);
                            if (!empty($patient_id)) {

                                $InitialDXID = null;
                                $InitialDX = ucwords(trim($Row['InitialDX']));
                                $Query = $this->db->query("SELECT id FROM vendor_sale_initial_dx WHERE name = '" . $InitialDX . "' limit 1");
                                $result = $Query->row_array();
                                if (!empty($result)) {
                                    $InitialDXID = $result['id'];
                                }

                                $InitialRXID = null;
                                $InitialRX = ucwords(trim($Row['InitialRX']));
                                $Query = $this->db->query("SELECT id FROM vendor_sale_initial_rx WHERE name = '" . $InitialRX . "' limit 1");
                                $result = $Query->row_array();
                                if (!empty($result)) {
                                    $InitialRXID = $result['id'];
                                }


                                $NewInitiaDXID = null;
                                $NewInitiaDX = ucwords(trim($Row['NewInitiaDX']));
                                $Query = $this->db->query("SELECT id FROM vendor_sale_initial_dx WHERE name = '" . $NewInitiaDX . "' limit 1");
                                $result = $Query->row_array();
                                if (!empty($result)) {
                                    $NewInitiaDXID = $result['id'];
                                }

                                $NewInitialRXID = null;
                                $NewInitialRX = ucwords(trim($Row['NewInitialRX']));
                                $Query = $this->db->query("SELECT id FROM vendor_sale_initial_rx WHERE name = '" . $NewInitialRX . "' limit 1");
                                $result = $Query->row_array();
                                if (!empty($result)) {
                                    $NewInitialRXID = $result['id'];
                                }
                                $option = array(
                                    'table' => 'patient_consult',
                                    'data' => array(
                                        'patient_id' => $patient_id,
                                        'initial_rx' => $InitialRXID,
                                        'initial_dx' => $InitialDXID,
                                        'initial_dot' => (!empty($Row['InitialDOT'])) ? $Row['InitialDOT'] : null,
                                        'new_initial_rx' => $NewInitialRXID,
                                        'new_initial_dx' => $NewInitiaDXID,
                                        'new_initial_dot' => (!empty($Row['NewInitialDOT'])) ? $Row['NewInitialDOT'] : null
                                    )
                                );
                                $insert_id = $this->common_model->customInsert($option);
                            }
                        }
                    }
                }
                $this->session->set_flashdata('success', "Successfully file import");
                redirect('patient');
            }
        } else {
            $this->session->set_flashdata('error', "Please select any one Care Unit");
            redirect('patient');
        }
    }

    /**
     * @method add
     * @description add dynamic rows
     * @return array
     */
    public function add()
    {
        $this->form_validation->set_rules('name', 'Name', 'trim');
        $this->form_validation->set_rules('address', 'Address', 'trim');
        $this->form_validation->set_rules('room_number', 'Room Number', 'trim');
        $this->form_validation->set_rules('symptom_onset', 'Infection Onset', 'trim|required|in_list[Hospital,Facility]');
        $this->form_validation->set_rules('care_unit_id', 'Care Unit', 'trim|required|numeric');
        $this->form_validation->set_rules('doctor_id', 'Provider Doctor', 'trim|required|numeric');
        $this->form_validation->set_rules('md_steward_id', 'MD Steward', 'trim|required');
      //  $this->form_validation->set_rules('md_stayward_consult', 'MD Stayward Consult', 'trim|required|in_list[Yes,No]');
        // $this->form_validation->set_rules('criteria_met', 'Criteria Met', 'trim|required|in_list[Yes,No]');
        if ($this->input->post('infection_surveillance_checklist') != 'N/A') {
            $this->form_validation->set_rules('criteria_met', 'Criteria Met', 'trim|required|in_list[Yes,No]');
        }
        $this->form_validation->set_rules('patient_id', 'Patient Id', 'trim|required|min_length[1]|max_length[15]');
        $this->form_validation->set_rules('initial_rx', 'Initial RX', 'trim|required|numeric');
        $this->form_validation->set_rules('initial_dx', 'Initial DX', 'trim|required|numeric');
        $this->form_validation->set_rules('initial_dot', 'Days of Therapy', 'trim|required|numeric');
        $this->form_validation->set_rules('new_initial_rx', 'New Initial RX', 'trim');
        $this->form_validation->set_rules('new_initial_dx', 'New Initial DX', 'trim');
        $this->form_validation->set_rules('new_initial_dot', 'New Days of Therapy', 'trim');
        $this->form_validation->set_rules('infection_surveillance_checklist', 'Infection Surveillance Checklist', 'trim');
        $this->form_validation->set_rules('md_stayward_response', 'MD Stayward Response', 'trim');
       // $this->form_validation->set_rules('culture_source', 'Culture Source', 'trim|required|in_list[NA,Sputum,Stool,Wound,Urine,Blood,Nares]');
        $this->form_validation->set_rules('culture_source', 'Culture Source', 'trim|required');
        $this->form_validation->set_rules('organism', 'Organism', 'trim|required');
        $this->form_validation->set_rules('precautions', 'Precautions', 'trim|required');
        //$this->form_validation->set_rules('organism', 'organism', 'trim|required|in_list[NA,Candida,C. auris,Citrobacter,Cdiff,Coag Neg Staph,COVID-19,Enterobacter,Enterococcus,Ecoli,ESBL ecoli,ESBL klebsiella,Klebsiella,MDRO,MRSA,MSSA,Proteus,Pseudomonas,Streptococcus,VRE,Other]');
        
        $this->form_validation->set_rules('psa', 'PSA(Provider Steward Agreement)', 'trim');
        $this->form_validation->set_rules('additional_comment_option[]', 'Additional Comment', 'trim');
        //$this->form_validation->set_rules('patient_mode', 'Patient Mode', 'trim|required|in_list[New,Existing]');
        if ($this->form_validation->run() == true) {
            $operator_id = ($this->ion_auth->is_admin()) ? 0 : $this->session->userdata('user_id');

           // if ($this->input->post('patient_mode') == 'New') {
                $option = array(
                    'table' => 'care_unit',
                    'where' => array(
                        'id' => $this->input->post('care_unit_id'),
                    ),
                    'single' => true
                );
                $CareUnit = $this->common_model->customGet($option);
                $patient_unique_id = strtoupper($CareUnit->care_unit_code) . '' . $this->input->post('patient_id');

                $option = array(
                    'table' => 'patient',
                    'where' => array(
                        'patient_id' => $patient_unique_id,
                    )
                );
                $IsPatientUniqueID = $this->common_model->customGet($option);
            /* } else {
                $IsPatientUniqueID = array();
                $patient_unique_id = $this->input->post('patient_id');
            } */

            /* if (!empty($IsPatientUniqueID)) {
                $response = array('status' => 0, 'message' => "Patient id already exists");
            } else { */
                $option = array(
                    'table' => 'patient',
                    'data' => array(
                        'name' => ucwords($this->input->post('name')),
                        'operator_id' => $operator_id,
                        'patient_id' => $patient_unique_id,
                        'address' => ucwords($this->input->post('address')),
                        //'room_number' => $this->input->post('room_number'),
                        'room_number' => (!empty($this->input->post('room_number'))) ? $this->input->post('room_number') : null,
                        'symptom_onset' => $this->input->post('symptom_onset'),
                        'culture_source' => $this->input->post('culture_source'),
                        'organism' => $this->input->post('organism'),
                        'precautions' => $this->input->post('precautions'),
                        'care_unit_id' => $this->input->post('care_unit_id'),
                        'doctor_id' => $this->input->post('doctor_id'),
                        'md_steward_id' => (!empty($this->input->post('md_steward_id'))) ? $this->input->post('md_steward_id') : null,
                       // 'md_stayward_consult' => $this->input->post('md_stayward_consult'),
                        'criteria_met' => $this->input->post('criteria_met'),
                        'md_stayward_response' => (!empty($this->input->post('md_stayward_response'))) ? $this->input->post('md_stayward_response') : null,
                        'psa' => (!empty($this->input->post('psa'))) ? $this->input->post('psa') : null,
                        //'pct' => (!empty($this->input->post('pct'))) ? $this->input->post('pct') : null,
                        'total_days_of_patient_stay' => (!empty($this->input->post('total_days_of_patient_stay'))) ? $this->input->post('total_days_of_patient_stay') : 0,
                        'infection_surveillance_checklist' => $this->input->post('infection_surveillance_checklist'),
                        'date_of_start_abx' => date('Y-m-d', strtotime($this->input->post('date_of_start_abx'))),
                       // 'pct' => (!empty($this->input->post('pct'))) ? $this->input->post('pct') : null,
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
                            //'culture_source' => $this->input->post('culture_source'),
                            'initial_dot' => $this->input->post('initial_dot'),
                            'new_initial_rx' => (!empty($this->input->post('new_initial_rx'))) ? $this->input->post('new_initial_rx') : null,
                            'new_initial_dx' => (!empty($this->input->post('new_initial_dx'))) ? $this->input->post('new_initial_dx') : null,
                            'new_initial_dot' => (!empty($this->input->post('new_initial_dot'))) ? $this->input->post('new_initial_dot') : null,
                            'additional_comment_option' => (!empty($this->input->post('additional_comment_option'))) ? json_encode($this->input->post('additional_comment_option')) : null,
                            
                        )
                    );
                    $insert_id = $this->common_model->customInsert($option);



                    $option = array(
                        'table' => 'notifications',
                        'data' => array(
                            'user_id' => $this->input->post('md_steward_id'),
                            'sender_id' => 1,
                            'message' => "ID: $patient_unique_id New patient added",
                            'user_type' => "USER",
                            'type_id' => $patient_unique_id,
                            'care_unit_id' => $this->input->post('care_unit_id'),
                            'patient_id' => $patient_id,
                            'sent_time' => date('Y-m-d H:i:s')
                        )
                    );
                    $this->common_model->customInsert($option);

                    if ($this->input->post('infection_surveillance_checklist') == 'N/A') {
                        $redirect_to = base_url($this->router->fetch_class());
                        $show_redirection_alert = false;
                    } elseif ($this->input->post('infection_surveillance_checklist') == 'Loeb') {
                        $redirect_to = base_url() . 'application/modules/patient/views/form1.html';
                        $show_redirection_alert = true;
                    } elseif ($this->input->post('infection_surveillance_checklist') == 'McGeer – UTI') {
                        $redirect_to = base_url() . 'application/modules/patient/views/form2.html';
                        $show_redirection_alert = true;
                    } elseif ($this->input->post('infection_surveillance_checklist') == 'McGeer – RTI') {
                        $redirect_to = base_url() . 'application/modules/patient/views/form3.html';
                        $show_redirection_alert = true;
                    } elseif ($this->input->post('infection_surveillance_checklist') == 'McGeer – GITI') {
                        $redirect_to = base_url() . 'application/modules/patient/views/form4.html';
                        $show_redirection_alert = true;
                    } elseif ($this->input->post('infection_surveillance_checklist') == 'McGeer –SSTI') {
                        $redirect_to = base_url() . 'application/modules/patient/views/form5.html';
                        $show_redirection_alert = true;
                    }
                    $response = array('status' => 1, 'show_redirection_alert' => $show_redirection_alert, 'message' => "Successfully added", 'url' => $redirect_to);
                } else {
                    $response = array('status' => 0, 'message' => "Failed to add");
                }
          //  }
        } else {
            $messages = (validation_errors()) ? validation_errors() : '';
            $response = array('status' => 0, 'message' => $messages);
        }
        echo json_encode($response);
    }

    /**
     * @method edit
     * @description edit dynamic rows
     * @return array
     */
    public function edit()
    {
        $this->data['parent'] = $this->title;
        $this->data['title'] = $this->title;
        $this->data['formUrl'] = $this->router->fetch_class() . "/update";
        $id = decoding($_GET['id']);
        if (!empty($id)) {

            $option = array(
                'table' => 'patient P',
                'select' => 'P.total_days_of_patient_stay,P.infection_surveillance_checklist,P.date_of_start_abx,P.md_patient_status,P.id ,P.patient_id,P.name as patient_name,P.address,P.room_number,P.symptom_onset,P.md_stayward_consult,P.criteria_met,P.md_stayward_response,P.psa,P.created_date,'
                    . 'P.care_unit_id,CI.name as care_unit_name,P.doctor_id,P.culture_source,P.organism,P.precautions,CS.name as culture_source_name,Org.name as organism_name,Pre.name as precautions_name,DOC.name as doctor_name,P.md_steward_id,U.first_name as md_steward,'
                    . 'PC.initial_rx,IRX.name as initial_rx_name,PC.initial_dx,IDX.name as initial_dx_name,PC.initial_dot,'
                    . 'PC.new_initial_rx,IRX2.name as new_initial_rx_name,PC.new_initial_dx,IDX2.name as new_initial_dx_name,PC.new_initial_dot,PC.additional_comment_option,PC.comment',
                'join' => array(
                    array('care_unit CI', 'CI.id=P.care_unit_id', 'inner'),
                    array('doctors DOC', 'DOC.id=P.doctor_id', 'inner'),
                    array('users U', 'U.id=P.md_steward_id', 'left'),
                    array('patient_consult PC', 'PC.patient_id=P.id', 'inner'),
                    array('initial_rx IRX', 'IRX.id=PC.initial_rx', 'left'),
                    array('initial_dx IDX', 'IDX.id=PC.initial_dx', 'left'),
                    array('culture_source CS', 'CS.name=P.culture_source', 'left'),
                    array('organism Org', 'Org.name=P.organism', 'left'),
                    array('precautions Pre', 'Pre.name=P.precautions', 'left'),
                    array('initial_rx IRX2', 'IRX2.id=PC.new_initial_rx', 'left'),
                    array('initial_dx IDX2', 'IDX2.id=PC.new_initial_dx', 'left')
                ),
                'single' => true
            );
            $option['where']['P.id'] = $id;
            $results_row = $this->common_model->customGet($option);
            if (!empty($results_row)) {
                $this->data['results'] = $results_row;
                $this->load->admin_render('edit', $this->data, 'inner_script');
            } else {
                $this->session->set_flashdata('error', lang('not_found'));
                redirect('patient');
            }
        } else {
            $this->session->set_flashdata('error', lang('not_found'));
            redirect('patient');
        }
    }

    /**
     * @method menu_category_edit
     * @description edit dynamic rows
     * @return array
     */
    public function edit_patient()
    {
        $this->data['title'] = "Edit " . $this->title;
        $this->data['formUrl'] = $this->router->fetch_class() . "/update";
        $id = decoding($this->input->post('id'));
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


        $this->data['initial_dx'] = $this->common_model->customGet(array('table' => 'initial_dx', 'select' => 'id,name', 'where' => array('is_active' => 1, 'delete_status' => 0), 'order' => array('name' => 'asc')));
        $this->data['culture_source'] = $this->common_model->customGet(array('table' => 'culture_source', 'select' => 'id,name', 'where' => array('is_active' => 1, 'delete_status' => 0), 'order' => array('name' => 'asc')));
        $this->data['organism'] = $this->common_model->customGet(array('table' => 'organism', 'select' => 'id,name', 'where' => array('is_active' => 1, 'delete_status' => 0), 'order' => array('name' => 'asc')));
        $this->data['precautions'] = $this->common_model->customGet(array('table' => 'precautions', 'select' => 'id,name', 'where' => array('is_active' => 1, 'delete_status' => 0), 'order' => array('name' => 'asc')));
        $this->data['initial_rx'] = $this->common_model->customGet(array('table' => 'initial_rx', 'select' => 'id,name', 'where' => array('is_active' => 1, 'delete_status' => 0), 'order' => array('name' => 'asc')));
        $this->data['doctors'] = $this->common_model->customGet(array('table' => 'doctors', 'select' => 'id,name', 'where' => array('is_active' => 1, 'delete_status' => 0), 'order' => array('name' => 'asc')));
        $option = array(
            'table' => 'users U',
            'select' => 'U.id,CONCAT(first_name," ",last_name) name',
            'join' => array('users_groups as UG' => 'UG.user_id=U.id'),
            'where' => array('U.active' => 1, 'U.delete_status' => 0, 'UG.group_id' => 3),
            'order' => array('first_name' => 'asc')
        );
        $this->data['md_steward'] = $this->common_model->customGet($option);
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

$option2 = array('table' => USERS . ' as user',
                    'select' => 'user.*,group.name as group_name,UP.doc_file',
                    'join' => array(array(USER_GROUPS . ' as ugroup', 'ugroup.user_id=user.id', 'left'),
                        array(GROUPS . ' as group', 'group.id=ugroup.group_id', 'left'),
                        array('user_profile UP', 'UP.user_id=user.id', 'left')),
                    'order' => array('user.id' => 'ASC'),
                    'where' => array('user.delete_status' => 0,
                        'group.id' => 3),
                    'order' => array('user.first_name' => 'ASC')
                );

                $this->data['stawardsss'] = $this->common_model->customGet($option2);



        
        if (!empty($id)) {
            $option = array(
                'table' => 'patient P',
                'select' => 'P.total_days_of_patient_stay,P.infection_surveillance_checklist,P.date_of_start_abx,P.md_patient_status,P.id as patient_id,P.name as patient_name,P.address,P.room_number,P.symptom_onset,P.md_stayward_consult,P.criteria_met,P.md_stayward_response,P.psa,P.created_date,'
                    . 'P.care_unit_id,CI.name as care_unit_name,P.doctor_id,P.culture_source,P.organism,P.precautions,CS.name as culture_source_name,Org.name as organism_name,Pre.name as precautions_name,DOC.name as doctor_name,P.md_steward_id,U.first_name as md_steward,'
                    . 'PC.initial_rx,IRX.name as initial_rx_name,PC.initial_dx,IDX.name as initial_dx_name,PC.initial_dot,'
                    . 'PC.new_initial_rx,IRX2.name as new_initial_rx_name,PC.new_initial_dx,IDX2.name as new_initial_dx_name,PC.new_initial_dot,PC.additional_comment_option',
                'join' => array(
                    array('care_unit CI', 'CI.id=P.care_unit_id', 'inner'),
                    array('doctors DOC', 'DOC.id=P.doctor_id', 'inner'),
                    array('users U', 'U.id=P.md_steward_id', 'left'),
                    array('patient_consult PC', 'PC.patient_id=P.id', 'inner'),
                    array('initial_rx IRX', 'IRX.id=PC.initial_rx', 'left'),
                    array('initial_dx IDX', 'IDX.id=PC.initial_dx', 'left'),
                    array('culture_source CS', 'CS.name=P.culture_source', 'left'),
                    array('organism Org', 'Org.name=P.organism', 'left'),
                    array('precautions Pre', 'Pre.name=P.precautions', 'left'),
                    array('initial_rx IRX2', 'IRX2.id=PC.new_initial_rx', 'left'),
                    array('initial_dx IDX2', 'IDX2.id=PC.new_initial_dx', 'left')
                ),
                'single' => true
            );
            $option['where']['P.id'] = $id;
            $results_row = $this->common_model->customGet($option);
            if (!empty($results_row)) {
                $this->data['results'] = $results_row;
                $this->load->view('update', $this->data);
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
        $this->form_validation->set_rules('name', 'Name', 'trim');
        $this->form_validation->set_rules('address', 'Address', 'trim');
        $this->form_validation->set_rules('room_number', 'Room Number', 'trim');
        $this->form_validation->set_rules('symptom_onset', 'Infection Onset', 'trim|required|in_list[Hospital,Facility]');
       // $this->form_validation->set_rules('culture_source', 'Culture Source', 'trim|required|in_list[NA,Sputum,Stool,Wound,Urine,Blood,Nares]');
       //$this->form_validation->set_rules('organism', 'organism', 'trim|required|in_list[NA,Candida,C. auris,Citrobacter,Cdiff,Coag Neg Staph,COVID-19,Enterobacter,Enterococcus,Ecoli,ESBL ecoli,ESBL klebsiella,Klebsiella,MDRO,MRSA,MSSA,Proteus,Pseudomonas,Streptococcus,VRE,Other]');
        
        $this->form_validation->set_rules('care_unit_id', 'Care Unit', 'trim|required|numeric');
        $this->form_validation->set_rules('doctor_id', 'Provider Doctor', 'trim|required|numeric');
        $this->form_validation->set_rules('md_steward_id', 'MD Steward', 'trim|required');
       // $this->form_validation->set_rules('md_stayward_consult', 'MD Stayward Consult', 'trim|required|in_list[Yes,No]');
        // $this->form_validation->set_rules('criteria_met', 'Criteria Met', 'trim|required|in_list[Yes,No]');
        if ($this->input->post('infection_surveillance_checklist') != 'N/A') {
            $this->form_validation->set_rules('criteria_met', 'Criteria Met', 'trim|required|in_list[Yes,No]');
        }
        $this->form_validation->set_rules('initial_rx', 'Initial RX', 'trim|required|numeric');
        $this->form_validation->set_rules('initial_dx', 'Initial DX', 'trim|required|numeric');
        $this->form_validation->set_rules('culture_source', 'Culture Source', 'trim|required');
        $this->form_validation->set_rules('organism', 'Organism', 'trim|required');
        $this->form_validation->set_rules('precautions', 'Precautions', 'trim|required');
        $this->form_validation->set_rules('initial_dot', 'Days of Therapy', 'trim|required|numeric');
        $this->form_validation->set_rules('new_initial_rx', 'New Initial RX', 'trim');
        $this->form_validation->set_rules('new_initial_dx', 'New Initial DX', 'trim');
        $this->form_validation->set_rules('new_initial_dot', 'New Days of Therapy', 'trim');
        $this->form_validation->set_rules('additional_comment_option[]', 'Additional Comment', 'trim');
        
        $this->form_validation->set_rules('infection_surveillance_checklist', 'Infection Surveillance Checklist', 'trim');
        $this->form_validation->set_rules('md_stayward_response', 'MD Stayward Response', 'trim');
        $this->form_validation->set_rules('psa', 'PSA(Provider Steward Agreement', 'trim');
        $where_id = $this->input->post('id');
        if ($this->form_validation->run() == FALSE) :
            $messages = (validation_errors()) ? validation_errors() : '';
            $response = array('status' => 0, 'message' => $messages);
        else :
            $option = array(
                'table' => 'patient',
                'data' => array(
                    'name' => ucwords($this->input->post('name')),
                    'address' => ucwords($this->input->post('address')),
                    'room_number' => (!empty($this->input->post('room_number'))) ? $this->input->post('room_number') : null,
                    'symptom_onset' => $this->input->post('symptom_onset'),
                    'culture_source' => $this->input->post('culture_source'),
                    'organism' => $this->input->post('organism'),
                    'precautions' => $this->input->post('precautions'),
                    'care_unit_id' => $this->input->post('care_unit_id'),
                    'doctor_id' => $this->input->post('doctor_id'),
                    'md_steward_id' => (!empty($this->input->post('md_steward_id'))) ? $this->input->post('md_steward_id') : null,
                    //'md_stayward_consult' => $this->input->post('md_stayward_consult'),
                    'criteria_met' => $this->input->post('criteria_met'),
                    'md_stayward_response' => (!empty($this->input->post('md_stayward_response'))) ? $this->input->post('md_stayward_response') : null,
                    'psa' => (!empty($this->input->post('psa'))) ? $this->input->post('psa') : null,
                    'infection_surveillance_checklist' => $this->input->post('infection_surveillance_checklist'),
                    'total_days_of_patient_stay' => (!empty($this->input->post('total_days_of_patient_stay'))) ? $this->input->post('total_days_of_patient_stay') : 0,
                    'date_of_start_abx' => date('Y-m-d', strtotime($this->input->post('date_of_start_abx'))),
                    //'pct' => (!empty($this->input->post('pct'))) ? $this->input->post('pct') : null,
                    'updated_date' => date('Y-m-d H:i:s')
                ),
                'where' => array('id' => $where_id)
            );
            $this->common_model->customupdate($option);
            $option = array(
                'table' => 'patient_consult',
                'data' => array(
                    'initial_dx' => (!empty($this->input->post('initial_dx'))) ? $this->input->post('initial_dx') : null,
                   // 'culture_source' => (!empty($this->input->post('culture_source'))) ? $this->input->post('culture_source') : null,
                    'initial_rx' => (!empty($this->input->post('initial_rx'))) ? $this->input->post('initial_rx') : null,
                    'initial_dot' => (!empty($this->input->post('initial_dot'))) ? $this->input->post('initial_dot') : null,
                    'new_initial_rx' => (!empty($this->input->post('new_initial_rx'))) ? $this->input->post('new_initial_rx') : null,
                    'new_initial_dx' => (!empty($this->input->post('new_initial_dx'))) ? $this->input->post('new_initial_dx') : null,
                    'new_initial_dot' => (!empty($this->input->post('new_initial_dot'))) ? $this->input->post('new_initial_dot') : null,
                    'additional_comment_option' => (!empty($this->input->post('additional_comment_option'))) ? json_encode($this->input->post('additional_comment_option')) : null
                ),
                'where' => array('patient_id' => $where_id)
            );
            $Update = $this->common_model->customupdate($option);
            if ($this->input->post('infection_surveillance_checklist') == 'N/A') {
                $redirect_to = base_url($this->router->fetch_class());
                $show_redirection_alert = false;
            } elseif ($this->input->post('infection_surveillance_checklist') == 'Loeb') {
                $redirect_to = base_url() . 'application/modules/patient/views/form1.html';
                $show_redirection_alert = true;
            } elseif ($this->input->post('infection_surveillance_checklist') == 'McGeer – UTI') {
                $redirect_to = base_url() . 'application/modules/patient/views/form2.html';
                $show_redirection_alert = true;
            } elseif ($this->input->post('infection_surveillance_checklist') == 'McGeer – RTI') {
                $redirect_to = base_url() . 'application/modules/patient/views/form3.html';
                $show_redirection_alert = true;
            } elseif ($this->input->post('infection_surveillance_checklist') == 'McGeer – GITI') {
                $redirect_to = base_url() . 'application/modules/patient/views/form4.html';
                $show_redirection_alert = true;
            } elseif ($this->input->post('infection_surveillance_checklist') == 'McGeer –SSTI') {
                $redirect_to = base_url() . 'application/modules/patient/views/form5.html';
                $show_redirection_alert = true;
            }
            $response = array('status' => 1, 'message' => 'updated Successfully', 'show_redirection_alert' => $show_redirection_alert, 'url' => $redirect_to, 'id' => encoding($this->input->post('id')));
        endif;

        echo json_encode($response);
    }

    function delete_patient()
    {

        $option = array(
            'table' => 'patient',
            'where' => array('id' => $this->input->post('patient_id'))
        );
        $this->common_model->customDelete($option);
        $option = array(
            'table' => 'patient_consult',
            'where' => array('patient_id' => $this->input->post('patient_id'))
        );
        $this->common_model->customDelete($option);
        $option = array(
            'table' => 'notifications',
            'where' => array(
                'patient_id' => $this->input->post('patient_id')
            )
        );
        $this->common_model->customDelete($option);

        $response = array('status' => 200, 'message' => 'Deleted Successfully', 'url' => base_url($this->router->fetch_class()));
        echo json_encode($response);
    }


    function send_mail_smtp($template)
    {

        $this->load->library('email');

        $config['protocol'] = 'smtp';
        $config['smtp_host'] = 'ssl://smtp.ionos.com'; //'smtp.mailtrap.io';//ssl://smtp.gmail.com
        $config['smtp_port'] = '465'; //2525';//465
        $config['smtp_timeout'] = '7';
        $config['smtp_user'] = "dnr@idcaresteward.com"; //"cd3f624d7cd7a0";//'pawan.mobiwebtech@gmail.com';
        $config['smtp_pass'] = "!0c@R3%7ew@R0"; //"5e1dbcb52e12d4";//'********';
        $config['charset'] = 'iso-8859-1';
        $config['newline'] = "\r\n";
        $config['mailtype'] = 'html';
        $config['validation'] = TRUE;
        $config['wordwrap'] = TRUE;
        $from = 'dnr@idcaresteward.com';
        $title = "IDCARE";
        $subject = "Changes in MD Steward Recommandation";
        // $to = array('dpdev@visvero.net', 'vajay8679@gmail.com');
        $to = ['dpdev@visvero.net'];

        /* $this->db->select('email');
        $list = $this->db->get('vendor_sale_doctors')->result_array(); */
        $doctor_id = $_POST['doctor_id'];
        $option = array(
            'table' => 'doctors',
            'where' => array('id' => $doctor_id)
        );
        $p_mail =  $this->common_model->customGet($option);
        // print_r($x[0]['email']);
        //print_r($x[0]->email);
        $providers_mail = $p_mail[0]->email;

        $md_steward_id = $_POST['md_steward_id'];
        $option = array(
            'table' => 'users',
            'where' => array('id' => $md_steward_id)
        );
        $m_mail =  $this->common_model->customGet($option);
        $md_steward_mail = $m_mail[0]->email;
        array_push($to, $providers_mail, $md_steward_mail);
        $this->email->initialize($config);
        $this->email->set_newline("\r\n");
        $this->email->from($from, $title);
        $this->email->to($to);
        $this->email->subject($subject);
        $this->email->message($template);
        if ($this->email->send()) {
            return true;
        } else {
            return false;
        }
    }

    function email_smtp()
    {
        // print_r("helllllllllllll");die;
        $md_stayward_response = $_POST['md_stayward_response']; //"md_stayward_response";
        $psa = $_POST['psa'];
        $new_initial_dx = $_POST['new_initial_dx'];
        $new_initial_rx = $_POST['new_initial_rx'];
        $new_initial_dot = $_POST['new_initial_dot'];
       // $pct = $_POST['pct'];
        $patient_id = $_POST['patient_id'];
        // $doctor_id = $_POST['doctor_id'];
        $template1 = "
        Patient ID: $patient_id<br>
        MD Steward Response: $md_stayward_response<br> 
        PSA(Provider Steward Agreement): $psa<br>
        New Diagnosis: $new_initial_dx<br>
        New Antibiotic Name: $new_initial_rx<br>
        New Days of Therapy: $new_initial_dot <br>";


        $template = $this->send_mail_smtp($template1);

        return $template;
    }


/*     function get_patient_id()
    {
        $option = array(
            'table' => 'patient',
            'select' => 'patient_id',
            'where' => array('care_unit_id' => $this->input->post('careunit_id')),
            'order' => array('patient_id' => 'ASC')
        );
        $result = $this->common_model->customGet($option);
        $html = ' <select id="patient_id" name="patient_id" class="form-control select-chosen" size="1">';
        foreach ($result as $row) {
            $html .= '<option value="' . $row->patient_id . '">' . $row->patient_id . '</option>';
        }

        $html .= '</select>';
        echo $html;
    } */



    function send_mail_smtp1($template2,$to_email)
    {

        
        $this->load->library('email');

        $config['protocol'] = 'smtp';
        $config['smtp_host'] = 'ssl://smtp.ionos.com'; //'smtp.mailtrap.io';//ssl://smtp.gmail.com
        $config['smtp_port'] = '465'; //2525';//465
        $config['smtp_timeout'] = '7';
        $config['smtp_user'] = "dnr@idcaresteward.com"; //"cd3f624d7cd7a0";//'pawan.mobiwebtech@gmail.com';
        $config['smtp_pass'] = "!0c@R3%7ew@R0"; //"5e1dbcb52e12d4";//'********';
        $config['charset'] = 'iso-8859-1';
        $config['newline'] = "\r\n";
        $config['mailtype'] = 'html';
        $config['validation'] = TRUE;
        $config['wordwrap'] = TRUE;
        $from = 'dnr@idcaresteward.com';
        $title = "IDCARE";
        $subject = "IDCARE-Patient Details";
        // $to = array('dpdev@visvero.net', 'vajay8679@gmail.com');
        // $to = ['dpdev@visvero.net'];
      //$a=   implode(" ",$to_email);
        $to = explode(',', $to_email);
        
       
        $this->email->initialize($config);
        $this->email->set_newline("\r\n");
        $this->email->from($from, $title);
        $this->email->to($to);
        $this->email->subject($subject);
        $this->email->message($template2);

        if ($this->email->send()) {
            
            return true;
        }
    // } 
    //     print_r($to_emailss);die;
 

        // if ($this->email->send()) {
        //     return true;
        // } else {
        //     return false;
        // }

    }

    function email_smtp1()
    {
       
        $to_email= $_POST['to_email'];
        $patient_id = $_POST['patient_id'];
        $room_number = $_POST['room_number'];
        $care_unit_name = ucwords($_POST['care_unit_name']);
        $doctor_name = ucwords($_POST['doctor_name']);
        if($_POST['symptom_onset'] == 'Facility'){
        $symptom_onset = 'Facility/HAI';
        }else if($_POST['symptom_onset'] == 'Hospital'){
            $symptom_onset = 'Hospital/CAI';
        }else{
            $symptom_onset = '';
        }
        $culture_source_name = $_POST['culture_source_name'];
        $organism_name = $_POST['organism_name'];
        $precautions_name = $_POST['precautions_name'];
        $md_steward = ucwords($_POST['md_steward']);
        $date_of_start_abx = date('m/d/Y',strtotime($_POST['date_of_start_abx']));
        $initial_rx_name = $_POST['initial_rx_name'];
        $initial_dx_name = $_POST['initial_dx_name'];
        $initial_dot = $_POST['initial_dot'];
        $infection_surveillance_checklist = $_POST['infection_surveillance_checklist'];
        $criteria_met = $_POST['criteria_met'];
        $md_stayward_response = $_POST['md_stayward_response'];
        $new_initial_rx_name = $_POST['new_initial_rx_name'];
        $psa = $_POST['psa'];
        $new_initial_dx_name = $_POST['new_initial_dx_name'];
        $new_initial_dot = $_POST['new_initial_dot'];
        $additional_comment_option = str_replace( array('[','"',']') , ''  ,$_POST['additional_comment_option']);

       

       $template3='<h3 align="left"><strong>Patient</strong> Info</h3>
       <table border="1" width="50%" cellpadding="5">
        <tr>
         <td width="30%">Patient ID</td>
         <td width="70%">'. $patient_id .'</td>
        </tr>
        <tr>
         <td width="30%">Room Number</td>
         <td width="70%">'. $room_number .'</td>
        </tr>
        <tr>
         <td width="30%">Care Unit</td>
         <td width="70%">'.$care_unit_name.'</td>
        </tr>
        <tr>
         <td width="30%">Provider MD</td>
         <td width="70%">'.$doctor_name.'</td>
        </tr>
        <tr>
         <td width="30%">Infection Onset</td>
         <td width="70%">'.$symptom_onset.'</td>
        </tr>
        <tr>
         <td width="30%">MD Steward</td>
         <td width="70%">'. $md_steward .'</td>
        </tr>
        <tr>
         <td width="30%">Date of start abx</td>
         <td width="70%">'.$date_of_start_abx.'</td>
         </tr>
       </table>
       </br>
    
       <h3 align="left"><strong>Initial</strong> Info</h3>
       <table border="1" width="50%" cellpadding="5">
        <tr>
         <td width="30%">Antibiotic Name</td>
         <td width="70%">'. $initial_rx_name .'</td>
        </tr>
        <tr>
         <td width="30%">Diagnosis</td>
         <td width="70%">'. $initial_dx_name .'</td>
        </tr>
        <tr>
         <td width="30%">Days of Therapy</td>
         <td width="70%">'. $initial_dot .'</td>
        </tr>
        <tr>
         <td width="30%">ABX Checklist</td>
         <td width="70%">'. $infection_surveillance_checklist .'</td>
        </tr>
        <tr>
         <td width="30%">Criteria Met</td>
         <td width="70%">'.$criteria_met.'</td>
        </tr>
        <tr>
         <td width="30%">Culture Source</td>
         <td width="70%">'.$culture_source_name.'</td>
        </tr>
        <tr>
         <td width="30%">Organism</td>
         <td width="70%">'.$organism_name.'</td>
        </tr>
        <tr>
        <td width="30%">Precautions</td>
        <td width="70%">'.$precautions_name.'</td>
       </tr>
       </table>
       </br>
    
       <h3 align="left"><strong>MD Steward</strong> Recommendation</h3>
       <table border="1" width="50%" cellpadding="5">
        <tr>
         <td width="30%">MD Steward Response</td>
         <td width="70%">'.$md_stayward_response.'</td>
        </tr>
        <tr>
         <td width="30%">NEW Antibiotic Name</td>
         <td width="70%">'.$new_initial_rx_name.'</td>
        </tr>
        <tr>
         <td width="30%">PSA(Provider Steward Agreement)</td>
         <td width="70%">'. $psa.'</td>
        </tr>
        <tr>
         <td width="30%">New Diagnosis</td>
         <td width="70%">'. $new_initial_dx_name .'</td>
        </tr>
        <tr>
         <td width="30%">New Days of Therapy</td>
         <td width="70%">'.$new_initial_dot.'</td>
        </tr>
        <tr>
         <td width="30%">Additional Comment</td>
         <td width="70%">'.$additional_comment_option.'</td>
        </tr>
       </table>';


        $template2 = $this->send_mail_smtp1($template3,$to_email);
       
        return $template2;
    }

}