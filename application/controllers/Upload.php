<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*
March 18, 2019 10:55:52 PM dyah line 237, 277 $ket_his
*/
class Upload extends CI_Controller {

	function __construct() {
        parent::__construct();
        $this->load->model('middleware','middle');
        date_default_timezone_set("Asia/Jakarta");
    }
    private function sendOutput($response){
        $this->output
            ->set_status_header(200)
            ->set_content_type('application/json', 'utf-8')
            ->set_output(json_encode($response, JSON_PRETTY_PRINT))
            ->_display();
        exit;
    }

	public function index(){
		$this->upload();
	}
	public function upload(){
		$data = array(
			'sidebar'	=> 'upload_data',
		);
		$this->load->view('upload/upload_data_view', $data);	
	}
	public function history_upload(){
		$data = array(
			'sidebar'	=> 'upload_history',
			'listData' 	=> '',
		);
		$this->load->view('upload/history_upload_view', $data);
    }
    public function import_data(){

        $dateKode = 'KOINWORKS'.date('Ymd');
        $sqlUrut = $this->db->query("SELECT coalesce(MAX(SUBSTR(oHistUploadCode,18,5)),'0') AS maks FROM obj_history_upload WHERE SUBSTR(oHistUploadCode,1,17)= '".$dateKode."'");

        $rowurut = $sqlUrut->row_array();
                    
        if($rowurut['maks'] == '0') {
            $urut = 00001;
        } else {
            $urut = ($rowurut['maks']*1) + 1;
        }
	    $kodeUpload = $dateKode.''.str_pad($urut,5,'0',STR_PAD_LEFT);
        $fileName = $this->input->post('upload_file', TRUE);
        $config['upload_path'] = './assets/upload/koins/'; 
        $config['file_name'] = $kodeUpload.'_'.date('YmdHis').'_'.$this->session->userdata('name');
        $config['allowed_types'] = 'xls|xlsx|csv|ods|ots';
        $this->load->library('upload', $config);
        $this->upload->initialize($config);

        if(!$this->upload->do_upload('upload_file')){
            $error = array('error' => $this->upload->display_errors());	
            $itemArray['status']='false';
            $itemArray['type']=1;
            $itemArray['msg']='Upload Failed';				    
        }else {
            $media = $this->upload->data();
            $inputFileName = 'assets/upload/koins/'.$media['file_name'];
            $this->load->library(array('PHPExcel','PHPExcel/IOFactory'));
            try {
                 $inputFileType = IOFactory::identify($inputFileName);
                 $objReader = IOFactory::createReader($inputFileType);
                 $objPHPExcel = $objReader->load($inputFileName);
            }catch(Exception $e) {
             die('Error loading file "'.pathinfo($inputFileName,PATHINFO_BASENAME).'": '.$e->getMessage());
            }
              $sheet = $objPHPExcel->getSheet(0);
              $highestRow = $sheet->getHighestRow();
              $highestColumn = $sheet->getHighestColumn();
              for ($row = 2; $row <= $highestRow; $row++){  
                    $rowData = $sheet->rangeToArray('A' . $row . ':' . $highestColumn . $row,NULL,TRUE,FALSE);
                   $dataRow[]=array( 
                                "TrxId"=>trim($rowData[0][0] ),
                                "LoanType"=>trim($rowData[0][1] ),
                                "custName"=>trim($rowData[0][2]),
                                "custIdCard"=>trim($rowData[0][3]),
                                "custDob"=>trim($rowData[0][4]),
                                "custAddress"=>trim($rowData[0][5]),
                                "occupation"=>trim($rowData[0][6]),
                                "city"=>trim($rowData[0][7]),
                                "zipCode"=>trim($rowData[0][8]),
                                "custPhone"=>trim($rowData[0][9]),
                                "custEmail"=>trim($rowData[0][10]),
                                "sumInsured"=>trim($rowData[0][11]),
                                "period"=>trim($rowData[0][12]),
                                "premiumRate"=>trim($rowData[0][13]),
                                "premium"=>trim($rowData[0][14]),
                                "policyEffective"=>trim($rowData[0][15])
                            );
             }
             $errorData=0;
             $successData=0;
             $existingCount=0;
             $dataError=array();
             $dataSuccess=array();
             $rowCek=1;
            foreach($dataRow as $key=>$value){
                $cekData=$this->validation($value);
                
                $errorData=$errorData+$cekData['dataError'];
                $successData=$successData+$cekData['dataSuccess'];
                $existingCount=$existingCount+$cekData['duplicate'];
                if(count($cekData['successRow'])>0){
                    $dataSuccess[]=$cekData['successRow'];
                }
                if(count($cekData['errorRow'])>0){
                    $dataError[]=$cekData['errorRow'];
                }
                $rowCek++;

                
            }
            $sqlUser = $this->db->query("SELECT * FROM ref_login WHERE rLoginKey = '".$this->session->userdata('key')."' LIMIT 1 ");
		    $rrUser = $sqlUser->row_array();
		    if ($sqlUser->num_rows() > 0) {
		    	$userKey	= $rrUser['rLoginKey'];
		    	$sourceId 	= $rrUser['rLoginSourceId'];
		    } else {
		    	$userKey	= $this->session->userdata('key');
		    	$sourceId 	= $this->session->userdata('akses_user');
            }
                $insHistoryUpload = array(
                                            'oHistUploadCode'		=> $kodeUpload,
                                            'oHistUploadUploadTime'	=> date('Y-m-d H:i:s'),
                                            'oHistUploadSuccess'	=> $successData,
                                            'oHistUploadFailed'		=> $errorData,
                                            'oHistUploadExisting'	=> $existingCount,
                                            'oHistUploadLoginId'	=> $this->session->userdata('userId'),
                                        );
                $this->db->insert('obj_history_upload', $insHistoryUpload);
                $id_history = $this->db->insert_id();
                $action = 'INSERT';
                $ket_his = 'Processing upload excel data, with file name : '.$media['file_name'];
                $array_before = '-';
                $array_after = '-';
                $insLog = array(
                                'histLogLoginId'	=> $this->session->userdata('userId'),
                                'histLogLoginUser'	=> $this->session->userdata('name'),
                                'histLogDatetime'	=> date('Y-m-d H:i:s'),
                                'histLogType'		=> $action,
                                'histLogTable'		=> 'temp_data_process',
                                'histLogTableId'	=> $id_history,
                                'histLogShortDesc'	=> $ket_his,
                                'histLogBefore'		=> $array_before,
                                'histLogAfter'		=> $array_after,
                            );
                $this->db->insert('history_log', $insLog);
            if($successData>0){
                $dataInsert = array(
                    'tDataProcessSourceId'			=>5,
                    'tDataProcessLoginKey'			=> $userKey,
                    'tDataProcessLocalIp'			=> $this->getUserIP(),
                    'tDataProcessIp'				=> $this->getUserIP(),
                    'tDataProcessUploadCode'		=> $kodeUpload,
                    'tDataProcessExcelRow'			=> json_encode($dataSuccess),
                    'tDataProcessExcelName'			=> $inputFileName,
                    'tDataProcessUploadDatetime'	=> date('Y-m-d H:i:s'),
                    'tDataProcessStatus'			=> 0,
                );
                if ($this->db->insert('temp_data_process', $dataInsert)) {
                    $idInsertTemp = $this->db->insert_id();
                    
                }
                $statusIn=0;
                $allIns=1;
                $successApi=0;
                $failedApi=0;
                
                $uplicateNew=$existingCount;
                $no=$errorData+1;
                $newError=$errorData;
                foreach($dataSuccess as $key=>$value){
                    $sqlTrx=$this->db->query("select * from obj_customer_dev where UPPER(customerTrxId)='".strtoupper($value['customerTrxId'])."'");
                    if($sqlTrx->num_rows()>0){
                        
                        $uplicateNew=$uplicateNew+1;
                        $dataError[$no]=array(  
                                                        "TrxId"=> '<td style="background: #E07171;">'.$value['customerTrxId'].'</td>',
                                                        "LoanType"=> '<td>'.$value['customerLoanType'].'</td>',
                                                        "custName"=> '<td>'.$value['customerName'].'</td>',
                                                        "custIdCard"=> '<td>'.$value['customerIdCard'].'</td>',
                                                        "custDob"=> '<td>'.$value['customerDateBirth'].'</td>',
                                                        "custAddress"=> '<td>'.$value['customerAddress'].'</td>',
                                                        "occupation"=> '<td>'.$value['customerOccupation'].'</td>',
                                                        "city"=> '<td>'.$value['customerCity'].'</td>',
                                                        "zipCode"=> '<td>'.$value['customerZipCode'].'</td>',
                                                        "custPhone"=> '<td>'.$value['customerPhone'].'</td>',
                                                        "custEmail"=> '<td>'.$value['customerEmail'].'</td>',
                                                        "sumInsured"=> '<td>'.$value['customerSumIns'].'</td>',
                                                        "period"=> '<td>'.$value['customerInstallmentPeriod'].'</td>',
                                                        "premiumRate"=> '<td>'.$value['customerPremiumRate'].'</td>',
                                                        "premium"=> '<td>'.$value['customerPremium'].'</td>',
                                                        "policyEffective"=> '<td>'.$value['customerPolicyEffectiveDate'].'</td>'
                                                );
                        $statusIn=$statusIn;
                        $successApi=0;
                        $failedApi=0;
                        $newError=$newError+1;
                        $no++;
                    }else{
                        $newError=$newError;
                        if($this->db->insert('obj_customer_dev',$value)){
                            $idInsertObj 	= $this->db->insert_id();
                            $uplicateNew=$uplicateNew;
                            $statusIn=$statusIn+1;
                           
                            $action 		= 'INSERT';
                            $ket_his 		= 'Upload data has been success.';
                            $array_before 	= '-';
                            $array_after 	= '-';
                            
                            $insLog = array(
                                'histLogLoginId'	=> $this->session->userdata('userId'),
                                'histLogLoginUser'	=> $this->session->userdata('name'),
                                'histLogDatetime'	=> date('Y-m-d H:i:s'),
                                'histLogType'		=> $action,
                                'histLogTable'		=> 'obj_customer_dev',
                                'histLogTableId'	=> $idInsertObj,
                                'histLogShortDesc'	=> $ket_his,
                                'histLogBefore'		=> $array_before,
                                'histLogAfter'		=> $array_after,
                            );
                            $this->db->insert('history_log', $insLog);
                            $end=date('Y-m-d', strtotime('+'.$value['customerInstallmentPeriod'].' month', strtotime($value['customerPolicyEffectiveDate'])));
                            $start=$value['customerPolicyEffectiveDate'];
                            $nows       		= strtotime($start);
                            $your_date 			= strtotime($end);
                            $datediff  			= $your_date-$nows;
                            $datediff3 			= floor($datediff/(60*60*24))+1;
                            if($datediff3 == 0) { $datediff2 = 1; } else { $datediff2 = $datediff3; }
                                $dataParams=array(		
                                            "SourceID"=>"20180912KOINWORKS",	
                                            "NoRef"=>$value['customerTrxId'],	
                                            "CustName"=>$value['customerName'],	
                                            "DateOfBirth"=>date('m/d/Y',strtotime($value['customerDateBirth'])),
                                            "InsuredIdNo"=>$value['customerIdCard'],	
                                            "CustAddress"=>$value['customerAddress'],	
                                            "Occupation"=>$value['customerOccupation'],	
                                            "City"=>$value['customerCity'],	
                                            "ZipCode"=>$value['customerZipCode'],	
                                            "PhoneNo"=>$value['customerPhone'],	
                                            "HandPhone"=>$value['customerPhone'],	
                                            "Email"=>$value['customerEmail'],	
                                            "Amount"=>$value['customerPremium'],	
                                            "Package"=>"KOINWORKS_".$value['customerInstallmentPeriod']."BLN",	
                                            "InseptionDate"=>date('m/d/Y',strtotime($value['customerPolicyEffectiveDate'])),	
                                            "Duration"=>$datediff2-1,	
                                            "Beneficiary"=>array(
                                                                array(				
                                                                        "BeneficiaryName"=>"PT. Koinworks Indonesia",	
                                                                        "BeneficiaryRelationship"=>"Perusahaan",
                                                                        "BeneficiaryPercentage"=>"100"	
                                                                )
                                                            ),	
                                            "Note"=>""	
                                    );
                            $url=$this->sendData($dataParams);
                            if($url['ErrorCode']==0){
                                $status='TRUE';
                                $successApi=$successApi+1;
                                $failedApi=$failedApi;
                            }else{
                                $successApi=$successApi;
                                $failedApi=$failedApi+1;
                                $status='FALSE';
                            }
                            $InsApi=array(                     
                                "his_api_url"=>'https://simasnet.id/dataservice_test/business/receive_pa_json.asp',
                                "his_api_request"=>json_encode($dataParams),
                                "his_api_response"=>json_encode($url),
                                "his_api_status"=>$status,
                                "his_api_datetime"=>date('Y-m-d H:i:s')
                            );
                            $this->db->insert('history_api',$InsApi);  
                            $endTime=$datediff2-1;
                            $endPolicy=date('Y-m-d', strtotime('+'.$endTime.' days', strtotime($value['customerPolicyEffectiveDate'])));

                            if($url['ErrorCode']==0){
                                $dataUpdate=array(
                                                "customerPolicyNumber"=>$url['PolicyInsuranceNo'],
                                                "customerPolicyGenDate"=>date('Y-m-d H:i:s'),
                                                "customerPolicyExpiredDate"=>$endPolicy,
                                                "customerPolicyUrl"=>'https://simasnet.id/schedule_polis_test/polis_pa/print_pa.asp?trxid='.$url['NoRef'].'&nopolis='.$url['PolicyInsuranceNo'],
                                                "responseAsm"=>json_encode($url),
                                                "sendingParam"=>json_encode($dataParams)
                        
                                        );
                                    $this->db->where('customerId',$idInsertObj)->update('obj_customer_dev',$dataUpdate);
                                    $action 		= 'UPDATE';
                                    $ket_his 		= 'Upload data has been success.';
                                    $array_before 	= '-';
                                    $array_after 	= '-';
                        
                                    $insLog = array(
                                        'histLogLoginId'	=> $this->session->userdata('userId'),
                                        'histLogLoginUser'	=> $this->session->userdata('name'),
                                        'histLogDatetime'	=> date('Y-m-d H:i:s'),
                                        'histLogType'		=> $action,
                                        'histLogTable'		=> 'obj_customer_dev',
                                        'histLogTableId'	=> $idInsertObj,
                                        'histLogShortDesc'	=> $ket_his,
                                        'histLogBefore'		=> $array_before,
                                        'histLogAfter'		=> $array_after,
                                    );
                                    $this->db->insert('history_log', $insLog); 
                                    
                            }else{
                                    $dataUpdate=array(
                                                "customerPolicyGenDate"=>date('Y-m-d H:i:s'),
                                                "responseAsm"=>json_encode($url),
                                                "customerPolicyExpiredDate"=>$endPolicy,
                                                "sendingParam"=>json_encode($dataParams)
                                            );
                                    $this->db->where('customerId',$idInsertObj)->update('obj_customer_dev',$dataUpdate);
                                    $action 		= 'UPDATE';
                                    $ket_his 		= 'Upload data has been Failed.';
                                    $array_before 	= '-';
                                    $array_after 	= '-';
                        
                                    $insLog = array(
                                        'histLogLoginId'	=> $this->session->userdata('userId'),
                                        'histLogLoginUser'	=> $this->session->userdata('name'),
                                        'histLogDatetime'	=> date('Y-m-d H:i:s'),
                                        'histLogType'		=> $action,
                                        'histLogTable'		=> 'obj_customer_dev',
                                        'histLogTableId'	=> $idInsertObj,
                                        'histLogShortDesc'	=> $ket_his,
                                        'histLogBefore'		=> $array_before,
                                        'histLogAfter'		=> $array_after,
                                    );
                                    $this->db->insert('history_log', $insLog); 
                            } 
                        }else{
                            $statusIn=$statusIn;
                            $successApi=0;
                            $failedApi=0;
                        }
                    }
                       
                    $allIns++;
                }
                $nowErrorData=$newError-$uplicateNew;
                $UploadUpdate = array(
                                    'oHistUploadSuccess'	=> $statusIn,
                                    'oHistUploadFailed'		=> $nowErrorData,
                                    'oHistUploadExisting'	=> $uplicateNew
                                );
                    $this->db->where('oHistUploadCode',$kodeUpload)->update('obj_history_upload', $UploadUpdate);
                    $id_history = $this->db->insert_id();
                    $action = 'INSERT';
                    $ket_his = 'Processing upload excel data, with file name : '.$media['file_name'];
                    $array_before = '-';
                    $array_after = '-';
                    $insLog = array(
                        'histLogLoginId'	=> $this->session->userdata('userId'),
                        'histLogLoginUser'	=> $this->session->userdata('name'),
                        'histLogDatetime'	=> date('Y-m-d H:i:s'),
                        'histLogType'		=> $action,
                        'histLogTable'		=> 'temp_data_process',
                        'histLogTableId'	=> $id_history,
                        'histLogShortDesc'	=> $ket_his,
                        'histLogBefore'		=> $array_before,
                        'histLogAfter'		=> $array_after,
                    );
                    $this->db->insert('history_log', $insLog);
            }else{
               $statusIn=0;
                $successApi=0;
                $failedApi=0;
                $allIns=0;
                $newError=$errorData;
                $uplicateNew=$existingCount;
            }
            
            $sumData=$rowCek;
            $failedIn=$allIns-$statusIn;
            $Now=$newError-$uplicateNew;
            if($Now<0){
                $errorNow=0;
            }else{
                $errorNow=$Now;
            }

           
            if(($errorData==0) AND ($successApi==$sumData)){
                $itemArray['status']='true';
                $itemArray['type']=0;
                $itemArray['msg']='Upload Success';
            }else if(($failedApi>0) AND ($uplicateNew==0)){
                $itemArray['status']='true';
                $itemArray['type']=1;
                $itemArray['msg']='Upload Success  '.$statusIn .' Failed Push API To Simas '.$failedApi;
            }else if(($failedApi>0) AND ($errorData >0)){
                $itemArray['status']='true';
                $itemArray['type']=1;
                $itemArray['msg']='Upload Success  '.$statusIn .' Error Data ' .$errorData. ' Failed Push API To Simas '.$failedApi;
            }else if(($errorData==0) AND ($statusIn >0) AND ($uplicateNew==0)){
                $itemArray['status']='false';
                $itemArray['type']=1;
                $itemArray['msg']='Upload Success '.$statusIn;
            }else if(($errorData>0) AND ($successData >0) AND ($failedIn>0) AND ($uplicateNew==0)){
                $itemArray['status']='false';
                $itemArray['type']=1;
                $itemArray['msg']='Upload Success '.$successData.' Error Data '.$errorData;
            }else if(($errorData>0) AND ($successData >0) AND ($uplicateNew==0)){
                $itemArray['status']='false';
                $itemArray['type']=1;
                $itemArray['msg']='Upload Success '.$successData.' Error Data '.$errorData;
            }else if(($successData>0) AND ($errorNow >0) AND ($uplicateNew>0)){
                $itemArray['status']='false';
                $itemArray['type']=1;
                $itemArray['msg']='Upload Success '.$statusIn .' Error Data '. $errorNow .' Duplicate '.$uplicateNew;
            }else if(($successData>0) AND ($errorNow ==0) AND ($uplicateNew>0)){
                $itemArray['status']='false';
                $itemArray['type']=1;
                $itemArray['msg']='Upload Success '.$statusIn .' Duplicate '.$uplicateNew;
            }else if(($errorNow==0) AND ($uplicateNew>0)){
                $itemArray['status']='false';
                $itemArray['type']=1;
                $itemArray['msg']='Duplicate '.$uplicateNew;
            }else if(($errorNow > 0) AND ($uplicateNew>0)){
                $itemArray['status']='false';
                $itemArray['type']=1;
                $itemArray['msg']='Error Data '. $errorNow .' Duplicate '.$uplicateNew;
            }else if(($successData==0) AND ($errorData==0)){
                $itemArray['status']='false';
                $itemArray['type']=1;
                $itemArray['msg']='Upload Failed';
            }else if(($successData==0) AND ($errorData >0) AND ($uplicateNew==0)){
                $itemArray['status']='false';
                $itemArray['type']=1;
                $itemArray['msg']='Error Data '.$errorData;
            }else if(($successData==0) AND ($errorNow >0) AND ($uplicateNew>0)){
                $itemArray['status']='false';
                $itemArray['type']=1;
                $itemArray['msg']='Error Data '.$errorNow .' Duplicate '.$uplicateNew;
            }else{
                $itemArray['status']='false';
                $itemArray['type']=1;
                $itemArray['msg']='Error Data'.$failedIn;
            }
            $itemArray['rowError']=$dataError;
            
        }
        $this->sendOutput($itemArray);	
    }
    public function validation($value){
        // print_r($value);
        $errorData=0;
        $successData=0;
        $duplicate=0;
        if($value['TrxId']=='' OR empty($value['TrxId'])){
            $error['TrxId']='<td style="background: #E07171;">'.$value['TrxId'].'</td>';
            $errorData=$errorData+1;
            $successData=$successData;
            $duplicate=$duplicate;
        }else{
           $sqlTrx=$this->db->query("select * from obj_customer_dev where UPPER(customerTrxId)='".strtoupper($value['TrxId'])."'");
           if($sqlTrx->num_rows()>0){
                $error['TrxId']='<td style="background: #E07171;">'.$value['TrxId'].'</td>';
                $errorData=$errorData+1;
                $successData=$successData-1;
                $duplicate=1;
           }else{
                $errorData=$errorData;
                $successData=$successData;
                $duplicate=$duplicate;
                $error['TrxId']='<td>'.$value['TrxId'].'</td>';
           }
        }
        if($value['LoanType']=='' OR empty($value['LoanType'])){
            $error['LoanType']='<td style="background: #E07171;">'.$value['LoanType'].'</td>';
            $errorData=$errorData+1;
            $successData=$successData;
            $duplicate=$duplicate;
        }else{
            $error['LoanType']='<td>'.$value['LoanType'].'</td>';
            $errorData=$errorData;
            $successData=$successData;
            $duplicate=$duplicate;
        }
        if($value['custName']=='' OR empty($value['custName'])){
            $error['custName']='<td style="background: #E07171;">'.$value['custName'].'</td>';
            $errorData=$errorData+1;
            $successData=$successData;
            $duplicate=$duplicate;
        }else{
            $error['custName']='<td>'.$value['custName'].'</td>';
            $errorData=$errorData;
            $successData=$successData;
            $duplicate=$duplicate;
        }
        if($value['custIdCard']=='' OR empty($value['custIdCard'])){
            $error['custIdCard']='<td style="background: #E07171;">'.$value['custIdCard'].'</td>';;
            $errorData=$errorData+1;
            $successData=$successData;
            $duplicate=$duplicate;
        }else{
            if(is_numeric($value['custIdCard'])){
                $error['custIdCard']='<td>'.$value['custIdCard'].'</td>';
                $errorData=$errorData;
                $successData=$successData;
                $duplicate=$duplicate;
            }else{
                $error['custIdCard']='<td style="background: #E07171;">'.$value['custIdCard'].'</td>';;
                $errorData=$errorData+1;
                $successData=$successData;
                $duplicate=$duplicate;
            }
            
        }
        if($value['custDob']=='' OR empty($value['custDob'])){
            $error['custDob']='<td style="background: #E07171;">'.$value['custDob'].'</td>';
            $errorData=$errorData+1;
            $successData=$successData;
            $duplicate=$duplicate;
        }else{
            $error['custDob']='<td>'.date('Y-m-d',($value['custDob']-25569)*86400).'</td>';
            $errorData=$errorData;
            $successData=$successData;
            $duplicate=$duplicate;
        }
        if($value['custAddress']=='' OR empty($value['custAddress'])){
            $error['custAddress']='<td style="background: #E07171;">'.$value['custAddress'].'</td>';
            $errorData=$errorData+1;
            $successData=$successData;
            $duplicate=$duplicate;
        }else{
            $error['custAddress']='<td>'.$value['custAddress'].'</td>';
            $errorData=$errorData;
            $successData=$successData;
            $duplicate=$duplicate;
        }
        if($value['occupation']=='' OR empty($value['occupation'])){
            $error['occupation']='<td style="background: #E07171;">'.$value['occupation'].'</td>';
            $errorData=$errorData+1;
            $successData=$successData;
            $duplicate=$duplicate;
        }else{
            $error['occupation']='<td>'.$value['occupation'].'</td>';
            $errorData=$errorData;
            $successData=$successData;
            $duplicate=$duplicate;
        }
        if($value['city']=='' OR empty($value['city'])){
            $error['city']='<td style="background: #E07171;">'.$value['city'].'</td>';
            $errorData=$errorData+1;
            $successData=$successData;
            $duplicate=$duplicate;
        }else{
            $error['city']='<td>'.$value['city'].'</td>';
            $errorData=$errorData;
            $successData=$successData;
            $duplicate=$duplicate;
        }
        if($value['zipCode']=='' OR empty($value['zipCode'])){
            $error['zipCode']='<td style="background: #E07171;">'.$value['zipCode'].'</td>';
            $errorData=$errorData+1;
            $successData=$successData;
            $duplicate=$duplicate;
        }else{
            $error['zipCode']='<td>'.$value['zipCode'].'</td>';
            $errorData=$errorData;
            $successData=$successData;
            $duplicate=$duplicate;
        }
        if($value['custPhone']=='' OR empty($value['custPhone'])){
            $error['custPhone']='<td style="background: #E07171;">'.$value['custPhone'].'</td>';
            $errorData=$errorData+1;
            $successData=$successData;
            $duplicate=$duplicate;
        }else{
            $phone=str_replace(array('-'),array(''),$value['custPhone']);
            $count=strlen($phone);
            if(preg_match("/^[0-9]*$/",$phone)){
                if($count >13){
                    $error['custPhone']='<td style="background: #E07171;">'.$phone.'  ==='.$count.'</td>';
                    $errorData=$errorData+1;
                    $successData=$successData;
                    $duplicate=$duplicate;
                }else{
                    $error['custPhone']='<td>'.$value['custPhone'].'</td>';
                    $errorData=$errorData;
                    $successData=$successData;
                    $duplicate=$duplicate;
                }
               
            }else{
                $error['custPhone']='<td style="background: #E07171;">'.$phone.'  ==='.$count.'</td>';
                $errorData=$errorData+1;
                $successData=$successData;
                $duplicate=$duplicate;
            }
           
        }
        if($value['custEmail']=='' OR empty($value['custEmail'])){
            $error['custEmail']='<td style="background: #E07171;">'.$value['custEmail'].'</td>';
            $errorData=$errorData+1;
            $successData=$successData;
            $duplicate=$duplicate;
        }else{
            if(!filter_var($value['custEmail'], FILTER_VALIDATE_EMAIL)){
                $error['custEmail']='<td style="background: #E07171;">'.$value['custEmail'].'</td>';
                $errorData=$errorData+1;
                $successData=$successData;
                $duplicate=$duplicate;
            }else{
                $error['custEmail']='<td>'.$value['custEmail'].'</td>';
                $errorData=$errorData;
                $successData=$successData;
                $duplicate=$duplicate;
            }
            
        }
        if($value['sumInsured']=='' OR empty($value['sumInsured'])){
            $error['sumInsured']='<td style="background: #E07171;">'.$value['sumInsured'].'</td>';
            $errorData=$errorData+1;
            $successData=$successData;
            $duplicate=$duplicate;
        }else{
            if(is_numeric($value['sumInsured'])){
                $error['sumInsured']='<td>'.$value['sumInsured'].'</td>';
                $errorData=$errorData;
                $successData=$successData;
                $duplicate=$duplicate;
            }else{
                $error['sumInsured']='<td style="background: #E07171;">'.$value['sumInsured'].'</td>';
                $errorData=$errorData+1;
                $successData=$successData;
                $duplicate=$duplicate;
            }
            
        }
        if($value['period']=='' OR empty($value['period'])){
            $error['period']='<td style="background: #E07171;">'.$value['period'].'</td>';
            $errorData=$errorData+1;
            $successData=$successData;
            $duplicate=$duplicate;
        }else{
            if(is_numeric($value['period'])){
                $error['period']='<td>'.$value['period'].'</td>';
                $errorData=$errorData;
                $successData=$successData;
                $duplicate=$duplicate;
            }else{
                $error['period']='<td style="background: #E07171;">'.$value['period'].'</td>';
                $errorData=$errorData+1;
                $successData=$successData;
                $duplicate=$duplicate;
            }
            
        }
        if($value['premiumRate']=='' OR empty($value['premiumRate'])){
            $error['premiumRate']='<td style="background: #E07171;">'.$value['premiumRate'].'</td>';
            $errorData=$errorData+1;
            $successData=$successData;
            $duplicate=$duplicate;
        }else{
            if(is_numeric($value['premiumRate'])){
                $error['premiumRate']='<td>'.$value['premiumRate'].'</td>';
                $errorData=$errorData;
                $successData=$successData;
                $duplicate=$duplicate;
            }else{
                $error['premiumRate']='<td style="background: #E07171;">'.$value['premiumRate'].'</td>';
                $errorData=$errorData+1;
                $successData=$successData;
                $duplicate=$duplicate;
            }
            
        }
        if($value['premium']=='' OR empty($value['premium'])){
            $error['premium']='<td style="background: #E07171;">'.$value['premium'].'</td>';
            $errorData=$errorData+1;
            $successData=$successData;
            $duplicate=$duplicate;
        }else{
            if(is_numeric($value['premium'])){
                $error['premium']='<td>'.$value['premium'].'</td>';
                $errorData=$errorData;
                $successData=$successData;
                $duplicate=$duplicate;
            }else{
                $error['premium']='<td style="background: #E07171;">'.$value['premium'].'</td>';
                $errorData=$errorData+1;
                $successData=$successData;
                $duplicate=$duplicate;
            }
            
        }
        if($value['policyEffective']=='' OR empty($value['policyEffective'])){
            $error['policyEffective']='<td style="background: #E07171;">'.$value['policyEffective'].'</td>';
            $errorData=$errorData+1;
            $successData=$successData;
            $duplicate=$duplicate;
        }else{
            if(date('Y-m-d')<= date('Y-m-d',($value['policyEffective']-25569)*86400)){
                $error['policyEffective']='<td>'.date('Y-m-d',($value['policyEffective']-25569)*86400).'</td>';
                $errorData=$errorData;
                $successData=$successData;
                $duplicate=$duplicate;
            }else{
                $error['policyEffective']='<td style="background: #E07171;">'.date('Y-m-d',($value['policyEffective']-25569)*86400).'</td>';
                $errorData=$errorData+1;
                $successData=$successData;
                $duplicate=$duplicate;
            }
            
        }
        
        if($errorData>0){
            $data['dataError']=1;
            $data['dataSuccess']=0;
            $data['errorRow']=$error;
            $data['successRow']=array();
        }else{
            $data['successRow']=array(
                            "customerTrxId"=>$value['TrxId'] ,
                            "customerLoanType"=>$value['LoanType'] ,
                            "customerName"=>$value['custName'] ,
                            "customerIdCard"=>$value['custIdCard'] ,
                            "customerDateBirth"=>date('Y-m-d',($value['custDob']-25569)*86400),
                            "customerAddress"=>$value['custAddress'],
                            "customerOccupation"=>$value['occupation'],
                            "customerCity"=>$value['city'],
                            "customerZipCode"=>$value['zipCode'],
                            "customerPhone"=>preg_replace("/[^0-9]/", '',$value['custPhone']),
                            "customerEmail"=>$value['custEmail'],
                            "customerSumIns"=>$value['sumInsured'],
                            "customerInstallmentPeriod"=>$value['period'],
                            "customerPremiumRate"=>$value['premiumRate'],
                            "customerPremium"=>$value['premium'],
                            "customerPolicyEffectiveDate"=>date('Y-m-d',($value['policyEffective']-25569)*86400),
                        );
            $data['errorRow']=array();
            $data['dataError']=0;
            $data['dataSuccess']=1;


        }
        $data['duplicate']=$duplicate;
         
        // print_r($data);
        return $data;
    }
	private function getUserIP() {
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
    public function sendData($dataApi) {
		// die(print_r($dataApi));
		
		$data = str_replace('\/','/',json_encode($dataApi));
		// die(print_r($data));
		$response = json_decode($this->curlPost("https://simasnet.id/dataservice_test/business/receive_pa_json.asp", $data), TRUE);

		return $response;
    }

    private function curlPost($url,$params) {
		$curl = curl_init();

		curl_setopt_array($curl, array(
		  	CURLOPT_URL =>$url,
		  	CURLOPT_RETURNTRANSFER => true,
		  	CURLOPT_ENCODING => "",
		  	CURLOPT_MAXREDIRS => 10,
		  	CURLOPT_TIMEOUT => 30,
		  	CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		  	CURLOPT_CUSTOMREQUEST => "POST",
		  	CURLOPT_POSTFIELDS =>$params,
		  	CURLOPT_HTTPHEADER => array(
				"Content-Type: application/json",
				"Postman-Token: 52d00945-dcef-4f49-89cf-898a8c977fce",
				"cache-control: no-cache"
		  	),
		));

		$response = curl_exec($curl);
		$err = curl_error($curl);

		curl_close($curl);
		return $response;
    }
    public function export_excel(){
        include APPPATH.'third_party/PHPExcel/PHPExcel.php';
        $filenameExport = 'FILE_IMPORT_EXCEL'.date('YmdHis');
        $excel = new PHPExcel();
        $style_col = array(
                          'font' => array('bold' => true), 
                          'alignment' => array(
                            'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER, 
                            'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER 
                          ),
                          'borders' => array(
                            'top' => array('style'  => PHPExcel_Style_Border::BORDER_THIN), 
                            'right' => array('style'  => PHPExcel_Style_Border::BORDER_THIN),  
                            'bottom' => array('style'  => PHPExcel_Style_Border::BORDER_THIN), 
                            'left' => array('style'  => PHPExcel_Style_Border::BORDER_THIN) 
                          )
                      );
                    
      $style_row = array(
                      'alignment' => array(
                        'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER 
                      ),
                      'borders' => array(
                        'top' => array('style'  => PHPExcel_Style_Border::BORDER_THIN), 
                        'right' => array('style'  => PHPExcel_Style_Border::BORDER_THIN),  
                        'bottom' => array('style'  => PHPExcel_Style_Border::BORDER_THIN), 
                        'left' => array('style'  => PHPExcel_Style_Border::BORDER_THIN) 
                      )
                  );
  
        $excel->setActiveSheetIndex(0)->setCellValue('A1', "TransactionID");
        $excel->setActiveSheetIndex(0)->setCellValue('B1', "Type of Loan");
        $excel->setActiveSheetIndex(0)->setCellValue('C1', "Customer Name");
        $excel->setActiveSheetIndex(0)->setCellValue('D1', "Customer ID Card");
        $excel->setActiveSheetIndex(0)->setCellValue('E1', "Date of Birth");
        $excel->setActiveSheetIndex(0)->setCellValue('F1', "Customer Address");
        $excel->setActiveSheetIndex(0)->setCellValue('G1', "Occupation");
        $excel->setActiveSheetIndex(0)->setCellValue('H1', "City");
        $excel->setActiveSheetIndex(0)->setCellValue('I1', "Zipcode");
        $excel->setActiveSheetIndex(0)->setCellValue('J1', "Phone Number");
        $excel->setActiveSheetIndex(0)->setCellValue('K1', "Email Address");
        $excel->setActiveSheetIndex(0)->setCellValue('L1', "Sum Insured ");
        $excel->setActiveSheetIndex(0)->setCellValue('M1', "Installment Period");
        $excel->setActiveSheetIndex(0)->setCellValue('N1', "Insurance Premium Rate");
        $excel->setActiveSheetIndex(0)->setCellValue('O1', "Premium");
		$excel->setActiveSheetIndex(0)->setCellValue('P1', "BARU 1");
		$excel->setActiveSheetIndex(0)->setCellValue('Q1', "BARU 2");
		$excel->setActiveSheetIndex(0)->setCellValue('R1', "DebitorEmail");
		$excel->setActiveSheetIndex(0)->setCellValue('S1', "Pinjaman"); 
		$excel->setActiveSheetIndex(0)->setCellValue('T1', "Package");
		$excel->setActiveSheetIndex(0)->setCellValue('U1', "InseptionDate yyyy-mm-dd");
		$excel->setActiveSheetIndex(0)->setCellValue('V1', "Lama Pinjaman (dalam Bulan)");
        $excel->getActiveSheet()->getStyle('A1')->applyFromArray($style_col);
        $excel->getActiveSheet()->getStyle('B1')->applyFromArray($style_col);
        $excel->getActiveSheet()->getStyle('C1')->applyFromArray($style_col);
        $excel->getActiveSheet()->getStyle('D1')->applyFromArray($style_col);
        $excel->getActiveSheet()->getStyle('E1')->applyFromArray($style_col);
        $excel->getActiveSheet()->getStyle('F1')->applyFromArray($style_col);
        $excel->getActiveSheet()->getStyle('G1')->applyFromArray($style_col);
        $excel->getActiveSheet()->getStyle('H1')->applyFromArray($style_col);
        $excel->getActiveSheet()->getStyle('I1')->applyFromArray($style_col);
        $excel->getActiveSheet()->getStyle('J1')->applyFromArray($style_col);
        $excel->getActiveSheet()->getStyle('K1')->applyFromArray($style_col);
        $excel->getActiveSheet()->getStyle('L1')->applyFromArray($style_col);
        $excel->getActiveSheet()->getStyle('M1')->applyFromArray($style_col);
        $excel->getActiveSheet()->getStyle('N1')->applyFromArray($style_col);
        $excel->getActiveSheet()->getStyle('O1')->applyFromArray($style_col);
		$excel->getActiveSheet()->getStyle('P1')->applyFromArray($style_col);
		$excel->getActiveSheet()->getStyle('Q1')->applyFromArray($style_col);
		$excel->getActiveSheet()->getStyle('R1')->applyFromArray($style_col);
		$excel->getActiveSheet()->getStyle('S1')->applyFromArray($style_col);
		$excel->getActiveSheet()->getStyle('T1')->applyFromArray($style_col);
		$excel->getActiveSheet()->getStyle('U1')->applyFromArray($style_col);
		$excel->getActiveSheet()->getStyle('V1')->applyFromArray($style_col);

       
        $excel->getActiveSheet()->getDefaultRowDimension()->setRowHeight(-1);							    
        $excel->getActiveSheet()->getPageSetup()->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_LANDSCAPE);							    
        $excel->getActiveSheet(0)->setTitle("Sheet1");
        $excel->setActiveSheetIndex(0);		
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="Import Data '.$filenameExport.'.xlsx"'); // Set nama file excel nya
        header('Cache-Control: max-age=0');    
        $write = PHPExcel_IOFactory::createWriter($excel, 'Excel2007');
        $write->save('php://output');
    }
}