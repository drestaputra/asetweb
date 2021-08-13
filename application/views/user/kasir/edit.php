<!doctype html>
<html class="fixed">
<head>

  <!-- Basic -->
  <meta charset="UTF-8">
  <title>Edit Kasir | <?php echo function_lib::get_config_value('website_name'); ?></title>
  <meta name="keywords" content="Dashboard Admin - <?php echo function_lib::get_config_value('website_name'); ?>" />
  <meta name="description" content="<?php echo function_lib::get_config_value('website_seo'); ?>">
  <meta name="author" content="Drestaputra - Inolabs">

  <!-- Mobile Metas -->
  <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />

  <!-- Web Fonts  -->
  <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700,800|Shadows+Into+Light" rel="stylesheet" type="text/css">

  <!-- Vendor CSS -->
  <link rel="stylesheet" href="<?php echo base_url(); ?>assets/vendor/bootstrap/css/bootstrap.css" />

  <link rel="stylesheet" href="<?php echo base_url(); ?>assets/vendor/font-awesome/css/font-awesome.css" />
  <link rel="stylesheet" href="<?php echo base_url(); ?>assets/vendor/magnific-popup/magnific-popup.css" />
  <link rel="stylesheet" href="<?php echo base_url(); ?>assets/vendor/bootstrap-datepicker/css/datepicker3.css" />
  <link rel="stylesheet" href="<?php echo base_url(); ?>assets/vendor/select2/select2.css" />		

  <!-- Specific Page Vendor CSS -->
  <link rel="stylesheet" href="<?php echo base_url(); ?>assets/vendor/jquery-ui/css/ui-lightness/jquery-ui-1.10.4.custom.css" />
  <link rel="stylesheet" href="<?php echo base_url(); ?>assets/vendor/bootstrap-multiselect/bootstrap-multiselect.css" />
  <link rel="stylesheet" href="<?php echo base_url(); ?>assets/vendor/morris/morris.css" />

  <!-- Theme CSS -->
  <link rel="stylesheet" href="<?php echo base_url(); ?>assets/stylesheets/theme.css" />

  <!-- Skin CSS -->
  <link rel="stylesheet" href="<?php echo base_url(); ?>assets/stylesheets/skins/default.css" />

  <!-- Theme Custom CSS -->
  <link rel="stylesheet" href="<?php echo base_url(); ?>assets/stylesheets/theme-custom.css">


  <!-- Head Libs -->
  <script src="<?php echo base_url(); ?>assets/vendor/modernizr/modernizr.js"></script>
