<!doctype html>
<html class="fixed">
<head>

  <!-- Basic -->
  <meta charset="UTF-8">
  <title>Edit Owner | <?php echo function_lib::get_config_value('website_name'); ?></title>
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
          <h2>Owner</h2>

          <div class="right-wrapper pull-right">
             <ol class="breadcrumbs">
                <li>
                   <a href="<?php echo base_url('admin/dashboard'); ?>">
                      <i class="fa fa-home"></i>
                  </a>
              </li>
              <li><span>Owner</span></li>
          </ol>

          <a class="sidebar-right-toggle" data-open="sidebar-right"><i class="fa fa-chevron-left"></i></a>
      </div>
  </header>
  <div class="panel panel-default">
    <div class="panel-heading">
        <h3 class="panel-title">Edit Owner</h3>
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

            <?php if (trim($this->input->get('status'))!=""): ?>
                <?php echo function_lib::response_notif($this->input->get('status'),$this->input->get('msg')); ?>
            <?php endif ?> 
            <div class="row m-b-10">
              <div class="col-md-6">
                 <div class="form-group">
                    <label class="col-sm-4 control-label">Username <span class="required">*</span></label>
                    <div class="col-sm-8">
                        <input type="text" name="username" value="<?php echo trim($owner['username'])!="" ?$owner['username']:""; ?>" class="form-control" placeholder="Username .." required/>
                    </div>                                            
                </div>   
              </div>
              <div class="col-md-6">
                <div class="form-group">
                    <label class="col-sm-4 control-label">Email <span class="required">*</span></label>
                    <div class="col-sm-8">

                        <input type="text" name="email" value="<?php echo (trim($owner['email'])!="")?trim($owner['email']):""; ?>" class="form-control" placeholder="Email .." required/>

                    </div>                                            
                </div>
              </div>
            </div>
            <div class="row m-b-10">
              <div class="col-md-6">
                  <div class="form-group">
                    <label class="col-sm-4 control-label">Nama Owner<span class="required">*</span></label>
                    <div class="col-sm-8">

                        <input type="text" name="nama_owner" value="<?php echo (trim($owner['nama_owner'])!="")?trim($owner['nama_owner']):""; ?>" class="form-control" placeholder="Nama Owner .." required/>

                    </div>                                            
                </div>   
              </div>
              <div class="col-md-6">
                  <div class="form-group">
                    <label class="col-sm-4 control-label">Nama Koperasi<span class="required">*</span></label>
                    <div class="col-sm-8">

                        <input type="text" name="nama_koperasi" value="<?php echo (trim($owner['nama_koperasi'])!="")?trim($owner['nama_koperasi']):""; ?>" class="form-control" placeholder="Nama koperasi .." required/>

                    </div>                                            
                </div> 
              </div>
            </div>
            <div class="row m-b-10">
              <div class="col-md-6">
                   <div class="form-group">
                    <label class="col-sm-4 control-label">No. HP/WA/No. Telp </label>
                    <div class="col-sm-8">

                        <input type="text" name="no_hp" value="<?php echo (trim($owner['no_hp'])!="")?trim($owner['no_hp']):""; ?>" class="form-control" placeholder="08XXXX" required/>

                    </div>                                            
                </div>  
              </div>
              <div class="col-md-6">
                 <div class="form-group">
                    <label class="col-sm-4 control-label">Alamat<span class="required">*</span></label>
                    <div class="col-sm-8">
                      <textarea name="alamat" class="form-control"><?php echo (trim($owner['alamat'])!="")?trim($owner['alamat']):""; ?></textarea>
                      
                    </div>                                            
                </div> 
              </div>
            </div>
            <div class="row m-b-10">
              <div class="col-md-6">
                 <div class="form-group">
                    <label class="col-sm-4 control-label">Provinsi <span class="required">*</span></label>
                    <div class="col-sm-8">
                      <select class="form-control select2-input select2-provinsi" name="provinsi">                    
                      </select>
                    </div>                                            
                </div>  
              </div>
              <div class="col-md-6">
                 <div class="form-group">
                    <label class="col-sm-4 control-label">Kabupaten <span class="required">*</span></label>
                    <div class="col-sm-8">
                      <select class="form-control select2-input select2-kabupaten" name="kabupaten">                    
                      </select>
                    </div>                                            
                </div>  
              </div>
            </div>
            <div class="row m-b-10">
              <div class="col-md-6">
                   <div class="form-group">
                    <label class="col-sm-4 control-label">Kecamatan <span class="required">*</span></label>
                    <div class="col-sm-8">
                      <select class="form-control select2-input select2-kecamatan" name="kecamatan">                    
                      </select>
                    </div>                                            
                </div> 
              </div>
              <div class="col-md-6">
                   <div class="form-group">
                    <label class="col-sm-4 control-label">No. Badan Hukum <span class="required">*</span></label>
                    <div class="col-sm-8">

                        <input type="text" name="no_badan_hukum" value="<?php echo (trim($owner['no_badan_hukum'])!="")?trim($owner['no_badan_hukum']):""; ?>" class="form-control" placeholder="..." required/>

                    </div>                                            
                </div> 
              </div>
            </div>
            <div class="row m-b-10">
              <div class="col-md-6">
                 <div class="form-group">
                    <label class="col-sm-4 control-label">Persentase Biaya Administrasi <span class="required">* dalam %</span></label>
                    <div class="col-sm-8">
                      <div class="input-group input-group-icon">
                        <input type="number" step="any" pattern="^\d*(\.\d{0,2})?$" step="any" pattern="^\d*(\.\d{0,2})?$" name="biaya_administrasi" value="<?php echo (trim($owner['biaya_administrasi'])!="")?trim($owner['biaya_administrasi']):""; ?>" class="form-control" placeholder=".." required/>
                            <span class="input-group-addon">
                              <span class="icon"><b>%</b></span>
                            </span>
                      </div>

                    </div>                                            
                </div>  
              </div>
              <div class="col-md-6">
                 <div class="form-group">
                    <label class="col-sm-4 control-label">Persentase Biaya Tabungan <span class="required">* dalam %</span></label>
                    <div class="col-sm-8">
                        <div class="input-group input-group-icon">
                        <input type="number" step="any" pattern="^\d*(\.\d{0,2})?$" name="biaya_simpanan" value="<?php echo (trim($owner['biaya_simpanan'])!="")?trim($owner['biaya_simpanan']):""; ?>" class="form-control" placeholder=".." required/>
                        <span class="input-group-addon">
                              <span class="icon"><b>%</b></span>
                            </span>
                        </div>

                    </div>                                            
                </div> 
              </div>
            </div>
            <div class="row m-b-10">
              <div class="col-md-6">
                   <div class="form-group">
                    <label class="col-sm-4 control-label">Persentase Bunga Pinjaman <span class="required">* dalam %</span></label>
                    <div class="col-sm-8">
                      <div class="input-group input-group-icon">
                        <input type="number" step="any" pattern="^\d*(\.\d{0,2})?$" name="bunga_pinjaman" value="<?php echo (trim($owner['bunga_pinjaman'])!="")?trim($owner['bunga_pinjaman']):""; ?>" class="form-control" placeholder=".." required/>
                        <span class="input-group-addon">
                              <span class="icon"><b>%</b></span>
                            </span>

                      </div>
                    </div>                                            
                </div> 
              </div>
              <div class="col-md-6">
                  <div class="form-group">
                    <label class="col-sm-4 control-label">Biaya Sewa Aplikasi <span class="required">* </span></label>
                    <div class="col-sm-8">
                      <div class="input-group input-group-icon">
                         <span class="input-group-addon">
                              <span class="icon"><b>Rp. </b></span>
                            </span>
                        <input type="number" step="any" pattern="^\d*(\.\d{0,2})?$" name="biaya_sewa_aplikasi" value="<?php echo (trim($owner['biaya_sewa_aplikasi'])!="")?trim($owner['biaya_sewa_aplikasi']):""; ?>" class="form-control" placeholder="" required/>
                      </div>

                    </div>                                            
                </div>    
              </div>
            </div>   
             <div class="row m-b-10">
              <div class="col-md-6">
                   <div class="form-group">
                    <label class="col-sm-4 control-label">Tgl Pembayaran Sistem <span class="required">*/span></label>
                    <div class="col-sm-8">
                      
                        <input type="datetime-local" name="tgl_jatuh_tempo_pembayaran_sistem" value="<?php echo (trim($owner['tgl_jatuh_tempo_pembayaran_sistem'])!="")?date("Y-m-d\TH:i:s",strtotime($owner['tgl_jatuh_tempo_pembayaran_sistem'])):""; ?>" class="form-control" placeholder=".." required/>
                      
                      
                    </div>                                            
                </div> 
              </div>              
            </div>            
                <!-- <div class="form-group">
                      <label class="col-sm-4 control-label">Status</label>
                      <div class="col-sm-8">
                          <select class="form-control" name="status">
                           <option value="aktif" <?php echo (trim($owner['status'])=="aktif")?"selected":""; ?>>Aktif</option>
                           <option value="pending" <?php echo (trim($owner['status'])=="pending")?"selected":""; ?>>Pending</option>
                           <option value="blokir" <?php echo (trim($owner['status'])=="blokir")?"selected":""; ?>>Blokir</option>                                                   
                       </select>
                   </div>                                            
               </div> -->
                                           
        <div class="panel-footer">
            <div class="row">
                <div class="col-md-9 col-md-offset-3">
                    <button type="submit" name="edit" value="1" class="btn btn-primary">Edit</button>
                    <a href="<?php echo base_url('user/owner'); ?>" class="btn btn-default" onclick="return confirm('Batal menambah?')" >Batal</a>
                </div>
            </div>
        </div>                                      


    </form>
            </div>
            <div id="password" class="tab-pane">
                <form id="change_password" method="POST">
                    <h4 class="mb-xlg">Ganti Password</h4>                    
                    <fieldset class="mb-xl">
                        <div class="form-group">
                            <label class="col-md-3 control-label" for="profileNewPassword">Password Baru</label>
                            <div class="col-md-6">
                                <input type="password" class="form-control" name="new_password">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3 control-label" for="profileNewPasswordRepeat">Konfirmasi Password</label>
                            <div class="col-md-6">
                                <input type="password" class="form-control" name="repeat_password">
                            </div>
                        </div>
                    </fieldset>
                    <div class="panel-footer">
                        <div class="row">
                            <div class="col-md-9 col-md-offset-3">
                                <button type="submit" class="btn btn-primary" name="change_password" value="1">Submit</button>
                                <button type="reset" class="btn btn-default">Reset</button>
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
  
  
  
