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
                            <h4>Edit Showroom</h4>
                        </div>
                        <nav aria-label="breadcrumb" role="navigation">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="<?php echo base_url('admin/dashboard'); ?>">Home</a></li>
                                <li class="breadcrumb-item">Manage Showroom</li>
                                <li class="breadcrumb-item active" aria-current="page">Edit Showroom Details</li>
                            </ol>
                        </nav>
                    </div>
                    <div class="col-md-6 col-sm-12 text-right">
                        <a href="<?php echo base_url('dealer/single-branch-info/' . $branchDetails['id']); ?>" class="btn btn-outline-primary btn-md" rel="content-y" role="button">
                            <i class="icon-copy bi bi-list-stars"></i> View this Branch
                        </a>
                        <a href="<?php echo base_url('dealer/list-branches'); ?>" class="btn btn-outline-primary btn-md" rel="content-y" role="button">
                            <i class="icon-copy bi bi-list-stars"></i> View All Branches
                        </a>
                    </div>
                </div>
            </div>
            <div class="pd-20 bg-white border-radius-4 box-shadow mb-30">
                <?= form_open('dealer/edit-update-branch-details', ['id' => 'edit_update_branch_details', 'method' => 'POST', 'enctype' => 'multipart/form-data']); ?>
                <?= csrf_field(); ?>
                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="selectDealer">Dealer:</label>
                            <input type="text" value="<?php echo !empty($branchDetails['owner_name']) ? $branchDetails['owner_name'] : ''; ?>" class="form-control" disabled readonly>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="branchType">Branch Type:</label>
                            <select class="form-control col-12 custom-select branchType" id="branchType" name="branchType" disabled readonly>
                                <option value="0" selected>Select Branch Type</option>
                                <?php foreach (BRANCH_TYPE as $id => $type) : ?>
                                    <?php if ($id != 0) : ?>
                                        <option value="<?= $id ?>" <?php if ($id == $branchDetails['branch_type']) echo 'selected'; ?>><?= $type ?></option>
                                    <?php endif; ?>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="branchName">Branch Name:</label>
                            <input type="text" value="<?php echo !empty($branchDetails['name']) ? $branchDetails['name'] : ''; ?>" class="form-control" id="branchName" name="branchName" disabled>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="branchType">Supported Vehicle Type:</label>
                            <select class="form-control custom-select vehicle-type col-12" name="branchSupportedVehicleType" disabled readonly>
                                <option>Select Vehicle Type</option>
                                <?php foreach (VEHICLE_TYPE as $id => $type) : ?>
                                    <option value="<?= $id ?>" <?php echo ($id == $branchDetails['branch_supported_vehicle_type']) ? 'selected' : ''; ?>><?= $type ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="branchName">Branch Thumbnail:</label>
                            <input type="file" class="form-control" id="branchThumbnail" name="branchThumbnail">
                            <div class="da-card box-shadow mt-3">
                                <div class="da-card-photo">
                                    <img src="<?php echo !empty($branchDetails['branch_thumbnail']) ? WHEELPACT_VEHICLE_UPLOAD_IMG_PATH . "branch_thumbnails/" . $branchDetails['branch_thumbnail'] : NO_IMAGE_AVAILABLE; ?>" alt="">
                                    <div class="da-overlay">
                                        <div class="da-social">
                                            <h5 class="mb-10 color-white pd-20"></h5>
                                            <ul class="clearfix">
                                                <li>
                                                    <a href="<?php echo !empty($branchDetails['branch_thumbnail']) ? WHEELPACT_VEHICLE_UPLOAD_IMG_PATH . "branch_thumbnails/" . $branchDetails['branch_thumbnail'] : NO_IMAGE_AVAILABLE; ?>" data-fancybox="images"><i class="fa fa-picture-o"></i></a>
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
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="branchName">Branch Logo:</label>
                            <input type="file" class="form-control" id="branchLogo" name="branchLogo">
                            <div class="da-card box-shadow mt-3">
                                <div class="da-card-photo">
                                    <img src="<?php echo !empty($branchDetails['branch_logo']) ? WHEELPACT_VEHICLE_UPLOAD_IMG_PATH . "branch_logos/" . $branchDetails['branch_logo'] : NO_IMAGE_AVAILABLE; ?>" alt="">
                                    <div class="da-overlay">
                                        <div class="da-social">
                                            <h5 class="mb-10 color-white pd-20"></h5>
                                            <ul class="clearfix">
                                                <li>
                                                    <a href="<?php echo !empty($branchDetails['branch_logo']) ? WHEELPACT_VEHICLE_UPLOAD_IMG_PATH . "branch_logos/" . $branchDetails['branch_logo'] : NO_IMAGE_AVAILABLE; ?>" data-fancybox="images"><i class="fa fa-picture-o"></i></a>
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
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="branchBanner">Banner 1:</label>
                            <input type="file" class="form-control" id="branchBanner1" name="branchBanner1">
                            <div class="da-card box-shadow mt-3">
                                <div class="da-card-photo">
                                    <img src="<?php echo !empty($branchDetails['branch_banner1']) ? WHEELPACT_VEHICLE_UPLOAD_IMG_PATH . "branch_banners/" . $branchDetails['branch_banner1'] : NO_IMAGE_AVAILABLE; ?>" alt="">
                                    <div class="da-overlay">
                                        <div class="da-social">
                                            <h5 class="mb-10 color-white pd-20"> </h5>
                                            <ul class="clearfix">
                                                <li>
                                                    <a href="<?php echo !empty($branchDetails['branch_banner1']) ? WHEELPACT_VEHICLE_UPLOAD_IMG_PATH . "branch_banners/" . $branchDetails['branch_banner1'] : NO_IMAGE_AVAILABLE; ?>" data-fancybox="images"><i class="fa fa-picture-o"></i></a>
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

                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="branchBanner">Banner 2:</label>
                            <input type="file" class="form-control" id="branchBanner2" name="branchBanner2">
                            <div class="da-card box-shadow mt-3">
                                <div class="da-card-photo">
                                    <img src="<?php echo !empty($branchDetails['branch_banner2']) ? WHEELPACT_VEHICLE_UPLOAD_IMG_PATH . "branch_banners/" . $branchDetails['branch_banner2'] : NO_IMAGE_AVAILABLE; ?>" alt="">
                                    <div class="da-overlay">
                                        <div class="da-social">
                                            <h5 class="mb-10 color-white pd-20"></h5>
                                            <ul class="clearfix">
                                                <li>
                                                    <a href="<?php echo !empty($branchDetails['branch_banner2']) ? WHEELPACT_VEHICLE_UPLOAD_IMG_PATH . "branch_banners/" . $branchDetails['branch_banner2'] : NO_IMAGE_AVAILABLE; ?>" data-fancybox="images"><i class="fa fa-picture-o"></i></a>
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

                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="branchBanner">Banner 3:</label>
                            <input type="file" class="form-control" id="branchBanner3" name="branchBanner3">
                            <div class="da-card box-shadow mt-3">
                                <div class="da-card-photo">
                                    <img src="<?php echo !empty($branchDetails['branch_banner3']) ? WHEELPACT_VEHICLE_UPLOAD_IMG_PATH . "branch_banners/" . $branchDetails['branch_banner3'] : NO_IMAGE_AVAILABLE; ?>" alt="">
                                    <div class="da-overlay">
                                        <div class="da-social">
                                            <h5 class="mb-10 color-white pd-20"></h5>
                                            <ul class="clearfix">
                                                <li>
                                                    <a href="<?php echo !empty($branchDetails['branch_banner3']) ? WHEELPACT_VEHICLE_UPLOAD_IMG_PATH . "branch_banners/" . $branchDetails['branch_banner3'] : NO_IMAGE_AVAILABLE; ?>" data-fancybox="images"><i class="fa fa-picture-o"></i></a>
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

                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="chooseCountry">Choose Country:</label>
                            <select class="form-control col-12 custom-select country" id="chooseCountry" name="chooseCountry">
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
                            <label for="chooseState">Choose State:</label>
                            <select class="form-control custom-select state" id="chooseState" name="chooseState">
                                <option value="">Choose State</option>
                                <?php if (isset($stateList) && !empty($stateList)) {
                                    foreach ($stateList as $stateData) { ?>
                                        <option value="<?php echo !empty($stateData['id']) ? $stateData['id'] : ''; ?>" <?php if ($branchDetails['state_id'] == $stateData['id']) echo 'selected'; ?>><?php echo !empty($stateData['name']) ? $stateData['name'] : ''; ?></option>
                                <?php }
                                } ?>
                            </select>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="chooseCity">Choose City:</label>
                            <select class="form-control  custom-select city" id="chooseCity" name="chooseCity">
                                <option value="">Choose City</option>
                                <?php if (isset($cityList) && !empty($cityList)) {
                                    foreach ($cityList as $cityData) { ?>
                                        <option value="<?php echo !empty($cityData['id']) ? $cityData['id'] : ''; ?>" <?php if ($branchDetails['city_id'] == $cityData['id']) echo 'selected'; ?>><?php echo !empty($cityData['name']) ? $cityData['name'] : ''; ?></option>
                                <?php }
                                } ?>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="address">Address:</label>
                            <textarea class="form-control" id="address" name="address"><?php echo !empty($branchDetails['address']) ? $branchDetails['address'] : ''; ?></textarea>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <!-- add iframe code of map Start -->
                        <div class="col-md-12 col-sm-12 mb-30">
                            <div class="form-group d-none">
                                <label for="branch_map">Branch Google Maps: </label>
                                <label class="text-right"><a href="https://support.google.com/maps/answer/7101463?hl=en" target="_blank">Help</a></label>
                                <textarea class="form-control" id="branch_map" name="branch_map"><?php echo !empty($branchDetails['branch_map']) ? $branchDetails['branch_map'] : ''; ?></textarea>
                            </div>

                            <div class="form-group">
                                <label for="location-input">Enter Branch Location:</label>
                                <input type="text" id="location-input" name="location" placeholder="Enter location" />
                                
                                <!-- Map Display -->
                                <div id="map" style="width: 100%; height: 400px;"></div>
                                <!-- Hidden Fields for Latitude, Longitude, City, District, State -->
                            </div>
                            <div class="d-none">
                                <input type="text" name="map_latitude" id="map_latitude" value="<?php echo !empty($branchDetails['map_latitude']) ? $branchDetails['map_latitude'] : ''; ?>">
                                <input type="text" name="map_longitude" id="map_longitude" value="<?php echo !empty($branchDetails['map_longitude']) ? $branchDetails['map_longitude'] : ''; ?>">
                                <input type="text" name="map_city" id="map_city" value="<?php echo !empty($branchDetails['map_city']) ? $branchDetails['map_city'] : ''; ?>">
                                <input type="text" name="map_district" id="map_district" value="<?php echo !empty($branchDetails['map_district']) ? $branchDetails['map_district'] : ''; ?>">
                                <input type="text" name="map_state" id="map_state" value="<?php echo !empty($branchDetails['map_state']) ? $branchDetails['map_state'] : ''; ?>">
                            </div>
                        </div>
                        <!-- add iframe code of map  End -->
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="contactNumber">Contact Number:</label>
                            <input type="text" minlength="9" maxlength="10" value="<?php echo !empty($branchDetails['contact_number']) ? $branchDetails['contact_number'] : ''; ?>" class="form-control numbersOnlyCheck" name="contactNumber" id="contactNumber" disabled>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="whatsapp_no">Whatsapp Number:</label>
                            <input type="text" minlength="9" maxlength="10" value="<?php echo !empty($branchDetails['whatsapp_no']) ? $branchDetails['whatsapp_no'] : ''; ?>" class="form-control numbersOnlyCheck" name="whatsapp_no" id="whatsapp_no">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="email">Email:</label>
                            <input type="email" value="<?php echo !empty($branchDetails['email']) ? $branchDetails['email'] : ''; ?>" class="form-control" id="email" disabled>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 col-sm-12 mb-30">
                        <div class="form-group">
                            <label for="shortDescription">Short Description:</label>
                            <textarea class="form-control" id="shortDescription" name="shortDescription"><?php echo !empty($branchDetails['short_description']) ? $branchDetails['short_description'] : ''; ?></textarea>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <!-- deliverable images Start -->
                    <div class="col-md-6 col-sm-12 mb-30">
                        <div class="form-group">
                            <label for="address">Add Deliverable Images:</label>
                            <div class="pull-right">
                                <a href="#input-validation-form" id="extend-deliverable-img" class="btn btn-primary btn-sm scroll-click collapsed" rel="content-y" data-toggle="collapse" role="button" aria-expanded="false"><i class="fa fa-plus"></i> Add </a>
                            </div>
                            <div class="col-md-10 col-sm-10">
                                <div id="extend-deliverable-img-field">
                                    <div class="form-group">
                                        <div class="input-group bootstrap-touchspin bootstrap-touchspin-injected"><input type="file" placeholder="Choose Image" name="deliverableImg[]" class="form-control deliverableImg" accept="image/png, image/jpeg"><span class="input-group-btn input-group-append"><button class="btn btn-primary bootstrap-touchspin-up remove-deliverable-img-field" type="button">-</button></span></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6 col-sm-12 mb-30">
                        <div class="form-group">
                            <label for="shortDescription">Deliverable Images:</label>
                            <div class="product-wrap">
                                <div class="product-detail-wrap mb-30">
                                    <div class="row">
                                        <div id="carouselExampleIndicators" class="carousel slide" data-ride="carousel">
                                            <ol class="carousel-indicators">
                                                <?php foreach ($branchDeliverableImgs as $key => $value) { ?>
                                                    <li data-target="#carouselExampleIndicators" data-slide-to="<?php echo $key; ?>" <?php
                                                                                                                                        if ($key == 0) {
                                                                                                                                            echo 'class="active"';
                                                                                                                                        } ?>></li>
                                                <?php } ?>
                                            </ol>
                                            <div class=" carousel-inner">
                                                <?php foreach ($branchDeliverableImgs as $k => $val) { ?>
                                                    <div class="carousel-item <?php
                                                                                if ($k == 0) {
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
                    <!-- deliverable images End -->
                </div>
                <div class="pull-right">
                    <input type="hidden" name="branchId" id="branchId" value="<?php echo !empty($branchDetails['id']) ? $branchDetails['id'] : ''; ?>">
                    <button type="submit" class="btn btn-success">Update</button>
                </div>
                <?= form_close() ?><br /><br />
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