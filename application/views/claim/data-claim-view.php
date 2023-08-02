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
							<form id="formProfilePage" action="<?php echo base_url();?>data/all/search" method="POST" >
                            
					            <div class="card-header">
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
							                    <option value="1">Active</option>
							                    <option value="0">In Active</option>
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
											<div class=" form-group col-md-12">
												<input type="text" name="endEffectiveTime" id="datepicker2" class="form-control" placeholder="End Effective Time">
											</div>
										</div>
										
									 </div>
									 <div class="row">
										<div class="col-lg-3 text-center" >
											<button type="submit" class="btn btn-block btn-danger">Search</button>
										</div>	
									 </div>
					            </div>
					           </form>
					            <div class="card-body">
					              	<table id="example2" class="table table-bordered table-striped table-responsive display nowrap " width="100%">   
					                	<thead>
										<tr>
							                  	<th>Action</th>
							                  	<th>#</th>
							                  	<th>Claim Status</th>
							                  	<th>Trx Id</th>
							                  	<th>Loan Type</th>
							                  	<th>Customer Name</th>
							                  	<th>Occupation</th>
							                  	<th>City</th>
							                  	<th>ZipCode</th>
							                  	<th>Phone</th>
							                  	<th>Email</th>
							                  	<th>Sum Insured</th>
							                  	<th>InstallMent Period</th>
							                  	<th>Premium</th>
							                  	<th>Policy Effective Date</th>
							                  	<th>Policy Number</th>
							                  	<th>Policy Url</th>
							                  	<th>Insert Date</th>
							                  	<th>Update Date</th>
							                  	
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
	$(document).ready(function() {
			var timerExcel=<?php echo date('YmdHis');?>;
		  $('#example2').DataTable({
			  "paging": true,
			dom: 'Bfrtip',
			buttons: [{
			  extend: 'pdf',
			  title: 'Customized PDF Title',
			  filename: 'customized_pdf_file_name'
			}, {
			  extend: 'excel',
			  title: 'File Excel',
			  filename: 'Policy Data Customer' + timerExcel,
			  exportOptions: {
				 columns: [1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,21,22,23,24,25,26,27,29 ]
				}
				
			}, {
			  extend: 'csv',
			  filename: 'customized_csv_file_name'
			}]
		  });
		  $('.buttons-pdf').hide();
		  $('.buttons-csv').hide();
		  $('.buttons-excel').html('Download Excel');
		  $('#example_filter').hide();

		  $('.glyphicon glyphicon-chevron-up').attr('class', 'fa fa-chevron-up');
		});
	</script>
</body>
</html>
