<?php
echo view('dealer/includes/_header');
echo view('dealer/includes/_sidebar');
?>
<div class="main-container">
    <div class="pd-ltr-20 xs-pd-20-10">
        <div class="min-height-200px">
            <div class="page-header">
                <div class="row">
                    <div class="col-md-12 col-sm-12">
                        <div class="title">
                            <h4>Profile</h4>
                        </div>
                        <nav aria-label="breadcrumb" role="navigation">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item">
                                    <a href="<?php echo base_url(); ?>">Home</a>
                                </li>
                                <li class="breadcrumb-item active" aria-current="page">
                                    Profile
                                </li>
                            </ol>
                        </nav>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-xl-4 col-lg-4 col-md-4 col-sm-12 mb-30">
                    <div class="pd-20 card-box height-100-p">
                        <div class="profile-photo">
                            <img src="<?php echo !empty($dealerData['profile_image']) ? WHEELPACT_VEHICLE_UPLOAD_IMG_PATH . "user_profile_img/" . $dealerData['profile_image'] : NO_IMAGE_AVAILABLE; ?>" alt="" class="avatar-photo">
                        </div>
                        <h5 class="text-center h5 mb-0"><?php echo !empty($dealerData['name']) ? $dealerData['name'] : ''; ?></h5>
                        <p class="text-center text-muted font-14"></p>
                        <div class="profile-info">
                            <h5 class="mb-20 h5 text-blue">Contact Information</h5>
                            <ul>
                                <li>
                                    <span>Email Address:</span>
                                    <?php echo !empty($dealerData['email']) ? $dealerData['email'] : ''; ?>
                                </li>
                                <li>
                                    <span>Phone Number:</span>
                                    <?php echo !empty($dealerData['contact_no']) ? $dealerData['contact_no'] : ''; ?>
                                </li>
                                <li>
                                    <span>WhatsApp Number:</span>
                                    <?php echo !empty($dealerData['whatsapp_no']) ? $dealerData['whatsapp_no'] : ''; ?>
                                </li>
                                <li>
                                    <span>Country / State / City</span>
                                    <?php echo !empty($dealerData['country']) ? $dealerData['country'] : 'NA'; ?>, &nbsp;
                                    <?php echo !empty($dealerData['state']) ? $dealerData['state'] : 'NA'; ?>, &nbsp;
                                    <?php echo !empty($dealerData['city']) ? $dealerData['city'] : 'NA'; ?>
                                </li>
                                <li>
                                    <span>Resident Address:</span>
                                    <?php echo !empty($dealerData['addr_residential']) ? $dealerData['addr_residential'] : 'NA'; ?>
                                </li>
                                <li>
                                    <span>Permanent Address:</span>
                                    <?php echo !empty($dealerData['addr_permanent']) ? $dealerData['addr_permanent'] : 'NA'; ?>
                                </li>
                            </ul>
                        </div>
                        <div class="profile-social">
                            <h5 class="mb-20 h5 text-blue">Social Links</h5>
                            <ul class="clearfix">
                                <li>
                                    <a href="<?php echo !empty($dealerData['social_fb_link']) ? $dealerData['social_fb_link'] : '#'; ?>" target="_blank" class="btn" data-bgcolor="#3b5998" data-color="#ffffff" style="color: rgb(255, 255, 255); background-color: rgb(59, 89, 152);"><i class="fa fa-facebook"></i></a>
                                </li>
                                <li>
                                    <a href="<?php echo !empty($dealerData['social_twitter_link']) ? $dealerData['social_twitter_link'] : '#'; ?>" target="_blank" class="btn" data-bgcolor="#1da1f2" data-color="#ffffff" style="color: rgb(255, 255, 255); background-color: rgb(29, 161, 242);"><i class="fa fa-twitter"></i></a>
                                </li>
                                <li>
                                    <a href="<?php echo !empty($dealerData['social_linkedin_link']) ? $dealerData['social_linkedin_link'] : '#'; ?>" target="_blank" class="btn" data-bgcolor="#007bb5" data-color="#ffffff" style="color: rgb(255, 255, 255); background-color: rgb(0, 123, 181);"><i class="fa fa-linkedin"></i></a>
                                </li>
                                <li>
                                    <a href="<?php echo !empty($dealerData['social_skype_link']) ? $dealerData['social_skype_link'] : '#'; ?>" target="_blank" class="btn" data-bgcolor="#00aff0" data-color="#ffffff" style="color: rgb(255, 255, 255); background-color: rgb(0, 175, 240);"><i class="fa fa-skype"></i></a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="col-xl-8 col-lg-8 col-md-8 col-sm-12 mb-30">
                    <div class="card-box height-100-p overflow-hidden">
                        <div class="profile-tab height-100-p">
                            <div class="tab height-100-p">
                                <ul class="nav nav-tabs customtab" role="tablist">
                                    <li class="nav-item">
                                        <a class="nav-link active" data-toggle="tab" href="#personal-details" role="tab">Personal Details</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" data-toggle="tab" href="#change-password" role="tab">Update Password</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" data-toggle="tab" href="#plan-details" role="tab">Active Plan</a>
                                    </li>
                                </ul>
                                <div class="tab-content">
                                    <!-- Setting Tab start -->
                                    <div class="tab-pane fade show active height-100-p" id="personal-details" role="tabpanel">
                                        <div class="profile-setting">
                                            <?= form_open('dealer/update-profile-details', ['id' => 'update_profile_details', 'method' => 'POST', 'enctype' => 'multipart/form-data']); ?>
                                            <?= csrf_field(); ?>
                                            <ul class="profile-edit-list row">
                                                <li class="weight-500 col-md-6">
                                                    <h4 class="text-blue h5 mb-20">
                                                        Edit Your Personal Details
                                                    </h4>
                                                    <div class="form-group">
                                                        <label>Change Profile Image</label>
                                                        <input type="file" name="profile_image" id="profile_image" class="form-control-lg form-control-file height-auto">
                                                    </div>
                                                    <div class="form-group">
                                                        <label>Full Name</label>
                                                        <input class="form-control form-control-lg" name="dealerName" id="dealerName" type="text" value="<?php echo !empty($dealerData['name']) ? $dealerData['name'] : ''; ?>">
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="branchType">Gender</label>
                                                        <select class="form-control form-control-lg col-12 custom-select gender" name="gender" id="gender">
                                                            <option value="" selected>Select Gender</option>
                                                            <?php foreach (GENDER as $id => $type) : ?>
                                                                <?php if ($id != 0) : ?>
                                                                    <option value="<?= $id ?>" <?php if (!empty($dealerData['gender']) && $dealerData['gender'] == $id) { ?>selected<?php } ?>>
                                                                        <?= $type ?>
                                                                    </option>
                                                                <?php endif; ?>
                                                            <?php endforeach; ?>
                                                        </select>

                                                    </div>
                                                    <div class="form-group">
                                                        <label>Date of birth</label>
                                                        <input class="form-control form-control-lg date-picker" name="date_of_birth" type="text" value="<?php echo !empty($dealerData['date_of_birth']) ? $dealerData['date_of_birth'] : ''; ?>">
                                                    </div>
                                                    <div class="form-group">
                                                        <label>Postal Code</label>
                                                        <input class="form-control form-control-lg numbersOnlyCheck" minlength="5" maxlength="6" name="zipcode" type="text" value="<?php echo !empty($dealerData['zipcode']) ? $dealerData['zipcode'] : ''; ?>">
                                                    </div>
                                                    <div class="form-group">
                                                        <label>Residential Address</label>
                                                        <textarea class="form-control addr_residential" name="addr_residential"><?php echo !empty($dealerData['addr_residential']) ? $dealerData['addr_residential'] : ''; ?></textarea>
                                                    </div>
                                                    <div class="form-group">
                                                        <label>Permanent Address</label>
                                                        <div class="custom-control custom-checkbox mb-5">
                                                            <input type="checkbox" class="custom-control-input" id="copyAddress">
                                                            <label class="custom-control-label" for="copyAddress">Same As Residential Address</label>
                                                        </div>
                                                        <textarea class="form-control addr_permanent" name="addr_permanent"><?php echo !empty($dealerData['addr_residential']) ? $dealerData['addr_residential'] : ''; ?></textarea>
                                                    </div>
                                                </li>

                                                <li class="weight-500 col-md-6">
                                                    <h4 class="text-blue h5 mb-20"> &nbsp;</h4>
                                                    <div class="form-group">
                                                        <label>Email</label>
                                                        <input class="form-control form-control-lg" name="email" type="email" value="<?php echo !empty($dealerData['email']) ? $dealerData['email'] : ''; ?>" disabled readonly>
                                                    </div>
                                                    <div class="form-group">
                                                        <label>Phone Number</label>
                                                        <input class="form-control form-control-lg numbersOnlyCheck" minlength="9" maxlength="10" name="contact_no" id="contact_no" type="text" value="<?php echo !empty($dealerData['contact_no']) ? $dealerData['contact_no'] : ''; ?>" disabled readonly>
                                                    </div>
                                                    <div class="form-group">
                                                        <label>WhatsApp Number</label>
                                                        <input class="form-control form-control-lg numbersOnlyCheck" minlength="9" maxlength="10" name="whatsapp_no" id="whatsapp_no" type="text" value="<?php echo !empty($dealerData['whatsapp_no']) ? $dealerData['whatsapp_no'] : ''; ?>">
                                                    </div>

                                                    <div class="form-group">
                                                        <label for="chooseCountry">Choose Country</label>
                                                        <select class="col-12 form-control-lg custom-select country" id="chooseCountry" name="chooseCountry">
                                                            <option value="">Select Country</option>
                                                            <?php foreach ($countryList as $id => $country) : ?>
                                                                <option value="<?= $country['id'] ?>" <?= ($country['id'] == $dealerData['country_id']) ? 'selected' : '' ?>>
                                                                    <?= $country['name'] ?>
                                                                </option>
                                                            <?php endforeach; ?>
                                                        </select>
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="chooseState">Choose State</label>
                                                        <select class="col-12 form-control-lg custom-select state" aria-placeholder="Select State" id="chooseState" name="chooseState">
                                                            <option value="">State</option>
                                                        </select>
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="chooseCity">Choose City</label>
                                                        <select class="col-12 form-control-lg custom-select city" aria-placeholder="Select City" id="chooseCity" name="chooseCity">
                                                            <option value="">City</option>
                                                        </select>
                                                    </div>
                                                    <div class="form-group">
                                                        <label>Facebook URL:</label>
                                                        <input class="form-control form-control-lg" name="social_fb_link" id="social_fb_link" type="text" placeholder="Paste your link here" value="<?php echo !empty($dealerData['social_fb_link']) ? $dealerData['social_fb_link'] : ''; ?>">
                                                    </div>
                                                    <div class="form-group">
                                                        <label>Twitter URL:</label>
                                                        <input class="form-control form-control-lg" name="social_twitter_link" id="social_twitter_link" type="text" placeholder="Paste your link here" value="<?php echo !empty($dealerData['social_twitter_link']) ? $dealerData['social_twitter_link'] : ''; ?>">
                                                    </div>
                                                    <div class="form-group">
                                                        <label>Linkedin URL:</label>
                                                        <input class="form-control form-control-lg" name="social_linkedin_link" id="social_linkedin_link" type="text" placeholder="Paste your link here" value="<?php echo !empty($dealerData['social_linkedin_link']) ? $dealerData['social_linkedin_link'] : ''; ?>">
                                                    </div>
                                                    <div class="form-group">
                                                        <label>Skype URL:</label>
                                                        <input class="form-control form-control-lg" name="social_skype_link" id="social_skype_link" type="text" placeholder="Paste your link here" value="<?php echo !empty($dealerData['social_skype_link']) ? $dealerData['social_skype_link'] : ''; ?>">
                                                    </div>
                                                    <div class="form-group">
                                                        <div class="custom-control custom-checkbox mb-5">
                                                            <input type="checkbox" class="custom-control-input" id="newsletterConfirm">
                                                            <label class="custom-control-label weight-400" for="newsletterConfirm">I agree to receive notification emails</label>
                                                        </div>
                                                    </div>
                                                    <div class="form-group mb-0 text-right">
                                                        <input type="submit" class="btn btn-primary" value="Update Information">
                                                    </div>
                                                </li>
                                            </ul>
                                            <?= form_close() ?>
                                        </div>
                                    </div>
                                    <!-- Setting Tab End -->

                                    <!-- Change Password Tab start -->
                                    <div class="tab-pane fade" id="change-password" role="tabpanel">
                                        <div class="pd-20">
                                            <div class="container pd-0">
                                                <?= form_open('dealer/update-password', 'id="changePasswordForm" class="changePasswordForm"') ?>
                                                <?= csrf_field(); ?>
                                                <div class="row">
                                                    <div class="col-md-2 col-sm-12"></div>
                                                    <div class="col-md-6 col-sm-12">
                                                        <form class="form" role="form" autocomplete="off">
                                                            <div class="form-group">
                                                                <label for="current_pwd">Current Password</label>
                                                                <input type="password" class="form-control" minlength="8" maxlength="12" name="old_password" id="old_password" required="">
                                                            </div>
                                                            <div class="form-group">
                                                                <label for="new_pwd">New Password</label>
                                                                <input type="password" class="form-control" minlength="8" maxlength="12" name="new_pwd" id="new_pwd" required="">

                                                                <span class="form-text small text-muted">
                                                                    The password must be 8-12 characters, and must <em>not</em> contain spaces.
                                                                </span>
                                                            </div>
                                                            <div class="form-group">
                                                                <label for="conf_new_pwd">Verify</label>
                                                                <input type="password" class="form-control" minlength="8" maxlength="12" name="conf_new_pwd" id="conf_new_pwd" required="">
                                                                <span class="form-text small text-muted">
                                                                    To confirm, type the new password again.
                                                                </span>
                                                            </div>
                                                            <div class="form-group">
                                                                <button type="submit" class="btn btn-success float-right">Update Password</button>
                                                            </div>
                                                        </form>
                                                    </div>
                                                    <div class="col-md-2 col-sm-12"></div>
                                                </div>
                                                <?= form_close() ?>
                                            </div>
                                        </div>
                                        <!-- Timeline Tab End -->
                                    </div>

                                    <!-- Tasks Tab start -->
                                    <div class="tab-pane fade" id="plan-details" role="tabpanel">
                                        <div class="pd-20 profile-task-wrap">
                                            <div class="container pd-0">
                                                <table class="table table-bordered">
                                                    <thead>
                                                        <tr>
                                                            <th scope="col" colspan=2>Plan Details</th>
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
                                                            <td scope="row"><?php echo $planData['end_dt'] ?></td>
                                                        </tr>
                                                        <tr>
                                                            <th scope="row">Allowed Vehicle Listing</th>
                                                            <!-- if planId 1=>Free Or planId 2=> basic-->
                                                            <?php if ($planData['activePlan'] == '1' || $planData['activePlan'] == '2') { ?>
                                                                <td scope="row">
                                                                    <?php
                                                                    if ($planData['allowedVehicleListing'] == 0) {
                                                                        echo '<a href=' . base_url('/dealer/dashboard') . '>Click Here</a>';
                                                                    } else {
                                                                        echo isset(VEHICLE_TYPE[$planData['allowedVehicleListing']]) ? VEHICLE_TYPE[$planData['allowedVehicleListing']] : '';
                                                                    }
                                                                    ?>
                                                                </td>
                                                            <?php } else { ?>
                                                                <td scope="row">
                                                                    <?php echo VEHICLE_TYPE['3']; ?>
                                                                </td>
                                                            <?php } ?>
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
                                    <!-- Tasks Tab End -->
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
        $(document).ready(function() {
            const selectedStateId = <?php echo !empty($dealerData['state_id']) ? json_encode($dealerData['state_id']) : 'null'; ?>;
            const selectedCityId = <?php echo !empty($dealerData['city_id']) ? json_encode($dealerData['city_id']) : 'null'; ?>;

            console.log(selectedStateId + " -> " + selectedCityId);
            /*// Show loading text */
            $('#chooseState').after('<div id="loadingState">Loading state...</div>');
            $('#chooseCity').after('<div id="loadingCity">Loading city...</div>');

            /* on page load trigger to load brands of cars & bikes both in select option filter */
            $('.custom-select.country').trigger('change');

            setTimeout(function() {
                $('#loadingState').remove();
                /* // Set the selected state if available */
                if (selectedStateId !== null) {
                    const stateElement = $('#chooseState');
                    if (stateElement.length > 0) {
                        stateElement.val(selectedStateId);
                        $('.custom-select.state').trigger('change');
                    } else {
                        console.warn('Element #chooseState not found.');
                    }
                }
            }, 3000);

            setTimeout(function() {
                $('#loadingCity').remove();
                /* // Set the selected city if available */
                if (selectedCityId !== null) {
                    const cityElement = $('#chooseCity');
                    if (cityElement.length > 0) {
                        cityElement.val(selectedCityId);
                        $('.custom-select.city').trigger('change');
                    } else {
                        console.warn('Element #chooseCity not found.');
                    }
                }
            }, 6000);

            /* add active class to tab based in url */
            var hash = window.location.hash;

            /* // Activate the tab based on the hash */
            if (hash) {
                $('.nav-tabs a[href="' + hash + '"]').tab('show');
            }

            /* // Handle tab change event to update URL hash*/
            $('.nav-tabs a').on('shown.bs.tab', function(e) {
                window.location.hash = e.target.hash;
            });

        });
    </script>