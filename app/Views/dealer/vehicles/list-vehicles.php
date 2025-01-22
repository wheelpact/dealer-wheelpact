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
							<h4>Vehicles</h4>
						</div>
						<nav aria-label="breadcrumb" role="navigation">
							<ol class="breadcrumb">
								<li class="breadcrumb-item"><a href="<?php echo base_url('dealer/dashboard'); ?>">Home</a></li>
								<li class="breadcrumb-item active" aria-current="page">Vehicles</li>
							</ol>
						</nav>
					</div>
					<div class="col-md-6 col-sm-12 text-right">
						<div class="dropdown">
							<a class="btn btn-primary" href="<?php echo base_url('dealer/add-vehicle'); ?>">Add Vehicles</a>
						</div>
					</div>
				</div>
			</div>
			<div class="pd-20 bg-white border-radius-4 box-shadow mb-30">
				<div class="row">
					<div class="col-md-2">
						<div class="form-group">
							<select class="custom-select vehicle-type col-12">
								<option>Select Vehicle Type</option>
								<?php foreach (VEHICLE_TYPE as $id => $type) : ?>
									<option value="<?= $id ?>" <?php echo ($id == 3) ? 'selected' : ''; ?>><?= $type ?></option>
								<?php endforeach; ?>
							</select>
						</div>
					</div>
					<div class="col-md-2">
						<div class="form-group">
							<select class="custom-select brand col-12" aria-placeholder="Select Vehicle Brand"></select>
						</div>
					</div>
					<div class="col-md-2">
						<div class="form-group">
							<select class="custom-select model col-12">
								<option value="0">Models</option>
							</select>
						</div>
					</div>
					<div class="col-md-3">
						<div class="form-group">
							<select class="custom-select variant col-12">
								<option value="0">Variants</option>
							</select>
						</div>
					</div>

					<div class="col-md-3">
						<div class="pull-right">
							<button class="btn btn-primary" id="apply_list_vehicle_filter">Apply Filters</button>
						</div>
					</div>
				</div>

			</div>

			<input type="hidden" id="actionurl" value="<?php echo base_url('dealer/getbranchvehicles/0/0/0/0'); ?>">
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
		<!-- footer -->
		<?php echo view('dealer/includes/_footer'); ?>
	</div>
</div>

<script>
	$(document).ready(function() {
		/* on page load trigger to load brands of cars & bikes both in select option filter */
		$('.custom-select.vehicle-type').trigger('change');
	});
</script>