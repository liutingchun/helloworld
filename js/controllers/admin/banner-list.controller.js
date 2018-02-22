'use strict';
(function() {
	angular.module('myApp').controller('BannerListController',BannerListController);
	BannerListController.$inject = ['$scope', '$state', '$translate', 'FileUploader', 'generalService', 'adminGeneralService', 'adminBannerService'];
	function BannerListController($scope, $state, $translate, FileUploader, generalService, adminGeneralService, adminBannerService) {
	
		var ctrl=this;
				
		/*	
		** Declaring
		*/
		ctrl.bannerList = [];
	
		ctrl.selection = {
			'target': [],
			'tag': [],
			'position': []
		};
	
		/*
		** Initialization
		*/
		init();
		function init() {
			getBanner();
			
			adminGeneralService.findSelectionByCategory('bannerTarget').then(function(response) {
				ctrl.selection.target = response.data;
			});
			
			adminGeneralService.findSelectionByCategory('productTag').then(function(response) {
				ctrl.selection.tag = response.data;
			});
			
			adminGeneralService.findSelectionByCategory('bannerPosition').then(function(response) {
				ctrl.selection.position = response.data;
			});
		}
		
		
		/*
		** Front-end Action Handler
		*/
		ctrl.updateBanner = function(banner) {
			adminBannerService.updateBanner(banner).then(function(response) {
				if (response.data.status == 'success') {
					swal($translate.instant('oym.action.msg.title.success'),$translate.instant('oym.add.banner.msg.update.success'),'success');
					getBanner();
				}
				else {
					swal($translate.instant('oym.action.msg.title.failure'),$translate.instant('oym.action.msg.admin.error.unknown'),'error');
				}
			});
		}
		
		ctrl.deleteBanner = function(banner) {
			adminBannerService.deleteBanner(banner).then(function(response) {
				if (response.data.status == 'success') {
					swal($translate.instant('oym.action.msg.title.success'),$translate.instant('oym.add.banner.msg.delete.success'),'success');
					$state.reload();
				}
				else {
					swal($translate.instant('oym.action.msg.title.failure'),$translate.instant('oym.action.msg.admin.error.unknown'),'error');
				}
			});
		}
		
		ctrl.moveForward = function(input) {
			var index = parseInt(input);
			var temp = ctrl.bannerList[index-1].bOrder;
			ctrl.bannerList[index-1].bOrder = ctrl.bannerList[index].bOrder;
			ctrl.bannerList[index].bOrder = temp;
			
			adminBannerService.updateBanner(ctrl.bannerList[index-1]).then(function(response) {
				if (response.data.status == 'success') {
					adminBannerService.updateBanner(ctrl.bannerList[index]).then(function(response2) {
						if (response2.data.status == 'success') {
							swal($translate.instant('oym.action.msg.title.success'),$translate.instant('oym.add.banner.msg.update.success'),'success');
							getBanner();
							//$state.reload();
						}
						else {
							swal($translate.instant('oym.action.msg.title.failure'),$translate.instant('oym.action.msg.admin.error.unknown'),'error');
						}
					});
				}
				else {
					swal($translate.instant('oym.action.msg.title.failure'),$translate.instant('oym.action.msg.admin.error.unknown'),'error');
				}
			});
		}
		
		ctrl.moveBackward = function(input) {
			var index = parseInt(input);
			var temp = ctrl.bannerList[index+1].bOrder;
			ctrl.bannerList[index+1].bOrder = ctrl.bannerList[index].bOrder;
			ctrl.bannerList[index].bOrder = temp;
			
			adminBannerService.updateBanner(ctrl.bannerList[index+1]).then(function(response) {
				if (response.data.status == 'success') {
					adminBannerService.updateBanner(ctrl.bannerList[index]).then(function(response2) {
						if (response2.data.status == 'success') {
							swal($translate.instant('oym.action.msg.title.success'),$translate.instant('oym.add.banner.msg.update.success'),'success');
							getBanner();
							//$state.reload();
						}
						else {
							swal($translate.instant('oym.action.msg.title.failure'),$translate.instant('oym.action.msg.admin.error.unknown'),'error');
						}
					});
				}
				else {
					swal($translate.instant('oym.action.msg.title.failure'),$translate.instant('oym.action.msg.admin.error.unknown'),'error');
				}
			});
		}
				
		/*
		** Utilities
		*/
		function getBanner() {
			generalService.getBanner().then(function(response) {
				ctrl.tempList = response.data;
				
				for (var index in ctrl.tempList) {
					ctrl.bannerList[index] = ctrl.tempList[index];
					ctrl.bannerList[index].index = index;
				}
			});
		}
	}
})();