<!doctype html>
<html class="fixed">
	<head>

		<!-- Basic -->
		<meta charset="UTF-8">
		<title>Riwayat Simpanan | <?php echo function_lib::get_config_value('website_name'); ?></title>
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
						<h2>Riwayat Simpanan</h2>
					
						<div class="right-wrapper pull-right">
							<ol class="breadcrumbs">
								<li>
									<a href="index.html">
										<i class="fa fa-home"></i>
									</a>
								</li>
								<li><span>Riwayat Simpanan</span></li>
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
                            <h3 class="panel-title">Data Riwayat Simpanan</h3>
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
				
			$("th[data-order-by=detail_simpanan]").prop("onclick", null).off("click");
			$(".searchable-input[name=detail_simpanan]").hide();			
			$("th[data-order-by=nama_nasabah]").prop("onclick", null).off("click");
			$(".searchable-input[name=nama_nasabah]").hide();			
			$(".searchable-input[name=sd6c9f337]").val(<?php echo isset($_GET['id_simpanan']) ? $_GET['id_simpanan'] : ""; ?>);
			$('.gc-refresh').trigger('click');
			});
		</script>
		<?php if ($this->uri->segment(3)=="add"): ?>
		<script type="text/javascript">
			var locale = 'id';
			var options = {style: 'currency', currency: 'idr', minimumFractionDigits: 2, maximumFractionDigits: 2};
			var formatter = new Intl.NumberFormat(locale, options);			
			$( ".id_simpanan_form_group" ).after( "<div class='detail_id_simpanan'></div>" );		
			$("#field-id_simpanan").on("change",function(){
				var id_simpanan = $(this).val();
				$.ajax({
					url: '<?php echo base_url('riwayat_simpanan/get_detail_simpanan/') ?>'+id_simpanan,
					type: 'GET',
					dataType: 'JSON',					
					success : function(response){
						if (response.status == 200) {
							$(".detail_id_simpanan").html('<div class="form-group"><label class="col-sm-2 control-label">Detail Simpanan</label><div class="col-sm-5"><div class="panel panel-warning"><div class="panel-body"><table class="table table-bordered"><tr><th>Nasabah</th><td>: '+response.nama_nasabah+'</td></tr><tr><th>Jumlah Simpanan</th><td>:  '+formatter.format(response.jumlah_simpanan)+'</td></tr></table></div></div></div></div>');
						}else{
							$(".detail_id_simpanan").html("");
						}
					}
				});	
			});
		</script>
		<?php endif ?>
        
	
          
	</body>
</html>