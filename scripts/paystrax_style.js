jQuery(document).ready(function () {
    // Retrieve the checkbox status from local storage
    var isChecked = localStorage.getItem('termsCheckbox');
    if (isChecked === 'checked') {
        // If checkbox was checked before, set it as checked
        jQuery('input[name="terms"]').prop('checked', true);
    }

    // Handle change event for the payment method checkbox
    jQuery(document).on("change", "input[name=payment_method]", function () {
        var payment_method = jQuery('input[name=payment_method]:checked').val();
        if (payment_method == 'paystrax') {
            jQuery('#place_order').addClass('hide');
            localStorage.setItem("payment_method", 'paystrax');
        } else {
            jQuery('#place_order').removeClass('hide');
        }
    });

    // Handle click event for opening the modal
    jQuery(document).on('click', '.open-modal', function () {
        // Set details and validate fields
        setDetails();
        let valid = checkoutFieldDetailsValidation();
        if (valid !== false) {
            jQuery('#modal1').addClass('is-visible');
        }
    });

    // Handle click event for closing the modal
    jQuery(document).on('click', '.close-modal', function () {
        jQuery('#modal1').removeClass('is-visible');
    });

    // Handle keyup event for closing the modal when pressing ESC
    jQuery(document).on('keyup', function (e) {
        if (e.key == "Escape") {
            jQuery('#modal1').removeClass('is-visible');
        }
    });

    // Handle change event for the terms checkbox
    jQuery(document).on("change", "input[name=terms]", function () {
        // Store the checkbox status in local storage
        localStorage.setItem('termsCheckbox', this.checked ? 'checked' : 'unchecked');
    });
});


