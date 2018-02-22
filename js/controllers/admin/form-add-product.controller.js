'use strict';
(function() {
	angular.module('myApp').controller('FormAddProductController',FormAddProductController);
	FormAddProductController.$inject = ['$scope', '$state', '$translate', 'adminGeneralService', 'adminProductService'];
	function FormAddProductController($scope, $state, $translate, adminGeneralService, adminProductService) {
	
		var ctrl=this;
				
		/*	
		** Declaring
		*/
		ctrl.productForm = {};		
		ctrl.forceDisplayError = false;		
				
		ctrl.selection = {
			'brand': [],
			'category': [],
			'gender': []
		};
	
		/*
		** Initialization
		*/
		
		init();
		function init() {
			adminGeneralService.findSelectionByCategory('productBrand').then(function(response) {
				ctrl.selection.brand = response.data;
			});
			
			adminGeneralService.findSelectionByCategory('productCategory').then(function(response) {
				ctrl.selection.category = response.data;
			});
			
			adminGeneralService.findSelectionByCategory('productGender').then(function(response) {
				ctrl.selection.gender = response.data;
			});
		}
		
		/*
		** Front-end Action Handler
		*/
		
		ctrl.submitForm = function($valid) {
			if ($valid) {
				adminProductService.addProduct(ctrl.productForm).then(function(response) {
					if (response.data.status == 'success') {
						swal($translate.instant('oym.action.msg.title.success'),$translate.instant('oym.action.msg.admin.product.success'),'success');
						$state.go('root.admin.form-update-product', {'pid': response.data.message.pid});
					}
					else {
						swal($translate.instant('oym.action.msg.title.failure'),$translate.instant('oym.action.msg.admin.product.failutre.missing.info'),'error');
					}
				});
			}
			else {
				ctrl.forceDisplayError = true;
				swal($translate.instant('oym.action.msg.title.failure'),$translate.instant('oym.action.msg.admin.product.failutre.missing.info'),'error');
			}
		}
		
		/*ctrl.submitRegistration = function($valid) {
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
		}*/

	}
})();