<!DOCTYPE html>
<html>

<head>
	<!-- Basic Page Info -->
	<meta charset="utf-8">
	<title>Wheelpact - Dealer Login</title>

	<!-- Site favicon -->
	<link rel="apple-touch-icon" sizes="180x180" href="<?php echo base_url(); ?>/assets/vendors/images/apple-touch-icon.png">
	<link rel="icon" type="image/png" sizes="32x32" href="<?php echo base_url(); ?>/assets/vendors/images/favicon-32x32.png">
	<link rel="icon" type="image/png" sizes="16x16" href="<?php echo base_url(); ?>/assets/vendors/images/favicon-16x16.png">

	<!-- Mobile Specific Metas -->
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">

	<!-- Google Font -->
	<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
	<!-- CSS -->
	<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>/assets/vendors/styles/core.css">
	<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>/assets/vendors/styles/icon-font.min.css">
	<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>/assets/vendors/styles/style.css">
</head>

<body>
	<div class="login-header box-shadow">
		<div class="container-fluid d-flex justify-content-between align-items-center">
			<div class="brand-logo">
				<a href="login">
					<img src="<?php echo base_url(); ?>/assets/vendors/images/wheelpact-logo.png" alt="">
				</a>
			</div>
			<div class="login-menu">
				<ul>
					<li><a href="<?php echo base_url('deskapp/login') ?>">Login</a></li>
				</ul>
			</div>
		</div>
	</div>
	<div class="login-wrap d-flex align-items-center flex-wrap justify-content-center">
		<div class="container">
			<div class="row align-items-center">
				<div class="col-md-6">
					<img src="<?php echo base_url(); ?>/assets/vendors/images/forgot-password.png" alt="">
				</div>
				<div class="col-md-6">
					<div class="login-box bg-white box-shadow border-radius-10">
						<div class="login-title">
							<h2 class="text-center text-primary">Forgot Password</h2>
						</div>
						<h6 class="mb-20">Enter your email address to reset your password</h6>
						<?= form_open('dealer/send-reset-password-link', 'id="reset_password_link_dealer"') ?>
						<?= csrf_field(); ?>

						<div class="input-group custom">
							<input type="email" name="email" id="email" class="form-control form-control-lg" placeholder="Email">
							<div class="input-group-append custom">
								<span class="input-group-text"><i class="fa fa-envelope-o" aria-hidden="true"></i></span>
							</div>
						</div>
						<div class="row align-items-center">
							<div class="col-5">
								<div class="input-group mb-0">
									<input class="btn btn-primary btn-lg btn-block" name="submit" type="submit" value="Submit">
								</div>
							</div>
							<div class="col-2">
								<div class="font-16 weight-600 text-center" data-color="#707373">OR</div>
							</div>
							<div class="col-5">
								<div class="input-group mb-0">
									<a class="btn btn-outline-primary btn-lg btn-block" href="<?php echo base_url('dealer/login'); ?>">Login</a>
								</div>
							</div>
						</div>
						<?= form_close() ?>
					</div>
				</div>
			</div>
		</div>
	</div>
	<!-- js -->
	<script src="<?php echo base_url(); ?>/assets/vendors/scripts/core.js"></script>
	<script src="<?php echo base_url(); ?>/assets/vendors/scripts/script.min.js"></script>
	<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.10.5/dist/sweetalert2.all.min.js"></script>
	<script src="<?php echo base_url(); ?>/assets/vendors/scripts/dealer.js"></script>
</body>

</html>