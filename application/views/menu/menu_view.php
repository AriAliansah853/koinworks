
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
			              		<li class="breadcrumb-item active">Menu</li>
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
					              	<h3 class="card-title">Menu</h3>
					              	<div class="card-tools">
					              	 	<a href="<?php echo base_url(); ?>menu/add"><button type="button" class="btn btn-primary btn-sm"><i class="fa fa-plus"></i> Add Menu</button></a>
					              	</div>					             
					            </div>

					            <div class="card-body">
					              	<table id="example1" class="table table-bordered table-striped">
						                <thead>
							                <tr>
							                  	<th>No.</th>
							                  	<th>URL</th>
							                  	<th>Name</th>
							                  	<th>Deskripsi</th>
							                  	<th>Status</th>
							                  	<th style="text-align: center;">Action</th>
							                </tr>
						                </thead>
						                <tbody>
						                	<?php 
						                		$no = 1;
						                		foreach ($listMenu->result_array() as $valueMenu) {
						                			if ($valueMenu['rMenuVisible'] == 1) {
						                				$visible = 'TRUE';
						                			} else {
						                				$visible = 'FALSE';
						                			}
						                	?>
							                	<tr>
							                		<td><?php echo $no; ?></td>
							                		<td><?php echo $valueMenu['rMenuUrl']; ?></td>
							                		<td><?php echo $valueMenu['rMenuName']; ?></td>
							                		<td><?php echo $valueMenu['rMenuDesc']; ?></td>
							                		<td><?php echo $visible; ?></td>
							                		<td style="text-align: center;">
							                			<a href="<?php echo base_url(); ?>menu/edit/<?php echo $valueMenu['rMenuId']; ?>"><button type="button" class="btn btn-success btn-sm"><i class="fa fa-edit"></i> Edit</button></a>
							                			<a href="<?php echo base_url(); ?>menu/menu_action/delete/<?php echo $valueMenu['rMenuId']; ?>"><button type="button" class="btn btn-danger btn-sm"><i class="fa fa-trash"></i> Delete</button></a>
							                		</td>
							                	</tr>
							                <?php $no++;} ?>
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
	<script type="text/javascript">
	    var url="<?php echo base_url();?>";
	    function delete(id){
	    	console.log(id);
	       	var r = confirm("Do you want to delete this?")
	        if (r == true) 
	          	window.location = url+"menu/menu_action/delete/"+id;
	        else
	          	return false;
	    } 
	</script>
</body>
</html>
