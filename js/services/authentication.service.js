'use strict';

(function() {
	angular.module('myApp').factory('authenticationService', authenticationService);
	authenticationService.$inject = ['$http'];
	function authenticationService($http) {
		var svc = {};
				
		svc.getLoggedInUser = function() {
			return $http({
				method : 'POST',
				url : 'backend/authentication-get-logged-in-user.php'
			});
		}		
				
		svc.login = function(username, password) {
			return $http({
				method : 'POST',
				url : 'backend/authentication-login.php',
				data: {
					username: username,
					password: password
				}
			});
		}
		
		svc.logout = function() {
			return $http({
				method : 'POST',
				url : 'backend/authentication-logout.php'
			});
		}	
				
		return svc;
	}
})();
