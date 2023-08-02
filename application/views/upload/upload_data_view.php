<!DOCTYPE html>
<html>
<head>
  	<meta charset="utf-8">
  	<meta http-equiv="X-UA-Compatible" content="IE=edge">
  	<title>Koinworks | Upload</title>
  	<!-- Tell the browser to be responsive to screen width -->
  	<meta name="viewport" content="width=device-width, initial-scale=1">

  	<?php $this->load->view('include/include_basecss'); ?>
	  <link href="<?php echo base_url(); ?>assets/tablesDinamis/css/buttons.dataTables.css" rel="stylesheet" />
	  <style>
	  
	  </style>
</head>
<body class="hold-transition sidebar-mini">
	<div class="wrapper">

  		<?php $this->load->view('include/include_header'); ?>

  		<?php $this->load->view('include/include_sidebar'); ?>

  		<div class="content-wrapper">
		    <div class="content-header">
		      	<div class="container-fluid">
			        <div class="row mb-2">
			          	<div class="col-sm-6">
			            	<h1 class="m-0 text-dark">Upload Data</h1>
			          	</div>
			          	<div class="col-sm-6">
			            	<ol class="breadcrumb float-sm-right">
			              		<li class="breadcrumb-item"><a href="#">Home</a></li>
			              		<li class="breadcrumb-item active">Upload Data</li>
			            	</ol>
			          	</div>
			        </div>
		      	</div>
		    </div>

    		<section class="content">
		      	<div class="container-fluid">
			        <div class="row">

			          	<div class="col-md-12">
			          	
			            	<div class="card card-danger ">
			              		<div class="card-header">
			                		<h3 class="card-title">Upload Data</h3>
			              		</div>
			              
			              		<form role="form" method="POST" id="add_import" action="<?php echo base_url(); ?>upload2/import-data" enctype="multipart/form-data">
			                		<div class="card-body file-class">
					                  	<div class="form-group">
					                    	<label for="exampleInputFile">File input</label>
					                        <input type="file" class="form-control" name="upload_file" id="exampleInputFile">
					                  	</div>
					                </div>

					                <div class="card-footer file-class">
					                  	<button type="button" name="preview" class="btn btn-danger btn-upload">Upload</button>&nbsp;&nbsp;<a href="<?php echo base_url('upload2/export-excel');?>" class="btn btn-success">Contoh File</a>
					                </div>
					             </form>


								 <div class="form-group reload-class" style="display:none">
										 <center><img src="<?php echo base_url();?>assets/img/load-data.gif"/></center>
								</div>
			            	</div>
			        	</div>		        
		      		</div>
		      	</div>
    		</section>
			<section class="content table-data" style="display:none">
		      	<div class="container-fluid">
			        <div class="row">

			          	<div class="col-md-12">

			            	<div class="card">
					            <div class="card-header">
					              <h3 class="card-title">History Upload</h3>
					            </div>

					            <div class="card-body">
					              	<table id="example2" class="table table-bordered table-striped table-responsive display nowrap " width="100%">   
						                <thead>
							                <tr>
												<th>No Ref/ TransactionID</th>
												<th>Kreditur Nama</th>
												<th>Kreditur Tgl Lahir</th>
												<th>Kreditur ID No</th>
												<th>KrediturAlamat</th>
												<th>Kreditur Kota</th>
												<th>Kreditur Kode Pos</th>
												<th>Kreditur Phone Number</th>
												<th>Kreditur Email</th>
												<th>ID Debitur</th>
												<th>Debitur Name</th>
												<th>Debitur Tgl Lahir</th>
												<th>Debitur ID No</th>
												<th>DebitorAlamat</th>
												<th>Debitor Kota</th>
												<th>Debitor KodePos</th>
												<th>Debitor HandPhone</th>
												<th>Debitor Email</th>
												<th>Pinjaman</th>
												<th>Package</th>
												<th>InseptionDate</th>
												<th>Lama Pinjaman (dalam Bulan)</th>
							                </tr>
						                </thead>
						                <tbody style="font-size:10px;" id="detail-data">
										</tbody>
					              	</table>
					            </div>
					            
					          </div>
			        	</div>		        
		      		</div>
		      	</div>
    		</section>
  		</div>
		  
		
		<?php $this->load->view('include/include_footer'); ?>
	  	<aside class="control-sidebar control-sidebar-dark">
	  	</aside>

	</div>

	<?php $this->load->view('include/include_basejs'); ?>
	<script>
	  	$(function () {
	    	$("#example1").DataTable();
	    	$('#example2').DataTable({
	      		"paging": true,
	      		"lengthChange": false,
	      		"searching": false,
	      		"ordering": true,
	      		"info": true,
	      		"autoWidth": false
	    	});
	  	});
	</script>
		<script>
		$(document).ready(function(){
			
			$(".table-data").hide();
			$(".reload-class").hide();
		});



		$(".btn-upload").on('click',function(){
			$(".table-data").hide();
			$(".reload-class").hide();
			if($('[name="upload_file"').val().trim()==''){
				swal({
					title: 'File Upload',
					text: 'Please Fill in the File',
           			type: 'warning',
					showConfirmButton: true
				});

			}else{
				$('.reload-class').show();
				$('.file-class').hide();
				url = "<?php echo site_url('upload2/import-data')?>";
				var formData = new FormData($('#add_import')[0]);
				$.ajax({
					url: url,
					type: "POST",
					data: formData,
					contentType: false,
					processData: false,
					dataType: "JSON",
					success: function (data) {
						$('#add_import')[0].reset();
						if(data.type==0){
							swal({
								title: '',
								text: data.msg,
								type: 'success',
								showConfirmButton: true
							});
							$(".reload-class").hide();
							$('.file-class').show();
							tabelData(data.rowError);
						}else{
							swal({
								title: '',
								text: data.msg,
								type: 'warning',
								showConfirmButton: true
							});
							$(".reload-class").hide(); 
							$('.file-class').show();
							tabelData(data.rowError);
						}
						
					},
					error: function (jqXHR, textStatus, errorThrown) {
						swal({
							title: '',
							text: 'Error Proccessing',
							type: 'error',
							showConfirmButton: true
						});
						$(".reload-class").hide();
						$('.file-class').show();
					}
				});
			}
		})
		function tabelData(data){
			console.log(data);
			if(data.length>0){
				$("#detail-data").html('');
					$.each(data, function (k, v) {
						var td = '<tr style="font-size:12px; text-align:center;" align="center">';
						td +=v.cust_cf_no_ref;
						td +=v.cust_cf_credit_name;
						td +=v.cust_cf_credit_dob;
						td +=v.cust_cf_credit_ktp;
						td +=v.cust_cf_credit_address;
						td +=v.cust_cf_credit_city;
						td +=v.cust_cf_credit_postal_code;
						td +=v.cust_cf_credit_hp;
						td +=v.cust_cf_credit_email;
						td +=v.cust_cf_debitor_id;
						td +=v.cust_cf_debitor_name;
						td +=v.cust_cf_debitor_dob;
						td +=v.cust_cf_debitor_ktp;
						td +=v.cust_cf_debitor_address;
						td +=v.cust_cf_debitor_city;
						td +=v.cust_cf_debitor_postal_code;
						td +=v.cust_cf_debitor_hp;
						td +=v.cust_cf_debitor_email;
						td +=v.cust_cf_loan;
						td +=v.cust_cf_package;
						td +=v.cust_cf_inseption_date;
						td +=v.cust_cf_long_loan;
						$("#detail-data").append(td);
					});
					$('.table-data').show();
			}else{
				
			}
			
		}
	</script>
</body>
</html>
