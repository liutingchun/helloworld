'use strict';

(function() {
	angular.module('myApp').factory('adminBannerService', adminBannerService);
	adminBannerService.$inject = ['$http'];
	function adminBannerService($http) {
		var svc = {};
			
		svc.updateBanner = function(banner) {
			return $http({
				method : 'POST',
				url : 'backend/admin-banner-update-banner.php',
				data : {
					banner: banner
				}
			});
		}
		
		svc.deleteBanner = function(banner) {
			return $http({
				method : 'POST',
				url : 'backend/admin-banner-delete-banner.php',
				data : {
					banner: banner
				}
			});
		}
				
		return svc;
	}
})();
