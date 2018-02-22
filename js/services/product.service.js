'use strict';

(function() {
	angular.module('myApp').factory('productService', productService);
	productService.$inject = ['$http'];
	function productService($http) {
		var svc = {};
			
		svc.findProductByPid = function(pid) {
			return $http({
				method : 'POST',
				url : 'backend/product-find-product-by-pid.php',
				data: {
					pid: pid
				}
			});
		}
		
		svc.updateViewCount = function(pid) {
			return $http({
				method : 'POST',
				url : 'backend/product-update-view-count.php',
				data: {
					pid: pid
				}
			});
		}
			
		svc.getAllVisibleProduct = function() {
			return $http({
				method : 'POST',
				url : 'backend/product-get-all-visible-product.php'
			});
		}

		svc.getAllVisibleDistinctCategory = function() {
			return $http({
				method : 'POST',
				url : 'backend/product-get-all-visible-distinct-category.php'
			});
		}
				
		svc.getAvailableInventory = function(pid) {
			return $http({
				method : 'POST',
				url : 'backend/product-get-available-inventory.php',
				data: {
					pid: pid
				}
			});
		}
		
		svc.getInventorySubPrice = function(pid, productStatus, productColor, productSize) {
			return $http({
				method : 'POST',
				url : 'backend/product-get-inventory-sub-price.php',
				data: {
					pid: pid,
					productStatus: productStatus,
					productColor: productColor,
					productSize: productSize
				}
			});
		}
		
		svc.getAllTag = function() {
			return $http({
				method : 'POST',
				url : 'backend/product-get-all-tag.php'
			});
		}
		
		svc.findTagByPId = function(pid) {
			return $http({
				method : 'POST',
				url : 'backend/product-find-tag-by-pid.php',
				data: {
					pid: pid
				}
			});
		}
				
		svc.findDiscountGroupTagByPid = function(pid) {
			return $http({
				method : 'POST',
				url : 'backend/product-find-discount-group-tag-by-pid.php',
				data: {
					pid: pid
				}
			});
		}	

		svc.findDiscountGroupJoinedByPid = function(pid) {
			return $http({
				method : 'POST',
				url : 'backend/product-find-discount-group-joined-by-pid.php',
				data: {
					pid: pid
				}
			});
		}	
				
		return svc;
	}
})();
