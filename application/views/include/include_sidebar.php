<?php 
	$sidebarStatus = $sidebar;
  	$sidebarDashboard = '';
  	$sidebarUpload = '';
  	$sidebarUploadData = '';
  	$sidebarUploadHIstory = '';
  	$sidebarData = '';
  	$sidebarReferensi = '';
  	$sidebarUser = '';
  	$sidebarMenu = '';
  	$sidebarSource = '';
  	$openMenuUpload = '';
  	$openMenuUser = '';
  	$sidebarPenagihan = '';
  	$sidebarPenCrowdo = '';
	$sidebarpenPinduit = '';
	$openMenuPenagihan = '';
	$sidebarClaim='';

  	if ($sidebarStatus == 'dashboard') {
		$sidebarDashboard = 'active';
	} else if ($sidebarStatus == 'upload_data') {
		$sidebarUpload = 'active';
    	$sidebarUploadData = 'active';
	} else if ($sidebarStatus == 'upload_history') {
	    $sidebarUpload = 'active';
	    $sidebarUploadHIstory = 'active';
	} else if ($sidebarStatus == 'Policy') {
	    $sidebarData = 'active';
	} else if ($sidebarStatus == 'user') {
	    $sidebarReferensi = 'active';
	    $sidebarUser = 'active';
	} else if ($sidebarStatus == 'menu') {
	    $sidebarReferensi = 'active';
	    $sidebarMenu = 'active';
	} else if ($sidebarStatus == 'source') {
	    $sidebarReferensi = 'active';
	    $sidebarSource = 'active';
	}else if ($sidebarStatus == 'penagihan_crowdo') {
	    $sidebarPenagihan = 'active';
	    $sidebarPenCrowdo = 'active';
	}else if ($sidebarStatus == 'penagihan_pinduit') {
	    $sidebarPenagihan = 'active';
	    $sidebarpenPinduit = 'active';
	}else if ($sidebarStatus == 'Claim') {
		$sidebarClaim = 'active';
	} else {}

	if ($sidebarUpload == 'active') {
	    $openMenuUpload = 'menu-open';
	}

	if ($sidebarPenagihan == 'active') {
	    $openMenuPenagihan = 'menu-open';
	}

	if ($sidebarReferensi == 'active') {
	    $openMenuUser = 'menu-open';
	}
	
	
	
?>


