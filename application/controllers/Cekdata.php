<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Cekdata extends CI_Controller {

	function __construct() {
        parent::__construct();

        date_default_timezone_set("Asia/Jakarta");
    }
    public function data($key=''){
        $sqlData=$this->db->query("SELECT * FROM `history_api` WHERE his_api_request LIKE '%".$key."%'");
        // echo json_encode();
        print_r($sqlData->result_array());
    }
}