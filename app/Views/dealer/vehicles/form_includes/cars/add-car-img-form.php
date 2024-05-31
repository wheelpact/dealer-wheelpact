<div class="card">
    <div class="card-header" id="headingOne">
        <h5 class="text-blue">
            <button class="btn btn-link collapsed" id="exterior_main_toggle_btn" type="button" data-toggle="collapse" data-target="#collapseOne" aria-expanded="false" aria-controls="collapseOne">
                Main Images<span class="required">*</span>
            </button>
        </h5>
    </div>
    <?= form_open_multipart('dealer/upload-vehicle-images', 'id="exterior_main"') ?>
    <?= csrf_field(); ?>
    <div id="collapseOne" class="collapse show" aria-labelledby="headingOne" data-parent="#vehicleExteriorImagesWrapper">
        <div class="card-body">
            <div class="row">
                <div class="col-md-6 col-lg-3">
                    <div class="form-group">
                        <label>Front Image<span class="required">*</span></label>
                        <input type="file" name="exterior_main_front_img" id="exterior_main_front_img" class="form-control-file form-control height-auto formInput onlyImageInput" required>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3">
                    <div class="form-group">
                        <label>Right Image<span class="required">*</span></label>
                        <input type="file" name="exterior_main_right_img" id="exterior_main_right_img" class="form-control-file form-control height-auto formInput onlyImageInput" required>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3">
                    <div class="form-group">
                        <label>Back Image<span class="required">*</span></label>
                        <input type="file" name="exterior_main_back_img" id="exterior_main_back_img" class="form-control-file form-control height-auto formInput onlyImageInput" required>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3">
                    <div class="form-group">
                        <label>Left Image<span class="required">*</span></label>
                        <input type="file" name="exterior_main_left_img" id="exterior_main_left_img" class="form-control-file form-control height-auto formInput onlyImageInput" required>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3">
                    <div class="form-group">
                        <label>Roof Image<span class="required">*</span></label>
                        <input type="file" name="exterior_main_roof_img" id="exterior_main_roof_img" class="form-control-file form-control height-auto formInput onlyImageInput" required>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3">
                    <div class="form-group">
                        <label>Bonet Open Image<span class="required">*</span></label>
                        <input type="file" name="exterior_main_bonetopen_img" id="exterior_main_bonetopen_img" class="form-control-file form-control height-auto formInput onlyImageInput" required>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3">
                    <div class="form-group">
                        <label>Engine Image<span class="required">*</span></label>
                        <input type="file" name="exterior_main_engine_img" id="exterior_main_engine_img" class="form-control-file form-control height-auto formInput onlyImageInput" required>
                    </div>
                </div>
            </div>
            <div class="row clearfix">
                <div class="col-md-6 col-lg-3"></div>
                <div class="col-md-6 col-lg-3">
                    <div class="pull-right">
                        <button type="submit" class="btn btn-primary upload_vehicle_images_form">Submit</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?= form_close() ?>
</div>

