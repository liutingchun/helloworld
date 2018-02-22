'use strict';

(function() {
	angular.module('myApp').factory('adminProductService', adminProductService);
	adminProductService.$inject = ['$http'];
	function adminProductService($http) {
		var svc = {};
			
		svc.addProduct = function(productForm) {
			return $http({
				method : 'POST',
				url : 'backend/admin-product-add-product.php',
				data : {
					productForm: productForm
				}
			});
		}		
		
		svc.updateProduct = function(productForm) {
			return $http({
				method : 'POST',
				url : 'backend/admin-product-update-product.php',
				data : {
					productForm: productForm
				}
			});
		}
		
		svc.findProductByPid = function(pid) {
			return $http({
				method : 'POST',
				url : 'backend/admin-product-find-product-by-pid.php',
				data : {
					pid: pid
				}
			});
		}
		
		svc.findAllProduct = function() {
			return $http({
				method : 'POST',
				url : 'backend/admin-product-find-all-product.php'
			});
		}	

		svc.findInventoryById = function(pid) {
			return $http({
				method : 'POST',
				url : 'backend/admin-product-find-inventory-by-pid.php',
				data : {
					pid: pid
				}
			});
		}
		
		svc.addInventory = function(pid,productStatus,productColor,productSize,productPrice,quantity) {
			return $http({
				method : 'POST',
				url : 'backend/admin-product-add-inventory.php',
				data : {
					pid: pid,
					productStatus: productStatus,
					productColor: productColor,
					productSize: productSize,
					productPrice: productPrice,
					quantity: quantity
				}
			});
		}
		
		svc.editInventory = function(pid,productStatus,productColor,productSize,productPrice,quantity) {
			return $http({
				method : 'POST',
				url : 'backend/admin-product-edit-inventory.php',
				data : {
					pid: pid,
					productStatus: productStatus,
					productColor: productColor,
					productSize: productSize,
					productPrice: productPrice,
					quantity: quantity
				}
			});
		}
		
		svc.deleteInventory = function(pid,productStatus,productColor,productSize) {
			return $http({
				method : 'POST',
				url : 'backend/admin-product-delete-inventory.php',
				data : {
					pid: pid,
					productStatus: productStatus,
					productColor: productColor,
					productSize: productSize
				}
			});
		}
		
		svc.addTag = function(pid, value) {
			return $http({
				method : 'POST',
				url : 'backend/admin-product-add-tag.php',
				data : {
					pid: pid,
					value: value
				}
			});
		}
		
		svc.removeTag = function(pid, value) {
			return $http({
				method : 'POST',
				url : 'backend/admin-product-remove-tag.php',
				data : {
					pid: pid,
					value: value
				}
			});
		}
		
		svc.addDiscountGroupTag = function(dId, pid) {
			return $http({
				method : 'POST',
				url : 'backend/admin-product-add-discount-group-tag.php',
				data : {
					dId: dId,
					pid: pid
				}
			});
		}
		
		svc.removeDiscountGroupTag = function(dId, pid) {
			return $http({
				method : 'POST',
				url : 'backend/admin-product-remove-discount-group-tag.php',
				data : {
					dId: dId,
					pid: pid
				}
			});
		}
		
		svc.addDiscountGroup = function(discountGroupForm) {
			return $http({
				method : 'POST',
				url : 'backend/admin-product-add-discount-group.php',
				data : {
					discountGroupForm: discountGroupForm
				}
			});
		}
		
		svc.updateDiscountGroup = function(entity) {
			return $http({
				method : 'POST',
				url : 'backend/admin-product-update-discount-group.php',
				data : {
					entity: entity
				}
			});
		}
		
		svc.findAllDiscountGroup = function() {
			return $http({
				method : 'POST',
				url : 'backend/admin-product-find-all-discount-group.php'
			});
		}
				
		return svc;
	}
})();
