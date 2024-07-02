<div class="left-side-bar">
	<div class="brand-logo">
		<a href="<?php echo base_url('dealer/dashboard'); ?>">
			<img src="<?php echo base_url(); ?>assets/vendors/images/wheelpact-logo.png" alt="" class="dark-logo">
			<img src="<?php echo base_url(); ?>assets/vendors/images/wheelpact-logo.png" alt="" class="light-logo">
		</a>
		<div class="close-sidebar" data-toggle="left-sidebar-close">
			<i class="ion-close-round"></i>
		</div>
	</div>
	<div class="menu-block customscroll">
		<div class="sidebar-menu">
			<ul id="accordion-menu">
				<li class="dropdown">
					<a href="<?php echo base_url('dealer/dashboard'); ?>" class="dropdown-toggle no-arrow <?php echo (current_url() == base_url('dealer/dashboard')) ? 'active' : ''; ?>">
						<span class="micon dw dw-monitor-1"></span><span class="mtext">Dashboard</span>
					</a>
				</li>
				<?php
				/* check if the user has is allowed to add vehicles, the dealer_subscription table column vehicle_type should not be 0 or null*/
				if ($planData['allowedVehicleListing'] != "0") {
				?>
					<li class="dropdown">
						<a href="javascript:;" class="dropdown-toggle">
							<span class="micon dw dw-car"></span><span class="mtext">Vehicles</span>
						</a>
						<ul class="submenu">
							<li><a href="<?php echo base_url('dealer/add-vehicle'); ?>">Add Vehicle</a></li>
							<li><a href="<?php echo base_url('dealer/list-vehicles'); ?>" class="<?php echo ((current_url() == base_url('dealer/list-vehicles')) || (strpos(current_url(), 'dealer/single-vehicle-info') !== false)) ? 'active' : ''; ?>">List Vehicles</a></li>
						</ul>

					</li>
					<li class="dropdown">
						<a href="javascript:;" class="dropdown-toggle">
							<span class="micon dw dw-megaphone"></span><span class="mtext">Promotions</span>
						</a>
						<ul class="submenu">
							<li><a href="#">Showroom Promotions</a></li>
							<li><a href="#">Vehicle Promotions</a></li>
						</ul>
					</li>
					<li class="dropdown">
						<a href="#" class="dropdown-toggle">
							<span class="micon dw dw-building"></span><span class="mtext">Manage Showroom</span>
						</a>
						<ul class="submenu">
							<li><a href="<?php echo base_url('dealer/add-branch'); ?>" class="dropdown-toggle no-arrow <?php echo (current_url() == base_url('dealer/add-branch')) ? 'active' : ''; ?>">Add Showroom</a></li>
							<li><a href="<?php echo base_url('dealer/list-branches'); ?>" class="<?php echo ((current_url() == base_url('dealer/list-branches')) || (strpos(current_url(), 'dealer/single-branches-info') !== false)) ? 'active' : ''; ?>">List Showrooms</a></li>
						</ul>
					</li>
					<li class="dropdown">
						<a href="<?php echo base_url('dealer/list-reserved-vehicles'); ?>" class="dropdown-toggle no-arrow">
							<span class="micon dw dw-calendar-6"></span><span class="mtext">Reservations</span>
						</a>
					</li>
				<?php } ?>
				<li class="dropdown">
					<a href="javascript:;" class="dropdown-toggle no-arrow">
						<span class="micon dw dw-question-1"></span><span class="mtext">Enquires</span>
					</a>
				</li>
				<li class="dropdown">
					<a href="javascript:;" class="dropdown-toggle no-arrow">
						<span class="micon dw dw-help"></span><span class="mtext">Admin Help</span>
					</a>
				</li>
			</ul>
		</div>
	</div>
</div>
<div class="mobile-menu-overlay"></div>