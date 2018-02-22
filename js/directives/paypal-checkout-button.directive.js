'use strict';
(function() {
	angular.module('myApp').directive("paypalCheckoutButton", function() {
		return {
			restrict: "E",
			template: "<div id=\"paypal-button-container\"></div>",
			scope: {
				amount: "=",
				callback: "="
			},
			link: function(scope, element, attrs){
				paypal.Button.render({

					locale: 'en_US',
				
					env: 'production', // sandbox | production

					// PayPal Client IDs - replace with your own
					// Create a PayPal app: https://developer.paypal.com/developer/applications/create
					client: {
						sandbox:    'Ac0940PQwUHbroe9amq4QzESxMIRAP7Co-Xa7Vox3GxeWDDJgGQX69rmAcKsDFrh4N0Q2oeWbqYMqowe',
						production: 'AUJbBKab4qijtNHdmfqrbunczGdN5wrYnj9LI5NM8aghpgtMcafk13GhbviYTFMTklQC9sz9mNuKUVF4'
					},

					// Show the buyer a 'Pay Now' button in the checkout flow
					commit: true,

					// payment() is called when the button is clicked
					payment: function(data, actions) {

						// Make a call to the REST api to create the payment
						return actions.payment.create({
							payment: {
								id: 'test id',
								transactions: [
									{
										amount: { total: scope.amount, currency: 'HKD' }
									}
								]
							}
						});
					},

					// onAuthorize() is called when the buyer approves the payment
					onAuthorize: function(data, actions) {

						// Make a call to the REST api to execute the payment
						return actions.payment.execute().then(function(response) {
							scope.callback(response);
						});
					}

				}, '#paypal-button-container');
			}
		};
	});
})();