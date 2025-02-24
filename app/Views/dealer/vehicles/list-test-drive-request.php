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
								<h4>Test Drive Requests</h4>
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
								<a class="btn btn-primary" href="<?php echo base_url('dealer/add-vehicle'); ?>">Add Vehicles</a>
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
										<th>#</th>
										<th>Action</th> <!-- Action column for status dropdown -->
										<th>License</th>
										<th>Name</th>
										<th>Phone</th>
										<th>Email</th>
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
								<tbody>
									<!-- Shimmer Effect -->
									<tr>
										<td colspan="12">
											<div class="d-flex justify-content-center">
												<div class="content-placeholder" style="width: 80%; height: 30px;">&nbsp;</div>
											</div>
										</td>
									</tr>
								</tbody>
							</table>

						</div>
					</div>
				</div>

			</div>
			<!-- footer -->
			<?php echo view('dealer/includes/_footer'); ?>
		</div>
	</div>
	<!-- Status Update Modal -->
	<!-- Modal for Adding Reason and Comments -->
	<div class="modal fade" id="reasonModal" tabindex="-1" role="dialog" aria-labelledby="reasonModalLabel" aria-hidden="true">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title" id="reasonModalLabel">Add Reason and Comments</h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="modal-body">
					<form id="reasonForm">
						<div class="form-group">
							<label for="reason">Reason for Rejection/Cancellation</label>
							<select class="form-control" id="reason_selected" name="reason_selected" required>
								<option value="">Select a reason</option>
								<option value="Vehicle is not available on the requested date.">Vehicle is not available on the requested date.</option>
								<option value="Vehicle is currently under maintenance or repair.">Vehicle is currently under maintenance or repair.</option>
								<option value="Vehicle has already been sold or reserved.">Vehicle has already been sold or reserved.</option>
								<option value="The requested booking slot is already full.">The requested booking slot is already full.</option>
								<option value="Dealer is unable to accommodate the selected date and time.">Dealer is unable to accommodate the selected date and time.</option>
								<option value="Dealer does not offer test drives on the requested date.">Dealer does not offer test drives on the requested date.</option>
								<option value="Driving License is invalid or unclear.">Driving License is invalid or unclear.</option>
								<option value="Required documents were not provided or are incomplete.">Required documents were not provided or are incomplete.</option>
								<option value="Contact details provided by the user are invalid or incomplete.">Contact details provided by the user are invalid or incomplete.</option>
								<option value="User has a history of no-shows or cancellations.">User has a history of no-shows or cancellations.</option>
								<option value="Dealer is temporarily unable to provide test drives due to operational constraints.">Dealer is temporarily unable to provide test drives due to operational constraints.</option>
								<option value="Test drive services are temporarily paused at the showroom.">Test drive services are temporarily paused at the showroom.</option>
								<option value="Other">Other (Specify Reason)</option>
							</select>
						</div>
						<div class="form-group">
							<label for="comments">Additional Comments</label>
							<textarea class="form-control" id="dealer_comments" name="dealer_comments" rows="3"></textarea>
						</div>
						<input type="hidden" id="selectedRequestId" name="selectedRequestId">
						<input type="hidden" id="selectedStatus" name="selectedStatus">
					</form>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
					<button type="button" class="btn btn-primary" id="saveReason">Save</button>
				</div>
			</div>
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
						"data": null, // Serial number
						"render": function(data, type, row, meta) {
							return meta.row + 1;
						}
					},
					{
						"data": "status", // Action column with dropdown
						"render": function(data, type, row) {
							const options = `
                    <select class="status-dropdown form-control" data-id="${row.id}">
                        <option value="pending" ${data === 'pending' ? 'selected' : ''}>Pending</option>
                        <option value="accepted" ${data === 'accepted' ? 'selected' : ''}>Accepted</option>
                        <option value="rejected" ${data === 'rejected' ? 'selected' : ''}>Rejected</option>
                        <option value="completed" ${data === 'completed' ? 'selected' : ''}>Completed</option>
                        <option value="canceled" ${data === 'canceled' ? 'selected' : ''}>Canceled</option>
                    </select>`;
							return options;
						}
					},
					{
						"data": "license_file_path",
						"render": function(data, type, row) {
							if (data) {
								return `
            <div class="da-card box-shadow">
                <div class="da-card-photo">
                    <a href="${data}" data-fancybox="gallery" data-caption="License Image for ${row.unique_id}">
                        <img class="card-img-top vehicle-image" src="${data}" alt="${row.unique_id}" style="width: 100px; height: auto; cursor: pointer;">
                    </a>
					<div class="da-overlay">
                        <div class="da-social">
                            <h5 class="mb-10 color-white pd-20"></h5>
                            <ul class="clearfix">
                                <li>
                                    <a href="${data}" data-fancybox="gallery" data-caption="License Image for ${row.unique_id}">
                                        <i class="fa fa-picture-o"></i>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>`;
							} else {
								return 'No Image';
							}
						}
					},
					{
						"data": "customer_name"
					},
					{
						"data": "customer_phone"
					},
					{
						"data": "customer_email"
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
					},
					{
						"data": "formatted_dateOfVisit"
					},
					{
						"data": "timeOfVisit",
						"render": function(data, type, row) {
							return testDriveSlots[data] || 'Unknown Slot';
						}
					},
					{
						"data": "comments"
					},
					{
						"data": "formatted_created_at"
					},

				],
				"order": [
					[10, "desc"]
				], // Default ordering by created_at column
			});

			$(document).on('change', '.status-dropdown', function() {
				const selectedValue = $(this).val();
				const requestId = $(this).data('id');
				const emailId = $(this).data('customer_email');
				const dropdown = $(this);

				if (selectedValue === 'rejected' || selectedValue === 'canceled') {
					// Show modal for adding reason and comments
					$('#selectedRequestId').val(requestId);
					$('#selectedStatus').val(selectedValue);
					$('#reasonModal').modal('show');
				} else {
					// Show confirmation dialog for other statuses
					Swal.fire({
						title: 'Are you sure?',
						text: `You are about to update the status to "${selectedValue}".`,
						icon: 'warning',
						showCancelButton: true,
						confirmButtonColor: '#3085d6',
						cancelButtonColor: '#d33',
						confirmButtonText: 'Yes, update it!',
					}).then((result) => {
						if (result.isConfirmed) {
							updateStatus(requestId, selectedValue, emailId);
						} else {
							// Revert dropdown to its previous value if the user cancels
							dropdown.val(dropdown.data('current-value'));
						}
					});
				}
			});

			// Save Reason and Comments from Modal
			$('#saveReason').on('click', function() {
				const reason_selected = $('#reason_selected').val();
				const dealer_comments = $('#dealer_comments').val();
				const requestId = $('#selectedRequestId').val();
				const selectedStatus = $('#selectedStatus').val();

				if (!reason_selected) {
					showWarningToast('Please select a reason.');
					$('#reason_selected').focus();
					return;
				}

				$('#reasonModal').modal('hide');
				updateStatus(requestId, selectedStatus, reason_selected, dealer_comments);
			});

		});

		// Function to Update Status
		function updateStatus(requestId, status, reason_selected = null, dealer_comments = null) {
			$.ajax({
				url: base_url + 'dealer/update-test-drive-status',
				type: 'POST',
				data: {
					testDriveRequestId: requestId,
					status: status,
					reason_selected: reason_selected,
					dealer_comments: dealer_comments
				},
				dataType: 'json',
				success: function(response) {
					if (response.status === 'success') {
						showSuccessAlert(response.message);
						$('#testDriveTable').DataTable().ajax.reload(null, false);
					} else {
						showWarningToast(response.message);
					}
				},
				error: function() {
					showWarningToast(response.message);
				},
			});
		}
	</script>