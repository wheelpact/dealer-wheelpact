<div class="tab">
    <ul class="nav nav-tabs customtab" role="tablist">
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
                <input type="hidden" name="vehicleId" id="vehicleId" class="vehicleId" value="<?php echo isset($vehicleDetails['id']) ? $vehicleDetails['id'] : ''; ?>">
                <div class="accordion" id="vehicleExteriorImagesWrapper">
                    <div class="card">
                        <div class="card-header" id="headingOne">
                            <h5 class="text-blue">
                                <button class="btn btn-link" type="button" data-toggle="collapse" data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                                    Main Images<span class="required">*</span>
                                </button>
                            </h5>
                        </div>
                        <div id="collapseOne" class="collapse show" aria-labelledby="headingOne" data-parent="#vehicleExteriorImagesWrapper">
                            <div class="card-body">
                                <div class="row clearfix">
                                    <?php
                                    if (!empty($vehicleImagesDetails['exterior_main'])) {
                                        foreach ($vehicleImagesDetails['exterior_main'] as $key => $label) : ?>
                                            <div class="col-lg-3 col-md-6 col-sm-12 mb-30">
                                                <div class="da-card">
                                                    <div class="da-card-photo">
                                                        <img src="<?php echo isset($label) && !empty($label) ? WHEELPACT_VEHICLE_UPLOAD_IMG_PATH . 'vehicle_' . $key . '/' . $label : WHEELPACT_VEHICLE_UPLOAD_IMG_PATH . 'default-img.png'; ?>" class="img img-responsive replace_<?php echo $key; ?>" alt="<?php echo $label; ?>">
                                                        <div class="da-overlay">
                                                            <div class="da-social">
                                                                <ul class="clearfix">
                                                                    <li>
                                                                        <a class="replace_<?php echo $key; ?>_li" href="<?php echo isset($label) && !empty($label) ? WHEELPACT_VEHICLE_UPLOAD_IMG_PATH . 'vehicle_' . $key . '/' . $label : ''; ?>" data-fancybox="images" data-caption="<?php echo ucwords(implode(' ', array_slice(explode('_', $key), 2, 2))); ?>"><i class="fa fa-picture-o"></i></a>
                                                                    </li>
                                                                </ul>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="da-card-content">
                                                        <p class="mb-2">
                                                            <?php echo ucwords(implode(' ', array_slice(explode('_', $key), 2, 2))); ?> <span class="required">*</span>
                                                        </p>
                                                        <div class="form-group">
                                                            <div class="input-group mb-3">
                                                                <input type="file" name="<?php echo $key; ?>" id="<?php echo $key; ?>" class="form-control-file form-control height-auto onlyImageInput">
                                                                <div class="input-group-append">
                                                                    <button class="btn btn-primary btn-sm updateVehiceImg" data-pickFormfield="<?php echo $key; ?>">Update</button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                    <?php
                                        endforeach;
                                    } else {
                                        echo "No Images Found";
                                    }
                                    ?>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card">
                        <div class="card-header" id="headingTwo">
                            <h2 class="mb-0">
                                <button class="btn btn-link collapsed" type="button" data-toggle="collapse" data-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                                    Diagonal Images<span class="required">*</span>
                                </button>
                            </h2>
                        </div>
                        <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#vehicleExteriorImagesWrapper">
                            <div class="card-body">
                                <div class="row clearfix">
                                    <?php
                                    if (!empty($vehicleImagesDetails['exterior_diagnoal'])) {
                                        foreach ($vehicleImagesDetails['exterior_diagnoal'] as $key => $label) : ?>
                                            <div class="col-lg-3 col-md-6 col-sm-12 mb-30">
                                                <div class="da-card">
                                                    <div class="da-card-photo">
                                                        <img src="<?php echo isset($label) && !empty($label) ? WHEELPACT_VEHICLE_UPLOAD_IMG_PATH . 'vehicle_' . $key . '/' . $label : WHEELPACT_VEHICLE_UPLOAD_IMG_PATH . 'default-img.png'; ?>" class="img img-responsive replace_<?php echo $key; ?>" alt="<?php echo $label; ?>">
                                                        <div class="da-overlay">
                                                            <div class="da-social">
                                                                <ul class="clearfix">
                                                                    <li>
                                                                        <a class="replace_<?php echo $key; ?>_li" href="<?php echo isset($label) && !empty($label) ? WHEELPACT_VEHICLE_UPLOAD_IMG_PATH . 'vehicle_' . $key . '/' . $label : ''; ?>" data-fancybox="images" data-caption="<?php echo ucwords(implode(' ', array_slice(explode('_', $key), 2, 2))); ?>"><i class="fa fa-picture-o"></i></a>
                                                                    </li>
                                                                </ul>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="da-card-content">
                                                        <p class="mb-0">
                                                            <?php echo ucwords(implode(' ', array_slice(explode('_', $key), 2, 2))); ?> <span class="required">*</span>
                                                        </p>
                                                        <div class="form-group">
                                                            <div class="input-group mb-3">
                                                                <input type="file" name="<?php echo $key; ?>" id="<?php echo $key; ?>" class="form-control-file form-control height-auto onlyImageInput">
                                                                <div class="input-group-append">
                                                                    <button class="btn btn-primary btn-sm updateVehiceImg" data-pickFormfield="<?php echo $key; ?>">Update</button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                    <?php
                                        endforeach;
                                    } else {
                                        echo "No Images Found";
                                    }
                                    ?>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card">
                        <div class="card-header" id="headingThree">
                            <h2 class="mb-0">
                                <button class="btn btn-link collapsed" type="button" data-toggle="collapse" data-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                                    Wheel Images<span class="required">*</span>
                                </button>
                            </h2>
                        </div>
                        <div id="collapseThree" class="collapse" aria-labelledby="headingThree" data-parent="#vehicleExteriorImagesWrapper">
                            <div class="card-body">
                                <div class="row clearfix">
                                    <?php
                                    if (!empty($vehicleImagesDetails['exterior_wheel'])) {
                                        foreach ($vehicleImagesDetails['exterior_wheel'] as $key => $label) : ?>
                                            <div class="col-lg-3 col-md-6 col-sm-12 mb-30">
                                                <div class="da-card">
                                                    <div class="da-card-photo">
                                                        <img src="<?php echo isset($label) && !empty($label) ? WHEELPACT_VEHICLE_UPLOAD_IMG_PATH . 'vehicle_' . $key . '/' . $label : WHEELPACT_VEHICLE_UPLOAD_IMG_PATH . 'default-img.png'; ?>" class="img img-responsive replace_<?php echo $key; ?>" alt="<?php echo $label; ?>">
                                                        <div class="da-overlay">
                                                            <div class="da-social">
                                                                <ul class="clearfix">
                                                                    <li>
                                                                        <a class="replace_<?php echo $key; ?>_li" href="<?php echo isset($label) && !empty($label) ? WHEELPACT_VEHICLE_UPLOAD_IMG_PATH . 'vehicle_' . $key . '/' . $label : ''; ?>" data-fancybox="images" data-caption="<?php echo ucwords(implode(' ', array_slice(explode('_', $key), 2, 2))); ?>"><i class="fa fa-picture-o"></i></a>
                                                                    </li>
                                                                </ul>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="da-card-content">
                                                        <p class="mb-0">
                                                            <?php echo ucwords(implode(' ', array_slice(explode('_', $key), 2, 2))); ?> <span class="required">*</span>
                                                        </p>
                                                        <div class="form-group">
                                                            <div class="input-group mb-3">
                                                                <input type="file" name="<?php echo $key; ?>" id="<?php echo $key; ?>" class="form-control-file form-control height-auto onlyImageInput">
                                                                <div class="input-group-append">
                                                                    <button class="btn btn-primary btn-sm updateVehiceImg" data-pickFormfield="<?php echo $key; ?>">Update</button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                    <?php
                                        endforeach;
                                    } else {
                                        echo "No Images Found";
                                    }
                                    ?>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card">
                        <div class="card-header" id="headingFour">
                            <h2 class="mb-0">
                                <button class="btn btn-link collapsed" type="button" data-toggle="collapse" data-target="#collapseFour" aria-expanded="false" aria-controls="collapseFour">
                                    Tyre Tread Images<span class="required">*</span>
                                </button>
                            </h2>
                        </div>
                        <div id="collapseFour" class="collapse" aria-labelledby="headingFour" data-parent="#vehicleExteriorImagesWrapper">
                            <div class="card-body">
                                <div class="row clearfix">
                                    <?php
                                    if (!empty($vehicleImagesDetails['exterior_tyrethread'])) {
                                        foreach ($vehicleImagesDetails['exterior_tyrethread'] as $key => $label) : ?>
                                            <div class="col-lg-3 col-md-6 col-sm-12 mb-30">
                                                <div class="da-card">
                                                    <div class="da-card-photo">
                                                        <img src="<?php echo isset($label) && !empty($label) ? WHEELPACT_VEHICLE_UPLOAD_IMG_PATH . 'vehicle_' . $key . '/' . $label : WHEELPACT_VEHICLE_UPLOAD_IMG_PATH . 'default-img.png'; ?>" class="img img-responsive replace_<?php echo $key; ?>" alt="<?php echo $label; ?>">
                                                        <div class="da-overlay">
                                                            <div class="da-social">
                                                                <ul class="clearfix">
                                                                    <li>
                                                                        <a class="replace_<?php echo $key; ?>_li" href="<?php echo isset($label) && !empty($label) ? WHEELPACT_VEHICLE_UPLOAD_IMG_PATH . 'vehicle_' . $key . '/' . $label : ''; ?>" data-fancybox="images" data-caption="<?php echo ucwords(implode(' ', array_slice(explode('_', $key), 2, 2))); ?>"><i class="fa fa-picture-o"></i></a>
                                                                    </li>
                                                                </ul>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="da-card-content">
                                                        <p class="mb-0">
                                                            <?php echo ucwords(implode(' ', array_slice(explode('_', $key), 2, 2))); ?> <span class="required">*</span>
                                                        </p>
                                                        <div class="form-group">
                                                            <div class="input-group mb-3">
                                                                <input type="file" name="<?php echo $key; ?>" id="<?php echo $key; ?>" class="form-control-file form-control height-auto onlyImageInput">
                                                                <div class="input-group-append">
                                                                    <button class="btn btn-primary btn-sm updateVehiceImg" data-pickFormfield="<?php echo $key; ?>">Update</button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                    <?php
                                        endforeach;
                                    } else {
                                        echo "No Images Found";
                                    }
                                    ?>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card">
                        <div class="card-header" id="headingFive">
                            <h2 class="mb-0">
                                <button class="btn btn-link collapsed" type="button" data-toggle="collapse" data-target="#collapseFive" aria-expanded="false" aria-controls="collapseFive">
                                    Underbody Images<span class="required">*</span>
                                </button>
                            </h2>
                        </div>
                        <div id="collapseFive" class="collapse" aria-labelledby="headingFive" data-parent="#vehicleExteriorImagesWrapper">
                            <div class="card-body">
                                <div class="row clearfix">
                                    <?php
                                    if (!empty($vehicleImagesDetails['exterior_underbody'])) {
                                        foreach ($vehicleImagesDetails['exterior_underbody'] as $key => $label) : ?>
                                            <div class="col-lg-3 col-md-6 col-sm-12 mb-30">
                                                <div class="da-card">
                                                    <div class="da-card-photo">
                                                        <img src="<?php echo isset($label) && !empty($label) ? WHEELPACT_VEHICLE_UPLOAD_IMG_PATH . 'vehicle_' . $key . '/' . $label : WHEELPACT_VEHICLE_UPLOAD_IMG_PATH . 'default-img.png'; ?>" class="img img-responsive replace_<?php echo $key; ?>" alt="<?php echo $label; ?>">
                                                        <div class="da-overlay">
                                                            <div class="da-social">
                                                                <ul class="clearfix">
                                                                    <li>
                                                                        <a class="replace_<?php echo $key; ?>_li" href="<?php echo isset($label) && !empty($label) ? WHEELPACT_VEHICLE_UPLOAD_IMG_PATH . 'vehicle_' . $key . '/' . $label : ''; ?>" data-fancybox="images" data-caption="<?php echo ucwords(implode(' ', array_slice(explode('_', $key), 2, 2))); ?>"><i class="fa fa-picture-o"></i></a>
                                                                    </li>
                                                                </ul>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="da-card-content">
                                                        <p class="mb-0">
                                                            <?php echo ucwords(implode(' ', array_slice(explode('_', $key), 2, 2))); ?> <span class="required">*</span>
                                                        </p>
                                                        <div class="form-group">
                                                            <div class="input-group mb-3">
                                                                <input type="file" name="<?php echo $key; ?>" id="<?php echo $key; ?>" class="form-control-file form-control height-auto onlyImageInput" required>
                                                                <div class="input-group-append">
                                                                    <button class="btn btn-primary btn-sm updateVehiceImg" data-pickFormfield="<?php echo $key; ?>">Update</button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                    <?php endforeach;
                                    } else {
                                        echo "No Images Found";
                                    }
                                    ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="pd-20">
                    <div class="pull-right">
                        <button type="submit" class="btn btn-primary">Submit</button>
                    </div>
                </div>
            </div>
        </div>

        <div class="tab-pane fade" id="interior" role="tabpanel">
            <div class="pd-20">
                <div class="row clearfix">
                    <?php
                    if (!empty($vehicleImagesDetails['interior'])) {
                        foreach ($vehicleImagesDetails['interior'] as $key => $label) : ?>
                            <div class="col-lg-3 col-md-6 col-sm-12 mb-30">
                                <div class="da-card">
                                    <div class="da-card-photo">
                                        <img src="<?php echo isset($label) && !empty($label) ? WHEELPACT_VEHICLE_UPLOAD_IMG_PATH . 'vehicle_' . $key . '/' . $label : WHEELPACT_VEHICLE_UPLOAD_IMG_PATH . 'default-img.png'; ?>" class="img img-responsive replace_<?php echo $key; ?>" alt="<?php echo $label; ?>">
                                        <div class="da-overlay">
                                            <div class="da-social">
                                                <ul class="clearfix">
                                                    <li>
                                                        <a class="replace_<?php echo $key; ?>_li" href="<?php echo isset($label) && !empty($label) ? WHEELPACT_VEHICLE_UPLOAD_IMG_PATH . 'vehicle_' . $key . '/' . $label : ''; ?>" data-fancybox="images" data-caption="<?php echo ucwords(implode(' ', array_slice(explode('_', $key), 2, 2))); ?>"><i class="fa fa-picture-o"></i></a>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="da-card-content">
                                        <p class="mb-0">
                                            <?php echo ucwords(implode(' ', array_slice(explode('_', $key), 1, 2))); ?> <span class="required">*</span>
                                        </p>
                                        <div class="form-group">
                                            <div class="input-group mb-3">
                                                <input type="file" name="<?php echo $key; ?>" id="<?php echo $key; ?>" class="form-control-file form-control height-auto onlyImageInput" required>
                                                <div class="input-group-append">
                                                    <button class="btn btn-primary btn-sm updateVehiceImg" data-pickFormfield="<?php echo $key; ?>">Update</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                    <?php endforeach;
                    } else {
                        echo "No Images Found";
                    }
                    ?>
                </div>
            </div>
        </div>

        <div class="tab-pane fade" id="others" role="tabpanel">
            <div class="pd-20">
                <div class="row clearfix">
                    <?php
                    if (!empty($vehicleImagesDetails['others'])) {
                        foreach ($vehicleImagesDetails['others'] as $key => $label) : ?>
                            <div class="col-lg-3 col-md-6 col-sm-12 mb-30">
                                <div class="da-card">
                                    <div class="da-card-photo">
                                        <img src="<?php echo isset($label) && !empty($label) ? WHEELPACT_VEHICLE_UPLOAD_IMG_PATH . 'vehicle_' . $key . '/' . $label : WHEELPACT_VEHICLE_UPLOAD_IMG_PATH . 'default-img.png'; ?>" class="img img-responsive replace_<?php echo $key; ?>" alt="<?php echo $label; ?>">
                                        <div class="da-overlay">
                                            <div class="da-social">
                                                <ul class="clearfix">
                                                    <li>
                                                        <a class="replace_<?php echo $key; ?>_li" href="<?php echo isset($label) && !empty($label) ? WHEELPACT_VEHICLE_UPLOAD_IMG_PATH . 'vehicle_' . $key . '/' . $label : ''; ?>" data-fancybox="images" data-caption="<?php echo ucwords(implode(' ', array_slice(explode('_', $key), 2, 2))); ?>"><i class="fa fa-picture-o"></i></a>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="da-card-content">
                                        <p class="mb-0">
                                            <?php echo ucwords(implode(' ', array_slice(explode('_', $key), 1, 2))); ?> <span class="required">*</span>
                                        </p>
                                        <div class="form-group">
                                            <div class="input-group mb-3">
                                                <input type="file" name="<?php echo $key; ?>" id="<?php echo $key; ?>" class="form-control-file form-control height-auto onlyImageInput" required>
                                                <div class="input-group-append">
                                                    <button class="btn btn-primary btn-sm updateVehiceImg" data-pickFormfield="<?php echo $key; ?>">Update</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                    <?php endforeach;
                    } else {
                        echo "No Images Found";
                    }
                    ?>
                </div>
            </div>
        </div>
    </div>
</div>