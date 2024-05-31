<div class="row" id="car_features_section">
    <div class="col-md-6 col-lg-3">
        <div class="form-group">
            <label>Number of Airbags<span class="required">*</span></label>
            <select class="custom-select formInput" name="car_no_of_airbags" id="car_no_of_airbags">
                <option value="">Choose...</option>
                <?php foreach (CAR_AIRBAGS as $id => $type) {
                    echo '<option value="' . $id . '">' . $type . '</option>';
                } ?>
            </select>
        </div>
    </div>
    <div class="col-md-6 col-lg-3">
        <div class="form-group">
            <label>Central Locking<span class="required">*</span></label>
            <select class="custom-select formInput" name="car_central_locking" id="car_central_locking">
                <option value="">Choose...</option>
                <?php foreach (CENTERLOCK_TYPE as $id => $type) {
                    echo '<option value="' . $id . '">' . $type . '</option>';
                } ?>
            </select>
        </div>
    </div>
    <div class="col-md-6 col-lg-3">
        <div class="form-group">
            <label>Seat Upholstery<span class="required">*</span></label>
            <select class="custom-select formInput" name="car_seat_upholstery" id="car_seat_upholstery">
                <option value="">Choose...</option>
                <?php foreach (YES_NO_OPTIONS as $id => $type) {
                    echo '<option value="' . $id . '">' . $type . '</option>';
                } ?>
            </select>
        </div>
    </div>
    <div class="col-md-6 col-lg-3">
        <div class="form-group">
            <label>Sunroof/Moonroof<span class="required">*</span></label>
            <select class="custom-select formInput" name="car_sunroof" id="car_sunroof">
                <option value="">Choose...</option>
                <?php foreach (YES_NO_OPTIONS as $id => $type) {
                    echo '<option value="' . $id . '">' . $type . '</option>';
                } ?>
            </select>
        </div>
    </div>
    <div class="col-md-6 col-lg-3">
        <div class="form-group">
            <label>Integrated Music System<span class="required">*</span></label>
            <select class="custom-select formInput" name="car_integrated_music_system" id="car_integrated_music_system">
                <option value="">Choose...</option>
                <?php foreach (YES_NO_OPTIONS as $id => $type) {
                    echo '<option value="' . $id . '">' . $type . '</option>';
                } ?>
            </select>
        </div>
    </div>
    <div class="col-md-6 col-lg-3">
        <div class="form-group">
            <label>Rear AC<span class="required">*</span></label>
            <select class="custom-select formInput" name="car_rear_ac" id="car_rear_ac">
                <option value="">Choose...</option>
                <?php foreach (YES_NO_OPTIONS as $id => $type) {
                    echo '<option value="' . $id . '">' . $type . '</option>';
                } ?>
            </select>
        </div>
    </div>
    <div class="col-md-6 col-lg-3">
        <div class="form-group">
            <label>Outside Rear View Mirrors<span class="required">*</span></label>
            <select class="custom-select formInput" name="car_outside_rear_view_mirrors" id="car_outside_rear_view_mirrors">
                <option value="">Choose...</option>
                <?php foreach (YES_NO_OPTIONS as $id => $type) {
                    echo '<option value="' . $id . '">' . $type . '</option>';
                } ?>
            </select>
        </div>
    </div>
    <div class="col-md-6 col-lg-3">
        <div class="form-group">
            <label>Power Windows<span class="required">*</span></label>
            <select class="custom-select formInput" name="car_power_windows" id="car_power_windows">
                <option value="">Choose...</option>
                <?php foreach (YES_NO_OPTIONS as $id => $type) {
                    echo '<option value="' . $id . '">' . $type . '</option>';
                } ?>
            </select>
        </div>
    </div>
    <div class="col-md-6 col-lg-3">
        <div class="form-group">
            <label>Engine Start-Stop<span class="required">*</span></label>
            <select class="custom-select formInput" name="car_engine_start_stop" id="car_engine_start_stop">
                <option value="">Choose...</option>
                <?php foreach (YES_NO_OPTIONS as $id => $type) {
                    echo '<option value="' . $id . '">' . $type . '</option>';
                } ?>
            </select>
        </div>
    </div>
    <div class="col-md-6 col-lg-3">
        <div class="form-group">
            <label>Headlamps<span class="required">*</span></label>
            <select class="custom-select formInput" name="car_headlamps" id="car_headlamps">
                <option value="">Choose...</option>
                <?php foreach (HEADLAMPS_TYPE as $id => $type) {
                    echo '<option value="' . $id . '">' . $type . '</option>';
                } ?>
            </select>
        </div>
    </div>
    <div class="col-md-6 col-lg-3">
        <div class="form-group">
            <label>Power Steering<span class="required">*</span></label>
            <select class="custom-select formInput" name="car_power_steering" id="car_power_steering">
                <option value="">Choose...</option>
                <?php foreach (YES_NO_OPTIONS as $id => $type) {
                    echo '<option value="' . $id . '">' . $type . '</option>';
                } ?>
            </select>
        </div>
    </div>
</div>