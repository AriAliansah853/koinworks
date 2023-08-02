
<!DOCTYPE html>
<html>
<head>
  	<meta charset="utf-8">
  	<meta http-equiv="X-UA-Compatible" content="IE=edge">
  	<title>Pinduit | Menu</title>
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
			            	<h1 class="m-0 text-dark">Menu</h1>
			          	</div>
			          	<div class="col-sm-6">
			            	<ol class="breadcrumb float-sm-right">
			              		<li class="breadcrumb-item"><a href="#">Home</a></li>
			              		<li class="breadcrumb-item"><a href="#">Menu</a></li>
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
				                	<h3 class="card-title">Tambah Menu</h3>
				              	</div>
				              
				              	<form role="form" method="POST" action="<?php echo base_url(); ?>menu/menu_action/<?php echo $flag; ?>/<?php echo $id; ?>">
					                <div class="card-body">
					                  	<div class="form-group">
						                    <label>URL</label>
						                    <input type="text" name="url" class="form-control" placeholder="URL" value="<?php echo $url; ?>">
					                  	</div>
					                  	<div class="form-group">
						                    <label>Name</label>
						                    <input type="text" name="name" class="form-control" placeholder="Name" value="<?php echo $name; ?>">
					                  	</div>
					                  	<div class="form-group">
						                    <label>Deskripsi</label>
						                    <textarea class="form-control" rows="3" name="deskripsi"><?php echo $deskripsi; ?></textarea>
					                  	</div>
					                  	<div class="form-group">
						                    <label>Visible</label>
						                    <select class="form-control" name="visible">
						                    	<?php if ($visible == 1) { ?>
							                    	<option value="1" selected>TRUE</option>
							                    	<option value="0">FALSE</option>
							                    <?php } else { ?>
							                    	<option value="1">TRUE</option>
							                    	<option value="0" selected>FALSE</option>
							                    <?php } ?>
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
</body>
</html>
