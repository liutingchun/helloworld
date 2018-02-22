'use strict';
(function() {
	angular.module('myApp').controller('DiscountGroupListController',DiscountGroupListController);
	DiscountGroupListController.$inject = ['$scope','$state','$translate','adminGeneralService','adminProductService'];
	function DiscountGroupListController($scope,$state,$translate,adminGeneralService,adminProductService) {
	
		var ctrl=this;
									
		/*
		** Declaring
		*/	
		ctrl.accordionOpen = {};
		ctrl.discountGroupList = [];
		
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
		
			adminProductService.findAllDiscountGroup().then(function(response) {
				ctrl.discountGroupList = response.data;
				for (var index in ctrl.discountGroupList) {
					ctrl.accordionOpen[ctrl.discountGroupList[index].dId] = false;
				}
			});
		}
		
		/*
		** Front-end Action Handle
		*/		
		ctrl.updateDiscountGroup = function(discountGroup) {
			adminProductService.updateDiscountGroup(discountGroup).then(function(response) {
				if (response.data.status == "success") {
					swal($translate.instant('oym.action.msg.title.success'),$translate.instant('oym.action.msg.admin.discount.group.update.success'),'success');
					$state.reload();
				}
				else {
					swal($translate.instant('oym.action.msg.title.failure'),$translate.instant('oym.action.msg.admin.error.unknown'),'error');
					console.log(response.data);
				}
			});
		}
		
		/*
		** Utilites
		*/

	}
})();