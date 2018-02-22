'use strict';

(function() {
	angular.module('myApp').factory('adminBatchService', adminBatchService);
	adminBatchService.$inject = ['$http'];
	function adminBatchService($http) {
		var svc = {};
			
		svc.generateTranslateHash = function() {
			return $http({
				method : 'POST',
				url : 'backend/admin-batch-generate-translate-hash.php'
			});
		}		
		
		svc.sendTestEmail = function() {
			return $http({
				method : 'POST',
				url : 'backend/admin-batch-send-test-email.php'
			});
		}
				
		return svc;
	}
})();