function setDetails() {
    //var customer_details = document.forms.checkout.children.customer_details;

    var customer_details = document.getElementById('customer_details');

document.getElementById('customer_details')
    if (customer_details.querySelector('#billing_first_name') !== null) {
        var billing_first_name = customer_details.querySelector('#billing_first_name').value;
        var is_billing_first_name_required = customer_details.querySelector('#billing_first_name_field');
        var billing_first_name_has_required_class = is_billing_first_name_required.classList.contains('validate-required');
    }
    if (customer_details.querySelector('#billing_last_name') !== null) {
        var billing_last_name = customer_details.querySelector('#billing_last_name').value;
        var is_billing_last_name_required = customer_details.querySelector('#billing_last_name_field');
        var billing_last_name_has_required_class = is_billing_last_name_required.classList.contains('validate-required');
    }
    if (customer_details.querySelector('#billing_company') !== null) {
        var billing_company = customer_details.querySelector('#billing_company').value;
        var is_billing_company_required = customer_details.querySelector('#billing_company_field');
        var billing_company_has_required_class = is_billing_company_required.classList.contains('validate-required');
    }
    if (customer_details.querySelector('#billing_country') !== null) {
        var billing_country = customer_details.querySelector('#billing_country').value;
        var is_billing_country_required = customer_details.querySelector('#billing_country_field');
        var billing_country_has_required_class = is_billing_country_required.classList.contains('validate-required');
    }
    if (customer_details.querySelector('#billing_address_1') !== null) {
        var billing_address_1 = customer_details.querySelector('#billing_address_1').value;
        var is_billing_address_1_required = customer_details.querySelector('#billing_address_1_field');
        var billing_address_1_has_required_class = is_billing_address_1_required.classList.contains('validate-required');
    }
    if (customer_details.querySelector('#billing_address_2') !== null) {
        var billing_address_2 = customer_details.querySelector('#billing_address_2').value;
        var is_billing_address_2_required = customer_details.querySelector('#billing_address_2_field');
        var billing_address_2_has_required_class = is_billing_address_2_required.classList.contains('validate-required');
    }
    if (customer_details.querySelector('#billing_city') !== null) {
        var billing_city = customer_details.querySelector('#billing_city').value;
        var is_billing_city_required = customer_details.querySelector('#billing_city_field');
        var billing_city_has_required_class = is_billing_city_required.classList.contains('validate-required');
    }
    if (customer_details.querySelector('#billing_state') !== null) {
        var billing_state = customer_details.querySelector('#billing_state').value;
        var is_billing_state_required = customer_details.querySelector('#billing_state_field');
        var billing_state_has_required_class = is_billing_state_required.classList.contains('validate-required');
    }
    if (customer_details.querySelector('#billing_postcode') !== null) {
        var billing_postcode = customer_details.querySelector('#billing_postcode').value;
        var is_billing_postcode_required = customer_details.querySelector('#billing_postcode_field');
        var billing_postcode_has_required_class = is_billing_postcode_required.classList.contains('validate-required');
    }
    if (customer_details.querySelector('#billing_phone') !== null) {
        var billing_phone = customer_details.querySelector('#billing_phone').value;
        var is_billing_phone_required = customer_details.querySelector('#billing_phone_field');
        var billing_phone_has_required_class = is_billing_phone_required.classList.contains('validate-required');
    }
    if (customer_details.querySelector('#billing_email') !== null) {
        var billing_email = customer_details.querySelector('#billing_email').value;
        var is_billing_email_required = customer_details.querySelector('#billing_email_field');
        var billing_email_has_required_class = is_billing_email_required.classList.contains('validate-required');
    }


    var customerDetails = {
        ...(billing_first_name_has_required_class && {
            billing_first_name
        }),
        ...(billing_last_name_has_required_class && {
            billing_last_name
        }),
        ...(billing_company_has_required_class && {
            billing_company
        }),
        ...(billing_country_has_required_class && {
            billing_country
        }),
        ...(billing_state_has_required_class && {
            billing_state
        }),
        ...(billing_city_has_required_class && {
            billing_city
        }),
        ...(billing_email_has_required_class && {
            billing_email
        }),
        ...(billing_postcode_has_required_class && {
            billing_postcode
        }),
        ...(billing_phone_has_required_class && {
            billing_phone
        }),
        ...(billing_address_1_has_required_class && {
            billing_address_1
        }),
        ...(billing_address_2_has_required_class && {
            billing_address_2
        }),
    }


    //get billing required fields and shipping details.

    if (customer_details.querySelector('#shipping_first_name') !== null) {
        var shipping_first_name = customer_details.querySelector('#shipping_first_name').value;
        var is_shipping_first_name_required = customer_details.querySelector('#shipping_first_name_field');
        var shipping_first_name_has_required_class = is_shipping_first_name_required.classList.contains('validate-required');
    }
    if (customer_details.querySelector('#shipping_last_name') !== null) {
        var shipping_last_name = customer_details.querySelector('#shipping_last_name').value;
        var is_shipping_last_name_required = customer_details.querySelector('#shipping_last_name_field');
        var shipping_last_name_has_required_class = is_shipping_last_name_required.classList.contains('validate-required');
    }
    if (customer_details.querySelector('#shipping_company') !== null) {
        var shipping_company = customer_details.querySelector('#shipping_company').value;
        var is_shipping_company_required = customer_details.querySelector('#shipping_company_field');
        var shipping_company_has_required_class = is_shipping_company_required.classList.contains('validate-required');
    }
    if (customer_details.querySelector('#shipping_country') !== null) {
        var shipping_country = customer_details.querySelector('#shipping_country').value;
        var is_shipping_country_required = customer_details.querySelector('#shipping_country_field');
        var shipping_country_has_required_class = is_shipping_country_required.classList.contains('validate-required');
    }
    if (customer_details.querySelector('#shipping_address_1') !== null) {
        var shipping_address_1 = customer_details.querySelector('#shipping_address_1').value;
        var is_shipping_address_1_required = customer_details.querySelector('#shipping_address_1_field');
        var shipping_address_1_has_required_class = is_shipping_address_1_required.classList.contains('validate-required');
    }
    if (customer_details.querySelector('#shipping_address_2') !== null) {
        var shipping_address_2 = customer_details.querySelector('#shipping_address_2').value;
        var is_shipping_address_2_required = customer_details.querySelector('#shipping_address_2_field');
        var shipping_address_2_has_required_class = is_shipping_address_2_required.classList.contains('validate-required');
    }
    if (customer_details.querySelector('#shipping_city') !== null) {
        var shipping_city = customer_details.querySelector('#shipping_city').value;
        var is_shipping_city_required = customer_details.querySelector('#shipping_city_field');
        var shipping_city_has_required_class = is_shipping_city_required.classList.contains('validate-required');
    }
    if (customer_details.querySelector('#shipping_state') !== null) {
        var shipping_state = customer_details.querySelector('#shipping_state').value;
        var is_shipping_state_required = customer_details.querySelector('#shipping_state_field');
        var shipping_state_has_required_class = is_shipping_state_required.classList.contains('validate-required');
    }
    if (customer_details.querySelector('#shipping_postcode') !== null) {
        var shipping_postcode = customer_details.querySelector('#shipping_postcode').value;
        var is_shipping_postcode_required = customer_details.querySelector('#shipping_postcode_field');
        var shipping_postcode_has_required_class = is_shipping_postcode_required.classList.contains('validate-required');
    }



    if (document.querySelector('.shipping_address') !== null) {
        let displayNone = document.querySelector('.shipping_address').style.display;
        if (displayNone == 'none') {
            console.log('if', displayNone);
        } else {
            localStorage.setItem("shipping_address", true);
            customerDetails = {
                ...customerDetails,
                requiredShippingDetails: {
                    ...(shipping_first_name_has_required_class && {
                        shipping_first_name
                    }),
                    ...(shipping_last_name_has_required_class && {
                        shipping_last_name
                    }),
                    ...(shipping_company_has_required_class && {
                        shipping_company
                    }),
                    ...(shipping_country_has_required_class && {
                        shipping_country
                    }),
                    ...(shipping_address_1_has_required_class && {
                        shipping_address_1
                    }),
                    ...(shipping_address_2_has_required_class && {
                        shipping_address_2
                    }),
                    ...(shipping_city_has_required_class && {
                        shipping_city
                    }),
                    ...(shipping_state_has_required_class && {
                        shipping_state
                    }),
                    ...(shipping_postcode_has_required_class && {
                        shipping_postcode
                    }),
                }
            }
        }
    }
    localStorage.setItem("customerDetatails", JSON.stringify(customerDetails));
}