</head>
<body>
  <section class="body">

   <?php function_lib::getHeader(); ?>

   <div class="inner-wrapper">
    <!-- start: sidebar -->
    <?php function_lib::getLeftMenu(); ?>
    <!-- end: sidebar -->

    <section role="main" class="content-body">
       <header class="page-header">
          <h2>Kasir</h2>

          <div class="right-wrapper pull-right">
             <ol class="breadcrumbs">
                <li>
                   <a href="<?php echo base_url('admin/dashboard'); ?>">
                      <i class="fa fa-home"></i>
                  </a>
              </li>
              <li><span>Kasir</span></li>
          </ol>

          <a class="sidebar-right-toggle" data-open="sidebar-right"><i class="fa fa-chevron-left"></i></a>
      </div>
  </header>
  <div class="panel panel-default">
    <div class="panel-heading">
        <h3 class="panel-title">Edit Kasir</h3>
    </div>
    <div class="panel panel-body">
        <?php if (trim($this->input->get('status'))!=""): ?>
            <?php echo function_lib::response_notif($this->input->get('status'),$this->input->get('msg')); ?>
        <?php endif ?>
        <div class="tabs">
            <ul class="nav nav-tabs tabs-primary">
                <li class="active">
                    <a href="#profil" data-toggle="tab">Profil</a>
                </li>
                <li>
                    <a href="#password" data-toggle="tab">Password</a>
                </li>
            </ul>
            <div class="tab-content">

                <div id="profil" class="tab-pane  active">

                    <form class="form-horizontal" method="post" id="form-profil">
                        <h4 class="mb-xlg">Profil</h4>

                        <fieldset>
                            <?php if (trim($this->input->get('status'))!=""): ?>
                                <?php echo function_lib::response_notif($this->input->get('status'),$this->input->get('msg')); ?>
                            <?php endif ?> 
                             <div class="form-group">
                                <label class="col-sm-2 control-label">Koperasi <span class="required">*</span></label>
                                <div class="col-sm-4">

                                    <select class="form-control" id="select2-koperasi" name="id_owner">
                                      <?php foreach ($owner as $key => $value): ?>
                                          <option <?php if ($value['id_owner']==$kasir['id_owner']): ?>selected <?php endif ?> value="<?php echo isset($value['id_owner']) ? $value['id_owner'] : "";  ?>"><?php echo isset($value['nama_koperasi']) ? $value['nama_koperasi'] : "";  ?></option>
                                      <?php endforeach ?>
                                    </select>

                                </div>                                            
                            </div>     
                            <div class="form-group">
                                <label class="col-sm-2 control-label">Username <span class="required">*</span></label>
                                <div class="col-sm-4">

                                    <input type="text" name="username" value="<?php echo (trim($kasir['username'])!="")?$kasir['username']:""; ?>" class="form-control" placeholder="Username .." required/>

                                </div>                                            
                            </div>                                              
                            <div class="form-group">
                                <label class="col-sm-2 control-label">Email <span class="required">*</span></label>
                                <div class="col-sm-4">

                                    <input type="text" name="email" value="<?php echo (trim($kasir['email'])!="")?$kasir['email']:""; ?>" class="form-control" placeholder="Email .." required/>

                                </div>                                            
                            </div>   
                            <div class="form-group">
                                <label class="col-sm-2 control-label">Nama </label>
                                <div class="col-sm-4">

                                    <input type="text" name="nama" value="<?php echo (trim($kasir['nama'])!="")?$kasir['nama']:""; ?>" class="form-control" placeholder="Nama .." required/>

                                </div>                                            
                            </div>      
                            <div class="form-group">
                                <label class="col-sm-2 control-label">No. HP </label>
                                <div class="col-sm-4">

                                    <input type="text" name="no_hp" value="<?php echo (trim($kasir['no_hp'])!="")?$kasir['no_hp']:""; ?>" class="form-control" placeholder="No Hp .." required/>

                                </div>                                            
                            </div>    
                            <div class="form-group">
                                <label class="col-sm-2 control-label">No. KTP </label>
                                <div class="col-sm-4">

                                    <input type="text" name="no_ktp" value="<?php echo (trim($kasir['no_ktp'])!="")?$kasir['no_ktp']:""; ?>" class="form-control" placeholder="Nomor KTP .." required/>

                                </div>                                            
                            </div>       
                            <div class="form-group">
                                <label class="col-sm-2 control-label">Status</label>
                                <div class="col-sm-4">
                                    <select class="form-control" name="status">
                                       <option value="aktif" <?php echo ($this->input->post('status')!="")?(($this->input->post('status')=="aktif")?'selected':''):(($kasir['status']=='aktif')?'selected':''); ?>>Aktif</option>
                                       <option value="pending" <?php echo ($this->input->post('status')!="")?(($this->input->post('status')=="pending")?'selected':''):(($kasir['status']=='pending')?'selected':''); ?>>Pending</option>
                                       <option value="blokir" <?php echo ($this->input->post('status')!="")?(($this->input->post('status')=="blokir")?'selected':''):(($kasir['status']=='blokir')?'selected':''); ?>>Blokir</option>                                                   
                                   </select>
                               </div>                                            
                           </div>                                  
                       </fieldset> 
                       <div class="panel-footer">
                        <div class="row">
                            <div class="col-md-9 col-md-offset-3">
                                <button type="submit" name="edit" value="1" class="btn btn-primary">Edit</button>
                                <a href="<?php echo base_url('user/kasir'); ?>" class="btn btn-default">Cancel</a>
                            </div>
                        </div>
                    </div>                                      


                </form>
            </div>
            <div id="password" class="tab-pane">
                <form method="POST" id="change_password">
                    <h4 class="mb-xlg">Ubah Password</h4>                    
                    <fieldset class="mb-xl">
                        <div class="form-group">
                            <label class="col-md-3 control-label" for="profileNewPassword">Password Baru</label>
                            <div class="col-md-8">
                                <input type="password" class="form-control" name="new_password">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3 control-label" for="profileNewPasswordRepeat">Ulangi Password</label>
                            <div class="col-md-8">
                                <input type="password" class="form-control" name="repeat_password">
                            </div>
                        </div>
                    </fieldset>
                    <div class="panel-footer">
                        <div class="row">
                            <div class="col-md-9 col-md-offset-3">
                                <button type="submit" class="btn btn-primary" name="change_password" value="1">Simpan</button>
                                <a href="<?php echo base_url('user/kasir'); ?>" class="btn btn-default">Cancel</a>
                            </div>
                        </div>
                    </div>
                </form>                                     




            </div>
        </div>
    </div>

</div>
</div>

</section>
</div>

<?php $this->load->view('admin/right_bar'); ?>
</section>
<!-- Vendor -->
<script src="<?php echo base_url(); ?>assets/vendor/jquery/jquery.js"></script>
<script src="<?php echo base_url(); ?>assets/vendor/jquery-browser-mobile/jquery.browser.mobile.js"></script>
<script src="<?php echo base_url(); ?>assets/vendor/bootstrap/js/bootstrap.js"></script>
<script src="<?php echo base_url(); ?>assets/vendor/nanoscroller/nanoscroller.js"></script>
<script src="<?php echo base_url(); ?>assets/vendor/bootstrap-datepicker/js/bootstrap-datepicker.js"></script>
<script src="<?php echo base_url(); ?>assets/vendor/magnific-popup/magnific-popup.js"></script>
<script src="<?php echo base_url(); ?>assets/vendor/jquery-placeholder/jquery.placeholder.js"></script>

