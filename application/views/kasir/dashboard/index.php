<!doctype html>
<html class="fixed">
	<head>

		<!-- Basic -->
		<meta charset="UTF-8">

		<title>Dashboard Kasir | <?php echo function_lib::get_config_value('website_name'); ?></title>
		<meta name="keywords" content="Dashboard Admin - <?php echo function_lib::get_config_value('website_name'); ?>" />
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

		<!-- Theme CSS -->
		<link rel="stylesheet" href="<?php echo base_url(); ?>assets/stylesheets/theme.css" />

		<!-- Skin CSS -->
		<link rel="stylesheet" href="<?php echo base_url(); ?>assets/stylesheets/skins/default.css" />

		<!-- Theme Custom CSS -->
		<link rel="stylesheet" href="<?php echo base_url(); ?>assets/stylesheets/theme-custom.css">

		<!-- Head Libs -->
		<script src="<?php echo base_url(); ?>assets/vendor/modernizr/modernizr.js"></script>
	</head>
	<body class="loading-overlay-showing" data-loading-overlay>
		<div class="loading-overlay light">
			<div class="loader black"></div>
		</div>
		<section class="body">

			<?php function_lib::getHeader(); ?>

			<div class="inner-wrapper">
				<!-- start: sidebar -->
				<?php function_lib::getLeftMenu(); ?>
				<!-- end: sidebar -->

				
				<section role="main" class="content-body">
					<header class="page-header">
						<h2>Dashboard</h2>
					
						<div class="right-wrapper pull-right">
							<ol class="breadcrumbs">
								<li>
									<a href="<?php echo base_url('kasir/dashboard'); ?>">
										<i class="fa fa-home"></i>
									</a>
								</li>
								<li><span>Dashboard</span></li>
							</ol>
					
							<a class="sidebar-right-toggle" data-open="sidebar-right"><i class="fa fa-chevron-left"></i></a>
						</div>
					</header>
					<div class="row">
						<div class="col-md-5">
							<div class="panel panel-primary">								
									<header class="panel-heading">
										<h2 class="panel-title">Batas Penggunaan Sistem</h2>															
										<div class="panel-actions">
											<a href="#" class="panel-action panel-action-toggle" data-panel-toggle></a>
											<a href="#" class="panel-action panel-action-dismiss" data-panel-dismiss></a>									
										</div>
									</header>								
								<div class="panel-body bg-primary">
									<div class="widget-summary">											
										<div class="widget-summary-col">
											<div id="clockdiv">
											  <div>
											    <span class="days"></span>
											    <div class="smalltext">Hari</div>
											  </div>
											  <div>
											    <span class="hours"></span>
											    <div class="smalltext">Jam</div>
											  </div>
											  <div>
											    <span class="minutes"></span>
											    <div class="smalltext">Menit</div>
											  </div>
											  <div>
											    <span class="seconds"></span>
											    <div class="smalltext">Detik</div>
											  </div>
											</div>
										</div>
									</div>
								</div>
							</div>

						</div>
							<div class="col-md-7">							
											
										<div class="row">
											<div class="col-md-4">
												<div class="panel panel-primary">
													<div class="panel-body bg-primary">
													<h4 class="title">Bunga Pinjaman</h4>
														<div class="info">
															<strong class="amount"><?php echo isset($koperasiArr[0]['bunga_pinjaman']) ? number_format($koperasiArr[0]['bunga_pinjaman'],2,'.','.')."%" : "0 %" ?></strong>		
														</div>
													</div>
												</div>
											</div>
											<div class="col-md-4">
												<div class="panel panel-primary">
												<div class="panel-body bg-tertiary">
												<h4 class="title">Biaya Simpanan</h4>
													<div class="info">
														<strong class="amount"><?php echo isset($koperasiArr[0]['biaya_simpanan']) ? number_format($koperasiArr[0]['biaya_simpanan'],2,'.','.')."%" : "0 %" ?></strong>		
													</div>
												</div>
												</div>
											</div>
											<div class="col-md-4">
												<div class="panel panel-primary">
													<div class="panel-body bg-quartenary">
												<h4 class="title">Biaya Admin</h4>
													<div class="info">
														<strong class="amount"><?php echo isset($koperasiArr[0]['biaya_administrasi']) ? number_format($koperasiArr[0]['biaya_administrasi'],2,'.','.')." %" : "0 %" ?></strong>		
													</div>
												</div>
												</div>
											</div>
										</div>
						</div>
					</div>

					<div class="row">
						<div class="col-md-9">							
							<section class="panel">
								<header class="panel-heading">
									<div class="panel-actions">
										<a href="#" class="panel-action panel-action-toggle" data-panel-toggle></a>
										<a href="#" class="panel-action panel-action-dismiss" data-panel-dismiss></a>									
									</div>
									<h2 class="panel-title">Pengguna Sistem</h2>									
									<p class="panel-subtitle">Jumlah pengguna sistem berdasarkan level user</p>
								</header>
								<div class="panel-body">
									<div class="row">
										<div class="form-group col-md-3">
											<select class="form-control" id="tahun_grafik_user">
													<option value="<?php echo date("Y",strtotime("-3 years")) ?>"><?php echo date("Y",strtotime("-3 years")) ?></option>													
													<option value="<?php echo date("Y",strtotime("-2 years")) ?>"><?php echo date("Y",strtotime("-2 years")) ?></option>													
													<option value="<?php echo date("Y",strtotime("-1 years")) ?>"><?php echo date("Y",strtotime("-1 years")) ?></option>													
													<option selected="" value="<?php echo date("Y",strtotime("0 years")) ?>"><?php echo date("Y",strtotime("0 years")) ?></option>													
											</select>				
										</div>
									</div>
									<!-- Flot: Basic -->
									<div class="chart chart-md" id="morrisArea"></div>							
								</div>
							</section>
						</div>
						<div class="col-md-3">
							<div class="row">
								<section class="panel">
									<div class="panel-body bg-primary">
										<div class="widget-summary">
											<div class="widget-summary-col widget-summary-col-icon">
												<div class="summary-icon">
													<i class="fa fa-users"></i>
												</div>
											</div>
											<div class="widget-summary-col">
												<div class="summary">
													<h4 class="title">Nasabah Hari Ini</h4>
													<div class="info">
														<strong class="amount"><?php echo isset($nasabah_hari_ini) ? number_format($nasabah_hari_ini,0,'.','.') : "0" ?></strong>
													</div>
												</div>
												<div class="summary-footer">
													<a class="text-uppercase" href="<?php echo base_url('user/nasabah'); ?>"><i class="fa fa-eye"></i> (Lihat)</a>
												</div>
											</div>
										</div>
									</div>
								</section>
							</div>
							<div class="row">
								
							<section class="panel">
								<div class="panel-body bg-tertiary">
									<div class="widget-summary">
										<div class="widget-summary-col widget-summary-col-icon">
											<div class="summary-icon">
												<i class="fa fa-users"></i>
											</div>
										</div>
										<div class="widget-summary-col">
											<div class="summary">
												<h4 class="title">Nasabah Bulan Ini</h4>
												<div class="info">
													<strong class="amount"><?php echo isset($nasabah_bulan_ini) ? number_format($nasabah_bulan_ini,0,'.','.') : "0" ?></strong>
												</div>
											</div>
											<div class="summary-footer">
												<a class="text-uppercase" href="<?php echo base_url('user/nasabah'); ?>"><i class="fa fa-eye"></i> (Lihat)</a>
											</div>
										</div>
									</div>
								</div>
							</section>
						
							</div>
							<div class="row">
								
							<section class="panel">
								<div class="panel-body bg-quartenary">
									<div class="widget-summary">
										<div class="widget-summary-col widget-summary-col-icon">
											<div class="summary-icon">
												<i class="fa fa-users"></i>
											</div>
										</div>
										<div class="widget-summary-col">
											<div class="summary">
												<h4 class="title">Nasabah Tahun Ini</h4>
												<div class="info">
													<strong class="amount"><?php echo isset($nasabah_tahun_ini) ? number_format($nasabah_tahun_ini,0,'.','.') : "0" ?></strong>
												</div>
											</div>
											<div class="summary-footer">
												<a class="text-uppercase" href="<?php echo base_url('user/nasabah'); ?>"><i class="fa fa-eye"></i> (Lihat)</a>
											</div>
										</div>
									</div>
								</div>
							</section>
						
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-9">
							
								<header class="panel-heading">
									<div class="panel-actions">
										<a href="#" class="panel-action panel-action-toggle" data-panel-toggle></a>
										<a href="#" class="panel-action panel-action-dismiss" data-panel-dismiss></a>									
									</div>
									<h2 class="panel-title">Grafik Transaksi</h2>									
									<p class="panel-subtitle">Data Grafik Harian Transaksi Pinjaman dan Simpanan nasabah dari berdasarkan koperasi</p>
								</header>
								<div class="panel-body">
									<div class="row">
										<div class="form-group col-md-3 hidden">
											<select class="form-control" id="select_koperasi">
												<option value="">Semua Koperasi</option>												
											</select>				
										</div>
									</div>
									<!-- Flot: Basic -->
									<div class="chart chart-md" id="morrisAreaTransaksi"></div>							
								</div>
						</div>
						<div class="col-md-3">
							<div class="row">
								<section class="panel">
									<div class="panel-body bg-primary">
										<div class="widget-summary">											
											<div class="widget-summary-col">												
												<div class="summary">
													<h5 class="title">Tot. Agsrn Pinjaman</h5>
													<div class="info">
														<strong class="amount"><?php echo isset($angsuran_pinjaman) ? "Rp. ".number_format($angsuran_pinjaman,0,'.','.') : "Rp .0" ?></strong>
													</div>
												</div>
												<div class="summary-footer">
													<a class="text-uppercase" href="<?php echo base_url('riwayat_pinjaman'); ?>"><i class="fa fa-eye"></i> (Lihat)</a>
												</div>												
												<div class="summary">
													<h5 class="title">Agsrn Simpanan Today</h5>
													<div class="info">
														<strong class="amount"><?php echo isset($angsuran_simpanan_today) ? "Rp. ".number_format($angsuran_simpanan_today,0,'.','.') : "Rp. 0" ?></strong>
													</div>
												</div>												
											</div>
										</div>
									</div>
								</section>
							</div>
							<div class="row">
								<section class="panel">
									<div class="panel-body bg-tertiary">
										<div class="widget-summary">											
											<div class="widget-summary-col">	
											<div class="summary">
													<h5 class="title">Tot. Agsrn Simpanan</h5>
													<div class="info">
														<strong class="amount"><?php echo isset($angsuran_simpanan) ? "Rp. ".number_format($angsuran_simpanan,0,'.','.') : "Rp. 0" ?></strong>
													</div>
												</div>
												<div class="summary-footer">
													<a class="text-uppercase" href="<?php echo base_url('riwayat_simpanan'); ?>"><i class="fa fa-eye"></i> (Lihat)</a>
												</div>											
												<div class="summary">
													<h5 class="title">Agsrn Pinjaman Today</h5>
													<div class="info">
														<strong class="amount"><?php echo isset($angsuran_pinjaman_today) ? "Rp. ".number_format($angsuran_pinjaman_today,0,'.','.') : "Rp. 0" ?></strong>
													</div>
												</div>																								
												
											</div>
										</div>
									</div>
								</section>
							</div>
						</div>
					</div>
					
					<!-- end: page -->
				</section>
			</div>

			<?php $this->load->view('kasir/right_bar'); ?>
		</section>

		<!-- Vendor -->
		<script src="<?php echo base_url(); ?>assets/vendor/jquery/jquery.js"></script>
		<script src="<?php echo base_url(); ?>assets/vendor/jquery-browser-mobile/jquery.browser.mobile.js"></script>
		<script src="<?php echo base_url(); ?>assets/vendor/bootstrap/js/bootstrap.js"></script>
		<script src="<?php echo base_url(); ?>assets/vendor/nanoscroller/nanoscroller.js"></script>
		<script src="<?php echo base_url(); ?>assets/vendor/bootstrap-datepicker/js/bootstrap-datepicker.js"></script>
		<script src="<?php echo base_url(); ?>assets/vendor/magnific-popup/magnific-popup.js"></script>
		<script src="<?php echo base_url(); ?>assets/vendor/jquery-placeholder/jquery.placeholder.js"></script>
		
		
		
		<!-- Theme Base, Components and Settings -->
		<script src="<?php echo base_url(); ?>assets/javascripts/theme.js"></script>
		
		<!-- Theme Custom -->
		<script src="<?php echo base_url(); ?>assets/javascripts/theme.custom.js"></script>
		
		<!-- Theme Initialization Files -->
		<script src="<?php echo base_url(); ?>assets/javascripts/theme.init.js"></script>
		<script src="<?php echo base_url(); ?>assets/vendor/raphael/raphael.js"></script>
		<script src="<?php echo base_url(); ?>assets/vendor/morris/morris.js"></script>

		<script>
			var morrisAreaData = null;
			var morrisAreaDataTransaksi = [];
			var grafik_user = null;
			var grafik_transaksi = null;
			$(document).ready(function() {
				load_grafik_user();
				load_grafik_transaksi();
				grafik_user = Morris.Area({
					resize: true,
					element: 'morrisArea',
					data: morrisAreaData,
					parseTime: false,
					xkey: 'y',
					ykeys: ['a'],
					labels: ['Nasabah'],
					lineColors: ['#0088cc', '#2baab1'],
					fillOpacity: 0.7,
					hideHover: true
				});
				grafik_transaksi = Morris.Area({
					resize: true,
					element: 'morrisAreaTransaksi',
					data: morrisAreaDataTransaksi,
					parseTime: false,
					xkey: 'y',
					ykeys: ['a', 'b'],
					labels: ['Angsuran Pinjaman', 'Angsuran Simpanan'],
					lineColors: ['#0088cc', '#2baab1'],
					fillOpacity: 0.7,
					hideHover: true
				});
			
			});

			$("#tahun_grafik_user").on('change', function(event) {
				event.preventDefault();
				load_grafik_user();
				grafik_user.setData(morrisAreaData);
			});
			$("#select_koperasi").on('change', function(event) {
				event.preventDefault();
				load_grafik_transaksi();
				grafik_transaksi.setData(morrisAreaDataTransaksi);
			});
			function load_grafik_user(){
				morrisAreaData = [];
				$.ajax({
				url: '<?php echo base_url('kasir/dashboard/get_grafik_user') ?>',
				type: 'POST',
				dataType: 'JSON',
				data: {tahun: $("#tahun_grafik_user").val()},
				success : function(response){					
					data_nasabah = response['nasabah'];							
					
						morrisAreaData = [{
							y: 'Januari',
							a: data_nasabah[1],							
						}, {
							y: 'Februari',
							a: data_nasabah[2],							
						}, {
							y: 'Maret',
							a: data_nasabah[3],							
						}, {
							y: 'April',
							a: data_nasabah[4],							
						}, {
							y: 'Mei',
							a: data_nasabah[5],							
						}, {
							y: 'Juni',
							a: data_nasabah[6],							
						}, {
							y: 'Juli',
							a: data_nasabah[7],							
						}, {
							y: 'Agustus',
							a: data_nasabah[8],							
						}, {
							y: 'September',
							a: data_nasabah[9],
						}, {						
							y: 'Oktober',
							a: data_nasabah[10],							
						}, {
							y: 'November',
							a: data_nasabah[11],							
						},{
							y: 'Desember',
							a: data_nasabah[12],							
						}];					
				
				},
				async: false
			})
			}	
			function load_grafik_transaksi(){
				morrisAreaDataTransaksi = [];
				$.ajax({
				url: '<?php echo base_url('kasir/dashboard/get_grafik_transaksi') ?>',
				type: 'POST',
				dataType: 'JSON',				
				success : function(response){
					data_riwayat_pinjaman = response['riwayat_pinjaman'];							
					data_riwayat_simpanan = response['riwayat_simpanan'];							
					var month = <?php echo date("t"); ?>;
					for (var i = 0; i < month ; i++){
						morrisAreaDataTransaksi.push({
							y : "H"+parseInt(i+1),
							a : data_riwayat_pinjaman[i+1],
							b : data_riwayat_simpanan[i+1],
						});
					}				
				
				},
				async: false
			})
			}			
		
				
			function getTimeRemaining(endtime) {
			  var t = Date.parse(endtime) - Date.parse(new Date());
			  var seconds = Math.floor((t / 1000) % 60);
			  var minutes = Math.floor((t / 1000 / 60) % 60);
			  var hours = Math.floor((t / (1000 * 60 * 60)) % 24);
			  var days = Math.floor(t / (1000 * 60 * 60 * 24));
			  return {
			    'total': t,
			    'days': days,
			    'hours': hours,
			    'minutes': minutes,
			    'seconds': seconds
			  };
			}

			function initializeClock(id, endtime) {
			  var clock = document.getElementById(id);
			  var daysSpan = clock.querySelector('.days');
			  var hoursSpan = clock.querySelector('.hours');
			  var minutesSpan = clock.querySelector('.minutes');
			  var secondsSpan = clock.querySelector('.seconds');

			  function updateClock() {
			    var t = getTimeRemaining(endtime);

			    daysSpan.innerHTML = t.days;
			    hoursSpan.innerHTML = ('0' + t.hours).slice(-2);
			    minutesSpan.innerHTML = ('0' + t.minutes).slice(-2);
			    secondsSpan.innerHTML = ('0' + t.seconds).slice(-2);

			    if (t.total <= 0) {
			      clearInterval(timeinterval);
			    }
			  }

			  updateClock();
			  var timeinterval = setInterval(updateClock, 1000);
			}

			var deadline = new Date("<?php echo $koperasiArr[0]['tgl_jatuh_tempo_pembayaran_sistem']; ?>");
			initializeClock('clockdiv', deadline);
										
		</script>		
		<script src="<?php echo base_url(); ?>assets/javascripts/dashboard/examples.dashboard.js"></script>

	</body>
</html>