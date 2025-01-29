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
							<h4>Promotion Details</h4>
						</div>
						<nav aria-label="breadcrumb" role="navigation">
							<ol class="breadcrumb">
								<li class="breadcrumb-item"><a href="index.html">Home</a></li>
								<li class="breadcrumb-item active" aria-current="page">Promotions</li>
								<li class="breadcrumb-item"><a href="vehicle-promotions.html">Vehicle Promotions</a></li>
								<li class="breadcrumb-item active" aria-current="page">Promotion Details</li>
							</ol>
						</nav>
					</div>
				</div>
			</div>

			<div class="row">
				<div class="col-md-8">
					<div class="pd-20 bg-white border-radius-10 box-shadow mb-30">
						<h4 class="text-blue h4">Promotion ID: #<?php echo str_replace('order_', '', $promotionDetails['orderId']); ?></h4>

						<div class="row mt-3">
							<div class="col-sm-6 col-md-6 col-lg-4">
								<div class="form-group">
									<label>Promotion Start Date</label>
									<h6><?php echo date('d-m-y', strtotime($promotionDetails['start_dt'])); ?></h6>
								</div>
							</div>
							<div class="col-sm-6 col-md-6 col-lg-4">
								<div class="form-group">
									<label>Promotion End Date</label>
									<h6><?php echo date('d-m-y', strtotime($promotionDetails['end_dt'])); ?></h6>
								</div>
							</div>

							<div class="col-sm-6 col-md-6 col-lg-4">
								<div class="form-group">
									<label>Plan Name</label>
									<h6><?php echo $promotionDetails['promotionName']; ?></h6>
								</div>
							</div>

							<div class="col-sm-6 col-md-6 col-lg-4">
								<div class="form-group">
									<label>Amount Paid</label>
									<h6>₹ <?php echo number_format($promotionDetails['amount'], 0, '.', ','); ?></h6>
								</div>
							</div>

							<div class="col-sm-6 col-md-6 col-lg-4">
								<div class="form-group">
									<label>Plan Validity</label>
									<h6><?php echo $promotionDetails['promotionDaysValidity']; ?> days</h6>
								</div>
							</div>

							<div class="col-sm-6 col-md-6 col-lg-4">
								<div class="form-group">
									<label>Promotion Status</label>
									<h6>
										<button type="button" class="btn btn-sm <?php echo ($promotionDetails['promotion_status'] == 'Active') ? 'btn-success' : 'btn-danger'; ?>">
											<?php echo $promotionDetails['promotion_status']; ?>
										</button>

									</h6>
								</div>
							</div>

							<div class="col-sm-12 col-md-12 col-lg-12">
								<div class="form-group">
									<label>Promotion Location</label>
									<h6 class="text-blue"><?php echo $vehicleDetails['branch_name']; ?></h6>
									<h6><?php echo $vehicleDetails['branch_address']; ?></h6>
								</div>
							</div>

						</div>
					</div>
					<?php if ($promotionDetails['promotion_status'] == 'Active'): ?>
						<div class="text-right mb-4">
							<a class="btn btn-danger sa-params delete-promotion" href="#" data-id="<?php echo encryptData($promotionDetails['promotionId']); ?>">Delete Promotion</a>
						</div>
					<?php endif ?>

				</div>

				<div class="col-md-4">
					<div class="card card-box mb-3 position-relative">
						<img class="card-img-top showroom-image" src="<?php echo WHEELPACT_VEHICLE_UPLOAD_IMG_PATH . 'vehicle_thumbnails/' . $vehicleDetails['thumbnail_url']; ?>" alt="<?php echo $vehicleDetails['cmp_name'] . ' ' . $vehicleDetails['cmp_model_name'] . ' ' . $vehicleDetails['variantName']; ?>" />

						<div class="card-body">
							<h5 class="card-title weight-500">
								<?php echo $vehicleDetails['cmp_name'] . ' ' . $vehicleDetails['cmp_model_name'] . ' ' . $vehicleDetails['variantName']; ?>
							</h5>

							<div class="showroom-location mb-2">
								<i class="icon-copy dw dw-pin-2"></i>
								<h5><?php echo $vehicleDetails['branch_location']; ?></h5>
							</div>

							<div class="d-flex vehicle-overview">
								<div class="overview-badge">
									<h6>Year</h6>
									<h5><?php echo $vehicleDetails['manufacture_year']; ?></h5>
								</div>

								<div class="overview-badge">
									<h6>Driven</h6>
									<h5><?php echo $vehicleDetails['kms_driven']; ?></h5>
								</div>

								<div class="overview-badge">
									<h6>Fuel Type</h6>
									<h5><?php echo $vehicleDetails['fuelTypeName']; ?></h5>
								</div>

								<div class="overview-badge">
									<h6>Owner</h6>
									<h5><?php echo OWNER_TYPE[$vehicleDetails['owner']]; ?></h5>
								</div>

								<div class="wishlist">
									<i class="icofont-heart"></i>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<!-- footer -->
		<?php echo view('dealer/includes/_footer'); ?>
	</div>
</div>]
<script>
	$(document).ready(function() {
		$('.delete-promotion').on('click', function(e) {
			e.preventDefault();

			var promotionId = $(this).data('id'); // Get promotion ID

			Swal.fire({
				title: "Are you sure?",
				text: "This action will delete the promotion!",
				icon: "warning",
				showCancelButton: true,
				confirmButtonColor: "#d33",
				cancelButtonColor: "#3085d6",
				confirmButtonText: "Yes, delete it!"
			}).then((result) => {
				if (result.isConfirmed) {
					$.ajax({
						url: base_url + 'dealer/delete-promotion',
						type: 'POST',
						data: {
							promotion_id: promotionId
						},
						dataType: 'json',
						success: function(response) {
							if (response.status === 'success') {
								showSuccessAlert(response.message);

								setTimeout(function() {
									location.reload();
								}, 3000);

							} else {
								showErrorAlert(response.message);
							}
						},
						error: function() {
							showErrorAlert('Something went wrong. Please try again.');
						}
					});
				}
			});
		});
	});
</script>