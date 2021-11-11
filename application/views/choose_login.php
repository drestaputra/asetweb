<!DOCTYPE html>
<html lang="en">
<head>
	<title>Login Admin</title>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="keywords" content="Dashboard Admin - ArtaKita" />	
	<meta name="author" content="Dresta Twas AP">

	<link rel="icon" type="image/png" href="images/icons/favicon.ico"/>
	<link rel="stylesheet" href="<?php echo base_url(); ?>assets/vendor/bootstrap/css/bootstrap.css" />
	<link rel="stylesheet" href="<?php echo base_url(); ?>assets/vendor/font-awesome/css/font-awesome.css" />
	<link rel="stylesheet" href="<?php echo base_url(); ?>assets/vendor/animate/animate.css" />

	<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/vendor/css-hamburgers/hamburgers.min.css">
	<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/vendor/select2/select2.css">
	<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/stylesheets/login.css">
	<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/stylesheets/theme-custom.css">
</head>
<body>
	
	<div class="limiter">
		<div class="container-login100">
			<div class="wrap-login100" style="justify-content: center;">
				<div class="row">
					<div class="col-md-12">
						<center >
							<h3>Pilih Jenis User</h3>
						</center>

					</div>
				</div>
				<div class="row items-card">
					<div class="col-md-3 col-xs-6">
						<a href="<?php echo base_url('super_admin/login'); ?>">
						<div class="card-login text-center">
							<img class="img-responsive img-circle" src="<?php echo base_url('assets/images/avatar.png') ?>">
							<h3>Super Admin</h3>
						</div>
						</a>
					</div>
					<div class="col-md-3 col-xs-6">
						<a href="<?php echo base_url('admin/login'); ?>">
						<div class="card-login text-center">
							<img class="img-responsive img-circle" src="<?php echo base_url('assets/images/avatar.png') ?>">
							<h3>Admin</h3>
						</div>
						</a>
					</div>
					<div class="col-md-3 col-xs-6">
						<a href="<?php echo base_url('koordinator/login'); ?>">
						<div class="card-login text-center">
							<img class="img-responsive img-circle" src="<?php echo base_url('assets/images/avatar.png') ?>">
							<h3>Koordinator</h3>
						</div>
						</a>
					</div>
					<div class="col-md-3 col-xs-6">
						<a href="<?php echo base_url('pengurus_barang/login'); ?>">
						<div class="card-login text-center">
							<img class="img-responsive img-circle" src="<?php echo base_url('assets/images/avatar.png') ?>">
							<h3>Pengurus Barang</h3>
						</div>
						</a>
					</div>
					
					
					
				</div>
			</div>
		</div>
	</div>
	
	

	
	<script src="<?php echo base_url(); ?>assets/vendor/jquery/jquery.js"></script>
	<script src="<?php echo base_url(); ?>assets/vendor/bootstrap/js/popper.js"></script>
	<script src="<?php echo base_url(); ?>assets/vendor/bootstrap/js/bootstrap.min.js"></script>
	<script src="<?php echo base_url(); ?>assets/vendor/select2/select2.min.js"></script>
	<script src="<?php echo base_url(); ?>assets/vendor/tilt/tilt.jquery.min.js"></script>
	<script >
		$('.js-tilt').tilt({
			scale: 1.1
		})
	</script>	

</body>
</html>