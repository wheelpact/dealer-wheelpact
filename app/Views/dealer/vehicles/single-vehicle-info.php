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
                            <h4>Single Vehicle Information</h4>
                        </div>
                        <nav aria-label="breadcrumb" role="navigation">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="<?php echo base_url('dealer/dashboard'); ?>">Home</a></li>
                                <li class="breadcrumb-item active" aria-current="page">Vehicles</li>
                                <li class="breadcrumb-item active" aria-current="page">View Vehicle Info</li>
                            </ol>
                        </nav>
                    </div>
                    <div class="col-md-6 col-sm-12 text-right">
                        <div class="">
                            <a class="btn btn-primary" href="<?php echo base_url('dealer/edit-vehicle/' . $vehicleDetails['id']); ?>" role="button">
                                Edit This Vehicle
                            </a>
                            <a class="btn btn-primary" href="<?php echo base_url('dealer/list-vehicles'); ?>" role="button">
                                List Vehicles
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="pd-20 card-box mb-30">
                <div class="clearfix">
                    <h4 class="text-blue h4">View Vehicle Details</h4>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <!-- Nav tabs -->
                        <ul class="nav nav-tabs">
                            <li class="nav-item"><a class="nav-link active" data-toggle="tab" href="#step1">Vehicle Info</a></li>
                            <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#step2">Registration Details</a></li>
                            <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#step3">Insurance Details</a></li>
                            <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#step4">Overview</a></li>
                            <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#step5">Features</a></li>
                            <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#step6">Pricing</a></li>
                        </ul>
                        <!-- Tab panes -->
                        <div class="tab-content">
                            <div class="tab-pane container active" id="step1">
                                <div class="row mt-2">
                                    <div class="col-md-12 col-lg-12">
                                        <table class="table table-bordered">
                                            <tr>
                                                <th>Showroom</th>
                                                <td>
                                                    <?php
                                                    if (isset($showroomList) && !empty($showroomList)) {
                                                        foreach ($showroomList as $value) {
                                                            if ($vehicleDetails['branch_id'] == $value['id']) {
                                                                echo isset($value['name']) ? $value['name'] : '';
                                                            }
                                                        }
                                                    }
                                                    ?>
                                                </td>
                                            </tr>
                                            <tr>
                                                <th>Vehicle Type</th>
                                                <td><?php if ($vehicleDetails['vehicle_type'] == 1) {
                                                        echo 'Car';
                                                    } elseif ($vehicleDetails['vehicle_type'] == 2) {
                                                        echo 'Bike';
                                                    } ?></td>
                                            </tr>
                                            <tr>
                                                <th>Make</th>
                                                <td>
                                                    <?php
                                                    if (isset($cmpList) && !empty($cmpList)) {
                                                        foreach ($cmpList as $value) {
                                                            if ($vehicleDetails['cmp_id'] == $value['id']) {
                                                                echo isset($value['cmp_name']) ? $value['cmp_name'] : '';
                                                            }
                                                        }
                                                    }
                                                    ?>
                                                </td>
                                            </tr>
                                            <tr>
                                                <th>Model</th>
                                                <td>
                                                    <?php
                                                    if (isset($cmpModelList) && !empty($cmpModelList)) {
                                                        foreach ($cmpModelList as $value) {
                                                            if ($vehicleDetails['model_id'] == $value['id']) {
                                                                echo isset($value['model_name']) ? $value['model_name'] : '';
                                                            }
                                                        }
                                                    }
                                                    ?>
                                                </td>
                                            </tr>
                                            <tr>
                                                <th>Fuel Type</th>
                                                <td>
                                                    <?php
                                                    if (isset($fuelTypeList) && !empty($fuelTypeList)) {
                                                        foreach ($fuelTypeList as $value) {
                                                            if ($vehicleDetails['fuel_type'] == $value['id']) {
                                                                echo isset($value['name']) ? $value['name'] : '';
                                                            }
                                                        }
                                                    }
                                                    ?>
                                                </td>
                                            </tr>
                                            <tr>
                                                <th>Variant</th>
                                                <td>
                                                    <?php
                                                    if (isset($fuelVariantList) && !empty($fuelVariantList)) {
                                                        foreach ($fuelVariantList as $value) {
                                                            if ($vehicleDetails['variant_id'] == $value['id']) {
                                                                echo isset($value['name']) ? $value['name'] : '';
                                                            }
                                                        }
                                                    }
                                                    ?>
                                                </td>
                                            </tr>
                                            <tr>
                                                <th>Mileage</th>
                                                <td>
                                                    <?php echo isset($vehicleDetails['mileage']) ? $vehicleDetails['mileage'] : ''; ?>
                                                </td>
                                            </tr>
                                            <tr>
                                                <th>Kilometers Driven</th>
                                                <td>
                                                    <?php echo isset($vehicleDetails['kms_driven']) ? $vehicleDetails['kms_driven'] : ''; ?>
                                                </td>
                                            </tr>
                                            <tr>
                                                <th>Owner</th>
                                                <td>
                                                    <?php
                                                    if ($vehicleDetails['owner'] == 1) {
                                                        echo '1st';
                                                    } elseif ($vehicleDetails['owner'] == 2) {
                                                        echo '2nd';
                                                    } elseif ($vehicleDetails['owner'] == 3) {
                                                        echo '3rd';
                                                    } elseif ($vehicleDetails['owner'] == 4) {
                                                        echo '3+ Owner';
                                                    }
                                                    ?>
                                                </td>
                                            </tr>
                                            <tr>
                                                <th>Gear Transmission</th>
                                                <td>
                                                    <?php
                                                    if (isset($transmissionList) && !empty($transmissionList)) {
                                                        foreach ($transmissionList as $value) {
                                                            if ($vehicleDetails['transmission_id'] == $value['id']) {
                                                                echo isset($value['title']) ? $value['title'] : '';
                                                            }
                                                        }
                                                    }
                                                    ?>
                                                </td>
                                            </tr>
                                            <tr>
                                                <th>Colour</th>
                                                <td>
                                                    <?php
                                                    if (isset($colorList) && !empty($colorList)) {
                                                        foreach ($colorList as $value) {
                                                            if ($vehicleDetails['color_id'] == $value['id']) {
                                                                echo isset($value['name']) ? $value['name'] : '';
                                                            }
                                                        }
                                                    }
                                                    ?>
                                                </td>
                                            </tr>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane container fade" id="step2">
                                <div class="row">
                                    <div class="col-md-12 col-lg-12">
                                        <table class="table table-bordered">
                                            <tr>
                                                <th>Make Year</th>
                                                <td>
                                                    <?php
                                                    for ($i = 1975; $i <= date("Y"); $i++) {
                                                        if ($vehicleDetails['manufacture_year'] == $i) {
                                                            echo $i;
                                                        }
                                                    }
                                                    ?>
                                                </td>
                                            </tr>
                                            <tr>
                                                <th>Registration Year</th>
                                                <td>
                                                    <?php
                                                    for ($i = 1975; $i <= date("Y"); $i++) {
                                                        if ($vehicleDetails['registration_year'] == $i) {
                                                            echo $i;
                                                        }
                                                    }
                                                    ?>
                                                </td>
                                            </tr>
                                            <tr>
                                                <th>Registered State</th>
                                                <td>
                                                    <?php
                                                    if (isset($stateList) && !empty($stateList)) {
                                                        foreach ($stateList as $value) {
                                                            if ($vehicleDetails['registered_state_id'] == $value['id']) {
                                                                echo isset($value['name']) ? $value['name'] : '';
                                                            }
                                                        }
                                                    }
                                                    ?>
                                                </td>
                                            </tr>
                                            <tr>
                                                <th>RTO</th>
                                                <td>
                                                    <?php
                                                    if (isset($vehicleRegRtoList) && !empty($vehicleRegRtoList)) {
                                                        foreach ($vehicleRegRtoList as $value) {
                                                            if ($vehicleDetails['rto'] == $value['id']) {
                                                                echo isset($value['rto_state_code']) ? $value['rto_state_code'] : '';
                                                            }
                                                        }
                                                    }
                                                    ?>
                                                </td>
                                            </tr>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane container fade" id="step3">
                                <div class="row">
                                    <div class="col-md-12">
                                        <table class="table table-bordered">
                                            <tr>
                                                <th>Insurance Type</th>
                                                <td>
                                                    <?php
                                                    if ($vehicleDetails['insurance_type'] == 1) {
                                                        echo 'Third Party';
                                                    } elseif ($vehicleDetails['insurance_type'] == 2) {
                                                        echo 'Comprehensive / Zero Debt';
                                                    }
                                                    ?>
                                                </td>
                                            </tr>
                                            <tr>
                                                <th>Insurance Validity</th>
                                                <td>
                                                    <?php echo isset($vehicleDetails['insurance_validity']) ? date('d M Y', strtotime($vehicleDetails['insurance_validity'])) : ''; ?>
                                                </td>
                                            </tr>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane container fade" id="step4">
                                <div class="row">
                                    <div class="col-md-12 col-lg-12">
                                        <table class="table table-bordered">
                                            <tr>
                                                <th>Accidental</th>
                                                <td>
                                                    <?php
                                                    if ($vehicleDetails['accidental_status'] == 1) {
                                                        echo 'Yes';
                                                    } elseif ($vehicleDetails['accidental_status'] == 2) {
                                                        echo 'No';
                                                    }
                                                    ?>
                                                </td>
                                            </tr>
                                            <tr>
                                                <th>Flooded</th>
                                                <td>
                                                    <?php
                                                    if ($vehicleDetails['flooded_status'] == 1) {
                                                        echo 'Yes';
                                                    } elseif ($vehicleDetails['flooded_status'] == 2) {
                                                        echo 'No';
                                                    }
                                                    ?>
                                                </td>
                                            </tr>
                                            <tr>
                                                <th>Last Service Kilometer</th>
                                                <td>
                                                    <?php echo isset($vehicleDetails['last_service_kms']) ? $vehicleDetails['last_service_kms'] : ''; ?>
                                                </td>
                                            </tr>
                                            <tr>
                                                <th>Last Service Date</th>
                                                <td>
                                                    <?php echo isset($vehicleDetails['last_service_date']) ? date('d M Y', strtotime($vehicleDetails['last_service_date'])) : ''; ?>
                                                </td>
                                            </tr>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane container fade" id="step5">
                                <?php if ($vehicleDetails['vehicle_type'] == 1) { ?>
                                    <div class="row" id="car_features_section">
                                        <div class="col-md-12 col-lg-12">
                                            <table class="table table-bordered">
                                                <tr>
                                                    <th>Number of Airbags</th>
                                                    <td>
                                                        <?php
                                                        if ($vehicleDetails['car_no_of_airbags'] == 1) {
                                                            echo 'None';
                                                        } elseif ($vehicleDetails['car_no_of_airbags'] == 2) {
                                                            echo '1 Airbag';
                                                        } elseif ($vehicleDetails['car_no_of_airbags'] == 3) {
                                                            echo '2 Airbag';
                                                        } elseif ($vehicleDetails['car_no_of_airbags'] == 4) {
                                                            echo '3 Airbag';
                                                        } elseif ($vehicleDetails['car_no_of_airbags'] == 5) {
                                                            echo '4 Airbag';
                                                        } elseif ($vehicleDetails['car_no_of_airbags'] == 6) {
                                                            echo '5 Airbag';
                                                        } elseif ($vehicleDetails['car_no_of_airbags'] == 7) {
                                                            echo '6 Airbag';
                                                        } elseif ($vehicleDetails['car_no_of_airbags'] == 8) {
                                                            echo '7 Airbag';
                                                        } elseif ($vehicleDetails['car_no_of_airbags'] == 9) {
                                                            echo '7+ Airbag';
                                                        }
                                                        ?>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <th>Central Locking</th>
                                                    <td>
                                                        <?php
                                                        if ($vehicleDetails['car_central_locking'] == 1) {
                                                            echo 'None';
                                                        } elseif ($vehicleDetails['car_central_locking'] == 2) {
                                                            echo 'Key';
                                                        } elseif ($vehicleDetails['car_central_locking'] == 3) {
                                                            echo 'Keyless';
                                                        }
                                                        ?>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <th>Seat Upholstery</th>
                                                    <td>
                                                        <?php
                                                        if ($vehicleDetails['car_seat_upholstery'] == 1) {
                                                            echo 'Yes';
                                                        } elseif ($vehicleDetails['car_seat_upholstery'] == 2) {
                                                            echo 'No';
                                                        }
                                                        ?>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <th>Sunroof/Moonroof</th>
                                                    <td>
                                                        <?php
                                                        if ($vehicleDetails['car_sunroof'] == 1) {
                                                            echo 'Yes';
                                                        } elseif ($vehicleDetails['car_sunroof'] == 2) {
                                                            echo 'No';
                                                        }
                                                        ?>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <th>Integrated Music System</th>
                                                    <td>
                                                        <?php
                                                        if ($vehicleDetails['car_integrated_music_system'] == 1) {
                                                            echo 'Yes';
                                                        } elseif ($vehicleDetails['car_integrated_music_system'] == 2) {
                                                            echo 'No';
                                                        }
                                                        ?>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <th>Rear AC</th>
                                                    <td>
                                                        <?php
                                                        if ($vehicleDetails['car_rear_ac'] == 1) {
                                                            echo 'Yes';
                                                        } elseif ($vehicleDetails['car_rear_ac'] == 2) {
                                                            echo 'No';
                                                        }
                                                        ?>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <th>Outside Rear View Mirrors</th>
                                                    <td>
                                                        <?php
                                                        if ($vehicleDetails['car_outside_rear_view_mirrors'] == 1) {
                                                            echo 'Yes';
                                                        } elseif ($vehicleDetails['car_outside_rear_view_mirrors'] == 2) {
                                                            echo 'No';
                                                        }
                                                        ?>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <th>Power Windows</th>
                                                    <td>
                                                        <?php
                                                        if ($vehicleDetails['car_power_windows'] == 1) {
                                                            echo 'Yes';
                                                        } elseif ($vehicleDetails['car_power_windows'] == 2) {
                                                            echo 'No';
                                                        }
                                                        ?>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <th>Engine Start-Stop</th>
                                                    <td>
                                                        <?php
                                                        if ($vehicleDetails['car_engine_start_stop'] == 1) {
                                                            echo 'Yes';
                                                        } elseif ($vehicleDetails['car_engine_start_stop'] == 2) {
                                                            echo 'No';
                                                        }
                                                        ?>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <th>Headlamps</th>
                                                    <td>
                                                        <?php
                                                        if ($vehicleDetails['car_headlamps'] == 1) {
                                                            echo 'LED';
                                                        } elseif ($vehicleDetails['car_headlamps'] == 2) {
                                                            echo 'Halogen';
                                                        }
                                                        ?>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <th>Power Steering</th>
                                                    <td>
                                                        <?php
                                                        if ($vehicleDetails['car_power_steering'] == 1) {
                                                            echo 'Yes';
                                                        } elseif ($vehicleDetails['car_power_steering'] == 2) {
                                                            echo 'No';
                                                        }
                                                        ?>
                                                    </td>
                                                </tr>
                                            </table>
                                        </div>
                                    </div>
                                <?php } elseif ($vehicleDetails['vehicle_type'] == 2) { ?>
                                    <div class="row" id="bike_features_section">
                                        <div class="col-md-12 col-lg-12">
                                            <table class="table table-bordered">
                                                <tr>
                                                    <th>Headlight Type</th>
                                                    <td>
                                                        <?php
                                                        if ($vehicleDetails['bike_headlight_type'] == 1) {
                                                            echo 'LED';
                                                        } elseif ($vehicleDetails['bike_headlight_type'] == 2) {
                                                            echo 'Halogen';
                                                        }
                                                        ?>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <th>Odometer</th>
                                                    <td>
                                                        <?php
                                                        if ($vehicleDetails['bike_odometer'] == 1) {
                                                            echo 'None';
                                                        } elseif ($vehicleDetails['bike_odometer'] == 2) {
                                                            echo 'Digital';
                                                        } elseif ($vehicleDetails['bike_odometer'] == 3) {
                                                            echo 'Analogue';
                                                        }
                                                        ?>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <th>DRLs (Day Time Running Lights)</th>
                                                    <td>
                                                        <?php
                                                        if ($vehicleDetails['bike_drl'] == 1) {
                                                            echo 'Yes';
                                                        } elseif ($vehicleDetails['bike_drl'] == 2) {
                                                            echo 'No';
                                                        }
                                                        ?>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <th>Mobile Connectivity</th>
                                                    <td>
                                                        <?php
                                                        if ($vehicleDetails['bike_mobile_connectivity'] == 1) {
                                                            echo 'Yes';
                                                        } elseif ($vehicleDetails['bike_mobile_connectivity'] == 2) {
                                                            echo 'No';
                                                        }
                                                        ?>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <th>GPS Navigation</th>
                                                    <td>
                                                        <?php
                                                        if ($vehicleDetails['bike_gps_navigation'] == 1) {
                                                            echo 'Yes';
                                                        } elseif ($vehicleDetails['bike_gps_navigation'] == 2) {
                                                            echo 'No';
                                                        }
                                                        ?>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <th>GPS Navigation</th>
                                                    <td>
                                                        <?php
                                                        if ($vehicleDetails['bike_usb_charging_port'] == 1) {
                                                            echo 'Yes';
                                                        } elseif ($vehicleDetails['bike_usb_charging_port'] == 2) {
                                                            echo 'No';
                                                        }
                                                        ?>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <th>Low Battery Indicator</th>
                                                    <td>
                                                        <?php
                                                        if ($vehicleDetails['bike_low_battery_indicator'] == 1) {
                                                            echo 'Yes';
                                                        } elseif ($vehicleDetails['bike_low_battery_indicator'] == 2) {
                                                            echo 'No';
                                                        }
                                                        ?>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <th>Under Seat Storage</th>
                                                    <td>
                                                        <?php
                                                        if ($vehicleDetails['bike_under_seat_storage'] == 1) {
                                                            echo 'Yes';
                                                        } elseif ($vehicleDetails['bike_under_seat_storage'] == 2) {
                                                            echo 'No';
                                                        }
                                                        ?>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <th>Speedometer</th>
                                                    <td>
                                                        <?php
                                                        if ($vehicleDetails['bike_speedometer'] == 1) {
                                                            echo 'Digital';
                                                        } elseif ($vehicleDetails['bike_speedometer'] == 2) {
                                                            echo 'Analogue';
                                                        }
                                                        ?>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <th>Stand Alarm</th>
                                                    <td>
                                                        <?php
                                                        if ($vehicleDetails['bike_stand_alarm'] == 1) {
                                                            echo 'Digital';
                                                        } elseif ($vehicleDetails['bike_stand_alarm'] == 2) {
                                                            echo 'Analogue';
                                                        }
                                                        ?>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <th>Low Fuel Indicator</th>
                                                    <td>
                                                        <?php
                                                        if ($vehicleDetails['bike_low_fuel_indicator'] == 1) {
                                                            echo 'Yes';
                                                        } elseif ($vehicleDetails['bike_low_fuel_indicator'] == 2) {
                                                            echo 'No';
                                                        }
                                                        ?>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <th>Low Oil Indicator</th>
                                                    <td>
                                                        <?php
                                                        if ($vehicleDetails['bike_low_oil_indicator'] == 1) {
                                                            echo 'Yes';
                                                        } elseif ($vehicleDetails['bike_low_oil_indicator'] == 2) {
                                                            echo 'No';
                                                        }
                                                        ?>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <th>Start Type</th>
                                                    <td>
                                                        <?php
                                                        if ($vehicleDetails['bike_start_type'] == 1) {
                                                            echo 'Electric Start';
                                                        } elseif ($vehicleDetails['bike_start_type'] == 2) {
                                                            echo 'Kick Start';
                                                        } elseif ($vehicleDetails['bike_start_type'] == 3) {
                                                            echo 'Electric + KickStart';
                                                        }
                                                        ?>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <th>Kill Switch</th>
                                                    <td>
                                                        <?php
                                                        if ($vehicleDetails['bike_kill_switch'] == 1) {
                                                            echo 'Yes';
                                                        } elseif ($vehicleDetails['bike_kill_switch'] == 2) {
                                                            echo 'No';
                                                        }
                                                        ?>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <th>Break Light</th>
                                                    <td>
                                                        <?php
                                                        if ($vehicleDetails['bike_break_light'] == 1) {
                                                            echo 'Halogen';
                                                        } elseif ($vehicleDetails['bike_break_light'] == 2) {
                                                            echo 'Analogue';
                                                        }
                                                        ?>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <th>Turn Signal Indicator</th>
                                                    <td>
                                                        <?php
                                                        if ($vehicleDetails['bike_turn_signal_indicator'] == 1) {
                                                            echo 'Halogen Bulb';
                                                        } elseif ($vehicleDetails['bike_turn_signal_indicator'] == 2) {
                                                            echo 'LED';
                                                        }
                                                        ?>
                                                    </td>
                                                </tr>
                                            </table>
                                        </div>
                                    </div>
                                <?php } ?>
                            </div>
                            <div class="tab-pane container fade" id="step6">
                                <div class="row">
                                    <div class="col-md-6 col-lg-3">
                                        <table class="table table-bordered">
                                            <tr>
                                                <th>Regular Price</th>
                                                <td><?php echo isset($vehicleDetails['regular_price']) ? $vehicleDetails['regular_price'] : ''; ?></td>
                                            </tr>
                                            <tr>
                                                <th>Selling Price</th>
                                                <td><?php echo isset($vehicleDetails['selling_price']) ? $vehicleDetails['selling_price'] : ''; ?></td>
                                            </tr>
                                            <tr>
                                                <th>Pricing Type</th>
                                                <td>
                                                    <?php
                                                    if ($vehicleDetails['pricing_type'] == 1) {
                                                        echo 'Fixed Price';
                                                    } elseif ($vehicleDetails['pricing_type'] == 2) {
                                                        echo 'Negotiable';
                                                    }
                                                    ?>
                                                </td>
                                            </tr>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

            <div class="pd-20 bg-white border-radius-4 box-shadow mb-30">
                <input type="hidden" name="vehicleId" id="vehicleId" class="vehicleId" value="<?php echo isset($vehicleDetails['id']) ? $vehicleDetails['id'] : ''; ?>">
                <h5 class="text-blue mb-3">Vehicle Images</h5>
                <div class="row">
                    <div class="col-lg-3 col-md-6 col-sm-12 mb-30">
                        <label>Thumbnail Image</label><br>
                        <div class="card card-box">
                            <img class="card-img" src="<?php echo isset($vehicleDetails['thumbnail_url']) ? WHEELPACT_VEHICLE_UPLOAD_IMG_PATH . 'vehicle_thumbnails/' . $vehicleDetails['thumbnail_url'] : WHEELPACT_VEHICLE_UPLOAD_IMG_PATH . 'default-img.png'; ?>" class="img img-responsive vehicleImg" alt="<?php echo $vehicleDetails['cmp_name'] . ' ' . $vehicleDetails['cmp_model_name']; ?>">
                            <!-- <div class="card-body"><p class="card-text">Thumbnail Image</p></div> -->
                        </div>
                    </div>
                </div>
                
                <?php if ($vehicleDetails['vehicle_type'] == 1) { ?>
                    <?php echo view('dealer/vehicles/form_includes/cars/view-car-img-form'); ?>
                <?php } elseif ($vehicleDetails['vehicle_type'] == 2) { ?>
                    <?php echo view('dealer/vehicles/form_includes/bikes/view-bike-img-form'); ?>
                <?php } ?>
            </div>
        </div>
        <?php echo view('dealer/includes/_footer'); ?>
    </div>
</div>