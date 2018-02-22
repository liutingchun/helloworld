'use strict';
(function() {
	angular.module('myApp').controller('MenuBarController',MenuBarController);
	MenuBarController.$inject = ['$scope','$rootScope','$translate','$state','productService','authenticationService'];
	function MenuBarController($scope,$rootScope,$translate,$state,productService,authenticationService) {
		
		var ctrl=this;
			
		/*
		** Declaring
		*/		
		ctrl.productCategoryList = [];			
		ctrl.loggedInUser = null;	
								
		/*
		** Initialization
		*/
		init();
		function init() {		
			productService.getAllVisibleDistinctCategory().then(function(response) {
				ctrl.productCategoryList = response.data;
			});	
			
			if ($rootScope.loggedInUser != null) {
				ctrl.loggedInUser = $rootScope.loggedInUser;
			}
		}
			
		/*
		** Front-end Action Handler
		*/
		ctrl.changeLanguage = function(langKey) {
			$translate.use(langKey);
			$state.reload();
		}
		
		ctrl.logout = function() {
			authenticationService.logout().then(function(response){
				$rootScope.loggedInUser = null;
				$state.go("root.home",{},{reload: "root"})
			});
		}
			
	}
})();