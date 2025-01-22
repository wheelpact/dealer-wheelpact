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
                            <i class="icon-copy bi bi-list-stars"></i> Edit This Branch
                        </a>
                        <a href="<?php echo base_url('dealer/list-branches'); ?>" class="btn btn-outline-primary btn-md" rel="content-y" role="button">
                            <i class="icon-copy bi bi-list-stars"></i> View Branches
                        </a>
                    </div>
                </div>
            </div>
            <div class="pd-20 bg-white border-radius-4 box-shadow mb-30">
                <div class="clearfix">
                    <h4 class="text-blue h4">Showroom Summary</h4>
                </div>
                <div class="row">

                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="branchType">Showroom Branch Type</label>
                            <select class="form-control col-12 custom-select branchType" name="branchType" disabled>
                                <option value="0" selected>Select Branch Type</option>
                                <?php foreach (BRANCH_TYPE as $id => $type) : ?>
                                    <?php if ($id != 0) : ?>
                                        <option value="<?= $id ?>" <?php if ($id == $branchDetails['branch_type']) echo 'selected'; ?>><?= $type ?></option>
                                    <?php endif; ?>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="branchType">Showroom Supported Vehicle Type</label>
                            <select class="form-control custom-select vehicle-type col-12" name="branchSupportedVehicleType" disabled>
                                <option>Select Vehicle Type</option>
                                <?php foreach (VEHICLE_TYPE as $id => $type) : ?>
                                    <option value="<?= $id ?>" <?php echo ($id == $branchDetails['branch_supported_vehicle_type']) ? 'selected' : ''; ?>><?= $type ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="branchName">Showroom Branch Name</label>
                            <input type="text" value="<?php echo isset($branchDetails['name']) ? $branchDetails['name'] : ''; ?>" class="form-control" id="branchName" name="branchName" disabled>
                        </div>
                    </div>

                    <div class="col-md-6 col-lg-6 mb-30">
                        <div class="form-group">
                            <label for="address">Showroom Services</label>
                            <div class="btn-list">
                                <?php foreach ($branchService as $key => $value) { ?>
                                    <button type="button" class="btn" data-bgcolor="#3b5998" data-color="#ffffff" style="color: rgb(255, 255, 255); background-color: rgb(59, 89, 152);">
                                        <i class="icon-copy fa fa-wrench" aria-hidden="true"></i> <?php echo isset($value) ? $value : ''; ?>
                                    </button>
                                <?php } ?>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6 col-lg-6 mb-30">
                        <div class="form-group">
                            <label for="shortDescription">Showroom Short Description</label>
                            <textarea class="form-control" id="shortDescription" name="shortDescription" disabled><?php echo isset($branchDetails['short_description']) ? $branchDetails['short_description'] : ''; ?></textarea>
                        </div>
                    </div>
                </div>

                <hr>

                <div class="clearfix">
                    <h4 class="text-blue h4">Showroom Banners</h4>
                </div>

                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="branchName">Showroom Branch Thumbnail</label>
                            <div class="da-card box-shadow">
                                <div class="da-card-photo">
                                    <img src="<?php echo isset($branchDetails['branch_thumbnail']) ? WHEELPACT_VEHICLE_UPLOAD_IMG_PATH . "branch_thumbnails/" . $branchDetails['branch_thumbnail'] : ''; ?>" alt="">
                                    <div class="da-overlay">
                                        <div class="da-social">
                                            <h5 class="mb-10 color-white pd-20"></h5>
                                            <ul class="clearfix">
                                                <li>
                                                    <a href="<?php echo isset($branchDetails['branch_thumbnail']) ? WHEELPACT_VEHICLE_UPLOAD_IMG_PATH . "branch_thumbnails/" . $branchDetails['branch_thumbnail'] : ''; ?>" data-fancybox="images"><i class="fa fa-picture-o"></i></a>
                                                </li>
                                                <li>
                                                    <a href="#"><i class="fa fa-link"></i></a>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="branchBanner">Showroom Banner 1</label>
                            <div class="da-card box-shadow">
                                <div class="da-card-photo">
                                    <img src="<?php echo isset($branchDetails['branch_banner1']) ? WHEELPACT_VEHICLE_UPLOAD_IMG_PATH . "/branch_banners/" . $branchDetails['branch_banner1'] : ''; ?>" alt="">
                                    <div class="da-overlay">
                                        <div class="da-social">
                                            <h5 class="mb-10 color-white pd-20"></h5>
                                            <ul class="clearfix">
                                                <li>
                                                    <a href="<?php echo isset($branchDetails['branch_banner1']) ? WHEELPACT_VEHICLE_UPLOAD_IMG_PATH . "/branch_banners/" . $branchDetails['branch_banner1'] : ''; ?>" data-fancybox="images"><i class="fa fa-picture-o"></i></a>
                                                </li>
                                                <li>
                                                    <a href="#"><i class="fa fa-link"></i></a>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="branchBanner">Showroom Banner 2</label>
                            <div class="da-card box-shadow">
                                <div class="da-card-photo">
                                    <img src="<?php echo isset($branchDetails['branch_banner2']) ? WHEELPACT_VEHICLE_UPLOAD_IMG_PATH . "/branch_banners/" . $branchDetails['branch_banner2'] : ''; ?>" alt="">
                                    <div class="da-overlay">
                                        <div class="da-social">
                                            <h5 class="mb-10 color-white pd-20"></h5>
                                            <ul class="clearfix">
                                                <li>
                                                    <a href="<?php echo isset($branchDetails['branch_banner2']) ? WHEELPACT_VEHICLE_UPLOAD_IMG_PATH . "/branch_banners/" . $branchDetails['branch_banner2'] : ''; ?>" data-fancybox="images"><i class="fa fa-picture-o"></i></a>
                                                </li>
                                                <li>
                                                    <a href="#"><i class="fa fa-link"></i></a>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="branchBanner">Showroom Banner 3</label>
                            <div class="da-card box-shadow">
                                <div class="da-card-photo">
                                    <img src="<?php echo isset($branchDetails['branch_banner3']) ? WHEELPACT_VEHICLE_UPLOAD_IMG_PATH . "/branch_banners/" . $branchDetails['branch_banner3'] : ''; ?>" alt="">
                                    <div class="da-overlay">
                                        <div class="da-social">
                                            <h5 class="mb-10 color-white pd-20"></h5>
                                            <ul class="clearfix">
                                                <li>
                                                    <a href="<?php echo isset($branchDetails['branch_banner3']) ? WHEELPACT_VEHICLE_UPLOAD_IMG_PATH . "/branch_banners/" . $branchDetails['branch_banner3'] : ''; ?>" data-fancybox="images"><i class="fa fa-picture-o"></i></a>
                                                </li>
                                                <li>
                                                    <a href="#"><i class="fa fa-link"></i></a>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <hr>

                <div class="clearfix">
                    <h4 class="text-blue h4">Showroom Contact Details</h4>
                </div>

                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="contactNumber">Contact Number</label>
                            <input type="text" value="<?php echo isset($branchDetails['contact_number']) ? $branchDetails['contact_number'] : ''; ?>" class="form-control NumOnly" id="contactNumber" name="contactNumber" disabled>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="whatsapp_no">Whatsapp Number</label>
                            <input type="text" value="<?php echo isset($branchDetails['whatsapp_no']) ? $branchDetails['whatsapp_no'] : ''; ?>" class="form-control NumOnly" id="whatsapp_no" name="whatsapp_no" disabled>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="email">Email</label>
                            <input type="email" value="<?php echo isset($branchDetails['email']) ? $branchDetails['email'] : ''; ?>" class="form-control" id="email" name="email" disabled>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="chooseCountry">Country</label>
                            <select class="form-control col-12 custom-select country" name="chooseCountry" disabled>
                                <option value="0">Select Country</option>
                                <?php foreach ($countryList as $id => $country) : ?>
                                    <option value="<?= $country['id'] ?>" <?= ($country['id'] == $branchDetails['country_id']) ? 'selected' : '' ?>>
                                        <?= $country['name'] ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="chooseState">State</label>
                            <select class="form-control" id="chooseState" name="chooseState" disabled>
                                <option value="">Choose State</option>
                                <?php if (isset($stateList) && !empty($stateList)) {
                                    foreach ($stateList as $stateData) { ?>
                                        <option value="<?php echo isset($stateData['id']) ? $stateData['id'] : ''; ?>" <?php if ($branchDetails['state_id'] == $stateData['id']) echo 'selected'; ?>><?php echo isset($stateData['name']) ? $stateData['name'] : ''; ?></option>
                                <?php }
                                } ?>
                            </select>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="chooseCity">City</label>
                            <select class="form-control" id="chooseCity" name="chooseCity" disabled>
                                <option value="">Choose City</option>
                                <?php if (isset($cityList) && !empty($cityList)) {
                                    foreach ($cityList as $cityData) { ?>
                                        <option value="<?php echo isset($cityData['id']) ? $cityData['id'] : ''; ?>" <?php if ($branchDetails['city_id'] == $cityData['id']) echo 'selected'; ?>><?php echo isset($cityData['name']) ? $cityData['name'] : ''; ?></option>
                                <?php }
                                } ?>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12 mb-30">
                        <div class="form-group">
                            <label for="address">Showroom Branch Address</label>
                            <textarea class="form-control" id="address" name="address" disabled><?php echo isset($branchDetails['address']) ? $branchDetails['address'] : ''; ?></textarea>
                        </div>
                    </div>

                </div>

                

                <div class="row">
                    <div class="col-lg-12 col-md-12">
                        <div class="form-group">
                            <label for="location-input">Map</label>
                            <!-- Map Display -->
                            <div id="map" class="embed-responsive embed-responsive-21by9"></div>
                        </div>
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

                <hr>

                <div class="clearfix">
                    <h4 class="text-blue h4">Showroom Delivery Images</h4>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="shortDescription">Deliverable Images</label>
                            <div class="product-wrap">
                                <div class="product-detail-wrap mb-30">
                                    <div class="row">
                                        <div id="carouselExampleIndicators" class="carousel slide" data-ride="carousel">
                                            <ol class="carousel-indicators">
                                                <?php foreach ($branchDeliverableImgs as $key => $value) { ?>
                                                    <li data-target="#carouselExampleIndicators" data-slide-to="<?php echo $key; ?>" <?php if ($key == 0) {
                                                                                                                                            echo 'class="active"';
                                                                                                                                        } ?>></li>
                                                <?php } ?>
                                            </ol>
                                            <div class=" carousel-inner">
                                                <?php foreach ($branchDeliverableImgs as $k => $val) { ?>
                                                    <div class="carousel-item <?php if ($k == 0) {
                                                                                    echo 'active';
                                                                                } ?>">
                                                        <img class="d-block w-100" src="<?php echo isset($val['img_name']) ? WHEELPACT_VEHICLE_UPLOAD_IMG_PATH . "branch_deliverables/" . $val['img_name'] : ''; ?>">
                                                    </div>
                                                <?php } ?>

                                            </div>
                                            <a class="carousel-control-prev" href="#carouselExampleIndicators" role="button" data-slide="prev">
                                                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                                <span class="sr-only">Previous</span>
                                            </a>
                                            <a class="carousel-control-next" href="#carouselExampleIndicators" role="button" data-slide="next">
                                                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                                <span class="sr-only">Next</span>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
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