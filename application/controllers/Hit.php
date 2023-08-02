<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
/*
March 18, 2019 10:55:52 PM dyah line 237, 277 $ket_his
*/
class Hit extends CI_Controller
{

    function __construct()
    {
        parent::__construct();
    }
    private function sendOutput($response)
    {
        $this->output
            ->set_status_header(200)
            ->set_content_type('application/json', 'utf-8')
            ->set_output(json_encode($response, JSON_PRETTY_PRINT))
            ->_display();
        exit;
    }


    public function cron()
    {

        $tokenData = json_encode(array(
            "SourceID" => '20200708KOINWORKS'
        ));

        $token = json_decode($this->curlPost('https://api.simasinsurtech.com/dataservice/createtoken.php', $tokenData, $key = ''), true);
        // $response=json_decode($this->curlPost('https://api.simasinsurtech.com/dataservice/asuransi_kredit_json.php',$params,$token['Token']),true);
        // print_r($token);
        $sql = $this->dataArray();
        // print_r($sql);
        foreach ($sql as $key => $rrData) {
            // print_r($rrData);
            $dataSql = $this->query($rrData['transactionId']);
            if ($dataSql->num_rows() > 0) {
                $noPolicy = $dataSql->row('cust_cf_policy_number');
            } else {
                $noPolicy = '';
            }
            $parameters = json_encode(array(
                "SourceID" => "20200708KOINWORKS",
                "NoRef" => $rrData['transactionId'],
                "PolicyInsuranceNo" => $noPolicy,
                "IDDebitor" => $rrData['transactionId'],
                "NilaiKlaim" => $rrData['nilaiKlaim'],
                "LamaTelatBayar" => "90",
                "NamaPemilikRekening" => "PT Lunaria Annua Teknologi",
                "NoRekeningBank" => "0353297888",
                "NamaBank" => "BCA",
                "Catatan" => ""
            ));
            print_r($parameters);
        }
    }
    public function send()
    {
        $paramBody             = json_decode(file_get_contents('php://input'), true);
        $tokenData = json_encode(array(
            "SourceID" => '20200708KOINWORKS'
        ));
        //    print_r($paramBody);
        $npp = str_replace('_', '-', $paramBody['transactionId']);
        // print_r($npp);
        $dataSql = $this->query($npp);
        if ($dataSql->num_rows() > 0) {
            $rrData = $dataSql->row_array();
            $noPolicy = $dataSql->row('cust_cf_policy_number');
            $parameters = json_encode(array(
                "SourceID" => "20200708KOINWORKS",
                "NoRef" => $paramBody['transactionId'],
                "PolicyInsuranceNo" => $noPolicy,
                "IDDebitor" => $paramBody['transactionId'],
                "NilaiKlaim" => $paramBody['nilaiKlaim'],
                "LamaTelatBayar" => "90",
                "NamaPemilikRekening" => "PT Lunaria Annua Teknologi",
                "NoRekeningBank" => "0353297888",
                "NamaBank" => "BCA",
                "Catatan" => ""
            ));
            echo $parameters;
            // die();
            $token = json_decode($this->curlPost('https://api.simasinsurtech.com/dataservice/createtoken.php', $tokenData, $key = ''), true);
            $response = $this->curlPost('https://api.simasinsurtech.com/dataservice/klaim_asuransi_kredit_json.php', $parameters, $token['Token']);
            echo $response;
            $dataInsert = array(
                "his_api_request" => $parameters,
                "his_api_related_table" => $paramBody['transactionId'],
                "his_api_response" => $response
            );
            $this->db->insert('history_api', $dataInsert);
        } else {
            echo 'tidak ada';
        }
    }
    public function notif($key)
    {
        $npp = str_replace('_', '-', $key);
        // print_r($npp);
        $dataNotif = array(
            '212020c86c-9', '212020b7db-9', '212020a783-9', '21202004ac-9', '2120209ccb-9', '212020189d-9', '212020280a-9', '2120204180-9', '21202049aa-9', '2120205a2d-9', '2120207768-9', '21202090c5-9', '2120202028-9', '2120202ae4-9', '2120205b2c-9', '212020c80a-9', '212020d921-9', '212020ee72-9', '212020fc27-9', '212020fec9-9', '920201634-9', '92020591d-9', '920209241-9', '92020e6fa-9', '92020e7ef-9', '32020d912-9', '32020bb05-9', '1920206eef-9'
        );
        // foreach ($dataNotif as $key => $value)
            $dataSql = $this->query($npp);
        if ($dataSql->num_rows() > 0) {
            $rrData = $dataSql->row_array();
            $noPolicy = $dataSql->row('cust_cf_policy_number');


            $paramBody = json_encode(array(
                "SourceID" => "20200708KOINWORKS",
                "NoRef" => $rrData['cust_cf_no_ref'],
                "PolicyInsuranceNo" => $rrData['cust_cf_policy_number'],
                "IDDebitor" => $rrData['cust_cf_no_ref'],
                "Status" => "Tidak Bayar",
                "TanggalNotif" => "09/01/2021"
            ));
            echo $paramBody;
            // die();
            $tokenData = json_encode(array(
                "SourceID" => '20200708KOINWORKS'
            ));

            $token = json_decode($this->curlPost('https://api.simasinsurtech.com/dataservice/createtoken.php', $tokenData, $key = ''), true);
            $response = $this->curlPost('https://api.simasinsurtech.com/dataservice/asuransi_kredit_notifbayar.php', $paramBody, $token['Token']);
            echo $response;


            $dataInsert = array(
                "his_api_request" => $paramBody,
                "his_api_related_table" => $rrData['cust_cf_no_ref'],
                "his_api_response" => $response
            );
            $this->db->insert('history_api', $dataInsert);
        } else {
            echo 'tdk ada data';
        }
    }
    public function lognotif()
    {
        $dataNotif = "('212020c86c-9','212020b7db-9','212020a783-9','21202004ac-9','2120209ccb-9','212020189d-9','212020280a-9','2120204180-9','21202049aa-9','2120205a2d-9','2120207768-9','21202090c5-9','2120202028-9','2120202ae4-9','2120205b2c-9','212020c80a-9','212020d921-9','212020ee72-9','212020fc27-9','212020fec9-9','920201634-9','92020591d-9','920209241-9','92020e6fa-9','92020e7ef-9','32020d912-9','32020bb05-9','1920206eef-9'
                        )";



