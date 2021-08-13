<!doctype html>
<html class="fixed">
<head>

  <!-- Basic -->
  <meta charset="UTF-8">
  <title>Tambah Kasir | <?php echo function_lib::get_config_value('website_name'); ?></title>
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
        <h3 class="panel-title">Tambah Kasir</h3>
    </div>
    <div class="panel panel-body">
        
        <form class="form-horizontal" method="post" id="form-tambah">

            <?php if (trim($this->input->get('status'))!=""): ?>
                <?php echo function_lib::response_notif($this->input->get('status'),$this->input->get('msg')); ?>
            <?php endif ?> 
            <div class="form-group">
                <label class="col-sm-2 control-label">Koperasi <span class="required">*</span></label>
                <div class="col-sm-4">

                    <select class="form-control" id="select2-koperasi" name="id_owner">
                      <?php foreach ($owner as $key => $value): ?>
                          <option value="<?php echo isset($value['id_owner']) ? $value['id_owner'] : "";  ?>"><?php echo isset($value['nama_koperasi']) ? $value['nama_koperasi'] : "";  ?></option>
                      <?php endforeach ?>
                    </select>

                </div>                                            
            </div>                                              
            <div class="form-group">
                <label class="col-sm-2 control-label">Username <span class="required">*</span></label>
                <div class="col-sm-4">

                    <input type="text" name="username" value="<?php echo ($this->input->post('username')!="")?$this->input->post('username'):""; ?>" class="form-control" placeholder="Username .." required/>

                </div>                                            
            </div>                                              
            <div class="form-group">
                <label class="col-sm-2 control-label">Email <span class="required">*</span></label>
                <div class="col-sm-4">

                    <input type="text" name="email" value="<?php echo ($this->input->post('email')!="")?$this->input->post('email'):""; ?>" class="form-control" placeholder="Email .." required/>

                </div>                                            
            </div>      
            <div class="form-group">
                <label class="col-sm-2 control-label">Nama <span class="required">*</span></label>
                <div class="col-sm-4">

                    <input type="text" name="nama" value="<?php echo ($this->input->post('nama')!="")?$this->input->post('nama'):""; ?>" class="form-control" placeholder="Nama kasir .." required/>

                </div>                                            
            </div>      
            <div class="form-group">
                <label class="col-sm-2 control-label">Nomor HP / Whatsapp</label>
                <div class="col-sm-4">

                    <input type="text" name="no_hp" value="<?php echo ($this->input->post('no_hp')!="")?$this->input->post('no_hp'):""; ?>" class="form-control" placeholder="Nomor HP atau whatsapp .." />
                </div>                                            
            </div>      
            <div class="form-group">
                <label class="col-sm-2 control-label">Nomor KTP</label>
                <div class="col-sm-4">

                    <input type="text" name="no_ktp" value="<?php echo ($this->input->post('no_ktp')!="")?$this->input->post('no_ktp'):""; ?>" class="form-control" placeholder="Nomor KTP kasir .." />
                </div>                                            
            </div>      
            <div class="form-group">
                <label class="col-sm-2 control-label">Status</label>
                <div class="col-sm-4">
                    <select class="form-control" name="status">
                     <option value="aktif" <?php echo ($this->input->post('status')=="aktif")?"selected":""; ?>>Aktif</option>
                     <option value="pending" <?php echo ($this->input->post('status')=="pending")?"selected":""; ?>>Pending</option>
                     <option value="blokir" <?php echo ($this->input->post('status')=="blokir")?"selected":""; ?>>Blokir</option>                                                   
                 </select>
             </div>                                            
         </div>   
         <div class="form-group">
            <label class="col-md-2 control-label" >Password</label>
            <div class="col-md-4">
                <input type="password" class="form-control" name="password">
            </div>
        </div>
        <div class="form-group">
            <label class="col-md-2 control-label" >Ulangi Password</label>
            <div class="col-md-4">
                <input type="password" class="form-control" name="conf_password">
            </div>
        </div>                                       
        <div class="panel-footer">
            <div class="row">
                <div class="col-md-9 col-md-offset-3">
                    <button type="submit" name="tambah" value="1" class="btn btn-primary">Tambah</button>
                    <a href="<?php echo base_url('user/kasir'); ?>" class="btn btn-default" onclick="return confirm('Batal menambah?')" >Batal</a>
                </div>
            </div>
        </div>                                      


    </form>

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
$('form').submit(function(event) {
    var formData = {
        'id_owner' : $('[name=id_owner]').val(),
        'username' : $('[name=username]').val(),
        'email' : $('[name=email]').val(),
        'nama' : $('[name=nama]').val(),
        'no_hp' : $('[name=no_hp]').val(),
        'no_ktp' : $('[name=no_ktp]').val(),
        'password' : $('[name=password]').val(),
        'conf_password' : $('[name=conf_password]').val(),
        'status' : $('[name=status]').val(),
        'tambah' : $('[name=tambah]').val(),
    };  
    if (confirm("Yakin simpan?")) {
      $.ajax({
        url: '<?php echo base_url().'user/kasir/tambah' ?>',
        type: 'POST',
        dataType: 'JSON',
        data: formData,
        success :function (response){
          if (response.status == 200) {
            window.location = '<?php echo base_url("user/kasir?status=200&msg=").base64_encode("Berhasil menambah kasir"); ?>';
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
            if (response.error.password) {
                  $('div:has(> [name=password])').addClass('has-error');
                  $('div:has(> [name=password])').after('<div class="text-danger">' + response.error.password + '</div>');
            }
            if (response.error.conf_password) {
                  $('div:has(> [name=conf_password])').addClass('has-error');
                  $('div:has(> [name=conf_password])').after('<div class="text-danger">' + response.error.conf_password + '</div>');
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