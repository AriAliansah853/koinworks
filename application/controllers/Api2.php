<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*
March 18, 2019 10:55:52 PM dyah line 237, 277 $ket_his
*/
class Api2 extends CI_Controller {

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
    public function testing(){
        $data=date('Y-m-d H:i:s');
        // die($data);
		$now = time(); // or your date as well
		$your_date = strtotime("2010-01-31");
		$longLoan = 2;
		$dateAsli = '2020-03-30';
		$startdate = strtotime($dateAsli);
		
		#$enddate = strtotime(date("Y-m-d", strtotime("+".$longLoan." months")));
		$enddate = strtotime( $dateAsli . "+".$longLoan." month" );
		$datediff = $enddate - $startdate;
		
		$hasil = round($datediff / (60 * 60 * 24))-1;
		die($hasil.'==');
	    
		$validInception = date("Y-m-d", strtotime("-4 months"));
	    die($validInception.'--');
	    
	    $date_now = '2020-02-15';
        //$cicilDate = date("Y-m-d", strtotime("+1 month",$date_now));
        $cicilDate = date("Y-m-d", strtotime( $date_now . "+1 month" ));
        for($cicilan=1; $cicilan <= 6; $cicilan++) {
            echo $cicilDate.'==';    
            #$cicilDate = date("Y-m-d", strtotime("+1 month", $cicilDate));
            $cicilDate = date("Y-m-d", strtotime( $date_now . "+1 month" ));
        }
        die();
    }
    
