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
							<h4>Promote</h4>
						</div>
						<nav aria-label="breadcrumb" role="navigation">
							<ol class="breadcrumb">
								<li class="breadcrumb-item"><a href="<?php echo base_url('dealer/dashboard'); ?>">Home</a></li>
								<li class="breadcrumb-item active" aria-current="page"><a href="<?php echo base_url('dealer/dashboard'); ?>">Vehicles</a></li>
								<li class="breadcrumb-item active" aria-current="page">Promote</li>
							</ol>
						</nav>
					</div>
				</div>
			</div>

			<div class="row">
				<div class="col-md-8">
					<div class="pd-20 bg-white border-radius-10 box-shadow mb-30">
						<h4 class="text-blue h4">Select Your Promotion Package</h4>
						<div class="promotion-points">
							<div class="d-flex">
								<i class="icon-copy dw dw-check"></i>
								<p>Reachout to more buyers</p>
							</div>
							<div class="d-flex">
								<i class="icon-copy dw dw-check"></i>
								<p>Get better visibility on your Vehicles</p>
							</div>
							<div class="d-flex">
								<i class="icon-copy dw dw-check"></i>
								<p>Sell you vehicles faster</p>
							</div>
						</div>
						<div class="form-group col-6">
							<label>Promote Under<span class="required">*</span></label>
							<select class="custom-select">
								<option>Choose...</option>
								<option>Featured</option>
								<option>On-Sale</option>
							</select>
						</div>
					</div>
					<div class="pd-20 bg-white border-radius-10 box-shadow mb-30 position-relative">
						<h4 class="text-blue h4">Silver Plan</h4>
						<div class="custom-control custom-radio mb-5">
							<input checked type="radio" id="customRadio1" name="custom-radio" class="custom-control-input getPaymentmentAmt" value="49">
							<label class="custom-control-label" for="customRadio1">Promotion Validity for 7 Days</label>
						</div>

						<div class="promotion-plan-pice">
							<h4>₹49</h4>
						</div>
					</div>

					<div class="pd-20 bg-white border-radius-10 box-shadow mb-30 position-relative">
						<h4 class="text-blue h4">Gold Plan</h4>
						<div class="custom-control custom-radio mb-5">
							<input type="radio" id="customRadio2" name="custom-radio" class="custom-control-input getPaymentmentAmt" value="149">
							<label class="custom-control-label" for="customRadio2">Promotion Validity for 15 Days</label>
						</div>

						<div class="promotion-plan-pice">
							<h4>₹149</h4>
						</div>
					</div>

					<div class="pd-20 bg-white border-radius-10 box-shadow mb-30 position-relative">
						<h4 class="text-blue h4">Titanium Plan</h4>
						<div class="custom-control custom-radio mb-5">
							<input type="radio" id="customRadio3" name="custom-radio" class="custom-control-input getPaymentmentAmt" value="249">
							<label class="custom-control-label" for="customRadio3">Promotion Validity for 30 Days</label>
						</div>

						<div class="promotion-plan-pice">
							<h4>₹249</h4>
						</div>
					</div>
				</div>

				<div class="col-md-4">
					<div class="card card-box mb-3 stick-promo">
						<img class="card-img-top vehicle-image" src="<?php echo isset($vehicleDetails['thumbnail_url']) ? WHEELPACT_VEHICLE_UPLOAD_IMG_PATH . 'vehicle_thumbnails/' . $vehicleDetails['thumbnail_url'] : WHEELPACT_VEHICLE_UPLOAD_IMG_PATH . 'default-img.png'; ?>" alt="<?php echo $vehicleDetails['cmp_name'] . ' ' . $vehicleDetails['cmp_model_name']; ?>" />
						<div class="card-body">
							<h5 class="card-title weight-500"><?php echo $vehicleDetails['cmp_name'] . ' ' . $vehicleDetails['cmp_model_name'] . ' ' . $vehicleDetails['variantName']; ?></h5>
							<p class="card-text">
							<div class="d-flex vehicle-overview">
								<div class="overview-badge">
									<h6>Year</h6>
									<h5><?php echo $vehicleDetails['manufacture_year']; ?></h5>
								</div>

								<div class="overview-badge">
									<h6>Driven</h6>
									<h5><?php echo $vehicleDetails['kms_driven']; ?>Km</h5>
								</div>

								<div class="overview-badge">
									<h6>Fuel Type</h6>
									<h5><?php echo $vehicleDetails['fuelTypeName']; ?></h5>
								</div>

								<div class="overview-badge">
									<h6>Owner</h6>
									<h5><?php echo $vehicleDetails['owner']; ?></h5>
								</div>
								<div class="wishlist">
									<i class="icofont-heart"></i>
								</div>
							</div>
							<h5 class="card-title mt-3"><?php echo $vehicleDetails['branch_name']; ?></h5>
							<h6 class="mb-10"><?php echo VEHICLE_TYPE[$vehicleDetails['vehicle_type']]; ?></h6>
							<a href="#" class="btn btn-primary mt-3 btn-block promotionPay">Pay ₹<span id="planValue"></span></a>
						</div>
					</div>
				</div>
			</div>
		</div>
		<!-- footer -->
		<?php echo view('dealer/includes/_footer'); ?>
	</div>
</div>