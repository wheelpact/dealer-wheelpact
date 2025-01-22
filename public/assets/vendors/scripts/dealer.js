$(document).ready(function () {

	/* loader start */
	var width = 100,
		perfEntries = performance.getEntriesByType("navigation")[0], // Get navigation timing entry
		EstimatedTime = -(perfEntries.loadEventEnd - perfEntries.startTime),
		time = parseInt((EstimatedTime / 1000) % 60) * 100;

	// Percentage Increment Animation
	var PercentageID = $("#percent1"),
		start = 0,
		end = 100,
		durataion = time;
	animateValue(PercentageID, start, end, durataion);

	function animateValue(id, start, end, duration) {

		var range = end - start,
			current = start,
			increment = end > start ? 1 : -1,
			stepTime = Math.abs(Math.floor(duration / range)),
			obj = $(id);

		var timer = setInterval(function () {
			current += increment;
			$(obj).text(current + "%");
			$("#bar1").css('width', current + "%");
			//obj.innerHTML = current;
			if (current == end) {
				clearInterval(timer);
			}
		}, stepTime);
	}

	// Fading Out Loadbar on Finised
	setTimeout(function () {
		$('.pre-loader').fadeOut(300);
	}, time);
	/* loader end*/

	var limit = 3;
	var start = 0;
	var action = 'inactive';

	/* // Login Page Toggle password visibility */
	$(".togglePassword").click(function () {
		const passwordField = $("#password");

		const type = passwordField.attr("type");
		passwordField.attr("type", type === "password" ? "text" : "password");

		$(this).toggleClass("dw-hide");
	});

	$(".confTogglePassword").click(function () {
		const confTogglePassword = $("#confirm_password");

		const typeConf = confTogglePassword.attr("type");
		confTogglePassword.attr("type", typeConf === "password" ? "text" : "password");

		$(this).toggleClass("dw-hide");
	});

	/* number feild validation */
	$('.numbersOnlyCheck').on('keypress', function (event) {
		// Get the pressed key code
		var keyCode = event.which;
		// Check if the pressed key is a number (0-9)
		if (keyCode < 48 || keyCode > 57) {
			// Prevent input if the key is not a number
			event.preventDefault();
		}
	});

	/*  // Function to copy address when checkbox is checked */
	$('#copyAddress').change(function () {
		if (this.checked) {
			// Copy residential address to permanent address
			$('.addr_permanent').val($('.addr_residential').val());
			//$('.addr_permanent').prop('disabled', true);
		} else {
			// Clear permanent address when checkbox is unchecked
			$('.addr_permanent').val('');
			//$('.addr_permanent').prop('disabled', false);
		}
	});

	$('.addr_residential').keypress(function () {
		$('#copyAddress').prop('checked', false);
		$('.addr_permanent').val('');
		$('.addr_permanent').prop('disabled', false);
	});

	/* //reset password - dealer */
	$('.resetPasswordForm').submit(function (event) {
		event.preventDefault();

		var password = $('#password').val();
		var confirmPassword = $('#confirm_password').val();

		var passwordValidationMessage = validatePassword(password, confirmPassword);

		if (passwordValidationMessage) {
			showWarningToast(passwordValidationMessage);
			$('#password').focus();
			return false;
		}

		/* // If validation passes, submit the form to the server */
		var action_page = $(this).attr('action');

		$.ajax({
			url: action_page,
			type: "POST",
			data: new FormData(this),
			processData: false,
			contentType: false,
			dataType: "json",
			success: function (response) {
				if (response.status == 'success') {
					showSuccessAlert(response.message);
					$('#password').val('');
					$('#confirm_password').val('');
				} else {
					showErrorAlert(response.message);
					$('#password').focus();
				}
			},
			error: function (xhr, status, error) {
				console.log("An error occurred:", error);
			}
		});
		setTimeout(function () {
			window.location.href = window.location.origin;
		}, 9000);
	});

	$('#reset_password_link_dealer').submit(function (event) {
		event.preventDefault();

		var email = $('#email').val();

		$.ajax({
			type: 'POST',
			url: '/dealer/send-reset-password-link',
			data: { email: email },
			dataType: 'json',
			success: function (response) {
				if (response.status == "success") {
					showSuccessAlert(response.message);
					setTimeout(function () {
						window.location.href = window.location.origin;
					}, 9000);
				} else {
					showWarningToast(response.message);
					$('#email').focus();
				}
			}
		});
	});

	/* update profile details dealer */
	$("#update_profile_details").submit(function (event) {
		event.preventDefault();

		var action_page = $("#update_profile_details").attr('action');
		var formData = new FormData($('#update_profile_details')[0]);
		$.ajax({
			url: action_page,
			type: "POST",
			data: formData,
			dataType: "json",
			processData: false,  // Important: Don't process the data
			contentType: false,  // Important: Don't set contentType
			success: function (response) {
				if (response.status == 'success') {
					showSuccessAlert(response.message);
					setTimeout(function () {
						window.location.href = base_url + response.redirect;
					}, 6000);
				} else {
					showWarningToast(response.message);
					$('[name="' + response.field + '"]').focus();
					$('#' + response.field).focus();
				}
			},
			error: function (xhr, status, error) {
				console.log("An error occurred:", error);
			}
		});

	});

	/* change/update password form profile page dealer */
	$("#changePasswordForm").submit(function (event) {
		event.preventDefault();

		var old_password = $('#old_password').val();
		var password = $('#new_pwd').val();
		var confirmPassword = $('#conf_new_pwd').val();

		/* // Check if old password is the same as the new password */
		if (old_password === password) {
			showWarningToast('New password cannot be the same as the old password.');
			return false;
		}

		var passwordValidationMessage = validatePassword(password, confirmPassword);

		if (passwordValidationMessage) {
			showWarningToast(passwordValidationMessage);
			return false;
		}

		var action_page = $("#changePasswordForm").attr('action');
		var formData = new FormData($('#changePasswordForm')[0]);

		$.ajax({
			url: action_page,
			type: "POST",
			data: formData,
			dataType: "json",
			processData: false,
			contentType: false,
			beforeSend: function () {
				showOverlay();
			},
			success: function (response) {
				if (response.status == 'success') {
					showSuccessAlert(response.message);
					$('#changePasswordForm')[0].reset();
				} else {
					showWarningToast(response.message);
					$('[name="' + response.field + '"]').focus();
				}
			},
			error: function (xhr, status, error) {
				console.log("An error occurred:", error);
			}
		});

	});

	/* add vechicle event start*/
	$(".custom-select.onsale_status").change(function () {
		var onsaleStatusSelected = $(this).val();
		if (onsaleStatusSelected == 1) {
			$(".onsale_percentage_div").html('<div class="form-group">' +
				'<label>On Sale Percentage<span class="required">*</span></label>' +
				'<input type="text" maxlength="2" class="form-control formInput numbersOnlyCheck" name="onsale_percentage" id="onsale_percentage" placeholder="%">' +
				'</div>');
			$(".onsale_percentage_div").addClass("col-md-6 col-lg-3");
		} else if (onsaleStatusSelected == 2) {
			$(".onsale_percentage_div").html('');
			$(".onsale_percentage_div").removeClass("col-md-6 col-lg-3");
		}
	});

	$("#step6").on('keyup', '#onsale_percentage', function () {
		var regular_price = $("#regular_price").val();
		if (regular_price) {
			var onsaleStatusSelected = $("#onsale_status").val();
			if (onsaleStatusSelected == 1) {

				var onsale_percentage = $("#onsale_percentage").val();
				var discountedOffPriceInt = parseFloat(regular_price) * parseFloat(onsale_percentage) / 100;
				var discountedPriceInt = parseFloat(regular_price) - parseFloat(discountedOffPriceInt);
				$("#selling_price").val(discountedPriceInt);
			} else if (onsaleStatusSelected == 2) {
				$("#selling_price").val(regular_price);
			}
		}

	});

	$("#regular_price").keyup(function () {
		var regular_price = $(this).val();
		var onsaleStatusSelected = $("#onsale_status").val();
		if (onsaleStatusSelected == 1) {
			var onsale_percentage = $("#onsale_percentage").val();
			var discountedOffPriceInt = parseFloat(regular_price) * parseFloat(onsale_percentage) / 100;
			var discountedPriceInt = parseFloat(regular_price) - parseFloat(discountedOffPriceInt);
			$("#selling_price").val(discountedPriceInt);
		} else if (onsaleStatusSelected == 2) {
			$("#selling_price").val(regular_price);
		} else {
			$("#selling_price").val(regular_price);
		}
	});

	$("#emi_option").change(function () {
		var emiOptionSelected = $(this).val();
		if (emiOptionSelected == 1) {
			$("#emi_options_div").html('<div class="row"><div class="col-md-6">' +
				'<div class="form-group">' +
				'<label>Average Interest Rate<span class="required">*</span></label>' +
				'<input type="number" placeholder="%" step="0.01" min="0" max="100" maxlength="4" class="form-control formInput" name="avg_interest_rate" id="avg_interest_rate">' +
				'</div>' +
				'</div>' +
				'<div class="col-md-6">' +
				'<div class="form-group">' +
				'<label>Tenure in Months<span class="required">*</span></label>' +
				'<input type="number" placeholder="0" min="0" maxlength="2" class="form-control formInput" name="tenure_months" id="tenure_months">' +
				'</div>' +
				'</div></div>');
			//$("#emi_options_div").addClass("row");
		} else if (emiOptionSelected == 2) {
			$("#emi_options_div").html('');
			//$("#emi_options_div").removeClass("row");
		}
	});

	/* add vechicle page event ends*/

	/* add vechicle type section end */
	$('#apply_list_vehicle_filter').click(function () {
		// Change the URL
		var vehicleTypeId = $('.custom-select.vehicle-type').val();
		var vehicleBrandId = $('.custom-select.brand').val();
		var vehicleModelId = $('.custom-select.model').val();
		var vehicleVariantId = $('.custom-select.variant').val();

		$('#actionurl').attr('value', base_url + 'dealer/getbranchvehicles/' + vehicleTypeId + '/' + vehicleBrandId + '/' + vehicleModelId + '/' + vehicleVariantId);

		var actionurl = $("#actionurl").val();

		$('#load_data').html("");
		load_data(0, 3, actionurl);
	});

	/* rto data load on state select */
	$('.custom-select.state-rto').change(function () {
		var stateId = $(this).val();
		$.ajax({
			url: base_url + 'dealer/load_staterto',
			type: 'POST',
			data: {
				stateId: stateId
			},
			success: function (response) {
				$('.custom-select.rto-code').html(response);
			}
		});
	});

	/* list vehicle page script start */
	$('.custom-select.vehicle-type').change(function () {
		var vehicleType = $(this).val();

		$('.custom-select.brand').prop('selectedIndex', 0);
		$('.custom-select.model').prop('selectedIndex', 0);
		$('.custom-select.variant').prop('selectedIndex', 0);
		$.ajax({
			url: base_url + 'dealer/load_brands',
			type: 'POST',
			data: {
				vehicle_type: vehicleType
			},
			success: function (response) {
				$('.custom-select.brand').html(response);
			}
		});

		/* display the body type */
		/* // Hide all options */
		$('.custom-select.body_type option').hide();
		/* // Show options with matching data-vehicle-type value */
		$('.custom-select.body_type option[data-vehicle-type="' + vehicleType + '"]').show();

		/* steps hide & show based on selected vehicle type */
		$.ajax({
			url: base_url + 'dealer/load_vehicle_step_fields',
			type: 'POST',
			data: {
				vehicle_type: vehicleType
			},
			success: function (response) {
				$("#vehicleFeaturesWrapper").html(response.vehicle_form_feilds);
				$("#vehicleExteriorImagesWrapper").html(response.vehicle_image_fields);
			}
		});
		/* Toggle display of tabs based on vehicle type seclection */
		if (vehicleType == 1) {
			/* cars */
			$('.nav-link[href="#exterior"]').tab('show');
			$('*[href="#interior"]').show();
			$('*[href="#others"]').show();
		} else if (vehicleType == 2) {
			/* bikes */
			$('.nav-link[href="#exterior"]').tab('show');
			$('*[href="#interior"]').hide();
			$('*[href="#others"]').hide();
		}
	});

	$('.custom-select.brand').change(function () {
		var brandId = $(this).val();
		var vehicleType = $(".custom-select.vehicle-type").val();

		$('.custom-select.model').prop('selectedIndex', 0);
		$('.custom-select.variant').prop('selectedIndex', 0);
		// Make an AJAX request to load models based on selected brand
		$.ajax({
			url: base_url + 'dealer/load_models',
			type: 'POST',
			data: {
				brand_id: brandId,
				vehicle_type: vehicleType
			},
			success: function (response) {
				// Replace the content of models dropdown with the new options
				$('.custom-select.model').html(response);
			}
		});
	});

	$('.custom-select.model').change(function () {
		var modelId = $(this).val();

		$('.custom-select.variant').prop('selectedIndex', 0);
		// Make an AJAX request to load variants based on selected model
		$.ajax({
			url: base_url + 'dealer/load_variants',
			type: 'POST',
			data: {
				model_id: modelId
			},
			success: function (response) {
				// Replace the content of variants dropdown with the new options
				$('.custom-select.variant').html(response);
			}
		});
	});

	$(document).on('click', '.delete-vehicle', function (e) {
		e.preventDefault();
		var vehicleId = $(this).data('vehicle-id');
		Swal.fire({
			title: 'Are you sure?',
			text: 'You won\'t be able to revert this!',
			icon: 'warning',
			showCancelButton: true,
			confirmButtonColor: '#3085d6',
			cancelButtonColor: '#d33',
			confirmButtonText: 'Yes, delete it!'
		}).then((result) => {
			if (result.isConfirmed) {
				$.ajax({
					url: base_url + 'dealer/deleteVehicle/' + vehicleId,
					type: 'POST',
					dataType: 'json',
					success: function (response) {
						if (response.status === 'success') {
							showSuccessAlert('Vehicle deleted successfully');
							$('.vehicle-card-' + vehicleId).remove();
						} else {
							showErrorAlert('Failed to delete vehicle');
						}
					},
					error: function () {
						showErrorAlert('Error in Ajax request');
					}
				});
			}
		});
	});
	/* list vehicle page script end */

	/* list braches page script start */
	$('.custom-select.country').change(function () {
		var selectedCountry = $(this).val();

		$('.custom-select.state').prop('selectedIndex', 0);
		$('.custom-select.city').prop('selectedIndex', 0);
		$('.custom-select.branchType').prop('selectedIndex', 1);

		$.ajax({
			url: base_url + 'dealer/load_states',
			type: 'POST',
			data: {
				country_id: selectedCountry
			},
			success: function (response) {
				$('.custom-select.state').html(response);
			}
		});
	});

	$('.custom-select.state').change(function () {
		var selectedState = $(this).val();

		$('.custom-select.city').prop('selectedIndex', 0);
		$('.custom-select.branchType').prop('selectedIndex', 1);

		$.ajax({
			url: base_url + 'dealer/load_cities',
			type: 'POST',
			data: {
				state_id: selectedState
			},
			success: function (response) {
				$('.custom-select.city').html(response);
			}
		});
	});

	$(document).on('click', '.delete-branch', function (e) {
		e.preventDefault();
		var branchId = $(this).data('branch-id');
		Swal.fire({
			title: 'Are you sure?',
			text: 'You won\'t be able to revert this!',
			icon: 'warning',
			showCancelButton: true,
			confirmButtonColor: '#3085d6',
			cancelButtonColor: '#d33',
			confirmButtonText: 'Yes, delete it!'
		}).then((result) => {
			if (result.isConfirmed) {
				$.ajax({
					url: base_url + 'dealer/deleteBranch/' + branchId,
					type: 'POST',
					dataType: 'json',
					success: function (response) {
						if (response.status === 'success') {
							showSuccessAlert('Branch Removed successfully');
						} else {
							$('.branch-card-' + branchId).remove();
							showErrorAlert('Failed to delete vehicle');
						}
					},
					error: function () {
						showErrorAlert('Error in Ajax request');
					}
				});
			}
		});
	});

	$(document).on('click', '.toggle-branch-status', function (e) {
		e.preventDefault(); // Prevent default link behavior

		const branchId = $(this).data('branch-id');
		const status = $(this).data('status');

		Swal.fire({
			title: 'Are you sure?',
			text: 'Do you want to enable/disable the branch?',
			icon: 'warning',
			showCancelButton: true,
			confirmButtonText: 'Yes, do it!',
			cancelButtonText: 'No, cancel!',
			reverseButtons: true
		}).then((result) => {
			if (result.isConfirmed) {
				// Send AJAX request to update branch status
				$.ajax({
					url: base_url + 'dealer/enable_disable_branch',
					type: 'POST',
					data: {
						branch_id: branchId,
						status: status
					},
					success: function (response) {
						if (response.status === 'success') {
							showSuccessAlert(response.message);

							// Update the specific card content dynamically
							const card = $('.branch-card-' + branchId);
							if (card.length) {
								card.find('.branch-status').text(status == 2 ? 'Disabled' : 'Enabled');
								// Optionally update the button's status
								card.find('.toggle-branch-status').data('status', status == 2 ? 1 : 2).text(status == 2 ? 'Enable' : 'Disable');
							}
						} else {
							showErrorAlert(response.message || 'An error occurred. Please try again.');
						}
					},
					error: function () {
						showErrorAlert('Failed to update the branch status. Please try again.');
					},
				});
			} else {
				// If canceled, show a message or do nothing
				Swal.fire(
					'Cancelled',
					'No changes were made.',
					'info'
				);
			}
		});
	});

	$('#apply_list_branch_filter').click(function () {

		var countryId = $('.custom-select.country').val();
		var stateBrandId = $('.custom-select.state').val();
		var citylId = $('.custom-select.city').val();
		var branchType = $('.custom-select.branchType').val();

		$('#actionurl').attr('value', base_url + 'dealer/getdealerbranches/' + countryId + '/' + stateBrandId + '/' + citylId + '/' + branchType);

		var actionurl = $("#actionurl").val();
		$('#load_data').html("");
		load_data(0, 3, actionurl);
	});

	$('#save_branch_form').submit(function (e) {
		e.preventDefault();

		var action_page = $(this).attr('action');

		$.ajax({
			url: action_page,
			type: "POST",
			data: new FormData(this),
			processData: false,
			contentType: false,
			dataType: "json",
			beforeSend: function () {
				showOverlay();
			},
			success: function (response) {
				if (response.status === "success") {
					showSuccessAlert(response.message);
					setTimeout(function () {
						window.location.href = base_url + response.redirect;
					}, 4000);
				} else {
					showErrorAlert(response.message);
					$('[name="' + response.field + '"]').focus();
				}
			},
			error: function (xhr, status, error) {
				console.log("An error occurred:", error);
			}
		});
	});

	$("#edit_update_branch_details").submit(function (event) {
		event.preventDefault();

		var action_page = $(this).attr("action");
		var formData = new FormData(this);

		$.ajax({
			url: action_page,
			type: "POST",
			data: formData,
			processData: false,
			contentType: false,
			success: function (response) {
				if (response.status === "success") {
					showSuccessAlert(response.message);
					setTimeout(function () {
						window.location.href = base_url + response.redirect;
					}, 4000);
				} else {
					showErrorAlert(response.message);
				}
			},
			error: function (xhr, status, error) {
				console.log("An error occurred:", error);
			},
		});

	});

	var maxFields = 10;
	var currentFields = 1;
	/* services + */
	$('#extend-branch-service').click(function (e) {
		e.preventDefault()
		if (currentFields < maxFields) {
			$('#extend-service-field').append(
				'<div class="form-group"><div class="input-group bootstrap-touchspin bootstrap-touchspin-injected"><input type="text" placeholder="Enter Branch Service" name="branchServices[]" class="form-control branchServices" required><span class="input-group-btn input-group-append"><button class="btn btn-primary bootstrap-touchspin-up remove-branch-service-field" type="button">-</button></span></div></div>'
			)
			currentFields++;
		} else {
			showWarningToast("You've reached the maximum number of fields.");
		}
	})

	$('#extend-service-field').on('click', '.remove-branch-service-field', function (e) {
		e.preventDefault()
		$(this).closest('.form-group').remove();
		currentFields--;
	})
	/* services - */

	/* deliverable images + */
	$('#extend-deliverable-img').click(function (e) {
		e.preventDefault();
		if (currentFields < maxFields) {
			$('#extend-deliverable-img-field').append(
				'<div class="form-group"><div class="input-group bootstrap-touchspin bootstrap-touchspin-injected"><input type="file" placeholder="Choose image" name="deliverableImg[]" class="form-control deliverableImg" required accept="image/png, image/jpeg"><span class="input-group-btn input-group-append"><button class="btn btn-primary bootstrap-touchspin-up remove-deliverable-img-field" type="button">-</button></span></div></div>'
			);
			currentFields++;
		} else {
			showWarningToast("You've reached the maximum number of fields.");
		}
	});

	$('#extend-deliverable-img-field').on('click', '.remove-deliverable-img-field', function (e) {
		e.preventDefault();
		$(this).closest('.form-group').remove();
		currentFields--;
	});
	/* deliverable images + */

	/* list braches page script end */

	/* // multistep form validation start */
	var currentStep = 0;
	var steps = $(".step");
	var stepIndicators = $(".step-indicators .indicator");

	function showStep(step) {
		steps.hide();
		steps.eq(step).show();
	}

	function updateButtons() {
		if (currentStep === 0) {
			$(".prev").hide();
		} else {
			$(".prev").show();
		}

		if (currentStep === steps.length - 1) {
			$(".next").hide();
			$(".submit").show();
		} else {
			$(".next").show();
			$(".submit").hide();
		}
	}

	function updateStepIndicators(step) {
		stepIndicators.removeClass("active");
		stepIndicators.eq(step).addClass("active");
	}

	function validateStep(step) {
		var fields = steps.eq(step).find('.formInput');
		var emptyFields = [];
		var allFieldsValid = fields.toArray().every(function (field) {
			if (field.value.trim() === '') {
				emptyFields.push(field.id);
				return false; /*// Field is empty, consider it as invalid */

			} else {
				return true;
			}
		});
		if (!allFieldsValid) {
			var emptyFieldsMessage = "";
			emptyFields.forEach(function (fieldId) {
				var msg = form_validation_messages(fieldId);
				emptyFieldsMessage += "- " + msg + "<br/>";
			});
			showWarningToast(emptyFieldsMessage);
		}
		return allFieldsValid;
	}

	showStep(currentStep);
	updateButtons();
	updateStepIndicators(currentStep);

	$(".next").click(function () {
		var formId = $(this).closest('form').attr('id');
		if (formId == 'update_vehicle_form') {
			currentStep++;
			showStep(currentStep);
			updateButtons();
			updateStepIndicators(currentStep);
			/*// Scroll to the target div */
			$('html, body').animate({ scrollTop: $('.stepsDivForm').offset().top }, 1000);
		} else {
			if (validateStep(currentStep)) {
				currentStep++;
				showStep(currentStep);
				updateButtons();
				updateStepIndicators(currentStep);
				/*// Scroll to the target div */
				$('html, body').animate({ scrollTop: $('.stepsDivForm').offset().top }, 1000);
			}
		}
	});

	$(".prev").click(function () {
		currentStep--;
		showStep(currentStep);
		updateButtons();
		updateStepIndicators(currentStep);
		/*// Scroll to the target div */
		$('html, body').animate({ scrollTop: $('.stepsDivForm').offset().top }, 1000);
	});
	/* // multistep form validation end */

	/* // multistep form submit */
	$("#save_new_vehicle").submit(function (event) {
		event.preventDefault();
		if (!validateStep(currentStep)) {
			return false;
		} else {
			var action_page = $("#save_new_vehicle").attr('action');
			$.ajax({
				url: action_page,
				type: "POST",
				data: $(this).serialize(),
				dataType: "json",
				beforeSend: function () {
					showOverlay();
				},
				success: function (response) {
					if (response.success) {
						showSuccessAlert(response.message + "\n" + "Please Upload Vehicle Images Now.");
						$("#vehicleBasicInformationMultipartFormWrapper").empty();
						$("#vehicleBasicInformationMultipartFormWrapper").html('<div class="alert alert-success alert-dismissible fade show" role="alert">' +
							'<p>Vehicle Basic Information Added Successfully, Please Upload Vehicle Images Now.</p>' +
							'</div>');

						$(".vehicleId").val(response.vehicleId);
						$(".vehicle_type").val(response.vehicle_type);
					} else {
						// Validation failed, handle errors
						showErrorAlert(response.message);
					}
				},
				error: function (xhr, status, error) {
					console.log("An error occurred:", error);
				}
			});
		}
	});

	$("#update_vehicle_form").submit(function (event) {
		event.preventDefault();

		var action_page = $("#update_vehicle_form").attr('action');
		event.preventDefault();
		if (!validateStep(currentStep)) {
			return false;
		}
		$.ajax({
			url: action_page,
			type: "POST",
			data: $(this).serialize(),
			dataType: "json",
			success: function (response) {
				if (response.success) {
					showSuccessAlert('Vehicle Basic Information Updated Successfully');
					setTimeout(function () {
						location.reload();
					}, 3000);
				} else {
					showErrorAlert(response.errors);
				}
			},
			error: function (xhr, status, error) {
				console.log("An error occurred:", error);
			}
		});

	});
	/* edit vehicle steps end */

	/* // upload vehicle thumbnail image */
	$('#uploadThumbnail').click(function () {
		var vehicleId = $("#vehicleId").val();
		if (vehicleId === '') {
			showWarningToast("Kindly Add Vehicle Information, Then add Thumbnail.");
			return false;
		}
		var fileInput = $('#thumbnailImage')[0];

		if (fileInput.files.length > 0) {
			var formData = new FormData();
			formData.append('thumbnailImage', fileInput.files[0]);
			formData.append('vehicleId', $(".vehicleId").val());

			let base_url = window.location.origin;

			$.ajax({
				type: 'POST',
				url: base_url + '/dealer/upload-thumbnail',
				data: formData,
				processData: false,
				contentType: false,
				beforeSend: function () {
					showOverlay();
				},
				success: function (response) {
					if (response.status === 'success') {
						showSuccessAlert(response.message);
						$('.replaceThumbnailImg').attr('src', response.thumbnail_url);
					} else {
						showErrorAlert(response.message);
					}
				}
			});
			$('#thumbnailImage').val("");
		} else {
			showErrorAlert("Choose Vehicle Thumbnail.");
			return false;
		}
	});

	/* // upload vehicle Exterior images */
	$("#upload_exterior_main_vehicle_images_form").submit(function (event) {
		event.preventDefault();
		var vehicleId = $(".vehicleId").val();
		if (vehicleId === '') {
			showWarningToast("Kinldy Add Vehicle Information, Then Vehicle Images.");
			/*// Scroll to the target div */
			$('html, body').animate({ scrollTop: $('.stepsDivForm').offset().top }, 1000);
			return false;
		} else {
			if (!validateVehicleImagesFields("upload_exterior_main_vehicle_images_form")) {
				return false;
			} else {
				var formData = new FormData(this);
				var action_page = $("#upload_exterior_main_vehicle_images_form").attr('action');
				$.ajax({
					url: action_page,
					type: 'POST',
					data: formData,
					processData: false,
					contentType: false,
					xhr: function () {
						var xhr = new window.XMLHttpRequest();
						xhr.upload.addEventListener('progress', function (evt) {
							if (evt.lengthComputable) {
								var percentComplete = (evt.loaded / evt.total) * 100;
								$(".vehicleUploadProgressBar").removeClass("d-none");
								$(".vehicleUploadProgressBarData").css('width', percentComplete + '%').attr('aria-valuenow', percentComplete).text(percentComplete.toFixed(0) + '%');
							}
						}, false);
						return xhr;
					},
					success: function (response) {
						if (response.status == 'success') {
							showSuccessAlert(response.message);
						} else {
							showErrorAlert(response.message);
						}

						setTimeout(function () {
							$(".vehicleUploadProgressBar").addClass("d-none");
						}, 3000);
					},
					error: function (xhr, status, error) {
						console.log("An error occurred:", error);
					}
				});
			}
		}
	});

	/* // upload vehicle Interior Photo */
	$("#upload_interior_vehicle_images_form").submit(function (event) {
		event.preventDefault();
		var vehicleId = $(".vehicleId").val();
		if (vehicleId === '') {
			showErrorAlert("First Add Vehicle Information and Then Add Vehicle Images.");
			return false;
		} else {
			if (!validateVehicleImagesFields("upload_interior_vehicle_images_form")) {
				return false;
			} else {
				var formData = new FormData(this);
				var action_page = $("#upload_interior_vehicle_images_form").attr('action');
				$.ajax({
					url: action_page,
					type: 'POST',
					data: formData,
					processData: false,
					contentType: false,
					xhr: function () {
						var xhr = new window.XMLHttpRequest();

						xhr.upload.addEventListener('progress', function (evt) {
							if (evt.lengthComputable) {
								var percentComplete = (evt.loaded / evt.total) * 100;
								console.log(percentComplete + '%');
							}
						}, false);

						return xhr;
					},
					success: function (response) {
						if (response.status == 'success') {
							showSuccessAlert(response.message);
						} else {
							showErrorAlert(response.message);
						}
					},
					error: function (xhr, status, error) {
						console.log("An error occurred:", error);
					}
				});
			}
		}
	});

	/* upload vehicle Other Photo */
	$("#upload_others_vehicle_images_form").submit(function (event) {
		event.preventDefault();
		var vehicleId = $(".vehicleId").val();
		if (vehicleId === '') {
			showErrorAlert("First Add Vehicle Information and Then Add Vehicle Images.");
			return false;
		} else {
			if (!validateVehicleImagesFields("upload_others_vehicle_images_form")) {
				return false;
			} else {
				var formData = new FormData(this);
				var action_page = $("#upload_others_vehicle_images_form").attr('action');
				$.ajax({
					url: action_page,
					type: 'POST',
					data: formData,
					processData: false,
					contentType: false,
					xhr: function () {
						var xhr = new window.XMLHttpRequest();
						xhr.upload.addEventListener('progress', function (evt) {
							if (evt.lengthComputable) {
								var percentComplete = (evt.loaded / evt.total) * 100;
								console.log(percentComplete + '%');
							}
						}, false);

						return xhr;
					},
					success: function (response) {
						if (response.status == 'success') {
							showSuccessAlert(response.message);
						} else {
							showErrorAlert(response.message);
						}
					},
					error: function (xhr, status, error) {
						console.log("An error occurred:", error);
					}
				});
			}
		}
	});

	/* new vehicle images upload funtion */
	$(document).on('click', '.upload_vehicle_images_form', function (event) {
		event.preventDefault();
		var vehicleId = $(".vehicleId").val();
		/* get the form id of the current form */
		var formId = $(this).closest('form').attr('id');
		var formElement = $(this).closest('form')[0];
		var formData = new FormData(formElement);

		if (vehicleId === '') {
			showWarningToast("Kinldy Add Vehicle Information, Then Vehicle Images.");
			/*// Scroll to the target div */
			$('html, body').animate({ scrollTop: $('.stepsDivForm').offset().top }, 1000);
			return false;
		}

		if (!validateVehicleImagesFields(formId)) {
			return false;
		}

		formData.append('formId', formId);
		formData.append('vehicle_id', $(".vehicleId").val());
		formData.append('vehicle_type', $(".vehicle_type").val());

		$.ajax({
			url: formElement.action,
			type: formElement.method,
			data: formData,
			processData: false,
			contentType: false,
			xhr: function () {
				var xhr = new window.XMLHttpRequest();
				xhr.upload.addEventListener('progress', function (evt) {
					if (evt.lengthComputable) {
						var percentComplete = (evt.loaded / evt.total) * 100;
						$(".vehicleUploadProgressBar").removeClass("d-none");
						$(".vehicleUploadProgressBarData").css('width', percentComplete + '%').attr('aria-valuenow', percentComplete).text(percentComplete.toFixed(0) + '%');
					}
				}, false);
				return xhr;
			},
			beforeSend: function () {
				/*// Disable current form submit button */
				$(this).prop('disabled', true);
				/*// Scroll to the target div */
				$('html, body').animate({
					scrollTop: $('.thumbnailImgDIv').offset().top
				}, 1000);
			},
			success: function (response) {
				/* form reset */
				$('#' + formId)[0].reset();
				/* minimize the accordian based on the form submitted  */
				setTimeout(function () {
					if (formId == 'exterior_main') {
						$('#exterior_diagnoal_toggle_btn').click();
					} else if (formId == 'exterior_diagnoal') {
						$('#exterior_wheel_toggle_btn').click();
					} else if (formId == 'exterior_wheel') {
						$('#exterior_tyrethread_toggle_btn').click();
					} else if (formId == 'exterior_tyrethread') {
						$('#exterior_underbody_toggle_btn').click();
					} else if (formId == 'interior_form') {

					} else if (formId == 'others_form') {

					}
					$(".vehicleUploadProgressBar").addClass("d-none");
				}, 3000);
				showSuccessAlert(response.message);
			},
			error: function (xhr, status, error) {
				showErrorAlert('Upload failed:', error);
			}
		});

		return false;
	});

	/* Update Vehicle Image */
	$('.updateVehiceImg').on('click', function () {

		var formField = $(this).data('pickformfield');
		var inputFile = $('#' + formField);

		if (inputFile.prop('files').length > 0) {
			var formData = new FormData();
			formData.append('newVehicleImg', inputFile.prop('files')[0]);
			formData.append('vehicleId', $(".vehicleId").val());
			formData.append('colName', formField);

			$.ajax({
				type: 'POST',
				url: base_url + '/dealer/update-vehicle-image',
				data: formData,
				processData: false,
				contentType: false,
				beforeSend: function () {
					showOverlay();
				},
				success: function (response) {
					if (response.status === 'success') {
						showSuccessAlert(response.message);
						var imgClass = '.replace_' + formField;
						$(imgClass).attr('src', response.img_url);
						$(imgClass + '_li').attr('href', response.img_url);
					} else {
						showErrorAlert(response.message);
					}
				},
				error: function (xhr, status, error) {
					console.error('Error occurred during image upload:', error);
				}
			});
		} else {
			showWarningToast('No File Selected');
		}
	});


	/* lazy loading skeleton shimmer effect start */

	lazzy_loader(limit);

	function lazzy_loader(limit) {
		var output = '';
		for (var count = 0; count < limit; count++) {
			output += '<div class="post_data">';
			output += '<p><span class="content-placeholder" style="width:100%; height: 30px;">&nbsp;</span></p>';
			output += '<p><span class="content-placeholder" style="width:100%; height: 100px;">&nbsp;</span></p>';
			output += '</div>';
		}
		$('#load_data_message').html(output);
	}

	function load_data(limit, start, actionurl) {

		$.ajax({
			url: actionurl,
			method: "POST",
			data: { limit: limit, start: start },
			cache: false,
			dataType: 'html',
			success: function (data) {
				if (data == '') {
					$('#load_data_message').html('<hr><h3>No More Result Found</h3>');
					action = 'active';
				} else {
					$('#load_data').append(data);
					$('#load_data_message').html("");
					action = 'inactive';
				}
			}
		});
	}

	if (action == 'inactive') {
		action = 'active';
		var actionurl = $("#actionurl").val();
		load_data(limit, start, actionurl);
	}

	$(window).scroll(function () {
		if ($(window).scrollTop() + $(window).height() > $("#load_data").height() && action == 'inactive') {
			var actionurl = $("#actionurl").val();
			lazzy_loader(limit);
			action = 'active';
			start = start + limit;
			setTimeout(function () {
				load_data(limit, start, actionurl);
			}, 1000);
		}
	});

	/* lazy loading skeleton shimmer effect end */

	/* review modal start */
	$(document).on('click', '.view-reviews-link', function (event) {
		event.preventDefault();
		var branchId = $(this).data('branch-id');
		$.ajax({
			url: base_url + 'dealer/branch-review/' + branchId,
			type: 'GET',
			dataType: 'json',
			success: function (response) {
				var reviewsContent = $('#reviewsContent');
				reviewsContent.empty();

				if (response.length > 0) {
					$.each(response, function (index, review) {
						reviewsContent.append('<div class="review-item">' +
							'<p><strong>Customer:</strong> ' + review.userName + '</p>' +
							'<p><strong>Rating:</strong> ' + review.rating + '</p>' +
							'<p><strong>Message:</strong> ' + review.message + '</p>' +
							'<p><strong>Date:</strong> ' + review.created_datetime + '</p>' +
							'<hr>' +
							'</div>');
					});
				} else {
					showErrorAlert('<p>No reviews found for this branch.</p>');
				}

				$('#reviewsModal').modal('show');
			},
			error: function () {
				showWarningToast('Error fetching reviews.');
			}
		});
	});
	/* review modal end */

	/* promtion page */
	$(".getPaymentmentAmt").click(function () {
		var PlanChecked = $("input[name='promotion-amount-radio']:checked").val();
		$('#promotionPlanValue').text(PlanChecked);

		/* hide the razorpay button */
		$("#rzp-promotion-button").hide();

		$(".promotionPlanPay").text("Promote");
		$(".promotionPlanPay").prop("disabled", false);
		/* show the promote button */
		$(".promotionPlanPay").show();
	});

	$("#promotionPlanProcess").submit(function (event) {
		event.preventDefault();
		$('.promotionPayBtnScript').html("");

		$(".promotionPlanPay").text("Processing...");
		$(".promotionPlanPay").prop("disabled", true);

		/*
		// Validation for "Promote Under" dropdown
		var promotionType = $("#promotionType").val();
		if (!promotionType) {
			showErrorAlert("Please select a promotion under which to promote.");
			return;
		}
		*/

		var action_page = $(this).attr("action");
		var formData = new FormData(this);

		/* appending selected PromotionPlan Id */
		var selectedRadio = $("input[name='promotion-amount-radio']:checked");
		if (selectedRadio.length > 0) {
			var promotionPlanId = selectedRadio.data("promotionplanid");
			formData.append('promotionPlanId', promotionPlanId);

			var promotionUnder = selectedRadio.data("promotionunder");
			formData.append('promotionUnder', promotionUnder);

			/* itemId shall be id of vehicle or showroom, from which its initiated */
			var itemId = selectedRadio.data("itemid");
			formData.append('itemId', itemId);
		} else {
			showErrorAlert("Please select a plan.");
		}

		$.ajax({
			url: action_page,
			type: "POST",
			data: formData,
			processData: false,
			contentType: false,
			beforeSend: function () {
				showOverlay();
			},
			success: function (response) {
				Swal.close();
				if (response.status === "success") {
					$('.promotionPayBtnScript').append(response.paymentForm);

					/* hide the promote button */
					$(".promotionPlanPay").hide();

				} else {
					$(".promotionPlanPay").text("Promote");
					$(".promotionPlanPay").prop("disabled", false);
					showErrorAlert(response.responseMessage);
				}
			},
			error: function (xhr, status, error) {
				console.log("An error occurred:", error);
			},
		});

	});

	/* update the Free/Basic plan vehicle category bike/car for the first time login of dealer */
	$("#updatePlanPreference").submit(function (event) {
		event.preventDefault();

		var action_page = $(this).attr("action");
		var formData = new FormData(this);
		Swal.fire({
			title: 'Are you sure?',
			text: 'You wont be able to revert these changes!',
			icon: 'info',
			showCancelButton: true,
			confirmButtonColor: '#3085d6',
			cancelButtonColor: '#d33',
			confirmButtonText: 'Yes, Proceed!'
		}).then((result) => {
			$("#updatePlanPreference :submit").prop("disabled", true);
			if (result.isConfirmed) {
				$.ajax({
					url: action_page,
					type: "POST",
					data: formData,
					processData: false,
					contentType: false,
					success: function (response) {
						if (response.status === "success") {
							showSuccessAlert(response.message);
							setTimeout(function () {
								location.reload();
							}, 3000);
						} else {
							showErrorAlert(response.message);
						}
					},
					error: function (xhr, status, error) {
						console.log("An error occurred:", error);
					},
				});
			}
		});
	});

});


