	<?php
	echo view('dealer/includes/_header');
	echo view('dealer/includes/_sidebar');
	?>

	<div class="main-container">
		<div class="pd-ltr-20 xs-pd-20-10">
			<div class="card-box pd-20 height-100-p mb-30">
				<div class="row align-items-center">
					<div class="col-md-4">
						<img src="<?php echo base_url(); ?>/assets/vendors/images/banner-img.png" alt="">
					</div>
					<div class="col-md-8">

						<?php if ($mainBranch) { ?>
							<h4 class="font-20 weight-500 mb-10 text-capitalize">
								Welcome <div class="weight-600 font-30 text-blue">
									<?php
									echo isset($mainBranch['name']) && !empty($mainBranch['name']) ? $mainBranch['name'] : 'Branch Name';
									?>,
									<span>
										<?php
										echo (isset($mainBranch['city']) && !empty($mainBranch['city']) ? $mainBranch['city'] : 'City') . ', ' .
											(isset($mainBranch['state']) && !empty($mainBranch['state']) ? $mainBranch['state'] : 'State');
										?>
									</span>
								</div>
							</h4>
							<p class="font-18 max-width-600">
								<?php echo isset($mainBranch['short_description']) && !empty($mainBranch['short_description']) ? $mainBranch['short_description'] : ''; ?>
							</p>
						<?php } else { ?>
							<h4 class="font-20 weight-500 mb-10 text-capitalize">
								Welcome <div class="weight-600 font-30 text-blue">
									<?php
									echo isset($userData['username']) && !empty($userData['username']) ? $userData['username'] : ' User';
									?>,
								</div>
							</h4>
						<?php } ?>
					</div>
				</div>
			</div>

			<div class="row">
				<div class="col-md-6 col-sm-12 mb-30">
					<div class="pd-20 card-box height-100-p">
						<div class="clearfix mb-30">
							<div class="pull-left">
								<table class="table table-bordered">
									<thead>
										<tr>
											<th scope="col" colspan=2>
												<h4 class="h4 text-blue">Plan Details</h4>
											</th>
										</tr>
									</thead>
									<tbody>
										<tr>
											<th scope="row">Plan Name</th>
											<td scope="row"><?php echo $planData['planName'] ?></td>
										</tr>
										<tr>
											<th scope="row">Plan Desc</th>
											<td scope="row"><?php echo $planData['planDesc'] ?></td>
										</tr>
										<tr>
											<th scope="row">Order Receipt</th>
											<td scope="row"><?php echo $planData['receipt'] ?></td>
										</tr>
										<tr>
											<th scope="row">Plan Valid till</th>
											<td scope="row"><?php echo  date('d-m-Y', strtotime($planData['end_dt'])); ?></td>
										</tr>
										<tr>
											<th scope="row">Maximum Vehicle Listing</th>
											<td scope="row"><?php echo $planData['max_vehicle_listing_per_month'] ?></td>
										</tr>
										<tr>
											<th scope="row">Free Inventory Promotions</th>
											<td scope="row"><?php echo $planData['free_inventory_promotions'] ?></td>
										</tr>
										<tr>
											<th scope="row">Free Showroom Promotions</th>
											<td scope="row"><?php echo $planData['free_showroom_promotions'] ?></td>
										</tr>
										<tr>
											<th scope="row">Showroom Branch Listing</th>
											<td scope="row"><?php echo $planData['max_showroom_branches'] ?></td>
										</tr>
									</tbody>
								</table>
							</div>
						</div>
					</div>
				</div>
				<div class="col-md-6 col-sm-12 mb-30">
					<div class="pd-20 card-box height-100-p">
						<?= form_open('dealer/updatePlanPreference', ['id' => 'updatePlanPreference', 'method' => 'POST', 'enctype' => 'multipart/form-data']); ?>
						<?= csrf_field(); ?>
						<input type="hidden" name="dealerId" value="<?php echo $planData['dealerUserId']; ?>">
						<input type="hidden" name="transactionId" value="<?php echo $planData['transactionId']; ?>">
						<input type="hidden" name="activePlan" value="<?php echo $planData['activePlan']; ?>">
						<div class="clearfix mb-30">
							<div class="pull-left">
								<h4 class="h4 text-blue">Choose Vehicle To listed</h4>
								<?php foreach (VEHICLE_TYPE as $id => $type) : ?>
									<?php if (($planData['activePlan'] == 1 || $planData['activePlan'] == 2) && ($id == 1 || $id == 2) || ($planData['activePlan'] != 1 && $planData['activePlan'] != 2)) : ?>
										<div class="custom-control custom-radio mb-5">
											<input type="radio" id="vehicle_type_<?= $id ?>" name="vehicle_type" value="<?= $id ?>" class="custom-control-input" required>
											<label class="custom-control-label" for="vehicle_type_<?= $id ?>"><?= $type ?></label>
										</div>
									<?php endif; ?>
								<?php endforeach; ?>
							</div>
						</div>
						<div class="form-group mb-0 text-right">
							<input type="submit" class="btn btn-primary" value="Update Information">
						</div>
						<?= form_close() ?>
					</div>
				</div>
			</div>

			<div class="row">
				<div class="col-xl-3 mb-30">
					<div class="card-box height-100-p widget-style1">
						<div class="d-flex flex-wrap align-items-center">
							<div class="progress-data">
								<div id="chart"></div>
							</div>
							<div class="widget-data">
								<div class="h4 mb-0">2020</div>
								<div class="weight-600 font-14">Contact</div>
							</div>
						</div>
					</div>
				</div>
				<div class="col-xl-3 mb-30">
					<div class="card-box height-100-p widget-style1">
						<div class="d-flex flex-wrap align-items-center">
							<div class="progress-data">
								<div id="chart2"></div>
							</div>
							<div class="widget-data">
								<div class="h4 mb-0">400</div>
								<div class="weight-600 font-14">Deals</div>
							</div>
						</div>
					</div>
				</div>
				<div class="col-xl-3 mb-30">
					<div class="card-box height-100-p widget-style1">
						<div class="d-flex flex-wrap align-items-center">
							<div class="progress-data">
								<div id="chart3"></div>
							</div>
							<div class="widget-data">
								<div class="h4 mb-0">350</div>
								<div class="weight-600 font-14">Campaign</div>
							</div>
						</div>
					</div>
				</div>
				<div class="col-xl-3 mb-30">
					<div class="card-box height-100-p widget-style1">
						<div class="d-flex flex-wrap align-items-center">
							<div class="progress-data">
								<div id="chart4"></div>
							</div>
							<div class="widget-data">
								<div class="h4 mb-0">$6060</div>
								<div class="weight-600 font-14">Worth</div>
							</div>
						</div>
					</div>
				</div>
			</div>

			<!-- footer -->
			<?php echo view('dealer/includes/_footer'); ?>
		</div>
	</div>