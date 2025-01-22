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
                            <h4>View Showroom</h4>
                        </div>
                        <nav aria-label="breadcrumb" role="navigation">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="<?php echo base_url('dealer/dashboard'); ?>">Home</a></li>
                                <li class="breadcrumb-item">Manage Showroom</li>
                                <li class="breadcrumb-item active" aria-current="page">View Showroom Details</li>
                            </ol>
                        </nav>
                    </div>
                    <div class="col-md-6 col-sm-12 text-right">
                        <a href="<?php echo base_url('dealer/edit-branch/' . $branchDetails['id']); ?>" class="btn btn-outline-primary btn-md" rel="content-y" role="button">
                            <i class="icon-copy bi bi-list-stars"></i> Edit this Showroom
                        </a>
                        <a href="<?php echo base_url('dealer/list-branches'); ?>" class="btn btn-outline-primary btn-md" rel="content-y" role="button">
                            <i class="icon-copy bi bi-list-stars"></i> View Showrooms
                        </a>
                    </div>
                </div>
            </div>
            <div class="pd-20 bg-white border-radius-4 box-shadow mb-30">
                <div class="clearfix">
                    <h4 class="text-blue h4 mb-30">Showroom Summary</h4>
                </div>
                <div class="row">
                    <div class="col-md-6 col-lg-5">
                        <div class="showroom-thumbnail mb-30">
                            <p class="text-blue">Showroom Thumbnail</p>
                            <img class="d-block w-100" src="<?php echo isset($branchDetails['branch_thumbnail']) ? WHEELPACT_VEHICLE_UPLOAD_IMG_PATH . "branch_thumbnails/" . $branchDetails['branch_thumbnail'] : ''; ?>" alt="<?php echo isset($branchDetails['name']) ? $branchDetails['name'] : ''; ?>">
                        </div>
                    </div>

                    <div class="col-md-6 col-lg-7">
                        <div class="row mb-20">
                            <div class="col-md-4 col-lg-4">
                                <div class="view-showroom-data">
                                    <p class="text-blue mb-1">Showroom Branch Name</p>
                                    <h5 class="h5"><?php echo isset($branchDetails['name']) ? $branchDetails['name'] : ''; ?></h5>
                                </div>
                            </div>

                            <div class="col-md-4 col-lg-4">
                                <div class="view-showroom-data">
                                    <p class="text-blue mb-1">Showroom Branch Type</p>
                                    <h5 class="h5"><?php echo BRANCH_TYPE[$branchDetails['branch_type']]; ?></h5>
                                </div>
                            </div>

                            <div class="col-md-4 col-lg-4">
                                <div class="view-showroom-data">
                                    <p class="text-blue mb-1">Showroom Vehicle Type</p>
                                    <h5 class="h5"><?php echo VEHICLE_TYPE[$branchDetails['branch_supported_vehicle_type']]; ?></h5>
                                </div>
                            </div>

                            <div class="col-md-12">
                                <div class="showroom-desc-data">
                                    <p class="text-blue mb-1">Showroom Short Description</p>
                                    <p><?php echo isset($branchDetails['short_description']) ? $branchDetails['short_description'] : ''; ?></p>
                                </div>
                            </div>

                            <div class="col-md-4 col-lg-4">
                                <div class="view-showroom-data">
                                    <p class="text-blue mb-1">Contact Number</p>
                                    <h5 class="h5"><?php echo isset($branchDetails['contact_number']) ? $branchDetails['contact_number'] : ''; ?></h5>
                                </div>
                            </div>

                            <div class="col-md-4 col-lg-4">
                                <div class="view-showroom-data">
                                    <p class="text-blue mb-1">WhatsApp Number</p>
                                    <h5 class="h5">+91 <?php echo isset($branchDetails['whatsapp_no']) ? $branchDetails['whatsapp_no'] : ''; ?></h5>
                                </div>
                            </div>

                            <div class="col-md-4 col-lg-4">
                                <div class="view-showroom-data">
                                    <p class="text-blue mb-1">Email Address</p>
                                    <h5 class="h5"><?php echo isset($branchDetails['email']) ? $branchDetails['email'] : ''; ?></h5>
                                </div>
                            </div>

                            <div class="col-md-4 col-lg-4">
                                <div class="view-showroom-data">
                                    <p class="text-blue mb-1">Country</p>
                                    <h5 class="h5">
                                        <?php foreach ($countryList as $country) : ?>
                                            <?php if ($country['id'] == $branchDetails['country_id']) : ?>
                                                <?= $country['name']; ?>
                                            <?php endif; ?>
                                        <?php endforeach; ?>
                                    </h5>
                                </div>
                            </div>

                            <div class="col-md-4 col-lg-4">
                                <div class="view-showroom-data">
                                    <p class="text-blue mb-1">State</p>
                                    <h5 class="h5">
                                        <?php
                                        if (isset($stateList) && !empty($stateList)) {
                                            foreach ($stateList as $stateData) {
                                                if ($branchDetails['state_id'] == $stateData['id']) {
                                                    echo isset($stateData['name']) ? $stateData['name'] : '';
                                                    break; // Stop the loop once the matching state is found
                                                }
                                            }
                                        }
                                        ?>
                                    </h5>
                                </div>
                            </div>

                            <div class="col-md-4 col-lg-4">
                                <div class="view-showroom-data">
                                    <p class="text-blue mb-1">City</p>
                                    <h5 class="h5">
                                        <?php
                                        if (isset($cityList) && !empty($cityList)) {
                                            foreach ($cityList as $cityData) {
                                                if ($branchDetails['city_id'] == $cityData['id']) {
                                                    echo isset($cityData['name']) ? $cityData['name'] : '';
                                                    break;
                                                }
                                            }
                                        }
                                        ?>
                                    </h5>
                                </div>
                            </div>

                            <div class="col-md-12">
                                <div class="showroom-desc-data">
                                    <p class="text-blue mb-1">Showroom Branch Address</p>
                                    <p><?php echo isset($branchDetails['address']) ? $branchDetails['address'] : ''; ?></p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="pd-20 bg-white border-radius-4 box-shadow mb-30">
                <div class="row">
                    <div class="col-md-12 col-lg-8">
                        <div class="clearfix">
                            <h4 class="text-blue h4 mb-30">Showroom Banners</h4>
                        </div>
                        <div id="carouselExampleIndicators" class="carousel slide" data-ride="carousel">
                            <ol class="carousel-indicators">
                                <li data-target="#carouselExampleIndicators" data-slide-to="0" class="active"></li>
                                <li data-target="#carouselExampleIndicators" data-slide-to="1"></li>
                                <li data-target="#carouselExampleIndicators" data-slide-to="2"></li>
                            </ol>
                            <div class="carousel-inner">
                                <div class="carousel-item active">
                                    <img class="d-block w-100" src="<?php echo isset($branchDetails['branch_banner1']) ? WHEELPACT_VEHICLE_UPLOAD_IMG_PATH . "/branch_banners/" . $branchDetails['branch_banner1'] : ''; ?>" alt="<?php echo isset($branchDetails['name']) ? $branchDetails['name'] : ''; ?> Banner 1">
                                </div>
                                <div class="carousel-item">
                                    <img class="d-block w-100" src="<?php echo isset($branchDetails['branch_banner2']) ? WHEELPACT_VEHICLE_UPLOAD_IMG_PATH . "/branch_banners/" . $branchDetails['branch_banner2'] : ''; ?>" alt="<?php echo isset($branchDetails['name']) ? $branchDetails['name'] : ''; ?> Banner 2">
                                </div>
                                <div class="carousel-item">
                                    <img class="d-block w-100" src="<?php echo isset($branchDetails['branch_banner3']) ? WHEELPACT_VEHICLE_UPLOAD_IMG_PATH . "/branch_banners/" . $branchDetails['branch_banner3'] : ''; ?>" alt="<?php echo isset($branchDetails['name']) ? $branchDetails['name'] : ''; ?> Banner 3">
                                </div>
                            </div>
                            <a class="carousel-control-prev" href="#carouselExampleIndicators" role="button"
                                data-slide="prev">
                                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                <span class="sr-only">Previous</span>
                            </a>
                            <a class="carousel-control-next" href="#carouselExampleIndicators" role="button"
                                data-slide="next">
                                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                <span class="sr-only">Next</span>
                            </a>
                        </div>
                    </div>

                    <div class="col-md-12 col-lg-4">
                        <div class="clearfix">
                            <h4 class="text-blue h4 mb-30">Showroom Services</h4>
                        </div>
                        <ul class="list-group list-group-flush">
                            <?php foreach ($branchService as $key => $value) { ?>
                                <li class="list-group-item"><?php echo isset($value) ? $value : ''; ?></li>
                            <?php } ?>
                        </ul>

                    </div>
                </div>
            </div>

            <div class="pd-20 bg-white border-radius-4 box-shadow mb-30">
                <div class="clearfix">
                    <h4 class="text-blue h4 mb-30">Showroom Location</h4>
                </div>

                <div class="showroom-location">
                    <div id="map" class="embed-responsive embed-responsive-21by9"></div>
                    <div class="d-none">
                        <input type="text" name="map_latitude" id="map_latitude" value="<?php echo !empty($branchDetails['map_latitude']) ? $branchDetails['map_latitude'] : ''; ?>">
                        <input type="text" name="map_longitude" id="map_longitude" value="<?php echo !empty($branchDetails['map_longitude']) ? $branchDetails['map_longitude'] : ''; ?>">
                        <input type="text" name="map_city" id="map_city" value="<?php echo !empty($branchDetails['map_city']) ? $branchDetails['map_city'] : ''; ?>">
                        <input type="text" name="map_district" id="map_district" value="<?php echo !empty($branchDetails['map_district']) ? $branchDetails['map_district'] : ''; ?>">
                        <input type="text" name="map_state" id="map_state" value="<?php echo !empty($branchDetails['map_state']) ? $branchDetails['map_state'] : ''; ?>">
                    </div>
                    <!-- add iframe code of map  End -->
                </div>

            </div>

            <div class="pd-20 bg-white border-radius-4 box-shadow mb-30">
                <div class="clearfix">
                    <h4 class="text-blue h4 mb-30">Showroom Deliveries</h4>
                </div>

                <div class="row">

                    <?php foreach ($branchDeliverableImgs as $k => $val) { ?>
                        <div class="col-md-6 col-lg-4">
                            <div class="showroom-del-image mb-2">
                                <img class="img-fluid" src="<?php echo isset($val['img_name']) ? WHEELPACT_VEHICLE_UPLOAD_IMG_PATH . "branch_deliverables/" . $val['img_name'] : ''; ?>" alt="<?php echo isset($branchDetails['name']) ? $branchDetails['name'] : ''; ?> Delivery <? $k; ?>">
                            </div>
                        </div>
                    <?php } ?>
                </div>
            </div>
        </div>
        <?php echo view('dealer/includes/_footer'); ?>
    </div>
</div>
<script>
    /* init map in addbranch page */
    var lat = <?php echo !empty($branchDetails['map_latitude']) ? $branchDetails['map_latitude'] : '19.2742053'; ?>;
    var lng = <?php echo !empty($branchDetails['map_longitude']) ? $branchDetails['map_longitude'] : '72.8788707'; ?>;

    // Call the map initialization function with the coordinates
    window.initMap = function() {
        initMapWithCoordinates(lat, lng);
    };
</script>