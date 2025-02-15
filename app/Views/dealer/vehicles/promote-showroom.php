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
					</div>

					<?php
					$first = true;
					if (isset($promotionPlans) && !empty($promotionPlans)) : ?>
						<?php foreach ($promotionPlans as $plan) :
							// Hide the free plan if the remaining showroom promotions are exhausted
							$hidePlan = ($remainingShowroomPromotions <= 0 && $plan['id'] === '1') ? 'd-none' : '';
							$isFreePlan = ($plan['id'] === '1'); // Check if this is the free plan
						?>
							<div class="pd-20 bg-white border-radius-10 box-shadow mb-30 position-relative <?= esc($hidePlan); ?>">
								<h4 class="text-blue h4"><?= esc($plan['promotionName']); ?></h4>

								<?php if ($isFreePlan) : ?>
									<p class="text-muted">
										Free promotions left: <strong><?= esc($remainingShowroomPromotions); ?></strong>
									</p>
								<?php endif; ?>

								<div class="custom-control custom-radio mb-5">
									<input
										type="radio"
										data-itemid="<?= esc($showroomDetails['id']); ?>"
										data-promotionunder="showroom"
										data-promotionplanid="<?= esc($plan['id']); ?>"
										id="promotionCustomRadio<?= esc($plan['id']); ?>"
										name="promotion-amount-radio"
										class="custom-control-input getPaymentmentAmt"
										value="<?= esc($plan['promotionAmount']); ?>"
										<?= $first ? 'checked' : ''; ?>>
									<label class="custom-control-label" for="promotionCustomRadio<?= esc($plan['id']); ?>">
										Promotion Validity for <?= esc($plan['promotionDaysValidity']); ?> Days
									</label>
								</div>

								<div class="promotion-plan-price">
									<h4>₹<?= esc($plan['promotionAmount']); ?></h4>
								</div>
							</div>
						<?php
							if ($hidePlan === '') { // Ensure the first valid plan is selected
								$first = false;
							}
						endforeach; ?>
					<?php endif; ?>
				</div>

				<div class="col-md-4">
					<div class="card card-box mb-3 stick-promo">
						<img class="card-img-top vehicle-image" src="<?php echo isset($showroomDetails['branch_thumbnail']) ? WHEELPACT_VEHICLE_UPLOAD_IMG_PATH . 'branch_thumbnails/' . $showroomDetails['branch_thumbnail'] : WHEELPACT_VEHICLE_UPLOAD_IMG_PATH . 'default-img.png'; ?>" alt="<?php echo $showroomDetails['name']; ?>" />
						<div class="card-body">
							<h5 class="card-title weight-500"><?php echo $showroomDetails['name'] ?></h5>
							<div class="showroom-location mb-2">
								<i class="icon-copy dw dw-pin-2"></i>
								<h6><?php echo $showroomDetails['cityName'] . ', ' . $showroomDetails['stateName']; ?></h6>
							</div>
							<p class="card-text"></p>
							<div class="d-flex vehicle-overview">
								<div class="overview-badge">
									<h6>Branch</h6>
									<h5><?php echo $showroomDetails['branch_type_label']; ?></h5>
								</div>
							</div>
							<div class="d-flex align-items-center">
								<div class="store-rating-icon">
									<i class="icofont-star"></i>
								</div>
								<div class="store-rating-count"><?php echo round($showroomDetails['branch_rating'], 1); ?></div>
								<div class="store-reviews">
									<a class="view-reviews-link" href="#" data-branch-id="' . $branch['id'] . '">(<?php echo $showroomDetails['branch_review_count']; ?> Reviews)</a>
								</div>
							</div>
							<button type="submit" class="btn btn-primary mt-3 btn-block promotionPlanPay">Promote</button>
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