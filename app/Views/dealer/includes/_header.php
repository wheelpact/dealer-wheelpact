<!DOCTYPE html>
<html>

<head>
	<!-- Basic Page Info -->
	<meta charset="utf-8">
	<title>Dealer - Wheelpact</title>

	<!-- Site favicon -->
	<link rel="apple-touch-icon" sizes="180x180" href="<?php echo base_url(); ?>assets/vendors/images/apple-touch-icon.png">
	<link rel="icon" type="image/png" sizes="32x32" href="<?php echo base_url(); ?>assets/vendors/images/favicon-32x32.png">
	<link rel="icon" type="image/png" sizes="16x16" href="<?php echo base_url(); ?>assets/vendors/images/favicon-16x16.png">

	<!-- Mobile Specific Metas -->
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">

	<!-- Google Font -->
	<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
	<!-- CSS -->
	<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/vendors/styles/core.css">
	<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/vendors/styles/icon-font.min.css">
	<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/src/plugins/datatables/css/dataTables.bootstrap4.min.css">
	<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/src/plugins/datatables/css/responsive.bootstrap4.min.css">
	<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/vendors/styles/style.css">
	<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/src/plugins/fancybox/dist/jquery.fancybox.min.css">
	<!-- JQUERY FORM STEPS CSS -->
	<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/src/plugins/jquery-steps/jquery.steps.css">
	<!-- Sweetalert2 CDN CSS -->
	<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/sweetalert2@11.10.5/dist/sweetalert2.min.css">
</head>

