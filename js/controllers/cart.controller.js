'use strict';
(function() {
	angular.module('myApp').controller('CartController',CartController);
	CartController.$inject = ['$scope','$rootScope','$state','$stateParams','$translate','productService','cartService','transactionService'];
	function CartController($scope,$rootScope,$state,$stateParams,$translate,productService,cartService,transactionService) {
	
		var ctrl=this;
									
		/*
		** Declaring
		*/	
		ctrl.cartItemList = [];
		ctrl.productDetailList = {};
		ctrl.orderProperty = {
			'quantity': 0,
			'priceOrigin': 0,
			'discount': 0,
			'coupon': 0,
			'payableAmount': 0
		};
			
		ctrl.loadingCount = 0;	
		ctrl.checkingOut = false;	
		ctrl.orderForm = {
			'name': null,
			'phone': null,
			'email': null,
			'country': null,
			'locale': null
		};
			
		ctrl.itemDiscount = {};
		/*
		** Initialization
		*/
		init();
		function init() {					
			if ($rootScope.loggedInUser) {
				ctrl.orderForm = {
					'name': $rootScope.loggedInUser.mFname,
					'phone': $rootScope.loggedInUser.mPhone,
					'email': $rootScope.loggedInUser.mEmail,
					'country': $rootScope.loggedInUser.mRegion
				};
			}
		
			cartService.removeSoldOutItem().then(function(response) {
				if (response.data.status == 'success') {
					swal($translate.instant('oym.action.msg.title.warning'),$translate.instant('oym.action.msg.trans.warning.no.stock'),'warning');
				}
				
				cartService.getAllProductInCart().then(function(response){
					var temp = response.data.message;
						
					for (var pid in temp) {
						for (var status in temp[pid]) {
							for (var color in temp[pid][status]) {
								for (var size in temp[pid][status][color]) {
									var quantity = temp[pid][status][color][size];
									
									ctrl.cartItemList.push({
										pid: pid,
										status: status,
										color: color,
										size: size,
										quantity: quantity,
										price: 0
									});
									
									ctrl.loadingCount += 2;
									
									getItemDetail(pid);
									getItemPrice(ctrl.cartItemList.length - 1, pid, status, color, size);
									
								}
							}
						}
					}
					
					postLoadingProcessing();
				});
			});
		}
		
		/*
		** Front-end Action Handler
		*/
		ctrl.removeFromCart = function(index, pid, status, color, size) {
			cartService.removeProductFromCart(pid, status, color, size).then(function(response){
				ctrl.cartItemList.splice(index, 1);
				computeOrderProperty();
			});
		}
		
		ctrl.checkOut = function() {
			ctrl.checkingOut = true;
		}
		
		ctrl.cancelCheckOut = function() {			
			ctrl.checkingOut = false;
		}
		
		ctrl.submitOrder = function(valid) {
			if (valid) {
				swal({
					title: $translate.instant('oym.checkout.warning.1'),
					text: (
						$translate.instant('oym.checkout.warning.2').concat(ctrl.orderForm.phone) +
						$translate.instant('oym.checkout.warning.3').concat(ctrl.orderForm.email) + 
						$translate.instant('oym.checkout.warning.4')
					),
					type: 'warning',
					showCancelButton: true,
					confirmButtonText: $translate.instant('oym.checkout.button.submit'),
					cancelButtonText: $translate.instant('oym.checkout.button.cancel')
				}).then(function (result) {
					ctrl.orderForm.locale = $translate.use();
					
					transactionService.submitOrder(ctrl.orderForm).then(function(response) {
						if (response.data.status == 'success') {
							swal($translate.instant('oym.action.msg.title.success'),$translate.instant('oym.action.msg.trans.success'),'success');
							$state.go('root.order-detail', {
								'serial': response.data.message.serial, 
								'phone': response.data.message.phone, 
								'email': response.data.message.email
							});
						}
						else if (response.data.status == 'failure') {
							swal($translate.instant('oym.action.msg.title.failure'),$translate.instant(response.data.message),'error');
						}
						else {
							swal($translate.instant('oym.action.msg.title.failure'),$translate.instant('oym.action.msg.trans.failure.unknown'),'error');
						}
					});
				});
			}
			else {
				swal($translate.instant('oym.action.msg.title.failure'),$translate.instant('oym.action.msg.trans.failure.missing.info'),'error');
			}
		}
				
		/*
		** Utilities
		*/			
		function getItemDetail(pid) {
			productService.findProductByPid(pid).then(function(response) {
				ctrl.productDetailList[pid] = response.data[0];
				
				postLoadingProcessing();
			});
		}

		function getItemPrice(index, pid, status, color, size) {
			productService.getInventorySubPrice(pid, status, color, size).then(function(response) {
				ctrl.cartItemList[index].price = response.data.productPrice;
				
				postLoadingProcessing();
			});
		}
		
		function postLoadingProcessing() {
			if (ctrl.loadingCount > 0) {
				ctrl.loadingCount--;
			}
			else {
				computeOrderProperty();
			}
		}
				
		function computeOrderProperty() {
			cartService.checkDiscountGroup().then(function(response) {		
				ctrl.itemDiscount = response.data.message;
				
				ctrl.orderProperty = {
					'quantity': 0,
					'priceOrigin': 0,
					'discount': 0,
					'coupon': 0,
					'payableAmount': 0
				};
				
				console.log(ctrl.cartItemList);
				
				for (var index in ctrl.cartItemList) {
					var item = ctrl.cartItemList[index];
					
					ctrl.orderProperty.quantity += item.quantity;
					
					if (ctrl.productDetailList[item.pid].fakeprice ) {
						ctrl.orderProperty.priceOrigin += ctrl.productDetailList[item.pid].fakeprice * item.quantity;
						ctrl.orderProperty.discount += (ctrl.productDetailList[item.pid].fakeprice - item.price) * item.quantity;
					}
					else {
						ctrl.orderProperty.priceOrigin += item.price * item.quantity;
					}
					
					if (
						ctrl.itemDiscount[item.pid] && 
						ctrl.itemDiscount[item.pid][item.status] && 
						ctrl.itemDiscount[item.pid][item.status][item.color] && 
						ctrl.itemDiscount[item.pid][item.status][item.color][item.size]
					) {
						ctrl.orderProperty.coupon += ctrl.itemDiscount[item.pid][item.status][item.color][item.size] * item.quantity;
						ctrl.orderProperty.payableAmount -= ctrl.itemDiscount[item.pid][item.status][item.color][item.size] * item.quantity;
					}
					
					ctrl.orderProperty.payableAmount += item.price * item.quantity;
				}
			});
		}
	}
})();