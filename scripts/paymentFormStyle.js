var wpwlOptions = {
    style: "card",
    //locale: 'it',
    locale: localStorage.getItem('language'),
    onReady: function (e) {
    	// Validate card holder only when submitting card number directly (not for ApplePay)
        $('.wpwl-form-card').find('.wpwl-button-pay').on('click', function (e) {
            validateHolder(e);
        });
    },
    
        
   onLoadThreeDIframe: function () {
       console.log('Global');
       document.getElementsByClassName('wpwl-container-virtualAccount-APPLEPAY')[0].style.display = 'none';
       document.getElementsByClassName('gpay-card-info-container')[0].style.display = 'none';
       document.getElementsByClassName('wpwl-form-card')[0].style.display = 'none';
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
    },
    applePay: {
    	version: 1,
    	style : "black",
		supportedNetworks: ["amex", "discover", "masterCard", "visa"],
        // Triggered when the payment sheet is dismissed
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
