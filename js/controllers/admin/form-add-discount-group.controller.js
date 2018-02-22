'use strict';
(function() {
	angular.module('myApp').controller('FormAddDiscountGroupController',FormAddDiscountGroupController);
	FormAddDiscountGroupController.$inject = ['$scope', '$state', '$translate', 'adminProductService', 'adminGeneralService'];
	function FormAddDiscountGroupController($scope, $state, $translate, adminProductService, adminGeneralService) {
	
		var ctrl=this;
				
		/*	
		** Declaring
		*/
		ctrl.discountGroupForm = {
			groupName: null,
			type: null,
			discountValue: null,
			triggerQuantity: null,
			descriptionEn: null,
			descriptionZhTW: null,
			descriptionZhCN: null
		};
		
		ctrl.selection = {
			'type': []
		};
	
		/*
		** Initialization
		*/
		init();
		function init() {			
			adminGeneralService.findSelectionByCategory('discountGroupType').then(function(response) {
				ctrl.selection.type = response.data;
			});
		}
	
			
		/*
		** Front-end Action Handler
		*/
		ctrl.submitForm = function() {
			adminProductService.addDiscountGroup(ctrl.discountGroupForm).then(function(response) {
				if (response.data.status == "success") {
					swal($translate.instant('oym.action.msg.title.success'),$translate.instant('oym.action.msg.admin.discount.group.add.success'),'success');
					$state.go('root.admin.discount-group-list');
				}
				else {
					swal($translate.instant('oym.action.msg.title.failure'),$translate.instant('oym.action.msg.admin.error.unknown'),'error');
					console.log(response.data);
				}
			});
		}
		

	}
})();