<aside class="main-sidebar sidebar-dark-danger elevation-4">

    <a href="<?php echo base_url();?>" class="brand-link">
      	<img src="<?php echo base_url();?>assets/img/logo-kion.png" alt="AdminLTE Logo" class="brand-image img-circle elevation-3"
        	style="opacity: .8">
      	<span class="brand-text font-weight-light">Koinworks</span>
    </a>

    <div class="sidebar">

      	<div class="user-panel mt-3 pb-3 mb-3 d-flex">
        	<div class="image">
          		<img src="<?php echo base_url(); ?>assets/alte_theme/dist/img/user2-160x160.jpg" class="img-circle elevation-2" alt="User Image">
        	</div>
	        <div class="info">
	          	<a href="#" class="d-block"><?php echo $this->session->userdata('username'); ?></a>
	        </div>
      	</div>

      	<nav class="mt-2">
        	<ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">

          	<li class="nav-item has-treeview">
            	<a href="<?php echo base_url(); ?>dashboard" class="nav-link <?php echo $sidebarDashboard; ?>">
              		<i class="nav-icon fa fa-dashboard"></i>
	              	<p>
	                	Dashboard
	              	</p>
            	</a>
          	</li>
			<?php $sqlCekUpload=$this->db->from('map_menu_user')
					->join('ref_menu','mMenuUserMenuId=rMenuId')
					->where('mMenuUserLoginId',$this->session->userdata('userId'))
					->where('rMenuName','Upload')
					->get();
				if($sqlCekUpload->num_rows()>0){
					
			?>	
          	<li class="nav-item has-treeview <?php echo $openMenuUpload; ?>">
            	<a href="#" class="nav-link <?php echo $sidebarUpload; ?>">
              		<i class="nav-icon fa fa-upload"></i>
              		<p>
                		Upload
                		<i class="right fa fa-angle-left"></i>
              		</p>
            	</a>
            	<ul class="nav nav-treeview">
              		<li class="nav-item">
                		<a href="<?php echo base_url(); ?>upload" class="nav-link <?php echo $sidebarUploadData; ?>">
	                  		<i class="fa fa-circle-o nav-icon"></i>
	                  		<p>Upload Data</p>
                		</a>
              		</li>
              		<li class="nav-item">
                		<a href="<?php echo base_url(); ?>upload/history_upload" class="nav-link <?php echo $sidebarUploadHIstory; ?>">
		                  	<i class="fa fa-circle-o nav-icon"></i>
		                  	<p>History Upload</p>
                		</a>
              		</li>
            	</ul>
          	</li>
			<?php }else{}
			$sqlCekData=$this->db->from('map_menu_user')
					->join('ref_menu','mMenuUserMenuId=rMenuId')
					->where('mMenuUserLoginId',$this->session->userdata('userId'))
					->where('rMenuName','Data')
					->get();
				if($sqlCekData->num_rows()>0){
			
			
			?>
          	<li class="nav-item">
	            <a href="<?php echo base_url(); ?>data" class="nav-link <?php echo $sidebarData; ?>">
	              	<i class="nav-icon fa fa-files-o"></i>
	              	<p>
	                	Policy List
	              	</p>
	            </a>
          	</li>
			<!-- <li class="nav-item">
	            <a href="<?php echo base_url(); ?>claim" class="nav-link <?php echo $sidebarClaim; ?>">
	              	<i class="nav-icon fa fa-database"></i>
	              	<p>
	                	Claim
	              	</p>
	            </a>
          	</li>   -->
			<?php }else{}
			
			$sqlCekUpload=$this->db->from('map_menu_user')
					->join('ref_menu','mMenuUserMenuId=rMenuId')
					->where('mMenuUserLoginId',$this->session->userdata('userId'))
					->where('rMenuName','Penagihan')
					->get();
				if($sqlCekUpload->num_rows()>0){
					
			?>	
          	<!-- <li class="nav-item has-treeview <?php echo $openMenuPenagihan; ?>">
            	<a href="#" class="nav-link <?php echo $sidebarPenagihan; ?>">
              		<i class="nav-icon fa fa-upload"></i>
              		<p>
                		Penagihan
                		<i class="right fa fa-angle-left"></i>
              		</p>
            	</a>
            	<ul class="nav nav-treeview">
              		<li class="nav-item">
                		<a href="<?php echo base_url(); ?>finance_data?req=crowdo" class="nav-link <?php echo $sidebarPenCrowdo; ?>">
	                  		<i class="fa fa-circle-o nav-icon"></i>
	                  		<p>Crowdo</p>
                		</a>
              		</li>
              		<li class="nav-item">
                		<a href="<?php echo base_url(); ?>finance_data?req=pinduit" class="nav-link <?php echo $sidebarpenPinduit; ?>">
		                  	<i class="fa fa-circle-o nav-icon"></i>
		                  	<p>Pinduit</p>
                		</a>
              		</li>
            	</ul>
          	</li> -->
			
			<?php }else{}
			
			$sqlCekReferensi=$this->db->from('map_menu_user')
					->join('ref_menu','mMenuUserMenuId=rMenuId')
					->where('mMenuUserLoginId',$this->session->userdata('userId'))
					->where('rMenuName','Referensi')
					->get();
				if($sqlCekReferensi->num_rows()>0){
			?>
          	<!-- <li class="nav-item has-treeview <?php echo $openMenuUser; ?>">
            	<a href="#" class="nav-link <?php echo $sidebarReferensi; ?>">
              		<i class="nav-icon fa fa-paperclip"></i>
              		<p>
                		Referensi
                		<i class="right fa fa-angle-left"></i>
              		</p>
            	</a>
            	<ul class="nav nav-treeview">
              		<li class="nav-item">
                		<a href="<?php echo base_url(); ?>user" class="nav-link <?php echo $sidebarUser; ?>">
	                  		<i class="fa fa-circle-o nav-icon"></i>
	                  		<p>User</p>
                		</a>
              		</li>
            	</ul>
            	<ul class="nav nav-treeview">
              		<li class="nav-item">
                		<a href="<?php echo base_url(); ?>menu" class="nav-link <?php echo $sidebarMenu; ?>">
	                  		<i class="fa fa-circle-o nav-icon"></i>
	                  		<p>Menu</p>
                		</a>
              		</li>
            	</ul>
            	<ul class="nav nav-treeview">
              		<li class="nav-item">
                		<a href="<?php echo base_url(); ?>source" class="nav-link <?php echo $sidebarSource; ?>">
	                  		<i class="fa fa-circle-o nav-icon"></i>
	                  		<p>Source</p>
                		</a>
              		</li>
            	</ul>
          	</li> -->
				<?php }else{}?>
          	<li class="nav-item">
            	<a href="javascript:void(0)" class="nav-link btn-logout">
              		<i class="nav-icon fa fa-arrow-circle-left"></i>
              		<p>Sign Out
              		</p>
            	</a>
          	</li>
        </ul>
    </div>
</aside>