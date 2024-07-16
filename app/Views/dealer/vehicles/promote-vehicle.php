<?php

use PhpParser\Node\Stmt\Foreach_;

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
					<?= form_open('create-rzp-order', ['id' => 'promotionPlanProcess', 'method' => 'POST']); ?>
					<?= csrf_field(); ?>
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
							<select class="custom-select formInput" name="promotionType" id="promotionType" required>
								<option value="">Choose...</option>
								<?php foreach (PROMTION_TYPE as $id => $type) : ?>
									<option value="<?= $id ?>"><?= $type ?></option>
								<?php endforeach; ?>
							</select>
						</div>
					</div>

					<?php $first = true;
					if (isset($promotionPlans) && !empty($promotionPlans)) : ?>
						<?php foreach ($promotionPlans as $plan) : ?>
							<div class="pd-20 bg-white border-radius-10 box-shadow mb-30 position-relative">
								<h4 class="text-blue h4"><?= esc($plan['promotionName']); ?></h4>
								<div class="custom-control custom-radio mb-5">
									<input checked type="radio" data-vehicleid="<?= esc($vehicleDetails['id']); ?>" data-promotionplanid="<?= esc($plan['id']); ?>" id="promotionCustomRadio<?= esc($plan['id']); ?>" name="promotion-amount-radio" class="custom-control-input getPaymentmentAmt" value="<?= esc($plan['promotionAmount']); ?>" <?= $first ? 'checked' : '' ?>>
									<label class="custom-control-label" for="promotionCustomRadio<?= esc($plan['id']); ?>">Promotion Validity for <?= esc($plan['promotionDaysValidity']); ?> Days</label>
								</div>

								<div class="promotion-plan-price">
									<h4>₹<?= esc($plan['promotionAmount']); ?></h4>
								</div>
							</div>
						<?php endforeach; ?>
					<?php endif; ?>
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
							<button type="submit" class="btn btn-primary mt-3 btn-block promotionPlanPay">Promote</button>
						</div>
						<?= form_close() ?>
						
						<div class="promotionPayBtnScript"></div>
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
		$("input[name='promotion-amount-radio']:checked").trigger('click');
	});
</script>