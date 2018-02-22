'use strict';

(function() {
	angular.module('myApp').factory('generalService', generalService);
	generalService.$inject = ['$http'];
	function generalService($http) {
		var svc = {};
				
		svc.getBanner = function() {
			return $http({
				method : 'POST',
				url : 'backend/banner-find-all.php'
			});
		}
				
		svc.addMember = function(registerForm) {
			return $http({
				method : 'POST',
				url : 'backend/register-add-member.php',
				data : {
					registerForm: registerForm
				}
			});
		}		
				
		return svc;
	}
})();
