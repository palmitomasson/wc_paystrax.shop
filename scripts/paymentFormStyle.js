var wpwlOptions = {
    style: "card",
    //locale: 'it',
    locale: localStorage.getItem('language'),
    onReady: function (e) {
    	// Validate card holder only when submitting card number directly (not for ApplePay)
        $('.wpwl-form-card').find('.wpwl-button-pay').on('click', function (e) {
            validateHolder(e);
            //alert('blax');

        });
    },
    // onPaymentMethodSelected: function (payment) {
        // //alert('blaxx');
        // console.log("onPaymentAuthorized payment: " + JSON.stringify(payment));
        // document.getElementById('debug-message').innerHTML = JSON.stringify(payment);
        // /*return {
            // // Possible values: SUCCESS, FAILURE
            // status: "FAILURE",

            // errors: [{
                // // Possible values: shippingContactInvalid, billingContactInvalid,
                // // addressUnserviceable
                // code: "shippingContactInvalid",

                // // Possible values: phoneNumber, emailAddress, name, phoneticName,
                // // postalAddress, addressLines, locality, subLocality, postalCode,
                // // administrativeArea, subAdministrativeArea, country, countryCode
                // contactField: "phoneNumber",

                // message: "Invalid phone number"
            // }]
        // };*/
    // },
    googlePay: {
        gatewayMerchantId: localStorage.getItem('entityId'),
		merchantId: localStorage.getItem('merchantId'),
        allowedAuthMethods: ["PAN_ONLY", "CRYPTOGRAM_3DS"],
        allowedCardNetworks: ["AMEX", "DISCOVER", "JCB", "MASTERCARD", "VISA"],
        submitOnPaymentAuthorized: ["customer", "billing"],
        emailRequired: true,
        billingAddressRequired: true,
        billingAddressParameters: { "format": "FULL"},
        

        onCancel: function onCancel(errorCode) {
            console.log('onCancel function called with ' +
                errorCode + ' errorCode');
            document.getElementById('debug-message').innerHTML = errorCode;
        },
        // onPaymentAuthorized: function onPaymentAuthorized(paymentData) {
        //     document.getElementById('debug-message').innerHTML = paymentData;
        //     return new Promise(function(resolve, reject) {
        //       // Custom merchant logic
        //       resolve(
        //         { transactionState: 'SUCCESS' }
        //       );
        //       console.log(paymentData);
        //     });
        // },
    },
    applePay: {
    	version: 1,
		supportedNetworks: ["amex", "discover", "masterCard", "visa"],
        // Triggered when the payment sheet is dismissed
        onCancel: function () {
            console.log("onCancel");
        },
        // onPaymentAuthorized: function (payment) {
            // console.log("onPaymentAuthorized payment: " + JSON.stringify(payment));
            // document.getElementById('debug-message').innerHTML = JSON.stringify(payment);
            // /*return {
                // // Possible values: SUCCESS, FAILURE
                // status: "FAILURE",

                // errors: [{
                    // // Possible values: shippingContactInvalid, billingContactInvalid,
                    // // addressUnserviceable
                    // code: "shippingContactInvalid",

                    // // Possible values: phoneNumber, emailAddress, name, phoneticName,
                    // // postalAddress, addressLines, locality, subLocality, postalCode,
                    // // administrativeArea, subAdministrativeArea, country, countryCode
                    // contactField: "phoneNumber",

                    // message: "Invalid phone number"
                // }]
            // };*/
        // },
    }
}


function validateHolder(e) {
    var holder = $('.wpwl-control-cardHolder').val();
    if (holder.trim().length < 2) {
        translateFormText('Invalid card holder');
        return false;
    }
    return true;
}

console.log(wpwlOptions);

//Language Translator
function translateFormText(txt) {
    var sourceText = txt;
    var sourceLang = 'en';
    var targetLang = localStorage.getItem('language');
    var url = "https://translate.googleapis.com/translate_a/single?client=gtx&sl=" + sourceLang + "&tl=" + targetLang + "&dt=t&q=" + encodeURI(sourceText);
    $.getJSON(url, function (data) {
        $('.wpwl-control-cardHolder').addClass('wpwl-has-error').after('<div class="wpwl-hint wpwl-hint-cardHolderError">' + data[0][0][0] + '</div>');
    })
        .fail(function () {
            console.log("error");
        });;

}
