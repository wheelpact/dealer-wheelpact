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
                            <h4>Add Vehicle</h4>
                        </div>
                        <nav aria-label="breadcrumb" role="navigation">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="<?php echo base_url('dealer/dashboard'); ?>">Home</a></li>
                                <li class="breadcrumb-item active" aria-current="page">Vehicles</li>
                                <li class="breadcrumb-item active" aria-current="page">Add Vehicle</li>
                            </ol>
                        </nav>
                    </div>
                    <div class="col-md-6 col-sm-12 text-right">
                        <div class="">
                            <a class="btn btn-primary" href="<?php echo base_url('dealer/list-vehicles'); ?>" role="button">
                                List Vehicles
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <?php if (isset($limitExceeded) && !empty($limitExceeded)) { ?>
                <div class="pd-ltr-20 xs-pd-20-10">
                    <div class="min-height-200px">
                        <div class="row">
                            <div class="col-md-12 col-sm-12 mb-30">
                                <div class="pd-20 card-box">
                                    <h4 class="mb-30 h4">
                                        You have reached your vehicle limit (<?php echo $totalVehicleCountForCurrentMonth; ?>) For current Month. <a href="<?php echo base_url('dealer/profile#plan-details'); ?>">Upgrade your plan</a> to add more vehicles.
                                    </h4>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <?php } else { ?>
                <!-- start steps -->
                <div class="pd-20 card-box mb-30 stepsDivForm">
                    <div class="clearfix">
                        <h4 class="text-blue h4">Vehicle Details</h4>
                    </div>
                    <div class="wizard-content" id="vehicleBasicInformationMultipartFormWrapper">
                        <ul class="step-indicators">
                            <li><span class="indicator active" data-step="1">1</span></li>
                            <li><span class="indicator" data-step="2">2</span></li>
                            <li><span class="indicator" data-step="3">3</span></li>
                            <li><span class="indicator" data-step="4">4</span></li>
                            <li><span class="indicator" data-step="5">5</span></li>
                            <li><span class="indicator" data-step="6">6</span></li>
                        </ul>

                        <?= form_open('dealer/save-new-vehicle', 'id="save_new_vehicle" class="custom-tab-wizard-add wizard-circle wizard"') ?>
                        <?= csrf_field(); ?>
                        <!-- Step 1 -->
                        <section id="step1" class="step">
                            <h5>Vehicle Info</h5>
                            <div class="row">
                                <div class="col-md-6 col-lg-4">
                                    <div class="form-group">
                                        <label>Choose Showroom<span class="required">*</span></label>
                                        <select class="custom-select formInput" name="branch_id" id="branch_id" placeholder="Please choose dealer branch.">
                                            <option value="">Choose...</option>
                                            <?php if (isset($showroomList) && !empty($showroomList)) {
                                                foreach ($showroomList as $value) { ?>
                                                    <option value="<?php echo isset($value['id']) ? $value['id'] : ''; ?>"><?php echo isset($value['name']) ? $value['name'] : ''; ?></option>
                                            <?php }
                                            } ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6 col-lg-4">
                                    <div class="form-group">
                                        <label>Vehicle Type<span class="required">*</span></label>
                                        <select class="custom-select vehicle-type formInput" data-tabid="step1" name="vehicle_type" placeholder="Please choose Vehicle Type.">
                                            <option value="0">Choose...</option>
                                            <?php
                                            $showOption1 = ($planData['allowedVehicleListing'] == 1 || $planData['allowedVehicleListing'] != 2);
                                            $showOption2 = ($planData['allowedVehicleListing'] == 2 || $planData['allowedVehicleListing'] != 1);

                                            foreach (VEHICLE_TYPE as $id => $type) : ?>
                                                <?php if (($id == 1 && $showOption1) || ($id == 2 && $showOption2)) : ?>
                                                    <option value="<?= $id ?>"><?= $type ?></option>
                                                <?php endif; ?>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6 col-lg-4">
                                    <div class="form-group">
                                        <label>Make<span class="required">*</span></label>
                                        <select class="custom-select brand formInput" name="cmp_id">
                                            <option value="">Choose...</option>
                                            <?php if (isset($cmpList) && !empty($cmpList)) {
                                                foreach ($cmpList as $value) { ?>
                                                    <option value="<?php echo isset($value['id']) ? $value['id'] : ''; ?>">
                                                        <?php echo isset($value['cmp_name']) ? $value['cmp_name'] : ''; ?>
                                                    </option>
                                            <?php }
                                            } ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6 col-lg-4">
                                    <div class="form-group">
                                        <label>Model<span class="required">*</span></label>
                                        <select class="custom-select model formInput" name="model_id" id="vehicleCompanyModel">
                                            <option value="">Choose...</option>
                                            <?php if (isset($cmpModelList) && !empty($cmpModelList)) {
                                                foreach ($cmpModelList as $value) { ?>
                                                    <option value="<?php echo isset($value['id']) ? $value['id'] : ''; ?>">
                                                        <?php echo isset($value['model_name']) ? $value['model_name'] : ''; ?>
                                                    </option>
                                            <?php }
                                            } ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6 col-lg-4">
                                    <div class="form-group">
                                        <label>Variant<span class="required">*</span></label>
                                        <select class="custom-select variant formInput" name="variant_id" id="variant_id">
                                            <option value="">Choose...</option>
                                            <?php if (isset($variantList) && !empty($variantList)) {
                                                foreach ($variantList as $va) { ?>
                                                    <option value="<?php echo isset($va['id']) ? $va['id'] : ''; ?>">
                                                        <?php echo isset($va['name']) ? $va['name'] : ''; ?>
                                                    </option>
                                            <?php }
                                            } ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6 col-lg-4">
                                    <div class="form-group">
                                        <label>Fuel Type<span class="required">*</span></label>
                                        <select class="custom-select formInput" name="fuel_type" id="fuel_type">
                                            <option value="">Choose...</option>
                                            <?php if (isset($fuelTypeList) && !empty($fuelTypeList)) {
                                                foreach ($fuelTypeList as $value) { ?>
                                                    <option value="<?php echo isset($value['id']) ? $value['id'] : ''; ?>">
                                                        <?php echo isset($value['name']) ? $value['name'] : ''; ?>
                                                    </option>
                                            <?php }
                                            } ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6 col-lg-4">
                                    <div class="form-group">
                                        <label>Body Type<span class="required">*</span></label>
                                        <select class="custom-select body_type formInput" name="body_type" id="body_type">
                                            <option value="">Choose...</option>
                                            <?php if (isset($bodyTypeList) && !empty($bodyTypeList)) {
                                                foreach ($bodyTypeList as $value) { ?>
                                                    <option value="<?php echo isset($value['id']) ? $value['id'] : ''; ?>" data-vehicle-type="<?php echo isset($value['vehicle_type']) ? $value['vehicle_type'] : ''; ?>">
                                                        <?php echo isset($value['title']) ? $value['title'] : ''; ?>
                                                    </option>
                                            <?php }
                                            } ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6 col-lg-4">
                                    <div class="form-group">
                                        <label>Mileage<span class="required">*</span></label>
                                        <input type="text" maxlength="2" placeholder="0" name="mileage" id="mileage" value="" class="form-control formInput numbersOnlyCheck">
                                    </div>
                                </div>
                                <div class="col-md-6 col-lg-4">
                                    <div class="form-group">
                                        <label>Kilometers Driven<span class="required">*</span></label>
                                        <input type="text" maxlength="12" placeholder="0" name="kms_driven" id="kms_driven" value="" class="form-control formInput numbersOnlyCheck">
                                    </div>
                                </div>
                                <div class="col-md-6 col-lg-4">
                                    <div class="form-group">
                                        <label>Owner<span class="required">*</span></label>
                                        <select class="custom-select formInput" name="owner" id="owner">
                                            <option value="">Choose...</option>
                                            <?php foreach (OWNER_TYPE as $id => $type) : ?>
                                                <option value="<?= $id ?>"><?= $type ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6 col-lg-4">
                                    <div class="form-group">
                                        <label>Gear Transmission<span class="required">*</span></label>
                                        <select class="custom-select formInput" name="transmission_id" id="transmission_id">
                                            <option value="">Choose...</option>
                                            <?php if (isset($transmissionList) && !empty($transmissionList)) {
                                                foreach ($transmissionList as $value) { ?>
                                                    <option value="<?php echo isset($value['id']) ? $value['id'] : ''; ?>">
                                                        <?php echo isset($value['title']) ? $value['title'] : ''; ?>
                                                    </option>
                                            <?php }
                                            } ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6 col-lg-4">
                                    <div class="form-group">
                                        <label>Colour<span class="required">*</span></label>
                                        <select class="custom-select formInput" name="color_id" id="color_id">
                                            <option value="">Choose...</option>
                                            <?php if (isset($colorList) && !empty($colorList)) {
                                                foreach ($colorList as $value) { ?>
                                                    <option value="<?php echo isset($value['id']) ? $value['id'] : ''; ?>">
                                                        <?php echo isset($value['name']) ? $value['name'] : ''; ?>
                                                    </option>
                                            <?php }
                                            } ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6 col-lg-4">
                                    <div class="form-group">
                                        <label>Featured Status<span class="required">*</span></label>
                                        <select class="custom-select formInput" name="featured_status" id="featured_status" data-toggle="tooltip" title="Vehicle will be displayed on Showroom pages Featured Section">
                                            <option value="">Choose...</option>
                                            <?php foreach (YES_NO_OPTIONS as $id => $type) : ?>
                                                <option value="<?= $id ?>"><?= $type ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-12 col-lg-12">
                                    <div class="form-group">
                                        <label>Search Keywords</label>
                                        <textarea name="search_keywords" id="search_keywords" rows="1" class="form-control" placeholder="Add Search Keywords & Tags Separated by  ',' "><?php echo isset($vehicleDetails['search_keywords']) ? $vehicleDetails['search_keywords'] : ''; ?></textarea>
                                    </div>
                                </div>
                            </div>
                            <!-- Add more fields for Step 1 here -->
                            <button type="button" class="btn btn-primary next pull-right">Next</button>
                        </section>

                        <!-- Step 2 -->
                        <section id="step2" class="step">
                            <h5>Registration Details</h5>
                            <div class="row">
                                <div class="col-md-6 col-lg-3">
                                    <div class="form-group">
                                        <label>Make Year<span class="required">*</span></label>
                                        <select class="custom-select formInput" name="manufacture_year" id="manufacture_year">
                                            <option value="">Choose...</option>
                                            <?php for ($i = date("Y"); $i >= 1975; $i--) { ?>
                                                <option value="<?php echo $i; ?>">
                                                    <?php echo $i; ?></option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6 col-lg-3">
                                    <div class="form-group">
                                        <label>Registration Year<span class="required">*</span></label>
                                        <select class="custom-select formInput" name="registration_year" id="registration_year">
                                            <option value="">Choose...</option>
                                            <?php for ($i = date("Y"); $i >= 1975; $i--) { ?>
                                                <option value="<?php echo $i; ?>">
                                                    <?php echo $i; ?></option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6 col-lg-3">
                                    <div class="form-group">
                                        <label>Registered State<span class="required">*</span></label>
                                        <select class="custom-select state-rto formInput" name="registered_state_id" id="registeredStateRto">
                                            <option value="">Choose...</option>
                                            <?php if (isset($stateList) && !empty($stateList)) {
                                                foreach ($stateList as $value) { ?>
                                                    <option value="<?php echo isset($value['id']) ? $value['id'] : ''; ?>">
                                                        <?php echo isset($value['name']) ? $value['name'] : ''; ?>
                                                    </option>
                                            <?php }
                                            } ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6 col-lg-3">
                                    <div class="form-group">
                                        <label>RTO<span class="required">*</span></label>
                                        <select class="custom-select rto-code formInput" name="rto" id="registeredRto">
                                            <option value="">Choose...</option>
                                            <?php if (isset($vehicleRegRtoList) && !empty($vehicleRegRtoList)) {
                                                foreach ($vehicleRegRtoList as $value) { ?>
                                                    <option value="<?php echo isset($value['id']) ? $value['id'] : ''; ?>">
                                                        <?php echo isset($value['rto_state_code']) ? $value['rto_state_code'] : ''; ?>
                                                    </option>
                                            <?php }
                                            } ?>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <!-- Add more fields for Step 2 here -->
                            <div class="pull-right">
                                <button type="button" class="btn btn-primary prev">Previous</button>
                                <button type="button" class="btn btn-primary next">Next</button>
                            </div>
                        </section>

                        <!-- Step 3 -->
                        <section id="step3" class="step">
                            <h5>Insurance Details</h5>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>Insurance Type<span class="required">*</span></label>
                                        <select class="custom-select formInput" name="insurance_type" id="insurance_type">
                                            <option value="">Choose...</option>
                                            <?php foreach (INSURANCE_TYPE as $id => $type) : ?>
                                                <option value="<?= $id ?>"><?= $type ?></option>
                                            <?php endforeach; ?>
                                        </select>

                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>Insurance Validity<span class="required">*</span></label>
                                        <input type="text" name="insurance_validity" id="insurance_validity" value="" class="form-control formInput date-picker" placeholder="Select Date" autocomplete="off">
                                    </div>
                                </div>
                            </div>
                            <!-- Add more fields for Step 3 here -->
                            <div class="pull-right">
                                <button type="button" class="btn btn-primary prev">Previous</button>
                                <button type="button" class="btn btn-primary next">Next</button>
                            </div>
                        </section>

                        <!-- Step 4 -->
                        <section id="step4" class="step">
                            <h5>Overview</h5>
                            <div class="row">
                                <div class="col-md-6 col-lg-3">
                                    <div class="form-group">
                                        <label>Accidental<span class="required">*</span></label>
                                        <select class="custom-select formInput" name="accidental_status" id="accidental_status">
                                            <option value="">Choose...</option>
                                            <?php foreach (YES_NO_OPTIONS as $id => $type) : ?>
                                                <option value="<?= $id ?>"><?= $type ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6 col-lg-3">
                                    <div class="form-group">
                                        <label>Flooded<span class="required">*</span></label>
                                        <select class="custom-select formInput" name="flooded_status" id="flooded_status">
                                            <option value="">Choose...</option>
                                            <?php foreach (YES_NO_OPTIONS as $id => $type) : ?>
                                                <option value="<?= $id ?>"><?= $type ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6 col-lg-3">
                                    <div class="form-group">
                                        <label>Last Service Kilometer<span class="required">*</span></label>
                                        <input type="text" maxlength="9" placeholder="0" value="" class="form-control formInput numbersOnlyCheck" name="last_service_kms" id="last_service_kms">
                                    </div>
                                </div>
                                <div class="col-md-6 col-lg-3">
                                    <div class="form-group">
                                        <label>Last Service Date<span class="required">*</span></label>
                                        <input type="text" value="" class="form-control formInput date-picker" name="last_service_date" id="last_service_date" placeholder="Select Date" autocomplete="off">
                                    </div>
                                </div>
                            </div>
                            <!-- Add more fields for Step 4 here -->
                            <div class="pull-right">
                                <button type="button" class="btn btn-primary prev">Previous</button>
                                <button type="button" class="btn btn-primary next">Next</button>
                            </div>
                        </section>

                        <!-- Step 5 -->
                        <section id="step5" class="step">
                            <h5>Features</h5>
                            <div class="row pd-20" id="vehicleFeaturesWrapper"></div>
                            <!-- Add more fields for Step 5 here -->
                            <div class="pull-right">
                                <button type="button" class="btn btn-primary prev">Previous</button>
                                <button type="button" class="btn btn-primary next">Next</button>
                            </div>
                        </section>

                        <!-- Step 6 -->
                        <section id="step6" class="step">
                            <h5>Pricing</h5>
                            <div class="row">
                                <div class="col-md-6 col-lg-3">
                                    <div class="form-group">
                                        <label>On Sale Status<span class="required">*</span></label>
                                        <select class="custom-select onsale_status formInput" name="onsale_status" id="onsale_status" data-toggle="tooltip" title="Vehicle will be displayed on Showroom pages On-Sale Section">
                                            <option value="">Choose...</option>
                                            <?php foreach (YES_NO_OPTIONS as $id => $type) : ?>
                                                <option value="<?= $id ?>"><?= $type ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="onsale_percentage_div"></div>
                                <div class="col-md-6 col-lg-3">
                                    <div class="form-group">
                                        <label>Regular Price<span class="required">*</span></label>
                                        <input type="text" maxlength="12" value="" class="form-control formInput numbersOnlyCheck" name="regular_price" id="regular_price">
                                    </div>
                                </div>
                                <div class="col-md-6 col-lg-3">
                                    <div id="saleInput">
                                        <div class="form-group">
                                            <label>Selling Price<span class="required">*</span></label>
                                            <input type="text" maxlength="12" value="" class="form-control formInput numbersOnlyCheck" name="selling_price" id="selling_price" readonly>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6 col-lg-3">
                                    <div class="form-group">
                                        <label>Pricing Type<span class="required">*</span></label>
                                        <select class="custom-select formInput" name="pricing_type" id="pricing_type">
                                            <option value="">Choose...</option>
                                            <option value="1">Fixed Price</option>
                                            <option value="2">Negotiable</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6 col-lg-3">
                                    <div class="form-group">
                                        <label>EMI Option<span class="required">*</span></label>
                                        <select class="custom-select formInput" name="emi_option" id="emi_option">
                                            <option value="">Choose...</option>
                                            <?php foreach (YES_NO_OPTIONS as $id => $type) : ?>
                                                <option value="<?= $id ?>"><?= $type ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6 col-lg-3">
                                    <div class="form-group">
                                        <label>Average Interest Rate<span class="required">*</span></label>
                                        <input type="text" placeholder="%" step="0.01" min="0" max="100" maxlength="4" value="<?php echo isset($vehicleDetails['avg_interest_rate']) ? $vehicleDetails['avg_interest_rate'] : ''; ?>" class="form-control formInput numbersOnlyCheck" name="avg_interest_rate" id="avg_interest_rate">
                                    </div>
                                </div>
                                <div class="col-md-6 col-lg-3">
                                    <div class="form-group">
                                        <label>Tenure in Months<span class="required">*</span></label>
                                        <input type="text" placeholder="0" min="0" maxlength="2" value="<?php echo isset($vehicleDetails['tenure_months']) ? $vehicleDetails['tenure_months'] : ''; ?>" class="form-control formInput numbersOnlyCheck" name="tenure_months" id="tenure_months">
                                    </div>
                                </div>
                            </div>
                            <!-- Add more fields for Step 6 here -->
                            <div class="pull-right">
                                <button type="button" class="btn btn-primary prev">Previous</button>
                                <button type="submit" class="btn btn-success">Submit</button>
                            </div>
                        </section>
                        <?= form_close() ?>
                    </div>
                </div>
                <!-- end steps -->

                <!-- vehicle image start -->
                <div class="pd-20 bg-white border-radius-4 box-shadow mb-30">
                    <h5 class="text-blue mb-3">Vehicle Images</h5>
                    <div class="row thumbnailImgDIv">
                        <div class="col-6">
                            <div class="form-group">
                                <label>Thumbnail Image<span class="required">*</span></label>
                                <div class="input-group mb-3">
                                    <input type="hidden" name="vehicleId" id="vehicleId" class="vehicleId" value="">
                                    <input type="hidden" name="vehicle_type" id="vehicle_type" class="vehicle_type" value="">
                                    <input type="file" class="form-control formInput onlyImageInput" id="thumbnailImage" accept="image/*">
                                    <div class="input-group-append">
                                        <button class="btn btn-primary" id="uploadThumbnail">Upload</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="pd-20">
                        <div class="progress vehicleUploadProgressBar d-none">
                            <div class="progress-bar bg-info progress-bar-striped progress-bar-animated vehicleUploadProgressBarData" role="progressbar" style="width: 50%" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100">
                                50%
                            </div>
                        </div>
                    </div>
                    <div class="tab">
                        <ul class="nav nav-tabs" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active" data-toggle="tab" href="#exterior" role="tab" aria-selected="true">Exterior</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" data-toggle="tab" href="#interior" role="tab" aria-selected="false">Interior</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" data-toggle="tab" href="#others" role="tab" aria-selected="false">Others</a>
                            </li>
                        </ul>
                        <div class="tab-content">
                            <div class="tab-pane fade show active" id="exterior" role="tabpanel">
                                <div class="pd-20">
                                    <div class="accordion mb-20" id="vehicleExteriorImagesWrapper">Kinldy Add Vehicle Information First</div>
                                </div>
                            </div>
                            <!-- these tabs only for cars /+/ -->
                            <div class="tab-pane fade" id="interior" role="tabpanel">
                                <?= form_open_multipart('dealer/upload-vehicle-images', 'id="interior_form"') ?>
                                <?= csrf_field(); ?>
                                <div class="pd-20">
                                    <div class="row">
                                        <div class="col-md-6 col-lg-3">
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="form-group">
                                                        <label>Dashboard Image<span class="required">*</span></label>
                                                        <input type="file" name="interior_dashboard_img" id="interior_dashboard_img" class="form-control-file formInput form-control height-auto onlyImageInput" required>
                                                    </div>
                                                </div>

                                            </div>
                                        </div>
                                        <div class="col-md-6 col-lg-3">
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="form-group">
                                                        <label>Infotainment System Image<span class="required">*</span></label>
                                                        <input type="file" name="interior_infotainment_system_img" id="interior_infotainment_system_img" class="form-control-file formInput form-control height-auto onlyImageInput" required>
                                                    </div>
                                                </div>

                                            </div>
                                        </div>
                                        <div class="col-md-6 col-lg-3">
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="form-group">
                                                        <label>Steering Wheel Image<span class="required">*</span></label>
                                                        <input type="file" name="interior_steering_wheel_img" id="interior_steering_wheel_img" class="form-control-file formInput form-control height-auto onlyImageInput" required>
                                                    </div>
                                                </div>

                                            </div>
                                        </div>
                                        <div class="col-md-6 col-lg-3">
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="form-group">
                                                        <label>Odometer Image<span class="required">*</span></label>
                                                        <input type="file" name="interior_odometer_img" id="interior_odometer_img" class="form-control-file formInput form-control height-auto onlyImageInput" required>
                                                    </div>
                                                </div>

                                            </div>
                                        </div>
                                        <div class="col-md-6 col-lg-3">
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="form-group">
                                                        <label>Gear Lever Image<span class="required">*</span></label>
                                                        <input type="file" name="interior_gear_lever_img" id="interior_gear_lever_img" class="form-control-file formInput form-control height-auto onlyImageInput" required>
                                                    </div>
                                                </div>

                                            </div>
                                        </div>
                                        <div class="col-md-6 col-lg-3">
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="form-group">
                                                        <label>Pedals Image<span class="required">*</span></label>
                                                        <input type="file" name="interior_pedals_img" id="interior_pedals_img" class="form-control-file formInput form-control height-auto onlyImageInput" required>
                                                    </div>
                                                </div>

                                            </div>
                                        </div>
                                        <div class="col-md-6 col-lg-3">
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="form-group">
                                                        <label>Front Cabin Image<span class="required">*</span></label>
                                                        <input type="file" name="interior_front_cabin_img" id="interior_front_cabin_img" class="form-control-file formInput form-control height-auto onlyImageInput" required>
                                                    </div>
                                                </div>

                                            </div>
                                        </div>
                                        <div class="col-md-6 col-lg-3">
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="form-group">
                                                        <label>Mid Cabin Image <small>(Optional)</small></label>
                                                        <input type="file" name="interior_mid_cabin_img" id="interior_mid_cabin_img" class="form-control-file formInput form-control height-auto onlyImageInput" required>
                                                    </div>
                                                </div>

                                            </div>
                                        </div>
                                        <div class="col-md-6 col-lg-3">
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="form-group">
                                                        <label>Rear Cabin Image<span class="required">*</span></label>
                                                        <input type="file" name="interior_rear_cabin_img" id="interior_rear_cabin_img" class="form-control-file formInput form-control height-auto onlyImageInput" required>
                                                    </div>
                                                </div>

                                            </div>
                                        </div>
                                        <div class="col-md-6 col-lg-3">
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="form-group">
                                                        <label>Driver Side Door Panel Image<span class="required">*</span></label>
                                                        <input type="file" name="interior_driver_side_door_panel_img" id="interior_driver_side_door_panel_img" class="form-control-file formInput form-control height-auto onlyImageInput" required>
                                                    </div>
                                                </div>

                                            </div>
                                        </div>
                                        <div class="col-md-6 col-lg-3">
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="form-group">
                                                        <label>Driver Side Adjustment Image<span class="required">*</span></label>
                                                        <input type="file" name="interior_driver_side_adjustment_img" id="interior_driver_side_adjustment_img" class="form-control-file formInput form-control height-auto onlyImageInput" required>
                                                    </div>
                                                </div>

                                            </div>
                                        </div>
                                        <div class="col-md-6 col-lg-3">
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="form-group">
                                                        <label>Boot Inside Image<span class="required">*</span></label>
                                                        <input type="file" name="interior_boot_inside_img" id="interior_boot_inside_img" class="form-control-file formInput form-control height-auto onlyImageInput" required>
                                                    </div>
                                                </div>

                                            </div>
                                        </div>
                                        <div class="col-md-6 col-lg-3">
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="form-group">
                                                        <label>Boot Door Open Image<span class="required">*</span></label>
                                                        <input type="file" name="interior_boot_door_open_img" id="interior_boot_door_open_img" class="form-control-file formInput form-control height-auto onlyImageInput" required>
                                                    </div>
                                                </div>

                                            </div>
                                        </div>
                                    </div>
                                    <div class="pd-20">
                                        <div class="pull-right">
                                            <button type="submit" class="btn btn-primary upload_vehicle_images_form">Submit</button>
                                        </div>
                                    </div>
                                    <?= form_close() ?>
                                </div>
                            </div>
                            <div class="tab-pane fade" id="others" role="tabpanel">
                                <?= form_open_multipart('dealer/upload-vehicle-images', 'id="others_form"') ?>
                                <?= csrf_field(); ?>
                                <div class="pd-20">
                                    <div class="row">
                                        <div class="col-md-6 col-lg-3">
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="form-group">
                                                        <label>Keys<span class="required">*</span></label>
                                                        <input type="file" name="others_keys_img" id="others_keys_img" class="form-control-file formInput form-control height-auto onlyImageInput" required>
                                                    </div>
                                                </div>

                                            </div>
                                        </div>
                                    </div>
                                    <div class="pd-20">
                                        <div class="pull-right">
                                            <button type="submit" class="btn btn-primary upload_vehicle_images_form">Submit</button>
                                        </div>
                                    </div>
                                    <?= form_close() ?>
                                </div>
                            </div>
                            <!-- these tabs only for cars /-/ -->
                        </div>
                    </div>
                </div>
                <!-- vehicle image end -->
            <?php } ?>

            <?php echo view('dealer/includes/_footer'); ?>
        </div>
    </div>