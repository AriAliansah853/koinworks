<!DOCTYPE html>
<html>
<head>
  	<meta charset="utf-8">
  	<meta http-equiv="X-UA-Compatible" content="IE=edge">
  	<title>Koinworks | Upload</title>
  	<!-- Tell the browser to be responsive to screen width -->
  	<meta name="viewport" content="width=device-width, initial-scale=1">
  	
  	<?php $this->load->view('include/include_basecss'); ?>

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
					            <div class="card-header">
					              <h3 class="card-title">History Upload</h3>
					            </div>

					            <div class="card-body">
					              	<table id="example2" class="table table-bordered table-striped">
						                <thead>
							                <tr>
							                  	<th>No.</th>
							                  	<th>Upload Code</th>
							                  	<th>Upload Time</th>
							                  	<th>Success Count</th>
							                  	<th>Failed Count</th>
							                  	<th>Existing Count</th>
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

	<script>
		$(document).ready(function(){  
			var timerExcel=<?php echo date('YmdHis');?>;
				var dataTable = $('#example2').DataTable({  
					"processing":true,  
					"serverSide":true,  
					"order":[],  
					"ajax":{  
						url:"<?php echo base_url() . 'serverside/data-upload/'; ?>",  
						type:"POST"  
					},
					 
					"columnDefs":[  
						{  
							"targets":[0],  
							"orderable":false,  
						},
						
					],
					  
				}); 			
		}); 

	 
	</script>
</body>
</html>