/*// check vehicle images fields empty or not loaded */
function validateVehicleImagesFields(formId) {
	var fields = $("#" + formId).find('.formInput');
	var emptyFields = [];
	var allFieldsValid = fields.toArray().every(function (field) {
		if (field.value.trim() === '') {
			emptyFields.push(field.id);
			return false;
		} else {
			return true;
		}
	});

	if (!allFieldsValid) {
		var emptyFieldsMessage = "";
		emptyFields.forEach(function (fieldId) {
			var msg = form_validation_messages(fieldId);
			emptyFieldsMessage += "- " + msg + "<br/>";
		});
		showWarningToast(emptyFieldsMessage);
	}
	return allFieldsValid;
}

function validatePassword(password, confirmPassword) {
	if (!password.trim()) {
		return 'Password cannot be empty.';
	}

	if (password.length < 8 || password.length > 12) {
		return 'Password must be between 8 to 12 characters.';
	}

	if (!/[A-Z]/.test(password)) {
		return 'Password must contain at least one uppercase letter.';
	}

	if (!/[a-z]/.test(password)) {
		return 'Password must contain at least one lowercase letter.';
	}

	if (!/\d/.test(password)) {
		return 'Password must contain at least one number.';
	}

	if (!/[!@#$%^&*(),.?":{}|<>]/.test(password)) {
		return 'Password must contain at least one special character.';
	}

	if (password !== confirmPassword) {
		return 'Passwords do not match.';
	}

	/* // Empty string indicates the password is valid */
	return '';
}

/* Alert functions start + */
function showCustomAlert(icon, message, customClass) {
	Swal.fire({
		position: "top-end",
		icon: icon,
		text: message,
		html: message,
		showConfirmButton: false,
		timerProgressBar: true,
		timer: 6000,
		toast: true,
		customClass: {
			popup: customClass + '-toast',
			title: customClass + '-title',
			icon: customClass + '-icon',
			htmlContainer: 'swal2-html-container',
		},
		didOpen: (toast) => {
			Swal.showLoading();
			toast.addEventListener('mouseenter', Swal.stopTimer);
			toast.addEventListener('mouseleave', Swal.resumeTimer);
		},
		didClose: () => {
			Swal.hideLoading();
		},
	});
}

function showSuccessAlert(message) {
	showCustomAlert('success', message, 'swal2-success');
}

function showErrorAlert(message) {
	showCustomAlert('error', message, 'swal2-error');
}

function showWarningToast(message) {
	showCustomAlert('warning', message, 'swal2-warning');
}

function showOverlay() {
	Swal.fire({
		title: '<span style="color: white;">Processing</span>',
		html: '<span style="color: white;">Please wait...</span>',
		allowOutsideClick: false,
		showConfirmButton: false,
		willOpen: () => {
			Swal.showLoading();
		}
	});
}
/* Alert functions end - */


function form_validation_messages(fieldId) {
	var msg = '';
	switch (fieldId) {
		// form step 1 fields validation messages
		case 'branch_id':
			msg = 'Please choose Showroom.';
			break;
		case 'vehicle_type':
			msg = 'Please choose Vehicle Type.';
			break;
		case 'vehicleCompany':
			msg = 'Please choose Vehicle Company/Brand.';
			break;
		case 'vehicleCompanyVariants':
			msg = 'Please choose Vehicle Company/Brand.';
			break;
		case 'vehicleCompanyModel':
			msg = 'Please choose Vehicle Company/Brand Model.';
			break;
		case 'VehicleVariant':
			msg = 'Enter Variant Name';
			break;
		case 'fuel_type':
			msg = 'Please choose Vehicle Fuel Type.';
			break;
		case 'body_type':
			msg = 'Please choose Vehicle Body Type.';
			break;
		case 'variant_id':
			msg = 'Please choose Vehicle Veriant.';
			break;
		case 'mileage':
			msg = 'Please enter Vehicle Mileage.';
			break;
		case 'kms_driven':
			msg = 'Please enter Vehicle Kelometers Driven.';
			break;
		case 'owner':
			msg = 'Please choose Vehicle Owner Type.';
			break;
		case 'transmission_id':
			msg = 'Please choose Vehicle Transmission.';
			break;
		case 'color_id':
			msg = 'Please choose Vehicle Color.';
			break;
		// form step 2 fields validation messages
		case 'manufacture_year':
			msg = 'Please choose Vehicle manufacture year.';
			break;
		case 'registration_year':
			msg = 'Please choose Vehicle registration year.';
			break;
		case 'registeredStateRto':
			msg = 'Please choose Vehicle registered State Rto.';
			break;
		case 'registeredRto':
			msg = 'Please choose Vehicle registered Rto.';
			break;
		// form step 3 fields validation messages
		case 'insurance_type':
			msg = 'Please choose Vehicle insurance type.';
			break;
		case 'insurance_validity':
			msg = 'Please enter Vehicle insurance validity.';
			break;
		// form step 4 fields validation messages
		case 'accidental_status':
			msg = 'Please choose Vehicle accidental status.';
			break;
		case 'flooded_status':
			msg = 'Please choose Vehicle flooded status.';
			break;
		case 'last_service_kms':
			msg = 'Please enter Vehicle last service kms.';
			break;
		case 'last_service_date':
			msg = 'Please choose Vehicle last service date.';
			break;
		// form step 5 fields validation messages
		case 'car_no_of_airbags':
			msg = 'Please choose Vehicle no of airbags.';
			break;
		case 'car_central_locking':
			msg = 'Please choose Vehicle central locking.';
			break;
		case 'car_seat_upholstery':
			msg = 'Please enter Vehicle seat upholstery.';
			break;
		case 'car_sunroof':
			msg = 'Please choose Vehicle sunroof.';
			break;
		case 'car_integrated_music_system':
			msg = 'Please choose Vehicle integrated music system.';
			break;
		case 'car_rear_ac':
			msg = 'Please choose Vehicle rear ac.';
			break;
		case 'car_outside_rear_view_mirrors':
			msg = 'Please choose Vehicle outside rear view mirrors.';
			break;
		case 'car_power_windows':
			msg = 'Please choose Vehicle power windows.';
			break;
		case 'car_engine_start_stop':
			msg = 'Please choose Vehicle engine start stop.';
			break;
		case 'car_headlamps':
			msg = 'Please choose Vehicle headlamps.';
			break;
		case 'car_power_steering':
			msg = 'Please choose Vehicle power steering.';
			break;
		case 'bike_headlight_type':
			msg = 'Please choose Vehicle headlight type.';
			break;
		case 'bike_odometer':
			msg = 'Please choose Vehicle odometer.';
			break;
		case 'bike_drl':
			msg = 'Please choose Vehicle drl.';
			break;
		case 'bike_mobile_connectivity':
			msg = 'Please choose Vehicle mobile connectivity.';
			break;
		case 'bike_gps_navigation':
			msg = 'Please choose Vehicle gps navigation.';
			break;
		case 'bike_usb_charging_port':
			msg = 'Please choose Vehicle usb charging_port.';
			break;
		case 'bike_low_battery_indicator':
			msg = 'Please choose Vehicle low battery indicator.';
			break;
		case 'bike_under_seat_storage':
			msg = 'Please choose Vehicle under seat storage.';
			break;
		case 'bike_speedometer':
			msg = 'Please choose Vehicle speedometer.';
			break;
		case 'bike_stand_alarm':
			msg = 'Please choose Vehicle stand alarm.';
			break;
		case 'bike_low_fuel_indicator':
			msg = 'Please choose Vehicle low fuel indicator.';
			break;
		case 'bike_low_oil_indicator':
			msg = 'Please choose Vehicle low oil indicator.';
			break;
		case 'bike_start_type':
			msg = 'Please choose Vehicle start type.';
			break;
		case 'bike_kill_switch':
			msg = 'Please choose Vehicle kill switch.';
			break;
		case 'bike_break_light':
			msg = 'Please choose Vehicle break light.';
			break;
		case 'bike_turn_signal_indicator':
			msg = 'Please choose Vehicle turn signal indicator.';
			break;
		// form step 6 fields validation messages
		case 'onsale_status':
			msg = 'Please choose Vehicle onsale status.';
			break;
		case 'onsale_percentage':
			msg = 'Please enter Vehicle onsale percentage.';
			break;
		case 'regular_price':
			msg = 'Please enter Vehicle regular price.';
			break;
		case 'selling_price':
			msg = 'Please enter Vehicle selling price.';
			break;
		case 'pricing_type':
			msg = 'Please choose Vehicle pricing type.';
			break;
		case 'emi_option':
			msg = 'Please choose Vehicle EMI option status.';
			break;
		case 'avg_interest_rate':
			msg = 'Please enter Vehicle price EMI avarage interest rate in %.';
			break;
		case 'tenure_months':
			msg = 'Please enter Vehicle EMI tenure months.';
			break;
		case 'reservation_amt':
			msg = 'Please enter Vehicle Reservation Amount.';
			break;
		// form images fields validation messages
		case 'exterior_main_front_img':
			msg = 'Please choose Vehicle Exterior Main Front Image.';
			break;
		case 'exterior_main_right_img':
			msg = 'Please choose Vehicle Exterior Main Right Image.';
			break;
		case 'exterior_main_back_img':
			msg = 'Please choose Vehicle Exterior Main Back Image.';
			break;
		case 'exterior_main_left_img':
			msg = 'Please choose Vehicle Exterior Main Left Image.';
			break;
		case 'exterior_main_roof_img':
			msg = 'Please choose Vehicle Exterior Main Roof Image.';
			break;
		case 'exterior_main_bonetopen_img':
			msg = 'Please choose Vehicle Exterior Main bonet open Image.';
			break;
		case 'exterior_main_engine_img':
			msg = 'Please choose Vehicle Exterior Main engine Image.';
			break;

		case 'exterior_diagnoal_right_front_img':
			msg = 'Please choose Vehicle Exterior Diagnoal Right Front Image.';
			break;
		case 'exterior_diagnoal_right_back_img':
			msg = 'Please choose Vehicle Exterior Diagnoal Right Back Image.';
			break;
		case 'exterior_diagnoal_left_back_img':
			msg = 'Please choose Vehicle Exterior Diagnoal Left Back Image.';
			break;
		case 'exterior_diagnoal_left_front_img':
			msg = 'Please choose Vehicle Exterior Diagnoal Left Front Image.';
			break;

		case 'exterior_wheel_right_front_img':
			msg = 'Please choose Vehicle Exterior Wheel Right Front Image.';
			break;
		case 'exterior_wheel_right_back_img':
			msg = 'Please choose Vehicle Exterior Wheel Right Back Image.';
			break;
		case 'exterior_wheel_left_back_img':
			msg = 'Please choose Vehicle Exterior Wheel Left Back Image.';
			break;
		case 'exterior_wheel_left_front_img':
			msg = 'Please choose Vehicle Exterior Wheel Left Front Image.';
			break;
		case 'exterior_wheel_spare_img':
			msg = 'Please choose Vehicle Exterior Wheel Spare Image.';
			break;

		case 'exterior_tyrethread_right_front_img':
			msg = 'Please choose Vehicle Exterior Tyrethread Right Front Image.';
			break;
		case 'exterior_tyrethread_right_back_img':
			msg = 'Please choose Vehicle Exterior Tyrethread Right Back Image.';
			break;
		case 'exterior_tyrethread_left_back_img':
			msg = 'Please choose Vehicle Exterior Tyrethread Left Back Image.';
			break;
		case 'exterior_tyrethread_left_front_img':
			msg = 'Please choose Vehicle Exterior Tyrethread Left Front Image.';
			break;

		case 'exterior_underbody_front_img':
			msg = 'Please choose Vehicle Exterior Underbody Front Image.';
			break;
		case 'exterior_underbody_rear_img':
			msg = 'Please choose Vehicle Exterior Underbody Rear Image.';
			break;
		case 'exterior_underbody_right_img':
			msg = 'Please choose Vehicle Exterior Underbody Right Image.';
			break;
		case 'exterior_underbody_left_img':
			msg = 'Please choose Vehicle Exterior Underbody Left Image.';
			break;

		case 'interior_dashboard_img':
			msg = 'Please choose Vehicle Interior Dashboard Image.';
			break;
		case 'interior_infotainment_system_img':
			msg = 'Please choose Vehicle Interior Infotainment System Image.';
			break;
		case 'interior_steering_wheel_img':
			msg = 'Please choose Vehicle Interior Steering Wheel Image.';
			break;
		case 'interior_odometer_img':
			msg = 'Please choose Vehicle Interior Odometer Image.';
			break;
		case 'interior_gear_lever_img':
			msg = 'Please choose Vehicle Interior Gear Lever Image.';
			break;
		case 'interior_pedals_img':
			msg = 'Please choose Vehicle Interior Pedals Image.';
			break;
		case 'interior_front_cabin_img':
			msg = 'Please choose Vehicle Interior Front Cabin Image.';
			break;
		case 'interior_rear_cabin_img':
			msg = 'Please choose Vehicle Interior Rear Cabin Image.';
			break;

		case 'interior_driver_side_door_panel_img':
			msg = 'Please choose Vehicle Interior Driver Side Door Panel Image.';
			break;
		case 'interior_driver_side_adjustment_img':
			msg = 'Please choose Vehicle Interior Driver Side Adjustment Image.';
			break;
		case 'interior_boot_inside_img':
			msg = 'Please choose Vehicle Interior Boot Inside Image.';
			break;
		case 'interior_boot_door_open_img':
			msg = 'Please choose Vehicle Interior Boot Door Open Image.';
			break;
		case 'others_keys_img':
			msg = 'Please choose Vehicle Interior Others Image.';
			break;

		/* //add company from validation start */
		case 'cmp_name':
			msg = 'Enter Vehicle Company Name.';
			break;
		case 'cmp_logo':
			msg = 'Choose Vehicle Company Image.';
			break;
		case 'cmp_category':
			msg = 'Choose Vehicle Company Category.';
			break;
		case 'cmp_status':
			msg = 'Choose Status.';
			break;
		case 'featured_status':
			msg = 'Choose Featured Status.';
			break;
		case 'VehicleModel':
			msg = 'Enter Model Name';
			break;
		/* //add company from validation end */
		case 'promotionType':
			msg = 'Choose Promotion Type'
			break;
		default:
			/*// Default case if none of the above cases match*/
			msg = 'Form Field ID not found.';
			break;
	}

	return msg;
}