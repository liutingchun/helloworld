'use strict';

(function() {
	angular.module('myApp').factory('orderService', orderService);
	orderService.$inject = ['$http'];
	function orderService($http) {
		var svc = {};
			
		svc.getOrderByLoggedInAccount = function() {
			return $http({
				method : 'POST',
				url : 'backend/order-find-by-logged-in-account.php'
			});
		}
			
		svc.getOrderByPhoneAndEmail = function(phone, email) {
			return $http({
				method : 'POST',
				url : 'backend/order-find-by-phone-and-email.php',
				data : {
					phone: phone,
					email: email
				}
			});
		}
		
		svc.getOrderDetail = function(serial, phone, email) {
			return $http({
				method : 'POST',
				url : 'backend/order-find-detail.php',
				data : {
					serial: serial,
					phone: phone,
					email: email
				}
			});
		}
		
		svc.paypalCheckout = function(paypalResponse, serial, phone, email) {
			return $http({
				method : 'POST',
				url : 'backend/order-paypal-checkout.php',
				data : {
					paypalResponse: paypalResponse,
					serial: serial,
					phone: phone,
					email: email
				}
			});
		}
				
		return svc;
	}
})();
