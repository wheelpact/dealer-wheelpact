<?php
echo view('dealer/includes/_header');
echo view('dealer/includes/_sidebar');
?>

<div class="main-container">
	<div class="pd-ltr-20 xs-pd-20-10">
		<div class="min-height-200px">
			<div class="page-header">
				<div class="row">
					<div class="col-md-6 col-sm-12">
						<div class="title">
							<h4>Dealer Branches</h4>
						</div>
						<nav aria-label="breadcrumb" role="navigation">
							<ol class="breadcrumb">
								<li class="breadcrumb-item"><a href="<?php echo base_url('dealer/dashboard'); ?>">Home</a></li>
								<li class="breadcrumb-item active" aria-current="page">Manage Showrooms</li>
							</ol>
						</nav>
					</div>
					<div class="col-md-6 col-sm-12 text-right">
						<div class="dropdown">
							<a class="btn btn-primary" href="<?php echo base_url('dealer/add-branch'); ?>">Add Showroom</a>
						</div>
					</div>
				</div>
			</div>
			<div class="pd-20 bg-white border-radius-4 box-shadow mb-30">
				<div class="row">
					<div class="col-md-2">
						<div class="form-group">
							<select class="col-12 custom-select country">
								<option>Select State</option>
								<?php foreach ($countryList as $id => $country) : ?>
									<option value="<?= $country['id'] ?>" <?= ($country['id'] == 101) ? 'selected' : '' ?>>
										<?= $country['name'] ?>
									</option>
								<?php endforeach; ?>
							</select>
						</div>
					</div>
					<div class="col-md-2">
						<div class="form-group">
							<select class="col-12 custom-select state" aria-placeholder="Select State">
								<option value="0">State</option>
							</select>
						</div>
					</div>
					<div class="col-md-2">
						<div class="form-group">
							<select class="col-12 custom-select city" aria-placeholder="Select City">
								<option value="0">City</option>
							</select>
						</div>
					</div>
					<div class="col-md-2">
						<div class="form-group">
							<select class="col-12 custom-select branchType" aria-placeholder="Select City">
								<option>Select Branch Type</option>
								<?php foreach (BRANCH_TYPE as $id => $type) : ?>
									<option value="<?= $id ?>" <?php echo ($id == 1) ? '' : ''; ?>><?= $type ?></option>
								<?php endforeach; ?>
							</select>
						</div>
					</div>

					<div class="col-md-3">
						<div class="pull-right">
							<button class="btn btn-primary" id="apply_list_branch_filter">Apply Filters</button>
						</div>
					</div>
				</div>
			</div>

			<input type="hidden" id="actionurl" value="<?php echo base_url('dealer/getdealerbranches/0/0/0/0'); ?>">
			<div class="row" class="vehicle-list">
				<div class="container-fluid mb-3 mt-3">
					<div class="row" id="load_data"></div>
					<div class="row">
						<div class="col-md-12 text-center">
							<div id="load_data_message"></div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<!-- include review modal -->
		<?php echo view('dealer/includes/modals/reviewsModal'); ?>
		<!-- footer -->
		<?php echo view('dealer/includes/_footer'); ?>
	</div>
</div>

<script>
	$(document).ready(function() {
		/* on page load trigger to load brands of cars & bikes both in select option filter */
		$('.custom-select.country').trigger('change');
	});
</script>