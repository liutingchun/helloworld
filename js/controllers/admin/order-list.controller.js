'use strict';
(function() {
	angular.module('myApp').controller('OrderListController',OrderListController);
	OrderListController.$inject = ['$scope','$state','$stateParams','$translate','DTOptionsBuilder','DTColumnDefBuilder','adminGeneralService','adminOrderService'];
	function OrderListController($scope,$state,$stateParams,$translate,DTOptionsBuilder,DTColumnDefBuilder,adminGeneralService,adminOrderService) {
	
		var ctrl=this;
									
		/*
		** Declaring
		*/	
		ctrl.orderList = {};
		
		ctrl.statusOption = [];
		
		/*
		** Initialization
		*/
		init();
		function init() {			
			adminGeneralService.findSelectionByCategory('orderStatus').then(function(response) {
				ctrl.statusOption = response.data;
			});
		
			var language = {
				"sEmptyTable": $translate.instant('oym.order.history.dt.sEmptyTable'),
				"sInfo": $translate.instant('oym.order.history.dt.sInfo'),
				"sInfoEmpty": $translate.instant('oym.order.history.dt.sInfoEmpty'),
				"sLengthMenu": $translate.instant('oym.order.history.dt.sLengthMenu'),
				"sSearch": $translate.instant('oym.order.history.dt.sSearch'),
				"oPaginate": {
					"sNext": $translate.instant('oym.order.history.dt.sNext'),
					"sPrevious": $translate.instant('oym.order.history.dt.sPrevious')
				}
			}
		
			ctrl.dtOptions = DTOptionsBuilder.newOptions()
				.withOption('responsive', true)
				.withOption('scrollX', 'true')
				.withOption('order', [0, 'desc'])
				.withLanguage(language);
				/*.withButtons([
					'columnsToggle',
					'colvis',
					'copy',
					'print',
					'excel',
					{
						text: 'Some button',
						key: '1',
						action: function (e, dt, node, config) {
							alert('Button activated');
						}
					}
				]);*/
		
			ctrl.dtColumnDefs = [
				{
					"targets": [8],
					"sortable": false,
					"searchable": false
				}
			];

			adminOrderService.findAllNonArchiveOrder().then(function(response) {
				if (response.data.status == 'success') {
					var resultList = response.data.message;
					
					//Post-processing
					for (var index in resultList) {
						resultList[index].oDate = new Date(resultList[index].oDate);
						
						ctrl.orderList[resultList[index].oId] = resultList[index];
					}
				}
				else {
					swal($translate.instant('oym.action.msg.title.warning'),$translate.instant(response.data.message),'warning');
				}
			});

		}
		
		/*
		** Front-end Action Handle
		*/		
		
		ctrl.changeStatus = function(oId) {
			updateOrder(oId);
		}
		
		ctrl.setPaidAmount = function(oId) {			
			swal({
				title: $translate.instant('oym.order.detail.admin.set.paid'),
				type: "warning",
				input: 'number',
				showCancelButton: true,
				confirmButtonClass: 'btn-danger',
				confirmButtonText: $translate.instant('oym.action.msg.btn.confirm'),
				cancelButtonText: $translate.instant('oym.action.msg.btn.cancel')
			}).then(function(response) {
				if (response) {
					ctrl.orderList[oId].oPaid = response;
					updateOrder(oId);
				}
			});
		}
		
		ctrl.archive = function(oId) {			
			swal({
				title: $translate.instant('oym.order.detail.admin.msg.archive.ask'),
				type: "warning",
				showCancelButton: true,
				confirmButtonClass: 'btn-danger',
				confirmButtonText: $translate.instant('oym.action.msg.btn.confirm'),
				cancelButtonText: $translate.instant('oym.action.msg.btn.cancel')
			}).then(function(response) {
				ctrl.orderList[oId].oArchive = 1;
				updateOrder(oId);
			});
		}
		
		ctrl.cancelOrder = function(oId) {
			swal({
				title: $translate.instant('oym.order.detail.admin.msg.cancel.ask'),
				type: "warning",
				showCancelButton: true,
				confirmButtonClass: 'btn-danger',
				confirmButtonText: $translate.instant('oym.action.msg.btn.confirm'),
				cancelButtonText: $translate.instant('oym.action.msg.btn.cancel')
			}).then(function(response) {
				adminOrderService.cancelOrder(oId).then(function(response) {
					if (response.data.status = "success") {
						var msg = "";
						for (var index in response.data.message) {
							var order = response.data.message[index];
							
							msg += "<br>" + order.pid + ": " + order.productColor + " " + order.productPrice + " " + order.productSize + " " + order.productStatus;
						}
						swal($translate.instant('oym.action.msg.title.success'),$translate.instant('oym.order.detail.admin.msg.cancel.success') + " " + msg,'success');
						ctrl.orderList[oId].oStatus = 'cancelled';
					}
				});
			});
		}
		
		ctrl.showReceipt = function(oId) {
			adminOrderService.findReceiptById(oId).then(function(response) {
				if (response.data.status == 'success') {
					swal({
						imageUrl: 'img/receipt/' + oId + '_' + response.data.message[0].rSub + '.' + response.data.message[0].rExtension,
						imageAlt: 'Image not found.',
						animation: false
					});
				}
				else {
					swal($translate.instant('oym.action.msg.title.failure'),$translate.instant('oym.order.detail.admin.msg.no.receipt'),'error');
				}
			});
		}
		
		ctrl.showRemarks = function(remarks) {
			swal(remarks);
		}
		
		/*
		** Utilites
		*/
		
		function updateOrder(oId) {
			adminOrderService.updateOrder(ctrl.orderList[oId]).then(function(response) {
				if (response.data.status = "success") {
					swal($translate.instant('oym.action.msg.title.success'),$translate.instant('oym.order.detail.admin.msg.update.success'),'success');
					if (ctrl.orderList[oId].oArchive == 1) {
						delete ctrl.orderList[oId];
					}
				}
			});
		}
	}
})();