<!doctype html>
<html class="fixed">
	<head>

		<!-- Basic -->
		<meta charset="UTF-8">

		<title>Dashboard Pengurus Barang | <?php echo function_lib::get_config_value('website_name'); ?></title>
		<meta name="keywords" content="Dashboard Pengurus Barang - <?php echo function_lib::get_config_value('website_name'); ?>" />
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
	<body>
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
									<a href="<?php echo base_url('pengurus_barang/dashboard'); ?>">
										<i class="fa fa-home"></i>
									</a>
								</li>
								<li><span>Dashboard</span></li>
							</ol>
					
							<a class="sidebar-right-toggle" ><i class="fa fa-chevron-left"></i></a>
						</div>
					</header>

					<div class="row"></div>										
					
					<div class="row"></div>
					
				</section>
			</div>

			<?php $this->load->view('pengurus_barang/right_bar'); ?>
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
<!-- Vendor -->		
		
		
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
					ykeys: ['a', 'b','c','d'],
					labels: ['Owner', 'Kasir','Kolektor','Nasabah'],
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
				url: '<?php echo base_url('pengurus_barang/dashboard/get_grafik_user') ?>',
				type: 'POST',
				dataType: 'JSON',
				data: {tahun: $("#tahun_grafik_user").val()},
				success : function(response){
					data_koperasi = response['koperasi'];							
					data_pengurus_barang = response['pengurus_barang'];							
					data_kolektor = response['kolektor'];							
					data_nasabah = response['nasabah'];							
					
						morrisAreaData = [{
							y: 'Januari',
							a: data_koperasi[1],
							b: data_pengurus_barang[1],
							c: data_kolektor[1],
							d: data_nasabah[1],							
						}, {
							y: 'Februari',
							a: data_koperasi[2],
							b: data_pengurus_barang[2],
							c: data_kolektor[2],
							d: data_nasabah[2],							
						}, {
							y: 'Maret',
							a: data_koperasi[3],
							b: data_pengurus_barang[3],
							c: data_kolektor[3],
							d: data_nasabah[3],							
						}, {
							y: 'April',
							a: data_koperasi[4],
							b: data_pengurus_barang[4],
							c: data_kolektor[4],
							d: data_nasabah[4],							
						}, {
							y: 'Mei',
							a: data_koperasi[5],
							b: data_pengurus_barang[5],
							c: data_kolektor[5],
							d: data_nasabah[5],							
						}, {
							y: 'Juni',
							a: data_koperasi[6],
							b: data_pengurus_barang[6],
							c: data_kolektor[6],
							d: data_nasabah[6],							
						}, {
							y: 'Juli',
							a: data_koperasi[7],
							b: data_pengurus_barang[7],
							c: data_kolektor[7],
							d: data_nasabah[7],							
						}, {
							y: 'Agustus',
							a: data_koperasi[8],
							b: data_pengurus_barang[8],
							c: data_kolektor[8],
							d: data_nasabah[8],							
						}, {
							y: 'September',
							a: data_koperasi[9],
							b: data_pengurus_barang[9],
							c: data_kolektor[9],
							d: data_nasabah[9],							
						}, {
							y: 'Oktober',
							a: data_koperasi[10],
							b: data_pengurus_barang[10],
							c: data_kolektor[10],
							d: data_nasabah[10],														
						}, {
							y: 'November',
							a: data_koperasi[11],
							b: data_pengurus_barang[11],
							c: data_kolektor[11],
							d: data_nasabah[11],														
						},{
							y: 'Desember',
							a: data_koperasi[12],
							b: data_pengurus_barang[12],
							c: data_kolektor[12],
							d: data_nasabah[12],							
						}];					
				
				},
				async: false
			})
			}	
			function load_grafik_transaksi(){
				morrisAreaDataTransaksi = [];
				$.ajax({
				url: '<?php echo base_url('pengurus_barang/dashboard/get_grafik_transaksi') ?>',
				type: 'POST',
				dataType: 'JSON',
				data: {id_owner: $("#select_koperasi").val()},
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
			
	

					
									
		</script>
		<script src="<?php echo base_url(); ?>assets/javascripts/dashboard/examples.dashboard.js"></script>

	</body>
</html>