<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Data extends CI_Controller {

	function __construct() {
        parent::__construct();
        $this->load->model('middleware','middle');

        date_default_timezone_set("Asia/Jakarta");
	}
	
	public function index(){
		$this->all();
	}
	public function all(){

		
		$data = array(
			'sidebar'	=> 'Policy',
			'whereClause'=>'',
			'policy'=>$this->db->where('cust_cf_credit_ktp !=','')->get('obj_customer_cf')->num_rows(),
			'loan'=>number_format($this->db->query("select coalesce(sum(cust_cf_loan),'0') as total from obj_customer_cf where cust_cf_credit_ktp !=''")->row('total'),'0',',','.'),
			'premium'=>number_format($this->db->query("select coalesce(sum(cust_cf_premium),'0') as total from obj_customer_cf where cust_cf_credit_ktp !=''")->row('total'),'0',',','.'),
			'kreditamount'=>number_format($this->db->query("select coalesce(sum(cust_cf_loan),'0') as total from obj_customer_cf where cust_cf_credit_ktp !=''")->row('total'),'0',',','.'),
			
		);
		// print_r($data);die();
		$this->load->view('data/data_policy_view', $data);	
	}
	public function detail($id){
		$tabled='';
		$sqlDet=$this->db->where('cust_cf_inst_id_hd',$id)->get('obj_cust_cf_installment');

			$tabled .='<table class="table table-striped table-bordered table-hover" id="details" width="100%">
						<thead>
							<tr style="text-align:center;" align="center">
								<th>No.</th>
								<th>Nominal</th>
								<th>Tanggal Bayar </th>
							</tr>
						</thead>
						<tbody';
            if($sqlDet->num_rows()>0){
				$no=1;
                foreach($sqlDet->result_array() as $rrDet){


					$tabled .='<tr style="text-align:center;" align="center">
								<td>'.$no.'</td>
								<td>'.$rrDet['cust_cf_inst_nominal'].'</td>
								<td>'.$rrDet['cust_cf_inst_exp_date'].'</td>
								</tr>';
                   
					$no++;
                }
            }else{
                $detData=array();
			}
			$tabled .='</tbody></table>';
			$data['data']=$tabled;
			$this->sendOutput($data);
	}
	public function get_data(){
		// print_r($_POST);
		if(count($_POST)>0){
			if($_POST['policyNumber']==''){
				$policy="";
			}else{
				$policy="AND cust_cf_policy_number='".$_POST['policyNumber']."'";
			}
			if($_POST['startUploadTime']==''){
				$uploadTime="";
			}else{
				$uploadTime=" AND cust_cf_insert_date >='".$_POST['startUploadTime']."'";
			}
			if($_POST['policyStatus']==''){
				$policyStatus="";
			}else{
				$policyStatus=" AND cust_cf_status='".$_POST['policyStatus']."'";
			}
			if($_POST['emailAddress']==''){
				$emailAddress="";
			}else{
				$emailAddress=" AND  cust_cf_credit_email='".$_POST['emailAddress']."'";	
			}
			if($_POST['endUploadTime']==''){
				$endUploadTime="";
			}else{
				$endUploadTime=" AND cust_cf_insert_date <='".$_POST['endUploadTime']."'";	
			}
			if($_POST['insuredName']==''){
				$insuredName="";
			}else{
				$insuredName=" AND  cust_cf_credit_name='".$_POST['insuredName']."'";	
			}
			// if($_POST['startEffectiveTime']==''){
			// 	$startEffectiveTime="";
			// }else{
			// 	$startEffectiveTime=" AND cust_cf_inseption_date >='".$_POST['startEffectiveTime']."'";	
			// }
			if($_POST['mobileNumber']==''){
				$mobileNumber="";
			}else{
				$mobileNumber=" AND cust_cf_credit_hp='".$_POST['mobileNumber']."'";	
			}
			if($_POST['trxId']==''){
				$trxId="";
			}else{
				$trxId=" AND cust_cf_no_ref='".$_POST['trxId']."'";	
			}

			if($_POST['debitor_name']==''){
				$debitor_name="";
			}else{
				$debitor_name=" AND cust_cf_debitor_name ='".$_POST['debitor_name']."'";	
			}

			if($_POST['debitor_ktp']==''){
				$Dktp="";
			}else{
				$Dktp=" AND cust_cf_debitor_ktp = ".$_POST['debitor_ktp']." ";	
			}

			if($_POST['debitor_email']==''){
				$Demail="";
			}else{
				$Demail=" AND cust_cf_debitor_email='".$_POST['debitor_email']."'";	
			}

			if($_POST['debitor_id']==''){
				$Did="";
			}else{
				$Did=" AND cust_cf_debitor_id='".$_POST['debitor_id']."'";	
			}
			// if($_POST['endEffectiveTime']==''){
			// 	$endEffectiveTime="";
			// }else{
			// 	$endEffectiveTime=" AND customerPolicyExpiredDate <='".$_POST['endEffectiveTime']."'";	
			// }
			if($_POST['startInception']=='' || $_POST['endInception']==''){
				$startEffectiveTime="";
				$endEffectiveTime="";
				$startEffectiveTime2="";
			}else{
				$startEffectiveTime=" AND cust_cf_inseption_date between '".$_POST['startInception']."' and '".$_POST['endInception']."'";	
				$startEffectiveTime2=" cust_cf_inseption_date between '".$_POST['startInception']."' and '".$_POST['endInception']."'";	
				// $endEffectiveTime=" AND cust_cf_inseption_date >='".$_POST['endInception']."'";	
			}
			// die(print_r($startEffectiveTime.'---------------'.$endEffectiveTime));
			// if($_POST['endEffectiveTime']==''){
			// 	$endEffectiveTime="";
			// }else{
			// 	$endEffectiveTime=" AND customerPolicyExpiredDate <='".$_POST['endEffectiveTime']."'";	
			// }

			$whereData=$policy.' '.$uploadTime.' '.$policyStatus.' '.$emailAddress.' '.$endUploadTime.' '.$insuredName.' '.$startEffectiveTime.' '.$mobileNumber.' '.$trxId.' '.$debitor_name.' '.$Dktp.' '.$Demail.' '.$Did;
			$whereId=urlencode(str_replace('=', '', base64_encode($whereData)));
			if($startEffectiveTime2 == '' && $policy == '' && $trxId == '' && $policy == '' && $endUploadTime == '' && $insuredName == '' && $mobileNumber == '' && $trxId == '' && $debitor_name == '' && $Dktp == '' && $Demail == '' && $Did == '' && $policyStatus == ''){
				// die("TEST2");
				$hasilPremi = $this->db->query("select coalesce(sum(cust_cf_premium),'0') as total from obj_customer_cf where cust_cf_credit_ktp !=''");
				$hasilLoan = $this->db->query("select coalesce(sum(cust_cf_loan),'0') as total_loan from obj_customer_cf where cust_cf_credit_ktp !='' ");
				$hasilPolicy = $this->db->query("select COUNT(cust_cf_credit_ktp) as total_policy from obj_customer_cf where cust_cf_credit_ktp !=''");
				// number_format(->row('total'),'0',',','.')
				// $CekPremi = $hasilPremi->result_array();
				$cekPolicy = $hasilPolicy->row_array();
				$hasilPolicy = $cekPolicy['total_policy'];
				$cekLoan = $hasilLoan->row_array();
				$hasilLoan = number_format((float)$cekLoan['total_loan'], 0, '.', '');
				$cekPremi = $hasilPremi->row_array();
				$hasilData = number_format((float)$cekPremi['total'], 0, '.', '');
			}else{
				// die("TEST");
				$hasilPremi = $this->db->query("select coalesce(sum(cust_cf_premium),'0') as total from obj_customer_cf where cust_cf_credit_ktp !='' ".$whereData."");
				$hasilLoan = $this->db->query("select coalesce(sum(cust_cf_loan),'0') as total_loan from obj_customer_cf where cust_cf_credit_ktp !='' ".$whereData." ");
				$hasilPolicy = $this->db->query("select COUNT(cust_cf_credit_ktp) as total_policy from obj_customer_cf where cust_cf_credit_ktp !='' ".$whereData." ");
				// number_format(->row('total'),'0',',','.')
				// $CekPremi = $hasilPremi->result_array();
				$cekPolicy = $hasilPolicy->row_array();
				$hasilPolicy = $cekPolicy['total_policy'];
				$cekLoan = $hasilLoan->row_array();
				$hasilLoan = number_format((float)$cekLoan['total_loan'], 0, '.', '');
				$cekPremi = $hasilPremi->row_array();
				$hasilData = number_format((float)$cekPremi['total'], 0, '.', '');
				
			}
			
			// die($hasilData);
		}else{
			$whereId='';
			$hasilData = '0';
			$hasilLoan = '0';
			$hasilPolicy = '0';
		}
		$arrayItem['data']=$whereId;
		$arrayItem['data2']=$hasilData;
		$arrayItem['data3']=$hasilLoan;
		$arrayItem['data4']=$hasilPolicy;
		// die($hasilData.'---'.$hasilLoan.'---'.$hasilPolicy);
		$this->sendOutput($arrayItem);

	}
	public function get_data_old(){
		// print_r($_POST);
		if(count($_POST)>0){
			if($_POST['policyNumber']==''){
				$policy="";
			}else{
				$policy="AND cust_cf_policy_number='".$_POST['policyNumber']."'";
			}
			if($_POST['startUploadTime']==''){
				$uploadTime="";
			}else{
				$uploadTime=" AND cust_cf_insert_date >='".$_POST['startUploadTime']."'";
			}
			if($_POST['policyStatus']==''){
				$policyStatus="";
			}else{
				$policyStatus=" AND cust_cf_status='".$_POST['policyStatus']."'";
			}
			if($_POST['emailAddress']==''){
				$emailAddress="";
			}else{
				$emailAddress=" AND  cust_cf_credit_email='".$_POST['emailAddress']."'";	
			}
			if($_POST['endUploadTime']==''){
				$endUploadTime="";
			}else{
				$endUploadTime=" AND cust_cf_insert_date <='".$_POST['endUploadTime']."'";	
			}
			if($_POST['insuredName']==''){
				$insuredName="";
			}else{
				$insuredName=" AND  cust_cf_credit_name='".$_POST['insuredName']."'";	
			}
			// if($_POST['startEffectiveTime']==''){
			// 	$startEffectiveTime="";
			// }else{
			// 	$startEffectiveTime=" AND cust_cf_inseption_date >='".$_POST['startEffectiveTime']."'";	
			// }
			if($_POST['mobileNumber']==''){
				$mobileNumber="";
			}else{
				$mobileNumber=" AND cust_cf_credit_hp='".$_POST['mobileNumber']."'";	
			}
			if($_POST['trxId']==''){
				$trxId="";
			}else{
				$trxId=" AND cust_cf_no_ref='".$_POST['trxId']."'";	
			}
			// if($_POST['endEffectiveTime']==''){
			// 	$endEffectiveTime="";
			// }else{
			// 	$endEffectiveTime=" AND customerPolicyExpiredDate <='".$_POST['endEffectiveTime']."'";	
			// }
			if($_POST['startInception']=='' || $_POST['endInception']==''){
				$startEffectiveTime="";
				$endEffectiveTime="";
				$startEffectiveTime2="";
			}else{
				$startEffectiveTime=" AND cust_cf_inseption_date between '".$_POST['startInception']."' and '".$_POST['endInception']."'";	
				$startEffectiveTime2=" cust_cf_inseption_date between '".$_POST['startInception']."' and '".$_POST['endInception']."'";	
				// $endEffectiveTime=" AND cust_cf_inseption_date >='".$_POST['endInception']."'";	
			}
			// die(print_r($startEffectiveTime.'---------------'.$endEffectiveTime));
			// if($_POST['endEffectiveTime']==''){
			// 	$endEffectiveTime="";
			// }else{
			// 	$endEffectiveTime=" AND customerPolicyExpiredDate <='".$_POST['endEffectiveTime']."'";	
			// }

			$whereData=$policy.' '.$uploadTime.' '.$policyStatus.' '.$emailAddress.' '.$endUploadTime.' '.$insuredName.' '.$startEffectiveTime.' '.$mobileNumber.' '.$trxId;
			$whereId=urlencode(str_replace('=', '', base64_encode($whereData)));
			if($startEffectiveTime2 == '' && $policy == '' && $trxId == '' && $policy == '' && $endUploadTime == '' && $insuredName == '' && $mobileNumber == '' && $trxId == '' && $debitor_name == '' && $Dktp == '' && $Demail == '' && $Did == ''){
				// die("TEST2");
				$hasilPremi = $this->db->query("select coalesce(sum(cust_cf_premium),'0') as total from obj_customer_cf where cust_cf_policy_number!=''");
				$hasilLoan = $this->db->query("select coalesce(sum(cust_cf_loan),'0') as total_loan from obj_customer_cf where cust_cf_policy_number!='' ");
				$hasilPolicy = $this->db->query("select COUNT(cust_cf_policy_number) as total_policy from obj_customer_cf where cust_cf_policy_number!='' ");
				// number_format(->row('total'),'0',',','.')
				// $CekPremi = $hasilPremi->result_array();
				$cekPolicy = $hasilPolicy->row_array();
				$hasilPolicy = $cekPolicy['total_policy'];
				$cekLoan = $hasilLoan->row_array();
				$hasilLoan = number_format((float)$cekLoan['total_loan'], 0, '.', '');
				$cekPremi = $hasilPremi->row_array();
				$hasilData = number_format((float)$cekPremi['total'], 0, '.', '');
			}else{
				// die("TEST");
				$hasilPremi = $this->db->query("select coalesce(sum(cust_cf_premium),'0') as total from obj_customer_cf where cust_cf_policy_number!='' ".$whereData."");
				$hasilLoan = $this->db->query("select coalesce(sum(cust_cf_loan),'0') as total_loan from obj_customer_cf where cust_cf_policy_number!='' ".$whereData." ");
				$hasilPolicy = $this->db->query("select COUNT(cust_cf_policy_number) as total_policy from obj_customer_cf where cust_cf_policy_number!='' ".$whereData." ");
				// number_format(->row('total'),'0',',','.')
				// $CekPremi = $hasilPremi->result_array();
				$cekPolicy = $hasilPolicy->row_array();
				$hasilPolicy = $cekPolicy['total_policy'];
				$cekLoan = $hasilLoan->row_array();
				$hasilLoan = number_format((float)$cekLoan['total_loan'], 0, '.', '');
				$cekPremi = $hasilPremi->row_array();
				$hasilData = number_format((float)$cekPremi['total'], 0, '.', '');
				
			}
			
			// die($hasilData);
		}else{
			$whereId='';
			$hasilData = '0';
			$hasilLoan = '0';
			$hasilPolicy = '0';
		}
		$arrayItem['data']=$whereId;
		$arrayItem['data2']=$hasilData;
		$arrayItem['data3']=$hasilLoan;
		$arrayItem['data4']=$hasilPolicy;
		// die($hasilData.'---'.$hasilLoan.'---'.$hasilPolicy);
		$this->sendOutput($arrayItem);

	}
	public function logHistory($id,$idTemp=''){
		$tabled='';
		$SQLTemp = $this->db->query("SELECT * FROM history_log WHERE histLogTableId = '".$id."' AND histLogTable = 'obj_customer_cf' ");
		$id_temp = 0;
		if ($SQLTemp->num_rows() > 0) {
			foreach ($SQLTemp->result_array() as $rrSqlTemp) {
				$id_temp .= ','.$rrSqlTemp['histLogId'];
			}			
		} else {
			$id_temp = 0;
		}

		$SQLObj = $this->db->query("SELECT * FROM history_log WHERE histLogTableId = '".$id."' AND histLogTable = 'obj_customer_cf'");
		$id_obj = 0;
		if ($SQLObj->num_rows() > 0) {
			foreach ($SQLObj->result_array() as $rrSqlObj) {
				$id_obj .= ','.$rrSqlObj['histLogId'];
			}			
		} else {
			$id_obj = 0;
		}

		$idWhere = $id_temp.','.$id_obj;

		$tabled .='<table class="table table-striped table-bordered table-hover" id="details" width="100%">
					<thead>
						<tr style="text-align:center;" align="center">
							<th>No.</th>
							<th width="20%">Log Type</th>
							<th width="40%">Short Desc</th>
							<th>Time</th>
							<th>User </th>
						</tr>
					</thead>
					<tbody id="isiDetail" style="font-size:10px;">';
		$logData = $this->db->query("SELECT * FROM history_log WHERE histLogId in (".$idWhere.") AND histLogTable = 'obj_customer_cf' ORDER BY histLogId DESC ");
		if($logData->num_rows()>0){
			$no=1;
			foreach($logData->result_array() as $isi){
				$tabled .='<tr style="text-align:center;" align="center">
				<td>'.$no.'</td>
				<td>'.$isi['histLogType'].'</td>
				<td>'.$isi['histLogShortDesc'].'</td>
				<td>'.$isi['histLogDatetime'].'</td>
				<td>'.$isi['histLogLoginUser'].'</td>
				</tr>';
			$no++;
			}
		}else{
		}	
		$tabled .='</tbody></table>';
		$data['data']=$tabled;
		$this->sendOutput($data);
	}
	public function update($key){
		$params=json_decode(base64_decode(urldecode($key)),true);
		// print_r($params);
		$sql=$this->db->where('cust_cf_id', $params['dataKey'])->get('obj_customer_cf');
		if($sql->num_rows()>0){


			$this->db->insert('obj_customer_cf_delete',$sql->row_array());
			$id_Data=$this->db->insert_id();
			$action = 'INSERT';
			$ket_his = 'Processing Delete data obj_customer_cf';
			$array_before = '-';
			$array_after = '-';
			$insLog = array(
							'histLogLoginId'	=> $this->session->userdata('userId'),
							'histLogLoginUser'	=> $this->session->userdata('name'),
							'histLogDatetime'	=> date('Y-m-d H:i:s'),
							'histLogType'		=> $action,
							'histLogTable'		=> 'obj_customer_cf_delete',
							'histLogTableId'	=> $id_Data,
							'histLogShortDesc'	=> $ket_his,
							'histLogBefore'		=> $array_before,
							'histLogAfter'		=> $array_after,
						);
			$this->db->insert('history_log', $insLog);
			$sql=$this->db->where('cust_cf_id', $params['dataKey'])->delete('obj_customer_cf');
			$action = 'DELETE';
			$ket_his = 'Processing Delete data obj_customer_cf';
			$array_before = '-';
			$array_after = '-';
			$insLog = array(
							'histLogLoginId'	=> $this->session->userdata('userId'),
							'histLogLoginUser'	=> $this->session->userdata('name'),
							'histLogDatetime'	=> date('Y-m-d H:i:s'),
							'histLogType'		=> $action,
							'histLogTable'		=> 'obj_customer_cf_delete',
							'histLogTableId'	=> $params['dataKey'],
							'histLogShortDesc'	=> $ket_his,
							'histLogBefore'		=> $array_before,
							'histLogAfter'		=> $array_after,
						);
			$this->db->insert('history_log', $insLog);
			$response='0';
		}else{
			$response='1';
		}
		$this->sendOutput($response);
	}
	public function export($id){
		$whereId=base64_decode(urldecode($id));
		$query="obj_customer_cf where cust_cf_credit_ktp !=''  ".$whereId;
		$sql=$this->db->query("select * from ".$query." LIMIT 4200");


		include APPPATH.'third_party/PHPExcel/PHPExcel.php';

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
			$excel->setActiveSheetIndex(0)->setCellValue('B1', "Code Upload");
			$excel->setActiveSheetIndex(0)->setCellValue('C1', "Creditor Name");
			$excel->setActiveSheetIndex(0)->setCellValue('D1', "Creditor Date of Birth");
			$excel->setActiveSheetIndex(0)->setCellValue('E1', "Creditor Number ID");
			$excel->setActiveSheetIndex(0)->setCellValue('F1', "Address Creditor");
			$excel->setActiveSheetIndex(0)->setCellValue('G1', "City Creditor");
			$excel->setActiveSheetIndex(0)->setCellValue('H1', "Postal Code Creditor");
			$excel->setActiveSheetIndex(0)->setCellValue('I1', "Creditor Phone Number");
			$excel->setActiveSheetIndex(0)->setCellValue('J1', "Creditor Email");
			$excel->setActiveSheetIndex(0)->setCellValue('K1', "Debtor ID");
			$excel->setActiveSheetIndex(0)->setCellValue('L1', "Debtor Name");
			$excel->setActiveSheetIndex(0)->setCellValue('M1', "Debtor Date of Birth");
			$excel->setActiveSheetIndex(0)->setCellValue('N1', "Debtor Number ID");
			$excel->setActiveSheetIndex(0)->setCellValue('O1', "Debtor Address");
			$excel->setActiveSheetIndex(0)->setCellValue('P1', "City Debtor");
			$excel->setActiveSheetIndex(0)->setCellValue('Q1', "Postal Code Debtor");
			$excel->setActiveSheetIndex(0)->setCellValue('R1', "Debtor Phone Number");
			$excel->setActiveSheetIndex(0)->setCellValue('S1', "Email Debtor");
			$excel->setActiveSheetIndex(0)->setCellValue('T1', "Loan");
			$excel->setActiveSheetIndex(0)->setCellValue('U1', "Package");
			$excel->setActiveSheetIndex(0)->setCellValue('V1', "InceptionDate");
			$excel->setActiveSheetIndex(0)->setCellValue('W1', "Loan Period (in months)");
			$excel->setActiveSheetIndex(0)->setCellValue('X1', "Insert Date");
			$excel->setActiveSheetIndex(0)->setCellValue('Y1', "Policy Number");
			$excel->setActiveSheetIndex(0)->setCellValue('Z1', "Premium");
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
			$excel->getActiveSheet()->getStyle('O1')->applyFromArray($style_col);
			$excel->getActiveSheet()->getStyle('P1')->applyFromArray($style_col);
			$excel->getActiveSheet()->getStyle('Q1')->applyFromArray($style_col);
			$excel->getActiveSheet()->getStyle('R1')->applyFromArray($style_col);
			$excel->getActiveSheet()->getStyle('S1')->applyFromArray($style_col);
			$excel->getActiveSheet()->getStyle('T1')->applyFromArray($style_col);
			$excel->getActiveSheet()->getStyle('U1')->applyFromArray($style_col);
			$excel->getActiveSheet()->getStyle('V1')->applyFromArray($style_col);
			$excel->getActiveSheet()->getStyle('W1')->applyFromArray($style_col);
			$excel->getActiveSheet()->getStyle('X1')->applyFromArray($style_col);
			$excel->getActiveSheet()->getStyle('Y1')->applyFromArray($style_col);
			$excel->getActiveSheet()->getStyle('Z1')->applyFromArray($style_col);

		  
			$numrow = 2; 
			
			foreach($sql->result_array() as $rrData){ 
				// $monthNum = sprintf("%02s", $rrData['cust_cf_long_loan']);
                // $monthName = date("F", strtotime($monthNum));

				// $months = floor($rrData['cust_cf_long_loan'] / 30);
				
				if($rrData['cust_cf_long_loan'] == '730'){
                    $monthNum = sprintf("%02s", $rrData['cust_cf_long_loan']);
                    $monthName = date("F", strtotime($monthNum));

                    $months = floor($rrData['cust_cf_long_loan'] / 30);
                }else if($rrData['cust_cf_long_loan'] == '28'){
                    $monthNum = sprintf("%02s", $rrData['cust_cf_long_loan']);
                    $monthName = date("F", strtotime($monthNum));

                    $months = floor($rrData['cust_cf_long_loan'] / 28);
                }else{
                    $monthNum = sprintf("%02s", $rrData['cust_cf_long_loan']);
                    $monthName = date("F", strtotime($monthNum));

                    $months = floor($rrData['cust_cf_long_loan'] / 29);
                }
				$excel->setActiveSheetIndex(0)->setCellValue('A'.$numrow, $rrData['cust_cf_no_ref']);
				$excel->setActiveSheetIndex(0)->setCellValue('B'.$numrow, $rrData['cust_cf_upload_code']);
				$excel->setActiveSheetIndex(0)->setCellValue('C'.$numrow, $rrData['cust_cf_credit_name']);
				$excel->setActiveSheetIndex(0)->setCellValue('D'.$numrow, $rrData['cust_cf_credit_dob']);
				$excel->setActiveSheetIndex(0)->setCellValue('E'.$numrow, $rrData['cust_cf_credit_ktp']);
				$excel->setActiveSheetIndex(0)->setCellValue('F'.$numrow, $rrData['cust_cf_credit_address']);
				$excel->setActiveSheetIndex(0)->setCellValue('G'.$numrow, $rrData['cust_cf_credit_city']);
				$excel->setActiveSheetIndex(0)->setCellValue('H'.$numrow, $rrData['cust_cf_credit_postal_code']);
				$excel->setActiveSheetIndex(0)->setCellValue('I'.$numrow, $rrData['cust_cf_credit_hp']);
				$excel->setActiveSheetIndex(0)->setCellValue('J'.$numrow, $rrData['cust_cf_credit_email']);
				$excel->setActiveSheetIndex(0)->setCellValue('K'.$numrow, $rrData['cust_cf_debitor_id']);
				$excel->setActiveSheetIndex(0)->setCellValue('L'.$numrow, $rrData['cust_cf_debitor_name']);
				$excel->setActiveSheetIndex(0)->setCellValue('M'.$numrow, $rrData['cust_cf_debitor_dob']);
				$excel->setActiveSheetIndex(0)->setCellValue('N'.$numrow, $rrData['cust_cf_debitor_ktp']);
				$excel->setActiveSheetIndex(0)->setCellValue('O'.$numrow, $rrData['cust_cf_debitor_address']);
				$excel->setActiveSheetIndex(0)->setCellValue('P'.$numrow, $rrData['cust_cf_debitor_city']);
				$excel->setActiveSheetIndex(0)->setCellValue('Q'.$numrow, $rrData['cust_cf_debitor_postal_code']);
				$excel->setActiveSheetIndex(0)->setCellValue('R'.$numrow, $rrData['cust_cf_debitor_hp']);
				$excel->setActiveSheetIndex(0)->setCellValue('S'.$numrow, $rrData['cust_cf_debitor_email']);
				$excel->setActiveSheetIndex(0)->setCellValue('T'.$numrow, $rrData['cust_cf_loan']);
				$excel->setActiveSheetIndex(0)->setCellValue('U'.$numrow, $rrData['cust_cf_package']);
				$excel->setActiveSheetIndex(0)->setCellValue('V'.$numrow, $rrData['cust_cf_inseption_date']);
				$excel->setActiveSheetIndex(0)->setCellValue('W'.$numrow, $months);
				$excel->setActiveSheetIndex(0)->setCellValue('X'.$numrow, $rrData['cust_cf_insert_date']);
				$excel->setActiveSheetIndex(0)->setCellValue('Y'.$numrow, $rrData['cust_cf_policy_number']);
				$excel->setActiveSheetIndex(0)->setCellValue('Z'.$numrow, $rrData['cust_cf_premium']);
				$numrow++; 
			}
		  #output data
		  $excel->getActiveSheet()->getDefaultRowDimension()->setRowHeight(-1);							    
          $excel->getActiveSheet()->getPageSetup()->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_LANDSCAPE);							    
          $excel->getActiveSheet(0)->setTitle("Sheet1");
          $excel->setActiveSheetIndex(0);		
    
          // $sourcePath = FCPATH;		
            // Proses file excel
           
          
            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header('Content-Disposition: attachment; filename="Data Policy '.date('YmdHis').'.xlsx"'); // Set nama file excel nya
            header('Cache-Control: max-age=0');    
            $write = PHPExcel_IOFactory::createWriter($excel, 'Excel2007');
            $write->save('php://output');
	}
	private function sendOutput($response){
        $this->output
            ->set_status_header(200)
            ->set_content_type('application/json', 'utf-8')
            ->set_output(json_encode($response, JSON_PRETTY_PRINT))
            ->_display();
        exit;
	}

	public function export2(){
		
		// $query="obj_customer_cf where cust_cf_credit_ktp !=''  ".$whereId;
		$sql=$this->db->query("select * from obj_customer_cf where cust_cf_credit_ktp !='' ");


		include APPPATH.'third_party/PHPExcel/PHPExcel.php';

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
    
			$excel->setActiveSheetIndex(0)->setCellValue('A1', "Id");
			$excel->setActiveSheetIndex(0)->setCellValue('B1', "Loan");
			$excel->setActiveSheetIndex(0)->setCellValue('C1', "Paket");
			$excel->setActiveSheetIndex(0)->setCellValue('D1', "Rate");
			$excel->setActiveSheetIndex(0)->setCellValue('E1', "Premium");
			
			$excel->getActiveSheet()->getStyle('A1')->applyFromArray($style_col);
			$excel->getActiveSheet()->getStyle('B1')->applyFromArray($style_col);
			$excel->getActiveSheet()->getStyle('C1')->applyFromArray($style_col);
			$excel->getActiveSheet()->getStyle('D1')->applyFromArray($style_col);
			$excel->getActiveSheet()->getStyle('E1')->applyFromArray($style_col);
			

		  
			$numrow = 2; 
			
			foreach($sql->result_array() as $rrData){ 
				// $paket = $this->db->query("select * from ref_package where ref_package_name = '".$rrData['cust_cf_package']."'");
				// $hasilPaket = $paket->row_array();
				
				$excel->setActiveSheetIndex(0)->setCellValue('A'.$numrow, $rrData['cust_cf_no_ref']);
				$excel->setActiveSheetIndex(0)->setCellValue('B'.$numrow, $rrData['cust_cf_loan']);
				$excel->setActiveSheetIndex(0)->setCellValue('C'.$numrow, $rrData['cust_cf_package']);
				$excel->setActiveSheetIndex(0)->setCellValue('D'.$numrow, $rrData['cust_cf_package']);;
				$excel->setActiveSheetIndex(0)->setCellValue('E'.$numrow, $rrData['cust_cf_premium']);
				
				
			}
		  #output data
		  $excel->getActiveSheet()->getDefaultRowDimension()->setRowHeight(-1);							    
          $excel->getActiveSheet()->getPageSetup()->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_LANDSCAPE);							    
          $excel->getActiveSheet(0)->setTitle("Sheet1");
          $excel->setActiveSheetIndex(0);		
    
          // $sourcePath = FCPATH;		
            // Proses file excel
           
          
            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header('Content-Disposition: attachment; filename="Data Policy '.date('YmdHis').'.xlsx"'); // Set nama file excel nya
            header('Cache-Control: max-age=0');    
            $write = PHPExcel_IOFactory::createWriter($excel, 'Excel2007');
            $write->save('php://output');
	}

	public function cekAll()
	{
		$sql['hasil']=$this->db->query("select * from obj_customer_cf");
		$this->load->view('cekData',$sql);
	}
	
	public function updateData()
	{
		$data = $this->db->query("SELECT * FROM obj_customer_cf ");
		foreach($data->result_array() as $key){

			$paket = $this->db->query("select * from ref_package where ref_package_name = '".$key['cust_cf_package']."'");
			$hasilPaket = $paket->row_array();
			$cekPremium = $key['cust_cf_loan'] * $hasilPaket['ref_package_rate'] / 100;
			$dataUpdate = array(
				"cust_cf_premium" => $cekPremium,
				// "cust_cf_id" => $key['cust_cf_id'],
			);
			$this->db->where('cust_cf_id',$key['cust_cf_id'])->update('obj_customer_cf', $dataUpdate);
			// echo "SUKSES";
		}
		// die(print_r($data->result_array()));
	}
}