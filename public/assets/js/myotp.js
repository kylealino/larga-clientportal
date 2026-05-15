var __mysys_myotp_ent = new __mysys_myotp_ent();
function __mysys_myotp_ent() {  
	const mesiteurl = $('#__siteurl').attr('data-mesiteurl');

	this.__user_otp_registration = function() { 
		'use strict' 
		var forms = document.querySelectorAll('.myotp-validation')
		Array.prototype.slice.call(forms)
		.forEach(function (form) {
			form.addEventListener('submit', function (event) {
				if (!form.checkValidity()) {
					event.preventDefault()
					event.stopPropagation()
				}
				try {
					event.preventDefault();
					event.stopPropagation();

					var company_code = document.getElementById("company_code");
					var otp_code = document.getElementById("otp_code");
					var otp1 = document.getElementById("otp1");
					var otp2 = document.getElementById("otp2");
					var otp3 = document.getElementById("otp3");
					var otp4 = document.getElementById("otp4");
					var otp5 = document.getElementById("otp5");
					var otp6 = document.getElementById("otp6");

					var combined_otp = otp1.value + otp2.value + otp3.value + otp4.value + otp5.value + otp6.value;
					
					// Frontend validation only (not attempt tracking)
					if (combined_otp.length !== 6) {
						$('#errorMsg').addClass('show');
						$('#errorText').text('Please enter all 6 digits.');
						toastr.error('Please enter all 6 digits.', 'Incomplete Code');
						return false;
					}

					// Get the verify button and add loading state
					const verifyBtn = $('#verifyBtn');
					verifyBtn.addClass('loading');
					verifyBtn.html('<i class="fas fa-spinner fa-spin"></i> VERIFYING...');

					var mparam = { 
						company_code: company_code.value,
						otp_code: otp_code.value,
						otp1: otp1.value,
						otp2: otp2.value,
						otp3: otp3.value,
						otp4: otp4.value,
						otp5: otp5.value,
						otp6: otp6.value,
						meaction: 'OTP-SAVE'
					}

					jQuery.ajax({
						type: "POST",
						url: mesiteurl + 'myotp',
						data: mparam,
						dataType: 'json',
						success: function(data) {
							console.log(data);
							
							verifyBtn.removeClass('loading');
							verifyBtn.html('<i class="fas fa-check-double"></i> VERIFY & CONTINUE');
							
							if(data.status == 'success'){
								toastr.success(data.message);
								setTimeout(() => {
									window.location.href = mesiteurl + '';
								}, 1500);
							} else {
								toastr.error(data.message);
								
								// If max attempts reached, disable buttons
								if(data.max_attempts_reached === true) {
									$('#verifyBtn').prop('disabled', true);
									$('#verifyBtn').css('opacity', '0.6');
									$('#resendBtn').prop('disabled', true);
									$('#resendBtn').css('opacity', '0.5');
								}
								
								// Shake animation effect on OTP container
								$('#otpContainer').addClass('shake-effect');
								setTimeout(() => $('#otpContainer').removeClass('shake-effect'), 500);
								
								// Make fields red
								otp1.classList.add('error');
								otp2.classList.add('error');
								otp3.classList.add('error');
								otp4.classList.add('error');
								otp5.classList.add('error');
								otp6.classList.add('error');
								
								setTimeout(() => {
									otp1.classList.remove('error');
									otp2.classList.remove('error');
									otp3.classList.remove('error');
									otp4.classList.remove('error');
									otp5.classList.remove('error');
									otp6.classList.remove('error');
								}, 1000);
								
								// Clear OTP fields after error
								otp1.value = '';
								otp2.value = '';
								otp3.value = '';
								otp4.value = '';
								otp5.value = '';
								otp6.value = '';
								otp1.focus();
							}
						},

						error: function(xhr, status, error) {
							toastr.error("Error: " + error);
							verifyBtn.removeClass('loading');
							verifyBtn.html('<i class="fas fa-check-double"></i> VERIFY & CONTINUE');
						}
					});
				} catch(err) { 
					alert(err.message)
					return false;
				}
			}, false)
		});
	};

	this.__user_otp_resend = function() { 
		'use strict' 
		// Fetch all the forms we want to apply custom Bootstrap validation styles to
		var forms = document.querySelectorAll('.myotp-resend-validation')
		// Loop over them and prevent submission
		Array.prototype.slice.call(forms)
		.forEach(function (form) {
			form.addEventListener('submit', function (event) {
				if (!form.checkValidity()) {
					event.preventDefault()
					event.stopPropagation()
				}
				try {
					event.preventDefault();
					event.stopPropagation();

					var company_code = document.getElementById("company_code");

					// Get the resend button and add loading state
					const resendBtn = $('#resendBtn');
					const originalButtonText = resendBtn.html();
					resendBtn.addClass('loading');
					resendBtn.prop('disabled', true);
					resendBtn.html('<i class="fas fa-spinner fa-spin"></i> Sending...');

					var mparam = { 
						company_code: company_code.value,
						meaction: 'OTP-RESEND'
					}

					jQuery.ajax({
						type: "POST",
						url: mesiteurl + 'myotp',
						data: mparam,
						dataType: 'json',
						success: function(data) {
							console.log(data);
							
							// Reset button state
							resendBtn.removeClass('loading');
							resendBtn.prop('disabled', false);
							resendBtn.html(originalButtonText);
							
							if(data.status == 'success'){
								toastr.success(data.message);
								
								// Reset timer if needed (optional)
								if (typeof startTimer === 'function') {
									startTimer();
								}
								
								// Clear OTP fields
								const otpInputs = ['otp1', 'otp2', 'otp3', 'otp4', 'otp5', 'otp6'];
								otpInputs.forEach(id => {
									const field = document.getElementById(id);
									if (field) field.value = '';
								});
								if (document.getElementById('otp1')) {
									document.getElementById('otp1').focus();
								}
								
								// Hide error messages
								$('#errorMsg').removeClass('show');
								
								// Optionally redirect or stay on same page
								setTimeout(() => {
								    window.location.href = mesiteurl + 'myotp?meaction=MAIN&company_code=' + company_code.value;
								}, 1500);
								
							} else {
								toastr.error(data.message);
							}
						},

						error: function(xhr, status, error) {
							toastr.error("Error: " + error);
							
							// Reset button state on error
							resendBtn.removeClass('loading');
							resendBtn.prop('disabled', false);
							resendBtn.html(originalButtonText);
						}
					});
				} catch(err) { 
					alert(err.message)
					return false;
				} //end try 
			}, false)
		}); //end forEach        
	};
	
}; //end main

__mysys_myotp_ent.__user_otp_registration();
__mysys_myotp_ent.__user_otp_resend();