<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*
March 18, 2019 10:55:52 PM dyah line 237, 277 $ket_his
*/
class Upload2 extends CI_Controller {

	function __construct() {
        parent::__construct();
        // $this->load->model('middleware','middle');
        $this->load->model('polis_model');
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

    public function download_polis($trxid)
    {
        // die(print_r($trxid.''.$nopolis));
        
        $dataPolis = $this->db->query("select * from obj_customer_cf where cust_cf_no_ref = '".$trxid."' ");
        $hasilPolis = $dataPolis->row_array();
         $this->polis_model->downloadFromSimas(trim($hasilPolis['cust_cf_policy_url']));
        //  die(print_r($data));
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

        $dateKode = date('Ymd').'KOINWORKS';
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
                                "cust_cf_source_id"=> '4', 
                                "cust_cf_source_label"=>'20200708KOINWORKS',
                                "cust_cf_upload_code"=>$kodeUpload,
                                "cust_cf_no_ref"=>trim($rowData[0][0]),
                                "cust_cf_credit_name"=>trim($rowData[0][1]),
                                "cust_cf_credit_dob"=>trim($rowData[0][2]),
                                "cust_cf_credit_ktp"=>trim($rowData[0][3]),
                                "cust_cf_credit_address"=>trim($rowData[0][4]),
                                "cust_cf_credit_city"=>trim($rowData[0][5]),
                                "cust_cf_credit_postal_code"=>trim($rowData[0][6]),
                                "cust_cf_credit_hp"=>trim($rowData[0][7]),
                                "cust_cf_credit_email"=>trim($rowData[0][8]),
                                "cust_cf_debitor_id"=>trim($rowData[0][9]),
                                "cust_cf_debitor_name"=>trim($rowData[0][10]),
                                "cust_cf_debitor_dob"=>trim($rowData[0][11]),
                                "cust_cf_debitor_ktp"=>trim($rowData[0][12]),
                                "cust_cf_debitor_address"=>trim($rowData[0][13]),
                                "cust_cf_debitor_city"=>trim($rowData[0][14]),
                                "cust_cf_debitor_postal_code"=>trim($rowData[0][15]),
                                "cust_cf_debitor_hp"=>trim($rowData[0][16]),
                                "cust_cf_debitor_email"=>trim($rowData[0][17]),
                                "cust_cf_loan"=>trim($rowData[0][18]),
                                "cust_cf_inseption_date"=>trim($rowData[0][19]),
                                "cust_cf_long_loan"=>trim($rowData[0][20]),
                                "cust_cf_policy_generate_date"=>date('Y-m-d H:i:s'),
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
                    $sqlTrx=$this->db->query("select * from obj_customer_cf where UPPER(cust_cf_no_ref)='".strtoupper($value['cust_cf_no_ref'])."'");
                    if($sqlTrx->num_rows()>0){
                        
                        $uplicateNew=$uplicateNew+1;
                        $dataError[$no]=array(  
                                                        "cust_cf_source_label"=> '<td style="background: #E07171;">'.$value['cust_cf_source_label'].'</td>',
                                                        "cust_cf_no_ref"=> '<td style="background: #E07171;">'.$value['cust_cf_no_ref'].'</td>',
                                                        "cust_cf_credit_name"=> '<td>'.$value['cust_cf_credit_name'].'</td>',
                                                        "cust_cf_credit_dob"=> '<td>'.$value['cust_cf_credit_dob'].'</td>',
                                                        "cust_cf_credit_ktp"=> '<td>'.$value['cust_cf_credit_ktp'].'</td>',
                                                        "cust_cf_credit_address"=> '<td>'.$value['cust_cf_credit_address'].'</td>',
                                                        "cust_cf_credit_city"=> '<td>'.$value['cust_cf_credit_city'].'</td>',
                                                        "cust_cf_credit_postal_code"=> '<td>'.$value['cust_cf_credit_postal_code'].'</td>',
                                                        "cust_cf_credit_hp"=> '<td>'.$value['cust_cf_credit_hp'].'</td>',
                                                        "cust_cf_credit_email"=> '<td>'.$value['cust_cf_credit_email'].'</td>',
                                                        "cust_cf_debitor_id"=> '<td>'.$value['cust_cf_debitor_id'].'</td>',
                                                        "cust_cf_debitor_name"=> '<td>'.$value['cust_cf_debitor_name'].'</td>',
                                                        "cust_cf_debitor_dob"=> '<td>'.$value['cust_cf_debitor_dob'].'</td>',
                                                        "cust_cf_debitor_ktp"=> '<td>'.$value['cust_cf_debitor_ktp'].'</td>',
                                                        "cust_cf_debitor_address"=> '<td>'.$value['cust_cf_debitor_address'].'</td>',
                                                        "cust_cf_debitor_city"=> '<td>'.$value['cust_cf_debitor_city'].'</td>',
                                                        "cust_cf_debitor_postal_code"=> '<td>'.$value['cust_cf_debitor_postal_code'].'</td>',
                                                        "cust_cf_debitor_hp"=> '<td>'.$value['cust_cf_debitor_hp'].'</td>',
                                                        "cust_cf_debitor_email"=> '<td>'.$value['cust_cf_debitor_email'].'</td>',
                                                        "cust_cf_loan"=> '<td>'.$value['cust_cf_loan'].'</td>',
                                                        "cust_cf_package"=> '<td>'.$value['cust_cf_package'].'</td>',
                                                        "cust_cf_inseption_date"=> '<td>'.$value['cust_cf_inseption_date'].'</td>',
                                                        "cust_cf_long_loan"=> '<td>'.$value['cust_cf_long_loan'].'</td>',
                                                        "cust_cf_policy_generate_date"=> '<td>'.$value['cust_cf_policy_generate_date'].'</td>'
                                                );
                        $statusIn=$statusIn;
                        $successApi=0;
                        $failedApi=0;
                        $newError=$newError+1;
                        $no++;
                    }else{
                        $newError=$newError;
                        if($this->db->insert('obj_customer_cf',$value)){
                            $idInsertObj 	= $this->db->insert_id();
                            $uplicateNew=$uplicateNew;
                            $statusIn=$statusIn+1;
                            
                            $cicilNom = round($value['cust_cf_loan']/$value['cust_cf_long_loan']);
                            $cicilDate = date("Y-m-d", strtotime( $value['cust_cf_inseption_date'] . "+1 month" ));
                            for($cicilan=1; $cicilan <= $value['cust_cf_long_loan']; $cicilan++) {
	                            
	                            if($value['cust_cf_long_loan'] == $cicilan) {
		                            $cicilDate = date("Y-m-d", strtotime( $cicilDate . "-2 day" ));
                                    $sqlCekNom = $this->db->query("SELECT sum(cust_cf_inst_nominal) as total FROM obj_cust_cf_installment WHERE cust_cf_inst_id_hd = '".$idInsertObj."' ");
                                    $rowNom = $sqlCekNom->row_array();
                                    $cicilNom = $value['cust_cf_loan'] - $rowNom['total'];
	                            }

	                            $InstallM = array(
	                                'cust_cf_inst_id_hd'	    => $idInsertObj,
	                                'cust_cf_inst_no_ref'	    => $value['cust_cf_no_ref'],
	                                'cust_cf_inst_exp_date'	    => $cicilDate,
	                                'cust_cf_inst_nominal'		=> $cicilNom,
	                                'cust_cf_inst_insert_date'	=> date('Y-m-d H:i:s')
	                            );
	                            $this->db->insert('obj_cust_cf_installment', $InstallM);
	                            
                                $bulanCheck = date('n', strtotime($cicilDate));
                                $tglCheck = date('j', strtotime($cicilDate));

                                if( ($bulanCheck == 1) && ($tglCheck > 28)) {
                                    $cicilDate = date("Y", strtotime($cicilDate)).'-02-28';
                                } else {
                                   $cicilDate = date("Y-m-d", strtotime( $cicilDate . "+1 month" ));
                                }
                            }
                            
                            #konversi bulan ke hari
                            $longLoan = $value['cust_cf_long_loan'];
							$dateAsli = $value['cust_cf_inseption_date'];
							
							$startdate = strtotime($dateAsli);
							$enddate = strtotime( $dateAsli . "+".$longLoan." month" );
							$datediff = $enddate - $startdate;
							
							$hasilKonversi = round($datediff / (60 * 60 * 24));
		
                            $tempUpdate = array("cust_cf_long_loan" => $hasilKonversi);
							$this->db->update('obj_customer_cf', $tempUpdate, array('cust_cf_id'=>$idInsertObj));
                           
                            $action 		= 'INSERT';
                            $ket_his 		= 'Upload data has been success.';
                            $array_before 	= '-';
                            $array_after 	= '-';
                            
                            $insLog = array(
                                'histLogLoginId'	=> $this->session->userdata('userId'),
                                'histLogLoginUser'	=> $this->session->userdata('name'),
                                'histLogDatetime'	=> date('Y-m-d H:i:s'),
                                'histLogType'		=> $action,
                                'histLogTable'		=> 'obj_customer_cf',
                                'histLogTableId'	=> $idInsertObj,
                                'histLogShortDesc'	=> $ket_his,
                                'histLogBefore'		=> $array_before,
                                'histLogAfter'		=> $array_after,
                            );
                            $this->db->insert('history_log', $insLog);
                            
                            
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
        $validInception='';

        if($value['cust_cf_source_label']=='' OR empty($value['cust_cf_source_label'])){
            $error['cust_cf_source_label']='<td style="background: #E07171;">'.$value['cust_cf_source_label'].'</td>';
            $errorData=$errorData+1;
            $successData=$successData;
            $duplicate=$duplicate;
        }else{
            $error['cust_cf_source_label']='<td>'.$value['cust_cf_source_label'].'</td>';
            $errorData=$errorData;
            $successData=$successData;
            $duplicate=$duplicate;
        }
        if($value['cust_cf_no_ref']=='' OR empty($value['cust_cf_no_ref'])){
            $error['cust_cf_no_ref']='<td style="background: #E07171;">'.$value['cust_cf_no_ref'].'</td>';
            $errorData=$errorData+1;
            $successData=$successData;
            $duplicate=$duplicate;
        }else{
            $dataTrx=str_replace(array('-'),array(''),$value['cust_cf_no_ref']);
            $count=strlen($dataTrx);
            if($count > 200){
                // die("gagal1");
                    $error['cust_cf_no_ref']='<td style="background: #E07171;">'.$value['cust_cf_no_ref'].'=>'.$count.'</td>';
                    $errorData=$errorData+1;
                    $successData=$successData-1;
                    $duplicate=$duplicate;
            }else{
                $sqlTrx=$this->db->query("select * from obj_customer_cf where cust_cf_no_ref ='".strtoupper($value['cust_cf_no_ref'])."'");
                if($sqlTrx->num_rows()>0){
                    // die("gagal2");
                    $error['cust_cf_no_ref']='<td style="background: #E07171;">'.$value['cust_cf_no_ref'].'</td>';
                    $errorData=$errorData+1;
                    $successData=$successData-1;
                    $duplicate=1;
                }else{
                    // die("sukses1");
                    $error['cust_cf_no_ref']='<td>'.$value['cust_cf_no_ref'].'</td>';
                    $errorData=$errorData;
                    $successData=$successData;
                    $duplicate=$duplicate;
                }
                
            }   
           
        }
        
        if($value['cust_cf_credit_name']=='' OR empty($value['cust_cf_credit_name'])){
            $error['cust_cf_credit_name']='<td style="background: #E07171;">'.$value['cust_cf_credit_name'].'</td>';
            $errorData=$errorData+1;
            $successData=$successData;
            $duplicate=$duplicate;
        }else{
            $dataName=str_replace(array('-'),array(''),$value['cust_cf_credit_name']);
            $count=strlen($dataName);
            if($count > 200){
                $error['cust_cf_credit_name']='<td style="background: #E07171;">'.$value['cust_cf_credit_name'].'=>'.$count.'</td>';
                $errorData=$errorData+1;
                $successData=$successData;
                $duplicate=$duplicate;
            }else{
                $error['cust_cf_credit_name']='<td>'.$value['cust_cf_credit_name'].'</td>';
                $errorData=$errorData;
                $successData=$successData;
                $duplicate=$duplicate;
            }
        }
        if($value['cust_cf_credit_dob']=='' OR empty($value['cust_cf_credit_dob'])){
            $error['cust_cf_credit_dob']='<td style="background: #E07171;">'.$value['cust_cf_credit_dob'].'</td>';
            $errorData=$errorData+1;
            $successData=$successData;
            $duplicate=$duplicate;
        }else{
            $birthDate2 = new DateTime(date('Y-m-d',($value['cust_cf_credit_dob']-25569)*86400));
            $today2 = new DateTime("today");
            
                $y = $today2->diff($birthDate2)->y;
                $m = $today2->diff($birthDate2)->m;
                $d = $today2->diff($birthDate2)->d;
                // die(print_r($y."Tahun".$m."Bulan".$d."Hari"));
           if($y < 17){
                // die("GAGAL1");
                $error['cust_cf_credit_dob']='<td style="background: #E07171;">'.date('Y-m-d',($value['cust_cf_credit_dob']-25569)*86400).' =>'.$y.'TAHUN</td>';
                $errorData=$errorData+1;
                $successData=$successData;
                $duplicate=$duplicate;
            
           }else if($y > 100){
                //  die("GAGAL2");
                 $error['cust_cf_credit_dob']='<td style="background: #E07171;">'.date('Y-m-d',($value['cust_cf_credit_dob']-25569)*86400).' =>'.$y.'TAHUN</td>';
                 $errorData=$errorData+1;
                 $successData=$successData;
                 $duplicate=$duplicate;
           }else{
            //    die("SUKSES");
                $error['cust_cf_credit_dob']='<td>'.date('Y-m-d',($value['cust_cf_credit_dob']-25569)*86400).'</td>';
                $errorData=$errorData;
                $successData=$successData;
                $duplicate=$duplicate;
           }

        }

        if($value['cust_cf_credit_ktp']=='' OR empty($value['cust_cf_credit_ktp'])){
            $error['cust_cf_credit_ktp']='<td style="background: #E07171;">'.$value['cust_cf_credit_ktp'].'</td>';
            $errorData=$errorData+1;
            $successData=$successData;
            $duplicate=$duplicate;
        }else{
            $data2=str_replace(array('-'),array(''),$value['cust_cf_credit_ktp']);
            $count=strlen($data2);
            if(preg_match("/^[0-9]*$/",$data2)){
                if($count == 16){
                    $error['cust_cf_credit_ktp']='<td>'.$value['cust_cf_credit_ktp'].'</td>';
                    $errorData=$errorData;
                    $successData=$successData;
                    $duplicate=$duplicate;
                }else{
                    $error['cust_cf_credit_ktp']='<td style="background: #E07171;">'.$data2.'  =>'.$count.'</td>';
                    $errorData=$errorData+1;
                    $successData=$successData;
                    $duplicate=$duplicate;
                }
               
            }else{
                $error['cust_cf_credit_ktp']='<td style="background: #E07171;">'.$data2.'  =>'.$count.'</td>';
                $errorData=$errorData+1;
                $successData=$successData;
                $duplicate=$duplicate;
            }
           
        }
        if($value['cust_cf_credit_address']=='' OR empty($value['cust_cf_credit_address'])){
            $error['cust_cf_credit_address']='<td style="background: #E07171;">'.$value['cust_cf_credit_address'].'</td>';
            $errorData=$errorData+1;
            $successData=$successData;
            $duplicate=$duplicate;
        }else{
            $dataAddres=str_replace(array('-'),array(''),$value['cust_cf_credit_address']);
            $count=strlen($dataAddres);
            if($count > 500){
                $error['cust_cf_credit_address']='<td style="background: #E07171;">'.$value['cust_cf_credit_address'].'=>'.$count.'</td>';
                $errorData=$errorData+1;
                $successData=$successData;
                $duplicate=$duplicate;
            }else{
                $error['cust_cf_credit_address']='<td>'.$value['cust_cf_credit_address'].'</td>';
                $errorData=$errorData;
                $successData=$successData;
                $duplicate=$duplicate;
            }
        }
        if($value['cust_cf_credit_city']=='' OR empty($value['cust_cf_credit_city'])){
            $error['cust_cf_credit_city']='<td style="background: #E07171;">'.$value['cust_cf_credit_city'].'</td>';
            $errorData=$errorData+1;
            $successData=$successData;
            $duplicate=$duplicate;
        }else{
            $dataCity=str_replace(array('-'),array(''),$value['cust_cf_credit_city']);
            $count=strlen($dataCity);
            if($count > 200){
                $error['cust_cf_credit_city']='<td style="background: #E07171;">'.$value['cust_cf_credit_city'].'=>'.$count.'</td>';
                $errorData=$errorData+1;
                $successData=$successData;
                $duplicate=$duplicate;
            }else{
                $error['cust_cf_credit_city']='<td>'.$value['cust_cf_credit_city'].'</td>';
                $errorData=$errorData;
                $successData=$successData;
                $duplicate=$duplicate;
            }
        }
        if($value['cust_cf_credit_postal_code']=='' OR empty($value['cust_cf_credit_postal_code'])){
            $error['cust_cf_credit_postal_code']='<td style="background: #E07171;">'.$value['cust_cf_credit_postal_code'].'</td>';
            $errorData=$errorData+1;
            $successData=$successData;
            $duplicate=$duplicate;
        }else{
            $dataPostal=str_replace(array('-'),array(''),$value['cust_cf_credit_postal_code']);
            $count=strlen($dataPostal);
            if($count > 10){
                $error['cust_cf_credit_postal_code']='<td style="background: #E07171;">'.$value['cust_cf_credit_postal_code'].' =>'.$count.'</td>';
                $errorData=$errorData+1;
                $successData=$successData;
                $duplicate=$duplicate;
            }else{
                $error['cust_cf_credit_postal_code']='<td>'.$value['cust_cf_credit_postal_code'].'</td>';
                $errorData=$errorData;
                $successData=$successData;
                $duplicate=$duplicate;
            }
            
        }
        if($value['cust_cf_credit_hp']=='' OR empty($value['cust_cf_credit_hp'])){
            $error['cust_cf_credit_hp']='<td style="background: #E07171;">'.$value['cust_cf_credit_hp'].'</td>';
            $errorData=$errorData+1;
            $successData=$successData;
            $duplicate=$duplicate;
        }else{
            $phone=str_replace(array('-'),array(''),$value['cust_cf_credit_hp']);
            $count=strlen($phone);
            if(preg_match("/^[0-9]*$/",$phone)){
                if($count > 20){
                    $error['cust_cf_credit_hp']='<td style="background: #E07171;">'.$phone.'  =>'.$count.'</td>';
                    $errorData=$errorData+1;
                    $successData=$successData;
                    $duplicate=$duplicate;
                }else{
                    $error['cust_cf_credit_hp']='<td>'.$value['cust_cf_credit_hp'].'</td>';
                    $errorData=$errorData;
                    $successData=$successData;
                    $duplicate=$duplicate;
                }
               
            }else{
                $error['cust_cf_credit_hp']='<td style="background: #E07171;">'.$phone.'  =>'.$count.'</td>';
                $errorData=$errorData+1;
                $successData=$successData;
                $duplicate=$duplicate;
            }
           
        }
        if($value['cust_cf_credit_email']=='' OR empty($value['cust_cf_credit_email'])){
            $error['cust_cf_credit_email']='<td style="background: #E07171;">'.$value['cust_cf_credit_email'].'</td>';
            $errorData=$errorData+1;
            $successData=$successData;
            $duplicate=$duplicate;
        }else{
            $dataName=str_replace(array('-'),array(''),$value['cust_cf_credit_email']);
            $count=strlen($dataName);
            if($count > 200){
                $error['cust_cf_credit_email']='<td style="background: #E07171;">'.$value['cust_cf_credit_email'].'=>'.$count.'</td>';
                $errorData=$errorData+1;
                $successData=$successData;
                $duplicate=$duplicate;
            }else{
                if(!filter_var($value['cust_cf_credit_email'], FILTER_VALIDATE_EMAIL)){
                    $error['cust_cf_credit_email']='<td style="background: #E07171;">'.$value['cust_cf_credit_email'].'</td>';
                    $errorData=$errorData+1;
                    $successData=$successData;
                    $duplicate=$duplicate;
                }else{
                    $error['cust_cf_credit_email']='<td>'.$value['cust_cf_credit_email'].'</td>';
                    $errorData=$errorData;
                    $successData=$successData;
                    $duplicate=$duplicate;
                }
                
            }
            
        }
        if($value['cust_cf_debitor_id']=='' OR empty($value['cust_cf_debitor_id'])){
            $error['cust_cf_debitor_id']='<td style="background: #E07171;">'.$value['cust_cf_debitor_id'].'</td>';;
            $errorData=$errorData+1;
            $successData=$successData;
            $duplicate=$duplicate;
        }else{
            // if(is_numeric($value['cust_cf_debitor_id'])){
            $dataId=str_replace(array('-'),array(''),$value['cust_cf_debitor_id']);
            $count=strlen($dataId);
                if($count > 100){
                    $error['cust_cf_debitor_id']='<td style="background: #E07171;">'.$value['cust_cf_debitor_id'].'=>'.$count.'</td>';
                    $errorData=$errorData+1;
                    $successData=$successData;
                    $duplicate=$duplicate;
                }else{
                    $error['cust_cf_debitor_id']='<td>'.$value['cust_cf_debitor_id'].'</td>';
                    $errorData=$errorData;
                    $successData=$successData;
                    $duplicate=$duplicate;
                }
            // }else{
            //     $error['cust_cf_debitor_id']='<td style="background: #E07171;">'.$value['cust_cf_debitor_id'].'=> Masukan Angka Tidak Boleh Huruf</td>';;
            //     $errorData=$errorData+1;
            //     $successData=$successData;
            //     $duplicate=$duplicate;
            // }
            
        }
        if($value['cust_cf_debitor_name']=='' OR empty($value['cust_cf_debitor_name'])){
            $error['cust_cf_debitor_name']='<td style="background: #E07171;">'.$value['cust_cf_debitor_name'].'</td>';
            $errorData=$errorData+1;
            $successData=$successData;
            $duplicate=$duplicate;
        }else{
            $dataName=str_replace(array('-'),array(''),$value['cust_cf_debitor_name']);
            $count=strlen($dataName);
            if($count > 200){
                $error['cust_cf_debitor_name']='<td style="background: #E07171;">'.$value['cust_cf_debitor_name'].'=>'.$count.'</td>';
                $errorData=$errorData+1;
                $successData=$successData;
                $duplicate=$duplicate;
            }else{
                $error['cust_cf_debitor_name']='<td>'.$value['cust_cf_debitor_name'].'</td>';
                $errorData=$errorData;
                $successData=$successData;
                $duplicate=$duplicate;
            }
        }

        if($value['cust_cf_debitor_dob']=='' OR empty($value['cust_cf_debitor_dob'])){
            $error['cust_cf_debitor_dob']='<td style="background: #E07171;">'.$value['cust_cf_debitor_dob'].'</td>';
            $errorData=$errorData+1;
            $successData=$successData;
            $duplicate=$duplicate;
        }else{
            $birthDate = new DateTime(date('Y-m-d',($value['cust_cf_debitor_dob']-25569)*86400));
            $today = new DateTime("today");
            
                $y = $today->diff($birthDate)->y;
                $m = $today->diff($birthDate)->m;
                $d = $today->diff($birthDate)->d;
                // die(print_r($y."Tahun".$m."Bulan".$d."Hari"));
           if($y < 17 ){
                // die(print_r($y."Tahun".$m."Bulan".$d."Hari"."GAGAL 1"));
                $error['cust_cf_debitor_dob']='<td style="background: #E07171;">'.date('Y-m-d',($value['cust_cf_debitor_dob']-25569)*86400).' =>'.$y.'TAHUN</td>';
                $errorData=$errorData+1;
                $successData=$successData;
                $duplicate=$duplicate;
            
           }else if($y > 100){
                // die(print_r($y."Tahun".$m."Bulan".$d."Hari"."GAGAL 2"));
                 $error['cust_cf_debitor_dob']='<td style="background: #E07171;">'.date('Y-m-d',($value['cust_cf_debitor_dob']-25569)*86400).' =>'.$y.'TAHUN</td>';
                 $errorData=$errorData+1;
                 $successData=$successData;
                 $duplicate=$duplicate;
           }else{
            // die(print_r($y."Tahun".$m."Bulan".$d."Hari"."SUKSES"));
                $error['cust_cf_debitor_dob']='<td>'.date('Y-m-d',($value['cust_cf_debitor_dob']-25569)*86400).'</td>';
                $errorData=$errorData;
                $successData=$successData;
                $duplicate=$duplicate;
           }
            
            
        }
        if($value['cust_cf_debitor_ktp']=='' OR empty($value['cust_cf_debitor_ktp'])){
            $error['cust_cf_debitor_ktp']='<td style="background: #E07171;">'.$value['cust_cf_debitor_ktp'].'</td>';
            $errorData=$errorData+1;
            $successData=$successData;
            $duplicate=$duplicate;
        }else{
            $data1=str_replace(array('-'),array(''),$value['cust_cf_debitor_ktp']);
            $count=strlen($data1);
            if(preg_match("/^[0-9]*$/",$data1)){
                if($count == 16){
                    $error['cust_cf_debitor_ktp']='<td>'.$value['cust_cf_debitor_ktp'].'</td>';
                    $errorData=$errorData;
                    $successData=$successData;
                    $duplicate=$duplicate;
                }else{
                    $error['cust_cf_debitor_ktp']='<td style="background: #E07171;">'.$data1.'  =>'.$count.'</td>';
                    $errorData=$errorData+1;
                    $successData=$successData;
                    $duplicate=$duplicate;
                }
               
            }else{
                $error['cust_cf_debitor_ktp']='<td style="background: #E07171;">'.$data1.'  =>'.$count.'</td>';
                $errorData=$errorData+1;
                $successData=$successData;
                $duplicate=$duplicate;
            }
           
        }
        

        if($value['cust_cf_debitor_address']=='' OR empty($value['cust_cf_debitor_address'])){
            $error['cust_cf_debitor_address']='<td style="background: #E07171;">'.$value['cust_cf_debitor_address'].'</td>';
            $errorData=$errorData+1;
            $successData=$successData;
            $duplicate=$duplicate;
        }else{
            $dataAddres2=str_replace(array('-'),array(''),$value['cust_cf_debitor_address']);
            $count=strlen($dataAddres2);
            if($count > 500){
                $error['cust_cf_debitor_address']='<td style="background: #E07171;">'.$value['cust_cf_debitor_address'].'=>'.$count.'</td>';
                $errorData=$errorData+1;
                $successData=$successData;
                $duplicate=$duplicate;
            }else{
                $error['cust_cf_debitor_address']='<td>'.$value['cust_cf_debitor_address'].'</td>';
                $errorData=$errorData;
                $successData=$successData;
                $duplicate=$duplicate;
            }
        }
        
        if($value['cust_cf_debitor_city']=='' OR empty($value['cust_cf_debitor_city'])){
            $error['cust_cf_debitor_city']='<td style="background: #E07171;">'.$value['cust_cf_debitor_city'].'</td>';
            $errorData=$errorData+1;
            $successData=$successData;
            $duplicate=$duplicate;
        }else{
            $dataCity=str_replace(array('-'),array(''),$value['cust_cf_debitor_city']);
            $count=strlen($dataCity);
            if($count > 200){
                $error['cust_cf_debitor_city']='<td style="background: #E07171;">'.$value['cust_cf_debitor_city'].'=>'.$count.'</td>';
                $errorData=$errorData+1;
                $successData=$successData;
                $duplicate=$duplicate;
            }else{
                $error['cust_cf_debitor_city']='<td>'.$value['cust_cf_debitor_city'].'</td>';
                $errorData=$errorData;
                $successData=$successData;
                $duplicate=$duplicate;
            }
        }

        if($value['cust_cf_debitor_postal_code']=='' OR empty($value['cust_cf_debitor_postal_code'])){
            $error['cust_cf_debitor_postal_code']='<td style="background: #E07171;">'.$value['cust_cf_debitor_postal_code'].'</td>';
            $errorData=$errorData+1;
            $successData=$successData;
            $duplicate=$duplicate;
        }else{
            $dataPostal=str_replace(array('-'),array(''),$value['cust_cf_debitor_postal_code']);
            $count=strlen($dataPostal);
            if($count > 10){
                $error['cust_cf_debitor_postal_code']='<td style="background: #E07171;">'.$value['cust_cf_debitor_postal_code'].' =>'.$count.'</td>';
                $errorData=$errorData+1;
                $successData=$successData;
                $duplicate=$duplicate;
            }else{
                $error['cust_cf_debitor_postal_code']='<td>'.$value['cust_cf_debitor_postal_code'].'</td>';
                $errorData=$errorData;
                $successData=$successData;
                $duplicate=$duplicate;
            }
            
        }
        if($value['cust_cf_debitor_hp']=='' OR empty($value['cust_cf_debitor_hp'])){
            $error['cust_cf_debitor_hp']='<td style="background: #E07171;">'.$value['cust_cf_debitor_hp'].'</td>';
            $errorData=$errorData+1;
            $successData=$successData;
            $duplicate=$duplicate;
        }else{
            $phone=str_replace(array('-'),array(''),$value['cust_cf_debitor_hp']);
            $count=strlen($phone);
            if(preg_match("/^[0-9]*$/",$phone)){
                if($count >20){
                    $error['cust_cf_debitor_hp']='<td style="background: #E07171;">'.$phone.'  =>'.$count.'</td>';
                    $errorData=$errorData+1;
                    $successData=$successData;
                    $duplicate=$duplicate;
                }else{
                    $error['cust_cf_debitor_hp']='<td>'.$value['cust_cf_debitor_hp'].'</td>';
                    $errorData=$errorData;
                    $successData=$successData;
                    $duplicate=$duplicate;
                }
               
            }else{
                $error['cust_cf_debitor_hp']='<td style="background: #E07171;">'.$phone.'  ==='.$count.'</td>';
                $errorData=$errorData+1;
                $successData=$successData;
                $duplicate=$duplicate;
            }
           
        }
        if($value['cust_cf_debitor_email']=='' OR empty($value['cust_cf_debitor_email'])){
            $error['cust_cf_debitor_email']='<td style="background: #E07171;">'.$value['cust_cf_debitor_email'].'</td>';
            $errorData=$errorData+1;
            $successData=$successData;
            $duplicate=$duplicate;
        }else{
            if(!filter_var($value['cust_cf_debitor_email'], FILTER_VALIDATE_EMAIL)){
                $error['cust_cf_debitor_email']='<td style="background: #E07171;">'.$value['cust_cf_debitor_email'].'</td>';
                $errorData=$errorData+1;
                $successData=$successData;
                $duplicate=$duplicate;
            }else{
                $error['cust_cf_debitor_email']='<td>'.$value['cust_cf_debitor_email'].'</td>';
                $errorData=$errorData;
                $successData=$successData;
                $duplicate=$duplicate;
            }
            
        }
        if($value['cust_cf_loan']=='' OR empty($value['cust_cf_loan'])){
            $error['cust_cf_loan']='<td style="background: #E07171;">'.$value['cust_cf_loan'].'</td>';
            $errorData=$errorData+1;
            $successData=$successData;
            $duplicate=$duplicate;
        }else{
            if(is_numeric($value['cust_cf_loan'])){
                $error['cust_cf_loan']='<td>'.$value['cust_cf_loan'].'</td>';
                $errorData=$errorData;
                $successData=$successData;
                $duplicate=$duplicate;
            }else{
                $error['cust_cf_loan']='<td style="background: #E07171;">'.$value['cust_cf_loan'].'</td>';
                $errorData=$errorData+1;
                $successData=$successData;
                $duplicate=$duplicate;
            }
            
        }
        if($value['cust_cf_inseption_date']=='' OR empty($value['cust_cf_inseption_date'])){
            $error['cust_cf_inseption_date']='<td style="background: #E07171;">'.date('Y-m-d',($value['cust_cf_inseption_date']-25569)*86400).'</td>';
            $errorData=$errorData+1;
            $successData=$successData;
            $duplicate=$duplicate;
            
        }else{
                $validInception = date("Y-m-d", strtotime("-4 months"));
                // $today = date("Y-m-d");
                $dateInput = date('Y-m-d',($value['cust_cf_inseption_date']-25569)*86400);
                // die(print_r($validInception));
                #if($dateInput > $validInception){
                    // die(print_r($validInception.'-------'.date('Y-m-d',($value['cust_cf_inseption_date']-25569)*86400)));
                    // die("TEST3");
                    $error['cust_cf_inseption_date']='<td>'.date('Y-m-d',($value['cust_cf_inseption_date']-25569)*86400).'</td>';
                    $errorData=$errorData;
                    $successData=$successData;
                    $duplicate=$duplicate;
                /*}else{
                    // die("TEST4");
                    $error['cust_cf_inseption_date']='<td style="background: #E07171;">'.date('Y-m-d',($value['cust_cf_inseption_date']-25569)*86400).'</td>';
                    $errorData=$errorData+1;
                    $successData=$successData;
                    $duplicate=$duplicate;
                }*/
            
        }

        if($value['cust_cf_long_loan']=='' OR empty($value['cust_cf_long_loan'])){
            $error['cust_cf_long_loan']='<td style="background: #E07171;">'.$value['cust_cf_long_loan'].'</td>';
            $errorData=$errorData+1;
            $successData=$successData;
            $duplicate=$duplicate;
        }else{
            $data3=str_replace(array('-'),array(''),$value['cust_cf_long_loan']);
            $count=strlen($data3);
            if(preg_match("/^[0-9]*$/",$data3)){
                $error['cust_cf_long_loan']='<td>'.$value['cust_cf_long_loan'].'</td>';
                        $errorData=$errorData;
                        $successData=$successData;
                        $duplicate=$duplicate;
                // if($count < 3){
                //     $loanx = 'Paket '.$value['cust_cf_long_loan'];
                //     if($value['cust_cf_package'] == $loanx) {
                //         $error['cust_cf_long_loan']='<td>'.$value['cust_cf_long_loan'].'</td>';
                //         $errorData=$errorData;
                //         $successData=$successData;
                //         $duplicate=$duplicate;
                //     }else{
                //         $error['cust_cf_long_loan']='<td style="background: #E07171;">'.$data3.'  =>Paket dan Lama Pinjaman Tidak Sesuai</td>';
                //         $errorData=$errorData+1;
                //         $successData=$successData;
                //         $duplicate=$duplicate;
                //     }
                // }else{
                //     $error['cust_cf_long_loan']='<td style="background: #E07171;">'.$data3.'  =>'.$count.'</td>';
                //     $errorData=$errorData+1;
                //     $successData=$successData;
                //     $duplicate=$duplicate;
                // }
               
            }else{
                $error['cust_cf_long_loan']='<td style="background: #E07171;">'.$data3.'  =>Tidak Bisa Huruf</td>';
                $errorData=$errorData+1;
                $successData=$successData;
                $duplicate=$duplicate;
            }
           
        }

        $cekPackage = $this->db->query("SELECT * FROM ref_package where ref_package_period = '".$value['cust_cf_long_loan']."' ");
        $hasilPackage = $cekPackage->row_array();
        // die(print_r($value['cust_cf_long_loan']));
        // die(print_r($cekPackage->result_array()));
        $dataPackage ='';
        if($cekPackage->num_rows() > 0){
            $error['cust_cf_package']='<td>'.$hasilPackage['ref_package_name'].'</td>';
            $errorData=$errorData;
            $successData=$successData;
            $duplicate=$duplicate;
            $dataPackage = $hasilPackage['ref_package_name'];
        }else{
            $error['cust_cf_package']='<td style="background: #E07171;">'.$hasilPackage['ref_package_name'].' Paket Tidak tersedia di Referensi </td>';
            $errorData=$errorData+1;
            $successData=$successData;
            $duplicate=$duplicate;
            $dataPackage = $hasilPackage['ref_package_name'];
        }

        
       
        
        if($errorData>0){
            $data['dataError']=1;
            $data['dataSuccess']=0;
            $data['errorRow']=$error;
            $data['successRow']=array();
        }else{
            $paket = $this->db->query("select * from ref_package where ref_package_name = '".$dataPackage."'");
            $hasilPaket = $paket->row_array();
            $premium = $value['cust_cf_loan'] * $hasilPaket['ref_package_rate'] / 100;
            $data['successRow']=array(
                            "cust_cf_source_id"=>$value['cust_cf_source_id'],
                            "cust_cf_source_label"=>$value['cust_cf_source_label'],
                            "cust_cf_upload_code"=>$value['cust_cf_upload_code'],
                            "cust_cf_no_ref"=>$value['cust_cf_no_ref'],
                            "cust_cf_credit_name"=>$value['cust_cf_credit_name'],
                            "cust_cf_credit_dob"=>date('Y-m-d',($value['cust_cf_credit_dob']-25569)*86400),
                            "cust_cf_credit_ktp"=>$value['cust_cf_credit_ktp'],
                            "cust_cf_credit_address"=>$value['cust_cf_credit_address'],
                            "cust_cf_credit_city"=>$value['cust_cf_credit_city'],
                            "cust_cf_credit_postal_code"=>$value['cust_cf_credit_postal_code'],
                            "cust_cf_credit_hp"=>preg_replace("/[^0-9]/", '',$value['cust_cf_credit_hp']),
                            "cust_cf_credit_email"=>$value['cust_cf_credit_email'],
                            "cust_cf_debitor_id"=>$value['cust_cf_debitor_id'],
                            "cust_cf_debitor_name"=>$value['cust_cf_debitor_name'],
                            "cust_cf_debitor_dob"=>date('Y-m-d',($value['cust_cf_debitor_dob']-25569)*86400),
                            "cust_cf_debitor_ktp"=>$value['cust_cf_debitor_ktp'],
                            "cust_cf_debitor_address"=>$value['cust_cf_debitor_address'],
                            "cust_cf_debitor_city"=>$value['cust_cf_debitor_city'],
                            "cust_cf_debitor_postal_code"=>$value['cust_cf_debitor_postal_code'],
                            "cust_cf_debitor_hp"=>preg_replace("/[^0-9]/", '',$value['cust_cf_debitor_hp']),
                            "cust_cf_debitor_email"=>$value['cust_cf_debitor_email'],
                            "cust_cf_loan"=>$value['cust_cf_loan'],
                            "cust_cf_package"=>$dataPackage,
                            "cust_cf_inseption_date"=>date('Y-m-d',($value['cust_cf_inseption_date']-25569)*86400),
                            "cust_cf_long_loan"=>$value['cust_cf_long_loan'],
                            "cust_cf_premium"=>$premium,
                            "cust_cf_policy_generate_date"=>$value['cust_cf_policy_generate_date'],
                        );
            $data['errorRow']=array();
            $data['dataError']=0;
            $data['dataSuccess']=1;


        }
        $data['duplicate']=$duplicate;
        
        // die(print_r($data));
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
  
        $excel->setActiveSheetIndex(0)->setCellValue('A1', "No Ref/ TransactionID");
        $excel->setActiveSheetIndex(0)->setCellValue('B1', "Creditor Name");
        $excel->setActiveSheetIndex(0)->setCellValue('C1', "Creditor Date of Birth (yyyy-mm-dd)");
        $excel->setActiveSheetIndex(0)->setCellValue('D1', "Creditor Number ID");
        $excel->setActiveSheetIndex(0)->setCellValue('E1', "Address Creditor");
        $excel->setActiveSheetIndex(0)->setCellValue('F1', "City Creditor");
        $excel->setActiveSheetIndex(0)->setCellValue('G1', "Postal Code Creditor");
        $excel->setActiveSheetIndex(0)->setCellValue('H1', "Creditor Phone Number (62xxxxxxxxx)");
        $excel->setActiveSheetIndex(0)->setCellValue('I1', "Creditor Email");
        $excel->setActiveSheetIndex(0)->setCellValue('J1', "Debtor ID");
        $excel->setActiveSheetIndex(0)->setCellValue('K1', "Debtor Name");
        $excel->setActiveSheetIndex(0)->setCellValue('L1', "Debtor Date of Birth (yyyy-mm-dd)");
        $excel->setActiveSheetIndex(0)->setCellValue('M1', "Debtor Number ID");
        $excel->setActiveSheetIndex(0)->setCellValue('N1', "Debtor Address");
        $excel->setActiveSheetIndex(0)->setCellValue('O1', "City Debtor");
		$excel->setActiveSheetIndex(0)->setCellValue('P1', "Postal Code Debtor");
		$excel->setActiveSheetIndex(0)->setCellValue('Q1', "Debtor Phone Number");
		$excel->setActiveSheetIndex(0)->setCellValue('R1', "Email Debtor");
		$excel->setActiveSheetIndex(0)->setCellValue('S1', "Loan"); 
		$excel->setActiveSheetIndex(0)->setCellValue('T1', "InceptionDate (yyyy-mm-dd)");
		$excel->setActiveSheetIndex(0)->setCellValue('U1', "Loan Period (in monts)");
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