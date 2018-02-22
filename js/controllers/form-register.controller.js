'use strict';
(function() {
	angular.module('myApp').controller('FormRegisterController',FormRegisterController);
	FormRegisterController.$inject = ['$scope', '$state', '$translate', 'generalService'];
	function FormRegisterController($scope, $state, $translate, generalService) {
	
		var ctrl=this;
						
		/*	
		** Declaring
		*/
		ctrl.registerForm = {};		
		ctrl.forceDisplayError = false;		
		
		/*
		** Front-end Action Handler
		*/
		
		ctrl.submitRegistration = function($valid) {
			if ($valid) {
				generalService.addMember(ctrl.registerForm).then(function(response) {
					if (response.data.status == 'success') {
						swal($translate.instant('oym.action.msg.title.success'),$translate.instant('oym.action.msg.register.success'),'success');
						$state.go("root.form-login");
					}
					else {
						swal($translate.instant('oym.action.msg.title.failure'),$translate.instant(response.data.message),'error');
					}
				});
			}
			else {
				ctrl.forceDisplayError = true;
				swal($translate.instant('oym.action.msg.title.failure'),$translate.instant('oym.action.msg.register.failure.missing.info'),'error');
			}
		}

	}
})();