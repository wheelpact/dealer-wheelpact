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

<body class="login-page">
	<div class="login-header box-shadow">
		<div class="container-fluid d-flex justify-content-between align-items-center">
			<div class="brand-logo">
				<a href="<?php echo base_url(); ?>">
					<img src="<?php echo base_url(); ?>/assets/vendors/images/wheelpact-logo.png" alt="">
				</a>
			</div>
			<div class="login-menu d-none">
				<ul>
					<li><a href="<?php echo base_url('dealer/register'); ?>">Register</a></li>
				</ul>
			</div>
		</div>
	</div>
	<div class="login-wrap d-flex align-items-center flex-wrap justify-content-center">

		<div class="container">
			<div class="row align-items-center">
				<div class="col-md-6 col-lg-7">
					<img src="<?php echo base_url(); ?>/assets/vendors/images/login-page-img.png" alt="">
				</div>
				<div class="col-md-6 col-lg-5">
					<div class="login-box bg-white box-shadow border-radius-10">
						<div class="login-title">
							<h2 class="text-center text-primary">Dealer Panel</h2>
						</div>

						<form method="post" id="loginForm" action="<?php echo base_url() ?>dealer/login/auth">
							<div class="input-group custom">
								<input name="username" type="text" id="username" class="form-control form-control-lg" placeholder="Username" value="<?= isset($username) ? esc($username) : '' ?>" required>
								<div class="input-group-append custom">
									<span class="input-group-text"><i class="icon-copy dw dw-user1"></i></span>
								</div>
							</div>

							<div class="input-group custom">
								<input name="password" type="password" minlength="8" maxlength="12" id="password" class="form-control form-control-lg" placeholder="**********" value="<?= isset($password) ? esc($password) : '' ?>" required>
								<div class="input-group-append custom">
									<span class="input-group-text"><i class="dw dw-eye togglePassword" id="togglePassword"></i></span>
								</div>
							</div>

							<div class="row pb-30">
								<div class="col-6">
									<div class="custom-control custom-checkbox">
										<input name="remember" type="checkbox" class="custom-control-input" id="customCheck1" <?= isset($username) ? 'checked' : '' ?>>
										<label class="custom-control-label" for="customCheck1">Remember Me</label>
									</div>
								</div>
								<div class="col-6">
									<div class="forgot-password"><a href="<?php echo base_url('dealer/forgot-password') ?>">Forgot Password</a></div>
								</div>
							</div>

							<div class="row">
								<div class="col-sm-12">
									<div class="input-group mb-0">
										<input class="btn btn-primary btn-lg btn-block" type="submit" value="Sign In">
									</div>
									<div class="font-16 weight-600 pt-10 pb-10 text-center" data-color="#707373">OR</div>
									<div class="input-group mb-0">
										<a class="btn btn-outline-primary btn-lg btn-block" target="_blank" href="<?php echo MAIN_SITE_PATH.'become-partner'; ?>">Register To Create Account</a>
									</div>
								</div>
							</div>
						</form>
						<br />
						<?php if (session()->getFlashdata('msg')) : ?>
							<div class="alert alert-danger"><?= session()->getFlashdata('msg') ?></div>
						<?php endif; ?>
					</div>
				</div>
			</div>
		</div>
	</div>
	<!-- js -->
	<script src="<?php echo base_url(); ?>/assets/vendors/scripts/core.js"></script>
	<script src="<?php echo base_url(); ?>/assets/vendors/scripts/script.min.js"></script>
	<script src="<?php echo base_url(); ?>/assets/vendors/scripts/process.js"></script>
	<script src="<?php echo base_url(); ?>/assets/vendors/scripts/layout-settings.js"></script>
	<script src="<?php echo base_url(); ?>/assets/vendors/scripts/dealer.js"></script>
</body>

</html>