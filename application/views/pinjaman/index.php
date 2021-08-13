<!doctype html>
<html class="fixed">
	<head>

		<!-- Basic -->
		<meta charset="UTF-8">
		<title>Pinjaman | <?php echo function_lib::get_config_value('website_name'); ?></title>
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
					<?php if ($state_data == "list" OR $state_data == "success"): ?>
						<div class="row">
							<div class="col-md-4">
								<div class="panel panel-primary">
									<div class="panel-body bg-primary">
									<h4 class="title">Total Modal</h4>
										<div class="info">
											<strong class="amount"><?php echo "Rp. ".number_format($total_modal, 0,'.','.'); ?></strong>		
										</div>
									</div>
								</div>
							</div>
							<div class="col-md-4">
								<div class="panel panel-tertiary">
									<div class="panel-body bg-tertiary">
									<h4 class="title">Total Pinjaman</h4>
										<div class="info">
											<strong class="amount"><?php echo "Rp. ".number_format($total_pinjaman, 0,'.','.'); ?></strong>		
										</div>
									</div>
								</div>
							</div>
							<div class="col-md-4">
								<div class="panel panel-quartenary">
									<div class="panel-body bg-quartenary">
									<h4 class="title">Ratio Modal dan Pinjaman</h4>
										<div class="info">
											<div class="progress progress-lg progress-half-rounded m-t-10 light">
												<div class="progress-bar progress-bar-info" role="progressbar" aria-valuenow="<?php echo number_format(floatval($ratio_modal_pinjaman)*100,2,'.',',') ?>" aria-valuemin="0" aria-valuemax="100" style="width: <?php echo number_format(floatval($ratio_modal_pinjaman)*100,2,'.',','); ?>%;">
													<?php echo number_format(floatval($ratio_modal_pinjaman)*100,2,',','.'); ?>%
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					<?php endif ?>
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
		  <script type="text/javascript">
			<?php if ((isset($level) AND ($level=="owner" OR $level=="kasir") AND $state_data != "edit")): ?>
				$(document).ready(function() {        	
				var selectedValue = <?php echo isset($id_user) ? $id_user : 0; ?>;
					// alert('selectedValue'+selectedValue);
					// console.log('post:'+'ajax_extension/id_kolektor/id_owner/9');
					$.post('ajax_extension/id_kolektor/id_owner/'+encodeURI(selectedValue), {}, function(data) {
					//alert('data'+data);
					var $el = $('#field-id_kolektor');
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
    	  			$('#field-id_kolektor').change();
    	  	});		
			<?php else: ?>					
			$(document).ready(function() {        		
				$('#field-id_owner').change(function() {									
					var selectedValue = $('#field-id_owner').val();					
					// alert('selectedValue'+selectedValue);
					//alert('post:'+'ajax_extension/id_kolektor/id_owner/'+encodeURI(selectedValue.replace(/\//g,'_agsl_')));
					$.post('ajax_extension/id_kolektor/id_owner/'+encodeURI(selectedValue.replace(/\//g,'_agsl_')), {}, function(data) {
					//alert('data'+data);
					var $el = $('#field-id_kolektor');
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
    	  			$('#field-id_kolektor').change();
				});
			});

			<?php endif ?>					
        	
			$(document).ready(function() {
				$('#field-id_kolektor').change(function() {					
					var selectedValue = $('#field-id_kolektor').val();
					// alert('selectedValue'+selectedValue);
					//alert('post:'+'ajax_extension/id_kolektor/id_owner/'+encodeURI(selectedValue.replace(/\//g,'_agsl_')));
					$.post('ajax_extension/id_nasabah/id_kolektor/'+encodeURI(selectedValue.replace(/\//g,'_agsl_')), {}, function(data) {
					//alert('data'+data);
					var $el = $('#field-id_nasabah');
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
    	  			$('#field-id_nasabah').change();
				});
			});
			
        </script>
		
		<script src="<?php echo base_url(); ?>assets/javascripts/theme.js"></script>
		
		<!-- Theme Custom -->
		<script src="<?php echo base_url(); ?>assets/javascripts/theme.custom.js"></script>
		
		<!-- Theme Initialization Files -->
		<script src="<?php echo base_url(); ?>assets/javascripts/theme.init.js"></script>		
		<!-- flexigrid -->		
		<script type="text/javascript">
			$(function() {
				
			$("th[data-order-by=Kekurangan]").prop("onclick", null).off("click");
			$(".searchable-input[name=Kekurangan]").hide();			
			$("th[data-order-by=biaya_admin]").prop("onclick", null).off("click");
			$(".searchable-input[name=biaya_admin]").hide();			
			$("th[data-order-by=angsuran]").prop("onclick", null).off("click");
			$(".searchable-input[name=angsuran]").hide();			
			
			});
		</script>
		
		<?php if ($this->uri->segment(3)=="add"): ?>
		<script type="text/javascript">		
			$( ".id_nasabah_form_group" ).after( "<div class='detail_id_nasabah'></div>" );		
			$('#field-id_kolektor').empty();
			$('#field-id_kolektor').chosen().trigger('chosen:updated');
			$('#field-id_kolektor').change();
			$('#field-id_nasabah').empty();
			$('#field-id_nasabah').chosen().trigger('chosen:updated');
			$('#field-id_nasabah').change();

			$("#field-id_nasabah").on("change",function(){
				var id_nasabah = $(this).val();
				if (id_nasabah != null) {
					$.ajax({
						url: '<?php echo base_url('pinjaman/get_detail_pinjaman_lama/') ?>'+id_nasabah,
						type: 'GET',
						dataType: 'JSON',
						success: function(response){
							if (response.status == 200) {
								$(".detail_id_nasabah").html('<div class="form-group"><label class="col-sm-2 control-label">Detail Pinjaman Belum Lunas</label><div class="col-sm-5"><div class="panel panel-warning"><div class="panel-body"><table class="table table-bordered"><tr><th>Angsuran</th><td>: Rp. '+response.jumlah_perangsuran+' X '+response.lama_angsuran+'/'+response.periode_angsuran+'</td></tr><tr><th>Jumlah terbayar</th><td>: Rp. '+response.jumlah_terbayar+'</td></tr><tr><th>Jumlah Pinjaman</th><td>: Rp. '+response.jumlah_pinjaman_setelah_bunga+'</td></tr><tr><th>Kurang</th><td>: Rp. '+parseFloat(parseFloat(response.jumlah_pinjaman_setelah_bunga)-parseFloat(response.jumlah_terbayar))+'</td></tr></table></div></div></div><div class="col-sm-4"><div class="alert alert-warning"><p>Apabila terjadi pinjaman dalam masa angsuran pinjaman berjalan maka Jumlah Pinjaman berikutnya dapat dipotong untuk menutup angsuran berjalannya.</p></div></div></div>');
							}else{
								$(".detail_id_nasabah").html("");
							}
						}					
					})
				}
			});
		</script>
		<?php endif ?>
	
          
	</body>
</html>