        $sql = $this->db->query("SELECT * FROM `history_api` WHERE 
                        `his_api_related_table` IN " . $dataNotif . " ");
        print_r($sql->result_array());
    }
    private function query($key)
    {
        $sql = $this->db->query("SELECT * FROM `obj_customer_cf` WHERE `cust_cf_no_ref` ='" . $key . "'");
        return $sql;
    }
    public function stage()
    {
        $tokenData = json_encode(array(
            "SourceID" => '20200708KOINWORKS'
        ));

        $token = json_decode($this->curlPost('https://api.simasinsurtech.com/dataservice_test/createtoken.php', $tokenData, $key = ''), true);

        $data = array(
            array(
                "transactionId" => "212021a126",
                "policyNumber" => "C7213000052544",
                "nilaiKlaim"    => '15000000'
            )
        );
        foreach ($data as $key => $rrData) {
            // print_r($rrData);
            $parameters = json_encode(array(
                "SourceID" => "20200708KOINWORKS",
                "NoRef" => $rrData['transactionId'],
                "PolicyInsuranceNo" => $rrData['policyNumber'],
                "IDDebitor" => $rrData['transactionId'],
                "NilaiKlaim" => $rrData['nilaiKlaim'],
                "LamaTelatBayar" => "14",
                "NamaPemilikRekening" => "PT Lunaria Annua Teknologi",
                "NoRekeningBank" => "0353297888",
                "NamaBank" => "BCA",
                "Catatan" => ""
            ));
            print_r($parameters);
            die();
            $response = json_decode($this->curlPost('https://api.simasinsurtech.com/dataservice_test/klaim_asuransi_kredit_json.php', $parameters, $token['Token']), true);

            print_r($response);
        }
    }
    private function dataArray()
    {
        $data = array(
            array(
                "transactionId" => "1920204708",
                "policyNumber" => "3200001559689",
                "nilaiKlaim"    => '15000000'
            ),
            array(
                "transactionId" => "1920204871",
                "policyNumber" => "3200001559697",
                "nilaiKlaim"    => '13000000'
            ),
            array(
                "transactionId" => "212020fe0e",
                "policyNumber" => "3200001559557",
                "nilaiKlaim"    => '137000000'
            ),
            array(
                "transactionId" => "212020f7ec",
                "policyNumber" => "3200001559558",
                "nilaiKlaim"    => '147000000'
            ),
            array(
                "transactionId" => "212020ebb8",
                "policyNumber" => "3200001559559",
                "nilaiKlaim"    => '167000000'
            ),
            array(
                "transactionId" => "212020d72e",
                "policyNumber" => "3200001559560",
                "nilaiKlaim"    => '187000000'
            ),
            array(
                "transactionId" => "212020d330",
                "policyNumber" => "3200001559561",
                "nilaiKlaim"    => '207000000'
            ),
            array(
                "transactionId" => "212020c86c",
                "policyNumber" => "3200001559562",
                "nilaiKlaim"    => '227000000'
            ),
            array(
                "transactionId" => "212020b7db",
                "policyNumber" => "3200001559563",
                "nilaiKlaim"    => '247000000'
            ),
            array(
                "transactionId" => "212020a783",
                "policyNumber" => "3200001559564",
                "nilaiKlaim"    => '267000000'
            ),
            array(
                "transactionId" => "21202004ac",
                "policyNumber" => "3200001559565",
                "nilaiKlaim"    => '127000000'
            ),
            array(
                "transactionId" => "2120209ccb",
                "policyNumber" => "3200001559568",
                "nilaiKlaim"    => '287000000'
            ),
            array(
                "transactionId" => "212020189d",
                "policyNumber" => "3200001565342",
                "nilaiKlaim"    => '55000000'
            ),
            array(
                "transactionId" => "212020280a",
                "policyNumber" => "3200001565341",
                "nilaiKlaim"    => '49000000'
            ),
            array(
                "transactionId" => "2120204180",
                "policyNumber" => "3200001565340",
                "nilaiKlaim"    => '48000000'
            ),
            array(
                "transactionId" => "21202049aa",
                "policyNumber" => "3200001565353",
                "nilaiKlaim"    => '65000000'
            ),
            array(
                "transactionId" => "2120205a2d",
                "policyNumber" => "3200001565339",
                "nilaiKlaim"    => '47000000'
            ),
            array(
                "transactionId" => "2120207768",
                "policyNumber" => "3200001565338",
                "nilaiKlaim"    => '46000000'
            ),
            array(
                "transactionId" => "21202090c5",
                "policyNumber" => "3200001565337",
                "nilaiKlaim"    => '45000000'
            ),
            array(
                "transactionId" => "2120202028",
                "policyNumber" => "3200001562668",
                "nilaiKlaim"    => '30000000'
            ),
            array(
                "transactionId" => "2120202ae4",
                "policyNumber" => "3200001562667",
                "nilaiKlaim"    => '82000000'
            ),
            array(
                "transactionId" => "2120205b2c",
                "policyNumber" => "3200001562674",
                "nilaiKlaim"    => '75000000'
            ),
            array(
                "transactionId" => "212020c80a",
                "policyNumber" => "3200001562673",
                "nilaiKlaim"    => '85000000'
            ),
            array(
                "transactionId" => "212020d921",
                "policyNumber" => "3200001562670",
                "nilaiKlaim"    => '50000000'
            ),
            array(
                "transactionId" => "212020ee72",
                "policyNumber" => "3200001562666",
                "nilaiKlaim"    => '60000000'
            ),
            array(
                "transactionId" => "212020fc27",
                "policyNumber" => "3200001562665",
                "nilaiKlaim"    => '40000000'
            ),
            array(
                "transactionId" => "212020fec9",
                "policyNumber" => "3200001562669",
                "nilaiKlaim"    => '78000000'
            ),
            array(
                "transactionId" => "920201634",
                "policyNumber" => "3200001559674",
                "nilaiKlaim"    => '18099963.04'
            ),
            array(
                "transactionId" => "92020591d",
                "policyNumber" => "3200001559675",
                "nilaiKlaim"    => '18094571.94'
            ),
            array(
                "transactionId" => "920209241",
                "policyNumber" => "3200001559673",
                "nilaiKlaim"    => '18099963.04'
            ),
            array(
                "transactionId" => "92020e6fa",
                "policyNumber" => "3200001559681",
                "nilaiKlaim"    => '18099963.04'
            ),
            array(
                "transactionId" => "92020e7ef",
                "policyNumber" => "3200001559676",
                "nilaiKlaim"    => '18100000'
            ),
            array(
                "transactionId" => "32020d912",
                "policyNumber" => "3200001567662",
                "nilaiKlaim"    => '6140559.00'
            ),
            array(
                "transactionId" => "32020bb05",
                "policyNumber" => "3200001559112",
                "nilaiKlaim"    => '1891940.77'
            ),
            array(
                "transactionId" => "1920206eef",
                "policyNumber" => "3200001567659",
                "nilaiKlaim"    => '1726302.05'
            ),
        );
        return $data;
    }
    public function arrayKey()
    {
        $data = array(
            '212020fe0e-9',
            '212020f7ec-9',
            '212020ebb8-9',
            '212020d72e-9',
            '212020d330-9',
            '212020c86c-9',
            '212020b7db-9',
            '212020a783-9',
            '21202004ac-9',
            '2120209ccb-9',
            '32020bb05-9',
            '21202090c5-9',
            '2120207768-9',
            '2120205a2d-9',
            '2120204180-9',
            '212020280a-9',
            '212020189d-9',
            '21202049aa-9',
            '212020fc27-9',
            '212020ee72-9',
            '2120202ae4-9',
            '2120202028-9',
            '212020fec9-9',
            '212020d921-9',
            '212020c80a-9',
            '2120205b2c-9',
            '920209241-9',
            '920201634-9',
            '92020591d-9',
            '92020e7ef-9',
            '92020e6fa-9',
            '1920204708-9',
            '1920204871-9',
            '1920206eef-9',
            '32020d912-9'
        );
        return $data;
    }
    public function curlPost($url, $params, $token = '')
    {
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => $params,
            CURLOPT_HTTPHEADER => array(
                "Content-Type: application/json",
                "Authorization: Bearer " . $token . "",
                "Cookie: BIGipServerapi.simasinsurtech.com.app~api.simasinsurtech.com_pool=3848906944.20480.0000"
            ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        return $response;
    }
}
