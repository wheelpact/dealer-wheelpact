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

<!-- MARK AS SOLD MODAL STARTS -->

<div class="modal fade" id="markSoldModal" tabindex="-1" role="dialog" aria-labelledby="markSoldModalLabel"
	aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered modal-lg" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="markSoldModalLabel">Mark as Sold</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<h5 class="text-blue h5">Mark Vehicle as Sold</h5>
				<p>Marking this vehicle as sold will remove it from the active inventory and update your dealership
					records. Please select the appropriate reason for marking this vehicle as sold. This helps us
					maintain accurate insights and data for your dealership's performance. If needed, you can
					re-list this vehicle at any time.</p>

				<label class="form-label">Select the reason for marking this vehicle as sold</label>
				<!-- Reason Dropdown -->
				<div class="form-group">
					<label for="reasonSelect">Reason for marking as sold:</label>
					<select class="form-control" id="reasonSelect">
						<option>Select reason</option>
						<option value="Price Negotiated">Price Negotiated</option>
						<option value="Customer Found">Customer Found</option>
						<option value="Vehicle Sold to Customer">Vehicle Sold to Customer</option>
						<option value="Reserved and Finalized">Reserved and Finalized</option>
						<option value="Transferred to Another Branch">Transferred to Another Branch</option>
						<option value="Removed from Inventory">Removed from Inventory</option>
						<option value="Vehicle Recalled">Vehicle Recalled</option>
						<option value="Others">Others</option>
					</select>
				</div>

				<!-- Hidden Text Area for "Others" -->
				<div id="otherReasonContainer" class="form-group d-none">
					<label for="otherReasonMessage">Please specify your reason:</label>
					<textarea class="form-control" id="otherReasonMessage" rows="3"></textarea>
				</div>

				<!-- Hidden input to store the vehicle ID -->
				<input type="hidden" id="vehicleId">
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
				<button type="button" id="vehicleMarkSoldbtn" class="btn btn-primary vehicleMarkSoldbtn">Confirm and Mark as Sold</button>
			</div>
		</div>
	</div>
</div>

<!-- MARK AS SOLD MODAL ENDS -->

<script>
	$(document).ready(function() {
		/* on page load trigger to load brands of cars & bikes both in select option filter */
		$('.custom-select.vehicle-type').trigger('change');

		let selectedVehicleId;

		// Store the vehicle ID when the "Mark as Sold" button is clicked
		$(document).on('click', '.vehicleMarkSoldModal', function() {
			selectedVehicleId = $(this).data('vehicle-id');
		});

		// Handle the "Confirm and Mark as Sold" button click
		$('#vehicleMarkSoldbtn').on('click', function() {
			const reason = $('#reasonSelect').val(); // Get the selected reason
			const otherReasonMessage = $('#otherReasonMessage').val(); // Get the "Others" text area message (if present)
			let finalReason = reason;

			// Check if "Others" is selected and validate the text area input
			if (reason === "Others") {
				if (!otherReasonMessage || otherReasonMessage.trim() === "") {
					showWarningToast("Please provide a valid reason in the message box.");
					return;
				}
				finalReason = otherReasonMessage; // Set the final reason to the input text
			}

			if (!reason || reason === "Select reason") {
				showWarningToast("Please select a valid reason before proceeding.");
				return;
			}

			// Show SweetAlert2 confirmation dialog
			Swal.fire({
				title: 'Are you sure?',
				text: 'Do you want to mark this vehicle as sold?',
				icon: 'warning',
				showCancelButton: true,
				confirmButtonColor: '#3085d6',
				cancelButtonColor: '#d33',
				confirmButtonText: 'Yes, confirm it!',
			}).then((result) => {
				if (result.isConfirmed) {
					$.ajax({
						url: base_url + 'dealer/update-vehicle-sold-status', // Replace with your actual backend endpoint
						method: 'POST',
						data: {
							vehicle_id: selectedVehicleId,
							reason: finalReason // Send the final reason
						},
						success: function(response) {
							if (response.success) {
								Swal.fire('Updated!', response.message, 'success');
								showSuccessAlert(response.message);
								$('#markSoldModal').modal('hide'); // Close the modal

								/* // Update the UI: Mark button as "Sold" and make it non-clickable */
								$(`[data-vehicle-id="${selectedVehicleId}"]`)
									.text('Sold')
									.removeClass('btn-success vehicleMarkSoldModal')
									.addClass('btn-secondary')
									.attr('disabled', true);
							} else {
								showErrorAlert(response.message);
							}
						},
						error: function() {
							showErrorAlert('An error occurred. Please try again later');
						}
					});
				}
			});
		});

		// Show/Hide the "Other Reason" text area based on the selected reason
		$('#reasonSelect').on('change', function() {
			const selectedReason = $(this).val();
			if (selectedReason === "Others") {
				$('#otherReasonContainer').removeClass('d-none'); // Show the text area
			} else {
				$('#otherReasonContainer').addClass('d-none'); // Hide the text area
				$('#otherReasonMessage').val(''); // Clear the text area
			}
		});
	});
</script>