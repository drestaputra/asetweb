<!doctype html>
<html class="fixed">
	<head>

		<!-- Basic -->
		<meta charset="UTF-8">
		<title>Pinjaman | <?php echo function_lib::get_config_value('website_name'); ?></title>
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
		<link rel="stylesheet" href="<?php echo base_url(); ?>assets/vendor/fuelux/css/spinner2.css" />

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
		<section class="body">

			<?php function_lib::getHeader(); ?>

			<div class="inner-wrapper">
				<!-- start: sidebar -->
				<?php function_lib::getLeftMenu(); ?>
				<!-- end: sidebar -->

				<section role="main" class="content-body">
					<header class="page-header">
						<h2>Pinjaman</h2>
					
						<div class="right-wrapper pull-right">
							<ol class="breadcrumbs">
								<li>
									<a href="index.html">
										<i class="fa fa-home"></i>
									</a>
								</li>
								<li><span>Pinjaman</span></li>
							</ol>
					
							<a class="sidebar-right-toggle" data-open="sidebar-right"><i class="fa fa-chevron-left"></i></a>
						</div>
					</header>
					<div class="row">
						<?php if (trim($this->input->get('status'))!=""): ?>
                                <?php echo function_lib::response_notif($this->input->get('status'),$this->input->get('msg')); ?>
                            <?php endif ?>
                            
						
					</div>
					<div class="panel panel-default">

                        <div class="panel-heading">
                            <h3 class="panel-title">Data Pinjaman</h3>
                        </div>
						<div class="panel-body">
                            <div class="alert " style="display: none;">
                                <p class="msg"></p>
                            </div>
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
				
		
		
		<script src="<?php echo base_url(); ?>assets/javascripts/theme.js"></script>
		
		<!-- Theme Custom -->
		<script src="<?php echo base_url(); ?>assets/javascripts/theme.custom.js"></script>
		
		<!-- Theme Initialization Files -->
		<script src="<?php echo base_url(); ?>assets/javascripts/theme.init.js"></script>		
		<!-- flexigrid -->		
       	<script type="text/javascript">
			$(function() {
				
			$("th[data-order-by=detail_pinjaman]").prop("onclick", null).off("click");
			$(".searchable-input[name=detail_pinjaman]").hide();
			$("th[data-order-by=sf552026b]").prop("onclick", null).off("click");
			$(".searchable-input[name=sf552026b]").hide();
			$("th[data-order-by=sa40336d8]").prop("onclick", null).off("click");
			$(".searchable-input[name=sa40336d8]").hide();
			$(".searchable-input[name=sf18c7b51]").val(<?php echo isset($_GET['id_pinjaman']) ? $_GET['id_pinjaman'] : ""; ?>);
			$('.gc-refresh').trigger('click');
			});
			
			$(".spinner").delegate('.spinner .btn:first-of-type','click',function() {
			    $('.spinner input').val( parseInt($('.spinner input').val(), 10) + 10);
			});
		</script> 				
		<?php if ($this->uri->segment(3)=="add"): ?>
			<script type="text/javascript">
				$( ".id_pinjaman_form_group" ).after( "<div class='detail_id_pinjaman'></div>" );				
				$("[name=id_pinjaman]").on("change", function(){
					var id_pinjaman = $(this).val();
					$.ajax({
						url: '<?php echo base_url('riwayat_pinjaman/get_detail_pinjaman/') ?>'+id_pinjaman,
						type: 'get',
						dataType: 'json',						
						success :function(response){
							if (response.status == 200) {
								var html_angsuran = '<div class="input-group spinner"><input id="field-jumlah_riwayat_pembayaran" name="jumlah_riwayat_pembayaran" type="text" value="0" class="numeric form-control" maxlength="11"><div class="input-group-btn-vertical"><button class="btn btn-default" id="spinner_up" type="button"><i class="fa fa-caret-up"></i></button><button class="btn btn-default" id="spinner_down" type="button"><i class="fa fa-caret-down"></i></button></div></div>';
								$(".detail_id_pinjaman").html('<div class="form-group"><label class="col-sm-2 control-label">Detail Pinjaman</label><div class="col-sm-5"><div class="panel panel-warning"><div class="panel-body"><table class="table table-bordered"><tr><th>Nasabah</th><td>: '+response.nama_nasabah+'</td></tr><tr><th>Angsuran</th><td>: Rp. '+response.jumlah_perangsuran+' X '+response.lama_angsuran+'/'+response.periode_angsuran+'</td></tr><tr><th>Jumlah terbayar</th><td>: Rp. '+response.jumlah_terbayar+'</td></tr><tr><th>Jumlah Pinjaman</th><td>: Rp. '+response.jumlah_pinjaman_setelah_bunga+'</td></tr></table></div></div></div></div>');
								$("#field-jumlah_riwayat_pembayaran").val(response.jumlah_perangsuran);
								$(".jumlah_riwayat_pembayaran_form_group .col-sm-5").html(html_angsuran).delegate( "#spinner_up", "click", function(){
									var jumlah_kekurangan = parseFloat(response.jumlah_pinjaman_setelah_bunga,2)-parseFloat(response.jumlah_pinjaman,2);
									if ((parseFloat($('.spinner input').val(), 2) - 10.20).toFixed(2)<=jumlah_kekurangan) {
								   		$('#field-jumlah_riwayat_pembayaran').val(( parseFloat($('.spinner input').val(), 2) + parseFloat(response.jumlah_perangsuran,2)).toFixed(2));  
									}
								}).delegate('#spinner_down', 'click', function(event) {									
									if ((parseFloat($('.spinner input').val(), 2) - 10.20).toFixed(2)>=0) {										
								   		$('#field-jumlah_riwayat_pembayaran').val(( parseFloat($('.spinner input').val(), 2) - parseFloat(response.jumlah_perangsuran,2)).toFixed(2));  
									}
								});
							}else{
								$(".detail_id_pinjaman").html("");
							}
						}
					});									
				});
			</script>
		<?php endif ?>
			<!-- buat script untuk edit -->
			<?php if ($this->uri->segment(3)=="edit"): ?>
			<script type="text/javascript">
				$(function() {
				var jumlah_riwayat_pembayaran = $("#field-jumlah_riwayat_pembayaran").val();
				$( ".angsuran_ke_form_group" ).before( "<div class='detail_id_pinjaman'></div>" );				
					$.ajax({
						url: '<?php echo base_url('riwayat_pinjaman/get_detail_pinjaman_by_id_riwayat/').$this->uri->segment(4) ?>',
						type: 'get',
						dataType: 'json',						
						success :function(response){
							if (response.status == 200) {
								var html_angsuran = '<div class="input-group spinner"><input id="field-jumlah_riwayat_pembayaran" name="jumlah_riwayat_pembayaran" type="text" value="'+jumlah_riwayat_pembayaran+'" class="numeric form-control" maxlength="11"><div class="input-group-btn-vertical"><button class="btn btn-default" id="spinner_up" type="button"><i class="fa fa-caret-up"></i></button><button class="btn btn-default" id="spinner_down" type="button"><i class="fa fa-caret-down"></i></button></div></div>';
								$(".detail_id_pinjaman").html('<div class="form-group"><label class="col-sm-2 control-label">Detail Pinjaman</label><div class="col-sm-5"><div class="panel panel-warning"><div class="panel-body"><table class="table table-bordered"><tr><th>Nasabah</th><td>: '+response.nama_nasabah+'</td></tr><tr><th>Angsuran</th><td>: Rp. '+response.jumlah_perangsuran+' X '+response.lama_angsuran+'/'+response.periode_angsuran+'</td></tr><tr><th>Jumlah terbayar</th><td>: Rp. '+response.jumlah_terbayar+'</td></tr><tr><th>Jumlah Pinjaman</th><td>: Rp. '+response.jumlah_pinjaman_setelah_bunga+'</td></tr></table></div></div></div></div>');
								// $("#field-jumlah_riwayat_pembayaran").val(response.jumlah_perangsuran);
								$(".jumlah_riwayat_pembayaran_form_group .col-sm-5").html(html_angsuran).delegate( "#spinner_up", "click", function(){
									var jumlah_kekurangan = parseFloat(response.jumlah_pinjaman_setelah_bunga,2)-parseFloat(response.jumlah_pinjaman,2);
									if ((parseFloat($('.spinner input').val(), 2) - 10.20).toFixed(2)<=jumlah_kekurangan) {
								   		$('#field-jumlah_riwayat_pembayaran').val(( parseFloat($('.spinner input').val(), 2) + parseFloat(response.jumlah_perangsuran,2)).toFixed(2));  
									}
								}).delegate('#spinner_down', 'click', function(event) {									
									if ((parseFloat($('.spinner input').val(), 2) - 10.20).toFixed(2)>=0) {										
								   		$('#field-jumlah_riwayat_pembayaran').val(( parseFloat($('.spinner input').val(), 2) - parseFloat(response.jumlah_perangsuran,2)).toFixed(2));  
									}
								});
							}else{
								$(".detail_id_pinjaman").html("");
							}
						}
					});				
				});
				
			</script>
			<?php endif ?>			
	
          
	</body>
</html>