<div class="card">
    <div class="card-header" id="headingTwo">
        <h2 class="mb-0">
            <button class="btn btn-link collapsed" id="exterior_diagnoal_toggle_btn" type="button" data-toggle="collapse" data-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                Diagonal Images<span class="required">*</span>
            </button>
        </h2>
    </div>
    <?= form_open_multipart('dealer/upload-vehicle-images', 'id="exterior_diagnoal"') ?>
    <?= csrf_field(); ?>
    <div id="collapseTwo" class="collapse exterior_diagnoal" aria-labelledby="headingTwo" data-parent="#vehicleExteriorImagesWrapper">
        <div class="card-body">
            <div class="row">
                <div class="col-md-6 col-lg-3">
                    <div class="form-group">
                        <label>Right Front Diagonal Image<span class="required">*</span></label>
                        <input type="file" name="exterior_diagnoal_right_front_img" id="exterior_diagnoal_right_front_img" class="form-control-file form-control height-auto onlyImageInput" required>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3">
                    <div class="form-group">
                        <label>Right Back Diagonal Image<span class="required">*</span></label>
                        <input type="file" name="exterior_diagnoal_right_back_img" id="exterior_diagnoal_right_back_img" class="form-control-file form-control height-auto onlyImageInput" required>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3">
                    <div class="form-group">
                        <label>Left Back Diagonal Image<span class="required">*</span></label>
                        <input type="file" name="exterior_diagnoal_left_back_img" id="exterior_diagnoal_left_back_img" class="form-control-file form-control height-auto onlyImageInput" required>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3">
                    <div class="form-group">
                        <label>Left Front Diagonal Image<span class="required">*</span></label>
                        <input type="file" name="exterior_diagnoal_left_front_img" id="exterior_diagnoal_left_front_img" class="form-control-file form-control height-auto onlyImageInput" required>
                    </div>
                </div>
            </div>
            <div class="row clearfix">
                <div class="col-md-6 col-lg-3"></div>
                <div class="col-md-6 col-lg-3">
                    <div class="pull-right">
                        <button type="submit" class="btn btn-primary upload_vehicle_images_form">Submit</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?= form_close() ?>
</div>

<div class="card">
    <div class="card-header" id="headingThree">
        <h2 class="mb-0">
            <button class="btn btn-link collapsed" id="exterior_wheel_toggle_btn" type="button" data-toggle="collapse" data-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                Wheel Images<span class="required">*</span>
            </button>
        </h2>
    </div>
    <?= form_open_multipart('dealer/upload-vehicle-images', 'id="exterior_wheel"') ?>
    <?= csrf_field(); ?>
    <div id="collapseThree" class="collapse" aria-labelledby="headingThree" data-parent="#vehicleExteriorImagesWrapper">
        <div class="card-body">
            <div class="row">
                <div class="col-md-6 col-lg-3">
                    <div class="form-group">
                        <label>Right Front Wheel Image<span class="required">*</span></label>
                        <input type="file" name="exterior_wheel_right_front_img" id="exterior_wheel_right_front_img" class="form-control-file form-control height-auto onlyImageInput" required>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3">
                    <div class="form-group">
                        <label>Right Back Wheel Image<span class="required">*</span></label>
                        <input type="file" name="exterior_wheel_right_back_img" id="exterior_wheel_right_back_img" class="form-control-file form-control height-auto onlyImageInput" required>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3">
                    <div class="form-group">
                        <label>Left Back Wheel Image<span class="required">*</span></label>
                        <input type="file" name="exterior_wheel_left_back_img" id="exterior_wheel_left_back_img" class="form-control-file form-control height-auto onlyImageInput" required>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3">
                    <div class="form-group">
                        <label>Left Front Wheel Image<span class="required">*</span></label>
                        <input type="file" name="exterior_wheel_left_front_img" id="exterior_wheel_left_front_img" class="form-control-file form-control height-auto onlyImageInput" required>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3">
                    <div class="form-group">
                        <label>Spare Wheel Image<span class="required">*</span></label>
                        <input type="file" name="exterior_wheel_spare_img" id="exterior_wheel_spare_img" class="form-control-file form-control height-auto onlyImageInput" required>
                    </div>
                </div>
            </div>
            <div class="row clearfix">
                <div class="col-md-6 col-lg-3"></div>
                <div class="col-md-6 col-lg-3">
                    <div class="pull-right">
                        <button type="submit" class="btn btn-primary upload_vehicle_images_form">Submit</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?= form_close() ?>
</div>