<body>
	<!-- header,rightsidebar,leftsidebar and loader -->
	<div class="pre-loader">
		<div class="pre-loader-box">
			<div class="loader-logo"><img src="<?php echo base_url(); ?>assets/vendors/images/wheelpact-loader.png" alt="Wheelpact - Dealer Portal"></div>
			<div class='loader-progress' id="progress_div">
				<div class='bar' id='bar1'></div>
			</div>
			<div class='percent' id='percent1'>0%</div>
			<div class="loading-text">Loading...</div>
		</div>
	</div>
	<div class="header">
		<div class="header-left">
			<div class="menu-icon dw dw-menu"></div>
			<div class="search-toggle-icon bi bi-search" data-toggle="header_search"></div>
		</div>
		<div class="header-right">
			<div class="dashboard-setting user-notification">
				<div class="dropdown">
					<a class="dropdown-toggle no-arrow" href="javascript:;" data-toggle="right-sidebar">
						<i class="dw dw-settings2"></i>
					</a>
				</div>
			</div>

			<div class="user-info-dropdown">
				<div class="dropdown">
					<a class="dropdown-toggle" href="#" role="button" data-toggle="dropdown">
						<span class="user-icon">
							<img src="<?php echo base_url(); ?>assets/vendors/images/photo1.jpg" alt="">
						</span>
						<span class="user-name">
							<?php
							echo isset($userData['dealerName']) && !empty($userData['dealerName']) ? $userData['dealerName'] : ' UserDealer';
							?>
						</span>
					</a>
					<div class="dropdown-menu dropdown-menu-right dropdown-menu-icon-list">
						<a class="dropdown-item" href="<?php echo base_url('dealer/profile'); ?>"><i class="dw dw-user1"></i> Profile</a>
						<a class="dropdown-item d-none" href="<?php echo base_url('dealer/faq'); ?>"><i class="dw dw-help"></i> Help</a>
						<a class="dropdown-item" href="<?php echo base_url('dealer/logout'); ?>"><i class="dw dw-logout"></i> Log Out</a>
					</div>
				</div>
			</div>

		</div>
	</div>

	<div class="right-sidebar">
		<div class="sidebar-title">
			<h3 class="weight-600 font-16 text-blue">
				Layout Settings
				<span class="btn-block font-weight-400 font-12">User Interface Settings</span>
			</h3>
			<div class="close-sidebar" data-toggle="right-sidebar-close">
				<i class="icon-copy ion-close-round"></i>
			</div>
		</div>
		<div class="right-sidebar-body customscroll mCustomScrollbar _mCS_2 mCS_no_scrollbar">
			<div id="mCSB_2" class="mCustomScrollBox mCS-dark-2 mCSB_vertical mCSB_inside" style="max-height: none;" tabindex="0">
				<div id="mCSB_2_container" class="mCSB_container mCS_y_hidden mCS_no_scrollbar_y" style="position:relative; top:0; left:0;" dir="ltr">
					<div class="right-sidebar-body-content">
						<h4 class="weight-600 font-18 pb-10">Header Background</h4>
						<div class="sidebar-btn-group pb-30 mb-10">
							<a href="javascript:void(0);" class="btn btn-outline-primary header-white active">White</a>
							<a href="javascript:void(0);" class="btn btn-outline-primary header-dark">Dark</a>
						</div>

						<h4 class="weight-600 font-18 pb-10">Sidebar Background</h4>
						<div class="sidebar-btn-group pb-30 mb-10">
							<a href="javascript:void(0);" class="btn btn-outline-primary sidebar-light active">White</a>
							<a href="javascript:void(0);" class="btn btn-outline-primary sidebar-dark">Dark</a>
						</div>

						<h4 class="weight-600 font-18 pb-10">Menu Dropdown Icon</h4>
						<div class="sidebar-radio-group pb-10 mb-10">
							<div class="custom-control custom-radio custom-control-inline">
								<input type="radio" id="sidebaricon-1" name="menu-dropdown-icon" class="custom-control-input" value="icon-style-1" checked="">
								<label class="custom-control-label" for="sidebaricon-1"><i class="fa fa-angle-down"></i></label>
							</div>
							<div class="custom-control custom-radio custom-control-inline">
								<input type="radio" id="sidebaricon-2" name="menu-dropdown-icon" class="custom-control-input" value="icon-style-2">
								<label class="custom-control-label" for="sidebaricon-2"><i class="ion-plus-round"></i></label>
							</div>
							<div class="custom-control custom-radio custom-control-inline">
								<input type="radio" id="sidebaricon-3" name="menu-dropdown-icon" class="custom-control-input" value="icon-style-3">
								<label class="custom-control-label" for="sidebaricon-3"><i class="fa fa-angle-double-right"></i></label>
							</div>
						</div>

						<h4 class="weight-600 font-18 pb-10">Menu List Icon</h4>
						<div class="sidebar-radio-group pb-30 mb-10">
							<div class="custom-control custom-radio custom-control-inline">
								<input type="radio" id="sidebariconlist-1" name="menu-list-icon" class="custom-control-input" value="icon-list-style-1" checked="">
								<label class="custom-control-label" for="sidebariconlist-1"><i class="ion-minus-round"></i></label>
							</div>
							<div class="custom-control custom-radio custom-control-inline">
								<input type="radio" id="sidebariconlist-2" name="menu-list-icon" class="custom-control-input" value="icon-list-style-2">
								<label class="custom-control-label" for="sidebariconlist-2"><i class="fa fa-circle-o" aria-hidden="true"></i></label>
							</div>
							<div class="custom-control custom-radio custom-control-inline">
								<input type="radio" id="sidebariconlist-3" name="menu-list-icon" class="custom-control-input" value="icon-list-style-3">
								<label class="custom-control-label" for="sidebariconlist-3"><i class="dw dw-check"></i></label>
							</div>
							<div class="custom-control custom-radio custom-control-inline">
								<input type="radio" id="sidebariconlist-4" name="menu-list-icon" class="custom-control-input" value="icon-list-style-4" checked="">
								<label class="custom-control-label" for="sidebariconlist-4"><i class="icon-copy dw dw-next-2"></i></label>
							</div>
							<div class="custom-control custom-radio custom-control-inline">
								<input type="radio" id="sidebariconlist-5" name="menu-list-icon" class="custom-control-input" value="icon-list-style-5">
								<label class="custom-control-label" for="sidebariconlist-5"><i class="dw dw-fast-forward-1"></i></label>
							</div>
							<div class="custom-control custom-radio custom-control-inline">
								<input type="radio" id="sidebariconlist-6" name="menu-list-icon" class="custom-control-input" value="icon-list-style-6">
								<label class="custom-control-label" for="sidebariconlist-6"><i class="dw dw-next"></i></label>
							</div>
						</div>

						<div class="reset-options pt-30 text-center">
							<button class="btn btn-danger" id="reset-settings">
								Reset Settings
							</button>
						</div>
					</div>
				</div>
				<div id="mCSB_2_scrollbar_vertical" class="mCSB_scrollTools mCSB_2_scrollbar mCS-dark-2 mCSB_scrollTools_vertical mCSB_scrollTools_onDrag_expand" style="display: none;">
					<div class="mCSB_draggerContainer">
						<div id="mCSB_2_dragger_vertical" class="mCSB_dragger" style="position: absolute; min-height: 30px; height: 0px; top: 0px;">
							<div class="mCSB_dragger_bar" style="line-height: 30px;"></div>
						</div>
						<div class="mCSB_draggerRail"></div>
					</div>
				</div>
			</div>
		</div>
	</div>
	