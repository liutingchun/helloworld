'use strict';
(function() {
	angular.module('myApp').controller('FormAddBannerController',FormAddBannerController);
	FormAddBannerController.$inject = ['$scope', '$state', '$translate', 'FileUploader', 'adminGeneralService'];
	function FormAddBannerController($scope, $state, $translate, FileUploader, adminGeneralService) {
	
		var ctrl=this;
				
		/*	
		** Declaring
		*/
		ctrl.bannerForm = {};		
		ctrl.forceDisplayError = false;		
				
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
		** File Uploader
		*/
		ctrl.uploader = new FileUploader({
            url: 'backend/admin-banner-upload-image.php'
        });
		
        ctrl.uploader.filters.push({
            name: 'imageFilter',
            fn: function(item /*{File|FileLikeObject}*/, options) {
                var type = '|' + item.type.slice(item.type.lastIndexOf('/') + 1) + '|';
                return '|jpg|png|jpeg|bmp|gif|'.indexOf(type) !== -1;
            }
        });
		
        ctrl.uploader.onWhenAddingFileFailed = function(item /*{File|FileLikeObject}*/, filter, options) {
			swal($translate.instant('oym.action.msg.title.failure'),$translate.instant('oym.action.msg.payment.failure.type'),'error');
        };
			
		ctrl.uploader.onBeforeUploadItem = function(item) {
            item.formData.push(
				{target: ctrl.bannerForm.target},
				{url: ctrl.bannerForm.url},
				{position: ctrl.bannerForm.position}
			);
        };
						
        ctrl.uploader.onCompleteItem = function(fileItem, response, status, headers) {
			if (response.status == 'success') {
				swal($translate.instant('oym.action.msg.title.success'),$translate.instant('oym.action.msg.admin.banner.add.success'),'success');
				$state.go('root.admin.banner-list');
			}
			else {
				swal($translate.instant('oym.action.msg.title.failure'),$translate.instant('oym.action.msg.admin.error.unknown'),'error');
			}
        };
		
		
		/*
		** Front-end Action Handler
		*/

		

	}
})();