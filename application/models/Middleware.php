<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Middleware extends CI_Model {
	function __construct() {
		parent::__construct();
		date_default_timezone_set("Asia/Jakarta");
            if (!($this->session->userdata('system_in'))) {
                redirect();
            } else {
            }
    }
    public function sendResponse($itemArray){
        $this->output
			->set_status_header(200)
			->set_content_type('application/json', 'utf-8')
			->set_output(json_encode($itemArray, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES))
			->_display();
		exit;
    }
    public function getUserIP() {
        $client  = @$_SERVER['HTTP_CLIENT_IP'];
        $forward = @$_SERVER['HTTP_X_FORWARDED_FOR'];
        $remote  = $_SERVER['REMOTE_ADDR'];

        if(filter_var($client, FILTER_VALIDATE_IP))
        {
            $ip = $client;
        }
        elseif(filter_var($forward, FILTER_VALIDATE_IP))
        {
            $ip = $forward;
        }
        else
        {
            $ip = $remote;
        }

        return $ip;
    }
}