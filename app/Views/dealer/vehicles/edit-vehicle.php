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
                            <h4>Edit Vehicle</h4>
                        </div>
                        <nav aria-label="breadcrumb" role="navigation">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="<?php echo base_url('dealer/dashboard'); ?>">Home</a></li>
                                <li class="breadcrumb-item active" aria-current="page">Vehicles</li>
                                <li class="breadcrumb-item active" aria-current="page">Edit Vehicle</li>
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
                    <?= form_open('dealer/update-vehicle', 'id="update_vehicle_form" class="custom-tab-wizard wizard-circle wizard" ') ?>
                    <?= csrf_field(); ?>
                    <input type="hidden" name="vehicleId" id="vehicleId" value="<?php echo isset($vehicleDetails['id']) ? $vehicleDetails['id'] : ''; ?>">
                    <!-- Step 1 -->
                    <section id="step1" class="step">
                        <h5>Vehicle Info</h5>
                        <div class="row">
                            <div class="col-md-6 col-lg-4">
                                <div class="form-group">
                                    <label>Choose Showroom<span class="required">*</span></label>
                                    <select class="custom-select formInput" name="branch_id" id="branch_id" placeholder="Please choose dealer branch." disabled readonly>
                                        <option value="">Choose...</option>
                                        <?php
                                        if (isset($showroomList) && !empty($showroomList)) {
                                            foreach ($showroomList as $value) {
                                        ?>
                                                <option value="<?php echo isset($value['id']) ? $value['id'] : ''; ?>" <?php if ($vehicleDetails['branch_id'] == $value['id']) {
                                                                                                                            echo 'selected';
                                                                                                                        } ?>><?php echo isset($value['name']) ? $value['name'] : ''; ?></option>
                                        <?php }
                                        } ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6 col-lg-4">
                                <div class="form-group">
                                    <label>Vehicle Type<span class="required">*</span></label>
                                    <select class="custom-select vehicle-type formInput cmpCat" data-tabid="step1" name="vehicle_type" id="vehicle_type" placeholder="Please choose Vehicle Type." disabled readonly>
                                        <option value="">Choose...</option>
                                        <?php foreach (VEHICLE_TYPE as $id => $type) : ?>
                                            <option value="<?= $id ?>" <?php echo ($id == $vehicleDetails['vehicle_type']) ? 'selected' : ''; ?>><?= $type ?></option>
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
                                                <option value="<?php echo isset($value['id']) ? $value['id'] : ''; ?>" <?php if ($vehicleDetails['cmp_id'] == $value['id']) {
                                                                                                                            echo 'selected';
                                                                                                                        } ?>>
                                                    <?php echo isset($value['cmp_name']) ? $value['cmp_name'] : ''; ?></option>
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
                                                <option value="<?php echo isset($value['id']) ? $value['id'] : ''; ?>" <?php if ($vehicleDetails['model_id'] == $value['id']) {
                                                                                                                            echo 'selected';
                                                                                                                        } ?>>
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
                                                <option value="<?php echo isset($va['id']) ? $va['id'] : ''; ?>" <?php if ($vehicleDetails['variant_id'] == $va['id']) {
                                                                                                                        echo 'selected';
                                                                                                                    } ?>>
                                                    <?php echo isset($va['name']) ? $va['name'] : ''; ?></option>
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
                                                <option value="<?php echo isset($value['id']) ? $value['id'] : ''; ?>" <?php if ($vehicleDetails['fuel_type'] == $value['id']) {
                                                                                                                            echo 'selected';
                                                                                                                        } ?>>
                                                    <?php echo isset($value['name']) ? $value['name'] : ''; ?></option>
                                        <?php }
                                        } ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6 col-lg-4">
                                <div class="form-group">
                                    <label>Body Type<span class="required">*</span></label>
                                    <select class="custom-select formInput" name="body_type" id="body_type">
                                        <option value="">Choose...</option>
                                        <?php if (isset($bodyTypeList) && !empty($bodyTypeList)) {
                                            foreach ($bodyTypeList as $value) { ?>
                                                <option value="<?php echo isset($value['id']) ? $value['id'] : ''; ?>" <?php if ($vehicleDetails['body_type'] == $value['id']) {
                                                                                                                            echo 'selected';
                                                                                                                        } ?>><?php echo isset($value['title']) ? $value['title'] : ''; ?></option>
                                        <?php }
                                        } ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6 col-lg-4">
                                <div class="form-group">
                                    <label>Mileage<span class="required">*</span></label>
                                    <input type="text" maxlength="2" placeholder="0" name="mileage" id="mileage" value="<?php echo isset($vehicleDetails['mileage']) ? $vehicleDetails['mileage'] : ''; ?>" class="form-control formInput numbersOnlyCheck">
                                </div>
                            </div>
                            <div class="col-md-6 col-lg-4">
                                <div class="form-group">
                                    <label>Kilometers Driven<span class="required">*</span></label>
                                    <input type="text" maxlength="12" placeholder="0" name="kms_driven" id="kms_driven" value="<?php echo isset($vehicleDetails['kms_driven']) ? $vehicleDetails['kms_driven'] : ''; ?>" class="form-control formInput numbersOnlyCheck">
                                </div>
                            </div>
                            <div class="col-md-6 col-lg-4">
                                <div class="form-group">
                                    <label>Owner<span class="required">*</span></label>
                                    <select class="custom-select formInput" name="owner" id="owner">
                                        <option value="">Choose...</option>
                                        <?php foreach (OWNER_TYPE as $id => $type) : ?>
                                            <option value="<?= $id ?>" <?php if ($vehicleDetails['owner'] == $id) {
                                                                            echo 'selected';
                                                                        } ?>><?= $type ?></option>
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
                                                <option value="<?php echo isset($value['id']) ? $value['id'] : ''; ?>" <?php if ($vehicleDetails['transmission_id'] == $value['id']) {
                                                                                                                            echo 'selected';
                                                                                                                        } ?>>
                                                    <?php echo isset($value['title']) ? $value['title'] : ''; ?></option>
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
                                                <option value="<?php echo isset($value['id']) ? $value['id'] : ''; ?>" <?php if ($vehicleDetails['color_id'] == $value['id']) {
                                                                                                                            echo 'selected';
                                                                                                                        } ?>>
                                                    <?php echo isset($value['name']) ? $value['name'] : ''; ?></option>
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
                                            <option value="<?= $id ?>" <?php if ($vehicleDetails['featured_status'] == $id) {
                                                                            echo 'selected';
                                                                        } ?>><?= $type ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-12 col-lg-12">
                                <div class="form-group">
                                    <label>Search Keywords</label>
                                    <textarea name="search_keywords" id="search_keywords" rows="1" class="form-control formInput" placeholder="Add Search Keywords & Tags Separated by  ',' "><?php echo isset($vehicleDetails['search_keywords']) ? $vehicleDetails['search_keywords'] : ''; ?></textarea>
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
                                        <?php for ($i = 1975; $i <= date("Y"); $i++) { ?>
                                            <option value="<?php echo $i; ?>" <?php if ($vehicleDetails['manufacture_year'] == $i) {
                                                                                    echo 'selected';
                                                                                } ?>>
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
                                        <?php for ($i = 1975; $i <= date("Y"); $i++) { ?>
                                            <option value="<?php echo $i; ?>" <?php if ($vehicleDetails['registration_year'] == $i) {
                                                                                    echo 'selected';
                                                                                } ?>>
                                                <?php echo $i; ?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6 col-lg-3">
                                <div class="form-group">
                                    <label>Registered State<span class="required">*</span></label>
                                    <select class="custom-select formInput" name="registered_state_id" id="registeredStateRto">
                                        <option value="">Choose...</option>
                                        <?php if (isset($stateList) && !empty($stateList)) {
                                            foreach ($stateList as $value) { ?>
                                                <option value="<?php echo isset($value['id']) ? $value['id'] : ''; ?>" <?php if ($vehicleDetails['registered_state_id'] == $value['id']) {
                                                                                                                            echo 'selected';
                                                                                                                        } ?>>
                                                    <?php echo isset($value['name']) ? $value['name'] : ''; ?></option>
                                        <?php }
                                        } ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6 col-lg-3">
                                <div class="form-group">
                                    <label>RTO<span class="required">*</span></label>
                                    <select class="custom-select formInput" name="rto" id="registeredRto">
                                        <option value="">Choose...</option>
                                        <?php if (isset($vehicleRegRtoList) && !empty($vehicleRegRtoList)) {
                                            foreach ($vehicleRegRtoList as $value) { ?>
                                                <option value="<?php echo isset($value['id']) ? $value['id'] : ''; ?>" <?php if ($vehicleDetails['rto'] == $value['id']) {
                                                                                                                            echo 'selected';
                                                                                                                        } ?>>
                                                    <?php echo isset($value['rto_state_code']) ? $value['rto_state_code'] : ''; ?>
                                                </option>
                                        <?php }
                                        } ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="pull-right">
                            <button type="button" class="btn btn-primary prev">Previous</button>
                            <button type="button" class="btn btn-primary next">Next</button>
                        </div>
                    </section>

                    <!-- Step 3 -->
                    <section id="step3" class="step">
                        <h5>Insurance Details</h5>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Insurance Type<span class="required">*</span></label>
                                    <select class="custom-select formInput" name="insurance_type" id="insurance_type">
                                        <option value="">Choose...</option>
                                        <?php foreach (INSURANCE_TYPE as $id => $type) : ?>
                                            <option value="<?= $id ?>" <?php if ($vehicleDetails['insurance_type'] == $id) {
                                                                            echo 'selected';
                                                                        } ?>><?= $type ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Insurance Validity<span class="required">*</span></label>
                                    <input type="text" name="insurance_validity" id="insurance_validity" value="<?php echo isset($vehicleDetails['insurance_validity']) ? date('d M Y', strtotime($vehicleDetails['insurance_validity'])) : ''; ?>" class="form-control formInput date-picker" placeholder="Select Date" autocomplete="off">
                                </div>
                            </div>
                        </div>
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
                                            <option value="<?= $id ?>" <?php if ($vehicleDetails['accidental_status'] == $id) {
                                                                            echo 'selected';
                                                                        } ?>><?= $type ?></option>
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
                                            <option value="<?= $id ?>" <?php if ($vehicleDetails['flooded_status'] == $id) {
                                                                            echo 'selected';
                                                                        } ?>><?= $type ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6 col-lg-3">
                                <div class="form-group">
                                    <label>Last Service Kilometer<span class="required">*</span></label>
                                    <input type="text" maxlength="9" placeholder="0" value="<?php echo isset($vehicleDetails['last_service_kms']) ? $vehicleDetails['last_service_kms'] : ''; ?>" class="form-control formInput numbersOnlyCheck" name="last_service_kms" id="last_service_kms">
                                </div>
                            </div>
                            <div class="col-md-6 col-lg-3">
                                <div class="form-group">
                                    <label>Last Service Date<span class="required">*</span></label>
                                    <input type="text" value="<?php echo isset($vehicleDetails['last_service_date']) ? date('d M Y', strtotime($vehicleDetails['last_service_date'])) : ''; ?>" class="form-control formInput date-picker" name="last_service_date" id="last_service_date" placeholder="Select Date" autocomplete="off">
                                </div>
                            </div>
                        </div>
                        <div class="pull-right">
                            <button type="button" class="btn btn-primary prev">Previous</button>
                            <button type="button" class="btn btn-primary next">Next</button>
                        </div>
                    </section>

                    <!-- Step 5 -->
                    <section id="step5" class="step">
                        <h5>Features</h5>
                        <div class="row pd-20" id="vehicleFeaturesWrapper">
                            <?php if ($vehicleDetails['vehicle_type'] == 1) { ?>
                                <?php echo view('dealer/vehicles/form_includes/cars/edit-car-step5'); ?>
                            <?php } elseif ($vehicleDetails['vehicle_type'] == 2) { ?>
                                <?php echo view('dealer/vehicles/form_includes/bikes/edit-bike-step5'); ?>
                            <?php } ?>
                        </div>
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
                                    <select class="custom-select formInput" name="onsale_status" id="onsale_status" data-toggle="tooltip" title="Vehicle will be displayed on Showroom pages On-Sale Section">
                                        <option value="">Choose...</option>
                                        <?php foreach (YES_NO_OPTIONS as $id => $type) : ?>
                                            <option value="<?= $id ?>" <?php if ($vehicleDetails['onsale_status'] == $id) {
                                                                            echo 'selected';
                                                                        } ?>><?= $type ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6 col-lg-3" id="onsale_percentage_div" style="<?php if ($vehicleDetails['onsale_status'] <> 1) {
                                                                                                    echo 'display:none;';
                                                                                                } ?>">
                                <div class="form-group">
                                    <label>On Sale Percentage<span class="required">*</span></label>
                                    <input type="text" maxlength="2" value="<?php echo isset($vehicleDetails['onsale_percentage']) ? $vehicleDetails['onsale_percentage'] : ''; ?>" class="form-control formInput numbersOnlyCheck" name="onsale_percentage" id="onsale_percentage">
                                </div>
                            </div>
                            <div class="col-md-6 col-lg-3">
                                <div class="form-group">
                                    <label>Regular Price<span class="required">*</span></label>
                                    <input type="text" maxlength="12" value="<?php echo isset($vehicleDetails['regular_price']) ? $vehicleDetails['regular_price'] : ''; ?>" value="" class="form-control formInput numbersOnlyCheck" name="regular_price" id="regular_price">
                                </div>
                            </div>
                            <div class="col-md-6 col-lg-3">
                                <div id="saleInput">
                                    <div class="form-group">
                                        <label>Selling Price<span class="required">*</span></label>
                                        <input type="text" maxlength="12" value="<?php echo isset($vehicleDetails['selling_price']) ? $vehicleDetails['selling_price'] : ''; ?>" class="form-control formInput numbersOnlyCheck" name="selling_price" id="selling_price" readonly>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 col-lg-3">
                                <div class="form-group">
                                    <label>Pricing Type<span class="required">*</span></label>
                                    <select class="custom-select formInput" name="pricing_type" id="pricing_type">
                                        <option value="">Choose...</option>
                                        <option value="1" <?php if ($vehicleDetails['pricing_type'] == 1) {
                                                                echo 'selected';
                                                            } ?>>Fixed Price</option>
                                        <option value="2" <?php if ($vehicleDetails['pricing_type'] == 2) {
                                                                echo 'selected';
                                                            } ?>>Negotiable</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6 col-lg-3">
                                <div class="form-group">
                                    <label>EMI Option<span class="required">*</span></label>
                                    <select class="custom-select formInput" name="emi_option" id="emi_option">
                                        <option value="">Choose...</option>
                                        <?php foreach (YES_NO_OPTIONS as $id => $type) : ?>
                                            <option value="<?= $id ?>" <?php if ($vehicleDetails['onsale_status'] == $id) {
                                                                            echo 'selected';
                                                                        } ?>><?= $type ?></option>
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

                <div class="row">
                    <div class="col-lg-3 col-md-6 col-sm-12 mb-30">
                        <label>Thumbnail Image</label><br>
                        <div class="card card-box">
                            <?php
                            $thumbnailUrl = isset($vehicleDetails['thumbnail_url']) ? WHEELPACT_VEHICLE_UPLOAD_IMG_PATH . 'vehicle_thumbnails/' . $vehicleDetails['thumbnail_url'] : WHEELPACT_VEHICLE_UPLOAD_IMG_PATH . 'default-img.png';
                            echo '<img class="card-img replaceThumbnailImg" src="' . $thumbnailUrl . '" class="img img-responsive vehicleImg" alt="' . $vehicleDetails['cmp_name'] . ' ' . $vehicleDetails['cmp_model_name'] . '">';
                            ?>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-3 col-md-6 col-sm-12 mb-30">
                        <div class="input-group mb-3">
                            <input type="file" class="form-control onlyImageInput" id="thumbnailImage" accept="image/*">
                            <div class="input-group-append">
                                <button class="btn btn-primary" id="uploadThumbnail">Update</button>
                            </div>
                        </div>
                    </div>
                </div>

                <?php if ($vehicleDetails['vehicle_type'] == 1) { ?>
                    <?php echo view('dealer/vehicles/form_includes/cars/edit-car-img-form'); ?>
                <?php } elseif ($vehicleDetails['vehicle_type'] == 2) { ?>
                    <?php echo view('dealer/vehicles/form_includes/bikes/edit-bike-img-form'); ?>
                <?php } ?>

            </div>
            <!-- vehicle image end -->
            <?php echo view('dealer/includes/_footer'); ?>
        </div>
    </div>
    <script>
        $(document).ready(function() {
            /* on page load trigger to load brands of cars & bikes both in select option filter */
            //$('.custom-select.vehicle-type').trigger('change');
        });
    </script>