'use strict';
(function() {
	angular.module('myApp').controller('FormUpdateProductController',FormUpdateProductController);
	FormUpdateProductController.$inject = ['$scope', '$state', '$stateParams', '$translate', 'FileUploader', 'adminProductService', 'productService', 'adminGeneralService'];
	function FormUpdateProductController($scope, $state, $stateParams, $translate, FileUploader, adminProductService, productService, adminGeneralService) {
	
		var ctrl=this;
				
		/*	
		** Declaring
		*/
		ctrl.pid = $stateParams.pid;
		
		ctrl.productDetail = null;
		ctrl.productForm = {};		
		ctrl.forceDisplayError = false;		
		ctrl.imageId = null;
		
		ctrl.selectedTag;
		ctrl.currentTagList = [];
		
		ctrl.selectedDiscountGroup = null;
		ctrl.currentDiscountGroupList = [];
		
		ctrl.productImageUrl = [];
				
		ctrl.selection = {
			'brand': [],
			'category': [],
			'gender': [],
			'hide': [],
			'tag': [],
			'stockOperation': [],
			'status': [],
			'discountGroup': []
		};
		
		ctrl.addInventory = {};
		
		ctrl.availableBuyStatus = [];
		ctrl.availableBuyColor = [];
		ctrl.availableBuySize = [];
	
		/*
		** Initialization
		*/
		init();
		function init() {
			adminGeneralService.findSelectionByCategory('productBrand').then(function(response) {
				ctrl.selection.brand = response.data;
			});
			
			adminGeneralService.findSelectionByCategory('productCategory').then(function(response) {
				ctrl.selection.category = response.data;
			});
			
			adminGeneralService.findSelectionByCategory('productGender').then(function(response) {
				ctrl.selection.gender = response.data;
			});
			
			adminGeneralService.findSelectionByCategory('productHide').then(function(response) {
				ctrl.selection.hide = response.data;
			});
			
			adminGeneralService.findSelectionByCategory('productTag').then(function(response) {
				ctrl.selection.tag = response.data;
			});
			
			adminGeneralService.findSelectionByCategory('stockOperationType').then(function(response) {
				ctrl.selection.stockOperation = response.data;
			});
			
			adminGeneralService.findSelectionByCategory('inventoryStatus').then(function(response) {
				ctrl.selection.status = response.data;
			});
		
			adminProductService.findProductByPid(ctrl.pid).then(function(response) {
				ctrl.productDetail = response.data[0];
				
				if (ctrl.productDetail) {
					adminProductService.findInventoryById(ctrl.productDetail.pid).then(function(result) {
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
									price: result.data[index].productPrice,
									quantity: result.data[index].quantity
								});
							}
						}
					});
					
					productService.findTagByPId(ctrl.productDetail.pid).then(function(result) {
						for (var index in result.data) {
							ctrl.currentTagList.push(result.data[index].tName);
						}
					});
					
					productService.findDiscountGroupTagByPid(ctrl.pid).then(function(result) {
						for (var index in result.data) {
							ctrl.currentDiscountGroupList.push(result.data[index].dId);
						}
					});
						
					ctrl.productDetail.lastEdit = new Date(ctrl.productDetail.lastEdit)
					
					ctrl.productForm.pid = ctrl.productDetail.pid;
					ctrl.productForm.productName = ctrl.productDetail.name;
					ctrl.productForm.officalCode = ctrl.productDetail.officalCode;
					ctrl.productForm.category = ctrl.productDetail.category;
					ctrl.productForm.brand = ctrl.productDetail.brand;
					ctrl.productForm.color = ctrl.productDetail.color;
					ctrl.productForm.gender = ctrl.productDetail.sex;
					ctrl.productForm.price = ctrl.productDetail.price;
					ctrl.productForm.fakeprice = ctrl.productDetail.fakeprice;
					ctrl.productForm.description = ctrl.productDetail.description;
					ctrl.productForm.image = ctrl.productDetail.image;
					ctrl.productForm.hide = ctrl.productDetail.hide;
					ctrl.productForm.viewCount = ctrl.productDetail.viewCount;
					
					for (var i = 0; i < ctrl.productDetail.image; i++) {
						ctrl.productImageUrl.push({
							'id': i,
							'src': 'img/product/' + ctrl.productDetail.pid + '/' + i + '.jpg'
						});
					}
				}
				else {
					$state.go('root.admin.product-list');
				}
			});
			
			adminProductService.findAllDiscountGroup().then(function(response) {
				ctrl.selection.discountGroup = response.data;
			});
		}
		
		/*
		** File Uploader
		*/
		ctrl.uploader = new FileUploader({
            url: 'backend/admin-product-upload-image.php'
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
			//$window.alert("Attempt to upload: " + ctrl.productDetail.pid + " " + ctrl.imageId.id);
			
            item.formData.push(
				{pid: ctrl.productDetail.pid},
				{iid: ctrl.imageId.id}
			);
        };
			
		/*ctrl.uploader.onErrorItem = function(item, response, status, headers) {
			$window.alert("Error :" + item + "|" + response + "|" + status + "|" + headers);
		}
			
		ctrl.uploader.onSuccessItem = function(item, response, status, headers) {
			$window.alert("Success :" + item + "|" + response + "|" + status + "|" + headers);
		}*/
		
        ctrl.uploader.onCompleteItem = function(fileItem, response, status, headers) {
			//$window.alert("Complete :" + fileItem + "|" + response + "|" + status + "|" + headers);
			
			if (response.status == 'success') {
				swal($translate.instant('oym.action.msg.title.success'),$translate.instant('oym.action.msg.admin.product.img.success'),'success');
				$state.reload();
			}
			else {
				swal($translate.instant('oym.action.msg.title.failure'),$translate.instant('oym.action.msg.admin.error.unknown'),'error');
			}
        };
		
		/*
		** Front-end Action Handler
		*/
		
		ctrl.submitForm = function($valid) {
			if ($valid) {
				adminProductService.updateProduct(ctrl.productForm).then(function(response) {
					if (response.data.status = 'success') {
						swal($translate.instant('oym.action.msg.title.success'),$translate.instant('oym.action.msg.admin.product.update.success'),'success');
						$state.reload();
					}
					else {
						swal($translate.instant('oym.action.msg.title.failure'),$translate.instant('oym.action.msg.admin.error.unknown'),'error');
					}
				});
			}
			else {
				ctrl.forceDisplayError = true;
				swal($translate.instant('oym.action.msg.title.failure'),$translate.instant('oym.action.msg.admin.product.failutre.missing.info'),'error');
			}
		}
		
		ctrl.addTag = function() {
			adminProductService.addTag(ctrl.productDetail.pid, ctrl.selectedTag).then(function(response) {
				if (response.data.status == 'success') {
					swal($translate.instant('oym.action.msg.title.success'),$translate.instant('oym.action.msg.admin.tag.add.success'),'success');
					$state.reload();
				}
				else {
					swal($translate.instant('oym.action.msg.title.failure'),$translate.instant('oym.action.msg.admin.error.unknown'),'error');
				}
			});
		}
		
		ctrl.removeTag = function() {
			adminProductService.removeTag(ctrl.productDetail.pid, ctrl.selectedTag).then(function(response) {
				if (response.data.status == 'success') {
					swal($translate.instant('oym.action.msg.title.success'),$translate.instant('oym.action.msg.admin.tag.remove.success'),'success');
					$state.reload();
				}
				else {
					swal($translate.instant('oym.action.msg.title.failure'),$translate.instant('oym.action.msg.admin.error.unknown'),'error');
				}
			});
		}
		
		ctrl.addDiscountGroupTag = function() {
			adminProductService.addDiscountGroupTag(ctrl.selectedDiscountGroup.dId, ctrl.productDetail.pid).then(function(response) {
				if (response.data.status == 'success') {
					swal($translate.instant('oym.action.msg.title.success'),$translate.instant('oym.action.msg.admin.discount.group.tag.add.success'),'success');
					$state.reload();
				}
				else {
					swal($translate.instant('oym.action.msg.title.failure'),$translate.instant('oym.action.msg.admin.error.unknown'),'error');
				}
			});
		}
		
		ctrl.removeDiscountGroupTag = function() {
			adminProductService.removeDiscountGroupTag(ctrl.selectedDiscountGroup.dId, ctrl.productDetail.pid).then(function(response) {
				console.log(response.data);
				if (response.data.status == 'success') {
					swal($translate.instant('oym.action.msg.title.success'),$translate.instant('oym.action.msg.admin.discount.group.tag.remove.success'),'success');
					$state.reload();
				}
				else {
					swal($translate.instant('oym.action.msg.title.failure'),$translate.instant('oym.action.msg.admin.error.unknown'),'error');
				}
			});
		}
		
		ctrl.confirmAddInventory = function() {
			adminProductService.addInventory(
				ctrl.productDetail.pid,
				ctrl.addInventory.status,
				ctrl.addInventory.color,
				ctrl.addInventory.size,
				ctrl.addInventory.price,
				ctrl.addInventory.quantity
			).then(function(response) {
				if (response.data.status == 'success') {
					swal($translate.instant('oym.action.msg.title.success'),$translate.instant('oym.action.msg.admin.inventory.add.success'),'success');
					
					if (ctrl.availableBuyStatus.indexOf(response.data.message.productStatus) == -1) {
						ctrl.availableBuyStatus.push(response.data.message.productStatus);
						ctrl.availableBuyColor[response.data.message.productStatus] = [];
						ctrl.availableBuySize[response.data.message.productStatus] = [];
					}
					
					if (ctrl.availableBuyColor[response.data.message.productStatus].indexOf(response.data.message.productColor) == -1) {
						ctrl.availableBuyColor[response.data.message.productStatus].push(response.data.message.productColor);
						ctrl.availableBuySize[response.data.message.productStatus][response.data.message.productColor] = [];
					}
					
					ctrl.availableBuySize[response.data.message.productStatus][response.data.message.productColor].push({
						size: response.data.message.productSize,
						quantity: response.data.message.quantity
					});
					
				}
				else {
					swal($translate.instant('oym.action.msg.title.failure'),$translate.instant(response.data.message),'error');
				}
			});
		}
		
		ctrl.confirmEditInventory = function() {
			adminProductService.editInventory(
				ctrl.productDetail.pid,
				ctrl.selectedBuyStatus,
				ctrl.selectedBuyColor,
				ctrl.selectedBuySize.size,
				ctrl.selectedBuySize.price,
				ctrl.selectedBuySize.quantity
			).then(function(response) {
				if (response.data.status == 'success') {
					swal($translate.instant('oym.action.msg.title.success'),$translate.instant('oym.action.msg.admin.inventory.update.success'),'success');
				}
				else {
					swal($translate.instant('oym.action.msg.title.failure'),$translate.instant('oym.action.msg.admin.error.unknown'),'error');
				}
			});
		}
		
		ctrl.confirmDeleteInventory = function() {
			adminProductService.deleteInventory(
				ctrl.productDetail.pid,
				ctrl.selectedBuyStatus,
				ctrl.selectedBuyColor,
				ctrl.selectedBuySize.size
			).then(function(response) {
				if (response.data.status == 'success') {
					swal($translate.instant('oym.action.msg.title.success'),$translate.instant('oym.action.msg.admin.inventory.delete.success'),'success');
					$state.reload();
				}
				else {
					swal($translate.instant('oym.action.msg.title.failure'),$translate.instant('oym.action.msg.admin.error.unknown'),'error');
				}
			});
		}
	}
})();