<div class="card">
    <div class="card-header" id="headingFour">
        <h2 class="mb-0">
            <button class="btn btn-link collapsed" id="exterior_tyrethread_toggle_btn" type="button" data-toggle="collapse" data-target="#collapseFour" aria-expanded="false" aria-controls="collapseFour">
                Tyre Tread Images<span class="required">*</span>
            </button>
        </h2>
    </div>
    <?= form_open_multipart('dealer/upload-vehicle-images', 'id="exterior_tyrethread"') ?>
    <?= csrf_field(); ?>
    <div id="collapseFour" class="collapse" aria-labelledby="headingFour" data-parent="#vehicleExteriorImagesWrapper">
        <div class="card-body">
            <div class="row">
                <div class="col-md-6 col-lg-3">
                    <div class="form-group">
                        <label>Right Front Tyre Tread Image<span class="required">*</span></label>
                        <input type="file" name="exterior_tyrethread_right_front_img" id="exterior_tyrethread_right_front_img" class="form-control-file form-control height-auto onlyImageInput" required>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3">
                    <div class="form-group">
                        <label>Right Back Tyre Tread Image<span class="required">*</span></label>
                        <input type="file" name="exterior_tyrethread_right_back_img" id="exterior_tyrethread_right_back_img" class="form-control-file form-control height-auto onlyImageInput" required>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3">
                    <div class="form-group">
                        <label>Left Back Tyre Tread Image<span class="required">*</span></label>
                        <input type="file" name="exterior_tyrethread_left_back_img" id="exterior_tyrethread_left_back_img" class="form-control-file form-control height-auto onlyImageInput" required>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3">
                    <div class="form-group">
                        <label>Left Front Tyre Tread Image<span class="required">*</span></label>
                        <input type="file" name="exterior_tyrethread_left_front_img" id="exterior_tyrethread_left_front_img" class="form-control-file form-control height-auto onlyImageInput" required>
                    </div>
                </div>
            </div>
            <div class="row clearfix">
                <div class="col-md-6 col-lg-3"></div>
                <div class="col-md-6 col-lg-3">
                    <div class="pull-right">
                        <button type="submit" class="btn btn-primary upload_vehicle_images_form">Submit</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?= form_close() ?>
</div>

<div class="card">
    <div class="card-header" id="headingFive">
        <h2 class="mb-0">
            <button class="btn btn-link collapsed" id="exterior_underbody_toggle_btn" type="button" data-toggle="collapse" data-target="#collapseFive" aria-expanded="false" aria-controls="collapseFive">
                Underbody Images<span class="required">*</span>
            </button>
        </h2>
    </div>
    <?= form_open_multipart('dealer/upload-vehicle-images', 'id="exterior_underbody"') ?>
    <?= csrf_field(); ?>
    <div id="collapseFive" class="collapse" aria-labelledby="headingFive" data-parent="#vehicleExteriorImagesWrapper">
        <div class="card-body">
            <div class="row">
                <div class="col-md-6 col-lg-3">
                    <div class="form-group">
                        <label>Front Underbody Image<span class="required">*</span></label>
                        <input type="file" name="exterior_underbody_front_img" id="exterior_underbody_front_img" class="form-control-file form-control height-auto onlyImageInput" required>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3">
                    <div class="form-group">
                        <label>Rear Underbody Image<span class="required">*</span></label>
                        <input type="file" name="exterior_underbody_rear_img" id="exterior_underbody_rear_img" class="form-control-file form-control height-auto onlyImageInput" required>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3">
                    <div class="form-group">
                        <label>Right Side Underbody Image<span class="required">*</span></label>
                        <input type="file" name="exterior_underbody_right_img" id="exterior_underbody_right_img" class="form-control-file form-control height-auto onlyImageInput" required>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3">
                    <div class="form-group">
                        <label>Left Side Underbody Image<span class="required">*</span></label>
                        <input type="file" name="exterior_underbody_left_img" id="exterior_underbody_left_img" class="form-control-file form-control height-auto onlyImageInput" required>
                    </div>
                </div>
            </div>
            <div class="row clearfix">
                <div class="col-md-6 col-lg-3"></div>
                <div class="col-md-6 col-lg-3">
                    <div class="pull-right">
                        <button type="submit" class="btn btn-primary upload_vehicle_images_form">Submit</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?= form_close() ?>
</div>