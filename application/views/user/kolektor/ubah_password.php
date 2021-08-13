<!doctype html>
<html class="fixed">
<head>

  <!-- Basic -->
  <meta charset="UTF-8">
  <title>Ubah Password Kolektor | <?php echo function_lib::get_config_value('website_name'); ?></title>
  <meta name="keywords" content="Dashboard Kolektor - <?php echo function_lib::get_config_value('website_name'); ?>" />
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
          <h2>Kolektor</h2>

          <div class="right-wrapper pull-right">
             <ol class="breadcrumbs">
                <li>
                   <a href="<?php echo base_url('user/kolektor/dashboard'); ?>">
                      <i class="fa fa-home"></i>
                  </a>
              </li>
              <li><span>Kolektor</span></li>
          </ol>

          <a class="sidebar-right-toggle" data-open="sidebar-right"><i class="fa fa-chevron-left"></i></a>
      </div>
  </header>
  <div class="panel panel-default">
    <div class="panel-heading">
        <h3 class="panel-title">Ubah Password Kolektor</h3>
    </div>
    <div class="panel panel-body">
        <?php if (trim($this->input->get('status'))!=""): ?>
            <?php echo function_lib::response_notif($this->input->get('status'),$this->input->get('msg')); ?>
        <?php endif ?>
        <div class="tabs">
            <ul class="nav nav-tabs tabs-primary">                
                <li class="active">
                    <a href="#password" data-toggle="tab">Password</a>
                </li>
            </ul>
            <div class="tab-content">
               
            <div id="password">
                <form method="POST" id="change_password">
                    <h4 class="mb-xlg">Ubah Password</h4>                    
                    <fieldset class="mb-xl">
                        <div class="form-group">
                            <label class="col-md-2 control-label" for="profileNewPassword">Password Baru</label>
                            <div class="col-md-3">
                                <input type="password" class="form-control" name="new_password">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-2 control-label" for="profileNewPasswordRepeat">Ulangi Password</label>
                            <div class="col-md-3">
                                <input type="password" class="form-control" name="repeat_password">
                            </div>
                        </div>
                    </fieldset>
                    <div class="panel-footer">
                        <div class="row">
                            <div class="col-md-9 col-md-offset-3">
                                <button type="submit" class="btn btn-primary" name="change_password" value="1">Simpan</button>
                                <a href="<?php echo base_url('user/kolektor'); ?>" class="btn btn-default">Cancel</a>
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

<?php $this->load->view('user/kolektor/right_bar'); ?>
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
  
$('form#change_password').submit(function(event) {
    var formData = {        
        'new_password' : $('[name=new_password]').val(),        
        'repeat_password' : $('[name=repeat_password]').val(),
        'change_password' : $('[name=change_password]').val(),        
    };  
    if (confirm("Yakin mengubah password?")) {
      $.ajax({
        url: '<?php echo base_url().'user/kolektor/change_password/'.$id_kolektor ?>',
        type: 'POST',
        dataType: 'JSON',
        data: formData,
        success :function (response){
          if (response.status == 200) {
            window.location = '<?php echo base_url("user/kolektor?status=200&msg=").base64_encode("Berhasil mengubah kolektor"); ?>';
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