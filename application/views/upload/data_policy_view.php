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
												Number Of Policies : <?php echo $policy;?>
											</div>
										</div>
										<div class="col-lg-4 text-center">
											<div class="form-group  col-md-12" id="border">
												Loan :	<?php echo $loan;?> 
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
												<input type="text" name="policyNumber" class="form-control" placeholder="Policy Number">
											</div>
											<div class=" form-group col-md-12">
												<input type="text" name="startUploadTime" id="datepicker3" class="form-control" placeholder="Start Upload Time">
											</div>
											<div class=" form-group col-md-12">
												
											<select class="form-control " style="width: 100%;" name="policyStatus">
							                    <option value="">Policy Status</option>
							                    <option value="1">Process</option>
							                    <option value="2">Active</option>
							                    <option value="3">Non Active</option>
							                </select>
											</div>
										</div>
										<div class="col-lg-3 text-center">
											<div class=" form-group col-md-12">
												<input type="text" name="emailAddress" class="form-control" placeholder="Email Address">
											</div>
											
											<div class=" form-group col-md-12">
												<input type="text" name="endUploadTime" id="datepicker4" class="form-control" placeholder=" End Upload Time">
											</div>
											<div class="col-md-12 text-center" >
												<button type="button" class="btn btn-block btn-danger btn-search">Search</button>
											</div>	
										</div>
										<div class="col-lg-3 text-center">
											
											<div class="form-group col-md-12">
												<input type="text" name="insuredName" class="form-control" placeholder="Insured Name">
											</div>
											<div class=" form-group col-md-12">
												<input type="text" name="startEffectiveTime" id="datepicker" class="form-control" placeholder="Start Effective Time">
											</div>
										</div>
										<div class="col-lg-3 text-center">
											
											<div class="form-group col-md-12">
												<input type="text" name="mobileNumber" class="form-control" placeholder="Mobile Number">
											</div>
											<!-- <div class=" form-group col-md-12">
												<input type="text" name="endEffectiveTime" id="datepicker2" class="form-control" placeholder="End Effective Time">
											</div> -->
										</div>
										
									 </div>
									 
					            </div>
					           </form>
					            <div class="card-body">
					              	<table id="example2" class="table table-bordered table-striped table-responsive display nowrap " width="100%">   
					                	<thead>
						                	<tr>
							                  	<th>Action</th>
							                  	<th></th>
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
												<th>Lama Pinjaman (dalam Hari)</th>
												<th>Insert Date</th>
							                  	<th>Policy Number</th>
							                  	<th>Policy Url</th>
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
							<div class="col-md-12 text-center">
								<table class="table table-striped table-bordered table-hover" id="details" width="100%">
									<thead>
										<tr style="text-align:center;" align="center">
											<th>No.</th>
											<th width="20%">Log Type</th>
											<th width="40%">Short Desc</th>
											<th>Time</th>
											<th>User </th>
										</tr>
									</thead>
									<tbody id="isiDetail" style="font-size:10px;">
									</tbody> 
								</table>
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
				},error: function (jqXHR, textStatus, errorThrown){
					
				}
			});
		});
		function loghistory(id,idtemp){
	
						
				$.ajax({
					url : "<?php echo site_url('data/logHistory')?>/" + id +"/" + idtemp,
					type: "GET",
					dataType: "JSON",
					success: function(sql){
						$('#modalDefault').modal('show');
						console.log(sql);
						var tr;
							var i=sql.length;
								$('#isiDetail').html('');
								var key=1;
							$.each(sql, function(k, v) {
							tr = $("<tr>");
							tr.append("<td>" + key+ "</td>");
							tr.append("<td>" + v.histLogType + "</td>");
								tr.append("<td>" + v.histLogShortDesc + "</td>");
								tr.append("<td>" + v.histLogDatetime + "</td>");
							tr.append("<td>" + v.histLogLoginUser + "</td>");
								//th=$("");
								
							$("#isiDetail").append(tr);
							key++;
							});
					},
					error: function (jqXHR, textStatus, errorThrown){
							alert('Data Error');
					}
				});

			} 
	</script>
</body>
</html>
