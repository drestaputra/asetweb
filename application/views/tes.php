<!doctype html>
<html class="fixed">
	<head>

		<!-- Basic -->
		<meta charset="UTF-8">
		<title>Admin | <?php echo function_lib::get_config_value('website_name'); ?></title>
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

		<!-- Specific Page Vendor CSS -->
		<link rel="stylesheet" href="<?php echo base_url(); ?>assets/vendor/jquery-ui/css/ui-lightness/jquery-ui-1.10.4.custom.css" />
		<link rel="stylesheet" href="<?php echo base_url(); ?>assets/vendor/bootstrap-multiselect/bootstrap-multiselect.css" />
		<link rel="stylesheet" href="<?php echo base_url(); ?>assets/vendor/morris/morris.css" />
		<link rel="stylesheet" href="<?php echo base_url(); ?>assets/vendor/select2/select2.css" />

		<!-- Theme CSS -->
		<link rel="stylesheet" href="<?php echo base_url(); ?>assets/stylesheets/theme.css" />

		<!-- Skin CSS -->
		<link rel="stylesheet" href="<?php echo base_url(); ?>assets/stylesheets/skins/default.css" />
		
		<!-- flexigrid -->

		<link type="text/css" rel="stylesheet" href="<?php echo base_url(); ?>assets/flexigrid/css/flexigrid.css" />
        <link type="text/css" rel="stylesheet" href="<?php echo base_url(); ?>assets/flexigrid/button/style.css" />

		<!-- Theme Custom CSS -->
		<link rel="stylesheet" href="<?php echo base_url(); ?>assets/stylesheets/theme-custom.css">
		<script src="https://siana.id/assets/vendor/modernizr/modernizr.js"></script>
                    <link type="text/css" rel="stylesheet" href="https://siana.id/assets/grocery_crud/css/jquery_plugins/chosen/chosen.css" />
            
                    <link type="text/css" rel="stylesheet" href="https://siana.id/assets/grocery_crud/css/ui/simple/jquery-ui-1.10.1.custom.min.css" />
            
                    <link type="text/css" rel="stylesheet" href="https://siana.id/assets/grocery_crud/css/jquery_plugins/jquery.ui.datetime.css" />
            
                    <link type="text/css" rel="stylesheet" href="https://siana.id/assets/grocery_crud/css/jquery_plugins/jquery-ui-timepicker-addon.css" />
            
                    <link type="text/css" rel="stylesheet" href="https://siana.id/assets/grocery_crud/themes/adminlte/css/bootstrap/bootstrap.min.css" />
            
                    <link type="text/css" rel="stylesheet" href="https://siana.id/assets/grocery_crud/themes/adminlte/css/font-awesome/css/font-awesome.min.css" />
            
                    <link type="text/css" rel="stylesheet" href="https://siana.id/assets/grocery_crud/themes/adminlte/css/common.css" />
            
                    <link type="text/css" rel="stylesheet" href="https://siana.id/assets/grocery_crud/themes/adminlte/css/general.css" />
            
                            <script src="https://siana.id/assets/grocery_crud/js/jquery-1.11.1.min.js"></script>
            
                    <script src="https://siana.id/assets/grocery_crud/js/jquery_plugins/jquery.chosen.min.js"></script>
            
                    <script src="https://siana.id/assets/grocery_crud/js/jquery_plugins/config/jquery.chosen.config.js"></script>
            
                    <script src="https://siana.id/assets/grocery_crud/js/jquery_plugins/jquery.numeric.min.js"></script>
            
                    <script src="https://siana.id/assets/grocery_crud/js/jquery_plugins/config/jquery.numeric.config.js"></script>
            
                    <script src="https://siana.id/assets/grocery_crud/js/jquery_plugins/ui/jquery-ui-1.10.3.custom.min.js"></script>
            
                    <script src="https://siana.id/assets/grocery_crud/js/jquery_plugins/ui/i18n/datepicker/jquery.ui.datepicker-id.js"></script>
            
                    <script src="https://siana.id/assets/grocery_crud/js/jquery_plugins/config/jquery.datepicker.config.js"></script>
            
                    <script src="https://siana.id/assets/grocery_crud/texteditor/ckeditor/ckeditor.js"></script>
            
                    <script src="https://siana.id/assets/grocery_crud/texteditor/ckeditor/adapters/jquery.js"></script>
            
                    <script src="https://siana.id/assets/grocery_crud/js/jquery_plugins/config/jquery.ckeditor.config.js"></script>
            
                    <script src="https://siana.id/assets/grocery_crud/js/jquery_plugins/jquery-ui-timepicker-addon.js"></script>
            
                    <script src="https://siana.id/assets/grocery_crud/js/jquery_plugins/ui/i18n/timepicker/jquery-ui-timepicker-id.js"></script>
            
                    <script src="https://siana.id/assets/grocery_crud/js/jquery_plugins/config/jquery-ui-timepicker-addon.config.js"></script>
            
                    <script src="https://siana.id/assets/grocery_crud/themes/adminlte/build/js/global-libs.min.js"></script>
            
                    <script src="https://siana.id/assets/grocery_crud/themes/adminlte/js/form/add.js"></script>
            
                    <script src="https://siana.id/assets/grocery_crud/js/jquery_plugins/jquery.noty.js"></script>
            
                    <script src="https://siana.id/assets/grocery_crud/js/jquery_plugins/config/jquery.noty.config.js"></script>
            


		
        
	</head>
	<body>
		<section class="body">

			

			<div class="inner-wrapper">
				<!-- start: sidebar -->
				
				<!-- end: sidebar -->

				<section role="main" class="content-body">
					<header class="page-header">
						<h2>Admin</h2>
					
						<div class="right-wrapper pull-right">
							<ol class="breadcrumbs">
								<li>
									<a href="<?php echo base_url(); ?>">
										<i class="fa fa-home"></i>
									</a>
								</li>
								<li><span>Admin</span></li>
							</ol>
					
							<a class="sidebar-right-toggle" ><i class="fa fa-chevron-left"></i></a>
						</div>
					</header>
					<div class="row">
						<?php if (trim($this->input->get('status'))!=""): ?>
                                <?php echo function_lib::response_notif($this->input->get('status'),$this->input->get('msg')); ?>
                            <?php endif ?>
                            
						
					</div>
					<div class="panel panel-default">

                        <div class="panel-heading">
                            <h3 class="panel-title">Data Admin</h3>
                        </div>
						<div class="panel-body">
                            <div class="alert " style="display: none;">
                                <p class="msg"></p>
                            </div>
							<div class="box  gc-container">
        <div class="box-header">
            <h4 class="box-title">Tambah Aset Tanah</h4>
            <div class="box-tools">
                <!-- <div class="floatR r5 minimize-maximize-container minimize-maximize">
                        <i class="fa fa-caret-up"></i>
                    </div>
                    <div class="floatR r5 gc-full-width">
                        <i class="fa fa-expand"></i>
                    </div>
                    <div class="clear"></div> -->
            </div>
        </div>
        <div class="box-body">
            <form action="https://siana.id/aset/index/insert" method="post" id="crudForm" enctype="multipart/form-data" class="form-horizontal" accept-charset="utf-8">

                                    <div class="form-group id_opd_aset_form_group">
                        <label class="col-sm-2 control-label">
                            OPD                        </label>
                        <div class="col-sm-5">
                            <select id="field-id_opd_aset" name="id_opd_aset" class="chosen-select" data-placeholder="Pilih OPD" style="width: 300px; display: none;"><option value=""></option><option value="11">Dinas Kesehatan</option><option value="1">Dinas Perumahan dan Kawasan Permukiman dan Lingkungan Hidup</option></select><div class="chosen-container chosen-container-single" style="width: 300px;" title="" id="field_id_opd_aset_chosen"><a class="chosen-single chosen-default" tabindex="-1"><span>Pilih OPD</span><div><b></b></div></a><div class="chosen-drop"><div class="chosen-search"><input type="text" autocomplete="off"></div><ul class="chosen-results"><li class="active-result" data-option-array-index="1" style="">Dinas Kesehatan</li><li class="active-result" data-option-array-index="2" style="">Dinas Perumahan dan Kawasan Permukiman dan Lingkungan Hidup</li></ul></div></div>                        </div>
                    </div>
                                    <div class="form-group nama_aset_form_group">
                        <label class="col-sm-2 control-label">
                            Nama aset                        </label>
                        <div class="col-sm-5">
                            <input id="field-nama_aset" class="form-control" name="nama_aset" type="text" value="" maxlength="255">                        </div>
                    </div>
                    <div class="form-group">
                    	<label>Pemanfaatan</label>
                    	<select class="form-control select2"  name="states[]" multiple="multiple">
                    		<option>Pasar</option>
                    		<option>Ruko</option>
                    		<option>Sekolah</option>
                    		<option>Kos-kosan</option>
                    	</select>
                    </div>
                                    <div class="form-group kode_barang_form_group">
                        <label class="col-sm-2 control-label">
                            Kode barang                        </label>
                        <div class="col-sm-5">
                            <input id="field-kode_barang" class="form-control" name="kode_barang" type="text" value="" maxlength="15">                        </div>
                    </div>
                                    <div class="form-group register_form_group">
                        <label class="col-sm-2 control-label">
                            Register                        </label>
                        <div class="col-sm-5">
                            <input id="field-register" class="form-control" name="register" type="text" value="" maxlength="15">                        </div>
                    </div>
                                    <div class="form-group luas_tanah_form_group">
                        <label class="col-sm-2 control-label">
                            Luas tanah                        </label>
                        <div class="col-sm-5">
                            <input id="field-luas_tanah" class="form-control" name="luas_tanah" type="text" value="" maxlength="51">                        </div>
                    </div>
                                    <div class="form-group tahun_perolehan_form_group">
                        <label class="col-sm-2 control-label">
                            Tahun perolehan                        </label>
                        <div class="col-sm-5">
                            <input id="field-tahun_perolehan" name="tahun_perolehan" type="text" value="" class="numeric form-control" maxlength="11">                        </div>
                    </div>
                                    <div class="form-group alamat_form_group">
                        <label class="col-sm-2 control-label">
                            Alamat                        </label>
                        <div class="col-sm-5">
                            <textarea id="field-alamat" name="alamat" class="form-control"></textarea>                        </div>
                    </div>
                                    <div class="form-group id_kecamatan_form_group">
                        <label class="col-sm-2 control-label">
                            Kecamatan                        </label>
                        <div class="col-sm-5"></div>
                    </div>
                                    <div class="form-group id_desa_form_group">
                        <label class="col-sm-2 control-label">
                            Desa                        </label>
                        <div class="col-sm-5">
                            <select id="field-id_desa" name="id_desa" class="chosen-select" data-placeholder="Pilih Desa" style="width: 300px; display: none;"><option value=""></option></select><div class="chosen-container chosen-container-single" style="width: 300px;" title="" id="field_id_desa_chosen"><a class="chosen-single chosen-default" tabindex="-1"><span>Pilih Desa</span><div><b></b></div></a><div class="chosen-drop"><div class="chosen-search"><input type="text" autocomplete="off"></div><ul class="chosen-results"></ul></div></div>                        </div>
                    </div>
                                    <div class="form-group jenis_hak_form_group">
                        <label class="col-sm-2 control-label">
                            Jenis hak                        </label>
                        <div class="col-sm-5">
                            <select id="field-jenis_hak" name="jenis_hak" class="chosen-select" data-placeholder="Pilih Jenis hak" style="display: none;"><option value=""></option><option value="Hak Pakai">Hak Pakai</option><option value=" Hak Guna"> Hak Guna</option></select><div class="chosen-container chosen-container-single" style="width: 112px;" title="" id="field_jenis_hak_chosen"><a class="chosen-single chosen-default" tabindex="-1"><span>Pilih Jenis hak</span><div><b></b></div></a><div class="chosen-drop"><div class="chosen-search"><input type="text" autocomplete="off"></div><ul class="chosen-results"></ul></div></div>                        </div>
                    </div>
                                    <div class="form-group tanggal_sertifikat_form_group">
                        <label class="col-sm-2 control-label">
                            Tanggal sertifikat                        </label>
                        <div class="col-sm-5">
                            <input id="field-tanggal_sertifikat" name="tanggal_sertifikat" type="text" value="" maxlength="10" class="datepicker-input form-control hasDatepicker">
		<a class="datepicker-input-clear ui-button ui-widget ui-state-default ui-corner-all ui-button-text-only" tabindex="-1" role="button" aria-disabled="false"><span class="ui-button-text">Bersihkan</span></a> (dd/mm/yyyy)                        </div>
                    </div>
                                    <div class="form-group nomor_sertifikat_form_group">
                        <label class="col-sm-2 control-label">
                            Nomor sertifikat                        </label>
                        <div class="col-sm-5">
                            <input id="field-nomor_sertifikat" class="form-control" name="nomor_sertifikat" type="text" value="" maxlength="50">                        </div>
                    </div>
                                    <div class="form-group penggunaan_form_group">
                        <label class="col-sm-2 control-label">
                            Penggunaan                        </label>
                        <div class="col-sm-5">
                            <textarea id="field-penggunaan" name="penggunaan" class="form-control"></textarea>                        </div>
                    </div>
                                    <div class="form-group asal_perolehan_form_group">
                        <label class="col-sm-2 control-label">
                            Asal perolehan                        </label>
                        <div class="col-sm-5">
                            <textarea id="field-asal_perolehan" name="asal_perolehan" class="form-control"></textarea>                        </div>
                    </div>
                                    <div class="form-group harga_perolehan_form_group">
                        <label class="col-sm-2 control-label">
                            Harga perolehan                        </label>
                        <div class="col-sm-5">
                            <input id="field-harga_perolehan" class="form-control" name="harga_perolehan" type="text" value="" maxlength="51">                        </div>
                    </div>
                                    <div class="form-group keterangan_form_group">
                        <label class="col-sm-2 control-label">
                            Keterangan                        </label>
                        <div class="col-sm-5"></div>
                                    <div class="form-group latitude_form_group">
                        <label class="col-sm-2 control-label">
                            Latitude                        </label>
                        <div class="col-sm-5">
                            <input id="field-latitude" class="form-control" name="latitude" type="text" value="" maxlength="100">                        </div>
                    </div>
                                    <div class="form-group longitude_form_group">
                        <label class="col-sm-2 control-label">
                            Longitude                        </label>
                        <div class="col-sm-5">
                            <input id="field-longitude" class="form-control" name="longitude" type="text" value="" maxlength="100">                        </div>
                    </div><div class="form-group"><div id="map" class="leaflet-container leaflet-touch leaflet-fade-anim leaflet-grab leaflet-touch-drag leaflet-touch-zoom" tabindex="0" style="position: relative;"><div class="leaflet-pane leaflet-map-pane" style="transform: translate3d(-97px, 1px, 0px);"><div class="leaflet-pane leaflet-tile-pane"><div class="leaflet-layer " style="z-index: 1; opacity: 1;"><div class="leaflet-tile-container leaflet-zoom-animated" style="z-index: 18; transform: translate3d(0px, 0px, 0px) scale(1);"><img alt="" role="presentation" src="https://b.tile.openstreetmap.org/16/52729/34167.png" class="leaflet-tile leaflet-tile-loaded" style="width: 256px; height: 256px; transform: translate3d(411px, -60px, 0px); opacity: 1;"><img alt="" role="presentation" src="https://c.tile.openstreetmap.org/16/52729/34168.png" class="leaflet-tile leaflet-tile-loaded" style="width: 256px; height: 256px; transform: translate3d(411px, 196px, 0px); opacity: 1;"><img alt="" role="presentation" src="https://a.tile.openstreetmap.org/16/52728/34167.png" class="leaflet-tile leaflet-tile-loaded" style="width: 256px; height: 256px; transform: translate3d(155px, -60px, 0px); opacity: 1;"><img alt="" role="presentation" src="https://c.tile.openstreetmap.org/16/52730/34167.png" class="leaflet-tile leaflet-tile-loaded" style="width: 256px; height: 256px; transform: translate3d(667px, -60px, 0px); opacity: 1;"><img alt="" role="presentation" src="https://b.tile.openstreetmap.org/16/52728/34168.png" class="leaflet-tile leaflet-tile-loaded" style="width: 256px; height: 256px; transform: translate3d(155px, 196px, 0px); opacity: 1;"><img alt="" role="presentation" src="https://a.tile.openstreetmap.org/16/52730/34168.png" class="leaflet-tile leaflet-tile-loaded" style="width: 256px; height: 256px; transform: translate3d(667px, 196px, 0px); opacity: 1;"><img alt="" role="presentation" src="https://c.tile.openstreetmap.org/16/52727/34167.png" class="leaflet-tile leaflet-tile-loaded" style="width: 256px; height: 256px; transform: translate3d(-101px, -60px, 0px); opacity: 1;"><img alt="" role="presentation" src="https://a.tile.openstreetmap.org/16/52731/34167.png" class="leaflet-tile leaflet-tile-loaded" style="width: 256px; height: 256px; transform: translate3d(923px, -60px, 0px); opacity: 1;"><img alt="" role="presentation" src="https://a.tile.openstreetmap.org/16/52727/34168.png" class="leaflet-tile leaflet-tile-loaded" style="width: 256px; height: 256px; transform: translate3d(-101px, 196px, 0px); opacity: 1;"><img alt="" role="presentation" src="https://b.tile.openstreetmap.org/16/52731/34168.png" class="leaflet-tile leaflet-tile-loaded" style="width: 256px; height: 256px; transform: translate3d(923px, 196px, 0px); opacity: 1;"><img alt="" role="presentation" src="https://b.tile.openstreetmap.org/16/52726/34167.png" class="leaflet-tile leaflet-tile-loaded" style="width: 256px; height: 256px; transform: translate3d(-357px, -60px, 0px); opacity: 1;"><img alt="" role="presentation" src="https://b.tile.openstreetmap.org/16/52732/34167.png" class="leaflet-tile leaflet-tile-loaded" style="width: 256px; height: 256px; transform: translate3d(1179px, -60px, 0px); opacity: 1;"><img alt="" role="presentation" src="https://c.tile.openstreetmap.org/16/52726/34168.png" class="leaflet-tile leaflet-tile-loaded" style="width: 256px; height: 256px; transform: translate3d(-357px, 196px, 0px); opacity: 1;"><img alt="" role="presentation" src="https://c.tile.openstreetmap.org/16/52732/34168.png" class="leaflet-tile leaflet-tile-loaded" style="width: 256px; height: 256px; transform: translate3d(1179px, 196px, 0px); opacity: 1;"></div></div></div><div class="leaflet-pane leaflet-shadow-pane"><img src="https://cdnjs.cloudflare.com/ajax/libs/leaflet/0.7.7/images/marker-shadow.png" class="leaflet-marker-shadow leaflet-zoom-animated" alt="" style="margin-left: -12px; margin-top: -41px; width: 41px; height: 41px; transform: translate3d(-5.1138e+06px, -3.1674e+06px, 0px);"></div><div class="leaflet-pane leaflet-overlay-pane"></div><div class="leaflet-pane leaflet-marker-pane"><img src="https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-2x-green.png" class="leaflet-marker-icon leaflet-zoom-animated leaflet-interactive" alt="" tabindex="0" style="margin-left: -12px; margin-top: -41px; width: 25px; height: 41px; transform: translate3d(-5.1138e+06px, -3.1674e+06px, 0px); z-index: -3167405;"></div><div class="leaflet-pane leaflet-tooltip-pane"></div><div class="leaflet-pane leaflet-popup-pane"></div><div class="leaflet-proxy leaflet-zoom-animated" style="transform: translate3d(1.34988e+07px, 8.747e+06px, 0px) scale(32768);"></div></div><div class="leaflet-control-container"><div class="leaflet-top leaflet-left"><div class="leaflet-control-zoom leaflet-bar leaflet-control"><a class="leaflet-control-zoom-in" href="#" title="Zoom in" role="button" aria-label="Zoom in">+</a><a class="leaflet-control-zoom-out leaflet-disabled" href="#" title="Zoom out" role="button" aria-label="Zoom out">−</a></div><div class="geocoder-control leaflet-control"><input class="geocoder-control-input leaflet-bar"><ul class="geocoder-control-suggestions leaflet-bar"></ul></div></div><div class="leaflet-top leaflet-right"></div><div class="leaflet-bottom leaflet-left"><div class="leaflet-control-scale leaflet-control"><div class="leaflet-control-scale-line" style="width: 85px;">200 m</div><div class="leaflet-control-scale-line" style="width: 64px;">500 ft</div></div></div><div class="leaflet-bottom leaflet-right"><div class="leaflet-control-attribution leaflet-control"><a href="https://leafletjs.com" title="A JS library for interactive maps">Leaflet</a> | © <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors, Geocoding by Esri</div></div></div></div></div>
                                    <div class="form-group status_aset_form_group">
                        <label class="col-sm-2 control-label">
                            Status aset                        </label>
                        <div class="col-sm-5">
                            <select id="field-status_aset" name="status_aset" class="chosen-select" data-placeholder="Pilih Status aset" style="display: none;"><option value=""></option><option value="aktif">Aktif</option><option value="non_aktif">Non Aktif</option><option value="deleted">Deleted</option></select><div class="chosen-container chosen-container-single" style="width: 105px;" title="" id="field_status_aset_chosen"><a class="chosen-single chosen-default" tabindex="-1"><span>Pilih Status aset</span><div><b></b></div></a><div class="chosen-drop"><div class="chosen-search"><input type="text" autocomplete="off"></div><ul class="chosen-results"></ul></div></div>                        </div>
                    </div>
                                    <div class="form-group status_verifikasi_aset_form_group">
                        <label class="col-sm-2 control-label">
                            Status verifikasi aset                        </label>
                        <div class="col-sm-5">
                            <select id="field-status_verifikasi_aset" name="status_verifikasi_aset" class="chosen-select" data-placeholder="Pilih Status verifikasi aset" style="display: none;"><option value=""></option><option value="valid">Valid</option><option value="tidak_valid">Tidak Valid</option><option value="sedang_diverifikasi">Sedang Diverifikasi</option></select><div class="chosen-container chosen-container-single" style="width: 168px;" title="" id="field_status_verifikasi_aset_chosen"><a class="chosen-single chosen-default" tabindex="-1"><span>Pilih Status verifikasi aset</span><div><b></b></div></a><div class="chosen-drop"><div class="chosen-search"><input type="text" autocomplete="off"></div><ul class="chosen-results"></ul></div></div>                        </div>
                    </div>
                                    <div class="form-group created_datetime_form_group">
                        <label class="col-sm-2 control-label">
                            Tanggal Data                        </label>
                        <div class="col-sm-5">
                            <input id="field-created_datetime" name="created_datetime" type="text" value="" maxlength="19" class="datetime-input form-control hasDatepicker">
		<a class="datetime-input-clear ui-button ui-widget ui-state-default ui-corner-all ui-button-text-only" tabindex="-1" role="button" aria-disabled="false"><span class="ui-button-text">Bersihkan</span></a>
		(dd/mm/yyyy) hh:mm:ss                        </div>
                    </div>
                                    <div class="form-group created_by_id_form_group">
                        <label class="col-sm-2 control-label">
                            Created by id                        </label>
                        <div class="col-sm-5">
                            <input id="field-created_by_id" name="created_by_id" type="text" value="" class="numeric form-control" maxlength="11">                        </div>
                    </div>
                                    <div class="form-group created_by_form_group">
                        <label class="col-sm-2 control-label">
                            Created by                        </label>
                        <div class="col-sm-5">
                            <input id="field-created_by" class="form-control" name="created_by" type="text" value="" maxlength="100">                        </div>
                    </div>
                                <!-- Start of hidden inputs -->
                                <!-- End of hidden inputs -->
                
                <div class="small-loading hidden" id="FormLoading">Mohon Tunggu</div>

                <div class="form-group">
                    <div class="col-sm-offset-2 col-sm-5">
                        <div id="report-error" class="report-div error bg-danger" style="display:none"></div>
                        <div id="report-success" class="report-div success bg-success" style="display:none"></div>
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-sm-offset-2 col-sm-7">
                        <button class="btn btn-success btn-flat" type="submit" id="form-button-save">
                            <i class="fa fa-check"></i>
                            Simpan                        </button>
                                                    <button class="btn btn-info btn-flat" type="button" id="save-and-go-back-button">
                                <i class="fa fa-rotate-left"></i>
                                Simpan dan Kembali                            </button>
                            <button class="btn btn-default cancel-button btn-flat" type="button" id="cancel-button">
                                <i class="fa fa-warning"></i>
                                Batal                            </button>
                                            </div>
                </div>
            </form>        </div>
    </div>
						</div>
					</div>
				</section>
			</div>

			
		</section>

		<!-- Vendor -->
		<!-- <script src="<?php echo base_url(); ?>assets/vendor/jquery/jquery.js"></script> -->
		<script src="<?php echo base_url(); ?>assets/vendor/jquery-browser-mobile/jquery.browser.mobile.js"></script>
		<script src="<?php echo base_url(); ?>assets/vendor/bootstrap/js/bootstrap.js"></script>
		<script src="<?php echo base_url(); ?>assets/vendor/nanoscroller/nanoscroller.js"></script>
		<script src="<?php echo base_url(); ?>assets/vendor/jquery-placeholder/jquery.placeholder.js"></script>
		
		<!-- Specific Page Vendor -->
		<script src="<?php echo base_url(); ?>assets/vendor/jquery-ui/js/jquery-ui-1.10.4.custom.js"></script>
		<script src="<?php echo base_url(); ?>assets/vendor/jquery-ui-touch-punch/jquery.ui.touch-punch.js"></script>
		<script src="<?php echo base_url(); ?>assets/vendor/jquery-appear/jquery.appear.js"></script>
		<script src="<?php echo base_url(); ?>assets/vendor/select2/select2.js"></script>
		<script type="text/javascript">
			$(".select2").select2();
		</script>
		
		
				
		<!-- flexigrid -->		
       <script type="text/javascript">

			$(document).ready(function() {
				<?php if (isset($state) AND $state=="add"): ?>

					$('#field-kabupaten').empty();
					$('#field-kabupaten').chosen().trigger('chosen:updated');
					$('#field-kabupaten').change();
					$('#field-kecamatan').empty();
					$('#field-kecamatan').chosen().trigger('chosen:updated');
					$('#field-kecamatan').change();
				<?php else: ?>
					$("[name=s4c6470b5]").hide();
					$("[name='Ubah Password']").hide();
				<?php endif ?>
				$('#field-provinsi').change(function() {					
					var selectedValue = $('#field-provinsi').val();					
					$.post('ajax_extension/kabupaten/id_provinsi/'+encodeURI(selectedValue.replace(/\//g,'_agsl_')), {}, function(data) {
					var $el = $('#field-kabupaten');
						  var newOptions = data;
						  $el.empty(); // remove old options
						  $el.append($('<option></option>').attr('value', '').text(''));
						  $.each(newOptions, function(key, value) {
						    $el.append($('<option></option>')
						       .attr('value', key).text(value));
						    });
						  //$el.attr('selectedIndex', '-1');
						  $el.chosen().trigger('chosen:updated');

    	  			},'json');
    	  			$('#field-kabupaten').change();
				});
			});
			
</script>
<script type="text/javascript">

			$(document).ready(function() {
				$('#field-kabupaten').change(function() {
					var selectedValue = $('#field-kabupaten').val();
					// alert('selectedValue'+selectedValue);
					//alert('post:'+'ajax_extension/kecamatan/id_kabupaten/'+encodeURI(selectedValue.replace(/\//g,'_agsl_')));
					$.post('ajax_extension/kecamatan/id_kabupaten/'+encodeURI(selectedValue.replace(/\//g,'_agsl_')), {}, function(data) {
					//alert('data'+data);
					var $el = $('#field-kecamatan');
						  var newOptions = data;
						  $el.empty(); // remove old options
						  $el.append($('<option></option>').attr('value', '').text(''));
						  $.each(newOptions, function(key, value) {
						    $el.append($('<option></option>')
						       .attr('value', key).text(value));
						    });
						  //$el.attr('selectedIndex', '-1');
						  $el.chosen().trigger('chosen:updated');

    	  			},'json');
    	  			$('#field-kecamatan').change();
				});
			});
			
</script>
<script src="<?php echo base_url(); ?>assets/javascripts/theme.js"></script>
		
		<!-- Theme Custom -->
		<script src="<?php echo base_url(); ?>assets/javascripts/theme.custom.js"></script>
		
		<!-- Theme Initialization Files -->
		<script src="<?php echo base_url(); ?>assets/javascripts/theme.init.js"></script>
 
	
          
	</body>
</html>