'use strict';
(function() {
	angular.module('myApp').controller('RootController',RootController);
	RootController.$inject = ['loggedInUserRequest','$scope','$rootScope'];
	function RootController(loggedInUserRequest,$scope,$rootScope) {
		
		var ctrl=this;
			
		/*
		** Declaring
		*/		
		ctrl.productCategoryList = [];			
		ctrl.loggedInUser = null;	
				
		console.log(loggedInUserRequest.data);		
				
		/*
		** Initialization
		*/
		init();
		function init() {					
			if (loggedInUserRequest.data.status == 'success') {
				ctrl.loggedInUser = loggedInUserRequest.data.message;
				$rootScope.loggedInUser = ctrl.loggedInUser;
			}
		}
					
	}
})();