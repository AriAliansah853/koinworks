<?php
  $sqlUser = $this->db->query("SELECT * FROM ref_login WHERE rLoginId = '".$this->session->userdata('userId')."' ");
  $row = $sqlUser->row_array();
  if($sqlUser->num_rows() > 0){
    $data = array(
      'id'            => $row['rLoginId'],
      'username'      => $row['rLoginUser'],
      // 'sipUser'    => $row['sipUser'],
    );
  }else{
    $data = array(
      'id'            => $id,
      'username'      => '',
      // 'sipUser'    => '',
    );
  }
?>
<script>
  var id = '<?php echo $row['rLoginId']; ?>';
  var password = '<?php echo $row['rLoginPassword']; ?>';
  console.log(password);
  $(function(){
    //on keypress 
    $('#passBaru').keyup(function(){

      var inputpassword = $(this).val();
      var jqxhr = $.getJSON( "<?php echo base_url('user/get_password'); ?>?password="+inputpassword+"&id="+id, function() {})
      .done(function(data) {
        console.log(data);
        if(data == true) {
          $('#statusOldpass').val(1);
          $('.errorPassLama').text('Password sesuai').css('color', '#02d124');
          $('#cekPass').val(data);
        } else {
          $('#statusOldpass').val(0);
          $('.errorPassLama').text('Password tidak sesuai').css('color','#ce0f02');
          $('#cekPass').val(data);
        }
      })
      .fail(function() {
        $('.errorPassLama').text('Password tidak sesuai').css('color','#ce0f02');
      });
    });

    $('#pass').keyup(function(){
      var confpass = $('#confpass').val();
      var cekPass = $('#cekPass').val();
      var pass = $(this).val();
      if(pass == confpass) {
        $('#confPassStatus').val(1);
        $('.error').text('Password sesuai').css('color', '#02d124');
      }else{
        $('#confPassStatus').val(0);
        $('.error').text('Password tidak sesuai').css('color','#ce0f02');
      }
    });

    $('#confpass').keyup(function(){
      var pass = $('#pass').val();
      var cekPass = $('#cekPass').val();
      var confpass = $(this).val();
      if(pass == confpass) {
        $('#confPassStatus').val(1);
        $('.error').text('Password sesuai').css('color', '#02d124');
      }else{
        $('#confPassStatus').val(0);
        $('.error').text('Password tidak sesuai').css('color','#ce0f02');
      }
    });
    
    $('#form').submit(function(){   
      var pass = $('#pass').val();
      var confpass = $('#confpass').val();
      var cekPass = $('#cekPass').val();
      
    });

    $('#btnSubmit').on('click',function(){
      var statusOldpass = $('#statusOldpass').val();
      var confPassStatus = $('#confPassStatus').val();
      if (statusOldpass == 1 && confPassStatus == 1) {
        $('#form').submit();
      } else {
        $("#form").submit(function(e){
              e.preventDefault();
              alert('Password lama salah atau confirmasi password baru tidak match');
              window.location.reload();
          });
      }
    });
  });
</script>
<script>
  $(document).ready(function() {
    $('#submit').click(function(event){
    
      data = $('.password').val();
      var len = data.length;
      
      if(len < 1) {
        alert("Password cannot be blank");
        // Prevent form submission
        event.preventDefault();
      }
      
      if($('.password').val() != $('.confpass').val()) {
        alert("Password and Confirm Password don't match");
        // Prevent form submission
        event.preventDefault();
      }
      
    });
  });
</script>
<!-- Navbar -->
  <nav class="main-header navbar navbar-expand bg-white navbar-light border-bottom">
    <!-- Left navbar links -->
    <ul class="navbar-nav">
      <li class="nav-item">
        <a class="nav-link" data-widget="pushmenu" href="#"><i class="fa fa-bars"></i></a>
      </li>
    </ul>

    <!-- SEARCH FORM -->
    <!-- <form class="form-inline ml-3">
      <div class="input-group input-group-sm">
        <input class="form-control form-control-navbar" type="search" placeholder="Search" aria-label="Search">
        <div class="input-group-append">
          <button class="btn btn-navbar" type="submit">
            <i class="fa fa-search"></i>
          </button>
        </div>
      </div>
    </form> -->

    <!-- Right navbar links -->
    <!-- <ul class="navbar-nav ml-auto">
      <li><button class="btn btn-default btn-flat" data-toggle="modal" data-target="#modalGantiPassword">Ganti Password</button></li>
    </ul> -->
  </nav>
<!-- /.navbar -->


<div class="modal fade bd-example-modal-md" id="modalGantiPassword">
  <div class="modal-dialog modal-md">
      <div class="modal-content">
        <div class="modal-header">
            <h4 class="modal-title">Ganti Password</h4>
        </div>
        <form role="form" method="POST" id="form" action="<?php echo base_url(); ?>user/edit_password">
          <input type="hidden" class="form-control" name="id" value="<?php echo $row['rLoginId']; ?>">
          <div class="modal-body">
            <div class="row">
              <div class="form-group col-12">
                <label>Username</label>
                <input type="text" name="username" value="<?php echo $this->session->userdata('name'); ?>"  class="form-control" disabled>                           
              </div>
            </div> 
            <div class="row">
              <div class="form-group col-12">
                <label>Password Lama</label>
                <input type="hidden" class="form-control" name="cekPass" id="cekPass">
                <input type="password" class="form-control" name="passBaru" id="passBaru" required>
                <span class="errorPassLama" style="color:red"></span><br/>                       
              </div>
            </div>
            <div class="row">
              <div class="form-group col-12">
                <label>Password</label>
                <input type="password" class="form-control" name="pass" id="pass" required>                          
              </div>
            </div>  
            <div class="row">
              <div class="form-group col-12">
                <label>Retype Password</label>
                <input type="password" class="form-control" name="password" id="confpass" required>
                <span class="error" style="color:red"></span><br/>                        
              </div>
            </div>                
          </div>
          <div class="modal-footer">
            <input type="hidden" id="confPassStatus" value="0">
            <input type="hidden" id="statusOldpass" value="0">
            <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
            <button type="submit" id="btnSubmit" name="submit" class="btn btn-primary" >Save changes</button>
          </div>
        </form>
      </div>     
  </div> 
</div>