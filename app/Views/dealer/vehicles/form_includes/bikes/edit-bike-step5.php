<div class="row" id="bike_features_section">
    <div class="col-md-6 col-lg-3">
        <div class="form-group">
            <label>Headlight Type<span class="required">*</span></label>
            <select class="custom-select formInput" name="bike_headlight_type" id="bike_headlight_type">
                <option value="">Choose...</option>
                <?php foreach (HEADLAMPS_TYPE as $id => $type) : ?>
                    <option value="<?= $id ?>" <?php if ($vehicleDetails['bike_headlight_type'] == $id) {
                                                    echo 'selected';
                                                } ?>><?= $type ?></option>
                <?php endforeach; ?>
            </select>
        </div>
    </div>
    <div class="col-md-6 col-lg-3">
        <div class="form-group">
            <label>Odometer<span class="required">*</span></label>
            <select class="custom-select formInput" name="bike_odometer" id="bike_odometer">
                <option value="">Choose...</option>
                <?php foreach (BIKE_ODOMETER as $id => $type) : ?>
                    <option value="<?= $id ?>" <?php if ($vehicleDetails['bike_odometer'] == $id) {
                                                    echo 'selected';
                                                } ?>><?= $type ?></option>
                <?php endforeach; ?>
            </select>
        </div>
    </div>
    <div class="col-md-6 col-lg-3">
        <div class="form-group">
            <label>DRLs (Day Time Running Lights)<span class="required">*</span></label>
            <select class="custom-select formInput" name="bike_drl" id="bike_drl">
                <option value="">Choose...</option>
                <?php foreach (YES_NO_OPTIONS as $id => $type) : ?>
                    <option value="<?= $id ?>" <?php if ($vehicleDetails['bike_drl'] == $id) {
                                                    echo 'selected';
                                                } ?>><?= $type ?></option>
                <?php endforeach; ?>
            </select>
        </div>
    </div>
    <div class="col-md-6 col-lg-3">
        <div class="form-group">
            <label>Mobile Connectivity<span class="required">*</span></label>
            <select class="custom-select formInput" name="bike_mobile_connectivity" id="bike_mobile_connectivity">
                <option value="">Choose...</option>
                <?php foreach (YES_NO_OPTIONS as $id => $type) : ?>
                    <option value="<?= $id ?>" <?php if ($vehicleDetails['bike_mobile_connectivity'] == $id) {
                                                    echo 'selected';
                                                } ?>><?= $type ?></option>
                <?php endforeach; ?>
            </select>
        </div>
    </div>
    <div class="col-md-6 col-lg-3">
        <div class="form-group">
            <label>GPS Navigation<span class="required">*</span></label>
            <select class="custom-select formInput" name="bike_gps_navigation" id="bike_gps_navigation">
                <option value="">Choose...</option>
                <?php foreach (YES_NO_OPTIONS as $id => $type) : ?>
                    <option value="<?= $id ?>" <?php if ($vehicleDetails['bike_gps_navigation'] == $id) {
                                                    echo 'selected';
                                                } ?>><?= $type ?></option>
                <?php endforeach; ?>
            </select>
        </div>
    </div>
    <div class="col-md-6 col-lg-3">
        <div class="form-group">
            <label>USB Charging Port<span class="required">*</span></label>
            <select class="custom-select formInput" name="bike_usb_charging_port" id="bike_usb_charging_port">
                <option value="">Choose...</option>
                <?php foreach (YES_NO_OPTIONS as $id => $type) : ?>
                    <option value="<?= $id ?>" <?php if ($vehicleDetails['bike_usb_charging_port'] == $id) {
                                                    echo 'selected';
                                                } ?>><?= $type ?></option>
                <?php endforeach; ?>
            </select>
        </div>
    </div>
    <div class="col-md-6 col-lg-3">
        <div class="form-group">
            <label>Low Battery Indicator<span class="required">*</span></label>
            <select class="custom-select formInput" name="bike_low_battery_indicator" id="bike_low_battery_indicator">
                <option value="">Choose...</option>
                <?php foreach (YES_NO_OPTIONS as $id => $type) : ?>
                    <option value="<?= $id ?>" <?php if ($vehicleDetails['bike_low_battery_indicator'] == $id) {
                                                    echo 'selected';
                                                } ?>><?= $type ?></option>
                <?php endforeach; ?>
            </select>
        </div>
    </div>
    <div class="col-md-6 col-lg-3">
        <div class="form-group">
            <label>Under Seat Storage<span class="required">*</span></label>
            <select class="custom-select formInput" name="bike_under_seat_storage" id="bike_under_seat_storage">
                <option value="">Choose...</option>
                <?php foreach (YES_NO_OPTIONS as $id => $type) : ?>
                    <option value="<?= $id ?>" <?php if ($vehicleDetails['bike_under_seat_storage'] == $id) {
                                                    echo 'selected';
                                                } ?>><?= $type ?></option>
                <?php endforeach; ?>
            </select>
        </div>
    </div>
    <div class="col-md-6 col-lg-3">
        <div class="form-group"><label>Speedometer<span class="required">*</span></label>
            <select class="custom-select formInput" name="bike_speedometer" id="bike_speedometer">
                <option value="">Choose...</option>
                <?php foreach (YES_NO_OPTIONS as $id => $type) : ?>
                    <option value="<?= $id ?>" <?php if ($vehicleDetails['bike_speedometer'] == $id) {
                                                    echo 'selected';
                                                } ?>><?= $type ?></option>
                <?php endforeach; ?>
            </select>
        </div>
    </div>
    <div class="col-md-6 col-lg-3">
        <div class="form-group">
            <label>Stand Alarm<span class="required">*</span></label>
            <select class="custom-select formInput" name="bike_stand_alarm" id="bike_stand_alarm">
                <option value="">Choose...</option>
                <?php foreach (BIKE_ODOMETER as $id => $type) : ?>
                    <option value="<?= $id ?>" <?php if ($vehicleDetails['bike_odometer'] == $id) {
                                                    echo 'selected';
                                                } ?>><?= $type ?></option>
                <?php endforeach; ?>
            </select>
        </div>
    </div>
    <div class="col-md-6 col-lg-3">
        <div class="form-group">
            <label>Low Fuel Indicator<span class="required">*</span></label>
            <select class="custom-select formInput" name="bike_low_fuel_indicator" id="bike_low_fuel_indicator">
                <option value="">Choose...</option>
                <?php foreach (YES_NO_OPTIONS as $id => $type) : ?>
                    <option value="<?= $id ?>" <?php if ($vehicleDetails['bike_low_fuel_indicator'] == $id) {
                                                    echo 'selected';
                                                } ?>><?= $type ?></option>
                <?php endforeach; ?>
            </select>
        </div>
    </div>
    <div class="col-md-6 col-lg-3">
        <div class="form-group">
            <label>Low Oil Indicator<span class="required">*</span></label>
            <select class="custom-select formInput" name="bike_low_oil_indicator" id="bike_low_oil_indicator">
                <option value="">Choose...</option>
                <?php foreach (YES_NO_OPTIONS as $id => $type) : ?>
                    <option value="<?= $id ?>" <?php if ($vehicleDetails['bike_low_oil_indicator'] == $id) {
                                                    echo 'selected';
                                                } ?>><?= $type ?></option>
                <?php endforeach; ?>
            </select>
        </div>
    </div>
    <div class="col-md-6 col-lg-3">
        <div class="form-group">
            <label>Start Type<span class="required">*</span></label>
            <select class="custom-select formInput" name="bike_start_type" id="bike_start_type">
                <option value="">Choose...</option>
                <?php foreach (BIKE_START_TYPE as $id => $type) : ?>
                    <option value="<?= $id ?>" <?php if ($vehicleDetails['bike_start_type'] == $id) {
                                                    echo 'selected';
                                                } ?>><?= $type ?></option>
                <?php endforeach; ?>
            </select>
        </div>
    </div>
    <div class="col-md-6 col-lg-3">
        <div class="form-group">
            <label>Kill Switch<span class="required">*</span></label>
            <select class="custom-select formInput" name="bike_kill_switch" id="bike_kill_switch">
                <option value="">Choose...</option>
                <?php foreach (YES_NO_OPTIONS as $id => $type) : ?>
                    <option value="<?= $id ?>" <?php if ($vehicleDetails['bike_kill_switch'] == $id) {
                                                    echo 'selected';
                                                } ?>><?= $type ?></option>
                <?php endforeach; ?>
            </select>
        </div>
    </div>
    <div class="col-md-6 col-lg-3">
        <div class="form-group">
            <label>Break Light<span class="required">*</span></label>
            <select class="custom-select formInput" name="bike_break_light" id="bike_break_light">
                <option value="">Choose...</option>
                <?php foreach (HEADLAMPS_TYPE as $id => $type) : ?>
                    <option value="<?= $id ?>" <?php if ($vehicleDetails['bike_break_light'] == $id) {
                                                    echo 'selected';
                                                } ?>><?= $type ?></option>
                <?php endforeach; ?>
            </select>
        </div>
    </div>
    <div class="col-md-6 col-lg-3">
        <div class="form-group">
            <label>Turn Signal Indicator<span class="required">*</span></label>
            <select class="custom-select formInput" name="bike_turn_signal_indicator" id="bike_turn_signal_indicator">
                <option value="">Choose...</option>
                <?php foreach (HEADLAMPS_TYPE as $id => $type) : ?>
                    <option value="<?= $id ?>" <?php if ($vehicleDetails['bike_turn_signal_indicator'] == $id) {
                                                    echo 'selected';
                                                } ?>><?= $type ?></option>
                <?php endforeach; ?>
            </select>
        </div>
    </div>
</div>