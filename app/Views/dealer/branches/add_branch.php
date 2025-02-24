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
                            <h4>Add Showroom or Branch</h4>
                        </div>
                        <nav aria-label="breadcrumb" role="navigation">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="<?php echo base_url('dealer/dashboard'); ?>">Home</a></li>
                                <li class="breadcrumb-item">Manage Showroom</li>
                                <li class="breadcrumb-item active" aria-current="page">Add Showroom</li>
                            </ol>
                        </nav>
                    </div>
                    <div class="col-md-6 col-sm-12 text-right">
                        <a href="<?php echo base_url('dealer/list-branches'); ?>" class="btn btn-outline-primary btn-md" rel="content-y" role="button">
                            <i class="icon-copy bi bi-list-stars"></i> List Showrooms
                        </a>
                    </div>
                </div>
            </div>
            <div class="pd-20 bg-white border-radius-4 box-shadow mb-30">
                <?php if (isset($limitExceeded) && !empty($limitExceeded)) { ?>
                    <div class="row">
                        <div class="col-md-12 col-sm-12 mb-30">
                            <div class="pd-20 card-box">
                                <h4 class="mb-30 h4">
                                    You have reached your showroom limit (<?php echo $maxBranchesAllowed; ?>). <a class="text-blue" href="<?php echo '#';//echo base_url('dealer/profile#plan-details'); ?>">Plans coming soon</a> to add more branch.
                                </h4>
                            </div>
                        </div>
                    </div>
                <?php } else { ?>
                    <?= form_open('dealer/save-branch', ['id' => 'save_branch_form', 'method' => 'POST', 'enctype' => 'multipart/form-data']); ?>
                    <?= csrf_field(); ?>
                    <div class="clearfix">
                        <h4 class="text-blue h4">Showroom Summary</h4>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="branchName">Showroom Name</label>
                                <input type="text" class="form-control w-50" id="branchName" name="branchName" placeholder="Enter Showroom Name">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="branchType">Showroom Type</label>
                                <select class="form-control col-12 custom-select branchType" name="branchType">
                                    <option value="0" selected>Select Branch Type</option>
                                    <?php foreach (BRANCH_TYPE as $id => $type) : ?>
                                        <?php if ($id != 0) : ?>
                                            <option value="<?= $id ?>" ?><?= $type ?></option>
                                        <?php endif; ?>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="branchType">Supported Vehicle Type</label>
                                <select class="form-control custom-select vehicle-type col-12" name="branchSupportedVehicleType">
                                    <option>Select Vehicle Type</option>
                                    <?php foreach (VEHICLE_TYPE as $id => $type) : ?>
                                        <option value="<?= $id ?>" <?php echo ($id == 3) ? 'selected' : ''; ?>><?= $type ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="branchName">Showroom Thumbnail</label>
                                <input type="file" class="form-control" id="branchThumbnail" name="branchThumbnail" accept="image/png, image/jpeg, image/jpg" required>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="branchName">Showroom Logo</label>
                                <input type="file" class="form-control" id="branchLogo" name="branchLogo" accept="image/png, image/jpeg, image/jpg" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <!-- add branch service Start -->
                            <div class="form-group">
                                <label for="address">Showroom Services</label>

                                <div id="extend-service-field">
                                    <div class="form-group">
                                        <div class="input-group bootstrap-touchspin bootstrap-touchspin-injected"><input type="text" placeholder="Mention sevices your showroom or branch offers" name="branchServices[]" class="form-control branchServices"><span class="input-group-btn input-group-append"><button class="btn btn-primary bootstrap-touchspin-up remove-branch-service-field" type="button">-</button></span></div>
                                    </div>
                                </div>

                                <div class="text-right">
                                    <a href="#input-validation-form" id="extend-branch-service" class="btn btn-primary btn-sm scroll-click collapsed" rel="content-y" data-toggle="collapse" role="button" aria-expanded="false"><i class="fa fa-plus"></i> Add More Services </a>
                                </div>
                            </div>
                            <!-- add branch service End -->
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="shortDescription">Showroom Short Description</label>
                                <textarea class="form-control" placeholder="Mention short description about your showroom" id="shortDescription" name="shortDescription"></textarea>
                            </div>
                        </div>
                    </div>
                    <hr>

                    <div class="clearfix">
                        <h4 class="text-blue h4">Showroom Banners</h4>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="branchBanner">Showroom Banner 1</label>
                                <input type="file" class="form-control" id="branchBanner1" name="branchBanner1" accept="image/png, image/jpeg, image/jpg">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="branchBanner">Showroom Banner 2</label>
                                <input type="file" class="form-control" id="branchBanner2" name="branchBanner2" accept="image/png, image/jpeg, image/jpg">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="branchBanner">Showroom Banner 3</label>
                                <input type="file" class="form-control" id="branchBanner3" name="branchBanner3" accept="image/png, image/jpeg, image/jpg">
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
                                <input type="text" class="form-control numbersOnlyCheck" minlength="9" maxlength="10" id="contactNumber" name="contactNumber">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="whatsapp_no">Whatsapp Number</label>
                                <input type="text" class="form-control numbersOnlyCheck" minlength="9" maxlength="10" id="whatsapp_no" name="whatsapp_no">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="email">Email Address</label>
                                <input type="email" class="form-control" id="email" name="email">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="chooseCountry">Choose Country</label>
                                <select class="col-12 custom-select country" name="chooseCountry">
                                    <option value="0">Select Country</option>
                                    <?php foreach ($countryList as $id => $country) : ?>
                                        <option value="<?= $country['id'] ?>" <?= ($country['id'] == 101) ? 'selected' : '' ?>>
                                            <?= $country['name'] ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="chooseState">Choose State</label>
                                <select class="col-12 custom-select state" aria-placeholder="Select State" name="chooseState">
                                    <option value="0">State</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="chooseCity">Choose City</label>
                                <select class="col-12 custom-select city" aria-placeholder="Select City" name="chooseCity">
                                    <option value="0">City</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12 col-sm-12">
                            <div class="form-group">
                                <label for="address">Showroom Address</label>
                                <textarea class="form-control" id="address" name="address"></textarea>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <!-- add iframe code of map Start -->
                        <div class="col-md-12 col-sm-12 mb-30">
                            <div class="form-group d-none">
                                <label for="branch_map">Branch Google Maps</label>
                                <label class="text-right"><a href="https://support.google.com/maps/answer/7101463?hl=en" target="_blank">Help</a></label>
                                <textarea class="form-control" id="branch_map" name="branch_map" placeholder="<iframe src='https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3763.1279385462935!2d72.86234667498618!3d19'></iframe>"></textarea>
                            </div>

                            <div class="form-group">
                                <label for="branch_map">Showroom Location</label>
                                <input class="form-control w-50 mb-3" id="location-input" type="text" placeholder="Enter location">
                            </div>

                            <div id="map" style="width: 100%; height: 400px;"></div>
                            <input type="hidden" name="map_latitude" id="map_latitude">
                            <input type="hidden" name="map_longitude" id="map_longitude">
                            <input type="hidden" name="map_city" id="map_city">
                            <input type="hidden" name="map_district" id="map_district">
                            <input type="hidden" name="map_state" id="map_state">
                        </div>
                        <!-- add iframe code of map  End -->
                    </div>
                    <hr>

                    <div class="clearfix">
                        <h4 class="text-blue h4">Showroom Delivery Images</h4>
                    </div>
                    <div class="row">
                        <!-- add deliverable images Start -->
                        <div class="col-md-6 col-sm-12 mb-30">
                            <div class="form-group">
                                <label for="address">Deliverable Images</label>
                                <div id="extend-deliverable-img-field">
                                    <div class="form-group">
                                        <div class="input-group bootstrap-touchspin bootstrap-touchspin-injected">
                                            <input type="file" placeholder="Choose Image" name="deliverableImg[]" class="form-control deliverableImg" accept="image/png, image/jpeg, image/jpg">
                                            <span class="input-group-btn input-group-append">
                                                <button class="btn btn-primary bootstrap-touchspin-up remove-deliverable-img-field" type="button">-</button>
                                            </span>
                                        </div>
                                    </div>
                                </div>

                                <div class="text-right">
                                    <a href="#input-validation-form" id="extend-deliverable-img" class="btn btn-primary btn-sm scroll-click collapsed" rel="content-y" data-toggle="collapse" role="button" aria-expanded="false"><i class="fa fa-plus"></i> Add More Delivery Images</a>
                                </div>
                            </div>
                        </div>
                        <!-- add deliverable images End -->
                    </div>
                    <button type="submit" class="btn btn-primary pull-right">Submit</button><br /><br />
                    <?= form_close(); ?>
                <?php } ?>
            </div>
        </div>
        <?php echo view('dealer/includes/_footer'); ?>
    </div>
</div>
<script>
    $(document).ready(function() {
        /* on page load trigger to load brands of cars & bikes both in select option filter */
        $('.custom-select.country').trigger('change');
    });

    /* init map in addbranch page */
    var lat = 19.2742053;
    var lng = 72.8788707;

    // Call the map initialization function with the coordinates
    window.initMap = function() {
        initMapWithCoordinates(lat, lng);
    };
</script>