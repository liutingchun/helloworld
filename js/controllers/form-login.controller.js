'use strict';
(function() {
	angular.module('myApp').controller('FormLoginController',FormLoginController);
	FormLoginController.$inject = ['$scope','$state','$window','$translate','authenticationService'];
	function FormLoginController($scope,$state,$window,$translate,authenticationService) {
	
		var ctrl=this;
				
		/*	
		** Declaring
		*/
		ctrl.username = null;
		ctrl.password = null;
		
		/*
		** Front-end Action Handler
		*/
		ctrl.login = function() {	
			if (ctrl.username && ctrl.password) {
				authenticationService.login(ctrl.username, ctrl.password).then(function(response) {
					if (response.data.status == 'success') {
						$state.go("root.home",{},{reload: "root"})
					}
					else {
						swal($translate.instant('oym.action.msg.title.failure'),$translate.instant(response.data.message),'error');
					}
				});
			}
		}				
	}
})();