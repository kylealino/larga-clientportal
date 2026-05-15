var __mysys_myregistration_ent = new __mysys_myregistration_ent();
function __mysys_myregistration_ent() {  
	const mesiteurl = $('#__siteurl').attr('data-mesiteurl');

	this.__user_registration = function() { 
		'use strict' 
		// Fetch all the forms we want to apply custom Bootstrap validation styles to
		var forms = document.querySelectorAll('.myregistration-validation')
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
					var email = document.getElementById("email");
					var username = document.getElementById("username");
					var hash_value = document.getElementById("confirm_password");
					
					var mparam = { 
						company_code: company_code.value,
						email: email.value,
						username: username.value,
						hash_value: hash_value.value,
						meaction: 'REGISTER-SAVE'
					}


					jQuery.ajax({
						type: "POST",
						url: mesiteurl + 'myregistration',
						data: mparam,
						dataType: 'json',

						success: function(data) {

							console.log(data);

							if(data.status == 'success'){

								toastr.success(data.message);

								setTimeout(() => {
									window.location.href = mesiteurl + 'myotp?meaction=MAIN&company_code=' + company_code.value;
								}, 1500);

							} else {

								toastr.error(data.message);

							}

						},

						error: function(xhr, status, error) {

							toastr.error("Error: " + error);

						}
					});
				} catch(err) { 
					alert(err.message)
					return false;
				} //end try 
			}, false)
		}); //end forEach		
	}; //

	
}; //end main

__mysys_myregistration_ent.__user_registration();