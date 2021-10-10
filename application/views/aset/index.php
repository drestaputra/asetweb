<!doctype html>
<html class="fixed">
	<head>

		<!-- Basic -->
		<meta charset="UTF-8">
		<title>Aset | <?php echo function_lib::get_config_value('website_name'); ?></title>
		<meta name="keywords" content="Dashboard Admin - <?php echo function_lib::get_config_value('website_name'); ?>" />
		<meta name="description" content="<?php echo function_lib::get_config_value('website_seo'); ?>">
		<meta name="author" content="Drestaputra ">

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


		<!-- Head Libs -->
		<script src="<?php echo base_url(); ?>assets/vendor/modernizr/modernizr.js"></script>
        <?php 
        foreach($css_files as $file): ?>
            <link type="text/css" rel="stylesheet" href="<?php echo $file; ?>" />
            
        <?php endforeach; ?>
        <?php foreach($js_files as $file): ?>
            <script src="<?php echo $file; ?>"></script>
            
        <?php endforeach; ?>        >
        
	</head>
	<body>
		<style type="text/css">
			#map { 
			  height: 40vh;
			}
		</style>
		<section class="body">

			<?php function_lib::getHeader(); ?>

			<div class="inner-wrapper">
				<!-- start: sidebar -->
				<?php function_lib::getLeftMenu(); ?>
				<!-- end: sidebar -->

				<section role="main" class="content-body">
					<header class="page-header">
						<h2>Aset</h2>
					
						<div class="right-wrapper pull-right">
							<ol class="breadcrumbs">
								<li>
									<a href="<?php echo base_url(); ?>">
										<i class="fa fa-home"></i>
									</a>
								</li>
								<li><span>Aset</span></li>
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
                            <h3 class="panel-title">Data Aset</h3>
                        </div>
						<div class="panel-body">
                            <div class="alert " style="display: none;">
                                <p class="msg"></p>
                            </div>
                            <?php if ($level != "pengurus_barang" AND $state_data == "list" OR $state_data == "success"): ?>
								<div class="row m-b-10">
									<div class="col-md-12">
										<a href="<?php echo base_url('aset/import') ?>" class="btn btn-success pull-left"><i class="fa fa-file-excel-o"></i> Import Data</a>
									</div>
								</div>
							<?php endif ?>
							<?php echo $output; ?>
						</div>
					</div>
				</section>
			</div>

			<?php $this->load->view('admin/right_bar'); ?>
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

		<!-- location picker -->
		<link rel="stylesheet" href="https://unpkg.com/leaflet@1.5.1/dist/leaflet.css" />
		<script type="text/javascript" src="https://unpkg.com/leaflet@1.5.1/dist/leaflet.js"></script>

		<script src="https://cdn-geoweb.s3.amazonaws.com/esri-leaflet/0.0.1-beta.5/esri-leaflet.js"></script>
		<script src="https://cdn-geoweb.s3.amazonaws.com/esri-leaflet-geocoder/0.0.1-beta.5/esri-leaflet-geocoder.js"></script>
		<link rel="stylesheet" type="text/css" href="https://cdn-geoweb.s3.amazonaws.com/esri-leaflet-geocoder/0.0.1-beta.5/esri-leaflet-geocoder.css">

		<script type="text/javascript">


			$(document).ready(function() {
				<?php if (isset($state) AND $state=="add"): ?>
					$('#field-id_kecamatan').empty();
					$('#field-id_kecamatan').chosen().trigger('chosen:updated');
					$('#field-id_kecamatan').change();
					$('#field-id_desa').empty();
					$('#field-id_desa').chosen().trigger('chosen:updated');
					$('#field-id_desa').change();
				<?php endif ?>
				<?php if ($state_data == "add" OR $state_data == "edit"): ?>
					
					$(".longitude_form_group").after("<div class='form-group'><div id='map'></div></div>")
					var map = L.map('map', {
					    // Set latitude and longitude of the map center (required)
					    center: [-7.667370, 109.652153],
					    // Set the initial zoom level, values 0-18, where 0 is most zoomed-out (required)
					    zoom: 13
					});
					var tiles = new L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
					attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors',
					minZoom: '10'}).addTo(map);

					map.on('click', function(ev) {
					  $("[name='latitude']").val(ev.latlng.lat);
					  $("[name='longitude']").val(ev.latlng.lng);
					  if (typeof pin == "object") {
					    pin.setLatLng(ev.latlng);
					  }
					  else {
					    pin = L.marker(ev.latlng,{ riseOnHover:true,draggable:true });
					    pin.addTo(map);
					    pin.on('drag',function(ev) {
					      $("[name='latitude']").val(ev.latlng.lat);
					      $("[name='longitude']").val(ev.latlng.lng);
					    });
					  }
					});

				// tambah fungsi search
				var greenIcon = new L.Icon({
					iconUrl: 'https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-2x-green.png',
					shadowUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/0.7.7/images/marker-shadow.png',
					iconSize: [25, 41],
					iconAnchor: [12, 41],
					popupAnchor: [1, -34],
					shadowSize: [41, 41]
				});

				L.marker([51.5, -0.09], {icon: greenIcon}).addTo(map);
				L.control.scale().addTo(map);

				var searchControl = new L.esri.Controls.Geosearch().addTo(map);

				var results = new L.LayerGroup().addTo(map);
				searchControl.on('results', function(data){
				    results.clearLayers();
				    for (var i = data.results.length - 1; i >= 0; i--) {
				      results.addLayer(L.marker(data.results[i].latlng,{icon: greenIcon}));
				    }
				});
				<?php endif ?>
			
			$('#field-id_kecamatan').change(function() {
					var selectedValue = $('#field-id_kecamatan').val();		
					$.post('ajax_extension/id_desa/id_kecamatan/'+encodeURI(selectedValue.replace(/\//g,'_agsl_')), {}, function(data) {
					var $el = $('#field-id_desa');
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
    	  			$('#field-id_desa').change();
				});
			});
			

			
		</script>
		<script src="<?php echo base_url(); ?>assets/javascripts/theme.js"></script>
		
		<!-- Theme Custom -->
		<script src="<?php echo base_url(); ?>assets/javascripts/theme.custom.js"></script>
		
		<!-- Theme Initialization Files -->
		<script src="<?php echo base_url(); ?>assets/javascripts/theme.init.js"></script>
		<script src="<?php echo base_url(); ?>assets/vendor/select2/select2.js"></script>
		<script type="text/javascript">
			function searchCustom(){
					$("[name=sea67d6ad]").parent().html(
						"<select style='width:200px;' class='form-control searchable-input searchable-input-select' name='id_opd_admin'><option value=''>Semua OPD</option><?php foreach ($dataOpd as $key => $value): ?><option value='<?php echo $value['id_opd'] ?>'><?php echo $value['label_opd'] ?></option><?php endforeach ?></select>"
					);
					$(".searchable-input-select").select2();
				
					
				}
			searchCustom();
		</script>
		
		
		
	
          
	</body>
</html>