///text translation

function translate(txt) {
    var sourceText = txt;
    var sourceLang = 'en';
    var targetLang = localStorage.getItem('language');
    var url = "https://translate.googleapis.com/translate_a/single?client=gtx&sl=" + sourceLang + "&tl=" + targetLang + "&dt=t&q=" + encodeURI(sourceText);
    $.getJSON(url, function (data) {
        swal(data[0][0][0]);
    })
    .fail(function() {
        console.log( "error" );
      });
}
///

function checkoutFieldDetailsValidation() {
    var details = JSON.parse(localStorage.getItem("customerDetatails"));
    if (details) {
        for (var key in details) {
            if (details[key] == '') {
                translate("Enter Required Billing Details!");
                return false;
            }
            if (key == 'billing_postcode') {
                if (details[key] !== '') {
                    let regEX = PostCodeValidation(details.billing_country, details.billing_postcode);
                    if (!regEX) {
                        translate("Billing PIN Code is not a valid postcode / ZIP!");
                        return false;
                    }
                }
            }
            if (key == 'billing_phone') {
                if (details[key] !== '') {
                    let pattern = /^(?!.*([\(\)\-\/]{2,}|\([^\)]+$|^[^\(]+\)|\([^\)]+\(|\s{2,}).*)\+?([\-\s\(\)\/]*\d){9,17}[\s\(\)]*$/;
                    if (!pattern.test(details.billing_phone) || 0 < details.billing_phone.replace(pattern, '').length) { //|| 12 > details.billing_phone.replace(pattern, '').length
                        translate("Billing Phone is not a valid phone number!");
                        return false;
                    }
                }
            }
            if (key == 'billing_email') {
                if (details[key] !== '') {
                    let pattern = new RegExp(/^([a-z\d!#$%&'*+\-\/=?^_`{|}~\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]+(\.[a-z\d!#$%&'*+\-\/=?^_`{|}~\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]+)*|"((([ \t]*\r\n)?[ \t]+)?([\x01-\x08\x0b\x0c\x0e-\x1f\x7f\x21\x23-\x5b\x5d-\x7e\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]|\\[\x01-\x09\x0b\x0c\x0d-\x7f\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]))*(([ \t]*\r\n)?[ \t]+)?")@(([a-z\d\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]|[a-z\d\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF][a-z\d\-._~\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]*[a-z\d\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])\.)+([a-z\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]|[a-z\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF][a-z\d\-._~\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]*[0-9a-z\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])\.?$/i); // eslint-disable-line max-len
                    if (!pattern.test(details.billing_email)) {
                        translate("Billing Email is not a valid Email!");
                        return false;
                    }
                }
            }
            if (key == 'requiredShippingDetails') {
                for (var key1 in details[key]) {
                    if (details[key][key1] == '') {
                        translate("Enter Required Shipping Details!");
                        return false;
                    }
                    if (key1 == 'shipping_postcode') {
                        if (details[key] !== '') {
                            let regEX = PostCodeValidation(details.requiredShippingDetails.shipping_country, details.requiredShippingDetails.shipping_postcode);
                            if (!regEX) {
                                translate("Shipping PIN Code is not a valid postcode / ZIP!");
                                return false;
                            }
                        }
                    }
                }
            }
        }
    }
	// add validation for the terms.
	var $termsCheckbox = $('input[name="terms"]');

    // Check if the checkbox is checked
    if ($termsCheckbox.is(':checked')) {
        // Checkbox is checked, do something
        console.log("Terms and conditions checkbox is checked.");
    } else {
        // Checkbox is not checked, do something else
        console.log("Terms and conditions checkbox is not checked.");
		translate("Terms and conditions checkbox is not checked.");
		return false;
    }
}



//postal code for different country
function PostCodeValidation(country, postcode) {
    let pattern = true;
    switch (country) {
        case 'AT':
            pattern = new RegExp(/^([0-9]{4})$/).test(postcode);
            break;
        case 'BA':
            pattern = new RegExp(/^([7-8]{1})([0-9]{4})$/).test(postcode);
            break;
        case 'BE':
            pattern = new RegExp(/^([0-9]{4})$/i).test(postcode);
            break;
        case 'BR':
            pattern = new RegExp(/^([0-9]{5})([-])?([0-9]{3})$/).test(postcode);
            break;
        case 'CH':
            pattern = new RegExp(/^([0-9]{4})$/i).test(postcode);
            break;
        case 'DE':
            pattern = new RegExp(/^([0]{1}[1-9]{1}|[1-9]{1}[0-9]{1})[0-9]{3}$/).test(postcode);
            break;
        case 'ES':
        case 'FR':
        case 'IT':
            pattern = new RegExp(/^([0-9]{5})$/i).test(postcode);
            break;
        case 'HU':
            pattern = new RegExp(/^([0-9]{4})$/i).test(postcode);
            break;
        case 'IN':
            pattern = new RegExp(/^[1-9]{1}[0-9]{2}\s{0,1}[0-9]{3}$/).test(postcode);
            break;
        case 'JP':
            pattern = new RegExp(/^([0-9]{3})([-]?)([0-9]{4})$/).test(postcode);
            break;
        case 'PT':
            pattern = new RegExp(/^([0-9]{4})([-])([0-9]{3})$/.test(postcode));
            break;
        case 'PR':
        case 'US':
            pattern = new RegExp(/^([0-9]{5})(-[0-9]{4})?$/i).test(postcode);
            break;
        case 'IS':
            pattern = new RegExp(/^([0-9]{3})(-[0-9]{4})?$/i).test(postcode);
            break;
        case 'CA':
            // CA Postal codes cannot contain D,F,I,O,Q,U and cannot start with W or Z. https://en.wikipedia.org/wiki/Postal_codes_in_Canada#Number_of_possible_postal_codes.
            pattern = new RegExp(/^([ABCEGHJKLMNPRSTVXY]\d[ABCEGHJKLMNPRSTVWXYZ])([\ ])?(\d[ABCEGHJKLMNPRSTVWXYZ]\d)$/i).test(postcode);
            break;
        case 'PL':
            pattern = new RegExp(/^([0-9]{2})([-])([0-9]{3})$/).test(postcode);
            break;
        case 'CZ':
        case 'SK':
            pattern = new RegExp(/^([0-9]{3})(\s?)([0-9]{2})$/).test(postcode);
            break;
        case 'NL':
            pattern = new RegExp(/^([1-9][0-9]{3})(\s?)(?!SA|SD|SS)[A-Z]{2}$/i).test(postcode);
            break;
        case 'SI':
            pattern = new RegExp(/^([1-9][0-9]{3})$/).test(postcode);
            break;
        case 'LI':
            pattern = new RegExp(/^(94[8-9][0-9])$/).test(postcode);
            break;
        default:
            pattern = true;
            break;
    }
    return pattern;
}