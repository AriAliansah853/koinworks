<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Upload_model extends CI_Model {

	public function upload_file($filename){
		$this->load->library('upload'); 
		
		$config['upload_path'] = './assets/excel/';
		$config['allowed_types'] = 'xlsx';
		$config['max_size']	= '2048';
		$config['overwrite'] = true;
		$config['file_name'] = $filename;
	
		$this->upload->initialize($config); 
		if($this->upload->do_upload('file')){ 

			$return = array('result' => 'success', 'file' => $this->upload->data(), 'error' => '');
			return $return;
		}else{

			$return = array('result' => 'failed', 'file' => '', 'error' => $this->upload->display_errors());
			return $return;
		}
	}
	
	public function insert_multiple($data){
		die(print_r($data));
		$this->db->insert_batch('siswa', $data);
	}
}