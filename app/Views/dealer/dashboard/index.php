	<?php
	echo view('dealer/includes/_header');
	echo view('dealer/includes/_sidebar');
	?>

	<div class="main-container">
		<div class="pd-ltr-20">
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
				<div class="col-md-6 col-lg-4">
					<div class="card-box p-4 mb-3">
						<div class="dash-hero-data text-center">
							<h5 class="text-blue mb-4">Total Vehicles Listed</h5>
							<div class="row">
								<div class="col-md-6">
									<h3 class="mb-2"><?php echo isset($branchVehicleInsights['total_active_vehicles']) ? $branchVehicleInsights['total_active_vehicles'] : '0'; ?></h3>
									<p class="mb-0">Active Vehicles</p>
								</div>

								<div class="col-md-6">
									<h3 class="mb-2"><?php echo isset($branchVehicleInsights['total_inactive_vehicles']) ? $branchVehicleInsights['total_inactive_vehicles'] : '0'; ?></h3>
									<p class="mb-0">Inactive Vehicles</p>
								</div>
							</div>

						</div>
					</div>
				</div>

				<div class="col-md-6 col-lg-4">
					<div class="card-box p-4 mb-3">
						<div class="dash-hero-data text-center">
							<h5 class="text-blue mb-4">Active Promotions</h5>
							<div class="row">
								<div class="col-md-6">
									<h3 class="mb-2"><?php echo isset($vehiclePromoteCount) ? $vehiclePromoteCount : '0'; ?></h3>
									<p class="mb-0">Vehicle Promotions</p>
								</div>

								<div class="col-md-6">
									<h3 class="mb-2"><?php echo isset($showroomPromoteCount) ? $showroomPromoteCount : '0'; ?></h3>
									<p class="mb-0">Showroom Promotions</p>
								</div>
							</div>

						</div>
					</div>
				</div>

				<div class="col-md-6 col-lg-4">
					<div class="card-box p-4 mb-3">
						<div class="dash-hero-data text-center">
							<h5 class="text-blue mb-4">Pending Test Drive Request</h5>
							<div class="row">
								<div class="col-md-12">
									<h3 class="mb-2"><?php echo isset($testDriveRequestsCount['pending_requests']) ? $testDriveRequestsCount['pending_requests']  : '0'; ?></h3>
									<p class="mb-0">Test Drive Requests in Pending</p>
								</div>
							</div>

						</div>
					</div>
				</div>
			</div>

			<div class="card-box p-4 mb-30">
				<h5 class="text-blue mb-4">Test Drive Request Insights</h5>
				<div id="testDriveInsightsChart"></div>
			</div>

			<div class="card-box p-4 mb-30 d-none">
				<h5 class="text-blue mb-4">Promotion Engagement Trends Over Time</h5>
				<div id="promotionContainer">
				</div>
			</div>

			<!-- Promoted vehicles section start-->
			<div class="card-box p-4 mb-30">
				<div class="row align-items-center mb-30">
					<div class="col-6">
						<h5 class="text-blue">Latest Promoted Vehicles</h5>
					</div>
					<div class="col-6">
						<div class="text-right">
							<a class="btn btn-primary" href="#">
								View All
							</a>
						</div>
					</div>
				</div>
				<?php //if (isset($dealerPromotedVehicles) && count($dealerPromotedVehicles) > '0'): ?>
					<div class="row">
						<?php foreach ($dealerPromotedVehicles as $vehicle): ?>
							<div class="col-md-6 col-lg-4 vehicle-card-<?= $vehicle['id'] ?>">
								<div class="card card-box mb-3 position-relative">
									<img class="card-img-top vehicle-image" src="<?= WHEELPACT_VEHICLE_UPLOAD_IMG_PATH . 'vehicle_thumbnails/' . $vehicle['thumbnail_url'] ?>" alt="<?= $vehicle['unique_id'] ?>" />
									<div class="card-body">
										<h5 class="card-title weight-500 text-blue">
											<?= $vehicle['cmp_name'] . ' ' . $vehicle['model_name'] . ' ' . $vehicle['variantName'] ?>
										</h5>
										<div class="d-flex vehicle-overview">
											<div class="overview-badge">
												<h6>Year</h6>
												<h5><?= $vehicle['manufacture_year'] ?></h5>
											</div>
											<div class="overview-badge">
												<h6>Driven</h6>
												<h5><?= number_format($vehicle['kms_driven'], 0, '.', ',') ?> km</h5>
											</div>
											<div class="overview-badge">
												<h6>Fuel Type</h6>
												<h5><?= $vehicle['fuel_type'] ?></h5>
											</div>
											<div class="overview-badge">
												<h6>Owner</h6>
												<h5><?= ordinal($vehicle['owner']) ?></h5>
											</div>
										</div>
										<h5 class="card-title mt-3">
											<?= $vehicle['branch_name'] . ' - ' . VEHICLE_TYPE[$vehicle['vehicle_type']] ?>
										</h5>

										<?php if ($vehicle['is_active'] != 3): ?>
											<?php if ($vehicle['is_promoted'] == 1 && $vehicle['is_active'] == 4): ?>
												<a href="javascript:void(0);" class="btn btn-success mt-3 btn-block">Promotion ends on: <?= date('Y-m-d', strtotime($vehicle['promotion_end_date'])) ?></a>
												<a href="<?= base_url() . 'vehicle-promotion-details/' . encryptData($vehicle['id']); ?>" target="_blank" class="btn btn-info btn-block">Promotion Details</a>
												<a href="javascript:void(0);" class="btn btn-secondary mt-3 btn-block" disabled>Sold</a>
											<?php elseif ($vehicle['is_promoted'] == 1): ?>
												<a href="javascript:void(0);" class="btn btn-success mt-3 btn-block">Promotion ends on: <?= date('Y-m-d', strtotime($vehicle['promotion_end_date'])) ?></a>
												<a href="<?= base_url() . 'dealer/vehicle-promotion-details/' . encryptData($vehicle['id']); ?>" target="_blank" class="btn btn-info btn-block">Promotion Details</a>
											<?php elseif ($vehicle['is_active'] == 4): ?>
												<a href="javascript:void(0);" class="btn btn-secondary mt-3 btn-block" disabled>Sold</a>
											<?php elseif ($vehicle['is_active'] != 2): ?>
												<a href="<?= base_url() . 'dealer/promote-vehicle/' . encryptData($vehicle['id']) ?>" class="btn btn-primary mt-3 btn-block">Promote</a>
											<?php endif; ?>

											<?php if ($vehicle['is_active'] != 4 && $vehicle['is_active'] != 2): ?>
												<a href="#" class="btn btn-success mt-1 btn-block vehicleMarkSoldModal" data-vehicle-id="<?= encryptData($vehicle['id']) ?>" data-toggle="modal" data-target="#markSoldModal">Mark as Sold</a>
											<?php endif; ?>

											<div class="option-btn">
												<div class="dropdown">
													<a class="btn btn-link font-24 p-0 line-height-1 no-arrow dropdown-toggle" href="#" role="button" data-toggle="dropdown">
														<i class="dw dw-more"></i>
													</a>
													<div class="dropdown-menu dropdown-menu-right dropdown-menu-icon-list">
														<a class="dropdown-item" href="<?= base_url() . 'dealer/single-vehicle-info/' . encryptData($vehicle['id']) ?>"><i class="dw dw-eye"></i> View</a>
														<a class="dropdown-item" href="<?= base_url() . 'dealer/edit-vehicle/' . encryptData($vehicle['id']) ?>"><i class="dw dw-edit2"></i> Edit</a>

														<?php if ($vehicle['is_promoted'] != 1 && $vehicle['is_active'] != 4): ?>
															<?php if ($vehicle['is_active'] == 1): ?>
																<a class="dropdown-item sa-params enable-disable-vehicle" data-vehicle-id="<?= $vehicle['id'] ?>" data-vehicle-flag="2" href="#">
																	<i class="dw dw-delete-3"></i> Disable
																</a>
															<?php elseif ($vehicle['is_active'] == 2): ?>
																<a class="dropdown-item sa-params enable-disable-vehicle" data-vehicle-id="<?= $vehicle['id'] ?>" data-vehicle-flag="1" href="#">
																	<i class="dw dw-delete-3"></i> Enable
																</a>
															<?php endif; ?>
														<?php endif; ?>
													</div>
												</div>
											</div>
										<?php endif; ?>

										<div class="card-status-badge">
											<?php if ($vehicle['is_active'] === '1' || $vehicle['is_active'] === '4'): ?>
												<span class="badge badge-success">Active</span>
											<?php elseif ($vehicle['is_active'] === '3'): ?>
												<span class="badge badge-danger">Deleted</span>
											<?php elseif ($vehicle['is_active'] === '2'): ?>
												<span class="badge badge-warning">Inactive</span>
											<?php endif; ?>
										</div>
									</div>
								</div>
							</div>
						<?php endforeach; ?>
					</div>
				<?php //endif; ?>
			</div>
			<!-- Promoted vehicles section start-->

			<!-- Promoted showrooms section start-->
			<div class="card-box p-4 mb-30">
				<div class="row align-items-center mb-30">
					<div class="col-6">
						<h5 class="text-blue">Latest Promoted Showrooms</h5>
					</div>
					<div class="col-6">
						<div class="text-right">
							<a class="btn btn-primary" href="#">
								View All
							</a>
						</div>
					</div>
				</div>

				<?php if (isset($dealerPromotedShowrooms) && count($dealerPromotedShowrooms) > 0): ?>
					<div class="row">
						<?php foreach ($dealerPromotedShowrooms as $branch): ?>
							<div class="col-md-6 col-lg-4 branch-card-<?= $branch['id'] ?>">
								<div class="card card-box mb-3 position-relative">
									<img class="card-img-top showroom-image" src="<?= WHEELPACT_VEHICLE_UPLOAD_IMG_PATH . 'branch_thumbnails/' . $branch['branch_thumbnail'] ?>" alt="<?= htmlspecialchars($branch['name']) ?>" />
									<div class="card-body">
										<h5 class="card-title weight-500"><?= htmlspecialchars($branch['name']) ?></h5>
										<div class="showroom-location mb-2">
											<i class="icon-copy dw dw-pin-2"></i>
											<h6><?= htmlspecialchars($branch['map_city'] . ', ' . $branch['state']) ?></h6>
										</div>
										<div class="d-flex vehicle-overview">
											<div class="overview-badge">
												<h6>Branch</h6>
												<h5><?= $branch['branch_type_label'] ?></h5>
											</div>
										</div>
										<div class="d-flex align-items-center">
											<div class="store-rating-icon">
												<i class="icofont-star"></i>
											</div>
											<div class="store-rating-count"><?= round($branch['branch_rating'], 1) ?></div>
											<div class="store-reviews">
												<a class="view-reviews-link" href="#" data-branch-id="<?= encryptData($branch['id']) ?>">(<?= $branch['branch_review_count'] ?> Reviews)</a>
											</div>
										</div>
										<?php if ($branch['is_active'] == 2): ?>
											<a href="javascript:void(0);" class="btn btn-secondary mt-3 btn-block disabled" aria-disabled="true">Branch Disabled</a>
										<?php elseif ($branch['is_promoted'] == 1): ?>
											<a href="javascript:void(0);" class="btn btn-success mt-3 btn-block">Promotion ends on: <?= date('Y-m-d', strtotime($branch['promotion_end_date'])) ?></a>
											<a href="<?php echo base_url() . 'dealer/showroom-promotion-details/' . encryptData($branch['id']); ?>" target="_blank" class="btn btn-info btn-block">Promotion Details</a>
										<?php else: ?>
											<a href="<?= base_url() . 'dealer/promote-showroom/' . encryptData($branch['id']) ?>" class="btn btn-primary mt-3 btn-block">Promote</a>
										<?php endif; ?>

										<div class="option-btn">
											<div class="dropdown">
												<a class="btn btn-link font-24 p-0 line-height-1 no-arrow dropdown-toggle" href="#" role="button" data-toggle="dropdown">
													<i class="dw dw-more"></i>
												</a>
												<div class="dropdown-menu dropdown-menu-right dropdown-menu-icon-list">
													<a class="dropdown-item" href="<?= base_url() . 'dealer/single-branch-info/' . encryptData($branch['id']) ?>"><i class="dw dw-eye"></i> View</a>
													<a class="dropdown-item" href="<?= base_url() . 'dealer/edit-branch/' . encryptData($branch['id']) ?>"><i class="dw dw-edit2"></i> Edit</a>
													<?php if ($branch['is_promoted'] != 1): ?>
														<?php if ($branch['is_active'] == 1): ?>
															<a class="dropdown-item toggle-branch-status" href="#" data-branch-id="<?= encryptData($branch['id']) ?>" data-status="2"><i class="dw dw-ban"></i> Disable Branch</a>
														<?php elseif ($branch['is_active'] == 2): ?>
															<a class="dropdown-item toggle-branch-status" href="#" data-branch-id="<?= encryptData($branch['id']) ?>" data-status="1"><i class="dw dw-checked"></i> Enable Branch</a>
														<?php endif; ?>
													<?php endif; ?>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
						<?php endforeach; ?>
					</div>
				<?php endif; ?>
			</div>
			<!-- Promoted showrooms section end-->

			<!-- recent test drive requests table section start-->
			<?php if (isset($testDriveRequests) && count($testDriveRequests) > 0): ?>
				<div class="pd-20 card-box mb-30">
					<div class="clearfix mb-20">
						<h2 class="h4 pd-20">Recent Test Drive Requests</h2>
						<table class="table table-bordered">
							<thead>
								<tr>
									<th scope="col">#</th>
									<th scope="col">Customer Name</th>
									<th scope="col">Branch Name</th>
									<th scope="col">Vehicle Model</th>
									<th scope="col">Date of Visit</th>
									<th scope="col">Time of Visit</th>
									<th scope="col">Status</th>
									<th scope="col">License File</th>
								</tr>
							</thead>
							<tbody>
								<?php
								foreach ($testDriveRequests as $key => $data) {
									echo "<tr>
								<th scope='row'>" . ($key + 1) . "</th>
								<td>" . htmlspecialchars($data['customer_name']) . "</td>
								<td>" . htmlspecialchars($data['branch_name']) . "</td>
								<td>" . htmlspecialchars($data['model_name']) . "</td>
								<td>" . htmlspecialchars($data['formatted_dateOfVisit']) . "</td>
								<td>" . htmlspecialchars($data['timeOfVisit']) . "</td>
								<td>" . htmlspecialchars($data['status']) . "</td>
								<td><a href='" . WHEELPACT_VEHICLE_UPLOAD_IMG_PATH . "test_drive_data/license/" . $data['license_file_path'] . "' target='_blank'>View License</a></td>
							</tr>";
								}
								?>
							</tbody>
						</table>
					</div>
				</div>
			<?php endif ?>
			<!-- recent test drive requests table section end-->

			<!-- footer -->
			<?php echo view('dealer/includes/_footer'); ?>
		</div>
	</div>
	<script>
		$(document).ready(function() {
			function loadTestDriveStats() {

				const chartContainer = $("#testDriveInsightsChart");
				chartContainer.addClass("content-placeholder");


				$.ajax({
					url: "<?= base_url('dealer/load-test-drive-chart-data') ?>",
					method: "GET",
					dataType: "json",
					success: function(response) {
						// Remove the placeholder class once the response is received
						chartContainer.removeClass("content-placeholder");

						const vehicles = response.vehicles; // Vehicle names for the x-axis
						const seriesData = response.series; // Series data for statuses

						const options = {
							chart: {
								type: 'bar',
								height: 350,
								stacked: true,
								toolbar: {
									show: true
								}
							},
							series: seriesData, // Use the series data from the response
							xaxis: {
								categories: vehicles, // Use vehicle names as categories
							},
							title: {
								text: 'Test Drive Requests Insights',
							},
							fill: {
								opacity: 1,
							},
							legend: {
								position: 'right',
								offsetX: 0,
								offsetY: 50,
							},
							plotOptions: {
								bar: {
									horizontal: false,
									borderRadius: 10,
								},
							},
							dataLabels: {
								enabled: true,
								style: {
									fontSize: '12px',
									fontWeight: 'bold',
								},
								formatter: function(val, opts) {
									// Display totals at the top of each bar
									if (opts.w.globals.seriesTotals) {
										return opts.w.globals.seriesTotals[opts.dataPointIndex];
									}
									return val;
								},
								offsetY: -20 // Position the total above the bar
							},
							tooltip: {
								shared: true,
								intersect: false,
								y: {
									formatter: function(val) {
										return val + " requests";
									}
								}
							}
						};

						const chart = new ApexCharts(document.querySelector("#testDriveInsightsChart"), options);
						chart.render();
					},
					error: function() {
						// Remove the placeholder class in case of an error
						chartContainer.removeClass("content-placeholder");
						console.log('Error loading test drive stats. Please try again.');
					},
				});
			}
			loadTestDriveStats();
		});
	</script>