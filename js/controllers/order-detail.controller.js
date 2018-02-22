'use strict';
(function() {
	angular.module('myApp').controller('OrderDetailController',OrderDetailController);
	OrderDetailController.$inject = ['$scope', '$state', '$translate', '$stateParams', 'FileUploader', 'orderService', 'productService'];
	function OrderDetailController($scope, $state, $translate, $stateParams, FileUploader, orderService, productService) {	
		var ctrl=this;
									
		/*
		** Declaring
		*/	
		ctrl.serial = $stateParams.serial;
		ctrl.phone = $stateParams.phone;
		ctrl.email = $stateParams.email;
		
		ctrl.orderDetail = null;
		ctrl.orderItemList = [];
		ctrl.productDetailList = {};
					
		ctrl.accordionOpen = {
			hkbank: true,
			payme: false,
			paypal: false
		};
		/*
		** Initialization
		*/
		init();
		function init() {			
			if (!ctrl.serial || !ctrl.phone || !ctrl.email) {
				swal($translate.instant('oym.action.msg.title.warning'),$translate.instant('oym.action.msg.history.failure.no.match'),'warning');
				$state.go('root.form-search-order');
			}
			
			orderService.getOrderDetail(ctrl.serial, ctrl.phone, ctrl.email).then(function(response) { 
				console.log(response.data);
				if (response.data.status == "success") {
					ctrl.orderDetail = response.data.message.orderDetail;
					ctrl.orderDetail.paypal = {
						handling: Math.round(ctrl.orderDetail.oTotal * 0.041 + 2.45),
						total: ctrl.orderDetail.oTotal + Math.round(ctrl.orderDetail.oTotal * 0.041 + 2.45)
					};
					ctrl.orderItemList = response.data.message.orderSub;
					for (var index in ctrl.orderItemList) {
						getProductDetail(ctrl.orderItemList[index].pId);
					}
				}
				else {
					swal($translate.instant('oym.action.msg.title.warning'),$translate.instant(response.data.message),'warning');
					$state.go('root.form-search-order');
				}
			});
		}
		
		/*
		** File Uploader
		*/
		ctrl.uploader = new FileUploader({
            url: 'backend/order-upload-receipt.php',
			formData: [
				{serial: ctrl.serial},
				{phone: ctrl.phone},
				{email: ctrl.email}
			]
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
					
        ctrl.uploader.onCompleteItem = function(fileItem, response, status, headers) {
			if (response.status == "success") {
				swal($translate.instant('oym.action.msg.title.success'),$translate.instant('oym.action.msg.payment.success.upload'),'success');
				
				ctrl.orderDetail = response.message;
			}
			else {
				swal($translate.instant('oym.action.msg.title.failure'),$translate.instant(response.message),'error');
			}
        };
		
		/*
		** Front-end Action Handle
		*/
		ctrl.paypalCallback = function(result) {
			orderService.paypalCheckout(result, ctrl.serial, ctrl.phone, ctrl.email).then(function(response) {
				if (response.data.status == "success") {
					swal($translate.instant('oym.action.msg.title.success'),$translate.instant('oym.action.msg.paypal.checkout.success'),'success');
					
					ctrl.orderDetail = response.data.message;
				}
				else {
					swal($translate.instant('oym.action.msg.title.failure'),$translate.instant(response.message),'error');
				}
			});
		}
		/*
		** Utility
		*/
		function getProductDetail(pid) {
			productService.findProductByPid(pid).then(function(response) {
				ctrl.productDetailList[pid] = response.data[0];
			});
		}
		
	}
})();