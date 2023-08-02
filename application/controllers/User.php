<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class User extends CI_Controller {
	public function __construct()
	{
		parent::__construct();
		 date_default_timezone_set("Asia/Jakarta");
		 
	}
    public function index(){
		// $this->view();
		// echo password_hash('1234567',PASSWORD_DEFAULT);
			$source=5; 
			$User='micro1';
			$Username='Team Micro'; 
			$Password='123456'; 
			$visible=1;
			$passUser=password_hash($Password,PASSWORD_DEFAULT);
			$dataInput=array(
							"rLoginSourceId"=>$source,
							"rLoginUser"=>$User,
							"rLoginName"=>$Username,
							"rLoginPassword"=>$passUser,
							"rLoginVisible"=>$visible,
							"rLoginInsertUser"=>24,
							"rLoginInsertDatetime"=>date('Y-m-d H:i:s'),
				);
			// die(print_r($dataInput));
			$this->db->insert('ref_login',$dataInput);
    }

    public function view(){

        $data = array(
            'sidebar'   => 'user',
            'listUser'  => $this->db->query("SELECT * FROM ref_login"),
        );
        $this->load->view('user/user_view',$data);
    }

    public function Add($flag=''){
		if($flag=='edit'){
			// die($_GET);
		}else{
			$data = array(
				'sidebar'   => 'user',
				'errorData'   =>1,
								
			);
		}
        $this->load->view('user/user_form_view',$data);
    }
	 public function save_user(){
		// die(print_r($_POST));
		if(count(($_POST))>0){
			
			$source=$_POST['source']; 
			$User=$_POST['User'];
			$Username=$_POST['Username']; 
			$Password=$_POST['Password']; 
			$visible=$_POST['visible'];
			$passUser=password_hash($Password,PASSWORD_DEFAULT);
			// if(password_verify($Password, $passUser)){
				// die($Password);
			// }else{	
				// die($passUser);
			// }
				$sqlCek=$this->db->where('rLoginUser',$User)->get('ref_login');
				if($sqlCek->num_rows()>0){
					 $data = array(
								'sidebar'   => 'user',
								'errorData'   =>0,
							);
					$this->load->view('user/user_form_view',$data);
				}else{
					$dataInput=array(
									"rLoginSourceId"=>$source,
									"rLoginUser"=>$User,
									"rLoginName"=>$Username,
									"rLoginPassword"=>$passUser,
									"rLoginVisible"=>$visible,
									"rLoginInsertUser"=>'0',
									"rLoginInsertDatetime"=>date('Y-m-d H:i:s'),
						);
					// die(print_r($dataInput));
					$this->db->insert('ref_login',$dataInput);
					$idIns=$this->db->insert_id();
					$dataUpdate=array('rLoginKey'=>md5($idIns));
					$this->db->where('rLoginId',$idIns)->update('ref_login',$dataUpdate);
					
					
					redirect('user');
					
				}
				
				
		}else{
			redirect('user/add');
		}
	}
	public function edit_user($id){
		$sqlUser=$this->db->where('rLoginId',$id)->get('ref_login');
		
		if($sqlUser->num_rows()>0){
			$rrUser=$sqlUser->row_array();
			$data = array(
								'sidebar'   => 'user',
								'errorData' =>1,
								'source'  =>$rrUser['rLoginSourceId'],
								'user'    =>$rrUser['rLoginUser'],
								'name'    =>$rrUser['rLoginName'],
								'password'  =>$rrUser['rLoginPassword'],
								'visible'   =>$rrUser['rLoginVisible'],
								'idUser'    =>$rrUser['rLoginId'],
							);
			$this->load->view('user/edit_form_view',$data);
		
		
		}else{
			redirect('user');
		}
	}
	public function save_menu(){
		$dataInsert=array(
				"mMenuUserLoginId"=>$_POST['idUser'],
				"mMenuUserMenuId"=>$_POST['menu'],
				"mMenuUserInsertUser"=>'24',
				"mMenuUserInsertDatetime"=>date('Y-m-d H:i:s')
		);
		// die(print_r($dataInsert));
		$sqlCek=$this->db->where('mMenuUserLoginId',$_POST['idUser'])
						->where('mMenuUserMenuId',$_POST['menu'])
						->get('map_menu_user');
		if($sqlCek->num_rows()>0){
			redirect('user/edit_user/'.$_POST['idUser']);
		}else{
			$this->db->insert('map_menu_user',$dataInsert);
		}
		redirect('user/edit_user/'.$_POST['idUser']);
		
	}
	public function deleteMenu($id){
		$this->db->where('mMenuUserId',$id)->delete('map_menu_user');
		
		
		$itemArray['status']='true';
		$itemArray['type']=1;
		$itemArray['msg']='Delete Success';
		
		$this->output
				->set_status_header(200)
				->set_content_type('application/json', 'utf-8')
				->set_output(json_encode($itemArray, JSON_PRETTY_PRINT))
				->_display();
			exit;
		
	}

}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */