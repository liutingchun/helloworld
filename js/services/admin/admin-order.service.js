'use strict';

(function() {
	angular.module('myApp').factory('adminOrderService', adminOrderService);
	adminOrderService.$inject = ['$http'];
	function adminOrderService($http) {
		var svc = {};
			
		svc.findAllNonArchiveOrder = function(productForm) {
			return $http({
				method : 'POST',
				url : 'backend/admin-order-find-all-non-archive.php'
			});
		}		
				
		svc.updateOrder = function(order) {
			return $http({
				method : 'POST',
				url : 'backend/admin-order-update-order.php',
				data : {
					entity: order
				}
			});
		}	
				
		svc.findReceiptById = function(oId) {
			return $http({
				method : 'POST',
				url : 'backend/admin-order-find-receipt-by-id.php',
				data : {
					oId: oId
				}
			});
		}		
		
		svc.cancelOrder = function(oId) {
			return $http({
				method : 'POST',
				url : 'backend/admin-order-cancel-order-by-id.php',
				data : {
					oId: oId
				}
			});
		}
				
		return svc;
	}
})();
