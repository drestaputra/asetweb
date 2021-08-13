<!doctype html>
<html class="fixed">
<head>

	<!-- Basic -->
	<meta charset="UTF-8">
	
	<meta name="author" content="Dresta TAP & OemahWeb">

	<!-- Mobile Metas -->
	<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />

	<!-- Web Fonts  -->
	<link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700,800|Shadows+Into+Light" rel="stylesheet" type="text/css">

	<!-- Vendor CSS -->
	<link rel="stylesheet" href="<?php echo base_url(); ?>assets/vendor/bootstrap/css/bootstrap.css" />

	<link rel="stylesheet" href="<?php echo base_url(); ?>assets/vendor/font-awesome/css/font-awesome.css" />	

	<!-- Theme CSS -->
	<link rel="stylesheet" href="<?php echo base_url(); ?>assets/stylesheets/theme.css" />

	<!-- Skin CSS -->
	<link rel="stylesheet" href="<?php echo base_url(); ?>assets/stylesheets/skins/default.css" />

	<!-- Theme Custom CSS -->
	<link rel="stylesheet" href="<?php echo base_url(); ?>assets/stylesheets/theme-custom.css">

	<!-- Head Libs -->
	<script href="<?php echo base_url(); ?>assets/vendor/modernizr/modernizr.js"></script>
</head>
<body>
	<style type="text/css">
		.body-sign {
			max-width: 400px;
		}
		.panel-body-login{
			background: #ffc904a6!important;
			padding-bottom: 100px!important;
			box-shadow: 0 0 10px 0px #9AADAB !important;
			border: none!important;
			border-radius: 20px!important;
		}
		body{
			color: #fff;
			background: #ccc;
		}
		.btn-orange-hover{
			font-size: 16px;
			font-weight: 600;
			background: linear-gradient(45deg, #85bf59, #dabb30);
			border-radius: 10px;
			border: none;		
			transition: all 0.5s ease-in-out;
			padding-top: 10px;
			padding-bottom: 10px;
			box-shadow: 0 0 10px 0px #d0b44e;
		}
		.btn-orange-hover:hover{
			transition: all 0.5s ease-in-out;
			color: white;
			background: linear-gradient(45deg, #fdb91f,#85bf59);
			box-shadow: 0 0 10px 0px #edc328;
		}

	</style>
	<!-- start: page -->
	<section class="body-sign">
		<div class="center-sign text-center">


			<div class="panel panel-sign">
				

				<div class="panel-body panel-body-login">
					<img src="<?php echo base_url(); ?>assets/images/logo.png" height="100" alt="Logo" />
					<?php if (trim($this->input->get('status'))!=""): ?>
						<?php echo function_lib::response_notif($this->input->get('status'),$this->input->get('msg')); ?>					
					<?php endif ?> 
					<?php if (isset($cek_kode) AND !empty($cek_kode)): ?>						
					<h2>Ganti Password</h2>
					<form method="post">					
						<div class="form-group mb-lg">
							<label>Password baru</label>
							<div class="input-group input-group-icon">
								<input name="pwd" type="password" class="form-control input-lg" />
								<span class="input-group-addon">
									<span class="icon icon-lg">
										<i class="fa fa-lock"></i>
									</span>
								</span>
							</div>
						</div>

						<div class="form-group mb-lg">
							<label>Konfirmasi Password</label>								
							<div class="input-group input-group-icon">
								<input name="repwd" type="password" class="form-control input-lg" />
								<span class="input-group-addon">
									<span class="icon icon-lg">
										<i class="fa fa-repeat"></i>
									</span>
								</span>
							</div>
						</div>
						<br>
						<div class="row">

							<div class="col-sm-12">
								<button type="submit" class="btn btn-orange-hover hidden-xs btn-block">Ganti</button>
								<button type="submit" class="btn btn-block btn-orange-hover  btn-block btn-lg visible-xs mt-lg">Ganti</button>
							</div>
						</div>
					</form>
					<?php else: ?>
						<div class="m-t-10">
						<?php echo function_lib::response_notif(500,base64_encode('Kode ubah password sudah tidak bisa digunakan silahkan lakukan request ubah password kembali')); ?>					
						</div>
					<?php endif ?>

				</div>
			</div>

			<p class="text-center text-muted mt-md mb-md">&copy; Copyright <?php echo date('Y'); ?>. All Rights Reserved.</p>
		</div>
	</section>
	<!-- end: page -->

	<!-- Vendor -->
	<script href="<?php echo base_url(); ?>assets/vendor/jquery/jquery.js"></script>
	<script href="<?php echo base_url(); ?>assets/vendor/jquery-browser-mobile/jquery.browser.mobile.js"></script>
	<script href="<?php echo base_url(); ?>assets/vendor/bootstrap/js/bootstrap.js"></script>


	<!-- Theme Base, Components and Settings -->
	<script href="<?php echo base_url(); ?>assets/javascripts/theme.js"></script>

	<!-- Theme Custom -->
	<script href="<?php echo base_url(); ?>assets/javascripts/theme.custom.js"></script>

	<!-- Theme Initialization Files -->
	<script href="<?php echo base_url(); ?>assets/javascripts/theme.init.js"></script>

</body>
</html>