$('form#form-profil').submit(function(event) {
    var formData = {
        'username' : $('[name=username]').val(),
        'email' : $('[name=email]').val(),                
        // 'status' : $('[name=status]').val(),
        'nama_owner' : $('[name=nama_owner]').val(),
        'nama_koperasi' : $('[name=nama_koperasi]').val(),
        'no_hp' : $('[name=no_hp]').val(),
        'alamat' : $('[name=alamat]').val(),
        'kecamatan' : $('[name=kecamatan]').val(),
        'kabupaten' : $('[name=kabupaten]').val(),
        'provinsi' : $('[name=provinsi]').val(),        
        'no_badan_hukum' : $('[name=no_badan_hukum]').val(),        
        'biaya_administrasi' : $('[name=biaya_administrasi]').val(),        
        'biaya_simpanan' : $('[name=biaya_simpanan]').val(),        
        'bunga_pinjaman' : $('[name=bunga_pinjaman]').val(),        
        'biaya_sewa_aplikasi' : $('[name=biaya_sewa_aplikasi]').val(),        
        'tgl_jatuh_tempo_pembayaran_sistem' : $('[name=tgl_jatuh_tempo_pembayaran_sistem]').val(),        
        'edit' : $('[name=edit]').val(),
    };  
    if (confirm("Yakin simpan?")) {
      $.ajax({
        url: '<?php echo base_url().'user/owner/edit/'.$id_owner ?>',
        type: 'POST',
        dataType: 'JSON',
        data: formData,
        success :function (response){
          if (response.status == 200) {
            window.location = '<?php echo base_url("user/owner?status=200&msg=").base64_encode("Berhasil menambah owner"); ?>';
          }else{
            if (response.error.username) {
                  $('div.form-group:has(> [name=username])').addClass('has-error');
                  $('[name=username]').after('<label  class="error">' + response.error.username + '</label>');
            }
            if (response.error.email) {
                  $('div.form-group:has(> [name=email])').addClass('has-error');
                  $('[name=email]').after('<label  class="error">' + response.error.email + '</label>');
            }
            // if (response.error.status) {
            //       $('div.form-group:has(> [name=status])').addClass('has-error');
            //       $('[name=status]').after('<label  class="error">' + response.error.status + '</label>');
            // }            
            if (response.error.nama_onwer) {
                  $('div.form-group:has(> [name=nama_onwer])').addClass('has-error');
                  $('[name=nama_onwer]').after('<label  class="error">' + response.error.nama_onwer + '</label>');
            }
            if (response.error.nama_koperasi) {
                  $('div.form-group:has(> [name=nama_koperasi])').addClass('has-error');
                  $('[name=nama_koperasi]').after('<label  class="error">' + response.error.nama_koperasi + '</label>');
            }
            if (response.error.no_hp) {
                  $('div.form-group:has(> [name=no_hp])').addClass('has-error');
                  $('[name=no_hp]').after('<label  class="error">' + response.error.no_hp + '</label>');
            }
           
            if (response.error.alamat) {
                  $('div.form-group:has(> [name=alamat])').addClass('has-error');
                  $('[name=alamat]').after('<label  class="error">' + response.error.alamat + '</label>');
            }
            if (response.error.kecamatan) {
                  $('div.form-group:has(> [name=kecamatan])').addClass('has-error');
                  $('[name=kecamatan]').after('<label  class="error">' + response.error.kecamatan + '</label>');
            }
            if (response.error.kabupaten) {
                  $('div.form-group:has(> [name=kabupaten])').addClass('has-error');
                  $('[name=kabupaten]').after('<label  class="error">' + response.error.kabupaten + '</label>');
            }
            if (response.error.provinsi) {
                  $('div.form-group:has(> [name=provinsi])').addClass('has-error');
                  $('[name=provinsi]').after('<label  class="error">' + response.error.provinsi + '</label>');
            }
            if (response.error.no_badan_hukum) {
                  $('div.form-group:has(> [name=no_badan_hukum])').addClass('has-error');
                  $('[name=no_badan_hukum]').after('<label  class="error">' + response.error.no_badan_hukum + '</label>');
            }
            if (response.error.biaya_administrasi) {
                  $('div.form-group:has(> [name=biaya_administrasi])').addClass('has-error');
                  $('[name=biaya_administrasi]').after('<label  class="error">' + response.error.biaya_administrasi + '</label>');
            }
            if (response.error.biaya_simpanan) {
                  $('div.form-group:has(> [name=biaya_simpanan])').addClass('has-error');
                  $('[name=biaya_simpanan]').after('<label  class="error">' + response.error.biaya_simpanan + '</label>');
            }
            if (response.error.bunga_pinjaman) {
                  $('div.form-group:has(> [name=bunga_pinjaman])').addClass('has-error');
                  $('[name=bunga_pinjaman]').after('<label  class="error">' + response.error.bunga_pinjaman + '</label>');
            }
            if (response.error.biaya_sewa_aplikasi) {
                  $('div.form-group:has(> [name=biaya_sewa_aplikasi])').addClass('has-error');
                  $('[name=biaya_sewa_aplikasi]').after('<label  class="error">' + response.error.biaya_sewa_aplikasi + '</label>');
            }
            if (response.error.tgl_jatuh_tempo_pembayaran_sistem) {
                  $('div.form-group:has(> [name=tgl_jatuh_tempo_pembayaran_sistem])').addClass('has-error');
                  $('[name=tgl_jatuh_tempo_pembayaran_sistem]').after('<label  class="error">' + response.error.tgl_jatuh_tempo_pembayaran_sistem + '</label>');
            }
          }
        }
      });
    }
    event.preventDefault();
});
$(function() {
  $.ajax({
      url:'<?php echo base_url();?>alamat/get_all_provinsi',       
      dataType: 'json',
      type: 'post',    
      success:function(response){                     
          optionArr = response;
          $('.select2-kabupaten').html("");
          var newOption = ['<option value="" >Pilih Provinsi</option>'];            
          for(var i=0;i<response.length;i++){                            
              newOption.push('<option value="'+ response[i]['id'] +'">'+ response[i]['text'] +'</option>');
          }               
          $('.select2-provinsi').select2({
              minimumInputLength: 0,                        
          }).append(newOption);
          var selected = <?php echo $owner['provinsi']; ?>;          
          $('.select2-provinsi').val(selected).trigger('change');
      }
  })
  $(".select2-provinsi").on('change',function(){        
    $.ajax({
        url:'<?php echo base_url();?>alamat/get_all_kabupaten/'+this.value,       
        dataType: 'json',
        type: 'post',    
        success:function(response){                     
            optionArr = response;
            $('.select2-kabupaten').html("");
            var newOption = ['<option value="" >Pilih Kabupaten</option>'];            
            for(var i=0;i<response.length;i++){                
                newOption.push('<option value="'+ response[i]['id'] +'">'+ response[i]['text'] +'</option>');
            }               
            $('.select2-kabupaten').select2({
                minimumInputLength: 0,                        
            }).append(newOption).select2('open');     
            var selected = <?php echo $owner['kabupaten']; ?>;          
            $('.select2-kabupaten').val(selected).trigger('change');   
        }
    })
  });
  $(".select2-kabupaten").on('change',function(){        
    $.ajax({
        url:'<?php echo base_url();?>alamat/get_all_kecamatan/'+this.value,       
        dataType: 'json',
        type: 'post',    
        success:function(response){                     
            optionArr = response;
            $('.select2-kecamatan').html("");
            var newOption = ['<option value="" >Pilih Kecamatan</option>'];            
            for(var i=0;i<response.length;i++){                
                newOption.push('<option value="'+ response[i]['id'] +'">'+ response[i]['text'] +'</option>');
            }               
            $('.select2-kecamatan').select2({
                minimumInputLength: 0,                        
            }).append(newOption).select2('open');        
            var selected = <?php echo $owner['kecamatan']; ?>;          
            $('.select2-kecamatan').val(selected).trigger('change');   
        }
    })
  });
});
$('form#change_password').submit(function(event) {
    var formData = {        
        'new_password' : $('[name=new_password]').val(),        
        'repeat_password' : $('[name=repeat_password]').val(),
        'change_password' : $('[name=change_password]').val(),        
    };  
    if (confirm("Yakin mengubah password?")) {
      $.ajax({
        url: '<?php echo base_url().'user/owner/change_password/'.$id_owner ?>',
        type: 'POST',
        dataType: 'JSON',
        data: formData,
        success :function (response){
          if (response.status == 200) {
            window.location = '<?php echo base_url("user/owner?status=200&msg=").base64_encode("Berhasil mengubah owner"); ?>';
          }else{
            if (response.error.new_password) {
                  $('div:has(> [name=new_password])').addClass('has-error');
                  $('[name=new_password]').after('<label  class="error">' + response.error.new_password + '</label>');                  
            }
            if (response.error.repeat_password) {
                  $('div:has(> [name=repeat_password])').addClass('has-error');
                  $('[name=repeat_password]').after('<label  class="error">' + response.error.repeat_password + '</label>');
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