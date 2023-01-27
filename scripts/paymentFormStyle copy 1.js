var wpwlOptions = {
    style: "card",
    //locale: 'it',
    locale: localStorage.getItem('language'),
    onReady: function (e) {
        $('.wpwl-form-card').find('.wpwl-button-pay').on('click', function (e) {
            //checkout validation
            // var valid = checkoutFieldDetailsValidation();
            // console.log(valid);
            // if (valid === false) {
            //     return false;
            // } else {

            validateHolder(e);
            // }

        });
    },
    onBeforeSubmitCard: function (e) {
        return validateHolder(e);
    },
    googlePay: {
        gatewayMerchantId: localStorage.getItem('entityId'),
        //gatewayMerchantId: '8ac7a4c86a304582016a30b41682019b',
        allowedAuthMethods: ["PAN_ONLY", "CRYPTOGRAM_3DS"],
        allowedCardNetworks: ["AMEX", "DISCOVER", "JCB", "MASTERCARD", "VISA"],
        submitOnPaymentAuthorized: ["customer", "billing"],
        emailRequired: true,
        billingAddressRequired: true,
        billingAddressParameters: { "format": "FULL", phoneNumberRequired: false },
        // merchantId: "12345678901234567890",
        merchantName: "Example Merchant",
        

        onCancel: function onCancel(errorCode) {
            console.log('onCancel function called with ' +
                errorCode + ' errorCode');
        },
        // onPaymentDataChanged: function onPaymentDataChanged(intermediatePaymentData) {
        //     console.log('intermediatePaymentData :- ', intermediatePaymentData);

        //     var returnMe = new Promise(function (resolve, reject) {
        //         var paymentDataRequestUpdate = {};
        //         var shippingOptionData = intermediatePaymentData.shippingOptionData;
        //         resolve(paymentDataRequestUpdate);
        //     });
        //     return returnMe;
        // },

    },
    applePay: {
        displayName: "MyStore",
        // Possible values: canMakePayments, canMakePaymentsWithActiveCard
       // checkAvailability: "canMakePaymentsWithActiveCard",
        // Required if checkAvailability is canMakePaymentsWithActiveCard
       // merchantIdentifier: "merchant.com.test",
        // total: getTotal(),
        //supportedNetworks: ["amex", "discover", "masterCard", "visa"],
        //requiredBillingContactFields: ["postalAddress"],
        // Triggered when the payment sheet is dismissed
        onCancel: function () {
            console.log("onCancel");
        },

    }
}


// function processPayment(paymentData) {
//     console.log('payment process');
//     paymentToken = paymentData.paymentMethodData.tokenizationData.token;
//     if (paymentToken != null) {
//         console.log('Received google pay data');
//         // send paymentToken to your backend server which will be parsed and used to
//         // create a Radial CreditCardAuthRequest using the WalletPaymentInformation data block as mentioned here.
//     } else {
//         alert("Please select appropriate payment method");
//     }
// }

function validateHolder(e) {
    var holder = $('.wpwl-control-cardHolder').val();
    if (holder.trim().length < 2) {
        //$('.wpwl-control-cardHolder').addClass('wpwl-has-error').after('<div class="wpwl-hint wpwl-hint-cardHolderError">Invalid card holder</div>');
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

