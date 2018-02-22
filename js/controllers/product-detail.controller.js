'use strict';
(function() {
	angular.module('myApp').controller('ProductDetailController',ProductDetailController);
	ProductDetailController.$inject = ['$scope','$state','$stateParams','$translate','productService','cartService'];
	function ProductDetailController($scope,$state,$stateParams,$translate,productService,cartService) {
	
		var ctrl=this;
									
		/*
		** Declaring
		*/	
		ctrl.pid = $stateParams.pid;
		
		ctrl.selectedProduct = null;
		ctrl.productImageUrl = [];	
		ctrl.productDescription = [];
				
		ctrl.availableBuyStatus = [];
		ctrl.availableBuyColor = [];
		ctrl.availableBuySize = [];
		
		ctrl.availableDiscountGroup = [];
		
		/*
		** Initialization
		*/
		init();
		function init() {
			productService.findProductByPid(ctrl.pid).then(function(response){
				ctrl.selectedProduct = response.data[0];
				
				if (ctrl.selectedProduct) {
					if (ctrl.selectedProduct.description) {
						ctrl.productDescription = ctrl.selectedProduct.description.split('|');
					}					
					
					for (var i = 0; i < ctrl.selectedProduct.image; i++) {
						ctrl.productImageUrl[i] = 'img/product/' + ctrl.selectedProduct.pid + '/' + i + '.jpg';
					}
					
					productService.getAvailableInventory(ctrl.selectedProduct.pid).then(function(result) {
						if (result.data.length > 0) {
							for (var index in result.data) {
								if (ctrl.availableBuyStatus.indexOf(result.data[index].productStatus) == -1) {
									ctrl.availableBuyStatus.push(result.data[index].productStatus);
									ctrl.availableBuyColor[result.data[index].productStatus] = [];
									ctrl.availableBuySize[result.data[index].productStatus] = [];
								}
								
								if (ctrl.availableBuyColor[result.data[index].productStatus].indexOf(result.data[index].productColor) == -1) {
									ctrl.availableBuyColor[result.data[index].productStatus].push(result.data[index].productColor);
									ctrl.availableBuySize[result.data[index].productStatus][result.data[index].productColor] = [];
								}
								
								ctrl.availableBuySize[result.data[index].productStatus][result.data[index].productColor].push({
									size: result.data[index].productSize,
									price: result.data[index].productPrice
								});
							}
						}
					});
					
					productService.findDiscountGroupJoinedByPid(ctrl.selectedProduct.pid).then(function(response) {
						ctrl.availableDiscountGroup = response.data;
					});
					
					productService.updateViewCount(ctrl.selectedProduct.pid).then(function(response) {
						
					});
				}
				else {
					$state.go('root.product-grid');
				}				
			});
		}

		/*
		** Front-end Action Handle
		*/
		ctrl.addToCart = function() {
			cartService.addProductToCart(
				ctrl.selectedProduct.pid, 
				ctrl.selectedBuyStatus, 
				ctrl.selectedBuyColor, 
				ctrl.selectedBuySize.size
			).then(function(response) {
				console.log(response.data);
				
				if (response.data.status == 'success') {
					swal($translate.instant('oym.action.msg.title.success'),$translate.instant('oym.action.msg.cart.add.success'),'success');
				}
				else {
					swal($translate.instant('oym.action.msg.title.failure'),$translate.instant(response.data.message),'error');
				}
			});
		}
	}
})();