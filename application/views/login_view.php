<!DOCTYPE html>
<html lang="en">
<head>
  	<title>Login &mdash; Koinworks</title>
  	
  	<?php $this->load->view('include/include_basecss'); ?>
  	
</head>
<body class="hold-transition login-page">
<div class="login-box">
  	<div class="login-logo">
    	<a href="<?php echo base_url(); ?>"><b>Koinworks</b></a>
  	</div>
  	<!-- /.login-logo -->
  	<div class="login-box-body">
    	<p class="login-box-msg">Please Input Your User and Password</p>

    	<form action="<?php echo base_url(); ?>auth/login" id="form-login" method="post">
	    	<div class="form-group has-feedback">
	        	<input type="text" autofocus name="username" class="form-control" placeholder="User">
	        	<span class="glyphicon glyphicon-envelope form-control-feedback"></span>
	      	</div>
	      	<div class="form-group has-feedback">
	        	<input type="password" name="password" class="form-control" placeholder="Password">
	        	<span class="glyphicon glyphicon-lock form-control-feedback"></span>
	      	</div>
	      	<div class="row">
	        	<div class="col-xs-12 col-md-12">
	          		<button type="button" class="btn btn-primary btn-block btn-flat btn-login">LOGIN</button>
	        	</div>
	      	</div>
	      	<br>
	      	<p class="login-box-msg">IP Kamu : <?php echo $ipAddress; ?></p>
	    </form>


  	</div>
  <!-- /.login-box-body -->
</div>
<!-- /.login-box -->

<?php $this->load->view('include/include_basejs'); ?>
<script src="<?php echo base_url();?>assets/page-js/js-query.min.js"></script>
</body>
</html>
