<?php 
header('Access-Control-Allow-Origin: *');
class Auth extends CI_Controller {

	function __construct() {
        parent::__construct();
    }
    private function sendOutput($response){
        $this->output
            ->set_status_header(200)
            ->set_content_type('application/json', 'utf-8')
            ->set_output(json_encode($response, JSON_PRETTY_PRINT))
            ->_display();
        exit;
    }

    private function getUserIP()
    {
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

    public function index(){
    	$this->login();
    }

    public function injectUser($data){
        $insertData = array(
            'rLoginUser' => $data,
            'rLoginName' => $data,
            'rLoginSourceId' => 5,
            'rLoginPassword' => password_hash('123456',PASSWORD_DEFAULT),
            'rLoginVisible'     => 1
        );

        // $insertData['useridmd5'] = md5($insertData['username'].$insertData['phone'].$insertData['email'].$insertData['password']);

        if ($this->db->insert('ref_login',$insertData)) {
            echo "SUKSESb";
        } else {
            echo "GAGAL";
        }
    }

    public function injectUserUpdate($id){
        $updateData = array(
            "rLoginPassword" => password_hash('123456',PASSWORD_DEFAULT),
        );

        // $passUser=password_hash($Password,PASSWORD_DEFAULT);

        // $updateData['useridmd5'] = md5($updateData['username'].$updateData['phone'].$updateData['email'].$updateData['password']);

        if ($this->db->update('ref_login',$updateData, array('rLoginId' => $id))) {
            echo "SUKSESb";
        } else {
            echo "GAGAL";
        }
    }


    public function login(){
		
        if ($this->session->userdata('system_in') ) {
            redirect('dashboard');
        };
		
        $data = array(
            'ipAddress' => $this->getUserIP()
        );
        $this->load->view('login_view',$data);
       
    }
    public function autolog(){

        // print_r($_POST);
        // die();
        // $sqlLog=$this->db->where('rLoginUser',$this->input->post('username'))->get('ref_login');
        $sqlLog=$this->db->query("select * from ref_login where rLoginUser = '".$this->input->post('username')."' and rLoginVisible = '1'");
        if($sqlLog->num_rows()>0){
            $rrLog=$sqlLog->row_array();
            if($rrLog['rLoginSourceId']==5){
                if(password_verify($this->input->post('password'), $rrLog['rLoginPassword'])) {
                    	$newdata = array(
                    		'userId'        => $rrLog['rLoginId'],
                    		'key'           => $rrLog['rLoginKey'] ,
                    		'name'          => $rrLog['rLoginUser'],
                    		'akses_user'    => $rrLog['rLoginSourceId'],
                    		'username'      => $rrLog['rLoginName'],
                    		'ip_user'		=>$this->getUserIP(),
                    		'system_in'     => TRUE,
                    	);

                    	$this->session->set_userdata($newdata);
                    	$itemArray['status']='true';
                    	$itemArray['type']=0;
                    	$itemArray['msg']='Your login is successful';
                }else{
                    	$itemArray['status']='false';
                    	$itemArray['type']=1;
                    	$itemArray['msg']='Password is wrong';
                }
            }else{
                $itemArray['status']='false';
                $itemArray['type']=1;
                $itemArray['msg']='Your access is denied';
            }
		}else{
			$itemArray['status']='false';
			$itemArray['type']=1;
			$itemArray['msg']='Username is wrong';
        }
        $this->sendOutput($itemArray);
    }
    public function logout(){
        $this->session->unset_userdata('userId');
		$this->session->unset_userdata('name');
		$this->session->unset_userdata('key');
		$this->session->unset_userdata('ip_user');
		$this->session->unset_userdata('username');
		$this->session->unset_userdata('akses_user');
        $this->session->unset_userdata('system_in');
		// redirect(base_url());
		$itemArray['status']='false';
		$itemArray['type']=1;
		$itemArray['msg']='Your logout is successful';
		$this->sendOutput($itemArray);
    }
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */