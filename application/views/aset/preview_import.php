<!doctype html>
<html class="fixed">
	<head>

		<!-- Basic -->
		<meta charset="UTF-8">
		<title>Aset | <?php echo function_lib::get_config_value('website_name'); ?></title>
		<meta name="keywords" content="Dashboard Admin - Koperasi Artakita" />
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
		<link rel="stylesheet" href="https://cdn.datatables.net/1.11.2/css/dataTables.bootstrap.min.css" />

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
						<h2>Import Aset</h2>
					
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
                            <h3 class="panel-title">Preview Import Data</h3>
                            <div class="row">
                            	<div class="col-md-12">
                            		<form method="POST" id="save_excel">
                            			<button name="save" value="1" class="btn btn-success pull-right"><i class="fa fa-save"></i> Simpan</button>
                            		</form>
                            	</div>
                            </div>
                        </div>
                        
						<div class="panel-body">
							<div class="table-responsive">
								<table class="table table-bordered table-hover table-striped table-data">
									<thead>
										<th>No.</th>
										<th>Nama Aset</th>
										<th>Kode</th>
										<th>Register</th>
										<th>Luas</th>
										<th>Tahun Perolehan</th>
										<th>Alamat</th>
										<th>Jenis Hak</th>
										<th>Tanggal Sertifikat</th>
										<th>No. Sertifikat</th>
										<th>Penggunaan</th>
										<th>Asal Perolehan</th>
										<th>Harga Perolehan</th>
										<th>Keterangan</th>
									</thead>
									<tbody>
										<?php foreach ($dataAset as $key => $value): ?>
											<tr>
												<td><?php echo $key+1; ?></td>
												
												<td><?php echo isset($value['B']) ? $value['B'] : "" ;  ?></td>
												<td><?php echo isset($value['C']) ? $value['C'] : "" ;  ?></td>
												<td><?php echo isset($value['D']) ? $value['D'] : "" ;  ?></td>
												<td><?php echo isset($value['E']) ? $value['E'] : "" ;  ?></td>
												<td><?php echo isset($value['F']) ? $value['F'] : "" ;  ?></td>
												<td><?php echo isset($value['G']) ? $value['G'] : "" ;  ?></td>
												<td><?php echo isset($value['H']) ? $value['H'] : "" ;  ?></td>
												<td><?php echo isset($value['I']) ? $value['I'] : "" ;  ?></td>
												<td><?php echo isset($value['J']) ? $value['J'] : "" ;  ?></td>
												<td><?php echo isset($value['K']) ? $value['K'] : "" ;  ?></td>
												<td><?php echo isset($value['L']) ? $value['L'] : "" ;  ?></td>
												<td><?php echo isset($value['M']) ? $value['M'] : "" ;  ?></td>
												<td><?php echo isset($value['N']) ? $value['N'] : "" ;  ?></td>
												
											</tr>
										<?php endforeach ?>
									</tbody>
								</table>
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
		<script src="<?php echo base_url(); ?>assets/vendor/jquery-placeholder/jquery.placeholder.js"></script>
		<script src="<?php echo base_url(); ?>assets/vendor/datatables/datatables.js"></script>
		
		<!-- Specific Page Vendor -->
		<script src="<?php echo base_url(); ?>assets/vendor/jquery-ui/js/jquery-ui-1.10.4.custom.js"></script>
		<script src="<?php echo base_url(); ?>assets/vendor/jquery-ui-touch-punch/jquery.ui.touch-punch.js"></script>
		<script src="<?php echo base_url(); ?>assets/vendor/jquery-appear/jquery.appear.js"></script>
				  
		
		<script src="<?php echo base_url(); ?>assets/javascripts/theme.js"></script>
		
		<!-- Theme Custom -->
		<script src="<?php echo base_url(); ?>assets/javascripts/theme.custom.js"></script>
		
		<!-- Theme Initialization Files -->
		<script src="<?php echo base_url(); ?>assets/javascripts/theme.init.js"></script>	
		<script src="https://cdn.datatables.net/1.11.2/js/dataTables.bootstrap.min.js"></script>	
		
		<!-- flexigrid -->		
		<script type="text/javascript">
			$(document).ready( function () {
			    $('.table-data').DataTable();
			    $("#save_excel").submit(function(event) {
			    	var conf = confirm("Apakah Anda yakin menyimpan data Aset tersebut?");
			    	return conf;
			    });
			} );
		</script>

	
          
	</body>
</html>