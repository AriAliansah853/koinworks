<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*
March 18, 2019 10:55:52 PM dyah line 237, 277 $ket_his
*/
class Upload extends CI_Controller {

	function __construct() {
        parent::__construct();
        if ( !($this->session->userdata('logged_in')) ) {
            redirect(base_url());
        } else {
        	$this->session->set_userdata('sidebar', 'upload');
        }

        date_default_timezone_set("Asia/Jakarta");
        // $this->load->model('Upload_model');
    }

	public function index(){
		$this->upload();
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

	public function upload(){
		$data = array(
			'sidebar'	=> 'upload_data',
		);
		$this->load->view('upload/upload_data_view', $data);	
	}

	public function history_upload(){
		$data = array(
			'sidebar'	=> 'upload_history',
			'listData' 	=> $this->db->query("SELECT * FROM obj_history_upload ORDER BY oHistUploadUploadTime DESC "),
		);
		$this->load->view('upload/history_upload_view', $data);
	}

	public function upload_file(){
		// header("Content-Type: text/plain");
		$data = array();
		$dataJson = ''; 
		
		if(isset($_POST['preview'])){ 

			$dateKode = 'PINDUIT'.date('Ymd');
			// CROWDO20190318001
	        $sqlUrut = $this->db->query("SELECT coalesce(MAX(SUBSTR(tDataProcessUploadCode,15,5)),'0') AS maks FROM temp_data_process WHERE SUBSTR(tDataProcessUploadCode,1,14) = '".$dateKode."'");

	        $rowurut = $sqlUrut->row_array();
	                    
	        if($rowurut['maks'] == '0') {
	          $urut = 00001;
	        } else {
	          $urut = ($rowurut['maks']*1) + 1;
	        }
	        $kodeUpload = $dateKode.''.str_pad($urut,5,'0',STR_PAD_LEFT);

	        $filename = "import_data_".$kodeUpload."_".date('Ymd')."_".str_replace(' ' , '_', $this->session->userdata('PinUsername'));
	        $filenameExport = $kodeUpload."_".date('YmdHis')."_".str_replace(' ' , '_', $this->session->userdata('PinUsername'));
	        $filename_upload = $_FILES['file']['name'];

			$sqlUser = $this->db->query("SELECT * FROM ref_login WHERE rLoginKey = '".$this->session->userdata('PinKey')."' LIMIT 1 ");
		    $rrUser = $sqlUser->row_array();
		    if ($sqlUser->num_rows() > 0) {
		    	$userKey	= $rrUser['rLoginKey'];
		    	$sourceId 	= $rrUser['rLoginSourceId'];
		    } else {
		    	$userKey	= $this->session->userdata('PinKey');
		    	$sourceId 	= $this->session->userdata('PinSource');
		    }
			// die(print_r($_FILES));
			$this->load->library('upload'); 			
		
			$config['upload_path'] = './assets/upload_excel/import';
			$config['allowed_types'] = 'xlsx';
			$config['max_size']	= '2048';
			$config['overwrite'] = true;
			$config['file_name'] = $filename;
		
			$this->upload->initialize($config); 
			if($this->upload->do_upload('file')){ 

				$upload = array('result' => 'success', 'file' => $this->upload->data(), 'error' => '');
				// return $upload;
			}else{

				$upload = array('result' => 'failed', 'file' => '', 'error' => $this->upload->display_errors());
				// return $upload;
			}
			
			if($upload['result'] == "success"){ 

				include APPPATH.'third_party/PHPExcel/PHPExcel.php';
				
				$excelreader = new PHPExcel_Reader_Excel2007();
				$loadexcel = $excelreader->load('assets/upload_excel/import/'.$filename.'.xlsx');
				$sheet = $loadexcel->getActiveSheet()->toArray(null, true, true ,true);

				$numrow = 0;
				$error = 0;
				$success = 0;
				$cekError = 0;
				$cekSuccess = 0;
				$out = array();
				foreach($sheet as $row){ 

					$orderTime 				= $row['A']; 					
					$transactionId 			= $row['B'];
					$productId 				= $row['C'];  
					$borrowerId 			= $row['D']; 
					$borrowerName			= $row['E'];
					$dob 		 			= $row['F'];
					$borrowerAddress		= $row['G'];
					$city		 			= $row['H'];
					$zipCode	 			= $row['I'];					
					$borrowerOccupation		= $row['J'];
					$borrowerMobileNumber 	= $row['K'];
					$borrowerEmailAddress	= $row['L'];
					$creditAmount 			= $row['M'];
					$startEfectiveTime 		= $row['N'];
					$coverPeriod 			= $row['O'];

					if(empty($orderTime) && empty($productId) && empty($transactionId) && empty($borrowerId) && empty($borrowerName) && empty($borrowerAddress) && empty($city) && empty($zipCode) && empty($dob) && empty($borrowerOccupation) && empty($borrowerMobileNumber) && empty($borrowerEmailAddress) && empty($creditAmount) && empty($startEfectiveTime) && empty($coverPeriod))
						continue;

					if($numrow > 0){
						$arrReplace = array('-', ' ');
						$orderTimeReplace 			= str_replace('-', '/', $orderTime);
    					$dobReplace 				= str_replace($arrReplace, '/', $dob);
						$startEfectiveTimeReplace 	= str_replace($arrReplace, '/', $startEfectiveTime);
						
						// $startDate = PHPExcel_Calculation_Functions::flattenSingleValue($startEfectiveTime);
						// $val = date('Y-m-d', PHPExcel_Shared_Date::ExcelToPHP($startEfectiveTimeReplace));
						// $var  = PHPExcel_Style_NumberFormat::toFormattedString($startEfectiveTime,  ‘Y-m-d’);

						$dateOrderTimeCek = strtotime(date('Y-m-d', strtotime($orderTimeReplace)));
						// $dateOrderTimeCek2 = strtotime(date('Y-m-d', strtotime($orderTime)));
						$startEfectiveTimeCek = strtotime(date('Y-m-d', strtotime($startEfectiveTimeReplace)));
    					// 	$today = strtotime('-7 days',strtotime(date("Y-m-d")));
    					// 	$today30 = strtotime((date("2019-03-01 00:00:00")));    					
    					
    					// 	$today2 = strtotime(date('Y-m-d'));

						$dateOrderTime 			= strtotime($orderTimeReplace);
						$dateDob 				= strtotime($dobReplace);	
						$dateStartEfectiveTime 	= strtotime($startEfectiveTimeReplace);	

						$borrowerEmail 	= trim($borrowerEmailAddress);
						$creditAmountt 	= preg_replace('/[^0-9]/','',$creditAmount);	
						$coverPeriode 	= preg_replace('/[^0-9]/','',$coverPeriod);
						$phone 			= preg_replace('/[^0-9]/','',$borrowerMobileNumber);

						if (substr($phone,0,1) == 0) {
							$mobilphone = $phone;
						} else if (substr($phone,0,2) == 62) {
							$mobilphone = '0'.substr($phone, 2);
						} else {
							$mobilphone = '0'.$phone;
						}

						$index = 'transactionId'.'-'.$transactionId;

						// if(empty($orderTime) OR empty($productId) OR empty($transactionId) OR empty($borrowerId) OR empty($insuredName) OR empty($insuredAddress) OR empty($insuredOccupation) OR empty($taxationNo) OR empty($mobilNumber) OR empty($emailAddress) OR empty($creditAmount) OR empty($startEfectiveTime) OR empty($coverPeriod) OR empty($invoiceNomor) OR empty($payorName) OR empty($loanExpired) OR empty($expireDate) OR !is_numeric($dateLoanExpired) == TRUE OR !is_numeric($dateExpireDate) == TRUE OR !is_numeric($mobilNumber) == TRUE OR !is_numeric($creditAmount) == TRUE OR !is_numeric($dateOrderTime) == TRUE OR !is_numeric($dateStartEfectiveTime) == TRUE OR ($dateOrderTimeCek <= $today OR $today2 < $dateOrderTimeCek) OR $startEfectiveTimeCek >= $dateOrderTimeCek2 OR $coverPeriode >= 180){
						if(empty($orderTime) OR empty($productId) OR 
						   empty($transactionId) OR empty($borrowerId) OR 
						   empty($borrowerName) OR empty($borrowerAddress) OR 
						   // empty($city) OR empty($zipCode) OR 
						   empty($dob) OR empty($borrowerOccupation) OR 
						   empty($borrowerMobileNumber) OR empty($borrowerEmailAddress) OR 
						   empty($creditAmount) OR empty($startEfectiveTime) OR 
						   empty($coverPeriod) OR 

						   $orderTime == "-" OR $productId == "-" OR 
						   $transactionId == "-" OR $borrowerId == "-" OR 
						   $borrowerName == "-" OR $borrowerAddress == "-" OR 
						   // $city == "-" OR $zipCode == "-" OR 
						   $dob == "-" OR $borrowerOccupation == "-" OR 
						   $borrowerMobileNumber == "-" OR $borrowerEmailAddress == "-" OR 
						   $creditAmount == "-" OR $startEfectiveTime == "-" OR 
						   $coverPeriod == "-" OR 

						   !is_numeric($dateOrderTime) == TRUE OR !is_numeric($creditAmountt) == TRUE OR $creditAmountt > 50000000 OR
						   !is_numeric($dateDob) == TRUE OR !is_numeric($mobilphone) == TRUE OR 
						   !is_numeric($dateStartEfectiveTime) == TRUE OR 
						   // ($dateOrderTimeCek <= $today OR $today2 < $dateOrderTimeCek2) OR 
						   // ($dateOrderTimeCek <= $today30 OR $today2 < $dateOrderTimeCek2) OR 
						   // $startEfectiveTimeCek < $today OR
						   // $today2 < $dateOrderTimeCek2 OR 
						   // $startEfectiveTimeCek < $dateOrderTimeCek2 OR 
						   $dateOrderTimeCek != $startEfectiveTimeCek OR
						   $coverPeriode > 365
						  ){
							
							$sqlObjDataProsesCek = $this->db->query("SELECT * FROM obj_data_process WHERE oDataProcessTransactionId = '".$transactionId ."' AND oDataProcessSourceId=3 ");
							if($sqlObjDataProsesCek->num_rows()>0){
								$error++; 
								$data['data_upload_error'][] = $cekError-1;
								$out[$index][$transactionId]++;
								$duplicate = 1;
							}else{
								$error++; 
								$data['data_upload_error'][] = $cekError-1;
								$duplicate = 0;
							}
							
							// $out[$index][$transactionId]++;
						} else {
							// die($out[$index][$transactionId]);
							if (array_key_exists($index, $out) > 0) {
								$error++; 
								$data['data_upload_error'][] = $cekError-1;
								$out[$index][$transactionId]++;
								$duplicate = 1;
							} else {
								$sqlObjDataProsesCek2 = $this->db->query("SELECT * FROM obj_data_process WHERE oDataProcessTransactionId = '".$transactionId ."' AND oDataProcessSourceId=3 ");
								if($sqlObjDataProsesCek2->num_rows()>0){
									$error++; 
									$data['data_upload_error'][] = $cekError-1;
									$duplicate = 1;
								}else{
									$success++;
									$data['data_upload_success'][] = $cekSuccess-1;
									$out[$index][$transactionId] = 1;
									$duplicate = 0;
								}
								
							}
							
						}

						$data['sheet'][] = array(
							'orderTime'				=> $orderTimeReplace, 			
							'productId'				=> $productId, 			
							'transactionId'			=> $transactionId, 		
							'borrowerId'			=> $borrowerId, 		
							'borrowerName'			=> $borrowerName,	
							'dob'					=> $dobReplace, 	
							'borrowerAddress'		=> $borrowerAddress, 
							'city'					=> $city, 	
							'zipCode'				=> $zipCode,								
							'borrowerOccupation'	=> $borrowerOccupation, 		
							'borrowerMobileNumber'	=> $mobilphone, 		
							'borrowerEmailAddress'	=> $borrowerEmail,
							'creditAmount'			=> $creditAmountt, 	
							'startEfectiveTime'		=> $startEfectiveTimeReplace, 		
							'coverPeriod'			=> $coverPeriode, 	
							'duplicate'				=> $duplicate,	
						);

					}

					$numrow++; 
					$cekError++;
					$cekSuccess++;
				}

				$data['error'] = $error;
				$data['success'] = $success;

				if ($data['success'] > 0) {

					$existingCount = 0;
					foreach ($data['data_upload_success'] as $valueSuccess) {

						$sqlObjDataProses = $this->db->query("SELECT * FROM obj_data_process WHERE oDataProcessTransactionId = '".$data['sheet'][$valueSuccess]['transactionId']."' AND oDataProcessSourceId=3 ");
						$rrObjDataProses = $sqlObjDataProses->row_array();
						if ($sqlObjDataProses->num_rows() > 0) { // jika filename upload excelnya ada							
							// redirect('upload');
							$existingCount++;
						} else { // jika filename upload excelnya tidak ada

							$dataJson[] = array(
								'orderTime'					=> date('Y-m-d H:i:s', strtotime($data['sheet'][$valueSuccess]['orderTime'])), 			
								'productId'					=> $data['sheet'][$valueSuccess]['productId'], 			
								'transactionId'				=> $data['sheet'][$valueSuccess]['transactionId'], 		
								'borrowerId'				=> $data['sheet'][$valueSuccess]['borrowerId'], 		
								'borrowerName'				=> $data['sheet'][$valueSuccess]['borrowerName'],
								'dob'						=> date('Y-m-d', strtotime($data['sheet'][$valueSuccess]['dob'])), 		
								'borrowerAddress'			=> $data['sheet'][$valueSuccess]['borrowerAddress'],
								'city'						=> $data['sheet'][$valueSuccess]['city'], 	
								'zipCode'					=> $data['sheet'][$valueSuccess]['zipCode'],											
								'borrowerOccupation'		=> $data['sheet'][$valueSuccess]['borrowerOccupation'], 		
								'borrowerMobileNumber'		=> $data['sheet'][$valueSuccess]['borrowerMobileNumber'], 		
								'borrowerEmailAddress'		=> trim($data['sheet'][$valueSuccess]['borrowerEmailAddress']), 	
								'creditAmount'				=> $data['sheet'][$valueSuccess]['creditAmount'],
								'startEfectiveTime'			=> date('Y-m-d', strtotime($data['sheet'][$valueSuccess]['startEfectiveTime'])), 		
								'coverPeriod'				=> $data['sheet'][$valueSuccess]['coverPeriod'],	
							);
						}										
					}

					if ($dataJson != '') {
						$dataInsert = array(
					    	'tDataProcessSourceId'			=> $sourceId,
					    	'tDataProcessLoginKey'			=> $userKey,
					    	'tDataProcessLocalIp'			=> $this->getUserIP(),
					    	'tDataProcessIp'				=> $this->getUserIP(),
					    	'tDataProcessUploadCode'		=> $kodeUpload,
					    	'tDataProcessExcelRow'			=> json_encode($dataJson),
					    	'tDataProcessExcelName'			=> $filename_upload,
					    	'tDataProcessUploadDatetime'	=> date('Y-m-d H:i:s'),
					    	'tDataProcessStatus'			=> 0,
					    );
						// die(print_r($dataInsert));
					    if ($this->db->insert('temp_data_process', $dataInsert)) {
					    	$idInsertTemp = $this->db->insert_id();

					    	$insHistoryUpload = array(
						    	'oHistUploadCode'		=> $kodeUpload,
						    	'oHistUploadUploadTime'	=> date('Y-m-d H:i:s'),
						    	'oHistUploadSuccess'	=> $data['success'],
						    	'oHistUploadFailed'		=> $data['error'],
						    	'oHistUploadExisting'	=> $existingCount,
						    	'oHistUploadLoginId'	=> $this->session->userdata('PinUserId'),
						    );
						    $this->db->insert('obj_history_upload', $insHistoryUpload);
					    	
					    	$action = 'INSERT';
					    	$ket_his = 'Processing upload excel data, with file name : '.$filename_upload;
							$array_before = '-';
							$array_after = '-';
					    	$insLog = array(
						    	'histLogLoginId'	=> $this->session->userdata('PinUserId'),
						    	'histLogLoginUser'	=> $this->session->userdata('PinUser'),
						    	'histLogDatetime'	=> date('Y-m-d H:i:s'),
						    	'histLogType'		=> $action,
						    	'histLogTable'		=> 'temp_data_process',
						    	'histLogTableId'	=> $idInsertTemp,
						    	'histLogShortDesc'	=> $ket_his,
						    	'histLogBefore'		=> $array_before,
						    	'histLogAfter'		=> $array_after,
						    );
						    $this->db->insert('history_log', $insLog);

					    	$sqlTmpDataProses = $this->db->query("SELECT * FROM temp_data_process WHERE tDataProcessId = '".$idInsertTemp."' ");
					    	$rrTmpDataProses = $sqlTmpDataProses->row_array();
					    	if ($sqlTmpDataProses->num_rows() > 0) {
					    		$excelRow = json_decode($rrTmpDataProses['tDataProcessExcelRow'], TRUE);
								foreach ($excelRow as $valueExcelRow) {

									$dateKodefuse = 'FUSE'.date('Ymd');
							        $sqlUrut2 = $this->db->query("SELECT coalesce(MAX(SUBSTR(oDataProcessFuseCode,13,5)),'0') AS maks FROM obj_data_process WHERE SUBSTR(oDataProcessFuseCode,1,12) = '".$dateKodefuse."'");
							        $rowurut2 = $sqlUrut2->row_array();							                    
							        if($rowurut2['maks'] == '0') {
							          	$urut2 = 00001;
							        } else {
							          	$urut2 = ($rowurut2['maks']*1) + 1;
							        }
							        $kodeUploadFuse = $dateKodefuse.''.str_pad($urut2,5,'0',STR_PAD_LEFT);

							        if($valueExcelRow['coverPeriod'] <= 365){
										$rate = 1.3;
									} else {
										$rate = 0;
									}
									$premiumAmount = ($valueExcelRow['creditAmount'] * $rate)/100;
									$activeStart=$valueExcelRow['coverPeriod']-1;
									if($valueExcelRow['coverPeriod']==365){
										$EndEffectiveTime = strtotime('+1 Year',strtotime($valueExcelRow['startEfectiveTime']));
										// die('dd');
									}else{
										$EndEffectiveTime = strtotime('+'.$activeStart.' days',strtotime($valueExcelRow['startEfectiveTime']));
										// die('xx');
									}	
									$dataAddressDet = array(
										'city'						=> $valueExcelRow['city'], 	
										'zipCode'					=> $valueExcelRow['zipCode'],	
										'dob'						=> $valueExcelRow['dob'],
									);

									$dataInsertObj = array(
										'oDataProcessSourceId'				=> $sourceId,
										'oDataProcessTempId'				=> $idInsertTemp,
										'oDataProcessOrdertime'				=> $valueExcelRow['orderTime'],
										'oDataProcessUploadCode'			=> $rrTmpDataProses['tDataProcessUploadCode'],
										'oDataProcessUploadDate'			=> $rrTmpDataProses['tDataProcessUploadDatetime'],
										'oDataProcessTransactionId'			=> $valueExcelRow['transactionId'],
										'oDataProcessProductId'				=> $valueExcelRow['productId'],
										'oDataProcessCustomerId'			=> $valueExcelRow['borrowerId'],
										'oDataProcessFuseCode'				=> $kodeUploadFuse,
										'oDataProcessInsuredName'			=> $valueExcelRow['borrowerName'],
										'oDataProcessPhoneNumber'			=> $valueExcelRow['borrowerMobileNumber'],
										'oDataProcessEmail'					=> $valueExcelRow['borrowerEmailAddress'],
										'oDataProcessInsuredAddress'		=> $valueExcelRow['borrowerAddress'],
										'oDataProcessInsuredAddressDet'		=> json_encode($dataAddressDet),
										'oDataProcessInsuredOccupation'		=> $valueExcelRow['borrowerOccupation'],
										'oDataProcessCreditAmount'			=> $valueExcelRow['creditAmount'],
										'oDataProcessPremiumAmount'			=> $premiumAmount,
										'oDataProcessStartEffectiveTime'	=> $valueExcelRow['startEfectiveTime'],
										'oDataProcessEndEffectiveTime'		=> date('Y-m-d', $EndEffectiveTime),
										'oDataProcessCoverPeriod'			=> $valueExcelRow['coverPeriod'],
									);
									// die(print_r($dataInsertObj));	
									$dataParam = array(
										"SourceID"			=> "20190418FUSE",
										"NoRef"				=> $valueExcelRow['transactionId'],
										"CustName"			=> $valueExcelRow['borrowerName'],
										"DateOfBirth"		=> date('m/d/Y', strtotime($valueExcelRow['dob'])),
										"InsuredIdNo"		=> $valueExcelRow['borrowerId'],
										"CustAddress"		=> $valueExcelRow['borrowerAddress'],
										"Occupation"		=> $valueExcelRow['borrowerOccupation'],
										"City"				=> $valueExcelRow['city'],
										"ZipCode"			=> $valueExcelRow['zipCode'],
										"PhoneNo"			=> "02122546511",
										"HandPhone"			=> "02122546511",
										"Email"				=> "bd@fuse.co.id",
										"Amount"			=> $valueExcelRow['creditAmount'],
										"Package"			=> "Paket 1",
										"InseptionDate"		=> date('m/d/Y', strtotime($valueExcelRow['startEfectiveTime'])),
										"Duration"			=> $valueExcelRow['coverPeriod'],
										"Beneficiary"		=> array(
																	array(
																		"BeneficiaryName" 			=> "PT. Pinduit Teknologi Indonesia",
																		"BeneficiaryRelationship" 	=> "Perusahaan",
																		"BeneficiaryPercentage" 	=> "100"
																	)
															   ),
										"Note"				=> "",
									);

									$cekAPI = $this->sendData($dataParam);
									// die(print_r($cekAPI));
									if ($cekAPI['ErrorCode'] != 1) {
										$this->db->insert('obj_data_process', $dataInsertObj);
										$idInsertObj 	= $this->db->insert_id();

								    	$action 		= 'INSERT';
								    	$ket_his 		= 'Upload data has been success.';
										$array_before 	= '-';
										$array_after 	= '-';

								    	$insLog = array(
									    	'histLogLoginId'	=> $this->session->userdata('PinUserId'),
									    	'histLogLoginUser'	=> $this->session->userdata('PinUser'),
									    	'histLogDatetime'	=> date('Y-m-d H:i:s'),
									    	'histLogType'		=> $action,
									    	'histLogTable'		=> 'obj_data_process',
									    	'histLogTableId'	=> $idInsertObj,
									    	'histLogShortDesc'	=> $ket_his,
									    	'histLogBefore'		=> $array_before,
							    			'histLogAfter'		=> $array_after,
									    );
									    $this->db->insert('history_log', $insLog);

									    $kode 		= $idInsertObj;
										$urut 		= str_pad($kode, 6, 000000, STR_PAD_LEFT);
										$sertifikat = "AP".date('y').''.$urut;
										
										$responseApi = array(
											"SourceID"			=> $cekAPI['SourceID'],
											"NoRef"				=> $cekAPI['NoRef'],
											"ErrorCode"			=> $cekAPI['ErrorCode'],
											"ErrorMessage"		=> $cekAPI['ErrorMessage'],
											"PolicyInsuranceNo"	=> $cekAPI['PolicyInsuranceNo'],
											"IssueDate"			=> $cekAPI['IssueDate']
										);

										$dataUpdate=array(
											"oDataPushParameterApi"			=> json_encode($dataParam),
											"oDataReponseApi"				=> json_encode($responseApi),
											"oDataProcessPolicyNumber" 		=> $cekAPI['PolicyInsuranceNo'],
											"oDataProcessPolicySertifikat"	=> $sertifikat
										);

										$this->db->where('oDataProcessId ',$idInsertObj)->update('obj_data_process',$dataUpdate);
										 
										$action 		= 'UPDATE';
										$ket_his 		= 'When User '.$this->session->userdata('user').', Upload Excel at '.date('Y-m-d H:i:s').'';
										$array_before 	= implode("::",$dataInsertObj);
										$array_after 	= implode("::",$dataUpdate);

										$insLog = array(
											'histLogLoginId'	=> $this->session->userdata('PinUserId'),
											'histLogLoginUser'	=> $this->session->userdata('PinUser'),
											'histLogDatetime'	=> date('Y-m-d H:i:s'),
											'histLogType'		=> $action,
											'histLogTable'		=> 'obj_data_process',
											'histLogTableId'	=> $idInsertObj,
											'histLogShortDesc'	=> $ket_his,
											'histLogBefore'		=> $array_before,
											'histLogAfter'		=> $array_after,
										);
										$this->db->insert('history_log', $insLog);
									} else {
										$data['noError']	= 4;
									}
								}
					    	}
					    }
					} 

					$data['existingCount'] = $existingCount;

					if ($data['error'] == 0) {
						if ($dataJson == '') {
							$data['noError']	= 3;
						} else {
							$data['noError']	= 2;
						}
					} else {
						$data['noError']	= 1;
					}

				} else {
					$data['noError']	= 0;
				}

			} else {
				$data['upload_error'] = $upload['error']; 
				$data['noError']	= 0;
			}
		}
		$data['sidebar'] = 'upload_data';
		$data['filename'] = $filename;
		
		// die(print_r($data));
		
		$this->load->view('upload/upload_data_view', $data);
	}

	public function sendData($dataApi) {
		// die(print_r($dataApi));
		
		$data = str_replace('\/','/',json_encode($dataApi));
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

}