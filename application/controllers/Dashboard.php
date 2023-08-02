<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Dashboard extends CI_Controller {

	function __construct() {
        parent::__construct();
        if ( !($this->session->userdata('system_in')) ) {
            redirect(base_url());
        } else {
        	$this->session->set_userdata('sidebar', 'dashboard');
        }
    }

	public function index(){
		$this->dashboard();
	}

	public function dashboard(){
		$data = array(
			'sidebar'	=> 'dashboard',
		);
		$this->load->view('dashboard_view', $data);	
	}

	public function pimpmyprofile(){
		echo "pimpmyprofile";
	}
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */