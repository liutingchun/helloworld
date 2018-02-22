'use strict';

(function() {
	angular.module('myApp').factory('cartService', cartService);
	cartService.$inject = ['$http'];
	function cartService($http) {
		var svc = {};
			
		svc.addProductToCart = function(pid, productStatus, productColor, productSize) {
			return $http({
				method : 'POST',
				url : 'backend/cart-add-product-to-cart.php',
				data: {
					pid: pid,
					productStatus: productStatus,
					productColor: productColor,
					productSize: productSize
				}
			});
		}	
		
		svc.removeProductFromCart = function(pid, productStatus, productColor, productSize) {
			return $http({
				method : 'POST',
				url : 'backend/cart-remove-product-from-cart.php',
				data: {
					pid: pid,
					productStatus: productStatus,
					productColor: productColor,
					productSize: productSize
				}
			});
		}
		
		svc.getAllProductInCart = function() {
			return $http({
				method : 'POST',
				url : 'backend/cart-get-all-product-in-cart.php'
			});
		}
				
		svc.removeSoldOutItem = function() {
			return $http({
				method : 'POST',
				url : 'backend/cart-remove-sold-out-item.php'
			});
		}		
		
		svc.checkDiscountGroup = function() {
			return $http({
				method : 'POST',
				url : 'backend/cart-check-discount-group.php'
			});
		}
				
		return svc;
	}
})();
