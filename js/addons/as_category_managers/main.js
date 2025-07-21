$(document).on('change', '#insurance_terms' , function() {
	let value = $(this).val();

	if (value === 'customer_to_bear_policy') {
		$('#insurance_policy_number_wrapper').removeClass('hidden');
	} else {
		$('#insurance_policy_number_wrapper').addClass('hidden');
	}
});