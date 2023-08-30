<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * This Class used as Webfront management
 * @package   CodeIgniter
 * @category  Controller
 * @author    MobiwebTech Team
 */
class Webfront extends CI_Controller {

    function __construct() {
        parent::__construct();
    }

    public function index() {
        redirect('pwfpanel');
         //$this->load->view('index.html');
    }

    public function test_mail()
    {
    	$to_email = $_GET['to_email'];
    	var_dump(send_mail('Hellooooo','Test Subject',$to_email));
    }



    public function setSheet(){
        require 'vendor/autoload.php';

        //Reading data from spreadsheet.

        $client = new \Google_Client();

        $client->setApplicationName('Google PHP');

       // $client->setScopes([\Google_Service_Sheets::SPREADSHEETS]);

        $client->setScopes([\Google_Service_Sheets::SPREADSHEETS]);


        $client->setAccessType('offline');

        $client->setAuthConfig(APPPATH . 'credentials.json');

        $service = new Google_Service_Sheets($client);

        $spreadsheetId = "1-YgKM47swcfLP-OTSyi1qBtDP39NkQcwS6jIFyMe92Y";

        $get_range = "B1";

        $requestBody = new Google_Service_Sheets_ValueRange();

        $requestBody->setValues(["values" => ["a", "b"]]);

        $conf = ["valueInputOption" => "RAW"];

        $response = $service->spreadsheets_values->update($spreadsheetId, $get_range, $requestBody, $conf);



        echo '<pre>', var_export($response, true), '</pre>', "\n";


            //$response = $service->spreadsheets->get($spreadsheetId);

            // TODO: Change code below to process the `response` object:
           // echo '<pre>', var_export($response, true), '</pre>', "\n";


    }

}

/* End of file Cron.php */
/* Location: ./application/controllers/Webfront.php */
?>
