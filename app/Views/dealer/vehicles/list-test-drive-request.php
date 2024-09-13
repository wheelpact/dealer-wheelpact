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
									<li class="breadcrumb-item active" aria-current="page">Test Drive Requests</li>
								</ol>
							</nav>
						</div>
						<div class="col-md-6 col-sm-12 text-right">
							<div class="dropdown">
								<a class="btn btn-primary" href="<?php echo base_url('dealer/add-vehicle'); ?>">Add Vehckes</a>
							</div>
						</div>
					</div>
				</div>

				<div class="pd-20 bg-white border-radius-4 box-shadow mb-30 d-none">
					<!-- filters start-->
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
					<!-- filters end-->
				</div>

				<div class="card-box mb-30">
					<div class="pd-20">
						<h4 class="text-blue h4">Test Drive Requests</h4>
					</div>
					<div class="pb-20">
						<div id="testDriveTable_wrapper" class="dataTables_wrapper dt-bootstrap4 no-footer table-responsive">

							<table class="table hover multiple-select-row data-table-export nowrap dataTable no-footer dtr-inline pd-30 table-bordered table-striped" id="testDriveTable" role="grid">
								<thead>
									<tr>
										<th>S.No.</th>
										<th>Customer Name</th>
										<th>Customer Phone</th>
										<th>Vehicle Brand</th>
										<th>Model</th>
										<th>Variant</th>
										<th>Branch</th>
										<th>Date of Visit</th>
										<th>Time of Visit</th>
										<th>Comments</th>
										<th>Created At</th>
									</tr>
								</thead>
							</table>

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

			const testDriveSlots = <?php echo json_encode(TEST_DRIVE_SLOTS); ?>;
			/* data-table loading */
			$('#testDriveTable').DataTable({
				dom: 'Bfrtip',
				buttons: [
					'copy', 'csv', 'excel', 'pdf', 'print'
				],
				"processing": true,
				"serverSide": true,
				"ajax": {
					url: base_url + 'dealer/fetch-test-drive-request',
					"type": "POST",
				},
				"pageLength": 5, // Limit 5 records per page
				"columns": [{
						"data": null, // Data is not directly from the server
						"render": function(data, type, row, meta) {
							return meta.row + 1; // Serial number
						}
					},
					{
						"data": "customer_name"
					},
					{
						"data": "customer_phone"
					},
					{
						"data": "cmp_name"
					},
					{
						"data": "model_name"
					},
					{
						"data": "variant_name"
					},
					{
						"data": "branch_name"
					}, // Branch Name
					{
						"data": "formatted_dateOfVisit"
					},
					{
						"data": "timeOfVisit",
						"render": function(data, type, row) {
							// Map the timeOfVisit value to the slot using the JavaScript object
							return testDriveSlots[data] || 'Unknown Slot';
						}
					},
					{
						"data": "comments"
					},
					{
						"data": "formatted_created_at"
					}
				],
				"order": [
					[8, "desc"]
				] // Default ordering by created_at column
			});
		});
	</script>