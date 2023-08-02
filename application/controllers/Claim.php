<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Claim extends CI_Controller {

	function __construct() {
        parent::__construct();
        $this->load->model('middleware','middle');

        date_default_timezone_set("Asia/Jakarta");
    }
    public function index(){
        $this->data();
    }
    public function data(){
        $sqlData=$this->db->query("select * from obj_data_process order by oDataProcessId desc");
		$data = array(
			'sidebar'	=> 'Claim',
			'dataSql'=>$sqlData
		);
		$this->load->view('claim/data-claim-view', $data);
    }
}