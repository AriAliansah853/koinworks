
<!DOCTYPE html>
<html>
<head>
  	<meta charset="utf-8">
  	<meta http-equiv="X-UA-Compatible" content="IE=edge">
  	<title>Crowdo | User</title>
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
		      	
			        <div class="row">
						<div class="col-md-6">
						<div class="card card-danger">
				              	<div class="card-header">
				                	<h3 class="card-title">Tambah User</h3>
				              	</div>
				              
				              	<form role="form" action="<?php echo base_url();?>user/update_user" method="post" >
					                <div class="card-body">
					                  	<div class="form-group">
						                    <label>Source</label>
						                    <select class="form-control select2" style="width: 100%;" name="source" required>
							                    <option value="">Pilih Source</option>
												<?php 
													$sqlSource=$this->db->where('rSourceVisible',1)->get('ref_source');
													foreach($sqlSource->result_array() as $rrSource){
														if($rrSource['rSourceId']==$source){
															$select='selected="selected"';
														}else{
															$select='';
														}
												?>
												<option value="<?php echo $rrSource['rSourceId'];?>"<?php echo $select;?>><?php echo $rrSource['rSourceBrandName'];?></option>
												<?php }?>
							                 </select>
											  <input type="hidden" name="Userid" value="<?php echo $idUser; ?>"class="form-control">
					                  	</div>
					                  	<div class="form-group">
						                    <label>User</label>
						                    <input type="text" name="User" class="form-control" value="<?php echo $user;?>" placeholder="User">
					                  	</div>
					                  	<div class="form-group">
						                    <label>Username</label>
						                    <input type="text" name="Username" class="form-control" value="<?php echo $name;?>" placeholder="Username">
					                  	</div>
					                  	<div class="form-group">
						                    <label>Password</label>
						                    <input type="password" name="Password" class="form-control" value=""placeholder="Password" required>
					                  	</div>
					                  	<div class="form-group">
						                    <label>Visible</label>
						                    <select class="form-control" name="visible">
						                    	<option value="1" <?php if($visible==1){echo'selected="selected"';}else{echo'';}?>>TRUE</option>
						                    	<option value="0" <?php if($visible==0){echo'selected="selected"';}else{echo'';}?>>FALSE</option>
						                    </select>
					                  	</div>
					                </div>

					                <div class="card-footer">
					                  	<button type="submit" class="btn btn-danger">Submit</button>
					                </div>
					            </form>
				            </div>
						</div>
						<div class="col-md-6">
						<div class="card card-default">
				              	<div class="card-header">
				                	<h3 class="card-title">Akse Menu User</h3>
				              	</div>
				              
				              	<form role="form" action="<?php echo base_url();?>user/save_user" method="post" >
					                <div class="card-body">
					                  	
					                </div>

					                <div class="card-footer">
										
					                  	<div class="card-body table-responsive">
										<div class="card-body"><a href="javascript:void(0)" onclick="add_menu(<?php echo $idUser; ?>)"><button type="button" class="btn btn-success">Tambah</button></a>
										</div>
											<table class="table table-bordered  " width="100%">
												<thead>
													<tr align="center">
														<th width="10%">No.</th>
														<th>Akses Menu</th>
														<th style="text-align: center;">Action</th>
													</tr>
												</thead>
												<tbody>
												<?php 
												
													$sqlCek=$this->db->from('map_menu_user')->join('ref_menu','mMenuUserMenuId=rMenuId')->where('mMenuUserLoginId',$idUser)->get();
													$no=1;
													foreach($sqlCek->result_array() as $rrMenu){
												?>
												<tr align="center">
													<td><?php echo $no;?></th>
													<td><?php echo $rrMenu['rMenuName'];?></th>
													<td><button type="button" class="btn btn-danger btn-sm" onclick="deleteData(<?php echo $rrMenu['mMenuUserId']?>)"><i class="fa fa-trash"></i> </button></th>
												</tr>		
													<?php $no++;}?>	
												</tbody>
											</table>
										</div>
					                </div>
					            </form>
				            </div>
						</div>
						<div class="col-md-12">

			        	</div>		        
		      		</div>
		      	
    		</section>

  		</div>

	  	<?php $this->load->view('include/include_footer'); ?>

	  	<aside class="control-sidebar control-sidebar-dark">

	  	</aside>
	</div>
		<div class="modal fade " id="modalDefault" >
          <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-header">
                
                <h4 class="modal-title">Log History</h4>
              </div>
              <div class="modal-body">
				
				<div class="form-group table-responsive">
						<div class="col-md-12 text-center">
							<form action="<?php echo base_url();?>user/save_menu" method="post" id="form" class="form-vertical">
						
						 <input type="hidden" name="idUser" class="form-control" id="idUser">
							<div class="form-group">
								<div class="col-md-12">
									<select name="menu" class="form-control" required>
										<option value="">Pilih Menu </option>
										<?php
											$sqlUser=$this->db->where('rMenuVisible',1)->get('ref_menu');
											foreach($sqlUser->result_array() as $rrUser){
										?><option value="<?php echo $rrUser['rMenuId'];?>"><?php echo $rrUser['rMenuName'];?></option>
											<?php }?>
									</select>
								</div>
							</div>
							<div class="form-group">
								<div class="col-md-12 text-left">
										<button type="submit" class="btn btn-success">Submit</button>
								</div>
							</div>
						</form>	
						</div>
					</div>
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-default pull-left" data-dismiss="modal">X</button>
              
              </div>
            </div>
            <!-- /.modal-content -->
          </div>
          <!-- /.modal-dialog -->
        </div>
	<?php $this->load->view('include/include_basejs'); ?>
	<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>

	<script>
	var urlAdd="<?php echo base_url('user/add');?>";
	var link="<?php echo base_url('user/edit_user/').''.$idUser;?>";
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
				window.location.replace(urlAdd); 
            });
			
			
        }else {
				
        }
		function add_menu(id){
			$('#idUser').val(id);	
			
				$('#modalDefault').modal('show');

		}
		function deleteData(id){
			if(confirm('Apa Ingin Hapus Data')){
					// ajax delete data to database
					$.ajax({
						url : "<?php echo site_url('user/deleteMenu')?>/"+id,
						type: "POST",
						dataType: "JSON",
						success: function(data)
						{
							if(data.type==1){
								 swal({
									title:data.msg,
									icon: "success",
								})
								window.location.replace(link);
							}else{
							 swal({
									title:data.msg,
									icon: "warning",
								})
								.then((Ok) => {
							   
								});
						}

						},
						error: function (jqXHR, textStatus, errorThrown)
						{
							  swal({
									title: 'Update Failed',
									icon: "danger",
								})
								.then((Ok) => {
							   
								});
						}
					});				
			}
	}
	</script>
</body>
</html>