    public function get_data($key){


        $sql=$this->db->where('cust_cf_id',$key)->get('obj_customer_cf');
        if($sql->num_rows()>0){
            $rrCek=$sql->row_array();
            $id_cust=$rrCek['cust_cf_id'];
            $sqlDet=$this->db->where('cust_cf_inst_id_hd',$rrCek['cust_cf_id'])->get('obj_cust_cf_installment');
            if($sqlDet->num_rows()>0){

                foreach($sqlDet->result_array() as $rrDet){
                    $detData[]=array(
                            "TanggalJatuhTempo"=>date('m/d/Y',strtotime($rrDet['cust_cf_inst_exp_date'])),
                            "Nominal"=>$rrDet['cust_cf_inst_nominal']
                    );
                }
            }else{
                $detData=array();
            }
            $params=json_encode(array(
                "SourceID"=>'20200708KOINWORKS',
                "NoRef"=>$rrCek['cust_cf_no_ref'].date('Ymdhis'),
                "KreditorNama"=>$rrCek['cust_cf_credit_name'],
                "KreditorTglLahir"=>date('m/d/Y',strtotime($rrCek['cust_cf_credit_dob'])),
                "KreditorIDNo"=>"KTP:".$rrCek['cust_cf_credit_ktp'],
                "KreditorAlamat"=>$rrCek['cust_cf_credit_address'],
                "KreditorKota"=>$rrCek['cust_cf_credit_city'],
                "KreditorKodePos"=>$rrCek['cust_cf_credit_postal_code'],
                "KreditorHandPhone"=>trim(str_replace(' ','',$rrCek['cust_cf_credit_hp'])),
                "KreditorEmail"=>$rrCek['cust_cf_credit_email'],
                "Debitor"=>array(array(
                                    "IDDebitor"=>$rrCek['cust_cf_debitor_id'],
                                    "DebitorName"=>$rrCek['cust_cf_debitor_name'],
                                    "DebitorTglLahir"=>date('m/d/Y',strtotime($rrCek['cust_cf_debitor_dob'])),
                                    "DebitorIDNo"=>"KTP:".$rrCek['cust_cf_debitor_ktp'],
                                    "DebitorAlamat"=>$rrCek['cust_cf_debitor_address'],
                                    "DebitorKota"=>$rrCek['cust_cf_debitor_city'],
                                    "DebitorKodePos"=>$rrCek['cust_cf_debitor_postal_code'],
                                    "DebitorHandPhone"=>trim(str_replace(' ','',$rrCek['cust_cf_debitor_hp'])),
                                    "DebitorEmail"=>$rrCek['cust_cf_debitor_email'],
                                    "Pinjaman"=>$rrCek['cust_cf_loan'],
                                    "Package"=>$rrCek['cust_cf_package'],
                                    "InceptionDate"=>date('m/d/Y',strtotime($rrCek['cust_cf_inseption_date'])),
                                    "LamaPinjam"=>$rrCek['cust_cf_long_loan'],
                                    "Catatan"=>$rrCek['cust_cf_notes'],
                                    "RincianCicilan"=>$detData,
                )),
            ));
            print_r($params);
            #die();
            $tokenData=json_encode(array(
                "SourceID"=>'20200708KOINWORKS'
            ));
               
            $token=json_decode($this->curlPost('https://api.simasinsurtech.com/dataservice/createtoken.php',$tokenData,$key=''),true);
            $response=json_decode($this->curlPost('https://api.simasinsurtech.com/dataservice/asuransi_kredit_json.php',$params,$token['Token']),true);
            // print_r($token);
            // print_r($response);
            
            if($response['ErrorCode']=='0'){
              $update=array(  
                                "cust_cf_policy_number"=>$response['PolicyInsuranceNo'],
                                "cust_cf_policy_generate_date"=>date('Y-m-d',strtotime($response['IssueDate'])),
                                "cust_cf_policy_url"=>" https://api.simasinsurtech.com/schedule_polis/asuransi_kredit/print_polis.php?trxid=".$response['NoRef']."&nopolis=".$response['PolicyInsuranceNo']
                            );
                $this->db->where('cust_cf_id',$id_cust)->update('obj_customer_cf',$update);
                 
                $action 		= 'UPDATE';
                $ket_his 		= 'Send data to Simas.';
                $array_before 	= '-';
                $array_after 	= json_encode($update);
                
                $insLog = array(
                    'histLogLoginId'	=> 0,
                    'histLogLoginUser'	=> 'sistem',
                    'histLogDatetime'	=> date('Y-m-d H:i:s'),
                    'histLogType'		=> $action,
                    'histLogTable'		=> 'obj_customer_dev',
                    'histLogTableId'	=> $key,
                    'histLogShortDesc'	=> $ket_his,
                    'histLogBefore'		=> $array_before,
                    'histLogAfter'		=> $array_after,
                );
                $this->db->insert('history_log', $insLog);
                $dataResponse=array(
                    'msg'=>'Succes Data',
                    'status'=>0);
            }else{
                $dataResponse=array('msg'=>$response['ErrorMessage'],
                                    'status'=>1);
            }
            $this->sendOutput($dataResponse);
            // $this->sendOutput(json_decode($params,true));
        }else{
            echo'tidak ada';
        }
    }
    public function cron_dev(){
        $sqlHd=$this->db->where('cust_cf_status',4)->limit('4')->order_by('cust_cf_id','DESC')->get('obj_customer_cf');
        if($sqlHd->num_rows()>0){
            $no=0;
            foreach($sqlHd->result_array() as $rrCek){
                $id_cust=$rrCek['cust_cf_id']; 
                $sqlDet = $this->db->query("select * from obj_cust_cf_installment where cust_cf_inst_id_hd = '".$rrCek['cust_cf_id']."'");
                if($sqlDet->num_rows()>0){
    
                    foreach($sqlDet->result_array() as $rrDet){
                        $detData[$no][]=array(
                                "TanggalJatuhTempo"=>date('m/d/Y',strtotime($rrDet['cust_cf_inst_exp_date'])),
                                "Nominal"=>$rrDet['cust_cf_inst_nominal']
                        );
                    }
                }else{
                    $detData[$no]=array();
                }
                $params=array(
                    "SourceID"=>'20200708KOINWORKS',
                    "NoRef"=>$rrCek['cust_cf_no_ref'],
                    "KreditorNama"=>$rrCek['cust_cf_credit_name'],
                    "KreditorTglLahir"=>date('m/d/Y',strtotime($rrCek['cust_cf_credit_dob'])),
                    "KreditorIDNo"=>"KTP:".$rrCek['cust_cf_credit_ktp'],
                    "KreditorAlamat"=>$rrCek['cust_cf_credit_address'],
                    "KreditorKota"=>$rrCek['cust_cf_credit_city'],
                    "KreditorKodePos"=>$rrCek['cust_cf_credit_postal_code'],
                    "KreditorHandPhone"=>trim(str_replace(' ','',$rrCek['cust_cf_credit_hp'])),
                    "KreditorEmail"=>$rrCek['cust_cf_credit_email'],
                    "Debitor"=>array(array(
                                        "IDDebitor"=>$rrCek['cust_cf_debitor_id'],
                                        "DebitorName"=>$rrCek['cust_cf_debitor_name'],
                                        "DebitorTglLahir"=>date('m/d/Y',strtotime($rrCek['cust_cf_debitor_dob'])),
                                        "DebitorIDNo"=>"KTP:".$rrCek['cust_cf_debitor_ktp'],
                                        "DebitorAlamat"=>$rrCek['cust_cf_debitor_address'],
                                        "DebitorKota"=>$rrCek['cust_cf_debitor_city'],
                                        "DebitorKodePos"=>$rrCek['cust_cf_debitor_postal_code'],
                                        "DebitorHandPhone"=>trim(str_replace(' ','',$rrCek['cust_cf_debitor_hp'])),
                                        "DebitorEmail"=>$rrCek['cust_cf_debitor_email'],
                                        "Pinjaman"=>$rrCek['cust_cf_loan'],
                                        "Package"=>$rrCek['cust_cf_package'],
                                        "InceptionDate"=>date('m/d/Y',strtotime($rrCek['cust_cf_inseption_date'])),
                                        "LamaPinjam"=>$rrCek['cust_cf_long_loan'],
                                        "Catatan"=>$rrCek['cust_cf_notes'],
                                        "RincianCicilan"=>$detData[$no],
                                 )),
                    );
                $no++;
                print_r($params);
            }

           
        }else{
            echo'xxxx';
        }

    }
    public function cron2(){
        $sqlHd=$this->db->where('cust_cf_status',4)->limit('4')->order_by('cust_cf_id','DESC')->get('obj_customer_cf');
        if($sqlHd->num_rows()>0){
            $no=0;
            foreach($sqlHd->result_array() as $rrCek){
                $id_cust=$rrCek['cust_cf_id'];
                $sqlDet = $this->db->query("select * from obj_cust_cf_installment where cust_cf_inst_id_hd = '".$rrCek['cust_cf_id']."'");
                if($sqlDet->num_rows()>0){
    
                    foreach($sqlDet->result_array() as $rrDet){
                        $detData[$no][]=array(
                                "TanggalJatuhTempo"=>date('m/d/Y',strtotime($rrDet['cust_cf_inst_exp_date'])),
                                "Nominal"=>$rrDet['cust_cf_inst_nominal']
                        );
                    }
                }else{
                    $detData[$no]=array();
                }
                $params=json_encode(array(
                    "SourceID"=>'20200708KOINWORKS',
                    "NoRef"=>$rrCek['cust_cf_no_ref'],
                    "KreditorNama"=>$rrCek['cust_cf_credit_name'],
                    "KreditorTglLahir"=>date('m/d/Y',strtotime($rrCek['cust_cf_credit_dob'])),
                    "KreditorIDNo"=>"KTP:".$rrCek['cust_cf_credit_ktp'],
                    "KreditorAlamat"=>$rrCek['cust_cf_credit_address'],
                    "KreditorKota"=>$rrCek['cust_cf_credit_city'],
                    "KreditorKodePos"=>$rrCek['cust_cf_credit_postal_code'],
                    "KreditorHandPhone"=>trim(str_replace(' ','',$rrCek['cust_cf_credit_hp'])),
                    "KreditorEmail"=>$rrCek['cust_cf_credit_email'],
                    "Debitor"=>array(array(
                                        "IDDebitor"=>$rrCek['cust_cf_debitor_id'],
                                        "DebitorName"=>$rrCek['cust_cf_debitor_name'],
                                        "DebitorTglLahir"=>date('m/d/Y',strtotime($rrCek['cust_cf_debitor_dob'])),
                                        "DebitorIDNo"=>"KTP:".$rrCek['cust_cf_debitor_ktp'],
                                        "DebitorAlamat"=>$rrCek['cust_cf_debitor_address'],
                                        "DebitorKota"=>$rrCek['cust_cf_debitor_city'],
                                        "DebitorKodePos"=>$rrCek['cust_cf_debitor_postal_code'],
                                        "DebitorHandPhone"=>trim(str_replace(' ','',$rrCek['cust_cf_debitor_hp'])),
                                        "DebitorEmail"=>$rrCek['cust_cf_debitor_email'],
                                        "Pinjaman"=>$rrCek['cust_cf_loan'],
                                        "Package"=>$rrCek['cust_cf_package'],
                                        "InceptionDate"=>date('m/d/Y',strtotime($rrCek['cust_cf_inseption_date'])),
                                        "LamaPinjam"=>$rrCek['cust_cf_long_loan'],
                                        "Catatan"=>$rrCek['cust_cf_notes'],
                                        "RincianCicilan"=>$detData[$no]
                    )),
                ));
                // print_r($params);
                // die();
                $tokenData=json_encode(array(
                    "SourceID"=>'20200708KOINWORKS'
                ));
                   
                $token=json_decode($this->curlPost('https://api.simasinsurtech.com/dataservice/createtoken.php',$tokenData,$key=''),true);
                $response=json_decode($this->curlPost('https://api.simasinsurtech.com/dataservice/asuransi_kredit_json.php',$params,$token['Token']),true);
                // print_r($token);
                
                if(isset($response)){
                    
                    $InsApi=array(                     
                        "his_api_url"=>'https://api.simasinsurtech.com/dataservice/asuransi_kredit_json.ph',
                        "his_api_request"=>$params,
                        "his_api_response"=>json_encode($response),
                        "his_api_status"=>$response['ErrorCode'],
                        "his_api_datetime"=>date('Y-m-d H:i:s')
                    );
                    $this->db->insert('history_api',$InsApi);  
                    if($response['ErrorCode']=='0'){
                      $update=array(  
                                        "cust_cf_policy_number"=>$response['PolicyInsuranceNo'],
                                        "cust_cf_status"=>'2',
                                        "cust_cf_policy_generate_date"=>date('Y-m-d',strtotime($response['IssueDate'])),
                                        "cust_cf_policy_url"=>" https://api.simasinsurtech.com/schedule_polis/asuransi_kredit/print_polis.php?trxid=".$response['NoRef']."&nopolis=".$response['PolicyInsuranceNo']
                                    );
                        $this->db->where('cust_cf_id',$id_cust)->update('obj_customer_cf',$update);
                         
                        $action 		= 'UPDATE';
                        $ket_his 		= 'The API process was successful.';
                        $array_before 	= '-';
                        $array_after 	= json_encode($update);
                        
                        $insLog = array(
                                        'histLogLoginId'	=> 1,
                                        'histLogLoginUser'	=> 'System',
                                        'histLogDatetime'	=> date('Y-m-d H:i:s'),
                                        'histLogType'		=> $action,
                                        'histLogTable'		=> 'obj_customer_cf',
                                        'histLogTableId'	=> $id_cust,
                                        'histLogShortDesc'	=> $ket_his,
                                        'histLogBefore'		=> $array_before,
                                        'histLogAfter'		=> $array_after,
                        );
                        $this->db->insert('history_log', $insLog);

                        echo $rrCek['cust_cf_no_ref'] .'===== SUKSES <br/>';
                    }else{
                        $action 		= 'UPDATE';
                        $ket_his 		= 'The API process has failed.';
                        $array_before 	= '-';
                        $array_after 	= '-';
                        
                        $insLog = array(
                                        'histLogLoginId'	=> 1,
                                        'histLogLoginUser'	=> 'System',
                                        'histLogDatetime'	=> date('Y-m-d H:i:s'),
                                        'histLogType'		=> $action,
                                        'histLogTable'		=> 'obj_customer_cf',
                                        'histLogTableId'	=> $id_cust,
                                        'histLogStatus'		=> '2',
                                        'histLogShortDesc'	=> $ket_his.'<br>'.$response['ErrorMessage'],
                                        'histLogBefore'		=> $array_before,
                                        'histLogAfter'		=> $array_after,
                        );
                        $this->db->insert('history_log', $insLog);
                        
                        $sqlCountF = $this->db->query("select *
                                                    from history_log where histLogTableId = '".$id_cust."' and histLogTable = 'obj_customer_cf' and histLogStatus = 2");			
                        if ($sqlCountF->num_rows() > 1) {
                            $tempUpdate = array("cust_cf_status" => '4');
                            $this->db->update('obj_customer_cf', $tempUpdate, array('cust_cf_id'=>$id_cust));
                        }
                        echo $rrCek['cust_cf_no_ref'] .'===== GAGAL <br/>';
                       
                    }
                   
                }else{
                    $action 		= 'UPDATE';
                    $ket_his 		= 'The API process has failed.';
                    $array_before 	= '-';
                    $array_after 	= '-';
                    
                    $insLog = array(
                                    'histLogLoginId'	=> 1,
                                    'histLogLoginUser'	=> 'System',
                                    'histLogDatetime'	=> date('Y-m-d H:i:s'),
                                    'histLogType'		=> $action,
                                    'histLogTable'		=> 'obj_customer_cf',
                                    'histLogTableId'	=> $id_cust,
                                    'histLogStatus'		=> '2',
                                    'histLogShortDesc'	=> $ket_his.'<br>'.$response['ErrorMessage'],
                                    'histLogBefore'		=> $array_before,
                                    'histLogAfter'		=> $array_after,
                    );
                    $this->db->insert('history_log', $insLog);
                    
                    $sqlCountF = $this->db->query("select *
                                                from history_log where histLogTableId = '".$id_cust."' and histLogTable = 'obj_customer_cf' and histLogStatus = 2");			
                    if ($sqlCountF->num_rows() > 1) {
                        $tempUpdate = array("cust_cf_status" => '4');
                        $this->db->update('obj_customer_cf', $tempUpdate, array('cust_cf_id'=>$id_cust));
                    }
                    echo $rrCek['cust_cf_no_ref'] .'===== GAGAL <br/>';

                } 
                $no++;
                print_r(json_decode($params,true));      
            }

        }else{
            echo'not data';
        }
    } 
    public function cron($key=''){
        // $sqlHd=$this->db->query("SELECT * FROM `obj_customer_cf` WHERE `cust_cf_no_ref` IN ('2120200fd2','212020fd2c')");
        $sqlHd=$this->db->query("SELECT * FROM `obj_customer_cf` WHERE `cust_cf_no_ref`='".$key."'");
        // $sqlHd=$this->db->where('cust_cf_status',1)->get('obj_customer_cf');
        if($sqlHd->num_rows()>0){
            $no=0;
            foreach($sqlHd->result_array() as $rrCek){
                $id_cust=$rrCek['cust_cf_id'];
                $sqlDet = $this->db->query("select * from obj_cust_cf_installment where cust_cf_inst_id_hd = '".$rrCek['cust_cf_id']."'");
                if($sqlDet->num_rows()>0){
    
                    foreach($sqlDet->result_array() as $rrDet){
                        $detData[$no][]=array(
                                "TanggalJatuhTempo"=>date('m/d/Y',strtotime($rrDet['cust_cf_inst_exp_date'])),
                                "Nominal"=>$rrDet['cust_cf_inst_nominal']
                        );
                    }
                }else{
                    $detData[$no]=array();
                }
                $params=json_encode(array(
                    "SourceID"=>'20200708KOINWORKS',
                    "NoRef"=>$rrCek['cust_cf_no_ref'],
                    "KreditorNama"=>$rrCek['cust_cf_credit_name'],
                    "KreditorTglLahir"=>date('m/d/Y',strtotime($rrCek['cust_cf_credit_dob'])),
                    "KreditorIDNo"=>"KTP:".$rrCek['cust_cf_credit_ktp'],
                    "KreditorAlamat"=>$rrCek['cust_cf_credit_address'],
                    "KreditorKota"=>$rrCek['cust_cf_credit_city'],
                    "KreditorKodePos"=>$rrCek['cust_cf_credit_postal_code'],
                    "KreditorHandPhone"=>trim(str_replace(' ','',$rrCek['cust_cf_credit_hp'])),
                    "KreditorEmail"=>$rrCek['cust_cf_credit_email'],
                    "Debitor"=>array(array(
                                        "IDDebitor"=>$rrCek['cust_cf_debitor_id'],
                                        "DebitorName"=>$rrCek['cust_cf_debitor_name'],
                                        "DebitorTglLahir"=>date('m/d/Y',strtotime($rrCek['cust_cf_debitor_dob'])),
                                        "DebitorIDNo"=>"PASSPORT:".$rrCek['cust_cf_debitor_ktp'],
                                        "DebitorAlamat"=>$rrCek['cust_cf_debitor_address'],
                                        "DebitorKota"=>$rrCek['cust_cf_debitor_city'],
                                        "DebitorKodePos"=>$rrCek['cust_cf_debitor_postal_code'],
                                        "DebitorHandPhone"=>trim(str_replace(' ','',$rrCek['cust_cf_debitor_hp'])),
                                        "DebitorEmail"=>$rrCek['cust_cf_debitor_email'],
                                        "Pinjaman"=>$rrCek['cust_cf_loan'],
                                        "Package"=>$rrCek['cust_cf_package'],
                                        "InceptionDate"=>date('m/d/Y',strtotime($rrCek['cust_cf_inseption_date'])),
                                        "LamaPinjam"=>$rrCek['cust_cf_long_loan'],
                                        "Catatan"=>$rrCek['cust_cf_notes'],
                                        "RincianCicilan"=>$detData[$no]
                    )),
                ));
                // print_r($params);
                // die();
                $tokenData=json_encode(array(
                    "SourceID"=>'20200708KOINWORKS'
                ));
                   
                $token=json_decode($this->curlPost('https://api.simasinsurtech.com/dataservice/createtoken.php',$tokenData,$key=''),true);
                $response=json_decode($this->curlPost('https://api.simasinsurtech.com/dataservice/asuransi_kredit_json.php',$params,$token['Token']),true);
                // print_r($token);
                
                if(isset($response)){
                    
                    $InsApi=array(                     
                        "his_api_url"=>'https://api.simasinsurtech.com/dataservice/asuransi_kredit_json.ph',
                        "his_api_request"=>$params,
                        "his_api_response"=>json_encode($response),
                        "his_api_status"=>$response['ErrorCode'],
                        "his_api_datetime"=>date('Y-m-d H:i:s')
                    );
                    $this->db->insert('history_api',$InsApi);  
                    if($response['ErrorCode']=='0'){
                      $update=array(  
                                        "cust_cf_policy_number"=>$response['PolicyInsuranceNo'],
                                        "cust_cf_status"=>'2',
                                        "cust_cf_policy_generate_date"=>date('Y-m-d',strtotime($response['IssueDate'])),
                                        "cust_cf_policy_url"=>" https://api.simasinsurtech.com/schedule_polis/asuransi_kredit/print_polis.php?trxid=".$response['NoRef']."&nopolis=".$response['PolicyInsuranceNo']
                                    );
                        $this->db->where('cust_cf_id',$id_cust)->update('obj_customer_cf',$update);
                         
                        $action 		= 'UPDATE';
                        $ket_his 		= 'The API process was successful.';
                        $array_before 	= '-';
                        $array_after 	= json_encode($update);
                        
                        $insLog = array(
                                        'histLogLoginId'	=> 1,
                                        'histLogLoginUser'	=> 'System',
                                        'histLogDatetime'	=> date('Y-m-d H:i:s'),
                                        'histLogType'		=> $action,
                                        'histLogTable'		=> 'obj_customer_cf',
                                        'histLogTableId'	=> $id_cust,
                                        'histLogShortDesc'	=> $ket_his,
                                        'histLogBefore'		=> $array_before,
                                        'histLogAfter'		=> $array_after,
                        );
                        $this->db->insert('history_log', $insLog);

                        echo $rrCek['cust_cf_no_ref'] .'===== SUKSES <br/>';
                    }else{
                        $action 		= 'UPDATE';
                        $ket_his 		= 'The API process has failed.';
                        $array_before 	= '-';
                        $array_after 	= '-';
                        
                        $insLog = array(
                                        'histLogLoginId'	=> 1,
                                        'histLogLoginUser'	=> 'System',
                                        'histLogDatetime'	=> date('Y-m-d H:i:s'),
                                        'histLogType'		=> $action,
                                        'histLogTable'		=> 'obj_customer_cf',
                                        'histLogTableId'	=> $id_cust,
                                        'histLogStatus'		=> '2',
                                        'histLogShortDesc'	=> $ket_his.'<br>'.$response['ErrorMessage'],
                                        'histLogBefore'		=> $array_before,
                                        'histLogAfter'		=> $array_after,
                        );
                        $this->db->insert('history_log', $insLog);
                        
                        $sqlCountF = $this->db->query("select *
                                                    from history_log where histLogTableId = '".$id_cust."' and histLogTable = 'obj_customer_cf' and histLogStatus = 2");			
                        if ($sqlCountF->num_rows() > 1) {
                            $tempUpdate = array("cust_cf_status" => '4');
                            $this->db->update('obj_customer_cf', $tempUpdate, array('cust_cf_id'=>$id_cust));
                        }
                        echo $rrCek['cust_cf_no_ref'] .'===== GAGAL <br/>';
                       
                    }
                   
                }else{
                    $action 		= 'UPDATE';
                    $ket_his 		= 'The API process has failed.';
                    $array_before 	= '-';
                    $array_after 	= '-';
                    
                    $insLog = array(
                                    'histLogLoginId'	=> 1,
                                    'histLogLoginUser'	=> 'System',
                                    'histLogDatetime'	=> date('Y-m-d H:i:s'),
                                    'histLogType'		=> $action,
                                    'histLogTable'		=> 'obj_customer_cf',
                                    'histLogTableId'	=> $id_cust,
                                    'histLogStatus'		=> '2',
                                    'histLogShortDesc'	=> $ket_his.'<br>'.$response['ErrorMessage'],
                                    'histLogBefore'		=> $array_before,
                                    'histLogAfter'		=> $array_after,
                    );
                    $this->db->insert('history_log', $insLog);
                    
                    $sqlCountF = $this->db->query("select *
                                                from history_log where histLogTableId = '".$id_cust."' and histLogTable = 'obj_customer_cf' and histLogStatus = 2");			
                    if ($sqlCountF->num_rows() > 1) {
                        $tempUpdate = array("cust_cf_status" => '4');
                        $this->db->update('obj_customer_cf', $tempUpdate, array('cust_cf_id'=>$id_cust));
                    }
                    echo $rrCek['cust_cf_no_ref'] .'===== GAGAL <br/>';

                } 
                $no++;
                // print_r(json_decode($params,true));      
            }

        }else{
            echo'not data';
        }
    }

    public function cron22($key=''){
        // $sqlHd=$this->db->query("SELECT * FROM `obj_customer_cf` WHERE `cust_cf_no_ref` IN ('2120200fd2','212020fd2c')");
        $sqlHd=$this->db->query("SELECT * FROM `obj_customer_cf` WHERE `cust_cf_no_ref`='".$key."'");
        // $sqlHd=$this->db->where('cust_cf_status',1)->get('obj_customer_cf');
        if($sqlHd->num_rows()>0){
            $no=0;
            $rrCek=$sqlHd->row_array();
            // foreach($sqlHd->result_array() as $rrCek){
                $id_cust=$rrCek['cust_cf_id'];
                $sqlDet = $this->db->query("select * from obj_cust_cf_installment where cust_cf_inst_id_hd = '".$rrCek['cust_cf_id']."'");
                if($sqlDet->num_rows()>0){
    
                    foreach($sqlDet->result_array() as $rrDet){
                        $detData[$no][]=array(
                                "TanggalJatuhTempo"=>date('m/d/Y',strtotime($rrDet['cust_cf_inst_exp_date'])),
                                "Nominal"=>$rrDet['cust_cf_inst_nominal']
                        );
                    }
                }else{
                    $detData[$no]=array();
                }
                $params=json_encode(array(
                    "SourceID"=>'20200708KOINWORKS',
                    "NoRef"=>$rrCek['cust_cf_no_ref'],
                    "KreditorNama"=>$rrCek['cust_cf_credit_name'],
                    "KreditorTglLahir"=>date('m/d/Y',strtotime($rrCek['cust_cf_credit_dob'])),
                    "KreditorIDNo"=>"KTP:".$rrCek['cust_cf_credit_ktp'],
                    "KreditorAlamat"=>$rrCek['cust_cf_credit_address'],
                    "KreditorKota"=>$rrCek['cust_cf_credit_city'],
                    "KreditorKodePos"=>$rrCek['cust_cf_credit_postal_code'],
                    "KreditorHandPhone"=>trim(str_replace(' ','',$rrCek['cust_cf_credit_hp'])),
                    "KreditorEmail"=>$rrCek['cust_cf_credit_email'],
                    "Debitor"=>array(array(
                                        "IDDebitor"=>$rrCek['cust_cf_debitor_id'],
                                        "DebitorName"=>$rrCek['cust_cf_debitor_name'],
                                        "DebitorTglLahir"=>date('m/d/Y',strtotime($rrCek['cust_cf_debitor_dob'])),
                                        "DebitorIDNo"=>"PASSPORT:".$rrCek['cust_cf_debitor_ktp'],
                                        "DebitorAlamat"=>$rrCek['cust_cf_debitor_address'],
                                        "DebitorKota"=>$rrCek['cust_cf_debitor_city'],
                                        "DebitorKodePos"=>$rrCek['cust_cf_debitor_postal_code'],
                                        "DebitorHandPhone"=>trim(str_replace(' ','',$rrCek['cust_cf_debitor_hp'])),
                                        "DebitorEmail"=>$rrCek['cust_cf_debitor_email'],
                                        "Pinjaman"=>$rrCek['cust_cf_loan'],
                                        "Package"=>$rrCek['cust_cf_package'],
                                        "InceptionDate"=>date('m/d/Y',strtotime($rrCek['cust_cf_inseption_date'])),
                                        "LamaPinjam"=>$rrCek['cust_cf_long_loan'],
                                        "Catatan"=>$rrCek['cust_cf_notes'],
                                        "RincianCicilan"=>$detData[$no]
                    )),
                ));
                print_r($params);
                // die();
                $tokenData=json_encode(array(
                    "SourceID"=>'20200708KOINWORKS'
                ));
                   
                $token=json_decode($this->curlPost('https://api.simasinsurtech.com/dataservice/createtoken.php',$tokenData,$key=''),true);

                // $response=json_decode($this->curlPost('https://api.simasinsurtech.com/dataservice/asuransi_kredit_json.php',$params,$token['Token']),true);
                print_r($token);
                die();
                if(isset($response)){
                    
                    $InsApi=array(                     
                        "his_api_url"=>'https://api.simasinsurtech.com/dataservice/asuransi_kredit_json.ph',
                        "his_api_request"=>$params,
                        "his_api_response"=>json_encode($response),
                        "his_api_status"=>$response['ErrorCode'],
                        "his_api_datetime"=>date('Y-m-d H:i:s')
                    );
                    $this->db->insert('history_api',$InsApi);  
                    if($response['ErrorCode']=='0'){
                      $update=array(  
                                        "cust_cf_policy_number"=>$response['PolicyInsuranceNo'],
                                        "cust_cf_status"=>'2',
                                        "cust_cf_policy_generate_date"=>date('Y-m-d',strtotime($response['IssueDate'])),
                                        "cust_cf_policy_url"=>" https://api.simasinsurtech.com/schedule_polis/asuransi_kredit/print_polis.php?trxid=".$response['NoRef']."&nopolis=".$response['PolicyInsuranceNo']
                                    );
                        $this->db->where('cust_cf_id',$id_cust)->update('obj_customer_cf',$update);
                         
                        $action 		= 'UPDATE';
                        $ket_his 		= 'The API process was successful.';
                        $array_before 	= '-';
                        $array_after 	= json_encode($update);
                        
                        $insLog = array(
                                        'histLogLoginId'	=> 1,
                                        'histLogLoginUser'	=> 'System',
                                        'histLogDatetime'	=> date('Y-m-d H:i:s'),
                                        'histLogType'		=> $action,
                                        'histLogTable'		=> 'obj_customer_cf',
                                        'histLogTableId'	=> $id_cust,
                                        'histLogShortDesc'	=> $ket_his,
                                        'histLogBefore'		=> $array_before,
                                        'histLogAfter'		=> $array_after,
                        );
                        $this->db->insert('history_log', $insLog);

                        echo $rrCek['cust_cf_no_ref'] .'===== SUKSES <br/>';
                    }else{
                        $action 		= 'UPDATE';
                        $ket_his 		= 'The API process has failed.';
                        $array_before 	= '-';
                        $array_after 	= '-';
                        
                        $insLog = array(
                                        'histLogLoginId'	=> 1,
                                        'histLogLoginUser'	=> 'System',
                                        'histLogDatetime'	=> date('Y-m-d H:i:s'),
                                        'histLogType'		=> $action,
                                        'histLogTable'		=> 'obj_customer_cf',
                                        'histLogTableId'	=> $id_cust,
                                        'histLogStatus'		=> '2',
                                        'histLogShortDesc'	=> $ket_his.'<br>'.$response['ErrorMessage'],
                                        'histLogBefore'		=> $array_before,
                                        'histLogAfter'		=> $array_after,
                        );
                        $this->db->insert('history_log', $insLog);
                        
                        $sqlCountF = $this->db->query("select *
                                                    from history_log where histLogTableId = '".$id_cust."' and histLogTable = 'obj_customer_cf' and histLogStatus = 2");			
                        if ($sqlCountF->num_rows() > 1) {
                            $tempUpdate = array("cust_cf_status" => '4');
                            $this->db->update('obj_customer_cf', $tempUpdate, array('cust_cf_id'=>$id_cust));
                        }
                        echo $rrCek['cust_cf_no_ref'] .'===== GAGAL <br/>';
                       
                    }
                   
                }else{
                    $action 		= 'UPDATE';
                    $ket_his 		= 'The API process has failed.';
                    $array_before 	= '-';
                    $array_after 	= '-';
                    
                    $insLog = array(
                                    'histLogLoginId'	=> 1,
                                    'histLogLoginUser'	=> 'System',
                                    'histLogDatetime'	=> date('Y-m-d H:i:s'),
                                    'histLogType'		=> $action,
                                    'histLogTable'		=> 'obj_customer_cf',
                                    'histLogTableId'	=> $id_cust,
                                    'histLogStatus'		=> '2',
                                    'histLogShortDesc'	=> $ket_his.'<br>'.$response['ErrorMessage'],
                                    'histLogBefore'		=> $array_before,
                                    'histLogAfter'		=> $array_after,
                    );
                    $this->db->insert('history_log', $insLog);
                    
                    $sqlCountF = $this->db->query("select *
                                                from history_log where histLogTableId = '".$id_cust."' and histLogTable = 'obj_customer_cf' and histLogStatus = 2");			
                    if ($sqlCountF->num_rows() > 1) {
                        $tempUpdate = array("cust_cf_status" => '4');
                        $this->db->update('obj_customer_cf', $tempUpdate, array('cust_cf_id'=>$id_cust));
                    }
                    echo $rrCek['cust_cf_no_ref'] .'===== GAGAL <br/>';

                } 
                $no++;
                // print_r(json_decode($params,true));      
            // }

        }else{
            echo'not data';
        }
    }
    public function curlPost($url,$params,$token=''){
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
        CURLOPT_POSTFIELDS =>$params,
        CURLOPT_HTTPHEADER => array(
            "Content-Type: application/json",
            "Authorization: Bearer ".$token."",
            "Cookie: BIGipServerapi.simasinsurtech.com.app~api.simasinsurtech.com_pool=3848906944.20480.0000"
        ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        return $response;

    }
	
}