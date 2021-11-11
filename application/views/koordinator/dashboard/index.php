<!doctype html>
<html class="fixed">
	<head>

		<!-- Basic -->
		<meta charset="UTF-8">

		<title>Dashboard Koordinator | <?php echo function_lib::get_config_value('website_name'); ?></title>
		<meta name="keywords" content="Dashboard Koordinator - <?php echo function_lib::get_config_value('website_name'); ?>" />
		<meta name="description" content="<?php echo function_lib::get_config_value('website_seo'); ?>">
		<meta name="author" content="okler.net">

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

		<!-- Theme Custom CSS -->
		<link rel="stylesheet" href="<?php echo base_url(); ?>assets/stylesheets/theme-custom.css">

		<!-- Head Libs -->
		<script src="<?php echo base_url(); ?>assets/vendor/modernizr/modernizr.js"></script>
	</head>
	<body>
		<style type="text/css">
			#map { 
			  height: 75vh;
			}
		</style>
		<section class="body">

			<?php $this->load->view('koordinator/header'); ?>

			<div class="inner-wrapper" style="padding-top: 60px!important;">
				<!-- start: sidebar -->
				<?php function_lib::getLeftMenu(); ?>
				<!-- end: sidebar -->

				<section role="main" class="content-body">
					<div class="">
					
					<div class="row">
						<div class="col-md-12">
							<section class="panel">
								<header class="panel-heading">
									<div class="panel-actions">
										<a href="#" class="panel-action panel-action-toggle" data-panel-toggle=""></a>
										<a href="#" class="panel-action panel-action-dismiss" data-panel-dismiss=""></a>									
									</div>
									<h2 class="panel-title">Grafik Sebaran Aset Tanah</h2>									
								</header>
								<div class="panel-body">
									<div class="row">
										
											<div class="col-md-2">
												<div class="form-group">
													<label>Nama Aset</label>
													<input type="text" class="form-control" name="nama_aset" id="nama_aset">
												</div>
											</div>
											<div class="col-md-3">
												<div class="form-group">
													<label>OPD</label>
													<select class="form-control select2" id="id_opd" name="id_opd">
														
														<?php foreach ($dataOpd as $key => $value): ?>
															<option value="<?php echo $value['id_opd'] ?>"><?php echo $value['label_opd']; ?></option>
														<?php endforeach ?>
													</select>
												</div>
											</div>
											<div class="col-md-3">
												<div class="form-group">
													<label>Kecamatan</label>
													<select class="form-control select2" id="kecamatan" name="Kecamatan">
														<option value="">Pilih Kecamatan</option>
														<?php foreach ($dataKecamatan as $key => $value): ?>
															<option value="<?php echo $value['id'] ?>"><?php echo $value['text'] ?></option>
														<?php endforeach ?>
													</select>
												</div>
											</div>
											<div class="col-md-3">
												<div class="form-group">
													<label>Desa</label>
													<select class="form-control select2" id="desa" name="desa">
														<option value="">Pilih Desa</option>
													</select>
												</div>
											</div>
											<div class="col-md-1">
												<div class="form-group">
													<button id="btn-cari" class="btn btn-info m-t-25">Cari</button>
												</div>
											</div>

									</div>
									<br>
									<div class="alert alert-warning">
										<p>Maksimal titik yang dapat ditampilkan adalah 500 data, gunakan Filter di atas untuk menampilkan titik Aset Tanah sesuai kebutuhan</p>
									</div>
									<div>
										<div id="map"></div>
									</div>

								</div>
							</section>
							
						</div>
					</div>
					<div class="row">
						<div class="col-md-1 col-xs-0"></div>
						<div class="col-md-5 col-xs-12">							
							<section class="panel">
								<header class="panel-heading">
									<div class="panel-actions">
										<a href="#" class="panel-action panel-action-toggle" data-panel-toggle=""></a>
										<a href="#" class="panel-action panel-action-dismiss" data-panel-dismiss=""></a>									
									</div>
									<h2 class="panel-title">Grafik Status Aset</h2>									
								</header>
								<div class="panel-body">
									
									<div>
										<canvas id="chartStatusAset"></canvas>
									</div>

								</div>
							</section>
						</div>
						<br>
						<div class="col-md-5 col-xs-12">							
							<section class="panel">
								<header class="panel-heading">
									<div class="panel-actions">
										<a href="#" class="panel-action panel-action-toggle" data-panel-toggle=""></a>
										<a href="#" class="panel-action panel-action-dismiss" data-panel-dismiss=""></a>									
									</div>
									<h2 class="panel-title">Grafik Status Verifikasi</h2>									
								</header>
								<div class="panel-body">
									<div class="row">
										
									</div>
									<div>
										<canvas id="chartStatusVerifikasi"></canvas>
									</div>

								</div>
							</section>
						</div>
						<div class="col-md-12 col-xs-12">							
							<section class="panel">
								<header class="panel-heading">
									<div class="panel-actions">
										<a href="#" class="panel-action panel-action-toggle" data-panel-toggle=""></a>
										<a href="#" class="panel-action panel-action-dismiss" data-panel-dismiss=""></a>									
									</div>
									<h2 class="panel-title">Grafik Jumlah Aset di OPD Sendiri</h2>									
								</header>
								<div class="panel-body">
									<div class="row">
										
									</div>
									<div>
										<canvas id="chartAsetOpd"></canvas>
									</div>

								</div>
							</section>
							
						</div>
						
 
					</div>										
					
					
					
					</div>
				</section>
			</div>

			<?php $this->load->view('koordinator/right_bar'); ?>
			
		</section>

		<!-- Vendor -->
		<script src="<?php echo base_url(); ?>assets/vendor/jquery/jquery.js"></script>
		<script src="<?php echo base_url(); ?>assets/vendor/jquery-browser-mobile/jquery.browser.mobile.js"></script>
		<script src="<?php echo base_url(); ?>assets/vendor/bootstrap/js/bootstrap.js"></script>
		<script src="<?php echo base_url(); ?>assets/vendor/nanoscroller/nanoscroller.js"></script>
		<script src="<?php echo base_url(); ?>assets/vendor/bootstrap-datepicker/js/bootstrap-datepicker.js"></script>
		<script src="<?php echo base_url(); ?>assets/vendor/magnific-popup/magnific-popup.js"></script>
		<script src="<?php echo base_url(); ?>assets/vendor/jquery-placeholder/jquery.placeholder.js"></script>
		<script src="<?php echo base_url(); ?>assets/vendor/select2/select2.js"></script>
		
		
		
		<!-- Theme Base, Components and Settings -->
		<script src="<?php echo base_url(); ?>assets/javascripts/theme.js"></script>
		
		<!-- Theme Custom -->
		<script src="<?php echo base_url(); ?>assets/javascripts/theme.custom.js"></script>
		
		<!-- Theme Initialization Files -->
		<script src="<?php echo base_url(); ?>assets/javascripts/theme.init.js"></script>

		<!-- Vendor -->		
		<link rel="stylesheet" href="https://unpkg.com/leaflet@1.5.1/dist/leaflet.css" />
		<script type="text/javascript" src="https://unpkg.com/leaflet@1.5.1/dist/leaflet.js"></script>
		<script type="text/javascript">
			$(".select2").select2();
			window.onload = function() {
			  $("#btn-cari").click();
			};
			jQuery(document).ready(function($) {

				var map = L.map('map', {
				    // Set latitude and longitude of the map center (required)
				    center: [-7.667370, 109.652153],
				    // Set the initial zoom level, values 0-18, where 0 is most zoomed-out (required)
				    zoom: 13
				});
				var tiles = new L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
					attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors',
					minZoom: '6'}).addTo(map);
				marker = L.marker(
									[0, 0],
									{ 
										draggable: true,
										title: "",
										opacity: 0
									});

				$("#btn-cari").click();



				$("#kecamatan").change(function(event) {
					var id_kecamatan = event.val;
					$.ajax({
						url: '<?php echo base_url('koordinator/dashboard/get_desa_by_kecamatan/') ?>'+id_kecamatan,
						type: 'GET',
						dataType: 'JSON',
						success : function(response){
							$('#desa').empty().trigger("change");
							var newOption = ['<option value="" >Pilih Desa</option>'];            
					        for(var i=0;i<response.length;i++){                            
					              newOption.push('<option value="'+ response[i]['id'] +'">'+ response[i]['text'] +'</option>');
					        }               

					        $('#desa').select2({
					            minimumInputLength: 0,                        
					        }).append(newOption);
						}
					})
				});
				
				$("#btn-cari").click(function(event) {
					
					$(".leaflet-marker-pane").children('.leaflet-marker-icon').remove();
					$(".leaflet-shadow-pane").children('*').remove();
					event.preventDefault();
					$("#btn-cari").attr('disabled',true);
					var nama_aset = $("#nama_aset").val();
					var id_opd = $("#id_opd").val();
					var id_kecamatan = $("#kecamatan").val();
					var id_desa = $("#desa").val();
					$.ajax({
						
						url: '<?php echo base_url('koordinator/dashboard/cari_aset') ?>',
						type: 'POST',
						dataType: 'json',
						data: {nama_aset: nama_aset,id_opd : id_opd,id_kecamatan : id_kecamatan, id_desa : id_desa},
						success :function(response){
							$("#btn-cari").attr('disabled',false);
							var points = [["",null,null]];
							for (var i = 0; i < response.length; i++) {
								if (response[i]["longitude"] != null && response[i]["latitude"] != null) {
									marker = L.marker(
									[response[i]["latitude"], response[i]["longitude"]],
									{ 
										draggable: true,
										title: response[i]['nama_aset'],
										opacity: 0.75
									});
									marker.addTo(map).bindPopup("<h3><b>"+response[i]['nama_aset']+"</b></h3><p><br>"+response[i]['keterangan']+"</p><p>Alamat : "+response[i]["alamat"]+"<p/><br><a class='btn btn-xs btn-info' href='<?php echo base_url('aset/index/read/') ?>"+response[i]['id_aset']+"'>Detail</a>") .openPopup();

								}
							}

						}
					})
					
					
				});

				
				


				

				
				
			});
			

		</script>
		
		
		<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
		<script >
			const dataStatus = {
			  labels: [
			    'Idle',
			    'Non Idle',
			  ],
			  datasets: [{
			    label: 'Status Aset',
			    data: [<?php echo isset($count_aset_idle) ? $count_aset_idle : 0; ?>, <?php echo isset($count_aset_non_idle) ? $count_aset_non_idle : 0; ?>],
			    backgroundColor: [
			      'rgb(54, 162, 235)',
			      'rgb(255, 99, 132)',
			    ],
			    hoverOffset: 4
			  }]
			};
			const dataStatusVerifikasi = {
			  labels: [
			    'Valid',
			    'Tidak Valid',
			    'Sedang Diverifikasi',
			  ],
			  datasets: [{
			    label: 'Status Verifikasi Aset',
			    data: [<?php echo isset($count_aset_valid) ? $count_aset_valid : 0; ?>, <?php echo isset($count_aset_tidak_valid) ? $count_aset_tidak_valid : 0; ?>,<?php echo isset($count_aset_sedang_diverifikasi) ? $count_aset_sedang_diverifikasi : 0; ?>],
			    backgroundColor: [
			      'rgb(54, 162, 235)',
			      'rgb(255, 99, 132)',
			      'rgb(255, 205, 86)',
			    ],
			    hoverOffset: 4
			  }]
			};
			const configStatus = {
			  type: 'pie',
			  data: dataStatus,
			};
			const configStatusVerifikasi = {
			  type: 'pie',
			  data: dataStatusVerifikasi,
			};
			var myChart = new Chart(
			    $("#chartStatusAset"),
			    configStatus
			);
			var myChart = new Chart(
			    $("#chartStatusVerifikasi"),
			    configStatusVerifikasi
			);


			
			const barLabels = [
			<?php foreach ($count_aset_by_opd as $key => $value): ?>
			    	'<?php echo $value['label_opd']; ?>',
			    <?php endforeach ?>
			];
			const barData = {
			  labels: barLabels,
			  datasets: [{
			    label: "Grafik Jumlah Aset di OPD Sendiri",
			    data: [
			    <?php foreach ($count_aset_by_opd as $key => $value): ?>
			    	<?php echo $value['total']; ?>,
			    <?php endforeach ?>
			    ],
			    backgroundColor: [
			    <?php for ($i = 0; $i < count($count_aset_by_opd); $i++): ?>
			    	random_rgba(),
			    <?php endfor ?>
			      
			    ],
			    borderColor: [
			      <?php for ($i = 0; $i < count($count_aset_by_opd); $i++): ?>
			    	random_rgba(),
			    <?php endfor ?>
			    ],
			    borderWidth: 1
			  }]
			};
			const barConfig = {
			  type: 'bar',
			  data: barData,
			  options: {
			    scales: {
			      y: {
			        beginAtZero: true
			      }
			    }
			  },
			};
			var myChart = new Chart(
			    $("#chartAsetOpd"),
			    barConfig
			  );
			function random_rgba() {
			    var o = Math.round, r = Math.random, s = 255;
			    return 'rgba(' + o(r()*s) + ',' + o(r()*s) + ',' + o(r()*s) + ',' + r().toFixed(1) + ')';
			}
		</script>

		

	</body>
</html>