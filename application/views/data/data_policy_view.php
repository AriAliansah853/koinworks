<!DOCTYPE html>
<html>
<head>
  	<meta charset="utf-8">
  	<meta http-equiv="X-UA-Compatible" content="IE=edge">
  	<title>Policy List</title>
  	<!-- Tell the browser to be responsive to screen width -->
  	<meta name="viewport" content="width=device-width, initial-scale=1">
  	
  	<?php $this->load->view('include/include_basecss'); ?>
	<style>
		#border{
			border:1px solid #ccc;
			background:#DC143C;
			color:white;
			font-size:20px;
			
			
		}
		
		#visible{
			
			background:#fff;
			color:white;
			font-size:20px;
		}
	</style>
	<link href="<?php echo base_url(); ?>assets/tablesDinamis/css/buttons.dataTables.css" rel="stylesheet" />
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
		            		<h1 class="m-0 text-dark">History Upload</h1>
		          		</div>
		          		<div class="col-sm-6">
		            		<ol class="breadcrumb float-sm-right">
		              			<li class="breadcrumb-item"><a href="#">Home</a></li>
		              			<li class="breadcrumb-item active">History Upload</li>
		            		</ol>
		          		</div>
		        	</div>
		      	</div>
		    </div>

    		<section class="content">
		      	<div class="container-fluid">
			        <div class="row">
						
			          	<div class="col-md-12">

			            	<div class="card">
							<form id="formProfilePage" action="#" method="POST" enctype="multipart/form-data"> 
                            
					            <div class="card-header">
									<div class="row">
										<div class="col-lg-4 text-center" >
											<div class="form-group col-md-12 text-center" id="border">
												Number Of Policies : <span id="premium-start-from1"><?php echo $policy;?></span>
											</div>
										</div>
										<div class="col-lg-4 text-center">
											<div class="form-group  col-md-12" id="border">
												Loan : <span id="premium-start-from2"><?php echo $loan;?></span>
											</div>
										</div>
										<div class="col-lg-4 text-center">
											<div class="form-group  col-md-12" id="border">
												Premium : <span id="premium-start-from"><?php echo $premium;?></span>
											</div>
										</div>
										<!-- <div class="col-lg-4 text-center">
											<div class="form-group  col-md-12" id="border">
												Credit Amount : <?php  echo $kreditamount;?>
											</div>
											
										</div> -->
									 </div>
					              	 <div class="row">
									   
										<div class="col-lg-3" >
											<div class="form-group col-md-12">
												<input type="text" name="policyNumber" class="form-control" placeholder="Policy Number" autocomplete="off">
											</div>
											<div class="form-group col-md-12">
												<input type="text" name="debitor_name" class="form-control" placeholder="Debitor Name">
											</div>
											<div class=" form-group col-md-12">
												<input type="text" name="startUploadTime" id="datepicker3" class="form-control" placeholder="Start Upload Time" autocomplete="off">
											</div>
											<div class=" form-group col-md-12">
												<input type="text" name="startInception" id="datepicker4" class="form-control" placeholder=" Start Inception Date" autocomplete="off">
											</div>
											
											
										</div>
										
										
										<div class="col-lg-3 text-center">
											<div class=" form-group col-md-12">
												<input type="text" name="emailAddress" class="form-control" placeholder="Creditor Email Address" autocomplete="off">
											</div>
											<div class="form-group col-md-12">
												<input type="text" name="debitor_ktp" class="form-control" placeholder="Debitor KTP">
											</div>
											
											<div class=" form-group col-md-12">
												<input type="text" name="endUploadTime" id="datepicker" class="form-control" placeholder=" End Upload Time" autocomplete="off">
											</div>
											<div class=" form-group col-md-12">
												<input type="text" name="endInception" id="datepicker2" class="form-control" placeholder=" End Inception Date" autocomplete="off">
											</div>	
										</div>
										<div class="col-lg-3 text-center">
											
											<div class="form-group col-md-12">
												<input type="text" name="insuredName" class="form-control" placeholder="Creditor Name" autocomplete="off">
											</div>
											<div class="form-group col-md-12">
												<input type="text" name="debitor_email" class="form-control" placeholder="Debitor Email">
											</div>
											<div class=" form-group col-md-12">
												
											<select class="form-control " style="width: 100%;" name="policyStatus">
							                    <option value="">Policy Status</option>
							                    <option value="1">Process</option>
							                    <option value="2">Active</option>
							                    <option value="4">Failed</option>
							                </select>
											</div>
											<div class="col-md-12 text-center" >
												<button type="button" class="btn btn-block btn-danger btn-search">Search</button>
											</div>
											<!-- <div class=" form-group col-md-12">
												<input type="text" name="startEffectiveTime" id="datepicker" class="form-control" placeholder="Inception Date">
											</div> -->
										</div>
										<div class="col-lg-3 text-center">
											
											<div class="form-group col-md-12">
												<input type="text" name="mobileNumber" class="form-control" placeholder="Creditor Mobile Number">
											</div>
											<div class="form-group col-md-12">
												<input type="text" name="debitor_id" class="form-control" placeholder="Debitor ID">
											</div>
											<div class="form-group col-md-12">
												<input type="text" name="trxId" class="form-control" placeholder="TransactionID">
											</div>
											
											<!-- <div class=" form-group col-md-12">
												<input type="text" name="endEffectiveTime" id="datepicker2" class="form-control" placeholder="End Effective Time">
											</div> -->
										</div>
										
									 </div>
									 
					            </div>
					           </form>
					            <div class="card-body">
									<div class="mb-3">
										<button class="btn btn-success btn-sm btn-round btn-export">Download Excel</button>&nbsp;
										<button class="btn btn-info btn-sm btn-round btn-reload"><span class="fa fa-refresh"></span></button>
					              	</div>
									<table id="example2" class="table table-bordered table-striped table-responsive display nowrap " width="100%">   
					                	<thead>
						                	<tr>
							                  	<th>Action</th>
							                  	<th></th>
												<th>No Ref/ TransactionID</th>
												<th>Code Upload</th>
												<th>Creditor Name</th>
												<th>Creditor Date of Birth</th>
												<th>Creditor Number ID</th>
												<th>Address Creditor</th>
												<th>City Creditor</th>
												<th>Postal Code Creditor</th>
												<th>Creditor Phone Number</th>
												<th>Creditor Email</th>
												<th>Debtor ID</th>
												<th>Debtor Name</th>
												<th>Debtor Date of Birth</th>
												<th>Debtor Number ID</th>
												<th>Debtor Address</th>
												<th>City Debtor</th>
												<th>Postal Code Debtor</th>
												<th>Debtor Phone Number</th>
												<th>Email Debtor</th>
												<th>Loan</th>
												<th>Package</th>
												<th>InceptionDate</th>
												<th>Loan Period (in months)</th>
												<th>Insert Date</th>
							                  	<th>Policy Number</th>
							                  	<th>Policy Url</th>
												<th>Premium</th>
							                  </tr>
						                </thead>
										<tbody>
										
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
		  <div class="modal fade bd-example-modal-lg" id="modalDefault" >
	        <div class="modal-dialog modal-lg">
	            <div class="modal-content">
		            <div class="modal-header">		                
		                <h4 class="modal-title">Log History</h4>
		            </div>
		            <div class="modal-body">
						
						<div class="form-group table-responsive">
							<div class="col-md-12 text-center" id="dataDet">
								
							</div>
						</div>
		            </div>
		            <div class="modal-footer">
		                <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
		              
		            </div>
	            </div>
	            <!-- /.modal-content -->
	        </div>
          <!-- /.modal-dialog -->
        </div>
	</div>
	
	<?php $this->load->view('include/include_basejs'); ?>

	<script src="<?php echo base_url(); ?>assets/tablesDinamis/js/jszip.min.js"></script>
	<script src="<?php echo base_url(); ?>assets/tablesDinamis/js/dataTables.buttons.min.js"></script>
	<script src="<?php echo base_url(); ?>assets/tablesDinamis/js/pdfmake.min.js"></script>
	<script src="<?php echo base_url(); ?>assets/tablesDinamis/js/vfs_fonts.js"></script>
	<script src="<?php echo base_url(); ?>assets/tablesDinamis/js/buttons.html5.min.js"></script>
	<style>
		
		.dt-buttons .buttons-excel{
			background:#228B22;
			color:white;
		}
		
	</style>

	<script> 
        $(document).ready(function() { 
                $(".bootstrap-timepicker-widget").find("i[class='glyphicon glyphicon-chevron-up']").removeClass('glyphicon glyphicon-chevron-up').addClass('fa fa-chevron-up'); 
                $(".bootstrap-timepicker-widget").find("i[class='glyphicon glyphicon-chevron-down']").removeClass('glyphicon glyphicon-chevron-down').addClass('fa fa-chevron-down'); 
        }); 
	</script> 
	
	<script>
		var timerExcel=<?php echo date('YmdHis');?>;
		var id='<?php echo $whereClause;?>';
		var linkIn='<?php echo base_url();?>';	
		var table;	
		$(document).ready(function(){  
			server(id);
			$('.buttons-excel').html('<span>Export to Excel</span>') 			
		}); 
		function server(data){
            //   alert('sssss');
			var tables = $('#example2').DataTable();
                tables.destroy();
                if(data==''){
                    urllink = linkIn +'serverside/data-policy';
                }else{
                    urllink = linkIn +'serverside/data-policy/'+data;
                }


                    table = $('#example2').DataTable({  
						"processing":true,  
						"serverSide":true,  
						"order":[],  
						"ajax":{  
							url:urllink,  
							type:"POST"  
						},  
						"columnDefs":[  
							{  
								"targets":[0],  
								"orderable":false,  
							},
							{  
								"targets":[1],  
								"orderable":false,  
							},
							{  
								"targets":[27],  
								"orderable":false,  
							}
						],
                         
				});
        }
		$('.btn-search').on('click',()=>{
			// alert('ccc');
			var formData = new FormData($('#formProfilePage')[0]);
			var url=linkIn +'data/get-data';
			$.ajax({
				url : url,
				type: "POST",
				data: formData,
				contentType: false,
				processData: false,
				dataType: "JSON",
				success: function(data){
					server(data.data);
					// console.log(data.data2);
					var reverse = data.data2.split('').reverse().join(''),
					ribuan  = reverse.match(/\d{1,3}/g);
					ribuan  = ribuan.join('.').split('').reverse().join('');
					console.log(ribuan);
					$('#premium-start-from').html(ribuan);

					var reverse1 = data.data4.split('').reverse().join(''),
					ribuan1  = reverse1.match(/\d{1,3}/g);
					ribuan1  = ribuan1.join('.').split('').reverse().join('');
					console.log(ribuan1);
					$('#premium-start-from1').html(ribuan1);

					var reverse2 = data.data3.split('').reverse().join(''),
					ribuan2  = reverse2.match(/\d{1,3}/g);
					ribuan2  = ribuan2.join('.').split('').reverse().join('');
					console.log(ribuan2);
					$('#premium-start-from2').html(ribuan2);
				},error: function (jqXHR, textStatus, errorThrown){
					
				}
			});
		});
		function update(id){
			var r = confirm("Apakah anda yakin untuk Hapus !?");
			if (r == true) {	
				$.ajax({
						url : "<?php echo site_url('data/update')?>/" + id,
						type: "GET",
						dataType: "JSON",
						success: function(sql){
							if(sql=='1'){
								swal({
									title: '',
									text: 'Delete Gagal',
									type: 'warning',
									showConfirmButton: true
								});
							}else{
								swal({
									title: '',
									text: 'Delete Sukses',
									type: 'success',
									showConfirmButton: true
								});
							}
							server('');
						},error:function(jqXHR,textStatus,errorThrown){

						}
				});
			}
			
		}
		function viewDetail(id){
			$('#dataDet').html('');
			$('#modalDefault').modal('show');
			$('.modal-title').html('Detail Data');
			$.ajax({
					url : "<?php echo site_url('data/detail')?>/" + id,
					type: "GET",
					dataType: "JSON",
					success: function(sql){
						$('#dataDet').append(sql.data);
					},error:function(jqXHR,textStatus,errorThrown){

					}
			});
		}
		function loghistory(id,idtemp){
			$('#dataDet').html('');
			$('.modal-title').html('History Log');
			$('#modalDefault').modal('show');
				$.ajax({
					url : "<?php echo site_url('data/logHistory')?>/" + id +"/" + idtemp,
					type: "GET",
					dataType: "JSON",
					success: function(sql){
						$('#dataDet').append(sql.data);
					},
					error: function (jqXHR, textStatus, errorThrown){
							alert('Data Error');
					}
				});

		} 
		$('.btn-export').on('click',function(){	
			var formData = new FormData($('#formProfilePage')[0]);
			var url=linkIn +'data/get-data';
			$.ajax({
                    url : url,
                    type: "POST",
                    data: formData,
                    contentType: false,
                    processData: false,
                    dataType: "JSON",
                    success: function(data){
                        // console.log(data.data);
                        location = linkIn + 'data/export/' + data.data;
                    },error: function (jqXHR, textStatus, errorThrown){
                    
					}
                });
			
		})
		$('.btn-reload').on('click',function(){	
			table.ajax.reload(null,false); //reload datatable ajax 
		});
            
	</script>
</body>
</html>
