
<!DOCTYPE html>
<html>
<head>
  	<meta charset="utf-8">
  	<meta http-equiv="X-UA-Compatible" content="IE=edge">
  	<title>Pinduit | User</title>
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
			            	<h1 class="m-0 text-dark">User</h1>
			          	</div>
			          	<div class="col-sm-6">
			            	<ol class="breadcrumb float-sm-right">
			              		<li class="breadcrumb-item"><a href="#">Home</a></li>
			              		<li class="breadcrumb-item"><a href="#">User</a></li>
			              		<li class="breadcrumb-item active">Add</li>
			            	</ol>
			          	</div>
			        </div>
		     	 </div>
		    </div>

    		<section class="content">
		      	<div class="container-fluid">
			        <div class="row">

			          	<div class="col-md-12">

			            	<div class="card card-danger">
				              	<div class="card-header">
				                	<h3 class="card-title">Tambah User</h3>
				              	</div>
				              
				              	<form role="form" action="<?php echo base_url();?>user/save_user" method="post" >
					                <div class="card-body">
					                  	<div class="form-group">
						                    <label>Source</label>
						                    <select class="form-control select2" style="width: 100%;" name="source" required>
							                    <option value="">Pilih Source</option>
												<?php 
													$sqlSource=$this->db->where('rSourceVisible',1)->get('ref_source');
													foreach($sqlSource->result_array() as $rrSource){
												?>
												<option value="<?php echo $rrSource['rSourceId'];?>"><?php echo $rrSource['rSourceBrandName'];?></option>
												<?php }?>
							                 </select>
					                  	</div>
					                  	<div class="form-group">
						                    <label>User</label>
						                    <input type="text" name="User" class="form-control" placeholder="User">
					                  	</div>
					                  	<div class="form-group">
						                    <label>Username</label>
						                    <input type="text" name="Username" class="form-control" placeholder="Username">
					                  	</div>
					                  	<div class="form-group">
						                    <label>Password</label>
						                    <input type="password" name="Password" class="form-control" placeholder="Password">
					                  	</div>
					                  	<div class="form-group">
						                    <label>Visible</label>
						                    <select class="form-control" name="visible">
						                    	<option value="1">TRUE</option>
						                    	<option value="0">FALSE</option>
						                    </select>
					                  	</div>
					                </div>

					                <div class="card-footer">
					                  	<button type="submit" class="btn btn-danger">Submit</button>
					                </div>
					            </form>
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
	<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>

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
		
		var cekError=<?php echo $errorData?>;
		 if(cekError == 0){
            swal({
                title: 'Username Sudah Ada',
                icon: "warning",
            })
            .then((Ok) => {
                $('#btnSubmit').text('Tambah User Gagal'); 
                $('#btnSubmit').attr('disabled',false);
				location='http://micro.fuse.co.id/crowdo/user/add'; 
            });
			
			
        }else {
				
        }

	</script>
</body>
</html>
