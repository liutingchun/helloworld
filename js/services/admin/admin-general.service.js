'use strict';

(function() {
	angular.module('myApp').factory('adminGeneralService', adminGeneralService);
	adminGeneralService.$inject = ['$http'];
	function adminGeneralService($http) {
		var svc = {};
			
		svc.findSelectionByCategory = function(sCategory) {
			return $http({
				method : 'POST',
				url : 'backend/admin-selection-find-selection-by-category.php',
				data : {
					sCategory: sCategory
				}
			});
		}
				
		return svc;
	}
})();