<!-- Specific Page Vendor -->
<script src="<?php echo base_url(); ?>assets/vendor/jquery-ui/js/jquery-ui-1.10.4.custom.js"></script>
<script src="<?php echo base_url(); ?>assets/vendor/jquery-ui-touch-punch/jquery.ui.touch-punch.js"></script>
<script src="<?php echo base_url(); ?>assets/vendor/fuelux/js/spinner.js"></script>
<script src="<?php echo base_url(); ?>assets/vendor/jquery-validation/jquery.validate.js"></script>
<script src="<?php echo base_url(); ?>assets/vendor/select2/select2.js"></script>

<!-- Theme Initialization Files -->
<!-- Theme Base, Components and Settings -->
<script src="<?php echo base_url(); ?>assets/javascripts/theme.js"></script>

<!-- Theme Custom -->
<script src="<?php echo base_url(); ?>assets/javascripts/theme.custom.js"></script>

<!-- Theme Initialization Files -->
<script src="<?php echo base_url(); ?>assets/javascripts/theme.init.js"></script>

<script src="<?php echo base_url(); ?>assets/javascripts/forms/examples.validation.js"></script>                
<script type="text/javascript">
$("#select2-koperasi").select2({});  
$('form#form-profil').submit(function(event) {
    var formData = {
      'id_owner' : $('[name=id_owner]').val(),
        'username' : $('[name=username]').val(),
        'email' : $('[name=email]').val(),
        'nama' : $('[name=nama]').val(),
        'no_hp' : $('[name=no_hp]').val(),
        'no_ktp' : $('[name=no_ktp]').val(),        
        'status' : $('[name=status]').val(),
        'edit' : $('[name=edit]').val(),        
    };  
    if (confirm("Yakin simpan?")) {
      $.ajax({
        url: '<?php echo base_url().'user/kasir/edit/'.$id_kasir ?>',
        type: 'POST',
        dataType: 'JSON',
        data: formData,
        success :function (response){
          if (response.status == 200) {
            window.location = '<?php echo base_url("user/kasir?status=200&msg=").base64_encode("Berhasil mengubah kasir"); ?>';
          }else{
            if (response.error.id_owner) {
                  $('div:has(> [name=id_owner])').addClass('has-error');
                  $('div:has(> [name=id_owner])').after('<div class="text-danger">' + response.error.id_owner + '</div>');
            }
            if (response.error.username) {
                  $('div:has(> [name=username])').addClass('has-error');
                  $('div:has(> [name=username])').after('<div class="text-danger">' + response.error.username + '</div>');
            }
            if (response.error.email) {
                  $('div:has(> [name=email])').addClass('has-error');
                  $('div:has(> [name=email])').after('<div class="text-danger">' + response.error.email + '</div>');
            }
            if (response.error.status) {
                  $('div:has(> [name=status])').addClass('has-error');
                  $('div:has(> [name=status])').after('<div class="text-danger">' + response.error.status + '</div>');
            }
            if (response.error.no_hp) {
                  $('div:has(> [name=no_hp])').addClass('has-error');
                  $('div:has(> [name=no_hp])').after('<div class="text-danger">' + response.error.no_hp + '</div>');
            }
            if (response.error.no_ktp) {
                  $('div:has(> [name=no_ktp])').addClass('has-error');
                  $('div:has(> [name=no_ktp])').after('<div class="text-danger">' + response.error.no_ktp + '</div>');
            }
            if (response.error.nama) {
                  $('div:has(> [name=nama])').addClass('has-error');
                  $('div:has(> [name=nama])').after('<div class="text-danger">' + response.error.nama + '</div>');
            }
          }
        }
      });
    }
    event.preventDefault();
});
$('form#change_password').submit(function(event) {
    var formData = {        
        'new_password' : $('[name=new_password]').val(),        
        'repeat_password' : $('[name=repeat_password]').val(),
        'change_password' : $('[name=change_password]').val(),        
    };  
    if (confirm("Yakin mengubah password?")) {
      $.ajax({
        url: '<?php echo base_url().'user/kasir/change_password/'.$id_kasir ?>',
        type: 'POST',
        dataType: 'JSON',
        data: formData,
        success :function (response){
          if (response.status == 200) {
            window.location = '<?php echo base_url("user/kasir?status=200&msg=").base64_encode("Berhasil mengubah kasir"); ?>';
          }else{
            if (response.error.new_password) {
                  $('div:has(> [name=new_password])').addClass('has-error');
                  $('div:has(> [name=new_password])').after('<div class="text-danger">' + response.error.new_password + '</div>');
            }
            if (response.error.repeat_password) {
                  $('div:has(> [name=repeat_password])').addClass('has-error');
                  $('div:has(> [name=repeat_password])').after('<div class="text-danger">' + response.error.repeat_password + '</div>');
            }
          }
        }
      });
    }
    event.preventDefault();
});

</script>


</body>
</html>