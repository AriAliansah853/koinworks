<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Serverside extends CI_Controller {
    function __construct() {
		parent::__construct();
		$this->load->model('middleware','middle');
    }
    
    function get_all_data($where)  
    {  
        $sub_query=$where;
        
        $query = $this->db->query("select * from ".$sub_query."");
        return $query->num_rows();  
    }   

    function make_search($column,$order,$like)  
    { 
        if(isset($_POST["search"]["value"])) {  
            $search_data = $this->input->post('search')['value']; 
            $like =$like;
        } else {

            $like = "";

        }
        if(isset($_POST["order"])) {  
            $order_by = ''.$column[$_POST['order']['0']['column']].' '.$_POST['order']['0']['dir'].'';
           
        } else {  
           
            $order_by = $order.' DESC';   
        }  
        
        return $like.' ORDER BY '.$order_by;
    }
    function make_data($where,$column,$order,$like){  
        $make_query = $this->make_search($column,$order,$like);  
        if($_POST["length"] != -1)  
        {  
            $limit = "LIMIT ".$_POST['start'].", ".$_POST['length'];  
        } else {
            $limit = "";
        }
        $sub_query=$where;
        $query = $this->db->query("select * from ".$sub_query." ".$make_query." ".$limit."");

        return $query->result();  
    }
    function get_filtered($where,$column,$order,$like){  
        $make_query = $this->make_search($column,$order,$like);  
        
        $sub_query=$where;
        
        $query = $this->db->query("select * from ".$sub_query." ".$make_query."");
        return $query->num_rows();  
    }
   
    public function data_policy($key=''){
        if($key==''){
            $whereId='';
        }else{
            $whereId=base64_decode(urldecode($key));
        }
        
        $query="obj_customer_cf where cust_cf_credit_ktp !=''  ".$whereId;

        $order='cust_cf_id';
        $search_data = $this->input->post('search')['value']; 
        $like ="  AND  ((UPPER(cust_cf_source_label) like '%".strtoupper($search_data)."%')
                        OR (UPPER(cust_cf_no_ref) like '%".strtoupper($search_data)."%')
                        OR (UPPER(cust_cf_upload_code) like '%".strtoupper($search_data)."%')
                        OR (UPPER(cust_cf_credit_name) like '%".strtoupper($search_data)."%')
                        OR (UPPER(cust_cf_credit_ktp) like '%".strtoupper($search_data)."%')
                        OR (UPPER(cust_cf_credit_hp) like '%".strtoupper($search_data)."%')
                        OR (UPPER(cust_cf_credit_email) like '%".strtoupper($search_data)."%')
                        OR (UPPER(cust_cf_debitor_id) like '%".strtoupper($search_data)."%')
                        OR (UPPER(cust_cf_debitor_ktp) like '%".strtoupper($search_data)."%')
                        OR (UPPER(cust_cf_debitor_postal_code) like '%".strtoupper($search_data)."%')
                        OR (UPPER(cust_cf_debitor_hp) like '%".strtoupper($search_data)."%')
                        OR (UPPER(cust_cf_debitor_email) like '%".strtoupper($search_data)."%')
                )";
        $column=array(null,null,'cust_cf_no_ref','cust_cf_upload_code','cust_cf_credit_name','cust_cf_credit_dob','cust_cf_credit_ktp','cust_cf_credit_address','cust_cf_credit_city','cust_cf_credit_postal_code','cust_cf_credit_hp','cust_cf_credit_email','cust_cf_debitor_id','cust_cf_debitor_name','cust_cf_debitor_dob','cust_cf_debitor_ktp','cust_cf_debitor_address','cust_cf_debitor_city','cust_cf_debitor_postal_code','cust_cf_debitor_hp','cust_cf_debitor_email','cust_cf_loan','cust_cf_package','cust_cf_inseption_date','cust_cf_long_loan','cust_cf_insert_date','cust_cf_policy_number',null);
        $posts = $this->make_data($query,$column,$order,$like);  
        $dataLead = array();
        if(!empty($posts))
        {   
            $no=1; 
            
            foreach ($posts as $post) {
                if($post->cust_cf_policy_url==''){
                    $urlPolicy='';
                    $data="('".urlencode(str_replace('=', '', base64_encode(json_encode(array('dataKey'=>$post->cust_cf_id)))))."')";
                    if($post->cust_cf_status=='4'){
                        $btnDel='<button class="btn btn-danger btn-sm" onclick="update'.$data.'">Delete</button>';
                    }else{     
                        $btnDel='<button class="btn btn-warning btn-sm">Process</button>';
                    }

                    $button='&nbsp;&nbsp;<a href="javascript:void(0)" onclick="editData('.$post->cust_cf_id.')">'.$btnDel.'</a>&nbsp;&nbsp;<button class="btn btn-sm btn-info" onclick="viewDetail('.$post->cust_cf_id.')">installment</button>';
                }else{
                    $urlPolicy='<a href="Upload2/download_polis/'.$post->cust_cf_no_ref.'" target="_blank"><button class="btn btn-info">Download Policy</button></a>';
                    $button='&nbsp;&nbsp;<a href="javascript:void(0)" onclick="editData('.$post->cust_cf_id.')"><button class="btn btn-success btn-sm">Success</button></a>&nbsp;&nbsp;<button class="btn btn-sm btn-info" onclick="viewDetail('.$post->cust_cf_id.')">installment</button>';
                }
                if($post->cust_cf_long_loan == '730'){
                    $monthNum = sprintf("%02s", $post->cust_cf_long_loan);
                    $monthName = date("F", strtotime($monthNum));

                    $months = floor($post->cust_cf_long_loan / 30);
                }else if($post->cust_cf_long_loan == '28'){
                    $monthNum = sprintf("%02s", $post->cust_cf_long_loan);
                    $monthName = date("F", strtotime($monthNum));

                    $months = floor($post->cust_cf_long_loan / 28);
                }else{
                    $monthNum = sprintf("%02s", $post->cust_cf_long_loan);
                    $monthName = date("F", strtotime($monthNum));

                    $months = floor($post->cust_cf_long_loan / 29);
                }
                

                        $subData= array(); 
                        $subData[]='<a href="javascript:void(0)" onclick="loghistory('.$post->cust_cf_id.','.$post->cust_cf_id.')"><button class="btn btn-info btn-sm">Log</button></a>'.$button;
                        $subData[]=$no++; 
                        $subData[]=$post->cust_cf_no_ref;
                        $subData[]=$post->cust_cf_upload_code;
                        $subData[]=$post->cust_cf_credit_name;
                        $subData[]=$post->cust_cf_credit_dob;
                        $subData[]=$post->cust_cf_credit_ktp;
                        $subData[]=$post->cust_cf_credit_address;
                        $subData[]=$post->cust_cf_credit_city;
                        $subData[]=$post->cust_cf_credit_postal_code;
                        $subData[]=$post->cust_cf_credit_hp;
                        $subData[]=$post->cust_cf_credit_email;
                        $subData[]=$post->cust_cf_debitor_id;
                        $subData[]=$post->cust_cf_debitor_name;
                        $subData[]=$post->cust_cf_debitor_dob;
                        $subData[]=$post->cust_cf_debitor_ktp;
                        $subData[]=$post->cust_cf_debitor_address;
                        $subData[]=$post->cust_cf_debitor_city;
                        $subData[]=$post->cust_cf_debitor_postal_code;
                        $subData[]=$post->cust_cf_debitor_hp;
                        $subData[]=$post->cust_cf_debitor_email;
                        $subData[]=$post->cust_cf_loan;
                        $subData[]=$post->cust_cf_package;
                        $subData[]=$post->cust_cf_inseption_date;
                        $subData[]=$months ." Months ";
                        $subData[]=$post->cust_cf_insert_date;
                        $subData[]=$post->cust_cf_policy_number;
                        $subData[]=$urlPolicy;
                        $subData[]=$post->cust_cf_premium;
                        $dataLead[] = $subData; 
                
            }
            
        }
        $json_data = array(
            "draw"            => intval($this->input->post('draw')),  
            "recordsTotal"    => $this->get_all_data($query),  
            "recordsFiltered" => $this->get_filtered($query,$column,$order,$like), 
            "data"            => $dataLead   
            );
            $this->middle->sendResponse($json_data);
    }
    public function data_upload(){
        
        $query="obj_history_upload ,ref_login where rLoginId=oHistUploadLoginId AND rLoginSourceId=5";

        $order='oHistUploadCode';
        $search_data = $this->input->post('search')['value']; 
        $like ="  AND  ((UPPER(oHistUploadUploadTime) like '%".strtoupper($search_data)."%')
                        OR (UPPER(oHistUploadCode) like '%".strtoupper($search_data)."%')
                        OR (UPPER(oHistUploadSuccess) like '%".strtoupper($search_data)."%')
                        OR (UPPER(oHistUploadFailed) like '%".strtoupper($search_data)."%')
                        OR (UPPER(oHistUploadExisting) like '%".strtoupper($search_data)."%')
                        
                )";
        $column=array(null,'oHistUploadCode','oHistUploadUploadTime','oHistUploadSuccess ','oHistUploadFailed ','oHistUploadExisting ');
        $posts = $this->make_data($query,$column,$order,$like);  
        $dataLead = array();
        if(!empty($posts))
        {   
            $no=1; 
            
            foreach ($posts as $post) {
                
                        $subData= array(); 
                        $subData[]=$no++;
                        $subData[]=$post->oHistUploadCode;
                        $subData[]=$post->oHistUploadUploadTime;
                        $subData[]=$post->oHistUploadSuccess;
                        $subData[]=$post->oHistUploadFailed;
                        $subData[]=$post->oHistUploadExisting;
                      
                        $dataLead[]=$subData;
                
            }
            
        }
        $json_data = array(
            "draw"            => intval($this->input->post('draw')),  
            "recordsTotal"    => $this->get_all_data($query),  
            "recordsFiltered" => $this->get_filtered($query,$column,$order,$like), 
            "data"            => $dataLead   
            );
            $this->middle->sendResponse($json_data);
    }
}