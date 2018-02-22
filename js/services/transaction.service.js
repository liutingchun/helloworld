'use strict';

(function() {
	angular.module('myApp').factory('transactionService', transactionService);
	transactionService.$inject = ['$http'];
	function transactionService($http) {
		var svc = {};
			
		svc.submitOrder = function(orderInfoForm) {
			return $http({
				method : 'POST',
				url : 'backend/checkout-submit-order.php',
				data: {
					orderInfoForm: orderInfoForm
				}
			});
		}	
				
		return svc;
	}
})();
