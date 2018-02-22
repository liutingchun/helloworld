'use strict';
(function() {
	angular.module('myApp').controller('AdminHomeController',AdminHomeController);
	AdminHomeController.$inject = ['$scope', '$rootScope', '$state'];
	function AdminHomeController($scope, $rootScope, $state) {
	
		var ctrl=this;
	
		/*
		** Admin Check
		*/
		if (!$rootScope.loggedInUser || $rootScope.loggedInUser.mLevel != 'admin') {
			$state.go('root.home');
		}
	
	}
})();